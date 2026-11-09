<style type="text/css">
  @media (max-width: 576px){ 
    #data-reportes_wrapper .dt-buttons{display: inline-table;}
    #data-reportes_wrapper #data-reportes_filter{margin-top: -20px; display:initial; }
    #data-reportes_filter label{width: 100%}
  }
  @media (max-width: 767.98px){
    #data-reportes_wrapper #data-reportes_filter{margin-top: -20px; width: 100%; display: inline-block;}
    #data-reportes_filter label{float: right;width: 101.5%}
  } 

</style>
<div class="tab-pane active show" id="nav-tab-2">
<div class="panel panel-default panel-unidades">
<div class="panel-heading">
<h4 class="panel-title"><b>Lista de reportes</b></h4>
<div class="panel-heading-btn">
<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-expand" data-original-title="" title="" data-init="true"><i class="fa fa-expand"></i></a>
<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-reload" onclick="updatereportes()" data-original-title="" title="" data-init="true"><i class="fa fa-sync-alt"></i></a>
<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-collapse" data-original-title="" title="" data-init="true"><i class="fa fa-minus"></i></a>
</div>
</div>
<div class="panel-body border-panel">

<table id="data-reportes" class="display nowrap table table-striped table-bordered dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%">
<thead>
<tr role="row">
  <th  class="all sorting_asc" aria-sort="ascending">ID</th>
  <th  class="sorting" >Tipo</th>
  <th  class="sorting">Desde</th>
  <th  class="sorting">Usuario</th>
  <th  class="sorting">Asunto</th>
  <th  class="sorting">Estatus</th>
  <th class="desktop sorting">Fecha alta</th>
  <th class="all text-center sorting_disabled" style="max-width: 40px !important; width: 40px;" data-orderable="false">Acción</th>
</tr>
</thead>
</table>

<input type="hidden" name="id_socio" value="<?=$id?>">
<input type="hidden" name="tipoacceso" value="<?=$tipoacceso?>">
</div>
</div>


 <script>
 var reportes;

   
updatereportes=function(){
reportes.ajax.reload( null, false );
}

 $(function(){


$('.sl2').select2();

$('#htmltk').wysihtml5({
locale: "es-ES",
toolbar: {
    "fa": true,
  }
});
  
$('#reportes-form').ajaxForm({
success: function(data) {
$('#gritter-notice-wrapper').remove();
if(data==1){
  alerta('exito','Reporte ingresado correctamente');
  updatereportes();
  $("#modal-new-edit").modal("hide")
}
else
{
  alerta('error', data);
}

},
beforeSubmit: function() {
$('#modaltmp').modal('hide');
},
error: function(response) {
$('#gritter-notice-wrapper').remove();
alerta('error500',response['responseText']);
}})


configtable('#data-reportes','reportes');
   
reportes=$('#data-reportes').DataTable({
"ajax": "socios/get_reportes_json?id="+$('[name="id_socio"]').val(),
"deferRender": true,
"aoColumns": [
{ mData: 'id', sWidth : "10px"},
{ mData: 'tipo'},
{ mData: '_from',sClass: 'text-center'},
{ mData: 'usuario'},
{ mData: 'asunto'},
{ mData: 'estado',sClass: 'text-center'},
{ mData: 'fechaalta'},
{ mData: 'tool',sClass: 'text-center'}
],
initComplete: function(oSettings, json){

      if($("[name=\"tipoacceso\"]").val()>1){

         $("#data-reportes_wrapper .dt-buttons")
         .append('<div class="btn-group">\
      <button type="button" class="btn btn-default" onclick="new_edit(\''+$('[name="id_socio"]').val()+'\', null,\'new\')"><i class="fas fa-plus"></i> <b>Nuevo</b></button>\
    </div>');

      }

      }
 }).on('processing.dt', function (e, settings, processing) {
if (processing) {
loaderin('.panel-unidadesvoip');
}else{
loaderout('.panel-unidadesvoip');  
}
}).on( 'draw', function () {
$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
});
  
 }).on('processing.dt', function (e, settings, processing) {
if (processing) {
loaderin('.panel-unidades');
}else{
loaderout('.panel-unidades');  
}
}).on( 'draw', function () {
$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
});





