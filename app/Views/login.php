<?php $assets = base_url('assets') . '/'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ingresar - Gesti&oacute;n de Flotilla</title>
  <link rel="icon" href="<?= base_url('favicon.ico') ?>">
  <link rel="stylesheet" href="<?= $assets ?>bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= $assets ?>bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= $assets ?>css/sistta.css">
</head>
<body>
<main class="login-page-sistta">
  <div class="login-card">
    <div class="login-brand">
      <span class="login-brand__icon" aria-hidden="true"><i class="fa fa-taxi"></i></span>
      <h1>Gesti&oacute;n de Flotilla</h1>
      <p>Ingrese sus credenciales para continuar</p>
    </div>

    <?php if (isset($error)) { echo $error; } ?>

    <form method="POST" action="<?= site_url('login') ?>">
      <?= csrf_field() ?>
      <div class="form-group">
        <label for="username">Usuario</label>
        <input autocomplete="username" type="text" class="form-control" id="username" name="username" placeholder="Ingrese usuario" autofocus>
        <?php if (isset($validation) && $validation->hasError('username')) { echo '<span class="text-danger">' . $validation->getError('username') . '</span>'; } ?>
      </div>
      <div class="form-group">
        <label for="password">Contrase&ntilde;a</label>
        <input autocomplete="current-password" type="password" class="form-control" id="password" name="password" placeholder="Ingrese contrase&ntilde;a">
        <?php if (isset($validation) && $validation->hasError('password')) { echo '<span class="text-danger">' . $validation->getError('password') . '</span>'; } ?>
      </div>
      <button class="btn btn-lg btn-primary btn-block" name="btn-login" id="btn-login" type="submit">Ingresar</button>
    </form>
  </div>
</main>
</body>
</html>
