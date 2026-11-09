<form class="row form-horizontal m-b-15" id="frmoperaciones" method="post" action="finanzas/viewreporte?action=transacciones" target="new">

  <div class="col-sm-6">
    <div class="form-group row">
      <label class="col-sm-3 control-label">Tipo Pago</label>
      <div class="col-sm-9">
        <select onchange="updateoperaciones()" class="form-control filtroop sl2" id="forma_pago" name="forma_pago" style="width: 100%">
          <option value="0">Cualquiera</option>
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
    </div>
  </div>

  <div class="col-sm-6">
    <div class="form-group row">
      <label class="col-sm-3 control-label">Operador</label>
      <div class="col-sm-9">
        <select onchange="updateoperaciones()" class="form-control filtroop sl2" id="operador" name="operador" style="width:100%">
          <option value="0">Cualquiera</option>
          <?= $options_operadores ?>
        </select>
      </div>
    </div>
  </div>

  <div class="col-sm-6 hidden">
    <div class="form-group row">
      <label class="col-sm-3 control-label">Fechas</label>
      <div class="col-sm-9">

        <div class="input-group input-daterange">
          <input type="text" class="form-control" id="desde" name="start" value="<?= $fecha_hoy ?>" readonly>
          <span class="input-group-addon">al</span>
          <input type="text" class="form-control" id="hasta" name="end" value="<?= $fecha_hoy ?>" readonly>
        </div>

      </div>
    </div>
  </div>

</form>

<table id="list-pago-cliente-hoy" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" data-order="[[ 0, &quot;desc&quot; ]]">
  <thead>
    <tr>
      <th>ID</th>
      <th class="all">Cliente</th>
      <th># Factura</th>
      <th># Legal</th>
      <th># Transacción</th>
      <th>Forma de Pago</th>
      <th>Fecha & Hora</th>
      <th>Meses</th>
      <th>Operador</th>
      <th class="all">Cobrado</th>
      <th>Comision</th>
      <th>Neto</th>
      <th>Descripción</th>
      <th class="all" data-orderable="false" style="min-width:30px !important;max-width:30px !important; width:30px !important">Acción</th>
    </tr>
  </thead>
</table>

<style type="text/css">
  .popover-content {
    padding: 12px;
    min-width: unset;
  }
</style>
<script>
  var footer = '<div class="col-xl-8 p-0" style="margin: 0 auto;">' +
    '<table  class="table table-bordered text-center">' +
    '<thead><tr><th>TOTAL COBRADO</th><th>TOTAL COMISION</th><th>TOTAL NETO</th></tr>	</thead>' +
    '<tbody><tr>' +
    '<td class="tdcobrado" style="font-weight: 600; font-size: 13px">0</td>' +
    '<td class="tdcomision" style="font-weight: 600; font-size: 13px">0</td>' +
    '<td class="tdneto" style="font-weight: 600; font-size: 13px">0</td></tr></tbody>	</table></div>';

  var transaccioneshoy, cobrado2 = 0,
    neto2 = 0,
    comision2 = 0;;

  function delete_paid(e) {
    id = e.dataset.idoperacion
    fc = e.dataset.idfactura
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
                id: id,
                factura: fc,
                action: "deletetransaccion"
              })
              .done(function(data) {
                if (data == "1") {
                  $('#content .nav-link.active').trigger('click');
                  alerta('exito', 'Pago eliminado correctamente,La Factura <b>Nº ' + fc + '</b> pasó a estado No pagado')
                }
                if (data == "2") {
                  alerta('error', 'Ocurrio un error, vuelva a intentarlo')
                }
              });

          }
        },
        Cancelar: {}
      }
    });


  }

  function closep() {
    $('[data-load-printer]').prop("disabled", false);
    $('[data-load-printer]').popover('hide');
    $('[data-load-printer]').popover('dispose');
  }

  $(function() {

    $(document).on('click', '[data-load-printer]', function(f) {
      closep();
      var e = $(this);
      $(this).prop("disabled", true);
      $.get(e.data('load-printer'), function(d) {

        e.popover({
          content: d,
          container: '#frmoperaciones',
          html: true,
          width: '140px',
          placement: "left",
          title: '<span class="text-info"><strong>Imprimir</strong></span>' +
            '<button type="button" id="close" class="close" onclick="closep()">&times;</button>'

        }).popover('show');
      });

      e.on('show.bs.popover', function(r) {
        ///$(this).data("bs.popover").tip().css("width", "160px");
      })
    })

    configtable('#list-pago-cliente-hoy', 'Transacciones');

    transaccioneshoy = $('#list-pago-cliente-hoy').DataTable({
      "ajax": "finanzas/transacciones?action=lista&nodo=" + $('#nodo').val() + '&forma_pago=' + $('#forma_pago').val() + '&operador=' + $('#operador').val() + '&desde=' + $('#desde').val() + '&hasta=' + $('#hasta').val(),
      "deferRender": true,
      "idDataTables": "40",
      //"bAutoWidth": true,
      "aoColumns": [{
          mData: 'id',
          sWidth: "20px"
        },
        {
          mData: 'nombre'
        },
        {
          mData: 'nfactura'
        },
        {
          mData: 'legal'
        },
        {
          mData: 'transaccion'
        },
        {
          mData: 'forma_pago',
          "visible": false
        },
        {
          mData: 'fecha_pago'
        },
        {
          mData: 'meses',
          "visible": false
        },
        {
          mData: 'operador',
          "visible": false
        },
        {
          mData: 'cobrado',
          "render": function(data, type, row) {
            return 'MX$' + data
          },
        },
        {
          mData: 'comision',
          "render": function(data, type, row) {
            return 'MX$' + data
          },
          "visible": false
        },
        {
          mData: 'cobrado',
          "render": function(data, type, row) {
            return 'MX$' + data
          },
          "visible": false
        },
        {
          mData: 'descripcion',
          "visible": false
        },
        {
          mData: 'tool',
          sClass: 'text-center',
          sWidth: "40px"
        }
      ],

      "drawCallback": function(settings) {
        cobrado2 = 0;
        neto2 = 0;
        comision2 = 0;
        var api = this.api().rows({
          search: 'applied'
        }).data();
        $.each(api, function(key, value) {

          cobrado2 += parseFloat(value['cobrado']);
          neto2 += parseFloat(value['cobrado']); //neto
          comision2 += parseFloat(value['comision']);
        });

        var moneda = "MXN ";
        $('#list-pago-cliente-hoy_info').prepend(footer);
        $('.tdcobrado').html(moneda + cobrado2.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
        $('.tdcomision').html(moneda + comision2.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
        $('.tdneto').html(moneda + neto2.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

      },
      initComplete: function(oSettings, json) {

        //------->Button Col
        $("#list-pago-cliente-hoy_wrapper div.tooltablas")
          .html('<div class="grupotabla btn-group">\
  		<button class="btn btn-default" onclick="reporte_operaciones()"><i class="far fa-file-pdf"></i> Resumen PDF </button>\
		</div>');

      }
    }).on('draw', function() {
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
    });


  })
  $('#frmoperaciones .sl2').select2();

  function reporte_operaciones() {
    $('#frmoperaciones').submit();
  }

  function updateoperaciones() {
    transaccioneshoy.ajax.url("finanzas/transacciones?action=lista&nodo=" + $('#nodo').val() + '&forma_pago=' + $('#forma_pago').val() + '&operador=' + $('#operador').val() + '&desde=' + $('#desde').val() + '&hasta=' + $('#hasta').val()).load()
  }
</script>