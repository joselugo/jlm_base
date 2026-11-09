<form id="streaming-form" method="post" action="streaming/save_edit_cuenta" class="form-horizontal" enctype="multipart/form-data">
    <div class="modal-body row" style="width: 100%">

        <div class="col-md-6 m-b-5">
            <label>Correo:</label>
            <input type="email" class="form-control" id="cuenta[correo]" name="cuenta[correo]" value="<?= $streaming->correo ?>" required="">
        </div>

        <div class="col-md-6 m-b-5">
            <label>Contraseña:</label>
            <input type="text" class="form-control" id="cuenta[contrasena]" name="cuenta[contrasena]" value="<?= $streaming->contrasena ?>" required="">
        </div>

        <div class="col-md-6 m-b-5">
            <label>Fecha activado:</label>
            <input class="form-control datetk" id="cuenta[fecha]" name="cuenta[fecha]" value="<?= date('Y-m-d', strtotime($streaming->fecha_alta_servicio)) ?>" required="">
        </div>

        <div class="col-md-6 m-b-5">
            <label>Fecha vencimiento:</label>
            <input class="form-control datetk" id="cuenta[fecha_vencimiento]" name="cuenta[fecha_vencimiento]" value="<?= date('Y-m-d', strtotime($streaming->fecha_vencimiento)) ?>" required="">
        </div>

        <div class="col-md-6 m-b-5">
            <label>Nombre streaming:</label>
            <select class="form-control sl2" name="cuenta[servicio_str]" id="cuenta[servicio_str]" placeholde="Seleccionar servicio streaming" style="width: 100%">
                <?= $tipo_streaming ?>
            </select>
            <input type="hidden" name="cuenta[id]" value="<?= $streaming->id ?>">
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
    $('[name="cuenta[servicio_str]"] option[value="<?= $streaming->id_streaming ?>"]').prop('selected', true);
    $('.sl2').select2();

    $(function() {

        $('.datetk').datetimepicker({
            format: 'YYYY-MM-DD',
            locale: 'es',
            ignoreReadonly: true,
            showClose: true,
            stepping: 5,
        }).on('dp.change', function(e) {
            $('#cuenta[alta_cuenta]').val(moment(e.date).format("YYYY-MM-DD"));
        });


        $('#streaming-form').ajaxForm({
            success: function(data) {

                if (data == 2) {
                    alerta("warning", "Verificar presupuesto, tiene un saldo de $0.00 MNX o el saldo es menor al costo de la cuenta");
                    return false;
                } else {
                    $('#gritter-notice-wrapper').remove();
                    updatecuentas();
                    updatepresupuesto();
                    alerta('exito', 'Cuenta streaming actualizado correctamente');
                }

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