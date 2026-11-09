<?php namespace App\Models;
use CodeIgniter\Model;
#[\AllowDynamicProperties]
class Model_api extends Model {
 var $db1;
	function __construct()
	{
		$db1 = \Config\Database::connect('default');
		$this->db1=$db1;
	}


		public function get_data_socio($id)
	{

		$builder = $this->db1->table('socios so');
		$builder->select('so.*,si.nombre as nombre_sindicato');
		$builder->join('sindicato si', 'so.sindicato = si.id');
		$builder->where('so.id',$id);
        $query=$builder->get();
		if($query != false)	return $query->getRow();
		else return false;
	}

		public function get_ultimo_reporte($id_socio)
	{
        
		$builder = $this->db1->table('reportes');
        $builder->select('MAX(fechaalta) as ultimo');
        $builder->where('idsocio',$id_socio);
        $query=$builder->get();
		if($query->getRow()->ultimo>0)return $query->getRow();
		else return false;
	}

		public function get_all_unidades($where)
	{

		$builder = $this->db1->table('unidades u');
		$builder->select('u.*,so.sindicato,so.id as id_socio,so.nombre,so.apellidos');
		$builder->join('socios so', 'so.id = u.idsocio');
		$builder->where($where);
        $query=$builder->get();
		if($query != false)	return $query->getResult();
		else return false;
	}

		public function get_unidad_by_id($id,$where)
	{

		$builder = $this->db1->table('unidades u');
		$builder->select('u.*,so.sindicato');
		$builder->join('socios so', 'so.id = u.idsocio');
		$builder->where('u.id', $id);
		$builder->where($where);
        $query=$builder->get();
		if($query != false)	return $query->getRow();
		else return false;
	}


		public function get_max_codigo($sindicato)
	{
        
		$builder = $this->db1->table('unidades');
        $builder->select('MAX(codigosindicato) as ultimo');
        $builder->where('sindicato',$sindicato);
        $query=$builder->get();
		if($query->getRow()->ultimo>0)return $query->getRow();
		else return false;
	}

		public function new_unidad($data)
	{

		$builder = $this->db1->table('unidades');
        $builder->insert($data);
		if($this->db1->affectedRows()>0)return true;
		else return false;
	}

		public function get_ultima_unidad()
	{
        
		$builder = $this->db1->table('unidades');
        $builder->select('MAX(id) as ultimo');
        $query=$builder->get();
		if($query->getRow()->ultimo>0)return $query->getRow();
		else return false;
	}

		public function update_unidad($id,$data)
	{

		$builder = $this->db1->table('unidades');
		$builder->where('id', $id);
		if($builder->update($data))return true;
		else return false;
	}

		public function delete_unidad($id)
	{   
		$builder = $this->db1->table('unidades');
		$builder->where('id', $id); 
		if($builder->delete()) return true;
	}

    
	public function check_matricula($matricula)
	{
		$builder = $this->db1->table('unidades');
		$builder->select('matricula');
		$builder->where('matricula',$matricula);
       
		if($builder->countAllResults()>0) return true;
		else return false;
	}	
		

		public function traer_permisos_especiales_sistema($padre=0)
	{

		$builder = $this->db1->table('permiso');
		$builder->select('*');
        $builder->where('activo',2);
        $builder->where('padre',$padre);
        $builder->orderBy('id', "ASC");
        $query=$builder->get();
		if($query != false)	return $query->getResult();
		else return false;
	}

	public function check_hijos($padre)
	{
		$builder = $this->db1->table('permiso');
		$builder->select('id_permiso');
		$builder->where('padre',$padre);
        $builder->where('activo',1);
       
		if($builder->countAllResults()>0) return true;
		else return false;
	}

	public function check_permiso_by_id($id,$id_permiso)
	{
		$builder = $this->db1->table('login_permiso');
		$builder->select('*');
		$builder->where('id_permiso',$id_permiso);
		$builder->where('id_usuario',$id);
        
       
		if($builder->countAllResults()>0) return true;
		else return false;
	}

	public function check_tipo_permiso_by_id($id,$id_permiso)
	{
		$builder = $this->db1->table('login_permiso');
		$builder->select('*');
		$builder->where('id_permiso',$id_permiso);
		$builder->where('id_usuario',$id);
		$query=$builder->get();
        
       
		if($query != false) return $query->getRow();
		else return false;
	}

		public function get_permisos_rol($id)
	{

		$builder = $this->db1->table('rol_permiso');
		$builder->select('*');
        $builder->where('idrol',$id);
        $builder->orderBy('id', "ASC");
        $query=$builder->get();
		if($query != false)	return $query->getResult();
		else return false;
	}

		public function update_perfil($id,$data)
	{

		$builder = $this->db1->table('login');
		$builder->where('id', $id);
		if($builder->update($data))return true;
		else return false;
	}

	  public function delete_permiso_by_usuario($id_usuario)
	{   
		$builder = $this->db1->table('login_permiso');
		$builder->where('id_usuario', $id_usuario); 
		if($builder->delete()) return true;
		else return false;
	}

	  public function insert_permiso_by_usuario($id_permiso,$id_usuario,$tipo_acceso)
	{   
		$builder = $this->db1->table('login_permiso');
        $builder->insert(['id_permiso' =>$id_permiso , 'id_usuario' =>$id_usuario , 'tipo_acceso' =>$tipo_acceso]);
		if( $this->db1->affectedRows()>0) return true;
		else return false;
	}

		public function new_perfil($data)
	{

		$builder = $this->db1->table('login');
        $builder->insert($data);
		if($this->db1->affectedRows()>0)return true;
		else return false;
	}
		public function get_last_login()
	{

		$builder = $this->db1->table('login');
        $builder->select('MAX(id) as ultimo');
        $query=$builder->get();
		if($query != false)	return $query->getRow();
		else return false;
	}

		  public function delete_operador($id_usuario)
	{   
		$builder = $this->db1->table('login');
		$builder->where('id', $id_usuario); 
		if($builder->delete()) return true;
		else return false;
	}

		public function traer_usuarios_by_id($id)
	{
		
	        $builder = $this->db1->table('login');
			$builder->select('*');	
			$builder->where('id',$id);


		$query = $builder->get();
		if($query != false)	return $query->getRow();
		else return false;
	}

	
}