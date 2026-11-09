<ul class="nav nav-tabs nav-ajax">
  <li class="nav-items"><a href="#default-tab-1" data-toggle="tab" class="nav-link active show"><span class="d-sm-block d-none"><i class="far fa-credit-card"></i> Registrar pago</span><span class="d-sm-none"><i class="far fa-credit-card"></i> Registrar</span></a></li>
  <li class="nav-items"><a href="#default-tab-2" data-toggle="tab" data-url="finanzas/agregar_pago_listapagos" class="nav-link"><span class="d-sm-block d-none"><i class="fas fa-shopping-cart"></i> Pagos registrados <small>(hoy)</small></span><span class="d-sm-none"><i class="fas fa-shopping-cart"></i> Pagos</span></a></li>
  <li class="nav-items "><a href="#default-tab-3" data-toggle="tab" data-url="finanzas/agregar_pago_listapromesa" class="nav-link"><span class="d-sm-block d-none"><i class="far fa-calendar-plus"></i> Promesas de pago <small>(activos)</small></span><span class="d-sm-none"><i class="far fa-calendar-plus"></i> Promesas de pago</span></a></li>
</ul>
<div class="tab-content">
  <div class="tab-pane active show" id="default-tab-1">
    <form id="frm-addpago" action="finanzas/addpago?action=newpago" method="post">
      <div class="form-group row m-b-15 m-t-15">
        <div class="col-xl-5 p-b-5 text-right">
          <div class="radio radio-css radio-inline">
            <input type="radio" name="radio_css_inline" id="inlineCssRadio1" value="xcliente" checked="" style="cursor: pointer">
            <label for="inlineCssRadio1" style="cursor: pointer">Buscar Cliente</label>
          </div>
          <div class="radio radio-css radio-inline">
            <input type="radio" name="radio_css_inline" id="inlineCssRadio2" value="xbarra" style="cursor: pointer">
            <label for="inlineCssRadio2" style="cursor: pointer">Buscar Nº comprobante</label>
          </div>
        </div>
        <div class="col-xl-6 p-t-5">
          <input name="p-cliente" type="text" autofocus="" class="txt_n_cliente form-control ui-autocomplete-input" placeholder="Nombre ó Nº cliente ó Cédula/NIT/RUC/DNI" autocomplete="off">
          <input style="display: none;" id="barcode-cliente" type="text" autofocus="" class="form-control" placeholder="Buscar Nº Factura Ejm: 000876">
        </div>
      </div>
      <div class="salidainvoice" style="display: none"></div>
    </form>
  </div>

  <div class="tab-pane fade" id="default-tab-2">
  </div>
  <div class="tab-pane fade" id="default-tab-3">
  </div>
</div>


