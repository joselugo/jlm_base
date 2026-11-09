
<style>
@media (max-width: 576px){
.icon-button,.dt-buttons {display:none;}
.slstatesuser{margin-left:-10px;}
}
</style>
<div class="panel panel-blue panel-socios">
<div class="panel-heading">
<h4 class="panel-title">Lista Unidades</h4>
<div class="panel-heading-btn">
<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fas fa-expand"></i></a>
<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onclick="updateunidad()"><i class="fas fa-sync-alt"></i></a>
</div>
</div>
<div class="panel-body">
 
	

<table id="data-unidades" class="display nowrap table table-striped table-bordered dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%">
<thead>
<tr role="row">
  <th  class="all sorting_asc" aria-sort="ascending">ID</th>
  <th  class="sorting" >Tipo</th>
  <th  class="sorting">Matrícula</th>
  <th class="desktop sorting" >Socio</th>
  <th class="desktop sorting" >N° Afiliado</th>
  <th class="desktop sorting">Modo</th>
  <th class="all text-center sorting">Estado</th>
  <th  class="sorting">Fecha alta</th>
   <th class="sorting">Descripción</th>
  <th class="all text-center sorting_disabled" style="max-width: 40px !important; width: 40px;" data-orderable="false">Acción</th>
</tr>
</thead>
</table>



</div>
</div>

<script>
var unidades;
var base_url="<?=base_url();?>";
	


$(document).ready(function() {

configtable('#data-unidades','unidades');

	
unidades=$('#data-unidades').DataTable({
"ajax": "unidades/get_unidades_json?estado=ACTIVO",
"deferRender": true,
"aoColumns": [
{ mData: 'id', sWidth : "10px"},
{ mData: 'tipo'},
{ mData: 'matricula'},
{ mData: 'socio'},
{ mData: 'codigo',sClass: 'text-center'},
{ mData: 'modounidad',sClass: 'text-center'},
{ mData: 'estado',sClass: 'text-center'},
{ mData: 'fechaalta'},
{ mData: 'descripcion'},
{ mData: 'tool',sClass: 'text-center'}
],
	
initComplete: function(oSettings, json){
		
 //------->Button Col
$('#data-unidades_wrapper .dt-buttons').append('');
      $("#data-unidades_wrapper div.tooltablas")
         .append('<div class="grupotabla">\
  		<button type="button" class="btn btn-default" data-toggle="modal" href="#modal-new-unidad"><i class="fas fa-plus"></i> <b>Nuevo</b></button>\
  		</div>');

	
$("#data-unidades_wrapper div.tooltablas div.grupotabla")
.append('<select class="slstatesuser form-control" name="tblestado" Onchange="loadtipo();" style="width: 170px">'+
'<option value="TODOS">TODOS</option>'+
'<option value="ACTIVO">ACTIVOS</option>'+
'<option value="SUSPENDIDO">SUSPENDIDOS</option>'+
'<option value="RETIRADO">RETIRADOS</option>'+
'</select>');


 $(".slstatesuser option[value='ACTIVO']").prop('selected', true);
$('[name="tblestado"]').select2();

 }
}).on('processing.dt', function (e, settings, processing) {
if (processing) {
loaderin('.panel-unidades');
}else{
loaderout('.panel-unidades');	
}
}).on( 'draw', function () {
$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
} );

loadtipo=function(){
if($('[name="tblestado"]').val()==1){
$('.allsp').show();
$('.allat').hide();
}else if($('[name="tblestado"]').val()==2){
$('.allsp').hide();
$('.allat').show();
}else{
$('.allsp').hide();
$('.allat').hide();	
}

unidades.ajax.url("unidades/get_unidades_json?action=lista&estado="+$('[name="tblestado"]').val()).load();
}

updateunidad=function(){
unidades.ajax.reload( null, false );
}


});


if ($("#searchcliente").autocomplete("instance")) {
$("#searchcliente").autocomplete("destroy");
$("#searchcliente").removeData('autocomplete');
}
	
App.restartGlobalFunction();
App.setPageTitle('Unidades');

</script>

</div>

<div class="modal" id="modal-new-unidad" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Nueva Unidad</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button></div>
<form id="servicio-form" method="post" action="unidades/new_unidad">
<div class="modal-body row">
  
<input type="hidden" class="form-control" name="idsocio" required="" value="generar codigo en base al sindicato">
<input type="hidden"  class="form-control" name="idsindicato" required="">


<div class="col-lg-6" style="min-height:150px">
 
<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Socio</label>
<div class="col-md-8">
<input type="text" class="form-control" id="searchsocio" name="socio" placeholder="Selecciona al socio" required="">
</div>
</div>


