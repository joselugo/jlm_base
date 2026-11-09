	<form id="emisor-form" method="post" action="gestion_red/save_new_emisor" class="form-horizontal">
	  <div class="modal-body" style="width: 100%">

	    <div class="col-sm-12">
	      <div class="form-group row">
	        <label class="col-md-4 control-label">Nombre Emisor</label>
	        <div class="col-md-8">
	          <input type="text" class="form-control" name="emisores[nombre]" placeholder="Emisor principal" required>
	        </div>
	      </div>
	    </div>

	    <div class="col-sm-12">
	      <div class="form-group row">
	        <label class="col-md-4 control-label">Direccion IP</label>
	        <div class="col-md-8">
	          <input type="text" class="form-control" name="emisores[ip]" placeholder="192.168.1.20" required>
	        </div>
	      </div>
	    </div>

	    <div class="col-sm-12">
	      <div class="form-group row">
	        <label class="col-md-4 control-label">Fabricante / Tipo</label>
	        <div class="col-md-8">
	          <select style="width: 100%;" class="form-control slemisor" name="emisores[fabricante]" id="tipoem">
	            <option value="0">MikroTik</option>
	            <option value="1">Ubiquiti M</option>
	            <option value="2">Ubiquiti AC</option>
	            <option value="3">Cambium</option>
	            <option value="4">TP-Link</option>
	            <option value="5">Mimosa</option>
	            <option value="10">Otro</option>
	          </select>
	        </div>
	      </div>
	    </div>

	    <div class="col-sm-12">
	      <div class="form-group row">
	        <label class="col-md-4 control-label">Modelo/Nombre</label>
	        <div class="col-md-8">
	          <input type="text" class="form-control" placeholder="RB 1100Ahx2" name="emisores[equipo]">
	        </div>
	      </div>
	    </div>

	    <div class="col-sm-12 user-data">
	      <div class="form-group row">
	        <label class="col-md-4 control-label">Usuario</label>
	        <div class="col-md-8">
	          <input type="text" class="form-control no" name="emisores[user]" placeholder="admin" readonly>
	        </div>
	      </div>
	    </div>

	    <div class="col-sm-12 user-data pass-mimosa">
	      <div class="form-group row">
	        <label class="col-md-4 control-label">Contraseña</label>
	        <div class="col-md-8">
	          <input type="password" class="form-control no" name="emisores[password]" placeholder="ubnt" readonly>
	        </div>
	      </div>
	    </div>


	    <div class="col-sm-12">
	      <div class="form-group row">
	        <label class="col-md-4 control-label">monitoreo SNMP</label>
	        <div class="col-md-8">
	          <input type="hidden" name="emisores[snmp]">
	          <input type="checkbox" class="form-control chemisor" name="emisores[snmp]">
	        </div>
	      </div>
	    </div>

	    <div class="col-sm-12">
	      <div class="form-group row">
	        <label class="col-md-4 control-label">Comunidad SNMP</label>
	        <div class="col-md-8">
	          <input type="text" class="form-control" name="emisores[snmp_comunidad]" placeholder="public">
	        </div>
	      </div>
	    </div>

	    <div class="col-sm-12">
	      <div class="form-group row">
	        <label class="col-md-4 control-label">Versi�n SNMP</label>
	        <div class="col-md-8">
	          <select style="width: 100%;" class="form-control slemisor" name="emisores[snmp_version]">
	            <option value="1">Version 1</option>
	            <option value="2">Version 2</option>
	          </select>
	        </div>
	      </div>
	    </div>


	  </div>

	  <div class="modal-footer">
	    <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
	    <button type="submit" class="btn btn-sm btn-success">Registrar</button>
	  </div>

	</form>


	<script>
	  $(function() {

	    $('#tipoem').change(function() {
	      if ($(this).val() == 2) {
	        $('.user-data').hide();
	      } else if ($(this).val() == 5) {
	        $('.user-data').hide();
	        $('.pass-mimosa').show();
	      } else {
	        $('.user-data').show();
	      }

	    });

	    $('.slemisor').select2();

	    var elems = Array.prototype.slice.call(document.querySelectorAll('.chemisor'));
	    elems.forEach(function(html) {
	      var switchery = new Switchery(html, {
	        size: 'small',
	        color: 'rgb(18, 187, 168)'
	      });
	    });

	    $('#emisor-form').ajaxForm({
	      success: function(data) {
	        $('#gritter-notice-wrapper').remove();
	        alerta('exito', 'Equipo registrado correctamente');
	        updateemisor();
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