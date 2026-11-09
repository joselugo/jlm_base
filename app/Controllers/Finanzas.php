<?php

namespace App\Controllers;

use App\Libraries\CamposPlantilla;

require 'vendor/autoload.php';

use App\Models\Model_finanzas;
use App\Libraries\Class_seguridad;
use App\Libraries\Calcular;
use App\Models\Model_log;
use App\Models\Model_selectores;
use App\Models\Model_formatos;
use App\Models\Model_finanzas_facturacion;
use App\Libraries\Class_permiso_sucursal;
use App\Libraries\Class_cantidad_en_letras;
use App\Models\Model_almacen;
use PEAR2\Net\RouterOS;
use App\Models\Model_clientes;

class Finanzas extends BaseController
{
  public function __construct()
  {
    $this->model_facturacion = new Model_finanzas_facturacion();
    $this->almacen = new Model_almacen();
    $this->model_selectores = new Model_selectores();
    $this->model_log = new Model_log();
    $this->seguridad = new Class_seguridad();
    $this->permiso_sucursal = new Class_permiso_sucursal();
    $this->finanzas = new Model_finanzas();
    $this->formatos = new Model_formatos();
    $this->calcular = new Calcular();
    $this->letras = new Class_cantidad_en_letras();
    $this->model_clientes = new Model_clientes();
    date_default_timezone_set('America/Mexico_City');

    session();
  }
  //----------------------FACTURAS------------------------------

