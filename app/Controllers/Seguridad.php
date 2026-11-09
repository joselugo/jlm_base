<?php

namespace App\Controllers;
//ini_set( "session.gc_maxlifetime", 30 );
use App\DTO\UsuarioSesion;
use App\Models\Model_configuracion;
use App\Libraries\Class_seguridad;
use App\Libraries\Contrasena;
use App\Models\Model_log;
use App\Models\UsuarioModel;
use Config\Services;
use Firebase\JWT\JWT;

class Seguridad extends BaseController
{
	var $nmodel_log;
	var $model_configuracion;
	public function __construct()
	{
		$this->model_log = new Model_log();
		$this->model_configuracion = new Model_configuracion();
		$this->seguridad = new Class_seguridad();
	}

	public function logout()
	{
		session()->destroy();
		$this->seguridad->login_back();
	}



	public function iniciar_sesion()
	{

		$usuarios = new UsuarioModel();
		$usuario = $usuarios->autenticar((string) $this->request->getPost("login"), (string) $this->request->getPost("password"));
		$img_db = $this->model_configuracion->get_configuracion([64]);

		if ($usuario !== null) {
			// Primer acceso: obliga a cambiar la contraseña antes de entrar
			if ($usuario->debeCambiarPassword()) {
				$change = (string) $this->request->getPost("change");
				if ($change === '') {
					echo "change";
					return;
				}
				if (Contrasena::yaUsada($change, (string) $usuario->password, (string) $usuario->oldPassword)) {
					echo "passold";
					return;
				}
				if (! $usuarios->cambiarPassword($usuario, $change)) {
					echo "error";
					return;
				}
			}

			$sesion = UsuarioSesion::desdeUsuario($usuario);

			// Nuevo ID de sesión tras autenticar (evita fijación de sesión)
			session()->regenerate(true);
			foreach ($sesion->aSesion() as $clave => $valor) {
				$_SESSION[$clave] = $valor;
			}
			$_SESSION['background'] = $img_db[0];
			$_SESSION['token8291'] = substr(base64_encode(md5(session_id())), 0, -1);

			// JWT (2 horas)
			$ahora = time();
			$token = JWT::encode([
				'cliente' => $sesion->id,
				'nombre'  => $sesion->nombre,
				'alias'   => $sesion->username,
				'iat'     => $ahora,
				'exp'     => $ahora + 7200,
			], Services::getSecretKey(), 'RS256');
			$this->response->setCookie('token', $token, 3600, config('Cookie')->domain);
			$this->response->setHeader('token', $token);
			$user_ip = $this->getUserIP();
			$insert_log_login = $this->model_log->insert_log_login($_SESSION['id_usuario8291'], $_SESSION['nombre'], $user_ip, "Acceso exitoso");
			echo "success";
		} else {
			echo "error";
			$user_ip = $this->getUserIP();
			$login = (string) $this->request->getPost("login"); // la columna no admite NULL
			$insert_log_login = $this->model_log->insert_log_login($login, $login, $user_ip, "Usuario o contraseña invalido");
		}
	}

	public function cerrar_sesion()
	{
		// Antes llamaba session_unset("...") con argumentos, que no borra nada:
		// la sesión seguía activa. Ahora se destruye igual que en logout()
		session()->destroy();
		$this->seguridad->login_back();
	}


	function getUserIP()
	{
		$ipaddress = '';
		if (getenv('HTTP_CLIENT_IP'))
			$ipaddress = getenv('HTTP_CLIENT_IP');
		else if (getenv('HTTP_X_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if (getenv('HTTP_X_FORWARDED'))
			$ipaddress = getenv('HTTP_X_FORWARDED');
		else if (getenv('HTTP_FORWARDED_FOR'))
			$ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if (getenv('HTTP_FORWARDED'))
			$ipaddress = getenv('HTTP_FORWARDED');
		else if (getenv('REMOTE_ADDR'))
			$ipaddress = getenv('REMOTE_ADDR');
		else
			$ipaddress = 'UNKNOWN';

		return $ipaddress;
	}

	public function cambiar_mi_contrasenia()
	{
		// Respuestas que espera el front: 1 = cambiada, 2 = error, 3 = actual incorrecta, 4 = ya usada
		$actual = (string) $this->request->getPost('oldpaddpassword');
		$nueva = (string) $this->request->getPost('newpassword');
		$usuarios = new UsuarioModel();
		$usuario = $usuarios->find(session('id_usuario8291'));

		if ($usuario === null) {
			echo 2;
		} elseif (! Contrasena::verificar($actual, (string) $usuario->password)) {
			echo 3;
		} elseif (Contrasena::yaUsada($nueva, (string) $usuario->password, (string) $usuario->oldPassword)) {
			echo 4;
		} else {
			echo $usuarios->cambiarPassword($usuario, $nueva) ? 1 : 2;
		}
	}
}
