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

        return view('backups_automaticos/index');
    }
}
