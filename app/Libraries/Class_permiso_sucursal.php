<?php

namespace App\Libraries;

/*
 * Condiciones SQL de sucursal para las consultas existentes. Cada función devuelve el
 * texto para $builder->where(): con todas las sucursales, una condición siempre verdadera;
 * si no, la delega en FiltroSucursal::condicion() (ids enteros, "1=0" si no tiene ninguna).
 *
 * Clientes y tickets se filtran por la sucursal del CLIENTE (u.sucursal). Antes usaban
 * la del servicio (t.sucursal) y un usuario limitado no veía clientes sin servicio ni
 * sus facturas, pagos o tickets.
 */

#[\AllowDynamicProperties]
class Class_permiso_sucursal
{

	public $string_permisos;

	function __construct()
	{
		$this->string_permisos = "";
	}

	function get_permiso_sucursal_intalacion($ids_sucursal)
	{
		$this->string_permisos = $ids_sucursal == "%%"
			? "i.id>0"
			: FiltroSucursal::condicion($ids_sucursal, 'i.sucursal');

		return $this->string_permisos;
	}

	function get_permiso_sucursal_ticket($ids_sucursal)
	{
		$this->string_permisos = $ids_sucursal == "%%"
			? "s.id>0"
			: FiltroSucursal::condicion($ids_sucursal, 'u.sucursal');

		return $this->string_permisos;
	}

	function get_permiso_sucursal($ids_sucursal)
	{
		$this->string_permisos = $ids_sucursal == "%%"
			? "u.id>0"
			: FiltroSucursal::condicion($ids_sucursal, 'u.sucursal');

		return $this->string_permisos;
	}

	
	function get_permiso_sucursal_gestion($ids_sucursal)
	{
		$this->string_permisos = $ids_sucursal == "%%"
			? "l.id>0"
			: FiltroSucursal::condicion($ids_sucursal, 't.sucursal');

		return $this->string_permisos;
	}

	function get_permiso_sucursal_cotizaciones($ids_sucursal)
	{
		$this->string_permisos = $ids_sucursal == "%%"
			? "id>0"
			: FiltroSucursal::condicion($ids_sucursal, 'sucursal');

		return $this->string_permisos;
	}

		function get_permiso_sucursal_login($ids_sucursal)
	{
		$this->string_permisos = $ids_sucursal == "%%"
			? "l.id>0"
			: FiltroSucursal::condicion($ids_sucursal, 'l.sucursal_origen');

		return $this->string_permisos;
	}


	function get_permiso_sucursal_select($ids_sucursal)
	{
		$this->string_permisos = $ids_sucursal == "%%"
			? "id>0"
			: FiltroSucursal::condicion($ids_sucursal, 'id');

		return $this->string_permisos;
	}

	function get_permiso_nodo($ids_nodo)
	{
		$this->string_permisos = $ids_nodo == "%%"
			? "u.id>0"
			: FiltroSucursal::condicion($ids_nodo, 'u.nodo');

		return $this->string_permisos;
	}

		function get_permiso_sucursal_calendar($ids_sucursal)
	{
		$this->string_permisos = $ids_sucursal == "%%"
			? "c.id>0"
			: FiltroSucursal::condicion($ids_sucursal, 'sucursal');

		return $this->string_permisos;
	}

	

	function get_permiso_socios_select($ids_sindicato)
	{
		$this->string_permisos = $ids_sindicato == "%%"
			? "id>0"
			: FiltroSucursal::condicion($ids_sindicato, 'id');

		return $this->string_permisos;
	}

	function get_permiso_sucursal_for_vehicles($ids_sindicato)
	{
		$this->string_permisos = $ids_sindicato == "%%"
			? "null"
			: FiltroSucursal::condicion($ids_sindicato, 'c.sucursal');

		return $this->string_permisos;
	}

	function get_permiso_sucursal_for_ordenes($ids_sindicato)
	{
		$this->string_permisos = $ids_sindicato == "%%"
			? "null"
			: FiltroSucursal::condicion($ids_sindicato, 'c.sucursal');

		return $this->string_permisos;
	}

	function get_permiso_sucursal_facturas($ids_sindicato)
	{
		$this->string_permisos = $ids_sindicato == "%%"
			? "null"
			: FiltroSucursal::condicion($ids_sindicato, 'c.sucursal');

		return $this->string_permisos;
	}

	function get_permiso_sucursal_estadisticas($ids_sindicato)
	{
		$this->string_permisos = $ids_sindicato == "%%"
			? null
			: FiltroSucursal::condicion($ids_sindicato, 'c.sucursal');

		return $this->string_permisos;
	}


