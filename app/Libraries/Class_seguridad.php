<?php

namespace App\Libraries;

use App\Models\Model_seguridad;
use App\Models\Model_configuracion;

#[\AllowDynamicProperties]
class Class_seguridad
{
    /** Última clave consultada con clave_permiso(); la usa la vista de acceso denegado. */
    private static string $ultimaClave = '';

    /**
     * Nombre legible del último permiso consultado, con sus módulos padre
     * ("Ajustes → Zonas"). Vacío si no se consultó ninguno o la clave no existe.
     */
    public static function nombreUltimoPermiso(): string
    {
        if (self::$ultimaClave === '') {
            return '';
        }
        try {
            $tabla   = db_connect()->table('permiso');
            $permiso = $tabla->select('modulo, padre')->where('clave', self::$ultimaClave)->get()->getRow();
            $ruta    = $permiso ? [$permiso->modulo] : [];
            // Sube por los módulos padre (máx. 5 niveles por si hubiera un ciclo en los datos).
            for ($i = 0, $padre = (int) ($permiso->padre ?? 0); $padre > 0 && $i < 5; $i++) {
                $fila = $tabla->select('modulo, padre')->where('id_permiso', $padre)->get()->getRow();
                if (!$fila) {
                    break;
                }
                array_unshift($ruta, $fila->modulo);
                $padre = (int) $fila->padre;
            }
        } catch (\Throwable $e) {
            return '';
        }

        return implode(' → ', $ruta);
    }



    function __construct()

    {
        $this->model_configuracion = new Model_configuracion();



        // $CI->load->helper('url');

        // $CI->load->model('Model_seguridad');

    }



    function pintar_menu($id_usuario)

    {

        $menu = '';

        $id_permiso_ant = 0;

        $modseguridad =  new Model_seguridad();

        $modulos = $modseguridad->traer_menu_sistema($id_usuario, 0);

        if ($modulos != false) {

            $menu = '<ul class="nav">' . PHP_EOL;
            $menu .= '<li class="nav-header">Menú</li>
                    <li class="has-sub active">
                    <a href="#index/home">
                    <i class="fa fa-home"></i>
                    <span>Inicio</span>
                     </a>
                    </li>';

            foreach ($modulos as $padre) {

                if ((int)$padre->id_permiso != $id_permiso_ant) {

                    $mostrar = (((int)$padre->permiso_usuario > 0) || ((int)$padre->permiso_usuario < 0 && (int)$padre->permiso_rol > 0));

                    if ($mostrar) {

                        $onclick = '';

                        $ruta = '<li>Inicio</li><li>' . $padre->modulo . '</li>';

                        if (strlen($padre->url) > 0) {

                            $url = $padre->url;

                            $onclick = '';
                        } else {
                            $url = "javascript:void(0)";
                        }


                        if ($padre->modulo == "Ajustes" ||  $padre->modulo == "Formatos") {

                            $menu .= '  <li class="has-sub"><a href="#' . $url . '" data-toggle="ajax"><i class="' . $padre->clase . '"></i> <span>' . $padre->modulo . '</span></a>' . PHP_EOL;
                        } else {
                            $menu .= '  <li class="has-sub"><a href="' . $url . '"><b class="caret pull-right"></b><i class="' . $padre->clase . '"></i> <span>' . $padre->modulo . '</span></a>' . PHP_EOL;
                        }


                        $permisos = $modseguridad->traer_menu_sistema($id_usuario, $padre->id_permiso);

                        if ($permisos != false) {

                            $menu .= $this->pintar_submenu($id_usuario, $permisos, $ruta);
                        }

                        $menu .= '  </li>' . PHP_EOL;


                        $id_permiso_ant = (int)$padre->id_permiso;
                    }
                }
            }
            $menu .= '<li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fas fa-angle-double-left"></i></a></li>' . PHP_EOL;

            $menu .= '</ul>' . PHP_EOL;
        }

        return $menu;
    }



    function pintar_submenu($id_usuario, $opciones, $ruta)

