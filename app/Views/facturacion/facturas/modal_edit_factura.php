<div id="tmp"><div class="modal fade in" id="modal-edit-factura">
<div class="modal-dialog modal-md" role="document">
<form id="frm-edit-factura">
<input type="hidden" name="idf" id="idf" value="00013463">
<input type="hidden" name="impuesto" id="impuesto" value="NO">
<input type="hidden" name="tipofc" id="tipofc" value="2">
<input type="hidden" name="iddelcliente" id="iddelcliente" value="000642">
<div class="modal-content">
<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></button>
<h4 class="modal-title"><i class="fa fa-list"></i> Editar factura <span id="txt-factura"></span></h4>
</div>
<div class="modal-body">
<h4 style="width:100%; text-align:center; margin-top:-5px;"><span id="txt-nombre"></span><small><span class="label label-danger"> No pagado</span></small></h4>
<div class="col-sm-6">
<div class="form-group">
<label class="col-sm-3 control-label">Emitido</label>
<div class="col-sm-9">
<div class="input-group">
<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
<input type="text" class="form-control" id="emitido" name="emitido">
</div>
</div>
</div>
</div>
 
<div class="col-sm-6">
<div class="form-group">
<label class="col-sm-4 control-label">Vencimiento</label>
<div class="col-sm-8">
<div class="input-group">
<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
<input type="text" class="form-control" id="vencimiento" name="vencimiento">
</div>
</div>
</div>
</div>
             
<button type="button" class="btn btn-primary btn-xs" id="blank-add"><i class="fa fa-plus"></i> Agregar Línea en blanco </button><br>         
              
 <table class="tblfactura" id="tablafactura">
 <thead>
 <tr><th>Descripción</th>
 <th align="center" style="width:144px">Importe</th>
 </tr></thead>
<tbody id="items">
</tbody>
</table>

<table style="margin-top: 10px; width:100%">    
    <tbody><tr style="border: 1px solid #c3c3c3;">
      <td width="35%" align="center" valign="middle" bgcolor="#FFFFFF" style="border-top:1px solid #c3c3c3"><strong>Subtotal: <span style="font-weight:bold; font-size:15px" id="txt-subtotal"></span></strong></td>
      <td width="35%" align="center" valign="middle" bgcolor="#FFFFFF" style="border-top:1px solid #c3c3c3"><strong>Impuesto: <span style="font-weight:bold; font-size:15px" id="txt-iva"></span></strong></td>
<td width="30%" align="center" valign="middle" bgcolor="#FFFFFF" style="border-top:1px solid #c3c3c3"><strong>Saldo: <span style="font-weight:bold; font-size:15px" id="txt-saldo"></span></strong></td>
    </tr>
    <tr style="background: #e0e0e0;;color:#3a3a3a;">
      <td colspan="3" align="center" valign="middle" style="border: 1px solid #c3c3c3;"><strong>Total: </strong><span style="font-weight:bold; font-size:15px" id="txt-total"></span></td>
      </tr>
</tbody></table>
 
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
<button type="button" class="btn btn-primary" id="btn-guardar"><i class="fa fa-floppy-o"></i> Guardar cambios</button>
</div>
</div>
</form>
</div>

<script>

var base_url="<?=base_url();?>"	

function deletetr(id_factura,id_item,tipo) {

 

swal({ title: "¿Esta seguro que desea eliminar el item seleccionado?", 
text: "Eliminar item de Factura!",
type: "warning",
showCancelButton: true,
confirmButtonColor: "#DD6B55",
confirmButtonText: "Si, Eliminar!",
closeOnConfirm: false },
function(){

  $.post( base_url+"facturas/delete_item", {id_item:id_item,id_factura:id_factura})
  .done(function( data ) {
  if(data=="1"){

 
  swal("Eliminado!", "Item eliminado correctamente.", "success");
  update_factura();
  $('#modal-edit-factura').modal('hide');
  }
}) 

})

}

$(function(){

$('#emitido,#vencimiento').datepicker({
dateFormat: "yy-mm-dd",
toggleActive: true,
todayHighlight: true,
autoclose: true,
beforeShow: function (input) {
                    $(input).css({
                        "position": "relative",
                        "z-index": 999999
                    });
    }
});	


 
$( "#blank-add" ).on( "click", function() {
$('#tablafactura tbody').append('<tr><td><textarea class="form-control input-sm" name="adddescription[]" rows="1"></textarea></td><td align="left" valign="top"><input class="form-control input-sm" type="number" step="0.01" name="addcosto[]" value="" style="text-align:center;width: 100px;float: left;"><button type="button" class="btn btn-xs btn-default" style="margin: 3px 4px 0 4px;" onclick="$(this).parent().parent().remove();"><span class="glyphicon glyphicon-trash" ></span></button></td></tr>');
});

});


 $("#btn-guardar").click(function () {
 	       lista_items=[];
 	       var campo0, campo1, campo2, campo3; bandera=0;

 	       var itemsArray,filaItem;
           $("#tablafactura tbody tr").each(function (index) {
               $(this).find("input,textarea").each(function (index2) {
                   switch (index2) {
                       case 0:
                           campo0 = $(this).val();
                           break;
                       case 1:
                           campo1 = $(this).val();
                           break;
                       case 2:
                           campo2 = $(this).val();
                           break;
                       case 3:
                           campo3 = $(this).val();
                           break;    
                   }
               $(this).css("background-color", "#ECF8E0");

               })

                 if(campo2=="" && campo3!=""){bandera=1}
                 if(campo3=="" && campo2!=""){bandera=1}
                
                 filaItem = new Item(campo0,campo1,campo2,campo3);
                 lista_items.push(filaItem);
           })
         itemsArray = JSON.stringify(lista_items);
         if(bandera==0){

            $.ajax({
         method: "POST",
         url: base_url+"facturas/update_items",
         data: { items: itemsArray,emitido:$("#emitido").val(),vencimiento:$("#vencimiento").val() }
         })
         .done(function( data ) {
         msgbox("success","Mensaje","Datos guardados correctamente",2000);
         $('#modal-edit-factura').modal('hide');
         update_factura(); 
         //console.log(data)
         }); 

         }else{
           msgbox("error","Mensaje","Acomplete los campos",2000);
         }
         
  })

 function Item(id_factura,id_item,descripcion,costo)	{
                    this.id_factura = id_factura;
                    this.id_item = id_item;
                    this.descripcion = descripcion;
                    this.costo=costo;
 } 

</script>

</div></div>