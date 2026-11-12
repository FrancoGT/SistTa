<?php
  // Nombre de la empresa (encabezado del menú)
  $nombre_empresa = '';
  $query = $ci->db->query('SELECT empresa FROM tbl_configuracion');
  foreach ($query->getResult() as $row)
  {
    $nombre_empresa = $row->empresa;
  }

  // Tipo de usuario: 1 = Administrador (ve el menú Configuración)
  $tipo = "1";
  $username = session('user_nama');
  $query = $ci->db->query('SELECT tipo FROM `tbl_users` WHERE nama_user=?', [$username]);
  foreach ($query->getResult() as $row)
  {
    $tipo = $row->tipo;
  }

  // Módulo activo = primer segmento de la URL
  $modulo = strtolower(explode('/', trim(uri_string(), '/'))[0] ?? '');
  if ($modulo === 'index.php')
  {
    $modulo = strtolower(explode('/', trim(uri_string(), '/'))[1] ?? '');
  }

  $menu = [
    ['titulo' => 'Gesti&oacute;n', 'icono' => 'fa-car', 'items' => [
      ['afiliado',       'Afiliados',         'fa-users'],
      ['conductor',      'Conductores',       'fa-id-card-o'],
      ['vehiculo',       'Veh&iacute;culos',  'fa-taxi'],
    ]],
    ['titulo' => 'Finanzas', 'icono' => 'fa-money', 'items' => [
      ['cobranza',       'Cobranza',          'fa-credit-card'],
      ['ingreso_egreso', 'Ingresos y egresos','fa-exchange'],
      ['reporte',        'Reportes',          'fa-bar-chart'],
    ]],
  ];
  if ($tipo == "1")
  {
    $menu[] = ['titulo' => 'Configuraci&oacute;n', 'icono' => 'fa-cogs', 'items' => [
      ['usuario',        'Usuarios',               'fa-user'],
      ['configuracion',  'Configuraci&oacute;n general', 'fa-sliders'],
    ]];
  }
?>
  <aside class="main-sidebar">
    <section class="sidebar">
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header" title="<?= esc($nombre_empresa, 'attr') ?>"><?= esc($nombre_empresa) ?></li>

        <li class="<?= ($modulo === 'dashboard' || $modulo === '') ? 'active' : '' ?>">
          <a href="<?= site_url('dashboard') ?>">
            <i class="fa fa-dashboard"></i> <span>Panel de control</span>
          </a>
        </li>

        <?php foreach ($menu as $grupo):
          $abierto = in_array($modulo, array_column($grupo['items'], 0), true); ?>
        <li class="treeview<?= $abierto ? ' active menu-open' : '' ?>">
          <a href="#">
            <i class="fa <?= $grupo['icono'] ?>"></i> <span><?= $grupo['titulo'] ?></span>
            <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
          </a>
          <ul class="treeview-menu">
            <?php foreach ($grupo['items'] as [$ruta, $texto, $icono]): ?>
            <li class="<?= $modulo === $ruta ? 'active' : '' ?>">
              <a href="<?= site_url($ruta . '/index') ?>"><i class="fa <?= $icono ?>"></i> <?= $texto ?></a>
            </li>
            <?php endforeach; ?>
          </ul>
        </li>
        <?php endforeach; ?>
      </ul>
    </section>
  </aside>

  <!-- Contenido: se cierra en footer.php -->
  <div class="content-wrapper">
    <section class="content">
      <div class="page-card">
