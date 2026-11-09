<?php
require_once dirname(__DIR__) . '/vendor/autoload.php'; // Composer (pear2/net_routeros)

use PEAR2\Net\RouterOS;

include('conexion.php');
include('funciones_cortes.php');
date_default_timezone_set('America/Mexico_City');
$fecha = date('Y-m-d');
//$fecha = '2021-05-11';
$conn = conectar();
$facturas = get_morosos($fecha, $conn);
$costo_reconexion = get_reconexion($conn);
$clientes = array_unique(array_column($facturas, "idcliente"));
$idfacturas = array_unique(array_column($facturas, "idfactura"));
foreach ($facturas as $value) {
  $comentario = "Corte_Servicio_" . $value['idservicio'];
  $client = true;
  try {
    $client = new RouterOS\Client($value['ipserver'], $value['usuario'], $value['contrasena'], $value['puerto']);
  } catch (\Throwable $th) {
    $client = false;
  }
  if ($client) {
    $util = new RouterOS\Util($client);
    $util->setMenu('/ip firewall address-list');
    $data = array(
      'list' => 'morosos',
      'address' => $value['ipservicio'],
      'comment' => $comentario
    );
    $util->add($data);
  }
}
$result = update_db($clientes, $idfacturas, $costo_reconexion, $conn); 
//print("Costo de reconexion es: $costo_reconexion (tomado de tblconfiguration)(ojo le puse int, mañana lecambio de float) \n");
//print("Los clientes que se cambian de estado son:  \n");
//print_r($clientes);
//print("Las facturas que se le suman $costo_reconexion pesos son los sig :  \n");
//print_r($idfacturas);
//print("Por ultimo, la data de los firewall-servicios son los sig: \n");
//print_r($facturas);
