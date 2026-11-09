


<div class="panel panel-blue panel-Usuarios">
<div class="panel-heading">
<h4 class="panel-title">AUTORIZAR ONU - <?=$sn?></h4>
<div class="panel-heading-btn">
<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fas fa-expand"></i></a>
<!--<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onclick="refresh_()"><i class="fas fa-sync-alt"></i></a>-->
</div>
</div>
<div class="panel-body">

<form id="saveresumen" method="POST" class="savedata"  action="fiber/update_onu_sistema">


<div class="form-group row">
<label class="col-md-2 col-form-label text-right"> Cliente: </label>
<div class="col-md-7">
<input type="text" class="form-control" name="cliente" id="cliente" required="" value="<?=$cliente?>">
<input type="hidden" class="form-control" name="id_cliente" id="id_cliente" required="" value="<?=$idcliente?>">
<input type="hidden" class="form-control" name="idolt"  value="<?=$idolt?>">
<input type="hidden" class="form-control" name="idpon"  value="<?=$idpon?>">
<input type="hidden" class="form-control" name="id"  value="<?=$id?>">
</div>
</div>

<div class="form-group row">
<label class="col-md-2 col-form-label text-right">Nº SERIE</label>
<div class="col-md-7">
<input type="text" class="form-control" name="sn" value="<?=$sn?>" disabled="">
</div>
</div>

<div class="form-group row">
<label class="col-md-2 col-form-label text-right">CHASIS</label>
<div class="col-md-1">
<input type="text" class="form-control" name="chasis" value="<?=$chasis?>" disabled="">
</div>

<label class="col-md-2 col-form-label text-right">TARJETA</label>
<div class="col-md-1">
<input type="text" class="form-control" name="tarjeta" value="<?=$tarjeta?>" disabled="">
</div>

<label class="col-md-2 col-form-label text-right">PUERTO</label>
<div class="col-md-1">
<input type="text" class="form-control" name="puerto" value="<?=$puerto?>" disabled="">
</div>

</div>

<div class="form-group row">
<label class="col-md-2 col-form-label text-right">MODELO ONU</label>
<div class="col-md-7">
<select class="form-control sl2" name="modelo" disabled="">
  <option></option>
  <?=$onts?>
</select>
</div>
</div>

<div class="form-group row div-image">
<label class="col-md-3 col-form-label text-right"></label>
<div class="col-md-7">
 <img id="id_image_hardware_version" alt="" width="300px" src="<?=base_url()?>public/images/ont_image/<?=$imagen?>">
</div>
</div>

<div class="form-group row">
<label class="col-md-2 col-form-label text-right">MODO</label>
<div class="col-md-7">
<select class="form-control sl2" name="modo">
  <option>Routing</option>
  <option>Bridging</option>
</select>
</div>
</div>

<div class="form-group row">
<label class="col-md-2 col-form-label text-right">VLAN</label>
<div class="col-md-7">
<select class="form-control sl2" name="vlan" disabled="">
  <option></option>
  <?=$vlans?>
</select>
</div>
</div>

<div class="form-group row">
<label class="col-md-2 col-form-label text-right">ZONA</label>
<div class="col-md-7">
<select class="form-control sl2" name="zona">
  <option></option>
</select>
</div>
</div>

<div class="form-group row">
<label class="col-md-2 col-form-label text-right">NAP</label>
<div class="col-md-7">
<select class="form-control sl2" name="nap">
  <option></option>
</select>

</div>
</div>

<div class="form-group row">
<label class="col-md-2 col-form-label text-right">TRÁFICO MAX SIMÉTRICO</label>
<div class="col-md-7">
<select class="form-control sl2" name="trafic" disabled="">
  <?=$trafic?>
</select>
</div>
</div>

<div class="form-group row">
<label class="col-md-2 col-form-label text-right">COMENTARIOS</label>
<div class="col-md-7">
<textarea class="form-control" name="comentarios" rows="4" ><?=$comentarios?></textarea>
</div>
</div>

<div class="form-group row">
<br>
<label class="col-md-2 col-form-label text-right"></label>
<div class="col-md-7 col-form-label text-right">
<button type="submit" class="btn-azul p-10 btsavedata" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Autorizando ONU..."><i class="far fa-save fa-lg"></i> Guardar</button>

<a href="#fiber/onu" class="btn-azul p-10">Cancelar</a>
</div>
</div>



</form>

</div>
</div>



<script>
var base_url="<?=base_url();?>"	

$(function(){

$('#saveresumen').ajaxForm({
success: function(data) {
    $('#gritter-notice-wrapper').remove();
                 if(data == "success") {
                  alerta('exito', "ONU GUARDADA CORRECTAMENTE");
                  $('.btsavedata').button('reset');
                  loadurl("#fiber/onu");
                }
                else {
                  alerta('error', "HA OCURRIDO UN ERROR");
                  $('.btsavedata').button('reset');
                }
},
beforeSubmit: function() {
$('.btsavedata').button('loading');

if($('input[name="id_cliente"]').val()>0){
  
  if($('select[name="modelo"]').val()>0){
    
    if($('select[name="vlan"]').val()>0){
      return true;
    }
    else{alerta("warning","Seleccione una VLAN");  $('.btsavedata').button('reset'); return false}

  }
  else{alerta("warning","Seleccione el modelo de la ONU");   $('.btsavedata').button('reset'); return false}
}
else{
  alerta("warning","Seleccione correctamente al cliente");   $('.btsavedata').button('reset'); return false
}


},
error: function(response) {
$('#gritter-notice-wrapper').remove();
alerta('error500',response);
$('.btsavedata').button('reset');
}})  


$('select[name="modelo"]').select2({placeholder: "Seleccione un modelo",allowClear: true});
//$('select[name="vlan"]').select2({placeholder: "Seleccione una Vlan",allowClear: true});
$('select[name="zona"]').select2({placeholder: "Seleccione una zona",allowClear: true});
$('select[name="nap"]').select2({placeholder: "Seleccione una Nap",allowClear: true});
$('select[name="trafic"]').select2();
$('select[name="modo"]').select2();


  $("[name=modelo]").val("<?=$srv_profile?>").trigger('change.select2');
  $("[name=vlan]").val("<?=$vlan?>").trigger('change.select2');
  $("[name=trafic]").val("<?=$idtrafic?>").trigger('change.select2');

$(document).on('keyup','#cliente',function(e){
$("#cliente" ).autocomplete({
source: base_url + 'clientes/searchcliente?action=listauser',
minLength: 3,
open : function(event, ui){
$('img[data-name]').initial({
height:64,
width:64,
charCount:2,
fontSize:26,
fontWeight:600
}); 
},
select : function(event, ui){
 $( "#id_cliente" ).val(ui.item.id);
}
}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
        return $( "<li></li>" )

            .data( "item.autocomplete", item )
            .append(item.socio)
            .appendTo( ul ); 
    };
});    

}) //////////end funtion ready




</script>
