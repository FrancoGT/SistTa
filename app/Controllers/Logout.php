<?php

namespace App\Controllers;

class Logout extends BaseController
{
    public function index()
    {
        // (en CI3 la vista logout/logout destruía la sesión y redirigía)
        session()->destroy();

        return redirect()->to(site_url('login'));
    }
}
