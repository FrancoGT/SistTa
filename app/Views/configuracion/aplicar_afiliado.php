<h1>Aplicar a Afiliado el Servicio</h1>
<br>
<form action="<?= base_url() ?>index.php/configuracion/encontrar_afiliado" method="POST">
<?= csrf_field() ?>
<h3> <?php echo '&nbsp;&nbsp;Nombre del Servicio: '.esc($nombre_serv); ?> </h3>
<br>
<p> </p>
  <div class="form-group">
  <h3>
      <label>Ingrese dni o c&oacute;digo del Afiliado</label><br>
     </h3>
      <input type="text" onkeypress="return AlphaNumeric(event)" autocomplete="off" id="bus_afi" name="bus_afi">
    </div>
<p>
  <div class="form-group">
      <input type="hidden" name="id_servicio" id="id_servicio" value="<?= esc($id_servicio) ?>">
    </div>
</p>
<p>
 &nbsp;&nbsp;
  <input type="submit" name="data_con" id="data_con" value="Buscar Afiliado" />
</p>
</form>
