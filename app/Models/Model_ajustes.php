<?php

namespace App\Models;

use CodeIgniter\Model;

#[\AllowDynamicProperties]
class Model_ajustes extends Model
{
	var $db1;
	function __construct()
	{
		$db1 = \Config\Database::connect('default');
		$this->db1 = $db1;
	}


	public function get_cloud($tipo)
	{
		$builder = $this->db1->table('cloud');
		$builder->select('*');
		$builder->where('tipo', $tipo);
		$query = $builder->get();
		if ($query != false)	return $query->getRow();
		else return false;
	}

	public function update_cloud($data, $tipo)
	{

		$builder = $this->db1->table('cloud');
		$builder->where('tipo', $tipo);
		if ($builder->update($data)) return true;
		else return false;
	}
	public function save_nodo_sucursal($data){
		$builder = $this->db1->table('tblsucursalesnodo');
		$builder->insert($data);
		$result = $this->db1->affectedRows();
		$id = $this->db1->insertID();
		return $result ? $id : false;
	}
	public function delete_nodo_sucursal($idsucursal){
		$builder = $this->db1->table('tblsucursalesnodo');
		$builder->where('idsucursal', $idsucursal);
		return $builder->delete() ? true : false;
	}
}
