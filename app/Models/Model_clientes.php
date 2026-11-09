<?php

namespace App\Models;

use CodeIgniter\Model;

#[\AllowDynamicProperties]
class Model_clientes extends Model
{
	var $db1;
	var $db2;
	function __construct()
	{
		$db1 = \Config\Database::connect('default');
		$this->db1 = $db1;
	}


	public function migrar()
	{

		$builder = $this->db2->table('facturas f');
		$builder->select('*');
		$builder->where("f.idcliente between '0' and '222'");
		$builder->orderBy('f.id', "ASC");
		$query = $builder->get();
		if ($query != false)	return $query->getResult();
		else return false;
	}

	public function insert_migrar($data)
	{

		$builder = $this->db1->table('facturas');
		$builder->insert($data);
		if ($this->db1->affectedRows() > 0) return true;
		else return false;
	}


	public function get_all_search($permiso, $where)
	{

		$builder = $this->db1->table('usuarios2 u');
		$builder->select('u.*,se.nodo as nombre_server,se.id as id_server,ta.avatar_cliente,ta.avatar_color');
		$builder->join('tblservicios t', 't.idcliente = u.id', 'left');
		$builder->join('tblavisouser ta', 'ta.cliente = u.id', 'left');
		$builder->join('server se', 't.nodo = se.id', 'left');
		$builder->where($permiso);
		$builder->where($where);
		$builder->groupBy('u.id');
		$builder->orderBy('u.id', "ASC");
		$query = $builder->get();
		if ($query != false)	return $query->getResult();
		else return false;
	}

	public function get_all_clientes_ips()
	{

		$builder = $this->db1->table('usuarios2 u');
		$builder->select('u.*,t.*,s.nodo as server,u.id as id_user');
		$builder->join('tblservicios t', 'u.id = t.idcliente');
		$builder->join('server s', 's.id = t.nodo');
		$builder->where('t.nodo', 1);
		$builder->groupBy('t.idcliente');
		$builder->orderBy('u.id ASC');
		$query = $builder->get();
		if ($query != false)	return $query->getResult();
		else return false;
	}
	public function get_all_instalaciones_pre($permiso)
	{
		$builder = $this->db1->table('instalaciones i');
		$builder->select('i.*,z.zona,i.zona as id_zona, IF(i.zona = 0 ,0,0),su.sucursal,su.color_su');
		$builder->join('zonas z', 'i.zona = z.id', 'left');
		$builder->join('sucursal su', 'i.sucursal = su.id', 'left');
		$builder->where('estate', 'PENDIENTE');
		$builder->where($permiso);
		$builder->orderBy('i.id DESC');
		$query = $builder->get();
		if ($query != false) return $query->getResult();
		else return false;
	}

	public function get_all_instalaciones($permiso)
	{
		$builder = $this->db1->table('instalaciones i');
		$builder->select('i.*,z.zona,i.zona as id_zona, l.nombre as tecnico,su.sucursal,su.color_su,u.nombre as nombre_cliente');
		$builder->join('usuarios2 u', 'u.id = i.userid', 'left');
		$builder->join('zonas z', 'i.zona = z.id', 'left');
		$builder->join('login l', 'l.id = i.idtecnico', 'left');
		$builder->join('sucursal su', 'i.sucursal = su.id', 'left');
		$builder->where("estate <> 'PENDIENTE'");
		$builder->where($permiso);
		$builder->orderBy('i.id DESC');
		$query = $builder->get();
		if ($query != false) return $query->getResult();
		else return false;
	}

