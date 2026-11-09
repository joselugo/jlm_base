<br>
<form method="post" class="form-horizontal row" id="Router-edit-configuracion" action="gestion_red/save_server_general">
  <input type="hidden" name="id" value="<?= $server->id; ?>">
  <div class="col-md-6">

    <div class="form-group row">
      <label class="col-md-4 control-label">Nombre Router</label>
      <div class="col-md-8">
        <input type="text" class="form-control" placeholder="Nombre del ruteador" name="nodo" value="<?= $server->nodo; ?>" required />
      </div>
    </div>

    <div class="form-group row">
      <label class="col-md-4 control-label">Tipo Router</label>
      <div class="col-md-8">
        <select class="form-control Select2" style="width:100%">
          <option value="mikrotik">MikroTik</option>
        </select>
      </div>
    </div>

    <div class="form-group row">
      <label class="col-md-4 control-label">Ubicación
        <button type="button" onclick="desaparecer()" class="btn btn-icono btn-xs btn-map boton_mapa" data-toggle="tooltip" data-placement="top" title="Abrir Google maps"><i class="fas fa-street-view"></i></button>
      </label>
      <div class="col-md-8">
        <input type="text" class="form-control" placeholder="Coordenadas Latitud,Longitud " name="coordenadas" value="<?= $server->coordenadas; ?>" />
      </div>
    </div>


    <div class="form-group row">
      <label class="col-md-4 control-label">IP / Host</label>
      <div class="col-md-8">
        <input type="text" class="form-control" placeholder="Ip ruteador" name="ip" value="<?= $server->ip; ?>" required />
      </div>
    </div>


    <div class="form-group row">
      <label class="col-md-4 control-label">Seguridad</label>
      <div class="col-md-8">
        <select style="width: 100%;" class="form-control Select2" name="seguridad">
          <option value="0">Ninguno / Accounting API</option>

        </select>
      </div>
    </div>

    <div class="form-group row security2">
      <label class="col-md-4 control-label">Seguridad alterna</label>
      <div class="col-md-8">
        <select style="width: 100%;" class="form-control Select2" name="seguridad2">
          <option value="0">Ninguno</option>
          <option value="1">Amarre de IP y Mac</option>
          <option value="2">DHCP Leases</option>
          <option value="3">IP Binding</option>
          <option value="4">Amarre de IP y Mac + DHCP Leases</option>
        </select>
      </div>
    </div>
    <hr class="radiusdv">
    <h4 class="text-center radiusdv">Configuración Radius</h5>
      <div class="form-group row radiusdv">
        <label class="col-md-4 control-label">Radius Secret
          <button type="button" class="btn btn-icono btn-xs" data-toggle="tooltip" data-placement="top" title="Generar contraseña" onClick="passwordaleatorio('radius_secret')"><i class="fas fa-lock"></i></button>
        </label>
        <div class="col-md-8">
          <input type="text" class="form-control" name="radius_secret" value="<?= $server->secret_radius; ?>" placeholder="Contraserña Radius" />
        </div>
      </div>

      <div class="form-group row radiusdv">
        <label class="col-md-4 control-label">Radius NAS IP
        </label>
        <div class="col-md-8">
          <input type="text" class="form-control" name="radius_ip" value="<?= $server->ip_radius; ?>" placeholder="<?= $server->ip_radius; ?>" />
        </div>
      </div>


  </div>

  <div class="col-md-6">
    <h4 style="width:100%; text-align:center"><b>MIKROTIK</b></h4>

    <div class="form-group row">
      <label class="col-md-4 control-label">Usuario (API)</label>
      <div class="col-md-8">
        <input id="mikro2" type="text" class="form-control" placeholder="Usuario API" name="user" value="<?= $server->usuario; ?>" required autocomplete="username" />
      </div>
    </div>

    <div class="form-group row">
      <label class="col-md-4 control-label">Contraseña (API)
        <i id="ME" class="fas fa-check-circle" style="display:none;font-size:21px;color:cadetblue;"></i>
        <button id="MF" type="button" onclick="check()" class="btn btn-icono btn-xs boton_check" data-toggle="tooltip" data-placement="top" style="font-size:10px;">Validar conexion</button>
      </label>
      <div class="col-md-8">
        <input id="mikro1" type="password" data-toggle="password" data-placement="after" class="no form-control" readonly placeholder="Contraseña API" name="password" value="<?= $server->contrasena; ?>" required autocomplete="current-password" />
      </div>
    </div>

    <div class="form-group row">
      <label class="col-md-4 control-label">Control Velocidad</label>
      <div class="col-md-8">
        <select style="width: 100%;" class="form-control Select2" name="velocidad">
          <option value="queues">Colas Simples (Estáticas)</option>
          <option value="pcq">PCQ + Addresslist</option>
          <option value="profile">Profile (Colas simples Dinámicas)</option>
        </select>
      </div>
    </div>

    <div class="form-group row">
      <label class="col-md-4 control-label">Guardar IP Visitadas</label>
      <div class="col-md-8">
        <input type="hidden" name="ipvisitadas">
        <input type="checkbox" class="js-switch" name="ipvisitadas">
      </div>
    </div>
    <div class="form-group text-center">
      <div class="alert alert-danger fade show m-b-10 alerta-proceso" style="display: none">
        <b>ATENCIÓN: </b> Se esta realizando cambios masivos en su router,No podrá editar el router hasta terminar con
        esta tarea.
        Puede seguir operando otras secciones del sistema.
      </div>
      <button id="btn_new_server" disabled type="submit" style="display:<?= $permiso >= 2 ? "block" : "none"; ?>" class="btn-loader btn btn-success"><i class="far fa-save"></i> Guardar Cambios</button>

    </div>
  </div>

