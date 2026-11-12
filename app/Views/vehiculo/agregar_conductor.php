<h1>Agregar Conductor</h1>
<br>
<form action="<?= base_url() ?>index.php/vehiculo/encontrar_conductor_add" method="POST">
<?= csrf_field() ?>
  <p>Ingrese DNI o C&oacute;digo de Conductor</p>
  <p>
    <input type="text" onkeypress="return AlphaNumeric(event)" name="data_conductor" autocomplete="off" id="data_conductor" />
    <input type="hidden" name="id_vehiculo" id="id_vehiculo" value="<?= esc($id_vehiculo) ?>"/>
    <br />
  </p>
        </p>
  <input type="submit" name="button" id="button" value="Buscar Conductor" />
</form>
