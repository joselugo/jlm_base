<form id="streaming-form" method="post" action="streaming/save_cliente_cuenta" class="form-horizontal" enctype="multipart/form-data">
    <div class="modal-body row" style="width: 100%">

        <div class="col-md-6 m-b-5">
            <label>Cliente:</label>
            <input type="text" class="form-control" id="searchsocio" name="cliente[cliente]" placeholder="Buscar cliente" required="">
            <input type="hidden" class="form-control" name="cliente[id]" id="idcliente" required="">
        </div>

        <div class="col-md-6 m-b-5">
            <label>Activacion cuenta straming:</label>
            <input class="form-control datetk" name="cliente[alta_cuenta]" id="cliente[alta_cuenta]" required="" placeholder="Seleccionar fecha">
        </div>

        <div class="col-md-6 m-b-5">
            <label>Tipo streaming:</label>
            <select class="form-control sl2 streamingshow" name="cliente[tipo_streaming]" id="streamingtp" placeholde="Seleccionar servicio streaming" style="width: 100%">
                <option value="">Seleccionar tipo</option>
                <?= $streaming ?>
            </select>
        </div>

        <div class="col-md-6 m-b-5 showstrcuenta" style="display: none">
            <label>Cuenta streaming:</label>
            <select class="form-control sl2" name="cliente[servicio_streaming]" id="cuentastr" placeholde="Seleccionar servicio streaming" style="width: 100%">
            </select>
        </div>

        <div class="col-md-6 m-b-5 showstreaming" style="display: none">
            <label>PIN:</label>
            <input type="number" class="form-control" id="cliente[pin]" name="cliente[pin]" placeholder="1234">
        </div>


    </div>

    <div class="modal-footer">
        <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
        <button type="submit" class="btn btn-sm btn-success" data-loading-text="<i class='fas fa-spinner fa-spin'></i> registrando">Guardar</button>
    </div>

</form>

<style type="text/css">
    .select2-dropdown {
        z-index: 1960 !important;
    }
</style>
<script>
    var base_url = "<?= base_url(); ?>";
    var searchsocio = document.getElementById('searchsocio');
    $('.sl2').select2();

    var random = Math.floor(1000 + Math.random() * 9000);
    $('[name="cliente[pin]"]').val(random);

    $(function() {

        $('.streamingshow').change(function() {
            if ($(this).find('option:selected').data('tipo') == 2 & $(this).find('option:selected').data('pin') == 1) {
                $('.showstreaming').show();
                $('.showstrcuenta').show();
            } else if ($(this).find('option:selected').data('tipo') == 1 & $(this).find('option:selected').data('pin') == 0) {
                $('.showstrcuenta').show();
                $('.showstreaming').hide();
            } else if ($(this).find('option:selected').data('tipo') == 2 & $(this).find('option:selected').data('pin') == 0) {
                $('.showstrcuenta').show();
                $('.showstreaming').hide();
            }
        });

        $("#streamingtp").change(function() {
            $.ajax({
                data: {
                    id: $("#streamingtp").val()
                },
                url: 'streaming/get_data_select_c',
                type: 'post',
                success: function(data) {
                    if (data) {
                        $("#cuentastr").html('<option></option>');
                        $("#cuentastr").append(data);
                        $('input[name="descripcion').val('');
                    } else {
                        alerta('warning', 'No hay cuentas vinculas al tipo de servicio.');
                        $('select[name="accesorio"]').html('<option></option>');
                        $('input[name="descripcion').val('');
                    }

                }
            });
        });

        $('.datetk').datetimepicker({
            format: 'YYYY-MM-DD',
            locale: 'es',
            ignoreReadonly: true,
            showClose: true,
            stepping: 5,
        }).on('dp.change', function(e) {
            $('#cuenta[alta_cuenta]').val(moment(e.date).format("YYYY-MM-DD"));
        });

        $(document).on('keyup', '#searchsocio', function(e) {
            $("#searchsocio").autocomplete({
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
                    $("#searchsocio").autocomplete("destroy");
                    $("#searchsocio").removeData('autocomplete');
                    $('[name="cliente[id]"]').val(ui.item.id);
                }
            }).data("ui-autocomplete")._renderItem = function(ul, item) {
                return $("<li></li>")
                    .data("item.autocomplete", item)
                    .append(item.socio)
                    .appendTo(ul);
            };
        });

        $('#streaming-form').ajaxForm({
            success: function(data) {
                $('#gritter-notice-wrapper').remove();
                //loadurl("#history/get_json_history");
                updatecliente();
                alerta('exito', 'Cuenta registrada correctamente');
            },
            beforeSubmit: function() {
                if ($("#streamingtp").val() == '') {
                    alerta("warning", "Seleccione una opcion en Servicio streaming");
                    return false;
                }
                $('#modaltmp').modal('hide');
            },
            error: function(response) {
                $('#gritter-notice-wrapper').remove();
                alerta('error500', response['responseText']);
            }
        })
        $('#modaltmp').modal('show');
    })
</script>