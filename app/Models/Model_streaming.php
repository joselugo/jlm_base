<?php

namespace App\Models;

use CodeIgniter\Model;

#[\AllowDynamicProperties]
class Model_streaming extends Model
{
    var $db1;

    public function __construct()
    {
        $db1 = \Config\Database::connect('default');
        $this->db1 = $db1;
    }
    //tables consulta
    public function get_streaming()
    {
        $builder = $this->db1->table('streaming_tipo');
        $builder->select('*');
        //$builder->where('estatus', 'ACTIVO');
        $builder->orderBy('id DESC');
        $query = $builder->get();
        return $query ? $query->getResult() : false;
    }

    public function get_cuentas()
    {
        $builder = $this->db1->table('streaming s');
        $builder->select('s.*, (TIMESTAMPDIFF(DAY, CURDATE(), s.fecha_vencimiento)) as lastdays, st.nombre, st.tipo, st.costo');
        $builder->join('streaming_tipo st', 'st.id = s.id_streaming', 'LEFT');
        //$builder->where('s.estado', 1);
        $builder->orderBy('s.id', 'DESC');
        $query = $builder->get();
        return $query ? $query->getResult() : false;
    }

    /* public function get_cuentas()
    {
        $query = $this->db1->query("select s.*, datediff(s.fecha_vencimiento, s.fecha_alta_servicio) as remaining, st.nombre, st.tipo, st.costo from streaming s left join streaming_tipo st on st.id = s.id_streaming ORDER BY s.id DESC");
        return $query ? $query->getResult() : false;
    } */

    public function get_clientes()
    {
        $builder = $this->db1->table('cliente_streaming c');
        $builder->select('c.id, u.nombre, s.correo, s.contrasena, c.pin, st.costo , c.fecha_vinculado, tb.diapago, u.estado, st.id id_tipo, st.tipo, st.nombre streaming, s.id id_streaming, c.id_cliente');
        $builder->join('usuarios2 u', 'u.id = c.id_cliente');
        $builder->join('streaming s', 's.id = c.id_cuentastr');
        $builder->join('streaming_tipo st', 'st.id = s.id_streaming');
        $builder->join('tblavisouser tb', 'tb.cliente = c.id_cliente');
        \App\Libraries\FiltroSucursal::aplicar($builder, 'u.sucursal'); // solo clientes de mis sucursales
        $builder->orderBy('c.id DESC');
        $query = $builder->get();
        return $query ? $query->getResult() : false;
    }

    public function get_balance_json()
    {
        $builder = $this->db1->table('record_streaming rs');
        $builder->select('rs.id, s.correo, rs.activation_date, st.nombre, st.tipo, st.costo');
        $builder->join('streaming s', 'rs.id_streamin = s.id');
        $builder->join('streaming_tipo st', 's.id_streaming = st.id');
        $builder->orderBy('rs.id DESC');
        $query = $builder->get();
        return $query ? $query->getResult() : false;
    }

    public function get_balance_netflix()
    {
        $builder = $this->db1->table('logsistema l');
        $builder->select('l.log, l.fechalog, u.username');
        $builder->join('login u', 'l.operador=u.id');
        $builder->where('l.tipo', 'balance_netflix');
        $builder->orderBy('l.fechalog', 'DESC');
        $query = $builder->get();
        return $query ? $query->getResult() : false;
    }

    public function get_balance_youtube()
    {
        $builder = $this->db1->table('logsistema l');
        $builder->select('l.log, l.fechalog, u.username');
        $builder->join('login u', 'l.operador=u.id');
        $builder->where('l.tipo', 'balance_youtube');
        $builder->orderBy('l.fechalog', 'DESC');
        $query = $builder->get();
        return $query ? $query->getResult() : false;
    }


    //end tables consultas
    public function save_streaming($data)
    {
        $builder = $this->db1->table('streaming_tipo');
        $builder->insert($data);
        if ($this->db1->affectedRows() > 0) return true;
        else return false;
    }

