	<form id="voip-form" method="post" action="clientes/newvoip">
	  <div class="modal-body row">
	    <input type="hidden" name="voip[cliente]" value="<?= $idcliente; ?>">
	    <div class="col-md-6">
	      <div class="form-group row">
	        <label class="col-md-4 col-form-label text-right">Plan</label>
	        <div class="col-md-8">
	          <select name="voip[idperfil]" class="form-control slvoip" style="width:100%;" required="" data-placeholder="Seleccionar plan">
	            <option></option>
	            <?= $perfiles_void; ?>
	          </select>
	        </div>
	      </div>
	    </div>


	    <div class="col-md-6">
	      <div class="form-group row">
	        <label class="col-md-4 col-form-label text-right">Costo</label>
	        <div class="col-md-8">
	          <input type="number" class="form-control" name="voip[costo]" required="" step="0.01">
	        </div>
	      </div>
	    </div>

	    <div class="col-md-6">
	      <div class="form-group row">
	        <label class="col-md-4 col-form-label text-right">Primary SIP Server</label>
	        <div class="col-md-8">
	          <input type="text" class="form-control" name="voip[server]">
	        </div>
	      </div>
	    </div>

	    <div class="col-md-6">
	      <div class="form-group row">
	        <label class="col-md-4 col-form-label text-right">SIP User ID</label>
	        <div class="col-md-8">
	          <input type="text" class="form-control" name="voip[sipuser]">
	        </div>
	      </div>
	    </div>

	    <div class="col-md-6">
	      <div class="form-group row">
	        <label class="col-md-4 col-form-label text-right">Authenticate ID</label>
	        <div class="col-md-8">
	          <input type="text" class="form-control" name="voip[sipid]">
	        </div>
	      </div>
	    </div>


	    <div class="col-md-6">
	      <div class="form-group row">
	        <label class="col-md-4 col-form-label text-right">Contraseña</label>
	        <div class="col-md-8">
	          <input type="text" class="form-control" name="voip[contrasena]">
	        </div>
	      </div>
	    </div>


	    <div class="col-md-6">
	      <div class="form-group row">
	        <label class="col-md-4 col-form-label text-right">Código de verificación</label>
	        <div class="col-md-8">
	          <input type="text" class="form-control" name="voip[codigoverificacion]">
	        </div>
	      </div>
	    </div>

	    <div class="col-md-6">
	      <div class="form-group row">
	        <label class="col-md-4 col-form-label text-right">Número Telefono</label>
	        <div class="col-md-8">
	          <input type="text" class="form-control" name="voip[numerotelefono]">
	        </div>
	      </div>
	    </div>

	    <div class="col-md-6">
	      <div class="form-group row">
	        <label class="col-md-4 col-form-label text-right">Fecha Instalación</label>
	        <div class="col-md-8">
	          <input type="text" class="form-control" name="voip[instalacion]" value="<?= $fecha; ?>">
	        </div>
	      </div>
	    </div>


	    <div class="col-md-6">
	      <div class="form-group row">
	        <label class="col-md-4 col-form-label text-right">Descripción <br><small>(Texto para factura.)</small></label>
	        <div class="col-md-8">
	          <textarea class="form-control" name="voip[descripcion]" style="height:50px"></textarea>
	        </div>
	      </div>
	    </div>

	    <div class="col-md-6">
	      <div class="form-group row">
	        <label class="col-md-4 col-form-label text-right">Notas</label>
	        <div class="col-md-8">
	          <textarea class="form-control" name="voip[notas]"></textarea>
	        </div>
	      </div>
	    </div>




	  </div>

	  <div class="modal-footer">
	    <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
	    <button type="submit" class="btn btn-sm btn-success btn-newrouter" data-loading-text="<i class='fas fa-spinner fa-spin'></i> registrando">Registrar</button>
	  </div>

	</form>


	<script>
	  $(function() {

	    $('.slvoip').change(function() {
	      $('input[name="voip\[costo\]"]').val($(this).find(':selected').data('costo'));
	      $('textarea[name="voip\[descripcion\]"]').val($(this).find(':selected').data('descripcion'));
	    });

	    $('.slvoip').select2();

	    $('#voip-form').ajaxForm({
	      success: function(data) {
	        $('#gritter-notice-wrapper').remove();
	        if (data['estado'] == 'error') {
	          alerta('error', data['salida']);
	        } else {
	          alerta('exito', data['salida']);
	          updatevoip();
	        }
	      },
	      beforeSubmit: function() {
	        $('#modaltmp').modal('hide');
	      },
	      error: function(response) {
	        $('#gritter-notice-wrapper').remove();
	        alerta('error500', response['responseText']);
	      }
	    })

	    $('#modaltmp').modal('show');


	  })
	</script>