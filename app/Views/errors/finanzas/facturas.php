<div class="panel panel-blue pb-1">
  <div class="panel-heading">
    <h4 class="panel-title"><b>FACTURAS</b></h4>
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onclick="updatefacturas()"><i class="fas fa-sync-alt"></i></a>
    </div>
  </div>
  <div class="panel-body border-panel">
    <form class="form-inline" style="margin-bottom:10px">
      <div class="form-group" style="margin-right: 10px;">
        <label>Fecha</label>
        <div class="input-group input-daterange m-l-5" style="width: 226px;">
          <input type="text" class="form-control" id="txt-desde" name="start" value="<?= $desde; ?>" style="width: 95px" readonly="">
          <span class="input-group-addon">al</span>
          <input type="text" class="form-control" id="txt-hasta" name="end" value="<?= $hasta; ?>" style="width: 95px" readonly="">
        </div>

      </div>


      <div class="form-group" style="margin-right: 15px">
        <label class="p-r-5"> Router </label>
        <select class="form-control filtrofactura sl2 select2-hidden-accessible" name="txt-router" id="txt-router" style="min-width:100px;" onchange="filterfc();" data-select2-id="txt-router" tabindex="-1" aria-hidden="true">
          <option value="cualquiera" data-select2-id="2">Cualquiera</option>
          <?= $nodos ?>
        </select>
      </div>

      <div class="form-group">
        <label class="p-r-5"> Estado </label>
        <select class="form-control filtrofactura sl2 select2-hidden-accessible" name="txt-estado" id="txt-estado" style="min-width:120px;" onchange="filterfc();" data-select2-id="txt-estado" tabindex="-1" aria-hidden="true">
          <option value="cualquiera">Cualquiera</option>
          <option value="no pagado">No pagadas</option>
          <option value="pagado">Pagadas</option>
          <option value="anulado">Anuladas</option>
        </select>
      </div>
    </form>
    <table id="facturas-cliente" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%">
      <thead>
        <tr role="row">
          <th>Nº Factura</th>
          <th>Nº legal</th>
          <th>Cliente</th>
          <th>F. Emitido</th>
          <th>F. Vencimiento</th>
          <th>F. Pagado</th>
          <th>Total</th>
          <th>Impuesto</th>
          <th>SubTotal</th>
          <th>Cobrado</th>
          <th>Referecia oxxo</th>
          <th>Saldo</th>
          <th>Forma de pago</th>
          <th>Nº Identificación</th>
          <th class="all text-center sorting_disabled">Estado</th>
          <th class="all text-center sorting_disabled"></th>
        </tr>
      </thead>
    </table>
    <div id="facturas-cliente_wrapper"></div>
  </div>
  <div class="col-xl-10 p-0 m-t-20 resumen_facturas mb-4" style="margin: 0 auto;">
    <table class="table table-bordered text-center">
      <thead>
        <tr>
          <th>DETALLE</th>
          <th>CANTIDAD</th>
          <th>IMPUESTO</th>
          <th>TOTAL</th>
          <th>COBRADO</th>
        </tr>
      </thead>
      <tbody>
        <tr class="table-success">
          <td class="pagada_detalle"></td>
          <td class="pagada_cantidad"></td>
          <td class="pagada_impuesto"></td>
          <td class="pagada_total"></td>
          <td class="pagada_subtotal"></td>
        </tr>
        <tr class="table-warning">
          <td class="nopagada_detalle" style="font-weight: 600; font-size: 12px">No pagadas</td>
          <td class="nopagada_cantidad" style="font-weight: 600; font-size: 12px">108</td>
          <td class="nopagada_impuesto" style="font-weight: 600; font-size: 12px">MX$5,382.23</td>
          <td class="nopagada_total" style="font-weight: 600; font-size: 12px">MX$42,290.00</td>
          <td class="nopagada_subtotal" style="font-weight: 600; font-size: 12px">MX$0.00</td>
        </tr>
        <tr class="table-danger">
          <td class="vencida_detalle" style="font-weight: 600; font-size: 12px">Vencidas</td>
          <td class="vencida_cantidad" style="font-weight: 600; font-size: 12px">39</td>
          <td class="vencida_impuesto" style="font-weight: 600; font-size: 12px">MX$3,114.93</td>
          <td class="vencida_total" style="font-weight: 600; font-size: 12px">MX$25,638.00</td>
          <td class="vencida_subtotal" style="font-weight: 600; font-size: 12px">MX$0.00</td>
        </tr>
        <tr class="table-active">
          <td class="anulada_detalle" style="font-weight: 600; font-size: 12px">Anuladas</td>
          <td class="anulada_cantidad" style="font-weight: 600; font-size: 12px">1</td>
          <td class="anulada_impuesto" style="font-weight: 600; font-size: 12px">MX$55.17</td>
          <td class="anulada_total" style="font-weight: 600; font-size: 12px">MX$400.00</td>
          <td class="anulada_subtotal" style="font-weight: 600; font-size: 12px">MX$0.00</td>
        </tr>
        <tr class="table-info">
          <td class="anulada_detalle" style="font-weight: 600; font-size: 12px">TOTALES</td>
          <td class="totales_cantidad" style="font-weight: 600; font-size: 12px">1394</td>
          <td class="totales_impuesto" style="font-weight: 600; font-size: 12px">MX$77,812.95</td>
          <td class="totales_total" style="font-weight: 600; font-size: 12px">MX$574,723.32</td>
          <td class="totales_subtotal" style="font-weight: 600; font-size: 12px">MX$488,868.32</td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<div class="modal show" id="modaltmpc" role="dialog" style="padding-right: 17px">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Transacción</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
      </div>

      <form id="form-paid-edit" method="post" action="" class="formin">
        <div class="modal-body">
          <div>

            <div class="form-group">
              <h5 id="titulo"></h5>
              <label>Forma de pago</label>
              <input type="hidden" class="form-control" name="idcliente">
              <input type="hidden" class="form-control" name="operacion[id]">
              <input type="hidden" class="form-control" name="nfactura">
              <select id="formadepago" name="operacion[forma_pago]" class="form-control Select2 select2-hidden-accessible" style="width: 100%" required="" data-select2-id="formadepago" tabindex="-1" aria-hidden="true">
                <option></option>
                <?= $pasarelas ?>
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
              <select class="form-control Select2 select2-hidden-accessible" id="descripcion_pago" name="operacion[descripcion_pago]" style="width: 100%" data-select2-id="descripcion_pago" tabindex="-1" aria-hidden="true">

              </select>
            </div>

            <div id="fecha" class="form-group">
              <label>Fecha &amp; Hora</label>
              <input type="text" class="form-control datetime" name="operacion[fecha_pago]" value="17/07/2020 09:34 AM">
            </div>


            <div class="form-group">
              <label>Nº transacción</label>
              <input type="text" class="form-control" name="operacion[transaccion]" value="">
            </div>

            <div id="fiscal" class="form-group">
              <label>Nº fiscal</label>
              <input type="text" class="form-control" name="operacion[fiscal]" value="">
            </div>

            <div class="form-group">
              <label>Monto</label>
              <div class="input-group">
                <span class="input-group-addon">MX$</span>
                <input type="text" id="monto" class="form-control" name="operacion[cobrado]" value="320.00" required="" title="El monto debe ser superior a 0" disabled="">
              </div>
            </div>


            <div class="form-group">
              <label>Comisión</label>
              <div class="input-group">
                <span class="input-group-addon">MX$</span>
                <input type="text" class="form-control" name="operacion[comision]" pattern="[0-9]*[.]?[0-9]+" value="0.00">
              </div>
            </div>


            <div class="form-group">
              <label>Notas</label>
              <textarea class="form-control" name="operacion[descripcion]" rows="6" style="min-height: 50px; overflow: hidden; overflow-wrap: break-word; height: 124px;"></textarea>
            </div>


          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn-sm btn-azul btn-new-libre">Guardar cambios</button>
        </div>

      </form>

    </div>
  </div>
