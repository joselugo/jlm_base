
<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
  <li class="breadcrumb-item"><a href="#index/home">Inicio</a></li>
  <li class="breadcrumb-item"><a href="#socios">Socios</a></li>
  <li class="breadcrumb-item active">Nuevo socio</li>
</ol>
<!-- end breadcrumb -->
<!-- begin page-header -->
<h1 class="page-header">Nuevo Socio</h1>
<!-- end page-header -->

<!-- begin wizard-form -->
<form action="socios/new_socio?action=newuser" method="POST" name="form-wizard" class="form-control-with-bg">
<input type="hidden" name="install" value="">
  <!-- begin wizard -->
  <div id="wizard" style="" class="sw-main sw-theme-default">
    <!-- begin wizard-step -->
    <ul class="nav nav-tabs step-anchor">
      <li class="col-md-3 col-sm-4 col-6 active">
        <a href="#step-1">
          <span class="number">1</span> 
          <span class="info text-ellipsis">
            Datos personales
            <small class="text-ellipsis">Nombre, dirección, teléfonos</small>
          </span>
        </a>
      </li>
      <li class="col-md-3 col-sm-4 col-6">
        <a href="#step-2">
          <span class="number">2</span> 
          <span class="info text-ellipsis">
            Sindicato
            <small class="text-ellipsis">Sindicato afiliado</small>
          </span>
        </a>
      </li>
      <li class="col-md-3 col-sm-4 col-6">
        <a href="#step-3">
          <span class="number">3</span>
          <span class="info text-ellipsis">
            Unidades
            <small class="text-ellipsis">Vehiculos, mototaxis, triciclos.</small>
          </span>
        </a>
      </li>

    </ul>
    <!-- end wizard-step -->
    <!-- begin wizard-content -->
    <div class="sw-container tab-content" style="min-height: 0px;">
      <!-- begin step-1 -->
      <div id="step-1" class="step-content" style="display: block;">
        <!-- begin fieldset -->
        <fieldset>
          <!-- begin row -->
          <div class="row">
            <!-- begin col-8 -->
            <div class="col-lg-9 offset-md-2">

             
              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">Contraseña Portal</label>
                <div class="col-md-4">
                  <input type="text" name="password" placeholder="4243Tdp" class="form-control form-control-sm" data-parsley-group="step-1" autocomplete="postal-code">
            <small class="text-warning">Dejar en blanco para que sea automático.</small>
                </div>
              </div>  
              
              
                          <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">Nº Identificación</label>
                <div class="col-md-8">
                  <input value="" type="text" name="ine" placeholder="223456634" data-parsley-group="step-1" class="form-control form-control-sm" autocomplete="off" style="max-width:160px;float: left;">
                  <br>                  
                    <small class="text-warning m-b-10" style="width: 100%;float: left;">INE,CURP,CARTILLA MILITAR, PASAPORTE</small>                  
                </div>
              </div>
              
              
              <!-- begin form-group -->
              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">Nombres <span class="text-danger">*</span></label>
                <div class="col-md-8">
                  <input value="" type="text" name="nombre" placeholder="Miguel Ángel" data-parsley-group="step-1" data-parsley-required="true" class="form-control form-control-sm" autocomplete="off" required="">
                </div>
              </div>

               <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">Apellidos <span class="text-danger">*</span></label>
                <div class="col-md-8">
                  <input value="" type="text" name="apellidos" placeholder="Santana Castro" data-parsley-group="step-1" data-parsley-required="true" class="form-control form-control-sm" autocomplete="off" required="">
                </div>
              </div>
              
              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">Dirección principal</label>
                <div class="col-md-8">
                  <input value="" type="text" name="domicilio" placeholder="Av. unios 4453" class="form-control form-control-sm" data-parsley-group="step-1" autocomplete="address-line1'">
                </div>
              </div>

              <div class="form-group row m-b-10">
<label class="col-md-3 col-form-label text-right">Ubicación</label>
<div class="col-md-8">

<div class="input-group mb-3">
  <input type="text" class="form-control" name ="ubicacion">
    <div class="input-group-append">
 
   <button style="float: left;" type="button" class="btn btn-outline-secondary" data-toggle="tooltip" data-placement="top" 
   data-original-title="Seleccionar ubicación en Google Maps" onclick="viewmodal();" 
   data-loading-text="<i class=&quot;fas fa-spinner fa-spin&quot;></i>"><i class="fas fa-search"></i></button>

  </div>
