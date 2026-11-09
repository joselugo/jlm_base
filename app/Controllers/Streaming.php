<?php

namespace App\Controllers;

use App\Libraries\Class_seguridad;
use App\Models\Model_streaming;
use App\Models\Model_log;
use App\Models\Model_selectores;

class Streaming extends BaseController
{
    var $model_streaming;
    var $seguridad;
    var $model_log;
    var $model_selectores;

    public function __construct()
    {
        $this->model_streaming = new Model_streaming();
        $this->seguridad = new Class_seguridad();
        $this->model_log = new Model_log();
        $this->model_selectores = new Model_selectores();
        session();
    }

    public function index()
    {

        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('STREAMING'));
            if ($tipoacceso >= 1) {
                //$data['netflix'] = $this->model_streaming->get_balance(000001);
                //$data['youtube'] = $this->model_streaming->get_balance(000002);
                $data['tipoacceso'] = $tipoacceso;

                echo view('streaming/index', $data);
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    //Consultas tables

    public function get_services_json()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('STREAMING'));
            if ($tipoacceso >= 1) {

                $streaming = $this->model_streaming->get_streaming();
                $array_streaming = array();

                if ($streaming) {
                    foreach ($streaming as $items) {

                        ($items->estatus == 'ACTIVO') ? $status = '<span class="label label-success">ACTIVO</span>' : $status = '<span class="label label-warning">INACTIVO</span>';
                        ($items->tipo == 1) ? $tipo = '<span class="label label-info">PERSONAL</span>' : $tipo = '<span class="label label-info">COMPARTIDO</span>';

                        $modal = "getmodal('streaming/editar?id=$items->id&action=serviciostr','Editar servicio streaming','md')";

                        if ($tipoacceso == 1) {
                            $tool = "";
                        } else if ($tipoacceso == 2) {
                            $tool = '<td class=" tools-documentos text-center">
                                <a onclick="' . $modal . '" data-toggle="tooltip" title="Editar" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a></td>';
                        } else if ($tipoacceso == 3 && $items->estatus == "ACTIVO") {
                            $tool = '<td class=" tools-documentos text-center">
                            <a onclick="' . $modal . '" data-toggle="tooltip" title="Editar" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                            <a data-toggle="tooltip" title="Desactivar" class="btn btn-default btn-icon btn-sm" onclick="update_status_servicio(\'' . $items->id . '\',\'' . $items->nombre . '\')"><i class="fa fa-window-close" aria-hidden="true"></i></a></td>';
                        } else if ($tipoacceso == 3 && $items->estatus == "DESACTIVADO") {
                            $tool = '<td class=" tools-documentos text-center">
                            <a onclick="' . $modal . '" data-toggle="tooltip" title="Editar" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                            <a data-toggle="tooltip" title="Activar" class="btn btn-default btn-icon btn-sm" onclick="update_status_servicio_activo(\'' . $items->id . '\',\'' . $items->nombre . '\')"><i class="fa fa-check-square" aria-hidden="true"></i></a></td>';
                        }

                        $array_items = array(
                            "id" => $items->id,
                            "nombre" => $items->nombre,
                            "tipo" => $tipo,
                            "costo" => "$" . $items->costo . " MNX",
                            "estatus" => $status,
                            "tool" => $tool
                        );
                        array_push($array_streaming, $array_items);
                    }
                }
                echo '{"data":' . json_encode($array_streaming) . '}';
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    public function get_cuentas_json()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('STREAMING'));
            if ($tipoacceso >= 1) {
                $cont = 0;
                $cont2 = 0;
                $streaming = $this->model_streaming->get_cuentas();
                $array_streaming = array();

                if ($streaming) {
                    foreach ($streaming as $items) {

                        ($items->estado == 1) ? $status = '<span class="label label-success">ACTIVO</span>' : $status = '<span class="label label-warning">INACTIVO</span>';
                        ($items->tipo == 1) ? $tipo = '<span class="label label-info">PERSONAL</span>' : $tipo = '<span class="label label-info">COMPARTIDO</span>';

                        if ($items->lastdays <= 0) {
                            $cont++;
                            $day = '<span class="label label-danger">CUENTA VENCIDA: ' . $items->lastdays . ' DIAS</span>';
                        } else if ($items->lastdays >= 1 && $items->lastdays <= 5) {
                            $cont2++;
                            $day = '<span class="label label-warning">CUENTA POR VENCER: ' . $items->lastdays . ' DIAS </span>';
                        } else {
                            $day = '<span class="label label-success">DIAS FALTANTES: ' . $items->lastdays . '</span>';
                        }



                        $modal = "getmodal('streaming/editar?id=$items->id&action=cuentastr','Pagar servicio streaming','md')";

                        if ($tipoacceso == 1) {
                            $tool = "";
                        } else if ($tipoacceso == 2) {
                            $tool = '<td class=" tools-documentos text-center">
                                <a onclick="' . $modal . '" data-toggle="tooltip" title="Pagar" class="btn btn-default btn-icon btn-sm"><i class="far fa-credit-card"></i></a></td>';
                        } else if ($tipoacceso == 3 && $items->estado == 1) {
                            $tool = '<td class=" tools-documentos text-center">
                            <a onclick="' . $modal . '" data-toggle="tooltip" title="Pagar" class="btn btn-default btn-icon btn-sm"><i class="far fa-credit-card"></i></a>
                            <a data-toggle="tooltip" title="Desactivar" class="btn btn-default btn-icon btn-sm" onclick="update_status_cuenta(\'' . $items->id . '\',\'' . $items->nombre . '\')"><i class="fa fa-window-close" aria-hidden="true"></i></a></td>';
                        } else if ($tipoacceso == 3 && $items->estado == 2) {
                            $tool = '<td class=" tools-documentos text-center">
                            <a onclick="' . $modal . '" data-toggle="tooltip" title="Pagar" class="btn btn-default btn-icon btn-sm"><i class="far fa-credit-card"></i></a>
                            <a data-toggle="tooltip" title="Activar" class="btn btn-default btn-icon btn-sm" onclick="update_status_cuenta_activo(\'' . $items->id . '\',\'' . $items->nombre . '\')"><i class="fa fa-check-square" aria-hidden="true"></i></a></td>';
                        }

                        $array_items = array(
                            "id" => $items->id,
                            "nombre" => $items->nombre . ' ' . $tipo,
                            "fecha_alta_servicio" => date('d-m-Y', strtotime($items->fecha_alta_servicio)),
                            "fecha_vencimiento" => date('d-m-Y', strtotime($items->fecha_vencimiento)),
                            "lastdays" => $day,
                            "costo" => "$" . $items->costo . " MNX",
                            "correo" => $items->correo,
                            "contrasena" => $items->contrasena,
                            "estatus" => $status,
                            "tool" => $tool
                        );
                        array_push($array_streaming, $array_items);
                    }
                }
                $temp = [
                    "data" => $array_streaming,
                    "contV" => $cont,
                    "contPV" => $cont2
                ];

                echo json_encode($temp);
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    //clientes
    public function get_clientes_json()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('STREAMING'));
            if ($tipoacceso >= 1) {

                $streaming = $this->model_streaming->get_clientes();
                $array_streaming = array();

                if ($streaming) {
                    foreach ($streaming as $items) {

                        if ($items->estado == "ACTIVO") {
                            $status = '<span class="label label-success">ACTIVO</span>';
                        } elseif ($items->estado == "SUSPENDIDO") {
                            $status = '<span class="label label-warning">SUSPENDIDO</span>';
                        } else {
                            $status = '<span class="label label-danger">RETIRADO</span>';
                        }
                        ($items->tipo == 1) ? $tipo = '<span class="label label-info">PERSONAL</span>' : $tipo = '<span class="label label-info">COMPARTIDO</span>';

                        $modal = "getmodal('streaming/editar?id=$items->id&id_t=$items->id_tipo&action=cliente','Editar servicio streaming','md')";

                        if ($tipoacceso == 1) {
                            $tool = "";
                        } else if ($tipoacceso == 2) {
                            $tool = '<td class=" tools-documentos text-center">
                                <a onclick="' . $modal . '" data-toggle="tooltip" title="Editar" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a></td>';
                        } else if ($tipoacceso == 3) {
                            $tool = '<td class=" tools-documentos text-center">
                            <a onclick="' . $modal . '" data-toggle="tooltip" title="Editar" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                            <a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_cliente_cuenta(\'' . $items->id . '\',\'' . $items->id_streaming . '\',\'' . $items->id_cliente . '\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a></td>';
                        }

                        $array_items = array(
                            "id" => $items->id,
                            "nombre" => $items->nombre,
                            "streaming" => $items->streaming . " " . $tipo,
                            "correo" => $items->correo,
                            "contrasena" => $items->contrasena,
                            "pin" => $items->pin,
                            "costo" =>  "$" . $items->costo . " MNX",
                            "fecha_vinculado" => date('d-m-Y', strtotime($items->fecha_vinculado)),
                            "diapago" => $items->diapago,
                            "estado" => $status,
                            "tool" => $tool
                        );
                        array_push($array_streaming, $array_items);
                    }
                }
                echo '{"data":' . json_encode($array_streaming) . '}';
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    //balance
    public function get_balance_json()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('STREAMING'));
            if ($tipoacceso >= 1) {

                $data['netflix'] = $this->model_streaming->get_balance(000001);
                $data['netflix']->balance = number_format($data['netflix']->balance, 2, '.', ',');
                $data['youtube'] = $this->model_streaming->get_balance(000002);
                $data['youtube']->balance = number_format($data['youtube']->balance, 2, '.', ',');
                $streaming = $this->model_streaming->get_balance_json();
                $array_streaming = array();

                if ($streaming) {
                    foreach ($streaming as $items) {


                        ($items->tipo == 1) ? $tipo = '<span class="label label-info">PERSONAL</span>' : $tipo = '<span class="label label-info">COMPARTIDO</span>';

                        $array_items = array(
                            "id" => $items->id,
                            "servicio" => $items->correo,
                            "fecha_vinculado" => date('d-m-Y', strtotime($items->activation_date)),
                            "tipo" => $items->nombre . " " . $tipo,
                            "costo" =>  "$" . $items->costo . " MNX"
                        );
                        array_push($array_streaming, $array_items);
                    }
                }
                $temp = [
                    "data_ext" => $data,
                    "data" => $array_streaming
                ];

                echo json_encode($temp);
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    //historial balance netflix
    public function get_balance_netflix()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('STREAMING'));
            if ($tipoacceso >= 1) {

                $streaming = $this->model_streaming->get_balance_netflix();
                $array_streaming = array();

                if ($streaming) {
                    foreach ($streaming as $items) {

                        $array_items = array(
                            "log" => $items->log,
                            "fecha" => date('d-m-Y', strtotime($items->fechalog)),
                            "usuario" => $items->username
                        );
                        array_push($array_streaming, $array_items);
                    }
                }
                echo '{"data":' . json_encode($array_streaming) . '}';
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }
    //historial balance youtube
    public function get_balance_youtube()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('STREAMING'));
            if ($tipoacceso >= 1) {

                $streaming = $this->model_streaming->get_balance_youtube();
                $array_streaming = array();

                if ($streaming) {
                    foreach ($streaming as $items) {

                        $array_items = array(
                            "log" => $items->log,
                            "fecha" => date('d-m-Y', strtotime($items->fechalog)),
                            "usuario" => $items->username
                        );
                        array_push($array_streaming, $array_items);
                    }
                }
                echo '{"data":' . json_encode($array_streaming) . '}';
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }



