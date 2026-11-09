


<table id="list-egresos" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" style="width: 100%;">
<thead>
<tr>
  <th class="all column">ID</th><th class="all column">Fecha</th><th class="all column">Monto</th>
  <th class="column">Abierto por</th><th class="all column">Entregado a:</th><th class="column">Referencia</th>
  <th class="column">Descripción</th> <th class="column">Tipo</th> <th class="all column">Estatus</th>
  <th class="all sorting_disabled column" data-orderable="false" style="min-width: 45px !important; max-width: 45px !important; width: 45px;">Acción</th>
</tr>
</thead>

</table>



<script>
var ingresos,egresos;

function update_egresos(){
egresos.ajax.reload( null, false );
}

function newegreso(e) {

   $('#frmegreso')[0].reset();
    $('#frmegreso #descripcion').text("");
   $("#modalegreso").modal();
   
}


function update_egreso(e) {

  	$("#modalegr_update").modal();
   $("#id_egreso_modal").val(e);
  var base_url="<?=base_url()?>"
  $.ajax({
  url: base_url+"index.php/facturacion/get_egreso_by_id",
  type: 'post',
  data: {action: "edit-factura", id: e},
  dataType:'json',
  success: function(data) {
     
     //  $('#gastos_tipo2 option[value='+data.tipo_gasto+']').attr('selected','selected');
       $('#gastos_tipo2').val(data.tipo_gasto).change();
       $('#sucursal1').val(data.sucursal).change();
       $("#fecha1").val(data.fecha)
       $("#monto1").val(data.monto)
       $("#referencia1").val(data.referencia)
       $("#descripcion1").val(data.descripcion)
       //$('#tologin1 option[value='+data.to_login+']').attr('selected','selected');
       $('#tologin1').val(data.to_login).change();
       $('#estatus1').val(data.estatus).change();
      // $('#estatus1 option[value='+data.estatus+']').attr('selected','selected');
       $("#comprobante1").val(data.nota)
       $("#url_comprobante").attr("href",base_url+"app_egresos/download_file_dropbox/"+data.nota)
   }

});


}


function delete_ingresos(e){
  var alsup = $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: '¿Esta seguro que desea eliminar este ingreso? <br><b class="text-warning">' + e + '</b><br><br><br>',
      title: '<i class="far fa-question-circle fa-lg icodialog"></i> ELIMINAR',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt"></i> Si, eliminar',
          action: function() {
            this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
            this.buttons.Eliminar.disable();
            $('.jconfirm-closeIcon').remove();
            $.post("<?=base_url();?>facturacion/delete_ingresos", {
            action: "delete",
            id: e
            }).done(function(e) {
            update_ingresos();alerta("exito", "Registro eliminado correctamente")
           })
            
          }
        }
      }
    });
}

$(function(){

configtable('#list-egresos','egresos');
egresos=$('#list-egresos').DataTable( {
"language": {
"url": "<?=base_url();?>public/js/Datatables-es.json"
        },
"ajax": "<?=base_url();?>facturacion/get_all_egresos",
"deferRender": true,
"stateSave": true,
"aoColumns": [
{ mData: 'id'},
{ mData: 'fecha',sType: 'date-uk'},
{ mData: 'monto'},
{ mData: 'username'},
{ mData: 'entregado'},
{ mData: 'referencia'},
{ mData: 'descripcion'},
{ mData: 'tipo'},
{ mData: 'estatus'},
{ mData: 'tool',sClass: 'dt-center' },
],
"initComplete": function() {

         $('#list-egresos_wrapper .dt-buttons').append('');
      $("#list-egresos_wrapper div.tooltablas")
         .html('<div class="grupotabla btn-group m-r-5">\
      <a class="btn btn-default" data-toggle="modal" data-target="#modalegreso"><i class="fas fa-plus"></i> Nuevo</a></div>');


 }
  
 } ) .order( [ 0, 'desc' ] )
    .draw();

 $.datepicker.regional['es'] = {
 closeText: 'Cerrar',
 prevText: '< Ant',
 nextText: 'Sig >',
 currentText: 'Hoy',
 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
 weekHeader: 'Sm',
 dateFormat: 'dd/mm/yy',
 firstDay: 1,
 isRTL: false,
 showMonthAfterYear: false,
 yearSuffix: ''
 };
 $.datepicker.setDefaults($.datepicker.regional['es']);
  
//--->Fechas
$('#fecha-in,#fecha,#fecha1,#fecha-in2').datepicker({
format: "yyyy-mm-dd",
toggleActive: true,
todayHighlight: true,
autoclose: true
});   
  
})


