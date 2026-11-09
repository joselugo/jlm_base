<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Tiempos de espera de las consultas SNMP a los equipos de red (OLT).
 * Se pueden ajustar en .env: snmp.timeout, snmp.reintentos
 */
class Snmp extends BaseConfig
{
    // Microsegundos de espera por intento
    public $timeout = 1000000;

    // Reintentos tras el primer intento sin respuesta
    public $reintentos = 1;
}
