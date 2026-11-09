<?php
require_once "conexion.php";

upload_backup();

function createbackup()
{
    $data = false;
    $dir = directorio_backup();
    $filename = "Mikrowisp6_backup_cron_" . date("m-d-Y") . ".sql" . ".zip";

    if (generar_backup($dir . $filename)) {
        $data = $filename;
        //print("Backup creado con exito");
    } else {
        print("Error al crear backup");
    }
    return $data;
}

function upload_backup()
{
    $dir = directorio_backup();
    $filename = createbackup();
    if (!$filename) {
        return;
    }

    $con = conectar();
    $sql = "SELECT * FROM cloud WHERE id = 1";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
        foreach ($result as $items) {
            $token = $items['token'];
            $on = $items['estado'];
            $upbp = $items['upbackup'];
        }
        if ($on == 'on' && $upbp == 'on') {
            $api_url = 'https://content.dropboxapi.com/2/files/upload'; //dropbox api url
            // $token = 'Generated access token'; // oauth token
            $headers = array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/octet-stream',
                'Dropbox-API-Arg: ' .
                    json_encode(
                        array(
                            "path" => '/Backup/' . basename($filename),
                            "mode" => "add",
                            "autorename" => true,
                            "mute" => false
                        )
                    )
            );
            $ch = curl_init($api_url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, true);

            $path = $dir . $filename;

            $fp = fopen($path, 'rb');
            $filesize = filesize($path);

            curl_setopt($ch, CURLOPT_POSTFIELDS, fread($fp, $filesize));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_VERBOSE, 1); // debug

            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);
        }
    }
}

?>


