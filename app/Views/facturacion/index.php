<section class="content" id="bodysystem"><div class="ibox">
<div class="ibox-title"><h5><i class="fa fa-server"></i> Facturas</h5></div>
<div class="ibox-content">
<form class="form-inline" style="margin-bottom:10px">

<div class="form-group">
<label> Fechas </label>
<input type="text" class="form-control filtrofactura input-sm" name="txt-desde" id="txt-desde" readonly="">
</div>
<div class="form-group">
<input type="text" class="form-control filtrofactura input-sm" name="txt-hasta" id="txt-hasta" readonly="">
</div>

<div class="form-group">
<label> Sucursal </label>
<select class="form-control filtrofactura input-sm " name="txt-sucursal" id="txt-sucursal">
<option value="%%">Cualquiera</option>
<?=$option_sucursal; ?>
</select>
</div>

<div class="form-group">
<label> Estado </label>
<select class="form-control filtrofactura input-sm" name="txt-estado" id="txt-estado">
<option value="%%">Cualquiera</option>
<option value="0">No pagadas</option>
<option value="1">Pagadas</option>
<option value="2">Anuladas</option>
</select>
</div>

<button type="button" onclick="resetfilter();" class="btn btn-default"><i class="fa fa-filter"></i> Resetear filtro</button>

</form>

 
<table id="list-factura-cliente" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%" style="width: 100%;">
<thead>
<tr role="row">
	<th class="all column">Nº Factura</th>
	<th class="column">Nº legal</th>
	<th class="column">Cliente</th>
	<th class="column">F. Emitido</th>
	<th class="column">F. Vencimiento</th>
	<th class="column">F. Pago</th>
	<th class="column">Total</th>
	<th class="column">Cobrado</th>
	<th class="column">Saldo</th>
	<th class="column">Forma de pago</th>
	<th class="column">Estado</th>
	<th class="all column" data-orderable="false" style="max-width: 60px; min-width: 60px !important; width: 70px;">Acción</th>
</tr>
</thead>
</table>

</section>
<style type="text/css">

  td{
    font-size: 12px;
  }


</style>




<script>
var base_url="<?=base_url();?>";
var selectnodo;
var facturas;

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

 loadSettingsfc();

function loadSettingsfc() {
$('#txt-desde').val("<?=$primera_fecha_factura; ?>");
$('#txt-hasta').val("<?=$fecha_hoy; ?>");
}


function resetfilter(){
$("#txt-estado option[value='%%']").prop("selected","selected");
$('#txt-desde').val("<?=$primera_fecha_factura; ?>");
$('#txt-hasta').val("<?=$fecha_hoy; ?>");
$('#txt-hasta').trigger("change");
$('#txt-sucursal').val("%%").trigger("change");
}


function update_factura(){

 facturas.ajax.url(base_url+"facturas/get_all_facturas_json?estado="+$('#txt-estado').val()+"&desde="+$('#txt-desde').val()+"&hasta="+$('#txt-hasta').val()+"&sucursal="+$('#txt-sucursal').val()).load();

}
  

$(function(){
facturas=$('#list-factura-cliente').DataTable( {
"language": {
 "url": base_url+"public/js/Datatables-es.json"
        },
"processing": true,
"stateSave": true,
"ajax": base_url+"facturas/get_all_facturas_json?estado="+$('#txt-estado').val()+"&desde="+$('#txt-desde').val()+"&hasta="+$('#txt-hasta').val()+"&sucursal="+$('#txt-sucursal').val(),
//"ajax": "ajaxfacturas.php?action=list&state="

"deferRender": true,
"aoColumns": [
{ mData: 'id'},
{ mData: 'num_legal' },
{ mData: 'nombre_cliente' },
{ mData: 'fecha_emision' },
{ mData: 'fecha_vencimiento' },
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
return '<button type="button" '+disab+' class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Editar Factura" Onclick="edit_factura(\''+coltool[0]+'\',\''+base_url+'\');"><span class="glyphicon glyphicon glyphicon-edit"></span></button><button '+disab+' type="button" class="btn btn-xs btn-default bt-delete css-tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Eliminar Factura"  Onclick="delete_factura(\''+coltool[0]+'\',\''+base_url+'\');"><span class="glyphicon glyphicon-trash"></span></button><button type="button" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Enviar por correo"  Onclick="mail_factura(\''+coltool[0]+'\');"><span class="glyphicon glyphicon-envelope"></span></button><button type="button" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Descargar PDF"  Onclick="down_factura(\''+coltool[0]+'\');"><span class="glyphicon glyphicon-save"></span></button><button type="button" '+disab+' class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Anular factura" Onclick="anulado_factura(\''+coltool[0]+'\');"><span class="glyphicon glyphicon-ban-circle"></span></button>';
   
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

 }
 } );

//--->Fechas
$('#txt-desde,#txt-hasta').datepicker({
dateFormat: "yy-mm-dd",
toggleActive: true,
todayHighlight: true,
autoclose: true
}); 

})

//--------> Filtro 
$( ".filtrofactura" ).change(function() {
 facturas.ajax.url(base_url+"facturas/get_all_facturas_json?estado="+$('#txt-estado').val()+"&desde="+$('#txt-desde').val()+"&hasta="+$('#txt-hasta').val()+"&sucursal="+$('#txt-sucursal').val()).load();
facturas.columns.adjust().responsive.recalc();
});

$( "#rango" ).change(function() {
if($("#rango option:selected").text()=="Intervalo de fecha personalizado"){
$('.persol').show();
}else{
$('.persol').hide();
}
});
$('.persol').hide();
</script>

<?= view("facturacion/facturas/modal_edit_factura") ?> 