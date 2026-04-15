<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class NotificationController extends BaseController
{
    public function index()
    {
        if (!tienePermiso('ver_notificaciones')) {
            return redirect()->to(base_url('dashboard'));
        }

        $userId = session()->get('id');
        $roleId = session()->get('role_id');

        $model = new NotificationModel();

        $from    = $this->request->getGet('from');
        $to      = $this->request->getGet('to');
        $perPage = $this->request->getGet('perPage') ?? 10;

        $notifications = $model->getNotificacionesVisibles($userId, $roleId, $from, $to, $perPage);

        return view('notifications/index', [
            'notifications' => $notifications,
            'pager'         => $model->pager,
        ]);
    }

    public function searchAjax()
    {
        if (!tienePermiso('ver_notificaciones')) {
            return $this->response->setJSON([]);
        }

        $userId = session()->get('id');
        $roleId = session()->get('role_id');

        $model   = new NotificationModel();
        $from    = $this->request->getGet('from');
        $to      = $this->request->getGet('to');
        $perPage = $this->request->getGet('perPage') ?? 10;

        $notifications = $model->getNotificacionesVisibles($userId, $roleId, $from, $to, $perPage);

        return view('notifications/_table', [
            'notifications' => $notifications,
            'pager'         => $model->pager,
        ]);
    }

    public function marcarLeida()
    {
        $input = $this->request->getJSON(true);
        $id    = $input['id'] ?? null;
        $userId = session()->get('id');

        if (!$id) {
            return $this->response->setJSON(['status' => 'error']);
        }

        $model = new NotificationModel();
        $model->marcarLeida($id, $userId);

        return $this->response->setJSON(['status' => 'ok']);
    }
}