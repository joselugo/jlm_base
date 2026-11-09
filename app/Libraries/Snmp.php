<?php

namespace App\Libraries;

/**
 * Consultas SNMP v2c a los equipos de red (OLT Huawei).
 *
 * Antes cada llamada fijaba sus propios tiempos (hasta 5 s x 4 intentos) y,
 * si una OLT no respondía, cada consulta volvía a esperar: el dashboard de
 * Fiber podía tardar más de un minuto. Aquí los tiempos salen de Config\Snmp
 * y un equipo que no responde se salta durante el resto de la petición.
 *
 * Devuelven lo mismo que snmp2_real_walk/snmp2_get/snmp2_set (false si falla).
 */
class Snmp
{
    /**
     * Equipos que no respondieron en esta petición
     *
     * @var array<string, true>
     */
    private static array $sinRespuesta = [];

    public static function walk(string $host, string $comunidad, string $oid)
    {
        return self::consultar($host, static fn (int $t, int $r) => snmp2_real_walk($host, $comunidad, $oid, $t, $r));
    }

    public static function get(string $host, string $comunidad, string $oid)
    {
        return self::consultar($host, static fn (int $t, int $r) => snmp2_get($host, $comunidad, $oid, $t, $r));
    }

    /**
     * @param array|string $oid
     * @param array|string $tipo
     * @param array|string $valor
     */
    public static function set(string $host, string $comunidad, $oid, $tipo, $valor): bool
    {
        return (bool) self::consultar($host, static fn (int $t, int $r) => snmp2_set($host, $comunidad, $oid, $tipo, $valor, $t, $r));
    }

    private static function consultar(string $host, callable $consulta)
    {
        if (isset(self::$sinRespuesta[$host])) {
            return false;
        }

        $config = config('Snmp');
        snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
        error_clear_last();

        // @: "No response from ..." es un warning; se trata como resultado false
        $resultado = @$consulta((int) $config->timeout, (int) $config->reintentos);

        if ($resultado === false && str_starts_with((string) (error_get_last()['message'] ?? ''), 'No response from')) {
            self::$sinRespuesta[$host] = true;
            log_message('warning', "SNMP: {$host} no responde; se omiten sus consultas en esta petición");
        }

        return $resultado;
    }
}
