<form id="servicio-form-edit" method="post" action="clientes/save_edit_servicio">
  <div class="modal-body row">
    <input type="hidden" name="equipo">
    <input type="hidden" name="idcliente" value="<?= $servicio->idcliente; ?>">
    <input type="hidden" name="idservicio" value="<?= $servicio->id; ?>">

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
          <select class="form-control sl2" name="servicio[nodo]" style="width:100%" data-placeholder="Seleccionar Router">
            <option></option>
            <?= $nodos; ?>
          </select>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-md-4 col-form-label text-right">Excluir Firewall</label>
        <div class="col-md-8">
          <input type="hidden" class="form-control" name="servicio[firewall_state]">
          <input type="checkbox" class="form-control chk" name="servicio[firewall_state]" <?= $servicio->firewall_state == "on" ? "checked" : ""; ?>>
        </div>
      </div>


      <div class="dataservice">
        <div class="form-group row">
          <label class="col-md-4 col-form-label text-right">Perfil Internet</label>
          <div class="col-md-8">
            <select class="form-control" name="servicio[idperfil]" style="width:100%" required data-placeholder="Seleccionar perfil">
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-4 col-form-label text-right">Descripción</label>
          <div class="col-md-8">
            <textarea class="form-control" name="servicio[descripcion]" style="padding-top: 7px;" required><?= $servicio->descripcion; ?>
            </textarea>
            <small>* Texto para facturación</small>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-4 col-form-label text-right">Costo</label>
          <div class="col-md-8">
            <input type="text" class="form-control" name="servicio[costo]" value="<?= $servicio->costo; ?>" required>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-4 col-form-label text-right">Tipo IPv4</label>
          <div class="col-md-8">
            <select class="form-control sl2" name="servicio[tipoipv4]" style="width:100%" required data-placeholder="Seleccionar">
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
            <input type="text" class="form-control" name="servicio[mac]" maxlength="17" value="<?= $servicio->mac; ?>">
          </div>
        </div>

        <div class="form-group row sppp" style="display:none">
          <label class="col-md-4 col-form-label text-right">User PPP/HS
            <button type="button" class="btn btn-icono btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="Generar usuario aleatorio" onclick="useraleatorio('servicio\[pppuser\]')"><i class="far fa-user"></i></button>
          </label>
          <div class="col-md-8">
            <input type="text" class="form-control" name="servicio[pppuser]" value="<?= $servicio->pppuser; ?>">
          </div>
        </div>

        <div class="form-group row sppp" style="display:none">
          <label class="col-md-4 col-form-label text-right">password PPP/HS
            <button type="button" class="btn btn-icono btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="Generar contraseña aleatoria" onclick="passwordaleatorio('servicio\[ppppass\]')"><i class="fas fa-lock"></i></button>
          </label>
          <div class="col-md-8">
            <input type="text" class="form-control" name="servicio[ppppass]" value="<?= $servicio->ppppass; ?>">
          </div>
        </div>

      </div>

    </div>

    <div class="dataservice col-lg-6">

      <div class="form-group row">
        <label class="col-md-3 col-form-label text-right">Dirección
          <button type="button" class="btn btn-icono btn-xs btn-map" data-toggle="tooltip" data-placement="top" data-original-title="Abrir google maps"><i class="fas fa-street-view"></i></button>
        </label>
        <div class="col-md-9">
          <input type="text" class="form-control" name="servicio[direccion]" value="<?= $servicio->direccion; ?>">
        </div>
      </div>

      <div class="form-group row">
        <label class="col-md-3 col-form-label text-right">Coordenadas
          <button type="button" class="btn btn-icono btn-xs btn-ubi" data-toggle="tooltip" data-placement="top" data-original-title="Obtener Ubicación Actual" data-loading-text="<i class='fas fa-spinner fa-spin'></i>" onClick="getubicacion()"><i class="fas fa-location-arrow"></i></button>
        </label>
        <div class="col-md-9">
          <input type="text" class="form-control" name="servicio[coordenadas]" value="<?= $servicio->coordenadas; ?>">
          <small>* Latitud,longitud</small>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-md-3 col-form-label text-right">Fecha Instalación</label>
        <div class="col-md-9">
          <div class="input-group">
            <div class="input-group-addon"><i class="far fa-calendar"></i></div>
            <input type="text" class="form-control" name="servicio[instalado]" readonly value="<?= date("d/m/Y", strtotime($servicio->instalado)); ?>">
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
            <input type="text" class="form-control no" name="servicio[ipap]" value="<?= $servicio->ipap; ?>" readonly>
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
            <input type="text" class="form-control no" name="servicio[snmp_comunidad]" value="<?= $servicio->snmp_comunidad; ?>">
          </div>
        </div>

        <div class="form-group row antena" style="display:none">
          <label class="col-md-3 col-form-label text-right">Usuario antena</label>
          <div class="col-md-9">
            <input type="text" class="form-control no" name="servicio[user_ubnt]" value="<?= $servicio->user_ubnt; ?>" readonly>
          </div>
        </div>

        <div class="form-group row antena" style="display:none">
          <label class="col-md-3 col-form-label text-right">Contraseña antena</label>
          <div class="col-md-9">
            <input type="password" class="no form-control" name="servicio[pass_ubnt]" value="<?= $servicio->pass_ubnt; ?>" readonly>
          </div>
        </div>

      </fieldset>
    </div>





  </div>

  <div class="modal-footer">
    <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
    <button type="submit" class="btn btn-sm btn-success btn-loader">Guardar cambios</button>
  </div>

</form>


