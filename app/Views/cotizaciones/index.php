<?php date_default_timezone_set('America/Mexico_City'); $fecha_actual = date("Y-m-d"); $fecha_mas_10=date("Y-m-d",strtotime($fecha_actual."+ 10 days"));?>
<style>
@media (max-width: 576px){
.icon-button,.dt-buttons {display:none;}
.slstatesuser{margin-left:-10px;}
}
</style>
<div class="panel panel-blue panel-sindicatos">
<div class="panel-heading">
<h4 class="panel-title">Lista Cotizaciones</h4>
<div class="panel-heading-btn">
<a href="javascript:;" class="btn btn-icon btn-sm btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fas fa-expand"></i></a>
<a href="javascript:;" class="btn btn-icon btn-sm btn-icon btn-circle btn-default" data-click="panel-reload" onclick="updatelist()"><i class="fas fa-sync-alt"></i></a>
</div>
</div>
<div class="panel-body">
    
<table id="list-cotizaciones" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%" style="width: 100%;">
<thead >
<tr>
<th class="all column" style=" padding-right:0px !important">Id</th>
<th  class="column">Nombre</th><th  class="column">Cliente</th><th  class="column">Teléfono</th>
<th  class="column">Total</th><th class="column">IVA</th>
<th  class="column">Estado</th>
<th  class="column">Factura</th>
<th  class="column">Fecha</th><th  class="column">Fecha Vencimiento</th><th  class="all column">Status</th>
<th  class="column">Correo</th>
<th class="all column" data-orderable="false" style="max-width:10px">Acción</th>
</tr>
</thead>
</table>
</div>
</div>


<style type="text/css">
  
  div.dataTables_wrapper div.dataTables_filter {

    padding-left:-15px;
    margin-top: 5px;
  }
  td{
    font-size: 12px;
  }


</style>
       
 
  <script>

//--->funciones
var updatelist;
var tblcol;
var cotizaciones;

var base_url="<?=base_url();?>";

