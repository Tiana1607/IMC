<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Activity;
use App\Models\ActivityObjective;
use CodeIgniter\HTTP\RedirectResponse;

class ActivityController extends BaseController
{
    private const INDEX_ROUTE = '/admin/activities';
    private const MSG_NOT_FOUND = 'Activité introuvable.';
    private const MSG_CREATE_SUCCESS = 'Activité créée avec succès.';
    private const MSG_UPDATE_SUCCESS = 'Activité mise à jour avec succès.';
    private const MSG_DELETE_SUCCESS = 'Activité supprimée avec succès.';
    private const MSG_CREATE_ERROR = 'Impossible de créer l\'activité.';
    private const MSG_UPDATE_ERROR = 'Impossible de mettre à jour l\'activité.';
    private const MSG_DELETE_ERROR = 'Impossible de supprimer l\'activité.';
    private const ALLOWED_OBJECTIVES = ['loss', 'gain', 'ideal'];

    public function index()
    {
        $activityModel = new Activity();

        return view('admin/activity/index', [
            'activities' => $activityModel->orderBy('name', 'ASC')->findAll(),
            'activity' => null,
            'objectives' => [],
        ]);
    }

    public function create()
    {
        return redirect()->to(self::INDEX_ROUTE);
    }

    public function store()
    {
        return $this->persistActivity(null);
    }

    public function edit(int $id)
    {
        $activityModel = new Activity();
        $activity = $activityModel->find($id);
        $response = redirect()->to(self::INDEX_ROUTE);
        $db = db_connect();

        if ($activity === null) {
            $response = $response->with('error', self::MSG_NOT_FOUND);
        } else {
            $objectiveModel = new ActivityObjective();
            $objectives = [];

            if ($db->tableExists('activity_objectives')) {
                $objectives = array_column($objectiveModel->getObjectivesByActivity($id), 'objective');
            }

            $response = view('admin/activity/index', [
                'activities' => $activityModel->orderBy('name', 'ASC')->findAll(),
                'activity' => $activity,
                'objectives' => $objectives,
            ]);
        }

        return $response;
    }

    public function update(int $id)
    {
        return $this->persistActivity($id);
    }

    public function delete(int $id)
    {
        return $this->deleteActivity($id);
    }

    private function persistActivity(?int $id): RedirectResponse|string
    {
        $activityModel = new Activity();
        $preflightError = $this->buildActivityPreflightError($id, $activityModel);

        if ($preflightError !== null) {
            return $preflightError;
        }

        $payload = $this->extractActivityPayload();
        $db = db_connect();
        $db->transStart();

        $savedId = $this->saveActivityRecord($activityModel, $id, $payload);

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

    private function buildActivityPreflightError(?int $id, Activity $activityModel): ?RedirectResponse
    {
        $response = null;

        if (! $this->validate($this->rules())) {
            $response = redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        } elseif ($id !== null && $activityModel->find($id) === null) {
            $response = redirect()->to(self::INDEX_ROUTE)->with('error', self::MSG_NOT_FOUND);
        }

        return $response;
    }

    private function saveActivityRecord(Activity $activityModel, ?int $id, array $payload): ?int
    {
        if ($id === null) {
            $savedId = $activityModel->insert($payload, true);

            return $savedId !== false ? (int) $savedId : null;
        }

        if ($activityModel->update($id, $payload) === false) {
            return null;
        }

        return $id;
    }

    private function deleteActivity(int $id): RedirectResponse
    {
        $response = redirect()->to(self::INDEX_ROUTE);
        $activityModel = new Activity();
        $activity = $activityModel->find($id);

        if ($activity === null) {
            $response = $response->with('error', self::MSG_NOT_FOUND);
        } else {
            if ($activityModel->delete($id) === false) {
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
            'calories_per_hour' => 'required|integer|greater_than[0]',
            'intensity' => 'required|in_list[low,medium,high]',
            'equipment_needed' => 'permit_empty|max_length[65535]',
            'difficulty_level' => 'permit_empty|in_list[beginner,intermediate,advanced]',
            'is_active' => 'required|in_list[0,1]',
        ];
    }

    private function extractActivityPayload(): array
    {
        return [
            'name' => trim((string) $this->request->getPost('name')),
            'description' => trim((string) $this->request->getPost('description')) ?: null,
            'calories_per_hour' => (int) $this->request->getPost('calories_per_hour'),
            'intensity' => trim((string) $this->request->getPost('intensity')),
            'equipment_needed' => trim((string) $this->request->getPost('equipment_needed')) ?: null,
            'difficulty_level' => trim((string) $this->request->getPost('difficulty_level')) ?: null,
            'is_active' => (int) $this->request->getPost('is_active'),
        ];
    }

    private function syncObjectives(int $activityId): void
    {
        $db = db_connect();

        if (! $db->tableExists('activity_objectives')) {
            return;
        }

        $objectiveModel = new ActivityObjective();
        $objectives = $this->extractObjectives();

        $objectiveModel->where('activity_id', $activityId)->delete();

        foreach ($objectives as $objective) {
            $objectiveModel->insert([
                'activity_id' => $activityId,
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
}
