<?php
include("conexion.php");
//config
$num_ping = 4;
$delay = 1; //medio segundo, 1 segundo=1,000,000.
//
$conn = conectar();
$sql = "SELECT nodo,ip,id,estado_netium FROM `server`";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  $data = ping_activos($result, $num_ping, $delay);
  foreach ($data as $emisor) {
    $id = $emisor['id'];
    if ($emisor['estado'] == "desconectado" and $emisor['estado_anterior'] == "conectado") {
      $sql = "UPDATE `server` SET `estado_netium`=0 WHERE id=$id";
      $result = $conn->query($sql);
    }
    if ($emisor['estado'] == "conectado" and $emisor['estado_anterior'] == "desconectado") {
      $sql = "UPDATE `server` SET `estado_netium`=1 WHERE id=$id";
      $result = $conn->query($sql);
    }
  }
}
//@ return array
function ping_activos(object $emisores, int $num_ping, int $delay)
{
  $data = array();
  $data_return = array();
  while ($row = mysqli_fetch_array($emisores)) {
    $data_temp = array();
    $data_temp['nombre'] = $row['nodo'];
    $data_temp['fallas'] = 0;
    $data_temp['ip'] = $row['ip'];
    $data_temp['id'] = $row['id'];
    $data_temp['estado_anterior'] = $row['estado_netium'];
    $data[] = $data_temp;
  }
  for ($i = 0; $i <= $num_ping; $i++) {
    foreach ($data as $key => $value) {
      exec('ping -c 1 ' . $value['ip'], $null, $estado); //estado=1  desconectado
      if ($estado != 0) {
        $data[$key]['fallas'] = $data[$key]['fallas'] + 1;
      }
      if ($i == $num_ping) {
        $data_temporal = array();
        $data_temporal['nombre'] = $value['nombre'];
        $data_temporal['id'] = $value['id'];
        $data_temporal['estado_anterior'] = $value['estado_anterior'] == 0 ? 'desconectado' : 'conectado';
        $data_temporal['estado'] = $data[$key]['fallas'] != $num_ping + 1 ? 'conectado' : 'desconectado';
        $data_temporal['fallas'] = $data[$key]['fallas'];
        $data_temporal['ip'] = $value['ip'];
        $data_return[] = $data_temporal;
      }

      sleep($delay);
    }
  }
  return $data_return;
}
