<?php

namespace App\Libraries;

use CodeIgniter\Debug\Exceptions;

/**
 * Compatibilidad con el código heredado de CodeIgniter 3.
 *
 * En PHP 7 los accesos a índices/variables/propiedades inexistentes eran E_NOTICE y CI3
 * los ignoraba en producción. En PHP 8 son E_WARNING y CodeIgniter 4 los convierte en
 * excepciones, lo que rompería flujos que antes funcionaban (p. ej. un checkbox sin marcar).
 * Aquí solo esos avisos se registran en writable/logs en lugar de interrumpir la petición;
 * cualquier otro error se maneja igual que en CodeIgniter 4.
 */
class LegacyExceptions extends Exceptions
{
    private const PATRONES = '/^(Undefined (array key|variable|property|offset|index)|Trying to access array offset on'
        . '|Attempt to read property)/';

    public function errorHandler(int $severity, string $message, ?string $file = null, ?int $line = null)
    {
        if (($severity & (E_WARNING | E_NOTICE)) !== 0 && preg_match(self::PATRONES, $message) === 1) {
            log_message('warning', '[legacy] {message} en {archivo}:{linea}', [
                'message' => $message,
                'archivo' => clean_path((string) $file),
                'linea'   => (string) $line,
            ]);

            return true;
        }

        return parent::errorHandler($severity, $message, $file, $line);
    }
}
