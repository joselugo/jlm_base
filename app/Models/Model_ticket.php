<?php

namespace App\Models;

use CodeIgniter\Model;

#[\AllowDynamicProperties]
class Model_ticket extends Model
{
    var $db;

    public function __construct()
    {
        $db = \Config\Database::connect('default');
        $this->db = $db;
    }

    public function get_tickets_unanswered($dep, $id, $permiso)
    {
        $builder = $this->db->table('soporte s');
        $builder->select("s.id, d.dp as departamento, u.nombre as remitente, s.asunto, l.nombre as tecnico, s.fecha_soporte as fecha, z.zona as ubicacion, s.operador, (CASE WHEN TIMESTAMPDIFF(YEAR,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN  CONCAT('Hace ',TIMESTAMPDIFF(YEAR,s.lastdate,CURRENT_TIMESTAMP),' año',CONCAT(CASE WHEN TIMESTAMPDIFF(YEAR,s.lastdate,CURRENT_TIMESTAMP)>1 THEN 's' ELSE '' END)) ELSE CASE WHEN TIMESTAMPDIFF(MONTH,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(MONTH,s.lastdate,CURRENT_TIMESTAMP),' mes',(CASE WHEN TIMESTAMPDIFF(MONTH,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 'es' ELSE '' END)) ELSE CASE WHEN TIMESTAMPDIFF(WEEK,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(WEEK,s.lastdate,CURRENT_TIMESTAMP),' semana',CASE WHEN TIMESTAMPDIFF(WEEK,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE CASE WHEN TIMESTAMPDIFF(DAY,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(DAY,s.lastdate,CURRENT_TIMESTAMP),' dia',CASE WHEN TIMESTAMPDIFF(DAY,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE CASE WHEN TIMESTAMPDIFF(HOUR,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(HOUR,s.lastdate,CURRENT_TIMESTAMP),' hora',CASE WHEN TIMESTAMPDIFF(HOUR,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE CASE WHEN TIMESTAMPDIFF(MINUTE,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(MINUTE,s.lastdate,CURRENT_TIMESTAMP),' minuto',CASE WHEN TIMESTAMPDIFF(MINUTE,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE CASE WHEN TIMESTAMPDIFF(SECOND,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(SECOND,s.lastdate,CURRENT_TIMESTAMP),' segundo',CASE WHEN TIMESTAMPDIFF(SECOND,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE '' END END END END END END END) as respuesta, d.color, s.idcliente, su.sucursal,su.color_su");
        $builder->join('usuarios2 u', 's.idcliente=u.id', 'left');
        $builder->join('tblservicios t', 'u.id = t.idcliente');
        $builder->join('departamentos d', 's.dp=d.id', 'left');
        $builder->join('login l', 's.idsoporte=l.id', 'left');
        $builder->join('tblavisouser tb', 'tb.cliente=s.idcliente', 'left');
        $builder->join('zonas z', 'z.id=tb.zona', 'left');
        $builder->join('sucursal su', 'su.id=t.sucursal', 'left');
        $builder->groupBy('s.id');
        $builder->where($permiso);
        $builder->where('s.estado', 'abierto');
        $builder->orderBy('s.id', 'DESC');
        if ($dep != "TODOS") {
            $builder->where('d.id', "$dep");
        }
        if ($id != "TODOS") {
            $builder->where('l.id', "$id");
        }
        $query = $builder->get();
        return $query ? $query->getResult() : false;
    }

