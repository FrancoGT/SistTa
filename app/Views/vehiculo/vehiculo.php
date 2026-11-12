<?php
/**
 * @var bool         $hay_registros  Vehiculo::listar_vehiculos()
 * @var list<object> $vehiculos      Solo vehículos activos
 */
if (!isset($vehiculos)) {
    ['hay_registros' => $hay_registros, 'vehiculos' => $vehiculos] = $ci->listar_vehiculos();
}
?>
<h1>Veh&iacute;culos</h1>
<h3>
  <?php if (isset($mensaje)) { echo ' ' . $mensaje . ' '; } ?>
  <a class="btn btn-info" href="<?= site_url('vehiculo/registrar_vehiculo') ?>"><i class="fa fa-car"></i> <i class="fa fa-plus"></i> Nuevo Veh&iacute;culo</a>
</h3>
<?php if (!$hay_registros): ?>
  <p class="alert alert-danger">No hay registros de veh&iacute;culos a&uacute;n</p>
<?php else: ?>
  <table class="table table-bordered table-hover js-datatable" data-dt-buscar="Buscar por c&oacute;digo, placa o due&ntilde;o...">
    <thead>
      <tr>
        <th>C&oacute;digo</th>
        <th>Placa</th>
        <th>Due&ntilde;o(s)</th>
        <th>Estado</th>
        <th class="dt-acciones">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($vehiculos as $v): ?>
      <tr>
        <td><?= esc($v->cod_vehiculo) ?></td>
        <td><?= esc($v->placa) ?></td>
        <td><?= esc($v->propietarios) ?></td>
        <td><?= $v->estado_vehiculo == 1 ? 'Activo' : 'Inactivo' ?></td>
        <td class="dt-acciones">
          <a class="btn btn-info" href="<?= site_url('vehiculo/actualizar_vehiculo/' . esc($v->id_vehiculo, 'url')) ?>" title="Actualizar Veh&iacute;culo"><i class="fa fa-refresh"></i> <i class="fa fa-car"></i></a>
        </td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
<?php endif ?>
