<?php
  echo $mensaje2;
  echo view('tablas/ficha_conductor', ['filas' => $ci->ficha_conductor($id_person_conductor)]);
?>
<div align="center">
	<h3>

		<p>¿Desea asignarlo?
		<p>
		<form action="<?= base_url() ?>index.php/vehiculo/confirmar_conductor_si" method="POST">
<?= csrf_field() ?>
			 <input type="hidden" id="cod_vehiculo_si" name="cod_vehiculo_si" value="<?= esc($cod_vehiculo) ?>">
			 <input type="hidden" required name="data_afiliado_si" id="data_afiliado_si" value="<?= esc($data_afiliado) ?>"/>
			 <input type="hidden" required name="id_person_afiliado_si" id="id_person_afiliado_si" value="<?= esc($id_person_afiliado) ?>"/>
			 <input type="hidden" required name="placa_si" id="placa_si" value="<?= esc($placa) ?>"/>
			 <input type="hidden" required name="estado_si" id="estado_si" value="<?= esc($estado_vehiculo) ?>"/>
			 <input type="hidden" required name="data_conductor_si" id="data_conductor_si" value="<?= esc($data_conductor) ?>"/>
			 <input type="hidden" required name="id_person_conductor_si" id="id_person_conductor_si" value="<?= esc($id_person_conductor) ?>"/>
			 <input type="submit" name="Si" id="Si" value="Si" />
		</form>
		<br>
		<form action="<?= base_url() ?>index.php/vehiculo/confirmar_conductor_no" method="POST">
<?= csrf_field() ?>
			 <input type="hidden" id="cod_vehiculo_no" name="cod_vehiculo_no" value="<?= esc($cod_vehiculo) ?>">
			 <input type="hidden" required name="data_afiliado_no" id="data_afiliado_no" value="<?= esc($data_afiliado) ?>"/>
			 <input type="hidden" required name="id_person_afiliado_no" id="id_person_afiliado_no" value="<?= esc($id_person_afiliado) ?>">
			 <input type="hidden" required name="placa_no" id="placa_no" value="<?= esc($placa) ?>"/>
			 <input type="hidden" required name="estado_no" id="estado_no" value="<?= esc($estado_vehiculo) ?>"/>
			 <input type="hidden" required name="data_conductor_no" id="data_conductor_no" value="<?= esc($data_conductor) ?>"/>
			 <input type="hidden" required name="id_person_conductor_no" id="id_person_conductor_no" value=""/>
			 <input type="submit" name="No" id="No" value="No" />
		</form>
		<p>
	</h3>
</div>
