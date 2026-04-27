<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_name', 'email', 'user_password', 'role_id', 'branch_id', 'foto'];
    protected $returnType = 'array';
    protected $useTimestamps = true;

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function getUserWithRole($email)
    {
        return $this->select('
                users.*,
                roles.nombre AS role_name
            ')
            ->join('roles', 'roles.id = users.role_id')
            ->where('users.email', $email)
            ->first();
    }
}