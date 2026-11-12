<?php
$num_operacion ??= $ci->get_num_operacion();
$nombre_usuario ??= $ci->get_nombre_usuario();
?>
<h2>Nuevo ingreso</h2>
<form data-async-form class="sistta-form-layout" name="registro" action="<?= site_url('ingreso_egreso/guardar_ingreso') ?>" method="post">
<?= csrf_field() ?>
<input type="hidden" name="nom_deudor" value="<?= esc($nombre ?? '') ?>">
<input type="hidden" name="documento" value="<?= esc($documento ?? '') ?>">
<input type="hidden" name="comprobante" value="<?= esc($comprobante ?? '0') ?>">
<div class="sistta-form-grid">
  <div class="form-group">
    <label for="num_operacion">N&uacute;mero de operaci&oacute;n</label>
    <input class="form-control" type="text" id="num_operacion" name="num_operacion" value="<?= esc($num_operacion) ?>" readonly>
  </div>
  <div class="form-group">
    <label for="fecha">Fecha</label>
    <input class="form-control" type="date" id="fecha" name="fecha" value="<?= esc($fecha ?? '') ?>" required>
  </div>
  <div class="form-group">
    <label for="monto_provicional">Monto</label>
    <input class="form-control" type="text" inputmode="decimal" id="monto_provicional" name="monto_provicional" required value="<?= esc($monto_provicional ?? '') ?>" onkeypress="return isNumberKey(event)">
  </div>
  <div class="form-group">
    <label for="nombre_usuario">Realizado por</label>
    <input class="form-control" type="text" id="nombre_usuario" name="nombre_usuario" value="<?= esc($nombre_usuario) ?>" readonly>
  </div>
  <div class="form-group sistta-span-all">
    <label for="descripcion">Descripci&oacute;n</label>
    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" onkeypress="return AlphaNumeric(event)"><?= esc($descripcion ?? '') ?></textarea>
  </div>
</div>
<section class="sistta-form-section">
  <h3>Comprobante externo <span class="text-muted">(opcional)</span></h3>
  <p class="help-block">Completa el documento, proveedor y tipo para guardar el comprobante.</p>
  <div class="sistta-form-grid">
    <div class="form-group">
      <label for="doc_comprobante">Documento del comprobante</label>
      <input class="form-control" type="text" id="doc_comprobante" name="doc_comprobante" value="<?= esc($doc_comprobante ?? '') ?>" onkeypress="return AlphaNumeric(event)">
    </div>
    <div class="form-group">
      <label for="proveedor">Proveedor</label>
      <input class="form-control" type="text" id="proveedor" name="proveedor" value="<?= esc($proveedor ?? '') ?>">
    </div>
    <fieldset class="form-group sistta-span-all">
      <legend>Tipo de comprobante</legend>
      <div class="sistta-options">
        <?php foreach (['1' => 'Boleta', '2' => 'Factura', '3' => 'Otro'] as $valor => $texto): ?>
        <label><input type="radio" name="tipo_comprobante" value="<?= $valor ?>" <?= (string) ($comprobante ?? '') === (string) $valor ? 'checked' : '' ?> onchange="this.form.elements.comprobante.value=this.value"><?= $texto ?></label>
        <?php endforeach ?>
      </div>
    </fieldset>
  </div>
</section>
<div class="sistta-form-actions"><button class="btn btn-primary" type="submit" name="guardar" value="1">Guardar ingreso</button></div>
</form>
