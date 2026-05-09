<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityObjective extends Model
{
    protected $table = 'activity_objectives';
    protected $allowedFields = ['activity_id', 'objective'];
    protected $primaryKey = ['activity_id', 'objective'];
    protected $useAutoIncrement = false;
    protected $timestamps = false;

    /**
     * Get all objectives for an activity
     */
    public function getObjectivesByActivity($activity_id)
    {
        return $this->where('activity_id', $activity_id)
                    ->findAll();
    }

    /**
     * Get all activities for a given objective
     */
    public function getActivitiesByObjective($objective)
    {
        return $this->where('objective', $objective)
                    ->findAll();
    }

    /**
     * Check if activity supports a given objective
     */
    public function hasObjective($activity_id, $objective)
    {
        return $this->where('activity_id', $activity_id)
                    ->where('objective', $objective)
                    ->first() !== null;
    }

    /**
     * Add objective to activity
     */
    public function addObjective($activity_id, $objective)
    {
        return $this->insert([
            'activity_id' => $activity_id,
            'objective' => $objective,
        ]);
    }

    /**
     * Remove objective from activity
     */
    public function removeObjective($activity_id, $objective)
    {
        return $this->where('activity_id', $activity_id)
                    ->where('objective', $objective)
                    ->delete();
    }
}
