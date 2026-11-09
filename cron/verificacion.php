<?php
require_once dirname(__DIR__) . '/vendor/autoload.php'; // Composer (pear2/net_routeros)
require_once 'ElephantIO/autoload.php';
include 'conexion.php';
include 'funciones_verificacion.php';

use PEAR2\Net\RouterOS;

$tiempo_inicio = microtime(true);

$conn = conectar();
$ip = recortar_ips($conn);
$ips = 0;
$ip1 = 0;
$fallas = 0;


/* $query = RouterOS\Query::where('address', $value1['ipservicio']); */
foreach ($ip as $value) {
 if ($value['estado']=="SUSPENDIDO") {
  $comentario = "Corte_Servicio_" . $value['idservicio'];
  $client = true;
  try {
    $client = new RouterOS\Client($value['ipserver'], $value['usuario'], $value['contrasena'], $value['puerto']);
  } catch (\Throwable $th) {
    $client = false;
  }
  if ($client) {
    
    try {
      $util = new RouterOS\Util($client);
      $util->setMenu('/ip firewall address-list');
      $query=RouterOS\Query::where('comment', $comentario);
      $hey=$util->count($query) ;
      if ($hey==0) {
        $data = array(
          'list' => 'morosos',
          'address' => $value['ipservicio'],
          'comment' => $comentario
        );
        $util->add($data);
      }
    } catch (\Throwable $th) {
    }
  }
 }
}

$tiempo_fin = microtime(true);

echo "Tiempo empleado: " . ($tiempo_fin - $tiempo_inicio);
