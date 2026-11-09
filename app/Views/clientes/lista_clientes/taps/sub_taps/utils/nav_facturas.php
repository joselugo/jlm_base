<div class="rowfc">
  <div class="table-responsive">
    <table id="data-tabinvoices" class="display nowrap table table-striped table-bordered" cellspacing="0" width="100%" data-order="[[ 0, &quot;desc&quot; ]]">
      <thead>
        <tr>
          <th class="all" style="max-width:60px !important">Nº Factura</th>
          <th style="max-width:60px !important">Nº Fiscal</th>
          <th>Emitido</th>
          <th>Vencimiento</th>
          <th class="all" width="40px">Estado</th>
          <th class="all" style="max-width:60px !important">total</th>
          <th style="max-width:60px !important">Impuesto</th>
          <th>tipo</th>
          <th>Pagado</th>
          <th>Fecha Pago</th>
          <th>Imp #1</th>
          <th>Imp #2</th>
          <th>Imp #3</th>
          <th>Forma pago</th>
          <th class="all" style="max-width:20px !important" data-orderable="false"></th>
        </tr>
      </thead>

      <tbody>
        <?php
        foreach ($facturas as $factura) : ?>
          <?php
          $tipo_css = "";
          $tools = "";
          if ($factura->tipo == 1) {
            $tipo_css = "success";
            $factura->tipo = "LIBRE";
          }
          if ($factura->tipo == 2) {
            $tipo_css = "warning";
            $factura->tipo = "SERVICIOS";
          }
          $factura->impuesto == 0 ? $impuesto = "N/A" : $impuesto = "MX$" . $factura->impuesto;
          if ($factura->estado == "anulado") {
            $tools = "<a data-toggle=\"tooltip\" title=\"Ver PDF\" class=\"btn btn-default btn-icon btn-sm\" target=\"_blank\" href=\"finanzas/factura_pdf?fc=$factura->id\"><i class=\"far fa-file-pdf\"></i></a>";
            $estado_css = "inverse";
          }
          if ($factura->estado == "pagado") {
            $estado_css = "success";
            $tools = "
            <a data-toggle=\"tooltip\" title=\"Ver PDF\" class=\"btn btn-default btn-icon btn-sm\" target=\"_blank\" href=\"finanzas/factura_pdf?fc=$factura->id\"><i class=\"far fa-file-pdf\"></i></a>";
            if ($permiso3 >= 3) {
              $tools .= "<button onClick=\"eliminar_pago('$factura->id')\" data-toggle=\"tooltip\" title=\"Eliminar pago\" class=\"btn btn-default btn-icon btn-sm\">
                <i class=\"fas fa-times\"></i>
              </button>";
            }
           
          /* <a data-toggle=\"tooltip\" title=\"Generar referencia OXXO\" class=\"btn btn-default btn-icon btn-sm\" target=\"_blank\" href=\"oxxo/generate_reference?fc=$factura->id\"><i class=\"fas fa-comment-dollar\"></i></a>*/

            $tools.="<a onclick=\"getmodal('finanzas/viewuser?action=edittransaccion&origen=pagos&id=$factura->id','Editar Transacción','sm');\" data-toggle=\"tooltip\" title=\"Editar pago\" class=\"btn btn-default btn-icon btn-sm\">
                <i class=\"far fa-edit\"></i>
              </a>";

          }
          if ($factura->estado == "No pagado") {
            $tools="";
            if ($factura->vencimiento < $fecha) {
              $estado_css = "danger";
              $factura->estado = "vencido";
            } else {
              $estado_css = "warning";
            }

            if ($permiso3 >= 2) {
              $tools .= "<a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"getmodal('finanzas/viewuser?action=paidinvoice&id=$factura->id','Agregar pago','sm');\" data-original-title=\"Agregar pago\"><i class=\"fas fa-check\"></i></a>   ";
            }
            $tools .= "
            <a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"edit_factura('$factura->id');\" data-original-title=\"Editar factura\"><i class=\"far fa-edit\"></i></a>
            <a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" target=\"_blank\" href=\"finanzas/factura_pdf?fc=$factura->id;\" data-original-title=\"Ver PDF\"><i class=\"far fa-file-pdf\"></i></a>
            <a data-toggle=\"tooltip\" title=\"Generar referencia OXXO\" class=\"btn btn-default btn-icon btn-sm\" target=\"_blank\" href=\"oxxo/generate_reference?fc=$factura->id\"><i class=\"fas fa-comment-dollar\"></i></a>";
            if ($permiso2==1) {
              $tools.="<a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"delete_invoice('$factura->id')\" data-original-title=\"Eliminar\"><i class=\"far fa-trash-alt\" aria-hidden=\"true\"></i></a>";
            }
            if ($permiso3>=3) {
              $tools.="<a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"cancel_invoice('$factura->id')\" data-original-title=\"Anular\"><i class=\"fas fa-ban\" aria-hidden=\"true\"></i></a>";
            }
          }
          ?>
          <tr>
            <td>
              <a onclick="getmodal('clientes_facturacion/ver_factura?id=<?= $factura->id; ?>','Ver Factura','lg');"><?= $factura->id; ?>
              </a>
            </td>
            <td><?= $factura->legal; ?></td>
            <td data-order="<?= strtotime($factura->emitido) ?>"><?= $factura->emitido; ?></td>
            <td data-order="<?= strtotime($factura->vencimiento) ?>"><?= $factura->vencimiento ?></td>
            <td><span class="label label-<?= $estado_css ?>"><?= strtoupper($factura->estado); ?></span></td>
            <td>MX$<?= $factura->total; ?></td>
            <td><?= $impuesto; ?></td>
            <td class="text-center"><small class="text-<?= $tipo_css ?>"><?= $factura->tipo; ?></small></td>
            <td>MX$<?= $factura->cobrado; ?></td>
            <td data-order="<?= strtotime($factura->pago) ?>"><?= $factura->pago; ?></td>
            <td>MX$0.00</td>
            <td>MX$0.00</td>
            <td>MX$0.00</td>
            <td><?= $factura->forma; ?></td>
            <td>
              <?= $tools; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
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
                <table class="table table-invoice2">
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
          <button style="display:<?= $permiso >= 2 ? "block" : "none"; ?>"  type="submit" class="btn-sm btn-azul btn-new-libre" data-loading-text="<i class='fas fa-spinner fa-spin'></i> creando..">Guardar cambios</button>
        </div>

      </form>
    </div>
  </div>
