<h1 class="page-header">Base de datos</h1>

<div class="row">
	<div class="col-md-6">

		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title">Crear & Eliminar Campos</h4>
			</div>
			<div class="panel-body">
				<small style=" font-size: 11px">Aquí se puede crear nuevos campos para la tabla "usuarios", por ejemplo si deasamos tener un campo en los datos del cliente llamado "colonia", solo debemos indicar el nombre y crear. No utilice Espacios o caracteres especiales en el nombre del campo en vez de espacios podria utilizar "_" por ejemplo: nuevo_campo. </small><br>

				<small class="text-danger" style=" font-size: 11px"><b>¡Atención!</b> solo debe eliminar campos que usted Ha creado, eliminar cualquier otro campo podría provocar un mal funcionamiento del sistema </small><br><br>

				<div class="form-group float-left input-group-sm col-sm-8">
					<input class="form-control" id="nuevo_campo" name="nuevo_campo" type="text" autocomplete="off" placeholder="nuevo_campo">
				</div>

				<div class="form-group float-left input-group-sm col-sm-4">
					<button type="button" class="form-control btn btn-white btn-sm" onclick="newcampo()">Crear campo</button>
				</div>

				<div class="form-group float-left input-group-sm col-sm-8">
					<select class="form-control sl2" id="eliminar_campo" name="eliminar_campo" data-placeholder="Seleccionar campo" onChange="$('.btnremovecampo').prop('disabled', false);" style="width: 100%">
						<option></option>
						<?= $tblcolumns ?>
					</select>
				</div>

				<div class="form-group float-left input-group-sm col-sm-4">
					<button type="button" class="form-control btn btn-white btn-sm btnremovecampo" onClick="deletecampo()" disabled>Eliminar campo</button>
				</div>



			</div>
		</div>
	</div>

	<div class="col-md-6">

		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title">Eliminar datos</h4>
			</div>
			<div class="panel-body">
				<small style=" font-size: 11px">Aqui se puede vaciar los datos de una tabla y reiniciar la numeracion(ID), por ejemplo para borrar todos los usuarios seleccionar "usuarios".</small><br>

				<small class="text-danger" style=" font-size: 11px"><b>¡Atención!</b> Se debe tener cuidado de no eliminar los datos de tabla importantes. </small><br><br>


				<div class="form-group input-group-sm col-sm-8 float-left">
					<select class="form-control sl2" id="tablas" name="tablas" data-placeholder="Seleccionar tabla" onChange="$('.btnemptytable').prop('disabled', false);" style="width: 100%">
						<option></option>
						<?= $table_name ?>
					</select>
				</div>

				<div class="form-group input-group-sm col-sm-4 float-left">
					<button type="button" class="form-control btn btn-white btn-sm btnemptytable" onClick="emptytable()" disabled>Eliminar Datos</button>
				</div>



			</div>
		</div>
	</div>

	<div class="col-md-12">
		<div class="panel panel-inverse">
			<div class="panel-heading">
				<h4 class="panel-title">Tablas Base de datos
					<div class="pull-right">Tamaño <b>
							<?= $MB->Tamaño ?> (MB)
						</b></div>
				</h4>

			</div>
			<div class="panel-body">

				<table id="list-base" class="table table-striped table-bordered dt-responsive nowrap table-hover" data-order="[[ 2, &quot;desc&quot; ]]" width="100%">
					<thead>
						<tr>
							<th>Nombre de la Tabla</th>
							<th>Filas</th>
							<th>Tamaño</th>
						</tr>
					</thead>
				</table>

			</div>
		</div>
	</div>


</div>

