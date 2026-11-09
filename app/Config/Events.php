<?php

namespace Config;

use CodeIgniter\Events\Events;
use CodeIgniter\Exceptions\FrameworkException;
use CodeIgniter\HotReloader\HotReloader;

/*
 * --------------------------------------------------------------------
 * Application Events
 * --------------------------------------------------------------------
 * Events allow you to tap into the execution of the program without
 * modifying or extending core files. This file provides a central
 * location to define your events, though they can always be added
 * at run-time, also, if needed.
 *
 * You create code that can execute by subscribing to events with
 * the 'on()' method. This accepts any form of callable, including
 * Closures, that will be executed when the event is triggered.
 *
 * Example:
 *      Events::on('create', [$myInstance, 'myMethod']);
 */

// Avisos que PHP 8 subió de notice a warning: al log en vez de error 500
// (ver App\Libraries\AvisosPhp8)
Events::on('pre_system', static function (): void {
    \App\Libraries\AvisosPhp8::registrar();
});

// Compatibilidad con MariaDB 10.1 (ver Config\Database::$sqlMode). Los modelos
// comparten la conexión 'default', así que basta con fijarlo una vez por petición.
// pre_system: en CI 4.7 ya no existe pre_controller y algunos constructores
// de controladores ya consultan la BD
Events::on('pre_system', static function (): void {
    $sqlMode = config(Database::class)->sqlMode;

    if ($sqlMode !== null && ! is_cli()) {
        $db = Database::connect();
        $db->simpleQuery('SET SESSION sql_mode = ' . $db->escape($sqlMode));
    }
});

Events::on('pre_system', static function (): void {
    if (ENVIRONMENT !== 'testing') {
        $value = ini_get('zlib.output_compression');

        if (filter_var($value, FILTER_VALIDATE_BOOLEAN) || (int) $value > 0) {
            throw FrameworkException::forEnabledZlibOutputCompression();
        }

        while (ob_get_level() > 0) {
            ob_end_flush();
        }

        ob_start(static fn ($buffer) => $buffer);
    }

    /*
     * --------------------------------------------------------------------
     * Debug Toolbar Listeners.
     * --------------------------------------------------------------------
     * If you delete, they will no longer be collected.
     */
    if (CI_DEBUG && ! is_cli()) {
        Events::on('DBQuery', 'CodeIgniter\Debug\Toolbar\Collectors\Database::collect');
        service('toolbar')->respond();
        // Hot Reload route - for framework use on the hot reloader.
        if (ENVIRONMENT === 'development') {
            service('routes')->get('__hot-reload', static function (): void {
                (new HotReloader())->run();
            });
        }
    }
});
