<h1>Agregar Propietario</h1>
<br>
<form action="<?= base_url() ?>index.php/vehiculo/encontrar_propietario_add" method="POST">
<?= csrf_field() ?>
  <p>Ingrese DNI o C&oacute;digo de Afiliado</p>
  <p>
    <input type="text" onkeypress="return AlphaNumeric(event)" name="data_afiliado" autocomplete="off" id="data_afiliado" />
    <input type="hidden" name="id_vehiculo" id="id_vehiculo" value="<?= esc($id_vehiculo) ?>"/>
    <br />
  </p>
        </p>
  <input type="submit" name="button" id="button" value="Buscar Afiliado" />
</form>
