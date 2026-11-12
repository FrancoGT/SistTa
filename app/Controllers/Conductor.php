<?php

namespace App\Controllers;

use App\Models\AfiliadoModel;
use App\Models\ConductorModel;
use App\Models\PersonaModel;
use App\Models\Responsable_VehiculoModel;

class Conductor extends BaseController
{
    public function index()
    {
        echo view('header');
        echo view('sidebar');
        echo view('conductor/conductor', ['conductores' => $this->listar_conductores()]);
        echo view('footer');
    }
    public function pagina_anterior()
    {
        echo "<script type='text/javascript'>";
        echo "window.history.back(-1)";
        echo "</script>";
    }
    public function es_afiliado($id_persona)
    {
      return model(ConductorModel::class)->existe_afiliado($id_persona);
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
    public function getIdPersonConductor($id_conductor)
    {
      $id_person_conductor = '';
      $query = $this->db->query('SELECT id_person_conductor FROM `tbl_conductor` WHERE id_conductor=?', [$id_conductor]);
      foreach ($query->getResult() as $row)
      {
        $id_person_conductor = $row->id_person_conductor;
      }
      return $id_person_conductor;
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
    public function getNombre_foto($foto)
    {
      if ($foto == '')
      {
        echo "<p class='alert alert-danger'>No hay foto a&uacute;n</p>";
      }
      else
      {
        $strArray = explode('/',$foto);
        $lastElement = end($strArray);
        echo '<img src="'.base_url().'assets/img/'.esc($lastElement).'" />';
      }
    }
    public function file_get_file_path($foto_anterior)
    {
      $name = $this->getNombre_foto($foto_anterior);
      return base_url($name);
    }
    //validaciones: Datos de conductor repetidos
    public function algun_error_validar_conductor($form)
    {
      $algun_error = false;
      if ($form['error_cod_conductor'] != '')
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
      if ($form['error_brevete'] != '')
      {
        $algun_error = true;
      }
      if ($form['error_afiliado'] != '')
      {
        $algun_error = true;
      }
      return $algun_error;
    }
    public function validar_conductor($form)
    {
      $cod_conductor = $form['cod_conductor'];
      $documento = $form['documento'];
      $telefono = $form['telefono_persona'];
      $brevete = $form['brevete'];
      $nuevo_afiliado = $form['nuevo_afiliado'];
      if (model(ConductorModel::class)->num_rows_registrar_conductor('cod_conductor', $cod_conductor))
      {
        $form['error_cod_conductor'] = "<p class='alert alert-danger'>El C&oacute;digo de conductor: ".esc($cod_conductor)." ya existe</p>";
      }
      if (model(PersonaModel::class)->num_rows_conductor('documento', $documento) == 1)
      {
        $form['error_documento'] = "<p class='alert alert-danger'>El Documento de Identidad: ".esc($documento)." ya fue asignado para otra Persona</p>";
      }
      if (model(PersonaModel::class)->num_rows_conductor('telefono_persona', $telefono) == 1)
      {
        $form['error_telefono'] = "<p class='alert alert-danger'>El Teléfono: ".esc($telefono)." ya fue asignado para otra Persona</p>";
      }
      if (model(ConductorModel::class)->num_rows_registrar_conductor('brevete', $brevete))
      {
        $form['error_brevete'] = "<p class='alert alert-danger'>El Brevete: ".esc($brevete)." ya fue asignado para otro Conductor</p>";
      }
      if ($nuevo_afiliado != 0 && model(ConductorModel::class)->ya_es_afiliado($documento) == 1)
      {
          $form['error_afiliado'] = "<p class='alert alert-danger'>El Nuevo Conductor ya esta registrado como Afiliado</h3>";
      }
      if ($nuevo_afiliado != 0 && model(ConductorModel::class)->ya_es_afiliado($documento) == -1)
      {
          $form['error_afiliado'] = '';
      }
      return $form;
    }
    public function validar_actualizacion($form)
    {
      $id_conductor = $form['id_conductor'];
      $cod_conductor = $form['cod_conductor'];
      $documento = $form['documento'];
      $telefono = $form['telefono_persona'];
      $brevete = $form['brevete'];
      //si ya existe otro registro con ese dato
  		if (model(ConductorModel::class)->num_rows_actualizacion_persona('documento', $documento, $id_conductor) == 1)
  		{
        $form['error_documento'] = "<p class='alert alert-danger'>El Documento de Identidad: ".esc($documento)." ya fue asignado para otra Persona</p>";
  		}
      if (model(ConductorModel::class)->num_rows_actualizacion_persona('telefono_persona', $telefono, $id_conductor) == 1)
  		{
        $form['error_telefono'] = "<p class='alert alert-danger'>El Teléfono: ".esc($telefono)." ya fue asignado para otra Persona</p>";
  		}
      if (model(ConductorModel::class)->num_rows_actualizacion_conductor('cod_conductor', $cod_conductor, $id_conductor) == 1)
  		{
  			$form['error_cod_conductor'] = "<p class='alert alert-danger'>El C&oacute;digo de conductor: ".esc($cod_conductor)." ya fue asignado para otro Conductor</p>";
  		}
      if (model(ConductorModel::class)->num_rows_actualizacion_conductor('brevete', $brevete, $id_conductor) == 1)
  		{
  			$form['error_brevete'] = "<p class='alert alert-danger'>El Brevete: ".esc($brevete)." ya fue asignado para otro Conductor</p>";
  		}
      return $form;
    }
    //Gestion de conductores
    //Mostrar Foto
    public function mostrar_foto($upload_data)
    {
      return $upload_data['full_path'];
    }
    //Si generamos un codigo de afiliado aleatorio
    //tenemos que evitar que sea repetido
    //en el caso de que ya exista uno asignado a otro registro
    public function existe_cod_afiliado($cod_afiliado)
    {
        $existe = false;
        $buscar = $this->db->query('SELECT cod_afiliado FROM `tbl_afiliado` WHERE cod_afiliado = ?', [$cod_afiliado]);
        foreach ($buscar->getResult() as $ver)
        {
            if ($ver->cod_afiliado == $cod_afiliado)
            {
                $existe = true;
            }
        }
        return $existe;
    }
    /**
     * Conductores registrados para el listado (la tabla se dibuja en conductor/conductor.php).
     *
     * @return list<object>
     */
    public function listar_conductores(): array
    {
        return $this->db->query('SELECT c.id_conductor, c.id_person_conductor, c.cod_conductor, p.documento, p.nombre_persona, p.id_persona, c.estado_conductor FROM tbl_conductor as c LEFT JOIN tbl_persona as p ON c.id_person_conductor=p.id_persona')->getResult();
    }
    public function get_documento_conductor($id_persona)
    {
        $query = $this->db->query('SELECT p.documento FROM tbl_persona as p LEFT JOIN tbl_conductor as c ON c.id_person_conductor=p.id_persona WHERE c.id_conductor=?', [$id_conductor]);
        foreach($query->getResult() as $row)
        {
            $documento = $row->documento;
        }
        return $documento;
    }
    public function get_cod_conductor($id_conductor)
    {
        $query = $this->db->query('SELECT c.cod_conductor FROM tbl_persona as p LEFT JOIN tbl_conductor as c ON c.id_person_conductor=p.id_persona WHERE c.id_conductor=?', [$id_conductor]);
        foreach($query->getResult() as $row)
        {
            $cod_con = $row->cod_conductor;
        }
        return $cod_con;
    }
    public function registrar_conductor()
    {
        echo view('header');
        echo view('sidebar');
        echo view('conductor/registrar_conductor');
        echo view('footer');
    }
    public function insertar_responable_vehiculo($id_vehi, $id_person_responsable)
    {
        $data = array(
                'id_responsable' => '',
                'id_vehi' => $id_vehi,
                'id_person_responsable' => $id_person_responsable,
                'es_propietario' => '0',
                'conduce_vehiculo' => '1',
                'estado_responsable_vehiculo' => '1'
            );
        model(Responsable_VehiculoModel::class)->guardar($data);
    }
    public function recuperar_data_conductor($id_conductor)
    {
        $data_conductor = array(
            'id_conductor' => $id_conductor,
            'cod_conductor' => '',
            'documento' => '',
            'nombre_persona' => '',
            'id_person_conductor' => '',
            'direccion_persona' => '',
            'telefono_persona' => '',
            'estado_conductor' => '',
            'foto' => '',
            'brevete' => ''
        );
        $query = $this->db->query('SELECT c.cod_conductor, p.nombre_persona, c.id_person_conductor, p.documento, p.direccion_persona, p.telefono_persona, c.foto, c.brevete, c.estado_conductor FROM tbl_conductor as c LEFT JOIN tbl_persona as p ON c.id_person_conductor=p.id_persona WHERE c.id_conductor=?', [$id_conductor]);
        foreach ($query->getResult() as $row)
        {
            $data_conductor['cod_conductor'] = str_replace('"','',json_encode($row->cod_conductor));
            $data_conductor['nombre_persona'] = str_replace('"','',json_encode($row->nombre_persona));
            $data_conductor['id_person_conductor'] = str_replace('"','',json_encode($row->id_person_conductor));
            $data_conductor['direccion_persona'] = str_replace('"','',json_encode($row->direccion_persona));
            $data_conductor['telefono_persona'] = str_replace('"','',json_encode($row->telefono_persona));
            $data_conductor['estado_conductor'] = str_replace('"','',json_encode($row->estado_conductor));
            $data_conductor['foto'] = str_replace('"','',json_encode($row->foto));
            $data_conductor['brevete'] = str_replace('"','',json_encode($row->brevete));
            $data_conductor['documento'] = str_replace('"','',json_encode($row->documento));
        }
        return $data_conductor;
    }
    public function buscar_vehiculo($data_vehiculo)
    {
      $datos_vehiculo = array(
          "id_vehiculo" => "",
          "cod_vehiculo" => "",
          "placa" => "",
          "id_person_conductor" => ""
        );
      $encontrado = array(
          "id_vehiculo" => "",
          "cod_vehiculo" => "",
          "placa" => "",
          "id_person_conductor" => ""
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
          "id_person_conductor" => ""
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
    public function guardar_asignar()
    {
        $form = $this->request->getPost();
        $id_vehi = $form['id_vehiculo'];
        $id_person_responsable = $form['id_person_conductor'];
        if (model(Responsable_VehiculoModel::class)->existe_responsable($id_person_responsable, $id_vehi))
        {
          model(Responsable_VehiculoModel::class)->reasignar_conductor($id_person_responsable, $id_vehi);
        }
        else
        {
          $this->insertar_responable_vehiculo($id_vehi, $id_person_responsable);
        }
        echo view('conductor/guardar_asignar');
    }
    public function asignar_vehiculo($id_person_conductor)
    {
        $datos['id_person_conductor'] = $id_person_conductor;
        echo view('header');
        echo view('sidebar');
        echo view('conductor/asignar_vehiculo', $datos);
        echo view('footer');
    }
    public function encontrar_vehiculo()
    {
        $form = $this->request->getPost();
        $data_vehiculo = $form['data_vehiculo'];
        $id_person_conductor = $form['id_person_conductor'];
        $Vehiculo_No_Encontrado = array(
            "id_vehiculo" => "",
            "cod_vehiculo" => "",
            "placa" => "",
            "id_person_conductor" => ""
        );
        $Vehiculo_Encontrado = array(
          "id_vehiculo" => "",
          "cod_vehiculo" => "",
          "placa" => "",
          "id_person_conductor" => ""
        );
        $Vehiculo_Encontrado = $this->buscar_vehiculo($data_vehiculo);

        if ($Vehiculo_No_Encontrado == $this->buscar_vehiculo($data_vehiculo))
        {
            echo "<script type=\"text/javascript\">alert(\"No existe el vehículo o esta inactivo\");</script>";
            $this->pagina_anterior();
        }
        else
        {
            $Vehiculo_Encontrado['id_person_conductor'] = $id_person_conductor;
            //si el vehiculo ya fue asignado al conductor
            if (model(Responsable_VehiculoModel::class)->ya_es_conductor($Vehiculo_Encontrado['id_person_conductor'], $Vehiculo_Encontrado['id_vehiculo']))
            {
              echo "<script type=\"text/javascript\">alert(\"Ya fue asignado este vehículo al Conductor Actual\");</script>";
              $this->pagina_anterior();
            }
            else
            {
              echo view('header');
              echo view('sidebar');
              echo view('conductor/encontrar_vehiculo', $Vehiculo_Encontrado);
              echo view('footer');
            }
        }
    }
    public function insertar_persona($form)
    {
        $nombre = $form['nombre_persona'];
        $documento = $form['documento'];
        $telefono = $form['telefono_persona'];
        $direccion = $form['direccion'];
        $data = array(
                'id_persona' => '',
                'nombre_persona' => $nombre,
                'documento' => $documento,
                'direccion_persona' => $direccion,
                'telefono_persona' => $telefono
        );
        model(PersonaModel::class)->guardar($data);
    }
    public function actualizar_persona($form, $id_person_conductor)
    {
        $nombre_persona = $form['nombre_persona'];
        $direccion_persona = $form['direccion_persona'];
        $telefono_persona = $form['telefono_persona'];
        $documento = $form['documento'];
        $data = array(
            'nombre_persona' => $nombre_persona,
            'direccion_persona' => $direccion_persona,
            'documento' => $documento,
            'telefono_persona' => $telefono_persona,
        );
        model(PersonaModel::class)->actualizar($data, $id_person_conductor);
    }
    public function codigo_afiliado_automatico()
    {
        $cod_afiliado = 'A-';
        do
        {
            $aleatorio = rand(100, 1000000);
            if (!$this->existe_cod_afiliado($cod_afiliado))
            {
                $cod_afiliado = $cod_afiliado . (string)$aleatorio;
                break;
            }
        } while($this->existe_cod_afiliado($cod_afiliado));
        return $cod_afiliado;
    }
    public function insertar_nuevo_afiliado($form, $id_persona)
    {
        $cod_afiliado = $this->codigo_afiliado_automatico();
        $estado_afiliado = $form['estado'];
        $data = array(
                'id_person_afiliado' => $id_persona,
                'cod_afiliado' => $cod_afiliado,
                'estado_afiliado' => $estado_afiliado
        );
        model(AfiliadoModel::class)->guardar($data);
    }
    public function insertar_afiliado($form)
    {
        $id_afiliado = '';
        $id_persona = $form['id_persona'];
        $cod_afiliado = $this->codigo_afiliado_automatico();
        $estado_afiliado = $form['estado'];
        $data = array(
                'id_person_afiliado' => $id_persona,
                'cod_afiliado' => $cod_afiliado,
                'estado_afiliado' => $estado_afiliado
        );
        model(AfiliadoModel::class)->guardar($data);
    }
    public function actualizar_conductor($id_conductor)
    {
        $data_conductor = $this->recuperar_data_conductor($id_conductor);
        echo view('header');
        echo view('sidebar');
        echo view('conductor/actualizar_conductor', $data_conductor);
        echo view('footer');
    }
    public function get_extencion($foto)
    {
      $punto = '.';
      $strArray = explode('.',$foto);
      $lastElement = end($strArray);
      return $punto.$lastElement;
    }
    public function get_directorio($foto)
    {
      $strArray = explode('/',$foto);
      $lastElement = end($strArray);
      $dir = str_replace($lastElement, "", $foto);
      return $dir;
    }
    /** Carpeta de fotos (antes ./assets/img/ del proyecto CI3), con separadores '/'. */
    private function directorio_fotos(): string
    {
      return str_replace('\\', '/', FCPATH) . 'assets/img/';
    }
    /** Nombre de archivo seguro a partir del documento (evita rutas como ../). */
    private function nombre_archivo_seguro($nombre): string
    {
      return preg_replace('/[^A-Za-z0-9_-]/', '', (string) $nombre);
    }
    /** Extensión segura de la foto guardada. */
    private function extencion_segura($foto): string
    {
      $extencion = strtolower(ltrim($this->get_extencion((string) $foto), '.'));
      return in_array($extencion, ['gif', 'jpg', 'jpeg', 'png'], true) ? '.' . $extencion : '';
    }
    /**
     * Reemplaza la librería Upload de CI3: valida y guarda la imagen enviada en 'userfile'
     * como <documento>.<ext>. Devuelve la ruta completa o null si no se subió una imagen válida.
     */
    private function subir_foto($documento): ?string
    {
      $file = $this->request->getFile('userfile');
      if ($file === null || ! $file->isValid() || $file->hasMoved())
      {
        return null;
      }
      $reglas = ['userfile' => 'uploaded[userfile]|is_image[userfile]|ext_in[userfile,gif,jpg,png]|max_size[userfile,4200]|max_dims[userfile,5000,3000]'];
      if (! $this->validate($reglas))
      {
        return null;
      }
      $nombre = $this->nombre_archivo_seguro($documento);
      if ($nombre === '')
      {
        return null;
      }
      $nombre .= '.' . $file->guessExtension();
      $file->move($this->directorio_fotos(), $nombre, true);
      return $this->directorio_fotos() . $nombre;
    }
    public function renombrar_foto($txt_dni_antiguo, $documento, $foto)
    {
      $extencion = $this->extencion_segura($foto);
      $anterior = $this->nombre_archivo_seguro($txt_dni_antiguo);
      $nuevo = $this->nombre_archivo_seguro($documento);
      if($txt_dni_antiguo != $documento && $extencion !== '' && $anterior !== '' && $nuevo !== '')
      {
        if (is_file($this->directorio_fotos().$anterior.$extencion))
        {
          rename($this->directorio_fotos().$anterior.$extencion, $this->directorio_fotos().$nuevo.$extencion);
        }
        return $this->get_directorio($foto).$nuevo.$extencion;
      }
      else
      {
        return '';
      }
    }
    public function borrar_foto($txt_dni_antiguo, $foto)
    {
      $extencion = $this->extencion_segura($foto);
      $anterior = $this->nombre_archivo_seguro($txt_dni_antiguo);
      if ($extencion !== '' && $anterior !== '' && is_file($this->directorio_fotos().$anterior.$extencion))
      {
        unlink($this->directorio_fotos().$anterior.$extencion);
      }
    }
    public function actualizar_datos()
    {
      $form = $this->request->getPost();
      $form['error_cod_conductor'] = '';
      $form['error_documento'] = '';
      $form['error_telefono'] = '';
      $form['error_brevete'] = '';
      $form['error_afiliado'] = '';
      $validacion = $this->validar_actualizacion($form);
      if ($this->algun_error_validar_conductor($validacion))
      {
        echo view('header');
        echo view('sidebar');
        echo view('conductor/actualizar_conductor', $validacion);
        echo view('footer');
      }
      else
      {
        //en el caso de que haya nueva foto
        $documento = $this->request->getPost('documento');
        $foto = $this->request->getPost('foto');
        $txt_dni_antiguo = $this->request->getPost('txt_dni_antiguo');
        $foto_subida = $this->subir_foto($documento);
        if ($foto_subida !== null)
        {
          // borrar la foto anterior solo si la nueva no la reemplazó con el mismo nombre
          $anterior = $this->directorio_fotos().$this->nombre_archivo_seguro($txt_dni_antiguo).$this->extencion_segura($foto);
          if ($anterior !== $foto_subida)
          {
            $this->borrar_foto($txt_dni_antiguo, $foto);
          }
          $foto = $foto_subida;
        }
        else
        {
          $foto_actualizada = $this->renombrar_foto($txt_dni_antiguo, $documento, $foto);
          if ($foto_actualizada != '')
          {
            $foto = $foto_actualizada;
          }
        }
        $id_person_conductor = $form['id_person_conductor'];
        $this->actualizar_persona($form, $id_person_conductor);
        $id_conductor = $form['id_conductor'];
        $cod_conductor = $form['cod_conductor'];
        $brevete = $form['brevete'];
        $documento = $form['documento'];
        $nuevo_afiliado = $form['nuevo_afiliado'];
        $estado_conductor = $form['estado_nuevo'];
        $data = array(
                  'cod_conductor' => $cod_conductor,
                  'brevete' => $brevete,
                  'foto' => $foto,
                  'estado_conductor' => $estado_conductor
        );
        model(ConductorModel::class)->actualizar($data, $id_conductor);
        // si cambio el documento
        //si se marco la casilla de que es afiliado
        if ($nuevo_afiliado == '1')
        {
          $this->insertar_afiliado($form);
        }
        echo view('conductor/actualizar_datos');
      }
    }
    public function guardar_conductor()
    {
      $form = $this->request->getPost();
      $form['error_cod_conductor'] = '';
      $form['error_documento'] = '';
      $form['error_telefono'] = '';
      $form['error_brevete'] = '';
      $form['error_afiliado'] = '';
      $documento = $form['documento'];
      $foto = '';
      $validacion = $this->validar_conductor($form);
      $validacion['error_archivo'] = 'No se pudo subir la imagen';
      if ($this->algun_error_validar_conductor($validacion) || $foto == "No se pudo subir el archivo")
      {
          echo view('header');
          echo view('sidebar');
          echo view('conductor/registrar_conductor', $validacion);
          echo view('footer');
      }
      else
      {
          $query = $this->db->query('SELECT * FROM `tbl_persona` WHERE documento=?', [$documento]);
          $count = $query->getNumRows();
          if ($count == '0')
          {
              $this->insertar_persona($form);
          }
          //guardar foto
          $foto_subida = $this->subir_foto($documento);
          if ($foto_subida !== null)
          {
            $foto = $foto_subida;
          }
          //insertar conductor
          $id_person_conductor = $this->getIdPersona($documento);
          $cod_conductor = $form['cod_conductor'];
          $brevete = $form['brevete'];
          $estado_conductor = $form['estado'];
          $es_afiliado = $form['nuevo_afiliado'];
          $data = array(
                  'id_conductor' => '',
                  'id_person_conductor' => $id_person_conductor,
                  'cod_conductor' => $cod_conductor,
                  'id_person_conductor' => $id_person_conductor,
                  'brevete' => $brevete,
                  'cod_conductor' => $cod_conductor,
                  'foto' => $foto,
                  'estado_conductor' => $estado_conductor
          );
          model(ConductorModel::class)->guardar($data);
          if ($es_afiliado == '1')
          {
           $this->insertar_nuevo_afiliado($form, $id_person_conductor);
          }
          echo view('conductor/guardar_conductor');
        }
    }
}
