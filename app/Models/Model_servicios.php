<?php

namespace App\Models;

use CodeIgniter\Model;

#[\AllowDynamicProperties]
class Model_servicios extends Model
{
  var $db;
  public function __construct()
  {
    $db1 = \Config\Database::connect('default');
    $this->db1 = $db1;
  }
  public function get_planes(array $sucursales, bool $todo)
  {
    $builder = $this->db1->table('perfiles p');
    $builder->select('p.id,p.plan,p.velocidad,p.profile,p.costo,p.costo_prepago,
    (Select Count(t.id) from tblservicios t left join usuarios2 u on u.id=t.idcliente where idperfil=p.id and u.estado="ACTIVO") activo,
    (Select Count(t.id) from tblservicios t left join usuarios2 u on u.id=t.idcliente where idperfil=p.id and u.estado="SUSPENDIDO") suspendido,
    (Select Count(t.id) from tblservicios t left join usuarios2 u on u.id=t.idcliente where idperfil=p.id and u.estado="RETIRADO") retirado');
    $builder->join('tblsucursalesnodo t', 't.idnodo=p.id_mikrotik', 'left');
    if (!$todo) {
      for ($i = 0; $i < count($sucursales); $i++) {
        if ($i == 0) {
          $builder->where('t.idsucursal', $sucursales[$i]);
        } else {
          $builder->OrWhere('t.idsucursal', $sucursales[$i]);
        }
      }
      $builder->orWhere('p.id_mikrotik', "%%");
    }

    $builder->groupBy('p.id');
    $builder->orderBy('p.id ASC');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function delete_plan(String $id)
  {
    $builder = $this->db1->table('perfiles');
    $builder->where('id', $id);
    return $builder->delete() ? true : false;
  }
  public function get_sucursales_nodo($idusuario)
  {
    $builder = $this->db1->table("login");
    $builder->select('sucursal');
    $builder->where('id', $idusuario);
    $query = $builder->get();
    if ($query) {

      $data = array();
      $fila = $query->getRow();
      if ($fila->sucursal == '%%') {
        $data['datos'] = "";
        $data['todos'] = true;
        return $data;
      } else {
        $data['datos'] = explode(",", $fila->sucursal);
        $data['todos'] = false;
        return $data;
      }
    } else {
      return false;
    }
  }
  public function get_plan(String $idplan)
  {
    $builder = $this->db1->table('perfiles');
    $builder->select('*');
    $builder->where('id', $idplan);
    $query = $builder->get();
    return $query ? $query->getRow() : false;
  }
  public function update_plan(String $id, array $data)
  {
    $builder = $this->db1->table('perfiles');
    $builder->where('id', $id);
    $builder->update($data);
    $result = $this->db1->affectedRows();
    return $result > 0 ? true : false;
  }
  public function new_plan(array $data)
  {
    $builder = $this->db1->table('perfiles');
    $builder->insert($data);
    $result = $this->db1->affectedRows();
    $id = $this->db1->insertID();
    return $result ? $id : false;
  }
  public function get_servicios_voz()
  {
    $builder = $this->db1->table('perfilesvoip p');
    $builder->select('p.id,p.plan nombre,p.minutos,p.costo,
    (SELECT COUNT(t.id) FROM tblserviciosvoip t LEFT JOIN usuarios2 u ON u.id=t.cliente WHERE t.idperfil=p.id AND u.estado="ACTIVO") activo,
    (SELECT COUNT(t.id) FROM tblserviciosvoip t LEFT JOIN usuarios2 u ON u.id=t.cliente WHERE t.idperfil=p.id AND u.estado="SUSPENDIDO") suspendido,
    (SELECT COUNT(t.id) FROM tblserviciosvoip t LEFT JOIN usuarios2 u ON u.id=t.cliente WHERE t.idperfil=p.id AND u.estado="RETIRADO") retirado');
    $builder->orderBy('id ASC');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function delete_perfil_voip(String $id)
  {
    $builder = $this->db1->table('perfilesvoip');
    $builder->where('id', $id);
    return $builder->delete() ? true : false;
  }
  public function new_perfil_voip($data)
  {
    $builder = $this->db1->table('perfilesvoip');
    $builder->insert($data);
    $result = $this->db1->affectedRows();
    $id = $this->db1->insertID();
    return $result ? $id : false;
  }
  public function get_perfil_voip(String $id)
  {
    $builder = $this->db1->table('perfilesvoip');
    $builder->select('*');
    $builder->where('id', $id);
    $query = $builder->get();
    return $query ? $query->getRow() : false;
  }
  public function update_perfil_voip($id, $data)
  {
    $builder = $this->db1->table('perfilesvoip');
    $builder->where('id', $id);
    $builder->update($data);
    $result = $this->db1->affectedRows();
    return $result > 0 ? true : false;
  }
  public function get_productos_otros()
  {
    $builder = $this->db1->table('productos p');
    $builder->select('p.producto,p.costo,p.impuesto,p.id,
    (
      (SELECT COUNT(u.id) FROM tmpfactura u LEFT JOIN usuarios2 us ON us.id=u.userid WHERE u.productoid=p.id AND us.estado="ACTIVO" AND u.state="pendiente")
      +
      (SELECT COUNT(r.id) FROM alquileres r LEFT JOIN usuarios2 us ON us.id=r.userid WHERE r.productoid=p.id AND us.estado="ACTIVO" AND r.state="activo")
    ) activo,
    (
      (SELECT COUNT(u.id) FROM tmpfactura u LEFT JOIN usuarios2 us ON us.id=u.userid WHERE u.productoid=p.id AND us.estado="SUSPENDIDO" AND u.state="pendiente")
      +
      (SELECT COUNT(r.id) FROM alquileres r LEFT JOIN usuarios2 us ON us.id=r.userid WHERE r.productoid=p.id AND us.estado="SUSPENDIDO" AND r.state="activo")
    ) suspendido,
    (
      (SELECT COUNT(u.id) FROM tmpfactura u LEFT JOIN usuarios2 us ON us.id=u.userid WHERE u.productoid=p.id AND us.estado="RETIRADO" AND u.state="pendiente")
      +
      (SELECT COUNT(r.id) FROM alquileres r LEFT JOIN usuarios2 us ON us.id=r.userid WHERE r.productoid=p.id AND us.estado="RETIRADO" AND r.state="activo")
    ) retirado
    ');
    $builder->where('tipo', 'otro');
    $builder->orderBy('id ASC');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_personaliado(String $id)
  {
    $builder = $this->db1->table('productos');
    $builder->select('*');
    $builder->where('id', $id);
    $query = $builder->get();
    return $query ? $query->getRow() : false;
  }
  public function update_personalizado($id, $data)
  {
    $builder = $this->db1->table('productos');
    $builder->where('id', $id);
    $builder->update($data);
    $result = $this->db1->affectedRows();
    return $result > 0 ? true : false;
  }
  public function new_personalizado($data)
  {
    $builder = $this->db1->table('productos');
    $builder->insert($data);
    $result = $this->db1->affectedRows();
    $id = $this->db1->insertID();
    return $result ? $id : false;
  }
  public function delete_personalizado($id)
  {
    $builder = $this->db1->table('productos');
    $builder->where('id', $id);
    return $builder->delete() ? true : false;
  }
}
