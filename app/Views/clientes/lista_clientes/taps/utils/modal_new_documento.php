<form id="fil-form-new" method="post" action="clientes/save_documento_cliente" class="form-horizontal" enctype="multipart/form-data">
  <div class="modal-body" style="width: 100%">

    <div class="form-group row">
      <label class="col-md-3 col-form-label text-right">Archivo <small>(.PDF)</small></label>
      <div class="col-md-9">
        <input type="hidden" name="idcliente" value="<?= $id; ?>">
        <input type="file" class="form-control" name="nota_file" placeholder="Seleccionar documento" accept="application/pdf" required>
      </div>
    </div>

    <div class="form-group row">
      <label class="col-md-3 col-form-label text-right">Título</label>
      <div class="col-md-9">
        <input type="text" class="form-control" name="nota_asunto" placeholder="Contrato Internet" required>
      </div>
    </div>


<!--
    <div class="form-group row">
      <label class="col-md-3 col-form-label text-right">Descripción</label>
      <div class="col-md-9">
        <textarea class="form-control" name="nota_mensaje" placeholder="Documento cliente" required></textarea>
      </div>
    </div> -->


<!--
    <div class="form-group row">
      <label class="col-md-3 col-form-label text-right">Mostrar al cliente</label>
      <div class="col-md-9">
        <input type="hidden" name="portal">
        <input type="checkbox" class="form-control chk" name="portal">
      </div>
    </div> -->

  </div>

  <div class="modal-footer">
    <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
    <button type="submit" class="btn btn-sm btn-success btn-loader">Guardar</button>
  </div>

</form>


<script>
  $(function() {



    $('#fil-form-new').ajaxForm({
      success: function(data) {
        $('#gritter-notice-wrapper').remove();
        if (data == 2) {

          alerta('error', "Error al agregar.");
        }
        else if(data == 1) {
          $('a[href="#nav-tab-9"]').trigger('click');
          alerta('exito', "Documento guardado con exito.");
          $('#modaltmp').modal('hide');
        } else {
          alerta('warning', "Error al leer el archivo adjunto");

        }
      },
      beforeSend: function() {
        alerta('loader', 'Adjuntando archivo');
      },
      error: function(response) {
        $('#gritter-notice-wrapper').remove();
        alerta('error500', "Error.");
      }
    })

    $('#modaltmp').modal('show');

  })
</script>