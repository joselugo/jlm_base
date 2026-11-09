<div class="panel panel-blue panel-sms-e">
    <div class="panel-heading">
        <h4 class="panel-title">SMS ENVIADOS</h4>
        <div class="panel-heading-btn">
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fas fa-expand"></i></a>
            <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onclick="update_enviados()"><i class="fas fa-sync"></i></a>
        </div>

    </div>
    <div class="panel-body">
        <div class="alert alert-success" role="alert">
            Esta pagina se actualiza en tiempo real.
        </div>
        <table id="data-sms-e" data-order="[[ 0, &quot;desc&quot; ]]" class="display nowrap table table-striped table-bordered dataTable no-footer dtr-inline collapsed" style="width: 100%;">
            <thead>
                <tr role="row">
                    <th class="sorting_desc">ID</th>
                    <th class="sorting">Cliente</th>
                    <th class="sorting">Sucursal</th>
                    <th class="sorting">Fecha</th>
                    <th class="sorting">Nº Destino</th>
                    <th class="text-center sorting">Estado</th>
                    <th class="sorting">Mensaje</th>
                    <th class="text-center sorting">Log</th>
                    <th class="all sorting_disabled" data-orderable="false">Acción</th>
                </tr>
            </thead>

        </table>



    </div>
</div>
<script>
    function conteruser() {
        if ($(".listaclientes input:checked").length > 0) {
            $('.cssconter').html('Total Clientes seleccionados (' + $(".listaclientes input:checked").length + ')');
        } else {
            $('.cssconter').html('Total Clientes seleccionados (0)');
        }
    }

    $(function() {


        $("#smscliente").select2({
            placeholder: "Seleccionar Cliente",
            minimumInputLength: 3,
            ajax: {
                url: function() {
                    return 'ajax/soporte?action=listauser&tipo=sms';
                },
                dataType: "json",
                delay: 200,
                type: "GET",
                data: function(params) {

                    var queryParameters = {
                        term: params.term
                    }
                    return queryParameters;
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(item) {
                            return {
                                text: item.nombre,
                                id: item.id + '-' + item.movil
                            }
                        })
                    };
                }
            }

        });

        $('#smscliente').on("select2:select", function(e) {
            var valsms = $(this).val().split("-");

            if (!valsms[1]) {
                alerta('error', 'El cliente ' + valsms[0] + ' no cuenta con un Nº móvil configurado.')
                return false;
            }
            if ($('input[name="smsadd[' + valsms[0] + ']"]').length) {
                alerta("error", "El cliente ya esta agregado");
                $('#smscliente').select2('val', '');
                $("#smscliente").html('');
            } else {
                $(".listacli").append('<div class="checkbox checkbox-css col-md-6 col-xs-6" style="margin-top: -5px;font-size: 12px;" onclick="conteruser()"><input type="checkbox" name="smsadd[' + valsms[0] + ']" value="' + valsms[1] + '" checked="" id="ch_sms_8"><label for="ch_sms_8">' + $("#smscliente option:selected").text() + '</label></div>');
                $('#smscliente').select2('val', '');
                $("#smscliente").html('');
                $('#smscliente').select2('open');
                conteruser();
                $('#collapsecorreo').show();
                $('#smscliente').select2('val', '');
                $("#smscliente").html('');
                $("#smscliente").select2('close');
            }

        });


        $('[data-toggle="popover"]').popover({
            html: true,
        })

        $("#grupo").change(function() {
            $(".listacli").html('<div><i class="fas fa-spinner fa-lg fa-pulse"></i> Generando lista de clientes</div></div>');
            $.post("ajax/correos", {
                    action: "grupo",
                    id: $('#grupo').val(),
                    estado: $('#smsestado').val()
                })
                .done(function(data) {
                    $(".listacli").empty();
                    if (data['estado'] == 'error') {
                        alerta("error", data['salida']);
                        conteruser();
                    } else {
                        var cont = 0;
                        $.each(data['salida'], function() {
                            $(".listacli").append('<div class="checkbox checkbox-css col-md-6 col-xs-6" style="margin-top: -5px;font-size: 12px;" onclick="conteruser()"><input type="checkbox" name="smsadd[' + this.id + ']" value="' + this.movil + '" checked id="ch_sms_' + cont + '"><label for="ch_sms_' + cont + '">' + this.value + '</label></div>');
                            cont++;
                        });
                        conteruser();
                    }

                });
        });

        $('#smsestado').change(function() {
            if ($("#grupo").val()) {
                $("#grupo").trigger('change');
            }
        });

        $('.sl2').select2();


        $('#sms-form').ajaxForm({
            success: function(data) {
                $('#gritter-notice-wrapper').remove();
                alerta('exito', 'SMS registrados correctamente');
                update_enviados();
            },
            beforeSubmit: function() {
                alerta('loader', 'Enviando SMS...');
                $('#modaltmp').modal('hide');
            },
            error: function(response) {
                $('#gritter-notice-wrapper').remove();
                alerta('error500', response['responseText']);
            }
        })



        $('#modaltmp').on('hidden.bs.modal', function(e) {
            $("[data-toggle='popover']").popover('hide');
        })

    })
