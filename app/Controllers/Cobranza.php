<?php

namespace App\Controllers;

use App\Models\Ingreso_EgresoModel;
use App\Models\PeriodoModel;
use App\Models\Realiza_PagoModel;

class Cobranza extends BaseController
{
  public function index()
  {
    echo view('header');
    echo view('sidebar');
    echo view('cobranza/cobranza');
    echo view('footer');
  }
  public function pagina_anterior()
  {
    echo "<script type='text/javascript'>";
    echo "window.history.back(-1)";
    echo "</script>";
  }
  public function pagina_anteriorX2()
  {
    echo "<script type='text/javascript'>";
    echo "window.history.back(-2)";
    echo "</script>";
  }
  public function getDocumento($id_persona)
  {
    $documento = '';
    $query = $this->db->query('SELECT documento FROM `tbl_persona` WHERE id_persona=?', [$id_persona]);
    foreach ($query->getResult() as $row)
    {
      $documento = $row->documento;
    }
    return $documento;
  }
  public function formato_moneda($value)
  {
    return number_format($value, 2);
  }
  public function fecha_mayor_que($fecha1, $fecha2)
  {
    $date1 = new \DateTime($fecha1);
    $date2 = new \DateTime($fecha2);
    return ($date1 > $date2);
  }
  public function get_nombre_persona($id_person)
  {
    $nombre = '';
    $query = $this->db->query('SELECT nombre_persona FROM `tbl_persona` WHERE id_persona=?', [$id_person]);
    foreach ($query->getResult() as $row)
    {
      $nombre = $row->nombre_persona;
    }
    return $nombre;
  }
  public function get_placa($id_vehiculo)
  {
    $placa = '';
    $query = $this->db->query('SELECT placa FROM `tbl_vehiculo` WHERE id_vehiculo=?', [$id_vehiculo]);
    foreach ($query->getResult() as $row)
    {
      $placa = $row->placa;
    }
    return $placa;
  }
  public function get_cod_vehiculo($id_vehiculo)
  {
    $cod_vehiculo = '';
    $query = $this->db->query('SELECT cod_vehiculo FROM `tbl_vehiculo` WHERE id_vehiculo=?', [$id_vehiculo]);
    foreach ($query->getResult() as $row)
    {
      $cod_vehiculo = $row->cod_vehiculo;
    }
    return $cod_vehiculo;
  }
  public function fecha_actual()
  {
    $date = new \DateTime("", new \DateTimeZone('America/Lima'));
    return date("Y-m-d", $date->format('U'));
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
  public function formato_a_m_d($date)
  {
    $date = date("Y-n-j", strtotime($date));
    return $date;
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
  public function calcular_dias($fecha1, $fecha2)
  {
    $date1 = new \DateTime($fecha1);
    $date2 = new \DateTime($fecha2);
    $diff = $date1->diff($date2);
    $dias = (int)$diff->days;
    return $dias + 1;
  }
  public function calcular_monto_servicio($pago_diario, $dias)
  {
    $pago_diario = (float)$pago_diario;
    $dias = (float)$dias;
    $monto = $pago_diario * $dias;
    $monto = number_format($monto, 2);
    return $monto;
  }
  //Validacion validacion pagos
  public function algun_error_pago($form)
  {
    $algun_error = false;
    if ($form['error_pago'] != '')
    {
      $algun_error = true;
    }
    return $algun_error;
  }
  public function monto_mayor($form)
  {
    $monto_provicional = $form['monto_provicional'];
    $pago_pendiente = $form['pago_pendiente'];
    $num1 = (float)$monto_provicional;
    $num2 = (float)$pago_pendiente;
    return $num1 > $num2;
  }
  public function validar_pago($form)
  {
    if ($this->monto_mayor($form))
    {
      $form['error_pago'] = '<h3>No puedes ingresar un n&uacute;mero mayor al pago pendiente</h3>';
    }
    return $form;
  }
  //Gestion Cobranzas
  public function get_id_usuario()
  {
    $id_usuario = session('user_id');
    return $id_usuario;
  }
  public function get_nombre_usuario()
  {
    $nombre_usuario = session('user_nama');
    return $nombre_usuario;
  }
  public function get_num_operacion()
  {
    $num_operacion = 1;
    $query = $this->db->query('SELECT max(id_operacion) FROM `tbl_operacion`');
    $count = json_encode($query->getResult());
    $resultado = preg_replace("/[^0-9]/", "", $count);
    if ($resultado == NULL)
    {
      return $num_operacion;
    }
    else
    {
      $new_id = (int)$resultado;
      $new_id = $new_id + 1;
      return $new_id;
    }
  }
  public function ElegirVista($Contexto, $info)
  {
    if ($Contexto == 'Conductor')
    {
      echo view('header');
      echo view('sidebar');
      echo view('cobranza/cobranza_detalle_conductor', $info);
      echo view('footer');
    }
    if ($Contexto == 'Afiliado')
    {
      echo view('header');
      echo view('sidebar');
      echo view('cobranza/cobranza_detalle_afiliado', $info);
      echo view('footer');
    }
    if ($Contexto == 'Vehiculo')
    {
      echo view('header');
      echo view('sidebar');
      echo view('cobranza/cobranza_detalle_vehiculo', $info);
      echo view('footer');
    }
  }
  public function buscar_afiliado($data_afiliado)
  {
    $datos_afiliado = array(
        "id_person_afiliado" => ""
    );
    $encontrado = array(
        "id_person_afiliado" => ""
    );
    $dividir = explode('~', $data_afiliado);
    $data_afiliado = $dividir[0];
    $criterio = $dividir[1] ?? '';
    if (! in_array($criterio, ['cod_afiliado', 'documento'], true)) { return $encontrado; }
    $datos_afiliado = $this->criterio_bus_afiliado($criterio, $data_afiliado);
    if ($datos_afiliado['id_person_afiliado'] != '')
      $encontrado = $datos_afiliado;
    return $encontrado;
  }
  public function criterio_bus_afiliado($criterio, $data_afiliado)
  {
    $table = 'p';
    if ($criterio == 'cod_afiliado')
    {
      $table = 'a';
    }
    $datos_afiliado = array(
        "id_person_afiliado" => ""
    );
    $buscar = $this->db->query('SELECT a.id_person_afiliado FROM tbl_persona as p LEFT JOIN tbl_afiliado as a ON p.id_persona=a.id_person_afiliado WHERE ' . sql_ident($table) . '.' . sql_ident($criterio) . ' = ? AND a.estado_afiliado=1', [$data_afiliado]);
    foreach ($buscar->getResult() as $key)
    {
      $datos_afiliado['id_person_afiliado'] = str_replace('"','',json_encode($key->id_person_afiliado));
    }
    return $datos_afiliado;
  }
  public function buscar_vehiculo($data_vehiculo)
  {
    $datos_vehiculo = array(
        "id_vehiculo" => ""
    );
    $encontrado = array(
        "id_vehiculo" => ""
    );
    $dividir = explode('~', $data_vehiculo);
    $data_vehiculo = $dividir[0];
    $criterio = $dividir[1] ?? '';
    if (! in_array($criterio, ['cod_vehiculo', 'placa'], true)) { return $encontrado; }
    $datos_vehiculo = $this->criterio_bus_vehiculo($criterio, $data_vehiculo);
    if ($datos_vehiculo['id_vehiculo'] != '')
      $encontrado = $datos_vehiculo;
    return $encontrado;
  }
  public function criterio_bus_vehiculo($criterio, $data_vehiculo)
  {
    $datos_vehiculo = array(
        "id_vehiculo" => ""
    );
    $buscar = $this->db->query('SELECT id_vehiculo, cod_vehiculo, placa FROM tbl_vehiculo WHERE ' . sql_ident($criterio) . ' = ? AND estado_vehiculo=1', [$data_vehiculo]);
    foreach ($buscar->getResult() as $key)
    {
      $datos_vehiculo['id_vehiculo'] = str_replace('"','',json_encode($key->id_vehiculo));
    }
    return $datos_vehiculo;
  }
  public function contenedor_periodos($id_person_conductor, $id_servicio)
  {
    $ids_periodos = array();
    $query = $this->db->query('SELECT perio.id_periodo FROM tbl_periodo_pago as perio LEFT JOIN tbl_realiza_pago as r ON perio.id_periodo=r.id_perio WHERE perio.id_serv=? AND r.id_person=?', [$id_servicio, $id_person_conductor]);
    foreach ($query->getResult() as $row)
    {
      $ids_periodos[] = $row->id_periodo;
    }
    return $ids_periodos;
  }
  public function get_nombre_servicio($id_servicio)
  {
      $query = $this->db->query('SELECT nombre_servicio FROM `tbl_servicio` WHERE id_servicio=?', [$id_servicio]);
      foreach($query->getResult() as $row)
      {
          $nombre = $row->nombre_servicio;
      }
      return $nombre;
  }
  public function generar_periodo($id_person, $id_serv, $inicio_pago)
  {
    $fecha_inicio = $this->formato_a_m_d($inicio_pago);
    $data_periodo_pago = array(
            'id_periodo' => '',
            'inicio' => $fecha_inicio,
            'estado_periodo' => '1',
            'id_serv' => $id_serv,
            'estado_pago' => '0',
    );
    $id_perio = model(PeriodoModel::class)->guardar($data_periodo_pago);
    $data_realiza_pago = array(
            'id_pago' => '',
            'id_person' => $id_person,
            'id_perio' => $id_perio,
    );
    model(Realiza_PagoModel::class)->guardar($data_realiza_pago);
  }
  public function buscar_conductor($data_conductor)
  {
    $datos_conductor = array(
        "id_person_conductor" => ""
    );
    $encontrado = array(
        "id_person_conductor" => ""
    );
    $dividir = explode('~', $data_conductor);
    $data_conductor = $dividir[0];
    $criterio = $dividir[1] ?? '';
    $datos_conductor = $this->criterio_bus_conductor($criterio, $data_conductor);
    if ($datos_conductor['id_person_conductor'] != '')
      $encontrado = $datos_conductor;
    return $encontrado;
  }
  public function criterio_bus_conductor($criterio, $data_conductor)
  {
    $datos_conductor = array(
        'id_person_conductor' => '',
        'nombre' => '',
        'cod_conductor' => '',
    );
    $buscar = '';
    if ($criterio == 'cod_conductor')
    {
      $buscar = $this->db->query('SELECT c.id_person_conductor FROM tbl_persona as p LEFT JOIN tbl_conductor as c ON p.id_persona=c.id_person_conductor WHERE c.cod_conductor =? AND c.estado_conductor=1', [$data_conductor]);
    }
    if ($criterio == 'documento')
    {
      $buscar = $this->db->query('SELECT c.id_person_conductor FROM tbl_persona as p LEFT JOIN tbl_conductor as c ON p.id_persona=c.id_person_conductor WHERE p.documento =? AND c.estado_conductor=1', [$data_conductor]);
    }
    if ($buscar != '')
    {
      foreach ($buscar->getResult() as $key)
      {
        $datos_conductor['id_person_conductor'] = str_replace('"','',json_encode($key->id_person_conductor));
      }
      return $datos_conductor;
    }
    else
      return $datos_conductor;
  }
  public function buscar_cobranza_detalle_afiliado()
  {
    $busquedas = false;
    $Afiliado_No_Encontrado = array(
        'id_person_afiliado' => ''
    );
    $form = $this->request->getPost();
    $info = $form['info_afiliado'];
    if ($this->buscar_afiliado($info) != $Afiliado_No_Encontrado)
    {
      $busquedas = true;
      $this->ElegirVista('Afiliado', $this->buscar_afiliado($info));
    }
    if (!$busquedas)
    {
      echo '<script type="text/javascript">
        alert("Registro No existe");
        </script>';
      echo view('header');
      echo view('sidebar');
      echo view('cobranza/cobranza');
      echo view('footer');
      $this->pagina_anterior();
    }
  }
  public function buscar_cobranza_detalle_conductor()
  {
    $busquedas = false;
    $Conductor_No_Encontrado = array(
        'id_person_conductor' => ''
    );
    $form = $this->request->getPost();
    $info = $form['info_conductor'];
    if ($this->buscar_conductor($info) != $Conductor_No_Encontrado)
    {
      $busquedas = true;
      $this->ElegirVista('Conductor', $this->buscar_conductor($info));
    }
    if (!$busquedas)
    {
      echo '<script type="text/javascript">
        alert("Registro No existe o No Esta Inactivo");
        </script>';
      echo view('header');
      echo view('sidebar');
      echo view('cobranza/cobranza');
      echo view('footer');
      $this->pagina_anterior();
    }
  }
  public function buscar_cobranza_detalle_vehiculo()
  {
    $busquedas = false;
    $Vehiculo_No_Encontrado = array(
        'id_vehiculo' => ''
    );
    $form = $this->request->getPost();
    $info = $form['info_vehiculo'];
    if ($this->buscar_vehiculo($info) != $Vehiculo_No_Encontrado)
    {
      $busquedas = true;
      $this->ElegirVista('Vehiculo', $this->buscar_vehiculo($info));
    }
    if (!$busquedas)
    {
      echo '<script type="text/javascript">
        alert("Registro No existe o No Esta Inactivo");
        </script>';
      echo view('header');
      echo view('sidebar');
      echo view('cobranza/cobranza');
      echo view('footer');
      $this->pagina_anterior();
    }
  }
  public function get_servicio($id_servicio)
  {
    $servicio = array(
        'nombre_servicio' => '',
        'pago_diario' => '',
        'estado_servicio' => '',
    );
    $consultar = $this->db->query('SELECT nombre_servicio, pago_diario, estado_servicio FROM `tbl_servicio` WHERE id_servicio=?', [$id_servicio]);
    foreach ($consultar->getResult() as $registro)
    {
      $servicio['nombre_servicio'] = str_replace('"','',json_encode($registro->nombre_servicio));
      $servicio['pago_diario'] = str_replace('"','',json_encode($registro->pago_diario));
      $servicio['estado_servicio'] = str_replace('"','',json_encode($registro->estado_servicio));
    }
    return $servicio;
  }
  public function nuevo_periodo($referencia)
  {
    $dividir = explode('-', $referencia);
    $id_person = $dividir[0];
    $id_servicio = $dividir[1];
    $data['id_person'] = $id_person;
    $data['id_servicio'] = $id_servicio;
    echo view('header');
    echo view('sidebar');
    echo view('cobranza/nuevo_periodo', $data);
    echo view('footer');
  }
  public function nuevo_periodo_vehiculo($referencia)
  {
    $dividir = explode('-', $referencia);
    $id_vehiculo = $dividir[0];
    $id_servicio = $dividir[1];
    $data['id_vehi'] = $id_vehiculo;
    $data['id_servicio'] = $id_servicio;
    echo view('header');
    echo view('sidebar');
    echo view('cobranza/nuevo_periodo_vehiculo', $data);
    echo view('footer');
  }
  public function calcular_pagos($id_periodo)
  {
    $total_sum = 0;
    $query = $this->db->query('SELECT o.monto FROM tbl_pago_operacion as pago LEFT JOIN tbl_operacion as o ON pago.id_ope=o.id_operacion WHERE pago.id_perio=?', [$id_periodo]);
    foreach ($query->getResult() as $row)
    {
      $total_sum += $row->monto;
    }
    return $total_sum;
  }
  public function verificar_fin_periodo($id_periodo)
  {
    $ultima_fecha = model(PeriodoModel::class)->fecha_ultimo_pago($id_periodo);
    if (model(PeriodoModel::class)->get_estado_pago_actual($id_periodo) == 1 && $ultima_fecha != -1)
    {
      return $ultima_fecha;
    }
    else
    {
      return $this->fecha_actual();
    }
  }
  public function get_saldo($id_periodo, $monto_pago)
  {
    $saldo = 0;
    $monto_provicional = $this->calcular_pagos($id_periodo);
    if ($monto_pago == $monto_provicional)
    {
      model(Ingreso_EgresoModel::class)->cancelar_periodo_pago($id_periodo, $this->fecha_actual());
      $saldo = model(PeriodoModel::class)->deuda_cancelada($id_periodo, $saldo);
      return $saldo;
    }
    else
    {
      $saldo = $monto_pago - $monto_provicional;
      $saldo = model(PeriodoModel::class)->deuda_cancelada($id_periodo, $saldo);
      return $saldo;
    }
  }
  public function get_fin_periodo($id_periodo)
  {
    $periodo_fin = $this->fecha_actual();
    $estado_pago = model(PeriodoModel::class)->get_estado_pago_actual($id_periodo);
    if ($estado_pago == 1)
    {
      $periodo_fin = $this->verificar_fin_periodo($id_periodo);
      return $periodo_fin;
    }
    else
    {
      return $periodo_fin;
    }
  }
  public function relacion_conductores($id_vehiculo)
  {
    $docs_conductores = array();
    $query = $this->db->query('SELECT c.id_person_conductor FROM tbl_responsable_vehiculo as r LEFT JOIN tbl_conductor as c ON r.id_person_responsable=c.id_person_conductor WHERE r.id_vehi=? AND r.conduce_vehiculo=1', [$id_vehiculo]);
    foreach ($query->getResult() as $conductor)
    {
      if ($conductor->id_person_conductor == NULL)
      {
        $docs_conductores = NULL;
      }
      else
      {
        $docs_conductores[] = str_replace('"','',json_encode($conductor->id_person_conductor));
      }
    }
    if ($docs_conductores != NULL)
    {
      $conductores = array();
      foreach ($docs_conductores as $valor)
      {
        if (!in_array($valor, $conductores))
        {
          $conductores[] = $valor;
        }
      }
      return $conductores;
    }
    else
    {
      $conductores = array();
      return $conductores;
    }
  }
  public function relacion_vehiculos($id_person_conductor)
  {
    $ids_vehiculos = array();
    $query = $this->db->query('SELECT id_vehi FROM `tbl_responsable_vehiculo` WHERE id_person_responsable=? AND es_propietario=1', [$id_person_conductor]);
    foreach ($query->getResult() as $vehiculo)
    {
      if ($vehiculo->id_vehi == NULL)
      {
        $ids_vehiculos = NULL;
      }
      else
      {
        $ids_vehiculos[] = str_replace('"','',json_encode($vehiculo->id_vehi));
      }
    }
    if ($ids_vehiculos != NULL)
    {
      $vehiculos = array();
      foreach ($ids_vehiculos as $valor)
      {
        if (!in_array($valor, $vehiculos))
        {
          $vehiculos[] = $valor;
        }
      }
      return $vehiculos;
    }
    else
    {
      $vehiculos = array();
      return $vehiculos;
    }
  }
  public function anular_periodo($id_periodo)
  {
    $info = array(
      'id_periodo' => $id_periodo
    );
    echo view('header');
    echo view('sidebar');
    echo view('cobranza/anular_periodo', $info);
    echo view('footer');
  }
  public function reasignar_periodo($id_periodo)
  {
    $info = array(
      'id_periodo' => $id_periodo
    );
    echo view('header');
    echo view('sidebar');
    echo view('cobranza/reasignar_periodo', $info);
    echo view('footer');
  }
  public function quitar_periodo()
  {
    $form = $this->request->getPost();
    $id_periodo = $form['id_periodo'];
    model(PeriodoModel::class)->volver_periodo_inactivo($id_periodo);
    echo view('cobranza/periodo_eliminado');
  }
  public function restablecer_periodo()
  {
    $form = $this->request->getPost();
    $id_periodo = $form['id_periodo'];
    model(PeriodoModel::class)->volver_periodo_activo($id_periodo);
    echo view('cobranza/periodo_restablecido');
  }
  public function periodo_esta_activo($id_periodo)
  {
    $estado_periodo = model(PeriodoModel::class)->get_estado_actual($id_periodo);
    return $estado_periodo == '1';
  }
  public function hay_operaciones($id_periodo)
  {
    $monto = -1;
    $query = $this->db->query('SELECT o.monto FROM tbl_pago_operacion as pago LEFT JOIN 
              tbl_operacion as o ON pago.id_ope=o.id_operacion WHERE pago.id_perio=?', [$id_periodo]);
    foreach ($query->getResult() as $row)
    {
      $monto = $row->monto;
    }
    return $monto != -1;
  }
  /*
   * ---------------------------------------------------------------------------
   * Datos de cobranza para las vistas.
   * Antes estos métodos imprimían las tablas con echo; ahora solo arman arreglos
   * y el HTML está en cobranza/_servicios.php y cobranza/cobranza_detalle_*.php.
   * El orden de los cálculos (incluido get_saldo(), que puede marcar el periodo
   * como cancelado) es el mismo que antes.
   * ---------------------------------------------------------------------------
   */

  /**
   * Filas de periodos de un servicio para una persona (afiliado/conductor) o un vehículo.
   *
   * @param 'persona'|'vehiculo' $tipo
   * @return list<array<string, mixed>>
   */
  public function periodos_servicio(string $tipo, $id, $id_servicio, array $servicio_actual): array
  {
    if ($tipo === 'vehiculo')
    {
      $periodos = $this->db->query('SELECT perio.id_periodo, perio.inicio, perio.estado_periodo FROM tbl_vehiculo as v LEFT JOIN tbl_realiza_pago_vehiculo as rea ON v.id_vehiculo=rea.id_vehi LEFT JOIN tbl_periodo_pago as perio ON rea.id_perio=perio.id_periodo WHERE v.id_vehiculo=? AND perio.id_serv=?', [$id, $id_servicio]);
      $ruta_pagar = 'cobranza/pagar_deuda_vehiculo/';
      $ruta_nuevo = 'cobranza/nuevo_periodo_vehiculo/';
    }
    else
    {
      $periodos = $this->db->query('SELECT perio.id_periodo, perio.inicio, perio.estado_periodo FROM tbl_persona as p LEFT JOIN tbl_realiza_pago as rea ON p.id_persona=rea.id_person LEFT JOIN tbl_periodo_pago as perio ON rea.id_perio=perio.id_periodo WHERE p.id_persona=? AND perio.id_serv=?', [$id, $id_servicio]);
      $ruta_pagar = 'cobranza/pagar_deuda/';
      $ruta_nuevo = 'cobranza/nuevo_periodo/';
    }

    $filas = [];
    $contador_periodos = 0;
    foreach ($periodos->getResult() as $row)
    {
      $id_periodo = $row->id_periodo;
      if (! $this->periodo_esta_activo($id_periodo))
      {
        $inicio = model(PeriodoModel::class)->get_inicio($id_periodo);
        $filas[] = [
          'inicio'      => $this->formato_d_m_a($inicio),
          'fin'         => $this->formato_d_m_a($this->fecha_actual()),
          'estado'      => 'Inactivo',
          'dias'        => 0,
          'pago_diario' => '0.00',
          'monto'       => '0.00',
          'saldo'       => '0.00',
          'cancelado'   => false,
          'acciones'    => [
            ['texto' => 'Restablecer', 'url' => site_url('cobranza/reasignar_periodo/' . rawurlencode($id_periodo)), 'clase' => 'btn-info'],
          ],
        ];
        continue;
      }

      $contador_periodos++;
      $periodo_inicio = $this->formato_d_m_a($row->inicio);
      $periodo_fin = $this->fecha_actual();
      if ($this->fecha_mayor_que($row->inicio, $this->fecha_actual()))
      {
        $periodo_fin = $row->inicio;
      }
      $dias = $this->calcular_dias($periodo_fin, $row->inicio);
      $monto = $this->calcular_monto_servicio($servicio_actual['pago_diario'], $dias);
      $saldo = $monto;
      if ($this->hay_operaciones($id_periodo))
      {
        $saldo = $this->get_saldo($id_periodo, $monto);
        $periodo_fin = $this->get_fin_periodo($id_periodo);
      }

      $cancelado = model(PeriodoModel::class)->get_estado_pago_actual($id_periodo) == 1;
      $acciones = [];
      if ($cancelado)
      {
        // Solo el primer periodo activo ya cancelado ofrece abrir uno nuevo
        if ($contador_periodos == 1)
        {
          $acciones[] = ['texto' => 'Nuevo Periodo', 'url' => site_url($ruta_nuevo . rawurlencode($id . '-' . $id_servicio)), 'clase' => 'btn-info', 'icono' => 'fa-calendar'];
        }
      }
      else
      {
        $acciones[] = ['texto' => 'Pagar', 'url' => site_url($ruta_pagar . rawurlencode($id_periodo . '-' . $id)), 'clase' => 'btn-success'];
        $acciones[] = ['texto' => 'Eliminar', 'url' => site_url('cobranza/anular_periodo/' . rawurlencode($id_periodo)), 'clase' => 'btn-danger'];
      }

      $filas[] = [
        'inicio'      => $periodo_inicio,
        'fin'         => $this->formato_d_m_a($periodo_fin),
        'estado'      => 'Activo',
        'dias'        => $dias,
        'pago_diario' => $servicio_actual['pago_diario'],
        'monto'       => $monto,
        'saldo'       => $this->formato_moneda($saldo),
        'cancelado'   => $cancelado,
        'acciones'    => $acciones,
      ];
    }
    return $filas;
  }

  /**
   * Servicios (con sus periodos) que paga un afiliado, conductor o vehículo.
   *
   * @param 'afiliado'|'conductor'|'vehiculo' $tipo
   * @return array{agregar_url: string, servicios: list<array{nombre: string, periodos: list<array<string, mixed>>}>}
   */
  public function servicios_cobranza(string $tipo, $id): array
  {
    if ($tipo === 'vehiculo')
    {
      $ids_servicios = $this->relacion_servicios_vehiculo($id);
    }
    else
    {
      $ids_servicios = $this->relacion_servicios($id, $tipo === 'afiliado' ? 1 : 2);
    }

    $servicios = [];
    foreach ($ids_servicios ?? [] as $id_servicio)
    {
      $servicio_actual = $this->get_servicio($id_servicio);
      $servicios[] = [
        'nombre'   => $servicio_actual['nombre_servicio'],
        'periodos' => $this->periodos_servicio($tipo === 'vehiculo' ? 'vehiculo' : 'persona', $id, $id_servicio, $servicio_actual),
      ];
    }

    return [
      'agregar_url' => site_url('cobranza/agregar_servicio_' . $tipo . '/' . rawurlencode($id)),
      'servicios'   => $servicios,
    ];
  }

  /**
   * Detalle completo de cobranza de un afiliado: sus servicios, sus vehículos
   * (con los servicios del vehículo) y los conductores de cada vehículo.
   */
  public function cobranza_afiliado($id_person_afiliado): array
  {
    $datos = [
      'servicios' => $this->servicios_cobranza('afiliado', $id_person_afiliado),
      'vehiculos' => [],
    ];
    foreach ($this->relacion_vehiculos($id_person_afiliado) as $id_vehiculo)
    {
      $conductores = [];
      foreach ($this->relacion_conductores($id_vehiculo) as $id_person_conductor)
      {
        $conductores[] = [
          'ficha'     => $this->ficha_conductor($id_person_conductor),
          'servicios' => $this->servicios_cobranza('conductor', $id_person_conductor),
        ];
      }
      $datos['vehiculos'][] = [
        'ficha'       => $this->ficha_vehiculo($id_vehiculo),
        'servicios'   => $this->servicios_cobranza('vehiculo', $id_vehiculo),
        'conductores' => $conductores,
      ];
    }
    return $datos;
  }

  /**
   * Deuda pendiente de un periodo (tabla de resumen en pagar_deuda*.php).
   *
   * @param 'persona'|'vehiculo' $tipo
   * @return list<array{servicio: string, saldo: string}>
   */
  public function deuda_periodo(string $tipo, $id_periodo, $id): array
  {
    if ($tipo === 'vehiculo')
    {
      $consultar = $this->db->query('SELECT perio.inicio, s.nombre_servicio, s.pago_diario FROM tbl_vehiculo as v LEFT JOIN tbl_realiza_pago_vehiculo as rea ON v.id_vehiculo=rea.id_vehi LEFT JOIN tbl_periodo_pago as perio ON rea.id_perio=perio.id_periodo LEFT JOIN tbl_servicio as s ON perio.id_serv=s.id_servicio WHERE v.id_vehiculo=? AND perio.id_periodo=?', [$id, $id_periodo]);
    }
    else
    {
      $consultar = $this->db->query('SELECT perio.inicio, s.nombre_servicio, s.pago_diario FROM tbl_persona as p LEFT JOIN tbl_realiza_pago as rea ON p.id_persona=rea.id_person LEFT JOIN tbl_periodo_pago as perio ON rea.id_perio=perio.id_periodo LEFT JOIN tbl_servicio as s ON perio.id_serv=s.id_servicio WHERE p.id_persona=? AND perio.id_periodo=?', [$id, $id_periodo]);
    }
    $filas = [];
    foreach ($consultar->getResult() as $row)
    {
      $dias = $this->calcular_dias($this->fecha_actual(), $row->inicio);
      $monto = $this->calcular_monto_servicio($row->pago_diario, $dias);
      $saldo = $monto;
      if ($this->hay_operaciones($id_periodo))
      {
        $saldo = $this->get_saldo($id_periodo, $monto);
      }
      $filas[] = ['servicio' => $row->nombre_servicio, 'saldo' => $this->formato_moneda($saldo)];
    }
    return $filas;
  }
  public function cual_servicio($ids_servicios, $aplica)
  {
    $servicios = array();
    foreach ($ids_servicios as $valor)
    {
      $query = $this->db->query('SELECT DISTINCT id_servicio FROM tbl_servicio WHERE id_servicio = ? AND aplica=?', [$valor, $aplica]);
      foreach ($query->getResult() as $row)
      {
        if ($row->id_servicio == NULL)
        {
          $servicios = NULL;
        }
        else
        {
          $servicios[] = str_replace('"','',json_encode($row->id_servicio));
        }
      }
    }
    return $servicios;
  }
  public function relacion_servicios($id_person_conductor, $aplica)
  {
    $ids_servicios = array();
    $query = $this->db->query('SELECT DISTINCT perio.id_serv FROM tbl_persona as p LEFT JOIN tbl_realiza_pago as rea ON p.id_persona=rea.id_person LEFT JOIN tbl_periodo_pago as perio ON rea.id_perio=perio.id_periodo WHERE p.id_persona=?', [$id_person_conductor]);
    foreach ($query->getResult() as $servicio)
    {
      if ($servicio->id_serv == NULL)
      {
        $ids_servicios = NULL;
      }
      else
      {
        $ids_servicios[] = str_replace('"','',json_encode($servicio->id_serv));
      }
    }
    if ($ids_servicios != NULL)
    {
      $ids_servicios = $this->cual_servicio($ids_servicios, $aplica);
      return $ids_servicios;
    }
    else
    {
      $servicios = array();
      return $servicios;
    }
  }
  public function relacion_servicios_vehiculo($id_vehiculo)
  {
    $ids_servicios = array();
    $query = $this->db->query('SELECT DISTINCT perio.id_serv FROM tbl_vehiculo as v LEFT JOIN tbl_realiza_pago_vehiculo as rea ON v.id_vehiculo=rea.id_vehi LEFT JOIN tbl_periodo_pago as perio ON rea.id_perio=perio.id_periodo WHERE v.id_vehiculo=?', [$id_vehiculo]);
    foreach ($query->getResult() as $servicio)
    {
      if ($servicio->id_serv == NULL)
      {
        $ids_servicios = NULL;
      }
      else
      {
        $ids_servicios[] = str_replace('"','',json_encode($servicio->id_serv));
      }
    }
    if ($ids_servicios != NULL)
    {
      return $ids_servicios;
    }
    else
    {
      $servicios = array();
      return $servicios;//
    }
  }
  public function get_pago_pendiente($id_periodo, $id_person_conductor)
  {
    $consultar = $this->db->query('SELECT perio.inicio, s.nombre_servicio, s.pago_diario FROM tbl_persona as p LEFT JOIN tbl_realiza_pago as rea ON p.id_persona=rea.id_person LEFT JOIN tbl_periodo_pago as perio ON rea.id_perio=perio.id_periodo LEFT JOIN tbl_servicio as s ON perio.id_serv=s.id_servicio WHERE p.id_persona=? AND perio.id_periodo=?', [$id_person_conductor, $id_periodo]);
    foreach ($consultar->getResult() as $row)
    {
      $dias = $this->calcular_dias($this->fecha_actual(), $row->inicio);
      $monto = $this->calcular_monto_servicio($row->pago_diario, $dias);
      $saldo = $monto;
      if ($this->hay_operaciones($id_periodo))
      {
        $saldo = $this->get_saldo($id_periodo, $monto);
      }
    }
    return $saldo;
  }
  public function get_pago_pendiente_vehiculo($id_periodo, $id_vehiculo)
  {
    $consultar = $this->db->query('SELECT perio.inicio, s.nombre_servicio, s.pago_diario FROM tbl_vehiculo as v LEFT JOIN tbl_realiza_pago_vehiculo as rea ON v.id_vehiculo=rea.id_vehi LEFT JOIN tbl_periodo_pago as perio ON rea.id_perio=perio.id_periodo LEFT JOIN tbl_servicio as s ON perio.id_serv=s.id_servicio WHERE v.id_vehiculo=? AND perio.id_periodo=?', [$id_vehiculo, $id_periodo]);
    foreach ($consultar->getResult() as $row)
    {
      $dias = $this->calcular_dias($this->fecha_actual(), $row->inicio);
      $monto = $this->calcular_monto_servicio($row->pago_diario, $dias);
      $saldo = $monto;
      if ($this->hay_operaciones($id_periodo))
      {
        $saldo = $this->get_saldo($id_periodo, $monto);
      }
    }
    return $saldo;
  }
  public function ver_monto($id_periodo, $id_person_conductor)
  {
    $consultar = $this->db->query('SELECT perio.inicio, s.nombre_servicio, s.pago_diario FROM tbl_persona as p LEFT JOIN tbl_realiza_pago as rea ON p.id_persona=rea.id_person LEFT JOIN tbl_periodo_pago as perio ON rea.id_perio=perio.id_periodo LEFT JOIN tbl_servicio as s ON perio.id_serv=s.id_servicio WHERE p.id_persona=? AND perio.id_periodo=?', [$id_person_conductor, $id_periodo]);
    foreach ($consultar->getResult() as $row)
    {
      $dias = $this->calcular_dias($this->fecha_actual(), $row->inicio);
    }
    $monto = $this->calcular_monto_servicio($row->pago_diario, $dias);
    return $monto;
  }
  public function ver_monto_vehiculo($id_periodo, $id_vehiculo)
  {
    $consultar = $this->db->query('SELECT perio.inicio, s.nombre_servicio, s.pago_diario FROM tbl_vehiculo as v LEFT JOIN tbl_realiza_pago_vehiculo as rea ON v.id_vehiculo=rea.id_vehi LEFT JOIN tbl_periodo_pago as perio ON rea.id_perio=perio.id_periodo LEFT JOIN tbl_servicio as s ON perio.id_serv=s.id_servicio WHERE v.id_vehiculo=? AND perio.id_periodo=?', [$id_vehiculo, $id_periodo]);
    foreach ($consultar->getResult() as $row)
    {
      $dias = $this->calcular_dias($this->fecha_actual(), $row->inicio);
    }
    $monto = $this->calcular_monto_servicio($row->pago_diario, $dias);
    return $monto;
  }
  public function pagar_deuda($datos)
  {
    $dividir = explode('-', $datos);
    $id_periodo = $dividir[0];
    $id_person = $dividir[1];
    $datos_deudor = array(
        "id_person" => $id_person,
        "nombre" => $this->get_nombre_persona($id_person),
        "id_periodo" => $id_periodo,
        "num_operacion" => "",
        "nombre_usuario" => "",
        "id_usuario" => "",
    );
    $datos_deudor["num_operacion"] = $this->get_num_operacion();
    $datos_deudor["nombre_usuario"] = $this->get_nombre_usuario();
    $datos_deudor["id_usuario"] = $this->get_id_usuario();
    echo view('header');
    echo view('sidebar');
    echo view('cobranza/pagar_deuda', $datos_deudor);
    echo view('footer');
  }
  public function pagar_deuda_vehiculo($datos)
  {
    $dividir = explode('-', $datos);
    $id_periodo = $dividir[0];
    $id_vehiculo = $dividir[1];
    $datos_vehiculo = array(
        "id_vehiculo" => $id_vehiculo,
	       "cod_vehiculo" => $this->get_cod_vehiculo($id_vehiculo),
        "placa" => $this->get_placa($id_vehiculo),
        "id_periodo" => $id_periodo,
        "num_operacion" => "",
        "nombre_usuario" => "",
        "id_usuario" => "",
    );
    $datos_vehiculo["num_operacion"] = $this->get_num_operacion();
    $datos_vehiculo["nombre_usuario"] = $this->get_nombre_usuario();
    $datos_vehiculo["id_usuario"] = $this->get_id_usuario();
    echo view('header');
    echo view('sidebar');
    echo view('cobranza/pagar_deuda_vehiculo', $datos_vehiculo);
    echo view('footer');
  }
  public function cancelar_pago()
  {
    $form = $this->request->getPost();
    $form['error_pago'] = '';
    $validacion = $this->validar_pago($form);
    if ($this->algun_error_pago($validacion))
    {
      echo view('header');
      echo view('sidebar');
      echo view('cobranza/pagar_deuda', $validacion);
      echo view('footer');
    }
    else
    {
      $monto_provicional = $form["monto_provicional"];
      $id_usuario = $form["id_usuario"];
      $id_periodo = $form["id_periodo"];
      $id_person = $form["id_person"];
      $fecha_ingreso = $this->fecha_actual();
      $monto_pago = $this->ver_monto($id_periodo, $id_person);
      $data_operacion_ingreso = array(
          "id_operacion" => "",
          "monto" => $monto_provicional,
          "fecha" => $fecha_ingreso,
          "tipo_operacion" => '1',
          "descripcion" => 'Pago de deuda por servicio'
      );
      $id_operacion_ingreso = model(Ingreso_EgresoModel::class)->guardar_operacion($data_operacion_ingreso);
      $data_realizado = array(
          "id_realizado" => "",
          "id_us" => $id_usuario,
          "id_ope" => $id_operacion_ingreso,
      );
      model(Ingreso_EgresoModel::class)->guardar_realizado($data_realizado);
      $data_pago_operacion = array(
          "id_pago_operacion" => "",
          "id_ope" => $id_operacion_ingreso,
          "id_perio" => $id_periodo,
      );
      model(Ingreso_EgresoModel::class)->guardar_pago_operacion($data_pago_operacion);
      model(Ingreso_EgresoModel::class)->actualizar_periodo_pago($id_periodo, $monto_pago, $fecha_ingreso);
      echo view('cobranza/pago_realizado');
    }
  }
  public function cancelar_pago_vehiculo()
  {
    $form = $this->request->getPost();
    $form['error_pago'] = '';
    $validacion = $this->validar_pago($form);
    if ($this->algun_error_pago($validacion))
    {
      echo view('header');
      echo view('sidebar');
      echo view('cobranza/pagar_deuda_vehiculo', $validacion);
      echo view('footer');
    }
    else
    {
      $monto_provicional = $form["monto_provicional"];
      $id_usuario = $form["id_usuario"];
      $id_periodo = $form["id_periodo"];
      $id_vehiculo = $form["id_vehiculo"];
      $fecha_ingreso = $this->fecha_actual();
      $monto_pago = $this->ver_monto_vehiculo($id_periodo, $id_vehiculo);
      $data_operacion_ingreso = array(
          "id_operacion" => "",
          "monto" => $monto_provicional,
          "fecha" => $fecha_ingreso,
          "tipo_operacion" => '1',
          "descripcion" => 'Pago de deuda por servicio'
      );
      $id_operacion_ingreso = model(Ingreso_EgresoModel::class)->guardar_operacion($data_operacion_ingreso);
      $data_realizado = array(
          "id_realizado" => "",
          "id_us" => $id_usuario,
          "id_ope" => $id_operacion_ingreso,
      );
      model(Ingreso_EgresoModel::class)->guardar_realizado($data_realizado);
      $data_pago_operacion = array(
          "id_pago_operacion" => "",
          "id_ope" => $id_operacion_ingreso,
          "id_perio" => $id_periodo,
      );
      model(Ingreso_EgresoModel::class)->guardar_pago_operacion($data_pago_operacion);
      model(Ingreso_EgresoModel::class)->actualizar_periodo_pago($id_periodo, $monto_pago, $fecha_ingreso);
      echo view('cobranza/pago_realizado');
    }
  }
  public function encontrar_servicio_afiliado()
  {
    $form = $this->request->getPost();
    $data_servicio = $form['data_servicio'];
    $id_person_afiliado = $form['id_person_afiliado'];
    $Servicio_No_Encontrado = array(
      'id_servicio' => '',
      'id_person_afiliado' => '',
      'id_person_conductor' => '',
      'id_vehiculo' => ''
      );
    if ($Servicio_No_Encontrado != $this->buscar_servicio($data_servicio, '1'))
    {
      $Servicio_Encontrado = $this->buscar_servicio($data_servicio, '1');
      $Servicio_Encontrado['id_person_afiliado'] = $id_person_afiliado;
      echo view('header');
      echo view('sidebar');
      echo view('cobranza/encontrar_servicio_afiliado', $Servicio_Encontrado);
      echo view('footer');
    }
    else
    {
      echo '<script type="text/javascript">
        alert("Servicio No existe o esta Inactivo o No se aplica para Afiliados");
        </script>';
      echo view('header');
      echo view('sidebar');
      echo view('cobranza/cobranza');
      echo view('footer');
      $this->pagina_anterior();
    }
  }
  public function encontrar_servicio_conductor()
  {
    $form = $this->request->getPost();
    $data_servicio = $form['data_servicio'];
    $id_person_conductor = $form['id_person_conductor'];
    $Servicio_No_Encontrado = array(
      'id_servicio' => '',
      'id_person_afiliado' => '',
      'id_person_conductor' => '',
      'id_vehiculo' => ''
      );
    if ($Servicio_No_Encontrado != $this->buscar_servicio($data_servicio, '2'))
    {
      $Servicio_Encontrado = $this->buscar_servicio($data_servicio, '2');
      $Servicio_Encontrado['id_person_conductor'] = $id_person_conductor;
      echo view('header');
      echo view('sidebar');
      echo view('cobranza/encontrar_servicio_conductor', $Servicio_Encontrado);
      echo view('footer');
    }
    else
    {
      echo '<script type="text/javascript">
        alert("Servicio No existe o esta Inactivo o No se aplica para Conductores");
        </script>';
      echo view('header');
      echo view('sidebar');
      echo view('cobranza/cobranza');
      echo view('footer');
      $this->pagina_anterior();
    }
  }
  public function encontrar_servicio_vehiculo()
  {
    $form = $this->request->getPost();
    $data_servicio = $form['data_servicio'];
    $id_vehi = $form['id_vehi'];
    $Servicio_No_Encontrado = array(
      'id_servicio' => '',
      'id_person_afiliado' => '',
      'id_person_conductor' => '',
      'id_vehiculo' => ''
      );
    if ($Servicio_No_Encontrado != $this->buscar_servicio($data_servicio, '3'))
    {
      $Servicio_Encontrado = $this->buscar_servicio($data_servicio, '3');
      $Servicio_Encontrado['id_vehi'] = $id_vehi;
      echo view('header');
      echo view('sidebar');
      echo view('cobranza/encontrar_servicio_vehiculo', $Servicio_Encontrado);
      echo view('footer');
    }
    else
    {
      echo '<script type="text/javascript">
        alert("Servicio No existe o esta Inactivo o No se aplica para Vehiculos");
        </script>';
      echo view('header');
      echo view('sidebar');
      echo view('cobranza/cobranza');
      echo view('footer');
      $this->pagina_anterior();
    }
  }
  public function buscar_servicio($data_servicio, $aplica)
  {
    $datos_servicio = array(
        'id_servicio' => '',
        'id_person_afiliado' => '',
        'id_person_conductor' => '',
        'id_vehiculo' => ''
      );
    $encontrado = $datos_servicio;
    $datos_servicio = $this->criterio_bus_servicio($data_servicio, $aplica);
    if ($datos_servicio['id_servicio'] != '')
      $encontrado = $datos_servicio;
    return $encontrado;
  }
  public function criterio_bus_servicio($data_servicio, $aplica)
  {
    $datos_servicio = array(
        'id_servicio' => '',
        'id_person_afiliado' => '',
        'id_person_conductor' => '',
        'id_vehiculo' => ''
      );
    $buscar = $this->db->query('SELECT * FROM `tbl_servicio` WHERE nombre_servicio=? AND aplica=? AND estado_servicio=1', [$data_servicio, $aplica]);
    foreach ($buscar->getResult() as $key)
    {
      $datos_servicio['id_servicio'] = str_replace('"','',json_encode($key->id_servicio));
    }
    return $datos_servicio;
  }
  public function guardar_servicio_conductor()
  {
      $form = $this->request->getPost();
      $id_person_conductor = $form['id_person_conductor'];
      $id_serv = $form['id_serv'];
      $inicio_pago = $form['inicio_pago'];
      $fecha_fin = '';
      $data_periodo_pago = array(
          'id_periodo' => '',
          'inicio' => $inicio_pago,
          'estado_periodo' => '1',
          'id_serv' => $id_serv,
          'estado_pago' => '0',
      );
      $id_perio = model(PeriodoModel::class)->guardar($data_periodo_pago);
      $data_realiza_pago = array(
          'id_pago' => '',
          'id_person' => $id_person_conductor,
          'id_perio' => $id_perio,
      );
      model(Realiza_PagoModel::class)->guardar($data_realiza_pago);
      echo view('cobranza/guardar_deuda_servicio');
  }
  public function guardar_nuevo_periodo()
  {
    $form = $this->request->getPost();
    $id_person = $form['id_person'];
    $id_serv = $form['id_serv'];
    $inicio_pago = $form['inicio_pago'];
    $fecha_fin = '';
    $data_periodo_pago = array(
        'id_periodo' => '',
        'inicio' => $inicio_pago,
        'estado_periodo' => '1',
        'id_serv' => $id_serv,
        'estado_pago' => '0',
    );
    $id_perio = model(PeriodoModel::class)->guardar($data_periodo_pago);
    $data_realiza_pago = array(
        'id_pago' => '',
        'id_person' => $id_person,
        'id_perio' => $id_perio,
    );
    model(Realiza_PagoModel::class)->guardar($data_realiza_pago);
    echo view('cobranza/guardar_deuda_servicio');
  }
  public function guardar_nuevo_periodo_vehiculo()
  {
    $form = $this->request->getPost();
    $id_vehiculo = $form['id_vehi'];
    $id_serv = $form['id_serv'];
    $inicio_pago = $form['inicio_pago'];
    $fecha_fin = '';
    $data_periodo_pago = array(
        'id_periodo' => '',
        'inicio' => $inicio_pago,
        'estado_periodo' => '1',
        'id_serv' => $id_serv,
        'estado_pago' => '0',
    );
    $id_perio = model(PeriodoModel::class)->guardar($data_periodo_pago);
    $data_realiza_pago = array(
        'id_pago' => '',
        'id_vehi' => $id_vehiculo,
        'id_perio' => $id_perio,
    );
    model(Realiza_PagoModel::class)->guardar_pago_vehiculo($data_realiza_pago);
    echo view('cobranza/guardar_deuda_servicio');
  }
  public function guardar_servicio_afiliado()
  {
    $form = $this->request->getPost();
    $id_person_afiliado = $form['id_person_afiliado'];
    $id_serv = $form['id_serv'];
    $inicio_pago = $form['inicio_pago'];
    $fecha_fin = '';
    $data_periodo_pago = array(
        'id_periodo' => '',
        'inicio' => $inicio_pago,
        'estado_periodo' => '1',
        'id_serv' => $id_serv,
        'estado_pago' => '0',
    );
    $id_perio = model(PeriodoModel::class)->guardar($data_periodo_pago);
    $data_realiza_pago = array(
        'id_pago' => '',
        'id_person' => $id_person_afiliado,
        'id_perio' => $id_perio,
    );
    model(Realiza_PagoModel::class)->guardar($data_realiza_pago);
    echo view('cobranza/guardar_deuda_servicio');
  }
  public function guardar_servicio_vehiculo()
  {
      $form = $this->request->getPost();
      $id_vehiculo = $form['id_vehi'];
      $id_serv = $form['id_serv'];
      $inicio_pago = $form['inicio_pago'];
      $fecha_fin = '';
      $data_periodo_pago = array(
          'id_periodo' => '',
          'inicio' => $inicio_pago,
          'estado_periodo' => '1',
          'id_serv' => $id_serv,
          'estado_pago' => '0',
      );
      $id_perio = model(PeriodoModel::class)->guardar($data_periodo_pago);
      $data_realiza_pago = array(
          'id_pago' => '',
          'id_vehi' => $id_vehiculo,
          'id_perio' => $id_perio,
      );
      model(Realiza_PagoModel::class)->guardar_pago_vehiculo($data_realiza_pago);
      echo view('cobranza/guardar_deuda_servicio');
  }
  public function agregar_servicio_afiliado($id_person_afiliado)
  {
    $info = array('id_person_afiliado' => $id_person_afiliado);
    echo view('header');
    echo view('sidebar');
    echo view('cobranza/agregar_servicio_afiliado', $info);
    echo view('footer');
  }
  public function agregar_servicio_conductor($id_person_conductor)
  {
    $info = array('id_person_conductor' => $id_person_conductor);
    echo view('header');
    echo view('sidebar');
    echo view('cobranza/agregar_servicio_conductor', $info);
    echo view('footer');
  }
  public function agregar_servicio_vehiculo($id_vehiculo)
  {
    $info = array('id_vehiculo' => $id_vehiculo);
    echo view('header');
    echo view('sidebar');
    echo view('cobranza/agregar_servicio_vehiculo', $info);
    echo view('footer');
  }
}
