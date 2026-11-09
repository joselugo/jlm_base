<form id="streaming-form" method="post" action="streaming/save_balance" class="form-horizontal" enctype="multipart/form-data">
    <div class="modal-body row" style="width: 100%">

        <div class="col-md-6 m-b-5">
            <label>Presupuesto autorizado:</label>
            <input type="number" class="form-control" id="streaming[costo]" name="streaming[costo]" placeholder="250" required="">
            <input type="hidden" id="action" name="action" value="balance_youtube">
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
    $('.sl2').select2();
    $(function() {
        $('#streaming-form').ajaxForm({
            success: function(data) {
                $('#gritter-notice-wrapper').remove();
                updatepresupuesto();
                alerta('exito', 'Presupuesto agregado correctamente');
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