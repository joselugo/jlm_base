<?php
/**
 * Vista previa del login en Ajustes > General (iframe). Usa la misma vista que el
 * login real, con el banner elegido en el selector y el formulario desactivado.
 *
 * @var string $img
 */
echo view('seguridad/login', ['img' => $img ?? '', 'vista_previa' => true]);