<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Tipo</label>
<div class="col-md-8">
<select class="form-control sl2" name="tipounidad" style="width:100%">
<option value="Mototaxi">Mototaxi</option>
<option value="Triciclo">Triciclo</option>
<option value="Motocarro">Motocarro</option>
<option value="Carro">Carro</option>
</select>
</div>
</div>
  

<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Matrícula</label>
<div class="col-md-8">
<input type="text" class="form-control" name="matricula">
</div>
</div>

<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Factura</label>
<div class="col-md-8">
<input type="text" class="form-control" name="factura">
<small>* Número de factura o serie de la unidad</small>
</div>
</div>
<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Descripción</label>
<div class="col-md-8">
<textarea class="form-control" name="descripcion" style="padding-top: 7px;"></textarea>
<small>* Texto para la unidad</small>
</div>
</div>



</div>                       

<div class="dataservice col-lg-6">

<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Modo</label>
<div class="col-md-8">
<select class="form-control sl2" name="modounidad" style="width:100%">
<option value="PROPIO">PROPIO</option>
<option value="CONCESIONADO">CONCESIONADO</option>
</select>
</div>
</div>

<div class="form-group row">
<label class="col-md-4 col-form-label text-right">ID Sindicato
</label>
<div class="col-md-8">
<input type="text" class="form-control" name="codigosindicato" readonly="">
<small>* Código único de identificación en el sindicato</small>
</div>
</div>

<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Fotografia
<button type="button" class="btn btn-icono btn-xs btn-map" onclick="viewimage()" data-toggle="tooltip" data-placement="top" data-original-title="Ver imagen"><i class="fas fa-eye"></i></button>
</label>
<div class="col-md-8">
<input class="form-control-file" name="fotounidad" type="file" accept="image/*,.pdf,.zip">
<small>* Fotografia de la unidad</small>
</div>
</div>


<div class="form-group row">
<label class="col-md-4 col-form-label text-right"></label>
<div class="col-md-8">
<a class="image-popup-vertical-fit" id="imgSalida2" href="">
  <img src="" id="imgSalida" width="80px" style="border-radius: 10px">
</a>
</div>

</div>

<style type="text/css">
  .mfp-wrap {
  z-index: 2000; }
  .tooltip{ z-index: 2000; }
</style>


  

</div>
</div>

<div class="modal-footer">
<a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
<?php $button=""; if($tipoacceso>1){$button='<button type="submit" class="btn btn-sm btn-success btn-newservice btn-loader">Registrar</button>';};
echo $button; ?>
</div>

</form>


<script>
var base_url="<?=base_url()?>";
var firelchk= Array.prototype.slice.call(document.querySelectorAll('.chk'));
firelchk.forEach(function(html) {
  var switchery = new Switchery(html, { size: 'small' ,color: 'rgb(18, 187, 168)'});
});


$(function(){

   $(document).on('keyup','#searchsocio',function(e){
$("#searchsocio" ).autocomplete({
source: base_url + 'socios/searchsocio?action=listauser',
minLength: 3,
open : function(event, ui){
$('img[data-name]').initial({
height:64,
width:64,
charCount:2,
fontSize:26,
fontWeight:600
}); 
},
select : function(event, ui){
$("#searchsocio").autocomplete("destroy");
$("#searchsocio").removeData('autocomplete');
//loadurl('#socios/view?id='+ui.item.id+'&token='+ui.item.token);
$('[name="idsocio"]').val(ui.item.id);
$('[name="idsindicato"]').val(ui.item.idsindicato)
load_codigo(ui.item.idsindicato)
}
}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li></li>" )

            .data( "item.autocomplete", item )
            .append(item.socio)
            .appendTo( ul ); 
    };
});

  $('.image-popup-vertical-fit').magnificPopup({
    type: 'image',
    closeOnContentClick: true,
    mainClass: 'mfp-img-mobile',
    image: {
      verticalFit: true
    }
    
  });

$('[name="fotounidad"]').change(function(e) {
      addImage(e); 
     });
$('[name="fotounidad-edit"]').change(function(e) {
      addImage2(e); 
     });

$(".sl2").select2({
    dropdownParent: $('#modal-new-unidad')
});

$(".sl3").select2({
    dropdownParent: $('#modal-edit-unidad')
});

$('#servicio-form').ajaxForm({ 
success: function(data) {
$('#gritter-notice-wrapper').remove();
btnloader('#servicio-form','<i class="far fa-save fa-lg"></i> Registrar');
if(data=='error'){
alerta('error',data);
}
else if(data=='matricula-duplicada'){
alerta('warning',"La matrícula ya está registrada");
}else{
$('#modal-new-unidad').modal('hide');
alerta('exito','Unidad registrada correctamente');
updateunidad();
$("#servicio-form")[0].reset()
}
},
beforeSubmit: function() {
btnloader('#servicio-form');
},
error: function(response) {
btnloader('#servicio-form','<i class="far fa-save fa-lg"></i> Registrar');
$('#gritter-notice-wrapper').remove();
alerta('error500',response['responseText']);
}})


///////////////////////////////////////////////FORM-UPDATE
$('#servicio-form-update').ajaxForm({
success: function(data) {
$('#gritter-notice-wrapper').remove();
btnloader('#servicio-form-update','<i class="far fa-save fa-lg"></i> Actualizar');
if(data=='error'){
alerta('error',data);
}else{
$('#modal-edit-unidad').modal('hide');
alerta('exito','Unidad actualizada correctamente');
updateunidad();
}
},
beforeSubmit: function() {
btnloader('#servicio-form-update');
},
error: function(response) {
btnloader('#servicio-form','<i class="far fa-save fa-lg"></i> Registrar');
$('#gritter-notice-wrapper').remove();
alerta('error500',response['responseText']);
}})


});

