<h1>Encontrar Nuevo Conductor</h1>
<br>
<h3>
	<p>
	<?php
	  echo $mensaje;

	  echo view('tablas/ficha_conductor', ['filas' => $ci->ficha_conductor($id_person)]);
	?>
</h3>

<div align="center">
	<h3>

		<p>¿Desea asignarlo?
		<p>
		<form action="<?= base_url() ?>index.php/vehiculo/confirmar_nuevo_conductor_si" method="POST">
<?= csrf_field() ?>
			 <input type="hidden" id="id_person_si" name="id_person_si" value="<?= esc($id_person) ?>">
			 <input type="hidden" id="id_vehiculo_si" name="id_vehiculo_si" value="<?= esc($id_vehiculo) ?>">
			 <input type="hidden" id="anterior_id_person_si" name="anterior_id_person_si" value="<?= esc($anterior_id_person) ?>">
  	 <input type="submit" name="Si" id="Si" value="Si" />
		</form>
		<br>
		<form action="<?= base_url() ?>index.php/vehiculo/confirmar_nuevo_conductor_no" method="POST">
<?= csrf_field() ?>
			<input type="hidden" id="id_person_no" name="id_person_no" value="<?= esc($id_person) ?>">
			<input type="hidden" id="id_vehiculo_no" name="id_vehiculo_no" value="<?= esc($id_vehiculo) ?>">
			<input type="hidden" id="anterior_id_person_no" name="anterior_id_person_no" value="<?= esc($anterior_id_person) ?>">
			<input type="submit" name="no" id="no" value="no" />
		</form>
		<p>
	</h3>
</div>
