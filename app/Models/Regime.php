<?php

namespace App\Models;

use CodeIgniter\Model;

class Regime extends Model
{
    protected $table = 'regimes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'name',
        'description',
        'calorie_target',
        'price_per_week',
        'duration_weeks',
        'weight_change_percent',
        'meat_percent',
        'fish_percent',
        'poultry_percent',
        'is_active',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * récupère les régimes par objectif
     */
    public function getByObjective($objective)
    {
        return $this->join('regime_objectives', 'regimes.id = regime_objectives.regime_id')
                    ->where('regime_objectives.objective', $objective)
                    ->where('regimes.is_active', 1)
                    ->findAll();
    }

    /**
     * get regimes avec tous les objectifs
     */
    public function getWithObjectives($regime_id)
    {
        $regime = $this->find($regime_id);
        if ($regime) {
            $objectiveModel = new RegimeObjective();
            $regime['objectives'] = $objectiveModel->where('regime_id', $regime_id)
                                                    ->findAll();
        }
        return $regime;
    }

    /**
     * ajoute l'objectif au regime
     */
    public function addObjective($regime_id, $objective)
    {
        $objectiveModel = new RegimeObjective();
        return $objectiveModel->insert([
            'regime_id' => $regime_id,
            'objective' => $objective,
        ]);
    }

    /**
     * supprime les objectif du regime
     */
    public function removeObjective($regime_id, $objective)
    {
        $objectiveModel = new RegimeObjective();
        return $objectiveModel->where('regime_id', $regime_id)
                             ->where('objective', $objective)
                             ->delete();
    }

    /**
     * prends tous les regimes actif depuis la recommendations
     */
    public function getActiveRegimes()
    {
        return $this->where('is_active', 1)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }
}
