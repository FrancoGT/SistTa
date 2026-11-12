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
</script>
<?php
  if (!isset($estado_afiliado))
  {
    $estado_afiliado = $estado_nuevo;
  }
?>
<form name="actualizar" action="<?= base_url() ?>index.php/afiliado/actualizar_datos" method ="POST">
<?= csrf_field() ?>
    <h1>Actualizar Afiliado</h1>
  <input type="hidden" required id="id_afiliado" name="id_afiliado" value="<?= esc($id_afiliado) ?>">
  <input type="hidden" required id="id_persona" name="id_persona" value="<?= esc($id_persona) ?>">
  <div class="form-group">
      <p>C&oacute;digo</p><br>
      <input autocomplete="off" type="text" onkeypress="return AlphaNumeric(event)" required id="cod_afiliado" name="cod_afiliado" placeholder="C&oacute;digo de Afiliado" value="<?= esc($cod_afiliado) ?>">
      <?php
        if (isset($error_cod_afiliado))
        {
          if ($error_cod_afiliado != '')
          {
            echo "<h3>".$error_cod_afiliado."</h3><br>";
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
    <h3>Confirmar el Estado del Afiliado
      <br>
    <?php
      if ($estado_afiliado == '1')
      {
        echo "Estado Actual de Afiliado: Activo <br>";
      }
      else
      {
        echo "Estado Actual de Afiliado: Inactivo <br>";
      }
    ?>
    </h3>
    <p>
      <p>Estado</p>
      <p>
        <input autocomplete="off" type="radio" required  name="estado" value="1" id="estado" onClick="Copiar_Estado();" /> Activo
      </p>
      <p>
        <input autocomplete="off" type="radio" required name="estado" value="0" id="estado" onClick="Copiar_Estado();" />
        Inactivo
      </p>
    </p>
    <p>
      <input autocomplete="off" id="id_afiliado" name="id_afiliado" type="hidden" value="<?= esc($id_afiliado) ?>" />
      <input autocomplete="off" type="hidden" id="estado_nuevo" name="estado_nuevo" value="<?= esc($estado_afiliado) ?>">
    </p>
      <input autocomplete="off" type="submit" name="guardar_afiliado" id="guardar_afiliado" value="Actualizar Afiliado">
  </div>
</form>
