<?php namespace App\Controllers;
use App\Libraries\Class_seguridad;
use App\Libraries\Class_permiso_sucursal;
use App\Libraries\Ciqrcode;
use App\Libraries\CotizacionPdf;
use App\Models\Model_cotizaciones;
use App\Models\Model_selectores;
use App\Models\Model_configuracion;
use App\Models\Model_log;
use App\Models\Model_parametros_sistema;
class Cotizaciones extends BaseController {

  var $correo_user;var $zona_horaria;
   var $model_cotizaciones;
  var $model_selectores;
  var $model_configuracion;
  var $model_log;
  var $permiso_sucursal;
  var $seguridad;
  var $ciqrcode;
  var $nmodel_log;
  var $model_parametros_sistema;
	public function __construct(){
	 $this->model_cotizaciones=new Model_cotizaciones();
   $this->model_selectores=new Model_selectores();
   $this->model_configuracion=new Model_configuracion();
   $this->model_log=new Model_log();
   $this->permiso_nodo=new Class_permiso_sucursal();
   $this->seguridad = new Class_seguridad();
   $this->model_parametros_sistema= new Model_parametros_sistema();
    session();
	}

	public function index(){	

    
		if( isset($_SESSION['id_usuario8291']) ){
			   $seguridad = new Class_seguridad();
			   $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_COTIZACIONES'));
			if( $tipoacceso > 0 ){
                 $data['usuario'] = $_SESSION["nombre"];
                 $data['tipoacceso']=$tipoacceso;				
			           echo view('cotizaciones/index', $data);
		    }
        else echo acceso_denegado();  
		}
		else (new Class_seguridad())->login_back();
	}

    public function nuevo(){  

    
    if( isset($_SESSION['id_usuario8291']) ){
         $seguridad = new Class_seguridad();
         $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_COTIZACIONES'));
         $permiso_sucursal = new Class_permiso_sucursal();
         $where_permiso_sucursal_select = $permiso_sucursal->get_permiso_sucursal_select($_SESSION['sucursales']); 
      if( $tipoacceso > 0 ){
                 $data['usuario'] = $_SESSION["nombre"];
                 $data['sucursales']=$this->model_selectores->get_all_sucursales_by_user($where_permiso_sucursal_select);
                 $data['tipoacceso']=$tipoacceso;       
                 echo view('cotizaciones/nuevo', $data);
        }
        else echo acceso_denegado();  
    }
    else (new Class_seguridad())->login_back();
  }

  public function editar(){  

    
    if( isset($_SESSION['id_usuario8291']) ){
         $seguridad = new Class_seguridad();
         $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_COTIZACIONES'));
          $permiso_sucursal = new Class_permiso_sucursal();
          $where_permiso_sucursal = $permiso_sucursal->get_permiso_sucursal_cotizaciones($_SESSION['sucursales']);
          $where_permiso_sucursal_select = $permiso_sucursal->get_permiso_sucursal_select($_SESSION['sucursales']); 
      if( $tipoacceso > 0 ){
                 $true_cot_permiso=$this->model_cotizaciones->check_permiso_cot($where_permiso_sucursal,$this->request->getGet('id'));
                 if($true_cot_permiso){

                 $data['id_cot']=$this->request->getGet('id');
                 $data['sucursales']=$this->model_selectores->get_all_sucursales_by_user($where_permiso_sucursal_select);
                 $data['usuario'] = $_SESSION["nombre"];
                 $data['tipoacceso']=$tipoacceso;       
                 echo view('cotizaciones/editar', $data);

                 }else{

                  echo "NO TIENES PERMISOS PARA VISUALIZAR ESTA COTIZACION";

                 }

                
        }
        else echo acceso_denegado();  
    }
    else (new Class_seguridad())->login_back();
  }


   public function get_cotizaciones_json(){

    if( isset($_SESSION['id_usuario8291']) ){
         $seguridad = new Class_seguridad();
         $permiso_nodo = new Class_permiso_sucursal();
         $where_permiso_sucursal = $permiso_nodo->get_permiso_sucursal_cotizaciones($_SESSION['sucursales']); 
         $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_COTIZACIONES')); 
         $estado=$this->request->getGet('estado');
    $all_items = $this->model_cotizaciones->getAll($where_permiso_sucursal,$estado);
    $array_cliente=array();  
    if($all_items){   
      $estado="";$status="0";$es_cliente=""; $nombre="";$telefono="";$correo="";
     // print_r($all_items);
      foreach ($all_items as $items) {
     
        if($items->estado==0) {$estado='<span class="label label-primary">NUEVO</span>'; } 
        if($items->estado==1) {$estado='<span class="label label-success">ACEPTADO</span>';}
        if($items->estado==2) {$estado='<span class="label label-warning">ANULADO</span>';}

        if($items->status==0) {$status='<span class="label label-primary">ACTIVO</span>'; } 
        if($items->status==1) {$status='<span class="label label-danger">VENCIDO</span>';}

        if($items->idcliente>0) {

          $es_cliente='<span class="label label-primary">SI</span>'; 
          $row=$this->model_cotizaciones->getAll_cliente_cot($items->idcliente);
          if($row){ 
            $nombre=$row->nombre;
            $telefono=$row->telefono;
            $correo=$row->correo; }
          else{
            $nombre="";
            $telefono="";
            $correo=""; }
        } 
        if($items->idcliente==0) {$es_cliente='<span class="label label-danger">NO</span>'; $nombre=$items->nombres;$telefono=$items->telefono;$correo=$items->correo;}
 

        $array_item=array(
        "id"=>$items->id_cotizacion,
        "nombre"=>$nombre,
        "cliente"=>$es_cliente,
        "telefono"=>$telefono,
        "correo"=>$correo,
        "total"=>'$ '.number_format($items->total,2),
        "impuesto"=>'<span class="label label-default">'.$items->impuesto.'</span>',
        "estado"=>$estado,
        "estado1"=>$items->estado,
        "status"=>$status,
        "nfactura"=>$items->nfactura,
        "fecha_cot"=>date_format(date_create($items->fecha_cot), 'd/m/Y'),
        "fecha_ven"=>date_format(date_create($items->fecha_ven), 'd/m/Y'),
        "tipoacceso"=>$tipoacceso
          ); 
        array_push($array_cliente, $array_item);
      }
     }  echo '{"data":'. json_encode($array_cliente).'}';
    }
    else (new Class_seguridad())->login_back();
 }

  public function get_all_productos_json(){

    if( isset($_SESSION['id_usuario8291']) ){
         $seguridad = new Class_seguridad();
       
      
    $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_COTIZACIONES')); 
    $all_items = $this->model_cotizaciones->get_all_productos();
    $all_planes = $this->model_cotizaciones->get_all_planes();
    

    $array_cliente=array();  
   if($all_items){   
      foreach ($all_items as $items) {
     
        $array_item=array(
        "id"=>$items->id,
        "producto"=>substr($items->producto,0,51),
        "descripcion"=>substr($items->descripcion,0,51),
        "costo"=>$items->costo,
        "unidad"=>$items->medida,
         "tipo"=>"producto",    
        "tipoacceso"=>$tipoacceso
          ); 
        array_push($array_cliente, $array_item);
      }
    } 

      if($all_planes){   
      foreach ($all_planes as $items) {
     
        $array_item=array(
        "id"=>$items->id,
        "producto"=>substr($items->plan,0,50),
        "descripcion"=>substr("Servicio de internet, velocidad - subida/descarga: ". $items->velocidad,0,65),
        "costo"=>$items->costo,
        "unidad"=>"servicio",
        "tipo"=>"plan",   
        "tipoacceso"=>$tipoacceso
          ); 
        array_push($array_cliente, $array_item);
      }
     }    

     echo '{"data":'. json_encode($array_cliente).'}';
    }
    else (new Class_seguridad())->login_back();
 }

