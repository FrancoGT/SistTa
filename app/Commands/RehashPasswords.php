<?php

namespace App\Commands;

use App\Models\Admin;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Protege las contraseñas MD5 heredadas de CI3 que siguen guardadas en tbl_users.
 *
 * No se puede recuperar la clave original, así que el MD5 se "envuelve":
 * se guarda '$md5wrap$' + password_hash(md5). En el siguiente ingreso del
 * usuario, Admin::check_login() lo reemplaza por un hash Argon2id normal.
 */
class RehashPasswords extends BaseCommand
{
    protected $group       = 'SistTa';
    protected $name        = 'usuarios:rehash';
    protected $description = 'Amplía tbl_users.password a VARCHAR(255) y envuelve los hashes MD5 heredados con Argon2id/bcrypt.';
    protected $usage       = 'usuarios:rehash';

    public function run(array $params)
    {
        $db = db_connect();

        $columna = $db->query(
            "SELECT CHARACTER_MAXIMUM_LENGTH AS largo FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_users' AND COLUMN_NAME = 'password'"
        )->getRow();

        if ($columna !== null && (int) $columna->largo < 255) {
            $db->query('ALTER TABLE `tbl_users` MODIFY `password` VARCHAR(255) NOT NULL');
            CLI::write('Columna tbl_users.password ampliada a VARCHAR(255).', 'yellow');
        }

        $usuarios     = $db->table('tbl_users')->select('id_user, username, password')->get()->getResult();
        $actualizados = 0;

        foreach ($usuarios as $usuario) {
            $hash = (string) $usuario->password;

            if (preg_match('/^[a-f0-9]{32}$/i', $hash) !== 1) {
                continue; // ya es un hash moderno o envuelto
            }

            $db->table('tbl_users')
                ->where('id_user', $usuario->id_user)
                ->update(['password' => Admin::PREFIJO_MD5 . Admin::hash_password(strtolower($hash))]);

            CLI::write('  ' . $usuario->username . ': MD5 envuelto');
            $actualizados++;
        }

        [$algo] = Admin::algoritmo();
        CLI::write(sprintf('Listo. %d de %d usuario(s) actualizados (algoritmo: %s).', $actualizados, count($usuarios), $algo), 'green');
    }
}
