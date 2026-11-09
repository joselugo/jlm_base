<div id="tmp"></div>
<section class="content" id="bodysystem">
<div class="box box-flat box-solid">
<div class="box-header with-border">
<h3 class="box-title">Transacciones</h3>
</div>
<div class="box-body">
<form class="form-horizontal" style="display: inline-block;" id="frmoperaciones" target="_blank" action="<?=base_url();?>pdf/transacciones?action=operaciones" method="post">

<div class="col-sm-6">
<div class="form-group">
<label class="col-sm-3 control-label">Tipo Pago</label>
<div class="col-sm-9">
<select class="form-control filtroop" id="forma_pago" name="forma_pago">
<option value="0">Cualquiera</option>
<?=$option_forma_pago?>
</select>
</div>
</div>
</div>  
  
<div class="col-sm-6">
<div class="form-group">
<label class="col-sm-3 control-label">Operador</label>
<div class="col-sm-9">
<select class="form-control filtroop" id="operador" name="operador" style="width:100%">
<option value="0">Cualquiera</option>
<?=$option_personal?>
</select>
</div>
</div>
</div>  


<div class="col-sm-6">
<div class="form-group">
<label class="col-sm-3 control-label">Fechas</label>
<div class="col-sm-9">
<div style="width:50%; float:left;padding-right: 5px;">
<input type="text" class="form-control filtroop input-sm desde" id="desde" name="desde" readonly="" value="2018-06-01">
<small>Desde</small>
</div>
<div style="width:50%; float:left;padding-right: 5px;">
<input type="text" class="form-control filtroop input-sm hasta" id="hasta" name="hasta" readonly="" value="2018-06-30">
<small>Hasta</small>
</div>
</div>
</div>
</div>  

<div class="col-sm-6">
<div class="form-group">
<label class="col-sm-3 control-label">Sucursal</label>
<div class="col-sm-9">
<select class="form-control filtroop" id="sucursal" name="sucursal">
<option value="%%">Cualquiera</option>
<?=$option_sucursal?>
</select>
</div>
</div>
</div>  

</form>

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

<script>
var operaciones,sloperador,slsucursal;
var base_url="<?=base_url();?>"
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


function update_factura(){
}
function loadSettingsop() {
$('#forma_pago option[value="' + localStorage.op_forma + '"]').prop('selected',true);
$('#operador option[value="' + localStorage.op_operador + '"]').prop('selected',true);
$('#sucursal option[value="' + localStorage.op_sucursal + '"]').prop('selected',true);
$('#desde').val(localStorage.op_desde);
$('#hasta').val(localStorage.op_hasta);
}

function update_pago(){
operaciones.ajax.reload( null,false); 
}

function saveSettingsop() {
localStorage.op_forma = $('#forma_pago').val();
localStorage.op_operador = $('#operador').val();
localStorage.op_desde = $('#desde').val();
localStorage.op_hasta = $('#hasta').val();
localStorage.op_sucursal = $('#sucursal').val();
}

function reset_operaciones(){
$('#forma_pago option[value="0"]').prop('selected',true);
$('#desde').val("<?=$fecha_hoy; ?>");
$('#hasta').val("<?=$fecha_hoy; ?>");
slsucursal.val("%%").trigger("change")
sloperador.val("0").trigger("change")}
if(localStorage.op_desde){
loadSettingsop();
}


$(function(){
operaciones=$('#list-pago-cliente').DataTable( {
"language": {
 "url": base_url+ "public/js/Datatables-es.json"
        },
"stateSave": true,
"ajax":base_url+ "Transacciones/get_all_transacciones_json?action=lista&sucursal="+$('#sucursal').val()+'&forma_pago='+$('#forma_pago').val()+'&operador='+$('#operador').val()+'&desde='+$('#desde').val()+'&hasta='+$('#hasta').val(),
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
 return '<button type="button"  Onclick="edit_pago(\''+full['id']+'\',\''+base_url+'\');" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Editar Datos del pago."><span class="glyphicon glyphicon glyphicon-edit"></span></button> <button type="button" class="btn btn-xs btn-default bt-delete css-tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Eliminar Pago Registrado"  Onclick="delete_pago(\''+full['id']+'\',\''+full['factura']+'\',\''+base_url+'\');"><span class="glyphicon glyphicon-trash"></span></button><button type="button" class="btn btn-xs btn-default" Onclick="print_recibo(\''+full['id']+'\',\''+base_url+'\');" title="Imprimir Recibo"><i class="fa fa-print"></i></button>';
    },sWidth:'30px'},
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

//-------->RESET FILTER
$('#list-pago-cliente_wrapper .dt-btns').append('<button type="button" class="btn btn-sm btn-default" onClick="reset_operaciones()"><i class="fa fa-filter"></i> Resetear Filtro</button>');

//-------->REPORTE PDF
$('#list-pago-cliente_wrapper .dt-btns').append('<button type="button" class="btn btn-sm btn-default" onClick="reporte_operaciones()"><i class="fa fa-file-pdf-o"></i> Reporte PDF</button>');

 }
 } );

$("#operador option").each( function(){

 var $option = $(this);  

 $option.siblings()
       .filter( function(){ return $(this).val() == $option.val() } )
       .remove()


})

sloperador=$("#operador").select2();
slnodo=$("#sucursal").select2();

//--->Fechas
$('.desde,.hasta').datepicker({
language: "es",
dateFormat: "yy-mm-dd",
toggleActive: true,
todayHighlight: true,
autoclose: true
}); 

})

//--------> Filtro 
$( ".filtroop" ).change(function() {
operaciones.ajax.url(base_url+ "Transacciones/get_all_transacciones_json?action=lista&sucursal="+$('#sucursal').val()+'&forma_pago='+$('#forma_pago').val()+'&operador='+$('#operador').val()+'&desde='+$('#desde').val()+'&hasta='+$('#hasta').val()).load();
operaciones.columns.adjust().responsive.recalc();
saveSettingsop();
});

function reporte_operaciones(){
$('#frmoperaciones').submit();
}
</script>
<?= view("facturacion/transacciones/modal_edit_transaccion") ?> 
</section><!-- /.content -->
