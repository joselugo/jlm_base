
  <ol class="breadcrumb pull-right d-print-none">
        <li class="breadcrumb-item"><a href="#index/home">Inicio</a></li>
    <li class="breadcrumb-item"><a href="#reportes">reportes</a></li>
        <li class="breadcrumb-item active">Reporte #<?=$id?></li>
    
</ol> 

<h1 class="page-header">Reporte #<?=$id?> - <small><?=$asunto?></small></h1>

  
<ul class="nav nav-tabs d-print-none">
<li class="nav-item"><a href="#default-tab-1" data-toggle="tab" class="nav-link active show"><i class="far fa-edit"></i> Añadir respuesta</a></li>
<li class="nav-item"><a href="#default-tab-2" data-toggle="tab" class="nav-link"><i class="far fa-sticky-note"></i> Añadir nota</a></li>
<li class="nav-item"><a href="#default-tab-3" data-toggle="tab" class="nav-link"><i class="fas fa-cog"></i> Opciones</a></li>
</ul>
            

<div class="tab-content">
  
<div class="tab-pane fade active show" id="default-tab-1">
  
<form class="d-print-none form-horizontal" id="frm-replyreporte" method="post" action="socios/add_respuesta" enctype="multipart/form-data">
<div style="background: #f5f5f5;margin-bottom: 20px;border: 1px solid rgb(211, 216, 222);padding: 0px 5px;">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="id_socio" value="<?=$id_socio?>">
<input type="hidden" name="token" value="<?=$token?>">

<textarea class="textarea form-control" id="htmlreply" name="descripcion" placeholder="Ingrese una respuesta ...">
  
</textarea>



<div class="inner-container" style="display: block;padding: 10px">

<div style="height: 40px;">
<div class="pull-left"><button type="button" class="btn btn-default" onclick="$('.btnsup').slideToggle()"><i class="far fa-file"></i> Adjuntar archivo</button></div>
  
<div class="pull-left" style="margin-left: 5px;">
  <button type="button" class="btn btn-default" onclick="$('.btndatauser').slideToggle()"><i class="far fa-user"></i> Info socio</button>
</div>
  
<div class="pull-left" style="margin-left: 5px;">
  <button type="button" class="btn btn-default" onclick="$('.infotk').slideToggle()"><i class="fas fa-info"></i> Info reporte</button>
</div>
  
<div class="pull-left" style="margin-left: 5px;"><button type="button" class="btn btn-default" onclick="window.print();"><i class="fas fa-print"></i></button></div>
    
<div class="pull-right"><button type="submit" class="btn btn-default"><i class="fas fa-reply"></i> Responder</button></div>

</div>
  
<div class="row btnsup" style="display: none">
<div class="col-sm-9" style="background: #fff;padding: 10px;border: 1px solid #e6e6e6;border-radius: 4px;">
<input type="file" name="adjunto[]" capture="camera" class="form-control" style="margin-bottom:10px;">
<div id="fileuploads"></div>
</div>
<div class="col-sm-3">
<a class="btn btn-white m-t-15" onclick="addupload()"><i class="fas fa-plus"></i> Añadir más</a>
</div>
</div>
 </div>
</div>
</form>
  
<div class="infotk print" style="display: none">
  
<div style="width: 100%;text-align: center"><img src="images/logo.png" style="max-height:50px; width:auto"></div>
  
<h2 class="text-center m-t-5 m-b-10">REPORTE #<?=$id?></h2>

<div class="panel panel-default">
<div class="panel-heading">
<h4 class="panel-title"><i class="far fa-life-ring"></i> Datos del reporte</h4>
</div>
<div class="panel-body border p-b-0">
<div class="list-group">

<div class="list-group-item ticket-details-children">
<span class="title">Asunto: <?=$asunto?></span>
</div>

<div class="list-group-item ticket-details-children">
<span class="title">Tipo : <?=$tipo?></span>
</div>
  
<div class="list-group-item ticket-details-children">
<span class="title">Fecha apertura: <?=$fechaalta?></span>
</div>

<div class="list-group-item ticket-details-children">
<span class="title">Estado : <?=$estado?></span>
</div>


