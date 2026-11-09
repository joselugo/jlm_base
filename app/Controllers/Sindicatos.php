<?php namespace App\Controllers;
use App\Libraries\Class_seguridad;
use App\Models\Model_sindicato;
use App\Models\Model_log;
use App\Models\Model_selectores;

class Sindicatos extends BaseController {
    var $model_sindicato;
    var $model_selectores;
    var $model_log;
    var $seguridad;

    public function __construct(){
        $this->model_sindicato = new Model_sindicato();
        $this->model_log = new Model_log();
        $this->seguridad = new Class_seguridad();
        $this->model_selectores = new Model_selectores();
        session();
    }

    public function index(){
        
        if (isset($_SESSION['id_usuario8291'])) {
            
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_SINDICATO'));
            if ($tipoacceso > 0) {
                $data['tipoacceso']=$tipoacceso;
                $data['tbllider']=$this->model_selectores->get_all_lideres();
                echo view('sindicatos/lista_sindicatos/index', $data); 
            }else {
                echo acceso_denegado(); 
            }
        }else {
            $this->seguridad->login_back();
        }
        
    }

    public function get_sindicatos_json(){

        if (isset($_SESSION['id_usuario8291'])) {
           
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_SINDICATO'));
            if ($tipoacceso > 0) {

                $sindicato = $this->model_sindicato->get_all_sindicatos();

                $array_sindicato=array();
                $tool="";$status="";
                if ($sindicato) {
                    foreach ($sindicato as $items) {

                        if ($items->estado==0) {
                            $status="<span class='badge badge-success'>".'ACTIVO'."</span>";
                        }else {
                            $status="<span class='badge badge-warning'>".'DESACTIVADO'."</span>";
                        }

                        if ($tipoacceso == 1) {
                            $tool = "";
                        }else if ($tipoacceso == 2) {
                            $tool='<td class=" text-center"><a href="#sindicatos/view_sindicato?id='.$items->id.'" data-toggle="tooltip" title="Editar" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>';
                        }else {
                            $tool='<td class=" text-center"><a href="#sindicatos/view_sindicato?id='.$items->id.'" data-toggle="tooltip" title="Editar" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                            <a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_sindicato(\''.$items->id.'\',\''.$items->nombre.'\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a></td>';
                        }

                        $array_item=array(
                            "id"=>$items->id,
                            "nombre"=>$items->nombre,
                            "idlider"=>$items->nombre_lider,
                            "estado"=>$status,
                            "tool"=>$tool
                        );
                        array_push($array_sindicato, $array_item);
                    }
                }
                echo '{"data":'. json_encode($array_sindicato).'}';

            }else {
                echo acceso_denegado(); 
            }

        }else {
            $this->seguridad->login_back();
        }

    }

    public function new_sindicatos() {
        if (isset($_SESSION['id_usuario8291'])){
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_SINDICATO'));
            if ($tipoacceso > 0) {
                $nombre=$this->request->getPost('nombre');
                $idlider=$this->request->getPost('idlider');

                $arrayPost=['nombre'=>$nombre,
                'idlider'=>$idlider];
                $insert = $this->model_sindicato->new_sindicato($arrayPost);
                if ($insert) {
                    echo 1;
                }else {
                    echo 2;
                }
            }else{
                echo acceso_denegado();
            }
        }else {
            $this->seguridad->login_back();
        }
    }

    public function delete_sindicato(){
        if (isset($_SESSION['id_usuario8291'])){
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_SINDICATO'));
            if ($tipoacceso > 0) {
                $id = $this->request->getPost('id');
                $nombre = $this->request->getPost('nombre');
                $insert_log=$this->model_log->insert_log("000000","sindicato","Sindicato eliminado <b>#".$id." ".$nombre."</b>",$_SESSION['nombre']);
                $delete=$this->model_sindicato->delete_sindicato($id);
                if ($delete) {
                    echo 1;
                }else {
                    echo 2;
                }
            }else{
                echo acceso_denegado();
            }
        }else {
            $this->seguridad->login_back();
        }
    }

    public function update_sindicato(){
        if (isset($_SESSION['id_usuario8291'])){
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_SINDICATO'));
            if ($tipoacceso > 0) {
                $id = $this->request->getPost('id');
                $row = $this->model_sindicato->update_sindicato($id);
                if ($row) {
                    $arrayItems = array(
                        'id'=> $row->$id,
                        'nombre'=> $row->nombre,
                        'idlider'=> $row->idlider
                    );
                }
                echo json_encode($arrayItems);
            }else{
                echo acceso_denegado();
            }
        }else {
            $this->seguridad->login_back();
        }
    }

        public function view_sindicato(){
        if (isset($_SESSION['id_usuario8291'])){
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_SINDICATO'));
            $arrayItems = array();
            if ($tipoacceso > 0) {
                $id = $this->request->getPost('id');
                $row = $this->model_sindicato->get_data_sindicato($id);
                if ($row) {
                    $arrayItems = array(
                        'id'=> $row->$id,
                        'nombre'=> $row->nombre,
                        'idlider'=> $row->idlider
                    );
                }
                echo json_encode($arrayItems);
            }else{
                echo acceso_denegado();
            }
        }else {
            $this->seguridad->login_back();
        }
    }


}


