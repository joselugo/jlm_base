<div class='col-sm-12'>
  <div class='form-group input-group-sm'>
    <label style="font-size: 12px;">Categoria producto</label>
    <select class="slp form-control" id="productoid" name="productoid" style="width:100%">
      <option></option>
      <optgroup label="Productos">
        <?= $productos; ?>
      </optgroup>
      <optgroup label="Otros Productos y Servicios">
        <?= $servicios; ?>
      </optgroup>
    </select>
  </div>
</div>

<div class='col-sm-12 hideproduct' style="display:none">
  <div class='form-group input-group-sm'>
    <label style="font-size: 12px;">Producto</label>
    <select class="form-control slp" id="almacenid" name="almacenid" style="width:100%">
      <option></option>
    </select>
  </div>
</div>

<div class='col-sm-12'>
  <div class='form-group input-group-sm'>
    <input type="button" class="btn btn-sm btn-white btnaddprod" value="Agregar producto" disabled>
  </div>
</div>


<script>
  $("#productoid").change(function() {
    var myString = $("#productoid").val();
    if (myString == "0") {
      $("#almacenid").html('<option></option>');
      $('.hideproduct').hide();
      $(".btnaddprod").prop("disabled", true);
    } else {
      var arr = $(this).find(':selected').data('tipo');
      if (arr == "otros") {
        $('.hideproduct').hide();
        $(".btnaddprod").prop("disabled", false);
      } else {
        $(".btnaddprod").prop("disabled", true);
        //+++++++++++++++++++
        $.ajax({
          data: {
            id: $("#productoid").val()
          },
          url: 'clientes_facturacion/popup_producto',
          type: 'post',
          success: function(data) {
            if (data) {
              data = JSON.parse(data)
              $('.hideproduct').show();
              $('#almacenid').html('<option></option>');
              data.forEach(producto => {
                descripcion = producto.descripcion.replace("{producto_serial}", producto.serial_producto);
                $('#almacenid').append(`<option data-des="${descripcion}" data-costo="${producto.costo_almacen}" value="${producto.idalmacen}">Nº mac: ${producto.mac_producto} (MX$${dosDecimales(producto.costo_almacen)})</option>`)
              })
            } else {
              alerta('error', 'No hay productos disponibles en esta categoría.');
              $('#almacenid').html('<option></option>');
              $('.hideproduct').hide();
            }

          }
        });
        //+++++++++++++++++++
      }
    }

  });

  $("#almacenid").change(function() {
    $(".btnaddprod").prop("disabled", false);
  });

  $(".slp").select2({
    placeholder: "Seleccionar.."
  });

  $('.btnaddprod').click(async function(e) {
    cambio = $('#fctotal').val()
    var existp = 0;
    if ($('#productoid').find(':selected').data('tipo') == 'producto') {
      var impuesto = $('#productoid').find(':selected').data('impuesto');
      var des = $('#almacenid').find(':selected').data('des');
      var costo = $('#almacenid').find(':selected').data('costo');
      var idprod = $('#almacenid').val();
      var clave_invoice = $('#productoid').find(':selected').data('codigo');
      $(".table-invoice1 tbody>tr").each(function(k, v) {
        var cont = 0;
        $('input', this).each(function(k, c) {
          if (cont == 0 && $(this).val() == idprod) {
            alerta('error', 'El producto ya estaba agregado en la factura');
            existp = 2;
            return false;
          }
          cont++;
        });
      });
      var items = '<tr><td><input class="form-control" type="hidden" name="idproducto[]" value="' + idprod + '"><textarea class="form-control" placeholder="Descripción" name="descripcion[]" rows="1">' + des + '</textarea></td><td><input type="text" class="form-control" placeholder="100"  name="costo[]" value="' + costo + '"></td><td><input type="text" class="form-control" placeholder="18"  name="impuesto[]" value="' + impuesto + '"></td><td><input type="text" class="form-control" placeholder="1"  name="unidad[]" value="1" readonly></td><td><input type="text" class="form-control" placeholder="100"  name="totales[]" disabled></td><td class="removeitem"><button type="button" class="removeritem btn btn-sm btn-default"><i class="fas fa-times"></i></button><input type="hidden" name="clave_invoice[]" value="' + clave_invoice + '"></td></tr>';
      if (idprod && existp < 1) {

        if (cambio > 0) {
          await $('.table-invoice1 tbody').append(items);
        } else {
          await $('.table-invoice1 tbody').html('').append(items);
        }
      }
      iva();
      closep()
    } else {
      var impuesto = $('#productoid').find(':selected').data('impuesto');
      var des = $('#productoid').find(':selected').data('des');
      var costo = $('#productoid').find(':selected').data('costo');
      var idprod = $('#productoid').val();

      var items = '<tr><td><input type="hidden" class="form-control" name="idproducto[]" value="0"><textarea class="form-control" placeholder="Descripción" name="descripcion[]" rows="1">' + des + '</textarea></td><td><input type="text" class="form-control" placeholder="100"  name="costo[]" value="' + costo + '"></td><td><input type="text" class="form-control" placeholder="18"  name="impuesto[]" value="' + impuesto + '"></td><td><input type="text" class="form-control" placeholder="1"  name="unidad[]" value="1"></td><td><input type="text" class="form-control" placeholder="100"  name="totales[]" disabled></td><td class="removeitem"><button type="button" class="removeritem btn btn-sm btn-default"><i class="fas fa-times"></i></button><input type="hidden" name="clave_invoice[]"></td></tr>';
      if (idprod) {
        if (cambio > 0) {
          await $('.table-invoice1 tbody').append(items);
        } else {
          await $('.table-invoice1 tbody').html('').append(items);
        }
      }
      iva();
      closep()
    }

  });

  function dosDecimales(n) {
    let t = n.toString();
    let regex = /(\d*.\d{0,2})/;
    return t.match(regex)[0];
  }
</script>