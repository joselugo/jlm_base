<?php

namespace App\Models;

use CodeIgniter\Model;

#[\AllowDynamicProperties]
class Model_sistema extends Model
{
	var $db1;
	function __construct()
	{
		$db1 = \Config\Database::connect('default');
		$this->db1 = $db1;
	}

	public function traer_permisos_sistema($padre = 0)
	{

		$builder = $this->db1->table('permiso');
		$builder->select('*');
		$builder->where('padre', $padre);
		$builder->where('activo', 1);
		$builder->orderBy('orden', "ASC");
		$query = $builder->get();
		if ($query != false)	return $query->getResult();
		else return false;
	}

	public function traer_widgets($activo=3)
	{

		$builder = $this->db1->table('permiso');
		$builder->select('*');
		$builder->where('activo', $activo);
		$builder->orderBy('orden', "ASC");
		$query = $builder->get();
		if ($query != false)	return $query->getResult();
		else return false;
	}

	public function traer_permisos_especiales_sistema($padre = 0)
	{

		$builder = $this->db1->table('permiso');
		$builder->select('*');
		$builder->where('activo', 2);
		$builder->where('padre', $padre);
		$builder->orderBy('orden', "ASC");
		$query = $builder->get();
		if ($query != false)	return $query->getResult();
		else return false;
	}

	public function check_hijos($padre)
	{
		$builder = $this->db1->table('permiso');
		$builder->select('id_permiso');
		$builder->where('padre', $padre);
		$builder->where('activo', 1);

		if ($builder->countAllResults() > 0) return true;
		else return false;
	}

	public function check_permiso_by_id($id, $id_permiso)
	{
		$builder = $this->db1->table('login_permiso');
		$builder->select('*');
		$builder->where('id_permiso', $id_permiso);
		$builder->where('id_usuario', $id);


		if ($builder->countAllResults() > 0) return true;
		else return false;
	}

	public function check_tipo_permiso_by_id($id, $id_permiso)
	{
		$builder = $this->db1->table('login_permiso');
		$builder->select('*');
		$builder->where('id_permiso', $id_permiso);
		$builder->where('id_usuario', $id);
		$query = $builder->get();


		if ($query != false) return $query->getRow();
		else return false;
	}

	public function get_permisos_rol($id)
	{

		$builder = $this->db1->table('rol_permiso');
		$builder->select('*');
		$builder->where('idrol', $id);
		$builder->orderBy('id', "ASC");
		$query = $builder->get();
		if ($query != false)	return $query->getResult();
		else return false;
	}

	public function update_perfil($id, $data)
	{

		$builder = $this->db1->table('login');
		$builder->where('id', $id);
		if ($builder->update($data)) return true;
		else return false;
	}

	public function delete_permiso_by_usuario($id_usuario)
	{
		$builder = $this->db1->table('login_permiso');
		$builder->where('id_usuario', $id_usuario);
		if ($builder->delete()) return true;
		else return false;
	}

	public function insert_permiso_by_usuario($id_permiso, $id_usuario, $tipo_acceso)
	{
		$builder = $this->db1->table('login_permiso');
		$builder->insert(['id_permiso' => $id_permiso, 'id_usuario' => $id_usuario, 'tipo_acceso' => $tipo_acceso]);
		if ($this->db1->affectedRows() > 0) return true;
		else return false;
	}

	public function new_perfil($data)
	{

		$builder = $this->db1->table('login');
		$builder->insert($data);
		if ($this->db1->affectedRows() > 0) return true;
		else return false;
	}
	public function get_last_login()
	{

		$builder = $this->db1->table('login');
		$builder->select('MAX(id) as ultimo');
		$query = $builder->get();
		if ($query != false)	return $query->getRow();
		else return false;
	}

	public function delete_operador($id_usuario)
	{
		$builder = $this->db1->table('login');
		$builder->where('id', $id_usuario);
		if ($builder->delete()) return true;
		else return false;
	}
	public function traer_usuarios_by_id($id)
	{

		$builder = $this->db1->table('login');
		$builder->select('*');
		$builder->where('id', $id);


		$query = $builder->get();
		if ($query != false)	return $query->getRow();
		else return false;
	}
	public function get_cumpleanios($mes, $dia)
	{
		$builder = $this->db1->table('login');
		$builder->select('nombre');
		$builder->where('MONTH(fecha_nacimiento)', $mes);
		$builder->where('DAY(fecha_nacimiento)', $dia);
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	public function update_check_cumpleanios($id, $data)
	{
		$builder = $this->db1->table('login');
		$builder->where('id', $id);
		$builder->update($data);
		$result = $this->db1->affectedRows();
		return $result > 0 ? true : false;
	}
	public function get_check($id)
	{
		$builder = $this->db1->table('login');
		$builder->select('id');
		$builder->where('id', $id);
		$builder->where('check_cumple_dia', 0);
		$query = $builder->get();
		return $query ? $query->getResult() : false;
	}
	public function get_datos_perfil($id)
	{
		$builder = $this->db1->table('login');
		$builder->select('nombre,correo,movil,fb,twitter,timeout');
		$builder->where('id', $id);
		$query = $builder->get();
		return $query ? $query->getRow() : false;
	}
}
