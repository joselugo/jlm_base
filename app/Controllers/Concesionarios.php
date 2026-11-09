<?php

namespace App\Controllers;

use App\Libraries\Class_seguridad;
use App\Models\Model_concesionarios;
use App\Models\Model_log;
use App\Models\Model_selectores;
use App\Libraries\Class_permiso_sucursal;

class Concesionarios extends BaseController
{
    var $model_concesionario;
    var $model_selectores;
    var $model_log;
    var $seguridad;
    var $permiso_sindicato;

    public function __construct()
    {
        $this->model_concesionario = new Model_concesionarios();
        $this->model_log = new Model_log();
        $this->seguridad = new Class_seguridad();
        $this->model_selectores = new Model_selectores();
        $this->permiso_sindicato = new Class_permiso_sucursal();
        session();
    }

    public function index()
    {

        if (isset($_SESSION['id_usuario8291'])) {

            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_CONCESIONARIO'));
            if ($tipoacceso > 0) {
                $data['tipoacceso'] = $tipoacceso;
                echo view('concesionarios/lista_concesionarios/index', $data);
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    public function get_concesionarios_json()
    {

        if (isset($_SESSION['id_usuario8291'])  && isset($_SESSION['token8291'])) {

            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_CONCESIONARIO'));
            if ($tipoacceso > 0) {

                $permiso_sindicato = $this->permiso_sindicato->get_permiso_socios($_SESSION['sindicatos']);
                $concesionario = $this->model_concesionario->get_all_concesionarios($permiso_sindicato);

                $array_concesionario = array();
                $tool = "";
                $status = "";
                if ($concesionario) {
                    foreach ($concesionario as $items) {


                        if ($items->status == 0) {
                            $status = "<span class='badge badge-success'>" . 'ACTIVO' . "</span>";
                        } else if ($items->status == 1) {
                            $status = "<span class='badge badge-warning'>" . 'SUSPENDIDO' . "</span>";
                        } else {
                            $status = "<span class='badge badge-danger'>" . 'RETIRADO' . "</span>";
                        }

                        if ($tipoacceso == 1) {
                            $tool = "";
                        } else if ($tipoacceso == 2) {
                            $tool = '<td class=" text-center"><a href="#concesionarios/update_concesionario?id=' . $items->id . '" data-toggle="tooltip" title="Editar" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>';
                        } else {
                            $tool = '<td class=" text-center"><a href="#concesionarios/update_concesionario?id=' . $items->id . '" data-toggle="tooltip" title="Editar" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                            <a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_concesionario(\'' . $items->id_socio . '\',\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $items->nombre . '\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a></td>';
                        }

                        $array_item = array(
                            "id" => $items->id,
                            "nombre" => $items->nombre . ' ' . $items->apellidos,
                            "idsocio" => $items->nombre_socio . " " . $items->apellidos_socio,
                            "idsindicato" => $items->nombre_sindicato,
                            "domicilio" => $items->domicilio,
                            "ine" => $items->ine,
                            "movil" => $items->movil,
                            "status" => $status,
                            "correo" => $items->correo,
                            "fechaalta" => $items->fechaalta,
                            "tool" => $tool
                        );
                        array_push($array_concesionario, $array_item);
                    }
                }
                echo '{"data":' . json_encode($array_concesionario) . '}';
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    public function get_concesionarios_json_vinculacion()
    {

        if (isset($_SESSION['id_usuario8291'])  && isset($_SESSION['token8291'])) {

            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_CONCESIONARIO'));
            if ($tipoacceso > 0) {

                $permiso_sindicato = $this->permiso_sindicato->get_permiso_socios($_SESSION['sindicatos']);
                $concesionario = $this->model_concesionario->get_all_concesionarios_vin($permiso_sindicato);

                $array_concesionario = array();
                $tool = "";
                $status = "";
                if ($concesionario) {
                    foreach ($concesionario as $items) {

                        if ($items->status == 0) {
                            $status = "<span class='badge badge-success'>" . 'ACTIVO' . "</span>";
                        } else if ($items->status == 1) {
                            $status = "<span class='badge badge-warning'>" . 'SUSPENDIDO' . "</span>";
                        } else {
                            $status = "<span class='badge badge-danger'>" . 'RETIRADO' . "</span>";
                        }

                        $array_item = array(
                            "id" => $items->id,
                            "nombre" => $items->nombre . ' ' . $items->apellidos,
                            "idsocio" => $items->nombre_socio . " " . $items->apellidos_socio,
                            "id_socio" => $items->id_socio,
                            "idsindicato" => $items->nombre_sindicato,
                            "domicilio" => $items->domicilio,
                            "ine" => $items->ine,
                            "movil" => $items->movil,
                            "status" => $status,
                            "correo" => $items->correo,
                            "fechaalta" => $items->fechaalta,
                            "tool" => $tool
                        );
                        array_push($array_concesionario, $array_item);
                    }
                }
                echo '{"data":' . json_encode($array_concesionario) . '}';
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    public function add_concesionario()
    {
        if (isset($_SESSION['id_usuario8291'])  && isset($_SESSION['token8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_CONCESIONARIO'));
            if ($tipoacceso > 0) {
                $permiso_sindicato = $this->permiso_sindicato->get_permiso_socios($_SESSION['sindicatos']);
                $data['tblsocios'] = $this->model_selectores->get_all_socios($permiso_sindicato);
                $data['tipoacceso'] = $tipoacceso;
                echo view('concesionarios/lista_concesionarios/new', $data);
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    public function vinculacion()
    {
        if (isset($_SESSION['id_usuario8291'])  && isset($_SESSION['token8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('VINCULAR_CONCESIONARIO'));
            if ($tipoacceso > 0) {
                $permiso_sindicato = $this->permiso_sindicato->get_permiso_socios($_SESSION['sindicatos']);
                $data['tblsocios'] = $this->model_selectores->get_all_socios($permiso_sindicato);
                $data['tipoacceso'] = $tipoacceso;
                echo view('concesionarios/vinculacion/index', $data);
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    public function new_concesionarios()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_CONCESIONARIO'));
            if ($tipoacceso > 0) {

                $nombre = $this->request->getPost('nombre');
                $apellidos = $this->request->getPost('apellidos');
                $idsocio = $this->request->getPost('idsocio');
                $domicilio = $this->request->getPost('domicilio');
                $ine = $this->request->getPost('ine');
                $movil = $this->request->getPost('movil');
                $correo = $this->request->getPost('correo');
                $avatar = $this->request->getPost('avatar');
                $password = $this->request->getPost('newpassword');

                $arrayPost = [
                    'nombre' => $nombre, 'apellidos' => $apellidos, 'idsocio' => $idsocio, 'domicilio' => $domicilio, 'ine' => $ine, 'movil' => $movil,
                    'correo' => $correo, 'avatar' => $avatar, 'password' => $password, 'fechaalta' => date("Y-m-d H:i:s")
                ];
                $insert = $this->model_concesionario->new_concesionario($arrayPost);
                if ($insert) {
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

    public function delete_concesionarios()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_CONCESIONARIO'));
            if ($tipoacceso > 0) {
                $id = $this->request->getPost('id');
                $id_socio = $this->request->getPost('id_socio');
                $nombre = $this->request->getPost('nombre');
                $insert_log = $this->model_log->insert_log($id_socio, "concesionario", "Concesionario eliminado <b>#" . $id . " " . $nombre . "</b>", $_SESSION['nombre']);
                $delete = $this->model_concesionario->delete_concesionario($id);
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

    public function update_sindicato()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_CONCESIONARIO'));
            if ($tipoacceso > 0) {
                $id = $this->request->getPost('id');
                $row = $this->model_concesionario->update_sindicato($id);
                if ($row) {
                    $arrayItems = array(
                        'id' => $row->$id,
                        'nombre' => $row->nombre,
                        'idlider' => $row->idlider
                    );
                }
                echo json_encode($arrayItems);
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    public function add_vinculo()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('VINCULAR_CONCESIONARIO'));
            if ($tipoacceso > 0) {
                $permiso_sindicato = $this->permiso_sindicato->get_permiso_socios($_SESSION['sindicatos']);
                $idconcesionario = $this->request->getPost('id-con');
                $idunidad = $this->request->getPost('id-u');
                $id_socio = $this->request->getPost('id-so');
                $true_vinculo = $this->model_concesionario->check_vinculo($idunidad, $idconcesionario);
                $row_unidad = $this->model_concesionario->get_unidad_by_id($idunidad, $permiso_sindicato);
                if ($true_vinculo || $row_unidad->idconcesionario != "000000") {
                    echo "exite";
                } else {
                    $arrayPost = ['idconcesionario' => $idconcesionario];
                    $insert_log = $this->model_log->insert_log($id_socio, "concesionario", "Unidad vinculada <b>#" . $idunidad . " al socio " . $id_socio . "</b>", $_SESSION['nombre']);
                    $update = $this->model_concesionario->update_vinculo($idunidad, $arrayPost);
                    if ($update) {
                        echo "success";
                    } else {
                        echo "error";
                    }
                }
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    public function delete_vinculo()
    {
        if (isset($_SESSION['id_usuario8291']) && $_SESSION['token8291'] == $this->request->getPost("token")) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('VINCULAR_CONCESIONARIO'));
            if ($tipoacceso > 0) {
                $idunidad = $this->request->getPost('id');
                $id_socio = $this->request->getPost('id_socio');

                $arrayPost = ['idconcesionario' => "000000"];
                $insert_log = $this->model_log->insert_log($id_socio, "concesionario", "Unidad desvinculada <b>#" . $idunidad . " al socio " . $id_socio . "</b>", $_SESSION['nombre']);
                $update = $this->model_concesionario->update_vinculo($idunidad, $arrayPost);
                if ($update) {
                    echo "success";
                } else {
                    echo "error";
                }
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }
}
