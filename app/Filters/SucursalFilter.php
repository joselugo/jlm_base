<?php

namespace App\Filters;

use App\Libraries\FiltroSucursal;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

/**
 * Impide abrir por id datos de una sucursal que el usuario no tiene.
 *
 * Los listados se filtran en cada consulta (FiltroSucursal), pero muchas pantallas
 * reciben el id de un cliente, factura, ticket, instalación, cotización, servicio o
 * router: con solo cambiar el número se veían datos de otra sucursal. Este filtro
 * corre antes de cualquier controlador y revisa esos parámetros (GET y POST).
 *
 *  - idcliente / id_cliente en cualquier ruta: siempre es un cliente (usuarios2).
 *  - "id" (u otro nombre) en las rutas de $porRuta: su significado depende de la ruta.
 *
 * Si el registro no existe se deja pasar (no hay nada que mostrar y algunas altas
 * envían el id nuevo). Una ruta nueva que reciba un id de otro tipo debe agregarse aquí.
 */
class SucursalFilter implements FilterInterface
{
    /** Parámetros que en cualquier ruta son el id de un cliente. */
    private const PARAMETROS_CLIENTE = ['idcliente', 'id_cliente'];

    /**
     * "controlador/metodo" (minúsculas) => [parámetro => tipo].
     * Tipos: cliente, factura, ticket, instalacion, cotizacion, servicio, onu, router.
     */
    private const POR_RUTA = [
        // Ficha y pestañas del cliente: "id" es el cliente
        'clientes/detalleuser' => ['id' => 'cliente'],
        'clientes/documentos_cliente' => ['id' => 'cliente'],
        'clientes/get_documentos_json' => ['id' => 'cliente'],
        'clientes/get_log_json' => ['id' => 'cliente'],
        'clientes/log_cliente' => ['id' => 'cliente'],
        'clientes/get_servicios_json' => ['id' => 'cliente'],
        'clientes/get_serviciosvoip_json' => ['id' => 'cliente'],
        'clientes/servicios_cliente' => ['id' => 'cliente'],
        'clientes/get_tickets_record' => ['id' => 'cliente'],
        'clientes/ticket_cliente' => ['id' => 'cliente'],
        'clientes/herramientas' => ['id' => 'cliente'],
        'clientes/nav_email_sms' => ['id' => 'cliente'],
        'clientes/new_message' => ['id' => 'cliente'],
        'clientes/nuevo_mensaje_cliente' => ['id' => 'cliente'],
        'clientes/modal_new_nota' => ['id' => 'cliente'],
        'clientes/modal_nuevo_servicio' => ['id' => 'cliente'],
        'clientes/popover' => ['id' => 'cliente'],
        'clientes_facturacion/home' => ['id' => 'cliente'],
        'clientes_facturacion/configuracion' => ['id' => 'cliente'],
        'clientes_facturacion/facturas' => ['id' => 'cliente'],
        'clientes_facturacion/saldos' => ['id' => 'cliente'],
        'clientes_facturacion/transacciones' => ['id' => 'cliente'],
        'clientes_facturacion/modal_new_factura_libre' => ['id' => 'cliente'],
        'clientes_facturacion/modal_new_factura_servicio' => ['id' => 'cliente'],
        'finanzas/addpago' => ['id' => 'cliente'],
        'cotizaciones/get_data_mail' => ['id' => 'cliente'],
        'cotizaciones/documentos' => ['id' => 'cliente'],
        'cotizaciones/notas' => ['id' => 'cliente'],
        'cotizaciones/get_email_json' => ['id' => 'cliente'],
        'cotizaciones/get_historial_json' => ['id' => 'cliente'],
        'cotizaciones/get_sms_json' => ['id' => 'cliente'],
        'cotizaciones/save_data_clientes' => ['id' => 'cliente'],
        'cotizaciones/update_config_client' => ['id' => 'cliente'],
        'cotizaciones/retirar_activar' => ['id' => 'cliente'],
        // Facturas
        'finanzas/factura_pdf' => ['fc' => 'factura'],
        'finanzas/transacciones' => ['id' => 'factura'],
        'finanzas/viewuser' => ['id' => 'factura'],
        'clientes_facturacion/ver_factura' => ['id' => 'factura'],
        // Tickets (soporte)
        'ticket/view_ticket' => ['id' => 'ticket'],
        'ticket/popover' => ['id' => 'ticket'],
        'ticket/delete_ticket' => ['id' => 'ticket'],
        // Instalaciones
        'clientes/add_materials' => ['id' => 'instalacion'],
        'clientes/instalaciones_a' => ['id' => 'instalacion'],
        'clientes/instalaciones_p' => ['id' => 'instalacion'],
        'clientes/to_print' => ['id' => 'instalacion'],
        'clientes/edit_instalacion' => ['id' => 'instalacion'],
        'clientes/update_install' => ['id' => 'instalacion'],
        'clientes/update_preinstall' => ['id' => 'instalacion'],
        'clientes/update_pre_install' => ['id' => 'instalacion'],
        'clientes/save_update_install' => ['id' => 'instalacion'],
        'clientes/nuevo_cliente_pre' => ['id' => 'instalacion'],
        'clientes/delete_instalacion' => ['id' => 'instalacion'],
        'clientes/delete_install' => ['id' => 'instalacion'],
        // Cotizaciones
        'cotizaciones/acepcot' => ['id' => 'cotizacion'],
        'cotizaciones/deletecot' => ['id' => 'cotizacion'],
        'cotizaciones/resendmail' => ['id' => 'cotizacion'],
        // Servicios
        'herramientas/rebootmodem' => ['id' => 'servicio'],
        'herramientas/rebootmodemestatus' => ['id' => 'servicio'],
        'herramientas/viewmodem' => ['id' => 'servicio'],
        'herramientas_mikrotik/modal_ping' => ['id' => 'servicio'],
        // ONUs (sucursal del cliente al que están asignadas)
        'fiber/info_onu' => ['id' => 'onu'],
        'fiber/reboot_onu' => ['id' => 'onu'],
        'fiber/edit_only_system' => ['id' => 'onu'],
        'fiber/delete_onu' => ['id' => 'onu'],
        'fiber/update_onu_sistema' => ['id' => 'onu'],
        'fiber/get_onu_estado' => ['id' => 'onu'],
        // Routers
        'gestion_red/get_addresslist' => ['id' => 'router'],
        'gestion_red/get_leases' => ['id' => 'router'],
        'gestion_red/save_server_general' => ['id' => 'router'],
        'gestion_red/editar_server' => ['id' => 'router'],
    ];

