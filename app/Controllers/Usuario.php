<?php

namespace App\Controllers;

use App\Models\Admin;
use App\Models\UsuarioModel;

class Usuario extends BaseController
{
	public function index()
	{
        echo view('header');
        echo view('sidebar');
        echo view('usuario/usuario', ['usuarios' => $this->listar_usuarios()]);
        echo view('footer');
	}
	public function recuperar_data($id_usuario)
	{
		$data_usuario = array(
				'id_usuario' => $id_usuario,
				'username' => '',
				'nama_user' => '',
				'email' => '',
				'estado_usuario' => '',
				'tipo' => '',
		);
		$query = $this->db->query('SELECT id_user, username, nama_user, password, email, estado_usuario, tipo FROM `tbl_users` WHERE id_user=?', [$id_usuario]);
		foreach ($query->getResult() as $row)
		{
			$data_usuario['id_usuario'] = str_replace('"','',json_encode($row->id_user));
			$data_usuario['username'] = str_replace('"','',json_encode($row->username));
			$data_usuario['nama_user'] = str_replace('"','',json_encode($row->nama_user));
			$data_usuario['email'] = str_replace('"','',json_encode($row->email));
			$data_usuario['estado_usuario'] = str_replace('"','',json_encode($row->estado_usuario));
			$data_usuario['tipo'] = str_replace('"','',json_encode($row->tipo));
		}
		return $data_usuario;
	}
	//Validacion usuarios
	public function algun_error_usuario($form)
	{
		$algun_error = false;
		if ($form['error_nama_user'] != '')
		{
			$algun_error = true;
		}
		if ($form['error_username'] != '')
		{
			$algun_error = true;
		}
		if ($form['error_email'] != '')
		{
			$algun_error = true;
		}
		return $algun_error;
	}
	public function nama_user_repetido($nama_user)
	{
		$nama_user_repetido = '';
		$query = $this->db->query('SELECT nama_user FROM `tbl_users` WHERE nama_user=?', [$nama_user]);
		foreach ($query->getResult() as $row)
		{
			$nama_user_repetido = $row->nama_user;
		}
		return $nama_user_repetido == $nama_user;
	}
	public function user_name_repetido($username)
	{
		$username_repetido = '';
		$query = $this->db->query('SELECT username FROM `tbl_users` WHERE username=?', [$username]);
		foreach ($query->getResult() as $row)
		{
			$username_repetido = $row->username;
		}
		return $username_repetido == $username;
	}
	public function email_repetido($email)
	{
		$email_repetido = '';
		$query = $this->db->query('SELECT email FROM `tbl_users` WHERE email=?', [$email]);
		foreach ($query->getResult() as $row)
		{
			$email_repetido = $row->email;
		}
		return $email_repetido == $email;
	}
	public function validar_usuario($form)
	{
		$nama_user = $form['nama_user'];
		$username = $form['username'];
		$email = $form['email'];
		if ($this->nama_user_repetido($nama_user))
		{
			$form['error_nama_user'] = "<h3>El nombre de usuario: ".esc($nama_user)." ya existe</h3>";
		}
		if ($this->user_name_repetido($username))
		{
			$form['error_username'] = "<h3>El nick de usuario: ".esc($username)." ya existe</h3>";
		}
		if ($this->email_repetido($email))
		{
			$form['error_email'] = "<h3>El correo electr&oacute;nico de usuario: ".esc($email)." ya existe</h3>";
		}
		return $form;
	}
	public function Dos_registros($data_usuario, $criterio, $id_usuario)
	{
		$query = $this->db->query('SELECT COUNT(*) FROM tbl_users WHERE ' . sql_ident($criterio) . ' = ? AND id_user <> ?', [$data_usuario, $id_usuario]);
		$count = json_encode($query->getResult());
		$resultado = preg_replace("/[^0-9]/", "", $count);
		$num = (int)$resultado;
		return $num == 1;
	}
	public function validar_actualizacion($form)
	{
		$nama_user = $form['nama_user'];
		$username = $form['username'];
		$email = $form['email'];
		$id_usuario = $form['id_usuario'];
		if ($this->Dos_registros($nama_user, 'nama_user', $id_usuario))
		{
			$form['error_nama_user'] = "<h3>El nombre de usuario: ".esc($nama_user)." ya existe</h3>";
		}
		if ($this->Dos_registros($username, 'username', $id_usuario))
		{
			$form['error_username'] = "<h3>El nick de usuario: ".esc($username)." ya existe</h3>";
		}
		if ($this->Dos_registros($email, 'email', $id_usuario))
		{
			$form['error_email'] = "<h3>El correo electr&oacute;nico de usuario: ".esc($email)." ya existe</h3>";
		}
		return $form;
	}
	//Gestion de usuarios
	public function registrar_usuario()
	{
        echo view('header');
        echo view('sidebar');
        echo view('usuario/registrar_usuario');
        echo view('footer');
	}
	public function guardar_usuario()
	{
    	$form = $this->request->getPost();
			$form['error_nama_user'] = '';
			$form['error_username'] = '';
			$form['error_email'] = '';
			$validacion = $this->validar_usuario($form);
			if ($this->algun_error_usuario($validacion))
			{
				echo view('header');
	      echo view('sidebar');
	      echo view('usuario/registrar_usuario', $validacion);
	      echo view('footer');
			}
			else
			{
				$nama_user = $form['nama_user'];
	    	$username = $form['username'];
	      $password = $form['password'];
	    	$tipo = $form['tipo'];
	    	$email = $form['email'];
	    	$estado = $form['estado'];
	    	$data = array(
	    		'nama_user' => $nama_user,
	    		'username' => $username,
					'tipo' => $tipo,
					'password' => Admin::hash_password($password),
					'email' => $email,
					'estado_usuario' => $estado
	    	);
	    	model(UsuarioModel::class)->guardar($data);
	      echo view('usuario/guardar_usuario');
			}
	}
	public function actualizar_datos()
	{
		$form = $this->request->getPost();
		$form['error_nama_user'] = '';
		$form['error_username'] = '';
		$form['error_email'] = '';
		$validacion = $this->validar_actualizacion($form);
		if ($this->algun_error_usuario($validacion))
		{
			echo view('header');
			echo view('sidebar');
			echo view('usuario/actualizar_usuario', $validacion);
			echo view('footer');
		}
		else
		{
			$id_usuario = $form['id_usuario'];
			$nama_user = $form['nama_user'];
			$username = $form['username'];
			$password = $form['password'];
			$tipo = $form['tipo'];
			$email = $form['email'];
			$estado = $form['estado'];
			$data = array(
				'nama_user' => $nama_user,
				'username' => $username,
				'tipo' => $tipo,
				'email' => $email,
				'estado_usuario' => $estado
			);
			if ($password != '')
			{
				$data['password'] = Admin::hash_password($password);
			}
			model(UsuarioModel::class)->actualizar($data, $id_usuario);
			echo view('usuario/actualizar_datos');
		}
	}
	public function actualizar_usuario($id_usuario)
	{
		$data_usuario = $this->recuperar_data($id_usuario);
		echo view('header');
		echo view('sidebar');
		echo view('usuario/actualizar_usuario', $data_usuario);
		echo view('footer');
	}
    /**
     * Usuarios registrados (la tabla se dibuja en usuario/usuario.php).
     *
     * @return list<object>
     */
    public function listar_usuarios(): array
    {
        return $this->db->query('SELECT id_user, username, nama_user, email, tipo, estado_usuario FROM `tbl_users`')->getResult();
    }
}
