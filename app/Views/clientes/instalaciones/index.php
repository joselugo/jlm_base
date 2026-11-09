<ul class="nav nav-tabs">
	<li class="nav-items">
		<a href="#default-tab-1" data-toggle="tab" class="nav-link active show" onclick="updatepre();">
			<span class="d-sm-block"><i class="fas fa-address-card fa-lg"></i> Registro</span>
		</a>
	</li>
	<li class="nav-items">
		<a href="#default-tab-2" data-toggle="tab" class="nav-link" onclick="updateinstall()">
			<span class="d-sm-block"><i class="fas fa-clipboard-list fa-lg"></i> Instalaciones</span>
		</a>
	</li>
</ul>

<div class="tab-content">

	<div class="tab-pane fade active show" id="default-tab-1">

		<table id="data-pre-registro" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" style="width: 100%;">
			<thead>
				<tr role="row">
					<th class="sorting_asc" aria-sort="ascending">ID</th>
					<th class="all sorting">Cliente</th>
					<th class="all sorting">Sucursal</th>
					<th>Ubicación</th>
					<th class="sorting">Fecha</th>
					<th class="sorting">Nº Cedula</th>
					<th class="sorting">Fecha Instalación</th>
					<th class="sorting">Teléfono</th>
					<th class="sorting">Móvil</th>
					<th class="sorting">Correo</th>
					<th class="sorting">Dirección</th>
					<th class="sorting">Notas</th>
					<th class="all text-center sorting_disabled" data-orderable="false" rowspan="1" colspan="1" data-column-index="22" style="width: 56px;" aria-label=""></th>
				</tr>
			</thead>
		</table>
	</div>

	<div class="tab-pane fade" id="default-tab-2">

		<table id="data-install-registro" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" style="width: 100%;">
			<thead>
				<tr role="row">
					<th class="sorting_asc" aria-sort="ascending">ID</th>
					<th class="all sorting">Cliente</th>
					<th class="all sorting">Sucursal</th>
					<th>Ubicación</th>
					<th class="sorting">Fecha</th>
					<th class="sorting">Nº Cedula</th>
					<th class="sorting">Fecha Instalación</th>
					<th class="sorting">Teléfono</th>
					<th class="sorting">Móvil</th>
					<th class="sorting">Correo</th>
					<th class="sorting">Dirección</th>
					<th class="sorting">Notas</th>
					<th class="sorting">Estado</th>
					<th class="all text-center sorting_disabled" data-orderable="false" rowspan="1" colspan="1" data-column-index="22" style="width: 56px;" aria-label=""></th>
				</tr>
			</thead>
		</table>
		</table>
	</div>
</div>