function addImage(e){
      var file = e.target.files[0],
      imageType = /image.*/;
    
      if (!file.type.match(imageType))
       return;
  
      var reader = new FileReader();
  
      reader.onload = function(e){
         var result=e.target.result;
        $('#imgSalida').attr("src",result);
        $('#imgSalida2').attr("href",result);
      }
       
      reader.readAsDataURL(file);
     }

 function addImage2(e){
      var file = e.target.files[0],
      imageType = /image.*/;
    
      if (!file.type.match(imageType))
       return;
  
      var reader = new FileReader();
  
      reader.onload = function(e){
         var result=e.target.result;
        $('#imgSalida-edit').attr("src",result);
        $('#imgSalida2-edit').attr("href",result);
      }
       
      reader.readAsDataURL(file);
     }    

 function viewimage(){
    $('#imgSalida2').click();
 }
  function viewimage2(){
    $('#imgSalida2-edit').click();
 }

 function edit_unidad(id,t){
 
$.ajax({
    data: {"id" : id, "token" : t},
    type: "POST",
    dataType: "json",
    url: base_url+ "unidades/edit_unidad",
})
 .done(function( data, textStatus, jqXHR ) {

     $('#modal-edit-unidad').modal();
     $('[name="idunidad-edit"]').val(data.id)
     $('[name="idsocio-edit"]').val(data.id_socio)
     $('[name="matricula-edit"]').val(data.matricula)
     $('[name="factura-edit"]').val(data.factura)
     $('[name="descripcion-edit"]').val(data.descripcion)
     $('[name="codigosindicato-edit"]').val(data.codigosindicato)
     $('#imgSalida-edit').attr("src",data.foto);
     $('#imgSalida2-edit').attr("href",data.foto);
     $('[name="tipounidad-edit"] option[value="'+data.tipounidad+'"]').prop('selected', true).change();
     $('[name="modounidad-edit"] option[value="'+data.modounidad+'"]').prop('selected', true).change();
     $('[name="estado-edit"] option[value="'+data.estado+'"]').prop('selected', true).change();
     
    
 })
 .fail(function( jqXHR, textStatus, errorThrown ) {
     if ( console && console.log ) {
         console.log( "La solicitud a fallado: " +  textStatus);
     }
}); 


 }

function delete_unidad(id,t,m,s){
$.confirm({
theme: 'supervan',
draggable: false,
closeIcon: true,
animationBounce: 2.5,
escapeKey: false,
content: 'Esta seguro que desea eliminar esta unidad? ',
title: '<i class="fa fa-question-circle fa-lg icodialog"></i> Eliminar Unidad',
buttons: {
Eliminar: {
text: '<i class="far fa-trash-alt icodialog"></i> Eliminar', // With spaces and symbols
action: function () {
$.post(base_url+"unidades/delete_unidad", { id: id, token: t , matricula:m,id_socio:s})
  .done(function( data ) {
   if(data=='error'){
  alerta('error',data);
  }else{
  alerta('exito',"Unidad eliminada");
  updateunidad();
  }
  });
 
}}}
}); 
}

function load_codigo(id){

 $.ajax({
    data: {"id" : id},
    type: "POST",
    url: base_url+ "unidades/codigo_qr_unidad",
})
 .done(function( data, textStatus, jqXHR ) {
   
    $('[name="codigosindicato"]').val(data) 
 }); 

}

