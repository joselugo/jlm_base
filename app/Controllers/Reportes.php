<?php namespace App\Controllers;

use App\Libraries\Class_seguridad;
use App\Libraries\Class_permiso_sucursal;
use App\Models\Model_reportes;
use App\Models\Model_selectores;
use App\Models\Model_log;

class Reportes extends BaseController
{
  var $model_reportes;
  var $model_selectores;
  var $permiso_sucursal;
  var $seguridad;
  var $nmodel_log;
  public function __construct()
  {
    $this->model_reportes = new Model_reportes();
    $this->model_selectores = new Model_selectores();
    $this->model_log = new Model_log();
    $this->permiso_sucursal = new Class_permiso_sucursal();
    $this->seguridad = new Class_seguridad();
    session();
  }

  public function index()
  {
    if (isset($_SESSION['id_usuario8291'])) {

      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_REPORTES'));
      if ($tipoacceso > 0) {

        $data['tipoacceso'] = $tipoacceso;
        $data['token'] = $_SESSION['token8291'];
        echo view('reportes/lista_reportes/index', $data);
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  public function get_reportes_json()
  {

    if (isset($_SESSION['id_usuario8291'])) {
      $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('LISTA_REPORTES'));
      if ($tipoacceso > 0) {

        $status = $this->request->getGet("tipo");
        $permiso_sindicato = $this->permiso_sindicato->get_permiso_socios($_SESSION['sindicatos']);
        $permiso_cerrar_re = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('CERRAR_REPORTES'));
        $reportes = $this->model_reportes->get_all_reportes($status, $permiso_sindicato);


        $array_cliente = array();
        $estado = "";
        if ($reportes) {
          foreach ($reportes as $items) {
            $status = "";
            $tool = "";

            if ($items->estado == "ATENDIDO") {
              $status = "<span class='badge badge-success'>" . $items->estado . "</span>";
            } else if ($items->estado == "EN REVISION") {
              $status = "<span class='badge badge-warning'>" . $items->estado . "</span>";
            } else if ($items->estado == "CERRADO") {
              $status = "<span class='badge badge-danger'>" . $items->estado . "</span>";
            } else {
              $status = "<span class='badge badge-primary'>" . $items->estado . "</span>";
            }

            if ($tipoacceso == 1) {
              $tool = '';
            }
            if ($tipoacceso == 2) {
              $tool = '<td class=" tool-documentos text-center">
             <a data-toggle="tooltip" title="Ver reporte" class="btn btn-default btn-icon btn-sm" onclick="edit_re(\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $id_socio . '\')"><i class="far fa-edit" aria-hidden="true"></i></a></td';
            }

            if ($tipoacceso == 2 && $permiso_cerrar_re > 0) {
              $tool = '<td class=" tool-documentos text-center">
             <a data-toggle="tooltip" title="Ver reporte" class="btn btn-default btn-icon btn-sm" onclick="edit_re(\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $id_socio . '\')"><i class="far fa-edit" aria-hidden="true"></i></a>
             <a data-toggle="tooltip" title="" class="btn btn-default btn-icon btn-sm" onclick="cerrar_re(\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $items->usuario . '\',\'' . $items->id_socio . '\')" data-original-title="Cerrar reporte"><i class="fas fa-times-circle"></i></a></td';
            }

            if ($tipoacceso == 3) {
              $tool = '<td class=" tools-documentos text-center">
             <a data-toggle="tooltip" title="Ver reporte" class="btn btn-default btn-icon btn-sm" onclick="edit_re(\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $items->id_socio . '\')"><i class="far fa-edit" aria-hidden="true"></i></a>
            <a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_re(\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $items->usuario . '\',\'' . $items->id_socio . '\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a></td>';
            }


            if ($tipoacceso == 3 && $permiso_cerrar_re > 0) {
              $tool = '<td class=" tools-documentos text-center">
             <a data-toggle="tooltip" title="Ver reporte" class="btn btn-default btn-icon btn-sm" onclick="edit_re(\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $items->id_socio . '\')"><i class="far fa-edit" aria-hidden="true"></i></a>
            <a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" onclick="delete_re(\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $items->usuario . '\',\'' . $items->id_socio . '\')"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
            <a data-toggle="tooltip" title="" class="btn btn-default btn-icon btn-sm" onclick="cerrar_re(\'' . $items->id . '\',\'' . $_SESSION['token8291'] . '\',\'' . $items->usuario . '\',\'' . $items->id_socio . '\')" data-original-title="Cerrar reporte"><i class="fas fa-times-circle"></i></a></td>';
            }




            $array_item = array(
              "id" => $items->id,
              "tipo" => '<strong style="color:black">' . strtoupper($items->tipo) . '</strong>',
              "_from" => '<span style="color:#0099ff;border: 1px solid #0099ff;padding: 2px 5px;border-radius: 5px;text-transform: uppercase;font-size: 9.5px;font-weight: 700;">' . $items->_from . '</span>',
              "socio" => '<a href="#socios/view?id=' . $items->id_socio . '&amp;token=' . $_SESSION['token8291'] . '"  >' . $items->nombre . ' ' . $items->apellidos . '</a>',
              "asunto" => "<a href='#socios/view_reportes?id=" . $items->id . "&token=" . $_SESSION['token8291'] . "&socio=" . $items->id_socio . "'>" . $items->asunto . "</a>",
              "estado" => $status,
              "fechaalta" => date('d/m/Y - h:i a', strtotime($items->fechaalta)),
              "tool" => $tool,
              "tipoacceso" => $tipoacceso
            );
            array_push($array_cliente, $array_item);
          }
        }

        echo '{"data":' . json_encode($array_cliente) . '}';
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }



  public function delete_re()
  {

    if (isset($_SESSION['id_usuario8291']) && $this->request->getPost('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {

        $id = $this->request->getPost('id');
        $nombre = $this->request->getPost('nombre');
        $id_socio = $this->request->getPost('id_socio');
        $insert_log = $this->model_log->insert_log($id_socio, "socios", "Reporte eliminado <b>#" . $id . " al socio " . $id_socio . "</b>", $_SESSION['nombre']);
        $delete = $this->model_reportes->delete_re($id);
        if ($delete) echo 1;
        else echo 2;
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function cerrar_re()
  {

    if (isset($_SESSION['id_usuario8291']) && $this->request->getPost('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('CERRAR_REPORTES'));
      if ($tipoacceso > 0) {

        $id = $this->request->getPost('id');
        $nombre = $this->request->getPost('nombre');
        $id_socio = $this->request->getPost('id_socio');
        $insert_log = $this->model_log->insert_log($id_socio, "socios", "Reporte cerrado <b>#" . $id . "</b> al socio " . $id_socio, $_SESSION['nombre']);
        $update = $this->model_reportes->cerrar_re($id);
        if ($update) echo 1;
        else echo 2;
      } else echo "ACCESO DENEGADO";
    } else $this->seguridad->login_back();
  }



  public function new_reporte()
  {

    if (isset($_SESSION['id_usuario8291']) && $this->request->getPost('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {
        $id_socio = $this->request->getPost('idsocio');
        $asunto = $this->request->getPost('asunto-modal');
        $tipo = $this->request->getPost('tipo-modal');
        $descripcion = $this->request->getPost('descripcion-modal');
        $action = $this->request->getPost('action-modal');

        $target_dir = 'public/uploads/reportes/';
        $cadena_names = "";
        if (isset($_FILES['adjunto-modal']['name'])) {

          $total_files = count($_FILES['adjunto-modal']['name']);


          for ($key = 0; $key < $total_files; $key++) {

            // Check if file is selected
            if (isset($_FILES['adjunto-modal']['name'][$key]) && $_FILES['adjunto-modal']['size'][$key] > 0) {

              $original_filename = $_FILES['adjunto-modal']['name'][$key];
              $target = $target_dir . basename($original_filename);
              $tmp  = $_FILES['adjunto-modal']['tmp_name'][$key];
              if ($total_files == 1) {
                $cadena_names .= $original_filename;
              } else {
                if ($total_files == $key + 1) {
                  $cadena_names .= $original_filename;
                } else {
                  $cadena_names .= $original_filename . "{--}";
                }
              }
              if (is_dir($target_dir)) {
                move_uploaded_file($tmp, $target);
              } else {
                mkdir($target_dir, 7777);
                move_uploaded_file($tmp, $target);
              }
            }
          }
        }

        if ($action == "new") {
          date_default_timezone_set('America/Mexico_City');
          $arrayName = [
            'idsocio' => $id_socio, 'tipo' => $tipo, 'fechaalta' => date('Y-m-d H:i:s'), '_from' => "sistema", 'usuario' => $_SESSION['nombre'],
            'asunto' => $asunto, 'estado' => "NUEVO"
          ];
          $insert = $this->model_reportes->new_reporte($arrayName);
          if ($insert) {
            $row = $this->model_reportes->get_ultimo_reporte();
            $ultimo_id = $row->ultimo;

            $arrayName1 = [
              'idreporte' => $ultimo_id, 'descripcion' => $descripcion, 'adjunto' => $cadena_names,
              'fechaaltare' => date('Y-m-d H:i:s'), 'state' => "respuesta", 'user' => 'sistema'
            ];
            $insert_ticket = $this->model_reportes->new_ticket($arrayName1);

            if ($insert_ticket) {
              $insert_log = $this->model_log->insert_log($id_socio, "socios", "Nuevo reporte <b>#" . $ultimo_id . " al socio " . $id_socio . "</b>", $_SESSION['nombre']);
              echo 1;
            }
          } else {
            echo 2;
          }
        } else {
        }
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }


  public function update_reporte()
  {

    if (isset($_SESSION['id_usuario8291']) && $this->request->getPost('token') == $_SESSION['token8291']) {
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('LISTA_SOCIOS'));
      if ($tipoacceso > 0) {
        $id_re = $this->request->getPost('id-reporte');
        $id_socio = $this->request->getPost('id-socio');
        $asunto = $this->request->getPost('asunto-reporte');
        $tipo = $this->request->getPost('tipo-reporte');
        $estado = $this->request->getPost('estado-reporte');

        $arrayName1 = ['asunto' => $asunto, 'tipo' => $tipo, 'estado' => $estado];
        $update = $this->model_reportes->update_reporte($id_re, $arrayName1);
        if ($update) {
          $insert_log = $this->model_log->insert_log("socios", "Asunto modificado al reporte <b>#" . $id_re . " -- socio " . $id_socio . "</b>", $_SESSION['nombre']);
          echo "success";
        } else {
          echo "error";
        }
      } else echo acceso_denegado();
    } else $this->seguridad->login_back();
  }

  
    public function balance(){   
    if( isset($_SESSION['id_usuario8291']) ){
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('BALANCE_GENERAL'));
      if($tipoacceso > 0){

        $month=date("m");$year=date("Y"); 
        $ultimo_dia=date("d",(mktime(0,0,0,$month+1,1,$year)-1));
        $fecha_inicio=date("Y")."-".date("m")."-01";
        $fecha_fin=date("Y")."-".date("m")."-".$ultimo_dia;
        $fecha_fin2 = date('Y-m-d', strtotime($fecha_fin." + 1 day"));
        $gastos_tipo = $this->model_reportes->get_gastos_tipo();
         
         $result_facturado = $this->model_reportes->get_facturado($fecha_inicio,$fecha_fin2);
         $facturado=0;
         if($result_facturado){
            foreach ($result_facturado as $items) {
            $facturado+=$items->cobrado;
           }
         }
         

         $result_ingresos = $this->model_reportes->get_ingresos($fecha_inicio,$fecha_fin);
         $ingresos=0;
         if($result_ingresos){
             foreach ($result_ingresos as $items) {
             $ingresos+=$items->monto;
            }
         }
  

         $content_table="";
         $contador=1;$total_tipo=0; $total_egresos=0;
         foreach ($gastos_tipo as $items) {
           $content_table.="<tr>";
           if($contador==1){
            $content_table.="<td style=''>Facturado <div id='totales'>$ ".number_format($facturado,2)."</div></td>";
           }
          
           else if($contador==2){
            $content_table.="<td style=''>Ingresos <div id='totales'>$ ".number_format($ingresos,2)."</div></td>";
           }
           else{ $content_table.="<td style=''></td>";}
           //////////////////////////////////////////////////////////////////////buscar items en gastos por tipo
            
           if($items->id==0){} ////////////////si el tipo de gasto es cero no imprimi ni suma nada
           else{

            $result_tipo = $this->model_reportes->get_total_tipo($items->id,$fecha_inicio,$fecha_fin);
           if($result_tipo){foreach ($result_tipo as $var){$total_tipo+=$var->monto;}}else{$total_tipo=0;}
          
           $total_egresos+=$total_tipo;
           $content_table.='<td style="">'.$items->tipo.'<div id="totales">$'.number_format($total_tipo,2).'&nbsp;&nbsp; <button type="button" class="btn btn-default btn-icon btn-md" value="'.$items->id.'" onclick="prueba(\''.$items->id.'\')"><i class="far fa-edit"></i></button></div></td>';
           $content_table.="<td style=''></td>";
           $content_table.="</tr>";
           $contador++;
           $total_tipo=0;

           } ///fin else si el tipo de gasto es cero
            
         }

          $content_table.="<tr style='background-color: gray; color: white'>";
          $content_table.="<td>TOTAL INGRESOS <div id='totales'>$ ".number_format($facturado + $ingresos,2)."</div></td>";
          $content_table.="<td>TOTAL EGRESOS <div id='totales'>$ ".number_format($total_egresos,2)."</div></td>";
          $content_table.="<td>TOTAL UTILIDADES <div id='totales'>$ ".number_format(($facturado + $ingresos) - $total_egresos,2)."</div></td>";
          $content_table.="</tr>";

         $data['fecha_inicio'] = $fecha_inicio;
         $data['fecha_fin'] = $fecha_fin; 
         $data['content_table'] = $content_table; 
         echo view('reportes/balance_general', $data);


        } else  echo acceso_denegado(); 
    
    }else $this->seguridad->login_back();
  }

       public function get_balance(){

     if( isset($_SESSION['id_usuario8291']) ){
      $seguridad = new Class_seguridad();
      $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('BALANCE_GENERAL'));
      if($tipoacceso > 0){

        $month=date("m");$year=date("Y"); 
        $ultimo_dia=date("d",(mktime(0,0,0,$month+1,1,$year)-1));
        $fecha_inicio=$this->request->getPost("desde");
        $fecha_fin=$this->request->getPost("hasta");
        $gastos_tipo = $this->model_reportes->get_gastos_tipo();
        $fecha_fin2 = date('Y-m-d', strtotime($fecha_fin." + 1 day"));
         $result_facturado = $this->model_reportes->get_facturado($fecha_inicio,$fecha_fin2);
         $facturado=0;
         if($result_facturado){
            foreach ($result_facturado as $items) {
            $facturado+=$items->cobrado;
           }
         }
         

         $result_ingresos = $this->model_reportes->get_ingresos($fecha_inicio,$fecha_fin);
         $ingresos=0;
         if($result_ingresos){
             foreach ($result_ingresos as $items) {
             $ingresos+=$items->monto;
            }
         }
  

         $content_table="";
         $contador=1;$total_tipo=0; $total_egresos=0;
         foreach ($gastos_tipo as $items) {
           $content_table.="<tr>";
           if($contador==1){
            $content_table.="<td style=''>Facturado <div id='totales'>$ ".number_format($facturado,2)."</div></td>";
           }
          
           else if($contador==2){
            $content_table.="<td style=''>Ingresos <div id='totales'>$ ".number_format($ingresos,2)."</div></td>";
           }
           else{ $content_table.="<td style=''></td>";}
           //////////////////////////////////////////////////////////////////////buscar items en gastos por tipo

             if($items->id==0){} ////////////////si el tipo de gasto es cero no imprimi ni suma nada
           else{
            
               $result_tipo = $this->model_reportes->get_total_tipo($items->id,$fecha_inicio,$fecha_fin);
           if($result_tipo){foreach ($result_tipo as $var){$total_tipo+=$var->monto;}}else{$total_tipo=0;}
          
           $total_egresos+=$total_tipo;
           $content_table.='<td style="">'.$items->tipo.'<div id="totales">$'.number_format($total_tipo,2).'&nbsp;&nbsp; <button type="button" class="btn btn-default btn-icon btn-md" value="'.$items->id.'" onclick="prueba(\''.$items->id.'\')"><span class="far fa-edit"></span></button></div></td>';
           $content_table.="<td style=''></td>";
           $content_table.="</tr>";
           $contador++;
           $total_tipo=0;

           } //fin else tipo gasto=0

         
         }

          $content_table.="<tr style='background-color: gray; color: white'>";
          $content_table.="<td>TOTAL INGRESOS <div id='totales'>$ ".number_format($facturado + $ingresos,2)."</div></td>";
          $content_table.="<td>TOTAL EGRESOS <div id='totales'>$ ".number_format($total_egresos,2)."</div></td>";
          $content_table.="<td>TOTAL UTILIDADES <div id='totales'>$ ".number_format(($facturado + $ingresos) - $total_egresos,2)."</div></td>";
          $content_table.="</tr>";

         
         echo $content_table;


        }else  { echo "ACCESO DENEGADO"; }
    }
    else $this->seguridad->login_back();
  }

       public function get_all_gastos_por_tipo()
  {   
    if( isset($_SESSION['id_usuario8291']) ){
       $seguridad = new Class_seguridad();
       $tipoacceso = $seguridad->verificar_permiso($_SESSION['id_usuario8291'], $seguridad->clave_permiso('BALANCE_GENERAL'));
      if($tipoacceso > 0){

        $fecha_inicio=$this->request->getPost("fecha_inicio");
        $fecha_fin=$this->request->getPost("fecha_fin");
        $id_tipo_gasto=$this->request->getPost("id_tipo_gasto");

     
          $content_table="";
         $result_all_gasto_por_tipo = $this->model_reportes->get_all_gastos_por_tipo($fecha_inicio,$fecha_fin,$id_tipo_gasto);
         if($result_all_gasto_por_tipo){
  

            foreach ($result_all_gasto_por_tipo as $items) {
            ////
             $content_table.="<tr>";
             $content_table.="<td><div id='totales'>".$items->id."</div></td>";
             $content_table.="<td><div id='totales'>".$items->referencia."</div></td>";
             $content_table.="<td><div id='totales'>$ ".number_format($items->monto,2)."</div></td>";
             $content_table.="</tr>";
           }
         }
            
         echo $content_table;


        }else  { echo "ACCESO DENEGADO"; }
    }
    else $this->seguridad->login_back();
  }


}
