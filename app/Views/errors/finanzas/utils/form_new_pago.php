<form id="form-paid-invoice" method="post" action="finanzas/viewuser?action=addtransaccion" class="formin">
  <div class="modal-body">
    <div>

      <div class="form-group">
        <label>Forma de pago</label>
        <input type="hidden" class="form-control" name="operacion[idcliente]" value="<?= $datos->idcliente; ?>">
        <input type="hidden" class="form-control" name="operacion[nfactura]" value="<?= $datos->nfactura; ?>">
        <input type="hidden" class="form-control" name="operacion[meses]" value="1">
        <input type="hidden" class="form-control" name="operacion[saldo]" value="0">
        <select name="operacion[forma_pagoss]" id="formadepagoss" class="form-control Select2" style="width: 100%" required>
          <option></option>
          <?= $pasarelas; ?>
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
        <select class="form-control Select2" id="descripcion_pagos" name="operacion[descripcion_pagos]" style="width: 100%" data-placeholder="Ninguno"></select>
      </div>

      <div class="form-group">
        <label>Nº transacción</label>
        <input type="text" class="form-control" name="operacion[transaccion]" value="">
      </div>


      <div class="form-group">
        <label>Nº Fiscal</label>
        <input type="text" class="form-control" name="factura[legal]" value="<?= $datos->legal; ?>">
      </div>



      <div class="form-group">
        <label>Monto</label>
        <div class="input-group">
          <span class="input-group-addon">MX$</span>
          <input type="hidden" id="totalpagar" value="<?= $datos->monto; ?>">
          <input type="hidden" name="validlegal" value="">
          <input type="text" class="form-control" name="factura[cobrado]" pattern="^(0*[1-9][0-9]*(\.[0-9]+)?|0+\.[0-9]*[1-9][0-9]*)$" value="<?= $datos->monto; ?>" required title="El monto debe ser superior a 0">
        </div>
      </div>


      <div class="form-group">
        <label>Comisión</label>
        <div class="input-group">
          <span class="input-group-addon">MX$</span>
          <input type="text" class="form-control" name="operacion[comision]" pattern="[0-9]*[.]?[0-9]+" value="0">
        </div>
      </div>


      <div class="form-group">
        <label>Notas</label>
        <textarea class="form-control" name="operacion[descripcion]" rows="6" style="min-height: 50px;"></textarea>
      </div>


    </div>
  </div>

  <div class="modal-footer">
    <button type="submit" class="btn-sm btn-azul btn-new-libre" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Registrando pago..">Registrar pago</button>
  </div>

</form>


<script>
  function validOp() {
    //--->VALIDA N Operacion	
    return $.ajax({
      async: false,
      type: "POST",
      url: "finanzas/viewuser",
      data: {
        cobrado: $('input[name="factura\[cobrado\]"]').val(),
        action: "validaroperacion",
        n: $('input[name="operacion\[transaccion\]"]').val(),
        id: ""
      },
      dataType: "JSON"
    }).responseJSON;


  }


  $(function() {

    $('#modaltmp').modal('show');

    $('select[name="operacion\[forma_pagos\]"] option[value=""]').prop('selected', true);

    $('#form-paid-invoice').on('submit', function(e) {

      /*  validaop = validOp();
       if (validaop['estado'] == 'error') {
         alerta('error', validaop['salida']);
         return false;
       } */

      var ee = parseFloat($('#totalpagar').val());
      var bb = parseFloat($('input[name="factura\[cobrado\]"]').val());
      var saldo = ee - bb;
      $('input[name="operacion\[saldo\]"]').val(saldo.toFixed(2));
      e.preventDefault();

      if (saldo == 0) {
        $('#form-paid-invoice').ajaxSubmit();
        $('#modaltmp').modal('hide');
        alerta('exito', 'Pago registrado correctamente');
        if (typeof updatefacturas === 'function') {
          updatefacturas();
        } else {
          $('a[href="#nav-facturas"]').trigger("click");
        }
      } else if (saldo > 0) {
        $.confirm({
          theme: 'supervan',
          draggable: false,
          closeIcon: true,
          animationBounce: 2.5,
          escapeKey: false,
          content: 'Esta registrando un monto menor al total de la factura, si registra el pago quedará un saldo pendiente de <h5>MX$' + saldo.toFixed(2) + '</h5>',
          title: '<i class="fas fa-question-circle fa-lg icodialog"></i> Saldo pendiente',
          buttons: {
            confirm: {
              text: 'Si, registrar',
              action: function() {
                $('#form-paid-invoice').ajaxSubmit();
                $('#modaltmp').modal('hide');
                alerta('exito', 'Pago registrado correctamente');
                if (typeof updatefacturas === 'function') {
                  updatefacturas();
                }
                if (typeof clientes_factura === 'function') {}
                $('a[href="#nav-facturas"]').trigger("click");
              }
            },
            cancelar: function() {

            }
          }
        });
      } else {
        $.confirm({
          theme: 'supervan',
          draggable: false,
          closeIcon: true,
          animationBounce: 2.5,
          escapeKey: false,
          content: 'Esta registrando un monto mayor al total de la factura, si registra el pago quedará un saldo a favor del cliente  de <h5>MX$' + Math.abs(saldo).toFixed(2) + '</h5>',
          title: '<i class="fas fa-question-circle fa-lg icodialog"></i> Saldo pendiente',
          buttons: {
            confirm: {
              text: 'Si, registrar',
              action: function() {
                $('#form-paid-invoice').ajaxSubmit();
                $('#modaltmp').modal('hide');
                alerta('exito', 'Pago registrado correctamente');
                if (typeof updatefacturas === 'function') {
                  updatefacturas();
                } else {
                  $('a[href="#nav-facturas"]').trigger("click");
                }
              }
            },
            cancelar: function() {

            }
          }
        });
      }

    });

    $('#formadepagoss').change(function() {
      $('#descripcion_pagos').html('');

      if ($(this).find(':selected').data('dsp1')) {
        $('#descripcion_pagos').append($("<option></option>").attr("value", $(this).find(':selected').data('dsp1')).text($(this).find(':selected').data('dsp1')));
      }

      if ($(this).find(':selected').data('dsp2')) {
        $('#descripcion_pagos').append($("<option></option>").attr("value", $(this).find(':selected').data('dsp2')).text($(this).find(':selected').data('dsp2')));
      }

      if ($(this).find(':selected').data('dsp3')) {
        $('#descripcion_pagos').append($("<option></option>").attr("value", $(this).find(':selected').data('dsp3')).text($(this).find(':selected').data('dsp3')));
      }

      if ($(this).find(':selected').data('dsp4')) {
        $('#descripcion_pagos').append($("<option></option>").attr("value", $(this).find(':selected').data('dsp4')).text($(this).find(':selected').data('dsp4')));
      }


    });


    $('select[name="operacion\[descripcion_pagos\]"] option[value=""]').prop('selected', true);

    $(".Select2").select2({
      placeholder: "Seleccionar..."
    });
    autosize($('textarea'));
  })
</script>