$(function(){

//-------> LISTA DE ACTIVOS
configtable('#list-cotizaciones','cotizaciones');
cotizaciones=$('#list-cotizaciones').DataTable( {
"language": {
 "url": "<?=base_url();?>public/js/Datatables-es.json"
        },
"processing": true,
"ajax": "<?=base_url();?>cotizaciones/get_cotizaciones_json",
"deferRender": true,
"aaSorting": [[0,'desc']],
"aoColumns": [
{ mData: 'id'},
{ mData: 'nombre'},
{ mData: 'cliente',sClass:"dt-center"},
{ mData: 'telefono'},
{ mData: 'total'},
{ mData: 'impuesto',sClass:"dt-center"},
{ mData: 'estado',sClass:"dt-center"},
{ mData: 'nfactura',sClass:"dt-center"},
{ mData: 'fecha_cot'},
{ mData: 'fecha_ven'},
{ mData: 'status',sClass:"dt-center"},
{ mData: 'correo'},
{ mData: 'id','render': function ( data, type, full, meta ) {
  var coltool=data.split('-');
  var permiso=full['tipoacceso'];var estado=full['estado1'];
  if(permiso>2 && estado==0){
         return '<td><button onClick="edit_cot(\''+full['id']+'\')" class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" title="Editar"><li class="far fa-edit"></li></button>'+
                '<button onClick="resendmail(\''+full['id']+'\')" class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" title="Enviar"><i class="far fa-envelope"></i></button>'+
                '<a target="_blank" href="<?=base_url();?>cotizaciones/pdf/'+full['id']+'" class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" title="PDF"><i class="far fa-file-pdf" aria-hidden="true"></i></a>'+
                '<button onClick="acep_cot(\''+full['id']+'\')" class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" title="Aceptar Cot" ><i class="fa fa-check"></i></button>'+
                '<button onClick="delete_cot(\''+full['id']+'\')" class="btn btn-icon btn-sm btn-default" ><i class="far fa-trash-alt"></i></button></td>';
                
    }
   if(permiso>2 && estado==1){
         return '<td>'+
                '<button onClick="resendmail(\''+full['id']+'\')" class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" title="Enviar"><i class="far fa-envelope"></i></button>'+
                '<a target="_blank" href="<?=base_url();?>cotizaciones/pdf/'+full['id']+'" class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" title="PDF"><i class="far fa-file-pdf" aria-hidden="true"></i></a>';
                
    }  
    if(permiso>1 && estado==0){
         return '<td><button onClick="edit_cot(\''+full['id']+'\')" class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" title="Editar"><li class="far fa-edit"></li></button>'+
                '<button onClick="resendmail(\''+full['id']+'\')" class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" title="Enviar"><i class="far fa-envelope"></i></button>'+
                '<a target="_blank" href="<?=base_url();?>cotizaciones/pdf/'+full['id']+'" class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" title="PDF"><i class="far fa-file-pdf" aria-hidden="true"></i></a>'+
                 '<button onClick="acep_cot(\''+full['id']+'\')" class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" title="Aceptar Cot"><i class="fa fa-check"></i></button></td>';
                
    } 

    if(permiso>1 && estado==1){
         return '<td>'+
                '<button onClick="resendmail(\''+full['id']+'\')" class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" title="Enviar"><i class="far fa-envelope"></i></button>'+
                '<a target="_blank" href="<?=base_url();?>cotizaciones/pdf/'+full['id']+'" class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" title="PDF"><i class="far fa-file-pdf" aria-hidden="true"></i></a>';
                
    }
    else{
         return '<td><a target="_blank" href="<?=base_url();?>cotizaciones/pdf/'+full['id']+'" class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" title="PDF"><i class="far fa-file-pdf" aria-hidden="true"></i></a></td>';
    } 
   } ,sClass: 'dt-center'
 }
],
"fnDrawCallback": function( oSettings ) {
$('input.allsuspendido').prop('checked', false);
    },
"initComplete": function() {

$('#list-cotizaciones_wrapper .dt-buttons').append('');
      $("#list-cotizaciones_wrapper div.tooltablas")
         .html('<div class="grupotabla btn-group m-r-5">\
        <a href="#cotizaciones/nuevo" class="btn btn-default" onclick="new_cot()"><i class="fas fa-plus"></i> Nuevo</a></div>');


 }
  
 } );



 
$('a[data-toggle="tab"]').on("shown.bs.tab", function (e) {
$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
});




//--------> UPDATE 
updatelist=function(){
cotizaciones.ajax.url(base_url+"cotizaciones/get_cotizaciones_json").load();
}

//--------> Modal Col
tblcol=function(){
$('.tmpusuarios').empty();
alerta("loader","procesando...");
$.post( "usuarios.php", { action: "modalcol" })
.done(function( datas ) {

$('.tmpusuarios').html(datas);
});
}

//------------>Herramientas del cliente
$('body').on('click', 'button.weutool', function(e) {
$(this).webuiPopover('destroy');
$('.webui-popover').remove();
var id_user=$(this).data('idcliente');
$(this).webuiPopover({
trigger:'manual',
cache:true,
closeable:true,
type:'async',
url:'usuarios.php?action=tool&id='+id_user,
content:function(data){
return data;
},
placement:'auto',
width:'240px',height:'auto',
animation:'pop'
});
e.stopPropagation();
$(this).webuiPopover('show');
});

//------------>Herramientas del cliente (whois)
$('body').on('click', '.whoisip', function() {
PopupCenter("tool.php?action=whois&ip="+$(this).data('ipurl'),'Whois IP','600','420');  
});


if(localStorage.caja){
$('#collapseactivo').collapse();  
}
loadSettings();

});

//------> Filtro
function filtro_a(columna,texto) {
if(texto==""){
clientes.search( '' ).columns().search( '' ).draw();
}else{
columna=parseInt(columna) + 1;
clientes.columns(columna).search(texto).draw();
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

function new_cot()
{
window.location.href = "#cotizaciones/nuevo";
}
function edit_cot(id_cot)
{
   
window.location.href = "#cotizaciones/editar?id="+id_cot;


}


  function delete_cot(id){
  var alsup = $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: '¿Esta seguro que desea eliminar esta cotización? <br><b class="text-warning">' + id + '</b><br><br><br>',
      title: '<i class="far fa-question-circle fa-lg icodialog"></i> ELIMINAR',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt"></i> Si, eliminar',
          action: function() {
            this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
            this.buttons.Eliminar.disable();
            $('.jconfirm-closeIcon').remove();
            $.ajax({
        url: "<?=base_url();?>cotizaciones/deleteCot",
        type: 'post',
        data:{id:id},
        success: function(response){
           if(response=="1"){alerta("exito","Cotizacion eliminada",2);updatelist();}
        else{ alerta("error","Ha ocurrido un error",2); }
          
        }
    });
           

          }
        }
      }
    });
}



    function acep_cot(id){
  var alsup = $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: '¿Esta seguro que desea aceptar esta cotización? <br><b class="text-warning">' + id + '</b><br><br><br>Se creará una nueva factura libre en el sistema',
      title: '<i class="far fa-question-circle fa-lg icodialog"></i> ACEPTAR',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt"></i> Si, aceptar',
          action: function() {
            this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
            this.buttons.Eliminar.disable();
            $('.jconfirm-closeIcon').remove();
            $.ajax({
        url: "<?=base_url();?>cotizaciones/acepCot",
        type: 'post',
        data:{id:id},
        success: function(response){
          $('#gritter-notice-wrapper').remove();
           if(response=="1"){alerta("exito","Factura creada",2);updatelist();}
           else{ alerta("error","Ha ocurrido un error",2); }
          
        }
    });
           

          }
        }
      }
    });
}


