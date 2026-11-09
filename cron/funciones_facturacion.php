<?php

function get_clientes_que_facturan_hoy(object $result, String $str_fecha_actual)
{
  $ids_clientes = array();
  while ($row = mysqli_fetch_array($result)) {
    $diafactura = $row['diafactura'];
    if ($diafactura != "") {
      $strday = strtotime("+$diafactura days", $str_fecha_actual);
      $day_create_factura = date("d", $strday);
    } else {
      $day_create_factura = date("d", $str_fecha_actual);
    }
    // var_dump($day_create_factura);
    // var_dump($row['diapago']);
    if ($day_create_factura == $row['diapago']) {
      $ids_clientes[] = [
        "idcliente" => $row['cliente'],
        "otros_impuestos" => $row['otros_impuestos'],
        "impuesto" => $row['impuesto'],
        "tipo_pago" => $row['tipopago']
      ];
    }
  }
  return $ids_clientes;
}
function get_vencimiento(String $idcliente, int $tipo_pago, object $conn)
{
  $result_avisouser = $conn->query("SELECT fecha_factura FROM `tblavisouser` WHERE cliente=" . $idcliente);
  $fecha_pago = $result_avisouser->fetch_row()[0];
  if ($fecha_pago == 0) {
    $sql = "SELECT `t`.`diapago`, `f`.`vencimiento`,`t`.`corteautomatico`
              FROM `facturas` `f`
              LEFT JOIN `tblavisouser` `t` ON `t`.`cliente`=`f`.`idcliente`
              WHERE `idcliente` = '$idcliente'
              AND `estado` <> 'anulado'
              AND `tipo` = '2'
              ORDER BY `f`.`id` DESC
              LIMIT 1";
    $result_max_fecha = $conn->query($sql);
    if ($result_max_fecha->num_rows > 0) {;
      $result_max_fecha = $result_max_fecha->fetch_row();
      $vencimiento = $result_max_fecha[1];
      $dia_pago = $result_max_fecha[0];
      $nuevafecha = strtotime('+1 month', strtotime($vencimiento));
      $nuevafecha = date('y-m-d', $nuevafecha);
      $mes = date("m", strtotime($nuevafecha));
      $año = date("y", strtotime($nuevafecha));
      $nuevafecha = $año . "-" . $mes . "-" .  $dia_pago;
      $nuevafecha = date('y-m-d', strtotime($nuevafecha));
      $vencimiento = $nuevafecha;
      $primera_factura = false;
    } else {
      $primera_factura = true;
    }
  } else {
    $vencimiento = $fecha_pago;
    $primera_factura = false;
  }
  if (!$primera_factura) {
    if ($tipo_pago == 0) {
      //pospago
      $desde = date('d/m/Y', strtotime($vencimiento));
      $hasta = date('d/m/Y', strtotime("+1month", strtotime("-1 days", strtotime($vencimiento))));
    }
    if ($tipo_pago == 1) {
      //prepago
      $desde = date('d/m/Y', strtotime("-1 month", strtotime($vencimiento)));
      $hasta = date('d/m/Y', strtotime("-1 day", strtotime($vencimiento)));
    }
  }
  $data = [
    "primerafactura" => $primera_factura ? "" : $primera_factura,
    "vencimiento" => $primera_factura ? "" : $vencimiento,
    "desde" => $primera_factura ? "" : $desde,
    "hasta" => $primera_factura ? "" : $hasta,
    "fecha_corte" => $primera_factura ? "" : date('d/m/Y', strtotime("+ " . $result_max_fecha[2] . " day", strtotime($vencimiento)))
  ];
  return $data;
}
function get_planes(String $idcliente, array $fechas, object $conn)
{
  $temp_descripcion = array();
  $sql = "SELECT `p`.`plan`, `s`.`descripcion`, `p`.`velocidad`, `s`.`costo`, `p`.`impuesto`
      FROM `tblservicios` `s`
      LEFT JOIN `perfiles` `p` ON `p`.`id`=`s`.`idperfil`
      WHERE `s`.`idcliente` = '$idcliente'";
  $result_planes = $conn->query($sql);
  foreach ($result_planes as $plan) {
    $velocidad = explode("/", $plan['velocidad']);
    $data = [
      "{fecha}" => $fechas['desde'] . " al " . $fechas['hasta'],
      "{corte}" => $fechas['fecha_corte'],
      "{mes_actual}" => "pendiente",
      "{plan}" => $plan['plan'],
      "{down}" => $velocidad[1],
      "{up}" => $velocidad[0],

    ];
    $descripcion = strtr($plan['descripcion'], $data);
    $temp_descripcion[] = [
      "descripcion" => $descripcion,
      'plan' => $plan['plan'],
      'velocidad' => $plan['velocidad'],
      'costo' => $plan['costo'],
      'impuesto' => $plan['impuesto']
    ];
  }
  return $temp_descripcion;
}
function get_productos(String $idcliente, object $conn)
{
  $temp_producto = array();
  $sql = "SELECT `t`.`id`  as id_tmp,`a`.`id`, `a`.`serial_producto`, `p`.`impuesto`, `p`.`descripcion`, `t`.`monto`, `a`.`cantidad`, `p`.`clave_invoice`
      FROM `tmpfactura` `t`
      LEFT JOIN `almacen` `a` ON `a`.`id`=`t`.`productoid`
      LEFT JOIN `productos` `p` ON `p`.`id`=`a`.`productoid`
      WHERE `t`.`userid` = '$idcliente'
      AND `t`.`state` = 'pendiente'";
  $result_productos = $conn->query($sql);
  foreach ($result_productos as $producto) {
    $data = [
      "{producto_serial}" => $producto['serial_producto'],
    ];
    $descripcion = strtr($producto['descripcion'], $data);
    $temp_producto[] = [
      "descripcion" => $descripcion,
      'id' => $producto['id'],
      'id_tmp' => $producto['id_tmp'],
      'impuesto' => $producto['impuesto'],
      'monto' => $producto['monto'],
      'cantidad' => $producto['cantidad'],
      'clave_invoice' => $producto['clave_invoice'],
    ];
  }
  return $temp_producto;
}
function get_saldos(String $idcliente, object $conn)
{
  $temp_saldos = array();
  $sql = "SELECT `id`, `monto`, `idorigen`
    FROM `saldos`
    WHERE `monto` > 0
    AND `estado` = 'no cobrado'
    AND `iduser` = '$idcliente'";
  $result_saldos = $conn->query($sql);
  foreach ($result_saldos as $saldo) {
    $temp_saldos[] = [
      'id' => $saldo['id'],
      'monto' => $saldo['monto'],
      'idorigen' => $saldo['idorigen']
    ];
  }
  return $temp_saldos;
}
function verificacion(String $idcliente, object $conn)
{
  $sql = "SELECT IF(estado='pagado',TRUE,FALSE) as pago
    FROM `facturas`
    WHERE `idcliente` = '$idcliente' ORDER BY id DESC LIMIT 1";
  $result_factura = $conn->query($sql);
  if ($result_factura->num_rows > 0) {
    foreach ($result_factura as $value) {
      if ($value['pago'] == 1) {
        return true;
      } else {
        return false;
      }
    }
  } else {
    return true;
  }
}
function get_descuentos(String $idcliente, object $conn)
{
  $temp_descuento = array();
  $sql = "SELECT sum(monto) descuento
    FROM `saldos`
    WHERE `estado` = 'no cobrado'
    AND `iduser` = '$idcliente'
    AND `monto` < 0";
  $result_descuentos = $conn->query($sql);
  $result_descuentos = $result_descuentos->fetch_row();
  $descuento = $result_descuentos[0];
  return abs($descuento);
}
function get_legal(object $conn)
{
  $sql = "SELECT `value`
      FROM `tblconfiguration`
      WHERE `id` = 12";
  $result_legal = $conn->query($sql);
  $result_legal = $result_legal->fetch_row();
  $sql = "UPDATE `tblconfiguration` SET value=value+1 WHERE id = 12";
  $conn->query($sql);
  return $result_legal[0];
}
function update_tmp_facturas(String $id_factura_creada, array $productos, object $conn)
{
  foreach ($productos as $producto) {
    $sql = "UPDATE tmpfactura SET state='facturado',nfactura=$id_factura_creada WHERE id=" . $producto['id_tmp'];
    $conn->query($sql);
  }
}
function new_factura(String $legal, string $idcliente, string $emitido, string $vencimiento, string $total, String $estado, String $iva, string $subtotal, String $impuesto, String $otros_impuestos, object $conn)
{
  $sql = "INSERT INTO
        `facturas` (`id`, `legal`, `idcliente`, `emitido`, `vencimiento`, `pago`, `total`, `forma`, `estado`, `tipo`, `cobrado`, `iva_igv`, `sub_total`, `impuesto`, `mp_id`, `mp_estado`, `payu_id`, `payu_pdf`, `payu_tipo`, `payu_urlpdf`, `oxxo_id`, `oxxo_referencia`, `total_khipu`, `id_khipu`, `url_khipu`, `barcode_cobro_digital`, `data_cobro_digital`, `otros_impuestos`, `siro`)
        VALUES
        (NULL, '$legal', '$idcliente', '$emitido', '$vencimiento', '0', '$total', '', '$estado', '2', '0', '$iva', '$subtotal', '$impuesto', '', '', '', '', '', '', '', '', '0', '', '', '', '', '$otros_impuestos', '0')";
  $result_insert_factura = $conn->query($sql);
  if ($result_insert_factura == true) {
    $id_factura_creada = $conn->insert_id;
    return $id_factura_creada;
  } else {
    return false;
  }
}
function update_saldos(string $idcliente, string $id_factura_creada, object $conn)
{
  $sql = "UPDATE saldos SET iddestino=$id_factura_creada,estado='facturado' WHERE iduser=$idcliente AND estado<>'facturado'";
  $result = $conn->query($sql);
  return $result;
}
function insert_factura_items_productos(String $id_factura_creada, array $productos, object $conn)
{
  foreach ($productos as $producto) {
    $descripcion = $producto['descripcion'];
    $cantidad = $producto['monto'];
    $idalmacen = $producto['id'];
    $unidades = $producto['cantidad'];
    $impuesto = $producto['impuesto'];
    $sql = "INSERT INTO
    `facturaitems` (`id`, `idfactura`, `descripcion`, `cantidad`, `idalmacen`, `unidades`, `impuesto`, `block`, `impuesto911`, `clave_invoice`)
    VALUES
    (NULL, '$id_factura_creada', '$descripcion', '$cantidad', '$idalmacen', '$unidades', '$impuesto', '0', '0.00', '')";
    $conn->query($sql);
  }
}
function insert_factura_items_saldos(String $id_factura_creada, array $saldos, object $conn)
{
  foreach ($saldos as $saldo) {
    $factura= $saldo['idorigen'];
    $descripcion = "Saldo anterior del comprobante #$factura";
    $cantidad = $saldo['monto'];
    $idalmacen = 0;
    $unidades=1;
    $impuesto=0;
    $sql = "INSERT INTO
    `facturaitems` (`id`, `idfactura`, `descripcion`, `cantidad`, `idalmacen`, `unidades`, `impuesto`, `block`, `impuesto911`, `clave_invoice`)
    VALUES
    (NULL, '$id_factura_creada', '$descripcion', '$cantidad', '$idalmacen', '$unidades', '$impuesto', '0', '0.00', '')";
    $conn->query($sql);
  }
}
function insert_factura_items_planes(string $id_factura_creada, array $planes, object $conn)
{
  foreach ($planes as $plan) {
    $descripcion = $plan['descripcion'];
    $cantidad = $plan['costo'];
    $idalmacen = 0;
    $unidades = 1;
    $impuesto = $plan['impuesto'];
    $sql = "INSERT INTO
    `facturaitems` (`id`, `idfactura`, `descripcion`, `cantidad`, `idalmacen`, `unidades`, `impuesto`, `block`, `impuesto911`, `clave_invoice`)
    VALUES
    (NULL, '$id_factura_creada', '$descripcion', '$cantidad', '$idalmacen', '$unidades', '$impuesto', '0', '0.00', '')";
    $conn->query($sql);
  }
}
function new_log(String $id_factura_creada, String $idcliente, object $conn)
{
  $txt = "Nueva factura de servicios creada -Cliente ID: " . $idcliente . " - Factura ID: " . $id_factura_creada;
  $fecha = date('Y-m-d H:i:s');
  $sql =
    "INSERT INTO
  `logsistema` (`id`, `log`, `fechalog`, `operador`, `idcliente`, `tipolog`, `tipo`) VALUES
  (NULL,'$txt', '$fecha', '0', $idcliente, '0', 'facturas');";
  $result_create_log = $conn->query($sql);
  return $result_create_log;
}
function calculadora(String $impuesto, array $productos, array $planes, array $saldos, object $otros_impuestos_array, float $descuento)
{
  $total_items = 0;
  $total = 0;
  $subtotal = 0;
  $iva = 0;
  $total_otros_impuestos = 0;
  $otros_impuestos_txt = "";
  if ($impuesto == "NADA") {
    foreach ($productos as $producto) {
      $total_items += $producto['monto'];
      $subtotal += $producto['monto'];
    }
    foreach ($planes as $plan) {
      $total_items += $plan['costo'];
      $subtotal += $plan['costo'];
    }
    foreach ($saldos as $saldo) {
      $total_items += $saldo['monto'];
      $subtotal += $saldo['monto'];
    }
    foreach ($otros_impuestos_array as $val_impuesto) {
      $val_impuesto = intval($val_impuesto);
      $val_impuesto = ($val_impuesto * $total_items) / 100;
      $total_otros_impuestos += $val_impuesto;
      $otros_impuestos_txt .= $val_impuesto . ";";
    }
    $subtotal = $total_items;
  } else if ($impuesto == "SI") {
    foreach ($productos as $producto) {
      $total_items += $producto['monto'];
      $subtotal += $producto['monto'];
      $iva += ($producto['monto'] * $producto['impuesto']) / 100;
    }
    foreach ($planes as $plan) {
      $total_items += $plan['costo'];
      $subtotal += $plan['costo'];
      $iva += ($plan['costo'] * $plan['impuesto']) / 100;
    }
    foreach ($saldos as $saldo) {
      $total_items += $saldo['monto'];
      $subtotal += $saldo['monto'];
    }
    foreach ($otros_impuestos_array as $val_impuesto) {
      $val_impuesto = intval($val_impuesto);
      $val_impuesto = ($val_impuesto * ($total_items + $iva)) / 100;
      $total_otros_impuestos += $val_impuesto;
      $otros_impuestos_txt .= $val_impuesto . ";";
    }
  } else if ($impuesto == "NO") {
    foreach ($productos as $producto) {
      $total_items += $producto['monto'];
      $subtotal += ($producto['monto'] / (1 + ($producto['impuesto']) / 100));
      $iva += abs($subtotal - $producto['monto']);
    }
    foreach ($planes as $plan) {
      $total_items += $plan['costo'];
      $subtotal += ($plan['costo'] / (1 + ($plan['impuesto']) / 100));
      $iva += abs($subtotal - $plan['costo']);
    }
    foreach ($saldos as $saldo) {
      $total_items += $saldo['monto'];
      $subtotal += $saldo['monto'];
    }
    foreach ($otros_impuestos_array as $val_impuesto) {
      $val_impuesto = intval($val_impuesto);
      $val_impuesto = ($val_impuesto * $total_items) / 100;
      $total_otros_impuestos += $val_impuesto;
      $otros_impuestos_txt .= $val_impuesto . ";";
    }
  }
  $total = $subtotal + $iva + $total_otros_impuestos;
  if ($total < $descuento) {
    $descuento = $descuento - $total;
    $total = 0;
  } else {
    $total -= $descuento;
    $descuento = 0;
  }
  $data = [
    "total" => $total,
    'subtotal' => $subtotal,
    'iva' => $iva,
    'otros_impuestos_txt' => $otros_impuestos_txt,
    'estado' => $total <= 0 ? 'pagado' : 'No pagado',
    'nuevo_saldo' => $descuento
  ];
  return $data;
}
function new_saldo(String $idcliente, string $nuevo_saldo, string $id_factura_creada, object $conn)
{
  $fecha = date('Y-m-d');
  $txt = "Saldo a favor generado por la factura #" . $id_factura_creada;
  $sql = "INSERT INTO
         `saldos` (`id`, `iduser`, `idorigen`, `iddestino`, `estado`, `monto`, `fecha`, `descripcion`)
         VALUES
         (NULL, '$idcliente', '$id_factura_creada', '0', 'no cobrado', '-$nuevo_saldo', '$fecha', '$txt');";
  $result_new_saldo = $conn->query($sql);
  return $result_new_saldo;
}
