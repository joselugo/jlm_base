
<div class="nav-tabs-custom">
<ul class="nav nav-tabs"><li class="dropdown pull-right tabdrop hide"><a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-align-justify"></i> <b class="caret"></b></a><ul class="dropdown-menu"></ul></li>
<li class="active"><a href="#tabf_1" data-toggle="tab"><i class="fa fa-angle-double-right"></i> Facturas</a></li>
<li><a href="#tabf_2" data-toggle="tab"><i class="fa fa-angle-double-right"></i> Transacciones</a></li>

<li><a href="#tabf_3" data-toggle="tab"><i class="fa fa-angle-double-right"></i> Saldos</a></li>
<li><a href="#tabf_4" data-toggle="tab"><i class="fa fa fa-cogs"></i> Configuración</a></li>
</ul>

<div class="tab-content">

<div class="tab-pane active" id="tabf_1">

 
<table id="list-factura-cliente" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%" style="width: 100%;">
<thead>
<tr role="row">
  <th class="all column">Nº Factura</th>
  <th class="column">Nº legal</th>
  <th class="column">Cliente</th>
  <th class="column sorting">F. Emitido</th>
  <th class="column">F. Vencimiento</th>
  <th class="column">F. Pago</th>
  <th class="column all">Total</th>
  <th class="column">Cobrado</th>
  <th class="column">Saldo</th>
  <th class="column">Forma de pago</th>
  <th class="column all">Estado</th>
  <th class="all column" data-orderable="false" style="max-width: 60px; min-width: 60px !important; width: 70px;">Acción</th>
</tr>
</thead>
</table>

 </div>
             
<div class="tab-pane" id="tabf_2">
<table id="list-pago-cliente" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer" cellspacing="0" width="100%">
<thead>
<tr role="row">
<th class="all column sorting_desc">ID</th>
<th class="all column sorting">Cliente</th>
<th class="column sorting"># Factura</th>
<th class="column sorting"># Legal</th>
<th class="column sorting"># Transacción</th>
<th class="column sorting">Forma de pago</th>
<th class="column all sorting">Fecha &amp; Hora</th>
<th class="column sorting">Operador</th>
<th class="column all sorting">Cobrado</th>
<th class="column sorting">Comisión</th>
<th class="column sorting">Neto</th>
<th class="column sorting">Descripción</th>
<th class="column all" data-orderable="false">Acción</th>
</tr>
</thead>
</table>
</div>
 
<div class="tab-pane" id="tabf_3">
<table id="list-saldo-cliente" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer" cellspacing="0" width="100%">
<thead>
<tr role="row">
<th class="all column sorting_desc">ID</th>
<th class="all column sorting">N° Factura (origen)</th>
<th class="column sorting">Descripcion</th>
<th class="column sorting">Monto</th>
<th class="column sorting">Fecha</th>
<th class="column sorting">Estado</th>
<th class="column all" data-orderable="false">Acción</th>
</tr>
</thead>
</table>
 </div> 
 
<div class="tab-pane" id="tabf_4">
<div class="row">
<form id="frm-config-factura" class="form-horizontal">
<div class="col-sm-6">
<div class="box box-flat box-solid">
<div class="box-header with-border">
<h3 class="box-title">Facturación</h3>
</div>
<div class="box-body">
                               

<div class="form-group">
<label class="control-label col-sm-7 col-xs-6">Impuestos</label>
<div class="col-sm-5 col-xs-6">
<select type="select" style="width: 100%;" class="form-control" name="conf-impuesto" id="conf-impuesto">
<option value="0">Mas Impuestos</option>
<option value="1">Impuestos incluido</option>
</select>
</div>
</div>


<div class="text-center">
<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar cambios</button>
</div>
</div>
</div>
</div>

<div class="col-sm-6">
<div class="box box-flat box-solid">
<div class="box-header with-border">
<h3 class="box-title">Notificaciones</h3>
</div>
<div class="box-body">

