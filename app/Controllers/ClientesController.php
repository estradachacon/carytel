<?php

namespace App\Controllers;

use App\Models\ClienteFrameworkModel;

class ClientesController extends BaseController
{
    // 📋 LISTADO
    public function index()
    {
        $model = new ClienteFrameworkModel();
        $data['clientes'] = $model->findAll();

        return view('clientes/index', $data);
    }

    // ➕ FORM NUEVO
    public function new()
    {
        return view('clientes/new');
    }

    // 💾 GUARDAR
    public function create()
    {
        $model = new ClienteFrameworkModel();

        $model->save([
            'nombre'    => $this->request->getPost('nombre'),
            'identificador'  => $this->request->getPost('identificador'),
        ]);

        return redirect()->to('/clientes');
    }

    // ✏️ EDITAR
    public function edit($id)
    {
        $model = new ClienteFrameworkModel();

        $data['cliente'] = $model->find($id);

        if (!$data['cliente']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Cliente no encontrado");
        }

        return view('clientes/edit', $data);
    }

    // 🔄 ACTUALIZAR
    public function update($id)
    {
        $model = new ClienteFrameworkModel();

        $model->update($id, [
            'nombre'    => $this->request->getPost('nombre'),
            'identificador'  => $this->request->getPost('identificador'),
        ]);

        return redirect()->to('/clientes');
    }

    // 🗑️ ELIMINAR (soft delete)
    public function delete($id)
    {
        $model = new ClienteFrameworkModel();

        $model->delete($id);

        return redirect()->to('/clientes');
    }

    // 🔍 BUSCAR (AJAX)
    public function buscar()
    {
        $term = $this->request->getGet('term');

        $model = new ClienteFrameworkModel();

        $clientes = $model
            ->groupStart()
            ->like('nombre', $term)
            ->orLike('identificador', $term)
            ->groupEnd()
            ->findAll(10);

        $data = [];

        foreach ($clientes as $c) {
            $data[] = [
                'id'   => $c->id,
                'text' => $c->nombre . ($c->identificador ? ' - ' . $c->identificador : '')
            ];
        }

        return $this->response->setJSON($data);
    }
}
