<?php

namespace App\Controllers;

use App\Libraries\Class_seguridad;
use App\Models\Model_bdatabase;
use App\Models\Model_log;

class System extends BaseController
{
    var $model_bdatabase;
    var $seguridad;
    var $model_log;


    public function __construct()
    {
        $this->model_bdatabase = new Model_bdatabase();
        $this->seguridad = new Class_seguridad();
        $this->model_log = new Model_log();
        session();
    }

    public function index()
    {

        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('SYSTEM'));
            if ($tipoacceso >= 2) {
                $data['tipoacceso'] = $tipoacceso;
                $data['logconexion'] = $this->model_bdatabase->get_log_conexion();
                $data['emails'] = $this->model_bdatabase->get_emails();
                $data['logsistema'] = $this->model_bdatabase->get_log_sistema();
                $data['totalsuport'] = $this->list_files_soport();
                $data['sizesuport'] = $this->list_size_soport();
                $data['sizeserver'] = $this->list_size_server();

                echo view('system/index', $data);
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    public function list_files()
    {
        if (isset($_SESSION['id_usuario8291']) && isset($_SESSION['token8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('SYSTEM'));
            if ($tipoacceso >= 2) {

                $array_server = array();
                $path = "backup/";
                $dir_handle = opendir($path) or die("No se encuentro: $path");

                while ($file = readdir($dir_handle)) {
                    if ($file == "." || $file == ".." || $file == "index.php") {
                        continue;
                    }

                    $tool = "";
                    if ($tipoacceso == 1) {
                        $tool;
                    } else if ($tipoacceso == 2 || $tipoacceso == 3) {
                        $tool = '<td class=" text-center">
                            <a href="' . $path . '' . $file . '"; data-toggle="tooltip" title="Descargar" class="btn btn-default btn-icon btn-sm"><i class="fas fa-download"></i></a></td>';
                    }

                    $time = date('d-m-Y', filemtime($path . "/" . $file));
                    $sizee = $this->formatSizeUnits(filesize($path . "/" . $file));
                    $array_items = array(
                        "name" => $file,
                        "size" => $sizee,
                        "time" => $time,
                        "tool" => $tool
                    );
                    array_push($array_server, $array_items);
                }
                closedir($dir_handle);
                echo '{"data":' . json_encode($array_server) . '}';
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    function list_files_soport()
    {
        $path = scandir("public/uploads/soporte/");
        $num = 0;

        for ($i = 2; $i < count($path); $i++) {
            $num++;
        }
        return $num;
    }

    function list_size_soport()
    {
        $path = "public/uploads/soporte";
        $dir_handle = opendir($path) or die("No se encuentro: $path");
        $mb = $this->formatSizeUnits(filesize($path));
        return $mb;
    }

    function list_size_server()
    {
        $path = "/var/log/apache2";
        // Ruta de Linux: en otros servidores (p. ej. XAMPP en Windows) no existe y antes daba error 500.
        if (!is_dir($path)) {
            return 'N/D';
        }
        $mb = $this->formatSizeUnits(filesize($path));
        return $mb;
    }

    /* function formatSizeUnitsMB($bytes)
    {
        if ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' GB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' MB';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    } */


    public function purge()
    {
        if (isset($_SESSION['id_usuario8291']) && isset($_SESSION['token8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('SYSTEM'));
            if ($tipoacceso >= 2) {

                $tipo = $this->request->getPost('tipo');
                $fecha = $this->request->getPost('fecha');
                $fecha = str_replace("/", "-", $fecha);
                $fecha = date('d-m-Y', strtotime($fecha));
                $fecha = date('Y-m-d', strtotime($fecha));



                if ($tipo == 'fechalogcliente') {
                    $delete = $this->model_bdatabase->purgeconexion($fecha);
                    if ($delete) {
                        $txt_log = "Log de conexion de cliente online/offline - Datos eliminado antes de " . $fecha;
                        $insert_log = $this->model_log->insert_log("000000", "Sistema", $txt_log, $_SESSION['id_usuario8291']);
                        echo 1;
                    } else {
                        echo 2;
                    }
                } else if ($tipo == 'fechamail') {
                    $delete = $this->model_bdatabase->purgeemails($fecha);
                    if ($delete) {
                        $txt_log = "Emails guardados - Datos eliminado antes de " . $fecha;
                        $insert_log = $this->model_log->insert_log("000000", "Sistema", $txt_log, $_SESSION['id_usuario8291']);
                        echo 1;
                    } else {
                        echo 2;
                    }
                } else if ($tipo == 'fechalogsistema') {
                    $delete = $this->model_bdatabase->purgesistema($fecha);
                    if ($delete) {
                        $txt_log = "Log del sistema - Datos eliminado antes de " . $fecha;
                        $insert_log = $this->model_log->insert_log("000000", "Sistema", $txt_log, $_SESSION['id_usuario8291']);
                        echo 1;
                    } else {
                        echo 2;
                    }
                } else if ($tipo == 'fechaadjunto') {
                    $files = glob('public/uploads/soporte/*'); //obtenemos el nombre de todos los ficheros
                    $currentTime = strtotime($fecha);
                    foreach ($files as $file) {
                        $lastModifiedTime = filemtime($file);
                        if ($lastModifiedTime < $currentTime) {
                            unlink($file); //elimino el fichero
                        }
                        /* $timeDiff = abs($currentTime - $lastModifiedTime); //en horas
                        if (is_file($file) && $timeDiff > 10){
                            unlink($file); //elimino el fichero
                        } */
                    }
                } else if ($tipo == 'fechalinux') {
                }
            } else {
                echo acceso_denegado();
            }
        } else {
            $this->seguridad->login_back();
        }
    }

    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}
