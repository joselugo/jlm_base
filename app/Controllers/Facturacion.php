<?php

namespace App\Controllers;

use App\Libraries\Class_seguridad;
use App\Libraries\Class_permiso_sucursal;
use App\Libraries\Class_permisos;
use App\Libraries\Ciqrcode;
use App\Models\Model_facturacion;
use App\Models\Model_selectores;
use App\Models\Model_reportes;
use App\Models\Model_configuracion;
use App\Models\Model_log;

class Facturacion extends BaseController
{

  var $model_facturacion;
  var $model_selectores;
  var $model_configuracion;
  var $model_log;
  var $permiso_nodo;
  var $seguridad;
  var $ciqrcode;
  var $nmodel_log;
  var $model_reportes;
  var $permiso_sucursal;

  public function __construct()
  {
    $this->model_facturacion = new Model_facturacion();
    $this->model_selectores = new Model_selectores();
    $this->model_configuracion = new Model_configuracion();
    $this->model_log = new Model_log();
    $this->permiso_nodo = new Class_permiso_sucursal();
    $this->permiso_sucursal = new Class_permiso_sucursal();
    $this->seguridad = new Class_seguridad();
    $this->model_reportes = new Model_reportes();
    session();
  }

  public function lista_razon_social()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_RAZON_SOCIAL'));
      if ($tipoacceso > 0) {
        $data['usuario'] = $_SESSION["usuario"];
        $data['mes'] = date("m");
        $data['tipoacceso'] = $tipoacceso;
        // El panel carga los módulos por AJAX: se devuelve solo el fragmento
        // (antes renderizaba el layout completo "inicio" sin este contenido)
        echo view('facturacion/lista_razon_social/index', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  protected function insert_fila_mes($id_lista_facturas)
  {

    $row = $this->model_facturacion->get_list_factura_by_id_lista_facturas($id_lista_facturas);
    $prefactura = "";
    $fecha_pago = "";
    $fecha_compromiso = "";
    $fecha_envio = "";
    $monto = 0;
    if ($row) {
      $prefactura = $row->prefactura;
      $fecha_envio = $row->fecha_envio;
      $fecha_pago = $row->fecha_pago;
      $fecha_compromiso = $row->fecha_compromiso;
      $monto = $row->monto;
    }

    $arrayName2 = array(
      'id_lista_facturas' => $id_lista_facturas, 'prefactura' => $prefactura, 'estatus' => 'No facturado',
      'monto' => $monto, 'fecha_envio' => $fecha_envio, 'fecha_pago' => $fecha_pago,
      'fecha_compromiso' => $fecha_compromiso,  'folio' => '', 'comentarios' => '',
      'mes' => date('m'), 'anio' => date('Y')
    );

    $new = $this->model_facturacion->new_lista_factura_por_mes($arrayName2);
  }



  public function get_lista_facturas_json()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_RAZON_SOCIAL'));
      if ($tipoacceso > 0) {

        $mes_hoy = date("m");
        $anio_hoy = date("Y");
        $all_items_short = $this->model_facturacion->get_lista_facturas_short();
        foreach ($all_items_short as $items) {
          $existe = $this->model_facturacion->consulta_existe($mes_hoy, $anio_hoy, $items->id_lista_facturas);
          if ($existe) {
          } else {
            $this->insert_fila_mes($items->id_lista_facturas);
          }
        }


        $mes = $this->request->getGet("mes");
        $anio = $this->request->getGet("anio");

        $all_items = $this->model_facturacion->get_lista_facturas($mes, $anio);
        $array_cliente = array();
        if ($all_items) {
          $prefactura = "";
          $fecha_envio = "";
          $fecha_compromiso = "";
          foreach ($all_items as $items) {

            ($items->prefactura == 0) ? $prefactura = '<span class="label label-danger">NO REQUIERE</span>' : $prefactura = '<span class="label label-success">SI REQUIERE</span>';

            if ($items->fecha_envio == "") {
            } else {
              $fecha_envio = date("d/m/Y", strtotime($items->fecha_envio));
            }
            if ($items->fecha_compromiso == "") {
            } else {
              $fecha_compromiso = date("d/m/Y", strtotime($items->fecha_compromiso));
            }

            $array_item = array(
              "id" => $items->id,
              "id_lista_facturas" => $items->id_lista_facturas,
              "nombre" => $items->nombre,
              "prefactura" => $prefactura,
              "fecha_envio" => $fecha_envio,
              "fecha_pago" => date("d/m/Y", strtotime($items->fecha_pago)),
              "fecha_compromiso" => $fecha_compromiso,
              "folio" => $items->folio,
              "monto" => $items->monto,
              "estatus" => '<span class="label label-primary">' . strtoupper($items->estatus) . '</span>',
              "tipoacceso" => $tipoacceso
            );
            array_push($array_cliente, $array_item);
            $fecha_envio = "";
            $fecha_compromiso = "";
          }
        }
        echo '{"data":' . json_encode($array_cliente) . '}';
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function update_list_by_id()
  {

    $id = $this->request->getPost('id');
    $prefactura = $this->request->getPost('prefactura');
    $fecha_envio = $this->request->getPost('fecha_envio');
    $fecha_pago = $this->request->getPost('fecha_pago');
    $fecha_compromiso = $this->request->getPost('fecha_compromiso');
    $folio = $this->request->getPost('folio');
    $monto = $this->request->getPost('monto');
    $estatus = $this->request->getPost('estatus');
    $comentarios = $this->request->getPost('comentarios');


    $arrayName = array(
      'prefactura' => $prefactura, 'estatus' => $estatus,
      'monto' => $monto, 'fecha_envio' => $fecha_envio, 'fecha_pago' => $fecha_pago,
      'fecha_compromiso' => $fecha_compromiso, 'folio' => $folio, 'comentarios' => $comentarios
    );




    $update = $this->model_facturacion->update_list_by_id($id, $arrayName);

    if ($update)
      echo json_encode(array('status' => 'success'));
    else
      echo json_encode(array('status' => 'failed'));
  }

  public function update_fiscales()
  {

    $id_lista_facturas = $this->request->getPost('id_lista_facturas');
    $razon_social = $this->request->getPost('razon_social');
    $rfc = $this->request->getPost('rfc');
    $domicilio_fiscal = $this->request->getPost('domicilio_fiscal');
    $cp = $this->request->getPost('cp');
    $telefono = $this->request->getPost('telefono');
    $correo = $this->request->getPost('correo');


    $arrayName = array(
      'razon_social' => $razon_social, 'rfc' => $rfc,
      'domicilio_fiscal' => $domicilio_fiscal, 'cp' => $cp, 'telefono' => $telefono,
      'correo' => $correo
    );



    $update = $this->model_facturacion->update_fiscales($id_lista_facturas, $arrayName);

    if ($update)
      echo json_encode(array('status' => 'success'));
    else
      echo json_encode(array('status' => 'failed'));
  }

  public function new_fiscales()
  {

    $id_cliente = $this->request->getPost('id_cliente');
    $razon_social = $this->request->getPost('razon_social');
    $rfc = $this->request->getPost('rfc');
    $domicilio_fiscal = $this->request->getPost('domicilio_fiscal');
    $cp = $this->request->getPost('cp');
    $telefono = $this->request->getPost('telefono');
    $correo = $this->request->getPost('correo');


    $arrayName = array(
      'id_cliente' => $id_cliente, 'razon_social' => $razon_social, 'rfc' => $rfc,
      'domicilio_fiscal' => $domicilio_fiscal, 'cp' => $cp, 'telefono' => $telefono,
      'correo' => $correo, 'activo' => 0
    );


    $new = $this->model_facturacion->new_fiscales($arrayName);
    $id_lista_facturas = $this->model_facturacion->get_ultimo_id();

    $arrayName2 = array(
      'id_lista_facturas' => $id_lista_facturas, 'prefactura' => 0, 'estatus' => 'No facturado',
      'monto' => 0, 'fecha_envio' => '', 'fecha_pago' => date('Y-m-d'),
      'fecha_compromiso' => '', 'comentarios' => '',
      'mes' => date('m'), 'anio' => date('Y')
    );

    $new = $this->model_facturacion->new_lista_factura_por_mes($arrayName2);

    if ($new)
      echo json_encode(array('status' => 'success'));
    else
      echo json_encode(array('status' => 'failed'));
  }

  public function get_list_factura_by_id()
  {

    $id = $this->request->getPost('id');

    $row = $this->model_facturacion->get_list_factura_by_id($id);
    $arrayName = array(
      'id' => $row->id, 'prefactura' => $row->prefactura, 'estatus' => $row->estatus,
      'monto' => $row->monto, 'fecha_envio' => $row->fecha_envio, 'fecha_pago' => $row->fecha_pago,
      'fecha_compromiso' => $row->fecha_compromiso, 'folio' => $row->folio, 'comentarios' => $row->comentarios
    );

    echo json_encode($arrayName);
  }

  public function get_datos_fiscales()
  {

    $id_lista_facturas = $this->request->getPost('id_lista_facturas');

    $row = $this->model_facturacion->get_datos_fiscales($id_lista_facturas);
    $arrayName = array(
      'id_lista_facturas' => $row->id_lista_facturas, 'razon_social' => $row->razon_social, 'rfc' => $row->rfc,
      'domicilio_fiscal' => $row->domicilio_fiscal, 'cp' => $row->cp, 'telefono' => $row->telefono,
      'correo' => $row->correo
    );

    echo json_encode($arrayName);
  }

  public function delete_cliente()
  {

    $id_lista_facturas = $this->request->getPost('id_lista_facturas');

    $delete_cliente = $this->model_facturacion->delete_cliente($id_lista_facturas);


    if ($delete_cliente)
      echo json_encode(array('status' => 'success'));
    else
      echo json_encode(array('status' => 'failed'));
  }

  public function ingresos()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $seguridad1 = new Class_permisos;

      $where_permiso_sucursal_select = $this->permiso_sucursal->get_permiso_sucursal_select($_SESSION['sucursales']); 
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('INGRESOS'));
      if ($tipoacceso > 0) {

        //session_destroy();
        $permiso_select_tipo_gasto = $seguridad1->verificar_permisos($_SESSION['id_usuario8291'], $seguridad->clave_permiso('INGRESOS_TIPO_GASTO_VIEW'));
        if ($permiso_select_tipo_gasto) {
          $data['gastos_tipo'] = $this->model_reportes->get_gastos_tipo();
        } else {
          $data['gastos_tipo'] = $this->model_reportes->get_gastos_tipo_restricted();
        }
        $data['sucursales']=$this->model_selectores->get_all_sucursales_by_user($where_permiso_sucursal_select);
        $data['login'] = $this->model_reportes->get_all_login();
        $data['tipoacceso'] = $tipoacceso;

        echo view('facturacion/ingresos/index', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

   public function egresos()
  {   
      if( isset($_SESSION['id_usuario8291']) ){
         $seguridad = new Class_seguridad();
         $seguridad1 = new Class_permisos;
         $where_permiso_sucursal_select = $this->permiso_sucursal->get_permiso_sucursal_select($_SESSION['sucursales']); 
         $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('INGRESOS'));
      if( $tipoacceso > 0 ){
              
                 //session_destroy();
              $permiso_select_tipo_gasto = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('INGRESOS_EGRESOS_VIEW_ESPECIAL')); 
              if($permiso_select_tipo_gasto>0){$data['gastos_tipo']=$this->model_reportes->get_gastos_tipo();}
              else{ $data['gastos_tipo']=$this->model_reportes->get_gastos_tipo_restricted();}
              $data['login']=$this->model_reportes->get_all_login();
               $data['tipoacceso']=$tipoacceso;
               $data['sucursales']=$this->model_selectores->get_all_sucursales_by_user($where_permiso_sucursal_select);
             
              echo view('facturacion/egresos/index', $data);
        }
        else echo acceso_denegado();  
    }
    else $this->seguridad->login_back();
  }

  public function get_all_ingresos()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $seguridad1 = new Class_permisos;
      // $permiso_sucursal = new Class_permiso_sucursal();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('INGRESOS'));
      if ($tipoacceso > 0) {

        $all_ingresos = $this->model_facturacion->get_all_ingresos();
        $tool = "";
        $array_ingresos = array();
        if ($all_ingresos) {
          foreach ($all_ingresos as $items) {

            if ($tipoacceso == 1) {
              $tool = '<td class=" tool-documentos text-center">
             <a data-toggle="tooltip" title="Ver reporte" class="btn btn-default btn-icon btn-sm" onclick="update_ingreso(\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\')"><i class="far fa-edit" aria-hidden="true"></i></a></td';
           
            }
            if ($tipoacceso == 2) {
              $tool = '<td class=" tool-documentos text-center">
             <a data-toggle="tooltip" title="Ver reporte" class="btn btn-default btn-icon btn-sm" onclick="update_ingreso(\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\')"><i class="far fa-edit" aria-hidden="true"></i></a></td';
            }

            if ($tipoacceso == 3) {
              $tool = '<td class=" tools-documentos text-center">
             <a data-toggle="tooltip" title="Ver reporte" class="btn btn-default btn-icon btn-sm" onclick="update_ingreso(\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\')"><i class="far fa-edit" aria-hidden="true"></i></a>
            <a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_ingresos(\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a></td>';
            }

            $array_item = array(
              "id" => $items->id,
              "fecha" => date("d/m/Y", strtotime($items->fecha)),
              "monto" => $items->monto,
              "username" => $items->nombre,
              "referencia" => $items->referencia,
              "descripcion" => $items->descripcion,
              "tool" => $tool

            );
            array_push($array_ingresos, $array_item);
          }
        }
        echo '{"data":' . json_encode($array_ingresos) . '}';
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function get_all_egresos()
    { 

      if( isset($_SESSION['id_usuario8291']) ){
       $seguridad = new Class_seguridad();
        $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('INGRESOS'));
        $tipoacceso1 = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('INGRESOS_EGRESOS_VIEW_ESPECIAL')); //permiso especial para ver pagos en el calendario
      if($tipoacceso1>0){$all_egresos = $this->model_facturacion->get_all_egresos();}
      else {$all_egresos = $this->model_facturacion->get_all_egresos_restricted(); }
      
    
        $array_egresos=array();  
        if($all_egresos){
        $estatus=""; $entregado=""; $tool=""; 
        foreach ($all_egresos as $items) {
         if($items->estatus==0){ $estatus='<span class="label label-danger">SIN COMPROBANTE</span>';}
         else if($items->estatus==1){ $estatus='<span class="label label-warning">SIN VALIDAR</span>';}
         else { $estatus='<span class="label label-success">VALIDADO</span>';}

          
            if ($tipoacceso == 1) {
              $tool = '<td class=" tool-documentos text-center">
             <a data-toggle="tooltip" title="Ver reporte" class="btn btn-default btn-icon btn-sm" onclick="update_ingreso(\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\')"><i class="far fa-edit" aria-hidden="true"></i></a></td';
           
            }
             if($tipoacceso==2){ $tool='<td class=" tool-documentos text-center">
             <a data-toggle="tooltip" title="Ver reporte" class="btn btn-default btn-icon btn-sm" onclick="update_egreso(\''.$items->id.'\',\''.$_SESSION['token8291'].'\')"><i class="far fa-edit" aria-hidden="true"></i></a></td';}

             if($tipoacceso==3){ $tool='<td class=" tools-documentos text-center">
             <a data-toggle="tooltip" title="Ver reporte" class="btn btn-default btn-icon btn-sm" onclick="update_egreso(\''.$items->id.'\',\''.$_SESSION['token8291'].'\')"><i class="far fa-edit" aria-hidden="true"></i></a>
            <a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_ingresos(\''.$items->id.'\',\''.$_SESSION['token8291'].'\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a></td>';}

        $row_user= $this->model_facturacion->get_name_user($items->to_login);
        if($row_user){$entregado=$row_user->nombre;}
        else{$entregado="No existe el usuario";}
        $array_item=array(
        "id"=>$items->id,
        "fecha"=>date("d/m/Y",strtotime($items->fecha)),
        "monto"=>$items->monto,
        "username"=>$items->nombre,
        "referencia"=>$items->referencia,
        "descripcion"=>$items->descripcion,
        "tipo"=>$items->tipo,
        "estatus"=>$estatus,
        "entregado"=>$entregado,
        "tool"=>$tool

          ); 
        array_push($array_egresos, $array_item);
      }
    }  echo '{"data":'. json_encode($array_egresos).'}'; 


   }
    else $this->seguridad->login_back();
                  
 }

  public function get_egreso_by_id(){

  if( isset($_SESSION['id_usuario8291']) ){
         $seguridad = new Class_seguridad();
         $seguridad1 = new Class_permisos;
        // $permiso_sucursal = new Class_permiso_nodo();
         $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('INGRESOS'));
      if( $tipoacceso > 0 ){

          $id_egreso=$this->request->getPost('id');
          $items = $this->model_facturacion->get_egreso_by_id($id_egreso);
          $array = array('descripcion'=>$items->descripcion, 'fecha'=>$items->fecha,'monto'=>$items->monto,'referencia'=>$items->referencia, 'tipo_gasto'=>$items->tipo_gasto,
            'estatus'=>$items->estatus,'to_login'=>$items->to_login,'nota'=>$items->nota,'sucursal'=>$items->idsucursal);
         echo json_encode($array);
              
        }
        else echo acceso_denegado();  
    }
    else $this->seguridad->login_back();

 }

  public function get_ingreso_by_id(){

  if( isset($_SESSION['id_usuario8291']) ){
         $seguridad = new Class_seguridad();
         $seguridad1 = new Class_permisos;
        // $permiso_sucursal = new Class_permiso_nodo();
         $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('INGRESOS'));
      if( $tipoacceso > 0 ){

          $id_egreso=$this->request->getPost('id');
          $items = $this->model_facturacion->get_ingreso_by_id($id_egreso);
          $array = array('descripcion'=>$items->descripcion, 'fecha'=>$items->fecha,'monto'=>$items->monto,'referencia'=>$items->referencia, 'tipo_gasto'=>$items->tipo_gasto,
                  'estatus'=>$items->estatus,'to_login'=>$items->to_login,'nota'=>$items->nota,'sucursal'=>$items->idsucursal);
         echo json_encode($array);
              
        }
        else echo acceso_denegado();  
    }
    else $this->seguridad->login_back();

 }

public function new_ingreso(){

 if( isset($_SESSION['id_usuario8291']) ){
         $seguridad = new Class_seguridad();
         $seguridad1 = new Class_permisos;
        // $permiso_sucursal = new Class_permiso_nodo();
         $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('INGRESOS'));
      if( $tipoacceso > 0 ){
  
  $sucursal=$this->request->getPost('sucursal');
  $gastos_tipo=$this->request->getPost('gastos_tipo');
  $fecha=$this->request->getPost('fecha-in');
  $monto=$this->request->getPost('monto');
  $referencia=$this->request->getPost('referencia');
  $descripcion=$this->request->getPost('descripcion');

 $arrayName = array('descripcion'=>$descripcion, 'fecha' => $fecha, 'tipo' => 'ingreso',
                     'monto' => $monto, 'idadmin' => $_SESSION['id_usuario8291'], 'referencia' => $referencia, 
                     'idnodo' => 1, 'tipo_gasto' => 0, 'idsucursal' => $sucursal);

 $new=$this->model_facturacion->new_ingreso_egreso($arrayName);
 if($new==false){echo "error";}
 else{echo "success";}
              
        }
        else echo acceso_denegado();  
    }
    else $this->seguridad->login_back();
 
   
 }

   public function new_egreso(){

 if( isset($_SESSION['id_usuario8291']) ){
         $seguridad = new Class_seguridad();
         $seguridad1 = new Class_permisos;
        // $permiso_sucursal = new Class_permiso_nodo();
         $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('INGRESOS'));
      if( $tipoacceso > 0 ){
  $sucursal=$this->request->getPost('sucursal');
  $gastos_tipo=$this->request->getPost('gastos_tipo');
  $fecha=$this->request->getPost('fecha');
  $monto=$this->request->getPost('monto');
  $referencia=$this->request->getPost('referencia');
  $descripcion=$this->request->getPost('descripcion');
  $tologin=$this->request->getPost('tologin');
 
 $arrayName = array('descripcion'=>$descripcion, 'fecha' => $fecha, 'tipo' => 'egreso',
                     'monto' => $monto, 'idadmin' => $_SESSION['id_usuario8291'], 'referencia' => $referencia, 
                     'idnodo' => 1, 'tipo_gasto' => $gastos_tipo,'to_login' => $tologin,'idsucursal' => $sucursal
                    );

 $new=$this->model_facturacion->new_ingreso_egreso($arrayName);
 $id_gasto=$this->model_facturacion->get_ultimo_id_gasto();
 $insert_log=$this->model_log->insert_log("000000","ingresos","Nuevo egreso <b>#".$id_gasto." Ref:".$referencia." Fecha:".$fecha." Monto:".$monto."</b>",$_SESSION['id_usuario8291']);
 if($new==false){echo "error";}

              
        }
        else echo acceso_denegado();  
    }
    else $this->seguridad->login_back();

   
 }


