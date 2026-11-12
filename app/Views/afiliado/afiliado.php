<?php /** @var list<object> $afiliados  Afiliado::listar_afiliados() */ ?>
<h1>Afiliados</h1>
<h3>
  <a class="btn btn-info" href="<?= site_url('afiliado/registrar_afiliado') ?>"><i class="ion ion-person-add"></i> Nuevo Afiliado</a>
</h3>
<br>
<?php if (empty($afiliados)): ?>
  <p class="alert alert-danger">No hay registros de afiliados a&uacute;n</p>
<?php else: ?>
  <table class="table table-bordered table-hover js-datatable" data-dt-buscar="Buscar por c&oacute;digo, nombre o documento...">
    <thead>
      <tr>
        <th>C&oacute;digo</th>
        <th>Nombres y Apellidos</th>
        <th>Documento</th>
        <th>Veh&iacute;culos</th>
        <th data-dt-filtro>Estado</th>
        <th class="dt-acciones">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($afiliados as $a): ?>
      <tr>
        <td><?= esc($a->cod_afiliado) ?></td>
        <td><?= esc($a->nombre_persona) ?></td>
        <td><?= esc($a->documento) ?></td>
        <td><?= esc($a->vehiculos) ?></td>
        <td><?= $a->estado_afiliado == 1 ? 'Activo' : 'Inactivo' ?></td>
        <td class="dt-acciones">
          <a class="btn btn-info" href="<?= site_url('afiliado/actualizar_afiliado/' . esc($a->id_afiliado, 'url')) ?>" title="Actualizar Afiliado"><i class="fa fa-refresh"></i> <i class="fa fa-male"></i></a>
          <a class="btn btn-primary" href="<?= site_url('afiliado/actualizar_vehiculo/' . esc($a->id_afiliado, 'url')) ?>" title="Actualizar Veh&iacute;culo"><i class="fa fa-refresh"></i> <i class="fa fa-car"></i></a>
          <a class="btn btn-warning" href="<?= site_url('afiliado/registrar_vehiculo_afiliado/' . esc($a->id_person_afiliado, 'url')) ?>" title="Agregar Veh&iacute;culo"><i class="fa fa-plus"></i> <i class="fa fa-car"></i></a>
        </td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
<?php endif ?>
