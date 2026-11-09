<section class="content" id="bodysystem">
<div class="box box-flat box-solid">
<div class="box-header with-border">
<h3 class="box-title"><i class="fa fa-bar-chart" aria-hidden="true"></i> Estadísticas</h3>
</div>
<div class="box-body">

<div class="col-sm-6">
  
<label>Sucursal </label>
<select class="form-control" id="et-sucursal" name="et-sucursal">
<option value="%%">Todas las Sucursales</option>
<?=$option_sucursal?>
</select>

</div>
<div class="col-sm-3">
  
<label>Año</label>
<select class="form-control" id="et-ano" name="et-ano" tabindex="-1" aria-hidden="true">
<?php
$ano_actual=date("Y"); 
$i=$primer_ano;
 while ($i<=$ano_actual) {
  if($ano_actual==$primer_ano){
    echo "<option value='",$i,"'>",$i,"</option>";
    break;
  }
    else{

         echo "<option value='",$i,"'>",$i,"</option>";
    }
    

    $i++;
 
 }
?>
</select>
</div>

<div class="col-sm-12">
<p class="text-center"><strong class="ttl">RESUMEN 2018</strong></p>
<div class="chart-responsive">
<div class="chart" id="bar-chart">

</div>
</div>
</div>


<div class="col-sm-12">
<div class="tblres">

</div>
</div>


</div>
</div>

<script>
var base_url="<?=base_url();  ?>"
function resumen(sucursal,ano){
  msgbox("loader","Mensaje", "procesando...",0);
$.post( base_url+"facturas/get_estadistica", { sucursal: sucursal,action:"grafico",ano:ano})
.done(function(datas) {
toastr.clear();  
var datajson=JSON.parse(datas);
$('.notydiv').remove();
//------->tbl resumen
$('.ttl').html('RESUMEN '+ano);

$('.tblres').html('<table class="table table-condensed table-hover table-striped table-bordered table-responsive"><tbody><tr><th>Mes</th><th>Facturado</th><th>Comisión</th><th>Ingresos</th><th>Egreso</th><th>Total</th></tr></tbody>'+datajson[0].tr+datajson[1].tr+datajson[2].tr+datajson[3].tr+datajson[4].tr+datajson[5].tr+datajson[6].tr+datajson[7].tr+datajson[8].tr+datajson[9].tr+datajson[11].tr+'</table>');
//++++++++++GRafico
$('#bar-chart').empty();
var bar = new Morris.Bar({
          element: 'bar-chart',
          resize: true,
          data: [
            {y: 'Ene', a: datajson[0].fac, b: datajson[0].ing, c: datajson[0].egr},
            {y: 'Feb', a: datajson[1].fac, b: datajson[1].ing, c: datajson[1].egr},
            {y: 'Mar', a: datajson[2].fac, b: datajson[2].ing, c: datajson[2].egr},
            {y: 'Abr', a: datajson[3].fac, b: datajson[3].ing, c: datajson[3].egr},
            {y: 'May', a: datajson[4].fac, b: datajson[4].ing, c: datajson[4].egr},
            {y: 'Jun', a: datajson[5].fac, b: datajson[5].ing, c: datajson[5].egr},
            {y: 'Jul', a: datajson[6].fac, b: datajson[6].ing, c: datajson[6].egr},
            {y: 'Ago', a: datajson[7].fac, b: datajson[7].ing, c: datajson[7].egr},
            {y: 'Sep', a: datajson[8].fac, b: datajson[8].ing, c: datajson[8].egr},
            {y: 'Oct', a: datajson[9].fac, b: datajson[9].ing, c: datajson[9].egr},
            {y: 'Nov', a: datajson[10].fac, b: datajson[10].ing, c: datajson[10].egr},
            {y: 'Dic', a: datajson[11].fac, b: datajson[11].ing, c: datajson[11].egr}
          ],
          barColors: ['#3DB099','#3E4BB0','#B03E77'],
          xkey: 'y',
          ykeys: ['a','b','c'],
      yLabelFormat:
  function (y) { 
  return '$' +y.toString();
   },
          labels: ['Facturado', 'Ingresos', 'Egresos'],
      xLabelAngle: 90,
          hideHover: 'auto'
        });
//+++++++++++++++++
})
}

$(function(){
$('#et-ano').val("<?=date('Y');?>").trigger('change.select2');

resumen($('#et-sucursal').val(),$('#et-ano').val());

$("#et-sucursal,#et-ano").change(function() {
resumen($('#et-sucursal').val(),$('#et-ano').val());
});

$('#et-sucursal,#et-ano').select2();




})
</script></section>