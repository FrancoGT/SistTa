<?php
/**
 * Ficha de detalle de un vehículo.
 * @var list<object> $filas  Resultado de BaseController::ficha_vehiculo()
 */
?>
<p class="alert alert-info">Datos de Veh&iacute;culo</p>
<table class="table table-bordered table-ficha">
  <thead>
    <tr>
      <th>C&oacute;digo</th>
      <th>Placa</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($filas as $fila): ?>
    <tr>
      <td><?= esc($fila->cod_vehiculo) ?></td>
      <td><?= esc($fila->placa) ?></td>
    </tr>
    <?php endforeach ?>
    <?php if ($filas === []): ?>
    <tr><td colspan="2" class="text-muted">Sin datos del veh&iacute;culo</td></tr>
    <?php endif ?>
  </tbody>
</table>
