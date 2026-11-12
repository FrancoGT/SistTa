<?php
/**
 * Servicios de cobranza y sus periodos (una tabla por servicio).
 *
 * @var array{agregar_url: string, servicios: list<array>} $bloque  Cobranza::servicios_cobranza()
 * @var string $titulo  Encabezado cuando hay servicios (opcional)
 * @var string $vacio   Mensaje cuando no hay servicios
 */
$titulo ??= '';
?>
<div class="cobranza-servicios">
  <?php if ($bloque['servicios'] !== [] && $titulo !== ''): ?>
    <h3><?= $titulo ?></h3>
  <?php endif ?>
  <p>
    <a class="btn btn-info" href="<?= esc($bloque['agregar_url'], 'attr') ?>" title="Agregar Servicio"><i class="fa fa-th-list"></i> <i class="fa fa-plus"></i> Agregar Servicio</a>
  </p>

  <?php if ($bloque['servicios'] === []): ?>
    <p class="alert alert-danger"><?= $vacio ?></p>
  <?php endif ?>

  <?php foreach ($bloque['servicios'] as $servicio): ?>
    <h4>Servicio: <?= esc($servicio['nombre']) ?></h4>
    <table class="table table-bordered table-hover js-datatable" data-dt-buscar="Buscar periodo por fecha o estado...">
      <thead>
        <tr>
          <th>Inicio</th>
          <th>Fin</th>
          <th data-dt-filtro>Estado</th>
          <th class="text-right">D&iacute;as</th>
          <th class="text-right">Pago Diario</th>
          <th class="text-right">Monto Total</th>
          <th class="text-right">Deuda Pendiente</th>
          <th class="dt-acciones">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($servicio['periodos'] as $p): ?>
        <tr>
          <td><?= esc($p['inicio']) ?></td>
          <td><?= esc($p['fin']) ?></td>
          <td><?= esc($p['estado']) ?></td>
          <td class="text-right"><?= esc($p['dias']) ?></td>
          <td class="text-right"><?= esc($p['pago_diario']) ?></td>
          <td class="text-right"><?= esc($p['monto']) ?></td>
          <td class="text-right"><?= esc($p['saldo']) ?></td>
          <td class="dt-acciones">
            <?php if ($p['cancelado']): ?>
              <span class="label label-success">Cancelado</span>
            <?php endif ?>
            <?php foreach ($p['acciones'] as $accion): ?>
              <a class="btn <?= $accion['clase'] ?>" href="<?= esc($accion['url'], 'attr') ?>" title="<?= esc($accion['texto'], 'attr') ?>">
                <?php if (isset($accion['icono'])): ?><i class="fa fa-plus"></i> <i class="fa <?= $accion['icono'] ?>"></i><?php endif ?>
                <?= esc($accion['texto']) ?>
              </a>
            <?php endforeach ?>
          </td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  <?php endforeach ?>
</div>
