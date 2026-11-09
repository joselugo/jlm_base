<?php
require_once dirname(__DIR__) . '/vendor/autoload.php'; // Composer (pear2/net_routeros)
require_once 'ElephantIO/autoload.php';
include 'conexion.php';
include 'funciones_online_offline.php';

use PEAR2\Net\RouterOS;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;

$conn = conectar();
$ip = recortar_ips($conn);
$ips = [];
foreach ($ip as $value) {

  $client = true;
  try {
    $client = new RouterOS\Client($value['ip'], $value['usuario'], $value['contrasena'], $value['puerto']);
  } catch (\Throwable $th) {
    $client = false;
    $data_ajax['mensaje'] = "Se elimino en la Base de datos, pero no se eliminaron todos los servicios en algunos Sistemas de mikrotik correspondientes.";
  }
  if ($client) {
    $util = new RouterOS\Util($client);
    $util->setMenu('/ip firewall address-list');
    foreach ($util->getAll() as $item) {
      $list = $item->getProperty('list');
      if ($list == "online") {
        $ips[] = [
          'ip' => $item->getProperty('address')
        ];
      }
    }
  } else {
    $nodo = $value['nodo'];
    $sql = "UPDATE `tblservicios` SET status_user='OFFLINE' WHERE nodo='$nodo'";
    $conn->query($sql);
  }
}
actualizar_online($ips, $conn);
$token = get_token(env_valor('cron.panelUser', ''), env_valor('cron.panelPassword', ''));
try {
  $client = new Client(new Version2X(env_valor('cron.socketUrl', 'http://localhost:2001'), [
    'headers' => [
      "token:$token"
    ]
  ]));

  $client->initialize();
  $client->emit('cronUpdateStateClient', ['datos' => $ips]);
  $client->close();
} catch (\Throwable $th) {
  echo "Error al conectar con el servidor socket.io";
}
