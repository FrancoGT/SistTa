<h2>Caja chica</h2>
<form data-async-form class="sistta-form-layout" method="post" action="<?= site_url('reporte/generar_caja_chica') ?>">
<?= csrf_field() ?>
<p class="help-block">Selecciona el rango de fechas del reporte.</p>
<div class="sistta-form-grid">
  <div class="form-group"><label for="inicio">Fecha de inicio</label><input class="form-control" type="date" id="inicio" name="inicio" required></div>
  <div class="form-group"><label for="fin">Fecha de fin</label><input class="form-control" type="date" id="fin" name="fin" required></div>
</div>
<div class="sistta-form-actions"><button class="btn btn-primary" type="submit" name="generar_caja_chica" value="1">Generar reporte</button></div>
</form>
