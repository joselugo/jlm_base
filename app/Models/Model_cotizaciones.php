<?php namespace App\Models;
use CodeIgniter\Model;
#[\AllowDynamicProperties]
class Model_cotizaciones extends Model{
     var $db1;
    var $db2;
    public function __construct(){
       $db1 = \Config\Database::connect('default');
        $this->db1=$db1;

        $db2 = \Config\Database::connect('version5');
        $this->db2=$db2;


    }

    function getAll($where_permiso_sucursal,$estado){
       $builder = $this->db1->table('cotizaciones c');
       $builder->select('c.*,c.id as id_cotizacion');
     
       //$builder->join('usuarios2 u','c.idcliente = u.id');
        $builder->where($where_permiso_sucursal);
        //$builder->groupBy('t.idcliente');                  
       $builder->orderBy('c.id ASC');
        
        $query=$builder->get();
        if($query != false) return $query->getResult();
        else return false;
    }

     function getAll_cliente_cot($id_cliente){
       $builder = $this->db1->table('usuarios2'); 
       $builder->select('*');
       $builder->where("id",$id_cliente);
        //$builder->groupBy('t.idcliente');                  
       
         $query=$builder->get();
        if($query != false) return $query->getRow();
        else return false;
    }

         function check_permiso_cot($where,$id){
       $builder = $this->db1->table('cotizaciones');    
       $builder->select('*');
       $builder->where('id',$id);
       $builder->where($where);                  
       
       if($builder->countAllResults()>0) return true;
        else return false;

    }

    function get_all_productos(){
       $builder = $this->db1->table('productos_tools'); 
       $builder->select('*');
       //$builder->where('tipo','otro');                  
       $builder->orderBy('id ASC');
   

         $query=$builder->get();
        if($query != false) return $query->getResult();
        else return false;
    }

      function get_all_planes(){
         $builder = $this->db1->table('perfiles'); 
       $builder->select('*');
      // $builder->where('tipo','queues');
        //$builder->orWhere('tipo','pcq');                    
        $builder->orderBy('id ASC');
         $query=$builder->get();
        if($query != false) return $query->getResult();
        else return false;
    }

    function get_all_productos_by_id($id_producto){
       $builder = $this->db1->table('productos_tools');
       $builder->select('*');
       //$builder->where('tipo','otro'); 
       $builder->where('id',$id_producto);                 
       $query=$builder->get();
        if($query != false) return $query->getResult();
        else return false;
    }

     function get_all_planes_by_id($id_plan){
       $builder = $this->db1->table('perfiles');
       $builder->select('*');
       //$builder->where('tipo','otro'); 
       $builder->where('id',$id_plan);                 
        $query=$builder->get();
        if($query != false) return $query->getResult();
        else return false;
    }

    function get_cot_items($idcotizacion){
       $builder = $this->db1->table('cotizaciones_items');
       $builder->select('*');
       $builder->where('idcotizacion',$idcotizacion);                  
       $builder->orderBy('id ASC');
        $query = $builder-> get();

        if($query != false) return $query->getResult();
        else return false;
    }


      function save_new_producto($data){
       $builder = $this->db1->table('productos_tools');
       $builder->insert($data);
       if($this->db1->affectedRows()>0)return true;
       else return false;

    }

        function save_new_cotizacion($data){

       $builder = $this->db1->table('cotizaciones');
       $builder->insert($data);
       if($this->db1->affectedRows()>0)return true;
       else return false;

    }

        function update_cotizacion($arrayName,$id_cot){
       $builder = $this->db1->table('cotizaciones');
       $builder->where('id', $id_cot);
       $query=$builder->update($arrayName); 

       if($this->db1->affectedRows()>0)return true;
       else return false;

    }

      function save_items($carrito,$idcotizacion){

          $builder = $this->db1->table('cotizaciones_items');
          for ($i=0; $i <count($carrito) ; $i++) {
            $data_base = array(
               'idcotizacion' => $idcotizacion,
               'idproducto' => $carrito[$i]['idproducto'],
               'producto' => $carrito[$i]['producto'],
               'descripcion' => $carrito[$i]['descripcion'],
               'cantidad' => $carrito[$i]['cantidad'],
               'unidad' => $carrito[$i]['unidad'],
               'subtotal' =>  $carrito[$i]['subtotal']
               );

              $query=$builder->insert($data_base); 
              
           }
           return true;  

       

    }

      function save_items_factura($arrayName){

          $builder = $this->db1->table('facturaitems');
         $query=$builder->insert($arrayName); 
          if($this->db1->affectedRows()>0)return true;
          else return false; 

       

    }