<div class="form-group">
<label class="control-label col-sm-7 col-xs-6">Tipo de aviso factura</label>
<div class="col-sm-5 col-xs-6">
<select type="select" style="width: 100%;" class="form-control select2-hidden-accessible" name="conf-tipoaviso" id="conf-tipoaviso" tabindex="-1" aria-hidden="true">
<option value="0" selected="">Desactivado</option>
<option value="1" selected="">Correo</option>
<option value="2">SMS</option>
<option value="3">Notificaciones Push</option>
<option value="4">Todos</option>
</select>
</div>
</div>
                               

<div class="text-center">
<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-floppy-o" aria-hidden="true"></i> Guardar cambios</button>
</div>
</div>
</div>
</div>
</form>

</div>
</div>

</div>
</div>


<!-- Modal -->
<div class="modal fade" id="ayudafactura" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Próximo día de pago</h4>
      </div>
      <div class="modal-body">
       <p><b>Campo vacío:</b></p><p>
       </p><p>Por defecto podemos dejar este campo vacío, esto permite que el sistema maneje la próxima fecha de pago dinamicamente, por ejemplo si tenemos configurado día de pago 1: esto quiere decir que el 1 de todos los meses será su día de pago e iniciará su facturación el próximo 1.</p><br><br>
       
       <p><b>Seleccionar fecha:</b></p><p>
       </p><p>Al indicar una fecha especifica estamos indicando al sistema que la facturación/día de pago próxima de este cliente iniciará en la fecha que indiquemos en este campo.
       Esta fecha será reseteado (vacío) al cumplir con la fecha seleccionado.
       Indicar una próxima fecha de pago incorrecta puede ocasionar que no se cree la factura del mes deseado.(fecha pasadas).
       Se debe seleccionar una fecha con el mismo día de pago asignado a nuestro cliente, si nuestro cliente tiene dia de pago 5, debemos seleccionar cualquier mes pero día 5.
       </p><br><br>
      </div>

    </div>
  </div>
</div>


<script type="text/javascript">
var base_url="<?=base_url(); ?>";
var id_cliente="<?=$id;?>";

//--->Config factura

$('#conf-impuesto option[value="<?=$impuesto?>"]').prop('selected',true); 
$('#conf-tipoaviso option[value="<?=$tipoaviso?>"]').prop('selected',true); 

var factura,pagos,saldos,noprocesado;
function update_factura(){
factura.ajax.reload( null, false );
}

function update_procesado(){
noprocesado.ajax.reload( null, false ); 
}

function update_pago(){
pagos.ajax.reload( null, false ); 
}
function update_saldo(){
saldos.ajax.reload( null, false );  
}

