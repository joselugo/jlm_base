<div class="panel panel-inverse panel-operaciones">
  <div class="panel-heading">
    <h4 class="panel-title">Transacciones</h4>
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fas fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onClick="updatepagos()"><i class="fas fa-sync-alt"></i></a>
    </div>

  </div>
  <div class="panel-body">

    <form class="row form-horizontal m-b-15" id="frmoperaciones" method="post" action="finanzas/viewreporte_transacciones" target="_blank">

      <div class="col-sm-6">
        <div class="form-group row">
          <label class="col-sm-3 control-label">Tipo Pago</label>
          <div class="col-sm-9">
            <select class="form-control filtroop sl2" id="forma_pago" name="forma_pago" style="width: 100%">
              <option value="todos">Cualquiera</option>
              <?= $pasarelas; ?>
              <option value="Mercadopago">Mercadopago</option>
              <option value="Siro">Siro (Banco Roela)</option>
              <option value="BancoEstado">Banco estado - Caja vecina</option>
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
            </select>
          </div>
        </div>
      </div>

      <div class="col-sm-6">
        <div class="form-group row">
          <label class="col-sm-3 control-label">Operador</label>
          <div class="col-sm-9">
            <select class="form-control filtroop sl2" id="operador" name="operador" style="width:100%">
              <option value="0">Cualquiera</option>
              <?= $operadores; ?>
            </select>
          </div>
        </div>
      </div>

      <div class="col-sm-6">
        <div class="form-group row">
          <label class="col-sm-3 control-label">Fechas</label>
          <div class="col-sm-9">

            <div class="input-group input-daterange">
              <input type="text" class="form-control" id="desde" name="start" value="<?= $primer_dia; ?>" readonly>
              <span class="input-group-addon">al</span>
              <input type="text" class="form-control" id="hasta" name="end" value="<?= $ultimo_dia; ?>" readonly>
            </div>

          </div>
        </div>
      </div>

    
      <div class="col-sm-6">
        <div class="form-group row">
          <label class="col-sm-3 control-label">Horario</label>
          <div class="col-sm-9 row">
            <div class="col-md-6">
              <div class="input-group datefiltro">
                <input type="text" class="cron form-control" id="hora_desde" name="hora_desde" value="00:00:00">
                <span class="input-group-addon"><i class="far fa-clock"></i>
                </span>
              </div>
              <small class="text-center d-block" style="width: 100%">DESDE</small>
            </div>

            <div class="col-md-6">
              <div class="input-group datefiltro">
                <input type="text" class="cron form-control" id="hora_hasta" name="hora_hasta" value="23:59:59">
                <span class="input-group-addon"><i class="far fa-clock"></i>
                </span>
              </div>
              <small class="text-center d-block">HASTA</small>
            </div>

          </div>
        </div>
      </div>

    </form>
    <div class="col-md-12">
      <table id="list-pago-cliente" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" data-order="[[ 0, &quot;desc&quot; ]]">
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
            <th>Cédula</th>
            <th>Descripción</th>
            <th>Detalle pago</th>
            <th class="all" data-orderable="false" style="min-width:30px !important;max-width:30px !important; width:30px !important">Acción</th>
          </tr>
        </thead>


        <tfoot>
          <tr>
            <th class="not-search-input"></th>
            <th class="all"></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th class="all"></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th class="all not-search-input"></th>
          </tr>
        </tfoot>



      </table>

      <div class="col-xl-8 p-0 m-t-20 resumen_transacciones" style="margin: 0 auto;"></div>
    </div>


  </div>
</div>


<style type="text/css">
  .popover-content {
    padding: 12px;
    min-width: unset;
  }
</style>

