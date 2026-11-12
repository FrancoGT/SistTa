<h3>Cobranza Vehiculo</h3>
<p><br />
  <a id="nuevo_afiliado" href="<?= base_url() ?>index.php/afiliado/registrar_afiliado" >Nuevo Afiliado</a> 
  <p>  
  <table class="table table-bordered table-hover">
  <tr>
    <th width="97">Codigo</th>
    <th width="189">Nombre</th>
    <th width="86">Vehiculos</th>
    <th width="64">Estado</th>
    <th width="141">&nbsp;</th>
  </tr>
  <?php
  $query = $ci->db->query('SELECT * FROM `afiliado` ');
  foreach($query->getResult() as $row)
  {
      echo "<tr>";
      echo "<td>".esc($row->cod_afiliado)."</td>";
      echo "<td>".esc($row->Nombre_afiliado)."</td>";
      echo "<td></td>";
      if ($row->estado_afiliado == 1)
        echo "<td>Activo</td>";
      if ($row->estado_afiliado == 0)
        echo "<td>Inactivo</td>";
      echo "<td></td>";
      echo "</tr>";
  }
   ?>
</table>