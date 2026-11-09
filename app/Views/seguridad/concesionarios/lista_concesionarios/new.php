

<ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="#index/home">Inicio</a></li>
        <li class="breadcrumb-item"><a href="#concesionarios/index">Lista Concesionarios</a></li>
        <li class="breadcrumb-item active">Nuevo Concesionario</li>
</ol> 
<h2 class="page-header">Nuevo Concesionario</h2>

<div class="panel panel-blue panel-sindicatos">
<div class="panel-heading">
<h4 class="panel-title">Nuevo Concesionario</h4>
</div>
<div class="panel-body">

<form id="form-concesionario-new" action="concesionarios/new_concesionarios" method="post">
<div class="panel-body row">
<div class="col-lg-6">
    
    <div class="form-group row">
        <label class="col-md-4 col-form-label text-right">Nombre</label>
        <div class="col-md-8">
            <input type="text" class="form-control" name="nombre" autofocus="" required="">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-4 col-form-label text-right">Apellidos</label>
        <div class="col-md-8">
            <input type="text" class="form-control" name="apellidos" autofocus="" required="">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-4 control-label text-right">Socio</label>
        <div class="col-md-8">
        <select style="width: 100%;" class="form-control Select2" name="idsocio" required="">
          <?=$tblsocios?>
        </select>
        </div>
    </div>
    
    <div class="form-group row">
        <label class="col-md-4 col-form-label text-right">Domicilio</label>
        <div class="col-md-8">
            <input type="text" class="form-control" name="domicilio" autofocus="" required="">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-4 col-form-label text-right">INE</label>
        <div class="col-md-8">
            <input type="text" class="form-control" placeholder="Clave de elector" minlengt="18" maxlength="18" name="ine" autofocus="" required="">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-4 col-form-label text-right">N&uacute;mero</label>
        <div class="col-md-8">
            <input type="tel" minlengt="10" maxlength="10" class="form-control" name="movil" autofocus="" required="">
        </div>
    </div>

    <div class="form-group row">
        <label class="col-md-4 col-form-label text-right">Correo</label>
        <div class="col-md-8">
            <input type="email" data-rule="email" class="form-control" name="correo" autofocus="" required="">
        </div>
    </div>
</div>

    <div class="col-lg-6">
        
        <div class="form-group row">
            <label class="col-md-4 control-label text-right">Avatar</label>
            <div class="col-md-8">
            <select style="width: 100%;" class="vodiapicker form-control" name="avatar" required="">
                <option value="admin.png"  data-thumbnail="<?=base_url()?>public/images/avatar/admin.png">admin.png</option>
                <option value="avatar-1.png"  data-thumbnail="<?=base_url()?>public/images/avatar/avatar-1.png">avatar-1.png</option>
                <option value="avatar-2.png"  data-thumbnail="<?=base_url()?>public/images/avatar/avatar-2.png">avatar-2.png</option>
                <option value="avatar-3.png"  data-thumbnail="<?=base_url()?>public/images/avatar/avatar-3.png">avatar-3.png</option>
                <option value="avatar-4.png"  data-thumbnail="<?=base_url()?>public/images/avatar/avatar-4.png">avatar-4.png</option>
                <option value="avatar-5.png"  data-thumbnail="<?=base_url()?>public/images/avatar/avatar-5.png">avatar-5.png</option>
                <option value="avatar-6.png"  data-thumbnail="<?=base_url()?>public/images/avatar/avatar-6.png">avatar-6.png</option>
                <option value="avatar-7.png"  data-thumbnail="<?=base_url()?>public/images/avatar/avatar-7.png">avatar-7.png</option>
                <option value="avatar.png"  data-thumbnail="<?=base_url()?>public/images/avatar/avatar.png">avatar.png</option>
                <option value="avatar04.png"  data-thumbnail="<?=base_url()?>public/images/avatar/avatar04.png">avatar04.png</option>
                <option value="avatar2.png"  data-thumbnail="<?=base_url()?>public/images/avatar/avatar2.png">avatar2.png</option>
                <option value="avatar3.png"  data-thumbnail="<?=base_url()?>public/images/avatar/avatar3.png">avatar3.png</option>
                <option value="avatar5.png"  data-thumbnail="<?=base_url()?>public/images/avatar/avatar5.png">avatar5.png</option>
                <option value="hombre-soporte.png"  data-thumbnail="<?=base_url()?>public/images/avatar/hombre-soporte.png">hombre-soporte.png</option>
                <option value="hombre1.png"  data-thumbnail="<?=base_url()?>public/images/avatar/hombre1.png">hombre1.png</option>
                <option value="hombre2.png"  data-thumbnail="<?=base_url()?>public/images/avatar/hombre2.png">hombre2.png</option>
                <option value="mujer-soporte.png"  data-thumbnail="<?=base_url()?>public/images/avatar/mujer-soporte.png">mujer-soporte.png</option>
                <option value="mujer.png"  data-thumbnail="<?=base_url()?>public/images/avatar/mujer.png">mujer.png</option>
            </select>
          
            </div>
        </div>
        
        <div class="form-group row">
            <label class="col-md-4 col-form-label text-right">Contraseña</label>
            <div class="col-md-8">
                <input type="password" class="form-control" name="newpassword" autofocus="" required="">
            </div>
        </div>
        
        <div class="form-group row">
            <label class="col-md-4 col-form-label text-right">Confirmar Contraseña</label>
            <div class="col-md-8">
                <input type="password" class="form-control" name="newpassword2" autofocus="" required="">
            </div>
        </div>
        <div class="text-right">
            <a href="#concesionarios/index" class="btn-azul">Cancelar</a>
            <?php $button=""; if($tipoacceso>1){$button='<button type="submit" class="btn btn-sm btn-azul">Guardar</button>';}?>
            <?=$button?>
        </div>
        
    </div>
    
</div>  
</form>

</div>
</div>

<script>
$('#form-concesionario-new').ajaxForm({
beforeSend: function(data) {
  alerta("loader","") ;
      if($('input[name="newpassword"]').val()==$('input[name="newpassword2"]').val()){}
      else{$('#gritter-notice-wrapper').remove(); alerta('error','Las contraseñas no coinciden');  return false}
  
},  
success: function(data) {  
if(data=='2'){
alerta('error',data); 
}else{
 alerta('exito','Datos Guardados Correctamente');
 $(location).attr('href',base_url+"#concesionarios/index");
}
},
error: function(response) {
$('#gritter-notice-wrapper').remove();
alerta('error500',response['responseText']);
}})

$(".Select2").select2();



</script>