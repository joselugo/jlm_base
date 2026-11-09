<?php

namespace App\Models;

use CodeIgniter\Model;

#[\AllowDynamicProperties]
class Model_bdatabase extends Model
{
	var $db1;
	function __construct()
	{
		$db1 = \Config\Database::connect('default');
		$this->db1 = $db1;
	}

	public function get_tables_database()
	{
		$builder = $this->db1->table('information_schema.tables');
		$builder->select('table_name,table_rows,data_length');
		$builder->where('table_schema', 'Mikrowisp6');
		$query = $builder->get();
		if ($query != false) {
			return $query->getResult();
		} else {
			return false;
		}
	}

	public function get_size_MB()
	{
		$builder = $this->db1->table('information_schema.TABLES');
		$builder->select('table_schema as database, ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) as Tamaño');
		$builder->where('table_schema', 'Mikrowisp6');
		$query = $builder->get();
		return $query ? $query->getRow() : false;
	}

	public function get_all_columns()
	{
		$builder = $this->db1->table('information_schema.columns');
		$builder->select('column_name');
		$builder->where('table_name', 'usuarios2');
		$builder->where('table_schema', 'Mikrowisp6');
		$query = $builder->get();
		if ($query != false) {
			$row = $query->getResult();
			$option = "";
			foreach ($row as $items) {
				$option .= "<option value='" . $items->COLUMN_NAME . "'>" . $items->COLUMN_NAME . "</option>";
			}
			return $option;
		} else {
			return false;
		}
	}

	public function get_all_tables()
	{
		$builder = $this->db1->table('information_schema.tables');
		$builder->select('table_name');
		$builder->where('table_schema', 'Mikrowisp6');
		$query = $builder->get();
		if ($query != false) {
			$row = $query->getResult();
			$option = "";
			foreach ($row as $items) {
				$option .= "<option value='" . $items->TABLE_NAME . "'>" . $items->TABLE_NAME . "</option>";
			}
			return $option;
		} else {
			return false;
		}
	}

	// Columnas propias de la tabla usuarios: la pantalla de campos personalizados no puede borrarlas
	private const COLUMNAS_BASE_USUARIOS = ['id', 'nombre', 'estado', 'correo', 'telefono', 'movil', 'cedula', 'pasarela', 'codigo', 'direccion_principal'];

	// Agrega un campo personalizado a usuarios. El nombre llega del formulario: se
	// valida y se escapa como identificador (antes se concatenaba en el ALTER)
	public function new_campo($data)
	{
		if (! is_string($data) || ! preg_match('/^[A-Za-z][A-Za-z0-9_]{0,63}$/', $data) || $this->db1->fieldExists($data, 'usuarios')) {
			return false;
		}
		return $this->db1->query('ALTER TABLE usuarios ADD ' . $this->db1->escapeIdentifiers($data) . ' varchar(100)') !== false;
	}

	public function delete_campo($data)
	{
		if (! is_string($data) || ! preg_match('/^[A-Za-z][A-Za-z0-9_]{0,63}$/', $data)
			|| in_array(strtolower($data), self::COLUMNAS_BASE_USUARIOS, true) || ! $this->db1->fieldExists($data, 'usuarios')) {
			return false;
		}
		return $this->db1->query('ALTER TABLE usuarios DROP ' . $this->db1->escapeIdentifiers($data)) !== false;
	}

	public function empytable($item)
	{
		// El nombre llega del formulario: solo tablas existentes de la BD configurada
		// (antes se concatenaba tal cual y apuntaba a la BD fija Mikrowisp6)
		if (! is_string($item) || ! preg_match('/^[A-Za-z0-9_]+$/', $item) || ! $this->db1->tableExists($item, false)) {
			return false;
		}
		$query = $this->db1->query('TRUNCATE ' . $this->db1->escapeIdentifiers($item));
		if ($query != false) {
			return $query->getResult();
		} else {
			return false;
		}
	}

	// =============================================== System ====================================
	public function get_log_conexion()
	{
		$builder = $this->db1->table('logsistema');
		$builder->select('count(id) as total');
		$builder->where('tipolog', 1);
		$query = $builder->get();
		return $query ? $query->getRow() : false;
	}

	public function get_emails()
	{
		$builder = $this->db1->table('mail');
		$builder->select('count(id) as total');
		$query = $builder->get();
		return $query ? $query->getRow() : false;
	}

	public function get_log_sistema()
	{
		$builder = $this->db1->table('logsistema');
		$builder->select('count(id) as total');
		$builder->where('tipolog', !1);
		$query = $builder->get();
		return $query ? $query->getRow() : false;
	}

	public function purgeconexion($where)
	{
		$builder = $this->db1->table('logsistema');
		$builder->where('tipolog', 1);
		$builder->where('fechalog <', $where);
		return $builder->delete() ? true : false;
	}

	public function purgeemail($where)
	{
		$builder = $this->db1->table('mail');
		$builder->where('fechalog <', $where);
		return $builder->delete() ? true : false;
	}

	public function purgesistema($where)
	{
		$builder = $this->db1->table('logsistema');
		$builder->where('tipolog', !1);
		$builder->where('fechalog <', $where);
		return $builder->delete() ? true : false;
	}
}
