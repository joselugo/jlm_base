<?php namespace App\Models;
use CodeIgniter\Model;
#[\AllowDynamicProperties]
class Model_facturacion extends Model{
    var $db1;


    public function __construct(){
       $db1 = \Config\Database::connect('default');
        $this->db1=$db1;

    }

    function get_lista_facturas($mes,$anio){
        $builder = $this->db1->newQuery();
        $builder->select('l.*,lm.*,u.nombre');
        $builder->from('lista_facturas l'); 
        $builder->join('lista_factura_por_mes lm', 'l.id_lista_facturas = lm.id_lista_facturas'); 
        $builder->join('usuarios2 u', 'u.id = l.id_cliente'); 
        $builder->where('lm.mes', $mes); 
        $builder->where('lm.anio', $anio);            
        $builder->where('l.activo', 0); 
                   
        $query = $builder->get();

        if($query->getNumRows()>0){
            return $query->getResult();
        }else{
            return false;
        }
    }

     function get_lista_facturas_short(){
        $builder = $this->db1->newQuery();
        $builder->select('*');
        $builder->from('lista_facturas');   
        $builder->where('activo', 0); 
                   
        $query = $builder->get();

        if($query->getNumRows()>0){
            return $query->getResult();
        }else{
            return false;
        }
    }

    function get_list_factura_by_id($id){
        $builder = $this->db1->newQuery();

        $builder->select('*');
        $builder->from('lista_factura_por_mes'); 
        $builder->where('id', $id); 
        $query = $builder->get();
        if($query->getNumRows()>0){
            return $query->getRow();
        }else{
            return false;
        }
    }

     function get_list_factura_by_id_lista_facturas($id){
        $builder = $this->db1->newQuery();
        
        $builder->select('*');
        $builder->from('lista_factura_por_mes'); 
        $builder->where('id_lista_facturas', $id); 
        $query = $builder->get();
        if($query->getNumRows()>0){
            return $query->getRow();
        }else{
            return false;
        }
    }

     function get_datos_fiscales($id){
        $builder = $this->db1->newQuery();
        $builder->select('*');
        $builder->from('lista_facturas'); 
        $builder->where('id_lista_facturas', $id); 
        $query = $builder->get();

        if($query->getNumRows()>0){
            return $query->getRow();
        }else{
            return false;
        }
    }

        function consulta_existe($mes,$anio,$id){
        $builder = $this->db1->newQuery();
        $builder->select('*');
        $builder->from('lista_factura_por_mes'); 
        $builder->where('id_lista_facturas', $id);
        $builder->where('mes', $mes);
        $builder->where('anio', $anio);   
        $query = $builder->get();

        if($query->getNumRows()>0){
            return true;
        }else{
            return false;
        }
    }


   
    function new_ev($title,$startdate,$enddate,$allDay,$color){
         $data = array(
        'id_cliente' => 328 ,
        'title' => $title,
         'descripcion' => "Ingrese una descripcion",
        'startdate' =>  $startdate,
        'enddate' => $startdate,
        'allDay' => "false",
        'color' => $color
     );

        $query=$this->db1->table('calendar')->insert( $data); 
        if($query){
            return true;
        }else{
            return false;
        }
    }

    function update_list_by_id($id,$array){
        $builder = $this->db1->newQuery();
     
        $builder->where('id', $id);
        $query=$builder->from('lista_factura_por_mes', true)->update( $array); 
        if($query){
            return true;
        }else{
            return false;
        }
    }

     function update_fiscales($id,$array){
        $builder = $this->db1->newQuery();
     
        $builder->where('id_lista_facturas', $id);
        $query=$builder->from('lista_facturas', true)->update( $array); 
        if($query){
            return true;
        }else{
            return false;
        }
    }

        function new_fiscales($array){
     
       $query=$this->db1->table('lista_facturas')->insert( $array); 
        if($query){
            return true;
        }else{
            return false;
        }
    }

