<?php

namespace App\Controllers;

/**
 * Portado tal cual desde CI3. Las vistas 'upload_form' y 'upload_success' no existían
 * en el proyecto original, por eso este controlador no tiene rutas en Config/Routes.php.
 */
class Upload extends BaseController
{
    public function index()
    {
        return view('upload_form', ['error' => ' ']);
    }

    public function do_upload($documento)
    {
        $rules = [
            'userfile' => 'uploaded[userfile]|is_image[userfile]|ext_in[userfile,gif,jpg,png]'
                . '|max_size[userfile,100]|max_dims[userfile,1024,768]',
        ];

        if (! $this->validate($rules)) {
            return view('upload_form', ['error' => implode('<br>', array_map('esc', $this->validator->getErrors()))]);
        }

        $file = $this->request->getFile('userfile');
        $file->move(FCPATH . 'assets/img/', '$documento' . '.' . $file->guessExtension());

        return view('upload_success', ['upload_data' => [
            'file_name' => $file->getName(),
            'full_path' => FCPATH . 'assets/img/' . $file->getName(),
        ]]);
    }
}