       function update_correo_cliente($id_cliente,$correo){
       $builder = $this->db1->table('usuarios2');
       $builder->where('id', $id_cliente);
       $query=$builder->update(array('correo' => $correo )); 

       if($this->db1->affectedRows()>0)return true;
       else return false;

    }

      function delete_cot($id){
         $builder = $this->db1->table('cotizaciones');
        $query=$builder->delete(array('id' => $id)); 
        if($this->db1->affectedRows()>0)return true;
       else return false;

    }



    public function get_cotizacion_by_id($id_cot){
       $builder = $this->db1->table('cotizaciones');
       $builder->select('*');
       $builder->where('id',$id_cot);               
        $query = $builder->get();

       if($this->db1->affectedRows()>0) return $query->getResult();
       else return false;
        
    }

    function delete_cot_items($id){
        $builder = $this->db1->table('cotizaciones_items');
        $query=$builder->delete(array('idcotizacion' => $id)); 

         if($query) return true;
        else return false;
    }
    function get_cotizaciones_data($id_cotizacion,$where){
       $builder = $this->db1->table('cotizaciones');
       $builder->select('*');
       $builder->where('id',$id_cotizacion);
        $builder->where($where);               
        $query = $builder-> get();

         if($this->db1->affectedRows()>0) return $query->getResult();
       else return false;
         
    }

       function getItemsCot($id_cotizacion){
        $builder = $this->db1->table('cotizaciones_items');
       $builder->select('*');
       $builder->where('idcotizacion',$id_cotizacion);               
        $query = $builder-> get();

         if($this->db1->affectedRows()>0) return $query->getResult();
       else return false;
         
    }

      function get_ultimo_id(){
      $builder = $this->db1->table('cotizaciones');  
      $builder->select("MAX(id) as ultimo");
      $query=$builder->get();
        if($query != false) return $query->getRow();
        else return false;

    }


     function getAll_search($where_permiso_nodo,$where){
        $builder = $this->db1->newQuery();
       
       $builder->select('c.*, se.nodo as server,s.avatar_cliente');
       $builder->from('usuarios2 c');
       $builder->join('tblavisouser s','c.id = s.cliente');
       $builder->join('tblservicios t','c.id = t.idcliente');
       $builder->join('server se','se.id = t.nodo');
       $builder->where($where, NULL, FALSE );
       $builder->where($where_permiso_nodo, NULL, FALSE );
       $builder->group_by('t.idcliente');                   
        $query = $builder->get();

        if($query->getNumRows()>0){
            return $query->getResult();
        }else{
            return false;
        }
    }
 

     function getAll_historial($id_cliente){
        $builder = $this->db1->newQuery();
       $builder->select('*');
       $builder->from('logcliente');
       $builder->where('id_cliente',$id_cliente);               
       $builder->order_by('id DESC');
        $query = $builder->get();

        if($query->getNumRows()>0){
            return $query->getResult();
        }else{
            return false;
        }
    }


     function getAll_email($id_cliente){
        $builder = $this->db1->newQuery();
       $builder->select('*');
       $builder->from('mail');
       $builder->where('userid',$id_cliente);               
       $builder->order_by('id DESC');
        $query = $builder->get();

        if($query->getNumRows()>0){
            return $query->getResult();
        }else{
            return false;
        }
    }

      function getAll_sms($id_cliente){
        $builder = $this->db1->newQuery();
       $builder->select('*');
       $builder->from('smsmensajes');
       $builder->where('user',$id_cliente);               
       $builder->order_by('id DESC');
        $query = $builder->get();

        if($query->getNumRows()>0){
            return $query->getResult();
        }else{
            return false;
        }
    }
     
     
       function get_user_data($id_cliente,$where_permiso_nodo){
        $builder = $this->db1->newQuery();
       
        $builder->select('u.*,t.*,p.*,s.nodo as server,u.id as id_user');
       $builder->from('usuarios2 u');
       $builder->join('tblservicios t','u.id = t.idcliente');
       $builder->join('perfiles p','p.id = t.idperfil');
       $builder->join('server s','s.id = t.nodo');
       $builder->where('u.id',$id_cliente);
      
        if($where_permiso_nodo!=null){
          $builder->where($where_permiso_nodo, NULL, FALSE );   
        }
                    
        $query = $builder->get();

        if($query->getNumRows()>0){
            return $query->getRow();
        }else{
            return false;
        }

    }

       function get_user_configuration($id_cliente){
        $builder = $this->db1->newQuery();
       $builder->select('*');
       $builder->from('tblavisouser');
       $builder->where('cliente',$id_cliente);            
        $query = $builder->get();

        if($query->getNumRows()>0){
            return $query->getRow();
        }else{
            return false;
        }

    }