function qr_unidad(id,t){

 $.ajax({
    data: {"id" : id,"token":t},
    type: "POST",
    url: base_url+ "unidades/codigo_qr_unidad",
})
 .done(function( data, textStatus, jqXHR ) {
   
    $('#qr-image').attr("src","<?=base_url()?>"+"public/qr_code/unidades/"+data)
    $('#qr-href').attr("href","<?=base_url()?>"+"public/qr_code/unidades/"+data) 
    $('#qr-href').attr("title",data)
    //$('.qr-title').html("Código QR #"+data)    
 });

 $("#modal-qr-unidad").modal(); 

}

</script>
</div></div></div>

<div class="modal" id="modal-edit-unidad" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title qr-title">Editar Unidad</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button></div>
<form id="servicio-form-update" method="post" action="unidades/update_unidad">
<div class="modal-body row">
  
<input type="hidden" name="idunidad-edit">
<input type="hidden" name="idsocio-edit">

<div class="col-lg-6" style="min-height:150px">

<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Status</label>
<div class="col-md-8">
<select class="form-control sl3" name="estado-edit" style="width:100%">
<option value="ACTIVO">ACTIVO</option>
<option value="SUSPENDIDO">SUSPENDIDO</option>
<option value="RETIRADO">RETIRADO</option>
</select>
</div>
</div>
  
<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Tipo</label>
<div class="col-md-8">
<select class="form-control sl3" name="tipounidad-edit" style="width:100%">
<option value="Mototaxi">Mototaxi</option>
<option value="Triciclo">Triciclo</option>
<option value="Motocarro">Motocarro</option>
<option value="Carro">Carro</option>
</select>
</div>
</div>
  

<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Matrícula</label>
<div class="col-md-8">
<input type="text" class="form-control" name="matricula-edit">
</div>
</div>

<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Factura</label>
<div class="col-md-8">
<input type="text" class="form-control" name="factura-edit">
<small>* Número de factura o serie de la unidad</small>
</div>
</div>
<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Descripción</label>
<div class="col-md-8">
<textarea class="form-control" name="descripcion-edit" style="padding-top: 7px;"></textarea>
<small>* Texto para la unidad</small>
</div>
</div>



</div>                       

<div class="dataservice col-lg-6">

<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Modo</label>
<div class="col-md-8">
<select class="form-control sl3" name="modounidad-edit" style="width:100%">
<option value="PROPIO">PROPIO</option>
<option value="CONCESIONADO">CONCESIONADO</option>
</select>
</div>
</div>

<div class="form-group row">
<label class="col-md-4 col-form-label text-right">ID Sindicato
</label>
<div class="col-md-8">
<input type="text" class="form-control" name="codigosindicato-edit" readonly="">
<small>* Código único de identificación en el sindicato</small>
</div>
</div>

<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Fotografia
<button type="button" class="btn btn-icono btn-xs btn-map" onclick="viewimage2()" data-toggle="tooltip" data-placement="top" data-original-title="Ver imagen"><i class="fas fa-eye"></i></button>
</label>
<div class="col-md-8">
<input class="form-control-file" name="fotounidad-edit" type="file" accept="image/*,.pdf,.zip">
<small>* Fotografia de la unidad</small>
</div>
</div>


<div class="form-group row">
<label class="col-md-4 col-form-label text-right"></label>
<div class="col-md-8">
<a class="image-popup-vertical-fit" id="imgSalida2-edit" href="">
  <img src="" id="imgSalida-edit" width="80px" style="border-radius: 10px">
</a>
</div>

</div>

<style type="text/css">
  .mfp-wrap {
  z-index: 2000; }
  .tooltip{ z-index: 2000; }
</style>


  

</div>
</div>

<div class="modal-footer">
<a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
<?php $button=""; if($tipoacceso>1){$button='<button type="submit" class="btn btn-sm btn-success btn-newservice btn-loader">Guardar</button>';};
echo $button; ?>
</div>

</form>

</div>
</div>
</div>



<div class="modal" id="modal-qr-unidad" role="dialog">
<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
<div class="modal-content">
<div class="modal-header">
  <h5 class="modal-title qr-title">Código QR</h5>
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
</div>

<div class="modal-body row">
 <div class="col-xs-12" style="text-align: center;"> 
 <a href="" title="" target="_blank" id="qr-href">
                    <img class="box-center" src="" id="qr-image">
                </a>
                <p class="text-center">Clic sobre la imagen para descargar</p>
</div>
</div>

<div class="modal-footer">
<a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
</div>
</div>
</div>
</div>
