<?php

namespace App\Controllers;

use App\Models\Ingreso_EgresoModel;

class Reporte extends BaseController
{
    public function index()
    {
        echo view('header');
        echo view('sidebar');
        echo view('reporte/reporte');
        echo view('footer');
    }
    public function formato_d_m_a($date)
    {
      $day = date("j", strtotime($date));
      $day = $this->anteponer_cero($day);
      $month = date("n", strtotime($date));
      $month = $this->anteponer_cero($month);
      $date = date("$day/$month/Y", strtotime($date));
      return $date;
    }
    public function fecha_actual()
    {
      $date = new \DateTime("", new \DateTimeZone('America/Lima'));
      return date("Y-m-d", $date->format('U'));
    }
    public function query_servicios_pagados()
    {
      $query = $this->db->query('SELECT DISTINCT s.id_servicio, s.nombre_servicio FROM tbl_periodo_pago as p LEFT JOIN tbl_servicio as s ON p.id_serv=s.id_servicio WHERE p.estado_pago=1 AND s.estado_servicio=1');
      return $query->getResult();
    }
    public function cant_periodos($id_servicio)
    {
      $query = $this->db->query('SELECT * FROM `tbl_periodo_pago` WHERE id_serv=?', [$id_servicio]);
      return $query->getNumRows();
    }
    public function get_data_servicios_pagados()
    {
      $data = $this->query_servicios_pagados();
      $responce = new \stdClass();
      $responce->cols[] = array(
            "id" => "",
            "label" => "Topping",
            "pattern" => "",
            "type" => "string"
      );
      $responce->cols[] = array(
            "id" => "",
            "label" => "Total",
            "pattern" => "",
            "type" => "number"
      );
      foreach($data as $cd)
      {
           $responce->rows[]["c"] = array(
               array(
                   "v" => "$cd->nombre_servicio",
                   "f" => null
               ) ,
               array(
                   "v" => (int)$this->cant_periodos($cd->id_servicio),
                   "f" => null
               )
           );
      }
      return $this->response->setJSON($responce);
    }
    public function calcular_dias($fecha1, $fecha2)
    {
      $date1 = new \DateTime($fecha1);
      $date2 = new \DateTime($fecha2);
      $diff = $date1->diff($date2);
      $dias = (int)$diff->days;
      return $dias + 1;
    }
    public function get_persona_morosa($id_periodo)
    {
      $id_persona = '';
      $query = $this->db->query('SELECT id_person FROM `tbl_realiza_pago` WHERE id_perio=?', [$id_periodo]);
      foreach ($query->getResult() as $row)
      {
        $id_persona = $row->id_person;
      }
      return $id_persona;
    }
    public function get_vehiculo_moroso($id_periodo)
    {
      $id_vehiculo = '';
      $query = $this->db->query('SELECT id_vehi FROM `tbl_realiza_pago_vehiculo` WHERE id_perio=?', [$id_periodo]);
      foreach ($query->getResult() as $row)
      {
        $id_vehiculo = $row->id_vehi;
      }
      return $id_vehiculo;
    }
    public function supera_dias($dias)
    {
      $dia_maximo = 28;
      $total_dias = (int) $dias;
      return $total_dias >= $dia_maximo;
    }
    public function get_fecha_inicio($id_periodo)
    {
      $inicio = '';
      $query = $this->db->query('SELECT inicio FROM `tbl_periodo_pago` WHERE id_periodo=?', [$id_periodo]);
      foreach ($query->getResult() as $row)
      {
        $inicio = str_replace('"','',json_encode($row->inicio));
      }
      return $inicio;
    }
    public function anteponer_cero($valor)
    {
      $num = (int)$valor;
      $nuevo_valor = "";
      if ($num < 10)
      {
        $nuevo_valor = (string)$valor;
        $nuevo_valor = str_pad($valor, 2, "0", STR_PAD_LEFT);
        return $nuevo_valor;
      }
      else
      {
        $nuevo_valor = (string)$valor;
        return $nuevo_valor;
      }
    }
    public function caja_chica()
    {
      echo view('header');
      echo view('sidebar');
      echo view('reporte/caja_chica');
      echo view('footer');
    }
    public function clientes_morosos()
    {
      echo view('header');
      echo view('sidebar');
      echo view('reporte/clientes_morosos', $this->datos_clientes_morosos());
      echo view('footer');
    }
    /**
     * Clientes morosos: periodos impagos con 28 días o más de deuda.
     * La presentación está en reporte/clientes_morosos.php.
     *
     * @return array{hay_periodos: bool, personas: list<object>, vehiculos: list<object>}
     */
    public function datos_clientes_morosos(): array
    {
      $datos = ['hay_periodos' => false, 'personas' => [], 'vehiculos' => []];
      $datos['hay_periodos'] = $this->db->query('SELECT 1 FROM tbl_periodo_pago LIMIT 1')->getNumRows() > 0;
      if (! $datos['hay_periodos'])
      {
        return $datos;
      }
      $ids_personas = [];
      $ids_vehiculos = [];
      $query = $this->db->query('SELECT id_periodo, inicio FROM `tbl_periodo_pago` WHERE estado_pago=0');
      foreach ($query->getResult() as $row)
      {
        $dias = $this->calcular_dias($this->fecha_actual(), $row->inicio);
        if (! $this->supera_dias($dias))
        {
          continue;
        }
        $id_persona = $this->get_persona_morosa($row->id_periodo);
        if ($id_persona != '')
        {
          $ids_personas[$id_persona] = true;
        }
        $id_vehiculo = $this->get_vehiculo_moroso($row->id_periodo);
        if ($id_vehiculo != '')
        {
          $ids_vehiculos[$id_vehiculo] = true;
        }
      }
      // Cada cliente aparece una sola vez aunque tenga varios periodos vencidos
      if ($ids_personas !== [])
      {
        $datos['personas'] = $this->db->table('tbl_persona')->select('documento, nombre_persona')
          ->whereIn('id_persona', array_keys($ids_personas))->orderBy('nombre_persona')->get()->getResult();
      }
      if ($ids_vehiculos !== [])
      {
        $datos['vehiculos'] = $this->db->table('tbl_vehiculo')->select('cod_vehiculo, placa')
          ->whereIn('id_vehiculo', array_keys($ids_vehiculos))->orderBy('cod_vehiculo')->get()->getResult();
      }
      return $datos;
    }
    public function servicios_mas_ventas()
    {
      $aviso['mensaje'] = '';
      $query = $this->db->query('SELECT COUNT(*) FROM `tbl_periodo_pago`');
      $count = json_encode($query->getResult());
      $resultado = preg_replace("/[^0-9]/", "", $count);
      if ($resultado == "0")
      {
        $aviso['mensaje'] = '<p class="alert alert-danger">No hay registros de pago de servicios a&uacute;n</p>';
      }
      echo view('header');
      echo view('sidebar');
      echo view('reporte/servicios_mas_ventas', $aviso);
      echo view('footer');
    }
    public function cant_operaciones()
    {
      $count = model(Ingreso_EgresoModel::class)->get_cant_operaciones();
      return $count;
    }
    public function generar_caja_chica()
    {
      $form = $this->request->getPost();
      $fecha_inicio = $form['inicio'];
      $fecha_fin = $form['fin'];
      $query1 = $this->db->query('SELECT monto FROM `tbl_operacion` WHERE tipo_operacion=\'1\' AND fecha BETWEEN ? AND ?', [$fecha_inicio, $fecha_fin]);
      $query2 = $this->db->query('SELECT monto FROM `tbl_operacion` WHERE tipo_operacion=\'2\' AND fecha BETWEEN ? AND ?', [$fecha_inicio, $fecha_fin]);
      $suma_ingresos = 0;
      $suma_egresos = 0;
      foreach ($query1->getResult() as $row)
      {
        $suma_ingresos += $row->monto;
      }
      foreach ($query2->getResult() as $row)
      {
        $suma_egresos += $row->monto;
      }
      $data['inicio'] = $fecha_inicio;
      $data['fin'] = $fecha_fin;
      $data['suma_ingresos'] = $suma_ingresos;
      $data['suma_egresos'] = $suma_egresos;
      $ganancia = $suma_ingresos - $suma_egresos;
      $perdida = 0;
      if ($ganancia < 0)
      {
        $perdida = $ganacia * (-1);
        $ganancia = 0;
      }
      if ($ganancia == 0)
      {
        $perdida = $suma_egresos;
      }
      $data['ganancia'] = $ganancia;
      $data['perdida'] = $perdida;
      echo view('header');
      echo view('sidebar');
      echo view('reporte/generar_caja_chica', $data);
      echo view('footer');
    }
    public function generar_periodos_pagados()
    {
      $periodos_pagados = array();
      $query1 = $this->db->query('SELECT id_perio FROM `tbl_realiza_pago`');
      foreach ($query1->getResult() as $row)
      {
        $periodos_pagados = $row->id_perio;
      }
      $query2 = $this->db->query('SELECT id_perio FROM `tbl_realiza_pago_vehiculo`');
      foreach ($query2->getResult() as $row)
      {
        $periodos_pagados = $row->id_perio;
      }
      return $periodos_pagados;
    }
}
