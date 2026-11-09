<div class="row">

  <div class="col-lg-12 form-horizontal">

    <div class="row form-group m-b-10">
      <label class="col-md-2 col-form-label">Servicio</label>
      <div class="col-md-5 m-b-10">
        <select id="services_user" class="slestadisticas form-control" style="width: 60%">
          <option value="0">Todos los servicios</option>
          <option value="3624">SIEMPRE NETIUM W + 10 - 172.29.34.53 (ONLINE)</option>
        </select>
        <div style="float:right;width: 150px">
          <select class="sl2" style="width: 100%" id="tipografico">
            <option value="0">Gráfico diario</option>
            <option value="1">Gráfico mensual</option>
          </select>
        </div>
      </div>
      <div class="col-md-5 dvrange">
        <div class="trafico input-group input-daterange"><input style="min-width: 95px;max-width: 95px;" type="text"
            class="form-control" name="start" readonly="" placeholder="fecha inicio" value="<?= $primer_dia; ?>"><span
            class="input-group-addon">al</span><input style="min-width: 95px;max-width: 95px;" type="text"
            class="form-control" name="end" readonly="" placeholder="fecha final" value="<?= $ultimo_dia; ?>"></div>
      </div>

    </div>


  </div>

  <div class="col-lg-3">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title"><b><i class="fas fa-info-circle"></i> Resumen</b></h4>
      </div>
      <div class="panel-body border-panel p-5">
        <div class="list-group">
          <a class="list-group-item">Sesiones<span class="pull-right" data-flow="sesiones">0</span></a>

          <a class="list-group-item">Descarga<span class="pull-right" data-flow="descarga">0 B</span></a>
          <a class="list-group-item">Subida<span class="pull-right" data-flow="subida">0 B</span></a>
          <!--     <a class="list-group-item text-center"><button type="button" class="btn btn-success"
              onclick="getmodal('ajax/viewuser?action=viewpages&id=3','Sitios visitados','lg');"><i
                class="fas fa-globe-americas"></i> Sitios visitados Hoy</button></a> -->
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-9">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title"><b><i class="far fa-chart-bar"></i> Gráfico</b></h4>
      </div>
      <div class="panel-body border-panel p-0" id="grafico-flow" style="height: 300px">

      </div>
    </div>
  </div>

</div>

<div id="ocultar" class="col-lg-12">
  <table id="data-trafico-flow" class="display nowrap table table-striped table-bordered" cellspacing="0" width="100%"
    data-order="[[ 1, &quot;desc&quot; ]]">
    <thead>
      <tr>
        <th>#</th>
        <th>Conectado</th>
        <th>Descarga</th>
        <th>Subida</th>
        <th>Server</th>
        <th>Mac</th>
        <th>Ip moden</th>
      </tr>
    </thead>
  </table>
</div>

<style type="text/css">
.select2-results__option {
  font-size: 11px;
}

.select2-container .select2-selection--single .select2-selection__rendered,
.select2-container .select2-selection--single {
  font-size: 11px !important;
}

</style>
<script>
var traficoflow;

$('#tipografico').change(function() {

  if ($(this).val() == 0) {
    $('#ocultar').show();
    $('.dvrange').show();
  } else {
    $('#ocultar').hide();
    $('.dvrange').hide();
  }
  $('#services_user').trigger('change');
})

$('#services_user').change(function() {
  $.post("estadisticas/datos_trafico", {
      id: $(this).val(),
      idcliente: "<?= $idcliente; ?>",
      inicio: $('input[name="start"]').val(),
      final: $('input[name="end"]').val(),
      tipo: $('#tipografico').val()
    })
    .done(function(data) {      
	data = JSON.parse(data);
        if ($.fn.DataTable.isDataTable('#data-trafico-flow')) {
        traficoflow.ajax.url("estadisticas/get_datos_json?servicio=" + $('#services_user').val() +
            "&idcliente=<?= $idcliente; ?>&inicio=" + $('input[name="start"]').val() + "&final=" + $(
              'input[name="end"]').val())
          .load();
      } else {
        loadtableflow();
      }


      $("[data-flow='sesiones']").html(data.data.sesiones)
      $("[data-flow='descarga']").html(data.data.descarga)
      $("[data-flow='subida']").html(data.data.subida)
      $('#grafico-flow').empty();
      Morris.Bar({
        element: 'grafico-flow',
        data: data.data.grafico,
        xLabelAngle: '30',
        resize: true,
        stacked: true,
        barColors: ['#348fe2', '#00ACAC'],
        gridTextSize: 11,
        hoverCallback: function(index, options, content, row) {
          if (row.descarga > 1024) {
            var down = roundto(row.descarga / 1024) + ' GiB';
          } else {
            var down = row.descarga.toFixed(2) + ' MiB';
          }
          if (row.subida > 1024) {
            var up = roundto(row.subida / 1024) + ' GiB';
          } else {
            var up = row.subida.toFixed(2) + ' MiB';
          }
          return row.fecha + '<br><div style="font-size:10px;color:#348fe2;">DOWN: ' + down +
            '<br></div><div style="font-size:10px;color:#00ACAC">UP: ' + up + '</div>';
        },
        yLabelFormat: function(y) {
          if (y > 1024) {
            return roundto(y / 1024) + ' GiB';
          } else {
            return y.toString() + ' MiB';
          }

        },
        xkey: 'fecha',
        ykeys: ['descarga', 'subida'],
        labels: ['Descarga', 'Subida']
      });

    });

});

$('.slestadisticas,.sl2').select2();


function loadtableflow() {
  configtable('#data-router', 'Tráfico');
  traficoflow = $('#data-trafico-flow').DataTable({
    "ajax": "estadisticas/get_datos_json?servicio=0&idcliente=<?= $idcliente; ?>&inicio=<?= $primer_dia; ?>&final=<?= $ultimo_dia; ?>",
    "deferRender": true,
    "idDataTables": "40",
    "aoColumns": [{
        mData: 'id',
        sWidth: "10px"
      },
      {
        mData: 'conectado'
      },
      {
        mData: 'descarga'
      },
      {
        mData: 'subida'
      },
      {
        mData: 'ipv4'
      },
      {
        mData: 'mac'
      },
      {
        mData: 'router'
      }
    ],
  });
}

$('#services_user').trigger('change');


$('.trafico.input-daterange').datepicker({
  todayHighlight: true,
  autoclose: true,
  language: "es"
}).on('changeDate', function(ev) {
  $('#services_user').trigger('change');
});
</script>