<script>
  select = true;
  select2 = true;
  $('select[name="servicio\[nodo\]"] option[value="<?= $servicio->nodo; ?>"]').prop('selected', true);
  $('select[name="servicio\[sucursal\]"] option[value="<?= $servicio->sucursal; ?>"]').prop('selected', true);
  $('select[name="servicio\[emisor\]"] option[value="<?= $servicio->emisor; ?>"]').prop('selected', true);
  var firelchk = Array.prototype.slice.call(document.querySelectorAll('.chk'));
  firelchk.forEach(function(html) {
    var switchery = new Switchery(html, {
      size: 'small',
      color: 'rgb(18, 187, 168)'
    });
  });

  function getmac() {

    /*    $.post("ajax/viewuser?action=getmac&router=" + $('select[name="servicio\[nodo\]"]').val() + "&ip=" + $('select[name="servicio\[ip\]\[\]"]').val(), {
         user: $('input[name="servicio\[pppuser\]"]').val(),
       }).done(function(data) {
         if (data) {
           $('input[name="servicio\[mac\]"]').val(data);
         }
       });
    */
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

    $('#modaltmp').modal('show');
    $('select[name="servicio\[idperfil\]"]').select2();

    $('select[name="servicio\[antena\]"] option[value="<?= $servicio->antena; ?>"]').prop('selected', true);

    $('select[name="servicio\[nodo\]"]').change(function() {

      //-->Obtener los planes
      $('select[name="servicio\[idperfil\]"]').select2('destroy');
      $.post("clientes/get_planes", {
          nodo: $(this).val(),
        })
        .done(function(data) {
          $('select[name="servicio\[idperfil\]"]').html(data);
          if (select) {
            $('select[name="servicio\[idperfil\]"] option[value="<?= $servicio->idperfil; ?>"]').prop('selected', true);
            select = false;
          }
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


    $('select[name="servicio\[nodo\]"]').trigger('change');

    //-->Fija el tipo de red
    $('select[name="servicio\[tipoipv4\]"] option[value="<?= $servicio->tipoipv4; ?>"]').prop('selected', true);

    $(".sl2").select2({
      placeholder: "Seleccionar..."
    });

    $('select[name="servicio\[tipoipv4\]"]').change(function() {

      $('select[name="servicio\[redipv4\]"]').select2('destroy');
      $('select[name="servicio\[redipv4\]"]').html('').find('option').remove().end().append('<option></option>');

      if ($(this).val() < 2 && select == true) {
        $.post("clientes/grupo_ipv4_disponibles", {
          nodo: $('select[name="servicio\[nodo\]"]').val()
        }).done(function(data) {


          data = JSON.parse(data)
          if (data == 2) {
            alerta('error', 'Ocurrio un error al buscar las ips disponibles.');
          } else {
            data.forEach(v => {
              if (v.red) {
                ipsplit = v.red
                ipsplit = ipsplit.split(".")
                ipsplit = ipsplit[0] + "." + ipsplit[1] + "." + ipsplit[2] + "."
                if ("<?= $ip; ?>" == ipsplit) {
                  $('select[name="servicio\[redipv4\]"]').append('<option data-cidr="' + v.cidr + '" data-rango="' + v.rango + '" value="' + v.id + '" selected>' + v.red + '</option>');
                }
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

    $('select[name="servicio\[tipoipv4\]"]').trigger('change');


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
          console.log(data);
          i = 0;
          data.forEach(dato => {
            $('select[name="servicio\[ip\]\[\]"]').append('<option value="' + dato.ip + '">' + dato.ip + '</option>');
            i++
          });
          if (select2 == false) {
            $('select[name="servicio\[ip\]\[\]"] option[value="' + data[0].ip + '"]').prop('selected', true);
            select2 = false
          }

        });

        $('select[name="servicio\[tipoipv4\]"]').select2();
        $('select[name="servicio\[ip\]\[\]"]').select2({
          placeholder: "Seleccionar Ip disponible"
        });

      }
    });

    var contadorips;
    contadorips = setInterval(function() {
      if ($('select[name="servicio\[redipv4\]"]').find(':selected').data('cidr')) {
        clearInterval(contadorips);
        $('select[name="servicio\[redipv4\]"]').trigger('change');
        $('select[name="servicio\[ip\]\[\]"]').select2('destroy');
        $('select[name="servicio\[ip\]\[\]"]').append('<?= $ipselect; ?>');
        $('select[name="servicio\[ip\]\[\]"]').select2({
          placeholder: "Seleccionar Ip disponible"
        });
      }
    }, 100);

    $('input[name="servicio\[mac\]"]').triggerInputMac({
      upperCase: true,
      splitor: ":"
    });

    $('input[name="servicio\[instalado\]"]').datepicker({
      todayHighlight: true,
      autoclose: true,
      language: "es",
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

    $('select[name="servicio\[antena\]"]').trigger('change');





    $('#servicio-form-edit').ajaxForm({
      success: function(data) {
        $('#gritter-notice-wrapper').remove();
        if (data == 2) {
          alerta('error', 'Error al guardar el servicio.');
          btnloader('#servicio-form-edit', '<i class="far fa-save fa-lg"></i> Guardar cambios');
        } else {
          updateservicio();
          $('#modaltmp').modal('hide');
          alerta('exito', 'Servicio actualizado correctamente');
        }
      },
      beforeSubmit: function() {
        btnloader('#servicio-form-edit');
      },
      error: function(response) {
        $('#gritter-notice-wrapper').remove();
        alerta('error500', response['responseText']);
      }
    })






  })
</script>