<div class="list-group-item ticket-details-children">
<span class="title">Solicitante : <?=$from?></span>
</div>

<div class="list-group-item ticket-details-children">
<span class="title">Abierto por : <?=$usuario?></span>
</div>

</div>
</div>
</div>

</div>
  
<div class="btndatauser print" style="display: none">

<div class="panel panel-default">
<div class="panel-heading">
<h4 class="panel-title"><i class="far fa-life-ring"></i> Información del socio</h4>
</div>
<div class="panel-body border p-b-0">
<div class="list-group">

<div class="list-group-item ticket-details-children">
<span class="title">Nombre : <?=$nombre?></span>
</div>

<div class="list-group-item ticket-details-children">
<span class="title">Dirección Principal: <span class="datadirec"><?=$domicilio?></span></span>
</div>


<div class="list-group-item ticket-details-children">
<span class="title">Móvil : <?=$movil?></span>
</div>

<div class="list-group-item ticket-details-children">
<span class="title">Sindicato : <?=$sindicato?></span>
</div>
  
<style type="text/css">
.tooltip {
  pointer-events: none;
} 
</style>


</div>
</div>
</div>

</div>
  
  <?=$cadena?>
  
</div>
  
<div class="tab-pane fade" id="default-tab-2">
<form class="form-horizontal" id="frm-replynota" method="post" action="socios/add_nota" enctype="multipart/form-data">
<div style="background: #f5f5f5;margin-bottom: 20px;border: 1px solid rgb(211, 216, 222);padding: 0px 5px;">
<input name="id" type="hidden" value="<?=$id?>">
<input type="hidden" name="id_socio" value="<?=$id_socio?>">
<input type="hidden" name="token" value="<?=$token?>">
<textarea class="textarea form-control" id="htmlnota" name="nota"  placeholder="Ingrese una respuesta ...">
  
</textarea>


<div class="inner-container" style="display: block;padding: 10px">

<div style="height: 40px;">

<div class="pull-right"><button type="submit" class="btn btn-success"><i class="fas fa-reply"></i> Agregar nota</button></div>
</div>

 </div>
</div>
</form>
</div>
  

<div class="tab-pane fade row" id="default-tab-3">
<form id="reporte-config" class="col-md-8" action="socios/update_reporte" method="post" data-select2-id="[object HTMLInputElement]">
<input name="id-reporte" type="hidden" value="<?=$id?>">
<input name="id-socio" type="hidden" value="<?=$id_socio?>">
<input type="hidden" name="token" value="<?=$token?>">
<div class="col-sm-12">    
<div class="form-group">
<label>Asunto</label>
<input class="form-control" name="asunto-reporte" type="text" value="<?=$asunto?>">
</div>
</div>

<div class="col-sm-6 float-left">    
<div class="form-group">
<label>Tipo</label>
<select class="form-control sl2" name="tipo-reporte" style="width: 100%">
<option value="SERVICIO">SERVICIO</option>
<option value="ATENCIÓN">ATENCIÓN</option> 
<option value="VIAL">VIAL</option>
</select>
</div>
</div>

<div class="col-sm-6 float-left">    
<div class="form-group">
<label>Status</label>
<select class="form-control sl2" name="estado-reporte" style="width: 100%">
<option value="NUEVO">NUEVO</option>
<option value="EN REVISION">EN REVISION</option>
</select>
</div>
</div>
  
<div class="col-sm-12 row">    
<div class="form-group">
<button type="submit" class="btn btn-primary">Guardar Cambios</button>
</div>
</div>


</form> 
</div>
  
</div>




<script>
  


function getrutamaps(id){
$.post( "ajax/usuarios", { action: "getruta",id:id})
.done(function( datas ) {
$('#tmp2').html(datas);
});
}
  

function addupload(){
$('#fileuploads').append('<input type="file" name="adjunto[]" capture="camera" class="form-control" style="margin-bottom:10px;">')
}
  


