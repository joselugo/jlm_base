<div class="panel panel-inverse panel-operaciones">
<div class="panel-heading">
<h4 class="panel-title">Lista unidades</h4>
<div class="panel-heading-btn">
<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fas fa-expand"></i></a>
<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onclick="updatelistas()"><i class="fas fa-sync-alt"></i></a>
</div>

</div>
<div class="panel-body">
  
<form class="row form-horizontal m-b-15" id="frmoperaciones" method="post" action="listas/viewreporte_unidades?token=<?=$token?>" target="_blank">
 <input type="hidden" name="token" value="<?=$token?>"> 
<div class="col-sm-6">
<div class="form-group row">
<label class="col-sm-3 control-label">Status</label>
<div class="col-sm-9">
<select class="form-control filtroop sl2" id="status" name="status" style="width: 100%">
<option value="%%">TODOS</option>
<option value="ACTIVO">ACTIVOS</option>
<option value="SUSPENDIDO">SUSPENDIDOS</option>
<option value="RETIRADO">RETIRADOS</option>
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
<label class="col-sm-3 control-label">Modo unidad</label>
<div class="col-sm-9">
<select class="form-control filtroop sl2" id="modounidad" name="modounidad" style="width:100%">
<option value="%%">TODOS</option>
<option value="PROPIO">PROPIO</option>
<option value="CONCESIONADO">CONCESIONADO</option>
</select>


</div>
</div>
</div>  

<div class="col-sm-6">
<div class="form-group row">
<label class="col-sm-3 control-label">Tipo unidad</label>
<div class="col-sm-9">
<select class="form-control filtroop sl2" id="tipounidad" name="tipounidad" style="width:100%">
<option value="%%">TODOS</option>
<option value="Mototaxi">MOTOTAXI</option>
<option value="Triciclo">TRICICLO</option>
<option value="Motocarro">MOTOCARRO</option>
<option value="Carro">CARRO</option>
</select>


</div>
</div>
</div> 

 
  
  
  
</form>
<div class="col-md-12">
<table id="data-unidades" class="display nowrap table table-striped table-bordered dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%">
<thead>
<tr role="row">
  <th  class="all sorting_asc" aria-sort="ascending">ID</th>
  <th  class="sorting" >Tipo</th>
  <th  class="sorting">Matrícula</th>
  <th class="desktop sorting" >Socio</th>
  <th class="desktop sorting" >N° Afiliado</th>
   <th class="desktop sorting">Sindicato</th>
  <th class="desktop sorting">Modo</th>
  <th class="all text-center sorting">Estado</th>
  <th  class="sorting">Fecha alta</th>
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

function reporte_listas_unidades(){
$('#frmoperaciones').submit();
}
  

var unidades,slostatus,slsindicado,slmodounidad;

function loadSettingsop() {
$('#status option[value="' + localStorage.op_status + '"]').prop('selected',true);
$('#sindicato option[value="' + localStorage.op_sindicato + '"]').prop('selected',true);
$('#modounidad option[value="' + localStorage.op_modounidad + '"]').prop('selected',true);
$('#tipounidad option[value="' + localStorage.op_tipounidad + '"]').prop('selected',true);
}

function saveSettingsop() {
localStorage.op_status = $('#status').val();
localStorage.op_sindicato = $('#sindicato').val();
localStorage.op_modounidad = $('#modounidad').val();
localStorage.op_tipounidad = $('#tipounidad').val();
}

function reset_operaciones(){
$('.sl2').select2('destroy');
$('#status option[value="%"]').prop('selected',true);
$('#sindicato option[value="%"]').prop('selected',true);
$('#modounidad option[value="%"]').prop('selected',true);
$('#tipounidad option[value="%"]').prop('selected',true);


slstatus.val("%%").trigger("change");
slsindicado.val("%%").trigger("change");
slmodounidad.val("%%").trigger("change");
sltipounidad.val("%%").trigger("change");
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
  
  
configtable('#data-unidades','Unidades');
  
unidades=$('#data-unidades').DataTable({
"ajax": base_url+ "listas/get_unidades_json?status="+$('#status').val()+'&sindicato='+$('#sindicato').val()+'&modounidad='+$('#modounidad').val()+'&tipounidad='+$('#tipounidad').val(),  
"deferRender": true,
"idDataTables": "1",
//"bAutoWidth": true,
"aoColumns": [
{ mData: 'id', sWidth : "10px"},
{ mData: 'tipo'},
{ mData: 'matricula'},
{ mData: 'socio'},
{ mData: 'codigo',sClass: 'text-center'},
{ mData: 'sindicato',sClass: 'text-center'},
{ mData: 'modounidad',sClass: 'text-center'},
{ mData: 'estado',sClass: 'text-center'},
{ mData: 'fechaalta'},
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
      $("#data-unidades_wrapper div.tooltablas")
         .html('<div class="grupotabla btn-group">\
      <button class="btn btn-default" onclick="reporte_listas_unidades()"><i class="far fa-file-pdf"></i> Resumen PDF </button>\
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
unidades.ajax.reload( null, false );
}

//--------> Filtro 
$( ".filtroop" ).change(function() {
unidades.ajax.url(base_url+ "listas/get_unidades_json?status="+$('#status').val()+'&sindicato='+$('#sindicato').val()+'&modounidad='+$('#modounidad').val()+'&tipounidad='+$('#tipounidad').val()).load();
$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
saveSettingsop();
});
  


App.restartGlobalFunction();
App.setPageTitle('Lista unidades');
  
});

    
</script>