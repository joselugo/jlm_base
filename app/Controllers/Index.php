<?php namespace App\Controllers;

use App\Libraries\Class_seguridad;
use App\Libraries\converter;
use App\Models\Model_log;
use App\Models\Model_sistema;
use App\Models\Model_dashboard;
use App\Models\Model_configuracion;

class Index extends BaseController {
	var $seguridad;
	var $model_log;
	var $model_dashboard;
	var $model_sistema;
        var $converter;

public function __construct(){
	$this->seguridad = new Class_seguridad();
	$this->model_log=new Model_log();
	$this->model_sistema = new Model_sistema();
	$this->model_dashboard = new Model_dashboard();
	date_default_timezone_set('America/Mexico_City');
	$this->converter = new converter();
  $this->model_configuracion = new Model_configuracion();
	session();
	}


	public function index()
	{		
		if( isset($_SESSION['id_usuario8291']) ){
      $ids = array(44, 64);
      $config = $this->model_configuracion->get_configuracion_name($ids);
      if ($config) {
       $data['menu'] = $this->seguridad->pintar_menu($_SESSION['id_usuario8291']);
			 $data['google_api']=$config["keyapigoogle"];
			 $data['img_slider']=$config["imgloginadmin"];
			 echo view('inicio', $data);
      }else{
        echo "Error la api Google no existe.";
      }
		
			
		}
		else{
      
			$this->seguridad->login_back();
		}
	}

  public function home()
  {
    if (isset($_SESSION['id_usuario8291'])) {


      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('HOME'));
      $check = $this->model_sistema->get_check($_SESSION['id_usuario8291']);
      $permisos = [
        "WIDGET CLIENTES_ONLINE" => false,
        "WIDGET TRANSACCIONES" => false,
        "WIDGET FACTURAS NO PAGADAS" => false,
        "WIDGET TICKETS SOPORTE" => false,
        "WIDGET TRAFICO DE CLIENTES" => false,
        "WIDGET RESUMEN DEL SISTEMA" => false,
        "WIDGET ULTIMOS PAGOS" => false,
        "WIDGET ULTIMOS CLIENTES CONECTADOS" => false,
        "WIDGET DATOS DEL SERVIDOR" => false,
        "WIDGET RESUMEN DE FACTURACION" => false,
        "WIDGET EMISORES" => false
      ];
      $permiso_dashboard = false;
      foreach ($permisos as $key => $value) {
        $permiso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso("$key"));

        if ($permiso != 0) {
          $permiso_dashboard = true;
          $permisos[$key] = true;
        }
      }

      if ($permiso_dashboard and !$check) {
        $hoy = date('d-m-Y');
        $emisores_body = "";
        $usuario = $this->model_dashboard->get_nombre_user($_SESSION['id_usuario8291']);
        $emisores = $this->model_dashboard->get_emisores();
        $data_grafico = array();
        $fechas = array();
        for ($i = 6; $i >= 0; $i--) {
          $fechas[] = date('Y-m-d', strtotime($hoy . "- $i days"));
        }

        if ($usuario) {
          $data['nombre'] = $usuario->username;
          if ($emisores) {
            foreach ($emisores as $value) {
              $id = $value->id;
              $nombre = $value->nombre;
              $ip = $value->ip;
              $equipo = $value->equipo;
              $estado = $value->estado_netium == 1 ? 'EN LINEA' : 'DESCONECTADO';
              $style = $value->estado_netium == 1 ? 'success' : 'danger';
              $emisores_body .= "
            <tr>
              <td tabindex=\"0\"><a href=\"#gestion_red/editar_emisor?idemisor=$id\" data-toggle=\"ajax\" style=\"color:#333\">$nombre</a></td>
              <td style=\"\">$equipo</td>
              <td style=\"\"><a href=\"http://$ip\" target=\"_Blank\">$ip</a></td>
              <td class=\"sorting_1\"><span class=\"label label-pill label-$style\">$estado</span></td>
              <td><a style=\"margin-right: 5px;\" data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"getmodal('gestion_red/modal_grafica_ping?idemisor=$id','Grafica','lg')\" data-original-title=\"Gr�fico ping\"><i class=\"fas fa-chart-bar\"></i></a></td>
            </tr>";
            }
            $data['emisores'] = $emisores_body;
          } else {
            $data['emisores'] = array();
          }
          $data['permisos'] = $permisos;

          return view('dashboard/home', $data);
        } else {
          echo "No existe tu nombre de usuario en la base de datos.";
        }
      }
      if ($check) {
        $data = [
          "check_cumple_dia" => 1
        ];
        $cumpleanios = $this->model_sistema->get_cumpleanios(date('m'), date('d'));
        $result_update = $this->model_sistema->update_check_cumpleanios($_SESSION['id_usuario8291'], $data);
        if ($cumpleanios) {
          $nombres = "";
          foreach ($cumpleanios as $key => $personal) {
            if ($key == 0) {
              $nombres .= $personal->nombre;
            } else {
              $nombres .= "," . $personal->nombre;
            }
          }
          $data['cumpleanios'] = $nombres;
        }
        if ($tipoacceso > 0) {
          $data['tipoacceso'] = $tipoacceso;
          if ($cumpleanios) {
            echo view('cumpleanios', $data);
          } else {
            echo view('content', $data);
          }
        } else echo acceso_denegado();
      } else {
        if ($tipoacceso > 0) {
          $data['tipoacceso'] = $tipoacceso;
          echo view('content', $data);
        } else echo acceso_denegado();
      }
    } else $this->seguridad->login_back();
  }
	public function get_log_json(){
		
		if( isset($_SESSION['id_usuario8291']) ){
			$tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('HOME'));
			if( $tipoacceso > 0 ){
		    $log=$this->model_log->get_all_log();                  

            $array_cliente=array();
            if($log){
            foreach ($log as $items) {
        
        $array_item=array(
        "id"=>$items->id,
        "log"=>$items->log,
        "user"=>$items->usuario,
        "fecha"=>$items->fechalog
          ); 
        array_push($array_cliente, $array_item);
      }
     }  echo '{"data":'. json_encode($array_cliente).'}';
                       
                       
		    }
        else echo acceso_denegado();  
		}
		else $this->seguridad->login_back();
	}

	protected function mib(){
	  
         $response = shell_exec("/var/www/html/cron_finalizados_netium/telnet.sh");
        //Response of the nslookup
        //echo nl2br($response);
        echo "<div style='background:black; color:white'>".nl2br($response)."<div>";





}


}