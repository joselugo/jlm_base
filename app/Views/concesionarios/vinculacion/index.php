
<h1 class="page-header">Vincular Concesionario</h1>

<form class="frmvinculacion form-horizontal" action="concesionarios/add_vinculo" method="post">
<div class="row">
	
<div class="col-md-6 ui-sortable">
<div class="panel panel-inverse">
  <div class="panel-heading ui-sortable-handle">
    <h4 class="panel-title">Datos del concesionario</h4>
  </div>
  <div class="panel-body">
	  

<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Folio</label>
<div class="col-md-8">

<div class="input-group mb-3">
  <input type="text" class="form-control" name ="id-con" readonly="" required>
    <div class="input-group-append">
 
   <button style="float: left;" type="button" class="btn btn-outline-secondary" data-toggle="tooltip" data-placement="top" 
   data-original-title="Seleccionar concesionario" onclick="viewcon();" 
   data-loading-text="<i class=&quot;fas fa-spinner fa-spin&quot;></i>"><i class="fas fa-search"></i></button>

  </div>
</div>
</div>
</div>

<div class="form-group row">
<label class="col-form-label col-md-4">Nombre</label>
<div class="col-md-8">
<input type="text" class="form-control" name="nombre-con"  readonly="">
</div>
</div>

<div class="form-group row">
<label class="col-form-label col-md-4">Domicilio</label>
<div class="col-md-8">
<input type="text" class="form-control" name="domicilio-con"  readonly="">
</div>
</div>

<div class="form-group row">
<label class="col-form-label col-md-4">Movil</label>
<div class="col-md-8">
<input type="text" class="form-control" name="movil-con"  readonly="">
</div>
</div>
	  
<div class="form-group row">
<label class="col-form-label col-md-4">Socio</label>
<div class="col-md-8">
<input type="text" class="form-control" name="socio-con"  readonly="">
<input type="hidden" class="form-control" name="id-so"  readonly="">
</div>
</div>

<div class="form-group row">
<label class="col-form-label col-md-4">Sindicato</label>
<div class="col-md-8">
<input type="email" class="form-control" name="sindicato-con" readonly="">
</div>
</div>
	  
</div>
	
<div class="panel-footer text-right" style="height: 58px">
  
</div>
</div>
</div>	
	
<div class="col-md-6 ui-sortable">
<div class="panel panel-inverse">
  <div class="panel-heading ui-sortable-handle">
    <h4 class="panel-title">Datos de la  unidad</h4>
  </div>
  <div class="panel-body">


<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Folio</label>
<div class="col-md-8">

<div class="input-group mb-3">
  <input type="text" class="form-control" name ="id-u"  readonly="">
    <div class="input-group-append">
 
   <button style="float: left;" type="button" class="btn btn-outline-secondary" data-toggle="tooltip" data-placement="top" 
   data-original-title="Seleccionar unidad" onclick="viewuni();" 
   data-loading-text="<i class=&quot;fas fa-spinner fa-spin&quot;></i>"><i class="fas fa-search"></i></button>

  </div>
</div>
</div>
</div>    


<div class="form-group row">
<label class="col-form-label col-md-4">Matricula</label>
<div class="col-md-8">
<input type="email" class="form-control" name="matricula-u" readonly="">
</div>
</div>

<div class="form-group row">
<label class="col-form-label col-md-4">Estado</label>
<div class="col-md-8">
<input type="email" class="form-control" name="estado-u" readonly="">
</div>
</div>
	  
<div class="form-group row">
<label class="col-form-label col-md-4">Modo</label>
<div class="col-md-8">
<input type="email" class="form-control" name="modo-u" readonly="">
</div>
</div>

<div class="form-group row">
<label class="col-form-label col-md-4">Socio</label>
<div class="col-md-8">
<input type="email" class="form-control" name="socio-u"  readonly="">
</div>
</div>

<div class="form-group row">
<label class="col-form-label col-md-4">Sindicato</label>
<div class="col-md-8">
<input type="email" class="form-control" name="sindicato-u" readonly="">
</div>
</div>
	  
</div>
<div class="panel-footer text-right">
    <button type="submit" class="btn-azul p-10 btsavedata btn-loader" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Guardando cambios..."><i class="far fa-save fa-lg"></i> Guardar datos</button>
</div>
</div>
</div>
	
</div>
</form>
<div class="row">
  
<div class="col-md-12 ui-sortable">
<div class="panel panel-inverse">
  <div class="panel-heading ui-sortable-handle">
    <h4 class="panel-title">Unidades viculadas</h4>
  </div>
<div class="panel-body">
    

<table id="data-unidades-vin" class="display nowrap table table-striped table-bordered dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%">
<thead>
<tr role="row">
  <th  class="all sorting_asc" aria-sort="ascending">ID</th>
  <th  class="sorting" >Tipo</th>
  <th  class="sorting">Matrícula</th>
  <th class="desktop sorting" >Socio</th>
  <th  class="sorting">Concesionario</th>
  <th class="desktop sorting" >N° Afiliado</th>
  <th class="desktop sorting">Modo</th>
  <th class="text-center sorting">Estado</th>
  <th class="all text-center sorting" style="min-width: 20px; max-width: 20px; width: 20px">Acción</th>