function delete_re(id,t,n,s){  
$.confirm({
theme: 'supervan',
draggable: false,
closeIcon: true,
animationBounce: 2.5,
escapeKey: false,
content: 'Esta seguro que desea eliminar este reporte '+ id +'? ',
title: '<i class="fa fa-question-circle fa-lg icodialog"></i> Eliminar reporte',
buttons: {
Eliminar: {
text: '<i class="far fa-trash-alt icodialog"></i> Eliminar', // With spaces and symbols
action: function () {
$.post(base_url+"socios/delete_re", { id: id, token: t , nombre:n,id_socio:s})
  .done(function( data ) {
   if(data=='1'){
   alerta('exito',"Documento eliminada");
  updatereportes();
  }else{
    alerta('error',data);
  }
  });
 
}}}
}); 
}

function cerrar_re(id,t,n,s){  
$.confirm({
theme: 'supervan',
draggable: false,
closeIcon: true,
animationBounce: 2.5,
escapeKey: false,
content: 'Esta seguro que desea cerrar este reporte #'+ id +'? ',
title: '<i class="fa fa-question-circle fa-lg icodialog"></i> Cerrar reporte',
buttons: {
Eliminar: {
text: '<i class="far fa-trash-alt icodialog"></i> Cerrar', // With spaces and symbols
action: function () {
$.post(base_url+"socios/cerrar_re", { id: id, token: t , nombre:n,id_socio:s})
  .done(function( data ) {
   if(data=='2'){
  alerta('error',data);
  }else if(data=='ACCESO DENEGADO'){
  alerta('error',data);
  }else if(data=='1'){
  alerta('exito',"Reporte cerrado");
  updatereportes();
  }
  });
 
}}}
}); 
}

function new_edit(id_socio,id_doc,action){
    $("[name='id_socio']").val(id_socio)
    $("[name='action-modal']").val(action)
  if(action=="new"){
    $("#modal-new-edit .modal-title").html("Nuevo reporte")
    $("[name='estado-modal']").attr('disabled',true)
    $("[name='tipo-modal']").attr('disabled',false)
  }
 
   $("#modal-new-edit").modal()
}

function edit_re(id,t,s){
  $(location).attr('href',"#socios/view_reportes?id="+id+"&token="+t+"&socio="+s);
}

function addupload(){
$('#fileuploads').append('<input type="file" name="adjunto-modal[]" class="form-control-file" style="margin-bottom:10px;" capture="camera">')
}


 </script>
            
</div>



<div class="modal" id="modal-new-edit">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content"><div class="modal-header">
      <h5 class="modal-title">Nuevo Reporte</h5>
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
    </div>  

<form id="reportes-form" method="post" action="socios/new_reporte" class="form-horizontal" enctype="multipart/form-data">
<div class="modal-body row" style="width: 100%">

<input type="hidden" name="id_socio" value="<?=$id?>">
<input type="hidden" name="token" value="<?=$token?>">
<input type="hidden" name="action-modal">

<div class="col-md-6 m-b-5">
<label>Tipo</label>
<select class="form-control sl2" name="tipo-modal" required="" style="width: 100%">
<option value="VIAL">VIAL</option>
<option value="SERVICIO">SERVICIO</option>
<option value="ATENCIÓN">ATENCIÓN</option>
</select>

</div>


<div class="col-md-6 m-b-5">  
<label>Asunto</label>
<input class="form-control" name="asunto-modal" type="text" required="">
</div>
  

  
<div class="col-md-7 m-b-5">
<label>Archivo Ajdunto</label>
<div id="fileuploads">
  <input class="form-control-file" name="adjunto-modal[]" type="file" accept="image/*,.pdf,.zip" style="margin-bottom:10px" capture="camera">
</div>
</div>

<div class="col-md-5 m-b-5">
<a class="btn btn-white m-t-15" onclick="addupload()"><i class="fas fa-plus"></i> Añadir más</a>
</div>
  
  
<div class="col-lg-12 m-t-15">
<textarea class="textarea form-control" id="htmltk" name="descripcion-modal" rows="12" required="" placeholder="Ingrese detalles del reporte">
  
</textarea>

</div>
  
  
</div>

<div class="modal-footer">
<a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
<?php $button=""; if($tipoacceso>1){$button='<button type="submit" class="btn btn-sm btn-success btn-newservice btn-loader">Guardar</button>';};
echo $button; ?>
</div>

</form>

<style type="text/css">
.select2-dropdown {
    z-index: 1960 !important;
}
</style>

</div></div></div>