      function get_column_to_show(){
        $builder = $this->db1->newQuery();
       $builder->select('*');
       $builder->from('tabla_show');
       $builder->where('id_tabla_controller',1);               
       $builder->order_by('position', 'ASC');
        $query = $builder->get();

         if($query->getNumRows()>0){
            return $query->getResult();
        }else{
            return false;
        }

    }

     function get_sucursal(){
        $builder = $this->db1->newQuery();
       $builder->select('*');
       $builder->from('sucursal');                 
       $builder->order_by('nombre','ASC');
        $query = $builder->get();

         if($query->getNumRows()>0){
            return $query->getResult();
        }else{
            return false;
        }

    }
       function get_sucursal_by_id_tbl($id_sucursal){
        $builder = $this->db1->newQuery();
       $builder->select('*');
       $builder->from('tblsucursal');  
       $builder->where('id_sucursal',$id_sucursal);                     
        $query = $builder->get();

         if($query->getNumRows()>0){
            return $query->getRow();
        }else{
            return false;
        }

    }

      function get_mail_by_id($id_mail){
        $builder = $this->db1->newQuery();
       $builder->select('*');
       $builder->from('mail');  
       $builder->where('id',$id_mail);                     
        $query = $builder->get();

         if($query->getNumRows()>0){
            return $query->getRow();
        }else{
            return false;
        }

    }

     function update_data_user($id_cliente,$array){
        $builder = $this->db1->newQuery();

      $builder->where('id', $id_cliente);
       $query=$this->db1->table('clientes')->update( $array); 

        if($query){
            return true;
        }else{
            return false;
        }

    }

      function new_tblavisouser($id_cliente,$impuesto,$tipoaviso){

      $arrayName = array('cliente' =>$id_cliente ,'impuesto' =>$impuesto,'tipoaviso' =>$tipoaviso, );
       $query=$this->db1->table('tblavisouser')->insert( $arrayName); 

        if($query){
            return true;
        }else{
            return false;
        }

    }

    function new_nota($id_cliente,$asunto,$mensaje,$file,$operador,$tipo){

      $arrayName = array('idcliente' =>$id_cliente ,'asunto' =>$asunto,'mensaje' =>$mensaje,'fecha' =>date("Y-m-d H:i:s"),'adjuntos' =>$file,'idoperador' =>$operador,'tipo' =>$tipo);
       $query=$this->db1->table('notas')->insert( $arrayName); 

        if($query){
            return true;
        }else{
            return false;
        }

    }

     function new_mail($id,$asunto,$cuerpo,$adjunto,$remitente,$destino,$nombre_cliente){

      $arrayName = array('userid' =>$id ,'asunto' =>$asunto,'cuerpo' =>$cuerpo, 'adjunto' =>$adjunto, 'facturaid' =>'00000000', 
                         'enviado' =>date("Y-m-d H:i:s"),'estado' =>2,'tipo' =>1 ,'mailremitente' =>$remitente, 'maildestino' =>$destino, 'nombrecliente' =>$nombre_cliente);
       $query=$this->db1->table('mail')->insert( $arrayName); 

        if($query){
            return true;
        }else{
            return false;
        }

    }

       function new_sms($id_cliente,$movil,$mensaje){

      $arrayName = array('user' =>$id_cliente ,'enviado' =>date("Y-m-d H:i:s"),'ndestino' =>$movil,'estado' =>"enviando",'mensaje' =>$mensaje);
       $query=$this->db1->table('smsmensajes')->insert( $arrayName); 

        if($query){
            return true;
        }else{
            return false;
        }

    }


     function update_config_client($id_cliente,$array){
        $builder = $this->db1->newQuery();

      $builder->where('cliente', $id_cliente);
       $query=$this->db1->table('tblavisouser')->update( $array); 

        if($query){
            return true;
        }else{
            return false;
        }

    }

     function save_new_cliente($array){

       $query=$this->db1->table('clientes')->insert( $array); 
        if($query){
            return true;
        }else{
           
            return false;
        }

    }

     function insert_factura($array){
        $builder = $this->db1->table('facturas');
        $query=$builder->insert($array); 
       if($this->db1->affectedRows()>0)return true;
       else return false;

    }

     function update_numero_legal($numero_legal){
      $builder = $this->db1->table('tblconfiguration');
      $builder->where('setting', "nfacturalegal");
       $query=$builder->update(array('value' => $numero_legal)); 

       if($this->db1->affectedRows()>0)return true;
       else return false;

    }


     function update_cotizacion_parametros($id_cot,$nfactura){
      $builder = $this->db1->table('cotizaciones');
      $builder->where('id', $id_cot);
       $query=$builder->update(array('estado' =>1,'nfactura' => $nfactura)); 

        
       if($this->db1->affectedRows()>0)return true;
       else return false;


    }

