<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Libraries\Class_seguridad;
use App\Libraries\converter;
/*
use App\Libraries\Class_permiso_sucursal;
use App\Libraries\Decodificar;
use App\Libraries\Ciqrcode;
use App\Models\Model_clientes;
use App\Models\Model_selectores;
use App\Models\Model_configuracion;
use App\Models\Model_log;
use App\Models\Model_ticket;
use App\Models\Model_finanzas;
use App\Models\Model_almacen;
use PEAR2\Net\RouterOS; */
use App\Models\Model_trafico;

class Estadisticas extends BaseController
{
  /*var $model_clientes;
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


  public function __construct()
  {
    /*     $this->almacen = new Model_almacen();
    $this->decodificar = new Decodificar();
    $this->model_clientes = new Model_clientes();
    $this->model_selectores = new Model_selectores();
    $this->model_configuracion = new Model_configuracion();
    $this->model_log = new Model_log();
    $this->permiso_sucursal = new Class_permiso_sucursal();
    $this->permiso_nodo = new Class_permiso_sucursal();
    $this->model_ticket = new Model_ticket();
    $this->finanzas = new Model_finanzas(); */
    $this->model_trafico = new Model_trafico();
    $this->converter = new converter();
    $this->seguridad = new Class_seguridad();
    date_default_timezone_set('America/Mexico_City');
    session();
  }

  public function home()
  {
    if ($this->seguridad->Acceso('ESTADISTICAS_CLIENTE', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $idcliente = $this->request->getGet('idcliente');
      $final = date('Y-m-d');
      $data['ultimo_dia'] = date('d/m/Y');
      $data['primer_dia'] = date('d/m/Y', strtotime($final . '-7 days'));
      /* $data['primer_dia'] = date('d/m/Y', strtotime("first day of this month"));;
      $data['ultimo_dia'] = date('d/m/Y', strtotime('last day of this month')); */
      $data['idcliente'] = $idcliente;
      return view('clientes/lista_clientes/taps/estadisticas', $data);
    }
  }
  public function get_datos_json()
  {
    if ($this->seguridad->Acceso('ESTADISTICAS_CLIENTE', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $idcliente = $this->request->getGet('idcliente');
      $inicio = $this->request->getGet('inicio');
      $final = $this->request->getGet('final');
      $servicio = $this->request->getGet('servicio');
      $inicio=$inicio." 00:00:00";
      $final=$final." 11:59:59";
      $inicio = date('Y-m-d H:i:s', strtotime(str_replace('/', "-", $inicio)));
      $final = date('Y-m-d H:i:s', strtotime(str_replace("/", "-", $final)."+ 1 day"));
      $trafico = $this->model_trafico->get_trafico_radact($idcliente, $inicio, $final, $servicio);
      if ($trafico) {
        foreach ($trafico as $key => $dato) {
          $dato->subida = $this->converter->formatSizeUnits($dato->subida);
          $dato->descarga = $this->converter->formatSizeUnits($dato->descarga);
          $dato->id = $key + 1;
        }
        $data['data'] = $trafico;
      } else {
        $data['data'] = array();
      }
      return json_encode($data);
    }
  }
  public function datos_trafico()
  {
    if ($this->seguridad->Acceso('ESTADISTICAS_CLIENTE', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $idcliente = $this->request->getPost('idcliente');
      $inicio = $this->request->getPost('inicio');
      $final = $this->request->getPost('final');
      $servicio = $this->request->getPost('servicio');
      $tipo = $this->request->getPost('tipo');

      /*   descarga: "41.3 GiB"
      grafico: [{fecha: "2020-09-16", descarga: 1895, subida: 70.6},…]
      sesiones: 42
      subida: "4 GiB"
      tiempo: "269:49:59" */
      $inicio=$inicio." 00:00:00";
      $final=$final." 11:59:59";
      $inicio = date('Y-m-d H:i:s', strtotime(str_replace('/', "-", $inicio)));
      $final = date('Y-m-d H:i:s', strtotime(str_replace("/", "-", $final)."+ 1 day"));
     
      if ($tipo != 0) {
        $anio = date('Y');
        $trafico_info = $this->model_trafico->get_trafico_info_mensual($idcliente, $anio, $servicio);
        $trafico_diario = $this->model_trafico->get_trafico_grafico_mensual($idcliente, $anio, $servicio);
      } else {
        $trafico_info = $this->model_trafico->get_trafico_info_diario($idcliente, $inicio, $final, $servicio);
        $trafico_diario = $this->model_trafico->get_trafico_grafico_diario($idcliente, $inicio, $final, $servicio);
      }
      if ($trafico_info) {
        $trafico_info->subida = $this->converter->formatSizeUnits($trafico_info->subida);
        $trafico_info->descarga = $this->converter->formatSizeUnits($trafico_info->descarga);
        if ($trafico_diario) {
        
          foreach ($trafico_diario as $trafico) {
            $trafico->subida = $trafico->subida / 1048576;
            $trafico->descarga = $trafico->descarga / 1048576;
          }
          $trafico_info->grafico = $trafico_diario;
         //$trafico_info->grafico = arrar_p;


        } else {
          $trafico_info->grafico = array();
        }
        $data['data'] = $trafico_info;
      } else {
        $data['data'] = array();
      }
      return json_encode($data);
    }
  }
}
