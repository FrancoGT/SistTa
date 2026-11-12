<form action="<?= base_url() ?>index.php/afiliado/guardar_afiliado" method ="POST">
<?= csrf_field() ?>
    <h1>Registrar Nuevo Afiliado</h1>
<?php
  if (!isset($cod_afiliado))
  {
    $cod_afiliado = "";
  }
  if (!isset($nombre))
  {
    $nombre = "";
  }
  if (!isset($documento))
  {
    $documento = "";
  }
  if (!isset($direccion))
  {
    $direccion = "";
  }
  if (!isset($telefono))
  {
    $telefono = "";
  }
?>
  <div class="form-group">
      <p>C&oacute;digo</p><br>
      <input autocomplete="off" type="text" onkeypress="return AlphaNumeric(event)" required id="cod_afiliado" name="cod_afiliado" placeholder="C&oacute;digo de Afiliado" value="<?= esc($cod_afiliado) ?>">
      <?php
        if (isset($error_cod_afiliado))
        {
          if ($error_cod_afiliado != '')
          {
            echo "<h3>".$error_cod_afiliado."</h3><br>";//
          }
        }
      ?>
  </div>

  <div class="form-group">
    <p>Nombre</p>
      <br>
      <input autocomplete="off" type="text" onkeypress="return ValidateAlpha(event)" required id="nombre" name="nombre" placeholder="Nombre de Afiliado" value="<?= esc($nombre) ?>">
  </div>

    <div class="form-group">
      <p>DNI</p><br>
      <input autocomplete="off" type="text" onkeypress="return isNumberKey(event)" required id="documento" name="documento" placeholder="DNI de Afiliado" value="<?= esc($documento) ?>">
      <?php
        if (isset($error_documento))
        {
          if ($error_documento != '')
          {
            echo "<h3>".$error_documento."</h3><br>";
          }
        }
      ?>
    </div>

  <div class="form-group">
      <p>Direcci&oacute;n</p>
      <br>
    <input autocomplete="off" type="text" onkeypress="return AlphaNumeric(event)" required id="direccion" name="direccion" placeholder="Direcci&oacute;n de Afiliado" value="<?= esc($direccion) ?>">
  </div>

    <div class="form-group">
    <p>Tel&eacute;fono</p>
    <br>
    <input autocomplete="off" type="text" onkeypress="return isNumberKey(event)" required id="telefono" name="telefono" placeholder="Tel&eacute;fono de Afiliado" value="<?= esc($telefono) ?>">
    <?php
      if (isset($error_telefono))
      {
        if ($error_telefono != '')
        {
          echo "<h3>".$error_telefono."</h3><br>";
        }
      }
    ?>
    </div>

  <div class="form-group">
    <p>
    <p>Estado</p>
    <p>
    <input autocomplete="off" type="radio" required  name="estado" value="1" id="estado" /> Activo
    </p>
    <p>
    <input autocomplete="off" type="radio" required name="estado" value="0" id="estado" />
          Inactivo
    </p>
    </p>
      <input autocomplete="off" type="submit" name="guardar_afiliado" id="guardar_afiliado" value="Guardar Afiliado">
  </div>

</form>