<?php namespace App\Libraries;
use App\Models\Model_seguridad;

#[\AllowDynamicProperties]
class Class_permisos {




    function verificar_permisos($id_usuario,$clave){
     $bandera=false;
     $modseguridad =  new Model_seguridad();
     $row=$modseguridad->get_permisos($id_usuario);
     $permisos=$row->acceso_tools;
     $array_forma_pago=explode(',', $permisos);
     for ($i=0; $i <count($array_forma_pago) ; $i++) { 
     	
     	 if($array_forma_pago[$i]==$clave){$bandera=true;}
     }

     return $bandera;

    }

    


}

