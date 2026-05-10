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