$(function() {

factura=$('#list-factura-cliente').DataTable( {
"language": {
 "url": base_url+"public/js/Datatables-es.json"
        },
"processing": true,
"stateSave": true,
"ajax": base_url+"facturas/get_all_facturas_by_cliente_json?id="+id_cliente,
//"ajax": "ajaxfacturas.php?action=list&state="

"deferRender": true,
"aoColumns": [
{ mData: 'id'},
{ mData: 'num_legal' },
{ mData: 'nombre_cliente' },
{ mData: 'fecha_emision' ,"bVisible": false },
{ mData: 'fecha_vencimiento',"bVisible": false },
{ mData: 'fecha_pago'} ,
{ mData: 'total'},
{ mData: 'cobrado'},
{ mData: 'saldo'},
{ mData: 'forma_pago'} ,
{ mData: 'estatus' ,sClass: "dt-center"},
{ mData: 'id','render': function ( data, type, full, meta ) {
var coltool=data.split('-');
if(full['enabled']=="exist" || full['anulado']==2 || full['anulado']==1){
var disab='disabled';
}else{
var disab='';
}
return '<button type="button" '+disab+' class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Editar Factura" Onclick="edit_factura(\''+coltool[0]+'\',\''+base_url+'\');"><span class="glyphicon glyphicon glyphicon-edit"></span></button><button '+disab+' type="button" class="btn btn-xs btn-default bt-delete css-tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Eliminar Factura"  Onclick="delete_factura(\''+coltool[0]+'\',\''+base_url+'\');"><span class="glyphicon glyphicon-trash"></span></button><button type="button" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Enviar por correo"  Onclick="mail_factura(\''+coltool[0]+'\',\''+base_url+'\');"><span class="glyphicon glyphicon-envelope"></span></button><button type="button" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Descargar PDF"  Onclick="down_factura(\''+coltool[0]+'\',\''+base_url+'\');"><span class="glyphicon glyphicon-save"></span></button><button type="button" '+disab+' class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Anular factura" Onclick="anulado_factura(\''+coltool[0]+'\',\''+base_url+'\');"><span class="glyphicon glyphicon-ban-circle"></span></button>';
   
 },sWidth:'70px'},
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
      title:'Facturas',
            text: '<i class="fa fa-print"></i> Imprimir',
        },
     {
            extend: 'csvHtml5',
      title:'Facturas',
            text: '<i class="fa fa-file-excel-o"></i> Exportar a EXCEL',
        },
     {
            extend: 'pdfHtml5',
      title:'Facturas',
      orientation: 'landscape',
            text: '<i class="fa fa-file-pdf-o"></i> Exportar a PDF',
        } ]
      }
        ],
"initComplete": function() {
$('#list-factura-cliente_wrapper .dt-buttons').after('<div class="btn-group dt-btns"></div>');

//-------->Boton refrescar
$('#list-factura-cliente_wrapper .dt-buttons').append('<a class="btn btn-sm btn-default buttons-collection" onClick="update_factura()"><i class="fa fa-refresh" ></i></a>');
//-------->Button Nuevo factura
$('#list-factura-cliente_wrapper .dt-btns').append('<button type="button" class="btn btn-sm btn-default " onClick="new_factura_libre(\''+id_cliente+'\',\''+base_url+'\')"><b><i class="fa fa-plus"></i></b> Nueva Factura Libre</button>');


 }
 } );  

 
pagos=$('#list-pago-cliente').DataTable( {
"language": {
 "url": base_url+ "public/js/Datatables-es.json"
        },
"stateSave": true,
"ajax":base_url+ "Transacciones/get_all_transacciones_by_cliente_json?action=lista&id="+id_cliente,
"deferRender": true,
"aoColumns": [
{ mData: 'id'},
{ mData: 'cliente' },
{ mData: 'factura' },
{ mData: 'legal' },
{ mData: 'transaccion' },
{ mData: 'forma_pago',"bVisible": false} ,
{ mData: 'fecha_pago',sType: 'date-euro'},
{ mData: 'operador',"bVisible": false},
{ mData: 'cobrado'} ,
{ mData: 'comision',"bVisible": false} ,
{ mData: 'neto',"bVisible": false} ,
{ mData: 'descripcion',"bVisible": false} ,

{ mData: 'tool','render': function ( data, type, full, meta ) {
 return '<button type="button" class="btn btn-xs btn-default bt-delete css-tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Eliminar Pago Registrado"  Onclick="delete_pago2(\''+full['id']+'\',\''+full['factura']+'\',\''+base_url+'\');"><span class="glyphicon glyphicon-trash"></span></button>';
    },sWidth:'30px',sClass: "dt-center"},
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
      title:'Transacciones',
            text: '<i class="fa fa-print"></i> Imprimir',
        },
     {
            extend: 'csvHtml5',
      title:'Transacciones',
            text: '<i class="fa fa-file-excel-o"></i> Exportar a EXCEL',
        },
     {
            extend: 'pdfHtml5',
      title:'Transacciones',
      orientation: 'landscape',
            text: '<i class="fa fa-file-pdf-o"></i> Exportar a PDF',
        } ]
      }
        ],
"initComplete": function() {
$('#list-pago-cliente_wrapper .dt-buttons').after('<div class="btn-group dt-btns"></div>');

//-------->Boton refrescar
$('#list-pago-cliente_wrapper .dt-buttons').append('<a class="btn btn-sm btn-default buttons-collection" onClick="update_pago()"><i class="fa fa-refresh" ></i></a>');

 }
 } );

