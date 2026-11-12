<h1>Actualizar Veh&iacute;culo</h1>
<br>
<form action="<?= base_url() ?>index.php/vehiculo/actualizar_datos" method="POST">
<?= csrf_field() ?>
  <input type="hidden" required autocomplete="off" id="id_vehiculo" name="id_vehiculo" placeholder="C&oacute;digo de Vehiculo" value="<?= esc($id_vehiculo) ?>" >
  <div class="form-group" >
  <p>C&oacute;digo</p>
  <input type="text" onkeypress="return AlphaNumeric(event)" required autocomplete="off" id="cod_vehiculo" name="cod_vehiculo" placeholder="C&oacute;digo de Vehiculo" value="<?= esc($cod_vehiculo) ?>" >
  <br> <br>
  <?php
    if (isset($error_cod_vehiculo))
    {
      if ($error_cod_vehiculo != '')
      {
        echo "<h3>".$error_cod_vehiculo."</h3><br>";
      }
    }
  ?>
  <p>Placa:</p>

    <input type="text" onkeypress="return AlphaNumeric(event)" required autocomplete="off"  name="placa" id="placa" value="<?= esc($placa) ?>"/> <br>
    <?php
      if (isset($error_placa))
      {
        if ($error_placa != '')
        {
          echo "<h3>".$error_placa."</h3><br>";
        }
      }
    ?>
    <input type="hidden" name="id_vehiculo" id="id_vehiculo" value="<?= esc($id_vehiculo) ?>"/>
<br>
  <p>
    Estado Actual:
    <?php
      $estado_vehiculo = $ci->get_estado_actual($id_vehiculo);
      if ($estado_vehiculo == '1')
        echo "Activo<br>";
      if ($estado_vehiculo == '0')
        echo "Inactivo";
    ?>
  </p>
  <h3>Para Actualizar Datos Actuales, confirmar el Estado del Veh&iacute;culo</h3>
  <p>
    <p>
      <input type="radio" required autocomplete="off" name="estado" value="1" id="estado_0" />
    Activo</p>
    <br />
    <p>
      <input type="radio" name="estado" value="0" id="estado_1" />
      Inactivo</p>
    <br />
  </p>
        </p>
  <input type="submit" name="button" id="button" value="Actualizar Vehículo" />
</form>

<?php
  /*
   * Propietarios y conductores del vehículo.
   * Datos: Vehiculo::responsables_vehiculo($id, $conductores)
   */
  $grupos = [
    [
      'titulo'   => 'Propietario(s) del Veh&iacute;culo',
      'filas'    => $ci->responsables_vehiculo($id_vehiculo, false),
      'agregar'  => ['vehiculo/agregar_propietario/', 'A&ntilde;adir Propietario'],
      'cambiar'  => ['vehiculo/cambiar_propietario/', 'Cambiar Propietario'],
      'eliminar' => ['vehiculo/eliminar_propietario/', 'Eliminar Propietario'],
      'vacio'    => 'Este Veh&iacute;culo no tiene Propietarios',
    ],
    [
      'titulo'   => 'Conductor(es) del Veh&iacute;culo',
      'filas'    => $ci->responsables_vehiculo($id_vehiculo, true),
      'agregar'  => ['vehiculo/agregar_conductor/', 'A&ntilde;adir Conductor'],
      'cambiar'  => ['vehiculo/cambiar_conductor/', 'Cambiar Conductor'],
      'eliminar' => ['vehiculo/eliminar_conductor/', 'Eliminar Conductor'],
      'vacio'    => 'Este Veh&iacute;culo no tiene Conductores',
    ],
  ];
?>
<?php foreach ($grupos as $g): ?>
  <h3><?= $g['titulo'] ?></h3>
  <p>
    <a class="btn btn-info" href="<?= site_url($g['agregar'][0] . esc($id_vehiculo, 'url')) ?>" title="<?= $g['agregar'][1] ?>"><?= $g['agregar'][1] ?> <i class="ion ion-person-add"></i></a>
  </p>
  <?php if (empty($g['filas'])): ?>
    <p class="alert alert-danger"><?= $g['vacio'] ?></p>
  <?php else: ?>
    <table class="table table-bordered table-hover js-datatable" data-dt-buscar="Buscar por documento o nombre...">
      <thead>
        <tr>
          <th>Documento</th>
          <th>Nombre</th>
          <th class="dt-acciones">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($g['filas'] as $r): ?>
          <?php $referencia = esc($r->id_persona . '-' . $id_vehiculo, 'url'); ?>
        <tr>
          <td><?= esc($r->documento) ?></td>
          <td><?= esc($r->nombre_persona) ?></td>
          <td class="dt-acciones">
            <a class="btn btn-info" href="<?= site_url($g['cambiar'][0] . $referencia) ?>" title="<?= $g['cambiar'][1] ?>"><i class="fa fa-male"></i> &#8644; <i class="fa fa-male"></i></a>
            <a class="btn btn-danger" href="<?= site_url($g['eliminar'][0] . $referencia) ?>" title="<?= $g['eliminar'][1] ?>"><i class="fa fa-male"></i> <i class="fa fa-close"></i></a>
          </td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  <?php endif ?>
<?php endforeach ?>
