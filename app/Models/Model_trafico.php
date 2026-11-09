<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;

#[\AllowDynamicProperties]
class Model_trafico
{
  var $db1;

  public function __construct()
  {
    $db1 = \Config\Database::connect('default');
    $this->db1 = $db1;
  }

  public function get_trafico_info_diario($idcliente, $inicio, $final, $servicio = 0)
  {
    $idcliente = intval($idcliente);
    $idcliente = strval($idcliente); 
    $servicio = intval($servicio);
    $servicio = strval($servicio);
    $builder = $this->db1->table('traficodata r');
    $builder->select('SUM(r.upload) subida,SUM(r.dowload) descarga,count(id) sesiones');
    $builder->where('r.idcliente', $idcliente);
    $builder->where('r.datestart>=', $inicio);
    $builder->where('r.datestart<=', $final);
    if ($servicio != 0) $builder->where('r.idservicio', $servicio);
    $query = $builder->get();
    return $query ? $query->getRow() : false;
  }
  public function get_trafico_grafico_diario($idcliente, $inicio, $final, $servicio = 0)
  {
    $idcliente = intval($idcliente);
    $idcliente = strval($idcliente); 
    $servicio = intval($servicio);
    $servicio = strval($servicio);
    $builder = $this->db1->table('traficodata r');
    $builder->select('DATE_FORMAT(r.datestart,\'%Y-%m-%d\') fecha,SUM(r.upload) subida,SUM(r.dowload) descarga');
    $builder->where('r.idcliente', $idcliente);
    $builder->where('r.datestart>=', $inicio);
    $builder->where('r.datestart<=', $final);
    if ($servicio != '0') $builder->where('r.idservicio', $servicio);
    $builder->groupBy("DATE_FORMAT(r.datestart, '%Y%m%d')");
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_trafico_grafico_mensual($idcliente, $anio, $servicio = 0)
  {
    $idcliente = intval($idcliente);
    $idcliente = strval($idcliente); 
    $servicio = intval($servicio);
    $servicio = strval($servicio);
    $builder = $this->db1->table('traficodata r');
    $builder->select('m.nombre fecha,SUM(r.upload) subida,SUM(r.dowload) descarga');
    $builder->join('meses m', 'm.id=MONTH(r.datestart)', 'right');
    $builder->groupStart();
    if ($servicio != '0') $builder->where('r.idservicio', $servicio);
    $builder->where('r.idcliente', $idcliente);
    $builder->where('YEAR(r.datestart)', $anio);
    $builder->groupEnd();
    $builder->orGroupStart();
    $builder->where('m.id>', 0);
    $builder->where('upload IS NULL');
    $builder->groupEnd();
    $builder->groupBy("m.id");
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
  public function get_trafico_info_mensual($idcliente, $anio, $servicio = 0)
  {
    $idcliente = intval($idcliente);
    $idcliente = strval($idcliente);
    $servicio = intval($servicio);
    $servicio = strval($servicio);
    $builder = $this->db1->table('traficodata r');
    $builder->select('SUM(r.upload) subida,SUM(r.dowload) descarga,count(id) sesiones');
    $builder->where('r.idcliente', $idcliente);
    $builder->where('YEAR(r.datestart)', $anio);
    if ($servicio != 0) $builder->where('r.idservicio', $servicio);
    $query = $builder->get();
    return $query ? $query->getRow() : false;
  }
  public function get_trafico_radact($idcliente, $inicio, $final, $servicio = 0)
  {

    $idcliente = intval($idcliente);
    $idcliente = strval($idcliente); 
    $servicio = intval($servicio);
    $servicio = strval($servicio);
    $builder = $this->db1->table('traficodata tn');
    $builder->select('DATE_FORMAT(tn.datestart,\'%d-%m-%Y\') conectado,tn.datestart tiempo,SUM(tn.dowload) descarga,SUM(tn.upload) subida,tn.server ipv4,tn.mac,tn.ipaddress router');
    $builder->where('tn.idcliente', $idcliente);
    $builder->where('tn.datestart>=', $inicio);
    $builder->where('tn.datestart<=', $final);
    if ($servicio != '0') $builder->where('tn.idservicio', $servicio);
    $builder->groupBy('CONCAT(YEAR(tn.datestart),"-",MONTH(tn.datestart),"-",DAY(tn.datestart))');
    $builder->orderBy('tn.datestart DESC');
    $query = $builder->get();
    return $query ? $query->getResult() : false;
  }
}
