<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

/**
 * Cliente (tabla usuarios2).
 *
 * Nota: usuarios2.id es INT ZEROFILL ("000328"); el panel usa ese formato en
 * enlaces y registros, así que el id se conserva como texto.
 */
class Cliente extends Entity
{
    protected $casts = [
        'sucursal' => '?integer',
    ];

    public const ACTIVO             = 'ACTIVO';
    public const SUSPENDIDO         = 'SUSPENDIDO';
    public const SUSPENDIDO_SISTEMA = 'SUSPENDIDO SISTEMA'; // lo pone el cron de cortes
    public const RETIRADO           = 'RETIRADO';
}
