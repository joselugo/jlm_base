<form id="frmpagos" onsubmit="newpago(this,event)">
<div class="row">
<div class="col-sm-7">
<div class="form-group">
<label>Buscar Cliente</label>
<input type="text" class="form-control" name="p_cliente" id="p_cliente" placeholder="Selecciona un cliente">
</div>
</div>

<div class="col-sm-5">
<label>Tipo Búsqueda</label>
<select class="form-control" name="p_tipo" id="p_tipo" style="width:100%">
<option value="nombre">Buscar por nombre</option>
<option value="id">Buscar por Nº cliente</option>
<option value="rfc">Buscar por RFC</option>
</select>

</div>
</div>

<div id="tmppagos" style="margin-top: 10px; display: none;"><div class="ibox">
  <div class="ibox-title"><h5><i class="fa fa-male" aria-hidden="true"></i> <a style="font-weight: 600;color: white;" href="#"><span class="username">Juan Lazaro Quijano Molina</span></a></h5></div>
<div class="ibox-content">
<div class="row">
  <div class="callout callout-danger" style="margin: 0px 10px 15px 10px; display: none"><h5 style="margin: 0px;font-size: 15px;" id="alert_n_facturas"></h5></div>

<div class="col-sm-7 col-xs-12 no-padding" style="border-right:2px solid #E8E8E8;">

<div class="col-sm-12">    
<div class="form-group">
<label style="width:100%">Seleccionar Factura a pagar</label>
<select class="form-control input-sm" id="facturas" name="facturas" style="width: calc(100% - 40px);float: left;">
 
</select>
<button type="button" class="btn btn-default btn-sm btopenfc" style="margin-left: 5px;font-size: 15px;padding: 2px 8px;" data-toggle="tooltip" data-placement="top" data-original-title="Ver factura PDF" onclick="window.open('pdf/facturaview?id='+$('#facturas').val()+'&token='+CryptoJS.MD5($('#facturas').val()))"><b><i class="fa fa-file-pdf-o"></i></b></button>
</div>
</div>

<div class="col-sm-6 xnlegal col-xs-6">    
<div class="form-group input-group-sm">
<label>N° Factura Legal</label>
<input type="number" class="form-control input-sm" id="nlegal" name="nlegal" readonly="">
</div>
</div>

<div class="col-sm-6 col-xs-6">    
<div class="form-group input-group-sm">
<label>Forma de Pago</label>
<select class="form-control input-sm" id="forma" name="forma">
  <?=$option_forma_pago?>
</select>
</div>
</div>

<div class="col-sm-6 dvtransaccion col-xs-6">    
<div class="form-group input-group-sm">
<label>N° Transacción</label>
<input type="text" class="form-control input-sm" id="transaccion" name="transaccion">
</div>
</div>

<div class="col-sm-6">    
<div class="form-group input-group-sm">
<label>Fecha de emisión</label>
<div class="input-group">
<div class="input-group-addon"> <i class="fa fa-calendar"></i></div>
<input type="text" name="fechaemision" class="form-control input-sm" id="fechaemision" readonly="">
</div>
</div>
</div>


<div class="col-sm-6 totalpagar">    
<div class="form-group input-group-sm has-success">
<label>TOTAL A PAGAR</label>
<div class="input-group"> <span class="input-group-addon"><b>$</b></span> <input type="number" class="form-control" id="total" name="total" required="" step="0.01" style="font-size: 19px;font-weight: 700;color: #1b9a73;">
</div>
<input type="hidden" id="total2" name="total2" >
<input type="hidden" id="saldos" name="saldos" value="0">
<input type="hidden" id="id_cliente2" name="id_cliente2" >
<input type="hidden" id="id_factura2" name="id_factura2" >
<input type="hidden" id="tipo2" name="tipo2" >
</div>
</div>



</div>

<div class="col-sm-5 col-xs-12 no-padding">

<div class="col-sm-12">    
<div class="form-group">
<label>Descripción</label>
<textarea class="form-control" rows="6" id="descripcion" name="descripcion"></textarea>
</div>
</div>


<div class="col-sm-12">

<div class="form-group">
<label style="width:100%;margin-top: 5px;">Imprimir</label>
<div class="radio" style="float: left;margin-top: 0px;margin-right: 8px;">
<label class="text-aqua">
<input type="radio" name="opprint" id="opprint1" value="print-r" checked="">
<i class="fa fa-print"></i> <b>Recibo</b>
</label>
</div>

<div class="radio impfactura" style="float: left;margin-top: 0px;margin-right: 8px;">
<label class="text-yellow">
<input type="radio" name="opprint" id="opprint3" value="print-f">
<i class="fa fa-print"></i> <b>Factura</b>
</label>
</div>


<div class="radio" style="float: left;margin-top: 0px;">
<label class="text-red">
<input type="radio" name="opprint" id="opprint2" value="no-print">
<i class="fa fa-ban"></i> <b>Ninguno</b>
</label>
</div>
      
</div>
</div>


<div class="col-sm-6 col-xs-12">
<div class="form-group input-group-sm">

<button class="btn btn-danger" type="button" onclick="$('#tmppagos').hide();" style="width:100%"><i class="fa fa-times"></i> Cancelar</button>
</div>
</div>

<div class="col-sm-6 col-xs-12">
<div class="form-group input-group-sm">
<button class="btn btn-primary btnpagos" type="submit" style="width:100%"><i class="fa fa-money"></i> Registrar pago</button>
</div>
</div>


</div>  

</div>
</div>
</div>

</div>
 
</form>
<script type="text/javascript">

