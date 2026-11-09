<br>
<form method="post" class="form-horizontal row" id="Router-edit-configuracion" action="fiber/save_server_general">
  <input type="hidden" name="id" value="<?= $server->id; ?>">
  <div class="col-md-6">

    <div class="form-group row">
      <label class="col-md-4 control-label">Nombre OLT</label>
      <div class="col-md-8">
        <input type="text" class="form-control" placeholder="Nombre de la olt" name="nombre" value="<?= $server->nombre; ?>" required />
      </div>
    </div>

    <div class="form-group row">
      <label class="col-md-4 control-label">Ip</label>
      <div class="col-md-8">
        <input type="text" class="form-control" placeholder="Ip de la olt" name="ip" value="<?= $server->ip; ?>" required />
      </div>
    </div>

    <div class="form-group row">
      <label class="col-md-4 control-label">Ubicación
        <button type="button" class="btn btn-icono btn-xs btn-map" data-toggle="tooltip" data-placement="top" title="Abrir Google maps"><i class="fas fa-street-view"></i></button>
      </label>
      <div class="col-md-8">
        <input type="text" class="form-control" placeholder="Coordenadas Latitud,Longitud " name="coordenadas" value="<?= $server->coordenadas; ?>" />
      </div>
    </div>


    <div class="form-group row modelo">
      <label class="col-md-4 control-label">Modelo</label>
      <div class="col-md-8">
        <select style="width: 100%;" class="form-control Select2" name="modelo">
          <?= $modelos_olt ?>
        </select>
      </div>
    </div>

    <div class="form-group row version">
      <label class="col-md-4 control-label">Versión</label>
      <div class="col-md-8">
        <select style="width: 100%;" class="form-control Select2" name="version">
          <option value="R019">R008</option>
          <option value="R009">R009</option>
          <option value="R011">R011</option>
          <option value="R013">R013</option>
          <option value="R014">R014</option>
          <option value="R015">R015</option>
          <option value="R016">R016</option>
          <option value="R017">R017</option>
          <option value="R018">R018</option>
          <option value="R019">R019</option>
        </select>
      </div>
    </div>

    <div class="form-group row version">
      <label class="col-md-4 control-label">Tipo Pon</label>
      <div class="col-md-8">
        <select style="width: 100%;" class="form-control Select2" name="pon">
          <option value="GPON" selected="">GPON</option>

          <option value="EPON">EPON</option>

          <option value="GPON+EPON">GPON+EPON</option>

          <option value="XGPON">XGPON</option>

          <option value="XGPON+GPON">XGPON+GPON</option>
        </select>
      </div>
    </div>

    <div class="form-group row">
      <label class="col-md-4 control-label">Puerto Telnet</label>
      <div class="col-md-8">
        <input type="text" class="form-control" name="port_telnet" value="<?= $server->port_telnet; ?>" />
      </div>
    </div>

    <div class="form-group row">
      <label class="col-md-4 control-label">Puerto SNMP</label>
      <div class="col-md-8">
        <input type="text" class="form-control" name="port_snmp" value="<?= $server->port_snmp; ?>" />
      </div>
    </div>

    <div class="form-group row radiusdv">
      <label class="col-md-4 control-label">Comunidad SNMP lectura
        <button type="button" class="btn btn-icono btn-xs" data-toggle="tooltip" data-placement="top" title="Generar contraseña" onClick="passwordaleatorio('comunidad_lectura')"><i class="fas fa-lock"></i></button>
      </label>
      <div class="col-md-8">
        <input type="text" class="form-control" name="comunidad_lectura" value="<?= $server->comunidad_lectura; ?>" />
      </div>
    </div>

    <div class="form-group row radiusdv">
      <label class="col-md-4 control-label">Comunidad SNMP escritura
        <button type="button" class="btn btn-icono btn-xs" data-toggle="tooltip" data-placement="top" title="Generar contraseña" onClick="passwordaleatorio('comunidad_escritura')"><i class="fas fa-lock"></i></button>
      </label>
      <div class="col-md-8">
        <input type="text" class="form-control" name="comunidad_escritura" value="<?= $server->comunidad_escritura; ?>" />
      </div>
    </div>



  </div>

  <div class="col-md-6">
    <h4 style="width:100%; text-align:center"><b>CREDENCIALES OLT</b></h4>

    <div class="form-group row">
      <label class="col-md-4 control-label">Usuario</label>
      <div class="col-md-8">
        <input type="text" class="form-control" placeholder="Usuario API" name="user" value="<?= $server->user; ?>" required autocomplete="username" />
      </div>
    </div>

    <div class="form-group row">
      <label class="col-md-4 control-label">Contraseña</label>
      <div class="col-md-8">
        <input type="password" data-toggle="password" data-placement="after" class="no form-control" readonly placeholder="Contraseña API" name="pass" value="<?= $server->pass; ?>" required autocomplete="current-password" />
      </div>
    </div>

    <div class="form-group row">
      <div class="col-md-4"></div>
      <div class="col-md-8">
        <div class="card">
          <div class="card-header"><i class="fa fa-info-circle"></i> Vista Previa Modelo:<br>
            <label id="id_label_hardware_version"><?= $server->modelo_name ?></label>
          </div>
          <div class="card-body justify-content-center mt-2" style="padding-left: 0px; padding-top: 0px; padding-right: 0px; padding-bottom: 0px;">
            <img id="id_image_hardware_version" src="<?= base_url() ?>public/images/olt_image/<?= $server->image_olt ?>" alt="" width="100%">
          </div>
        </div>

      </div>

    </div>

    <br>
    <div class="form-group text-right">
      <div class="alert alert-danger fade show m-b-10 alerta-proceso" style="display: none">
        <b>ATENCIÓN: </b> Se esta realizando cambios masivos en su router,No podrá editar el router hasta terminar con
        esta tarea.
        Puede seguir operando otras secciones del sistema.
      </div>
      <button <?= $permiso < 2 ? "style=\"display:none\"" : ""; ?> type="button" class="btn-loader btn btn-success guardar"><i class="far fa-save"></i> Guardar</button>

      <button <?= $permiso < 2 ? "style=\"display:none\"" : ""; ?> type="button" class="btn-loader btn btn-success guardar_c"><i class="far fa-save"></i> Guardar y Comprobar Conexión</button>

      <input type="hidden" class="form-control" name="action">
      <input type="hidden" class="form-control" name="id" value="<?= $server->id ?>" />

    </div>
  </div>

