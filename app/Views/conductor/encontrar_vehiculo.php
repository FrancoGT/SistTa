<h1>Confirmar</h1>
<?php
  echo view('tablas/ficha_conductor', ['filas' => $ci->ficha_conductor($id_person_conductor)]);
  echo view('tablas/ficha_vehiculo', ['filas' => $ci->ficha_vehiculo($id_vehiculo)]);
?>
<form method="POST" action="<?= base_url() ?>index.php/conductor/guardar_asignar">
<?= csrf_field() ?>
<div align="center"> <h3> <p>¿Est&aacute;s seguro(a) de asignar el veh&iacute;culo al Conductor?</h3> </p>
</div>
<p>
  <div align="center"> <input type="submit" name="si" id="si" value="Si"/> </div>
</p>

<input name="id_vehiculo" type="hidden" id="id_vehiculo" value="<?= esc($id_vehiculo) ?>"/>
<input name="id_person_conductor" type="hidden" id="id_person_conductor" value="<?= esc($id_person_conductor) ?>"/>
</form>

<form action="<?= base_url() ?>index.php/conductor/volver" method ="POST">
<?= csrf_field() ?>
  <div align="center"> <input type="submit" name="no" id="no" value="No" /> </div>
</form>
