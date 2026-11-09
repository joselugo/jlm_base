	<div class="modal-body form-horizontal" style="width: 100%">

	  <div class="form-group row">
	    <label class="col-md-3 control-label">IP</label>
	    <div class="col-md-5">
	      <select class="Select2 form-control" name="ip-ping">
	        <option value="<?= $ip; ?>"><?= $ip; ?></option>
	      </select>
	    </div>
	  </div>

	  <div class="form-group row">
	    <label class="col-md-3 control-label">Interface</label>
	    <div class="col-md-5">
	      <select class="Select2 form-control" name="interface-ping">
	        <?= $interfaces; ?>
	      </select>
	    </div>
	  </div>

	  <div class="form-group row">
	    <label class="col-md-3 control-label">ARP</label>
	    <div class="col-md-5">
	      <label class="switch">
	        <input class="chk" type="checkbox" name="arp-ping" value="yes">
	        <span class="slider round"></span>
	      </label>
	    </div>
	  </div>

	  <div class="form-group row">
	    <label class="col-md-3 control-label">Paquetes</label>
	    <div class="col-md-3">
	      <input type="hidden" name="nodo-ping" value="<?= $nodo; ?>">
	      <input type="text" class="form-control" name="paquete-ping" value="6">
	    </div>
	    <div class="col-md-6"><button type="button" class="btnping btn btn-default">Enviar ping</button></div>
	  </div>


	  <table id="grid-ping" class="table table-condensed table-bordered table-responsive" style="display:none">
	    <thead>
	      <tr>
	        <th style="background: #f5f5f5;">#</th>
	        <th style="background: #f5f5f5;">Host</th>
	        <th style="background: #f5f5f5;">Tamaño</th>
	        <th style="background: #f5f5f5;">TTL</th>
	        <th style="background: #f5f5f5;">Tiempo</th>
	      </tr>
	    </thead>
	    <tbody>

	    </tbody>
	  </table>


	</div>


	<script>
$(function() {

  $('.btnping').on('click', function() {
    $('.tooltip').remove();
    $('.btnping').html('<i class="fas fa-circle-notch fa-spin"></i> Enviando ping...').prop("disabled", true);
    var arp = "no";
    if ($('input[name="arp-ping"]').is(':checked')) {
      arp = "yes";
    }

    $('#grid-ping').slideUp();
    $.post("herramientas_mikrotik/ping", {
        ip: $('select[name="ip-ping"]').val(),
        nodo: $('input[name="nodo-ping"]').val(),
        interface: $('select[name="interface-ping"]').val(),
        paquete: $('input[name="paquete-ping"]').val(),
        arp: arp
      })
      .done(function(data) {

        $('.btnping').html('Enviar ping').prop("disabled", false);
        $('#grid-ping tbody').html(data);
        $('#grid-ping').slideDown();
      });
  });

  closeall();
  $('.tooltip').remove();
  $('#modaltmp').modal('show');
  $(".Select2").select2();

  $('.btnping').trigger('click');

})
	</script>
