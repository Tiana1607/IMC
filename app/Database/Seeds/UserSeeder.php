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
                'password_hash' => '$2y$10$4f5grljIYI7uX9WDcNQa7eHGz/V1geYAsIdjICFgdHVosd8kzAfZm',
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
                'password_hash' => '$2y$10$chENiDiRk5W6R256rdA.j.Dja1As.dC.s.UDwd.DwQO2S9gnzGcxm',
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
                'password_hash' => '$2y$10$u0fPHbKSKhI6HPrcTRJXmuJ3QGB9vMDk13hQGqlFnkefGAP6CTZ26',
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
            [
                'email' => 'sarah@gmail.com',
                'password_hash' => '$2y$10$.j5UiT0UHBfa.Fk3qMhO7ORvtyJQLmyHngBWBP8i5bjdxe7xhsGUC',
                'name' => 'sarah',
                'gender' => 'F',
                'height_cm' => 168.00,
                'weight_kg' => 74.00,
                'age' => 27,
                'objective' => 'reduire_poids',
                'imc_value' => 26.22,
                'imc_category' => 'Surpoids',
                'wallet_balance' => 0.00,
                'is_gold' => 0,
                'is_admin' => 0,
                'is_active' => 1,
                'last_login' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'email' => 'mine@gmail.com',
                'password_hash' => '$2y$10$gSu4v/1m3.FyfjCncc4F4OsXO3C4mmlAqwDsS4rz7hDqHVMkiK5h6',
                'name' => 'mine',
                'gender' => 'M',
                'height_cm' => 182.00,
                'weight_kg' => 66.00,
                'age' => 25,
                'objective' => 'augmenter_poids',
                'imc_value' => 19.92,
                'imc_category' => 'Normal',
                'wallet_balance' => 0.00,
                'is_gold' => 0,
                'is_admin' => 0,
                'is_active' => 1,
                'last_login' => null,
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
