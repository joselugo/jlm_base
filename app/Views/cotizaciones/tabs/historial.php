
<div class="row">
<div class="col-sm-12">
<h4>Historial del cliente</h4>
<table id="list-historial" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%" data-order="[[ 0, &quot;desc&quot; ]]"  style="width: 100%;">
<thead>
<tr role="row">
<th class="all column">ID</th>
<th class="column">Detalle</th>
<th class="column all" style="width:250px">Fecha &amp; Hora</th>
</tr>
</thead>

</table>

</div>

<script>
var base_url="<?=base_url(); ?>"
var loghistorial;
$(function(){
loghistorial=$('#list-historial').DataTable( {
"language": {
 "url": base_url+ "public/js/Datatables-es.json"
        },
"ajax": base_url+"clientes/get_historial_json?action=listlogconex&id="+id_cliente,
"deferRender": true,
"stateSave": true,
"aoColumns": [
{ mData: 'id'},
{ mData: 'descripcion'},
{ mData: 'fecha'}
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
      title:'Lista Conexión usuario',
            text: '<i class="fa fa-print"></i> Imprimir',
        },
     {
            extend: 'csvHtml5',
      title:'Lista Conexión usuario',
            text: '<i class="fa fa-file-excel-o"></i> Exportar a EXCEL',
        },
     {
            extend: 'pdfHtml5',
      title:'Lista Conexión usuario',
      orientation: 'landscape',
            text: '<i class="fa fa-file-pdf-o"></i> Exportar a PDF',
        } ]
      }
        ],
"initComplete": function() {
$('#list-historial_wrapper .dt-buttons').after('<div class="btn-group dt-btns"></div>');

//-------->Boton refrescar
$('#list-historial_wrapper .dt-buttons').append('<a class="btn btn-sm btn-default buttons-collection" onClick="updatehistorial()"><i class="fa fa-refresh" ></i></a>');
 }
  
 } );


updatehistorial=function(){
loghistorial.ajax.reload( null, false );
}
  
})
</script>
</div>