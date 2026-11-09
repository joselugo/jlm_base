<?php namespace App\Models;
use CodeIgniter\Model;
#[\AllowDynamicProperties]
class Model_formatos extends Model{

  var $db1;
    function __construct()
    {
        $db1 = \Config\Database::connect('default');
        $this->db1=$db1;
    }

    function get_serie_antena($id_cliente){
        $builder = $this->db1->table('usuarios2 u');
        $builder->select('ts.*,u.nombre');
        $builder->join('tblservicios ts', 'u.id = ts.idcliente'); 
        $builder->where('u.id', $id_cliente); 
                   
        $query=$builder->get();
        if($query != false) return $query->getRow();
        else return false;
    }


    function get_serie_modem($id_cliente){
        $builder = $this->db1->table('productos p');
        $builder->select('a.*,p.producto');
        $builder->join('almacen a', 'p.id = a.productoid');
        $builder->where('a.userid',$id_cliente); 
        $where='(p.id=76 OR p.id=68 OR p.id=59 OR p.id=34 OR p.id=31 OR p.id=10 OR p.id=5)';
        $builder->where($where);
      
                             
                   
        $query=$builder->get();
        if($query != false) return $query->getRow();
        else return false;
    }

     function getAll_tickets(){

        $builder = $this->db1->table('soporte s');
        $builder->select('s.*,u.nombre,d.dp as departamento,d.color as colord');
        $builder->join('usuarios2 u', 'u.id = s.idcliente');
        $builder->join('departamentos d', 'd.id = s.dp');
        $builder->where("(s.estado='abierto' OR s.estado='respondido')");              
        $builder->orderBy('s.id DESC');

         $query=$builder->get();
        if($query != false) return $query->getResult();
        else return false;
    }

        function get_cliente($id_cliente){
        $builder = $this->db1->table('usuarios2 u');
        $builder->select('ts.id idservicio,ts.*,u.nombre,u.telefono,u.movil,u.correo,u.cedula,u.direccion_principal,u.codigo,p.plan');
        $builder->join('tblservicios ts', 'u.id = ts.idcliente');
        $builder->join('perfiles p', 'p.id = ts.idperfil');  
        $builder->where('u.id', $id_cliente); 
                   
        $query=$builder->get();
        if($query != false) return $query->getRow();
        else return false;
    
    }

    function get_servicios_cliente($id_cliente){

       $builder = $this->db1->table('tblservicios t');
       $builder->select('t.*,p.*,s.nodo as server,t.id as id_servicio');
       $builder->join('perfiles p','p.id = t.idperfil');
       $builder->join('server s','s.id = t.nodo');
       $builder->where('t.idcliente',$id_cliente);              
       $builder->orderBy('t.id DESC');
       $query=$builder->get();
       if($query != false)  return $query->getResult();
    else return false;
       
    }

      function get_materiales_cliente($id_cliente){

       $builder = $this->db1->table('instalaciones i');
       $builder->select('i.notas,m.*,p.producto');
       $builder->join('materiales m','i.id = m.idinstall');
      $builder->join('almacen a','a.id = m.idalmacen');
      $builder->join('productos p','p.id = productoid');
       $builder->where('i.userid',$id_cliente);
       $builder->where('m.tipopro','producto');               
       $query=$builder->get();
       if($query != false)  return $query->getResult();
    else return false;
       
    }

      function get_accesorios_cliente($id_cliente){

       $builder = $this->db1->table('instalaciones i');
       $builder->select('i.notas,m.*,p.producto,p.medida');
       $builder->join('materiales m','i.id = m.idinstall');
      $builder->join('almacen a','a.id = m.idalmacen');
      $builder->join('productos p','p.id = productoid');
       $builder->where('i.userid',$id_cliente);
       $builder->where('m.tipopro','accesorio');               
       $query=$builder->get();
       if($query != false)  return $query->getResult();
    else return false;
       
    }

       function get_cliente_install($id_cliente){

       $builder = $this->db1->table('instalaciones i');
       $builder->select('i.*,l.nombre'); 
       $builder->join('login l','l.id = i.idtecnico','left');
       $builder->where('i.userid',$id_cliente);              
       $query=$builder->get();
       if($query != false)  return $query->getRow();
    else return false;
       
    }

