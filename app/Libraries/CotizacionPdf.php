<?php

namespace App\Libraries;

/**
 * Arma el PDF de una cotización a partir de las plantillas editables
 * (Ajustes > Editor de plantillas), por sucursal:
 *
 *   cotizacion                  página 1: datos del cliente, productos y totales
 *   cotizacion_bco  página 2: datos para el pago
 *   cotizacion_pie              pie de página de ambas páginas
 *   cotizacion_mail           cuerpo del correo con el que se envía (no va en el PDF)
 *
 * Los datos de la empresa y el logo ({url}) salen de la sucursal de la cotización,
 * igual que en facturas y recibos.
 */
class CotizacionPdf
{
    public const PLANTILLAS = ['cotizacion', 'cotizacion_bco', 'cotizacion_pie'];

    private const MESES = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto',
        'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    private const DIAS = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

    /**
     * Plantilla de la sucursal; si la sucursal no tiene, la de cualquier otra.
     */
    public static function plantilla(string $tipo, int $idsucursal): ?object
    {
        $fila = db_connect()->table('formatos')
            ->where('tipoaviso', $tipo)
            ->orderBy('idsucursal = ' . $idsucursal, 'DESC', false)
            ->orderBy('id', 'ASC')
            ->get(1)->getRow();

        return $fila ?: null;
    }

    /**
     * @param object       $cot   fila de cotizaciones
     * @param list<object> $items filas de cotizaciones_items
     */
    public static function crear(object $cot, array $items): \Mpdf\Mpdf
    {
        $idsucursal = (int) ($cot->sucursal ?? 0);
        $valores    = self::valores($cot, $items, $idsucursal);

        $paginas = [];
        foreach (self::PLANTILLAS as $tipo) {
            $p = self::plantilla($tipo, $idsucursal);
            $paginas[$tipo] = $p ? self::rellenar((string) $p->html, $valores, $cot) : '';
        }
        $principal = self::plantilla('cotizacion', $idsucursal);

        // Un @page dentro de la plantilla hace que mPDF reinicie el pie en cada página nueva.
        // Sus márgenes se pasan a la configuración y la regla se quita del HTML.
        $margenes = self::margenesPagina($paginas['cotizacion']);
        foreach ($paginas as $tipo => $html) {
            $paginas[$tipo] = preg_replace('~@page\s*\{[^}]*\}~i', '', $html);
        }

        $mpdf = new \Mpdf\Mpdf([
            'format'        => self::formato($principal),
            'margin_top'    => $margenes['top'] ?? 5,
            'margin_left'   => $margenes['left'] ?? 10,
            'margin_right'  => $margenes['right'] ?? 8,
            'margin_bottom' => max($margenes['bottom'] ?? 0, 22), // espacio para el pie
            'margin_footer' => 4,
        ]);
        $mpdf->SetTitle('Cotización ' . $cot->id);
        $mpdf->SetHTMLFooter($paginas['cotizacion_pie']);
        $mpdf->WriteHTML($paginas['cotizacion']);
        if (trim(strip_tags($paginas['cotizacion_bco'])) !== '') {
            $mpdf->AddPage();
            $mpdf->WriteHTML($paginas['cotizacion_bco']);
        }
        $mpdf->SetTitle('Cotización ' . $cot->id); // el <title> de cada plantilla lo sobrescribe

        return $mpdf;
    }

    /**
     * Márgenes (en mm) del primer @page de la plantilla: margin-top: 20px, etc.
     *
     * @return array<string, float>
     */
    private static function margenesPagina(string $html): array
    {
        if (!preg_match('~@page\s*\{([^}]*)\}~i', $html, $m)) {
            return [];
        }
        $aMm = ['mm' => 1, 'cm' => 10, 'in' => 25.4, 'pt' => 0.3528, 'px' => 0.2646];
        $margenes = [];
        preg_match_all('~margin-(top|left|right|bottom)\s*:\s*([\d.]+)\s*(mm|cm|in|pt|px)?~i', $m[1], $reglas, PREG_SET_ORDER);
        foreach ($reglas as [, $lado, $valor, $unidad]) {
            $margenes[strtolower($lado)] = (float) $valor * $aMm[strtolower($unidad ?: 'px')];
        }

        return $margenes;
    }

    /**
     * Cuerpo del correo de envío (plantilla cotizacion_mail).
     */
    public static function correo(object $cot, array $items): string
    {
        $idsucursal = (int) ($cot->sucursal ?? 0);
        $plantilla  = self::plantilla('cotizacion_mail', $idsucursal);
        $html       = $plantilla ? (string) $plantilla->html
            : '<p>Hola {nombre_cliente}, adjuntamos la cotización {folio} por {total}, vigente hasta {vigencia}.</p>';

        return self::rellenar($html, self::valores($cot, $items, $idsucursal), $cot);
    }

    /** Rellena un texto corto (p. ej. el asunto del correo) con los campos de la cotización. */
    public static function rellenarTexto(string $texto, object $cot, array $items): string
    {
        return self::rellenar($texto, self::valores($cot, $items, (int) ($cot->sucursal ?? 0)), $cot);
    }

