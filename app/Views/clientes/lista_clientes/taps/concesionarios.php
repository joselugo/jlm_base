<style type="text/css">
  @media (max-width: 576px){ 
    #data-concesionarios_wrapper .dt-buttons{display: inline-table;}
    #data-concesionarios_wrapper #data-concesionarios_filter{margin-top: -20px; display:initial; }
    #data-concesionarios_filter label{width: 100%}
  }
  @media (max-width: 767.98px){
    #data-concesionarios_wrapper #data-concesionarios_filter{margin-top: -20px; width: 100%; display: inline-block;}
    #data-concesionarios_filter label{float: right;width: 101.5%}
  } 

</style>

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

function delete_concesionario(s,id,t,n){

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
$.post(base_url+ "socios/delete_concesionarios", {id_socio:s, id: id,nombre:n,action: "eliminar"})
  .done(function( data ) {
alsup.close();
if(data=='1'){
alerta('exito','Concesionario eliminado correctamente');
updateconcesionarios();
}else{
alerta('error',data);
}
  });
return false;
 
}}}
});


}



$(document).ready(function() {

configtable('#data-concesionarios','concesionarios');

  
concesionarios=$('#data-concesionarios').DataTable({
"ajax": "socios/get_concesionarios_json?id=<?=$id?>",
"deferRender": true,
"idDataTables": "1",
"aoColumns": [
{ mData: 'id'},
{ mData: 'nombre'},
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
  if($("[name=\"tipoacceso\"]").val()>1){
  $("#data-concesionarios_wrapper .dt-buttons")
         .append('<div class="btn-group">\
      <button type="button" class="btn btn-default" onclick="add_concesionario(\'new\')"><i class="fas fa-plus"></i> <b>Nuevo</b></button>\
    </div>');//------->Button Col
  }

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


$(".Select2").select2();

$('#form-concesionario-new').ajaxForm({
beforeSend: function(data) {
 // alerta("loader","") ;
},  
success: function(data) {  
if(data=='1'){
alerta('exito','Datos Guardados Correctamente');
$("#modal-new-concesionario").modal("hide");
updateconcesionarios(); 
}else{

  alerta('error',data);
 
}
},
error: function(response) {
$('#gritter-notice-wrapper').remove();
alerta('error500',response['responseText']);
}})



});

function add_concesionario(action,id,t,n,s){
   $('#form-concesionario-new')[0].reset();
   $('[name="idsocio"]').val("<?=$id?>") 
   $('[name="token"]').val("<?=$token?>") 

  if(action=="new"){$(".modal-title").html("Nuevo concesionario"); $('[name="action"]').val("new")}
    else {
      $(".modal-title").html("Editar concesionario")
      $('[name="action"]').val("update")
      $.ajax({
    data: {"id" : id, "token" : t},
    type: "POST",
    dataType: "json",
    async:false,
    url: base_url+ "socios/edit_concesionario",
})
 .done(function( data, textStatus, jqXHR ) {
     $('[name="idconcesionario"]').val(data.id) 
     $('[name="nombre"]').val(data.nombre)
     $('[name="apellidos"]').val(data.apellidos)
     $('[name="domicilio"]').val(data.domicilio)
     $('[name="movil"]').val(data.movil)
     $('[name="correo"]').val(data.correo)
     $('[name="ine"]').val(data.ine)
     $('[name="password"]').val(data.password)
    

     $('[name="status"] option[value="'+data.status+'"]').prop('selected', true).change();
     $('[name="avatar"] option[value="'+data.avatar+'"]').prop('selected', true).change();
 
     
    
 })
 .fail(function( jqXHR, textStatus, errorThrown ) {
     if ( console && console.log ) {
         console.log( "La solicitud a fallado: " +  textStatus);
     }
}); 
    }
  $("#modal-new-concesionario").modal();
}

</script>

<div class="modal" id="modal-new-concesionario" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Nuevo concesionario</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button></div>
<form id="form-concesionario-new" action="socios/new_concesionarios" method="post">
<div class="modal-body row">

<input type="hidden" name="idsocio" value="<?=$id?>">
<input type="hidden" name="idconcesionario">
<input type="hidden" name="token" value="<?=$token?>">
<input type="hidden" name="tipoacceso" value="<?=$tipoacceso?>">
<input type="hidden" name="action">

<div class="col-lg-6">

     <div class="form-group row">
            <label class="col-md-4 control-label text-right">Status</label>
            <div class="col-md-8">
            <select style="width: 100%;" class="vodiapicker form-control" name="status" required="">
              <option value="0">ACTIVO</option>
               <option value="1">SUSPENDIDO</option>
                <option value="2">RETIRADO</option>
            </select>
          
            </div>
        </div>
    
    <div class="form-group row">
        <label class="col-md-4 col-form-label text-right">Nombre</label>
        <div class="col-md-8">
            <input type="text" class="form-control" name="nombre" autofocus="" required="">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-4 col-form-label text-right">Apellidos</label>
        <div class="col-md-8">
            <input type="text" class="form-control" name="apellidos" autofocus="" required="">
        </div>
    </div>

    
    <div class="form-group row">
        <label class="col-md-4 col-form-label text-right">Domicilio</label>
        <div class="col-md-8">
            <input type="text" class="form-control" name="domicilio" autofocus="" required="">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-4 col-form-label text-right">INE</label>
        <div class="col-md-8">
            <input type="text" class="form-control" placeholder="Clave de elector" minlengt="18" maxlength="18" name="ine" autofocus="" required="">
        </div>
    </div>

</div>

    <div class="col-lg-6">

      <div class="form-group row">
        <label class="col-md-4 col-form-label text-right">Móvil</label>
        <div class="col-md-8">
            <input type="tel" minlengt="10" maxlength="10" class="form-control" name="movil" autofocus="" required="">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-4 col-form-label text-right">Correo</label>
        <div class="col-md-8">
            <input type="email" data-rule="email" class="form-control" name="correo" autofocus="" required="">
        </div>
    </div>
        
        <div class="form-group row">
            <label class="col-md-4 control-label text-right">Avatar</label>
            <div class="col-md-8">
            <select style="width: 100%;" class="vodiapicker form-control" name="avatar" required="">
                <option value="admin.png"  data-thumbnail="<?=base_url()?>public/images/avatar/admin.png">admin.png</option>
                <option value="avatar-1.png"  data-thumbnail="<?=base_url()?>public/images/avatar/avatar-1.png">avatar-1.png</option>
                <option value="avatar-2.png"  data-thumbnail="<?=base_url()?>public/images/avatar/avatar-2.png">avatar-2.png</option>
                <option value="avatar-3.png"  data-thumbnail="<?=base_url()?>public/images/avatar/avatar-3.png">avatar-3.png</option>
                <option value="avatar-4.png"  data-thumbnail="<?=base_url()?>public/images/avatar/avatar-4.png">avatar-4.png</option>
                <option value="avatar-5.png"  data-thumbnail="<?=base_url()?>public/images/avatar/avatar-5.png">avatar-5.png</option>
                <option value="avatar-6.png"  data-thumbnail="<?=base_url()?>public/images/avatar/avatar-6.png">avatar-6.png</option>
                <option value="avatar-7.png"  data-thumbnail="<?=base_url()?>public/images/avatar/avatar-7.png">avatar-7.png</option>
                <option value="avatar.png"  data-thumbnail="<?=base_url()?>public/images/avatar/avatar.png">avatar.png</option>
                <option value="avatar04.png"  data-thumbnail="<?=base_url()?>public/images/avatar/avatar04.png">avatar04.png</option>
                <option value="avatar2.png"  data-thumbnail="<?=base_url()?>public/images/avatar/avatar2.png">avatar2.png</option>
                <option value="avatar3.png"  data-thumbnail="<?=base_url()?>public/images/avatar/avatar3.png">avatar3.png</option>
                <option value="avatar5.png"  data-thumbnail="<?=base_url()?>public/images/avatar/avatar5.png">avatar5.png</option>
                <option value="hombre-soporte.png"  data-thumbnail="<?=base_url()?>public/images/avatar/hombre-soporte.png">hombre-soporte.png</option>
                <option value="hombre1.png"  data-thumbnail="<?=base_url()?>public/images/avatar/hombre1.png">hombre1.png</option>
                <option value="hombre2.png"  data-thumbnail="<?=base_url()?>public/images/avatar/hombre2.png">hombre2.png</option>
                <option value="mujer-soporte.png"  data-thumbnail="<?=base_url()?>public/images/avatar/mujer-soporte.png">mujer-soporte.png</option>
                <option value="mujer.png"  data-thumbnail="<?=base_url()?>public/images/avatar/mujer.png">mujer.png</option>
            </select>
          
            </div>
        </div>
        
       
<div class="form-group row">
    <label class="col-md-4 col-form-label text-right">Contraseña
     <button type="button" class="btn btn-icono btn-xs btn-map" data-toggle="tooltip" data-placement="top" data-original-title="Generar contraseña aleatoria" onclick="passwordaleatorio('password')" autocomplete="postal-code"><i class="fa fa-lock"></i></button>
     </label>
     <div class="col-md-8">
      <input type="text" class="form-control" name="password"  required="">
      </div>
</div>
        
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