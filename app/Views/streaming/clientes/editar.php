<form id="streaming-form" method="post" action="streaming/save_edit_clientestr" class="form-horizontal" enctype="multipart/form-data">
    <div class="modal-body row" style="width: 100%">

        <div class="col-md-6 m-b-5">
            <label>Cliente:</label>
            <input type="text" class="form-control" id="searchsocio" name="cliente[cliente]" value="<?= $streaming->nombre ?>">
            <input type="hidden" class="form-control" name="cliente[id_cliente]" id="idcliente" value="<?= $streaming->id_cliente ?>">
            <input type="hidden" class="form-control" name="cliente[id_clientestr]" id="cliente[id_cs]" value="<?= $streaming->id ?>">
        </div>

        <div class="col-md-6 m-b-5">
            <label>Fecha Activado:</label>
            <input class="form-control datetk" id="cliente[fecha]" name="cliente[fecha]" value="<?= date('Y-m-d', strtotime($streaming->fecha_vinculado)) ?>" required="">
        </div>

        <div class="col-md-6 m-b-5">
            <label>Cuenta streaming vinculado:</label>
            <select class="form-control sl2" name="cliente[servicio_str]" id="cliente[servicio_str]" placeholde="Seleccionar servicio streaming" style="width: 100%">
                <?= $tipo_streaming ?>
            </select>
        </div>

        <div class="col-md-6 m-b-5">
            <label>Pin:</label>
            <input type="text" class="form-control" id="cliente[pin]" name="cliente[pin]" value="<?= $streaming->pin ?>">
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
    $('[name="cliente[servicio_str]"] option[value="<?= $streaming->id_cuentastr ?>"]').prop('selected', true);
    $('.sl2').select2();

    $(function() {

        $('.datetk').datetimepicker({
            format: 'YYYY-MM-DD',
            locale: 'es',
            ignoreReadonly: true,
            showClose: true,
            stepping: 5,
        }).on('dp.change', function(e) {
            $('#cliente[fecha]').val(moment(e.date).format("YYYY-MM-DD"));
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
                    $('[name="cliente[id_cliente]"]').val(ui.item.id);
                }
            }).data("ui-autocomplete")._renderItem = function(ul, item) {
                return $("<li></li>")
                    .data("item.autocomplete", item)
                    .append(item.socio)
                    .appendTo(ul);
            };
        })


        $('#streaming-form').ajaxForm({
            success: function(data) {
                $('#gritter-notice-wrapper').remove();
                //loadurl("#history/get_json_history");
                updatecliente();
                alerta('exito', 'Cuenta streaming actualizado correctamente');
            },
            beforeSubmit: function() {
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