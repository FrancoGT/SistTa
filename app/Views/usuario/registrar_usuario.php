<form action="<?= base_url() ?>index.php/usuario/guardar_usuario" method ="POST">
<?= csrf_field() ?>
    <h1>Registrar Nuevo Usuario</h1>
    <br>
  <?php
    if (!isset($username))
    {
      $username = '';
    }
    if (!isset($nama_user))
    {
      $nama_user = '';
    }
    if (!isset($password))
    {
      $password = '';
    }
    if (!isset($email))
    {
      $email = '';
    }
  ?>
  <div class="form-group">
      <p>Nick de usuario</p><br>
      <input type="text" autocomplete="off" required id="username" name="username" placeholder="Nick de usuario" value = "<?= esc($username) ?>">
      <?php
        if (isset($error_username))
        {
          echo $error_username;
        }
      ?>
  </div>

  <div class="form-group">
    <p>Nombre</p>
      <br>
      <input type="text" autocomplete="off" required  id="nama_user" name="nama_user" placeholder="Nombre de usuario" value = "<?= esc($nama_user) ?>">
      <?php
        if (isset($error_nama_user))
        {
            echo $error_nama_user;
        }
      ?>
  </div>

    <div class="form-group">
      <p>Contraseña</p><br>
      <input type="password" autocomplete="new-password" required  id="password" name="password" placeholder="Contraseña de usuario" value = "<?= esc($password) ?>">
    </div>
    <div class="form-group">
  </div>
    <div class="form-group">
    <p>Email</p>
    <br>
    <input type="email" autocomplete="off" required  id="email" name="email" placeholder="Correo electr&oacute;nico" value = "<?= esc($email) ?>">
    </div>
    <?php
        if (isset($error_email))
        {
          echo $error_email;
        }
    ?>
    <p>
    <fieldset>
    <p>Estado</p>
    <p>
    <input type="radio"  required  name="estado" value="1" id="estado" /> Activo</p>
        <p>
          <input type="radio"  required name="estado" value="0" id="estado" />
          Inactivo</p>
    </fieldset>
    </p>
   <br>
   <br>
    <div>
    <p>
    <fieldset>
    <p>Tipo de Usuario</p>
    <p>
    <input type="radio"  required  name="tipo" value="1" id="tipo" /> Administrador
    </p>
        <p>
          <input type="radio"  required  name="tipo" value="2" id="tipo" />
          Secretaria</p>
    </fieldset>
    </p>
   </div>
   <input type="submit" name="guardar_usuario" id="guardar_usuario" value="Guardar usuario">
</form>
