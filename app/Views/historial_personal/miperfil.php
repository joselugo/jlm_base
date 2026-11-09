<style type="text/css">
.gritter-without-image a {
  color: #c5d9ff;
  text-decoration: none;
}

.card-body {
  -ms-flex: 1 1 auto;
  flex: 1 1 auto;
  padding: 1.25rem;
}

.profile-tab li a.nav-link.active,
.customtab li a.nav-link.active {
  border-bottom: 2px solid #009efb;
  color: #009efb;
}

.profile-tab li a.nav-link.active,
.customtab li a.nav-link.active {
  border-bottom: 2px solid #009efb !important;
  color: #009efb;
}

.nav-tabs .nav-item.show .nav-link,
.nav-tabs .nav-link.active {
  color: #495057;
  background-color: #fff;
  border-color: #ddd #ddd #fff;
}

.nav-tabs .nav-link {
  border: 1px solid transparent;
  border-top-left-radius: .25rem;
  border-top-right-radius: .25rem;
}

.nav-link {
  display: block;
  padding: .5rem 1rem;
}

.profile-tab li a.nav-link,
.customtab li a.nav-link {
  border: 0px;
  padding: 15px 20px;
  color: #54667a;
}

.nav-tabs {
  background: #FFF;
}

.form-material .form-control,
.form-material .form-control.focus,
.form-material .form-control:focus {
  background-image: -webkit-gradient(linear, left top, left bottom, from(#009efb), to(#009efb)), -webkit-gradient(linear, left top, left bottom, from(#d9d9d9), to(#d9d9d9));
  background-image: -webkit-linear-gradient(#009efb, #009efb), -webkit-linear-gradient(#d9d9d9, #d9d9d9);
  background-image: -o-linear-gradient(#009efb, #009efb), -o-linear-gradient(#d9d9d9, #d9d9d9);
  background-image: linear-gradient(#009efb, #009efb), linear-gradient(#d9d9d9, #d9d9d9);
  border: 0 none;
  border-radius: 0;
  -webkit-box-shadow: none;
  box-shadow: none;
  float: none;
}

.form-material .form-control {
  background-color: transparent;
  background-position: center bottom, center calc(100% - 1px);
  background-repeat: no-repeat;
  background-size: 0 2px, 100% 1px;
  padding: 0;
  -webkit-transition: background 0s ease-out 0s;
  -o-transition: background 0s ease-out 0s;
  transition: background 0s ease-out 0s;
}

.card {
  position: relative;
  display: -ms-flexbox;
  display: flex;
  -ms-flex-direction: column;
  flex-direction: column;
  min-width: 0;
  word-wrap: break-word;
  background-color: #fff;
  background-clip: border-box;
  border: 1px solid rgba(0, 0, 0, .125);
  border-radius: .25rem;
}

</style>


<div class="row">
  <!-- Column -->
  <div class="col-lg-4 col-xlg-3 col-md-5">
    <div class="card">
      <div class="card-body">
        <center class="m-t-30">
          <img width="100" height="100" class="avataradmin miprofile" data-name="<?= $perfil->nombre; ?>"
            style="border-radius: 50%;" />
          <h4 class="card-title m-t-10"><?= $perfil->nombre; ?></h4>
          <h6 class="card-subtitle">Administrador</h6>
          <br>
          <button type="button" onClick="getfb()" class="btnfb btn btn-xs btn-white" data-toggle="tooltip"
            title="Obtener Avatar desde Facebook" style="margin-right: 5px;font-size: 10px;padding: 4px 7px;"><i
              class="fab fa-facebook-f"></i>acebook</button>

          <button type="button" onClick="gettwitter()" class="btntwitter btn btn-xs btn-white" data-toggle="tooltip"
            title="Obtener Avatar desde twitter" style="margin-right: 5px;font-size: 10px;padding: 4px 7px;"><i
              class="fab fa-twitter"></i> Twitter</button>

          <button type="button" onClick="getgravatar()" class="btngravatar btn btn-xs btn-white" data-toggle="tooltip"
            title="Obtener Avatar desde Gravatar" style="margin-right: 5px;font-size: 10px;padding: 4px 7px;"><i
              class="fas fa-camera-retro"></i> Gravatar</button>


        </center>

      </div>
      <div>
        <hr>
      </div>
      <div class="card-body"> <small class="text-muted"><i class="far fa-envelope" aria-hidden="true"></i> Email</small>
        <h6><?= $perfil->correo; ?></h6> <small class="text-muted p-t-30 db"><i class="fas fa-mobile-alt"
            aria-hidden="true"></i> Teléfono Móvil</small>
        <h6><?= $perfil->movil; ?></h6>
      </div>
    </div>
  </div>
  <!-- Column -->
  <!-- Column -->
  <div class="col-lg-8 col-xlg-9 col-md-7">
    <div class="card">


      <form id="frm-login" class="form-horizontal form-material" action="sistema/update_perfil_personal" method="post">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs profile-tab" role="tablist">
          <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#datos" role="tab"
              aria-expanded="true">Mis datos</a> </li>
          <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#configoperdor" role="tab"
              aria-expanded="false">Configuración</a> </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content">
          <div class="tab-pane active" id="datos" role="tabpanel" aria-expanded="true">
            <div class="card-body">
              <div class="form-group">
                <label class="col-md-12"><i class="far fa-user-o" aria-hidden="true"></i> Nombres Completos</label>
                <div class="col-md-12">
                  <input type="text" placeholder="Johnathan Doe" class="form-control form-control-line"
                    name="user-nombre" value="<?= $perfil->nombre; ?>">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-12"><i class="far fa-envelope" aria-hidden="true"></i> Email</label>
                <div class="col-md-12">
                  <input type="email" placeholder="johnathan@admin.com" class="form-control form-control-line"
                    name="user-correo" value="<?= $perfil->correo; ?>">
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-12"><i class="fas fa-mobile-alt" aria-hidden="true"></i> Teléfono Móvil</label>
                <div class="col-md-12">
                  <input type="text" placeholder="1234567890" class="form-control form-control-line" name="user-movil"
                    value="<?= $perfil->movil; ?>">
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-12"><i class="fab fa-facebook-f" aria-hidden="true"></i> Usuario Facebook</label>
                <div class="col-md-12">
                  <input type="text" placeholder="pepito123" class="form-control form-control-line" name="fb"
                    value="<?= $perfil->fb; ?>">
                </div>
              </div>

              <div class="form-group">
                <label class="col-md-12"><i class="fab fa-twitter" aria-hidden="true"></i> Usuario twitter</label>
                <div class="col-md-12">
                  <input type="text" placeholder="@luisito" class="form-control form-control-line" name="twitter"
                    value="<?= $perfil->twitter; ?>">
                </div>
              </div>



              <div class="form-group">
                <div class="col-sm-12">
                  <button class="btn btn-success" type="submit">Actualizar datos</button>
                </div>
              </div>

            </div>
          </div>


          <div class="tab-pane" id="configoperdor" role="tabpanel" aria-expanded="true">
            <div class="card-body">
              <form class="form-horizontal form-material">
                <div class="form-group">
                  <label class="col-md-12">Desconectar por inactividad</label>
                  <div class="col-md-12">
                    <input type="number" name="timeout" value="<?= $perfil->timeout; ?>"
                      class="form-control form-control-line" required min="0" max="59" title="Máximo 59 minutos"
                      style="width:50%;">
                    <small>* minutos, 0 = Desactivado</small>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-md-12">Preferencias Tablas</label>
                  <div class="col-md-12">
                    <button type="button" class="btn btn-white" onClick="clearstor()"><i class="far fa-trash-alt"></i>
                      Eliminar preferencias</button>
                  </div>
                </div>

                <div class="form-group m-t-15">
                  <div class="col-sm-12">
                    <button class="btn btn-success" type="submit">Guardar cambios</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>


      </form>
    </div>
  </div>
  <!-- Column -->
</div>

<script>
function clearstor() {
  for (var key in localStorage) {
    if (key.search("40_DataTables_") == 0) {
      localStorage.removeItem(key);
    }
    localStorage.removeItem('columna');
    localStorage.removeItem('caja');
  }
  alerta('exito', 'Preferencias eliminadas correctamente.');
}

function getfb() {
  $('.tooltip').remove();
  $('.btnfb').button("loading");

  $.post("ajax/miperfil", {
      action: "fb",
      id: "40",
      user: $('[name="fb"]').val()
    })
    .done(function(data) {
      $('.btnfb').button("reset");
      $('#gritter-notice-wrapper').remove();
      if (data == "user") {
        alerta('error', 'No ha indicado el usuario de facebook');
      } else if (data == "error") {
        alerta('error',
          'La razón más común de este error es que debe permitir temporalmente el acceso al motor de búsqueda a la información pública de su página de perfil. Para ello, vaya a su página de <b><a href="https://www.facebook.com/settings?tab=privacy" target="_blank">configuración de privacidad de Facebook</a></b> y permita que los motores de búsqueda se enlacen a su línea de tiempo. Luego vuelve aquí y vuelve a intentarlo. Después de obtener su avatar de Facebook, puedes revertir la configuración de privacidad a su estado anterior.'
        );
      } else if (data) {
        $('.avataradmin').attr('src', data);
      }


    });

}

function gettwitter() {
  $('.tooltip').remove();
  $('.btntwitter').button("loading");

  $.post("ajax/miperfil", {
      action: "twitter",
      id: "40",
      user: $('[name="twitter"]').val()
    })
    .done(function(data) {
      $('.btntwitter').button("reset");

      $('#gritter-notice-wrapper').remove();
      if (data == "error") {
        alerta('error', 'No ha indicado un usuario twitter correcto.');
      } else if (data) {
        $('.avataradmin').attr('src', data);
      }


    });

}

function getgravatar() {
  $('.tooltip').remove();
  $('.btngravatar').button("loading");

  $.post("ajax/miperfil", {
      action: "gravatar",
      id: "40",
      user: $('[name="user-correo"]').val()
    })
    .done(function(data) {
      $('.btngravatar').button("reset");

      $('#gritter-notice-wrapper').remove();
      if (data == "error") {
        alerta('error', 'El correo gravatar es incorrecto.');
      } else if (data) {
        $('.avataradmin').attr('src', data);
      }


    });

}


$(function() {


  $('#frm-login').ajaxForm({
    success: function(data) {
      if (data == 2) {
        $('#gritter-notice-wrapper').remove();
        alerta('error', 'Error en actualizar los datos.');
      } else {
        $('#gritter-notice-wrapper').remove();
        alerta('exito', 'Datos actualizados correctamente.');
	window.location = "/herramientas";
      }
    },
    error: function(response) {
      $('#gritter-notice-wrapper').remove();
      alerta('error500', response['responseText']);
    }
  })


  $('.miprofile').initial({
    height: 100,
    width: 100,
    charCount: 2,
    color: '#32A3AD',
    fontSize: 50,
    fontWeight: 600
  });

  App.restartGlobalFunction();
  App.setPageTitle('Mi perfil');

})
</script>
