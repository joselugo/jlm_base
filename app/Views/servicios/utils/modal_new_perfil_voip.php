	<form id="voip-form" method="post" action="servicios/save_new_perfil_voip" class="form-horizontal">
		<div class="modal-body">
			<div class="form-group row">
				<label class="col-md-3 control-label">Nombre</label>
				<div class="col-md-9">
					<input type="text" class="form-control" name="voip[plan]" placeholder="Plan minutos Libre" required autofocus value="">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-md-3 control-label">Precio</label>
				<div class="col-md-9">
					<input type="number" class="form-control" name="voip[costo]" placeholder="100" required value="" step="0.01">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-md-3 control-label">Impuesto (%)</label>
				<div class="col-md-4">
					<div class="input-group">
						<input type="text" name="voip[impuesto]" class="form-control" placeholder="19" required="" value="" maxlength="5" pattern="^[0-9]\d*(\.\d+)?$" title="Ingrese un valor correcto para el impuesto">
						<span class="input-group-addon"><i class="fas fa-percent" aria-hidden="true"></i></span>
					</div>
				</div>
			</div>


			<div class="form-group row">
				<label class="col-md-3 control-label">Minutos</label>
				<div class="col-md-4">
					<input type="text" class="form-control" name="voip[minutos]" placeholder="100" value="">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-md-3 control-label">Costo tel. Fijo</label>
				<div class="col-md-4">
					<input type="number" class="form-control" name="voip[valorfijo]" placeholder="100" value="" step="0.01">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-md-3 control-label">Costo tel. Móvil</label>
				<div class="col-md-4">
					<input type="number" class="form-control" name="voip[valormovil]" placeholder="100" value="" step="0.01">
				</div>
			</div>

			<div class="form-group row">
				<label class="col-md-3 control-label">Descripción</label>
				<div class="col-md-9">
					<textarea class="form-control" name="voip[descripcion]" placeholder="Servicio de voz minutos libres" required></textarea>
					<small>* Texto para facturación</small>
				</div>
			</div>

			<div class="form-group row">
				<label class="col-md-3 control-label">Notas</label>
				<div class="col-md-9">
					<textarea class="form-control" name="voip[notas]" placeholder="Nota"></textarea>
				</div>
			</div>


		</div>

		<div class="modal-footer">
			<a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
			<button type="submit" class="btn btn-sm btn-success btn-new">Registrar</button>
		</div>

	</form>


	<script>
		$(function() {

			$('#voip-form').ajaxForm({
				success: function(data) {
					if (data == 1) {
						$('#gritter-notice-wrapper').remove();
						alerta('exito', 'Servicio registrado correctamente.');
						updateperfilesvoip();
					} else if (data == 2) {
						alerta('error', 'Error al insertar.');
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
					alerta('error500', response['responseText']);
				}
			})

			$('#modaltmp').modal('show');

			$('textarea').height($("textarea")[0].scrollHeight);
		})
	</script>