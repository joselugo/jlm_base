
<div class="row">
<div class="col-sm-12">
<h4>Correos</h4>

<table id="list-email-user" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%" style="width: 100%;">
<thead>
<tr role="row">
<th class="column sorting_desc">ID</th>
<th class="column all sorting">Asunto</th>
<th class="column sorting">Fecha</th>
<th class="column sorting">Remitente</th>
<th class="column sorting">Destino</th>
<th class="column all sorting">Estado</th>
<th class="column all sorting_disabled" data-orderable="false" style="max-width: 10px; min-width: 10px !important; width: 10px;">Acción</th>
</tr>
</thead>
</table>
</div>

<div class="col-sm-12">
<h4>Mensaje de texto</h4>

<table id="list-sms-user" class="table table-striped table-bordered dt-responsive dataTable no-footer dtr-inline" cellspacing="0" width="100%" style="width: 100%;">
<thead>
<tr role="row">
<th class=" column sorting_desc">ID</th>
<th class=" column all sorting">Mensaje</th>
<th class=" column all sorting">Estado</th>
<th class=" column sorting"># Destino</th>
<th class=" column sorting">Fecha</th>
<th class=" column all" data-orderable="false" style="max-width: 40px; min-width: 40px !important; width: 40px;">Acción</th>
</tr>
</thead>
</table>
</div>
</div>

<script>
$(function(){
	
var usermail;
$(function(){
usermail=$('#list-email-user').DataTable( {
"language": {
 "url": base_url+ "public/js/Datatables-es.json"
        },
"ajax": base_url+ "clientes/get_email_json?action=listmailuser&id="+id_cliente,
"deferRender": true,
"stateSave": true,
"aoColumns": [
{ mData: 'id'},
{ mData: 'asunto'},
{ mData: 'fecha',sType: 'date-euro'},
{ mData: 'remitente'},
{ mData: 'destino'},
{ mData: 'estado'},
{ mData: 'tool','render': function ( data, type, full, meta ) {
return '<button type="button" class="btn btn-xs btn-default bt-delete css-tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Ver correo"  Onclick="viewmail(\''+full['id']+'\',\''+base_url+'\');"><i class="fa fa-envelope-o"></i></button><button type="button" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Volver Enviar correo"  Onclick="resendmail(\''+full['id']+'\',\''+base_url+'\');"><i class="fa fa-paper-plane-o"></i></button>';
    } },
],
dom: 'Bfrtip',
        lengthMenu: [
            [10,15, 25, 50,100, -1],
            [ '10 Registros','15 Registros', '25 Registros', '50 Registros', '100 Registros', 'Mostrar todos' ]
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
			title:'Lista Correos usuario',
            text: '<i class="fa fa-print"></i> Imprimir',
        },
		 {
            extend: 'csvHtml5',
			title:'Lista Correos usuario',
            text: '<i class="fa fa-file-excel-o"></i> Exportar a EXCEL',
        },
		 {
            extend: 'pdfHtml5',
			title:'Lista Correos usuario',
			orientation: 'landscape',
            text: '<i class="fa fa-file-pdf-o"></i> Exportar a PDF',
        } ]
      }
        ],
"initComplete": function() {
$('#list-email-user_wrapper .dt-buttons').after('<div class="btn-group dt-btns"></div>');

//-------->Boton refrescar
$('#list-email-user_wrapper .dt-buttons').append('<a class="btn btn-sm btn-default buttons-collection" onClick="updatemail()"><i class="fa fa-refresh" ></i></a>');
//-------->Button Nuevo
$('#list-email-user_wrapper .dt-btns').append('<button type="button" class="btn btn-sm btn-primary" onClick="newmail(\''+id_cliente+'\',\''+base_url+'\')"><i class="fa fa-plus"></i> Nuevo correo</button>');

 }
  
 } );


updatemail=function(){
usermail.ajax.reload( null, false );
}

var usersms;

usersms=$('#list-sms-user').DataTable( {
"language": {
 "url": base_url+"public/js/Datatables-es.json"
        },
"ajax": base_url+ "clientes/get_sms_json?action=listmailuser&id="+id_cliente,
"deferRender": true,
"stateSave": true,
"aoColumns": [
{ mData: 'id'},
{ mData: 'mensaje'},
{ mData: 'estado'},
{ mData: 'destino'},
{ mData: 'fecha',sType: 'date-euro'},
{ mData: 'tool','render': function ( data, type, full, meta ) {
return '<button type="button" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Volver Enviar sms"  Onclick="resendsms(\''+full['id']+'\',\''+base_url+'\');"><i class="fa fa-paper-plane-o"></i></button>';
    },sClass:'dt-center' },
],
dom: 'Bfrtip',
        lengthMenu: [
            [10,15, 25, 50,100, -1],
            [ '10 Registros','15 Registros', '25 Registros', '50 Registros', '100 Registros', 'Mostrar todos' ]
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
			title:'Lista sms usuario',
            text: '<i class="fa fa-print"></i> Imprimir',
        },
		 {
            extend: 'csvHtml5',
			title:'Lista sms usuario',
            text: '<i class="fa fa-file-excel-o"></i> Exportar a EXCEL',
        },
		 {
            extend: 'pdfHtml5',
			title:'Lista sms usuario',
			orientation: 'landscape',
            text: '<i class="fa fa-file-pdf-o"></i> Exportar a PDF',
        } ]
      }
        ],
