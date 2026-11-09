<?php

namespace App\Models;

use App\Libraries\FiltroSucursal;
use CodeIgniter\Model;

/**
 * Datos del inicio (dashboard).
 *
 * Todo se limita a las sucursales del usuario (FiltroSucursal): antes cada widget
 * sumaba los datos de todas las sucursales aunque el usuario tuviera solo una.
 *   clientes, facturas, pagos, tickets, tráfico, log  -> sucursal del cliente (usuarios2.sucursal)
 *   servicios                                         -> tblservicios.sucursal
 *   routers                                           -> tblsucursalesnodo
 * Los emisores no tienen sucursal ni nodo en la base: se muestran a todos.
 */
#[\AllowDynamicProperties]
class Model_dashboard extends Model
{
    public $db1;

    public function __construct()
    {
        $this->db1 = \Config\Database::connect('default');
    }

    /** Condición "<columna idcliente> IN (clientes de mis sucursales)". */
    private function deMisClientes(string $columnaCliente): string
    {
        return FiltroSucursal::todas()
            ? '1=1'
            : $columnaCliente . ' IN (SELECT id FROM usuarios2 WHERE ' . FiltroSucursal::sql('sucursal') . ')';
    }

    /** Condición "<columna id router> pertenece a mis sucursales". */
    private function deMisRouters(string $columnaRouter): string
    {
        return FiltroSucursal::todas()
            ? '1=1'
            : $columnaRouter . ' IN (SELECT idnodo FROM tblsucursalesnodo WHERE ' . FiltroSucursal::sql('idsucursal') . ')';
    }

    public function get_nombre_user(string $id)
    {
        return $this->db1->table('login')->select('nombre username')->where('id', $id)->get()->getRow() ?: false;
    }

    // Servicios en línea según el último cron online_offline (tblservicios.status_user)
    public function get_clientes_online()
    {
        $builder = $this->db1->table('tblservicios')->select('count(id) online')->where('status_user', 'ONLINE');
        FiltroSucursal::aplicar($builder, 'sucursal');

        return $builder->get()->getRow() ?: false;
    }

    public function get_clientes_activos()
    {
        return $this->clientes_activos();
    }

    public function get_pagoshoy(string $anio, string $mes, $dia)
    {
        return $this->db1->table('operaciones')->select('SUM(cobrado) pagos')
            ->where('MONTH(fecha_pago)', $mes)->where('YEAR(fecha_pago)', $anio)->where('DAY(fecha_pago)', $dia)
            ->where($this->deMisClientes('idcliente'), null, false)
            ->get()->getRow() ?: false;
    }

    public function facturas_no_pagadas()
    {
        return $this->db1->table('facturas')->select('count(id) facturas')->where('estado', 'No pagado')
            ->where($this->deMisClientes('idcliente'), null, false)
            ->get()->getRow() ?: false;
    }

    public function soporte_activo()
    {
        return $this->db1->table('soporte')->select('count(id) ticket')->where('estado', 'abierto')
            ->where($this->deMisClientes('idcliente'), null, false)
            ->get()->getRow() ?: false;
    }

    public function servicios_registrados()
    {
        $builder = $this->db1->table('tblservicios t')->select('count(t.id) servicios')
            ->join('usuarios2 u', 'u.id=t.idcliente', 'left')
            ->whereIn('u.estado', ['SUSPENDIDO', 'ACTIVO']);
        FiltroSucursal::aplicar($builder, 't.sucursal');

        return $builder->get()->getRow() ?: false;
    }

    public function pagos_del_mes(string $anio, string $mes)
    {
        return $this->db1->table('operaciones')->select('SUM(cobrado) pagos,count(id) total_pagos')
            ->where('MONTH(fecha_pago)', $mes)->where('YEAR(fecha_pago)', $anio)
            ->where($this->deMisClientes('idcliente'), null, false)
            ->get()->getRow() ?: false;
    }

    public function get_facturas_vencidas(string $hoy)
    {
        return $this->db1->table('facturas')->select('COUNT(id) vencidas')
            ->where('vencimiento<', $hoy)->where('estado', 'No pagado')
            ->where($this->deMisClientes('idcliente'), null, false)
            ->get()->getRow() ?: false;
    }

    public function soporte_abierto()
    {
        return $this->db1->table('soporte')->select('count(id) ticket')
            ->whereIn('estado', ['abierto', 'respondido'])
            ->where($this->deMisClientes('idcliente'), null, false)
            ->get()->getRow() ?: false;
    }

