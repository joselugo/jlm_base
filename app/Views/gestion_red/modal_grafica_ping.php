<div class="modal-body">
<div class="text-center" style="margin-bottom:10px;">
  <select id="ping_grafico_tipo_cri" style="width:200px !important" onchange="get_grafico()">
    <option value="hora" selected="selected">Gráfico última hora</option>
    <option value="dia">Gráfico del día</option>
    <option value="semana">Gráfico de la semana</option>
    <option value="mes">Gráfico del mes</option>
    <option value="año">Gráfico del año</option>
  </select>
</div>

<input id="id_ping_chart_grafica" type="hidden" value="<?= $idemisor; ?>">
<div class="text-center" >
<img src="public/images/graficas/espera.gif" id="ping_grafico_img" style="width:100%;max-width:280px">
</div>
<script>
$(document).ready(function() {
    $('#ping_grafico_tipo_cri').select2();
});
function get_grafico() {
$("#ping_grafico_img").css({"width":"100%","max-width":"280px"})
$("#ping_grafico_img").attr("src","public/images/graficas/espera.gif");
get_imagen();
}
get_imagen();
$('#modaltmp').modal('show');
function get_imagen(){
  $('.modal-title').text('Grafica por ' +$("#ping_grafico_tipo_cri").val())
  $.get('gestion_red/generar_grafica?idemisor=' + $('#id_ping_chart_grafica').val() + '&tipo=' + $("#ping_grafico_tipo_cri").val(),
  function(data) {
    if (data) {
      $("#ping_grafico_img").attr(
        "src",
        "public/images/graficas/latency_graph.png?random=" + Math.floor(Math.random() * 100 + 1)
      );
    $("#ping_grafico_img").css({"width":"100%","max-width":"780px"})
    }
  });
}
</script>
</div>