</script>



<form id="frmegreso">
<div class="modal fade" id="modalegreso">
<div class="modal-dialog modal-dialog-centered modal-md" role="document">
<div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Nuevo egreso</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
</div>
<div class="modal-body">
<div class="row">

<div class="col-sm-12">    
<div class="form-group input-group-sm">
<label>Sucursal</label>
<select  style="width:100%" class="form-control sl2" id="sucursal" name="sucursal">
<?=$sucursales?>
</select>
</div>
</div>

<div class="col-sm-6">    
<div class="form-group input-group-sm">
<label>Tipo</label>
<select  style="width:100%" class="form-control sl2" id="gastos_tipo" name="gastos_tipo">
<option value=-1>Selecciona una opcion</option> 
<?php 

foreach ($gastos_tipo as $item) {
  echo "<option value='".$item->id."'>".$item->tipo."</option>";
}
?>
</select>
</div>
</div>
<?php date_default_timezone_set('America/Mexico_City');?>
<div class="col-sm-6">    
<div class="form-group input-group-sm">
<label>Fecha</label>
<input class="form-control" id="fecha" name="fecha" value="<?=date("Y-m-d")?>" required autocomplete="off">
</div>
</div>

<div class="col-sm-6">    
<div class="form-group input-group-sm">
<label>Monto</label>
<input class="form-control" id="monto" name="monto" type="number" step="0.01" placeholder="ingrese el monto" autocomplete="off" required="">
</div>
</div>

<div class="col-sm-6">    
<div class="form-group input-group-sm">
<label>Concepto</label>
<input class="form-control" id="referencia" name="referencia" required="" type="text" placeholder="por ejemplo numero de boleta,recibo" autocomplete="off">
</div>
</div>

<div class="col-sm-12">    
<div class="form-group input-group-sm">
<label>Detalles</label>
<textarea class="form-control" id="descripcion" name="descripcion" placeholder="Ingrese mas detalles" style="height:100px" rows="3"></textarea>
</div>
</div>

<div class="col-sm-12" id="tologin_div">    
<div class="form-group input-group-sm">
<label>Entregado a:</label>
<select  style="width:100%" class="form-control sl2" id="tologin" name="tologin" required>
<option value=-1>Selecciona una opción</option> 
<?php 

foreach ($login as $item) {
  echo "<option value='".$item->id."'>".$item->nombre."</option>";
}
?>
</select>
</div>


  
</div>
</div>
</div>
<div class="modal-footer">
<a href="" class="btn-azul" data-dismiss="modal">Cerrar</a>
<?php $button=""; if($tipoacceso>1){$button='<button type="submit" class="btn-azul">Guardar</button>';}?>
<?=$button?>
</div>
</div>
</div>
</div>
</div>
</form>



<form id="frmegresoupdate">
<div class="modal fade" id="modalegr_update">
<div class="modal-dialog modal-dialog-centered modal-md" role="document">
<div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Editar egreso</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
</div>
<div class="modal-body">
<div class="row">

<div class="col-sm-12">    
<div class="form-group input-group-sm">
<label>Sucursal</label>
<select  style="width:100%" class="form-control sl2" id="sucursal1" name="sucursal">
<?=$sucursales?>
</select>
</div>
</div>

<div class="col-sm-6">    
<div class="form-group input-group-sm">
<label>Tipo</label>
<select  style="width:100%" class="form-control sl2" id="gastos_tipo2" name="gastos_tipo2">
<option value=-1>Selecciona una opción</option> 
<?php 

foreach ($gastos_tipo as $item) {
  echo "<option value='".$item->id."'>".$item->tipo."</option>";
}
?>
</select>
</div>
</div>
<?php date_default_timezone_set('America/Mexico_City');?>
<div class="col-sm-6">    
<div class="form-group input-group-sm">
<label>Fecha</label>
<input class="form-control" id="fecha1" name="fecha1" required="" autocomplete="off">
<input id="tipo" name="tipo" type="hidden" >
</div>
</div>

