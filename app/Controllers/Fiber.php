<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Libraries\Class_seguridad;
use App\Libraries\Class_oids;
use App\Models\Model_fiber;
use App\Models\OltModel;
use App\Models\Model_selectores;
use App\Models\Model_log;


class Fiber extends BaseController
{
  var $seguridad;
  var $model_fiber;
  var $model_selectores;
  var $model_log;
  var $library_oid;

  public function __construct()
  {

    $this->seguridad = new Class_seguridad();
    $this->model_fiber = new Model_fiber();
    $this->model_selectores = new Model_selectores();
    $this->model_log = new Model_log();
    $this->library_oid = new Class_oids();
    date_default_timezone_set('America/Mexico_City');
    session();
  }


  public function index()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('FIBER_DASHBOARD'));
      if ($tipoacceso > 0) {
        $server = $this->model_fiber->get_olt();
        $en_olt = 0;
        $activas = 0;
        $offline = 0;
        $sin_autorizar = 0;
        if ($server) {
          foreach ($server as $item) {
            $result_snmp_con = "snmp-fail";
            snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
            $array = \App\Libraries\Snmp::walk("$item->ip", "$item->comunidad_escritura", "1.3.6.1.2.1.1.1");
            if ($array) {
              foreach ($array as $key) {
                $result_snmp_con = $key;
              }
            }
            if ($result_snmp_con == 'Huawei Integrated Access Software') {
              $array1 = \App\Libraries\Snmp::walk("$item->ip", "$item->comunidad_escritura", ".1.3.6.1.4.1.2011.6.128.1.1.2.46.1.15");
              if ($array1) {
                $en_olt += count($array1);
                foreach ($array1 as $value) {
                  if ($value == 1) {
                    $activas++;
                  } else {
                    $offline++;
                  }
                }
              }
              $array1 = \App\Libraries\Snmp::walk("$item->ip", "$item->comunidad_escritura", "1.3.6.1.4.1.2011.6.128.1.1.2.48.1.2");
              if ($array1) {
                $sin_autorizar = count($array1);
              }
            }
          }
        }




        $data['en_sistema'] = $this->model_fiber->get_onus_en_sistema();
        $data['en_olt'] = $en_olt;
        $data['sin_autorizar'] = $sin_autorizar;
        $data['autorizadas_hoy'] = $this->model_fiber->get_onus_autorizadas_hoy();
        $data['autorizadas_mes'] = $this->model_fiber->get_onus_autorizadas_mes();
        // strftime() está deprecado desde PHP 8.1: nombre del mes con intl
        $data['mes_actual'] = mb_strtoupper((new \IntlDateFormatter('es_MX', \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, null, null, 'MMMM'))->format(time()));
        $data['activas'] = $activas;
        $data['offline'] = $offline;
        return view('fiber/dashboard', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function get_onu_lower_signal()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('FIBER_DASHBOARD'));
      if ($tipoacceso > 0) {
        $server = $this->model_fiber->get_olt();
        $total = 0;
        if ($server) {
          foreach ($server as $item) {
            $result_snmp_con = "snmp-fail";
            snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
            $array = \App\Libraries\Snmp::walk("$item->ip", "$item->comunidad_escritura", "1.3.6.1.2.1.1.1");
            if ($array) {
              foreach ($array as $key) {
                $result_snmp_con = $key;
              }
            }
            if ($result_snmp_con == 'Huawei Integrated Access Software') {
              $array1 = \App\Libraries\Snmp::walk("$item->ip", "$item->comunidad_escritura", "1.3.6.1.4.1.2011.6.128.1.1.2.51.1.4");
              if ($array1) {
                foreach ($array1 as $value) {
                  if ($value != "2147483647") {
                    $señal = $value / 100;
                    if ((int)$señal <= -26) {
                      $total++;
                    }
                  }
                }
              }
            }
          }
        }
        echo $total;
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function get_onu_autorize_per_mes()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('FIBER_DASHBOARD'));
      if ($tipoacceso > 0) {
        $year = $this->request->getGet('anio');
        $enero_t = 0;
        $febrero_t = 0;
        $marzo_t = 0;
        $abril_t = 0;
        $mayo_t = 0;
        $junio_t = 0;
        $julio_t = 0;
        $agosto_t = 0;
        $septiembre_t = 0;
        $octubre_t = 0;
        $noviembre_t = 0;
        $diciembre_t = 0;
        $onus = $this->model_fiber->get_onus_autorize("all");
        for ($i = 1; $i <= 12; $i++) {

          $ultimo_dia = date("d", (mktime(0, 0, 0, $i + 1, 1, $year) - 1));
          $inicio = $year . "-" . $i . "-01";
          $inicio = date("Y-m-d", strtotime($inicio));
          $fin = $year . "-" . $i . "-" . $ultimo_dia;
          $fin = date("Y-m-d", strtotime($fin));
          foreach ($onus as $item) {
            $fecha_onu = date("Y-m-d", strtotime($item->fecha));
            if ($fecha_onu >= $inicio && $fecha_onu <= $fin) {
              if ($i == 1) {
                $enero_t++;
              }
              if ($i == 2) {
                $febrero_t++;
              }
              if ($i == 3) {
                $marzo_t++;
              }
              if ($i == 4) {
                $abril_t++;
              }
              if ($i == 5) {
                $mayo_t++;
              }
              if ($i == 6) {
                $junio_t++;
              }
              if ($i == 7) {
                $julio_t++;
              }
              if ($i == 8) {
                $agosto_t++;
              }
              if ($i == 9) {
                $septiembre_t++;
              }
              if ($i == 10) {
                $octubre_t++;
              }
              if ($i == 11) {
                $noviembre_t++;
              }
              if ($i == 12) {
                $diciembre_t++;
              }
            }
          }
        }
        $data = array();
        array_push($data, array("y" => "Ene", "a" => $enero_t));
        array_push($data, array("y" => "Feb", "a" => $febrero_t));
        array_push($data, array("y" => "Mar", "a" => $marzo_t));
        array_push($data, array("y" => "Abr", "a" => $abril_t));
        array_push($data, array("y" => "May", "a" => $mayo_t));
        array_push($data, array("y" => "Jun", "a" => $junio_t));
        array_push($data, array("y" => "Jul", "a" => $julio_t));
        array_push($data, array("y" => "Ago", "a" => $agosto_t));
        array_push($data, array("y" => "Sep", "a" => $septiembre_t));
        array_push($data, array("y" => "Oct", "a" => $octubre_t));
        array_push($data, array("y" => "Nov", "a" => $noviembre_t));
        array_push($data, array("y" => "Dic", "a" => $diciembre_t));
        echo json_encode($data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function olt()
  {
    $permiso = $this->seguridad->Acceso('OLT', 'LECTURA');
    if ($permiso) {
      $data['permiso'] = $permiso;
      return view('fiber/olt', $data);
    }
  }

  public function onu($estado = "all", $tab_click = "tab_autorizadas")
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('ONU'));
      if ($tipoacceso > 0) {
        $data['estado'] = $estado;
        $data['tab_click'] = $tab_click;
        return view('fiber/onu/onu', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function view()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('ONU'));
      if ($tipoacceso > 0) {
        $id = $this->request->getGet('id');
        $onu = $this->model_fiber->get_onu_id($id);
        $data['onu'] = $onu;
        return view('fiber/onu/view', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function get_olt()
  {
    $permiso = $this->seguridad->Acceso('OLT', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      // DTO: sin user/pass ni comunidades SNMP (antes viajaban al navegador)
      $filas = [];
      foreach ((new OltModel())->listado() as $olt) {
          $id = $olt->id;
          $clientes = 0;
          $nombre = esc($olt->nombre, 'js');
          $value = (object) ['id' => $olt->id, 'ip' => esc($olt->ip)];
          $value->modelo = '<img src="public/images/olt_image/' . esc($olt->imagen, 'url') . '" class="rounded mr-3" width="50"><p class="m-0 d-inline-block align-middle font-16"><span class="font-14 my-1 font-weight-normal">' . esc($olt->modelo) . '</span><br><span class="text-muted font-13">Huawei</span></p>';
          $value->nombre = "<a href=\"gestion_red/asa\">" . esc($olt->nombre) . "</a>";
          $value->clientes = "<span class=\"badge\"> 0 ONUS</span>";
          $value->temperatura = "<i class='fa fa-thermometer-empty'></i> " . esc($olt->temperatura) . "";
          $value->conexion = "<i class='fa fa-clock'></i> " . esc($olt->conexion) . "";
          $value->estado = esc($olt->estado);
          if ($olt->estado == "CONECTADO") {
            $value->estado = "<span class=\"label label-pill label-success\">" . $olt->estado . "</span>";
          }
          if ($olt->estado == "DESCONECTADO") {
            $value->estado = "<span class=\"label label-pill label-danger\">" . $olt->estado . "</span>";
          }
          $value->tool = "
          <a href=\"#fiber/edit_olt?id=$id\" data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" data-original-title=\"Editar\">
            <i class=\"far fa-edit\"></i>
          </a>";
          if ($permiso >= 3) {
            $value->tool .= "<a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"delete_olt('$id','$nombre','$clientes')\" data-original-title=\"Eliminar\">
            <i class=\"far fa-trash-alt\" aria-hidden=\"true\"></i>
          </a>";
            $value->tool .= "<a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"reiciar_olt('$id')\" data-original-title=\"Reiciar\">
            <i class=\"fas fa-retweet\" aria-hidden=\"true\"></i>
          </a>";
          }
          $filas[] = $value;
      }
      return json_encode(['data' => $filas]);
    }
  }

  public function get_gpon_port()
  {
    if ($this->seguridad->Acceso('OLT', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getGet('id');
      $data_port = array();
      $row_olt = $this->model_fiber->get_olt_id($id);
      if ($row_olt) {
        $delete = $this->model_fiber->delete_gpon_olt($id);
        $result_telnet_con = "";
        $result_snmp_con = "snmp-fail";
        snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
        $array = \App\Libraries\Snmp::walk("$row_olt->ip", "$row_olt->comunidad_escritura", "1.3.6.1.2.1.1.1");
        if ($array) {
          foreach ($array as $key) {
            $result_snmp_con = $key;
          }
        }
        if ($result_snmp_con == 'Huawei Integrated Access Software') {
          $result_snmp_con = "snmp-ok";
          $array1 =\App\Libraries\Snmp::walk("$row_olt->ip", "$row_olt->comunidad_escritura", "1.3.6.1.4.1.2011.6.128.1.1.2.22.1.2");
          //$array2 =\App\Libraries\Snmp::walk("$row_olt->ip","$row_olt->comunidad_escritura",".1.3.6.1.2.1.31.1.1.1.1.");
          //$array3 =\App\Libraries\Snmp::walk("$row_olt->ip","$row_olt->comunidad_escritura","1.3.6.1.2.1.2.2.1.7.4194307840");
          if ($array1) {
            while (current($array1)) {
              $id_port = key($array1);
              $id_port = str_replace('iso.3.6.1.4.1.2011.6.128.1.1.2.22.1.2.', '', $id_port);
              $array2 =\App\Libraries\Snmp::walk("$row_olt->ip", "$row_olt->comunidad_escritura", ".1.3.6.1.2.1.31.1.1.1.1.$id_port");
              $name_port = $array2['iso.3.6.1.2.1.31.1.1.1.1.' . $id_port];
              $name_port = str_replace('GPON ', '', $name_port);
              $name_port = explode('/', $name_port);
              $array3 =\App\Libraries\Snmp::walk("$row_olt->ip", "$row_olt->comunidad_escritura", "1.3.6.1.2.1.2.2.1.7.$id_port");
              $estado = $array3['iso.3.6.1.2.1.2.2.1.7.' . $id_port];
              if ($estado == 1) {
                $estado = '<span class="label label-success">UP</span>';
              } else {
                $estado = '<span class="label label-danger">DOWN</span>';
              }
              $array3 =\App\Libraries\Snmp::walk("$row_olt->ip", "$row_olt->comunidad_escritura", "1.3.6.1.4.1.2011.6.128.1.1.2.21.1.10.$id_port");
              $operacion = $array3['iso.3.6.1.4.1.2011.6.128.1.1.2.21.1.10.' . $id_port];
              if ($operacion == 1) {
                $operacion = '<span class="label label-success">UP</span>';
              } else {
                $operacion = '<span class="label label-danger">DOWN</span>';
              }
              $array3 =\App\Libraries\Snmp::walk("$row_olt->ip", "$row_olt->comunidad_escritura", "1.3.6.1.4.1.2011.6.128.1.1.2.21.1.16.$id_port");
              $onus_olt = $array3['iso.3.6.1.4.1.2011.6.128.1.1.2.21.1.16.' . $id_port];
              $array3 =\App\Libraries\Snmp::walk("$row_olt->ip", "$row_olt->comunidad_escritura", "1.3.6.1.4.1.2011.6.128.1.1.2.21.1.14.$id_port");
              $avaliable_bandwith = $this->formatSizeUnits($array3['iso.3.6.1.4.1.2011.6.128.1.1.2.21.1.14.' . $id_port]);





              array_push($data_port, array("id" => $id_port, "nombre" => "H805GPFD", "ranura" => $name_port[1], "puerto" => $name_port[2], "senial" => "H805GPFD", "estado" => $estado . "/" . $operacion, "onus_olt" => $onus_olt, "onus_sistema" => $onus_olt, "tipo" => "Gpon", "avaliable_bandwith" => $avaliable_bandwith, "tool" => ""));

              $data_port_base = array("idolt" => $id, "idpon" => $id_port, "tipo" => "Gpon", "chasis" => $name_port[0], "ranura" => $name_port[1], "puerto" => $name_port[2], "interface" => $name_port[0] . "/" . $name_port[1]);
              $insert = $this->model_fiber->insert_gpon_olt($data_port_base);
              next($array1);
            }
          }


          $arrayName = array('result_snmp_con' => $result_snmp_con);




          $data['data'] = $row_olt;
        } else {
          $data['data'] = array();
        }
        echo '{"data":' . json_encode($data_port) . '}';
      }
    }
  }

  public function get_onus_autorize()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('ONU'));
      if ($tipoacceso > 0) {
        $estado = $this->request->getGet('estado');

        $data = array();
        $onus = $this->model_fiber->get_onus_autorize(strtoupper($estado));
        if ($onus) {
          foreach ($onus as $value) {
            $id = $value->id;

            if ($value->estado == "ONLINE") {
              $value->estado = "<span class=\"label label-pill label-success\">" . $value->estado . "</span>";
            }
            if ($value->estado == "OFFLINE") {
              $value->estado = "<span class=\"label label-pill label-danger\">" . $value->estado . "</span>";
            }

            if ($tipoacceso == 1) {
              $value->tool = '
           <a href="#fiber/view?id=' . $value->id . '" class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Ver detalles"><span class="far fa-eye"></span>
           </a>';
            }
            if ($tipoacceso == 2) {
              $value->tool = '
           <a href="#fiber/view?id=' . $value->id . '" class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Ver detalles"><span class="far fa-eye"></span>
           </a>
            <a href="#fiber/edit_only_system?id=' . $value->id . '" class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Editar en OLT"><span class="far fa-edit"></span>
           </a>';
            }
            if ($tipoacceso == 3) {

              $value->tool = '
           <a href="#fiber/view?id=' . $value->id . '" class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Ver detalles"><span class="far fa-eye"></span>
           </a>
            <a href="#fiber/edit_only_system?id=' . $value->id . '" class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Editar en sistema"><span class="far fa-edit"></span>
           </a>
           <button class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Eliminar"><span class="far fa-trash-alt" onclick="delete_onu(\'' . $value->id . '\',\'' . $value->idolt . '\',\'' . $value->idcliente . '\',\'' . $value->sn . '\',\'' . $value->nombre . '\')"></span>
           </button>';
            }

            $nombre = "<a href='#clientes/detalleuser?id=" . $value->idcliente . "'>" . $value->nombre . "</a>";
            $value->nombre = $nombre;
          }

          $data['data'] = $onus;
        } else {
          $data['data'] = array();
        }
        return json_encode($data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function herramientas()
  {
    if ($this->seguridad->Acceso('OLT', 'LECTURA', $_SESSION['id_usuario8291'])) {
      echo "hola";
    }
  }


  public function edit_olt()
  {
    if ($this->seguridad->Acceso('OLT', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getGet('id');
      $data['id'] = $id;
      return view('fiber/editar_olt.php', $data);
    }
  }

  public function tab_edit_general()
  {
    $permiso = $this->seguridad->Acceso('OLT', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getGet('id');
      $server = $this->model_fiber->get_olt_id($id);
      $modelos_olt = $this->model_selectores->get_modelos_olt();
      if ($server) {
        $data['permiso'] = $permiso;
        $data['server'] = $server;
        $data['modelos_olt'] = $modelos_olt;
        return view('fiber/tab_edit_general', $data);
      } else {
        echo "El servidor ha sido eliminado";
      }
    }
  }

  public function tab_puertos()
  {
    if ($this->seguridad->Acceso('OLT', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getGet('id');
      $server = $this->model_fiber->get_olt_id($id);
      // $modelos_olt=$this->model_selectores->get_modelos_olt();
      if ($server) {
        $data['server'] = $server;
        $data['id'] = $id;
        //  $data['modelos_olt'] =$modelos_olt;
        return view('fiber/tab_puertos', $data);
      } else {
        echo "El servidor ha sido eliminado";
      }
    }
  }

  public function tab_gpon_ports()
  {
    if ($this->seguridad->Acceso('OLT', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getGet('id');
      $server = $this->model_fiber->get_olt_id($id);
      // $modelos_olt=$this->model_selectores->get_modelos_olt();
      if ($server) {
        $data['server'] = $server;
        $data['id'] = $id;
        //  $data['modelos_olt'] =$modelos_olt;
        return view('fiber/tab_puertos_olt/gpon', $data);
      } else {
        echo "El servidor ha sido eliminado";
      }
    }
  }

  public function tab_autorizadas($estado = "all")
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('ONU'));
      if ($tipoacceso > 0) {

        $data['estado'] = $estado;
        return view('fiber/onu/tab_autorizadas', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function tab_no_autorizadas()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('AUTORIZE_ONU'));

      if ($tipoacceso > 0) {

        $server = $this->model_fiber->get_olt();

        $onus = array();
        $sn = array();
        $modelo = array();
        $idpon_a = array();
        if ($server) {
          foreach ($server as $item) {
            $result_snmp_con = "snmp-fail";
            snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
            $array = \App\Libraries\Snmp::walk("$item->ip", "$item->comunidad_escritura", "1.3.6.1.2.1.1.1");
            if ($array) {
              foreach ($array as $key) {
                $result_snmp_con = $key;
              }
            }
            if ($result_snmp_con == 'Huawei Integrated Access Software') {



              $array1 = \App\Libraries\Snmp::walk("$item->ip", "$item->comunidad_escritura", "1.3.6.1.4.1.2011.6.128.1.1.2.48.1");

              if ($array1) {
                foreach ($array1 as $key => $value) {
                  if (strrpos($key, "iso.3.6.1.4.1.2011.6.128.1.1.2.48.1.2.") !== false) {
                    array_push($sn, strtoupper(bin2hex($value)));
                    $idpon = explode(".", $key);
                    $idpon = $idpon[15];

                    array_push($idpon_a, $idpon);
                  }
                  if (strrpos($key, "iso.3.6.1.4.1.2011.6.128.1.1.2.48.1.7.") !== false) {
                    array_push($modelo, $value);
                  }
                 


                }
                for ($i = 0; $i < count($sn); $i++) {
                  $port = $this->model_fiber->get_port_id($idpon_a[$i]);
                  
                  if ($port) {
                    $tool = '<button onclick="autorize_onu(\'' . $sn[$i] . '\',\'' . $idpon_a[$i] . '\',\'' . $item->id . '\')" class="btn btn-success btn-sm" data-toggle="tooltip" data-placement="top" title="" "data-original-title="Autorizar onu" role="button" aria-disabled="true"> <i class="fas fa-power-off"></i></button>';
                    array_push($onus, array("idolt" => $item->id, "olt" => $item->nombre, "interface" => $port->interface . "/" . $port->puerto, "idpon" => $port->idpon, "puerto" => $port->puerto, "sn" => $sn[$i], "modelo" => $modelo[$i], "tool" => $tool));

                  }
                }
              }


            }
          }
        }

        $data['onus'] = $onus;
        return view('fiber/onu/tab_no_autorizadas', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
  public function save_olt_general()
  {
    if ($this->seguridad->Acceso('OLT', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $action = $this->request->getPost('action');
      $id = $this->request->getPost('id');
      $nombre = $this->request->getPost('nombre');
      $coordenadas = $this->request->getPost('coordenadas');
      $ip = $this->request->getPost('ip');
      $modelo = $this->request->getPost('modelo');
      $version = $this->request->getPost('version');
      $pon = $this->request->getPost('pon');
      $comunidad_lectura = $this->request->getPost('comunidad_lectura');
      $comunidad_escritura = $this->request->getPost('comunidad_escritura');
      $port_telnet = $this->request->getPost('port_telnet');
      $port_snmp = $this->request->getPost('port_snmp');
      $user = $this->request->getPost('user');
      $pass = $this->request->getPost('pass');
      $data_server = [
        'nombre' => $nombre,
        'coordenadas' => $coordenadas,
        'ip' => $ip,
        'modelo' => $modelo,
        'version' => $version,
        'pon' => $pon,
        'comunidad_lectura' => $comunidad_lectura,
        'comunidad_escritura' => $comunidad_escritura,
        'port_telnet' => $port_telnet,
        'port_snmp' => $port_snmp,
        'user' => $user,
        'pass' => $pass
        /* 'ipvisitadas'=>$ipvisitadas */
      ];
      $result_update_server = $this->model_fiber->update_olt($id, $data_server);
      if ($result_update_server) {
        if ($action == "guardar_c") {

          $row_olt = $this->model_fiber->get_olt_id($id);
          $result_telnet_con = "";
          $result_snmp_con = "snmp-fail";
          $response = shell_exec("/var/www/html/app/scripts/test.sh $action $row_olt->port_telnet $row_olt->ip $row_olt->user $row_olt->pass $row_olt->prompt");
          $pos_ok = strpos($response, $row_olt->prompt . "#");
          $pos_error = strpos($response, "Unable to connect to remote host");
          $pos_limit = strpos($response, "Reenter times have reached the upper limit");
          if ($pos_ok) {
            $result_telnet_con = "telnet-ok";
          } else if ($pos_error) {
            $result_telnet_con = "telnet-fail";
          } else if ($pos_limit) {
            $result_telnet_con = "telnet-limit";
          } else {
            $result_telnet_con = "telnet-credenciales";
          }

          snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
          $array = \App\Libraries\Snmp::walk("$row_olt->ip", "$row_olt->comunidad_escritura", "1.3.6.1.2.1.1.1");

          if ($array) {
            foreach ($array as $key) {
              $result_snmp_con = $key;
            }
          }
          if ($result_snmp_con == 'Huawei Integrated Access Software') {
            $result_snmp_con = "snmp-ok";
          }

          $arrayName = array('result_snmp_con' => $result_snmp_con, 'result_telnet_con' => $result_telnet_con);
          if ($result_snmp_con == "snmp-ok" && $result_telnet_con == "telnet-ok") {
            $data_server = ['estado' => "CONECTADO"];
            $result_update_server = $this->model_fiber->update_olt($id, $data_server);
          } else {
            $data_server = ['estado' => "DESCONECTADO"];
            $result_update_server = $this->model_fiber->update_olt($id, $data_server);
          }
          echo json_encode($arrayName);
        } else {
          echo 1;
        }
      } else {
        echo 2;
      }
    }
  }

  public function delete_server()
  {
    if ($this->seguridad->Acceso('SERVER', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getPost('id');
      $result_delete = $this->model_gestion_red->delete_server($id);
      echo $result_delete ? 1 : 2;
    }
  }

  public function autorize_onu($sn, $idpon, $idolt)
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('AUTORIZE_ONU'));
      if ($tipoacceso > 0) {
        //$id=$this->request->getPost('id');
        $port = $this->model_fiber->get_port_id($idpon);
        $data["sn"] = $sn;
        $data["chasis"] = $port->chasis;
        $data["tarjeta"] = $port->ranura;
        $data["puerto"] = $port->puerto;
        $data["idolt"] = $idolt;
        $data["idpon"] = $idpon;
        $data["onts"] = $this->model_selectores->get_modelos_ont($idolt);
        $data["vlans"] = $this->model_selectores->get_vlans_olt($idolt);
        $data["trafic"] = $this->model_selectores->get_trafic_olt($idolt);
        return view('fiber/onu/autorizar', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function edit_only_system()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('ONU'));
      if ($tipoacceso > 0) {
        $id = $this->request->getGet('id');
        $row_onu = $this->model_fiber->get_onu_id($id);
        $data["id"] = $row_onu->id;
        $data["idcliente"] = $row_onu->idcliente;
        $data["cliente"] = $row_onu->nombre;
        $data["idpon"] = $row_onu->idpon;
        $data["sn"] = $row_onu->sn;
        $data["chasis"] = $row_onu->chasis;
        $data["tarjeta"] = $row_onu->tarjeta;
        $data["puerto"] = $row_onu->puerto;
        $data["idolt"] = $row_onu->idolt;
        $data["idpon"] = $row_onu->idpon;
        $data["modo"] = $row_onu->modo;
        $data["vlan"] = $row_onu->vlan;
        $data["idtrafic"] = $row_onu->idtrafic;
        $data["srv_profile"] = $row_onu->srv_profile;
        $data["zona"] = $row_onu->zona;
        $data["nap"] = $row_onu->nap;
        $data["imagen"] = $row_onu->imagen;
        $data["comentarios"] = $row_onu->comentarios;
        $data["onts"] = $this->model_selectores->get_modelos_ont($row_onu->idolt);
        $data["vlans"] = $this->model_selectores->get_vlans_olt($row_onu->idolt);
        $data["trafic"] = $this->model_selectores->get_trafic_olt($row_onu->idolt);
        return view('fiber/onu/edit_only_system', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function save_autorize_onu()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('AUTORIZE_ONU'));
      if ($tipoacceso > 0) {
        $idcliente = $this->request->getPost('id_cliente');
        $cliente = $this->request->getPost('cliente');
        $idpon = $this->request->getPost('idpon');
        $idolt = $this->request->getPost('idolt');
        $sn = $this->request->getPost('sn');
        $chasis = $this->request->getPost('chasis');
        $tarjeta = $this->request->getPost('tarjeta');
        $puerto = $this->request->getPost('puerto');
        $srv_profile = $this->request->getPost('modelo');
        $modo = $this->request->getPost('modo');
        $vlan = $this->request->getPost('vlan');
        $zona = $this->request->getPost('zona');
        $nap = $this->request->getPost('nap');
        $trafic_table = $this->request->getPost('trafic');
        $comentarios = $this->request->getPost('comentarios');
        $action = "test";
        $idonu = "";
        $srv_port = 0;
        $modelo = $this->model_fiber->get_modelo_by_svr_profile($srv_profile);
        $enable_script = $modelo->enable_script;
        $script = $modelo->script;
        $modelo = $modelo->modelo;


        $row_olt = $this->model_fiber->get_olt_id($idolt);
        $row_port = $this->model_fiber->get_port_id($idpon);
        $row_line = $this->model_fiber->get_line_profile_id($idolt);
        $result_telnet_con = "";
        $result_snmp_con = "snmp-fail";
        $response = shell_exec("/var/www/html/app/scripts/test.sh $action $row_olt->port_telnet $row_olt->ip $row_olt->user $row_olt->pass $row_olt->prompt");
        $pos_ok = strpos($response, $row_olt->prompt . "#");
        $pos_error = strpos($response, "Unable to connect to remote host");
        $pos_limit = strpos($response, "Reenter times have reached the upper limit");
        snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
        $array = \App\Libraries\Snmp::walk("$row_olt->ip", "$row_olt->comunidad_escritura", "1.3.6.1.2.1.1.1");
        if ($array) {
          foreach ($array as $key) {
            $result_snmp_con = $key;
          }
        }
        if ($result_snmp_con == 'Huawei Integrated Access Software') {
          $result_snmp_con = "snmp-ok";
        }
        if ($pos_ok && $result_snmp_con == "snmp-ok") {

          $result_telnet_con = "telnet-ok";
          $cliente1 = "IdCliente_" . $idcliente;
          //$cliente_space_not = $this->quitar_tildes($cliente_space_not);
          $response = shell_exec("/var/www/html/app/scripts/MA5608T_confirm.sh $row_olt->port_telnet $row_olt->ip $row_olt->user $row_olt->pass $row_port->interface $puerto $sn $row_line->id_line_profile $srv_profile $cliente1");
          sleep(1);
          $pos_success = strpos($response, "success");
          $pos_duplicate = strpos($response, "SN already exists");
          if ($pos_success) {
            //echo "success";
            $array = \App\Libraries\Snmp::walk("$row_olt->ip", "$row_olt->comunidad_escritura", "1.3.6.1.4.1.2011.6.128.1.1.2.43.1.3.$idpon");
            if ($array) {
              foreach ($array as $key => $value) {
                if (strtoupper(bin2hex($value)) == $sn) {
                  $idonu = explode(".", $key);
                  $idonu = $idonu[16];
                }
              }
            }
            sleep(1);
            $array = \App\Libraries\Snmp::walk("$row_olt->ip", "$row_olt->comunidad_escritura", "1.3.6.1.4.1.2011.5.14.3.1.1.1");
            if ($array) {
              foreach ($array as $key => $value) {
                $srv_port = ($value - 1);
              }
              $srv_port++;
            }
            sleep(1);
            $interface = $row_port->interface . "/" . $puerto;
            $response = shell_exec("/var/www/html/app/scripts/MA5608T_srvport.sh $row_olt->port_telnet $row_olt->ip $row_olt->user $row_olt->pass $srv_port $interface $idonu $trafic_table $vlan");
            $pos_success1 = strpos($response, "(config)#service-port");

            if ($enable_script == "SI") {
              sleep(2);
              $response2 = shell_exec("/var/www/html/app/scripts/MA5608T_extrascript.sh $row_olt->port_telnet $row_olt->ip $row_olt->user $row_olt->pass $row_port->interface $puerto $idonu $vlan");
            }

            if ($pos_success1) {
              $array_onu = array("idolt" => $idolt, "idcliente" => $idcliente, "sn" => $sn, "idpon" => $idpon, "chasis" => $chasis, "tarjeta" => $tarjeta, "puerto" => $puerto, "modelo" => $modelo, "modo" => $modo, "line_profile" => 3, "srv_profile" => $srv_profile, "srv_port" => $srv_port, "idonu" => $idonu, "vlan" => $vlan, "zona" => $zona, "nap" => $nap, "trafic_table" => $trafic_table, "estado" => "ONLINE", "comentarios" => $comentarios, "fecha" => date("Y-m-d H:i:s"));
              $insert = $this->model_fiber->insert_onu_olt($array_onu);
              $txt_log = "ONU SN:" . $sn . " agregado a la OLT - Cliente <b>#" . $idcliente . " " . $cliente . "</b>";
              $this->model_log->insert_log($idcliente, "cliente", $txt_log, $_SESSION['id_usuario8291']);
              if ($insert) {
                echo "success";
              }
            }
          } else if ($pos_duplicate) {
            echo "Numero de serie ya exite en la OLT";
          } else {
            echo "Error al agregar ONU en OLT";
          }
        } else if ($pos_error) {
          echo "telnet-fail";
        } else if ($pos_limit) {
          echo "telnet-limit";
        } else {
          echo "telnet-credenciales";
        }
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function update_onu_sistema()
  {
    if ($this->seguridad->Acceso('ONU', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getPost('id');
      $idcliente = $this->request->getPost('id_cliente');
      $modo = $this->request->getPost('modo');
      $zona = $this->request->getPost('zona');
      $nap = $this->request->getPost('nap');
      $comentarios = $this->request->getPost('comentarios');
      $array_onu = array("idcliente" => $idcliente, "modo" => $modo, "zona" => $zona, "nap" => $nap, "comentarios" => $comentarios);
      $update = $this->model_fiber->update_onu_sistema($id, $array_onu);
      if ($update) {
        $txt_log = "ONU editada en el sistema - Cliente <b>#" . $idcliente . " {{cliente}} </b>";
        $this->model_log->insert_log($idcliente, "cliente", $txt_log, $_SESSION['id_usuario8291']);
        echo "success";
      }
    }
  }

  public function get_onu_estado()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('ONU'));
      if ($tipoacceso > 0) {
        $id = $this->request->getGet('id');
        $onu = $this->model_fiber->get_onu_id($id);
        $idpon = $onu->idpon;
        $idonu = $onu->idonu;
        $idolt = $onu->idolt;
        $row_olt = $this->model_fiber->get_olt_id($idolt);
        snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
        $result_snmp_con = "";
        $online = "OFFLINE";
        $tx_onu = "--";
        $rx_onu = "--";
        $rx_olt = "--";
        $array = \App\Libraries\Snmp::walk("$row_olt->ip", "$row_olt->comunidad_escritura", "1.3.6.1.2.1.1.1");
        if ($array) {
          foreach ($array as $key) {
            $result_snmp_con = $key;
          }
        }
        if ($result_snmp_con == 'Huawei Integrated Access Software') {
          $result_snmp_con = "snmp-ok";
        }
        if ($result_snmp_con == "snmp-ok") {

          $array = \App\Libraries\Snmp::walk("$row_olt->ip", "$row_olt->comunidad_escritura", ".1.3.6.1.4.1.2011.6.128.1.1.2.46.1.15.$idpon.$idonu");
          if ($array) {
            foreach ($array as $key => $value) {
              if ($value == 1) {
                $online = "ONLINE";
              }
              $result = $this->model_fiber->update_estado_onu($id, $online);
            }
          }

          $array = \App\Libraries\Snmp::walk("$row_olt->ip", "$row_olt->comunidad_escritura", ".1.3.6.1.4.1.2011.6.128.1.1.2.51.1.3.$idpon.$idonu");
          if ($array) {
            foreach ($array as $key => $value) {
              if ($value != "2147483647") {
                $tx_onu = round($value / 100, 2);
              } else {
                $tx_onu = "--";
              }
            }
          }

          $array = \App\Libraries\Snmp::walk("$row_olt->ip", "$row_olt->comunidad_escritura", ".1.3.6.1.4.1.2011.6.128.1.1.2.51.1.4.$idpon.$idonu");
          if ($array) {
            foreach ($array as $key => $value) {
              if ($value != "2147483647") {
                $rx_onu = round($value / 100, 2);
              } else {
                $rx_onu = "--";
              }
            }
          }

          $array = \App\Libraries\Snmp::walk("$row_olt->ip", "$row_olt->comunidad_escritura", ".1.3.6.1.4.1.2011.6.128.1.1.2.51.1.6.$idpon.$idonu");
          if ($array) {
            foreach ($array as $key => $value) {
              if ($value != "2147483647") {
                $rx_olt = round(($value / 100) - 100, 2);
              } else {
                $rx_olt = "--";
              }
            }
          }
        }
        $valores = array("estado" => $online, "tx_onu" => $tx_onu, "rx_onu" => $rx_onu, "rx_olt" => $rx_olt);
        echo json_encode($valores);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function delete_onu()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('ONU'));
      if ($tipoacceso > 2) {
        $id = $this->request->getPost('id');
        $idolt = $this->request->getPost('idolt');
        $idcliente = $this->request->getPost('idcliente');
        $sn = $this->request->getPost('sn');
        $cliente = $this->request->getPost('cliente');
        $check = $this->request->getPost('check');
        if ($check == "true") {
          $txt_log = "ONU SN:" . $sn . " eliminado únicamente del sistema - Cliente <b>#" . $idcliente . " " . $cliente . "</b>";
          $this->model_log->insert_log($idcliente, "cliente", $txt_log, $_SESSION['id_usuario8291']);
          $delete = $this->model_fiber->delete_onu($id);
          if ($delete) {
            $array = array("estado" => "success", "mensaje" => "ONU eliminado únicamente de la base de datos");
          } else {
            $array = array("estado" => "error", "mensaje" => "Error al eliminar ONU de la base de datos");
          }
        } else {
          $row_olt = $this->model_fiber->get_olt_id($idolt);
          $row_onu = $this->model_fiber->get_onu_id($id);
          $interface = $row_onu->chasis . "/" . $row_onu->tarjeta;
          $result_telnet_con = "";
          $action = "test";

          $response = shell_exec("/var/www/html/app/scripts/test.sh $action $row_olt->port_telnet $row_olt->ip $row_olt->user $row_olt->pass $row_olt->prompt");

          $pos_ok = strpos($response, $row_olt->prompt . "#");
          $pos_error = strpos($response, "Unable to connect to remote host");
          $pos_limit = strpos($response, "Reenter times have reached the upper limit");
          if ($pos_ok) {
            $response = shell_exec("/var/www/html/app/scripts/MA5608T_delete.sh $row_olt->port_telnet $row_olt->ip $row_olt->user $row_olt->pass $interface $row_onu->puerto $row_onu->srv_port $row_onu->idonu");
            $pos_success = strpos($response, "success");

            if ($pos_success) {

              $txt_log = "ONU SN:" . $sn . " eliminado del sistema y la OLT - Cliente <b>#" . $idcliente . " " . $cliente . "</b>";
              $this->model_log->insert_log($idcliente, "cliente", $txt_log, $_SESSION['id_usuario8291']);
              $delete = $this->model_fiber->delete_onu($id);
              if ($delete) {
                $array = array("estado" => "success", "mensaje" => "ONU eliminado de la OLT y la base de datos");
              } else {
                $array = array("estado" => "error", "mensaje" => "Error al eliminar ONU de la base de datos");
              }
            } else {
              $array = array("estado" => "error", "mensaje" => "Error al eliminar ONU de la OLT");
            }
          } else if ($pos_error) {
            $array = array("estado" => "error", "mensaje" => "Error de conexión con la OLT");
          } else if ($pos_limit) {
            $array = array("estado" => "error", "mensaje" => "Limite se sesión de usuario TELNET");
          } else {
            $array = array("estado" => "error", "mensaje" => "Usuario o contraseña incorrecto de la OLT");
          }
        }
        echo json_encode($array);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function reboot_onu()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('ONU'));
      if ($tipoacceso > 1) {
        $id = $this->request->getPost('id');

        $row_onu = $this->model_fiber->get_onu_id($id);
        $row_olt = $this->model_fiber->get_olt_id($row_onu->idolt);
        $interface = $row_onu->chasis . "/" . $row_onu->tarjeta;
        $result_telnet_con = "";
        $action = "test";

        $response = shell_exec("/var/www/html/app/scripts/test.sh $action $row_olt->port_telnet $row_olt->ip $row_olt->user $row_olt->pass $row_olt->prompt");



        $pos_ok = strpos($response, $row_olt->prompt . "#");
        $pos_error = strpos($response, "Unable to connect to remote host");
        $pos_limit = strpos($response, "Reenter times have reached the upper limit");
        if ($pos_ok) {
          $response = shell_exec("/var/www/html/app/scripts/MA5608T_reboot.sh $row_olt->port_telnet $row_olt->ip $row_olt->user $row_olt->pass $interface $row_onu->puerto $row_onu->idonu");

          $pos_success = strpos($response, $row_olt->prompt . "(config-if-gpon-" . $interface . ")#");
          if ($pos_success) {
            $txt_log = "ONU SN:" . $row_onu->sn . " reiniciado del sistema - Cliente <b>#" . $row_onu->idcliente . " " . $row_onu->nombre . "</b>";
            $this->model_log->insert_log($row_onu->idcliente, "cliente", $txt_log, $_SESSION['id_usuario8291']);
            $array = array("estado" => "success", "mensaje" => "ONU REBOOT SUCCESS");
          } else {
            $array = array("estado" => "error", "mensaje" => "Error al reiniciar ONU de la OLT");
          }
        } else if ($pos_error) {
          $array = array("estado" => "error", "mensaje" => "Error de conexión con la OLT");
        } else if ($pos_limit) {
          $array = array("estado" => "error", "mensaje" => "Limite se sesión de usuario TELNET");
        } else {
          $array = array("estado" => "error", "mensaje" => "Usuario o contraseña incorrecto de la OLT");
        }

        echo json_encode($array);
      } else {
        $array = array("estado" => "error", "mensaje" => "Error al reiniciar ONU de la OLT - Permiso Denegado");
        echo json_encode($array);
      }
    } else $this->seguridad->login_back();
  }

  public function info_onu()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('ONU'));
      if ($tipoacceso > 0) {
        $id = $this->request->getPost('id');

        $row_onu = $this->model_fiber->get_onu_id($id);
        $row_olt = $this->model_fiber->get_olt_id($row_onu->idolt);
        $interface = $row_onu->chasis . "/" . $row_onu->tarjeta;
        $result_telnet_con = "";
        $action = "test";
        $cadena_estado = "";

        $response = shell_exec("/var/www/html/app/scripts/test.sh $action $row_olt->port_telnet $row_olt->ip $row_olt->user $row_olt->pass $row_olt->prompt");




        $pos_ok = strpos($response, $row_olt->prompt . "#");
        $pos_error = strpos($response, "Unable to connect to remote host");
        $pos_limit = strpos($response, "Reenter times have reached the upper limit");
        if ($pos_ok) {
          $response = shell_exec("/var/www/html/app/scripts/MA5608T_info.sh $row_olt->port_telnet $row_olt->ip $row_olt->user $row_olt->pass $interface $row_onu->puerto $row_onu->idonu");


          $pos_success = strpos($response, "ONU NNI port ID");

          if ($pos_success) {
            $array_c = explode("\n", $response);

            $cadena_estado .= "<div class='row'>";
            foreach ($array_c as $item) {

              if (strpos($item, "Module type")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }
              if (strpos($item, "Module sub-type")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }
              if (strpos($item, "Used type")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }

              if (strpos($item, "Rx optical power(dBm)")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }
              if (strpos($item, "Tx optical power(dBm)")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }
              if (strpos($item, "Temperature(C)")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }
              if (strpos($item, "OLT Rx ONT optical power(dBm)")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }
              if (strpos($item, "CATV Rx optical power(dBm)")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }

              if (strpos($item, "Control flag")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }
              if (strpos($item, "Run state")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }
              if (strpos($item, "Run state")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }
              if (strpos($item, "Match state")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }
              if (strpos($item, "ONT distance(m)")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }
              if (strpos($item, "CPU occupation")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }
              if (strpos($item, "Management mode")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }
              if (strpos($item, "Description")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }

              if (strpos($item, "Last down cause")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }
              if (strpos($item, "Last up time")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }
              if (strpos($item, "Last down time")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }
              if (strpos($item, "ONT online duration")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }
              if (strpos($item, "Interoperability")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }
              if (strpos($item, "Line profile ID")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }
              if (strpos($item, "Line profile name")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }
              if (strpos($item, "TR069 management")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }
              if (strpos($item, "TR069 IP index")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }
              if (strpos($item, "Service profile ID")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }
              if (strpos($item, "Service profile name")) {
                $child = explode(":", $item);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
              }
            } ////fin foreach

            for ($i = 0; $i < count($array_c); $i++) {
              if (strpos($array_c[$i], "IPv4 address")) {
                $child = explode(":", $array_c[$i - 6]);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . " Connection</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
                $child = explode(":", $array_c[$i - 5]);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
                $child = explode(":", $array_c[$i - 4]);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
                $child = explode(":", $array_c[$i - 3]);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
                $child = explode(":", $array_c[$i - 2]);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
                $child = explode(":", $array_c[$i - 1]);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
                $child = explode(":", $array_c[$i]);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
                $child = explode(":", $array_c[$i + 1]);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
                $child = explode(":", $array_c[$i + 2]);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
                $child = explode(":", $array_c[$i + 3]);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
                $child = explode(":", $array_c[$i + 7]);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
                $child = explode(":", $array_c[$i + 9]);
                $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
                break;
              } else {

                if (strpos($array_c[$i], "ONT IP host index")) {

                  $child = explode(":", $array_c[$i + 2]);
                  $cadena = str_replace(" ", "", $child[1]);
                  $cadena = trim($cadena);
                  if ($cadena != "-") {

                    $child = explode(":", $array_c[$i]);
                    $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
                    $child = explode(":", $array_c[$i + 1]);
                    $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
                    $child = explode(":", $array_c[$i + 2]);
                    $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
                    $child = explode(":", $array_c[$i + 3]);
                    $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
                    $child = explode(":", $array_c[$i + 4]);
                    $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
                    $child = explode(":", $array_c[$i + 5]);
                    $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
                    $child = explode(":", $array_c[$i + 6]);
                    $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
                    $child = explode(":", $array_c[$i + 7]);
                    $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
                    $child = explode(":", $array_c[$i + 8]);
                    $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
                    $child = explode(":", $array_c[$i + 9]);
                    $cadena_estado .= "<div class='col-xs-4'>" . $child[0] . "</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>" . $child[1] . "</div>";
                    //$child=explode(":", $array_c[$i+10]);
                    // $cadena_estado.="<div class='col-xs-4'>".$child[0]."</div><div class='col-xs-3 text-right'>:</div><div class='col-xs-4 text-left'>".$child[1]."</div>";   
                    break;
                  }
                }
              }
            }



            $array_ = array();
            $contador = count($array_c) - 3;
            $contador2 = count($array_c) - 10;
            $contador3 = 0;
            for ($i = $contador; $i >= $contador2; $i--) {
              $array_[$contador3] = $array_c[$i] . "<br>";
              $contador3++;
            }
            for ($i = count($array_) - 1; $i > 0; $i--) {
              //  $cadena_estado.=$array_[$i]."<br>";
            }

            $cadena_estado .= "</div>";
            // print_r($array_c);
            $array = array("estado" => "success", "response" => $cadena_estado);
          } else {
            $array = array("estado" => "error", "mensaje" => "Error al consultar info ONU de la OLT");
          }
        } else if ($pos_error) {
          $array = array("estado" => "error", "mensaje" => "Error de conexión con la OLT");
        } else if ($pos_limit) {
          $array = array("estado" => "error", "mensaje" => "Limite se sesión de usuario TELNET");
        } else {
          $array = array("estado" => "error", "mensaje" => "Usuario o contraseña incorrecto de la OLT");
        }

        echo json_encode($array);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
  function modal_new_olt()
  {
    $permiso = $this->seguridad->Acceso_full('OLT', 'ESCRITURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      return view('fiber/utils/modal_new_olt');
    }
  }
  function validar_olt()
  {
    $permiso = $this->seguridad->Acceso_full('OLT', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $ip = $this->request->getPost('ip');
      $usuario = $this->request->getPost('usuario');
      $contrasenia = $this->request->getPost('contrasenia');
      $data = array();
      $data["producto"] = "";
      $data["estado"] = 2;
      $response = shell_exec("/var/www/html/herramientas/scripts/olt_get_info.sh $ip $usuario $contrasenia");
      if ($response) {
        $response_array = $this->txt_explode($response);
        if ($i = $this->find_array("PRODUCT", $response_array)) {
          $producto = explode(" ", $response_array[$i]);
          $data["producto"] = $producto[2];
          $data["estado"] = 1;
        }
      }
      return json_encode($data);
    }
  }
  function save_new_olt()
  {
    $permiso = $this->seguridad->Acceso_full('OLT', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $nombre = $this->request->getPost('nombre');
      $ip = $this->request->getPost('ip');
      $user = $this->request->getPost('user');
      $pass = $this->request->getPost('password');
      $response = shell_exec("/var/www/html/herramientas/scripts/olt_get_info.sh $ip $user $pass");
      if ($response) {
        $data = [
          'nombre' => $nombre,
          'ip' => $ip,
          'user' => $user,
          'pass' => $pass,
          'port_telnet' => 23,
          'port_snmp' => 161,
        ];
        $save = $this->model_fiber->save_new_olt($data);
        if ($save) {
          $txt_log = "Nueva OLT agregada ID: $save";
          $this->model_log->insert_log(000000, "cliente", $txt_log, $_SESSION['id_usuario8291']);
        }
        echo $save ? 1 : 2;
      } else {
        echo 2;
      }
    }
  }
  function olt_targetas()
  {
    $permiso = $this->seguridad->Acceso_full('OLT', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getGet('id');
      $data["id"] = $id;
      return view("fiber/tab_puertos_olt/targetas", $data);
    }
  }
  function get_placas()
  {
    $permiso = $this->seguridad->Acceso_full('OLT', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getGet('id');
      $data = array();
      $SlotTable = $this->library_oid->get_performance_oids("hwSlotTable");
      $SlotIndex = $this->library_oid->get_performance_oids("hwSlotIndex");
      snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
      $placas = $this->snmp2_real_walk_olt($id, $SlotTable);
      if ($placas) {
        $placas = $this->ordenar($SlotIndex, $placas);
        if (count($placas) > 0) {
          $data_tmp = array();
          $style = "";
          foreach ($placas["data"] as $key => $value) {
            $chasis = $value["chasis"];
            $ranura = $value["ranura"];

            $style = "";
            if ($value[11] > 50) {
              $style = "text-red";
            }
            if ($value[6] == 2) {
              $style_status = "success";
            } else if ($value[6] == 8) {
              $style_status = "success";
            } else {
              $style_status = "warning";
            }
            $modo = $this->library_oid->get_hwSlotWorkMode($value[4]);
            $estatus = strtoupper($this->library_oid->get_hwSlotOperStatus($value[6]));
            $data_tmp["ranura"] = $chasis . "/" . $value[0];
            $data_tmp["nombre"] = $value[2];
            $temp = "<div class=\"$style\"><i class=\"fa fa-thermometer-empty\"></i> " . $value[11] . " °C</div>";
            if ($estatus == 'FAULT' or $estatus == 'CONFIG') {
              $temp = "";
            }
            $data_tmp["estado"] = "<span class=\"label label-pill label-$style_status\">$estatus</span>";
            $data_tmp["modo"] = $modo;
            $data_tmp["temp"] = $temp;
            $data_tmp["puertos"] = "<div class=\"puertos puerto_" . $chasis . "_" . $ranura . "\"><div style=\"width: 20px;height: 20px;\" class=\"spinner-grow\"></div></div>";
            $data_tmp["tool"] = "<div><a class=\"btn btn-icon btn-sm btn-default\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"Reiniciar ranura\" onclick=\"slot_admin('$id','$chasis','$value[0]','3')\"><i class=\"fas fa-retweet\"></i></a></div>";
            $data['data'][] = $data_tmp;
          }
        }
      }
      return json_encode($data);
    }
  }
  function olt_puertos()//nuevo
  {
    $permiso = $this->seguridad->Acceso_full('OLT', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getGet('id');
      $data = array();
      $ifname = $this->library_oid->get_performance_oids("ifname");
      $interfaces = $this->library_oid->get_performance_oids("interfaces");
      $type = $this->library_oid->get_performance_oids("ifType");
      $ifAdminStatus = $this->library_oid->get_performance_oids("ifAdminStatus");
      $ifOperStatus = $this->library_oid->get_performance_oids("ifOperStatus");
      $hwVlanPort = $this->library_oid->get_performance_oids("hwVlanPort");
      
      snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
      $puertos_olt = array();
      $puertos = $this->snmp2_real_walk_olt($id, $ifname);
      $puertos_all = $this->snmp2_real_walk_olt($id, $interfaces);
      $vlans = $this->snmp2_real_walk_olt($id, $hwVlanPort);
      $all_vlans = array();
      if ($puertos) {
        foreach ($vlans as $key => $value) {
          $id_vlan = explode(".", substr($key, strlen($hwVlanPort)));
          $id_vlan = $id_vlan[1];
          if ($id_vlan != 1) {
            $array_vlans = str_split($value, 16);
            for ($ranura = 0; $ranura < count($array_vlans); $ranura++) {
              $tmp = str_split($array_vlans[$ranura]);
              if ($tmp[1] != "0") {
                $puertos_bin = base_convert($tmp[1], 16, 2);
                $puertos_bin = strrev($puertos_bin);
                $puertos_array_bin = str_split($puertos_bin);
                for ($bin_puerto = 0; $bin_puerto < count($puertos_array_bin); $bin_puerto++) {
                  if ($puertos_array_bin[$bin_puerto] != "0") {
                    $all_vlans[] = [
                      "ranura" => $ranura,
                      "puerto" => $bin_puerto,
                      "vlan" => $id_vlan
                    ];
                  }
                }
              }
            }
          }
        }
        foreach ($puertos as $key => $value) {
          $existe_slash = strpos($value, "/");
          if ($existe_slash) {
            $valores = preg_replace('/[A-Za-z\s]/', "", $value);
            $explode_puertos = explode("/", $valores);
            $partes = explode(".", $key);
            /* var_dump($puertos_all); */
            $if_index = $partes[11];
            if (isset($puertos_all[$type . $if_index])) {
              $tipo = $puertos_all[$type . $if_index];
              $tipo = $this->library_oid->IANAifType($tipo);
            } else {
              $tipo = "no se pudo optener el tipo.";
            }
            if (isset($puertos_all[$ifAdminStatus . $if_index])) {
              $admin_estado = $puertos_all[$ifAdminStatus . $if_index];
              $admin_estado = $this->library_oid->ifAdminStatus($admin_estado);
            } else {
              $admin_estado = "no se pudo optener el estado del admin.";
            }
            if (isset($puertos_all[$ifOperStatus . $if_index])) {
              $estado_operacion = $puertos_all[$ifOperStatus . $if_index];
              $estado_operacion = $this->library_oid->ifOperStatus($estado_operacion);
            } else {
              $estado_operacion = "no se pudo optener el estado de operacion.";
            }
            $puertos_olt[] = [
              "chasis" => $explode_puertos[0],
              "slot" => $explode_puertos[1],
              "puerto" => $explode_puertos[2],
              "if_index" => $if_index,
              "admin_estado" => $admin_estado,
              "estado_operacion" => $estado_operacion,
              "tipo" => ucfirst($tipo),
            ];
          }
        }
        $data["data"] = $puertos_olt;
        $data["vlans"] = $all_vlans;
      }
      return json_encode($data);
    }
  }

  function reiciar_olt()
  {
    $permiso = $this->seguridad->Acceso_full('OLT', 'ESCRITURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getPost('id');
      $hwFrameAdminStatus = $this->library_oid->get_performance_oids_functions("hwFrameAdminStatus");
      try {
        $response = $this->snmp2_set_olt($id, $hwFrameAdminStatus, "i", "3");
      } catch (\Throwable $th) {
        echo 100;
        return;
      }
      if ($response) {
        $op = $this->library_oid->get_hwFrameAdminStatus("3");
        $txt_log = "Configuracion OLT [id_olt:$id] se camnio a estado ($op)";
        $this->model_log->insert_log(000000, "olt", $txt_log, $_SESSION['id_usuario8291']);
      }
      echo $response ? 1 : 2;
    }
  }
  function slot_admin()
  {
    $permiso = $this->seguridad->Acceso_full('OLT', 'ESCRITURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $olt = $this->request->getPost('olt');
      $chasis = $this->request->getPost('chasis');
      $ranura = $this->request->getPost('ranura');
      $opcion = $this->request->getPost('opcion');
      $hwSlotAdminStatus = $this->library_oid->get_performance_oids_functions("hwSlotAdminStatus");
      try {
        $response = $this->snmp2_set_olt($olt, $hwSlotAdminStatus . $chasis . "." . $ranura, "i", $opcion);
      } catch (\Throwable $th) {
        echo 100;
        return;
      }
      if ($response) {
        $op = $this->library_oid->get_hwSlotAdminStatus("$opcion");
        $txt_log = "Configuracion OLT [id_olt:$olt chasis:$chasis ranura: $ranura] se cambio a estado ($opcion.$op)";
        $this->model_log->insert_log(000000, "olt", $txt_log, $_SESSION['id_usuario8291']);
      }
      echo $response ? 1 : 2;
    }
  }
  function editar_puerto()
  {
    $permiso = $this->seguridad->Acceso_full('OLT', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $all_vlans = array();
      $tipo = $this->request->getGet('tipo');
      if ($tipo) {
        $tipo = strtolower($tipo);
      }
      $id_olt = $this->request->getGet('olt');
      $if_index = $this->request->getGet('if_index');
      $chasis = $this->request->getGet('chasis');
      $ranura = $this->request->getGet('ranura');
      $puerto_s = $this->request->getGet('puerto');

      $avaible_vlan = false;
      if ($tipo == "gpon" or $tipo == "uplink") {
        snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
        $hwVlanPort = $this->library_oid->get_performance_oids("hwVlanPort");
        $vlans = $this->snmp2_real_walk_olt($id_olt, $hwVlanPort);
        foreach ($vlans as $key => $value) {
          $id_vlan = explode(".", substr($key, strlen($hwVlanPort)));
          $id_vlan = $id_vlan[1];
          $array_vlans = str_split($value, 16);
          for ($ranura_v = 0; $ranura_v < count($array_vlans); $ranura_v++) {
            if ($ranura_v == $ranura) {
              $tmp = str_split($array_vlans[$ranura_v]);
              if ($tmp[1] != "0") {
                $puertos_bin = base_convert($tmp[1], 16, 2);
                $puertos_bin = strrev($puertos_bin);
                $puertos_array_bin = str_split($puertos_bin);
                for ($bin_puerto = 0; $bin_puerto < count($puertos_array_bin); $bin_puerto++) {
                  if ($bin_puerto == $puerto_s) {
                    if ($puertos_array_bin[$bin_puerto] != "0") {
                      if ($id_vlan == 1) {
                        $avaible_vlan = true;
                      } else {
                        $all_vlans[] = [
                          "vlan_id" => $id_vlan
                        ];
                      }
                    }
                  }
                }
              }
            }
          }
        }

        if ($tipo == "gpon") {
          $tmp = [
            $this->library_oid->get_performance_oids("hwGponDeviceOltControlInfoDesc"),
            $this->library_oid->get_performance_oids("hwGponDeviceOltControlInfoMin"),
            $this->library_oid->get_performance_oids("hwGponDeviceOltControlInfoMax"),
            $this->library_oid->get_performance_oids("ifAdminStatus"),
            $this->library_oid->get_performance_oids("ifLastChange"),
          ];
        }
        if ($tipo == "uplink") {
          $tmp = [
            $this->library_oid->get_performance_oids("ifAdminStatus"),
            $this->library_oid->get_performance_oids("ifLastChange"),
          ];
        }

        $puertos = $this->snmp2_get_olt($id_olt, $if_index, $tmp, [$tipo == "gpon" ? 4 : 1]);
        $data["puerto"] = $puertos;
        $data["tipo"] = $tipo;
        $data["olt"] = $id_olt;
        $data["if_index"] = $if_index;
        $data["chasis"] = $chasis;
        $data["ranura"] = $ranura;
        $data["vlans"] = $all_vlans;
        $data["puerto_s"] = $puerto_s;
        $data["avaible_vlan"] = $avaible_vlan;
        if ($tipo == "gpon") return view("fiber/tab_puertos_olt/modal_editar_puerto_gpon", $data);
        if ($tipo == "uplink") return view("fiber/tab_puertos_olt/modal_editar_puerto_uplink", $data);
      } else {
        echo "<script>alerta(\"warning\",\"Esta opcion esta en desarrollo, solo GPON tiene esta opcion disponible por el momento.\")</script>";
      }
    }
  }
  function save_puerto_olt()
  {
    $permiso = $this->seguridad->Acceso_full('OLT', 'ESCRITURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $tipo = $this->request->getGet('tipo');
      if ($tipo) {
        $tipo = strtolower($tipo);
      }
      if ($tipo == "gpon") {
        $olt = $this->request->getGet('olt');
        $if_index = $this->request->getGet('if_index');
        $chasis = $this->request->getGet('chasis');
        $ranura = $this->request->getGet('ranura');
        $puerto = $this->request->getGet('puerto');
        $descripcion = $this->request->getPost('descripcion');
        $max = $this->request->getPost('max');
        $min = $this->request->getPost('min');
        $tmp = [
          [$this->library_oid->get_performance_oids("hwGponDeviceOltControlInfoDesc"), $descripcion, "s"],
          [$this->library_oid->get_performance_oids("hwGponDeviceOltControlInfoMin"), $min, "i"],
          [$this->library_oid->get_performance_oids("hwGponDeviceOltControlInfoMax"), $max, "i"]
        ];
        snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
        $save = $this->snmp2_set_olt_batch($olt, $if_index, $tmp);
        if ($save) {
          $txt_log = "Configuracion OLT [id_olt:$olt, chasis:$chasis, ranura: $ranura, puerto:$puerto] se configuro un puerto Gpon (descripcion: $descripcion, mix-km: $min, max-k: $max)";
          $this->model_log->insert_log(000000, "olt", $txt_log, $_SESSION['id_usuario8291']);
        }
        echo $save ? 1 : 2;
      } else {
        echo "<script>alerta(\"warning\",\"Ese tipo de puerto no se puede modificar, comuniquese con el administrador.\")</script>";
      }
    }
  }
  function eliminar_vlan_puerto()
  {
    $permiso = $this->seguridad->Acceso_full('OLT', 'ESCRITURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getPost('id');
      $olt = $this->request->getGet('olt');
      $chasis = $this->request->getGet('chasis');
      $ranura = $this->request->getGet('ranura');
      $puerto = $this->request->getGet('puerto');
      $command = "undo port vlan $id $chasis/$ranura $puerto";
      $response = $this->olt_one_command($olt, $command);
      $pos = strpos($response, "Failure");
      if ($pos === false) {
        echo 1;
      } else {
        echo 2;
      }
    } else {
      echo 3;
    }
  }
  function list_vlan()
  {
    $permiso = $this->seguridad->Acceso_full('OLT', 'ESCRITURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getPost('id');
      $olt = $this->request->getPost('olt');
      $chasis = $this->request->getPost('chasis');
      $ranura = $this->request->getPost('ranura');
      $puerto = $this->request->getPost('puerto');
      snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
      $hwVlanIndex = $this->library_oid->get_performance_oids("hwVlanIndex");
      $vlans = $this->snmp2_real_walk_olt($olt, $hwVlanIndex);
      $tmp = array();
      $i = 0;
      foreach ($vlans as $key => $value) {
        if ($i != 0) $tmp[] = $value;
        $i++;
      }
      return json_encode($tmp);
    }
  }
  function add_vlan_puerto()
  {
    $permiso = $this->seguridad->Acceso_full('OLT', 'ESCRITURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $vlan = $this->request->getPost('vlan');
      $olt = $this->request->getGet('olt');
      $if_index = $this->request->getGet('if_index');
      $chasis = $this->request->getGet('chasis');
      $ranura = $this->request->getGet('ranura');
      $puerto = $this->request->getGet('puerto');
      $command = "port vlan $vlan $chasis/$ranura $puerto";
      $response = $this->olt_one_command($olt, $command);
      $pos = strpos($response, "Failure");
      if ($pos === false) {
        echo 1;
      } else {
        echo 2;
      }
    } else {
      echo 3;
    }
  }
  function tab_vlans()
  {
    $permiso = $this->seguridad->Acceso_full('OLT', 'ESCRITURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getGet('id');
      $data["id"] = $id;
      return view("fiber/tab_puertos_olt/vlans", $data);
    } else {
      echo "No cuentas con los permisos necesarios.";
    }
  }
  function modal_new_vlan()
  {
    $permiso = $this->seguridad->Acceso_full('OLT', 'ESCRITURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $olt = $this->request->getGet('olt');
      $data["olt"] = $olt;
      return view("fiber/tab_puertos_olt/modal_new_vlan", $data);
    } else {
      echo "No cuentas con los permisos necesarios.";
    }
  }
  function delete_vlan()
  {
    $permiso = $this->seguridad->Acceso_full('OLT', 'ESCRITURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $olt = $this->request->getGet('olt');
      $id = $this->request->getPost('id');
      $command = "undo vlan $id to $id";
      $response = $this->olt_one_command($olt, $command);
      $pos = strpos($response, "Failure");
      if ($pos === false) {
        $data["status"] = 1;
      } else {
        if (strpos($response, "The VLAN has some service virtual ports")) $data["error"] = "La VLAN esta asignada a algunos puertos virtuales de servicio. (GPON, EPON o XPON)";
        if (strpos($response, "Some ports have been added to this VLAN")) $data["error"] = "Se han agregado algunos puertos a esta VLAN.";
        $data["status"] = 2;
      }
    } else {
      $data["status"] = 2;
    }
    return json_encode($data);
  }
  function new_vlan()
  {
    $permiso = $this->seguridad->Acceso_full('OLT', 'ESCRITURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $olt = $this->request->getGet('olt');
      $nombre = $this->request->getPost('nombre');
      $descripcion = $this->request->getPost('descripcion');
      $command = "vlan $nombre smart";
      $response = $this->olt_one_command($olt, $command);
      $pos = strpos($response, "Failure");
      if ($pos === false) {
        $data["status"] = 1;
      } else {
        if (strpos($response, "VLAN has existed")) $data["error"] = "El ID ($nombre) de la VLAN ya existe.";
        $data["status"] = 2;
      }
    } else {
      $data["status"] = 2;
    }
    return json_encode($data);
  }
  function get_vlans()
  {
    $permiso = $this->seguridad->Acceso_full('OLT', 'ESCRITURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getGet('id');
      snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
      $hwVlanPort = $this->library_oid->get_performance_oids("hwVlanPort");
      $vlans = $this->snmp2_real_walk_olt($id, $hwVlanPort);
      $ie = 0;

      $tmp = array();
      foreach ($vlans as $key => $value) {
        $id_vlan = explode(".", substr($key, strlen($hwVlanPort)));
        $id_vlan = $id_vlan[1];
        $counter = 0;
        if ($ie != 0) {
          $hex = str_split($value);
          for ($i = 0; $i < count($hex); $i++) {
            if ($hex[$i] != 0) {
              $puertos_bin = base_convert($hex[$i], 16, 2);
              $bin_split = str_split($puertos_bin);
              for ($e = 0; $e < count($bin_split); $e++) {
                if ($bin_split[$e] != 0) {
                  $counter++;
                }
              }
            }
          }
          $disabled = $counter == 0 ? "" : "disabled";
          $title = $counter == 0 ? "elimnar" : "Esta Vlan esta en uso, por lo que no se puede eliminar.";
          $tmp[] = [
            "num" => $ie,
            "id" => $id_vlan,
            "descripcion" => "",
            "tool" => "<button data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"delete_vlan($id_vlan,$counter)\" data-original-title=\"$title\">
                      <i class=\"far fa-trash-alt\" aria-hidden=\"true\"></i>
                    </button>",
            "uso" => $counter == 0 ? "N/A" : $counter,

          ];
        }
        $ie++;
      }
      $data["data"] = $tmp;
      return json_encode($data);
    }
  }
  function change_status_port()
  {
    $permiso = $this->seguridad->Acceso_full('OLT', 'ESCRITURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $tipo = $this->request->getGet('tipo');
      if ($tipo) {
        $tipo = strtolower($tipo);
      }
      if ($tipo == "gpon" or $tipo == "uplink") {
        $olt = $this->request->getGet('olt');
        $if_index = $this->request->getGet('if_index');
        $chasis = $this->request->getGet('chasis');
        $ranura = $this->request->getGet('ranura');
        $puerto = $this->request->getGet('puerto');
        $num = $this->request->getPost('num');
        if ($num <> 1) {
          $num = 2;
        }
        $tmp = [
          [$this->library_oid->get_performance_oids("ifAdminStatus"), $num, "i"]
        ];
        snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
        $save = $this->snmp2_set_olt_batch($olt, $if_index, $tmp);
        if ($save) {
          $txt_log = "Configuracion OLT [id_olt: $olt, chasis: $chasis, ranura: $ranura, puerto: $puerto] se " . $puerto == 1 ? "habilito" : "deshabilito" . " el puerto";
          $this->model_log->insert_log(000000, "olt", $txt_log, $_SESSION['id_usuario8291']);
        }
        echo $save ? 1 : 2;
      } else {
        echo "<script>alerta(\"warning\",\"Ese tipo de puerto no se puede modificar, comuniquese con el administrador.\")</script>";
      }
    }
  }

  protected function formatSizeUnits($bytes)
  {


    if ($bytes >= 1024) {
      $bytes = number_format($bytes / 1024, 2) . ' MB';
    } else {
      $bytes = '0 bytes';
    }

    return $bytes;
  }
  protected function snmp2_real_walk_olt(int $id_olt, string $oid)
  {
    $row_olt = $this->model_fiber->get_olt_id($id_olt);
    $response = \App\Libraries\Snmp::walk("$row_olt->ip", "$row_olt->comunidad_escritura", $oid);
    return $response;
  }
  protected function snmp2_get_olt(int $id_olt, $if_index, array $oids, array $time_sticks = array())
  {
    $row_olt = $this->model_fiber->get_olt_id($id_olt);
    $tmp = array();
    foreach ($oids as $key => $value) {
      $response =\App\Libraries\Snmp::get("$row_olt->ip", "$row_olt->comunidad_escritura", $value . $if_index);
      $tmp[] = $response;
    }
    foreach ($time_sticks as $key => $value) {
      $tmp[$value] = $this->timeticks_convert($tmp[$value]);
    }
    return $tmp;
  }
  protected function snmp2_set_olt(int $id_olt, string $oid, string $tipe, string $new_val): bool
  {
    $row_olt = $this->model_fiber->get_olt_id($id_olt);
    $response =\App\Libraries\Snmp::set("$row_olt->ip", "$row_olt->comunidad_escritura", $oid, $tipe, $new_val);
    return $response;
  }
  protected function olt_one_command(int $id_olt, $command)
  {
    $row_olt = $this->model_fiber->get_olt_id($id_olt);
    $response = shell_exec("/var/www/html/app/scripts/olt_onecommand.sh $row_olt->ip $row_olt->user $row_olt->pass \"$command\"");
    return $response;
  }
  protected function snmp2_set_olt_batch(int $id_olt, $if_index, array $oids): bool
  {
    $tmp = false;
    $row_olt = $this->model_fiber->get_olt_id($id_olt);
    foreach ($oids as $key => $value) {
      $oid = $value[0];
      $new_val = $value[1];
      $type = $value[2];
      $response =\App\Libraries\Snmp::set("$row_olt->ip", "$row_olt->comunidad_escritura", $oid . $if_index, $type, $new_val);
      if ($response) {
        $tmp = true;
      }
    }
    return $tmp;
  }
  protected function ordenar(string $oid, array $oids): array
  {
    $oid = $oid . ".";
    $keys = array_keys($oids);
    $count = count($oids);
    $counter = 0;
    $counter2 = 0;
    $new_oids = array();
    $chasis = array();
    $ranura = array();
    for ($i = 0; $i < count($keys); $i++) {
      $search = strpos($keys[$i], $oid);
      if ($search === false) {
      } else {
        $counter++;
      }
    }
    foreach ($oids as $key => $value) {
      $new_oids[] = $value;
    }
    $data_tmp = array();
    $tmp_count = $counter;
    for ($e = 0; $e < $tmp_count; $e++) {
      $tmp = array();
      for ($i = 0; $i < count($new_oids); $i = $i + $counter) {
        $tmp[] = $new_oids[$e + $i];
      }
      $data_tmp[] = $tmp;
    }
    for ($i = 0; $i < $counter; $i++) {
      $chasis[] = substr($keys[$i], strlen($oid), 1);
      $ranura_s = explode(".", substr($keys[$i], strlen($oid)));
      $ranura[] = $ranura_s[1];
    }
    foreach ($data_tmp as $key => $value) {
      $data_tmp[$key]["chasis"] = $chasis[$key];
      $data_tmp[$key]["ranura"] = $ranura[$key];
    }
    $data["rows"] = $counter;
    $data["data"] = $data_tmp;
    return $data;
  }

  protected function quitar_tildes($cadena)
  {
    $no_permitidas = array("á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú", "ñ", "À", "Ã", "Ì", "Ò", "Ù", "Ã™", "Ã ", "Ã¨", "Ã¬", "Ã²", "Ã¹", "ç", "Ç", "Ã¢", "ê", "Ã®", "Ã´", "Ã»", "Ã‚", "ÃŠ", "ÃŽ", "Ã”", "Ã›", "ü", "Ã¶", "Ã–", "Ã¯", "Ã¤", "«", "Ò", "Ã", "Ã„", "Ã‹");
    $permitidas = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "n", "N", "A", "E", "I", "O", "U", "a", "e", "i", "o", "u", "c", "C", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U", "u", "o", "O", "i", "a", "e", "U", "I", "A", "E");
    $texto = str_replace($no_permitidas, $permitidas, $cadena);
    return $texto;
  }
  protected function txt_explode($array): array
  {
    $array = explode("\n", $array);
    $tmp = array();
    foreach ($array as $key => $value) {
      $tmp[] = preg_replace(['/\s+/', '/^\s|\s$/'], [' ', ''], $value);
    }
    return $tmp;
  }
  protected function find_array(string $aguja, array $pajar)
  {
    foreach ($pajar as $key1 => $value1) {
      $tmp = explode(" ", $value1);
      foreach ($tmp as $key2 => $value2) {
        if ($aguja == $value2) {
          return $key1;
        }
      }
    }
    return false;
  }
  protected function timeticks_convert($timeticks)
  {
    if ($timeticks <= 0) {
      $ConvertSeconds = "N/A";
    } else {
      $lntSecs = intval($timeticks / 100);
      $intDays = intval($lntSecs / 86400);
      $intHours = intval(($lntSecs - ($intDays * 86400)) / 3600);
      $intMinutes = intval(($lntSecs - ($intDays * 86400) - ($intHours * 3600)) / 60);
      $intSeconds = intval(($lntSecs - ($intDays * 86400) - ($intHours * 3600) - ($intMinutes * 60)));
      $d = $intDays != 0 ? "$intDays Dias" : "";
      $h = $intHours != 0 ? "$intHours Horas" : "";
      $m = $intMinutes != 0 ? "$intMinutes Min" : "";
      $s = $intSeconds != 0 ? "$intSeconds Seg" : "";
      $ConvertSeconds = $d . " " . $h . " " . $m . " " . $s;
    }
    return $ConvertSeconds;
  }
}
