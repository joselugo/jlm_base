<?php

namespace App\Controllers;



require 'vendor/autoload.php';

use App\Libraries\Class_seguridad;
/* use App\Models\Model_almacen;
use App\Models\Model_log;
use App\Models\Model_selectores;
use App\Libraries\Class_permiso_sucursal;
use App\Models\Model_configuracion; */
use App\Models\Model_game;


class Game extends BaseController
{
  var $model_game;
   public function __construct()
  {
    $this->seguridad = new Class_seguridad();
  /*   $this->almacen = new Model_almacen();
    $this->model_log = new Model_log();
    $this->model_configuracion = new Model_configuracion();
    $this->model_selectores = new Model_selectores();
    $this->permiso_sucursal = new Class_permiso_sucursal(); */
    date_default_timezone_set('America/Mexico_City');
    $this->model_game = new Model_game();
    session();
  }
  public function index(){
    return view('game/index');
  }
  public function preguntas(){
    $permiso = $this->seguridad->Acceso_full('GAME_PREGUNTAS', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      return view('game/preguntas');
    }
    }

  public function get_preguntas(){
  $permiso = $this->seguridad->Acceso_full('GAME_PREGUNTAS', 'LECTURA', $_SESSION['id_usuario8291']);
  if ($permiso) {
    $data = array();
    $preguntas = $this->model_game->get_preguntas(0);
    if ($preguntas) {
      foreach ($preguntas as $key => $value) {
        $numr= $value->numrespuestas;
        $color= $numr == 0 ? "danger" : "success";
        $value->pregunta= "<span class=\"badge label-$color\">$numr</span> ".$value->pregunta;
        $value->num=$key+1;
        $value->ir="<a data-toggle=\"tooltip\" title=\"\" style=\"color:white !important;\" class=\"btn btn-info btn-sm\" onclick=\"ir_pregunta(this,'$value->id','$value->num');\" data-original-title=\"ir ala pregunta\">
          ir ala pregunta
        </a>";
        $value->tool="
        <a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"getmodal('game/modal_edit_pregunta?id=$value->id','Editar pregunta','lg');\" data-original-title=\"Editar\">
            <i class=\"far fa-edit\" aria-hidden=\"true\"></i>
        </a>
        <a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"eliminar_pregunta('$value->id')\" data-original-title=\"Eliminar\">
            <i class=\"far fa-trash-alt\" aria-hidden=\"true\"></i>
        </a>";
      }
      $data['data'] = $preguntas;
    } else {
      $data['data'] = array();
    }
    return json_encode($data);
    }
  }
  public function get_preguntas_rapidas(){
  $permiso = $this->seguridad->Acceso_full('GAME_PREGUNTAS', 'LECTURA', $_SESSION['id_usuario8291']);
  if ($permiso) {
    $data = array();
    $preguntas = $this->model_game->get_preguntas(1);
    if ($preguntas) {
      foreach ($preguntas as $key => $value) {
        $numr= $value->numrespuestas;
        $color= $numr == 0 ? "danger" : "success";
        $value->pregunta= "<span class=\"badge label-$color\">$numr</span> ".$value->pregunta;
        $value->num=$key+1;
         $value->ir = "<a data-toggle=\"tooltip\" title=\"\" style=\"color:white !important;\" class=\"btn btn-info btn-sm\" onclick=\"ir_pregunta(this,'$value->id','$value->num');\" data-original-title=\"ir ala pregunta\">
          ir ala pregunta
        </a>";
        $value->tool="
        <a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"getmodal('game/modal_edit_pregunta?id=$value->id','Editar pregunta','lg');\" data-original-title=\"Editar\">
            <i class=\"far fa-edit\" aria-hidden=\"true\"></i>
        </a>
        <a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm count_respuestas\" onclick=\"eliminar_pregunta('$value->id')\" data-original-title=\"Eliminar\">
            <i class=\"far fa-trash-alt\" aria-hidden=\"true\"></i>
        </a>";
      }
      $data['data'] = $preguntas;
    } else {
      $data['data'] = array();
    }
    return json_encode($data);
    }
  }
  public function modal_new_pregunta(){
    $tipo=$this->request->getGet('tipo');
    $data=array();
    if ($tipo) {
      $permiso = $this->seguridad->Acceso_full('GAME_PREGUNTAS_RAPIDAS', 'ESCRITURA', $_SESSION['id_usuario8291']);
      $data['tipo']=1;
    }else{
      $permiso = $this->seguridad->Acceso_full('GAME_PREGUNTAS', 'ESCRITURA', $_SESSION['id_usuario8291']);
    }
    if ($permiso) {
      return view('game/modal_new_pregunta',$data);
    }
  }
  public function modal_edit_pregunta(){
    $permiso = $this->seguridad->Acceso_full('GAME_PREGUNTAS', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id=$this->request->getGet('id');
      $respuestas=$this->model_game->get_respuestas($id);
      if ($respuestas) {
        $data['respuestas']=$respuestas;
      }
      $data['id']=$id;
      return view('game/modal_edit_pregunta',$data);
    }
  }
  public function ir_a_pregunta(){
    $rapida=$this->request->getPost('rapida');
    $key = $this->request->getPost('key');
    if ($rapida) {
      $permiso = $this->seguridad->Acceso_full('GAME_PREGUNTAS_RAPIDAS', 'LECTURA', $_SESSION['id_usuario8291']);
      $data['key'] = $key;
    }else{
      $permiso = $this->seguridad->Acceso_full('GAME_PREGUNTAS', 'LECTURA', $_SESSION['id_usuario8291']);
    }
    if ($permiso) {
      $id=$this->request->getPost('id');
      $respuestas=$this->model_game->get_respuestas($id);
      if ($respuestas) {
        foreach ($respuestas as $key => $value) {
          $value->key=$key+1;
          $value->txtbase64=base64_encode($value->respuesta);
        }
        $data['respuestas']=$respuestas;
      }else{
        $data['error']="error";
      }
      return json_encode($data);
    }
  }
  public function save_new_pregunta(){
    $permiso = $this->seguridad->Acceso_full('GAME_PREGUNTAS', 'ESCRITURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $pregunta=$this->request->getPost('pregunta');
      $data=[
        "pregunta"=>$pregunta,
      ];
      $save=$this->model_game->save_new_pregunta($data);
      echo $save?1:2;
    }
  }
  public function save_new_pregunta_rapida(){
    $permiso = $this->seguridad->Acceso_full('GAME_PREGUNTAS_RAPIDAS', 'ESCRITURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $pregunta=$this->request->getPost('pregunta');
      $data=[
        "pregunta"=>$pregunta,
        "tipo"=>1
      ];
      $save=$this->model_game->save_new_pregunta($data);
      echo $save?1:2;
    }
  }
  public function delete_pregunta(){
    $permiso = $this->seguridad->Acceso_full('GAME_PREGUNTAS', 'ESCRITURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id=$this->request->getPost('id');
      $delete=$this->model_game->delete_pregunta($id);
      echo $delete?1:2;
    }
  }

  public function save_respuestas(){
    $permiso = $this->seguridad->Acceso_full('GAME_PREGUNTAS', 'ESCRITURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id=$this->request->getPost('id');
      $respuestas=$this->request->getPost('respuesta');
      $puntos=$this->request->getPost('puntos');
      $data=array();
      foreach ($respuestas as $key => $respuesta) {
        $punto=$puntos[$key];
        $data[]=[
          "id_pregunta"=>$id,
          "respuesta"=>$respuesta,
          "puntos"=>$punto
        ];
      }
      $this->model_game->delete_respuestas($id);
      $save=$this->model_game->save_respuestas($data);
      echo $save?1:2;
    }
  }
  public function preguntas_rapidas()
  {
    $permiso = $this->seguridad->Acceso_full('GAME_PREGUNTAS_RAPIDAS', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      return view("game/preguntas_rapidas");
    }
  }
}

