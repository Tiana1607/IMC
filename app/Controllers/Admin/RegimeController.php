<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Regime;
use App\Models\RegimeObjective;
use CodeIgniter\HTTP\RedirectResponse;

class RegimeController extends BaseController
{
    private const INDEX_ROUTE = '/admin/regimes';
    private const MSG_NOT_FOUND = 'Régime introuvable.';
    private const MSG_CREATE_SUCCESS = 'Régime créé avec succès.';
    private const MSG_UPDATE_SUCCESS = 'Régime mis à jour avec succès.';
    private const MSG_DELETE_SUCCESS = 'Régime supprimé avec succès.';
    private const MSG_CREATE_ERROR = 'Impossible de créer le régime.';
    private const MSG_UPDATE_ERROR = 'Impossible de mettre à jour le régime.';
    private const MSG_DELETE_ERROR = 'Impossible de supprimer le régime.';
    private const MSG_PERCENTAGE_ERROR = 'La somme des pourcentages viande, poisson et volaille doit être égale à 100%.';
    private const RULE_PRICE = 'required|decimal|greater_than[0]';
    private const RULE_PERCENT = 'required|decimal|greater_than_equal_to[0]';
    private const ALLOWED_OBJECTIVES = ['loss', 'gain', 'ideal'];

    public function index()
    {
        $regimeModel = new Regime();

        return view('admin/regime/index', [
            'regimes' => $regimeModel->orderBy('name', 'ASC')->findAll(),
            'regime' => null,
            'objectives' => [],
        ]);
    }

    public function create()
    {
        return redirect()->to(self::INDEX_ROUTE);
    }

    public function store()
    {
        return $this->persistRegime(null);
    }

    public function edit(int $id)
    {
        $regimeModel = new Regime();
        $regime = $regimeModel->find($id);
        $response = redirect()->to(self::INDEX_ROUTE);
        $db = db_connect();

        if ($regime === null) {
            $response = $response->with('error', self::MSG_NOT_FOUND);
        } else {
            $objectiveModel = new RegimeObjective();
            $objectives = [];

            if ($db->tableExists('regime_objectives')) {
                $objectives = array_column($objectiveModel->getObjectivesByRegime($id), 'objective');
            }

            $response = view('admin/regime/index', [
                'regimes' => $regimeModel->orderBy('name', 'ASC')->findAll(),
                'regime' => $regime,
                'objectives' => $objectives,
            ]);
        }

        return $response;
    }

    public function update(int $id)
    {
        return $this->persistRegime($id);
    }

    public function delete(int $id)
    {
        return $this->deleteRegime($id);
    }

    private function persistRegime(?int $id): RedirectResponse|string
    {
        $regimeModel = new Regime();
        $preflightError = $this->buildRegimePreflightError($id, $regimeModel);

        if ($preflightError !== null) {
            return $preflightError;
        }

        $payload = $this->extractRegimePayload();
        $db = db_connect();
        $db->transStart();

        $savedId = $this->saveRegimeRecord($regimeModel, $id, $payload);

        if ($savedId !== null) {
            $this->syncObjectives($savedId);
        }

        $db->transComplete();

        if ($savedId !== null && $db->transStatus() !== false) {
            $successMessage = $id === null ? self::MSG_CREATE_SUCCESS : self::MSG_UPDATE_SUCCESS;

            return redirect()->to(self::INDEX_ROUTE)->with('success', $successMessage);
        }

        $errorMessage = $id === null ? self::MSG_CREATE_ERROR : self::MSG_UPDATE_ERROR;

        return redirect()->back()->withInput()->with('error', $errorMessage);
    }

    private function buildRegimePreflightError(?int $id, Regime $regimeModel): ?RedirectResponse
    {
        $response = null;

        if (! $this->validate($this->rules())) {
            $response = redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        } elseif ($id !== null && $regimeModel->find($id) === null) {
            $response = redirect()->to(self::INDEX_ROUTE)->with('error', self::MSG_NOT_FOUND);
        } else {
            $payload = $this->extractRegimePayload();
            $percentageError = $this->validatePercentages($payload);

            if ($percentageError !== null) {
                $response = redirect()->back()->withInput()->with('error', $percentageError);
            }
        }

        return $response;
    }

