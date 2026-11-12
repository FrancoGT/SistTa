<h1>Agregar Servicio</h1>
<br>
<form data-async-form name="asignar_servicio" action="<?= base_url() ?>index.php/cobranza/encontrar_servicio_vehiculo" method ="POST">
<?= csrf_field() ?>
<h3> Buscar Servicio</h3>
<p>Nombre del Servicio:
  <label for="data_servicio"></label>
  <input type="text" autocomplete="off" onkeypress="return AlphaNumeric(event)" name="data_servicio" id="data_servicio" />
  <input type="hidden" name="id_vehi" id="id_vehi" value="<?= esc($id_vehiculo) ?>" />
</p>
<p>
  <input type="submit" name="Buscar" id="Buscar" value="Buscar Servicio" />
</p>
<p>&nbsp; </p>
</form>
