<?php
/**
 * Tabla de consulta de conductores activos (formularios de registro de vehículo).
 * @var list<object> $conductores  Vehiculo::listar_conductores_activos()
 */
?>
<?php if (empty($conductores)): ?>
  <p class="alert alert-danger">No hay registros de conductores a&uacute;n</p>
<?php else: ?>
  <h3>Conductores activos</h3>
  <table class="table table-bordered table-hover js-datatable" data-dt-buscar="Buscar conductor por c&oacute;digo, nombre o brevete...">
    <thead>
      <tr>
        <th>C&oacute;digo</th>
        <th>Nombre</th>
        <th>Brevete</th>
        <th>Estado</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($conductores as $c): ?>
      <tr>
        <td><?= esc($c->cod_conductor) ?></td>
        <td><?= esc($c->nombre_persona) ?></td>
        <td><?= esc($c->brevete) ?></td>
        <td><?= $c->estado_conductor == 1 ? 'Activo' : 'Inactivo' ?></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
<?php endif ?>
