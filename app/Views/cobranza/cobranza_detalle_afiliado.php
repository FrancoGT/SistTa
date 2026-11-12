<?php
/** Datos: Cobranza::cobranza_afiliado() — servicios del afiliado, sus vehículos y conductores */
$cobranza = $ci->cobranza_afiliado($id_person_afiliado);
?>
<h1>Cobranza Detalle</h1>
<?= view('tablas/ficha_afiliado', ['filas' => $ci->ficha_afiliado($id_person_afiliado)]) ?>
<?= view('cobranza/_servicios', [
  'bloque' => $cobranza['servicios'],
  'titulo' => 'Servicios que paga el Afiliado',
  'vacio'  => 'No tiene ning&uacute;n servicio asignado',
]) ?>

<h2>Veh&iacute;culos</h2>
<?php if ($cobranza['vehiculos'] === []): ?>
  <p class="alert alert-danger">El Afiliado no tiene ning&uacute;n veh&iacute;culo asignado</p>
<?php endif ?>

<?php foreach ($cobranza['vehiculos'] as $i => $vehiculo): ?>
  <div class="cobranza-vehiculo">
    <h3>Veh&iacute;culo <?= $i + 1 ?></h3>
    <?= view('tablas/ficha_vehiculo', ['filas' => $vehiculo['ficha']]) ?>
    <?= view('cobranza/_servicios', [
      'bloque' => $vehiculo['servicios'],
      'titulo' => 'Servicios del Veh&iacute;culo ' . ($i + 1),
      'vacio'  => 'El Veh&iacute;culo no tiene ning&uacute;n servicio asignado',
    ]) ?>

    <?php if ($vehiculo['conductores'] === []): ?>
      <p class="alert alert-danger">El veh&iacute;culo no tiene ning&uacute;n Conductor asignado</p>
    <?php else: ?>
      <h3>Conductores del Veh&iacute;culo <?= $i + 1 ?></h3>
      <?php foreach ($vehiculo['conductores'] as $j => $conductor): ?>
        <h4>Conductor <?= $j + 1 ?></h4>
        <?= view('tablas/ficha_conductor', ['filas' => $conductor['ficha']]) ?>
        <?= view('cobranza/_servicios', [
          'bloque' => $conductor['servicios'],
          'titulo' => 'Servicios que paga el Conductor',
          'vacio'  => 'El Conductor no tiene ning&uacute;n servicio asignado',
        ]) ?>
      <?php endforeach ?>
    <?php endif ?>
  </div>
<?php endforeach ?>