</div>

<div class="modal show" id="modaltmp4" role="dialog" style="padding-right: 17px; ">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Factura</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
      </div>
      <style type="text/css">
        .popover {
          z-index: 1050 !important;
        }
      </style>
      <form id="form-edit-invoice" method="post" action="finanzas/viewuser?action=saveeditinvoice" class="form-horizontal">
        <div class="modal-body row">
          <div class="col-md-6">
            <input type="hidden" name="descuento" value="0">
            <input type="hidden" name="idfacturamodal" id="idfacturamodal">
            <div class="form-group row">
              <label class="col-md-4 col-form-label text-right">Vencimiento</label>
              <div class="col-md-8">
                <input type="text" class="form-control form-control-sm" name="factura[vencimiento]" value="28/08/2020" style="max-width:100px" readonly="">
              </div>
            </div>

          </div>

          <div class="col-md-6">

            <div class="form-group row">
              <label class="col-md-4 col-form-label text-right">Fecha</label>
              <div class="col-md-8">
                <input type="text" class="form-control form-control-sm" name="factura[emitido]" value="22/07/2020" style="max-width:100px" readonly="">
              </div>
            </div>
          </div>




          <div class="invoice">
            <div class="invoice-content">
              <div class="table-responsive">
                <table class="table table-invoice">
                  <thead>
                    <tr>
                      <th width="60%">Descripción <div class="pull-right"><button type="button" data-loadajax="finanzas/listadeproductos" class="btn btn-sm btn-default"><i class="fas fa-plus"></i> Agregar Productos</button></div>
                      </th>
                      <th style="width:77px;">Precio</th>
                      <th width="60px">Imp %</th>
                      <th width="58px">Cant.</th>
                      <th>Total</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody id="list_item">

                  </tbody>

                </table>

                <div class="pull-left" style="margin-bottom:10px; margin-top:10px">
                  <button type="button" onclick="additem()" class="btn btn-sm btn-default"><i class="fas fa-plus"></i> Agregar Línea</button>
                </div>

              </div>

              <div class="invoice-price">
                <div class="invoice-price-left">
                  <div class="invoice-price-row">
                    <div class="sub-price">
                      <small>SUBTOTAL</small>
                      MX$<span class="spsub">671.80</span>
                    </div>
                    <div class="sub-price">
                      <i class="fas fa-plus"></i>
                    </div>
                    <div class="sub-price">
                      <small>IMPUESTO</small>
                      MX$<span class="spimp">149.20</span>
                    </div>

                    <div class="sub-price">
                      <i class="fas fa-plus"></i>
                    </div>
                    <div class="sub-price">
                      <small>OTROS IMPUESTOS</small>
                      MX$<span class="spimp2">0.00</span>
                    </div>

                    <div class="sub-price">
                      <i class="fas fa-minus"></i>
                    </div>
                    <div class="sub-price">
                      <small>DESCUENTO</small>
                      MX$<span class="spdesc">100</span>
                    </div>

                  </div>
                </div>
                <div class="invoice-price-right">
                  <input type="hidden" value="328" name="factura[idcliente]">
                  <input type="hidden" value="671.80" name="factura[sub_total]">
                  <input type="hidden" value="149.20" name="factura[iva_igv]">
                  <input type="hidden" value="" name="factura[otros_impuestos]">
                  <input type="hidden" value="" name="idfactura">
                  <input type="hidden" value="NO" name="factura[impuesto]">
                  <input type="hidden" value="2" name="factura[tipo]">
                  <input type="hidden" id="fctotal" value="721.00" name="factura[total]">
                  <small>TOTAL</small> MX$<span class="sptot">721.00</span>
                </div>
              </div>
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn-sm btn-azul btn-new-libre" data-loading-text="<i class='fas fa-spinner fa-spin'></i> creando..">Guardar cambios</button>
        </div>

      </form>
    </div>
  </div>
