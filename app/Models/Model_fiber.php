<?php

namespace App\Models;

use CodeIgniter\Model;

#[\AllowDynamicProperties]
class Model_fiber extends Model
{
  var $db1;
  function __construct()
  {
    $db1 = \Config\Database::connect('default');
    $this->db1 = $db1;
  }
  public function get_emisores()
  {
    $builder = $this->db1->table('emisores e');
    $builder->select("e.id,e.nombre,e.ip,estado_netium,e.equipo,
    (SELECT count(ts.id) from tblservicios ts WHERE ts.emisor=e.id) servicios,
    (SELECT count(ts.id) from tblservicios ts WHERE ts.status_user='ONLINE' and ts.emisor=e.id) online,
    (SELECT count(ts.id) from tblservicios ts WHERE ts.status_user='OFFLINE' and ts.emisor=e.id) offline,
    (SELECT count(DISTINCT ts.idcliente) from tblservicios ts LEFT JOIN usuarios2 us ON us.id=ts.idcliente WHERE us.estado<>'RETIRADO' AND ts.emisor=e.id) usuarios2,
    (SELECT count(DISTINCT ts.idcliente) from tblservicios ts LEFT JOIN usuarios2 us ON us.id=ts.idcliente WHERE us.estado='ACTIVO' and ts.emisor=e.id) activos,
    (SELECT count(DISTINCT ts.idcliente) from tblservicios ts LEFT JOIN usuarios2 us ON us.id=ts.idcliente WHERE us.estado='SUSPENDIDO' and ts.emisor=e.id) suspendidos");
    $builder->orderBy('e.id ASC');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_emisor($idemisor)
  {
    $builder = $this->db1->table('emisores');
    $builder->select('ip,nombre');
    $builder->where('id', $idemisor);
    $query = $builder->get();
    return $query ? $query->getRow() : false;
  }
  public function update_emisor($id, $data)
  {
    $builder = $this->db1->table('emisores');
    $builder->where('id', $id);
    $builder->update($data);
    $result = $this->db1->affectedRows();
    return $result > 0 ? true : false;
  }
  public function delete_emisor(String $idemisor)
  {
    $builder = $this->db1->table('emisores');
    $builder->where('id', $idemisor);
    return $builder->delete() ? true : false;
  }
  public function get_olt()
  {
   $builder = $this->db1->table('olt o');
    $builder->select('o.*,m.modelo,m.imagen as image_olt');
    $builder->join('olt_model m','m.id = o.modelo','left');
    $builder->orderBy('o.id ASC');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_olt_id(String $idserver){
    $builder = $this->db1->table('olt o');
    $builder->select('o.*,m.modelo as modelo_name,m.imagen as image_olt');
    $builder->join('olt_model m','m.id = o.modelo','left');
    $builder->where('o.id',$idserver);
    $query = $builder->get();
  return $query ? $query->getRow() : false;
  }
  public function get_image_model($id){
    $builder = $this->db1->table('olt_model');
    $builder->select('*');
    $builder->where('id',$id);
    $query = $builder->get();
  return $query ? $query->getRow() : false;
  }
  public function update_olt(String $id,Array $data){
    $builder = $this->db1->table('olt');
    $builder->where('id', $id);
    $result=$builder->update($data);
    if($result){ return true;}
    else{ return false;}
    
  }
    public function update_onu_sistema($id,$data){
    $builder = $this->db1->table('olt_onus');
    $builder->where('id', $id);
    $result=$builder->update($data);
    if($result){ return true;}
    else{ return false;}
    
  }

      public function update_onu_id_tmp($idpon,$idonu,$data){
    $builder = $this->db1->table('olt_onus');
    $builder->where('idpon', $idpon);
    $builder->where('idonu', $idonu);
    $result=$builder->update($data);
    if($result){ return true;}
    else{ return false;}
    
  }
  public function delete_gpon_olt(String $id){
    $builder = $this->db1->table('gpon_port_olt');
    $builder->where('idolt', $id);
    return $builder->delete() ? true : false;
  }

   public function insert_gpon_olt($data){
    $builder = $this->db1->table('gpon_port_olt');
    $result=$builder->insert($data);
    if($result){ return true;}
    else{ return false;}
    
  }
  public function get_port_olt($idolt)
  {
    $builder = $this->db1->table('gpon_port_olt');
    $builder->select('*');
    $builder->where('idolt', $idolt);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }

  public function get_port_id($idpon)
  {
    $builder = $this->db1->table('gpon_port_olt');
    $builder->select('*');
    $builder->where('idpon', $idpon);
    $query = $builder->get();
    return $query ? $query->getRow() : false;
  }
  public function get_modelo_by_svr_profile($srv_profile)
  {
    $builder = $this->db1->table('gpon_model_service_profile');
    $builder->select('*');
    $builder->where('srv_profile', $srv_profile);
    $query = $builder->get();
    return $query ? $query->getRow() : false;
  }

   public function get_line_profile_id($idolt)
  {
    $builder = $this->db1->table('olt_line_profile');
    $builder->select('*');
    $builder->where('idolt', $idolt);
    $query = $builder->get();
    return $query ? $query->getRow() : false;
  }
   public function insert_onu_olt($data){
    $builder = $this->db1->table('olt_onus');
    $result=$builder->insert($data);
    if($result){ return true;}
    else{ return false;}
    
  }

   public function get_onus_autorize($estado)
  {
    $builder = $this->db1->table('olt_onus o');
    $builder->select('o.*,ol.nombre as olt,u.nombre');
    $builder->join('olt ol','ol.id = o.idolt','left');
    $builder->join('usuarios2 u','u.id = o.idcliente','left');
    if($estado=="ALL"){}
    if($estado=="ONLINE"){$builder->where("o.estado",$estado);}
    if($estado=="OFFLINE"){$builder->where("o.estado",$estado);}
    // ONUs de clientes de otras sucursales no se muestran; las que aún no tienen
    // cliente (pendientes de asignar) sí, porque las OLT no pertenecen a una sucursal.
    if (!\App\Libraries\FiltroSucursal::todas()) {
      $builder->where('(o.idcliente IS NULL OR o.idcliente = 0 OR ' . \App\Libraries\FiltroSucursal::sql('u.sucursal') . ')', null, false);
    }
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }

     public function get_onu_id($id)
  {
    $builder = $this->db1->table('olt_onus o');
    $builder->select('o.*,ol.nombre as olt,t.idtrafic,t.trafic,u.nombre,g.imagen');
    $builder->join('olt ol','ol.id = o.idolt','left');
    $builder->join('olt_trafic t','t.idtrafic = o.trafic_table','left');
    $builder->join('usuarios2 u','u.id = o.idcliente','left');
    $builder->join('gpon_model_service_profile g','g.srv_profile = o.srv_profile','left');
    $builder->where("o.id",$id);  
    $query = $builder->get();
    return $query ? $query->getRow() : false;
  }


     public function delete_onu($id)
  {
        $builder = $this->db1->table('olt_onus');
        $builder->where('id', $id);
        $builder->delete();
        $result = $this->db1->affectedRows();
        return $result > 0 ? true : false;
  }

     public function update_estado_onu($id,$estado)
  {
        $builder = $this->db1->table('olt_onus');
        $builder->where('id', $id);
        $builder->update(array("estado"=>$estado));
        $result = $this->db1->affectedRows();
        return $result > 0 ? true : false;
  }

  public function get_onus_en_sistema(){
      $builder = $this->db1->table('olt_onus');
      $builder->select('count(id) as total');
      $query = $builder->get();
      if($query) {$row=$query->getRow(); $total=$row->total; return $total;}
      else{return 0;}
      //return $query ? $query->getRow() : false;
    }

    public function get_onus_autorizadas_hoy(){
      $inicio=date("Y-m-d");
      $builder = $this->db1->table('olt_onus');
      $builder->select('count(id) as total');
      $builder->where("fecha like '%".$inicio."%'");
      $query = $builder->get();
      if($query) {$row=$query->getRow(); $total=$row->total; return $total;}
      else{return 0;}
    }
      public function get_onus_autorizadas_mes(){
        $month=date("m");$year=date("Y"); 
        $ultimo_dia=date("d",(mktime(0,0,0,$month+1,1,$year)-1));
        $inicio=date("Y")."-".date("m")."-01 00:00:00";
        $fin=date("Y")."-".date("m")."-".$ultimo_dia." 23:59:59";

      $builder = $this->db1->table('olt_onus');
      $builder->select('count(id) as total');
      $builder->where("fecha between '".$inicio."' and '".$fin."' ");
      $query = $builder->get();
      if($query) {$row=$query->getRow(); $total=$row->total; return $total;}
      else{return 0;}
    }
    public function save_new_olt($data){
      $builder = $this->db1->table('olt');
      $builder->insert($data);
      $result = $this->db1->affectedRows();
      $id = $this->db1->insertID();
      return $result ? $id : false;
    }
}
