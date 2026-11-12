<h1>Actualizar Servicio</h1>
<br>
<form action="<?= base_url() ?>index.php/configuracion/actualizar_datos_servicio" method ="POST">
<?= csrf_field() ?>
<input type="hidden" id="id_servicio" name="id_servicio" value="<?= esc($id_servicio) ?>">
<p>Nombre del Servicio
  <label for="nombre_servicio"></label>
  <input type="text" autocomplete="off" onkeypress="return ValidateAlpha(event)" required name="nombre_servicio" id="nombre_servicio" value="<?= esc($nombre_servicio) ?>"/>
  <?php
    if (isset($error_nombre_servicio))
    {
      if ($error_nombre_servicio != '')
      {
        echo "<h3>".$error_nombre_servicio."</h3><br>";
      }
    }
  ?>
  <div class="form-group">
    <p>
    <label>Estado</label>
    <label>
    <input type="radio" required name="estado" value="1" id="estado" /> Activo</label>
        <label>
          <input type="radio" required name="estado" value="0" id="estado" />
          Inactivo</label>
    </p>
    <p>Pago diario
      <label for="pago_diario"></label>
      <input autocomplete="off" type="text" onkeypress="return isNumberKey(event)" required name="pago_diario" id="pago_diario" value="<?= esc($pago_diario) ?>"/>
    </p>
    <?php
    switch ($aplica)
    {
      case '1':
        $que_aplica = 'Afiliado';
        break;
      case '2':
        $que_aplica = 'Conductor';
        break;
      case '3':
        $que_aplica = 'Unidad';
        break;
    }
    ?>
    <h3>Por favor confirme a que se aplica el Servicio</h3>
    <h3>El Servicio Actual se aplica a: <?= esc($que_aplica) ?></h3>
    <p>El servicio aplica a:</p>
    <p>
      <label>
        <input type="radio" id="aplica" required name="aplica" value="1" id="Aplica_0" />
      Afiliado</label>
      <br />
      <label>
        <input type="radio" id="aplica" required name="aplica" value="2" id="Aplica_1" />
        Conductor</label>
      <br />
      <label>
        <input type="radio" id="aplica" required name="aplica" value="3" id="Aplica_2" />
        Unidad</label>
      <br />
    </p>
    <input type="submit" required name="guardar_servicio" id="guardar_servicio" value="Guardar Servicio">
</div>
</p>
</form>
