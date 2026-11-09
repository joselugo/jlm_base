<?php namespace App\Controllers;
use App\Libraries\Class_seguridad;
use App\Libraries\Contrasena;
use App\Libraries\Class_permiso_sucursal;
use App\Models\Model_api;
use App\Models\Model_selectores;
use App\Models\Model_log;
use SMSGatewayMe\Client\ApiClient;
use SMSGatewayMe\Client\Configuration;
use SMSGatewayMe\Client\Api\MessageApi;
use SMSGatewayMe\Client\Model\SendMessageRequest;
use PEAR2\Net\RouterOS;

class Api extends BaseController
{
  var $model_api;
  var $model_selectores;
  var $permiso_sindicato;
  var $seguridad;
  var $nmodel_log;
  public function __construct()
  {
    $this->model_api = new Model_api();
    $this->model_selectores = new Model_selectores();
    $this->model_log = new Model_log();
    $this->permiso_sindicato = new Class_permiso_sucursal();
    $this->seguridad = new Class_seguridad();
    session();
  }

  public function index()
  {
    if (isset($_SESSION['id_usuario8291'])) {

      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_UNIDADES'));
      if ($tipoacceso > 0) {


        $data['tipoacceso'] = $tipoacceso;
        echo view('unidades/lista_unidades/index', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  protected function uroboros()
  {

      $addresslist=array();
      try {
        $client = new RouterOS\Client(config('Integraciones')->mikrotikHost, config('Integraciones')->mikrotikUser, config('Integraciones')->mikrotikPass, config('Integraciones')->mikrotikPort);
      } catch (\Throwable $th) {
        echo 2;
      }
      $util = new RouterOS\Util($client);
      $util->setMenu('/queue simple');
      $items= $util->getAll();
    /*  if($items){
         $util->setMenu('/queue simple');
        for ($i=0; $i <count($items) ; $i++) {

        $ip = $items[$i]->getProperty('remote-address');
        $comment= $items[$i]->getProperty('comment');
        $profile= $items[$i]->getProperty('profile');
        $profile=$profile[0];
        $queue="pcq-upload-default/pcq-download-default";
        if($profile==1){$profile="10M"; $burst_limit="20M/20M";$burst_threshold="5M/5M";$burst_time="120/120";}
        if($profile==2){$profile="20M"; $burst_limit="0/0";$burst_threshold="0/0";$burst_time="0/0";}
        if($profile==3){$profile="30M"; $burst_limit="0/0";$burst_threshold="0/0";$burst_time="0/0";}
        if($profile==5){$profile="5M";  $burst_limit="10M/10M";$burst_threshold="2M/2M";$burst_time="120/120";}

            $data_queue = array(
              'name' => $comment,
              'target' => $ip,
              'queue'=>$queue,
              'max-limit' => $profile."/".$profile,
              'burst-limit' => $burst_limit,
              'burst-threshold' => $burst_threshold,
              'burst-time' => $burst_time,
            );
       // $util->add($data_queue); 
       
          $addresslist[] = [
              'name' => $comment,
              'target' => $ip,
              'max-limit' => $profile."/".$profile,
              'burst-limit' => $burst_limit,
              'burst-threshold' => $burst_threshold,
              'burst-time' => $burst_time,
              'queue'=>$queue,
        ]; 
        
       } ///fim for

      } //fin if items */

      print_r($items);
   
  }


  public function get_unidades_json()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_UNIDADES'));
      if ($tipoacceso > 0) {

        $permiso_sindicato = $this->permiso_sindicato->get_permiso_socios($_SESSION['sindicatos']);
        $estado = $this->request->getGet('estado');
        if ($estado == "TODOS") {
          $unidades = $this->model_unidades->get_all_unidades($permiso_sindicato);
        } else {
          $unidades = $this->model_unidades->get_unidades($estado, $permiso_sindicato);
        }


        $array_cliente = array();
        $estado = "";
        $tool = "";
        $status = "";
        $modounidad = "";
        if ($unidades) {
          foreach ($unidades as $items) {
            if ($items->estado == "ACTIVO") {
              $status = "<span class='badge badge-primary'>" . $items->estado . "</span>";
            } else if ($items->estado == "SUSPENDIDO") {
              $status = "<span class='badge badge-warning'>" . $items->estado . "</span>";
            } else {
              $status = "<span class='badge badge-danger'>" . $items->estado . "</span>";
            }

            if ($items->modounidad == "PROPIO") {
              $modounidad = "<span class='badge badge-success'>" . $items->modounidad . "</span>";
            } else {
              $modounidad = "<span class='badge badge-success'>" . $items->modounidad . "</span>";
            }

            if ($tipoacceso == 1) {
              $tool = '';
            }
            if ($tipoacceso == 2) {
              $tool = '<td class=" text-center"><button onclick="edit_unidad(\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\')" data-toggle="tooltip" title="Editar" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></button></td';
            }
            if ($tipoacceso == 3) {
              $tool = '<td class=" text-center"><button onclick="edit_unidad(\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\')" data-toggle="tooltip" title="Editar" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></button>
            <a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_unidad(\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $items->tipounidad . " " . $items->matricula . '\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a></td>';
            }



            $array_item = array(
              "id" => $items->id,
              "tipo" => '<a href="#unidades/view?id=' . $items->id . '&amp;token=' . $_SESSION['token8291'] . '"  >' . $items->tipounidad . '</a>',
              "matricula" => $items->matricula,
              "socio" => '<a href="#socios/view?id=' . $items->id_socio . '&amp;token=' . $_SESSION['token8291'] . '"  >' . $items->nombre . ' ' . $items->apellidos . '</a>',
              "codigo" => $items->codigosindicato,
              "fechaalta" => $items->fechaalta,
              "modounidad" => $modounidad,
              "estado" => $status,
              "descripcion" => $items->descripcion,
              "tool" => $tool,
              "sindicato" => $items->sindicato,
              "tipoacceso" => $tipoacceso
            );
            array_push($array_cliente, $array_item);
          }
        }
        echo '{"data":' . json_encode($array_cliente) . '}';
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function new_unidad()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {

        $idsocio = $this->request->getPost('idsocio');
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
          'idsocio' => $idsocio, 'tipounidad' => $tipounidad, 'matricula' => $matricula, 'factura' => $factura, 'descripcion' => $descripcion,
          'fechaalta' => date("Y-m-d H:i:s"), 'modounidad' => $modounidad, 'sindicato' => $sindicato, 'codigosindicato' => $codigosindicato,
          'foto' => $base64
        ];

        $true_matricula = $this->model_unidades->check_matricula($matricula);
        if ($true_matricula) {

          echo "matricula-duplicada";
        } else {

          $insert = $this->model_unidades->new_unidad($arrayName);
          if ($insert) {
            echo 1;
            $row = $this->model_unidades->get_ultima_unidad();
            $insert_log = $this->model_log->insert_log("unidad", "Nueva unidad agregada <b>#" . $row->ultimo . " Matricula:" . $matricula . "</b>", $_SESSION['nombre']);
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
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_UNIDADES'));
      if ($tipoacceso > 0) {
        $arrayName = array();
        $permiso_sindicato = $this->permiso_sindicato->get_permiso_socios($_SESSION['sindicatos']);
        $id_unidad = $this->request->getPost('id');
        $row = $this->model_unidades->get_unidad_by_id($id_unidad, $permiso_sindicato);
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

  public function update_unidad()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_UNIDADES'));
      if ($tipoacceso > 0) {
        $id = $this->request->getPost('idunidad-edit');
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





        $update = $this->model_unidades->update_unidad($id, $arrayName);
        if ($update) {
          $insert_log = $this->model_log->insert_log("unidad", "Unidad editada <b>#" . $id . " Matrícula" . $matricula . "</b>", $_SESSION['nombre']);
          echo 1;
        } else {
          echo 2;
        }
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function delete_unidad()
  {

    if (isset($_SESSION['id_usuario8291']) && $this->request->getPost('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_UNIDADES'));
      if ($tipoacceso > 0) {

        $id = $this->request->getPost('id');
        $matricula = $this->request->getPost('matricula');
        $insert_log = $this->model_log->insert_log("socios", "Unidad eliminada <b>#" . $id . " " . $matricula . "</b>", $_SESSION['nombre']);
        $delete = $this->model_unidades->delete_unidad($id);
        if ($delete) echo "succes";
        else echo "error";
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function get_codigo()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_UNIDADES'));
      if ($tipoacceso > 0) {

        $sindicato = $this->request->getPost('id');
        $max_codigo = $this->model_unidades->get_max_codigo($sindicato);
        if ($max_codigo) {
          echo $max_codigo->ultimo + 1;
        } else {
          echo $sindicato . '0001';
        }
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }



  public function get_permisos_rol()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('GESTION_PERSONAL'));
      if ($tipoacceso > 0) {
        $id_rol = $this->request->getPost('id_rol');
        $rol_permisos = $this->model_sistema->get_permisos_rol($id_rol);
        echo json_encode($rol_permisos);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function update_perfil()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('GESTION_PERSONAL'));
      if ($tipoacceso > 0) {
        $id = $this->request->getPost('id');
        $nombre = $this->request->getPost('nombre');
        $username = $this->request->getPost('username');
        $correo = $this->request->getPost('correo');
        $movil = $this->request->getPost('movil');
        $rol = $this->request->getPost('rol');
        $estado = $this->request->getPost('estado');
        $password = $this->request->getPost('newpassword');
        $sindicatos = (array) $this->request->getPost('sindicatos'); // sin selección llega null
        $cadena_sindicatos = "";
        if (($sindicatos[0] ?? '') == "%%") {
          $cadena_sindicatos = "%%";
        } else {
          foreach ($sindicatos as $items) {
            $cadena_sindicatos .= $items . ",";
          }
        }
        if ($password == "") {
          $arrayName = [
            'nombre' => $nombre, 'username' => $username, 'correo' => $correo, 'movil' => $movil,
            'rol' => $rol, 'estado' => $estado, 'sindicato' => $cadena_sindicatos
          ];
        } else {
          $arrayName = [
            'nombre' => $nombre, 'username' => $username, 'correo' => $correo, 'movil' => $movil,
            'rol' => $rol, 'estado' => $estado, 'password' => Contrasena::hash((string) $password), 'sindicato' => $cadena_sindicatos
          ];
        }

        $update = $this->model_sistema->update_perfil($id, $arrayName);
        if ($update) {
          echo 1;
        } else {
          echo 2;
        }
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function update_permisos_tabla()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('GESTION_PERSONAL'));
      if ($tipoacceso > 0) {

        $listaPermisos = json_decode($this->request->getPost('permisos'));
        $id_usuario = $this->request->getPost('id_usuario');
        $delete = $this->model_sistema->delete_permiso_by_usuario($id_usuario);

        if ($delete) {
          foreach ($listaPermisos as $items) {
            $insert = $this->model_sistema->insert_permiso_by_usuario($items->id_permiso, $items->id_usuario, $items->tipoacceso);
          }
          echo 1;
        } else echo 2;
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function new_operador()
  {
    if (isset($_SESSION['id_usuario8291']) && isset($_SESSION['token8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('GESTION_PERSONAL'));
      if ($tipoacceso > 0) {


        /////////////////////////////7 	

        $permisos = $this->model_sistema->traer_permisos_sistema(0);
        $cadena = "";
        foreach ($permisos as $items) {
          $cadena .= '<div id="accordion-permisos" class="card-accordion">
                   <div class="card">
                   <div class="card-header bg-blue text-white pointer-cursor collapsed" data-toggle="collapse" data-target="#p' . $items->id_permiso . '">' . $items->modulo . '<small class="pull-right h-' . $items->id_permiso . '"><b>Sin submenú</b></small></div>
                   <div id="p' . $items->id_permiso . '" class="collapse" data-parent="#accordion-permisos">
                   <div class="card-body border">';
          $cadena .= '<span class="contenedores">';
          $check_hijos = $this->model_sistema->check_hijos($items->id_permiso);
          if ($check_hijos) {
            $cadena .= '<span style="width: 100%; display: block; text-align: center">
                   <button class="btn btn-link" type="button" onclick="selectgroup(\'.group-' . $items->id_permiso . '\',false);"><i class="fas fa-times"></i> Quitar todos</button>
                   <button class="btn btn-link" type="button" onclick="selectgroup(\'.group-' . $items->id_permiso . '\',true);"><i class="fas fa-check"></i> Seleccionar todos</button>
                   </span>';
            $cadena .= '<br><label class="col-sm-4"><strong>Acceso Menú</strong></label>';
            $cadena .= '<input type="checkbox" value="' . $items->id_permiso . '" class="chk acc0 group-' . $items->id_permiso . '" />&nbsp;&nbsp;<br>';
            $cadena .= '<select style="border-radius:5px; display:none" class="slc" data-id="' . $items->id_permiso . '">
                  <option value="1">Lectura</option>
                  <option value="2">Lec & Escritura</option>
                  <option value="3">Eliminar</option></select>';
          } else {

            $cadena .= '<br><label class="col-sm-3"><strong>Acceso Menú</strong></label>';
            $cadena .= '<input type="checkbox" value="' . $items->id_permiso . '" class="chk acc0 group-' . $items->id_permiso . '" />&nbsp;&nbsp;';
            $cadena .= '<select style="border-radius:5px;" class="slc" data-id="' . $items->id_permiso . '">
                     <option value="1">Lectura</option>
                     <option value="2">Lec & Escritura</option>
                     <option value="3">Eliminar</option></select><br>';
          }
          $cadena .= '</span">';



          $permisos1 = $this->model_sistema->traer_permisos_sistema($items->id_permiso);

          foreach ($permisos1 as $items1) {
            $cadena .= '<span class="contenedores">';
            $cadena .= '<label class="col-sm-4"><strong>' . $items1->modulo . '</strong></label>';
            $cadena .= '<input type="checkbox" value="' . $items1->id_permiso . '" class="chk acc0 group-' . $items->id_permiso . '" />&nbsp;&nbsp;';
            $cadena .= '<select style="border-radius:5px;" class="slc" data-id="' . $items1->id_permiso . '">
                                   <option value="1">Lectura</option>
                                   <option value="2">Lec & Escritura</option>
                                   <option value="3">Eliminar</option></select><br>';
            $cadena .= '</span>';
          }


          //////////////////////////////PERMISOS ESPECIALES

          $permisos2 = $this->model_sistema->traer_permisos_especiales_sistema($items->id_permiso);
          if ($permisos2) {
            $cadena .= '<br><label class="col-sm-12">Permisos especiales -----------------------------------------------------------------------------------------</label><br>';
          }

          foreach ($permisos2 as $items2) {

            $cadena .= '<span class="contenedores">';
            $cadena .= '<label class="col-sm-4"><strong>' . $items2->modulo . '</strong></label>';
            $cadena .= '<input type="checkbox" value="' . $items2->id_permiso . '" class="chk acc0 group-' . $items->id_permiso . '" />&nbsp;&nbsp;';
            $cadena .= '<select style="border-radius:5px;display:none" class="slc" data-id="' . $items2->id_permiso . '">
                                   <option value="1">Lectura</option>
                                   <option value="2">Lec & Escritura</option>
                                   <option value="3">Eliminar</option></select><br>';
            $cadena .= '</span>';
          }

          $cadena .= '</div></div></div></div>';
        }

        ///////////////////////////



        $data['sindicatos'] = $this->model_selectores->get_all_sindicatos();
        $data['roles'] = $this->model_selectores->get_all_roles();
        $data['permisos'] = $cadena;
        $data['tipoacceso'] = $tipoacceso;
        echo view('sistema/gestion_personal/new', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function new_perfil()
  {

    if (isset($_SESSION['id_usuario8291']) && isset($_SESSION['token8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('GESTION_PERSONAL'));
      if ($tipoacceso > 0) {

        $nombre = $this->request->getPost('nombre');
        $username = $this->request->getPost('username');
        $correo = $this->request->getPost('correo');
        $movil = $this->request->getPost('movil');
        $rol = $this->request->getPost('rol');
        $estado = $this->request->getPost('estado');
        $password = $this->request->getPost('newpassword');
        $sindicatos = (array) $this->request->getPost('sindicatos'); // sin selección llega null
        $cadena_sindicatos = "";
        if (($sindicatos[0] ?? '') == "%%") {
          $cadena_sindicatos = "%%";
        } else {
          foreach ($sindicatos as $items) {
            $cadena_sindicatos .= $items . ",";
          }
        }
        $arrayName = [
          'nombre' => $nombre, 'username' => $username, 'correo' => $correo, 'movil' => $movil,
          'rol' => $rol, 'estado' => $estado, 'password' => Contrasena::hash((string) $password), 'sindicato' => $cadena_sindicatos
        ];

        $insert = $this->model_sistema->new_perfil($arrayName);
        if ($insert) {
          echo 1;
        } else {
          echo 2;
        }
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function insert_permisos_tabla()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('GESTION_PERSONAL'));
      if ($tipoacceso > 0) {

        $listaPermisos = json_decode($this->request->getPost('permisos'));
        $id_usuario = $this->model_sistema->get_last_login();

        foreach ($listaPermisos as $items) {
          $insert = $this->model_sistema->insert_permiso_by_usuario($items->id_permiso, $id_usuario->ultimo, $items->tipoacceso);
        }
        echo 1;
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function delete_operador()
  {

    if (isset($_SESSION['id_usuario8291']) && $this->request->getPost('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('GESTION_PERSONAL'));
      if ($tipoacceso > 0) {

        $id = $this->request->getPost('id');
        $delete = $this->model_sistema->delete_operador($id);
        if ($delete) echo "succes";
        else echo "error";
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
}