    public function get_tickets_answered($dep, $id, $permiso)
    {
        $builder = $this->db->table('soporte s');
        $builder->select("s.id, d.dp as departamento, u.nombre as remitente, s.asunto, l.nombre as tecnico, s.fecha_soporte as fecha, z.zona as ubicacion, s.operador, (CASE WHEN TIMESTAMPDIFF(YEAR,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN  CONCAT('Hace ',TIMESTAMPDIFF(YEAR,s.lastdate,CURRENT_TIMESTAMP),' año',CONCAT(CASE WHEN TIMESTAMPDIFF(YEAR,s.lastdate,CURRENT_TIMESTAMP)>1 THEN 's' ELSE '' END)) ELSE CASE WHEN TIMESTAMPDIFF(MONTH,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(MONTH,s.lastdate,CURRENT_TIMESTAMP),' mes',(CASE WHEN TIMESTAMPDIFF(MONTH,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 'es' ELSE '' END)) ELSE CASE WHEN TIMESTAMPDIFF(WEEK,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(WEEK,s.lastdate,CURRENT_TIMESTAMP),' semana',CASE WHEN TIMESTAMPDIFF(WEEK,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE CASE WHEN TIMESTAMPDIFF(DAY,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(DAY,s.lastdate,CURRENT_TIMESTAMP),' dia',CASE WHEN TIMESTAMPDIFF(DAY,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE CASE WHEN TIMESTAMPDIFF(HOUR,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(HOUR,s.lastdate,CURRENT_TIMESTAMP),' hora',CASE WHEN TIMESTAMPDIFF(HOUR,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE CASE WHEN TIMESTAMPDIFF(MINUTE,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(MINUTE,s.lastdate,CURRENT_TIMESTAMP),' minuto',CASE WHEN TIMESTAMPDIFF(MINUTE,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE CASE WHEN TIMESTAMPDIFF(SECOND,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(SECOND,s.lastdate,CURRENT_TIMESTAMP),' segundo',CASE WHEN TIMESTAMPDIFF(SECOND,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE '' END END END END END END END) as respuesta, d.color, s.idcliente,su.sucursal,su.color_su");
        $builder->join('usuarios2 u', 's.idcliente=u.id', 'left');
        $builder->join('departamentos d', 's.dp=d.id', 'left');
        $builder->join('login l', 's.idsoporte=l.id', 'left');
        $builder->join('tblavisouser tb', 'tb.cliente=s.idcliente', 'left');
        $builder->join('zonas z', 'z.id=tb.zona', 'left');
        $builder->join('tblservicios t', 'u.id = t.idcliente');
        $builder->join('sucursal su', 'su.id=t.sucursal', 'left');
        $builder->orderBy('s.id', 'DESC');
        $builder->groupBy('s.id');
        $builder->where('s.estado', 'respondido');
        $builder->where($permiso);
        if ($dep != "TODOS") {
            $builder->where('d.id', "$dep");
        }
        if ($id != "TODOS") {
            $builder->where('l.id', "$id");
        }

        $query = $builder->get();
        return $query ? $query->getResult() : false;
    }

    public function get_tickets_closed($dep, $id, $permiso)
    {
       
        $builder = $this->db->table('soporte s');
        $builder->select("s.id, d.dp as departamento, u.nombre as remitente, s.asunto, l.nombre as tecnico, s.fecha_soporte as fecha, z.zona as ubicacion, s.operador, (CASE WHEN TIMESTAMPDIFF(YEAR,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN  CONCAT('Hace ',TIMESTAMPDIFF(YEAR,s.lastdate,CURRENT_TIMESTAMP),' año',CONCAT(CASE WHEN TIMESTAMPDIFF(YEAR,s.lastdate,CURRENT_TIMESTAMP)>1 THEN 's' ELSE '' END)) ELSE CASE WHEN TIMESTAMPDIFF(MONTH,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(MONTH,s.lastdate,CURRENT_TIMESTAMP),' mes',(CASE WHEN TIMESTAMPDIFF(MONTH,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 'es' ELSE '' END)) ELSE CASE WHEN TIMESTAMPDIFF(WEEK,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(WEEK,s.lastdate,CURRENT_TIMESTAMP),' semana',CASE WHEN TIMESTAMPDIFF(WEEK,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE CASE WHEN TIMESTAMPDIFF(DAY,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(DAY,s.lastdate,CURRENT_TIMESTAMP),' dia',CASE WHEN TIMESTAMPDIFF(DAY,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE CASE WHEN TIMESTAMPDIFF(HOUR,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(HOUR,s.lastdate,CURRENT_TIMESTAMP),' hora',CASE WHEN TIMESTAMPDIFF(HOUR,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE CASE WHEN TIMESTAMPDIFF(MINUTE,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(MINUTE,s.lastdate,CURRENT_TIMESTAMP),' minuto',CASE WHEN TIMESTAMPDIFF(MINUTE,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE CASE WHEN TIMESTAMPDIFF(SECOND,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(SECOND,s.lastdate,CURRENT_TIMESTAMP),' segundo',CASE WHEN TIMESTAMPDIFF(SECOND,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE '' END END END END END END END) as respuesta, d.color, s.idcliente, s.estado,su.sucursal,su.color_su");
        $builder->join('usuarios2 u', 's.idcliente=u.id', 'LEFT');
        $builder->join('departamentos d', 's.dp=d.id', 'LEFT');
        $builder->join('login l', 's.idsoporte=l.id', 'LEFT');
        $builder->join('tblavisouser tb', 's.idcliente=tb.cliente', 'LEFT');
        $builder->join('zonas z', 'z.id=tb.zona', 'LEFT');
        $builder->join('tblservicios t', 'u.id=t.idcliente', 'LEFT');
        $builder->join('sucursal su', 'su.id=t.sucursal', 'left');
        $builder->where('s.estado', 'cerrado');
        $builder->where($permiso);
        $builder->groupBy('s.id');
        $builder->orderBy('s.id', 'DESC');

        if ($dep != "TODOS") {
            $builder->where('d.id', "$dep");
        }
        if ($id != "TODOS") {
            $builder->where('l.id', "$id");
        }
        $query = $builder->get();
        return $query ? $query->getResult() : false;
    }

