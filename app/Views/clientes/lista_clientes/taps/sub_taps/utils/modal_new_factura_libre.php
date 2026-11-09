<style type="text/css">
  .popover {
    z-index: 1050 !important;
  }
</style>
<form id="form-new-libre" method="post" action="clientes_facturacion/save_factura_libre" class="form-horizontal">
  <div class="modal-body row">
    <input type="hidden" name="descuento" value="0">
    <input type="hidden" name="descuento2" value="0">
    <div class="col-md-6">

      <div class="form-group row">
        <label class="col-md-4 col-form-label text-right">Vencimiento</label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm" name="factura[vencimiento]" value="<?= $fecha; ?>" style="max-width:100px" readonly>
        </div>
      </div>

    </div>

    <div class="col-md-6">

      <div class="form-group row">
        <label class="col-md-4 col-form-label text-right">Fecha</label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm" name="factura[emitido]" value="<?= $fecha; ?>" style="max-width:100px" readonly>
        </div>
      </div>
    </div>




    <div class="invoice">
      <div class="invoice-content">
        <div class="table-responsive">
          <table class="table table-invoice1">
            <thead>
              <tr>
                <th width="60%">Descripción <div class="pull-right"><button type="button" data-loadajax="clientes_facturacion/get_popup" class="btn btn-sm btn-default"><i class="fas fa-plus"></i> Agregar Productos</button></div>
                </th>
                <th style="width:77px;">Precio</th>
                <th width="60px">Imp %</th>
                <th width="58px">Cant.</th>
                <th>Total</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input type="hidden" class="form-control" name="idproducto[]" value="0"><textarea class="form-control" placeholder="Descripción" name="descripcion[]" rows="1"></textarea></td>
                <td><input type="text" class="form-control" placeholder="100" name="costo[]" pattern="[-+]?[0-9]*[.]?[0-9]+" value="0"></td>
                <td><input type="text" class="form-control" placeholder="18" name="impuesto[]" value="0" pattern="[0-9]*[.]?[0-9]+"></td>
                <td><input type="text" class="form-control" placeholder="1" name="unidad[]" value="1" pattern="[0-9]+"></td>
                <td><input type="text" class="form-control" placeholder="100" name="totales[]" disabled></td>
                <td class="removeitem"><input type="hidden" name="clave_invoice[]"></td>
              </tr>

            </tbody>

          </table>

          <div class="pull-left" style="margin-bottom:10px; margin-top:10px">
            <button type="button" onClick="additem()" class="btn btn-sm btn-default"><i class="fas fa-plus"></i> Agregar Línea</button>
          </div>

        </div>
        <div class="invoice-price">
          <div class="invoice-price-left">
            <div class="invoice-price-row">
              <div class="sub-price">
                <small>SUBTOTAL</small>
                MX$<span class="spsub">0</span>
              </div>
              <div class="sub-price">
                <i class="fas fa-plus"></i>
              </div>
              <div class="sub-price">
                <small>IMPUESTO</small>
                MX$<span class="spimp">0</span>
              </div>
            </div>
          </div>
          <div class="invoice-price-right">
            <input type="hidden" value="<?= $idcliente; ?>" name="factura[idcliente]">
            <input type="hidden" value="0" name="factura[sub_total]">
            <input type="hidden" value="0" name="factura[iva_igv]">
            <input type="hidden" value="<?= $impuesto; ?>" name="factura[impuesto]">
            <input type="hidden" id="fctotal" value="0" name="factura[total]">
            <small>TOTAL</small> MX$<span class="sptot">0</span>
          </div>
        </div>
      </div>
    </div>

  </div>

  <div class="modal-footer">
    <button type="submit" class="btn-sm btn-azul btn-new-libre" data-loading-text="<i class='fas fa-spinner fa-spin'></i> creando..">Crear Factura</button>
  </div>

</form>


