<?php

namespace App\Controllers;


use App\Models\UsuarioModel;
use App\Libraries\Class_seguridad;
use App\Libraries\Contrasena;
use App\Models\Model_sistema;
use App\Models\Model_selectores;
use App\Models\Model_log;
use App\Libraries\Class_permiso_sucursal;

class Sistema extends BaseController
{
  var $model_sistema;
  var $model_selectores;
  var $model_log;
  var $seguridad;
  var $permiso_sucursal;
  var $permiso_nodo;
  public function __construct()
  {
    $this->model_sistema = new Model_sistema();
    $this->seguridad = new Class_seguridad();
    $this->model_selectores = new Model_selectores();
    $this->model_log = new Model_log();
    $this->permiso_sucursal = new Class_permiso_sucursal();
    $this->permiso_nodo = new Class_permiso_sucursal();
    session();
  }

  public function gestion_personal()
  {


    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();

      // $permiso_sucursal = new Class_permiso_nodo();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('GESTION_PERSONAL'));
      if ($tipoacceso > 0) {

        $data['tipoacceso'] = $tipoacceso;
        echo view('sistema/gestion_personal/index', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function get_personal_json()
  {

    if (isset($_SESSION['id_usuario8291'])) {

      $permiso_sucursal = new Class_permiso_sucursal();
      $where_permiso_sucursal_login=$permiso_sucursal->get_permiso_sucursal_login($_SESSION['sucursales']);
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('GESTION_PERSONAL'));
      $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal_gestion($_SESSION['sucursales']);

      if ($tipoacceso > 0) {

        // DataTables pinta estos valores como HTML: se escapan (antes iban tal cual)
        $token = $_SESSION['token8291'];
        $filas = [];
        foreach ((new UsuarioModel())->listado($where_permiso_sucursal_login) as $u) {
          $estado = $u->habilitado
            ? '<span class="label label-success">HABILITADO</span>'
            : '<span class="label label-danger">DESHABILITADO</span>';
          $tool = '<td class=" text-center"><a href="#sistema/edit_personal?id=' . $u->id . '&amp;token=' . esc($token, 'attr') . '" data-toggle="tooltip" title="Editar" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a><a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_operador(\'' . $u->id . '\',\'' . esc($token, 'js') . '\',\'' . esc($u->nombre, 'js') . '\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a></td>';
          $filas[] = [
            "id"       => $u->id,
            "nombre"   => esc($u->nombre),
            "username" => esc($u->username),
            "sucursal" => esc($u->sucursal),
            "correo"   => esc($u->correo),
            "rol"      => esc($u->rol),
            "estado"   => $estado,
            "tool"     => $tool,
          ];
        }
        echo json_encode(['data' => $filas]);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function edit_personal()
  {
    if (isset($_SESSION['id_usuario8291']) && $this->request->getGet('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      // $permiso_sucursal = new Class_permiso_nodo();
       $permiso_sucursal = new Class_permiso_sucursal();
      $where_permiso_sucursal_select = $permiso_sucursal->get_permiso_sucursal_select($_SESSION['sucursales']);
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('GESTION_PERSONAL'));
      if ($tipoacceso > 0) {
        $id = $this->request->getGet('id');
        $row = $this->model_sistema->traer_usuarios_by_id($id);
        $data['id'] = $row->id;
        $data['nombre'] = $row->nombre;
        $data['username'] = $row->username;
        $data['rol'] = $row->rol;
        $data['correo'] = $row->correo;
        $data['movil'] = $row->movil;
        $data['estado'] = $row->estado;
        $data['sucursal'] = $row->sucursal;
        $data['sucursal_origen'] = $row->sucursal_origen;
        $data['sucursales'] = $this->model_selectores->get_all_sucursales();
        $data['sucursales_by_user'] =$this->model_selectores->get_all_sucursales_by_user($where_permiso_sucursal_select);
        $data['roles'] = $this->model_selectores->get_all_roles();

        $permisos = $this->model_sistema->traer_permisos_sistema(0);
        $widgets =  $this->model_sistema->traer_widgets(3);
        $cadena = "";
        foreach ($permisos as $items) {
          $cadena .= '<div id="accordion-permisos" class="card-accordion">
                   <div class="card">
                   <div class="card-header bg-blue text-white pointer-cursor collapsed" data-toggle="collapse" data-target="#p' . $items->id_permiso . '">' . $items->modulo . '<small class="pull-right h-' . $items->id_permiso . '"><b>Sin submenú</b></small></div>
                   <div id="p' . $items->id_permiso . '" class="collapse" data-parent="#accordion-permisos">
                   <div class="card-body border">';
          $cadena .= '<span class="contenedores">';
          $true_permiso = $this->model_sistema->check_permiso_by_id($id, $items->id_permiso); /////check si tiene permiso de padre          
          $check_hijos = $this->model_sistema->check_hijos($items->id_permiso);
          if ($check_hijos) {
            $cadena .= '<span style="width: 100%; display: block; text-align: center">
                   <button class="btn btn-link" type="button" onclick="selectgroup(\'.group-' . $items->id_permiso . '\',false);"><i class="fas fa-times"></i> Quitar todos</button>
                   <button class="btn btn-link" type="button" onclick="selectgroup(\'.group-' . $items->id_permiso . '\',true);"><i class="fas fa-check"></i> Seleccionar todos</button>
                   </span>';

            $cadena .= '<br><label class="col-sm-4"><strong>Acceso Menú</strong></label>';
            if ($true_permiso) { ///AGREGAMOS UN SELECT HIDDEN PARA QUE EL SISTEMA NO TOME COMO UNDEFINIDO CUANDO TIENE HIJOS
              $cadena .= '<input type="checkbox" value="' . $items->id_permiso . '" checked class="chk acc0 group-' . $items->id_permiso . '" />&nbsp;&nbsp;<br>';
              $cadena .= '<select style="border-radius:5px; display:none" class="slc" data-id="' . $items->id_permiso . '">
                                                   <option value="1">Lectura</option>
                                                   <option value="2">Lec & Escritura</option>
                                                   <option value="3">Eliminar</option></select>';
            } else {
              $cadena .= '<input type="checkbox" value="' . $items->id_permiso . '" class="chk acc0 group-' . $items->id_permiso . '" />&nbsp;&nbsp;<br>';
              $cadena .= '<select style="border-radius:5px;display:none" class="slc" data-id="' . $items->id_permiso . '">
                                                   <option value="1">Lectura</option>
                                                   <option value="2">Lec & Escritura</option>
                                                   <option value="3">Eliminar</option></select>';
            }
          } else {

            $cadena .= '<br><label class="col-sm-3"><strong>Acceso Menú</strong></label>';
            if ($true_permiso) {
              $cadena .= '<input type="checkbox" value="' . $items->id_permiso . '" checked class="chk acc0 group-' . $items->id_permiso . '" />&nbsp;&nbsp;';
            } else {
              $cadena .= '<input type="checkbox" value="' . $items->id_permiso . '" class="chk acc0 group-' . $items->id_permiso . '" />&nbsp;&nbsp;';
            }
            $tipo_permiso_padre = $this->model_sistema->check_tipo_permiso_by_id($id, $items->id_permiso); ////// traer permiso select del padre	
            if ($tipo_permiso_padre) {
              if ($tipo_permiso_padre->tipo_acceso == 1) {
                $cadena .= '<select style="border-radius:5px;" class="slc" data-id="' . $items->id_permiso . '">
                                   <option value="1" selected>Lectura</option>
                                   <option value="2">Lec & Escritura</option>
                                   <option value="3">Eliminar</option></select><br>';
              } else if ($tipo_permiso_padre->tipo_acceso == 2) {
                $cadena .= '<select style="border-radius:5px;" class="slc" data-id="' . $items->id_permiso . '">
                                   <option value="1">Lectura</option>
                                   <option value="2" selected>Lec & Escritura</option>
                                   <option value="3">Eliminar</option></select><br>';
              } else {
                $cadena .= '<select style="border-radius:5px;" class="slc" data-id="' . $items->id_permiso . '">
                                   <option value="1">Lectura</option>
                                   <option value="2">Lec & Escritura</option>
                                   <option value="3" selected>Eliminar</option></select><br>';
              }
            } else {
              $cadena .= '<select style="border-radius:5px;" class="slc" data-id="' . $items->id_permiso . '">
                                   <option value="1">Lectura</option>
                                   <option value="2">Lec & Escritura</option>
                                   <option value="3">Eliminar</option></select><br>';
            }
          }
          $cadena .= '</span">';



          $permisos1 = $this->model_sistema->traer_permisos_sistema($items->id_permiso);

          foreach ($permisos1 as $items1) {
            $true_permiso = $this->model_sistema->check_permiso_by_id($id, $items1->id_permiso);
            $cadena .= '<span class="contenedores">';
            $cadena .= '<label class="col-sm-4"><strong>' . $items1->modulo . '</strong></label>';
            if ($true_permiso) {
              $cadena .= '<input type="checkbox" value="' . $items1->id_permiso . '" checked class="chk acc0 group-' . $items->id_permiso . '" />&nbsp;&nbsp;';
            } else {
              $cadena .= '<input type="checkbox" value="' . $items1->id_permiso . '" class="chk acc0 group-' . $items->id_permiso . '" />&nbsp;&nbsp;';
            }
            $tipo_permiso = $this->model_sistema->check_tipo_permiso_by_id($id, $items1->id_permiso);
            if ($tipo_permiso) {

              if ($tipo_permiso->tipo_acceso == 1) {
                $cadena .= '<select style="border-radius:5px;" class="slc" data-id="' . $items1->id_permiso . '">
                                   <option value="1" selected>Lectura</option>
                                   <option value="2">Lec & Escritura</option>
                                   <option value="3">Eliminar</option></select><br>';
              } else if ($tipo_permiso->tipo_acceso == 2) {
                $cadena .= '<select style="border-radius:5px;" class="slc" data-id="' . $items1->id_permiso . '">
                                   <option value="1">Lectura</option>
                                   <option value="2" selected>Lec & Escritura</option>
                                   <option value="3">Eliminar</option></select><br>';
              } else {
                $cadena .= '<select style="border-radius:5px;" class="slc" data-id="' . $items1->id_permiso . '">
                                   <option value="1">Lectura</option>
                                   <option value="2">Lec & Escritura</option>
                                   <option value="3" selected>Eliminar</option></select><br>';
              }
            } else {
              $cadena .= '<select style="border-radius:5px;" class="slc" data-id="' . $items1->id_permiso . '">
                                   <option value="1">Lectura</option>
                                   <option value="2">Lec & Escritura</option>
                                   <option value="3">Eliminar</option></select><br>';
            }


            $cadena .= '</span>';
          }


          //////////////////////////////PERMISOS ESPECIALES

          $permisos2 = $this->model_sistema->traer_permisos_especiales_sistema($items->id_permiso);
          if ($permisos2) {
            $cadena .= '<br><label class="col-sm-12">Permisos especiales -----------------------------------------------------------------------------------------</label><br>';
          }

          foreach ($permisos2 as $items2) {
            $true_permiso1 = $this->model_sistema->check_permiso_by_id($id, $items2->id_permiso);
            $cadena .= '<span class="contenedores">';
            $cadena .= '<label class="col-sm-4"><strong>' . $items2->modulo . '</strong></label>';
            if ($true_permiso1) { //AGREGAMOS UN SELECT HIDDEN PARA QUE EL SISTEMA NO TOME COMO UNDEFINIDO CUANDO TIENE HIJOS
              $cadena .= '<input type="checkbox" value="' . $items2->id_permiso . '" checked class="chk acc0 group-' . $items->id_permiso . '" />&nbsp;&nbsp;';
              $cadena .= '<select style="border-radius:5px;display:none" class="slc" data-id="' . $items2->id_permiso . '">
                                       <option value="1">Lectura</option>
                                       <option value="2">Lec & Escritura</option>
                                       <option value="3" selected>Eliminar</option></select><br>';
            } else {
              $cadena .= '<input type="checkbox"  value="' . $items2->id_permiso . '" class="chk acc0 group-' . $items->id_permiso . '" />&nbsp;&nbsp;';
              $cadena .= '<select style="border-radius:5px;display:none" class="slc" data-id="' . $items2->id_permiso . '">
                                                   <option value="1">Lectura</option>
                                                   <option value="2">Lec & Escritura</option>
                                                   <option value="3" selected>Eliminar</option></select><br>';
            }

            $cadena .= '</span>';
          }

          $cadena .= '</div></div></div></div>';
        }

        
         $cadena .= '<div id="accordion-permisos" class="card-accordion">
                   <div class="card">
                   <div class="card-header bg-blue text-white pointer-cursor collapsed" data-toggle="collapse" data-target="#p1000">WIDGETS<small class="pull-right h-1000"><b>Sin submenú</b></small></div>
                   <div id="p1000" class="collapse" data-parent="#accordion-permisos">
                   <div class="card-body border">';
          $cadena .= '<span class="contenedores">';
          //$true_permiso = $this->model_sistema->check_permiso_by_id($id, $items->id_permiso); /////check si tiene permiso de padre          
          $cadena .= '<br>';
            foreach ($widgets as $items2) {
            $true_permiso1 = $this->model_sistema->check_permiso_by_id($id, $items2->id_permiso);
            $cadena .= '<span class="contenedores">';
            $cadena .= '<label class="col-sm-4"><strong>' . $items2->modulo . '</strong></label>';
            if ($true_permiso1) { //AGREGAMOS UN SELECT HIDDEN PARA QUE EL SISTEMA NO TOME COMO UNDEFINIDO CUANDO TIENE HIJOS
              $cadena .= '<input type="checkbox" value="' . $items2->id_permiso . '" checked class="chk acc0 group-' . $items->id_permiso . '" />&nbsp;&nbsp;';
              $cadena .= '<select style="border-radius:5px;display:none" class="slc" data-id="' . $items2->id_permiso . '">
                                       <option value="1">Lectura</option>
                                       <option value="2">Lec & Escritura</option>
                                       <option value="3" selected>Eliminar</option></select><br>';
            } else {
              $cadena .= '<input type="checkbox"  value="' . $items2->id_permiso . '" class="chk acc0 group-' . $items->id_permiso . '" />&nbsp;&nbsp;';
              $cadena .= '<select style="border-radius:5px;display:none" class="slc" data-id="' . $items2->id_permiso . '">
                                                   <option value="1">Lectura</option>
                                                   <option value="2">Lec & Escritura</option>
                                                   <option value="3" selected>Eliminar</option></select><br>';
            }

            $cadena .= '</span>';
          }
            
          
          $cadena .= '</span">';
          $cadena .= '</div></div></div></div>';

         


        $data['permisos'] = $cadena;
        $data['tipoacceso'] = $tipoacceso;
        echo view('sistema/gestion_personal/edit', $data);
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
        $sucursales = (array) $this->request->getPost('sucursales'); // sin selección llega null
        $sucursal_origen = $this->request->getPost('sucursal_origen');
        $cadena_sucursales = "";
        if (($sucursales[0] ?? '') == "%%") {
          $cadena_sucursales = "%%";
        } else {
          foreach ($sucursales as $items) {
            $cadena_sucursales .= $items . ",";
          }
          $cadena_sucursales = substr($cadena_sucursales, 0, -1);
        }
        if ($password == "") {
          $arrayName = [
            'nombre' => $nombre, 'username' => $username, 'correo' => $correo, 'movil' => $movil,
            'rol' => $rol, 'estado' => $estado, 'sucursal' => $cadena_sucursales,'sucursal_origen' => $sucursal_origen
          ];
        } else {
          $arrayName = [
            'nombre' => $nombre, 'username' => $username, 'correo' => $correo, 'movil' => $movil,
            'rol' => $rol, 'estado' => $estado, 'password' => Contrasena::hash((string) $password), 'sucursal' => $cadena_sucursales, 'sucursal_origen' => $sucursal_origen
          ];
        }

        $update = $this->model_sistema->update_perfil($id, $arrayName);
        if ($update) {
          $insert_log = $this->model_log->insert_log("000000", "operador", "Operador editado <b>#" . $id . " " . $nombre . "</b>", $_SESSION['id_usuario8291']);
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
      $permiso_sucursal = new Class_permiso_sucursal();
      $where_permiso_sucursal_select = $permiso_sucursal->get_permiso_sucursal_select($_SESSION['sucursales']);
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('GESTION_PERSONAL'));
      var_dump($tipoacceso);
      if ($tipoacceso >=2) {


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
                                   <option value="3" selected>Eliminar</option></select><br>';
            $cadena .= '</span>';
          }

          $cadena .= '</div></div></div></div>';
        }

        ///////////////////////////



        $data['sucursales'] = $this->model_selectores->get_all_sucursales();
        $data['sucursales_by_user'] = $this->model_selectores->get_all_sucursales_by_user($where_permiso_sucursal_select);
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
        $sucursales = (array) $this->request->getPost('sucursales'); // sin selección llega null
        $sucursal_origen = $this->request->getPost('sucursal_origen');
        $cadena_sucursales = "";
        if (($sucursales[0] ?? '') == "%%") {
          $cadena_sucursales = "%%";
        } else {
          foreach ($sucursales as $items) {
            $cadena_sucursales .= $items . ",";
          }
          $cadena_sucursales = substr($cadena_sucursales, 0, -1);
        }
        $arrayName = [
          'nombre' => $nombre, 'username' => $username, 'correo' => $correo, 'movil' => $movil,
          'rol' => $rol, 'estado' => $estado, 'password' => Contrasena::hash((string) $password), 'sucursal' => $cadena_sucursales,'sucursal_origen' => $sucursal_origen
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
        $nombre = $this->request->getPost('nombre');
        $insert_log = $this->model_log->insert_log("000000", "operador", "Operador eliminado <b>#" . $id . " " . $nombre . "</b>", $_SESSION['id_usuario8291']);
        $delete = $this->model_sistema->delete_operador($id);
        if ($delete) echo "succes";
        else echo "error";
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

 public function modal_cambiar_mi_contrasenia()
  {
    return view('sistema/gestion_personal/modal_cambiar_mi_contrasenia.php');
  }
   public function miperfil()
  {
    $perfil = $this->model_sistema->get_datos_perfil($_SESSION['id_usuario8291']);
    if ($perfil) {

      $data['perfil'] = $perfil;
      return view('historial_personal/miperfil.php', $data);
    } else {
      echo "No se encontro el usuario";
    }
  }
  public  function update_perfil_personal()
  {
    $user_nombre = $this->request->getPost('user-nombre');
    $user_correo = $this->request->getPost('user-correo');
    $user_movil = $this->request->getPost('user-movil');
    $fb = $this->request->getPost('fb');
    $twitter = $this->request->getPost('twitter');
    $timeout = $this->request->getPost('timeout');
    $data = [
      'nombre' => $user_nombre,
      'correo' => $user_correo,
      'fb' => $fb,
      'twitter' => $twitter,
      'timeout' => $timeout,
      'movil' => $user_movil
    ];
    $result = $this->model_sistema->update_perfil($_SESSION['id_usuario8291'], $data);
    if ($result) {
      echo 1;
    } else {
      echo 2;
    }
  }
}
