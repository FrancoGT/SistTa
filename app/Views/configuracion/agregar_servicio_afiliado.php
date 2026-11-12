<h1>Agregar Servicio</h1>
<br>
<form data-async-form name="asignar_servicio" action="<?= base_url() ?>index.php/configuracion/encontrar_servicio_afiliado" method ="POST">
<?= csrf_field() ?>
<h3> Buscar Servicio </h3>
<br>
<p>Nombre del Servicio:
  <label for="data_servicio"></label>
  <input type="hidden" name="id_person_afiliado" id="id_person_afiliado" value="<?= esc($id_person_afiliado) ?>"/>
  <input type="text" onkeypress="return ValidateAlpha(event)" autocomplete="off" name="data_servicio" id="data_servicio" />
</p>
<p>
  <input type="submit" name="Buscar" id="Buscar" value="Buscar Servicio" />
</p>
<p>&nbsp; </p>
</form>
