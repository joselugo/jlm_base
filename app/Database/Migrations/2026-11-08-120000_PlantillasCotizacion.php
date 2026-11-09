<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * La cotización pasa al Editor de plantillas (tabla formatos), una copia por sucursal.
 * El HTML es el de las vistas app/Views/cotizaciones/pdf_*.php que se usaban antes (eliminadas),
 * con los datos fijos de la empresa cambiados por campos de la sucursal.
 * No toca plantillas que ya existan (se puede correr de nuevo sin duplicar).
 */
class PlantillasCotizacion extends Migration
{
    private const TIPOS = ['cotizacion', 'cotizacion_bco', 'cotizacion_pie', 'cotizacion_mail'];

    public function up()
    {
        $plantillas = [
            'cotizacion'                 => [1, self::PAGINA_COTIZACION, 'LETTER', 'Cotización {folio}'],
            'cotizacion_bco' => [1, self::PAGINA_BANCOS, 'LETTER', ''],
            'cotizacion_pie'             => [1, self::PIE, 'LETTER', ''],
            'cotizacion_mail'          => [2, self::CORREO, '', 'Cotización {folio} - {nombre_empresa}'],
        ];

        foreach ($this->db->table('sucursal')->select('id')->get()->getResult() as $sucursal) {
            foreach ($plantillas as $tipo => [$tipoformato, $html, $tamano, $asunto]) {
                $existe = $this->db->table('formatos')
                    ->where(['tipoaviso' => $tipo, 'idsucursal' => $sucursal->id])->countAllResults();
                if ($existe) {
                    continue;
                }
                $this->db->table('formatos')->insert([
                    'html'          => $html,
                    'tipoaviso'     => $tipo,
                    'asuntofactura' => $asunto,
                    'tamano'        => $tamano,
                    'ancho'         => '',
                    'alto'          => '',
                    'posicion'      => '',
                    'idsucursal'    => $sucursal->id,
                    'idtipoformato' => $tipoformato,
                ]);
            }
        }
    }

    public function down()
    {
        $this->db->table('formatos')->whereIn('tipoaviso', self::TIPOS)->delete();
    }

