<script type="text/javascript">
  function Copiar_Estado()
  {
    if(document.actualizar.estado[0].checked)
    {
      document.getElementById("estado_nuevo").value = document.actualizar.estado[0].value;
    }
    if(document.actualizar.estado[1].checked)
    {
      document.getElementById("estado_nuevo").value = document.actualizar.estado[1].value;
    }
  }
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
</script>
<?php
  if (!isset($estado_conductor))
  {
    $estado_conductor = $estado_nuevo;
  }
?>
<?php
  if (!isset($afiliado_es))
  {
    $afiliado_es = $ci->es_afiliado($id_person_conductor);
  }
?>
<h1>Actualizar Conductor</h1>
<?php echo form_open_multipart('conductor/actualizar_datos', 'name="actualizar"');?>
  <div class="form-group">
      <input type="hidden" id="id_conductor" name="id_conductor" value="<?= esc($id_conductor) ?>">
      <input autocomplete="off" type="hidden" id="estado_nuevo" name="estado_nuevo" value="<?= esc($estado_conductor) ?>">
      <input type="hidden" id="id_person_conductor" name="id_person_conductor" value="<?= esc($id_person_conductor) ?>">
      <input type="hidden" id="foto" name="foto" value="<?= esc($foto) ?>">
      <input type="hidden" name="txt_dni_antiguo" value="<?= esc($documento) ?>" >
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
      <input autocomplete="off" type="text" onkeypress="return isNumberKey(event)" type="number" required id="documento" name="documento" placeholder="DNI de Conductor" value="<?= esc($documento) ?>" >
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
          if ($error_brevete != '')
          {
            echo "<h3>".$error_brevete."</h3><br>";
          }
        }
      ?>
  </div>
  <div class="form-group">
    <p>Nombre</p>
      <br>
      <input autocomplete="off" type="text" onkeypress="return ValidateAlpha(event)" required id="nombre_persona" name="nombre_persona" placeholder="Nombre de Conductor" value="<?= esc($nombre_persona) ?>">
    </div>
    <p>Foto del Conductor:</p>
    <p>Foto Actual:</p>
    <?php
      $ci->getNombre_foto($foto);
    ?>
    <p>Si desea cambiarla suba otra imagen</p>
    <input type="file" requiered accept="image/gif,image/jpeg,image/png" id="userfile" name="userfile" size="20" />
    <?php
      if (isset($error_arhivo))
      echo $error_arhivo;
    ?>
    <br /><br />

  <div class="form-group">
      <p>Direcci&oacute;n</p>
      <br>
    <input autocomplete="off" type="text" required id="direccion_persona" onkeypress="return AlphaNumeric(event)" name="direccion_persona" placeholder="Direccion de Conductor" value="<?= esc($direccion_persona) ?>">
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
    <input autocomplete="off" type="radio" required name="estado" value="1" id="estado" onchange="Copiar_Estado();"/> Activo</p>
        <p>
          <input autocomplete="off" type="radio" required name="estado" value="0" id="estado" onchange="Copiar_Estado();"/>
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
        echo "<P>¿Es afiliado?";
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
