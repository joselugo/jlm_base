<?php

namespace App\Controllers;


use SMSGatewayMe\Client\ApiClient;
use SMSGatewayMe\Client\Configuration;
use SMSGatewayMe\Client\Api\MessageApi;
use SMSGatewayMe\Client\Model\SendMessageRequest;
use SMSGatewayMe\Client\Api\CallbackApi;
use SMSGatewayMe\Client\Model\CreateCallbackRequest;


use App\Libraries\Class_seguridad;
use App\Libraries\Class_permiso_sucursal;
use App\Models\Model_clientes;
use App\Models\Model_selectores;
use App\Models\Model_configuracion;
use App\Models\Model_log;
use App\Models\Model_sms;


class Sms extends BaseController
{
  var $model_clientes;
  var $model_selectores;
  var $model_configuracion;
  var $model_log;
  var $permiso_sucursal;
  var $seguridad;
  var $model_sms;

  public function __construct()
  {
    $this->model_clientes = new Model_clientes();
    $this->model_selectores = new Model_selectores();
    $this->model_configuracion = new Model_configuracion();
    $this->model_log = new Model_log();
    $this->permiso_sucursal = new Class_permiso_sucursal();
    $this->seguridad = new Class_seguridad();
    $this->model_sms = new Model_sms();
    date_default_timezone_set('America/Mexico_City');
    session();
  }

  public function enviados()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $where_permiso_sucursal_select = $this->permiso_sucursal->get_permiso_sucursal_select($_SESSION['sucursales']);
      $sucursales = $this->model_selectores->get_all_sucursales_by_user($where_permiso_sucursal_select);
      $sucursales ? $data['sucursales'] = $sucursales : $data['sucursales'] = array();
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('SMS_ENVIADOS'));
      if ($tipoacceso > 0) {
        $month = date("m");
        $year = date("Y");
        $ultimo_dia = date("d", (mktime(0, 0, 0, $month + 1, 1, $year) - 1));
        $fecha_inicio = date("Y") . "-" . date("m") . "-01";
        $fecha_fin = date("Y") . "-" . date("m") . "-" . $ultimo_dia;
        //  $fecha_fin2 = date('Y-m-d', strtotime($fecha_fin." + 1 day"));
        $data['tipoacceso'] = $tipoacceso;
        $data['inicio'] = $fecha_inicio;
        $data['fin'] = $fecha_fin;
        echo view('sms/enviados', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
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