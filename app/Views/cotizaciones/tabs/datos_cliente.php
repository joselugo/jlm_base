<div class="row">
<form id="form-detalles" class="form-horizontal" action="<?=base_url();?>clientes/save_data_clientes" onsubmit="update(this,event);">
<div class="col-sm-6">
<div class="box box-flat box-solid" style="border: 1px solid #d5d3d3">
<div class="box-header with-border" style="background-color: #f0f0f0">
<h3 class="box-title">Información Principal</h3>
</div>
<div class="box-body">

<div class="form-group" style="margin-bottom:5px;">
<label class="col-sm-4 control-label">Estado</label>
<div class="col-sm-8">
<div class="form-control" style="border:none; font-size:14px; margin-left: -10px;">
<?php if($estatus=='ACTIVO'){echo '<span class="label label-success">ACTIVO</span>';} else if($estatus=='SUSPENDIDO'){echo '<span class="label label-danger">SUSPENDIDO</span>';} else {echo '<span class="label label-warning">RETIRADO</span>';} ?>  
</div>
</div>
</div>

<div class="form-group">
<label class="col-sm-4 control-label">Nodo</label>
<div class="col-sm-8">
<div class="form-control">
<span class=""><?php echo $nodo?></span>  
</div>
</div>
</div>

<div class="form-group">
<label class="col-sm-4 control-label">ID/Login Portal</label>
<div class="col-sm-8">
<input type="text" class="form-control" id="id" name="id" value='<?php echo $id?>' readonly="" style="font-size: 18px;font-weight: bold;color: #1870D2;">
</div>
</div>

<div class="form-group">
<label class="col-sm-4 control-label">Contraseña portal <a href="javascript:void(0);" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Generar contraseña aleaotoria" onclick="generatepass('#contrasenia_portal');"><i class="fa fa-unlock"></i></a></label>
<div class="col-sm-8">              
<input type="text" class="form-control" id="contrasenia_portal" name="contrasenia_portal" value='<?php echo $contrasenia_portal ?>' required>
</div>
</div>
 
<div class="form-group">
<label class="col-sm-4 control-label">Nombre</label>
<div class="col-sm-8">
<input type="text" class="form-control" id="nombres" name="nombres" value='<?php echo $nombres ?>' required>
</div>
</div>

<div class="form-group" id="div_direccion">
<label class="col-sm-4 control-label">Domicilio</label>
<div class="col-sm-8">
<input type="text" class="form-control" id="domicilio_completo" name="domicilio_completo" value='<?php echo $domicilio_completo ?>'>
</div>
</div>

<div class="form-group">
<label class="col-sm-4 control-label">Nº Móvil</label>
<div class="col-sm-8">
<input type="text" class="form-control" id="movil" name="movil" value='<?php echo $movil ?>' required>
</div>
</div>

<div class="form-group">
<label class="col-sm-4 control-label">Nº Télefono</label>
<div class="col-sm-8">
<input type="text" class="form-control" id="telefono" name="telefono" value='<?php echo $telefono ?>'>
</div>
</div>

<div class="form-group">
<label class="col-sm-4 control-label">Correo</label>
<div class="col-sm-8">
<input type="text" class="form-control" id="email" name="email" value='<?php echo $email ?>'>
</div>
</div>

<div class="form-group">
<label class="col-sm-4 control-label">Pasarela</label>
<div class="col-sm-8">
<input type="text" class="form-control" id="pasarela" name="pasarela" value='<?php echo $pasarela ?>'>
</div>
</div>

</div>
</div>
</div>

<div class="col-sm-6" style="padding:0">

<div class="col-sm-12">
<div class="box box-flat box-solid" style="border: 1px solid #d5d3d3">
<div class="box-header with-border" style="background-color: #f0f0f0; margin-bottom:10px">
<h3 class="box-title">Otros datos</h3>
</div>
<div class="box-body">

<div class="form-group">
<label class="col-sm-4 control-label">Netflix</label>
<div class="col-sm-8">
<input type="text" class="form-control"  value='<?php echo $netflix?>'>
</div>
</div>

<div class="form-group">
<label class="col-sm-4 control-label">Camaras</label>
<div class="col-sm-8">
<input type="text" class="form-control"  value='<?php echo $camaras?>'>
</div>
</div>

<div class="form-group">
<label class="col-sm-4 control-label">TV</label>
<div class="col-sm-8">
<input type="text" class="form-control"  value='<?php echo $tv?>'>
</div>
</div>

<div class="form-group">
<label class="col-sm-4 control-label">Cableado</label>
<div class="col-sm-8">
<input type="text" class="form-control"  value='<?php echo $cableado?>'>
</div>
</div>

<div class="form-group">
<label class="col-sm-4 control-label">Wisp</label>
<div class="col-sm-8">
<input type="text" class="form-control"  value='<?php echo $wisp?>'>
</div>
</div>


</div>
</div>
</div>


</div>

</form>
</div>
<script src="<?=base_url();?>js/levas/clientes.js"></script>
<script type="text/javascript">
function update(f,e){
 e.preventDefault();
 var variables = $(f).serialize()+"&tipofiscal="+$("#tipofiscal").val()+"&razonsocial="+$("#razonsocial").val()+"&rfc="+$("#rfc").val()+"&domiciliofiscal="+$("#domiciliofiscal").val()+"&cp="+$("#cp").val()+"&notasfac="+$("#notasfac").val();
 process_save('<?=base_url();?>', f.action, "div#response", true, "POST", variables);   
}

$("#close-box").click(function(){
  var cargando = '<p align="center" style="padding-top:50px;"><img src="<?=base_url();?>img/loaders/loader6.gif" width="31" height="31"></p><p align="center" style="font-size: 15px;"><b>Cargando datos...</b></p>';
  $("#bodysistem").html(cargando); 
  
   window.location.href="<?=base_url();?>clientes";
 
}); 
</script>