	function get_permiso_sucursal_gastos($ids_sindicato)
	{
		$this->string_permisos = $ids_sindicato == "%%"
			? null
			: FiltroSucursal::condicion($ids_sindicato, 'sucursal');

		return $this->string_permisos;
	}

	function get_permiso_sucursal_for_transacciones($ids_sindicato)
	{
		$this->string_permisos = "";
		$array_id_user_for_view = array();
		if ($ids_sindicato == "%%") {
		} else {

			$array_permisos_nodo_user_active = explode(",", $ids_sindicato);
			$model_selectores = new \App\Models\Model_selectores();
			$all_user = $model_selectores->get_all_personal();
			foreach ($all_user as $data) {
				if ($data->sucursales != "%%") {
					$array_permisos_nodo_for_user = explode(",", $data->sucursales);
					foreach ($array_permisos_nodo_for_user as $sucursal_one_one_for_user) {

						foreach ($array_permisos_nodo_user_active as $sucursal_one_one_for_user_active) {
							if ($sucursal_one_one_for_user == $sucursal_one_one_for_user_active) {
								array_push($array_id_user_for_view, $data->id_usuario);
							}
						}
					}
				}
			} ////fin first foreach



			//////////////decodifica todos los permisos para pasar a where

			$cadena_permisos = "";
			$contador = 0;
			foreach ($array_id_user_for_view as $value) {
				if ($contador == 0) {
					$cadena_permisos .= "u.id_usuario = " . $value;
				} else {
					$cadena_permisos .= " OR u.id_usuario = " . $value;
				}
				$contador++;
			}
			// $cadena_permisos.=" OR u.sucursales = '%%' "; //los que tengan permiso %% lo pueden visualizar cualquiera
			$this->string_permisos = $cadena_permisos;
		} ///fin else	


		return $this->string_permisos;
	}

	function get_permiso_sucursal_for_search($ids_sindicato)
	{
		$this->string_permisos = $ids_sindicato == "%%"
			? "null"
			: FiltroSucursal::condicion($ids_sindicato, 'c.sucursal');

		return $this->string_permisos;
	}

	function get_sucursal_for_user($id_usuario)
	{
		$model_selectores = new \App\Models\Model_selectores();
		$row = $model_selectores->get_sucursal_for_user($id_usuario);
		$ids_sindicato = $row->sucursales;

		$option_sucursales = "";
		if ($ids_sindicato == "%%") {
			$sucursales = $model_selectores->get_all_sucursales();
			if ($sucursales) {
				foreach ($sucursales as $items) {
					$option_sucursales .= "<option value='" . $items->id . "'>" . $items->nombre . "</option>";
				}
			} else {
				$option_sucursales .= "";
			}

			return $option_sucursales;
		} else {
			$array_permisos_nodo = explode(",", $ids_sindicato);

			foreach ($array_permisos_nodo as $value) {

				$row = $model_selectores->get_sucursal_by_id($value);
				if ($row) {
					$option_sucursales .= "<option value='" . $row->id . "'>" . $row->nombre . "</option>";
				} else {
					$option_sucursales .= "";
				}
			}
			return $option_sucursales;
		}
	}
	
	function get_permiso_sucursal_almacen($ids_sucursal)
	{
		$this->string_permisos = $ids_sucursal == "%%"
			? "a.id>0"
			: FiltroSucursal::condicion($ids_sucursal, 'a.sucursal');

		return $this->string_permisos;
	}
	// Filtro para consultas de nodos: "server s JOIN tblsucursalesnodo t"
	// (get_all_nodos_by_usuario). Antes usaba el alias "u", que no existe en esas
	// consultas: la lista de nodos salía vacía (o error con DBDebug activo)
	function get_permiso_sucursal_nodo($ids_sucursal)
	{
		if ($ids_sucursal == "%%") {
			$this->string_permisos = "s.id>0";
		} else {
			$ids = array_map('intval', explode(",", $ids_sucursal));
			$this->string_permisos = "t.idsucursal IN (" . implode(",", $ids) . ")";
		}

		return $this->string_permisos;
	}
	function get_permiso_sucursal_server($ids_sucursal)
	{
		$this->string_permisos = $ids_sucursal == "%%"
			? "s.id>0"
			: FiltroSucursal::condicion($ids_sucursal, 's.idsucursal');

		return $this->string_permisos;
	}
}
