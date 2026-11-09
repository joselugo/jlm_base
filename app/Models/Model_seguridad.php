<?php  namespace App\Models;
use CodeIgniter\Model;
#[\AllowDynamicProperties]
class Model_seguridad extends Model{
     var  $db1;
	function __construct()
	{
		
		//$this->db1 = $this->load->database('default',TRUE);
		$db1 = \Config\Database::connect('default');
		$this->db1=$db1;
	}

	// $password llega en texto plano: se verifica en PHP (MD5 heredado o
	// password_hash) y, si era MD5, se vuelve a guardar con password_hash()

	// Evita guardar un hash de 60 caracteres en la columna de 32 (MD5) si aún no
	// se ejecutó la migración AmpliarPasswordLogin: quedaría truncado y el
	// usuario no podría volver a entrar

		public function verificar_permiso($id_usuario, $id_permiso)
	{
		$builder = $this->db1->table('permiso cp');
		$builder->select('cup.tipo_acceso AS permiso_usuario,cup.id_permiso');
		
		$builder->join('login_permiso cup', 'cup.id_permiso = cp.id_permiso AND cup.id_usuario = '.$id_usuario, 'left');
		//$this->db1->join('usuario_rol cur', 'cur.id_usuario = '.$id_usuario, 'left');
		//$this->db1->join('rol_permiso crp', 'crp.id_permiso = cp.id_permiso AND crp.id_rol = cur.id_rol', 'left');
		$builder->where('cup.id_permiso',$id_permiso);
		$builder->where('cup.id_usuario',$id_usuario);
		$query=$builder->get();
		if($query != false)	return $query->getResult();
		else return false;
	}

	public function traer_menu_sistema($id_usuario=0, $padre=0)
	{
		$builder = $this->db1->table('permiso cp'); 
		
		$builder->select('cp.id_permiso, cp.modulo, cp.url, cp.clase, IFNULL(cup.tipo_acceso, -1) AS permiso_usuario, IFNULL(cup.tipo_acceso, 0) AS permiso_rol', FALSE);
	//	$this->db1->from('permiso cp');
		$builder->join('login_permiso cup', 'cup.id_permiso = cp.id_permiso AND cup.id_usuario = '.$id_usuario, 'left');
		//$this->db1->join('usuario_rol cur', 'cur.id_usuario = '.$id_usuario, 'left');
		//$this->db1->join('rol_permiso crp', 'crp.id_permiso = cp.id_permiso AND crp.id_rol = cur.id_rol', 'left');
		$builder->where('cp.tipo',1);
		$builder->where('cp.padre',$padre);
		$builder->where('cp.activo',1);
		$builder->orderBy('cp.orden, cp.id_permiso');
		$query=$builder->get();

		if($query != false)	return $query->getResult();
		else return false;
	}


	function get_permisos($id_usuario){
		$builder = $this->db1->table('login'); 
        $builder->select('*');
        $builder->where('id',$id_usuario);               
        $query = $builder-> get();

        if($query != false)	return $query->getRow();
		else return false;

    }

	
}