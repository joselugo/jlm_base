<?php

namespace App\Libraries;

use CodeIgniter\Database\BaseBuilder;

/**
 * Sucursales que puede ver el usuario en sesión ($_SESSION['sucursales']).
 *
 *   "%%"   todas las sucursales
 *   "1,3"  solo esas
 *   ""     ninguna (no ve datos de sucursal)
 *
 * Toda consulta de datos que pertenecen a una sucursal (clientes, servicios, facturas,
 * pagos, tickets, instalaciones, cotizaciones, eventos, gastos, routers...) debe pasar
 * por aquí. Relación de cada dato con su sucursal:
 *   clientes          usuarios2.sucursal
 *   servicios         tblservicios.sucursal
 *   facturas / pagos  usuarios2.sucursal del cliente (facturas.idcliente, operaciones.idcliente)
 *   tickets           usuarios2.sucursal del cliente (soporte.idcliente)
 *   routers / nodos   tblsucursalesnodo (idnodo -> idsucursal)
 *   instalaciones, cotizaciones, calendar, almacen: columna sucursal; gastos: idsucursal
 */
final class FiltroSucursal
{
    /**
     * @return list<int>|null null = todas las sucursales
     */
    public static function ids(?string $valor = null): ?array
    {
        $valor = trim((string) ($valor ?? ($_SESSION['sucursales'] ?? '')));
        if ($valor === '%%') {
            return null;
        }
        $ids = array_values(array_unique(array_filter(array_map('intval', explode(',', $valor)), static fn (int $id) => $id > 0)));

        return $ids;
    }

    public static function todas(): bool
    {
        return self::ids() === null;
    }

    /**
     * Condición SQL para una columna: "1=1" (todas), "col IN (1,3)" o "1=0" (ninguna).
     * Los ids son enteros, no hay datos del usuario en el SQL.
     */
    public static function condicion(?string $valor, string $columna): string
    {
        $ids = self::ids($valor);
        if ($ids === null) {
            return '1=1';
        }

        return $ids === [] ? '1=0' : $columna . ' IN (' . implode(',', $ids) . ')';
    }

    /** Igual que condicion() con las sucursales de la sesión. */
    public static function sql(string $columna): string
    {
        return self::condicion(null, $columna);
    }

    /** Aplica el filtro de la sesión a un query builder. */
    public static function aplicar(BaseBuilder $builder, string $columna): BaseBuilder
    {
        $ids = self::ids();
        if ($ids === null) {
            return $builder;
        }

        return $ids === [] ? $builder->where('1=0', null, false) : $builder->whereIn($columna, $ids);
    }

    /** ¿El usuario puede ver datos de esta sucursal? */
    public static function permite(int|string|null $idsucursal): bool
    {
        $ids = self::ids();

        return $ids === null || in_array((int) $idsucursal, $ids, true);
    }

    /** ¿El usuario puede ver a este cliente (según usuarios2.sucursal)? */
    public static function permiteCliente(int|string|null $idcliente): bool
    {
        if (self::todas()) {
            return true;
        }
        $fila = db_connect()->table('usuarios2')->select('sucursal')->where('id', (int) $idcliente)->get()->getRow();

        return $fila !== null && self::permite($fila->sucursal);
    }

    /** ¿El usuario puede ver este router / nodo (según tblsucursalesnodo)? */
    public static function permiteNodo(int|string|null $idnodo): bool
    {
        if (self::todas()) {
            return true;
        }
        $ids = self::ids();
        if ($ids === []) {
            return false;
        }

        return db_connect()->table('tblsucursalesnodo')->where('idnodo', (int) $idnodo)->whereIn('idsucursal', $ids)->countAllResults() > 0;
    }
}
