<?php

/**
 * Logo general del sistema (login y barra superior).
 *
 * Se sube en Ajustes > General y se guarda como public/images/logo_sistema.<ext>.
 * Mientras no se suba uno se usa el logo de siempre (public/images/logo-inicio.png).
 */

if (! function_exists('logo_sistema_archivo')) {
    /** Ruta relativa al proyecto del logo vigente. */
    function logo_sistema_archivo(): string
    {
        foreach (['png', 'jpg', 'jpeg'] as $ext) {
            if (is_file(FCPATH . "public/images/logo_sistema.$ext")) {
                return "public/images/logo_sistema.$ext";
            }
        }

        return 'public/images/logo-inicio.png';
    }
}

if (! function_exists('ajuste_sistema')) {
    /** Valor de un ajuste de tblconfiguration (columna setting); $porDefecto si no existe. */
    function ajuste_sistema(string $ajuste, string $porDefecto = ''): string
    {
        static $cache = [];
        if (! array_key_exists($ajuste, $cache)) {
            try {
                $fila = db_connect()->table('tblconfiguration')->select('value')->where('setting', $ajuste)->get()->getRow();
                $cache[$ajuste] = $fila ? (string) $fila->value : null;
            } catch (\Throwable $e) {
                $cache[$ajuste] = null;
            }
        }

        return ($cache[$ajuste] ?? '') !== '' ? $cache[$ajuste] : $porDefecto;
    }
}

if (! function_exists('acceso_denegado')) {
    /**
     * Respuesta cuando el usuario no tiene permiso.
     *
     * - Pantallas y modales (GET que espera HTML): vista amigable que indica qué permiso falta.
     * - Acciones (POST) y peticiones que esperan JSON: el texto de siempre, "<p>ACCESO DENEGADO.</p>",
     *   porque varias pantallas comparan ese texto exacto en JavaScript para mostrar una alerta.
     */
    function acceso_denegado(): string
    {
        $legado  = '<p>ACCESO DENEGADO.</p>';
        $request = service('request');
        if (! $request instanceof \CodeIgniter\HTTP\IncomingRequest
            || strtoupper($request->getMethod()) !== 'GET'
            || str_contains(strtolower($request->getHeaderLine('Accept')), 'application/json')) {
            return $legado;
        }

        return view('errors/acceso_denegado', [
            'permiso' => \App\Libraries\Class_seguridad::nombreUltimoPermiso(),
            'pagina'  => ! $request->isAJAX(),
        ]);
    }
}

if (! function_exists('logo_sistema_url')) {
    /** URL absoluta del logo, con versión para que el navegador no muestre el anterior. */
    function logo_sistema_url(): string
    {
        $archivo = logo_sistema_archivo();

        return base_url($archivo) . '?v=' . (is_file(FCPATH . $archivo) ? filemtime(FCPATH . $archivo) : 0);
    }
}
