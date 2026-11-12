<?php

namespace App\Models;

use CodeIgniter\Model;

class Realiza_PagoModel extends Model
{
    public function index()
    {
    }

    public function guardar($data)
    {
        $this->db->table('tbl_realiza_pago')->insert($data);

        return $this->db->insertID();
    }

    public function guardar_pago_vehiculo($data)
    {
        $this->db->table('tbl_realiza_pago_vehiculo')->insert($data);

        return $this->db->insertID();
    }
}
