<h1>Cobranza Detalle</h1>
<?php
  echo view('tablas/ficha_vehiculo', ['filas' => $ci->ficha_vehiculo($id_vehiculo)]);
  echo view('cobranza/_servicios', [
    'bloque' => $ci->servicios_cobranza('vehiculo', $id_vehiculo),
    'titulo' => 'Servicios del Veh&iacute;culo',
    'vacio'  => 'El Veh&iacute;culo no tiene ning&uacute;n servicio asignado',
  ]);
?>