"initComplete": function() {
$('#list-sms-user_wrapper .dt-buttons').after('<div class="btn-group dt-btns"></div>');

//-------->Boton refrescar
$('#list-sms-user_wrapper .dt-buttons').append('<a class="btn btn-sm btn-default buttons-collection" onClick="updatesms()"><i class="fa fa-refresh" ></i></a>');
//-------->Button Nuevo
$('#list-sms-user_wrapper .dt-btns').append('<button type="button" class="btn btn-sm btn-primary" onClick="newsms(\''+id_cliente+'\',\''+base_url+'\')"><i class="fa fa-plus"></i> Nuevo SMS</button>');

 }
  
 } );


updatesms=function(){
usersms.ajax.reload( null, false );
}

})


})


</script>

<div id="tmp"><div class="modal fade in" id="modalmail">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></button>
<h4 class="modal-title"><i class="fa fa-list"></i> Correo <strong id="id-modal-mail"></strong></h4>
</div>
<div class="modal-body">
<div class="mailbox-read-info">
<h3 id="asunto-modal-mail"></h3>
<h5 id="remitente-modal-mail"></h5>
<h5 id="destino-modal-mail"></h5>
</div>
<div id="log-modal-error">

</div>
<div class="mailbox-read-message" id="cuerpo-modal-mail">

</div>
<p><b>Archivos Adjuntos:</b></p>
<ul class="mailbox-attachments clearfix" id="adjunto-modal-mail">
        
</ul>
</div>

</div>
</div>
</div>
</div>

<div class="modal fade in" id="modalnewmail">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<form class="form-horizontal" id="frmmail">

<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></button>
<h4 class="modal-title"><i class="fa fa-list"></i> Nuevo Correo</h4>
</div>
<div class="modal-body">
<div class="row">



<div class="col-sm-7">
<div class="form-group">
<label class="col-sm-3 control-label">Asunto</label>
<div class="col-sm-9">
<input type="text" class="form-control" name="asuntomailnew" id="asuntomailnew" required="" autofocus="">
</div>
</div>
</div>

<div class="col-sm-5">
<div class="form-group">
<label class="col-sm-4 control-label">Remitente</label>
<div class="col-sm-8">
<input type="email" class="form-control" name="remitentemailnew" id="remitentemailnew">
<input type="hidden" name="destinomailnew" id="destinomailnew">
<input type="hidden" name="idmailnew" id="idmailnew">
<input type="hidden" name="nombremailnew" id="nombremailnew">
</div>
</div>
</div>

