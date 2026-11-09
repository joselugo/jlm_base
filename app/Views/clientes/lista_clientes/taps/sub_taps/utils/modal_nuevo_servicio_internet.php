<form id="servicio-form" method="post" action="clientes/new_otro_servicio">
  <div class="modal-body row">

    <input type="hidden" name="servicio[idcliente]" value="<?= $id; ?>">

    <div class="col-lg-6" style="min-height:150px">

      <div class="form-group row">
        <label class="col-md-4 col-form-label text-right">Sucursal</label>
        <div class="col-md-8">
          <select class="form-control sl2" name="servicio[sucursal]" style="width:100%" data-placeholder="Seleccionar una sucursal" required>
            <option></option>
            <?= $sucursales; ?>
          </select>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-md-4 col-form-label text-right">Router</label>
        <div class="col-md-8">
          <select class="form-control sl2" name="servicio[nodo]" style="width:100%" data-placeholder="Seleccionar Router" required>
            <option></option>
          </select>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-md-4 col-form-label text-right">Excluir Firewall</label>
        <div class="col-md-8">
          <input type="hidden" class="form-control" name="servicio[firewall_state]">
          <input type="checkbox" class="form-control chk" name="servicio[firewall_state]">
        </div>
      </div>


      <div class="dataservice">
        <div class="form-group row">
          <label class="col-md-4 col-form-label text-right">Perfil Internet</label>
          <div class="col-md-8">
            <select class="form-control sl2" name="servicio[idperfil]" style="width:100%" required data-placeholder="Seleccionar plan">
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-4 col-form-label text-right">Descripción</label>
          <div class="col-md-8">
            <textarea class="form-control" name="servicio[descripcion]" style="padding-top: 7px;" required></textarea>
            <small>* Texto para facturación</small>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-4 col-form-label text-right">Costo</label>
          <div class="col-md-8">
            <input type="text" class="form-control" name="servicio[costo]" required>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-4 col-form-label text-right">Tipo IPv4</label>
          <div class="col-md-8">
            <select class="form-control sl2" name="servicio[tipoipv4]" style="width:100%" required data-placeholder="Tipo de IP">
              <option></option>
              <option value="0">IP fija (Estática)</option>
              <option value="1">IP dinámica (Pool IPs)</option>
            </select>
          </div>
        </div>

        <div class="form-group row sred" style="display:none">
          <label class="col-md-4 col-form-label text-right lblred">Redes IPv4</label>
          <div class="col-md-8">
            <select class="form-control sl2" name="servicio[redipv4]" style="width:100%" required>
              <option></option>
            </select>
          </div>
        </div>


        <div class="form-group row sip" style="display:none">
          <label class="col-md-4 col-form-label text-right">IPv4</label>
          <div class="col-md-8">
            <select class="form-control sl2" name="servicio[ip][]" style="width:100%" multiple="multiple">
              <option></option>
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-4 col-form-label text-right">Mac
            <button type="button" class="btn btn-icono btn-xs btnmac" data-toggle="tooltip" data-placement="top" data-original-title="Obtener Mac" onclick="getmac()"><i class="fas fa-question"></i></button>
          </label>
          <div class="col-md-8">
            <input type="text" class="form-control" name="servicio[mac]" maxlength="17">
          </div>
        </div>

        <div class="form-group row sppp" style="display:none">
          <label class="col-md-4 col-form-label text-right">User PPP/HS
            <button type="button" class="btn btn-icono btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="Generar usuario aleatorio" onclick="useraleatorio('servicio\[pppuser\]')"><i class="far fa-user"></i></button>
          </label>
          <div class="col-md-8">
            <input type="text" class="form-control" name="servicio[pppuser]" value="">
          </div>
        </div>

        <div class="form-group row sppp" style="display:none">
          <label class="col-md-4 col-form-label text-right">password PPP/HS
            <button type="button" class="btn btn-icono btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="Generar contraseña aleatoria" onclick="passwordaleatorio('servicio\[ppppass\]')"><i class="fas fa-lock"></i></button>
          </label>
          <div class="col-md-8">
            <input type="text" class="form-control" name="servicio[ppppass]">
          </div>
        </div>

      </div>

    </div>

    <div class="dataservice col-lg-6">

      <div class="form-group row">
        <label class="col-md-3 col-form-label text-right">Dirección
          <button type="button" class="btn btn-icono btn-xs btn-map" data-toggle="tooltip" data-placement="top" data-original-title="Abrir google maps"><i class="fas fa-street-view"></i></button>
        </label>
        </label>
        <div class="col-md-9">
          <input type="text" class="form-control" name="servicio[direccion]" value="">
        </div>
      </div>

      <div class="form-group row">
        <label class="col-md-3 col-form-label text-right">Coordenadas
          <button type="button" class="btn btn-icono btn-xs btn-ubi" data-toggle="tooltip" data-placement="top" data-original-title="Obtener Ubicación Actual" data-loading-text="<i class='fas fa-spinner fa-spin'></i>" onClick="getubicacion()"><i class="fas fa-location-arrow"></i></button>
        </label>
        <div class="col-md-9">
          <input type="text" class="form-control" name="servicio[coordenadas]">
          <small>* Latitud,longitud</small>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-md-3 col-form-label text-right">Fecha Instalación</label>
        <div class="col-md-9">
          <div class="input-group">
            <div class="input-group-addon"><i class="far fa-calendar"></i></div>
            <input type="text" class="form-control" name="servicio[instalado]" readonly value="<?= $fecha; ?>">
          </div>
        </div>
      </div>

      <fieldset class="form-group m-t-20">
        <legend>EQUIPO RECEPTOR</legend>

        <div class="form-group row">
          <label class="col-md-3 col-form-label text-right">Conectado A</label>
          <div class="col-md-9">
            <select class="form-control sl2" name="servicio[emisor]" style="width:100%">
              <option value="0">Ninguno</option>
              <?= $emisores; ?>
            </select>
          </div>
        </div>


        <div class="form-group row">
          <label class="col-md-3 col-form-label text-right">IP administración</label>
          <div class="col-md-9">
            <input type="text" class="form-control no" name="servicio[ipap]" readonly>
            <small>* Ip antena/Equipo del cliente</small>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 col-form-label text-right">Tipo antena</label>
          <div class="col-md-9">
            <select class="form-control sl2" name="servicio[antena]" style="width:100%">
              <option value="0">Otro</option>
              <option value="1">Ubiquiti® M</option>
              <option value="2">Ubiquiti® AC</option>
              <option value="3">Mikrotik</option>
              <option value="4">Cambium Networks (ePMP)</option>
            </select>
          </div>
        </div>

        <div class="form-group row antena" style="display:none">
          <label class="col-md-3 col-form-label text-right">Comunidad SNMP</label>
          <div class="col-md-9">
            <input type="text" class="form-control no" name="servicio[snmp_comunidad]">
          </div>
        </div>

        <div class="form-group row antena" style="display:none">
          <label class="col-md-3 col-form-label text-right">Usuario antena</label>
          <div class="col-md-9">
            <input type="text" class="form-control no" name="servicio[user_ubnt]" readonly>
          </div>
        </div>

        <div class="form-group row antena" style="display:none">
          <label class="col-md-3 col-form-label text-right">Contraseña antena</label>
          <div class="col-md-9">
            <input type="password" class="form-control no" name="servicio[pass_ubnt]" readonly>
          </div>
        </div>

      </fieldset>

    </div>


  </div>

  <div class="modal-footer">
    <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
    <button type="submit" class="btn btn-sm btn-success btn-newservice btn-loader">Registrar</button>
  </div>

