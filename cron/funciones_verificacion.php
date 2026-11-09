<?php

//retorna dos array (ipmedium y ip)
function recortar_ips($conn)
{

  $sql = "SELECT u.nombre,u.estado,t.id idservicio,t.ip ipservicio,s.ip,s.usuario,s.contrasena,s.port_api FROM tblservicios t left join usuarios2 u on u.id=t.idcliente LEFT join server s on t.nodo=s.id WHERE u.estado<>\"RETIRADO\"";
  $result = $conn->query($sql);
  $temp = array();
  if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
      $temp[] = [
        "ipserver" => $row['ip'],
        "usuario" => $row['usuario'],
        "contrasena" => $row['contrasena'],
        "puerto" => $row['port_api'],
        "estado" => $row['estado'],
        "idservicio" => $row['idservicio'],
        "ipservicio" => $row['ipservicio'],
        "nombre" => $row['nombre'],
      ];
    }
  }
  return $temp;
}
