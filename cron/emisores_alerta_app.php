<?php 
sleep(10);
include 'conexion.php';

$enviar = new Send_notificaction();
$con = conectar();
$result = $con->query("SELECT * FROM emisores");
 while($row = $result->fetch_object())
    {
       if($row->estado_netium=="0" && $row->alert_push_notifi=="on"){}
       else if($row->estado_netium=="0"){
      
        $enviar->send($row->nombre,"Emisor caido");
        $result1= $con->query("UPDATE emisores SET alert_push_notifi='on',estado2='DESCONECTADO' WHERE id='$row->id'");


       }
       else{
         
           if($row->estado_netium=="1" && $row->alert_push_notifi=="on"){
            
             $enviar->send($row->nombre,"Emisor en linea");
             $result1 = $con->query("UPDATE emisores SET alert_push_notifi='', estado2='EN LINEA' WHERE id='$row->id'");

          }
            else if($row->estado_netium=="1"){}

       }
       
    } 

////////////////////////////////////////////////////PARA ENVIAR NOTIFICACIONES DE OTROS SERVIDORES    
      
$ip =   "10.20.30.250";
exec("ping -c 5 $ip", $output, $status);
echo($output[8]);
$pos = strpos($output[8], "100% packet loss");
if($pos){

    $con->query("UPDATE otros_not_push SET estado='DESCONECTADO' WHERE id=1"); 	
   
}
else{
    
	$con->query("UPDATE otros_not_push SET estado='EN LINEA' WHERE id=1");
}

$result = $con->query("SELECT * FROM otros_not_push");
 while($row = $result->fetch_object())
    {
       if($row->estado=="DESCONECTADO" && $row->alert_push_notifi=="on"){}
       else if($row->estado=="DESCONECTADO"){
      
        $enviar->send($row->nombre,"Server caido");
        $result1= $con->query("UPDATE otros_not_push SET alert_push_notifi='on' WHERE id='$row->id'");


       }
       else{
         
           if($row->estado=="EN LINEA" && $row->alert_push_notifi=="on"){
            
             $enviar->send($row->nombre,"Server en linea");
             $result1 = $con->query("UPDATE otros_not_push SET alert_push_notifi='' WHERE id='$row->id'");

          }
            else if($row->estado=="EN LINEA"){}

       }
       
    } 


class Send_notificaction { 
  
    function send($emisor,$title) { 

    	defined('API_ACCESS_KEY') || define('API_ACCESS_KEY', env_valor('integraciones.fcmServerKey', ''));
      
     #API access key from Google API's Console
   
 //  $registrationIds = "eKkQFkHEAHk:APA91bEmpcEqUDC621K8pntT5bi8k0PBt3fB1tz11Hb5EMiuxrZNrp275uj6NVHgUvkjgKeghdg1GUuxhXIDNOFDH5Gi_tsaRNGWYM-hwwhn4n2uJyyBbVE9N5RHRXIbtVdyeVuuKewR";
#prep the bundle
     $msg = array
          (
		'body' 	=> $emisor,
		'title'	=> $title,
             	'icon'	=> 'myicon',/*Default Icon*/
              	'sound' => 'mySound'/*Default sound*/
          );
	$fields = array
			(
				'to'		=> "/topics/emisores",
				'notification'	=> $msg
			);
	
	
	$headers = array
			(
				'Authorization: key=' . API_ACCESS_KEY,
				'Content-Type: application/json'
			);
#Send Reponse To FireBase Server	
	
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
#Echo Result Of FireBase Server
        echo $result;


        
    }

}

?>