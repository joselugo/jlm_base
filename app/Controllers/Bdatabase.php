<?php

namespace App\Controllers;

//use Kunnu\Dropbox\Dropbox;
//use Kunnu\Dropbox\DropboxApp;

use App\Libraries\Class_seguridad;
use App\Models\Model_bdatabase;
use App\Models\Model_log;

class Bdatabase extends BaseController
{
    var $model_bdatabase;
    var $seguridad;
    var $bac;
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
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('CREATE_BACKUP'));
            if ($tipoacceso >= 2) {
                $data['tipoacceso'] = $tipoacceso;
                $data['tblcolumns'] = $this->model_bdatabase->get_all_columns();
                $data['table_name'] = $this->model_bdatabase->get_all_tables();
                $data['MB'] = $this->model_bdatabase->get_size_MB();
                echo view('basedatos/database', $data);
            } else {
                echo acceso_denegado();
            }
        } else {
            echo view('seguridad/login');
        }
    }

    public function view_tables()
    {
        if (isset($_SESSION['id_usuario8291']) && isset($_SESSION['token8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('CREATE_BACKUP'));
            if ($tipoacceso >= 2) {
                $backup = $this->model_bdatabase->get_tables_database();
                $array_sistema = array();
                if ($backup) {
                    foreach ($backup as $items) {
                        $array_item = array(
                            "table_name" => $items->TABLE_NAME,
                            "table_rows" => $items->TABLE_ROWS,
                            "data_length" => $this->formatSizeUnits($items->DATA_LENGTH)
                        );
                        array_push($array_sistema, $array_item);
                    }
                }
                echo '{"data":' . json_encode($array_sistema) . '}';
            } else {
                echo acceso_denegado();
            }
        } else {
            echo view('seguridad/login');
        }
    }

    public function create_backup()
    {
        if (isset($_SESSION['id_usuario8291']) && isset($_SESSION['token8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('CREATE_BACKUP'));
            if ($tipoacceso >= 2) {
                $dir = ROOTPATH . "backup/";
                $filename = "Mikrowisp6_backup_" . date("d_m_Y") . ".sql" . ".zip";

                // Credenciales desde .env; la contraseña va por MYSQL_PWD para
                // que no quede visible en la lista de procesos
                $db = config('Database')->default;
                putenv('MYSQL_PWD=' . $db['password']);
                $cmd = "mysqldump -h " . escapeshellarg($db['hostname']) . " -P " . (int) $db['port']
                    . " -u " . escapeshellarg($db['username']) . " " . escapeshellarg($db['database'])
                    . " | gzip > " . escapeshellarg($dir . $filename);

                exec($cmd, $salida, $estado);
                putenv('MYSQL_PWD');

                if ($estado === 0 && is_file($dir . $filename) && filesize($dir . $filename) > 0) {
                    $data['error'] = 1;
                    $data['salida'] = $filename;
                } else {
                    $data['error'] = 2;
                }
                return json_encode($data);
            } else {
                echo acceso_denegado();
            }
        } else {
            echo view('seguridad/login');
        }
    }

    // backup/ está denegado por HTTP; la descarga pasa por aquí con sesión y permiso
    public function descargar_backup()
    {
        if (isset($_SESSION['id_usuario8291']) && isset($_SESSION['token8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('CREATE_BACKUP'));
            if ($tipoacceso >= 2) {
                $fileName = basename((string) $this->request->getGet('archivo'));
                $filePath = ROOTPATH . 'backup/' . $fileName;
                if (preg_match('/^Mikrowisp6_backup_[0-9_-]+\.sql\.zip$/', $fileName) && is_file($filePath)) {
                    header("Content-Type: application/zip");
                    header("Content-Disposition: attachment; filename=\"$fileName\"");
                    header("Content-Length: " . filesize($filePath));
                    readfile($filePath);
                    exit;
                }
                echo "<p>ARCHIVO NO ENCONTRADO.</p>";
            } else {
                echo acceso_denegado();
            }
        } else {
            echo view('seguridad/login');
        }
    }

    /* public function download_bac($name)
    {
        if (!empty($_GET[$name])) {
            $fileName = basename($_GET[$name]);
            $filePath = 'backup/' . $fileName;
            if (!empty($fileName) && file_exists($filePath)) {
                // Define headers
                header("Cache-Control: public");
                header("Content-Description: File Transfer");
                header("Content-Disposition: attachment; filename=$fileName");
                header("Content-Type: application/zip");
                header("Content-Transfer-Encoding: binary");

                // Read the file
                readfile($filePath);
                exit;
            } else {
                echo 'The file does not exist.';
            }
        }
    } */


    public function new_campo()
    {
        if (isset($_SESSION['id_usuario8291']) && isset($_SESSION['token8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('CREATE_BACKUP'));
            if ($tipoacceso >= 2) {
                $newcampo = $this->request->getPost('campo');
                $insert = $this->model_bdatabase->new_campo($newcampo);
                if ($insert) {
                    echo 1;
                } else {
                    echo 2;
                }
            } else {
                echo acceso_denegado();
            }
        } else {
            echo view('seguridad/login');
        }
    }

    public function delete_campo()
    {
        if (isset($_SESSION['id_usuario8291']) && isset($_SESSION['token8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('CREATE_BACKUP'));
            if ($tipoacceso >= 1) {
                $delete_campo = $this->request->getPost('campo');
                $insert_log = $this->model_log->insert_log("000000", "Base_Datos", "Campo eliminado <b>#" . $delete_campo . " de la tabla usuarios por " . $_SESSION['nombre'] . " </b>", $_SESSION['id_usuario8291']);
                $delete = $this->model_bdatabase->delete_campo($delete_campo);
                if ($delete) {
                    echo 1;
                } else {
                    echo 2;
                }
            } else {
                echo acceso_denegado();
            }
        } else {
            echo view('seguridad/login');
        }
    }


    public function emptytable()
    {
        if (isset($_SESSION['id_usuario8291']) && isset($_SESSION['token8291'])) {
            $tipoacceso = $this->seguridad->verificar_permiso($_SESSION['id_usuario8291'], $this->seguridad->clave_permiso('CREATE_BACKUP'));
            if ($tipoacceso >= 2) {
                $emptycampo = $this->request->getPost('campo');
                $delete = $this->model_bdatabase->empytable($emptycampo);
                $insert_log = $this->model_log->insert_log("000000", "Base_Datos", "Tabla reestablecida <b>#" . $emptycampo . " de la BD Mikrowisp6 por " . $_SESSION['nombre'] . " </b>", $_SESSION['id_usuario8291']);
                if ($delete) {
                    echo 1;
                } else {
                    echo 2;
                }
            } else {
                echo acceso_denegado();
            }
        } else {
            echo view('seguridad/login');
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
