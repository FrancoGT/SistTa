<h1>Registrar Nuevo Veh&iacute;culo</h1>
<form name="formu" action = "<?= base_url() ?>index.php/vehiculo/" method="POST">
<?= csrf_field() ?>
  <p>
  <?php
    $link = "<?= base_url() ?>index.php/vehiculo/";
	if (!isset($cod_vehiculo)) 
	{
    	$cod_vehiculo = "";
	}
	if (!isset($dni_afiliado)) 
	{
    	$dni_afiliado = "72703963";
	}
	if (!isset($placa)) 
	{
    	$placa = "";
	}
	if (!isset($deuda)) 
	{
    	$deuda = "12.00";
	}
	if (!isset($dni_conductor)) 
	{
    	$dni_conductor = "";
	}
	if (!isset($estado)) 
	{
    	$estado = "-3";
	}
?>
  <input type="hidden" name="oculto">
  <div class="form-group" >
      <label>C&oacute;digo</label>
      <input type="text" required id="cod_vehiculo" name="cod_vehiculo" placeholder="C&oacute;digo de Vehiculo" autocomplete="off" value="<?= esc($cod_vehiculo) ?>">
      <br> <br>
      <label>Afiliado</label> 
      <label for="data_afiliado"></label>
      <input type="text" name="data_afiliado" id="data_afiliado" />
  </p>
  <p><br>
    </p>
    <p>
      <input type="submit" name="buscar_afiliado" id="asignar_conductor2" value="Buscar Afiliado" oClick="elegir_modulo('buscar_afiliado')/>
  </p>
    <p>
      <label for="dni_afiliado">
      </label>
    </p>
    <p>DNI de Afiliado</p>
    <p>
      <input name="dni_afiliado" type="text" id="dni_afiliado" readonly="readonly"  value="<?= esc($dni_afiliado) ?>" />
  </p>
    </p>
  <div class="form-group">
    <label>Placa</label>
      <br>
    <input type="text" required id="placa" name="placa" placeholder="Placa del Vehiculo" value="<?= esc($placa) ?>">
  </div>
    <div class="form-group">
    <label>Deuda</label>
    <br>
    <input type="number" id="deuda" name="deuda" readonly="readonly" value="<?= esc($deuda) ?>">
    </div>
  <div class="form-group">
   <p>
    <label>Estado</label>
    <label>
    	<?php  
    		if ($estado == '1') 
    			echo "<input type='radio' required  name='estado' value='1' id='estado' checked> Activo</label>"; 
    		else 
    			echo "<label>
					    <input type='radio' required  name='estado' value='1' id='estado' /> Activo
					</label>";
    	?>
    	<?php  
    		if ($estado == '0') 
    			echo "<input type='radio' required name='estado' value='0' id='estado' 
    					checked>Inactivo</label>"; 
    		else 
    			echo "<input type='radio' required name='estado' value='0' id='estado' 
    					>Inactivo</label>"; 
         ?>
    </label>
    </p>
    <p>
      <input type="submit" name="asignar_conductor" id="asignar_conductor" value="Asignar Conductor" onClick="elegir_modulo('asignar_conductor')"/>
    </p>
      </p>
  </div>
    <input type="submit" name="guardar_vehiculo" id="guardar_vehiculo" value="Guardar" onClick="elegir_modulo('guardar_vehiculo')"/>


<div class="form-group">
  <script type="text/javascript">
	function elegir_modulo(tipo)
	{
		if (tipo=="buscar_afiliado")
		{
			document.forms.formu.oculto.valu="buscar_afiliado";
			document.forms.formu.action="<?= base_url() ?>index.php/vehiculo/buscar_afiliado"
			document.forms.formu.submit();
		}
		else if (tipo=="asignar_conductor")
		{
			document.forms.formu.oculto.valu="asignar_conductor";
			document.forms.formu.action="<?= base_url() ?>index.php/vehiculo/asignar_conductor"
			document.forms.formu.submit();
		}
		else if (tipo=="guardar_vehiculo")
		{
			document.forms.formu.oculto.valu="guardar_vehiculo";
			document.forms.formu.action="<?= base_url() ?>index.php/vehiculo/guardar_vehiculo"
			document.forms.formu.submit();
		}
	}
</script>
</form>
<br>
<div align="center">

  <?php
    echo view('tablas/conductores_activos', ['conductores' => $ci->listar_conductores_activos()]);
  ?>
</div>