<script>
  function calcular(tipo, base, iva, cantidad) {
    if (base == '' || cantidad == '') {
      ivaN = 0;
      base = 0;
      total = 0;
      return [base, ivaN, total];
    }

    var total, ivaN;
    base2 = parseFloat(base) * cantidad;
    base = base * cantidad;

    //-->Cero
    if (base2 == 0) {

      ivaN = 0;
      base = 0;
      total = 0;
      return [base, ivaN, total];
      //+++++++SIN IVA
    } else if (iva == 0) {
      ivaN = 0;
      base = (base2.toFixed(2));
      total = (base2.toFixed(2));

      return [base, ivaN, total];
      //++++++++ MAS IVA
    } else if (tipo == 'SI') {
      ivaN = parseFloat(base) * parseFloat(iva) / 100
      var num = new Number(parseFloat(ivaN));
      ivaN = (num.toFixed(2));
      total = parseFloat(base) + parseFloat(ivaN)
      total = (total.toFixed(2));
      base = (base.toFixed(2));

      return [base, ivaN, total];

      //++++++++ IVA INCLUIDO
    } else if (tipo == 'NO') {
      total = base;
      var ivaS = "1." + iva;
      var restamos = parseFloat(total) / parseFloat(ivaS);
      restamos = (restamos.toFixed(2));
      base = parseFloat(restamos);
      base = (base.toFixed(2));
      ivaN = parseFloat(total) - parseFloat(restamos);
      ivaN = (ivaN.toFixed(2));
      total = (total.toFixed(2));

      return [base, ivaN, total];

      //-->Ningun impuesto
    } else {
      ivaN = 0;
      base = (base.toFixed(2));
      ivaN = (ivaN.toFixed(2));
      total = (base.toFixed(2));

      return [base, ivaN, total];
    }

  }

  function iva() {

    var total_imp = 0,
      total_sub = 0,
      total_t = 0;
    $(".table-invoice1 tbody>tr").each(function(k, v) {
      var cont = 0;
      var subtmp = 0;
      var valor = new Array();
      $('input.form-control', this).each(function(k, c) {
        //--->TOTAL
        if (cont > 3) {
          var txtcal = calcular('<?= $impuesto; ?>', valor[1], valor[2], valor[3]);
          if (txtcal[2] !== 'NaN' && txtcal[1] !== 'NaN' && txtcal[0] !== 'NaN') {
            //subtmp=txtcal[2]*valor[3]
            $(this).val('MX$' + txtcal[2]);
            total_imp = total_imp + parseFloat(txtcal[1]);
            total_sub = total_sub + parseFloat(txtcal[0]);
          } else {
            $(this).val(0);
          }
        } else {
          valor[cont] = parseFloat($(this).val());
          cont++;
        }
      });
    });

    total_t = total_sub + total_imp;

    total_t = parseFloat(total_t.toFixed(2)) + parseFloat($('input[name="descuento"]').val());



    if (parseFloat($('input[name="descuento"]').val()) <= 0) {
      //-->Si Hay mas saldo que el total
      if (total_t < 0) {
        var txtdesc = parseFloat($('input[name="descuento"]').val()) - total_t;
        $('input[name="descuento2"]').val(txtdesc);
        $('.spdesc').text(txtdesc.toString().replace("-", ""));
        total_t = 0;
      } else {
        var txtdesc = parseFloat($('input[name="descuento"]').val());
        $('input[name="descuento2"]').val(txtdesc);
        $('.spdesc').text(txtdesc.toString().replace("-", ""));
      }


    }

    $('.spsub').text(total_sub.toFixed(2));
    $('.spimp').text(total_imp.toFixed(2));
    $('.sptot').text(total_t.toFixed(2));

    $('input[name="factura\[sub_total\]"]').val(total_sub.toFixed(2));
    $('input[name="factura\[iva_igv\]"]').val(total_imp.toFixed(2));
    $('input[name="factura\[total\]"]').val(total_t.toFixed(2));

  }

  function additem() {
    var items = '<tr><td><input class="form-control" type="hidden" name="idproducto[]" value="0"><textarea class="form-control" placeholder="Descripción" name="descripcion[]" rows="1"></textarea></td><td><input type="text" class="form-control" placeholder="100"  name="costo[]" value="0"></td><td><input type="text" class="form-control" placeholder="18"  name="impuesto[]" value="0"></td><td><input type="text" class="form-control" placeholder="1"  name="unidad[]" value="1"></td><td><input type="text" class="form-control" placeholder="100"  name="totales[]" disabled></td><td class="removeitem"><button type="button" class="removeritem btn btn-sm btn-default"><i class="fas fa-times"></i></button><input type="hidden" name="clave_invoice[]"></td></tr>';
    $('.table-invoice1 tbody').append(items);
  }

  function closep() {
    $('*[data-loadajax]').prop("disabled", false);
    $('*[data-loadajax]').popover('dispose');
  }

  $(function() {

    $(document).on('keyup', '.table-invoice1 tbody>tr input', function() {

      iva();
    })

    $('*[data-loadajax]').click(function() {
      $(this).prop("disabled", true);
      var e = $(this);
      $.get(e.data('loadajax'), function(d) {
        e.popover({
          content: d,
          html: true,
          title: '<span class="text-info"><strong>Productos disponibles</strong></span>' +
            '<button type="button" id="close" class="close" onclick="closep()">&times;</button>'

        }).popover('show');
      });
    });

    $('input[name="factura\[emitido\]"],input[name="factura\[vencimiento\]"]').datepicker({
      todayHighlight: true,
      autoclose: true,
      language: "es",
    });

    $(document).on('click', '.removeritem', function() {
      $(this).parents("tr").remove();
      iva();
    });

    $('#form-new-libre').ajaxForm({
      success: function(data) {
        $('.btn-new-libre').button("reset")
        $('#gritter-notice-wrapper').remove();
        if (data == 2) {
          alerta('error', data['salida']);
        } else {
          alerta('exito', data['salida']);
          $('a[href="#nav-facturas"]').trigger('click');
        }
        $('#modaltmp').modal('hide');
      },
      beforeSubmit: function() {
        $('.btn-new-libre').button("loading")
      },
      error: function(response) {
        $('#gritter-notice-wrapper').remove();
        alerta('error500', response['responseText']);
        $('.btn-new-libre').button("reset")
      }
    })


    $('#modaltmp').modal('show');

    autosize(document.querySelectorAll('textarea'));
  })
</script>