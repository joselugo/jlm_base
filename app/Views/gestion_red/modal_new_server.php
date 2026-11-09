<form id="router-form" method="post" action="gestion_red/save_new_server" class="form-horizontal">
  <div class="modal-body">

    <div class="form-group row">
      <label class="col-md-3 control-label">Nombre</label>
      <div class="col-md-9">
        <input type="text" class="form-control" name="nodo" placeholder="Nombre del router" required autofocus>
      </div>
    </div>



    <div class="form-group row">
      <label class="col-md-3 control-label">IP Mikrotik</label>
      <div class="col-md-9">
        <input type="text" class="form-control" name="ip" placeholder="Ip o url de conexión" required>
      </div>
    </div>



    <div class="form-group row">
      <label class="col-md-3 control-label">Usuario Mikrotik</label>
      <div class="col-md-9">
        <input id="mikro2" type="text" class="form-control no" name="user" placeholder="usuario de conexión" readonly required>
      </div>
    </div>



    <div class="form-group row">
      <label class="col-md-3 control-label">Contraseña Mikrotik
      </label>
      <div class="col-md-9">
        <input id="mikro1" readonly type="password" class="no form-control" id="password" name="password" placeholder="contraseña de conexión" data-placement="after" required>
        <button id="MF" type="button" onclick="check()" class="btn btn-icono btn-xs boton_check" data-toggle="tooltip" data-placement="top" style="font-size:10px;">Validar conexion</button>
      </div>
    </div>



  </div>

  <div class="modal-footer">
    <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
    <button id="btn_new_server" disabled type="submit" class="btn btn-sm btn-success btn-newrouter" data-loading-text="<i class='fas fa-spinner fa-spin'></i> registrando">Registrar</button>
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

  $(function() {

    $('#router-form').ajaxForm({
      beforeSend: function() {
        if (!enable_m) {
          alerta("warning", "Valida primero la conexion con el router mikrotik");
          return false;
        }
      },
      success: function(data) {
        $('#gritter-notice-wrapper').remove();
        if (data == 1) {
          $('#modaltmp').modal('hide');
          alerta('exito', 'Agregado con exito.');
          updaterouter();
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
          alerta('loader', '');
        }
      },
      error: function(response) {
        $('#gritter-notice-wrapper').remove();
        alerta('error', "error");
      }
    })

    $('#mikro1').password();
    $('#modaltmp').modal('show');


  })
</script>