<!-- =============== MODAL NEW INSTALACION ====================== -->
<div class="modal" id="modal_new" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Nuevo Registro</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
			</div>
			<form id="pre-form" method="post" action="clientes/new_pre_install" class="form-horizontal">
				<div class="modal-body" style="width: 100%">
					<div class="row">

						<div class='col-sm-12'>
							<div class='form-group'>
								<label style="width: 100%">Nº Identificación</label>
								<input class="form-control" name="cedula" type="text" placeholder="cedula cliente" autocomplete="off" required style="max-width: 160px; float: left" />
								<br>
								<small class="text-warning m-b-10" style="width: 100%;float: left;">CEDULA,DNI,RUC,CUIT,NIT,SAT,RUT,RTN,ETC.</small>
							</div>

						</div>

						<div class='col-sm-12'>
							<div class='form-group'>
								<label>Sucursal</label>
								<select class="form-control sl2" name="install[sucursal]" required style="width:100%" placeholder="Seleccionar una sucursal">
									<?= $sucursales ?>
								</select>
							</div>
						</div>

						<div class='col-sm-12'>
							<div class='form-group'>
								<label>Nombres y Apellidos</label>
								<input class="form-control" name="cliente" type="text" placeholder="nombre del cliente" autofocus required />
							</div>
						</div>

						<div class='col-sm-12'>
							<div class='form-group'>
								<label>Dirección</label>
								<input class="form-control" name="direccion" type="text" placeholder="domicilio del cliente" autocomplete="off" required />
							</div>
						</div>

						<div class='col-sm-6'>
							<div class='form-group'>
								<label>Telefono Casa</label>
								<input class="form-control" name="telefono" type="text" placeholder="telefono casa" autocomplete="off" />
							</div>
						</div>

						<div class='col-sm-6'>
							<div class='form-group'>
								<label>Telefono Móvil</label>
								<input class="form-control" name="movil" type="text" placeholder="telefono celular" autocomplete="off" />
							</div>
						</div>

						<div class='col-sm-6'>
							<div class='form-group'>
								<label>Correo</label>
								<input class="form-control" name="email" type="email" placeholder="correo cliente" autocomplete="off" />
							</div>
						</div>

						<div class="col-md-6 m-b-5">
							<label>Fecha y hora de instalación</label>
							<input class="form-control datetk" name="fechavisita" id="fechavisita" required="" placeholder="Seleccionar fecha">
						</div>

						<div class='col-sm-6'>
							<div class='form-group'>
								<label>Ubicación</label>
								<select style="width: 100%;" name="zona" class="form-control slpre" data-placeholder="Seleccionar Zona">
									<option></option>
									<?= $zonas ?>
								</select>
							</div>
						</div>

						<div class='col-sm-6'>
							<div class='form-group'>
								<label>Ubicación</label>
								<select style="width: 100%;" name="tecnico" class="form-control slpre" data-placeholder="Seleccionar técnico">
									<option></option>
									<?= $tecnicos ?>
								</select>
							</div>
						</div>


						<div class='col-sm-12'>
							<div class='form-group'>
								<label>Observaciones</label>
								<textarea class="form-control" name="notas" placeholder="notas ..." rows="4" style="height:60px"></textarea>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
					<?php $button = "";
					if ($tipoacceso >= 2) {
						$button = '<button type="submit" class="btn btn-sm btn-success btn-newservice btn-loader" data-loading-text="<i class=\'fas fa-spinner fa-spin\'></i> registrando">Registrar</button>';
					};
					echo $button; ?>
				</div>

			</form>

		</div>
	</div>
</div>

<style type="text/css">
	.select2-dropdown {
		z-index: 1960 !important;
	}
</style>
<!-- =============== END MODAL NEW INSTALACION ====================== -->

