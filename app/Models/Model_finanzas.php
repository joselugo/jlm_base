<?php

namespace App\Models;

use App\Controllers\Almacen;
use CodeIgniter\Model;
use SebastianBergmann\CodeCoverage\Node\Builder;

#[\AllowDynamicProperties]
class Model_finanzas extends Model
{
  function __construct()
  {
    $db1 = \Config\Database::connect('default');
    $this->db1 = $db1;
  }

  //----------------------Facturacion ------------------------------
  function get_lista_facturas($from, $to, $nodo, $state, $where)
  {
    $builder = $this->db1->table('facturas f');
    $builder->select('f.idcliente,f.id idfactura,u.nombre nombre,u.cedula,f.legal,f.emitido,f.vencimiento,f.pago pagado,f.total,f.iva_igv impuesto,f.sub_total subtotal,f.cobrado,f.oxxo_referencia oxxo,s.monto saldo,f.forma,f.estado');
    if ($state != "cualquiera") $builder->where('f.estado', $state);
    $builder->where('f.vencimiento BETWEEN ' . $this->db1->escape($from) . ' AND ' . $this->db1->escape($to));
    $builder->join('saldos s', 'f.id=s.idorigen', 'left');
    $builder->join('usuarios2 u', 'u.id=f.idcliente', 'left');
    $builder->join('tblservicios t', 'u.id=t.idcliente', 'left');
    if ($nodo != "cualquiera") {
      $builder->where('t.nodo', $nodo);
    }
    $builder->where($where);
    $builder->orderBy('f.id', 'ASC');
    $builder->groupBy('f.id');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  function get_lista_facturas2($from, $to, $nodo, $state)
  {
    $builder = $this->db1->table('facturas f');
    $builder->select('f.id,o.id idoperacion,f.idclaiente,u.nombre,u.cedula,f.legal,f.emitido,f.vencimiento,f.pago pagado,f.total,f.iva_igv impuesto,f.sub_total subtotal,f.cobrado,f.oxxo_referencia oxxo,s.monto saldo,f.forma,f.estado');
    if ($state != "cualquiera") $builder->where('f.estado', $state);
    $builder->where('f.vencimiento BETWEEN ' . $this->db1->escape($from) . ' AND ' . $this->db1->escape($to));
    $builder->join('usuarios2 u', 'u.id=f.idcliente', 'left');
    $builder->join('saldos s', 'f.id=s.idorigen', 'left');
    $builder->join('operaciones o', 'o.nfactura=f.id', 'left');

    if ($nodo != "cualquiera") {
      $builder->join('tblservicios t', 'u.id=t.idcliente');
      $builder->where('t.nodo', $nodo);
    }
    $builder->orderBy('f.id', 'ASC');
    $sql = $builder->getCompiledSelect();
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }

  public function edit_factura($id, $data)
  {

    $builder = $this->db1->table('facturas');
    $builder->update($data, "id=$id ");
    $result = $this->db1->affectedRows();
    return $result > 0 ? true : false;
  }
  public function edit_saldo_pagar($id, $data)
  {

    $builder = $this->db1->table('saldos');
    $builder->where('id', $id);
    $builder->update($data);
    $result = $this->db1->affectedRows();
    return $result > 0 ? true : false;
  }

  public function edit_items_factura($id, $data)
  {

    $builder = $this->db1->table('facturaitems');
    $builder->where('id', $id);
    $builder->update($data);
    $result = $this->db1->affectedRows();
    return $result > 0 ? true : false;
  }
  public function edit_operacion($id, $data)
  {

    $builder = $this->db1->table('operaciones');
    $builder->where('id', $id);
    $builder->update($data);
    $result = $this->db1->affectedRows();
    return $result > 0 ? true : false;
  }
  public function delete_saldo($id)
  {
    $builder = $this->db1->table('saldos');
    $builder->where('idorigen', $id);
    return $builder->delete() ? true : false;
  }

  public function consult_edittransaccion($id)
  {
    $builder = $this->db1->table('facturas f');
    $builder->select('f.idcliente,o.descripcion_pago,o.forma_pago,o.transaccion,o.cobrado,o.comision,o.id operacionesid,f.id,o.descripcion,DATE_FORMAT(o.fecha_pago,"%d/%m/%Y %h:%i %p")  fecha_pago');
    $builder->where('f.id', $id);
    $builder->join('operaciones o', 'f.id=o.nfactura', 'left');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function consult_edittransaccion_por_operacion($id)
  {
    $builder = $this->db1->table('operaciones o');
    $builder->select('f.idcliente,o.descripcion_pago,o.forma_pago,o.transaccion,o.cobrado,o.comision,o.id operacionesid,f.id,o.descripcion,DATE_FORMAT(o.fecha_pago,"%d/%m/%Y %h:%i %p")  fecha_pago');
    $builder->where('o.nfactura', $id);
    $builder->join('facturas f', 'f.id=o.nfactura', 'left');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_ids_almacen($id)
  {
    $builder = $this->db1->table('facturaitems f');
    $builder->select('f.idalmacen,f.unidades,f.idfactura');
    $builder->where('idfactura', $id);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }

  public function eliminar_factura_items($id)
  {
    $builder = $this->db1->table('facturaitems');
    $builder->where('idfactura', $id);
    return $builder->delete() ? true : false;
  }
  public function delete_operacion($id)
  {
    $builder = $this->db1->table('operaciones');
    $builder->where('nfactura', $id);
    $builder->delete();
    $result = $this->db1->affectedRows();
    return $result ? true : false;
  }

  /*  public function reponer_en_almacen($id, $unidades)
  {
    $builder = $this->db1->table('almacen');
    $builder->set('cantidad', 'cantidad+' . $unidades, FALSE);
    $builder->where('id', $id);
    return $builder->update() ? true : false;
  } */
  public function  edit_saldo($id, $data)
  {
    $builder = $this->db1->table('saldos');
    $builder->where('iddestino', $id);
    $builder->update($data);
    $result = $this->db1->affectedRows();
    return $result ? true : false;
  }
  public function eliminar_saldo($id)
  {
    $builder = $this->db1->table('saldos');
    $builder->where('idorigen', $id);
    return $builder->delete() ? true : false;
  }
  public function eliminar_items_factura($id)
  {
    $builder = $this->db1->table('facturaitems');
    $builder->where('id', $id);
    return $builder->delete() ? true : false;
  }
  public function insert_nuevo_saldo($data)
  {
    $builder = $this->db1->table('saldos');
    $builder->insert($data);
    $result = $this->db1->affectedRows();
    return $result ? true : false;
  }
  public function insert_items_factura($data)
  {
    $builder = $this->db1->table('facturaitems');
    $builder->insert($data);
    $result = $this->db1->affectedRows();
    return $result ? true : false;
  }
  public function insert_nuevo_operacion($data)
  {
    $builder = $this->db1->table('operaciones');
    $builder->insert($data);
    $result = $this->db1->affectedRows();
    return $result ? true : false;
  }
  public function consulta_de_total_factura($id)
  {
    $builder = $this->db1->table('facturas f');
    $builder->select('*');
    $builder->where('id', $id);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function consulta_factura_user($id)
  {
    $builder = $this->db1->table('facturas f');
    $builder->select('f.total,f.cobrado,f.impuesto str_impuesto,f.otros_impuestos,f.oxxo_referencia,f.oxxo_id,u.id idcliente,fi.cantidad,fi.impuesto iimpuesto,fi.unidades,fi.descripcion idescripcion,f.*,u.nombre,u.cedula,u.movil,u.telefono,u.direccion_principal');
    $builder->where('f.id', $id);
    $builder->join('usuarios2 u', 'u.id=f.idcliente', 'left');
    $builder->join('facturaitems fi', 'fi.idfactura=f.id', 'left');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function eliminar_factura($id)
  {
    $builder = $this->db1->table('facturas');
    $builder->where('id', $id);
    return $builder->delete() ? true : false;
  }
  public function get_items_factura($id)
  {
    $builder = $this->db1->table('facturaitems fi');
    $builder->select('f.impuesto impuesto_factura,f.otros_impuestos,f.id idfactura,DATE_FORMAT(f.vencimiento,"%d/%m/%Y") as vencimiento,DATE_FORMAT(f.emitido,"%d/%m/%Y") as emitido,fi.idalmacen,fi.descripcion,fi.cantidad,fi.impuesto,fi.unidades,fi.id');
    $builder->join('facturas f', 'fi.idfactura=f.id', 'left');
    $builder->where('fi.idfactura', $id);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  /* public function get_items_facturaa($id)
  {
    $builder = $this->db1->table('facturaitems fi');
    $builder->select('f.otros_impuestos,SUM(s.monto) monto,f.id idfactura,DATE_FORMAT(f.vencimiento,"%d/%m/%Y") as vencimiento,DATE_FORMAT(f.emitido,"%d/%m/%Y") as emitido,fi.idalmacen,fi.descripcion,fi.cantidad,fi.impuesto,fi.unidades,fi.id');
    $builder->join('facturas f', 'fi.idfactura=f.id', 'left');
    $builder->join('saldos s', 's.iddestino=f.id', 'left');
    $builder->where('fi.idfactura', $id);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  } */
  public function get_productos()
  {
    $builder = $this->db1->table('productos');
    $builder->select('*');
    $builder->where('tipo', 'producto');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_otros()
  {
    $builder = $this->db1->table('productos');
    $builder->select('*');
    $builder->where('tipo', 'accesorio');
    $builder->orWhere('tipo', 'otro');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_serialproductos($id, $where)
  {
    $builder = $this->db1->table('almacen a');
    $builder->select('a.serial_producto,p.descripcion,a.mac_producto,a.id idalmacen,a.costo costo_almacen');
    $builder->where('productoid', $id);
    $builder->where('estado', 'disponible');
    $builder->join('productos p', 'a.productoid=p.id');
    $builder->where($where);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_items_factura_edit($id)
  {
    $builder = $this->db1->table('facturaitems');
    $builder->select('id,idfactura,cantidad,idalmacen');
    $builder->where('idfactura', $id);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }

  //nueva saldo_n
  public function get_saldos_n($id)
  {
    $builder = $this->db1->table('saldos');
    $builder->select('sum(monto) monto');
    $builder->where('iddestino', $id);
    $query = $builder->get();
    return $query ? $query->getRow() : false;
  }
  public function get_saldos_ndos($id)
  {
    $builder = $this->db1->table('saldos');
    $builder->select('sum(monto) monto');
    $builder->where('iddestino', $id);
    $builder->where('monto<',0);
    $query = $builder->get();
    return $query ? $query->getRow() : false;
  }

  //----------------Registrar pedido-----------------
  public function buscar_cliente($data, $where)
  {

    $builder = $this->db1->table('usuarios2 u');
    $builder->select('u.*,se.nodo');
    $builder->like('nombre', "%" . $data . "%");
    $builder->join('tblservicios t', 'u.id=t.idcliente', 'left');
    $builder->join('server se', 't.nodo=se.id', 'left');
    $builder->where('u.estado<>', 'RETIRADO');
    $builder->where($where);
    $builder->groupBy('u.id');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_all_facturas_cliente($id, $where)
  {
    $builder = $this->db1->table('facturas f');
    $builder->select('u.estado,f.idcliente,u.nombre,f.total,f.legal,f.tipo,f.emitido,f.id idfactura,u.id');
    $builder->join('usuarios2 u', 'u.id=f.idcliente');
   // $builder->join('tblservicios t', 't.idcliente=f.idcliente');
    $builder->where('f.idcliente', $id);
    $builder->where('f.estado', 'No pagado');
    $builder->where($where);
    $builder->groupBy('f.id');
    $builder->orderBy('f.tipo ASC');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_all_saldos_cliente($id)
  {
    $builder = $this->db1->table('saldos s');
    $builder->select('s.*');
    $builder->where('s.iduser', $id);
    $builder->where('s.estado', 'no cobrado');
    $builder->join('usuarios2 u', 'u.id=s.iduser');
    $builder->orderBy('s.id ASC');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }

  public function get_datos_empresa($idcliente)
  {
    $builder = $this->db1->table('usuarios2 u');
    $builder->select('s.*');
    $builder->join('sucursal s','s.id=u.sucursal');
    $builder->where('u.id', $idcliente);
    $query = $builder->get();
    return $query ? $query->getRow() : false;
  }
  public function get_operaciones_registradas_hoy($fecha, $nodo, $operador, $pasarela, $where)
  {
    $builder = $this->db1->table('operaciones o');
    $builder->select('o.id id,u.nombre,f.id nfactura,f.legal,o.forma_pago,o.fecha_pago,o.meses,l.nombre operador,o.descripcion,o.comision,,o.cobrado,o.transaccion,');
    $builder->join('facturas f', 'f.id=o.nfactura', 'left');
    $builder->join('usuarios2 u', 'u.id=o.idcliente', 'left');
    $builder->join('login l', 'l.id=o.operador', 'left');
    $builder->join('tblservicios t', 't.idcliente = u.id', 'left');
    if ($nodo != 0) $builder->where('t.nodo', $nodo);
    if ($operador != 0) $builder->where('o.operador', $operador);
    if ($pasarela != "0") $builder->where('o.forma_pago', $pasarela);
    $builder->where('fecha_pago>=', $fecha);
    $builder->where($where);
    $builder->orderBy('o.fecha_pago ASC');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_pasarelas_data($fecha)
  {
    $builder = $this->db1->table('operaciones o');
    $builder->select('o.forma_pago pasarela');
    $builder->where('fecha_pago>=', $fecha);
    $builder->groupBy('o.forma_pago');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_operaciones_registradas_hoy_reporte($fecha, $nodo, $operador, $pasarela, $where)
  {
    $builder = $this->db1->table('operaciones o');
    $builder->select('sv.nodo,s.monto,p.plan,u.cedula,o.descripcion,o.id id,u.nombre,f.id nfactura,f.legal,o.forma_pago,o.fecha_pago,o.meses,l.nombre operador,o.descripcion,o.comision,,o.cobrado,o.transaccion,');
    $builder->join('facturas f', 'f.id=o.nfactura', 'left');
    $builder->join('usuarios2 u', 'u.id=o.idcliente', 'left');
    $builder->join('login l', 'l.id=o.operador', 'left');
    $builder->join('tblservicios t', 't.idcliente = u.id', 'left');
    $builder->join('perfiles p', 'p.id=t.idperfil', 'left');
    $builder->join('saldos s', 's.idorigen=f.id', 'left');
    $builder->join('server sv', 'sv.id=t.nodo', 'left');
    $builder->where('fecha_pago>=', $fecha);
    if ($nodo != 0) $builder->where('t.nodo', $nodo);
    if ($operador != 0) $builder->where('o.operador', $operador);
    if ($pasarela != "0") $builder->where('o.forma_pago', $pasarela);
    $builder->where($where);
    $builder->orderBy('o.fecha_pago ASC');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_promesas_cliente($id)
  {
    $builder = $this->db1->table('credito');
    $builder->select('*');
    $builder->where('idcliente', $id);
    $builder->orderBy('id ASC');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function new_promesa($data)
  {
    $builder = $this->db1->table('credito');
    $builder->insert($data);
    $result = $this->db1->affectedRows();
    return $result ? true : false;
  }
  public function get_all_promesas($where)
  {
    $builder = $this->db1->table('credito c');
    $builder->select('l.nombre operador_nombre,c.*,u.nombre cliente');
    $builder->join('usuarios2 u', 'u.id=c.idcliente');
    $builder->join('login l', 'l.id=c.operador');
    $builder->join('tblservicios t', 't.idcliente=c.idcliente');
    $builder->where($where);
    $builder->orderBy('id ASC');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function delete_promesa($idcliente)
  {
    $builder = $this->db1->table('credito');
    $builder->where('idcliente', $idcliente);
    return $builder->delete() ? true : false;
  }
  public function get_saldo_factura($id)
  {
    $builder = $this->db1->table('saldos');
    $builder->select('*');
    $builder->where('iddestino', $id);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_max_fecha_pago($idcliente)
  {
    $builder = $this->db1->table('facturas f');
    $builder->select('t.diapago,f.id,f.vencimiento');
    $builder->where('idcliente', $idcliente);
    $builder->where('estado<>', 'anulado');
    $builder->where('tipo', '2');
    $builder->join('tblavisouser t', 't.cliente=f.idcliente', 'left');
    $builder->orderBy('f.id DESC');
    $builder->limit(1);
    $query = $builder->get();
    if ($query->getResult()) {
      return $query->getResult();
    } else {
      $builder = $this->db1->table('facturas f');
    $builder->select('t.diapago,f.id,f.vencimiento');
    $builder->where('idcliente', $idcliente);
    $builder->where('estado<>', 'anulado');
    $builder->where('tipo', '1');
    $builder->join('tblavisouser t', 't.cliente=f.idcliente', 'left');
    $builder->orderBy('f.id DESC');
      $query = $builder->get();
      return $query ? $query->getResult() : false;
    }
  }



  public function check_facture($idcliente)
  {
    $builder = $this->db1->table('facturas f');
    $builder->select('count(f.id) numfacturas');
    $builder->where('f.idcliente', $idcliente);
    $query = $builder->get();
    return $query ? $query->getRow() : false;
  }




  public function get_proxima_facturacion($idcliente)
  {
    $builder = $this->db1->table('facturas f');
    $builder->select('t.diapago,f.vencimiento');
    $builder->where('idcliente', $idcliente);
    $builder->where('estado<>', 'anulado');
    $builder->where('tipo', '2');
    $builder->join('tblavisouser t', 't.cliente=f.idcliente', 'left');
    $builder->orderBy('f.id DESC');
    $builder->limit(1);
    $query = $builder->get();
    if ($query->getResult()) {
      $max_fechapago = $query->getResult();
      $nuevafecha = strtotime('+1 month', strtotime($max_fechapago[0]->vencimiento));
      $nuevafecha = date('y-m-d', $nuevafecha);
      $mes = date("m", strtotime($nuevafecha));
      $año = date("y", strtotime($nuevafecha));
      $nuevafecha = $año . "-" . $mes . "-" .  $max_fechapago[0]->diapago;
      $nuevafecha = date('y-m-d', strtotime($nuevafecha));
      return $nuevafecha;
    } else {
      $builder = $this->db1->table('tblservicios s');
      $builder->select('t.diapago,s.instalado');
      $builder->join('tblavisouser t', 't.cliente=s.idcliente', 'left');
      $builder->where('s.idcliente', $idcliente);
      $builder->limit(1);
      $query = $builder->get();
      if ($query->getResult()) {
        $max_fechapago = $query->getResult();
        $nuevafecha = strtotime('+1 month', strtotime($max_fechapago[0]->instalado));
        $nuevafecha = date('y-m-d', $nuevafecha);
        $mes = date("m", strtotime($nuevafecha));
        $año = date("y", strtotime($nuevafecha));
        $nuevafecha = $año . "-" . $mes . "-" .  $max_fechapago[0]->diapago;
        $nuevafecha = date('y-m-d', strtotime($nuevafecha));
        return $nuevafecha;
      } else {
        return false;
      }
    }
  }
  public function get_idcliente($idfactura)
  {
    $builder = $this->db1->table('facturas f');
    $builder->select('f.idcliente');
    $builder->where('id', $idfactura);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_factura($id)
  {
    $builder = $this->db1->table('facturas');
    $builder->select('id nfactura,idcliente,legal,total monto');
    $builder->where('id', $id);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_nombre_cliente($id)
  {
    $builder = $this->db1->table('usuarios2');
    $builder->select('nombre');
    $builder->where('id', $id);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_id_operacion($id)
  {
    $builder = $this->db1->table('facturas f');
    $builder->select('f.idcliente,o.id idoperacion,u.nombre,t.impuesto');
    $builder->join('operaciones o', 'o.nfactura=f.id', 'left');
    $builder->join('usuarios2 u', 'u.id=f.idcliente', 'left');
    $builder->join('tblavisouser t', 't.cliente=u.id', 'left');
    $builder->where('f.id', $id);
    $builder->limit(1);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_descuento_total_factura($idfactura)
  {
    $builder = $this->db1->table('saldos');
    $builder->select('SUM(monto) smonto');
    $builder->where('iddestino', $idfactura);
    $builder->where('monto<', 0);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function delete_factura_no_cobrados($id)
  {
    $builder = $this->db1->table('saldos');
    $builder->where('idorigen', $id);
    $builder->where('estado', 'no cobrado');
    return $builder->delete() ? true : false;
  }
  public function get_descuento_saldo($idfactura)
  {
    $builder = $this->db1->table('saldos');
    $builder->select('monto');
    $builder->where('idorigen', $idfactura);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_estadisticas2()
  {
    $builder = $this->db1->table('operaciones o');
    $builder->select('SUM(g.monto) as ingreso,count(o.id) as operaciones,SUM(o.comision) as comision,SUM(o.cobrado) as total,MONTHNAME(o.fecha_pago) as mes,SUM(o.cobrado) as cobrado');
    $builder->join('gastos g.', 'MONTH(g.fecha)=MONTH(o.fecha_pago)');
    $builder->where('year(o.fecha_pago)', 2020);
    $builder->groupBy('mes');
    $builder->orderBy('MONTH(o.fecha_pago)');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_estadisticas($sucursal, $anio)
  {
    // Antes: sucursal 0 sumaba TODAS las sucursales del sistema, y sucursal/año se
    // pegaban al SQL sin validar (inyección). Ahora son enteros y siempre se limita a
    // las sucursales del usuario; una sucursal que no tiene no devuelve nada.
    $anio = (int) $anio;
    $sucursal = (int) $sucursal;
    if ($sucursal === 0) {
      // Pagos por la sucursal del CLIENTE (la del servicio dejaba fuera a clientes sin servicio)
      $tsucursal = \App\Libraries\FiltroSucursal::todas() ? "" : " and o.idcliente IN (SELECT id FROM usuarios2 WHERE " . \App\Libraries\FiltroSucursal::sql('sucursal') . ")";
      $gsucursal = " and " . \App\Libraries\FiltroSucursal::sql('g.idsucursal');
    } elseif (\App\Libraries\FiltroSucursal::permite($sucursal)) {
      $tsucursal = " and o.idcliente IN (SELECT id FROM usuarios2 WHERE sucursal=$sucursal)";
      $gsucursal = " and g.idsucursal=$sucursal";
    } else {
      $tsucursal = " and 1=0";
      $gsucursal = " and 1=0";
    }
    $query = $this->db1->query(
      'SELECT t1.mes as otro_mes,t1.egreso,t1.ingreso,t2.* FROM (
        SELECT MONTHNAME(g.fecha)as mes,SUM(
        CASE
        WHEN g.tipo<>"ingreso" THEN g.monto
        END
        ) as egreso,
        SUM(
        CASE
        WHEN g.tipo="ingreso" THEN g.monto
        END
        ) as ingreso
        FROM `gastos` `g`
        WHERE year(g.fecha) =' . $anio . $gsucursal . '
        GROUP BY `mes`
        ORDER BY MONTH(g.fecha)) as t1
    LEFT JOIN
        (SELECT COUNT(id) as operaciones,SUM(comision) AS comision,SUM(cobrado) as cobrado , 						MONTHNAME(fecha_pago) as mes from(
    SELECT  DISTINCT o.id,o.cobrado,o.comision,o.fecha_pago
            FROM `operaciones` `o`
            LEFT JOIN tblservicios t on t.idcliente=o.idcliente
            WHERE year(o.fecha_pago) = ' . $anio . $tsucursal .
        '
			) as tabla
            GROUP BY `mes`
     	   ORDER BY MONTH(fecha_pago)) as t2
    on t1.mes=t2.mes
    UNION ALL
    SELECT * FROM (
        SELECT MONTHNAME(g.fecha)as mes,SUM(
        CASE
        WHEN g.tipo<>"ingreso" THEN g.monto
        END
        ) as egreso,
        SUM(
        CASE
        WHEN g.tipo="ingreso" THEN g.monto
        END
        ) as ingreso
        FROM `gastos` `g`
        WHERE year(g.fecha) = ' . $anio . $gsucursal . '
        GROUP BY `mes`
        ORDER BY MONTH(g.fecha)) as t1
    RIGHT JOIN
        (SELECT COUNT(id) as operaciones,SUM(comision) AS comision,SUM(cobrado) as cobrado , 						MONTHNAME(fecha_pago) as mes from(
    SELECT  DISTINCT o.id,o.cobrado,o.comision,o.fecha_pago
            FROM `operaciones` `o`
            LEFT JOIN tblservicios t on t.idcliente=o.idcliente
            WHERE year(o.fecha_pago) = ' . $anio . $tsucursal . '
			) as tabla
            GROUP BY `mes`
     	   ORDER BY MONTH(fecha_pago)) as t2
    on t1.mes=t2.mes
    WHERE  t1.mes is null'
    );
    return $query ? $query->getResult() : false;
  }

  public function get_transacciones($desde, $hasta, $nodo, $operador, $forma_pago, $where)
  {

    $builder = $this->db1->table('operaciones o');
    $builder->select('o.forma_pago,u.cedula,o.id id,u.nombre,f.id nfactura,f.legal,o.forma_pago,o.fecha_pago,o.meses,l.nombre operador,o.descripcion,o.descripcion_pago detalle,o.comision,,o.cobrado,o.transaccion,');
    $builder->join('facturas f', 'f.id=o.nfactura', 'left');
    $builder->join('usuarios2 u', 'u.id=o.idcliente', 'left');
    $builder->join('login l', 'l.id=o.operador', 'left');
    $builder->join('tblservicios t', 't.idcliente = u.id', 'left');
    if ($nodo <> 0) {
      $builder->where('t.nodo', $nodo);
    }
    if ($forma_pago != "todos") {
      $builder->where('o.forma_pago', $forma_pago);
    }
    if ($operador <> 0) {
      $builder->where('o.operador', $operador);
    }
    $builder->where('o.fecha_pago>=', $desde);
    $builder->where('o.fecha_pago<=', $hasta);
    $builder->where($where);
    $builder->orderBy('o.fecha_pago ASC');
    $builder->groupBy('o.id');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_operaciones_registradas_reporte($desde, $hasta, $nodo, $operador, $pasarela, $where)
  {
    $builder = $this->db1->table('operaciones o');
    $builder->select('sv.nodo,if(s.estado="pagado" or s.monto<0,null,s.monto) monto,p.plan,u.cedula,o.descripcion,o.id id,u.nombre,f.id nfactura,f.legal,o.forma_pago,o.fecha_pago,o.meses,l.nombre operador,o.descripcion,o.comision,,o.cobrado,o.transaccion,');
    $builder->join('facturas f', 'f.id=o.nfactura', 'left');
    $builder->join('usuarios2 u', 'u.id=o.idcliente', 'left');
    $builder->join('login l', 'l.id=o.operador', 'left');
    $builder->join('tblservicios t', 't.idcliente = u.id', 'left');
    $builder->join('perfiles p', 'p.id=t.idperfil', 'left');
    $builder->join('saldos s', 's.idorigen=f.id', 'left');
    $builder->join('server sv', 'sv.id=t.nodo', 'left');
    if ($nodo != 0) $builder->where('t.nodo', $nodo);
    if ($operador != 0) $builder->where('o.operador', $operador);
    if ($pasarela != "todos") $builder->where('o.forma_pago', $pasarela);
    $builder->where('fecha_pago>=', $desde);
    $builder->where('fecha_pago<=', $hasta);
    $builder->where($where);
    $builder->orderBy('o.fecha_pago ASC');
    $builder->groupBy('o.id');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function update_tmp($idfactura, $productoid, $data)
  {
    $builder = $this->db1->table('tmpfactura');
    $builder->where('nfactura', $idfactura);
    $builder->where('productoid', $productoid);
    $builder->where('state', 'facturado');
    $builder->update($data);
    $result = $this->db1->affectedRows();
    return $result > 0 ? true : false;
  }
  public function get_data_servicios(String $idcliente)
  {
    $builder = $this->db1->table('tblservicios ts');
    $builder->select('ts.id,se.ip,se.usuario,se.contrasena,se.port_api puerto');
    $builder->join('server se', 'se.id=ts.nodo', 'left');
    $builder->join('usuarios2 us', 'us.id=ts.idcliente', 'left');
    $builder->where('ts.idcliente', $idcliente);
    $builder->where('us.estado', 'SUSPENDIDO');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function consulta_de_total_factura_o_saldo(string $id, int $tipo)
  {
    if ($tipo == 0) {
      $builder = $this->db1->table('saldos');
      $builder->select('estado,monto total,idorigen');
      $builder->where('id', $id);
      $query = $builder->get();
      return $query ? $query->getRow() : false;
    } else {
      $builder = $this->db1->table('facturas f');
      $builder->select('estado,total');
      $builder->where('id', $id);
      $query = $builder->get();
      return $query ? $query->getRow() : false;
    }
  }
  public function  edit_saldo_factura($idfactura, $data)
  {
    $builder = $this->db1->table('saldos');
    $builder->where('idorigen', $idfactura);
    $builder->where('iddestino', '00000000');
    $builder->where('estado', 'pagado');
    $builder->update($data);
    $result = $this->db1->affectedRows();
    return $result ? true : false;
  }
  public function edit_factura_saldo($id, $data)
  {
    $builder = $this->db1->table('facturas');
    $builder->set('cobrado', "cobrado+$data", FALSE);
    $builder->where('id', $id);
    $builder->update();
    $result = $this->db1->affectedRows();
    return $result > 0 ? true : false;
  }
  public function get_operaciones(String $idfactura)
  {
    $builder = $this->db1->table('operaciones o');
    $builder->select('o.id,o.fecha_pago,o.forma_pago,o.cobrado,o.transaccion,l.nombre');
    $builder->join('login l', 'l.id=o.operador', 'left');
    $builder->where('nfactura', $idfactura);
    $builder->orderBy('id DESC');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_estado_factura(string $idfactura){
    $builder = $this->db1->table('facturas');
    $builder->select('if(estado="pagado","TRUE","FALSE") estado');
    $builder->where('id',$idfactura);
    $query = $builder->get();
  return $query ? $query->getRow() : false;
  }
  public function delete_operacion_por_id($id){
    $builder = $this->db1->table('operaciones');
    $builder->where('id', $id);
    return $builder->delete() ? true : false;
  }
}
