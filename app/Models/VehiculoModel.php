<?php

namespace App\Models;

use CodeIgniter\Model;

class VehiculoModel extends Model
{
    public function get_estado_actual($id_vehiculo)
    {
        $estado_vehiculo = '0';
        $query           = $this->db->query('SELECT estado_vehiculo FROM `tbl_vehiculo` WHERE id_vehiculo=?', [$id_vehiculo]);

        foreach ($query->getResult() as $row) {
            $estado_vehiculo = $row->estado_vehiculo;
        }

        return $estado_vehiculo;
    }

    public function guardar($data)
    {
        $this->db->table('tbl_vehiculo')->insert($data);

        return $this->db->insertID();
    }

    public function actualizar($data, $id_vehiculo)
    {
        $this->db->table('tbl_vehiculo')->where('id_vehiculo', $id_vehiculo)->update($data);
    }

    public function num_rows($criterio, $dato)
    {
        $query = $this->db->query('SELECT * FROM `tbl_vehiculo` WHERE ' . sql_ident($criterio) . '=?', [$dato]);

        return $query->getNumRows();
    }

    public function num_rows_actualizacion_vehiculo($criterio, $dato, $id_vehiculo)
    {
        $query = $this->db->query('SELECT * FROM `tbl_vehiculo` WHERE ' . sql_ident($criterio) . '=? AND id_vehiculo<>?', [$dato, $id_vehiculo]);

        return $query->getNumRows();
    }
}
