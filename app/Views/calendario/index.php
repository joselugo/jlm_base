<div class="panel panel-blue panel-Usuarios">
  <div class="panel-heading">
    <h4 class="panel-title">Calendario</h4>
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fas fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onclick="refresh_()"><i class="fas fa-sync-alt"></i></a>
    </div>
  </div>
  <div class="panel-body">

    <div id="calendar"></div>

  </div>
</div>



<div class="row">
  <div class="col-sm-12" style="margin-top: 10px;">
    <div class="row">
      <div class="col-md-3" style="display:none">
        <div class="box box-solid">
          <div class="box-header with-border">
            <h4 class="box-title">Tipo</h4>
          </div>
          <div class="box-body">
            <!-- the events -->
            <div id="external-events">

            </div>


          </div>
          <!-- /.box-body -->
        </div>
        <!-- /. box -->
        <div class="box box-solid">
          <div class="box-header with-border">
            <h3 class="box-title">Crear Evento</h3>
          </div>
          <div class="box-body">
            <div class="btn-group" style="width: 100%; margin-bottom: 10px;">
              <!--<button type="button" id="color-chooser-btn" class="btn btn-info btn-block dropdown-toggle" data-toggle="dropdown">Color <span class="caret"></span></button>-->
              <ul class="fc-color-picker" id="color-chooser">
                <li><a class="text-aqua" href="#"><i class="fa fa-square"></i></a></li>
                <li><a class="text-blue" href="#"><i class="fa fa-square"></i></a></li>
                <li><a class="text-light-blue" href="#"><i class="fa fa-square"></i></a></li>
                <li><a class="text-teal" href="#"><i class="fa fa-square"></i></a></li>
                <li><a class="text-yellow" href="#"><i class="fa fa-square"></i></a></li>
                <li><a class="text-orange" href="#"><i class="fa fa-square"></i></a></li>
                <li><a class="text-green" href="#"><i class="fa fa-square"></i></a></li>
                <li><a class="text-lime" href="#"><i class="fa fa-square"></i></a></li>
                <li><a class="text-red" href="#"><i class="fa fa-square"></i></a></li>
                <li><a class="text-purple" href="#"><i class="fa fa-square"></i></a></li>
                <li><a class="text-fuchsia" href="#"><i class="fa fa-square"></i></a></li>
                <li><a class="text-muted" href="#"><i class="fa fa-square"></i></a></li>
                <li><a class="text-navy" href="#"><i class="fa fa-square"></i></a></li>
              </ul>
            </div>
            <!-- /btn-group -->
            <div class="input-group">
              <input id="new-event" type="text" class="form-control" placeholder="Titulo del evento">

              <div class="input-group-btn">
                <button id="add-new-event" type="button" class="btn btn-primary btn-flat">Agregar</button>
              </div>
              <!-- /btn-group -->
            </div>
            <!-- /input-group -->
          </div>
        </div>
      </div>
      <!-- /.col -->
      <div class="col-md-12">

        <!-- /. box -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

  </div>
</div>


<div class="modal" id="modal_evento" style="z-index:2000">
  <form id="form_evento">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Evento</h5>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="exampleInputEmail1">Sucursal</label>
            <select class="form-control" name="sucursal_e" id="sucursal_e">
              <?= $sucursales ?>
            </select>

          </div>
          <div class="form-group">
            <label for="exampleInputEmail1">Tipo</label>
            <input type="text" class="form-control" name="titulo" id="titulo" readonly="">
          </div>
          <div class="form-group" id="cli">
            <label for="exampleInputEmail1">Cliente</label>
            <input type="text" class="form-control" name="serchwi" id="serchwi">
            <input type="hidden" class="form-control" name="id_cliente" id="id_cliente" readonly="">
            <input type="hidden" class="form-control" name="id" id="id" readonly="">
            <input type="hidden" class="form-control" name="tabla" id="tabla" readonly="">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Descripcion</label>

            <textarea class="textarea form-control" id="descripcion" name="descripcion" placeholder="Ingrese una respuesta ...">

</textarea>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" class="btn btn-danger" id="delete_button">Eliminar Evento</button>
          <button type="button" id="update_evento" class="btn btn-success">Guardar</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </form>
