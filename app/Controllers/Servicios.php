<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Model_servicios;
use App\Models\Model_selectores;
use App\Libraries\Class_seguridad;
use App\Models\Model_log;

class Servicios extends BaseController
{

  var $model_servicios;
  var $model_selectores;
  var $seguridad;

  public function __construct()
  {
    $this->model_log = new Model_log();
    $this->seguridad = new Class_seguridad();
    $this->model_servicios = new Model_servicios();
    $this->model_selectores = new Model_selectores();
    date_default_timezone_set('America/Mexico_City');
    session();
  }
  public function internet()
  {
    $permiso = $this->seguridad->Acceso('INTERNET', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $data['permiso'] = $permiso;
      return view('servicios/internet', $data);
    }
  }
  public function streaming()
  {
    if ($this->seguridad->Acceso('INTERNET', 'LECTURA', $_SESSION['id_usuario8291'])) {
      return view('servicios/internet');
    }
  }
  public function get_planes()
  {
    $permiso = $this->seguridad->Acceso_full('INTERNET', 'LECTURA', $_SESSION['id_usuario8291']);
    $nodos_number = array();
    if ($permiso) {
      $nodos = $this->model_servicios->get_sucursales_nodo($_SESSION['id_usuario8291']);

      if ($nodos['todos'] == true) {
        $todo = true;
      } else {
        $todo = false;
        foreach ($nodos['datos'] as $key => $value) {
          $nodos_number[] = $value;
        }
      }
      $planes = $this->model_servicios->get_planes($nodos_number, $todo);
      if ($planes) {
        foreach ($planes as $value) {
          $eliminar = "";
          $velocidad = explode('/', $value->velocidad);
          $value->costo = "MX$" . $value->costo;
          $value->up = substr($velocidad[0], 0, -1);
          $value->down = substr($velocidad[1], 0, -1);
          $id = $value->id;
          $activo = $value->activo;
          if ($permiso >= 3) {
            $eliminar = "<a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"delete_internet('$id','$activo')\" data-original-title=\"Eliminar\">
                <i class=\"far fa-trash-alt\" aria-hidden=\"true\"></i>
              </a>";
          }
          $value->tool = "
            <td class=\"text-center\">
              $eliminar
              <a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"getmodal('servicios/modal_editar?id=$id','Editar Perfil','lg');\" data-original-title=\"Editar\">
                <i class=\"far fa-edit\"></i>
              </a>
            </td>
          ";
        }
        $data['data'] = $planes;
      } else {
        $data['data'] = array();
      }
      return json_encode($data);
    }
  }
  public function delete_plan()
  {
    if ($this->seguridad->Acceso_full('INTERNET', 'ELIMINAR', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getPost('id');
      $result = $this->model_servicios->delete_plan($id);
      echo $result ? 1 : 2;
    }
  }
  public function modal_editar()
  {
    $permiso = $this->seguridad->Acceso('INTERNET', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getGet('id');
      $plan = $this->model_servicios->get_plan($id);
      $servers = $this->model_selectores->get_all_nodos();
      if ($plan and $servers) {
        $velocidad = explode('/', $plan->velocidad);
        $plan->up = substr($velocidad[0], 0, -1);
        $plan->down = substr($velocidad[1], 0, -1);
        $data['plan'] = $plan;
        $data['select'] = $servers;
        $data['permiso'] = $permiso;
        return view('servicios/utils/modal_editar_plan', $data);
      } else {
        echo 'El plan/server no existe o fue eliminado';
      }
    }
  }
  public function save_edit_plan()
  {
    if ($this->seguridad->Acceso('INTERNET', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getPost('id');
      $nombre = $this->request->getPost('nombre');
      $descripcion = $this->request->getPost('descripcion');
      $precio = $this->request->getPost('precio');
      $precio_prepago = $this->request->getPost('precioPrepago');
      $impuesto = $this->request->getPost('impuesto');
      $id_mikrotik = $this->request->getPost('id_mikrotik[]');
      $api_mk = $this->request->getPost('api_mk');
      $descarga = $this->request->getPost('descarga');
      $limitat_descarga = $this->request->getPost('limitat_descarga');
      $blimit_descarga = $this->request->getPost('blimit_descarga');
      $bthreshold_descarga = $this->request->getPost('bthreshold_descarga');
      $btime_descarga = $this->request->getPost('btime_descarga');
      $prioridad_descarga = $this->request->getPost('prioridad_descarga');
      $pcq_descarga = $this->request->getPost('pcq_descarga');
      $profile = $this->request->getPost('profile');
      $subida = $this->request->getPost('subida');
      $limitat_subida = $this->request->getPost('limitat_subida');
      $blimit_subida = $this->request->getPost('blimit_subida');
      $bthreshold_subida = $this->request->getPost('bthreshold_subida');
      $btime_subida = $this->request->getPost('btime_subida');
      $prioridad_subida = $this->request->getPost('prioridad_subida');
      $pcq_subida = $this->request->getPost('pcq_subida');
      $lista = $this->request->getPost('lista');
      $ids_mikrotik = "";
      foreach ($id_mikrotik as $key => $value) {
        $ids_mikrotik .= $key == 0 ? $value : "," . $value;
      }
      $data_plan = [
        'plan' => $nombre,
        'descripcion' => $descripcion,
        'velocidad' => $subida . "K/" . $descarga . "K",
        'costo' => $precio,
        'costo_prepago' => $precio_prepago==""?null:$precio_prepago,
        'profile' => $profile,
        'lista' => $lista,
        'prioridad_descarga' => $prioridad_descarga,
        'pcq_descarga' => $pcq_descarga,
        'limitat_descarga' => $limitat_descarga == "" ? 0 : $limitat_descarga,
        'burst_limit_descarga' => $blimit_descarga == "" ? 0 : $blimit_descarga,
        'burst_threshold_descarga' => $bthreshold_descarga == "" ? 0 : $bthreshold_descarga,
        'burst_time_descarga' => $btime_descarga == "" ? 0 : $btime_descarga,
        'prioridad_subida' => $prioridad_subida,
        'pcq_subida' => $pcq_subida,
        'limitat_subida' => $limitat_subida == "" ? 0 : $limitat_subida,
        'burst_limit_subida' => $blimit_subida == "" ? 0 : $blimit_subida,
        'burst_threshold_subida' => $bthreshold_subida == "" ? 0 : $bthreshold_subida,
        'burst_time_subida' => $btime_subida == "" ? 0 : $btime_subida,
        'impuesto' => $impuesto,
        'api' => $api_mk,
        'id_mikrotik' => $ids_mikrotik,
      ];
      $result_update = $this->model_servicios->update_plan($id, $data_plan);
      if ($result_update) {
        $txt_log = "Nuevo perfil editado ID: " . $id;
        $this->model_log->insert_log(000000, "perfiles", $txt_log, $_SESSION['id_usuario8291']);
      }
      echo $result_update ? 1 : 2;
    }
  }
  public function modal_nuevo_plan()
  {
    $permiso = $this->seguridad->Acceso('INTERNET', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $servers = $this->model_selectores->get_all_nodos();
      $data['select'] = $servers;
      $data['permiso'] = $permiso;
      return view('servicios/utils/modal_new_plan', $data);
    }
  }
  public function save_new_plan()
  {
    if ($this->seguridad->Acceso('INTERNET', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $nombre = $this->request->getPost('nombre');
      $descripcion = $this->request->getPost('descripcion');
      $precio = $this->request->getPost('precio');
      $precioPrepago = $this->request->getPost('precioPrepago');
      $impuesto = $this->request->getPost('impuesto');
      $id_mikrotik = $this->request->getPost('id_mikrotik[]');
      $api_mk = $this->request->getPost('api_mk');
      $descarga = $this->request->getPost('descarga');
      $limitat_descarga = $this->request->getPost('limitat_descarga');
      $blimit_descarga = $this->request->getPost('blimit_descarga');
      $bthreshold_descarga = $this->request->getPost('bthreshold_descarga');
      $btime_descarga = $this->request->getPost('btime_descarga');
      $prioridad_descarga = $this->request->getPost('prioridad_descarga');
      $pcq_descarga = $this->request->getPost('pcq_descarga');
      $profile = $this->request->getPost('profile');
      $subida = $this->request->getPost('subida');
      $limitat_subida = $this->request->getPost('limitat_subida');
      $blimit_subida = $this->request->getPost('blimit_subida');
      $bthreshold_subida = $this->request->getPost('bthreshold_subida');
      $btime_subida = $this->request->getPost('btime_subida');
      $prioridad_subida = $this->request->getPost('prioridad_subida');
      $pcq_subida = $this->request->getPost('pcq_subida');

      $lista = $this->request->getPost('lista');
      $ids_mikrotik = "";
      foreach ($id_mikrotik as $key => $value) {
        $ids_mikrotik .= $key == 0 ? $value : "," . $value;
      }
      /* $nombre = $this->request->getPost('nombre');
      $descripcion = $this->request->getPost('descripcion');
      $precio = $this->request->getPost('precio');
      $impuesto = $this->request->getPost('impuesto');
      $id_mikrotik= $this->request->getPost('id_mikrotik[]');
      $api_mk = $this->request->getPost('api_mk');
      $descarga = $this->request->getPost('descarga');
      $subida = $this->request->getPost('subida');
      $limitat = $this->request->getPost('limitat');
      $blimit = $this->request->getPost('blimit');
      $bthreshold = $this->request->getPost('bthreshold');
      $btime = $this->request->getPost('btime');
      $prioridad = $this->request->getPost('prioridad');
      $lista = $this->request->getPost('lista');
      $profile = $this->request->getPost('profile');
      $lista_pcq = $this->request->getPost('lista_pcq'); */
      $data_new = [
        'plan' => $nombre,
        'descripcion' => $descripcion,
        'velocidad' => $subida . "K/" . $descarga . "K",
        'costo' => $precio,
        'costo_prepago' => $precioPrepago==""?null:$precioPrepago,
        'profile' => $profile,
        'lista' => $lista,
        'prioridad_descarga' => $prioridad_descarga,
        'pcq_descarga' => $pcq_descarga,
        'limitat_descarga' => $limitat_descarga,
        'burst_limit_descarga' => $blimit_descarga,
        'burst_threshold_descarga' => $bthreshold_descarga,
        'burst_time_descarga' => $btime_descarga,
        'prioridad_subida' => $prioridad_subida,
        'pcq_subida' => $pcq_subida,
        'limitat_subida' => $limitat_subida,
        'burst_limit_subida' => $blimit_subida,
        'burst_threshold_subida' => $bthreshold_subida,
        'burst_time_subida' => $btime_subida,
        'impuesto' => $impuesto,
        'api' => $api_mk,
        'id_mikrotik' => $ids_mikrotik,
      ];
      $result_insert = $this->model_servicios->new_plan($data_new);
      if ($result_insert) {
        $txt_log = "Nuevo perfil creado ($nombre) ID: " . $result_insert;
        $this->model_log->insert_log(000000, "perfiles", $txt_log, $_SESSION['id_usuario8291']);
      }
      echo $result_insert ? 1 : 2;
    }
  }
  public function voz()
  {
    $permiso = $this->seguridad->Acceso_full('VOZ', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $data['permiso'] = $permiso;
      return view('servicios/voz', $data);
    }
  }
  public function get_servicios_voz()
  {
    $permiso = $this->seguridad->Acceso('VOZ', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $servicios_voz = $this->model_servicios->get_servicios_voz();
      if ($servicios_voz) {
        foreach ($servicios_voz as $value) {
          $activos = $value->activo;
          $id = $value->id;
          $eliminar = "";
          if ($permiso >= 3) {
            $eliminar = "<a data-toggle=\"tooltip\" title=\"Eliminar\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"delete_voip('$id','$activos')\">
              <i class=\"far fa-trash-alt\" aria-hidden=\"true\"></i>
            </a>";
          }


          $value->costo = "MX$" . $value->costo;
          $value->tool = "
          <td class=\" text-center\">
            <a onclick=\"getmodal('servicios/modal_edit_perfil_voip?id=$id','Editar servicio','md');\" data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\"  data-original-title=\"Editar\">
              <i class=\"far fa-edit\"></i>
            </a>
            $eliminar
          </td>
          ";
        }
        $data['data'] = $servicios_voz;
      } else {
        $data['data'] = array();
      }
      return json_encode($data);
    }
  }
  public function delete_perfil_voip()
  {
    if ($this->seguridad->Acceso_full('VOZ', 'ELIMINAR', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getPost('id');
      $result_delete = $this->model_servicios->delete_perfil_voip($id);
      echo $result_delete ? 1 : 2;
    }
  }
  public function modal_new_perfil_voip()
  {
    if ($this->seguridad->Acceso('VOZ', 'LECTURA', $_SESSION['id_usuario8291'])) {
      return view('servicios/utils/modal_new_perfil_voip');
    }
  }
  public function save_new_perfil_voip()
  {
    if ($this->seguridad->Acceso('VOZ', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $plan = $this->request->getPost('voip[plan]');
      $costo = $this->request->getPost('voip[costo]');
      $impuesto = $this->request->getPost('voip[impuesto]');
      $minutos = $this->request->getPost('voip[minutos]');
      $valorfijo = $this->request->getPost('voip[valorfijo]');
      $valormovil = $this->request->getPost('voip[valormovil]');
      $descripcion = $this->request->getPost('voip[descripcion]');
      $notas = $this->request->getPost('voip[notas]');
      $data_new = [
        'plan' => $plan,
        'minutos' => $minutos,
        'valormovil' => $valormovil,
        'valorfijo' => $valorfijo,
        'costo' => $costo,
        'descripcion' => $descripcion,
        'notas' => $notas,
        'impuesto' => $impuesto,
      ];
      $result_insert = $this->model_servicios->new_perfil_voip($data_new);
      echo $result_insert ? 1 : 2;
    }
  }
  public function modal_edit_perfil_voip()
  {
    $permiso = $this->seguridad->Acceso('VOZ', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getGet('id');
      $perfil = $this->model_servicios->get_perfil_voip($id);
      if ($perfil) {
        $data['perfil'] = $perfil;
        $data['permiso'] = $permiso;
        return view('servicios/utils/modal_edit_perfil_voip', $data);
      } else {
        echo "No existe el servicio seleccionado.";
      }
    }
  }
  public function save_edit_perfil_voip()
  {
    if ($this->seguridad->Acceso('VOZ', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getPost('id');
      $plan = $this->request->getPost('voip[plan]');
      $costo = $this->request->getPost('voip[costo]');
      $impuesto = $this->request->getPost('voip[impuesto]');
      $minutos = $this->request->getPost('voip[minutos]');
      $valorfijo = $this->request->getPost('voip[valorfijo]');
      $valormovil = $this->request->getPost('voip[valormovil]');
      $descripcion = $this->request->getPost('voip[descripcion]');
      $notas = $this->request->getPost('voip[notas]');
      $data_update = [
        'plan' => $plan,
        'minutos' => $minutos,
        'valormovil' => $valormovil,
        'valorfijo' => $valorfijo,
        'costo' => $costo,
        'descripcion' => $descripcion,
        'notas' => $notas,
        'impuesto' => $impuesto,
      ];
      $result_update = $this->model_servicios->update_perfil_voip($id, $data_update);
      echo $result_update ? 1 : 2;
    }
  }
  public function personalizado()
  {
    $permiso = $this->seguridad->Acceso('PERSONALIZADO', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $data['permiso'] = $permiso;
      return view('servicios/personalizado', $data);
    }
  }
  public function get_personalizado()
  {
    $permiso = $this->seguridad->Acceso_full('PERSONALIZADO', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $productos = $this->model_servicios->get_productos_otros();
      if ($productos) {
        foreach ($productos as $value) {
          $id = $value->id;
          $count = $value->activo;
          $eliminar = "";
          if ($permiso == 3) {
            $eliminar = "<a onclick=\"delete_otro('$id','$count')\" data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" data-original-title=\"Eliminar\">
              <i class=\"far fa-trash-alt\" aria-hidden=\"true\"></i>
            </a>";
          }

          $value->costo = 'MX$' . $value->costo;
          $value->tool = "
          <td class=\" text-center\">
            <a onclick=\"getmodal('servicios/modal_edit_personalizado?id=$id','Editar servicio','md');\" data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" data-original-title=\"Editar\">
              <i class=\"far fa-edit\"></i>
            </a>
            $eliminar
          </td>
          ";
        }
        $data['data'] = $productos;
      } else {
        $data['data'] = array();
      }
      return json_encode($data);
    }
  }
  public function modal_new_personalizado()
  {
    if ($this->seguridad->Acceso('PERSONALIZADO', 'LECTURA', $_SESSION['id_usuario8291'])) {
      return view('servicios/utils/modal_new_personalizado');
    }
  }
  public function save_new_personalizado()
  {
    if ($this->seguridad->Acceso('PERSONALIZADO', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $tipo = $this->request->getPost('otro[tipo]');
      $producto = $this->request->getPost('otro[producto]');
      $costo = $this->request->getPost('otro[costo]');
      $ric = $this->request->getPost('new_price');
      $impuesto = $this->request->getPost('otro[impuesto]');
      $clave_invoice = $this->request->getPost('otro[clave_invoice]');
      $descripcion = $this->request->getPost('otro[descripcion]');
      $data_new = [
        'producto' => $producto,
        'descripcion' => $descripcion,
        'costo' => $costo,
        'tipo' => $tipo,
        'impuesto' => $impuesto,
        'clave_invoice' => $clave_invoice,
      ];
      $result_new = $this->model_servicios->new_personalizado($data_new);
      echo $result_new ? 1 : 2;
    }
  }
  public function modal_edit_personalizado()
  {
    $permiso = $this->seguridad->Acceso('PERSONALIZADO', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $id = $this->request->getGet('id');
      $personalizado = $this->model_servicios->get_personaliado($id);
      if ($personalizado) {
        $data['personalizado'] = $personalizado;
        $data['permiso'] = $permiso;
        return view('servicios/utils/modal_editar_personalizado', $data);
      } else {
        echo "No se encontro el servicio o fue eliminado";
      }
    }
  }
  public function save_edit_personalizado()
  {
    if ($this->seguridad->Acceso('PERSONALIZADO', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getPost('id');
      $tipo = $this->request->getPost('otro[tipo]');
      $producto = $this->request->getPost('otro[producto]');
      $costo = $this->request->getPost('otro[costo]');
      $impuesto = $this->request->getPost('otro[impuesto]');
      $clave_invoice = $this->request->getPost('otro[clave_invoice]');
      $descripcion = $this->request->getPost('otro[descripcion]');
      $data_update = [
        'producto' => $producto,
        'descripcion' => $descripcion,
        'costo' => $costo,
        'tipo' => $tipo,
        'impuesto' => $impuesto,
        'clave_invoice' => $clave_invoice,
      ];
      $result_update = $this->model_servicios->update_personalizado($id, $data_update);
      echo $result_update ? 1 : 2;
    }
  }
  public function delete_personalizado()
  {
    if ($this->seguridad->Acceso_full('PERSONALIZADO', 'ELIMINAR', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getPost('id');
      $result_delete = $this->model_servicios->delete_personalizado($id);
      echo $result_delete ? 1 : 2;
    }
  }
}
