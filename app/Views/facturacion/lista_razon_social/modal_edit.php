<div id="tmp"><div class="modal fade in" id="modal-edit">
<div class="modal-dialog modal-sm" role="document">
<form id="frm-edit-factura">
<input type="hidden" name="id" id="id">
<div class="modal-content">
<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></button>
<h4 class="modal-title"><i class="fa fa-list"></i> Editar - <span id="nombre"></span></h4>
</div>
<div class="modal-body">
<div class="form-group">
<label> Prefactura </label>
<select class="form-control" id="prefactura" name="prefactura">
<option value="0">No requiere</option>
<option value="1">Si requiere</option>
</select>
</div>

<div class="form-group">
<label> Fecha de envio </label>
<input class="form-control" type="text" name="fecha_envio" id="fecha_envio">
</div>

<div class="form-group">
<label> Fecha de pago </label>
<input class="form-control" type="text" name="fecha_pago" id="fecha_pago">
</div>

<div class="form-group">
<label> Fecha compromiso </label>
<input class="form-control" type="text" name="fecha_compromiso" id="fecha_compromiso">
</div>

<div class="form-group">
<label> Folio facturado </label>
<input class="form-control" type="text" name="folio" id="folio">
</div>

<div class="form-group">
<label> Total</label>
<input class="form-control" type="text" name="monto" id="monto">
</div>

<div class="form-group">
<label> Estatus </label>
<select class="form-control" id="estatus" name="estatus">
<option value="No facturado">No facturado</option>
<option value="Facturado">Facturado</option>
<option value="Pagado">Pagado</option>
<option value="Complemento pago pendiente">Complemento pago pendiente</option>
<option value="Complemento pago abonado">Complemento pago abonado</option>
</select>
</div>

<div class="form-group">
<label> Comentarios </label>
<textarea class="form-control" rows="4" id="comentarios" name="comentarios"></textarea>
</div>

<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
<button type="submit" class="btn btn-primary" id="btn-guardar"><i class="fa fa-floppy-o"></i> Guardar cambios</button>
</div>
</div>
</form>
</div>

<script>

base_url="<?=base_url();?>"

$(function(){

$('#fecha_envio,#fecha_pago,#fecha_compromiso').datepicker({
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

//+++++ SAVE FACTURA
$('#frm-edit-factura').ajaxForm({
      url: base_url+'index.php/facturacion/update_list_by_id',
			type: "POST",
			delegation: true,
      success: function(result) {
       
      msgbox("success","Datos guardados",2);
		  update_factura();
      
			},
      beforeSubmit: function() {
			$('#modal-edit').modal('hide');
			
      },
      error: function(response) {
      msgbox("danger","Error al procesar datos, vuelva a intentar",2);
      }
 });
 

});

</script>

</div></div>