 public function addCarrito(){      
         if( isset($_SESSION['id_usuario8291']) ){
          $seguridad = new Class_seguridad();
            $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_COTIZACIONES'));
            if( $tipoacceso > 0 ){
             
              $id_producto=$this->request->getPost('id_producto');
              $tipo=$this->request->getPost('tipo');

         
              if($tipo=="producto"){ $listaCarrito = $this->model_cotizaciones->get_all_productos_by_id($id_producto);}
              else{ $listaCarrito = $this->model_cotizaciones->get_all_planes_by_id($id_producto);}
           //   $listaCarrito = $this->model_cotizaciones->get_all_productos_by_id($id_producto);
           //   $listaCarrito = $this->model_cotizaciones->get_all_servicios_by_id($id_producto);


              $nuevoProducto=array();
              $subtotal=0;$iva=0;$total=0;
            
                         
                         if(isset($_SESSION['carrito'.$_SESSION['id_usuario8291']])){
                          $contador=$_SESSION['contador']+1;

                          if($tipo=="producto"){

                          foreach ($listaCarrito as $items) {
                          
                          $nuevoProducto['id'] = $contador;
                          $nuevoProducto['idproducto'] = $items->id;
                          $nuevoProducto['producto'] = $items->producto;
                          $nuevoProducto['descripcion'] = $items->descripcion;
                          $nuevoProducto['unitario'] = $items->costo;
                          $nuevoProducto['cantidad'] = 1;
                          $nuevoProducto['unidad'] = $items->medida;
                          $nuevoProducto['subtotal'] = $items->costo; 
                       
                         }

                        }
                        else {
                           foreach ($listaCarrito as $items) {
                          
                          $nuevoProducto['id'] = $contador;
                          $nuevoProducto['idproducto'] = $items->id;
                          $nuevoProducto['producto'] = $items->plan;
                          $nuevoProducto['descripcion'] = "Servicio de internet de banda ancha, velocidad - subida/descarga: ".$items->velocidad;
                          $nuevoProducto['unitario'] = $items->costo;
                          $nuevoProducto['cantidad'] = 1;
                          $nuevoProducto['unidad'] = "servicio";
                          $nuevoProducto['subtotal'] = $items->costo; 
                           
                         }
                      }
                         
                             
                              array_push($_SESSION['carrito'.$_SESSION['id_usuario8291']], $nuevoProducto);
                              $_SESSION['contador']=$contador;
    
                          }
                          else {
                             $_SESSION['carrito'.$_SESSION['id_usuario8291']]=array();
                             $_SESSION['contador']=1;
                             $contador=$_SESSION['contador']; 
                         
                          if($tipo=="producto"){

                          foreach ($listaCarrito as $items) {
                          
                          $nuevoProducto['id'] = $contador;
                          $nuevoProducto['idproducto'] = $items->id;
                          $nuevoProducto['producto'] = $items->producto;
                          $nuevoProducto['descripcion'] = $items->descripcion;
                          $nuevoProducto['unitario'] = $items->costo;
                          $nuevoProducto['cantidad'] = 1;
                          $nuevoProducto['unidad'] = $items->medida;
                          $nuevoProducto['subtotal'] = $items->costo; 
                           
                         }

                        }
                        else {
                           foreach ($listaCarrito as $items) {
                          
                          $nuevoProducto['id'] = $contador;
                          $nuevoProducto['idproducto'] = $items->id;
                          $nuevoProducto['producto'] = $items->plan;
                          $nuevoProducto['descripcion'] = "Servicio de internet de banda ancha, velocidad - subida/descarga: ".$items->velocidad;
                          $nuevoProducto['unitario'] = $items->costo;
                          $nuevoProducto['cantidad'] = 1;
                          $nuevoProducto['unidad'] = "servicio";
                          $nuevoProducto['subtotal'] = $items->costo; 
                           
                         }
                      }
                         
                             
                         array_push($_SESSION['carrito'.$_SESSION['id_usuario8291']], $nuevoProducto);
                         print_r($_SESSION['carrito'.$_SESSION['id_usuario8291']]);
   
                    }


    }else echo acceso_denegado();  
    }else echo acceso_denegado(); 
 
      
  }

  public function addCarrito_otro_producto()
  {
         if( isset($_SESSION['id_usuario8291']) ){
          $seguridad = new Class_seguridad();
            $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_COTIZACIONES'));
            if( $tipoacceso > 0 ){
             $nuevoProducto=array();
              

                         if(isset($_SESSION['carrito'.$_SESSION['id_usuario8291']])){

                          $contador=$_SESSION['contador']+1;
                          $nuevoProducto['id'] = $contador;
                          $nuevoProducto['idproducto'] = 0;
                          $nuevoProducto['producto'] = $this->request->getPost('otro_producto_modal');
                          $nuevoProducto['descripcion'] = $this->request->getPost('otro_descripcion_modal');
                          $nuevoProducto['unitario'] = $this->request->getPost('otro_costo_modal');
                          $nuevoProducto['cantidad'] = 1;
                          $nuevoProducto['unidad'] = $this->request->getPost('otro_medida_modal');
                          $nuevoProducto['subtotal'] = $this->request->getPost('otro_costo_modal');   
                          echo "1";   
                          array_push($_SESSION['carrito'.$_SESSION['id_usuario8291']], $nuevoProducto);
                          $_SESSION['contador']=$contador;

    
                          }
                          else {
                          $_SESSION['carrito'.$_SESSION['id_usuario8291']]=array();
                          $_SESSION['contador']=1;
                          $contador=$_SESSION['contador']; 
                          $nuevoProducto['id'] = $contador;
                          $nuevoProducto['idproducto'] = 0;
                          $nuevoProducto['producto'] = $this->request->getPost('otro_producto_modal');
                          $nuevoProducto['descripcion'] = $this->request->getPost('otro_descripcion_modal');
                          $nuevoProducto['unitario'] = $this->request->getPost('otro_costo_modal');
                          $nuevoProducto['cantidad'] = 1;
                          $nuevoProducto['unidad'] = $this->request->getPost('otro_medida_modal');
                          $nuevoProducto['subtotal'] = $this->request->getPost('otro_costo_modal');   
                          echo "1";   
                          array_push($_SESSION['carrito'.$_SESSION['id_usuario8291']], $nuevoProducto);
   
                          }


      }else echo acceso_denegado();  
    }else echo acceso_denegado(); 
 
      
  }



   public function deleteCarrito()
  {
    if( isset($_SESSION['id_usuario8291']) ){
        $seguridad = new Class_seguridad();
        $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_COTIZACIONES'));
        if( $tipoacceso > 0 ){
             $nuevoProducto=array();
             $id_producto=$this->request->getPost('id_producto');

              $arreglo=$_SESSION['carrito'.$_SESSION['id_usuario8291']];
              for($i=0;$i<count($arreglo);$i++){
              if($arreglo[$i]['id']!=$id_producto){
              $datosNuevos[]=array(
              'id'=>$arreglo[$i]['id'],
              'idproducto'=>$arreglo[$i]['idproducto'],
              'producto'=>$arreglo[$i]['producto'],
              'descripcion'=>$arreglo[$i]['descripcion'],
              'unitario'=>$arreglo[$i]['unitario'],
              'cantidad'=>$arreglo[$i]['cantidad'],
              'unidad'=>$arreglo[$i]['unidad'],
              'subtotal'=>$arreglo[$i]['subtotal'],
              );
            }
          }

            if(isset($datosNuevos)){
            $_SESSION['carrito'.$_SESSION['id_usuario8291']]=$datosNuevos;
            $datosNuevos=$_SESSION['carrito'.$_SESSION['id_usuario8291']];
            }else{ unset($_SESSION['carrito'.$_SESSION['id_usuario8291']]); }




        }else echo acceso_denegado();  
    }else echo acceso_denegado(); 
 
      
  }

   public function updateItemCarrito()
  {
    if( isset($_SESSION['id_usuario8291']) ){
        $seguridad = new Class_seguridad();
        $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_COTIZACIONES'));
        if( $tipoacceso > 0 ){
             $nuevoProducto=array();
             $id_producto=$this->request->getPost('id_producto');

              $arreglo=$_SESSION['carrito'.$_SESSION['id_usuario8291']];
              for($i=0;$i<count($arreglo);$i++){
              if($arreglo[$i]['id']==$id_producto){
              $return_arr[]=array(
              'id'=>$arreglo[$i]['id'],
              'idproducto'=>$arreglo[$i]['idproducto'],
              'producto'=>$arreglo[$i]['producto'],
              'descripcion'=>$arreglo[$i]['descripcion'],
              'unitario'=>$arreglo[$i]['unitario'],
              'cantidad'=>$arreglo[$i]['cantidad'],
              'unidad'=>$arreglo[$i]['unidad'],
              'subtotal'=>$arreglo[$i]['subtotal'],
              );
            }
          } 

         echo json_encode($return_arr);

        }else echo acceso_denegado();  
    }else echo acceso_denegado(); 
 
      
  }

