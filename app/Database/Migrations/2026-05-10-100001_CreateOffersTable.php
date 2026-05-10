<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOffersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'label' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'discount_percent' => [
                'type' => 'INT',
                'constraint' => 3,
                'default' => 0,
            ],
            'description' => [
                'type' => 'TEXT',
            ],
            'cta_text' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
                'on_update' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('offers');

        $data = [
            [
                'name' => 'Pass Gold',
                'label' => 'Offre limitée',
                'discount_percent' => 15,
                'description' => 'Bénéficiez d\'une remise exclusive de 15% sur tous les régimes personnalisés.',
                'cta_text' => 'Être un membre Gold',
                'is_active' => 1,
            ],
        ];

        $this->db->table('offers')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('offers');
    }
}
