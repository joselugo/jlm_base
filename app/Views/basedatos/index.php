 <!--<ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="#index/home">Inicio</a></li>
        <li class="breadcrumb-item">Sistema</li>
        <li class="breadcrumb-item active">Gestión de personal</li>
</ol> 
<h1 class="page-header">Operadores</h1>-->

<div class="row">

  <div class="col-md-6 ui-sortable">
    <div class="panel panel-inverse">
      <div class="panel-heading ui-sortable-handle">
      <h4 class="panel-title">Crear &amp; Eliminar Campos</h4>
      </div>
      <div class="panel-body">
      <small style=" font-size: 11px">Aquí se puede crear nuevos campos para la tabla "usuarios", por ejemplo si deasamos tener un campo en los datos del cliente llamado "colonia", solo debemos indicar el nombre y crear. No utilice Espacios o caracteres especiales en el nombre del campo en vez de espacios podria utilizar "_" por ejemplo: nuevo_campo. </small><br>

      <small class="text-danger" style=" font-size: 11px"><b>¡Atención!</b> solo debe eliminar campos que usted Ha creado, eliminar cualquier otro campo podría provocar un mal funcionamiento del sistema </small><br><br>
        
      <form id="nuevo_campo_form" method="post" action="bdatabase/new_campo" class="form-horizontal">
      <div class="form-group float-left input-group-sm col-sm-8">
      <input class="form-control" id="nuevo_campo" name="nuevo_campo" required type="text" autocomplete="off" placeholder="nuevo_campo">
      </div>

      <div class="form-group float-left input-group-sm col-sm-4">
      <button type="submit" class="btn btn-info"><i class="fa fa-save"> Crear campo</i></button>
      </div>
      </form>
        <div class="form-group float-left input-group-sm col-sm-8">
        <select class="form-control slcol select2-hidden-accessible" id="eliminar_campo" name="eliminar_campo" data-placeholder="Seleccionar campo" onchange="$('.btnremovecampo').prop('disabled', false);" style="width: 100%" data-select2-id="eliminar_campo" tabindex="-1" aria-hidden="true">
          <option value=""></option>
          <?=$tblcolumns?>
        </select>
        </div>
        <button class="btn btn-danger btnremovecampo" onclick="deletecampo()"><i class="fa fa-trash"> Eliminar campo</i></button>
      </div>
    </div>
  </div>
  <div class="col-md-6 ui-sortable">
    <div class="panel panel-inverse">
        <div class="panel-heading ui-sortable-handle">
        <h4 class="panel-title">Eliminar datos</h4>
        </div>
        <div class="panel-body">
        <small style=" font-size: 11px">Aqui se puede vaciar los datos de una tabla y reiniciar la numeracion(ID), por ejemplo para borrar todos los usuarios seleccionar "usuarios".</small><br>

        <small class="text-danger" style=" font-size: 11px"><b>¡Atención!</b> Se debe tener cuidado de no eliminar los datos de tabla importantes. </small><br><br>
          
          
        <div class="form-group input-group-sm col-sm-8 float-left">
        <select class="form-control slempty select2-hidden-accessible" id="tablas" name="tablas" data-placeholder="Seleccionar tabla" onchange="$('.btnemptytable').prop('disabled', false);" style="width: 100%" data-select2-id="tablas" tabindex="-1" aria-hidden="true">
          <option value=""></option>
          <?=$table_name?>
        </select>
        </div>
          
        <div class="form-group input-group-sm col-sm-4 float-left">
        <button type="button" class=" btn btn-danger btn-sm btnemptytable" onclick="emptytable()"><i class="fa fa-trash"> Eliminar Datos</i></button>
        </div>
          
        </div>
    </div>
    <!-- -->
  </div>
  <div class="col-md-12 ui-sortable">
    <div class="panel panel-default">
      <div class="panel-heading">
      <h4 class="panel-title"><b>BASE DATOS</b></h4>
      <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload" onclick="updatetables()"><i class="fas fa-sync-alt"></i></a>
      </div>

      </div>
      <div class="panel-body border-panel">

      <table id="data-operadores" class="display nowrap table table-striped table-bordered dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%" role="grid" aria-describedby="data-operadores_info" style="width: 100%;">
      <thead>
      <tr role="row">
      <th>Tablas</th>
      <th>Filas</th>
      <th>Tamaño</th>
      </tr>
      </thead>

      </table>

      </div>
    </div>
  </div>
