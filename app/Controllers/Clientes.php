<?php

namespace App\Controllers;

use App\Libraries\CamposPlantilla;

require 'vendor/autoload.php';

use PEAR2\Net\RouterOS;
use App\Libraries\Class_seguridad;
use App\Libraries\Class_permiso_sucursal;
use App\Libraries\Ciqrcode;
use App\Models\Model_clientes;
use App\Models\Model_selectores;
use App\Models\Model_configuracion;
use App\Models\Model_formatos;
use App\Models\Model_log;
use App\Models\Model_ticket;
use App\Models\Model_almacen;
use App\Models\Model_finanzas;
use App\Libraries\Decodificar;
use App\Models\Model_sms;
use App\Entities\Cliente;
use App\Models\ClienteModel;
use SMSGatewayMe\Client\ApiClient;
use SMSGatewayMe\Client\Configuration;
use SMSGatewayMe\Client\Api\MessageApi;
use SMSGatewayMe\Client\Model\SendMessageRequest;

class Clientes extends BaseController
{
  var $model_clientes;
  var $model_selectores;
  var $model_configuracion;
  var $model_log;
  var $permiso_sucursal;
  var $permiso_nodo;
  var $seguridad;
  var $ciqrcode;
  var $nmodel_log;
  var $model_ticket;
  var $decodificar;
  var $model_formatos;
  var $model_sms;
  public function __construct()
  {
    $this->model_sms = new Model_sms();

    $this->almacen = new Model_almacen();
    $this->model_clientes = new Model_clientes();
    $this->model_selectores = new Model_selectores();
    $this->model_configuracion = new Model_configuracion();
    $this->model_log = new Model_log();
    $this->permiso_sucursal = new Class_permiso_sucursal();
    $this->permiso_nodo = new Class_permiso_sucursal();
    $this->seguridad = new Class_seguridad();
    $this->finanzas = new Model_finanzas();
    $this->model_ticket = new Model_ticket();
    $this->model_formatos = new Model_formatos();
    date_default_timezone_set('America/Mexico_City');
    $this->decodificar = new Decodificar();
    session();
  }



  protected function migrar()
  {

    $result = $this->model_clientes->migrar();
    // echo count($result);
    foreach ($result as $items) {
      $array_cliente = array();
      $array_cliente = array(
        "id" => $items->id, "legal" => $items->legal, "idcliente" => $items->idcliente, "emitido" => $items->emitido, "vencimiento" => $items->vencimiento, "pago" => $items->pago, "total" => $items->total,
        "forma" => $items->forma, "estado" => $items->estado, "tipo" => $items->tipo, "cobrado" => $items->cobrado, "iva_igv" => $items->iva_igv, "sub_total" => $items->sub_total, "impuesto" => $items->impuesto
      );



      $inser = $this->model_clientes->insert_migrar($array_cliente);
    }

    echo "listo";
  }