</div>
<script>
  hoy = new Date('<?= $fecha; ?>')
  var facturas;

  var formEditar = document.getElementById('form-paid-edit')
  var monto = document.getElementById('monto')
  var list_item = document.getElementById('list_item')

  $('.sl2').select2();
  configtable('#facturas-cliente', 'facturas');
  App.restartGlobalFunction();
  App.setPageTitle('Facturas');

  function calcular(tipo, base, iva, cantidad) {
    if (base == '' || cantidad == '' || !cantidad || !base) {
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

      return [base, 0, base];
    }

  }

  function iva() {
    var total_imp = 0,
      total_sub = 0,
      total_t = 0;
    $(".table-invoice tbody>tr").each(function(k, v) {
      var cont = 0;
      var subtmp = 0;
      var valor = new Array();
      $('input.form-control', this).each(function(k, c) {
        //--->TOTAL
        if (cont > 3) {
          var txtcal = calcular('NO', valor[1], valor[2], valor[3]);
          if (txtcal[2] !== 'NaN' && txtcal[1] !== 'NaN' && txtcal[0] !== 'NaN') {
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


    var total_timp
    total_timp2 = 0
    var otrosimp = "";
    total_timp = total_t;
    otros_impuestos = $('input[name="factura\[otros_impuestos\]"]').val();
    otros_impuestos = otros_impuestos.split(';');
    for (let i = 0; i < otros_impuestos.length - 1; i++) {
      total_timp2 = total_timp2 + parseFloat(otros_impuestos[i])

    }
    total_t = parseFloat((total_t.toFixed(2)) - parseFloat($('input[name="descuento"]').val()));
    total_t = total_t + parseFloat(total_timp2)
    $('.spdesc').text($('input[name="descuento"]').val().toString().replace("-", ""));


    $('.spsub').text(total_sub.toFixed(2));
    $('.spimp').text(total_imp.toFixed(2));
    $('.spimp2').text(total_timp2.toFixed(2));
    $('.sptot').text(total_t.toFixed(2));

    $('input[name="factura\[sub_total\]"]').val(total_sub.toFixed(2));
    $('input[name="factura\[iva_igv\]"]').val(total_imp.toFixed(2));
    $('input[name="factura\[total\]"]').val(total_t.toFixed(2));

  }

  function additem() {
    var items = '<tr><td><input class="form-control" type="hidden" name="idproducto[]" value="0"><textarea class="form-control" placeholder="Descripción" name="descripcion[]" rows="1"></textarea></td><td><input type="text" class="form-control" placeholder="100"  name="costo[]" value="0"></td><td><input type="text" class="form-control" placeholder="18"  name="impuesto[]" value="0"></td><td><input type="text" class="form-control" placeholder="1"  name="unidad[]" value="1"></td><td><input type="text" class="form-control" placeholder="100"  name="totales[]" disabled></td><td class="removeitem"><input value=0 type="hidden" name="iditem[]"><button type="button" class="removeritem btn btn-sm btn-default"><i class="fas fa-times"></i></button><input type="hidden" name="clave_invoice[]"></td></tr>';
    $('.table-invoice tbody').append(items);
  }

  function closepop() {
    $('*[data-loadajax]').prop("disabled", false);
    $('*[data-loadajax]').popover('dispose');
  }

  $(document).on('keyup', '.table-invoice tbody>tr input', function() {
    iva();
  })

  $('*[data-loadajax]').click(function() {
    $(this).prop("disabled", true);
    var e = $(this);
    $.get(e.data('loadajax'), function(data) {


      e.popover({
        content: data,
        html: true,
        title: '<span class="text-info"><strong>Productos disponibles</strong></span>' +
          '<button type="button" id="close" class="close" onclick="closepop()">&times;</button>'

      }).popover('show');
    });
  });

  $('input[name="factura\[emitido\]"],input[name="factura\[vencimiento\]"]').datepicker({
    todayHighlight: true,
    autoclose: true,
    language: "es",
  });

  $('#form-edit-invoice').ajaxForm({
    success: function(data) {
      $('.btn-new-libre').button("reset")
      $('#gritter-notice-wrapper').remove();
      if (data == 2) {
        alerta('error', data['salida']);
      } else {
        alerta('exito', data['salida']);
        $('a[href="#nav-facturas"]').trigger('click');
      }
      $('#modaltmp4').modal('hide');
      if (typeof updatefacturas == 'function') {
        updatefacturas();
      }
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

  function closepe() {
    $('[data-load-send]').prop("disabled", false);
    $('[data-load-send]').popover('hide');
    $('[data-load-send]').popover('dispose');
  }

  function habilitar_boton() {
    $(".btnaddprod").prop("disabled", false)
  }

  function selectproducto(e) {
    almacen = e.parentElement.parentElement.parentElement.childNodes[3].childNodes[1].childNodes[3]
    tipo = e.selectedOptions[0].dataset.tipo
    var myString = e.value
    if (myString == "0") {
      almacen.innerHTML = '<option></option>'
      $('.hideproduct').hide();
      $(".btnaddprod").prop("disabled", true);
    } else {
      if (tipo == "otro" || tipo == "otros") {
        $('.hideproduct').hide();
        $('.btnaddprod').prop("disabled", false);
      } else {
        $('.btnaddprod').prop("disabled", true);
        //+++++++++++++++++++
        $.ajax({
          data: {
            id: e.value
          },
          url: 'finanzas/popover_productos_disponibles',
          type: 'post',
          success: function(data) {
            if (data != 'error') {
              data = JSON.parse(data)
              $('.hideproduct').show();
              almacen.innerHTML = "<option></option>"
              for (let i = 0; i < data.length; i++) {
                almacen.innerHTML += `<option data-des="${data[i].producto} Nº Serial: ${data[i].serial_producto}" data-costo=${data[i].costo} value=${data[i].idalmacen}>N° mac: ${data[i].mac_producto} (MX$${data[i].costo})</option>`
              }
            } else {

              alerta('error', 'No hay productos disponibles en esta categoría.');
              almacen.innerHTML = "<option></option>"
            }

          }
        });
        //+++++++++++++++++++
      }
    }
  };

  function agregar_item(e) {
    almacen = e.parentElement.parentElement.parentElement.childNodes[3].childNodes[1].childNodes[3]
    producto = e.parentElement.parentElement.parentElement.childNodes[1].childNodes[1].childNodes[3]
    impuesto = producto.selectedOptions[0].dataset.impuesto
    clave_invoice = producto.selectedOptions[0].dataset.codigo
    tipo = producto.selectedOptions[0].dataset.tipo

    var existp = 0;
    if (tipo == 'producto') {
      des = almacen.selectedOptions[0].dataset.des
      costo = almacen.selectedOptions[0].dataset.costo
      idprod = almacen.value
      $('#list_item tbody>tr').each(function(k, v) {
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
      var items = '<tr><td><input class="form-control" type="hidden" name="idproducto[]" value="' + idprod + '"><textarea class="form-control" placeholder="Descripción" name="descripcion[]" rows="1">' + des + '</textarea></td><td><input type="text" class="form-control" placeholder="100"  name="costo[]" value="' + costo + '"></td><td><input type="text" class="form-control" placeholder="18"  name="impuesto[]" value="' + impuesto + '"></td><td><input type="text" class="form-control" placeholder="1"  name="unidad[]" value="1" readonly></td><td><input type="text" class="form-control" placeholder="100"  name="totales[]" disabled></td><td class="removeitem"><input value=0 type="hidden" name="iditem[]"><button type="button" class="removeritem btn btn-sm btn-default"><i class="fas fa-times"></i></button><input type="hidden" name="clave_invoice[]" value="' + clave_invoice + '"></td></tr>';
      /* var items = '<tr><td><input class="form-control" type="hidden" name="idproducto[]" value="' + idprod + '"><textarea class="form-control" placeholder="Descripción" name="descripcion[]" rows="1">' + des + '</textarea></td><td><input type="text" class="form-control" placeholder="100"  name="costo[]" value="' + costo + '"></td><td><input type="text" class="form-control" placeholder="18"  name="impuesto[]" value="' + impuesto + '"></td><td><input type="text" class="form-control" placeholder="1"  name="unidad[]" value="1" readonly></td><td><input type="text" class="form-control" placeholder="100"  name="totales[]" disabled></td><td class="removeitem"><button type="button" class="removeritem btn btn-sm btn-default"><i class="fas fa-times"></i></button><input type="hidden" name="clave_invoice[]" value="' + clave_invoice + '"></td></tr>'; */
      if (idprod && existp < 1) {
        if ($('#fctotal').val() > 0) {
          $('.table-invoice tbody').append(items);
        } else {
          $('.table-invoice tbody').html('').append(items);
        }
      }
      iva();

      closepop()
    } else {
      des = producto.selectedOptions[0].dataset.des
      costo = producto.selectedOptions[0].dataset.costo
      idprod = producto.value
      var items = '<tr><td><input type="hidden" class="form-control" name="idproducto[]" value="0"><textarea class="form-control" placeholder="Descripción" name="descripcion[]" rows="1">' + des + '</textarea></td><td><input type="text" class="form-control" placeholder="100"  name="costo[]" value="' + costo + '"></td><td><input type="text" class="form-control" placeholder="18"  name="impuesto[]" value="' + impuesto + '"></td><td><input type="text" class="form-control" placeholder="1"  name="unidad[]" value="1"></td><td><input type="text" class="form-control" placeholder="100"  name="totales[]" disabled></td><td class="removeitem"><input value=0 type="hidden" name="iditem[]"><button type="button" class="removeritem btn btn-sm btn-default"><i class="fas fa-times"></i></button><input type="hidden" name="clave_invoice[]"></td></tr>';
      if (idprod) {
        if ($('#fctotal').val() > 0) {
          $('.table-invoice tbody').append(items);
        } else {
          $('.table-invoice tbody').html('').append(items);
        }
      }
      iva();

      closepop()
    }
  }

  async function edit_factura(e) {
    $("#modaltmp2").empty()
    $("#modaltmp").empty()
    id = e.dataset.id
    await $.get("finanzas/consulta_items_factura", {
        id
      },
      function(data) {
        data = JSON.parse(data)
        $('#idfacturamodal').val(data[0].idfactura)
        if (data[0].monto < 0) {
          $("[name='descuento']").val(data[0].monto)
        } else {
          $("[name='descuento']").val(0)
        }
        $("[name='factura[emitido]']").datepicker('setDate', data[0].emitido)
        $("[name='factura[vencimiento]']").datepicker('setDate', data[0].vencimiento)

        $('input[name="factura\[otros_impuestos\]"]').val(data[0].otros_impuestos)
        $("#modaltmp4").modal("show")
        $('*[data-loadajax]').prop("disabled", false);

        list_item.innerHTML = ""
        for (let i = 0; i < data.length; i++) {
          list_item.innerHTML +=
            ` <tr>
               <td>
                 <input type="hidden" class="form-control" name="idproducto[]" value=${data[i].idalmacen}>
                 <textarea class="form-control" placeholder="Descripción" name="descripcion[]" rows="1" style="overflow: hidden; overflow-wrap: break-word; height: 142px;">${data[i].descripcion}</textarea></td>
               <td>
                 <input value=${data[i].cantidad} type="text" class="form-control" placeholder="100" name="costo[]" pattern="[-+]?[0-9]*[.]?[0-9]+">
               </td>
               <td>
                 <input value="${data[i].impuesto}" type="text" class="form-control" placeholder="18" name="impuesto[]" pattern="[0-9]*[.]?[0-9]+">
               </td>
               <td>
                 <input value="1" type="text" class="form-control" placeholder="1" name="unidad[]" pattern="[0-9]+">
               </td>
               <td>
                 <input type="text" class="form-control" placeholder="100" name="totales[]" disabled="">
               </td>
               <td class="removeitem"><input value=${data[i].id} type="hidden" name="iditem[]">
                 <button type="button" class="removeritem btn btn-sm btn-default"><i class="fas fa-times"></i></button>
                 <input type="hidden" name="clave_invoice[]" value="">
               </td>
             </tr>`
        }
      }
    );
    iva();
  }

  var footer = '<table  class="table table-bordered text-center">' +
    '<thead><tr><th>DETALLE</th><th>CANTIDAD</th><th>IMPUESTO</th><th>TOTAL</th><th>COBRADO</th></tr></thead><tbody>' +
    '<tr class="table-success"><td class="pagada_detalle" style="font-weight: 600; font-size: 12px">Pagadas</td>' +
    '<td class="pagada_cantidad" style="font-weight: 600; font-size: 12px">0</td>' +
    '<td class="pagada_impuesto" style="font-weight: 600; font-size: 12px">0</td>' +
    '<td class="pagada_total" style="font-weight: 600; font-size: 12px">0</td><td class="pagada_subtotal" style="font-weight: 600; font-size: 12px">0</td></tr>' +
    '<tr class="table-warning"><td class="nopagada_detalle" style="font-weight: 600; font-size: 12px">No pagadas</td>' +
    '<td class="nopagada_cantidad" style="font-weight: 600; font-size: 12px">0</td>' +
    '<td class="nopagada_impuesto" style="font-weight: 600; font-size: 12px">0</td>' +
    '<td class="nopagada_total" style="font-weight: 600; font-size: 12px">0</td><td class="nopagada_subtotal" style="font-weight: 600; font-size: 12px">0</td></tr>' +
    '<tr class="table-danger"><td class="vencida_detalle" style="font-weight: 600; font-size: 12px">Vencidas</td>' +
    '<td class="vencida_cantidad" style="font-weight: 600; font-size: 12px">0</td>' +
    '<td class="vencida_impuesto" style="font-weight: 600; font-size: 12px">0</td>' +
    '<td class="vencida_total" style="font-weight: 600; font-size: 12px">0</td><td class="vencida_subtotal" style="font-weight: 600; font-size: 12px">0</td></tr>' +
    '<tr class="table-active"><td class="anulada_detalle" style="font-weight: 600; font-size: 12px">Anuladas</td>' +
    '<td class="anulada_cantidad" style="font-weight: 600; font-size: 12px">0</td>' +
    '<td class="anulada_impuesto" style="font-weight: 600; font-size: 12px">0</td>' +
    '<td class="anulada_total" style="font-weight: 600; font-size: 12px">0</td><td class="anulada_subtotal" style="font-weight: 600; font-size: 12px">0</td></tr>' +
    '<tr class="table-info"><td class="anulada_detalle" style="font-weight: 600; font-size: 12px">TOTALES</td>' +
    '<td class="totales_cantidad" style="font-weight: 600; font-size: 12px">0</td>' +
    '<td class="totales_impuesto" style="font-weight: 600; font-size: 12px">0</td>' +
    '<td class="totales_total" style="font-weight: 600; font-size: 12px">0</td><td class="totales_subtotal" style="font-weight: 600; font-size: 12px">0</td></tr>' +
    '</tbody></table>';


  function getmodal_pagar(e) {
    $("#fecha").hide()
    $("#fiscal").show()

    monto.removeAttribute("disabled")

    $('#modaltmpc').modal('show')
    $("[name='idcliente']").val(e.dataset.idcliente)
    $("[name='operacion[fiscal]']").val(e.dataset.fiscal)
    $("[name='operacion[id]']").val("")
    $("[name='nfactura']").val(e.dataset.id)
    $("[name='operacion[cobrado]']").val(e.dataset.total)
    $("[name='operacion[comision]']").val("")
    $("[name='operacion[descripcion]']").val("")
    $("[name='operacion[transaccion]']").val("")
    $("#titulo").text("")
    $("[name='operacion[fecha_pago]']").val("")
    $("#formadepago").val("").change()
    $("#descripcion_pago").val("").change()
    $(`#descripcion_pago option[value=""]`).prop('selected', true);
  }



  function updatefacturas() {
    facturas.ajax.reload(null, false);

  }

  function send_invoice(id) {
    closepe();
    alerta('loader', 'Generando PDF...');
    $.post("ajax/viewuser", {
        id: id,
        action: "sendinvoice"
      })
      .done(function(data) {
        $('#gritter-notice-wrapper').remove();
        if (data['estado'] == 'error') {
          alerta('error', data['salida']);
        } else {
          alerta('exito', data['salida']);
        }
      });
  }

  function cancel_invoice(e) {
    id = e.dataset.id
    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Si la factura se encuentra con productos agregados estos serán removidos de la factura y serán restaurados al almacén.',
      title: '<i class="far fa-question-circle fa-lg icodialog"></i> Anular Factura #' + id,
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt icodialog"></i> Anular', // With spaces and symbols
          action: function() {
            $.post("finanzas/viewuser", {
                id: id,
                action: "cancelinvoice"
              })
              .done(function(data) {

                if (data == "1") {
                  updatefacturas();
                  alerta('exito', 'Factura #' + id + ' anulada correctamente')
                } else {
                  alerta('error', 'Ocurrio un error vuelva a intentarlo')

                }
              });

          }
        },
        Cancelar: {}
      }
    });

  }

  function eliminar_pago(e) {
    fc = e.dataset.id

    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Al eliminar esta transacción la factura <b>Nº ' + fc + '</b> pasará como estado <b>NO PAGADO</b>, si hay saldos no procesados estos serán eliminados.',
      title: '<i class="far fa-question-circle fa-lg icodialog"></i> Eliminar transacción',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt icodialog"></i> Eliminar pago',
          action: function() {
            $.post("finanzas/viewuser", {
                factura: fc,
                action: "deletetransaccion"
              })
              .done(function(data) {
                updatefacturas();
                alerta('exito', 'Pago eliminado correctamente')
              });

          }
        },
        Cancelar: {}
      }
    });


  }

  function filterfc() {
    facturas.ajax.url("finanzas/get_facturas?action=lista&state=" + $('#txt-estado').val() + "&from=" + $('#txt-desde').val() + "&to=" + $('#txt-hasta').val() + "&nodo=" + $('#txt-router').val()).load();
    //facturas.columns.adjust().responsive.recalc();
  }



  function delete_invoice(e) {
    id = e.dataset.id
    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Si la factura se encuentra con productos agregados del almacen estos serán restaurados al almacén.<br> Saldos agregados a esta factura volverán a estar pendientes',
      title: '<i class="fas fa-question-circle fa-lg icodialog"></i> Eliminar Factura #' + id,
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt icodialog"></i> Eliminar', // With spaces and symbols
          action: function() {
            $.post("finanzas/viewuser", {
                id: id,
                action: "eliminar_factura"
              })
              .done(function(data) {
                if (data == "1") {
                  updatefacturas();
                  alerta('exito', 'Factura #' + id + ' eliminada correctamente')
                } else {
                  alerta('error', 'Ocurrio un error, vuelva a intentarlo')
                }
              });

          }
        },
        Cancelar: {}
      }
    });


  }


  facturas = $('#facturas-cliente').DataTable({
    "ajax": "finanzas/get_facturas?action=lista&state=" + $('#txt-estado').val() + "&from=" + $('#txt-desde').val() + "&to=" + $('#txt-hasta').val() + "&nodo=" + $('#txt-router').val(),
    "deferRender": true,
    'processing': true,
    'language': {
      'processing': 'Cargando'
    },
    "idDataTables": "40",
    "bAutoWidth": false,
    "aoColumns": [{
        mData: 'idfactura',
        "render": function(data, type, row) {
          return `<a target="_blank" href ="finanzas/factura_pdf?fc=${data}">${parseInt(data)}</a>`

        },
        sWidth: "20px"
      },
      {
        mData: 'legal'
      },
      {
        mData: 'nombre',
        "render": function(data, type, row) {
          return `<a href="#clientes/detalleuser?id=${row.idcliente}">${data}</a>`

        },
      },
      {
        mData: 'emitido',
        sClass: 'text-center'
      },
      {
        mData: 'vencimiento',
        sClass: 'text-center'
      },
      {
        mData: 'pagado',
        "render": function(data, type, row) {
          if (data == "0000-00-00") {
            return "N/A"
          } else {
            return data
          }
        },
        sClass: 'text-center',
        visible: false
      },
      {
        mData: 'total',
        "render": function(data, type, row) {
          return 'MX$' + data
        },
        sClass: 'text-center'
      },
      {
        mData: 'impuesto',
        "render": function(data, type, row) {
          return 'MX$' + data
        },
        sClass: 'text-center',
        visible: false
      },
      {
        mData: 'subtotal',
        "render": function(data, type, row) {
          return 'MX$' + data
        },
        sClass: 'text-center',
        visible: false
      },
      {
        mData: 'cobrado',
        "render": function(data, type, row) {
          return 'MX$' + data
        },
        sClass: 'text-center'
      },
      {
        mData: 'oxxo',
        sClass: 'text-center',
        visible: false
      },
      {
        mData: 'saldo',
        "render": function(data, type, row) {
          if (data != null) {
            return 'MX$' + data
          } else {
            return "N/A"
          }
        },
        sClass: 'text-center'
      },
      {
        mData: 'forma',
        sClass: 'text-center'
      },
      {
        mData: 'cedula'
      },
      {
        mData: 'estado',
        "render": function(data, type, row) {
          if (data == "pagado") return `<span class="label label-success">PAGADO</span>`
          if (data == "No pagado") {
            if (new Date(row.vencimiento) < hoy) {
              return `<span class="label label-danger">VENCIDO</span>`
            } else {
              return `<span class="label label-warning">NO PAGADO</span>`
            }
          }
          if (data == "anulado") return `<span class="label label-inverse">ANULADO</span>`
        },
        sClass: 'text-center'
      },
      {
        mData: 'tool',

        "render": function(data, type, row) {
          if (data >= 2 && row.estado == "pagado") {
            return `
              <a style="display:none;" class="btn btn-default btn-icon btn-sm" data-load-send="finanzas/facturas?action=enviarcomprobante&amp;id=${row.idfactura}"><i class="far fa-share-square" aria-hidden="true"></i></a>
              <button data-toggle="tooltip" title="Eliminar pago" class=" btn btn-default btn-icon btn-sm" data-id=${row.idfactura} onclick="eliminar_pago(this)"><i class="fas fa-times"></i></button>
              <a onclick=\"getmodal('finanzas/viewuser?action=edittransaccion&amp;origen=pagos&amp;tipo=op&amp;id=${row.idfactura}','Editar Transacción','sm');\" data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" data-original-title=\"Editar pago\"><i class=\"far fa-edit\"></i></a>
              `
          }
          if (data >= 2 && row.estado == "No pagado") {
            return ` 
              <a style="display:none;" class="btn btn-default btn-icon btn-sm" data-load-send="finanzas/facturas?action=enviarcomprobante&amp;id=${row.idfactura}"><i class="far fa-share-square" aria-hidden="true"></i></a>
              <a data-toggle="tooltip" title="" class=" btn btn-default btn-icon btn-sm" onclick="getmodal('finanzas/viewuser?action=paidinvoice&amp;origen=facturas&amp;id=${row.idfactura}','Agregar pago','sm');" data-original-title="Agregar pago"><i class="fas fa-check"></i></a>
              <a data-toggle="tooltip" title="Editar factura" class=" btn btn-default btn-icon btn-sm" data-id=${row.idfactura} onclick="edit_factura(this);"><i class="far fa-edit"></i></a>
              <a data-toggle="tooltip" title="Eliminar" class=" btn btn-default btn-icon btn-sm" data-id=${row.idfactura} onclick="delete_invoice(this)"><i class="far fa-trash-alt" aria-hidden="true"></i></a>
              <a data-toggle="tooltip" title="Anular" class=" btn btn-default btn-icon btn-sm" data-id=${row.idfactura} onclick="cancel_invoice(this)"><i class="fas fa-ban" aria-hidden="true"></i></a>
              `
          }

          return '';
        },
        sClass: 'text-center',
        sWidth: "40px"
      }
    ],

    "drawCallback": function(settings) {
      var moneda = "MX$";
      var pagada_cantidad = 0,
        pagada_subtotal = 0,
        pagada_impuesto = 0,
        pagada_total = 0
      var nopagada_cantidad = 0,
        nopagada_subtotal = 0,
        nopagada_impuesto = 0,
        nopagada_total = 0
      var vencida_cantidad = 0,
        vencida_subtotal = 0,
        vencida_impuesto = 0,
        vencida_total = 0
      var anulada_cantidad = 0,
        anulada_subtotal = 0,
        anulada_impuesto = 0,
        anulada_total = 0

      var api = this.api().rows({
        search: 'applied'
      }).data();
      $.each(api, function(key, value) {

        switch (value.estado) {
          case "pagado":
            pagada_cantidad++;
            pagada_subtotal += parseFloat(value.cobrado);
            pagada_impuesto += parseFloat(value.impuesto);
            pagada_total += parseFloat(value.total);
            break;
          case "No pagado":
            fecha = new Date(value.vencimiento);

            if (fecha < hoy) {
              vencida_cantidad++;
              vencida_subtotal += parseFloat(value.cobrado);
              vencida_impuesto += parseFloat(value.impuesto);
              vencida_total += parseFloat(value.total);
            } else {
              nopagada_cantidad++;
              nopagada_subtotal += parseFloat(value.cobrado);
              nopagada_impuesto += parseFloat(value.impuesto);
              nopagada_total += parseFloat(value.total);
            }
            break;
          case "anulado":
            anulada_cantidad++;
            anulada_subtotal += parseFloat(value.cobrado);
            anulada_impuesto += parseFloat(value.impuesto);
            anulada_total += parseFloat(value.total);

            break;

        }

      });

      var totales_cantidad = pagada_cantidad + nopagada_cantidad + vencida_cantidad + anulada_cantidad;
      var totales_subtotal = pagada_subtotal + nopagada_subtotal + vencida_subtotal + anulada_subtotal;
      var totales_impuesto = pagada_impuesto + nopagada_impuesto + vencida_impuesto + anulada_impuesto;
      var totales_total = pagada_total + nopagada_total + vencida_total + anulada_total;

      $('.resumen_facturas').html(footer);

      $('.pagada_cantidad').html(pagada_cantidad);
      $('.pagada_subtotal').html(moneda + pagada_subtotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
      $('.pagada_impuesto').html(moneda + pagada_impuesto.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
      $('.pagada_total').html(moneda + pagada_total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

      $('.nopagada_cantidad').html(nopagada_cantidad);
      $('.nopagada_subtotal').html(moneda + nopagada_subtotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
      $('.nopagada_impuesto').html(moneda + nopagada_impuesto.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
      $('.nopagada_total').html(moneda + nopagada_total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

      $('.vencida_cantidad').html(vencida_cantidad);
      $('.vencida_subtotal').html(moneda + vencida_subtotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
      $('.vencida_impuesto').html(moneda + vencida_impuesto.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
      $('.vencida_total').html(moneda + vencida_total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

      $('.anulada_cantidad').html(anulada_cantidad);
      $('.anulada_subtotal').html(moneda + anulada_subtotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
      $('.anulada_impuesto').html(moneda + anulada_impuesto.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
      $('.anulada_total').html(moneda + anulada_total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

      $('.totales_cantidad').html(totales_cantidad);
      $('.totales_subtotal').html(moneda + totales_subtotal.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
      $('.totales_impuesto').html(moneda + totales_impuesto.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
      $('.totales_total').html(moneda + totales_total.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

    },

    initComplete: function(oSettings, json) {

      this.api().columns().every(function() {
        var state = this.state.loaded();
        var column = this;

        var txtsrh = '';
        if (state) {
          txtsrh = state.columns[column[0]].search.search;
        }

        $('<input type="text" placeholder="Buscar" class="form-control" value="' + txtsrh + '">')
          .appendTo($(column.footer()).empty())
          .on('keyup', function() {
            column.search($(this).val()).draw();
          });

      });

      $('.not-search-input').html('');
      this.api().columns().every(function() {
        var state = this.state.loaded();
        var column = this;

        var txtsrh = '';
        if (state) {
          txtsrh = state.columns[column[0]].search.search;
        }

        $('<input type="text" placeholder="Buscar" class="form-control" value="' + txtsrh + '">')
          .appendTo($(column.footer()).empty())
          .on('keyup', function() {
            column.search($(this).val()).draw();
          });

      });

      $('.not-search-input').html('');

      //------->Button Col
      $("#facturas-cliente_wrapper div.tooltablas")
        .html(`<div class="grupotabla btn-group">\
  		<button type="button" class="btn btn-default" Onclick="alerta('warning','Funcion en desarrollo');"><i class="fas fa-cogs"></i> Herramientas</button>\
		</div>`);

    }
  }).on('processing.dt', function(e, settings, processing) {
    if (processing) {
      loaderin('.panel-facturas');
    } else {
      loaderout('.panel-facturas');
    }
  }).on('draw', function() {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
  });

  //--->Fechas
  $('.input-daterange').datepicker({
    language: "es",
    toggleActive: true,
    todayHighlight: true,
    autoclose: true
  }).on('changeDate', function(ev) {
    facturas.ajax.url("finanzas/get_facturas?action=lista&state=" + $('#txt-estado').val() + "&from=" + $('#txt-desde').val() + "&to=" + $('#txt-hasta').val() + "&nodo=" + $('#txt-router').val()).load();
    //facturas.columns.adjust().responsive.recalc();
  });




  //modal

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

  $('#formadepago option[value="Efectivo Oficina/Sucursal"]').prop('selected', true);

  $('#formadepago').trigger('change');

  $(".Select2").select2({
    placeholder: "Seleccionar..."
  });
  autosize($('textarea'));
</script>