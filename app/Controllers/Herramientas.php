<?php

namespace App\Controllers;


use App\Libraries\Class_seguridad;
use App\Libraries\Class_permiso_sucursal;
use App\Libraries\Ciqrcode;
use App\Models\Model_clientes;
use App\Models\Model_selectores;
use App\Models\Model_configuracion;
use App\Models\Model_log;
use phpseclib3\Net\SSH2;
use phpseclib3\File\ANSI;

class Herramientas extends BaseController
{

  var $ansi;
  var $ssh;
  var $loader;
  var $model_clientes;
  var $model_selectores;
  var $Model_configuracion;
  var $model_configuracion;
  var $permiso_nodo;
  var $seguridad;
  var $ciqrcode;
  var $nmodel_log;
  var $model_log;

  public function __construct()
  {
    $this->model_clientes = new Model_clientes();
    $this->model_selectores = new Model_selectores();
    $this->model_configuracion = new Model_configuracion();
    $this->model_log = new Model_log();
    $this->permiso_nodo = new Class_permiso_sucursal();
    $this->seguridad = new Class_seguridad();
    session();

    $this->ansi = new ANSI();
  }

  // Sesión SSH con el CMTS. Se abre solo cuando un método la usa (antes se abría
  // en el constructor y, si fallaba, todo el módulo terminaba en exit)
  protected function cmts(): SSH2
  {
    if ($this->ssh === null) {
      $cfg = config('Integraciones');
      $this->ssh = new SSH2($cfg->cmtsHost, (int) $cfg->cmtsPort);
      if (!$this->ssh->login($cfg->cmtsUser, $cfg->cmtsPass)) exit('Login Failed');
    }
    return $this->ssh;
  }

  // La MAC se concatena en comandos del CMTS: solo hex y separadores
  protected function mac_valida($mac): bool
  {
    return is_string($mac) && preg_match('/^[0-9A-Fa-f]{2}([.:-]?[0-9A-Fa-f]{2}){5}$|^[0-9A-Fa-f]{4}\.[0-9A-Fa-f]{4}\.[0-9A-Fa-f]{4}$/D', $mac) === 1;
  }

