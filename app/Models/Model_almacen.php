<?php

namespace App\Models;

use CodeIgniter\Model;

#[\AllowDynamicProperties]
class Model_almacen extends Model
{
  function __construct()
  {
    $db1 = \Config\Database::connect('default');
    $this->db1 = $db1;
  }

  //----------------------Proveedores ------------------------------

  public function get_proveedores()
  {
    $builder = $this->db1->table('proveedores');
    $builder->select('*');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }

  public function create_proveedor($data)
  {
    $builder = $this->db1->table('proveedores');
    $builder->insert($data);
    $result = $this->db1->affectedRows();
    return ($result > 0) ? true : false;
  }

  public function update_proveedor($id, $data)
  {
    $builder = $this->db1->table('proveedores');
    $builder->where('id', $id);
    $builder->update($data);
    $result = $this->db1->affectedRows();
    return $result > 0 ? true : false;
  }
  public function delete_proveedor($id)
  {
    $builder = $this->db1->table('proveedores');
    $builder->where('id', $id);
    return $builder->delete() ? true : false;
  }

  //----------------------Tipos productos------------------------------

  public function get_tipos_producto($where)
  {
     $builder = $this->db1->table('productos p');
     $builder->select("p.id,p.clave_invoice as clave,p.producto,p.descripcion,p.medida,p.tipo,p.impuesto,SUM(CASE WHEN a.estado = 'disponible' THEN a.cantidad END) AS disponibles");
     $builder->join('almacen a', 'a.productoid = p.id', 'left');
    // $builder->where($where);
     $builder->groupBy('p.id');
     $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function create_new_tipo_producto($data)
  {
    $builder = $this->db1->table('productos');
    $builder->insert($data);
    $result = $this->db1->affectedRows();
    return ($result > 0) ? true : false;
  }
  public function delete_tipo_producto($id)
  {
    $builder = $this->db1->table('productos');
    $builder->where('id', $id);
    return $builder->delete() ? true : false;
  }
  public function update_tipo_producto($id, $data)
  {
    $builder = $this->db1->table('productos');
    $builder->where('id', $id);
    $builder->update($data);
    $result = $this->db1->affectedRows();
    return $result > 0 ? true : false;
  }

  //----------------------Productos------------------------------
  public function get_productos($estado, $where)
  {
    $builder = $this->db1->table('almacen a');
    $builder->select('u.nombre usuario, pv.proveedor,p.producto nombre,a.*');
    $builder->join('productos p', 'a.productoid=p.id', 'left');
    $builder->join('proveedores pv', 'a.idproveedor=pv.id', 'left');
    $builder->join('usuarios2 u', 'u.id=a.userid', 'left');
    if ($estado <> "") {
      $builder->where('a.estado', $estado);
    }
    $builder->where('catid', 'producto');
    $builder->where('p.tipo', 'producto');
    $builder->where($where);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }

  public function get_productos_agrupado()
  {

    $builder = $this->db1->table('almacen a');
    $builder->select('p.producto,p.id');
    $builder->join('productos p', 'a.productoid=p.id', 'left');
    $builder->join('proveedores pv', 'a.idproveedor=pv.id', 'left');
    $builder->join('usuarios2 u', 'u.id=a.userid', 'left');
    $builder->where('catid', 'producto');
    $builder->groupBy('p.producto');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function create_producto($data)
  {
    $builder = $this->db1->table('almacen');
    $builder->insert($data);
    $result = $this->db1->affectedRows();
    return $result > 0 ? true : false;
  }

  public function get_accesorios($where)
  {
    $builder = $this->db1->table('almacen a');
    $builder->select('a.id,a.cantidad,p.producto,a.catid,a.costo');
    $builder->join('productos p', 'p.id=a.productoid');
    $builder->where('catid', 'accesorio');
    $builder->where('p.tipo', 'accesorio');
    $builder->where($where);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function retira_prod($id, $data)
  {
    $builder = $this->db1->table('almacen');
    $builder->where('id', $id);
    $builder->update($data);
    $result = $this->db1->affectedRows();
    return $result > 0 ? true : false;
  }
  public function get_producto($id)
  {
    $builder = $this->db1->table('almacen a');
    $builder->select('a.*,p.id proveedorid,p.producto,p.costo productocosto,p.medida,p.tipo,p.impuesto,p.clave_invoice');
    $builder->join('productos p', 'a.productoid=p.id', 'left');
    $builder->join('proveedores pv', 'a.idproveedor=pv.id', 'left');
    $builder->where('a.id', $id);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }

  public function update_producto($id, $data)
  {
    $builder = $this->db1->table('almacen');
    $builder->where('id', $id);
    $builder->update($data);
    $result = $this->db1->affectedRows();
    return $result > 0 ? true : false;
  }
  public function delete_producto($id)
  {
    $builder = $this->db1->table('almacen');
    $builder->where('id', $id);
    return $builder->delete() ? true : false;
  }
  public function insert_batch_productos($data)
  {
    $builder = $this->db1->table('almacen');
    $builder->insertBatch($data);
    $result = $this->db1->affectedRows();
    return ($result > 0) ? true : false;
  }
  public function consulta_existencia_almacen($key, $key2)
  {
    $builder = $this->db1->table('almacen');
    $builder->where('mac_producto', $key2);
    $builder->orWhere('serial_producto', $key);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
}
