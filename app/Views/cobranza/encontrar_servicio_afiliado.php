<h1>Confirmar</h1>
<br>
<form name="asignar_servicio" action="<?= base_url() ?>index.php/cobranza/guardar_servicio_afiliado" method ="POST">
<?= csrf_field() ?>
<?php
  $nombre_serv = $ci->get_nombre_servicio($id_servicio);
  echo '<h3>Nombre del Servicio: '.esc($nombre_serv).'</h3><br>';
?>
  <p>
    <input name="id_serv" type="hidden" id="id_serv" value="<?= esc($id_servicio) ?>" />
    <input name="id_person_afiliado" type="hidden" id="id_person_afiliado" value="<?= esc($id_person_afiliado) ?>" />
  </p>
  <?php
    echo view('tablas/ficha_afiliado', ['filas' => $ci->ficha_afiliado($id_person_afiliado)]);
  ?>
<br>
<p>Fecha de inicio de servicio: </p>
<label for="inicio_pago"></label>
<input id="inicio_pago" name="inicio_pago" type="date" required>
<div align="center"><h3> <p>¿Estas seguro(a) de asignar el servicio al Afiliado?</h3> </p>  </div>
<p>
  <div align="center"> <input type="submit" name="si" id="si" value="Si" /> </div>
</p>
</form>

<form action="<?= base_url() ?>index.php/cobranza" method ="POST">
<?= csrf_field() ?>
  <div align="center"> <input type="submit" name="no" id="no" value="No" /> </div>
</form>
