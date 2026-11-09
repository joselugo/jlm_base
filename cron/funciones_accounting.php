<?php

//@return body de la consulta
function datos_url(String $url)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HEADER, 0);
  $data = curl_exec($ch);
  $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  if ($httpcode == 200) {
    $data = str_replace("\n", ' ', $data);
    return explode(" ", $data);
  } else {
    return false;
  }
}
//@retorna un array
function reducir_por_igualdad(array $array_multidimensional)
{
  $unique = array();
  foreach ($array_multidimensional as $key1 => $value1) {
    $existe = false;
    foreach ($unique as $key2 => $value2) {
      if ($value1['iporigen'] == $value2['iporigen']) {
        $existe = true;
      }
    }
    if (!$existe) {
      $unique[] = $value1;
    } else {
      foreach ($unique as $key_unique => $value) {
        if ($value['iporigen'] == $value1['iporigen']) {
          $unique[$key_unique]['bytes'] += $value1['bytes'];
        }
      }
    }
  }
  return $unique;
}
//retorna dos array (ipmedium y ip)
function recortar_ips($conn)
{

  $sql = "SELECT ip,port_web FROM `server`";
  $result = $conn->query($sql);
  $temp = array();
  if ($result) {
    if ($result->num_rows > 0) {
      while ($row = mysqli_fetch_array($result)) {
        $ip = $row['ip'];
        $port = $row['port_web'];
        $temp[] = [
          "ip" => $ip,
          "port" => $port,
          "ipmedium" => get_medium_ip($ip)
        ];
      }
    }
    $result->close();
  }
  return $temp;
}
//retorna dos array (clientes)
function get_clientes($conn)
{

  $sql = "SELECT ip FROM tblservicios";
  $result = $conn->query($sql);
  $temp = array();
  if ($result) {
    if ($result->num_rows > 0) {
      while ($row = mysqli_fetch_array($result)) {
        if ($row['ip']!="") {
          $tmp = array();
          $tmp['ip'] = $row['ip'];
          $temp[] = $tmp;
        }
      }
    }
    $result->close();
  }
  return $temp;
}

//retorna dos array (ipmedium y ip)
function recortar_ips_api_mikrotik($conn)
{

  $sql = "SELECT ip,usuario,contrasena,port_web FROM `server`";
  $result = $conn->query($sql);
  $temp = array();
  if ($result) {
    if ($result->num_rows > 0) {
      while ($row = mysqli_fetch_array($result)) {
        $ip = $row['ip'];
        $port = $row['port_web'];
        $temp[] = [
          "ip" => $ip,
          "port" => $port,
          "ipmedium" => get_medium_ip($ip)
        ];
      }
    }
  }
  $result->close();
  return $temp;
}
//retorna un string;
function get_medium_ip($ip)
{
  $medium_ip = "";
  $ip = explode(".", $ip, -2);
  foreach ($ip as $value) {
    $medium_ip .=  $value . ".";
  }
  return $medium_ip;
}
//@retorna dos arrays (clientes_web y web_clientes)
function categorizar_solicitudes(array $data, String $ipmedium, array $clientes_a)
{
  $temp = array();
  $clientes_web = array();
  $web_clientes = array();
  $deconocidos = array();
  $clientes = array();
  for ($i = 0; $i < count($data) - 1; $i = $i + 6) {
    $data_temp = array();
    $data_temp['bytes'] = intval($data[$i + 2]);
    if (in_array($data[$i], $clientes_a)) {
      $data_temp['iporigen'] = $data[$i];
      $clientes_web[] = $data_temp;
    }
    if (in_array($data[$i + 1], $clientes_a)) {
      $data_temp['iporigen'] = $data[$i + 1];
      $web_clientes[] = $data_temp;
    }/*  else {
      $deconocidos[] = $data_temp;
    } */
  }
  $temp = [
    "clientes_web" => $clientes_web,
    "web_clientes" => $web_clientes,
    /*  "desconcidos" => $deconocidos, */
  ];
  return $temp;
}

function insert_all_db(array $data, String $fecha, $conn)
{
  foreach ($data as $value) {
    $cliente = $value['cliente'];/* $web = $value['web']; */
    $subida = $value['subida'];
    $descarga = $value['descarga'];
    $server = $value['server'];
    $sql = "INSERT INTO `traficodata`(`ipaddress`, `upload`, `dowload`, `datestart`,`server`) VALUES ('$cliente','$subida','$descarga','$fecha','$server')";
    $conn->query($sql);
  }
}

function estadosAnteriores(Array $data,$conn){
  $subSQL = "";
  foreach (array_column($data, "ip") as $key => $value) {
    $coma = $key != 0 ? ',' : '';
    $subSQL .= "$coma(SELECT MAX(id) FROM traficodata WHERE ipaddress=\"$value\")";
  }
  $sql = "SELECT ipaddress,id,sessionstop FROM traficodata where id in ($subSQL)";
  $result=$conn->query($sql);
  $temp = array();
  if ($result) {
    if ($result->num_rows > 0) {
      while ($row = mysqli_fetch_array($result)) {
        $tmp = array();
        $tmp['ip'] = $row['ipaddress'];
        $tmp['id'] = $row['id'];
        $tmp['sessionstop'] = $row['sessionstop']==0?false:true;
        $temp[] = $tmp;
      }
    }
    $result->close();
  }
  return $temp;
}
function cerrar_sessiones($conn){
  $sql= "UPDATE traficodata SET sessionstop=1 WHERE sessionstop=0";
  $conn->query($sql);
}