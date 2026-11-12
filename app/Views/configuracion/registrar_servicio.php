<h2>Nuevo servicio</h2>
<form data-async-form class="sistta-form-layout" action="<?= site_url('configuracion/guardar_servicio') ?>" method="post">
<?= csrf_field() ?>

<div class="sistta-form-grid">
  <div class="form-group">
    <label for="nombre_servicio">Nombre del servicio</label>
    <input class="form-control" type="text" id="nombre_servicio" name="nombre_servicio" required value="<?= esc($nombre_servicio ?? '') ?>" onkeypress="return AlphaNumeric(event)">
    <?php if (!empty($error_nombre_servicio)): ?><div class="sistta-field-error" role="alert"><?= $error_nombre_servicio ?></div><?php endif ?>
  </div>
  <div class="form-group">
    <label for="pago_diario">Pago diario</label>
    <input class="form-control" type="text" inputmode="decimal" id="pago_diario" name="pago_diario" required value="<?= esc($pago_diario ?? '') ?>" onkeypress="return isNumberKey(event)">
  </div>
  <fieldset class="form-group">
    <legend>Estado</legend>
    <div class="sistta-options">
      <?php foreach (['1' => 'Activo', '0' => 'Inactivo'] as $valor => $texto): ?>
      <label><input type="radio" name="estado" value="<?= $valor ?>" required <?= (string) ($estado ?? $estado_servicio ?? '') === (string) $valor ? 'checked' : '' ?>><?= $texto ?></label>
      <?php endforeach ?>
    </div>
  </fieldset>
  <fieldset class="form-group">
    <legend>Aplicar a</legend>
    <div class="sistta-options">
      <?php foreach (['1' => 'Afiliado', '2' => 'Conductor', '3' => 'Unidad'] as $valor => $texto): ?>
      <label><input type="radio" name="aplica" value="<?= $valor ?>" required <?= (string) ($aplica ?? '') === (string) $valor ? 'checked' : '' ?>><?= $texto ?></label>
      <?php endforeach ?>
    </div>
  </fieldset>
</div>
<div class="sistta-form-actions"><button class="btn btn-primary" type="submit" name="guardar_afiliado" value="1">Guardar servicio</button></div>
</form>
