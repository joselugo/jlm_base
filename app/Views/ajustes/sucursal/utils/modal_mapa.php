<style type="text/css">
  .pac-container {
    z-index: 1600 !important;
  }

  .modal {
    z-index: 1300;
  }

  .modal-backdrop {
    z-index: 10;
  }
</style>
<div class="modal fade in" id="modalmap" role="dialog" tabindex="-1">
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
          <input type="text" class="form-control" id="address" name="address" onKeyUp="codeAddress()" style="width:250px !important;height: 35px;" placeholder="Ingrese una dirección">
        </div>
        <div id="map-canvas" style="width:100%; height:400px; position:fixed"></div>
      </div>
    </div>
  </div>
</div>

<script>
  $('#modalmap').modal('show');
  var geocoder = new google.maps.Geocoder();
  var marker;
  var map;

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
    $('input[name="sucursal\[ubicacion\]"]').val(latLng.lat() + ',' + latLng.lng())
  }

  function updateMarkerAddress(str) {
    //$('#txt-direccion').val(str);
  }

  function erroubi(err) {

  }

  function initialize() {
    var input = document.getElementById('address');
    var autocomplete = new google.maps.places.Autocomplete(input);

    latLng = new google.maps.LatLng(20.873758, -89.74902500000002);
    map = new google.maps.Map(document.getElementById('map-canvas'), {
      zoom: 16,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      mapTypeControlOptions: {
        style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
      }
    });


    //++++++++++> UBICACION +++++++++
    if ($('input[name="sucursal\[ubicacion\]"]').val() == "") {
      // Try HTML5 geolocation.
      if (navigator.geolocation) {

        var optionsubi = {
          enableHighAccuracy: true,
          timeout: 15000,
          maximumAge: 0
        };

        navigator.geolocation.getCurrentPosition(function(position, erroubi, optionsubi) {
          latLng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

          marker = new google.maps.Marker({
            position: latLng,
            map: map,
            draggable: true
          });
          updateMarkerPosition(latLng);
          geocodePosition(latLng);
          map.setCenter(latLng);
          var infowindow = new google.maps.InfoWindow({
            content: '<b>Ubicación del Cliente</b>'
          });
          infowindow.open(map, marker);

          google.maps.event.addListener(marker, 'dragend', function() {
            geocodePosition(marker.getPosition());
            updateMarkerPosition(marker.getPosition());
          });

        }, function() {
          latLng = new google.maps.LatLng(20.873758, -89.74902500000002);

          marker = new google.maps.Marker({
            position: latLng,
            map: map,
            draggable: true
          });
          updateMarkerPosition(latLng);
          geocodePosition(latLng);
          map.setCenter(latLng);
          var infowindow = new google.maps.InfoWindow({
            content: '<b>Ubicación del cliente</b>'
          });
          infowindow.open(map, marker);

          google.maps.event.addListener(marker, 'dragend', function() {
            geocodePosition(marker.getPosition());
            updateMarkerPosition(marker.getPosition());
          });
        });
      }

    } else {
      var corde = $('input[name="sucursal\[ubicacion\]"]').val().split(',');
      latLng = new google.maps.LatLng(parseFloat(corde[0]), parseFloat(corde[1]));
      marker = new google.maps.Marker({
        position: latLng,
        map: map,
        draggable: true
      });
      updateMarkerPosition(latLng);
      geocodePosition(latLng);
      map.setCenter(latLng);

      var infowindow = new google.maps.InfoWindow({
        content: '<b>Ubicacion del cliente</b>'
      });
      infowindow.open(map, marker);

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
    geocoder.geocode({
      'address': address
    }, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        if (marker) {
          marker.setMap(null);
        }
        var nucor = String(results[0].geometry.location);
        nucor = nucor.replace(")", "")
        nucor = nucor.replace('(', '')
        nucor = nucor.split(',');
        latLng = new google.maps.LatLng(parseFloat(nucor[0]), parseFloat(nucor[1]));

        map.setCenter(latLng);
        marker = new google.maps.Marker({
          map: map,
          position: latLng,
          draggable: true
        });

        updateMarkerPosition(latLng);
        geocodePosition(latLng);
        map.setCenter(latLng);

        var infowindow = new google.maps.InfoWindow({
          content: '<b>Ubicación del cliente</b>'
        });
        infowindow.open(map, marker);

        google.maps.event.addListener(marker, 'dragend', function() {
          geocodePosition(marker.getPosition());
          updateMarkerPosition(marker.getPosition());
        });

      }
    });
  }


  $(function() {
    $('#modalmap').on('shown.bs.modal', function() {
      initialize();
    });

    $('#modalmap').on('hidden.bs.modal', function() {
      $('#modalmap,#tmp2').empty().html('');
    })

  })
</script>