<form id="streaming-form" method="post" action="streaming/save_edit_streaming" class="form-horizontal" enctype="multipart/form-data">
    <div class="modal-body row" style="width: 100%">

        <div class="col-md-6 m-b-5">
            <label>Nombre streaming:</label>
            <input type="text" class="form-control" id="streaming[name]" name="streaming[name]" value="<?= $streaming->nombre ?>" required="">
            <input type="hidden" name="streaming[id]" value="<?= $streaming->id ?>">
        </div>

        <div class="col-md-6 m-b-5">
            <label>Costo streaming:</label>
            <input type="number" class="form-control" id="streaming[costo]" name="streaming[costo]" value="<?= $streaming->costo ?>" required="">
        </div>

        <div class="col-sm-6 m-b-5">
            <label>Tipo streaming:</label>
            <select class="form-control sl2" name="streaming[tipo]" placeholde="Seleccionar" style="width: 100%">
                <option value="1">Cuenta personal</option>
                <option value="2">Cuenta compartida</option>
            </select>
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
    $('[name="streaming[tipo]"] option[value="<?= $streaming->tipo ?>"]').prop('selected', true);
    $('.sl2').select2();
    $(function() {
        $('#streaming-form').ajaxForm({
            success: function(data) {
                $('#gritter-notice-wrapper').remove();
                //loadurl("#history/get_json_history");
                updateservicios();
                alerta('exito', 'Streaming actualizado correctamente');
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