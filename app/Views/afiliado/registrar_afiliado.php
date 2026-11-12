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
<h2>Nuevo afiliado</h2>
<form data-async-form class="sistta-form-layout" action="<?= site_url('afiliado/guardar_afiliado') ?>" method="post">
<?= csrf_field() ?>

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
      <label><input type="radio" name="estado" value="<?= $valor ?>" required <?= (string) $estadoActual === (string) $valor ? 'checked' : '' ?> ><?= $texto ?></label>
      <?php endforeach ?>
    </div>
  </fieldset>
</div>
<div class="sistta-form-actions"><button class="btn btn-primary" type="submit" name="guardar_afiliado" value="1">Guardar afiliado</button></div>
</form>