</form>


<script>
  var firelchk = Array.prototype.slice.call(document.querySelectorAll('.chk'));
  firelchk.forEach(function(html) {
    var switchery = new Switchery(html, {
      size: 'small',
      color: 'rgb(18, 187, 168)'
    });
  });

  function getmac() {
    /*   $.post("ajax/viewuser?action=getmac&router=" + $('select[name="servicio\[nodo\]"]').val() + "&ip=" + $('select[name="servicio\[ip\]\[\]"]').val(), function(data) {
        if (data) {
          $('input[name="servicio\[mac\]"]').val(data);
        }
      }); */
  }

  $('.btn-map').on('click', function() {
    $.post("clientes/modal_mapa", {
        router: $('select[name="servicio\[nodo\]"]').val()
      })
      .done(function(datas) {
        $('#tmp2').html(datas);
      });
  });

  $(function() {
    passwordaleatorio('servicio\[ppppass\]');

    $('input[name="servicio\[mac\]"]').triggerInputMac({
      upperCase: true,
      splitor: ":"
    });

    $('input[name="servicio\[instalado\]"]').datepicker({
      todayHighlight: true,
      autoclose: true,
      language: "es",
    });
    $('select[name="servicio\[sucursal\]"]').change(function() {
      $('select[name="servicio\[nodo\]"]').select2('destroy');
      $.post("clientes/get_nodos_por_sucursal", {
          sucursal: $(this).val(),
        })
        .done(function(data) {
          $('select[name="servicio\[nodo\]"]').html(data);
          $('select[name="servicio\[nodo\]"]').select2();
        });
    })

    $('select[name="servicio\[nodo\]"]').change(function() {

      //-->Obtener los planes
      $('select[name="servicio\[idperfil\]"]').select2('destroy');
      $.post("clientes/get_planes", {
          nodo: $(this).val(),
        })
        .done(function(data) {
          $('select[name="servicio\[idperfil\]"]').html(data);
          $('select[name="servicio\[idperfil\]"]').select2();
        });

      //-->Seguridad alterna
      if ($(this).find(':selected').data('tipo2') > 0 && $(this).find(':selected').data('tipo2') !== 3) {
        $('input[name="servicio\[mac\]"]').prop('required', true);
      } else {
        $('input[name="servicio\[mac\]"]').prop('required', false);
      }

      if ($(this).find(':selected').data('tipo') > 2) {
        $('select[name="servicio\[tipoipv4\]"]').html('<option></option><option value="0">IP fija (Estática)</option><option value="1">IP dinámica (Pool IPs)</option>');
      } else {
        $('select[name="servicio\[tipoipv4\]"]').html('<option></option><option value="0">IP fija (Estática)</option>');
      }

      $('.sip,.sred').slideUp();
      $('.sppp').hide();
      if ($(this).find(':selected').data('tipo') == 1 || $(this).find(':selected').data('tipo') == 4) {
        $('.sppp').show();
      }

      if ($(this).find(':selected').data('tipo') == 2 || $(this).find(':selected').data('tipo') == 3) {
        $('.sppp').show();
      }


      autosize(document.querySelectorAll('textarea'));
    });

    $('select[name="servicio\[idperfil\]"]').change(function() {
      $('input[name="servicio\[costo\]"]').val($(this).find(':selected').data('costo'));
      $('textarea[name="servicio\[descripcion\]"]').val($(this).find(':selected').data('descripcion')).height('0');
      $('textarea[name="servicio\[descripcion\]"]').height($("textarea")[0].scrollHeight);
    });

    $('select[name="servicio\[antena\]"]').change(function() {
      if ($(this).val() > 0) {
        $('.antena').slideDown();
      } else {
        $('.antena').slideUp();
      }
    });

    $('select[name="servicio\[tipoipv4\]"]').change(function() {

      $('select[name="servicio\[redipv4\]"]').select2('destroy');
      $('select[name="servicio\[redipv4\]"]').html('').find('option').remove().end().append('<option></option>');

      if ($(this).val() < 2) {
        $.post("clientes/grupo_ipv4_disponibles", {
          nodo: $('select[name="servicio\[nodo\]"]').val()
        }).done(function(data) {
          data = JSON.parse(data)
          if (data == 2) {
            alerta('error', 'Ocurrio un error al buscar las ips disponibles.');
          } else {
            data.forEach(v => {
              if (v.red) {
                $('select[name="servicio\[redipv4\]"]').append('<option data-cidr="' + v.cidr + '" data-rango="' + v.rango + '" value="' + v.id + '">' + v.red + '</option>');
              }
            });
          }
        });
      }

      $('.sred').slideDown();

      $('select[name="servicio\[ip\]\[\]"]').select2('destroy');

      if ($(this).val() == 1) {
        $('.lblred').text('Pool IPv4');
        $('.sip').slideUp();
        $('.btnmac').prop('disabled', true);

        $('select[name="servicio\[ip\]\[\]"]').find('option').remove().end().append('<option></option>');
        $('select[name="servicio\[ip\]\[\]"]').prop('required', false);
        $('select[name="servicio\[redipv4\]"]').prop('required', true);

      } else if ($(this).val() == 0) {
        $('.lblred').text('Redes IPv4');
        $('.btnmac').prop('disabled', false);
        $('select[name="servicio\[ip\]\[\]"],select[name="servicio\[redipv4\]"]').prop('required', true);
      } else {
        $('.sip,.sred').slideUp();
        $('select[name="servicio\[ip\]\[\]"],select[name="servicio\[redipv4\]"]').prop('required', false);
      }

      $('select[name="servicio\[ip\]\[\]"],select[name="servicio\[redipv4\]"]').select2({
        placeholder: "Seleccionar..."
      });

    });

    $('select[name="servicio\[redipv4\]"]').change(function() {
      if ($('select[name="servicio\[tipoipv4\]"]').val() == 0) {
        $('.sip').slideDown();

        $('select[name="servicio\[ip\]\[\]"]').select2('destroy');
        $('select[name="servicio\[ip\]\[\]"]').find('option').remove().end().append('<option></option>');

        $.post("clientes/ipv4_disponibles", {
          id: $('select[name="servicio\[redipv4\]"]').val(),
          nodo: $('select[name="servicio\[nodo\]"]').val(),
          cidr: $(this).find(':selected').data('cidr'),
          red: $(this).find(':selected').data('rango')
        }).done(function(data) {
          data = JSON.parse(data)

          i = 0;
          data.forEach(dato => {
            $('select[name="servicio\[ip\]\[\]"]').append('<option value="' + dato.ip + '">' + dato.ip + '</option>');
            i++
          });
          $('select[name="servicio\[ip\]\[\]"] option[value="' + data[0].ip + '"]').prop('selected', true);

        });

        $('select[name="servicio\[ip\]\[\]"]').select2({
          placeholder: "Seleccionar Ip disponible"
        });

      }
    });


    $('#servicio-form').ajaxForm({
      success: function(data) {
	    data = JSON.parse(data);
        $('#gritter-notice-wrapper').remove();
        btnloader('#servicio-form', '<i class="far fa-save fa-lg"></i> Registrar');
	if (data.mensaje != null) {
            alerta('warning', data.mensaje);
          }        
	if (data.estado == 2) {
          alerta('error', 'Error al agregar el servicio.');
        } else {
            alerta('exito', 'Servicio agregado correctamente');
          updateservicio();
          $('#modaltmp').modal('hide');
        }    
       },
      beforeSubmit: function() {
        btnloader('#servicio-form');
      },
      error: function(response) {
        btnloader('#servicio-form', '<i class="far fa-save fa-lg"></i> Registrar');
        $('#gritter-notice-wrapper').remove();
        alerta('error500', response['responseText']);
      }
    })

    $(".sl2").select2({
      placeholder: "Seleccionar..."
    });


    $('#modaltmp').modal('show');

  })
</script>