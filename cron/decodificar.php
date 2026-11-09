<?php


function decodificar($string)
{
  if ($string == "") return json_decode('{"1":"","2":"","3":""}');
  $code = $string;



  for ($i = 0; $i < strlen($code); $i++) {

    if ($code[$i] == "a") {

      $posicion_llave_abrir = stripos($code, "{", $i);
      $primera_parte = substr($code, 0, $i);
      $segunda_parte = substr($code, $posicion_llave_abrir, strlen($code));
      $nuevo_String = $primera_parte . $segunda_parte;
      $code = $nuevo_String;
    }
    if ($code[$i] == "i") {
      $posicion_punto_coma = stripos($code, ";", $i);
      $primera_parte = substr($code, 0, $i);
      $segunda_parte = substr($code, $posicion_punto_coma + 1, strlen($code));
      $nombre = "";
      for ($e = $i + 2; $e < $posicion_punto_coma; $e++) {
        $nombre .= $code[$e];
      }
      $nuevo_String = $primera_parte . "\"" . $nombre . "\";" . $segunda_parte;
      $code = $nuevo_String;
      $i = $i + 3;
    }
    if ($code[$i] == "s") {
      $posicion_dos_puntos1 = $i + 2;
      $posicion_dos_puntos2 = stripos($code, ":", $i + 2);
      $largo = "";
      for ($e = $i + 2; $e < $posicion_dos_puntos2; $e++) {
        $largo .= $code[$e];
      }
      $largo = strval($largo);
      $primera_parte = substr($code, 0, $i);
      $segunda_parte = substr($code, $posicion_dos_puntos2 + 1, strlen($code));
      $nuevo_String = $primera_parte . $segunda_parte;
      $code = $nuevo_String;
      $i = $i + $largo + 2;
    }
  }
  $validacion = true;
  for ($i = 0; $i < strlen($code); $i++) {
    if ($code[$i] == "{") {
      $validacion = true;
    }
    if ($code[$i] == "}") {
      $validacion = false;
      $posicion_coma = $i - 1;
      if ($code[$i - 1] != "}") {
        $primera_parte = substr($code, 0, $posicion_coma);
        $segunda_parte = substr($code, $i, strlen($code));
        $nuevo_String = $primera_parte . $segunda_parte;
        $code = $nuevo_String;
        $i = $i - 1;
      }
    }
    if ($code[$i] == ";") {
      $validacion ? $code[$i] = ":" : $code[$i] = ",";
      $validacion = !$validacion;
    }
  }
  $code = preg_replace("[\n]", "", $code);
  $code = preg_replace("[\r]", "", $code);
  return json_decode($code);
}

function codificar(array $array)
{
  $otros_impuestos = "";
  foreach ($array as $key => $fila) {
    $largo = count($array);
    $objeto = (object) $fila;
    $columnas_y_valores = "";
    foreach ($fila as $propiedad => $value) {
      $large_propiedad = strlen($propiedad);
      $large_value = strlen($value);
      $type_propiedad = substr(gettype($propiedad), 0, 1);
      $type_value = substr(gettype($value), 0, 1);
      $campo = "$type_propiedad:$large_propiedad:\"$propiedad\";";
      $valor = "$type_value:$large_value:\"$value\";";
      $columnas_y_valores .= $campo . $valor;
    }
    $type = substr(gettype($key), 0, 1);
    $otros_impuestos .= "$type:$key;a:$largo{{$columnas_y_valores}};";
  }
  $data = "a1{{$otros_impuestos}}";
  return $data;
}
function codificar_post(array $array)
{
  $texto_final = "";
  $largo = count($array);
  $columnas_y_valores = "";
  foreach ($array as $propiedad => $value) {
    $large_propiedad = strlen($propiedad);
    $large_value = strlen($value);
    $type_propiedad = substr(gettype($propiedad), 0, 1);
    $type_value = substr(gettype($value), 0, 1);
    $campo = "$type_propiedad:$large_propiedad:\"$propiedad\";";
    $valor = "$type_value:$large_value:\"$value\";";
    $columnas_y_valores .= $campo . $valor;
  }
  $texto_final .= "a:$largo:{{$columnas_y_valores}}";

  return $texto_final;
}