<script>
  var footer = '<table  class="table table-bordered text-center">' +
    '<thead><tr><th>TOTAL COBRADO</th><th>TOTAL COMISION</th><th>TOTAL NETO</th></tr>	</thead>' +
    '<tbody><tr>' +
    '<td class="tdcobrado" style="font-weight: 600; font-size: 13px">0</td>' +
    '<td class="tdcomision" style="font-weight: 600; font-size: 13px">0</td>' +
    '<td class="tdneto" style="font-weight: 600; font-size: 13px">0</td></tr></tbody>	</table>';

  function reporte_operaciones() {
    $('#frmoperaciones').submit();
  }

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
                console.log(data);
                if (data == "1") {
                  updatepagos();
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

  var transacciones, sloperador, slnodo, cobrado2 = 0,
    neto2 = 0,
    comision2 = 0;

  function loadSettingsop() {
    $('#forma_pago option[value="' + localStorage.op_forma + '"]').prop('selected', true);
    $('#operador option[value="' + localStorage.op_operador + '"]').prop('selected', true);
    $('#nodo option[value="' + localStorage.op_router + '"]').prop('selected', true);
    $('#desde').val(localStorage.op_desde);
    $('#hasta').val(localStorage.op_hasta);
  }

  function saveSettingsop() {
    localStorage.op_forma = $('#forma_pago').val();
    localStorage.op_operador = $('#operador').val();
    localStorage.op_desde = $('#desde').val();
    localStorage.op_hasta = $('#hasta').val();
    localStorage.op_router = $('#nodo').val();
  }

  function reset_operaciones() {
    $('.sl2').select2('destroy');
    $('#forma_pago option[value=""]').prop('selected', true);
    $('#desde').val('01/09/2020');
    $('#hasta').val('30/09/2020');
    slnodo.val("0").trigger("change");
    sloperador.val("0").trigger("change");
    $('.sl2').select2('destroy');
  }

  if (localStorage.op_desde) {
    loadSettingsop();
  }

  $(document).ready(function() {

    $('#hora_desde').datetimepicker({
      format: 'LTS',
    })

    $('#hora_hasta').datetimepicker({
      format: 'LTS',
    })

    $('#hora_desde,#hora_hasta').on("dp.change", function(e) {
      transacciones.ajax.url("finanzas/get_transacciones_json?nodo=" + $('#nodo').val() + '&forma_pago=' + $('#forma_pago').val() + '&operador=' + $('#operador').val() + '&desde=' + $('#desde').val() + '&hasta=' + $('#hasta').val() + '&hora_desde=' + $('#hora_desde').val() + '&hora_hasta=' + $('#hora_hasta').val()).load();
    });


    $('.sl2').select2();

    $(document).on('click', '[data-load-printer]', function(f) {
      closep();
      var e = $(this);
      $(this).prop("disabled", true);
      $.get(e.data('load-printer'), function(d) {

        e.popover({
          content: d,
          container: '.panel-operaciones',
          html: true,
          width: '140px',
          placement: "left",
          title: '<span class="text-info"><strong>Imprimir</strong></span>' +
            '<button type="button" id="close" class="close" onclick="closep()">&times;</button>'

        }).popover('show');
      });

      e.on('show.bs.popover', function(r) {
        //$(this).data("bs.popover").tip().css("width", "160px");
      })
    })


    configtable('#list-pago-cliente', 'Transacciones');


    transacciones = $('#list-pago-cliente').DataTable({
      "ajax": "finanzas/get_transacciones_json?nodo=" + $('#nodo').val() + '&forma_pago=' + $('#forma_pago').val() + '&operador=' + $('#operador').val() + '&desde=' + $('#desde').val() + '&hasta=' + $('#hasta').val() + '&hora_desde=' + $('#hora_desde').val() + '&hora_hasta=' + $('#hora_hasta').val(),


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
          mData: 'cedula',
          "visible": false
        },
        {
          mData: 'descripcion',
          "visible": false
        },
        {
          mData: 'detalle',
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
          cobrado2 += parseFloat(value.cobrado);
          neto2 += parseFloat(value.cobrado);
          comision2 += parseFloat(value.comision);
        });
        console.log("ese :" + cobrado2);
        var moneda = "MX$ ";
        $('.resumen_transacciones').html(footer);
        $('.tdcobrado').html(moneda + cobrado2.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
        $('.tdcomision').html(moneda + comision2.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));
        $('.tdneto').html(moneda + neto2.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,'));

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

        //------->Button Col
        $("#list-pago-cliente_wrapper div.tooltablas")
          .html('<div class="grupotabla btn-group">\
  		<button class="btn btn-default" onclick="reporte_operaciones()"><i class="far fa-file-pdf"></i> Resumen PDF </button>\
		</div>');
      }
    }).on('processing.dt', function(e, settings, processing) {
      if (processing) {
        loaderin('.panel-operaciones');
      } else {
        loaderout('.panel-operaciones');
      }
    }).on('draw', function(e, d) {
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
    });


    updatepagos = function() {
      transacciones.ajax.reload(null, false);
    }

    //--------> Filtro 
    $(".filtroop").change(function() {
      transacciones.ajax.url("finanzas/get_transacciones_json?nodo=" + $('#nodo').val() + '&forma_pago=' + $('#forma_pago').val() + '&operador=' + $('#operador').val() + '&desde=' + $('#desde').val() + '&hasta=' + $('#hasta').val() + '&hora_desde=' + $('#hora_desde').val() + '&hora_hasta=' + $('#hora_hasta').val()).load();
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
      saveSettingsop();
    });

    //--->Fechas
    $('.input-daterange').datepicker({
      language: "es",
      toggleActive: true,
      todayHighlight: true,
      autoclose: true
    }).on('changeDate', function(ev) {
      transacciones.ajax.url("finanzas/get_transacciones_json?nodo=" + $('#nodo').val() + '&forma_pago=' + $('#forma_pago').val() + '&operador=' + $('#operador').val() + '&desde=' + $('#desde').val() + '&hasta=' + $('#hasta').val() + '&hora_desde=' + $('#hora_desde').val() + '&hora_hasta=' + $('#hora_hasta').val()).load();
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
      saveSettingsop();
    });


    App.restartGlobalFunction();
    App.setPageTitle('Transacciones');


  });

  function transacciones_page() {

  }
</script>