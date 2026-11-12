<?php
  $contar = static function ($ci, string $sql): string {
    $query = $ci->db->query($sql);
    $count = json_encode($query->getResult());
    return preg_replace("/[^0-9]/", "", $count);
  };

  $indicadores = [
    ['Afiliados',           $contar($ci, 'SELECT COUNT(*) FROM tbl_afiliado'),                                 'fa-users',      ''],
    ['Unidades',            $contar($ci, 'SELECT COUNT(*) FROM tbl_vehiculo'),                                 'fa-taxi',       'stat-card--orange'],
    ['Conductores',         $contar($ci, 'SELECT COUNT(*) FROM tbl_conductor'),                                'fa-id-card-o',  ''],
    ['Unidades activas',    $contar($ci, 'SELECT COUNT(*) FROM tbl_vehiculo WHERE estado_vehiculo = 1'),       'fa-check-circle', 'stat-card--green'],
    ['Conductores activos', $contar($ci, 'SELECT COUNT(*) FROM tbl_conductor WHERE estado_conductor = 1'),     'fa-user-circle',  'stat-card--green'],
  ];
?>
<h1 class="page-title">Panel de control</h1>
<div class="stat-grid">
  <?php foreach ($indicadores as [$titulo, $valor, $icono, $variante]): ?>
  <div class="stat-card <?= $variante ?>">
    <span class="stat-card__icon" aria-hidden="true"><i class="fa <?= $icono ?>"></i></span>
    <div class="stat-card__body">
      <span class="stat-card__value"><?= esc($valor) ?></span>
      <span class="stat-card__label"><?= $titulo ?></span>
    </div>
  </div>
  <?php endforeach; ?>
</div>
