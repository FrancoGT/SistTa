<?php

namespace App\Models;

use CodeIgniter\Model;

class Admin extends Model
{
    /**
     * Prefijo de los hashes MD5 heredados de CI3 que fueron "envueltos":
     * se guarda password_hash(md5(clave)) para que el MD5 no quede expuesto en la BD.
     */
    public const PREFIJO_MD5 = '$md5wrap$';

    // función de verificación de sesión
    public function logged_id()
    {
        return session('user_id');
    }

    /**
     * Algoritmo de hash: Argon2id si PHP lo soporta, si no bcrypt con costo 12.
     *
     * @return array{0: string|int|null, 1: array<string, int>}
     */
    public static function algoritmo(): array
    {
        if (defined('PASSWORD_ARGON2ID')) {
            return [PASSWORD_ARGON2ID, ['memory_cost' => 65536, 'time_cost' => 4, 'threads' => 1]];
        }

        return [PASSWORD_BCRYPT, ['cost' => 12]];
    }

    public static function hash_password(string $password): string
    {
        [$algo, $opciones] = self::algoritmo();

        return password_hash($password, $algo, $opciones);
    }

    public static function necesita_rehash(string $hash): bool
    {
        [$algo, $opciones] = self::algoritmo();

        return password_needs_rehash($hash, $algo, $opciones);
    }

    /**
     * Comprobar la función de inicio de sesión.
     *
     * Formatos aceptados en tbl_users.password:
     *  - hash moderno (Argon2id / bcrypt): se verifica y, si el algoritmo cambió, se rehashea.
     *  - '$md5wrap$' + password_hash(md5): MD5 heredado envuelto por `php spark usuarios:rehash`.
     *  - MD5 plano de CodeIgniter 3.
     * Los dos últimos se convierten a hash moderno en el primer ingreso correcto.
     *
     * @return list<object>|false
     */
    public function check_login(string $username, string $password)
    {
        $user = $this->db->table('tbl_users')
            ->where('username', $username)
            ->where('estado_usuario', '1')
            ->limit(1)
            ->get()
            ->getRow();

        if ($user === null) {
            // mismo costo de tiempo que un usuario existente (evita enumerar usuarios)
            password_verify($password, self::hash_password('sistta'));

            return false;
        }

        $hash = (string) $user->password;

        if (str_starts_with($hash, self::PREFIJO_MD5)) {
            if (! password_verify(md5($password), substr($hash, strlen(self::PREFIJO_MD5)))) {
                return false;
            }
            $this->actualizar_password((int) $user->id_user, $password);
        } elseif (password_get_info($hash)['algo'] !== null) {
            if (! password_verify($password, $hash)) {
                return false;
            }
            if (self::necesita_rehash($hash)) {
                $this->actualizar_password((int) $user->id_user, $password);
            }
        } elseif (! hash_equals(strtolower($hash), md5($password))) {
            return false;
        } else {
            // migrar el hash MD5 heredado
            $this->actualizar_password((int) $user->id_user, $password);
        }

        return [$user];
    }

    private function actualizar_password(int $id_user, string $password): void
    {
        $this->db->table('tbl_users')
            ->where('id_user', $id_user)
            ->update(['password' => self::hash_password($password)]);
    }
}
