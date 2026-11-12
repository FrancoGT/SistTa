<?php
$cod_vehiculo ??= $cod_vehiculo_G ?? '';
$placa ??= $placa_G ?? '';
$data_afiliado ??= $data_afiliado_G ?? '';
$id_person_afiliado ??= $id_person_afiliado_G ?? '';
$data_conductor ??= $data_conductor_G ?? '';
$id_person_conductor ??= $id_person_conductor_G ?? '';
$estadoActual = $estado ?? $estado_vehiculo ?? $estado_G ?? '';
$valores = ['cod_vehiculo' => $cod_vehiculo, 'placa' => $placa, 'estado' => $estadoActual,
    'data_afiliado' => $data_afiliado, 'id_person_afiliado' => $id_person_afiliado,
    'data_conductor' => $data_conductor, 'id_person_conductor' => $id_person_conductor];
?>
<h2>Nuevo veh&iacute;culo</h2>
<form data-async-form data-vehicle-form class="sistta-form-layout" action="<?= site_url('vehiculo/recepcionar_vehiculo') ?>" method="post">
<?= csrf_field() ?>
<?php foreach (['BA', 'BC', 'G'] as $sufijo): ?>
  <?php foreach ($valores as $campo => $valor): ?>
  <input type="hidden" name="<?= esc($campo . '_' . $sufijo) ?>" value="<?= esc($valor) ?>">
  <?php endforeach ?>
<?php endforeach ?>
<input data-vehicle-field type="hidden" name="id_person_afiliado" value="<?= esc($id_person_afiliado) ?>">
<input data-vehicle-field type="hidden" name="id_person_conductor" value="<?= esc($id_person_conductor) ?>">
<div class="sistta-form-grid">
  <div class="form-group">
    <label for="cod_vehiculo">C&oacute;digo</label>
    <input data-vehicle-field class="form-control" type="text" name="cod_vehiculo" id="cod_vehiculo" required value="<?= esc($cod_vehiculo) ?>" onkeypress="return AlphaNumeric(event)">
    <?php if (!empty($error_cod_vehiculo)): ?><div class="sistta-field-error" role="alert"><?= $error_cod_vehiculo ?></div><?php endif ?>
  </div>
  <div class="form-group">
    <label for="placa">Placa</label>
    <input data-vehicle-field class="form-control" type="text" name="placa" id="placa" required value="<?= esc($placa) ?>" onkeypress="return AlphaNumeric(event)">
    <?php if (!empty($error_placa)): ?><div class="sistta-field-error" role="alert"><?= $error_placa ?></div><?php endif ?>
  </div>
  <fieldset class="form-group sistta-span-all">
    <legend>Estado</legend>
    <div class="sistta-options">
      <?php foreach (['1' => 'Activo', '0' => 'Inactivo'] as $valor => $texto): ?>
      <label><input data-vehicle-field type="radio" name="estado" value="<?= $valor ?>" required <?= (string) $estadoActual === (string) $valor ? 'checked' : '' ?>><?= $texto ?></label>
      <?php endforeach ?>
    </div>
  </fieldset>
</div>
<section class="sistta-form-section">
  <h3>Afiliado propietario</h3>
  <?php if ($id_person_afiliado !== ''): ?>
    <?= view('tablas/ficha_afiliado', ['filas' => $ci->ficha_afiliado($id_person_afiliado)]) ?>
  <?php else: ?><p class="help-block">Busca y asigna un afiliado antes de guardar.</p><?php endif ?>
  <?php if (!empty($mensaje)): ?><div class="sistta-field-error" role="status"><?= $mensaje ?></div><?php endif ?>
  <input data-vehicle-field type="hidden" name="data_afiliado" value="<?= esc($data_afiliado) ?>">
</section>
<section class="sistta-form-section">
  <h3>Conductor <span class="text-muted">(opcional)</span></h3>
  <?php if ($id_person_conductor !== ''): ?>
    <?= view('tablas/ficha_conductor', ['filas' => $ci->ficha_conductor($id_person_conductor)]) ?>
  <?php endif ?>
  <?php if (!empty($mensaje2)): ?><div class="sistta-field-error" role="status"><?= $mensaje2 ?></div><?php endif ?>
  <div class="sistta-search-field">
    <div><label for="data_conductor">DNI, brevete o c&oacute;digo del conductor</label><input data-vehicle-field class="form-control" type="text" name="data_conductor" id="data_conductor" value="<?= esc($data_conductor) ?>" onkeypress="return AlphaNumeric(event)"></div>
    <button class="btn btn-default" type="submit" formnovalidate formaction="<?= site_url('vehiculo/encontrar_conductor') ?>" name="buscar_conductor" value="1">Buscar conductor</button>
  </div>
</section>
<div class="sistta-form-actions"><button class="btn btn-primary" type="submit" name="guardar_vehiculo" value="1">Guardar veh&iacute;culo</button></div>
</form>
<section class="sistta-form-section">
  <h3>Conductores activos</h3>
  <?= view('tablas/conductores_activos', ['conductores' => $ci->listar_conductores_activos()]) ?>
</section>