  public function index()
  {
    if (isset($_SESSION['id_usuario8291'])) {

      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_CLIENTES'));
      if ($tipoacceso > 0) {
        $where_permiso_sucursal_select = $this->permiso_sucursal->get_permiso_sucursal_select($_SESSION['sucursales']);
        $data['sucursales'] = $this->model_selectores->get_all_sucursales_by_user($where_permiso_sucursal_select);
        $data['tipoacceso'] = $tipoacceso;
        echo view('clientes/lista_clientes/index', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function instalaciones()
  {
    if (isset($_SESSION['id_usuario8291'])) {

      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('INSTALACIONES'));
      $permiso_sucursal_select = $this->permiso_sucursal->get_permiso_sucursal_select($_SESSION['sucursales']);
      if ($tipoacceso > 0) {
        $data['tipoacceso'] = $tipoacceso;
        $data['zonas'] = $this->model_selectores->get_all_zonas();
        $data['sucursales'] = $this->model_selectores->get_all_sucursales_by_user($permiso_sucursal_select);
        $data['tecnicos'] = $this->model_selectores->get_usuarios_tecnicos();
        echo view('clientes/instalaciones/index', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function nuevo()
  {
    if (isset($_SESSION['id_usuario8291'])) {

      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('NUEVO_SOCIO'));
      if ($tipoacceso > 0) {
        $permiso_sindicato_select = $this->permiso_sindicato->get_permiso_socios_select($_SESSION['sindicatos']);
        $data['sindicatos'] = $this->model_selectores->get_all_sindicatos_by_user($permiso_sindicato_select);
        $data['tipoacceso'] = $tipoacceso;
        echo view('socios/nuevo_socio/new', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function mapa()
  {
    if (isset($_SESSION['id_usuario8291'])) {

      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('MAPA_SOCIOS'));
      if ($tipoacceso > 0) {
        $permiso_sindicato_select = $this->permiso_sindicato->get_permiso_socios_select($_SESSION['sindicatos']);
        $data['sindicatos'] = $this->model_selectores->get_all_sindicatos_by_user($permiso_sindicato_select);
        $data['tipoacceso'] = $tipoacceso;
        echo view('socios/mapa_socios/index', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function searchcliente()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      
      $permiso_sucursal = $this->permiso_nodo->get_permiso_sucursal($_SESSION['sucursales']);
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_CLIENTES'));
      $palabraclave = $this->request->getGet('term');
      $tipo_busqueda = "";
      if ($this->request->getGet('tipo') != null) {
        $tipo_busqueda = $this->request->getGet('tipo');
      } else {
        $tipo_busqueda = "nombre";
      }
      
      if ($tipo_busqueda == "nombre") {
        $where = "(u.nombre LIKE '%$palabraclave%')";
      } elseif ($tipo_busqueda == "id") {
        $where = "(u.id LIKE '%$palabraclave%')";
      } else {
        $where = "(u.id LIKE '%$palabraclave%')";
      }
      
      
      
      $content_value = "";
     
      $all_items = $this->model_clientes->get_all_search($permiso_sucursal, $where);
   
      $array_cliente = array();
      
      if ($all_items) {
        
        
        foreach ($all_items as $items) {
          if ($items->avatar_cliente == "") {
            $content_value = '<a class="widget-list-item ui-menu-item-wrapper">
            <div class="widget-list-media icon">
            <img data-name="' . $items->nombre . '" data-color="' . $items->avatar_color . '" style="border-radius: 50%">
            </div>
            <div class="widget-list-content">
            <h4 class="widget-list-title">' . $items->nombre . '</h4>
            <small>Server:' . $items->nombre_server . '</small>
            </div>
            </a>';
          } else {
            $content_value = '<a class="widget-list-item ui-menu-item-wrapper">
            <div class="widget-list-media icon">
            <img  style="border-radius: 50%" src="data:image/jpeg;base64,' . base64_encode($items->avatar_cliente) . '"/>
            </div>
            <div class="widget-list-content">
            <h4 class="widget-list-title">' . $items->nombre . '</h4>
            <small>Server:' . $items->nombre_server . '</small>
            </div>
            </a>';
          }
          $array_cliente[] = array(
            "value" => $items->nombre,
            "socio" => $content_value,
            "id" => $items->id,
            "nombre" => $items->nombre,
            "movil" => $items->movil,
            "correo" => $items->correo,
            "direccion" => $items->direccion_principal,
            "nodo" => $items->nombre_server,
            "avatar" => "sdasda",
            "token" => $_SESSION['token8291']
          );
          //array_push($array_cliente, $array_item);
        }
      }
      
      echo json_encode($array_cliente);
    } else $this->seguridad->login_back();
  }
  
  public function update_ips()
  {
    
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_CLIENTES'));
      if ($tipoacceso > 0) {
        
        $clientes = $this->model_clientes->get_all_clientes_ips();
        foreach ($clientes as $item) {
          $id = $item->id;
          $ip = $item->ip;
          $update = $this->model_clientes->update_ip($id, $ip);
        }
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function get_clientes_json()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_CLIENTES'));
      if ($tipoacceso > 0) {



        $tipo = (string) $this->request->getGet('tipo');
        $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal($_SESSION['sucursales']);
        $clientes = (new ClienteModel())->listado($tipo === "TODOS" ? null : $tipo);

        // DataTables pinta estos valores como HTML: se escapan (antes iban tal cual)
        $filas = [];
        foreach ($clientes as $c) {
          $id = esc($c->id, 'url');
          $nombre = esc($c->nombre);
          if ($tipo === "TODOS") {
            // Antes no contemplaba "SUSPENDIDO SISTEMA" y heredaba el enlace de la fila anterior
            $nombres = "<a href='#clientes/detalleuser?id=$id'>$nombre<small> <span class=\"badge " . $c->claseEtiqueta() . "\">" . esc($c->estado) . "</span></small></a>";
          } elseif ($c->estado === Cliente::RETIRADO) {
            $nombres = "<a href='#clientes/detalleuser?id=$id&estado=RETIRADO'>$nombre</a>";
          } else {
            $nombres = "<a href='#clientes/detalleuser?id=$id'>$nombre</a>";
          }
          $color = $c->colorSeguro();

          $filas[] = [
            "id" => $c->id,
            "nombres" => $nombres,
            "nombre" => base64_encode($c->nombre),
            "domicilio_completo" => esc($c->direccion),
            "movil" => esc($c->movil),
            "correo" => esc($c->correo),
            "estado" => esc($c->estado),
            "sucursal" => '<span style="color:' . $color . ';border: 1px solid ' . $color . ';padding: 2px 5px;border-radius: 5px;text-transform: uppercase;font-size: 9.5px;font-weight: 700;">' . esc($c->sucursal) . '</span>',
            "tipoacceso" => $tipoacceso
          ];
        }
        echo json_encode(['data' => $filas]);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function get_instalaciones_pre_json()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('INSTALACIONES'));
      if ($tipoacceso > 0) {

        $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal_intalacion($_SESSION['sucursales']);
        $pre = $this->model_clientes->get_all_instalaciones_pre($permiso_sucursal);
        //validar permiso especial
        $aceptar_instalacion = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('ACEPTAR_INSTALACION'));

        $array_cliente = array();
        $tool = "";
        if ($pre) {
          foreach ($pre as $items) {

            $id_i = $items->id;
            $modal = "getmodal('clientes/update_preinstall?id=$id_i','Editar registro','md')";

            if ($tipoacceso == 1) {
              $tool = "";
            } else if ($tipoacceso == 2 && $aceptar_instalacion >= 1) {
              $tool = '<td class=" text-center">
              <a onclick="' . $modal . '" data-toggle="tooltip" title="Editar Instalacion" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
              <a onclick="aceptar_instalacion(\'' . $items->id . '\')" data-toggle="tooltip" title="Acpetar Instalacion" class="btn btn-default btn-icon btn-sm"><i class="far fa-check-circle"></i></a></td>';
            } else if ($tipoacceso == 2) {
              '<td class=" text-center">
              <a onclick="' . $modal . '" data-toggle="tooltip" title="Editar Instalacion" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a></td>';
            } else if ($tipoacceso == 3 && $aceptar_instalacion >= 1) {
              $tool = '<td class=" text-center">
              <a onclick="' . $modal . '" data-toggle="tooltip" title="Editar Instalacion" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
              <a onclick="delete_instalacion(\'' . $items->id . '\',\'' . $items->cliente . '\')" data-toggle="tooltip" title="Eliminar Instalacion" class="btn btn-default btn-icon btn-sm"><i class="far fa-trash-alt"></i></a>
              <a onclick="aceptar_instalacion(\'' . $items->id . '\')" data-toggle="tooltip" title="Acpetar Instalacion" class="btn btn-default btn-icon btn-sm"><i class="far fa-check-circle"></i></a></td>';
            } else if ($tipoacceso == 3) {
              $tool = '<td class=" text-center">
              <a onclick="' . $modal . '" data-toggle="tooltip" title="Editar Instalacion" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
              <a onclick="delete_instalacion(\'' . $items->id . '\',\'' . $items->cliente . '\')" data-toggle="tooltip" title="Eliminar Instalacion" class="btn btn-default btn-icon btn-sm"><i class="far fa-trash-alt"></i></a></td>';
            }

            $array_item = array(
              "id" => $items->id,
              "nombre" => $items->cliente,
              "sucursal" => '<span style="color:' . $items->color_su . ';border: 1px solid ' . $items->color_su . ';padding: 2px 5px;border-radius: 5px;text-transform: uppercase;font-size: 9.5px;font-weight: 700;">' . strtoupper($items->sucursal) . '</span>',
              "fecha" => $items->fecha_ingreso,
              "zona" => $items->zona,
              "cedula" => $items->cedula,
              "instalacion" => $items->fecha_instalacion,
              "telefono" => $items->telefono,
              "movil" => $items->movil,
              "correo" => $items->email,
              "direccion" => $items->direccion,
              "notas" => $items->notas,
              "tool" => $tool
            );
            array_push($array_cliente, $array_item);
          }
        }
        echo '{"data":' . json_encode($array_cliente) . '}';
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function get_instalaciones_json()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('INSTALACIONES'));
      if ($tipoacceso > 0) {

        $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal_intalacion($_SESSION['sucursales']);
        $pre = $this->model_clientes->get_all_instalaciones($permiso_sucursal);

        $aceptar_instalacion = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('ACEPTAR_INSTALACION'));

        $array_cliente = array();
        $tool = "";
        $status = "";
        if ($pre) {
          foreach ($pre as $items) {

            if ($items->estate == "INSTALADO") {
              $status = "<span class='badge badge-success'>" . 'INSTALADO' . "</span>";
            } else if ($items->estate == "NO INSTALADO") {
              $status = "<span class='badge badge-danger'>" . 'NO INSTALADO' . "</span>";
            }

            $id_i = $items->id;
            $modal = "getmodal('clientes/update_install?id=$id_i','Editar registro','md')";
            
            if ($tipoacceso == 1) {
              $tool = "";
            } else if ($tipoacceso == 2 && $aceptar_instalacion >= 1 && $items->estate == "NO INSTALADO") {
              $tool = '<td class=" text-center">
              <a onclick="add_materials(\'' . $items->id . '\')" data-toggle="tooltip" title="Agregar Materiales" class="btn btn-default btn-icon btn-sm"><i class="fas fa-suitcase"></i></a>
              <a onclick="nuevo_cliente_pre(\'' . $items->id . '\')" data-toggle="tooltip" title="Acpetar Instalacion" class="btn btn-default btn-icon btn-sm"><i class="far fa-check-circle"></i></a>
              <a onclick="' . $modal . '" data-toggle="tooltip" title="Editar Instalacion" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
              <a onclick="print_install(\'clientes/to_print?id=' . $id_i . '\')" data-toggle="tooltip" title="Imprimir Hoja de Instalacion" class="btn btn-default btn-icon btn-sm"><i class="fas fa-print"></i></a></td>';
            } elseif ($tipoacceso == 2 && $items->estate == "NO INSTALADO") {
              $tool = '<td class=" text-center">
              <a onclick="add_materials(\'' . $items->id . '\')" data-toggle="tooltip" title="Agregar Materiales" class="btn btn-default btn-icon btn-sm"><i class="fas fa-suitcase"></i></a>
              <a onclick="' . $modal . '" data-toggle="tooltip" title="Editar Instalacion" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
              <a onclick="print_install(\'clientes/to_print?id=' . $id_i . '\')" data-toggle="tooltip" title="Imprimir Hoja de Instalacion" class="btn btn-default btn-icon btn-sm"><i class="fas fa-print"></i></a></td>';
            } else if ($tipoacceso == 2 && $items->estate == "INSTALADO") {
              '<td class=" text-center">
              <a onclick="add_materials(\'' . $items->id . '\')" data-toggle="tooltip" title="Editar/Quitar Materiales" class="btn btn-default btn-icon btn-sm"><i class="fas fa-suitcase"></i></a>
              <a onclick="' . $modal . '" data-toggle="tooltip" title="Editar Instalacion" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
              <a onclick="print_install(\'clientes/to_print?id=' . $id_i . '\')" data-toggle="tooltip" title="Imprimir Hoja de Instalacion" class="btn btn-default btn-icon btn-sm"><i class="fas fa-print"></i></a></td>';
            } else if ($tipoacceso == 3 && $aceptar_instalacion >= 1 && $items->estate == "NO INSTALADO") {
              $tool = '<td class=" text-center">
              <a onclick="add_materials(\'' . $items->id . '\')" data-toggle="tooltip" title="Agregar Materiales" class="btn btn-default btn-icon btn-sm"><i class="fas fa-suitcase"></i></a>
              <a onclick="nuevo_cliente_pre(\'' . $items->id . '\')" data-toggle="tooltip" title="Acpetar Instalacion" class="btn btn-default btn-icon btn-sm"><i class="far fa-check-circle"></i></a>
              <a onclick="' . $modal . '" data-toggle="tooltip" title="Editar Instalacion" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
              <a onclick="delete_install(\'' . $items->id . '\',\'' . $items->cliente . '\')" data-toggle="tooltip" title="Eliminar Instalacion" class="btn btn-default btn-icon btn-sm"><i class="far fa-trash-alt"></i></a>
              <a onclick="print_install(\'clientes/to_print?id=' . $id_i . '\')" data-toggle="tooltip" title="Imprimir Hoja de Instalacion" class="btn btn-default btn-icon btn-sm"><i class="fas fa-print"></i></a></td>';
            } elseif ($tipoacceso == 3 && $items->estate == "NO INSTALADO") {
              $tool = '<td class=" text-center">
              <a onclick="add_materials(\'' . $items->id . '\')" data-toggle="tooltip" title="Agregar Materiales" class="btn btn-default btn-icon btn-sm"><i class="fas fa-suitcase"></i></a>
              <a onclick="' . $modal . '" data-toggle="tooltip" title="Editar Instalacion" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
              <a onclick="delete_install(\'' . $items->id . '\',\'' . $items->cliente . '\')" data-toggle="tooltip" title="Eliminar Instalacion" class="btn btn-default btn-icon btn-sm"><i class="far fa-trash-alt"></i></a>
              <a onclick="print_install(\'clientes/to_print?id=' . $id_i . '\')" data-toggle="tooltip" title="Imprimir Hoja de Instalacion" class="btn btn-default btn-icon btn-sm"><i class="fas fa-print"></i></a></td>';
            } else if ($tipoacceso == 3 && $items->estate == "INSTALADO") {
              $tool = '<td class=" text-center">
              <a onclick="add_materials(\'' . $items->id . '\')" data-toggle="tooltip" title="Editar/Quitar Materiales" class="btn btn-default btn-icon btn-sm"><i class="fas fa-suitcase"></i></a>
              <a onclick="' . $modal . '" data-toggle="tooltip" title="Editar Instalacion" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
              <a onclick="delete_install(\'' . $items->id . '\',\'' . $items->cliente . '\')" data-toggle="tooltip" title="Eliminar Instalacion" class="btn btn-default btn-icon btn-sm"><i class="far fa-trash-alt"></i></a>
              <a onclick="print_install(\'clientes/to_print?id=' . $id_i . '\')" data-toggle="tooltip" title="Imprimir Hoja de Instalacion" class="btn btn-default btn-icon btn-sm"><i class="fas fa-print"></i></a></td>';
            }

            if ($items->userid == 0) {
              $cliente = $items->cliente;
            } else {
              $cliente = $items->nombre_cliente;
            }


            $array_item = array(
              "id" => $items->id,
              "nombre" => $cliente,
              "sucursal" => '<span style="color:' . $items->color_su . ';border: 1px solid ' . $items->color_su . ';padding: 2px 5px;border-radius: 5px;text-transform: uppercase;font-size: 9.5px;font-weight: 700;">' . strtoupper($items->sucursal) . '</span>',
              "fecha" => $items->fecha_ingreso,
              "zona" => $items->zona,
              "cedula" => $items->cedula,
              "instalacion" => $items->fecha_instalacion,
              "telefono" => $items->telefono,
              "movil" => $items->movil,
              "correo" => $items->email,
              "direccion" => $items->direccion,
              "notas" => $items->notas,
              "estate" => $status,
              "tool" => $tool
            );
            array_push($array_cliente, $array_item);
          }
        }
        echo '{"data":' . json_encode($array_cliente) . '}';
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function detalleuser()
  {

    if (isset($_SESSION['id_usuario8291'])) {

      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_CLIENTES'));
      $tipoaccesodatosprivados = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('DATOS_CLIENTES_PRIVADOS'));


      if ($tipoacceso > 0) {
        $id_cliente = $this->request->getGet('id');
        $estado = $this->request->getGet('estado');
        $where_permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal($_SESSION['sucursales']);
        if ($estado) {
          $user_data = $this->model_clientes->get_user_data_retirado($id_cliente);
        } else {
          $user_data = $this->model_clientes->get_user_data($id_cliente, $where_permiso_sucursal);
        }

        $user_aviso = $this->model_clientes->get_user_aviso($id_cliente);
        $user_private = $this->model_clientes->get_user_private($id_cliente);

        if ($user_data) {
          $status_cliente = "";
          $avatar = "";
          if ($user_data->estado == 'ACTIVO') {
            $status_cliente = '<span id="status_detalleuser" class="label label-success">ACTIVO</span> ';
          }
          if ($user_data->estado == 'SUSPENDIDO') {
            $status_cliente = '<span id="status_detalleuser" class="label label-danger">SUSPENDIDO</span> ';
          }
          if ($user_data->estado == 'RETIRADO') {
            $status_cliente = '<span id="status_detalleuser" class="label label-warning">RETIRADO</span> ';
          }
          //----Rodrigo----
          $data['estado'] = $user_data->estado;
          if ($user_data->estado == "RETIRADO") {
            $data['fecha_retirado'] = $user_aviso->fecha_retirado;
            $code = $user_aviso->data_retirado;

            if ($code != "") {
              $data_retirado = $this->decodificar->decodificar($code);

              $data['data_retirado'] = $data_retirado;
            } else {
              $data['data_retirado'] = array();
            }
          }
          //-----------------------------


          $data['id'] = $id = $user_data->id_user;
        
          $data['nombre'] = $nombres = $user_data->nombre;
          $data['cedula'] = $cedula = $user_data->cedula;
          $data['domicilio_completo'] = $domicilio_completo = $user_data->direccion_principal;
          $data['movil'] = $movil = $user_data->movil;
        
          $data['email'] = $email = $user_data->correo;
          $data['estatus'] = $status_cliente;
          $data['contrasenia_portal'] = $contrasenia_portal = $user_data->codigo;
         
          if ($user_private) {
            if ($tipoaccesodatosprivados > 0) {
              $data['netflix_activo'] = $user_private->Netflix_Activo;
              $data['netflix_datos'] = $user_private->Netflix_Datos;
              $data['camaras'] = $user_private->Camaras;
              $data['cableado'] = $user_private->Cableado;
              $data['wisp'] = $user_private->Wisp;
              $data['factibilidad'] = $user_private->Factibilidad;
              $data['referencia1'] = $user_private->Referencia1;
              $data['referencia2'] = $user_private->Referencia2;
              $data['colonia'] = $user_private->Colonia;
              $data['ejecutivo'] = $user_private->Ejecutivo;
              $data['permisodatos'] = $tipoaccesodatosprivados;
            } else {
              $data['netflix_activo'] = "";
              $data['netflix_datos'] = "";
              $data['camaras'] = "";
              $data['cableado'] = $user_private->Cableado;
              $data['wisp'] = $user_private->Wisp;
              $data['factibilidad'] = $user_private->Factibilidad;
              $data['referencia1'] = $user_private->Referencia1;
              $data['referencia2'] = $user_private->Referencia2;
              $data['colonia'] = $user_private->Colonia;
              $data['ejecutivo'] = $user_private->Ejecutivo;
              $data['permisodatos'] = $tipoaccesodatosprivados;
            }
          } else {
            $data['netflix_activo'] = "";
            $data['netflix_datos'] = "";
            $data['camaras'] = "";
            $data['cableado'] = "";
            $data['wisp'] = "";
            $data['factibilidad'] = "";
            $data['referencia1'] = "";
            $data['referencia2'] = "";
            $data['colonia'] = "";
            $data['ejecutivo'] = "";
            $data['permisodatos'] = "";
          }






          if ($user_data->avatar_cliente == "") {
            $avatar = '<img data-name="' . $user_data->nombre . '" data-color="' . $user_data->avatar_color . '" style="border-radius: 100%" width="45" height="45">';
          } else {
            $avatar = '<img  style="border-radius: 50%" width="45" height="45" src="data:image/jpeg;base64,' . base64_encode($user_data->avatar_cliente) . '"/>';
          }

          $data['avatar'] = $avatar;

          $max_fecha_factura = $this->model_clientes->get_max_factura($id_cliente);
          if ($max_fecha_factura) {
            $ultima_factura = $max_fecha_factura->ultimo;
          } else {
            $ultima_factura = date("Y-m-d");
          }

          $dia_pago = date("d/m/Y", strtotime($ultima_factura . "+ 1 month"));
          $dia_pago2 = date("Y/m/d", strtotime($ultima_factura . "+ 1 month"));

          $dia_crear = date("d/m/Y", strtotime($dia_pago2 . "- " . $user_aviso->diafactura . " day"));
          $dia_aviso = date("d/m/Y", strtotime($dia_pago2 . "- " . $user_aviso->avisopantalla . " day"));
          $dia_corte = date("d/m/Y", strtotime($dia_pago2 . "+ " . $user_aviso->corteautomatico . " day"));
          $data['dia_pago'] = $dia_pago;
          $data['dia_crear'] = $dia_crear;
          $data['dia_aviso'] = $dia_aviso;
          $data['dia_corte'] = $dia_corte;

          $data['hora_factura'] = date("h:i a", strtotime($this->model_configuracion->get_cron_factura()));
          $data['hora_pantalla'] = date("h:i a", strtotime($this->model_configuracion->get_cron_pantalla()));
          $data['hora_corte'] = date("h:i a", strtotime($this->model_configuracion->get_cron_corte()));


          $all_servicios = $this->model_selectores->get_all_servicios_by_cliente($id_cliente);
          $option_servicios = "";
          if ($all_servicios) {
            foreach ($all_servicios as $items) {
              $option_servicios .= "<option value='" . $items->id_servicio . "'>" . $items->plan . " (" . $items->ip . ")" . "</option>";
            }
          }
          $data['option_servicios'] = $option_servicios;

          $data['usuario'] = $_SESSION["usuario"];

          $data['tipoacceso'] = $tipoacceso;
          // $data['content_sucursal']=$content_sucursal;
          echo view('clientes/lista_clientes/view', $data);
        }  ///fin if user data

        else {
          echo "NO TIENES PERMISOS PARA VISUALIZAR AL SOCIO";
        }
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function servicios_cliente()
  {
    if (isset($_SESSION['id_usuario8291']) && $this->request->getGet('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      // $permiso_sucursal = new Class_permiso_sucursal();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_CLIENTES'));
      if ($tipoacceso > 0) {
        $id = $this->request->getGet('id');
        $permiso_nodo = $this->permiso_nodo->get_permiso_sucursal($_SESSION['nodos']);
        $data['id'] = $id;
        $data['permiso'] = $tipoacceso;


        echo view('clientes/lista_clientes/taps/servicios', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function get_servicios_json()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_CLIENTES'));
      if ($tipoacceso > 0) {

        $id_cliente = $this->request->getGet('id');
        $all_items = $this->model_clientes->get_servicios_cliente($id_cliente);
        $array_servicio = array();
        if ($all_items) {
          foreach ($all_items as $items) {
            $estatus_label = "";
            if ($items->status_user == "ONLINE") {
              $estatus_label = '<span class="label label-success">ONLINE</span>';
            } else {
              $estatus_label = '<span class="label label-danger">OFFLINE</span>';
            }
            $array_item = array(
              "id" => $items->id_servicio,
              "plan" => $items->plan,
              "costo" => "$ " . $items->costotblservicio,
              "ip" => $items->ip,
              "mac" => $items->mac,
              "nodo" => $items->server,
              "direccion" => $items->direccion,
              "instalado" => date_format(date_create($items->instalado), 'd/m/Y'),
              "estado" => $estatus_label,
            );

            if ($tipoacceso == 1) {
              $array_item['tools'] = "
                <td class=\" text-center\">
                  <a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"getmodal('clientes/modal_editar_servicio?idservicio=$items->id_servicio','Editar Servicio','lg');\" data-original-title=\"Editar\">
                    <i class=\"far fa-edit\"></i>
                  </a>
                </td>
              ";
            }
            if ($tipoacceso == 2) {
              $array_item['tools'] = "
                <td class=\" text-center\">
		  <a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"getmodal('herramientas_mikrotik/modal_trafico?id=$items->id_servicio','Trafico en tiempo real','lg');\" data-original-title=\"Editar\">
                    <i class=\"fas fa-signal\"></i>
                  </a>
                  <a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"getmodal('clientes/modal_editar_servicio?idservicio=$items->id_servicio','Editar Servicio','lg');\" data-original-title=\"Editar\">
                    <i class=\"far fa-edit\"></i>
                </td>
              ";
            }

            if ($tipoacceso == 3) {
              $array_item['tools'] = "
                <td class=\" text-center\">
      <a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"getmodal('herramientas_mikrotik/modal_trafico?id=$items->id_servicio','Trafico en tiempo real','lg');\" data-original-title=\"Editar\">
                    <i class=\"fas fa-signal\"></i>
                  </a>
                  <a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"getmodal('clientes/modal_editar_servicio?idservicio=$items->id_servicio','Editar Servicio','lg');\" data-original-title=\"Editar\">
                    <i class=\"far fa-edit\"></i>
                  </a>
                  <a data-toggle=\"tooltip\" title=\"Eliminar\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"remove_service('$items->id_servicio')\">
                    <i class=\"far fa-trash-alt\" aria-hidden=\"true\"></i>
                  </a>
                </td>
              ";
            }
            array_push($array_servicio, $array_item);
          }
        }
        echo '{"data":' . json_encode($array_servicio) . '}';
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function get_serviciosvoip_json()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_CLIENTES'));
      if ($tipoacceso > 0) {

        $id_cliente = $this->request->getGet('id');
        $all_items = $this->model_clientes->get_serviciosvoip_cliente($id_cliente);
        $array_servicio = array();
        if ($all_items) {
          foreach ($all_items as $items) {
            $array_item = array(
              "id" => $items->id,
              "plan" => $items->plan,
              "costo" => "$ " . $items->costo,
              "server" => $items->server,
              "sipuser" => $items->sipuser,
              "sipid" => $items->sipid,
              "numerotelefono" => $items->numerotelefono,
              "instalacion" => date_format(date_create($items->instalacion), 'd/m/Y'),
              "notas" => $items->notas
            );
            if ($tipoacceso >= 1) {
              $array_item['tools'] = "
                <td class=\" text-center\">
                <a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"getmodal('clientes/modal_editar_servicio_voip?idcliente=$id_cliente&idservicio=$items->id','Editar Servicio','lg');\" data-original-title=\"Editar\">
                  <i class=\"far fa-edit\"></i>
                </a>";
              if ($tipoacceso >= 2) {
                $array_item['tools'] .= "<a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"remove_servicevoip('$items->id')\" data-original-title=\"Eliminar\">
                  <i class=\"far fa-trash-alt\" aria-hidden=\"true\"></i>
                </a>";
              }
              $array_item['tools'] .= "</td>";
            } else {
              $array_item['tools'] = "";
            }
            array_push($array_servicio, $array_item);
          }
        }
        echo '{"data":' . json_encode($array_servicio) . '}';
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function herramientas()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_CLIENTES'));
      if ($tipoacceso > 0) {

        $id_cliente = $this->request->getGet('id');
        $all_items = $this->model_clientes->get_servicios_cliente($id_cliente);

        if ($all_items[0]->cuenta_con_servicio == 0) {
          echo "<script>alerta(\"warning\",\"El cliente no cuenta con ningun servicio\");</script>";
          return;
        }
        $estado = $all_items[0]->estadou;
        echo '
        <div class="popover-body">  

<label>Seleccionar servicio</label>
<select name="servicio" id="sl-service" class="idservicio form-control sl2" style=" width: 100%; margin-bottom: 10px !important">';
        $array_servicio = array();
        if ($all_items) {
          foreach ($all_items as $items) {
            echo '<option value="' . $items->id_servicio . '">' . $items->plan . '(' . $items->ip . ')</option>';
          }
        }
        $herra = '</select>

    <a class="btn btn-default btn-app btnremote" style="margin-top:10px" data-toggle="modal"  data-remote="false" data-title="Modem Cliente" data-target="#modal-tool2"><i class="fa fa-wifi"></i> Ver Modem </a>
    <a class="btn btn-default btn-app btnremote" style="margin-top:10px" data-toggle="modal"  data-remote="false" data-title="Modem Reboot" data-target="#modal-tool2"><i class="fa fa-chart-line"></i> Reboot Modem </a>
    <button type="button" class="btn btn-default btn-app" data-toggle="tooltip" title="" onclick="getmodal(\'herramientas_mikrotik/modal_ping?id=\'+$(\'.idservicio\').val(),\'Ping Mikrotik\',\'md\');closeall();" data-original-title="Permite realizar ping utilizando mikrotik">
      <i class="fas fa-exchange-alt"></i>
      Ping Mikrotik
    </button>
    <button type="button" class="btn btn-default btn-app" data-toggle="tooltip" title="" onclick="getmodal(\'herramientas_mikrotik/modal_trafico?id=\'+$(\'.idservicio\').val(),\'Ping Mikrotik\',\'md\');closeall();" data-original-title="Permite realizar ping utilizando mikrotik">
      <i class="fas fa-exchange-alt"></i>
      Trafico Mikrotik
    </button>
    <button type="button" class="btn btn-warning btn-app" onclick="suspender_cliente(\'' . $id_cliente . '\');closeall();">
    <i class="fas fa-power-off"></i>
    SUSPENDER
    </button>
    <button type="button" class="btn btn-success btn-app" onclick="activar_cliente(\'' . $id_cliente . '\');closeall();">
    <i class="fas fa-check"></i>
    ACTIVAR
    </button>
    </div>
    ';
        echo $herra;
?>
        <script>
          var myInterval;
          var mySetTime;

          function suspender_cliente(id) {
            var status = document.getElementById('status_detalleuser');

            if (status.innerHTML == "SUSPENDIDO") {
              alerta("warning", "El cliente ya esta suspendido");
              return false;
            }
            var alsup = $.confirm({
              theme: 'supervan',
              draggable: false,
              closeIcon: true,
              animationBounce: 2.5,
              escapeKey: false,
              content: 'Esta seguro que desea suspender a este cliente <br><b class="text-warning"></b>',
              title: '<i class="far fa-question-circle fa-lg icodialog"></i> SUSPENDER',
              buttons: {
                Eliminar: {
                  text: '<i class="fas fa-ban icodialog"></i> Si, suspender',
                  action: function() {
                    this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
                    this.buttons.Eliminar.disable();
                    $('.jconfirm-closeIcon').remove();
                    $.post("clientes/suspender_cliente", {
                        idcliente: id
                      })
                      .done(function(data) {
                        alsup.close();
                        data = JSON.parse(data);
                        if (data.estado == 2) {
                          alerta('warning', data.mensaje);
                        } else {
                          alerta('exito', 'Cliente suspendido correctamente');
                          document.getElementById('status_detalleuser').innerHTML = "<span class=\"label label-danger\">SUSPENDIDO</span>"
                        }
                      });
                    return false;

                  }
                }
              }
            });


          }

          function activar_cliente(id) {
            var status = document.getElementById('status_detalleuser');
            if (status.innerHTML == "ACTIVO") {
              alerta("warning", "El cliente ya esta activo");
              return false;
            }
            var alsup = $.confirm({
              theme: 'supervan',
              draggable: false,
              closeIcon: true,
              animationBounce: 2.5,
              escapeKey: false,
              content: 'Esta seguro que desea Activar a este cliente <br><b class="text-warning"></b>',
              title: '<i class="far fa-question-circle fa-lg icodialog"></i> ACTIVAR !',
              buttons: {
                Eliminar: {
                  text: '<i class="fas fa-check icodialog"></i> Si, Activar',
                  action: function() {
                    this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
                    this.buttons.Eliminar.disable();
                    $('.jconfirm-closeIcon').remove();
                    $.post("clientes/activar_cliente", {
                        idcliente: id,
                      })
                      .done(function(data) {
                        alsup.close();
                        data = JSON.parse(data);
                        if (data.estado == 2) {
                          alerta('error', 'Error al activar al cliente.');
                        } else {
                          if (data.mensaje != null) {
                            alerta('warning', data.mensaje);
                          } else {
                            alerta('exito', 'Cliente activado correctamente');
                            status.innerHTML = "<span class=\"label label-success\">ACTIVO</span>"
                          }
                        }
                      });
                    return false;

                  }
                }
              }
            });


          }
          $(document).ready(function() {

            $('.btnremote').click(function(e) {
              e.preventDefault();
              if ($(this).data('title') == 'Modem Cliente') {

                var url = base_url + 'herramientas/viewmodem?id=' + $('#sl-service').val();

                //var url = base_url+'herramientas/viewmodem/59'
                $('#modal-tool2 .modal-body').html('<div class="ubloader" style="padding:30px;"><i class="fa fa-spinner fa-spin fa-lg fa-fw margin-bottom"></i><span>Cargando...</span></div>').load(url);
                $('#modal-tool2 h5').html($(this).data('title'));

              } else if ($(this).data('title') == 'Modem Reboot') {

                var url = base_url + 'herramientas/rebootmodem?id=' + $('#sl-service').val();

                //var url = base_url+'herramientas/viewmodem/59'
                $('#modal-tool2 .modal-body').html('<div class="ubloader" style="padding:30px;"><i class="fa fa-spinner fa-spin fa-lg fa-fw margin-bottom"></i><span>Cargando...</span></div>');
                $('#modal-tool2 h5').html($(this).data('title'));
                //setInterval(estatusmodem($('#sl-service').val()),5000);

                $.post(url)
                  .done(function(data) {
                    $('#modal-tool2 .modal-body').html(data);
                    myFunction();
                  });

              } else {
                var url = $(this).prop('href') + '&id=' + $('#sl-service').val();
                $('#modal-tool .modal-body').html('<div class="ubloader" style="padding:30px;"><i class="fa fa-spinner fa-spin fa-lg fa-fw margin-bottom"></i><span>Cargando...</span></div>').load(url);
                $('#modal-tool h4').html($(this).data('title'));
              }

            });


            $('#modal-tool2').on('hidden.bs.modal', function(e) {

              clearInterval(myInterval);
              clearTimeout(mySetTime);
              console.log("se detuvo mi setTimeout")
            })

          });

          function myFunction() {
            var d = new Date();
            console.log(d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds());
            myInterval = setInterval(estatusmodem, 25000);
            mySetTime = setTimeout(pararInterval, 600000);
          }

          function pararInterval() {
            clearInterval(myInterval);
            console.log("funcion interval detenido");
            var d = new Date();
            console.log(d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds());
          }

          function estatusmodem() {

            var url = base_url + 'herramientas/rebootmodemestatus?id=' + $('#sl-service').val();
            $.post(url)
              .done(function(data) {
                $('#modal-tool2 .modal-body').html(data);
              });

          }
        </script>
<?php
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function get_documentos_json()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_CLIENTES'));
      $permiso_aprobar_doc = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('APROBAR_DOCUMENTOS'));
      if ($tipoacceso > 0) {

        $id_cliente = $this->request->getGet('id');
        //  $permiso_sindicato=$this->permiso_sindicato->get_permiso_socios($_SESSION['sindicatos']);
        $tipodocumentos = $this->model_clientes->get_tipodocumento($id_cliente);


        $array_cliente = array();
        $estado = "";
        if ($tipodocumentos) {
          foreach ($tipodocumentos as $items) {
            $adjunto = "";
            $fechaalta = "";
            $status = "";
            $tool = "";
            $documentos = $this->model_clientes->get_documentos_by_cliente($id_cliente, $items->id, null);

            foreach ($documentos as $items1) {
              $adjunto = '<a target="_blank" href="clientes/dowload_doc?id=' . $items1->id . '&amp;token=' . $_SESSION['token8291'] . '">' . $items1->adjunto . '</a>';
              $fechaalta = date('d/m/Y - h:i a', strtotime($items1->fechaalta));


              if ($items1->estado == "APROBADO") {
                $status = "<span class='badge badge-success'>" . $items1->estado . "</span>";
              } else if ($items1->estado == "NO VALIDADO") {
                $status = "<span class='badge badge-warning'>" . $items1->estado . "</span>";
              } else {
                $status = "<span class='badge badge-danger'>" . $items1->estado . "</span>";
              }

              if ($tipoacceso == 1) {
                $tool = '';
              }
              if ($tipoacceso == 2) {
                $tool = '<td class=" tool-documentos text-center"></td';
              }
              if ($tipoacceso == 2 && $permiso_aprobar_doc > 0) {
                $tool = '<td class=" tool-documentos text-center">
                  <button onclick="aprobar_doc(\'' . $items1->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $items->nombre . '\',\'' . $items1->idcliente . '\')" data-toggle="tooltip" title="Aprobar" class="btn btn-default btn-icon btn-sm"><i class="fa fa-check"></i></button></td';
              }
              if ($tipoacceso == 3) {
                $tool = '<td class=" tools-documentos text-center">
            <a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_doc(\'' . $items1->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $items->nombre . '\',\'' . $items1->idcliente . '\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a></td>';
              }
              if ($tipoacceso == 3 && $permiso_aprobar_doc > 0) {
                $tool = '<td class=" tools-documentos text-center">
            <a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_doc(\'' . $items1->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $items->nombre . '\',\'' . $items1->idcliente . '\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
            <button onclick="aprobar_doc(\'' . $items1->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $items->nombre . '\',\'' . $items1->idcliente . '\')" data-toggle="tooltip" title="Aprobar" class="btn btn-default btn-icon btn-sm"><i class="fa fa-check"></i></button></td>';
              }
            }

            $array_item = array(
              "id" => $items->id,
              "tipo" => '<strong style="color:black">' . strtoupper($items->nombre) . '</strong>',
              "adjunto" => $adjunto,
              "estado" => $status,
              "fechaalta" => $fechaalta,
              "tool" => $tool,
              "tipoacceso" => $tipoacceso
            );
            array_push($array_cliente, $array_item);
          }
        }
        echo '{"data":' . json_encode($array_cliente) . '}';
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function log_cliente()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LOG'));

      if ($tipoacceso > 0) {


        $data['id'] = $this->request->getGet('id');
        $data['token'] = $_SESSION['token8291'];
        $data['tipoacceso'] = $tipoacceso;
        echo view('clientes/lista_clientes/taps/log', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function get_log_json()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LOG'));
      if ($tipoacceso > 0) {
        $id = $this->request->getGet("id");
        $permiso_nodo = $this->permiso_nodo->get_permiso_sucursal($_SESSION['nodos']);
        $log = $this->model_log->get_all_log_by_clientes($id, $permiso_nodo);

        $array_cliente = array();
        if ($log) {
          foreach ($log as $items) {
            $items->log = str_replace("{{cliente}}", $items->nombrecliente, $items->log);
            $array_item = array(
              "id" => $items->id,
              "log" => wordwrap($items->log, 100, "\n", 1),
              "user" => $items->username,
              "fecha" => $items->fechalog
            );
            array_push($array_cliente, $array_item);
          }
        }
        echo '{"data":' . json_encode($array_cliente) . '}';
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function get_reportes_json()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {
        $id_socio = $this->request->getGet('id');
        $permiso_sindicato = $this->permiso_sindicato->get_permiso_socios($_SESSION['sindicatos']);
        $permiso_cerrar_re = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('CERRAR_REPORTES'));


        $reportes = $this->model_socios->get_reportes_by_socio($id_socio, $permiso_sindicato);


        $array_cliente = array();
        $estado = "";
        if ($reportes) {
          foreach ($reportes as $items) {
            $status = "";
            $tool = "";

            if ($items->estado == "ATENDIDO") {
              $status = "<span class='badge badge-success'>" . $items->estado . "</span>";
            } else if ($items->estado == "EN REVISION") {
              $status = "<span class='badge badge-warning'>" . $items->estado . "</span>";
            } else if ($items->estado == "CERRADO") {
              $status = "<span class='badge badge-danger'>" . $items->estado . "</span>";
            } else {
              $status = "<span class='badge badge-primary'>" . $items->estado . "</span>";
            }

            if ($tipoacceso == 1) {
              $tool = '';
            }
            if ($tipoacceso == 2) {
              $tool = '<td class=" tool-documentos text-center">
             <a data-toggle="tooltip" title="Ver reporte" class="btn btn-default btn-icon btn-sm" onclick="edit_re(\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $id_socio . '\')"><i class="far fa-edit" aria-hidden="true"></i></a></td';
            }

            if ($tipoacceso == 2 && $permiso_cerrar_re > 0) {
              $tool = '<td class=" tool-documentos text-center">
             <a data-toggle="tooltip" title="Ver reporte" class="btn btn-default btn-icon btn-sm" onclick="edit_re(\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $id_socio . '\')"><i class="far fa-edit" aria-hidden="true"></i></a>
             <a data-toggle="tooltip" title="" class="btn btn-default btn-icon btn-sm" onclick="cerrar_re(\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $items->usuario . '\',\'' . $id_socio . '\')" data-original-title="Cerrar reporte"><i class="fas fa-times-circle"></i></a></td';
            }

            if ($tipoacceso == 3) {
              $tool = '<td class=" tools-documentos text-center">
             <a data-toggle="tooltip" title="Ver reporte" class="btn btn-default btn-icon btn-sm" onclick="edit_re(\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $id_socio . '\')"><i class="far fa-edit" aria-hidden="true"></i></a>
            <a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_re(\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $items->usuario . '\',\'' . $id_socio . '\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a></td>';
            }


            if ($tipoacceso == 3 && $permiso_cerrar_re > 0) {
              $tool = '<td class=" tools-documentos text-center">
             <a data-toggle="tooltip" title="Ver reporte" class="btn btn-default btn-icon btn-sm" onclick="edit_re(\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $id_socio . '\')"><i class="far fa-edit" aria-hidden="true"></i></a>
            <a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_re(\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $items->usuario . '\',\'' . $id_socio . '\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
            <a data-toggle="tooltip" title="" class="btn btn-default btn-icon btn-sm" onclick="cerrar_re(\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $items->usuario . '\',\'' . $id_socio . '\')" data-original-title="Cerrar reporte"><i class="fas fa-times-circle"></i></a></td>';
            }




            $array_item = array(
              "id" => $items->id,
              "tipo" => '<strong style="color:black">' . strtoupper($items->tipo) . '</strong>',
              "_from" => '<span style="color:#0099ff;border: 1px solid #0099ff;padding: 2px 5px;border-radius: 5px;text-transform: uppercase;font-size: 9.5px;font-weight: 700;">' . $items->_from . '</span>',
              "usuario" => $items->usuario,
              "asunto" => "<a href='#socios/view_reportes?id=" . $items->id . "&token=" . $_SESSION['token8291'] . "&socio=" . $id_socio . "'>" . $items->asunto . "</a>",
              "estado" => $status,
              "fechaalta" => date('d/m/Y - h:i a', strtotime($items->fechaalta)),
              "tool" => $tool,
              "tipoacceso" => $tipoacceso
            );
            array_push($array_cliente, $array_item);
          }
        }

        echo '{"data":' . json_encode($array_cliente) . '}';
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }




  public function delete_socio()
  {

    if (isset($_SESSION['id_usuario8291']) && $this->request->getPost('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {

        $id = $this->request->getPost('id');
        $nombre = $this->request->getPost('nombre');
        $insert_log = $this->model_log->insert_log($id, "socios", "Socio eliminado <b>#" . $id . " " . $nombre . "</b>", $_SESSION['nombre']);
        $delete = $this->model_socios->delete_socio($id);
        if ($delete) echo "succes";
        else echo "error";
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function delete_doc()
  {

    if (isset($_SESSION['id_usuario8291']) && $this->request->getPost('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {

        $id = $this->request->getPost('id');
        $id_socio = $this->request->getPost('id_socio');
        $nombre = $this->request->getPost('nombre');
        $insert_log = $this->model_log->insert_log($id_socio, "socios", "Documento eliminado <b>#" . $id . " " . $nombre . "</b>", $_SESSION['nombre']);
        $delete = $this->model_socios->delete_doc($id);
        if ($delete) echo "succes";
        else echo "error";
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function delete_re()
  {

    if (isset($_SESSION['id_usuario8291']) && $this->request->getPost('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {

        $id = $this->request->getPost('id');
        $nombre = $this->request->getPost('nombre');
        $id_socio = $this->request->getPost('id_socio');
        $insert_log = $this->model_log->insert_log($id_socio, "socios", "Reporte eliminado <b>#" . $id . " al socio " . $id_socio . "</b>", $_SESSION['nombre']);
        $delete = $this->model_socios->delete_re($id);
        if ($delete) echo 1;
        else echo 2;
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function aprobar_doc()
  {

    if (isset($_SESSION['id_usuario8291']) && $this->request->getPost('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('APROBAR_DOCUMENTOS'));
      if ($tipoacceso > 0) {

        $id = $this->request->getPost('id');
        $nombre = $this->request->getPost('nombre');
        $id_socio = $this->request->getPost('id_socio');
        $insert_log = $this->model_log->insert_log($id_socio, "socios", "Documento aprobado <b>#" . $id . " " . $nombre . "</b> al socio " . $id_socio, $_SESSION['nombre']);
        $update = $this->model_socios->aprobar_doc($id);
        if ($update) echo "succes";
        else echo "error";
      } else echo "ACCESO DENEGADO";
    } else $this->seguridad->login_back();
  }

  public function cerrar_re()
  {

    if (isset($_SESSION['id_usuario8291']) && $this->request->getPost('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('CERRAR_REPORTES'));
      if ($tipoacceso > 0) {

        $id = $this->request->getPost('id');
        $nombre = $this->request->getPost('nombre');
        $id_socio = $this->request->getPost('id_socio');
        $insert_log = $this->model_log->insert_log($id_socio, "socios", "Reporte cerrado <b>#" . $id . "</b> al socio " . $id_socio, $_SESSION['nombre']);
        $update = $this->model_socios->cerrar_re($id);
        if ($update) echo 1;
        else echo 2;
      } else echo "ACCESO DENEGADO";
    } else $this->seguridad->login_back();
  }

  public function popover()
  {

    if (isset($_SESSION['id_usuario8291']) && $this->request->getGet('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {

        $id = $this->request->getGet('id');
        $id_socio = $this->request->getGet('id_socio');
        $nombre = $this->request->getGet('nombre');
        $status = $this->request->getGet('status');
        $botones = "";
        if ($status == "ACTIVO") {
          $botones = '<div class="popover-body">
                         <button type="button" class="btn btn-primary btn-app" disabled onclick="cambiarstatus(\'' . $id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $nombre . '\',\'ACTIVO\',\'ACTIVAR\')"><i class="fas fa-power-off"></i>ACTIVAR</button>
                         <button type="button" class="btn btn-green btn-app" onclick="cambiarstatus(\'' . $id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $nombre . '\',\'PRUEBA\',\'PRUEBA\')"><i class="fas fa-user-times"></i>PRUEBA</button>	
                         <button type="button" class="btn btn-warning btn-app" onclick="cambiarstatus(\'' . $id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $nombre . '\',\'SUSPENDIDO\',\'SUSPENDER\')"><i class="fas fa-power-off"></i>SUSPENDER</button>
                         <button type="button" class="btn btn-danger btn-app" onclick="cambiarstatus(\'' . $id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $nombre . '\',\'RETIRADO\',\'RETIRAR\')"><i class="fas fa-user-times"></i>RETIRAR</button>
                         </div>';
        } else if ($status == "PRUEBA") {
          $botones = '<div class="popover-body">
                         <button type="button" class="btn btn-primary btn-app" onclick="cambiarstatus(\'' . $id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $nombre . '\',\'ACTIVO\',\'ACTIVAR\')"><i class="fas fa-power-off"></i>ACTIVAR</button>
                         <button type="button" class="btn btn-green btn-app"  disabled onclick="cambiarstatus(\'' . $id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $nombre . '\',\'PRUEBA\',\'PRUEBA\')"><i class="fas fa-user-times"></i>PRUEBA</button>	
                         <button type="button" class="btn btn-warning btn-app" onclick="cambiarstatus(\'' . $id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $nombre . '\',\'SUSPENDIDO\',\'SUSPENDER\')"><i class="fas fa-power-off"></i>SUSPENDER</button>
                         <button type="button" class="btn btn-danger btn-app" onclick="cambiarstatus(\'' . $id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $nombre . '\',\'RETIRADO\',\'RETIRAR\')"><i class="fas fa-user-times"></i>RETIRAR</button>
                         </div>';
        } else if ($status == "SUSPENDIDO") {
          $botones = '<div class="popover-body">
                         <button type="button" class="btn btn-primary btn-app" onclick="cambiarstatus(\'' . $id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $nombre . '\',\'ACTIVO\',\'ACTIVAR\')"><i class="fas fa-power-off"></i>ACTIVAR</button>
                         <button type="button" class="btn btn-green btn-app" onclick="cambiarstatus(\'' . $id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $nombre . '\',\'PRUEBA\',\'PRUEBA\')"><i class="fas fa-user-times"></i>PRUEBA</button>	
                         <button type="button" class="btn btn-warning btn-app" disabled onclick="cambiarstatus(\'' . $id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $nombre . '\',\'SUSPENDIDO\',\'SUSPENDER\')"><i class="fas fa-power-off"></i>SUSPENDER</button>
                         <button type="button" class="btn btn-danger btn-app" onclick="cambiarstatus(\'' . $id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $nombre . '\',\'RETIRADO\',\'RETIRAR\')"><i class="fas fa-user-times"></i>RETIRAR</button>
                         </div>';
        } else {
          $botones = '<div class="popover-body">
                         <button type="button" class="btn btn-primary btn-app" onclick="cambiarstatus(\'' . $id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $nombre . '\',\'ACTIVO\',\'ACTIVAR\')"><i class="fas fa-power-off"></i>ACTIVAR</button>
                         <button type="button" class="btn btn-green btn-app" onclick="cambiarstatus(\'' . $id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $nombre . '\',\'PRUEBA\',\'PRUEBA\')"><i class="fas fa-user-times"></i>PRUEBA</button>
                         <button type="button" class="btn btn-warning btn-app" onclick="cambiarstatus(\'' . $id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $nombre . '\',\'SUSPENDIDO\',\'SUSPENDER\')"><i class="fas fa-power-off"></i>SUSPENDER</button>
                         <button type="button" class="btn btn-danger btn-app" disabled onclick="cambiarstatus(\'' . $id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $nombre . '\',\'RETIRADO\',\'RETIRAR\')"><i class="fas fa-user-times"></i>RETIRAR</button>
                         </div>';
        }



        echo $botones;
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function update_status()
  {

    if (isset($_SESSION['id_usuario8291']) && $this->request->getPost('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {
        $id = $this->request->getPost('id');
        $nombre = $this->request->getPost('nombre');
        $status = $this->request->getPost('status');
        $update_status = $this->model_socios->update_status($id, $status);
        if ($update_status) {
          echo "succes";
          $insert_log = $this->model_log->insert_log($id, "socios", "<b>Socio #" . $id . " " . $nombre . "</b> cambiado de status a " . $status . "", $_SESSION['nombre']);
        } else {
          echo "error";
        }
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function view()
  {
    if (isset($_SESSION['id_usuario8291']) && $this->request->getGet('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      // $permiso_sucursal = new Class_permiso_sucursal();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {
        $id = $this->request->getGet('id');
        $permiso_sindicato = $this->permiso_sindicato->get_permiso_socios($_SESSION['sindicatos']);
        $permiso_sindicato_select = $this->permiso_sindicato->get_permiso_socios_select($_SESSION['sindicatos']);
        $data_socio = $this->model_socios->get_data_socio($id, $permiso_sindicato);
        $status = "";
        if ($data_socio) {

          if ($data_socio->status == "ACTIVO") {
            $status = "<span class='badge badge-primary'>" . $data_socio->status . "</span>";
          } else if ($data_socio->status == "PRUEBA") {
            $status = "<span class='badge badge-success'>" . $data_socio->status . "</span>";
          } else if ($data_socio->status == "SUSPENDIDO") {
            $status = "<span class='badge badge-warning'>" . $data_socio->status . "</span>";
          } else {
            $status = "<span class='badge badge-danger'>" . $data_socio->status . "</span>";
          }
          $row_lider_sindical = $this->model_socios->get_lider_sindical($data_socio->sindicato);
          $data['id'] = $data_socio->id;
          $data['status'] = $status;
          $data['sindicato'] = $data_socio->sindicato;
          $data['password'] = $data_socio->password;
          $data['ine'] = $data_socio->ine;
          $data['nombre'] = $data_socio->nombre;
          $data['apellidos'] = $data_socio->apellidos;
          $data['domicilio'] = $data_socio->domicilio;
          $data['movil'] = $data_socio->movil;
          $data['correo'] = $data_socio->correo;
          $data['licencia'] = $data_socio->licencia;
          $data['ubicacion'] = $data_socio->ubicacion;
          $data['fechaalta'] = date("d/m/Y h:i a", strtotime($data_socio->fechaalta));
          $data['lidersindical'] = $row_lider_sindical->nombre;
          $data['sindicatos'] = $this->model_selectores->get_all_sindicatos_by_user($permiso_sindicato_select);
          $data['tipoacceso'] = $tipoacceso;

          $nombre = $data_socio->nombre;
          $apellidos = $data_socio->apellidos;
          if ($data_socio->avatar == "") {
            $data['avatar'] = '<span class="user-image">' . substr($nombre, 0, 1) . substr($apellidos, 0, 1) . '</span>';
          } else {
            $data['avatar'] = '<img src="' . $data_socio->avatar . '" width="45" height="45" style="border-radius: 100%">';
          }



          echo view('socios/lista_socios/view', $data);
        } else {
          echo "NO TIENES PERMISOS PARA VISUALIZAR AL SOCIO";
        }
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function view_reportes()
  {
    // echo $_SERVER['REQUEST_URI'];   
    if (isset($_SESSION['id_usuario8291']) && $this->request->getGet('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {
        $id = $this->request->getGet('id');
        $permiso_sindicato = $this->permiso_sindicato->get_permiso_socios($_SESSION['sindicatos']);
        $data_reportes = $this->model_socios->get_reportes_by_id($id, $permiso_sindicato);
        $cadena = "";
        $image = "";
        if ($data_reportes) {
          $data_tickets = $this->model_socios->get_tickets_by_reporte($id);
          if ($data_tickets) {
            foreach ($data_tickets as $items1) {

              if ($items1->user == "sistema") {
                $image = "default-avatar.jpg";
              } else {
                $image = "admin.png";
              }

              if ($items1->state == "respuesta") {

                $cadena .= '<ul class="media-list media-list-with-divider media-messaging">
                      <li class="tksoporte media media-sm item-' . $items1->id . '">
                      <div class="pull-left text-center" style="margin-right: 10px;">
                      <img src="' . base_url() . 'public/images/avatar/' . $image . '"" width="80" height="80">
                      
                      </div>
                      <div class="media-body inbox">
                      <h5 class="media-heading"><b>' . strtoupper($items1->user) . '</b>
                      <div class="pull-right">
                      <small style="font-size: 12px;font-weight: 600;color: #6aa1c3;">' . $items1->fechaaltare . '
                      </small> | 
                      <i class="d-print-none far fa-trash-alt" onclick="deleteitem(\'' . $items1->id . '\',\'' . $id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $this->request->getGet("socio") . '\')" style="cursor: pointer;"></i>
                      </div>
                      </h5>
                      <div style="font-size: 12.5px;margin-top: 11px;min-height: 50px;"><p>' . htmlspecialchars_decode($items1->descripcion) . '</p></div>
                      <ul class="attached-document clearfix">';
                $array_adjunto = explode("{--}", $items1->adjunto);
                foreach ($array_adjunto as $value) {
                  $tmp = explode('.', $value);
                  $file_ext = end($tmp);
                  $icon = "";
                  if ($file_ext == "jpg" || $file_ext == "jpeg" || $file_ext == "png" || $file_ext == "gif" || $file_ext == "PNG" || $file_ext == "JPG" || $file_ext == "JPEG") {

                    $cadena .= '<li class="fa-camera" style="margin-bottom: 0;">
                      <div class="document-file" style="height: auto;">
                      <a  href="' . base_url() . 'public/uploads/reportes/' . $value . '" data-toggle="lightbox" data-gallery="gallery-soporte-2" data-type="image">
                      <img src="' . base_url() . 'public/uploads/reportes/' . $value . '" alt="Imagen no disponible">
                      </a>
                      </div>
                      <div class="document-name">
                       <a href="' . base_url() . 'public/uploads/reportes/' . $value . '"><i class="fas fa-download"></i>  ' . $value . '</a></div>
                      </li>';
                  } else if ($items1->adjunto == "") {
                  } else {
                    $cadena .= '<li class="fa-file" style="margin-bottom: 0;">
                      <div class="document-file">
                      <a href="' . base_url() . 'public/uploads/reportes/' . $value . '"><i class="far fa-file-pdf"></i></a>
                      </div>
                      <div class="document-name">
                      <a href="' . base_url() . 'public/uploads/reportes/' . $value . '"><i class="fas fa-download"></i> ' . $value . '</a></div>
                      </li>';
                  }
                }

                $cadena .=  '</ul>
                      </div>
                      </li>
                      </ul>';
              } //////// fin IF RESPUESTA

              else {

                $cadena .= '
            <li class="tknota media media-sm item-' . $items1->id . '">
            <div class="pull-left text-center" style="margin-right: 10px;">
            <img src="' . base_url() . 'public/images/avatar/' . $image . '"" width="80" height="80">
            </div>
            <div class="media-body inbox">
            <h5 class="media-heading"><b>Nota publicado por ' . $items1->user . '</b> <div class="pull-right"><small style="font-size: 12px;font-weight: 600;color:             #6aa1c3;">04/03/2020 07:48:49 pm</small> | 
             <i class="d-print-none far fa-trash-alt" onclick="deleteitem(\'' . $items1->id . '\',\'' . $id . '\',\'' . $_SESSION['token8291'] . '\')" style="cursor: pointer;"></i>
            </div></h5>
            <div style="font-size: 12.5px;margin-top: 11px;min-height: 50px;"><p>' . htmlspecialchars_decode($items1->descripcion) . '</p></div>
            <ul class="attached-document clearfix">

            </ul>
            </div>
            </li>';
              }
            }  //////////fin foreach

          }



          $data['id'] = $data_reportes->id;
          $data['id_socio'] = $this->request->getGet('socio');
          $data['asunto'] = $data_reportes->asunto;
          $data['estado'] = $data_reportes->estado;
          $data['tipo'] = $data_reportes->tipo;
          $data['nombre'] = $data_reportes->nombre . " " . $data_reportes->apellidos;
          $data['domicilio'] = $data_reportes->domicilio;
          $data['movil'] = $data_reportes->movil;
          $data['sindicato'] = $data_reportes->nombre_sindicato;
          $data['estado'] = $data_reportes->estado;
          $data['usuario'] = $data_reportes->usuario;
          $data['from'] = $data_reportes->_from;
          $data['cadena'] = $cadena;
          $data['fechaalta'] = date("d/m/Y h:i a", strtotime($data_reportes->fechaalta));
          $data['tipoacceso'] = $tipoacceso;
          $data['token'] = $_SESSION['token8291'];
          echo view('socios/lista_socios/view_reportes', $data);
        } else {
          echo "NO TIENES PERMISOS PARA VISUALIZAR AL SOCIO";
        }
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function update()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {
        $id = $this->request->getPost('id');
        $nombre = $this->request->getPost('nombre');
        $apellidos = $this->request->getPost('apellidos');
        $domicilio = $this->request->getPost('domicilio');
        $movil = $this->request->getPost('movil');
        $sindicato = $this->request->getPost('sindicato');
        $correo = $this->request->getPost('correo');
        $password = $this->request->getPost('password');
        $ine = $this->request->getPost('ine');
        $ubicacion = $this->request->getPost('ubicacion');
        $licencia = $this->request->getPost('licencia');


        $arrayName = [
          'nombre' => $nombre, 'apellidos' => $apellidos, 'sindicato' => $sindicato, 'password' => $password,
          'domicilio' => $domicilio, 'ubicacion' => $ubicacion, 'licencia' => $licencia, 'ine' => $ine,
          'movil' => $movil, 'correo' => $correo
        ];


        $update = $this->model_socios->update_data($id, $arrayName);
        if ($update) {
          $insert_log = $this->model_log->insert_log($id, "socio", "Socio editado <b>#" . $id . " " . $nombre . "</b>", $_SESSION['nombre']);
          echo 1;
        } else {
          echo 2;
        }
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function unidades_socio()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {
        $sindicato = $this->request->getGet('sindicato');
        $max_codigo = $this->model_socios->get_max_codigo($sindicato);
        if ($max_codigo) {
          $data['codigosindicato'] = $max_codigo->ultimo + 1;
        } else {
          $data['codigosindicato'] = $sindicato . '0001';
        }



        $data['id'] = $this->request->getGet('id');
        $data['idsindicato'] = $this->request->getGet('sindicato');
        $data['tipoacceso'] = $tipoacceso;
        echo view('socios/lista_socios/taps/unidades', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function get_codigo_sindicato()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {
        $sindicato = $this->request->getPost('id');
        $max_codigo = $this->model_socios->get_max_codigo($sindicato);
        if ($max_codigo) {
          $data['codigosindicato'] = $max_codigo->ultimo + 1;
          echo $data['codigosindicato'];
        } else {
          $data['codigosindicato'] = $sindicato . '0001';
          echo $data['codigosindicato'];
        }
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function documentos_cliente()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $id = $this->request->getGet('id');
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('DOCUMENTOS_VIEW_ESPECIAL'));

      if ($tipoacceso > 0) {
        $data['tipodocumentos'] = $this->model_selectores->get_all_tipo_documentos();
        $data['notas'] = $this->model_clientes->get_notas_cliente($id);
        $data['documentos'] = $this->model_clientes->get_documentos_cliente($id);
        $data['id'] = $this->request->getGet('id');
        $data['token'] = $_SESSION['token8291'];
        $data['tipoacceso'] = $tipoacceso;
        echo view('clientes/lista_clientes/taps/documentos', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function concesionarios_socio()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));

      if ($tipoacceso > 0) {
        $permiso_sindicato = $this->permiso_sindicato->get_permiso_socios($_SESSION['sindicatos']);
        $data['tblsocios'] = $this->model_selectores->get_all_socios($permiso_sindicato);
        $data['id'] = $this->request->getGet('id');
        $data['token'] = $_SESSION['token8291'];
        $data['tipoacceso'] = $tipoacceso;
        echo view('socios/lista_socios/taps/concesionarios', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function reportes_socio()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));

      if ($tipoacceso > 0) {

        $data['tipodocumentos'] = $this->model_selectores->get_all_tipo_documentos();
        $data['id'] = $this->request->getGet('id');
        $data['token'] = $_SESSION['token8291'];
        $data['tipoacceso'] = $tipoacceso;
        echo view('socios/lista_socios/taps/reportes', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function codigo_socio()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));

      if ($tipoacceso > 0) {


        // $codesDir = "./public/qr_code/";   
        $codeFile = date('d-m-Y-h-i-s');
        $id = $this->request->getGet('id');
        $params['data'] = $id;
        $params['level'] = 'H';
        $params['size'] = 10;

        //decimos el directorio a guardar el codigo qr, en este
        //caso una carpeta en la raíz llamada qr_code
        $params['savename'] = "./public/qr_code/qr_$id.png";
        //generamos el código qr

        $this->ciqrcode->generate($params);

        $data['img'] = "qr_$id.png";
        $data['id'] = $this->request->getGet('id');
        $data['sindicato'] = $this->request->getGet('sindicato');
        $data['token'] = $_SESSION['token8291'];
        $data['tipoacceso'] = $tipoacceso;
        echo view('socios/lista_socios/taps/qr', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function log_socio()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));

      if ($tipoacceso > 0) {


        $data['id'] = $this->request->getGet('id');
        $data['token'] = $_SESSION['token8291'];
        $data['tipoacceso'] = $tipoacceso;
        echo view('socios/lista_socios/taps/log', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function new_socio()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('NUEVO_SOCIO'));
      if ($tipoacceso > 0) {



        $nombre = $this->request->getPost('nombre');
        $apellidos = $this->request->getPost('apellidos');
        $domicilio = $this->request->getPost('domicilio');
        $movil = $this->request->getPost('movil');
        $sindicato = $this->request->getPost('sindicato');
        $correo = $this->request->getPost('correo');
        $password = $this->request->getPost('password');
        $ine = $this->request->getPost('ine');
        $ubicacion = $this->request->getPost('ubicacion');
        $licencia = $this->request->getPost('licencia');
        $status = $this->request->getPost('status');
        $tipounidad = $this->request->getPost('tipounidad');
        $matricula = $this->request->getPost('matricula');
        $factura = $this->request->getPost('factura');
        $descripcion = $this->request->getPost('descripcion');
        $modounidad = $this->request->getPost('modounidad');
        $codigosindicato = $this->request->getPost('codigosindicato');
        $true_matricula = $this->model_socios->check_matricula($matricula);
        if ($true_matricula) {

          echo "matricula-duplicada";
        } else {   //////////verifica la matricula

          date_default_timezone_set('America/Mexico_City');
          $arrayName = [
            'nombre' => $nombre, 'apellidos' => $apellidos, 'sindicato' => $sindicato, 'password' => $password,
            'domicilio' => $domicilio, 'ubicacion' => $ubicacion, 'licencia' => $licencia, 'ine' => $ine,
            'movil' => $movil, 'correo' => $correo, 'status' => $status, 'fechaalta' => date("Y-m-d H:i:s")
          ];


          $insert = $this->model_socios->new_socio($arrayName);
          if ($insert) {
            $row = $this->model_socios->get_ultimo_socio();
            $insert_log = $this->model_log->insert_log($row->ultimo, "socio", "NUevo soscio <b>#" . $row->ultimo . " " . $nombre . " " . $apellidos . "</b>", $_SESSION['nombre']);

            ///////////////////////////inserta unidad

            $base64 = null;
            if (isset($_FILES['fotounidad']['tmp_name'])) {
              $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
              $file_name = $_FILES['fotounidad']['name'];
              $tmp = explode('.', $file_name);
              $file_ext = end($tmp);
              $file_size = $_FILES['fotounidad']['size'];
              $file_tmp = $_FILES['fotounidad']['tmp_name'];
              $type = pathinfo($file_tmp, PATHINFO_EXTENSION);
              $data = file_get_contents($file_tmp);
              $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }

            $arrayName1 = [
              'idsocio' => $row->ultimo, 'tipounidad' => $tipounidad, 'matricula' => $matricula, 'factura' => $factura, 'descripcion' => $descripcion,
              'fechaalta' => date("Y-m-d H:i:s"), 'modounidad' => $modounidad, 'sindicato' => $sindicato, 'codigosindicato' => $codigosindicato,
              'foto' => $base64
            ];

            $insert1 = $this->model_socios->new_unidad($arrayName1);
            if ($insert1) {
              echo "success";
              $row1 = $this->model_socios->get_ultima_unidad();
              $insert_log = $this->model_log->insert_log($row->ultimo, "unidad", "Nueva unidad agregada <b>#" . $row1->ultimo . " Matricula:" . $matricula . "</b>", $_SESSION['nombre']);
            } else {
              echo "error";
            }


            //////////////////////////////////////// fin inserta unidad

          } else {
            echo "error";
          }
        } //////////////fin else check matricula


      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function new_unidad()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {

        $id_socio = $this->request->getPost('idsocio');
        $sindicato = $this->request->getPost('idsindicato');
        $tipounidad = $this->request->getPost('tipounidad');
        $matricula = $this->request->getPost('matricula');
        $factura = $this->request->getPost('factura');
        $descripcion = $this->request->getPost('descripcion');
        $modounidad = $this->request->getPost('modounidad');
        $codigosindicato = $this->request->getPost('codigosindicato');

        $base64 = null;

        if (isset($_FILES['fotounidad']['tmp_name'])) {
          $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
          $file_name = $_FILES['fotounidad']['name'];
          $tmp = explode('.', $file_name);
          $file_ext = end($tmp);
          $file_size = $_FILES['fotounidad']['size'];
          $file_tmp = $_FILES['fotounidad']['tmp_name'];
          $type = pathinfo($file_tmp, PATHINFO_EXTENSION);
          $data = file_get_contents($file_tmp);
          $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }



        date_default_timezone_set('America/Mexico_City');
        $arrayName = [
          'idsocio' => $id_socio, 'tipounidad' => $tipounidad, 'matricula' => $matricula, 'factura' => $factura, 'descripcion' => $descripcion,
          'fechaalta' => date("Y-m-d H:i:s"), 'modounidad' => $modounidad, 'sindicato' => $sindicato, 'codigosindicato' => $codigosindicato,
          'foto' => $base64
        ];

        $true_matricula = $this->model_socios->check_matricula($matricula);
        if ($true_matricula) {

          echo "matricula-duplicada";
        } else {

          $insert = $this->model_socios->new_unidad($arrayName);
          if ($insert) {
            echo 1;
            $row = $this->model_socios->get_ultima_unidad();
            $insert_log = $this->model_log->insert_log($id_socio, "unidad", "Nueva unidad agregada <b>#" . $row->ultimo . " Matricula:" . $matricula . "</b>", $_SESSION['nombre']);
          } else {
            echo "error";
          }
        }
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function edit_unidad()
  {

    if (isset($_SESSION['id_usuario8291']) && $this->request->getPost('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {
        $arrayName = array();
        $permiso_sindicato = $this->permiso_sindicato->get_permiso_socios($_SESSION['sindicatos']);
        $id_unidad = $this->request->getPost('id');
        $row = $this->model_socios->get_unidad_by_id($id_unidad, $permiso_sindicato);
        if ($row) {
          $arrayName = array(
            'id' => $row->id,
            'tipounidad' => $row->tipounidad,
            'matricula' => $row->matricula,
            'factura' => $row->factura,
            'descripcion' => $row->descripcion,
            'modounidad' => $row->modounidad,
            'sindicato' => $row->sindicato,
            'codigosindicato' => $row->codigosindicato,
            'estado' => $row->estado,
            'foto' => $row->foto
          );
        }
        echo json_encode($arrayName);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function edit_concesionario()
  {

    if (isset($_SESSION['id_usuario8291']) && $this->request->getPost('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {
        $arrayName = array();
        $permiso_sindicato = $this->permiso_sindicato->get_permiso_socios($_SESSION['sindicatos']);
        $id_concesionario = $this->request->getPost('id');
        $row = $this->model_socios->get_concesionarios_by_id($id_concesionario, $permiso_sindicato);
        if ($row) {
          $arrayName = array(
            'id' => $row->id,
            'nombre' => $row->nombre,
            'apellidos' => $row->apellidos,
            'password' => $row->password,
            'domicilio' => $row->domicilio,
            'ine' => $row->ine,
            'movil' => $row->movil,
            'correo' => $row->correo,
            'avatar' => $row->avatar,
            'status' => $row->status
          );
        }
        echo json_encode($arrayName);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function update_unidad()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {
        $id = $this->request->getPost('idunidad-edit');
        $id_socio = $this->request->getPost('idsocio-edit');
        $estado = $this->request->getPost('estado-edit');
        $tipounidad = $this->request->getPost('tipounidad-edit');
        $matricula = $this->request->getPost('matricula-edit');
        $factura = $this->request->getPost('factura-edit');
        $descripcion = $this->request->getPost('descripcion-edit');
        $modounidad = $this->request->getPost('modounidad-edit');
        $foto = $this->request->getPost('fotounidad-edit');

        if (isset($_FILES['fotounidad-edit']['tmp_name'])) {
          $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
          $file_name = $_FILES['fotounidad-edit']['name'];
          $tmp = explode('.', $file_name);
          $file_ext = end($tmp);
          $file_size = $_FILES['fotounidad-edit']['size'];
          $file_tmp = $_FILES['fotounidad-edit']['tmp_name'];
          $type = pathinfo($file_tmp, PATHINFO_EXTENSION);
          $data = file_get_contents($file_tmp);
          $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
          $arrayName = [
            'tipounidad' => $tipounidad, 'matricula' => $matricula, 'factura' => $factura, 'descripcion' => $descripcion,
            'modounidad' => $modounidad, 'estado' => $estado, 'foto' => $base64
          ];
        } else {
          $arrayName = [
            'tipounidad' => $tipounidad, 'matricula' => $matricula, 'factura' => $factura, 'descripcion' => $descripcion,
            'modounidad' => $modounidad, 'estado' => $estado
          ];
        }



        $update = $this->model_socios->update_unidad($id, $arrayName);
        if ($update) {
          $insert_log = $this->model_log->insert_log($id_socio, "unidad", "Unidad editada <b>#" . $id . " Matrícula" . $matricula . "</b>", $_SESSION['nombre']);
          echo 1;
        } else {
          echo 2;
        }
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function check_documento()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {
        $id_socio = $this->request->getPost('id_socio');
        $tipo = $this->request->getPost('tipo');
        $row = $this->model_socios->check_documento($id_socio, $tipo);
        if ($row) {
          $data = array("id" => $row->id, "bandera" => "true");
        } else {
          $data = array("id" => null, "bandera" => "false");
        }

        echo json_encode($data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }



  public function new_update_doc()
  {

    if (isset($_SESSION['id_usuario8291']) && $this->request->getPost('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {
        $id_socio = $this->request->getPost('id_socio');
        $id_documento = $this->request->getPost('id_documento');
        $estado = $this->request->getPost('estado-modal');
        $tipo = $this->request->getPost('tipo-modal');
        $action = $this->request->getPost('action-modal');
        $bandera = $this->request->getPost('bandera-modal');


        if ($action == "new") {
          if (isset($_FILES['fotodocumento-modal']['tmp_name'])) {
            $file_name = $_FILES['fotodocumento-modal']['name'];
            $tmp = explode('.', $file_name);
            $file_ext = end($tmp);
            $file_size = $_FILES['fotodocumento-modal']['size'];
            $file_tmp = $_FILES['fotodocumento-modal']['tmp_name'];
            $type = pathinfo($file_tmp, PATHINFO_EXTENSION);
            $data = file_get_contents($file_tmp);
            date_default_timezone_set('America/Mexico_City');
            if ($bandera == "existe") {
              $arrayName = [
                'adjunto' => $file_name, 'data' => base64_encode($data), 'fechaalta' => date('Y-m-d H:i:s'),
                'operador' => $_SESSION['id_usuario8291'], 'estado' => 'NO VALIDADO'
              ];
              $update = $this->model_socios->update_doc($id_documento, $arrayName);
              if ($update) {
                $insert_log = $this->model_log->insert_log($id_socio, "socios", "Documento editado <b>#" . $id_documento . " al socio " . $id_socio . "</b>", $_SESSION['nombre']);
                echo 1;
              } else {
                echo 2;
              }
            } else {
              $arrayName = [
                'idsocio' => $id_socio, 'tipo' => $tipo, 'adjunto' => $file_name, 'data' => base64_encode($data), 'fechaalta' => date('Y-m-d H:i:s'),
                'operador' => $_SESSION['id_usuario8291']
              ];
              $insert = $this->model_socios->new_doc($arrayName);
              if ($insert) {
                $row = $this->model_socios->get_ultimo_documento();
                $ultimo_id = $row->ultimo;
                $insert_log = $this->model_log->insert_log($id_socio, "socios", "Nuevo documento <b>#" . $ultimo_id . " al socio " . $id_socio . "</b>", $_SESSION['nombre']);
                echo 1;
              } else {
                echo 2;
              }
            }
          }
        } else {
        }
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function new_reporte()
  {

    if (isset($_SESSION['id_usuario8291']) && $this->request->getPost('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {
        $id_socio = $this->request->getPost('id_socio');
        $asunto = $this->request->getPost('asunto-modal');
        $tipo = $this->request->getPost('tipo-modal');
        $descripcion = $this->request->getPost('descripcion-modal');
        $action = $this->request->getPost('action-modal');

        $target_dir = 'public/uploads/reportes/';
        $cadena_names = "";
        if (isset($_FILES['adjunto-modal']['name'])) {

          $total_files = count($_FILES['adjunto-modal']['name']);


          for ($key = 0; $key < $total_files; $key++) {

            // Check if file is selected
            if (isset($_FILES['adjunto-modal']['name'][$key]) && $_FILES['adjunto-modal']['size'][$key] > 0) {

              $original_filename = $_FILES['adjunto-modal']['name'][$key];
              $target = $target_dir . basename($original_filename);
              $tmp  = $_FILES['adjunto-modal']['tmp_name'][$key];
              if ($total_files == 1) {
                $cadena_names .= $original_filename;
              } else {
                if ($total_files == $key + 1) {
                  $cadena_names .= $original_filename;
                } else {
                  $cadena_names .= $original_filename . "{--}";
                }
              }
              if (is_dir($target_dir)) {
                move_uploaded_file($tmp, $target);
              } else {
                mkdir($target_dir, 7777);
                move_uploaded_file($tmp, $target);
              }
            }
          }
        }

        if ($action == "new") {
          date_default_timezone_set('America/Mexico_City');
          $arrayName = [
            'idsocio' => $id_socio, 'tipo' => $tipo, 'fechaalta' => date('Y-m-d H:i:s'), '_from' => "sistema", 'usuario' => $_SESSION['nombre'],
            'asunto' => $asunto, 'estado' => "NUEVO"
          ];
          $insert = $this->model_socios->new_reporte($arrayName);
          if ($insert) {
            $row = $this->model_socios->get_ultimo_reporte();
            $ultimo_id = $row->ultimo;

            $arrayName1 = [
              'idreporte' => $ultimo_id, 'descripcion' => $descripcion, 'adjunto' => $cadena_names,
              'fechaaltare' => date('Y-m-d H:i:s'), 'state' => "respuesta", 'user' => 'sistema'
            ];
            $insert_ticket = $this->model_socios->new_ticket($arrayName1);

            if ($insert_ticket) {
              $insert_log = $this->model_log->insert_log($id_socio, "socios", "Nuevo reporte <b>#" . $ultimo_id . " al socio " . $id_socio . "</b>", $_SESSION['nombre']);
              echo 1;
            }
          } else {
            echo 2;
          }
        } else {
        }
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function add_respuesta()
  {

    if (isset($_SESSION['id_usuario8291']) && $this->request->getPost('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {
        $id_re = $this->request->getPost('id');
        $id_socio = $this->request->getPost('id_socio');
        $descripcion = $this->request->getPost('descripcion');


        $target_dir = 'public/uploads/reportes/';
        $cadena_names = "";
        if (isset($_FILES['adjunto']['name'])) {
          $total_files = count($_FILES['adjunto']['name']);
          for ($key = 0; $key < $total_files; $key++) {
            if (isset($_FILES['adjunto']['name'][$key]) && $_FILES['adjunto']['size'][$key] > 0) {
              $original_filename = $_FILES['adjunto']['name'][$key];
              $target = $target_dir . basename($original_filename);
              $tmp  = $_FILES['adjunto']['tmp_name'][$key];
              if ($total_files == 1) {
                $cadena_names .= $original_filename;
              } else {
                if ($total_files == $key + 1) {
                  $cadena_names .= $original_filename;
                } else {
                  $cadena_names .= $original_filename . "{--}";
                }
              }
              if (is_dir($target_dir)) {
                move_uploaded_file($tmp, $target);
              } else {
                mkdir($target_dir, 7777);
                move_uploaded_file($tmp, $target);
              }
            }
          } ///EN FOR

        } /// END IF FILES


        date_default_timezone_set('America/Mexico_City');
        $arrayName1 = [
          'idreporte' => $id_re, 'descripcion' => $descripcion, 'adjunto' => $cadena_names,
          'fechaaltare' => date('Y-m-d H:i:s'), 'state' => 0, 'user' => $_SESSION['usuario'], 'state' => 'respuesta'
        ];
        $insert_ticket = $this->model_socios->new_ticket($arrayName1);
        if ($insert_ticket) {
          $insert_log = $this->model_log->insert_log($id_socio, "socios", "Reporte repondido <b>#" . $id_re . " al socio " . $id_socio . "</b>", $_SESSION['nombre']);
          $update_re = $this->model_socios->update_status_reporte($id_re, "ATENDIDO");
          echo "success";
        } else {
          echo "error";
        }
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function add_nota()
  {

    if (isset($_SESSION['id_usuario8291']) && $this->request->getPost('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {
        $id_re = $this->request->getPost('id');
        $id_socio = $this->request->getPost('id_socio');
        $descripcion = $this->request->getPost('nota');


        date_default_timezone_set('America/Mexico_City');
        $arrayName1 = [
          'idreporte' => $id_re, 'descripcion' => $descripcion,
          'fechaaltare' => date('Y-m-d H:i:s'), 'state' => 0, 'user' => $_SESSION['usuario'], 'state' => 'nota'
        ];
        $insert_ticket = $this->model_socios->new_ticket($arrayName1);
        if ($insert_ticket) {
          $insert_log = $this->model_log->insert_log($id_socio, "socios", "Nota agregada al reporte <b>#" . $id_re . " -- socio " . $id_socio . "</b>", $_SESSION['nombre']);
          $update_re = $this->model_socios->update_status_reporte($id_re, "ATENDIDO");
          echo "success";
        } else {
          echo "error";
        }
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function update_reporte()
  {

    if (isset($_SESSION['id_usuario8291']) && $this->request->getPost('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {
        $id_re = $this->request->getPost('id-reporte');
        $id_socio = $this->request->getPost('id-socio');
        $asunto = $this->request->getPost('asunto-reporte');
        $tipo = $this->request->getPost('tipo-reporte');
        $estado = $this->request->getPost('estado-reporte');

        $arrayName1 = ['asunto' => $asunto, 'tipo' => $tipo, 'estado' => $estado];
        $update = $this->model_socios->update_reporte($id_re, $arrayName1);
        if ($update) {
          $insert_log = $this->model_log->insert_log($id_socio, "socios", "Asunto modificado al reporte <b>#" . $id_re . " -- socio " . $id_socio . "</b>", $_SESSION['nombre']);
          echo "success";
        } else {
          echo "error";
        }
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function delete_unidad()
  {

    if (isset($_SESSION['id_usuario8291']) && $this->request->getPost('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {

        $id = $this->request->getPost('id');
        $id_socio = $this->request->getPost('idsocio');
        $matricula = $this->request->getPost('matricula');
        $insert_log = $this->model_log->insert_log($id_socio, "socios", "Unidad eliminada <b>#" . $id . " " . $matricula . "</b>", $_SESSION['nombre']);
        $delete = $this->model_socios->delete_unidad($id);
        if ($delete) echo "succes";
        else echo "error";
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function delete_respuesta()
  {

    if (isset($_SESSION['id_usuario8291']) && $this->request->getPost('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {

        $id_ti = $this->request->getPost('id');
        $id_socio = $this->request->getPost('id_socio');
        $id_reporte = $this->request->getPost('id_reporte');
        $insert_log = $this->model_log->insert_log($id_socio, "socios", "Respuesta eliminada <b>#" . $id_ti . " del reporte: " . $id_reporte . "</b>", $_SESSION['nombre']);
        $delete = $this->model_socios->delete_respuesta($id_ti);
        if ($delete) echo "success";
        else echo "error";
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function get_ubicaciones()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('MAPA_SOCIOS'));
      if ($tipoacceso > 0) {
        $arrayUbis = array();
        $latitud = "";
        $longitud = "";
        $permiso_sindicato = $this->permiso_sindicato->get_permiso_socios($_SESSION['sindicatos']);
        $id_sindicato = $this->request->getGet('sindicatos');
        if ($id_sindicato == "%%") {
          $result = $this->model_socios->get_all_ubicaciones($permiso_sindicato);
        } else {
          $result = $this->model_socios->get_ubicaciones($id_sindicato);
        }
        if ($result) {
          foreach ($result as $items) {

            if ($items->ubicacion == "") {
            } else {
              $items->ubicacion = explode(",", $items->ubicacion);
              $latitud = $items->ubicacion[0];
              $longitud = $items->ubicacion[1];
              $arrayitems = array(
                'id_socio' => $items->id,
                'descr' => '<h5><span class="far fa-user"></span> ' . $items->nombre . " " . $items->apellidos . '</h5><p><span class="fas fa-home"> ' . $items->domicilio . '</span> </p><p><span class="fas fa-mobile-alt"></span>  ' . $items->movil . '</p><p><i class="fas fa-laptop"></i> ' . $items->nombre_sindicato . '</p>',
                'latitud' => $latitud,
                'longitud' => $longitud,
                'icono' => 'ONLINE',
                'elatitud' => "",
                'elongitud' => null,
                'api' => 'AIzaSyAwtUrf9EpyekCZU6gLEBzX5KKTtAlnM40'
              );
              array_push($arrayUbis, $arrayitems);
            }
          }
        }
        echo json_encode($arrayUbis);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function dowload_doc()
  {

    if (isset($_SESSION['id_usuario8291']) && $this->request->getGet('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('DOCUMENTOS_VIEW_ESPECIAL'));
      if ($tipoacceso > 0) {
        $arrayName = array();
        $id_doc = $this->request->getGet('id');
        $permiso_sindicato = $this->permiso_sindicato->get_permiso_socios($_SESSION['sindicatos']);
        $row_doc = $this->model_socios->get_documentos_by_id($id_doc, $permiso_sindicato);
        if ($row_doc) {
          $file = $row_doc->adjunto;
          $data = $row_doc->data;
          $path = "./writable/tmp/" . $file;

          $status = file_put_contents($path, base64_decode($data));
          if ($status) {
            if (is_dir("./writable/tmp/")) {

              if (file_exists("./writable/tmp/" . $file)) {

                $tmp = explode('.', $file);
                $file_ext = end($tmp);
                $type = "";
                header("Content-Length: " . filesize($path));
                if ($file_ext == "jpg" || $file_ext == "jpeg" || $file_ext == "png" || $file_ext == "gif") {
                  $type = 'image/' . $file_ext;
                } else {
                  $type = 'application/' . $file_ext;
                }
                header("Content-type: " . $type);
                header("Content-disposition: inline; filename=" . basename($path));
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                ob_clean();
                flush();
                readfile($path);
              }
            }
          } else {
            echo "Upload failed";
          }
        } else {
          echo "No tienes permisos para visualizar este archivo";
        }
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function new_concesionarios()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_CONCESIONARIO'));
      if ($tipoacceso > 0) {

        $id = $this->request->getPost('idconcesionario');
        $nombre = $this->request->getPost('nombre');
        $apellidos = $this->request->getPost('apellidos');
        $idsocio = $this->request->getPost('idsocio');
        $domicilio = $this->request->getPost('domicilio');
        $ine = $this->request->getPost('ine');
        $movil = $this->request->getPost('movil');
        $correo = $this->request->getPost('correo');
        $avatar = $this->request->getPost('avatar');
        $password = $this->request->getPost('password');
        $status = $this->request->getPost('status');
        $action = $this->request->getPost('action');

        $arrayPost = [
          'nombre' => $nombre, 'apellidos' => $apellidos, 'idsocio' => $idsocio, 'domicilio' => $domicilio, 'ine' => $ine, 'movil' => $movil,
          'correo' => $correo, 'avatar' => $avatar, 'password' => $password, 'status' => $status, 'fechaalta' => date("Y-m-d H:i:s")
        ];
        if ($action == "new") {
          $insert = $this->model_socios->new_concesionario($arrayPost);
          if ($insert) {
            $row = $this->model_socios->get_ultimo_concesionario();
            $ultimo_id = $row->ultimo;
            $insert_log = $this->model_log->insert_log($idsocio, "socios", "Nuevo concesionario <b>#" . $ultimo_id . " al socio " . $idsocio . "</b>", $_SESSION['nombre']);
            echo 1;
          } else {
            echo 2;
          }
        } else {
          $insert_log = $this->model_log->insert_log($idsocio, "socios", "Concesionario editado <b>#" . $id . " al socio " . $idsocio . "</b>", $_SESSION['nombre']);
          $update = $this->model_socios->update_concesionario($id, $arrayPost);
          if ($update) {
            echo 1;
          } else {
            echo 2;
          }
        }
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }

  public function delete_concesionarios()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_CONCESIONARIO'));
      if ($tipoacceso > 0) {
        $id = $this->request->getPost('id');
        $id_socio = $this->request->getPost('id_socio');
        $nombre = $this->request->getPost('nombre');
        $insert_log = $this->model_log->insert_log($id_socio, "socios", "Concesionario eliminado <b>#" . $id . " " . $nombre . "</b>", $_SESSION['nombre']);
        $delete = $this->model_socios->delete_concesionario($id);
        if ($delete) {
          echo 1;
        } else {
          echo 2;
        }
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }

  public function codigo_qr_unidad()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_UNIDADES'));

      if ($tipoacceso > 0) {


        // $codesDir = "./public/qr_code/";   
        $codeFile = date('d-m-Y-h-i-s');
        $id = $this->request->getPost('id');
        $params['data'] = $id;
        $params['level'] = 'H';
        $params['size'] = 10;

        //decimos el directorio a guardar el codigo qr, en este 
        //caso una carpeta en la raíz llamada qr_code
        $params['savename'] = "./public/qr_code/unidades/qr_$id.png";
        //generamos el código qr

        $this->ciqrcode->generate($params);

        echo "qr_$id.png";
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function mapaclientes()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('MAPA_CLIENTES'));
      if ($tipoacceso > 0) {
        $permiso_sucursal_nodo = $this->permiso_sucursal->get_permiso_sucursal_nodo($_SESSION['sucursales']);
        $permiso_sucursal_select = $this->permiso_sucursal->get_permiso_sucursal_select($_SESSION['sucursales']);
        $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal($_SESSION['sucursales']);
        $ubica = $this->model_clientes->get_all_coordenadas("", $permiso_sucursal);
        $nodos = $this->model_selectores->get_all_nodos_by_usuario($permiso_sucursal_nodo);
        $sucursales = $this->model_selectores->get_all_sucursales_by_user($permiso_sucursal_select);
        $server = $this->model_clientes->get_all_coordenadas_server($permiso_sucursal_nodo);

        /*  foreach ($geocercas as $key => $value) {
          var_dump($value);
          $coordenadas=json_decode($value->coordenadas);

        } */
        $data['data'] = $ubica;
        $data['nodos'] = $nodos;
        $data['server'] = $server;
        $data['sucursales'] = $sucursales;

        echo view('clientes/mapa_clientes/mapa_clientes.php', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
  public function filter_clientes_map()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('MAPA_CLIENTES'));
      if ($tipoacceso > 0) {
        $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal($_SESSION['sucursales']);
        $permiso_sucursal_nodo = $this->permiso_sucursal->get_permiso_sucursal_nodo($_SESSION['sucursales']);
        $nodo = $this->request->getPost('nodo');
        $sucursal = $this->request->getPost('sucursal');
        $ubica = $this->model_clientes->get_all_coordenadas2($sucursal, $nodo, $permiso_sucursal);
        $server = $this->model_clientes->get_all_coordenadas_server($permiso_sucursal_nodo);
        $data['data'] = $ubica;
        $data['server'] = $server;
        echo json_encode($data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
  public function filter_clientes_map_geocercas()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('GEOCERCAS'));
      if ($tipoacceso > 0) {
        $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal($_SESSION['sucursales']);
        $permiso_sucursal_nodo = $this->permiso_sucursal->get_permiso_sucursal_nodo($_SESSION['sucursales']);
        $nodo = $this->request->getPost('nodo');
        $sucursal = $this->request->getPost('sucursal');
        $ubica = $this->model_clientes->get_all_coordenadas2($sucursal, $nodo, $permiso_sucursal);
        $server = $this->model_clientes->get_all_coordenadas_server($permiso_sucursal_nodo);
        $data['data'] = $tipoacceso >= 2 ? $ubica : array();
        $data['server'] = $server;
        echo json_encode($data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
  public function ticket_cliente()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('TICKET'));
      $record = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('UNANSWERED_TICKET'));
      $id = $this->request->getGet('id');
      $cliente = $this->model_clientes->get_user_by_id($id);
      if ($tipoacceso >= 1) {
        $data['record'] = $record;
        $data['id'] = $this->request->getGet('id');
        $data['tipoacceso'] = $tipoacceso;
        $data['tbldepa'] = $this->model_selectores->get_departamentos();
        $data['tbluser'] = $this->model_selectores->get_usuarios();
        $data['cliente'] = $cliente[0];
        echo view('clientes/lista_clientes/taps/tickets', $data);
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }

  public function get_tickets_record()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('TICKET'));
      if ($tipoacceso >= 1) {
        $id = $this->request->getGet('id'); //id cliente
        $dep = $this->request->getGet('dep'); //obtener por get el departamento
        $id_ = $this->request->getGet('id_'); //id usuario
        $tickets = $this->model_ticket->get_tickets_record($id, $dep, $id_);
        //validar permiso especial cerrar ticket
        $close_ticket = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('CLOSE_TICKET'));
        //validar permiso answered
        $record_ticket = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('UNANSWERED_TICKET'));

        $array_unanswered = array();
        $tool = "";
        $status = "";

        if ($tickets) {
          foreach ($tickets as $items) {

            if ($items->estado == "abierto") {
              $status = "<span class='badge badge-success'>" . 'ABIERTO' . "</span>";
            } else if ($items->estado == "respondido") {
              $status = "<span class='badge badge-warning'>" . 'RESPONDIDO' . "</span>";
            } else if ($items->estado == "cerrado") {
              $status = "<span class='badge badge-danger'>" . 'CERRADO' . "</span>";
            }

            if ($record_ticket == 1) {
              $tool = "";
            } else if ($record_ticket == 2 && $close_ticket == 3 && $items->estado == "abierto" || $items->estado == "respondido") {
              $tool = '<td class=" text-center">
                          <a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_ticket(\'' . $items->id . '\',\'' . $items->asunto . '\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                          <a onclick="view_ticket(\'' . $items->id . '\'); get_all_tickets_client(\'' . $items->idcliente . '\');" data-toggle="tooltip" title="Ver ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                          <a class="btn btn-default btn-icon btn-sm" role="button"  data-trigger="click" data-ajaxpoputo="ticket/popover?id=' . $items->id . '"><i class="far fa-eye"></i></a>
                          <a onclick="close_ticket(\'' . $items->id . '\')" data-toggle="tooltip" title="Cerrar ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-times-circle"></i></a></td>';
            } else if ($record_ticket == 2 && $items->estado == "cerrado") {
              $tool = '<td class=" text-center">
                          <a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_ticket(\'' . $items->id . '\',\'' . $items->asunto . '\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                          <a onclick="view_ticket(\'' . $items->id . '\'); get_all_tickets_client(\'' . $items->idcliente . '\');" data-toggle="tooltip" title="Ver ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                          <a class="btn btn-default btn-icon btn-sm" role="button"  data-trigger="click" data-ajaxpoputo="ticket/popover?id=' . $items->id . '"><i class="far fa-eye"></i></a>
                          </td>';
            } else if ($record_ticket == 3 && $close_ticket == 3 && $items->estado == "abierto" || $items->estado == "respondido") {
              $tool = '<td class=" text-center">
                          <a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_ticket(\'' . $items->id . '\',\'' . $items->asunto . '\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                          <a onclick="view_ticket(\'' . $items->id . '\'); get_all_tickets_client(\'' . $items->idcliente . '\');" data-toggle="tooltip" title="Ver ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                          <a class="btn btn-default btn-icon btn-sm" role="button"  data-trigger="click" data-ajaxpoputo="ticket/popover?id=' . $items->id . '"><i class="far fa-eye"></i></a>
                          <a onclick="close_ticket(\'' . $items->id . '\')" data-toggle="tooltip" title="Cerrar ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-times-circle"></i></a></td>';
            } else if ($record_ticket == 3 && $items->estado == "cerrado") {
              $tool = '<td class=" text-center">
                          <a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_ticket(\'' . $items->id . '\',\'' . $items->asunto . '\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                          <a onclick="view_ticket(\'' . $items->id . '\'); get_all_tickets_client(\'' . $items->idcliente . '\');" data-toggle="tooltip" title="Ver ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                          <a class="btn btn-default btn-icon btn-sm" role="button"  data-trigger="click" data-ajaxpoputo="ticket/popover?id=' . $items->id . '"><i class="far fa-eye"></i></a>
                         </td>';
            }
            /*if ($tipoacceso==2 && $close_ticket==1) {
                          $tool='<td class=" text-center"><a onclick="view_ticket(\''.$items->id.'\')" data-toggle="tooltip" title="Ver ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                          <a onclick="popover_ticket(\''.$items->id.'\')" data-toggle="tooltip" title="Vista previa" class="btn btn-default btn-icon btn-sm"><i class="far fa-eye"></i></a>
                          <a onclick="close_ticket(\''.$items->id.'\')" data-toggle="tooltip" title="Cerrar ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-times-circle"></i></a></td>'; 
                      }else {
                          $tool='<td class=" text-center"><a onclick="view_ticket(\''.$items->id.'\')" data-toggle="tooltip" title="Ver ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                          <a onclick="popover_ticket(\''.$items->id.'\')" data-toggle="tooltip" title="Vista previa" class="btn btn-default btn-icon btn-sm"><i class="far fa-eye"></i></a></td>'; 
                      }
                      if ($tipoacceso==3 && $close_ticket==1) {
                          $tool='<td class=" text-center"><a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_ticket(\''.$items->id.'\',\''.$items->asunto.'\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                          <a onclick="view_ticket(\''.$items->id.'\')" data-toggle="tooltip" title="Ver ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                          <a onclick="popover_ticket(\''.$items->id.'\')" data-toggle="tooltip" title="Vista previa" class="btn btn-default btn-icon btn-sm"><i class="far fa-eye"></i></a>
                          <a onclick="close_ticket(\''.$items->id.'\')" data-toggle="tooltip" title="Cerrar ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-times-circle"></i></a></td>';
                      }else{
                          $tool='<td class=" text-center"><a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_ticket(\''.$items->id.'\',\''.$items->asunto.'\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                          <a onclick="view_ticket(\''.$items->id.'\')" data-toggle="tooltip" title="Ver ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                          <a onclick="popover_ticket(\''.$items->id.'\')" data-toggle="tooltip" title="Vista previa" class="btn btn-default btn-icon btn-sm"><i class="far fa-eye"></i></a></td>';
                      }*/



            $array_items = array(
              "id" => $items->id,
              "departamento" => '<span style="color:' . $items->color . ';border: 1px solid ' . $items->color . ';padding: 2px 5px;border-radius: 5px;text-transform: uppercase;font-size: 9.5px;font-weight: 700;">' . strtoupper($items->departamento) . '</span>',
              "asunto" => "<a href='#ticket/view_ticket?id=" . $items->id . "'>" . $items->asunto . "</a>",
              "tecnico" => $items->tecnico,
              "fecha" => date('d/m/Y - h:i a', strtotime($items->fecha)),
              "estado" => $status,
              "operador" => $items->operador,
              "respuesta" => $items->respuesta,
              "tool" => $tool
            );
            array_push($array_unanswered, $array_items);
          }
        }
        echo '{"data":' . json_encode($array_unanswered) . '}';
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }


  public function nuevo_cliente()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $permiso_sucursal_nodo = $this->permiso_sucursal->get_permiso_sucursal_nodo($_SESSION['sucursales']);
      $permiso_sucursal_select = $this->permiso_sucursal->get_permiso_sucursal_select($_SESSION['sucursales']);
     // $data['zonas'] = $this->model_selectores->get_all_zonas();
    //  $data['nodos'] = $this->model_selectores->get_all_nodos_by_usuario($permiso_sucursal_nodo);
   //   $data['emisores'] = $this->model_selectores->get_all_antenas_amisoras();
      $data['sucursales'] = $this->model_selectores->get_all_sucursales_by_user($permiso_sucursal_select);
      $data['fecha'] = date('d/m/Y');

      return view('clientes/lista_clientes/nuevo_cliente', $data);
    }
  }
  public function get_planes()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $nodo = $this->request->getPost('nodo');
      $planes = $this->model_selectores->get_planes_by_nodo($nodo);
      echo $planes ? $planes : "error";
    }
  }
  public function modal_mapa()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $data["var"] = true;
      return view('clientes/utils/modal_mapa', $data);
    }
  }
  public function grupo_ipv4_disponibles()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $nodo = $this->request->getPost('nodo');
      $all_ipv4 = $this->model_clientes->get_alll_ipv4($nodo);
      $ips = explode(",", $all_ipv4[0]->rangos);
      $cidr = $all_ipv4[0]->cidr;
      $id = $all_ipv4[0]->id;
      $nombre = $all_ipv4[0]->nombre;
      foreach ($ips as $ip) {
        $expl_mediu_ip = explode('.', $ip, -1);
        $string_medium_ip = $expl_mediu_ip[0] . "." . $expl_mediu_ip[1] . "." . $expl_mediu_ip[2] . ".";
        $count_ips = $this->model_clientes->get_cound_ips($nodo, $string_medium_ip);
        if (!$count_ips) {
          $count = "No se puedo calcular las";
        }
        $count = 255 - $count_ips[0]->num_ips;
        if ($ips[0] == $ip) $count = $count - 2;
        if ($ips[count($ips) - 1] == $ip) $count--;

        $data[] = [
          'rango' => $ip,
          'id' => $id,
          'cidr' => $cidr,
          'red' => $ip . " ($count Ips disponibles) -" . $nombre,
        ];
      }
      return json_encode($data);
    }
  }
  public function ipv4_disponibles()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getPost('id');
      $nodo = $this->request->getPost('nodo');
      $cidr = $this->request->getPost('cidr');
      $red = $this->request->getPost('red');
      $ip_breack = explode(".", $red, -1);
      $medium_ip = $ip_breack[0] . "." . $ip_breack[1] . "." . $ip_breack[2] . ".";
      $ips = $this->model_clientes->ip_individual($nodo, $medium_ip);
      $rango = $this->model_clientes->get_alll_ipv4_solo_rangos($nodo);
      $rango_explode = explode(',', $rango[0]->rangos);
      $min_ip_no_utilizable = $rango_explode[0];
      $max_rango = count($rango_explode) - 1;
      $max_ip_no_utilizable = $rango_explode[$max_rango];
      $data = array();
      if ($ips) {
        for ($i = 0; $i <= 255; $i++) {
          if ($red == $min_ip_no_utilizable and $i == 0) $i = $i + 2;
          $ip_nueva = $medium_ip . $i;
          $igual = false;
          foreach ($ips as $ip) {
            $expl_ip = explode(",", $ip->ip);
            for ($e = 0; $e < count($expl_ip); $e++) {
              if ($ip_nueva == $expl_ip[$e]) {
                $igual = true;
              }
            }
          }
          if (!$igual) {
            $data[] = ["ip" => $ip_nueva];
          }
          if ($red == $max_ip_no_utilizable and $i == 254) $i = $i + 2;
        }
      } else {
        for ($i = 0; $i < 255; $i++) {
          $ip_nueva = $medium_ip . $i;
          $data[] = ["ip" => $ip_nueva];
        }
      }
      return json_encode($data);
    }
  }
  public function guardar_nuevo_cliente()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'ESCRITURA', $_SESSION['id_usuario8291'])) {

      $usuarios_sucursal = $this->request->getPost("usuarios[sucursal]");
      $usuarios_codigo = $this->request->getPost("usuarios[codigo]");
      $usuarios_cedula = $this->request->getPost("usuarios[cedula]");
      $usuarios_nombre = $this->request->getPost("usuarios[nombre]");
      $usuarios_direccion_principal = $this->request->getPost("usuarios[direccion_principal]");
      $usuarios_movil = $this->request->getPost("usuarios[movil]");
      $usuarios_correo = $this->request->getPost("usuarios[correo]");


    
      $config_tipopago = $this->request->getPost("config[tipopago]");
      $config_diapago = $this->request->getPost("config[diapago]");
      $config_diafactura = $this->request->getPost("config[diafactura]");
      $config_impuesto = $this->request->getPost("config[impuesto]");
      $config_mora = $this->request->getPost("config[mora]");

    
      $config_tipoaviso = $this->request->getPost("config[tipoaviso]");
      $config_avisopantalla = $this->request->getPost("config[avisopantalla]");
      $config_tiporecordatorio = $this->request->getPost("config[tiporecordatorio]");
      $config_avisosms = $this->request->getPost("config[avisosms]");
      $config_avisosms2 = $this->request->getPost("config[avisosms2]");
      $config_avisosms3 = $this->request->getPost("config[avisosms3]");

      
     

      $impuestos1 = $this->request->getPost("impuestos[1]");
      $impuestos2 = $this->request->getPost("impuestos[2]");
      $impuestos3 = $this->request->getPost("impuestos[3]");
      $impuestos = "i:1;s:" . strlen($impuestos1) . ":\"" . $impuestos1 . "\";";
      $impuestos .= "i:2;s:" . strlen($impuestos2) . ":\"" . $impuestos2 . "\";";
      $impuestos .= "i:3;s:" . strlen($impuestos3) . ":\"" . $impuestos3 . "\";";
      $otros_impuestos = "a3{" . $impuestos . "}";
      $error = false;
      $client = false;
     
      
      $data_usuario = [
        'nombre' => $usuarios_nombre,
        'estado' => 'ACTIVO',
        'correo' => $usuarios_correo,
        'movil' => $usuarios_movil,
        'cedula' => $usuarios_cedula,
        'codigo' => $usuarios_codigo,
        'direccion_principal' => $usuarios_direccion_principal,
        'sucursal' => $usuarios_sucursal,
      ];
      $id_new_cliente = $this->model_clientes->new_usuario($data_usuario);
      if (!$id_new_cliente) {
        $data_ajax['estado'] = 2;
        return json_encode($data_ajax);
      }
      $data_aviso_user = [
        'cliente' => $id_new_cliente,
        'mora' => $config_mora,
        'impuesto' => $config_impuesto,
        'chat' => 0,
        'diapago' => $config_diapago,
        'tipopago' => $config_tipopago,
        'tipoaviso' => $config_tipoaviso,
        'diafactura' => $config_diafactura,
        'avisopantalla' => $config_avisopantalla,
        'avisosms' => $config_avisosms,
        'avisosms2' => $config_avisosms2,
        'avisosms3' => $config_avisosms3,  
        'avatar_color' => '#' . strtoupper(substr(md5(time()), 0, 6)),
        'otros_impuestos' => $otros_impuestos,

      ];
      $id_new_configuracion = $this->model_clientes->new_configuracion($data_aviso_user);
      if (!$id_new_configuracion) {
        $result_delete_usuario = $this->model_clientes->delete_clientes($id_new_cliente);
        $data_ajax['estado'] = 2;
        return json_encode($data_ajax);
      }
      else{
       
        $txt_log = "Nuevo cliente registrado ($usuarios_nombre) - Cliente ID: $id_new_cliente";
        $insert_log = $this->model_log->insert_log($id_new_cliente, "clientes", $txt_log, $_SESSION['id_usuario8291']);
        $data_ajax['estado'] = 1;
        return json_encode($data_ajax);

      }
      
    }
  }

  //funciones Nav servicios -Cristian
  public function modal_nuevo_servicio()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getGet('id');
      $permiso_sucursal_nodo = $this->permiso_sucursal->get_permiso_sucursal_nodo($_SESSION['sucursales']);
      $permiso_sucursal_select = $this->permiso_sucursal->get_permiso_sucursal_select($_SESSION['sucursales']);
      $data['nodos'] = $this->model_selectores->get_all_nodos_by_usuario($permiso_sucursal_nodo);
      $data['emisores'] = $this->model_selectores->get_all_antenas_amisoras();
      $data['sucursales'] = $this->model_selectores->get_all_sucursales_by_user($permiso_sucursal_select);
      $data['fecha'] = date('d/m/Y');
      $data['id'] = $id;
      return view('clientes/lista_clientes/taps/sub_taps/utils/modal_nuevo_servicio_internet', $data);
    }
  }
  public function get_productos_y_servicios_recurrentes()
  {
    $permiso = $this->seguridad->Acceso('LISTA_CLIENTES', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $idcliente = $this->request->getGet('idcliente');
      $servicios = $this->model_clientes->get_servicios_recurrentes($idcliente);
      if ($servicios) {
        foreach ($servicios as $servicio) {
          $servicio->monto = "MX$" . $servicio->monto;
          $servicio->fecha = date('d/m/Y', strtotime($servicio->fecha));
          $servicio->estado == "activo" ? $servicio->estado = "<span class=\"label label-warning\">ACTIVO</span>" : $servicio->estado = "<span class=\"label label-success\">PAGADO</span>";
          if ($servicio->totalcuota == 0) {
            $servicio->cuotas = "Mensual";
          } else {
            $servicio->cuotas = $servicio->ncuota . " de " . $servicio->totalcuota;
          }
          $idservicio = $servicio->id;
          if ($permiso >= 2) {
            $servicio->tools = "
          <a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"remove_recurrente('$idservicio')\" data-original-title=\"Eliminar\">
             <i class=\"far fa-trash-alt\" aria-hidden=\"true\"></i>
          </a>
          ";
          } else {
            $servicio->tools = "";
          }
        }
        $data['data'] = $servicios;
      } else {
        $data['data'] = array();
      }
      return json_encode($data);
    }
  }
  public function new_otro_servicio()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $servicio_idcliente = $this->request->getPost('servicio[idcliente]');
      $servicio_sucursal = $this->request->getPost('servicio[sucursal]');
      $servicio_nodo = $this->request->getPost('servicio[nodo]');
      $servicio_firewall_state = $this->request->getPost('servicio[firewall_state]');
      $servicio_idperfil = $this->request->getPost('servicio[idperfil]');
      $servicio_descripcion = $this->request->getPost('servicio[descripcion]');
      $servicio_costo = $this->request->getPost('servicio[costo]');
      $servicio_tipoipv4 = $this->request->getPost('servicio[tipoipv4]');
      $servicio_redipv4 = $this->request->getPost('servicio[redipv4]');
      $servicio_ips = $this->request->getPost('servicio[ip]');
      $servicio_ip = "";
      foreach ($servicio_ips as $key => $ip) {
        if ($key == 0) {
          $servicio_ip .= $ip;
        } else {
          $servicio_ip .= "," . $ip;
        }
      }
      $servicio_mac = $this->request->getPost('servicio[mac]');
      $servicio_pppuser = $this->request->getPost('servicio[pppuser]');
      $servicio_ppppass = $this->request->getPost('servicio[ppppass]');
      $servicio_direccion = $this->request->getPost('servicio[direccion]');
      $servicio_coordenadas = $this->request->getPost('servicio[coordenadas]');
      $servicio_instalado = $this->request->getPost('servicio[instalado]');
      $servicio_instalado = str_replace("/", "-", $servicio_instalado);
      $servicio_instalado = date('d-m-Y', strtotime($servicio_instalado));
      $servicio_instalado = date('Y-m-d', strtotime($servicio_instalado));
      $servicio_emisor = $this->request->getPost('servicio[emisor]');
      $servicio_ipap = $this->request->getPost('servicio[ipap]');
      $servicio_antena = $this->request->getPost('servicio[antena]');
      $servicio_snmp_comunidad = $this->request->getPost('servicio[snmp_comunidad]');
      $servicio_user_ubnt = $this->request->getPost('servicio[user_ubnt]');
      $servicio_pass_ubnt = $this->request->getPost('servicio[pass_ubnt]');
      $nombre_cliente = $this->finanzas->get_nombre_cliente($servicio_idcliente);
      $error = false;
      $client = false;
      $up_dow = $this->model_clientes->get_velocidades($servicio_idperfil);
      $server = $this->model_clientes->get_server($servicio_nodo);
      if ($server and $up_dow) {
        $server = $server[0];
        $up_dow = $up_dow[0];
      } else {
        $data_ajax['estado'] = 2;
        return json_encode($data_ajax);
      }

      $data_servicio = [
        'idcliente' => $servicio_idcliente,
        'idperfil' => $servicio_idperfil,
        'nodo' => $servicio_nodo,
        'sucursal' => $servicio_sucursal,
        'descripcion' => $servicio_descripcion,
        'costo' => $servicio_costo,
        'ipap' => $servicio_ipap,
        'mac' => $servicio_mac,
        'ip' => $servicio_ip,
        'instalado' => $servicio_instalado,
        'pppuser' => $servicio_pppuser,
        'ppppass' => $servicio_ppppass,
        'antena' => $servicio_antena,
        'emisor' => $servicio_emisor,
        'user_ubnt' => $servicio_user_ubnt,
        'pass_ubnt' => $servicio_pass_ubnt,
        'tipoipv4' => $servicio_tipoipv4,
        'redipv4' => $servicio_redipv4,
        'coordenadas' => $servicio_coordenadas,
        'direccion' => $servicio_direccion,
        'firewall_state' => $servicio_firewall_state,
        'snmp_comunidad' => $servicio_snmp_comunidad
      ];
      $id_new_servicio = $this->model_clientes->new_servicio($data_servicio);
      if ($id_new_servicio) {
        try {
          $client = new RouterOS\Client($server->ip, $server->usuario, $server->contrasena, $server->puerto);
        } catch (\Throwable $e) {
          $client = false;
          $data_ajax['mensaje'] = "Se inserto en la Base de datos, pero no se dio de alta en el Sistema de mikrotik correspondiente.";
        }
        if ($client) {
          try {
            $util = new RouterOS\Util($client);
            $util->setMenu('/queue simple');
            $data_queue = array(
              'name' => "Mkws_queue_$id_new_servicio",
              'target' => $servicio_ip,
              'max-limit' => $up_dow->velocidad,
              'comment' => $nombre_cliente[0]->nombre
            );
            $util->add($data_queue);
          } catch (RouterOS\RouterErrorException $e) {
            $error = true;
            if ($e->getCode() === RouterOS\RouterErrorException::CODE_GET_ERROR) {
              $e->getResponses()->getProperty('message');
            } else {
              echo $e->getResponses()->getProperty('message');
            }
          }
        }
        if ($error) {
          $data_ajax['mesaje'] = "No se puedo insertar los datos en el mikrotik";
          $data_ajax['estado'] = 2;
          return json_encode($data_ajax);
        } else {
          $txt_log = "Nuevo servicio Agregado (" . $nombre_cliente[0]->nombre . ") - Servicio ID: $id_new_servicio";
          $insert_log = $this->model_log->insert_log($servicio_idcliente, "servicios", $txt_log, $_SESSION['id_usuario8291']);
          $data_ajax['estado'] = 1;
          return json_encode($data_ajax);
        }
      } else {
        $data_ajax['estado'] = 2;
        $data_ajax['mesaje'] = "";
        return json_encode($data_ajax);
      }
    }
  }
  public function modal_editar_servicio()
  {
    if ($tipoacceso = $this->seguridad->Acceso('LISTA_CLIENTES', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $idservicio = $this->request->getGet('idservicio');
      $servicio = $this->model_clientes->get_servicio($idservicio);
      if ($servicio) {
        $permiso_sucursal_nodo = $this->permiso_sucursal->get_permiso_sucursal_nodo($_SESSION['sucursales']);
        $permiso_sucursal_select = $this->permiso_sucursal->get_permiso_sucursal_select($_SESSION['sucursales']);
        $data['nodos'] = $this->model_selectores->get_all_nodos_by_usuario($permiso_sucursal_nodo);
        $data['emisores'] = $this->model_selectores->get_all_antenas_amisoras();
        $data['sucursales'] = $this->model_selectores->get_all_sucursales_by_user($permiso_sucursal_select);
        $data['idcliente'] = $servicio[0]->idcliente;
        $data['redipv4'] = $servicio[0]->redipv4;
        $explode_ips = explode(",", $servicio[0]->ip);
        $explode = explode(".", $explode_ips[0], -1);
        $data['ip'] = $explode[0] . "." . $explode[1] . "." . $explode[2] . ".";
        $ipselect = "";
        foreach ($explode_ips as $ip) {
          $ipselect .= "<option value=\"$ip\" selected>$ip</option>";
        }
        $data['explode_ips'] = json_encode($explode_ips);
        $data['ipselect'] = $ipselect;
        $data['servicio'] = $servicio[0];
        $data['tipoacceso'] = $tipoacceso;
        return view('clientes/lista_clientes/taps/sub_taps/utils/modal_editar_servicio', $data);
      } else {
        return "<>alerta('error','Error al cargar el recurso solicitado')</>";
      }
    }
  }
  public function save_edit_servicio()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'ESCRITURA', $_SESSION['id_usuario8291'])) {


      $idcliente = $this->request->getPost('idcliente');
      $idservicio = $this->request->getPost('idservicio');
      $servicio_sucursal = $this->request->getPost('servicio[sucursal]');
      $servicio_nodo = $this->request->getPost('servicio[nodo]');
      $servicio_firewall_state = $this->request->getPost('servicio[firewall_state]');
      $servicio_idperfil = $this->request->getPost('servicio[idperfil]');
      $servicio_descripcion = $this->request->getPost('servicio[descripcion]');
      $servicio_costo = $this->request->getPost('servicio[costo]');
      $servicio_tipoipv4 = $this->request->getPost('servicio[tipoipv4]');
      $servicio_redipv4 = $this->request->getPost('servicio[redipv4]');
      $servicio_ips = $this->request->getPost('servicio[ip]');
      $servicio_ip = "";
      foreach ($servicio_ips as $key => $ip) {
        if ($key == 0) {
          $servicio_ip .= $ip;
        } else {
          $servicio_ip .= "," . $ip;
        }
      }
      $servicio_mac = $this->request->getPost('servicio[mac]');
      $servicio_pppuser = $this->request->getPost('servicio[pppuser]');
      $servicio_ppppass = $this->request->getPost('servicio[ppppass]');
      $servicio_direccion = $this->request->getPost('servicio[direccion]');
      $servicio_coordenadas = $this->request->getPost('servicio[coordenadas]');
      $servicio_instalado = $this->request->getPost('servicio[instalado]');
      $servicio_instalado = str_replace("/", "-", $servicio_instalado);
      $servicio_instalado = date('d-m-Y', strtotime($servicio_instalado));
      $servicio_instalado = date('Y-m-d', strtotime($servicio_instalado));
      $servicio_emisor = $this->request->getPost('servicio[emisor]');
      $servicio_ipap = $this->request->getPost('servicio[ipap]');
      $servicio_antena = $this->request->getPost('servicio[antena]');
      $servicio_snmp_comunidad = $this->request->getPost('servicio[snmp_comunidad]');
      $servicio_user_ubnt = $this->request->getPost('servicio[user_ubnt]');
      $servicio_pass_ubnt = $this->request->getPost('servicio[pass_ubnt]');
      $server = $this->model_clientes->get_server_servicio($idservicio);
      $new_server = $this->model_clientes->get_new_server($servicio_nodo);
      $up_dow = $this->model_clientes->get_velocidades($servicio_idperfil);
      $error = false;
      if (!$server and $up_dow) {
        $data_ajax['estado'] = 2;
        return json_encode($data_ajax);
      } else {
        $up_dow = $up_dow[0];
      }

      try {
        $client = new RouterOS\Client($server->ip, $server->usuario, $server->contrasena, $server->puerto);
      } catch (\Throwable $e) {
        $error = true;
        $data_ajax['mensaje'] = "Se inserto en la Base de datos, pero no se dio de alta en el Sistema de mikrotik correspondiente.";
      }
      if (!$error) {
        try {
          $util = new RouterOS\Util($client);
          $util->setMenu('/queue simple');
          $util->remove($util->find(RouterOS\Query::where('name', "Mkws_queue_$idservicio")));
        } catch (RouterOS\RouterErrorException $e) {
          $error = true;
          if ($e->getCode() === RouterOS\RouterErrorException::CODE_GET_ERROR) {
            $e->getResponses()->getProperty('message');
          } else {
            echo $e->getResponses()->getProperty('message');
          }
        }
        if ($error) {
          $data_ajax['mensaje'] = "";
          $data_ajax['estado'] = 2;
          return json_encode($data_ajax);
        } else {
          try {
            $pcq_subida = $up_dow->pcq_subida == "" ? "default-small" : $up_dow->pcq_subida;
            $pcq_descarga = $up_dow->pcq_descarga == "" ? "default-small" : $up_dow->pcq_descarga;
            $util = new RouterOS\Util($client = new RouterOS\Client($new_server->ip, $new_server->usuario, $new_server->contrasena, $new_server->puerto));
            $util->setMenu('/queue simple');
            $data_queue = array(
              'name' => "Mkws_queue_$idservicio",
              'target' => $servicio_ip,
              'max-limit' => $up_dow->velocidad,
              'comment' => $server->nombre,
              'priority' => $up_dow->prioridad_subida . "/" . $up_dow->prioridad_descarga,
              "queue" => $pcq_subida . "/" . $pcq_descarga,
              "limit-at" => $up_dow->limitat_subida . "k/" . $up_dow->limitat_descarga . "k",
              "burst-limit" => $up_dow->burst_limit_subida . "k/" . $up_dow->burst_limit_descarga . "k",
              "burst-threshold" => $up_dow->burst_threshold_subida . "k/" . $up_dow->burst_threshold_descarga . "k",
              "burst-time" => $up_dow->burst_time_subida . "s/" . $up_dow->burst_time_descarga . "s",
            );
            $util->add($data_queue);
          } catch (RouterOS\RouterErrorException $e) {
            $error_message = "";
            if ($e->getCode() === RouterOS\RouterErrorException::CODE_GET_ERROR) {
              $error_message = $e->getResponses()->getProperty('message');
            } else {
              $error_message = $e->getResponses()->getProperty('message');
            }
            $data_ajax['mensaje'] = "<p>Se inserto en la Base de datos, pero no se dio de alta en el Sistema de mikrotik correspondiente.</p><b>Error:</b><p>$error_message</p>";
          }
        }
      }
      $data_servicio = [
        'idperfil' => $servicio_idperfil,
        'nodo' => $servicio_nodo,
        'sucursal' => $servicio_sucursal,
        'descripcion' => $servicio_descripcion,
        'costo' => $servicio_costo,
        'ipap' => $servicio_ipap,
        'mac' => $servicio_mac,
        'ip' => $servicio_ip,
        'instalado' => $servicio_instalado,
        'pppuser' => $servicio_pppuser,
        'ppppass' => $servicio_ppppass,
        'antena' => $servicio_antena,
        'emisor' => $servicio_emisor,
        'user_ubnt' => $servicio_user_ubnt,
        'pass_ubnt' => $servicio_pass_ubnt,
        'tipoipv4' => $servicio_tipoipv4,
        'redipv4' => $servicio_redipv4,
        'coordenadas' => $servicio_coordenadas,
        'direccion' => $servicio_direccion,
        'firewall_state' => $servicio_firewall_state,
        'snmp_comunidad' => $servicio_snmp_comunidad
      ];
      $new_servicio = $this->model_clientes->update_servicio($idservicio, $data_servicio);
      if ($new_servicio) {
        $nombre_cliente = $this->finanzas->get_nombre_cliente($idcliente);
        $txt_log = "Servicio Editado (" . $nombre_cliente[0]->nombre . ") - Servicio ID: $idservicio";
        $insert_log = $this->model_log->insert_log($idcliente, "servicios", $txt_log, $_SESSION['id_usuario8291']);
        $data_ajax['estado'] = 1;
        return json_encode($data_ajax);
      } else {
        $data_ajax['mensaje'] = "";
        $data_ajax['estado'] = 2;
        return json_encode($data_ajax);
      }
    }
  }
  public function delete_servicio()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $idservicio = $this->request->getPost('idservicio');
      $idcliente = $this->request->getPost('idcliente');
      $server = $this->model_clientes->get_server_servicio($idservicio);
      $error = false;
      try {
        $client = new RouterOS\Client($server->ip, $server->usuario, $server->contrasena, $server->puerto);
      } catch (\Throwable $e) {
        $error = true;
      }
      // Sin conexión al MikroTik $client no existe: antes Util(null) daba error 500
      if (! $error) {
        try {
          $util = new RouterOS\Util($client);
          $util->setMenu('/queue simple');
          $util->remove($util->find(RouterOS\Query::where('name', "Mkws_queue_$idservicio")));
        } catch (RouterOS\RouterErrorException $e) {
          $error = true;
          if ($e->getCode() === RouterOS\RouterErrorException::CODE_GET_ERROR) {
            $e->getResponses()->getProperty('message');
          } else {
            echo $e->getResponses()->getProperty('message');
          }
        }
      }
      if ($error) {
        echo 2;
        return null;
      }
      $result_delete_servicio = $this->model_clientes->delete_servicio($idservicio);
      if ($result_delete_servicio) {
        $nombre_cliente = $this->finanzas->get_nombre_cliente($idcliente);
        $txt_log = "Servicio Eliminado (" . $nombre_cliente[0]->nombre . ") - Servicio ID: $idservicio";
        $insert_log = $this->model_log->insert_log($idcliente, "servicio", $txt_log, $_SESSION['id_usuario8291']);
        echo 1;
        return null; // antes seguía y respondía "12"
      }
      echo 2;
    }
  }
  public function modal_nuevo_servicio_void()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $perfiles_void = $this->model_selectores->get_perfiles_void();
      $idcliente = $this->request->getGet('idcliente');
      if ($perfiles_void) {
        $data['perfiles_void'] = $perfiles_void;
        $data['fecha'] = date('d/m/Y');
        $data['idcliente'] = $idcliente;
        return view('clientes/lista_clientes/taps/sub_taps/modal_nuevo_servicio_void', $data);
      } else {
        return "<>alerta('error','Error al cargar el recurso solicitado')</>";
      }
    }
  }
  public function newvoip()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $voip_cliente = $this->request->getPost('voip[cliente]');
      $voip_idperfil = $this->request->getPost('voip[idperfil]');
      $voip_costo = $this->request->getPost('voip[costo]');
      $voip_server = $this->request->getPost('voip[server]');
      $voip_sipuser = $this->request->getPost('voip[sipuser]');
      $voip_sipid = $this->request->getPost('voip[sipid]');
      $voip_contrasena = $this->request->getPost('voip[contrasena]');
      $voip_codigoverificacion = $this->request->getPost('voip[codigoverificacion]');
      $voip_numerotelefono = $this->request->getPost('voip[numerotelefono]');
      $voip_instalacion = $this->request->getPost('voip[instalacion]');
      $voip_instalacion = str_replace("/", "-", $voip_instalacion);
      $voip_descripcion = $this->request->getPost('voip[descripcion]');
      $voip_notas = $this->request->getPost('voip[notas]');
      $data_void = [
        'cliente' => $voip_cliente,
        'server' => $voip_server,
        'sipuser' => $voip_sipuser,
        'sipid' => $voip_sipid,
        'contrasena' => $voip_contrasena,
        'codigoverificacion' => $voip_codigoverificacion,
        'numerotelefono' => $voip_numerotelefono,
        'costo' => $voip_costo,
        'instalacion' => date('Y-m-d', strtotime($voip_instalacion)),
        'notas' => $voip_notas,
        'idperfil' => $voip_idperfil,
        'descripcion' => $voip_descripcion,
      ];
      $id_servicio_voip = $this->model_clientes->new_servicio_voip($data_void);
      if ($id_servicio_voip) {
        $nombre_cliente = $this->finanzas->get_nombre_cliente($voip_cliente);
        $txt_log = "Nuevo servicio Voip Agregado (" . $nombre_cliente[0]->nombre . ") - Servicio VOIP ID: $id_servicio_voip";
        $insert_log = $this->model_log->insert_log($voip_cliente, "servicios voip", $txt_log, $_SESSION['id_usuario8291']);
        echo 1;
      } else {
        echo 2;
      }
    }
  }
  public function modal_editar_servicio_voip()
  {
    $permiso = $this->seguridad->Acceso('LISTA_CLIENTES', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $idservicio = $this->request->getGet('idservicio');
      $idcliente = $this->request->getGet('idcliente');
      $servicio_void = $this->model_clientes->get_servicio_voip($idservicio);
      $perfiles_void = $this->model_selectores->get_perfiles_void();
      if ($servicio_void and $perfiles_void) {
        $data['perfiles_void'] = $perfiles_void;
        $servicio_void[0]->instalacion = date('d/m/Y', strtotime($servicio_void[0]->instalacion));
        $data['servicio'] = $servicio_void[0];
        $data['idcliente'] = $idcliente;
        $data['idservicio'] = $idservicio;
        $data['permiso'] = $permiso;
        return view('clientes/lista_clientes/taps/sub_taps/utils/modal_editar_servicio_voip', $data);
      } else {
        return "<>alerta('error','Error al cargar el recurso solicitado')</>";
      }
    }
  }
  public function save_editar_servicio_voip()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $idvoip = $this->request->getPost('idvoip');
      $idcliente = $this->request->getPost('cliente');
      $voip_idperfil = $this->request->getPost('voip[idperfil]');
      $voip_costo = $this->request->getPost('voip[costo]');
      $voip_server = $this->request->getPost('voip[server]');
      $voip_sipuser = $this->request->getPost('voip[sipuser]');
      $voip_sipid = $this->request->getPost('voip[sipid]');
      $voip_contrasena = $this->request->getPost('voip[contrasena]');
      $voip_codigoverificacion = $this->request->getPost('voip[codigoverificacion]');
      $voip_numerotelefono = $this->request->getPost('voip[numerotelefono]');
      $voip_instalacion = $this->request->getPost('voip[instalacion]');
      $voip_instalacion = str_replace("/", "-", $voip_instalacion);
      $voip_descripcion = $this->request->getPost('voip[descripcion]');
      $voip_notas = $this->request->getPost('voip[notas]');
      $data_voip = [
        'server' => $voip_server,
        'sipuser' => $voip_sipuser,
        'sipid' => $voip_sipid,
        'contrasena' => $voip_contrasena,
        'codigoverificacion' => $voip_codigoverificacion,
        'numerotelefono' => $voip_numerotelefono,
        'costo' => $voip_costo,
        'instalacion' => date('Y-m-d', strtotime($voip_instalacion)),
        'notas' => $voip_notas,
        'idperfil' => $voip_idperfil,
        'descripcion' => $voip_descripcion,
      ];
      $result_update_voip = $this->model_clientes->update_servicio_voip($idvoip, $data_voip);
      if ($result_update_voip) {
        $nombre_cliente = $this->finanzas->get_nombre_cliente($idcliente);
        $txt_log = "Servicio Voip editado (" . $nombre_cliente[0]->nombre . ") - Servicio VOIP ID: $idvoip";
        $insert_log = $this->model_log->insert_log($idcliente, "servicios_voip", $txt_log, $_SESSION['id_usuario8291']);
        echo 1;
      } else {
        echo 2;
      }
    }
  }
  public function delete_servicio_voip()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $idservicio = $this->request->getPost('idservicio');
      $idcliente = $this->request->getPost('idcliente');
      $result_delete_servicio_voip = $this->model_clientes->delete_ervicio_voip($idservicio);
      if ($result_delete_servicio_voip) {
        $nombre_cliente = $this->finanzas->get_nombre_cliente($idcliente);
        $txt_log = "Servicio Voip Eliminado (" . $nombre_cliente[0]->nombre . ") - Servicio VOIP ID: $idservicio";
        $insert_log = $this->model_log->insert_log($idcliente, "servicios_voip", $txt_log, $_SESSION['id_usuario8291']);
        echo 1;
      } else {
        echo 2;
      }
    }
  }
  public function modal_new_servicio_recurrente()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $idcliente = $this->request->getGet('idcliente');
      $productos = $this->model_selectores->get_producto_popup();
      $servicios = $this->model_selectores->get_producto_otro_popup();
      if ($productos and $servicios) {
        $data['idcliente'] = $idcliente;
        $data['productos'] = $productos;
        $data['otros_productos'] = $servicios;
        return view('clientes/lista_clientes/taps/sub_taps/utils/modal_new_servicio_recurrente', $data);
      } else {
        return "Error al cargar los productos";
      }
    }
  }
  public function productos_de_almacen()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $idproducto = $this->request->getPost('idproducto');
      $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal_almacen($_SESSION['sucursales']);
      $all_items = $this->finanzas->get_serialproductos($idproducto, $permiso_sucursal);
      echo $all_items ? json_encode($all_items) : "error";
    }
  }
  public function new_servicio_recurrente()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $productoid = $this->request->getPost('producto-otros');
      $userid = $this->request->getPost('alquileres[userid]');
      $tipo = "otro";
      if (!$productoid) {
        $tipo = "producto";
        $productoid = $this->request->getPost('alquileres[productoid]');
        $data_almacen = [
          "userid" => $userid,
          "fecha_salida" => date('Y-m-d'),
          "estado" => "no disponible"
        ];
        $result_update_producto = $this->almacen->update_producto($productoid, $data_almacen);
        if (!$result_update_producto) {
          echo 2;
          return null;
        }
      }
      $totalcuota = $this->request->getPost('alquileres[totalcuota]');
      $monto = $this->request->getPost('alquileres[monto]');
      $nombre_cliente = $this->finanzas->get_nombre_cliente($userid);
      $fecha = date('Y-m-d');
      if ($totalcuota == 0) {
        //insert tmpfacturas
        $data_tmpfactura = [
          'userid' => $userid,
          'fechaingreso' => $fecha,
          'productoid' => $productoid,
          'tipoa' => $tipo,
          'monto' => $monto,
          'nfactura' => 0,
          'idinstall' => 0,
        ];
        $id_result_insert_producto = $this->model_clientes->insert_new_tmpfactura($data_tmpfactura);
        $txt_log = "Nuevo producto de un solo pago Agregado (" . $nombre_cliente[0]->nombre . ") - ID:$id_result_insert_producto";
      } else {
        //insert alquileres con totalcuota=0
        $data_alquiler = [
          'userid' => $userid,
          'fechaingreso' => $fecha,
          'fechaproxima' => "0000-00-00",
          'productoid' => $productoid,
          'ciclo' => 0,
          'tipoa' => $tipo,
          'totalcuota' => $totalcuota == 1 ? 0 : $totalcuota,
          'ncuota' => 0,
          'monto' => $monto,
          'idinstall' => 0,
        ];
        $id_result_insert_producto = $this->model_clientes->insert_new_alquiler($data_alquiler);
        if ($totalcuota = 1) {
          $txt_log = "Nuevo producto de cobro mensual Agregado (" . $nombre_cliente[0]->nombre . ") - ID:$id_result_insert_producto";
        } else {
          $txt_log = "Nuevo producto en cuotas ($totalcuota x $monto) Agregado (" . $nombre_cliente[0]->nombre . ") - ID:$id_result_insert_producto";
        }
      }
      if ($id_result_insert_producto) {
        $insert_log = $this->model_log->insert_log($userid, "productos y servicios", $txt_log, $_SESSION['id_usuario8291']);
        echo 1;
      } else {
        echo 2;
      }
    }
  }
  public function delete_servicio_recurrente()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $idservicio = $this->request->getPost('idservicio');
      $idcliente = $this->request->getPost('idcliente');
      $servicio = $this->model_clientes->get_servicio_recurrente($idservicio);
      if ($servicio) {
        $result_delete_servicio_recurrente = $this->model_clientes->delete_servicio_recurrente($idservicio);
        if ($result_delete_servicio_recurrente) {
          $data_almacen = [
            "userid" => 0,
            "fecha_salida" => "0000-00-00",
            "estado" => "disponible"
          ];
          $this->almacen->update_producto($servicio[0]->productoid, $data_almacen);
          $nombre_cliente = $this->finanzas->get_nombre_cliente($idcliente);
          $txt_log = "Producto recurrente eliminado (" . $servicio[0]->nombre . ") - (" . $nombre_cliente[0]->nombre . ")";
          $insert_log = $this->model_log->insert_log($idcliente, "productos y servicios", $txt_log, $_SESSION['id_usuario8291']);
          echo 1;
        } else {
          echo 2;
        }
      } else {
        echo 2;
      }
    }
  }
  public function get_productos_y_servicios_un_solo_pago()
  {
    $permiso = $this->seguridad->Acceso('LISTA_CLIENTES', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $idcliente = $this->request->getGet('idcliente');
      $servicios = $this->model_clientes->get_servicios_un_pago($idcliente);
      if ($servicios) {
        foreach ($servicios as $servicio) {
          $idservicio = $servicio->id;
          $servicio->monto = "MX$" . $servicio->monto;
          $servicio->estado == "pendiente" ? $servicio->estado = "<span class=\"label label-warning\">POR FACTURAR</span>" : $servicio->estado = "<span class=\"label label-success\">FACTURADO</span>";
          $servicio->factura == 0 ? $servicio->factura = "" : null;
          $servicio->fecha = date('d/m/Y', strtotime($servicio->fecha));
          if ($permiso >= 2) {
            $servicio->tools = "
            <a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"remove_unpago('$idservicio')\" data-original-title=\"Eliminar\">
              <i class=\"far fa-trash-alt\" aria-hidden=\"true\"></i>
            </a>
            ";
          } else {
            $servicio->tools = "";
          }
        }
        $data['data'] = $servicios;
      } else {
        $data['data'] = array();
      }
      return json_encode($data);
    }
  }
  public function delete_servicio_un_pago()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $idcliente = $this->request->getPost('idcliente');
      $idservicio = $this->request->getPost('idservicio');
      $servicio = $this->model_clientes->get_servicio_un_solo_pago($idservicio);
      if ($servicio) {
        $result_delete_servicio_recurrente = $this->model_clientes->delete_servicio_un_solo_pago($idservicio);
        if ($result_delete_servicio_recurrente) {
          $data_almacen = [
            "userid" => 0,
            "fecha_salida" => "0000-00-00",
            "estado" => "disponible"
          ];
          $this->almacen->update_producto($servicio[0]->productoid, $data_almacen);
          $nombre_cliente = $this->finanzas->get_nombre_cliente($idcliente);
          $txt_log = "Producto eliminado (" . $servicio[0]->nombre . ") - (" . $nombre_cliente[0]->nombre . ")";
          $insert_log = $this->model_log->insert_log($idcliente, "productos y servicios", $txt_log, $_SESSION['id_usuario8291']);
          echo 1;
        } else {
          echo 2;
        }
      } else {
        echo 2;
      }
    }
  }

  public function get_nodos_por_sucursal()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $sucursal = $this->request->getPost('sucursal');
      $nodos = $this->model_selectores->get_nodos_por_sucursal($sucursal);
      if (!$nodos) {
        $nodos = " <option></option>";
      }
      echo $nodos;
    }
  }

  public function delete_cliente()
  {
    if ($this->seguridad->Acceso_full('LISTA_CLIENTES', 'ELIMINAR', $_SESSION['id_usuario8291'])) {
      $idcliente = $this->request->getPost('idcliente');
      $nombre_cliente = $this->finanzas->get_nombre_cliente($idcliente);
      //------------------API---------------
   
      //---------------------------------
      $all = $this->request->getPost('all');
      if ($all != "false") {
        $data_almacen = ["userid" => 000000, "estado" => "disponible"];
        $this->model_clientes->delete_tmpfactura_cliente($idcliente);
        $this->model_clientes->update_almacen($idcliente, $data_almacen);
        $delete = $this->model_clientes->delete_join_facturas_items($idcliente);
        $this->model_clientes->delete_facturas($idcliente);
        $this->model_clientes->delete_saldos($idcliente);
        $this->model_clientes->delete_instalaciones($idcliente);
        $this->model_clientes->delete_servicios_voip($idcliente);
        $this->model_clientes->delete_servicios($idcliente);
        $this->model_clientes->delete_avisouser($idcliente);
        $this->model_clientes->delete_alquiler($idcliente);
        $this->model_clientes->delete_operaciones($idcliente);
        ## Delete materiales.
      }
      $delete_usuario = $this->model_clientes->delete_usuario($idcliente);
      if ($delete_usuario) {
        $data_ajax['estado'] = 1;
        return json_encode($data_ajax);
        $txt_log = "Cliente eliminado (" . $nombre_cliente[0]->nombre . ") ID : $idcliente";
        $insert_log = $this->model_log->insert_log($idcliente, "clientes", $txt_log, $_SESSION['id_usuario8291']);
      } else {
        $data_ajax['estado'] = 2;
        $data_ajax['mesaje'] = "";
        return json_encode($data_ajax);
      }
      return null;
    }
  }
  public function suspender_cliente()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $idcliente = $this->request->getPost('idcliente');
      $server = $this->model_clientes->get_servicios_server($idcliente);
      if (!$server) {
        $data_ajax['estado'] = 2;
        $data_ajax['mesaje'] = "";
        return json_encode($data_ajax);
      }
      foreach ($server as $serve) {
        $client = true;
        $idservicio = $serve->idservicio;
        try {
          $client = new RouterOS\Client($serve->ip, $serve->usuario, $serve->contrasena, $serve->puerto);
        } catch (\Throwable $th) {
          $client = false;
          $data_ajax['mensaje'] = "Se suspendieron los servicios en la Base de datos, pero no se suspendieron en algunos Sistemas de mikrotik.";
        }
        if ($client) {
          $util = new RouterOS\Util($client);
          $util->setMenu('/ip firewall address-list');
          $data = array(
            'list' => "morosos",
            'address' => $serve->ipcliente,
            'comment' => "Corte_Servicio_$idservicio"
          );
          $util->add($data);
        }
      }

      $data_cliente = [
        "estado" => "SUSPENDIDO"
      ];

      $result_suspender = $this->model_clientes->update_cliente($idcliente, $data_cliente);
      if ($result_suspender) {
        $nombre_cliente = $this->finanzas->get_nombre_cliente($idcliente);
        $txt_log = "Servicios Suspendidos (" . $nombre_cliente[0]->nombre . ") - Cliente ID: $idcliente";
        $insert_log = $this->model_log->insert_log($idcliente, "clientes", $txt_log, $_SESSION['id_usuario8291']);
        $data_ajax['estado'] = 1;
        return json_encode($data_ajax);
      } else {
        $data_ajax['estado'] = 2;
        $data_ajax['mesaje'] = "";
        return json_encode($data_ajax);
      }
    }
  }
  public function activar_cliente()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $idcliente = $this->request->getPost('idcliente');
      $server = $this->model_clientes->get_servicios_server($idcliente);
      if (!$server) {
        $data_ajax['estado'] = 2;
        $data_ajax['mesaje'] = "";
        return json_encode($data_ajax);
      }
      foreach ($server as $serve) {
        $client = true;
        $idservicio = $serve->idservicio;
        try {
          $client = new RouterOS\Client($serve->ip, $serve->usuario, $serve->contrasena, $serve->puerto);
        } catch (\Throwable $th) {
          $client = false;
          $data_ajax['mensaje'] = "Se activo el servicios en la Base de datos, pero no se activo en el Sistemas de mikrotik.";
        }
        if ($client) {
          $idservicio = $serve->idservicio;
          $util = new RouterOS\Util($client);
          $util->setMenu('/ip firewall address-list');
          $util->remove(RouterOS\Query::where('comment', "Corte_Servicio_$idservicio"));
        }
      }

      $data_cliente = [
        "estado" => 'ACTIVO'
      ];
      $result_activar = $this->model_clientes->update_cliente($idcliente, $data_cliente);
      if ($result_activar) {
        $nombre_cliente = $this->finanzas->get_nombre_cliente($idcliente);
        $txt_log = "Cliente activado (" . $nombre_cliente[0]->nombre . ") - Cliente ID: $idcliente";
        $insert_log = $this->model_log->insert_log($idcliente, "clientes", $txt_log, $_SESSION['id_usuario8291']);
        $data_ajax['estado'] = 1;
        return json_encode($data_ajax);
      } else {
        $data_ajax['estado'] = 2;
        $data_ajax['mesaje'] = "";
        return json_encode($data_ajax);
      }
    }
  }
  public function retirar_cliente()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $idcliente = $this->request->getPost('idcliente');
      $all = $this->request->getPost('all');
      $server = $this->model_clientes->get_servicios_server($idcliente);
      if (!$server) {
        $data_ajax['estado'] = 2;
        $data_ajax['mesaje'] = "";
        return json_encode($data_ajax);
      }
      foreach ($server as $serve) {
        $client = true;
        $idservicio = $serve->idservicio;
        try {
          $client = new RouterOS\Client($serve->ip, $serve->usuario, $serve->contrasena, $serve->puerto);
        } catch (\Throwable $th) {
          $client = false;
          $data_ajax['mensaje'] = "Se elimino en la Base de datos, pero no se dio de alta en todos los Sistemas de mikrotik correspondiente.";
        }
        if ($client) {
          $util = new RouterOS\Util($client);
          $util->setMenu('/queue simple');
          $util->remove(RouterOS\Query::where('name', "Mkws_queue_$idservicio"));
        }
      }
      //---------------------------------
      $result_servicio = $this->model_clientes->get_servicio_cliente($idcliente);
      $otros_impuestos = "";
      if ($all != "false") {
        $delete_instalacion = $this->model_clientes->delete_instalaciones($idcliente);
      }
      $data_retirado = $this->decodificar->codificar($result_servicio);
      $data_avisouser = [
        "data_retirado" => $data_retirado,
        "fecha_retirado" => date('Y-m-d')
      ];
      $result_update_aviso_user = $this->model_clientes->update_tblavisouser($idcliente, $data_avisouser);
      $data_almacen = ["estado" => "disponible"];
      $delete_comodato = $this->model_clientes->update_almacen($idcliente, $data_almacen);
      $data_cliente = ["estado" => "RETIRADO"];
      $retirar_cliente = $this->model_clientes->update_cliente($idcliente, $data_cliente);
      $this->model_clientes->delete_servicios($idcliente);
      if ($retirar_cliente) {
        $data_ajax['estado'] = 1;
        return json_encode($data_ajax);
        $nombre_cliente = $this->finanzas->get_nombre_cliente($idcliente);
        $txt_log = "Cliente retirado (" . $nombre_cliente[0]->nombre . ") ID : $idcliente";
        $insert_log = $this->model_log->insert_log($idcliente, "clientes", $txt_log, $_SESSION['id_usuario8291']);
      } else {
        $data_ajax['estado'] = 2;
        $data_ajax['mesaje'] = "";
        return json_encode($data_ajax);
      }
    }
  }
  //End funciones Nav servicios

  //Instalaciones funciones
  public function delete_instalacion()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('INSTALACIONES'));
      if ($tipoacceso >= 3) {
        $id = $this->request->getPost('id');
        $delete = $this->model_clientes->delete_instalacion($id);
        if ($delete) {
          $insert_log = $this->model_log->insert_log("000000", "Instalaciones", "Pre instalacion eliminada <b>#" . $id . " de la tabla instalaciones por " . $_SESSION['nombre'] . " </b>", $_SESSION['id_usuario8291']);
          echo 1;
        } else {
          echo 2;
        }
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }

  public function new_pre_install()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('INSTALACIONES'));
      if ($tipoacceso >= 1) {

        $cedula = $this->request->getPost('cedula');
        $sucursal = $this->request->getPost('install[sucursal]');
        $cliente = $this->request->getPost('cliente');
        $direccion = $this->request->getPost('direccion');
        $telefono = $this->request->getPost('telefono');
        $movil = $this->request->getPost('movil');
        $email = $this->request->getPost('email');
        $fecha_instalacion = $this->request->getPost('fechavisita');
        $zona = $this->request->getPost('zona');
        $id_tecnico = $this->request->getPost('tecnico');
        $notas = $this->request->getPost('notas');

        date_default_timezone_set('America/Mexico_City');
        $fecha_visita = str_replace('/', '-', $fecha_instalacion);
        $fech = date('Y-m-d H:i:s', strtotime($fecha_visita));

        $array_pre = [
          "cedula" => $cedula, "cliente" => $cliente, "fecha_ingreso" => date('Y-m-d'), "idtecnico" => $id_tecnico, "estate" => "PENDIENTE", "direccion" => $direccion, "telefono" => $telefono, "movil" => $movil, "sucursal" => $sucursal, "email" => $email, "fecha_instalacion" => $fech, "zona" => $zona, "notas" => $notas, "idvendedor" => $_SESSION['id_usuario8291']
        ];
        $insert_pre = $this->model_clientes->new_pre_install($array_pre);
        if ($insert_pre) {
          $r = $this->model_clientes->last_id();
          $lastid = $r->last;
          $insert_log = $this->model_log->insert_log("000000", "Instalaciones", "Pre instalacion registrada <b>#" . $lastid . " por " . $_SESSION['nombre'] . " </b>", $_SESSION['id_usuario8291']);
          echo 1;
        } else {
          echo 2;
        }
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }
  public function update_pre_install()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('INSTALACIONES'));
      if ($tipoacceso >= 1) {

        $id = $this->request->getPost('id');
        $insert_pre = $this->model_clientes->update_pre_install($id, 'NO INSTALADO');

        if ($insert_pre) {
          $insert_log = $this->model_log->insert_log("000000", "Instalaciones", "Pre instalacion aceptada <b>#" . $id . " por " . $_SESSION['nombre'] . " </b>", $_SESSION['id_usuario8291']);
          echo 1;
        } else {
          echo 2;
        }
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }

  public function edit_instalacion()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('INSTALACIONES'));
      if ($tipoacceso >= 1) {
        $arrayName = array();
        $id = $this->request->getPost('id');
        $row = $this->model_clientes->get_pre_by_id($id);
        if ($row) {
          $arrayName = array(
            'id' => $row->id, 'cedula' => $row->cedula, 'cliente' => $row->cliente, 'direccion' => $row->direccion, 'telefono' => $row->telefono, 'movil' => $row->movil,
            'email' => $row->email, 'Netflix_Activo' => $row->Netflix_Activo, 'Netflix_Datos' => $row->Netflix_Datos, 'Camaras' => $row->Camaras, 'TV' => $row->TV, '_Cableado' => $row->_Cableado,
            'Wisp' => $row->Wisp, 'Factibilidad' => $row->Factibilidad, 'Referencia1' => $row->Referencia1, 'Referencia2' => $row->Referencia2, 'Ejecutivo' => $row->Ejecutivo, 'Actualizado' => $row->Actualizado,
            'fecha_instalacion' => $row->fecha_instalacion, 'zona' => $row->zona, 'notas' => $row->notas
          );
        }
        echo json_encode($arrayName);
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }

  public function add_materials()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('INSTALACIONES'));
      if ($tipoacceso >= 1) {
        $id = $this->request->getGet('id');
        $materials = $this->model_clientes->get_instal_by_id($id);
        if ($materials) {
          $data['materials'] = $materials[0];
        }
        echo view('clientes/instalaciones/view_materials', $data);
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }

  public function get_productos_materials()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('INSTALACIONES'));
      if ($tipoacceso >= 1) {
        $id = $this->request->getGet('id'); //id install
        $materials_add = $this->model_clientes->get_materials_add($id);
        $tool = "";
        $condicion = "";
        $array_all = array();
        if ($materials_add) {
          foreach ($materials_add as $items) {

            if ($items->condicion == 0) {
              $condicion = "Libre";
            } else if ($items->condicion == 1) {
              $condicion = "Préstamo/Comodato";
            } else if ($items->condicion == 2) {
              $condicion = "Venta (facturar)";
            }

            if ($tipoacceso == 3) {
              $tool = '<td class=" text-center">
              <a onclick="remove_p_install(\'' . $items->id . '\',\'' . $items->idalmacen . '\')" data-toggle="tooltip" title="Eliminar Instalacion" class="btn btn-default btn-icon btn-sm"><i class="far fa-trash-alt"></i></a></td>';
            }

            $array_items = array(
              "tipo" => $items->tipo,
              "producto" => $items->producto,
              "mac_producto" => $items->mac_producto,
              "serial_producto" => $items->serial_producto,
              "condicion" => $condicion,
              "tool" => $tool
            );
            array_push($array_all, $array_items);
          }
        }
        echo '{"data":' . json_encode($array_all) . '}';
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }

  public function get_accesorios_add()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('INSTALACIONES'));
      if ($tipoacceso >= 1) {
        $id = $this->request->getGet('id'); //id install
        $accesorios_add = $this->model_clientes->get_accesorios_add($id);
        $tool = "";
        $array_all = array();
        if ($accesorios_add) {
          foreach ($accesorios_add as $items) {

            if ($tipoacceso == 3) {
              $tool = '<td class=" text-center">
              <a onclick="remove_a_install(\'' . $items->id . '\',\'' . $items->idalmacen . '\')" data-toggle="tooltip" title="Eliminar Instalacion" class="btn btn-default btn-icon btn-sm"><i class="far fa-trash-alt"></i></a></td>';
            }

            $array_items = array(
              "descripcion" => $items->detalle,
              "cantidad" => $items->cantidad,
              "tool" => $tool
            );
            array_push($array_all, $array_items);
          }
        }
        echo '{"data":' . json_encode($array_all) . '}';
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }

  public function instalaciones_p()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('INSTALACIONES'));
      if ($tipoacceso >= 1) {

        $id = $this->request->getGet('id');
        $instal = $this->model_clientes->get_instal_by_id($id);
        if ($instal) {
          $data['instal'] = $instal[0];
        }
        $data['select_p'] = $this->model_selectores->get_all_categorias_productos_tools();
        $data['tipoacceso'] = $tipoacceso;
        echo view('clientes/instalaciones/modal_p', $data);
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }

  //funcion llenar select de productos por id
  public function get_data_select()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('INSTALACIONES'));
      if ($tipoacceso >= 1) {
        $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal_almacen($_SESSION['sucursales']);
        $id = $this->request->getPost('id');
        $option_a = $this->model_selectores->get_productos_permiso_suscursal($id, $permiso_sucursal);
        echo $option_a;
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }

  public function new_p_add()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('INSTALACIONES'));
      if ($tipoacceso >= 1) {
        $idinstall = $this->request->getPost('idinstall');
        $productoid = $this->request->getPost('productoid');
        $idalmacen = $this->request->getPost('alquileres[productoid]');
        $tipo = $this->request->getPost('tipo_producto');
        $condicion = $this->request->getPost('condicion');
        $totalcuota = $this->request->getPost('alquileres[totalcuota]');
        $monto = $this->request->getPost('alquileres[monto]');

        $sql_almacen = $this->model_clientes->get_p_a_by_id($idalmacen);
        $sql_producto = $this->model_clientes->get_p_by_id($productoid);
        if ($sql_almacen and $sql_producto) {
          if ($sql_producto) {
            foreach ($sql_almacen as $items) {
              foreach ($sql_producto as $items_p) {
                $array_mat = [
                  'idalmacen' => $idalmacen,
                  'idinstall' => $idinstall,
                  'cantidad' => $items->cantidad,
                  'detalle' => $items_p->producto,
                  'tipo' => $tipo,
                  'serial' => $items->serial_producto,
                  'mac' => $items->mac_producto,
                  'tipopro' => $items_p->tipo,
                  'condicion' => $condicion
                ];
              } //fin foreach producto
            } //fin foreach almacen
          } //if producto fin
        } //if almacen fin

        if ($condicion == 2 && $totalcuota == 0) {
          $inser_materiales = $this->model_clientes->insert_materiales($array_mat);
          if ($inser_materiales) {
            $array_alm = [
              "fecha_salida" => date('Y-m-d'), "estado" => 'no disponible'
            ];
            $update_almacen = $this->model_clientes->update_estado_almacen($idalmacen, $array_alm);
            if ($update_almacen) {
              $insert_log = $this->model_log->insert_log("000000", "Instalaciones", "Producto agregado en la instalacion <b>#" . $idinstall . " por " . $_SESSION['nombre'] . " </b>", $_SESSION['id_usuario8291']);
            }
            $array_tmpfactura = [
              "userid" => 0, "fechaingreso" => date('Y-m-d'),
              "productoid" => $idalmacen, "tipoa" => 'producto', "monto" => $monto, "nfactura" => 0, "idinstall" => $idinstall
            ];
            $insert_tmpfactura = $this->model_clientes->insert_tmpfactura($array_tmpfactura);
            if ($insert_tmpfactura) {
              $insert_log = $this->model_log->insert_log("000000", "Instalaciones", "Producto agregado en la tabla tmpfactura de la instalacion <b>#" . $idinstall . " por " . $_SESSION['nombre'] . " </b>", $_SESSION['id_usuario8291']);
              echo 1;
            }
          }
        } else if ($condicion == 2 && $totalcuota >= 1) {
          $inser_materiales = $this->model_clientes->insert_materiales($array_mat);
          if ($inser_materiales) {
            $array_alm = [
              "fecha_salida" => date('Y-m-d'), "estado" => 'no disponible'
            ];
            $update_almacen = $this->model_clientes->update_estado_almacen($idalmacen, $array_alm);
            if ($update_almacen) {
              $insert_log = $this->model_log->insert_log("000000", "Instalaciones", "Producto agregado en la instalacion <b>#" . $idinstall . " por " . $_SESSION['nombre'] . " </b>", $_SESSION['id_usuario8291']);
            }
            $array_alquileres = [
              "userid" => 0, "fechaingreso" => date('Y-m-d'), "fechaproxima" => 0,
              "productoid" => $idalmacen, "ciclo" => 0, "tipoa" => 'producto',
              "totalcuota" => $totalcuota == 1 ? 0 : $totalcuota, "ncuota" => 0, "monto" => $monto, "idinstall" => $idinstall
            ];
            $insert_alquileres = $this->model_clientes->insert_alquileres($array_alquileres);
            if ($insert_alquileres) {
              $insert_log = $this->model_log->insert_log("000000", "Instalaciones", "Producto agregado en la tabla alquileres de la instalacion <b>#" . $idinstall . " por " . $_SESSION['nombre'] . " </b>", $_SESSION['id_usuario8291']);
              echo 1;
            }
          }
        } else {
          $inser_materiales = $this->model_clientes->insert_materiales($array_mat);
          if ($inser_materiales) {
            $array_alm = [
              "fecha_salida" => date('Y-m-d'), "estado" => 'no disponible'
            ];
            $update_almacen = $this->model_clientes->update_estado_almacen($idalmacen, $array_alm);
            if ($update_almacen) {
              $insert_log = $this->model_log->insert_log("000000", "Instalaciones", "Producto agregado en la instalacion <b>#" . $idinstall . " por " . $_SESSION['nombre'] . " </b>", $_SESSION['id_usuario8291']);
              echo 1;
            }
          }
          echo 2;
        }
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }

  public function remove_p_install()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('INSTALACIONES'));
      if ($tipoacceso >= 1) {
        $id = $this->request->getPost('id'); //id material
        $idalmacen = $this->request->getPost('ida'); //id  almacen
        //$idinstall = $this->request->getPost('idi'); //id instalacion

        /* $delete_materials = $this->model_clientes->delete_materials($id);
        $delete_tmpfactura = $this->model_clientes->delete_tmpfactura($idalmacen);
        $delete_alquileres = $this->model_clientes->delete_alquileres($idalmacen);
        $update_almacen_idalmacen = $this->model_clientes->update_estado_almacen($idalmacen, 'disponible'); */

        $sql = $this->model_clientes->get_material_by_id($id);
        if ($sql) {
          foreach ($sql as $items) {
            if ($items->condicion <= 1) {
              $delete_materials = $this->model_clientes->delete_materials($id);
              if ($delete_materials) {
                $update_almacen_idalmacen = $this->model_clientes->update_almacen_simple($idalmacen, 'disponible');
                $insert_log = $this->model_log->insert_log("000000", "Instalaciones", "Producto eliminado de la tabla materiales <b>#" . $id . " por " . $_SESSION['nombre'] . " </b>", $_SESSION['id_usuario8291']);
                echo 1;
              }
            } // fin if conducion
            $sqltmpf = $this->model_clientes->get_tmpfactura_by_idalmacen($idalmacen);
            if ($sqltmpf && $sql) {
              foreach ($sqltmpf as $itemsf) {
                if ($items->condicion == 2 && $itemsf->productoid == $idalmacen) {
                  #eliminar ambas xD
                  $delete_materials = $this->model_clientes->delete_materials($id);
                  $delete_tmpfactura = $this->model_clientes->delete_tmpfactura($idalmacen);
                  if ($delete_materials && $delete_tmpfactura) {
                    $update_almacen_idalmacen = $this->model_clientes->update_almacen_simple($idalmacen, 'disponible');
                    $insert_log = $this->model_log->insert_log("000000", "Instalaciones", "Producto eliminado de la tabla materiales <b>#" . $id . " por " . $_SESSION['nombre'] . " </b>", $_SESSION['id_usuario8291']);
                    $insert_log = $this->model_log->insert_log("000000", "Instalaciones", "Producto eliminado de la tabla tmpfacturas con id:<b>#" . $idalmacen . " por " . $_SESSION['nombre'] . " </b>", $_SESSION['id_usuario8291']);
                    echo 1;
                  }
                } //fin if condicion =2 y tmpfactura diferente a vacio idinstall
              } //fin foreach tmpfactura 
            } //fin recorrer material y tmpfactura
            $sqlalquiler = $this->model_clientes->get_alquiler_by_idalmacen($idalmacen);
            if ($sqlalquiler && $sql) {
              foreach ($sqlalquiler as $itemsA) {
                if ($items->condicion == 2 && $itemsA->productoid == $idalmacen) {
                  $delete_materials = $this->model_clientes->delete_materials($id);
                  $delete_alquileres = $this->model_clientes->delete_alquileres($idalmacen);
                  if ($delete_materials && $delete_alquileres) {
                    $update_almacen_idalmacen = $this->model_clientes->update_almacen_simple($idalmacen, 'disponible');
                    $insert_log = $this->model_log->insert_log("000000", "Instalaciones", "Producto eliminado de la tabla materiales <b>#" . $id . " por " . $_SESSION['nombre'] . " </b>", $_SESSION['id_usuario8291']);
                    $insert_log = $this->model_log->insert_log("000000", "Instalaciones", "Producto eliminado de la tabla alquileres con id:<b>#" . $idalmacen . " por " . $_SESSION['nombre'] . " </b>", $_SESSION['id_usuario8291']);
                    echo 1;
                  }
                }
              }
            }
          } //foreach fin materiales
        } else {
          echo 2;
        }
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }

  public function instalaciones_a()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('INSTALACIONES'));
      if ($tipoacceso >= 1) {

        $id = $this->request->getGet('id');
        $instal = $this->model_clientes->get_instal_by_id($id);
        if ($instal) {
          $data['instal'] = $instal[0];
        }
        $data['select_a'] = $this->model_selectores->get_all_categorias_productos_accesorios_tools();
        $data['tipoacceso'] = $tipoacceso;
        echo view('clientes/instalaciones/modal_a', $data);
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }

  //funcion llenar select de accesorios por id
  public function get_data_select_a()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('INSTALACIONES'));
      if ($tipoacceso >= 1) {
        $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal_almacen($_SESSION['sucursales']);
        $id = $this->request->getPost('id');
        $option_a = $this->model_selectores->get_accesorios_permiso_suscursal($id, $permiso_sucursal);
        echo $option_a;
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }

  public function new_acc()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('INSTALACIONES'));
      if ($tipoacceso >= 1) {
        $idinstall = $this->request->getPost('idinstall');
        $productoid = $this->request->getPost('productoid');
        $almacenid = $this->request->getPost('accesorio');
        $descripcion = $this->request->getPost('descripcion');
        $cantidad = $this->request->getPost('cantidad');

        $array_mat = [
          "idalmacen" => $almacenid, "idinstall" => $idinstall, "cantidad" => $cantidad, "detalle" => $descripcion,
          "tipopro" => "accesorio", "condicion" => 0
        ];

        $insert_mat = $this->model_clientes->insert_materiales($array_mat);
        if ($insert_mat) {
          $fecha = date('Y-m-d');
          $update_almacen = $this->model_clientes->update_stock_almacen($almacenid, $fecha, $cantidad);
          if ($update_almacen) {
            $insert_log = $this->model_log->insert_log("000000", "Instalaciones", "Accesorio agregado en la instalacion <b>#" . $idinstall . " por " . $_SESSION['nombre'] . " </b>", $_SESSION['id_usuario8291']);
            echo 1;
          }
        } else {
          echo 2;
        }
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }

  public function remove_a_install()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('INSTALACIONES'));
      if ($tipoacceso >= 1) {
        $id = $this->request->getPost('id'); //id material
        $idalmacen = $this->request->getPost('ida'); //id  almacen
        $sql = $this->model_clientes->get_material_by_id($id);
        if ($sql) {
          foreach ($sql as $items) {
            $delete_materiales = $this->model_clientes->delete_materials($id);
            if ($delete_materiales) {
              $cantidad = $items->cantidad;
              $update_almacen = $this->model_clientes->update_stock_almacen_mas($idalmacen, $cantidad);
              $insert_log = $this->model_log->insert_log("000000", "Instalaciones", "Accesorio eliminado de la tabla materiales <b>#" . $id . " por " . $_SESSION['nombre'] . " </b>", $_SESSION['id_usuario8291']);
              echo 1;
            }
          }
        } else {
          echo 2;
        }
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }

  public function nuevo_cliente_pre()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getGet('id');
      $permiso_sucursal_nodo = $this->permiso_sucursal->get_permiso_sucursal_nodo($_SESSION['sucursales']);
      $permiso_sucursal_select = $this->permiso_sucursal->get_permiso_sucursal_select($_SESSION['sucursales']);
      $data['zonas'] = $this->model_selectores->get_all_zonas();
      $data['nodos'] = $this->model_selectores->get_all_nodos_by_usuario($permiso_sucursal_nodo);
      $data['emisores'] = $this->model_selectores->get_all_antenas_amisoras();
      $data['sucursales'] = $this->model_selectores->get_all_sucursales_by_user($permiso_sucursal_select);
      $data['fecha'] = date('d/m/Y');
      $instalaciones = $this->model_clientes->get_pre_by_id($id);
      $instalaciones->fecha_instalacion = date('d/m/Y', strtotime($instalaciones->fecha_instalacion));
      $data['instalaciones'] = $instalaciones;

      return view('clientes/lista_clientes/nuevo_cliente_install', $data);
    }
  }

  public function guardar_nuevo_cliente_install()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'ESCRITURA', $_SESSION['id_usuario8291'])) {

      $idinstall = $this->request->getPost('usuarios[idinstall]');
      $usuarios_id = $this->request->getPost("usuarios[id]");
      $usuarios_codigo = $this->request->getPost("usuarios[codigo]");
      $usuarios_cedula = $this->request->getPost("usuarios[cedula]");
      $usuarios_nombre = $this->request->getPost("usuarios[nombre]");
      $usuarios_direccion_principal = $this->request->getPost("usuarios[direccion_principal]");
      $usuarios_telefono = $this->request->getPost("usuarios[telefono]");
      $usuarios_movil = $this->request->getPost("usuarios[movil]");
      $usuarios_correo = $this->request->getPost("usuarios[correo]");
      $usuarios_pasarela = $this->request->getPost("usuarios[Pasarela]");


      $config_zona = $this->request->getPost("config[zona]");
      $config_tipopago = $this->request->getPost("config[tipopago]");
      $config_diapago = $this->request->getPost("config[diapago]");
      $config_diafactura = $this->request->getPost("config[diafactura]");
      $config_impuesto = $this->request->getPost("config[impuesto]");
      $config_corteautomatico = $this->request->getPost("config[corteautomatico]");
      $config_meses = $this->request->getPost("config[meses]");
      $config_fecha_factura = $this->request->getPost("config[fecha_factura]");
      $config_mora = $this->request->getPost("config[mora]");
      $config_reconexion = $this->request->getPost("config[reconexion]");
      $config_afip = $this->request->getPost("config[afip]");
      $config_afip_punto_venta = $this->request->getPost("config[afip_punto_venta]");
      $config_afip_condicion_iva = $this->request->getPost("config[afip_condicion_iva]");
      $config_afip_condicion_venta = $this->request->getPost("config[afip_condicion_venta]");
      $config_afip_automatico = $this->request->getPost("config[afip_automatico]");
      $config_tipoaviso = $this->request->getPost("config[tipoaviso]");
      $config_avisopantalla = $this->request->getPost("config[avisopantalla]");
      $config_tiporecordatorio = $this->request->getPost("config[tiporecordatorio]");
      $config_avisosms = $this->request->getPost("config[avisosms]");
      $config_avisosms2 = $this->request->getPost("config[avisosms2]");
      $config_avisosms3 = $this->request->getPost("config[avisosms3]");

      $install = $this->request->getPost("install");
      $equipo = $this->request->getPost("equipo");
      $servicio_nodo = $this->request->getPost("servicio[nodo]");
      $servicio_firewall_state = $this->request->getPost("servicio[firewall_state]");
      $servicio_idperfil = $this->request->getPost("servicio[idperfil]");
      $servicio_descripcion = $this->request->getPost("servicio[descripcion]");
      $servicio_costo = $this->request->getPost("servicio[costo]");
      $servicio_tipoipv4 = $this->request->getPost("servicio[tipoipv4]");
      $servicio_redipv4 = $this->request->getPost("servicio[redipv4]");
      $servicio_ips = $this->request->getPost("servicio[ip]");
      $servicio_ip = "";
      foreach ($servicio_ips as $key => $ip) {
        if ($key == 0) {
          $servicio_ip .= $ip;
        } else {
          $servicio_ip .= "," . $ip;
        }
      }
      $servicio_mac = $this->request->getPost("servicio[mac]");
      $servicio_pppuser = $this->request->getPost("servicio[pppuser]");
      $servicio_ppppass = $this->request->getPost("servicio[ppppass]");
      $servicio_direccion = $this->request->getPost("servicio[direccion]");
      $servicio_coordenadas = $this->request->getPost("servicio[coordenadas]");
      $servicio_instalado = $this->request->getPost("servicio[instalado]");
      $servicio_instalado = str_replace("/", "-", $servicio_instalado);
      $servicio_instalado = date('d-m-Y', strtotime($servicio_instalado));
      $servicio_instalado = date('Y-m-d', strtotime($servicio_instalado));
      $servicio_emisor = $this->request->getPost("servicio[emisor]");
      $servicio_ipap = $this->request->getPost("servicio[ipap]");
      $servicio_antena = $this->request->getPost("servicio[antena]");
      $servicio_user_ubnt = $this->request->getPost("servicio[user_ubnt]");
      $servicio_pass_ubnt = $this->request->getPost("servicio[pass_ubnt]");
      $servicio_sucursal = $this->request->getPost("servicio[sucursal]");

      $impuestos1 = $this->request->getPost("impuestos[1]");
      $impuestos2 = $this->request->getPost("impuestos[2]");
      $impuestos3 = $this->request->getPost("impuestos[3]");
      $impuestos = "i:1;s:" . strlen($impuestos1) . ":\"" . $impuestos1 . "\";";
      $impuestos .= "i:2;s:" . strlen($impuestos2) . ":\"" . $impuestos2 . "\";";
      $impuestos .= "i:3;s:" . strlen($impuestos3) . ":\"" . $impuestos3 . "\";";
      $otros_impuestos = "a3{" . $impuestos . "}";

      $error = false;
      $client = false;
      $up_dow = $this->model_clientes->get_velocidades($servicio_idperfil);
      $server = $this->model_clientes->get_server($servicio_nodo);
      if ($server and $up_dow) {
        $server = $server[0];
        $up_dow = $up_dow[0];
      } else {
        $data_ajax['estado'] = 2;
        return json_encode($data_ajax);
      }

      $data_usuario = [
        'id' => $usuarios_id,
        'nombre' => $usuarios_nombre,
        'estado' => 'ACTIVO',
        'correo' => $usuarios_correo,
        'telefono' => $usuarios_telefono,
        'movil' => $usuarios_movil,
        'cedula' => $usuarios_cedula,
        'pasarela' => $usuarios_pasarela,
        'codigo' => $usuarios_codigo,
        'direccion_principal' => $usuarios_direccion_principal,
      ];
      $id_new_cliente = $this->model_clientes->new_usuario($data_usuario);
      if (!$id_new_cliente) {
        $data_ajax['estado'] = 2;
        return json_encode($data_ajax);
      }

      $data_aviso_user = [
        'cliente' => $id_new_cliente,
        'mora' => $config_mora,
        'reconexion' => $config_reconexion,
        'impuesto' => $config_impuesto,
        'chat' => 0,
        'zona' => $config_zona,
        'diapago' => $config_diapago,
        'tipopago' => $config_tipopago,
        'tipoaviso' => $config_tipoaviso,
        'meses' => $config_meses,
        'fecha_factura' => $config_fecha_factura,
        'diafactura' => $config_diafactura,
        'avisopantalla' => $config_avisopantalla,
        'corteautomatico' => $config_corteautomatico,
        'avisosms' => $config_avisosms,
        'avisosms2' => $config_avisosms2,
        'avisosms3' => $config_avisosms3,
        'afip_condicion_iva' => $config_afip_condicion_iva,
        'afip' => $config_afip,
        'avatar_color' => '#' . strtoupper(substr(md5(time()), 0, 6)),
        'afip_condicion_venta' => $config_afip_condicion_venta,
        'afip_automatico' => $config_afip_automatico,
        'tiporecordatorio' => $config_tiporecordatorio,
        'afip_punto_venta' => $config_afip_punto_venta,
        'otros_impuestos' => $otros_impuestos,
      ];
      $id_new_configuracion = $this->model_clientes->new_configuracion($data_aviso_user);
      if (!$id_new_configuracion) {
        $result_delete_usuario = $this->model_clientes->delete_clientes($id_new_cliente);
        $data_ajax['estado'] = 2;
        return json_encode($data_ajax);
      }
      $data_servicio = [
        'idcliente' => $id_new_cliente,
        'idperfil' => $servicio_idperfil,
        'nodo' => $servicio_nodo,
        'sucursal' => $servicio_sucursal,
        'descripcion' => $servicio_descripcion,
        'costo' => $servicio_costo,
        'ipap' => $servicio_ipap,
        'mac' => $servicio_mac,
        'ip' => $servicio_ip,
        'instalado' => $servicio_instalado,
        'antena' => $servicio_antena,
        'emisor' => $servicio_emisor,
        'user_ubnt' => $servicio_user_ubnt,
        'pass_ubnt' => $servicio_pass_ubnt,
        'tipoipv4' => $servicio_tipoipv4,
        'redipv4' => $servicio_redipv4,
        'coordenadas' => $servicio_coordenadas,
        'direccion' => $servicio_direccion,
        'firewall_state' => $servicio_firewall_state,
      ];

      $data_tbl = ['userid' => $id_new_cliente, 'estate' => 'INSTALADO'];
      $id_new_servicio = $this->model_clientes->new_servicio($data_servicio);
      if ($id_new_servicio) {
        try {
          $client = new RouterOS\Client($server->ip, $server->usuario, $server->contrasena, $server->puerto);
        } catch (\Throwable $e) {
          $client = false;
          $data_ajax['mensaje'] = "Se inserto en la Base de datos, pero no se dio de alta en el Sistema de mikrotik correspondiente.";
        }
        if ($client) {
          try {
            $pcq_subida = $up_dow->pcq_subida == "" ? "default-small" : $up_dow->pcq_subida;
            $pcq_descarga = $up_dow->pcq_descarga == "" ? "default-small" : $up_dow->pcq_descarga;
            $util = new RouterOS\Util($client);
            $util->setMenu('/queue simple');
            $data_queue = array(
              'name' => "Mkws_queue_$id_new_servicio",
              'target' => $servicio_ip,
              'max-limit' => $up_dow->velocidad,
              'comment' => $usuarios_nombre,
              'priority' => $up_dow->prioridad_subida . "/" . $up_dow->prioridad_descarga,
              "queue" => $pcq_subida . "/" . $pcq_descarga,
              "limit-at" => $up_dow->limitat_subida . "k/" . $up_dow->limitat_descarga . "k",
              "burst-limit" => $up_dow->burst_limit_subida . "k/" . $up_dow->burst_limit_descarga . "k",
              "burst-threshold" => $up_dow->burst_threshold_subida . "k/" . $up_dow->burst_threshold_descarga . "k",
              "burst-time" => $up_dow->burst_time_subida . "s/" . $up_dow->burst_time_descarga . "s",
            );
            $util->add($data_queue);
          } catch (RouterOS\RouterErrorException $e) {
            $error_message = "";
            if ($e->getCode() === RouterOS\RouterErrorException::CODE_GET_ERROR) {
              $error_message = $e->getResponses()->getProperty('message');
            } else {
              $error_message = $e->getResponses()->getProperty('message');
            }
            $data_ajax['mensaje'] = "<p>Se inserto en la Base de datos, pero no se dio de alta en el Sistema de mikrotik correspondiente.</p><b>Error:</b><p>$error_message</p>";
          }
        }

        //$insert_pre = $this->model_clientes->update_pre_install($idinstall, 'INSTALADO');
        $update_tbl = $this->model_clientes->save_update_pre_install($idinstall, $data_tbl);
        $txt_log = "Cliente agregado  <b>#" . $id_new_cliente . " ($usuarios_nombre)</b>  desde instalaciones";
        $insert_log = $this->model_log->insert_log($id_new_cliente, "Clientes", $txt_log, $_SESSION['id_usuario8291']);
        $data_ajax['estado'] = 1;
        return json_encode($data_ajax);
      } else {
        $result_delete_usuario = $this->model_clientes->delete_clientes($id_new_cliente);
        $result_delete_servicio = $this->model_clientes->delete_configuracion($id_new_configuracion);
        $data_ajax['mensaje'] = "";
        $data_ajax['estado'] = 2;
        return json_encode($data_ajax);
      }
    }
  }
  public function update_install()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('INSTALACIONES'));
      if ($tipoacceso >= 1) {
        $id = $this->request->getGet('id');
        $data['tbluser'] = $this->model_selectores->get_usuarios();
        $permiso_sucursal_select = $this->permiso_sucursal->get_permiso_sucursal_select($_SESSION['sucursales']);
        $data['zonas'] = $this->model_selectores->get_all_zonas();
        $data['instalaciones'] = $this->model_clientes->get_pre_by_id($id);
        $data['sucursales'] = $this->model_selectores->get_all_sucursales_by_user($permiso_sucursal_select);

        echo view('clientes/instalaciones/modal_editar', $data);
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }

  public function update_preinstall()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('INSTALACIONES'));
      if ($tipoacceso >= 1) {
        $id = $this->request->getGet('id');
        $data['tbluser'] = $this->model_selectores->get_usuarios();
        $permiso_sucursal_select = $this->permiso_sucursal->get_permiso_sucursal_select($_SESSION['sucursales']);
        $data['zonas'] = $this->model_selectores->get_all_zonas();
        $data['instalaciones'] = $this->model_clientes->get_pre_by_id($id);
        $data['sucursales'] = $this->model_selectores->get_all_sucursales_by_user($permiso_sucursal_select);

        echo view('clientes/instalaciones/editar_preinstall', $data);
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }

  public function save_update_install()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('INSTALACIONES'));
      if ($tipoacceso >= 1) {
        $id = $this->request->getPost('id');
        $sucursal = $this->request->getPost('install[sucursal]');
        $cliente = $this->request->getPost('pre[cliente]');
        $direccion = $this->request->getPost('pre[direccion]');
        $cedula = $this->request->getPost('pre[cedula]');
        $telefono = $this->request->getPost('pre[telefono]');
        $movil = $this->request->getPost('pre[movil]');
        $email = $this->request->getPost('pre[email]');
        $fecha_instalacion = $this->request->getPost('pre[fecha_instalacion]');
        $zona = $this->request->getPost('pre[zona]');
        $estate = $this->request->getPost('pre[estate]');
        $idtecnico = $this->request->getPost('pre[idtecnico]');
        $fecha_salida = $this->request->getPost('pre[fecha_salida]');
        $notas = $this->request->getPost('pre[notas]');
        $data_install_update = [
          'sucursal' => $sucursal,
          'cliente' => $cliente,
          'direccion' => $direccion,
          'cedula' => $cedula,
          'telefono' => $telefono,
          'movil' => $movil,
          'email' => $email,
          'fecha_instalacion' => $fecha_instalacion,
          'zona' => $zona,
          'estate' => $estate,
          'idtecnico' => $idtecnico,
          'fecha_salida' => $fecha_salida,
          'notas' => $notas
        ];

        $update_save = $this->model_clientes->save_update_pre_install($id, $data_install_update);
        if ($update_save) {

          $insert_log = $this->model_log->insert_log("000000", "Instalaciones", "Instalacion editada <b>#" . $id . " por " . $_SESSION['nombre'] . " </b>", $_SESSION['id_usuario8291']);
          echo 1;
        } else {
          echo 2;
        }
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }


  public function delete_install()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('INSTALACIONES'));
      if ($tipoacceso >= 3) {
        $id = $this->request->getPost('id');

        $sql_material = $this->model_clientes->get_materials_add($id);
        if ($sql_material) {
          foreach ($sql_material as $itemM) {
            if ($itemM->condicion == 1 && $itemM->idinstall == $id) {
              $delete_materials = $this->model_clientes->delete_materials_by($id);
              if ($delete_materials && $itemM->condicion == 1 && $itemM->idinstall == $id) {
                $update_almacen_idalmacen = $this->model_clientes->update_almacen_simple($itemM->idalmacen, 'disponible');
                $delete = $this->model_clientes->delete_instalacion($id);
                if ($delete) {
                  $insert_log = $this->model_log->insert_log("000000", "Instalaciones", "Pre instalacion eliminada <b>#" . $id . " de la tabla instalaciones por " . $_SESSION['nombre'] . " </b>", $_SESSION['id_usuario8291']);
                  echo 1;
                } else {
                  echo 2;
                }
              }
            }
          }
        } else {
          $delete = $this->model_clientes->delete_instalacion($id);
          if ($delete) {
            $insert_log = $this->model_log->insert_log("000000", "Instalaciones", "Pre instalacion eliminada <b>#" . $id . " de la tabla instalaciones por " . $_SESSION['nombre'] . " </b>", $_SESSION['id_usuario8291']);
            echo 1;
          } else {
            echo 2;
          }
        }
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }

  public function to_print()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('INSTALACIONES'));
      if ($tipoacceso >= 1) {
        $id = $this->request->getGet('id');
        $productos = "";
        $accesorios = "";
        $servicio = "";
        $sucursal = "";

        $sql_install = $this->model_clientes->get_instal_by_id($id);

        if ($sql_install) {
          foreach ($sql_install as $itemI) {
            $sql_html = $this->model_formatos->get_formato_install($itemI->sucursal);
            $sql_productos = $this->model_clientes->get_materials_add($id);
            $arrayP = array();
            if ($sql_productos) {
              foreach ($sql_productos as $itemP) {
                $productos .= "
                <tr>
                <td>$itemP->tipo</td>
                <td>$itemP->producto</td>
                <td>$itemP->mac_producto</td>
                <td>$itemP->serial_producto</td>                
                </tr>";
              }
            }
            $sql_accesorios = $this->model_clientes->get_accesorios_add($id);
            if ($sql_accesorios) {
              foreach ($sql_accesorios as $itemA) {
                $accesorios .= "
                <tr>
                <td>$itemA->detalle</td>
                <td>$itemA->cantidad</td>
                </tr>
                ";
              }
            }
            $sql_servicios = $this->model_clientes->get_servicios_cliente($itemI->userid);
            if ($sql_servicios) {
              foreach ($sql_servicios as $itemS) {
                $servicio .= "
                <tr>
                <td>$itemS->plan</td>
                <td>$ $itemS->costo</td>
                </tr>
                ";
              }
            }
            $sql_sucursal = $this->model_clientes->get_sucurisal_byid($itemI->sucursal);

            $html = $sql_html[0]->html;
            if ($sql_html[0]->tamano != "") {
              $formato = $sql_html[0]->tamano;
            } else {
              $formato = [intval($sql_html[0]->ancho), intval($sql_html[0]->alto)];
            }
            $config = [

              'format' => $formato . $sql_html[0]->posicion
            ];
            $mpdf = new \Mpdf\Mpdf($config);

            if ($itemI->userid == 000000) {

              $data = [
                "{barcode}" => $itemI->id, //itemI Sql de instalacion y sql result all sucursal
                "{url}" => $sql_sucursal[0]->logo_facturas_recibos,
                "images/logofactura.png" => "/" . $sql_sucursal[0]->logo_facturas_recibos, // plantillas antiguas apuntan aquí al logo
                "{nombre_empresa}" => $sql_sucursal[0]->alias,
                "{ruc_empresa}" =>  $sql_sucursal[0]->rfc,
                "{direccion_empresa}" => $sql_sucursal[0]->direccion,
                "{telefono_empresa}" => "TEL: " . $sql_sucursal[0]->telefono,
                "{nombre_cliente}" => $itemI->cliente,
                "{movil_cliente}" => $itemI->movil,
                "{direccion_principal_cliente}" => $itemI->direccion,
                "{password}" => "Sin definir",
                "{observaciones}" => nl2br($itemI->notas),
                "{productos}" => $productos,
                "{materiales}" => $accesorios,
                "{servicios}" => $servicio,
                "{nombre_tecnico}" => $itemI->tecnico
              ];
            } else {
              $sql_user_installed = $this->model_clientes->get_user_by_id($itemI->userid);
              $data = [
                "{barcode}" => $itemI->id, //itemI Sql de instalacion y sql result all sucursal
                "{url}" => $sql_sucursal[0]->logo_facturas_recibos,
                "images/logofactura.png" => "/" . $sql_sucursal[0]->logo_facturas_recibos, // plantillas antiguas apuntan aquí al logo
                "{nombre_empresa}" => $sql_sucursal[0]->alias,
                "{ruc_empresa}" =>  $sql_sucursal[0]->rfc,
                "{direccion_empresa}" => $sql_sucursal[0]->direccion,
                "{telefono_empresa}" => "TEL: " . $sql_sucursal[0]->telefono,
                "{nombre_cliente}" => $sql_user_installed[0]->nombre,
                "{movil_cliente}" => $sql_user_installed[0]->movil,
                "{direccion_principal_cliente}" => $sql_user_installed[0]->direccion_principal,
                "{password}" => $sql_user_installed[0]->codigo,
                "{observaciones}" => nl2br($itemI->notas),
                "{productos}" => $productos,
                "{materiales}" => $accesorios,
                "{servicios}" => $servicio,
                "{nombre_tecnico}" => $itemI->tecnico
              ];
            }

            $html = html_entity_decode($html);
            $data += CamposPlantilla::valores($html, $sql_sucursal[0] ?? null); // {sucursal_*}, {config_*}
            $html = strtr($html, $data);
            $mpdf->WriteHTML($html);
            $mpdf->Output("./files/pdfs/Hoja_Instalacion.pdf", "F"); // opens in browser
            $this->show("Hoja_Instalacion.pdf");
          }
        }
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
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


  //End funciones instalaciones

  public function save_edit_cliente()
  {
    $tipoaccesodatosprivados = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('DATOS_CLIENTES_PRIVADOS'));
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $idcliente = $this->request->getGet('idcliente');
      $cliente_codigo = $this->request->getPost('cliente[codigo]');
      $cliente_cedula = $this->request->getPost('cliente[cedula]');
      $cliente_nombre = $this->request->getPost('cliente[nombre]');
      $cliente_direccion_principal = $this->request->getPost('cliente[direccion_principal]');
      $cliente_movil = $this->request->getPost('cliente[movil]');
      $cliente_correo = $this->request->getPost('cliente[correo]');
     /* $cliente_Netflix_Activo = $this->request->getPost('cliente[Netflix_Activo]');
      $cliente_Netflix_Datos = $this->request->getPost('cliente[Netflix_Datos]');
      $cliente_Camaras = $this->request->getPost('cliente[Camaras]');
      $cliente_TV = $this->request->getPost('cliente[TV]');
      $cliente__Cableado = $this->request->getPost('cliente[_Cableado]');
      $cliente_Wisp = $this->request->getPost('cliente[Wisp]');
      $cliente_Factibilidad = $this->request->getPost('cliente[Factibilidad]');
      $cliente_Referencia1 = $this->request->getPost('cliente[Referencia1]');
      $cliente_Referencia2 = $this->request->getPost('cliente[Referencia2]');
      $cliente_Colonia = $this->request->getPost('cliente[Colonia]');
      $cliente_Ejecutivo = $this->request->getPost('cliente[Ejecutivo]');
      $zona = $this->request->getPost('pre[zona]');*/
      $data_cliente = [
        'nombre' => $cliente_nombre,
        'correo' => $cliente_correo,
       // 'telefono' => $cliente_telefono,
        'movil' => $cliente_movil,
        'cedula' => $cliente_cedula,
      //  'pasarela' => $cliente_pasarela,
        'codigo' => $cliente_codigo,
        'direccion_principal' => $cliente_direccion_principal,
      ];

      if ($tipoaccesodatosprivados > 0) {
        $data_cliente_private = [
          'iduser' => $idcliente,
        //  'Netflix_Activo' => $cliente_Netflix_Activo,
        //  'Netflix_Datos' => $cliente_Netflix_Datos,
        //  'Camaras' => $cliente_Camaras,
        //  'Cableado' => $cliente__Cableado,
        //  'Wisp' => $cliente_Wisp,
        //  'Factibilidad' => $cliente_Factibilidad,
        //  'Referencia1' => $cliente_Referencia1,
        //  'Referencia2' => $cliente_Referencia2,
        //  'Colonia' => $cliente_Colonia,
        //  'Ejecutivo' => $cliente_Ejecutivo,
        ];
      } else {
        $data_cliente_private = [
          'iduser' => $idcliente,
          //'Cableado' => $cliente__Cableado,
         // 'Wisp' => $cliente_Wisp,
         // 'Factibilidad' => $cliente_Factibilidad,
         // 'Referencia1' => $cliente_Referencia1,
         // 'Referencia2' => $cliente_Referencia2,
         // 'Colonia' => $cliente_Colonia,
         // 'Ejecutivo' => $cliente_Ejecutivo,
        ];
      }
      $data_cliente_zona = [
      //  'zona' => $zona
      ];

      $result_save_cliente = $this->model_clientes->update_cliente($idcliente, $data_cliente);
   //   $result_save_cliente_zona = $this->model_clientes->update_cliente_zona($idcliente, $data_cliente_zona);

      $check_folio_tabla_privados = $this->model_clientes->check_folio_tabla_private($idcliente);
      if ($check_folio_tabla_privados) {

     //   $result_save_cliente_private = $this->model_clientes->update_cliente_private($idcliente, $data_cliente_private);
      } else {
     //   $result_save_cliente_private = $this->model_clientes->insert_cliente_private($data_cliente_private);
      }

     $result_save_cliente_private=true;
      if ($result_save_cliente > 0 or $result_save_cliente_private > 0) {
        $txt_log = "Datos editados del cliente";
        $insert_log = $this->model_log->insert_log($idcliente, "cliente", $txt_log, $_SESSION['id_usuario8291']);
        echo $cliente_nombre;
      } else if ($result_save_cliente == 0 && $result_save_cliente_private == 0) {
        echo 2;
      } else {
        echo 3;
      }
    }
  }

  public function mihistorial()
  {

    $idusuario = $_SESSION['id_usuario8291'];
    $historial = $this->model_clientes->get_historial_personal($idusuario);
    $data['historial'] = $historial ? $historial : array();
    return view('historial_personal/historial_personal', $data);
  }
  public function masive_create_queues()
  {
    if ($this->seguridad->Acceso('INTERNET', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getPost('id');
      $error_message = "";
      $data = array();
      $servicios = $this->model_clientes->get_servicios($id);
      $txt_errores = "<p>Los servicios con los sig ID's no se crearon correctamente en el mikrotick </p><b>Servicios:</b>";
      $error = false;
      $existentes = 0;
      $error_perfiles = 0;
      $otros = 0;
      $arrayErrores = array();
      $server = $this->model_clientes->get_server_info($id);
      if ($servicios and $server) {
        try {
          $client = new RouterOS\Client($server->ip, $server->usuario, $server->contrasena, $server->puerto);
        } catch (\Throwable $th) {
          // MikroTik inalcanzable: misma respuesta que sin servidor (antes: error 500)
          echo 2;
          return null;
        }
        $util = new RouterOS\Util($client);
        $util->setMenu('/queue simple');
        foreach ($servicios as $key => $value) {
          $id = 3;
          $idservicio = $value->idservicio;
          $up_dow = $this->model_clientes->get_velocidades_row($value->idperfil);
          if ($up_dow) {
            try {
              $pcq_subida = $up_dow->pcq_subida == "" ? "default-small" : $up_dow->pcq_subida;
              $pcq_descarga = $up_dow->pcq_descarga == "" ? "default-small" : $up_dow->pcq_descarga;
              $data_queue = array(
                'name' => "Mkws_queue_$idservicio",
                'target' => $value->ip_servicio,
                'max-limit' => $up_dow->velocidad,
                'comment' => $value->nombre,
                'priority' => $up_dow->prioridad_subida . "/" . $up_dow->prioridad_descarga,
                "queue" => $pcq_subida . "/" . $pcq_descarga,
                "limit-at" => $up_dow->limitat_subida . "k/" . $up_dow->limitat_descarga . "k",
                "burst-limit" => $up_dow->burst_limit_subida . "k/" . $up_dow->burst_limit_descarga . "k",
                "burst-threshold" => $up_dow->burst_threshold_subida . "k/" . $up_dow->burst_threshold_descarga . "k",
                "burst-time" => $up_dow->burst_time_subida . "s/" . $up_dow->burst_time_descarga . "s",
              );
              $util->add($data_queue);
            } catch (RouterOS\RouterErrorException $e) {
              if ($e->getCode() === RouterOS\RouterErrorException::CODE_GET_ERROR) {

                $error_message = $e->getResponses()->getProperty('message');
              } else {
                $error_message = $e->getResponses()->getProperty('message');
              }
              if ($error_message == "failure: download-burst-limit less than download-max-limit") {
                $error_message = "(Mikrotik) Error en el Burst-limit del <b>perfil</b>";
                $error_perfiles++;
                $id = 2;
              } elseif ($error_message == "failure: already have such name") {
                $existentes++;
                $error_message = "(Mikrotik) El Queues ya existe.";
                $id = 1;
              } else {
                $otros++;
              }
              $error = true;
              $arrayErrores[] = [
                "id_cliente" => $value->id_cliente,
                "id_servicio" => $idservicio,
                "nombre" => $value->nombre,
                "error" => $error_message,
                "id_perfil" => $value->idperfil,
                "img" => $value->avatar_cliente,
                "id" => $id
              ];
            }
          } else {
            $error = true;
            $arrayErrores[] = [
              "id_cliente" => $value->id_cliente,
              "id_servicio" => $idservicio,
              "nombre" => $value->nombre,
              "error" => "No existe el <b>perfil</b> con ID :" . $value->idperfil,
              "id_perfil" => null,
              "img" => $value->avatar_cliente,
              "id" => $id
            ];
          }
        }
        if ($error) {
          $arrayErrores = $this->array_sort($arrayErrores, 'id', SORT_ASC);
          $data['estado'] = 2;
          $data['existentes'] = $existentes;
          $data['perfiles'] = $error_perfiles;
          $data['otros'] = $otros;
          $data['total'] = $existentes + $error_perfiles + $otros;
          $data['clientes'] = $arrayErrores;
        } else {
          $data['estado'] = 1;
          $data['clientes'] = array();
        }
        return view('servicios/utils/temporal_erros', $data);
      } else {
        echo 2;
      }
    }
  }
  public function validar_mac()
  {
    if ($this->seguridad->Acceso('LISTA_CLIENTES', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $mac = $this->request->getPost('mac');
      $servicios = $this->model_clientes->get_macs($mac);
      $tmp = "";
      if ($servicios) {
        foreach ($servicios as $value) {
          $idcliente = $value->idcliente;
          $tmp .= "<br><a href=\"" . base_url() . "?#clientes/detalleuser?id=$idcliente\" target=\"_blank\">[" . $value->idcliente . "] " . $value->nombre . "ir al cliente</a>\n";
        }
        $tmp .= "<hr>";
      }
      $data['mensaje'] = $tmp;
      return json_encode($data);
    }
  }
  public function nav_email_sms()
  {
    $permiso = $this->seguridad->Acceso('EMAIL_SMS_CLIENTE', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getGet('id');
      $mensajes = $this->model_clientes->get_sms_cliente($id);
      $data['permiso'] = $permiso;
      $data['mensajes'] = $mensajes;
      $data['id'] = $id;
      return view('clientes/lista_clientes/taps/email_sms', $data);
    }
  }
  public function nuevo_mensaje_cliente()
  {
    $id = $this->request->getGet('id');
    $permiso = $this->seguridad->Acceso('EMAIL_SMS_CLIENTE', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $cliente = $this->model_sms->get_cliente_num($id);
      if ($cliente) {
        if ($cliente->movil == "") {
          echo "<>alerta(\"warning\",\"El cliente no cuenta con un telefono movil asignado.\")</>";
        } else {
          $movil =  str_replace(", ", ",", preg_replace("([^0-9]\,)", "", $cliente->movil));
          $numeros = "838389";
          $data['permiso'] = $permiso;
          $data['numeros'] = $numeros;
          $data['id'] = $id;
          $data['movil'] = $movil;
          return view('clientes/lista_clientes/taps/sub_taps/utils/modal_new_mensaje_cliente', $data);
        }
      } else {
        echo "<>alerta(\"warning\",\"No se encontro al cliente en la base de datos.\")</>";
      }
    }
  }
  public function new_message()
  {
    $permiso = $this->seguridad->Acceso('EMAIL_SMS_CLIENTE', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $input_numeros = $this->request->getPost('numeros');
      $newsms = $this->request->getPost('newsms');
      $smsconfig = $this->model_sms->get_config('2');
      $id = $this->request->getGet('id');
      $numerosSms = array();
      if ($smsconfig) {
        try {
          $config = Configuration::getDefaultConfiguration();
          $config->setApiKey('Authorization', $smsconfig->userapi);
          $apiClient = new ApiClient($config);
          $messageClient = new MessageApi($apiClient);
          $data_sms = array();
          foreach ($input_numeros as $key => $numero) {
            $numerosSms[] = $numero;
            $data_sms[] = new SendMessageRequest([
              'phoneNumber' => $numero,
              'message' => $newsms,
              'deviceId' => $smsconfig->idequipo
            ]);
          }
          $sendMessages = $messageClient->sendMessages($data_sms);
        } catch (\Throwable $th) {
          echo 3;
          return;
        }
        if ($sendMessages) {
          $data_insert = array();
          foreach ($sendMessages as $key => $value) {
            $data[] = [
              'user' => $id,
              'enviado'  => date("Y-m-d H:i:s"),
              'ndestino'  => $numerosSms[$key],
              'stado'  => $value->getStatus() == "pending" ? "en espera" : "enviado",
              'mensaje'  => $value->getMessage(),
              'proveedor'  => 2,
              'sms_id'  => $value->getId()
            ];
          }
          $insert_sms = $this->model_sms->insert_sms($data);
          $txt_log = "Nuevo sms creado - {{cliente}}(" . $newsms . ")";
          $this->model_log->insert_log($value, "sms", $txt_log, $_SESSION['id_usuario8291']);
          echo 1;
        }
        if (strpos($input_numeros, ',')) {
          $numeros = explode(",", $input_numeros);
        } else {
          $numeros = str_split($input_numeros, 10);
        }
      } else {
        echo 4;
      }
    }
  }
  public function delete_nota()
  {
    $permiso = $this->seguridad->Acceso('DOCUMENTOS_VIEW_ESPECIAL', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getPost('id');
      $nota = $this->model_clientes->get_nota($id);
      if ($nota) {
        $ruta = "./files/notas_cliente/" . $nota->imagen;
        if ($nota->adjuntos != "" and file_exists($ruta)) {
          unlink($ruta);
        }
        $delete = $this->model_clientes->delete_nota($id);
        if ($delete) {
          $txt_log = "Nota eliminada ID: $id";
          $insert_log = $this->model_log->insert_log($nota->idcliente, "documentos & notas", $txt_log, $_SESSION['id_usuario8291']);
        }
        echo $delete ? 1 : 2;
      } else {
        echo 3;
      }
    }
  }
  public function delete_documento()
  {
    $permiso = $this->seguridad->Acceso('DOCUMENTOS_VIEW_ESPECIAL', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getPost('id');
      $nota = $this->model_clientes->get_nota($id);
      if ($nota) {
        $ruta = "./files/notas_cliente/" . $nota->imagen;
        if ($nota->adjuntos != "" and file_exists($ruta)) {
          unlink($ruta);
        }
        $delete = $this->model_clientes->delete_nota($id);
        if ($delete) {
          $txt_log = "Nota eliminada ID: $id";
          $insert_log = $this->model_log->insert_log($nota->idcliente, "documentos & notas", $txt_log, $_SESSION['id_usuario8291']);
        }
        echo $delete ? 1 : 2;
      } else {
        echo 3;
      }
    }
  }
  public function modal_new_nota()
  {
    $permiso = $this->seguridad->Acceso('DOCUMENTOS_VIEW_ESPECIAL', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getGet('id');
      $data['id'] = $id;
      return view('clientes/lista_clientes/taps/utils/modal_new_nota', $data);
    }
  }
  public function render_img_nota()
  {
    $permiso = $this->seguridad->Acceso('DOCUMENTOS_VIEW_ESPECIAL', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $nombre = $this->request->getGet('nombre');
      $image_path = './files/notas_cliente/' . $nombre;
      if (file_exists($image_path)) {
        $archivo = file_get_contents($image_path);
        $this->response->setContentType(mime_content_type($image_path));
        return $archivo;
      } else {
        echo "No se encontro el archivo solicitado.";
      }
    }
  }

  public function modal_new_documento()
  {
    $permiso = $this->seguridad->Acceso('DOCUMENTOS_VIEW_ESPECIAL', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getGet('id');
      $data['id'] = $id;
      return view('clientes/lista_clientes/taps/utils/modal_new_documento', $data);
    }
  }
  public function modal_edit_documento()
  {
    $permiso = $this->seguridad->Acceso('DOCUMENTOS_VIEW_ESPECIAL', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getGet('id');
      $documento = $this->model_clientes->get_documento($id);
      if ($documento) {
        $data['documento'] = $documento;
        return view('clientes/lista_clientes/taps/utils/modal_edit_documento', $data);
      } else {
        echo "<>alerta(\"error\,\"No se encontro el cocumento.\");</>";
      }
    }
  }
  public function save_edit_documento_cliente()
  {
    $permiso = $this->seguridad->Acceso('DOCUMENTOS_VIEW_ESPECIAL', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getPost('id');
      $asunto = $this->request->getPost('titulo');
      /* $idcliente=$this->request->getPost('idcliente'); */
      $documento = $this->model_clientes->get_documento($id);
      if ($documento) {
        $data = ["asunto" => $asunto];
        $update = $this->model_clientes->edit_documento($documento->id, $data);
        if ($update) {
          $txt_log = "Documento editado, se cambio el titulo de ($documento->asunto)a ($asunto) ID: $id";
          $insert_log = $this->model_log->insert_log($documento->idcliente, "documentos & notas", $txt_log, $_SESSION['id_usuario8291']);
        }
        echo $update ? 1 : 2;
      } else {
        echo 3;
      }
    }
  }
  public function save_documento_cliente()
  {
    $permiso = $this->seguridad->Acceso('DOCUMENTOS_VIEW_ESPECIAL', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $file = $this->request->getFile('nota_file');
      $idcliente = $this->request->getPost('idcliente');
      $nota_asunto = $this->request->getPost('nota_asunto');
      /*  $nota_mensaje=$this->request->getPost('nota_mensaje');
      $portal=$this->request->getPost('portal'); */
      if ($file->isValid() && !$file->hasMoved()) {
        $newName = strtotime(date('Y-m-d H:i:s')) . "_" . $file->getRandomName();
        $file->move('./files/documentos_cliente', $newName);
        if ($file->hasMoved()) {
          $data = [
            "idcliente" => $idcliente,
            "fechaalta" => date('Y-m-d H:i:s'),
            "adjunto" => $newName,
            "operador" => $_SESSION['id_usuario8291'],
            "nombre_real" => $file->getClientName(),
            "asunto" => $nota_asunto,
          ];
          $new_documento = $this->model_clientes->new_documento($data);
          if ($new_documento) {
            $txt_log = "Documento agregado ($nota_asunto) ID: $new_documento";
            $insert_log = $this->model_log->insert_log($idcliente, "documentos & notas", $txt_log, $_SESSION['id_usuario8291']);
          }
          echo $new_documento ? 1 : 2;
        } else {
          echo 2;
        }
      } else {
        echo 3;
        return;
      }
    }
  }
  public function render_documento_nota()
  {
    $permiso = $this->seguridad->Acceso('DOCUMENTOS_VIEW_ESPECIAL', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $nombre = $this->request->getGet('nombre');
      $image_path = './files/documentos_cliente/' . $nombre;
      if (file_exists($image_path)) {
        $archivo = file_get_contents($image_path);
        $this->response->setContentType(mime_content_type($image_path));
        return $archivo;
      } else {
        echo "No se encontro el archivo solicitado.";
      }
    }
  }
  public function remove_documento()
  {
    $permiso = $this->seguridad->Acceso('DOCUMENTOS_VIEW_ESPECIAL', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getPost('id');
      $documento = $this->model_clientes->get_documento($id);
      if ($documento) {
        $ruta = "./files/documentos_cliente/" . $documento->adjunto;
        if ($documento->adjunto != "" and file_exists($ruta)) {
          unlink($ruta);
        }
        $remove = $this->model_clientes->delete_documento($id);
        if ($remove) {
          $txt_log = "Documento eliminado ID: $id";
          $insert_log = $this->model_log->insert_log($documento->idcliente, "documentos & notas", $txt_log, $_SESSION['id_usuario8291']);
        }
        echo $remove ? 1 : 2;
      } else {
        echo 3;
      }
    }
  }
  public function save_nota()
  {
    $permiso = $this->seguridad->Acceso('DOCUMENTOS_VIEW_ESPECIAL', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $file = $this->request->getFile('nota_adjunto');
      $titulo = $this->request->getPost('nota_titulo');
      $mensaje = $this->request->getPost('nota_mensaje');
      $idcliente = $this->request->getPost('idcliente');
      if ($file == NULL) {
        $data = [
          "idoperador" => $_SESSION['id_usuario8291'],
          "idcliente" => $idcliente,
          "asunto" => ucfirst($titulo),
          "mensaje" => ucfirst($mensaje),
          "adjuntos" => "",
          "imagen" => "",
          "fecha" => date('Y-m-d H:i:s'),
        ];
        $new_nota = $this->model_clientes->new_nota($data);
        if ($new_nota) {
          $txt_log = "Nota agregada ID: $new_nota";
          $insert_log = $this->model_log->insert_log($idcliente, "documentos & notas", $txt_log, $_SESSION['id_usuario8291']);
        }
        echo $new_nota ? 1 : 2;
      } else {
        if ($file->isValid() && !$file->hasMoved()) {
          $newName = strtotime(date('Y-m-d H:i:s')) . "_" . $file->getRandomName();
          $file->move('./files/notas_cliente', $newName);
          if ($file->hasMoved()) {
            $data = [
              "idoperador" => $_SESSION['id_usuario8291'],
              "idcliente" => $idcliente,
              "asunto" => ucfirst($titulo),
              "mensaje" => ucfirst($mensaje),
              "adjuntos" => $file->getClientName(),
              "imagen" => $newName,
              "fecha" => date('Y-m-d H:i:s'),
            ];
            $new_nota = $this->model_clientes->new_nota($data);
            if ($new_nota) {
              $txt_log = "Nota agregada ID: $new_nota";
              $insert_log = $this->model_log->insert_log($idcliente, "documentos & notas", $txt_log, $_SESSION['id_usuario8291']);
            }
            echo $new_nota ? 1 : 2;
          } else {
            echo 2;
          }
        } else {
          echo 3;
          return;
        }
      }
    }
  }
  public function save_geocerca()
  {
    $permiso = $this->seguridad->Acceso_void('GEOCERCAS', 'LECTURA', $_SESSION['id_usuario8291']);
    $data = array();
    if ($permiso) {
      $nombre = $this->request->getPost('nombre');
      $descripcion = $this->request->getPost('descripcion');
      $jsonString = $this->request->getPost('jsonString');
      $tipo_tecnologia = $this->request->getPost('tipo_tecnologia');
      $color_linea = $this->request->getPost('color_linea');
      $color_relleno = $this->request->getPost('color_relleno');
      $tipo = $this->request->getPost('tipo');
      $data = [
        "coordenadas" => $jsonString,
        "tipo" => $tipo,
        "color_relleno" => $color_relleno,
        "color_linea" => $color_linea,
        "nombre" => $nombre,
        "descripcion" => $descripcion,
        "tipo_tecnologia" => $tipo_tecnologia
      ];
      $save = $this->model_clientes->save_geocerca($data);
      if ($save) {
        $txt_log = "Geocerca agregada ID: $save";
        $insert_log = $this->model_log->insert_log(000000, "mapa_geocerca", $txt_log, $_SESSION['id_usuario8291']);
        $data['code'] = 1;
        $data['id'] = $save;
      } else {
        $data['code'] = 2;
      }
    } else {
      $data['code'] = 3;
    }
    return json_encode($data);
  }
  public function delete_geocerca()
  {
    $permiso = $this->seguridad->Acceso_full('GEOCERCAS', 'ELIMINAR', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getPost('id');
      $delete = $this->model_clientes->delete_geocerca($id);
      if ($delete) {
        $txt_log = "Geocerca eliminada ID: $delete";
        $insert_log = $this->model_log->insert_log(000000, "mapa_geocerca", $txt_log, $_SESSION['id_usuario8291']);
      }
      echo $delete ? 1 : 2;
    }
  }
  public function update_geocerca()
  {
    $permiso = $this->seguridad->Acceso_full('GEOCERCAS', 'ESCRITURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getPost('id');
      $coordenadas = $this->request->getPost('txt');
      $data = [
        "coordenadas" => $coordenadas
      ];
      $update = $this->model_clientes->update_geocerca($id, $data);
      if ($update) {
        $txt_log = "Geocerca editada ID: $id";
        $insert_log = $this->model_log->insert_log(000000, "mapa_geocerca", $txt_log, $_SESSION['id_usuario8291']);
      }
      echo $update ? 1 : 2;
    }
  }
  public function geocercas()
  {
    $permiso = $this->seguridad->Acceso_full('GEOCERCAS', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $geocercas = $this->model_clientes->get_all_geocercas();
      $permiso_sucursal_nodo = $this->permiso_sucursal->get_permiso_sucursal_nodo($_SESSION['sucursales']);
      $permiso_sucursal_select = $this->permiso_sucursal->get_permiso_sucursal_select($_SESSION['sucursales']);
      $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal($_SESSION['sucursales']);
      $ubica = $this->model_clientes->get_all_coordenadas("", $permiso_sucursal);
      $nodos = $this->model_selectores->get_all_nodos_by_usuario($permiso_sucursal_nodo);
      $sucursales = $this->model_selectores->get_all_sucursales_by_user($permiso_sucursal_select);
      $server = $this->model_clientes->get_all_coordenadas_server($permiso_sucursal_nodo);

      $data['data'] = $ubica;
      $data['nodos'] = $nodos;
      $data['permiso'] = $permiso;
      $data['server'] = $server;
      $data['sucursales'] = $sucursales;
      $data['geocercas'] = $geocercas;
      return view('clientes/mapa_clientes/mapa_geocercas', $data);
    }
  }
  public function geocercas2()
  {
    $permiso = $this->seguridad->Acceso_full('GEOCERCAS', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $geocercas = $this->model_clientes->get_all_geocercas();
      $permiso_sucursal_nodo = $this->permiso_sucursal->get_permiso_sucursal_nodo($_SESSION['sucursales']);
      $permiso_sucursal_select = $this->permiso_sucursal->get_permiso_sucursal_select($_SESSION['sucursales']);
      $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal($_SESSION['sucursales']);
      $ubica = $this->model_clientes->get_all_coordenadas("", $permiso_sucursal);
      $nodos = $this->model_selectores->get_all_nodos_by_usuario($permiso_sucursal_nodo);
      $sucursales = $this->model_selectores->get_all_sucursales_by_user($permiso_sucursal_select);
      $server = $this->model_clientes->get_all_coordenadas_server($permiso_sucursal_nodo);

      $data['data'] = $ubica;
      $data['nodos'] = $nodos;
      $data['permiso'] = $permiso;
      $data['server'] = $server;
      $data['sucursales'] = $sucursales;
      $data['geocercas'] = $geocercas;
      return view('clientes/mapa_clientes/mapa_geocercas2', $data);
    }
  }
  public function update_color_poligono()
  {
    $permiso = $this->seguridad->Acceso_void('GEOCERCAS', 'ESCRITURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getPost('id');
      $tipo = $this->request->getPost('tipo');
      $color = $this->request->getPost('color');
      $data = array();
      if ($tipo == "linea") {
        $data = ["color_linea" => $color];
      } else {
        $data = ["color_relleno" => $color];
      }
      $this->model_clientes->update_color_poligono($id, $data);
    }
  }

  public function array_sort($array, $on, $order = SORT_ASC)
  {
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
      foreach ($array as $k => $v) {
        if (is_array($v)) {
          foreach ($v as $k2 => $v2) {
            if ($k2 == $on) {
              $sortable_array[$k] = $v2;
            }
          }
        } else {
          $sortable_array[$k] = $v;
        }
      }

      switch ($order) {
        case SORT_ASC:
          asort($sortable_array);
          break;
        case SORT_DESC:
          arsort($sortable_array);
          break;
      }

      foreach ($sortable_array as $k => $v) {
        $new_array[$k] = $array[$k];
      }
    }

    return $new_array;
  }
}
