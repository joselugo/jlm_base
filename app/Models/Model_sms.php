<?php namespace App\Models;
use CodeIgniter\Model;
use phpseclib\Common\Functions\Strings;

#[\AllowDynamicProperties]
class Model_sms extends Model{
var $db1;
    public function __construct(){
        $db1 = \Config\Database::connect('default');
		$this->db1=$db1;
    }

  public function get_all_sms($inicio,$final,$permiso_sucursal)
  {

    $builder = $this->db1->table('smsmensajes s');
    $builder->select('s.*,u.nombre,su.sucursal,su.color_su');
    $builder->join("usuarios2 u","u.id=s.user",'left');
    $builder->join("tblservicios t","t.idcliente=u.id",'left');
    $builder->join("sucursal su","su.id=t.sucursal",'left');
    $builder->where($permiso_sucursal);
    $builder->where('s.enviado BETWEEN ' . $this->db1->escape($inicio) . ' AND ' . $this->db1->escape($final));
    $builder->orderBy('s.id', "DESC");

    $query=$builder->get();
    if($query != false) return $query->getResult();
    else return false;
  }
  public function get_all_sms_pendientes($inicio,$final,$permiso_sucursal)
  {

    $builder = $this->db1->table('smsmensajes s');
    $builder->select('s.sms_id,s.id,s.user');
    $builder->join("usuarios2 u","u.id=s.user",'left');
    $builder->join("tblservicios t","t.idcliente=u.id",'left');
    $builder->join("sucursal su","su.id=t.sucursal",'left');
    $builder->where($permiso_sucursal);
    $builder->where('stado', 'en espera');
    $builder->where('s.enviado BETWEEN ' . $this->db1->escape($inicio) . ' AND ' . $this->db1->escape($final));
    $builder->orderBy('s.id', "DESC");

    $query=$builder->get();
    if($query != false) return $query->getResult();
    else return false;
  }

   public function get_all_log_by_clientes($id,$where)
  {

    $builder = $this->db1->table('logsistema l');
    $builder->select('l.*,lo.username');
    $builder->join("usuarios2 u","u.id=l.idcliente");
     $builder->join("login lo","l.operador=lo.id");
    $builder->where($where);
    $builder->where("l.idcliente",$id);
    $builder->orderBy('l.id', "DESC");
    $query=$builder->get();
    if($query != false) return $query->getResult();
    else return false;
  }


    public  function insert_log($id_cliente,$tipo,$log,$username){
       date_default_timezone_set('America/Mexico_City');
       $data=['idcliente'=>$id_cliente,'log' =>$log, 'operador'=>$username,'fechalog'=>date("Y-m-d H:i:s"),'tipo'=>$tipo];
       $builder = $this->db1->table('logsistema');
       $query=$builder->insert($data); 

        if($this->db1->affectedRows()>0){
            return true;
        }else{
            return false;
        }
    }

      public  function insert_log_login($id,$user,$ip,$error){
       date_default_timezone_set('America/Mexico_City');
       $data=['idadmin' =>$id, 'user'=>$user,'fecha'=>date("Y-m-d H:i:s"),'ipadmin'=>$ip,'error'=>$error];
       $builder = $this->db1->table('loglogin');
       $query=$builder->insert($data); 

        if($this->db1->affectedRows()>0){
            return true;
        }else{
            return false;
        }
    }

      function get_ultimo_id(){
        $builder = $this->db1->newQuery();
       $builder->select("MAX(id) as ultimo");
        $builder->from('cotizaciones');          
        $query = $builder->get();

        if($query->getNumRows()>0){
            return $query->getRow();
        }else{
            return false;
        }

    }
	   public function get_nombre_cliente($id)
    {
        $builder = $this->db1->table('usuarios2');
        $builder->select('nombre');
        $builder->where('id', $id);
        $query = $builder->get();
        return $query ? $query->getResult() : false;
    }

    public function find_clientes(String $name_id, String $estado)
    {
        $name_id=explode("_",$name_id);
        $name=$name_id[0];
        $id=$name_id[1];
        if ($name=="SERVER") {
            $tabla="tblservicios t";
            $condicion="t.idcliente=u.id";
            $columna="t.nodo";
        }
        if ($name=="EMISOR") {
            $tabla="tblservicios t";
            $condicion="t.idcliente=u.id";
            $columna="t.emisor";
        }
        $builder = $this->db1->table('usuarios2 u');
        $builder->select('u.id,u.nombre,u.movil');
        $id!='all'?$builder->join($tabla,$condicion,'left'):null;
        $id!='all'?$builder->where($columna,$id):null;
        $estado!='0'?$builder->where('u.estado',$estado):null;
        $builder->orderBy('id ASC');
        $query = $builder->get();
    return $query ? $query->getResult() : false;
    }
    public function get_cliente($term){
        $builder = $this->db1->table('usuarios2 u');
        $builder->select('u.id,u.nombre,u.movil');
        $builder->like('u.nombre',$term);
        $query = $builder->get();
    return $query ? $query->getResult() : false;
    }
    public function get_cliente_num($id){
        $builder = $this->db1->table('usuarios2 u');
        $builder->select('u.id,u.nombre,u.movil');
        $builder->where('u.id',$id);
        $query = $builder->get();
    return $query ? $query->getRow() : false;
    }
    public function get_config(String $id){
        $builder = $this->db1->table('smsconfig');
        $builder->select('*');
        $builder->where('id',$id);
        $query = $builder->get();
    return $query ? $query->getRow() : false;
    }
    public function insert_sms(array $data){
        $builder = $this->db1->table('smsmensajes');
        $builder->insertBatch($data);
        $result = $this->db1->affectedRows();
        return $result ? true : false;
    }
    public function update_sms_pendientes(array $data,string $columna)
    {
        $builder = $this->db1->table('smsmensajes');
        $builder->updateBatch($data,$columna);
        $result = $this->db1->affectedRows();
        return $result ? true : false;
    }
    public function get_sms(int $id)
    {
        $builder = $this->db1->table('smsmensajes');
        $builder->select('*');
        $builder->where('id', $id);
        $query = $builder->get();
        return $query ? $query->getRow() : false;
    }
}