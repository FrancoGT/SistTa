<?php
$estadoActual = $estado ?? $estado_usuario ?? '';
$tipoActual = $tipo ?? '';
?>
<h2>Nuevo usuario</h2>
<form data-async-form class="sistta-form-layout" action="<?= site_url('usuario/guardar_usuario') ?>" method="post">
<?= csrf_field() ?>

<div class="sistta-form-grid">
  <div class="form-group">
    <label for="username">Usuario</label>
    <input class="form-control" type="text" id="username" name="username" required autocomplete="off" value="<?= esc($username ?? '') ?>">
    <?php if (!empty($error_username)): ?><div class="sistta-field-error" role="alert"><?= $error_username ?></div><?php endif ?>
  </div>
  <div class="form-group">
    <label for="nama_user">Nombre completo</label>
    <input class="form-control" type="text" id="nama_user" name="nama_user" required value="<?= esc($nama_user ?? '') ?>">
    <?php if (!empty($error_nama_user)): ?><div class="sistta-field-error" role="alert"><?= $error_nama_user ?></div><?php endif ?>
  </div>
  <div class="form-group">
    <label for="email">Correo electr&oacute;nico</label>
    <input class="form-control" type="email" id="email" name="email" required value="<?= esc($email ?? '') ?>">
    <?php if (!empty($error_email)): ?><div class="sistta-field-error" role="alert"><?= $error_email ?></div><?php endif ?>
  </div>
  <div class="form-group">
    <label for="password">Contrase&ntilde;a</label>
    <input class="form-control" type="password" id="password" name="password" required autocomplete="new-password">
    
  </div>
  <fieldset class="form-group">
    <legend>Estado</legend>
    <div class="sistta-options">
      <label><input type="radio" name="estado" value="1" required <?= (string) $estadoActual === '1' ? 'checked' : '' ?>> Activo</label>
      <label><input type="radio" name="estado" value="0" required <?= (string) $estadoActual === '0' ? 'checked' : '' ?>> Inactivo</label>
    </div>
  </fieldset>
  <fieldset class="form-group">
    <legend>Tipo de usuario</legend>
    <div class="sistta-options">
      <label><input type="radio" name="tipo" value="1" required <?= (string) $tipoActual === '1' ? 'checked' : '' ?>> Administrador</label>
      <label><input type="radio" name="tipo" value="2" required <?= (string) $tipoActual === '2' ? 'checked' : '' ?>> Secretaria</label>
    </div>
  </fieldset>
</div>
<div class="sistta-form-actions"><button class="btn btn-primary" type="submit" name="guardar_usuario" value="1">Guardar usuario</button></div>
</form>
