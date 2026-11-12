<?php
/** @var list<object> $conductores  Conductor::listar_conductores() */
$conductores ??= $ci->listar_conductores();
?>
<h1>Conductores</h1>
<h3>
  <?php if (isset($mensaje)) { echo ' ' . $mensaje . ' '; } ?>
  <a data-form-modal class="btn btn-info" href="<?= site_url('conductor/registrar_conductor') ?>"><i class="ion ion-person-add"></i> Nuevo Conductor</a>
</h3>
<?php if (empty($conductores)): ?>
  <p class="alert alert-danger">No hay registros de conductores a&uacute;n</p>
<?php else: ?>
  <table class="table table-bordered table-hover js-datatable" data-dt-buscar="Buscar por c&oacute;digo, nombre o documento...">
    <thead>
      <tr>
        <th>C&oacute;digo</th>
        <th>Nombre</th>
        <th>Documento</th>
        <th data-dt-filtro>Estado</th>
        <th class="dt-acciones">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($conductores as $c): ?>
      <tr>
        <td><?= esc($c->cod_conductor) ?></td>
        <td><?= esc($c->nombre_persona) ?></td>
        <td><?= esc($c->documento) ?></td>
        <td><?= $c->estado_conductor == 1 ? 'Activo' : 'Inactivo' ?></td>
        <td class="dt-acciones">
          <a data-form-modal class="btn btn-primary" href="<?= site_url('conductor/actualizar_conductor/' . esc($c->id_conductor, 'url')) ?>" title="Actualizar conductor"><i class="fa fa-refresh"></i> <i class="fa fa-male"></i></a>
          <a data-form-modal class="btn btn-info" href="<?= site_url('conductor/asignar_vehiculo/' . esc($c->id_person_conductor, 'url')) ?>" title="Asignar Veh&iacute;culo"><i class="fa fa-plus"></i> <i class="fa fa-car"></i></a>
        </td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
<?php endif ?>