    public function get_tickets_record($id, $dep, $id_)
    {
        $builder = $this->db->table('soporte s');
        $builder->select("s.id, d.dp as departamento, s.asunto, l.nombre as tecnico, s.fecha_soporte as fecha, s.estado, s.operador, (CASE WHEN TIMESTAMPDIFF(YEAR,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN  CONCAT('Hace ',TIMESTAMPDIFF(YEAR,s.lastdate,CURRENT_TIMESTAMP),' año',CONCAT(CASE WHEN TIMESTAMPDIFF(YEAR,s.lastdate,CURRENT_TIMESTAMP)>1 THEN 's' ELSE '' END)) ELSE CASE WHEN TIMESTAMPDIFF(MONTH,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(MONTH,s.lastdate,CURRENT_TIMESTAMP),' mes',(CASE WHEN TIMESTAMPDIFF(MONTH,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 'es' ELSE '' END)) ELSE CASE WHEN TIMESTAMPDIFF(WEEK,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(WEEK,s.lastdate,CURRENT_TIMESTAMP),' semana',CASE WHEN TIMESTAMPDIFF(WEEK,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE CASE WHEN TIMESTAMPDIFF(DAY,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(DAY,s.lastdate,CURRENT_TIMESTAMP),' dia',CASE WHEN TIMESTAMPDIFF(DAY,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE CASE WHEN TIMESTAMPDIFF(HOUR,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(HOUR,s.lastdate,CURRENT_TIMESTAMP),' hora',CASE WHEN TIMESTAMPDIFF(HOUR,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE CASE WHEN TIMESTAMPDIFF(MINUTE,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(MINUTE,s.lastdate,CURRENT_TIMESTAMP),' minuto',CASE WHEN TIMESTAMPDIFF(MINUTE,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE CASE WHEN TIMESTAMPDIFF(SECOND,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(SECOND,s.lastdate,CURRENT_TIMESTAMP),' segundo',CASE WHEN TIMESTAMPDIFF(SECOND,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE '' END END END END END END END) as respuesta, d.color, s.idcliente");
        $builder->join('usuarios2 u', 's.idcliente=u.id', 'left');
        $builder->join('departamentos d', 's.dp=d.id', 'left');
        $builder->join('login l', 's.idsoporte=l.id', 'left');
        $builder->join('tblavisouser tb', 'tb.cliente=s.idcliente', 'left');
        $builder->join('zonas z', 'z.id=tb.zona', 'left');
        $builder->orderBy('s.id', 'DESC');
        $builder->where('s.idcliente', $id);
        if ($dep != "TODOS") {
            $builder->where('d.id', "$dep");
        }
        if ($id_ != "TODOS") {
            $builder->where('l.id', "$id_");
        }
        $query = $builder->get();
        return $query ? $query->getResult() : false;
    }

    public function get_id_client($id)
    {
        $builder = $this->db->table('soporte s');
        $builder->select('s.idcliente');
        $builder->join('usuarios2 u', 'u.id = s.idcliente');
        $builder->where('s.id', $id);
        $query = $builder->get();
        return $query ? $query->getRow() : false;
    }

    public function delete_soporte($id)
    {
        $builder = $this->db->table('soporte');
        $builder->where('id', $id);
        return $builder->delete() ? true : false;
    }
    public function delete_ticket($id)
    {
        $builder = $this->db->table('ticket');
        $builder->where('idticket', $id);
        return $builder->delete() ? true : false;
    }

    public function new_soporte($data)
    {
        $builder = $this->db->table('soporte');
        $builder->insert($data);
        $query = $this->db->affectedRows();
        return ($query > 0) ? true : false;
    }

    public function new_ticket($data)
    {
        $builder = $this->db->table('ticket');
        $builder->insert($data);
        $query = $this->db->affectedRows();
        return ($query > 0) ? true : false;
    }

    public function last_id()
    {
        $builder = $this->db->table('soporte');
        $builder->select('MAX(id) as last');
        $query = $builder->get();
        if ($query->getRow()->last > 0) {
            return $query->getRow();
        } else {
            return false;
        }
    }

    public function get_soporte_id($id)
    {
        $builder = $this->db->table('soporte s');
        $builder->select('s.*, u.telefono,u.movil, l.nombre as tecnico, z.zona as ubicacion, tbl.costo, tbl.instalado, tbl.ip, tbl.direccion, p.plan, ts.nodo,d.dp as departamento');
        $builder->join('usuarios2 u', 's.idcliente=u.id', 'left');
        $builder->join('login l', 's.idsoporte=l.id', 'left');
        $builder->join('departamentos d', 's.dp=d.id', 'left');
        $builder->join('tblavisouser tb', 'tb.cliente=s.idcliente', 'left');
        $builder->join('zonas z', 'z.id=tb.zona', 'left');
        $builder->join('tblservicios tbl', 'tbl.idcliente=s.idcliente', 'left');
        $builder->join('perfiles p', 'p.id=tbl.idperfil', 'left');
        $builder->join('server ts', 'ts.id=tbl.nodo', 'left');
        $builder->where('s.id', $id);
        $query = $builder->get();
        return $query ? $query->getResult() : false;
    }

