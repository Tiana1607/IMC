<?php

namespace App\Models;

use CodeIgniter\Model;

class Activity extends Model
{
    protected $table = 'activities';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'name',
        'description',
        'calories_per_hour',
        'intensity',
        'equipment_needed',
        'difficulty_level',
        'is_active',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get activities by objective
     */
    public function getByObjective($objective)
    {
        return $this->join('activity_objectives', 'activities.id = activity_objectives.activity_id')
                    ->where('activity_objectives.objective', $objective)
                    ->where('activities.is_active', 1)
                    ->findAll();
    }

    /**
     * Get activity with all objectives
     */
    public function getWithObjectives($activity_id)
    {
        $activity = $this->find($activity_id);
        if ($activity) {
            $objectiveModel = new ActivityObjective();
            $activity['objectives'] = $objectiveModel->where('activity_id', $activity_id)
                                                      ->findAll();
        }
        return $activity;
    }

    /**
     * Add objective to activity
     */
    public function addObjective($activity_id, $objective)
    {
        $objectiveModel = new ActivityObjective();
        return $objectiveModel->insert([
            'activity_id' => $activity_id,
            'objective' => $objective,
        ]);
    }

    /**
     * Remove objective from activity
     */
    public function removeObjective($activity_id, $objective)
    {
        $objectiveModel = new ActivityObjective();
        return $objectiveModel->where('activity_id', $activity_id)
                             ->where('objective', $objective)
                             ->delete();
    }

    /**
     * Get all active activities for recommendations
     */
    public function getActiveActivities()
    {
        return $this->where('is_active', 1)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    /**
     * Get activities by intensity level
     */
    public function getByIntensity($intensity)
    {
        return $this->where('intensity', $intensity)
                    ->where('is_active', 1)
                    ->findAll();
    }
}
