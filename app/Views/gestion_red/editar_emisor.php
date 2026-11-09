<ol class="breadcrumb pull-right">
  <li class="breadcrumb-item"><a href="/herramientas">Inicio</a></li>
  <li class="breadcrumb-item"><a href="herramientas/#gestion_red/monitoreo" class="linkemisor">Lista Emisores</a></li>
  <li class="breadcrumb-item active">Editar Emisor</li>
</ol>
<h4><b><?= $emisor->nombre; ?></b></h4>

<ul class="nav nav-tabs" style="width: 100%">
  <li class="nav-item"><a href="#default-tab-1" data-toggle="tab" class="nav-link active"><i class="far fa-edit" aria-hidden="true"></i> Editar datos</a></li>
  <li class="nav-item"><a href="#default-tab-2" data-toggle="tab" class="nav-link"><i class="far fa-bell-o" aria-hidden="true"></i> Notificaciones</a></li>
  <li class="nav-item"><a href="#default-tab-3" data-toggle="tab" onClick="$('.graf').trigger('change')" class="nav-link"><i class="far fa-chart-bar" aria-hidden="true"></i> Gráficos</a></li>
  <li class="nav-item"><a href="#default-tab-4" data-toggle="tab" class="nav-link"><i class="far fa-bell-o"></i>
      SNMP</a></li>
</ul>

