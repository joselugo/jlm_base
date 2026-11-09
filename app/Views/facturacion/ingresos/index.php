<style>
@media (max-width: 576px){
.icon-button,.dt-buttons {display:none;}
.slstatesuser{margin-left:-10px;}
}
</style>

<ul class="nav nav-tabs nav-ajax">
<li class="nav-items">
  <a href="#default-tab-1" data-toggle="tab" class="nav-link active show"><span class="d-sm-block d-none"><i class="far fa-credit-card"></i> Ingresos</span><span class="d-sm-none"><i class="far fa-credit-card"></i> Ingresos</span></a>
</li>
<li class="nav-items">
  <a href="#default-tab-2" data-toggle="tab" data-url="facturacion/egresos" class="nav-link"><span class="d-sm-block d-none"><i class="fas fa-shopping-cart"></i> Egresos</span><span class="d-sm-none"><i class="fas fa-shopping-cart"></i> Egresos</span></a>
</li>
</ul>
<div class="tab-content">
<div class="tab-pane active show" id="default-tab-1">

<table id="list-ingresos" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" style="width: 100%;">
<thead>
<tr>
  <th class="all column">ID</th><th class="all column">Fecha</th><th class="all column">Monto</th>
  <th class="column">Operador</th><th class="column">Referencia</th>
  <th class="column">Descripción</th><th class="all sorting_disabled column" style="min-width: 10px !important; max-width: 10px !important; width: 10px;">Acción</th></tr>
</thead>

</table>



</div>
  
  
  
<div class="tab-pane fade" id="default-tab-2">  


</div>
  

</div>




<script>
var ingresos,egresos;

function update_ingresos(){
ingresos.ajax.reload( null, false );
$($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
}


function newingreso(e) {

   $('#frmingreso')[0].reset();
   $('#frmingreso #descripcion').text("");
   $("#modalingr").modal();
   $(".modal-title ").html("Registrar " +e);

}


function update_ingreso(e) {

  $("#modalingr_update").modal();
   $("#frmingresoupdate #id_ingreso_modal").val(e);
  var base_url="<?=base_url()?>"
  $.ajax({
  url: base_url+"facturacion/get_ingreso_by_id",
  type: 'post',
  data: {action: "edit-factura", id: e},
  dataType:'json',
  success: function(data) {
      
     //  $('#gastos_tipo2 option[value='+data.tipo_gasto+']').attr('selected','selected');
       $('#frmingresoupdate #gastos_tipo').val(data.tipo_gasto).change();
       $('#frmingresoupdate #sucursal').val(data.sucursal).change();
       $("#frmingresoupdate #fecha-in2").val(data.fecha)
       $("#frmingresoupdate #monto").val(data.monto)
       $("#frmingresoupdate #referencia").val(data.referencia)
       $("#frmingresoupdate #descripcion").val(data.descripcion)
       //$('#tologin1 option[value='+data.to_login+']').attr('selected','selected');
      // $('#frmingresoupdate #tologin').val(data.to_login).change();
       //$('#frmingresoupdate #estatus').val(data.estatus).change();
      // $('#estatus1 option[value='+data.estatus+']').attr('selected','selected');
     //  $("#frmingresoupdate #comprobante").val(data.nota)
      // $("#url_comprobante").attr("href",base_url+"index.php/app_egresos/download_file_dropbox/"+data.nota)
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


configtable('#list-ingresos','ingresos');

ingresos=$('#list-ingresos').DataTable( {
"language": {
 "url": "<?=base_url();?>public/js/Datatables-es.json"
        },
"ajax": "<?=base_url();?>facturacion/get_all_ingresos",
"stateSave": true,
"aoColumns": [
{ mData: 'id'},
{ mData: 'fecha',sType: 'date-uk'},
{ mData: 'monto'},
{ mData: 'username'},
{ mData: 'referencia'},
{ mData: 'descripcion'},
{ mData: 'tool'}
],
initComplete: function(oSettings, json){
  
$('.not-search-input').html('');  
 //------->Button Col
$('#list-ingresos_wrapper .dt-buttons').append('');
      $("#list-ingresos_wrapper div.tooltablas")
         .html('<div class="grupotabla btn-group m-r-5">\
      <a class="btn btn-default" data-toggle="modal" data-target="#modalingr"><i class="fas fa-plus"></i> Nuevo</a></div>');
}
  
 } ).order( [ 0, 'desc' ] )
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


<div class="modal" id="modalingr" role="dialog">
<div class="modal-dialog modal-dialog-centered modal-md" role="document">
<div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Nuevo ingreso</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
</div>
<form id="frmingreso" class="form-horizontal">
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
<select class="form-control" id="gastos_tipo" name="gastos_tipo">
<option value="0">Ingreso</option>
</select>
</div>
</div>
<?php date_default_timezone_set('America/Mexico_City');?>
<div class="col-sm-6">    
<div class="form-group input-group-sm">
<label>Fecha</label>
<input class="form-control" id="fecha-in" name="fecha-in" value="<?=date("Y-m-d")?>" required="" autocomplete="off">
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

</div>
</div>
<div class="modal-footer">
<a href="" class="btn-azul" data-dismiss="modal">Cerrar</a>
<?php $button=""; if($tipoacceso>1){$button='<button type="submit" class="btn-azul">Guardar</button>';}?>
<?=$button?>
</div>
</form>
</div>
</div>
</div>

<form id="frmingresoupdate">
<div class="modal fade" id="modalingr_update">
<div class="modal-dialog modal-dialog-centered modal-md" role="document">
<div class="modal-content">
<div class="modal-header"><h5 class="modal-title">Editar ingreso</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button></div>
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
<select class="form-control" id="gastos_tipo" name="gastos_tipo">
<option value="0">Ingreso</option>
</select>
</div>
</div>
<div class="col-sm-6">    
<div class="form-group input-group-sm">
<label>Fecha</label>
<input class="form-control" id="fecha-in2" name="fecha-in" required="" autocomplete="off">
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

</div>
</div>
<div class="modal-footer">
<input type="hidden" class="form-control" id="id_ingreso_modal" name="id_ingreso_modal">
<a href="" class="btn-azul" data-dismiss="modal">Cerrar</a>
<?php $button=""; if($tipoacceso>1){$button='<button type="submit" class="btn-azul">Guardar</button>';}?>
<?=$button?>
</div>
</form>
</div>
</div>
</div>
</div>


<script>
$(function(){

$('#frmingreso').ajaxForm({
      url: '<?=base_url()?>facturacion/new_ingreso',
      type: "POST",
            success: function(result) {
      if(result=="error"){
      alerta("error",result);  
      }else{
      alerta("exito","Operación Exitosa");
      $('#modalingr').modal('hide');
      }
    //  update_egresos();
      update_ingresos();
      },
            beforeSubmit: function() {
           
    
            },
            error: function(response) {
            alerta("error","Error al procesar datos, vuelva a intentar");
            }
 })

$('#frmingresoupdate').ajaxForm({
      url: '<?=base_url()?>facturacion/update_ingreso',
      type: "POST",
            success: function(result) {
      if(result=="error"){
      alerta("error",result);  
      }else{
      alerta("exito","Operación Exitosa");
      $('#modalingr_update').modal('hide');
      }
     // update_egresos();
      update_ingresos();
      },
            beforeSubmit: function() {
           
    
            },
            error: function(response) {
            alerta("error","Error al procesar datos, vuelva a intentar");
            }
 })
 
})
</script>