function resendmail(id_cot) {

    swal({
        title: "¿Esta seguro que desea volver enviar este correo?",
        text: "Será marcado como No enviado.",
        type: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si, Enviar!",
        closeOnConfirm: !0
    }, function() {
         alerta("loader","procesando..."), $.post(base_url +"cotizaciones/resendmail", {
            action: "resendmail",
            id: id_cot
        }).done(function(e) {
          $('#gritter-notice-wrapper').remove(); alerta("exito", "Correo enviado correctamente", 2)
        })
    })
}


</script>


  <div class="modal fade" id="modal-new-cot" rol="dialog" style="overflow-y:auto">
   <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
     <form id="form-detalles" class="form-horizontal" action="<?=base_url();?>cotizaciones/save_update_cotizacion" onsubmit="save_update_cotizacion(this,event);">

     <div class="modal-header">
        <h5 class="modal-title edit-new-modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
     </div>
<div class="modal-body">
                       <!-- Custom Tabs -->
<div class="row">
<div class="col-sm-12" data-sortable="false">

<div class="panel panel-inverse panel-with-tabs" data-sortable="false">
      <!-- begin panel-heading -->
<div class="panel-heading p-0">
        
<!-- begin nav-tabs -->
<div class="tab-overflow nav-ajax overflow-right" style="width: 100%">
<ul class="nav nav-tabs nav-tabs-inverse">


<li class="nav-item"><a href="#nav-tab-1" data-toggle="tab" data-url="" class="nav-link active"><i class="fa fa-info-circle"></i> Resumen</a></li>
<li class="nav-item"><a href="#nav-tab-2" data-toggle="tab" data-url="" class="nav-link"><i class="fa fa-shopping-cart"></i> Productos y Servicios</a></li>
<li class="nav-item"><a href="#nav-tab-3" data-toggle="tab" data-url="" class="nav-link"><i class="far fa-file-pdf"></i> Notas</a></li>

<style type="text/css">
    
.popover {
z-index: 1021 !important;
}
   </style>
            
        
</ul>
</div>
<!-- end nav-tabs -->
  
        
</div>

<div class="panel-body tab-content">
        <!-- begin tab-pane -->
<div class="tab-pane active show" id="nav-tab-1">
 <div class="form-group row">
  <label class="col-sm-3 control-label">Cliente</label>
  <div class="col-sm-4">
    <select class="form-control" id="cliente" name="cliente" style="width: 100%">
      <option value="0" selected>NO</option> <option value="1">SI</option>
   </select>
  </div>
  <div class="col-sm-4">
     <input type="text" class="form-control" id="nombre_search" name="nombre_search" placeholder="Buscar cliente" disabled="">
  
  </div>
</div>

 
<div class="form-group row">
<label class="col-sm-3 control-label">Nombre</label>
<div class="col-sm-8">
<input type="text" class="form-control" id="nombres" name="nombres">
</div>
</div>

<div class="form-group row">
<label class="col-sm-3 control-label">Domicilio</label>
<div class="col-sm-8">
<input type="text" class="form-control" id="domicilio_completo" name="domicilio_completo" value=''>
</div>
</div>

<div class="form-group row">
<label class="col-sm-3 control-label">Nº Télefono</label>
<div class="col-sm-8">
<input type="text" class="form-control" id="telefono" name="telefono" value=''>
</div>
</div>

<div class="form-group row">
<label class="col-sm-3 control-label">Correo</label>
<div class="col-sm-8">
<input type="email" class="form-control" id="correo" name="correo" value=''>
</div>
</div>
</div>

<div class="tab-pane" id="nav-tab-2">

<div class="form-group row">
 <div class="col-sm-2">
    <label for="fecha_cot">Fecha emisión</label>
    <input type="text" class="form-control" id="fecha_cot" name="fecha_cot" value="<?=date('Y-m-d')?>" aria-describedby="emailHelp">
  </div>
 <div class="col-sm-2">
    <label for="fecha_ven">Fecha Vencimiento</label> <?php date_default_timezone_set('America/Mexico_City'); $fecha_actual = date("Y-m-d"); $fecha_mas_10=date("Y-m-d",strtotime($fecha_actual."+ 10 days"));?>
    <input type="text" class="form-control" id="fecha_ven" name="fecha_ven" value="<?=date("Y-m-d",strtotime($fecha_actual."+ 10 days"));?>" aria-describedby="emailHelp">
  </div>
  <div class="col-sm-2">
    <label for="exampleInputEmail1">Impuesto</label>
    <select class="form-control" id="impuesto" name="impuesto" style="width: 100%">
      <option value="SI">SI</option>
      <option value="NO">NO</option>
    </select>
  </div>
   <div class="col-sm-3 col-xs-6" style="margin-top:10px">
    <br>
    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal_all_productos "><i class="fa fa-plus"></i> Agregar Producto/Servicio</button>
  </div>
  
  <div class="col-sm-3 col-xs-6" style="margin-top:10px;">
    <br>
    <button style="" type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-otro-producto "><i class="fa fa-plus"></i> Otro Concepto/Servicio</button>
  </div>
