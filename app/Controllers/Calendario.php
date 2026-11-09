<?php namespace App\Controllers;
use App\Libraries\Class_seguridad;
use App\Libraries\Class_permiso_sucursal;
use App\Libraries\Ciqrcode;
use App\Models\Model_calendario;
use App\Models\Model_selectores;
use App\Models\Model_configuracion;
use App\Models\Model_log;

class Calendario extends BaseController {

  var $model_calendario;
  var $model_selectores;
  var $model_configuracion;
  var $model_log;
  var $permiso_sucursal;
  var $seguridad;
  var $nmodel_log;

		
   public function __construct(){
   $this->model_calendario=new Model_calendario();
   $this->model_selectores=new Model_selectores();
   $this->model_configuracion=new Model_configuracion();
   $this->model_log=new Model_log();
   $this->permiso_sucursal=new Class_permiso_sucursal();
   $this->seguridad = new Class_seguridad();
    session();
  }

	public function index()
	{		
		if( isset($_SESSION['id_usuario8291']) ){
			$seguridad = new Class_seguridad();
			 $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('AGENDA'));
		if( $tipoacceso > 0 ){

       
         $where_permiso_sucursal_select = $this->permiso_sucursal->get_permiso_sucursal_select($_SESSION['sucursales']);
         $data['sucursales'] =$this->model_selectores->get_all_sucursales_by_user($where_permiso_sucursal_select);
         $data['tipoacceso']=$tipoacceso;               
         echo view('calendario/index', $data);


		    }
             else echo acceso_denegado();
        }
        else $this->seguridad->login_back();
	}

  public function get_eventos(){

    if( isset($_SESSION['id_usuario8291']) ){
      $seguridad = new Class_seguridad();
       $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('AGENDA'));

    if( $tipoacceso > 0 ){
    $where_permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal($_SESSION['sucursales']);
    $where_permiso_sucursal_calendar = $this->permiso_sucursal->get_permiso_sucursal_calendar($_SESSION['sucursales']);
    $sucursal=$this->request->getGet("sucursal");
   

    date_default_timezone_set("America/Mexico_City");
    $events = array();
    $e = array();
    $seguridad = new Class_seguridad();
    
    $result=$this->model_calendario->get_eventos($sucursal,$where_permiso_sucursal_calendar);
   // $result_soporte=$this->model_calendario->get_soportes($sucursal,$where_permiso_sucursal);
   // $result_instalaciones=$this->model_calendario->get_instalaciones($sucursal,$where_permiso_sucursal);

    //print_r($result_instalaciones);

    foreach ($result as $fetch) {
    
    $e['id'] = $fetch->id;
    $e['id_cliente'] = $fetch->id_cliente;
    $e['nombre'] = $fetch->nombre;
    $e['title'] = $fetch->title;
    $e['tipo'] = $fetch->title;
    $e['tabla'] = "calendar";
    $e['descripcion'] = htmlspecialchars_decode($fetch->descripcion);
    $e['start'] = $fetch->startdate;
    $e['end'] = $fetch->enddate;
    $e['sucursal'] = $fetch->sucursal;
    $e['backgroundColor']=$fetch->color;
    $e['borderColor']=$fetch->color;
    $allday = ($fetch->color == "true") ? true : false;
    $e['allDay'] = $allday;

    array_push($events, $e);
  }

/*

   $color="";
   foreach ($result_soporte as $fetch) {
    if($fetch->fechavisita=="0000-00-00 00:00:00"){}
        else{
    if($fetch->color==""){$color="#0073b7";}else{$color=$fetch->color;}

    $e['id'] = $fetch->id;
    $e['id_cliente'] = $fetch->idcliente;
    $e['nombre'] = $fetch->nombre;
    $e['title'] = "Ticket:".$fetch->id." ".$fetch->asunto." ".$fetch->nombre;
    $e['tipo'] = $fetch->asunto;
    $e['tabla'] = "soporte";
    $e['sucursal'] = $fetch->sucursal;
    $result_respuestas=$this->model_calendario->get_tickets($fetch->id); 
    $descripcion="";
    if($result_respuestas){
      foreach ($result_respuestas as $items) {
        $descripcion.=$items->contenido;
         $descripcion.="<br>";
         $descripcion.="--------------------------------------------------------------";
         $descripcion.="<br>";
      }
    }
   
    $e['descripcion'] = htmlspecialchars_decode($descripcion);
    if($fetch->fechavisita=="0000-00-00 00:00:00"){$e['start'] = date("Y-m-d")." 00:00:00";}
    else{ $e['start'] = $fetch->fechavisita;}
   
  //  if($fetch->fechaendvisita=="0000-00-00 00:00:00"){$e['end'] = date("Y-m-d")." 00:00:00";}
    $e['end'] = $fetch->fechaendvisita;
    $e['backgroundColor']=$fetch->color_ticket;
    $e['borderColor']=$fetch->color_ticket;
    $e['allDay'] = false;

    array_push($events, $e);
    }
  }



  foreach ($result_instalaciones as $fetch) {
     if($fetch->fecha_instalacion=="0000-00-00 00:00:00"){}
    else{
     if($fetch->color==""){$color="#00a65a";}else{$color=$fetch->color;}

    $e['id'] = $fetch->id;
    $e['id_cliente'] = $fetch->userid;
    $e['nombre'] = $fetch->nombre;
    $e['title'] = "Intalacion:".$fetch->id." ".$fetch->nombre;
    $e['tipo'] = "Instalacion";
    $e['tabla'] = "instalaciones";
    $e['sucursal'] = $fetch->sucursal;
    $e['descripcion'] = htmlspecialchars_decode($fetch->descripcion_ins);
    if($fetch->fecha_instalacion=="0000-00-00 00:00:00"){$e['start'] = date("Y-m-d")." 00:00:00";}
    else{ $e['start'] = $fetch->fecha_instalacion;}
   
    $e['end'] = $fetch->fecha_instalacion_end;
    $e['backgroundColor']=$color;
    $e['borderColor']=$color;
    $e['allDay'] = false;

    array_push($events, $e);
    }
 
  } */




     echo json_encode($events);

   } else echo acceso_denegado();  
        }
        else $this->seguridad->login_back();
      
}

  public function get_tipos(){
    $events = array();
    $result=$this->model_calendario->get_tipos();

    foreach ($result as $fetch) {
    
    $e = array();
    $e['id'] = $fetch->id;
    $e['titulo'] = $fetch->titulo;
    $e['color'] = $fetch->color;

    array_push($events, $e);
  }
     echo '{"row":'.json_encode($events).'}';
      
}

 public function new_evento(){
 
    $title=$this->request->getPost("title");
    $startdate=$this->request->getPost("startdate").'+'.$this->request->getPost("zone");
    $color=$this->request->getPost("color");
    $idcliente=$this->request->getPost("id_cliente");
    $sucursal=$this->request->getPost("sucursal");

    $insert=$this->model_calendario->new_evento($title,$startdate,"","",$color,$idcliente,$sucursal);
    $ultimo_id=$this->model_calendario->get_ultimo_id();
    $row_evento=$this->model_calendario->get_evento_by_id($ultimo_id->ultimo);
    $evento=$row_evento->title;
    $insert_log=$this->model_log->insert_log($ultimo_id->ultimo,"calendario","Nuevo evento creado <b>#".$ultimo_id->ultimo." ".$evento."</b>",$_SESSION['id_usuario8291']);
    echo json_encode(array('status'=>'success','eventid'=>$ultimo_id->ultimo));
      
}

