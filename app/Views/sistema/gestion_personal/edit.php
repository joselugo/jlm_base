<link href="<?=base_url()?>/public/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet">

<style type="text/css">
.card-body .switchery>small,.horario .switchery>small {
    height: 13px;
    width: 13px;
}
  .card-body div{
  
    float: left;
  }
  .card-body{
display: inline-block;
    width: 100%;
    padding: 5px;
  }
.jstree-leaf a.jstree-anchor{
font-size: 11px;
  }
  
.card-body .switchery,.horario .switchery  {
    height: 13px;
    width: 25px;
}

.jstree-default .jstree-open>.jstree-ocl {
    background-position: unset;
  display: none;
}
.jstree-default .jstree-node {
    margin-left: 0px;
}
.jstree-default .jstree-anchor {
    margin-left: 4px;
}

.jstree-default .jstree-clicked,.jstree-default .jstree-hovered, .jstree-default .jstree-wholerow-clicked {
    background: unset !important;
    box-shadow: none!important;
  cursor:unset;
}
  legend{
      font-size: 14px;
  padding-bottom: 0px;
  }
  
</style>
  
<ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="#index/home">Inicio</a></li>
        <li class="breadcrumb-item"><a href="#sistema/gestion_personal">Lista operadores</a></li>
        <li class="breadcrumb-item active">Editar Operador</li>
</ol> 
<h2 class="page-header"><?=$nombre?></h2>

<div class="panel panel-blue">
<div class="panel-heading">
<h4 class="panel-title">Editar Operador</h4>
</div>
<form id="form-login-perfil" action="sistema/update_perfil" method="post">
<div class="panel-body row">

  
<div class="col-lg-6">
  
<legend class="m-b-15">Datos</legend>

<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Sucursal</label>
<div class="col-md-8">
<select class="form-control sl2" style="width: 100%" name="sucursal_origen">
  <?=$sucursales_by_user?>
</select>

</div>
</div>


<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Nombre</label>
<input type="hidden" name="id" id="id" required="" value="<?=$id?>">
<div class="col-md-8">
<input type="text" class="form-control" name="nombre" autofocus="" required="" value="<?=$nombre?>">
</div>
</div>
  
<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Usuario</label>
<div class="col-md-8">
<input type="text" class="form-control no" name="username" required="" value="<?=$username?>">
</div>
</div>
  
<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Email</label>
<div class="col-md-8">
<input type="email" class="form-control" name="correo" required="" value="<?=$correo?>">
</div>
</div>
  
<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Télefono móvil</label>
<div class="col-md-8">
<input type="text" class="form-control" name="movil" value="<?=$movil?>">
</div>
</div>
  
<legend class="m-b-15">Configuración</legend>
  
<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Permisos Sucursal</label>
<div class="col-md-8">
<select name="sucursales[]"  multiple style="width: 100%" class="slopsucursal">
<option value="%%" class="allnodo">Todos</option>
<?=$sucursales?>
</select>
</div>
</div>

<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Tipo de Operador</label>
<div class="col-md-8">
<select name="rol" style="width: 100%" class="sl2">
<?=$roles?>
</select>
</div>
</div>
  
<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Estado</label>
<div class="col-md-8">
<select name="estado" style="width: 100%" class="sl2">
<option value="1">Habilitado</option>
<option value="0">Deshabilitado</option>
</select>
</div>
</div>

<legend class="m-b-15">Nueva Contraseña</legend>

<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Contraseña</label>
<div class="col-md-8">
<input type="password" class="form-control" name="newpassword" autocomplete="new-password">
</div>
</div>
  
<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Repite contraseña</label>
<div class="col-md-8">
<input type="password" class="form-control" name="newpassword2" autocomplete="new-password2">
</div>
</div>
  
<div class="text-right">
<?php $button=""; if($tipoacceso>1){$button='<button type="submit" class="btn-azul btn-import">Guardar</button>';}?>
<?=$button?>
<a href="#sistema/gestion_personal" class="btn btn-azul" style="background-color:transparent; color:#0070BA;">Cancelar</a>
</div>
  
</div>
  
<div class="col-lg-6">
  