  public function facturas()
  {
    $permiso= $this->seguridad->Acceso('FACTURAS', 'LECTURA', $_SESSION['id_usuario8291']);
    $permiso2 = $this->seguridad->Acceso_void('REGISTRAR_PAGO', 'ELIMINAR', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $nodos = $this->model_selectores->get_all_nodos();
      $pasarelas = $this->model_selectores->get_all_pasarelas();
      $data['pasarelas'] = $pasarelas;
      $data['nodos'] = $nodos;
      $data['permiso'] = $permiso;
      $data['permiso2'] = $permiso2?1:2;
      $data['desde'] = date('d/m/Y', strtotime("first day of this month"));
      $data['hasta'] = date('t/m/Y');
      $data['fecha'] = date('Y-m-d');
      echo view('finanzas/facturas', $data);
    }
  }
  public function factura_pdf()
  {
    if ($this->seguridad->Acceso('FACTURAS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $idfactura = $this->request->getGet('fc');
      $nombre_formato = "finanzas_factura_pdf";
      $data = array();
      $all_items = $this->finanzas->consulta_factura_user($idfactura);
      $descuento = $this->finanzas->get_descuento_total_factura($idfactura);
      $descuento = abs($descuento ? $descuento[0]->smonto : 0);
      $html_factura = $this->formatos->get_formato_factura();
      $alloperaciones = $this->finanzas->get_operaciones($idfactura);



      if ($html_factura[0]->tamano != "") {
        $formato = $html_factura[0]->tamano;
      } else {
        $formato = [intval($html_factura[0]->ancho), intval($html_factura[0]->alto)];
      }
      $config = [

        'format' => $formato . $html_factura[0]->posicion
      ];
      $mpdf = new \Mpdf\Mpdf($config);
      $datos_empresa = $this->finanzas->get_datos_empresa($all_items[0]->idcliente);
      if ($all_items) {
        $impuesto1 = 0;
        $impuesto2 = 0;
        $impuesto3 = 0;
        if ($all_items[0]->otros_impuestos == "" or $all_items[0]->otros_impuestos == null or $all_items[0]->otros_impuestos == "0;0;0") $all_items[0]->otros_impuestos = "0;0;0;";
        $otros_impuestos = explode(";", $all_items[0]->otros_impuestos, -1);
        $impuesto1 = $otros_impuestos[0];
        $impuesto2 = $otros_impuestos[1];
        $impuesto3 = $otros_impuestos[2];

        if ($all_items[0]->estado == "No pagado") $estadocss = "nopagado";
        if ($all_items[0]->estado == "pagado") $estadocss = "pagado";
        if ($all_items[0]->estado == "anulado") $estadocss = "anulado";
        $items = "";
        $subtotal = 0;
        $impuesto = 0;
        foreach ($all_items as $item) {
          $items .= "
          <tr>
            <td>$item->idescripcion</td>
            <td>MX$".number_format($item->cantidad,2)."</td>
            <td>$item->iimpuesto</td>
            <td>$item->unidades</td>
            <td>MX$".number_format($item->cantidad,2)."</td>
          </tr>";
        }
        $data = $this->calcular->calculadora($all_items[0]->str_impuesto, $all_items, $otros_impuestos, $descuento);
        $operaciones = "";
        $suma_t = 0;
        if ($alloperaciones) {
          foreach ($alloperaciones as $value) {
            $suma_t += $value->cobrado;
            $operaciones .=
              "<tr>
                          <td >" . date('Y-m-d H:i A', strtotime($value->fecha_pago)) . "</td>
                          <td>" . $value->forma_pago . "</td>
                          <td>MX$" . number_format($value->cobrado,2) . "</td>
                        </tr>";
          }
          $operaciones =
            "<div class=\"invoice-content\" style=\"margin-top:10px\">
                        <table autosize=\"2\" class=\"table-invoice\">
                        <thead>
                        <tr>
                          <th COLSPAN=3 width=\"100px\">Transacciones</th>
                          </tr>
                      </thead>
                      <tbody>
                       " . $operaciones . "
                       <tr>

                          <td COLSPAN=\"2\" style=\"text-aling:right;background-color: #dcdcdc;\">Nuevo balance:</>
                          <td style=\"text-aling:right;background-color: #8484840d;\">MX$" . number_format(($data['total'] - $suma_t),2) . "</td>
                        </tr>
                      </tbody>
                      </table>
                  </div>";
        }
        $data = [
          "images/logofactura.png" =>"/". $datos_empresa->logo_facturas_recibos,
          "{url}" => $datos_empresa->logo_facturas_recibos, // plantillas por sucursal usan {url}

          "{estado}" => $all_items[0]->estado,
          "estadocss" => $estadocss,
          "{nfactura}" => $all_items[0]->id,
          "{emitido}" => $all_items[0]->emitido,
          "{vencimiento}" => $all_items[0]->vencimiento."<br>",

          "{nombre_empresa}" => $datos_empresa->alias,
          "{ruc_empresa}" =>   $datos_empresa->rfc,
          "{direccion_empresa}" =>   $datos_empresa->direccion,
          "{telefono_empresa}" =>   $datos_empresa->telefono,


          "{nombre_cliente}" => $all_items[0]->nombre,
          "{cedula_cliente}" => $all_items[0]->cedula,
          "{direccion_principal_cliente}" => $all_items[0]->direccion_principal,
          "{telefono_cliente}" => $all_items[0]->telefono,
          "{movil_cliente}" => $all_items[0]->movil,

          "<td>{items}</td>" => $items,
          "{total_letras}" => number_format(round($data['total'], 2),2)." - (".$this->letras->ValorEnLetras(round($data['total'], 2),"MX").")",

          "{barcode}" => $all_items[0]->idcliente,
          "{subtotal}" => "MX$" . number_format(round($data['subtotal'], 2),2),
          "{impuesto}" => "MX$" . number_format(round($data['iva'], 2),2),
          "{otro_impuesto_1}" => "MX$" . number_format(round($impuesto1, 2),2),
          "{otro_impuesto_2}" => "MX$" . number_format(round($impuesto2, 2),2),
          "{otro_impuesto_3}" => "MX$" . number_format(round($impuesto3, 2),2),
          "{descuento}" =>  "MX$" . number_format($data['nuevo_descuento'],2),
          "{total}" => "MX$" . number_format(round($data['total'], 2),2),

          "<p>{operaciones}</p>" => $operaciones,
        ];
        $html = $html_factura[0]->html;
        $html = html_entity_decode($html);
        $data += CamposPlantilla::valores($html, ($datos_empresa ?? null) ?: null); // {sucursal_*}, {config_*}
        $html = strtr($html, $data);
        $mpdf->WriteHTML($html);
        $mpdf->Output("./files/pdfs/" . $nombre_formato . ".pdf", "F"); // opens in browser
        $this->show($nombre_formato . ".pdf");
      } else {
        return 'error';
      }
    }
  }



  public function show($file)
  {

    if (is_dir("./files/pdfs/")) {
      $path = "./files/pdfs/" . $file;
      if (file_exists("./files/pdfs/" . $file)) {

        header("Content-Length: " . filesize($path));
        header("Content-type: application/pdf");
        header("Content-disposition: inline; filename=" . basename($path));
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        ob_clean();
        flush();
        readfile($path);
      }
    }
  }


  public function get_facturas()
  {
    $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal($_SESSION['sucursales']);
    $acceso = $this->seguridad->Acceso('FACTURAS', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($acceso) {
      $action = $this->request->getGet('action');
      $state = $this->request->getGet('state');
      $from = $this->request->getGet('from');
      $to = $this->request->getGet('to');
      $nodo = $this->request->getGet('nodo');
      $action = $this->request->getGet('action');
      $to = $this->fecha_simple($to);
      $from = $this->fecha_simple($from);
      //transformar a fecha
      $all_items = $this->finanzas->get_lista_facturas($from, $to, $nodo, $state, $permiso_sucursal);
      foreach ($all_items as $item) $item->tool = $acceso;
      $data['data'] = $all_items;
      echo json_encode($data);
    }
  }
  public function consulta_items_factura()
  {
    $acceso = $this->seguridad->Acceso('FACTURAS', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($acceso) {
      $idfactura = $this->request->getGet('id');
      $all_items = $this->finanzas->get_items_factura($idfactura);
      $saldo = $this->finanzas->get_descuento_total_factura($idfactura);
      /*$ajuste_descuento = $this->finanzas->get_descuento_saldo($idfactura);
      if ($ajuste_descuento) {
        $saldo[0]->smonto = $saldo[0]->smonto - $ajuste_descuento[0]->monto;
      }*/
      if ($all_items) {
        $all_items[0]->otros_impuestos == "" ? $all_items[0]->otros_impuestos = "0;0;0;" : null;
       // $all_items[0]->monto = $saldo ? $saldo[0]->smonto : 0;
        $all_items[0]->permiso = $acceso;
        echo $all_items ? json_encode($all_items) : "error";
      } else {
        echo  "error";
      }
    }
  }

  public function popover_productos_disponibles()
  {
    $acceso = $this->seguridad->Acceso('FACTURAS', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($acceso) {
      $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal_almacen($_SESSION['sucursales']);
      $idproducto = $this->request->getPost('id');
      $all_items = $this->finanzas->get_serialproductos($idproducto, $permiso_sucursal);
      echo $all_items ? json_encode($all_items) : "error";
    }
  }
  public function viewuser()
  {
    $action = $this->request->getPost('action');
    if ($action == "") {
      $action = $this->request->getGet('action');
    }
    if ($action == "saveedittransaccion") {
      $permiso_r = $this->seguridad->Acceso('TRANSACCIONES', 'ESCRITURA', $_SESSION['id_usuario8291']);
      if ($permiso_r){
        $idoperacion = $this->request->getPost('operacion[id]');
        $idfactura = $this->request->getPost('nfactura');
        $cobrado = $this->request->getPost('cobrado');
        $comision = $this->request->getPost('operacion[comision]');
        $descripcion = $this->request->getPost('operacion[descripcion]');
        $transaccion = $this->request->getPost('operacion[transaccion]');
        $fecha_pago = $this->request->getPost('operacion[fecha_pago]');
        $descripcion_pago = $this->request->getPost('operacion[descripcion_pago]');
        $forma_pago = $this->request->getPost('operacion[forma_pago]');
        $idcliente = $this->request->getPost('operacion[idcliente]');
        $data_operacion = [
          "comision" => $comision,
          "descripcion" => $descripcion,
          "transaccion" => $transaccion,
          "fecha_pago" => $this->fecha_con_hora($fecha_pago),
          "descripcion_pago" => $descripcion_pago,
          "forma_pago" => $forma_pago
        ];
        $data_factura = [
          "pago" => $this->fecha_simple($fecha_pago),
          "forma" => $forma_pago
        ];
        $result = $this->finanzas->edit_operacion($idoperacion, $data_operacion);
        $result2 = $this->finanzas->edit_factura($idfactura, $data_factura);
        if ($result2 or $result) {
          $nombre_cliente = $this->finanzas->get_nombre_cliente($idcliente);
          $txt_log = "Editar Transacción (" . $nombre_cliente[0]->nombre . ") monto: MX$$cobrado - Operación ID: $idoperacion";
          $insert_log = $this->model_log->insert_log($idcliente, "facturas", $txt_log, $_SESSION['id_usuario8291']);
          echo $insert_log ?  1 : 2;
        } else {
          echo 2;
        }
      }
    }
    //----------------eliminar factura----------------
    if ($action == "edittransaccion") {
      $permiso_r = $this->seguridad->Acceso('FACTURAS', 'LECTURA', $_SESSION['id_usuario8291']);
      if ($permiso_r) {
        $origen = $this->request->getGET('origen');

        if ($origen == "pagos") {
          $idoperacion = $this->request->getGET('id');
          $data = $this->finanzas->consult_edittransaccion_por_operacion($idoperacion);
          $options_pasarelas = $this->model_selectores->get_all_pasarelas();
          if ($data) {
            $datos['data'] = $data[0];
            $datos['options_pasarelas'] = $options_pasarelas;
            $datos['permiso'] = $permiso_r;
            return view('finanzas/utils/form_editar_pago', $datos);
          } else {
            echo "<script>alerta('warning','Esta factura no cuenta con ninguna transacción registrada.');</script>";
          }
        } else {
          $idfactura = $this->request->getGET('idfc');
          $data = $this->finanzas->consult_edittransaccion($idfactura);
          if ($data[0]->operacionesid == null) {
            return "ninguna operacion";
          } else {
            $data[0]->permiso = $permiso_r;
            return $data ? json_encode($data) : "error";
          }
        }
      }
    }
    if ($action == "eliminar_factura") {
      if ($this->seguridad->Acceso('CREAR_Y_ELIMINAR_FACTURAS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
        $idfactura = $this->request->getPost('id');
        $log_data = $this->finanzas->get_id_operacion($idfactura);

        $all_items = $this->finanzas->get_ids_almacen($idfactura);
        $items_almacen = false;
        //eliminar saldos generados por la factura a eliminar.
        $result_delete = $this->finanzas->delete_factura_no_cobrados($idfactura);

        if ($all_items) {
          foreach ($all_items as $item) {
            if ($item->idalmacen != 0) {
              $data_tmp = [
                "state" => "pendiente",
                "nfactura" => 0
              ];
              $result_tmp = $this->finanzas->update_tmp($item->idfactura, $item->idalmacen, $data_tmp);
              if (!$result_tmp) {
                $items_almacen = true;
                $data_almacen[] = [
                  "id" => $item->idalmacen,
                  "estado" => "disponible",
                  "userid" => 0
                ];
              }
            }
          }
          $items_almacen ? $this->model_facturacion->update_batch_almacen($data_almacen, 'id') : null;
        }
        $data_saldo = [
          "iddestino" => 0,
          "estado" => "no cobrado"
        ];
        $result1 = $this->finanzas->edit_saldo($idfactura, $data_saldo);
        $result2 = $this->finanzas->eliminar_factura_items($idfactura);
        $result3 = $this->finanzas->eliminar_factura($idfactura);
        if ($result1 or $result2 or $result3) {
          $txt_log = "Factura eliminada (" . $log_data[0]->nombre . ") - Factura ID: " . $idfactura;
          $insert_log = $this->model_log->insert_log($log_data[0]->idcliente, "facturas", $txt_log, $_SESSION['id_usuario8291']);
          echo  "1";
        } else {
          echo "2";
        }
      }
    }
    if ($action == "deletetransaccion") {
      $permiso_delete= $this->seguridad->Acceso_full('REGISTRAR_PAGO', 'ELIMINAR', $_SESSION['id_usuario8291']);
      if ($permiso_delete)
      {
        $idfactura = $this->request->getPost('factura');
        $log_data = $this->finanzas->get_id_operacion($idfactura);
  
        $data_factura = [
          "forma" => "",
          "estado" => "No pagado",
          "pago" => "0000-00-00",
          "cobrado" => 0,
        ];
        $result_operacion = $this->finanzas->delete_operacion($idfactura);
        $result_operacion ? $this->finanzas->delete_saldo($idfactura) : null;
  
        $result = $this->finanzas->edit_factura($idfactura, $data_factura);
        if ($result) {
          $txt_log = "Transaccion eliminada (" . $log_data[0]->nombre . ") - Factura ID:$idfactura - Operacion ID:" . $log_data[0]->idcliente;
          $insert_log = $this->model_log->insert_log($log_data[0]->idcliente, "facturas", $txt_log, $_SESSION['id_usuario8291']);
        }
        echo $result ? "1" : "2";
        return;
      }
    }
    if ($this->seguridad->Acceso('FACTURAS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $action = $this->request->getPost('action');
      if ($action == "") {
        $action = $this->request->getGet('action');
      }
      //-----------------pagar----------------------
      if ($action == "paidinvoice") {
        $id = $this->request->getGet('id');
        $datos = $this->finanzas->get_factura($id);
        $data['datos'] = $datos[0];
        $data['pasarelas'] = $this->model_selectores->get_all_pasarelas();
        return view('finanzas/utils/form_new_pago', $data);
      }

      //----------------Eliminar--------------------

      //----------------Editar--------------------


      //----------------Anular--------------------
      if ($action == "cancelinvoice") {
        $idfactura = $this->request->getPost('id');
        $log_data = $this->finanzas->get_id_operacion($idfactura);
        $items_almacen = false;
        $data_factura = [
          "estado" => "anulado"
        ];
        $result = $this->finanzas->edit_factura($idfactura, $data_factura);
        $all_items = $this->finanzas->get_ids_almacen($idfactura);

        if ($all_items) {
          foreach ($all_items as $item) {
            if ($item->idalmacen != 0) {
              $data_tmp = [
                "state" => "pendiente",
                "nfactura" => 0
              ];
              $result_tmp = $this->finanzas->update_tmp($item->idfactura, $item->idalmacen, $data_tmp);
              if (!$result_tmp) {
                $items_almacen = true;
                $data_almacen[] = [
                  "id" => $item->idalmacen,
                  "estado" => "disponible",
                  "userid" => 0
                ];
              }
            }
          }
          $items_almacen ? $this->model_facturacion->update_batch_almacen($data_almacen, 'id') : null;
        }
        $data_saldo = [
          "iddestino" => 0,
          "estado" => "no cobrado"
        ];
        $result_edit_saldo = $this->finanzas->edit_saldo($idfactura, $data_saldo);
        //$result_delete_factura_items = $this->finanzas->eliminar_factura_items($idfactura);
        $txt_log = "Factura anulada (" . $log_data[0]->nombre . ") - Factura ID: " . $idfactura;
        $insert_log = $this->model_log->insert_log($log_data[0]->idcliente, "facturas", $txt_log, $_SESSION['id_usuario8291']);
        echo $result ? "1" : "2";
      }
      //----------------Editar--------------------

      //--------------nueva transaccion-----------------
      if ($action == "addtransaccion") {
        $idcliente = $this->request->getPost('operacion[idcliente]');
        $idfactura = $this->request->getPost('operacion[nfactura]');
        $cobrado = $this->request->getPost('factura[cobrado]');
        $fiscal = $this->request->getPost('factura[legal]');
        $comision = $this->request->getPost('operacion[comision]');
        $descripcion = $this->request->getPost('operacion[descripcion]');
        $transaccion = $this->request->getPost('operacion[transaccion]');
        $descripcion_pago = $this->request->getPost('operacion[descripcion_pagos]');
        if (!$descripcion_pago) {
          $descripcion_pago = "";
        }
        $promesa_pago = $this->finanzas->get_promesas_cliente($idcliente);
        if ($promesa_pago) {
          $this->finanzas->delete_promesa($idcliente);
        }
        $forma_pago = $this->request->getPost('operacion[forma_pagoss]');
        $total_factura = $this->finanzas->consulta_de_total_factura($idfactura);
        $nuevo_saldo = $total_factura[0]->total - $cobrado;
        //agregando saldo si es necesario.
        if ($nuevo_saldo != 0) {
          $data_saldo = [
            'iduser' => $idcliente,
            'idorigen'  => $idfactura,
            'estado'  => 'no cobrado',
            'monto'  => $nuevo_saldo,
            'fecha'  => date('Y-m-d'),
            'descripcion'  => 'Saldo generado Automático'
          ];

          $result_saldo = $this->finanzas->insert_nuevo_saldo($data_saldo);
        }
        $data_operacion = [
          "nfactura" => $idfactura,
          "idcliente" => $idcliente,
          "fecha_pago" => date('Y-m-d H:i:s'),
          "operador" => $_SESSION['id_usuario8291'],
          "transaccion" => $transaccion,
          "meses" => 1,
          "forma_pago" => $forma_pago,
          "descripcion" => $descripcion,
          "cobrado" => $cobrado,
          "comision" => $comision,
          "saldo" => $nuevo_saldo,
          "descripcion_pago" => $descripcion_pago,
          "folio" => 0,
        ];
        $data_factura = [
          "legal" => $fiscal,
          "pago" => date('Y-m-d'),
          "forma" => $forma_pago,
          "estado" => "pagado",
          "cobrado" => $cobrado

        ];
        $result_operacion = $this->finanzas->insert_nuevo_operacion($data_operacion);
        if ($result_operacion) {
          $result_factura = $this->finanzas->edit_factura($idfactura, $data_factura);
          if ($result_factura) {
            $data_servicios = $this->finanzas->get_data_servicios($idcliente);
            if ($data_servicios) {
              foreach ($data_servicios as $value) {
                $client = true;
                try {
                  $client = new RouterOS\Client($value->ip, $value->usuario, $value->contrasena, $value->puerto);
                } catch (\Throwable $th) {
                  $client = false;
                }
                if ($client) {
                  $comentario = "Corte_Servicio_" . intval($value->id);
                  $data['commet'] = $comentario;
                  $util = new RouterOS\Util($client);
                  $util->setMenu('/ip firewall address-list');
                  $util->remove($util->find(RouterOS\Query::where('comment', $comentario)));
                }
              }
            }
            $data_up = ["estado" => 'ACTIVO'];
            $result_update_estado = $this->model_clientes->update_cliente($idcliente, $data_up);
            $txt_log = "Factura de servicio pagada (Total cobrado: MX$$cobrado) - Factura ID: $idfactura";
            $insert_log = $this->model_log->insert_log($idcliente, "facturas", $txt_log, $_SESSION['id_usuario8291']);
            echo 1;
          } else {
            //eliminar transaccion.
            $this->finanzas->delete_operacion_por_id($result_operacion);
            echo 2;
          }
        } else {
          echo 2;
        }
      }
      //--------------------guardar cambios de factura---------------------
      if ($action == "saveeditinvoice") {
        $f_vencimiento = $this->request->getPost('factura[vencimiento]');
        $f_total = $this->request->getPost('factura[total]');
        $f_emitido = $this->request->getPost('factura[emitido]');
        $f_vencimiento = str_replace("/", "-", $f_vencimiento);
        $f_emitido = str_replace("/", "-", $f_emitido);
        $iditems = $this->request->getPost('iditem[]');
        $idfactura = $this->request->getPost('idfacturamodal');
        $idproductos = $this->request->getPost('idproducto[]');
        $descripciones = $this->request->getPost('descripcion[]');
        $costos = $this->request->getPost('costo[]');
        $impuestos = $this->request->getPost('impuesto[]');
        $unidades = $this->request->getPost('unidad[]');
        $claves_invoice = $this->request->getPost('clave_invoice[]');
        $items_existentes = $this->finanzas->get_items_factura_edit($idfactura);
        $log_data = $this->finanzas->get_id_operacion($idfactura);
        //consulta existencia. si existe lo edita, si no existe lo elimina
        //falta eliminar en el almacen
        $total = 0;

        foreach ($iditems as $key => $item) {
          $data = [];
          $existencia = false;
          foreach ($items_existentes as  $existente) {
            if ($item == $existente->id) {
              $existencia = true;
            }
          }
          $data = [
            "idfactura" => $idfactura,
            "descripcion" => $descripciones[$key],
            "cantidad" => $costos[$key],
            "idalmacen" => $idproductos[$key],
            "unidades" => $unidades[$key],
            "impuesto" => $impuestos[$key],
            "clave_invoice" => $claves_invoice[$key]

          ];
          if ($existencia) {
            $result_edit_item = $this->finanzas->edit_items_factura($item, $data);
          } else {
            $result_insert_item = $this->finanzas->insert_items_factura($data);
          }
        }

        foreach ($items_existentes as $exis) {
          $existencia = false;
          foreach ($iditems as $item) {
            if ($exis->id == $item) {
              $existencia = true;
            }
          }
          if (!$existencia) {
            if ($exis->idalmacen <> 0) {
              $data_tmp = [
                "state" => "pendiente",
                "nfactura" => 0
              ];
              $result_update_tmp = $this->finanzas->update_tmp($exis->idfactura, $exis->idalmacen, $data_tmp);
              if (!$result_update_tmp) {
                $data_almacen = [
                  "userid" => 0,
                  "fecha_salida" => "0000-00-00",
                  "estado" => "disponible"
                ];
                $this->almacen->update_producto($exis->idalmacen, $data_almacen);
              }
            }
            //cambiar a disponibles
            $result_eliminar_item = $this->finanzas->eliminar_items_factura($exis->id);
          }
        }

        $items_existentes = $this->finanzas->get_items_factura_edit($idfactura);
        $saldos_n = $this->finanzas->get_saldos_ndos($idfactura);
        if ($items_existentes) {
          foreach ($items_existentes as $item) {
            $total += $item->cantidad;
          }
        }
        $total = $total + ($saldos_n ? $saldos_n->monto : 0);
        $data_factura = [
          'vencimiento' => date('Y-m-d', strtotime($f_vencimiento)),
          'emitido' => date('Y-m-d', strtotime($f_emitido)),
          'total' => $f_total,
          'estado' => $total == 0 ? 'pagado' : 'No pagado'
        ];
        $result_edit_factura = $this->finanzas->edit_factura($idfactura, $data_factura);
        if ($result_edit_factura) {
          $txt_log = "Factura Editada (" . $log_data[0]->nombre . ") - Factura ID:" . $idfactura;
          $insert_log = $this->model_log->insert_log($log_data[0]->idcliente, "facturas", $txt_log, $_SESSION['id_usuario8291']);
          echo "1";
        } else {
          echo "1";
        }
      }
    }
  }
  public function listadeproductos()
  {
    $acceso = $this->seguridad->Acceso('FACTURAS', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($acceso) {
      $productos = $this->finanzas->get_productos();
      $otros = $this->finanzas->get_otros();

      $options_productos = "";
      $options_otro = "";
      foreach ($productos as $producto) {
        $options_productos .= "<option data-des=\"$producto->descripcion\" data-tipo=\"$producto->tipo\" data-costo=\"$producto->costo\" data-codigo=\"$producto->clave_invoice\" data-impuesto=\"$producto->impuesto\" value=\"$producto->id\">$producto->producto</option>";
      }
      foreach ($otros as $otro) {
        $options_otro .= "<option data-des=$otro->descripcion data-tipo=$otro->tipo data-costo=$otro->costo data-codigo=\"$producto->clave_invoice\" data-impuesto=$otro->impuesto value=$otro->id >$otro->producto</option>";
      }
      echo "<div class=\"p\">
            <div class=\"col-sm-12\" data-select2-id=\"98\">
              <div class=\"form-group input-group-sm\">
                <label style=\"font-size: 12px;\">Categoria producto</label>
                <select onchange='selectproducto(this)' class=\"slp form-control select2-hidden-accessible\" id=\"productoid\" name=\"productoid\" style=\"width:100%\" data-select2-id=\"productoid\" tabindex=\"-1\" aria-hidden=\"true\">
                  <option data-select2-id=\"94\"></option>
                  <optgroup label=\"Productos\">"
        . $options_productos .
        "</optgroup>
                  <optgroup label=\"Otros Productos y Servicios\">
                  " . $options_otro . "
                  </optgroup>
                </select>
              </div>
            </div>
            <div class=\"col-sm-12 hideproduct\" style=\"display:none\">
              <div class=\"form-group input-group-sm\">
                <label style=\"font-size: 12px;\">Producto</label>
                <select onchange='habilitar_boton()' class=\"form-control slp select2-hidden-accessible\" id=\"almacenid\" name=\"almacenid\" style=\"width:100%\" data-select2-id=\"almacenid\" tabindex=\"-1\" aria-hidden=\"true\">
                  <option data-select2-id=\"96\"></option>
                </select>
              </div>
            </div>
            <div class=\"col-sm-12\">
              <div class=\"form-group input-group-sm\">
                <input onclick='agregar_item(this)' type=\"button\" class=\"btn btn-sm btn-white btnaddprod\" value=\"Agregar producto\" disabled=\"\">
              </div>
            </div>
          </div>
          
        <script>
        

          $('.slp').select2({
            placeholder: 'Seleccionar...'
          });

          
        </script>
          ";
    }
  }
  //-------------------Registrar pago------------------------
  public function registrar_pago()
  {
    $acceso = $this->seguridad->Acceso('REGISTRAR_PAGO', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($acceso) {
      return view('finanzas/registrar_pago');
    }
  }
  public function agregar_pago_listapagos()
  {
    $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal($_SESSION['sucursales']);
    $acceso = $this->seguridad->Acceso('REGISTRAR_PAGO', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($acceso) {
      $permiso_sucursa_nodo = $this->permiso_sucursal->get_permiso_sucursal_nodo($_SESSION['sucursales']);
      $options_pasarelas = $this->model_selectores->get_all_pasarelas();
   //   $nodos = $this->model_selectores->get_all_nodos_by_usuario($permiso_sucursa_nodo);
      $options_operadores = $this->model_selectores->get_all_operadores();
      $data['fecha_hoy'] = date("d/m/Y");
      $data['options_pasarelas'] = $options_pasarelas;
      $data['options_operadores'] = $options_operadores;
     // $data['options_nodos'] = $nodos;
      return view('finanzas/utils/nav_pagos_registrados_hoy', $data);
    }
  }
  public function buscar()
  {
    $acceso = $this->seguridad->Acceso('REGISTRAR_PAGO', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($acceso) {
      $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal($_SESSION['sucursales']);
      $term = $this->request->getGet('term');
      $all_items = $this->finanzas->buscar_cliente($term, $permiso_sucursal);
      $datos = array();
      foreach ($all_items as $item) {
        $data['nombre'] = $item->nombre;
        $data['cedula'] = $item->cedula;
        $data['nodo'] = $item->nodo;
        $data['id'] = $item->id;
        $datos[] = $data;
      }
      echo json_encode($datos);
    }
  }
  public function addpago()
  {
    $acceso = $this->seguridad->Acceso('REGISTRAR_PAGO', 'ESCRITURA', $_SESSION['id_usuario8291']);
    if ($acceso) {
      $action = $this->request->getPost('action');
      if (!$action) {
        $action = $this->request->getGet('action');
      }
      if ($action == "getinvoice") {
        $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal($_SESSION['sucursales']);
        $idfactura = 0;
        $idcliente = $this->request->getPost('id');
        $data['select'] = "";
        if ($idcliente == "") {
          $idfactura = $this->request->getPost('code');
          $search_id_cliente = $this->finanzas->get_idcliente($idfactura);
          if ($search_id_cliente) {
            $idcliente = $search_id_cliente[0]->idcliente;
          } else {
            echo "<script>alerta('warning','No existe esa factura o pertenece a otra sucursal')</script>";
            return null;
          }
        }
        $all_items = $this->finanzas->get_all_facturas_cliente($idcliente, $permiso_sucursal);
        $saldos = $this->finanzas->get_all_saldos_cliente($idcliente);
        $select_pasarelas = $this->model_selectores->get_all_pasarelas();
        $promesa_pago = $this->finanzas->get_promesas_cliente($idcliente);
        $dia_pago = $this->model_facturacion->get_dia_pago($idcliente);
        $data_cliente = $this->model_facturacion->get_data_cliente($idcliente);
        $data['diapago'] = $dia_pago->diapago;
        $select_factura = "";
        $select_saldos = "";
        if (count($all_items) <> 0 or count($saldos) <> 0) {
          $sumatotal = 0;
          foreach ($all_items as $item) {
            $sumatotal += $item->total;
            if ($item->idfactura == $idfactura) {
              if ($item->tipo == 2) $select_factura .= "<option data-tipo=\"$item->tipo\"  selected=\"selected\" data-legal=\"$item->legal\" data-token=\"c2tnSDVGQ3dDMXk1cXVxY1BMUDVDdz09\" value=$item->idfactura data-total=\"$item->total\">Nº $item->idfactura - (MX$$item->total Factura de Servicios - $item->emitido)</option>";
              if ($item->tipo == 1) $select_factura .= "<option data-tipo=\"$item->tipo\"  selected=\"selected\" data-legal=\"$item->legal\" data-token=\"c2tnSDVGQ3dDMXk1cXVxY1BMUDVDdz09\" value=$item->idfactura data-total=\"$item->total\">Nº $item->idfactura -(MX$$item->total)</option>";
            } else {
              if ($item->tipo == 2) $select_factura .= "<option data-tipo=\"$item->tipo\" data-legal=\"$item->legal\" data-token=\"c2tnSDVGQ3dDMXk1cXVxY1BMUDVDdz09\" value=$item->idfactura data-total=\"$item->total\">Nº $item->idfactura - (MX$$item->total Factura de Servicios - $item->emitido)</option>";
              if ($item->tipo == 1) $select_factura .= "<option data-tipo=\"$item->tipo\" data-legal=\"$item->legal\" data-token=\"c2tnSDVGQ3dDMXk1cXVxY1BMUDVDdz09\" value=$item->idfactura data-total=\"$item->total\">Nº $item->idfactura -(MX$$item->total)</option>";
            }
          }
          foreach ($saldos as $saldo) {
            $select_saldos .= "<option data-detalle=\"Pago del saldo anterior - Factura Nº $saldo->idorigen\" data-total=\"$saldo->monto\" data-tipo=\"0\" value=\"$saldo->id\" >Saldo Factura Nº $saldo->id - (MX$" . round($saldo->monto, 2) . ")</option>";
          }
          $data['select_facturas'] = $select_factura;
          $data['select_pasarelas'] = $select_pasarelas;
          $data['sumatotal'] = $sumatotal;
          $data['select_saldos'] = $select_saldos;
          $data['num_facturas'] = count($all_items);
          $data['idcliente'] = $data_cliente->idcliente;
          $data['nombre'] = $data_cliente->nombre;
          $data['estado'] = $data_cliente->estado;
          $data['promesa_pago'] = $promesa_pago ? $promesa_pago[0]->id : "";

          echo view('finanzas/utils/nav_addpago', $data);
        } else {
          echo null;
        }
      }
      if ($action == "newpago") {
        $fecha_actual = date('Ymd');
        $radio_css_inline = $this->request->getPost('radio_css_inline');
        //$p_cliente = $this->request->getPost('p-cliente');
        $tipopago = $this->request->getPost('tipopago');
        $validpromesa = $this->request->getPost('validpromesa');
        $idcliente = $this->request->getPost('id-cliente');
        $idfactura = $this->request->getPost('p-facturas');
        $comision = $this->request->getPost('p-comision');
        $transaccion = $this->request->getPost('p-transaccion');
        $fiscal = $this->request->getPost('p-nlegal');
        $forma_pago = $this->request->getPost('p-forma');
        $descripcion_pago = $this->request->getPost('p-forma-desc');
        if (!$descripcion_pago) {
          $descripcion_pago = "";
        }
        $fecha_limite = $this->request->getPost('fechalimite');
        $p_mesespagar = $this->request->getPost('p-mesespagar');
        $p_dia = $this->request->getPost('p-dia');
        $cobrado = $this->request->getPost('p-total');
        $total2 = $this->request->getPost('total2');
        $nuevo_saldo = $this->request->getPost('saldos');
        $descripcion = $this->request->getPost('p-notas');
        $radio_print = $this->request->getPost('radio_print');
        $p_tipofc = $this->request->getPost('p-tipofc');
        $total_factura = $this->finanzas->consulta_de_total_factura_o_saldo($idfactura, $p_tipofc);
        if ($total_factura) {
          if ($total_factura->estado == "pagado") {
            $data['estado'] = "error";
            $data['salida'] = "La factura ya esta pagada";
            return json_encode($data);
          }
        }
        $promesa_pago = $this->finanzas->get_promesas_cliente($idcliente);
        $nuevo_saldo = $total_factura->total - $cobrado;
        $dia_pago_t = $this->model_facturacion->get_dia_pago($idcliente);
        if ($dia_pago_t->diapago != $p_dia) {
          $update_day_pago = $this->model_facturacion->update_dia_pago($idcliente, ["diapago" => $p_dia]);
          if ($update_day_pago) {
            $txt_log = "Dia de pago cambiado al pagar una factura (de $dia_pago_t->diapago al $p_dia) - Factura ID: $idfactura";
            $insert_log = $this->model_log->insert_log($idcliente, "configuracion", $txt_log, $_SESSION['id_usuario8291']);
          }
        }
        if ($promesa_pago) {
          $this->finanzas->delete_promesa($idcliente);
        }
        if ($tipopago <= 2) {
          if ($nuevo_saldo != 0) {
            $data_saldo = [
              'iduser' => $idcliente,
              'idorigen'  => $idfactura,
              'estado'  => 'no cobrado',
              'monto'  => $nuevo_saldo,
              'fecha'  => $fecha_actual,
              'descripcion'  => 'Saldo generado Automático'
            ];
            $result_saldo = $this->finanzas->insert_nuevo_saldo($data_saldo);
          }
          $data_operacion = [
            "nfactura" => $p_tipofc == 0 ? $total_factura->idorigen : $idfactura,
            "idcliente" => $idcliente,
            "fecha_pago" => date("Y-m-d H:i:s"),
            "operador" => $_SESSION['id_usuario8291'],
            "transaccion" => $transaccion,
            "meses" => 1,
            "forma_pago" => $forma_pago,
            "descripcion" => $descripcion,
            "cobrado" => $cobrado,
            "comision" => $comision,
            "saldo" => $nuevo_saldo,
            "descripcion_pago" => $descripcion_pago,
            "folio" => 0,
          ];
          $data_factura = [
            "legal" => $fiscal,
            "pago" => $fecha_actual,
            "forma" => $forma_pago,
            "estado" => "pagado",
            "cobrado" => $cobrado

          ];
          $data_saldo = [
            'estado' => 'pagado'
          ];
          $result_operacion = $this->finanzas->insert_nuevo_operacion($data_operacion);
          if ($result_operacion) {
            if ($p_tipofc == 0) {
              $result_actualizacion = $this->finanzas->edit_saldo_pagar($idfactura, $data_saldo);
              $this->finanzas->edit_factura_saldo($total_factura->idorigen, $cobrado);
            } else {
              $result_actualizacion = $this->finanzas->edit_factura($idfactura, $data_factura);
            }
            $newid = $p_tipofc == 0 ? $total_factura->idorigen : $idfactura;
            if ($radio_print == "recibo") $data['printerurl'] = "finanzas/transacciones?action=printer&id=$newid";
            if ($radio_print == "recibopos") $data['printerurl'] = "finanzas/transacciones?action=printer&id=$newid&d=.pdf";
            if ($radio_print == "factura") $data['printerurl']  = "finanzas/transacciones?action=printer&id=$newid&d=.pdf";
            $data['printertipo'] = $radio_print;
            $data['estado'] = "OK";
            if ($result_actualizacion) {
              if ($tipopago == 1) {

                $data_servicios = $this->finanzas->get_data_servicios($idcliente);
                if ($data_servicios) {
                  foreach ($data_servicios as $value) {
                    $client = true;
                    try {
                      $client = new RouterOS\Client($value->ip, $value->usuario, $value->contrasena, $value->puerto);
                    } catch (\Throwable $th) {
                      $client = false;
                    }
                    if ($client) {
                      $comentario = "Corte_Servicio_" . intval($value->id);
                      $data['commet'] = $comentario;
                      $util = new RouterOS\Util($client);
                      $util->setMenu('/ip firewall address-list');
                      $util->remove($util->find(RouterOS\Query::where('comment', $comentario)));
                    }
                  }
                }
                $data_up = ["estado" => 'ACTIVO'];
                $result_update_estado = $this->model_clientes->update_cliente($idcliente, $data_up);
              }
              $txt_log = $p_tipofc != 0 ? "Factura de servicio pagada (Total cobrado: MX$$cobrado) - Factura ID: $idfactura" : "Saldo Pagado de la factura ID: $newid";
              $insert_log = $this->model_log->insert_log($idcliente, "facturas", $txt_log, $_SESSION['id_usuario8291']);
            }
            return $result_actualizacion ? json_encode($data) :  json_encode($data['estado'] = "error");
          } else {
            return json_encode($data['estado'] = "error");
          }
        }
        if ($tipopago == 3) {
          $data_promesa = [
            'idcliente' => $idcliente,
            'idfactura' => $idfactura,
            'fechaingreso' => date('Y-m-d'),
            'fecha_limite' => $this->fecha_simple($fecha_limite),
            'operador' => $_SESSION['id_usuario8291'],
            'descripcion' => $descripcion,
            'estado' => "",
          ];
          $result_promesa = $this->finanzas->new_promesa($data_promesa);
          if ($result_promesa) {
            $data_servicios = $this->finanzas->get_data_servicios($idcliente);
            if ($data_servicios) {
              foreach ($data_servicios as $value) {
                $client = true;
                try {
                  $client = new RouterOS\Client($value->ip, $value->usuario, $value->contrasena, $value->puerto);
                } catch (\Throwable $e) {
                  $client = false;
                }
                if ($client) {
                  try {
                    $comentario = "Corte_Servicio_" . $value->id;
                    $util = new RouterOS\Util($client);
                    $util->setMenu('/ip firewall address-list');
                    $util->remove($util->find(RouterOS\Query::where('comment', $comentario)));
                  } catch (RouterOS\RouterErrorException $e) {
                  }
                }
              }
            }
            $data = ["estado" => 'ACTIVO'];
            $result_update_estado = $this->model_clientes->update_cliente($idcliente, $data);
            $data['estado'] = "OK";
            return  json_encode($data);
          } else {
            return json_encode($data['estado'] = "error");
          }
        }
      }
    }
  }

  public function transacciones()
  {
    $acceso = $this->seguridad->Acceso_full('REGISTRAR_PAGO', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($acceso) {
      $action = $this->request->getPost('action');
      if (!$action) {
        $action = $this->request->getGet('action');
      }
      if ($action == "lista") {
        $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal($_SESSION['sucursales']);
        $nodo = $this->request->getGet('nodo');
        $pasarela = $this->request->getGet('forma_pago');
        $operador = $this->request->getGet('operador');
        $desde = $this->request->getGet('desde');
        $hasta = $this->request->getGet('hasta');
        $fecha_actual = date("Y-m-d");
        $all_items = $this->finanzas->get_operaciones_registradas_hoy($fecha_actual, $nodo, $operador, $pasarela, $permiso_sucursal);
        foreach ($all_items as $key => $item) {
          $tool = "
          <td class=\" text-center\">
              <a data-load-printer=\"finanzas/transacciones?action=print&amp;saldo=&amp;idfactura=$item->nfactura&amp;id=$item->id\" class=\"btn btn-default btn-icon btn-sm\">
                <i class=\"fas fa-print\"></i>
              </a>
              <a  onclick=\"getmodal('finanzas/viewuser?action=edittransaccion&amp;origen=pagos&amp;tipo=op&amp;id=$item->nfactura','Editar Transacción','sm');\" data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" data-original-title=\"Editar pago\">
                <i class=\"far fa-edit\"></i>
              </a>";
          if ($acceso>=3) {
            $tool.="<a data-idfactura=$item->nfactura data-idoperacion=$item->id onclick=\"delete_paid(this)\" title=\"Eliminar\" data-toggle=\"tooltip\" class=\"btn btn-default btn-icon btn-sm\" >
                <i class=\"far fa-trash-alt\"></i>
              </a>";
            }
            $tool.= "</td>";

          $item->tool = $tool;
        }
        echo '{"data":' . json_encode($all_items) . '}';
      }
      if ($action == "print") {
        $id = $this->request->getGet('id');
        $idfactura = $this->request->getGet('idfactura');
        $data["id"] = $id;
        $data["idfactura"] = $idfactura;
        return view("finanzas/utils/popover_print", $data);
      }
      if ($action == "printer") {
        $idfactura = $this->request->getGet('id');
        $tipo = $this->request->getGet('tipo');
        $all_items = $this->finanzas->consulta_factura_user($idfactura);
        $saldo_n = $all_items[0]->total - $all_items[0]->cobrado;
        $descuento = $this->finanzas->get_descuento_total_factura($idfactura);
        $descuento = abs($descuento ? $descuento[0]->smonto : 0);
        $datos_empresa = $this->finanzas->get_datos_empresa($all_items[0]->idcliente);
        if ($tipo == "recibo") {
          $nombre_formato = "finanzas_recibo_pdf";
          $html_recibo = $this->formatos->get_formato_recibo();
          $alloperaciones = $this->finanzas->get_operaciones($idfactura);
          $origen = $this->request->getGet('origen');
          if ($origen) $idfactura = $this->formatos->id_prueba_factura_operacion();
          if ($html_recibo[0]->tamano != "") {
            $formato = $html_recibo[0]->tamano;
          } else {
            $formato = [intval($html_recibo[0]->ancho), intval($html_recibo[0]->alto)];
          }
          $config = [
            'format' => $formato . $html_recibo[0]->posicion
          ];
          $mpdf = new \Mpdf\Mpdf($config);

          if ($all_items) {
            $otros_impuestos = explode(";", $all_items[0]->otros_impuestos, -1);
            $data = $this->calcular->calculadora($all_items[0]->str_impuesto, $all_items, $otros_impuestos, $descuento);
            $items = "";

            foreach ($all_items as $item) {
              $items .= "
                <tr>
                    <td>$item->idescripcion</td>
                    <td>MX$$item->cantidad</td>
              </tr>";
            }
            $Nsaldo = round($all_items[0]->total - $all_items[0]->cobrado, 2);
            $data = [
              "images/logofactura.png" => "/". $datos_empresa->logo_facturas_recibos,
              "{url}" => $datos_empresa->logo_facturas_recibos, // plantillas por sucursal usan {url}

              "{nrecibo}" => $all_items[0]->id,
              "{fecha}" => $alloperaciones[0]->fecha_pago,
              "{fecha_impresion}" => date("Y-m-d H:i:s"),

              "{nombre_empresa}" => $datos_empresa->alias,
              "{ruc_empresa}" =>   $datos_empresa->rfc,
              "{direccion_empresa}" =>   $datos_empresa->direccion,
              "{telefono_empresa}" =>   $datos_empresa->telefono,

              "{nombre_cliente}" => $all_items[0]->nombre,
              "{cedula_cliente}" => $all_items[0]->cedula,
              "{direccion_principal_cliente}" => $all_items[0]->direccion_principal,
              "{telefono_cliente}" => $all_items[0]->telefono,
              "{movil_cliente}" => $all_items[0]->movil,

              "<td>{items}</td>" => $items,
              "{total_letras}" => "MX$" . round($data['total'], 2),
              "{barcode}" => $alloperaciones[0]->id,
              "{nrecibo}" => $alloperaciones[0]->id,

              "{descuento}" => "MX$" . round($data['nuevo_descuento'], 2),
              "{total}" => "MX$" . round($data['total'], 2),
              "{cobrado}" => "MX$" . round($all_items[0]->cobrado, 2),
              "{saldo}" => "MX$" . ($Nsaldo < 0 ? abs($Nsaldo) . "(A favor)" : $Nsaldo),
            ];
            $html = $html_recibo[0]->html;
            $html = html_entity_decode($html);
            $data += CamposPlantilla::valores($html, ($datos_empresa ?? null) ?: null); // {sucursal_*}, {config_*}
            $html = strtr($html, $data);
            $mpdf->WriteHTML($html);
            $mpdf->Output("./files/pdfs/" . $nombre_formato . ".pdf", "F"); // opens in browser
            $this->show($nombre_formato . ".pdf");
          }
        }
        if ($tipo == "recibopos") {
          $origen = $this->request->getGet('origen');
          if ($origen) $idfactura = $this->formatos->id_prueba_factura();
          $nombre_formato = "finanzas_recibopos_pdf";
          $html_recibo = $this->formatos->get_formato_recibopos();
          $max_fechapago = $this->finanzas->get_max_fecha_pago($all_items[0]->idcliente);
          $nuevafecha = strtotime('+1 month', strtotime($max_fechapago[0]->vencimiento));
          $alloperaciones = $this->finanzas->get_operaciones($idfactura);
          $nuevafecha = date('y-m-d', $nuevafecha);
          $mes = date("m", strtotime($nuevafecha));
          $anio = date("y", strtotime($nuevafecha));
          $max_fechapago[0]->diapago = $max_fechapago[0]->diapago + 1;
          $nuevafecha = $anio . "-" . $mes . "-" . $max_fechapago[0]->diapago;
          if ($html_recibo[0]->posicion == "") {
            $posicion = "-P";
          }
          if ($html_recibo[0]->tamano != "") {
            $formato = $html_recibo[0]->tamano;
          } else {
            $formato = [intval($html_recibo[0]->ancho), intval($html_recibo[0]->alto)];
          }
          $config = [

            'format' => [$html_recibo[0]->tamano, 250],
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_footer' => 0,
            'margin_bottom' => 0,
          ];
          $mpdf = new \Mpdf\Mpdf($config);

          if ($all_items) {
            $otros_impuestos = explode(";", $all_items[0]->otros_impuestos, -1);
            $data = $this->calcular->calculadora($all_items[0]->str_impuesto, $all_items, $otros_impuestos, $descuento);
            $items = "";
            foreach ($all_items as $item) {

              $items .= "<p>- $item->idescripcion</p>";
            }
            $data = [
              "{nombre_empresa}" => $datos_empresa->alias,
              "{ruc_empresa}" =>   $datos_empresa->rfc,
              "{direccion_empresa}" =>   $datos_empresa->direccion,
              "{telefono_empresa}" =>   $datos_empresa->telefono,

    
              "{fecha}" => $alloperaciones[0]->fecha_pago,

              "{itempos}" => $items,

              "{descuento}" => "MX$" . $data['nuevo_descuento'],
              "{total}" => "MX$" . $data['total'],
              "{saldo}" => "MX$" . (0 > $saldo_n ? abs($saldo_n) . "(A favor)" : $saldo_n),

              "{total_letras}" => "MX$" . round($data['total'], 2),

              "{forma_pago}" => $alloperaciones[0]->forma_pago,

              "{nombre_cliente}" => $all_items[0]->nombre,
              "{direccion_principal_cliente}" => $all_items[0]->direccion_principal,
              "{cedula_cliente}" => $all_items[0]->cedula,
              "{corte}" => $nuevafecha,

              "{nrecibo}" => $alloperaciones[0]->id,
              "{operador}" => $alloperaciones[0]->nombre,
              "{fecha_impresion}" => date("Y-m-d h:i A"),

              "{telefono_cliente}" => $all_items[0]->telefono,
              "{movil_cliente}" => $all_items[0]->movil,

              "{barcode}" => $alloperaciones[0]->id,
              "{nrecibo}" => $alloperaciones[0]->id,
              "{cobrado}" => "MX$" . round(0, 2),
            ];
            $html = $html_recibo[0]->html;
            $html = html_entity_decode($html);
            $data += CamposPlantilla::valores($html, ($datos_empresa ?? null) ?: null); // {sucursal_*}, {config_*}
            $html = strtr($html, $data);
            $mpdf->WriteHTML($html);
            $mpdf->Output("./files/pdfs/" . $nombre_formato . ".pdf", "F"); // opens in browser
            $this->show($nombre_formato . ".pdf");
          }
        }
        if ($tipo == "factura") {
          $origen = $this->request->getGet('origen');
          if ($origen) $idfactura = $this->formatos->id_prueba_factura();
          $html_factura = $this->formatos->get_formato_factura();
          $data = array();
          $items = "";
          $impuesto1 = 0;
          $impuesto2 = 0;
          $impuesto3 = 0;
          $nombre_formato = "finanzas_factura_pdf";
          if ($html_factura[0]->tamano != "") {
            $formato = $html_factura[0]->tamano;
          } else {
            $formato = [intval($html_factura[0]->ancho), intval($html_factura[0]->alto)];
          }
          $config = [
            'format' => $formato . $html_factura[0]->posicion
          ];
          $mpdf = new \Mpdf\Mpdf($config);
          //config

          if ($all_items) {
            if ($all_items[0]->otros_impuestos == "" or $all_items[0]->otros_impuestos == null) $all_items[0]->otros_impuestos = "0;0;0;";
            $otros_impuestos = explode(";", $all_items[0]->otros_impuestos, -1);
            $impuesto1 = $otros_impuestos[0];
            $impuesto2 = $otros_impuestos[1];
            $impuesto3 = $otros_impuestos[2];
            if ($all_items[0]->estado == "No pagado") $estadocss = "nopagado";
            if ($all_items[0]->estado == "pagado") $estadocss = "pagado";
            if ($all_items[0]->estado == "anulado") $estadocss = "anulado";
            foreach ($all_items as $item) {
              $items .= "
                          <tr>
                            <td>$item->idescripcion</td>
                            <td>MX$$item->cantidad</td>
                            <td>$item->iimpuesto</td>
                            <td>$item->unidades</td>
                            <td>MX$$item->cantidad</td>
                          </tr>";
            }
            $data = $this->calcular->calculadora($all_items[0]->str_impuesto, $all_items, $otros_impuestos, $descuento);
            $alloperaciones = $this->finanzas->get_operaciones($idfactura);
            $operaciones = "";
            $suma_t = 0;
            if ($alloperaciones) {
              foreach ($alloperaciones as $value) {
                $suma_t += $value->cobrado;
                $operaciones .=
                  "<tr>
                          <td >" . date('Y-m-d H:i A', strtotime($value->fecha_pago)) . "</td>
                          <td>" . $value->forma_pago . "</td>
                          <td>MX$" . $value->cobrado . "</td>
                        </tr>";
              }
              $Nsaldo = $data['total'] - $suma_t;
              $operaciones =
                "<div class=\"invoice-content\" style=\"margin-top:10px\">
                        <table autosize=\"2\" class=\"table-invoice\">
                        <thead>
                        <tr>
                          <th COLSPAN=3 width=\"100px\">Transacciones</th>
                          </tr>
                      </thead>
                      <tbody>
                       " . $operaciones . "
                       <tr>

                          <td COLSPAN=\"2\" style=\"text-aling:right;background-color: #dcdcdc;\">Nuevo balance:</>
                          <td style=\"text-aling:right;background-color: #8484840d;\">MX$" . ($Nsaldo < 0 ? abs($Nsaldo) . "(A favor)" : $Nsaldo) . "</td>
                        </tr>
                      </tbody>
                      </table>
                  </div>";
            }
            $data = [
              "images/logofactura.png" =>"/". $datos_empresa->logo_facturas_recibos,
              "{url}" => $datos_empresa->logo_facturas_recibos, // plantillas por sucursal usan {url}

              "{nombre_empresa}" => $datos_empresa->alias,
              "{ruc_empresa}" =>   $datos_empresa->rfc,
              "{direccion_empresa}" =>   $datos_empresa->direccion,
              "{telefono_empresa}" =>   $datos_empresa->telefono,

              "{estado}" => $all_items[0]->estado,
              "estadocss" => $estadocss,
              "{nfactura}" => $all_items[0]->id,
              "{emitido}" => $all_items[0]->emitido,
              "{vencimiento}" => $all_items[0]->vencimiento,



              "{nombre_cliente}" => $all_items[0]->nombre,
              "{cedula_cliente}" => $all_items[0]->cedula,
              "{direccion_principal_cliente}" => $all_items[0]->direccion_principal,
              "{telefono_cliente}" => $all_items[0]->telefono,
              "{movil_cliente}" => $all_items[0]->movil,

              "<td>{items}</td>" => $items,
              "{total_letras}" => round($data['total'], 2),

              "{barcode}" => $all_items[0]->idcliente,
              "{subtotal}" => "MX$" . round($data['subtotal'], 2),
              "{impuesto}" => "MX$" . round($data['iva'], 2),
              "{otro_impuesto_1}" => "MX$" . round($impuesto1, 2),
              "{otro_impuesto_2}" => "MX$" . round($impuesto2, 2),
              "{otro_impuesto_3}" => "MX$" . round($impuesto3, 2),
              "{descuento}" =>  "MX$" . $data['nuevo_descuento'],
              "{total}" => "MX$" . round($data['total'], 2),

              "<p>{operaciones}</p>" => $operaciones,
            ];
            $html = $html_factura[0]->html;
            $html = html_entity_decode($html);
            $data += CamposPlantilla::valores($html, ($datos_empresa ?? null) ?: null); // {sucursal_*}, {config_*}
            $html = strtr($html, $data);
            $mpdf->WriteHTML($html);
            $mpdf->Output("./files/pdfs/" . $nombre_formato . ".pdf", "F"); // opens in browser
            $this->show($nombre_formato . ".pdf");
          } else {
            return null;
          }
        }
      }
    }
  }

  public function viewreporte()
  {
    if ($this->seguridad->Acceso('REGISTRAR_PAGO', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $action = $this->request->getGet('action');
      if ($action == "transacciones") {
        $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal($_SESSION['sucursales']);
        $nodo = $this->request->getPost('nodo');
        $pasarela = $this->request->getPost('forma_pago');
        $operador = $this->request->getPost('operador');
        $desde = $this->request->getPost('desde');
        $hasta = $this->request->getPost('hasta');
        $fecha_actual = date("Y-m-d");
        $config = [
          'orientation' => 'L',
          'margin_left' => 10,
          'margin_right' => 10,
          'margin_top' => 10,
          'margin_footer' => 0,
          'margin_bottom' => 0,
        ];
        $mpdf = new \Mpdf\Mpdf($config);
        $nombre_formato = "reporte_diario_pdf";
        $all_items = $this->finanzas->get_operaciones_registradas_hoy_reporte($fecha_actual, $nodo, $operador, $pasarela, $permiso_sucursal);
        if ($all_items) {
          $pasarelas = $this->finanzas->get_pasarelas_data($fecha_actual);

          foreach ($pasarelas as $pasarela) {
            $pasarela->value = 0;
          }

          $data['fecha_desde'] = date("Y-m-d");
          $data['fecha_hasta'] = date("Y-m-d");
          $data['data'] = $all_items;
          $data['pasarelas'] = $pasarelas;
          $html = view('finanzas/utils/reporte', $data);
          $mpdf->WriteHTML($html);
          $mpdf->Output("./files/pdfs/" . $nombre_formato . ".pdf", "F"); // opens in browser
          $this->show($nombre_formato . ".pdf");
        } else {
          echo 'No hay operaciones';
        }
      }
    }
  }
  public function agregar_pago_listapromesa()
  {
    $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal($_SESSION['sucursales']);
    $acceso = $this->seguridad->Acceso('REGISTRAR_PAGO', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($acceso) {
      $data['permiso'] = $acceso;
      $data['promesas'] = $this->finanzas->get_all_promesas($permiso_sucursal);
      return view('finanzas/utils/nav_promesas', $data);
    }
  }

  public function estadisticas()
  {
    if ($this->seguridad->Acceso('ESTADISTICAS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $sucursales = $this->model_selectores->get_all_sucursales();
      $anios = $this->model_selectores->get_anios_operacion();
      if (!$sucursales) return "No existen sucursales";
      $data['sucursales'] = $sucursales;
      $data['anios'] = $anios;
      return view('finanzas/estadisticas', $data);
    }
  }
  public function pagos_estadisticas()
  {
    if ($this->seguridad->Acceso('ESTADISTICAS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $sucursal = $this->request->getGet('sucursal');
      $anio = $this->request->getGet('ano');
      $estadisticas = $this->finanzas->get_estadisticas($sucursal, $anio);
      foreach ($estadisticas as $key => $row) {
        $row->id = $key + 1;
        $row->mes = $row->mes ? $row->mes : $row->otro_mes;
        $cobrado = $row->cobrado ? $row->cobrado : 0;
        $ingreso = $row->ingreso ? $row->ingreso : 0;
        $egreso = $row->egreso ? $row->egreso : 0;
        $bruto = $cobrado + $ingreso;
        $neto = $bruto - $egreso;
        $comision = $row->comision ? $row->comision : 0;
        $row->comision = 'MX$' . number_format($comision, 2, ",", ",");
        $row->neto = 'MX$' . number_format($neto, 2, ",", ",");
        $row->bruto = 'MX$' . number_format($bruto, 2, ",", ",");
        $row->cobrado = 'MX$' . number_format($cobrado, 2, ",", ",");
        $row->egreso = 'MX$' . number_format($egreso, 2, ",", ",");
        $row->ingreso = 'MX$' . number_format($ingreso, 2, ",", ",");
        $row->gneto = $neto;
        $row->eg = $egreso;
        $row->in = $ingreso;
        $row->operaciones = $row->operaciones ? $row->operaciones : 0;
      }
      $data['data'] = $estadisticas;
      return json_encode($data);
    }
  }
  public function get_transacciones()
  {
    $permiso = $this->seguridad->Acceso('TRANSACCIONES', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $data['primer_dia'] = date('d/m/Y', strtotime("first day of this month"));;
      $data['ultimo_dia'] = date('d/m/Y');
      $data['pasarelas'] = $this->model_selectores->get_all_pasarelas();
      $data['nodos'] = $this->model_selectores->get_all_nodos();
      $data['operadores'] = $this->model_selectores->get_all_operadores();
      return view('finanzas/transacciones', $data);
    }
  }
  public function get_transacciones_json()
  {
    $permiso = $this->seguridad->Acceso('TRANSACCIONES', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $where = $this->permiso_sucursal->get_permiso_sucursal($_SESSION['sucursales']);
      $nodo = $this->request->getGet('nodo');
      $forma_pago = $this->request->getGet('forma_pago');
      $operador = $this->request->getGet('operador');
      $desde = $this->request->getGet('desde');
      $desde = str_replace("/", "-", $desde);
      $hora_desde = $this->request->getGet('hora_desde');
      $desde = $desde . " " . $hora_desde;
      $desde = date('Y-m-d H:i:s', strtotime($desde));
      $hasta = $this->request->getGet('hasta');
      $hasta = str_replace("/", "-", $hasta);
      $hora_hasta = $this->request->getGet('hora_hasta');
      $hasta = $hasta . " " . $hora_hasta;
      $hasta = date('Y-m-d H:i:s', strtotime($hasta));
      $transacciones = $this->finanzas->get_transacciones($desde, $hasta, $nodo, $operador, $forma_pago, $where);
      if ($transacciones) {
        foreach ($transacciones as $transaccion) {
          $eliminar = "";
          if ($permiso >= 2) {
            $eliminar = "<a data-idfactura=$transaccion->nfactura data-idoperacio=$transaccion->id onclick=\"delete_paid(this)\" title=\"Eliminar\" data-toggle=\"tooltip\" class=\"btn btn-default btn-icon btn-sm\" >
                <i class=\"far fa-trash-alt\"></i>
              </a>";
          }
          $tool = "
          <td class=\" text-center\">
              <a data-load-printer=\"finanzas/transacciones?action=print&amp;saldo=&amp;idfactura=$transaccion->nfactura&amp;id=$transaccion->id\" class=\"btn btn-default btn-icon btn-sm\">
                <i class=\"fas fa-print\"></i>
              </a>
              <a  onclick=\"getmodal('finanzas/viewuser?action=edittransaccion&amp;origen=pagos&amp;tipo=op&amp;id=$transaccion->nfactura','Editar Transacción','sm');\" data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" data-original-title=\"Editar pago\">
                <i class=\"far fa-edit\"></i>
              </a>
              $eliminar
          </td>";
          $transaccion->tool = $tool;
        }
        $data['data'] = $transacciones;
        return json_encode($data);
      } else {
        $transacciones = array();
        $data['data'] = $transacciones;
        return json_encode($data);
      }
    }
  }

  public function viewreporte_transacciones()
  {
    if ($this->seguridad->Acceso('TRANSACCIONES', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal($_SESSION['sucursales']);
      $nodo = $this->request->getPost('nodo');
      $pasarela = $this->request->getPost('forma_pago');
      $operador = $this->request->getPost('operador');
      $desde = $this->request->getPost('start');
      $hasta = $this->request->getPost('end');
      $hora_desde = $this->request->getPost('hora_desde');
      $hora_hasta = $this->request->getPost('hora_hasta');
      $desde = str_replace("/", "-", $desde);
      $hasta = str_replace("/", "-", $hasta);
      $desde = $desde . " " . $hora_desde;
      $hasta = $hasta . " " . $hora_hasta;
      $desde = date('Y-m-d H:i:s', strtotime($desde));
      $hasta = date('Y-m-d H:i:s', strtotime($hasta));
      $fdesde = date('Y-m-d', strtotime($desde));
      $fhasta = date('Y-m-d', strtotime($hasta));
      $fecha_actual = date("Y-m-d");

      $config = [
        'orientation' => 'L',
        'margin_left' => 10,
        'margin_right' => 10,
        'margin_top' => 10,
        'margin_footer' => 0,
        'margin_bottom' => 0,
      ];
      $mpdf = new \Mpdf\Mpdf($config);
      $nombre_formato = "reporte_pdf";
      $all_items = $this->finanzas->get_operaciones_registradas_reporte($desde, $hasta, $nodo, $operador, $pasarela, $permiso_sucursal);
      if ($all_items) {
        $pasarelas = $this->finanzas->get_pasarelas_data($fecha_actual);
        foreach ($pasarelas as $pasarela) {
          $pasarela->value = 0;
        }

        $data['fecha_desde'] = $fdesde;
        $data['fecha_hasta'] = $fhasta;
        $data['data'] = $all_items;
        $data['pasarelas'] = $pasarelas;
        $html = view('finanzas/utils/reporte', $data);
        /* echo $html; */
        $mpdf->WriteHTML($html);
        $mpdf->Output("./files/pdfs/" . $nombre_formato . ".pdf", "F"); // opens in browser
        $this->show($nombre_formato . ".pdf");
      } else {
        echo "No hay operaciones";
      }
    }
  }
  public function validarOperacion()
  {
    if ($this->seguridad->Acceso('FACTURAS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getPost('id');
      $pagado = $this->finanzas->get_estado_factura($id);
      $data['estado'] = $pagado ? $pagado->estado : "na";
      return json_encode($data);
    }
  }
  public function fecha_simple($fecha)
  {
    $fecha = str_replace("/", "-", $fecha);
    $fecha = date("Ymd", strtotime($fecha));
    return $fecha;
  }
  public function fecha_con_hora($fecha)
  {
    $fecha = str_replace("/", "-", $fecha);
    $fecha = date("Y-m-d H:i:s", strtotime($fecha));
    return $fecha;
  }
}
