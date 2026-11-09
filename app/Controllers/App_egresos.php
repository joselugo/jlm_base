<?php namespace App\Controllers;
use App\Models\Model_ajustes;
class App_egresos extends BaseController {

    var $model_ajustes;
	public function __construct(){
		$this->model_ajustes=new Model_ajustes();
	}

// Muestra el comprobante de un egreso guardado en Dropbox (vista facturacion/egresos)
public function download_file_dropbox($file){

        $row_dropbox=$this->model_ajustes->get_cloud('dropbox');
        $filename='/uploads/comprobantes/'.basename($file);

        $api_url = 'https://content.dropboxapi.com/2/files/download'; //dropbox api url

        $headers = array('Authorization: Bearer '. $row_dropbox->token,
            'Content-Type: application/octet-stream',
            'Dropbox-API-Arg: '.
            json_encode(
                array(
                    "path"=> $filename
                )
            )

        );

       $ch = curl_init($api_url);

       curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
       curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        echo '<img class="avataradmin" style="width:100%" src="data:image/jpeg;base64,'.base64_encode( $response ).'"/>';

        curl_close($ch);

}

}