     function new_lista_factura_por_mes($array){
     
       $query=$this->db1->table('lista_factura_por_mes')->insert( $array); 
        if($query){
            return true;
        }else{
            return false;
        }
    }

    function delete_cliente($id_lista_facturas){
     
      $query=$this->db1->table('lista_facturas')->delete( array('id_lista_facturas' => $id_lista_facturas));
      $query=$this->db1->table('lista_factura_por_mes')->delete( array('id_lista_facturas' => $id_lista_facturas));  
        if($query){
            return true;
        }else{
            return false;
        }
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

       function get_ultimo_id_gasto(){
        $builder = $this->db1->table('gastos');
        $builder->select("id, MAX(id) ultimo");
        $query = $builder-> get();

        if($query){
           $row=$query->getRow();
           $ultimo=$row->ultimo;
           return $ultimo;
        }else{
            return false;
        }

    }

      function get_all_ingresos(){
        $builder = $this->db1->table('gastos g');
        $builder->select('g.*,u.nombre');
        $builder->join('login u', 'u.id = g.idadmin'); 
        $builder->where('g.tipo', "ingreso"); 
                   
     

        // Solo datos de las sucursales del usuario
        \App\Libraries\FiltroSucursal::aplicar($builder, 'g.idsucursal');
        $query=$builder->get();
        if($query != false) return $query->getResult();
        else return false;
    }

        function get_all_egresos(){
       $builder = $this->db1->table('gastos g');     
       $builder->select('g.*,u.nombre,gt.tipo');
       $builder->join('gastos_tipo gt','gt.id = g.tipo_gasto');  
       $builder->join('login u', 'u.id = g.idadmin'); 
       $builder->where('g.tipo', "egreso");
       $builder->orderBy('g.id', "DESC");  
                   
       // Solo datos de las sucursales del usuario
       \App\Libraries\FiltroSucursal::aplicar($builder, 'g.idsucursal');
       $query=$builder->get();
        if($query != false) return $query->getResult();
        else return false;
    }

        function get_all_egresos_restricted(){
        $builder = $this->db1->table('gastos g');      
       $builder->select('g.*,u.nombre,gt.tipo');
       $builder->join('gastos_tipo gt','gt.id = g.tipo_gasto');  
       $builder->join('login u', 'u.id = g.idadmin'); 
       $builder->where('g.tipo', "egreso");
       $builder->where('gt.onview', 0);
       $builder->orderBy('g.id', "desc");  
                   
        // Solo datos de las sucursales del usuario
        \App\Libraries\FiltroSucursal::aplicar($builder, 'g.idsucursal');
        $query=$builder->get();
        if($query != false) return $query->getResult();
        else return false;
    }

        function get_name_user($id){
        $builder = $this->db1->table('login');    
        $builder->select('nombre');
        $builder->where('id', $id);

        $query=$builder->get();
        if($query != false) return $query->getRow();
        else return false;
    }


    function new_ingreso_egreso($array){

      $builder = $this->db1->table('gastos');
      $query=$builder->insert($array); 
      if($this->db1->affectedRows()>0)return true;
      else return false;
    }


     function update_egreso($id,$data){
        $builder = $this->db1->table('gastos');
        $builder->where('id', $id);
        if($builder->update($data))return true;
        else return false;
    }


     function update_ingreso($id,$data){
        $builder = $this->db1->table('gastos');
        $builder->where('id', $id);
        if($builder->update($data))return true;
        else return false;
    }

        function get_egreso_by_id($id){
        $builder = $this->db1->table('gastos');
        $builder->select('*');
        $builder->where('id', $id);                
        $query=$builder->get();
        if($query != false) return $query->getRow();
        else return false;
    }

     function get_ingreso_by_id($id){

        $builder = $this->db1->table('gastos');
        $builder->select('*');
        $builder->where('id', $id);                
        $query=$builder->get();
        if($query != false) return $query->getRow();
        else return false;
    }

    function delete_gasto($id){
      $builder = $this->db1->table('gastos');
      $builder->where('id', $id); 
      if($builder->delete()) return true;
        else return false;
      
    }

}