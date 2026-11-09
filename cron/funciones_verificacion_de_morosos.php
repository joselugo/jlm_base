<?php

function get_morosos(String $fecha, object $conn)
{
  $tmp = array();
  $sql_facturas = "SELECT f.id idfactura,f.idcliente,s.id idservicio,se.ip ipserver,se.usuario,se.contrasena,se.port_api,s.ip ipservicio FROM facturas f left join usuarios2 u on f.idcliente=u.id LEFT JOIN tblavisouser t on t.cliente=f.idcliente LEFT JOIN tblservicios s ON s.idcliente=f.idcliente LEFT JOIN server se on se.id=s.nodo WHERE DATE_ADD(f.vencimiento,INTERVAL (ifnull(t.corteautomatico,0)) DAY)='$fecha' and f.estado='No pagado' and f.tipo=2 and u.estado='ACTIVO'";
  $facturas = $conn->query($sql_facturas);
  if ($facturas->num_rows > 0) {
    foreach ($facturas as $value) {
      if (intval($value['idcliente']) != 0) {
        $tmp[] = [
          "idcliente" => $value['idcliente'],
          "idservicio" => $value['idservicio'],
          "ipservicio" => $value['ipservicio'],
          "ipserver" => $value['ipserver'],
          "usuario" => $value['usuario'],
          "contrasena" => $value['contrasena'],
          "puerto" => $value['port_api'],
          "idfactura" => $value['idfactura']
        ];
      }
    }
  } else {
    exit;
  }
  return $tmp;
}

function update_db(array $clientes, $idfacturas, $reconexion, $conn)
{
  $clientes = implode(",", $clientes);
  $facturas = implode(",", $idfacturas);
  $sql = "UPDATE usuarios2 SET `estado`='SUSPENDIDO SISTEMA' WHERE id IN ($clientes);";
  $sql2 = "UPDATE facturas SET oxxo_referencia='$reconexion', oxxo_id='SUSPENDIDO SISTEMA', sub_total=sub_total+$reconexion, total=total+$reconexion WHERE id IN ($facturas);";  $conn->query($sql);
  $conn->query($sql2);
}

function get_reconexion(object $conn)
{
  $costo = 0;
  $sql_facturas = "SELECT value costo FROM tblconfiguration where id=34";
  $facturas = $conn->query($sql_facturas);
  if ($facturas->num_rows > 0) {
    foreach ($facturas as $value) {
      $costo = intval($value['costo']);
    }
  }
  return $costo;
}
