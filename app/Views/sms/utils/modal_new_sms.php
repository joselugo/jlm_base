<style type="text/css">
  .popover {
    z-index: 1090;
  }
</style>
<form id="sms-form" method="post" action="sms/new_sms" class="form-horizontal">
  <div class="modal-body" style="width: 100%">

    <div class="row">
      <div class="col-sm-4">
        <div class="form-group input-group-sm">

          <label>Estado Cliente</label>
          <select id="smsestado" name="smsestado" class="form-control slcorreo" style="width:100%">
            <option value="0">Cualquiera</option>
            <option value="ACTIVO">Activo</option>
            <option value="SUSPENDIDO">Suspendido</option>
          </select>
        </div>

        <div class='form-group input-group-sm'>
          <label>Seleccionar Grupo</label>
          <select class="form-control slcorreo" id="grupo" name="grupo" style="width:100%" data-placeholder="Seleccionar grupo">
            <option></option>

            <optgroup label="ROUTER">
              <option value="server_all">Cualquier Router</option>
              <?= $servers; ?>
            </optgroup>

            <optgroup label="EMISOR">
              <?= $emisores; ?>

            </optgroup>

            <optgroup label="UBICACIONES">

            </optgroup>

            <optgroup label="FACTURAS NO PAGADAS">

            </optgroup>

            <optgroup label="RED IPV4">

            </optgroup>

            <optgroup label="PLANES INTERNET">

            </optgroup>

          </select>
        </div>
        <hr>
        <label>Agregar Cliente</label>
        <select class="form-control" id="smscliente" name="smscliente" style="width:100%">
        </select>



        <div class="m-t-20 form-group input-group-sm">
          <a class="btn btn-white cssconter text-center" onclick="$('#collapsecorreo').slideToggle()" style="width:100%;font-size: 11px;padding: 5px 0px;">
            Total clientes (0)
          </a>
        </div>

        <div class="form-group input-group-sm">

          <a href="javascript:void(0)" class="taguser" data-container="body" data-toggle="popover" data-placement="right" data-content="EN DERARROLLO">Insertar datos con Tags <i class="fas fa-tags"></i> </a>

        </div>

      </div>

      <div class="col-sm-8">
        <label>Mensaje a enviar</label>
        <textarea name="newsms" class="form-control" rows="7" required onkeyup="maximosms(this,'400');"></textarea>
        <div class="counter p-5" style="text-align:center; width:100%">Caracteres <b>0/400</b></div>
      </div>
    </div>

    <div class="panel panel-inverse m-t-10" id="collapsecorreo" style="display: none">
      <div class="panel-heading">
        <h4 class="panel-title">Enviar a los clientes <span class="cssconter pull-right label label-success" style="font-size: 11px;"></span></h4>
      </div>
      <div class="panel-body listaclientes border">
        <div class="listacli row" style="max-height: 350px;overflow: auto;padding-top: 4px;"></div>
      </div>
    </div>


  </div>

  <div class="modal-footer">
    <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
    <button type="submit" class="btn btn-sm btn-success">Enviar SMS</button>
  </div>

</form>


<script>
  function conteruser() {
    if ($(".listaclientes input:checked").length > 0) {
      $('.cssconter').html('Total Clientes seleccionados (' + $(".listaclientes input:checked").length + ')');
    } else {
      $('.cssconter').html('Total Clientes seleccionados (0)');
    }
  }

  $(function() {

    $("#smscliente").select2({
      placeholder: "Seleccionar Cliente",
      minimumInputLength: 3,
      ajax: {
        url: function() {
          return 'sms/add_client';
        },
        dataType: "json",
        delay: 200,
        type: "GET",
        data: function(params) {

          var queryParameters = {
            term: params.term
          }
          return queryParameters;
        },
        processResults: function(data) {
          return {
            results: $.map(data, function(item) {
              return {
                text: item.nombre,
                id: item.id + '-' + item.movil
              }
            })
          };
        }
      }

    });

    $('#smscliente').on("select2:select", function(e) {
      var valsms = $(this).val().split("-");

      if (!valsms[1]) {
        alerta('error', 'El cliente ' + valsms[0] + ' no cuenta con un Nº móvil configurado.')
        return false;
      }
      if ($('input[name="smsadd[' + valsms[0] + ']"]').length) {
        alerta("error", "El cliente ya esta agregado");
        $('#smscliente').select2('val', '');
        $("#smscliente").html('');
      } else {
        $(".listacli").append('<div class="checkbox checkbox-css col-md-6 col-xs-6" style="margin-top: -5px;font-size: 12px;" onclick="conteruser()"><input type="checkbox" name="smsadd[' + valsms[0] + ']" value="' + valsms[1] + '" checked="" id="ch_sms_8"><label for="ch_sms_8">' + $("#smscliente option:selected").text() + '</label></div>');
        $('#smscliente').select2('val', '');
        $("#smscliente").html('');
        $('#smscliente').select2('open');
        conteruser();
        $('#collapsecorreo').show();
        $('#smscliente').select2('val', '');
        $("#smscliente").html('');
        $("#smscliente").select2('close');
      }

    });


    $('[data-toggle="popover"]').popover({
      html: true,
    })

    $("#grupo").change(function() {
      $(".listacli").html('<div><i class="fas fa-spinner fa-lg fa-pulse"></i> Generando lista de clientes</div></div>');
      $.post("sms/find_clients", {
          id: $('#grupo').val(),
          estado: $('#smsestado').val()
        })
        .done(function(data) {
          data = JSON.parse(data);
          $(".listacli").empty();
          if (data.estado == 'error') {
            alerta("error", 'No se econtro ningun cliente.');
            conteruser();
          } else {
            var cont = 0;
            $.each(data.salida, function() {
              $(".listacli").append('<div class="checkbox checkbox-css col-md-6 col-xs-6" style="margin-top: -5px;font-size: 12px;" onclick="conteruser()"><input type="checkbox" name="smsadd[' + this.id + ']" value="' + this.movil + '" checked id="ch_sms_' + cont + '"><label for="ch_sms_' + cont + '">' + this.nombre + '</label></div>');
              cont++;
            });
            conteruser();
          }

        });
    });

    $('#smsestado').change(function() {
      if ($("#grupo").val()) {
        $("#grupo").trigger('change');
      }
    });

    $('.slcorreo').select2();


    $('#sms-form').ajaxForm({
      success: function(data) {
        if (data == 1) {
          $('#gritter-notice-wrapper').remove();
          alerta('exito', 'SMS registrados correctamente');
          update_enviados();
        } else if (data == 2) {
          alerta('error', 'Error al enviar.');
        } else {
          alerta('warning', 'No cuentas los permisos requeridos.');
        }
      },
      beforeSubmit: function() {
        alerta('loader', 'Enviando SMS...');
        $('#modaltmp').modal('hide');
      },
      error: function(response) {
        $('#gritter-notice-wrapper').remove();
        alerta('error500', 'Error.');
      }
    })

    $('#modaltmp').modal('show');

    $('#modaltmp').on('hidden.bs.modal', function(e) {
      $("[data-toggle='popover']").popover('hide');
    })

  })
</script>