<?php
/**
 * Resumen de la deuda del periodo que se va a pagar.
 * @var list<array{servicio: string, saldo: string}> $filas  Cobranza::deuda_periodo()
 */
?>
<table class="table table-bordered table-ficha">
  <thead>
    <tr>
      <th>Servicio</th>
      <th class="text-right">Deuda Pendiente</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($filas as $fila): ?>
    <tr>
      <td><?= esc($fila['servicio']) ?></td>
      <td class="text-right"><?= esc($fila['saldo']) ?></td>
    </tr>
    <?php endforeach ?>
    <?php if ($filas === []): ?>
    <tr><td colspan="2" class="text-muted">Sin deuda registrada para este periodo</td></tr>
    <?php endif ?>
  </tbody>
</table>
