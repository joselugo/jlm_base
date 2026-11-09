<?php
include 'conexion.php';
include 'funciones_accounting.php';
date_default_timezone_set('America/Mexico_City');
//---------------------------------------------
$conn = conectar();
$fecha = Date('Y-m-d H:i:s');
$fecha_corte=strtotime(date('Y-m-d')." 00:03:00");
if (strtotime($fecha)<$fecha_corte) {
  cerrar_sessiones($conn);
}
$data_ip = recortar_ips($conn); //obtiene ip del los server.
$clientes_a = get_clientes($conn);
$data = array();
foreach ($data_ip as $datos_ip) {
  $url = $datos_ip['ip'] . ":" . $datos_ip['port'] . '/accounting/ip.cgi';
  $all_data = datos_url($url);
  if ($all_data) {
    $categorizado = categorizar_solicitudes($all_data, $datos_ip['ipmedium'], array_column($clientes_a,"ip"));
    unset($all_data);
    //1.obtenemos un array de  clientes que mandaron datos a sitios web sin repeticiones y con los paquetes sumados.
    $unique_cliente_web = reducir_por_igualdad($categorizado['clientes_web']);
    //2.obtenemos un array de sitios web que que enviarion datos a los clientes sin repeticiones  y con los paquetes sumados.
    $unique_web_cliente = reducir_por_igualdad($categorizado['web_clientes']);
    unset($categorizado);
    //3.igualamos los de origen sumando los paquetes.
    foreach ($unique_cliente_web as $value1) {
      $existe = false;
      foreach ($unique_web_cliente as $value2) {
        if ($value1['iporigen'] == $value2['iporigen']) {
          $existe = true;
          $data[] = [
            "cliente" => $value1['iporigen'],
            "subida" => $value1['bytes'],
            "server" => $datos_ip['ip'],
            "descarga" => $value2['bytes'],
          ];
        }
      }
      if (!$existe) {
        $data_existe = false;
        foreach ($data as $key_data => $value_data) {
          if ($value_data['cliente'] == $value1['iporigen']) {
            $data_existe = true;
            $data[$key_data]['subida'] += $value1['bytes'];
          }
        }
        if (!$data_existe) {
          $data[] = [
            "cliente" => $value1['iporigen'],
            "subida" => $value1['bytes'],
            "server" => $datos_ip['ip'],
            "descarga" => 0
          ];
        }
      }
    }
    foreach ($unique_web_cliente as $key => $value1) {
      $existe = false;
      foreach ($unique_cliente_web as $key => $value2) {
        $value1['iporigen'] == $value2['iporigen'] ? $existe = true : null;
      }
      if (!$existe) {
        $data_existe = false;
        foreach ($data as $key_data => $value_data) {
          if ($value_data['cliente'] == $value1['iporigen']) {
            $data_existe = true;
            $data[$key_data]['descarga'] += $value1['bytes'];
          }
        }
        if (!$data_existe) {
          $data[] = [
            "cliente" => $value1['iporigen'],
            "subida" => 0,
            "server" => $datos_ip['ip'],
            "descarga" => $value1['bytes']
          ];
        }
      }
    }
  }
}

$estadosAnteriores=estadosAnteriores($clientes_a,$conn);
$tiempo_final = microtime(true);
$tiempo = $tiempo_final - $tiempo_inicial;
foreach ($clientes_a as $key => $value) {
  $index=array_search($value['ip'], array_column($data,'cliente'));
  $index2=array_search($value['ip'], array_column($estadosAnteriores,'ip'));
  if (is_int($index)) {//Si el ip del servicio exite en el snapshot
    $subida=$data[$index]['subida'];
    $descarga=$data[$index]['descarga'];
    $server=$data[$index]['server'];
    if (is_int($index2)) {//Si el ip del servicio existe en la tabla Radact
      $idradact=$estadosAnteriores[$index2]['id'];
      $sessionstop=$estadosAnteriores[$index2]['sessionstop'];
      if ($sessionstop) { //Si el maxID de la ip en la tabla radact es TRUE
        //new row
        //print("//new row 1 \n");
        $ipcliente= $value['ip'];
        $sql = "INSERT INTO `traficodata`(`ipaddress`, `upload`, `dowload`, `datestart`,`server`) VALUES ('$ipcliente','$subida','$descarga','$fecha','$server')";
        $conn->query($sql);
      }else{
        //sum
        //print("//sum \n");
        $sql = "UPDATE traficodata SET upload=upload+$subida, dowload=dowload+$descarga WHERE id=$idradact;";
        $conn->query($sql);
      }
    }else{
      //new row
      //print("//new row 2\n");
      $ipcliente= $value['ip'];
      $sql = "INSERT INTO `traficodata`(`ipaddress`, `upload`, `dowload`, `datestart`,`server`) VALUES ('$ipcliente','$subida','$descarga','$fecha','$server')";
      $conn->query($sql);
    }
  }else{
    if (is_int($index2)) { //Si el ip del servicio existe en la tabla Radact
      //update sessionstop
      //print("//update sessionstop \n");
      $idradact = $estadosAnteriores[$index2]['id'];
      $sql = "UPDATE traficodata SET sessionstop=1,datestop=\"$fecha\" WHERE id=$idradact";
      $conn->query($sql);
    }else{
      /* print("//No hacer nada \n"); */
    }
  }
}

$conn->close();