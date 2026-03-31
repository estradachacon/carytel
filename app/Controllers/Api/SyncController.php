<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\PermisoRolModel;

class SyncController extends BaseController
{
    public function saveToken()
    {
        $token = $this->request->getPost('token');

        if (!$token) {
            return $this->response->setJSON([
                'success' => false
            ]);
        }

        $db = db_connect();

        $db->table('device_tokens')->insert([
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'success' => true
        ]);
    }
    public function users()
    {
        $userModel = new UserModel();
        $permisoModel = new PermisoRolModel();

        $users = $userModel->findAll();

        $data = [];

        foreach ($users as $user) {

            $permisosRaw = $permisoModel->getPermisosPorRol($user['role_id']);

            $permisos = [];

            foreach ($permisosRaw as $p) {
                $permisos[$p['nombre_accion']] = (bool)$p['habilitado'];
            }

            $data[] = [
                'id' => (int)$user['id'],
                'username' => $user['user_name'],
                'email' => $user['email'],
                'password_hash' => $user['user_password'],
                'role_id' => $user['role_id'],
                'branch_id' => $user['branch_id'],
                'foto' => $user['foto'],
                'permisos' => $permisos
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $data
        ]);
    }
}
