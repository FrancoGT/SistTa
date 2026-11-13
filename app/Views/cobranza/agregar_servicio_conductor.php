<h1>Agregar Servicio</h1>
<form data-async-form action="<?= site_url('cobranza/encontrar_servicio_conductor') ?>" method="post">
  <?= csrf_field() ?>
  <input type="hidden" name="id_person_conductor" value="<?= esc($id_person_conductor, 'attr') ?>">
  <?php if ($servicios === []): ?>
    <p class="alert alert-warning">No hay servicios activos para este tipo de persona. Registra o activa un servicio en Configuraci&oacute;n.</p>
  <?php else: ?>
    <div class="form-group">
      <label for="data_servicio">Servicio</label>
      <select class="form-control" name="data_servicio" id="data_servicio" required>
        <option value="">Selecciona un servicio</option>
        <?php foreach ($servicios as $servicio): ?>
          <option value="<?= esc($servicio->nombre_servicio, 'attr') ?>"><?= esc($servicio->nombre_servicio) ?></option>
        <?php endforeach ?>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">Continuar</button>
  <?php endif ?>
</form>
