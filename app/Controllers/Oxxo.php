<?php

namespace App\Controllers;
require_once './app/Libraries/conekta-php-master/lib/Conekta.php';

use SMSGatewayMe\Client\ApiClient;
use SMSGatewayMe\Client\Configuration;
use SMSGatewayMe\Client\Api\MessageApi;
use SMSGatewayMe\Client\Model\SendMessageRequest;
use SMSGatewayMe\Client\Api\CallbackApi;
use SMSGatewayMe\Client\Model\CreateCallbackRequest;


use App\Libraries\Class_seguridad;
use App\Libraries\Class_permiso_sucursal;
use App\Models\Model_oxxo;
use App\Models\Model_selectores;
use App\Models\Model_configuracion;
use App\Models\Model_log;
use App\Models\Model_finanzas;
use App\Models\Model_clientes;
use PEAR2\Net\RouterOS;


class Oxxo extends BaseController
{
  var $model_oxxo;
  var $model_selectores;
  var $model_configuracion;
  var $model_log;
  var $permiso_sucursal;
  var $seguridad;
  var $finanzas;
  var $model_clientes;
  var $api_key_conekta;

  public function __construct()
  {
    $this->model_oxxo = new Model_oxxo();
    $this->model_clientes = new Model_clientes();
    $this->model_selectores = new Model_selectores();
    $this->model_configuracion = new Model_configuracion();
    $this->model_log = new Model_log();
    $this->permiso_sucursal = new Class_permiso_sucursal();
    $this->seguridad = new Class_seguridad();
    $this->finanzas = new Model_finanzas();
    $this->api_key_conekta = config('Integraciones')->conektaPrivateKey;
    date_default_timezone_set('America/Mexico_City');
    session();
  }


    public function generate_reference()
  {   

      $idfactura=$this->request->getGet('fc');
      $all_items = $this->finanzas->consulta_factura_user($idfactura);
      if($all_items[0]->oxxo_referencia=="" && $all_items[0]->oxxo_id==""){

         $productos = $this->finanzas->get_items_factura($idfactura);
      \Conekta\Conekta::setApiKey($this->api_key_conekta);
      \Conekta\Conekta::setApiVersion("2.0.0");
         $total = str_replace ( ".", '', $all_items[0]->total);
         $data = array();
         foreach ($productos as $item) {
           $subtotal = str_replace ( ".", '', $item->cantidad);
           $unidades = str_replace ( ".", '', $item->unidades);
            array_push($data, [
                'name'        => $item->descripcion,
                'unit_price'  => (int)$subtotal,
                'quantity'    => (int)$unidades
            ]);
        }

         
      $valid_order =
    [
        'line_items'=> $data,
        'currency' => 'mxn',
        'metadata' => ['test' => 'extra info'],
        'charges'  => [
            [
                'payment_method' => [
                    'type'       => 'oxxo_cash',
                    'expires_at' => strtotime(date("Y-m-d H:i:s")) + "5255998"
                ],
                'amount' => (int)$total,
            ]
        ],
        'currency' => 'mxn',
        'customer_info' => [
            'name'  => $all_items[0]->nombre,
            'phone' => '9991898989',
            'email' => 'jmlopez017@gmail.com',
        ]
    ];

 

try {
  

  $order = \Conekta\Order::create($valid_order);
  $result=json_decode($order);
  $id_order=$result->id;
  $tipo_pago= $result->charges[0]->payment_method->service_name;
  $referencia= $result->charges[0]->payment_method->reference;
  $barcode= $result->charges[0]->payment_method->barcode_url;  
  $save=$this->model_oxxo->update_reference($idfactura,$id_order,$referencia,$barcode);

  $this->pdf_reference($idfactura);

   
  
} catch (\Conekta\ProcessingError $e){
   echo $e->getMessage();
} catch (\Conekta\ParameterValidationError $e){
  echo $e->getMessage();
}

      } ///fin if referencia e id oxxo no es nulo
      else{
         $this->pdf_reference($idfactura);
      }
}

