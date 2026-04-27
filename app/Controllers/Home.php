<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $session = session();

        // ✅ Si ya inició sesión, enviarlo directo al dashboard
        if ($session->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('welcome_message');
    }
}
