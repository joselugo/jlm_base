<form id="editar" method="post" action="finanzas/viewuser?action=saveedittransaccion" class="formin">
  <div class="modal-body">
    <div>

      <div class="form-group">
        <h5>Pago de la factura Nº <?= $data->id; ?></h5>
        <label>Forma de pago</label>
        <input type="hidden" class="form-control" name="operacion[id]" value="<?= $data->operacionesid; ?>">
        <input type="hidden" class="form-control" name="operacion[idcliente]" value="<?= $data->idcliente; ?>">
        <input type="hidden" class="form-control" name="nfactura" value="<?= $data->id; ?>">
        <select id="formadepago" name="operacion[forma_pago]" class="form-control Select2" style="width: 100%" required>
          <option></option>
          <?= $options_pasarelas ?>
          <option value="PagueloFacil">PagueloFacil</option>
          <option value="Mercadopago">Mercadopago</option>
          <option value="bbva">Bbva</option>
          <option value="Kushki">Kushki</option>
          <option value="Pagofacil">Pagofácil</option>
          <option value="Oxxo Pay">Oxxo Pay</option>
          <option value="Webpay">Webpay</option>
          <option value="Epayco">Epayco</option>
          <option value="culqui">Culqui</option>
          <option value="khipu">khipu</option>
          <option value="Cobro digital">Cobro Digital</option>
          <option value="Cuenta Digital">Cuenta Digital</option>
          <option value="Flow">Flow</option>
          <option value="PayPal/Visa/Mastercard">PayPal/Visa/Mastercard</option>
        </select>

      </div>


      <div class="form-group">
        <label>Detalle forma pago</label>
        <select class="form-control Select2" id="descripcion_pago" name="operacion[descripcion_pago]" style="width: 100%">
        </select>
      </div>
      <div class="form-group">
        <label>Fecha & Hora</label>
        <input type="text" class="form-control datetime" name="operacion[fecha_pago]" value="<?= $data->fecha_pago; ?>">
      </div>

      <div class="form-group">
        <label>Nº transacción</label>
        <input type="text" class="form-control" name="operacion[transaccion]" value="<?= $data->transaccion; ?>">
      </div>


      <div class="form-group">
        <label>Monto</label>
        <div class="input-group">
          <span class="input-group-addon">MX$</span>
          <input type="text" class="form-control" name="operacion[cobrado]" value="<?= $data->cobrado; ?>" required title="El monto debe ser superior a 0" disabled>
        </div>
      </div>


      <div class="form-group">
        <label>Comisión</label>
        <div class="input-group">
          <span class="input-group-addon">MX$</span>
          <input type="text" class="form-control" name="operacion[comision]" pattern="[0-9]*[.]?[0-9]+" value="<?= $data->comision; ?>">
        </div>
      </div>


      <div class="form-group">
        <label>Notas</label>
        <textarea class="form-control" name="operacion[descripcion]" rows="6" style="min-height: 50px;"><?= $data->descripcion; ?></textarea>
      </div>


    </div>
  </div>

  <div class="modal-footer">
    <button style="display:<?= $permiso >= 2 ? "block" : "none"; ?>" type="submit" class="btn-sm btn-azul btn-new-libre">Guardar cambios</button>
  </div>

</form>


<script>
  var form = document.getElementById('editar')
  form.addEventListener('submit', function(e) {
    e.preventDefault()
    $.ajax({
      type: "POST",
      url: "finanzas/viewuser?action=saveedittransaccion",
      data: $('#editar').serialize(),
      success: function(response) {
        if (response == 1) {
          if (typeof updatefacturas === 'function') {
            $('#gritter-notice-wrapper').remove();
            updatefacturas();
            alerta('exito', 'Los cambios fueron guardados correctamente');
            $('#modaltmp').modal('hide').html('');
          }
          if (typeof updateoperaciones === 'function') {
            $('#gritter-notice-wrapper').remove();
            $('a[href="#default-tab-2"]').trigger('click');
            alerta('exito', 'Los cambios fueron guardados correctamente');
            $('#modaltmp').modal('hide').html('');
          }
          if (typeof clientes_factura === 'function') {
            $('#gritter-notice-wrapper').remove();
            $('a[href="#nav-facturas"]').trigger("click");
            alerta('exito', 'Los cambios fueron guardados correctamente');
            $('#modaltmp').modal('hide').html('');
          }
          if (typeof clientes_trasacciones === 'function') {
            $('#gritter-notice-wrapper').remove();
            $('a[href="#nav-transacciones"]').trigger("click");
            alerta('exito', 'Los cambios fueron guardados correctamente');
            $('#modaltmp').modal('hide').html('');
          }
        } else if (response == "<p>ACCESO DENEGADO.</p>") {
          alerta('warning', 'No tienes los permisos necesarios.');
        }else{
          alerta('error', 'Error al realizar la accion, vuelva a intentarlo.');

        }

      }
    });


  })

  function validOp() {
    //--->VALIDA N Operacion
    ope = ajax({
      async: false,
      type: "POST",
      url: "finanzas/viewuser",
      data: {
        cobrado: $('input[name="operacion\[cobrado\]"]').val(),
        action: "validaroperacion",
        n: $('input[name="operacion\[transaccion\]"]').val(),
        id: "<?= $data->id; ?>"
      },
      dataType: "JSON"
    }).responseJSON;
    console.log(ope);
    return ope
  }

  $(function() {

    $('#formadepago').change(function() {
      $('#descripcion_pago').html('');

      if ($(this).find(':selected').data('dsp1')) {
        $('#descripcion_pago').append($("<option></option>").attr("value", $(this).find(':selected').data('dsp1')).text($(this).find(':selected').data('dsp1')));
      }

      if ($(this).find(':selected').data('dsp2')) {
        $('#descripcion_pago').append($("<option></option>").attr("value", $(this).find(':selected').data('dsp2')).text($(this).find(':selected').data('dsp2')));
      }

      if ($(this).find(':selected').data('dsp3')) {
        $('#descripcion_pago').append($("<option></option>").attr("value", $(this).find(':selected').data('dsp3')).text($(this).find(':selected').data('dsp3')));
      }

      if ($(this).find(':selected').data('dsp4')) {
        $('#descripcion_pago').append($("<option></option>").attr("value", $(this).find(':selected').data('dsp4')).text($(this).find(':selected').data('dsp4')));
      }


      $('#descripcion_pago option[value="Efectivo"]').prop('selected', true);
      //$('#formadepago option[value="Efectivo Oficina/Sucursal"]').prop('selected', true);

    });

    $('.datetime').datetimepicker({
      format: 'DD/MM/YYYY hh:mm A'
    });

    $('#modaltmp').modal('show');

    $('#formadepago option[value="<?= $data->forma_pago; ?>"]').prop('selected', true);

    $('#formadepago').trigger('change');

    $(".Select2").select2({
      placeholder: "Seleccionar..."
    });
    autosize($('textarea'));




  })
</script>