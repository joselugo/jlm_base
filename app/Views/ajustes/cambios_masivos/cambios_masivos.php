	<div class="panel panel-inverse">
		<div class="panel-heading">
			<h4 class="panel-title">Cambios masivos</h4>
		</div>
		<div class="panel-body">
			<p>Aquí podemos cambiar la configuración de la facturación cliente, por ejemplo día de pago,activar corte,activar notificaciones,etc. Los cambios son de forma masiva y no se puede volver atrás.</p>
			<p class="text-warning"><b>Si solo deseamos cambiar esta configuración algunos clientes, solo debemos ingresar a los detalles del cliente->sección facturación->Configuración.</b></p>
			<div class="alert alert-yellow fade show m-b-10">
				<b>ALERTA: </b> realizar estos cambios de manera prudente, una mala configuración podría dejar de funcionar algunos servicios como corte,avisos,facturas del mes actual.
			</div>

			<form class="row form-horizontal" id="formchangemass" action="ajustes/save_cambios_masivos" method="post">

				<div class="col-sm-7 m-t-20" style="float: none; margin: 0 auto">
					<div class="form-group row m-b-15">
						<label class="col-sm-6 col-form-label text-right">Filtrar por Router</label>
						<div class="col-md-6">
							<select style="width: 100%;" class="form-control sl2" name="nodo" onChange="filtermasivo()" required>
								<option></option>
								<option value="0">Cualquiera</option>
								<?= $servers; ?>
							</select>
						</div>
					</div>

					<div class="form-group row m-b-15">
						<label class="col-sm-6 col-form-label text-right">Filtrar por Plan</label>
						<div class="col-md-6">
							<select style="width: 100%;" class="form-control sl2" name="perfil" onChange="filtermasivo()" required>
								<option></option>
								<option value="0">Cualquiera</option>
								<?= $planes;?>
							</select>
						</div>
					</div>


					<div class="form-group row m-b-15">
						<label class="col-sm-6 col-form-label text-right">Filtrar por Día de pago</label>
						<div class="col-md-6">
							<select name="diapago" class="sl2" style="width: 100%;" onChange="filtermasivo()" required>
								<option></option>
								<option value="0">Cualquiera</option>
								<option value="1">01</option>
								<option value="2">02</option>
								<option value="3">03</option>
								<option value="4">04</option>
								<option value="5">05</option>
								<option value="6">06</option>
								<option value="7">07</option>
								<option value="8">08</option>
								<option value="9">09</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
								<option value="13">13</option>
								<option value="14">14</option>
								<option value="15">15</option>
								<option value="16">16</option>
								<option value="17">17</option>
								<option value="18">18</option>
								<option value="19">19</option>
								<option value="20">20</option>
								<option value="21">21</option>
								<option value="22">22</option>
								<option value="23">23</option>
								<option value="24">24</option>
								<option value="25">25</option>
								<option value="26">26</option>
								<option value="27">27</option>
								<option value="28">28</option>
							</select>
						</div>
					</div>




				</div>

				<div class="col-lg-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title"><b><i class="far fa-file"></i> Facturación</b></h4>
						</div>
						<div class="panel-body border-panel">

							<div class="form-group row">
								<label class="col-md-5 col-form-label">Tipo</label>
								<div class="col-md-6">
									<select name="config[tipopago]" class="sl2 form-control" style="width: 100%;" required>
										<option></option>
										<option value="0">Prepago (Adelantado)</option>
										<option value="1">Postpago (Vencido)</option>
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-5 col-form-label">Día pago</label>
								<div class="col-md-6">
									<select name="config[diapago]" class="sl2 form-control" style="width: 100%;">
										<option></option>
										<option value="1">01</option>
										<option value="2">02</option>
										<option value="3">03</option>
										<option value="4">04</option>
										<option value="5">05</option>
										<option value="6">06</option>
										<option value="7">07</option>
										<option value="8">08</option>
										<option value="9">09</option>
										<option value="10">10</option>
										<option value="11">11</option>
										<option value="12">12</option>
										<option value="13">13</option>
										<option value="14">14</option>
										<option value="15">15</option>
										<option value="16">16</option>
										<option value="17">17</option>
										<option value="18">18</option>
										<option value="19">19</option>
										<option value="20">20</option>
										<option value="21">21</option>
										<option value="22">22</option>
										<option value="23">23</option>
										<option value="24">24</option>
										<option value="25">25</option>
										<option value="26">26</option>
										<option value="27">27</option>
										<option value="28">28</option>
									</select>

								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-5 col-form-label">Crear Factura</label>
								<div class="col-md-6">
									<select name="config[diafactura]" class="sl2 form-control" style="width: 100%;" required>
										<option></option>
										<option value="0">Desactivado</option>
										<option value="1">1 Día antes</option>
										<option value="2">2 Días antes</option>
										<option value="3">3 Días antes</option>
										<option value="4">4 Días antes</option>
										<option value="5">5 Días antes</option>
										<option value="6">6 Días antes</option>
										<option value="7">7 Días antes</option>
										<option value="8">8 Días antes</option>
										<option value="9">9 Días antes</option>
										<option value="10">10 Días antes</option>
										<option value="11">11 Días antes</option>
										<option value="12">12 Días antes</option>
										<option value="13">13 Días antes</option>
										<option value="14">14 Días antes</option>
										<option value="15">15 Días antes</option>
										<option value="16">16 Días antes</option>
										<option value="17">17 Días antes</option>
										<option value="18">18 Días antes</option>
										<option value="19">19 Días antes</option>
										<option value="20">20 Días antes</option>
										<option value="21">21 Días antes</option>
										<option value="22">22 Días antes</option>
										<option value="23">23 Días antes</option>
										<option value="24">24 Días antes</option>
										<option value="25">25 Días antes</option>
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-5 col-form-label">Tipo impuesto</label>
								<div class="col-md-6">
									<select style="width: 100%;" class="sl2 form-control" name="config[impuesto]" required>
										<option></option>
										<option value="NO">Impuestos incluido</option>
										<option value="SI">Mas Impuestos</option>
										<option value="NADA">Ninguno</option>
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-5 col-form-label">Días de gracia</label>
								<div class="col-md-6">
									<select name="config[corteautomatico]" class="sl2 form-control" style="width: 100%;" required>
										<option></option>
										<option value="0">0 Días</option>
										<option value="1">1 Día</option>
										<option value="2">2 Días</option>
										<option value="3">3 Días</option>
										<option value="4">4 Días</option>
										<option value="5">5 Días</option>
										<option value="6">6 Días</option>
										<option value="7">7 Días</option>
										<option value="8">8 Días</option>
										<option value="9">9 Días</option>
										<option value="10">10 Días</option>
										<option value="11">11 Días</option>
										<option value="12">12 Días</option>
										<option value="13">13 Días</option>
										<option value="14">14 Días</option>
										<option value="15">15 Días</option>
										<option value="16">16 Días</option>
										<option value="17">17 Días</option>
										<option value="18">18 Días</option>
										<option value="19">19 Días</option>
										<option value="20">20 Días</option>
										<option value="21">21 Días</option>
										<option value="22">22 Días</option>
										<option value="23">23 Días</option>
										<option value="24">24 Días</option>
										<option value="25">25 Días</option>
									</select>
									<small class="text-warning">*días tolerancia para aplicar corte</small>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-5 col-form-label">Aplicar Corte</label>
								<div class="col-md-6">
									<select name="config[meses]" class="sl2 form-control" style="width: 100%;" required>
										<option></option>
										<option value="0">Desactivado</option>
										<option value="1">1 Mes vencido</option>
										<option value="2">2 Meses vencidos</option>
										<option value="3">3 Meses vencidos</option>
										<option value="4">4 Meses vencidos</option>
										<option value="5">5 Meses vencidos</option>
										<option value="6">6 Meses vencidos</option>
										<option value="7">7 Meses vencidos</option>
										<option value="8">8 Meses vencidos</option>
										<option value="9">9 Meses vencidos</option>
										<option value="10">10 Meses vencidos</option>
										<option value="11">11 Meses vencidos</option>
										<option value="12">12 Meses vencidos</option>
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-5 col-form-label">Bajar Velocidad</label>
								<div class="col-md-6">
									<select name="config[limit_velocidad]" class="sl2 form-control" style="width: 100%;" required>
										<option></option>
										<option value="0">Desactivado</option>
										<option value="1">Activar</option>
									</select>
									<small class="text-danger">*Baja la velocidad del cliente cuando vence una Factura, este se aplica solo si ha configurado "Aplicar Corte" + de un mes de vencido.</small>
								</div>
							</div>


							<div class="form-group row">
								<label class="col-md-5 col-form-label">Aplicar Mora</label>
								<div class="col-md-6">
									<select name="config[mora]" class="sl2 form-control" style="width: 100%;" required>
										<option></option>
										<option value="no">NO aplicar mora</option>
										<option value="on">SI aplicar mora</option>
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-5 col-form-label">Cobrar Reconexión</label>
								<div class="col-md-6">
									<select name="config[reconexion]" class="sl2 form-control" style="width: 100%;" required>
										<option></option>
										<option value="no">NO Cobrar reconexión</option>
										<option value="on">SI Cobrar reconexión</option>
									</select>
								</div>
							</div>

							<h5 class="text-center"><b>Otros Impuestos</b></h5>
							<small class="text-center m-b-10" style="width: 100%; display: block">Estos Impuestos serán Agregados al total de la factura</small>
							<div class="form-group row">
								<label class="col-md-5 col-form-label">Impuesto #1 (%)</label>
								<div class="col-md-6">
									<input type="number" name="impuestos[1]" class="form-control" placeholder="10" value="" step="0.1" max="100" min="0">
									<small>* Dejar en 0 (cero) para quedar deshabilitado</small>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-5 col-form-label">Impuesto #2 (%)</label>
								<div class="col-md-6">
									<input type="number" name="impuestos[2]" class="form-control" placeholder="10" value="" step="0.1" max="100" min="0">
									<small>* Dejar en 0 (cero) para quedar deshabilitado</small>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-5 col-form-label">Impuesto #3 (%)</label>
								<div class="col-md-6">
									<input type="number" name="impuestos[3]" class="form-control" placeholder="10" value="" step="0.1" max="100" min="0">
									<small>* Dejar en 0 (cero) para quedar deshabilitado</small>
								</div>
							</div>


						</div>
					</div>
				</div>


				<div class="col-lg-6">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title"><b><i class="far fa-bell"></i> Notificaciones</b></h4>
						</div>
						<div class="panel-body border-panel">

							<div class="form-group row">
								<label class="col-md-5 col-form-label">Aviso nueva factura</label>
								<div class="col-md-6">
									<select name="config[tipoaviso]" class="sl2 form-control" style="width: 100%;">
										<option></option>
										<option value="0">Desactivado</option>
										<option value="1">Correo</option>
										<option value="2">SMS</option>
										<option value="4">Telegram</option>
										<option value="5">Correo + Telegram</option>
										<option value="3">Correo + SMS</option>
										<option value="6">Telegram + SMS</option>
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-5 col-form-label">Aviso en Pantalla</label>
								<div class="col-md-6">
									<select name="config[avisopantalla]" class="sl2 form-control" style="width: 100%;">
										<option></option>
										<option value="0">Desactivado</option>
										<option value="1">1 Día antes</option>
										<option value="2">2 Días antes</option>
										<option value="3">3 Días antes</option>
										<option value="4">4 Días antes</option>
										<option value="5">5 Días antes</option>
										<option value="6">6 Días antes</option>
										<option value="7">7 Días antes</option>
										<option value="8">8 Días antes</option>
										<option value="9">9 Días antes</option>
										<option value="10">10 Días antes</option>
										<option value="11">11 Días antes</option>
										<option value="12">12 Días antes</option>
										<option value="13">13 Días antes</option>
										<option value="14">14 Días antes</option>
										<option value="15">15 Días antes</option>
										<option value="16">16 Días antes</option>
										<option value="17">17 Días antes</option>
										<option value="18">18 Días antes</option>
										<option value="19">19 Días antes</option>
										<option value="20">20 Días antes</option>
										<option value="21">21 Días antes</option>
										<option value="22">22 Días antes</option>
										<option value="23">23 Días antes</option>
										<option value="24">24 Días antes</option>
										<option value="25">25 Días antes</option>
									</select>
									<small class="text-warning">* Aviso solo en páginas HTTP</small>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-5 col-form-label">Recordatorios de pago</label>
								<div class="col-md-6">
									<select name="config[tiporecordatorio]" class="sl2 form-control" style="width: 100%;">
										<option></option>
										<option value="0">Desactivado</option>
										<option value="1">Correo</option>
										<option value="2">SMS</option>
										<option value="4">Telegram</option>
										<option value="5">Correo + Telegram</option>
										<option value="3">Correo + SMS</option>
										<option value="6">Telegram + SMS</option>
									</select>

								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-5 col-form-label">Recordatorio #1</label>
								<div class="col-md-6">
									<select name="config[avisosms]" class="sl2 form-control" style="width: 100%;">
										<option></option>
										<option value="0">Desactivado</option>

										<optgroup label="Antes del vencimiento">
											<option value="-1">1 Día Antes</option>
											<option value="-2">2 Días Antes</option>
											<option value="-3">3 Días Antes</option>
											<option value="-4">4 Días Antes</option>
											<option value="-5">5 Días Antes</option>
											<option value="-6">6 Días Antes</option>
											<option value="-7">7 Días Antes</option>
											<option value="-8">8 Días Antes</option>
											<option value="-9">9 Días Antes</option>
											<option value="-10">10 Días Antes</option>
										</optgroup>


										<optgroup label="Después del vencimiento">
											<option value="1">1 Día después</option>
											<option value="2">2 Días después</option>
											<option value="3">3 Días después</option>
											<option value="4">4 Días después</option>
											<option value="5">5 Días después</option>
											<option value="6">6 Días después</option>
											<option value="7">7 Días después</option>
											<option value="8">8 Días después</option>
											<option value="9">9 Días después</option>
											<option value="10">10 Días después</option>
											<option value="11">11 Días después</option>
											<option value="12">12 Días después</option>
											<option value="13">13 Días después</option>
											<option value="14">14 Días después</option>
											<option value="15">15 Días después</option>
											<option value="16">16 Días después</option>
											<option value="17">17 Días después</option>
											<option value="18">18 Días después</option>
											<option value="19">19 Días después</option>
											<option value="20">20 Días después</option>
											<option value="21">21 Días después</option>
											<option value="22">22 Días después</option>
											<option value="23">23 Días después</option>
											<option value="24">24 Días después</option>
											<option value="25">25 Días después</option>
										</optgroup>
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-5 col-form-label">Recordatorio #2</label>
								<div class="col-md-6">
									<select name="config[avisosms2]" class="sl2 form-control" style="width: 100%;">
										<option></option>
										<option value="0">Desactivado</option>

										<optgroup label="Antes del vencimiento">
											<option value="-1">1 Día Antes</option>
											<option value="-2">2 Días Antes</option>
											<option value="-3">3 Días Antes</option>
											<option value="-4">4 Días Antes</option>
											<option value="-5">5 Días Antes</option>
											<option value="-6">6 Días Antes</option>
											<option value="-7">7 Días Antes</option>
											<option value="-8">8 Días Antes</option>
											<option value="-9">9 Días Antes</option>
											<option value="-10">10 Días Antes</option>
										</optgroup>


										<optgroup label="Después del vencimiento">
											<option value="1">1 Día después</option>
											<option value="2">2 Días después</option>
											<option value="3">3 Días después</option>
											<option value="4">4 Días después</option>
											<option value="5">5 Días después</option>
											<option value="6">6 Días después</option>
											<option value="7">7 Días después</option>
											<option value="8">8 Días después</option>
											<option value="9">9 Días después</option>
											<option value="10">10 Días después</option>
											<option value="11">11 Días después</option>
											<option value="12">12 Días después</option>
											<option value="13">13 Días después</option>
											<option value="14">14 Días después</option>
											<option value="15">15 Días después</option>
											<option value="16">16 Días después</option>
											<option value="17">17 Días después</option>
											<option value="18">18 Días después</option>
											<option value="19">19 Días después</option>
											<option value="20">20 Días después</option>
											<option value="21">21 Días después</option>
											<option value="22">22 Días después</option>
											<option value="23">23 Días después</option>
											<option value="24">24 Días después</option>
											<option value="25">25 Días después</option>
										</optgroup>
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-md-5 col-form-label">Recordatorio #3</label>
								<div class="col-md-6">
									<select name="config[avisosms3]" class="sl2 form-control" style="width: 100%;">
										<option></option>
										<option value="0">Desactivado</option>

										<optgroup label="Antes del vencimiento">
											<option value="-1">1 Día Antes</option>
											<option value="-2">2 Días Antes</option>
											<option value="-3">3 Días Antes</option>
											<option value="-4">4 Días Antes</option>
											<option value="-5">5 Días Antes</option>
											<option value="-6">6 Días Antes</option>
											<option value="-7">7 Días Antes</option>
											<option value="-8">8 Días Antes</option>
											<option value="-9">9 Días Antes</option>
											<option value="-10">10 Días Antes</option>
										</optgroup>


										<optgroup label="Después del vencimiento">
											<option value="1">1 Día después</option>
											<option value="2">2 Días después</option>
											<option value="3">3 Días después</option>
											<option value="4">4 Días después</option>
											<option value="5">5 Días después</option>
											<option value="6">6 Días después</option>
											<option value="7">7 Días después</option>
											<option value="8">8 Días después</option>
											<option value="9">9 Días después</option>
											<option value="10">10 Días después</option>
											<option value="11">11 Días después</option>
											<option value="12">12 Días después</option>
											<option value="13">13 Días después</option>
											<option value="14">14 Días después</option>
											<option value="15">15 Días después</option>
											<option value="16">16 Días después</option>
											<option value="17">17 Días después</option>
											<option value="18">18 Días después</option>
											<option value="19">19 Días después</option>
											<option value="20">20 Días después</option>
											<option value="21">21 Días después</option>
											<option value="22">22 Días después</option>
											<option value="23">23 Días después</option>
											<option value="24">24 Días después</option>
											<option value="25">25 Días después</option>
										</optgroup>
									</select>
									<small class="text-warning">* Días antes/despues del vencimiento de una factura</small>
								</div>
							</div>

							<div class="text-center m-t-20">
								<div class="alert alert-yellow fade show m-b-5 alertachange" style="display: none">

								</div>
								<button type="submit" class="btn btn-white btnchange m-t-5" data-loading-text='<i class="fas fa-spinner fa-spin"></i> procesando..'> <i class="fas fa-exchange-alt"></i> Aplicar cambios</button>
							</div>

						</div>
					</div>
				</div>






			</form>
		</div>
	</div>


	<script>
		function filtermasivo() {
			if (!$('select[name="nodo"]').val()) {
				return false;
			}

			if (!$('select[name="perfil"]').val()) {
				return false;
			}

			if (!$('select[name="diapago"]').val()) {
				return false;
			}

			$('.btnchange').button('loading');
			$.post("ajustes/consulta_cambios_masivos", {
					nodo: $('select[name="nodo"]').val(),
					perfil: $('select[name="perfil"]').val(),
					dia: $('select[name="diapago"]').val()
				})
				.done(function(data) {
					data = JSON.parse(data);
					$('.btnchange').button('reset');
					if (data.clientes > 0) {
						$('.alertachange').html('Se va aplicar la nueva configuración a <b>' + data.clientes + '</b> clientes.').show();
						$('.btnchange').prop('disabled', false);
					} else {
						$('.alertachange').html('No existe ningún cliente con el filtro seleccionado.').show();
						$('.btnchange').prop('disabled', true);
					}
				});

		}


		$(document).ready(function() {

			$('#formchangemass').ajaxForm({
				success: function(data) {
					$('.btnchange').button('reset');
					$('#gritter-notice-wrapper').remove();
					if (data == 1) {
						alerta("exito", "Los cambios se realizaron con exito.");
					} else if (data == 2) {
						alerta("warning", "No se realizaron los cambios.");
					} else if (data == 3) {
						alerta("warning", "No se encontro ningun cliente con esos filtros.");
					} else {
						alerta("warning", "Error.");
					}
				},
				beforeSubmit: function() {
					$('.btnchange').button('loading');
				},
				error: function(response) {
					$('.btnchange').button('reset');
					$('#gritter-notice-wrapper').remove();
					alerta('error500', "Error");
				}
			})


			App.restartGlobalFunction();
			App.setPageTitle('Cambios Masivos');

			$('.sl2').select2({
				placeholder: "Seleccionar para cambiar"
			})

			var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
			elems.forEach(function(html) {
				var switchery = new Switchery(html, {
					size: 'small',
					color: 'rgb(18, 187, 168)'
				});
			});

		});
	</script>