function deleteitem(id,id_reporte,t,s){
  
$.confirm({
theme: 'supervan',
draggable: false,
closeIcon: true,
animationBounce: 2.5,
escapeKey: false,
content: 'Esta seguro que desea eliminar esta respuesta y los archivos adjuntos?',
title: '<i class="far fa-question-circle fa-lg icodialog"></i> Eliminar respuesta',
buttons: {
Eliminar: {
text: '<i class="far fa-trash-alt icodialog"></i> Si, eliminar', // With spaces and symbols
action: function () {
$.post( "socios/delete_respuesta", { id: id,id_reporte:id_reporte,token:t,id_socio:s})
  .done(function( data ) {
$('.item-'+id).remove();
alerta('exito','Respuesta eliminada correctamente');
  });
 
}}}
});
  
  
}
  
$(document).ready(function() {
var estado='<?=$estado?>';
if(estado=="CERRADO" || estado=="ATENDIDO"){
  $('select[name="estado-reporte"]').append('<option value="ATENDIDO">ATENDIDO</option>');
  $('select[name="estado-reporte"]').append('<option value="CERRADO">CERRADO</option>');  
  $('select[name="estado-reporte"]').attr('disabled', true);
}
else{
  $('select[name="estado-reporte"]').attr('disabled', false);
}
 

$('select[name="tipo-reporte"]   option[value="<?=$tipo?>"]').prop('selected', true).change();
$('select[name="estado-reporte"] option[value="<?=$estado?>"]').prop('selected', true).change();


$('.sl2').select2();
 
configtable('#data-soporte-cliente','Soporte cliente');
$('#data-soporte-cliente').DataTable({
"deferRender": true,
"idDataTables": "1",
})  
  
$('.btn-map').on('click', function () {
$.post( "ajax/router", { action: "mapa"})
.done(function( datas ) {
$('#tmp').html(datas);
});
});

$('#frm-replyreporte').ajaxForm({
success: function(data) {
//-->Enviar notificacion
//socketSerVER.emit('enviaralerta',{id:data['id']});

$('#gritter-notice-wrapper').remove();
if(data=="success"){
loadurl("#socios/view_reportes?id=<?=$id?>&token=<?=$token?>");
alerta('exito','Respuesta enviada correctamente');
}
else{
  alerta('error',data);
}

},
beforeSubmit: function() {
if(!$('#htmlreply').val()){
$('#gritter-notice-wrapper').remove();
alerta('error','No ha indicado ninguna respuesta');
return false;
}
alerta('loader','Subiendo archivos adjuntos...')
},
error: function(response) {
$('#gritter-notice-wrapper').remove();
alerta('error500',response['responseText']);
}})
  
$('#frm-replynota').ajaxForm({
success: function(data) {
$('#gritter-notice-wrapper').remove();
if(data=="success"){
loadurl("#socios/view_reportes?id=<?=$id?>&token=<?=$token?>");
alerta('exito','Nota registrada correctamente');
}
else{
  alerta('error',data);
}
},
beforeSubmit: function() {
if(!$('#htmlnota').val()){
$('#gritter-notice-wrapper').remove();
alerta('error','No ha indicado ninguna nota');
return false;
}
alerta('loader','Agregando nota...')
},
error: function(response) {
$('#gritter-notice-wrapper').remove();
alerta('error500',response['responseText']);
}})
  
$('#reporte-config').ajaxForm({
success: function(data) {
$('#gritter-notice-wrapper').remove();
if(data=="success"){
loadurl("#socios/view_reportes?id=<?=$id?>&token=<?=$token?>");
alerta('exito','Nota registrada correctamente');
}
else{
  alerta('error',data);
}
},
error: function(response) {
$('#gritter-notice-wrapper').remove();
alerta('error500',response['responseText']);
}})
  
$('#htmlreply,#htmlnota').wysihtml5({
locale: "es-ES",
toolbar: {
    "fa": true,
  }
});

  
  
$('.imguser').initial({
height:80,
width:80,
charCount:2,
color:'#348fe2',
fontSize:40,
fontWeight:500
});
  
$('.imgtecnico').initial({
height:80,
width:80,
charCount:2,
color:'#975CAF',
fontSize:40,
fontWeight:500
});
  
App.restartGlobalFunction();
App.setPageTitle('Ver reporte');

  
})
</script> 
  