    //end consulta tables
    public function nuevo()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('STREAMING'));
            if ($tipoacceso >= 2) {
                $data['tipoacceso'] = $tipoacceso;
                $action = $this->request->getGet('action');
                if ($action == 'streaming') {
                    echo view('streaming/servicios/nuevo_str', $data);
                }
                if ($action == 'cuenta') {
                    $data['streaming'] = $this->model_selectores->get_tipo_streaming();
                    echo view('streaming/cuentas/nuevo', $data);
                }
                if ($action == 'cliente') {
                    $data['streaming'] = $this->model_selectores->get_tipo_cuenta();
                    echo view('streaming/clientes/nuevo', $data);
                }
                if ($action == 'balance') {
                    echo view('streaming/balance/nuevo');
                }
                if ($action == 'balanceYT') {
                    echo view('streaming/balance/nuevo_yt');
                }
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }


    public function editar()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('STREAMING'));
            if ($tipoacceso >= 2) {
                $data['tipoacceso'] = $tipoacceso;
                $action = $this->request->getGet('action');
                if ($action == 'serviciostr') {
                    $id = $this->request->getGet('id');
                    $row = $this->model_streaming->get_streaming_byid($id);
                    if ($row) {
                        $data['streaming'] = $row[0];
                    }
                    echo view('streaming/servicios/editar_str', $data);
                }
                if ($action == 'cuentastr') {
                    $id = $this->request->getGet('id');
                    $data['tipo_streaming'] = $this->model_selectores->get_tipo_streaming();
                    $row = $this->model_streaming->get_cuenta_byid($id);
                    if ($row) {
                        $data['streaming'] = $row[0];
                    }
                    echo view('streaming/cuentas/editar', $data);
                }
                if ($action == 'cliente') {
                    $id = $this->request->getGet('id');
                    $id_t = $this->request->getGet('id_t');
                    $row = $this->model_streaming->get_cliente_streaming_byid($id);
                    $new_option = "<option value='" . $row[0]->id_streaming . "'>" . $row[0]->correo . " (Asignado)" .  "</option>";
                    $data['tipo_streaming'] = $new_option . $this->model_selectores->get_clientes_streaming_asig($id_t);
                    if ($row) {
                        $data['streaming'] = $row[0];
                    }
                    echo view('streaming/clientes/editar', $data);
                }
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    public function save_streaming()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('STREAMING'));
            if ($tipoacceso >= 2) {
                $name = $this->request->getPost('streaming[name]');
                $costo = $this->request->getPost('streaming[costo]');
                $tipo = $this->request->getPost('streaming[tipo]');
                $pin = $this->request->getPost('streaming[pin]');

                $array_streaming = [
                    'nombre' => $name, 'tipo' => $tipo, 'pin' => $pin, 'costo' => $costo
                ];
                $isnert = $this->model_streaming->save_streaming($array_streaming);
                if ($isnert) {
                    $txt_log = "Servicio tipo streaming agregado";
                    $insert_log = $this->model_log->insert_log("000000", "Streaming", $txt_log, $_SESSION['id_usuario8291']);
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



    public function save_edit_streaming()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('STREAMING'));
            if ($tipoacceso >= 2) {
                $id = $this->request->getPost('streaming[id]');
                $name = $this->request->getPost('streaming[name]');
                $costo = $this->request->getPost('streaming[costo]');
                $tipo = $this->request->getPost('streaming[tipo]');

                $array_streaming = [
                    'nombre' => $name, 'tipo' => $tipo, 'costo' => $costo
                ];
                $update = $this->model_streaming->update_streaming($id, $array_streaming);
                if ($update) {
                    $txt_log = "Servicio streaming editado ID: " . $id;
                    $insert_log = $this->model_log->insert_log("000000", "Streaming", $txt_log, $_SESSION['id_usuario8291']);
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

    public function update_status_servicio()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('STREAMING'));
            if ($tipoacceso >= 2) {
                $id = $this->request->getPost('id');
                $action = $this->request->getPost('action');
                $tipo = $this->request->getPost('tipo');


                if ($tipo == "servicio" && $action == "desactivar") {
                    $array_streaming = ['estatus' => 'DESACTIVADO'];
                    $update = $this->model_streaming->update_streaming($id, $array_streaming);
                    if ($update) {
                        $txt_log = "Servicio streaming desactivado ID: " . $id;
                        $insert_log = $this->model_log->insert_log("000000", "Streaming", $txt_log, $_SESSION['id_usuario8291']);
                        echo 1;
                    } else {
                        echo 2;
                    }
                }
                if ($tipo == "servicio" && $action == "activar") {
                    $array_streaming = ['estatus' => 'ACTIVO'];
                    $update = $this->model_streaming->update_streaming($id, $array_streaming);
                    if ($update) {
                        $txt_log = "Servicio streaming activado ID: " . $id;
                        $insert_log = $this->model_log->insert_log("000000", "Streaming", $txt_log, $_SESSION['id_usuario8291']);
                        echo 1;
                    } else {
                        echo 2;
                    }
                }
                if ($tipo == "cuenta" && $action == "desactivar") {
                    $array_streaming = ['estado' => 2];
                    $update = $this->model_streaming->update_streaming_cuenta($id, $array_streaming);
                    if ($update) {
                        $txt_log = "Cuenta streaming desactivado ID: " . $id;
                        $insert_log = $this->model_log->insert_log("000000", "Streaming", $txt_log, $_SESSION['id_usuario8291']);
                        echo 1;
                    } else {
                        echo 2;
                    }
                }
                if ($tipo == "cuenta" && $action == "activar") {
                    $array_streaming = ['estado' => 1];
                    $update = $this->model_streaming->update_streaming_cuenta($id, $array_streaming);
                    if ($update) {
                        $txt_log = "Cuenta streaming activado ID: " . $id;
                        $insert_log = $this->model_log->insert_log("000000", "Streaming", $txt_log, $_SESSION['id_usuario8291']);
                        echo 1;
                    } else {
                        echo 2;
                    }
                }
                if ($tipo == "cliente" && $action == "remove_perfil") {
                    if ($tipoacceso >= 3) {
                        $id_str = $this->request->getPost('id_streaming');
                        $id_cliente = $this->request->getPost('id_cliente');
                        $delete = $this->model_streaming->delete_perfil($id);
                        if ($delete) {
                            $row_str = $this->model_streaming->get_straming_byid($id_str);
                            $new_total = $row_str->total_cuentas + 1;
                            $array_update = ['total_cuentas' => $new_total];
                            $update_str = $this->model_streaming->update_streaming_cuenta($id_str, $array_update);
                            $txt_log = "Perfil streaming eliminado ID: (" . $id . ")";
                            $insert_log = $this->model_log->insert_log($id_cliente, "Streaming", $txt_log, $_SESSION['id_usuario8291']);
                        }
                    } else {
                        echo acceso_denegado();
                    }
                }
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    public function save_cuenta()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('STREAMING'));
            if ($tipoacceso >= 2) {

                $fecha_alta = $this->request->getPost('cuenta[alta_cuenta]');
                $fecha_vencimiento = $this->request->getPost('cuenta[fecha_vencimiento]');
                $id_streaming = $this->request->getPost('cuenta[servicio_str]');
                $correo = $this->request->getPost('cuenta[correo]');
                $contrasena = $this->request->getPost('cuenta[contrasena]');
                $total_cuenta = $this->request->getPost('cuenta[total_cuenta]');

                date_default_timezone_set('America/Mexico_City');

                //validar servicio_str == 000001 (netflix compartido) / 000002 (youtube) if else
                if ($id_streaming == 000002) {
                    $id = 000002;
                    $row = $this->model_streaming->get_balance($id);
                    $row2 = $this->model_streaming->get_streaming_tipo($id_streaming);
                    if ($row->balance == 0 || $row->balance < $row2->costo) {
                        echo 2;
                    } else {

                        $coste = $row2->costo;
                        $new_balance = $row->balance - $coste;

                        $array_streaming = [
                            'id_streaming' => $id_streaming, 'fecha_alta_servicio' => $fecha_alta,
                            'fecha_vencimiento' => $fecha_vencimiento, 'correo' => $correo, 'contrasena' => $contrasena,
                            'total_cuentas' => $total_cuenta
                        ];
                        //recuperar el ultimo id de streaming
                        $isnert = $this->model_streaming->save_cuenta($array_streaming);
                        if ($isnert) {
                            $str = $this->model_streaming->last_id();
                            $id_last_streaming = $str->last;
                            $array_record = [
                                'id_streamin' => $id_last_streaming, 'activation_date' => date('Y-m-d')
                            ];
                            $insert_record = $this->model_streaming->insert_record($array_record);
                            if ($insert_record) {
                                $array_streaming_t = [
                                    'balance' => $new_balance
                                ];
                                $update = $this->model_streaming->update_saldo_netflix($id, $array_streaming_t);
                                $txt_log = "Servicio streaming agregado #" . $id_last_streaming;
                                $insert_log = $this->model_log->insert_log("000000", "Streaming", $txt_log, $_SESSION['id_usuario8291']);
                                echo 1;
                            }
                        } else {
                            echo 2;
                        }
                    }
                } else {
                    $id = 000001;
                    $row = $this->model_streaming->get_balance($id);
                    $row2 = $this->model_streaming->get_streaming_tipo($id_streaming);
                    if ($row->balance == 0 || $row->balance < $row2->costo) {
                        echo 2;
                    } else {

                        $coste = $row2->costo;
                        $new_balance = $row->balance - $coste;

                        $array_streaming = [
                            'id_streaming' => $id_streaming, 'fecha_alta_servicio' => $fecha_alta,
                            'fecha_vencimiento' => $fecha_vencimiento, 'correo' => $correo, 'contrasena' => $contrasena,
                            'total_cuentas' => $total_cuenta
                        ];
                        //recuperar el ultimo id de streaming
                        $isnert = $this->model_streaming->save_cuenta($array_streaming);
                        if ($isnert) {
                            $str = $this->model_streaming->last_id();
                            $id_last_streaming = $str->last;
                            $array_record = [
                                'id_streamin' => $id_last_streaming, 'activation_date' => date('Y-m-d')
                            ];
                            $insert_record = $this->model_streaming->insert_record($array_record);
                            if ($insert_record) {
                                $array_streaming_t = [
                                    'balance' => $new_balance
                                ];
                                $update = $this->model_streaming->update_saldo_netflix($id, $array_streaming_t);
                                $txt_log = "Servicio streaming agregado #" . $id_last_streaming;
                                $insert_log = $this->model_log->insert_log("000000", "Streaming", $txt_log, $_SESSION['id_usuario8291']);
                                echo 1;
                            }
                        } else {
                            echo 2;
                        }
                    }
                }
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    public function save_edit_cuenta()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('STREAMING'));
            if ($tipoacceso >= 2) {
                $id = $this->request->getPost('cuenta[id]');
                $correo = $this->request->getPost('cuenta[correo]');
                $contrasena = $this->request->getPost('cuenta[contrasena]');
                $fecha = $this->request->getPost('cuenta[fecha]');
                $fecha_vencimiento = $this->request->getPost('cuenta[fecha_vencimiento]');
                $id_str = $this->request->getPost('cuenta[servicio_str]');

                date_default_timezone_set('America/Mexico_City');

                if ($id_str == 000002) {
                    $id_ = 000002;
                    $row = $this->model_streaming->get_balance($id_);
                    $row2 = $this->model_streaming->get_streaming_tipo($id_str);
                    if ($row->balance == 0 || $row->balance < $row2->costo) {
                        echo 2;
                    } else {

                        $coste = $row2->costo;
                        $new_balance = $row->balance - $coste;

                        $array_streaming = [
                            'id_streaming' => $id_str, 'fecha_alta_servicio' => $fecha, 'fecha_vencimiento' => $fecha_vencimiento, 'correo' => $correo, 'contrasena' => $contrasena
                        ];

                        $update = $this->model_streaming->update_streaming_cuenta($id, $array_streaming);
                        if ($update) {
                            $array_record = [
                                'id_streamin' => $id, 'activation_date' => date('Y-m-d')
                            ];
                            $insert_record = $this->model_streaming->insert_record($array_record);
                            if ($insert_record) {
                                $array_streaming_t = [
                                    'balance' => $new_balance
                                ];
                                $update = $this->model_streaming->update_saldo_netflix($id_, $array_streaming_t);
                                $txt_log = "Pago de servicio streaming  #" . $id;
                                $insert_log = $this->model_log->insert_log("000000", "Streaming", $txt_log, $_SESSION['id_usuario8291']);
                                echo 1;
                            }
                        } else {
                            echo 2;
                        }
                    }
                } else {
                    $id_ = 000001;
                    $row = $this->model_streaming->get_balance($id_);
                    $row2 = $this->model_streaming->get_streaming_tipo($id_str);
                    if ($row->balance == 0 || $row->balance < $row2->costo) {
                        echo 2;
                    } else {

                        $coste = $row2->costo;
                        $new_balance = $row->balance - $coste;

                        $array_streaming = [
                            'id_streaming' => $id_str, 'fecha_alta_servicio' => $fecha, 'fecha_vencimiento' => $fecha_vencimiento, 'correo' => $correo, 'contrasena' => $contrasena
                        ];

                        $update = $this->model_streaming->update_streaming_cuenta($id, $array_streaming);
                        if ($update) {
                            $array_record = [
                                'id_streamin' => $id, 'activation_date' => date('Y-m-d')
                            ];
                            $insert_record = $this->model_streaming->insert_record($array_record);
                            if ($insert_record) {
                                $array_streaming_t = [
                                    'balance' => $new_balance
                                ];
                                $update = $this->model_streaming->update_saldo_netflix($id_, $array_streaming_t);
                                $txt_log = "Pago de servicio streaming  #" . $id;
                                $insert_log = $this->model_log->insert_log("000000", "Streaming", $txt_log, $_SESSION['id_usuario8291']);
                                echo 1;
                            }
                        } else {
                            echo 2;
                        }
                    }
                }
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    public function get_data_select_c()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('STREAMING'));
            if ($tipoacceso >= 2) {
                $id = $this->request->getPost('id');
                $option = $this->model_selectores->get_cuentas_streaming($id);
                echo $option;
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }
    /* public function get_data_select_cstr()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('STREAMING'));
            if ($tipoacceso >= 1) {
                $id = $this->request->getPost('id');
                $option = $this->model_selectores->get_clientes_streaming_asig($id);
                echo $option;
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    } */

    public function save_cliente_cuenta()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('STREAMING'));
            if ($tipoacceso >= 2) {

                $cliente = $this->request->getPost('cliente[cliente]');
                $id = $this->request->getPost('cliente[id]');
                $alta_cuenta = $this->request->getPost('cliente[alta_cuenta]');
                $tipo_streaming = $this->request->getPost('cliente[tipo_streaming]'); //id tipo straming
                $servicio_streaming = $this->request->getPost('cliente[servicio_streaming]'); //id streaming
                $pin = $this->request->getPost('cliente[pin]');

                date_default_timezone_set('America/Mexico_City');

                $row = $this->model_streaming->get_straming_tipo_byid($tipo_streaming);
                if ($row->tipo == 2 && $row->pin == 1) {
                    $check_pin = $pin;
                } else {
                    $check_pin = "";
                }

                $array_streaming = ['id_cuentastr' => $servicio_streaming, 'id_cliente' => $id, 'pin' => $check_pin, 'fecha_vinculado' => $alta_cuenta];
                $isnert = $this->model_streaming->save_cuenta_cliente($array_streaming);
                if ($isnert) {
                    $row_str = $this->model_streaming->get_straming_byid($servicio_streaming);
                    $new_total = $row_str->total_cuentas - 1;
                    $array_update = ['total_cuentas' => $new_total];
                    $update_str = $this->model_streaming->update_streaming_cuenta($servicio_streaming, $array_update);
                    $txt_log = "Cuenta streaming vinculado al cliente: (" . $cliente . ")";
                    $insert_log = $this->model_log->insert_log($id, "Streaming", $txt_log, $_SESSION['id_usuario8291']);
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

    public function save_edit_clientestr()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('STREAMING'));
            if ($tipoacceso >= 2) {

                $cliente = $this->request->getPost('cliente[cliente]');
                $id_cliente = $this->request->getPost('cliente[id_cliente]');
                $id_clientestr = $this->request->getPost('cliente[id_clientestr]');
                $fecha = $this->request->getPost('cliente[fecha]');
                $servicio_str = $this->request->getPost('cliente[servicio_str]');
                $pin = $this->request->getPost('cliente[pin]');

                date_default_timezone_set('America/Mexico_City');

                $row = $this->model_streaming->get_clientestreaming_byid($id_clientestr);

                if ($row->id_cuentastr <> $servicio_str) {
                    $new_idservicio  = $servicio_str;
                    //descontar al nuevo servicio
                    $row_str = $this->model_streaming->get_straming_byid($new_idservicio);
                    $new_total = $row_str->total_cuentas - 1;
                    $array_update = ['total_cuentas' => $new_total];
                    $update_str = $this->model_streaming->update_streaming_cuenta($new_idservicio, $array_update);
                    if ($update_str) {
                        //contar el +1 al antiguo servicio
                        $row_str = $this->model_streaming->get_straming_byid($row->id_cuentastr);
                        $new_total = $row_str->total_cuentas + 1;
                        $array_update = ['total_cuentas' => $new_total];
                        $update_str = $this->model_streaming->update_streaming_cuenta($row->id_cuentastr, $array_update);
                    }
                } else {
                    $new_idservicio = $row->id_cuentastr;
                }

                $array_streaming = ['id_cuentastr' => $new_idservicio, 'id_cliente' => $id_cliente, 'pin' => $pin, 'fecha_vinculado' => $fecha];
                $update = $this->model_streaming->update_streaming_clientestr($id_clientestr, $array_streaming);
                if ($update) {
                    $txt_log = "Cuenta streaming editado vinculada al cliente : " . $cliente . " Editado";
                    $insert_log = $this->model_log->insert_log($id_clientestr, "Streaming", $txt_log, $_SESSION['id_usuario8291']);
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

    public function save_balance()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('STREAMING'));
            if ($tipoacceso >= 2) {

                $costo = $this->request->getPost('streaming[costo]');
                $action = $this->request->getPost('action');

                if ($action == 'balance_netflix') {
                    $id = 000001;

                    //Validar saldo
                    $row = $this->model_streaming->get_balance($id);
                    if ($row->balance == 0) {
                        $new_balance = $costo;
                    } else {
                        $new_balance = $row->balance + $costo;
                    }

                    $array_streaming = [
                        'balance' => $new_balance
                    ];

                    $update = $this->model_streaming->update_saldo_netflix($id, $array_streaming);
                    if ($update) {
                        $txt_log = "$" . $costo . "MNX, Monto agregado para saldo Netflix";
                        $insert_log = $this->model_log->insert_log("000000", "balance_netflix", $txt_log, $_SESSION['id_usuario8291']);
                        echo 1;
                    } else {
                        echo 2;
                    }
                }
                if ($action == 'balance_youtube') {
                    $id = 000002;

                    //Validar saldo
                    $row = $this->model_streaming->get_balance($id);
                    if ($row->balance == 0) {
                        $new_balance = $costo;
                    } else {
                        $new_balance = $row->balance + $costo;
                    }

                    $array_streaming = [
                        'balance' => $new_balance
                    ];

                    $update = $this->model_streaming->update_saldo_netflix($id, $array_streaming);
                    if ($update) {
                        $txt_log = "$" . $costo . "MNX, Monto agregado para saldo Youtube";
                        $insert_log = $this->model_log->insert_log("000000", "balance_youtube", $txt_log, $_SESSION['id_usuario8291']);
                        echo 1;
                    } else {
                        echo 2;
                    }
                }
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    public function history()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('STREAMING'));
            if ($tipoacceso >= 2) {
                $data['tipoacceso'] = $tipoacceso;
                $action = $this->request->getGet('action');

                if ($action == 'balance_history') {
                    echo view('streaming/balance/historynet', $data);
                }
                if ($action == 'balanceYT_history') {
                    $data['streaming'] = $this->model_selectores->get_tipo_streaming();
                    echo view('streaming/balance/historyyt', $data);
                }
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }
}
