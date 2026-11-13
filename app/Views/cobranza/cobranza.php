<h1>Cobranzas</h1>
<p>Busca por documento, c&oacute;digo o placa para consultar y registrar deudas.</p>
<?php
$busquedas = [
    'afiliado' => ['Afiliado', ['documento' => 'Documento', 'cod_afiliado' => 'C&oacute;digo']],
    'conductor' => ['Conductor', ['documento' => 'Documento', 'cod_conductor' => 'C&oacute;digo']],
    'vehiculo' => ['Veh&iacute;culo', ['placa' => 'Placa', 'cod_vehiculo' => 'C&oacute;digo']],
];
?>
<?php foreach ($busquedas as $tipo => [$nombre, $criterios]): ?>
<form data-async-form action="<?= site_url('cobranza/buscar_cobranza_detalle_' . $tipo) ?>" method="post">
  <?= csrf_field() ?>
  <h2><?= $nombre ?></h2>
  <div class="row">
    <div class="form-group col-sm-4">
      <label for="criterio_<?= $tipo ?>">Buscar por</label>
      <select class="form-control" id="criterio_<?= $tipo ?>" name="criterio_busqueda">
        <?php foreach ($criterios as $criterio => $etiqueta): ?>
          <option value="<?= esc($criterio, 'attr') ?>"><?= $etiqueta ?></option>
        <?php endforeach ?>
      </select>
    </div>
    <div class="form-group col-sm-5">
      <label for="valor_<?= $tipo ?>">Documento, c&oacute;digo o placa</label>
      <input class="form-control" id="valor_<?= $tipo ?>" name="valor_busqueda" type="text" autocomplete="off" required>
    </div>
    <div class="form-group col-sm-3">
      <label>Consultar</label><br>
      <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Buscar <?= $nombre ?></button>
    </div>
  </div>
</form>
<?php endforeach ?>