</div><!-- /.modal -->

<div class="modal" id="modal_new_event" style="z-index:2000">
  <form id="form_evento1">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Nuevo evento</h5>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
        </div>
        <div class="modal-body">
          <div class="form-group">

            <div class="form-group">
              <label for="exampleInputEmail1">Sucursal</label>
              <select class="form-control" name="sucursal_n" id="sucursal_n">
                <?= $sucursales ?>
              </select>

            </div>

            <label for="">Tipo</label>
            <select class="bs-select selectpicker form-control select-type" data-show-subtext="true" id="selec-modal-new">
              <option value="#008D4D" data-content="<i class='fa fa-circle text-green'></i> Instalación" selected>Instalacíon</option>
              <option value="#00629C" data-content="<i class='fa fa-circle text-blue'></i> Soporte">Soporte</option>
              <option value="#CF850F" data-content="<i class='fa fa-circle text-yellow'></i> Mantenimiento">Mantenimiento</option>
              <option value="#BA3F30" data-content="<i class='fa fa-circle text-red'></i> Desinstalación">Desinstalación</option>
              <option value="#FF05EC" data-content="<i class='fa fa-circle text-purple'></i> Migración">Migración</option>
              <option value="#001A36" data-content="<i class='fa fa-circle text-navy'></i> Cambio de domicilio">Cambio de domicilio</option>
              <option value="#CC0FA2" data-content="<i class='fa fa-circle text-fuchsia'></i> Capacitacíon">Capacitación</option>
              <option value="#01D95F" data-content="<i class='fa fa-circle text-lime'></i> Otro evento">Otro evento</option>
            </select>
          </div>


          <div class="form-group" id="cli">
            <label for="exampleInputEmail1">Cliente</label>
            <input type="text" class="form-control" name="serchwi-new" id="serchwi-new">
            <input type="hidden" class="form-control" name="id_cliente_new" id="id_cliente_new" readonly="">
            <input type="hidden" class="form-control" name="start_new" id="start_new" readonly="">
          </div>



        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="button" id="button_new_evento" class="btn btn-success">Guardar</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </form>
</div><!-- /.modal -->

<style type="text/css">
  .green {
    color: green !important
  }

  .yellow {
    color: yellow !important
  }

  .white {
    color: white !important
  }

  .blue {
    color: blue !important
  }

  .red {
    color: red !important
  }

  .bootstrap-select.btn-group:not(.input-group-btn),
  .bootstrap-select.btn-group[class*="span"] {
    margin: 0 !important;
  }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>

<link rel="stylesheet" href="<?= base_url(); ?>public/plugins/fullcalendar/fullcalendar.css">

<link rel="stylesheet" href="<?= base_url(); ?>public/plugins/fullcalendar/fullcalendar.print.css" media="print">

<script src="<?= base_url(); ?>public/plugins/moment/moment.min.js"></script>

<script src="<?= base_url(); ?>public/plugins/fullcalendar/fullcalendar.min.js"></script>


