<div class="panel panel-inverse panel-operaciones">
<div class="panel-heading">
<h4 class="panel-title">Lista socios</h4>
<div class="panel-heading-btn">
<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fas fa-expand"></i></a>
<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onclick="updatelistas()"><i class="fas fa-sync-alt"></i></a>
</div>

</div>
<div class="panel-body">
  
<form class="row form-horizontal m-b-15" id="frmoperaciones" method="post" action="listas/viewreporte?token=<?=$token?>" target="_blank">
 <input type="hidden" name="token" value="<?=$token?>"> 
<div class="col-sm-6">
<div class="form-group row">
<label class="col-sm-3 control-label">Status</label>
<div class="col-sm-9">
<select class="form-control filtroop sl2" id="status" name="status" style="width: 100%">
<option value="%%">TODOS</option>
<option value="ACTIVO">Socios ACTIVOS</option>
<option value="PRUEBA">Socios PRUEBA</option>
<option value="SUSPENDIDO">Socios SUSPENDIDOS</option>
<option value="RETIRADO">Socios RETIRADOS</option>
</select>

</div>
</div>
</div>  
  
<div class="col-sm-6">
<div class="form-group row">
<label class="col-sm-3 control-label">Sindicato</label>
<div class="col-sm-9">
<select class="form-control filtroop sl2" id="sindicato" name="sindicato" style="width:100%">
<option value="%%">TODOS</option>
<?=$sindicatos?>
</select>


</div>
</div>
</div> 

<div class="col-sm-6">
<div class="form-group row">
<label class="col-sm-3 control-label">Licencia</label>
<div class="col-sm-9">
<select class="form-control filtroop sl2" id="licencia" name="licencia" style="width:100%">
<option value="%%">TODOS</option>
<option value="SI">CON LICENCIA</option>
<option value="NO">SIN LICENCIA</option>
</select>


</div>
</div>
</div>  

 
  
  
  
</form>
<div class="col-md-12">
<table id="data-socios" class="table table-striped table-bordered dt-responsive nowrap dataTable dtr-inline collapsed" cellspacing="0" width="100%"  role="grid" aria-describedby="data-socios_info" style="width: 100%;">
<thead>
<tr role="row">
<th class="all sorting_asc" aria-sort="ascending">Id</th>
<th class="all sorting" style="min-width: 100px; width: 100px;">Nombre</th>
<th class="sorting">Sindicato</th>
<th class="sorting">Movil</th>
<th class="sorting">Licencia</th>
<th class="sorting">Alta</th>
<th class="text-center sorting">Status</th>
</tr>
  
</thead>  
</table>
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

var base_url="<?=base_url()?>";  

function reporte_listas_socios(){
$('#frmoperaciones').submit();
}
  

var socios,slostatus,slsindicado,sllicencia;

function loadSettingsop() {
$('#status option[value="' + localStorage.op_status + '"]').prop('selected',true);
$('#sindicato option[value="' + localStorage.op_sindicato + '"]').prop('selected',true);
$('#licencia option[value="' + localStorage.op_licencia + '"]').prop('selected',true);
}

function saveSettingsop() {
localStorage.op_status = $('#status').val();
localStorage.op_sindicato = $('#sindicato').val();
localStorage.op_licencia = $('#licencia').val();
}

function reset_operaciones(){
$('.sl2').select2('destroy');
$('#status option[value="%"]').prop('selected',true);
$('#sindicato option[value="%"]').prop('selected',true);
$('#licencia option[value="%"]').prop('selected',true);


slstatus.val("%%").trigger("change");
slsindicado.val("%%").trigger("change");
sllicencia.val("%%").trigger("change");
$('.sl2').select2('destroy');
}
  
if(localStorage.op_desde){
loadSettingsop();
}
  
$(document).ready(function() {
  

  
$('.sl2').select2();
  
$(document).on('click','[data-load-printer]',function(f) {  
closep();
var e=$(this);
$(this).prop( "disabled",true);
$.get(e.data('load-printer'),function(d) {

  e.popover({content: d,
  container: '.panel-operaciones',
  html:true,
  width:'140px',
  placement:"left",
  title : '<span class="text-info"><strong>Imprimir</strong></span>'+
          '<button type="button" id="close" class="close" onclick="closep()">&times;</button>'
  
  }).popover('show');
    });
  
e.on('show.bs.popover', function (r) {
//$(this).data("bs.popover").tip().css("width", "160px");
})
})
  
  
configtable('#data-socios','Socios');
  
socios=$('#data-socios').DataTable({
"ajax": base_url+ "listas/get_socios_json?status="+$('#status').val()+'&sindicato='+$('#sindicato').val()+'&licencia='+$('#licencia').val(),  
"deferRender": true,
"idDataTables": "1",
//"bAutoWidth": true,
"aoColumns": [
{ mData: 'id'},
{ mData: 'nombre'},
{ mData: 'sindicato',sClass: 'text-center',sWidth:'30px'},
{ mData: 'movil'},
{ mData: 'licencia'},
{ mData: 'fechaalta'},
{ mData: 'status',sClass: 'text-center',sWidth:'30px'},
],
   
initComplete: function(oSettings, json){
  
this.api().columns().every( function () {
var state = this.state.loaded();  
var column = this;
  
var txtsrh='';
if(state){txtsrh=state.columns[column[0]].search.search;  }
  
$('<input type="text" placeholder="Buscar" class="form-control" value="'+txtsrh+'">')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'keyup', function () {
  column.search( $(this).val()).draw();
                    } );
 
 });
  
$('.not-search-input').html('');
  
 //------->Button Col
      $("#data-socios_wrapper div.tooltablas")
         .html('<div class="grupotabla btn-group">\
      <button class="btn btn-default" onclick="reporte_listas_socios()"><i class="far fa-file-pdf"></i> Resumen PDF </button>\
    </div>');

}
}).on('processing.dt', function (e, settings, processing) {
if (processing) {
loaderin('.panel-operaciones');
}else{
loaderout('.panel-operaciones');  
}
}).on( 'draw', function (e,d) {
$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
});
  

updatelistas=function(){
socios.ajax.reload( null, false );
}

//--------> Filtro 
$( ".filtroop" ).change(function() {
socios.ajax.url(base_url+ "listas/get_socios_json?status="+$('#status').val()+'&sindicato='+$('#sindicato').val()+'&licencia='+$('#licencia').val()).load();
$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
saveSettingsop();
});
  


App.restartGlobalFunction();
App.setPageTitle('Lista socios');
  
});

    
</script>