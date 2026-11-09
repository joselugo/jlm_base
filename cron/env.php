<?php
// Lee el .env de la raíz del proyecto: el mismo archivo que usa CI4, para que
// las credenciales vivan en un solo lugar. Formato: clave = valor

function cargar_env(): array
{
  static $vars = null;
  if ($vars !== null) {
    return $vars;
  }
  $vars = array();
  $archivo = dirname(__DIR__) . '/.env';
  if (!is_file($archivo)) {
    fwrite(STDERR, "No existe $archivo\n");
    return $vars;
  }
  foreach (file($archivo, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $linea) {
    $linea = trim($linea);
    if ($linea === '' || $linea[0] === '#' || strpos($linea, '=') === false) {
      continue;
    }
    list($clave, $valor) = array_map('trim', explode('=', $linea, 2));
    $comilla = substr($valor, 0, 1);
    if (strlen($valor) > 1 && ($comilla === '"' || $comilla === "'") && substr($valor, -1) === $comilla) {
      $valor = substr($valor, 1, -1);
    }
    $vars[$clave] = $valor;
  }
  return $vars;
}

function env_valor(string $clave, $defecto = null)
{
  $vars = cargar_env();
  return (isset($vars[$clave]) && $vars[$clave] !== '') ? $vars[$clave] : $defecto;
}
