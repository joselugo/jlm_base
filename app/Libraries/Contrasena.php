<?php

namespace App\Libraries;

/**
 * Hash y verificación de contraseñas de los usuarios del panel (tabla login).
 *
 * Las contraseñas históricas están en MD5 sin sal. Se aceptan ambos formatos:
 * al iniciar sesión, una contraseña MD5 correcta se vuelve a guardar con
 * password_hash(), de modo que la migración ocurre sola usuario por usuario.
 */
class Contrasena
{
    // bcrypt: su hash no contiene comas, que es el separador del historial
    // (login.oldPassword). Argon2 sí las contiene.
    private const ALGORITMO = PASSWORD_BCRYPT;

    public static function hash(string $plano): string
    {
        return password_hash($plano, self::ALGORITMO);
    }

    public static function esMd5(string $guardado): bool
    {
        return preg_match('/^[a-f0-9]{32}$/i', $guardado) === 1;
    }

    public static function verificar(string $plano, string $guardado): bool
    {
        if ($guardado === '') {
            return false;
        }

        if (self::esMd5($guardado)) {
            return hash_equals(strtolower($guardado), md5($plano));
        }

        return password_verify($plano, $guardado);
    }

    public static function necesitaRehash(string $guardado): bool
    {
        return self::esMd5($guardado) || password_needs_rehash($guardado, self::ALGORITMO);
    }

    /**
     * ¿La contraseña es la actual o alguna del historial? (no se permite reutilizarlas)
     */
    public static function yaUsada(string $plano, string $actual, string $historial): bool
    {
        foreach (array_merge([$actual], explode(',', $historial)) as $guardado) {
            if (self::verificar($plano, trim($guardado))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Historial con la contraseña actual agregada (separado por comas)
     */
    public static function agregarAlHistorial(string $historial, string $actual): string
    {
        $lista   = array_filter(explode(',', $historial), 'strlen');
        $lista[] = $actual;

        return implode(',', $lista);
    }
}
