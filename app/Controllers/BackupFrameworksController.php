<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class BackupFrameworksController extends BaseController
{
    public function index()
    {
        $chk = requerirPermiso('ver_backups');
        if ($chk !== true) return $chk;

        if ($this->request->isAJAX()) {
            $id = $this->request->getPost('id');
            $backup = model('BackupFrameworksModel')->find($id);
            if (!$backup) {
                return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                    ->setJSON(['error' => 'Backup no encontrado']);
            }

            return $this->response->setJSON($backup);
        }

        $pager = 1;
        return view('backups_automaticos/index', ['pager' => $pager]);
    }
}
