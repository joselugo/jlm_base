<div id="tmp"><div class="modal fade in" id="modal-new-factura-libre">
<div class="modal-dialog modal-md" role="document">
<form id="frm-new-factura-libre">
<input type="hidden" name="idf" id="idf" value="00013463">
<input type="hidden" name="impuesto" id="impuesto" value="NO">
<input type="hidden" name="tipofc" id="tipofc" value="2">
<input type="hidden" name="iddelcliente" id="iddelcliente" value="000642">
<div class="modal-content">
<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></button>
<h4 class="modal-title"><i class="fa fa-list"></i> Nueva factura libre <span id="txt-factura"></span></h4>
</div>
<div class="modal-body">
<div class="col-sm-6">
<div class="form-group">
<label class="col-sm-3 control-label">Emitido</label>
<div class="col-sm-9">
<div class="input-group">
<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
<input type="text" class="form-control" id="emitido-libre" name="emitido-libre" value="<?=date('Y-m-d')?>">
</div>
</div>
</div>
</div>
 
<div class="col-sm-6" style="margin-bottom:20px;">
<div class="form-group">
<label class="col-sm-4 control-label">Vencimiento</label>
<div class="col-sm-8">
<div class="input-group">
<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
<?php  $vencimiento=date('Y-m-d', strtotime(''.date('Y-m-d').' + 10 days')); ?> 
<input type="text" class="form-control" id="vencimiento-libre" name="vencimiento-libre" value="<?=$vencimiento?>">
<input type="hidden" class="form-control" id="id-cliente" name="id-cliente" value="<?=$id?>">
</div>
</div>
</div>
</div>

                   
         
 <table class="tblfactura-libre" id="tablafactura-libre">
 <thead>
 <tr><th>Descripción</th>
 <th align="center" style="width:144px">Importe</th>
 </tr></thead>
<tbody id="items">

<tr>
         <td style="display: none;"><input type="text" value="-1"></td>
         <td style="display: none;"><input type="text" value="-1"></td>  
         <td>
         <textarea class="form-control input-sm" rows="1"></textarea>
         </td>

         <td align="left" valign="top">
         <input class="form-control input-sm" type="number" step="0.01" style="text-align:center;width: 100px;float: left;">
         <button type="button" class="btn btn-xs btn-default" style="margin: 3px 4px 0 4px;" onclick="$(this).parent().parent().remove();"><span class="glyphicon glyphicon-trash"></span></button>
         </td>
          
</tr>

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
<button type="button" class="btn btn-primary" id="btn-guardar-libre"><i class="fa fa-floppy-o"></i> Guardar cambios</button>
</div>
</div>
</form>
</div>

<script>

var base_url="<?=base_url();?>"	



$(function(){

$('#emitido-libre,#vencimiento-libre').datepicker({
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

});


 $("#btn-guardar-libre").click(function () {
 	       lista_items=[];
 	       var campo0, campo1, campo2, campo3; bandera=0;

 	       var itemsArray,filaItem;
           $("#tablafactura-libre tbody tr").each(function (index) {
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
                 if(campo3=="" && campo2==""){bandera=1}
                
                 filaItem = new Item(campo0,campo1,campo2,campo3);
                 lista_items.push(filaItem);
           })
         itemsArray = JSON.stringify(lista_items);
         if(bandera==0){

            $.ajax({
         method: "POST",
         url: base_url+"facturas/new_factura_libre",
         data: { items: itemsArray,emitido:$("#emitido-libre").val(),vencimiento:$("#vencimiento-libre").val(),id_cliente:$("#id-cliente").val() }
         })
         .done(function( data ) {
         msgbox("success","Mensaje","Datos guardados correctamente",2000);
         $('#modal-new-factura-libre').modal('hide');
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