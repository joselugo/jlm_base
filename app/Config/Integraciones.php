<?php namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Credenciales de servicios externos.
 *
 * Los valores reales viven en .env (fuera de Git) con el prefijo
 * "integraciones.", p. ej.: integraciones.conektaPrivateKey = key_xxx
 * Uso: config('Integraciones')->conektaPrivateKey
 */
class Integraciones extends BaseConfig
{
	// Conekta (referencias OXXO)
	public $conektaPrivateKey = '';

	// SMTP para envío de cotizaciones
	public $smtpHost = '';
	public $smtpUser = '';
	public $smtpPass = '';
	public $smtpPort = 587;
	public $smtpFromName = 'Cotizaciones';

	// Firebase Cloud Messaging (push a la app móvil)
	public $fcmServerKey = '';

	// Dropbox (subida de backups)
	public $dropboxKey    = '';
	public $dropboxSecret = '';
	public $dropboxToken  = '';

	// SMSGateway (API antigua por usuario/contraseña)
	public $smsgatewayEmail    = '';
	public $smsgatewayPassword = '';

	// MikroTik usado por Api::uroboros
	public $mikrotikHost = '';
	public $mikrotikUser = '';
	public $mikrotikPass = '';
	public $mikrotikPort = 8728;

	// CMTS (SSH) usado por Herramientas para consultar/reiniciar cable módems
	public $cmtsHost = '';
	public $cmtsUser = '';
	public $cmtsPass = '';
	public $cmtsPort = 22;

	public function __construct()
	{
		parent::__construct();

		$claves = [
			'conektaPrivateKey',
			'smtpHost', 'smtpUser', 'smtpPass', 'smtpPort', 'smtpFromName',
			'fcmServerKey',
			'dropboxKey', 'dropboxSecret', 'dropboxToken',
			'smsgatewayEmail', 'smsgatewayPassword',
			'mikrotikHost', 'mikrotikUser', 'mikrotikPass', 'mikrotikPort',
			'cmtsHost', 'cmtsUser', 'cmtsPass', 'cmtsPort',
		];

		foreach ($claves as $clave)
		{
			$value = env('integraciones.' . $clave);
			if ($value !== null && $value !== false && $value !== '')
			{
				$this->{$clave} = $value;
			}
		}
	}
}