    private const ESTILOS = <<<'HTML'
<style type="text/css">
@page { margin-top: 20px; margin-left: 40px; margin-right: 30px; }
body, table { font-family: Arial; font-size: 12px; color: #32373a; }
#table-fecha { border-spacing: 0; }
#table-fecha thead tr th { padding: 2px; background-color: #dddddd; text-align: center; }
#carrito { border: 1px solid #32373a; border-collapse: collapse; }
#carrito thead tr th { padding: 3px; text-align: center; background: #2196f3; border: 1px solid #32373a; color: #ffffff; }
#carrito tbody tr td { padding: 5px; text-align: center; border: 1px solid #32373a; }
.condiciones { border: 1px solid #32373a; border-collapse: collapse; }
.condiciones td { border: 1px solid #32373a; }
.titulo-azul { background: #2196f3; text-align: center; color: #ffffff; font-size: 14px; }
</style>
HTML;

    private const ENCABEZADO = <<<'HTML'
<table cellpadding="0" cellspacing="0" style="width:100%;">
<tbody>
<tr>
<td style="width:22%; vertical-align:middle;"><img alt="" src="{url}" style="width: 120px; height: auto;" /></td>
<td style="width:52%; text-align:center; vertical-align:top;">
<p style="font-size:16px; font-family:verdana,geneva,sans-serif; margin:0 0 6px 0;">COTIZACIÓN DE PRODUCTOS Y SERVICIOS</p>
<div style="font-size:14px;">{nombre_empresa}</div>
<div style="font-size:14px;">{direccion_empresa}</div>
<div style="font-size:14px;">TEL: {telefono_empresa}</div>
</td>
<td style="width:26%; text-align:right; vertical-align:top;">Folio cotización: <strong style="color: red">{folio}</strong>
<table cellpadding="0" cellspacing="0" id="table-fecha" style="width:160px; margin-top:4px;">
<thead><tr><th>DIA</th><th>MES</th><th>AÑO</th></tr></thead>
<tbody><tr><td style="text-align:center">{dia}</td><td style="text-align:center">{mes}</td><td style="text-align:center">{anio}</td></tr></tbody>
</table>
</td>
</tr>
</tbody>
</table>
<hr />
HTML;

    private const PAGINA_COTIZACION = '<html><head><title>Cotización</title>' . self::ESTILOS . '</head><body>'
        . self::ENCABEZADO . <<<'HTML'
<p style="font-size:12px; font-family:verdana,geneva,sans-serif;">Nombre: {nombre_cliente}</p>
<div style="font-size:11px;">Tel: {telefono_cliente}</div>
<div style="font-size:11px;">Email: {correo_cliente}</div>
<p>Por medio de la presente y de acuerdo con su solicitud, nos permitimos poner a su consideración la siguiente propuesta de los productos/servicios que {nombre_empresa} le ofrece.</p>
<p>Vigencia de la cotización: <strong>{vigencia}</strong></p>
<table cellpadding="0" cellspacing="0" id="carrito" style="width:100%; font-size:11px">
<thead><tr><th>N°</th><th>Folio</th><th>Producto/Servicio</th><th>Descripción</th><th>Costo P/U</th><th>Cantidad</th><th>Unidad</th><th>Costo</th></tr></thead>
<tbody>
<tr><td>{items}</td></tr>
<tr><td colspan="7" style="text-align:right"><strong>SUBTOTAL</strong></td><td><strong>{subtotal}</strong></td></tr>
<tr><td colspan="7" style="text-align:right"><strong>DESCUENTO</strong></td><td><strong>{descuento}</strong></td></tr>
<tr><td colspan="7" style="text-align:right"><strong>IVA {impuesto_incluido}</strong></td><td><strong>{iva}</strong></td></tr>
<tr><td colspan="7" style="text-align:right"><strong>TOTAL</strong></td><td><strong>{total}</strong></td></tr>
</tbody>
</table>
<br />
<table cellpadding="0" cellspacing="0" class="condiciones" style="width:100%;">
<tbody>
<tr><td class="titulo-azul">Notas</td></tr>
<tr><td style="padding:5px;">{notas}</td></tr>
</tbody>
</table>
<br />
<table cellpadding="0" cellspacing="0" class="condiciones" style="width:100%;">
<tbody>
<tr><td class="titulo-azul">Condiciones Generales</td></tr>
<tr><td>
<ul style="font-family:courier new,courier,monospace;">
<li><strong>Precios expresados en Moneda Nacional</strong></li>
<li><strong>Condiciones de Pago: contado anticipado.</strong></li>
<li><strong>Tiempo de entrega: 5 a 7 días hábiles para productos y de 5 a 10 días hábiles para servicios</strong></li>
</ul>
</td></tr>
</tbody>
</table>
</body></html>
HTML;

    private const PAGINA_BANCOS = '<html><head><title>Datos bancarios</title>' . self::ESTILOS . '</head><body>'
        . self::ENCABEZADO . <<<'HTML'
<br />
<table cellpadding="0" cellspacing="0" class="condiciones" style="width:100%;">
<tbody>
<tr><td class="titulo-azul">Pago depósito o transferencia a nombre de:</td></tr>
<tr><td style="text-align:center; font-size:14px">
<ul style="font-family:courier new,courier,monospace;">
<li><strong>NETIUM TELECOM DE YUCATÁN S DE RL DE CV</strong></li>
<li><strong>C. 31 #107G CP: 97390</strong></li>
<li><strong>UMÁN, YUCATÁN</strong></li>
<li><strong>RFC: NTY200618KA9</strong></li>
</ul>
</td></tr>
</tbody>
</table>
<br />
<table cellpadding="0" cellspacing="0" class="condiciones" style="width:100%;">
<tbody>
<tr><td class="titulo-azul">Datos Bancarios</td></tr>
<tr><td style="text-align:center; font-size:14px">
<ul style="font-family:courier new,courier,monospace;">
<li><strong>CUENTA: 0117341318</strong></li>
<li><strong>CUENTA CLABE: 012910001173413180</strong></li>
<li><strong>BANCOMER</strong></li>
</ul>
</td></tr>
</tbody>
</table>
<table cellpadding="0" cellspacing="0" class="condiciones" style="width:100%;">
<tbody>
<tr><td class="titulo-azul">Favor de enviar los datos de pago y confirmación de compra vía correo electrónico a:</td></tr>
<tr><td style="text-align:center; font-size:14px; padding:8px"><a href="mailto:facturas@moloch.mx?subject=Comprobante">facturas@moloch.mx</a></td></tr>
</tbody>
</table>
<p>Sin más por el momento y agradeciendo de antemano su atención a la presente, nos es grato repetirnos a sus órdenes para cualquier información adicional.</p>
<div style="margin-top: 20px">
<p align="center"><span style="font-size:16px; font-family:verdana,geneva,sans-serif;">ATENTAMENTE</span></p>
<div align="center" style="padding-top: 15px; font-size:14px;">Diego Manuel Paredes Montero</div>
<div align="center" style="font-size:14px;">Ejecutivo de ventas</div>
<div align="center" style="font-size:14px;">Tel: 9992 72 99 96</div>
</div>
<br />
<table cellpadding="0" cellspacing="0" class="condiciones" style="width:100%;">
<tbody>
<tr><td style="padding:3px; font-size:9px">La información contenida está dirigida solamente a la persona o entidad a quien está orientada y puede contener material confidencial y/o privilegiado. Cualquier revisión, retransmisión, difusión o tomar cualquier acción en abuso de confianza sobre esta información por las personas o entidades, con excepción del destinatario está prohibida. La información expresada aquí es de quien la emite. {nombre_empresa}, Aviso de privacidad en http://moloch.mx</td></tr>
</tbody>
</table>
</body></html>
HTML;

    private const PIE = <<<'HTML'
<table border="0" cellpadding="0" cellspacing="0" style="width:100%; border-collapse:collapse;">
<tbody>
<tr><td style="background:#2196f3; text-align:center; font-family:arial,helvetica,sans-serif; font-size:14px; color:#ffffff;">https://moloch.mx</td></tr>
</tbody>
</table>
<p style="text-align:left; font-size:11px;">Fecha de impresión: {fecha_impresion}</p>
HTML;

    private const CORREO = <<<'HTML'
<p>Hola {nombre_cliente},</p>
<p>Le enviamos adjunta la cotización <strong>{folio}</strong> del {fecha_cotizacion} por un total de <strong>{total}</strong>.</p>
<p>La cotización es válida hasta el {vigencia}.</p>
<p>Quedamos a sus órdenes para cualquier duda.</p>
<p>{nombre_empresa}<br />Tel: {telefono_empresa}</p>
HTML;
}