<script>
  if (!localStorage.tipopagooption) {
    localStorage.setItem("tipopagooption", "inlineCssRadio1");
  }

  if (localStorage.tipopagooption == 'inlineCssRadio1') {
    $('#inlineCssRadio1').prop('checked', true);
    $('#inlineCssRadio2').prop('checked', false);

    $('.txt_n_cliente').show().focus();
    $('#barcode-cliente').hide();
    localStorage.setItem("tipopagooption", "inlineCssRadio1");

  } else {
    $('#inlineCssRadio2').prop('checked', true);
    $('#inlineCssRadio1').prop('checked', false);

    $('#barcode-cliente').show().focus();
    $('.txt_n_cliente').hide();;
    localStorage.setItem("tipopagooption", "inlineCssRadio2");
    if ($('input[name="p-cliente"]').data('autocomplete')) {
      $('input[name="p-cliente"]').autocomplete("destroy");
      $('input[name="p-cliente"]').removeData('autocomplete');
    }

  }



  $('input[type=radio][name=radio_css_inline]').change(function() {
    $('#barcode-cliente,.txt_n_cliente').val('');
    if (this.value == 'xcliente') {
      $('.txt_n_cliente').show().focus();
      $('#barcode-cliente').hide();
      localStorage.setItem("tipopagooption", "inlineCssRadio1");
    } else {
      $('#barcode-cliente').show().focus();
      $('.txt_n_cliente').hide();;
      localStorage.setItem("tipopagooption", "inlineCssRadio2");
      if ($('input[name="p-cliente"]').data('autocomplete')) {
        $('input[name="p-cliente"]').autocomplete("destroy");
        $('input[name="p-cliente"]').removeData('autocomplete');
      }
    }
  });


  $('#barcode-cliente').on('keypress', function(e) {
    if (e.which === 13) {
      if ($(this).val() == "") {
        return false;
      }
      $.post("finanzas/addpago", {
          action: "getinvoice",
          id: "",
          code: $(this).val()
        })
        .done(function(data) {
          if (data) {
            $('#barcode-cliente').val('');
            $('.salidainvoice').slideUp().html(data).slideDown();
          } else {
            $('.salidainvoice').slideUp().empty();
            alerta('error', 'El Nº ingresado no pertenece a ninguna factura pendiente de pago');
          }

        });

    }
  });


  function printer(url, tipo) {
    alerta('loader', 'Preparando impresión...');
    $('#printframe').remove();
    var t = $("<iframe></iframe>");
    t.attr("id", "printframe").attr("name", "printframe").attr("src", "about:blank").css("width", "300").css("height", "100").css("position", "absolute").css("left", "-9999px").appendTo($("body:first"));
    null != t && null != url && (t.attr("src", url + "&tipo=" + tipo), t.on('load', function() {
      $('#gritter-notice-wrapper').remove();
      alerta('exito', 'Pago registrado correctamente');
      if (getChromeVersion() > 76) {
        t.get(0).contentWindow.print();
      }
    }))
  }

  function view_doc() {
    if ($('select[name="p-facturas"]').val()) {
      window.open('finanzas/factura_pdf?fc=' + $('select[name="p-facturas"]').val())
    }
  }

  $(document).ready(function() {

    $('#frm-addpago').on('submit', function(e) {
      $(".btncancelv,.btnagregapago").attr("disabled", false);

      var ee = parseFloat($('input[name="total2"]').val());
      var bb = parseFloat($('input[name="p-total"]').val());
      var saldo = ee - bb;
      $('input[name="saldos"]').val(saldo.toFixed(2));

      if (saldo > 0) {
        e.preventDefault();
        $("#cssRadio4").prop("disabled", true);
        $("#cssRadio4").prop("checked", false);
        $.confirm({
          theme: 'supervan',
          draggable: false,
          closeIcon: true,
          animationBounce: 2.5,
          escapeKey: false,
          content: 'Esta registrando un monto menor al total de la factura, si registra el pago quedará un saldo pendiente de <h5>MX$' + saldo.toFixed(2) + '</h5>',
          title: '<i class="far fa-question-circle fa-lg icodialog"></i> Saldo pendiente',
          buttons: {
            confirm: {
              text: 'Si, registrar',
              action: function() {
                $('.jconfirm-open').remove();
                btnloader('#frm-addpago');
                $('.loaderbtn').button('loading');
                $(".btncancelv").prop("disabled", true);
                $.post("finanzas/addpago?action=newpago", $('#frm-addpago').serialize(), function(data) {
                  data = JSON.parse(data);
                  $('.loaderbtn').button('reset');
                  $(".btncancelv").prop("disabled", false);
                  if (data.afip) {
                    alerta('error', 'AFIP: ' + data.afip);
                  }
                  if (data.estado == 'error') {
                    alerta('error', data.salida);
                  } else {
                    if (data.printerurl) {
                      printer(data.printerurl, data.printertipo)
                    }
                    alerta('exito', "Abono realizado con exito");
                    $('.salidainvoice').slideUp().empty();
                    if ($('.txt_n_cliente').is(":visible")) {
                      $('.txt_n_cliente').focus();
                    } else {
                      $('#barcode-cliente').focus();
                    }
                  }
                });
                return false;
              }
            },
            cancelar: function() {
              $(".btncancelv,.btnagregapago").attr("disabled", false);
              return true;
            }
          }
        });
        return false;
      } else if (saldo < 0) {
        e.preventDefault();
        $("#cssRadio4").prop("disabled", true);
        $("#cssRadio4").prop("checked", false);
        $.confirm({
          theme: 'supervan',
          draggable: false,
          closeIcon: true,
          animationBounce: 2.5,
          escapeKey: false,
          content: 'Esta registrando un monto mayor al total de la factura, si registra el pago quedará un saldo a favor del cliente  de <h5>MX$' + Math.abs(saldo).toFixed(2) + '</h5>',
          title: '<i class="far fa-question-circle fa-lg icodialog"></i> Saldo pendiente',
          buttons: {
            confirm: {
              text: 'Si, registrar',
              action: function() {
                $('.jconfirm-open').remove();
                btnloader('#frm-addpago');

                $('.loaderbtn').button('loading');
                $(".btncancelv").prop("disabled", true);
                $.post("finanzas/addpago?action=newpago", $('#frm-addpago').serialize(), function(data) {
                  $('.loaderbtn').button('reset');
                  $(".btncancelv").prop("disabled", false);
                  if (data.afip) {
                    alerta('error', 'AFIP: ' + data.afip);
                  }
                  if (data.estado == 'error') {
                    alerta('error', data.salida);
                  } else {
                    if (data.printerurl) {
                      printer(data.printerurl, data.printertipo)
                    }
                    alerta('exito', "Pago realizado con exito");
                    $('.salidainvoice').slideUp().empty();
                    if ($('.txt_n_cliente').is(":visible")) {
                      $('.txt_n_cliente').focus();
                    } else {
                      $('#barcode-cliente').focus();
                    }
                  }
                });
                return false;
              }
            },
            cancelar: function() {
              $(".btncancelv,.btnagregapago").attr("disabled", false);
              return true;
            }
          }
        });
        return false;
      }

      return true;
    })

    $('#frm-addpago').ajaxForm({
      success: function(data) {
        data = JSON.parse(data);
        btnloader('#frm-addpago', '<i class="fas fa-check"></i> Registrar pago');
        $(".btncancelv,.btnagregapago").attr("disabled", false);
        $('#gritter-notice-wrapper').remove();
        if (data.afip) {
          alerta('error', 'AFIP: ' + data['afip']);
        }
        if (data.estado == 'error') {
          alerta('error', data['salida']);
        } else {
          if (data.printerurl) {
            printer(data.printerurl, data.printertipo)
          }
          alerta('exito', "Pago realizado con exito");
          $('.salidainvoice').slideUp().empty();
          if ($('.txt_n_cliente').is(":visible")) {
            $('.txt_n_cliente').focus();
          } else {
            $('#barcode-cliente').focusssss();
          }
        }
      },
      beforeSubmit: function() {
        $(".btncancelv,.btnagregapago").prop("disabled", true);
        btnloader('#frm-addpago');
      },
      error: function(response) {
        btnloader('#frm-addpago', '<i class="fas fa-check"></i> Registrar pago');
        $('#gritter-notice-wrapper').remove();
        $(".btncancelv,.btnagregapago").attr("disabled", false);
        alerta('error500', response['responseText']);
      }
    })


    $('input[name="p-cliente"]').keyup(function() {
      $('input[name="p-cliente"]').autocomplete({
        source: 'finanzas/buscar',
        minLength: 3,
        open: function(event, ui) {
          $('img[data-name]').initial({
            height: 64,
            width: 64,
            charCount: 2,
            fontSize: 26,
            fontWeight: 600
          });
        },
        select: function(event, ui) {
          $.post("finanzas/addpago", {
              action: "getinvoice",
              id: ui.item.id
            })
            .done(function(data) {
              if (data) {
                $('.salidainvoice').slideUp().html(data).slideDown();
              } else {
                $('.salidainvoice').slideUp().empty();
                alerta('error', 'El cliente no tiene ninguna factura pendiente de pago')
              }

            });
        }
      }).data("ui-autocomplete")._renderItem = function(ul, item) {
        return $("<li></li>")
          .data("item.autocomplete", item)
          .append(
            `<a class="widget-list-item ui-menu-item-wrapper" id="ui-id-99" tabindex="-1">
            <div class="widget-list-media icon">
            <img data-name="${item.nombre}" style="border-radius: 50%" >
            </div>
            <div class="widget-list-content">
            <h4 class="widget-list-title">${item.nombre}<small style="font-size:9px">(ID.000807 - DOC ${item.cedula})</small></h4>
            <small>${item.nodo}</small>
            </div>
            </a>`
          )
          .appendTo(ul);
      };
    });

    setTimeout(function() {
      $('input[name="p-cliente"]').focus();
    }, 300);
  });

  App.restartGlobalFunction();
  App.setPageTitle('Registrar pagos');
</script>