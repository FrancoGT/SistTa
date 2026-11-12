<?php

namespace App\Controllers;

use App\Models\Admin;

class Dashboard extends BaseController
{
    public function index()
    {
        if (model(Admin::class)->logged_id()) {
            return view('dashboard');
        }

        // Si la sesión no se ha registrado,
        // redirigir a la página de inicio de sesión
        return redirect()->to(site_url('login'));
    }

    public function get_user()
    {
        return (string) session('user_nama');
    }
}
