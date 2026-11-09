	<div class="modal-body form-horizontal" style="width: 100%">

	  <div class="panel panel-inverse m-b-0" data-sortable-id="ui-widget-1">
	    <div class="panel-heading" style="padding: 5px 12px;">
	      <h4 class="panel-title">Datos directos de mikrotik</h4>
	      <div class="panel-heading-btn">
	      </div>

	    </div>

	  </div>



	  <div id="main-graph" class="border" style="height: 220px"><i class="fas fa-spinner fa-spin fa-2x fa-fw"></i>
	    Conectando con router mikrotik...</div>

	  <table id="grid-torch" class="table table-condensed table-bordered" style="display:none">
	    <thead>
	      <tr>
	        <th style="background: #f5f5f5;">Fecha</th>
	        <th style="background: #f5f5f5;">Ip</th>
	        <th style="background: #f5f5f5;">Plan contratado</th>
	        <th style="background: #f5f5f5;">TX(Mbps)</th>
	        <th style="background: #f5f5f5;">RX(Mbps)</th>
	        <!--  <th style="background: #f5f5f5;">TX packet</th>
	        <th style="background: #f5f5f5;">RX packet</th> -->
	      </tr>
	    </thead>
	    <tbody id="tablaDatos">

	    </tbody>
	  </table>


	</div>

	<script>
var interval;
var tmpdata = [];
var contador = 0;
var tdcount = 0;
body = document.getElementById('tablaDatos');

function renderLiveTempGraph() {
  var mainGraph;

  $.post("herramientas_mikrotik/monitor_traffic", {
      servicio: '<?= $servicio; ?>'
    })
    .done(function(results) {
      results = JSON.parse(results);
      $('#grid-torch tbody').html(results.tabla);
      console.log(results.tabla);
      $('#grid-torch').show();
      $('.btncolla').trigger('click');
      $('#main-graph').html('');
      var temp = {
        'x': results.fecha,
        'descarga': results.descarga,
        'subida': results.subida
      };
      tmpdata.push(temp);
      mainGraph = Morris.Line({
        element: 'main-graph',
        data: tmpdata,

        xkey: 'x',
        ykeys: ['descarga', 'subida'],
        grid: true,
        postUnits: ' Mbps',
        lineColors: ['#199cef', '#ffd34c'],
        labels: ['Descarga', 'Subida'],
        lineWidth: 1,
        pointSize: 3,
        resize: true
      });
      interval = setInterval(function() {
        updateLiveTempGraph(mainGraph);
      }, 1000);
    });

}

renderLiveTempGraph();

function updateLiveTempGraph(mainGraph) {
  $.post("herramientas_mikrotik/monitor_traffic", {
      servicio: '<?= $servicio; ?>'
    })
    .done(function(results) {
      results = JSON.parse(results);
      if (tmpdata.length > 10) {
        tmpdata.shift();
      }
      console.log(contador);
      if (contador >= 11) {
        body.firstChild.remove();
      }
      contador++
      body.innerHTML += (results.tabla);

      var temp = {
        'x': results.fecha,
        'descarga': results.descarga,
        'subida': results.subida
      };
      tmpdata.push(temp);
      mainGraph.setData(tmpdata);
    });
}


//interval=setInterval(gettorch,4000);

function gettorch() {

  $.post("ajax/usuarios", {
      servicio: '<?= $servicio; ?>'
    })
    .done(function(data) {
      $('#grid-torch tbody').html(data['salida']);
      $('#grid-torch').show();
    });
}

$(function() {
  closeall();
  $('.tooltip').remove();
  $('#modaltmp').modal('show');
  $(".Select2").select2();

  $('#modaltmp').on('hidden.bs.modal', function(e) {
    clearTimeout(interval);
    clearInterval(interval);
  })


})
	</script>
