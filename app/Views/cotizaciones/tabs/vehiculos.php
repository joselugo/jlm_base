<div class="box box-flat box-solid">
<div class="box-header with-border">
<h3 class="box-title">Mis vehiculos</h3>
</div>
<div class="box-body">
<table id="list-vehiculos" class="table-hover table table-striped table-bordered dt-responsive nowrap compact" cellspacing="0" width="100%" data-order="[[ 1, &quot;asc&quot; ]]">
<thead >
<tr>
<th class="column" data-orderable="false" style="min-width:46px !important;max-width:46px !important">Id</th>

<th class="column">Matricula</th><th  class="column">Status</th>
<th class="column">Marca</th><th  class="column">Año</th><th  class="column">Modelo</th><th  class="column">Motor</th>
<th class="column"># Serie</th><th  class="column">Color</th><th  class="column">Transmisión</th><th  class="column">Tipo</th>
<th  class="column">Alta</th>

<th class="all column" data-orderable="false" style="min-width:46px !important;max-width:46px !important">Acción</th>
</tr>
</thead>
</table>
</div>




<script src="<?=base_url();?>js/levas/vehiculos.js"></script>
 <script>

//--->funciones
var updatelist_v;
var new_vehiculo;
var vehiculos;
var base_url="<?=base_url();?>";
var id_cliente="<?php echo $id;?>";

