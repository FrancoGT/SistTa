<?php

namespace App\Models;

use CodeIgniter\Model;

class ServicioModel extends Model
{
    public function guardar($data)
    {
        $this->db->table('tbl_servicio')->insert($data);
    }

    public function actualizar($data, $id_servicio)
    {
        $this->db->table('tbl_servicio')->where('id_servicio', $id_servicio)->update($data);
    }
}
