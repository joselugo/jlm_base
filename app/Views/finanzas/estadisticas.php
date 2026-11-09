<div class="panel panel-blue panel-sms-est">
  <div class="panel-heading">
    <h4 class="panel-title">RESUMEN DE TRANSACCIONES</h4>
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fas fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onClick="update_estadisticas()"><i class="fas fa-sync-alt"></i></a>
    </div>

  </div>
  <div class="panel-body row">

    <div class="col-sm-6">

      <label>Sucursal </label>
      <select class="form-control sle" id="sucursal" name="sucursal" onChange="update_estadisticas()" style="width:100%">
        <option value="0">Todas las sucursales</option>
        <?= $sucursales; ?>
      </select>
    </div>
    <div class="col-sm-3">

      <label>Año</label>
      <select class="form-control sle" id="et-ano" name="et-ano" onChange="update_estadisticas()" style="width:100%">
        <?= $anios; ?>
      </select>
    </div>

    <div class="col-sm-12">
      <div class="chart-responsive">
        <div class="chart" id="bar-chart" style="height: 280px;"></div>
      </div>
    </div>

    <div class="col-sm-12" style="margin-top:10px">
      <table id="data-resumen-pagos" class="display nowrap table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th data-orderable="false">#</th>
            <th data-orderable="false">Mes</th>
            <th data-orderable="false">Cobrado</th>
            <th data-orderable="false">Comisión</th>
            <th data-orderable="false">Ingresos</th>
            <th data-orderable="false">Egresos</th>
            <th data-orderable="false">Total Op.</th>
            <th data-orderable="false">Bruto</th>
            <th data-orderable="false">Neto</th>
          </tr>
        </thead>
      </table>
    </div>

  </div>
</div>

<script>
  var estd;
  var bar;

  update_estadisticas = function() {
    estd.ajax.url("finanzas/pagos_estadisticas?sucursal=" + $('#sucursal').val() + "&ano=" + $('#et-ano').val()).load();
  }

  $(function() {
    $('.sle').select2();
    App.setPageTitle('RESUMEN OPERACIONES');
    bar = new Morris.Bar({
      element: 'bar-chart',
      resize: true,
      data: [{
          y: 'Ene',
          a: 0,
          b: 0,
          c: 0
        },
        {
          y: 'Feb',
          a: 0,
          b: 0,
          c: 0
        },
        {
          y: 'Mar',
          a: 0,
          b: 0,
          c: 0
        },
        {
          y: 'Abr',
          a: 0,
          b: 0,
          c: 0
        },
        {
          y: 'May',
          a: 0,
          b: 0,
          c: 0
        },
        {
          y: 'Jun',
          a: 0,
          b: 0,
          c: 0
        },
        {
          y: 'Jul',
          a: 0,
          b: 0,
          c: 0
        },
        {
          y: 'Ago',
          a: 0,
          b: 0,
          c: 0
        },
        {
          y: 'Sep',
          a: 0,
          b: 0,
          c: 0
        },
        {
          y: 'Oct',
          a: 0,
          b: 0,
          c: 0
        },
        {
          y: 'Nov',
          a: 0,
          b: 0,
          c: 0
        },
        {
          y: 'Dic',
          a: 0,
          b: 0,
          c: 0
        }
      ],
      barColors: ['#3F88D1', '#F4B851', '#EF4D30'],
      xkey: 'y',
      ykeys: ['a', 'b', 'c'],
      yLabelFormat: function(y) {
        return 'MXN ' + y.toString();
      },
      labels: ['Neto', 'Ingresos', 'Egresos'],
      xLabelAngle: 90,
      hideHover: 'auto'
    });

    configtable('#data-resumen-pagos', 'Resumen operaciones');
    estd = $('#data-resumen-pagos').DataTable({
      "ajax": "finanzas/pagos_estadisticas?sucursal=" + $('#sucursal').val() + "&ano=" + $('#et-ano').val(),
      "deferRender": true,
      "idDataTables": "40",
      "aoColumns": [{
          mData: 'id',
          sWidth: "10px"
        },
        {
          mData: 'mes'
        },
        {
          mData: 'cobrado'
        },
        {
          mData: 'comision'
        },
        {
          mData: 'ingreso'
        },
        {
          mData: 'egreso'
        },
        {
          mData: 'operaciones'
        },
        {
          mData: 'bruto'
        },
        {
          mData: 'neto'
        },
      ]
    }).on('processing.dt', function(e, settings, processing) {
      if (processing) {
        loaderin('.panel-sms-est');
      } else {
        loaderout('.panel-sms-est');
        var tmpchart = [];

        estd.rows().every(function(rowIdx, tableLoop, rowLoop) {
          console.log(this.data());
          tmpchart.push({
            'y': this.data().mes,
            'a': this.data().gneto,
            'b': this.data().in,
            'c': this.data().eg
          });
        });
        bar.setData(tmpchart);
      }
    }).on('draw', function() {
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
    });

  })
</script>