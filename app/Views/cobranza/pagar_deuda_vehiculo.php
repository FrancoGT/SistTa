<h1>Nuevo Ingreso</h1>
<br>
<form action="<?= base_url() ?>index.php/cobranza/cancelar_pago_vehiculo" method ="POST">
<?= csrf_field() ?>
<p>Nº de operacion:
  <p for="num_operacion"></p>
  <input name="num_operacion" type="text" id="num_operacion" readonly="readonly" value='<?= esc($num_operacion) ?>' />
</p>
<p>Fecha:
  <p for="fecha"></p>
  <input type="text" name="fecha" id="fecha" readonly value="<?php echo $ci->formato_d_m_a($ci->fecha_actual()); ?>"/>
</p>
  <input type="hidden" name="doc_externo" id="doc_externo" />
<p>C&oacute;digo:
  <p for="cod_vehiculo"></p>
  <input type="text" name="cod_vehiculo" id="cod_vehiculo" value='<?= esc($cod_vehiculo) ?>'/>
</p>
<p>Placa:
  <p for="placa"></p>
  <input name="placa" type="text" id="placa" readonly="readonly" value='<?= esc($placa) ?>'/>
</p>
<?php
  echo view('cobranza/_deuda', ['filas' => $ci->deuda_periodo('vehiculo', $id_periodo, $id_vehiculo)]);
  $pago_pendiente = $ci->get_pago_pendiente_vehiculo($id_periodo, $id_vehiculo);
?>
<br>
<p>Pago:
  <p for="monto_provicional"></p>
  <input type="text" onkeypress="return isNumberKey(event)" required autocomplete="off" name="monto_provicional" id="monto_provicional" step=".01" />
  <?php
    if (isset($error_pago))
    {
      if ($error_pago != '')
        echo $error_pago;
    }
  ?>
</p>
<p>
  <p for="id_periodo"></p>
  <input type="hidden" name="id_periodo" id="id_periodo" value='<?= esc($id_periodo) ?>'/>
  <input type="hidden" name="id_vehiculo" id="id_vehiculo" value='<?= esc($id_vehiculo) ?>'/>
</p>
<p>
  <p for="id_usuario"></p>
  <input type="hidden" name="id_usuario" id="id_usuario" value='<?= esc($id_usuario) ?>'/>
  <input type="hidden" name="pago_pendiente" id="pago_pendiente" value='<?= esc($pago_pendiente) ?>'/>
</p>
  <input type="submit" name="guardar" id="guardar" value="Guardar" />
  <br>
  <p for="nombre_usuario">Realizado por:</p>
  <br>
  <input name="nombre_usuario" type="text" id="nombre_usuario" value='<?= esc($nombre_usuario) ?>' readonly="readonly" />
</form>
