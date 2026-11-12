<?php
$estadoActual = $estado ?? $estado_nuevo ?? $estado_conductor ?? '';
$afiliado_es ??= (!empty($documento) ? $ci->es_afiliado($documento) : '');
$campos = [
    ['cod_conductor', 'C&oacute;digo', $cod_conductor ?? '', 'AlphaNumeric', 'error_cod_conductor'],
    ['documento', 'DNI', $documento ?? '', 'isNumberKey', 'error_documento'],
    ['brevete', 'Brevete', $brevete ?? '', 'AlphaNumeric', 'error_brevete'],
    ['nombre_persona', 'Nombre completo', $nombre_persona ?? '', 'ValidateAlpha', ''],
    ['telefono_persona', 'Tel&eacute;fono', $telefono_persona ?? '', 'isNumberKey', 'error_telefono'],
    ['direccion', 'Direcci&oacute;n', $direccion ?? '', 'AlphaNumeric', ''],
];
?>
<h2>Nuevo conductor</h2>
<?= form_open_multipart('conductor/guardar_conductor', ['data-async-form' => '', 'class' => 'sistta-form-layout', 'name' => 'nuevo_conductor']) ?>

<input type="hidden" name="deuda" value="<?= esc($deuda ?? '') ?>">
<input type="hidden" name="nuevo_afiliado" value="<?= esc($nuevo_afiliado ?? '0') ?>">
<div class="sistta-form-grid">
<?php foreach ($campos as [$campo, $etiqueta, $valor, $filtro, $error]): ?>
  <div class="form-group">
    <label for="<?= esc($campo) ?>"><?= $etiqueta ?></label>
    <input class="form-control" type="text" id="<?= esc($campo) ?>" name="<?= esc($campo) ?>" required autocomplete="off" value="<?= esc($valor) ?>" onkeypress="return <?= esc($filtro) ?>(event)">
    <?php if ($error !== '' && !empty($$error)): ?><div class="sistta-field-error" role="alert"><?= $$error ?></div><?php endif ?>
  </div>
<?php endforeach ?>
  <div class="form-group sistta-span-all">
    <label for="userfile">Foto del conductor</label>
    
    <input class="form-control" type="file" id="userfile" name="userfile" accept="image/gif,image/jpeg,image/png">
    <?php if (!empty($error_arhivo)): ?><div class="sistta-field-error" role="alert"><?= $error_arhivo ?></div><?php endif ?>
  </div>
  <fieldset class="form-group">
    <legend>Estado</legend>
    <div class="sistta-options">
      <?php foreach (['1' => 'Activo', '0' => 'Inactivo'] as $valor => $texto): ?>
      <label><input type="radio" name="estado" value="<?= $valor ?>" required <?= (string) $estadoActual === (string) $valor ? 'checked' : '' ?> ><?= $texto ?></label>
      <?php endforeach ?>
    </div>
  </fieldset>
  <div class="form-group">
    <span class="sistta-field-label">Afiliaci&oacute;n</span>
    <?php if ((string) $afiliado_es === '1'): ?>
      <p class="help-block">Este conductor ya est&aacute; registrado como afiliado.</p>
    <?php else: ?>
      <div class="sistta-options"><label><input type="checkbox" name="es_afiliado" value="1" <?= !empty($nuevo_afiliado) ? 'checked' : '' ?> onchange="this.form.elements.nuevo_afiliado.value=this.checked ? '1' : '0'"> Registrar tambi&eacute;n como afiliado</label></div>
    <?php endif ?>
    <?php if (!empty($error_afiliado)): ?><div class="sistta-field-error" role="alert"><?= $error_afiliado ?></div><?php endif ?>
  </div>
</div>
<div class="sistta-form-actions"><button class="btn btn-primary" type="submit" name="guardar_conductor" value="1">Guardar conductor</button></div>
</form>
