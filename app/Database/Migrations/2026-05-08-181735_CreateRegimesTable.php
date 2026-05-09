<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRegimesTable extends Migration
{
    public function up()
    {
        $sql = "CREATE TABLE regimes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            description TEXT,
            calorie_target INT NOT NULL,
            price_per_week DECIMAL(20, 2) NOT NULL,
            duration_weeks INT NOT NULL,
            weight_change_percent DECIMAL(5, 2),
            meat_percent DECIMAL(5, 2) DEFAULT 0,
            fish_percent DECIMAL(5, 2) DEFAULT 0,
            poultry_percent DECIMAL(5, 2) DEFAULT 0,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

        $this->db->query($sql);
    }

    public function down()
    {
        $this->forge->dropTable('regimes', true);
    }
}
