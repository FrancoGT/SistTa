<?php

if (! function_exists('sql_ident')) {
    /**
     * Valida un nombre de columna/tabla/alias que se interpola en una consulta SQL.
     * Los valores siempre van con query bindings (?); esta función cubre solo los
     * identificadores dinámicos (p. ej. $criterio = 'documento'), que no pueden enlazarse.
     *
     * @throws InvalidArgumentException si el identificador no es válido
     */
    function sql_ident(string $name): string
    {
        if (preg_match('/^[A-Za-z_][A-Za-z0-9_]{0,63}$/', $name) !== 1) {
            throw new InvalidArgumentException('Identificador SQL no válido.');
        }

        return $name;
    }
}