<legend class="m-b-15">Permisos</legend>  
  

<?=$permisos?> 

  
</div>
</div>  
</form>

</div>
<script src="<?=base_url()?>/public/plugins/jstree/dist/jstree.min.js"></script>

<script>
var base_url="<?=base_url()?>";
var array_permisos=""; 
function hedpermisos(){
$('.h-113').html('Activos <b>'+$('.group-113:checked').length+' de '+$('.group-113').length+'</b>');
$('.h-118').html('Activos <b>'+$('.group-118:checked').length+' de '+$('.group-118').length+'</b>');
$('.h-11').html('Activos <b>'+$('.group-11:checked').length+' de '+$('.group-11').length+'</b>');
$('.h-92').html('Activos <b>'+$('.group-92:checked').length+' de '+$('.group-92').length+'</b>');
$('.h-27').html('Activos <b>'+$('.group-27:checked').length+' de '+$('.group-27').length+'</b>');
$('.h-5').html('Activos <b>'+$('.group-5:checked').length+' de '+$('.group-5').length+'</b>');
$('.h-72').html('Activos <b>'+$('.group-72:checked').length+' de '+$('.group-72').length+'</b>');
$('.h-96').html('Activos <b>'+$('.group-96:checked').length+' de '+$('.group-96').length+'</b>');
$('.h-10').html('Activos <b>'+$('.group-10:checked').length+' de '+$('.group-10').length+'</b>');
$('.h-95').html('Activos <b>'+$('.group-95:checked').length+' de '+$('.group-95').length+'</b>');
$('.h-83').html('Activos <b>'+$('.group-83:checked').length+' de '+$('.group-83').length+'</b>');
$('.h-1000').html('Activos <b>'+$('.group-1000:checked').length+' de '+$('.group-1000').length+'</b>');
$('.h-2').html('Activos <b>'+$('.group-2:checked').length+' de '+$('.group-2').length+'</b>');
$('.h-3').html('Activos <b>'+$('.group-3:checked').length+' de '+$('.group-3').length+'</b>');
$('.h-4').html('Activos <b>'+$('.group-4:checked').length+' de '+$('.group-4').length+'</b>');
$('.h-5').html('Activos <b>'+$('.group-5:checked').length+' de '+$('.group-5').length+'</b>');
$('.h-6').html('Activos <b>'+$('.group-6:checked').length+' de '+$('.group-6').length+'</b>');
$('.h-7').html('Activos <b>'+$('.group-7:checked').length+' de '+$('.group-7').length+'</b>');
$('.h-8').html('Activos <b>'+$('.group-8:checked').length+' de '+$('.group-8').length+'</b>');
$('.h-9').html('Activos <b>'+$('.group-9:checked').length+' de '+$('.group-9').length+'</b>');
}
  
$(document).on('click', '.switchery', function(e) {
hedpermisos();  
});
  
function selectgroup(g,v){
$('.switchery').remove();
$(g).prop("checked",v);
var elems = Array.prototype.slice.call(document.querySelectorAll('.chk'));
elems.forEach(function(html) {
var switchery = new Switchery(html, { size: 'small' ,color: 'rgb(19, 131, 220)'});
});
hedpermisos();
}

function check_permisos(array_permisos){
  for(var i=0;i<array_permisos.length;i++)
    {   
        $('#accordion-permisos .chk').each(function(){
        if($(this).val()==array_permisos[i]['idpermiso']){$(this).prop("checked",true)}
        });

        $('#accordion-permisos .slc').each(function(){
        if($(this).attr('data-id')==array_permisos[i]['idpermiso']){$(this).val(array_permisos[i]['tipopermiso'])}
        });
      
    }
  
var elems = Array.prototype.slice.call(document.querySelectorAll('#accordion-permisos .chk'));
elems.forEach(function(html) {
var switchery = new Switchery(html, { size: 'small' ,color: 'rgb(19, 131, 220)'});
});
hedpermisos(); 
}