    /**
     * Cotización ficticia para la vista previa del editor de plantillas.
     *
     * @return array{0: object, 1: list<object>}
     */
    public static function ejemplo(int $idsucursal): array
    {
        $cot = (object) [
            'id' => '000123', 'nombres' => 'Cliente de Ejemplo', 'telefono' => '555 000 0000',
            'correo' => 'cliente@ejemplo.com', 'fecha_cot' => date('Y-m-d'), 'fecha_ven' => date('Y-m-d', strtotime('+15 days')),
            'subtotal' => 1500, 'descuento' => 0, 'iva' => 240, 'total' => 1740, 'impuesto' => 'SI',
            'notas' => "Instalación incluida.\nPrecios sujetos a cambio sin previo aviso.", 'sucursal' => $idsucursal,
        ];
        $items = [
            (object) ['idproducto' => 10, 'producto' => 'Router inalámbrico', 'descripcion' => 'Doble banda', 'cantidad' => 1, 'unidad' => 'pieza', 'subtotal' => 900],
            (object) ['idproducto' => 0, 'producto' => 'Instalación', 'descripcion' => 'Servicio en sitio', 'cantidad' => 2, 'unidad' => 'servicio', 'subtotal' => 600],
        ];

        return [$cot, $items];
    }

    /**
     * @return array<string, string>
     */
    private static function valores(object $cot, array $items, int $idsucursal): array
    {
        $sucursal = self::sucursal($idsucursal);
        $fecha = strtotime((string) $cot->fecha_cot) ?: time();
        $dinero = static fn ($v): string => '$ ' . number_format((float) $v, 2);

        $filas = '';
        foreach ($items as $n => $item) {
            $cantidad = (float) $item->cantidad ?: 1;
            $filas .= '<tr>'
                . '<td>' . ($n + 1) . '</td>'
                . '<td>' . esc($item->idproducto) . '</td>'
                . '<td style="text-align:left">' . esc($item->producto) . '</td>'
                . '<td style="text-align:left">' . esc($item->descripcion) . '</td>'
                . '<td style="text-align:left">' . number_format((float) $item->subtotal / $cantidad, 2) . '</td>'
                . '<td>' . esc($item->cantidad) . '</td>'
                . '<td>' . esc($item->unidad) . '</td>'
                . '<td>' . number_format((float) $item->subtotal, 2) . '</td>'
                . '</tr>';
        }

        return [
            '{url}'               => (string) ($sucursal->logo_facturas_recibos ?? ''),
            '{nombre_empresa}'    => esc($sucursal->alias ?? ''),
            '{ruc_empresa}'       => esc($sucursal->rfc ?? ''),
            '{direccion_empresa}' => esc($sucursal->direccion ?? ''),
            '{telefono_empresa}'  => esc($sucursal->telefono ?? ''),
            '{folio}'             => esc($cot->id),
            '{dia}'               => date('d', $fecha),
            '{mes}'               => date('m', $fecha),
            '{anio}'              => date('Y', $fecha),
            '{fecha_cotizacion}'  => date('d/m/Y', $fecha),
            '{vigencia}'          => self::fechaLarga((string) $cot->fecha_ven),
            '{nombre_cliente}'    => esc($cot->nombres),
            '{telefono_cliente}'  => esc($cot->telefono),
            '{correo_cliente}'    => esc($cot->correo),
            '{items}'             => $filas,
            '{subtotal}'          => $dinero($cot->subtotal),
            '{descuento}'         => $dinero($cot->descuento),
            '{iva}'               => $dinero($cot->iva),
            '{impuesto_incluido}' => ($cot->impuesto ?? '') === 'SI' ? '' : 'INCLUIDO',
            '{total}'             => $dinero($cot->total),
            '{notas}'             => nl2br(esc((string) $cot->notas)),
            '{fecha_impresion}'   => date('d/m/Y g:i a'),
        ];
    }

    /**
     * @param array<string, string> $valores
     */
    private static function rellenar(string $html, array $valores, object $cot): string
    {
        $html = html_entity_decode($html);
        // Campos automáticos {sucursal_<columna>}, {cotizacion_<columna>}, {config_<ajuste>}; los fijos tienen prioridad.
        $valores += CamposPlantilla::valores($html, self::sucursal((int) ($cot->sucursal ?? 0)), ['cotizacion' => $cot]);
        // La fila que contiene {items} se reemplaza completa por las filas de productos.
        $html = preg_replace('~<tr[^>]*>\s*<td[^>]*>\s*\{items\}\s*</td>\s*</tr>~i', '{items}', $html);

        return strtr($html, $valores);
    }

    /** Sucursal del documento (o la primera, si no tiene). */
    private static function sucursal(int $id): ?object
    {
        static $cache = [];

        return $cache[$id] ??= (db_connect()->table('sucursal')->where('id', $id)->get()->getRow()
            ?? db_connect()->table('sucursal')->orderBy('id')->get(1)->getRow());
    }

    /**
     * @return string|array{int, int}
     */
    private static function formato(?object $p): string|array
    {
        if ($p && $p->tamano != '' && !is_numeric($p->tamano)) {
            return $p->tamano . $p->posicion;
        }
        if ($p && intval($p->ancho) > 0 && intval($p->alto) > 0) {
            return [intval($p->ancho), intval($p->alto)];
        }

        return 'LETTER';
    }

    private static function fechaLarga(string $fecha): string
    {
        $t = strtotime(substr($fecha, 0, 10));
        if (!$t) {
            return $fecha;
        }

        return self::DIAS[(int) date('w', $t)] . ' ' . date('d', $t) . ' de '
            . self::MESES[(int) date('n', $t) - 1] . ' de ' . date('Y', $t);
    }
}
