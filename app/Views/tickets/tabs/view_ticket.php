<ol class="breadcrumb pull-right d-print-none">
  <li class="breadcrumb-item"><a href="/admin">Inicio</a></li>
  <li class="breadcrumb-item"><a href="#ticket/index">soporte</a></li>
  <li class="breadcrumb-item active">Ticket #<?= $soporte->id ?></li>

</ol>

<h1 class="page-header">Ticket #<?= $soporte->id ?> - <small><?= $soporte->asunto ?></small></h1>


<ul class="nav nav-tabs d-print-none">
  <li class="nav-item"><a href="#default-tab-1" data-toggle="tab" class="nav-link active"><i class="far fa-edit"></i> Añadir respuesta</a></li>
  <li class="nav-item"><a href="#default-tab-2" data-toggle="tab" class="nav-link"><i class="far fa-sticky-note"></i> Añadir nota</a></li>
  <li class="nav-item"><a href="#default-tab-3" data-toggle="tab" class="nav-link"><i class="far fa-life-ring"></i> Otros tickets</a></li>
  <li class="nav-item"><a href="#default-tab-4" data-toggle="tab" class="nav-link"><i class="fas fa-cog"></i> Opciones</a></li>
</ul>


<div class="tab-content">

  <!-- ================================================================ -->
  <div class="tab-pane fade active show" id="default-tab-1">

    <form class="d-print-none form-horizontal" id="frm-replysoporte" method="post" action="ticket/add_reply" enctype="multipart/form-data">
      <div style="background: #f5f5f5;margin-bottom: 20px;border: 1px solid rgb(211, 216, 222);padding: 0px 5px;">
        <input type="hidden" name="estadoticket" value="<?= $soporte->estado ?>">
        <input type="hidden" name="idticket" value="<?= $soporte->id ?>">
        <input type="hidden" name="idcliente" value="<?= $soporte->idcliente ?>">
        <input type="hidden" name="asunto" value="<?= $soporte->asunto ?>">
        <input type="hidden" name="idtecnico" value="<?= $_SESSION['id_usuario8291'] ?>"><!-- id usuario a agregar ticket _session -->

        <textarea class="textarea form-control" id="htmlreply" name="htmlreply" rows="12" style="line-height: 13px;" placeholder="Ingrese una respuesta ...">
        </textarea>

        <div class="inner-container" style="display: block;padding: 10px">

          <div style="height: 40px;">
            <div class="pull-left"><button type="button" class="btn btn-default" onclick="$('.btnsup').slideToggle()"><i class="far fa-file"></i> Adjuntar archivo</button></div>

            <div class="pull-left" style="margin-left: 5px;"><button type="button" class="btn btn-default" onclick="$('.btndatauser').slideToggle()"><i class="far fa-user"></i> Info cliente</button></div>

            <div class="pull-left" style="margin-left: 5px;"><button type="button" class="btn btn-default" onclick="$('.infotk').slideToggle()"><i class="fas fa-info"></i> Info ticket</button></div>

            <div class="pull-left" style="margin-left: 5px;"><button type="button" class="btn btn-default" onclick="window.print();"><i class="fas fa-print"></i></button></div>


            <?php $buttons = "";
            if ($unanswered_ticket >= 2) {
              $buttons = '
              <div class="pull-right"><button type="submit" class="btn btn-success"><i class="fas fa-reply"></i> Responder</button></div>
              <div class="pull-right"><button type="button" class="btn btn-default btn-closetk m-r-10"><i class="fas fa-reply"></i> Responder y Cerrar Ticket</button></div>';
            } else if ($answered_ticket >= 2) {
              $buttons = '
              <div class="pull-right"><button type="submit" class="btn btn-success"><i class="fas fa-reply"></i> Responder</button></div>
              <div class="pull-right"><button type="button" class="btn btn-default btn-closetk m-r-10"><i class="fas fa-reply"></i> Responder y Cerrar Ticket</button></div>';
            } else if ($closed_ticket >= 2) {
              $buttons = '
              <div class="pull-right"><button type="submit" class="btn btn-success"><i class="fas fa-reply"></i> Responder</button></div>
              <div class="pull-right"><button type="button" class="btn btn-default btn-closetk m-r-10"><i class="fas fa-reply"></i> Responder y Cerrar Ticket</button></div>';
            } else {
              $buttons;
            }
            echo $buttons;
            ?>

          </div>

          <div class="row btnsup" style="display: none">
            <div id="fileuploads"></div>
            <div class="col-md-12 m-b-5">
              <a class="btn btn-white m-t-15" onclick="addfile()"><i class="fas fa-plus"></i> Añadir m&aacute;s</a>
            </div>
          </div>
        </div>
      </div>
    </form>

    <div class="infotk print" style="display: none">
      <!-- info ticket -->

      <div style="width: 100%;text-align: center"><img src="<?= base_url() ?>public/images/Logotipo.png" style="max-height:50px; width:auto"></div>

      <h2 class="text-center m-t-5 m-b-10">TICKET #<?= $soporte->id ?></h2>

      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title"><i class="far fa-life-ring"></i> Datos del Ticket</h4>
        </div>
        <div class="panel-body border p-b-0">
          <div class="list-group">

            <div class="list-group-item ticket-details-children">
              <span class="title">Asunto: <?= $soporte->asunto ?></span>
            </div>

            <div class="list-group-item ticket-details-children">
              <span class="title">Fecha apertura: <?= date('d/m/Y - h:i a', strtotime($soporte->fecha_soporte)) ?></span>
            </div>

            <div class="list-group-item ticket-details-children">
              <span class="title">Estado : <?= $soporte->estado ?></span>
            </div>

            <div class="list-group-item ticket-details-children">
              <span class="title">Fecha de cierre : <?= $fecha_c ?></span>
            </div>

            <div class="list-group-item ticket-details-children">
              <span class="title">Solicitante : <?= $soporte->solicitante ?></span>
            </div>

            <div class="list-group-item ticket-details-children">
              <span class="title">Fecha visita : <?= date('d/m/Y - h:i a', strtotime($soporte->fechavisita)) ?></span>
            </div>

            <div class="list-group-item ticket-details-children">
              <span class="title">Turno : <?= $soporte->turno ?></span>
            </div>

            <div class="list-group-item ticket-details-children">
              <span class="title">Agendado por : <?= $soporte->agendado ?></span>
            </div>

          </div>
        </div>
      </div>
    </div><!-- end info ticket -->
    <!-- info del cliente -->
    <div class="btndatauser print" style="display: none">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title"><i class="far fa-life-ring"></i> Información del Cliente</h4>
        </div>
        <div class="panel-body border p-b-0">
          <div class="list-group">
            <div class="list-group-item ticket-details-children">
              <div class="table-responsive">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Plan</th>
                      <th>Costo</th>
                      <th>Instalado</th>
                      <th>IP</th>
                      <th>Router</th>
                      <th>Dirección</th>
                    </tr>

                  </thead>
                  <tbody>
                    <tr>
                      <td><?= $soporte->plan ?></td>
                      <td>$<?= $soporte->costo ?> MNX</td>
                      <td><?= date('d/m/Y', strtotime($soporte->instalado)) ?></td>
                      <td><?= $soporte->ip ?></td>
                      <td><?= $soporte->nodo ?></td>
                      <td><?= $soporte->direccion ?></td>
                      <!--<td> <a class="datacoor" href="javascript:void(0)" data-mapa="3555"> -->
                      <!-- </a> </td>-->
                      <!-- <td><button class="btn btn-blue btn-sm d-print-none" data-container="body" data-placement="left" data-toggle="tooltip" title="" onclick="getrutamaps('3555')" data-original-title="Crear ruta de como llegar a la dirección del servicio"><i class="fas fa-location-arrow"></i></button></td> -->
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="list-group-item ticket-details-children">
              <span class="title">Dirección Principal: <?= $soporte->direccion ?></span>
            </div>

            <div class="list-group-item ticket-details-children">
              <span class="title">Ubicación : <?= $soporte->ubicacion ?></span>
            </div>

            <div class="list-group-item ticket-details-children">
              <span class="title">Teléfono : <?= $soporte->telefono ?></span>
            </div>

            <div class="list-group-item ticket-details-children">
              <span class="title">Móvil : <?= $soporte->movil ?></span>
            </div>

            <style type="text/css">
              .tooltip {
                pointer-events: none;
              }
            </style>

          </div>
        </div>
      </div>
    </div>
    <!-- end info cliente -->

    <!-- ticket -->
    <!-- recuperar la etiquta del puto controlador -->
    <?= $div ?>
    <!-- end ticket -->
  </div>

  <!--======================================================================= -->
  <div class="tab-pane fade" id="default-tab-2">
    <form class="form-horizontal" id="frm-replynota" method="post" action="ticket/add_note" enctype="multipart/form-data">
      <div style="background: #f5f5f5;margin-bottom: 20px;border: 1px solid rgb(211, 216, 222);padding: 0px 5px;">
        <input type="hidden" name="idticket" value="<?= $soporte->id ?>">
        <input type="hidden" name="idtecnico" value="<?= $_SESSION['id_usuario8291'] ?>">

        <textarea class="textarea form-control" id="htmlnota" name="htmlnota" rows="12" style="line-height: 13px;" placeholder="Ingrese una respuesta ...">
        </textarea>

        <div class="inner-container" style="display: block;padding: 10px">

          <div style="height: 40px;">
            <div class="pull-left"><button type="button" class="btn btn-default" onclick="$('.btnsup2').slideToggle()"><i class="far fa-file"></i> Adjuntar archivo</button></div>

            <?php $buttons = "";
            if ($unanswered_ticket >= 2) {
              $buttons = '
              <div class="pull-right"><button type="submit" class="btn btn-success"><i class="fas fa-reply"></i> Agregar nota</button></div>';
            } else if ($answered_ticket >= 2) {
              $buttons = '
              <div class="pull-right"><button type="submit" class="btn btn-success"><i class="fas fa-reply"></i> Agregar nota</button></div>';
            } else if ($closed_ticket >= 2) {
              $buttons = '
              <div class="pull-right"><button type="submit" class="btn btn-success"><i class="fas fa-reply"></i> Agregar nota</button></div>';
            } else {
              $buttons;
            }


            echo $buttons;
            ?>
          </div>


          <div class="row btnsup2" style="display: none">
            <div id="fileuploads2"></div>
            <div class="col-md-12 m-b-5">
              <a class="btn btn-white m-t-15" onclick="addfile2()"><i class="fas fa-plus"></i> Añadir m&aacute;s</a>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
  <!-- ============================================================================================ -->
  <div class="tab-pane fade" id="default-tab-3">
    <div class="panel panel-info panel-alltickets">
      <div class="panel-heading">
        <h4 class="panel-title">Todos los tickets registrados</h4>
        <div class="panel-heading-btn">
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fas fa-expand"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onclick="updateall()"><i class="fas fa-sync-alt"></i></a>
        </div>
      </div>
      <div class="panel-body">


        <table id="data-alltickets" class="nowrap display table table-striped table-bordered dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%">
          <thead>
            <tr role="row">
              <th class="all sorting_asc" aria-sort="ascending">N°</th>
              <th class="sorting">Asunto</th>
              <th class="sorting">Fecha</th>
              <th class="sorting">T&eacute;cnico</th>
              <th class="sorting">Estado</th>
              <th class="sorting">Abierto por</th>
              <th class="sorting">&Uacute;ltima Rspta.</th>
            </tr>
          </thead>
        </table>

      </div>
    </div>

  </div>
  <!-- =============================================================================================== -->
  <div class="tab-pane fade row" id="default-tab-4">
    <form id="soporte-config" class="col-md-8" action="ticket/update_ticket_option" method="post">
      <div class="col-sm-12">
        <div class="form-group">
          <label>Asunto</label>
          <input class="form-control" name="asunto" type="text" value="<?= $soporte->asunto ?>">
          <input type="hidden" name="id_soporte" value="<?= $soporte->id ?>">
        </div>
      </div>

      <div class="col-sm-6 float-left">
        <div class="form-group">
          <label>Departamento</label>
          <select class="form-control sl2" style="width: 100%" name="dp">
            <?= $dep ?>
          </select>
        </div>
      </div>

      <div class="col-sm-6 float-left">
        <div class="form-group">
          <label>Técnico</label>
          <select name="tecnico" style="width: 100%" class="form-control sl2">
            <?= $users ?>
          </select>
        </div>
      </div>

      <div class="col-sm-6 float-left">
        <div class="form-group">
          <label>Estado ticket</label>
          <select class="form-control sl2" style="width: 100%" name="estado">
            <option value="abierto">abierto</option>
            <option value="respondido">respondido</option>
            <option value="cerrado">cerrado</option>
          </select>
        </div>
      </div>

      <div class="col-sm-6 float-left">
        <div class="form-group">
          <label>Nombre del solicitante</label>
          <input class="form-control" name="solicitante" type="text" value="<?= $soporte->solicitante ?>">
        </div>
      </div>


      <div class="col-sm-6 float-left">
        <div class="form-group">
          <label style="width:100%">Visita agendada para</label>
          <input class="form-control datetk" name="fechavisita" id="fechavisita" readonly placeholder="<?= date('Y-m-d H:i a', strtotime($soporte->fechavisita)) ?>">
          <label>Turno</label>
          <select class="form-control sl2" style="width: 100%" name="turno">
            <option value=" (MAÑANA)">Turno mañana</option>
            <option value=" (TARDE)">Turno tarde</option>
          </select>
        </div>
      </div>

      <div class="col-sm-6 float-left">
        <div class="form-group">
          <label>Agendado desde</label>
          <select class="form-control sl2" style="width: 100%" name="agendado">
            <option value="Oficina">Oficina</option>
            <option value="Cliente">Cliente</option>
            <option value="Via Telefonica">Vía telefónica</option>
            <option value="Presencial">Presencial</option>
            <option value="Pagina Web">Página Web</option>
            <option value="Red Social">Redes sociales</option>
          </select>
        </div>
      </div>
      <div class="col-sm-6 float-left">
        <div class="form-group">
          <label>Fecha y hora cierre</label>
          <input class="form-control" readonly name="fecha_cerrado" type="text" value="<?= $fecha_c ?>">
        </div>
      </div>

      <div class="col-sm-12 float-left">
        <div class="form-group">
          <label>Motivo del cierre de ticket</label>
          <textarea class="form-control" name="motivo_cierre" id="motivo_cierre"><?= $soporte->motivo_cierre ?></textarea>
        </div>
      </div>

      <?php $buttons = "";
      if ($unanswered_ticket >= 2) {
        $buttons = '<button type="submit" class="btn btn-primary"><i class="fas fa-save"> Guardar Cambios</i></button>';
      } else if ($answered_ticket >= 2) {
        $buttons = '<button type="submit" class="btn btn-primary"><i class="fas fa-save"> Guardar Cambios</i></button>';
      } else if ($closed_ticket >= 2) {
        $buttons = '<button type="submit" class="btn btn-primary"><i class="fas fa-save"> Guardar Cambios</i></button>';
      } else {
        $buttons;
      }
      ?>
      <div class="col-sm-12 row">
        <?php echo $buttons ?>
      </div>

    </form>
  </div>

