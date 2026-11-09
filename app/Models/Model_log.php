<?php namespace App\Models;
use CodeIgniter\Model;
#[\AllowDynamicProperties]
class Model_log extends Model{
var $db1;
    public function __construct(){
        $db1 = \Config\Database::connect('default');
        date_default_timezone_set('America/Mexico_City');
	$this->db1=$db1;
    }

  public function get_all_log()
  {

    $builder = $this->db1->table('logsistema');
    $builder->select('*');
    $builder->orderBy('id', "DESC");
    $query=$builder->get();
    if($query != false) return $query->getResult();
    else return false;
  }
   public function get_all_log_by_clientes($id,$where)
  {

    $builder = $this->db1->table('logsistema l');
    $builder->select('l.*,lo.username,u.nombre nombrecliente');
    $builder->join("usuarios2 u","u.id=l.idcliente");
    $builder->join("tblservicios t","t.idcliente=l.idcliente");
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
       $data=['idadmin' =>$id, 'fecha'=>date("Y-m-d H:i:s"),'ipadmin'=>$ip,'error'=>$error];
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
    public function get_log_date(String $inico,String $final){
        if (strpos($inico,'/')) {
            $inico=str_replace('/','-',$inico);
            $final=str_replace('/','-',$final);
            $inico=date('Y-m-d',strtotime($inico));
            $final=date('Y-m-d',strtotime($final));
        }
        $builder = $this->db1->table('logsistema l');
        $builder->select('l.fechalog fecha,l.log,o.nombre operador,u.nombre cliente');
        $builder->join('usuarios2 u','l.idcliente=u.id','left');
        $builder->join('login o','l.operador=o.id','left');
        $builder->where('fechalog BETWEEN ' . $this->db1->escape($inico) . ' AND ' . $this->db1->escape($final));
	$builder->where("l.tipolog<>","1");
        $builder->orderBy('fechalog DESC');
        $query = $builder->get();
        return $query ? $query->getResult() : false;
    }
    public function get_log_acceso(String $inico,String $final){
        if (strpos($inico,'/')) {
            $inico=str_replace('/','-',$inico);
            $final=str_replace('/','-',$final);
            $inico=date('Y-m-d',strtotime($inico));
            $final=date('Y-m-d',strtotime($final));
        }
        $builder = $this->db1->table('loglogin l');
        $builder->select('o.nombre operador,l.fecha,l.error detalle,l.ipadmin ip');
        $builder->join('login o','o.id=l.idadmin','left');
        $builder->where('l.fecha BETWEEN ' . $this->db1->escape($inico) . ' AND ' . $this->db1->escape($final));
        $builder->orderBy('l.id DESC');
        $query = $builder->get();
    return $query ? $query->getResult() : false;
    }

}