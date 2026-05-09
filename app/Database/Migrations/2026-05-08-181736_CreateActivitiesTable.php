<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActivitiesTable extends Migration
{
    public function up()
    {
        $sql = "CREATE TABLE activities (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            description TEXT,
            calories_per_hour INT NOT NULL,
            intensity VARCHAR(20) NOT NULL,
            equipment_needed TEXT,
            difficulty_level VARCHAR(20),
            is_active TINYINT(1) DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )";

        $this->db->query($sql);
    }

    public function down()
    {
        $this->forge->dropTable('activities', true);
    }
}
