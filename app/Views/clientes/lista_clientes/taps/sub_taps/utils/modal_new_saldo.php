<form id="form-new-saldo" method="post" action="clientes_facturacion/save_new_saldo" class="formin">
  <div class="modal-body" style="width: 100%;">
    <div style="margin: 0;padding: 0px 20px;">

      <input type="hidden" class="form-control" name="saldo[iduser]" value="<?= $idcliente; ?>">

      <small>Aquí puede agregar saldos a la cuenta del cliente ya sea un saldo a favor o descuento (Ejm: -100) o un saldo en contra (Ejm: 100)</small>

      <div class="form-group row">
        <label>Monto</label>
        <div class="input-group">
          <span class="input-group-addon">MX$</span>
          <input type="text" class="form-control" name="saldo[monto]" pattern="[-+]?[0-9]*[.]?[0-9]+" value="" required title="El monto debe ser superior a 0">
        </div>
      </div>


      <div class="form-group row">
        <label>Descripción</label>
        <textarea class="form-control" name="saldo[descripcion]" required></textarea>
      </div>

      <small>NOTA: Recuerde que el saldo agregado será aplicado en la próxima factura servicio que sea creado.</small>

    </div>
  </div>

  <div class="modal-footer">
    <button type="submit" class="btn-sm btn-azul btn-new-libre" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Registrando pago..">Registrar saldo</button>
  </div>

</form>


<script>
  $(function() {
    $('#modaltmp').modal('show');

    $('#form-new-saldo').ajaxForm({
      success: function(data) {
        if (data == 1) {
          $('.btn-new-libre').button("reset")
          $('#gritter-notice-wrapper').remove();
          alerta('exito', 'Saldo registrado correctamente');
          $('a[href="#nav-saldos"]').trigger('click');
          $('#modaltmp').modal('hide');
        } else {
          $('.btn-new-libre').button("reset")
          $('#gritter-notice-wrapper').remove();
          alerta('error', 'Error al guardar');
          $('a[href="#nav-saldos"]').trigger('click');
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