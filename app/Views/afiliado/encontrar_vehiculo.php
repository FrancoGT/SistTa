<h1>Confirmar</h1>
<h3>
    <p>Datos del Afiliado</p>
</h3>
<?php
  $documento = $ci->getDocumento($id_person_afi);
?>
<form action="<?= base_url() ?>index.php/afiliado/guardar_agregar" method="POST" >
<?= csrf_field() ?>
<p>Documento:
  <input name="id_person_afi" type="hidden" id="id_person_afi" value="<?= esc($id_person_afi) ?>" readonly="readonly" />
  <input name="documento" type="text" id="documento" value="<?= esc($documento) ?>" readonly="readonly" />
</p>
<p>Nombre:
  <label for="nom_Afi"></label>
  <input name="nom_afi" type="text" id="nom_afi" value="<?= esc($nom_afi) ?>" readonly="readonly" />
</p>
<p>C&oacute;digo de Afiliado:
  <label for="cod_afi"></label>
  <input name="cod_afi" type="text" id="cod_afi" value="<?= esc($cod_afi) ?>" readonly="readonly" />
</p>
<h3>
<p>Datos del Veh&iacute;culo</p>
</h3>
<p>Placa:
  <label for="placa"></label>
  <input name="placa" type="text" id="placa" value="<?= esc($placa) ?>" readonly="readonly" />
</p>
<p>C&oacute;digo del Veh&iacute;culo:
  <label for="cod_vehiculo"></label>
  <input name="cod_vehiculo" type="text" id="cod_vehiculo" value="<?= esc($cod_vehiculo) ?>" readonly="readonly" />
</p>
<div align="center"> <h3> <p>¿Est&aacute;s seguro(a) de asignar el veh&iacute;culo al Afiliado?</h3> </p>
</div>
<p>
  <div align="center"> <input type="submit" name="si" id="si" value="Si" </div>
</p>

<input name="id_vehiculo" type="hidden" id="id_vehiculo" value="<?= esc($id_vehiculo) ?>" readonly="readonly" />

</form>

<form action="<?= base_url() ?>index.php/afiliado" method ="POST">
<?= csrf_field() ?>
  <div align="center"> <input type="submit" name="no" id="no" value="No" /> </div>
</form>
