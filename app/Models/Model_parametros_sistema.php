<?php  namespace App\Models;
use CodeIgniter\Model;
#[\AllowDynamicProperties]
class Model_parametros_sistema extends Model {
    var $db1;
   
    public function __construct(){
       $db1 = \Config\Database::connect('default');
       $this->db1=$db1;
    }
	
	function get_iva()
	{
        $builder = $this->db1->newQuery();
		$builder->select('*');
		$builder->from('tblconfiguration');
		$builder->where('setting','iva');				
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
	}

	function get_nombre_empresa()
	{
        $builder = $this->db1->newQuery();
		$builder->select('*');
		$builder->from('tblconfiguration');
		$builder->where('setting','nombre_empresa');				
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
	}

	function get_rfc_empresa()
	{
        $builder = $this->db1->newQuery();
		$builder->select('*');
		$builder->from('tblconfiguration');
		$builder->where('setting','rfc_empresa');				
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
	}

	function get_direccion_empresa()
	{
        $builder = $this->db1->newQuery();
		$builder->select('*');
		$builder->from('tblconfiguration');
		$builder->where('setting','direccion_empresa');				
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
	}

	function get_telefono_empresa()
	{
        $builder = $this->db1->newQuery();
		$builder->select('*');
		$builder->from('tblconfiguration');
		$builder->where('setting','telefono_empresa');				
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
	}

	function get_simbolo_moneda()
	{
        $builder = $this->db1->newQuery();
		$builder->select('*');
		$builder->from('tblconfiguration');
		$builder->where('setting','simbolo_moneda');				
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
	}

	function get_formato_moneda()
	{
        $builder = $this->db1->newQuery();
		$builder->select('*');
		$builder->from('tblconfiguration');
		$builder->where('setting','formato_moneda');				
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
	}

    function get_num_legal()
    {
        $builder=$this->db1->table('tblconfiguration');
         $builder->select('value');
        $builder->where('setting','nfacturalegal');               
        
          $query=$builder->get();
        if($query != false) return $query->getRow();
        else return false;
    }

        function get_opcion_num_legal()
    {
        $builder = $this->db1->newQuery();
        $builder->select('*');
        $builder->from('tblconfiguration');
        $builder->where('setting','opcion_num_legal');               
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
    }

	function get_tamanio_factura()
	{
        $builder = $this->db1->newQuery();
		$builder->select('*');
		$builder->from('tblconfiguration');
		$builder->where('setting','tamanio_factura');				
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
	}


    function get_tamanio_recibo()
    {
        $builder = $this->db1->newQuery();
        $builder->select('*');
        $builder->from('tblconfiguration');
        $builder->where('setting','tamanio_recibo');              
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
    }

	function get_orientacion_factura()
	{
        $builder = $this->db1->newQuery();
		$builder->select('*');
		$builder->from('tblconfiguration');
		$builder->where('setting','orientacion_factura');				
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
	}

        function get_orientacion_recibo()
    {
        $builder = $this->db1->newQuery();
        $builder->select('*');
        $builder->from('tblconfiguration');
        $builder->where('setting','orientacion_recibo');              
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
    }

		function get_moneda_letras()
	{
        $builder = $this->db1->newQuery();
		$builder->select('*');
		$builder->from('tblconfiguration');
		$builder->where('setting','moneda_letras');				
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
	}

		function get_mostrar_franja()
	{
        $builder = $this->db1->newQuery();
		$builder->select('*');
		$builder->from('tblconfiguration');
		$builder->where('setting','mostrar_franja');				
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
	}

		function get_mostrar_fecha_pdf()
	{
        $builder = $this->db1->newQuery();
		$builder->select('*');
		$builder->from('tblconfiguration');
		$builder->where('setting','mostrar_fecha_pdf');				
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
	}

        function get_send_nueva_factura()
    {
        $builder = $this->db1->newQuery();
        $builder->select('*');
        $builder->from('tblconfiguration');
        $builder->where('setting','send_nueva_factura');                
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
    }

        function get_send_pagado_factura()
    {
        $builder = $this->db1->newQuery();
        $builder->select('*');
        $builder->from('tblconfiguration');
        $builder->where('setting','send_pagado_factura');                
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
    }

       function get_zona_horaria()
    {
        $builder = $this->db1->newQuery();
        $builder->select('*');
        $builder->from('tblconfiguration');
        $builder->where('setting','zona_horaria');                
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
    }

    function get_correo_host()
    {
        $builder = $this->db1->newQuery();
        $builder->select('*');
        $builder->from('tblconfiguration');
        $builder->where('setting','correo_host');                
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
    }


    function get_correo_port()
    {
        $builder = $this->db1->newQuery();
        $builder->select('*');
        $builder->from('tblconfiguration');
        $builder->where('setting','correo_port');                
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
    }


    function get_correo_secure()
    {
        $builder = $this->db1->newQuery();
        $builder->select('*');
        $builder->from('tblconfiguration');
        $builder->where('setting','correo_secure');                
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
    }

    function get_correo_user()
    {
        $builder = $this->db1->newQuery();
        $builder->select('*');
        $builder->from('tblconfiguration');
        $builder->where('setting','correo_user');                
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
    }

    function get_correo_pass()
    {
        $builder = $this->db1->newQuery();
        $builder->select('*');
        $builder->from('tblconfiguration');
        $builder->where('setting','correo_pass');                
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
    }


    function get_correo_firma()
    {
        $builder = $this->db1->newQuery();
        $builder->select('*');
        $builder->from('tblconfiguration');
        $builder->where('setting','correo_firma');                
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
    }


    function get_url_logo()
    {
        $builder = $this->db1->newQuery();
        $builder->select('*');
        $builder->from('tblconfiguration');
        $builder->where('setting','url_logo');                
        $query=$builder->get();
         if($query->getNumRows()>0){
             $row=$query->getRow();
             return $row;    
        }else{
            return false;
        }
    }		
	
}
?>