        function get_facturas_by_cliente($id_cliente){
        $builder = $this->db1->table('facturas');
        $builder->selectSum('total');
        $builder->where('idcliente', $id_cliente);
        $builder->where('estado', 'No pagado');  
                   
        $query=$builder->get();
        if($query != false) return $query->getRow();
        else return false;
    }

      function get_facturas_by_cliente2($id_cliente){
         $builder = $this->db1->table('facturas');
        $builder->select('*');
        $builder->where('idcliente', $id_cliente);
        $builder->where('estado', 'No pagado');  
                   
        $query=$builder->get();
        if($query != false) return $query->getResult();
        else return false;
    }

       function get_ultimo_id(){
        $builder = $this->db1->newQuery();
        $builder->select("id_lista_facturas, MAX(id_lista_facturas) ultimo");
        $builder->from('lista_facturas');          
        $query = $builder->get();

        if($query->getNumRows()>0){
           $row=$query->getRow();
           $ultimo=$row->ultimo;
           return $ultimo;
        }else{
            return false;
        }
    	}

	public function get_formato_factura(){
   	 $builder = $this->db1->table('formatos');
   	 $builder->select('*');
   	 $builder->where('id', 8);
   	 $query = $builder->get();
   	 return $query ? $query->getResult() : false;
 	 }
  	
	public function get_formato_recibo(){
   	 $builder = $this->db1->table('formatos');
   	 $builder->select('*');
   	 $builder->where('tipoaviso', 'recibo');
   	 $query = $builder->get();
   	 return $query ? $query->getResult() : false;
  	}

  	public function get_formato_recibopos(){
   	 $builder = $this->db1->table('formatos');
   	 $builder->select('*');
   	 $builder->where('tipoaviso', 'recibopos');
   	 $query = $builder->get();
   	 return $query ? $query->getResult() : false;
  	}

  	public function save_formato($id, $data){
   	 $builder = $this->db1->table('formatos');
   	 $builder->where('id', $id);
   	 $builder->update($data);
   	 $result = $this->db1->affectedRows();
   	 return $result >= 0 ? true : false;
  	}

  	public function id_prueba_factura(){
    	 $builder = $this->db1->table('facturas');
    	 $builder->selectMax('id');
    	 $query = $builder->get();
    	 if ($query) {
    	   return $query->getResult()[0]->id;
    	 } else {
      	   return false;
    	 }
  	}

	 public function id_prueba_factura_operacion(){
   	 $builder = $this->db1->table('operaciones');
    	$builder->selectMax('nfactura');
    	$query = $builder->get();
    	if ($query) {
      	return $query->getResult()[0]->nfactura;
    	} else {
      	return false;
    	}
  	}
	 public function get_formato_edit($idformato)
  {
    $builder = $this->db1->table('formatos');
    $builder->select('*');
    $builder->where('id', $idformato);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }	  public function get_primer_sucursal($where){
  	  $builder = $this->db1->table('sucursal');
  	  $builder->select('id');
 	   $builder->where($where);
  	  $builder->limit(1);
 	   $builder->orderBy('id', 'ASC');
 	   $query = $builder->get();
 	 return $query ? $query->getResult() : false;
 	 }
	 public function get_formatos(){
    $builder = $this->db1->table('tipoformato');
    $builder->select('id,descripcion');
    $builder->orderBy('id ASC');
    $query = $builder->get();
  return $query ? $query->getResult() : false;
  }
	 public function get_documentos($idtipoformato,$idsucursal){
    $builder = $this->db1->table('formatos f');
    $builder->select('f.id,f.tipoaviso');
    $builder->where('idtipoformato',$idtipoformato);
    $builder->where('idsucursal',$idsucursal);
    $builder->orderBy('id ASC');
    $query = $builder->get();
  return $query ? $query->getResult() : false;
  }

public function get_formato_install($idsucursal)
  {
    $builder = $this->db1->table('formatos');
    $builder->select('*');
    $builder->where('tipoaviso', 'instalacion');
    $builder->where('idsucursal', $idsucursal);
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }

}