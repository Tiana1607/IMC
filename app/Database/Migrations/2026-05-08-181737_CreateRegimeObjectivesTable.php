<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRegimeObjectivesTable extends Migration
{
    public function up()
    {
        $sql = "CREATE TABLE regime_objectives (
            regime_id INT NOT NULL,
            objective VARCHAR(50) NOT NULL,
            PRIMARY KEY (regime_id, objective),
            FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE CASCADE ON UPDATE CASCADE
        )";

        $this->db->query($sql);
    }

    public function down()
    {
        $this->forge->dropTable('regime_objectives', true);
    }
}
