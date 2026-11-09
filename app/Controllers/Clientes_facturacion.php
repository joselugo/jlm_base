<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Model_clientes;
use App\Libraries\Class_seguridad;
use App\Libraries\Decodificar;
use App\Models\Model_finanzas_facturacion;
use App\Models\Model_finanzas;
use App\Models\Model_log;
use App\Models\Model_selectores;
use App\Libraries\Class_permiso_sucursal;

class Clientes_facturacion extends BaseController
{
  public function __construct()
  {
    $this->model_log = new Model_log();
    $this->finanzas = new Model_finanzas();
    $this->seguridad = new Class_seguridad();
    $this->decode = new Decodificar();
    $this->model_clientes = new Model_clientes();
    $this->model_facturacion = new Model_finanzas_facturacion();
    $this->model_selectores = new Model_selectores();
    $this->permiso_sucursal = new Class_permiso_sucursal();
    date_default_timezone_set('America/Mexico_City');
    session();
  }
  public function home()
  {
    if ($this->seguridad->Acceso('FACTURAS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getGet('id');
      $data['id'] = $id;
      return view('clientes/lista_clientes/taps/facturacion_home', $data);
    }
  }
  public function facturas()
  {
    $permiso = $this->seguridad->Acceso('FACTURAS', 'LECTURA', $_SESSION['id_usuario8291']);
    $permiso2 = $this->seguridad->Acceso_void('CREAR_Y_ELIMINAR_FACTURAS', 'LECTURA', $_SESSION['id_usuario8291']);
    $permiso3 = $this->seguridad->Acceso_void('REGISTRAR_PAGO', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getGet('id');
      $data['facturas'] = $this->model_clientes->get_facturas_cliente($id);
      $data['id'] = $id;
      $data['fecha'] = date('Y-m-d');
      $data['permiso'] = $permiso;
      $data['permiso2'] = $permiso2 ? 1 : 2;
      $data['permiso3'] = $permiso3 ? $permiso3 : 1;
      return view('clientes/lista_clientes/taps/sub_taps/utils/nav_facturas', $data);
    }
  }
  public function ver_factura()
  {
    if ($this->seguridad->Acceso('FACTURAS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $idfactura = $this->request->getGet('id');
      $factura = $this->model_clientes->get_factura_cliente($idfactura);
      if ($factura) {
        $factura->otros_impuestos == "" ? $factura->otros_impuestos = "0;0;0;" : null;
        $data['factura'] = $factura;
        $data['items'] = $this->model_clientes->get_factura_items($idfactura);
        $data['emisor'] = $this->model_clientes->get_datos_empresa();
        $descuento  = $this->model_clientes->get_descuento_factura($idfactura);
        $data['descuento'] = $descuento ? abs($descuento[0]->monto) : 0;
        return view('clientes/lista_clientes/taps/sub_taps/utils/modal_ver_factura.php', $data);
      } else {
        echo  "<script>alerta('error','No se encontro la factura.')</script>";
      }
    }
  }
  public function configuracion()
  {
    $permiso = $this->seguridad->Acceso('FACTURAS', 'LECTURA', $_SESSION['id_usuario8291']);
    $permiso2 = $this->seguridad->Acceso_void('CLIENTE_CONFIGURACION', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $idcliente = $this->request->getGet('id');
      $configuracion = $this->model_facturacion->get_configuracion_personal($idcliente);
      if ($configuracion) {
        $data['idcliente'] = $idcliente;
        $data['permiso'] = $permiso;
        $data['permiso2'] = $permiso2;
        $data['config'] = $configuracion[0];
        $data['impuestos'] = $this->decode->decodificar($configuracion[0]->otros_impuestos);
        return view('clientes/lista_clientes/taps/sub_taps/utils/nav_configuracion.php', $data);
      } else {
        return 'error';
      }
    }
  }
  public function get_notas()
  {
    if ($this->seguridad->Acceso('FACTURAS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $diapago = $this->request->getPost('config[diapago]');
      $diafactura = $this->request->getPost('config[diafactura]');
      $corteautomatico = $this->request->getPost('config[corteautomatico]');
      $avisopantalla = $this->request->getPost('config[avisopantalla]');
      $tiporecordatorio = $this->request->getPost('config[tiporecordatorio]');
      $avisosms = $this->request->getPost('config[avisosms]');
      $avisosms2 = $this->request->getPost('config[avisosms2]');
      $avisosms3 = $this->request->getPost('config[avisosms3]');
      $idcliente = $this->request->getPost('idcliente');
      $config = $this->model_facturacion->get_config($idcliente);
      if ($config[0]->fecha_factura == 0) {
        $max_fechapago = $this->finanzas->get_max_fecha_pago($idcliente);
        if ($max_fechapago) {
          $max_fechapago = $max_fechapago[0]->vencimiento;
        }
      } else {
        $max_fechapago = $config[0]->fecha_factura;
      }
      $nuevafecha = strtotime('+1 month', strtotime($max_fechapago));
      $nuevafecha = date('y-m-d', $nuevafecha);
      $mes = date("m", strtotime($nuevafecha));
      $año = date("y", strtotime($nuevafecha));
      $daypago = $año . "-" . $mes . "-" . $diapago;
      $daycorte = strtotime("+$corteautomatico day", strtotime($daypago));
      $dayfactura = strtotime("-$diafactura day", strtotime($daypago));
      if ($avisopantalla == 0) {
        $daypantalla = "Desactivado";
      } else {
        $daypantalla = date('d/m/Y', strtotime("-$avisopantalla day", strtotime($daypago)));
      }
      $daysms1 = "Desactivado";
      $daysms2 = "Desactivado";
      $daysms3 = "Desactivado";
      if ($tiporecordatorio != 0) {
        if ($avisosms > 0) $daysms1 = date('d/m/Y', strtotime("+$avisosms day", strtotime($daypago)));
        if ($avisosms < 0) $daysms1 = date('d/m/Y', strtotime("$avisosms day", strtotime($daypago)));
        if ($avisosms2 > 0) $daysms2 = date('d/m/Y', strtotime("+$avisosms2 day", strtotime($daypago)));
        if ($avisosms2 < 0) $daysms2 = date('d/m/Y', strtotime("$avisosms2 day", strtotime($daypago)));
        if ($avisosms3 > 0) $daysms3 = date('d/m/Y', strtotime("+$avisosms3 day", strtotime($daypago)));
        if ($avisosms3 < 0) $daysms3 = date('d/m/Y', strtotime("$avisosms3 day", strtotime($daypago)));
      }

      /* $daysms1 = strtotime("-$avisosms day", strtotime($daypago));
      $daysms2 = strtotime("-$avisosms2 day", strtotime($daypago));
      $daysms3 = strtotime("-$avisosms3 day", strtotime($daypago)); */
      $data['daypago'] = date('d/m/Y', strtotime($daypago));
      $data['daycorte'] = date('d/m/Y', $daycorte);
      $data['dayfactura'] = date('d/m/Y', $dayfactura);
      $data['daypantalla'] = $daypantalla;
      $data['daysms1'] = $daysms1;
      $data['daysms2'] = $daysms2;
      $data['daysms3'] =  $daysms3;
      return json_encode($data);
    }
  }
  public function guardar_configuracion()
  {
    if ($this->seguridad->Acceso('CLIENTE_CONFIGURACION', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id_config = $this->request->getPost('config[id]');
      $fecha_factura1 = $this->request->getPost('fecha_factura');
      $idcliente = $this->request->getPost('idcliente');
      $tipopago = $this->request->getPost('config[tipopago]');
      $diapago = $this->request->getPost('config[diapago]');
      $diafactura = $this->request->getPost('config[diafactura]');
      $impuesto = $this->request->getPost('config[impuesto]');
      $corteautomatico = $this->request->getPost('config[corteautomatico]');
      $meses = $this->request->getPost('config[meses]');
      $limit_velocidad = $this->request->getPost('config[limit_velocidad]');
      $fecha_factura = $this->request->getPost('config[fecha_factura]');
      $mora = $this->request->getPost('config[mora]');
      $impuestos1 = $this->request->getPost('impuestos[1]');
      $impuestos2 = $this->request->getPost('impuestos[2]');
      $impuestos3 = $this->request->getPost('impuestos[3]');
      $afip = $this->request->getPost('config[afip]');
      $afip_punto_venta = $this->request->getPost('config[afip_punto_venta]');
      $afip_condicion_iva = $this->request->getPost('config[afip_condicion_iva]');
      $afip_condicion_venta = $this->request->getPost('config[afip_condicion_venta]');
      $afip_automatico = $this->request->getPost('config[afip_automatico]');
      $tipoaviso = $this->request->getPost('config[tipoaviso]');
      $avisopantalla = $this->request->getPost('config[avisopantalla]');
      $tiporecordatorio = $this->request->getPost('config[tiporecordatorio]');
      $avisosms = $this->request->getPost('config[avisosms]');
      $avisosms2 = $this->request->getPost('config[avisosms2]');
      $avisosms3 = $this->request->getPost('config[avisosms3]');

      $reconexion = $this->request->getPost('config[reconexion]');
      $impuestos = "i:1;s:" . strlen($impuestos1) . ":\"" . $impuestos1 . "\";";
      $impuestos .= "i:2;s:" . strlen($impuestos2) . ":\"" . $impuestos2 . "\";";
      $impuestos .= "i:3;s:" . strlen($impuestos3) . ":\"" . $impuestos3 . "\";";
      $otros_impuestos = "a3{" . $impuestos . "}";
      $data_configuracion = [
        'mora' => $mora ? $mora : "",
        'reconexion' => $reconexion ? $reconexion : "",
        'impuesto' => $impuesto,
        'diapago' => $diapago,
        'tipopago' => $tipopago,
        'tipoaviso' => $tipoaviso,
        'meses' => $meses,
        'fecha_factura' => $fecha_factura != "" ? date('Y-m-d', strtotime(str_replace("/", "-", $fecha_factura))) : 0,
        'diafactura' => $diafactura,
        'avisopantalla' => $avisopantalla,
        'corteautomatico' => $corteautomatico,
        'avisosms' => $avisosms,
        'avisosms2' => $avisosms2,
        'avisosms3' => $avisosms3,
        'afip_condicion_iva' => $afip_condicion_iva,
        'afip' => $afip,
        'afip_condicion_venta' => $afip_condicion_venta,
        'afip_automatico' => $afip_automatico,
        'tiporecordatorio' => $tiporecordatorio,
        'afip_punto_venta' => $afip_punto_venta,
        'otros_impuestos' => $otros_impuestos,
        'limit_velocidad' => $limit_velocidad
      ];
      $result_save = $this->model_facturacion->save_configuracion($id_config, $data_configuracion);
      if ($result_save) {
        echo "1";
        $nombre_cliente = $this->model_log->get_nombre_cliente($idcliente);
        $txt_log = "Cambio en configuración facturación y notificaciones (" . $nombre_cliente[0]->nombre . ") - Cliente ID: " . $idcliente;
        $insert_log = $this->model_log->insert_log($idcliente, "configuracion_cliente", $txt_log, $_SESSION['id_usuario8291']);
      } else {
        echo 2;
      }
    }
  }


  public function modal_new_factura_libre()
  {
    if ($this->seguridad->Acceso('FACTURAS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $idcliente = $this->request->getGet('id');
      $config = $this->model_facturacion->get_config($idcliente);
      $data['idcliente'] = $idcliente;
      $data['fecha'] = date('d/m/Y');
      $data['impuesto'] = $config[0]->impuesto;
      return view('clientes/lista_clientes/taps/sub_taps/utils/modal_new_factura_libre', $data);
    }
  }
  public function modal_new_factura_servicio()
  {
    if ($this->seguridad->Acceso('FACTURAS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $idcliente = $this->request->getGet('id');
      $config = $this->model_facturacion->get_config($idcliente);

      $num_facture = $this->finanzas->check_facture($idcliente);
      if ($num_facture->numfacturas > 0) {
        $vencimiento = $this->finanzas->get_proxima_facturacion($idcliente);
      } else {
        /* if ($config[0]->diapago >= date('d')) {
          $vencimiento = $config[0]->diapago . '-' . date('m') . '-' . date('Y');
        } elseif ($config[0]->diapago < date('d')) {
          $vencimiento = $config[0]->diapago . '-' . date('m') . '-' . date('Y');
          $vencimiento = date("d-m-Y", strtotime($vencimiento . "+ 1 month"));
        } */
        $vencimiento = $config[0]->diapago . '-' . date('m') . '-' . date('Y');
        if ($config[0]->tipopago == 0) {

          $vencimiento = date("d-m-Y", strtotime($vencimiento));
        } else {
          $vencimiento = date("d-m-Y", strtotime($vencimiento . "+ 1 month"));
        }
      }

      $code = $config[0]->otros_impuestos;
      $impuestos = $this->decode->decodificar($code);


      $data['idcliente'] = $idcliente;
      $data['vencimiento'] = date('d/m/Y', strtotime($vencimiento));
      $data['fecha_actual'] = date('d/m/Y');

      $data['impuestos'] = $impuestos;


      $data['impuesto'] = $config[0]->impuesto;
      /* $data['impuestos'] = $config[0]->otros_impuestos; */
      return view('clientes/lista_clientes/taps/sub_taps/utils/modal_new_factura_servicio', $data);
    }
  }
  public function get_descuento_factura_servicios()
  {
    if ($this->seguridad->Acceso('FACTURAS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $idcliente = $this->request->getPost('idcliente');
      $vencimiento = $this->request->getPost('fecha');
      $config = $this->model_facturacion->get_config($idcliente);
      $saldos = $this->model_facturacion->get_saldos($idcliente);
      $planes = $this->model_facturacion->get_planes_cliente($idcliente);
      $productos = $this->model_facturacion->get_productos_cliente($idcliente);
      $alquileres = $this->model_facturacion->get_alquileres_cliente($idcliente);
      $descuento_disponible = $this->model_facturacion->get_descuento_disponible($idcliente);
      $tolerancia = $config[0]->corteautomatico;
      // echo $descuento_disponible[0]->descuento;
      $body = "";
      if ($config[0]->tipopago == 0) {
        //pospago
        $desde = date('d/m/Y', strtotime($vencimiento));
        $hasta = date('d/m/Y', strtotime("+1month", strtotime("-1 days", strtotime($vencimiento))));
      }
      if ($config[0]->tipopago == 1) {
        //prepago
        $desde = date('d/m/Y', strtotime("-1 month", strtotime($vencimiento)));
        $hasta = date('d/m/Y', strtotime("-1 day", strtotime($vencimiento)));
      }
      foreach ($alquileres as $alquiler) {
        $numero_cuota = $alquiler->ncuota + 1;
        $numero_cuota_total = $alquiler->totalcuota;
        if ($alquiler->tipoa == "producto") {
          $descripcion = $alquiler->p_descripcion;
          $serial = $alquiler->serial;
          $descripcion = str_replace("{producto_serial}", $serial, $descripcion);
          $alquiler->impuesto_final = $alquiler->p_impuesto;
          $alquiler->clave_invoice_final = $alquiler->p_clave_invoice;
        } else {
          $descripcion = $alquiler->descripcion;
          $alquiler->impuesto_final = $alquiler->impuesto;
          $alquiler->clave_invoice_final = $alquiler->clave_invoice;
        }
        if ($numero_cuota_total <> 0) {
          $descripcion .= " (Cuota $numero_cuota de $numero_cuota_total)";
        }
        $alquiler->descripcion_final = $descripcion;
        $body .= "              <tr>
                <td>
                  <input value=\"0\" type=\"hidden\" class=\"form-control\" name=\"idproducto[]\">
                  <textarea class=\"form-control\" placeholder=\"Descripci�n\" name=\"descripcion[]\" rows=\"1\"
                    style=\"overflow: hidden; overflow-wrap: break-word; height: 232px;\">$alquiler->descripcion_final</textarea>
                    </td>
                <td>
                  <input value=\"$alquiler->monto\" type=\"text\" class=\"form-control\"
                    placeholder=\"$alquiler->monto\" name=\"costo[]\" pattern=\"[-+]?[0-9]*[.]?[0-9]+\">
                </td>
                <td>
                  <input value=\"$alquiler->impuesto_final\" type=\"text\" class=\"form-control\"
                    placeholder=\"$alquiler->impuesto_final\" name=\"impuesto[]\" pattern=\"[0-9]*[.]?[0-9]+\">
                </td>
                <td>
                  <input value=\"1\" type=\"text\" class=\"form-control\" placeholder=\"1\" name=\"unidad[]\" pattern=\"[0-9]+\"
                    readonly=\"\">
                </td>
                <td>
                  <input placeholder=\"$alquiler->monto\" type=\"text\" class=\"form-control\" name=\"totales[]\" disabled=\"\">
                </td>
                <td class=\"removeitem\">
                  <button type=\"button\" disabled=\"\" class=\"removeritem btn btn-sm btn-default\"><i class=\"fas
                      fa-times\"></i></button>
                  <input value=\"$alquiler->clave_invoice_final\" type=\"hidden\" name=\"clave_invoice[]\">
                  <input value=\"\" type=\"hidden\" name=\"costoPrepago[]\">                  
                </td>
                </tr>";
      }
      foreach ($planes as $plan) {
        $velocidad = explode("/", $plan->velocidad);
        $data = [
          "{fecha}" => $desde . " al " . $hasta,
          "{corte}" => date('d/m/Y', strtotime("+$tolerancia day", strtotime($vencimiento))),
          "{mes_actual}" => "pendiente",
          "{plan}" => $plan->plan,
          "{down}" => $velocidad[1],
          "{up}" => $velocidad[0],

        ];
        $costo_plan = 0;
        $plan->descripcion = strtr($plan->descripcion, $data);
        if ($descuento_disponible[0]->descuento) {
          $costo_plan = ($plan->costo) + ($descuento_disponible[0]->descuento);
          $plan->descripcion = $plan->descripcion . "\n" . "SALDO A FAVOR DE " . str_replace("-", '', $descuento_disponible[0]->descuento);;
        } else {
          $costo_plan = $plan->costo;
        }
        $prepago = $plan->costo_prepago == null ? "" : $plan->costo_prepago;
        $body .= "  <tr>
                <td>
                  <input value=\"0\" type=\"hidden\" class=\"form-control\" name=\"idproducto[]\">
                  <textarea class=\"form-control\" placeholder=\"Descripci�n\" name=\"descripcion[]\" rows=\"1\"
                    style=\"overflow: hidden; overflow-wrap: break-word; height: 232px;\">$plan->descripcion</textarea>
                </td>
                <td>
                  <input value=\"$costo_plan\" type=\"text\" class=\"form-control\"
                    placeholder=\"$costo_plan\" name=\"costo[]\" pattern=\"[-+]?[0-9]*[.]?[0-9]+\">
                </td>
                <td>
                  <input value=\"$plan->impuesto\" type=\"text\" class=\"form-control\"
                    placeholder=\"$plan->impuesto\" name=\"impuesto[]\" pattern=\"[0-9]*[.]?[0-9]+\">
                </td>
                <td>
                  <input value=\"1\" type=\"text\" class=\"form-control\" placeholder=\"1\" name=\"unidad[]\" pattern=\"[0-9]+\"
                    readonly=\"\">
                </td>
                <td>
                  <input placeholder=\"$costo_plan\" type=\"text\" class=\"form-control\" name=\"totales[]\"
                    disabled=\"\">
                </td>
                <td class=\"removeitem\">
                  <button type=\"button\" disabled=\"\" class=\"removeritem btn btn-sm btn-default\"><i
                      class=\"fas fa-times\"></i></button>
                  <input value=\"\" type=\"hidden\" name=\"clave_invoice[]\">
                  <input value=\"$prepago\" type=\"hidden\" name=\"costoPrepago[]\">
                </td>
              </tr>";
      }
      foreach ($productos as $producto) {
        $tipo = $producto->tipoa;
        if ($tipo == "producto") {
          $data = [
            "{producto_serial}" => $producto->serial_producto,
          ];
          $producto->id_final = $producto->id;
          $producto->descripcion_final = strtr($producto->descripcion, $data);
          $producto->impuesto_final = $producto->impuesto;
          $producto->clave_invoice_final = $producto->clave_invoice;
        } else {
          $producto->id_final = 0;
          $producto->descripcion_final = $producto->o_descripcion;
          $producto->impuesto_final = $producto->o_impuesto;
          $producto->clave_invoice_final = $producto->o_clave_invoice;
        }
        $body .= "              <tr>
                <td>
                  <input value=\"$producto->id_final\" type=\"hidden\" class=\"form-control\" name=\"idproducto[]\">
                  <textarea class=\"form-control\" placeholder=\"Descripci�n\" name=\"descripcion[]\" rows=\"1\"
                    style=\"overflow: hidden; overflow-wrap: break-word; height: 232px;\">$producto->descripcion_final</textarea>
                </td>
                <td>
                  <input value=\"$producto->monto\" type=\"text\" class=\"form-control\"
                    placeholder=\"$plan->costo\" name=\"costo[]\" pattern=\"[-+]?[0-9]*[.]?[0-9]+\">
                </td>
                <td>
                  <input value=\"$producto->impuesto_final\" type=\"text\" class=\"form-control\" placeholder=\"18\"
                    name=\"impuesto[]\" pattern=\"[0-9]*[.]?[0-9]+\">
                </td>
                <td>
                  <input value=\"1\" type=\"text\" class=\"form-control\" placeholder=\"1\" name=\"unidad[]\" pattern=\"[0-9]+\"
                    readonly=\"\">
                </td>
                <td>
                  <input placeholder=\"$producto->monto\" type=\"text\" class=\"form-control\" name=\"totales[]\"
                    disabled=\"\">
                </td>
                <td class=\"removeitem\">
                  <button type=\"button\" disabled=\"\" class=\"removeritem btn btn-sm btn-default\"><i
                      class=\"fas fa-times\"></i></button>
                  <input value=\"$producto->clave_invoice_final\" type=\"hidden\" name=\"clave_invoice[]\">
                  <input value=\"\" type=\"hidden\" name=\"costoPrepago[]\">

                </td>
              </tr>";
      }
      foreach ($saldos as $saldo) {
        $saldo_str = $saldo->idorigen != 0 ? "Saldo anterior del comprobante #" . $saldo->idorigen : "Saldo en contra.";
        $body .= "              <tr>
                <td>
                  <input value=\"0\" type=\"hidden\" class=\"form-control\" name=\"idproducto[]\">
                  <textarea class=\"form-control\" placeholder=\"Descripci�n\" name=\"descripcion[]\" rows=\"1\"
                    style=\"overflow: hidden; overflow-wrap: break-word; height: 232px;\">$saldo_str</textarea>
                </td>
                <td>
                  <input value=\"$saldo->monto\" type=\"text\" class=\"form-control\"
                placeholder=\"$saldo->monto\" name=\"costo[]\" pattern=\"[-+]?[0-9]*[.]?[0-9]+\">
                </td>
                <td>
                  <input value=\"0\" type=\"text\" class=\"form-control\" placeholder=\"18\" name=\"impuesto[]\"
                    pattern=\"[0-9]*[.]?[0-9]+\">
                </td>
                <td>
                  <input value=\"1\" type=\"text\" class=\"form-control\" placeholder=\"1\" name=\"unidad[]\" pattern=\"[0-9]+\"
                    readonly=\"\">
                </td>
                <td>
                  <input placeholder=\"$saldo->monto\" type=\"text\" class=\"form-control\" name=\"totales[]\"
                  disabled=\"\">
                </td>
                <td class=\"removeitem\">
                  <button type=\"button\" disabled=\"\" class=\"removeritem btn btn-sm btn-default\"><i class=\"fas
                      fa-times\"></i></button>
                  <input value=\"\" type=\"hidden\" name=\"clave_invoice[]\">
                  <input value=\"\" type=\"hidden\" name=\"costoPrepago[]\">

                </td>
                </tr>";
      }
      $body = utf8_decode($body);
      $body = utf8_encode($body);


      $data = [
        'descuento' => 0,
        'items' => $body
      ];

      return json_encode($data);
    }
  }
  public function get_popup()
  {
    if ($this->seguridad->Acceso('FACTURAS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $productos = $this->model_selectores->get_producto_popup();
      $servicios = $this->model_selectores->get_producto_otro_popup();
      $data['productos'] = $productos;
      $data['servicios'] = $servicios;
      return view('clientes/lista_clientes/taps/sub_taps/utils/popup.php', $data);
    }
  }
  public function popup_producto()
  {
    if ($this->seguridad->Acceso('FACTURAS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal_almacen($_SESSION['sucursales']);
      $idproducto = $this->request->getPost('id');
      $productos = $this->finanzas->get_serialproductos($idproducto, $permiso_sucursal);
      return $productos ? json_encode($productos) : null;
    }
  }
  public function save_factura_servicios()
  {
    if ($this->seguridad->Acceso('CREAR_Y_ELIMINAR_FACTURAS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $idcliente = $this->request->getPost('factura[idcliente]');
      $emitido = $this->request->getPost('factura[emitido]');
      $total = $this->request->getPost('factura[total]');
      $tipo = $this->request->getPost('factura[tipo]');
      $iva_igv = $this->request->getPost('factura[iva_igv]');
      $iva_igv = $this->request->getPost('factura[iva_igv]');
      $vencimiento = $this->request->getPost('factura[vencimiento]');
      $sub_total = $this->request->getPost('factura[sub_total]');
      $otros_impuestos = $this->request->getPost('factura[otros_impuestos]');
      $impuesto = $this->request->getPost('factura[impuesto]');
      $cantidad_prepago_db =

        $idproductos = $this->request->getPost('idproducto[]');
      $descripciones = $this->request->getPost('descripcion[]');
      $costos = $this->request->getPost('costo[]');
      $costosPrepago = $this->request->getPost('costoPrepago[]');
      $impuestos = $this->request->getPost('impuesto[]');
      $unidades = $this->request->getPost('unidad[]');
      $claves_invoice = $this->request->getPost('clave_invoice[]');
      //descuento:-30.00 - descuento2:-13
      $descuento = $this->request->getPost('descuento');
      $descuento2 = $this->request->getPost('descuento2');
      $legal = $this->model_facturacion->get_legal();
      $tmp_factura_existentes = $this->model_facturacion->get_tmp_existentes($idcliente);
      $alquileres_existentes = $this->model_facturacion->get_alquileres_existentes($idcliente);
      $products = false;
      $data_batch = array();
      if (count($idproductos) == 0) return "2";
      foreach ($idproductos as $idproducto) {
        $get_id = false;
        if ($idproducto != 0) {
          foreach ($tmp_factura_existentes as $factura_tmp) {
            if ($factura_tmp->productoid == $idproducto) {
              $get_id = true;
            }
          }
          if (!$get_id) {
            $products = true;
            $data = [
              "id" => $idproducto,
              "estado" => "no disponible",
              "userid" => $idcliente,
              "fecha_salida" => date('Y-m-d'),
            ];
            $data_batch[] = $data;
          }
        }
      }
      if ($products) {
        $result_update_almacen = $this->model_facturacion->update_batch_almacen($data_batch, 'id');
      }
      if (!$products or $result_update_almacen) {
        //agregar factura
        // echo $total;
        $data_factura = [
          'legal' => $legal[0]->value,
          'idcliente' => $idcliente,
          'impuesto' => $impuesto,
          'emitido' => date('Y-m-d', strtotime(str_replace('/', '-', $emitido))),
          'vencimiento' => date('Y-m-d', strtotime(str_replace('/', '-', $vencimiento))),
          'total' => $total < 0 ? 0 : $total,
          'tipo' => $tipo,
          'cobrado' => 0,
          'estado' => $total <= 0 ? 'pagado' : 'No pagado',
          'iva_igv' => $iva_igv,
          'sub_total' => $sub_total,
          'total_khipu' => 0,
          'otros_impuestos' => $otros_impuestos,
          'siro' => 0
        ];
        $result_new_factura = $this->model_facturacion->new_factura($data_factura);
        //los saldos se cambian a estado facturado.
        $data_saldo = [
          "iddestino" => $result_new_factura,
          "estado" => "facturado"
        ];
        $result_update_saldo = $this->model_facturacion->update_saldo($idcliente, $data_saldo);
        //se crea un nuevo saldo si es menor el costo de la nueva factura.
        if ($descuento < $descuento2) {
          $nuevo_saldo = $descuento - $descuento2;
          $data_saldo_insert = [
            'iduser' => $idcliente,
            'idorigen'  => $result_new_factura,
            'estado'  => 'no cobrado',
            'monto'  => $nuevo_saldo,
            'fecha'  => date('Y-m-d'),
            'descripcion'  => 'Saldo a favor generado por la factura #' . $result_new_factura
          ];
          $result_new_saldo = $this->finanzas->insert_nuevo_saldo($data_saldo_insert);
        }
        if ($result_new_factura) {
          //insertar items,
          $data_insert = array();
          foreach ($idproductos as $key => $idproducto) {

            if (count($costosPrepago) <= $key - 1) {

              $cantidad_prepago_db = $costosPrepago[$key] == "" ? null : $costosPrepago[$key];
            } else {
              $cantidad_prepago_db = null;
            }
            $data_insert[] = [
              "idfactura" => $result_new_factura,
              "descripcion" => $descripciones[$key],
              "cantidad" => $costos[$key],
              "cantidad_prepago" => $cantidad_prepago_db,
              "idalmacen" => $idproductos[$key],
              "unidades" => $unidades[$key],
              "impuesto" => $impuestos[$key],
              "clave_invoice" => $claves_invoice[$key]
            ];
          }
          foreach ($tmp_factura_existentes as $tmp) {
            $data_tmp_factura = array();
            $data_tmp_factura = [
              "state" => "facturado",
              "nfactura" => $result_new_factura
            ];
            $this->model_facturacion->update_tmpfactura($tmp->id, $data_tmp_factura);
          }
          foreach ($alquileres_existentes as $alq) {
            $data_alq = array();
            $nueva_cuota = $alq->ncuota + 1;
            $data_alq['ncuota'] = $nueva_cuota;
            if ($nueva_cuota >= $alq->totalcuota and $alq->totalcuota > 0) {
              $data_alq['state'] = "pagado";
            }
            $this->model_facturacion->update_alquiler($alq->id, $data_alq);
          }
          $result_insert_item = $this->model_facturacion->insert_items_factura($data_insert);
          if ($result_insert_item) {
            $nombre_cliente = $this->finanzas->get_nombre_cliente($idcliente);
            $txt_log = "Nueva factura de servicios creado (" . $nombre_cliente[0]->nombre . ") - Factura ID: " . $result_new_factura;
            $insert_log = $this->model_log->insert_log($idcliente, "facturas", $txt_log, $_SESSION['id_usuario8291']);
            echo 1;
          } else {
            echo 2;
          }
        } else {
          echo 2;
        }
      } else {
        echo 2;
      }
    }
  }
  public function save_factura_libre()
  {
    if ($this->seguridad->Acceso('CREAR_Y_ELIMINAR_FACTURAS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $vencimiento = $this->request->getPost('factura[vencimiento]');
      $emitido = $this->request->getPost('factura[emitido]');

      $idproductos = $this->request->getPost('idproducto[]');
      $descripciones = $this->request->getPost('descripcion[]');
      $costos = $this->request->getPost('costo[]');
      $impuestos = $this->request->getPost('impuesto[]');
      $unidades = $this->request->getPost('unidad[]');
      $claves_invoice = $this->request->getPost('clave_invoice[]');

      $idcliente = $this->request->getPost('factura[idcliente]');
      $sub_total = $this->request->getPost('factura[sub_total]');
      $iva_igv = $this->request->getPost('factura[iva_igv]');
      $impuesto = $this->request->getPost('factura[impuesto]');
      $total = $this->request->getPost('factura[total]');
      $legal = $this->model_facturacion->get_legal();
      $tmp_factura_existentes = $this->model_facturacion->get_tmp_existentes($idcliente);
      $products = false;
      $data_batch = array();
      if (count($idproductos) == 0) return "2";
      foreach ($idproductos as $idproducto) {
        if ($idproducto != 0) {
          $products = true;
          $data = [
            "id" => $idproducto,
            "estado" => "no disponible",
            "userid" => $idcliente,
          ];
          $data_batch[] = $data;
        }
      }
      if ($products) {
        $result_update_almacen = $this->model_facturacion->update_batch_almacen($data_batch, 'id');
      }
      if (!$products or $result_update_almacen) {
        //agregar factura
        $data_factura = [
          'legal' => $legal[0]->value,
          'idcliente' => $idcliente,
          'impuesto' => $impuesto,
          'emitido' => date('Y-m-d', strtotime(str_replace('/', '-', $emitido))),
          'vencimiento' => date('Y-m-d', strtotime(str_replace('/', '-', $vencimiento))),
          'total' => $total < 0 ? 0 : $total,
          'tipo' => 1,
          'cobrado' => 0,
          'estado' => $total <= 0 ? 'pagado' : 'No pagado',
          'iva_igv' => $iva_igv,
          'sub_total' => $sub_total,
          'total_khipu' => 0,
          'otros_impuestos' => "",
          'siro' => 0
        ];
        $result_new_factura = $this->model_facturacion->new_factura($data_factura);
        //se crea un nuevo saldo si es menor el costo de la nueva factura.

        if ($result_new_factura) {
          //insertar items,
          $data_insert = array();
          foreach ($idproductos as $key => $idproducto) {
            $data_insert[] = [
              "idfactura" => $result_new_factura,
              "descripcion" => $descripciones[$key],
              "cantidad" => $costos[$key],
              "idalmacen" => $idproductos[$key],
              "unidades" => $unidades[$key],
              "impuesto" => $impuestos[$key],
              "clave_invoice" => $claves_invoice[$key]
            ];
          }
          $result_insert_item = $this->model_facturacion->insert_items_factura($data_insert);
          if ($result_insert_item) {
            $nombre_cliente = $this->finanzas->get_nombre_cliente($idcliente);
            $txt_log = "Nueva factura de libre creado (" . $nombre_cliente[0]->nombre . ") - Factura ID: " . $result_new_factura;
            $insert_log = $this->model_log->insert_log($idcliente, "facturas", $txt_log, $_SESSION['id_usuario8291']);
            echo 1;
          } else {
            echo 2;
          }
        } else {
          echo 2;
        }
      } else {
        echo 2;
      }
    }
  }
  public function transacciones()
  {
    $permiso = $this->seguridad->Acceso('FACTURAS', 'LECTURA', $_SESSION['id_usuario8291']);
    $permiso2 = $this->seguridad->Acceso_void('TRANSACCIONES', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $idcliente = $this->request->getGet('id');
      $operaciones = $this->model_facturacion->get_operaciones_cliente($idcliente);
      if ($operaciones) {
        $data['operaciones'] = $operaciones;
        $data['permiso2'] = $permiso2 ? $permiso2 : 1;
        return view('clientes/lista_clientes/taps/sub_taps/utils/nav_transacciones.php', $data);
      } else {
        echo 'error';
      }
    }
  }
  public function saldos()
  {
    $permiso = $this->seguridad->Acceso('FACTURAS', 'LECTURA', $_SESSION['id_usuario8291']);
    $permiso2 = $this->seguridad->Acceso_void('CREAR_Y_ELIMINAR_SALDOS', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $idcliente = $this->request->getGet('id');
      $saldos = $this->model_facturacion->get_saldos_cliente($idcliente);
      if ($saldos) {
        foreach ($saldos as $value) {
          $state = $value->monto > 0 ? "danger" : "success";
          $estado = $value->monto < 0 ? "A favor" : "En contra";
          $value->monto = abs($value->monto) . " <span class=\"badge label-$state\">$estado</span>";
        }
        $data['saldos'] = $saldos;
      } else {
        $data['saldos'] = array();
      }
      $data['idcliente'] = $idcliente;
      $data['permiso2'] = $permiso2 ? 1 : 2;
      return view('clientes/lista_clientes/taps/sub_taps/utils/nav_saldos', $data);
    }
  }
  public function delete_saldo()
  {
    if ($this->seguridad->Acceso('CREAR_Y_ELIMINAR_SALDOS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $idsaldo = $this->request->getPost('id');
      $idcliente = $this->request->getPost('idcliente');
      $data_saldo = $this->model_facturacion->get_saldo_id($idsaldo);
      if ($data_saldo) {
        $result_delete_saldo = $this->model_facturacion->delete_saldo_cliente($idsaldo);
        if ($result_delete_saldo) {
          $nombre_cliente = $this->finanzas->get_nombre_cliente($idcliente);
          $txt_log = "Saldo eliminado (" . $nombre_cliente[0]->nombre . ") monto:MX$" . $data_saldo[0]->monto;
          $insert_log = $this->model_log->insert_log($idcliente, "saldos", $txt_log, $_SESSION['id_usuario8291']);
          echo 1;
        } else {
          echo 2;
        }
      } else {
        echo 2;
      }
    }
  }
  public function new_saldo()
  {
    $permiso = $this->seguridad->Acceso('CREAR_Y_ELIMINAR_SALDOS', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $data['idcliente'] = $this->request->getGet('idcliente');
      return view('clientes/lista_clientes/taps/sub_taps/utils/modal_new_saldo.php', $data);
    }
  }
  public function save_new_saldo()
  {
    if ($this->seguridad->Acceso('CREAR_Y_ELIMINAR_SALDOS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $idcliente = $this->request->getPost('saldo[iduser]');
      $monto = $this->request->getPost('saldo[monto]');
      $descripcion = $this->request->getPost('saldo[descripcion]');
      $data_saldo = [
        'iduser' => $idcliente,
        'idorigen' => 0,
        'iddestino' => 0,
        'estado' => 'no cobrado',
        'monto' => $monto,
        'fecha' => date('Y-m-d'),
        'descripcion' => $descripcion,
      ];
      $result_new_saldo = $this->model_facturacion->save_new_saldo($data_saldo);
      if ($result_new_saldo) {
        $nombre_cliente = $this->finanzas->get_nombre_cliente($idcliente);
        $txt_log = "Nuevo saldo Agregado (" . $nombre_cliente[0]->nombre . ") monto: MX$" . $monto;
        $insert_log = $this->model_log->insert_log($idcliente, "saldos", $txt_log, $_SESSION['id_usuario8291']);
        echo 1;
      } else {
        echo 2;
      }
    }
  }
}
