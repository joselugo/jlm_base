<?php

namespace App\Controllers;

use PEAR2\Net\RouterOS;
use CodeIgniter\Controller;
use App\Libraries\Class_seguridad;
use App\Models\Model_dashboard;

class Dashboard extends BaseController
{
  var $seguridad;
  var $model_gestion_red;
  var $model_dashboard;
  public function __construct()
  {
    /* $this->model_facturacion = new Model_finanzas_facturacion();
    $this->almacen = new Model_almacen();
    $this->model_selectores = new Model_selectores();
    $this->model_log = new Model_log();
    $this->permiso_sucursal = new Class_permiso_sucursal();
    $this->finanzas = new Model_finanzas();
    $this->formatos = new Model_formatos();
    $this->calcular = new Calcular(); */
    $this->seguridad = new Class_seguridad();
    $this->model_dashboard = new Model_dashboard();
    date_default_timezone_set('America/Mexico_City');
    session();
  }
  public function home(){
    if ($this->seguridad->Acceso('DASHBOARD', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $emisores_body="";
      $usuario=$this->model_dashboard->get_nombre_user($_SESSION['id_usuario8291']);
      $emisores = $this->model_dashboard->get_emisores();
      if ($usuario) {
        $data['nombre']=$usuario->username;
        if ($emisores) {
          foreach ($emisores as $value) {
            $id = $value->id;
            $nombre = $value->nombre;
            $ip = $value->ip;
            $equipo = $value->equipo;
            $estado = $value->estado_netium == 1 ? 'EN LINEA' : 'DESCONECTADO';
            $style = $value->estado_netium == 1 ? 'success' : 'danger';
            $emisores_body .= "
            <tr>
              <td tabindex=\"0\"><a href=\"#gestion_red/editar_emisor?idemisor=$id\" data-toggle=\"ajax\" style=\"color:#333\">$nombre</a></td>
              <td style=\"\">$equipo</td>
              <td style=\"\"><a href=\"http://$ip\" target=\"_Blank\">$ip</a></td>
              <td class=\"sorting_1\"><span class=\"label label-pill label-$style\">$estado</span></td>
              <td><a style=\"margin-right: 5px;\" data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"getmodal('gestion_red/modal_grafica_ping?idemisor=$id','Grafica','lg')\" data-original-title=\"Gráfico ping\"><i class=\"fas fa-chart-bar\"></i></a></td>
            </tr>";
          }
          $data['emisores']=$emisores_body;
        } else {
          $data['emisores']=array();
        }
        return view('dashboard/home',$data);
      }else{
        echo "No existe tu nombre de usuario en la base de datos.";
      }
    }
  }
  public function get_data(){
  $permisos=[
    "WIDGET CLIENTES_ONLINE"=>false,
    "WIDGET TRANSACCIONES"=>false,
    "WIDGET FACTURAS NO PAGADAS"=>false,
    "WIDGET TICKETS SOPORTE"=>false,
    "WIDGET TRAFICO DE CLIENTES"=>false,
    "WIDGET RESUMEN DEL SISTEMA"=>false,
    "WIDGET ULTIMOS PAGOS"=>false,
    "WIDGET ULTIMOS CLIENTES CONECTADOS"=>false,
    "WIDGET DATOS DEL SERVIDOR"=>false,
    "WIDGET RESUMEN DE FACTURACION"=>false,
    "WIDGET EMISORES"=>false
  ];
    $permiso_dashboard=false;
    foreach ($permisos as $key => $value) {
      $permiso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso("$key"));
      if ($permiso!=0) {
        $permiso_dashboard=true;
        $permisos[$key]=true;
      }
    }

    if ($permiso_dashboard) {
      
      $tmp = array();
      $ultimos_pagos_body="";
      $ultimos_conectados_body="";
      $emisores_body="";
      $hoy=date('Y-m-d');
      // sys_getloadavg() y /proc solo existen en Linux (en Windows el widget muestra 0)
      $avg=function_exists('sys_getloadavg') ? sys_getloadavg() : [0, 0, 0];
      $memoria_total=disk_total_space("/");
      $memoria_libre=disk_free_space("/");
      $memoria=$this->getSystemMemInfo();
      $activos=$this->model_dashboard->get_clientes_activos();
      $pagoshoy=$this->model_dashboard->get_pagoshoy(date('Y'), date('m'),date('d'));
      $facturas=$this->model_dashboard->facturas_no_pagadas();
      $soporte=$this->model_dashboard->soporte_activo();
      $servicios=$this->model_dashboard->servicios_registrados();
      $pagos_mes=$this->model_dashboard->pagos_del_mes(date('Y'),date('m'));

      $facturas_vencidas=$this->model_dashboard->get_facturas_vencidas($hoy);
      $soporte_abierto=$this->model_dashboard->soporte_abierto();
      $routers_activos=$this->model_dashboard->routers_activos();
      $routers_desconectados=$this->model_dashboard->routers_desconectados();
      $clientes_activos=$this->model_dashboard->clientes_activos();
      $clientes_suspendidos=$this->model_dashboard->clientes_suspendidos();
      // Antes $online nunca se calculaba (el widget mostraba siempre 0)
      $online=$this->model_dashboard->get_clientes_online();
      $emisores_activos=$this->model_dashboard->emisores_activos();
      $emisores_caidos=$this->model_dashboard->emisores_caidos();
      $ultimos_pagos=$this->model_dashboard->ultimos_pagos();
      $ultimos_conectados=$this->model_dashboard->ultimos_conectados();
      $facturas_mes=$this->model_dashboard->facturas_mes(date('Y'),date('m'));
      $facturas_mes_sin_pagar=$this->model_dashboard->facturas_mes_sin_pagar(date('Y'),date('m'));
      $pagos_mes_pasado=$this->model_dashboard->pagos_del_mes(date('Y'), date('m', strtotime('-1 month')));
      $facturas_mes_pasado=$this->model_dashboard->facturas_mes(date('Y'), date('m', strtotime('-1 month')));
      $facturas_mes_sin_pagar_mes_pasado=$this->model_dashboard->facturas_mes_sin_pagar(date('Y'), date('m', strtotime('-1 month')));

      if ($ultimos_pagos) {
        foreach ($ultimos_pagos as $key => $value) {
          $cobrado=$value->cobrado;
          $cliente=$value->cliente;
          $operador=$value->nombre;
          $idcliente=$value->idcliente;
          $fecha=$value->fecha;
          $ultimos_pagos_body.="
          <tr>
            <td class=\"text-ellipsis\"><a href=\"#clientes/detalleuser?id=$idcliente\" class=\"text-center\" style=\"color:#333\">$cliente</a></td>
            <td>MX$$cobrado</td>
            <td>$operador</td>
            <td id=\"pago_$key\">...</td>
          </tr>
            <script>
              var pago_$key=document.getElementById('pago_$key');
              pago_$key.innerHTML=moment(\"$fecha\").fromNow()
            </script>
          ";     
	   }
      }else{
        $ultimos_pagos_body="";
      }
      if ($ultimos_conectados) {
        foreach ($ultimos_conectados as $key => $value) {
          $nombre=$value->nombre;
          $idcliente=$value->idcliente;
	  $fecha=$value->fecha;
          $ultimos_conectados_body.= "
         <li>
          <a href=\"#clientes/detalleuser?id=$idcliente\" class=\"text-center\"><img data-name=\"$nombre\" ></a>
          <h4 class=\"username text-ellipsis text-center\">
            $nombre
            <small class=\"text-center\" id=\"conectado_$key\">Hace ...</small>
          </h4>
         </li>
          <script>
              var conectado_$key=document.getElementById(\"conectado_$key\");
              conectado_$key.innerHTML=moment(\"$fecha\").fromNow()
          </script>";
        }
      }else{
        $ultimos_conectados_body="";
      }
      // SUM() devuelve NULL si no hay pagos
      $pagos=(float) ($pagoshoy->pagos ?? 0);
      $pagosmes=(float) ($pagos_mes->pagos ?? 0);
      $pagos_format= number_format($pagos);
      $pagosmes_format= number_format($pagosmes);

      if($permisos['WIDGET CLIENTES_ONLINE']){
      $tmp['online']=$activos?$activos->activos:0;
      $tmp['registrados']=$servicios?$servicios->servicios:0;
      if($tmp['registrados']==0){$w1=0;}
      else{ $w1=($tmp['online'] * 100) / $tmp['registrados'];}
      $tmp['w1']="$w1".'%';
      }

      if($permisos['WIDGET TRANSACCIONES']){
      $tmp['pagoshoy']='MX$'."$pagos_format";
      $tmp['pagosmes']='MX$'."$pagosmes_format";
      if($pagosmes==null){
      	$w2=0;
      }else{
        $w2=($pagos * 100) / $pagosmes;
      }
      
      $tmp['w2']="$w2".'%';
      }
      if($permisos['WIDGET FACTURAS NO PAGADAS']){
      $tmp['facturas']=$facturas?$facturas->facturas:0;
      $tmp['vencidas']=$facturas_vencidas?$facturas_vencidas->vencidas:0;
      if($tmp['vencidas']==0){$w3=0;}
      else{  $w3=($tmp['vencidas']* 100) / $tmp['facturas'];}
     
      $tmp['w3']="$w3".'%';
      }
      if($permisos['WIDGET TICKETS SOPORTE']){
      $tmp['soporte_activo']=$soporte?$soporte->ticket:0;
      $tmp['soporte_abierto']= $soporte_abierto? $soporte_abierto->ticket:0;
       if($tmp['soporte_abierto']==0){$w4=0;}
      else{$w4=($tmp['soporte_activo']* 100) / $tmp['soporte_abierto'];}
      
      $tmp['w4']="$w4".'%';
      }
      if($permisos['WIDGET DATOS DEL SERVIDOR']){         
      $tmp['r1']=$routers_activos->activos;
      $tmp['r2']=$routers_desconectados->desconectados;
      $tmp['r3']=$clientes_activos->activos;
      $tmp['r4']=$clientes_suspendidos->suspendidos;
      $tmp['r5']=$online ? $online->online : 0;
      $tmp['r6']=$emisores_activos->activos;
      $tmp['r7']=$emisores_caidos->caidos;
      }

      if($permisos['WIDGET ULTIMOS PAGOS']){
      $tmp['ultimospagos']=$ultimos_pagos_body;
      }
      if($permisos['WIDGET ULTIMOS CLIENTES CONECTADOS']){
      $tmp['ultimosconectados']=$ultimos_conectados_body;
      }
      if($permisos['WIDGET RESUMEN DE FACTURACION']){
      $tmp['pagosmestotal']=$pagos_mes->total_pagos;
      $tmp['facturasmes']=$facturas_mes->facturas;
      $tmp['facturastotalmes']="MX$$facturas_mes->total_facturas";
      $tmp['facturasmes2']=$facturas_mes_sin_pagar->facturas;
      $tmp['facturastotalmes2']="MX$$facturas_mes_sin_pagar->total_facturas";
      $tmp['pagosmestotalpasado']=$pagos_mes_pasado->total_pagos;
      $tmp['pagosmespasado']="MX$$pagos_mes_pasado->pagos";
      $tmp['facturasmespasado'] = $facturas_mes_pasado->facturas;
      $tmp['facturastotalmespasado'] = "MX$$facturas_mes_pasado->total_facturas";
      $tmp['facturasmespasado2'] = $facturas_mes_sin_pagar_mes_pasado->facturas;
      $tmp['facturastotalmespasado2'] = "MX$$facturas_mes_sin_pagar_mes_pasado->total_facturas";
      }
      if($permisos['WIDGET RESUMEN DEL SISTEMA']){
      $tmp['totalmemoria']= round($memoria['MemTotal'],2)." GB";
      $total_mem = $memoria['MemTotal'] ?: 1; // sin /proc/meminfo vale 0: evita división entre cero
      $tmp['memorialibre'] = round(($memoria['MemAvailable']*100)/$total_mem,2)."%";
      $tmp['memoriausada'] =round((($memoria['MemTotal']-$memoria['MemAvailable'])*100/$total_mem),2)."%";
      $tmp['totaldisco'] =round($memoria_total/1073741824,2);
      $tmp['discolibre'] = round((($memoria_libre*100)/$memoria_total),2)."%";
      $tmp['discousado'] = round(((($memoria_total-$memoria_libre)*100)/$memoria_total),2)."%";
      $tmp['cpu'] =$load = $avg[0]."%";
      $tmp['carga'] = implode(",",$avg);
      $tmp['cores'] = $this->get_processor_cores_number();
      }
      return json_encode($tmp);
    }
  }
 public function trafico()
  {
    if ($this->seguridad->Acceso('WIDGET TRAFICO DE CLIENTES', 'LECTURA', $_SESSION['id_usuario8291'])) {
      $hoy = date('d-m-Y');
      $data_grafico = array();
      $fechas = array();
      for ($i = 6; $i >= 0; $i--) {
        $fechas[] = date('Y-m-d', strtotime($hoy . "- $i days"));
      }
      //$trafico_total = $this->model_dashboard->get_total_trafico_ultimos_7_dias($fechas);
      $traf = $this->model_dashboard->get_data_grafico($fechas);
      $unGb=pow(1024,4);
      $cuantos=count($traf)-1;
      foreach ($traf as $value) {

        $value->tx = round($value->tx/$unGb, 2);
        $value->rx = round($value->rx/$unGb, 2);
        $data_grafico[] =$value;
      }
      if(count($data_grafico)==0){
        $rx=0; $tx=0;
      }
      else if(count($data_grafico)==7){
        $rx=$data_grafico[6]->rx; $tx=$data_grafico[6]->tx;
      }
      else{$rx=$data_grafico[$cuantos]->rx; $tx=$data_grafico[$cuantos]->tx;} 
    
      $data['data_grafico'] = $data_grafico;
      $data['descarga'] = $tx;
      $data['subida'] = $rx;
      $total_trafico = $tx + $rx;
      $data['trafico_total'] = round($total_trafico,2);

      if($rx!=0 && $tx!=0){
       $data['porcentaje_descarga'] = round($tx * 100 / $total_trafico,1);
      $data['porcentaje_subida'] = round($rx * 100 / $total_trafico,1);
      }
      else{
        $data['porcentaje_descarga'] = 0;
        $data['porcentaje_subida'] = 0;
      }


       return json_encode($data);
    }
  }
  protected function espace($Frase)
  {
    return preg_replace("/\s+/", " ", trim($Frase));
  }
  protected function get_processor_cores_number()
  {
    if (! is_readable('/proc/cpuinfo')) {
      return 0;
    }
    // Antes: shell_exec("cat /proc/cpuinfo | grep processor | wc -l")
    return preg_match_all('/^processor\s*:/m', (string) file_get_contents('/proc/cpuinfo'));
  }
  // return GB
  protected function getSystemMemInfo()
  {
    if (! is_readable('/proc/meminfo')) {
      return ['MemTotal' => 0, 'MemFree' => 0, 'MemAvailable' => 0];
    }
    $data = explode("\n", file_get_contents("/proc/meminfo"));
    $meminfo = array();
    for ($i=0; $i <3 ; $i++) {
      $string= explode(":", $data[$i]);
      $kb=str_replace("kb","",$string[1]);
      $meminfo[$string[0]]=intval(trim($kb))/1048576;
    }
    return $meminfo;
  }
}