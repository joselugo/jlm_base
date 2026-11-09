<form id="router-form" method="post" action="game/save_respuestas" class="form-horizontal">
  <input type="hidden" value="<?= $id; ?>" name="id">
  <div class="modal-body">
    <div class="container-fluid" id="formulario_respuestas">
      <?php if (isset($respuestas)) : ?>
        <?php foreach ($respuestas as $key => $dato) : ?>
          <div class="row">
            <div class="col-9">
              <div class="form-group row">
                <label class="col-md-2 control-label"><?= $key + 1; ?>.Respuesta </label>
                <div class="col-md-10">
                  <input type="text" class="form-control" value="<?= $dato->respuesta; ?>" name="respuesta[]" placeholder="<?= $dato->respuesta; ?>" required autofocus>
                </div>
              </div>
            </div>
            <div class="col-3">
              <div class="form-group row">
                <label class="col-md-3 control-label">Puntos </label>
                <div class="col-md-9">
                  <input type="number" class="form-control" value="<?= $dato->puntos; ?>" name="puntos[]" placeholder="<?= $dato->puntos; ?>" required autofocus>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else : ?>
        <div class="row">
          <div class="col-9">
            <div class="form-group row">
              <label class="col-md-2 control-label">1.Respuesta </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name="respuesta[]" placeholder="Juan" required autofocus>
              </div>
            </div>
          </div>
          <div class="col-3">
            <div class="form-group row">
              <label class="col-md-3 control-label">Puntos </label>
              <div class="col-md-9">
                <input type="number" class="form-control" name="puntos[]" placeholder="31" required autofocus>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
    <div class="col-12 " style="text-align-last: end;"><a style="font-size:10px;padding-right: 10px;padding-left: 10px;padding-top: 5px;padding-bottom: 5px;" class="btn btn-success" onclick="agregar_fila()"><i class="fas fa-plus"></i> Agregar fila</a></div>



  </div>

  <div class="modal-footer">
    <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
    <button id="btn_new_server" type="submit" class="btn btn-sm btn-success btn-newrouter" data-loading-text="<i class='fas fa-spinner fa-spin'></i> registrando">Registrar</button>
  </div>

</form>


<script>
  var tbl = document.getElementById("formulario_respuestas");

  function agregar_fila() {
    hijos = tbl.childNodes.length;
    console.log(hijos);
    if (hijos == 3) {
      num = 2;
    } else {
      num = (hijos / 2) + .5;
    }
    items = `
      <div class="row">
          <div class="col-9">
            <div class="form-group row">
              <label class="col-md-2 control-label">${num}.Respuesta </label>
              <div class="col-md-10">
                <input type="text" class="form-control" name="respuesta[]" placeholder="Pedro" required autofocus>
              </div>
            </div>
          </div>
          <div class="col-3">
            <div class="form-group row">
              <label class="col-md-3 control-label">Puntos </label>
              <div class="col-md-9">
                <input type="number" class="form-control" name="puntos[]" placeholder="${num+10}" required autofocus>
              </div>
            </div>
          </div>
        </div>`
    $('#formulario_respuestas').append(items);
  }
  $(function() {

    $('#router-form').ajaxForm({
      beforeSend: function() {
        var puntos = document.getElementsByName("puntos[]");
        total = 0;
        puntos.forEach(element => {
          total += parseInt(element.value);
        });
        console.log(total);
        if (total != 100) {
          alerta("warning", "La suma total de los puntos no es 100.");
          return false;
        }
      },
      success: function(data) {
        $('#gritter-notice-wrapper').remove();
        if (data == 1) {
          $('#modaltmp').modal('hide');
          alerta('exito', 'Agregado con exito.');
          updatepreguntas();
        } else if (data == 2) {
          alerta('error', 'Error no se pudo actualizar los datos.');
        } else {
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