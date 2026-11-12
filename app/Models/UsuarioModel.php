<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    public function guardar($data)
    {
        $this->db->table('tbl_users')->insert($data);
    }

    public function actualizar($data, $id_usuario)
    {
        $this->db->table('tbl_users')->where('id_user', $id_usuario)->update($data);
    }
}