public function resize(){

      if( isset($_SESSION['id_usuario8291']) ){
    $seguridad = new Class_seguridad();
    $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('CALENDARIO_EDITAR_ELIMINAR_ESPECIAL'));
    if( $tipoacceso > 0 ){
    $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal_intalacion($_SESSION['sucursales']);
    $title=$this->request->getPost("title");
    $startdate=$this->request->getPost("start");
    $enddate=$this->request->getPost("end");
    $eventid=$this->request->getPost("eventid");
    $tabla=$this->request->getPost("tabla");
    $id_cliente=$this->request->getPost("id_cliente");
   

   // echo $startdate." ---".$enddate;



    
    if($tabla=="soporte"){$update=$this->model_calendario->update_soporte($startdate,$enddate,$eventid);}
    if($tabla=="calendar"){$update=$this->model_calendario->update_evento($startdate,$enddate,$eventid,$permiso_sucursal);}
    if($tabla=="instalaciones"){$update=$this->model_calendario->update_instalaciones($startdate,$enddate,$eventid);}
  //  if($tabla=="pagos"){$update=$this->model_calendario->update_pagos_egresos($startdate,$enddate,$eventid);}
    
   
    if($update){
    echo json_encode(array('status'=>'success')); 
    $evento="";
    if($tabla=="instalaciones"){$evento="Instalacion ".$title;} if($tabla=="soporte"){$evento="Ticket: ".$title;} else {$evento=$title;}

    //$row_evento=$this->model_calendario->get_evento_by_id($eventid);
    //$id_cliente=$row_evento->id_cliente;
    $insert_log=$this->model_log->insert_log($id_cliente,"calendario","Evento editado <b>#".$eventid." ".$evento."</b>",$_SESSION['id_usuario8291']);
   
  }
    else{
    echo json_encode(array('status'=>'failed'));}
       

        }else  echo json_encode(array('status'=>'NO TIENES PERMISOS PARA EDITAR'));
      }else echo acceso_denegado(); 
 }