    {

        $submenu = '';

        $id_permiso_ant = 0;

        $modseguridad =  new Model_seguridad();

        foreach ($opciones as $opcion) {

            if ((int)$opcion->id_permiso != $id_permiso_ant) {

                $mostrar = (((int)$opcion->permiso_usuario > 0) || ((int)$opcion->permiso_usuario < 0 && (int)$opcion->permiso_rol > 0));

                if ($mostrar) {

                    if (strlen($submenu) == 0) {

                        $submenu .= '    <ul class="sub-menu">' . PHP_EOL;
                    }

                    // $onclick = '';

                    // $nuevaruta = $ruta.'<li>'.$opcion->modulo.'</li>';

                    if (strlen($opcion->url) > 0) {

                        //$url = base_url().'index.php/'.$opcion->url;

                        //$onclick = ' onclick="CargarModulo(\''.base_url().'\', \''.$opcion->url.'\', \''.$nuevaruta.'\');"';

                    }

                    $submenu .= '      <li><a href="#' . $opcion->url . '" data-toggle="ajax">';



                    $submenu .= $opcion->modulo . '</a>' . PHP_EOL;

                    $permisos = $modseguridad->traer_menu_sistema($id_usuario, $opcion->id_permiso);

                    if ($permisos != false) {

                        $submenu .= $this->pintar_submenu($id_usuario, $permisos, $ruta);
                    }

                    $submenu .= '      </li>' . PHP_EOL;

                    $id_permiso_ant = (int)$opcion->id_permiso;
                }
            }
        }

        if (strlen($submenu) > 0) {

            $submenu .= '    </ul>' . PHP_EOL;
        }

        return $submenu;
    }



    //Pinta los permisos de un rol determinado, en forma de arbol

    //Solamente pinta los permisos de nivel mas alto

    //Utiliza la funcion hijos_permisos_rol, para pintar las demas opciones

    function pintar_permisos_rol($id_rol)

    {

        $arbol = '';

        $modseguridad =  new Model_seguridad();

        $modulos = $modseguridad->traer_permisos_rol($id_rol, 0);

        if ($modulos != false) {

            $arbol = '<ul>' . PHP_EOL;

            foreach ($modulos as $padre) {

                $arbol .= '  <li>' . PHP_EOL;

                $permisos = $modseguridad->traer_permisos_rol($id_rol, $padre->id_permiso);

                if ($permisos != false) {

                    $arbol .= '    <span><i class="fa fa-lg fa-plus-circle"></i> ' . $padre->modulo . '</span>' . PHP_EOL;

                    $arbol .= $this->hijos_permisos_rol($id_rol, $permisos);
                } else {

                    $arbol .= '    <span>' . $padre->modulo . '</span>' . PHP_EOL;

                    $checked = "";

                    if ((int)$padre->permiso_rol == 0) $checked = ' checked="checked"';

                    $arbol .= '    <label><input type="checkbox" name="chkdenegado[]" id="den' . $padre->id_permiso . '" value="' . $padre->id_permiso . '" onchange="VerificarCheckbox(this.id);" disabled="disabled"' . $checked . '><i></i> Denegado</label>' . PHP_EOL;

                    $checked = "";

                    if ((int)$padre->permiso_rol == 1) $checked = ' checked="checked"';

                    $arbol .= '    <label><input type="checkbox" name="chklectura[]" id="lec' . $padre->id_permiso . '" value="' . $padre->id_permiso . '" onchange="VerificarCheckbox(this.id);" disabled="disabled"' . $checked . '><i></i> Lectura</label>' . PHP_EOL;

                    $checked = "";

                    if ((int)$padre->permiso_rol == 2) $checked = ' checked="checked"';

                    $arbol .= '    <label><input type="checkbox" name="chkescritura[]" id="esc' . $padre->id_permiso . '" value="' . $padre->id_permiso . '" onchange="VerificarCheckbox(this.id);" disabled="disabled"' . $checked . '><i></i> Escritura</label>' . PHP_EOL;
                }

                $arbol .= '  </li>' . PHP_EOL;
            }

            $arbol .= '</ul>' . PHP_EOL;
        }

        return $arbol;
    }



    //Pinta los permisos de un rol determinado, en forma de arbol

    //Se invoca desde la funcion pintar_permisos_rol y en forma recursiva

    //para pintar los permisos a partir del segundo nivel

    function hijos_permisos_rol($id_rol, $opciones)

