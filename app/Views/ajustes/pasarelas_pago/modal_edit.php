<form id="pasarela-form" method="post" action="ajustes/save_edit_pasarela" class="form-horizontal">
  <div class="modal-body" style="width: 100%">

    <div class="form-group row">
      <label class="col-md-3 control-label">Nombre</label>
      <div class="col-md-9">
        <input type="hidden" name="pasarela[id]" required value="<?= $pasarela->id; ?>">
        <input type="text" class="form-control" name="pasarela[pasarela]" required value="<?= $pasarela->pasarela; ?>">
      </div>
    </div>

    <div class="form-group row">
      <label class="col-md-3 control-label">Descripción 1</label>
      <div class="col-md-9">
        <input type="text" class="form-control" name="pasarela[dsp1]" value="<?= $pasarela->dsp1; ?>">
      </div>
    </div>

    <div class="form-group row">
      <label class="col-md-3 control-label">Descripción 2</label>
      <div class="col-md-9">
        <input type="text" class="form-control" name="pasarela[dsp2]" value="<?= $pasarela->dsp2; ?>">
      </div>
    </div>

    <div class="form-group row">
      <label class="col-md-3 control-label">Descripción 3</label>
      <div class="col-md-9">
        <input type="text" class="form-control" name="pasarela[dsp3]" value="<?= $pasarela->dsp3; ?>">
      </div>
    </div>

    <div class="form-group row">
      <label class="col-md-3 control-label">Descripción 4</label>
      <div class="col-md-9">
        <input type="text" class="form-control" name="pasarela[dsp4]" value="<?= $pasarela->dsp4; ?>">
      </div>
    </div>


  </div>

  <div class="modal-footer">
    <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
    <button type="submit" class="btn btn-sm btn-success btn-newrouter">Guardar cambios</button>
  </div>

</form>


<script>
  $(function() {

    $('#pasarela-form').ajaxForm({
      success: function(data) {
        $('#gritter-notice-wrapper').remove();
        if (data == 1) {
          alerta('exito', 'forma de pago registrado correctamente');
        } else if (data == 2) {
          alerta('warning', 'Error al actualizar.');
        } else {
          alerta('warning', "No cuentas con los permisos necesarios.")
        }
        updatepasarela();
        $('#modaltmp').modal('hide');
      },
      error: function(response) {
        $('#gritter-notice-wrapper').remove();
        alerta('error500', "Error");
      }
    })

    $('#modaltmp').modal('show');


  })
</script>