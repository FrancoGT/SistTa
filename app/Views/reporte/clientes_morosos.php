<?php
/**
 * @var bool         $hay_periodos  Reporte::datos_clientes_morosos()
 * @var list<object> $personas      Afiliados/conductores con deuda >= 28 días
 * @var list<object> $vehiculos     Vehículos con deuda >= 28 días
 */
?>
<h1>Clientes Morosos</h1>
<br>
<?php if (!$hay_periodos): ?>
  <p class="alert alert-danger">No hay registros de deudas a&uacute;n</p>
<?php elseif (empty($personas) && empty($vehiculos)): ?>
  <p class="alert alert-danger">No hay clientes morosos</p>
<?php else: ?>
  <?php if (!empty($personas)): ?>
    <h3>Personas</h3>
    <table class="table table-bordered table-hover js-datatable" data-dt-buscar="Buscar por documento o nombre...">
      <thead>
        <tr>
          <th>Documento</th>
          <th>Nombre</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($personas as $p): ?>
        <tr>
          <td><?= esc($p->documento) ?></td>
          <td><?= esc($p->nombre_persona) ?></td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  <?php endif ?>

  <?php if (!empty($vehiculos)): ?>
    <h3>Veh&iacute;culos de Clientes Morosos</h3>
    <table class="table table-bordered table-hover js-datatable" data-dt-buscar="Buscar por c&oacute;digo o placa...">
      <thead>
        <tr>
          <th>C&oacute;digo</th>
          <th>Placa</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($vehiculos as $v): ?>
        <tr>
          <td><?= esc($v->cod_vehiculo) ?></td>
          <td><?= esc($v->placa) ?></td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  <?php endif ?>
<?php endif ?>