</form>


<script>
  var enable_m = false;
  var m1 = document.getElementById('mikro1');
  var m2 = document.getElementById('mikro2');
  m1.addEventListener('keypress', function() {
    normal_todo();
  })
  m2.addEventListener('keypress', function() {
    normal_todo();
  })

  function normal_todo() {
    enable_m = false;
    $("#MF").show();
    $("#ME").hide();
    $("#btn_new_server").prop("disabled", true);

    $("#mikro1").removeClass("is-valid");
    $("#mikro2").removeClass("is-valid");
  }

  function desactivar_todo() {
    enable_m = false;
    $("#MF").show();
    $("#ME").hide();
    $("#btn_new_server").prop("disabled", true);
    $("#mikro1").addClass("is-invalid");
    $("#mikro2").addClass("is-invalid");
    $("#mikro1").removeClass("is-valid");
    $("#mikro2").removeClass("is-valid");
  }

  function activar_todo() {
    enable_m = true;
    $("#btn_new_server").prop("disabled", false);
    $("#ME").show();
    $("#MF").hide();
    $("#mikro1").removeClass("is-invalid");
    $("#mikro2").removeClass("is-invalid");
    $("#mikro1").addClass("is-valid");
    $("#mikro2").addClass("is-valid");
  }

  function check() {
    $.ajax({
      type: "POST",
      url: "gestion_red/validar_mikrotik",
      data: {
        usuario: $('[name="user"]').val(),
        contrasenia: $('[name="password"]').val(),
        ip: $('[name="ip"]').val()
      },
      beforeSend: function() {
        $('#MF').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Validando...');
        $("#MF").prop("disabled", true);
      },
      success: function(data) {
        $("#MF").prop("disabled", false);
        $('#MF').html('Validar conexion');
        if (data == 1) {
          activar_todo();
        } else if (data == 2) {
          desactivar_todo();
        } else {
          desactivar_todo();
          alerta("warning", "No cuentas con los permisos requeridos");
        }
      }
    });
  }

  function desaparecer(e) {
    $('.boton_mapa').hide();
  }
  $('#Router-edit-configuracion').ajaxForm({
    beforeSend: function() {
      console.log(enable_m);
      if (!enable_m) {
        alerta("warning", "Valida primero la conexion con el router mikrotik");
        return false;
      }
    },
    success: function(data) {
      btnloader('#Router-edit-configuracion', '<i class="far fa-save"></i>  Guardar Cambios');
      $('#gritter-notice-wrapper').remove();
      if (data == 1) {
        alerta('exito', 'Cambios realizados.');
      } else if (data == 2) {
        alerta('error', 'Error no se pudo actualizar los datos.');
      } else if (data == 3) {
        alerta("error", "No se pudo conectar al routerboard");
      } else {
        alerta('warning', 'No tienes permiso para realizar esta accion.');
      }

    },
    beforeSubmit: function() {
      if (enable_m) {
        btnloader('#Router-edit-configuracion');

      }
    },
    error: function(response) {
      btnloader('#Router-edit-configuracion', '<i class="far fa-save"></i>  Guardar Cambios');
      $('#gritter-notice-wrapper').remove();
      alerta('error500', response['responseText']);
    }
  })


  $('.btn-map').on('click', function() {
    $.post("gestion_red/modal_mapa", {
        action: "mapa"
      })
      .done(function(datas) {
        $('#tmp').html(datas);
      });
  });

  $('select[name="seguridad"]').change(function() {
    if ($(this).val() == 1) {
      $('.security2').slideUp();
    }
    $('[name="velocidad"] option[value="0"]').prop('selected', true);
  });

  $(function() {

    autosize(document.querySelectorAll('textarea'));
    $('select[name="velocidad"]').html(
      '<option value="queues">Colas Simples (Estáticas)</option><option value="pcq">PCQ + Addresslist</option>');
    $('[name="velocidad"] option[value="<?= $server->velocidad; ?>"]').prop('selected', true);
    $('[name="seguridad"] option[value="<?= $server->seguridad; ?>"]').prop('selected', true);
    $('[name="seguridad2"] option[value="<?= $server->seguridad2; ?>"]').prop('selected', true);

    $(".Select2").select2();
    $('[name="password"]').password();

    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    elems.forEach(function(html) {
      var switchery = new Switchery(html, {
        size: 'small',
        color: 'rgb(18, 187, 168)'
      });
    });

  })
</script>