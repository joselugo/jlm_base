
<div class="panel panel-blue panel-sindicatos">
<div class="panel-heading">
<h4 class="panel-title">Lista Sindicatos</h4>
<div class="panel-heading-btn">
<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fas fa-expand"></i></a>
<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onclick="updatesindicatos()"><i class="fas fa-sync-alt"></i></a>
</div>
</div>
<div class="panel-body">
 	
<table id="data-sindicatos" class="table table-striped table-bordered dt-responsive nowrap dataTable dtr-inline collapsed" cellspacing="0" width="100%" data-order="[[ 1, &quot;asc&quot; ]]" role="grid" aria-describedby="data-socios_info" style="width: 100%;">
<thead>
<tr role="row">
<th class="desktop sorting_asc" aria-sort="ascending">Id</th>
<th class="all sorting" style="min-width: 100px; width: 100px;">Nombre</th>
<th class="sorting">Nombre del lider</th>
<th class="text-center sorting">Status</th>
<th class="all sorting_disabled" style="min-width: 30px; max-width:30px; width: 30px;" data-orderable="false">Acción</th>
</tr>
	
</thead>	
</table>
</div>
</div>

<script>
var sindicatos;
var base_url="<?=base_url();?>";
	
function delete_sindicato(id,n){

var alsup=$.confirm({
theme: 'supervan',
draggable: false,
closeIcon: true,
animationBounce: 2.5,
escapeKey: false,
content: '¿Esta seguro que desea eliminar el sindicato? <br><b class="text-warning">#'+n+'</b><br> Los datos del sindicato serán eliminados junto a todas sus datos registradas.<br>',
title: '<i class="far fa-question-circle fa-lg icodialog"></i> ELIMINAR',
buttons: {
Eliminar: {
text: '<i class="far fa-trash-alt"></i> Si, eliminar',
action: function () {
this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...'); 
this.buttons.Eliminar.disable(); 
$('.jconfirm-closeIcon').remove();
$.post(base_url+ "sindicatos/delete_sindicato", { id: id,nombre:n,action: "eliminar"})
  .done(function( data ) {
alsup.close();
if(data=='2'){
alerta('error',data);
}else{
alerta('exito','Sindicato eliminado correctamente');
updatesindicatos();
}
  });
return false;
 
}}}
});


}
	
function cambiarstatus(id,t,n,s,s1){
var alsup=$.confirm({
theme: 'supervan',
draggable: false,
closeIcon: true,
animationBounce: 2.5,
escapeKey: false,
content: 'Esta seguro que desea cambiar el status del socio <br><b class="text-warning">'+n+'</b> a '+ s,
title: '<i class="far fa-question-circle fa-lg icodialog"></i>'+ s1,
buttons: {
Eliminar: {
text: '<i class="fas fa-check icodialog"></i> Si, ' + s1,
action: function () {
this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...'); 
this.buttons.Eliminar.disable(); 
$('.jconfirm-closeIcon').remove();
$.post( "socios/update_status", { id: id, token:t, nombre:n, status: s, action: "activar" })
  .done(function( data ) {
alsup.close();
if(data=='error'){
alerta('error',data);
}else{
alerta('exito','Status de socio modificado correctamente');
$('[data-toggle="popover"]').popover('toggle')
updatesindicatos();
}
  });
return false;
 
}}}
});


}
	

