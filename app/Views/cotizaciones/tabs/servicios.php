
<div class="box box-flat box-solid">
<div class="box-header with-border">
<h3 class="box-title">Servicios de Internet</h3>
</div>
<div class="box-body">

<table id="list-servicios" class="table-hover table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" width="100%">
<thead>
<tr role="row">
<th class="sorting_asc" tabindex="0" aria-controls="list-servicios" aria-sort="ascending">ID</th>
<th class="all sorting">Plan</th>
<th class="sorting">Costo</th>
<th class="sorting">IP</th>
<th class="sorting">MAC</th>
<th class="sorting">Router</th>
<th class="sorting">Dirección</th>
<th class="sorting">Instalado</th>
<th class="all dt-center sorting">Estado</th>
<th class="all sorting_disabled" data-orderable="false" style="min-width: 20px !important; max-width: 20px !important; width: 20px;">Acción</th>
</tr>
</thead>
</table>
<script>

$(".extrabt").hide();function vermapa(id){
msgbox("loader","procesando...",0);
$.post( "detalleuser.php", { action: "mapa",id:id})
.done(function( datas ) {
$('.notydiv').remove();
$('.tmpmapa').html(datas);
});
}

function getmapa(id){
msgbox("loader","procesando...",0);
$.post( "detalleuser.php", { action: "mapa",coor:id})
.done(function( datas ) {
$('.notydiv').remove();
$('.tmpdetalle').html(datas);
});
}

var servicios;
var updatelist;
var newservice;
var newservice2;
var edit_service;
var edit_service2;
var base_url="<?=base_url(); ?>";
var id_cliente="<?=$id;?>";


$(function(){

servicios=$('#list-servicios').DataTable( {
"language": {
 "url":base_url+"public/js/Datatables-es.json"
        },
"ajax": base_url+ "servicios/get_servicios_by_cliente_json/"+id_cliente,
"deferRender": true,
"stateSave": true,
"aoColumns": [
{ mData: 'id'},
{ mData: 'plan'},
{ mData: 'costo'},
{ mData: 'ip'},
{ mData: 'mac'},
{ mData: 'nodo'},
{ mData: 'direccion'},
{ mData: 'instalado'},
{ mData: 'estado'},

{ mData: 'id' }
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
      title:'Lista servicios',
            text: '<i class="fa fa-print"></i> Imprimir',
        },
     {
            extend: 'csvHtml5',
      title:'Lista de servicios',
            text: '<i class="fa fa-file-excel-o"></i> Exportar a EXCEL',
        },
     {
            extend: 'pdfHtml5',
      title:'Lista servicios',
      orientation: 'landscape',
            text: '<i class="fa fa-file-pdf-o"></i> Exportar a PDF',
        } ]
      }
        ],
"initComplete": function() {

$('#list-servicios_wrapper .dt-buttons').after('<div class="btn-group dt-btns"></div>');

//-------->Boton refrescar
$('#list-servicios_wrapper .dt-buttons').append('<a class="btn btn-sm btn-default" onClick="updatelist()"><i class="fa fa-refresh" ></i></a>');

 }
  
 } );

    
$("a[data-toggle=\"tab\"]").on("shown.bs.tab", function (e) {
$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
});

$('.nav-pills, .nav-tabs').tabdrop();

//--------> UPDATE 
updatelist=function(){
servicios.ajax.reload( null, false );
}


//----------->Editar servicio
edit_service=function(idserv){
event.preventDefault();
$('.tmpdetalle').empty();
msgbox("loader","procesando...",0);
$.post( "formservicios.php", { action: "editservice",id:idserv,idcliente:"001150"})
.done(function( datas ) {
$('.notydiv').remove();
$('.tmpdetalle').html(datas);
});
}

//----------->Editar servicio2
edit_service2=function(idserv){
event.preventDefault();
$('.tmpdetalle').empty();
msgbox("loader","procesando...",0);
$.post( "formservicios.php", { action: "editservice2",id:idserv,idcliente:"001150"})
.done(function( datas ) {
$('.notydiv').remove();
$('.tmpdetalle').html(datas);
});
}

});

</script>
</div>
</div>

<div class="box box-flat box-solid">
<div class="box-header with-border">
<h3 class="box-title">Servicios Voip</h3>
</div>
<div class="box-body">

