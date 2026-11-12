<h1>Configuraci&oacute;n</h1>
<br>
<form data-async-form action="<?= base_url() ?>index.php/configuracion/actualizar_configuracion" method ="POST">
<?= csrf_field() ?>
<?php
  if (!isset($empresa) ||
      !isset($RUC) ||
      !isset($direccion_empresa) ||
      !isset($cod_configuracion))
  {
    $datos = $ci->recuperarInfoConfiguracion();
    $empresa = $datos['empresa'];
    $RUC = $datos['RUC'];
    $direccion_empresa = $datos['direccion_empresa'];
    $cod_configuracion = $datos['cod_configuracion'];
  }
?>
<p>Empresa
  <a for="empresa"></a>
  <input type="text" onkeypress="return AlphaNumeric(event)" name="empresa" id="empresa" value ="<?= esc($empresa) ?>" />
</p>
<p>RUC
  <a for="textfield"></a>
  <input type="text" onkeypress="return isNumberKey(event)" name="ruc" id="ruc" value ="<?= esc($RUC) ?>" />
</p>
<p>Direcci&oacute;n
  <a for="direccion"></a>
  <input type="text" onkeypress="return AlphaNumeric(event)" name="direccion" id="direccion" value ="<?= esc($direccion_empresa) ?>" />
</p>
<p>C&oacute;digo
  <a for="codigo"></a>
  <input type="text" onkeypress="return AlphaNumeric(event)" name="codigo" id="codigo" value ="<?= esc($cod_configuracion) ?>" />
</p>
<br>
<input type="submit" name="guardar" id="guardar" value="Guardar" />
<br> </br>
</form>
<h1>Servicios</h1>
<br>
<a data-form-modal class="btn btn-info" href="<?= base_url() ?>index.php/configuracion/registrar_servicio" ><i class="fa fa-th-list"></i> <i class="fa fa-plus"></i> Agregar Servicio</a>
<br>
<br>
<?php
  /** @var list<object> $servicios  Configuracion::listar_servicios() */
  $servicios ??= $ci->listar_servicios();
  // aplica: 1 = Afiliado, 2 = Conductor, 3 = Unidad (vehículo)
  $aplicaciones = [
    '1' => ['Afiliado',  'configuracion/aplicar_afiliado/'],
    '2' => ['Conductor', 'configuracion/aplicar_conductor/'],
    '3' => ['Unidad',    'configuracion/aplicar_unidad/'],
  ];
?>
<?php if (empty($servicios)): ?>
  <p class="alert alert-danger">No hay registros de servicios a&uacute;n</p>
<?php else: ?>
  <table class="table table-bordered table-hover js-datatable" data-dt-buscar="Buscar servicio...">
    <thead>
      <tr>
        <th>Nombre</th>
        <th class="text-right">Pago Diario</th>
        <th data-dt-filtro>Estado</th>
        <th data-dt-filtro>Aplica a</th>
        <th class="dt-acciones">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($servicios as $s): ?>
        <?php [$aplica_txt, $aplica_ruta] = $aplicaciones[(string) $s->aplica] ?? ['', null]; ?>
      <tr>
        <td><?= esc($s->nombre_servicio) ?></td>
        <td class="text-right"><?= esc($s->pago_diario) ?></td>
        <td><?= $s->estado_servicio == 1 ? 'Activo' : 'Inactivo' ?></td>
        <td data-search="<?= esc($aplica_txt) ?>" data-order="<?= esc($aplica_txt) ?>">
          <?php if ($aplica_ruta !== null): ?>
            <a data-form-modal class="btn btn-info" href="<?= site_url($aplica_ruta . esc($s->id_servicio, 'url')) ?>" title="Aplicar a <?= esc($aplica_txt) ?>"><?= esc($aplica_txt) ?></a>
          <?php endif ?>
        </td>
        <td class="dt-acciones">
          <a data-form-modal class="btn btn-info" href="<?= site_url('configuracion/actualizar_servicio/' . esc($s->id_servicio, 'url')) ?>" title="Actualizar Servicio"><i class="fa fa-refresh"></i> <i class="fa fa-th-list"></i></a>
        </td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
<?php endif ?>
