<?php
/**
 * Ficha de detalle de un afiliado.
 * @var list<object> $filas  Resultado de BaseController::ficha_afiliado()
 */
?>
<p class="alert alert-info">Datos de Afiliado</p>
<table class="table table-bordered table-ficha">
  <thead>
    <tr>
      <th>C&oacute;digo</th>
      <th>Documento</th>
      <th>Nombre</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($filas as $fila): ?>
    <tr>
      <td><?= esc($fila->cod_afiliado) ?></td>
      <td><?= esc($fila->documento) ?></td>
      <td><?= esc($fila->nombre_persona) ?></td>
    </tr>
    <?php endforeach ?>
    <?php if ($filas === []): ?>
    <tr><td colspan="3" class="text-muted">Sin datos del afiliado</td></tr>
    <?php endif ?>
  </tbody>
</table>