<table id="list-serviciosvoip" class="table-hover table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" width="100%" role="grid" aria-describedby="list-serviciosvoip_info" style="width: 100%;">
<thead>
<tr role="row">
<th class="sorting_asc" aria-sort="ascending">ID</th>
<th class="all sorting">Plan</th>
<th class="sorting">SIP Server</th>
<th class="sorting">SIP User</th>
<th class="sorting">Authenticate ID</th>
<th class="sorting">Nº telefono</th>
<th class="all sorting">Costo</th>
<th class="sorting">Instalado</th>
<th class="sorting">Notas</th>
<th class="all sorting_disabled" data-orderable="false" style="min-width: 20px !important; max-width: 20px !important; width: 20px;">Acción</th>
</tr>
</thead>
</table>
<script>

function delete_servicevoip(id){
swal({ title: "¿Esta seguro que desea eliminar este servicio Voip ?", 
text: "Eliminar plan.",
type: "warning",
showCancelButton: true,
confirmButtonColor: "#DD6B55",
confirmButtonText: "Si, Eliminar!",
closeOnConfirm: true },
function(){
$('.notydiv').remove();
msgbox("loader","procesando...",0);
$.post( "formservicios.php", { action: "eliminar-serviciovoip",id: id })
.done(function( data ) {
if(data=="error"){
$('.notydiv').remove();
msgbox("danger","Error No se puede conectar al router Mikrotik",4);
}else{
updatelistvoip();
$('.notydiv').remove();
msgbox("success","Servicio eliminado correctamente",2);
}
}); 

});
}


var listavoip,updatelistvoip,newservicevoip,edit_servicevoip;
$(function(){

listavoip=$('#list-serviciosvoip').DataTable( {
"language": {
 "url": base_url + "public/js/Datatables-es.json"
        },
"ajax": base_url+ "servicios/get_servicios_voip_by_cliente_json/"+id_cliente,
"deferRender": true,
"stateSave": true,
"aoColumns": [
{ mData: 'id'},
{ mData: 'plan'},
{ mData: 'server'},
{ mData: 'sipuser'},
{ mData: 'sipid'},
{ mData: 'numerotelefono'},
{ mData: 'costo'},
{ mData: 'instalacion'},
{ mData: 'notas'},
{ mData: 'id' }
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
      title:'Lista servicios',
            text: '<i class="fa fa-print"></i> Imprimir',
        },
     {
            extend: 'csvHtml5',
      title:'Lista de servicios',
            text: '<i class="fa fa-file-excel-o"></i> Exportar a EXCEL',
        },
     {
            extend: 'pdfHtml5',
      title:'Lista servicios',
      orientation: 'landscape',
            text: '<i class="fa fa-file-pdf-o"></i> Exportar a PDF',
        } ]
      }
        ],
"initComplete": function() {

$('#list-serviciosvoip_wrapper .dt-buttons').after('<div class="btn-group dt-btns"></div>');

//-------->Boton refrescar
$('#list-serviciosvoip_wrapper .dt-buttons').append('<a class="btn btn-sm btn-default" onClick="updatelistvoip()"><i class="fa fa-refresh" ></i></a>');
$('#list-serviciosvoip_wrapper .dt-btns').append('<button type="button" class="btnewservice btn btn-sm btn-primary" onClick="newservicevoip();"><i class="fa fa-plus"></i> Nuevo servicio</button>');
 }
  
 } );

//--------> UPDATE 
updatelistvoip=function(){
listavoip.ajax.reload( null, false );
}

//---------->New service voip
newservicevoip=function(){
$('.tmpdetalle').empty();
msgbox("loader","procesando...",0);

$.post( "formservicios.php", { action: "newservicevoip",id:"001150"})
.done(function( datas ) {
$('.notydiv').remove();
$('.tmpdetalle').html(datas);
});
}

//----------->Editar servicio
edit_servicevoip=function(idserv){
event.preventDefault();
$('.tmpdetalle').empty();
msgbox("loader","procesando...",0);
$.post( "formservicios.php", { action: "editservicevoip",id:idserv,idcliente:"001150"})
.done(function( datas ) {
$('.notydiv').remove();
$('.tmpdetalle').html(datas);
});
}

});


</script>
</div>
</div>


