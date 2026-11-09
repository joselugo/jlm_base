

<form id="frmnota" name="frmnota">
<div class="modal fade in" id="modalnota" tabindex="-1" role="dialog">
<div class="modal-dialog modal-lg" role="document">
<div class="modal-content">
<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></button>
<h4 class="modal-title"><i class="fa fa-list"></i> Registrar nueva Nota</h4>
</div>
<div class="modal-body">
<div class="row">

<div class="col-sm-12">    
<div class="form-group input-group-sm">
<label>Titulo de la Nota</label>
<input class="form-control" id="notatitulo" name="notatitulo" type="text" required="">
</div>
</div>

<div class="col-xs-12">    
<div class="form-group input-group-sm">
<label>Subir Fotos (Máximo : 5M)</label>
<input class="form-control" id="notadjunto" name="notadjunto" accept="image/*,.doc,.docx,.txt,.pdf,.xls,.zip" type="file">
</div>
</div>

<div class="col-sm-12">
<textarea id="newnota" name="newnota" class="form-control noimp" style="visibility: hidden; display: none;"></textarea>
</div>

</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Guardar</button>
</div>
</div>
</div>
</div>
</form>


<div class="box box-flat box-solid">
<div class="box-header with-border" style="padding: 5px;">
<h3 class="box-title"><i class="fa fa-sticky-note-o" aria-hidden="true"></i> Notas <div class="pull-right"><button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modalnota"><i class="fa fa-plus"></i> Nueva Nota</button></div></h3>
</div>
<div class="box-body" style="max-height:300px;overflow-y: scroll;background-color: #ecf0f5;">
<ul class="timeline salidanota" style="overflow-y: scroll;">

<script>
function delete_notas(e) {
    swal({
        title: "¿Esta seguro que desea eliminar esta nota ?",
        text: "Una vez eliminado no podrá ser recuperado.",
        type: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si, Eliminar!",
        closeOnConfirm: !0
    }, function() {
        toastr.clear(), msgbox("loader","Mensaje", "procesando...", 0), $.post(base_url+"clientes/delete_nota", {
            action: "deletedoc",
            id: e
        }).done(function() {
             toastr.clear(); 
            var o = ".trnota" + e;
            $(o).remove(), msgbox("success","Mensaje", "Nota eliminada correctamente", 2000)
        })
    })
}
function delete_doc(e) {
    swal({
        title: "¿Esta seguro que desea eliminar esta nota ?",
        text: "Una vez eliminado no podrá ser recuperado.",
        type: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si, Eliminar!",
        closeOnConfirm: !0
    }, function() {
        toastr.clear(), msgbox("loader","Mensaje", "procesando...", 0), $.post(base_url+"clientes/delete_nota", {
            action: "deletedoc",
            id: e
        }).done(function() {
             toastr.clear(); 
             loaddoc(id_cliente);
             msgbox("success","Mensaje", "Nota eliminada correctamente", 2000)
        })
    })
}
</script>
</ul>

</div>
</div>

<form id="frmdoc">
<div class="box box-flat box-solid">
<div class="box-header with-border" style="padding: 5px;">
<h3 class="box-title"><i class="fa fa-file-text-o" aria-hidden="true"></i> Documentos(PDF) 
<div class="pull-right">
<label class="btn btn-primary btn-file btn-xs"><i class="fa fa-plus"></i> Subir Documento
<input id="docadjunto" name="docadjunto" accept=".pdf" type="file" style="display:none"></label>
</div>
</h3>
</div>
<div class="box-body">
<table id="tbldocumentos" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%" style="width: 100%;">

  <thead>
    <tr>
      <th>Archivo</th>
      <th>Fecha</th>
     <th class="center" data-orderable="false" style="width:46px">Acción</th>

    </tr>
  </thead>
  <tbody class="salidadocumento">
    
  </tbody>
</table>
<script>$("#tbldocument").DataTable({
  "paging":   false,
        "ordering": false,
        "info":     false,
    "bFilter": false,
    "language": {
 "url": base_url+"public/js/Datatables-es.json"
        }
    } );;</script></div>
</div>
</form>

<script>
var base_url="<?=base_url();?>"
function loadnotas(id){
$.post( base_url + "clientes/notas", { action: "listnota",id: id })
.done(function(data) {
  $('.salidanota').html(data);
 });  
}

function loaddoc(id){
$.post( base_url + "clientes/documentos", { action: "listdoc",id: id })
.done(function(data) {
$('.salidadocumento').html(data);
 });  
}

function dowload_pdf(id,file){
$.post( base_url + "clientes/dowload_doc", { file:file ,id: id })
.done(function(data) {
 
 });  
}




loadnotas(id_cliente);

loaddoc(id_cliente);

$(function(){

$('#docadjunto').bind('change', function() {
var fileSize = this.files[0].size;
if(fileSize>5242880){
this.value=""; 
alert("No se permite Subir archivos mas de 5M");
}else{
//Procesa Formulario
$('#frmdoc').ajaxForm({
      url:base_url+ 'clientes/new_doc?action=newnota&id='+id_cliente,
      type: "POST",
      delegation: true,
            success: function(result) {
      toastr.clear();
   
      if(result=="ERROR MYSQL"){
      msgbox("error","",result,4);  
      }else{
      msgbox("success","Mensaje","Documento guardado correctamente",2000);
      $('#frmdoc').trigger("reset");
      loaddoc(id_cliente);
      }
      },
            beforeSubmit: function() {
      msgbox("loader","","procesando...",0);
            },
            error: function(response) {
            toastr.clear();
            msgbox("error","","Error al procesar datos, vuelva a intentar",4000);
            }
 }).submit();
}
})

$('#notadjunto').bind('change', function() {
var fileSize = this.files[0].size;

if(fileSize>5242880){
this.value=""; 
alert("No se permite Subir archivos mas de 5M");
}
})

var editor1=CKEDITOR.replace('newnota',{height: '150px'});

//++++++++ NUEVA NOTA
$('#frmnota').ajaxForm({
            url:base_url+ 'clientes/new_nota?action=newnota&id='+id_cliente,
      type: "POST",
      delegation: true,
            success: function(result) {
        console.log(id_cliente)      
        toastr.clear();
      if(result=="ERROR MYSQL"){
      msgbox("error","Mensaje","Error al guardar datos",4000);  
      }else{

      msgbox("success","Mensaje","Datos guardados correctamente",2000);
      $('#frmnota').trigger("reset");
      loadnotas(id_cliente);
      editor1.destroy();
      editor1 = null;
      $('#newnota').val('');
      editor1=CKEDITOR.replace('newnota',{height: '150px'});
      }
      },
            beforeSubmit: function() {
      msgbox("loader","","procesando...",0);
      $('#modalnota').modal('hide');
            },
            error: function(response) {
     toastr.clear();
            msgbox("danger","", "Error al procesar datos, vuelva a intentar",4000);
            }
 });

})
</script>