<div class="col-sm-6">    
<div class="form-group input-group-sm">
<label>Monto</label>
<input class="form-control" id="monto1" name="monto1" type="number" step="0.01" placeholder="ingrese el monto" autocomplete="off" required="">
</div>
</div>

<div class="col-sm-6">    
<div class="form-group input-group-sm">
<label>Concepto</label>
<input class="form-control" id="referencia1" name="referencia1" required="" type="text" placeholder="por ejemplo numero de boleta,recibo" autocomplete="off">
</div>
</div>

<div class="col-sm-12">    
<div class="form-group input-group-sm">
<label>Detalles</label>
<textarea class="form-control" id="descripcion1" name="descripcion1" placeholder="Ingrese mas detalles" style="height:100px" rows="3"></textarea>
</div>
</div>

<div class="col-sm-6">    
<div class="form-group input-group-sm">
<label>Entregado a:</label>
<select  style="width:100%" class="form-control sl2" id="tologin1" name="tologin1" required>
<option value="-1">Selecciona una opción</option> 
<?php 

foreach ($login as $item) {
  echo "<option value='".$item->id."'>".$item->nombre."</option>";
}
?>
</select>
</div>
</div>

<div class="col-sm-6">    
<div class="form-group input-group-sm">
<label>Estado</label>
<select  style="width:100%" class="form-control sl2" id="estatus1" name="estatus1" required>
<option value="0">SIN COMPROBANTE</option>
<option value="1">NO VALIDADO</option>  
<option value="2">VALIDADO</option> 
</select>
</div>
</div>

<div class="col-sm-12">    
<div class="form-group input-group-sm">
<label>Comprobante</label>
  <div class="input-group input-group-sm">
                <input type="text" class="form-control" id="comprobante1" name="comprobante1" readonly="">
                    <span class="input-group-btn">
                      <a href="" target="_blank" id="url_comprobante" class="btn btn-default btn-flat"><i class="fa fa-image"></i></a>
                    </span>
              </div>
</div>
</div>

</div>
<input type="hidden" class="form-control" id="id_egreso_modal" name="id_egreso_modal">
</div>
<div class="modal-footer">
<a href="" class="btn-azul" data-dismiss="modal">Cerrar</a>
<?php $button=""; if($tipoacceso>1){$button='<button type="submit" class="btn-azul">Guardar</button>';}?>
<?=$button?>
</div>
</div>
</div>
</div>
</div>
</form>

<script>
$(function(){

$(".sl2").select2();  


$('#frmegreso').ajaxForm({
      url: '<?=base_url()?>facturacion/new_egreso',
      type: "POST",
            success: function(result) {
     
      if(result=="error"){
      alerta("error",result);  
      }else{
     alerta("exito","Operación Exitosa");
      $('#modalegreso').modal('hide');
      }
      update_egresos();
   //  update_ingresos();
      },
            beforeSubmit: function() {
              if($("#frmegreso #gastos_tipo").val()>"-1"){

                 if($("#tologin").val()>"-1"){return true;}
                 else{alerta("error","Selecciona a quien entregas el egreso");   return false; }

              }
              else{alerta("error","Selecciona un tipo de gasto");   return false;}

              
    
            },
            error: function(response) {
            alerta("error","Error al procesar datos, vuelva a intentar");
            }
 })

$('#frmegresoupdate').ajaxForm({
      url: '<?=base_url()?>facturacion/update_egreso',
      type: "POST",
            success: function(result) {
    
      if(result=="error"){
      alerta("error",result,4);  
      }else{
     alerta("exito","Operación Exitosa");
      $('#modalegr_update').modal('hide');
      }
      update_egresos();
    //  update_ingresos();
      },
            beforeSubmit: function() {
              if($("#gastos_tipo2").val()>"-1"){

                 if($("#tologin1").val()>"-1"){return true;}
                 else{alerta("error","Selecciona a quien entregas el egreso");   return false; }

              }
              else{alerta("error","Selecciona un tipo de gasto");   return false;}

              
    
            },
            error: function(response) {
            alerta("error","Error al procesar datos, vuelva a intentar",4);
            }
 })
 
})
</script>

</section>