	<form id="form-new-sms" method="post" action="clientes/new_message?id=<?= $id; ?>" class="formin form-horizontal">
	  <div class="modal-body" style="width: 100%;">
	    <div class="row">
	      <input type="hidden" name="numeros" value="<?= $numeros; ?>">

	      <div class="col-sm-12">
	        <label>Mensaje a enviar</label>
	        <textarea class="form-control" name="newsms" rows="4" onkeyup=" maximosms(this,'400');" required="" style="height: 120px !important"></textarea>
	        <div class="counter" style="text-align:center; width:100%">Caracteres <b>0/400</b></div>
	      </div>

	    </div>

	  </div>

	  <div class="modal-footer">
	    <button type="submit" class="btn-sm btn-azul btn-loader">Enviar SMS</button>
	  </div>

	</form>

	<script>
	  $(function() {

	    $('#form-new-sms').ajaxForm({
	      success: function(data) {
	        $('#gritter-notice-wrapper').remove();
	        if (data == 2) {
	          alerta('error', "Error al enviar el mensaje");
	        } else if (data == 1) {
	          $('a[href="#nav-tab-8"]').trigger('click');
	          alerta('exito', "Exito al enviar.");
	        } else if (data == 4) {
	          alerta("error", "Error al consultar las configuraciones de la base de datos.");
	        } else {
	          alerta("error", "Error con la API, contacte a su administrador.");
	        }
	        $('#modaltmp').modal('hide');

	      },
	      error: function(response) {
	        $('#gritter-notice-wrapper').remove();
	        alerta('error500', "Error");
	      }
	    })

	    $('#modaltmp').modal('show');

	  })
	</script>