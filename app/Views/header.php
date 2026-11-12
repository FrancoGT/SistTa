<?php
  if (service('request')->isAJAX()) {
    service('response')->setHeader('X-CSRF-Name', csrf_token());
    service('response')->setHeader('X-CSRF-Hash', csrf_hash());
  }
  $assets      = base_url('assets') . '/';
  $nombre_user = (string) session('user_nama');
  $iniciales   = '';
  foreach (array_slice(preg_split('/\s+/', trim($nombre_user)) ?: [], 0, 2) as $parte) {
    $iniciales .= mb_substr($parte, 0, 1);
  }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gesti&oacute;n de Flotilla</title>
  <link rel="icon" href="<?= base_url('favicon.ico') ?>">

  <!-- Bootstrap 3.3.7 + AdminLTE 2.4.5 (solo skin-blue) + iconos -->
  <link rel="stylesheet" href="<?= $assets ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= $assets ?>bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= $assets ?>bower_components/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="<?= $assets ?>dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="<?= $assets ?>dist/css/skins/skin-blue.min.css">
  <!-- DataTables (tablas dinámicas con buscador y paginación) -->
  <link rel="stylesheet" href="<?= $assets ?>bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <!-- Estilos globales del sistema -->
  <link rel="stylesheet" href="<?= $assets ?>css/sistta.css?v=<?= filemtime(FCPATH . 'assets/css/sistta.css') ?>">

  <!-- jQuery se carga aquí porque algunas vistas tienen scripts en línea -->
  <script src="<?= $assets ?>bower_components/jquery/dist/jquery.min.js"></script>
  <script>
    function isNumberKey(evt)
    {
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
      return true;
    }
    function ValidateAlpha(evt)
    {
      var keyCode = (evt.which) ? evt.which : evt.keyCode;
      if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32)
        return false;
      return true;
    }
    function AlphaNumeric(e)
    {
      // Teclas de control (Enter, Backspace, flechas, Ctrl+V...) no se filtran
      if (e.ctrlKey || e.metaKey || e.altKey) { return true; }
      if (e.key !== undefined && e.key.length !== 1) { return true; }
      var keyCode = e.charCode || e.keyCode || e.which;
      if (keyCode < 32) { return true; }
      var regex = /^[\w\s\.-]+$/;
      return regex.test(e.key !== undefined ? e.key : String.fromCharCode(keyCode));
    }
  </script>
</head>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <a href="<?= site_url('dashboard') ?>" class="logo">
      <span class="logo-mini"><i class="fa fa-taxi"></i></span>
      <span class="logo-lg"><i class="fa fa-taxi"></i><b>Gesti&oacute;n</b> de Flotilla</span>
    </a>

    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button" aria-label="Mostrar u ocultar el men&uacute;">
        <span class="sr-only">Mostrar u ocultar el men&uacute;</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle user-menu-link" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <span class="user-avatar" aria-hidden="true"><?= esc($iniciales !== '' ? $iniciales : '?') ?></span>
              <span class="user-name hidden-xs"><?= esc($nombre_user) ?></span>
              <i class="fa fa-angle-down" aria-hidden="true"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-right">
              <li class="dropdown-header visible-xs"><?= esc($nombre_user) ?></li>
              <li><a href="<?= site_url('logout') ?>"><i class="fa fa-sign-out"></i> Cerrar sesi&oacute;n</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
