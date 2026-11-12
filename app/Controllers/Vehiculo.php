<?php

namespace App\Controllers;

use App\Models\Responsable_VehiculoModel;
use App\Models\VehiculoModel;

class Vehiculo extends BaseController
{

  public function index()
  {
    echo view('header');
    echo view('sidebar');
    echo view('vehiculo/vehiculo', $this->listar_vehiculos());
    echo view('footer');
  }
  public function get_estado_actual($id_vehiculo)
  {
    return model(VehiculoModel::class)->get_estado_actual($id_vehiculo);
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
  public function cant_conductores($id_vehiculo)
  {
    $query = $this->db->query('SELECT * FROM `tbl_responsable_vehiculo` WHERE id_vehi=? AND conduce_vehiculo=1', [$id_vehiculo]);
    return $query->getNumRows();
  }
  public function agregar_propietario($id_vehiculo)
  {
    $info = array('id_vehiculo' => $id_vehiculo);
    echo view('header');
    echo view('sidebar');
    echo view('vehiculo/agregar_propietario', $info);
    echo view('footer');
  }
  public function agregar_conductor($id_vehiculo)
  {
    $info = array('id_vehiculo' => $id_vehiculo);
    echo view('header');
    echo view('sidebar');
    echo view('vehiculo/agregar_conductor', $info);
    echo view('footer');
  }
    /**
     * Propietarios (es_propietario=1) o conductores activos (conduce_vehiculo=1) de un vehículo.
     * Las tablas se dibujan en vehiculo/actualizar_vehiculo.php.
     *
     * @return list<object>
     */
    public function responsables_vehiculo($id_vehiculo, bool $conductores): array
    {
        $condicion = $conductores
            ? 'r.conduce_vehiculo=1 AND r.estado_responsable_vehiculo=1'
            : 'r.es_propietario=1';
        return $this->db->query('SELECT r.id_responsable, p.id_persona, p.nombre_persona, p.documento FROM tbl_responsable_vehiculo as r LEFT JOIN tbl_persona as p ON r.id_person_responsable=p.id_persona WHERE r.id_vehi=? AND ' . $condicion, [$id_vehiculo])->getResult();
    }
  //El Vehiculo no puede quedarse sin dueño, por tanto hacemos este control ->
  public function al_menos_un_propietario($id_person, $id_vehiculo, $info)
  {
    if (model(Responsable_VehiculoModel::class)->al_menos_un_propietario($id_vehiculo))
    {
      echo ("<script LANGUAGE='JavaScript'>
    		window.alert('El Vehículo tiene que quedarse al menos con un propietario');
    		</script>");
      $this->pagina_anterior();
    }
    else
    {
      echo view('header');
      echo view('sidebar');
      echo view('vehiculo/eliminar_propietario', $info);
      echo view('footer');
    }
  }
  public function eliminar_propietario($referencia)
  {
    $info = $this->get_info_referencia_responsable($referencia);
    $id_person = $info['id_person'];
    $id_vehiculo = $info['id_vehiculo'];
    $this->al_menos_un_propietario($id_person, $id_vehiculo, $info);
  }
  public function eliminar_conductor($referencia)
  {
    $info = $this->get_info_referencia_responsable($referencia);
    echo view('header');
    echo view('sidebar');
    echo view('vehiculo/eliminar_conductor', $info);
    echo view('footer');
  }
  public function quitar_conductor_si()
  {
    $form = $this->request->getPost();
    $id_person = $form['id_person_conductor_si'];
    $id_vehiculo = $form['id_vehiculo_si'];
    model(Responsable_VehiculoModel::class)->quitar_conductor($id_person, $id_vehiculo);
    echo view('vehiculo/conductor_eliminado');
  }
  public function quitar_conductor_no()
  {
    $form = $this->request->getPost();
    $id_person = $form['id_person_no'];
    $id_vehiculo = $form['id_vehiculo_no'];
    $this->actualizar_vehiculo($id_vehiculo);
  }
  public function quitar_propietario_si()
  {
    $form = $this->request->getPost();
    $id_person = $form['id_person_propietario_si'];
    $id_vehiculo = $form['id_vehiculo_si'];
    model(Responsable_VehiculoModel::class)->quitar_propietario($id_person, $id_vehiculo);
    echo view('vehiculo/propietario_eliminado');
  }
  public function quitar_propietario_no()
  {
    $form = $this->request->getPost();
    $id_person = $form['id_person_no'];
    $id_vehiculo = $form['id_vehiculo_no'];
    $this->actualizar_vehiculo($id_vehiculo);
  }
  //Validacion Vehiculos
  public function algun_error_vehiculo($form)
  {
    $algun_error = false;
    if ($form['error_cod_vehiculo'] != '')
    {
      $algun_error = true;
    }
    if ($form['error_placa'] != '')
    {
      $algun_error = true;
    }
    return $algun_error;
  }
  public function validar_vehiculo($form)
  {
    $cod_vehiculo = $form['cod_vehiculo_G'];
    $placa = $form['placa_G'];
    if (model(VehiculoModel::class)->num_rows('cod_vehiculo', $cod_vehiculo) == 1)
    {
      $form['error_cod_vehiculo'] = "<p class='alert alert-danger'>El C&oacute;digo de Veh&iacute;culo: ".esc($cod_vehiculo)." ya existe</p>";
    }
    if (model(VehiculoModel::class)->num_rows('placa', $placa) == 1)
    {
      $form['error_placa'] = "<p class='alert alert-danger'>La Placa de Veh&iacute;culo: ".esc($placa)." ya existe</p>";
    }
    return $form;
  }
  public function validar_actualizacion($form)
  {
    $id_vehiculo = $form['id_vehiculo'];
    $cod_vehiculo = $form['cod_vehiculo'];
    $placa = $form['placa'];
    if (model(VehiculoModel::class)->num_rows_actualizacion_vehiculo('cod_vehiculo', $cod_vehiculo, $id_vehiculo) == 1)
    {
      $form['error_cod_vehiculo'] = "<p class='alert alert-danger'>El C&oacute;digo de Veh&iacute;culo: ".esc($cod_vehiculo)." ya existe</p>";
    }
    if (model(VehiculoModel::class)->num_rows_actualizacion_vehiculo('placa', $placa, $id_vehiculo) == 1)
    {
      $form['error_placa'] = "<p class='alert alert-danger'>La Placa de Veh&iacute;culo: ".esc($placa)." ya existe</p>";
    }
    return $form;
  }
  //Gestion Vehiculos
  public function get_propietario_vehiculo($id_vehiculo)
  {
    $propietario = "";
    $query = $this->db->query('SELECT p.nombre_persona FROM tbl_responsable_vehiculo as r LEFT JOIN tbl_vehiculo as v ON r.id_vehi=v.id_vehiculo LEFT JOIN tbl_persona as p ON r.id_person_responsable=p.id_persona WHERE v.id_vehiculo=? AND r.es_propietario=1', [$id_vehiculo]);
    $cant = $query->getNumRows();
    $i = 1;
    if ($cant == 1)
    {
      foreach ($query->getResult() as $row)
      {
        $propietario = $row->nombre_persona;
      }
    }
    else
    {
      foreach ($query->getResult() as $row)
      {
        if ($i == $cant)
        {
          $propietario = $propietario . $row->nombre_persona;
        }
        else
        {
          $i = $i + 1;
          $propietario = $propietario . $row->nombre_persona . ", <br>";
        }
      }
    }
    return $propietario;
  }
  public function recuperar_data($cod_vehiculo, $data_afiliado, $id_person_afiliado,
    $placa, $estado, $data_conductor, $id_person_conductor, $mensaje, $mensaje2)
  {
    $info = array(
      'cod_vehiculo' => $cod_vehiculo,
      'data_afiliado' => $data_afiliado,
      'id_person_afiliado' => $id_person_afiliado,
      'placa' => $placa,
      'estado_vehiculo' => $estado,
      'data_conductor' => $data_conductor,
      'id_person_conductor' => $id_person_conductor,
      'mensaje' => $mensaje,
      'mensaje2' => $mensaje2,
    );
    return $info;
  }
  public function recuperar_info($id_vehiculo)
  {
    $data_vehiculo = array(
      'id_vehiculo' => $id_vehiculo,
      'cod_vehiculo' => '',
      'placa' => '',
      'estado_vehiculo' => '',
    );
    $query = $this->db->query('SELECT cod_vehiculo, placa, estado_vehiculo FROM `tbl_vehiculo` WHERE id_vehiculo=?', [$id_vehiculo]);
    foreach ($query->getResult() as $row)
    {
      $data_vehiculo['cod_vehiculo'] = str_replace('"','',json_encode($row->cod_vehiculo));
      $data_vehiculo['placa'] = str_replace('"','',json_encode($row->placa));
      $data_vehiculo['estado_vehiculo'] = str_replace('"','',json_encode($row->estado_vehiculo));
    }
    return $data_vehiculo;
  }
  public function actualizar_vehiculo($id_vehiculo)
  {
    $info_vehiculo = $this->recuperar_info($id_vehiculo);
    echo view('header');
    echo view('sidebar');
    echo view('vehiculo/actualizar_vehiculo', $info_vehiculo);
    echo view('footer');
  }

    /**
     * Vehículos activos para el listado (la tabla se dibuja en vehiculo/vehiculo.php).
     * Cada fila incluye "propietarios" (texto) para mostrarlo y buscarlo.
     *
     * @return array{hay_registros: bool, vehiculos: list<object>}
     */
    public function listar_vehiculos(): array
    {
        $hay_registros = $this->db->query('SELECT 1 FROM tbl_vehiculo LIMIT 1')->getNumRows() > 0;
        $vehiculos = $this->db->query('SELECT id_vehiculo, cod_vehiculo, placa, estado_vehiculo FROM tbl_vehiculo WHERE estado_vehiculo=1')->getResult();
        foreach ($vehiculos as $vehiculo)
        {
            $vehiculo->propietarios = $this->get_propietario_vehiculo($vehiculo->id_vehiculo);
        }
        return ['hay_registros' => $hay_registros, 'vehiculos' => $vehiculos];
    }
  public function pagina_anterior()
  {
    echo "<script type='text/javascript'>";
    echo "window.history.back(-1)";
    echo "</script>";
  }
  public function registrar_vehiculo()
  {
    echo view('header');
    echo view('sidebar');
    echo view('vehiculo/registrar_vehiculo');
    echo view('footer');
  }
  public function criterio_bus_afiliado($criterio, $data_afiliado)
  {
    $id_person_afiliado = '';
    $table = 'a';
    if ($criterio == 'documento')
    {
      $table = 'p';
    }
    $buscar = $this->db->query('SELECT a.id_person_afiliado FROM tbl_afiliado as a LEFT JOIN tbl_persona as p ON a.id_person_afiliado=p.id_persona WHERE ' . sql_ident($table) . '.' . sql_ident($criterio) . ' = ?', [$data_afiliado]);
    foreach ($buscar->getResult() as $key)
    {
      $id_person_afiliado = $key->id_person_afiliado;
    }
    return $id_person_afiliado;
  }
  public function criterio_bus_conductor($criterio, $data_conductor)
  {
    $id_person_conductor = '';
    $table = 'c';
    if ($criterio == 'documento')
    {
      $table = 'p';
    }
    $buscar = $this->db->query('SELECT c.id_person_conductor FROM tbl_conductor as c LEFT JOIN tbl_persona as p ON c.id_person_conductor=p.id_persona WHERE ' . sql_ident($table) . '.' . sql_ident($criterio) . ' = ?', [$data_conductor]);
    foreach ($buscar->getResult() as $key)
    {
      $id_person_conductor = $key->id_person_conductor;
    }
    return $id_person_conductor;
  }
  public function buscar_afiliado($data_afiliado)
  {
    $id_person_afiliado = '';
    if ($this->criterio_bus_afiliado('documento', $data_afiliado) != '')
    {
      $id_person_afiliado = $this->criterio_bus_afiliado('documento', $data_afiliado);
    }
    else if ($this->criterio_bus_afiliado('cod_afiliado', $data_afiliado) != '')
    {
      $id_person_afiliado = $this->criterio_bus_afiliado('cod_afiliado', $data_afiliado);
    }
    return $id_person_afiliado;
  }
  public function buscar_conductor($data_conductor)
  {
    $id_person_conductor = '';
    if ($this->criterio_bus_conductor('documento', $data_conductor) != '')
    {
      $id_person_conductor = $this->criterio_bus_conductor('documento', $data_conductor);
    }
    else if ($this->criterio_bus_conductor('cod_conductor', $data_conductor) != '')
    {
      $id_person_conductor = $this->criterio_bus_conductor('cod_conductor', $data_conductor);
    }
    else if ($this->criterio_bus_conductor('brevete', $data_conductor) != '')
    {
      $id_person_conductor = $this->criterio_bus_conductor('brevete', $data_conductor);
    }
    return $id_person_conductor;
  }
  public function confirmar_afiliado_si()
  {
    $form = $this->request->getPost();
    $cod_vehiculo = $form['cod_vehiculo_si'];
    $data_afiliado = $form['data_afiliado_si'];
    $id_person_afiliado = $form['id_person_afiliado_si'];
    $placa = $form['placa_si'];
    $estado = $form['estado_si'];
    $data_conductor = $form['data_conductor_si'];
    $id_person_conductor = $form['id_person_conductor_si'];
    $info = $this->recuperar_data($cod_vehiculo, $data_afiliado, $id_person_afiliado,
      $placa, $estado, $data_conductor, $id_person_conductor, "<p class='alert alert-info'>Afiliado Asignado con Éxito</p>", '');
    echo view('header');
    echo view('sidebar');
    echo view('vehiculo/registrar_vehiculo', $info);
    echo view('footer');
  }
  public function confirmar_conductor_si()
  {
    $form = $this->request->getPost();
    $cod_vehiculo = $form['cod_vehiculo_si'];
    $data_afiliado = $form['data_afiliado_si'];
    $id_person_afiliado = $form['id_person_afiliado_si'];
    $placa = $form['placa_si'];
    $estado = $form['estado_si'];
    $data_conductor = $form['data_conductor_si'];
    $id_person_conductor = $form['id_person_conductor_si'];
    $info = $this->recuperar_data($cod_vehiculo, $data_afiliado, $id_person_afiliado,
      $placa, $estado, $data_conductor, $id_person_conductor, '', "<p class='alert alert-info'>Conductor Asignado con Éxito</p");
    echo view('header');
    echo view('sidebar');
    echo view('vehiculo/registrar_vehiculo', $info);
    echo view('footer');
  }
  public function confirmar_afiliado_no()
  {
    $form = $this->request->getPost();
    $cod_vehiculo = $form['cod_vehiculo_no'];
    $data_afiliado = $form['data_afiliado_no'];
    $id_person_afiliado = $form['id_person_afiliado_no'];
    $placa = $form['placa_no'];
    $estado = $form['estado_no'];
    $data_conductor = $form['data_conductor_no'];
    $id_person_conductor = $form['id_person_conductor_no'];
    $info = $this->recuperar_data($cod_vehiculo, $data_afiliado, $id_person_afiliado,
      $placa, $estado, $data_conductor, $id_person_conductor, "<p class='alert alert-danger'>Afiliado Rechazado</p>", '');
    echo view('header');
    echo view('sidebar');
    echo view('vehiculo/registrar_vehiculo', $info);
    echo view('footer');
  }
  public function confirmar_conductor_no()
  {
    $form = $this->request->getPost();
    $cod_vehiculo = $form['cod_vehiculo_no'];
    $data_afiliado = $form['data_afiliado_no'];
    $id_person_afiliado = $form['id_person_afiliado_no'];
    $placa = $form['placa_no'];
    $estado = $form['estado_no'];
    $data_conductor = $form['data_conductor_no'];
    $id_person_conductor = $form['id_person_conductor_no'];
    $info = $this->recuperar_data($cod_vehiculo, $data_afiliado, $id_person_afiliado,
      $placa, $estado, $data_conductor, $id_person_conductor, '', "<p class='alert alert-danger'>Conductor Rechazado</p>");
    echo view('header');
    echo view('sidebar');
    echo view('vehiculo/registrar_vehiculo', $info);
    echo view('footer');
  }
  public function confirmar_nuevo_propietario_si()
  {
    $form = $this->request->getPost();
    $id_person = $form['id_person_si'];
    $id_vehiculo = $form['id_vehiculo_si'];
    $anterior_id_person = $form['anterior_id_person_si'];
    //indica si solamente hemos agreado un propietario
    $add_only = true;
    if ($anterior_id_person != '-1')
    {
      model(Responsable_VehiculoModel::class)->quitar_propietario($anterior_id_person, $id_vehiculo);
      $add_only = false;
    }
    if (model(Responsable_VehiculoModel::class)->existe_responsable($id_person, $id_vehiculo))
    {
      model(Responsable_VehiculoModel::class)->reasignar_propietario($id_person, $id_vehiculo);
    }
    else
    {
      $this->insertar_afiliado($id_person, $id_vehiculo);
    }
    if ($add_only)
    {
      echo view('vehiculo/propietario_agregado');
    }
    echo view('vehiculo/actualizar_propietario');
  }
  public function confirmar_nuevo_propietario_no()
  {
    $form = $this->request->getPost();
    $id_person = $form['id_person_no'];
    $id_vehiculo = $form['id_vehiculo_no'];
    $anterior_id_person = $form['anterior_id_person_no'];
    $data_cambiar_propietario = array(
      'id_person' => $anterior_id_person,
      'id_vehiculo' => $id_vehiculo
    );
  }
  public function confirmar_nuevo_conductor_si()
  {
    $form = $this->request->getPost();
    $id_person = $form['id_person_si'];
    $id_vehiculo = $form['id_vehiculo_si'];
    $anterior_id_person = $form['anterior_id_person_si'];
    //indica si solamente hemos agreado un propietario
    $add_only = true;
    if ($anterior_id_person != '-1')
    {
      model(Responsable_VehiculoModel::class)->quitar_conductor($anterior_id_person, $id_vehiculo);
      $add_only = false;
    }
    if (model(Responsable_VehiculoModel::class)->existe_responsable($id_person, $id_vehiculo))
    {
      model(Responsable_VehiculoModel::class)->reasignar_conductor($id_person, $id_vehiculo);
    }
    else
    {
      $this->insertar_conductor($id_person, $id_vehiculo);
    }
    if ($add_only)
    {
      echo view('vehiculo/conductor_agregado');
    }
    echo view('vehiculo/actualizar_conductor');
  }
  public function confirmar_nuevo_conductor_no()
  {
    $form = $this->request->getPost();
    $id_person = $form['id_person_no'];
    $id_vehiculo = $form['id_vehiculo_no'];
    $anterior_id_person = $form['anterior_id_person_no'];
    $data_cambiar_conductor = array(
      'id_person' => $anterior_id_person,
      'id_vehiculo' => $id_vehiculo
    );
  }
  public function encontrar_afiliado()
  {
    $form = $this->request->getPost();
    $cod_vehiculo = $form['cod_vehiculo_BA'];
    $data_afiliado = $form['data_afiliado_BA'];
    $id_person_afiliado = $form['id_person_afiliado_BA'];
    $placa = $form['placa_BA'];
    $estado = $form['estado_BA'];
    $data_conductor = $form['data_conductor_BA'];
    $id_person_conductor = $form['id_person_conductor_BA'];
    if ($this->buscar_afiliado($data_afiliado) != '')
    {
      $id_person_afiliado = $this->buscar_afiliado($data_afiliado);
      $info = $this->recuperar_data($cod_vehiculo, $data_afiliado, $id_person_afiliado,
      $placa, $estado, $data_conductor, $id_person_conductor, '<p class="alert alert-info">Afiliado Encontrado</p>', '');
      echo view('header');
      echo view('sidebar');
      echo view('vehiculo/encontrar_afiliado', $info);
      echo view('footer');
    }
    else
    {
      $info = $this->recuperar_data($cod_vehiculo, $data_afiliado, $id_person_afiliado,
      $placa, $estado, $data_conductor, $id_person_conductor, "<p class='alert alert-danger'>Afiliado No Existe o esta Inactivo</p>", '');
      echo view('header');
      echo view('sidebar');
      echo view('vehiculo/registrar_vehiculo', $info);
      echo view('footer');
    }
  }
  public function encontrar_conductor()
  {
    $form = $this->request->getPost();
    $cod_vehiculo = $form['cod_vehiculo_BC'];
    $data_afiliado = $form['data_afiliado_BC'];
    $id_person_afiliado = $form['id_person_afiliado_BC'];
    $placa = $form['placa_BC'];
    $estado = $form['estado_BC'];
    $data_conductor = $form['data_conductor_BC'];
    $id_person_conductor = $form['id_person_conductor_BC'];
    if ($this->buscar_conductor($data_conductor) != '')
    {
      $id_person_conductor = $this->buscar_conductor($data_conductor);
      $info = $this->recuperar_data($cod_vehiculo, $data_afiliado, $id_person_afiliado,
      $placa, $estado, $data_conductor, $id_person_conductor, '', '<p class="alert alert-info">Conductor Encontrado</p>');
      echo view('header');
      echo view('sidebar');
      echo view('vehiculo/encontrar_conductor', $info);
      echo view('footer');
    }
    else
    {
      $info = $this->recuperar_data($cod_vehiculo, $data_afiliado, $id_person_afiliado,
      $placa, $estado, $data_conductor, $id_person_conductor, '', "<p class='alert alert-danger'>Conductor No Existe o esta Inactivo</p>");
      echo view('header');
      echo view('sidebar');
      echo view('vehiculo/registrar_vehiculo', $info);
      echo view('footer');
    }
  }
  public function insertar_afiliado($id_person_afiliado, $id_vehiculo)
  {
    $data = array(
            'id_responsable' => '',
            'id_vehi' => $id_vehiculo,
            'id_person_responsable' => $id_person_afiliado,
            'es_propietario' => '1',
            'conduce_vehiculo' => '0',
            'estado_responsable_vehiculo' => '1'
    );
    model(Responsable_VehiculoModel::class)->guardar($data);
  }
  public function insertar_conductor($id_person_conductor, $id_vehiculo)
  {
    $data = array(
            'id_responsable' => '',
            'id_vehi' => $id_vehiculo,
            'id_person_responsable' => $id_person_conductor,
            'es_propietario' => '0',
            'conduce_vehiculo' => '1',
            'estado_responsable_vehiculo' => '1'
    );
    model(Responsable_VehiculoModel::class)->guardar($data);
  }
  public function insertar_afiliado_conductor($id_person_afiliado_conductor, $id_vehiculo)
  {
    $data = array(
            'id_responsable' => '',
            'id_vehi' => $id_vehiculo,
            'id_person_responsable' => $id_person_conductor,
            'es_propietario' => '1',
            'conduce_vehiculo' => '1',
            'estado_responsable_vehiculo' => '1'
    );
    model(Responsable_VehiculoModel::class)->guardar($data);
  }
  public function guardar_vehiculo_sin_conductor($cod_vehiculo, $placa, $estado, $id_person_afiliado)
  {
    $data_vehiculo = array(
                'id_vehiculo' => '',
                'cod_vehiculo' => $cod_vehiculo,
                'placa' => $placa,
                'estado_vehiculo' => $estado
    );
    $id_vehiculo_new = model(VehiculoModel::class)->guardar($data_vehiculo);
    $this->insertar_afiliado($id_person_afiliado, $id_vehiculo_new);
  }
  public function guardar_vehiculo_datos_completos($cod_vehiculo, $placa, $estado, $id_person_afiliado, $id_person_conductor)
  {
    $data_vehiculo = array(
                'id_vehiculo' => '',
                'cod_vehiculo' => $cod_vehiculo,
                'placa' => $placa,
                'estado_vehiculo' => $estado
    );
    $id_vehiculo_new = model(VehiculoModel::class)->guardar($data_vehiculo);
    if ($id_person_afiliado == $id_person_conductor)
    {
      $this->insertar_afiliado_conductor($id_person_afiliado, $id_vehiculo_new);
    }
    else
    {
      $this->insertar_afiliado($id_person_afiliado, $id_vehiculo_new);
      $this->insertar_conductor($id_person_conductor, $id_vehiculo_new);
    }
  }
  public function recepcionar_vehiculo()
  {
    $form = $this->request->getPost();
    $form['error_cod_vehiculo'] = '';
    $form['error_placa'] = '';
    $validacion = $this->validar_vehiculo($form);
    if ($this->algun_error_vehiculo($validacion))
    {
      echo view('header');
      echo view('sidebar');
      echo view('vehiculo/registrar_vehiculo', $validacion);
      echo view('footer');
    }
    else
    {
      $id_person_afiliado = $form['id_person_afiliado_G'];
      $cod_vehiculo = $form['cod_vehiculo_G'];
      $data_afiliado = $form['data_afiliado_G'];
      $placa = $form['placa_G'];
      $estado = $form['estado_G'];
      $data_conductor = $form['data_conductor_G'];
      $id_person_conductor = $form['id_person_conductor_G'];
      if ($id_person_afiliado == '')
      {
        $info = $this->recuperar_data($cod_vehiculo, $data_afiliado, $id_person_afiliado,
        $placa, $estado, $data_conductor, $id_person_conductor, 'El Veh&iacute; No puede ser registrado sin Afiliado<br>', '');
        echo view('header');
        echo view('sidebar');
        echo view('vehiculo/registrar_vehiculo', $info);
        echo view('footer');
      }
      if ($id_person_conductor == '')
      {
        $this->guardar_vehiculo_sin_conductor($cod_vehiculo, $placa, $estado, $id_person_afiliado);
        echo view('vehiculo/guardar_vehiculo');
      }
      else
      {
        $this->guardar_vehiculo_datos_completos($cod_vehiculo, $placa, $estado, $id_person_afiliado, $id_person_conductor);
        echo view('vehiculo/guardar_vehiculo');
      }
    }
  }
  public function actualizar_datos()
  {
    $form = $this->request->getPost();
    $id_vehiculo = $form['id_vehiculo'];
    $cod_vehiculo = $form['cod_vehiculo'];
    $placa = $form['placa'];
    $estado = $form['estado'];
    $form['error_cod_vehiculo'] = '';
    $form['error_placa'] = '';
    $validacion = $this->validar_actualizacion($form);
    if ($this->algun_error_vehiculo($validacion))
    {
      echo view('header');
      echo view('sidebar');
      echo view('vehiculo/actualizar_vehiculo', $validacion);
      echo view('footer');
    }
    else
    {
      $data_vehiculo = array(
        'id_vehiculo' => $id_vehiculo,
        'cod_vehiculo' => $cod_vehiculo,
        'placa' => $placa,
        'estado_vehiculo' => $estado
      );
      model(VehiculoModel::class)->actualizar($data_vehiculo, $id_vehiculo);
      echo view('vehiculo/actualizar_datos');
    }
  }
    /**
     * Conductores activos (tabla de consulta en los formularios de registro de vehículo:
     * vehiculo/registrar_vehiculo.php y vehiculo/registrar_vehiculo_asignar_afiliado.php).
     *
     * @return list<object>
     */
    public function listar_conductores_activos(): array
    {
        return $this->db->query('SELECT c.cod_conductor, p.nombre_persona, c.brevete, c.estado_conductor FROM tbl_conductor as c LEFT JOIN tbl_persona as p ON c.id_person_conductor=p.id_persona WHERE c.estado_conductor = 1')->getResult();
    }
  public function encontrar_propietario_add()
  {
    $form = $this->request->getPost();
    $data_afiliado = $form['data_afiliado'];
    $id_vehiculo = $form['id_vehiculo'];
    $info = array(
      'id_person' => '',
      'id_vehiculo' => $id_vehiculo,
      'mensaje' => ''
    );
    if ($this->buscar_afiliado($data_afiliado) != '')
    {
      $id_persona = $this->buscar_afiliado($data_afiliado);
      $info['id_person'] = $id_persona;
      $info['mensaje'] = 'Afiliado Encontrado<br>';
      echo view('header');
      echo view('sidebar');
      echo view('vehiculo/encontrar_propietario_add', $info);
      echo view('footer');
    }
    if ($this->buscar_afiliado($data_afiliado) == '')
    {
      echo "<script type=\"text/javascript\">alert(\"No existe el Afiliado o esta inactivo\");</script>";
      $this->pagina_anterior();
    }
  }
  public function encontrar_conductor_add()
  {
    $form = $this->request->getPost();
    $data_conductor = $form['data_conductor'];
    $id_vehiculo = $form['id_vehiculo'];
    $info = array(
      'id_person' => '',
      'id_vehiculo' => $id_vehiculo,
      'mensaje' => ''
    );
    if ($this->buscar_conductor($data_conductor) != '')
    {
      $id_persona = $this->buscar_conductor($data_conductor);
      $info['id_person'] = $id_persona;
      $info['mensaje'] = 'Conductor Encontrado<br>';
      echo view('header');
      echo view('sidebar');
      echo view('vehiculo/encontrar_conductor_add', $info);
      echo view('footer');
    }
    if ($this->buscar_conductor($data_conductor) == '')
    {
      echo "<script type=\"text/javascript\">alert(\"No existe el Conductor o esta inactivo\");</script>";
      $this->pagina_anterior();
    }
  }
  public function getInfoPropietario($data_afiliado, $id_persona, $id_vehiculo, $mensaje)
  {
    $info_afiliado = array(
      'data_afiliado' => $data_afiliado,
      'id_persona' => $id_persona,
      'id_vehiculo' => $id_vehiculo,
      'mensaje' => '<p class="alert alert-info">Afiliado Encontrado</p>'
    );
    return $info_afiliado;
  }
  public function getInfoConductor($data_conductor, $id_persona, $id_vehiculo, $mensaje)
  {
    $info_conductor = array(
      'data_conductor' => $data_conductor,
      'id_persona' => $id_persona,
      'id_vehiculo' => $id_vehiculo,
      'mensaje' => '<p class="alert alert-info">Conductor Encontrado</p>'
    );
    return $info_conductor;
  }
  public function get_info_referencia_responsable($referencia)
  {
    $dividir = explode('-', $referencia);
    $id_person = $dividir[0];
    $id_vehiculo = $dividir[1];
    $info = array(
      'id_person' => $id_person,
      'id_vehiculo' => $id_vehiculo,
    );
    return $info;
  }
  public function cambiar_propietario($referencia)
  {
    $info = $this->get_info_referencia_responsable($referencia);
    echo view('header');
    echo view('sidebar');
    echo view('vehiculo/cambiar_propietario', $info);
    echo view('footer');
  }
  public function cambiar_conductor($referencia)
  {
    $info = $this->get_info_referencia_responsable($referencia);
    echo view('header');
    echo view('sidebar');
    echo view('vehiculo/cambiar_conductor', $info);
    echo view('footer');
  }
  public function encontrar_nuevo_propietario()
  {
    $form = $this->request->getPost();
    $data_afiliado = $form['data_afiliado'];
    $anterior_id_persona = $form['id_person'];
    $id_vehiculo = $form['id_vehiculo'];
    $info = array(
      'anterior_id_person' => $anterior_id_persona,
      'id_person' => '',
      'id_vehiculo' => $id_vehiculo,
      'mensaje' => ''
    );
    if ($this->buscar_afiliado($data_afiliado) != '')
    {
      $id_persona = $this->buscar_afiliado($data_afiliado);
      $info['id_person'] = $id_persona;
      $info['mensaje'] = 'Afiliado Encontrado<br>';
      echo view('header');
      echo view('sidebar');
      echo view('vehiculo/encontrar_nuevo_propietario', $info);
      echo view('footer');
    }
    if ($this->buscar_afiliado($data_afiliado) == '')
    {
      echo "<script type=\"text/javascript\">alert(\"No existe el Afiliado o esta inactivo\");</script>";
      $this->pagina_anterior();
    }
  }
  public function encontrar_nuevo_conductor()
  {
    $form = $this->request->getPost();
    $data_conductor = $form['data_conductor'];
    $anterior_id_persona = $form['id_person'];
    $id_vehiculo = $form['id_vehiculo'];
    $info = array(
      'anterior_id_person' => $anterior_id_persona,
      'id_person' => '',
      'id_vehiculo' => $id_vehiculo,
      'mensaje' => ''
    );
    if ($this->buscar_conductor($data_conductor) != '')
    {
      $id_persona = $this->buscar_conductor($data_conductor);
      $info['id_person'] = $id_persona;
      $info['mensaje'] = 'Conductor Encontrado<br>';
      echo view('header');
      echo view('sidebar');
      echo view('vehiculo/encontrar_nuevo_conductor', $info);
      echo view('footer');
    }
    if ($this->buscar_conductor($data_conductor) == '')
    {
      echo "<script type=\"text/javascript\">alert(\"No existe el Conductor o esta inactivo\");</script>";
      $this->pagina_anterior();
    }
  }
}
