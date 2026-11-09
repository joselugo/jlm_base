<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Campos automáticos de las plantillas (Ajustes > Editor de plantillas).
 *
 * Además de los campos fijos ({nombre_empresa}, {total}...), una plantilla puede usar:
 *   {<ajuste>} o {config_<ajuste>}  cualquier ajuste de tblconfiguration (columna setting)
 *   {sucursal_<columna>}            cualquier columna de la tabla sucursal del documento
 *   {cotizacion_<columna>}          cualquier columna de la tabla cotizaciones (solo en la cotización)
 *
 * Quien edita la plantilla decide qué ajuste incluir. Si alguno nunca debe poder
 * imprimirse en un documento, agrégalo a $ajustesOcultos.
 */
class Plantillas extends BaseConfig
{
    /** @var list<string> nombres de ajustes (columna setting) que nunca se sustituyen */
    public array $ajustesOcultos = [];
}
