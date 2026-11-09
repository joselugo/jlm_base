<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Libraries\Class_seguridad;
use App\Models\Model_log;
use App\Models\Model_formatos;
use App\Libraries\Class_permiso_sucursal;
use App\Models\Model_selectores;
class Editor_plantillas extends BaseController
{
  protected Class_permiso_sucursal $permiso_sucursal;
  protected Model_selectores $model_selectores;
  protected Model_log $model_log;
  protected Class_seguridad $seguridad;
  protected Model_formatos $formatos;

  public function __construct()
  {
    $this->permiso_sucursal = new Class_permiso_sucursal();
    $this->model_selectores = new Model_selectores();
    $this->model_log = new Model_log();
    $this->seguridad = new Class_seguridad();
    $this->formatos = new Model_formatos();
    date_default_timezone_set('America/Mexico_City');
    session();
  }
  public function home()
  {
    if ($this->seguridad->Acceso('EDITOR_PLANTILLAS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $idsucursal=$this->request->getGet('idsucursal');
      $permiso_sucursal = $this->permiso_sucursal->get_permiso_sucursal_select($_SESSION['sucursales']);
      if (!$idsucursal) {
        $idsucursal=$this->formatos->get_primer_sucursal($permiso_sucursal);
        if (!$idsucursal) {
          return 'Error no tinenes asignado ninguna sucursal para cargar esa opcion';
        }
        $idsucursal = $idsucursal[0]->id;
      }
      $sucursales=$this->model_selectores->get_all_sucursales_by_user($permiso_sucursal);
      $formatos=$this->formatos->get_formatos();
      $data['idsucursal']=$idsucursal;
      if ($formatos) {
      $data['formatos']=$formatos;
      }else{
        $data['formatos']=array();
      }
      if ($sucursales) {
      $data['sucursales']=$sucursales;
    }else{
      $data['sucursales']=array();
    }
      return view('ajustes/editor_plantillas/home',$data);
    }
  }

  public function formatos()
  {
    if ($this->seguridad->Acceso('EDITOR_PLANTILLAS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $idformato=$this->request->getGet('idformato');
      $idsucursal=$this->request->getGet('idsucursal');
      $documentos=$this->formatos->get_documentos($idformato,$idsucursal);
      if ($documentos) {
        $data['documentos']=$documentos;
      }else{
        $data['documentos']=array();
      }
      return view('ajustes/editor_plantillas/utils/nav_formatos',$data);
    }
  }
  public function nav_documentos()
  {
    if ($this->seguridad->Acceso('EDITOR_PLANTILLAS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $idformato=$this->request->getGet('idformato');
      $documentos=$this->formatos->get_documentos($idformato);
      if ($documentos) {
        $data['documentos']=$documentos;
      }else{
        $data['documentos']=array();
      }
      return view('ajustes/editor_plantillas/utils/nav_documentos',$data);
    }
  }

  public function editar_plantilla()
  {
    if ($this->seguridad->Acceso('EDITOR_PLANTILLAS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $idformato = $this->request->getGet('idformato');
      $formato = $this->formatos->get_formato_edit($idformato);
      if ($formato[0]->ancho == "") $formato[0]->ancho = "0";
      if ($formato[0]->alto == "") $formato[0]->alto = "0";
      if ($formato[0]->tamano == "") $formato[0]->tamano = "0";
      $data['formato'] = $formato[0];
      return view('ajustes/editor_plantillas/utils/modal_ckeditor', $data);
    }
  }
  /**
   * Vista previa de una plantilla con datos de ejemplo.
   * Usa los datos y el logo reales de la sucursal dueña de la plantilla, con las
   * mismas sustituciones que los controladores que generan el documento real
   * ({url} y images/logofactura.png = logo de facturas y recibos de la sucursal).
   * Los documentos se muestran como PDF (mismo tamaño/orientación configurados);
   * correos, avisos y SMS se muestran como HTML.
   */
  public function vista_previa()
  {
    if (!$this->seguridad->Acceso('EDITOR_PLANTILLAS', 'LECTURA', $_SESSION['id_usuario8291'])) {
      return;
    }
    $formato = $this->formatos->get_formato_edit((int) $this->request->getGet('idformato'));
    if (!$formato) {
      return $this->response->setStatusCode(404)->setBody('Plantilla no encontrada');
    }
    $formato = $formato[0];

    // La cotización se arma con sus tres plantillas juntas (página 1, datos bancarios y pie).
    if (str_starts_with($formato->tipoaviso, 'cotizacion')) {
      [$cot, $items] = \App\Libraries\CotizacionPdf::ejemplo((int) $formato->idsucursal);
      if ($formato->tipoaviso === 'cotizacion_mail') {
        return $this->response->setContentType('text/html')->setBody(\App\Libraries\CotizacionPdf::correo($cot, $items));
      }
      return $this->response
        ->setContentType('application/pdf')
        ->setHeader('Content-Disposition', 'inline; filename="vista_previa_cotizacion.pdf"')
        ->setBody(\App\Libraries\CotizacionPdf::crear($cot, $items)->Output('', 'S'));
    }

    $sucursal = db_connect()->table('sucursal')->where('id', $formato->idsucursal)->get()->getRow();
    $logo = $sucursal->logo_facturas_recibos ?? '';

    $fila = '<tr><td>Servicio de internet 50 Mbps</td><td>MX$500.00</td><td>16%</td><td>1</td><td>MX$500.00</td></tr>'
          . '<tr><td>Renta de equipo</td><td>MX$100.00</td><td>16%</td><td>1</td><td>MX$100.00</td></tr>';
    $hoy = date('d/m/Y');
    $ejemplo = [
      '../admin/images/logofactura.png' => '/' . $logo,
      'images/logofactura.png' => '/' . $logo,
      '{url}'               => $logo,
      '{url_logo}'          => $logo,
      '{nombre_empresa}'    => $sucursal->alias ?? '',
      '{ruc_empresa}'       => $sucursal->rfc ?? '',
      '{direccion_empresa}' => $sucursal->direccion ?? '',
      '{telefono_empresa}'  => $sucursal->telefono ?? '',
      '<td>{items}</td>'    => $fila,
      '{items}'             => $fila,
      '{itempos}'           => 'Servicio de internet 50 Mbps ..... MX$500.00',
      '{nombre_cliente}'    => 'Cliente de Ejemplo',
      '{cliente}'           => 'Cliente de Ejemplo',
      '{cedula_cliente}'    => 'XAXX010101000',
      '{codigo_cliente}'    => '000123',
      '{id_cliente}'        => '123',
      '{direccion_principal_cliente}' => 'Av. Siempre Viva 742',
      '{direccion}'         => 'Av. Siempre Viva 742',
      '{telefono_cliente}'  => '555 000 0000',
      '{movil_cliente}'     => '555 111 1111',
      '{estado}'            => 'No pagado',
      'estadocss'           => 'nopagado',
      '{nfactura}'          => '00000001',
      '{nrecibo}'           => '00000001',
      '{folio}'             => '00000001',
      '{barcode}'           => '123',
      '{emitido}'           => $hoy,
      '{fecha_emision}'     => $hoy,
      '{fecha}'             => $hoy,
      '{fecha_actual}'      => $hoy,
      '{fecha_impresion}'   => $hoy,
      '{fecha_pagado}'      => $hoy,
      '{vencimiento}'       => $hoy,
      '{fecha_vencimiento}' => $hoy,
      '{fecha_corte}'       => $hoy,
      '{subtotal}'          => 'MX$600.00',
      '{impuesto}'          => 'MX$96.00',
      '{iva}'               => 'MX$96.00',
      '{descuento}'         => 'MX$0.00',
      '{otro_impuesto_1}'   => 'MX$0.00',
      '{otro_impuesto_2}'   => 'MX$0.00',
      '{otro_impuesto_3}'   => 'MX$0.00',
      '{total}'             => 'MX$696.00',
      '{cobrado}'           => 'MX$696.00',
      '{total_pagado}'      => 'MX$696.00',
      '{saldo}'             => 'MX$0.00',
      '{total_letras}'      => '696.00 - (SEISCIENTOS NOVENTA Y SEIS PESOS 00/100 MX)',
      '{forma_pago}'        => 'Efectivo',
      '<p>{operaciones}</p>' => '',
      '{password}'          => '********',
      '{nombre_tecnico}'    => 'Técnico de Ejemplo',
      '{observaciones}'     => 'Observaciones de ejemplo',
      '{servicios}'         => '<tr><td>Internet 50 Mbps</td><td>$ 500</td></tr>',
      '{productos}'         => '',
      '{materiales}'        => '',
      '{plan}'              => 'Internet 50 Mbps',
    ];
    $html = html_entity_decode((string) $formato->html);
    $ejemplo += \App\Libraries\CamposPlantilla::valores($html, $sucursal); // {sucursal_*}, {config_*}
    $html = strtr($html, $ejemplo);

    $documentos = ['factura', 'recibo', 'recibopos', 'recibopos80', 'instalacion', 'contrato', 'afip', 'dian', 'publicidad'];
    if (!in_array($formato->tipoaviso, $documentos, true)) {
      return $this->response->setContentType('text/html')->setBody($html);
    }

    if (is_numeric($formato->tamano)) {
      // Ticket POS: el tamaño es el ancho del rollo en mm (igual que Finanzas::transacciones).
      $config = ['format' => [(int) $formato->tamano, 250], 'margin_left' => 10, 'margin_right' => 10,
                 'margin_top' => 10, 'margin_footer' => 0, 'margin_bottom' => 0];
    } elseif ($formato->tamano != '') {
      $config = ['format' => $formato->tamano . $formato->posicion];
    } elseif (intval($formato->ancho) > 0 && intval($formato->alto) > 0) {
      $config = ['format' => [intval($formato->ancho), intval($formato->alto)]];
    } else {
      $config = ['format' => 'A4'];
    }
    $mpdf = new \Mpdf\Mpdf($config);
    $mpdf->SetTitle('Vista previa - ' . $formato->tipoaviso);
    $mpdf->WriteHTML($html);

    return $this->response
      ->setContentType('application/pdf')
      ->setHeader('Content-Disposition', 'inline; filename="vista_previa_' . $formato->tipoaviso . '.pdf"')
      ->setBody($mpdf->Output('', 'S'));
  }
  public function guardar_formato()
  {
    if ($this->seguridad->Acceso('EDITOR_PLANTILLAS', 'ESCRITURA', $_SESSION['id_usuario8291'])) {
      $id = $this->request->getPost('formato[id]');
      $asuntofactura = $this->request->getPost('formato[asuntofactura]');
      $tamano = $this->request->getPost('formato[tamano]');
      $posicion = $this->request->getPost('formato[posicion]');
      $tipoaviso = $this->request->getPost('formato[tipoaviso]');
      $html = $this->request->getPost('formato[html]');
      $alto = "";
      $ancho = "";
      if ($tamano == "") $tamano = "0";
      if ($tamano == "0") {
        $ancho = $this->request->getPost('formato[ancho]');
        $alto = $this->request->getPost('formato[alto]');
      }
      $data_formato = [
        'html' => $html,
        'tipoaviso' => $tipoaviso,
        'asuntofactura' => $asuntofactura,
        'tamano' => $tamano != "0" ? $tamano : "",
        'ancho' => $ancho != "" ? $ancho : "",
        'alto' => $alto != "" ? $alto : "",
        'posicion' => $posicion,
      ];
      $result_guardar_formato = $this->formatos->save_formato($id, $data_formato);
      if ($result_guardar_formato) {
        $txt_log = "Plantilla editada ($tipoaviso)";
        $insert_log = $this->model_log->insert_log(0, "", $txt_log, $_SESSION['id_usuario8291']);
        return "1";
      } else {
        return "2";
      }
    }
  }
}