<script>
  var base_url = "<?= base_url(); ?>";
  var refresh_;
  var sucursales = "<?= $sucursales ?>";


  // var editor1=CKEDITOR.replace('descripcion',{height: '300px'});
  $('#htmlreply,#descripcion').wysihtml5({
    locale: "es-ES",
    toolbar: {
      "fa": true,
    }
  });
  $(document).ready(function() {

    $(document).on('keyup', '#serchwi-new', function(e) {
      $("#serchwi-new").autocomplete({
        source: base_url + 'clientes/searchcliente?action=listauser',
        minLength: 3,
        open: function(event, ui) {
          $('img[data-name]').initial({
            height: 64,
            width: 64,
            charCount: 2,
            fontSize: 26,
            fontWeight: 600
          });
        },
        select: function(event, ui) {
          $("#id_cliente_new").val(ui.item.id);
        }
      }).data("ui-autocomplete")._renderItem = function(ul, item) {
        return $("<li></li>")

          .data("item.autocomplete", item)
          .append(item.socio)
          .appendTo(ul);
      };
    });

    $(document).on('keyup', '#serchwi', function(e) {
      $("#serchwi").autocomplete({
        source: base_url + 'clientes/searchcliente?action=listauser',
        minLength: 3,
        open: function(event, ui) {
          $('img[data-name]').initial({
            height: 64,
            width: 64,
            charCount: 2,
            fontSize: 26,
            fontWeight: 600
          });
        },
        select: function(event, ui) {
          $("#id_cliente").val(ui.item.id);
        }
      }).data("ui-autocomplete")._renderItem = function(ul, item) {
        return $("<li></li>")

          .data("item.autocomplete", item)
          .append(item.socio)
          .appendTo(ul);
      };
    });

    $('.select-type').selectpicker();


    if (!/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
      $(".sidebar-toggle").trigger("click");
    }

    var zone = "05:30"; //Change this to your timezone

    $.ajax({
      url: base_url + 'calendario/get_eventos?sucursal=TODOS',
      type: 'POST', // Send post data
      data: 'type=fetch',
      async: false,
      success: function(s) {
        json_events = s;

      }
    });

    var currentMousePos = {
      x: -1,
      y: -1
    };
    jQuery(document).on("mousemove", function(event) {
      currentMousePos.x = event.pageX;
      currentMousePos.y = event.pageY;

    });
    var url = base_url + 'calendario/get_tipos';

    $.getJSON(url, function(data) {
      $.each(data.row, function(i, row) {

        var event = $('<div />')
        event.css({
          'background-color': row.color,
          'border-color': row.color,
          'color': '#fff'
        }).addClass('external-event')
        event.html(row.titulo)
        event.attr('data-color', row.color)
        $('#external-events').prepend(event)

        //Add draggable funtionality
        init_events(event)
      })
    })
    /* initialize the external events
    -----------------------------------------------------------------*/


    function init_events(ele) {
      $('#external-events div.external-event').each(function() {

        // store data so the calendar knows to render an event upon drop
        $(this).data('event', {
          title: $.trim($(this).text()),
          tipo: $.trim($(this).text()), // use the element's text as the event title
          backgroundColor: $.trim($(this).attr('data-color')), // use the element's text as the event title
          stick: true, // maintain when user navigates (see docs on the renderEvent method)
          descripcion: "Ingrese una descripcion",
          id_cliente: 328,
          tabla: "calendar",
          nombre: 'Venta al mostrador'

        });

        // make the event draggable using jQuery UI
        $(this).draggable({
          zIndex: 999,
          revert: true, // will cause the event to go back to its
          revertDuration: 0 //  original position after the drag
        });

      });
    }


    /* initialize the calendar
    -----------------------------------------------------------------*/

    $('#calendar').fullCalendar({

      monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
      monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
      dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
      dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'],
      events: JSON.parse(json_events),
      //events: [{"id":"14","title":"New Event","start":"2015-01-24T16:00:00+04:00","allDay":false}],
      utc: true,
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay,listDay'
      },
      buttonText: {
        today: 'Hoy',
        month: 'Mes',
        week: 'Semana',
        day: 'Dia',
        listDay: 'Lista'
      },
      editable: true,
      droppable: true,
      lang: 'es',
      longPressDelay: 1000,
      slotDuration: '00:30:00',
      eventReceive: function(event, data) {

        var title = event.title;
        var descripcion = event.descripcion;
        var start = event.start.format("YYYY-MM-DD[T]HH:mm:SS");
        var color = event.backgroundColor;


        $.ajax({
          url: base_url + 'index.php/calendario/new_evento',
          data: 'type=new&title=' + title + '&startdate=' + start + '&zone=' + zone + '&color=' + color,
          type: 'POST',
          dataType: 'json',
          success: function(response) {

            event.id = response.eventid;
            $('#calendar').fullCalendar('updateEvent', event);
          },
          error: function(e) {
            console.log(e.responseText);

          }
        });
        $('#calendar').fullCalendar('updateEvent', event);
        console.log(event);
      },
      eventDrop: function(event, delta, revertFunc) {



        var title = event.title;
        var start = event.start.format();
        var end = (event.end == null) ? start : event.end.format();
        var id_cliente = event.id_cliente;
        $.ajax({
          url: base_url + 'index.php/calendario/resize',
          data: 'type=resetdate&title=' + title + '&start=' + start + '&end=' + end + '&eventid=' + event.id + '&tabla=' + event.tabla + '&id_cliente=' + id_cliente,
          type: 'POST',
          dataType: 'json',
          success: function(response) {
            if (response.status == 'success') {
              alerta("success", "Evento editado");
            } else {
              alerta("danger", response.status);
              revertFunc();
            }

          },
          error: function(e) {
            revertFunc();
            alerta("danger", e.responseText);
          }
        });
      },

      dayClick: function(date, jsEvent, view) {
        $("#fecha_modal_new").html(" - " + date.format("dddd") + " " + date.format("DD  MMM YYYY"));
        $("#modal_new_event").modal();
        $("#start_new").val(date.format("YYYY-MM-DD[T]HH:mm:SS"));



      },
      eventClick: function(event, jsEvent, view) {

        $("#id_cliente").val(event.id_cliente);
        $("#serchwi").val(event.nombre);
        $("#titulo").val(event.title);
        $("#id").val(event.id);
        $("#tabla").val(event.tabla);
        $("#sucursal_e").val(event.sucursal);

        //  $("#descripcion").val(event.descripcion);
        $('.wysihtml5-sandbox').contents().find('.wysihtml5-editor').html(event.descripcion);
        //  editor1.destroy();
        //  editor1 = null;
        // $('#descripcion').val(event.descripcion);
        // editor1=CKEDITOR.replace('descripcion',{height: '300px'}); 
        $("#modal_evento").modal();



        if (event.tabla == "soporte" || event.tabla == "instalaciones") {
          $("#serchwi").attr("disabled", true);
          $("#sucursal_e").attr("disabled", true);
        } else {
          $("#serchwi").attr("disabled", false);
          $("#sucursal_e").attr("disabled", false);
        }

        if (event.tabla == "pagos") {
          $("#paleta_color").css("display", "none");
          $("#cli").css("display", "none");
        } else {
          $("#paleta_color").css("display", "block");
          $("#cli").css("display", "block");
        }

        $("#update_evento").unbind('click').on("click", function() {
          var color;
          event.descripcion = $("#descripcion").val();
          event.id_cliente = $("#id_cliente").val()
          event.nombre = $("#serchwi").val()
          event.sucursal = $("#sucursal_e").val()

          if (event.tabla == "pagos") {} else {
            event.title = event.tipo + " " + $("#serchwi").val()
          }

          // if(currColor1=="color"){color=event.backgroundColor}
          //  else{color=currColor1;}

          event.backgroundColor = color;
          event.borderColor = color;



          $.ajax({
            url: base_url + 'calendario/update_title',
            type: 'post',
            data: {
              title: event.title,
              descripcion: event.descripcion,
              eventid: event.id,
              id_cliente: event.id_cliente,
              tabla: event.tabla,
              sucursal: event.sucursal
            },
            dataType: 'json',
            success: function(response) {
              $("#modal_evento").modal("toggle");
              if (response.status == 'success') {
                $('#calendar').fullCalendar('updateEvent', event);
                alerta("success", "Evento editado");
              } else {
                alerta("danger", response.status);
                revertFunc();
              }


            },
            error: function(e) {
              alert('Error processing your request: ' + e.responseText);
            }
          });

        })


      },
      eventResize: function(event, delta, revertFunc) {


        var title = event.title;
        var end = event.end.format();
        var start = event.start.format();
        var id_cliente = event.id_cliente;


        $.ajax({
          url: base_url + 'calendario/resize',
          data: 'type=resetdate&title=' + title + '&start=' + start + '&end=' + end + '&eventid=' + event.id + '&tabla=' + event.tabla + '&id_cliente=' + id_cliente,
          type: 'POST',
          dataType: 'json',
          success: function(response) {
            if (response.status == 'success') {
              alerta("success", "Evento editado");
            } else {
              alerta("danger", response.status);
              revertFunc();
            }
          },
          error: function(e) {
            revertFunc();

            console.log("error");
          }
        });
      }
    });

    //$('.fc-center').append('<div class="fc-title-custom"></div>');
    //$('.fc-center').append('<h1><select class="form-control sucursales"><option value="TODOS">TODAS</option>'+sucursales+'</select></h1>');
    $('.fc-center').append('<h6><select class="form-control sucursales"  id="sucursales_e"><option value="TODOS">TODOS</option>' + sucursales + '</select></h6>');
    console.log("inicio select2");
    $('#sucursales_e').select2({
      placeholder: 'Select an option'
    });
    $("#delete_button").on("click", function() {

      var alsup = $.confirm({
        theme: 'supervan',
        draggable: false,
        closeIcon: true,
        animationBounce: 2.5,
        escapeKey: false,
        content: '¿Esta seguro que desea eliminar este evento? <br><b class="text-warning"></b><br><br><br>',
        title: '<i class="far fa-question-circle fa-lg icodialog"></i> ELIMINAR',
        buttons: {
          Eliminar: {
            text: '<i class="far fa-trash-alt"></i> Si, eliminar',
            action: function() {
              this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
              this.buttons.Eliminar.disable();
              $('.jconfirm-closeIcon').remove();
              $.ajax({
                url: base_url + 'calendario/delete_evento',
                data: 'type=remove&eventid=' + $("#id").val() + '&tabla=' + $("#tabla").val(),
                type: 'POST',
                dataType: 'json',
                success: function(response) {

                  if (response.status == 'success') {
                    alerta("success", "Evento eliminado", 2)
                    // $('#calendar').fullCalendar('removeEvents');
                    $("#modal_evento").modal("toggle");
                    refresh_();
                  } else {
                    alerta("error", response.status);
                    $("#modal_evento").modal("toggle");
                  }

                },
                error: function(e) {
                  alert('Error processing your request: ' + e.responseText);
                }
              });


            }
          }
        }
      });

    });

    refresh_ = function getFreshEvents() {
      $('#calendar').fullCalendar('removeEvents');
      $.ajax({
        url: base_url + 'calendario/get_eventos?sucursal=' + $(".sucursales").val(),
        type: 'POST', // Send post data
        data: 'type=fetch',
        async: false,
        success: function(s) {
          freshevents = s;
        }
      });
      $('#calendar').fullCalendar('addEventSource', JSON.parse(freshevents));
    }

    refresh_sucursal = function getFreshEvents() {
      $('#calendar').fullCalendar('removeEvents');
      $.ajax({
        url: base_url + 'calendario/get_eventos?sucursal=' + $(".sucursales").val(),
        type: 'POST', // Send post data
        data: 'type=fetch',
        async: false,
        success: function(s) {
          freshevents = s;
        }
      });
      $('#calendar').fullCalendar('addEventSource', JSON.parse(freshevents));
    }


    function isElemOverDiv() {
      var trashEl = jQuery('#trash');

      var ofs = trashEl.offset();

      var x1 = ofs.left;
      var x2 = ofs.left + trashEl.outerWidth(true);
      var y1 = ofs.top;
      var y2 = ofs.top + trashEl.outerHeight(true);

      if (currentMousePos.x >= x1 && currentMousePos.x <= x2 &&
        currentMousePos.y >= y1 && currentMousePos.y <= y2) {
        return true;
      }
      return false;
    }


    /* ADDING EVENTS */
    var currColor = '#3c8dbc' //Red by default
    var currColor1 = 'color' //Red by default
    //Color chooser button
    var colorChooser = $('#color-chooser-btn')

    $('#color-chooser > li > a').click(function(e) {
      e.preventDefault()
      //Save color
      currColor = $(this).css('color')
      //Add color effect to button
      $('#add-new-event').css({
        'background': currColor,
        'border-color': currColor
      })
    })

    $('#color-chooser1 > li > a').click(function(e) {
      e.preventDefault()
      //Save color
      currColor1 = $(this).css('color')


      //Add color effect to button
      //$('#add-new-event1').css({ 'background': currColor1, 'border-color': currColor1 })
    })

    $('#color-chooser2 > li > a').click(function(e) {
      e.preventDefault()
      //Save color
      currColor = $(this).css('color')
      //Add color effect to button
      $('#add-new-event2').css({
        'background': currColor,
        'border-color': currColor
      })
    })

    $('#add-new-event').click(function(e) {
      e.preventDefault()
      //Get value and make sure it is not null
      var val = $('#new-event').val()
      if (val.length == 0) {
        return
      }

      //Create events
      var event = $('<div />')
      event.css({
        'background-color': currColor,
        'border-color': currColor,
        'color': '#fff'
      }).addClass('external-event')
      event.html(val)
      event.attr('data-color', currColor)
      $('#external-events').prepend(event)

      //Add draggable funtionality
      init_events(event)

      //Remove event from text input
      $('#new-event').val('')
    })



    $(".salir").click(function() {

      window.location.href = "exit.php";

    })

    $('#external-event').draggable();



    $('#list-logsistema').DataTable({
      "language": {
        "url": "<?= base_url(); ?>public/js/Datatables-es.json"
      },
      "ajax": "<?= base_url(); ?>calendario/get_log_calendario_json",
      "processing": true,
      "deferRender": true,
      "order": [
        [0, "asc"]
      ],
      "aoColumns": [{
          mData: 'id',
          "asSorting": ["desc"]
        },
        {
          mData: 'log'
        },
        {
          mData: 'username'
        },
        {
          mData: 'fechalog',
          sType: 'date-uk'
        }
      ],
      dom: 'Bfrtip',
      lengthMenu: [
        [15, 25, 50, 100, -1],
        ['15 Registros', '25 Registros', '50 Registros', '100 Registros', 'Mostrar todos']
      ],
      buttons: [
        'pageLength',
        'colvis',
        {
          extend: 'collection',
          text: '<i class="fa fa-floppy-o"></i> <span class="caret"></span>',
          buttons: [{
              extend: 'print',
              title: 'Lista Router',
              text: '<i class="fa fa-print"></i> Imprimir',
            },
            {
              extend: 'csvHtml5',
              title: 'Lista Router',
              text: '<i class="fa fa-file-excel-o"></i> Exportar a EXCEL',
            },
            {
              extend: 'pdfHtml5',
              title: 'Lista Router',
              text: '<i class="fa fa-file-pdf-o"></i> Exportar a PDF',
            }
          ]
        },

      ],
      "initComplete": function() {


      }

    });


    $('.sucursales').change(function() {


      refresh_sucursal();
    });


    $('#button_new_evento').click(function(e) {
      e.preventDefault();
      var title = $('#selec-modal-new option:selected').html() + " " + $("#serchwi-new").val();
      var tipo = $('#selec-modal-new option:selected').html();
      var color = $("#selec-modal-new").val();
      var start = $("#start_new").val();
      var end = $("#start_new").val();
      var id_cliente = $("#id_cliente_new").val();
      var nombre = $("#serchwi-new").val();
      var sucursal = $("#sucursal_n").val();
      if (id_cliente == "") {
        id_cliente = 328;
        nombre = "Venta al mostrador";
      }







      $.ajax({
        url: base_url + 'calendario/new_evento',
        data: 'type=new&title=' + title + '&startdate=' + start + '&zone=' + zone + '&color=' + color + '&id_cliente=' + id_cliente + '&sucursal=' + sucursal,
        type: 'POST',
        dataType: 'json',
        success: function(response) {

          event.id = response.eventid;
          addCalanderEvent(response.eventid, start, end, title, color, tipo, id_cliente, nombre, sucursal);
          $("#modal_new_event").modal("toggle");
          // $('#calendar').fullCalendar('updateEvent',event);
        },
        error: function(e) {
          console.log(e.responseText);

        }
      });

    });


  });

  function addCalanderEvent(id, start, end, title, color, tipo, id_cliente, nombre, sucursal) {
    var eventObject = {
      title: title,
      start: start,
      end: end,
      id: id,
      color: color,
      stick: true,
      tipo: tipo,
      id_cliente: id_cliente,
      tabla: "calendar",
      nombre: nombre,
      descripcion: "Ingrese una descripcion",
      sucursal: sucursal
    };

    $('#calendar').fullCalendar('renderEvent', eventObject, true);

    return eventObject;
  }
</script>