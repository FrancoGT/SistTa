<?php

namespace App\Controllers;

use App\Models\ConfiguracionModel;
use App\Models\PeriodoModel;
use App\Models\Realiza_PagoModel;
use App\Models\ServicioModel;

class Configuracion extends BaseController
{
    //Validacion
    public function algun_error_servicio($form)
    {
      $algun_error = false;
      if ($form['error_nombre_servicio'] != '')
      {
        $algun_error = true;
      }
      return $algun_error;
    }
    public function nombre_servicio_repetido($nombre_servicio)
    {
      $nombre_servicio_repetido = '';
      $query = $this->db->query('SELECT nombre_servicio FROM `tbl_servicio` WHERE nombre_servicio=?', [$nombre_servicio]);
      foreach ($query->getResult() as $row)
      {
        $nombre_servicio_repetido = $row->nombre_servicio;
      }
      return $nombre_servicio_repetido == $nombre_servicio;
    }
    public function validar_servicio($form)
    {
      $nombre_servicio = $form ['nombre_servicio'];
      if ($this->nombre_servicio_repetido($nombre_servicio))
      {
        $form['error_nombre_servicio'] = '<h3>Ya existe un Servicio con el nombre: '.esc($nombre_servicio).'</h3>';
      }
      return $form;
    }
    public function validar_servicio_actualizar($data_servicio, $criterio, $id_servicio)
    {
      $query = $this->db->query('SELECT * FROM `tbl_servicio` WHERE ' . sql_ident($criterio) . ' = ? AND id_servicio<>?', [$data_servicio, $id_servicio]);
  		return $query->getNumRows();
    }
    //Gestion: Servicios
    public function get_id_configuracion()
    {
      $id_configuracion = '';
      $query = $this->db->query('SELECT id_configuracion FROM `tbl_configuracion`');
      foreach ($query->getResult() as $row)
      {
        $id_configuracion = $row->id_configuracion;
      }
      return $id_configuracion;
    }
    public function recuperarInfoConfiguracion()
    {
        $query = $this->db->query('SELECT empresa,RUC, direccion_empresa, cod_configuracion FROM tbl_configuracion');
        foreach($query->getResult() as $row)
        {
            $datos['empresa'] = $row->empresa;
            $datos['RUC'] = $row->RUC;
            $datos['direccion_empresa'] = $row->direccion_empresa;
            $datos['cod_configuracion'] = $row->cod_configuracion;
        }
        return $datos;
    }
    public function index()
    {
        $info = $this->recuperarInfoConfiguracion();
        $info['servicios'] = $this->listar_servicios();
        echo view('header');
        echo view('sidebar');
        echo view('configuracion/configuracion', $info);
        echo view('footer');
    }

