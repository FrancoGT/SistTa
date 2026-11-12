<?php

namespace App\Controllers;

use App\Models\AfiliadoModel;
use App\Models\PersonaModel;
use App\Models\Responsable_VehiculoModel;

class Afiliado extends BaseController
{
    public function index()
    {
        echo view('header');
        echo view('sidebar');
        echo view('afiliado/afiliado', ['afiliados' => $this->listar_afiliados()]);
        echo view('footer');
    }
    public function pagina_anterior()
    {
        echo "<script type='text/javascript'>";
        echo "window.history.back(-1)";
        echo "</script>";
    }
    public function getIdPersona($documento)
    {
      $id_persona = '';
      $query = $this->db->query('SELECT id_persona FROM `tbl_persona` WHERE documento=?', [$documento]);
      foreach ($query->getResult() as $row)
      {
        $id_persona = $row->id_persona;
      }
      return $id_persona;
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
    public function recuperar_data_afiliado($id_afiliado)
    {
        $data_afiliado = array(
            'id_afiliado' => $id_afiliado,
            'id_persona' => '',
            'cod_afiliado' => '',
            'nombre' => '',
            'documento' => '',
            'direccion' => '',
            'telefono' => '',
            'estado_afiliado' => ''
        );
        $query = $this->db->query('SELECT a.id_afiliado, a.cod_afiliado, p.nombre_persona, p.documento, p.direccion_persona, p.telefono_persona, p.id_persona, a.estado_afiliado FROM tbl_afiliado as a LEFT JOIN tbl_persona as p ON a.id_person_afiliado=p.id_persona WHERE a.id_afiliado=?', [$id_afiliado]);
        foreach ($query->getResult() as $row)
        {
            $data_afiliado['id_afiliado'] = str_replace('"','',json_encode($row->id_afiliado));
            $data_afiliado['id_persona'] = str_replace('"','',json_encode($row->id_persona));
            $data_afiliado['cod_afiliado'] = str_replace('"','',json_encode($row->cod_afiliado));
            $data_afiliado['nombre'] = str_replace('"','',json_encode($row->nombre_persona));
            $data_afiliado['documento'] = str_replace('"','',json_encode($row->documento));
            $data_afiliado['direccion'] = str_replace('"','',json_encode($row->direccion_persona));
            $data_afiliado['telefono'] = str_replace('"','',json_encode($row->telefono_persona));
            $data_afiliado['estado_afiliado'] = str_replace('"','',json_encode($row->estado_afiliado));
        }
        return $data_afiliado;
    }
    /**
     * Afiliados registrados para el listado (la tabla se dibuja en afiliado/afiliado.php).
     * "vehiculos" cuenta los vehículos de los que la persona es propietaria
     * (misma regla que antes: por nombre de persona y es_propietario=1).
     *
     * @return list<object>
     */
    public function listar_afiliados(): array
    {
        return $this->db->query('SELECT a.id_afiliado, a.id_person_afiliado, a.cod_afiliado, p.nombre_persona, p.documento, a.estado_afiliado,
                (SELECT COUNT(*) FROM tbl_responsable_vehiculo as r LEFT JOIN tbl_persona as p2 ON r.id_person_responsable = p2.id_persona
                  WHERE p2.nombre_persona = p.nombre_persona AND r.es_propietario = 1) AS vehiculos
            FROM tbl_afiliado as a LEFT JOIN tbl_persona as p ON a.id_person_afiliado=p.id_persona ORDER BY p.nombre_persona')->getResult();
    }
    public function es_conductor($id_persona)
    {
      $id_conductor = '';
      $query = $this->db->query('SELECT id_conductor FROM `tbl_conductor` WHERE id_person_conductor=?', [$id_persona]);
      foreach ($query->getResult() as $row)
      {
        $id_conductor = $row->id_conductor;
      }
      return $id_conductor;
    }
    public function get_nombre_afiliado($id_afiliado)
    {
        $query = $this->db->query('SELECT p.nombre_persona FROM tbl_persona as p LEFT JOIN tbl_afiliado as c ON c.id_person_afiliado=p.id_persona WHERE c.id_afiliado=?', [$id_afiliado]);
        foreach($query->getResult() as $row)
        {
            $nombre_afi = $row->nombre_persona;
        }
        return $nombre_afi;
    }
    public function get_id_person_afiliado($id_afiliado)
    {
        $query = $this->db->query('SELECT p.id_persona FROM tbl_persona as p LEFT JOIN tbl_afiliado as a ON a.id_person_afiliado=p.id_persona WHERE a.id_afiliado=?', [$id_afiliado]);
        foreach($query->getResult() as $row)
        {
            $id_person_afi = $row->id_persona;
        }
        return $id_person_afi;
    }
    public function get_cod_afiliado($id_afiliado)
    {
        $query = $this->db->query('SELECT a.cod_afiliado FROM tbl_persona as p LEFT JOIN tbl_afiliado as a ON a.id_person_afiliado=p.id_persona WHERE a.id_afiliado=?', [$id_afiliado]);
        foreach($query->getResult() as $row)
        {
            $cod_afi = $row->cod_afiliado;
        }
        return $cod_afi;
    }
    //validaciones: Datos de afiliado repetidos
    public function algun_error_validar_afiliado($form)
    {
      $algun_error = false;
      if ($form['error_cod_afiliado'] != '')
      {
        $algun_error = true;
      }
      if ($form['error_documento'] != '')
      {
        $algun_error = true;
      }
      if ($form['error_telefono'] != '')
      {
        $algun_error = true;
      }
      return $algun_error;
    }
    public function validar_afiliado($form)
    {
      $documento = $form['documento'];
      $telefono = $form['telefono'];
      $cod_afiliado = $form['cod_afiliado'];
      if (model(PersonaModel::class)->num_rows_afiliado('documento', $documento) == 1)
      {
        $form['error_documento'] = "<p class='alert alert-danger'>El Documento de Identidad: ".esc($documento)." ya fue asignado para otra Persona</p>";
      }
      if (model(PersonaModel::class)->num_rows_afiliado('telefono_persona', $telefono) == 1)
      {
        $form['error_telefono'] = "<p class='alert alert-danger'>El Tel&eacute;fono: ".esc($telefono)." ya fue asignado para otra Persona</p>";
      }
      if (model(AfiliadoModel::class)->num_rows_registrar_afiliado('cod_afiliado', $cod_afiliado))
      {
        $form['error_cod_afiliado'] = "<p class='alert alert-danger'>El C&oacute;digo de Afiliado: ".esc($cod_afiliado)." ya existe</p>";
      }
      return $form;
    }
  	public function validar_actualizacion($form)
  	{
      $id_afiliado = $form['id_afiliado'];
      $id_persona = $form['id_persona'];
      $cod_afiliado = $form['cod_afiliado'];
      $telefono = $form['telefono'];
      $documento = $form['documento'];
      //si ya existe otro registro con ese dato
  		if (model(AfiliadoModel::class)->num_rows_actualizacion_persona('documento', $documento, $id_afiliado) == 1)
  		{
        $form['error_documento'] = "<p class='alert alert-danger'>El Documento de Identidad: ".esc($documento)." ya fue asignado para otra Persona</p>";
  		}
      if (model(AfiliadoModel::class)->num_rows_actualizacion_persona('telefono_persona', $telefono, $id_afiliado) == 1)
  		{
        $form['error_telefono'] = "<p class='alert alert-danger'>El Tel&eacute;fono: ".esc($telefono)." ya fue asignado para otra Persona</p>";
  		}
      if (model(AfiliadoModel::class)->num_rows_actualizacion_afiliado('cod_afiliado', $cod_afiliado, $id_afiliado) == 1)
  		{
  			$form['error_cod_afiliado'] = "<p class='alert alert-danger'>El C&oacute;digo de Afiliado: ".esc($cod_afiliado)." ya existe</p>";
  		}
      return $form;
  	}
    //Gestion de afiliados
    public function registrar_afiliado()
    {
        echo view('header');
        echo view('sidebar');
        echo view('afiliado/registrar_afiliado');
        echo view('footer');
    }
    public function actualizar_afiliado($id_afiliado)
    {
        $data_afiliado = $this->recuperar_data_afiliado($id_afiliado);
        echo view('header');
        echo view('sidebar');
        echo view('afiliado/actualizar_afiliado', $data_afiliado);
        echo view('footer');
    }
    public function actualizar_datos()
    {
        $form = $this->request->getPost();
        $form['error_cod_afiliado'] = '';
        $form['error_documento'] = '';
        $form['error_telefono'] = '';
        $validacion = $this->validar_actualizacion($form);
        if ($this->algun_error_validar_afiliado($validacion))
        {
          echo view('header');
          echo view('sidebar');
          echo view('afiliado/actualizar_afiliado', $validacion);
          echo view('footer');
        }
        else
        {
          $this->actualizar_persona($form);
          $cod_afiliado = $form['cod_afiliado'];
          $id_afiliado = $form['id_afiliado'];
          $id_persona = $form['id_persona'];
          $estado_afiliado = $form['estado_nuevo'];
          $data = array(
                  'cod_afiliado' => $cod_afiliado,
                  'estado_afiliado' => $estado_afiliado
          );
          model(AfiliadoModel::class)->actualizar($data, $id_afiliado);
          echo view('afiliado/actualizar_datos');
        }
    }
    public function insertar_responable_vehiculo($id_vehi, $id_person_responsable)
    {
        $data = array(
            'id_responsable' => '',
            'id_vehi' => $id_vehi,
            'id_person_responsable' => $id_person_responsable,
            'es_propietario' => '1',
            'conduce_vehiculo' => '0',
            'estado_responsable_vehiculo' => '1'
        );
        model(Responsable_VehiculoModel::class)->guardar($data);
    }
    public function buscar_vehiculo($data_vehiculo)
    {
      $datos_vehiculo = array(
            "id_vehiculo" => "",
            "cod_vehiculo" => "",
            "placa" => "",
            "id_afi" => "",
            "id_person_afi" => "",
            "cod_afi" => "",
            "nom_afi" => "",
        );
      $encontrado = array(
            "id_vehiculo" => "",
            "cod_vehiculo" => "",
            "placa" => "",
            "id_afi" => "",
            "id_person_afi" => "",
            "cod_afi" => "",
            "nom_afi" => "",
        );
      $datos_vehiculo = $this->criterio_bus_vehiculo('cod_vehiculo', $data_vehiculo);
      if ($datos_vehiculo['id_vehiculo'] != '')
        $encontrado = $datos_vehiculo;
      if ($datos_vehiculo['id_vehiculo'] == '')
        $datos_vehiculo = $this->criterio_bus_vehiculo('placa', $data_vehiculo);
      $encontrado = $datos_vehiculo;
      return $encontrado;
    }
    public function criterio_bus_vehiculo($criterio, $data_vehiculo)
    {
      $datos_vehiculo = array(
            "id_vehiculo" => "",
            "cod_vehiculo" => "",
            "placa" => "",
            "id_afi" => "",
            "id_person_afi" => "",
            "cod_afi" => "",
            "nom_afi" => "",
        );
      $buscar = $this->db->query('SELECT id_vehiculo, placa, cod_vehiculo FROM tbl_vehiculo WHERE ' . sql_ident($criterio) . ' =? AND estado_vehiculo=1', [$data_vehiculo]);
      foreach ($buscar->getResult() as $key)
      {
        $datos_vehiculo['id_vehiculo'] = str_replace('"','',json_encode($key->id_vehiculo));
        $datos_vehiculo['cod_vehiculo'] = str_replace('"','',json_encode($key->cod_vehiculo));
        $datos_vehiculo['placa'] = str_replace('"','',json_encode($key->placa));
      }
      return $datos_vehiculo;
    }
    public function guardar_agregar()
    {
        $form = $this->request->getPost();
        $id_vehi = $form['id_vehiculo'];
        $id_person_responsable = $form['id_person_afi'];
        if (model(Responsable_VehiculoModel::class)->existe_responsable($id_person_responsable, $id_vehi))
        {
          model(Responsable_VehiculoModel::class)->reasignar_propietario($id_person_responsable, $id_vehi);
        }
        else
        {
          $this->insertar_responable_vehiculo($id_vehi, $id_person_responsable);
        }
        echo view('afiliado/afiliado_asignado');
    }
    public function actualizar_vehiculo($id_afiliado)
    {
        $datos['id_afi'] = $id_afiliado;
        $datos['nombre_afi'] = $this->get_nombre_afiliado($id_afiliado);
        echo view('header');
        echo view('sidebar');
        echo view('afiliado/actualizar_vehiculo', $datos);
        echo view('footer');
    }
    public function registrar_vehiculo_afiliado($id_person_afiliado)
    {
        $datos['id_person_afiliado'] = $id_person_afiliado;
        echo view('header');
        echo view('sidebar');
        echo view('vehiculo/registrar_vehiculo_afiliado', $datos);
        echo view('footer');
    }
    public function encontrar_vehiculo()
    {
        $form = $this->request->getPost();
        $data_vehiculo = $form['data_vehiculo'];
        $id_afi = $form['id_afi'];
        $Vehiculo_No_Encontrado = array(
            "id_vehiculo" => "",
            "cod_vehiculo" => "",
            "placa" => "",
            "id_afi" => "",
            "id_person_afi" => "",
            "cod_afi" => "",
            "nom_afi" => "",
        );
        $Vehiculo_Encontrado = array(
            "id_vehiculo" => "",
            "cod_vehiculo" => "",
            "placa" => "",
            "id_afi" => "",
            "id_person_afi" => "",
            "cod_afi" => "",
            "nom_afi" => "",
        );
        if ($Vehiculo_No_Encontrado == $this->buscar_vehiculo($data_vehiculo))
        {
            echo "<script type=\"text/javascript\">alert(\"No existe el veh&iacute;culo o esta inactivo\");</script>";
            $this->pagina_anterior();
        }
        else
        {
            $Vehiculo_Encontrado = $this->buscar_vehiculo($data_vehiculo);
            $Vehiculo_Encontrado['id_afi'] = $id_afi;
            $Vehiculo_Encontrado['id_person_afi'] = $this->get_id_person_afiliado($id_afi);
            $Vehiculo_Encontrado['cod_afi'] = $this->get_cod_afiliado($id_afi);
            $Vehiculo_Encontrado['nom_afi'] = $this->get_nombre_afiliado($id_afi);
            //si el vehiculo ya fue asignado al afiliado
            if (model(Responsable_VehiculoModel::class)->ya_es_propietario($Vehiculo_Encontrado['id_person_afi'], $Vehiculo_Encontrado['id_vehiculo']))
            {
              echo "<script type=\"text/javascript\">alert(\"El Vehiculo ya fue asignado al Afiliado\");</script>";
              $this->pagina_anterior();
            }
            else
            {
              echo view('header');
              echo view('sidebar');
              echo view('afiliado/encontrar_vehiculo', $Vehiculo_Encontrado);
              echo view('footer');
            }
        }
    }

    public function insertar_persona($form)
    {
        $nombre = $form['nombre'];
        $documento = $form['documento'];
        $direccion = $form['direccion'];
        $telefono = $form['telefono'];
        $data = array(
            'id_persona' => '',
            'nombre_persona' => $nombre,
            'documento' => $documento,
            'direccion_persona' => $direccion,
            'telefono_persona' => $telefono,
        );
        model(PersonaModel::class)->guardar($data);
    }
    public function actualizar_persona($form)
    {
        $id_persona = $form['id_persona'];
        $nombre_persona = $form['nombre'];
        $documento = $form['documento'];
        $direccion_persona = $form['direccion'];
        $telefono_persona = $form['telefono'];
        $data = array(
            'nombre_persona' => $nombre_persona,
            'documento' => $documento,
            'direccion_persona' => $direccion_persona,
            'telefono_persona' => $telefono_persona,
        );
        model(PersonaModel::class)->actualizar($data, $id_persona);
    }
    public function guardar_afiliado()
    {
        $form = $this->request->getPost();
        $form['error_cod_afiliado'] = '';
        $form['error_documento'] = '';
        $form['error_telefono'] = '';
        $validacion = $this->validar_afiliado($form);
        if ($this->algun_error_validar_afiliado($validacion))
        {
          echo view('header');
          echo view('sidebar');
          echo view('afiliado/registrar_afiliado', $validacion);
          echo view('footer');
        }
        else
        {
          $documento = $form['documento'];
          $query = $this->db->query('SELECT COUNT(*) FROM `tbl_persona` WHERE documento=?', [$documento]);
          $count = json_encode($query->getResult());
          $count = json_encode($query->getResult());
          $resultado = preg_replace("/[^0-9]/", "", $count);
          if ($resultado == '0')
          {
              $this->insertar_persona($form);
          }
          $cod_afiliado = $form['cod_afiliado'];
          $estado_afiliado = $form['estado'];
          $id_persona = $this->getIdPersona($documento);
          $data = array(
                  'id_afiliado' => '',
                  'id_person_afiliado' => $id_persona,
                  'cod_afiliado' => $cod_afiliado,
                  'estado_afiliado' => $estado_afiliado
          );
          echo "<script>javascript:alert('Afiliado Guardado Exitosamente'</script>";
          model(AfiliadoModel::class)->guardar($data);
          echo view('afiliado/guardar_afiliado');
        }
    }
}