	public function tiene_servicio()
	{
		$builder = $this->db1->table('usuarios2 u');
		$builder->select('*');
		$builder->where('id', 1);
		$builder->orderBy('id ASC');
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	function get_user_data($id_cliente, $where_permiso_nodo)
	{

		$builder = $this->db1->table('usuarios2 u');
		$builder->select('u.*, u.id as id_user,ta.avatar_cliente,ta.avatar_color');
	//	$builder->join('tblservicios t', 'u.id = t.idcliente', "left");
		$builder->join('tblavisouser ta', 'ta.cliente = u.id');
		//$builder->join('perfiles p', 'p.id = t.idperfil', "left");
	//	$builder->join('server s', 's.id = t.nodo');
	//	$builder->join('instalaciones i', 'i.userid = u.id', 'left');
		// Solo clientes de las sucursales del usuario (antes recibía "t.sucursal ..." sin unir
		// tblservicios: error 500 para usuarios sin todas las sucursales). $where_permiso_nodo ya no se usa.
		\App\Libraries\FiltroSucursal::aplicar($builder, 'u.sucursal');
		$builder->where('u.id', $id_cliente);


		$query = $builder->get();
		if ($query != false)	return $query->getRow();
		else return false;
	}


	function get_user_aviso($id_cliente)
	{
		$builder = $this->db1->table('tblavisouser');
		$builder->select('*');
		$builder->where('cliente', $id_cliente);
		$query = $builder->get();
		if ($query != false)	return $query->getRow();
		else return false;
	}

	function get_user_private($id_cliente)
	{
		$builder = $this->db1->table('tbldatauserprivate');
		$builder->select('*');
		$builder->where('iduser', $id_cliente);
		$query = $builder->get();
		if ($query != false)	return $query->getRow();
		else return false;
	}

	public function get_max_factura($id_cliente)
	{

		$builder = $this->db1->table('facturas');
		$builder->select('MAX(vencimiento) as ultimo');
		$builder->where('idcliente', $id_cliente);
		$query = $builder->get();
		if ($query->getRow()->ultimo > 0) return $query->getRow();
		else return false;
	}

	function get_servicios_cliente($id_cliente)
	{

		$builder = $this->db1->table('tblservicios t');
		$builder->select('IFNULL(p.id,0) cuenta_con_servicio,p.id idperfild,u.estado estadou,t.costo costotblservicio,t.*,p.*,s.nodo as server,t.id as id_servicio');
		$builder->join('usuarios2 u', 'u.id =t.idcliente', "left");
		$builder->join('perfiles p', 'p.id = t.idperfil', "left");
		$builder->join('server s', 's.id = t.nodo');
		$builder->where('t.idcliente', $id_cliente);
		$builder->orderBy('t.id DESC');
		$query = $builder->get();
		if ($query != false)	return $query->getResult();
		else return false;
	}

	function get_servicios_by_id($id_servicio)
	{

		$builder = $this->db1->table('tblservicios t');
		$builder->select('t.*,p.*,s.nodo as server,t.id as id_servicio');
		$builder->join('perfiles p', 'p.id = t.idperfil');
		$builder->join('server s', 's.id = t.nodo');
		$builder->where('t.id', $id_servicio);
		$query = $builder->get();
		if ($query != false)	return $query->getRow();
		else return false;
	}


	function get_serviciosvoip_cliente($id_cliente)
	{


		$builder = $this->db1->table('tblserviciosvoip t');
		$builder->select('t.id,t.costo,t.server,t.sipuser,t.sipid,t.numerotelefono,t.instalacion,t.notas,p.plan');
		$builder->join('perfilesvoip p', 'p.id = t.idperfil');
		$builder->where('t.cliente', $id_cliente);
		$builder->orderBy('t.id DESC');
		$query = $builder->get();
		if ($query != false)	return $query->getResult();
		else return false;
	}

	public function update_ip($id, $ip)
	{

		$builder = $this->db1->table('tblservicios');
		$builder->where('id', $id);
		if ($builder->update(['ipap' => $ip, 'redipv4' => 1, 'antena' => 1])) return true;
		else return false;
	}

	public function delete_socio($id)
	{
		$builder = $this->db1->table('socios');
		$builder->where('id', $id);
		if ($builder->delete()) return true;
		else return false;
	}

	public function delete_doc($id)
	{
		$builder = $this->db1->table('documentos');
		$builder->where('id', $id);
		if ($builder->delete()) return true;
		else return false;
	}

	public function delete_re($id)
	{
		$builder = $this->db1->table('reportes');
		$builder->where('id', $id);
		if ($builder->delete()) return true;
		else return false;
	}

	public function update_status($id, $status)
	{

		$builder = $this->db1->table('socios');
		$builder->where('id', $id);
		if ($builder->update(['status' => $status])) return true;
		else return false;
	}

	public function update_status_reporte($id, $status)
	{

		$builder = $this->db1->table('reportes');
		$builder->where('id', $id);
		if ($builder->update(['estado' => $status])) return true;
		else return false;
	}



	public function get_data_socio($id, $permiso)
	{
		$builder = $this->db1->table('usuarios2 cli');
		$builder->select('cli.*,si.nombre as nombre_server');
		$builder->join('server se', 'cli.server = si.id');
		$builder->where('cli.id', $id);
		$builder->where($permiso);
		$builder->orderBy('cli.id', "ASC");
		$query = $builder->get();
		if ($query != false)	return $query->getRow();
		else return false;
	}
	public function get_data_doc($id)
	{
		$builder = $this->db1->table('documentos');
		$builder->select('*');
		$builder->where('id', $id);
		$query = $builder->get();
		if ($query != false)	return $query->getRow();
		else return false;
	}

	public function get_lider_sindical($id_server)
	{
		$builder = $this->db1->table('server se');
		$builder->select('l.nombre');
		$builder->join('login l', 'si.idlider = l.id');
		$builder->where('si.id', $id_server);
		$query = $builder->get();
		if ($query != false)	return $query->getRow();
		else return false;
	}

	public function get_unidades($id, $where)
	{

		$builder = $this->db1->table('unidades u');
		$builder->select('u.*,cli.server');
		$builder->join('usuarios2 cli', 'cli.id = u.idsocio');
		$builder->where('u.idsocio', $id);
		$builder->where($where);
		$query = $builder->get();
		if ($query != false)	return $query->getResult();
		else return false;
	}

	public function get_unidad_by_id($id, $where)
	{

		$builder = $this->db1->table('unidades u');
		$builder->select('u.*,cli.server');
		$builder->join('usuarios2 cli', 'cli.id = u.idsocio');
		$builder->where('u.id', $id);
		$builder->where($where);
		$query = $builder->get();
		if ($query != false)	return $query->getRow();
		else return false;
	}

	public function get_concesionarios_by_id($id, $where)
	{

		$builder = $this->db1->table('concesionarios u');
		$builder->select('u.*,cli.server');
		$builder->join('usuarios2 cli', 'cli.id = u.idsocio');
		$builder->where('u.id', $id);
		$builder->where($where);
		$query = $builder->get();
		if ($query != false)	return $query->getRow();
		else return false;
	}

	public function update_data($id, $data)
	{

		$builder = $this->db1->table('socios');
		$builder->where('id', $id);
		if ($builder->update($data)) return true;
		else return false;
	}

	public function get_max_codigo($server)
	{

		$builder = $this->db1->table('unidades');
		$builder->select('MAX(codigoserver) as ultimo');
		$builder->where('server', $server);
		$query = $builder->get();
		if ($query->getRow()->ultimo > 0) return $query->getRow();
		else return false;
	}
	public function new_socio($data)
	{

		$builder = $this->db1->table('socios');
		$builder->insert($data);
		if ($this->db1->affectedRows() > 0) return true;
		else return false;
	}

	public function new_unidad($data)
	{

		$builder = $this->db1->table('unidades');
		$builder->insert($data);
		if ($this->db1->affectedRows() > 0) return true;
		else return false;
	}

	public function new_doc($data)
	{

		$builder = $this->db1->table('documentos');
		$builder->insert($data);
		if ($this->db1->affectedRows() > 0) return true;
		else return false;
	}

	public function new_reporte($data)
	{

		$builder = $this->db1->table('reportes');
		$builder->insert($data);
		if ($this->db1->affectedRows() > 0) return true;
		else return false;
	}

	public function new_ticket($data)
	{

		$builder = $this->db1->table('ticket');
		$builder->insert($data);
		if ($this->db1->affectedRows() > 0) return true;
		else return false;
	}


	public function update_doc($id, $data)
	{

		$builder = $this->db1->table('documentos');
		$builder->where('id', $id);
		if ($builder->update($data)) return true;
		else return false;
	}

	public function check_documento($id_socio, $tipo)
	{

		$builder = $this->db1->table('documentos');
		$builder->select('*');
		$builder->where('idsocio', $id_socio);
		$builder->where('tipo', $tipo);
		$query = $builder->get();

		if (count($query->getResult()) > 0) return $query->getRow();
		else return false;
	}

	public function get_ultima_unidad()
	{

		$builder = $this->db1->table('unidades');
		$builder->select('MAX(id) as ultimo');
		$query = $builder->get();
		if ($query->getRow()->ultimo > 0) return $query->getRow();
		else return false;
	}

	public function get_ultimo_socio()
	{

		$builder = $this->db1->table('socios');
		$builder->select('MAX(id) as ultimo');
		$query = $builder->get();
		if ($query->getRow()->ultimo > 0) return $query->getRow();
		else return false;
	}
	public function get_ultimo_concesionario()
	{

		$builder = $this->db1->table('concesionarios');
		$builder->select('MAX(id) as ultimo');
		$query = $builder->get();
		if ($query->getRow()->ultimo > 0) return $query->getRow();
		else return false;
	}

	public function get_ultimo_documento()
	{

		$builder = $this->db1->table('documentos');
		$builder->select('MAX(id) as ultimo');
		$query = $builder->get();
		if ($query->getRow()->ultimo > 0) return $query->getRow();
		else return false;
	}

	public function get_ultimo_reporte()
	{

		$builder = $this->db1->table('reportes');
		$builder->select('MAX(id) as ultimo');
		$query = $builder->get();
		if ($query->getRow()->ultimo > 0) return $query->getRow();
		else return false;
	}

	public function update_unidad($id, $data)
	{

		$builder = $this->db1->table('unidades');
		$builder->where('id', $id);
		if ($builder->update($data)) return true;
		else return false;
	}

	public function update_concesionario($id, $data)
	{

		$builder = $this->db1->table('concesionarios');
		$builder->where('id', $id);
		if ($builder->update($data)) return true;
		else return false;
	}

	public function update_reporte($id, $data)
	{

		$builder = $this->db1->table('reportes');
		$builder->where('id', $id);
		if ($builder->update($data)) return true;
		else return false;
	}

	public function delete_unidad($id)
	{
		$builder = $this->db1->table('unidades');
		$builder->where('id', $id);
		if ($builder->delete()) return true;
	}

	public function delete_respuesta($id)
	{
		$builder = $this->db1->table('ticket');
		$builder->where('id', $id);
		if ($builder->delete()) return true;
	}


	public function check_matricula($matricula)
	{
		$builder = $this->db1->table('unidades');
		$builder->select('matricula');
		$builder->where('matricula', $matricula);

		if ($builder->countAllResults() > 0) return true;
		else return false;
	}

	public function get_all_ubicaciones($where)
	{

		$builder = $this->db1->table('usuarios2 cli');
		$builder->select('cli.*,si.nombre as nombre_server,si.id as id_server');
		$builder->join('server se', 'cli.server = si.id');
		$builder->where($where);
		$builder->orderBy('cli.id', "ASC");
		$query = $builder->get();
		if ($query != false)	return $query->getResult();
		else return false;
	}
	public function get_ubicaciones($id_server)
	{

		$builder = $this->db1->table('usuarios2 cli');
		$builder->select('cli.*,si.nombre as nombre_server,si.id as id_server');
		$builder->join('server se', 'cli.server = si.id');
		$builder->where('server', $id_server);
		$builder->orderBy('cli.id', "ASC");
		$query = $builder->get();
		if ($query != false)	return $query->getResult();
		else return false;
	}

	public function get_tipodocumento()
	{

		$builder = $this->db1->table('tipodocumento');
		$builder->select('*');
		$builder->orderBy('id', "ASC");
		$query = $builder->get();
		if ($query != false)	return $query->getResult();
		else return false;
	}

	public function get_documentos_by_cliente($id_socio, $tipo, $permiso)
	{

		$builder = $this->db1->table('documentos do');
		$builder->select('do.*,cli.id as id_socio');
		$builder->join('usuarios2 cli', 'cli.id = do.idcliente');
		$builder->join('tipodocumento ti', 'ti.id = do.tipo');
		$builder->where('cli.id', $id_socio);
		$builder->where('do.tipo', $tipo);
		//$builder->where($permiso);
		$builder->orderBy('do.id', "ASC");
		$query = $builder->get();
		if ($query != false)	return $query->getResult();
		else return false;
	}


	public function get_reportes_by_socio($id_socio, $permiso)
	{

		$builder = $this->db1->table('reportes re');
		$builder->select('re.*,cli.id as id_socio');
		$builder->join('usuarios2 cli', 'cli.id = re.idsocio');
		$builder->where('re.idsocio', $id_socio);
		$builder->where($permiso);
		$builder->orderBy('re.id', "ASC");
		$query = $builder->get();
		if ($query != false)	return $query->getResult();
		else return false;
	}

	public function get_documentos_by_id($id, $where)
	{

		$builder = $this->db1->table('documentos do');
		$builder->select('do.*');
		$builder->join('usuarios2 cli', 'cli.id = do.idsocio');
		$builder->where('do.id', $id);
		$builder->where($where);
		$query = $builder->get();
		if ($query != false)	return $query->getRow();
		else return false;
	}


	public function get_reportes_by_id($id, $where)
	{

		$builder = $this->db1->table('reportes re');
		$builder->select('re.*, cli.domicilio,cli.nombre,cli.apellidos,cli.movil,si.nombre as nombre_server');
		$builder->join('usuarios2 cli', 'cli.id = re.idsocio');
		$builder->join('server se', 'si.id = cli.server');
		$builder->where('re.id', $id);
		$builder->where($where);
		$query = $builder->get();
		if ($query != false)	return $query->getRow();
		else return false;
	}


	public function get_tickets_by_reporte($id)
	{

		$builder = $this->db1->table('ticket');
		$builder->select('*');
		$builder->where('idreporte', $id);
		$builder->orderBy('id', 'DESC');
		$query = $builder->get();
		if ($query != false)	return $query->getResult();
		else return false;
	}


	public function aprobar_doc($id)
	{

		$builder = $this->db1->table('documentos');
		$builder->where('id', $id);
		if ($builder->update(['estado' => 'APROBADO'])) return true;
		else return false;
	}

	public function cerrar_re($id)
	{

		$builder = $this->db1->table('reportes');
		$builder->where('id', $id);
		if ($builder->update(['estado' => 'CERRADO'])) return true;
		else return false;
	}

	public function get_all_concesionarios($id, $permiso)
	{
		$builder = $this->db1->table('concesionarios c');
		$builder->select('c.*,si.nombre as nombre_server,cli.id as id_socio');
		$builder->join('usuarios2 cli', 'c.idsocio = cli.id');
		$builder->where("cli.id", $id);
		$builder->join('server se', 'cli.server = si.id');
		$builder->where($permiso);
		$builder->orderBy('c.id', "ASC");
		$query = $builder->get();
		if ($query != false) {
			return $query->getResult();
		} else {
			return false;
		}
	}

	public function new_concesionario($data)
	{
		$builder = $this->db1->table('concesionarios');
		$builder->insert($data);
		if ($this->db1->affectedRows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	public function delete_concesionario($id)
	{
		$builder = $this->db1->table('concesionarios');
		$builder->where('id', $id);
		if ($builder->delete()) {
			return true;
		} else {
			return false;
		}
	}

	public function get_all_coordenadas($nodo, $where)
	{
		$builder = $this->db1->table('usuarios2 u');
		$builder->select('t.mac,t.ipap,t.ip,u.movil,u.telefono,u.nombre,t.direccion,t.status_user,t.nodo,t.coordenadas,tv.zona');
		$builder->join('tblservicios t', 't.idcliente = u.id');
		$builder->join('tblavisouser tv', 'tv.cliente = u.id');
		if ($nodo != "") $builder->where('t.nodo', $nodo);
		$builder->where('u.estado', 'ACTIVO');
		$builder->where($where);
		$query = $builder->get();
		if ($query != false) {
			return $query->getResult();
		} else {
			return false;
		}
	}
	public function get_all_coordenadas2($sucursal, $nodo, $where)
	{
		$builder = $this->db1->table('usuarios2 u');
		$builder->select('t.mac,t.ipap,t.ip,u.movil,u.telefono,u.nombre,t.direccion,t.status_user,t.nodo,t.coordenadas,tv.zona');
		$builder->join('tblservicios t', 't.idcliente = u.id');
		$builder->join('tblavisouser tv', 'tv.cliente = u.id');
		if ($nodo != "") $builder->where('t.nodo', $nodo);
		$builder->where('u.estado', 'ACTIVO');
		// La sucursal elegida solo acota: siempre se limita a las del usuario (antes una
		// sucursal elegida reemplazaba al filtro y mostraba clientes de otra sucursal).
		if ($sucursal != "") {
			$builder->where('t.sucursal', (int) $sucursal);
		}
		\App\Libraries\FiltroSucursal::aplicar($builder, 't.sucursal');
		$query = $builder->get();
		if ($query != false) {
			return $query->getResult();
		} else {
			return false;
		}
	}
	public function get_all_coordenadas_server()
	{
		$builder = $this->db1->table('server s');
		$builder->select('s.estado,s.coordenadas,s.nodo,s.ip');
		// Solo routers de las sucursales del usuario (antes salían todos en el mapa)
		if (!\App\Libraries\FiltroSucursal::todas()) {
			$builder->where('s.id IN (SELECT idnodo FROM tblsucursalesnodo WHERE ' . \App\Libraries\FiltroSucursal::sql('idsucursal') . ')', null, false);
		}
		$query = $builder->get();
		if ($query != false) {
			return $query->getResult();
		} else {
			return false;
		}
	}


	public function get_user_by_id($id)
	{
		$builder = $this->db1->table('usuarios2');
		$builder->select('*');
		$builder->where('id', $id);
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}

	public function get_facturas_cliente($id)
	{
		$builder = $this->db1->table('facturas f');
		$builder->select('id,legal,emitido,vencimiento,estado,total,impuesto,tipo,cobrado,pago,forma');
		$builder->where('idcliente', $id);
		$builder->orderBy('f.id', 'DESC');
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	public function get_factura_cliente($id)
	{
		$builder = $this->db1->table('facturas f');
		$builder->select('f.impuesto,f.otros_impuestos,f.id,f.emitido,f.vencimiento,u.nombre,u.direccion_principal,u.telefono,u.movil');
		$builder->join('usuarios2 u', 'u.id=f.idcliente', 'left');
		$builder->where('f.id', $id);
		$query = $builder->get();
		return $query ? $query->getResult()[0] : false;
	}

	public function get_datos_empresa()
	{
		$builder = $this->db1->table('tblconfiguration');
		$builder->select('*');
		$builder->where('setting', 'ruc_empresa');
		$builder->orWhere('setting', 'nombre_empresa');
		$builder->orWhere('setting', 'telefono_empresa');
		$builder->orWhere('setting', 'direccion_empresa');
		$builder->orderBy('setting ASC');
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	public function get_factura_items($id)
	{
		$builder = $this->db1->table('facturaitems');
		$builder->select('descripcion,cantidad,unidades,impuesto');
		$builder->where('idfactura', $id);
		$builder->orderBy('cantidad DESC');
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	public function get_descuento_factura($id)
	{
		$builder = $this->db1->table('saldos');
		$builder->select('monto');
		$builder->where('iddestino', $id);
		$builder->where('monto<', 0);
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	public function new_usuario($data)
	{
		$builder = $this->db1->table('usuarios2');
		$builder->insert($data);
		$result = $this->db1->affectedRows();
		$id = $this->db1->insertID();
		return $result ? $id : false;
	}
	public function new_configuracion($data)
	{
		$builder = $this->db1->table('tblavisouser');
		$builder->insert($data);
		$result = $this->db1->affectedRows();
		$id = $this->db1->insertID();
		return $result ? $id : false;
	}
	public function new_servicio($data)
	{
		$builder = $this->db1->table('tblservicios');
		$builder->insert($data);
		$result = $this->db1->affectedRows();
		$id = $this->db1->insertID();
		return $result ? $id : false;
	}
	public function delete_clientes($id)
	{
		$builder = $this->db1->table('usuarios2');
		$builder->where('id', $id);
		return $builder->delete() ? true : false;
	}
	public function delete_configuracion($id)
	{
		$builder = $this->db1->table('tblavisouser');
		$builder->where('id', $id);
		return $builder->delete() ? true : false;
	}
	public function get_alll_ipv4($nodo)
	{
		$builder = $this->db1->table('ipv4');
		$builder->select('rangos,cidr,id,nombre');
		$builder->where('nodo', $nodo);
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	public function get_alll_ipv4_solo_rangos($nodo)
	{
		$builder = $this->db1->table('ipv4');
		$builder->select('rangos');
		$builder->where('nodo', $nodo);
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	public function get_cound_ips($nodo, $expl_mediu_ip)
	{
		$builder = $this->db1->table('tblservicios');
		$builder->select('COUNT(id) num_ips');
		$builder->where('nodo', $nodo);
		$builder->like('ip', $expl_mediu_ip, 'after');
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	//ejemplo, $medium_ip=172.29.0.
	public function ip_individual($nodo, $medium_ip)
	{
		$builder = $this->db1->table('tblservicios');
		$builder->select('ip');
		$builder->where('nodo', $nodo);
		$builder->like('ip', $medium_ip, 'after');
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}


	public function delete_instalacion($id)
	{
		$builder = $this->db1->table('instalaciones');
		$builder->where('id', $id);
		return $builder->delete() ? true : false;
	}
	public function last_id()
	{
		$builder = $this->db1->table('instalaciones');
		$builder->select('MAX(id) as last');
		$query = $builder->get();
		if ($query->getRow()->last > 0) {
			return $query->getRow();
		} else {
			return false;
		}
	}

	public function new_pre_install($data)
	{
		$builder = $this->db1->table('instalaciones');
		$builder->insert($data);
		$query = $this->db1->affectedRows();
		return ($query > 0) ? true : false;
	}

	public function update_pre_install($id, $state)
	{
		$builder = $this->db1->table('instalaciones');
		$builder->where('id', $id);
		$builder->update(['estate' => $state]);
		$result = $this->db1->affectedRows();
		return $result > 0 ? true : false;
	}

	public function get_pre_by_id($id)
	{
		$builder = $this->db1->table('instalaciones');
		$builder->select('*');
		$builder->where('id', $id);
		$query = $builder->get();
		return $query ? $query->getRow() : false;
	}
	public function get_servicio($id)
	{
		$builder = $this->db1->table('tblservicios');
		$builder->select('id,idcliente,idperfil,nodo,sucursal,descripcion,costo,mac,ip,ipap,instalado,antena,pppuser,ppppass,emisor,user_ubnt,pass_ubnt,tipoipv4,redipv4,coordenadas,direccion,snmp_comunidad,firewall_state');
		$builder->where('id', $id);
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	public function update_servicio($id, $data)
	{
		$builder = $this->db1->table('tblservicios');
		$builder->where('id', $id);
		$builder->update($data);
		$result = $this->db1->affectedRows();
		return $result > 0 ? true : false;
	}
	public function delete_servicio($id)
	{
		$builder = $this->db1->table('tblservicios');
		$builder->where('id', $id);
		return $builder->delete() ? true : false;
	}
	public function new_servicio_voip($data)
	{
		$builder = $this->db1->table('tblserviciosvoip');
		$builder->insert($data);
		$result = $this->db1->affectedRows();
		$id = $this->db1->insertID();
		return $result ? $id : false;
	}
	public function get_servicio_voip($idservicio)
	{
		$builder = $this->db1->table('tblserviciosvoip');
		$builder->select('id,cliente,server,sipuser,sipid,contrasena,codigoverificacion,numerotelefono,costo,instalacion,notas,idperfil,descripcion');
		$builder->where('id', $idservicio);
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	public function update_servicio_voip($id, $data)
	{
		$builder = $this->db1->table('tblserviciosvoip');
		$builder->where('id', $id);
		$builder->update($data);
		$result = $this->db1->affectedRows();
		return $result > 0 ? true : false;
	}
	public function delete_ervicio_voip($idservicio)
	{
		$builder = $this->db1->table('tblserviciosvoip');
		$builder->where('id', $idservicio);
		return $builder->delete() ? true : false;
	}
	public function insert_new_tmpfactura($data)
	{
		$builder = $this->db1->table('tmpfactura');
		$builder->insert($data);
		$result = $this->db1->affectedRows();
		$id = $this->db1->insertID();
		return $result ? $id : false;
	}
	public function insert_new_alquiler($data)
	{
		$builder = $this->db1->table('alquileres');
		$builder->insert($data);
		$result = $this->db1->affectedRows();
		$id = $this->db1->insertID();
		return $result ? $id : false;
	}
	public function get_servicios_recurrentes($id_cliente)
	{
		$builder = $this->db1->table('alquileres al');
		$builder->select('al.tipoa,al.id,p2.producto o_producto,p.producto,al.monto,am.serial_producto serie,am.mac_producto mac,al.fechaingreso fecha,al.ncuota,al.totalcuota,al.state estado');
		$builder->join('almacen am', 'am.id=al.productoid', 'left');
		$builder->join('productos p', 'p.id=am.productoid', 'left');
		$builder->join('productos p2', 'p2.id=al.productoid', 'left');
		$builder->where('al.userid', $id_cliente);
		$builder->orderBy('al.id ASC');
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	public function delete_servicio_recurrente($id)
	{
		$builder = $this->db1->table('alquileres');
		$builder->where('id', $id);
		return $builder->delete() ? true : false;
	}
	public function delete_servicio_un_solo_pago($id)
	{
		$builder = $this->db1->table('tmpfactura');
		$builder->where('id', $id);
		return $builder->delete() ? true : false;
	}
	public function get_servicio_recurrente($idservicio)
	{
		$builder = $this->db1->table('alquileres al');
		$builder->select('p.producto nombre,p2.producto o_nombre,al.productoid,al.tipoa');
		$builder->join('almacen am', 'am.id=al.productoid', 'left');
		$builder->join('productos p', 'p.id=am.productoid', 'left');
		$builder->join('productos p2', 'p2.id=al.productoid', 'left');
		$builder->where('al.id', $idservicio);
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	public function get_servicio_un_solo_pago($idservicio)
	{
		$builder = $this->db1->table('tmpfactura t');
		$builder->select('p.producto nombre,p2.producto o_nombre,t.productoid,t.tipoa');
		$builder->join('almacen am', 'am.id=t.productoid', 'left');
		$builder->join('productos p', 'p.id=am.productoid', 'left');
		$builder->join('productos p2', 'p2.id=t.productoid', 'left');
		$builder->where('t.id', $idservicio);
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	public function get_servicios_un_pago($id_cliente)
	{
		$builder = $this->db1->table('tmpfactura t');
		$builder->select('t.id,t.tipoa,p2.producto o_producto,p.producto,t.monto,a.serial_producto serie,a.mac_producto mac,t.fechaingreso fecha,t.nfactura factura,t.state estado');
		$builder->join('almacen a', 'a.id=t.productoid', 'left');
		$builder->join('productos p', 'p.id=a.productoid', 'left');
		$builder->join('productos p2', 'p2.id=t.productoid', 'left');
		$builder->where('t.userid', $id_cliente);
		$builder->orderBy('t.id ASC');
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	//funciones agregadas instalaciones
	public function get_materials_add($id)
	{
		$builder = $this->db1->table('materiales m');
		$builder->select("m.id, m.idalmacen, m.idinstall, m.tipo, p.producto, a.mac_producto, a.serial_producto , m.condicion");
		$builder->join('almacen a', 'm.idalmacen=a.id', 'left');
		$builder->join('productos p', 'a.productoid=p.id', 'left');
		$builder->join('usuarios2 u', 'a.userid=u.id', 'left');
		$builder->where('m.tipopro', 'producto');
		$builder->where('m.idinstall', $id);
		$builder->orderBy('m.id', 'DESC');
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}

	public function get_accesorios_add($id)
	{
		$builder = $this->db1->table('materiales');
		$builder->select("*");
		$builder->where('tipopro', 'accesorio');
		$builder->where('idinstall', $id);
		$builder->orderBy('id', 'DESC');
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}

	public function get_instal_by_id($id)
	{
		$builder = $this->db1->table('instalaciones i');
		$builder->select("i.*, l.nombre as tecnico");
		$builder->join('login l', 'l.id = i.idtecnico', 'left');
		$builder->where('i.id', $id);
		$builder->orderBy('id', 'DESC');
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}

	public function get_p_a_by_id($id)
	{
		$builder = $this->db1->table('almacen');
		$builder->select('*');
		$builder->where('id', $id);
		$builder->orderBy('id ASC');
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}

	public function get_p_by_id($id)
	{
		$builder = $this->db1->table('productos');
		$builder->select('*');
		$builder->where('id', $id);
		$builder->orderBy('id ASC');
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}

	public function insert_materiales($data)
	{
		$builder = $this->db1->table('materiales');
		$builder->insert($data);
		$query = $this->db1->affectedRows();
		return ($query > 0) ? true : false;
	}

	public function update_estado_almacen($id, $data)
	{
		$builder = $this->db1->table('almacen');
		$builder->where('id', $id);
		$builder->update($data);
		$result = $this->db1->affectedRows();
		return $result > 0 ? true : false;
	}

	public function update_stock_almacen($id, $fecha, $cantidad) //restar de almacen
	{
		$builder = $this->db1->table('almacen');
		$builder->set('fecha_salida', $fecha);
		$builder->set('cantidad', 'cantidad - ' . $cantidad . '', false);
		$builder->where('id', $id);
		$builder->update();
		$result = $this->db1->affectedRows();
		return $result ? true : false;
	}

	public function update_stock_almacen_mas($id, $cantidad) //sumar de almacen
	{
		$builder = $this->db1->table('almacen');
		$builder->set('cantidad', 'cantidad + ' . $cantidad . '', false);
		$builder->where('id', $id);
		$builder->update();
		$result = $this->db1->affectedRows();
		return $result ? true : false;
	}

	public function insert_tmpfactura($data)
	{
		$builder = $this->db1->table('tmpfactura');
		$builder->insert($data);
		$query = $this->db1->affectedRows();
		return ($query > 0) ? true : false;
	}
	public function insert_alquileres($data)
	{
		$builder = $this->db1->table('alquileres');
		$builder->insert($data);
		$query = $this->db1->affectedRows();
		return ($query > 0) ? true : false;
	}

	public function get_material_by_id($id)
	{
		$builder = $this->db1->table('materiales');
		$builder->select('*');
		$builder->where('id', $id);
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}

	public function delete_materials($id)
	{
		$builder = $this->db1->table('materiales');
		$builder->where('id', $id);
		return $builder->delete() ? true : false;
	}

	public function delete_tmpfactura($id)
	{
		$builder = $this->db1->table('tmpfactura');
		$builder->where('productoid', $id);
		return $builder->delete() ? true : false;
	}
	public function delete_tmpfactura_cliente($idcliente)
	{
		$builder = $this->db1->table('tmpfactura');
		$builder->where('userid', $idcliente);
		return $builder->delete() ? true : false;
	}

	public function delete_alquileres($id)
	{
		$builder = $this->db1->table('alquileres');
		$builder->where('productoid', $id);
		return $builder->delete() ? true : false;
	}

	public function get_tmpfactura_by_idalmacen($id)
	{
		$builder = $this->db1->table('tmpfactura');
		$builder->select('*');
		$builder->where('productoid', $id);
		$builder->orderBy('id ASC');
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}

	public function get_alquiler_by_idalmacen($id)
	{
		$builder = $this->db1->table('alquileres');
		$builder->select('*');
		$builder->where('productoid', $id);
		$builder->orderBy('id ASC');
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}

	public function update_almacen_simple($id, $state)
	{
		$builder = $this->db1->table('almacen');
		$builder->where('id', $id);
		$builder->update(['estado' => $state]);
		$result = $this->db1->affectedRows();
		return $result > 0 ? true : false;
	}

	public function save_update_pre_install($id, $data)
	{
		$builder = $this->db1->table('instalaciones');
		$builder->where('id', $id);
		$builder->update($data);
		$result = $this->db1->affectedRows();
		return $result > 0 ? true : false;
	}

	public function delete_materials_by($id)
	{
		$builder = $this->db1->table('materiales');
		$builder->where('idinstall', $id);
		return $builder->delete() ? true : false;
	}

	public function get_sucurisal_byid($id)
	{
		$builder = $this->db1->table('sucursal');
		$builder->select("*");
		$builder->where('id', $id);
		$builder->orderBy('id', 'DESC');
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}

	public function get_servicios_byid($id)
	{
		$builder = $this->db1->table('tblservicios');
		$builder->select("*");
		$builder->where('id', $id);
		$builder->orderBy('id', 'DESC');
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	//end funciones instalaciones

	// inicio funciones usuarios2

	public function delete_join_facturas_items($idcliente)
	{
		$query = $this->db1->query("
			DELETE i.* FROM facturaitems i
			INNER JOIN
				 facturas f ON i.idfactura = f.id
			WHERE
				 f.id=$idcliente;
		");
		$result = $this->db1->affectedRows();
		return  $result >= 0 ? true : false;
	}
	public function delete_facturas($idcliente)
	{
		$builder = $this->db1->table('facturas');
		$builder->where('idcliente', $idcliente);
		return $builder->delete() ? true : false;
	}
	public function delete_saldos($idcliente)
	{
		$builder = $this->db1->table('saldos');
		$builder->where('iduser', $idcliente);
		return $builder->delete() ? true : false;
	}
	public function delete_instalaciones($idcliente)
	{
		$builder = $this->db1->table('instalaciones');
		$builder->where('userid', $idcliente);
		return $builder->delete() ? true : false;
	}
	public function delete_servicios_voip($idcliente)
	{
		$builder = $this->db1->table('tblserviciosvoip');
		$builder->where('cliente', $idcliente);
		return $builder->delete() ? true : false;
	}
	public function delete_servicios($idcliente)
	{
		$builder = $this->db1->table('tblservicios');
		$builder->where('idcliente', $idcliente);
		return $builder->delete() ? true : false;
	}
	public function delete_avisouser($idcliente)
	{
		$builder = $this->db1->table('tblavisouser');
		$builder->where('cliente', $idcliente);
		return $builder->delete() ? true : false;
	}
	public function delete_alquiler($idcliente)
	{
		$builder = $this->db1->table('alquileres');
		$builder->where('userid', $idcliente);
		return $builder->delete() ? true : false;
	}
	public function delete_operaciones($idcliente)
	{
		$builder = $this->db1->table('operaciones');
		$builder->where('idcliente', $idcliente);
		return $builder->delete() ? true : false;
	}
	public function delete_usuario($idcliente)
	{
		$builder = $this->db1->table('usuarios2');
		$builder->where('id', $idcliente);
		return $builder->delete() ? true : false;
	}
	public function update_cliente($id_cliente, $data_cliente)
	{
		$builder = $this->db1->table('usuarios2');
		$builder->where('id', $id_cliente);
		$builder->update($data_cliente);
		$result = $this->db1->affectedRows();
		return $result;
	}
	public function update_cliente_zona($id_cliente, $data_cliente)
	{
		$builder = $this->db1->table('tblavisouser');
		$builder->where('cliente', $id_cliente);
		$builder->update($data_cliente);
		$result = $this->db1->affectedRows();
		return $result;
	}
	public function update_cliente_private($id_cliente, $data_cliente)
	{
		$builder = $this->db1->table('tbldatauserprivate');
		$builder->where('iduser', $id_cliente);
		$builder->update($data_cliente);
		$result = $this->db1->affectedRows();
		return $result;
	}

	public function insert_cliente_private($data_cliente)
	{
		$builder = $this->db1->table('tbldatauserprivate');
		$builder->insert($data_cliente);
		$result = $this->db1->affectedRows();
		return $result;
	}

	public function check_folio_tabla_private($id_cliente)
	{
		$builder = $this->db1->table('tbldatauserprivate');
		$builder->select('*');
		$builder->where('iduser', $id_cliente);

		if ($builder->countAllResults() > 0) return true;
		else return false;
	}
	public function update_almacen($idcliente, $data)
	{
		$builder = $this->db1->table('almacen');
		$builder->where('userid', $idcliente);
		$builder->update($data);
		$result = $this->db1->affectedRows();
		return $result > 0 ? true : false;
	}
	public function get_servicio_cliente($idcliente)
	{
		$builder = $this->db1->table('tblservicios');
		$builder->select('*');
		$builder->where('idcliente', $idcliente);
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	public function update_tblavisouser($idcliente, $data)
	{
		$builder = $this->db1->table('tblavisouser');
		$builder->where('cliente', $idcliente);
		$builder->update($data);
		$result = $this->db1->affectedRows();
		return $result > 0 ? true : false;
	}

	function get_user_data_retirado($id_cliente)
	{

		$builder = $this->db1->table('usuarios2 u');
		$builder->select('u.*,u.id as id_user,ta.zona,ta.avatar_cliente,ta.avatar_color,u.direccion_principal direccion');
		$builder->join('tblavisouser ta', 'ta.cliente = u.id');
		$builder->where('u.id', $id_cliente);
		$query = $builder->get();
		if ($query != false)	return $query->getRow();
		else return false;
	}

	// END funciones de usuarios2

	// funciones de mikrowisp
	public function get_server($idnodo)
	{
		$builder = $this->db1->table('server s');
		$builder->select('s.ip,s.usuario,s.contrasena,s.port_api puerto');
		$builder->where('s.id', $idnodo);
		$builder->limit(1);
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}

	public function get_velocidades($idperfil)
	{
		$builder = $this->db1->table('perfiles');
		$builder->select('*');
		$builder->where('id', $idperfil);
		$builder->limit(1);
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}

	public function get_servicios_server($idcliente)
	{
		$builder = $this->db1->table('tblservicios t');
		$builder->select('t.id idservicio,t.ip ipcliente,u.nombre,s.ip,s.usuario,s.contrasena,s.port_api puerto');
		$builder->join('usuarios2 u', 'u.id=t.idcliente', 'left');
		$builder->join('server s', 's.id=t.nodo', 'left');
		$builder->where('idcliente', $idcliente);
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}

	public function get_server_servicio($idservicio)
	{
		$builder = $this->db1->table('tblservicios t');
		$builder->select('u.nombre,s.port_api puerto,s.usuario,s.contrasena,s.ip');
		$builder->join('server s', 't.nodo=s.id');
		$builder->join('usuarios2 u', 'u.id=t.idcliente');
		$builder->where('t.id', $idservicio);
		$query = $builder->get();
		return $query ? $query->getRow() : false;
	}
	public function get_new_server($idserver)
	{
		$builder = $this->db1->table('server s');
		$builder->select('s.port_api puerto,s.usuario,s.contrasena,s.ip');
		$builder->where('s.id', $idserver);
		$query = $builder->get();
		return $query ? $query->getRow() : false;
	}
	// END funciones de mikrowisp
	public function get_historial_personal($idusuario)
	{
		$builder = $this->db1->table('logsistema');
		$builder->select('*');
		$builder->where('operador', $idusuario);
		$builder->orderBy('id DESC');
		$builder->limit(50);
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	public function get_macs(string $mac)
	{
		$builder = $this->db1->table('tblservicios t');
		$builder->select('u.nombre,t.idcliente');
		$builder->where('mac', $mac);
		$builder->join('usuarios2 u', 'u.id=t.idcliente');
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	public function get_velocidades_row($idperfil)
	{
		$builder = $this->db1->table('perfiles');
		$builder->select('*');
		$builder->where('id', $idperfil);
		$query = $builder->get();
		return $query ? $query->getRow() : false;
	}
	public function get_server_info($id)
	{
		$builder = $this->db1->table('server s');
		$builder->select('s.ip,s.usuario,s.contrasena,s.port_api puerto');
		$builder->where('s.id', $id);
		$query = $builder->get();
		return $query ? $query->getRow() : false;
	}
	public function get_servicios(int $idnodo)
	{
		$builder = $this->db1->table('tblservicios t');
		$builder->select('i.avatar_cliente,u.id id_cliente,t.idperfil,u.estado,t.id idservicio,t.ip ip_servicio,u.nombre');
		$builder->join('usuarios2 u', 'u.id=t.idcliente');
		$builder->join('server s', 's.id=t.nodo');
		$builder->join('tblavisouser i', 'i.cliente=t.idcliente');
		$builder->where('t.ip<>', "");
		$builder->where('u.estado<>', "RETIRADO");
		$builder->where('t.nodo', $idnodo);
		$builder->orderBy('t.id ASC');
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	public function get_sms_cliente(int $idcliente)
	{
		$builder = $this->db1->table('smsmensajes');
		$builder->select('*');
		$builder->where('user', $idcliente);
		$builder->orderBy('id DESC');
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	public function get_notas_cliente(int $idcliente)
	{
		$builder = $this->db1->table('notas n');
		$builder->select("n.*,IF(l.nombre='','Usuario eliminado',l.nombre) nombre");
		$builder->join('login l', 'n.idoperador=l.id', 'left');
		$builder->where('n.idcliente', $idcliente);
		$builder->orderBy('n.id DESC');
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	public function delete_nota(int $id)
	{
		$builder = $this->db1->table('notas');
		$builder->where('id', $id);
		return $builder->delete() ? true : false;
	}
	public function get_nota($id)
	{
		$builder = $this->db1->table('notas');
		$builder->select('*');
		$builder->where('id', $id);
		$query = $builder->get();
		return $query ? $query->getRow() : false;
	}
	public function new_nota($data)
	{
		$builder = $this->db1->table('notas');
		$builder->insert($data);
		$result = $this->db1->affectedRows();
		$id = $this->db1->insertID();
		return $result ? $id : false;
	}
	public function new_documento($data)
	{
		$builder = $this->db1->table('documentos');
		$builder->insert($data);
		$result = $this->db1->affectedRows();
		$id = $this->db1->insertID();
		return $result ? $id : false;
	}
	public function edit_documento($id, $data)
	{
		$builder = $this->db1->table('documentos');
		$builder->where('id', $id);
		$builder->update($data);
		$result = $this->db1->affectedRows();
		return $result > 0 ? true : false;
	}
	public function get_documento($id)
	{
		$builder = $this->db1->table('documentos');
		$builder->select('*');
		$builder->where('id', $id);
		$query = $builder->get();
		return $query ? $query->getRow() : false;
	}
	public function get_documentos_cliente()
	{
		$builder = $this->db1->table('documentos d');
		$builder->select('d.*,l.nombre');
		$builder->join('login l', "d.operador=l.id", "left");
		$builder->orderBy('d.id DESC');
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	public function delete_documento($id)
	{
		$builder = $this->db1->table('documentos');
		$builder->where('id', $id);
		return $builder->delete() ? true : false;
	}
	public function get_all_geocercas()
	{
		$builder = $this->db1->table('geocercas');
		$builder->select('*');
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	public function save_geocerca(array $data)
	{
		$builder = $this->db1->table('geocercas');
		$builder->insert($data);
		$result = $this->db1->affectedRows();
		$id = $this->db1->insertID();
		return $result ? $id : false;
	}
	public function delete_geocerca($id)
	{
		$builder = $this->db1->table('geocercas');
		$builder->where('id', $id);
		return $builder->delete() ? true : false;
	}
	public function update_geocerca($id, $data)
	{
		$builder = $this->db1->table('geocercas');
		$builder->where('id', $id);
		$builder->update($data);
		$result = $this->db1->affectedRows();
		return $result > 0 ? true : false;
	}
	public function update_color_poligono($id, $data)
	{
		$builder = $this->db1->table('geocercas');
		$builder->where('id', $id);
		$builder->update($data);
		$result = $this->db1->affectedRows();
		return $result > 0 ? true : false;
	}
}
