<h1>Aplicar a Unidad el Servicio</h1>
<br>
<form data-async-form action="<?= base_url() ?>index.php/configuracion/encontrar_vehiculo" method ="POST">
<?= csrf_field() ?>
<h3> <?php echo 'Nombre del Servicio: '.esc($nombre_serv); ?></h3>
<br>
<p> </p>
	<div class="form-group">
	<h3>
      <label>Ingrese placa o c&oacute;digo del Veh&iacute;culo</label>
      <br>
  </h3>
      <input type="text" onkeypress="return AlphaNumeric(event)" autocomplete="off" id="bus_vehi" name="bus_vehi">
  	</div>
<p>
	<div class="form-group">
      <input type="hidden" name="id_servicio" id="id_servicio" value="<?= esc($id_servicio) ?>">
  	</div>
</p>
<p>
 &nbsp;&nbsp;
  <input type="submit" name="data_vehi" id="data_vehi" value="Buscar Veh&iacute;culo" />
</p>
</form>
