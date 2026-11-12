<h1>Caja Chica</h1>
<form method="POST" action="<?= base_url() ?>index.php/reporte/generar_caja_chica">
<?= csrf_field() ?>
<h3>Ingrese un rango de fechas para generar el Reporte</h3>
<h3>Fecha inicio</h3>
<input type="date" required id="inicio" name="inicio" >
<h3>Fecha fin</h3>
<input type="date" required id="fin" name="fin" >
<br>
<br>
<input type="submit" id="generar_caja_chica" name="generar_caja_chica" value="Generar Reporte">
</form>
