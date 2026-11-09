<form id="ipv4-form" method="post" action="gestion_red/save_new_red_ipv4" class="form-horizontal">
  <div class="modal-body ">

    <div class="form-group row">
      <label class="col-md-3 control-label">Nombre</label>
      <div class="col-md-9">
        <input type="text" class="form-control" name="nombre" placeholder="nombre de red" required autofocus>
      </div>
    </div>

    <div class="form-group row">
      <label class="col-md-3 control-label">Router</label>
      <div class="col-md-9">
        <select style="width: 100%;" class="form-control Select2" name="nodo" required>
          <option></option>
          <?= $servers;?>
        </select>
      </div>
    </div>


    <div class="form-group row">
      <label class="col-md-3 control-label">Red</label>
      <div class="col-md-9">
        <div class="input-group">
          <div class="input-group-addon"><i class="fas fa-sitemap"></i></div>
          <input type="text" class="no form-control" name="red" required pattern="((^|\.)((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]?\d))){4}$" placeholder="Ejm: 192.168.1.0">
        </div>
        <small>Ejm: 192.168.1.0</small>
      </div>
    </div>


    <div class="form-group row">
      <label class="col-md-3 control-label">CIDR</label>
      <div class="col-md-9">
        <select name="cidr" class="form-control Select2" style="width: 100%">
          <optgroup label="Clase A">
            <option value="8">8 (255.0.0.0 - 16777214 hosts, 16777216 IP)</option>
            <option value="9">9 (255.128.0.0 - 8388606 hosts, 8388608 IP)</option>
            <option value="10">10 (255.192.0.0 - 4194302 hosts, 4194304 IP)</option>
            <option value="11">11 (255.224.0.0 - 2097150 hosts, 2097152 IP)</option>
            <option value="12">12 (255.240.0.0 - 1048574 hosts, 1048576 IP)</option>
            <option value="13">13 (255.248.0.0 - 524286 hosts, 524288 IP)</option>
            <option value="14">14 (255.252.0.0 - 262142 hosts, 262144 IP)</option>
            <option value="15">15 (255.254.0.0 - 131070 hosts, 131072 IP)</option>
          </optgroup>
          <optgroup label="Clase B">
            <option value="16">16 (255.255.0.0 - 65534 hosts, 65536 IP)</option>
            <option value="17">17 (255.255.128.0 - 32766 hosts, 32768 IP)</option>
            <option value="18">18 (255.255.192.0 - 16382 hosts, 16384 IP)</option>
            <option value="19">19 (255.255.224.0 - 8190 hosts, 8192 IP)</option>
            <option value="20">20 (255.255.240.0 - 4094 hosts, 4096 IP)</option>
            <option value="21">21 (255.255.248.0 - 2046 hosts, 2048 IP)</option>
            <option value="22">22 (255.255.252.0 - 1022 hosts, 1024 IP)</option>
            <option value="23">23 (255.255.254.0 - 510 hosts, 512 IP)</option>
          </optgroup>
          <optgroup label="Clase C">
            <option value="24" selected="">24 (255.255.255.0 - 254 hosts, 256 IP)</option>
            <option value="25">25 (255.255.255.128 - 126 hosts, 128 IP)</option>
            <option value="26">26 (255.255.255.192 - 62 hosts, 64 IP)</option>
            <option value="27">27 (255.255.255.224 - 30 hosts, 32 IP)</option>
            <option value="28">28 (255.255.255.240 - 14 hosts, 16 IP)</option>
            <option value="29">29 (255.255.255.248 - 6 hosts, 8 IP)</option>
            <option value="30">30 (255.255.255.252 - 2 hosts, 4 IP)</option>
          </optgroup>
        </select>

      </div>
    </div>

    <div class="form-group row">
      <label class="col-md-3 control-label">Tipo de Uso</label>
      <div class="col-md-9">
        <select style="width: 100%;" class="form-control Select2" name="tipo" required>
          <option></option>
        </select>
      </div>
    </div>


  </div>

  <div class="modal-footer">
    <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
    <button type="submit" class="btn btn-sm btn-azul btn-newipv4" data-loading-text="<i class='fas fa-spinner fa-spin'></i> registrando" disabled>Registrar</button>
  </div>

</form>


<script>
  $(function() {


    $('[name="nodo"]').change(function() {
      $('.btn-newipv4').prop('disabled', false);
      $('[name="tipo"]')[0].options.length = 0;
      var security = $(this).find(':selected').data('seguridad');
      if (security > 2) {
        $('[name="tipo"]').append('<option></option><option value="0">Estático</option><option value="1">Pool</option>');
      } else {
        $('[name="tipo"]').append('<option></option><option value="0">Estático</option>');
      }
    });

    $(".Select2").select2({
      placeholder: "Seleccionar..."
    });

    $('#ipv4-form').ajaxForm({
      success: function(data) {
        $('#gritter-notice-wrapper').remove();
        if (data==2) {
          alerta('error', "Error al agregar.");
        } else {
          updateipv4();
          alerta('exito', "Exito al agregar.");
        }
      },
      beforeSubmit: function() {
        $('#modaltmp').modal('hide');
        alerta('loader', '');
      },
      error: function(response) {
        $('#gritter-notice-wrapper').remove();
        alerta('error500', "Error");
      }
    })

    $('#modaltmp').modal('show');


  })
</script>