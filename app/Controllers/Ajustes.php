<?php

namespace App\Controllers;


use App\Libraries\Class_seguridad;

use App\Models\Model_sms;
use App\Models\Model_sucursal;
use App\Models\Model_log;
use App\Models\Model_ajustes;
use App\Models\Model_configuracion;
use App\Models\Model_selectores;
use SMSGatewayMe\Client\ApiClient;
use SMSGatewayMe\Client\Configuration;
use SMSGatewayMe\Client\Api\DeviceApi;

class Ajustes extends BaseController
{
  /** Peso máximo de los logos de sucursal (KB). */
  private const LOGO_MAX_KB = 2048;

  var $model_log2;
  var $model_ajustes;
  var $model_selectores;
  var $model_configuracion;
  protected Class_seguridad $seguridad;
  protected Model_sucursal $model_sucursal;
  protected Model_log $model_log;
  protected Model_sms $model_sms;


  public function __construct()
  {
    $this->seguridad = new Class_seguridad();
    $this->model_sucursal = new Model_sucursal();
    $this->model_log = new Model_log();
    $this->model_log2 = new Model_log();
    $this->model_ajustes = new Model_ajustes();
    $this->model_selectores = new Model_selectores();
    $this->model_sms = new Model_sms();
    $this->model_configuracion = new Model_configuracion();
    date_default_timezone_set('America/Mexico_City');
    session();
  }