$( "#p_cliente" ).autocomplete({

minLength: 2,
source: function(request ,response){
$.ajax({
url: '<?=base_url();?>clientes/get_clientes_search',
dataType: "json",
data: {
term:request.term,
tipo:$('#p_tipo').val()
},
success: function( data ) {
response( $.map( data, function( item ) {
return { value: item.value, avatar: item.avatar,sucursal: item.sucursal,id:item.id}
}));
}
});  
},
select : function(event, ui){
   // window.location.href="<?=base_url();?>clientes/detalleuser/"+ui.item.id;
   get_facturas_cliente(ui.item.id);
},
create: function( event, ui ) {
      $(this).data("ui-autocomplete")._renderItem = function( ul, item ) {
      var inner_html = '<a><div class="list_item_container" style="margin-top:-19px;"><div class="image"><img src="<?=base_url();?>img/avatars/' + item.avatar + '" style="width:30px; height:30px;"></div><div class="autocom">' + item.value + '</div><div class="description">' + item.sucursal + '<div class="pull-right"></div></div></div></a>';
      return $( "<li></li>" )
            .data( "item.autocomplete", item )
            .append(inner_html)
            .appendTo( ul );
    }
  }
})

 get_facturas_cliente=function(id_cliente){
 $.post( '<?=base_url();?>pagos/get_facturas_cliente', { id:id_cliente})
 .done(function(data) {
   toastr.clear();
   var item=data.split('ç');
   var n_facturas=item[1];
   if(n_facturas==0){msgbox("error","Mensaje","El cliente no cuenta con facturas pendientes",4000);  $('#tmppagos').hide();}
   if(n_facturas==1){$('#tmppagos').show();   $('.callout-danger').hide(); $('#alert_n_facturas').html(text);}
   if(n_facturas>1){
      $('#tmppagos').show(); 
      var text='<i class="fa fa-exclamation-triangle"></i> Hay <b>'+n_facturas+'</b> Facturas por cobrar, total a Cobrar <b>$ '+item[2]+'</b>';
       $('.callout-danger').show(); 
       $('#alert_n_facturas').html(text);
    }
    $('#facturas').html(""); $('#facturas').html(item[0]); $('.username').html(item[3]);
    $('#id_cliente2').val(id_cliente);
    get_data_factura();

 });

}  

get_data_factura=function(){
 id_factura=$('#facturas').val();
 $.post( '<?=base_url();?>pagos/get_factura_by_id', { id:id_factura})
 .done(function(data) {
   var item=data.split('ç');
   $("#nlegal").val(item[0])
   $("#fechaemision").val(item[1])
   $("#total").val(item[2])
   $('#total2').val(item[2]);
   $('#saldos').val(0);
   $('#id_factura2').val(item[4]);
   if(item[3]=="saldo"){ $("#total").attr("readonly",true), $('#tipo2').val("saldo");}
   if(item[3]=="factura"){ $("#total").attr("readonly",false),$('#tipo2').val("factura");}
 });

}

/////////////////////////////////////////////////////////////////////////////////nuevo pagooo

function newpago(f,e){
e.preventDefault();
var variables=$(f).serialize();
if($("#total").val()<0){////////////////7no admite numeros en el total
 msgbox("error","Mensaje","Ingrese una cantidad mayor a cero",3000);
}
else{

  if($('#saldos').val()=="0"){
//////////////////////////////////solo registramos el pago
 $.post( "<?=base_url();?>pagos/save_only_pago", variables)
  .done(function( data ) {
     var item=data.split('ç');
     if(item[1]=="success"){
      msgbox("success","Mensaje","Pago registrado",2000);
      setTimeout(function(){$('#tmppagos').hide(),$('#p_cliente').val(""); }, 3000);
      
      //+++++ PRINT ++++++++++
      if(item[2]=="print-r"){
        print_recibo(item[0],base_url); 
      }else if(item[2]=="print-f"){
       print_factura($("#facturas").val(),base_url);
      }
      //+++++++++++++++++++++++ 
      
    }
     if(item[1]=="error"){ msgbox("error","Mensaje","Ocurrio un error al registrar el pago",3000);}
  }); 

}else{
if($('#saldos').val() > 0){
var msj='¿Esta seguro que desea registrar un monto menor?'; 
var msj2='Está Quedando un saldo de $ '+Math.round($('#saldos').val() * 100) / 100; 
swal({
        title: msj,
        text: msj2,
        type: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Si, Registrar!",
        closeOnConfirm: !0
    }).then(function() {
       ///////////////////////////registramos pago la transaccion y agregamos un nuevo saldo
    $.post( "<?=base_url();?>pagos/save_pago_saldo", variables)
   .done(function( data ) {
     var item=data.split('ç');
     if(item[1]=="success"){
      msgbox("success","Mensaje","Pago registrado",2000);
      setTimeout(function(){$('#tmppagos').hide(),$('#p_cliente').val("");}, 3000);
      //+++++ PRINT ++++++++++
      if(item[2]=="print-r"){
        print_recibo(item[0],base_url); 
      }else if(item[2]=="print-f"){
       print_factura($("#facturas").val(),base_url);
      }
      //+++++++++++++++++++++++ 
     
    }
     if(item[1]=="error"){ msgbox("error","Mensaje","Ocurrio un error al registrar el pago",3000);}
     });
        
 })

}else{
 msgbox("error","Mensaje","No puede registrar un monto mayor",3000);
}

}


}  /////////////////////fin else verifica no aceptar numero negativo

}  ///////fin metodo new pago


//////////////////////////////////////////////////////////////////////////////////////////


$("#facturas").change(function() {
  get_data_factura();
 });
 
 $( "#total" ).keyup(function() {
$('#saldos').val($('#total2').val()-$(this).val());
if($('#saldos').val()=="0"){

}else{

}

});

</script>