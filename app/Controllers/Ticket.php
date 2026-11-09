<?php

namespace App\Controllers;

use App\Libraries\Class_seguridad;
use App\Models\Model_ticket;
use App\Models\Model_selectores;
use App\Models\Model_log;
use App\Libraries\Class_permiso_sucursal;

class Ticket extends BaseController
{
    var $seguridad;
    var $model_ticket;
    var $model_selectores;
    var $model_log;
    var $permiso_sucursal;
    var $permiso_nodo;

    public function __construct()
    {
        $this->model_ticket = new Model_ticket();
        $this->seguridad = new Class_seguridad();
        $this->model_selectores = new Model_selectores();
        $this->model_log = new Model_log();
        $this->permiso_sucursal = new Class_permiso_sucursal();
        $this->permiso_nodo = new Class_permiso_sucursal();
        session();
    }

    public function index()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('UNANSWERED_TICKET'));
            $unanswered_ticket = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('UNANSWERED_TICKET'));
            if ($unanswered_ticket >= 1) {
                $data['tipoacceso'] = $tipoacceso;
                $data['unanswered_ticket'] = $unanswered_ticket;
                $data['tbldepa'] = $this->model_selectores->get_departamentos();
                $data['tbluser'] = $this->model_selectores->get_usuarios();
                echo view('tickets/unanswered', $data);
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    public function get_tickets_unanswered()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('TICKET'));
            if ($tipoacceso >= 1) {

                $dep = $this->request->getGet('dep'); //obtener por get el departamento
                $id = $this->request->getGet('id'); //id usuario
                $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal($_SESSION['sucursales']); //permiso surcusales...
                $tickets = $this->model_ticket->get_tickets_unanswered($dep, $id, $permiso_sucursal);
                //validar permiso especial cerrar ticket
                $close_ticket = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('CLOSE_TICKET'));
                //validar permiso unanswered
                $unanswered_ticket = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('UNANSWERED_TICKET'));

                $array_unanswered = array();
                $tool = "";
                $dep = "";

                if ($tickets) {
                    foreach ($tickets as $items) {
                        if ($unanswered_ticket == 1 && $close_ticket >= 1) {
                            $tool = '<td class=" text-center">
                            <a onclick="close_ticket(\'' . $items->id . '\')" data-toggle="tooltip" title="Cerrar ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-times-circle"></i></a></td>';
                        } else if ($unanswered_ticket == 1) {
                            $tool = '<td class=" text-center">
                            <a onclick="view_ticket(\'' . $items->id . '\');get_all_tickets_client(\'' . $items->idcliente . '\');" data-toggle="tooltip" title="Ver ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a></td>';
                        } else if ($unanswered_ticket == 2 && $close_ticket >= 1) {
                            $tool = '<td class=" text-center">
                            <a onclick="view_ticket(\'' . $items->id . '\');get_all_tickets_client(\'' . $items->idcliente . '\');" data-toggle="tooltip" title="Ver ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                            <a class="btn btn-default btn-icon btn-sm" role="button"  data-trigger="click" data-ajaxpoputo="ticket/popover?id=' . $items->id . '"><i class="far fa-eye"></i></a>
                            <a onclick="close_ticket(\'' . $items->id . '\')" data-toggle="tooltip" title="Cerrar ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-times-circle"></i></a></td>';
                        } else if ($unanswered_ticket == 2) {
                            $tool = '<td class=" text-center">
                            <a onclick="view_ticket(\'' . $items->id . '\');get_all_tickets_client(\'' . $items->idcliente . '\');" data-toggle="tooltip" title="Ver ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                            <a class="btn btn-default btn-icon btn-sm" role="button"  data-trigger="click" data-ajaxpoputo="ticket/popover?id=' . $items->id . '"><i class="far fa-eye"></i></a></td>';
                        } else if ($unanswered_ticket == 3 && $close_ticket >= 1) {
                            $tool = '<td class=" text-center">
                            <a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_ticket(\'' . $items->id . '\',\'' . $items->asunto . '\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                            <a onclick="view_ticket(\'' . $items->id . '\'); get_all_tickets_client(\'' . $items->idcliente . '\');" data-toggle="tooltip" title="Ver ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                            <a class="btn btn-default btn-icon btn-sm" role="button"  data-trigger="click" data-ajaxpoputo="ticket/popover?id=' . $items->id . '"><i class="far fa-eye"></i></a>
                            <a onclick="close_ticket(\'' . $items->id . '\')" data-toggle="tooltip" title="Cerrar ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-times-circle"></i></a></td>';
                        } else if ($unanswered_ticket == 3) {
                            $tool = '<td class=" text-center">
                            <a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_ticket(\'' . $items->id . '\',\'' . $items->asunto . '\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                            <a onclick="view_ticket(\'' . $items->id . '\');get_all_tickets_client(\'' . $items->idcliente . '\');" data-toggle="tooltip" title="Ver ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                            <a class="btn btn-default btn-icon btn-sm" role="button"  data-trigger="click" data-ajaxpoputo="ticket/popover?id=' . $items->id . '"><i class="far fa-eye"></i></a></td>';
                        }

                        $array_items = array(
                            "id" => $items->id,
                            "departamento" => '<span style="color:' . $items->color . ';border: 1px solid ' . $items->color . ';padding: 2px 5px;border-radius: 5px;text-transform: uppercase;font-size: 9.5px;font-weight: 700;">' . strtoupper($items->departamento) . '</span>',
                            "remitente" => "<a href='#clientes/detalleuser?id=" . $items->idcliente . "'>" . $items->remitente . "</a>",
                            "asunto" => "<a href='#ticket/view_ticket?id=" . $items->id . "'>" . $items->asunto . "</a>",
                            "tecnico" => $items->tecnico,
                            "fecha" => date('d/m/Y - h:i a', strtotime($items->fecha)),
                            "ubicacion" => $items->ubicacion,
                            "operador" => $items->operador,
                            "respuesta" => $items->respuesta,
                            "sucursal" => '<span style="color:' . $items->color_su . ';border: 1px solid ' . $items->color_su . ';padding: 2px 5px;border-radius: 5px;text-transform: uppercase;font-size: 9.5px;font-weight: 700;">' . strtoupper($items->sucursal) . '</span>',
                            "tool" => $tool
                        );
                        array_push($array_unanswered, $array_items);
                    }
                }
                echo '{"data":' . json_encode($array_unanswered) . '}';
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }
    public function get_tickets_answered()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('TICKET'));
            if ($tipoacceso >= 1) {

                $dep = $this->request->getGet('dep'); //obtener por get el departamento
                $id = $this->request->getGet('id'); //id usuario
                $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal($_SESSION['sucursales']); //permiso surcusales...
                $tickets = $this->model_ticket->get_tickets_answered($dep, $id, $permiso_sucursal);
                //validar permiso especial cerrar ticket
                $close_ticket = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('CLOSE_TICKET'));
                //validar permiso answered
                $answered_ticket = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('ANSWERED_TICKET'));


                $array_unanswered = array();
                $tool = "";
                $dep = "";

                if ($tickets) {
                    foreach ($tickets as $items) {
                        if ($answered_ticket == 1 && $close_ticket >= 1) {
                            $tool = '<td class=" text-center">
                            <a onclick="close_ticket(\'' . $items->id . '\')" data-toggle="tooltip" title="Cerrar ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-times-circle"></i></a></td>';
                        } else if ($answered_ticket == 1) {
                            $tool = '<td class=" text-center">
                            <a onclick="view_ticket(\'' . $items->id . '\');get_all_tickets_client(\'' . $items->idcliente . '\');" data-toggle="tooltip" title="Ver ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a></td>';
                        } else if ($answered_ticket == 2 && $close_ticket >= 1) {
                            $tool = '<td class=" text-center">
                            <a onclick="view_ticket(\'' . $items->id . '\');get_all_tickets_client(\'' . $items->idcliente . '\');" data-toggle="tooltip" title="Ver ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                            <a class="btn btn-default btn-icon btn-sm" role="button"  data-trigger="click" data-ajaxpoputo="ticket/popover?id=' . $items->id . '"><i class="far fa-eye"></i></a>
                            <a onclick="close_ticket(\'' . $items->id . '\')" data-toggle="tooltip" title="Cerrar ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-times-circle"></i></a></td>';
                        } else if ($answered_ticket == 2) {
                            $tool = '<td class=" text-center">
                            <a onclick="view_ticket(\'' . $items->id . '\');get_all_tickets_client(\'' . $items->idcliente . '\');" data-toggle="tooltip" title="Ver ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                            <a class="btn btn-default btn-icon btn-sm" role="button"  data-trigger="click" data-ajaxpoputo="ticket/popover?id=' . $items->id . '"><i class="far fa-eye"></i></a></td>';
                        } else if ($answered_ticket == 3 && $close_ticket >= 1) {
                            $tool = '<td class=" text-center">
                            <a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_ticket(\'' . $items->id . '\',\'' . $items->asunto . '\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                            <a onclick="view_ticket(\'' . $items->id . '\'); get_all_tickets_client(\'' . $items->idcliente . '\');" data-toggle="tooltip" title="Ver ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                            <a class="btn btn-default btn-icon btn-sm" role="button"  data-trigger="click" data-ajaxpoputo="ticket/popover?id=' . $items->id . '"><i class="far fa-eye"></i></a>
                            <a onclick="close_ticket(\'' . $items->id . '\')" data-toggle="tooltip" title="Cerrar ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-times-circle"></i></a></td>';
                        } else if ($answered_ticket == 3) {
                            $tool = '<td class=" text-center">
                            <a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_ticket(\'' . $items->id . '\',\'' . $items->asunto . '\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                            <a onclick="view_ticket(\'' . $items->id . '\');get_all_tickets_client(\'' . $items->idcliente . '\');" data-toggle="tooltip" title="Ver ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                            <a class="btn btn-default btn-icon btn-sm" role="button"  data-trigger="click" data-ajaxpoputo="ticket/popover?id=' . $items->id . '"><i class="far fa-eye"></i></a></td>';
                        }

                        $array_items = array(
                            "id" => $items->id,
                            "departamento" => '<span style="color:' . $items->color . ';border: 1px solid ' . $items->color . ';padding: 2px 5px;border-radius: 5px;text-transform: uppercase;font-size: 9.5px;font-weight: 700;">' . strtoupper($items->departamento) . '</span>',
                            "remitente" => "<a href='#clientes/detalleuser?id=" . $items->idcliente . "'>" . $items->remitente . "</a>",
                            "asunto" => "<a href='#ticket/view_ticket?id=" . $items->id . "'>" . $items->asunto . "</a>",
                            "tecnico" => $items->tecnico,
                            "fecha" => date('d/m/Y - h:i a', strtotime($items->fecha)),
                            "ubicacion" => $items->ubicacion,
                            "operador" => $items->operador,
                            "respuesta" => $items->respuesta,
                            "sucursal" => '<span style="color:' . $items->color_su . ';border: 1px solid ' . $items->color_su . ';padding: 2px 5px;border-radius: 5px;text-transform: uppercase;font-size: 9.5px;font-weight: 700;">' . strtoupper($items->sucursal) . '</span>',
                            "tool" => $tool
                        );
                        array_push($array_unanswered, $array_items);
                    }
                }
                echo '{"data":' . json_encode($array_unanswered) . '}';
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }
    public function get_tickets_closed()
    {
       
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('TICKET'));
            if ($tipoacceso >= 1) {

                $dep = $this->request->getGet('dep'); //obtener por get el departamento
                $id = $this->request->getGet('id'); //id usuario
                $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal_ticket($_SESSION['sucursales']); //permiso surcusales...
                $tickets = $this->model_ticket->get_tickets_closed($dep, $id, $permiso_sucursal);
                //validar permiso especial cerrar ticket
                $close_ticket = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('CLOSE_TICKET'));
                //validar permiso answered
                $closed_ticket = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('CLOSED_TICKET'));

                $array_unanswered = array();
                $tool = "";
                $dep = "";

                if ($tickets) {
                    foreach ($tickets as $items) {
                        if ($closed_ticket == 1) {
                            $tool = '<td class=" text-center">
                            <a onclick="view_ticket(\'' . $items->id . '\');get_all_tickets_client(\'' . $items->idcliente . '\');" data-toggle="tooltip" title="Ver ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a></td>';
                        } else if ($closed_ticket == 2) {
                            $tool = '<td class=" text-center">
                            <a onclick="view_ticket(\'' . $items->id . '\');get_all_tickets_client(\'' . $items->idcliente . '\');" data-toggle="tooltip" title="Ver ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                            <a class="btn btn-default btn-icon btn-sm" role="button"  data-trigger="click" data-ajaxpoputo="ticket/popover?id=' . $items->id . '"><i class="far fa-eye"></i></a>
                            </td>';
                        } else if ($closed_ticket == 3) {
                            $tool = '<td class=" text-center">
                            <a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_ticket(\'' . $items->id . '\',\'' . $items->asunto . '\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                            <a onclick="view_ticket(\'' . $items->id . '\');get_all_tickets_client(\'' . $items->idcliente . '\');" data-toggle="tooltip" title="Ver ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                            <a class="btn btn-default btn-icon btn-sm" role="button"  data-trigger="click" data-ajaxpoputo="ticket/popover?id=' . $items->id . '"><i class="far fa-eye"></i></a>
                           </td>';
                        }

                        $array_items = array(
                            "id" => $items->id,
                            "departamento" => '<span style="color:' . $items->color . ';border: 1px solid ' . $items->color . ';padding: 2px 5px;border-radius: 5px;text-transform: uppercase;font-size: 9.5px;font-weight: 700;">' . strtoupper($items->departamento) . '</span>',
                            "remitente" => "<a href='#clientes/detalleuser?id=" . $items->idcliente . "'>" . $items->remitente . "</a>",
                            "asunto" => "<a href='#ticket/view_ticket?id=" . $items->id . "'>" . $items->asunto . "</a>",
                            "tecnico" => $items->tecnico,
                            "fecha" => date('d/m/Y - h:i a', strtotime($items->fecha)),
                            "ubicacion" => $items->ubicacion,
                            "operador" => $items->operador,
                            "respuesta" => $items->respuesta,
                            "sucursal" => '<span style="color:' . $items->color_su . ';border: 1px solid ' . $items->color_su . ';padding: 2px 5px;border-radius: 5px;text-transform: uppercase;font-size: 9.5px;font-weight: 700;">' . strtoupper($items->sucursal) . '</span>',
                            "tool" => $tool
                        );
                        array_push($array_unanswered, $array_items);
                    }
                }
                echo '{"data":' . json_encode($array_unanswered) . '}';
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    public function delete_ticket()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('TICKET'));
            if ($tipoacceso >= 1) {
                $id = $this->request->getPost('id');
                var_dump($id);
                $asunto = $this->request->getPost('asunto');
                $row = $this->model_ticket->get_id_client($id);
                $delete = $this->model_ticket->delete_soporte($id);
                if ($delete) {
                    $delete_t = $this->model_ticket->delete_ticket($id);
                    if ($delete_t) {
                        $txt_log = "Ticket eliminado <b>#" . $id . "";
                        $insert_log = $this->model_log->insert_log($row->idcliente, "Tickets", $txt_log, $_SESSION['id_usuario8291']);
                        echo 1;
                    }
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

    public function newticket()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('TICKET'));
            if ($tipoacceso >= 1) {
                $idcliente = $this->request->getPost('idcliente');
                $dp = $this->request->getPost('dp');
                $idsoporte = $this->request->getPost('idsoporte');
                $asunto = $this->request->getPost('asunto');
                $solicitante = $this->request->getPost('solicitante');
                $fechavisita = $this->request->getPost('fechavisita');
                $turno = $this->request->getPost('turno');
                $agendado = $this->request->getPost('agendado');
                //$adjunto=$this->request->getPost('adjunto');
                $contenido = $this->request->getPost('contenido');
                //$action=$this->request->getPost('action');

                $location = 'public/uploads/soporte/';
                $file_name = "";
                if (isset($_FILES['adjunto']['name'])) {

                    $count_files = count($_FILES['adjunto']['name']);

                    for ($key = 0; $key < $count_files; $key++) {

                        // Check if file is selected
                        if (isset($_FILES['adjunto']['name'][$key]) && $_FILES['adjunto']['size'][$key] > 0) {

                            $name_origen = $_FILES['adjunto']['name'][$key];
                            $send_to = $location . basename($name_origen);
                            $tmp  = $_FILES['adjunto']['tmp_name'][$key];
                            if ($count_files == 1) {
                                $file_name .= $name_origen;
                            } else {
                                if ($count_files == $key + 1) {
                                    $file_name .= $name_origen;
                                } else {
                                    $file_name .= $name_origen . ",";
                                }
                            }
                            if (is_dir($location)) {
                                move_uploaded_file($tmp, $send_to);
                            } else {
                                mkdir($location, 7777);
                                move_uploaded_file($tmp, $send_to);
                            }
                        }
                    }
                }

                date_default_timezone_set('America/Mexico_City');
                $fecha_visita = str_replace('/', '-', $fechavisita);
                $fech = date('Y-m-d H:i:s', strtotime($fecha_visita));

                if ($idcliente <> "") {
                    $arraysoporte = [
                        'idcliente' => $idcliente, 'idsoporte' => $idsoporte, 'asunto' => $asunto, 'fecha_soporte' => date('Y-m-d H:i:s'),
                        'estado' => 'abierto', 'operador' => $_SESSION['nombre'], 'solicitante' => $solicitante, 'fechavisita' => $fech,
                        'turno' => $turno, 'agendado' => $agendado, 'lastdate' => date('Y-m-d H:i:s'), 'dp' => $dp
                    ];
                    $insert_soporte = $this->model_ticket->new_soporte($arraysoporte);
                    if ($insert_soporte) {
                        $r = $this->model_ticket->last_id();
                        $lastid = $r->last;
                        $arrayticket = [
                            'idticket' => $lastid, 'adjunto' => $file_name, 'contenido' => $contenido, 'fecha_hora' => date('Y-m-d H:i:s'),
                            'idtecnico' => 0, 'state' => 0, 'nota' => 0
                        ];

                        $insert_ticket = $this->model_ticket->new_ticket($arrayticket);
                        if ($insert_ticket) {
                            $txt_log = "Ticket abiero <b>#" . $lastid . "";
                            $insert_log = $this->model_log->insert_log($idcliente, "Tickets", $txt_log, $_SESSION['id_usuario8291']);
                            echo 1;
                        }
                    } else {
                        echo 2;
                    }
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

    public function view_ticket()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('TICKET'));
            if ($tipoacceso >= 1) {
                $id = $this->request->getGet('id');
                $soporte = $this->model_ticket->get_soporte_id($id);
                $div = "";
                //validar permiso Abierto,respondido y cerrado
                $unanswered_ticket = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('UNANSWERED_TICKET'));
                $answered_ticket = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('ANSWERED_TICKET'));
                $closed_ticket = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('CLOSED_TICKET'));

                if ($unanswered_ticket >= 1 || $answered_ticket >= 1 || $closed_ticket >= 1) {
                    if ($soporte) {
                        $ticket = $this->model_ticket->get_ticket_id($id);
                        if ($ticket) {
                            foreach ($ticket as $itemT) {

                                $hreft = "<a href='#clientes/detalleuser?id=" . $itemT->idclient . "'>" . $itemT->nombre . "</a>";

                                if ($unanswered_ticket == 3) {
                                    $trash = ' | <i class="d-print-none far fa-trash-alt" onclick="deleteitem(\'' . $itemT->id . '\',\'' . $id . '\')" style="cursor: pointer;"></i>';
                                } else if ($answered_ticket == 3) {
                                    $trash = ' | <i class="d-print-none far fa-trash-alt" onclick="deleteitem(\'' . $itemT->id . '\',\'' . $id . '\')" style="cursor: pointer;"></i>';
                                } else if ($closed_ticket == 3) {
                                    $trash = ' | <i class="d-print-none far fa-trash-alt" onclick="deleteitem(\'' . $itemT->id . '\',\'' . $id . '\')" style="cursor: pointer;"></i>';
                                } else {
                                    $trash = '<i></i>';
                                }

                                if ($itemT->state == 0 && $itemT->idtecnico == 0) {
                                    $div .= '
                                    <ul class="media-list media-list-with-divider media-messaging">
                                    <li class="tkcliente media media-sm item-' . $itemT->id . '">
                                    <div class="pull-left text-center" style="margin-right: 10px;">
                                    <img class="imguser" data-name="' . $itemT->nombre . '" width="80" height="80">
                                    <p><b>Cliente</b></p>
                                    </div>
                                    <div class="media-body inbox">
                                    <h5 class="media-heading"><b>' . $hreft . '</b>
                                    <div class="pull-right">
                                    <small style="font-size: 12px;font-weight: 600;color: #6aa1c3;">' . date('d-m-Y - H:i a', strtotime($itemT->fecha_hora)) . '
                                    </small> 
                                         ' . $trash . '
                                    </div>
                                    </h5>
                                    <div style="font-size: 12.5px;margin-top: 11px;min-height: 50px;"><p>' . htmlspecialchars_decode($itemT->contenido) . '</p></div>
                                    <ul class="attached-document clearfix">
                                    ';
                                    $array_adjunto = explode(",", $itemT->adjunto);
                                    foreach ($array_adjunto as $value) {
                                        $tmp = explode('.', $value);
                                        $file_ext = end($tmp);
                                        $icon = "";
                                        if ($file_ext == "jpg" || $file_ext == "jpeg" || $file_ext == "png" || $file_ext == "gif" || $file_ext == "PNG" || $file_ext == "JPG" || $file_ext == "JPEG") {
                                            $div .= '
                                            <li class="fa-camera" style="margin-bottom: 0;">
                                            <div class="document-file" style="height: auto;">
                                            <a  href="' . base_url() . 'public/uploads/soporte/' . $value . '" data-toggle="lightbox" data-gallery="gallery-soporte-2" data-type="image">
                                            <img src="' . base_url() . 'public/uploads/soporte/' . $value . '" alt="Imagen no disponible"></a>
                                            </div>
                                            <div class="document-name">
                                            <a href="' . base_url() . 'public/uploads/soporte/' . $value . '"><i class="fas fa-download"></i>  ' . $value . '</a></div>
                                            </li>
                                            ';
                                        } else if ($itemT->adjunto == "") {
                                            //image vacia
                                        } else {
                                            $div .= '
                                            <li class="fa-file" style="margin-bottom: 0;">
                                            <div class="document-file">
                                            <a href="' . base_url() . 'public/uploads/soporte/' . $value . '"><i class="far fa-file-pdf"></i></a>
                                            </div>
                                            <div class="document-name">
                                            <a href="' . base_url() . 'public/uploads/soporte/' . $value . '"><i class="fas fa-download"></i> ' . $value . '</a></div>
                                            </li>
                                            ';
                                        }
                                    }
                                    $div .= '
                                    </ul>
                                    </div>
                                    </li>
                                    
                                    ';
                                } //if state==0 && idtecnico==0
                                if ($itemT->state == 1 && $itemT->nota == 0) {
                                    $div .= '
                                    <li class="tksoporte media media-sm item-' . $itemT->id . '">
                                    <div class="pull-left text-center" style="margin-right: 10px;">
                                    <img class="imguser" data-name="' . $itemT->tecnico . '" width="80" height="80">
                                    <p><b>Empleado</b></p>
                                    </div>
                                    <div class="media-body inbox">
                                    <h5 class="media-heading"><b>' . strtoupper($itemT->tecnico) . '</b>
                                    <div class="pull-right">
                                    <small style="font-size: 12px;font-weight: 600;color: #6aa1c3;">' . date('d-m-Y - H:i a', strtotime($itemT->fecha_hora)) . '
                                    </small>
                                        ' . $trash . '
                                    </div>
                                    </h5>
                                    <div style="font-size: 12.5px;margin-top: 11px;min-height: 50px;"><p>' . htmlspecialchars_decode($itemT->contenido) . '</p></div>
                                    <ul class="attached-document clearfix">
                                    ';
                                    $array_adjunto = explode(",", $itemT->adjunto);
                                    foreach ($array_adjunto as $value) {
                                        $tmp = explode('.', $value);
                                        $file_ext = end($tmp);
                                        $icon = "";
                                        if ($file_ext == "jpg" || $file_ext == "jpeg" || $file_ext == "png" || $file_ext == "gif" || $file_ext == "PNG" || $file_ext == "JPG" || $file_ext == "JPEG") {
                                            $div .= '
                                            <li class="fa-camera" style="margin-bottom: 0;">
                                            <div class="document-file" style="height: auto;">
                                            <a  href="' . base_url() . 'public/uploads/soporte/' . $value . '" data-toggle="lightbox" data-gallery="gallery-soporte-2" data-type="image">
                                            <img src="' . base_url() . 'public/uploads/soporte/' . $value . '" alt="Imagen no disponible"></a>
                                            </div>
                                            <div class="document-name">
                                            <a href="' . base_url() . 'public/uploads/soporte/' . $value . '"><i class="fas fa-download"></i>  ' . $value . '</a></div>
                                            </li>
                                            ';
                                        } else if ($itemT->adjunto == "") {
                                            //image vacia
                                        } else {
                                            $div .= '
                                            <li class="fa-file" style="margin-bottom: 0;">
                                            <div class="document-file">
                                            <a href="' . base_url() . 'public/uploads/soporte/' . $value . '"><i class="far fa-file-pdf"></i></a>
                                            </div>
                                            <div class="document-name">
                                            <a href="' . base_url() . 'public/uploads/soporte/' . $value . '"><i class="fas fa-download"></i> ' . $value . '</a></div>
                                            </li>
                                            ';
                                        }
                                    }
                                    $div .= '
                                    </ul>
                                    </div>
                                    </li>
                                    ';
                                } //if reply tecnico state=1
                                if ($itemT->state == 1 && $itemT->nota == 1) {
                                    $div .= '
                                    <li class="tknota media media-sm item-' . $itemT->id . '">
                                    <div class="pull-left text-center" style="margin-right: 10px;">
                                    <img class="imguser" data-name="' . $itemT->tecnico . '" width="80" height="80">
                                    <p><b>Empleado</b></p>
                                    </div>
                                    <div class="media-body inbox">
                                    <h5 class="media-heading"><b>' . strtoupper($itemT->tecnico) . '</b>
                                    <div class="pull-right">
                                    <small style="font-size: 12px;font-weight: 600;color: #6aa1c3;">' . date('d-m-Y - H:i a', strtotime($itemT->fecha_hora)) . '
                                    </small> 
                                        ' . $trash . '
                                    </div>
                                    </h5>
                                    <div style="font-size: 12.5px;margin-top: 11px;min-height: 50px;"><p>' . htmlspecialchars_decode($itemT->contenido) . '</p></div>
                                    <ul class="attached-document clearfix">
                                    ';
                                    $array_adjunto = explode(",", $itemT->adjunto);
                                    foreach ($array_adjunto as $value) {
                                        $tmp = explode('.', $value);
                                        $file_ext = end($tmp);
                                        $icon = "";
                                        if ($file_ext == "jpg" || $file_ext == "jpeg" || $file_ext == "png" || $file_ext == "gif" || $file_ext == "PNG" || $file_ext == "JPG" || $file_ext == "JPEG") {
                                            $div .= '
                                            <li class="fa-camera" style="margin-bottom: 0;">
                                            <div class="document-file" style="height: auto;">
                                            <a  href="' . base_url() . 'public/uploads/soporte/' . $value . '" data-toggle="lightbox" data-gallery="gallery-soporte-2" data-type="image">
                                            <img src="' . base_url() . 'public/uploads/soporte/' . $value . '" alt="Imagen no disponible"></a>
                                            </div>
                                            <div class="document-name">
                                            <a href="' . base_url() . 'public/uploads/soporte/' . $value . '"><i class="fas fa-download"></i>  ' . $value . '</a></div>
                                            </li>
                                            ';
                                        } else if ($itemT->adjunto == "") {
                                            //image vacia
                                        } else {
                                            $div .= '
                                            <li class="fa-file" style="margin-bottom: 0;">
                                            <div class="document-file">
                                            <a href="' . base_url() . 'public/uploads/soporte/' . $value . '"><i class="far fa-file-pdf"></i></a>
                                            </div>
                                            <div class="document-name">
                                            <a href="' . base_url() . 'public/uploads/soporte/' . $value . '"><i class="fas fa-download"></i> ' . $value . '</a></div>
                                            </li>
                                            ';
                                        }
                                    }
                                    $div .= '
                                    </ul>
                                    </div>
                                    </li>
                                    </ul>
                                    ';
                                }
                            } //fin foreach tickets
                        } //if ticket
                        foreach ($soporte as $itemsS) {
                            if ($itemsS->fecha_cerrado == "0000-00-00 00:00:00") {
                                $fecha_c = "Ticket sin cerrar";
                            } else {
                                $fecha_c = date('Y-m-d - H:i a', strtotime($itemsS->fecha_cerrado));
                            }
                        }
                        $data['fecha_c'] = $fecha_c;
                        $data['soporte'] = $soporte[0];
                        $data['div'] = $div;
                        $data['dep'] = $this->model_selectores->get_departamentos();
                        $data['users'] = $this->model_selectores->get_usuarios();
                        $data['unanswered_ticket'] = $unanswered_ticket;
                        $data['answered_ticket'] = $answered_ticket;
                        $data['closed_ticket'] = $closed_ticket;
                    } //if soporte
                } //if cualquier permiso
                echo view('tickets/tabs/view_ticket', $data);
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    public function get_all_tickets_client()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('TICKET'));
            if ($tipoacceso >= 1) {
                $id = $this->request->getGet('idcliente'); //id cliente
                $tickets = $this->model_ticket->get_all_tickets_client($id);
                $array_all = array();
                if ($tickets) {
                    foreach ($tickets as $items) {

                        $status = "";
                        if ($items->estado == 'abierto') {
                            $status = "<span class='label label-success'>" . $items->estado . "</span>";
                        } else if ($items->estado == 'respondido') {
                            $status = "<span class='label label-warning'>" . $items->estado . "</span>";
                        } else if ($items->estado == 'cerrado') {
                            $status = "<span class='label label-danger'>" . $items->estado . "</span>";
                        }
                        $array_items = array(
                            "id" => $items->id,
                            "asunto" => "<a href='#ticket/view_ticket?id=" . $items->id . "'>" . $items->asunto . "</a>",
                            "fecha" => date('d/m/Y - h:i a', strtotime($items->fecha)),
                            "tecnico" => $items->tecnico,
                            "estado" => $status,
                            "operador" => $items->operador,
                            "respuesta" => $items->respuesta,
                        );
                        array_push($array_all, $array_items);
                    }
                }
                echo '{"data":' . json_encode($array_all) . '}';
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    public function add_reply()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('TICKET'));
            if ($tipoacceso >= 1) {
                //codigo
                $idticket = $this->request->getPost('idticket');
                $contenido = $this->request->getPost('htmlreply');
                $idtecnico = $this->request->getPost('idtecnico');

                $row = $this->model_ticket->get_id_client($idticket);

                $location = 'public/uploads/soporte/';
                $file_name = "";
                if (isset($_FILES['adjunto']['name'])) {
                    $count_files = count($_FILES['adjunto']['name']);
                    for ($key = 0; $key < $count_files; $key++) {
                        // Check if file is selected
                        if (isset($_FILES['adjunto']['name'][$key]) && $_FILES['adjunto']['size'][$key] > 0) {
                            $name_origen = $_FILES['adjunto']['name'][$key];
                            $send_to = $location . basename($name_origen);
                            $tmp  = $_FILES['adjunto']['tmp_name'][$key];
                            if ($count_files == 1) {
                                $file_name .= $name_origen;
                            } else {
                                if ($count_files == $key + 1) {
                                    $file_name .= $name_origen;
                                } else {
                                    $file_name .= $name_origen . ",";
                                }
                            }
                            if (is_dir($location)) {
                                move_uploaded_file($tmp, $send_to);
                            } else {
                                mkdir($location, 7777);
                                move_uploaded_file($tmp, $send_to);
                            }
                        }
                    }
                }

                $arrayreply = [
                    'idticket' => $idticket, 'adjunto' => $file_name, 'contenido' => $contenido, 'fecha_hora' => date('Y-m-d H:i:s'),
                    'idtecnico' => $idtecnico, 'state' => 1, 'nota' => 0
                ];
                $arraysoport = ['estado' => 'respondido', 'lastdate' => date('Y-m-d H:i:s')];
                $insert_reply = $this->model_ticket->new_ticket($arrayreply);
                if ($insert_reply) {
                    $txt_log = "Ticket respondido <b>#" . $idticket . "";
                    $insert_log = $this->model_log->insert_log($row->idcliente, "Tickets", $txt_log, $_SESSION['id_usuario8291']);
                    $update_soporte = $this->model_ticket->update_reporte_by_id($idticket, $arraysoport);
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

    public function add_note()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('TICKET'));
            if ($tipoacceso >= 1) {
                //codigo
                $idticket = $this->request->getPost('idticket');
                $contenido = $this->request->getPost('htmlnota');
                $idtecnico = $this->request->getPost('idtecnico');

                $row = $this->model_ticket->get_id_client($idticket);

                $location = 'public/uploads/soporte/';
                $file_name = "";
                if (isset($_FILES['adjunto']['name'])) {
                    $count_files = count($_FILES['adjunto']['name']);
                    for ($key = 0; $key < $count_files; $key++) {
                        // Check if file is selected
                        if (isset($_FILES['adjunto']['name'][$key]) && $_FILES['adjunto']['size'][$key] > 0) {
                            $name_origen = $_FILES['adjunto']['name'][$key];
                            $send_to = $location . basename($name_origen);
                            $tmp  = $_FILES['adjunto']['tmp_name'][$key];
                            if ($count_files == 1) {
                                $file_name .= $name_origen;
                            } else {
                                if ($count_files == $key + 1) {
                                    $file_name .= $name_origen;
                                } else {
                                    $file_name .= $name_origen . ",";
                                }
                            }
                            if (is_dir($location)) {
                                move_uploaded_file($tmp, $send_to);
                            } else {
                                mkdir($location, 7777);
                                move_uploaded_file($tmp, $send_to);
                            }
                        }
                    }
                }

                $arrayreply = [
                    'idticket' => $idticket, 'adjunto' => $file_name, 'contenido' => $contenido, 'fecha_hora' => date('Y-m-d H:i:s'),
                    'idtecnico' => $idtecnico, 'state' => 1, 'nota' => 1
                ];
                $arraysoport = ['estado' => 'respondido', 'lastdate' => date('Y-m-d H:i:s')];
                $insert_reply = $this->model_ticket->new_ticket($arrayreply);
                if ($insert_reply) {
                    $txt_log = "Ticket respondido <b>#" . $idticket . " - nota agregada";
                    $insert_log = $this->model_log->insert_log($row->idcliente, "Tickets", $txt_log, $_SESSION['id_usuario8291']);
                    $update_soporte = $this->model_ticket->update_reporte_by_id($idticket, $arraysoport);
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

    public function delete_reply()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('TICKET'));
            if ($tipoacceso >= 1) {
                $idt = $this->request->getPost('id');
                $ids = $this->request->getPost('idticket');
                $row = $this->model_ticket->get_id_client($ids);
                $delete = $this->model_ticket->delete_reply_id($idt);
                if ($delete) {
                    $txt_log = "Ticket  <b>#" . $ids . " - respuesta eliminada";
                    $insert_log = $this->model_log->insert_log($row->idcliente, "Tickets", $txt_log, $_SESSION['id_usuario8291']);
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

    public function reply_close()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('TICKET'));
            if ($tipoacceso >= 1) {
                //codigo
                $idticket = $this->request->getGet('idticket');
                $contenido = $this->request->getGet('htmlreply');
                $idtecnico = $this->request->getGet('idtecnico');
                $img = $this->request->getGet('adjunto');
                $row = $this->model_ticket->get_id_client($idticket);

                $location = 'public/uploads/soporte/';
                $file_name = "";
                if (isset($_FILES["'$img'"]['name'])) {
                    $count_files = count($_FILES["'$img'"]['name']);
                    for ($key = 0; $key < $count_files; $key++) {
                        // Check if file is selected
                        if (isset($_FILES["'$img'"]['name'][$key]) && $_FILES["'$img'"]['size'][$key] > 0) {
                            $name_origen = $_FILES["'$img'"]['name'][$key];
                            $send_to = $location . basename($name_origen);
                            $tmp  = $_FILES["'$img'"]['tmp_name'][$key];
                            if ($count_files == 1) {
                                $file_name .= $name_origen;
                            } else {
                                if ($count_files == $key + 1) {
                                    $file_name .= $name_origen;
                                } else {
                                    $file_name .= $name_origen . ",";
                                }
                            }
                            if (is_dir($location)) {
                                move_uploaded_file($tmp, $send_to);
                            } else {
                                mkdir($location, 7777);
                                move_uploaded_file($tmp, $send_to);
                            }
                        }
                    }
                }

                $arrayreply = [
                    'idticket' => $idticket, 'adjunto' => $file_name, 'contenido' => $contenido, 'fecha_hora' => date('Y-m-d H:i:s'),
                    'idtecnico' => $idtecnico, 'state' => 1, 'nota' => 0
                ];
                $arraysoport = ['estado' => 'cerrado', 'lastdate' => date('Y-m-d H:i:s'), 'fecha_cerrado' => date('Y-m-d H:i:s')];
                $insert_reply = $this->model_ticket->new_ticket($arrayreply);
                if ($insert_reply) {
                    $txt_log = "Ticket Cerrado <b>#" . $idticket;
                    $insert_log = $this->model_log->insert_log($row->idcliente, "Tickets", $txt_log, $_SESSION['id_usuario8291']);
                    $update_soporte = $this->model_ticket->update_reporte_close_by_id($idticket, $arraysoport);
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

    public function popover()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('TICKET'));
            if ($tipoacceso >= 1) {
                $id = $this->request->getGet('id');
                $ticket = $this->model_ticket->get_ticket_id($id);
                $div = "";
                if ($ticket) {
                    foreach ($ticket as $itemT) {
                        if ($itemT->state == 0 && $itemT->idtecnico == 0) {
                            $div .= '
                            <h5>Ticket #' . $itemT->idticket . ' - </h5><h6>' . $itemT->asunto . '</h6>
                            <ul class="media-list media-list-with-divider media-messaging">
                            <li class="tkcliente media media-sm item-' . $itemT->id . '">
                            <div class="pull-left text-center" style="margin-right: 10px;">
                            <img class="imguser" data-name="' . $itemT->nombre . '">
                            <p><b>Cliente</b></p>
                            </div>
                            <div class="media-body inbox">
                            <h6 class=""><b>' . strtoupper($itemT->nombre) . '</b>
                            <div class="pull-right">
                            <small style="font-size: 10px; color: #6aa1c3;">' . date('d-m-Y - H:i a', strtotime($itemT->fecha_hora)) . '
                            </small> 
                            </div>
                            </h6><br><br>
                            <div style="font-size: 10px;"><p>' . htmlspecialchars_decode($itemT->contenido) . '</p></div>
                            <ul class="attached-document clearfix">
                            ';
                            $array_adjunto = explode(",", $itemT->adjunto);
                            foreach ($array_adjunto as $value) {
                                $tmp = explode('.', $value);
                                $file_ext = end($tmp);
                                $icon = "";
                                if ($file_ext == "jpg" || $file_ext == "jpeg" || $file_ext == "png" || $file_ext == "gif" || $file_ext == "PNG" || $file_ext == "JPG" || $file_ext == "JPEG") {
                                    $div .= '
                                    <li class="fa-camera">
                                    <div class="document-file" style="height: auto; ">
                                    <a  href="' . base_url() . 'public/uploads/soporte/' . $value . '" data-toggle="lightbox" data-gallery="gallery-soporte-2" data-type="image">
                                    <img src="' . base_url() . 'public/uploads/soporte/' . $value . '" alt="Imagen no disponible"></a>
                                    </div>
                                    <div class="document-name">
                                    <a href="' . base_url() . 'public/uploads/soporte/' . $value . '"><i class="fas fa-download"></i>  ' . $value . '</a></div>
                                    </li>
                                    ';
                                } else if ($itemT->adjunto == "") {
                                    //image vacia
                                } else {
                                    $div .= '
                                    <li class="fa-file" >
                                    <div class="document-file">
                                    <a href="' . base_url() . 'public/uploads/soporte/' . $value . '"><i class="far fa-file-pdf"></i></a>
                                    </div>
                                    <div class="document-name">
                                    <a href="' . base_url() . 'public/uploads/soporte/' . $value . '"><i class="fas fa-download"></i> ' . $value . '</a></div>
                                    </li>
                                    ';
                                }
                            }
                            $div .= '
                            </ul>
                            </div>
                            </li>
                            </ul>
                            ';
                        } //if state==0 && idtecnico==0

                    } //fin foreach tickets
                }
                echo $div;
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    public function close_ticket()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('TICKET'));
            if ($tipoacceso >= 1) {
                $id = $this->request->getPost('id');
                $motivo = $this->request->getPost('motivo');
                $close = $this->model_ticket->close_ticket($id, $motivo);
                if ($close) {
                    $insert_log = $this->model_log->insert_log("000000", "Tickets", "Ticket cerrado <b>#" . $id . " de la tabla soporte por " . $_SESSION['nombre'] . " </b>", $_SESSION['id_usuario8291']);
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

    public function answered()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('TICKET'));
            $answered_ticket = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('ANSWERED_TICKET'));
            if ($answered_ticket >= 1) {
                $data['answered_ticket'] = $answered_ticket;
                $data['tipoacceso'] = $tipoacceso;
                $data['tbldepa'] = $this->model_selectores->get_departamentos();
                $data['tbluser'] = $this->model_selectores->get_usuarios();
                echo view('tickets/answered', $data);
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }
    public function closed()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('TICKET'));
            $closed_ticket = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('CLOSED_TICKET'));
            if ($closed_ticket >= 1) {
                $data['closed_ticket'] = $closed_ticket;
                $data['tipoacceso'] = $tipoacceso;
                $data['tbldepa'] = $this->model_selectores->get_departamentos();
                $data['tbluser'] = $this->model_selectores->get_usuarios();
                echo view('tickets/closed', $data);
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    public function update_ticket_option()
    {
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('TICKET'));
            if ($tipoacceso >= 1) {
                $id = $this->request->getPost('id_soporte');
                $asunto = $this->request->getPost('asunto');
                $dep = $this->request->getPost('dp');
                $tecni = $this->request->getPost('tecnico');
                $estado = $this->request->getPost('estado');
                $solicitante = $this->request->getPost('solicitante');
                $fechavisita = $this->request->getPost('fechavisita');
                $turno = $this->request->getPost('turno');
                $agendado = $this->request->getPost('agendado');
                $motivo = $this->request->getPost('motivo_cierre');

                date_default_timezone_set('America/Mexico_City');
                $fecha_visita = str_replace('/', '-', $fechavisita);
                $fech = date('Y-m-d H:i:s', strtotime($fecha_visita));
                $array_post = [
                    'idsoporte' => $tecni, 'asunto' => $asunto, 'estado' => $estado, 'solicitante' => $solicitante,
                    'fechavisita' => $fech, 'turno' => $turno, 'agendado' => $agendado, 'dp' => $dep, 'motivo_cierre' => $motivo
                ];
                $update = $this->model_ticket->update_ticket_option($id, $array_post);
                if ($update) {
                    $insert_log = $this->model_log->insert_log("000000", "Tickets", "Ticket modificado <b>#" . $id . " de la tabla soporte por " . $_SESSION['nombre'] . " </b>", $_SESSION['id_usuario8291']);
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
}
