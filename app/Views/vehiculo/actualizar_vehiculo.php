<?php $estadoActual = $estado ?? $ci->get_estado_actual($id_vehiculo); ?>
<h2>Actualizar veh&iacute;culo</h2>
<form data-async-form class="sistta-form-layout" action="<?= site_url('vehiculo/actualizar_datos') ?>" method="post">
<?= csrf_field() ?>
<input type="hidden" name="id_vehiculo" value="<?= esc($id_vehiculo) ?>">
<div class="sistta-form-grid">
  <div class="form-group">
    <label for="cod_vehiculo">C&oacute;digo</label>
    <input class="form-control" type="text" id="cod_vehiculo" name="cod_vehiculo" required autocomplete="off" value="<?= esc($cod_vehiculo) ?>" onkeypress="return AlphaNumeric(event)">
    <?php if (!empty($error_cod_vehiculo)): ?><div class="sistta-field-error" role="alert"><?= $error_cod_vehiculo ?></div><?php endif ?>
  </div>
  <div class="form-group">
    <label for="placa">Placa</label>
    <input class="form-control" type="text" id="placa" name="placa" required autocomplete="off" value="<?= esc($placa) ?>" onkeypress="return AlphaNumeric(event)">
    <?php if (!empty($error_placa)): ?><div class="sistta-field-error" role="alert"><?= $error_placa ?></div><?php endif ?>
  </div>
  <fieldset class="form-group sistta-span-all">
    <legend>Estado</legend>
    <div class="sistta-options">
      <?php foreach (['1' => 'Activo', '0' => 'Inactivo'] as $valor => $texto): ?>
      <label><input type="radio" name="estado" value="<?= $valor ?>" required <?= (string) $estadoActual === (string) $valor ? 'checked' : '' ?>><?= $texto ?></label>
      <?php endforeach ?>
    </div>
  </fieldset>
</div>
<div class="sistta-form-actions"><button class="btn btn-primary" type="submit" name="button" value="1">Guardar cambios</button></div>
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
    <a data-form-modal class="btn btn-info" href="<?= site_url($g['agregar'][0] . esc($id_vehiculo, 'url')) ?>" title="<?= $g['agregar'][1] ?>"><?= $g['agregar'][1] ?> <i class="ion ion-person-add"></i></a>
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
            <a data-form-modal class="btn btn-info" href="<?= site_url($g['cambiar'][0] . $referencia) ?>" title="<?= $g['cambiar'][1] ?>"><i class="fa fa-male"></i> &#8644; <i class="fa fa-male"></i></a>
            <a data-form-modal class="btn btn-danger" href="<?= site_url($g['eliminar'][0] . $referencia) ?>" title="<?= $g['eliminar'][1] ?>"><i class="fa fa-male"></i> <i class="fa fa-close"></i></a>
          </td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  <?php endif ?>
<?php endforeach ?>