<!-- =============== MODAL EDIT INSTALACION ====================== -->
<!-- <div class="modal" id="modal_edit" role="dialog">
	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Editar Registro</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
			</div>
			<form id="pre_form" method="post" action="clientes/save_update_install" class="form-horizontal">
				<div class="modal-body" style="width: 100%">
					<div class="row">
						<input type="hidden" name="id_edit">

						<div class='col-sm-12'>
							<div class='form-group'>
								<label>Nombres y Apellidos</label>
								<input class="form-control" name="cliente_edit" type="text" placeholder="nombre del cliente" autofocus required />
							</div>
						</div>

						<div class='col-sm-12'>
							<div class='form-group'>
								<label>Dirección</label>
								<input class="form-control" name="direccion_edit" type="text" placeholder="domicilio del cliente" autocomplete="off" required />
							</div>
						</div>

						<div class='col-sm-6'>
							<div class='form-group'>
								<label>Nº Cedula/DNI/CUIT/RUT</label>
								<input class="form-control" name="cedula_edit" type="text" placeholder="cedula cliente" autocomplete="off" required />
							</div>
						</div>


						<div class='col-sm-6'>
							<div class='form-group'>
								<label>Telefono Casa</label>
								<input class="form-control" name="telefono_edit" type="text" placeholder="telefono casa" autocomplete="off" />
							</div>
						</div>

						<div class='col-sm-6'>
							<div class='form-group'>
								<label>Telefono Móvil</label>
								<input class="form-control" name="movil_edit" type="text" placeholder="telefono celular" autocomplete="off" />
							</div>
						</div>

						<div class='col-sm-6'>
							<div class='form-group'>
								<label>Correo</label>
								<input value="" class="form-control" name="email_edit" type="email" placeholder="correo cliente" autocomplete="off" />
							</div>
						</div>

						<div class="col-sm-6">
							<div class="form-group">
								<label>Netflix Activo</label>
								<input value="" class="form-control" name="Netflix_Activo_edit" type="text" placeholder="Netflix Activo" autocomplete="off" />
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Netflix Datos</label>
								<input value="" class="form-control" name="Netflix_Datos_edit" type="text" placeholder="Netflix Datos" autocomplete="off" />
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Camaras</label>
								<input value="" class="form-control" name="Camaras_edit" type="text" placeholder="Camaras" autocomplete="off" />
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>TV</label>
								<input value="" class="form-control" name="TV_edit" type="text" placeholder="TV" autocomplete="off" />
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label> Cableado</label>
								<input value="" class="form-control" name="_Cableado_edit" type="text" placeholder=" Cableado" autocomplete="off" />
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Wisp</label>
								<input value="" class="form-control" name="Wisp_edit" type="text" placeholder="Wisp" autocomplete="off" />
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Factibilidad</label>
								<input value="" class="form-control" name="Factibilidad_edit" type="text" placeholder="Factibilidad" autocomplete="off" />
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Referencia1</label>
								<input value="" class="form-control" name="Referencia1_edit" type="text" placeholder="Referencia1" autocomplete="off" />
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Referencia2</label>
								<input value="" class="form-control" name="Referencia2_edit" type="text" placeholder="Referencia2" autocomplete="off" />
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Colonia</label>
								<input value="" class="form-control" name="Colonia_edit" type="text" placeholder="Colonia" autocomplete="off" />
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Ejecutivo</label>
								<input value="" class="form-control" name="Ejecutivo_edit" type="text" placeholder="Ejecutivo" autocomplete="off" />
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label>Actualizado</label>
								<input value="" class="form-control" name="Actualizado_edit" type="text" placeholder="Actualizado" autocomplete="off" />
							</div>
						</div>

						<div class="col-md-6 m-b-5">
							<label>Fecha y hora de instalación</label>
							<input class="form-control datetk" name="fechavisita_edit" id="fechavisita_edit" readonly placeholder="Seleccionar fecha">
						</div>

						<div class='col-sm-6'>
							<div class='form-group'>
								<label>Ubicación</label>
								<select style="width: 100%;" name="zona_edit" class="form-control slpre" required="" data-placeholder="Seleccionar Zona">
									<option></option>
									<?= $zonas ?>
								</select>
							</div>
						</div>


						<div class='col-sm-12'>
							<div class='form-group'>
								<label>Observaciones</label>
								<textarea class="form-control" name="notas_edit" placeholder="notas ..." rows="4" style="height:60px"></textarea>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer">
					<a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
					<?php $button = "";
					if ($tipoacceso >= 2) {
						$button = '<button type="submit" class="btn btn-sm btn-success btn-newservice btn-loader" data-loading-text="<i class=\'fas fa-spinner fa-spin\'></i> Guardando">Guardar</button>';
					};
					echo $button; ?>
				</div>

			</form>
		</div>
	</div>
</div>
 -->
<!-- =============== END MODAL EDIT INSTALACION ====================== -->



