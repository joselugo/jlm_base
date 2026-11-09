<?php namespace App\Models;
use CodeIgniter\Model;
use phpseclib\Common\Functions\Strings;

#[\AllowDynamicProperties]
class Model_oxxo extends Model{
var $db1;
    public function __construct(){
        $db1 = \Config\Database::connect('default');
		$this->db1=$db1;
    }


    public function update_reference($idfactura,$id_order,$reference,$barcode){
        $builder = $this->db1->table('facturas');
         $builder->where('id', $idfactura);
        $builder->update(["oxxo_id"=>$id_order,"oxxo_referencia"=>$reference,"barcode_cobro_digital"=>$barcode]);
        $result = $this->db1->affectedRows();
        return $result ? true : false;
    }
    public function update_factura($idfactura,$array){
        $builder = $this->db1->table('facturas');
         $builder->where('id', $idfactura);
        $builder->update($array);
        $result = $this->db1->affectedRows();
        return $result ? true : false;
    }
    public function get_factura_idorder($id_order)
    {
        $builder = $this->db1->table('facturas');
        $builder->select('id,idcliente,tipo,total');
        $builder->where('oxxo_id', $id_order);
        $query = $builder->get();
        return $query ? $query->getRow() : false;
    }
     public function get_data_servicios(String $idcliente)
  {
    $builder = $this->db1->table('tblservicios ts');
    $builder->select('ts.id,se.ip,se.usuario,se.contrasena,se.port_api puerto');
    $builder->join('server se', 'se.id=ts.nodo', 'left');
    $builder->join('usuarios2 us', 'us.id=ts.idcliente', 'left');
    $builder->where('ts.idcliente', $idcliente);
    $builder->where('us.estado', 'SUSPENDIDO');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
     public function insert_operacion($data)
  {
    $builder = $this->db1->table('operaciones');
    $builder->insert($data);
    $result = $this->db1->affectedRows();
    return $result ? true : false;
  }
   public function insert_nuevo_saldo($data)
  {
    $builder = $this->db1->table('saldos');
    $builder->insert($data);
    $result = $this->db1->affectedRows();
    return $result ? true : false;
  }
    public function update_webhook($event){
        $builder = $this->db1->table('tblconfiguration');
         $builder->where('id', 123);
        $builder->update(["value"=>$event]);
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