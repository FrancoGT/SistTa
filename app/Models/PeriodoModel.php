<?php

namespace App\Models;

use CodeIgniter\Model;

class PeriodoModel extends Model
{
    public function guardar($data)
    {
        if (isset($data['id_periodo']) && $data['id_periodo'] === '') {
            unset($data['id_periodo']);
        }
        $this->db->table('tbl_periodo_pago')->insert($data);

        return $this->db->insertID();
    }

    public function get_inicio($id_periodo)
    {
        $inicio = '';
        $query  = $this->db->query('SELECT inicio FROM `tbl_periodo_pago` WHERE id_periodo=?', [$id_periodo]);

        foreach ($query->getResult() as $row) {
            $inicio = $row->inicio;
        }

        return $inicio;
    }

    public function deuda_cancelada($id_periodo, $saldo)
    {
        $estado_pago = '';
        $query       = $this->db->query('SELECT estado_pago FROM `tbl_periodo_pago` WHERE id_periodo=?', [$id_periodo]);

        foreach ($query->getResult() as $row) {
            $estado_pago = $row->estado_pago;
        }

        if ($estado_pago == '1') {
            return 0;
        }

        return $saldo;
    }

    public function get_estado_actual($id_periodo)
    {
        $estado_actual = '';
        $query         = $this->db->query('SELECT estado_periodo FROM `tbl_periodo_pago` WHERE id_periodo=?', [$id_periodo]);

        foreach ($query->getResult() as $row) {
            $estado_actual = $row->estado_periodo;
        }

        return $estado_actual;
    }

    public function get_estado_pago_actual($id_periodo)
    {
        $estado_pago = '0';
        $query       = $this->db->query('SELECT estado_pago FROM `tbl_periodo_pago` WHERE id_periodo=?', [$id_periodo]);

        foreach ($query->getResult() as $row) {
            $estado_pago = $row->estado_pago;
        }

        return $estado_pago;
    }

    public function volver_periodo_inactivo($id_periodo)
    {
        $this->db->table('tbl_periodo_pago')->where('id_periodo', $id_periodo)->update(['estado_periodo' => '0']);
    }

    public function volver_periodo_activo($id_periodo)
    {
        $this->db->table('tbl_periodo_pago')->where('id_periodo', $id_periodo)->update(['estado_periodo' => '1']);
    }

    public function get_ultima_operacion($id_periodo)
    {
        $final_id = '1';
        $query    = $this->db->table('tbl_pago_operacion')
            ->selectMax('id_pago_operacion')
            ->where('id_perio', $id_periodo)
            ->get();

        foreach ($query->getResult() as $row) {
            $final_id = $row->id_pago_operacion;
        }

        return $final_id;
    }

    public function get_id_operacion($id_pago_operacion)
    {
        $query        = $this->db->query('SELECT id_ope FROM `tbl_pago_operacion` WHERE id_pago_operacion=?', [$id_pago_operacion]);
        $id_operacion = '1';

        foreach ($query->getResult() as $row) {
            $id_operacion = $row->id_ope;
        }

        return $id_operacion;
    }

    public function get_fecha_operacion($id_operacion)
    {
        $query = $this->db->query('SELECT fecha FROM `tbl_operacion` WHERE id_operacion=?', [$id_operacion]);
        $fecha = '-1';

        foreach ($query->getResult() as $row) {
            $fecha = $row->fecha;
        }

        return $fecha;
    }

    public function fecha_ultimo_pago($id_periodo)
    {
        $final_id     = $this->get_ultima_operacion($id_periodo);
        $id_operacion = $this->get_id_operacion($final_id);

        return $this->get_fecha_operacion($id_operacion);
    }
}
