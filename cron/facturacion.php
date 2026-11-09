<?php
include("conexion.php");
include("decodificar.php");
include("funciones_facturacion.php");
date_default_timezone_set('America/Mexico_City');
$conn = conectar();
//id 223;
//$str_fecha_actual = strtotime(date('d-m-Y'));
$str_fecha_actual = strtotime('16-01-2021');
//$sql = "SELECT cliente,diapago,diafactura,otros_impuestos,impuesto,tipopago FROM `tblavisouser` WHERE id='258' LIMIT 1";
$sql = "SELECT t.cliente,t.diapago,t.diafactura,t.otros_impuestos,t.impuesto,t.tipopago FROM tblavisouser as t INNER JOIN usuarios2 as u ON u.id = t.cliente WHERE u.estado='ACTIVO'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  $clientes = get_clientes_que_facturan_hoy($result, $str_fecha_actual);
  foreach ($clientes as $config) {
    $idcliente = $config['idcliente'];
    $impuesto = $config['impuesto'];
    $tipo_pago = $config['tipo_pago'];
    $otros_impuestos = $config['otros_impuestos'];
    $factura_anterior_pagada = verificacion($idcliente, $conn);
    if ($factura_anterior_pagada) {
      $otros_impuestos_array = decodificar($otros_impuestos);
      $fechas = get_vencimiento($idcliente, $tipo_pago, $conn);
      if (!$fechas['primerafactura']) {
        $planes = get_planes($idcliente, $fechas, $conn);
        $productos = get_productos($idcliente, $conn);
        $saldos = get_saldos($idcliente, $conn);
        $descuento = get_descuentos($idcliente, $conn);
        $vencimiento = date('Y-m-d', strtotime($fechas['vencimiento']));
        $emitido = date('Y-m-d');
        $legal = get_legal($conn);
        //procesos
        $datos = calculadora($impuesto, $productos, $planes, $saldos, $otros_impuestos_array, $descuento);
        printf("DIA VENCIMIENTO:  $vencimiento --> IDcliente: $idcliente \n");
        //print_r($datos);
        $id_factura_creada = new_factura($legal, $idcliente, $emitido, $vencimiento, $datos['total'], $datos['estado'], $datos['iva'], $datos['subtotal'], $impuesto, $datos['otros_impuestos_txt'], $conn);
        //printf("--Se creo una factura con los sig. resultados: \n");

        if ($id_factura_creada) {
          if (count($productos) != 0) {
            update_tmp_facturas($id_factura_creada, $productos, $conn);
            //printf("--Los productos en tmpfactura del cliente $idcliente se cambiaron a estado FACTURADO \n");
          }
          $result_update_saldos = update_saldos($idcliente, $id_factura_creada, $conn);
          //printf("--Los saldos anteriores se cambiaron a estado facturado \n");
          if ($datos['nuevo_saldo'] > 0) {
            $result_new_saldo = new_saldo($idcliente, $datos['nuevo_saldo'], $id_factura_creada, $conn);
            //printf("--NOTA: Se creo un nuevo saldo a favor, por que el saldo anterior era mayor a el monto de la factura generada \n");
          }

          $result_new_items_productos = insert_factura_items_productos($id_factura_creada, $productos, $conn);
          $result_new_items_saldos = insert_factura_items_saldos($id_factura_creada, $saldos, $conn);
          $result_new_items_planes = insert_factura_items_planes($id_factura_creada, $planes, $conn);
          //printf("--Se insertaron los items en la tabla FACTURA_ITEMS: $id_factura_creada \n");
          $result_nuevo_log = new_log($id_factura_creada, $idcliente, $conn);
          //printf("--Se inserto un nuevo LOG \n");
        }
      }
    }
  }
}
