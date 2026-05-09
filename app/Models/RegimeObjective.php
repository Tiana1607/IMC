<?php

namespace App\Models;

use CodeIgniter\Model;

class RegimeObjective extends Model
{
    protected $table = 'regime_objectives';
    protected $allowedFields = ['regime_id', 'objective'];
    protected $primaryKey = ['regime_id', 'objective'];
    protected $useAutoIncrement = false;
    protected $timestamps = false;

    /**
     * Get all objectives for a regime
     */
    public function getObjectivesByRegime($regime_id)
    {
        return $this->where('regime_id', $regime_id)
                    ->findAll();
    }

    /**
     * Get all regimes for a given objective
     */
    public function getRegimesByObjective($objective)
    {
        return $this->where('objective', $objective)
                    ->findAll();
    }

    /**
     * Check if regime supports a given objective
     */
    public function hasObjective($regime_id, $objective)
    {
        return $this->where('regime_id', $regime_id)
                    ->where('objective', $objective)
                    ->first() !== null;
    }

    /**
     * Add objective to regime
     */
    public function addObjective($regime_id, $objective)
    {
        return $this->insert([
            'regime_id' => $regime_id,
            'objective' => $objective,
        ]);
    }

    /**
     * Remove objective from regime
     */
    public function removeObjective($regime_id, $objective)
    {
        return $this->where('regime_id', $regime_id)
                    ->where('objective', $objective)
                    ->delete();
    }
}
