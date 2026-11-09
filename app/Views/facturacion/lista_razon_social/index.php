<section class="content" id="bodysystem"><div class="ibox">
<div class="ibox-title"><h5><i class="fa fa-server"></i> Lista de Facturas</h5></div>
<div class="ibox-content">

  <form class="form-inline" style="margin-bottom:10px">

<div class="form-group">
<label> Mes </label>
<select class="form-control filtrofactura input-sm" id="mes">
  <option value="01">Enero</option>
  <option value="02">Febrero</option>
  <option value="03">Marzo</option>
  <option value="04">Abril</option>
  <option value="05">Mayo</option>
  <option value="06">Junio</option>
  <option value="07">Julio</option>
  <option value="08">Agosto</option>
  <option value="09">Septiembre</option>
  <option value="10">Octubre</option>
  <option value="11">Noviembre</option>
  <option value="12">Diciembre</option>
</select>
</div>

<div class="form-group">
<label> Año </label>
<select class="form-control filtrofactura input-sm " id="anio">
<option value="2018">2018</option>
<option value="2019" selected="">2019</option>
</select>
</div>

</form>

<table id="list-factura-cliente" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%" style="width: 100%;">
<thead>
<tr role="row">
	<th class="column">Nº</th>
	<th class="column">Cliente</th>
	<th class="column">Pre factura</th>
	<th class="column">F. envio</th>
	<th class="column">F. pago</th>
	<th class="column">F. compromiso</th>
  <th class="column">Folio facturado</th>
  <th class="column">Total</th>
  <th class="column">Status</th>
	<th class="all column" data-orderable="false" style="max-width: 20px; min-width: 20px !important; width: 20px;">Acción</th>
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
var base_url="<?=base_url();?>"	
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

 

function update_factura(){
  facturas.ajax.url(base_url+"index.php/facturacion/get_lista_facturas_json?mes="+$('#mes').val()+"&anio="+$('#anio').val()).load();

}
  

