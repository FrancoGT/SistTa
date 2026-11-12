<h1>Nuevo Ingreso</h1>
<?php
  $documento = $ci->getDocumento($id_person);
?>
<br>
<form action="<?= base_url() ?>index.php/cobranza/cancelar_pago" method ="POST">
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
<p>Nombre:
  <p for="nom_deudor"></p>
  <input type="text" name="nombre" id="nombre" value='<?= esc($nombre) ?>'/>
</p>
<p>DNI:
  <p for="documento"></p>
  <input name="documento" type="text" id="documento" readonly="readonly" value='<?= esc($documento) ?>'/>
  <input name="id_person" type="hidden" id="id_person" readonly="readonly" value='<?= esc($id_person) ?>'/>
</p>
<?php
  echo view('cobranza/_deuda', ['filas' => $ci->deuda_periodo('persona', $id_periodo, $id_person)]);
  $pago_pendiente = $ci->get_pago_pendiente($id_periodo, $id_person);
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
</p>
<p>
  <p for="id_usuario"></p>
  <input type="hidden" name="id_usuario" id="id_usuario" value='<?= esc($id_usuario) ?>'/>
  <input type="hidden" name="pago_pendiente" id="pago_pendiente" value='<?= esc($pago_pendiente) ?>'/>
</p>
  <input type="submit" name="guardar" id="guardar" value="Guardar" />
  <br>
  Realizado por:
  <br>
  <p for="nombre_usuario"></p>
  <input name="nombre_usuario" type="text" id="nombre_usuario" value='<?= esc($nombre_usuario) ?>' readonly="readonly" />
</form>
