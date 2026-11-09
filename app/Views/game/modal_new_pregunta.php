<form id="router-form" method="post" action="game/save_new_pregunta<?= isset($tipo)?"_rapida":""?>" class="form-horizontal">
  <div class="modal-body">

    <div class="form-group row">
      <label class="col-md-3 control-label">Pregunta</label>
      <div class="col-md-9">
        <input type="text" class="form-control" name="pregunta" placeholder="Nombre del router" required autofocus>
      </div>
    </div>

  </div>

  <div class="modal-footer">
    <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
    <button id="btn_new_server"  type="submit" class="btn btn-sm btn-success btn-newrouter" data-loading-text="<i class='fas fa-spinner fa-spin'></i> registrando">Registrar</button>
  </div>

</form>


<script>
  $(function() {

    $('#router-form').ajaxForm({
      success: function(data) {
        $('#gritter-notice-wrapper').remove();
        if (data == 1) {
          $('#modaltmp').modal('hide');
          alerta('exito', 'Agregado con exito.');
          updatepreguntas();
        } else if (data == 2) {
          alerta('error', 'Error no se pudo actualizar los datos.');
        }  else {
          alerta('warning', 'No tienes permiso para realizar esta accion.');
        }



      },
      beforeSubmit: function() {

          alerta('loader', '');

      },
      error: function(response) {
        $('#gritter-notice-wrapper').remove();
        alerta('error', "error");
      }
    })

    $('#modaltmp').modal('show');


  })
</script>