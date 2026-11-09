<div id="tmp"><div class="modal fade in" id="modal-edit-fiscales">
<div class="modal-dialog modal-sm" role="document">
<form id="frm-edit-fiscales">
<input type="hidden" name="id_lista_facturas" id="id_lista_facturas">
<div class="modal-content">
<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></button>
<h4 class="modal-title"><i class="fa fa-list"></i> Editar - <span id="nombre1"></span></h4>
</div>
<div class="modal-body">

<div class="form-group">
<label> Razon social</label>
<input class="form-control" type="text" name="razon_social" id="razon_social">
</div>

<div class="form-group">
<label> RFC </label>
<input class="form-control" type="text" name="rfc" id="rfc">
</div>

<div class="form-group">
<label> Domicilio fiscal </label>
<input class="form-control" type="text" name="domicilio_fiscal" id="domicilio_fiscal">
</div>

<div class="form-group">
<label> CP</label>
<input class="form-control" type="text" name="cp" id="cp">
</div>


<div class="form-group">
<label> Telefono </label>
<input type="text" class="form-control" id="telefono" name="telefono"></textarea>
</div>

<div class="form-group">
<label> Correo </label>
<input type="text" class="form-control" id="correo" name="correo"></textarea>
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


//+++++ SAVE FACTURA
$('#frm-edit-fiscales').ajaxForm({
      url: base_url+'index.php/facturacion/update_fiscales',
			type: "POST",
			delegation: true,
      success: function(result) {
       
      msgbox("success","Datos guardados",2);
		  update_factura();
      
			},
      beforeSubmit: function() {
			$('#modal-edit-fiscales').modal('hide');
			
      },
      error: function(response) {
      msgbox("danger","Error al procesar datos, vuelva a intentar",2);
      }
 });
 

});

</script>

</div></div>