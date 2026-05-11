<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PromoCodeSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        $data = [
            ['code' => 'WELCOME5', 'amount' => 5.00, 'is_used' => 0, 'used_by_user_id' => null, 'used_at' => null, 'description' => 'Welcome bonus', 'expires_at' => null, 'created_at' => $now],
            ['code' => 'VITAL-10', 'amount' => 10.00, 'is_used' => 0, 'used_by_user_id' => null, 'used_at' => null, 'description' => 'Promo 10€', 'expires_at' => null, 'created_at' => $now],
            ['code' => 'GIFT50', 'amount' => 50.00, 'is_used' => 0, 'used_by_user_id' => null, 'used_at' => null, 'description' => 'Grand cadeau', 'expires_at' => null, 'created_at' => $now],
            ['code' => 'USED-TEST', 'amount' => 20.00, 'is_used' => 1, 'used_by_user_id' => 2, 'used_at' => date('Y-m-d H:i:s', strtotime('-1 days')), 'description' => 'Used by test user', 'expires_at' => null, 'created_at' => $now],
            ['code' => 'START-15', 'amount' => 15.00, 'is_used' => 0, 'used_by_user_id' => null, 'used_at' => null, 'description' => 'Bonus demarrage', 'expires_at' => null, 'created_at' => $now],
            ['code' => 'MOVE-05', 'amount' => 5.00, 'is_used' => 0, 'used_by_user_id' => null, 'used_at' => null, 'description' => 'Code activite', 'expires_at' => null, 'created_at' => $now],
            ['code' => 'FIT-20', 'amount' => 20.00, 'is_used' => 0, 'used_by_user_id' => null, 'used_at' => null, 'description' => 'Code fitness', 'expires_at' => null, 'created_at' => $now],
            ['code' => 'HEALTH-25', 'amount' => 25.00, 'is_used' => 0, 'used_by_user_id' => null, 'used_at' => null, 'description' => 'Code sante', 'expires_at' => null, 'created_at' => $now],
            ['code' => 'BOOST-30', 'amount' => 30.00, 'is_used' => 0, 'used_by_user_id' => null, 'used_at' => null, 'description' => 'Boost portefeuille', 'expires_at' => null, 'created_at' => $now],
            ['code' => 'CARDIO-08', 'amount' => 8.00, 'is_used' => 0, 'used_by_user_id' => null, 'used_at' => null, 'description' => 'Code cardio', 'expires_at' => null, 'created_at' => $now],
            ['code' => 'MUSCU-12', 'amount' => 12.00, 'is_used' => 0, 'used_by_user_id' => null, 'used_at' => null, 'description' => 'Code musculation', 'expires_at' => null, 'created_at' => $now],
            ['code' => 'ZEN-07', 'amount' => 7.00, 'is_used' => 0, 'used_by_user_id' => null, 'used_at' => null, 'description' => 'Code bien-etre', 'expires_at' => null, 'created_at' => $now],
            ['code' => 'PLUS-18', 'amount' => 18.00, 'is_used' => 0, 'used_by_user_id' => null, 'used_at' => null, 'description' => 'Recharge plus', 'expires_at' => null, 'created_at' => $now],
            ['code' => 'POWER-22', 'amount' => 22.00, 'is_used' => 0, 'used_by_user_id' => null, 'used_at' => null, 'description' => 'Code puissance', 'expires_at' => null, 'created_at' => $now],
            ['code' => 'SPRING-11', 'amount' => 11.00, 'is_used' => 0, 'used_by_user_id' => null, 'used_at' => null, 'description' => 'Offre saisonniere', 'expires_at' => null, 'created_at' => $now],
        ];

        foreach ($data as $row) {
            $existing = $this->db->table('promo_codes')->where('code', $row['code'])->get()->getRowArray();
            if ($existing) {
                $this->db->table('promo_codes')->where('code', $row['code'])->update($row);
            } else {
                $this->db->table('promo_codes')->insert($row);
            }
        }
    }
}