</tr>
</thead>
</table>
    
</div>
  
<div class="panel-footer text-right" style="height: 58px">
  
</div>
</div>
</div>  
  
</div>

<script>
var concesionarios;
var unidades
var unidadesvin
	
$(function(){

configtable('#data-concesionarios','concesionarios');

  
concesionarios=$('#data-concesionarios').DataTable({
"ajax": "concesionarios/get_concesionarios_json_vinculacion",
"deferRender": true,
"idDataTables": "1",
 "responsive": true,
"aoColumns": [
{ mData: 'id'},
{ mData: 'nombre'},
{ mData: 'idsocio'},
{ mData: 'idsindicato'},
{ mData: 'movil'},
{ mData: 'status',sClass: 'text-center',sWidth:'30px'},
{ mData: 'id','render': function ( data, type, full, meta ) {
  var coltool=data.split('-');
  return '<input type="radio" class="flat-red" id="flat-red" name="slactivo[]" value="'+data+'" data-nombre="'+full['nombre']+'" data-dom="'+full['domicilio']+'" data-movil="'+full['movil']+'" data-socio="'+full['idsocio']+'" data-sin="'+full['idsindicato']+'" data-id-so="'+full['id_socio']+'">';
},sClass: 'text-center'
   } 
],
"fnDrawCallback": function( oSettings ) {
$('input[type="radio"].flat-red').iCheck({   radioClass: 'iradio_flat-blue',increaseArea: '-10%' })
//$('input[type="radio"].flat-red').on( "ifChecked", function( e ) {}); funcion cuando se hace click en iCheck
 
},
}).on( 'draw', function () {
$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
} );



configtable('#data-unidades','unidades');

  
unidades=$('#data-unidades').DataTable({
"ajax": "unidades/get_unidades_json_vinculacion?estado=ACTIVO",
"deferRender": true,
"aoColumns": [
{ mData: 'id', sWidth : "10px"},
{ mData: 'tipo'},
{ mData: 'matricula'},
{ mData: 'socio'},
{ mData: 'codigo',sClass: 'text-center'},
{ mData: 'sindicato'},
{ mData: 'modounidad',sClass: 'text-center'},
{ mData: 'estado',sClass: 'text-center'},
{ mData: 'id','render': function ( data, type, full, meta ) {
  var coltool=data.split('-');
  return '<input type="radio" class="flat-red1" id="flat-red1" name="slactivo[]" value="'+data+'" data-matricula="'+full['matricula']+'" data-estado="'+full['estado1']+'" data-modo="'+full['modounidad1']+'" data-socio="'+full['socio1']+'" data-sin="'+full['sindicato']+'">';
},sClass: 'text-center'
   } 
],
"fnDrawCallback": function( oSettings ) {
$('input[type="radio"].flat-red1').iCheck({   radioClass: 'iradio_flat-blue',increaseArea: '-10%' })
//$('input[type="radio"].flat-red').on( "ifChecked", function( e ) {}); funcion cuando se hace click en iCheck
 
},
}).on( 'draw', function () {
$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
} );


configtable('#data-unidades-vin','unidadesvin');

  
unidadesvin=$('#data-unidades-vin').DataTable({
"ajax": "unidades/get_unidades_json_vinculadas_table?estado=ACTIVO",
"deferRender": true,
"aoColumns": [
{ mData: 'id', sWidth : "10px"},
{ mData: 'tipo'},
{ mData: 'matricula'},
{ mData: 'socio'},
{ mData: 'concesionario'},
{ mData: 'codigo',sClass: 'text-center'},
{ mData: 'modounidad',sClass: 'text-center'},
{ mData: 'estado',sClass: 'text-center'},
{ mData: 'tool',sClass: 'text-center'},
]
}).on( 'draw', function () {
$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
} );



updateunidadesvin=function(){
unidadesvin.ajax.reload( null, false );
}

	
$('.frmvinculacion').ajaxForm({
success: function(data) {
if(data=="success"){
alerta("exito","Datos guardados correctamente");
updateunidadesvin()
}
else if("existe"){
alerta('warning','Ya esta viculada esta unidad a un concesionario');
}
else{
alerta('error',data);
}  
$('.btsavedata').button('reset'); 
},
beforeSubmit: function() {
$('.btsavedata').button('loading');  
if($("[name='id-con']").val()=="" || $("[name='id-u']").val()==""){
  alerta('warning','Acomplete los campos');
  $('.btsavedata').button('reset');
  return false

}
else{
  if($("[name='socio-con']").val() == $("[name='socio-u']").val() && $("[name='sindicato-con']").val() == $("[name='sindicato-u']").val()){
   
     return true
  }else{
    alerta('error','No puedes vicular la unidad. El socio o sindicato no son los mismos');
    $('.btsavedata').button('reset');
    return false
  }
 
}

$('.btsavedata').button('reset'); 

},
error: function(response) {
$('#gritter-notice-wrapper').remove();
alerta('error500',response['responseText']);
$('.btsavedata').button('reset');
}})
	
})

