<form id="editzona-form" method="post" action="ajustes/save_edit_zona">

  <div class="modal-body" style="width: 100%">

    <div class="form-group row">
      <label class="col-form-label">Nombre Ubicación</label>
      <input type="hidden" name="id" value="<?= $zona->id; ?>">
      <input type="text" class="form-control" name="zona" required value="<?= $zona->zona; ?>">
    </div>

  </div>

  <div class="modal-footer">
    <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
    <button type="submit" class="btn btn-sm btn-success btn-newrouter">Guardar cambios</button>
  </div>

</form>


<script>
  $(function() {

    $('#editzona-form').ajaxForm({
      success: function(data) {
        $('#gritter-notice-wrapper').remove();
        if (data == 1) {
          alerta('exito', 'Ubicación guardado correctamente');
        } else if (data == 2) {
          alerta('warning', 'Error al editar');
        } else {
          alerta('warning', 'Error.');
        }
        $('#modaltmp').modal('hide');
        updatezona();
      },
      error: function(response) {
        $('#gritter-notice-wrapper').remove();
        alerta('error500', "Error");
      }
    })

    $('#modaltmp').modal('show');


  })
</script>