    {

        $hijos = '    <ul>';

        foreach ($opciones as $opcion) {

            $hijos .= '      <li style="display:none">' . PHP_EOL;

            $hijos .= '        <span>' . $opcion->modulo . '</span>' . PHP_EOL;



            $modseguridad =  new Model_seguridad();

            $permisos = $modseguridad->traer_permisos_rol($id_rol, $opcion->id_permiso);

            if ($permisos != false) {

                $hijos .= $this->hijos_permisos_rol($id_rol, $permisos);
            } else {

                $checked = "";

                if ((int)$opcion->permiso_rol == 0) $checked = ' checked="checked"';

                $hijos .= '        <label><input type="checkbox" name="chkdenegado[]" id="den' . $opcion->id_permiso . '" value="' . $opcion->id_permiso . '" onchange="VerificarCheckbox(this.id);" disabled="disabled"' . $checked . '><i></i> Denegado</label>' . PHP_EOL;

                $checked = "";

                if ((int)$opcion->permiso_rol == 1) $checked = ' checked="checked"';

                $hijos .= '        <label><input type="checkbox" name="chklectura[]" id="lec' . $opcion->id_permiso . '" value="' . $opcion->id_permiso . '" onchange="VerificarCheckbox(this.id);" disabled="disabled"' . $checked . '><i></i> Lectura</label>' . PHP_EOL;

                $checked = "";

                if ((int)$opcion->permiso_rol == 2) $checked = ' checked="checked"';

                $hijos .= '        <label><input type="checkbox" name="chkescritura[]" id="esc' . $opcion->id_permiso . '" value="' . $opcion->id_permiso . '" onchange="VerificarCheckbox(this.id);" disabled="disabled"' . $checked . '><i></i> Escritura</label>' . PHP_EOL;
            }

            $hijos .= '      </li>' . PHP_EOL;
        }

        $hijos .= '    </ul>';

        return $hijos;
    }



    //Pinta los permisos de un usuario determinado, de acuerdo a los roles asignados

    //Solamente pinta los permisos de nivel mas alto en forma de arbol

    //Utiliza la funcion hijos_permisos_roles_usuario, para pintar las demas opciones

    function pintar_permisos_roles_usuario($id_usuario, $editar = 0)

    {

        $arbol = '';

        $modseguridad =  new Model_seguridad();

        $modulos = $modseguridad->traer_permisos_por_padre(0);

        if ($modulos != false) {

            $arbol = '<ul>' . PHP_EOL;

            foreach ($modulos as $padre) {

                $arbol .= '  <li>' . PHP_EOL;

                $permisos = $modseguridad->traer_permisos_por_padre($padre->id_permiso);

                if ($permisos != false) {

                    $arbol .= '    <span><i class="fa fa-lg fa-plus-circle"></i> ' . $padre->modulo . '</span>' . PHP_EOL;

                    $arbol .= $this->hijos_permisos_roles_usuario($id_usuario, $permisos, $editar);
                } else {

                    $arbol .= '    <span>' . $padre->modulo . '</span>' . PHP_EOL;

                    $tipo_acceso = $this->verificar_permiso_por_roles($id_usuario, $padre->id_permiso);

                    $arbol .= '    <label>' . $this->nombre_acceso($tipo_acceso) . '</label>' . PHP_EOL;

                    if ($editar > 0) {

                        $arbol .= '    <a class="btn-default" href="javascript:void(0);" title="Asignar permiso especial" onclick="AsignarPermisoEspecial(' . $id_usuario . ', ' . $padre->id_permiso . ', \'' . $padre->modulo . '\');"> <i class="fa fa-pencil"></i> </a>' . PHP_EOL;
                    }
                }

                $arbol .= '  </li>' . PHP_EOL;
            }

            $arbol .= '</ul>' . PHP_EOL;
        }

        return $arbol;
    }



    //Pinta los permisos de un usuario determinado, de acuerdo a los roles asignados

    //Se invoca desde la funcion pintar_permisos_roles_usuario y en forma recursiva

    //para pintar los permisos a partir del segundo nivel

    function hijos_permisos_roles_usuario($id_usuario, $opciones, $editar = 0)

    {

        $hijos = '    <ul>';

        foreach ($opciones as $opcion) {

            $hijos .= '      <li style="display:none">' . PHP_EOL;

            $hijos .= '        <span>' . $opcion->modulo . '</span>' . PHP_EOL;

            $modseguridad =  new Model_seguridad();

            $permisos = $modseguridad->traer_permisos_por_padre($opcion->id_permiso);

            if ($permisos != false) {

                $hijos .= $this->hijos_permisos_roles_usuario($id_usuario, $permisos, $editar);
            } else {

                $tipo_acceso = $this->verificar_permiso_por_roles($id_usuario, $opcion->id_permiso);

                $hijos .= '        <label>' . $this->nombre_acceso($tipo_acceso) . '</label>' . PHP_EOL;

                if ($editar > 0) {

                    $hijos .= '      <a class="btn-default" href="javascript:void(0);" title="Asignar permiso especial" onclick="AsignarPermisoEspecial(' . $id_usuario . ', ' . $opcion->id_permiso . ', \'' . $opcion->modulo . '\');"> <i class="fa fa-pencil"></i> </a>' . PHP_EOL;
                }
            }

            $hijos .= '      </li>' . PHP_EOL;
        }

        $hijos .= '    </ul>';

        return $hijos;
    }



