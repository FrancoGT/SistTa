<?php

namespace App\Models;

use CodeIgniter\Model;

class ConfiguracionModel extends Model
{
    public function index()
    {
    }

    public function actualizar($data, $id_configuracion)
    {
        $this->db->table('tbl_configuracion')->where('id_configuracion', $id_configuracion)->update($data);
    }

    public function actualizar_servicio($data, $id_servicio)
    {
        $this->db->table('tbl_servicio')->where('id_servicio', $id_servicio)->update($data);
    }
}
