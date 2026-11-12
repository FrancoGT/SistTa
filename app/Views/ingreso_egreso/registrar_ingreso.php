<script type="text/javascript">
  function Asignar_Comprobante()
  {
    if(document.registro.tipo_comprobante[0].checked)
    {
      document.getElementById("comprobante").value = 1;
    }
    if(document.registro.tipo_comprobante[1].checked)
    {
      document.getElementById("comprobante").value = 2;
    }
    if(document.registro.tipo_comprobante[2].checked)
    {
      document.getElementById("comprobante").value = 3;
    }
  }
</script>
<h1>Nuevo Ingreso</h1>
<br>
<?php
    if (!isset($num_operacion))
    {
      $num_operacion = $ci->get_num_operacion();
    }
    if (!isset($nombre))
    {
      $nombre = "";
    }
    if (!isset($documento))
    {
      $documento = "";
    }
    if (!isset($fecha))
    {
      $fecha = "";
    }
    if (!isset($doc_externo))
    {
      $fecha = "";
    }
    if (!isset($doc_externo))
    {
      $fecha = "";
    }
    if (!isset($nombre_usuario)
        || !isset($documento)
        || !isset($nombre))
    {
      $nombre_usuario = $ci->get_nombre_usuario();
      $documento = $ci->get_nombre_usuario();
    }
?>
<form name="registro" action="<?= base_url() ?>index.php/ingreso_egreso/guardar_ingreso" method ="POST">
<?= csrf_field() ?>
  <p>Nº de operacion:
    <p for="num_operacion"></p>
    <input name="num_operacion" type="text" id="num_operacion" readonly value='<?= esc($num_operacion) ?>' />
  </p>
<p>Fecha:
    <p for="fecha"></p>
    <input type="date" name="fecha" id="fecha" value='<?= esc($fecha) ?>' required/>
  </p>
  <!---<p>Nombre:
    <p for="nom_deudor"></p> -->
    <input type="hidden" name="nom_deudor" id="nom_deudor" value='<?= esc($nombre) ?>'/>
  </p>
  <!---
  <p>DNI:
    <p for="documento"></p> -->
    <input name="documento" type="hidden" id="documento" readonly value='<?= esc($documento) ?>'/>
</p>
  <p>Descripci&oacute;n:</p>
  <p for="descripcion"></p>
    <p for="descripcion"></p>
    <textarea autocomplete="off" onkeypress="return AlphaNumeric(event)" name="descripcion" id="descripcion" cols="45" rows="5"></textarea>
  </p>
  <p>Monto:
    <p for="monto_provicional2"></p>
    <input autocomplete="off" type="text" onkeypress="return isNumberKey(event)" required name="monto_provicional" id="monto_provicional" step=".01" />
  </p>
  <div class="form-section">
  <h3>Documento Externo (Opcional):</h3>
  <p class="help-block">
    Si no completas todos estos campos no se guardar&aacute; el Documento Externo.
  </p>
  <p>Documento Comprobante:
    <p for="doc_comprobante"></p>

    <input autocomplete="off" type="text" onkeypress="return AlphaNumeric(event)" name="doc_comprobante" id="doc_comprobante" />
    <br/>
    <p for="data_razon_social"></p>
    <br>
    <p>Proveedor:</p>
    <p for="proveedor"></p>
    <input autocomplete="off" type="text" name="proveedor" id="proveedor" />
  </p>
  <p>Tipo Comprobante:</p>
  <input type="hidden" name="comprobante" id="comprobante" value="0" />
  <p>
    <p>
      <input type="radio" name="tipo_comprobante" value="1" id="tipo_comprobante" onchange="Asignar_Comprobante();"/>
      Boleta</p>
    <br />
    <p>
      <input type="radio" name="tipo_comprobante" value="2" id="tipo_comprobante" onchange="Asignar_Comprobante();"/>
      Factura</p>
    <br />
    <p>
      <input type="radio" name="tipo_comprobante" value="3" id="tipo_comprobante" onchange="Asignar_Comprobante();"/>
      Otro</p>
    <br />
    <br/>
  </p>
</div>
  <p>
    <input type="submit" name="guardar" id="guardar" value="Guardar" />
</form>
    Realizado por:
    <p for="nombre_usuario"></p>
    <input name="nombre_usuario" type="text" id="nombre_usuario" value='<?= esc($nombre_usuario) ?>' readonly />
</p>
