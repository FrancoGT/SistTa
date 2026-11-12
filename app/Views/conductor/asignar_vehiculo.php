<h1>Asignar Veh&iacute;culo</h1>
<form data-async-form action="<?= base_url() ?>index.php/conductor/encontrar_vehiculo" method ="POST">
<?= csrf_field() ?>
<?php
  echo view('tablas/ficha_conductor', ['filas' => $ci->ficha_conductor($id_person_conductor)]);
?>
<br> </br>
<p>Ingrese C&oacute;digo o Placa del Veh&iacute;culo</p>
<br>
<input type="text" onkeypress="return AlphaNumeric(event)" autocomplete="off" id="data_vehiculo" name="data_vehiculo" size="24" maxlength="24" placeholder="Placa o c&oacute;digo del veh&iacute;culo" >
<input type="hidden" id="id_person_conductor" name="id_person_conductor" size="24" maxlength="24" value="<?= esc($id_person_conductor) ?>">
<input type="submit" name="buscar_vehiculo" id="buscar_vehiculo" value="Buscar Veh&iacute;culo" />

<br> </br>

</form>
