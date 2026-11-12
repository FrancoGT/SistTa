<?php

namespace App\Controllers;

use App\Models\Admin;

class Login extends BaseController
{
    public function index()
    {
        if (model(Admin::class)->logged_id()) {
            // Si la sesión está registrada, redirigir a la página principal.
            return redirect()->to(site_url('dashboard'));
        }

        // Si la sesión no ha sido registrada.
        if (! $this->request->is('post')) {
            return view('login');
        }

        // establecer validación de formulario
        $rules = [
            'username' => ['label' => 'Username', 'rules' => 'required'],
            'password' => ['label' => 'Password', 'rules' => 'required'],
        ];
        // configurar mensaje de validación de formulario
        $mensaje  = '<div class="alert alert-danger" style="margin-top: 3px">
                    <div class="header"><b><i class="fa fa-exclamation-circle"></i> {field}</b> debe ser ingresado</div></div>';
        $messages = [
            'username' => ['required' => $mensaje],
            'password' => ['required' => $mensaje],
        ];

        // verificación de validación
        if (! $this->validate($rules, $messages)) {
            return view('login', ['validation' => $this->validator]);
        }

        // obtener datos del FORM
        $username = (string) $this->request->getPost('username');
        $password = (string) $this->request->getPost('password');

        // revisar data via model
        $checking = model(Admin::class)->check_login($username, $password);

        // Si lo encuentra, entonces cree una sesión
        if ($checking !== false) {
            $apps = $checking[0];

            // evita fijación de sesión
            session()->regenerate(true);
            session()->set([
                'user_id'   => $apps->id_user,
                'user_name' => $apps->username,
                'user_nama' => $apps->nama_user,
            ]);

            return redirect()->to(site_url('dashboard'));
        }

        $data['error'] = '<div class="alert alert-danger" style="margin-top: 3px">
                            <div class="header"><b><i class="fa fa-exclamation-circle"></i> ERROR</b> Usuario y contraseña incorrectos o el Usuario esta Inactivo!</div></div>';

        return view('login', $data);
    }

    public function getflash()
    {
        // obtener datos flash
        return esc((string) session()->getFlashdata('flash_welcome'));
    }
}