    public function get_streaming_byid($id)
    {
        $builder = $this->db1->table('streaming_tipo');
        $builder->select('*');
        $builder->where('id', $id);
        $query = $builder->get();
        return $query ? $query->getResult() : false;
    }
    public function get_cuenta_byid($id)
    {
        $builder = $this->db1->table('streaming s');
        $builder->select('s.* , st.nombre, st.tipo, st.costo');
        $builder->join('streaming_tipo st', 'st.id = s.id_streaming', 'LEFT');
        $builder->where('s.id', $id);
        $query = $builder->get();
        return $query ? $query->getResult() : false;
    }

    public function update_streaming($id, $data)
    {
        $builder = $this->db1->table('streaming_tipo');
        $builder->where('id', $id);
        $builder->update($data);
        $result = $this->db1->affectedRows();
        return $result > 0 ? true : false;
    }

    public function update_saldo_netflix($id, $data)
    {
        $builder = $this->db1->table('presupuesto_streaming');
        $builder->where('id', $id);
        $builder->update($data);
        $result = $this->db1->affectedRows();
        return $result > 0 ? true : false;
    }

    public function update_streaming_cuenta($id, $data)
    {
        $builder = $this->db1->table('streaming');
        $builder->where('id', $id);
        $builder->update($data);
        $result = $this->db1->affectedRows();
        return $result > 0 ? true : false;
    }

    public function save_cuenta($data)
    {
        $builder = $this->db1->table('streaming');
        $builder->insert($data);
        if ($this->db1->affectedRows() > 0) return true;
        else return false;
    }

    public function save_cuenta_cliente($data)
    {
        $builder = $this->db1->table('cliente_streaming');
        $builder->insert($data);
        if ($this->db1->affectedRows() > 0) return true;
        else return false;
    }

    public function get_straming_tipo_byid($id)
    {
        $builder = $this->db1->table('streaming_tipo');
        $builder->select('*');
        $builder->where('id', $id);
        $query = $builder->get();
        if ($query != false)    return $query->getRow();
        else return false;
    }

    public function get_straming_byid($id)
    {
        $builder = $this->db1->table('streaming');
        $builder->select('*');
        $builder->where('id', $id);
        $query = $builder->get();
        if ($query != false)    return $query->getRow();
        else return false;
    }

    public function get_cliente_streaming_byid($id)
    {
        $builder = $this->db1->table('cliente_streaming c');
        $builder->select('c.*, u.nombre, s.correo, st.id id_tipo, s.id id_streaming');
        $builder->join('usuarios2 u', 'u.id=c.id_cliente', 'LEFT');
        $builder->join('streaming s', 's.id=c.id_cuentastr', 'LEFT');
        $builder->join('streaming_tipo st', 'st.id=s.id_streaming', 'LEFT');
        $builder->where('c.id', $id);
        $query = $builder->get();
        return $query ? $query->getResult() : false;
    }
    public function get_clientestreaming_byid($id)
    {
        $builder = $this->db1->table('cliente_streaming');
        $builder->select('*');
        $builder->where('id', $id);
        $query = $builder->get();
        return $query ? $query->getRow() : false;
    }

    public function update_streaming_clientestr($id, $data)
    {
        $builder = $this->db1->table('cliente_streaming');
        $builder->where('id', $id);
        $builder->update($data);
        $result = $this->db1->affectedRows();
        return $result > 0 ? true : false;
    }

    public function delete_perfil($id)
    {
        $builder = $this->db1->table('cliente_streaming');
        $builder->where('id', $id);
        return $builder->delete() ? true : false;
    }

    public function get_balance($id)
    {
        $builder = $this->db1->table('presupuesto_streaming');
        $builder->select('*');
        $builder->where('id', $id);
        $query = $builder->get();
        return $query ? $query->getRow() : false;
    }
    public function get_streaming_tipo($id)
    {
        $builder = $this->db1->table('streaming_tipo');
        $builder->select('*');
        $builder->where('id', $id);
        $query = $builder->get();
        return $query ? $query->getRow() : false;
    }

    public function last_id()
    {
        $builder = $this->db1->table('streaming');
        $builder->select('MAX(id) as last');
        $query = $builder->get();
        return $query->getRow()->last > 0 ? $query->getRow() : false;
    }

    public function insert_record($data)
    {
        $builder = $this->db1->table('record_streaming');
        $builder->insert($data);
        if ($this->db1->affectedRows() > 0) return true;
        else return false;
    }
}