<div class="tab-content">

  <div class="tab-pane fade active show row" id="default-tab-1">
    <form method="post" action="gestion_red/save_emisor" class="form-horizontal emisor_edit_form">
      <input type="hidden" name="id" value="<?= $emisor->id; ?>">
      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-4 col-form-label">Nombre Emisor</label>
          <div class="col-md-8">
            <input type="text" class="form-control" name="emisores[nombre]" placeholder="Servidor cloud" required value="<?= $emisor->nombre; ?>">
          </div>
        </div>
      </div>

      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-4 col-form-label">Dirección IP</label>
          <div class="col-md-8">
            <input type="text" class="form-control" name="emisores[ip]" placeholder="8.8.8.8" required value="<?= $emisor->ip; ?>">
          </div>
        </div>
      </div>

      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-4 col-form-label">Ping debug <i class="far fa-question-circle fa-lg" data-toggle="tooltip" title="Registra en log del sistema la salida del comando PING cuando el equipo cambia de estado Online/offline"></i></label>
          <div class="col-md-8">
            <input type="hidden" name="emisores[debug]" value="0">
            <input type="checkbox" class="form-control js-switch" value="1" name="emisores[debug]" <?= $emisor->debug == 1 ? "checked" : ""; ?>>
          </div>
        </div>
      </div>


      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-4 col-form-label">Fabricante / Tipo</label>
          <div class="col-md-8">
            <select style="width: 100%;" class="form-control Select2" name="emisores[fabricante]">
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
          <label class="col-md-4 col-form-label">Modelo/Nombre</label>
          <div class="col-md-8">
            <input type="text" class="form-control" name="emisores[equipo]" placeholder="RB 1100Ahx2" value="<?= $emisor->equipo; ?>">
          </div>
        </div>
      </div>

      <div class="col-sm-12 user-data">
        <div class="form-group row">
          <label class="col-md-4 col-form-label">Usuario</label>
          <div class="col-md-8">
            <input type="text" class="form-control no" name="emisores[user]" placeholder="ubnt" value="<?= $emisor->usuario_netium; ?>" readonly>
          </div>
        </div>
      </div>

      <div class="col-sm-12 user-data pass-mimosa">
        <div class="form-group row">
          <label class="col-md-4 col-form-label">Contraseña</label>
          <div class="col-md-8">
            <input type="password" class="form-control no" name="emisores[password]" placeholder="ubnt" value="<?= $emisor->contrasenia_netium; ?>" readonly>
          </div>
        </div>
      </div>

      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-4 col-form-label">Access list wireless <i class="far fa-question-circle fa-lg" data-toggle="tooltip" title="Disponible para Emisores mikrotik, active esta opción si desea agregar los clientes en Wireless Access List"></i></label>
          <div class="col-md-8">
            <input type="hidden" name="emisores[access]" value="">
            <input type="checkbox" class="form-control js-switch" name="emisores[access]" <?= $emisor->access == "" ? "" : "checked"; ?>>
          </div>
        </div>
      </div>

      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-4 col-form-label">Ubicación <button type="button" class="btn btn-icono btn-xs btn-map" data-toggle="tooltip" data-placement="top" title="" data-original-title="Abrir Google maps"><i class="fas fa-street-view"></i></button></label>
          <div class="col-md-8">
            <input type="text" class="form-control" name="emisores[lglt]" placeholder="-109987894734,-03242342344" value="<?= $emisor->lglt; ?>">
          </div>
        </div>
      </div>

      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-4 col-form-label">monitoreo SNMP</label>
          <div class="col-md-8">
            <input type="hidden" name="emisores[snmp]" value="0">
            <input type="checkbox" class="form-control js-switch" value="1" name="emisores[snmp]" <?= $emisor->snmp == 1 ? "checked" : ""; ?>>
          </div>
        </div>
      </div>

      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-4 col-form-label">Comunidad SNMP</label>
          <div class="col-md-8">
            <input type="text" class="form-control" name="emisores[snmp_comunidad]" placeholder="public" value="<?= $emisor->snmp_comunidad; ?>">
          </div>
        </div>
      </div>

      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-4 col-form-label">Versión SNMP</label>
          <div class="col-md-8">
            <select style="width: 100%;" class="form-control Select2" name="emisores[snmp_version]">
              <option value="1">Versión 1</option>
              <option value="2">Versión 2</option>
            </select>
          </div>
        </div>
      </div>

      <div id="mapaemisor" class="w-100 d-none" style="height: 400px; border: 1px solid #eee;">Google Maps</div>


      <div class="text-center">
        <a class="btn-azul" href="#gestion_red/monitoreo" style="margin-bottom: 0px;"><i class="fas fa-times"></i>
          Salir</a>
        <button type="submit" style="display:<?= $permiso >= 2 ? "block" : "none"; ?>" class="btn-azul" style="margin-bottom: 0px;"><i class="far fa-save"></i> Guardar</button>
      </div>
    </form>
  </div>


  <script type="text/javascript">


  </script>

  <div class="tab-pane fade" id="default-tab-2">
    <form method="post" action="gestion_red/save_emisor" class="form-horizontal emisor_edit_form2">
      <input type="hidden" name="id" value="<?= $emisor->id; ?>">
      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-4 control-label">Correos de Notificacion</label>
          <div class="col-md-8">
            <input type="text" class="form-control tagemisor" name="emisores[correos]" placeholder="correo@gmail.com" value="<?= $emisor->correos; ?>">
            <small class="text-warning">Puede Ingresar varios correos separados por comas,si deja en blanco las
              notificaciones serán enviados a los correos configurados en ajustes generales.</small>
          </div>
        </div>
      </div>

      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-4 control-label">Notificación Email</label>
          <div class="col-md-8">
            <input type="hidden" name="emisores[alert]">
            <input type="checkbox" class="form-control js-switch" name="emisores[alert]" <?= $emisor->alert == "" ? "" : "checked"; ?>>
          </div>
        </div>
      </div>

      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-4 control-label">Nº Móvil de Notificacion</label>
          <div class="col-md-8">
            <input type="text" class="form-control tagemisor" name="emisores[telefonos]" placeholder="public" value="<?= $emisor->telefonos; ?>">
            <small class="text-warning">Puede Ingresar varios números separados por comas,si deja en blanco las
              notificaciones serán enviados a los teléfonos configurados en ajustes generales.</small>
          </div>
        </div>
      </div>

      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-4 control-label">Notificación SMS</label>
          <div class="col-md-8">
            <input type="hidden" name="emisores[alert2]">
            <input type="checkbox" class="form-control js-switch" name="emisores[alert2]" <?= $emisor->alert2 == "" ? "" : "checked"; ?>>
          </div>
        </div>
      </div>

      <div class="text-center">
        <a class="btn-azul" href="#gestion_red/monitoreo" style="margin-bottom: 0px;"><i class="fas fa-times"></i>
          Salir</a>
        <button style="display:<?= $permiso >= 2 ? "block" : "none"; ?>" type="submit" class="btn-azul" style="margin-bottom: 0px;"><i class="far fa-save"></i> Guardar</button>
      </div>
    </form>
  </div>

  <div class="tab-pane fade" id="default-tab-3">
    <div class="row">
      <div class="col-md-6">
        <div class="panel panel-inverse" style="width:100%;max-width:640px !important">
          <div class="panel-heading">
            <h4 class="panel-title">PING</h4>
            <div class="panel-heading-btn">
              <select id="tab_ping_grafico_tipo" class="graf Select2" style="width:140px !important;">
                <option value="-30min" selected="selected">últimos 30 minutos</option>
                <option value="hora">última hora</option>
                <option value="-12h">últimas 12 horas</option>
                <option value="dia">Ultimas 24 horas</option>
                <option value="semana">Semana</option>
                <option value="mes">Mes</option>
                <option value="año">Año</option>
              </select>
            </div>
          </div>
          <div class="panel-body no-padding">
            <img src="" id="tab_ping_grafico" style="width:100%;max-width:640px !important" class="imgchartrafico">
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="panel panel-inverse" style="width:100%;max-width:640px !important">
          <div class="panel-heading">
            <h4 class="panel-title">ESTACIONES CONECTADAS</h4>
            <div class="panel-heading-btn">
              <select id="tab_estaciones_grafico_tipo" class="graf Select2" style="width:140px !important;">
                <option value="-30min" selected="selected">últimos 30 minutos</option>
                <option value="hora">última hora</option>
                <option value="-12h">últimas 12 horas</option>
                <option value="dia">Ultimas 24 horas</option>
                <option value="semana">Semana</option>
                <option value="mes">Mes</option>
                <option value="año">Año</option>
              </select>
            </div>
          </div>
          <div class="panel-body no-padding">
            <img src="" id="tab_estaciones_grafico" style="width:100%;max-width:640px !important" class="imgchartrafico">
          </div>
        </div>
      </div>
    </div>

    <div class="row">


      <div class="col-md-6">
        <div class="panel panel-inverse" style="width:100%;max-width:640px !important">
          <div class="panel-heading">
            <h4 class="panel-title">CPU & MEMORIA</h4>
            <div class="panel-heading-btn">
              <select id="tab_cpu_grafico_tipo" class="graf Select2" style="width:140px !important;">
                <option value="-30min" selected="selected">últimos 30 minutos</option>
                <option value="hora">última hora</option>
                <option value="-12h">últimas 12 horas</option>
                <option value="dia">Ultimas 24 horas</option>
                <option value="semana">Semana</option>
                <option value="mes">Mes</option>
                <option value="año">Año</option>
              </select>
            </div>
          </div>
          <div class="panel-body no-padding">
            <img src="" id="tab_cpu_grafico" style="width:100%;max-width:640px !important" class="imgchartrafico">
          </div>
        </div>
      </div>

      <div class="col-md-6 panel-ccq-ruido-ubiquiti">
        <div class="panel panel-inverse" style="width:100%;max-width:640px !important">
          <div class="panel-heading">
            <h4 class="panel-title">SEÑAL & RUIDO</h4>
            <div class="panel-heading-btn">
              <select id="tab_signal_grafico_tipo" class="graf Select2" style="width:140px !important;">
                <option value="-30min" selected="selected">últimos 30 minutos</option>
                <option value="hora">última hora</option>
                <option value="-12h">últimas 12 horas</option>
                <option value="dia">Ultimas 24 horas</option>
                <option value="semana">Semana</option>
                <option value="mes">Mes</option>
                <option value="año">Año</option>
              </select>
            </div>
          </div>
          <div class="panel-body no-padding">
            <img src="" id="tab_signal_grafico" style="width:100%;max-width:640px !important" class="imgchartrafico">
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="tab-pane fade" id="default-tab-4">
    <form method="post" action="gestion_red/save_emisor" class="form-horizontal emisor_edit_form3">
      <input type="hidden" name="id" value="<?= $emisor->id; ?>">

      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-4 control-label"> Estaciones conectadas <small class="text-success">(OID)</small></label>
          <div class="col-md-8">
            <input type="text" class="form-control" name="snmp[estaciones]" placeholder=".1.3.6.1...." value="<?= $emisor->oid->estaciones; ?>">
          </div>
        </div>
      </div>

      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-4 control-label"> CPU <small class="text-success">(OID)</small></label>
          <div class="col-md-8">
            <input type="text" class="form-control" name="snmp[cpu]" placeholder=".1.3.6.1...." value="<?= $emisor->oid->cpu; ?>">
          </div>
        </div>
      </div>

      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-4 control-label">Memoria Total <small class="text-success">(OID)</small></label>
          <div class="col-md-8">
            <input type="text" class="form-control" name="snmp[memoria-total]" placeholder=".1.3.6.1...." value="<?= $emisor->oid->{'memoria-total'}; ?>">
          </div>
        </div>
      </div>

      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-4 control-label">Memoria usada <small class="text-success">(OID)</small></label>
          <div class="col-md-8">
            <input type="text" class="form-control" name="snmp[memoria-usada]" placeholder=".1.3.6.1...." value="<?= $emisor->oid->{'memoria-usada'}; ?>">
          </div>
        </div>
      </div>

      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-4 control-label">CCQ/Señal <small class="text-success">(OID)</small></label>
          <div class="col-md-8">
            <input type="text" class="form-control" name="snmp[ccq]" placeholder=".1.3.6.1.4.1.14988.1.1.1.3.1.11.11" value="<?= $emisor->oid->ccq; ?>">
          </div>
        </div>
      </div>

      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-4 control-label">Ruido <small class="text-success">(OID)</small></label>
          <div class="col-md-8">
            <input type="text" class="form-control" name="snmp[ruido]" placeholder=".1.3.6.1.4.1.14988.1.1.1.3.1.11.11" value="<?= $emisor->oid->ruido; ?>">
          </div>
        </div>
      </div>


      <div class="text-center">
        <a class="btn-azul" href="#gestion_red/monitoreo" style="margin-bottom: 0px;"><i class="fas fa-times"></i>
          Salir</a>
        <button style="display:<?= $permiso >= 2 ? "block" : "none"; ?>" type="submit" class="btn-azul" style="margin-bottom: 0px;"><i class="far fa-save"></i> Guardar</button>
      </div>
    </form>
  </div>