function viewcon(){
  $("#modal-con").modal();
  $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
}

function viewuni(){
  $("#modal-uni").modal();
  $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
}

selec_con=function(){ 
var id="";
var nombre
var domicilio
var movil
var socio
var sindicato
var id_socio
$('input[name^="slactivo"]:checked').each(function() {
    id=$(this).val();
    nombre=$(this).attr("data-nombre");
    domicilio=$(this).attr("data-dom");
    movil=$(this).attr("data-movil");
    socio=$(this).attr("data-socio");
    sindicato=$(this).attr("data-sin");
    id_socio=$(this).attr("data-id-so");
});

$("[name='id-con']").val(id);
$("[name='nombre-con']").val(nombre);
$("[name='domicilio-con']").val(domicilio);
$("[name='movil-con']").val(movil);
$("[name='socio-con']").val(socio);
$("[name='sindicato-con']").val(sindicato);
$("[name='id-so']").val(id_socio);
$('#modal-con').modal('toggle');


}

selec_uni=function(){ 
var id="";
var matricula
var estado
var modo
var socio
var sindicato
$('input[name^="slactivo"]:checked').each(function() {
    id=$(this).val();
    matricula=$(this).attr("data-matricula");
    estado=$(this).attr("data-estado");
    modo=$(this).attr("data-modo");
    socio=$(this).attr("data-socio");
    sindicato=$(this).attr("data-sin");
});

$("[name='id-u']").val(id);
$("[name='matricula-u']").val(matricula);
$("[name='estado-u']").val(estado);
$("[name='modo-u']").val(modo);
$("[name='socio-u']").val(socio);
$("[name='sindicato-u']").val(sindicato);
$('#modal-uni').modal('toggle');


}

function delete_vinculo(id,t,s){

var alsup=$.confirm({
theme: 'supervan',
draggable: false,
closeIcon: true,
animationBounce: 2.5,
escapeKey: false,
content: '¿Esta seguro que desea eliminar esta vinculación de la  unidad? <br><b class="text-warning">#'+id,
title: '<i class="far fa-question-circle fa-lg icodialog"></i> ELIMINAR',
buttons: {
Eliminar: {
text: '<i class="far fa-trash-alt"></i> Si, eliminar',
action: function () {
this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...'); 
this.buttons.Eliminar.disable(); 
$('.jconfirm-closeIcon').remove();
$.post(base_url+ "concesionarios/delete_vinculo", {id:id, token: t,id_socio:s,action: "eliminar"})
  .done(function( data ) {
alsup.close();
if(data=='success'){
alerta('exito','Vínculo eliminado correctamente');
updateunidadesvin();
}else{
alerta('error',data);
}
  });
return false;
 
}}}
});


}

	
</script>

<div class="modal" id="modal-con" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Seleccionar</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button></div>

<div class="modal-body">

<table id="data-concesionarios" class="table table-striped table-bordered dt-responsive nowrap dataTable dtr-inline collapsed" style="width: 100%;">
<thead>
<tr role="row">
<th class="all sorting_asc" aria-sort="ascending">Id</th>
<th class="all sorting" style="min-width: 100px; width: 100px;">Nombre</th>
<th class="sorting">Socio</th>
<th class="sorting">Sindicato</th>
<th class="sorting">Movil</th>
<th class="sorting">Status</th>
<th class="all sorting_disabled" style="min-width: 30px; max-width:30px; width: 30px;" data-orderable="false">Acción</th>
</tr>
  
</thead>  
</table>  

</div>

<div class="modal-footer">
<a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
<button type="submit" class="btn btn-sm btn-success btn-newservice btn-loader" onclick="selec_con()">Seleccionar</button>

</div>



</div>
</div>
</div>

<div class="modal" id="modal-uni" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Seleccionar</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button></div>

<div class="modal-body">

<table id="data-unidades" class="display nowrap table table-striped table-bordered dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%">
<thead>
<tr role="row">
  <th  class="all sorting_asc" aria-sort="ascending">ID</th>
  <th  class="sorting" >Tipo</th>
  <th  class="sorting">Matrícula</th>
  <th class="desktop sorting" >Socio</th>
  <th class="desktop sorting" >N° Afiliado</th>
  <th class="desktop sorting" >Sindicato</th>
  <th class="desktop sorting">Modo</th>
  <th class="all text-center sorting">Estado</th>
  <th class="all text-center sorting_disabled" style="max-width: 40px !important; width: 40px;" data-orderable="false">Acción</th>
</tr>
</thead>
</table>

</div>

<div class="modal-footer">
<a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
<button type="submit" class="btn btn-sm btn-success btn-newservice btn-loader" onclick="selec_uni()">Seleccionar</button>

</div>



</div>
</div>
</div>