$(function(){

  $('#mes option[value="<?=$mes?>"]').attr("selected",true);


facturas=$('#list-factura-cliente').DataTable( {
"language": {
 "url": base_url+"public/js/Datatables-es.json"
        },
"processing": true,
"stateSave": true,
"ajax": base_url+"index.php/facturacion/get_lista_facturas_json?mes="+$('#mes').val()+"&anio="+$('#anio').val(),
"deferRender": true,
"aoColumns": [
{ mData: 'id'},
{ mData: 'nombre' },
{ mData: 'prefactura' },
{ mData: 'fecha_envio' },
{ mData: 'fecha_pago'} ,
{ mData: 'fecha_compromiso'},
{ mData: 'folio'},
{ mData: 'monto'},
{ mData: 'estatus' ,sClass: "dt-center"},
{ mData: 'id','render': function ( data, type, full, meta ) {
if(full['tipoacceso']>2){ 

  return '<button type="button" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Editar" Onclick="edit(\''+full['id']+'\',\''+full['nombre']+'\',\''+base_url+'\');"><span class="glyphicon glyphicon glyphicon-edit"></span></button>'+
         '<button type="button" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Datos fiscales"  Onclick="consult(\''+full['id_lista_facturas']+'\',\''+full['nombre']+'\',\''+base_url+'\');"><span class="fa fa-folder"></span></button>'+
         '<button type="button" class="btn btn-xs btn-default bt-delete css-tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Eliminar"  Onclick="delete_cliente(\''+full['id_lista_facturas']+'\',\''+full['nombre']+'\',\''+base_url+'\');"><span class="glyphicon glyphicon-trash"></span></button>'; 

 }
if(full['tipoacceso']>1){ 
   
   return '<button type="button" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Editar" Onclick="edit(\''+full['id']+'\',\''+full['nombre']+'\',\''+base_url+'\');"><span class="glyphicon glyphicon glyphicon-edit"></span></button>'+
          '<button type="button" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Datos fiscales"  Onclick="consult(\''+full['id_lista_facturas']+'\',\''+full['nombre']+'\',\''+base_url+'\');"><span class="fa fa-folder"></span></button>';
 }
 
 else{
  return '';
 }
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

//-------->Button Nuevo
$('#list-factura-cliente_wrapper .dt-btns').append('<button type="button" class="btn btn-sm btn-default" onClick="new_cliente()"><i class="fa fa-plus"></i> Nuevo</button>');



 }
 } );

//--->Fechas
$('#txt-desde,#txt-hasta').datepicker({
dateFormat: "yy-mm-dd",
toggleActive: true,
todayHighlight: true,
autoclose: true
}); 


//--------> Filtro 
   $( ".filtrofactura" ).change(function() {
   facturas.ajax.url(base_url+"index.php/facturacion/get_lista_facturas_json?mes="+$('#mes').val()+"&anio="+$('#anio').val()).load();
   facturas.columns.adjust().responsive.recalc();
  });



$('body').on('keyup', '#serchwi', function() {
$( "#serchwi" ).autocomplete({
source: base_url+'index.php/calendario/get_clientes_search',
minLength: 2,
select : function(event, ui){
 $( "#id_cliente" ).val(ui.item.id);
}
}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
 var inner_html = '<a><div class="list_item_container"><div class="image"><img src="http://172.29.1.1/admin/images/avatar/cliente/' + item.avatar + '" style="width:30px; height:30px;"></div><div class="autocom">' + item.value + '</div><div class="description">' + item.nodo + '<div class="pull-right"></div></div></div></a>';
  return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append(inner_html)
            .appendTo( ul );
    };
});   


}) //////////end funtion ready


edit=function(id,nombre,base_url){


  $.ajax({
  method: "post",
  url: base_url+ "index.php/facturacion/get_list_factura_by_id",
  data: { id: id },
  dataType: "json"
})
  .done(function( data ) {
   $("#nombre").html(nombre); 
   $("#id").val(data.id);
   $('#prefactura option[value="'+data.prefactura+'"]').attr("selected",true);
   $("#fecha_envio").val(data.fecha_envio); 
   $("#fecha_pago").val(data.fecha_pago); 
   $("#fecha_compromiso").val(data.fecha_compromiso);
   $("#folio").val(data.folio);  
   $("#monto").val(data.monto); 
   $('#estatus option[value="'+data.estatus+'"]').attr("selected",true); 
    $("#comentarios").val(data.comentarios); 
   $("#modal-edit").modal();

  });


 
}


consult=function(id_lista_facturas,nombre,base_url){
  $.ajax({
  method: "post",
  url: base_url+ "index.php/facturacion/get_datos_fiscales",
  data: { id_lista_facturas: id_lista_facturas },
  dataType: "json"
})
  .done(function( data ) {
   $("#nombre1").html(nombre); 
   $("#id_lista_facturas").val(data.id_lista_facturas);
   $("#razon_social").val(data.razon_social); 
   $("#rfc").val(data.rfc); 
   $("#domicilio_fiscal").val(data.domicilio_fiscal); 
   $("#cp").val(data.cp); 
   $("#telefono").val(data.telefono); 
   $("#correo").val(data.correo); 
   $("#modal-edit-fiscales").modal();

  });

}

new_cliente=function(){
  $("#modal-new").modal();

}

delete_cliente=function(id_lista_facturas,nombre,base_url){

   swal({ title: "¿Esta seguro que desea Eliminar a "+nombre+"?", 
           text: "Al eliminar, este cliente se perderan sus datos fiscales.",
           type: "warning",
           showCancelButton: !0,
           confirmButtonColor: "#DD6B55",
           confirmButtonText: "Si, Eliminar!",
           closeOnConfirm: !0 } ,
           function(){

            $.ajax({
                url: base_url+'index.php/facturacion/delete_cliente',
                data: {id_lista_facturas:id_lista_facturas},
                type: 'POST',
                dataType: 'json',
                success: function(response){
                 update_factura(); 
                },
                error: function(e){ 
                  alert('Error processing your request: '+e.responseText);
                }
              });

           }); 

}

</script>

<?= view("facturacion/lista_razon_social/modal_edit") ?> 
<?= view("facturacion/lista_razon_social/modal_edit_datos_fiscales") ?> 
<?= view("facturacion/lista_razon_social/modal_new") ?> 