  public function update_ingreso(){
 
  if( isset($_SESSION['id_usuario8291']) ){
         $seguridad = new Class_seguridad();
         $seguridad1 = new Class_permisos;
        // $permiso_sucursal = new Class_permiso_nodo();
         $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('INGRESOS'));
      if( $tipoacceso > 0 ){

          $id_ingreso=$this->request->getPost('id_ingreso_modal');
          $fecha=$this->request->getPost('fecha-in');
          $monto=$this->request->getPost('monto');
          $referencia=$this->request->getPost('referencia');
          $descripcion=$this->request->getPost('descripcion');
          $sucursal=$this->request->getPost('sucursal');

          $items = $this->model_facturacion->get_ingreso_by_id($id_ingreso);

          $arrayName = array('descripcion'=>$descripcion, 'fecha' => $fecha,
                     'monto' => $monto, 'referencia' => $referencia,'idsucursal' => $sucursal
                    );
         $insert_log=$this->model_log->insert_log("000000","ingresos","Ingreso editado <b>#".$id_ingreso." ".$referencia." -Monto anterior:".$items->monto." -Monto actual:".$monto."</b>",$_SESSION['id_usuario8291']);
         $update=$this->model_facturacion->update_ingreso($id_ingreso,$arrayName);
         if($update==false){echo "error";}



              
        }
        else echo acceso_denegado();  
    }
    else $this->seguridad->login_back();

   
 }


 public function update_egreso(){
 
  if( isset($_SESSION['id_usuario8291']) ){
         $seguridad = new Class_seguridad();
         $seguridad1 = new Class_permisos;
        // $permiso_sucursal = new Class_permiso_nodo();
         $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('INGRESOS'));
      if( $tipoacceso > 0 ){
  $action=$this->request->getPost('tipo');
  $id_egreso=$this->request->getPost('id_egreso_modal');
  $gastos_tipo=$this->request->getPost('gastos_tipo2');
  $sucursal=$this->request->getPost('sucursal');
  $fecha=$this->request->getPost('fecha1');
  $monto=$this->request->getPost('monto1');
  $referencia=$this->request->getPost('referencia1');
  $descripcion=$this->request->getPost('descripcion1');
  $tologin=$this->request->getPost('tologin1');
  $estatus=$this->request->getPost('estatus1');
 

 $arrayName = array('descripcion'=>$descripcion, 'fecha' => $fecha,
                     'monto' => $monto, 'referencia' => $referencia, 
                     'tipo_gasto' => $gastos_tipo,  'estatus' => $estatus, 'to_login' => $tologin,'idsucursal' => $sucursal
                    );
 $items = $this->model_facturacion->get_egreso_by_id($id_egreso);
 $insert_log=$this->model_log->insert_log("000000","egresos","Egreso editado <b>#".$id_egreso." ".$referencia." -Monto anterior:".$items->monto." -Monto actual:".$monto."</b>",$_SESSION['nombre']);
 $update=$this->model_facturacion->update_egreso($id_egreso,$arrayName);
 //$insert_log=$this->model_log->insert("ingresos","Egreso editado <b>#".$id_egreso." ".$referencia." Monto:".$monto."</b>",$_SESSION['nombre']);
 if($update==false){echo "error";}

          
        }
        else echo acceso_denegado();  
    }
    else $this->seguridad->login_back();

   
 }

 

  public function delete_ingresos(){
 
  if( isset($_SESSION['id_usuario8291']) ){
         $seguridad = new Class_seguridad();
         $seguridad1 = new Class_permisos;
        // $permiso_sucursal = new Class_permiso_nodo();
         $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('INGRESOS'));
      if( $tipoacceso > 0 ){

         $id_gasto=$this->request->getPost('id');
         $row_gasto=$this->model_facturacion->get_ingreso_by_id($id_gasto);
         $insert_log=$this->model_log->insert_log("000000","ingresos","Egreso-ingreso eliminado <b>#".$id_gasto." Ref:".$row_gasto->referencia." Monto:".$row_gasto->monto."</b>",$_SESSION['id_usuario8291']);
         $delete_gasto=$this->model_facturacion->delete_gasto($id_gasto);

   
           if($delete_gasto)
            echo "success";
           else
           echo "error";
              
        }
        else echo acceso_denegado();  
    }
    else $this->seguridad->login_back();

 }
}