    public function get_ticket_id($id)
    {
        $builder = $this->db->table('ticket t');
        $builder->select('t.*, u.id as idclient, u.nombre, l.nombre as tecnico, s.asunto');
        $builder->join('soporte s', 's.id=t.idticket', 'left');
        $builder->join('login l', 'l.id=t.idtecnico', 'left');
        $builder->join('usuarios2 u', 's.idcliente=u.id', 'left');
        $builder->where('t.idticket', $id);
        $builder->orderBy('t.id', 'DESC');
        $query = $builder->get();
        return $query ? $query->getResult() : false;
    }

    public function get_all_tickets_client($id)
    {
        $builder = $this->db->table('soporte s');
        $builder->select("s.id, s.asunto, s.fecha_soporte as fecha, l.nombre as tecnico, s.estado, s.operador, (CASE WHEN TIMESTAMPDIFF(YEAR,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN  CONCAT('Hace ',TIMESTAMPDIFF(YEAR,s.lastdate,CURRENT_TIMESTAMP),' año',CONCAT(CASE WHEN TIMESTAMPDIFF(YEAR,s.lastdate,CURRENT_TIMESTAMP)>1 THEN 's' ELSE '' END)) ELSE CASE WHEN TIMESTAMPDIFF(MONTH,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(MONTH,s.lastdate,CURRENT_TIMESTAMP),' mes',(CASE WHEN TIMESTAMPDIFF(MONTH,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 'es' ELSE '' END)) ELSE CASE WHEN TIMESTAMPDIFF(WEEK,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(WEEK,s.lastdate,CURRENT_TIMESTAMP),' semana',CASE WHEN TIMESTAMPDIFF(WEEK,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE CASE WHEN TIMESTAMPDIFF(DAY,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(DAY,s.lastdate,CURRENT_TIMESTAMP),' dia',CASE WHEN TIMESTAMPDIFF(DAY,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE CASE WHEN TIMESTAMPDIFF(HOUR,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(HOUR,s.lastdate,CURRENT_TIMESTAMP),' hora',CASE WHEN TIMESTAMPDIFF(HOUR,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE CASE WHEN TIMESTAMPDIFF(MINUTE,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(MINUTE,s.lastdate,CURRENT_TIMESTAMP),' minuto',CASE WHEN TIMESTAMPDIFF(MINUTE,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE CASE WHEN TIMESTAMPDIFF(SECOND,s.lastdate,CURRENT_TIMESTAMP) > 0 THEN CONCAT('Hace ',TIMESTAMPDIFF(SECOND,s.lastdate,CURRENT_TIMESTAMP),' segundo',CASE WHEN TIMESTAMPDIFF(SECOND,s.lastdate,CURRENT_TIMESTAMP) > 1 THEN 's' ELSE '' END) ELSE 'No aplica' END END END END END END END) as respuesta");
        $builder->join('login l', 's.idsoporte=l.id');
        $builder->where('s.idcliente', $id);
        $builder->orderBy('s.id', 'DESC');
        $query = $builder->get();
        return $query ? $query->getResult() : false;
    }

    public function update_reporte_by_id($id, $update)
    {
        $builder = $this->db->table('soporte');
        $builder->where('id', $id);
        $builder->update($update);
        $result = $this->db->affectedRows();
        return $result > 0 ? true : false;
    }

    public function close_ticket($id, $motivo)
    {
        $builder = $this->db->table('soporte');
        $builder->where('id', $id);
        $builder->update(['estado' => 'cerrado', 'motivo_cierre' => $motivo]);
        $result = $this->db->affectedRows();
        return $result > 0 ? true : false;
    }

    public function update_reporte_close_by_id($id, $array)
    {
        $builder = $this->db->table('soporte');
        $builder->where('id', $id);
        $builder->update($array);
        $result = $this->db->affectedRows();
        return $result > 0 ? true : false;
    }

    public function delete_reply_id($id)
    {
        $builder = $this->db->table('ticket');
        $builder->where('id', $id);
        return $builder->delete() ? true : false;
    }

    public function update_ticket_option($id, $array)
    {
        $builder = $this->db->table('soporte');
        $builder->where('id', $id);
        $builder->update($array);
        $result = $this->db->affectedRows();
        return $result > 0 ? true : false;
    }
}