</div>
<script>
  function closep() {
    $('[data-load-sendfc]').prop("disabled", false);
    $('[data-load-sendfc]').popover('hide');
    $('[data-load-sendfc]').popover('dispose');
  }

  $(document).on('click', '[data-load-sendfc]', function(f) {
    closep();
    var ee = $(this);
    $(this).prop("disabled", true);
    $.get(ee.data('load-sendfc'), function(d) {

      ee.popover({
        content: d,
        html: true,
        width: '225px',
        placement: "left",
        title: '<span class="text-info"><strong>Enviar & Crear</strong></span>' +
          '<button type="button" id="close" class="close" onclick="closep()">&times;</button>'

      }).popover('show');
    });

    ee.on('show.bs.popover', function(r) {
      //$(this).data("bs.popover").getTipElement().css("width", "225px");
    })
  })


  function delete_invoice(id) {

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
                $('a[href="#nav-facturas"]').trigger('click');
                alerta('exito', 'Factura #' + id + ' eliminada correctamente')
              });

          }
        },
        Cancelar: {}
      }
    });


  }

  function cancel_invoice(id) {

    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Si la factura se encuentra con productos agregados estos serán removidos de la factura y serán restaurados al almacén.',
      title: '<i class="fas fa-question-circle fa-lg icodialog"></i> Anular Factura #' + id,
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt icodialog"></i> Anular', // With spaces and symbols
          action: function() {
            $.post("finanzas/viewuser", {
                id: id,
                action: "cancelinvoice"
              })
              .done(function(data) {
                $('a[href="#nav-facturas"]').trigger('click');
                alerta('exito', 'Factura #' + id + ' anulada correctamente')
              });

          }
        },
        Cancelar: {}
      }
    });

  }

  function send_invoice(id) {
    alerta('loader', 'Generando PDF...');
    $.post("ajax/viewuser", {
        id: id,
        action: "sendinvoice"
      })
      .done(function(data) {
        $('.btn-send-invoice').show();
        $('#gritter-notice-wrapper').remove();
        if (data['estado'] == 'error') {
          alerta('error', data['salida']);
        } else {
          alerta('exito', data['salida']);
        }
      });
  }

  function eliminar_pago(fc) {

    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Al eliminar esta transacción la factura <b>Nº ' + fc + '</b> pasará como estado <b>NO PAGADO</b>, si hay saldos no procesados estos serán eliminados.',
      title: '<i class="fas fa-question-circle fa-lg icodialog"></i> Eliminar transacción',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt icodialog"></i> Eliminar pago',
          action: function() {
            $.post("finanzas/viewuser", {
                factura: fc,
                action: "deletetransaccion"
              })
              .done(function(data) {
                $('a[href="#nav-facturas"]').trigger('click');
                alerta('exito', 'Pago eliminado correctamente')
              });

          }
        },
        Cancelar: {}
      }
    });


  }



  $(document).ready(function() {
    configtable('#data-tabinvoices', 'Facturas');
    var facturasclientes = $('#data-tabinvoices').DataTable({
      "idDataTables": "40",
      "columnDefs": [{
        "targets": [10, 11, 12],
        "visible": false,
      }, ],

      initComplete: function(oSettings, json) {
        if (<?= $permiso2;?>==1) {

          $("#data-tabinvoices_wrapper div.tooltablas")
            .html('<div class="grupotabla btn-group">\
        <button type="button" class="btn btn-white" Onclick="get_modal_and_close(\'clientes_facturacion/modal_new_factura_libre?id=<?= $id; ?>\',\'Nueva Factura Libre\',\'lg\');"><i class="fas fa-plus"></i> Factura Libre</button>\
      </div>');
        }
      }
    });


  })

  function get_modal_and_close(link, titulo, tamaño) {
    $("#modaltmp2").empty()
    $("#modaltmp").empty()
    $("#modaltmp4").empty()
    getmodal(link, titulo, tamaño)
  }

  function clientes_factura() {};
  //edit factura
  var facturas;

  var formEditar = document.getElementById('form-paid-edit')
  var monto = document.getElementById('monto')
  var list_item = document.getElementById('list_item')

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

  function iva2() {

    var total_imp = 0,
      total_sub = 0,
      total_t = 0;
    $(".table-invoice2 tbody>tr").each(function(k, v) {
      var cont = 0;
      var subtmp = 0;
      var valor = new Array();
      $('input.form-control', this).each(function(k, c) {
        //--->TOTAL
        if (cont > 3) {
          var txtcal = calcular($('input[name="factura\[impuesto\]"]').val(), valor[1], valor[2], valor[3]);
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
    $('.table-invoice2 tbody').append(items);
  }

  function closepop() {
    $('*[data-loadajax]').prop("disabled", false);
    $('*[data-loadajax]').popover('dispose');
  }

  $(document).on('keyup', '.table-invoice2 tbody>tr input', function() {
    iva2();
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
      }
      $('#modaltmp4').modal('hide');
      if (typeof updatefacturas == 'function') {
        $('a[href="#nav-facturas"]').trigger('click');
        updatefacturas();
      }
      if (typeof clientes_factura === 'function') {

        $('a[href="#nav-facturas"]').trigger("click");

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
          $('.table-invoice2 tbody').append(items);
        } else {
          $('.table-invoice2 tbody').html('').append(items);
        }
      }
      iva2();

      closepop()
    } else {
      des = producto.selectedOptions[0].dataset.des
      costo = producto.selectedOptions[0].dataset.costo
      idprod = producto.value
      var items = '<tr><td><input type="hidden" class="form-control" name="idproducto[]" value="0"><textarea class="form-control" placeholder="Descripción" name="descripcion[]" rows="1">' + des + '</textarea></td><td><input type="text" class="form-control" placeholder="100"  name="costo[]" value="' + costo + '"></td><td><input type="text" class="form-control" placeholder="18"  name="impuesto[]" value="' + impuesto + '"></td><td><input type="text" class="form-control" placeholder="1"  name="unidad[]" value="1"></td><td><input type="text" class="form-control" placeholder="100"  name="totales[]" disabled></td><td class="removeitem"><input value=0 type="hidden" name="iditem[]"><button type="button" class="removeritem btn btn-sm btn-default"><i class="fas fa-times"></i></button><input type="hidden" name="clave_invoice[]"></td></tr>';
      if (idprod) {
        if ($('#fctotal').val() > 0) {
          $('.table-invoice2 tbody').append(items);
        } else {
          $('.table-invoice2 tbody').html('').append(items);
        }
      }
      iva2();

      closepop()
    }
  }

  function edit_factura(e) {
    $("#modaltmp2").empty()
    $("#modaltmp").empty()
    id = e
    $.get("finanzas/consulta_items_factura", {
        id
      },
      function(data) {
        data = JSON.parse(data)
        $('#idfacturamodal').val(data[0].idfactura)
        if (data[0].monto < 0) {
          $("[name='descuento']").val(Math.abs(data[0].monto))
        } else {
          $("[name='descuento']").val(0)
        }
        $("[name='factura[emitido]']").datepicker('setDate', data[0].emitido)
        $("[name='factura[vencimiento]']").datepicker('setDate', data[0].vencimiento)
        $('input[name="factura\[otros_impuestos\]"]').val(data[0].otros_impuestos)
        $('input[name="factura\[impuesto\]"]').val(data[0].impuesto_factura)
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
        iva2();
      }
    );
  }
</script>