</div>


<script>
  $('[name="estado"] option[value="<?= $soporte->estado ?>"]').prop('selected', true);
  $('[name="dp"] option[value="<?= $soporte->dp ?>"]').prop('selected', true);
  $('[name="tecnico"] option[value="<?= $soporte->idsoporte ?>"]').prop('selected', true);
  $('[name="agendado"] option[value="<?= $soporte->agendado ?>"]').prop('selected', true);
  $('[name="turno"] option[value="<?= $soporte->turno ?>"]').prop('selected', true);
  $('.sl2').select2();
  $('#soporte-config').ajaxForm({
    success: function(data) {
      $('#gritter-notice-wrapper').remove();
      loadurl("#ticket/view_ticket?id=<?= $soporte->id ?>");
      alerta('exito', 'Datos guardado correctamente');
    },
    error: function(response) {
      $('#gritter-notice-wrapper').remove();
      alerta('error500', response['responseText']);
    }
  })

  $('.datetk').datetimepicker({
    format: 'D MMMM YYYY h:mm a',
    locale: 'es',
    ignoreReadonly: true,
    showClose: true,
    stepping: 5,
  });
  $('#fechavisita').val('<?= $soporte->fechavisita ?>');
  $('#fechavisita').on('dp.change', function(e) {
    $('#fechavisita').val(moment(e.date).format("YYYY-MM-DD H:mm:ss"));
  });

  $('.btn-closetk').on('click', function() {
    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Esta seguro que desea responder y marcar como cerrado este ticket?',
      title: '<i class="far fa-question-circle fa-lg icodialog"></i> Cerrar Ticket',
      buttons: {
        Eliminar: {
          text: 'SI, Responder y Cerrar', // With spaces and symbols
          action: function() {
            var variables = $("#frm-replysoporte").serialize();
            $.post("ticket/reply_close?" + variables)
              .done(function(data) {
                loadurl("#ticket/view_ticket?id=<?= $soporte->id ?>");
                alerta('exito', 'Ticket respondido y cerrado');
              });
          }
        }
      }
    });
  });

  function addfile() {
    $('#fileuploads').append('<input type="file" name="adjunto[]" class="form-control-file" accept="image/*,.pdf,.zip" style="margin-bottom:10px;" capture="camera">')
  }

  function addfile2() {
    $('#fileuploads2').append('<input type="file" name="adjunto[]" class="form-control-file" accept="image/*,.pdf,.zip" style="margin-bottom:10px;" capture="camera">')
  }

  function deleteitem(id, idticket, idcliente) {

    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Esta seguro que desea eliminar esta respuesta y los archivos adjuntos?',
      title: '<i class="far fa-question-circle fa-lg icodialog"></i> Eliminar respuesta',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt icodialog"></i> Si, eliminar', // With spaces and symbols
          action: function() {
            $.post("ticket/delete_reply", {
                id: id,
                idticket: idticket,
                idcliente: idcliente
              })
              .done(function(data) {
                $('.item-' + id).remove();
                alerta('exito', 'Respuesta eliminada correctamente');
              });

          }
        }
      }
    });
  }
  var all_tickets;

  $('#htmlreply,#htmlnota').wysihtml5({
    locale: "es-ES",
    toolbar: {
      "fa": true,
    }
  });

  $('.imguser').initial({
    height: 80,
    width: 80,
    charCount: 2,
    color: '#348fe2',
    fontSize: 40,
    fontWeight: 500
  });

  $('.imgtecnico').initial({
    height: 80,
    width: 80,
    charCount: 2,
    color: '#975CAF',
    fontSize: 40,
    fontWeight: 500
  });

  $('#frm-replysoporte').ajaxForm({
    success: function(data) {
      $('#gritter-notice-wrapper').remove();
      loadurl("#ticket/view_ticket?id=<?= $soporte->id ?>");
      alerta('exito', 'Respuesta enviada correctamente');
    },
    beforeSubmit: function() {
      if (!$('#htmlreply').val()) {
        $('#gritter-notice-wrapper').remove();
        alerta('error', 'No ha indicado ninguna respuesta');
        return false;
      }
      alerta('loader', 'Subiendo archivos adjuntos...')
    },
    error: function(response) {
      $('#gritter-notice-wrapper').remove();
      alerta('error500', response['responseText']);
    }
  })

  $('#frm-replynota').ajaxForm({
    success: function(data) {
      $('#gritter-notice-wrapper').remove();
      loadurl("#ticket/view_ticket?id=<?= $soporte->id ?>");
      alerta('exito', 'Nota registrada correctamente');
    },
    beforeSubmit: function() {
      if (!$('#htmlnota').val()) {
        $('#gritter-notice-wrapper').remove();
        alerta('error', 'No ha indicado ninguna nota');
        return false;
      }
      alerta('loader', 'Subiendo archivos adjuntos...')
    },
    error: function(response) {
      $('#gritter-notice-wrapper').remove();
      alerta('error500', response['responseText']);
    }
  })

  updateall = function() {
    all_tickets.ajax.reload(null, false);
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
  }


  configtable('#data-alltickets', 'alltickets');
  all_tickets = $('#data-alltickets').DataTable({
    "ajax": "ticket/get_all_tickets_client?idcliente=<?= $soporte->idcliente ?>",
    "deferRender": true,
    "aoColumns": [{
        mData: 'id',
        sWidth: "60px"
      },
      {
        mData: 'asunto'
      },
      {
        mData: 'fecha'
      },
      {
        mData: 'tecnico'
      },
      {
        mData: 'estado'
      },
      {
        mData: 'operador'
      },
      {
        mData: 'respuesta'
      }
    ],

    initComplete: function(oSettings, json) {}
  }).on('processing.dt', function(e, settings, processing) {
    if (processing) {
      loaderin('.panel-alltickets');
    } else {
      loaderout('.panel-alltickets');
    }
  }).on('draw', function() {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
  });

  App.restartGlobalFunction();
  App.setPageTitle('Ver ticket');
</script>