</div>
</div>
</div>

        

                         
              
              <div class="form-group row m-b-10">
          <label class="col-md-3 col-form-label text-md-right">Teléfono Movil</label>
                <div class="col-md-8">
                  <input value="" type="text" name="movil" placeholder="9876526478" class="form-control form-control-sm" data-parsley-group="step-1" autocomplete="off">
                </div>
              </div>              

              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">E-mail</label>
                <div class="col-md-8">
                  <input value="" type="text" name="correo" placeholder="jorge@correo.com" class="form-control form-control-sm" data-parsley-group="step-1" autocomplete="email">
                </div>
              </div>

                <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">Licencia</label>
                <div class="col-md-8">
                  
              <select name="licencia" style="width: 100%;" class="slw">
              <option value="SI">SI</option>
              <option value="NO">NO</option>
            </select>

              </div>
              </div>  
              
              
              <!-- end form-group -->
            </div>
            <!-- end col-8 -->
          </div>
          <!-- end row -->
        </fieldset>
        <!-- end fieldset -->
      </div>
      <!-- end step-1 -->
      <!-- begin step-2 -->
      <div id="step-2" class="step-content">
        <!-- begin fieldset -->
        <fieldset>
          <!-- begin row -->
          <div class="row">

      

<div class="col-lg-9 form-horizontal ui-sortable offset-md-2">
  <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">Tipo socio</label>
                <div class="col-md-4">
                 <select name="status" style="width: 100%;" class="slw">
              <option value="ACTIVO">ACTIVO</option>
              <option value="PRUEBA">SOCIO EN PRUEBA</option>
            </select>
                </div>
              </div> 

</div>

<div class="col-lg-9 form-horizontal ui-sortable offset-md-2">
          <div class="form-group row m-b-10">
            <label class="col-md-3 col-form-label text-md-right">Sindicato</label>
              <div class="col-md-4">
                <select name="sindicato" style="width: 100%;" class="slw">
                 <?=$sindicatos?>
                 </select>
              </div>
           </div> 

</div>
            
            
          </div>
          <!-- end row -->
        </fieldset>
        <!-- end fieldset -->
      </div>
      <!-- end step-2 -->
      <!-- begin step-3 -->
      <div id="step-3" class="step-content">
        <!-- begin fieldset -->
        <fieldset>
          <!-- begin row -->
<div class="row">




<div class="col-lg-6" style="min-height:150px">
  
<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Tipo</label>
<div class="col-md-8">
<select class="form-control sl2" name="tipounidad" style="width:100%">
<option value="Mototaxi">Mototaxi</option>
<option value="Triciclo">Triciclo</option>
<option value="Motocarro">Motocarro</option>
<option value="Carro">Carro</option>
</select>
</div>
</div>
  

<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Matrícula</label>
<div class="col-md-8">
<input type="text" class="form-control" name="matricula" data-parsley-required="true" data-parsley-group="step-3" required="">
</div>
</div>

<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Factura</label>
<div class="col-md-8">
<input type="text" class="form-control" name="factura">
<small>* Número de factura o serie de la unidad</small>
</div>
</div>
<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Descripción</label>
<div class="col-md-8">
<textarea class="form-control" name="descripcion" style="padding-top: 7px;"></textarea>
<small>* Texto para la unidad</small>
</div>
</div>



</div>                       

<div class="dataservice col-lg-6">

<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Modo</label>
<div class="col-md-8">
<select class="form-control sl2" name="modounidad" style="width:100%">
<option value="PROPIO">PROPIO</option>
<option value="CONCESIONADO">CONCESIONADO</option>
</select>
</div>
</div>

<div class="form-group row">
<label class="col-md-4 col-form-label text-right">ID Sindicato
</label>
<div class="col-md-8">
<input type="text" class="form-control" name="codigosindicato" readonly="">
<small>* Código único de identificación en el sindicato</small>
</div>
</div>

<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Fotografia
<button type="button" class="btn btn-icono btn-xs btn-map" onclick="viewimage()" data-toggle="tooltip" data-placement="top" data-original-title="Ver imagen"><i class="fas fa-eye"></i></button>
</label>
<div class="col-md-8">
<input class="form-control-file" name="fotounidad" type="file" accept="image/*,.pdf,.zip">
<small>* Fotografia de la unidad</small>
</div>
</div>


<div class="form-group row">
<label class="col-md-4 col-form-label text-right"></label>
<div class="col-md-8">
<a class="image-popup-vertical-fit" id="imgSalida2" href="">
  <img src="" id="imgSalida" width="70px">
</a>
</div>

</div>