<div class="col-sm-7">
<div class="form-group">
<label class="col-sm-3 control-label">Archivo</label>
<div class="col-sm-9">
<input type="file" class="form-control" name="adjuntomailnew" accept="image/*,.pdf,.zip" onchange="tamano(this)">
<small>Tamaño Máximo : 5M</small>
</div>
</div>
</div>


 <div class="col-sm-12">
<textarea id="cuerpomailnew" name="cuerpomailnew">
</textarea>
</div>

</div>                
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
<button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane-o"></i> Enviar correo</button>
</div>
</form>
</div>
</div>
</div>
<script>
function tamano(items){
var fileSize = items.files[0].size;
if(fileSize>5242880){
items.value=""; 
alert("No se permite Subir archivos mas de 5M");
}
}
$(function(){


//+++++ procesa
$('#frmmail').ajaxForm({
            url: base_url+'clientes/new_mail',
            type: "POST",
            delegation: true,
            success: function(result) {
            console.log(result)    
            toastr.clear()
           
            if(result=="ERROR_MYSQL"){
            msgbox("error","" ,result,4000);  
            }else{
            updatemail(); send_new_mail(result,base_url);
            }
            },
            beforeSubmit: function() {
            msgbox("loader","", "procesando...",0);
            $('#modalnewmail').modal('hide')
            },
            error: function(response) {
            $('.notydiv').remove();
            msgbox("error","Mensaje", "Error al procesar datos, vuelva a intentar",4000);
            }
 });
 

CKEDITOR.replace('cuerpomailnew');
$('#modalnewmail').on('shown.bs.modal', function () {
$('#asuntomail').focus();
})
})
</script>

<div class="modal fade in" id="modalnewsms">
<div class="modal-dialog modal-md" role="document">
<div class="modal-content">
<form class="form-horizontal" id="frmsms">

<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></button>
<h4 class="modal-title"><i class="fa fa-list"></i> Enviar Mensaje de Texto.</h4>
</div>
<div class="modal-body">
<div class="row">

<div class="col-sm-12">
<label>Mensaje a enviar</label>
<input name="idsms" type="hidden" value="<?=$id?>">
<input name="movilsms" type="hidden" value="<?=$movil?>">
<textarea class="form-control" id="newsms" name="newsms" rows="4" onkeyup="maximo();" required=""></textarea>
<div class="counter" style="text-align:center; width:100%">Caracteres <b>0/160</b></div>
</div>

</div>                
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
<button type="submit" class="btn btn-primary btnnewsms"><i class="fa fa-paper-plane-o"></i> Enviar sms</button>
</div>
</form>
</div>
</div>
</div>
<script>
function maximo(){
if($("#newsms").val().length>=160){
$('#newsms').val($("#newsms").val().substring(0,160));
 }
$('.counter').html('Caracteres <b>'+$("#newsms").val().length+'/160</b>');
}

$(function(){
    


//+++++ procesa
$('#frmsms').ajaxForm({
            url: base_url + 'clientes/new_sms',
            type: "POST",
            delegation: true,
            success: function(result) {
            toastr.clear();
            if(result=="ERROR_MYSQL"){
            msgbox("error","Mensaje",result,4000);  
            }else{
            msgbox("success","Mensaje","Mensaje de texto enviado.",2000);
            updatesms();send_new_sms(result,base_url);
            }
            },
            beforeSubmit: function() {
            msgbox("loader","","procesando...",0);
            $('#modalnewsms').modal('hide')
            },
            error: function(response) {
            toastr.clear();
            msgbox("error","mensaje","Error al procesar datos, vuelva a intentar",4000);
            }
 });
 

$('#modalnewsms').on('shown.bs.modal', function () {
$('#newsms').focus();
})
})
</script>