</div>



<script>
var base_url="<?=base_url();?>"  
var new_campo = document.getElementById('nuevo_campo');

$('.slcol').select2();
$('.slempty').select2();

$(function() {//bloquear tecla espacio
    $('#nuevo_campo').on('keypress', function(e) {
        if (e.which == 32)
            return false;
    });
});

function cleaning(){
 new_campo.value="";
 
}

function emptytable(){

  $.confirm({
theme: 'supervan',
draggable: false,
closeIcon: true,
animationBounce: 2.5,
escapeKey: false,
content: 'Está seguro de reestablecer la tabla? todos los datos guardados serán eliminados',
title: '<i class="far fa-question-circle fa-lg icodialog"></i> Eliminar',
buttons: {
Eliminar: {
text: '<i class="far fa-trash-alt icodialog"></i> Eliminar', // With spaces and symbols
action: function () {
  $.post( "bdatabase/emptytable", { tablas: $('#tablas').val()})
 .done(function( data ) {
    $('.slempty').select2('destroy');
    $('#tablas option[value='+$('#tablas').val()+']').remove();
    $('.btnemptytable').prop('disabled', true);
    alerta('exito','Registros eliminados correctamente');	
    $('#tablas option[value=""]').remove();
    $('.slempty').select2();
  });
}}}
});	


}

function deletecampo(){
$.confirm({
theme: 'supervan',
draggable: false,
closeIcon: true,
animationBounce: 2.5,
escapeKey: false,
content: 'Está seguro de eliminar este campo personalizado? todos los datos guardados serán eliminados',
title: '<i class="far fa-question-circle fa-lg icodialog"></i> Eliminar',
buttons: {
Eliminar: {
text: '<i class="far fa-trash-alt icodialog"></i> Eliminar', // With spaces and symbols
action: function () {
$.post( "bdatabase/delete_campo", { eliminar_campo: $('#eliminar_campo').val()})
  .done(function( data ) {
$('.slcol').select2('destroy');
$('#eliminar_campo option[value='+$('#eliminar_campo').val()+']').remove();
$('.btnremovecampo').prop('disabled', true);
alerta('exito','Campo eliminado correctamente');
$('#eliminar_campo option[value=""]').remove();
$('.slcol').select2();
	
  });
 
}}}
});	
}

$(function(){
  $('#nuevo_campo_form').ajaxForm({//Guardar nueva columna en tabla existente
  success: function(data) {
  $('#gritter-notice-wrapper').remove();
  if(data =='1'){
  alerta('error',data);
  }else{
    $('.slcol').select2('destroy');
    $('#eliminar_campo').append($('<option>', {
                    value: $('#nuevo_campo').val(),
                    text: $('#nuevo_campo').val(),
                }));
    alerta('exito','Campo creado correctamente');
    $('.slcol').select2();
    $('#nuevo_campo').val('');	
  cleaning();
  }
  }
  });
  

App.setPageTitle('Gestión personal');

configtable('#data-operadores','Operadores');
  
var operadores=$('#data-operadores').DataTable({
"ajax": base_url + "bdatabase/view_tables",
"deferRender": true,
"idDataTables": "1",
"aoColumns": [
{ mData: 'table_name'},
{mData: 'table_rows'},
{mData: 'data_length'}
],
initComplete: function(oSettings, json){
      $("#data-operadores_wrapper .dt-buttons")
         .append('<div class="btn-group"><a class="btn btn-success" href="#bdatabase/create_backup"><i class="fa fa-database"></i> Generar Backup</a></div>');
      } 
}).on( 'draw', function () {
$('img[data-name]').initial({
height:64,
width:64,
charCount:2,
fontSize:24,
fontWeight:600
});
});
  
updatetables=function(){
operadores.ajax.reload( null, false );
$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
}
  
})
  
</script>
