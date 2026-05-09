<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateActivityObjectivesTable extends Migration
{
    public function up()
    {
        $sql = "CREATE TABLE activity_objectives (
            activity_id INT NOT NULL,
            objective VARCHAR(50) NOT NULL,
            PRIMARY KEY (activity_id, objective),
            FOREIGN KEY (activity_id) REFERENCES activities(id) ON DELETE CASCADE ON UPDATE CASCADE
        )";

        $this->db->query($sql);
    }

    public function down()
    {
        $this->forge->dropTable('activity_objectives', true);
    }
}
