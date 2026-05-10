<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RegimeSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $regimes = [
            [
                'name' => 'Régime Léger Cardio',
                'description' => 'Perte de poids',
                'calorie_target' => 1500,
                'price_per_week' => 12.99,
                'duration_weeks' => 4,
                'weight_change_percent' => -2.5,
                'meat_percent' => 15,
                'fish_percent' => 30,
                'poultry_percent' => 35,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Régime Équilibré',
                'description' => 'Maintenance',
                'calorie_target' => 2000,
                'price_per_week' => 14.99,
                'duration_weeks' => 4,
                'weight_change_percent' => 0,
                'meat_percent' => 25,
                'fish_percent' => 20,
                'poultry_percent' => 25,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Régime Protéiné Muscu',
                'description' => 'Prise masse',
                'calorie_target' => 2800,
                'price_per_week' => 16.99,
                'duration_weeks' => 4,
                'weight_change_percent' => 3,
                'meat_percent' => 40,
                'fish_percent' => 25,
                'poultry_percent' => 20,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Régime Méditerranéen',
                'description' => 'Sain',
                'calorie_target' => 2200,
                'price_per_week' => 18.99,
                'duration_weeks' => 6,
                'weight_change_percent' => 0.5,
                'meat_percent' => 20,
                'fish_percent' => 35,
                'poultry_percent' => 15,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Détox Printanier',
                'description' => 'Détox',
                'calorie_target' => 1800,
                'price_per_week' => 13.99,
                'duration_weeks' => 3,
                'weight_change_percent' => -1.5,
                'meat_percent' => 10,
                'fish_percent' => 15,
                'poultry_percent' => 20,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($regimes as $regime) {
            $existing = $this->db->table('regimes')->where('name', $regime['name'])->get()->getRowArray();
            if ($existing) {
                $this->db->table('regimes')->where('name', $regime['name'])->update($regime);
            } else {
                $this->db->table('regimes')->insert($regime);
            }
        }
    }
}
