<div class="panel panel-info panel-tickets">
    <div class="panel-heading">
        <h4 class="panel-title">Historial de tickets</h4>
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fas fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onclick="updatesoporte()"><i class="fas fa-sync-alt"></i></a>
        </div>
    </div>
    <div class="panel-body">


        <table id="data-tickets" class="nowrap display table table-striped table-bordered dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%">
            <thead>
                <tr role="row">
                    <th class="all sorting_asc" aria-sort="ascending">N°</th>
                    <th class="sorting">Departamento</th>
                    <th class="sorting">Asunto</th>
                    <th class="sorting">T&eacute;cnico</th>
                    <th class="sorting">Fecha</th>
                    <th class="sorting">Estado</th>
                    <th class="sorting">Abierto por</th>
                    <th class="sorting">&Uacute;ltima Rspta.</th>
                    <th class="all text-center sorting" style="max-width: 40px !important; width: 40px;" data-orderable="false">Acción</th>
                </tr>
            </thead>
        </table>

    </div>
</div>

<!-- =============== MODAL NEW TICKET ========================== -->
<div class="modal" id="modal_new" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nuevo Ticket</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
            </div>

            <form id="soporte-form" method="post" action="ticket/newticket" class="form-horizontal">
                <div class="modal-body row" style="width: 100%">
                    <input type="hidden" value="nonull" name="action">
                    <div class="col-md-6 m-b-5">
                        <label>Cliente</label>
                        <input type="text" class="form-control" id="searchsocio" name="cliente" required="" value="<?= $cliente->nombre ?>" readonly>
                        <input type="hidden" class="form-control" name="idcliente" id="idcliente" required="" value="<?= $cliente->id ?>">
                    </div>

                    <div class="col-md-6 m-b-5">
                        <label>Departamento</label>
                        <select class="form-control sl2 select2-hidden-accessible" id="dp" name="dp" style="width: 100%" data-placeholder="Seleccionar">
                            <?= $tbldepa ?>
                        </select>
                    </div>


                    <div class="col-md-6 m-b-5">
                        <label>Técnico</label>
                        <select class="form-control sl2 select2-hidden-accessible" id="idsoporte" name="idsoporte" style="width: 100%" data-placeholder="Sin asignar">
                            <option value="0">Sin asignar</option>
                            <?= $tbluser ?>
                        </select>
                    </div>

                    <div class="col-md-6 m-b-5">
                        <label>Asunto del ticket</label>
                        <input class="form-control" name="asunto" id="lista" list="listasp" required="">
                        <datalist id="listasp">
                            <option value="Disponibilidad del servicio por falla técnica"></option>
                            <option value="Lentitud o baja velocidad del servicio"></option>
                            <option value="Problemas con la antena o baja señal de equipo CPE"></option>
                            <option value="Problemas del servicio por ausencia o falla en potencia óptica"></option>
                            <option value="Problemas con el router o señal WIFI"></option>
                            <option value="Cambio de clave WIFI"></option>
                            <option value="Bloqueo de servicios o accesos a internet"></option>
                            <option value="Falla masiva"></option>
                            <option value="Congelamiento temporal del servicio"></option>
                            <option value="Traslado del servicio"></option>
                            <option value="Cambio de condiciones del servicio"></option>
                            <option value="Suspensión Injustificada"></option>
                            <option value="Cesión de contrato"></option>
                            <option value="Servicios adicionales"></option>
                            <option value="Equipos en comodato"></option>
                            <option value="Terminación de contrato"></option>
                            <option value="Certificaciones y paz y salvo"></option>
                            <option value="Fidelización"></option>
                            <option value="Reclamo sobre reporte a centrales de riesgos"></option>
                            <option value="Cambio de periodos de facturación"></option>
                            <option value="Reclamo sobre facturación"></option>
                            <option value="Descuento o compensación"></option>
                            <option value="Recurso de reposición"></option>
                            <option value="Recurso de reposición y en subsidio de apelación"></option>
                            <option value="Cumplimiento de una orden de la SIC"></option>
                            <option value="Sugerencias"></option>
                            <option value="Otras PQR"></option>
                        </datalist>
                    </div>

                    <div class="col-md-6 m-b-5">
                        <label>Nombre solicitante</label>
                        <input class="form-control" id="solicitante" name="solicitante" type="text">
                    </div>

                    <div class="col-md-6 m-b-5">
                        <label style="width: 100%">Fecha visita</label>
                        <input class="form-control datetk" name="fechavisita" id="fechavisita" style="width: 50%;float: left;" readonly placeholder="Seleccionar fecha">
                        <select class="form-control sl2 select2-hidden-accessible" name="turno" style="width: 50%" tabindex="-1" aria-hidden="true">
                            <option value=" (MAÑANA)">Turno mañana</option>
                            <option value=" (TARDE)">Turno tarde</option>
                        </select>
                    </div>


                    <div class="col-sm-6 m-b-5">
                        <label>Agendado desde</label>
                        <select class="form-control sl2 select2-hidden-accessible" name="agendado" style="width: 100%" data-select2-id="1659" tabindex="-1" aria-hidden="true">
                            <option value="Oficina" data-select2-id="1661">Oficina</option>
                            <option value="Cliente">Cliente</option>
                            <option value="Via Telefonica">Vía telefónica</option>
                            <option value="Presencial">Presencial</option>
                            <option value="Pagina Web">Página Web</option>
                            <option value="Red Social">Redes sociales</option>
                        </select>
                    </div>

                    <div class="col-md-12 m-b-5">
                        <label>Archivo Ajdunto (evidencia)</label>
                        <div id="fileuploads">
                        </div>
                    </div>

                    <div class="col-md-12 m-b-5">
                        <a class="btn btn-white m-t-15" onclick="addfile()"><i class="fas fa-plus"></i> Añadir imagen</a>
                    </div>

                    <div class="col-lg-12 m-t-15">
                        <textarea class="textarea form-control" id="htmltk" name="contenido" rows="12" placeholder="Ingresar texto del ticket">
            </textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
                    <?php $button = "";
                    if ($record >= 2) {
                        $button = '<button type="submit" class="btn btn-sm btn-success btn-newservice btn-loader" data-loading-text="<i class=\'fas fa-spinner fa-spin\'></i> registrando">Abrir ticket</button>';
                    };
                    echo $button; ?>
                </div>
            </form>
        </div>
    </div>
