<?php
include "funciones_desconexion_conexion.php";
date_default_timezone_set('America/Mexico_City');
$fecha=date("Y-m-d H:i:s");
$maximoDeDesconexionesTolerables=7;
$rangoDeHoras=1;


$fallos=GetFallosLog($fecha,$rangoDeHoras);
foreach ($fallos as $value) {
  if ($value['fallos']>$maximoDeDesconexionesTolerables and $value['info']==0) {
    changeInfo($value['id']);
    $idSoporte = InsertTblSoporte($value['id'], $fecha);
    if ($idSoporte <> 0) {
      $result = InsertTblTikect($idSoporte, $value['id'], $fecha,$rangoDeHoras);
      if ($result) InsertLog($idSoporte, $value['id'], $fecha);
    }
  }
}