$(document).ready(function() {

configtable('#data-sindicatos','sindicatos');

	
sindicatos=$('#data-sindicatos').DataTable({
"ajax": "sindicatos/get_sindicatos_json",
"deferRender": true,
"idDataTables": "1",
"aoColumns": [
{ mData: 'id'},
{ mData: 'nombre'},
{ mData: 'idlider'},
{ mData: 'estado',sClass: 'text-center',sWidth:'30px'},
{ mData: 'tool',sWidth:'40px',sClass:'text-center'}
],
	
initComplete: function(oSettings, json){
	
$('.not-search-input').html('');	
 //------->Button Col
$('#data-sindicatos_wrapper .dt-buttons').append('');
      $("#data-sindicatos_wrapper div.tooltablas")
         .html('<div class="grupotabla btn-group m-r-5">\
  		<a class="btn btn-default" data-toggle="modal" data-target="#moda_sindicatos"><i class="fas fa-plus"></i> Nuevo</a></div>');
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

updatesindicatos=function(){
sindicatos.ajax.reload( null, false );
$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
}
});

if(localStorage.caja){
$('#collapseactivo').collapse();	
}
loadSettings();

//------> Filtro
function filtro_a(columna,texto) {
if(texto==""){
socios.search( '' ).columns().search( '' ).draw();
}else{
columna=parseInt(columna) + 1;
socios.columns(columna).search(texto).draw();
}
saveSettings();
}

function loadSettings() {
$('.select-filter-a option[value="' + localStorage.columna + '"]').prop('selected',true);
$('.txt-filtro-a').val(localStorage.caja);
}

function saveSettings() {
localStorage.columna = $('.select-filter-a').val();
localStorage.caja = $('.txt-filtro-a').val();
}

if ($("#searchcliente").autocomplete("instance")) {
$("#searchcliente").autocomplete("destroy");
$("#searchcliente").removeData('autocomplete');
}
	
App.restartGlobalFunction();
App.setPageTitle('Socios');

</script>


<!-- Modal New Sindicato -->
<div class="modal" id="moda_sindicatos" role="dialog"><div class="modal-dialog modal-dialog-centered modal-md" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Nuevo Sindicato</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button></div>
<form id="sindicato_form" method="post" action="sindicatos/new_sindicatos" class="form-horizontal">
<div class="modal-body ">                 

<div class="form-group row">
<label class="col-md-3 control-label">Nombre</label>
<div class="col-md-9">
<input type="text" class="form-control" name="nombre" placeholder="Nombre sindicato" required="" autofocus="">
</div>
</div>

<div class="form-group row">
<label class="col-md-3 control-label">Lider Sindicato</label>
<div class="col-md-9">
<select style="width: 100%;" class="form-control Select2" name="idlider" required="">
<?=$tbllider?>
</select>
</div>
</div>
    
</div>

<div class="modal-footer">
<a href="" class="btn-azul" data-dismiss="modal">Cerrar</a>
<?php $button=""; if($tipoacceso>1){$button='<button type="submit" class="btn btn-sm btn-azul">Guardar</button>';}?>
<?=$button?>
</div>

</form>
</div>
</div>
</div>

<script>
$(function(){

$(".Select2").select2({
placeholder: "Seleccionar..."
});

$('#sindicato_form').ajaxForm({
success: function(data) {
$('#gritter-notice-wrapper').remove();
if(data =='2'){
alerta('error',data);
}else{
alerta('exito','Datos Guardados Correctamente');
updatesindicatos();
}
},
beforeSubmit: function() {
$('#moda_sindicatos').modal('hide');
alerta('loader','');
},
error: function(response) {
$('#gritter-notice-wrapper').remove();
alerta('error500',response['responseText']);
}})

})
</script>

<!-- End Modal Sindicato -->

<!-- ================================= Update Modal Sindicato ============================== -->
<div class="modal" id="update_sindicato_modal" role="dialog"><div class="modal-dialog modal-dialog-centered modal-sm" role="document"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">Editar Sindicato</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button></div>	
<form id="sindicato-edit-form" method="post" action="ajax/almacen?action=updatecat" class="form-horizontal">
<div class="modal-body">
<div class="row">
 <input type="hidden" name="id-edit" >
    
    <div class="form-group row">
        <label class="col-md-3 control-label">Nombre</label>
        <div class="col-md-9">
        <input type="text" class="form-control" name="nombre-edit" placeholder="Nombre sindicato" required="" autofocus="">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-3 control-label">Lider Sindicato</label>
        <div class="col-md-9">
        <select style="width: 100%;" class="form-control Select2" name="idlider-edit" required="">
        <?=$tbllider?>
        </select>
        </div>
    </div>
    
 </div>
</div>

<div class="modal-footer">
<a class="btn-azul" data-dismiss="modal">Cerrar</a>
<button type="submit" class="btn-azul">Registrar</button>
</div>

</form>

</div></div></div>

<script>
function update_sindicato(id){
 
 $.ajax({
     data: {"id" : id},
     type: "POST",
     dataType: "json",
     url: base_url+ "sindicatos/update_sindicato",
 })
  .done(function(data) {
      $('#update_sindicato_modal').modal();
      $('[name="id-edit"]').val(data.id)
      $('[name="nombre-edit"]').val(data.nombre)
      $('[name="idlider-edit"] option[value="'+data.idlider+'"]').prop('selected', true).change();
  })
  .fail(function( jqXHR, textStatus, errorThrown ) {
      if ( console && console.log ) {
          console.log( "La solicitud a fallado: " +  textStatus);
      }
 }); 
 
  }


</script>
	
	
	

<!-- =============================== End Update Modal Sindicato =========================== -->