<style type="text/css">
  .mfp-wrap {
  z-index: 2000; }
  .tooltip{ z-index: 2000; }
</style>


  

</div>

  

</div>
        
          <!-- end row -->
        </fieldset>
        <!-- end fieldset -->
      </div>
      <!-- end step-3 -->
    </div>


    <!-- end wizard-content -->
  </div>
  <!-- end wizard -->
</form>
<!-- end wizard-form -->


<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script>

  
$(function() {

getcodigo();  

$("select[name=\"sindicato\"]").change(function(){
getcodigo();
});

$('.image-popup-vertical-fit').magnificPopup({
    type: 'image',
    closeOnContentClick: true,
    mainClass: 'mfp-img-mobile',
    image: {
      verticalFit: true
    }
    
  });

$('[name="fotounidad"]').change(function(e) {
      addImage(e); 
     });    
  
passwordaleatorio('servicio\[ppppass\]');
  
    App.setPageTitle('Nuevo socio');
    App.restartGlobalFunction();
  
  
$('.slw,.sl2').select2();

  $('#wizard').smartWizard({ 
    selected: 0, 
    theme: 'default',
    transitionEffect:'',
    transitionSpeed: 0,
    useURLhash: false,
    lang: {  
                next: 'Siguiente', 
                previous: 'Anterior'
            },
    showStepURLhash: false,
    toolbarSettings: {
    toolbarPosition: 'bottom',
                toolbarExtraButtons: [
$('<button></button>').html('<i class="far fa-save fa-lg"></i> Registrar Socio').attr('type', 'submit').addClass('btn-loader btn btn-success btn-finish d-none'),
]
},
    
  
  });
  
  $('#wizard').on('leaveStep', function(e, anchorObject, stepNumber, stepDirection) {
  var res = $('form[name="form-wizard"]').parsley().validate('step-' + (stepNumber + 1));
  return res; 
  });


 $("#wizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection) {
if(stepNumber==2){
$('.btn-finish').removeClass( "d-none" );
$('.sw-btn-group').hide();
}else{
$('.btn-finish').addClass( "d-none" );
$('.sw-btn-group').show();
}
  });

  
  
$('form[name="form-wizard"]').ajaxForm({
success: function(data) {
btnloader('form[name="form-wizard"]','<i class="far fa-save fa-lg"></i> Registrar Cliente');
$('#gritter-notice-wrapper').remove();
if(data=="success"){
alerta('exito',"Socio registrado correctamente");
loadurl("#socios");
}
else if(data=="matricula-duplicada"){
alerta('warning',"La matrícula ya esta registrada");
}else{
alerta('error',data);
}
},
beforeSubmit: function() {
btnloader('form[name="form-wizard"]');
},
error: function(response) {
btnloader('form[name="form-wizard"]','<i class="far fa-save fa-lg"></i> Registrar Cliente');
$('#gritter-notice-wrapper').remove();
alerta('error500',response['responseText']);
}})

  
//$('select[name="servicio\[nodo\]"]').trigger('change'); 
$('#wizard').show();
  
})

function getcodigo(){

$.post( "socios/get_codigo_sindicato", {id: $("select[name=\"sindicato\"]").val() })
  .done(function( data ) { $("[name=\"codigosindicato\"]").val(data) });

}

function addImage(e){
      var file = e.target.files[0],
      imageType = /image.*/;
    
      if (!file.type.match(imageType))
       return;
  
      var reader = new FileReader();
  
      reader.onload = function(e){
         var result=e.target.result;
        $('#imgSalida').attr("src",result);
        $('#imgSalida2').attr("href",result);
      }
       
      reader.readAsDataURL(file);
     }

 function viewimage(){
    $('#imgSalida2').click();
 }     
</script>
<!-- ================== END PAGE LEVEL JS ================== -->

<div id="tmp"><style type="text/css">
.pac-container {
    z-index: 1600 !important;
}
.modal{
    z-index: 1300;   
}
.modal-backdrop{
    z-index: 10;        
}
</style>
<div class="modal fade in show" id="modalmap" role="dialog">
<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
<div class="modal-content">
<div class="modal-header">
<h4 class="modal-title"><i class="fas fa-list"></i> Google Maps</h4>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
</div>
<div class="modal-body no-padding" style="display: block;">
<div class="input-group" style="width:290px !important;position: absolute;
  top: 10px;
  left: 15%;
  z-index: 5;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);">
 <div class="input-group-addon"><b><i class="fas fa-search"></i></b></div>