</div>

<div class="form-group row">
  <div class="col-sm-3 col-xs-6" style="margin-top:3px;">
    <br>
    <span style="font-size:16px">Subtotal: <label style="font-size:20px" id="subtotal">$0.00</label></span>
  </div>
  <div class="col-sm-3 col-xs-6" style="margin-top:3px;">
  <br>
    <div class="form-group row">
    <span style="font-size:16px; margin-top:0px" for="staticEmail" class="col-sm-5 col-xs-5 col-form-label">Descuento: </span>
    <div class="col-sm-7 col-xs-7">
      <input type="number"  onKeyPress="return enter_descuento(event)" class="form-control" id="descuento_carrito" name="descuento_carrito" value="0.00" step="0.001">
      
    </div>
  </div>
  </div>
   <div class="col-sm-3 col-xs-6" style="margin-top:3px;">
    <br>
    <span style="font-size:16px">Iva: <label style="font-size:20px" id="iva">$0.00</label></span>
  </div>
  <div class="col-sm-3 col-xs-6" style="margin-top:3px;">
    <br>
    <span style="font-size:16px">Total: <label style="font-size:20px" id="total">$0.00</label></span>
  </div>
</div>
       <input type="hidden"  id="subtotal_carrito" name="subtotal_carrito" value="0.00">
       <input type="hidden"  id="iva_carrito" name="iva_carrito" value="0.00">
        <input type="hidden"  id="total_carrito" name="total_carrito" value="0.00">
        <input type="hidden"  id="id_cliente" name="id_cliente" value="000000">
        <input type="hidden"  id="id_cot" name="id_cot" >

<div class="form-group row">
 <div class="col-xs-12">
<table id="list-carrito" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%" style="width: 100%;">
<thead >
<tr>
<th class="all column"  style=" padding-right:0px !important">N°</th>
<th  class="column">Id</th>
<th  class="column">Id</th>
<th  class="column">Producto/Servicio</th>
<th  class="column">Descripción</th>
<th  class="column">C. Unitario</th>
<th  class="column">Cantidad</th>
<th  class="column">Unidad</th>
<th  class="all column">Subtotal</th>
<th class="all column" data-orderable="false" style="max-width:10px">Acción</th>
</tr>
</thead>

</table>
  </div>
</div>


</div>

<div class="tab-pane" id="nav-tab-3">
<div class="form-group row">
<label class="col-md-3 control-label">Notas</label>
<div class="col-md-9">
<textarea class="form-control" rows="6" id="notas" name="notas"></textarea>
</div>
</div>

</div>

</div> 

</div>
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



<div class="modal fade" id="modal_all_productos" role="dialog">
   <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
     <div class="modal-header"><h5 class="modal-title">Selecciona un producto</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
     </div>
    <div class="modal-body">
                
<div class="row">
  <div class="col-sm-12" style="margin-top: 10px;">
<table id="list-productos" class="table-hover table table-striped table-bordered dt-responsive nowrap compact" cellspacing="0" width="100%" data-order="[[ 1, &quot;asc&quot; ]]">
<thead >
<tr>
<th class="all column">Id</th>
<th class="column">Producto/Servicio</th>
<th  class="column">Descripción</th>
<th  class="column">Unidad</th>
<th class="column">Costo</th>
<th class="all column radios" data-orderable="false" style="min-width:46px !important;max-width:46px !important">Acción</th>
</tr>
</thead>

</table>
  </div>
  </div>
  </div>


     <div class="modal-footer">
<a href="" class="btn-azul" data-dismiss="modal">Cerrar</a>
<?php $button=""; if($tipoacceso>1){$button='<button type="button" class="btn btn-sm btn-azul" onclick="selec_producto()">Guardar</button>';}?>
<?=$button?>
</div>
      
 </div>
            <!-- /.modal-content -->

</div>
</div>

<div class="modal fade" id="modal-new-producto">
   <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <form id="form-detalles" action="<?=base_url();?>cotizaciones/save_new_producto" onsubmit="save_producto(this,event);">
      <div class="modal-header"><h5 class="modal-title">Nuevo producto</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
     </div>
<div class="modal-body">
                
