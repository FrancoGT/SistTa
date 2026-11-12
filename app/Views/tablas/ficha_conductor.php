<?php
/**
 * Ficha de detalle de un conductor.
 * @var list<object> $filas  Resultado de BaseController::ficha_conductor()
 */
?>
<p class="alert alert-info">Datos de Conductor</p>
<table class="table table-bordered table-ficha">
  <thead>
    <tr>
      <th>C&oacute;digo</th>
      <th>Documento</th>
      <th>Brevete</th>
      <th>Nombre</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($filas as $fila): ?>
    <tr>
      <td><?= esc($fila->cod_conductor) ?></td>
      <td><?= esc($fila->documento) ?></td>
      <td><?= esc($fila->brevete) ?></td>
      <td><?= esc($fila->nombre_persona) ?></td>
    </tr>
    <?php endforeach ?>
    <?php if ($filas === []): ?>
    <tr><td colspan="4" class="text-muted">Sin datos del conductor</td></tr>
    <?php endif ?>
  </tbody>
</table>
