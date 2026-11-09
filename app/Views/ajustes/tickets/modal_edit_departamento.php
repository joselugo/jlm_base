<form id="editdp-form" method="post" action="ajustes/save_edit_departamento">
  <div class="modal-body m-5">
    <input name="id" type="hidden" value="<?= $departamento->id; ?>">
    <div class="form-group row">
      <label class="col-form-label">Nombre Departamento</label>
      <input type="text" class="form-control" name="tk[dp]" required value="<?= $departamento->dp; ?>">
    </div>

    <div class="form-group row">
      <label class="col-form-label">Descripción</label>
      <textarea class="form-control" name="tk[dsp]" required style="min-height: 70px"><?= $departamento->dsp; ?></textarea>
    </div>

    <div class="form-group row">
      <label class="col-form-label">Asuntos predeterminados</label>
      <textarea class="form-control" onkeyup="textAreaAdjust(this)" onClick="textAreaAdjust(this)" name="tk[asuntos]" required style="min-height: 100px"><?= $departamento->asuntos; ?></textarea>
      <small class="text-danger">Un asunto por línea.</small>
    </div>

    <div class="form-group row">
      <label class="col-form-label">Correo</label>
      <input type="text" class="form-control" name="tk[maildp]" required value="<?= $departamento->maildp; ?>">
    </div>

    <div class="form-group row">
      <label class="col-form-label" style="width: 100%">Color Etiqueta</label>
      <input type="text" class="form-control" name="tk[color]" required style="max-width: 100px;" pattern="^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$" title="Indique un código de color correcto." value="<?= $departamento->color; ?>">
      <small style="width: 100%">Ejm: #00e6ac</small>
    </div>

    <div class="form-group row">
      <label class="col-form-label" style="width: 100%">Mostrar en portal cliente</label>
      <input type="hidden" name="tk[portal]">
      <input type="checkbox" class="form-control chk" name="tk[portal]" <?= $departamento->portal == "on" ? "checked" : ""; ?>>
    </div>

  </div>

  <div class="modal-footer">
    <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
    <button type="submit" class="btn btn-sm btn-success btn-newrouter">Guardar cambios</button>
  </div>

</form>


<script>
  function textAreaAdjust(o) {
    o.style.height = "1px";
    o.style.height = (5 + o.scrollHeight) + "px";
  }

  $(function() {
    var elems = Array.prototype.slice.call(document.querySelectorAll('.chk'));
    elems.forEach(function(html) {
      var switchery = new Switchery(html, {
        size: 'small',
        color: 'rgb(18, 187, 168)'
      });
    });
    $('#editdp-form').ajaxForm({
      success: function(data) {
        $('#gritter-notice-wrapper').remove();
        if (data == 1) {
          alerta('exito', 'Departamento Actualizado correctamente');
        } else if (data == 2) {
          alerta('warning', 'Error al actualizar.');
        } else {
          alerta('warning', 'No tienes los permisos necesarios');
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