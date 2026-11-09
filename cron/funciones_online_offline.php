<?php

//retorna dos array (ipmedium y ip)
function recortar_ips($conn)
{

  $sql = "SELECT ip,usuario,contrasena,port_api,id FROM `server`";
  $result = $conn->query($sql);
  $temp = array();
  if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
      $ip = $row['ip'];
      $temp[] = [
        "ip" => $ip,
        "usuario" => $row['usuario'],
        "contrasena" => $row['contrasena'],
        "puerto" => $row['port_api'],
        "nodo" => $row['id']
      ];
    }
  }
  return $temp;
}

function actualizar_online(array $Array_a_aceptuar, $conn)
{
  if (count($Array_a_aceptuar) != 0) {
    $exepciones = "";
    foreach ($Array_a_aceptuar as $key => $value) {
      if ($key == 0) {
        $exepciones .= "'" . $value['ip'] . "'";
      } else {
        $exepciones .= ",'" . $value['ip'] . "'";
      }
    }
    $sql = "UPDATE tblservicios SET `status_user`='OFFLINE' WHERE ip NOT IN ($exepciones);";
    $sql2 = "UPDATE tblservicios SET `status_user`='ONLINE' WHERE ip IN ($exepciones);";
    $conn->query($sql);
    $conn->query($sql2);
  }
}
function get_token(string $usuario,$contrasenia):string
{
  $token="";
  $parametros = http_build_query(array("login" => $usuario, "password" => $contrasenia));
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, rtrim(env_valor('cron.panelUrl', ''), '/') . "/seguridad/iniciar_sesion");
  curl_setopt($ch, CURLOPT_POST, TRUE);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $parametros);
  curl_setopt($ch, CURLOPT_HEADER, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $output = curl_exec($ch);
  curl_close($ch);
  $output = explode("\n", $output);
  $output = explode(" ", $output[8]);
  $token = $output[1];
  return $token;
}