<div class="row">
<div class="col-sm-12" style="margin-top: 10px;">
  <div class="form-group row">
 <div class="col-xs-12">
    <label for="producto_modal">Selecciona tipo</label>
     <select class="form-control sl2" name="tipo_modal" id="tipo_modal" style="width: 100%">
      <option value="producto">Producto</option>
      <option value="servicio">Servicio</option>
    </select>
    
  </div>
   <div class="col-xs-12">
    <label for="producto_modal">Producto/Servicio</label>
    <input type="text" class="form-control" id="producto_modal" name="producto_modal" required>
  </div>
  <div class="col-xs-12">
    <label for="costo_modal">Costo</label>
    <input type="number" onKeyPress="return acceptNum(event)" step="0.0001"  class="form-control" id="costo_modal" name="costo_modal" required>
  </div>
  <div class="col-xs-12">
    <label for="medida_modal">Medida</label>
    <select class="form-control" name="medida_modal" id="medida_modal">
      <option value="unidad">Unidad</option>
      <option value="metro">Metro</option>
      <option value="kilo">Kilo</option>
      <option value="caja">Caja</option>
      <option value="kit">Kit</option>
      <option value="servicio">Servicio</option>
    </select>
  </div>
</div>

  <div class="form-group row">
  <div class="col-xs-12">
    <label for="descripcion_modal">Descripción</label>
    <textarea class="form-control" id="descripcion_modal" name="descripcion_modal" rows="4"></textarea>
  </div>
</div>

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
            <!-- /.modal-content -->

</div>
</div>

<div class="modal fade" id="modal-otro-producto">
   <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <form id="form-detalles" action="<?=base_url();?>cotizaciones/save_otro_producto" onsubmit="save_otro_producto(this,event);">
        <div class="modal-header"><h5 class="modal-title">Otro concepto</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
       </div>
<div class="modal-body">
                
<div class="row">
<div class="col-sm-12" style="margin-top: 10px;">
  <div class="form-group">
 <div class="col-xs-12">
    <label for="producto_modal">Producto/Servicio</label>
    <input type="text" class="form-control" id="otro_producto_modal" name="otro_producto_modal" required>
  </div>
  <div class="col-xs-6">
    <label for="costo_modal">Costo</label>
    <input type="number" onKeyPress="return acceptNum(event)" step="0.0001"  class="form-control" id="otro_costo_modal" name="otro_costo_modal" required>
  </div>
  <div class="col-xs-6">
    <label for="otro_medida_modal">Medida</label>
    <select class="form-control" name="otro_medida_modal" id="otro_medida_modal">
      <option value="unidad">Unidad</option>
      <option value="metro">Metro</option>
      <option value="kilo">Kilo</option>
      <option value="caja">Caja</option>
      <option value="kit">Kit</option>
      <option value="servicio">Servicio</option>
    </select>
  </div>
</div>

  <div class="form-group">
  <div class="col-xs-12">
    <label for="descripcion_modal">Descripción</label>
    <textarea class="form-control" id="otro_descripcion_modal" name="otro_descripcion_modal" rows="4"></textarea>
  </div>
</div>

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
            <!-- /.modal-content -->

</div>
</div>

<div class="modal fade" id="modal-edit-item">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
    <form id="form-detalles" action="<?=base_url();?>cotizaciones/update_item" onsubmit="update_item(this,event);">
       <div class="modal-header"><h5 class="modal-title">Editar concepto</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
       </div>
<div class="modal-body">
                
<div class="row">
<div class="col-sm-12" style="margin-top: 10px;">
  <div class="form-group row">
  <div class="col-xs-12">
    <label for="producto_modal">Id Producto</label>
     <input type="hidden" class="form-control" id="id_item" name="id_item" required>
    <input type="text" class="form-control" id="id_producto_item" name="id_producto_item" required readonly="">
  </div>
 <div class="col-xs-12">
    <label for="producto_modal">Producto/Servicio</label>
    <input type="text" class="form-control" id="producto_item" name="producto_item" required>
  </div>
  <div class="col-xs-4">
    <label for="costo_modal">Costo</label>
    <input type="number" onKeyPress="return acceptNum(event)" step="0.0001"  class="form-control" id="costo_item" name="costo_item" required>
  </div>
   <div class="col-xs-4">
    <label for="cantidad_modal">Cantidad</label>
    <input type="number" onKeyPress="return acceptNum(event)"  class="form-control" id="cantidad_item" name="cantidad_item" required>
  </div>
  <div class="col-xs-4">
    <label for="medida_modal">Medida</label>
    <select class="form-control" name="medida_item" id="medida_item">
      <option value="unidad">Unidad</option>
      <option value="metro">Metro</option>
      <option value="kilo">Kilo</option>
      <option value="caja">Caja</option>
      <option value="kit">Kit</option>
      <option value="servicio">Servicio</option>
    </select>
  </div>
