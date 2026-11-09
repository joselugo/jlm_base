<div class="panel panel-blue panel-sindicatos">
<div class="panel-heading">
<h4 class="panel-title">Balance general</h4>
<div class="panel-heading-btn">
<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fas fa-expand"></i></a>
<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onclick="consulta()"><i class="fas fa-sync-alt"></i></a>
</div>
</div>
<div class="panel-body">
<form  id="frmoperaciones" method="post" onsubmit="consulta(this,event);">
  <div class="row"> 
<label class="col-sm-12">Fechas</label>
<div class="col-sm-3">
<input type="text" class="form-control" id="desde" name="desde" readonly="" value="<?=$fecha_inicio?>">
<small>Desde</small>

</div>
<div class="col-sm-3">
<input type="text" class="form-control" id="hasta" name="hasta" readonly="" value="<?=$fecha_fin?>">
<small>Hasta</small>

</div>
 <div class="col-sm-4">
     <button type="submit" class="btn btn-primary" style=" margin-right: 10px;">Generar reporte</button>
   </div>


</div>

<div class="row">
  <div class="col-sm-12" id="tabla">
   
  <br>  
 <table width="100%" border="0">
  <thead>
    <tr style="color: white">
     <th style="background-color: #70ad47; width: 30%">INGRESOS</th>
     <th style="background-color: #ed7d31; width: 40%">EGRESOS</th>
     <th style="background-color: red; width: 30%">UTILIDADES</th>
    </tr>
  </thead>
  <tbody id="content_table">
     <?=$content_table ?>
  </tbody>
  
 </table>

  </div>
</div>

</form>
  

</div>
</div>

       
      

<div class="modal fade in" id="modal-gastos">
 <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
<form id="frm-edit-factura">
<input type="hidden" name="id" id="id">
<div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Lista gastos</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button></div>
<div class="modal-body">

<div class="row">
  <div class="col-sm-12" id="tabla">
   
  <br>  
 <table width="100%" border="0">
  <thead>
    <tr style="color: white">
     <th style="background-color: red; width: 10%">ID</th>
     <th style="background-color: #f39c12; width: 60%">REFERENCIA</th>
     <th style="background-color: red; width: 30%">MONTO</th>
    </tr>
  </thead>
  <tbody id="content_table_tipos">
     
  </tbody>
  
 </table>

  </div>
</div>


</div>
</form>
</div>

</div>

<!-- Include Date Range Picker -->
<script type="text/javascript">
var prueba;
$(document).ready(function() {

$.datepicker.regional['es'] = {
 closeText: 'Cerrar',
 prevText: '< Ant',
 nextText: 'Sig >',
 currentText: 'Hoy',
 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
 weekHeader: 'Sm',
 dateFormat: 'dd/mm/yy',
 firstDay: 1,
 isRTL: false,
 showMonthAfterYear: false,
 yearSuffix: ''
 };
 $.datepicker.setDefaults($.datepicker.regional['es']);  

 $('#desde,#hasta').datepicker({
format: "yyyy-mm-dd",
toggleActive: true,
todayHighlight: true,
autoclose: true
});  

 }); 



consulta=function(f,e){
  e.preventDefault();
  $.post( "<?=base_url();?>index.php/reportes/get_balance", $(f).serialize())
  .done(function( data ) {
    $("#content_table").html("");
    $("#content_table").html(data);
  });
}

prueba=function(id_tipo_gasto){
 

 fecha_inicio=$("#desde").val();
 fecha_fin=$("#hasta").val();
 $.post( "<?=base_url();?>index.php/reportes/get_all_gastos_por_tipo", {fecha_inicio:fecha_inicio,fecha_fin:fecha_fin,id_tipo_gasto:id_tipo_gasto})
  .done(function( data ) {
     $("#modal-gastos").modal();
    $("#content_table_tipos").html("");
    $("#content_table_tipos").html(data);
  });


}

   
</script>

<style type="text/css">
  #totales{

    float: right;
    padding-right: 15px;
  }
  td{
   
    padding-left: 15px;
  }
  th{padding: 5px; text-align: center;}
  tr:nth-child(odd) {
    background-color:#f2f2f2;
}
tr:nth-child(even) {
    background-color:#fbfbfb;
}
</style>