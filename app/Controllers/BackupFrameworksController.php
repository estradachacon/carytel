<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BackupFrameworkModel;
use CodeIgniter\HTTP\ResponseInterface;

class BackupFrameworksController extends BaseController
{
    public function index()
    {
        $chk = requerirPermiso('ver_backups');
        if ($chk !== true) return $chk;

        return view('backups_automaticos/index');
    }

    // AJAX → devuelve tbody + pager para el listado
    public function lista()
    {
        $chk = requerirPermiso('ver_backups');
        if ($chk !== true) return $chk;

        $clienteId = $this->request->getGet('cliente_id') ?: null;
        $fecha     = $this->request->getGet('fecha') ?: null;

        $model   = new BackupFrameworkModel();
        $backups = $model->getWithCliente(
            $clienteId ? (int) $clienteId : null,
            $fecha,
            20
        );

        $pager = $model->pager;

        $tbody = view('backups_automaticos/tbody_row', ['backups' => $backups]);

        return $this->response->setJSON([
            'tbody' => $tbody,
            'pager' => $pager ? $pager->links() : '',
        ]);
    }

    // AJAX → detalle de un backup
    public function detalle(int $id)
    {
        $chk = requerirPermiso('ver_backups');
        if ($chk !== true) return $chk;

        $backup = (new BackupFrameworkModel())->find($id);

        if (!$backup) {
            return $this->response->setStatusCode(ResponseInterface::HTTP_NOT_FOUND)
                ->setJSON(['error' => 'Backup no encontrado']);
        }

        return $this->response->setJSON($backup);
    }

    // Descarga el archivo del backup
    public function descargar(int $id)
    {
        $chk = requerirPermiso('ver_backups');
        if ($chk !== true) return $chk;

        $backup = (new BackupFrameworkModel())->find($id);

        if (!$backup) {
            return redirect()->back()->with('error', 'Backup no encontrado');
        }

        $ruta = WRITEPATH . $backup->archivo;

        if (!is_file($ruta)) {
            return redirect()->back()->with('error', 'El archivo no existe en el servidor');
        }

        $nombre = basename($ruta);
        $mime   = str_ends_with($nombre, '.zip') ? 'application/zip' : 'application/sql';

        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Disposition', 'attachment; filename="' . $nombre . '"')
            ->setHeader('Content-Length', filesize($ruta))
            ->setBody(file_get_contents($ruta));
    }
}
