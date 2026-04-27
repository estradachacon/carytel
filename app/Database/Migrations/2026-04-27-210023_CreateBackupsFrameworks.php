<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBackups extends Migration
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
            'cliente_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'archivo' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
            ],
            'peso' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'fecha' => [
                'type' => 'DATETIME',
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

        // Índice para consultas rápidas
        $this->forge->addKey('cliente_id');

        // Foreign key
        $this->forge->addForeignKey('cliente_id', 'clientes_frameworks', 'id', 'CASCADE', 'CASCADE');

        $this->forge->createTable('backups_frameworks');
    }

    public function down()
    {
        $this->forge->dropTable('backups_frameworks');
    }
}