    public function pagina_anterior()
    {
        echo "<script type='text/javascript'>";
        echo "window.history.back(-1)";
        echo "</script>";
    }
    public function actualizar_configuracion()
    {
      $form = $this->request->getPost();
      $empresa = $form['empresa'];
      $ruc = $form['ruc'];
      $direccion_empresa = $form['direccion'];
      $cod_configuracion = $form['codigo'];
      $configuracion_data = array(
        'empresa' => $empresa,
        'ruc' => $ruc,
        'direccion_empresa' => $direccion_empresa,
        'cod_configuracion' => $cod_configuracion,
      );
      $id_configuracion = $this->get_id_configuracion();
      model(ConfiguracionModel::class)->actualizar($configuracion_data, $id_configuracion);
      echo view('configuracion/actualizar_datos_configuracion');
    }
    public function volver()
    {
        echo view('header');
        echo view('sidebar');
        echo view('configuracion/volver');
        echo view('footer');
    }
    public function formato_a_m_d($date)
    {
        $date = date("Y-n-j", strtotime($date));
        return $date;
    }
    public function fecha_actual()
    {
        $date = new \DateTime("", new \DateTimeZone('America/Lima'));
        return date("Y-m-d", $date->format('U'));
    }
    public function registrar_servicio()
    {
        echo view('header');
        echo view('sidebar');
        echo view('configuracion/registrar_servicio');
        echo view('footer');
    }
    public function guardar_servicio()
    {
        $form = $this->request->getPost();
        $form['error_nombre_servicio'] = '';
        $validacion = $this->validar_servicio($form);
        if ($this->algun_error_servicio($validacion))
        {
          echo view('header');
          echo view('sidebar');
          echo view('configuracion/registrar_servicio', $validacion);
          echo view('footer');
        }
        else
        {
          $nombre_servicio = $form['nombre_servicio'];
          $estado_servicio = $form['estado'];
          $pago_diario = $form['pago_diario'];
          $aplica = $form['aplica'];
          $data = array(
              'id_servicio' => '',
              'nombre_servicio' => $nombre_servicio,
              'estado_servicio' => $estado_servicio,
              'pago_diario' => $pago_diario,
              'aplica' => $aplica
          );
          model(ServicioModel::class)->guardar($data);
          echo view('configuracion/guardar_servicio');
        }
    }
    public function actualizar_datos_servicio()
    {
      $form = $this->request->getPost();
      $form['error_nombre_servicio'] = "<p class='alert alert-danger'>Ya existe el nombre de servicio: ".esc($form['nombre_servicio'])."</p>";
      $id_servicio = $form['id_servicio'];
      $nombre_servicio = $form['nombre_servicio'];
      $estado_servicio = $form['estado'];
      $pago_diario = $form['pago_diario'];
      $aplica = $form['aplica'];
      if ($this->validar_servicio_actualizar($nombre_servicio, 'nombre_servicio', $id_servicio) == 1)
      {
        echo view('header');
        echo view('sidebar');
        echo view('configuracion/actualizar_servicio', $form);
        echo view('footer');
      }
      else
      {
        $data = array(
            'nombre_servicio' => $nombre_servicio,
            'estado_servicio' => $estado_servicio,
            'pago_diario' => $pago_diario,
            'aplica' => $aplica
        );
        model(ServicioModel::class)->actualizar($data, $id_servicio);
        echo view('configuracion/actualizar_datos_servicio');
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
    public function buscar_conductor($data_conductor)
    {
      $datos_conductor = array(
        'id_person_conductor' => '',
        'id_servicio' => '',
        );
      $encontrado = array(
        'id_person_conductor' => '',
        'id_servicio' => '',
        );
      $datos_conductor = $this->criterio_bus_conductor('documento', $data_conductor);
      if ($datos_conductor['id_person_conductor'] != '')
      {
        $encontrado = $datos_conductor;
      }
      $datos_conductor = $this->criterio_bus_conductor('cod_conductor', $data_conductor);
      if ($datos_conductor['id_person_conductor'] != '')
      {
        $encontrado = $datos_conductor;
      }
      return $encontrado;
    }
    public function criterio_bus_conductor($criterio, $data_conductor)
    {
      $datos_conductor =  array(
        'id_person_conductor' => '',
        'id_servicio' => '',
        );
      $table = 'c';
      if ($criterio == 'documento')
      {
        $table = 'p';
      }
      $buscar = $this->db->query('SELECT c.id_person_conductor FROM tbl_persona as p LEFT JOIN tbl_conductor as c ON p.id_persona=c.id_person_conductor WHERE ' . sql_ident($table) . '.' . sql_ident($criterio) . ' =? AND c.estado_conductor=1', [$data_conductor]);
      foreach ($buscar->getResult() as $key)
      {
        $datos_conductor['id_person_conductor'] = str_replace('"','',json_encode($key->id_person_conductor));
      }
      return $datos_conductor;
    }
    public function buscar_afiliado($data_afiliado)
    {
      $datos_afiliado = array(
        'id_person_afiliado' => '',
        'id_servicio' => ''
        );
      $encontrado = array(
        'id_person_afiliado' => '',
        'id_servicio' => ''
        );
      $datos_afiliado = $this->criterio_bus_afiliado('documento', $data_afiliado);
      if ($datos_afiliado['id_person_afiliado'] != '')
      {
        $encontrado = $datos_afiliado;
      }
      $datos_afiliado = $this->criterio_bus_afiliado('cod_afiliado', $data_afiliado);
      if ($datos_afiliado['id_person_afiliado'] != '')
      {
        $encontrado = $datos_afiliado;
      }
      $datos_afiliado = $this->criterio_bus_afiliado('id_person_afiliado', $data_afiliado);
      if ($datos_afiliado['id_person_afiliado'] != '')
      {
        $encontrado = $datos_afiliado;
      }
      return $encontrado;
    }
    public function criterio_bus_afiliado($criterio, $data_afiliado)
    {
      $datos_afiliado = array(
        'id_person_afiliado' => '',
        'id_servicio' => ''
        );
      $table = 'p';
      if ($criterio == 'cod_afiliado' || $criterio == 'cod_afiliado' || $criterio == 'id_person_afiliado')
      {
        $table = 'a';
      }
      $buscar = $this->db->query('SELECT a.id_person_afiliado FROM tbl_afiliado as a LEFT JOIN tbl_persona as p ON a.id_person_afiliado=p.id_persona WHERE ' . sql_ident($table) . '.' . sql_ident($criterio) . ' = ?  AND a.estado_afiliado=1', [$data_afiliado]);
      foreach ($buscar->getResult() as $key)
      {
        $datos_afiliado['id_person_afiliado'] = str_replace('"','',json_encode($key->id_person_afiliado));
      }
      return $datos_afiliado;
  }
  public function buscar_vehiculo($data_vehiculo)
  {
      $datos_vehiculo = array(
        'id_vehiculo' => '',
        'id_servicio' => ''
        );
      $encontrado = array(
        'id_vehiculo' => '',
        'id_servicio' => ''
        );
      $datos_vehiculo = $this->criterio_bus_vehiculo('placa', $data_vehiculo);
      if ($datos_vehiculo['id_vehiculo'] != '')
      {
        $encontrado = $datos_vehiculo;
      }
      $datos_vehiculo = $this->criterio_bus_vehiculo('cod_vehiculo', $data_vehiculo);
      if ($datos_vehiculo['id_vehiculo'] != '')
      {
        $encontrado = $datos_vehiculo;
      }
      return $encontrado;
  }
    public function criterio_bus_vehiculo($criterio, $data_vehiculo)
    {
      $datos_vehiculo = array(
        'id_vehiculo' => '',
        'id_servicio' => ''
        );
      $buscar = $this->db->query('SELECT id_vehiculo FROM tbl_vehiculo WHERE ' . sql_ident($criterio) . ' = ? AND estado_vehiculo=1', [$data_vehiculo]);
      foreach ($buscar->getResult() as $key)
      {
        $datos_vehiculo['id_vehiculo'] = str_replace('"','',json_encode($key->id_vehiculo));
      }
      return $datos_vehiculo;
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
        echo view('configuracion/encontrar_servicio_afiliado', $Servicio_Encontrado);
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
      $id_person_conductor = $form['id_auxiliar'];
      $Servicio_No_Encontrado = array(
          'id_servicio' => '',
          'id_person_afiliado' => '',
          'id_person_conductor' => '',
          'id_vehiculo' => ''
        );
      if ($Servicio_No_Encontrado != $this->buscar_servicio($data_servicio, '2'))
      {
        $Servicio_Encontrado = $this->buscar_servicio($data_servicio, '2');
        $Servicio_Encontrado['id_auxiliar'] = $id_person_conductor;
        echo view('header');
        echo view('sidebar');
        echo view('configuracion/encontrar_servicio_conductor', $Servicio_Encontrado);
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
        echo view('configuracion/encontrar_servicio_conductor', $Servicio_Encontrado);
        echo view('footer');
      }
      else
      {
        echo '<script type="text/javascript">
          alert("Servicio No existe o esta Inactivo o No se aplica para Unidades");
          </script>';
        echo view('header');
        echo view('sidebar');
        echo view('cobranza/cobranza');
        echo view('footer');
        $this->pagina_anterior();
      }
    }
    public function encontrar_afiliado()
    {
        $form = $this->request->getPost();
        $data_afiliado = $form['bus_afi'];
        $id_servicio = $form['id_servicio'];
        $Afiliado_No_Encontrado = array(
          'id_person_afiliado' => '',
          'id_servicio' => ''
        );
        if ($this->buscar_afiliado($data_afiliado) != $Afiliado_No_Encontrado)
        {
            $Afiliado_Encontrado = $this->buscar_afiliado($data_afiliado);
            $Afiliado_Encontrado['id_servicio'] = $id_servicio;
            echo view('header');
            echo view('sidebar');
            echo view('configuracion/encontrar_afiliado', $Afiliado_Encontrado);
            echo view('footer');
        }
        else
        {
            echo '<script type="text/javascript">
            alert("El Afiliado no existe o esta inactivo");
            </script>';
            $this->pagina_anterior();
        }
    }
    public function encontrar_conductor()
    {
        $form = $this->request->getPost();
        $data_conductor = $form['bus_con'];
        $id_servicio = $form['id_servicio'];
        $Conductor_No_Encontrado =  array(
          'id_person_conductor' => '',
          'id_servicio' => ''
        );
        if ($this->buscar_conductor($data_conductor) != $Conductor_No_Encontrado)
        {
            $Conductor_Encontrado = $this->buscar_conductor($data_conductor);
            $Conductor_Encontrado['id_servicio'] = $id_servicio;
            echo view('header');
            echo view('sidebar');
            echo view('configuracion/encontrar_conductor', $Conductor_Encontrado);
            echo view('footer');
        }
        else
        {
            echo '<script type="text/javascript">
            alert("El Conductor no existe o esta inactivo");
            </script>';
            $this->pagina_anterior();
        }
    }
    public function encontrar_vehiculo()
    {
        $form = $this->request->getPost();
        $data_vehiculo = $form['bus_vehi'];
        $id_servicio = $form['id_servicio'];
        $Vehiculo_No_Encontrado =  array(
          'id_vehiculo' => '',
          'id_servicio' => ''
        );
        if ($this->buscar_vehiculo($data_vehiculo) != $Vehiculo_No_Encontrado)
        {
            $Vehiculo_Encontrado = $this->buscar_vehiculo($data_vehiculo);
            $Vehiculo_Encontrado['id_servicio'] = $id_servicio;
            echo view('header');
            echo view('sidebar');
            echo view('configuracion/encontrar_vehiculo', $Vehiculo_Encontrado);
            echo view('footer');
        }
        else
        {
            echo '<script type="text/javascript">
            alert("El Vehiculo no existe o esta inactivo");
            </script>';
            $this->pagina_anterior();
        }
    }
    public function guardar_servicio_conductor()
    {
        $form = $this->request->getPost();
        $id_person_conductor = $form['id_person_conductor'];
        $id_serv = $form['id_serv'];
        $inicio_pago = $form['inicio_pago'];
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
        echo view('configuracion/guardar_deuda_servicio');
    }
    public function guardar_servicio_afiliado()
    {
      $form = $this->request->getPost();
      $id_person_afiliado = $form['id_person_afiliado'];
      $id_serv = $form['id_serv'];
      $inicio_pago = $form['inicio_pago'];
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
      echo view('configuracion/guardar_deuda_servicio');
    }
    public function guardar_servicio_vehiculo()
    {
        $form = $this->request->getPost();
        $id_vehiculo = $form['id_vehi'];
        $id_serv = $form['id_serv'];
        $inicio_pago = $form['inicio_pago'];
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
        echo view('configuracion/guardar_deuda_servicio');
    }
    public function aplicar_conductor($id_servicio)
    {
        $datos['nombre_serv'] = $this->get_nombre_servicio($id_servicio);
        $datos['id_servicio'] = $id_servicio;
        echo view('header');
        echo view('sidebar');
        echo view('configuracion/aplicar_conductor', $datos);
        echo view('footer');
    }
    public function aplicar_afiliado($id_servicio)
    {
        $datos['nombre_serv'] = $this->get_nombre_servicio($id_servicio);
        $datos['id_servicio'] = $id_servicio;
        echo view('header');
        echo view('sidebar');
        echo view('configuracion/aplicar_afiliado', $datos);
        echo view('footer');
    }
    public function aplicar_unidad($id_servicio)
    {
        $datos['nombre_serv'] = $this->get_nombre_servicio($id_servicio);
        $datos['id_servicio'] = $id_servicio;
        echo view('header');
        echo view('sidebar');
        echo view('configuracion/aplicar_unidad', $datos);
        echo view('footer');
    }
    public function recuperar_data_servicio($id_servicio)
    {
      $data_servicio = array(
        'id_servicio' => '',
        'nombre_servicio' => '',
        'pago_diario' => '',
        'aplica' => '',
        'estado_servicio' => '',
      );
      $query = $this->db->query('SELECT id_servicio, nombre_servicio, pago_diario, aplica, estado_servicio FROM `tbl_servicio` WHERE id_servicio=?', [$id_servicio]);
      foreach($query->getResult() as $row)
      {
        $data_servicio['id_servicio'] = str_replace('"','',json_encode($row->id_servicio));
        $data_servicio['nombre_servicio'] = str_replace('"','',json_encode($row->nombre_servicio));
        $data_servicio['pago_diario'] = str_replace('"','',json_encode($row->pago_diario));
        $data_servicio['aplica'] = str_replace('"','',json_encode($row->aplica));
        $data_servicio['estado_servicio'] = str_replace('"','',json_encode($row->estado_servicio));
      }
      return $data_servicio;
    }
    public function actualizar_servicio($id_servicio)
    {
      $data_servicio = $this->recuperar_data_servicio($id_servicio);
      echo view('header');
      echo view('sidebar');
      echo view('configuracion/actualizar_servicio', $data_servicio);
      echo view('footer');
    }
    /**
     * Servicios registrados (la tabla se dibuja en configuracion/configuracion.php).
     *
     * @return list<object>
     */
    public function listar_servicios(): array
    {
        return $this->db->query('SELECT id_servicio, nombre_servicio, pago_diario, aplica, estado_servicio FROM `tbl_servicio`')->getResult();
    }
    public function agregar_servicio_conductor($id_person_conductor)
    {
      $Conductor_Encontrado = $this->buscar_conductor($id_person_conductor);
      echo view('header');
      echo view('sidebar');
      echo view('configuracion/agregar_servicio_conductor', $Conductor_Encontrado);
      echo view('footer');
    }
    public function agregar_servicio_afiliado($id_person_afiliado)
    {
      $Afiliado_Encontrado = $this->buscar_afiliado($id_person_afiliado);
      echo view('header');
      echo view('sidebar');
      echo view('configuracion/agregar_servicio_afiliado', $Afiliado_Encontrado);
      echo view('footer');
    }
    public function agregar_servicio_vehiculo($id_vehiculo)
    {
      $Vehiculo_Encontrado = $this->buscar_vehiculo($id_vehiculo);
      echo view('header');
      echo view('sidebar');
      echo view('configuracion/agregar_servicio_vehiculo', $Vehiculo_Encontrado);
      echo view('footer');
    }
}
