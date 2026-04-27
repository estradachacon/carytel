<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 5,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_name' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
                'unique' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '100',
            ],
            'user_password' => [
                'type' => 'VARCHAR',
                'constraint' => '255', 
            ],
            'role_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE, 
                'default' => 5,   
            ],
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'email' // puedes cambiar la posición si quieres
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
        $this->forge->addForeignKey('role_id', 'roles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('users');
        $this->db->table('users')->insertBatch([
            ['user_name' => 'Gerente', 'user_password' => '$2y$10$42k/s7W2rpRZFWMwWHQmYurL0gEvsSjBAEQ69m4pkPlc9cbUjWzWW', 'email' => 'gerente@mail.com', 'role_id' => 1],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('users');
    }
}
