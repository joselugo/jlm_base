<?php
/**
 * Pantalla principal de Ajustes. El permiso "Ajustes" solo abre esta pantalla:
 * cada botón se muestra si el usuario tiene al menos lectura en el permiso de su
 * sección ($puede viene de Ajustes::secciones_visibles()).
 * Los botones "ajax/..." son secciones que aún no tienen pantalla; dependen de "Ajustes".
 *
 * @var array<string, bool> $puede
 */
$icono = static fn (string $clase): string => '<i class="' . $clase . '"></i>';
$secciones = [
  ['GENERAL',            '#ajustes/general',                        $icono('fas fa-cog fa-2x'),                  'General'],
  ['GESTION_PERSONAL',   '#sistema/gestion_personal',               $icono('fas fa-users fa-2x'),                'Gestión personal'],
  ['AJUSTES',            '#ajax/ajustes?action=correo',             $icono('far fa-envelope fa-2x'),             'Servidor de correo'],
  ['CONFIG_FACTURACION', '#ajustes/facturacion',                    $icono('far fa-file-pdf fa-2x'),             'Facturación'],
  ['AJUSTES',            '#ajax/ajustesotros?action=facturaselectronicas', $icono('fas fa-file-invoice-dollar'), 'Facturación Electrónica'],
  ['PASARELAS_PAGO',     '#ajustes/pasarelas_pago',                 $icono('fas fa-hand-holding-usd fa-2x'),     'Pasarelas de pago'],
  ['EDITOR_PLANTILLAS',  '#editor_plantillas/home',                 $icono('fas fa-code fa-2x'),                 'Editor plantillas'],
  ['AJUSTES',            '#ajax/ajustes?action=portalcliente',      $icono('far fa-user fa-2x'),                 'Portal cliente'],
  ['AJUSTES',            '#ajax/ajustesotros?action=onesignal',     '<img src="images/onesignal.svg" style="height: 28px;margin-top: 18px;max-width: 100%;">', 'Notificaciones Push'],
  ['TICKETS',            '#ajustes/ajustes_tickets',                $icono('far fa-life-ring fa-2x'),            'Tickets'],
  ['AJUSTES',            '#ajax/ajustesotros?action=zendesk',       '<img src="images/zendesk.svg" style="height: 50px;margin-top: 0px;max-width: 100%;">', 'Zendesk Support'],
  ['AJUSTES',            '#ajax/ajustes?action=importar',           $icono('fa fa-upload'),                      'Importar clientes'],
  ['CAMBIOS_MASIVOS',    '#ajustes/cambios_masivos',                $icono('fas fa-retweet'),                    'Cambios Masivos'],
  ['AJUSTES',            '#ajax/ajustes?action=predeterminado',     $icono('fas fa-users-cog'),                  'Plantillas<br>Configuración'],
  ['ZONAS',              '#ajustes/zonas',                          $icono('fas fa-map-signs fa-2x'),            'Ubicaciones'],
  ['SMS',                '#ajustes/sms',                            $icono('far fa-comments fa-2x'),             'SMS'],
  ['CLOUD',              '#ajustes/cloud',                          $icono('fab fa-dropbox fa-2x'),              'Cloud'],
  ['GOOGLE',             '#ajustes/google',                         $icono('fab fa-google fa-2x'),               'Google'],
  ['CREATE_BACKUP',      '#bdatabase',                              $icono('fas fa-database fa-2x'),             'Base de datos'],
  ['AJUSTES',            '#ajax/ajustes?action=crontab',            $icono('far fa-clock fa-2x'),                'Crontab'],
  ['LOG',                '#ajustes/log',                            $icono('fas fa-cogs fa-2x'),                 'Logs'],
  ['SYSTEM',             '#system',                                 $icono('fas fa-wrench fa-2x'),               'Sistema'],
  ['AJUSTES',            '#ajax/ajustes?action=server',             $icono('fas fa-server fa-2x'),               'Servidor'],
  ['AJUSTES',            '#ajax/ajustes?action=migrar',             $icono('fas fa-sync-alt'),                   'Migrar'],
  ['AJUSTES',            '#ajax/ajustes?action=freeradius',         $icono('fas fa-key fa-2x'),                  'Freeradius'],
  ['AJUSTES',            '#ajax/ajustes?action=licencia',           $icono('fas fa-info-circle fa-2x'),          'Licencia'],
  ['RUTAS',              '#rutas/index',                            $icono('fas fa-route'),                      'Rutas'],
  ['SUCURSAL',           '#ajustes/ajuste_sucursal',                $icono('fas fa-store-alt'),                  'Sucursales'],
];
$visibles = 0;
?>
	<!-- begin row -->
	<div class="row">
		<?php foreach ($secciones as [$clave, $url, $imagen, $titulo]) : ?>
			<?php if (empty($puede[$clave])) continue; $visibles++; ?>
			<a href="<?= $url ?>" data-toggle="ajax" class="btnajustes btn btn-lg btn-white"<?= str_contains($titulo, '<br>') ? ' style="line-height: inherit;"' : '' ?>>
				<?= $imagen ?><br>
				<?= $titulo ?></a>
		<?php endforeach; ?>
		<?php if (!empty($puede['AJUSTES_ALMACEN'])) : $visibles++; ?>
			<a data-toggle="tooltip" title="" class="btnajustes btn btn-lg btn-white" onclick="getmodal('ajustes/modal_ajustes_almacen','Personalizar almacen','lg');">
				<i class="fa fa-archive"></i>
				<br>
				Almacen
			</a>
		<?php endif; ?>
		<?php if ($visibles === 0) : ?>
			<div class="col-12"><div class="alert alert-info">No tienes permiso para ninguna sección de Ajustes. Pide al administrador que te asigne las que necesitas.</div></div>
		<?php endif; ?>
	</div>
	<!-- end row -->

	<script>
		$(document).ready(function() {
			App.restartGlobalFunction();
			App.setPageTitle('Ajustes');
		})
	</script>
