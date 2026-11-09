
<div>

	
	<div class="map-btn row">

<div class="col-sm-12" style="padding-left: 2px;padding-right: 2px;">
<div class="form-group">
<label><b>Sindicato</b></label>

<select class="form-control slmap" name="sindicatos" id="sindicatos" style="width:100%" onchange="initialize();">
<option value="%%">Todos</option>
<?=$sindicatos?>
</select>

</div>
</div>
</div>
		
 <!-- begin row -->
<div class="map">
<div id="google-map-default" class="height-full width-full" style="position: relative; overflow: hidden;">
</div>
	
 </div>
<!-- end row -->
</div>
<style type="text/css">
.map-btn{
width: 200px;
    padding: 10px;
    z-index: 500;
    position: absolute;
    right: 45px;
    top: 60px;
    background: rgba(255, 255, 255, 0.55);
    color: #251F1F;
}
.col-sm-6{
	padding:0px 5px;
}
	
@media (max-width: 767px){
.map-btn{
	width:100% !important;
	top: 120px;
	right: 12px;
}
}
	
</style>
<script>

function initialize() {
var myLatlng = new google.maps.LatLng(20.8880785,-89.7479478);
var infowindow = null;
var bounds = new google.maps.LatLngBounds();

var mapOptions = {center: myLatlng,zoom: 15,mapTypeId: google.maps.MapTypeId.ROADMAP};
var map = new google.maps.Map(document.getElementById("google-map-default"), mapOptions);
       
 
$.ajax({
url: 'socios/get_ubicaciones?action=marker&sindicatos='+document.getElementById("sindicatos").value,
            type: 'GET'      
        }).done(function(response){
            var repetidoras = JSON.parse(response);
	
            $.each(repetidoras, function(index, item){       
				

var icon = {
path: "M96 0c35.346 0 64 28.654 64 64s-28.654 64-64 64-64-28.654-64-64S60.654 0 96 0m48 144h-11.36c-22.711 10.443-49.59 10.894-73.28 0H48c-26.51 0-48 21.49-48 48v136c0 13.255 10.745 24 24 24h16v136c0 13.255 10.745 24 24 24h64c13.255 0 24-10.745 24-24V352h16c13.255 0 24-10.745 24-24V192c0-26.51-21.49-48-48-48z",
fillColor: '#00acac',
fillOpacity: 1,
strokeWeight: 1.5,
scale: 0.09,
anchor: new google.maps.Point(105,510),
labelOrigin: new google.maps.Point(150,190)
}
	

		
icon=item.icono
if(icon=="ONLINE"){
	
var icon = {
path: "M96 0c35.346 0 64 28.654 64 64s-28.654 64-64 64-64-28.654-64-64S60.654 0 96 0m48 144h-11.36c-22.711 10.443-49.59 10.894-73.28 0H48c-26.51 0-48 21.49-48 48v136c0 13.255 10.745 24 24 24h16v136c0 13.255 10.745 24 24 24h64c13.255 0 24-10.745 24-24V352h16c13.255 0 24-10.745 24-24V192c0-26.51-21.49-48-48-48z",
fillColor: '#00acac',
fillOpacity: 2,
strokeWeight: 1.3,
strokeColor: '#007974',

scale: 0.07,
anchor: new google.maps.Point(105,510),
labelOrigin: new google.maps.Point(150,190)
}

}
				
				
				
				
                var marker = new google.maps.Marker({
					animation: google.maps.Animation.DROP,
                    position: new google.maps.LatLng(item.latitud, item.longitud),
                    map: map,
					animation: google.maps.Animation.DROP,
                    icon:icon,
 
                });	
map.setCenter(marker.position);
				
if(item.elongitud){
var flightPlanCoordinates = [{lat: Number(item.elatitud), lng: Number(item.elongitud)},{lat: Number(item.latitud), lng: Number(item.longitud)}];
var flightPath = new google.maps.Polyline({
    path: flightPlanCoordinates,
    geodesic: true,
    strokeColor: '#FF0000',
    strokeOpacity: 0.7,
    strokeWeight: 2
  });

flightPath.setMap(map);
}

var streetmap=item.descr+'<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel"><ol class="carousel-indicators"><li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li><li data-target="#carouselExampleIndicators" data-slide-to="1" class=""></li><li data-target="#carouselExampleIndicators" data-slide-to="2" class=""></li><li data-target="#carouselExampleIndicators" data-slide-to="3" class=""></li></ol><div class="carousel-inner"><div class="carousel-item active"><img src="https://maps.googleapis.com/maps/api/streetview?size=520x280&location='+item.latitud+','+item.longitud+'&fov=70&heading=0&pitch=0&key='+item.api+'" /></div><div class="carousel-item"><img src="https://maps.googleapis.com/maps/api/streetview?size=520x280&location='+item.latitud+','+item.longitud+'&fov=70&heading=90&pitch=0&key='+item.api+'" /></div><div class="carousel-item"><img src="https://maps.googleapis.com/maps/api/streetview?size=520x280&location='+item.latitud+','+item.longitud+'&fov=70&heading=180&pitch=0&key='+item.api+'" /></div><div class="carousel-item"><img src="https://maps.googleapis.com/maps/api/streetview?size=520x280&location='+item.latitud+','+item.longitud+'&fov=70&heading=270&pitch=0&key='+item.api+'" /></div></div><a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span></a><a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span></a></div>';

				
google.maps.event.addListener(marker, 'click', function() { 
if (infowindow) {
infowindow.close();
}
infowindow = new google.maps.InfoWindow({ content: streetmap});
infowindow.open(map,marker);
});
});
});

} 
$(document).ready(function() {
$(".slmap").select2();	
initialize();
App.restartGlobalFunction();
App.setPageTitle('Mapa');
   
})
</script>