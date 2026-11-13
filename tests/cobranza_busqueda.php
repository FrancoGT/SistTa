<?php

// Ejecutar desde la raiz: php tests/cobranza_busqueda.php (solo lectura).
define('FCPATH', dirname(__DIR__) . '/public/');
define('ENVIRONMENT', getenv('CI_ENVIRONMENT') ?: 'production');
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
CodeIgniter\Boot::bootConsole($paths);
helper('sql');

$controller = new App\Controllers\Cobranza();
$controller->db = db_connect();
$casos = [
    ['afiliado', 'id_person_afiliado', 'SELECT a.id_person_afiliado, a.cod_afiliado, p.documento FROM tbl_afiliado a JOIN tbl_persona p ON p.id_persona=a.id_person_afiliado', ['cod_afiliado', 'documento']],
    ['conductor', 'id_person_conductor', 'SELECT c.id_person_conductor, c.cod_conductor, p.documento FROM tbl_conductor c JOIN tbl_persona p ON p.id_persona=c.id_person_conductor', ['cod_conductor', 'documento']],
    ['vehiculo', 'id_vehiculo', 'SELECT id_vehiculo, cod_vehiculo, placa FROM tbl_vehiculo', ['cod_vehiculo', 'placa']],
];
$checks = 0;
foreach ($casos as [$tipo, $id, $sql, $criterios]) {
    $method = 'buscar_' . $tipo;
    foreach ($controller->db->query($sql)->getResultArray() as $row) {
        foreach ($criterios as $criterio) {
            if (trim((string) $row[$criterio]) === '') {
                continue;
            }
            $result = $controller->$method('  ' . $row[$criterio] . '  ~' . $criterio);
            if ((string) $result[$id] !== (string) $row[$id]) {
                throw new RuntimeException("Fallo buscando $tipo por $criterio");
            }
            $checks++;
        }
    }
    foreach (['', 'inexistente~criterio_invalido', '__sin_registro_cobranza__~' . $criterios[0]] as $input) {
        if ($controller->$method($input)[$id] !== '') {
            throw new RuntimeException("Falso positivo buscando $tipo");
        }
        $checks++;
    }
}
echo "OK: $checks comprobaciones de busqueda; sin modificar datos.\n";