    public function routers_activos()
    {
        return $this->db1->table('server')->select('count(id) activos')->where('estado_netium', '1')
            ->where($this->deMisRouters('id'), null, false)
            ->get()->getRow() ?: false;
    }

    public function routers_desconectados()
    {
        return $this->db1->table('server')->select('count(id) desconectados')->where('estado_netium', '0')
            ->where($this->deMisRouters('id'), null, false)
            ->get()->getRow() ?: false;
    }

    public function clientes_activos()
    {
        $builder = $this->db1->table('usuarios2')->select('count(id) activos')->where('estado', 'ACTIVO');
        FiltroSucursal::aplicar($builder, 'sucursal');

        return $builder->get()->getRow() ?: false;
    }

    public function clientes_suspendidos()
    {
        $builder = $this->db1->table('usuarios2')->select('count(id) suspendidos')->where('estado', 'SUSPENDIDO');
        FiltroSucursal::aplicar($builder, 'sucursal');

        return $builder->get()->getRow() ?: false;
    }

    public function emisores_activos()
    {
        return $this->db1->table('emisores')->select('count(id) activos')->where('estado_netium', 1)->get()->getRow() ?: false;
    }

    public function emisores_caidos()
    {
        return $this->db1->table('emisores')->select('count(id) caidos')->where('estado_netium', 0)->get()->getRow() ?: false;
    }

    public function ultimos_pagos()
    {
        $builder = $this->db1->table('operaciones o')
            ->select('u.id idcliente,u.nombre cliente,o.cobrado,l.nombre,o.fecha_pago fecha')
            ->join('usuarios2 u', 'o.idcliente=u.id', 'left')
            ->join('login l', 'l.id=o.operador', 'left')
            ->orderBy('o.fecha_pago DESC')->limit(10);
        FiltroSucursal::aplicar($builder, 'u.sucursal');

        return $builder->get()->getResult();
    }

    public function ultimos_conectados()
    {
        $builder = $this->db1->table('logsistema l')
            ->select('u.id idcliente,u.nombre,fechalog fecha')
            ->join('usuarios2 u', 'l.idcliente=u.id', 'left')
            ->like('log', 'Servicio Cliente ONLINE')
            ->orderBy('l.id DESC')->limit(8);
        FiltroSucursal::aplicar($builder, 'u.sucursal');

        return $builder->get()->getResult();
    }

    public function facturas_mes(string $anio, string $mes)
    {
        return $this->db1->table('facturas')->select('COUNT(id) facturas,SUM(cobrado) total_facturas')
            ->where('MONTH(vencimiento)', $mes)->where('YEAR(vencimiento)', $anio)->where('estado', 'pagado')
            ->where($this->deMisClientes('idcliente'), null, false)
            ->get()->getRow() ?: false;
    }

    public function facturas_mes_sin_pagar(string $anio, string $mes)
    {
        return $this->db1->table('facturas')->select('COUNT(id) facturas,SUM(total) total_facturas')
            ->where('MONTH(vencimiento)', $mes)->where('YEAR(vencimiento)', $anio)->where('estado', 'No pagado')
            ->where($this->deMisClientes('idcliente'), null, false)
            ->get()->getRow() ?: false;
    }

    public function get_emisores()
    {
        return $this->db1->table('emisores')->select('id,nombre,equipo,ip,estado_netium')->get()->getResult();
    }

    /** @param list<string> $fechas 7 fechas Y-m-d (las arma el controlador) */
    public function get_data_grafico(array $fechas)
    {
        return $this->db1->table('traficodata')
            ->select('CONCAT(DAY(datestart), "/", MONTH(datestart),"/", YEAR(datestart)) period,SUM(IFNULL(upload,0)) rx,SUM(IFNULL(dowload,0)) tx', false)
            ->where('datestart >=', $fechas[0])
            ->where('datestart <', date('Y-m-d', strtotime($fechas[6] . ' +1 day')))
            ->where($this->deMisClientes('idcliente'), null, false)
            ->groupBy('period')->orderBy('MIN(datestart)', 'ASC', false)
            ->get()->getResult();
    }

    /** @param list<string> $fechas */
    public function get_total_trafico_ultimos_7_dias(array $fechas)
    {
        return $this->db1->table('traficodata')
            ->select('IFNULL(SUM(upload),0) subida, IFNULL(SUM(dowload),0) descarga')
            ->where('datestart >=', $fechas[6])
            ->where('datestart <', date('Y-m-d', strtotime($fechas[6] . ' +1 day')))
            ->where($this->deMisClientes('idcliente'), null, false)
            ->get()->getRow() ?: false;
    }
}
