<?php
require_once __DIR__ . '/env.php';

function conectar()
{
  $credencial = credencial();
  $conexion = mysqli_connect($credencial['server'], $credencial['user'], $credencial['password'], $credencial['database'], $credencial['port']);
  if ($conexion->connect_error) {
    die($conexion->connect_error);
  } else {
    // Mismo sql_mode que la app (Config\Database::$sqlMode): compatibilidad MariaDB 10.1
    $sqlMode = env_valor('database.sqlMode', 'NO_ENGINE_SUBSTITUTION');
    $conexion->query("SET SESSION sql_mode = '" . $conexion->real_escape_string($sqlMode) . "'");
    return $conexion;
  }
  return $conexion;
}



// Credenciales desde .env (mismas claves que Config\Database de CI4)
function credencial(){
$data['user'] = env_valor('database.default.username', '');
$data['password'] = env_valor('database.default.password', '');
$data['server']= env_valor('database.default.hostname', 'localhost');
$data['database'] = env_valor('database.default.database', '');
$data['port'] = (int) env_valor('database.default.port', 3306);

return $data;
}

// Directorio de backups: <raíz del proyecto>/backup/ (denegado por HTTP)
function directorio_backup(): string
{
  return dirname(__DIR__) . '/backup/';
}

// mysqldump comprimido; la contraseña va por MYSQL_PWD para que no quede
// visible en la lista de procesos
function generar_backup(string $destino): bool
{
  $credencial = credencial();
  putenv('MYSQL_PWD=' . $credencial['password']);
  $cmd = "mysqldump -h " . escapeshellarg($credencial['server']) . " -P " . $credencial['port']
    . " -u " . escapeshellarg($credencial['user']) . " " . escapeshellarg($credencial['database'])
    . " | gzip > " . escapeshellarg($destino);
  exec($cmd, $salida, $estado);
  putenv('MYSQL_PWD');
  return $estado === 0 && is_file($destino) && filesize($destino) > 0;
}

?>
