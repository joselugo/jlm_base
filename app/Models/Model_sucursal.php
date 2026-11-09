<?php

namespace App\Models;

use CodeIgniter\Model;

#[\AllowDynamicProperties]
class Model_sucursal extends Model
{
  public function __construct()
  {
    $db1 = \Config\Database::connect('default');
    $this->db1 = $db1;
  }
  public function get_sucursales(){
    $builder = $this->db1->table('sucursal');
    $builder->select('id,sucursal,direccion,ubicacion,telefono,rfc');
    $builder->orderBy('id ASC');
    $query = $builder->get();
  return $query ? $query->getResult() : false;
  }
  public function get_sucursal($idsucursal){
    $builder = $this->db1->table('sucursal');
    $builder->select('*');
    $builder->where('id',$idsucursal);
    $query = $builder->get();
  return $query ? $query->getResult() : false;
  }
  public function update_sucursal($id, $data){
    $builder = $this->db1->table('sucursal');
    $builder->where('id', $id);
    $builder->update($data);
    $result = $this->db1->affectedRows();
    return $result > 0 ? true : false;
  }
    public function new_sucursal($data){
    $builder = $this->db1->table('sucursal');
    $builder->insert($data);
    $result = $this->db1->affectedRows();
    $id = $this->db1->insertID();
    return $result ? $id : false;
  }
  public function get_sucursal_nodos($idsucursal){
    $builder = $this->db1->table('tblsucursalesnodo');
    $builder->select('idnodo');
    $builder->where('idsucursal',$idsucursal);
    $builder->orderBy('id ASC');
    $query = $builder->get();
  return $query ? $query->getResult() : false;
  }
}
