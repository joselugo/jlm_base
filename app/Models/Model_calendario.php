<?php namespace App\Models;
use CodeIgniter\Model;
#[\AllowDynamicProperties]
class Model_calendario extends Model{

 var $db1;
    function __construct()
    {
        $db1 = \Config\Database::connect('default');
        $this->db1=$db1;
    }

    function get_eventos($sucursal,$where_permiso){

        $builder = $this->db1->table('calendar c');
        $builder->select('c.*,u.nombre');
        $builder->join('usuarios2 u', 'u.id=c.id_cliente');
        // La sucursal elegida en pantalla solo acota; siempre se limita a las del usuario
        // (antes "sucursal=2" mostraba esa sucursal aunque el usuario no la tuviera, y
        // "TODOS" daba error 500 por la columna sucursal ambigua). $where_permiso ya no se usa.
        if($sucursal!="TODOS"){$builder->where("c.sucursal",(int)$sucursal);}
        \App\Libraries\FiltroSucursal::aplicar($builder, "c.sucursal");
        $query=$builder->get();
        if($query != false) return $query->getResult();
        else return false;

    }

      function get_soportes($sucursal,$where_permiso){

        $hoy=date('Y-m-d')." 23:59:59";
        $datepast=date('Y-m-d',strtotime($hoy.' -6 month'));
        $builder = $this->db1->table('soporte s');
        $builder->select('s.*,u.nombre,d.color as color_ticket,t.sucursal');
        $builder->join('usuarios2 u', 'u.id=s.idcliente');
        $builder->join('departamentos d', 'd.id=s.dp');
        $builder->join('tblservicios t', 't.idcliente=s.idcliente');
       if($sucursal!="TODOS"){$builder->where("t.sucursal",$sucursal);}
       else{$builder->where($where_permiso);}
        $builder->where('s.fecha_soporte BETWEEN ' . $this->db1->escape($datepast) . ' AND ' . $this->db1->escape($hoy));
        $builder->groupBy('s.id'); 
                   
        $query=$builder->get();
        if($query != false) return $query->getResult();
        else return false;
    }

      function get_instalaciones($sucursal,$where_permiso){
        $hoy=date('Y-m-d');
        $datepast=date('Y-m-d',strtotime($hoy.' -6 month'));
        $builder = $this->db1->table('instalaciones i');
        $builder->select('i.*,u.nombre,t.sucursal');
        $builder->join('usuarios2 u', 'u.id=i.userid');
        $builder->join('tblservicios t', 't.idcliente=i.userid'); 	
        if($sucursal!="TODOS"){$builder->where("t.sucursal",$sucursal);}
        else{$builder->where($where_permiso);}

        
      //  $builder->where('i.estate','NO INSTALADO');
        $builder->where('i.fecha_ingreso BETWEEN ' . $this->db1->escape($datepast) . ' AND ' . $this->db1->escape($hoy));
        $builder->where('(u.estado= "ACTIVO" OR u.estado="SUSPENDIDO")');
        $builder->groupBy('i.id'); 
                   
        $query=$builder->get();
        if($query != false) return $query->getResult();
        else return false;
    }

      function get_tickets($id){
        $builder = $this->db1->table('ticket');
        $builder->select('*');
        $builder->where('idticket',$id);
        $builder->orderBy('id','ASC'); 
                   
        $query=$builder->get();
        if($query != false) return $query->getResult();
        else return false;
    }

        function get_tipos(){
        $builder = $this->db1->table('calendar_tipos');
        $builder->select('*');
     
                   
        $query=$builder->get();
        if($query != false) return $query->getResult();
        else return false;
    }


    function get_evento_by_id($id){
        $builder = $this->db1->table('calendar c');
        $builder->select('c.title, u.nombre,u.id as id_cliente');
        $builder->join('usuarios2 u',"c.id_cliente = u.id");  
        $builder->where('c.id',$id); 
                   
       $query=$builder->get();
        if($query != false) return $query->getRow();
        else return false;
    }


    function get_evento_by_id_instalaciones($id){
        $builder = $this->db1->newQuery();
        $builder->select('i.*, u.nombre');
        $builder->from('instalaciones i');
        $builder->join('usuarios2 u',"i.userid = u.id");  
        $builder->where('i.id',$id); 
                   
        $query=$builder->get();
        if($query != false) return $query->getRow();
        else return false;
    }

     function get_evento_by_id_soporte($id){
        $builder = $this->db1->newQuery();
        $builder->select('s.*, u.nombre');
        $builder->from('soporte s');
        $builder->join('usuarios2 u',"s.idcliente = u.id");  
        $builder->where('s.id',$id); 
                   
         $query=$builder->get();
        if($query != false) return $query->getRow();
        else return false;
    }


       function get_pagos(){
        $builder = $this->db1->newQuery();
        $builder->select('*');
        $builder->from('fecha_egresos_pagos');        
        $builder->where('estatus',0); 
                   
        $query = $builder->get();

        if($query->getNumRows()>0){
            return $query->getResult();
        }else{
            return false;
        }
    }