</div>

<style type="text/css">
    .select2-dropdown {
        z-index: 1960 !important;
    }
</style>
<!-- =============== END MODAL NEW TICKET ====================== -->


<script>
    function closep() {
        $('*[data-loadajax]').prop("disabled", false);
        $('*[data-loadajax]').popover('dispose');
    }

    function close_ticket(id) {
        $.confirm({
            theme: 'supervan',
            draggable: false,
            closeIcon: true,
            onContentReady: function() {
                $('#motivocierre').focus();
            },
            animationBounce: 2.5,
            escapeKey: false,
            content: 'Esta seguro que desea cerrar el ticket  #' + id + '?<br><br><label>Indica el motivo de cierre de este ticket</label><br><textarea class="p-5"  name="motivocierre" id="motivocierre" rows="3" cols="50">',
            title: '<i class="far fa-question-circle fa-lg icodialog"></i> Cerrar Ticket',
            buttons: {
                Eliminar: {
                    text: '<i class="fas fa-times icodialog"></i> Si, Cerrar', // With spaces and symbols
                    action: function() {
                        $.post("ticket/close_ticket", {
                                id: id,
                                motivo: $('#motivocierre').val()
                            })
                            .done(function(data) {
                                updatesoporte();
                                alerta('exito', 'Ticket de soporte cerrado correctamente');

                            });

                    }
                }
            }
        });
    }

    var ticktes;
    var base_url = "<?= base_url(); ?>";

    var searchsocio = document.getElementById('searchsocio');
    var idcliente = document.getElementById('idcliente');
    var dp = document.getElementById('dp');
    var idsoporte = document.getElementById('idsoporte');
    var lista = document.getElementById('lista');
    var solicitante = document.getElementById('solicitante');
    var htmltk = document.getElementById('htmltk');

    function emptyinputs() {
        searchsocio.value = "";
        idcliente.value = "";
        dp.value = "";
        idsoporte = "";
        lista.value = "";
        solicitante.value = "";
        htmltk.value = "";
    }

    //Reload tabla soporte
    updatesoporte = function() {
        ticktes.ajax.reload(null, false);
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
    }

    configtable('#data-tickets', 'tickets');

    ticktes = $('#data-tickets').DataTable({
        "ajax": "clientes/get_tickets_record?id=<?= $id ?>&dep=TODOS&id_=TODOS",
        "deferRender": true,
        "aoColumns": [{
                mData: 'id',
                sWidth: "60px"
            },
            {
                mData: 'departamento'
            },
            {
                mData: 'asunto'
            },
            {
                mData: 'tecnico'
            },
            {
                mData: 'fecha'
            },
            {
                mData: 'estado'
            },
            {
                mData: 'operador'
            },
            {
                mData: 'respuesta'
            },
            {
                mData: 'tool',
                sClass: 'text-center',
                sWidth: "120px"
            }
        ],

        initComplete: function(oSettings, json) {



            $('.not-search-input').html('');

            //------->Button Col
            $('#data-tickets_wrapper .dt-buttons').append('');
            $("#data-tickets_wrapper div.tooltablas")
                .html('<div class="grupotabla m-r-5">\
  		 <button type="button" data-toggle="modal" data-target="#modal_new" class="btn btn-default" ><i class="fas fa-plus"></i> <b>Nuevo</b></button>\
       </div>');


            //select all tickets
            $("#data-tickets_wrapper div.tooltablas div.grupotabla")
                .append('<select class="slstatesuser form-control" name="tblme" Onchange="loaddepa();">' +
                    '<option value="TODOS">Todos Ticket</option>' +
                    '<option value="<?= $_SESSION['id_usuario8291'] ?>">Mis Tickets</option>' +
                    '</select>');
            //end select all tickets
            //select departamentos
            $("#data-tickets_wrapper div.tooltablas div.grupotabla")
                .append('<select class="sldep form-control" name="tbldep" Onchange="loaddepa();">' +
                    '<option value="TODOS">Todos Departamento</option>' +
                    "<?= $tbldepa ?>" +
                    '</select>');
            //end select departamentos

            $(".slstatesuser option[value='TODOS']").prop('selected', true);
            $(".sldep option[value='TODOS']").prop('selected', true);


            $('[name="tbldep"]').select2();
            $('[name="tblme"]').select2();
            if ($('[name="tbldep"]').val() == 1 && $('[name="tblme"]').val() == 1) {
                $('.allsp').show();
                $('.allat').hide();
            } else if ($('[name="tbldep"]').val() == 2 && $('[name="tblme"]').val() == 2) {
                $('.allsp').hide();
                $('.allat').show();
            } else {
                $('.allsp').hide();
                $('.allat').hide();
            }
        }
    }).on('processing.dt', function(e, settings, processing) {
        if (processing) {
            loaderin('.panel-tickets');
        } else {
            loaderout('.panel-tickets');
        }
    }).on('draw', function() {
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
    });
    //datatable update for selects 
    loaddepa = function() {
        if ($('[name="tbldep"]').val() == 1 && $('[name="tblme"]').val() == 1) {
            $('.allsp').show();
            $('.allat').hide();
        } else if ($('[name="tbldep"]').val() == 2 && $('[name="tblme"]').val() == 2) {
            $('.allsp').hide();
            $('.allat').show();
        } else {
            $('.allsp').hide();
            $('.allat').hide();
        }
        ticktes.ajax.url("clientes/get_tickets_record?id=<?= $id ?>&dep=" + $('[name="tbldep"]').val() + "&id_=" + $('[name="tblme"]').val()).load();
    }

    /* $('.datetk').datepicker({
        todayHighlight: true,
        calendarWeeks: true,
        autoclose: true,
        language: "es",
    }); */

    $('.datetk').datetimepicker({
        format: 'D MMMM YYYY h:mm a',
        locale: 'es',
        ignoreReadonly: true,
        showClose: true,
        stepping: 5,
    }).on('dp.change', function(e) {
        $('#fechavisita').val(moment(e.date).format("YYYY-MM-DD H:mm:ss"));
    });



    $('.sl2').select2();

    $('#htmltk').wysihtml5({
        locale: "es-ES",
        toolbar: {
            "fa": true,
        }
    });


    function addfile() {
        $('#fileuploads').append('<input type="file" name="adjunto[]" class="form-control-file" accept="image/*,.pdf,.zip" style="margin-bottom:10px;" capture="camera">')
    }

    //Delete ticket
    function delete_ticket(id) {
        $.confirm({
            theme: 'supervan',
            draggable: false,
            closeIcon: true,
            animationBounce: 2.5,
            escapeKey: false,
            content: 'Esta seguro que desea eliminar todo el contenido del ticket  #' + id + '?',
            title: '<i class="far fa-question-circle fa-lg icodialog"></i> Eliminar Ticket',
            buttons: {
                Eliminar: {
                    text: '<i class="far fa-trash-alt icodialog"></i> Si, eliminar', // With spaces and symbols
                    action: function() {
                        $.post("ticket/delete_ticket", {
                                id: id
                            })
                            .done(function(data) {

                                updatesoporte();
                                alerta('exito', 'Ticket de soporte eliminado correctamente');

                            });

                    }
                }
            }
        });
    }
    //End delete ticket
    //New ticket
    $('#soporte-form').ajaxForm({
        success: function(data) {
            $('#gritter-notice-wrapper').remove();
            //loadurl("#ticket/newticket");
            alerta('exito', 'Ticket registrado correctamente');
            emptyinputs();
            updatesoporte();
        },
        beforeSubmit: function() {
            $('#modal_new').modal('hide');
        },
        error: function(response) {
            $('#gritter-notice-wrapper').remove();
            alerta('error500', response['responseText']);
        }
    })

    //end new ticket
    //View ticket y all tickets tap 3
    function view_ticket(id, idcliente) {
        $(location).attr('href', "#ticket/view_ticket?id=" + id);
    }

    /* function get_all_tickets_client(idcliente){
      $(location).attr('href',"#ticket/get_all_tickets_client?idcliente="+idcliente);
    } */

    //end view ticket
</script>