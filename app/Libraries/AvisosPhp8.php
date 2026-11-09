<?php

namespace App\Libraries;

/**
 * Compatibilidad del código heredado con PHP 8.
 *
 * PHP 8 subió de E_NOTICE a E_WARNING varios avisos de los que el código
 * depende (variable o índice inexistente, leer una propiedad de null).
 * CodeIgniter convierte los warnings en excepciones (error 500), así que
 * pantallas que en PHP 7 funcionaban dejan de cargar.
 *
 * Mientras se corrigen, SOLO esos casos se registran en el log
 * ("[PHP8] ... en archivo:línea") en lugar de interrumpir la petición.
 * Cualquier otro warning o error sigue su curso normal.
 */
class AvisosPhp8
{
    private const PATRONES = [
        'Undefined variable',
        'Undefined array key',
        'Undefined property',
        'Attempt to read property',
        'Trying to access array offset',
    ];

    public static function registrar(): void
    {
        $anterior = null;
        $anterior = set_error_handler(static function (int $nivel, string $mensaje, string $archivo = '', int $linea = 0) use (&$anterior) {
            if ($nivel === E_WARNING && self::esAvisoPromovido($mensaje)) {
                log_message('warning', '[PHP8] ' . $mensaje . ' en ' . clean_path($archivo) . ':' . $linea);

                return true;
            }

            return $anterior !== null ? $anterior($nivel, $mensaje, $archivo, $linea) : false;
        });
    }

    private static function esAvisoPromovido(string $mensaje): bool
    {
        foreach (self::PATRONES as $patron) {
            if (str_starts_with($mensaje, $patron)) {
                return true;
            }
        }

        return false;
    }
}