</div>

  <div class="form-group row">
  <div class="col-xs-12">
    <label for="descripcion_modal">Descripción</label>
    <textarea class="form-control" id="descripcion_item" name="descripcion_item" rows="4"></textarea>
  </div>
</div>

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
            <!-- /.modal-content -->

</div>
</div>

<script type="text/javascript" charset="utf8">
 

  var carrito;
  var productos;
  var nav4 = window.Event ? true : false;
  $(function(){

    loadlist_carrito_init();
    update_precios();
    $(".sl2").select2();

    $('#modal_all_productos').on('shown.bs.modal', function () {
      loadlist_productos();

    })

      $('#modal-new-cot').on('shown.bs.modal', function () {
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();

    })


$(document).on('keyup','#nombre_search',function(e){
$("#nombre_search" ).autocomplete({
source: base_url + 'clientes/searchcliente?action=listauser',
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
 $( "#id_cliente" ).val(ui.item.id);
 $( "#nombres" ).val(ui.item.nombre);
 $( "#domicilio_completo" ).val(ui.item.direccion);
 $( "#telefono" ).val(ui.item.movil);
 $( "#correo" ).val(ui.item.correo);
}
}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li></li>" )

            .data( "item.autocomplete", item )
            .append(item.socio)
            .appendTo( ul ); 
    };
});


$( "#cliente" ).change(function() {
    if($(this).val()=="0") { $("#nombre_search").attr("disabled","disabled"); $("#id_cliente").val("000000");}
    else { $("#nombre_search").removeAttr("disabled");}
});


$( "#impuesto" ).change(function() {
   alerta("loader", "procesando...");
   update_precios(); 
  $('#gritter-notice-wrapper').remove();
});


 $.datepicker.regional['es'] = {
 closeText: 'Cerrar',
 prevText: '< Ant',
 nextText: 'Sig >',
 currentText: 'Hoy',
 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
 weekHeader: 'Sm',
 dateFormat: 'dd/mm/yy',
 firstDay: 1,
 isRTL: false,
 showMonthAfterYear: false,
 yearSuffix: ''
 };
 $.datepicker.setDefaults($.datepicker.regional['es']);
  
//--->Fechas
$('#fecha_cot,#fecha_ven').datepicker({
dateFormat: "yy-mm-dd",
toggleActive: true,
todayHighlight: true,
autoclose: true
});

});
   

 loadlist_carrito=function(){
 if ( ! $.fn.DataTable.isDataTable( '#list-carrito' ) ) {
carrito=$('#list-carrito').DataTable( {
"language": {
 "url": "<?=base_url();?>public/js/Datatables-es.json"
        },
"ajax": "<?=base_url();?>cotizaciones/updateCarrito",
"processing": true,
"deferRender": true,
"searching": false,
"aoColumns": [
{ mData: 'numero'},
{ mData: 'id',bVisible: false},
{ mData: 'idproducto'},
{ mData: 'producto',sClass:"dt-center"},
{ mData: 'descripcion'},
{ mData: 'unitario'},
{ mData: 'cantidad'},
{ mData: 'unidad'},
{ mData: 'subtotal',sClass:"dt-center"},
{ mData: 'id','render': function ( data, type, full, meta ) {
  var permiso=full['tipoacceso'];
  return '<button type="button" onClick="updateItemCarrito(\''+full['id']+'\')"class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" title="Editar"><li class="far fa-edit"></li></button>'+
          '<button type="button" onClick="deleteCarrito(\''+full['id']+'\')" class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" title="Eliminar"><i class="far fa-trash-alt"></i></button>';
      
   } ,sClass: 'dt-center'
 }
],
 } );

}else{
updatelist_carrito(); 
 }
 }

updatelist_carrito=function(){
carrito.ajax.url(base_url+"cotizaciones/updateCarrito").load();
}

 loadlist_carrito_init=function(){
 if ( ! $.fn.DataTable.isDataTable( '#list-carrito' ) ) {
carrito=$('#list-carrito').DataTable( {
"language": {
 "url": "<?=base_url();?>public/js/Datatables-es.json"
        },
"ajax": "<?=base_url();?>cotizaciones/updateCarrito_init",
"processing": true,
"deferRender": true,
"searching": false,
"lengthChange": false,
"aoColumns": [
{ mData: 'numero'},
{ mData: 'id',bVisible: false},
{ mData: 'idproducto'},
{ mData: 'producto',sClass:"dt-center"},
{ mData: 'descripcion'},
{ mData: 'unitario'},
{ mData: 'cantidad'},
{ mData: 'unidad'},
{ mData: 'subtotal',sClass:"dt-center"},
{ mData: 'id','render': function ( data, type, full, meta ) {
  var permiso=full['tipoacceso'];
  return '<button type="button" onClick="updateItemCarrito(\''+full['id']+'\')"class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" title="Editar"><li class="far fa-edit"></li></button>'+
          '<button type="button" onClick="deleteCarrito(\''+full['id']+'\')" class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" title="Eliminar"><i class="far fa-trash-alt"></i></button>';
      
   } ,sClass: 'dt-center'
 }
],
 } );

}else{
updatelist_carrito_init(); 
 }
 }