    //Devuelve el tipo de acceso definido para un usuario y permiso determinados

    //No toma en cuenta los permisos especiales del usuario (usuario_permiso)

    function verificar_permiso_por_roles($id_usuario, $id_permiso)

    {

        $tipo_acceso = 0;

        $modseguridad =  new Model_seguridad();

        $permisos = $modseguridad->verificar_permiso($id_usuario, $id_permiso);

        if ($permisos != false) {

            foreach ($permisos as $permiso) {

                $tipo_acceso_temp = (int)$permiso->permiso_rol;

                if ($tipo_acceso_temp > $tipo_acceso) {

                    $tipo_acceso = $tipo_acceso_temp;
                }
            }
        }

        return $tipo_acceso;
    }



    //Concatena el nombre de los modulos padre, para un permiso determinado

    function concatenar_modulos_padre($modulo, $id_padre)

    {

        $ruta_modulo = $modulo;



        $modseguridad =  new Model_seguridad();

        while ($id_padre > 0) {

            $permisos = $modseguridad->traer_permisos($id_padre);

            if ($permisos != false) {

                foreach ($permisos as $permiso) {

                    $modulo_padre = $permiso->modulo;

                    $id_padre = $permiso->padre;
                }

                $ruta_modulo = $modulo_padre . ' --> ' . $ruta_modulo;
            } else {

                //Si ocurre algun error, nos salimos del ciclo

                break;
            }
        }



        return $ruta_modulo;
    }



    function nombre_acceso($tipo_acceso)

    {

        $nombre_acceso = "";

        switch ($tipo_acceso) {

            case 0:

                $nombre_acceso = "Denegado";

                break;

            case 1:

                $nombre_acceso = "Lectura";

                break;

            case 2:

                $nombre_acceso = "Escritura";

                break;

            default:

                $nombre_acceso = "No definido";
        }

        return $nombre_acceso;
    }



    /**
     * Id del permiso (tabla permiso) que corresponde a una clave, p. ej. "ZONAS".
     *
     * La clave se guarda en la columna permiso.clave (migración ClavePermiso). Para un
     * permiso nuevo basta con escribir su clave en esa columna y usarla en el código con
     * Acceso("MI_CLAVE"); ya no hay que agregarla aquí. Una clave que no existe devuelve 0
     * (acceso denegado) y queda registrada en el log.
     */
    function clave_permiso($modulo = '')
    {
        static $claves = null;
        if ($claves === null) {
            $claves = [];
            try {
                $filas = db_connect()->table('permiso')->select('clave, id_permiso')
                    ->where('clave IS NOT NULL')->get()->getResult();
                foreach ($filas as $fila) {
                    $claves[strtoupper(trim($fila->clave))] = (int) $fila->id_permiso;
                }
            } catch (\Throwable $e) {
                log_message('critical', 'No se pudieron leer las claves de permiso (¿falta correr la migración ClavePermiso?): ' . $e->getMessage());
            }
        }
        $clave = strtoupper(trim((string) $modulo));
        self::$ultimaClave = $clave;
        if (!isset($claves[$clave])) {
            log_message('warning', 'Permiso sin clave en la tabla permiso: "' . $clave . '"');
            return 0;
        }

        return $claves[$clave];
    }
    function verificar_permiso($id_usuario, $id_permiso)

    {

        $tipo_acceso = 0;

        $modseguridad =  new Model_seguridad();

        $permisos = $modseguridad->verificar_permiso($id_usuario, $id_permiso);

        if ($permisos != false) {


            foreach ($permisos as $permiso) {

                $tipo_acceso_temp = 0;

                if ($permiso->permiso_usuario < 0) {

                    //Significa que no está definido un permiso por usuario y se toma el permiso por rol

                    $tipo_acceso_temp = (int)$permiso->permiso_rol;
                } else {

                    //Si está definido el permiso por usuario, se da prioridad a este valor sobre el permiso por rol.

                    $tipo_acceso_temp = (int)$permiso->permiso_usuario;
                }

                if ($tipo_acceso_temp > $tipo_acceso) {

                    $tipo_acceso = $tipo_acceso_temp;
                }
            }
        }

        return $tipo_acceso;
    }

