<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $activities = [
            [
                'name' => 'Marche Rapide',
                'description' => 'Marche',
                'calories_per_hour' => 300,
                'intensity' => 'low',
                'equipment_needed' => 'Chaussures',
                'difficulty_level' => 'beginner',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Jogging',
                'description' => 'Course',
                'calories_per_hour' => 600,
                'intensity' => 'medium',
                'equipment_needed' => 'Running shoes',
                'difficulty_level' => 'intermediate',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Musculation',
                'description' => 'Poids',
                'calories_per_hour' => 400,
                'intensity' => 'high',
                'equipment_needed' => 'Haltères',
                'difficulty_level' => 'intermediate',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Yoga',
                'description' => 'Relax',
                'calories_per_hour' => 150,
                'intensity' => 'low',
                'equipment_needed' => 'Tapis',
                'difficulty_level' => 'beginner',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Natation',
                'description' => 'Piscine',
                'calories_per_hour' => 500,
                'intensity' => 'high',
                'equipment_needed' => 'Maillot',
                'difficulty_level' => 'intermediate',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($activities as $activity) {
            $existing = $this->db->table('activities')->where('name', $activity['name'])->get()->getRowArray();
            if ($existing) {
                $this->db->table('activities')->where('name', $activity['name'])->update($activity);
            } else {
                $this->db->table('activities')->insert($activity);
            }
        }
    }
}
