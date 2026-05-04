<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldsToBackups extends Migration
{
    public function up()
    {
        $this->forge->addColumn('backups_frameworks', [
            'hash' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
                'null'       => true,
            ],
            'ip' => [
                'type'       => 'VARCHAR',
                'constraint' => 45,
                'null'       => true,
            ],
            'origen' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
                'null'       => true,
            ],
            'db_nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'notas' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'estado' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'default'    => 'ok',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('backups_frameworks', ['hash', 'ip', 'origen', 'db_nombre', 'notas', 'estado']);
    }
}