</script>


<script>
    update_enviados = function() {
        sms_e.ajax.reload(null, false);
    }

    function picker_sms() {
        $('.input-daterange').datepicker({
            todayHighlight: true,
            autoclose: true,
            language: "es",
            format: "yyyy-mm-dd"
        }).on('changeDate', function(ev) {
            sms_e.ajax.url(base_url + "sms/get_enviados_json?action=listenviados&inicio=" + $('[name="start"]').val() + "&final=" + $('[name="end"]').val()).load();
        });
    }



    var sms_e;
    var base_url = "<?= base_url() ?>";
    $(function() {
        App.setPageTitle('SMS ENVIADOS');

        configtable('#data-sms-e', 'SMS Enviados');
        sms_e = $('#data-sms-e').DataTable({
            "ajax": base_url + "sms/get_enviados_json?action=listenviados&inicio=<?= $inicio ?>&final=<?= $fin ?>",
            "deferRender": true,
            "idDataTables": "1",
            "autoWidth": false,
            "aoColumns": [{
                    mData: 'id',
                    sWidth: "10px"
                },
                {
                    mData: 'user'
                },
                {
                    mData: 'sucursal'
                },
                {
                    mData: 'fecha'
                },
                {
                    mData: 'ndestino'
                },
                {
                    mData: 'estado',
                    sClass: 'text-center'
                },
                {
                    mData: 'mensaje'
                },
                {
                    mData: 'log',
                    bVisible: false
                },
                {
                    mData: 'tool',
                    sWidth: '21px',
                    sClass: 'text-center'
                }
            ],

            initComplete: function(oSettings, json) {

                var sucursales = "<?= $sucursales ?>";
                if (<?= $tipoacceso;?>>=2) {
                    botones = '<button type="button" class="m-l-5 btn btn-white " onclick="getmodal(\'sms/modal_new_sms\',\'Nuevo sms masivo\',\'lg\');"><i class="fas fa-plus"></i> Nuevo </button>';
                }else{
                    botones = '';
                }
                $("#data-sms-e_wrapper div.tooltablas")
                    .html(botones+'<div class="grupotabla btn-group">\
                        <div class="log input-group input-daterange">\
                        <input style="width:70px" type="text" class="form-control" name="start" readonly placeholder="fecha inicio" value="<?= $inicio ?>">\
                        <span class="input-group-addon">al</span>\
                        <input style="width:70px"  type="text" class="form-control" name="end" readonly placeholder="fecha final" value="<?= $fin ?>">\
                        </div>\
                        <select class="form-control sl2su" style="100%"><option></option><option value="%%">Cualquiera</option>' + sucursales + '</select>\
                        </div>');
                picker_sms();
                $(".sl2su").select2({
                    placeholder: 'Sucursal',
                    allowClear: true
                });
            }
        }).on('processing.dt', function(e, settings, processing) {
            if (processing) {
                loaderin('.panel-sms-e');
            } else {
                loaderout('.panel-sms-e');
            }
        }).on('draw', function() {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
        });

        async function update() {
            var table = document.getElementById('data-sms-e');
            $.ajax({
                type: "GET",
                url: "sms/update_table?inicio=" + $('[name="start"]').val() + "&fin=" + $('[name="end"]').val(),
                success: function(response) {
                    response = JSON.parse(response);
                    if (response.length > 0) {
                        var data = sms_e.rows().data();
                        response.forEach(element => {
                            element.id = parseInt(element.id);
                            if (element.stado == "enviado") {
                                span = '<span  class="badge badge-success">ENVIADO</span>';
                            } else {
                                span = '<span class="badge badge-warning">EN ESPERA</span>';
                            }
                            data.each(function(value, index) {
                                if (parseInt(element.id) == parseInt(value.id)) {
                                    value.estado = span;
                                    return false;
                                }
                            });
                            for (var i = 1, row; row = table.rows[i]; i++) {
                                if (element.id == parseInt(row.cells[0].innerHTML)) {
                                    row.cells[5].innerHTML = span;
                                    break;
                                }
                            }
                        });
                    }
                }
            });
            setTime = await setTimeout(update, 20000);
        }

        setTimeout(update, 20000);
    })
</script>