    public function update_item()
  {
    if( isset($_SESSION['id_usuario8291']) ){
        $seguridad = new Class_seguridad();
        $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_COTIZACIONES'));
        if( $tipoacceso > 0 ){
          
             $id=$this->request->getPost('id_item');
             $idproducto=$this->request->getPost('id_producto_item');
             $producto=$this->request->getPost('producto_item');
             $descripcion=$this->request->getPost('descripcion_item');
             $unitario=$this->request->getPost('costo_item');
             $cantidad=$this->request->getPost('cantidad_item');
             $unidad=$this->request->getPost('medida_item');
             $subtotal=$this->request->getPost('costo_item');
             
              
              $arreglo=$_SESSION['carrito'.$_SESSION['id_usuario8291']];

              for($i=0;$i<count($arreglo);$i++){
              if($arreglo[$i]['id']==$id){
              $datosNuevos[]=array(
              'id'=>$id,
              'idproducto'=>$idproducto,
              'producto'=>$producto,
              'descripcion'=>$descripcion,
              'unitario'=>$unitario,
              'cantidad'=>$cantidad,
              'unidad'=>$unidad,
              'subtotal'=>$subtotal*$cantidad
              );
            }
            else{

              $datosNuevos[]=array(
              'id'=>$arreglo[$i]['id'],
              'idproducto'=>$arreglo[$i]['idproducto'],
              'producto'=>$arreglo[$i]['producto'],
              'descripcion'=>$arreglo[$i]['descripcion'],
              'unitario'=>$arreglo[$i]['unitario'],
              'cantidad'=>$arreglo[$i]['cantidad'],
              'unidad'=>$arreglo[$i]['unidad'],
              'subtotal'=>$arreglo[$i]['subtotal'],
              );

            }
          }

            if(isset($datosNuevos)){
            $_SESSION['carrito'.$_SESSION['id_usuario8291']]=$datosNuevos;
            $datosNuevos=$_SESSION['carrito'.$_SESSION['id_usuario8291']];
            echo "1";
            }else{ unset($_SESSION['carrito'.$_SESSION['id_usuario8291']]); echo "2";}

            

            

        }else echo acceso_denegado();  
    }else echo acceso_denegado(); 
 
      
  }

  public function updateCarrito(){
  // unset( $_SESSION["carrito".$_SESSION['id_usuario8291']] ); 
  // unset( $_SESSION["contador"] ); 

     $seguridad = new Class_seguridad();
     $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_COTIZACIONES'));
     $array_carrito=array();  
    if(isset($_SESSION['carrito'.$_SESSION['id_usuario8291']])){


         
            $datos=$_SESSION['carrito'.$_SESSION['id_usuario8291']];
     
            for ($i=0; $i <count($datos) ; $i++) {
            
             $array_item=array(
            "numero"=>$i+1,
            "id"=>$datos[$i]['id'],
            "idproducto"=>$datos[$i]['idproducto'],
            "producto"=>substr($datos[$i]['producto'],0,50),
            "descripcion"=>substr($datos[$i]['descripcion'],0,26)."...",
            "unitario"=>number_format($datos[$i]['unitario'],2),
            "cantidad"=>$datos[$i]['cantidad'],
            "unidad"=>$datos[$i]['unidad'],
            "subtotal"=>number_format($datos[$i]['subtotal'],2),
            "tipoacceso"=>$tipoacceso
          ); 
            array_push($array_carrito, $array_item);


           }

          echo '{"data":'. json_encode($array_carrito).'}';
        

  
      }
      else {
          $_SESSION['carrito'.$_SESSION['id_usuario8291']]=array();

          echo '{"data":'. json_encode($array_carrito).'}';
    } 

  }

  public function updateCarrito_init(){
     unset( $_SESSION["carrito".$_SESSION['id_usuario8291']] ); 
     unset( $_SESSION["contador"] ); 
     $seguridad = new Class_seguridad();
     $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_COTIZACIONES'));
     $array_carrito=array();  
     echo '{"data":'. json_encode($array_carrito).'}';
  
  }



   public function updatePrecios(){
     $descuento=$this->request->getPost('descuento');
     if($descuento==""){$descuento=0;}else{$descuento=$descuento;}
     $impuesto=$this->request->getPost('impuesto');
     $array_carrito=array(); 
     $subtotal=0;$iva=0;$total=0; $subtotal1=0;
    if(isset($_SESSION['carrito'.$_SESSION['id_usuario8291']])){

          $datos=$_SESSION['carrito'.$_SESSION['id_usuario8291']];
          for ($i=0; $i <count($datos) ; $i++) { $subtotal+=$datos[$i]['subtotal']; }
            
            if($impuesto=="SI"){
            $subtotal1=$subtotal-$descuento;
            $iva=$subtotal1*0.16;
            $total=$subtotal1+$iva;

            
             $return_arr[]=array(
              'subtotal'=>number_format($subtotal,2),
              'iva'=>number_format($iva,2),
              'total'=>number_format($total,2),
              'subtotal2'=>$subtotal,
              'iva2'=>$iva,
              'total2'=>$total
              );
              echo json_encode($return_arr);
           
            }

            else{
            $subtotal1=$subtotal-$descuento;
            ///$subtotal1=$subtotal1/1.16;
            $iva=0;
            $total=$subtotal1+$iva;
             $return_arr[]=array(
              'subtotal'=>number_format($subtotal,2),
              'iva'=>number_format(0,2),
              'total'=>number_format($total,2),
              'subtotal2'=>$subtotal,
              'iva2'=>0,
              'total2'=>$total
              );
            echo json_encode($return_arr);

            }           

      }
      else {
                             
          $return_arr[]=array(
              'subtotal'=>0,
              'iva'=>0,
              'total'=>0,
              'subtotal2'=>0,
              'iva2'=>0,
              'total2'=>0
              );
          echo json_encode($return_arr);
    } 

  }

  public function save_new_producto(){
  
        $data_base = array(
               
             
               'producto' => $this->request->getPost('producto_modal'),
               'descripcion' => $this->request->getPost('descripcion_modal'),
               'costo' => $this->request->getPost('costo_modal'),
               'medida' => $this->request->getPost('medida_modal'),
                'tipo' => $this->request->getPost('tipo_modal')
            ); 
           
              $save = $this->model_cotizaciones->save_new_producto($data_base);
              if($save)echo "1";
              else echo "2";

  }

