	<ol class="breadcrumb pull-right">
		<li class="breadcrumb-item"><a href="/admin">Inicio</a></li>
		<li class="breadcrumb-item"><a href="/admin/#ajax/ajustes">Ajustes</a></li>
		<li class="breadcrumb-item active">Cloud</li>
	</ol>

	<h1 class="page-header">Google</h1>

	<div class="row col-md-6">

		<form class="formin frm-google" action="ajustes/save_google" method="post">

			<div class="panel panel-inverse">
				<div class="panel-heading">
					<h4 class="panel-title">Claves Google</h4>
				</div>
				<div class="panel-body">

					<div class="form-group row">
						<label>Clave API google (Maps & street view)</label>
						<input value="Ajuste clave google editado" name="log" type="hidden">
						<input class="form-control" value="<?= $config['keyapigoogle']; ?>" name="ajuste[keyapigoogle]" type="text">
						<small>Para obtener su Clave API Google visite: <a href="https://console.cloud.google.com/freetrial?hl=es&page=0" target="_blank">Obtén una clave para API para JavaScript</a> </small>
					</div>


				</div>

				<div class="panel-footer text-right">
					<button type="submit" class="btn-azul btn-sm m-l-5"><i class="far fa-save"></i> Guardar cambios</button>
				</div>
			</div>

		</form>

	</div>

	<script>
		$(function() {

			$('.frm-google').ajaxForm({
				success: function(data) {
					$('#gritter-notice-wrapper').remove();
					if (data == 1) {
						alerta('exito', 'Datos guardados correctamente.');
					} else if (data == 2) {
						alerta('warning', 'Error.');
					} else {
						alerta('warning', 'Error.');
					}
				},
				error: function(response) {
					$('#gritter-notice-wrapper').remove();
					alerta('error500', "Error.");
				}
			})

			App.setPageTitle('Configuración');


		})
	</script>