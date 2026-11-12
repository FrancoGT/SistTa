<?php
/** @var list<object> $usuarios  Usuario::listar_usuarios() */
$usuarios ??= $ci->listar_usuarios();
$tipos = ['1' => 'Administrador', '2' => 'Secretaria'];
?>
<h1>Usuarios</h1>
<h3>
  <?php if (isset($mensaje)) { echo ' ' . $mensaje . ' '; } ?>
  <a data-form-modal class="btn btn-info" href="<?= site_url('usuario/registrar_usuario') ?>"><i class="ion ion-person-add"></i> Nuevo Usuario</a>
</h3>
<?php if (empty($usuarios)): ?>
  <p class="alert alert-danger">No hay usuarios registrados</p>
<?php else: ?>
  <table class="table table-bordered table-hover js-datatable" data-dt-buscar="Buscar por usuario, nombre o e-mail...">
    <thead>
      <tr>
        <th>Usuario</th>
        <th>Nombre</th>
        <th>E-mail</th>
        <th data-dt-filtro>Tipo</th>
        <th data-dt-filtro>Estado</th>
        <th class="dt-acciones">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($usuarios as $u): ?>
      <tr>
        <td><?= esc($u->username) ?></td>
        <td><?= esc($u->nama_user) ?></td>
        <td><?= esc($u->email) ?></td>
        <td><?= $tipos[(string) $u->tipo] ?? '' ?></td>
        <td><?= $u->estado_usuario == 1 ? 'Activo' : 'Inactivo' ?></td>
        <td class="dt-acciones">
          <a data-form-modal class="btn btn-info" href="<?= site_url('usuario/actualizar_usuario/' . esc($u->id_user, 'url')) ?>" title="Actualizar Usuario"><i class="fa fa-refresh"></i> <i class="fa fa-male"></i></a>
        </td>
      </tr>
      <?php endforeach ?>
    </tbody>
  </table>
<?php endif ?>