   public function save_update_cotizacion(){
  

    if($this->request->getPost('id_cot')==""){ ////////si cotizacion es nueva 
  
      $data_base = array(
               'idcliente' => $this->request->getPost('id_cliente'),
               'nombres' => $this->request->getPost('nombres'),
               'domicilio' => $this->request->getPost('domicilio_completo'),
               'telefono' => $this->request->getPost('telefono'),
               'correo' => $this->request->getPost('correo'),
               'fecha_cot' => $this->request->getPost('fecha_cot'),
               'fecha_ven' => $this->request->getPost('fecha_ven'),
               'impuesto' => $this->request->getPost('impuesto'), 
               'subtotal' => $this->request->getPost('subtotal_carrito'),
               'descuento' => $this->request->getPost('descuento_carrito'),
               'total' => $this->request->getPost('total_carrito'),
               'iva' => $this->request->getPost('iva_carrito'),
               'estado' => 0,
               'status' => 0,
               'notas' => $this->request->getPost('notas'),
               'sucursal' => $this->request->getPost('sucursal')
               
            ); 

             $save = $this->model_cotizaciones->save_new_cotizacion($data_base);
             $row=$this->model_cotizaciones->get_ultimo_id(); 
             $ultimo_id_cotizacion=$row->ultimo;
            
            if(isset($_SESSION['carrito'.$_SESSION['id_usuario8291']])){

          

            $carrito=$_SESSION['carrito'.$_SESSION['id_usuario8291']];

            }
            else{ 
              $carrito=array();
            }

             
             $save_items=$this->model_cotizaciones->save_items($carrito,$ultimo_id_cotizacion);
             //////////////////////////////////// Actualiza correo de usuario si no tiene correo actual
             $row_cot = $this->model_cotizaciones->getAll_cliente_cot($this->request->getPost('id_cliente'));
             $correo_cliente="";
             if($row_cot){$correo_cliente=$row_cot->correo;}
            
             if($this->request->getPost('id_cliente')>0 && $correo_cliente==""){
               $update_correo_cliente=$this->model_cotizaciones->update_correo_cliente($this->request->getPost('id_cliente'),$this->request->getPost('correo'));

             }

             ///////////////////////////////////
              if($save)echo "1";
              else echo "2";
              unset($_SESSION["carrito".$_SESSION['id_usuario8291']]); 
              
              } ///////////////fin si nueva cotizacion

              else {
                
                    
                $data_base = array(
               'idcliente' => $this->request->getPost('id_cliente'),
               'nombres' => $this->request->getPost('nombres'),
               'domicilio' => $this->request->getPost('domicilio_completo'),
               'telefono' => $this->request->getPost('telefono'),
               'correo' => $this->request->getPost('correo'),
               'fecha_cot' => $this->request->getPost('fecha_cot'),
               'fecha_ven' => $this->request->getPost('fecha_ven'),
               'impuesto' => $this->request->getPost('impuesto'), 
               'subtotal' => $this->request->getPost('subtotal_carrito'),
               'descuento' => $this->request->getPost('descuento_carrito'),
               'total' => $this->request->getPost('total_carrito'),
               'iva' => $this->request->getPost('iva_carrito'),
               'notas' => $this->request->getPost('notas'),
               'sucursal' => $this->request->getPost('sucursal')
            
            ); 
             

             $update = $this->model_cotizaciones->update_cotizacion($data_base,$this->request->getPost("id_cot"));
             $carrito=$_SESSION['carrito'.$_SESSION['id_usuario8291']];
             $delete_items=$this->model_cotizaciones->delete_cot_items($this->request->getPost("id_cot"));  
             $save_items=$this->model_cotizaciones->save_items($carrito,$this->request->getPost("id_cot"));
              if($update && $delete_items && $save_items)echo "1";
              else echo "2";
              unset($_SESSION["carrito".$_SESSION['id_usuario8291']]); 

            }   

  }

    public function editCotizacion()
  {
    if( isset($_SESSION['id_usuario8291']) ){
        $seguridad = new Class_seguridad();
        $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_COTIZACIONES'));
        if( $tipoacceso > 0 ){
             $id_cot=$this->request->getPost('id_cot'); $es_cliente="";
             $cotizacion=$this->model_cotizaciones->get_cotizacion_by_id($id_cot);
              
              foreach ($cotizacion as $items) {
              if($items->idcliente>0) {$es_cliente=1;}else{$es_cliente=0;}
              $return_arr[]=array(
              'id'=>$id_cot,
              'es_cliente'=>$es_cliente,
              'idcliente'=>$items->idcliente,
              'nombres'=>$items->nombres,
              'domicilio'=>$items->domicilio,
              'telefono'=>$items->telefono,
              'correo'=>$items->correo,
              'fecha_cot'=>$items->fecha_cot,
              'fecha_ven'=>$items->fecha_ven,
              'subtotal'=>$items->subtotal,
              'descuento'=>$items->descuento,
              'total'=>$items->total,
              'iva'=>$items->iva,
              'impuesto'=>$items->impuesto,
              'subtotal_view'=>"$ ".number_format($items->subtotal,2),
              'total_view'=>"$ ".number_format($items->total,2),
              'iva_view'=>"$ ".number_format($items->iva,2),
              'notas'=>$items->notas,
              'sucursal'=>$items->sucursal
              );
            
          } 
        
         echo json_encode($return_arr);
          $this->editCarrito($id_cot);
  //     

        }else echo acceso_denegado();  
    }else echo acceso_denegado(); 
 
      
  }

     public function editCarrito($id_cot){

     unset($_SESSION['carrito'.$_SESSION['id_usuario8291']]);
     unset($_SESSION['contador']);
     $nuevoProducto=array();
     $array_item=array();
     
     $items_cot=$this->model_cotizaciones->getItemsCot($id_cot);
     $i=0;
     if($items_cot){
      foreach ($items_cot as $items) {
                          $array_item['id'] = $i+1;
                          $array_item['idproducto'] = $items->idproducto;
                          $array_item['producto'] = $items->producto;
                          $array_item['descripcion'] = urldecode($items->descripcion);
                          $array_item['unitario'] = $items->subtotal/$items->cantidad;
                          $array_item['cantidad'] = $items->cantidad;
                          $array_item['unidad'] = $items->unidad;
                          $array_item['subtotal'] = $items->subtotal; 

            array_push($nuevoProducto, $array_item);
            $i++;
        
       }
       
     }

    $_SESSION['carrito'.$_SESSION['id_usuario8291']]=$nuevoProducto;
   // print_r($nuevoProducto);
    $_SESSION['contador']=$i;

   
                   
  }

   public function deleteCot(){
   if( isset($_SESSION['id_usuario8291']) ){
         $seguridad = new Class_seguridad();
         $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_COTIZACIONES'));
      if( $tipoacceso > 0 ){
        $id_cot=$this->request->getPost('id');      
       $delete = $this->model_cotizaciones->delete_cot($id_cot);
       $delete_items = $this->model_cotizaciones->delete_cot_items($id_cot);
       if($delete && $delete_items){echo 1;}else{echo 2;}            
        //echo $id_cliente;
      }else echo acceso_denegado();  
    }
    else (new Class_seguridad())->login_back();  
  }

     public function acepCot()
  {
    if( isset($_SESSION['id_usuario8291']) ){
        $seguridad = new Class_seguridad();
        $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_COTIZACIONES'));
        if( $tipoacceso > 0 ){
             $id_cot=$this->request->getPost('id'); $idliente="";
             $cotizacion=$this->model_cotizaciones->get_cotizacion_by_id($id_cot);

              $ultimo_numero_legal=$this->model_parametros_sistema->get_num_legal();
              $num_legal=$ultimo_numero_legal->value;
              $impuesto_item=0;

              foreach ($cotizacion as $items) {
              if($items->idcliente>0) {$idcliente=$items->idcliente;}else{$idcliente=328;}
              if($items->impuesto=="SI") {$impuesto_item=16;}else{$impuesto_item=0;}
              $array=array(
              'legal'=>$num_legal,
              'idcliente'=>$idcliente,
              'emitido'=>$items->fecha_cot,
              'vencimiento'=>$items->fecha_ven,
              'pago'=>"0000-00-00",
              'total'=>$items->total,
              'forma'=>"-------",
              'estado'=>"No pagado",
              'tipo'=>"1",
              'cobrado'=>"0.00",
              'iva_igv'=>$items->iva,
              'sub_total'=>$items->subtotal,
              'impuesto'=>$items->impuesto
              );
            
          } 
         
          $insert_factura=$this->model_cotizaciones->insert_factura($array);
          if($insert_factura){
            $update_num_legal=$this->model_cotizaciones->update_numero_legal(($num_legal+1));
            $row=$this->model_cotizaciones->get_ultima_factura(); 
            $id_factura=$row->ultimo;

            $update_cotizacion=$this->model_cotizaciones->update_cotizacion_parametros($id_cot,$id_factura);
            $items_cot = $this->model_cotizaciones->get_cot_items($id_cot);
            foreach ($items_cot as $items) {
              $arrayName = array('idfactura' => $id_factura,
                                 'descripcion' => $items->producto,
                                 'cantidad' =>$items->subtotal,
                                 'idalmacen'=>0,
                                 'unidades'=>$items->cantidad,
                                 'impuesto'=>$impuesto_item,
                                 'block'=>0,
                                 'impuesto911'=>0
                                 );
             $insert_items_factura=$this->model_cotizaciones->save_items_factura($arrayName);

            }
             echo 1;

          }
          else echo 2;
        

        }else echo acceso_denegado();  
    }else echo acceso_denegado(); 
 
      
  }