    public function before(RequestInterface $request, $arguments = null)
    {
        if (is_cli() || !$request instanceof IncomingRequest || empty(session('id_usuario8291')) || FiltroSucursal::todas()) {
            return;
        }

        $reglas = array_fill_keys(self::PARAMETROS_CLIENTE, 'cliente') + (self::POR_RUTA[$this->ruta($request)] ?? []);
        foreach ($reglas as $parametro => $tipo) {
            $valor = $request->getGet($parametro) ?? $request->getPost($parametro);
            if (!is_scalar($valor) || !ctype_digit(trim((string) $valor))) {
                continue; // vacío, "TODOS", arreglos...: lo resuelve el filtro del listado
            }
            $sucursal = $this->sucursalDe($tipo, (int) $valor);
            if ($sucursal !== null && !$this->permitida($sucursal)) {
                log_message('warning', sprintf('Acceso bloqueado por sucursal: usuario %s, %s %s=%s (%s)',
                    session('id_usuario8291'), $this->ruta($request), $parametro, $valor, $tipo));

                return $this->denegar($request);
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }

    /**
     * Sucursal del registro (para routers, lista de sucursales), o null si no existe.
     *
     * @return int|list<int>|null
     */
    private function sucursalDe(string $tipo, int $id): int|array|null
    {
        $db = db_connect();
        $fila = match ($tipo) {
            'cliente'     => $db->table('usuarios2')->select('sucursal')->where('id', $id)->get()->getRow(),
            'factura'     => $db->table('facturas f')->select('u.sucursal')->join('usuarios2 u', 'u.id = f.idcliente')->where('f.id', $id)->get()->getRow(),
            'ticket'      => $db->table('soporte s')->select('u.sucursal')->join('usuarios2 u', 'u.id = s.idcliente')->where('s.id', $id)->get()->getRow(),
            'instalacion' => $db->table('instalaciones')->select('sucursal')->where('id', $id)->get()->getRow(),
            'cotizacion'  => $db->table('cotizaciones')->select('sucursal')->where('id', $id)->get()->getRow(),
            'servicio'    => $db->table('tblservicios')->select('sucursal')->where('id', $id)->get()->getRow(),
            'onu'         => $db->table('olt_onus o')->select('u.sucursal')->join('usuarios2 u', 'u.id = o.idcliente')->where('o.id', $id)->get()->getRow(),
            'router'      => null,
            default       => null,
        };
        if ($tipo === 'router') {
            if (!$db->table('server')->where('id', $id)->countAllResults()) {
                return null;
            }

            return array_map('intval', array_column($db->table('tblsucursalesnodo')->select('idsucursal')->where('idnodo', $id)->get()->getResultArray(), 'idsucursal'));
        }

        return $fila ? (int) $fila->sucursal : null;
    }

    /** @param int|list<int> $sucursal */
    private function permitida(int|array $sucursal): bool
    {
        if (is_array($sucursal)) {
            foreach ($sucursal as $id) {
                if (FiltroSucursal::permite($id)) {
                    return true;
                }
            }

            return false; // router sin sucursal asignada: solo lo ven quienes tienen todas
        }

        return FiltroSucursal::permite($sucursal);
    }

    private function denegar(IncomingRequest $request): ResponseInterface
    {
        $respuesta = Services::response();
        if (str_contains(strtolower($request->getHeaderLine('Accept')), 'application/json')) {
            return $respuesta->setJSON(['data' => [], 'error' => 'Registro de otra sucursal']);
        }
        if (strtoupper($request->getMethod()) !== 'GET') {
            return $respuesta->setBody('<p>ACCESO DENEGADO.</p>');
        }

        return $respuesta->setBody(view('errors/acceso_denegado', [
            'permiso' => '',
            'motivo'  => 'Este registro pertenece a una sucursal que no tienes asignada.',
            'pagina'  => !$request->isAJAX(),
        ]));
    }

    /** "controlador/metodo" en minúsculas, sin el subdirectorio de instalación ni index.php. */
    private function ruta(IncomingRequest $request): string
    {
        $segmentos = array_values(array_filter(explode('/', strtolower(trim($request->getPath(), '/'))), 'strlen'));
        if (($segmentos[0] ?? '') === 'index.php') {
            array_shift($segmentos);
        }

        return ($segmentos[0] ?? '') . '/' . ($segmentos[1] ?? 'index');
    }
}