 public function pdf_reference($idfactura)
  {
    
        $all_items = $this->finanzas->consulta_factura_user($idfactura);
        $nombre_formato = "referencia_oxxo";
        $oxxo_referencia="";
        $arr1 = str_split($all_items[0]->oxxo_referencia);
        for ($i=0; $i < count($arr1); $i++) {
           
           if($i==3 || $i==7 || $i==11 || $i==15 ){
             $oxxo_referencia.=$arr1[$i]."-";
           }
           else{
            $oxxo_referencia.=$arr1[$i];
           } 
           
        }
        $data["idfactura"]=$idfactura;
        $data["oxxo_referencia"]=$oxxo_referencia;
        $data["total"]=number_format($all_items[0]->total,2);
        $data["cliente"]=$all_items[0]->nombre;


         $config = [

        'format' => "a4"
        ];
        $mpdf = new \Mpdf\Mpdf($config);
        //echo view('clientes/lista_clientes/taps/sub_taps/utils/oxxo_pay', $data);
        $mpdf->WriteHTML(view('clientes/lista_clientes/taps/sub_taps/utils/oxxo_pay', $data));
        $mpdf->Output("./files/pdfs/" . $nombre_formato . ".pdf", "F"); // opens in browser
        $this->show($nombre_formato . ".pdf");
      
    
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

   public function listenerAction() {
   
        $body = file_get_contents('php://input');
        if($body){
           
              $event = json_decode($body);
              $charge = $event->data->object;
    // $save=$this->model_oxxo->update_webhook($event->id);
   // $charge_id = $charge->metadata->checkout_id;
              echo $charge->payment_status;
             echo $event->type;

          if($event->type=="order.paid"){
    	sleep(3);
    	$this->get_orden($charge->id."");
    	//$save=$this->model_oxxo->update_webhook($charge->id."");
            }
       }
    
  }

  public function get_orden($id_order){

    \Conekta\Conekta::setApiKey($this->api_key_conekta);
    \Conekta\Conekta::setApiVersion("2.0.0");
  	$order = \Conekta\Order::find($id_order);
    $order->capture();
    $result=json_decode($order);
    $status= $result->charges[0]->status;

   // print_r($result);

    if($status=="paid"){
     $paid_at= $result->charges[0]->paid_at;
     $paid_at=date("Y-m-d H:i:s", $paid_at);
     $amount= $result->charges[0]->amount;
     $comision= $result->charges[0]->fee;
    
     
     $row=$this->model_oxxo->get_factura_idorder($id_order);
     if($row){
       $idfactura=$row->id;
       $idcliente=$row->idcliente;
       $tipo=$row->tipo;
       $amount=substr_replace($amount, '.', -2,0);
       $comision=substr_replace($comision, '.', -2,0);
       $arrayName = array('pago' =>$paid_at ,"forma"=>"Oxxo Pay","estado"=>"pagado","cobrado"=>$amount);
       $update=$this->model_oxxo->update_factura($idfactura,$arrayName);
       if($update){
       	  $comision=
       	  $arrayName = array("nfactura"=>$idfactura,'idcliente' =>$idcliente,'fecha_pago' =>$paid_at ,"operador"=>0,
       	  	                 "meses"=>1,"forma_pago"=>"Oxxo Pay","cobrado"=>$amount,"comision"=>$comision,"descripcion_pago"=>"Efectivo Oxxo");
       	 $insert=$this->model_oxxo->insert_operacion($arrayName);
       	 if($insert){
       	 	$insert_log = $this->model_log->insert_log($idcliente, "facturas", "Pago registrado por Oxxo Pay", 0);
       	 	 $nuevo_saldo=0;
       	 	 if($row->total>$amount){
               $nuevo_saldo = $row->total - $amount;
               if ($nuevo_saldo != 0) {
               $data_saldo = [
              'iduser' => $idcliente,
              'idorigen'  => $idfactura,
              'estado'  => 'no cobrado',
              'monto'  => $nuevo_saldo,
              'fecha'  => date("Y-m-d"),
              'descripcion'  => 'Saldo generado Automático'
             ];
            $result_saldo = $this->model_oxxo->insert_nuevo_saldo($data_saldo);
          }
       	 	  }
       	 	
           
              if ($tipo == 2) {

              /*  $data_servicios = $this->model_oxxo->get_data_servicios($idcliente);
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
                    
                      $data_up = ["estado" => 'ACTIVO'];
                      $result_update_estado = $this->model_clientes->update_cliente($idcliente, $data_up);
                      $insert_log = $this->model_log->insert_log($idcliente, "facturas", "Servicio activado via sistema por pago Oxxo Pay",0);


                    }
                  }
                } */
                

              }
              
            
       	 }
       }

      }
    }
    
   // $save=$this->model_oxxo->update_webhook($status);

  }

  protected function prueba()
  {
      \Conekta\Conekta::setApiKey($this->api_key_conekta);
      \Conekta\Conekta::setApiVersion("2.0.0");

  
      $valid_order =
    [
        'line_items'=> [
            [
                'name'        => 'Internet 10 megas',
                'description' => 'Siempre Netium.',
                'unit_price'  => 30000,
                'quantity'    => 1,
                'sku'         => '108883',
                'category'    => 'Internet',
                'tags'        => ['telecom', 'internet']
            ],
              [
                'name'        => 'Reconexion',
                'description' => 'Reconexion por corte',
                'unit_price'  => 3000,
                'quantity'    => 1,
                'sku'         => '1983773',
                'category'    => 'Internet',
                'tags'        => ['telecom', 'internet']
            ]
        ],
        'currency' => 'mxn',
        'metadata' => ['test' => 'extra info'],
        'charges'  => [
            [
                'payment_method' => [
                    'type'       => 'oxxo_cash',
                    'expires_at' => strtotime(date("Y-m-d H:i:s")) + "360000"
                ],
                'amount' => 33000,
            ]
        ],
        'currency' => 'mxn',
        'customer_info' => [
            'name'  => 'Juan Lopez',
            'phone' => '9991160800',
            'email' => 'jmlopez016@gmail.com',
        ]
    ];

try {
  $order = \Conekta\Order::create($valid_order);
  print_r($order);
} catch (\Conekta\ProcessingError $e){
  echo $e->getMessage();
} catch (\Conekta\ParameterValidationError $e){
  echo $e->getMessage();
} 

}

 protected function oxxorecurrente()
  {
      \Conekta\Conekta::setApiKey($this->api_key_conekta);
      \Conekta\Conekta::setApiVersion("2.0.0");

      
try{
    $customer = \Conekta\Customer::create(
      array(
          'name'  => "fulanito",
          'email' => "fulanito@test.com",
          'phone' => "+5218181818181",
          'payment_sources' => array(
                array(
                'type' => "oxxo_recurrent"
                )
            )
      )
    );
    var_dump(json_encode($customer));
  } catch (\Conekta\ProcessingError $error){
    echo $error->getMessage();
  } catch (\Conekta\ParameterValidationError $error){
    echo $error->getMessage();
  } catch (\Conekta\Handler $error){
    echo $error->getMessage();
  }
  
     
}

 protected function update()
  {
      \Conekta\Conekta::setApiKey($this->api_key_conekta);
      \Conekta\Conekta::setApiVersion("2.0.0");

$order = \Conekta\Order::find("ord_2qV5ZnFEGevKwEpjp");
try {
	$order->update([
    'customer_info'=> [
    "name" => "Juan Lopez"
  ]
]);
	
} catch (\Conekta\ProcessingError $error){
    echo $error->getMessage();
  } catch (\Conekta\ParameterValidationError $error){
    echo $error->getMessage();
  } catch (\Conekta\Handler $error){
    echo $error->getMessage();
  }

     
}

  public function update_table()
  {
    

    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('SMS_ENVIADOS'));
      if ($tipoacceso > 0) {
        $inicio=$this->request->getGet('inicio') . " 00:00:00";
        $fin=$this->request->getGet('fin') . " 11:59:59";;
        $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal($_SESSION['sucursales']);
        $sms_pendientes = $this->model_sms->get_all_sms_pendientes($inicio, $fin, $permiso_sucursal);
        $smsconfig = $this->model_sms->get_config('2');
        $config = Configuration::getDefaultConfiguration();
        $config->setApiKey('Authorization', $smsconfig->userapi);
        $apiClient = new ApiClient($config);
        $messageClient = new MessageApi($apiClient);
        $data_sms=array();

        if (count($sms_pendientes) > 0) {
          foreach ($sms_pendientes as $value) {
            try {
              $message = $messageClient->getMessage($value->sms_id);
              if ($message->getStatus() == "sent") {
                $data_sms[] = [
                  "id" => $value->id,
                  "stado" => "enviado"
                ];
              }
            } catch (\Throwable $th) {
            }
          }
        }
        if (count($data_sms) > 0) {
          $this->model_sms->update_sms_pendientes($data_sms, 'id');
        }
        return json_encode(count($data_sms) > 0? $data_sms:array());
      }
    }
  }

  public function get_enviados_json()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('SMS_ENVIADOS'));
      if ($tipoacceso > 0) {

        $inicio = $this->request->getGet('inicio') . " 00:00:00";
        $final = $this->request->getGet('final') . " 11:59:59";;
        $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal($_SESSION['sucursales']);
        $sms_pendientes = $this->model_sms->get_all_sms_pendientes($inicio, $final, $permiso_sucursal);
        $smsconfig = $this->model_sms->get_config('2');
        $config = Configuration::getDefaultConfiguration();
        $config->setApiKey('Authorization', $smsconfig->userapi);
        $apiClient = new ApiClient($config);
        $messageClient = new MessageApi($apiClient);
        $data_sms=array();
        if (count($sms_pendientes)>0) {
          foreach ($sms_pendientes as $value) {
            try {
              $message = $messageClient->getMessage($value->sms_id);
              if ($message->getStatus()=="sent") {
                $data_sms[]=[
                  "id"=>$value->id,
                  "stado"=>"enviado"
                ];
              }
            } catch (\Throwable $th) {}
          }
          if (count($data_sms)>0) {
            $this->model_sms->update_sms_pendientes($data_sms,'id');
          }
        }
        $sms = $this->model_sms->get_all_sms($inicio, $final, $permiso_sucursal);
        $array_cliente = array();
        $estado = "";
        if ($sms) {
          foreach ($sms as $items) {
            $adjunto = "";
            $fechaalta = "";
            $status = "";
            $tool = "";
            $fechaalta = date('d/m/Y - h:i a', strtotime($items->enviado));
            if ($items->stado == "enviado") {
              $status = "<span class='badge badge-success'>" . strtoupper($items->stado) . "</span>";
            }
            if ($items->stado == "en espera") {
              $status = "<span class='badge badge-warning'>" . strtoupper($items->stado) . "</span>";
            }

            if ($tipoacceso == 1) {
              $tool = '';
            }
            if ($tipoacceso == 2) {
              $tool = '<a data-toggle="tooltip" title="" class="btn btn-default btn-icon btn-sm" onclick="resendsms(' . $items->id . ')" data-original-title="Volver enviar"><i class="far fa-share-square" aria-hidden="true"></i></a>';
            }
            if ($tipoacceso == 3) {
              $tool = '<a data-toggle="tooltip" title="" class="btn btn-default btn-icon btn-sm" onclick="resendsms(' . $items->id . ')" data-original-title="Volver enviar"><i class="far fa-share-square" aria-hidden="true"></i></a>';
            }




            $array_item = array(
              "id" => $items->id,
              "sms_id" => $items->sms_id,
              "user" => $items->nombre,
              "sucursal" => '<span style="color:' . $items->color_su . ';border: 1px solid ' . $items->color_su . ';padding: 2px 5px;border-radius: 5px;text-transform: uppercase;font-size: 9.5px;font-weight: 700;">' . $items->sucursal . '</span>',
              "fecha" => $fechaalta,
              "ndestino" => $items->ndestino,
              "estado" => $status,
              "mensaje" => nl2br($items->mensaje),
              "log" => $items->error,
              "tool" => $tool
            );
            array_push($array_cliente, $array_item);
          }
        }
        echo '{"data":' . json_encode($array_cliente) . '}';
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function modal_new_sms()
  {
    if ($this->seguridad->Acceso('SMS_ENVIADOS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $where_permiso_sucursal_select = $this->permiso_sucursal->get_permiso_sucursal_select($_SESSION['sucursales']);
      $sucursales = $this->model_selectores->get_all_sucursales_by_user($where_permiso_sucursal_select);
      $servers = $this->model_selectores->get_all_nodos_sms();
      $emisores = $this->model_selectores->get_all_emisores_sms();
      $data['sucursales'] = $sucursales ? $sucursales : array();
      $data['servers'] = $servers ? $servers : array();
      $data['emisores'] = $emisores ? $emisores : array();
      return view('sms/utils/modal_new_sms', $data);
    }
  }

  public function find_clients()
  {
    if ($this->seguridad->Acceso('SMS_ENVIADOS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getPost('id');
      $estado = $this->request->getPost('estado');
      $clientes = $this->model_sms->find_clientes($id, $estado);
      if ($clientes) {
        foreach ($clientes as $value) {
          $value->movil =  str_replace(", ", ",", preg_replace("([^0-9]\,)", "", $value->movil));
        }
      }
      $data['salida'] = $clientes ? $clientes : array();
      $data['estado'] = $clientes ? "success" : "error";
      return json_encode($data);
    }
  }
  public function add_client()
  {
    if ($this->seguridad->Acceso('SMS_ENVIADOS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $term = $this->request->getGet('term');
      $clientes = $this->model_sms->get_cliente($term);
      if ($clientes) {
        foreach ($clientes as $value) {
          $value->movil =  str_replace(", ", ",", preg_replace("([^0-9]\,)", "", $value->movil));
        }
      }
      return json_encode($clientes ? $clientes : array());
    }
  }
  public function new_sms()
  {
    if ($this->seguridad->Acceso('SMS_ENVIADOS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $smsestado = $this->request->getPost('smsestado');
      $grupo = $this->request->getPost('grupo');
      $smsadd = $this->request->getPost('smsadd[]');
      $newsms = $this->request->getPost('newsms');
      $smsconfig = $this->model_sms->get_config('2');
      $clientes = array();
      $data_sms = array();
      if ($smsconfig) {
        $config = Configuration::getDefaultConfiguration();
        $config->setApiKey('Authorization', $smsconfig->userapi);
        $apiClient = new ApiClient($config);
        $messageClient = new MessageApi($apiClient);
        foreach ($smsadd as $key => $value) {
          $clientes[] = $key;
          if (strpos($value, ',')) {
            $numeros = explode(",", $value);
          } else {
            $numeros = str_split($value, 10);
          }
          foreach ($numeros as $numero) {
            if (strlen($numero) == 10) {
              $mensajesClientes[] = $key;
              $numerosSms[] = $numero;
              $data_sms[] = new SendMessageRequest([
                'phoneNumber' => $numero,
                'message' => $newsms,
                'deviceId' => $smsconfig->idequipo
              ]);
            }
          }
        }
        $sendMessages = $messageClient->sendMessages($data_sms);
        if ($sendMessages) {
          $data_insert = array();
          foreach ($sendMessages as $key => $value) {
            $data[] = [
              'user' => $mensajesClientes[$key],
              'enviado'  => date("Y-m-d H:i:s"),
              'ndestino'  => $numerosSms[$key],
              'stado'  => $value->getStatus() == "pending" ? "en espera" : "enviado",
              'mensaje'  => $value->getMessage(),
              'proveedor'  => 2,
              'sms_id'  => $value->getId()
            ];
          }
          $insert_sms = $this->model_sms->insert_sms($data);
          foreach ($clientes as $value) {
            $txt_log = "Nuevo sms creado - {{cliente}}(" . $newsms . ")";
            $this->model_log->insert_log($value, "sms", $txt_log, $_SESSION['id_usuario8291']);
          }
        }
        return $sendMessages ? "1" : "2";
      } else {
        echo 2;
      }
    }
  }
  public function reenviar()
  {
    if ($this->seguridad->Acceso('SMS_ENVIADOS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getPost('id');
      $sms = $this->model_sms->get_sms($id);
      $smsconfig = $this->model_sms->get_config('2');
      $sendMessages = false;
      if ($smsconfig and $sms) {
        try {
          $config = Configuration::getDefaultConfiguration();
          $config->setApiKey('Authorization', $smsconfig->userapi);
          $apiClient = new ApiClient($config);
          $messageClient = new MessageApi($apiClient);
          $data_sms[] = new SendMessageRequest([
            'phoneNumber' => $sms->ndestino,
            'message' => $sms->mensaje,
            'deviceId' => $smsconfig->idequipo
          ]);
          $sendMessages = $messageClient->sendMessages($data_sms);
        } catch (\Throwable $th) {
          echo 3;
          return;
        }
        if ($sendMessages) {
          $txt_log = "Mensaje reenviado ID: $id a el cliente {{cliente}}";
          $this->model_log->insert_log($sms->user, "sms", $txt_log, $_SESSION['id_usuario8291']);
        }
      }
      echo $sendMessages ? 1 : 2;
    }
  }
}








/* $api_instance = new CallbackApi();
$callback = new CreateCallbackRequest(); // \SMSGatewayMe\Client\Model\CreateCallbackRequest | callback to create

try {
  $result = $api_instance->createCallback($callback);
  print_r($result);
} catch (\Throwable $e) {
  echo 'Exception when calling CallbackApi->createCallback: ',
    $e->getMessage(),
    "\n";
}
 */