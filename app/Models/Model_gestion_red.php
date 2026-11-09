<?php

namespace App\Models;

use CodeIgniter\Model;

#[\AllowDynamicProperties]
class Model_gestion_red extends Model
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
    (SELECT count(DISTINCT ts.idcliente) from tblservicios ts LEFT JOIN usuarios2 us ON us.id=ts.idcliente WHERE us.estado<>'RETIRADO' AND ts.emisor=e.id) usuarios,
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
  public function get_emisor_edit($idemisor)
  {
    $builder = $this->db1->table('emisores');
    $builder->select('*');
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
  public function get_servers()
  {
    $builder = $this->db1->table('server s');
    $builder->select('s.velocidad,s.id,s.nodo,s.ip,IF(s.estado_netium="1","CONECTADO","DESCONECTADO") estado,s.port_web,s.modelo,s.version,(SELECT count(t.id) FROM tblservicios t LEFT JOIN usuarios2 u ON u.id=t.idcliente WHERE t.nodo=s.id and u.estado<>"RETIRADO") clientes');
    $builder->orderBy('s.id ASC');
    // Solo datos de las sucursales del usuario
    if (!\App\Libraries\FiltroSucursal::todas()) { $builder->where('s.id IN (SELECT idnodo FROM tblsucursalesnodo WHERE ' . \App\Libraries\FiltroSucursal::sql('idsucursal') . ')', null, false); }
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_server(String $idserver)
  {
    $builder = $this->db1->table('server s');
    $builder->select('s.port_web puerto_web,s.id,s.velocidad,s.nodo,s.coordenadas,s.ip,s.seguridad,s.seguridad2,s.secret_radius,s.ip_radius,s.usuario,s.contrasena,s.port_api puerto');
    $builder->where('id', $idserver);
    $query = $builder->get();
    return $query ? $query->getRow() : false;
  }
  public function update_server(String $id, array $data)
  {
    $builder = $this->db1->table('server');
    $builder->where('id', $id);
    $builder->update($data);
    $result = $this->db1->affectedRows();
    return $result > 0 ? true : false;
  }
  public function delete_server(String $id)
  {
    $builder = $this->db1->table('server');
    $builder->where('id', $id);
    return $builder->delete() ? true : false;
  }

  public function get_cliente_by_servicio($idservicio)
  {
    $builder = $this->db1->table('tblservicios t');
    $builder->select('u.nombre');
    $builder->join('usuarios2 u', "u.id = t.idcliente", "left");
    $builder->where('t.id', $idservicio);
    $query = $builder->get();
    return $query ? $query->getRow() : false;
  }
  public function get_cliente_by_ip($ip)
  {
    $builder = $this->db1->table('tblservicios t');
    $builder->select('u.nombre');
    $builder->join('usuarios2 u', "u.id = t.idcliente", "left");
    $builder->where('t.ip', $ip);
    $query = $builder->get();
    return $query ? $query->getRow() : false;
  }

  public function get_redes_ipv4()
  {
    $query = $this->db1->query('SELECT `i`.`id`, `i`.`nombre`, `i`.`red`, `i`.`cidr`, `i`.`tipo`, `i`.`rangos`, `i`.`host`, `s`.`nodo`, 0 `data`, (INET_ATON(SUBSTRING_INDEX(i.rangos, ",", 1))) rango1, (INET_ATON(SUBSTRING_INDEX(i.rangos, ",", -1))) rango2, (SELECT COUNT(t2.id) from tblservicios t2 left JOIN usuarios2 u ON u.id= t2.idcliente WHERE ( i.nodo=nodo and rango1<INET_ATON(t2.ip) and rango2>INET_ATON(t2.ip) )and( (u.estado<>"RETIRADO" AND t2.ip<>"") ) ) uso FROM `ipv4` `i` JOIN `server` `s` ON `s`.`id`=`i`.`nodo` ORDER BY `i`.`id` ASC');
    return $query ? $query->getResult() : false;
  }
  public function insert_new_red_ipv4($data)
  {
    $builder = $this->db1->table('ipv4');
    $builder->insert($data);
    $result = $this->db1->affectedRows();
    $id = $this->db1->insertID();
    return $result ? $id : false;
  }
  public function get_red_ipv4(int $id_ipv4)
  {
    $query = $this->db1->query('SELECT *, (INET_ATON(SUBSTRING_INDEX(rangos, ",", 1))) rango1, (INET_ATON(SUBSTRING_INDEX(rangos, ",", -1))) rango2 FROM `ipv4` WHERE `id` =' . $id_ipv4);
    return $query ? $query->getRow() : false;
  }
  public function update_red_ipv4($id, $data)
  {
    $builder = $this->db1->table('ipv4');
    $builder->where('id', $id);
    $builder->update($data);
    $result = $this->db1->affectedRows();
    return $result > 0 ? true : false;
  }
  public function get_users_red_ipv4($id, $rango1, $rango2)
  {
    $builder = $this->db1->table('ipv4 i');
    $builder->select('count(u.id) usuarios');
    $builder->join('tblservicios t', "i.nodo=t.nodo", "left");
    $builder->join('usuarios2 u', "u.id=t.idcliente", "left");
    $builder->groupStart();
    $builder->where('u.estado', "ACTIVO");
    $builder->orGroupStart();
    $builder->where('u.estado', "SUSPENDIDO");
    $builder->groupEnd();
    $builder->groupEnd();
    $builder->where('t.nodo', $id);
    $builder->where('INET_ATON(t.ip)>', $rango1);
    $builder->where('INET_ATON(t.ip)<', $rango2);
    $query = $builder->get();
    return $query ? $query->getRow() : false;
  }
  public function delete_red_ipv4($id)
  {
    $builder = $this->db1->table('ipv4');
    $builder->where('id', $id);
    return $builder->delete() ? true : false;
  }
  public function new_emisor($data){
    $builder = $this->db1->table('emisores');
    $builder->insert($data);
    $result = $this->db1->affectedRows();
    $id = $this->db1->insertID();
    return $result ? $id : false;
  }
  public function save_new_server($data)
  {
    $builder = $this->db1->table('server');
    $builder->insert($data);
    $result = $this->db1->affectedRows();
    $id = $this->db1->insertID();
    return $result ? $id : false;
  }
}
