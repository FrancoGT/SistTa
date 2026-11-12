<?php

namespace App\Controllers;

use App\Models\Ingreso_EgresoModel;

class Ingreso_Egreso extends BaseController
{
  public function index()
  {
    echo view('header');
    echo view('sidebar');
    echo view('ingreso_egreso/ingreso_egreso', ['operaciones' => $this->listar_ingresos_egresos()]);
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
  public function getRUC()
  {
    $ruc = '';
    $query = $this->db->query('SELECT RUC FROM tbl_configuracion');
    foreach($query->getResult() as $row)
    {
      $ruc = $row->RUC;
    }
    return $ruc;
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
  public function get_monto_operacion($id_operacion)
  {
    return 0;
  }
  public function get_fecha_operacion($id_operacion)
  {
    $fecha_operacion = '';
    $query = $this->db->query('SELECT fecha FROM tbl_operacion WHERE id_operacion=?', [$id_operacion]);
    foreach ($query->getResult() as $row)
    {
      if ($row->fecha != NULL)
      {
        $fecha_operacion = $row->fecha;
      }
    }
    return $fecha_operacion;
  }
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
  public function nom_usuario($id_operacion)
  {
    $nom_usuario = "username";
    $query = $this->db->query('SELECT u.nama_user FROM tbl_realizado as r LEFT JOIN tbl_users as u ON r.id_us=u.id_user WHERE r.id_ope=?', [$id_operacion]);
    foreach ($query->getResult() as $usuario)
    {
      $nom_usuario = $usuario->nama_user;
    }
    return $nom_usuario;
  }
  public function get_operacion($id_operacion)
  {
    return 0;
  }
    /**
     * Operaciones de ingreso/egreso (la tabla se dibuja en ingreso_egreso/ingreso_egreso.php).
     * Se agrega "fecha_dma" ya formateada para mostrar.
     *
     * @return list<object>
     */
    public function listar_ingresos_egresos(): array
    {
        $operaciones = $this->db->query('SELECT o.fecha, o.monto, o.descripcion, o.tipo_operacion, u.nama_user FROM tbl_operacion as o LEFT JOIN tbl_realizado as r ON o.id_operacion=r.id_ope LEFT JOIN tbl_users as u ON r.id_us=u.id_user')->getResult();
        foreach ($operaciones as $operacion)
        {
            $operacion->fecha_dma = $this->formato_d_m_a($operacion->fecha);
        }
        return $operaciones;
    }
  public function criterio_bus_afiliado($criterio, $data_afiliado)
  {
    $dni_afiliado = '';
    $buscar = $this->db->query('SELECT a.id_person_afiliado FROM tbl_afiliado as a LEFT JOIN tbl_persona as p ON a.id_person_afiliado=p.id_persona WHERE a.' . sql_ident($criterio) . ' = ?', [$data_afiliado]);
    foreach ($buscar->getResult() as $key)
    {
      $dni_afiliado = $key->id_person_afiliado;
    }
    return $dni_afiliado;
  }
  public function criterio_bus_conductor($criterio, $data_conductor)
  {
    $dni_conductor = '';
    $buscar = $this->db->query('SELECT c.id_person_conductor FROM tbl_conductor as c LEFT JOIN tbl_persona as p ON c.id_person_conductor=p.id_persona WHERE c.' . sql_ident($criterio) . ' = ?', [$data_conductor]);
    foreach ($buscar->getResult() as $key)
    {
      $dni_conductor = $key->id_person_conductor;
    }
    return $dni_conductor;
  }
  public function buscar_afiliado($data_afiliado)
  {
    $dni_afiliado = '';
    if ($this->criterio_bus_afiliado('id_person_afiliado', $data_afiliado) != '')
    {
      $dni_afiliado = $this->criterio_bus_afiliado('id_person_afiliado', $data_afiliado);
    }
    else if ($this->criterio_bus_afiliado('cod_afiliado', $data_afiliado) != '')
    {
      $dni_afiliado = $this->criterio_bus_afiliado('cod_afiliado', $data_afiliado);
    }
    return $dni_afiliado;
  }
  public function buscar_conductor($data_conductor)
  {
    $dni_conductor = '';
    if ($this->criterio_bus_conductor('id_person_conductor', $data_conductor) != '')
    {
      $dni_conductor = $this->criterio_bus_conductor('id_person_conductor', $data_conductor);
    }
    else if ($this->criterio_bus_conductor('cod_conductor', $data_conductor) != '')
    {
      $dni_conductor = $this->criterio_bus_conductor('cod_conductor', $data_conductor);
    }
    else if ($this->criterio_bus_conductor('brevete', $data_conductor) != '')
    {
      $dni_conductor = $this->criterio_bus_conductor('brevete', $data_conductor);
    }
    return $dni_conductor;
  }
  public function get_id_persona($data_deudor)
  {
    $id_persona = '';
    $datos_deudor = array(
        "id_persona" => "",
        "nombre" => "",
    );
    if ($id_persona == $this->buscar_conductor($data_deudor))
    $buscar = $this->db->query('SELECT p.id_persona, p.nombre_persona FROM tbl_periodo_pago as perio LEFT JOIN tbl_realiza_pago as r ON perio.id_periodo=r.id_perio LEFT JOIN tbl_persona as p ON r.id_person=p.id_persona WHERE perio.id_periodo=?', [$id_periodo]);
    foreach ($buscar->getResult() as $key)
    {
      $datos_deudor['id_persona'] = str_replace('"','',json_encode($key->id_persona));
      $datos_deudor['nombre'] = str_replace('"','',json_encode($key->nombre_persona));
    }
    return $datos_deudor;
  }
  public function recuperar_data($fecha, $doc_externo, $monto_provicional,
    $descripcion, $mensaje)
  {
    $info = array(
      'fecha' => $fecha,
      'doc_externo' => $doc_externo,
      'monto_provicional' => $monto_provicional,
      'descripcion' => $descripcion,
      'mensaje' => $mensaje
    );
    return $info;
  }
  public function insertar_comprobante($form, $id_operacion)
  {
    $doc_comprobante = $form['doc_comprobante'];
    $proveedor = $form['proveedor'];
    $tipo_comprobante = $form['comprobante'];
    if ($doc_comprobante == '' || $proveedor == '' || $tipo_comprobante == '')
    {
      return false;
    }
    else
    {
      $data_comprobante = array(
        'id_comprobante' => '',
        'doc_comprobante' => $doc_comprobante,
        'proveedor' => $proveedor,
        'tipo_comprobante' => $tipo_comprobante,
        'id_ope' => $id_operacion
      );
      model(Ingreso_EgresoModel::class)->guardar_comprobante($data_comprobante);
      return true;
    }
  }
  public function registrar_ingreso()
  {
    $info_ingreso['num_operacion'] = $this->get_num_operacion();
    $info_ingreso['nombre_usuario'] = $this->get_nombre_usuario();
    echo view('header');
    echo view('sidebar');
    echo view('ingreso_egreso/registrar_ingreso', $info_ingreso);
    echo view('footer');
  }
  public function guardar_ingreso()
  {
    $form = $this->request->getPost();
    $fecha = $form['fecha'];
    $descripcion = $form['descripcion'];
    $monto_provicional = $form['monto_provicional'];
    $id_usuario = $this->get_id_usuario();
    $data_operacion_ingreso = array(
        "id_operacion" => "",
        "monto" => $monto_provicional,
        "fecha" => $fecha,
        "tipo_operacion" => '1',
        "descripcion" => $descripcion
    );
    $id_operacion_ingreso = model(Ingreso_EgresoModel::class)->guardar_operacion($data_operacion_ingreso);
    $data_realizado = array(
        "id_realizado" => "",
        "id_us" => $id_usuario,
        "id_ope" => $id_operacion_ingreso,
    );
    model(Ingreso_EgresoModel::class)->guardar_realizado($data_realizado);
    if ($this->insertar_comprobante($form, $id_operacion_ingreso))
    {
      echo view('ingreso_egreso/ingreso_guardado_comprobante');
    }
    else
    {
      echo view('ingreso_egreso/ingreso_guardado');
    }
  }
  public function registrar_egreso()
  {
    $info_egreso['num_operacion'] = $this->get_num_operacion();
    $info_egreso['nombre_usuario'] = $this->get_nombre_usuario();
    echo view('header');
    echo view('sidebar');
    echo view('ingreso_egreso/registrar_egreso', $info_egreso);
    echo view('footer');
  }
  public function guardar_egreso()
  {
    $form = $this->request->getPost();
    $fecha = $form['fecha'];
    $descripcion = $form['descripcion'];
    $monto_provicional = $form['monto_provicional'];
    $id_usuario = $this->get_id_usuario();
    $data_operacion_egreso = array(
        "id_operacion" => "",
        "monto" => $monto_provicional,
        "fecha" => $fecha,
        "tipo_operacion" => '2',
        "descripcion" => $descripcion
    );
    $id_operacion_egreso = model(Ingreso_EgresoModel::class)->guardar_operacion($data_operacion_egreso);
    $data_realizado = array(
        "id_realizado" => "",
        "id_us" => $id_usuario,
        "id_ope" => $id_operacion_egreso,
    );
    model(Ingreso_EgresoModel::class)->guardar_realizado($data_realizado);
    if ($this->insertar_comprobante($form, $id_operacion_egreso))
    {
      echo view('ingreso_egreso/egreso_guardado_comprobante');
    }
    else
    {
      echo view('ingreso_egreso/egreso_guardado');
    }
  }
}