  public function viewmodem()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso("LISTA_CLIENTES"));
      if ($tipoacceso > 0) {
        $id_servicio = $this->request->getGet('id');

        $user_data_service = $this->model_clientes->get_servicios_by_id($id_servicio);
        if ($user_data_service) {

          $mac_original = $user_data_service->mac;
          if (!$this->mac_valida($mac_original)) { echo "<p>MAC invalida.</p>"; return; }
          $mac_1 = "";
          $mac_2 = "";



          $this->cmts()->write("terminal length 0 \n");
          $this->cmts()->write("show cable modem " . $mac_original . " verbose \n");
          $this->cmts()->write("show cable modem " . $mac_original . " phy \n");
          $this->cmts()->write("show cable modem " . $mac_original . "\n");
          $this->cmts()->write("show cable modem " . $mac_original . " cpe \n");
          $this->cmts()->setTimeout(10);
          $this->ansi->appendString($this->cmts()->read());
          $html =  $this->ansi->getHistory(); // salidas HTML


          if (strpos($html, 'not registered.') !== false) {
            $this->ansi = new ANSI();
            $mac_1 = substr($mac_original, 0, -2) . strtoupper(dechex(hexdec(substr($mac_original, -2)) - 2));
            $this->cmts()->write("terminal length 0 \n");
            $this->cmts()->write("show cable modem " . $mac_1 . " verbose \n");
            $this->cmts()->write("show cable modem " . $mac_1 . " phy \n");
            $this->cmts()->write("show cable modem " . $mac_1 . "\n");
            $this->cmts()->write("show cable modem " . $mac_1 . " cpe \n");
            $this->cmts()->setTimeout(10);
            $this->ansi->appendString($this->cmts()->read());
            $html =  $this->ansi->getHistory(); // salidas HTML
            if (strpos($html, 'not registered.') !== false) {
              $this->ansi = new ANSI();
              $mac_2 = substr($mac_original, 0, -2) . strtoupper(dechex(hexdec(substr($mac_original, -2)) - 1));
              $this->cmts()->write("terminal length 0 \n");
              $this->cmts()->write("show cable modem " . $mac_2 . " verbose \n");
              $this->cmts()->write("show cable modem " . $mac_2 . " phy \n");
              $this->cmts()->write("show cable modem " . $mac_2 . "\n");
              $this->cmts()->write("show cable modem " . $mac_2 . " cpe \n");
              $this->cmts()->setTimeout(10);
              $this->ansi->appendString($this->cmts()->read());
              $html =  $this->ansi->getHistory(); // salidas HTML
              echo "<div style='font-size:1rem;'>$html</div>";
            } else {
              echo "<div style='font-size:1rem;'>$html</div>";            
}
          } else {
            echo "<div style='font-size:1rem;'>$html</div>";
          }
        } //if user dat               


      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function rebootmodem()
  {
    if (isset($_SESSION['id_usuario8291'])) {

      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_CLIENTES'));
      if ($tipoacceso > 0) {
        $id_servicio = $this->request->getGet('id');
        $user_data_service = $this->model_clientes->get_servicios_by_id($id_servicio);
        if ($user_data_service) {

          $mac_original = $user_data_service->mac;
          if (!$this->mac_valida($mac_original)) { echo "<p>MAC invalida.</p>"; return; }
          $mac_1 = "";
          $mac_2 = "";

          $this->cmts()->write("terminal length 0 \n");
          $this->cmts()->write("clear cable modem " . $mac_original . " reset \n");
          $this->cmts()->setTimeout(10);
          $this->ansi->appendString($this->cmts()->read());
          $html =  $this->ansi->getHistory(); // salidas HTML 
          $html1 = '';


          if (strpos($html, 'not registered.') !== false) {
            $this->ansi = new ANSI();
            $mac_1 = substr($mac_original, 0, -2) . strtoupper(dechex(hexdec(substr($mac_original, -2)) - 2));
            $this->cmts()->write("terminal length 0 \n");
            $this->cmts()->write("clear cable modem " . $mac_1 . " reset \n");
            $this->cmts()->setTimeout(10);
            $this->ansi->appendString($this->cmts()->read());
            $html =  $this->ansi->getHistory(); // salidas HTML
            if (strpos($html, 'not registered.') !== false) {
              $this->ansi = new ANSI();
              $mac_2 = substr($mac_original, 0, -2) . strtoupper(dechex(hexdec(substr($mac_original, -2)) - 1));
              $this->cmts()->write("terminal length 0 \n");
              $this->cmts()->write("clear cable modem " . $mac_2 . " reset \n");
              $this->cmts()->setTimeout(10);
              $this->ansi->appendString($this->cmts()->read());
              $html =  $this->ansi->getHistory(); // salidas HTML
              $html1 = '<div class="alert alert-danger" style="width:100%;margin-bottom:-10px"><strong><div class="ubloader"><i class="fa fa-spinner fa-spin fa-lg fa-fw"></i><span>REBOOT MODEM...</span></div></strong></div>';
              echo $html1 . $html;
            } else {

              $html1 = '<div class="alert alert-danger" style="width:100%;margin-bottom:-10px"><strong><div class="ubloader"><i class="fa fa-spinner fa-spin fa-lg fa-fw"></i><span>REBOOT MODEM...</span></div></strong></div>';
              echo $html1 . $html;
            }
          } else {

            $html1 = '<div class="alert alert-danger" style="width:100%;margin-bottom:-10px"><strong><div class="ubloader"><i class="fa fa-spinner fa-spin fa-lg fa-fw"></i><span>REBOOT MODEM...</span></div></strong></div>';
            echo $html1 . $html;
          }
        }
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function rebootmodemestatus()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_CLIENTES'));
      if ($tipoacceso > 0) {
        $id_servicio = $this->request->getGet('id');
        $user_data_service = $this->model_clientes->get_servicios_by_id($id_servicio);
        if ($user_data_service) {
          $this->ansi = new ANSI();
          $mac_original = $user_data_service->mac;
          if (!$this->mac_valida($mac_original)) { echo "<p>MAC invalida.</p>"; return; }
          $mac_1 = "";
          $mac_2 = "";

          $html = 'not registered.';
          if (strpos($html, 'not registered.') !== false) {
            $this->ansi = new ANSI();
            $mac_1 = substr($mac_original, 0, -2) . strtoupper(dechex(hexdec(substr($mac_original, -2)) - 2));
            $this->cmts()->write("terminal length 0 \n");
            $this->cmts()->write("show cable modem " . $mac_1 . "\n");
            $this->cmts()->setTimeout(10);
            $this->ansi->appendString($this->cmts()->read());
            $html =  $this->ansi->getScreen(); // salidas HTML
            $html1 = "";

            if (strpos($html, 'w-online(pt)')) { // si contiene w-online(pt) es que ya rebooteo
              $html1 = '<div class="alert alert-success" style="width:100%;margin-bottom:-10px"><strong>Modem REBOOT SUCCES... ESPERA QUE CPE SEA = 1</strong></div>';
              echo $html1 . $html;
            } else { // si no esta reboteado vuelve a hacer el proceso

              if (strpos($html, 'not registered.') !== false) {
                $this->ansi = new ANSI();
                $mac_2 = substr($mac_original, 0, -2) . strtoupper(dechex(hexdec(substr($mac_original, -2)) - 1));
                $this->cmts()->write("terminal length 0 \n");
                $this->cmts()->write("show cable modem " . $mac_2 . "\n");
                $this->cmts()->setTimeout(10);
                $this->ansi->appendString($this->cmts()->read());
                $html =  $this->ansi->getHistory(); // salidas HTML


                if (strpos($html, 'w-online(pt)')) { // si contiene w-online(pt) es que ya rebooteo
                  $html1 = '<div class="alert alert-success" style="width:100%;margin-bottom:-10px"><strong>Modem REBOOT SUCCES... ESPERA QUE CPE SEA = 1</strong></div>';
                  echo $html1 . $html;
                } else {
                  $html1 = '<div class="alert alert-danger" style="width:100%;margin-bottom:-10px;"><strong><div class="ubloader"><i class="fa fa-spinner fa-spin fa-lg fa-fw"></i><span>REBOOT MODEM...</span></div></strong></div>';
                  echo $html1 . $html;
                }
              } else {
                $html1 = '<div class="alert alert-danger" style="width:100%;margin-bottom:-10px"><strong><div class="ubloader"><i class="fa fa-spinner fa-spin fa-lg fa-fw"></i><span>REBOOT MODEM...</span></div></strong></div>';
                echo $html1 . $html;
              }
            }
          }
        } // if data service               


      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
}