<input type="text" class="form-control" id="address" name="address" onkeyup="codeAddress()" style="height: 35px;" placeholder="Ingrese una dirección" autocomplete="off">
</div>
<div id="map-canvas" style="width: 100%; height: 400px; position: relative; overflow: hidden;">
</div>
    
<script>

var geocoder = new google.maps.Geocoder();
var marker;
var map;


function viewmodal(){
  $('#modalmap').modal('show');
}

function geocodePosition(pos) {
  geocoder.geocode({
    latLng: pos
  }, function(responses) {
    if (responses && responses.length > 0) {
      updateMarkerAddress(responses[0].formatted_address);
    } else {
      updateMarkerAddress('Cannot determine address at this location.');
    }
  });
}

function updateMarkerPosition(latLng) {
$('[name="ubicacion"]').val(latLng.lat()+','+latLng.lng())
}

function updateMarkerAddress(str) {
//$('#txt-direccion').val(str);
}

function initialize() {
var input = document.getElementById('address');
var autocomplete = new google.maps.places.Autocomplete(input);

  var latLng = new google.maps.LatLng(20.8809818,-89.7436489);
  map = new google.maps.Map(document.getElementById('map-canvas'), {
    zoom: 16,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
  mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
  });


//++++++++++> UBICACION +++++++++
if($('[name="ubicacion"]').val()==""){
   // Try HTML5 geolocation.
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
latLng= new google.maps.LatLng(position.coords.latitude,position.coords.longitude);
 
 marker = new google.maps.Marker({
    position: latLng,
    map: map,
    draggable: true
  });  
updateMarkerPosition(latLng);
geocodePosition(latLng);
map.setCenter(latLng);
var infowindow = new google.maps.InfoWindow( { content: '<b>'+$('[name="nombre"]').val()+'</b>' } );
infowindow.open( map, marker );

google.maps.event.addListener(marker, 'dragend', function() {
geocodePosition(marker.getPosition());
updateMarkerPosition(marker.getPosition());
});

    }, function() {
latLng= new google.maps.LatLng(20.8809818,-89.7436489);
 
 marker = new google.maps.Marker({
    position: latLng,
    map: map,
    draggable: true
  });  
updateMarkerPosition(latLng);
geocodePosition(latLng);
map.setCenter(latLng);
var infowindow = new google.maps.InfoWindow( { content: '<b>'+$('[name="nombre"]').val()+'</b>' } );
infowindow.open( map, marker );

google.maps.event.addListener(marker, 'dragend', function() {
geocodePosition(marker.getPosition());
updateMarkerPosition(marker.getPosition());
});
    });
  }
  
 }else{
var corde = $('[name="ubicacion"]').val().split(',');
latLng= new google.maps.LatLng(parseFloat(corde[0]),parseFloat(corde[1]));
marker = new google.maps.Marker({
    position: latLng,
    map: map,
    draggable: true
  });  
updateMarkerPosition(latLng);
geocodePosition(latLng);
map.setCenter(latLng);

var infowindow = new google.maps.InfoWindow( { content: '<b>'+$('[name="nombre"]').val()+'</b>' } );
infowindow.open( map, marker );

google.maps.event.addListener(marker, 'dragend', function() {
geocodePosition(marker.getPosition());
updateMarkerPosition(marker.getPosition());
});
 }
  
//+++++++++++++++++++++++++++++++

}

//---------->BUSCAR EN MAPA
function codeAddress() {
  var address = document.getElementById('address').value;
   geocoder.geocode( { 'address': address}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
  if(marker){
  marker.setMap(null);
  }
  var nucor=String(results[0].geometry.location);
  nucor=nucor.replace(')','')
  nucor=nucor.replace('(','')
  nucor=nucor.split(',');
  latLng= new google.maps.LatLng(parseFloat(nucor[0]),parseFloat(nucor[1]));
  
    map.setCenter(latLng);
      marker = new google.maps.Marker({
      map: map,
      position: latLng,
      draggable: true
      });
  
  updateMarkerPosition(latLng);
geocodePosition(latLng);
map.setCenter(latLng);

var infowindow = new google.maps.InfoWindow( { content: '<b>'+$('[name="nombre"]').val()+'</b>' } );
infowindow.open( map, marker );

google.maps.event.addListener(marker, 'dragend', function() {
geocodePosition(marker.getPosition());
updateMarkerPosition(marker.getPosition());
});
  
    }
  });
}


$(function(){
$('#modalmap').on('shown.bs.modal', function () {
initialize();
});
})

</script>
</div>