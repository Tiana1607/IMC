<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserRegimesTable extends Migration
{
    public function up()
    {
        $sql = "CREATE TABLE user_regimes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            regime_id INT NOT NULL,
            price_paid DECIMAL(20, 2) NOT NULL,
            purchased_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            starts_at TIMESTAMP NULL,
            ends_at TIMESTAMP NULL,
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY unique_purchase (user_id, regime_id, purchased_at)
        )";

        $this->db->query($sql);
    }

    public function down()
    {
        $this->forge->dropTable('user_regimes', true);
    }
}
