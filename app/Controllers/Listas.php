<?php

namespace App\Controllers;

set_time_limit(0);
ini_set('memory_limit', -1);
ini_set("pcre.backtrack_limit", "10000000");
use App\Libraries\Class_seguridad;
use App\Libraries\Class_permiso_sucursal;
use App\Models\Model_listas;
use App\Models\Model_selectores;
use App\Models\Model_log;


class Listas extends BaseController
{
  var $model_listas;
  var $model_selectores;
  var $permiso_sindicato;
  var $seguridad;
  var $model_log;

  public function __construct()
  {
    $this->model_listas = new Model_listas();
    $this->model_selectores = new Model_selectores();
    $this->model_log = new Model_log();
    $this->permiso_sindicato = new Class_permiso_sucursal();
    $this->seguridad = new Class_seguridad();

    session();
  }

  public function socios()
  {
    if (isset($_SESSION['id_usuario8291'])) {

      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('REPORTE_SOCIOS'));
      if ($tipoacceso > 0) {

        $permiso_sindicato_select = $this->permiso_sindicato->get_permiso_socios_select($_SESSION['sindicatos']);
        $data['sindicatos'] = $this->model_selectores->get_all_sindicatos_by_user($permiso_sindicato_select);
        $data['token'] = $_SESSION['token8291'];
        $data['tipoacceso'] = $tipoacceso;
        echo view('listas/socios', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function unidades()
  {
    if (isset($_SESSION['id_usuario8291'])) {

      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('REPORTE_UNIDADES'));
      if ($tipoacceso > 0) {

        $permiso_sindicato_select = $this->permiso_sindicato->get_permiso_socios_select($_SESSION['sindicatos']);
        $data['sindicatos'] = $this->model_selectores->get_all_sindicatos_by_user($permiso_sindicato_select);
        $data['token'] = $_SESSION['token8291'];
        $data['tipoacceso'] = $tipoacceso;
        echo view('listas/unidades', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function get_socios_json()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('REPORTE_SOCIOS'));
      if ($tipoacceso > 0) {

        $tipo = $this->request->getGet('status');
        $sindicato = $this->request->getGet('sindicato');
        $licencia = $this->request->getGet('licencia');
        $permiso_sindicato = $this->permiso_sindicato->get_permiso_socios($_SESSION['sindicatos']);

        $socios = $this->model_listas->get_all_socios($permiso_sindicato, $tipo, $sindicato, $licencia);

        $array_cliente = array();
        $estado = "";
        $tool = "";
        $status = "";
        $licencia = "";
        if ($socios) {
          foreach ($socios as $items) {
            if ($items->status == "ACTIVO") {
              $status = "<span class='badge badge-primary'>" . $items->status . "</span>";
            } else if ($items->status == "PRUEBA") {
              $status = "<span class='badge badge-success'>" . $items->status . "</span>";
            } else if ($items->status == "SUSPENDIDO") {
              $status = "<span class='badge badge-warning'>" . $items->status . "</span>";
            } else {
              $status = "<span class='badge badge-danger'>" . $items->status . "</span>";
            }

            if ($items->licencia == "SI") {
              $licencia = "<span class='badge badge-success'>SI</span>";
            } else {
              $licencia = "<span class='badge badge-danger'>NO</span>";
            }
            $array_item = array(
              "id" => $items->id,
              "nombre" => '<a href="#socios/view?id=' . $items->id . '&amp;token=' . $_SESSION['token8291'] . '"  >' . $items->nombre . ' ' . $items->apellidos . '</a>',
              "domicilio" => $items->domicilio,
              "movil" => $items->movil,
              "correo" => $items->correo,
              "licencia" => $licencia,
              "ine" => $items->ine,
              "sindicato" => "<span class='badge badge-danger'>" . $items->nombre_sindicato . "</span>",
              "fechaalta" => date('d/m/Y', strtotime($items->fechaalta)),
              "status" => $status,
              "tipoacceso" => $tipoacceso
            );
            array_push($array_cliente, $array_item);
          }
        }
        echo '{"data":' . json_encode($array_cliente) . '}';
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function get_unidades_json()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('REPORTE_SOCIOS'));
      if ($tipoacceso > 0) {

        $status = $this->request->getGet('status');
        $sindicato = $this->request->getGet('sindicato');
        $modounidad = $this->request->getGet('modounidad');
        $tipounidad = $this->request->getGet('tipounidad');
        $permiso_sindicato = $this->permiso_sindicato->get_permiso_socios($_SESSION['sindicatos']);

        $unidades = $this->model_listas->get_all_unidades($permiso_sindicato, $status, $sindicato, $modounidad, $tipounidad);

        $array_cliente = array();
        $estado = "";
        $tool = "";
        $status = "";
        $licencia = "";
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
              "fechaalta" => date('d/m/Y', strtotime($items->fechaalta)),
              "modounidad" => $modounidad,
              "estado" => $status,
              "descripcion" => $items->descripcion,
              "tool" => $tool,
              "sindicato" => $items->nombre_sindicato,
              "tipoacceso" => $tipoacceso
            );
            array_push($array_cliente, $array_item);
          }
        }
        echo '{"data":' . json_encode($array_cliente) . '}';
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function viewreporte()
  {
    if (isset($_SESSION['id_usuario8291']) &&  $this->request->getPost('token') == $_SESSION['token8291']) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('REPORTE_SOCIOS'));
      if ($tipoacceso > 0) {

        $tipo = $this->request->getPost('status');
        $sindicato = $this->request->getPost('sindicato');
        $licencia = $this->request->getPost('licencia');
        $permiso_sindicato = $this->permiso_sindicato->get_permiso_socios($_SESSION['sindicatos']);



        $socios = $this->model_listas->get_all_socios($permiso_sindicato, $tipo, $sindicato, $licencia);

        $array_cliente = array();
        $estado = "";
        $tool = "";
        $status = "";
        $licencia = "";

        $mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);

        $data['socios'] = $socios;
        $html = view('listas/pdf_socios', $data);

        $mpdf->WriteHTML($html);
        $mpdf->Output("./writable/tmp/reporte_socios.pdf", "F"); // opens in browser
        $this->show("reporte_socios.pdf");
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function viewreporte_unidades()
  {
    if (isset($_SESSION['id_usuario8291']) &&  $this->request->getPost('token') == $_SESSION['token8291']) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('REPORTE_UNIDADES'));
      if ($tipoacceso > 0) {

        $status = $this->request->getPost('status');
        $sindicato = $this->request->getPost('sindicato');
        $modounidad = $this->request->getPost('modounidad');
        $tipounidad = $this->request->getPost('tipounidad');
        $permiso_sindicato = $this->permiso_sindicato->get_permiso_socios($_SESSION['sindicatos']);

        $unidades = $this->model_listas->get_all_unidades($permiso_sindicato, $status, $sindicato, $modounidad, $tipounidad);

        $mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);

        $data['unidades'] = $unidades;
        $html = view('listas/pdf_unidades', $data);
        $mpdf->WriteHTML($html);
        $mpdf->Output("./writable/tmp/reporte_unidades.pdf", "F"); // opens in browser
        $this->show("reporte_unidades.pdf");
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function show($file)
  {
    if (is_dir("./writable/tmp/")) {
      $path = "./writable/tmp/" . $file;
      if (file_exists("./writable/tmp/" . $file)) {

        header("Content-Length: " . filesize($path));
        header("Content-type: application/pdf");
        header("Content-disposition: inline; filename=" . basename($path));
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        ob_clean();
        flush();
        readfile($path);
      }
    }
  }
}
