<div class="panel panel-blue panel-sindicatos">
<div class="panel-heading">
<h4 class="panel-title">Lista Concesionarios</h4>
<div class="panel-heading-btn">
<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fas fa-expand"></i></a>
<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onclick="updateconcesionarios()"><i class="fas fa-sync-alt"></i></a>
</div>
</div>
<div class="panel-body">
 	
<table id="data-concesionarios" class="table table-striped table-bordered dt-responsive nowrap dataTable dtr-inline collapsed" cellspacing="0" width="100%" data-order="[[ 1, &quot;asc&quot; ]]" role="grid" aria-describedby="data-concesionarios_info" style="width: 100%;">
<thead>
<tr role="row">
<th class="all sorting_asc" aria-sort="ascending">Id</th>
<th class="all sorting" style="min-width: 100px; width: 100px;">Nombre</th>
<th class="sorting">Socio</th>
<th class="sorting">Sindicato</th>
<th class="sorting">Domicilio</th>
<th class="sorting">Ine</th>
<th class="sorting">Movil</th>
<th class="sorting">Status</th>
<th class="sorting">Correo</th>
<th class="text-center sorting">Fecha</th>
<th class="all sorting_disabled" style="min-width: 30px; max-width:30px; width: 30px;" data-orderable="false">Acción</th>
</tr>
	
</thead>	
</table>



</div>
</div>

<script>
var concesionarios;
var base_url="<?=base_url();?>";

function delete_concesionario(id,n){

var alsup=$.confirm({
theme: 'supervan',
draggable: false,
closeIcon: true,
animationBounce: 2.5,
escapeKey: false,
content: '¿Esta seguro que desea eliminar el concesionario? <br><b class="text-warning">#'+n+'</b><br> Los datos del concesionario serán eliminados junto a todas sus datos registradas.<br>',
title: '<i class="far fa-question-circle fa-lg icodialog"></i> ELIMINAR',
buttons: {
Eliminar: {
text: '<i class="far fa-trash-alt"></i> Si, eliminar',
action: function () {
this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...'); 
this.buttons.Eliminar.disable(); 
$('.jconfirm-closeIcon').remove();
$.post(base_url+ "concesionarios/delete_concesionarios", { id: id,nombre:n,action: "eliminar"})
  .done(function( data ) {
alsup.close();
if(data=='2'){
alerta('error',data);
}else{
alerta('exito','Concesionario eliminado correctamente');
updateconcesionarios();
}
  });
return false;
 
}}}
});


}

$(document).ready(function() {

configtable('#data-concesionarios','concesionarios');

	
concesionarios=$('#data-concesionarios').DataTable({
"ajax": "concesionarios/get_concesionarios_json",
"deferRender": true,
"idDataTables": "1",
"aoColumns": [
{ mData: 'id'},
{ mData: 'nombre'},
{ mData: 'idsocio'},
{ mData: 'idsindicato'},
{ mData: 'domicilio'},
{ mData: 'ine'},
{ mData: 'movil'},
{ mData: 'status',sClass: 'text-center',sWidth:'30px'},
{ mData: 'correo'},
{ mData: 'fechaalta'},
{ mData: 'tool',sWidth:'40px',sClass:'text-center'}
],
	
initComplete: function(oSettings, json){
	
$('.not-search-input').html('');	
 //------->Button Col
$('#data-concesionarios_wrapper .dt-buttons').append('');
      $("#data-concesionarios_wrapper div.tooltablas")
         .html('<div class="grupotabla btn-group m-r-5">\
  		<a class="btn btn-default" href="#concesionarios/add_concesionario"><i class="fas fa-plus"></i> Nuevo</a></div>');
}
}).on('processing.dt', function (e, settings, processing) {
if (processing) {
loaderin('.panel-sindicatos');
}else{
loaderout('.panel-sindicatos');	
}
}).on( 'draw', function () {
$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
} );


updateconcesionarios=function(){
concesionarios.ajax.reload( null, false );
$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
}

});
</script>