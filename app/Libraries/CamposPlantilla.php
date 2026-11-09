<?php

namespace App\Libraries;

/**
 * Campos automáticos de las plantillas (ver app/Config/Plantillas.php):
 *
 *   {<ajuste>} o {config_<ajuste>}  cualquier ajuste de tblconfiguration (columna setting)
 *   {sucursal_<columna>}            cualquier columna de la sucursal del documento
 *   {<prefijo>_<columna>}           columnas de las filas que pase cada documento (p. ej. cotizacion)
 *
 * Los campos fijos de cada documento ({nombre_empresa}, {total}...) tienen prioridad.
 * Solo se resuelven los campos que aparecen en el HTML; los que no existen se dejan
 * tal cual para que se note en el documento.
 */
final class CamposPlantilla
{
    /**
     * @param object|null           $sucursal fila de la tabla sucursal
     * @param array<string, object> $filas    prefijo => fila, p. ej. ['cotizacion' => $cot]
     *
     * @return array<string, string> '{campo}' => valor, listo para strtr()
     */
    public static function valores(string $html, ?object $sucursal, array $filas = []): array
    {
        if (!preg_match_all('~\{([A-Za-z0-9_]+)\}~', $html, $m)) {
            return [];
        }
        $filas = ['sucursal' => $sucursal] + $filas;

        $valores = [];
        $ajustes = []; // '{campo}' => nombre del ajuste
        foreach (array_unique($m[1]) as $nombre) {
            $campo = '{' . $nombre . '}';
            // {prefijo_columna} de una fila (sucursal, cotizacion...): datos de clientes, se escapan
            foreach ($filas as $prefijo => $fila) {
                $columna = substr($nombre, strlen($prefijo) + 1);
                if (is_object($fila) && str_starts_with($nombre, $prefijo . '_') && property_exists($fila, $columna)
                    && is_scalar($fila->{$columna} ?? '')) {
                    $valores[$campo] = esc((string) ($fila->{$columna} ?? ''));
                    continue 2;
                }
            }
            $ajustes[$campo] = str_starts_with($nombre, 'config_') ? substr($nombre, 7) : $nombre;
        }

        $ocultos = config('Plantillas')->ajustesOcultos;
        $buscar  = array_values(array_diff(array_unique($ajustes), $ocultos));
        if ($buscar !== []) {
            $config = db_connect()->table('tblconfiguration')->select('setting, value')
                ->whereIn('setting', $buscar)->get()->getResult();
            $porNombre = array_column($config, 'value', 'setting');
            foreach ($ajustes as $campo => $nombre) {
                if (array_key_exists($nombre, $porNombre)) {
                    // Los ajustes los define el administrador y pueden traer HTML (p. ej. una firma).
                    $valores[$campo] = html_entity_decode((string) $porNombre[$nombre]);
                }
            }
        }

        return $valores;
    }
}
