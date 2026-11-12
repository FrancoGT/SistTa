<?php

namespace App\Models;

use CodeIgniter\Model;

class Ingreso_EgresoModel extends Model
{
    public function guardar_operacion($data)
    {
        $this->db->table('tbl_operacion')->insert($data);

        return $this->db->insertID();
    }

    public function guardar_comprobante($data)
    {
        $this->db->table('tbl_comprobante')->insert($data);
    }

    public function actualizar_monto_provicional($id_periodo)
    {
        $total_sum = 0;
        $query     = $this->db->query('SELECT o.monto FROM tbl_pago_operacion as pago LEFT JOIN tbl_operacion as o ON pago.id_ope=o.id_operacion WHERE pago.id_perio=?', [$id_periodo]);

        foreach ($query->getResult() as $row) {
            $total_sum += (float) $row->monto;
        }

        $this->db->table('tbl_periodo_pago')
            ->set('monto_provicional', $total_sum)
            ->where('id_periodo', $id_periodo)
            ->update();
    }

    public function guardar_realizado($data)
    {
        $this->db->table('tbl_realizado')->insert($data);

        return $this->db->insertID();
    }

    public function guardar_pago_operacion($data)
    {
        $this->db->table('tbl_pago_operacion')->insert($data);
    }

    private function get_monto_provicional($id_periodo)
    {
        $monto_provicional = 0;
        $query             = $this->db->query('SELECT monto_provicional FROM `tbl_periodo_pago` WHERE id_periodo=?', [$id_periodo]);

        foreach ($query->getResult() as $row) {
            $monto_provicional = $row->monto_provicional;
        }

        return $monto_provicional;
    }

    public function diferencia_montos($monto_pago, $monto_provicional)
    {
        $monto1 = (float) $monto_pago;
        $monto2 = (float) $monto_provicional;

        return $monto1 - $monto2;
    }

    public function cancelar_periodo_pago($id_periodo, $fecha_ingreso)
    {
        $this->db->table('tbl_periodo_pago')
            ->set('estado_pago', 1)
            ->where('id_periodo', $id_periodo)
            ->update();
    }

    public function actualizar_periodo_pago($id_periodo, $monto_pago, $fecha_ingreso)
    {
        $monto_provicional = $this->get_monto_provicional($id_periodo);
        $diferencia        = $this->diferencia_montos($monto_pago, $monto_provicional);

        if ($diferencia == 0) {
            $this->cancelar_periodo_pago($id_periodo, $fecha_ingreso);
        }
        $this->actualizar_monto_provicional($id_periodo);
    }

    public function get_cant_operaciones()
    {
        $query = $this->db->query('SELECT * FROM `tbl_operacion`');

        return $query->getNumRows();
    }
}
