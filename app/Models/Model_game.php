<?php

namespace App\Models;

use CodeIgniter\Model;

#[\AllowDynamicProperties]
class Model_game extends Model
{
  function __construct()
  {
    $db1 = \Config\Database::connect('default');
    $this->db1 = $db1;
  }
  public function get_preguntas(int $tipo){
    $builder = $this->db1->table('game_preguntas p');
    $builder->select('p.*,(SELECT COUNT(id) FROM game_respuestas WHERE id_pregunta=p.id) numrespuestas');
    $builder->where("p.tipo",$tipo);
    $builder->orderBy('p.id ASC');
    $query = $builder->get();
  return $query ? $query->getResult() : false;
  }
  public function save_new_pregunta($data){
    $builder = $this->db1->table('game_preguntas');
    $builder->insert($data);
    $result = $this->db1->affectedRows();
    $id = $this->db1->insertID();
    return $result ? $id : false;
  }
  public function delete_pregunta($id){
    $builder = $this->db1->table('game_preguntas');
    $builder->where('id', $id);
    return $builder->delete() ? true : false;
  }
  public function save_respuestas($data){
    $builder = $this->db1->table('game_respuestas');
    $builder->insertBatch($data);
    $result = $this->db1->affectedRows();
    return $result ? true : false;
  }
  public function get_respuestas($id){
    $builder = $this->db1->table('game_respuestas');
    $builder->select('*');
    $builder->where('id_pregunta',$id);
    $query = $builder->get();
  return $query ? $query->getResult() : false;
  }
  public function delete_respuestas($id){
    $builder = $this->db1->table('game_respuestas');
    $builder->where('id_pregunta', $id);
    return $builder->delete() ? true : false;
  }
}
