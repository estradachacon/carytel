<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;

class UserController extends BaseController
{
    public function index()
    {
        // 1. Instanciamos el modelo de usuario.
        $userModel = new UserModel();

        // 2. Obtenemos todos los usuarios, uniendo la tabla 'roles' 
        // para obtener el nombre legible del rol.
        // Nota: Asume que la tabla de roles tiene una columna 'nombre' para el nombre del rol.
        $users = $userModel
                    ->select('users.id, users.user_name, users.email, roles.nombre AS role_name')
                    ->join('roles', 'roles.id = users.role_id')
                    ->findAll();
        
        // 3. Preparamos los datos para la vista.
        $data = [
            'users' => $users,
            'title' => 'Lista de Usuarios' // Título para la página/layout
        ];

        // 4. Cargamos la vista. Asegúrate de que esta ruta sea correcta para tu proyecto.
        return view('users/index', $data);
    }
}