saldos=$('#list-saldo-cliente').DataTable( {
"language": {
 "url": base_url+"public/js/Datatables-es.json"
        },
"ajax": base_url+"transacciones/get_all_saldo_by_cliente_json?action=getpagosno&id="+id_cliente,
"deferRender": true,
"stateSave": true,
"aoColumns": [
{ mData: 'id'},
{ mData: 'idorigen'},
{ mData: 'descripcion'},
{ mData: 'monto'},
{ mData: 'fecha',sType: 'date-uk'},
{ mData: 'estado'},
{ mData: 'tool','render': function ( data, type, full, meta ) {
   return '<button type="button" class="btn btn-xs btn-default bt-delete css-tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Eliminar saldo pendiente" Onclick="delete_saldo(\''+full['id']+'\',\''+base_url+'\');"><span class="glyphicon glyphicon-trash"></span></button>';
    },sWidth:'30px',sClass: "dt-center"},
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
      title:'Saldo clientes',
            text: '<i class="fa fa-print"></i> Imprimir',
        },
     {
            extend: 'csvHtml5',
      title:'Saldo clientes',
            text: '<i class="fa fa-file-excel-o"></i> Exportar a EXCEL',
        },
     {
            extend: 'pdfHtml5',
      title:'Saldo clientes',
      orientation: 'landscape',
            text: '<i class="fa fa-file-pdf-o"></i> Exportar a PDF',
        } ]
      }
        ],
"initComplete": function() {
$('#list-saldo-cliente_wrapper .dt-buttons').after('<div class="btn-group dt-btns"></div>');

//-------->Boton refrescar
$('#list-saldo-cliente_wrapper .dt-buttons').append('<a class="btn btn-sm btn-default buttons-collection" onClick="update_saldo()"><i class="fa fa-refresh" ></i></a>');

//-------->Button Nuevo saldo
//$('#list-saldo-cliente_wrapper .dt-btns').append('<button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#myModalsaldo"><b><i class="fa fa-plus"></i></b> Agregar Saldo</button>');

 },
 "fnDrawCallback": function( oSettings ) {

    }
 } );
 
$('.nav-pills, .nav-tabs').tabdrop();
$("#conf-impuesto,#conf-tipoaviso").select2();


$("a[data-toggle=\"tab\"]").on("shown.bs.tab", function (e) {
$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
});


//+++++ SAVE DATOS
$('#frm-config-factura').ajaxForm({
            url: base_url+'clientes/update_config_client?action=gdconfigfactura&id='+id_cliente,
      type: "POST",
      delegation: true,
            success: function(result) {
      console.log(result);        
      toastr.clear();        
      if(result=="error"){
      msgbox("danger","Mensaje","Error al guardar cambios",3000);  
      }else{
      msgbox("success", "Mensaje","Datos guardados correctamente",2000);
      }
      },
            beforeSubmit: function() {
      msgbox("loader","Mensaje", "procesando...",0);
            },
            error: function(response) {
      toastr.clear();
            msgbox("danger","Error al procesar datos, vuelva a intentar",4);
            }
 });



 
});

</script>

<!-- Modal New Saldo -->
<div class="modal fade" id="myModalsaldo" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog modal-sm" role="document">
    <div class="modal-content">
   <form id="frmsaldonew">
   <input type="hidden" name="iduser" value="000002">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Registrar Saldo</h4>
      </div>
      <div class="modal-body">
      <small>Aquí puede agregar saldos a la cuenta del cliente ya sea un saldo a favor o descuento (Ejm: -100) o un saldo en contra (Ejm: 100)</small>
<br>
<div class="form-group">
    <label for="txtnewsaldo">Ingrese el monto</label>
    <div class="input-group">
      <div class="input-group-addon">$</div>
      <input type="number" class="form-control" name="txtnewsaldo" placeholder="100" step="0.005">
    </div>
</div>

<div class="form-group">
    <label>Descripción</label>
      <input type="text" class="form-control" name="descripcionsaldo" placeholder="Ejm: Descuento en proxima factura" step="0.005">
</div>

<small>NOTA: Recuerde que el saldo agregado será aplicado en la próxima factura servicio que sea creado.</small>
  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Registrar</button>
      </div>
      
 </form>     
    </div>
  </div>
</div>

<?= view("facturacion/facturas/modal_edit_factura") ?> 

<?= view("facturacion/facturas/modal_new_factura_libre") ?> 
      