$(function(){

//-------> LISTA DE SUCURSALES
  $('#modal-new-cliente').on('show.bs.modal', function () {
    $.post( "<?=base_url();?>clientes/get_sucursal", { action: "modalcol" })
    .done(function( datas ) {
       $('#id_sucursal').html(datas);
       console.log(datas)
    });
 })

//-------> LISTA DE ACTIVOS
vehiculos=$('#list-vehiculos').DataTable( {
"language": {
 "url": "<?=base_url();?>public/js/Datatables-es.json"
        },
"ajax": "<?=base_url();?>vehiculos/get_vehicle_json_by_id/"+id_cliente+"/",
"deferRender": true,
"stateSave": true,
"aoColumns": [
{ mData: 'id','render': function ( data, type, full, meta ) {var coltool=data.split('-'); return coltool[0]}},
{ mData: 'matricula'},{ mData: 'estatus'},
{ mData: 'marca'},{ mData: 'anio'},{ mData: 'modelo'},{ mData: 'motor'},
{ mData: 'serie'},{ mData: 'color'},{ mData: 'transmision'},{ mData: 'tipo'},
{ mData: 'fecha_creacion'},
{ mData: 'id','render': function ( data, type, full, meta ) {
  var coltool=data.split('-');
   return '<a href="<?=base_url();?>vehiculos/vehicledetail/'+coltool[0]+'" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Editar Vehiculo"><span class="glyphicon glyphicon glyphicon-edit"></span></a><button type="button" class="btn btn-xs btn-default bt-delete css-tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Eliminar Vehiculo"  Onclick="delete_vehiculo_v(\''+coltool[0]+'\',\''+base_url+'\');"><span class="glyphicon glyphicon-trash"></span></button>';
    } }
],
dom: 'Bfrtip',
        lengthMenu: [
            [15, 25, 50,100, -1],
            [ '15 Registros', '25 Registros', '50 Registros', '100 Registros', 'Mostrar todos' ]
        ],
        buttons: [
            'pageLength',
      'colvis',
    {
         extend: 'collection',
         text: '<i class="fa fa-floppy-o"></i> <span class="caret"></span>',
         buttons: [  
     {
            extend: 'print',
      title:'Lista Usuarios',
            text: '<i class="fa fa-print"></i> Imprimir',
        },
     {
            extend: 'csvHtml5',
      title:'Lista de Usuarios',
            text: '<i class="fa fa-file-excel-o"></i> Exportar a EXCEL',
        },
     {
            extend: 'pdf',
      title:'Lista Usuarios',
      orientation: 'landscape',
            text: '<i class="fa fa-file-pdf-o"></i> Exportar a PDF',
        } ]
      }
        ],
"fnDrawCallback": function( oSettings ) {
$('input.allsuspendido').prop('checked', false);
    },
"initComplete": function() {
$('#list-vehiculos_wrapper .dt-buttons').after('<div class="btn-group dt-btns"></div>');

//-------->Boton refrescar
$('#list-vehiculos_wrapper .dt-buttons').append('<a class="btn btn-sm btn-default buttons-collection" onClick="updatelist_v()"><i class="fa fa-refresh" ></i></a>');

$('#list-vehiculos_wrapper .dt-btns').append('<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-new-vehiculo"><i class="fa fa-plus"></i> NUEVO VEHÍCULO</button>');
 }
  
 } );


$('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
});


//--------> UPDATE 
updatelist_v=function(){
vehiculos.ajax.reload( null, false );
}

//--------> Nuevo vehiculo
 new_vehiculo=function(f,e){
 e.preventDefault();
 var variables = $(f).serialize();
 save_new_vehiculo('<?=base_url();?>', f.action, "div#response", true, "POST", variables);   
}

var base_url="<?=base_url();?>";
  ////////todas las funciones que conllevan a marca
  $(".select2-marca").select2();
  $(".select2-anio").select2();
  $(".select2-modelo").select2();
  $(".select2-motor").select2();

  ///////////////CARGA PARAMETROS CUANDO SELECCIONO MARCAS ANIOS MODELOS ETC

  $("#marca").change(function() {
    id_marca=$(this).val();
    $(".select2-anio").html('').select2({placeholder: 'Loading...'});
    $(".select2-modelo").html('').select2({placeholder: 'Loading...'});
    $(".select2-motor").html('').select2({placeholder: 'Loading...'});
    setTimeout(function(){
      $.post( base_url+"vehiculos/get_anios_by_marca", { id_marca: id_marca})
        .done(function( data ) {
        $('.select2-anio').html('').select2().html(data);
             id_anio=$(".select2-anio").val();
             $.post( base_url+"vehiculos/get_modelo_by_anio_marca", { id_marca: id_marca, id_anio:id_anio})
             .done(function( data1 ) {
             $('.select2-modelo').html('').select2().html(data1);
                   id_modelo=$(".select2-modelo").val();
                   $.post( base_url+"vehiculos/get_motor_by_modelo", { id_modelo: id_modelo})
                   .done(function(data2) {
                  $('.select2-motor').html('').select2().html(data2);

                 });
             });
          });
     }, 1500); 
  });

  $("#anio").change(function() {
    id_anio=$(this).val();
    id_marca=$("#marca").val();
     $(".select2-modelo").html('').select2({placeholder: 'Loading...'});
     $(".select2-motor").html('').select2({placeholder: 'Loading...'});
     setTimeout(function(){
         $.post( base_url+"vehiculos/get_modelo_by_anio_marca", {id_marca: id_marca, id_anio:id_anio})
         .done(function( data ) {
               $('.select2-modelo').html('').select2().html(data);
                   id_modelo=$(".select2-modelo").val();
                    $.post( base_url+"vehiculos/get_motor_by_modelo", { id_modelo: id_modelo})
                    .done(function(data1) {
                    $('.select2-motor').html('').select2().html(data1);
               });      
           });
      }, 1500);  
  });

  $("#modelo").change(function() {
    id_modelo=$(this).val();
    $(".select2-motor").html('').select2({placeholder: 'Loading...'});
    setTimeout(function(){
       $.post( base_url+"vehiculos/get_motor_by_modelo", { id_modelo: id_modelo})
       .done(function(data1) {
       $('.select2-motor').html('').select2().html(data1); });
    }, 1500);
 });

});


</script>

