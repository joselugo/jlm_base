<?php

namespace App\Controllers;


use CodeIgniter\Controller;

use App\Libraries\Class_seguridad;
use PEAR2\Net\RouterOS;
use PhpParser\Node\Expr\PostDec;
use App\Models\Model_clientes;

/*
use App\Libraries\Class_permiso_sucursal;
use App\Libraries\Decodificar;
use App\Libraries\Ciqrcode;
use App\Models\Model_selectores;
use App\Models\Model_configuracion;
use App\Models\Model_log;
use App\Models\Model_ticket;
use App\Models\Model_finanzas;
use App\Models\Model_almacen;
*/

class Herramientas_mikrotik extends BaseController
{
  /*

  var $model_selectores;
  var $model_configuracion;
  var $model_log;
  var $permiso_sucursal;
  var $permiso_nodo;
  var $ciqrcode;
  var $nmodel_log;
  var $model_ticket;
  var $almacen;
  var $decodificar; */
  var $model_trafico;
  var $converter;
  var $seguridad;
  var $model_clientes;


  public function __construct()
  {
    /*     $this->almacen = new Model_almacen();
    $this->decodificar = new Decodificar();
    $this->model_selectores = new Model_selectores();
    $this->model_configuracion = new Model_configuracion();
    $this->model_log = new Model_log();
    $this->permiso_sucursal = new Class_permiso_sucursal();
    $this->permiso_nodo = new Class_permiso_sucursal();
    $this->model_ticket = new Model_ticket();
    $this->finanzas = new Model_finanzas();
    $this->model_trafico = new Model_trafico();
    $this->converter = new converter();
    */
    $this->seguridad = new Class_seguridad();
    $this->model_clientes = new Model_clientes();
    date_default_timezone_set('America/Mexico_City');
    session();
  }
  public function modal_ping()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $idservicio = $this->request->getGet('id');
      $servicio = $this->model_clientes->get_servicio($idservicio);
      if (!$servicio) {
        echo 'error al cargar';
        return null;
      } else {
        $servicio = $servicio[0];
      }
      $server = $this->model_clientes->get_server($servicio->nodo);
      if (!$server) {
        echo 'error al cargar';
        return null;
      } else {
        $server = $server[0];
      }
      try {
        $client = new RouterOS\Client($server->ip, $server->usuario, $server->contrasena, $server->puerto);
      } catch (\Throwable $th) {
        echo 2;
        return null;
      }
      $util = new RouterOS\Util($client);
      $util->setMenu('/interface');
      $select = '<option value="">Automático</option>';
      foreach ($util->getAll() as $item) {
        $interface = $item->getProperty('name');
        $select .= '<option value="' . $interface . '">' . $interface . '</option>';
      }
      $data['interfaces'] = $select;
      $data['nodo'] = $servicio->nodo;
      $data['ip'] = $servicio->ip;
      return view('clientes/lista_clientes/taps/sub_taps/utils/modal_ping', $data);
    }
  }
  public function ping()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $ip = $this->request->getPost('ip');
      $nodo = $this->request->getPost('nodo');
      $interface = $this->request->getPost('interface');
      $paquete = $this->request->getPost('paquete');
      $arp = $this->request->getPost('arp');
      $server = $this->model_clientes->get_server($nodo);
      if ($server) {
        $server = $server[0];
      } else {
        echo 2;
        return null;
      }
      try {
        $client = new RouterOS\Client($server->ip, $server->usuario, $server->contrasena, $server->puerto);
      } catch (\Throwable $th) {
        echo "<h4>No se pudo hacer la consulta.</h4>";
        return null; // sin conexión $client no existe (antes: error 500 más abajo)
      }


      try {

        $addRequest = new RouterOS\Request('/ping');
        $addRequest->setArgument('address', $ip);
        $addRequest->setArgument('count', $paquete);
        if ($arp != 'no') $addRequest->setArgument('arp-ping', 'yes');
        if ($interface != '') $addRequest->setArgument('interface', $interface);
        $responses = $client->sendSync($addRequest);
        $client->loop();
        $body = "";
        foreach ($responses as $key => $value) {
          if ($key != count($responses) - 1) {
            if ($client->sendSync($addRequest)->getType() == RouterOS\Response::TYPE_FINAL) {
            } else {
              $id = $value->getProperty('seq');
              $status = $value->getProperty('status');
              $host = $value->getProperty('host');
              $size = $status == 'timeout' ? '---' : $value->getProperty('size');
              $ttl = $status == 'timeout' ? '---' : $value->getProperty('ttl') . ' B';
              $tiempo = $status == 'timeout' ? 'Tiempo agotado.' : $value->getProperty('time');
              $clase = $status == 'timeout' ? 'pingdown' : 'pingup';
              $body .= '
                <tr>
                  <td class="' . $clase . '">' . $id . '</td>
                  <td class="' . $clase . '">' . $host . '</td>
                  <td class="' . $clase . '">' . $size . '</td>
                  <td class="' . $clase . '">' . $ttl . '</td>
                  <td class="' . $clase . '">' . $tiempo . '</td>
                </tr>';
            }
          }
        }
        echo $body;
      } catch (RouterOS\RouterErrorException $e) {
        if ($e->getCode() === RouterOS\RouterErrorException::CODE_GET_ERROR) {
          $e->getResponses()->getProperty('message');
        } else {
          echo $e->getResponses()->getProperty('message');
        }
      }
    }
  }
  public function monitor_traffic()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $idservicio = $this->request->getPost('servicio');
      $servicio = $this->model_clientes->get_servicio($idservicio);
      if (!$servicio) {
        echo 'error al cargar';
        return null;
      } else {
        $servicio = $servicio[0];
      }
      $server = $this->model_clientes->get_server($servicio->nodo);
      if (!$server) {
        echo 'error al cargar';
        return null;
      } else {
        $server = $server[0];
      }
      try {
        $client = new RouterOS\Client($server->ip, $server->usuario, $server->contrasena, $server->puerto);
      } catch (\Throwable $th) {
        echo 2;
        return null;
      }
      $util = new RouterOS\Util($client);
      $util->setMenu('/queue simple');
      $query = $util->find(RouterOS\Query::where('name', "Mkws_queue_" . $idservicio));
      if ($query == "") {
        echo 2;
        return null;
      }
      $data = $util->get($query);
      $dato = explode('/', $data['rate']);
      $info['descarga'] = round(($dato[1] / 1000) / 1024, 2);
      $info['subida'] = round(($dato[0] / 1000) / 1024, 2);
      $info['fecha'] = date('Y-m-d H:i:s');
      $fecha = date('h:i:s A');
      $ip = $data['target'];
      $plan = $data['max-limit'];
      $plan = explode('/', $plan);
      $plan = (($plan[0] / 1000) / 1000) . " M/" . (($plan[1] / 1000) / 1000) . " M";
      $tx = $info['descarga'];
      $tr =  $info['subida'];
      $paquetes = $data['packet-rate'];
      $paquetes = explode('/', $paquetes);
      $txpaquetes = $paquetes[1];
      $trpaquetes = $paquetes[0];
      $table = "<tr>
        <td>$fecha</td>
        <td>$ip</td>
        <td>$plan</td>
        <td>$tx</td>
        <td>$tr</td>
       </tr>";
      $code = preg_replace("[\n]", "", $table);
      $code = preg_replace("[\r]", "", $code);
      $info['tabla'] = $code;
      return json_encode($info);
    }
  }
  public function modal_trafico()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getGet('id');
      $data['servicio'] = $id;
      return view('clientes/lista_clientes/taps/sub_taps/utils/modal_trafico', $data);
    }
  }
}
