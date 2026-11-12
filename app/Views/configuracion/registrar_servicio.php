<h1>Registrar Nuevo Servicio</h1>
<br>
<?php
  if (!isset($nombre_servicio))
  {
    $nombre_servicio = '';
  }
  if (!isset($pago_diario))
  {
    $pago_diario = '';
  }
?>
<form action="<?= base_url() ?>index.php/configuracion/guardar_servicio" method ="POST">
<?= csrf_field() ?>
<p>Nombre del Servicio
  <label for="nombre_servicio"></label>
  <input type="text" onkeypress="return AlphaNumeric(event)" autocomplete="off" required name="nombre_servicio" id="nombre_servicio" value="<?= esc($nombre_servicio) ?>"/>
  <?php
      if (isset($error_nombre_servicio))
      {
        if ($error_nombre_servicio != '')
        {
          echo $error_nombre_servicio;
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
    <p>El servicio aplica a:</p>
    <p>
      <label>
        <input type="radio" required name="aplica" id="aplica" value="1" id="aplica_0" />
      Afiliado</label>
      <br />
      <label>
        <input type="radio" required name="aplica" value="2" id="aplica_1" />
        Conductor</label>
      <br />
      <label>
        <input type="radio" required name="aplica" value="3" id="aplica_2" />
        Unidad</label>
      <br />
    </p>
    <input type="submit" required name="guardar_afiliado" id="guardar_afiliado" value="Guardar Servicio">
</div>
</p>
</form>
