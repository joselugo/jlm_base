<?php

include("conexion.php");

$dir = directorio_backup();
$filename = "Mikrowisp6_backup_cron_" . date("m-d-Y") . ".sql" . ".zip";

if (generar_backup($dir . $filename)) {
    //print("Backup creado con exito");
} else {
    print("Error al crear backup");
}
