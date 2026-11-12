<h1>Actualizar Veh&iacute;culo</h1>

<form data-async-form action="<?= base_url() ?>index.php/afiliado/encontrar_vehiculo" method ="POST">
<?= csrf_field() ?>
<h3> <?php echo 'Afiliado: '.esc($nombre_afi); ?></h3>
<br> </br>
<h3>Asignar otro Veh&iacute;culo para el Afiliado</h3>
<input type="text" onkeypress="return AlphaNumeric(event)" autocomplete="off" id="data_vehiculo" name="data_vehiculo" size="24" maxlength="24" placeholder="Placa o c&oacute;digo del veh&iacute;culo" >
<input type="hidden" id="id_afi" name="id_afi" size="24" maxlength="24" value="<?= esc($id_afi) ?>">
<input type="submit" name="buscar_vehiculo" id="buscar_vehiculo" value="Buscar Veh&iacute;culo" />

<br> </br>

</form>
