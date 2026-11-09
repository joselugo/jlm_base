	<form id="perfil-password" method="post" action="seguridad/cambiar_mi_contrasenia" class="form-horizontal">
	  <div class="modal-body">

	    <div class="form-group">
	      <label class="control-label">Contraseña Antigua</label>
	      <input type="password" pattern=".{5,}" title="Mínimo 5 carácteres" class="form-control no" name="oldpaddpassword"
	        required autofocus autocomplete="off" readonly>
	    </div>


	    <div class="form-group">
	      <label class="control-label">Nueva contraseña</label>
	      <input type="password" pattern=".{5,}" title="Mínimo 5 carácteres" class="form-control no" name="newpassword"
	        required autocomplete="off" readonly>
	    </div>

	  </div>

	  <div class="modal-footer">
	    <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
	    <button type="submit" class="btn btn-sm btn-success">Cambiar</button>
	  </div>

	</form>


	<script>
$(function() {

  $('#perfil-password').ajaxForm({
    success: function(data) {
      $('#gritter-notice-wrapper').remove();
      if (data == 2) {
        alerta('error', "Ocurrio un error.");
      } else if (data == 3) {
        alerta('warning', "Contraseña antigua ingresada incorrecta.");
      }else if (data == 4) {
        alerta('warning', "Ingrese una contraseña diferente a la actual o a las antiguas.");
      } else {
        alerta('exito', 'Contraseña Actualizada');
        $('#modaltmp').modal('hide');
      }
    },
    error: function(response) {
      $('#gritter-notice-wrapper').remove();
      alerta('error500', "error");
    }
  })


  $('#modaltmp').modal('show');
  $('input[name="newpassword"]').password();
})
	</script>