</form>


<script>
  $('.guardar').on('click', function() {
    alerta('loader', 'Guardando Cambios.');
    $('[name="action"]').val("guardar");
    datos = $("#Router-edit-configuracion").serialize();
    $.post("fiber/save_olt_general", datos)
      .done(function(data) {
        $('#gritter-notice-wrapper').remove();
        if (data == 1) {
          alerta('exito', 'Cambios realizados.');
        } else if (data == 2) {
          alerta('error', 'Error no se pudo actualizar los datos.');
        } else {
          alerta('warning', 'No tienes permiso para realizar esta accion.');
        }

      })
      .fail(function() {
        $('#gritter-notice-wrapper').remove();
      });
  });


  $('.guardar_c').on('click', function() {
    alerta('loader', 'Guardando y Conectando.');
    $('[name="action"]').val("guardar_c");
    datos = $("#Router-edit-configuracion").serialize();
    $.post("fiber/save_olt_general", datos)
      .done(function(data) {

        $('#gritter-notice-wrapper').remove();

        var response = jQuery.parseJSON(data);
        if (response.result_telnet_con == "telnet-ok") {
          alerta('exito', 'Conexión TELNET OK.');
        } else if (response.result_telnet_con == "telnet-limit") {
          alerta('error', 'Haz alcanzado el límite de conexiones por TELNET.');
        } else if (response.result_telnet_con == "telnet-credenciales") {
          alerta('error', 'Usuario o contraseña incorrecto TELNET.');
        } else {
          alerta('error', 'Error de conexión TELNET.');
        }

        if (response.result_snmp_con == "snmp-ok") {
          alerta('exito', 'Conexión SNMP OK.');
        } else {
          alerta('error', 'Error de conexión SNMP.');
        }



      })
      .fail(function() {
        $('#gritter-notice-wrapper').remove();
      }, "json");
  });




  $('.btn-map').on('click', function() {
    $.post("ajax/router", {
        action: "mapa"
      })
      .done(function(datas) {
        $('#tmp').html(datas);
      });
  });

  $("[name=modelo]").change(function() {
    image = $("[name=modelo] option:selected").attr("data-image");
    name_modelo = $("[name=modelo] option:selected").html();
    $('#id_image_hardware_version').attr('src', '<?= base_url() ?>public/images/olt_image/' + image);
    $('#id_label_hardware_version').html(name_modelo);;
  });



  $(function() {

    $('[name="modelo"] option[value="<?= $server->modelo; ?>"]').prop('selected', true);
    $('[name="version"] option[value="<?= $server->version; ?>"]').prop('selected', true);
    $('[name="pon"] option[value="<?= $server->pon; ?>"]').prop('selected', true);
    $(".Select2").select2();
    $('[name="pass"]').password();


  })
</script>