   public function pdf($id_cotizacion){
   if( isset($_SESSION['id_usuario8291']) ){
         $seguridad = new Class_seguridad();
         $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_COTIZACIONES'));
         $permiso_sucursal = new Class_permiso_sucursal();
         $where_permiso_sucursal = $permiso_sucursal->get_permiso_sucursal_cotizaciones($_SESSION['sucursales']); 
      if( $tipoacceso > 0 ){

        $cot_data = $this->model_cotizaciones->get_cotizaciones_data($id_cotizacion,$where_permiso_sucursal);
        if($cot_data){
          // Plantillas editables: Ajustes > Editor de plantillas (cotizacion, cotizacion_bco, cotizacion_pie)
          $mpdf = CotizacionPdf::crear($cot_data[0], $this->model_cotizaciones->get_cot_items($id_cotizacion) ?: []);
          return $this->response
            ->setContentType("application/pdf")
            ->setHeader("Content-Disposition", "inline; filename=\"cotizacion_" . $cot_data[0]->id . ".pdf\"")
            ->setBody($mpdf->Output("", "S"));

      } //fin if data
      else echo "NO TIENES PERMISO PARA VISUALIZAR ESTA COTIZACION EN PDF";

        
        

      }else echo acceso_denegado();  
    }
    else echo view('seguridad/login');  
  }


   public function show($file)
    {

          if(is_dir("./files/pdfs/")){
            $path = "./files/pdfs/".$file;
                if(file_exists("./files/pdfs/".$file)){
      
                 header("Content-Length: " . filesize ( $path ) );
                 header("Content-type: application/pdf"); 
                 header("Content-disposition: inline; filename=".basename($path)); 
                 header('Expires: 0'); 
                 header('Cache-Control: must-revalidate, post-check=0, pre-check=0'); 
                 ob_clean(); 
                 flush(); 
                 readfile($path); 
              }
           }
        
    }

    public function resendmail(){
   if( isset($_SESSION['id_usuario8291']) ){
         $seguridad = new Class_seguridad();
         $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_COTIZACIONES'));
      if( $tipoacceso > 0 ){
         $id_cotizacion=$this->request->getPost('id'); 
         $where_permiso_sucursal = (new Class_permiso_sucursal())->get_permiso_sucursal_cotizaciones($_SESSION["sucursales"]);
         $cot_data = $this->model_cotizaciones->get_cotizaciones_data($id_cotizacion, $where_permiso_sucursal);
        if(!$cot_data){ echo "2"; return; }
        $mpdf = CotizacionPdf::crear($cot_data[0], $this->model_cotizaciones->get_cot_items($id_cotizacion) ?: []);
        if (! is_dir(FCPATH . "files/cotizaciones")) {
          mkdir(FCPATH . "files/cotizaciones", 0755, true);
        }
        $mpdf->Output(FCPATH . "files/cotizaciones/cotizacion.pdf","F");
        $this->send_cotizacion_to_mail($id_cotizacion);
        

      }else echo acceso_denegado();  
    }
    else (new Class_seguridad())->login_back();  
  }

     protected function send_cotizacion_to_mail($id_cotizacion)
    {
       
        // Antes llamaba a un modelo inexistente ($this->model_formatos->get_data_formato(20)) y fallaba siempre.
        $where_permiso_sucursal = (new Class_permiso_sucursal())->get_permiso_sucursal_cotizaciones($_SESSION["sucursales"]);
        $cot = $this->model_cotizaciones->get_cotizaciones_data($id_cotizacion, $where_permiso_sucursal)[0];
        $items = $this->model_cotizaciones->get_cot_items($id_cotizacion) ?: [];
        $correo = $cot->correo;
        $plantilla = CotizacionPdf::plantilla("cotizacion_mail", (int) $cot->sucursal);
        $asunto = CotizacionPdf::rellenarTexto($plantilla && $plantilla->asuntofactura != "" ? $plantilla->asuntofactura : "Cotizacion: {folio}", $cot, $items);
        $html = CotizacionPdf::correo($cot, $items);

        // Email de CI4 (antes API de CI3: $this->load->library('email'), que no existe en CI4)
        $integraciones = config('Integraciones');
        $email = \Config\Services::email();
        $email->initialize([
          'protocol'   => 'smtp',
          'SMTPHost'   => $integraciones->smtpHost,
          'SMTPUser'   => $integraciones->smtpUser,
          'SMTPPass'   => $integraciones->smtpPass,
          'SMTPPort'   => (int) $integraciones->smtpPort,
          'SMTPCrypto' => 'tls',
          'mailType'   => 'html',
          'charset'    => 'utf-8',
          'wordWrap'   => true,
          'validate'   => true,
        ]);
        $email->setFrom($integraciones->smtpUser, $integraciones->smtpFromName);
        $email->setTo($correo);
        $email->setSubject(html_entity_decode(strip_tags($asunto)));
        $email->setMessage($html);
        $email->attach(FCPATH . 'files/cotizaciones/cotizacion.pdf');

        // Antes se devolvía print_debugger() al navegador (detalles del SMTP)
        if ($email->send(false)) {
          echo "1";
        } else {
          log_message('error', 'Envío de cotización ' . $id_cotizacion . ' fallido: ' . $email->printDebugger(['headers']));
          echo "2";
        }


    }


  public function get_historial_json(){
    
    $id_cliente=$this->request->getGet('id');  
    $all_items = $this->model_clientes->getAll_historial($id_cliente);
     $array_cliente=array();  
    if($all_items){   
   
      foreach ($all_items as $items) {
        $array_item=array(
        "id"=>$items->id,
        "descripcion"=>$items->descripcion,
        "fecha"=>$this->fechaCastellano($items->fecha)." a las ".date_format(date_create($items->fecha), 'H:i:s a')
          ); 
        array_push($array_cliente, $array_item);
      }
    }  echo '{"data":'. json_encode($array_cliente).'}';
 }

   public function get_email_json(){
    
    $id_cliente=$this->request->getGet('id');  
    $all_items = $this->model_clientes->getAll_email($id_cliente);
     $array_cliente=array();  $asunto="";$estado="";
    if($all_items){   
   
      foreach ($all_items as $items) {
        if($items->adjunto!=""){$asunto="<span class='fa fa-paperclip'>&nbsp</span>".$items->asunto;} else{ $asunto=$items->asunto;}
        if($items->estado==0) {$estado='<span class="label label-danger">No enviado</span>'; } 
        if($items->estado==1) {$estado='<span class="label label-success">Enviado</span>';}
        if($items->estado==2) {$estado='<span class="label label-warning">Enviando</span>';}
        $array_item=array(
        "id"=>$items->id,
        "asunto"=>$asunto,
        "fecha"=>$this->fechaCastellano($items->enviado)." a las ".date_format(date_create($items->enviado), 'H:i:s a'),
         "remitente"=>$items->mailremitente,
          "destino"=>$items->maildestino,
           "estado"=> $estado,
          ); 
        array_push($array_cliente, $array_item);
      }
    }  echo '{"data":'. json_encode($array_cliente).'}';
 }

   public function get_sms_json(){
    
    $id_cliente=$this->request->getGet('id');  
    $all_items = $this->model_clientes->getAll_sms($id_cliente);
     $array_cliente=array();  $asunto="";$estado="";
    if($all_items){   
   
     foreach ($all_items as $items) {
       if($items->estado=="no enviado") {$estado='<span class="label label-danger">No enviado</span>'; } 
        if($items->estado=="enviado") {$estado='<span class="label label-success">Enviado</span>';}
        if($items->estado=="enviando") {$estado='<span class="label label-warning">Enviando</span>';}
        $array_item=array(
        "id"=>$items->id,
        "mensaje"=>$items->mensaje,
        "fecha"=>$this->fechaCastellano($items->enviado)." a las ".date_format(date_create($items->enviado), 'H:i:s a'),
         "estado"=>$estado,
          "destino"=>$items->ndestino,
           "fecha"=>$this->fechaCastellano($items->enviado)." a las ".date_format(date_create($items->enviado), 'H:i:s a'),
          ); 
        array_push($array_cliente, $array_item);
      }
    }  echo '{"data":'. json_encode($array_cliente).'}';
 }