     function get_ultima_factura(){
       $builder = $this->db1->table('facturas');
      $builder->select("MAX(id) as ultimo");         
        $query = $builder-> get();

        if($this->db1->affectedRows()>0)return $query->getRow();
        else return false;

    }

     function checkid($id_cliente){
        $builder = $this->db1->newQuery();
       $builder->select('id');
       $builder->from('clientes');
       $builder->where('id',$id_cliente);                  
        $query = $builder->get();

         if($query->getNumRows()>0){
            return true;
        }else{
            return false;
        }

    }



      function get_id_by_parametros($nombres,$paterno,$materno,$rfc,$domicilio_completo,$movil,$contrasenia_portal){
        $builder = $this->db1->newQuery();
       $builder->select('id');
       $builder->from('clientes');
       $builder->where('nombres',$nombres);
       $builder->where('paterno',$paterno); 
       $builder->where('materno',$materno); 
       $builder->where('rfc',$rfc); 
       $builder->where('domicilio_completo',$domicilio_completo); 
       $builder->where('movil',$movil); 
       $builder->where('contrasenia_portal',$contrasenia_portal);                   
        $query = $builder->get();

         if($query->getNumRows()>0){
            return $query->getRow();
        }else{
            return false;
        }
    }

     function check_id_by_parametros($nombres,$paterno,$materno,$rfc,$domicilio_completo,$movil,$contrasenia_portal){
        $builder = $this->db1->newQuery();
       $builder->select('id');
       $builder->from('clientes');
       $builder->where('nombres',$nombres);
       $builder->where('paterno',$paterno); 
       $builder->where('materno',$materno); 
       $builder->where('rfc',$rfc); 
       $builder->where('domicilio_completo',$domicilio_completo); 
       $builder->where('movil',$movil); 
       $builder->where('contrasenia_portal',$contrasenia_portal);                   
        $query = $builder->get();

         if($query->getNumRows()>0){
            return true;
        }else{
            return false;
        }
    }

    function delete_cliente($id){

        $query=$this->db1->table('clientes')->delete( array('id' => $id)); 
        if($query){return true;}
        else{
            $query=$this->db1->error()['message'];
            return false;
        }
    }

   function delete_nota($id){

        $query=$this->db1->table('notas')->delete( array('id' => $id)); 
        if($query){return true;}
        else{
            $query=$this->db1->error()['message'];
            return false;
        }
    }

    function retirar_activar($id,$estatus){
        $builder = $this->db1->newQuery();
        
       $builder->where('id', $id);
        $query=$this->db1->table('clientes')->update( array('id' => $id,'estatus'=>$estatus));
        if($query){return true;}
        else{
            $query=$this->db1->error()['message'];
            return false;
        }
    }

      function get_ultimo_cliente(){
        $builder = $this->db1->newQuery();
      $builder->select("MAX(id) as ultimo");
       $builder->from('clientes');          
        $query = $builder->get();

        if($query->getNumRows()>0){
            return $query->getRow();
        }else{
            return false;
        }

    }

         function get_ultima_nota(){
        $builder = $this->db1->newQuery();
      $builder->select("MAX(id) as ultimo");
       $builder->from('notas');          
        $query = $builder->get();

        if($query->getNumRows()>0){
            return $query->getRow();
        }else{
            return false;
        }

    }

      function get_ultimo_correo(){
        $builder = $this->db1->newQuery();
      $builder->select("MAX(id) as ultimo");
       $builder->from('mail');          
        $query = $builder->get();

        if($query->getNumRows()>0){
            return $query->getRow();
        }else{
            return false;
        }

    }

      function get_ultimo_sms(){
        $builder = $this->db1->newQuery();
      $builder->select("MAX(id) as ultimo");
       $builder->from('smsmensajes');          
        $query = $builder->get();

        if($query->getNumRows()>0){
            return $query->getRow();
        }else{
            return false;
        }

    }


 function getAll_notas_by_cliente($id_cliente){
        $builder = $this->db1->newQuery();
       $builder->select('n.*,u.nombre');
       $builder->from('notas n');
        $builder->join('usuario u','u.id_usuario = n.idoperador'); 
         $builder->where('n.idcliente',$id_cliente); 
          $builder->where('n.tipo',0);                    
       $builder->order_by('n.id DESC');
        $query = $builder->get();

        if($query->getNumRows()>0){
            return $query->getResult();
        }else{
            return false;
        }
}

 function getAll_docs_by_cliente($id_cliente){
        $builder = $this->db1->newQuery();
       $builder->select('*');
       $builder->from('notas');
         $builder->where('idcliente',$id_cliente);
          $builder->where('tipo',1);                   
       $builder->order_by('id DESC');
        $query = $builder->get();

        if($query->getNumRows()>0){
            return $query->getResult();
        }else{
            return false;
        }
}
    

}