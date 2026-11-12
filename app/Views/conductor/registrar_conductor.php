<script type="text/javascript">
  function Guardar_Afiliado()
  {
    if (document.getElementById("nuevo_afiliado").value == 0)
    {
      document.getElementById("nuevo_afiliado").value = 1;
    }
    else
    {
      document.getElementById("nuevo_afiliado").value = 0;
    }
  }
  function Copiar_Doc()
  {
    document.getElementById("doc_copia").value = document.getElementById("documento").value;
    var dni = document.getElementById("documento").value;
  }
</script>
<?php
  if (!isset($cod_conductor))
  {
    $cod_conductor = "";
  }
  if (!isset($brevete))
  {
    $brevete = "";
  }
  if (!isset($nombre_persona))
  {
    $nombre_persona = "";
  }
  if (!isset($documento))
  {
    $documento = "";
  }
  if (!isset($direccion))
  {
    $direccion = "";
  }
  if (!isset($telefono_persona))
  {
    $telefono_persona = "";
  }

  if (!isset($afiliado_es) && $documento == '')
  {
    $afiliado_es = '';
  }
  if (!isset($afiliado_es) && $documento != '')
  {
    $afiliado_es = $ci->es_afiliado($documento);
  }
?>
<h1>Registrar Nuevo Conductor</h1>
<?php echo form_open_multipart('conductor/guardar_conductor');?>
  <div class="form-group">
      <p>C&oacute;digo</p><br>
      <input autocomplete="off" type="text" onkeypress="return AlphaNumeric(event)" required id="cod_conductor" name="cod_conductor" placeholder="Codigo de Conductor" value="<?= esc($cod_conductor) ?>">
      <?php
        if (isset($error_cod_conductor))
        {
          if ($error_cod_conductor != '')
          {
            echo "<h3>".$error_cod_conductor."</h3><br>";
          }
        }
      ?>
  </div>

   <div class="form-group">
      <p>DNI</p><br>
      <input autocomplete="off" type="text" onkeypress="return isNumberKey(event)" required id="documento" name="documento" placeholder="DNI de Conductor" value="<?= esc($documento) ?>" onkeyup="Copiar_Doc();">
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
    <p>Brevete</p>
      <br>
      <input autocomplete="off" type="text" onkeypress="return AlphaNumeric(event)" required id="brevete" name="brevete" placeholder="Brevete del conductor" value="<?= esc($brevete) ?>">
      <?php
        if (isset($error_brevete))
        {
          echo $error_brevete;
        }
      ?>
  </div>
  <div class="form-group">
    <p>Nombre</p>
      <br>
      <input autocomplete="off" type="text" onkeypress="return ValidateAlpha(event)" required id="nombre_persona" name="nombre_persona" placeholder="Nombre de Conductor" value="<?= esc($nombre_persona) ?>">
    </div>
    <p>Foto del Conductor:</p>
    <input requiered type="file" accept="image/gif,image/jpeg,image/png" id="userfile" name="userfile" size="20" />
    <?php
      if (isset($error_arhivo))
      echo $error_arhivo;
    ?>
    <br /><br />

  <div class="form-group">
      <p>Direcci&oacute;n</p>
      <br>
    <input autocomplete="off" type="text" onkeypress="return AlphaNumeric(event)" required id="direccion" name="direccion" placeholder="Direccion de Conductor" value="<?= esc($direccion) ?>">
  </div>


<div class="form-group">
    <p>Tel&eacute;fono</p>
    <br>
    <input autocomplete="off" type="text" onkeypress="return isNumberKey(event)" required id="telefono_persona" name="telefono_persona" placeholder="Telefono de Conductor" value="<?= esc($telefono_persona) ?>">
    </div>
    <?php
      if (isset($error_telefono))
      {
        if ($error_telefono != '')
        {
          echo "<h3>".$error_telefono."</h3><br>";
        }
      }
    ?>
<div class="form-group">

    <br>
    <input autocomplete="off" type="hidden" id="deuda" name="deuda" readonly="readonly">
    </div>

  <div class="form-group">
    <p>
    <p>Estado</p>
    <p>
    <input autocomplete="off" type="radio" required name="estado" value="1" id="estado" /> Activo</p>
        <p>
          <input autocomplete="off" type="radio" required name="estado" value="0" id="estado" />
          Inactivo</p>
    </p>

  <div class="form-group">
    <?php
      if ($afiliado_es == '1')
      {
        echo "<h3>Este Conductor ya fue registrado como Afiliado tambi&eacute;n</h3><br>";
        echo "<element hidden>";
      }
      else
      {
        echo "<h3>Si el Nuevo Conductor ya fue registrado como Afiliado <br> No es necesario marcar la casilla de abajo </h3>";
        echo "<P>Es afiliado?";
      }
    ?>
      <input autocomplete="off" type="checkbox" id="es_afiliado" name="es_afiliado" value="1" onclick="Guardar_Afiliado();">
      <br>
      <?php
        if ($afiliado_es == '1')
        {
          echo "</element>";
        }
      ?>
      </P>
  </div>
    <input autocomplete="off" type="hidden" name="nuevo_afiliado" id="nuevo_afiliado" value="0" />
    <input autocomplete="off" type="submit" name="guardar_conductor" id="guardar_conductor" value="Guardar Conductor">
  </div>
</form>
