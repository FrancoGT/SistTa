<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Conexión a la base de datos (equivalente a $this->db de CodeIgniter 3).
     * Es pública porque las vistas la usan a través de $ci.
     */
    public BaseConnection $db;

    /**
     * Helpers que en CodeIgniter 3 se cargaban en config/autoload.php.
     *
     * @var list<string>
     */
    protected $helpers = ['url', 'form', 'date', 'sql'];

    /**
     * @param CLIRequest|IncomingRequest $request
     *
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        $this->db = db_connect();

        // Las vistas heredadas de CI3 llamaban a get_instance() para usar métodos
        // del controlador; aquí se les entrega el controlador actual como $ci.
        service('renderer')->setVar('ci', $this);
    }

    /*
     * Datos de las fichas de detalle (afiliado, conductor, vehículo).
     * Antes cada controlador tenía su propia copia de mostrar_*() que imprimía
     * la tabla con echo; ahora solo se devuelven los datos y el HTML vive en
     * app/Views/tablas/ficha_*.php.
     */

    /** @return list<object> */
    public function ficha_afiliado($id_person_afiliado): array
    {
        return $this->db->query('SELECT a.cod_afiliado, p.nombre_persona, p.documento FROM tbl_afiliado as a LEFT JOIN tbl_persona as p ON a.id_person_afiliado=p.id_persona WHERE a.id_person_afiliado=?', [$id_person_afiliado])->getResult();
    }

    /** @return list<object> */
    public function ficha_conductor($id_person_conductor): array
    {
        return $this->db->query('SELECT c.cod_conductor, c.brevete, p.nombre_persona, p.documento FROM tbl_conductor as c LEFT JOIN tbl_persona as p ON c.id_person_conductor=p.id_persona WHERE c.id_person_conductor=?', [$id_person_conductor])->getResult();
    }

    /** @return list<object> */
    public function ficha_vehiculo($id_vehiculo): array
    {
        return $this->db->query('SELECT cod_vehiculo, placa FROM tbl_vehiculo WHERE id_vehiculo=?', [$id_vehiculo])->getResult();
    }
}
