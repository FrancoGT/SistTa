<h1>Cobranza Detalle</h1>
<?php
  echo view('tablas/ficha_conductor', ['filas' => $ci->ficha_conductor($id_person_conductor)]);
  echo view('cobranza/_servicios', [
    'bloque' => $ci->servicios_cobranza('conductor', $id_person_conductor),
    'titulo' => 'Servicios que paga el Conductor',
    'vacio'  => 'El Conductor no tiene ning&uacute;n servicio asignado',
  ]);
?>
