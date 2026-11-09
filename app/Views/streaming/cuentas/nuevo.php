<form id="streaming-form" method="post" action="streaming/save_cuenta" class="form-horizontal" enctype="multipart/form-data">
    <div class="modal-body row" style="width: 100%">

        <div class="col-md-6 m-b-5">
            <label>Activacion cuenta straming:</label>
            <input class="form-control datetk" name="cuenta[alta_cuenta]" id="cuenta[alta_cuenta]" required="" placeholder="Seleccionar fecha">
        </div>
        <div class="col-md-6 m-b-5">
            <label>Vencimiento cuenta straming:</label>
            <input class="form-control datetk" name="cuenta[fecha_vencimiento]" id="cuenta[fecha_vencimiento]" required="" placeholder="Seleccionar fecha">
        </div>

        <div class="col-md-6 m-b-5">
            <label>Servicio streaming:</label>
            <select class="form-control sl2 streamingshow" name="cuenta[servicio_str]" id="streamingtp" placeholde="Seleccionar servicio streaming" style="width: 100%">
                <option value="">Seleccionar tipo</option>
                <?= $streaming ?>
            </select>
        </div>

        <div class="col-md-6 m-b-5 showstreaming" style="display: none">
            <label>Total cuentas:</label>
            <input type="number" class="form-control" id="cuenta[total_cuenta]" name="cuenta[total_cuenta]" placeholder="4">
        </div>
        <div class="col-md-6 m-b-5">
            <label>Correo:</label>
            <input type="email" class="form-control" id="cuenta[correo]" name="cuenta[correo]" placeholder="ejemplo@gmail.com">
        </div>

        <div class="col-md-6 m-b-5">
            <label>Contraseña:</label>
            <input type="text" class="form-control" id="cuenta[contrasena]" name="cuenta[contrasena]" placeholder="Netium123">
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

    $(function() {

        $('.streamingshow').change(function() {
            if ($(this).val() > 0) {
                $('.showstreaming').show();
            } else {
                $('.showstreaming').hide();
            }
        })

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
                    //loadurl("#history/get_json_history");
                    updatecuentas();
                    updatepresupuesto();
                    alerta('exito', 'Cuenta registrada correctamente');
                }
            },
            beforeSubmit: function(data) {
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