<script>
	var base_url = "<?= base_url(); ?>"

	function deletecampo() {
		$.confirm({
			theme: 'supervan',
			draggable: false,
			closeIcon: true,
			animationBounce: 2.5,
			escapeKey: false,
			content: 'Está seguro de eliminar este campo personalizado? todos los datos guardados serán eliminados',
			title: '<i class="far fa-question-circle fa-lg icodialog"></i> Eliminar',
			buttons: {
				Eliminar: {
					text: '<i class="far fa-trash-alt icodialog"></i> Eliminar', // With spaces and symbols
					action: function() {
						$.post("bdatabase/delete_campo", {
								campo: $('#eliminar_campo').val(),
								action: "removecampo"
							})
							.done(function(data) {
								$('.sl2').select2('destroy');
								$('#eliminar_campo option[value=' + $('#eliminar_campo').val() + ']').remove();
								$('.btnremovecampo').prop('disabled', true);
								alerta('exito', 'Campo eliminado correctamente');
								$('#eliminar_campo option[value=""]').remove();
								$('.sl2').select2();

							});

					}
				}
			}
		});




	}

	function newcampo() {

		var input = $('#nuevo_campo');
		var re = /^[a-zA-Z_]*$/;
		var is_email = re.test(input.val());
		if (!is_email) {
			alerta('error', 'No ha indicado un nombre correcto.');
			return false;
		}

		if (!$('#nuevo_campo').val()) {
			alerta('error', 'No ha indicado un nombre correcto.');
			return false;
		}

		$.post("bdatabase/new_campo", {
				campo: $('#nuevo_campo').val(),
				action: "newcampo"
			})
			.done(function(data) {

				if (data['estado'] == 'error') {
					alerta('error', data['salida']);
				} else {
					$('.sl2').select2('destroy');
					$('#eliminar_campo').append($('<option>', {
						value: $('#nuevo_campo').val(),
						text: $('#nuevo_campo').val(),
					}));
					alerta('exito', 'Campo creado correctamente');
					$('.sl2').select2();
					$('#nuevo_campo').val('');
				}


			});
	}

	function emptytable() {
		$.confirm({
			theme: 'supervan',
			draggable: false,
			closeIcon: true,
			animationBounce: 2.5,
			escapeKey: false,
			content: 'Está seguro de reestablecer la tabla? todos los datos guardados serán eliminados',
			title: '<i class="far fa-question-circle fa-lg icodialog"></i> Eliminar',
			buttons: {
				Eliminar: {
					text: '<i class="far fa-trash-alt icodialog"></i> Eliminar', // With spaces and symbols
					action: function() {
						$.post("bdatabase/emptytable", {
								campo: $('#tablas').val(),
								action: "emptytable"
							})
							.done(function(data) {
								$('.sl2').select2('destroy');
								$('#tablas option[value=' + $('#tablas').val() + ']').remove();
								$('.btnremovecampo').prop('disabled', true);
								alerta('exito', 'Campo eliminado correctamente');
								$('#tablas option[value=""]').remove();
								$('.sl2').select2();
							});
					}
				}
			}
		});

	}


	$(function() {
		$('.sl2').select2();
		App.setPageTitle('Base Datos');

		configtable('#list-base', 'Base de datos');
		var zona = $('#list-base').DataTable({
			"ajax": base_url + "bdatabase/view_tables",
			"idDataTables": "4",
			"aoColumns": [{
					mData: 'table_name'
				},
				{
					mData: 'table_rows'
				},
				{
					mData: 'data_length'
				}
			],
			initComplete: function(oSettings, json) {
				$("#list-base_wrapper div.tooltablas")
					.html('<div class="grupotabla btn-group">\
					<button type="button" class="btn btn-info btn-loader" Onclick="downbd();"><i class="far fa-save fa-lg"></i> Descargar base de datos</button>\
					</div>');
			}
		});

	})

	function downbd(elm) {
		btnloader('.grupotabla');
		$.ajax({
			url: 'bdatabase/create_backup',
			method: 'GET',
			success: function(data) {
				data = JSON.parse(data);
				btnloader('.grupotabla', '<i class="far fa-save fa-lg"></i> Descargar base de datos');
				if (data.error == 2) {
					alerta('error', 'Error al generar Backup')
				} else {
					var a = document.createElement('a');
					a.href = base_url + 'bdatabase/descargar_backup?archivo=' + encodeURIComponent(data.salida);
					a.download = data.salida;
					a.click();
					a.remove();

				}
			}
		});
	}
</script>