  public function save_data_clientes(){
   if( isset($_SESSION['id_usuario8291']) ){
         $seguridad = new Class_seguridad();
         $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('USUARIOS'));
      if( $tipoacceso > 0 ){
        $id_cliente=$this->request->getPost('id');
        $tipo=$this->request->getPost('tipo');
        $data_base = array(
               'tipo' => $this->request->getPost('tipo'),
               'nombres' => $this->request->getPost('nombres'),
               'apellidos' => $this->request->getPost('apellidos'),
               'domicilio_completo' => $this->request->getPost('domicilio_completo'),
               'tipofiscal' => $this->request->getPost('tipofiscal'),
               'razonsocial' => $this->request->getPost('razonsocial'),
               'rfc' => $this->request->getPost('rfc'),
               'domiciliofiscal' => $this->request->getPost('domiciliofiscal'),
               'notasfac' => $this->request->getPost('notasfac'),
               'cp' => $this->request->getPost('cp'),
               'movil' => $this->request->getPost('movil'),
               'telefono' => $this->request->getPost('telefono'),
               'email' => $this->request->getPost('email'),
               'nombreempresa' => $this->request->getPost('nombreempresa'),
               'contrasenia_portal' => $this->request->getPost('contrasenia_portal'),
               'id_sucursal' => $this->request->getPost('id_sucursal')
            ); 
    
              $update = $this->model_clientes->update_data_user($id_cliente,$data_base);
              if($update){echo 1;}else{echo 2;} 

      }else echo acceso_denegado();  
    }
    else (new Class_seguridad())->login_back();  
  }


  public function update_config_client(){
   if( isset($_SESSION['id_usuario8291']) ){
         $seguridad = new Class_seguridad();
         $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('USUARIOS'));
      if( $tipoacceso > 0 ){
        $id_cliente=$this->request->getGet('id');
        $impuesto=$this->request->getPost('conf-impuesto');
        $tipoaviso=$this->request->getPost('conf-tipoaviso');

        $data_base = array(
               'impuesto' => $impuesto,
               'tipoaviso' => $tipoaviso
            ); 
    
              $update_config_client = $this->model_clientes->update_config_client($id_cliente,$data_base);
              if($update_config_client){echo "success";}else{echo "error";} 

      }else echo acceso_denegado();  
    }
    else (new Class_seguridad())->login_back();  
  }

  public function save_new_cliente(){
   if( isset($_SESSION['id_usuario8291']) ){
         $seguridad = new Class_seguridad();
         $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('USUARIOS'));
      if( $tipoacceso > 0 ){
        $tipo=$this->request->getPost('tipo');
        $data_base = array(
              
               'tipo' => $this->request->getPost('tipo'),
               'nombres' => $this->request->getPost('nombres'),
               'apellidos' => $this->request->getPost('apellidos'),
               'domicilio_completo' => $this->request->getPost('domicilio_completo'),
               'tipofiscal' => $this->request->getPost('tipofiscal'),
               'razonsocial' => $this->request->getPost('razonsocial'),
               'rfc' => $this->request->getPost('rfc'),
               'domiciliofiscal' => $this->request->getPost('domiciliofiscal'),
               'notasfac' => $this->request->getPost('notasfac'),
               'cp' => $this->request->getPost('cp'),
               'movil' => $this->request->getPost('movil'),
               'telefono' => $this->request->getPost('telefono'),
               'email' => $this->request->getPost('email'),
               'nombreempresa' => $this->request->getPost('nombreempresa'),
               'contrasenia_portal' => $this->request->getPost('contrasenia_portal'),
               'estatus' => 0,
               'id_sucursal' => $this->request->getPost('id_sucursal'),
               'fecha_creacion' => date('Y-m-d H:i:s'),
               'ultima_visita' => date('Y-m-d H:i:s')
            ); 
           
              $save = $this->model_clientes->save_new_cliente($data_base);

              $row=$this->model_clientes->get_ultimo_cliente(); $id_cliente=$row->ultimo;

              /////////obtengo la sucursal del cliente y luego consulto el estatus del impuesto y tipo de aviso
              $user_data = $this->model_clientes->get_user_data($id_cliente,null);
              $id_sucursal=$user_data->id_sucursal;

              $sucursal_data = $this->model_clientes->get_sucursal_by_id_tbl($id_sucursal);
              $impuesto=$sucursal_data->impuesto;
              $tipoaviso=$sucursal_data->tipoaviso;

              /////////aqui guardo los parametros de impuiestos,tipo aviso etc
              $new_tblavisouser = $this->model_clientes->new_tblavisouser($id_cliente,$impuesto,$tipoaviso);
              ////////////aqui guardoooo los parametros nuevos del cliente
              if($save){echo 1;}else{echo 2;} 
              

      }else echo acceso_denegado();  
    }
    else (new Class_seguridad())->login_back();  
  }

  public function save_new_cliente_from_step(){
   if( isset($_SESSION['id_usuario8291']) ){
         $seguridad = new Class_seguridad();
         $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('USUARIOS'));
      if( $tipoacceso > 0 ){
        ////////revisamos si existe el id, si no existe lo guardamos.
        $id_cliente=$this->request->getPost('id_cliente');
        $checkid = $this->model_clientes->checkid($id_cliente);
        if($checkid){
           echo $id_cliente."&exist";
        }
        else{
          $data_base = array(
               'tipo' => $this->request->getPost('tipo'),
               'nombres' => $this->request->getPost('nombres'),
               'paterno' => $this->request->getPost('paterno'),
               'materno' => $this->request->getPost('materno'),
               'rfc' => $this->request->getPost('rfc'),
               'domicilio_completo' => $this->request->getPost('domicilio_completo'),
               'cp' => $this->request->getPost('cp'),
               'movil' => $this->request->getPost('movil'),
               'telefono' => $this->request->getPost('telefono'),
               'email' => $this->request->getPost('correo'),
               'estatus' => 0,
               'contrasenia_portal' => $this->request->getPost('contrasenia_portal'),
               'fecha_creacion' => date('Y-m-d H:i:s'),
               'ultima_visita' => date('Y-m-d H:i:s'),
               'id_sucursal' => $this->request->getPost('id_sucursal')
            ); 
             
              $save = $this->model_clientes->save_new_cliente($data_base);
              $row= $this->model_clientes->get_id_by_parametros($this->request->getPost('nombres'),$this->request->getPost('paterno'),$this->request->getPost('materno'),$this->request->getPost('rfc'),$this->request->getPost('domicilio_completo'),$this->request->getPost('movil'),$this->request->getPost('contrasenia_portal'));
              $ultimo_id=$row->id;
              
              $id_cliente=$ultimo_id;

              /////////obtengo la sucursal del cliente y luego consulto el estatus del impuesto y tipo de aviso
              $user_data = $this->model_clientes->get_user_data($id_cliente,null);
              $id_sucursal=$user_data->id_sucursal;

              $sucursal_data = $this->model_clientes->get_sucursal_by_id_tbl($id_sucursal);
              $impuesto=$sucursal_data->impuesto;
              $tipoaviso=$sucursal_data->tipoaviso;

              /////////aqui guardo los parametros de impuiestos,tipo aviso etc
              $new_tblavisouser = $this->model_clientes->new_tblavisouser($id_cliente,$impuesto,$tipoaviso);

              echo $ultimo_id."&not-exist";
              
        }

      }else echo acceso_denegado();  
    }
    else (new Class_seguridad())->login_back();  
  }

  public function delete_cliente(){
   if( isset($_SESSION['id_usuario8291']) ){
         $seguridad = new Class_seguridad();
         $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('USUARIOS'));
      if( $tipoacceso > 0 ){
        $id_cliente=$this->request->getPost('id');      
       $delete = $this->model_clientes->delete_cliente($id_cliente);
       if($delete){echo 1;}else{echo 2;} $id_cliente=null;           
        //echo $id_cliente;
      }else echo acceso_denegado();  
    }
    else (new Class_seguridad())->login_back();  
  }

  public function retirar_activar(){
   if( isset($_SESSION['id_usuario8291']) ){
         $seguridad = new Class_seguridad();
         $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('USUARIOS'));
      if( $tipoacceso > 0 ){
        $id_cliente=$this->request->getPost('id');
        $action=$this->request->getPost('action');
        ($action=="retirado") ? $estatus=2 : $estatus=0;        
        $retirar_activar = $this->model_clientes->retirar_activar($id_cliente,$estatus);
       if($retirar_activar){echo 1;}else{echo 2;} $id_cliente=null;           
        //echo $id_cliente;
      }else echo acceso_denegado();  
    }
    else (new Class_seguridad())->login_back();  
  }

    public function retirar_activar_masivo(){
   if( isset($_SESSION['id_usuario8291']) ){
         $seguridad = new Class_seguridad();
         $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('USUARIOS'));
      if( $tipoacceso > 0 ){
        $id_all=$this->request->getPost('id');
        $action=$this->request->getPost('action');
        $bandera=false;
        $array_id=explode(",", $id_all);
        ($action=="retirarmasivo") ? $estatus=2 : $estatus=0;        
       //if($retirar_activar){echo 1;}else{echo 2;} $id_cliente=null;           
        foreach ($array_id as $items) {
          $retirar_activar_masivo = $this->model_clientes->retirar_activar($items,$estatus);
          ($retirar_activar_masivo) ? $bandera=true : $bandera=false;
        }
        if($bandera) {echo 1;} else echo 2;
      }else echo acceso_denegado();  
    }
    else (new Class_seguridad())->login_back();  
  }

    public function reload_retirados()
  {   
    if( isset($_SESSION['id_usuario8291']) ){
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('USUARIOS'));
      if( $tipoacceso > 0 ){

            $all_items_column_name = $this->model_clientes->get_column_to_show($id_tabla_controller=1,$active=0); 
            $array_pila_column = array();
            $content_table_column_name="";
          if($all_items_column_name){
                       $content_table_column_name.='<tr><th><div class="checkbox"><label><input type="checkbox"></label></div></th>';     
                       foreach ($all_items_column_name as $items) {
                        ($items->visible==0) ? $content_table_column_name.="<th>".$items->nombre_view."</th>" : null;
                        array_push($array_pila_column, $items->visible);                                
                    }
                      $content_table_column_name.='<th class="">Acciones</th></tr>'; 
                 } 
         
             $all_items = $this->model_clientes->getAll_retirados();
             $content_table="";
             if($all_items){     
                      foreach ($all_items as $items) {
                      ($items->tipo==1) ? $domicilio="Calle:".$items->calle." #".$items->numero." x ".$items->cruzamientos." ".$items->colonia." ".$items->municipio.", ".$items->estado." ".$items->cp : $domicilio=$items->domicilio_completo;
                      ($items->estatus==2) ? $status='<span class="label label-warning">RETIRADO</span>' : $status='<span class="label label-danger">INACTIVO</span>';
                      ($items->tipo==0) ? $tipo='<span class="label label-default">PARTICULAR</span>' : $tipo='<span class="label label-primary">FISCAL</span>';
                         
                          $acciones='<a href="'.base_url().'clientes/detalleuser/'.$items->id.'" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Editar Cliente"><span class="glyphicon glyphicon glyphicon-edit"></span></a>
                                     <button type="button" class="btn btn-xs btn-default bt-delete css-tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Eliminar Cliente" onclick="delete_cliente(\''. $items->id.'\',\''.base_url().'\');"><span class="glyphicon glyphicon-trash"></span>
                                     <button type="button" class="btn btn-xs btn-default " data-toggle="tooltip" data-placement="top" data-original-title="Retirar Cliente" onclick="retirar_activar(\''. $items->id.'\',\'retirado\',\''.base_url().'\');"><i class="fa fa-ban" aria-hidden="true"></i></button>
                                     <button type="button" class="weutool btn btn-xs btn-default " title="Herramientas" data-idcliente="000002"><span class="glyphicon glyphicon-wrench"></span></button>';


                        $content_table.='<tr>';
                        $content_table.='<td class="details-control"><div class="checkbox check"><label><input type="checkbox" value=""></label></div></td>';
                        ($array_pila_column[0]==0) ? $content_table.="<td>".$items->id."</td>" : null;
                        ($array_pila_column[1]==0) ? $content_table.="<td>".$tipo."</td>" : null;
                        ($array_pila_column[2]==0) ? $content_table.="<td><a href='clientes/detalleuser/".$items->id."/'>".$items->nombres." ".$items->apellidos."</a></td>" : null;
                        ($array_pila_column[3]==0) ? $content_table.="<td>".$items->rfc."</td>" : null;
                        ($array_pila_column[4]==0) ? $content_table.="<td>".$domicilio."</td>" : null;
                        ($array_pila_column[5]==0) ? $content_table.="<td>".$items->movil."</td>" : null;
                        ($array_pila_column[6]==0) ? $content_table.="<td>".$items->telefono."</td>" : null;
                        ($array_pila_column[7]==0) ? $content_table.="<td>".$items->email."</td>" : null;
                        ($array_pila_column[8]==0) ? $content_table.="<td>".$status."</td>" : null;
                        ($array_pila_column[9]==0) ? $content_table.="<td>".$items->contrasenia_portal."</td>" : null;
                        ($array_pila_column[10]==0) ? $content_table.="<td>".date_format(date_create($items->fecha_creacion), 'd/m/Y H:i:s a')."</td>" : null;
                        ($array_pila_column[11]==0) ? $content_table.="<td>".date_format(date_create($items->ultima_visita), 'd/m/Y H:i:s a')."</td>" : null;
                        $content_table.="<td>".$acciones."</td>";
                        $content_table.='</tr>';
                                 
                    }
                 }  

              
        echo $content_table_column_name."Ç".$content_table;   
        }else echo acceso_denegado();  
    }
    else (new Class_seguridad())->login_back();
  }

  public function notas(){
 $id_cliente=$this->request->getPost('id');
 $all_notas = $this->model_clientes->getAll_notas_by_cliente($id_cliente);
 if($all_notas){
  foreach ($all_notas as $items) {
    $id_nota=$items->id;
    $fecha=$this->fechaCastellano($items->fecha);
    $hora=date("h:i A",strtotime($items->fecha));
    $operador=$items->nombre;
    $asunto=$items->asunto;
    $mensaje=$items->mensaje;
    $adjuntos=$items->adjuntos;  
   helper('filesystem');
   $ruta_adjunto = "./files/notas/".$id_nota."_".$adjuntos;
   $fileinfo = get_file_info($ruta_adjunto);
   // get_mime_by_extension() era del helper "file" de CI3
   $file_extension = \Config\Mimes::guessTypeFromExtension(pathinfo($ruta_adjunto, PATHINFO_EXTENSION)) ?? '';
   $size=$this->FileSizeConvert($fileinfo['size'] ?? 0);
    


    echo '
<li class="time-label trnota'.$id_nota.'"><span class="bg-red">15 de Junio del 2018</span></li>  
<li class="trnota'.$id_nota.'">
<i class="fa fa-envelope bg-blue"></i>
<div class="timeline-item"><span class="time"><i class="fa fa-clock-o"></i> '.$operador.' - '.$hora.' <button type="button" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Eliminar Archivo" onclick="delete_notas(\''.$id_nota.'\');"><span class="glyphicon glyphicon-trash"></span></button></span>
<h3 class="timeline-header"><b>'.$asunto.'</b></h3>
<div class="timeline-body">

<p>'.$mensaje.'</p>
</div>';



    if($adjuntos!=""){

echo '<div class="timeline-footer">
<ul class="mailbox-attachments clearfix">
 <li>
<span class="mailbox-attachment-icon has-img">';
if($file_extension=="image/jpeg" || $file_extension=="image/jpg" || $file_extension=="image/png" || $file_extension=="image/gif"){

echo '<img src="'.base_url().'files/notas/'.$id_nota.'_'.$adjuntos.'" alt="imagen">';
echo'</span>
<div class="mailbox-attachment-info">
<a href="javascript:void(0)" class="mailbox-attachment-name"><i class="fa fa-camera"></i>&nbsp;'.$adjuntos.'</a>
<span class="mailbox-attachment-size">'.$size.'
<a href="'.base_url().'clientes/dowload_img?down='.$id_nota.'_'.$adjuntos.'" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
</span>
</div>
</li></ul>
</div>
</div>
</li>';

}
else{

echo '<img src="'.base_url().'files/notas/000_default_doc.png" alt="imagen">';
echo'</span>
<div class="mailbox-attachment-info">
<a href="javascript:void(0)" class="mailbox-attachment-name"><i class="fa fa-file"></i>&nbsp;'.$adjuntos.'</a>
<span class="mailbox-attachment-size">'.$size.'
<a href="'.base_url().'clientes/dowload_img?down='.$id_nota.'_'.$adjuntos.'" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
</span>
</div>
</li></ul>
</div>
</div>
</li>';

}


    }

  }

 }
  
}

 public function documentos(){
 $id_cliente=$this->request->getPost('id');
 $all_docs = $this->model_clientes->getAll_docs_by_cliente($id_cliente);
  $content_table="";
 if($all_docs){
  foreach ($all_docs as $items) {
    $id_nota=$items->id;
    $fecha=$this->fechaCastellano($items->fecha);
    $adjuntos=$items->adjuntos;  
    helper('filesystem');
    
    $content_table.="<tr>";
    $content_table.='<td><a href="'.base_url().'clientes/dowload_doc?filename='.$id_nota.'_'.$adjuntos.'">'.$adjuntos.'</a></td>';
    $content_table.="<td>".$fecha."</td>";
    $content_table.='<td style="text-align:center"><button type="button" class="btn btn-xs btn-default bt-delete css-tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Eliminar Cliente"  Onclick="delete_doc(\''.$id_nota.'\');"><span class="glyphicon glyphicon-trash"></span></button></td>';
    $content_table.="</tr>";
  
    }

  }

  echo $content_table;

 }
  
public function delete_nota(){
   $id_nota=$this->request->getPost('id');
   $delete = $this->model_clientes->delete_nota($id_nota);
   if($delete){echo 1;}else{echo 2;} 
}

public function new_nota(){
   $id_cliente=$this->request->getGet('id');
   $adjuntos=$this->request->getPost('notadjunto');
   $notatitulo=$this->request->getPost('notatitulo');
   $newnota=$this->request->getPost('newnota');

   $new_nota = $this->model_clientes->new_nota($id_cliente,$notatitulo,$newnota,$_FILES['notadjunto']['name'],$_SESSION['id_usuario8291'],0);
   $ultimo_id=$this->model_clientes->get_ultima_nota(); $id_nota=$ultimo_id->ultimo;
   
$dir_subida = './files/notas/';
$fichero_subido = $dir_subida.basename($id_nota."_".$_FILES['notadjunto']['name']);

echo '<pre>';
if (move_uploaded_file($_FILES['notadjunto']['tmp_name'], $fichero_subido)) {
    echo "El fichero es válido y se subió con éxito.\n";
} else {
    echo $_FILES['notadjunto']['error'];
    echo " error - verifica el codigo de error";
}

  
}

public function new_doc(){
   $id_cliente=$this->request->getGet('id');
   $new_nota = $this->model_clientes->new_nota($id_cliente,"","",$_FILES['docadjunto']['name'],$_SESSION['id_usuario8291'],1);
   $ultimo_id=$this->model_clientes->get_ultima_nota(); $id_nota=$ultimo_id->ultimo;
   
$dir_subida = './files/notas/';
$fichero_subido = $dir_subida.basename($id_nota."_".$_FILES['docadjunto']['name']);

echo '<pre>';
if (move_uploaded_file($_FILES['docadjunto']['tmp_name'], $fichero_subido)) {
    echo "El fichero es válido y se subió con éxito.\n";
} else {
    echo $_FILES['docadjunto']['error'];
    echo " error - verifica el codigo de error";
}

  
}

public function dowload_img(){
  $filename=$this->request->getGet('down');
  if(is_dir("./files/notas"))
        {
            
            $route = base_url("files/notas/".$filename); 
            if(file_exists("./files/notas/".$filename))
            {
                header('Content-type: application/pdf'); 
                header("Content-Disposition:attachment;filename=".$filename);
                readfile($route);
            }
        }
}

public function dowload_doc(){
  $filename=$this->request->getGet('filename');
  if(is_dir("./files/notas"))
        {
            
            $route = base_url("files/notas/".$filename); 
            if(file_exists("./files/notas/".$filename))
            {
                header('Content-type: application/pdf'); 
                header("Content-Disposition:attachment;filename=".$filename);
                readfile($route);
            }
    }
}


 public function fechaCastellano ($fecha) {
  $fecha = substr($fecha, 0, 10);
  $numeroDia = date('d', strtotime($fecha));
  $dia = date('l', strtotime($fecha));
  $mes = date('F', strtotime($fecha));
  $anio = date('Y', strtotime($fecha));
  $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
  $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
  $nombredia = str_replace($dias_EN, $dias_ES, $dia);
$meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
  $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
  $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
  return $nombredia." ".$numeroDia." de ".$nombreMes." de ".$anio;
}

function FileSizeConvert($bytes)
{
    $result=null;
    $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

    foreach($arBytes as $arItem)
    {
        if($bytes >= $arItem["VALUE"])
        {
            $result = $bytes / $arItem["VALUE"];
            $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
            break;
        }
    }
    return $result;
}


  public function mail_by_id(){
   
   $id_mail=$this->request->getPost('id');

   $items = $this->model_clientes->get_mail_by_id($id_mail);
   $array = array('id'=>$items->id, 'asunto'=>$items->asunto,'mailremitente'=>$items->mailremitente,'maildestino'=>$items->maildestino, 'log'=>$items->log,
                  'cuerpo'=>$items->cuerpo,'adjunto'=>$items->adjunto);
    echo json_encode($array);
 }  



  public function get_data_mail(){
   
   $id=$this->request->getPost('id');
   $items = $this->model_clientes->get_user_data($id,null);
   $array = array('id'=>$items->id, 'email'=>$items->email,'remitente'=>$this->correo_user,'nombres'=>$items->nombres);
    echo json_encode($array);
  
 }


 public function new_mail(){
   $adjunto="";
   $id=$this->request->getPost('idmailnew');
   $asunto=$this->request->getPost('asuntomailnew');
   $cuerpo=$this->request->getPost('cuerpomailnew');
   if(isset($_FILES['adjuntomailnew']['name'])){$adjunto=$_FILES['adjuntomailnew']['name'];}else{$adjunto="";}
   $remitente=$this->request->getPost('remitentemailnew');
   $destino=$this->request->getPost('destinomailnew');
   $nombre_cliente=$this->request->getPost('nombremailnew');
   if($adjunto!=""){

    $dir_subida = './files/uploads/';
    $fichero_subido = $dir_subida.basename($id."_".$_FILES['adjuntomailnew']['name']);
    if (move_uploaded_file($_FILES['adjuntomailnew']['tmp_name'], $fichero_subido)) {
     // echo "El fichero es válido y se subió con éxito.\n";
      $adjunto=$id."_".$_FILES['adjuntomailnew']['name'];
    } else {
      echo $_FILES['adjuntomailnew']['error'];
   }
}

   date_default_timezone_set($this->zona_horaria);
   $new_mail = $this->model_clientes->new_mail($id,$asunto,$cuerpo,$adjunto,$remitente,$destino,$nombre_cliente);
   $ultimo_id=$this->model_clientes->get_ultimo_correo(); $id_correo=$ultimo_id->ultimo;
   if($new_mail){echo $id_correo;} else{echo "ERROR_MYSQL";} 

   //////////////////enviamos el correo
   //$mail_send=new Mail_send();
   //$mail_send->send_mail_all($id_correo);

  
 }

  public function new_sms(){

   $id_cliente=$this->request->getPost('idsms');
   $movil=$this->request->getPost('movilsms');
   $mensaje=$this->request->getPost('newsms');

  

   date_default_timezone_set($this->zona_horaria);
   $new_sms = $this->model_clientes->new_sms($id_cliente,$movil,$mensaje);
   $ultimo_id=$this->model_clientes->get_ultimo_sms(); $id_sms=$ultimo_id->ultimo;
   if($new_sms){echo $id_sms;} else{echo "ERROR_MYSQL";} 

 }


	public function get_column_to_dropdown(){
     
       $all_items_column_name = $this->model_clientes->get_column_to_show2($id_tabla_controller=1,$active=0); 
       $array_pila_column = array();
       $content_table_column_name="";
	   if($all_items_column_name){  
           $array_pila_column['row'] = $all_items_column_name;                               
        }    
      
        echo json_encode($array_pila_column);
      }

}

function fechaCastellano ($fecha) {
  $fecha = substr($fecha, 0, 10);
  $numeroDia = date('d', strtotime($fecha));
  $dia = date('l', strtotime($fecha));
  $mes = date('F', strtotime($fecha));
  $anio = date('Y', strtotime($fecha));
  $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
  $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
  $nombredia = str_replace($dias_EN, $dias_ES, $dia);
$meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
  $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
  $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
  return $nombredia." ".$numeroDia." de ".$nombreMes." de ".$anio;
}