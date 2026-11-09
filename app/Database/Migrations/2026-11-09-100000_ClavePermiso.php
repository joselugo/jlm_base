<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Clave de cada permiso en la propia tabla permiso.
 *
 * El código pide permisos por clave (Acceso("ZONAS")) y antes la traducción clave -> id
 * estaba escrita a mano en Class_seguridad::clave_permiso(); cada permiso nuevo
 * obligaba a tocar código. Ahora la clave vive en permiso.clave y basta con
 * escribirla en la fila del permiso.
 *
 * Las claves se copian del switch que existía; solo se asignan a ids que existan.
 */
class ClavePermiso extends Migration
{
    /** Clave => id_permiso, tal como estaba en Class_seguridad::clave_permiso(). */
    private const CLAVES = [
        'HOME' => 2,
        'AJUSTES' => 95,
        'TICKET' => 96,
        'CLOSE_TICKET' => 98,
        'UNANSWERED_TICKET' => 97,
        'ANSWERED_TICKET' => 99,
        'CLOSED_TICKET' => 100,
        'NUEVO_SOCIO' => 14,
        'ACEPTAR_INSTALACION' => 108,
        'LISTA_CLIENTES' => 8,
        'DATOS_CLIENTES_PRIVADOS' => 116,
        'AGENDA' => 81,
        'CALENDARIO_EDITAR_ELIMINAR_ESPECIAL' => 79,
        'LOG_CALENDARIO' => 82,
        'FORMATOS' => 72,
        'GESTION_PERSONAL' => 6,
        'INGRESOS' => 68,
        'INGRESOS_EGRESOS_VIEW_ESPECIAL' => 78,
        'INGRESOS_EGRESOS_ELIMINAR_ESPECIAL' => 80,
        'BALANCE_GENERAL' => 74,
        'LISTA_COTIZACIONES' => 71,
        'DOCUMENTOS_VIEW_ESPECIAL' => 89,
        'APROBAR_DOCUMENTOS' => 90,
        'INSTALACIONES' => 91,
        'LISTA_REPORTES' => 22,
        'SMS_ENVIADOS' => 110,
        'VINCULAR_CONCESIONARIO' => 24,
        'CREATE_BACKUP' => 85,
        'PRODUCTOS' => 86,
        'PROVEEDORES' => 87,
        'TIPOS_DE_PRODUCTOS' => 88,
        'FINANZAS' => 92,
        'FACTURAS' => 93,
        'REGISTRAR_PAGO' => 94,
        'MAPA_CLIENTES' => 9,
        'RUTAS' => 101,
        'CLIENTE_CONFIGURACION' => 102,
        'CREAR_Y_ELIMINAR_FACTURAS' => 103,
        'CREAR_Y_ELIMINAR_SALDOS' => 104,
        'EDITOR_PLANTILLAS' => 105,
        'ESTADISTICAS' => 106,
        'TRANSACCIONES' => 107,
        'SUCURSAL' => 111,
        'ESTADISTICAS_CLIENTE' => 112,
        'MONITOREO' => 115,
        'ADDRESSLIST' => 138,
        'SERVER' => 117,
        'INTERNET' => 119,
        'VOZ' => 120,
        'PERSONALIZADO' => 121,
        'FIBER_DASHBOARD' => 123,
        'OLT' => 124,
        'ONU' => 125,
        'AUTORIZE_ONU' => 139,
        'LOG' => 128,
        'WIDGET CLIENTES_ONLINE' => 126,
        'WIDGET TRANSACCIONES' => 127,
        'WIDGET FACTURAS NO PAGADAS' => 129,
        'WIDGET TICKETS SOPORTE' => 130,
        'WIDGET TRAFICO DE CLIENTES' => 131,
        'WIDGET RESUMEN DEL SISTEMA' => 132,
        'WIDGET ULTIMOS PAGOS' => 133,
        'WIDGET ULTIMOS CLIENTES CONECTADOS' => 134,
        'WIDGET DATOS DEL SERVIDOR' => 135,
        'WIDGET RESUMEN DE FACTURACION' => 136,
        'WIDGET EMISORES' => 137,
        'REDES_IPV4' => 141,
        'SYSTEM' => 142,
        'GENERAL' => 143,
        'CONFIG_FACTURACION' => 144,
        'PASARELAS_PAGO' => 145,
        'TICKETS' => 146,
        'CAMBIOS_MASIVOS' => 147,
        'ZONAS' => 148,
        'SMS' => 149,
        'GOOGLE' => 150,
        'CLOUD' => 151,
        'STREAMING' => 140,
        'AJUSTES_ALMACEN' => 152,
        'EMAIL_SMS_CLIENTE' => 153,
        'GEOCERCAS' => 155,
        'LISTA_RAZON_SOCIAL' => 73,
        'GAME_PREGUNTAS' => 157,
        'GAME_PREGUNTAS_RAPIDAS' => 158,
    ];

    public function up()
    {
        if (! $this->db->fieldExists("clave", "permiso")) {
            $this->forge->addColumn("permiso", [
                "clave" => ["type" => "VARCHAR", "constraint" => 60, "null" => true, "after" => "id_permiso"],
            ]);
            $this->db->query("ALTER TABLE permiso ADD UNIQUE INDEX permiso_clave (clave)");
        }
        foreach (self::CLAVES as $clave => $id) {
            $this->db->table("permiso")->where("id_permiso", $id)->where("clave IS NULL")->update(["clave" => $clave]);
        }
    }

    public function down()
    {
        $this->db->query("ALTER TABLE permiso DROP INDEX permiso_clave");
        $this->forge->dropColumn("permiso", "clave");
    }
}
