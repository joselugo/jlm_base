<div class="modal fade in" id="modal-edit-pago">
<div class="modal-dialog modal-md" role="document">
<div class="modal-content">
<form id="form-editpago">
<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></button>
<h4 class="modal-title"><i class="fa fa-list"></i> Editar Transacción Nº <strong id="id-view"></strong></h4>
</div>
<div class="modal-body">
<div class="row">
<div class="col-lg-6 col-xs-6">
<div class="form-group input-group-sm">
<label>Forma de Pago</label>
<select class="form-control" id="forma_pago" name="forma_pago" >
<?=$option_forma_pago?>
</select>
</div>
</div>

<div class="col-lg-6 col-xs-6"> 
<div class="form-group input-group-sm">
<label>Nº transacción</label>
<input class="form-control" id="transaccion" name="transaccion" type="text" autocomplete="off" value="">
<input id="id_pago" name="id_pago" type="hidden">

</div>
</div>

<div class="col-lg-6 col-xs-6"> 
<div class="form-group input-group-sm">
<label>Monto Cobrado</label>
<input class="form-control" id="cobrado" name="cobrado" type="text"  autocomplete="off" required="" readonly="">
</div>
</div>

<div class="col-lg-6 col-xs-6"> 
<div class="form-group input-group-sm">
<label>Comisión</label>
<input class="form-control" id="comision" name="comision" type="number" step="0.01" autocomplete="off">
</div>
</div>


<div class="col-lg-6 col-xs-6">    
<div class="form-group input-group-sm">
<label>Fecha de registro</label>
<input type="text" class="form-control" id="fecha_pago" name="fecha_pago" required="">
</div>
</div>

<div class="col-lg-6 col-xs-6">    



<div class="form-group">
<label>Hora de registro</label>
    <div class="input-group date form_time col-md-12" data-date="" data-date-format="hh:ii" data-link-field="dtp_input3" data-link-format="hh:ii">
      <input class="form-control" size="16" type="text" name="hora_pago" id="hora_pago" readonly>
      <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
       <span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
    </div>
    <input type="hidden" id="dtp_input3" value="" /><br/>
</div>
</div>


<div class="col-lg-12">  
<div class="form-group input-group-sm">
<label>Descripción</label>
<textarea class="form-control input-sm" rows="5" id="descripcion" name="descripcion" style="height:60px;"></textarea>
</div>
</div>

</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Cancelar</button>
<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Registrar</button>
</div>
</form>
</div>
</div>

<style type="text/css">
  #hora_pago{border-radius: 0px; height: 30px}
</style>

<link rel="stylesheet" href="<?=base_url()?>css/plugins/datetimepicker/bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?=base_url()?>js/plugins/datetimepicker/bootstrap-datetimepicker.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?=base_url()?>js/plugins/datetimepicker/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
<script>

var base_url="<?=base_url();?>"	
$(function(){

$('#fecha_pago').datepicker({
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

  $('.form_time').datetimepicker({
        language:  'es',
        weekStart: 1,
        todayBtn:  1,
    autoclose: 1,
    todayHighlight: 1,
    startView: 1,
    minView: 0,
    maxView: 1,
    forceParse: 0
    });

});

//+++++ SAVE DATOS
$('#form-editpago').ajaxForm({
      url: base_url + 'transacciones/update_pago',
      type: "POST",
      delegation: true,
            success: function(result) {
              console.log(result)
      toastr.clear();
      if(result=="ERROR MYSQL"){
      msgbox("error","Mensaje", result,4000);  
      }else{
      msgbox("success","Mensaje", "Datos guardados correctamente",2000);
      update_pago();
      }
      },
            beforeSubmit: function() {
    $('#modal-edit-pago').modal('hide');
      msgbox("loader","Mensaje", "procesando...",0);
            },
            error: function(response) {
    
            msgbox("error","Mensaje" ,"Error al procesar datos, vuelva a intentar",4000);
            }
 });

</script>

</div></div>