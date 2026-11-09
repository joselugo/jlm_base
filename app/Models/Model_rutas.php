<?php

namespace App\Models;

use CodeIgniter\Model;

#[\AllowDynamicProperties]
class Model_rutas extends Model
{
  var $db1;
  function __construct()
  {
    $db1 = \Config\Database::connect('default');
    $this->db1 = $db1;
  }
  public function traer_rutas()
  {
    $builder = $this->db1->table('permiso');
    $builder->select('*');
    $query = $builder->get();
    if ($query != false) {
      return $query->getResult();
    } else {
      return false;
    }
  }

  public function traer_hijos()
  {
    $builder = $this->db1->table('permiso');
    $builder->select('modulo,id_permiso,padre,orden,descripcion,url');
    $builder->where('padre', 0);
    $query = $builder->get();
    if ($query != false) {
      return $query->getResult();
    } else {
      return false;
    }
  }

  public function create_ruta($data)
  {
    $builder = $this->db1->table('permiso');
    $builder->insert($data);
    if ($this->db1->affectedRows() > 0) return true;
    else return false;
  }
  public function delete_ruta($id)
  {
    $builder = $this->db1->table('permiso');
    $builder->where('id_permiso', $id);
    return $builder->delete() ? true : false;
  }
}