updatelist_carrito_init=function(){
carrito.ajax.url(base_url+"cotizaciones/updateCarrito_init").load();
$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
}


loadlist_productos=function(){
if ( ! $.fn.DataTable.isDataTable( '#list-productos' ) ) {
productos=$('#list-productos').DataTable( {
"language": {
 "url": "<?=base_url();?>public/js/Datatables-es.json"
        },
"ajax": "<?=base_url();?>cotizaciones/get_all_productos_json",
"deferRender": true,
"pageLength": 10,
"lengthMenu": [[10, 15, 25, 50, -1], [10,15, 25, 50, "Todos"]],
"aaSorting": [[0,'desc']],
"aoColumns": [
{ mData: 'id'},
{ mData: 'producto'},
{ mData: 'descripcion'},
{ mData: 'unidad'},
{ mData: 'costo'},
{ mData: 'id','render': function ( data, type, full, meta ) {
  var coltool=data.split('-');
  return '<input type="radio" class="flat-red" id="flat-red" name="slactivo_pro[]" value="'+data+'" data-tipo="'+full["tipo"]+'">';
},sClass: 'dt-center'
   } 
],
"fnDrawCallback": function( oSettings ) {
$('input[type="radio"].flat-red').iCheck({   radioClass: 'iradio_flat-green',increaseArea: '-10%' })
//$('input[type="radio"].flat-red').on( "ifChecked", function( e ) {}); funcion cuando se hace click en iCheck
 
},
"initComplete": function() {

$('#list-productos_wrapper .dt-buttons').after('<div class="btn-group dt-btns"></div>');

//-------->Boton refrescar
//$('#list-productos_wrapper .dt-buttons').append('<a class="btn btn-sm btn-default buttons-collection" onClick="updatelist_productos()"><i class="fa fa-refresh" ></i></a>');


$('.not-search-input').html('');    
 //------->Button Col
$('#list-productos_wrapper .dt-buttons').append('');
      $("#list-productos_wrapper div.tooltablas")
         .html('<div class="grupotabla btn-group m-r-5">\
        <a class="btn btn-default" data-toggle="modal" data-target="#modal-new-producto"><i class="fas fa-plus"></i> Nuevo Producto</a></div>');


 }
  
 } );

}else{
updatelist_productos(); 
 }

}

updatelist_productos=function(){
productos.ajax.reload( null, false );
}



selec_producto=function(){ 
var id_pro="";
var tipo="";
$('input[name^="slactivo_pro"]:checked').each(function() {
    id_pro=$(this).val();
    tipo=$(this).attr("data-tipo");
});
if(id_pro){
$.post(base_url+"cotizaciones/addCarrito/", {id_producto: id_pro,tipo:tipo})
.done(function( datas ) {
$('#modal_all_productos').modal('toggle');

  console.log(datas)
  updatelist_carrito();
  update_precios();

});
}
 

}

function acceptNum(evt)  //Sólo números y SÓLO 1 punto decimal
{ 
  // Punto = 46
  var key = nav4 ? evt.which : evt.keyCode;
  cadena=document.getElementById("costo_modal").value;
  if(cadena.indexOf('.')==-1)
  {return (key <= 13 || (key >= 48 && key <= 57) || key == 46);}
  else
  {return (key <= 13 || (key >= 48 && key <= 57));}
  
}

function enter_descuento(evt)  //Sólo números y SÓLO 1 punto decimal
{ 
  // Punto = 46
  var key = nav4 ? evt.which : evt.keyCode;
  if(key == '13'){
    alerta("loader", "procesando...");
   update_precios(); 
   $('#gritter-notice-wrapper').remove(); 
  }
 
  
}

function save_producto(f,e){
  e.preventDefault();
  var variables=$(f).serialize();
    $.post("<?=base_url();?>cotizaciones/save_new_producto", variables).done(function( data ) {
    console.log(data)
    if(data=="1"){$('#modal-new-producto').modal('toggle');alerta("exito","Producto agregado");updatelist_productos();}
    else{ $('#modal-new-producto').modal('toggle'); alerta("error","Ha ocurrido un error"); }
      
   })
    
}

function save_otro_producto(f,e){
  e.preventDefault();
  var variables=$(f).serialize();
    $.post("<?=base_url();?>cotizaciones/addCarrito_otro_producto", variables).done(function( data ) {
    console.log(data)
    if(data=="1"){$('#modal-otro-producto ').modal('toggle');alerta("exito","Producto agregado",2);updatelist_carrito();update_precios();}
    else{ $('#modal-otro-producto').modal('toggle'); alerta("error","Ha ocurrido un error",2); }
      
   })
    
}


