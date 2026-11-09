<table id="list-pagoshoy" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%" style="width: 100%;">
<thead>
<tr role="row">
	<th class="all column">ID</th>
	<th class="column">Cliente</th>
	<th class="column"># Factura</th>
	<th class="column"># Legal</th>
	<th class="column"># Transaccion</th>
	<th class="column">Fecha & Hora</th>
	<th class="column">Operador</th>
	<th class="column">Cobrado</th>
	<th class="all column" data-orderable="false" style="max-width: 45px; min-width: 36px !important; width: 45px;">Acción</th>
</tr>
</thead>
</table>

<script type="text/javascript">
	var pagohoy;
  load_list_pagos=function(){
  if ( ! $.fn.DataTable.isDataTable( '#list-pagoshoy' ) ) {
  pagohoy=$('#list-pagoshoy').DataTable( {
"language": {
 "url": "public/js/Datatables-es.json"
        },
"stateSave": true,
"ajax": base_url+"pagos/get_pagos_hoy_json",
"deferRender": true,
"aoColumns": [
{ mData: 'id','render': function ( data, type, full, meta ) {  var coltool=data.split('-'); return coltool[0];},sClass: 'dt-center'},
{ mData: 'nombre' },
{ mData: 'id_factura' },
{ mData: 'num_legal' },
{ mData: 'transaccion' },
{ mData: 'fecha_pago'},
{ mData: 'operador'},
{ mData: 'cobrado'} ,
//{ mData: 'tool',sWidth:'10px'},
{ mData: 'id','render': function ( data, type, full, meta ) {
  var coltool=data.split('-');
  return '<button type="button" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Imprimir recibo"   Onclick="print_recibo(\''+coltool[0]+'\',\''+base_url+'\');"><span class="fa fa-print"></span></button>'+
         '<button type="button" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Imprimir factura" Onclick="print_factura(\''+full['id_factura']+'\',\''+base_url+'\');"><span class="fa fa-file-pdf-o"></span></button>';
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
			title:'Transacciones Hoy',
            text: '<i class="fa fa-print"></i> Imprimir',
        },
		 {
            extend: 'csvHtml5',
			title:'Transacciones',
            text: '<i class="fa fa-file-excel-o"></i> Exportar a EXCEL',
        },
		 {
            extend: 'pdfHtml5',
			title:'Transacciones Hoy',
			orientation: 'landscape',
            text: '<i class="fa fa-file-pdf-o"></i> Exportar a PDF',
        } ]
      }
        ],
"initComplete": function() {
$('#list-pagoshoy_wrapper .dt-buttons').after('<div class="btn-group dt-btns"></div>');

//-------->Boton refrescar
$('#list-pagoshoy_wrapper .dt-buttons').append('<a class="btn btn-sm btn-default buttons-collection" onClick="update_pago()"><i class="fa fa-refresh" ></i></a>');

}
 } );
 }
 else{update_pago();}
 }

 function update_pago(){
pagohoy.ajax.reload( null,false);	
}
</script>