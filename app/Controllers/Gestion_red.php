<?php

namespace App\Controllers;


use PEAR2\Net\RouterOS;
use CodeIgniter\Controller;
use App\Libraries\Class_seguridad;
use App\Models\Model_gestion_red;
use App\Models\Model_log;
use App\Models\Model_selectores;
use App\Libraries\Decodificar;

class Gestion_red extends BaseController
{
  public function __construct()
  {
    /* $this->model_facturacion = new Model_finanzas_facturacion();
    $this->almacen = new Model_almacen();
    $this->model_selectores = new Model_selectores();
    $this->permiso_sucursal = new Class_permiso_sucursal();
    $this->finanzas = new Model_finanzas();
    $this->formatos = new Model_formatos();
    $this->calcular = new Calcular(); */
    $this->model_log = new Model_log();
    $this->seguridad = new Class_seguridad();
    $this->model_gestion_red = new Model_gestion_red();
    $this->model_selectores = new Model_selectores();
    date_default_timezone_set('America/Mexico_City');
    $this->decodificar = new Decodificar();
    session();

  }

  public function monitoreo()
  {
    $permiso= $this->seguridad->Acceso('MONITOREO', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $data['permiso']=$permiso;
      return view('gestion_red/monitoreo',$data);
    }
  }

  public function addresslist()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('ADDRESSLIST'));
      if ($tipoacceso > 0) {
        return view('gestion_red/addresslist/index');
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
  public function get_emisores_json()
  {
    $permiso= $this->seguridad->Acceso('MONITOREO', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      /*       $where_permiso_sucursal = $permiso_sucursal->get_permiso_sucursal_facturas($_SESSION['sucursales']); */
      $emisores = $this->model_gestion_red->get_emisores();
      if ($emisores) {
        foreach ($emisores as $value) {
          $value->estado_netium == 1 ? $value->estado = '<span class="label label-pill label-success">EN LINEA</span>' : $value->estado = '<span class="label label-pill label-danger">DESCONECTADO</span>';
          $value->servicios = "<span class=\"badge label-secondary\">" . $value->servicios . "</span>";
          $value->online = "<span class=\"badge label-success\">" . $value->online . "</span>";
          $value->offline = "<span class=\"badge label-danger\">" . $value->offline . "</span>";
          $value->usuarios = "<span class=\"badge label-secondary\">" . $value->usuarios . "</span>";
          $value->activos = "<span class=\"badge label-success\">" . $value->activos . "</span>";
          $value->suspendidos = "<span class=\"badge label-danger\">" . $value->suspendidos . "</span>";
          $idemisor = $value->id;
          $value->tool = "
            <a data-toggle=\"tooltip\" style=\"margin-right: 5px;\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"getmodal('gestion_red/modal_ping?idemisor=$idemisor','PING','lg');\" data-original-title=\"Enviar ping\">
              <i class=\"fas fa-exchange-alt\"></i>
            </a>
            <a style=\"margin-right: 5px;\" data-toggle=\"tooltip\" class=\" btn btn-default btn-icon btn-sm\" href=\"#gestion_red/editar_emisor?idemisor=$idemisor\" data-original-title=\"Editar\">
              <i class=\"far fa-edit\"></i>
            </a>
            <a style=\"margin-right: 5px;\" data-toggle=\"tooltip\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"getmodal('gestion_red/modal_grafica_ping?idemisor=$idemisor','Grafica','lg')\" data-original-title=\"Gráfico ping\">
              <i class=\"fas fa-chart-bar\"></i>
            </a>";

            if ($permiso>=2) {
              $value->tool.="<a style=\"margin-right: 5px;\" data-toggle=\"tooltip\" class=\" btn btn-default btn-icon btn-sm\" onclick=\"delete_emisor('$idemisor')\" data-original-title=\"Eliminar\">
                <i class=\"far fa-trash-alt\"></i>
              </a>";
            }
            $value->tool="<div style=\"display:inline-flex\">$value->tool</div>";
        }
        $data['data'] = $emisores;
      } else {
        $data['data'] = array();
      }
      return json_encode($data);
    }
  }
  public function modal_ping()
  {
    if ($this->seguridad->Acceso('MONITOREO', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $idemisor = $this->request->getGet('idemisor');
      $emisor = $this->model_gestion_red->get_emisor($idemisor);
      $emisor = $emisor->ip;
      exec("ping -s 56 -c 8 $emisor", $ouput);
      $data['paquetes'] = $ouput;
      return view('gestion_red/modal_ping', $data);
    }
  }
  public function modal_grafica_ping()
  {
    if ($this->seguridad->Acceso('MONITOREO', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $idemisor = $this->request->getGet('idemisor');
      $data['idemisor'] = $idemisor;
      return view('gestion_red/modal_grafica_ping', $data);
    }
  }
  public function generar_grafica()
  {
    if ($this->seguridad->Acceso('MONITOREO', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $idemisor = $this->request->getGet('idemisor');
      $tipo = $this->request->getGet('tipo');
      if ($tipo == "hora") {
        $inicio = "now-1h";
        $conf_etiquetas = "MINUTE:2:MINUTE:10:MINUTE:10:0:%R";
      }
      if ($tipo == "dia") {
        $inicio = "end-1d";
        $conf_etiquetas = "HOUR:1:HOUR:2:HOUR:2:0:%R";
      }
      if ($tipo == "semana") {
        $inicio = "end-1w";
        $conf_etiquetas = "HOUR:12:DAY:1:DAY:1:0:'Dia-%d'";
      }
      if ($tipo == "mes") {
        $inicio = "end-1m";
        $conf_etiquetas = "DAY:1:WEEK:1:WEEK:1:0:'Semana-%V'";
      }
      if ($tipo == "año") {
        $inicio = "end-1y";
        $conf_etiquetas = "WEEK:1:MONTH:1:MONTH:1:0:'%m/%y'";
      }
      $emisor = $this->model_gestion_red->get_emisor($idemisor);
      if ($emisor) {
        $titulo = $emisor->nombre;
        $emisor = $emisor->ip;
        /* $this->ssh->write("/bin/bash /var/www/html/herramientas/grafico/create_graph.sh $inicio ping_db_id_$idemisor.rrd \"$titulo ($emisor)\" $conf_etiquetas \n");
        $html = $this->ssh->read(); */
        $path_cod= "/var/www/html/app/public/images/graficas/";
        $path_graf="/var/www/html/app/grafico/";
        $eh=shell_exec("rm ".$path_cod."latency_graph.png");
        $eh=shell_exec("/usr/bin/rrdtool graph ".$path_cod."latency_graph.png -w 600 -h 310 -a PNG --slope-mode --start $inicio --font DEFAULT:9: --title \"$titulo ($emisor)\" --watermark \"Generado por Netium\" --vertical-label \"Ping (ms)\" --right-axis-label \"Ping (ms)\" --lower-limit 0 --right-axis 1:0 --x-grid $conf_etiquetas --alt-y-grid --rigid DEF:roundtrip=" . $path_graf . "ping_db_id_$idemisor.rrd:rtt:MAX DEF:packetloss=" . $path_graf . "ping_db_id_$idemisor.rrd:pl:MAX CDEF:PLNone=packetloss,0,0,LIMIT,UN,UNKN,INF,IF CDEF:PL10=packetloss,1,10,LIMIT,UN,UNKN,INF,IF CDEF:PL25=packetloss,10,25,LIMIT,UN,UNKN,INF,IF CDEF:PL50=packetloss,25,50,LIMIT,UN,UNKN,INF,IF CDEF:PL100=packetloss,50,100,LIMIT,UN,UNKN,INF,IF AREA:roundtrip#9DFFFF:\"ms\" LINE0.8:roundtrip#0000FF GPRINT:roundtrip:AVERAGE:\"Minimo\: %5.2lf ms\" GPRINT:roundtrip:MIN:\"Promedio\: %5.2lf ms\" GPRINT:roundtrip:MAX:\"Maximo\: %5.2lf ms\" 2>&1");
        /* echo strpos($html, "931x519") ? 1 : 2; */
        echo $eh;
      }
    }
  }
  public function editar_emisor()
  {
    $permiso=$this->seguridad->Acceso('MONITOREO', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $idemisor = $this->request->getGet('idemisor');
      $emisor = $this->model_gestion_red->get_emisor_edit($idemisor);
      if ($emisor) {
        $emisor->oid = $this->decodificar->decodificar($emisor->oid);
        $data['emisor'] = $emisor;
        $data['permiso'] = $permiso;
        return view('gestion_red/editar_emisor', $data);
      }
    }
  }
  public function save_emisor()
  {
    if ($this->seguridad->Acceso('MONITOREO', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $data = array();
      $idemisor = $this->request->getPost('id');
      $emisores_nombre = $this->request->getPost('emisores[nombre]');
      $emisores_ip = $this->request->getPost('emisores[ip]');
      $emisores_debug = $this->request->getPost('emisores[debug]');
      $emisores_fabricante = $this->request->getPost('emisores[fabricante]');
      $emisores_equipo = $this->request->getPost('emisores[equipo]');
      $emisores_user = $this->request->getPost('emisores[user]');
      $emisores_password = $this->request->getPost('emisores[password]');
      $emisores_access = $this->request->getPost('emisores[access]');
      $emisores_lglt = $this->request->getPost('emisores[lglt]');
      $emisores_snmp = $this->request->getPost('emisores[snmp]');
      $emisores_snmp_comunidad = $this->request->getPost('emisores[snmp_comunidad]');
      $emisores_version = $this->request->getPost('emisores[snmp_version]');

      $emisores_correos = $this->request->getPost('emisores[correos]');
      $emisores_alert = $this->request->getPost('emisores[alert]');
      $emisores_alert2 = $this->request->getPost('emisores[alert2]');
      $emisores_telefonos = $this->request->getPost('emisores[telefonos]');

      $snmp_estaciones = $this->request->getPost('snmp[estaciones]');
      $snmp_cpu = $this->request->getPost('snmp[cpu]');
      $snmp_memoria_total = $this->request->getPost('snmp[memoria-total]');
      $snmp_memoria_usada = $this->request->getPost('snmp[memoria-usada]');
      $snmp_ccq = $this->request->getPost('snmp[ccq]');
      $snmp_ruido = $this->request->getPost('snmp[ruido]');

      if ($emisores_nombre !== null) {
        $data['nombre'] = $emisores_nombre;
        $data['ip'] = $emisores_ip;
        $data['debug'] = $emisores_debug;
        $data['fabricante'] = $emisores_fabricante;
        $data['equipo'] = $emisores_equipo;
        $data['user'] = $emisores_user;
        $data['password'] = $emisores_password;
        $data['access'] = $emisores_access;
        $data['lglt'] = $emisores_lglt;
        $data['snmp'] = $emisores_snmp;
        $data['snmp_comunidad'] = $emisores_snmp_comunidad;
        $data['snmp_version'] = $emisores_version;
      }
      if ($emisores_correos !== null) {
        $data['correos'] = $emisores_correos;
        $data['alert'] = $emisores_alert;
        $data['alert2'] = $emisores_alert2;
        $data['telefonos'] = $emisores_telefonos;
      }
      if ($snmp_estaciones !== null) {
        $data_convert = array();
        $data_convert['estaciones'] = $snmp_estaciones;
        $data_convert['cpu'] = $snmp_cpu;
        $data_convert['memoria-total'] = $snmp_memoria_total;
        $data_convert['memoria-usada'] = $snmp_memoria_usada;
        $data_convert['ccq'] = $snmp_ccq;
        $data_convert['ruido'] = $snmp_ruido;
        $data['oid'] = $this->decodificar->codificar_post($data_convert);
      }


      $result_update = $this->model_gestion_red->update_emisor($idemisor, $data);
      if ($result_update) {
        echo 1;
      } else {
        echo 2;
      }
    }
  }
  public function delete_emisor()
  {
    if ($this->seguridad->Acceso_full('MONITOREO', 'ELIMINAR', $_SESSION['id_usuario8291'])) {
      $idemisor = $this->request->getPost('id');
      $result_delete = $this->model_gestion_red->delete_emisor($idemisor);
      if ($result_delete) {
        shell_exec("rm /var/www/html/herramientas/grafico/ping_db_id_$idemisor.rrd");
      }
      echo $result_delete ? 1 : 2;
    }
  }
  public function servers()
  {
    $permiso= $this->seguridad->Acceso('SERVER', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $data['permiso']=$permiso;
      return view('gestion_red/routers',$data);
    }
  }
  public function get_servers()
  {
    $permiso= $this->seguridad->Acceso_full('SERVER', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $data = array();
      $servers = $this->model_gestion_red->get_servers();
      if ($servers) {
        foreach ($servers as $value) {
          $id = $value->id;
          $nombre = base64_encode($value->nodo);
          $velocidad = $value->velocidad == "queues" ? "Api + Colas simples Estaticas" : "PCQ + Addresslist";
          $value->nombre = "<a href=\"gestion_red/asa\">" . $value->nodo . "</a> <small style=\"display: block;\" class=\"text-warning\">" . $velocidad . "</small>";
          $clientes = $value->clientes;
          $value->clientes = "<span class=\"badge\">$value->clientes</span>";
          if ($value->estado == "CONECTADO") {
            $value->estado = "<span class=\"label label-pill label-success\">" . $value->estado . "</span>";
          }
          if ($value->estado == "DESCONECTADO") {
            $value->estado = "<span class=\"label label-pill label-danger\">" . $value->estado . "</span>";
          }
          $value->tool = "
          <a href=\"#gestion_red/edit_server?id=$id\" data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" data-original-title=\"Editar\">
            <i class=\"far fa-edit\"></i>
          </a>";
          if ($permiso>=3) {

            $value->tool .= "<a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"masive_create_queues('$id')\" data-original-title=\"Importacion masiva de reglas de control de trafico\">
                    <i class=\"fas fa-tachometer-alt\" ></i>
                  </a>
                  <a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"delete_router('$id','$nombre','$clientes')\" data-original-title=\"Eliminar\">
                    <i class=\"far fa-trash-alt\" aria-hidden=\"true\"></i>
                  </a>
                  ";
          }
        }

        $data['data'] = $servers;
      } else {
        $data['data'] = array();
      }
      return json_encode($data);
    }
  }
  public function new_server()
  {
    if ($this->seguridad->Acceso('SERVER', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      return view('gestion_red/modal_new_server');
    }
  }
  public function edit_server()
  {
    if ($this->seguridad->Acceso('SERVER', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getGet('id');
      $data['id'] = $id;
      return view('gestion_red/editar_server.php', $data);
    }
  }
  public function tab_general()
  {
    $permiso = $this->seguridad->Acceso('SERVER', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getGet('id');
      $server = $this->model_gestion_red->get_server($id);
      if ($server) {
        $data['server'] = $server;
        $data['permiso'] = $permiso;
        return view('gestion_red/tab_edit_general', $data);
      } else {
        echo "El servidor ha sidos eliminado";
      }
    }
  }

  public function save_server_general()
  {
    if ($this->seguridad->Acceso('SERVER', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getPost('id');
      $nodo = $this->request->getPost('nodo');
      $coordenadas = $this->request->getPost('coordenadas');
      $ip = $this->request->getPost('ip');
      $seguridad = $this->request->getPost('seguridad');
      $seguridad2 = $this->request->getPost('seguridad2');
      $radius_secret = $this->request->getPost('radius_secret');
      $radius_ip = $this->request->getPost('radius_ip');
      $user = $this->request->getPost('user');
      $password = $this->request->getPost('password');
      $velocidad = $this->request->getPost('velocidad');
      $ipvisitadas = $this->request->getPost('ipvisitadas');
      $modelo = "";
      $serial = "";
      $version = "";
      try {
        $client = new RouterOS\Client($ip, $user, $password);
      } catch (\Throwable $e) {
        echo 3;
        return;
      }
      $util = new RouterOS\Util($client);
      $util->setMenu('/system routerboard');
      foreach ($util->getAll() as $item) {
        $modelo = $item->getProperty('model');
        $serial = $item->getProperty('serial-number');
      }
      $util->setMenu('/system resource');
      foreach ($util->getAll() as $item) {
        $version = $item->getProperty('version');
      }

      $data_server = [
        'nodo' => $nodo,
        'coordenadas' => $coordenadas,
        'ip' => $ip,
        'seguridad' => $seguridad,
        'seguridad2' => $seguridad2,
        'secret_radius' => $radius_secret,
        'ip_radius' => $radius_ip,
        'usuario' => $user,
        'contrasena' => $password,
        'velocidad' => $velocidad,
        'version' => $version,
        'modelo' => $modelo,
        'serial' => $serial,
        /* 'ipvisitadas'=>$ipvisitadas */
      ];
      $result_update_server = $this->model_gestion_red->update_server($id, $data_server);
      if ($result_update_server) {
        echo 1;
      } else {
        echo 2;
      }
    }
  }
  public function tab_firewall()
  {
    if ($this->seguridad->Acceso('SERVER', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getGet('id');
      $data['id'] = $id;
      return view('gestion_red/tab_edit_firewall', $data);
    }
  }
  public function get_firewall()
  {
    if ($this->seguridad->Acceso('SERVER', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getGet('id');

      $data['data'] = array();
      return json_encode($data);
    }
  }
  public function tab_mikrotik()
  {
    if ($this->seguridad->Acceso('SERVER', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getGet('id');
      $server = $this->model_gestion_red->get_server($id);
      try {
        $client = new RouterOS\Client($server->ip, $server->usuario, $server->contrasena, $server->puerto);
      } catch (\Throwable $th) {
        echo 2;
        return null;
      }
      $util = new RouterOS\Util($client);
      $util->setMenu('/interface');
      $select = '';
      foreach ($util->getAll() as $item) {
        $interface = $item->getProperty('name');
        $select .= '<option value="' . $interface . '">' . $interface . '</option>';
      }
      $data['interfaces'] = $select;
      $data['id'] = $id;
      return view('gestion_red/tab_mikrotik', $data);
    }
  }
  public function snmp_mikrotik()
  {
    if ($this->seguridad->Acceso('SERVER', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getGet('id');
      $interface = $this->request->getGet('interface');
      $server = $this->model_gestion_red->get_server($id);
      $json = "";
      $core = "";
      $txt = "";
      $propiedades = array();
      $titulos = array(
        "Tiempo de actividad",
        "Version",
        "Tiempo de construccion",
        "Software de fabrica",
        "Memoria libre",
        "Memoria total",
        "UPC",
        "Cpu-count",
        "Frecuencia de la CPU",
        "Cpu-load",
        "Espacio libre en el disco duro",
        "Espacio-disco-total",
        "Write-sect-since-reboot",
        "Write-sec-total",
        "Bloques malos",
        "Nombre-arquitectura",
        "Nombre de la placa",
        "Plataforma "
      );
      try {
        $client = new RouterOS\Client($server->ip, $server->usuario, $server->contrasena, $server->puerto);
      } catch (\Throwable $th) {
        echo 2;
        return null;
      }
      $util = new RouterOS\Util($client);
      $util->setMenu('/system resource');
      $estado_mikrotik = $util->getAll();
      // $util->setMenu("/interface monitor-traffic interface=$interface once");
      $trequest2 = new RouterOS\Request('/interface monitor-traffic interface=' . $interface . ' once');
      $rx = $client->sendSync($trequest2)->getProperty('rx-bits-per-second');
      $tx = $client->sendSync($trequest2)->getProperty("tx-bits-per-second");



      $tx_rx = $util->getAll();
      foreach ($estado_mikrotik as $key => $item) {
        $load = $item->getProperty('cpu-load') . "%";
        $propiedades = array(
          $item->getProperty('uptime'),
          $item->getProperty('version'),
          $item->getProperty('build-time'),
          $item->getProperty('factory-software'),
          $item->getProperty('free-memory'),
          $item->getProperty('total-memory'),
          $item->getProperty('cpu'),
          $item->getProperty('cpu-count'),
          $item->getProperty('cpu-frequency'),
          "<span style='float:left;margin-right:3px'>: $load </span>
           <div class='progress progress-sm progress-striped active'>
              <div class='progress-bar progress-bar-success' style='width: $load'></div>
           </div>",
          $item->getProperty('free-hdd-space'),
          $item->getProperty('total-hdd-space'),
          $item->getProperty('write-sect-since-reboot'),
          $item->getProperty('write-sect-total'),
          $item->getProperty('bad-blocks'),
          $item->getProperty('architecture-name'),
          $item->getProperty('board-name'),
          $item->getProperty('platform'),
        );
      }
      foreach ($propiedades as $key => $value) {
        $json .= "
        <tr>
          <td align='right'>" . $titulos[$key] . "</td>
          <td> : " . $propiedades[$key] . "</td></tr>
        <tr>";
      }


      $data['core'] = round($rx / 1048576, 2);
      $data['resumen'] = $json;
      $data['txt'] = round($tx / 1048576, 2);
      return json_encode($data);
    }
  }
  public function tab_grafico_mikrotik()
  {
    if ($this->seguridad->Acceso('SERVER', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getGet('id');
      $server = $this->model_gestion_red->get_server($id);
      if ($server) {
        $data['link'] = $server->ip . ":" . $server->puerto_web;
        $data['id'] = $id;
        return view('gestion_red/tab_graficos', $data);
      } else {
        echo "El servidor no existe o fue eliminado";
      }
    }
  }
  public function tab_logmikrotik()
  {
    if ($this->seguridad->Acceso('SERVER', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getGet('id');
      $server = $this->model_gestion_red->get_server($id);
      $temp = array();
      try {
        $client = new RouterOS\Client($server->ip, $server->usuario, $server->contrasena, $server->puerto);
      } catch (\Throwable $th) {
        echo 2;
        return null;
      }
      $util = new RouterOS\Util($client);
      $util->setMenu('/log');
      $logs = $util->getAll();
      $count = count($logs) <= 100 ? count($logs) : 100;
      for ($i = 0; $i < $count; $i++) {
        $time = $logs[$i]->getProperty('time');
        $topics = $logs[$i]->getProperty('topics');
        $message = $logs[$i]->getProperty('message');
        $temp[] = [
          "fecha" => $time,
          "tipo" => $topics,
          "log" => $message
        ];
      }
      /*   foreach ($util->getAll() as $item) {
        $time=$item->getProperty('time');
        $topics=$item->getProperty('topics');
        $message=$item->getProperty('message');
        $temp[]=[
          "fecha"=>$time,
          "tipo"=>$topics,
          "log"=>$message
        ];
      } */
      $data['logs'] = $temp;
      return view('gestion_red/tab_log', $data);
    }
  }
  public function delete_server()
  {
    if ($this->seguridad->Acceso_full('SERVER', 'ELIMINAR', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getPost('id');
      $result_delete = $this->model_gestion_red->delete_server($id);
      echo $result_delete ? 1 : 2;
    }
  }

  public function tab_addresslist()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('ADDRESSLIST'));
      if ($tipoacceso > 0) {
        $data['servers'] = $this->model_selectores->get_all_nodos();
        return view('gestion_red/addresslist/tab_addresslist', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function tab_dhcpleases()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('ADDRESSLIST'));
      if ($tipoacceso > 0) {
        $data['servers'] = $this->model_selectores->get_all_nodos();
        return view('gestion_red/addresslist/tab_leases', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function get_addresslist()
  {

    $id = $this->request->getGet('id');
    $addresslist = array();
    if ($id != "-1") {
      $server = $this->model_gestion_red->get_server($id);
      try {
        $client = new RouterOS\Client($server->ip, $server->usuario, $server->contrasena, $server->puerto);
      } catch (\Throwable $th) {
        // echo 2;
        return '{"data":' . json_encode($addresslist) . '}';
      }
      $util = new RouterOS\Util($client);
      $util->setMenu('/ip firewall address-list');
      $items = $util->getAll();
      if ($items) {
        for ($i = 0; $i < count($items); $i++) {
          if ($items[$i]->getProperty('list') == "morosos") {
            $list = $items[$i]->getProperty('list');
            $address = $items[$i]->getProperty('address');
            $comment = null;
            $idservicio = -1;
            $comment = $items[$i]->getProperty('comment');
            if ($comment == null) {
            } else {
              $child = explode("_", $comment);
              $idservicio = $child[2];
            }
            $row_cliente = $this->model_gestion_red->get_cliente_by_servicio($idservicio);
            if ($row_cliente) {
              $cliente = $row_cliente->nombre;
            } else {
              $cliente = "Cliente retirado";
            }

            $addresslist[] = [
              'cliente' => $cliente,
              'idservicio' => $idservicio,
              "list" => $list,
              "address" => $address,
              "comment" => $comment,
              'tool' => "",
            ];
          }
        } ///fim for

      } //fin if items
    } ///fin if diferente de -1
    echo '{"data":' . json_encode($addresslist) . '}';
  }

  public function get_leases()
  {

    $id = $this->request->getGet('id');
    $addresslist = array();
    if ($id != "-1") {
      $server = $this->model_gestion_red->get_server($id);
      try {
        $client = new RouterOS\Client($server->ip, $server->usuario, $server->contrasena, $server->puerto);
      } catch (\Throwable $th) {
        // echo 2;
        return '{"data":' . json_encode($addresslist) . '}';
      }
      $util = new RouterOS\Util($client);
      $util->setMenu('/ip dhcp-server lease');
      $items = $util->getAll();

      if ($items) {
        for ($i = 0; $i < count($items); $i++) {
          $address = $items[$i]->getProperty('address');
          $mac_address = $items[$i]->getProperty('mac-address');
          $address_list = $items[$i]->getProperty('address-lists');
          $active_address = $items[$i]->getProperty('active-address');
          $active_mac_address = $items[$i]->getProperty('active-mac-address');
          $status = $items[$i]->getProperty('status');
          $dynamic = $items[$i]->getProperty('dynamic');
          $expires_after = $items[$i]->getProperty('expires-after');
          $row_cliente = $this->model_gestion_red->get_cliente_by_ip($address);
          if ($row_cliente) {
            $cliente = $row_cliente->nombre;
          } else {
            $cliente = "Cliente retirado";
          }

          $addresslist[] = [
            'cliente' => $cliente,
            'address' => $address,
            'mac-address' => $mac_address,
            'address-list' => $address_list,
            'active-address' => $active_address,
            'active-mac-address' => $active_mac_address,
            'status' => $status,
            'dynamic' => $dynamic,
            'expires-after' => $expires_after,
            'tool' => ""
          ];
        } ///fim for

      } //fin if items
    } ///fin if diferente de -1
    echo '{"data":' . json_encode($addresslist) . '}';
  }
  public function redes_ipv4()
  {
    $permiso= $this->seguridad->Acceso('REDES_IPV4', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $data['permiso']=$permiso;
      return view("gestion_red/redes_ipv4",$data);
    }
  }
  public function redes_ipv4_json()
  {
    $permiso= $this->seguridad->Acceso('REDES_IPV4', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $redes = $this->model_gestion_red->get_redes_ipv4();
      if ($redes) {
        foreach ($redes as $value) {
          $value->tool = $value->id;
          $value->permiso = $permiso;
          $total = $value->host;
          $uso = $value->uso;
          $porcentaje = round(($uso * 100) / $total, 2);
          $txt = "<td class=\" text-center\"><div class=\"progress progress-striped progress-sm active\" style=\"margin-bottom: 0px;\">
          <div class=\"progress-bar progress-bar-success\" style=\"width: $porcentaje%\"><span>$porcentaje% ($uso de $total)</span></div></div></td>";
          $value->uso = $txt;
        }
        $data['data'] = $redes;
      } else {
        $data['data'] = array();
      }
      return json_encode($data);
    }
  }
  public function modal_new_red_ipv4()
  {
    if ($this->seguridad->Acceso('REDES_IPV4', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $servers = $this->model_selectores->get_all_nodos();
      if ($servers) {
        $data['servers'] = $servers;
      } else {
        $data['servers'] = array();
      }
      return view("gestion_red/modal_nueva_red_ipv4", $data);
    }
  }
  public function save_new_red_ipv4()
  {
    if ($this->seguridad->Acceso('REDES_IPV4', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $red = $this->request->getPost('red');
      $cidr = $this->request->getPost('cidr');
      $nodo = $this->request->getPost('nodo');
      $tipo = $this->request->getPost('tipo');
      $nombre = $this->request->getPost('nombre');
      $data = $this->get_rangos($red, $cidr);
      $data['rangos'] = implode(",", $data['rangos']);
      $data['nodo'] = $nodo;
      $data['nombre'] = $nombre;
      $data['red'] = $red;
      $data['cidr'] = $cidr;
      $data['tipo'] = $tipo;
      $insert = $this->model_gestion_red->insert_new_red_ipv4($data);
      echo $insert ? 1 : 2;
    }
  }
  public function modal_edit_red_ipv4()
  {
    $permiso= $this->seguridad->Acceso('REDES_IPV4', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getGet('id');
      $red_ipv4 = $this->model_gestion_red->get_red_ipv4($id);
      if ($red_ipv4) {
        $servers = $this->model_selectores->get_all_nodos();
        if ($servers) {
          $data['servers'] = $servers;
        } else {
          $data['servers'] = array();
        }
        $data["red"] = $red_ipv4;
        $data["permiso"] = $permiso;
        return view("gestion_red/modal_edit_red_ipv4", $data);
      } else {
        echo "No se encontro la red seleccionada.";
      }
    }
  }
  public function save_edit_red_ipv4()
  {
    if ($this->seguridad->Acceso('REDES_IPV4', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $nombre = $this->request->getPost('nombre');
      $nombre2 = $this->request->getPost('nombre2');
      $id = $this->request->getPost('id');
      $red = $this->request->getPost('red');
      $cidr = $this->request->getPost('cidr');
      $nodo2 = $this->request->getPost('nodo2');
      $nodo = $this->request->getPost('nodo');
      $tipo2 = $this->request->getPost('tipo2');
      $tipo = $this->request->getPost('tipo');
      $txt = "";
      $txt .= $nombre <> $nombre2 ? " nombre: " . $nombre . " a " . $nombre2 : "";
      $txt .= $nodo <> $nodo2 ? " nodo: " . $nodo . " a " . $nodo2 : "";
      $txt .= $tipo <> $tipo2 ? " tipo: " . $tipo . " a " . $tipo2 : "";
      $data = [
        "nombre" => $nombre,
        "nodo" => $nodo,
        "tipo" => $tipo
      ];
      $update = $this->model_gestion_red->update_red_ipv4($id, $data);
      if ($update) {
        $this->model_log->insert_log(000000, "monitoreo", "Se edito la red IPv4 con ID: $id . Datos cambiados: ($txt)", $_SESSION['id_usuario8291']);
        echo 1;
      } else {
        echo 2;
      }
    }
  }
  public function delete_red_ipv4()
  {
    if ($this->seguridad->Acceso_full('REDES_IPV4', 'ELIMINAR', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getPost('id');
      $red = $this->model_gestion_red->get_red_ipv4($id);
      if (!$red) {
        $data["error"] = 1;
        $data["mensaje"] = "No se encontro la red que se intento eliminar, tal vez otro usuario la elimino.";
        return $data;
      }
      $get_usuarios = $this->model_gestion_red->get_users_red_ipv4($red->nodo, $red->rango1, $red->rango2);
      $data = array();
      if ($get_usuarios) {
        $usuarios = $get_usuarios->usuarios;
        if ($usuarios > 0) {
          $data["error"] = 1;
          $data["mensaje"] = "No se puede eliminar, existen $usuarios usuarios en esta red.";
        } else {
          $delete = $this->model_gestion_red->delete_red_ipv4($id);
          if ($delete) {
            $this->model_log->insert_log(000000, "monitoreo", "Red IPv4 eliminada ID: $id", $_SESSION['id_usuario8291']);
            $data["error"] = 2;
          } else {
            $data["error"] = 1;
            $data["mensaje"] = "Error vuelva a intentarlo";
          }
        }
      } else {
        $data["error"] = 1;
        $data["mensaje"] = "No se encontro la red que se intento eliminar, tal vez otro usuario la elimino.";
      }
      return json_encode($data);
    }
  }

  public function modal_detalle_red_ipv4()
  {
    if ($this->seguridad->Acceso('REDES_IPV4', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getGet('id');
      $red_ipv4 = $this->model_gestion_red->get_red_ipv4($id);
      if ($red_ipv4) {
        $servers = $this->model_selectores->get_all_nodos();
        if ($servers) {
          $data['server'] = $servers;
        } else {
          $data['server'] = array();
        }
        $ipint = ip2long($red_ipv4->red);
        $ips = 1 << (32 - $red_ipv4->cidr);
        $mask = -1 << (32 - $red_ipv4->cidr);
        $primer_ip = $ipint & $mask;
        $ultima_ip = $primer_ip + $ips;

        $red_ipv4->redH = strtoupper(dechex($ipint));
        $red_ipv4->redB = decbin($ipint);
        $red_ipv4->mask = long2ip($mask);
        $red_ipv4->maskH = strtoupper(dechex($mask));
        $red_ipv4->maskB = decbin($mask);
        $red_ipv4->portion = long2ip($primer_ip);
        $red_ipv4->portionH = strtoupper(dechex($primer_ip));
        $red_ipv4->portionB = decbin($primer_ip);
        $red_ipv4->ips = $ips;
        $red_ipv4->host = $ips - 2;
        $red_ipv4->rango = long2ip($primer_ip + 2) . " - " . long2ip($ultima_ip - 2);
        $red_ipv4->broadcast = long2ip($ultima_ip - 1);
        $data["red"] = $red_ipv4;
        return view("gestion_red/modal_detalle_red_ipv4", $data);
      } else {
        echo "No se encontro la red seleccionada.";
      }
    }
  }
  public function modal_new_emisor(){
    $permiso=$this->seguridad->Acceso('REDES_IPV4', 'ESCRITURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      return view("gestion_red/modal_new_emisor");
    }
  }
  public function save_new_emisor(){
    $permiso=$this->seguridad->Acceso('REDES_IPV4', 'ESCRITURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $nombre=$this->request->getPost('emisores[nombre]');
      $ip=$this->request->getPost('emisores[ip]');
      $fabricante=$this->request->getPost('emisores[fabricante]');
      $equipo=$this->request->getPost('emisores[equipo]');
      $user=$this->request->getPost('emisores[user]');
      $password=$this->request->getPost('emisores[password]');
      $snmp=$this->request->getPost('emisores[snmp]');
      $snmp_comunidad=$this->request->getPost('emisores[snmp_comunidad]');
      $snmp_version=$this->request->getPost('emisores[snmp_version]');
      $data_convert = array();
      $data_convert['estaciones'] = "";
      $data_convert['cpu'] = "";
      $data_convert['memoria-total'] = "";
      $data_convert['memoria-usada'] = "";
      $data_convert['ccq'] = "";
      $data_convert['ruido'] = "";
      $oid = $this->decodificar->codificar_post($data_convert);
      $data=[
         'nombre'=>$nombre,
         'ip'=>$ip,
         'fabricante'=>$fabricante,
         'equipo'=>$equipo,
         'user'=>$user,
         'usuario_netium'=>$user,
         'password'=>$password,
         'contrasenia_netium'=>$password,
         'snmp'=>$snmp,
         'snmp_comunidad'=>$snmp_comunidad,
         'snmp_version'=>$snmp_version,
         'oid'=>$oid
      ];

      $save=$this->model_gestion_red->new_emisor($data);
      if ($save) {
        shell_exec("rrdtool create /var/www/html/herramientas/grafico/ping_db_id_$save.rrd --step 60 DS:pl:GAUGE:120:0:100 DS:rtt:GAUGE:120:0:10000000 RRA:MAX:0.5:1:1500");
        $txt_log = "Nuevo emisor agregado ($nombre) ID: $save";
        $insert_log = $this->model_log->insert_log(000000, "emisores", $txt_log, $_SESSION['id_usuario8291']);
      }
      echo $save?1:2;
    }
  }


   public function modal_mapa()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'LECTURA', $_SESSION['id_usuario8291'])) {
      return view('gestion_red/modal_mapa');
    }
  }

  public function validar_mikrotik(){
    $permiso=$this->seguridad->Acceso('SERVER', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $ip=$this->request->getPost('ip');
      $usuario=$this->request->getPost('usuario');
      $contrasenia=$this->request->getPost('contrasenia');
      try {
        $client = new RouterOS\Client($ip,$usuario, $contrasenia);
        echo 1;
      } catch (\Throwable $e) {
        echo 2;
      }
    }
  }
  public function save_new_server(){
    $permiso=$this->seguridad->Acceso('SERVER', 'ESCRITURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $nodo=$this->request->getPost('nodo');
      $ip=$this->request->getPost('ip');
      $user=$this->request->getPost('user');
      $password=$this->request->getPost('password');
      $modelo = "";
      $serial = "";
      $version = "";
      try {
        $client = new RouterOS\Client($ip, $user, $password);
      } catch (\Throwable $e) {
        echo 3;
        return;
      }
      $util = new RouterOS\Util($client);
      $util->setMenu('/system routerboard');
      foreach ($util->getAll() as $item) {
        $modelo = $item->getProperty('model');
        $serial = $item->getProperty('serial-number');
      }
      $util->setMenu('/system resource');
      foreach ($util->getAll() as $item) {
        $version = $item->getProperty('version');
      }
      $data_server = [
        'nodo' => $nodo,
        'ip' => $ip,
        'usuario' => $user,
        'contrasena' => $password,
        'version' => $version,
        'modelo' => $modelo,
        'serial' => $serial,
        /* 'ipvisitadas'=>$ipvisitadas */
      ];
      $new_server=$this->model_gestion_red->save_new_server($data_server);
      if ($new_server) {
        $txt="Se agrego un nuevo servidor ($nodo) id: $new_server";
        $this->model_log->insert_log(000000, "gestion_red", $txt, $_SESSION['id_usuario8291']);
      }
      echo $new_server?1:2;
    }
  }


  private function get_rangos(string $ip, int $cidr)
  {
    $ip = ip2long($ip);
    $ips = 1 << (32 - $cidr);
    $mask = -1 << (32 - $cidr);
    $primer_ip = $ip & $mask;
    $hots = $ips - 2;
    $data = array();
    $temp = array();
    for ($i = 0; $i < $ips; $i = $i + 256) {
      $temp[] = long2ip($primer_ip + $i);
    }
    $data['rangos'] = $temp;
    $data['host'] = $hots;
    return $data;
  }
}