<script>
	var base_url = "<?= base_url(); ?>";

	$('.slpre').select2();

	//New instal-pre
	$('#pre-form').ajaxForm({
		success: function(data) {
			$('#gritter-notice-wrapper').remove();
			alerta('exito', 'Pre instalacion registrado correctamente');
			emptyinputs();
			updatepre();
		},
		beforeSubmit: function() {
			$('#modal_new').modal('hide');
		},
		error: function(response) {
			$('#gritter-notice-wrapper').remove();
			alerta('error500', response['responseText']);
		}
	})

	//end new instal-pre

	function emptyinputs() {
		document.getElementById("pre-form").reset();
	}

	$('.datetk').datetimepicker({
		format: 'D MMMM YYYY h:mm a',
		locale: 'es',
		ignoreReadonly: true,
		showClose: true,
		stepping: 30,
	}).on('dp.change', function(e) {
		$('#fechavisita').val(moment(e.date).format("YYYY-MM-DD H:mm:ss"));
	});


	function mail_install(url) {
		alerta('loader', 'Preparando Archivo...');
		$.post(url, {
				action: "sendmail"
			})
			.done(function(data) {
				$('#gritter-notice-wrapper').remove();
				alerta("exito", "Correo enviado correctamente.");
			});
	}

	function print_install(url) {
		alerta('loader', 'Preparando impresión...');
		$('#printframe').remove();
		var t = $("<iframe></iframe>");
		t.attr("id", "printframe").attr("name", "printframe").attr("src", "about:blank").css("width", "300").css("height", "100").css("position", "absolute").css("left", "-9999px").appendTo($("body:first"));
		null != t && null != url && (t.attr("src", url), t.on('load', function() {
			$('#gritter-notice-wrapper').remove();
			if (getChromeVersion() > 76) {
				t.get(0).contentWindow.print();
			}
		}))

	}

	var pre, installregistro;

	function delete_instalacion(id) {
		$.confirm({
			theme: 'supervan',
			draggable: false,
			closeIcon: true,
			animationBounce: 2.5,
			escapeKey: false,
			content: 'Todo los datos asociados a esta instalación se perderán.',
			title: '<i class="fa fa-question-circle fa-lg icodialog"></i> Eliminar',
			buttons: {
				Eliminar: {
					text: '<i class="far fa-trash-alt icodialog"></i> Eliminar', // With spaces and symbols
					action: function() {
						$.post("clientes/delete_instalacion", {
								id: id
							})
							.done(function(data) {
								if (data == "<p>ACCESO DENEGADO.</p>") {
									alerta('warning', 'No cuentas con los permisos necesarios.');
								} else if (data == 1) {
									alerta('exito', 'Registro eliminado correctamente');
									updatepre();
								} else {
									alerta('error', 'No se elimino correctamente, vuelva a intentarlo.');

								}
							});

					}
				}
			}
		});
	}

	function delete_install(id) {
		$.confirm({
			theme: 'supervan',
			draggable: false,
			closeIcon: true,
			animationBounce: 2.5,
			escapeKey: false,
			content: 'Está seguro que desea eliminar esta instalación,<b>Solo los Productos en Préstamo/Comodato serán devueltos al almacén.</b><br> Si existen mas equipos que desea retirar del cliente deberá eliminar con el boton "Agregar materiales" ',
			title: '<i class="fa fa-question-circle fa-lg icodialog"></i> Eliminar Instalación',
			buttons: {
				Eliminar: {
					text: '<i class="far fa-trash-alt icodialog"></i> Eliminar', // With spaces and symbols
					action: function() {
						$.post("clientes/delete_install", {
								id: id
							})
							.done(function(data) {
								if (data == "<p>ACCESO DENEGADO.</p>") {
									alerta('warning', 'No cuentas con los permisos necesarios.');
								} else if (data == 1) {
									alerta('exito', 'Registro eliminado correctamente');
									updateinstall();
								} else {
									alerta('error', 'No se elimino correctamente, vuelva a intentarlo.');

								}

							});

					}
				}
			}
		});
	}


	function aceptar_instalacion(id) {
		$.confirm({
			theme: 'supervan',
			draggable: false,
			closeIcon: true,
			animationBounce: 2.5,
			escapeKey: false,
			content: 'Esta seguro que dese enviar El pre-registro ID: <b>' + id + '</b> para su instalación',
			title: '<i class="fa fa-question-circle fa-lg icodialog"></i> Aceptar instalación',
			buttons: {
				aceptar: {
					text: 'Si, Aceptar instalación', // With spaces and symbols
					action: function() {
						$.post("clientes/update_pre_install", {
								id: id
							})
							.done(function(data) {
								alerta('exito', 'Operación exitosa');
								updatepre();

							});

					}
				}
			}
		});
	}

	function updatepre() {
		pre.ajax.reload(null, false);
	}


	function updateinstall() {
		installregistro.ajax.reload(null, false);
	}

	$.fn.dataTable.moment('D/MMM/YY HH:mm');
	$.fn.dataTable.moment('D/MMM/YY');

	configtable('#data-pre-registro', 'Instalaciones');
	pre = $('#data-pre-registro').DataTable({
		"ajax": base_url + "clientes/get_instalaciones_pre_json",
		"deferRender": true,
		"idDataTables": "1",
		"aoColumns": [{
				mData: 'id'
			},
			{
				mData: 'nombre'
			},
			{
				mData: 'sucursal'
			},
			{
				mData: 'zona'
			},
			{
				mData: 'fecha'
			},
			{
				mData: 'cedula'
			},
			{
				mData: 'instalacion'
			},
			{
				mData: 'telefono'
			},
			{
				mData: 'movil'
			},
			{
				mData: 'correo'
			},
			{
				mData: 'direccion'
			},
			{
				mData: 'notas'
			},
			{
				mData: 'tool',
				sClass: 'text-center'
			}
		],
		initComplete: function(oSettings, json) {
			if (<?= $tipoacceso; ?> >= 2) {
				$("#data-pre-registro_wrapper div.tooltablas")
					.html('<div class="grupotabla btn-group m-r-5">\
				<button data-toggle="modal" data-target="#modal_new" class="btn btn-default" ><i class="fas fa-plus"></i> <b>Nuevo</b></button>\
		</div>');
			}

		}
	}).on('draw', function() {
		$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
	});


	function edit_instalacion(id) {
		$.ajax({
				data: {
					"id": id
				},
				type: "POST",
				dataType: "json",
				url: base_url + "clientes/edit_instalacion",
			})
			.done(function(data, textStatus, jqXHR) {
				$('#modal_edit').modal();
				$('[name=id_edit]').val(data.id)
				$('[name=cliente_edit]').val(data.cliente)
				$('[name=direccion_edit]').val(data.direccion)
				$('[name=cedula_edit]').val(data.cedula)
				$('[name=telefono_edit]').val(data.telefono)
				$('[name=movil_edit]').val(data.movil)
				$('[name=email_edit]').val(data.email)
				$('[name=Netflix_Activo_edit]').val(data.Netflix_Activo)
				$('[name=Netflix_Datos_edit]').val(data.Netflix_Datos)
				$('[name=Camaras_edit]').val(data.Camaras)
				$('[name=TV_edit]').val(data.TV)
				$('[name=_Cableado_edit]').val(data._Cableado)
				$('[name=Wisp_edit]').val(data.Wisp)
				$('[name=Referencia1_edit]').val(data.Referencia1)
				$('[name=Referencia2_edit]').val(data.Referencia2)
				$('[name=Ejecutivo_edit]').val(data.Ejecutivo)
				$('[name=Actualizado_edit]').val(data.Actualizado)
				$('[name=Factibilidad_edit]').val(data.Factibilidad)
				$('[name=fechavisita_edit]').val(data.fecha_instalacion)
				$('[name=notas_edit]').val(data.notas)
				$('[name="zona_edit"] option[value="' + data.zona + '"]').prop('selected', true).change();

				/* $('#fechavisita_edit').val(data.fecha_instalacion);
				$('#fechavisita_edit').on('dp.change', function(e) {
						$('#fechavisita_edit').val(moment(e.date).format("YYYY-MM-DD H:mm:ss")); */
				//

			})
			.fail(function(jqXHR, textStatus, errorThrown) {
				if (console && console.log) {
					console.log("La solicitud a fallado: " + textStatus);
				}
			});
	}

	configtable('#data-install-registro', 'Instalaciones');
	installregistro = $('#data-install-registro').DataTable({
		"ajax": base_url + "clientes/get_instalaciones_json",
		"deferRender": true,
		"idDataTables": "1",
		"aoColumns": [{
				mData: 'id'
			},
			{
				mData: 'nombre'
			},
			{
				mData: 'sucursal'
			},
			{
				mData: 'zona'
			},
			{
				mData: 'fecha'
			},
			{
				mData: 'cedula'
			},
			{
				mData: 'instalacion'
			},
			{
				mData: 'telefono'
			},
			{
				mData: 'movil'
			},
			{
				mData: 'correo'
			},
			{
				mData: 'direccion'
			},
			{
				mData: 'notas'
			},
			{
				mData: 'estate'
			},
			{
				mData: 'tool',
				sClass: 'text-center'
			}
		],

	}).on('draw', function() {
		$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
	});


	function add_materials(id) {
		$(location).attr('href', "#clientes/add_materials?id=" + id);
	}

	function nuevo_cliente_pre(id) {
		$(location).attr('href', "#clientes/nuevo_cliente_pre?id=" + id);
	}
	//17 de sep
	function update_install(id) {
		$(location).attr('href', "#clientes/update_install?id=" + id);
	}

	App.restartGlobalFunction();
	App.setPageTitle('Instalaciones');
</script>