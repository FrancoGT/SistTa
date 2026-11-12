<?php
$estadoActual = $estado ?? $estado_nuevo ?? $estado_afiliado ?? '';
$campos = [
    ['cod_afiliado', 'C&oacute;digo', $cod_afiliado ?? '', 'AlphaNumeric', 'error_cod_afiliado'],
    ['nombre', 'Nombre completo', $nombre ?? '', 'ValidateAlpha', ''],
    ['documento', 'DNI', $documento ?? '', 'isNumberKey', 'error_documento'],
    ['telefono', 'Tel&eacute;fono', $telefono ?? '', 'isNumberKey', 'error_telefono'],
    ['direccion', 'Direcci&oacute;n', $direccion ?? '', 'AlphaNumeric', ''],
];
?>
<h2>Actualizar afiliado</h2>
<form data-async-form class="sistta-form-layout" name="actualizar" action="<?= site_url('afiliado/actualizar_datos') ?>" method="post">
<?= csrf_field() ?>
<input type="hidden" name="id_afiliado" value="<?= esc($id_afiliado) ?>">
<input type="hidden" name="id_persona" value="<?= esc($id_persona) ?>">
<input type="hidden" name="estado_nuevo" id="estado_nuevo" value="<?= esc($estadoActual) ?>">
<div class="sistta-form-grid">
<?php foreach ($campos as [$campo, $etiqueta, $valor, $filtro, $error]): ?>
  <div class="form-group <?= $campo === 'direccion' ? 'sistta-span-all' : '' ?>">
    <label for="<?= esc($campo) ?>"><?= $etiqueta ?></label>
    <input class="form-control" type="text" id="<?= esc($campo) ?>" name="<?= esc($campo) ?>" required autocomplete="off" value="<?= esc($valor) ?>" onkeypress="return <?= esc($filtro) ?>(event)">
    <?php if ($error !== '' && !empty($$error)): ?><div class="sistta-field-error" role="alert"><?= $$error ?></div><?php endif ?>
  </div>
<?php endforeach ?>
  <fieldset class="form-group sistta-span-all">
    <legend>Estado</legend>
    <div class="sistta-options">
      <?php foreach (['1' => 'Activo', '0' => 'Inactivo'] as $valor => $texto): ?>
      <label><input type="radio" name="estado" value="<?= $valor ?>" required <?= (string) $estadoActual === (string) $valor ? 'checked' : '' ?> onchange="this.form.elements.estado_nuevo.value=this.value" ><?= $texto ?></label>
      <?php endforeach ?>
    </div>
  </fieldset>
</div>
<div class="sistta-form-actions"><button class="btn btn-primary" type="submit" name="guardar_afiliado" value="1">Guardar cambios</button></div>
</form>
