<?php namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

/**
 * Exige sesión iniciada en todas las rutas salvo las públicas.
 *
 * Con AutoRoute activo cualquier método público es una URL; antes cada
 * método verificaba la sesión por su cuenta y muchos no lo hacían. Los
 * permisos por módulo (Class_seguridad) se siguen validando en cada método.
 */
class SesionFilter implements FilterInterface
{
	// controlador => métodos accesibles sin sesión (en minúsculas)
	protected $publicas = [
		'index'     => ['index'],                                     // pantalla de login
		'seguridad' => ['iniciar_sesion', 'logout', 'cerrar_sesion'],
		'oxxo'      => ['listeneraction'],                            // webhook de Conekta
	];

	// $arguments es opcional para ser compatible con FilterInterface antes y después de CI 4.0.4
	public function before(RequestInterface $request, $arguments = null)
	{
		helper('url');

		if (is_cli() || $this->esPublica($request))
		{
			return;
		}

		// Sesión de CI4 (compartida con los controladores vía session())
		if (empty(session('id_usuario8291')))
		{
			// La raíz muestra el login; también sirve para las cargas por AJAX
			return Services::response()->redirect(base_url());
		}
	}

	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
	{
	}

	protected function esPublica(RequestInterface $request): bool
	{
		// getPath() devuelve la ruta relativa al baseURL (IncomingRequest)
		$ruta = method_exists($request, 'getPath') ? $request->getPath() : $request->getUri()->getPath();
		$ruta = trim($ruta, '/');

		// Instalación en subdirectorio (p. ej. localhost/panel-networking/)
		$base = trim((string) parse_url(base_url(), PHP_URL_PATH), '/');
		if ($base !== '' && stripos($ruta . '/', $base . '/') === 0)
		{
			$ruta = substr($ruta, strlen($base));
		}

		$segmentos = array_values(array_filter(explode('/', trim($ruta, '/')), 'strlen'));
		if (isset($segmentos[0]) && strtolower($segmentos[0]) === 'index.php')
		{
			array_shift($segmentos);
		}
		$controlador = strtolower($segmentos[0] ?? 'index');
		$metodo      = strtolower($segmentos[1] ?? 'index');

		return isset($this->publicas[$controlador]) && in_array($metodo, $this->publicas[$controlador], true);
	}
}
