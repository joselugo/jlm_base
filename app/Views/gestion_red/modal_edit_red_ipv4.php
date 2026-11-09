<form id="ipv4-form-edit" method="post" action="gestion_red/save_edit_red_ipv4" class="form-horizontal">
  <div class="modal-body ">

    <div class="form-group row">
      <label class="col-md-3 control-label">Nombre</label>
      <div class="col-md-9">
        <input type="text" class="form-control" name="nombre" placeholder="nombre de red" required autofocus value="<?= $red->nombre; ?>">
        <input type="hidden" name="nombre2" value="<?= $red->nombre; ?>">
        <input type="hidden" name="id" value="<?= $red->id; ?>">
        <input type="hidden" name="red" value="<?= $red->red; ?>">
        <input type="hidden" name="cidr" value="<?= $red->cidr; ?>">
        <input type="hidden" name="nodo2" value="<?= $red->nodo; ?>">
      </div>
    </div>

    <div class="form-group row">
      <label class="col-md-3 control-label">Router</label>
      <div class="col-md-9">
        <select style="width: 100%;" class="form-control Select2" name="nodo">
          <?= $servers; ?>
        </select>
      </div>
    </div>


    <div class="form-group row">
      <label class="col-md-3 control-label">Red</label>
      <div class="col-md-9">
        <div class="input-group">
          <div class="input-group-addon"><i class="fas fa-sitemap"></i></div>
          <div class="no form-control" style="background: #f5f5f5;"><?= $red->red; ?></div>
        </div>

      </div>
    </div>


    <div class="form-group row">
      <label class="col-md-3 control-label">CIDR</label>
      <div class="col-md-9">
        <div class="form-control readonly" style="width: 100%;background: #f5f5f5;">
          / <?= $red->cidr; ?></div>

      </div>
    </div>

    <div class="form-group row">
      <input type="hidden" name="tipo2" value="<?= $red->tipo; ?>">
      <label class="col-md-3 control-label">Tipo de Uso</label>
      <div class="col-md-9">
        <select style="width: 100%;" class="form-control Select2" name="tipo">
          <option value="0">Estático</option>
          <option value="1">Pool</option>
        </select>
      </div>
    </div>


  </div>

  <div class="modal-footer">
    <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
    <button type="submit" style="display:<?= $permiso>=2?"block":"none";?>" class="btn btn-sm btn-azul" data-loading-text="<i class='fas fa-spinner fa-spin'></i> registrando">Guardar cambios</button>
  </div>

</form>


<script>
  $(function() {
    $('[name="nodo"] option[value="<?= $red->nodo; ?>"]').prop("selected", true);
    $('[name="tipo"] option[value="<?= $red->tipo; ?>"]').prop("selected", true);
    $('[name="tipo"]').change(function() {
      if ($('[name="tipo2"]').val() == "0" && $(this).val() == "1") {
        alerta('warning', ' Al cambiar a Pool,Todos los usuarios que esten utilizando esta RED pasarán a utilizar IP dinámica y su<b> IP fija Será Borrado de sus datos.</b>');
      }

      if ($('[name="tipo2"]').val() == "1" && $(this).val() == "0") {
        alerta('warning', ' Al cambiar a IP estáticas,Necesitará agregar manualmente la IP de cada usuario manualmente en su datos.</b>');
      }

    });

    $('[name="nodo"]').change(function() {
      $('.btn-newipv4').prop('disabled', false);
      $('[name="tipo"]')[0].options.length = 0;
      var security = $(this).find(':selected').data('seguridad');


      if (security > 2) {
        $('[name="tipo"]').append('<option value="0">Estático</option><option value="1">Pool</option>');
        $('[name="tipo"] option[value="0"]').prop('selected', true);
      } else {
        $('[name="tipo"]').append('<option value="0">Estático</option>');
        $('[name="tipo"] option[value="0"]').prop('selected', true);
      }
    });

    $('[name="nodo"]').trigger("change");

    $(".Select2").select2();

    $('#ipv4-form-edit').ajaxForm({
      success: function(data) {
        $('#gritter-notice-wrapper').remove();
        if (data == 2) {
          alerta('error', "Error al actualizar.");
        } else {
          updateipv4();
          alerta('exito', "Exito al actualizar.");
        }
      },
      beforeSubmit: function() {
        $('#modaltmp').modal('hide');
        alerta('loader', '');
      },
      error: function(response) {
        $('#gritter-notice-wrapper').remove();
        alerta('error500', "Error");
      }
    })

    $('#modaltmp').modal('show');


  })
</script>