<div class="modal fade" id="modal-new-vehiculo">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
<span aria-hidden="true">&times;</span></button>
<h4 class="modal-title">Nuevo vehiculo</h4>
</div>
<div class="modal-body">
<form id="form-detalles" class="form-horizontal control-form-v" action="<?=base_url();?>vehiculos/save_new_vehiculo" onsubmit="new_vehiculo(this,event);">
<div class="form-group">
<label class="col-sm-2 control-label">Matrícula</label>
<div class="col-sm-4">              
<input type="text" class="form-control" id="matricula" name="matricula" value='' required placeholder="Ingrese la matrìcula">
</div>
<label class="col-sm-2 control-label"># Serie</label>
<div class="col-sm-4">
<input type="text" class="form-control" id="numero_serie" name="numero_serie" value='' required placeholder="Ingrese # serie">
</div>
</div>
<div class="form-group">
<label class="col-sm-2 control-label">Marca</label>
<div class="col-sm-4">
<select class="select2-marca form-control" id="marca" name="marca" style="width: 100%;">
<?php echo $option_marcas;?>
</select>
</div>

<label class="col-sm-2 control-label">Año</label>
<div class="col-sm-4">
<select class="select2-anio form-control"  id="anio" name="anio" style="width: 100%;">
<option value="0">Selecciona el año</option>
</select>
</div>

</div>

<div class="form-group">

<label class="col-sm-2 control-label">Modelo</label>
<div class="col-sm-4">
<select class="select2-modelo form-control" id="modelo" name="modelo" style="width: 100%;">
<option value="0">Selecciona el el modelo</option>
</select>
</div>

<label class="col-sm-2 control-label">Motor</label>
<div class="col-sm-4">
<select class="select2-motor form-control"  id="motor" name="motor" style="width: 100%;">
  <option value="0">Selecciona una opciòn</option>
</select>
</div>

</div>

<div class="form-group">
<label class="col-sm-2 control-label">Color</label>
<div class="col-sm-4">
<input type="text" class="form-control" id="color" name="color" value='' required placeholder="Ingrese el color">
</div>
<label class="col-sm-2 control-label">Transmisión</label>
<div class="col-sm-4">
<select class="form-control"  id="transmision" name="transmision">
  <?php echo $option_tipo_transmision_vehiculo;?>
</select>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label">Tipo</label>
<div class="col-sm-4">
<select class="form-control"  id="tipo" name="tipo">
  <?php echo $option_tipo_vehiculo;?>
</select>
</div>
<label class="col-sm-2 control-label">Combustión</label>
<div class="col-sm-4">
<select class="form-control"  id="combustion" name="combustion">
  <?php echo $option_tipo_combustion_vehiculo;?>
</select>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 control-label">Combustible</label>
<div class="col-sm-4">
<select class="form-control"  id="combustible" name="combustible">
 <?php echo $option_tipo_combustible_vehiculo;?>
</select>
</div>
<label class="col-sm-2 control-label">Dirección</label>
<div class="col-sm-4">
<select class="form-control"  id="direccion" name="direccion">
  <?php echo $option_tipo_direccion_vehiculo;?>
</select>
</div>
</div>

<div class="form-group">
<label class="col-sm-2 col-xs-4 control-label">A/AC</label>
<div class="col-sm-4 col-xs-2" style="margin-top: 5px;">
  <input class="form-check-input" type="checkbox" name="aire_acondicionado" id="aire_acondicionado">
</div>
<label class="col-sm-2 col-xs-4 control-label">Asegurado</label>
<div class="col-sm-4 col-xs-2" style="margin-top: 5px;">
  <input class="form-check-input" type="checkbox" name="asegurado" id="asegurado">
</div>
</div>
<div class="modal-footer">
<div class="col-sm-12">
<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"> </i> Cancelar</button>
    <?php 
    ($tipoacceso==1) ? $button=null : $button='<button type="submit" class="btn btn-sm btn-success" ><i class="fa fa-floppy-o"></i> Registrar</button>';
    echo $button; ?>
</div> 
</div>
<input type="hidden" name="id_cliente" value='<?php echo $id ?>'>
<input type="hidden" name="id_sucursal" value='<?php echo $id_sucursal ?>'>
</form>
</div>    

</div>
</div>
</div>

</div></div>
          <!-- /.modal-dialog </d </div>