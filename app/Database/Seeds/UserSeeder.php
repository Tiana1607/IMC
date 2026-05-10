<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $users = [
            [
                'email' => 'admin@gmail.com',
                'password_hash' => '$2y$10$qPKSGNHqdvgaktg15LaNzuABodUnWVMXt3K0/kZJLzF5dOPpCIk8O',
                'name' => 'admin',
                'gender' => 'M',
                'height_cm' => 158.00,
                'weight_kg' => 52.00,
                'age' => 19,
                'objective' => 'imc_ideal',
                'imc_value' => 20.83,
                'imc_category' => 'Normal',
                'wallet_balance' => 0.00,
                'is_gold' => 0,
                'is_admin' => 1,
                'is_active' => 1,
                'last_login' => '2026-05-10 07:20:18',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'email' => 'rova@gmail.com',
                'password_hash' => '$2y$10$Gkuz37RtEd6DTQKLcg/8nusiEm05BDdUSXzGe6MXdHfxA2NUPiBY6',
                'name' => 'rova',
                'gender' => 'F',
                'height_cm' => 163.00,
                'weight_kg' => 52.00,
                'age' => 19,
                'objective' => 'imc_ideal',
                'imc_value' => 19.57,
                'imc_category' => 'Normal',
                'wallet_balance' => 0.00,
                'is_gold' => 0,
                'is_admin' => 0,
                'is_active' => 1,
                'last_login' => '2026-05-09 10:38:23',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'email' => 'jeremie@gmail.com',
                'password_hash' => '$2y$10$vLL56xljlTACEjOjt5kwG.GbZNB4RQJG/4widNGNE1ReTYzspETkW',
                'name' => 'jeremie',
                'gender' => 'M',
                'height_cm' => 170.00,
                'weight_kg' => 48.00,
                'age' => 23,
                'objective' => 'augmenter_poids',
                'imc_value' => 16.61,
                'imc_category' => 'Sous-poids',
                'wallet_balance' => 0.00,
                'is_gold' => 0,
                'is_admin' => 0,
                'is_active' => 1,
                'last_login' => '2026-05-09 15:50:06',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($users as $user) {
            $existing = $this->db->table('users')->where('email', $user['email'])->get()->getRowArray();
            if ($existing) {
                $this->db->table('users')->where('email', $user['email'])->update($user);
            } else {
                $this->db->table('users')->insert($user);
            }
        }
    }
}
