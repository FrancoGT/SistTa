<?php
  echo view('tablas/ficha_afiliado', ['filas' => $ci->ficha_afiliado($id_person)]);
?>
<div align="center">
	<h3>

    <p>¿Desea eliminarlo?
		<p>
		<form action="<?= base_url() ?>index.php/vehiculo/quitar_propietario_si" method="POST">
<?= csrf_field() ?>
       <input type="hidden" id="id_vehiculo_si" name="id_vehiculo_si" value="<?= esc($id_vehiculo) ?>">
			 <input type="hidden" required name="id_person_propietario_si" id="id_person_propietario_si" value="<?= esc($id_person) ?>"/>
			 <input type="submit" name="Si" id="Si" value="Si" />
		</form>
		<br>
    <form action="<?= base_url() ?>index.php/vehiculo/quitar_propietario_no" method="POST">
<?= csrf_field() ?>
       <input type="hidden" id="id_vehiculo_no" name="id_vehiculo_no" value="<?= esc($id_vehiculo) ?>">
			 <input type="hidden" required name="id_person_propietario_no" id="id_person_propietario_no" value="<?= esc($id_person) ?>"/>
			 <input type="submit" name="No" id="No" value="No" />
		</form>
		<p>
	</h3>
</div>
