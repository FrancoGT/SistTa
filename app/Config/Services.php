<?php

namespace Config;

use App\Libraries\LegacyExceptions;
use CodeIgniter\Config\BaseService;
use Config\Exceptions as ExceptionsConfig;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    /**
     * Manejador de errores con compatibilidad para el código heredado de CI3.
     *
     * @return LegacyExceptions
     */
    public static function exceptions(?ExceptionsConfig $config = null, bool $getShared = true)
    {
        if ($getShared) {
            return static::getSharedInstance('exceptions', $config);
        }

        $config ??= config(ExceptionsConfig::class);

        return new LegacyExceptions($config);
    }

    /*
     * public static function example($getShared = true)
     * {
     *     if ($getShared) {
     *         return static::getSharedInstance('example');
     *     }
     *
     *     return new \CodeIgniter\Example();
     * }
     */
}
