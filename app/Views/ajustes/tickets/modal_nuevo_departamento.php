<form id="newdp-form" method="post" action="ajustes/save_nuevo_departamento">
  <div class="modal-body m-5">

    <div class="form-group row">
      <label class="col-form-label">Nombre Departamento</label>
      <input type="text" class="form-control" name="tk[dp]" required>
    </div>

    <div class="form-group row">
      <label class="col-form-label">Descripción</label>
      <textarea class="form-control" name="tk[dsp]" required style="min-height: 100px"></textarea>
    </div>

    <div class="form-group row">
      <label class="col-form-label">Correo</label>
      <input type="text" class="form-control" name="tk[maildp]" required>
    </div>

    <div class="form-group row">
      <label class="col-form-label" style="width: 100%">Color Etiqueta</label>
      <input type="text" class="form-control" name="tk[color]" required style="max-width: 100px;" pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$" title="Indique un código de color correcto." value="#0099ff">
      <small style="width: 100%">Ejm: #00e6ac</small>
    </div>

    <div class="form-group row">
      <label class="col-form-label" style="width: 100%">Mostrar en portal cliente</label>
      <input type="hidden" name="tk[portal]">
      <input type="checkbox" class="form-control chk" name="tk[portal]">
    </div>

  </div>

  <div class="modal-footer">
    <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
    <button type="submit" class="btn btn-sm btn-success btn-newrouter">Registrar</button>
  </div>

</form>


<script>
  $(function() {

    var elems = Array.prototype.slice.call(document.querySelectorAll('.chk'));
    elems.forEach(function(html) {
      var switchery = new Switchery(html, {
        size: 'small',
        color: 'rgb(18, 187, 168)'
      });
    });

    $('#newdp-form').ajaxForm({
      success: function(data) {
        $('#gritter-notice-wrapper').remove();
        if (data == 1) {
          alerta('exito', 'Departamento registrado correctamente');
        } else if (data == 2) {
          alerta('warning', 'Error al agregar.');
        }else{
          alerta('warning', 'No cuentas con los permisos necesarios');
        }
        $('#modaltmp').modal('hide');
        updatedp();
      },
      error: function(response) {
        $('#gritter-notice-wrapper').remove();
        alerta('error500', response['responseText']);
      }
    })

    $('#modaltmp').modal('show');


  })
</script>