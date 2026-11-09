<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Model_rutas;
use App\Libraries\Class_seguridad;

class Rutas extends BaseController
{
  public function __construct()
  {
    $this->seguridad = new Class_seguridad();
    $this->model_rutas = new Model_rutas();
    session();
  }

  public function index()
  {
    if ($this->seguridad->Acceso('RUTAS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      return view('rutas/index');
    }
  }

  public function get_rutas()
  {
    if ($this->seguridad->Acceso('RUTAS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $all_items = $this->model_rutas->traer_rutas();

      $array_cliente = array();
      if ($all_items) {
        $tipo = 0;
        foreach ($all_items as $items) {
          if ($items->activo == 2) {
            $especial = "<u>Especial </u>";
          } else {
            $especial = "";
          }

          if ($items->padre != 0) {
            $padre = "";
            foreach ($all_items as $item) {
              if ($items->padre == $item->id_permiso) {
                $padre = $item->modulo . " <sup>id:$item->id_permiso</sup>";
              }
            }
            $tipo = $especial . "Hijo de: <b>$padre</b>";
          }
          $items->padre == 0 ? $tipo = "Padre" : null;

          $array_item = array(
            "id" => $items->id_permiso,
            "modulo" => $items->modulo,
            "url" => $items->url,
            "padre" => $tipo,
            "tool" => "<a data-toggle=\"tooltip\" title=\"\" class=\" btn btn-default btn-icon btn-sm\" onclick=\"eliminar('$items->id_permiso')\" data-original-title=\"Eliminar\"><i class=\"far fa-trash-alt\" aria-hidden=\"true\"></i></a>"
          );
          array_push($array_cliente, $array_item);
        }
      }
      echo '{"data":' . json_encode($array_cliente) . '}';
    }
  }
  public function new_ruta()
  {
    if ($this->seguridad->Acceso('RUTAS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $data['data'] = $this->model_rutas->traer_hijos();
      return view('rutas/new', $data);
    }
  }

  public function create_ruta()
  {
    if ($this->seguridad->Acceso('RUTAS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $especial = $this->request->getPost('especial');
      $imagen = $this->request->getPost('imagen');
      $nombre = $this->request->getPost('nombre');
      $nombre = ucfirst($nombre);             // HELLO WORLD!
      $nombre = ucfirst(strtolower($nombre));
      $descripcion = $this->request->getPost('descripcion');
      $url = $this->request->getPost('url');
      $padre = $this->request->getPost('padre');
      $newRuta = [
        'modulo' => $nombre,
        'descripcion' => $descripcion,
        'url' => $url,
        'padre' => $padre,
        'activo' => $especial == 2 ? 2 : 1,
        'clase' => $imagen,
      ];
      $insert = $this->model_rutas->create_ruta($newRuta);
      if ($insert) {
        echo '1';
      } else {
        echo '2';
      }
    }
  }
  public function delete_ruta()
  {
    if ($this->seguridad->Acceso('RUTAS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getPost('id');
      $delete = $this->model_rutas->delete_ruta($id);
      if ($delete) {
        echo '1';
      } else {
        echo '2';
      }
    }
  }
}