    function new_evento($title,$startdate,$enddate,$allDay,$color,$id_cliente,$sucursal){
         $data = array(
        'id_cliente' => $id_cliente,
        'title' => $title,
         'descripcion' => "Ingrese una descripcion",
        'startdate' =>  $startdate,
        'enddate' => $startdate,
        'allDay' => "false",
        'color' => $color,
        'sucursal' => $sucursal
     );


        $builder = $this->db1->table('calendar');
        $builder->insert($data);
        if($this->db1->affectedRows()>0)return true;
        else return false;
    }

    function update_evento($startdate,$enddate,$id,$sucursal){
         $data = array(
        'startdate' =>  $startdate,
        'enddate' => $enddate,
        'sucursal' => $sucursal
     );


        $builder = $this->db1->table('calendar');
        $builder->where('id', $id);
        if($builder->update($data))return true;
        else return false;
    }

      function update_soporte($startdate,$enddate,$id){
         $data = array(
        'fechavisita' =>  $startdate,
        'fechaendvisita' => $enddate
     );

        $builder = $this->db1->table('soporte');
        $builder->where('id', $id);
        if($builder->update($data))return true;
        else return false;



    }

     function update_instalaciones($startdate,$enddate,$id){
         $data = array(
        'fecha_instalacion' =>  $startdate,
        'fecha_instalacion_end' => $enddate
        );
        $builder = $this->db1->table('instalaciones');
        $builder->where('id', $id);
        if($builder->update($data))return true;
        else return false;
    }

      function update_pagos_egresos($startdate,$enddate,$id){
        $builder = $this->db1->newQuery();
         $data = array(
        'start' =>  $startdate,
        'end' => $enddate
        );
       $builder->where('id', $id);
        $query=$builder->update('fecha_egresos_pagos', $data); 
        if($query){
            return true;
        }else{
            return false;
        }
    }

    function update_pago($id,$fecha){
        $builder = $this->db1->newQuery();
         $data = array(
        'start' =>  $fecha,
     );
        $builder->where('id', $id);
        $query=$builder->update('fecha_egresos_pagos', $data); 
        if($query){
            return true;
        }else{
            return false;
        }
    }

    function update_title($title,$descripcion,$id,$id_cliente,$sucursal){
        
        $builder = $this->db1->table('calendar');
        $builder->where('id', $id);
        if($builder->update(['title'=>$title, 'id_cliente' => $id_cliente ,    
        'descripcion' => $descripcion,'sucursal' => $sucursal]))return true;
        else return false;
    }


    function update_descripcion_soporte($descripcion,$id,$id_cliente){
       


        $builder = $this->db1->table('ticket');
        $builder->where('idticket', $id);
        if($builder->update(['contenido'=>$descripcion]))return true;
        else return false;
    }

    function update_descripcion_instalaciones($descripcion,$id,$id_cliente){
        
        $builder = $this->db1->table('instalaciones');
        $builder->where('id', $id);
        if($builder->update(['descripcion_ins'=>$descripcion]))return true;
        else return false;
    }

     function update_descripcion_pagos($descripcion,$id){
        $builder = $this->db1->newQuery();
         $data = array(
        'descripcion' => $descripcion
     );
        
        $builder->where('id', $id);
        $query=$builder->update('fecha_egresos_pagos', $data); 

        if($query){
            return true;
        }else{
            return false;
        }
    }

    
        public function delete_evento($id)
    {   
        $builder = $this->db1->table('calendar');
        $builder->where('id', $id); 
        if($builder->delete()) return true;
        else return false;
    }
    

    
       function get_ultimo_id(){
        $builder = $this->db1->table('calendar');
        $builder->select('MAX(id) as ultimo');
        $query=$builder->get();
        if($query->getRow()->ultimo>0)return $query->getRow();
        else return false;

    }

     function getAll_search($where){
        $builder = $this->db1->newQuery();
       
        $builder->select('u.*,s.nodo,b.avatar_cliente');
        $builder->from('usuarios2 u');
        $builder->join('tblservicios t','t.idcliente = u.id');
        $builder->join('server s','s.id = t.nodo');
        $builder->join('tblavisouser b','b.cliente = u.id');
       
        $builder->where($where, NULL, FALSE );
                      
        $query = $builder->get();

        if($query->getNumRows()>0){
            return $query->getResult();
        }else{
            return false;
        }
    }

          function get_log_calendario($tipo){
        $builder = $this->db1->table('logsistema lo');    
        $builder->select('lo.*,l.nombre as operador');
        $builder->join('login l','l.id = lo.operador');
        $builder->where('lo.tipo',$tipo);                  
        $builder->orderBy('id ASC');
     
        $query=$builder->get();
        if($query != false) return $query->getResult();
        else return false;
    }


        function get_log_instalaciones($tipo){
        $builder = $this->db1->newQuery();
        $builder->select('*');
        $builder->from('logsistema');
        $builder->where('tipo',$tipo);                  
        $builder->order_by('id ASC');
        $query = $builder->get();

        if($query->getNumRows()>0){
            return $query->getResult();
        }else{
            return false;
        }
    }


}