$('#form-login-perfil').ajaxForm({
beforeSend: function(data) {
   alerta("loader","") ;
  if($('input[name="newpassword"]').val()=="" && $('input[name="newpassword2"]').val()==""){}
  else{ 
    
      if($('input[name="newpassword"]').val()==$('input[name="newpassword2"]').val()){}
      else{$('#gritter-notice-wrapper').remove(); alerta('error','Las contraseñas no coinciden');  return false}
  }
},  
success: function(data) {  
if(data=='error'){
alerta('error',data); 
}else{
update_permisos_tabla();
}
},
error: function(response) {
$('#gritter-notice-wrapper').remove();
alerta('error500',response['responseText']);
}})


function update_permisos_tabla(){
  
  var listaPermisos = [];
  var id_usuario = $("#id").val();
    $( "#accordion-permisos .contenedores" ).each(function( index ) {

     var selector_id_permiso = $(this).find('input:checked');

     if ( selector_id_permiso.is(":checked") ){


     var id_permiso = $(this).find('input:checkbox').val();
     var tipoacceso = $(this).find('select[class=slc]').val();

     console.log(id_permiso+ " "+tipoacceso)
      var filaItem = new Permiso(id_permiso,id_usuario,tipoacceso);
      listaPermisos.push(filaItem);

      }    
       
  });

  var permisosJSON = JSON.stringify(listaPermisos);
  $.post(base_url + 'sistema/update_permisos_tabla', {permisos: permisosJSON,id_usuario:id_usuario},function(data) {
        if(data=="1"){$('#gritter-notice-wrapper').remove();alerta('exito','Datos actualizados correctamente');}
        else{alerta('error','Error al actualizar datos') }
             

  });  

}

function Permiso(id_permiso,id_usuario,tipoacceso) {
   this.id_permiso = id_permiso;
   this.id_usuario = id_usuario;
   this.tipoacceso = tipoacceso;
 }

$('[name="rol"]').change(function() {
$('#accordion-permisos .switchery').remove();
$('#accordion-permisos .chk').prop("checked",false);
$.post(base_url + "sistema/get_permisos_rol", { id_rol: $(this).val() }, function( data ) {
check_permisos(data);
}, "json");
});

  
(function ($, undefined) {
    "use strict";
    $.jstree.plugins.noclose = function () {
        this.close_node = $.noop;
    };
})(jQuery);

$('.card-body div').jstree({
        "core": {
            "themes": {
                "responsive": true,
        "icons":false
            }
        },
        "types": {
            "default": {
                "icon": "fas fa-caret-right text-black",
            }
        },
      "plugins": ["noclose","types"]
    });
  



$('[name="rol"] option[value="<?=$rol?>"]').prop('selected', true);

$('[name="estado"] option[value="<?=$estado?>"]').prop('selected', true);
  
$('.slopsucursal option[value="<?=$sucursal?>"]').prop('selected', true);

$('[name="sucursal_origen"] option[value="<?=$sucursal_origen?>"]').prop('selected', true);

var elems = Array.prototype.slice.call(document.querySelectorAll('.chk'));
elems.forEach(function(html) {
var switchery = new Switchery(html, { size: 'small' ,color: 'rgb(19, 131, 220)'});
});
  


var selectedValues = new Array();
var nodo="<?=$sucursal?>";
if(nodo=="%%"){selectedValues=nodo;}
else{
var item=nodo.split(',');
for (i=0;i<item.length;i++){
     selectedValues[i] = item[i];
  }
}
     
$('.slopsucursal,.sl2').select2();
$(".slopsucursal").val(selectedValues).change();

$('.slopsucursal').on('select2:select', function (e) { 
$('.slopsucursal').select2('destroy');
$('.slopsucursal').select2();
});
  
$('.slopsucursal').change(function() {
if($('.allnodo').is(':selected') ){
$(".slopsucursal > option").prop("selected",false);
$('.slopsucursal > option[value="%%"]').prop("selected","selected");
}else{
$('.slopsucursal > option[value="%%"]').prop("selected",false);
}
});


  
hedpermisos();
App.restartGlobalFunction();
App.setPageTitle('Editar Operador');
 $( document ).ready(function() {
$('.datet').datetimepicker({
format: 'LT',
})
});   
</script>
