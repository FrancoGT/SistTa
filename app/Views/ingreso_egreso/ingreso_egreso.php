<?php
/** @var list<object> $operaciones  Ingreso_Egreso::listar_ingresos_egresos() */
$tipos = ['1' => 'Ingreso', '2' => 'Egreso'];
?>
<h1>Ingresos - Egresos</h1>
<br>
<h3>
  <a data-form-modal class="btn btn-success" href="<?= site_url('ingreso_egreso/registrar_egreso') ?>"><i class="fa fa-money"></i> <i class="fa fa-plus"></i> Nuevo Gasto</a>
  <a data-form-modal class="btn btn-success" href="<?= site_url('ingreso_egreso/registrar_ingreso') ?>"><i class="fa fa-money"></i> <i class="fa fa-plus"></i> Nuevo Ingreso</a>
</h3>
<br>
<?php if (empty($operaciones)): ?>
  <p class="alert alert-danger">No hay registros de ingresos o egresos a&uacute;n</p>
<?php else: ?>
  <table class="table table-bordered table-hover js-datatable" data-dt-buscar="Buscar por fecha, nombre, monto o usuario...">
    <thead>
      <tr>
        <th>Fecha</th>
        <th class="text-right">Monto</th>
        <th>Nombre/Raz&oacute;n Social</th>
        <th data-dt-filtro>Tipo</th>
        <th data-dt-filtro>Usuario</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($operaciones as $o): ?>
      <tr>
        <td><?= esc($o->fecha_dma) ?></td>
        <td class="text-right"><?= esc($o->monto) ?></td>
        <td><?= esc($o->descripcion) ?></td>
        <td><?= $tipos[(string) $o->tipo_operacion] ?? '' ?></td>
        <td><?= esc($o->nama_user) ?></td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
<?php endif ?>
