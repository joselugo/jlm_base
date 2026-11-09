<?php

namespace App\Controllers;



require 'vendor/autoload.php';

use App\Libraries\Class_seguridad;
use App\Models\Model_almacen;
use App\Models\Model_log;
use App\Models\Model_selectores;
use App\Libraries\Class_permiso_sucursal;
use App\Models\Model_configuracion;


class Almacen extends BaseController
{
  public function __construct()
  {
    $this->seguridad = new Class_seguridad();
    $this->almacen = new Model_almacen();
    $this->model_log = new Model_log();
    $this->model_configuracion = new Model_configuracion();
    $this->model_selectores = new Model_selectores();
    $this->permiso_sucursal = new Class_permiso_sucursal();
    date_default_timezone_set('America/Mexico_City');
    session();
  }

  //----------------------Proveedores ------------------------------
  public function proveedores_tools()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('PROVEEDORES'));
      if ($tipoacceso > 0) {
        $data['permiso']= $tipoacceso;
        return view('almacen/proveedores',$data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
  public function get_proveedores()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('PROVEEDORES'));
      if ($tipoacceso > 0) {
        $arrayData = array();
        $all_items = $this->almacen->get_proveedores();
        $tools = "";
        foreach ($all_items as $item) {
          $tools = '<td class=" text-center"><button onclick="update_proveedor(\'' . $item->id . '\',\'' . $item->proveedor . '\',\'' . $item->mail . '\',\'' . $item->telefono . '\',\'' . $item->direccion . '\',\'' . $_SESSION['token8291'] . '\')" id=' . $item->id . '  data-toggle="modal" title="Editar" data-target="#add" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></button>';
          if ($tipoacceso>=3) {
            $tools .= '<a data-toggle="tooltip" title="Eliminar"  class="btn btn-default btn-icon btn-sm update" onclick="delete_proveedor(\'' . $item->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $item->proveedor . '\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a>';
          }
          $tools.= '</td>';
          $array_item = [
            "id" => $item->id,
            "proveedor" => $item->proveedor,
            "email" => $item->mail,
            "telefono" => $item->telefono,
            "direccion" => $item->direccion,
            "tool" => $tools
          ];
          array_push($arrayData, $array_item);
        }
        $data['data'] = $arrayData;
        echo json_encode($data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
  public function new_proveedor()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('PROVEEDORES'));
      if ($tipoacceso >= 2) {
        $proveedor = $this->request->getPost('proveedor');
        $email = $this->request->getPost('email');
        $telefono = $this->request->getPost('telefono');
        $direccion = $this->request->getPost('direccion');
        $data = [
          'proveedor' => $proveedor,
          'mail' => $email,
          'telefono' => $telefono,
          'direccion' => $direccion
        ];
        $result = $this->almacen->create_proveedor($data);
        echo $result ?  1 : 2;
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
  public function update_proveedor()
  {
    $token = $this->request->getPost('token');
    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('PROVEEDORES'));
      if ($tipoacceso >= 2) {
        $id = $this->request->getPost('id');
        $proveedor = $this->request->getPost('proveedor');
        $email = $this->request->getPost('email');
        $telefono = $this->request->getPost('telefono');
        $direccion = $this->request->getPost('direccion');
        $data = [
          'proveedor' => $proveedor,
          'mail' => $email,
          'telefono' => $telefono,
          'direccion' => $direccion
        ];
        $result = $this->almacen->update_proveedor($id, $data);
        if ($result) {
          $insert_log = $this->model_log->insert_log("000000", "proveedor", "Proveedor editado <b>#" . $id . " " . $proveedor . "</b>", $_SESSION['id_usuario8291']);
        }
        echo $result ?  1 : 2;
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
  public function delete_proveedor()
  {
    $token = $this->request->getPost('token');
    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('PROVEEDORES'));
      if ($tipoacceso >= 2) {
        $id = $this->request->getPost('id');
        //$session = $this->request->getPost('session');
        $nombre = $this->request->getPost('nombre');
        $result = $this->almacen->delete_proveedor($id);
        if ($result) {
          $insert_log = $this->model_log->insert_log("000000", "proveedor", "Proveedor eliminado <b>#" . $id . " " . $nombre . "</b>", $_SESSION['id_usuario8291']);
        }
        echo $result ?  1 : 2;
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
  //----------------------Tipos productos ------------------------------
  public function tipo_productos_tools()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('TIPOS_DE_PRODUCTOS'));
      $tipoacceso2 = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('AJUSTES_ALMACEN'));
      if ($tipoacceso > 0) {
        $data['unidad'] = $this->model_selectores->get_all_unidad_medida_tools();
        $data['categorias'] = $this->model_selectores->get_all_categorias_tools();
        $data['permiso']=$tipoacceso;
        $data['almacen_permiso']=$tipoacceso2;
        return view('almacen/tipos_producto', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
  public function get_tipos_producto()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('TIPOS_DE_PRODUCTOS'));
      if ($tipoacceso > 0) {
        $ids = array(120);
        $config = $this->model_configuracion->get_configuracion_name($ids);
        $parametros= explode(';',$config["parametros_almacen"]);
        $permiso_sucursal_almacen = $this->permiso_sucursal->get_permiso_sucursal_almacen($_SESSION['sucursales']);
        $arrayData = array();
        $all_items = $this->almacen->get_tipos_producto($permiso_sucursal_almacen);
        $tools = "";
        foreach ($all_items as $item) {
          if (
            $item->tipo == "producto" or
            $item->tipo == "accesorio"
          ) {
            /* 120 */
            if ($item->disponibles == null) $item->disponibles =0;
            if ($item->medida == null) $item->medida = "unidades";
            if ($item->disponibles<$parametros[0]){
               $css= $parametros[1];
              }elseif($item->disponibles>=$parametros[2] and $item->disponibles<= $parametros[4]) {
                $css= $parametros[3];
              }elseif ($item->disponibles>$parametros[4]){
                $css= $parametros[5];
              }else{
                $css= "light";
              }
            $disponible = '<span class="badge badge-'.$css.'">' . $item->disponibles . ' ' . ucfirst($item->medida) . '</span>';
            $tools = '<td class=" text-center"><button onclick="update_tipo_producto(this,\'' . $item->id . '\',\'' . $item->tipo . '\',\'' . $item->impuesto . '\',\'' . $item->clave . '\',\'' . $item->medida . '\',\'' . $_SESSION['token8291'] . '\')" id=' . $item->id . '  data-toggle="modal" title="Editar" data-target="#modaltmp" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></button>';
            if ($tipoacceso>=2) {
              $tools .= '<a data-toggle="tooltip" title="Eliminar"  class="btn btn-default btn-icon btn-sm update buttonu" onclick="delete_tipo_producto(\'' . $item->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $item->producto . '\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a>';
            }
            $tools.='</td>';
            $array_item = [
              "id" => $item->id,
              "producto" => $item->producto,
              "descripcion" => $item->descripcion,
              "tipo" => $item->tipo,
              "impuesto" => $item->impuesto,
              "disponibles" => $disponible,
              "tool" => $tools
            ];
            array_push($arrayData, $array_item);
          }
        }
        $data['data'] = $arrayData;
        echo json_encode($data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
  public function new_tipo_producto()
  { {
      if (isset($_SESSION['id_usuario8291'])) {
        $seguridad = new Class_seguridad();
        $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('TIPOS_DE_PRODUCTOS'));
        if ($tipoacceso >= 2) {
          $tipo = $this->request->getPost('tipo');
          $producto = $this->request->getPost('producto');
          $impuesto = $this->request->getPost('impuesto');
          $clave = $this->request->getPost('clave');
          $medida = $this->request->getPost('medida');
          $descripcion = $this->request->getPost('descripcion');
          $data = [
            'tipo' => $tipo,
            'producto' => $producto,
            'impuesto' => $impuesto,
            'clave_invoice	' => $clave,
            'medida' => $medida,
            'descripcion' => $descripcion
          ];
          $result = $this->almacen->create_new_tipo_producto($data);
          echo $result ?  1 : 2;
        } else echo acceso_denegado();
      } else $this->seguridad->login_back();
    }
  }
  public function delete_tipo_producto()
  {
    $token = $this->request->getPost('token');
    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('TIPOS_DE_PRODUCTOS'));
      if ($tipoacceso >= 2) {
        $id = $this->request->getPost('id');
        //$session = $this->request->getPost('session');
        $nombre = $this->request->getPost('nombre');
        $result = $this->almacen->delete_tipo_producto($id);
        if ($result) {
          $insert_log = $this->model_log->insert_log("000000", "proveedor", "Proveedor eliminado <b>#" . $id . " " . $nombre . "</b>", $_SESSION['id_usuario8291']);
        }
        echo $result ?  1 : 2;
      } else echo "error";
    } else $this->seguridad->login_back();
  }
  public function update_tipo_producto()
  {
    $token = $this->request->getPost('token');
    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('TIPOS_DE_PRODUCTOS'));
      if ($tipoacceso >= 2) {
        $id = $this->request->getPost('id');
        $tipo = $this->request->getPost('tipo');
        $producto = $this->request->getPost('producto');
        $impuesto = $this->request->getPost('impuesto');
        $clave = $this->request->getPost('clave');
        $medida = $this->request->getPost('medida');
        $descripcion = $this->request->getPost('descripcion');
        $data = [
          'tipo' => $tipo,
          'producto' => $producto,
          'impuesto' => $impuesto,
          'clave_invoice	' => $clave,
          'medida' => $medida,
          'descripcion' => $descripcion
        ];
        $result = $this->almacen->update_tipo_producto($id, $data);
        if ($result) {
          $insert_log = $this->model_log->insert_log("000000", "Categoria de producto", "Categoria de producto editado <b>#" . $id . " " . $producto . "</b>", $_SESSION['id_usuario8291']);
        }
        echo $result ?  1 : 2;
      } else echo "error";
    } else $this->seguridad->login_back();
  }
  //----------------------Productos ------------------------------
  public function productos_tools()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal_select($_SESSION['sucursales']);
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('PRODUCTOS'));
      $tipoacceso2 = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('AJUSTES_ALMACEN'));
      if ($tipoacceso > 0) {
        $select_categorias = $this->model_selectores->get_all_categorias_productos_tools();
        $select_categorias_accesorios = $this->model_selectores->get_all_categorias_productos_accesorios_tools();
        $select_proveedores = $this->model_selectores->get_all_proveedores_productos_tools();
        $select_sucursales = $this->model_selectores->get_all_sucursales_by_user($permiso_sucursal);
        $data['select_categorias'] = $select_categorias;
        $data['permiso'] = $tipoacceso;
        $data['almacen_permiso'] = $tipoacceso2;
        $data['select_categorias_accesorios'] = $select_categorias_accesorios;
        $data['select_proveedores'] = $select_proveedores;
        $data['select_sucursales'] = $select_sucursales;
        return view('almacen/productos', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
  public function get_productos()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $permiso_sucursal_almacen = $this->permiso_sucursal->get_permiso_sucursal_almacen($_SESSION['sucursales']);
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('PRODUCTOS'));
      if ($tipoacceso > 0) {
        $estado = $this->request->getGet('estado');
        if ($estado == 0) {
          $estado = "";
        }
        if ($estado == 1) {
          $estado = "no disponible";
        }
        if ($estado == 2) {
          $estado = "disponible";
        }
        if ($estado == 3) {
          $estado = "comodato";
        }
        $arrayData = array();
        $all_items = $this->almacen->get_productos($estado, $permiso_sucursal_almacen);
        /* $all_items_agrupado = $this->almacen->get_productos_agrupado(); */
        $categorias = "";
        foreach ($all_items as $item) {
          $tools = "";
          $eliminar = "";
          $estado = strtoupper($item->estado);
          if ($estado == "DISPONIBLE") {
            $estado = "<span class=\"label label-success\">$estado</span>";
          }
          if ($estado == "NO DISPONIBLE") {
            $estado = "<span class=\"label label-danger\">$estado</span>";
          }
          if ($estado == "COMODATO") {
            $estado = "<span class=\"label label-warning\">$estado</span>";
          }
          if ($tipoacceso >= 3) {
            $eliminar = "<a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"delete_producto($item->id,'$item->nombre')\" data-original-title=\"Eliminar\">
                            <i class=\"far fa-trash-alt\" aria-hidden=\"true\"></i>
                          </a>";
          }
          if ($item->userid == 000000) {
            $tools = "<td>
                        <button data-toggle=\"tooltip\" data-toggle=\"modal\" data-target=\"#modaltmp\" class=\"btn btn-default btn-icon btn-sm\" data-original-title=\"Editar\" onclick=\"update_producto($item->id,$item->usuario);\">
                          <i class=\"far fa-edit\"></i>
                        </button>
                        $eliminar
                      </td>";
          } else {
            if ($tipoacceso >= 2) {
              $tools = "<a data-toggle=\"tooltip\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"retira_prod($item->id,'$item->usuario')\" data-original-title=\"Retirar del cliente (vuelve a estar disponible)\">
                                      <i class=\"fas fa-user-times\"></i>
                                    </a>";
            }
          }
          /* $tools = '<td class=" text-center"><button onclick="update_proveedor(\'' . $item->id . '\',\'' . $item->proveedor . '\',\'' . $item->mail . '\',\'' . $item->telefono . '\',\'' . $item->direccion . '\',\'' . $_SESSION['token8291'] . '\')" id=' . $item->id . '  data-toggle="modal" title="Editar" data-target="#add" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></button>';
          $tools .= '<a data-toggle="tooltip" title="Eliminar"  class="btn btn-default btn-icon btn-sm update" onclick="delete_proveedor(\'' . $item->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $item->proveedor . '\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a></td>'; */
          $array_item = [
            "id" => $item->id,
            "nombre" => $item->nombre,
            "proveedor" => $item->proveedor,
            "serial_producto" => $item->serial_producto,
            "mac_producto" => $item->mac_producto,
            "costo" => "MX$" . $item->costo,
            "estado" => $estado,
            "tool" => $tools,
            "usuario" => $item->usuario,
            "fecha_ingreso" => $item->fecha_ingreso,
            "fecha_salida" => $item->fecha_salida,

          ];
          array_push($arrayData, $array_item);
        }
        $data['data'] = $arrayData;
        $data['categorias'] = $categorias;
        echo json_encode($data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
  public function new_producto()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('PRODUCTOS'));
      if ($tipoacceso >= 2) {
        $categoria = $this->request->getPost('categoria');
        $date = date("Y/m/d");
        $cantidad = $this->request->getPost('cantidad');
        $idproveedor = $this->request->getPost('idproveedor');
        $costo = $this->request->getPost('costo');
        $mac = $this->request->getPost('mac');
        $serial = $this->request->getPost('serial');
        $sucursal = $this->request->getPost('sucursal');
        $data = [
          'fecha_ingreso' => $date,
          'idproveedor' => $idproveedor,
          'productoid' => $categoria,
          'costo' => $costo,
          'mac_producto' => $mac,
          'serial_producto' => $serial,
          'sucursal' => $sucursal
        ];

        $result = $this->almacen->create_producto($data);
        echo $result ?  1 : 2;
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
  public function new_producto_accesorio()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('PRODUCTOS'));
      if ($tipoacceso >= 2) {
        $costo = $this->request->getPost('costo');
        $sucursal = $this->request->getPost('sucursal');
        $categoria = $this->request->getPost('categoria');
        $cantidad = $this->request->getPost('cantidad');
        $date = $this->request->getPost('date');
        $date = date("Y/m/d", strtotime($date));
        $data = [
          'fecha_ingreso' => $date,
          'catid' => 'accesorio',
          'productoid' => $categoria,
          'costo' => $costo,
          'cantidad' => $cantidad,
          'sucursal' => $sucursal
        ];
        $result = $this->almacen->create_producto($data);
        echo $result ?  1 : 2;
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
  public function retira_prod()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('PRODUCTOS'));
      if ($tipoacceso >= 2) {
        $id = $this->request->getPost('id');
        $nombre = $this->request->getPost('nombre');
        $data = [
          'userid' => 0,
          'estado' => 'disponible'
        ];
        $result = $this->almacen->retira_prod($id, $data);
        if ($result) {
          if ($nombre != "") {
            $insert_log = $this->model_log->insert_log("000000", "producto", "Producto retirado de cliente <b>#" . $id . " del cliente " . $nombre . "</b>", $_SESSION['id_usuario8291']);
          } else {
            $insert_log = $this->model_log->insert_log("000000", "producto", "Producto retirado de cliente <b>#" . $id . "</b>", $_SESSION['id_usuario8291']);
          }
        }
        echo $result ? 1 : 2;
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
  public function get_producto()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('PRODUCTOS'));
      if ($tipoacceso > 0) {
        $id = $this->request->getPost('id');
        $item = $this->almacen->get_producto($id);
        if ($item) {
          $item[0]->permiso = $tipoacceso;
          echo json_encode($item[0]);
        } else echo 'error';
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
  public function get_producto_accesorio()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('PRODUCTOS'));
      if ($tipoacceso > 0) {
        $id = $this->request->getPost('id');
        $item = $this->almacen->get_producto_accesorio($id);
        echo $item ? json_encode($item[0]) : 'error';
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
  public function update_producto()
  {
    $token = $this->request->getPost('token');
    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('PRODUCTOS'));
      if ($tipoacceso >= 2) {
        $id = $this->request->getPost('id');
        $nombre = $this->request->getPost('nombre');
        /* $token = $this->request->getPost('token'); */
        $categoria = $this->request->getPost('categoria');
        $cantidad = $this->request->getPost('cantidad');
        $idproveedor = $this->request->getPost('idproveedor');
        $costo = $this->request->getPost('costo');
        $mac = $this->request->getPost('mac');
        $serial = $this->request->getPost('serial');
        $data = [
          'idproveedor' => $idproveedor,
          'productoid' => $categoria,
          'costo' => $costo,
          'mac_producto' => $mac,
          'serial_producto' => $serial,
        ];
        $result = $this->almacen->update_producto($id, $data);
        if ($result) {
          $insert_log = $this->model_log->insert_log("000000", "producto", "Producto editado <b>#" . $id . " de nombre " . $categoria . " del proveedor " . $idproveedor . "</b>", $_SESSION['id_usuario8291']);
        }
        echo $result ?  1 : 2;
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
  public function update_producto_accesorio()
  {
    $token = $this->request->getPost('token');
    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('PRODUCTOS'));
      if ($tipoacceso >= 2) {
        $id = $this->request->getPost('id');
        /* $token = $this->request->getPost('token'); */
        $categoria = $this->request->getPost('categoria');
        $cantidad = $this->request->getPost('cantidad');
        $costo = $this->request->getPost('costo');
        $data = [
          'costo' => $costo,
          'productoid' => $categoria,
          'cantidad' => $cantidad
        ];
        $result = $this->almacen->update_producto($id, $data);
        if ($result) {
          $insert_log = $this->model_log->insert_log("000000", "accesorio", "Accesorio editado <b>#" . $id  . "</b>", $_SESSION['id_usuario8291']);
        }
        echo $result ?  1 : 2;
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
  public function delete_producto()
  {
    $token = $this->request->getPost('token');
    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('PRODUCTOS'));
      if ($tipoacceso >= 2) {
        $id = $this->request->getPost('id');
        $nombre = $this->request->getPost('nombre');
        //$session = $this->request->getPost('session');
        $result = $this->almacen->delete_producto($id);
        if ($result) {
          $insert_log = $this->model_log->insert_log("000000", "producto", "Producto eliminado <b>#" . $id . " del nombre " . $nombre . "</b>", $_SESSION['id_usuario8291']);
        }
        echo $result ?  1 : 2;
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
  public function importar_excel()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('PRODUCTOS'));
      if ($tipoacceso >= 2) {
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(FALSE);
        $file = $this->request->getFile('imp-pro');
        $sucursal = $this->request->getPost('excel_sucursal');
        $spreadsheet = $reader->load($file);
        $worksheet = $spreadsheet->getActiveSheet();

        $highestRow = $worksheet->getHighestRow(); // e.g. 10
        $highestColumn = $worksheet->getHighestColumn(); // e.g 'F'
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5
        $array_data = array();
        $respuesta = array();
        for ($row = 2; $row <= $highestRow; ++$row) {
          $categoria = $worksheet->getCell([1, $row])->getValue();
          $serial = $worksheet->getCell([2, $row])->getValue();
          $mac = $worksheet->getCell([3, $row])->getValue();
          $costo = $worksheet->getCell([4, $row])->getFormattedValue();
          $estado = $worksheet->getCell([5, $row])->getValue();
          $idcliente = $worksheet->getCell([6, $row])->getValue();
          $fechaingreso = $worksheet->getCell([7, $row])->getFormattedValue();
          if ($fechaingreso != "") {
            $fechaingreso = date("Y/m/d", strtotime($fechaingreso));
          }
          $fechaegreso = $worksheet->getCell([8, $row])->getFormattedValue();
          if ($fechaegreso != "") {
            $fechaegreso = date("Y/m/d", strtotime($fechaegreso));
          }
          $idproveedor = $worksheet->getCell([9, $row])->getValue();
          if ($estado == "1") $estado = "no disponible";
          if ($estado == "2") $estado = "disponible";
          if ($estado == "3") $estado = "comodato";
          $data = [
            'productoid' => $categoria,
            'serial_producto' => $serial,
            'mac_producto' => $mac,
            'costo' => $costo,
            'estado' => $estado,
            'userid' => $idcliente,
            'fecha_ingreso' => $fechaingreso,
            'fecha_salida' => $fechaegreso,
            'idproveedor' => $idproveedor,
            'sucursal' => $sucursal,
          ];
          array_push($array_data, $data);
        }
        $verificacion = $this->request->getPost('verificacion');
        if ($verificacion == 0) {
          $array_batch_mac = $this->unique_multidim_array($array_data, 'mac_producto', "C", "mac");
          $array_batch_serie = $this->unique_multidim_array($array_data, 'serial_producto', "B", "numero de serie");
          $array_batch_db = $this->unique_multidim_array_db($array_data, 'serial_producto', 'mac_producto', "numero de serie o mac");

          array_push($respuesta, $array_batch_mac['repetidos']);
          array_push($respuesta, $array_batch_serie['repetidos']);
          array_push($respuesta, $array_batch_db['repetidos']);

          if (count($respuesta[0]) == 0 and count($respuesta[1]) == 0 and count($respuesta[2]) == 0) {
            echo 0;
          } else {
            echo json_encode($respuesta);
          }
        } else {
          $result = $this->almacen->insert_batch_productos($array_data);
          echo $result ?  1 : 2;
        }

        /* if ($result) {
          $insert_log = $this->model_log->insert_log("000000", "producto", "Producto eliminado <b>#" . $id . " del nombre " . $nombre . "</b>", $_SESSION['id_usuario8291']); 
        } */
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }
  public function get_accesorios()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $permiso_sucursal_almacen = $this->permiso_sucursal->get_permiso_sucursal_almacen($_SESSION['sucursales']);
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('PRODUCTOS'));
      if ($tipoacceso > 0) {

        $arrayData = array();
        $all_items = $this->almacen->get_accesorios($permiso_sucursal_almacen);
        $ids = array(122);
        $config = $this->model_configuracion->get_configuracion_name($ids);
        $parametros = explode(';', $config["parametros_almacen_accesorios"]);

        foreach ($all_items as $item) {
          $eliminar="";
          if ($tipoacceso>=3) {
            $eliminar="<a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=delete_producto($item->id,$item->id) data-original-title=\"Eliminar\">
                      <i class=\"far fa-trash-alt\" aria-hidden=\"true\"></i>
                    </a>";
          }
          $tools = "<td>
                    <a data-toggle=\"tooltip\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"update_producto($item->id,$item->id)\">
                      <i class=\"far fa-edit\"></i>
                    </a>
                    $eliminar
                  </td>";
          if ($item->cantidad < $parametros[0]) {
            $css = $parametros[1];
          } elseif ($item->cantidad >= $parametros[2] and $item->cantidad <= $parametros[4]) {
            $css = $parametros[3];
          } elseif ($item->cantidad > $parametros[4]) {
            $css = $parametros[5];
          } else {
            $css = "light";
          }
          $array_item = [
            "id" => $item->id,
            "proveedor" => $item->producto,
            "costo" => "MX$" . $item->costo,
            "cantidad" => "<span class=\"badge badge-$css\">$item->cantidad</span>",
            "tools" => $tools,
          ];
          array_push($arrayData, $array_item);
        }
        $data['data'] = $arrayData;
        echo json_encode($data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }



  //funciones
  public function unique_multidim_array($array, $key, $columna, $texto)
  {

    $temp_array_repetidos = array();
    $temp_array = array();
    $i = 0;
    $index = 0;
    $key_array = array();

    foreach ($array as $val) {
      if (!in_array($val[$key], $key_array)) {
        $key_array[$i] = $val[$key];
        $temp_array[$i] = $val;
      } else {
        array_push($val, "Fila " . ($i + 2) . " celda " . $columna . ($i + 2) . " -> $texto repetida");
        $temp_array_repetidos[$index] = $val;
        $index++;
      }
      $i++;
    }
    $data["repetidos"] = $temp_array_repetidos;
    $data["insert"] = $temp_array;
    return $data;
  }
  public function unique_multidim_array_db($array, $key, $key2, $texto)
  {
    $data = array();
    $temp_array_repetidos = array();
    $temp_array = array();
    $i = 0;
    $index = 0;
    $key_array = array();

    foreach ($array as $val) {
      $result = $this->almacen->consulta_existencia_almacen($val[$key], $val[$key2]);
      if (!$result) {
        $key_array[$i] = $val[$key];
        $temp_array[$i] = $val;
      } else {
        array_push($val, "Fila " . ($i + 2) . "-> $texto existe en la base de datos");
        $temp_array_repetidos[$index] = $val;
        $index++;
      }
      $i++;
    }
    $data["repetidos"] = $temp_array_repetidos;
    $data["insert"] = $temp_array;
    return  $data;;
  }
}
