<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class WalletSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['user_id' => 1, 'balance' => 0.00, 'created_at' => date('Y-m-d H:i:s')],
            ['user_id' => 2, 'balance' => 100.00, 'created_at' => date('Y-m-d H:i:s')],
            ['user_id' => 3, 'balance' => 10.00, 'created_at' => date('Y-m-d H:i:s')],
        ];

        foreach ($data as $row) {
            $exists = $this->db->table('wallets')->where('user_id', $row['user_id'])->get()->getRowArray();
            if ($exists) {
                $this->db->table('wallets')->where('user_id', $row['user_id'])->update(['balance' => $row['balance']]);
            } else {
                $this->db->table('wallets')->insert($row);
            }
        }
    }
}
