<?php

namespace App\Models;

use CodeIgniter\Model;

#[\AllowDynamicProperties]
class Model_finanzas_facturacion extends Model
{
  function __construct()
  {
    $db1 = \Config\Database::connect('default');
    $this->db1 = $db1;
  }

  public function get_configuracion_personal($idcliente)
  {
    $builder = $this->db1->table('tblavisouser');
    $builder->select('id,otros_impuestos,limit_velocidad,tipoaviso,tipopago,diapago,diafactura,impuesto,corteautomatico,meses,afip_condicion_iva,afip_condicion_venta,afip_automatico,avisopantalla,tiporecordatorio,avisosms,avisosms2,avisosms3');
    $builder->where('cliente', $idcliente);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function save_configuracion($idconfig, $data)
  {
    $builder = $this->db1->table('tblavisouser');
    $builder->where('id', $idconfig);
    $builder->update($data);
    $result = $this->db1->affectedRows();
    return $result ? true : false;
  }
  public function get_planes_cliente($idcliente)
  {
    $builder = $this->db1->table('tblservicios s');
    $builder->select('p.plan,s.descripcion,p.velocidad,s.costo,p.impuesto,p.costo_prepago');
    $builder->join('perfiles p', 'p.id=s.idperfil', 'left');
    $builder->where('s.idcliente', $idcliente);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_productos_cliente($idcliente)
  {
    $builder = $this->db1->table('tmpfactura t');
    $builder->select('t.tipoa,p2.id o_id,p2.impuesto o_impuesto,p2.descripcion o_descripcion,p2.clave_invoice o_clave_invoice,a.id,a.serial_producto,p.impuesto,p.descripcion,t.monto,a.cantidad,p.clave_invoice');
    $builder->join('almacen a', 'a.id=t.productoid', 'left');
    $builder->join('productos p', 'p.id=a.productoid', 'left');
    $builder->join('productos p2', 'p2.id=t.productoid', 'left');
    $builder->where('t.userid', $idcliente);
    $builder->where('t.state', 'pendiente');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_descuento_disponible($idcliente)
  {
    $builder = $this->db1->table('saldos');
    $builder->select('sum(monto) descuento');
    $builder->where('estado', 'no cobrado');
    $builder->where('iduser', $idcliente);
    $builder->where('monto<', 0);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_config($idcliente)
  {
    $builder = $this->db1->table('tblavisouser');
    $builder->select('corteautomatico,tipopago,otros_impuestos,impuesto,mora,fecha_factura,diapago');
    $builder->where('cliente', $idcliente);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_legal()
  {
    $builder = $this->db1->table('tblconfiguration');
    $builder->select('value');
    $builder->where('id', 12);
    $query1 = $builder->get();
    $builder = $this->db1->table('tblconfiguration');
    $builder->set('value', 'value+1', false);
    $builder->where('id', 12);
    $builder->update();
    return $query1 ? $query1->getResult() : false;
  }
  public function insert_items_factura($data)
  {
    $builder = $this->db1->table('facturaitems');
    $builder->insertBatch($data);
    $result = $this->db1->affectedRows();
    return $result ? true : false;
  }
  public function update_batch_almacen($data, $string)
  {
    $builder = $this->db1->table('almacen');
    $builder->updateBatch($data, $string);
    $result = $this->db1->affectedRows();
    return $result ? true : false;
  }
  public function update_tmpfactura($idproducto, $data)
  {
    $builder = $this->db1->table('tmpfactura');
    $builder->where('id', $idproducto);
    $builder->update($data);
    $result = $this->db1->affectedRows();
    return $result ? true : false;
  }
  public function update_saldo($idcliente, $data)
  {
    $builder = $this->db1->table('saldos');
    $builder->where('estado<>', 'facturado');
    $builder->where('iduser', $idcliente);
    $builder->update($data);
    $result = $this->db1->affectedRows();
    return $result ? true : false;
  }
  public function get_tmp_existentes($idcliente)
  {
    $builder = $this->db1->table('tmpfactura');
    $builder->select('id,productoid');
    $builder->where('userid', $idcliente);
    $builder->where('state', 'pendiente');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function new_factura($data)
  {
    $builder = $this->db1->table('facturas');
    $builder->insert($data);
    $result = $this->db1->affectedRows();
    $id = $this->db1->insertID();
    return $result ? $id : false;
  }
  public function get_saldos($idcliente)
  {
    $builder = $this->db1->table('saldos');
    $builder->select('id,monto,idorigen');
    $builder->where('monto>', 0);
    $builder->where('estado', 'no cobrado');
    $builder->where('iduser', $idcliente);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_operaciones_cliente($idcliente)
  {
    $builder = $this->db1->table('operaciones o');
    $builder->select('l.nombre operador,o.meses,o.id,nfactura,o.fecha_pago,o.cobrado,o.comision,o.saldo,o.transaccion,o.forma_pago,u.nombre');
    $builder->join('usuarios2 u', 'u.id=o.idcliente', 'left');
    $builder->join('login l', 'l.id=o.operador', 'left');
    $builder->where('o.idcliente', $idcliente);
    $builder->orderBy('o.id DESC');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_saldos_cliente($idcliente)
  {
    $builder = $this->db1->table('saldos s');
    $builder->select('s.id,s.idorigen,s.iddestino,s.monto,s.fecha,s.descripcion,s.estado');
    $builder->where('iduser', $idcliente);
    $builder->where('estado<>', 'pagado');
    $builder->orderBy('id DESC');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_saldo_id($idsaldo)
  {
    $builder = $this->db1->table('saldos');
    $builder->select('monto');
    $builder->where('id', $idsaldo);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function delete_saldo_cliente($idsaldo)
  {
    $builder = $this->db1->table('saldos');
    $builder->where('id', $idsaldo);
    return $builder->delete() ? true : false;
  }
  public function save_new_saldo($data)
  {
    $builder = $this->db1->table('saldos');
    $builder->insert($data);
    $result = $this->db1->affectedRows();
    return $result ? true : false;
  }
  public function get_alquileres_cliente($idcliente)
  {
    $builder = $this->db1->table('alquileres al');
    $builder->select('al.tipoa,al.monto,al.ncuota,al.totalcuota,a.serial_producto serial,p2.descripcion p_descripcion,p2.clave_invoice p_clave_invoice,p2.impuesto p_impuesto,p.descripcion,p.impuesto,p.clave_invoice');
    $builder->where('al.userid', $idcliente);
    $builder->where('al.state', 'activo');
    $builder->join('almacen a', 'a.id=al.productoid', 'left');
    $builder->join('productos p2', 'p2.id=a.productoid', 'left');
    $builder->join('productos p', 'p.id=al.productoid', 'left');
    $builder->orderBy('al.id ASC');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_alquileres_existentes($idcliente)
  {
    $builder = $this->db1->table('alquileres');
    $builder->select('id,totalcuota,ncuota');
    $builder->where('userid', $idcliente);
    $builder->orderBy('id ASC');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function update_alquiler($id, $data)
  {
    $builder = $this->db1->table('alquileres');
    $builder->where('id', $id);
    $builder->update($data);
    $result = $this->db1->affectedRows();
    return $result > 0 ? true : false;
  }
  public function get_dia_pago(string $idcliente)
  {
    $builder = $this->db1->table('tblavisouser');
    $builder->select('IFNULL(diapago,0) diapago');
    $builder->where('cliente', $idcliente);
    $query = $builder->get();
    return $query ? $query->getRow() : false;
  }
  public function update_dia_pago($id, $data)
  {
    $builder = $this->db1->table('tblavisouser');
    $builder->where('cliente', $id);
    $builder->update($data);
    $result = $this->db1->affectedRows();
    return $result > 0 ? true : false;
  }
  public function get_data_cliente($idcliente)
  {
    $builder = $this->db1->table('usuarios2');
    $builder->select('id idcliente,estado,nombre');
    $builder->where('id', $idcliente);
    $query = $builder->get();
    return $query ? $query->getRow() : false;
  }
}
