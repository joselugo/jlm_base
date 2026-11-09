<?php namespace App\Models;
use CodeIgniter\Model;
#[\AllowDynamicProperties]
class Model_reportes extends Model {
 var $db1;
	function __construct()
	{
		$db1 = \Config\Database::connect('default');
		$this->db1=$db1;
	}


     function get_gastos_tipo(){
     	$builder = $this->db1->table('gastos_tipo');
        $builder->select('*');
        $builder->orderBy('tipo');  

         $query=$builder->get();
		if($query != false)	return $query->getResult();
		else return false;             
        
    }

     function get_all_gastos_por_tipo($fecha_inicio,$fecha_fin,$id_tipo_gasto){
     	$builder = $this->db1->table('gastos');
        $builder->select('*');
        $builder->where("tipo_gasto",$id_tipo_gasto); 
        $builder->where('fecha BETWEEN ' . $this->db1->escape($fecha_inicio) . ' AND ' . $this->db1->escape($fecha_fin));   
        
                      
        // Solo datos de las sucursales del usuario
        \App\Libraries\FiltroSucursal::aplicar($builder, 'idsucursal');
        $query = $builder-> get();

        if($query != false)	return $query->getResult();
		else return false;     
    }


     function get_gastos_tipo_restricted(){
     	$builder = $this->db1->table('gastos_tipo');
        $builder->select('*');
        $builder->where('onview',0); 
        $builder->orderBy('tipo');               
        $query = $builder -> get();

		if($query != false)	return $query->getResult();
		else return false;

    }

     function get_facturado($fecha_inicio,$fecha_fin){
        $builder = $this->db1->table('operaciones');	
        $builder->select('*');
     
        $builder->where('fecha_pago BETWEEN ' . $this->db1->escape($fecha_inicio) . ' AND ' . $this->db1->escape($fecha_fin));  
                      
        // Solo datos de las sucursales del usuario
        if (!\App\Libraries\FiltroSucursal::todas()) { $builder->where('idcliente IN (SELECT id FROM usuarios2 WHERE ' . \App\Libraries\FiltroSucursal::sql('sucursal') . ')', null, false); }
        $query=$builder->get();
		if($query != false)	return $query->getResult();
		else return false;  

    }

       function get_ingresos($fecha_inicio,$fecha_fin){
       	 $builder = $this->db1->table('gastos');
        $builder->select('*');
         $builder->where('fecha BETWEEN ' . $this->db1->escape($fecha_inicio) . ' AND ' . $this->db1->escape($fecha_fin));  
         $builder->where("tipo","ingreso"); 


       // Solo datos de las sucursales del usuario
       \App\Libraries\FiltroSucursal::aplicar($builder, 'idsucursal');
       $query=$builder->get();
		if($query != false)	return $query->getResult();
		else return false;  
    }

    function get_total_tipo($id_tipo_gasto,$fecha_inicio,$fecha_fin){
        $builder = $this->db1->table('gastos');	
        $builder->select('*');
        $builder->where("tipo_gasto",$id_tipo_gasto); 
        $builder->where('fecha BETWEEN ' . $this->db1->escape($fecha_inicio) . ' AND ' . $this->db1->escape($fecha_fin));   
        
                      
        // Solo datos de las sucursales del usuario
        \App\Libraries\FiltroSucursal::aplicar($builder, 'idsucursal');
        $query=$builder->get();
		if($query != false)	return $query->getResult();
		else return false;  
    }


      function get_all_login(){
      	$builder = $this->db1->table('login');
        $builder->select('*'); 
        $builder->where('estado',"Habilitado"); 
        $builder->orderBy('nombre');               
        $query = $builder -> get();

        $query=$builder->get();
		if($query != false)	return $query->getResult();
		else return false;

    }



		public function get_all_reportes($estado,$permiso)
	{

		$builder = $this->db1->table('reportes re');
		$builder->select('re.*,so.id as id_socio,so.nombre,so.apellidos');
		$builder->join('socios so', 'so.id = re.idsocio');
		if($estado!="%%"){$builder->where("estado",$estado);}
		$builder->where($permiso);
        $builder->orderBy('re.id', "ASC");
        $query=$builder->get();
		if($query != false)	return $query->getResult();
		else return false;
	}	

		public function get_documentos_by_id($id,$where)
	{

		$builder = $this->db1->table('documentos do');
		$builder->select('do.*');
		$builder->join('socios so', 'so.id = do.idsocio');
		$builder->where('do.id',$id);
		$builder->where($where);
        $query=$builder->get();
		if($query != false)	return $query->getRow();
		else return false;
	}


		public function get_reportes_by_id($id,$where)
	{

		$builder = $this->db1->table('reportes re');
		$builder->select('re.*, so.domicilio,so.nombre,so.apellidos,so.movil,si.nombre as nombre_sindicato');
		$builder->join('socios so', 'so.id = re.idsocio');
		$builder->join('sindicato si', 'si.id = so.sindicato');
		$builder->where('re.id',$id);
		$builder->where($where);
        $query=$builder->get();
		if($query != false)	return $query->getRow();
		else return false;
	}


		public function get_tickets_by_reporte($id)
	{

		$builder = $this->db1->table('ticket');
		$builder->select('*');
		$builder->where('idreporte',$id);
		$builder->orderBy('id','DESC');
        $query=$builder->get();
		if($query != false)	return $query->getResult();
		else return false;
	}


		public function aprobar_doc($id)
	{

		$builder = $this->db1->table('documentos');
		$builder->where('id', $id);
		if($builder->update(['estado'=>'APROBADO']))return true;
		else return false;
	}

		public function cerrar_re($id)
	{

		$builder = $this->db1->table('reportes');
		$builder->where('id', $id);
		if($builder->update(['estado'=>'CERRADO']))return true;
		else return false;
	}

	    public function new_reporte($data)
	{

		$builder = $this->db1->table('reportes');
        $builder->insert($data);
		if($this->db1->affectedRows()>0)return true;
		else return false;
	}

		    public function new_ticket($data)
	{

		$builder = $this->db1->table('ticket');
        $builder->insert($data);
		if($this->db1->affectedRows()>0)return true;
		else return false;
	}

			public function get_ultimo_reporte()
	{
        
		$builder = $this->db1->table('reportes');
        $builder->select('MAX(id) as ultimo');
        $query=$builder->get();
		if($query->getRow()->ultimo>0)return $query->getRow();
		else return false;
	}

		public function delete_re($id)
	{   
		$builder = $this->db1->table('reportes');
		$builder->where('id', $id); 
		if($builder->delete()) return true;
		else return false;
	}
		

	
}