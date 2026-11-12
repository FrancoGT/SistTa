<h1>Confirmar</h1>
<br>
<form data-async-form name="asignar_servicio" action="<?= base_url() ?>index.php/cobranza/guardar_servicio_vehiculo" method ="POST">
<?= csrf_field() ?>
<h3>
  <?php
      $nombre_serv = $ci->get_nombre_servicio($id_servicio);
    echo 'Nombre del Servicio: '.esc($nombre_serv);
  ?>
</h3>
<h3>
  <p>
    <input name="id_serv" type="hidden" id="id_serv" value="<?= esc($id_servicio) ?>" />
    <input name="id_vehi" type="hidden" id="id_vehi" value="<?= esc($id_vehi) ?>" />
    </p>
    <?php
      echo view('tablas/ficha_vehiculo', ['filas' => $ci->ficha_vehiculo($id_vehi)]);
    ?>
</h3>

<p>Fecha de inicio de servicio: </p>
<label for="inicio_pago"></label>
<input id="inicio_pago" name="inicio_pago" type="date" required>
<div align="center"><h3> <p>¿Estas seguro(a) de asignar el servicio al Vehiculo?</h3> </p>  </div>
<p>
  <div align="center"> <input type="submit" name="si" id="si" value="Si" /> </div>
</p>
</form>

<form data-async-form action="<?= base_url() ?>index.php/configuracion" method ="POST">
<?= csrf_field() ?>
  <div align="center"> <input type="submit" name="no" id="no" value="No" /> </div>
</form>