public function update_title(){

      if( isset($_SESSION['id_usuario8291']) ){
    $seguridad = new Class_seguridad();
    $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('CALENDARIO_EDITAR_ELIMINAR_ESPECIAL'));
    if( $tipoacceso > 0 ){
     $title=$this->request->getPost("title");    
    $descripcion=$this->request->getPost("descripcion");
    $eventid=$this->request->getPost("eventid");
    $id_cliente=$this->request->getPost("id_cliente");
    $sucursal=$this->request->getPost("sucursal");
  //  $color=$this->request->getPost("color");
    $tabla=$this->request->getPost("tabla");   /////tabla soporte, no guardar descripcion, porque reemplaza todo el seguimiento
    if($tabla=="soporte"){
      $descripcion = str_replace("&amp;nbsp;", " ", $descripcion);
     $update=$this->model_calendario->update_descripcion_soporte($descripcion,$eventid,$id_cliente);
 }

    if($tabla=="calendar"){

        $update=$this->model_calendario->update_title($title,$descripcion,$eventid,$id_cliente,$sucursal);}

    if($tabla=="instalaciones"){
      $update=$this->model_calendario->update_descripcion_instalaciones($descripcion,$eventid,$id_cliente);
    }
  //  if($tabla=="pagos"){$update=$this->model_calendario->update_descripcion_pagos($descripcion,$eventid,$id_cliente);}
    

   
   
    if($update){
    echo json_encode(array('status'=>'success'));
    
    $insert_log=$this->model_log->insert_log($id_cliente,"calendario","Titulo del evento editado <b>#".$eventid."</b>",$_SESSION['id_usuario8291']);
  }
    else{
    echo json_encode(array('status'=>'failed'));}


       }else  echo json_encode(array('status'=>'NO TIENES PERMISOS PARA EDITAR'));
    }else echo acceso_denegado(); 
 
 }

 public function delete_evento(){

     if( isset($_SESSION['id_usuario8291']) ){
    $seguridad = new Class_seguridad();
    $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('CALENDARIO_EDITAR_ELIMINAR_ESPECIAL'));
    if( $tipoacceso > 0 ){
 
    $eventid=$this->request->getPost("eventid");
    $tabla=$this->request->getPost("tabla");
  
    if($tabla=="calendar"){

    $row_evento=$this->model_calendario->get_evento_by_id($eventid);
    $evento=$row_evento->title;
    $id_cliente=$row_evento->id_cliente;
    $delete=$this->model_calendario->delete_evento($eventid);
    $insert_log=$this->model_log->insert_log($id_cliente,"calendario","Evento eliminado <b>#".$eventid." ".$evento."</b>",$_SESSION['id_usuario8291']);
    if($delete){echo json_encode(array('status'=>'success'));}
    else{echo json_encode(array('status'=>'failed'));}

    }
    else{echo json_encode(array('status'=>'NO PUEDES ELIMINAR ESTOS EVENTOS'));}

       }else  echo json_encode(array('status'=>'NO TIENES PERMISOS PARA ELIMINAR'));
    }else echo acceso_denegado(); 
 }

 public function get_clientes_search(){
         $palabraclave=$this->request->getGet('term');
         $where = "(u.nombre LIKE '%$palabraclave%')"; 
         $all_items = $this->model_calendario->getAll_search($where);
         $array_cliente=array();  
         if($all_items){   
        foreach ($all_items as $items) {
        $array_cliente[]=array(
        "value"=>$items->nombre,
        "id"=>$items->id,
        "nombres"=>$items->nombre,
        "nodo"=>$items->nodo,
        "avatar"=>$items->avatar_cliente
          ); 
        //array_push($array_cliente, $array_item);
      }
     }  echo json_encode($array_cliente);
 }

 public function log(){

       if( isset($_SESSION['id_usuario8291']) ){
		    	$seguridad = new Class_seguridad();
			    $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LOG_CALENDARIO'));
	      	if( $tipoacceso > 0 ){
             $data['tipoacceso']=$tipoacceso;
             echo view('calendario/log', $data);
		      }
            else echo acceso_denegado();
        }
        else $this->seguridad->login_back();
 }

  public function get_log_calendario_json(){

    if( isset($_SESSION['id_usuario8291']) ){
    $seguridad = new Class_seguridad(); 
    $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LOG_CALENDARIO')); 
    $all_items = $this->model_calendario->get_log_calendario("calendario");

    $array_cliente=array();  
    if($all_items){   
      foreach ($all_items as $items) {
     
        $array_item=array(
        "id"=>$items->id,
        "log"=>$items->log,
        "username"=>$items->operador,
        "fechalog"=>$items->fechalog,
        "tipo"=>$items->tipo,  
        "tipoacceso"=>(int)$tipoacceso
          ); 
        array_push($array_cliente, $array_item);
      }
     }  echo '{"data":'. json_encode($array_cliente).'}';
    }
    else $this->seguridad->login_back();
 }

}