    function verificar_permiso1($id_usuario, $id_permiso)

    {

        $tipo_acceso = 0;

        $modseguridad =  new Model_seguridad();

        $permisos = $modseguridad->verificar_permiso($id_usuario, $id_permiso);

        if ($permisos != false) {

            foreach ($permisos as $permiso) {

                $tipo_acceso_temp = 0;

                if ($permiso->permiso_usuario < 0) {

                    //Significa que no está definido un permiso por usuario y se toma el permiso por rol

                    $tipo_acceso_temp = (int)$permiso->permiso_rol;
                } else {

                    //Si está definido el permiso por usuario, se da prioridad a este valor sobre el permiso por rol.

                    $tipo_acceso_temp = (int)$permiso->permiso_usuario;
                }

                if ($tipo_acceso_temp > $tipo_acceso) {

                    $tipo_acceso = $tipo_acceso_temp;
                }
            }
        }

        return $tipo_acceso;
    }

    public function Acceso($NombreDeAcceso = "", $tipoDeAcceso = "escritura", $session = null)
    {
        $tipoDeAcceso = strtolower($tipoDeAcceso);
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->verificar_permiso($_SESSION['id_usuario8291'], $this->clave_permiso($NombreDeAcceso));
            if ($tipoacceso == 1 and $tipoDeAcceso == "lectura") {
                return $tipoacceso;
            } elseif ($tipoacceso >= 2 and ($tipoDeAcceso == "escritura" or $tipoDeAcceso == "lectura")) {
                return $tipoacceso;
            } else {
                echo acceso_denegado();
                return false;
            }
        } else {
            $ids = array(64);
            $img_db = $this->model_configuracion->get_configuracion($ids);
            $data['img'] = $img_db[0];
            echo view('seguridad/login', $data);
            return false;
        }
    }
    public function Acceso_full($NombreDeAcceso = "", $tipoDeAcceso = "escritura", $session = null)
    {
        $tipoDeAcceso = strtolower($tipoDeAcceso);
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->verificar_permiso($_SESSION['id_usuario8291'], $this->clave_permiso($NombreDeAcceso));
            if ($tipoacceso == 1 and $tipoDeAcceso == "lectura") {
                return $tipoacceso;
            } elseif ($tipoacceso >= 2 and ($tipoDeAcceso == "escritura" or $tipoDeAcceso == "lectura")) {
                return $tipoacceso;
            }elseif ($tipoacceso >= 3 and ($tipoDeAcceso == "escritura" or $tipoDeAcceso == "lectura" or $tipoDeAcceso == "eliminar")){
                return $tipoacceso;
            }else {
                echo acceso_denegado();
                return false;
            }
        } else {
            $ids = array(64);
            $img_db = $this->model_configuracion->get_configuracion($ids);
            $data['img'] = $img_db[0];
            echo view('seguridad/login', $data);
            return false;
        }
    }
    public function Acceso_void($NombreDeAcceso = "", $tipoDeAcceso = "escritura", $session = null)
    {
        $tipoDeAcceso = strtolower($tipoDeAcceso);
        if (isset($_SESSION['id_usuario8291'])) {
            $tipoacceso = $this->verificar_permiso($_SESSION['id_usuario8291'], $this->clave_permiso($NombreDeAcceso));
            if ($tipoacceso == 1 and $tipoDeAcceso == "lectura") {
                return $tipoacceso;
            } elseif ($tipoacceso >= 2 and ($tipoDeAcceso == "escritura" or $tipoDeAcceso == "lectura")) {
                return $tipoacceso;
            } elseif ($tipoacceso >= 3 and ($tipoDeAcceso == "escritura" or $tipoDeAcceso == "lectura" or $tipoDeAcceso == "eliminar")) {
                return $tipoacceso;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
    public function login_back()
    {
        $ids = array(64);
        $img_db = $this->model_configuracion->get_configuracion($ids);
        $data['img'] = $img_db[0];
        echo view('seguridad/login', $data);
        return false;
    }
}
