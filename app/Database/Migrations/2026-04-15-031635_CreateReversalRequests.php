<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateReversalRequests extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'package_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'solicitado_por' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'estatus' => [
                'type'       => 'ENUM',
                'constraint' => ['pendiente', 'aprobada', 'denegada'],
                'default'    => 'pendiente',
            ],
            'aprobado_por' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'default'    => null,
            ],
            'comentario' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('reversal_requests');
    }

    public function down()
    {
        $this->forge->dropTable('reversal_requests', true);
    }
}