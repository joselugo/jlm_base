<?php namespace App\Controllers;
use App\Libraries\Class_seguridad;
use App\Libraries\Class_cantidad_en_letras;
use App\Models\Model_clientes;
use App\Models\Model_selectores;
use App\Models\Model_formatos;
use App\Models\Model_sucursal;
use App\Models\Model_log;
class Formatos extends BaseController {
  var $model_formatos;
  var $model_sucursal;
	public function __construct(){
    $this->model_formatos=new Model_formatos();
    $this->model_sucursal = new Model_sucursal();
		session();
	}

	public function index()
	{		
	

            if( isset($_SESSION['id_usuario8291']) ){
               $seguridad = new Class_seguridad();
               $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('FORMATOS'));
            if( $tipoacceso >= 2 ){
                    //   $data['usuario'] = $_SESSION["nombre"];
                 //session_destroy();
                       $data['fecha_hoy']=date("Y-m-d");
                       $data['tipoacceso']=$tipoacceso;               
                       echo view('formatos/index', $data);
            }
        else echo "<script>alerta(\"warning\",\"No cuentas con los permisos necesarios, contacta con tu administrador.\")</script>";
        }
        else $this->seguridad->login_back();
	}

	public function viewformato(){

     if( isset($_SESSION['id_usuario8291']) ){
            $seguridad = new Class_seguridad();
            $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('FORMATOS'));
         if($tipoacceso){ 
         	$nombre_formato="";$html="";
        $mpdf = new \Mpdf\Mpdf(['orientation' => 'P']);        
        

         $formato = $this->request->getGet('formato');
         $id_cliente = $this->request->getGet('id_cliente');
         $cliente = $this->request->getGet('cliente');
         $folio = $this->request->getGet('folio');
         $fecha = $this->request->getGet('fecha');
         $rfc = $this->request->getGet('rfc');
         $new_contrato_select = $this->request->getGet('new_contrato_select');
         $mes = $this->mes(date("m"));
         $data['mes']=$mes;
         $data['id_cliente']=$id_cliente;
         $data['cliente']=$cliente;
         $data['folio']=$folio;
      

         switch ($formato) {
         	case '1':
         	     $row_cliente=$this->model_formatos->get_cliente($id_cliente);
         	     $data['direccion']=$row_cliente->direccion;
         	     $data['telefono']=$row_cliente->telefono;
         	     $data['movil']=$row_cliente->movil;
         	     $data['correo']=$row_cliente->correo;
                 $data['folio_servicio']=$row_cliente->id;
                 $data['plan']=$row_cliente->plan;
         	     $data['costo']=$row_cliente->costo;
                 $nombre_formato="contrato"; 
                 $html = view('formatos/contrato',$data);
         		break;
         	case '2': 
                 $nombre_formato="soporte";	
         		 $html = view('formatos/soporte',$data);

         		break;		
         	case '3': 
         		 $nombre_formato="mantenimiento";  
                 $html = view('formatos/mantenimiento',$data);
         		break;			
         	case '4':
         	    $row=$this->model_formatos->get_serie_antena($id_cliente);
         	    $row_modem=$this->model_formatos->get_serie_modem($id_cliente);
         	    $data['antena_serie']=str_replace(":","",$row->mac);
         	    $data['modem_serie']=$row_modem->serial_producto;
         	    $data['modem_modelo']=$row_modem->producto;
         		  $nombre_formato="aviso_cancelacion";   
                 $html = view('formatos/aviso_cancelacion',$data);
         		break;		
         	case '5':
         		 $nombre_formato="cancelacion";  
                 $html = view('formatos/cancelacion',$data);
         		break;	
         	case '6':
         		$this->show_cctv_terminos();
         		break;
            case '7':
                 $nombre_formato="contrato";
                 $row_cliente=$this->model_formatos->get_cliente($id_cliente);
                 $f_sucursal = $this->model_sucursal->get_sucursal($row_cliente->sucursal);
                 $mpdf = new \Mpdf\Mpdf(['format'=>'A4','orientation' => 'P']);
                 $row_cliente->f_anio=Date('Y');
                 $row_cliente->f_mes=Date('m');
                 $row_cliente->f_dia=Date('d');
                 $row_cliente->persona=$new_contrato_select;
                 $row_cliente->rfc=$rfc;
                 $datos['datos']=$row_cliente; 
                 $datos['f_sucursal'] = $f_sucursal[0];
                
                 $html = view('formatos/contrato_nuevo',$datos);
                break;

            case '8':
                 $en_letras=new Class_cantidad_en_letras(); 
                 $row_facturas=$this->model_formatos->get_facturas_by_cliente($id_cliente);
                 $facturas=$this->model_formatos->get_facturas_by_cliente2($id_cliente);
                 
                 $data['total']=$row_facturas->total;
                 $data['facturas']=$facturas;
                 $data['en_letras']=$en_letras->ValorEnLetras($row_facturas->total, "Pesos"); 
                 $html=view('formatos/carta_adeudo',$data);
   
                 
                break;   

             case '9':
                
                 $this->show_hoja_servicio();
   
                break;
              case '10':
                
                 $this->show_hoja_levantamiento_camaras();
   
                break;

             case '11':
               //  $en_letras=new Class_cantidad_en_letras(); 
               //  $row_facturas=$this->model_formatos->get_facturas_by_cliente($id_cliente);
               //  $facturas=$this->model_formatos->get_facturas_by_cliente2($id_cliente);
                 
                // $data['total']=$row_facturas->total;
                // $data['facturas']=$facturas;
                // $data['en_letras']=$en_letras->ValorEnLetras($row_facturas->total, "Pesos");
                 $row_cliente=$this->model_formatos->get_cliente($id_cliente);

                 $row_cliente_install=$this->model_formatos->get_cliente_install($id_cliente);
                 $notas="";$tecnico="";
                 if($row_cliente_install){
                    $notas=$row_cliente_install->notas;
                    $tecnico=$row_cliente_install->nombre;
                 }
                 $data['id']=$id_cliente;
                 $data['nombre']=$row_cliente->nombre;
                 $data['cedula']=$row_cliente->cedula;
         	     $data['direccion']=$row_cliente->direccion_principal;
         	     $data['telefono']=$row_cliente->telefono;
         	     $data['movil']=$row_cliente->movil;
         	     $data['correo']=$row_cliente->correo;
                 $data['folio_servicio']=$row_cliente->id;
                 $data['password']=$row_cliente->codigo;
                 $data['plan']=$row_cliente->plan;
         	     $data['costo']=$row_cliente->costo;
         	     $data['servicios']=$this->model_formatos->get_servicios_cliente($id_cliente);
         	     $data['materiales']=$this->model_formatos->get_materiales_cliente($id_cliente);
         	     $data['accesorios']=$this->model_formatos->get_accesorios_cliente($id_cliente);
         	     $data['notas']= nl2br($notas);
         	     $data['tecnico']= $tecnico;

                 $mpdf = new \Mpdf\Mpdf(['format'=>'A4','orientation' => 'P']);
                 $mpdf->curlAllowUnsafeSslRequests = true;
                 $mpdf->debug = true;  
             
                 $html=view('formatos/hoja_instalacion_unnet',$data);
   
                 
                break;   
       
                		
         	
         	default:
         		# code...
         		break;
         }

       
        
        //si el pdf se guarda correctamente lo mostramos en pantalla
             
                 $mpdf->WriteHTML($html);
                 $mpdf->Output("./files/pdfs/".$nombre_formato.".pdf","F"); // opens in browser
                 $this->show($nombre_formato.".pdf");



          }else echo "ACCESO DENEGADO." ;
        }else echo "ACCESO DENEGADO.";  
        
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

       public function show_cctv_terminos($file="cctv-terminos.pdf")
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

    public function show_hoja_servicio($file="hoja_servicio.pdf")
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

       public function show_hoja_levantamiento_camaras($file="levantamiento_camaras.pdf")
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



      private function createFolder()
    {
        if(!is_dir("./files"))
        {
            mkdir("./files", 0777);
            mkdir("./files/pdfs", 0777);
        }
    }

  public function get_tickets_json(){
    $model_formatos=new Model_formatos();
    $all_items = $model_formatos->getAll_tickets();
    $array_tickets=array();  
    if($all_items){   
  
      foreach ($all_items as $items) {
      
         $array_item=array(
        "id"=>$items->id,
        "departamento"=>'<span style="color:'.$items->colord.';border: 1px solid '.$items->colord.';padding: 2px 5px;border-radius: 5px;text-transform: uppercase;font-size: 9.5px;font-weight: 700;">'.strtoupper($items->departamento).'</span>',
        "nombre"=>$items->nombre,
        "asunto"=>$items->asunto,
       "fecha"=>date_format(date_create($items->fecha_soporte), 'd/m/Y H:i:s a'),
        "estado"=>'<span class="label label-danger">ABIERTO</span>',
        "login"=>$items->operador
          ); 
        array_push($array_tickets, $array_item);
      }
    }  echo '{"data":'. json_encode($array_tickets).'}';
 }

public function mes($mes)
{
	switch ($mes) {
		case "01":
			return "Enero";
			break;
		case "02":
			return "Febrero";
			break;
		case "03":
			return "Marzo";
			break;
		case "04":
			return "Abril";
			break;
		case "05":
			return "Mayo";
			break;
		case "06":
			return "Junio";
			break;
		case "07":
			return "Julio";
			break;	
		case "08":
			return "Agosto";
			break;	
		case "09":
			return "Septiembre";
			break;	
		case "10":
			return "Octubre";
			break;	
		case "11":
			return "Noviembre";
			break;
		case "12":
			return "Diciembre";
			break;													
		
		default:
			# code...
			break;
	}
}


}