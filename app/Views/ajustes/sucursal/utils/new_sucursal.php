<form id="form-new-sucursal" method="post" action="ajustes/save_new_sucursal" class="formin">
  <div class="modal-body" style="width: 100%;">
    <div style="margin: 0;padding: 0px 20px;">
      <div class="form-group row">
        <label>Nombre de la sucursal:</label>
        <div class="input-group">
          <input type="text" class="form-control" name="sucursal[nombre]" placeholder="Netium Telecom" required title="Debe escribir un nombre">
        </div>
        <label>Alias:</label>
        <div class="input-group">
          <input type="text" class="form-control" name="sucursal[alias]" placeholder="Piedra de agua" required title="Debe un alias">
        </div>
        <label>Nodos:</label>
        <div class="input-group">
          <select name="nodos[]" class="slopnodos" multiple="" style="width: 100%" required="">
            <?= $nodos;?>
          </select>
        </div>
      </div>
    </div>
  </div>

  <div class="modal-footer">
    <button type="submit" class="btn-sm btn-azul btn-new-libre" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Registrando..">Registrar</button>
  </div>

</form>


<script>
  $('.slopnodos').select2();
  $(function() {

    $('#modaltmp').modal('show');

    $('#form-new-sucursal').ajaxForm({
      success: function(data) {
        console.log(data);
        if (data == 1) {
          $('.btn-new-libre').button("reset")
          $('#gritter-notice-wrapper').remove();
          alerta('exito', 'Sucursal registrado correctamente');
          updatesucursal();
          $('#modaltmp').modal('hide');
        } else {
          $('.btn-new-libre').button("reset")
          $('#gritter-notice-wrapper').remove();
          alerta('error', 'Error al guardar');
          updatesucursal();
          $('#modaltmp').modal('hide');
        }
      },
      error: function(response) {
        $('#gritter-notice-wrapper').remove();
        alerta('error500', response['responseText']);
        $('.btn-new-libre').button("reset")
      }
    })

    $(".Select2").select2({
      placeholder: "Seleccionar..."
    });
    autosize($('textarea'));
  })
</script>