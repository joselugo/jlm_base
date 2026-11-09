<?php
include 'conexion.php';
$conn = conectar();

  $sql = "SELECT id,ip,comunidad_escritura FROM `olt`";
  $result = $conn->query($sql);
  $temp = array(); snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
  if ($result->num_rows > 0) {
    while ($row = mysqli_fetch_array($result)) {
      $ip = $row['ip'];
      $comunidad_escritura = $row['comunidad_escritura'];
      $result_snmp_con="";
      $estado="";
      $onus=array();
      $array = @snmp2_real_walk("$ip","$comunidad_escritura","1.3.6.1.2.1.1.1");
      if($array){foreach ($array as $key) {$result_snmp_con=$key;}}
      if($result_snmp_con=='Huawei Integrated Access Software'){$result_snmp_con="snmp-ok";}
        if($result_snmp_con=="snmp-ok"){
           $array = @snmp2_real_walk("$ip","$comunidad_escritura",".1.3.6.1.4.1.2011.6.128.1.1.2.46.1.15",5000000,3);
           if($array){
            foreach ($array as $key => $value) {
               $child=explode(".", $key);
               $idpon=$child[15];
               $idonu=$child[16];
              // array_push($onus, array("idpon"=>$idpon,"idonu"=>$idonu,"estado"=>$value));
               if($value==1){$estado="ONLINE";}else{$estado="OFFLINE";}
               $sql1 = "UPDATE olt_onus SET `estado`= '$estado' WHERE `idpon`='$idpon' AND `idonu`=$idonu";
               $conn->query($sql1);
             }
            // print_r($onus);
           }
      }
    }
  }

?>