</div>


<div class="modal fade" id="enlargeImageModal" tabindex="-1" role="dialog" aria-labelledby="enlargeImageModal" aria-hidden="true" style="z-index: 99999">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body" style="display: block;">
        <img src="" class="enlargeImageModalSource" style="width: 100%;">
      </div>
    </div>
  </div>
</div>


<style type="text/css">
  .panel-heading-btn .select2-container {
    margin-top: -4px !important;
  }

  .panel-heading-btn .select2-container .select2-selection--single .select2-selection__rendered,
  .panel-heading-btn .select2-container .select2-selection--single {
    font-size: 11px !important;
  }

  .imgchartrafico {
    cursor: zoom-in;
  }
</style>
<script>
  $(function() {
    $(".tagemisor").tagit();

    $('.imgchartrafico').on('click', function() {
      $('.enlargeImageModalSource').attr('src', $(this).attr('src'));
      $('#enlargeImageModal').modal('show');
    });

    $('select[name="emisores\[fabricante\]"] option[value="<?= $emisor->fabricante; ?>"]').prop('selected', true);

    if ($('select[name="emisores\[fabricante\]"]').val() == 2) {
      $('.user-data').hide();
    } else if ($('select[name="emisores\[fabricante\]"]').val() == 5) {
      $('.user-data').hide();
      $('.pass-mimosa').show();
    } else {
      $('.user-data').show();
    }

    $('#tab_ping_grafico_tipo').change(function() {
      $('#tab_ping_grafico').attr('src', 'ajax/emisores?action=grafico&id=1&grafico=ping&t=' + $.now() +
        '&tipo=' + $(this).val());
    });

    $('#tab_cpu_grafico_tipo').change(function() {
      $('#tab_cpu_grafico').attr('src', 'ajax/emisores?action=grafico&id=1&grafico=cpu&t=' + $.now() + '&tipo=' +
        $(this).val());
    });

    $('#tab_memory_grafico_tipo').change(function() {
      $('#tab_memory_grafico').attr('src', 'ajax/emisores?action=grafico&id=1&grafico=memory&t=' + $.now() +
        '&tipo=' + $(this).val());
    });

    $('#tab_signal_grafico_tipo').change(function() {
      $('#tab_signal_grafico').attr('src', 'ajax/emisores?action=grafico&id=1&grafico=signal&t=' + $.now() +
        '&tipo=' + $(this).val());
    });

    $('#tab_signalmk_grafico_tipo').change(function() {
      $('#tab_signalmk_grafico').attr('src', 'ajax/emisores?action=grafico&id=1&grafico=signalmk&t=' + $.now() +
        '&tipo=' + $(this).val());
    });


    $('#tab_consumomk_grafico_tipo').change(function() {
      $('#tab_consumomk_grafico').attr('src', 'ajax/emisores?action=grafico&id=1&grafico=consumomk&t=' + $.now() +
        '&tipo=' + $(this).val());
    });


    $('#tab_cpu_temp_tipo').change(function() {
      $('#tab_cpu_temp').attr('src', 'ajax/emisores?action=grafico&id=1&grafico=mimosatemp&t=' + $.now() +
        '&tipo=' + $(this).val());
    });


    $('#tab_estaciones_grafico_tipo').change(function() {
      $('#tab_estaciones_grafico').attr('src', 'ajax/emisores?action=grafico&id=1&grafico=estaciones&t=' + $
        .now() + '&tipo=' + $(this).val());
    });

    $('select[name="emisores\[fabricante\]"]').change(function() {
      if ($(this).val() == 2) {
        $('.user-data').hide();
      } else if ($(this).val() == 5) {
        $('.user-data').hide();
        $('.pass-mimosa').show();
      } else {
        $('.user-data').show();
      }
    });


    App.restartGlobalFunction();
    App.setPageTitle('Editar Emisor');


    $('.btn-map').on('click', function() {
      $.post("ajax/emisores", {
          action: "mapa"
        })
        .done(function(datas) {
          $('#tmp').html(datas);
        });
    });

    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    elems.forEach(function(html) {
      var switchery = new Switchery(html, {
        size: 'small',
        color: 'rgb(18, 187, 168)'
      });
    });

    $('[name="emisores\[snmp_version\]"] option[value="<?= $emisor->snmp_version; ?>"]').prop('selected', true);


    $(".Select2").select2({
      placeholder: "Seleccionar..."
    });

    $('.emisor_edit_form,.emisor_edit_form2,.emisor_edit_form3').ajaxForm({
      success: function(data) {
        if (data == 2) {
          $('#gritter-notice-wrapper').remove();
          alerta('error', 'No se guardo los nuevos datos.');
        } else {
          $('#gritter-notice-wrapper').remove();
          alerta('exito', 'Datos guardados correctamente');
        }
      },
      error: function(response) {
        $('#gritter-notice-wrapper').remove();
        $('.btn-editperfil').button('reset');
        alerta('error500', response['responseText']);
      }
    })

  })
</script>