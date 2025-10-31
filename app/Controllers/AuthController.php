<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;
use App\Models\PermisoRolModel;
use CodeIgniter\Controller;

class AuthController extends BaseController
{
    public function login()
    {
    helper(['form']);
    $session = session();
    $userModel = new \App\Models\UserModel();
    $permisoModel = new \App\Models\PermisoRolModel();

    $username = $this->request->getPost('username');
    $password = $this->request->getPost('password');

    // Buscar por correo o nombre de usuario
    $user = $userModel->where('email', $username)
                      ->orWhere('user_name', $username)
                      ->first();

    if (!$user) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Usuario no encontrado.'
        ]);
    }

    if (!password_verify($password, $user['user_password'])) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Contraseña incorrecta.'
        ]);
    }

    // Cargar los permisos
    $permisos = $permisoModel->getPermisosPorRol($user['role_id']);

    // Guardar sesión
    $sessionData = [
        'id'        => $user['id'],
        'user_name' => $user['user_name'],
        'email'     => $user['email'],
        'role_id'   => $user['role_id'],
        'permisos'  => array_column($permisos, 'habilitado', 'nombre_accion'),
        'logged_in' => true
    ];
    $session->set($sessionData);

    return $this->response->setJSON([
        'success' => true,
        'message' => 'Inicio de sesión correcto',
        'redirect' => base_url('dashboard')
    ]);
}

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