function deleteCarrito(id_producto){
 
   

    swal({
        title: "¿Esta seguro que desea eliminar este concepto?",
        text: "",
        type: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si, Eliminar!",
        closeOnConfirm: !0 },
        function() {

        $.post("<?=base_url();?>cotizaciones/deleteCarrito", {id_producto:id_producto}).done(function( data ) {
        console.log(data)
        updatelist_carrito();
        update_precios();     
        }) 

    })
 
}

function updateItemCarrito(id_producto){
  
       $.ajax({
        url: "<?=base_url();?>cotizaciones/updateItemCarrito",
        type: 'post',
        data:{id_producto:id_producto},
        dataType: 'JSON',
        success: function(response){
          for(var i=0; i<response.length; i++){
             var id = response[i].id;
                var idproducto = response[i].idproducto;
                 var producto = response[i].producto;
                  var descripcion = response[i].descripcion;
                   var unitario = response[i].unitario;
                   var cantidad = response[i].cantidad;
                    var unidad = response[i].unidad;
                     var subtotal = response[i].subtotal;

                     load_data_item(id,idproducto,producto,descripcion,unitario,cantidad,unidad,subtotal);
                
              }
          
        }
    });
}

function load_data_item(id,idproducto,p,d,unitario,c,u,s){
    
   $("#id_item").val(id); 
   $("#id_producto_item").val(idproducto);
   $("#producto_item").val(p);
   $("#descripcion_item").val(d);
   $("#cantidad_item").val(c);
   $("#medida_item").val(u);
   $("#costo_item").val(unitario);

   $('#modal-edit-item').modal();


}

function update_item(f,e){
    
  e.preventDefault();
  var variables=$(f).serialize();
    $.post("<?=base_url();?>cotizaciones/update_item", variables).done(function( data ) {
    console.log(data)
    $('#modal-edit-item').modal('toggle');
    updatelist_carrito();
    update_precios();
  //  if(data=="1"){$('#modal-new-producto').modal('toggle');alerta("exito","Producto agregado",2);updatelist_productos();}
  //  else{ $('#modal-new-producto').modal('toggle'); alerta("error","Ha ocurrido un error",2); }
      
   }) 

}

function update_precios(){
  
      $.ajax({
        url: "<?=base_url();?>cotizaciones/updatePrecios",
        type: 'post',
        data:{descuento:$("#descuento_carrito").val(),impuesto:$("#impuesto").val()},
        dataType: 'JSON',
        success: function(response){
          for(var i=0; i<response.length; i++){
             var subtotal = response[i].subtotal;
              var iva = response[i].iva;
               var total = response[i].total;

              var subtotal2 = response[i].subtotal2;
              var iva2 = response[i].iva2;
              var total2 = response[i].total2;

             $("#subtotal").text(subtotal);
             $("#iva").text(iva);
             $("#total").text(total);

             $("#subtotal_carrito").val(subtotal2);
             $("#iva_carrito").val(iva2);
             $("#total_carrito").val(total2);
                         
              }
          
        }
    });

}

function save_update_cotizacion(f,e){

      e.preventDefault();
      var variables=$(f).serialize();

      if($("#id_cliente").val()=="" || $("#nombres").val()==""){
        alerta("error","Acomplete los datos ó agregue conceptos",2);
      }
     
      else{

        $.ajax({
        url: "<?=base_url();?>cotizaciones/save_update_cotizacion",
        type: 'post',
        data:variables,
        dataType: 'JSON',
         beforeSend: function ( xhr ) {
         alerta("loader","Procesando")      
         },
        success: function(response){
          if(response=="1"){
           $('#gritter-notice-wrapper').remove();
           $("#modal-new-cot").modal("toggle");
           updatelist();
           alerta("exito","Cotización agregada",2);
 $( "#id_cliente" ).val("000000");
 $( "#nombres" ).val("");
 $( "#domicilio_completo" ).val("");
 $( "#telefono" ).val("");
 $( "#correo" ).val("");
 $( "#list-carrito tbody" ).html("");
  $( "#impuesto" ).val("SI");
 $( "#subtotal" ).text("0.00");
 $( "#iva" ).text("0.00");   
 $( "#total" ).text("0.00");
 $( "#descuento_carrito" ).val("0.00");
 $( "#cliente" ).val("0");
 $( "#descuento_carrito" ).val("");
 $( "#nombre_search" ).val("");
 $( "#nombre_search" ).attr("disabled","disabled");       
          }
          else{$('#gritter-notice-wrapper').remove();}
          
        }
    });
         

      }

}
                

</script>