  public function index()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('AJUSTES'));
      if ($tipoacceso >= 1) {
        echo view('ajustes/index', ['puede' => $this->secciones_visibles()]);
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }

  /**
   * Secciones de Ajustes que el usuario puede abrir (al menos lectura).
   * El permiso "Ajustes" solo da acceso a esta pantalla; cada sección tiene el suyo.
   *
   * @return array<string, bool> clave del permiso => puede
   */
  private function secciones_visibles(): array
  {
    $claves = ['AJUSTES', 'GENERAL', 'GESTION_PERSONAL', 'CONFIG_FACTURACION', 'PASARELAS_PAGO', 'EDITOR_PLANTILLAS',
      'TICKETS', 'CAMBIOS_MASIVOS', 'ZONAS', 'SMS', 'CLOUD', 'GOOGLE', 'CREATE_BACKUP', 'LOG', 'SYSTEM', 'RUTAS',
      'SUCURSAL', 'AJUSTES_ALMACEN'];
    $puede = [];
    foreach ($claves as $clave) {
      $puede[$clave] = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso($clave)) >= 1;
    }

    return $puede;
  }

  public function tickets()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('TICKETS'));
      if ($tipoacceso >= 1) {
        echo view('ajustes/tickets');
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }

  public function get_departaments_tickets_json()
  {
    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('TICKETS'));
      if ($tipoacceso >= 1) {

        $tickets = $this->model_ticket->get_departaments_tickets_json();

        $array_departamentos = array();
        $tool = "";

        if ($tickets) {
          foreach ($tickets as $items) {
            if ($tipoacceso == 1) {
              $tool = "";
            } else if ($tipoacceso == 2) {
              $tool = '<td class=" text-center">
                            <a onclick="view_ticket(\'' . $items->id . '\');get_all_tickets_client(\'' . $items->idcliente . '\');" data-toggle="tooltip" title="Ver ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                            <a class="btn btn-default btn-icon btn-sm" role="button"  data-trigger="click" data-ajaxpoputo="ticket/popover?id=' . $items->id . '"><i class="far fa-eye"></i></a>
                            <a onclick="close_ticket(\'' . $items->id . '\')" data-toggle="tooltip" title="Cerrar ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-times-circle"></i></a></td>';
            } else if ($tipoacceso == 3) {
              $tool = '<td class=" text-center">
                            <a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_ticket(\'' . $items->id . '\',\'' . $items->asunto . '\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
                            <a onclick="view_ticket(\'' . $items->id . '\');get_all_tickets_client(\'' . $items->idcliente . '\');" data-toggle="tooltip" title="Ver ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-edit"></i></a>
                            <a class="btn btn-default btn-icon btn-sm" role="button"  data-trigger="click" data-ajaxpoputo="ticket/popover?id=' . $items->id . '"><i class="far fa-eye"></i></a>
                            <a onclick="close_ticket(\'' . $items->id . '\')" data-toggle="tooltip" title="Cerrar ticket" class="btn btn-default btn-icon btn-sm"><i class="far fa-times-circle"></i></a></td>';
            }

            $array_items = array(
              "id" => $items->id,
              "departamento" => '<span style="color:' . $items->color . ';border: 1px solid ' . $items->color . ';padding: 2px 5px;border-radius: 5px;text-transform: uppercase;font-size: 9.5px;font-weight: 700;">' . strtoupper($items->departamento) . '</span>',
              "remitente" => $items->remitente,
              "asunto" => $items->asunto,
              "tecnico" => $items->tecnico,
              "fecha" => date('d/m/Y - h:i a', strtotime($items->fecha)),
              "ubicacion" => $items->ubicacion,
              "operador" => $items->operador,
              "respuesta" => $items->respuesta,
              "tool" => $tool
            );
            array_push($array_departamentos, $array_items);
          }
        }
        echo '{"data":' . json_encode($array_departamentos) . '}';
      } else {
        echo acceso_denegado();
      }
    } else {
      $this->seguridad->login_back();
    }
  }
  public function ajuste_sucursal()
  {
    if ($this->seguridad->Acceso('SUCURSAL', 'LECTURA', $_SESSION['id_usuario8291'])) {
      return view("ajustes/sucursal/ajuste_sucursal");
    }
  }
  public function get_sucursales()
  {
    $permiso = $this->seguridad->Acceso('SUCURSAL', 'LECTURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $sucursales = $this->model_sucursal->get_sucursales();
      if ($sucursales) {
        foreach ($sucursales as $sucursal) {
          if ($permiso >= 2) {
            $idsucursal = $sucursal->id;
            $sucursal->tool = "
            <td class=\" text-center\">
              <a class=\"btn btn-default btn-icon btn-sm\" href=\"#ajustes/editar_sucursal?idsucursal=$idsucursal\">
                <i class=\"far fa-edit\" aria-hidden=\"true\"></i>
              </a>";
            if ($permiso>=2) {
              $sucursal->tool.="<a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"delete_sucursal('$idsucursal')\" data-original-title=\"Eliminar\">
                <i class=\"far fa-trash-alt\" aria-hidden=\"true\"></i>
              </a>
            </td>";
            }else{
              $sucursal->tool .= "</td>";
            }
          } else {
            $sucursal->tool = "";
          }
          $sucursal->direccion == "" ? $sucursal->direccion = "N/A" : null;
          $sucursal->ubicacion == "" ? $sucursal->ubicacion = "N/A" : null;
        }
        $data['data'] = $sucursales;
        return json_encode($data);
      } else {
        echo "<script>alerta('warning','No se encontraron sucursales')</script>";
      }
    }
  }
  public function editar_sucursal()
  {
    if ($this->seguridad->Acceso('SUCURSAL', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $idsucursal = $this->request->getGet('idsucursal');
      $sucursal = $this->model_sucursal->get_sucursal($idsucursal);
      $nodos = $this->model_sucursal->get_sucursal_nodos($idsucursal);
      $txt_select="";
      if ($sucursal ) {
        foreach ($nodos as $key => $value) {
          $txt_select.=$key==0?"".$value->idnodo:",".$value->idnodo;
        }
        $options = $this->model_selectores->get_all_nodos();
        $data['nodos'] = $options ? $options : array();
        $data['idsucursal'] = $idsucursal;
        $data['nodos_select'] = $txt_select;
        $data['sucursal'] = $sucursal[0];
        return view("ajustes/sucursal/utils/edit_sucursal", $data);
      } else {
        return "<script>alerta('warning','No se encontro la sucursal.')</script>";
      }
    }
  }
  public function save_editar_sucursal()
  {
    if ($this->seguridad->Acceso('SUCURSAL', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $idsucursal = $this->request->getGet('idsucursal');
      $sucursal_nombre = $this->request->getPost('sucursal[nombre]');
      $sucursal_direccion = $this->request->getPost('sucursal[direccion]');
      $sucursal_ubicacion = $this->request->getPost('sucursal[ubicacion]');
      $sucursal_telefono = $this->request->getPost('sucursal[telefono]');
      $sucursal_rfc = $this->request->getPost('sucursal[rfc]');
      $alias = $this->request->getPost('sucursal[alias]');
      $sucursal_nodos = $this->request->getPost('nodos[]');
      $this->model_ajustes->delete_nodo_sucursal($idsucursal);
      foreach ($sucursal_nodos as $value) {
        $data = [
          "idnodo" => $value,
          "idsucursal" => $idsucursal
        ];

        $news_sucursales_nodo=$this->model_ajustes->save_nodo_sucursal($data);
      }

      $data_sucursal = [
        'sucursal' => $sucursal_nombre ? $sucursal_nombre : "",
        'direccion' => $sucursal_direccion ? $sucursal_direccion : "",
        'ubicacion' => $sucursal_ubicacion ? $sucursal_ubicacion : "",
        'telefono' => $sucursal_telefono ? $sucursal_telefono : "",
        'rfc' => $sucursal_rfc ? $sucursal_rfc : "",
        'alias' => $alias ? $alias : ""
      ];
      $result_save_sucursal = $this->model_sucursal->update_sucursal($idsucursal, $data_sucursal);
      if ($result_save_sucursal or $news_sucursales_nodo) {
        echo 1;
        $txt_log = "Sucursal modificada ID : $idsucursal";
        $insert_log = $this->model_log->insert_log(0, "Configuracion[sucursal]", $txt_log, $_SESSION['id_usuario8291']);
      } else {
        echo 2;
      }
    }
  }
  public function save_logo_sistema()
  {
    if ($this->seguridad->Acceso('SUCURSAL', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $idsucursal = (int) $this->request->getGet('idsucursal');
      $image = $this->request->getFile('logosistema');
      $campo = $image ? 'logosistema' : 'logofactura';
      $imagen = $image ?: $this->request->getFile('logofactura');
      $nombre = ($image ? 'logo_sistema' : 'logo_factura') . "_sucursal_$idsucursal.";
      // Máximo 2 MB y solo PNG/JPG: el logo de facturas es el que se incrusta en
      // cada PDF (facturas, recibos, hoja de instalación), uno pesado los vuelve lentos.
      $validacion = $idsucursal > 0 && $this->validate([
        $campo => "uploaded[$campo]|max_size[$campo," . self::LOGO_MAX_KB . "]|is_image[$campo]"
          . "|mime_in[$campo,image/png,image/jpeg]|ext_in[$campo,png,jpg,jpeg]",
      ], [
        $campo => [
          'uploaded' => 'No se recibió la imagen.',
          'max_size' => 'El logo no puede pesar más de 2 MB.',
          'is_image' => 'El archivo no es una imagen válida.',
          'mime_in'  => 'Solo se permiten imágenes PNG o JPG.',
          'ext_in'   => 'Solo se permiten imágenes PNG o JPG.',
        ],
      ]);
      if (!$validacion) {
        return $this->response->setStatusCode(422)->setJSON([
          'error' => $idsucursal > 0 ? implode(' ', $this->validator->getErrors()) : 'Sucursal no válida.',
        ]);
      }
      if ($validacion) {
        $archivo = $nombre . strtolower($imagen->guessExtension() ?: $imagen->getExtension());
        $url = 'public/images/logos_sucursales/' . $archivo;
        // Borra el logo anterior aunque tuviera otra extensión (.jpg -> .png).
        foreach (glob('public/images/logos_sucursales/' . $nombre . '*') ?: [] as $anterior) {
          unlink($anterior);
        }
        $imagen->move('public/images/logos_sucursales', $archivo);
        if ($image) {
          $data_sucursal = [
            'logo_principal' => $url
          ];
        } else {
          $data_sucursal = [
            'logo_facturas_recibos' => $url
          ];
        }
        $result_save_sucursal = $this->model_sucursal->update_sucursal($idsucursal, $data_sucursal);
        if ($image) {
          $txt_log = "Logo principal de sucursal modificada ID : $idsucursal";
        } else {
          $txt_log = "Logo de facturas y recibos modificada de la sucursal ID : $idsucursal";
        }
        $insert_log = $this->model_log->insert_log(0, "Configuracion[sucursal]", $txt_log, $_SESSION['id_usuario8291']);
        $data = [
          'archivo' => $archivo,
          'tipo' => $image ? 'logo_principal' : 'logo_faturas'
        ];
        return json_encode($data);
      }
    }
  }
  public function modal_mapa_sucursal()
  {
    if ($this->seguridad->Acceso('SUCURSAL', 'LECTURA', $_SESSION['id_usuario8291'])) {
      return view('ajustes/sucursal/utils/modal_mapa');
    }
  }
  public function new_sucursal()
  {
    if ($this->seguridad->Acceso('SUCURSAL', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $options=$this->model_selectores->get_all_nodos();
      $data['nodos']=$options?$options:array();
      return view("ajustes/sucursal/utils/new_sucursal",$data);
    }
  }
  public function save_new_sucursal()
  {
    if ($this->seguridad->Acceso('SUCURSAL', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $sucursal_nombre = $this->request->getPost('sucursal[nombre]');
      $alias = $this->request->getPost('sucursal[alias]');
      $sucursal_nodos = $this->request->getPost('nodos[]');
      $nodos = array();
      $data_sucursal = [
        "sucursal" => $sucursal_nombre,
        "alias" => $alias
      ];
      $idsucursal = $this->model_sucursal->new_sucursal($data_sucursal);
      if ($idsucursal) {
        foreach ($sucursal_nodos as $value) {
          $data = [
            "idnodo" => $value,
            "idsucursal" => $idsucursal,
          ];
          $this->model_ajustes->save_nodo_sucursal($data);
        }
        $txt_log = "Se agrego una nueva sucursal ID : $idsucursal";
        $insert_log = $this->model_log->insert_log(0, "facturas(categoria)", $txt_log, $_SESSION['id_usuario8291']);
        echo 1;
      } else {
        echo 2;
      }
    }
  }
  public function log()
  {
    if ($this->seguridad->Acceso('LOG', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $data['inicio'] = date('d/m/Y', strtotime("first day of this month"));
      $data['final'] = date('t/m/Y');
      return view('ajustes/log/log', $data);
    }
  }
  public function log_sistema_json()
  {
    if ($this->seguridad->Acceso('LOG', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $inicio = $this->request->getGet('inicio');
      $final = $this->request->getGet('final');
      $log = $this->model_log2->get_log_date($inicio, $final);
      if ($log) {
        foreach ($log as $value) {
          $cliente = $value->cliente ? $value->cliente : "n/a";
          $value->log = str_replace("{{cliente}}", $cliente, $value->log);
          $value->log = wordwrap($value->log, 100, "<br />", 1);
        }
      }
      $data['data'] = $log ? $log : array();
      return json_encode($data);
    }
  }
  public function log_acceso()
  {
    // Antes no revisaba ningún permiso.
    if (!$this->seguridad->Acceso_void('LOG', 'LECTURA')) {
      return $this->response->setJSON(['data' => []]);
    }
    $inicio = $this->request->getGet('inicio');
    $final = $this->request->getGet('final');
    $log = $this->model_log2->get_log_acceso($inicio, $final);
    if ($log) {
      foreach ($log as $value) {
        $style = $value->detalle == "Acceso exitoso" ? "success" : "danger";
        $value->detalle = "<span class=\"label label-$style\">$value->detalle</span>";
      }
    }
    $data['data'] = $log ? $log : array();
    return json_encode($data);
  }
  public function cloud()
  {
    if ($this->seguridad->Acceso('CLOUD', 'LECTURA', $_SESSION['id_usuario8291'])) {

      $row_dropbox = $this->model_ajustes->get_cloud('dropbox');
      if ($row_dropbox->estado == "on") {
        $estado_dropbox = '<input class="chk" type="checkbox" name="estado_dropbox" checked>';
      } else {
        $estado_dropbox = '<input class="chk" type="checkbox" name="estado_dropbox">';
      }
      if ($row_dropbox->upbackup == "on") {
        $upbackup_dropbox = '<input class="chk" type="checkbox" name="upbackup_dropbox" checked>';
      } else {
        $upbackup_dropbox = '<input class="chk" type="checkbox" name="upbackup_dropbox">';
      }

      $data['token_dropbox'] = $row_dropbox->token;
      $data['estado_dropbox'] = $estado_dropbox;
      $data['upbackup_dropbox'] = $upbackup_dropbox;
      return view('ajustes/cloud/cloud', $data);
    }
  }

  public function savecloud()
  {
    if ($this->seguridad->Acceso('CLOUD', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $token_dropbox = $this->request->getPost('token_dropbox');
      $estado_dropbox = $this->request->getPost('estado_dropbox');
      $upbackup_dropbox = $this->request->getPost('upbackup_dropbox');
      $arrayName = array('estado' => $estado_dropbox, 'token' => $token_dropbox, 'upbackup' => $upbackup_dropbox);
      $update_cloud = $this->model_ajustes->update_cloud($arrayName, 'dropbox');
      if ($upbackup_dropbox) {
        echo 'success';
      } else {
        'error';
      }
    }
  }

  public function spacedropbox()
  {
    if ($this->seguridad->Acceso('CLOUD', 'LECTURA', $_SESSION['id_usuario8291'])) {

      $row_dropbox = $this->model_ajustes->get_cloud('dropbox');
      $headers = array(
        "Authorization: Bearer $row_dropbox->token",
        "Content-Type: application/json"
      );

      $ch = curl_init('https://api.dropboxapi.com/2/users/get_space_usage');
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, "null");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $response = curl_exec($ch);
      curl_close($ch);
      $obj = json_decode($response, true);
      $keys = array_keys($obj);
      if ($keys[1] != 'error') {

        $usage = $this->formatSizeUnits($obj['used']);
        $free = $this->formatSizeUnits(($obj['allocation']['allocated']) - $obj['used']);
        $porcentaje = round($obj['used'] * 100 / $obj['allocation']['allocated'], 1);
        echo '<div class="progress" style="margin-bottom: 0px;">
           <div class="progress-bar" style="width: ' . $porcentaje . '%">
           <span class="sr-only">' . $porcentaje . '% usado</span>
           </div>
           </div>
           <small style="font-size: 10px;">Utilizado (' . $usage . ') - Libre (' . $free . ')</small>';
      } else {
        echo "Error de conexión API";
      }
    }
  }

  function formatSizeUnits($bytes)
  {
    if ($bytes >= 1073741824) {
      $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
      $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
      $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
      $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
      $bytes = $bytes . ' byte';
    } else {
      $bytes = '0 bytes';
    }

    return $bytes;
  }
  public function general()
  {
    if ($this->seguridad->Acceso('GENERAL', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $ids = array(1, 31, 32, 30, 26, 2, 8, 9, 64);
      $config = $this->model_configuracion->get_configuracion($ids);
      if ($config) {
        $data['config'] = $config;
        return view('ajustes/general/general', $data);
      } else {
        echo "error en la tabla de configuraciones general.";
      }
    }
  }
  public function view_login()
  {
    if ($this->seguridad->Acceso('GENERAL', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $ids = array(64);
      $img = $this->request->getGet('img');
      if ($img) {
        $data['img'] = $img;
      } else {
        $img_db = $this->model_configuracion->get_configuracion($ids);
        $data['img'] = $img_db[0];
      }

      return view('seguridad/view_login', $data);
    }
  }
  /**
   * Logo general del sistema (login y barra superior). Ver app/Helpers/sistema_helper.php.
   * Los logos de facturas y recibos siguen siendo por sucursal (Ajustes > Sucursal).
   */
  public function save_logo_general()
  {
    if (!$this->seguridad->Acceso('GENERAL', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      return $this->response->setStatusCode(403)->setJSON(['error' => 'No cuentas con los permisos necesarios.']);
    }
    $imagen = $this->request->getFile('logogeneral');
    $validacion = $this->validate([
      'logogeneral' => 'uploaded[logogeneral]|max_size[logogeneral,' . self::LOGO_MAX_KB . ']|is_image[logogeneral]'
        . '|mime_in[logogeneral,image/png,image/jpeg]|ext_in[logogeneral,png,jpg,jpeg]',
    ], [
      'logogeneral' => [
        'uploaded' => 'No se recibió la imagen.',
        'max_size' => 'El logo no puede pesar más de 2 MB.',
        'is_image' => 'El archivo no es una imagen válida.',
        'mime_in'  => 'Solo se permiten imágenes PNG o JPG.',
        'ext_in'   => 'Solo se permiten imágenes PNG o JPG.',
      ],
    ]);
    if (!$validacion) {
      return $this->response->setStatusCode(422)->setJSON(['error' => implode(' ', $this->validator->getErrors())]);
    }
    foreach (glob(FCPATH . 'public/images/logo_sistema.*') ?: [] as $anterior) {
      unlink($anterior);
    }
    $archivo = 'logo_sistema.' . strtolower($imagen->guessExtension() ?: $imagen->getExtension());
    $imagen->move(FCPATH . 'public/images', $archivo);
    $this->model_log->insert_log(0, 'Configuracion[general]', 'Logo general del sistema actualizado', $_SESSION['id_usuario8291']);

    return $this->response->setJSON(['url' => logo_sistema_url()]);
  }
  public function save_general()
  {
    if ($this->seguridad->Acceso('GENERAL', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $nombre_empresa = $this->request->getPost('ajuste[nombre_empresa]');
      $direccion_empresa = $this->request->getPost('ajuste[direccion_empresa]');
      $telefono_empresa = $this->request->getPost('ajuste[telefono_empresa]');
      $ruc_empresa = $this->request->getPost('ajuste[ruc_empresa]');
      $zona_horaria = $this->request->getPost('ajuste[zona_horaria]');
      $correo_backup = $this->request->getPost('ajuste[correo_backup]');
      $correo_soporte = $this->request->getPost('ajuste[correo_soporte]');
      $correo_factura = $this->request->getPost('ajuste[correo_factura]');
      $imgloginadmin = $this->request->getPost('ajuste[imgloginadmin]');
      $data = array();
      $data[] = [
        'id' => 1,
        'value' => $nombre_empresa
      ];
      $data[] = [
        'id' => 31,
        'value' => $direccion_empresa
      ];
      $data[] = [
        'id' => 32,
        'value' => $telefono_empresa
      ];
      $data[] = [
        'id' => 30,
        'value' => $ruc_empresa
      ];
      $data[] = [
        'id' => 26,
        'value' => $zona_horaria
      ];
      $data[] = [
        'id' => 2,
        'value' => $correo_backup
      ];
      $data[] = [
        'id' => 8,
        'value' => $correo_soporte
      ];
      $data[] = [
        'id' => 9,
        'value' => $correo_factura
      ];
      $data[] = [
        'id' => 64,
        'value' => $imgloginadmin
      ];
      $update = $this->model_configuracion->update_batch($data, 'id');
      if ($update) {
        $txt_log = "Modificacion de los ajustes generales del SISTEMA";
        $insert_log = $this->model_log->insert_log(000000, "ajustes(general)", $txt_log, $_SESSION['id_usuario8291']);
      }
      echo $update ? 1 : 2;
    }
  }

  public function facturacion()
  {
    if ($this->seguridad->Acceso('CONFIG_FACTURACION', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $ids = array(29, 62, 19, 12, 38, 34, 54, 119, 35, 90, 43, 45, 63, 53, 55, 80, 107);
      $config = $this->model_configuracion->get_configuracion_name($ids);
      if ($config) {
        $data['config'] = $config;
        return view('ajustes/facturacion/facturacion', $data);
      } else {
        echo "error en la tabla de configuraciones general.";
      }
    }
  }
  public function save_config_facturacion()
  {
    if ($this->seguridad->Acceso('CONFIG_FACTURACION', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $log = $this->request->getPost('log');
      $moneda_letra = $this->request->getPost('ajuste[moneda_letra]');
      $moneda_unidad = $this->request->getPost('ajuste[moneda_unidad]');
      $currency = $this->request->getPost('ajuste[currency]');
      $nfacturalegal = $this->request->getPost('ajuste[nfacturalegal]');
      $optlegal = $this->request->getPost('ajuste[optlegal]');
      $reconexion_cliente = $this->request->getPost('ajuste[reconexion_cliente]');
      $tipo_reconexion = $this->request->getPost('ajuste[tipo_reconexion]');
      $reconexion_texto = $this->request->getPost('ajuste[reconexion_texto]');
      $mora_cliente = $this->request->getPost('ajuste[mora_cliente]');
      $mora_cliente = $this->request->getPost('ajuste[mora_cliente]');
      $mora_texto = $this->request->getPost('ajuste[mora_texto]');
      $pdf_generado = $this->request->getPost('ajuste[pdf_generado]');
      $send_pagado = $this->request->getPost('ajuste[send_pagado]');
      $send_recibo = $this->request->getPost('ajuste[send_recibo]');
      $valida_pago = $this->request->getPost('ajuste[valida_pago]');
      $facturacontinua = $this->request->getPost('ajuste[facturacontinua]');
      $sms_suspendido = $this->request->getPost('ajuste[sms_suspendido]');
      $sms_alpagar = $this->request->getPost('ajuste[sms_alpagar]');
      $data = array();
      $ids = array(29, 62, 19, 12, 38, 34, 54, 119, 35, 90, 43, 45, 63, 53, 55, 80, 107);
      $data[] = [
        'id' => 29,
        'value' => $moneda_letra
      ];
      $data[] = [
        'id' => 62,
        'value' => $moneda_unidad
      ];
      $data[] = [
        'id' => 19,
        'value' => $currency
      ];
      $data[] = [
        'id' => 12,
        'value' => $nfacturalegal
      ];
      $data[] = [
        'id' => 38,
        'value' => $optlegal
      ];
      $data[] = [
        'id' => 34,
        'value' => $reconexion_cliente
      ];
      $data[] = [
        'id' => 54,
        'value' => $tipo_reconexion
      ];
      $data[] = [
        'id' => 119,
        'value' => $reconexion_texto
      ];
      $data[] = [
        'id' => 35,
        'value' => $mora_cliente
      ];
      $data[] = [
        'id' => 90,
        'value' => $mora_texto
      ];
      $data[] = [
        'id' => 43,
        'value' => $pdf_generado
      ];
      $data[] = [
        'id' => 45,
        'value' => $send_pagado
      ];
      $data[] = [
        'id' => 63,
        'value' => $send_recibo
      ];
      $data[] = [
        'id' => 53,
        'value' => $valida_pago
      ];
      $data[] = [
        'id' => 55,
        'value' => $facturacontinua
      ];
      $data[] = [
        'id' => 80,
        'value' => $sms_suspendido
      ];
      $data[] = [
        'id' => 107,
        'value' => $sms_alpagar
      ];
      $update = $this->model_configuracion->update_batch($data, 'id');
      if ($update) {
        $txt_log = "Modificacion de los ajustes generales de las FACTURAS";
        $insert_log = $this->model_log->insert_log(000000, "ajustes(facturas)", $txt_log, $_SESSION['id_usuario8291']);
      }
      echo $update ? 1 : 2;
    }
  }
  public function pasarelas_pago()
  {
    if ($this->seguridad->Acceso('PASARELAS_PAGO', 'LECTURA', $_SESSION['id_usuario8291'])) {
      return view('ajustes/pasarelas_pago/pasarelas_pago');
    }
  }
  public function get_pasarelas_pago_otros_json()
  {
    if ($this->seguridad->Acceso('PASARELAS_PAGO', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $pasarelas = $this->model_configuracion->get_pasarelas();
      if ($pasarelas) {
        foreach ($pasarelas as $key => $value) {
          $id = $value->id;
          $value->tool = "
          <a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"delete_pasarela('$id')\" data-original-title=\"Eliminar\">
            <i class=\"far fa-trash-alt\" aria-hidden=\"true\"></i>
          </a>
          <a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"getmodal('ajustes/edit_pasarela_otros?id=$id','Editar Forma de pago','md');\" data-original-title=\"Editar\">
            <i class=\"far fa-edit\"></i>
          </a>";
        }
      }
      $data['data'] = $pasarelas;
      return json_encode($data);
    }
  }
  public function modal_new_pasarela()
  {
    if ($this->seguridad->Acceso('PASARELAS_PAGO', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      return view('ajustes/pasarelas_pago/modal_new');
    }
  }
  public function delete_pasarela_pago_otros()
  {
    if ($this->seguridad->Acceso('PASARELAS_PAGO', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getPost('id');
      $delete = $this->model_configuracion->delete_pasarela_otros($id);
      if ($delete) {
        $txt_log = "Pasarela de pago(otros) eliminada ID: " . $id;
        $insert_log = $this->model_log->insert_log(000000, "ajustes(pasarelas de pago)", $txt_log, $_SESSION['id_usuario8291']);
      }
      $delete ? 1 : 2;
    }
  }
  public function edit_pasarela_otros()
  {
    if ($this->seguridad->Acceso('PASARELAS_PAGO', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getGet('id');
      $pasarela = $this->model_configuracion->get_pasarela_otro($id);
      if ($pasarela) {
        $data['pasarela'] = $pasarela;
        return view('ajustes/pasarelas_pago/modal_edit', $data);
      } else {
        echo "No se encontro la pasarela seleccionada.";
      }
    }
  }
  public function save_edit_pasarela()
  {
    if ($this->seguridad->Acceso('PASARELAS_PAGO', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getPost('pasarela[id]');
      $pasarela = $this->request->getPost('pasarela[pasarela]');
      $dsp1 = $this->request->getPost('pasarela[dsp1]');
      $dsp2 = $this->request->getPost('pasarela[dsp2]');
      $dsp3 = $this->request->getPost('pasarela[dsp3]');
      $dsp4 = $this->request->getPost('pasarela[dsp4]');
      $data = [
        'pasarela' => $pasarela,
        'dsp1' => $dsp1,
        'dsp2' => $dsp2,
        'dsp3' => $dsp3,
        'dsp4' => $dsp4
      ];
      $update = $this->model_configuracion->update_pasarela_otro($id, $data);
      if ($update) {
        $txt_log = "Pasarela de pago(otros) editada ID: " . $id;
        $insert_log = $this->model_log->insert_log(000000, "ajustes(pasarelas de pago)", $txt_log, $_SESSION['id_usuario8291']);
      }
      echo $update ? 1 : 2;
    }
  }
  public function new_pasarela_otros()
  {
    if ($this->seguridad->Acceso('PASARELAS_PAGO', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $pasarela = $this->request->getPost('pasarela[pasarela]');
      $dsp1 = $this->request->getPost('pasarela[dsp1]');
      $dsp2 = $this->request->getPost('pasarela[dsp2]');
      $dsp3 = $this->request->getPost('pasarela[dsp3]');
      $dsp4 = $this->request->getPost('pasarela[dsp4]');
      $data = [
        'pasarela' => $pasarela,
        'dsp1' => $dsp1,
        'dsp2' => $dsp2,
        'dsp3' => $dsp3,
        'dsp4' => $dsp4
      ];
      $insert = $this->model_configuracion->new_pasarela_otros($data);
      if ($insert) {
        $txt_log = "Nueva pasarela de pago(otros) insertada.";
        $insert_log = $this->model_log->insert_log(000000, "ajustes(pasarelas de pago)", $txt_log, $_SESSION['id_usuario8291']);
      }
      echo $insert ? 1 : 2;
    }
  }
  public function ajustes_tickets()
  {
    if ($this->seguridad->Acceso('TICKETS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      return view('ajustes/tickets/tickets');
    }
  }
  public function ticket_json()
  {
    if ($this->seguridad->Acceso('TICKETS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $departamentos = $this->model_configuracion->get_departamentos();
      if ($departamentos) {
        foreach ($departamentos as $value) {
          $id = $value->id;
          $value->tool = "
          <a onclick=\"getmodal('ajustes/edit_departamento?id=$id','Editar departamento','lg');\" data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" data-original-title=\"Editar\">
            <i class=\"far fa-edit\"></i>
          </a><a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"delete_dp('$id')\" data-original-title=\"Eliminar\">
            <i class=\"far fa-trash-alt\" aria-hidden=\"true\"></i>
          </a>";
        }
      }
      $data['data'] = $departamentos;
      return json_encode($data);
    }
  }
  public function edit_departamento()
  {
    if ($this->seguridad->Acceso('TICKETS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getGet('id');
      $departamento = $this->model_configuracion->get_departamento($id);
      if ($departamento) {
        $data['departamento'] = $departamento;
        return view('ajustes/tickets/modal_edit_departamento', $data);
      } else {
        echo "El departamento seleccionado no exite.";
      }
    }
  }
  public function delete_departamento()
  {
    if ($this->seguridad->Acceso('TICKETS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getPost('id');
      $delete = $this->model_configuracion->delete_departamento($id);
      if ($delete) {
        $txt_log = "Departamento eliminado ID: " . $id;
        $insert_log = $this->model_log->insert_log(000000, "ajustes(tickets)", $txt_log, $_SESSION['id_usuario8291']);
      }
      echo $delete ? 1 : 2;
    }
  }
  public function save_edit_departamento()
  {
    if ($this->seguridad->Acceso('TICKETS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getPost('id');
      $dp = $this->request->getPost('tk[dp]');
      $dsp = $this->request->getPost('tk[dsp]');
      $asuntos = $this->request->getPost('tk[asuntos]');
      $maildp = $this->request->getPost('tk[maildp]');
      $color = $this->request->getPost('tk[color]');
      $portal = $this->request->getPost('tk[portal]');
      $data = [
        'dp' => $dp,
        'dsp' => $dsp,
        'maildp' => $maildp,
        'color' => $color,
        'portal' => $portal,
        'asuntos' => $asuntos
      ];
      $update = $this->model_configuracion->update_departamento($id, $data);
      if ($update) {
        $txt_log = "Departamento editado ID: " . $id;
        $insert_log = $this->model_log->insert_log(000000, "ajustes(tickets)", $txt_log, $_SESSION['id_usuario8291']);
      }
      echo $update ? 1 : 2;
    }
  }
  public function nuevo_departamento()
  {
    if ($this->seguridad->Acceso('TICKETS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      return view('ajustes/tickets/modal_nuevo_departamento');
    }
  }
  public function save_nuevo_departamento()
  {
    if ($this->seguridad->Acceso('TICKETS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $dp = $this->request->getPost('tk[dp]');
      $dsp = $this->request->getPost('tk[dsp]');
      $maildp = $this->request->getPost('tk[maildp]');
      $color = $this->request->getPost('tk[color]');
      $portal = $this->request->getPost('tk[portal]');
      $data = [
        'dp' => $dp,
        'dsp' => $dsp,
        'maildp' => $maildp,
        'color' => $color,
        'portal' => $portal
      ];
      $insert = $this->model_configuracion->insert_new_departamento($data);
      if ($insert) {
        $txt_log = "Nuevo departamento creado ($dp) ID: " . $insert;
        $insert_log = $this->model_log->insert_log(000000, "ajustes(tickets)", $txt_log, $_SESSION['id_usuario8291']);
      }
      echo $insert ? 1 : 2;
    }
  }
  public function cambios_masivos()
  {
    if ($this->seguridad->Acceso('CAMBIOS_MASIVOS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $servers = $this->model_selectores->get_all_nodos();
      $planes = $this->model_selectores->get_planes();
      if ($planes and $servers) {
        $data['servers'] = $servers;
        $data['planes'] = $planes;
        return view('ajustes/cambios_masivos/cambios_masivos', $data);
      } else {
        echo "Error al cargar los planes o servidores.";
      }
    }
  }
  public function save_cambios_masivos()
  {
    if ($this->seguridad->Acceso('CAMBIOS_MASIVOS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $nodo = $this->request->getPost('nodo');
      $perfil = $this->request->getPost('perfil');
      $diapago = $this->request->getPost('diapago');
      $tipopago = $this->request->getPost('config[tipopago]');
      $dia_pago = $this->request->getPost('config[diapago]');
      $diafactura = $this->request->getPost('config[diafactura]');
      $impuesto = $this->request->getPost('config[impuesto]');
      $corteautomatico = $this->request->getPost('config[corteautomatico]');
      $meses = $this->request->getPost('config[meses]');
      $limit_velocidad = $this->request->getPost('config[limit_velocidad]');
      $mora = $this->request->getPost('config[mora]');
      $reconexion = $this->request->getPost('config[reconexion]');
      $impuestos1 = $this->request->getPost('impuestos[1]');
      $impuestos2 = $this->request->getPost('impuestos[2]');
      $impuestos3 = $this->request->getPost('impuestos[3]');
      $tipoaviso = $this->request->getPost('config[tipoaviso]');
      $avisopantalla = $this->request->getPost('config[avisopantalla]');
      $tiporecordatorio = $this->request->getPost('config[tiporecordatorio]');
      $avisosms = $this->request->getPost('config[avisosms]');
      $avisosms2 = $this->request->getPost('config[avisosms2]');
      $avisosms3 = $this->request->getPost('config[avisosms3]');
      $impuestos = "i:1;s:" . strlen($impuestos1) . ":\"" . $impuestos1 . "\";";
      $impuestos .= "i:2;s:" . strlen($impuestos2) . ":\"" . $impuestos2 . "\";";
      $impuestos .= "i:3;s:" . strlen($impuestos3) . ":\"" . $impuestos3 . "\";";
      $otros_impuestos = "a3{" . $impuestos . "}";
      $ids_cliente = $this->model_configuracion->consulta_cambios_masivos_idcliente($nodo, $perfil, $diapago);
      if (count($ids_cliente) >= 0) {
        $data = array();
        foreach ($ids_cliente as $value) {
          $data[] = [
            'cliente' => $value->idcliente,
            'mora' => $mora,
            'reconexion' => $reconexion,
            'impuesto' => $impuesto,
            'diapago' => $dia_pago,
            'tipopago' => $tipopago,
            'tipoaviso' => $tipoaviso,
            'meses' => $meses,
            'diafactura' => $diafactura,
            'avisopantalla' => $avisopantalla,
            'corteautomatico' => $corteautomatico,
            'avisosms' => $avisosms,
            'avisosms2' => $avisosms2,
            'avisosms3' => $avisosms3,
            'tiporecordatorio' => $tiporecordatorio,
            'limit_velocidad' => $limit_velocidad,
            'otros_impuestos' => $otros_impuestos,
          ];
        }
        $update = $this->model_configuracion->update_batch_avisouser($data, 'cliente');
        if ($update) {
          $txt_nodo = $nodo == 0 ? "cualquiera" : $nodo;
          $txt_perfil = $perfil == 0 ? "cualquiera" : $perfil;
          $txt_diapago = $diapago == 0 ? "cualquiera" : $diapago;
          $txt_log = "Se realizo un cambio masivo de la configuracion de los clientes con los sig parametros (nodo:$txt_nodo, perfil: $txt_perfil, dia de pago: $txt_diapago)";
          $insert_log = $this->model_log->insert_log(000000, "ajustes(cambios masivos)", $txt_log, $_SESSION['id_usuario8291']);
        }
        echo $update ? 1 : 2;
      } else {
        echo 3;
      }
    }
  }
  public function consulta_cambios_masivos()
  {
    if ($this->seguridad->Acceso('CAMBIOS_MASIVOS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $nodo = $this->request->getPost('nodo');
      $perfil = $this->request->getPost('perfil');
      $dia = $this->request->getPost('dia');
      $consulta = $this->model_configuracion->consulta_cambios_masivos($nodo, $perfil, $dia);
      if ($consulta) {
        $data['clientes'] = $consulta->clientes;
      } else {
        $data['clientes'] = 0;
      }
      return json_encode($data);
    }
  }
  public function zonas()
  {
    if ($this->seguridad->Acceso('ZONAS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      return view('ajustes/zonas/zonas');
    }
  }
  public function get_zonas_json()
  {
    if ($this->seguridad->Acceso('ZONAS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $zonas = $this->model_configuracion->get_zonas();
      if ($zonas) {
        foreach ($zonas as $value) {
          $id = $value->id;
          $activos = $value->activos;
          $suspendidos = $value->suspendidos;
          $total = $activos + $suspendidos;
          $value->activos = "<span class=\"badge label-success\">$activos</span>";
          $value->suspendidos = "<span class=\"badge label-danger\">$suspendidos</span>";
          $value->tool = "
          <a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"delete_zona('$id','$total')\" data-original-title=\"Eliminar\">
            <i class=\"far fa-trash-alt\" aria-hidden=\"true\"></i>
          </a>
          <a data-toggle=\"tooltip\" title=\"Editar\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"getmodal('ajustes/modal_edit_zona?id=$id','Editar Zona','sm');\">
            <i class=\"far fa-edit\"></i>
          </a>";
        }
        $data['data'] = $zonas;
      } else {
        $data['data'] = array();
      }
      return json_encode($data);
    }
  }
  public function delete_zona()
  {
    if ($this->seguridad->Acceso('ZONAS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getPost('id');
      $delete = $this->model_configuracion->delete_zona($id);
      if ($delete) {
        $txt_log = "Zona eliminada ID: " . $id;
        $insert_log = $this->model_log->insert_log(000000, "ajustes(zonas)", $txt_log, $_SESSION['id_usuario8291']);
      }
      echo $delete ? 1 : 2;
    }
  }
  public function save_new_zona()
  {
    if ($this->seguridad->Acceso('ZONAS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $zona = $this->request->getPost('zona');
      $data = [
        "zona" => $zona
      ];
      $insert = $this->model_configuracion->insert_new_zona($data);
      if ($insert) {
        $txt_log = "Zona insertada ($zona) ID: " . $insert;
        $insert_log = $this->model_log->insert_log(000000, "ajustes(zonas)", $txt_log, $_SESSION['id_usuario8291']);
      }
      echo $insert ? 1 : 2;
    }
  }
  public function modal_new_zona()
  {
    if ($this->seguridad->Acceso('ZONAS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      return view('ajustes/zonas/modal_new_zona');
    }
  }
  public function modal_edit_zona()
  {
    if ($this->seguridad->Acceso('ZONAS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getGet('id');
      $zona = $this->model_configuracion->get_zona($id);
      if ($zona) {
        $data['zona'] = $zona;
        return view('ajustes/zonas/modal_edit_zona', $data);
      } else {
        echo "No se encontro la zona seleccionada.";
      }
    }
  }
  public function save_edit_zona()
  {
    if ($this->seguridad->Acceso('ZONAS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getPost('id');
      $zona = $this->request->getPost('zona');
      $data = [
        'zona' => $zona
      ];
      $update = $this->model_configuracion->update_zona($id, $data);
      if ($update) {
        $txt_log = "Zona editada ID: " . $id;
        $insert_log = $this->model_log->insert_log(000000, "ajustes(zonas)", $txt_log, $_SESSION['id_usuario8291']);
      }
      echo $update ? 1 : 2;
    }
  }
  public function save_sms()
  {
    if ($this->seguridad->Acceso('SMS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $userapi = $this->request->getPost('2[userapi]');
      $idequipo = $this->request->getPost('2[idequipo]');
      $limite = $this->request->getPost('2[limite]');
      $pausa = $this->request->getPost('2[pausa]');
      $pais = $this->request->getPost('2[pais]');
      $state = $this->request->getPost('2[state]');
      $data = [
        'userapi' => $userapi,
        'state' => $state,
        'idequipo' => $idequipo,
        'pausa' => $pausa,
        'pais' => $pais,
        'limite' => $limite,
      ];
      $update = $this->model_configuracion->update_sms(2, $data);
      if ($update) {
        $txt_log = "Configuracion de sms editada ID: 2";
        $insert_log = $this->model_log->insert_log(000000, "ajustes(sms)", $txt_log, $_SESSION['id_usuario8291']);
      }
      echo $update ? 1 : 2;
    }
  }
  public function sms()
  {
    if ($this->seguridad->Acceso('SMS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $sms = $this->model_configuracion->get_sms();
      $smsconfig = $this->model_sms->get_config('2');
      if ($sms and $smsconfig) {
        $options = "";
        $data['sms'] = $sms;
        try {
          $config = Configuration::getDefaultConfiguration();
          $config->setApiKey('Authorization', $smsconfig->userapi);
          $apiClient = new ApiClient($config);
          $deviceClient = new DeviceApi($apiClient);
          $devices = $deviceClient->searchDevices();
          $devices = $devices->getResults();
          foreach ($devices as  $value) {
            $id = $value->getId();
            $name = $value->getName();
            $options .= "<option value=\"$id\">$id - $name </option>";
          }
        } catch (\Throwable $th) {
        }
        $data['options'] = $options;
        return view('ajustes/sms/sms', $data);
      } else {
        echo "No se encontro ninguna configuracion";
      }
    }
  }
  public function google()
  {
    if ($this->seguridad->Acceso('GOOGLE', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $ids = array(44);
      $config = $this->model_configuracion->get_configuracion_name($ids);
      if ($config) {
        $data['config'] = $config;
        return view('ajustes/google/clave_google', $data);
      } else {
        echo "No se encontro ninguna api de google registrada en el sistema.";
      }
    }
  }
  public function save_google()
  {
    if ($this->seguridad->Acceso('GOOGLE', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $keyapigoogle = $this->request->getPost('ajuste[keyapigoogle]');
      $data = [
        'value' => $keyapigoogle
      ];
      $update = $this->model_configuracion->save_google(44, $data);
      if ($update) {
        $txt_log = "Api de Google actualizada.";
        $insert_log = $this->model_log->insert_log(000000, "ajustes(google)", $txt_log, $_SESSION['id_usuario8291']);
      }
      echo $update ? 1 : 2;
    }
  }
  public function delete_sucursal(){
    $id=$this->request->getPost('id');
    $permiso=$this->seguridad->Acceso('SUCURSAL', 'ESCRITURA', $_SESSION['id_usuario8291']);
    if ($permiso) {
      $delete_sucursal=$this->model_configuracion->delete_sucursal($id);
      if ($delete_sucursal) {
        $this->model_configuracion->delete_relacion_sucursal_nodo($id);
        $txt_log = "Sucursal eliminada ID: $id";
        $insert_log = $this->model_log->insert_log(000000, "sucursal", $txt_log, $_SESSION['id_usuario8291']);
      }
      echo $delete_sucursal?1:2;
    }
  }
  public function modal_ajustes_almacen()
  {
    $permiso= $this->seguridad->Acceso_void('AJUSTES_ALMACEN', 'ESCRITURA', $_SESSION['id_usuario8291']);
    $ids = array(120,122);
    $config = $this->model_configuracion->get_configuracion_name($ids);
    $data=array();
    if ($permiso==null) {
      echo "<script>alerta('warning','No tienes permisos para acceder a esta opcion.')</script>";
    }
    if (!$config) {
      echo "<script>alerta('erro','No se pudo optener las configuraciones generales.')</script>";
    }
    if ($permiso) {
      $extra=$this->request->getGet('extra');
      if ($extra) {
        $data['extra']=$extra;
        $data['individual']=true;
      }
      $datos= $config["parametros_almacen"];
      $datos2= $config["parametros_almacen_accesorios"];
      $datos= explode(";", $datos);
      $datos2= explode(";", $datos2);
      if (count($datos)!=6) {
        echo "<script>alerta('erro','Error al leer los datos de la configuracion.')</script>";
      }
      if (count($datos2)!=6) {
        echo "<script>alerta('erro','Error al leer los datos de la configuracion.')</script>";
      }
      $data['datos'] =$datos;
      $data['datos2'] =$datos2;
      return view('ajustes/almacen/modal_ajustes_almacen',$data);
    }
  }
  public function save_personalizacion_almacen()
  {
    if ($this->seguridad->Acceso('AJUSTES_ALMACEN', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $i1=$this->request->getPost('i1');
      $s1=$this->request->getPost('s1');
      $i2=$this->request->getPost('i2');
      $s2=$this->request->getPost('s2');
      $i3=$this->request->getPost('i3');
      $s3=$this->request->getPost('s3');
      $i4=$this->request->getPost('i4');
      $s4=$this->request->getPost('s4');
      $i5=$this->request->getPost('i5');
      $s5=$this->request->getPost('s5');
      $i6=$this->request->getPost('i6');
      $s6=$this->request->getPost('s6');
      $data = array();
      $txt="";
      $txt2="";
      for ($u=1; $u <=3 ; $u++) {
        $var=$u!=1?";":"";
        $uno=strval('i'.$u);
        $dos=strval('s'.$u);
        $txt.=$var.$$uno.";".$$dos;
      }
      if (count(explode(";",$txt))!=6) {
        echo 2;
        return;
      }
      for ($u=4; $u <=6 ; $u++) {
        $var=$u!=4?";":"";
        $uno=strval('i'.$u);
        $dos=strval('s'.$u);
        $txt2.=$var.$$uno.";".$$dos;
      }
      if (count(explode(";",$txt))!=6) {
        echo 2;
        return;
      }
      $data[] = [
        'id' => 120,
        'value' => $txt
      ];
      $data2[] = [
        'id' => 122,
        'value' => $txt2
      ];
      $update = $this->model_configuracion->update_batch($data, 'id');
      $update2 = $this->model_configuracion->update_batch($data2, 'id');
      if ($update or $update2) {
        $txt_log = "Se actualizo la personalizacion de almacen (ajustes)";
        $insert_log = $this->model_log->insert_log(000000, "ajustes(almacen)", $txt_log, $_SESSION['id_usuario8291']);
      }
      if ($update2 or $update) {
        echo 1;
      }else{
        echo 2;
      }
    }
  }
}