    private function saveRegimeRecord(Regime $regimeModel, ?int $id, array $payload): ?int
    {
        if ($id === null) {
            $savedId = $regimeModel->insert($payload, true);

            return $savedId !== false ? (int) $savedId : null;
        }

        if ($regimeModel->update($id, $payload) === false) {
            return null;
        }

        return $id;
    }

    private function deleteRegime(int $id): RedirectResponse
    {
        $response = redirect()->to(self::INDEX_ROUTE);
        $regimeModel = new Regime();
        $regime = $regimeModel->find($id);

        if ($regime === null) {
            $response = $response->with('error', self::MSG_NOT_FOUND);
        } else {
            if ($regimeModel->delete($id) === false) {
                $response = $response->with('error', self::MSG_DELETE_ERROR);
            } else {
                $response = $response->with('success', self::MSG_DELETE_SUCCESS);
            }
        }

        return $response;
    }

    private function rules(): array
    {
        return [
            'name' => 'required|min_length[3]|max_length[100]',
            'description' => 'permit_empty|max_length[65535]',
            'calorie_target' => 'required|integer|greater_than[0]',
            'price_per_week' => self::RULE_PRICE,
            'duration_weeks' => 'required|integer|greater_than[0]',
            'weight_change_percent' => 'permit_empty|decimal',
            'meat_percent' => self::RULE_PERCENT,
            'fish_percent' => self::RULE_PERCENT,
            'poultry_percent' => self::RULE_PERCENT,
            'is_active' => 'required|in_list[0,1]',
        ];
    }

    private function extractRegimePayload(): array
    {
        return [
            'name' => trim((string) $this->request->getPost('name')),
            'description' => trim((string) $this->request->getPost('description')) ?: null,
            'calorie_target' => (int) $this->request->getPost('calorie_target'),
            'price_per_week' => (float) $this->request->getPost('price_per_week'),
            'duration_weeks' => (int) $this->request->getPost('duration_weeks'),
            'weight_change_percent' => $this->normalizeNullableFloat($this->request->getPost('weight_change_percent')),
            'meat_percent' => (float) $this->request->getPost('meat_percent'),
            'fish_percent' => (float) $this->request->getPost('fish_percent'),
            'poultry_percent' => (float) $this->request->getPost('poultry_percent'),
            'is_active' => (int) $this->request->getPost('is_active'),
        ];
    }

    private function validatePercentages(array $payload): ?string
    {
        $message = null;
        $total = round(
            (float) $payload['meat_percent'] + (float) $payload['fish_percent'] + (float) $payload['poultry_percent'],
            2
        );

        if ($total !== 100.00) {
            $message = self::MSG_PERCENTAGE_ERROR;
        }

        return $message;
    }

    private function syncObjectives(int $regimeId): void
    {
        $db = db_connect();

        if (! $db->tableExists('regime_objectives')) {
            return;
        }

        $objectiveModel = new RegimeObjective();
        $objectives = $this->extractObjectives();

        $objectiveModel->where('regime_id', $regimeId)->delete();

        foreach ($objectives as $objective) {
            $objectiveModel->insert([
                'regime_id' => $regimeId,
                'objective' => $objective,
            ]);
        }
    }

    private function extractObjectives(): array
    {
        $postedObjectives = $this->request->getPost('objectives');
        $postedObjectives = is_array($postedObjectives) ? $postedObjectives : [$postedObjectives];

        return array_values(array_unique(array_filter($postedObjectives, static function ($objective) {
            return is_string($objective) && in_array($objective, self::ALLOWED_OBJECTIVES, true);
        })));
    }

    private function normalizeNullableFloat(mixed $value): ?float
    {
        $normalizedValue = null;

        if ($value !== null && $value !== '') {
            $normalizedValue = (float) $value;
        }

        return $normalizedValue;
    }
}
