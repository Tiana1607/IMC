<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OfferSeeder extends Seeder
{
    public function run()
    {
        $offer = [
            'name' => 'Pass Gold',
            'label' => 'Offre limitée',
            'discount_percent' => 15,
            'description' => "Bénéficiez d'une remise exclusive de 15% sur tous les régimes personnalisés.",
            'cta_text' => 'Être un membre Gold',
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $existing = $this->db->table('offers')->where('name', $offer['name'])->get()->getRowArray();
        if ($existing) {
            $this->db->table('offers')->where('name', $offer['name'])->update($offer);
        } else {
            $this->db->table('offers')->insert($offer);
        }
    }
}
