<?php

namespace App\Controllers;

use App\Models\ClienteFrameworkModel;

class ClientesController extends BaseController
{
    public function index()
    {
        $model = new ClienteFrameworkModel();
        $data['clientes'] = $model->findAll();

        return view('clientes/index', $data);
    }

    public function new()
    {
        return view('clientes/new');
    }

    public function create()
    {
        $model = new ClienteFrameworkModel();

        $model->save([
            'nombre'       => $this->request->getPost('nombre'),
            'identificador' => $this->request->getPost('identificador'),
        ]);

        return redirect()->to('/clientes');
    }

    public function edit(int $id)
    {
        $model = new ClienteFrameworkModel();
        $data['cliente'] = $model->find($id);

        if (!$data['cliente']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Cliente no encontrado");
        }

        return view('clientes/edit', $data);
    }

    public function update(int $id)
    {
        $model = new ClienteFrameworkModel();

        $model->update($id, [
            'nombre'       => $this->request->getPost('nombre'),
            'identificador' => $this->request->getPost('identificador'),
        ]);

        return redirect()->to('/clientes');
    }

    public function delete(int $id)
    {
        $model = new ClienteFrameworkModel();
        $model->delete($id);

        return redirect()->to('/clientes');
    }

    public function buscar()
    {
        $term  = $this->request->getGet('term');
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
                'text' => $c->nombre . ($c->identificador ? ' - ' . $c->identificador : ''),
            ];
        }

        return $this->response->setJSON($data);
    }

    // Genera (o regenera) el api_key del cliente y lo devuelve como JSON
    public function generarApiKey($id)
    {
        $model   = new ClienteFrameworkModel();
        $cliente = $model->find($id);

        if (!$cliente) {
            return $this->response->setStatusCode(404)
                ->setJSON(['ok' => false, 'error' => 'Cliente no encontrado']);
        }

        $apiKey = bin2hex(random_bytes(32)); // 64 chars hex

        $model->update($id, ['api_key' => $apiKey]);

        return $this->response->setJSON(['ok' => true, 'api_key' => $apiKey]);
    }
}
