<form id="nota-form" method="post" action="clientes/save_nota" class="form-horizontal" enctype="multipart/form-data">
  <div class="modal-body" style="width: 100%">

    <div class="form-group row">
      <input type="hidden" name="idcliente" value="<?= $id; ?>">
      <label class="col-md-3 col-form-label text-right">Titulo Nota</label>
      <div class="col-md-9">
        <input type="text" class="form-control" name="nota_titulo" placeholder="Nota de instalacion" required autofocus>
      </div>
    </div>

    <div class="form-group row">
      <label class="col-md-3 col-form-label text-right">Archivo Adjunto</label>
      <div class="col-md-9">
        <input type="file" class="form-control" name="nota_adjunto">
      </div>
    </div>


    <div class="form-group row">
      <div class="col-md-12">
        <textarea class="form-control" name="nota_mensaje" placeholder="mensaje..." required></textarea>
      </div>
    </div>

  </div>

  <div class="modal-footer">
    <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
    <button type="submit" class="btn btn-sm btn-success">Registrar</button>
  </div>

</form>


<script>
  $(function() {

    $('textarea[name="nota-mensaje"]').wysihtml5({
      locale: "es-ES",
      toolbar: {
        "fa": true,
      }
    });

    $('#nota-form').ajaxForm({
      success: function(data) {
        $('#gritter-notice-wrapper').remove();
        if (data == 2) {

          alerta('error', "Error al agregar.");
        } else if (data == 1) {
          $('a[href="#nav-tab-9"]').trigger('click');
          alerta('exito',"Nota guardada con exito.");
          $('#modaltmp').modal('hide');
        } else {
          alerta('warning', "Error al leer el archivo adjunto");

        }
      },
      beforeSubmit: function() {
        $('#modaltmp').modal('hide');
      },
      error: function(response) {
        $('#gritter-notice-wrapper').remove();
        alerta('error500', "Error.");
      }
    })

    $('#modaltmp').modal('show');


  })
</script>