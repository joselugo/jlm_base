	<form id="otro-form" method="post" action="servicios/save_edit_personalizado" class="form-horizontal">
		<div class="modal-body">
			<input type="hidden" name="id" value="<?= $personalizado->id; ?>">
			<input type="hidden" name="otro[tipo]" value="otro">
			<div class="form-group row">
				<label class="col-md-3 control-label">Nombre</label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="otro[producto]" placeholder="<?= $personalizado->producto; ?>" required autofocus value="<?= $personalizado->producto; ?>">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-md-3 control-label">Precio</label>
				<div class="col-md-6">
					<input type="number" class="form-control" name="otro[costo]" placeholder="<?= $personalizado->costo; ?>" required value="<?= $personalizado->costo; ?>" step="0.01">
				</div>
			</div>

			<!--
			<div class="form-group row">
				<label class="col-md-3 control-label">Nuevo precio <i class="far fa-question-circle fa-lg" style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="" data-original-title="Al estar activo reemplaza el precio en todos los todos clientes que tenga activado este servicio de forma recurrente."></i></label>
				<div class="col-md-9">
					<input type="hidden" name="new_price" value="0">
					<label class="switch">
						<input class="chk" type="checkbox" name="new_price" value="1">
						<span class="slider round"></span>
					</label>

				</div>
			</div> -->



			<div class="form-group row">
				<label class="col-md-3 control-label">Impuesto (%)</label>
				<div class="col-md-6">
					<div class="input-group">
						<input type="text" name="otro[impuesto]" class="form-control" placeholder="<?= $personalizado->impuesto; ?>" required="" value="<?= $personalizado->impuesto; ?>" maxlength="5" pattern="^[0-9]\d*(\.\d+)?$" title="Ingrese un valor correcto para el impuesto">
						<span class="input-group-addon"><i class="fas fa-percent" aria-hidden="true"></i></span>
					</div>
				</div>
			</div>


			<div class="form-group row">
				<label class="col-md-3 control-label">Clave/Código SAT,SUNAT,etc.</label>
				<div class="col-md-6">
					<input type="text" class="form-control" name="otro[clave_invoice]" placeholder="<?= $personalizado->clave_invoice; ?>" value="<?= $personalizado->clave_invoice; ?>">
					<small class="text-warning">*Esto código es utilizado para Facturación Electrónica</small>
				</div>
			</div>


			<div class="form-group row">
				<label class="col-md-3 control-label">Descripción</label>
				<div class="col-md-9">
					<textarea class="form-control" name="otro[descripcion]" placeholder="<?= $personalizado->descripcion; ?>" required><?= $personalizado->descripcion; ?></textarea>
					<small>* Texto para facturación</small>
				</div>
			</div>

		</div>

		<div class="modal-footer">
			<a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
			<button style="display:<?= $permiso >= 2 ? "block" : "none"; ?>" type="submit" class="btn btn-sm btn-success btn-new">Registrar</button>
		</div>

	</form>


	<script>
		$(function() {

			$('#otro-form').ajaxForm({
				success: function(data) {
					if (data == 1) {
						$('#gritter-notice-wrapper').remove();
						alerta('exito', 'Servicio personalizado actualizado correctamente');
						updateperfilesotro();
					} else if (data == 2) {
						alerta('error', 'texto_error.');
					} else {
						alerta('warning', 'No cuentas los permisos requeridos.');
					}

				},
				beforeSubmit: function() {
					$('#modaltmp').modal('hide');
					alerta('loader', '');
				},
				error: function(response) {
					$('#gritter-notice-wrapper').remove();
					alerta('error500', 'Error');
				}
			})

			$('#modaltmp').modal('show');

			$('textarea').height($("textarea")[0].scrollHeight);
		})
	</script>