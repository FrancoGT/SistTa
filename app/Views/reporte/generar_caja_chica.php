<h1>Caja Chica</h1>
<h3>Reporte de Caja Chica:</h3>
<h3>Fecha inicio</h3>
<?php
?>

<input type="text" required id="inicio" name="inicio" readonly value="<?php echo $ci->formato_d_m_a($inicio); ?>">
<h3>Fecha fin</h3>
<input type="text" required id="fin" name="fin" readonly value="<?php echo $ci->formato_d_m_a($fin); ?>">
<br>
<br>
<h3>
<table class='table table-bordered table-hover'>
  <tr>
    <th>Total Ingresos</th>
    <th>Total Egresos</th>
    <th>Ganancia</th>
    <th>P&eacute;rdida</th>
  </tr>
  <tr>
    <td><?= esc($suma_ingresos) ?></td>
    <td><?= esc($suma_egresos) ?></td>
    <td><?= esc($ganancia) ?></td>
    <td><?= esc($perdida) ?></td>
  </tr>
</table>
</h3>
<br>
