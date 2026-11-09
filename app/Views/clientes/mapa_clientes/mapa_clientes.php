<div id="map"></div>
<style>
  #map {
    height: calc(100% + 40px);
    margin-top: -20px;
    margin-left: -30px;
    margin-bottom: -20px;
    margin-right: -30px;
  }
</style>
<script type="text/javascript">
  data = <?php echo json_encode($data); ?>;
  nodos = <?php echo json_encode($nodos); ?>;
  server = <?php echo json_encode($server); ?>;
  sucursales = <?php echo json_encode($sucursales); ?>;
  var markers = [];
  var markers_server = [];
  var poligonos = [];
  var map;
  var infowindow
  

  inicializar();

  function inicializar() {
    infowindow = new google.maps.InfoWindow();


    map = new google.maps.Map(document.getElementById("map"), {
      center: {
        lat: 20.881648,
        lng: -89.747319
      },
      zoom: 14,
      mapTypeControlOptions: {
        position: google.maps.ControlPosition.BOTTOM_CENTER
      }
    });





    function CenterControl(controlDiv, map) {
      // Set CSS for the control border.
      var controlUI = document.createElement("div");
      controlUI.style.backgroundColor = "#fff";
      controlUI.style.border = "2px solid #fff";
      controlUI.style.borderRadius = "5px 5px 5px 5px";
      controlUI.style.boxShadow = "0 2px 6px rgba(0,0,0,.3)";
      controlUI.style.cursor = "pointer";
      controlUI.style.marginBottom = "22px";
      controlUI.style.textAlign = "center";
      controlUI.style.marginTop = "15px";
      controlUI.style.marginLeft = "10px";
      controlUI.title = "Click to recenter the map";
      controlUI.style.width = "290px"


      controlUI.innerHTML = `
      <div class="container-fluid">
        <form>
          <div class="form-group">
            <label style="margin-top:5px">FILTROS</label>
            <select style="margin-top:10px" onchange="filtrar()" id="sucursal" class="form-control form-control-sm mapselect" placeholder="Seleccionar Nodo">
              <option value="" selected>Sucursales</option>
              ${sucursales}
            </select>
            <select style="margin-top:10px" onchange="filtrar()" id="nodo" class="form-control form-control-sm mapselect" placeholder="Seleccionar Nodo">
              <option value="" selected>Nodos</option>
              ${nodos}
            </select>
          </div>
        </form>
      </div>
      `;
      var controlUI2 = document.createElement("div");


      controlDiv.appendChild(controlUI);


      // Setup the click event listeners: simply set the map to Chicago.
      controlUI.addEventListener("click", function() {});

      // Create the DIV to hold the control and call the CenterControl()
      // constructor passing in this DIV.

    }

    var centerControlDiv = document.createElement("div");
    var centerControl = new CenterControl(centerControlDiv, map);

    centerControlDiv.index = 1;
    map.controls[google.maps.ControlPosition.LEFT_TOP].push(centerControlDiv);

    recorrerInsertar_server(server, map, infowindow)
    recorrerInsertar(data, map, infowindow)

    setMapOnAll(map)


  };

  function filtrar() {
    nodo = $("#nodo").val()
    sucursal = $("#sucursal").val()
    $.ajax({
      type: "POST",
      url: "clientes/filter_clientes_map",
      data: {
        nodo,
        sucursal
      },
      success: function(response) {
        data = JSON.parse(response)
        deleteMarkers();
        recorrerInsertar(data['data'], map, infowindow)
        recorrerInsertar_server(data['server'], map, infowindow)
        setMapOnAll(map)
      }
    });
  }


  function getLatLng(ll) {
    var latlng = ll.split(/, ?/)
    return new google.maps.LatLng(parseFloat(latlng[0]), parseFloat(latlng[1]));
  }

  function setMapOnAll(map) {
    for (var i = 0; i < markers.length; i++) {
      markers[i].setMap(map);
    }
    for (var i = 0; i < markers_server.length; i++) {
      markers_server[i].setMap(map);
    }
  }

  // Removes the markers from the map, but keeps them in the array.
  function clearMarkers() {
    setMapOnAll(null);
  }

  // Shows any markers currently in the array.
  function showMarkers() {
    setMapOnAll(map);
  }

  // Deletes all markers in the array by removing references to them.
  function deleteMarkers() {
    clearMarkers();
    markers = [];
    markers_server = [];
  }

  function recorrerInsertar(data, map, infowindow) {
    for (let i = 0; i < data.length; i++) {
      if (data[i].coordenadas != "") {
        var myLatlng = getLatLng(data[i].coordenadas)
        if (data[i].status_user == "ONLINE") {
          icono = "usuario_activo.png"
        } else {
          icono = "usuario_inactivo.png"
        }
        var marker = new google.maps.Marker({
          position: myLatlng,
          icon: "<?= base_url() ?>public/images/mapa/" + icono
        });
        content = `
      <div>
        <h5><span class="far fa-user"></span> ${data[i].nombre} </h5>
        <p><span class="fas fa-home"></span> ${data[i].direccion} </p>
        <p><span class="fas fa-phone-square"></span> ${data[i].telefono} <span class="fas fa-mobile-alt"></span> ${data[i].movil}</p>
        <p><i class="fas fa-laptop"></i> ${data[i].ipap} <i class="fas fa-signal"></i> ${data[i].ip} </p>
      </div>
      `

        makeInfoWindowEvent(map, infowindow, content, marker, infowindow);
        markers.push(marker)
      }

    }



  }

  function recorrerInsertar_server(data, map, infowindow) {
    for (let i = 0; i < data.length; i++) {
      if (data[i].coordenadas != "") {
        var myLatlng = getLatLng(data[i].coordenadas)
        if (data[i].estado == "CONECTADO") {
          icono = "server_activo.png"
        } else {
          icono = "server_inactivo.png"
        }
        var marker = new google.maps.Marker({
          position: myLatlng,
          icon: "<?= base_url() ?>public/images/mapa/" + icono
        });
        content = `
        <div>
          <h5><i class="fas fa-rss"></i> ${data[i].nodo}</h5>
          <p>IP: ${data[i].ip}</p>
        </div>
        `
        makeInfoWindowEvent(map, infowindow, content, marker, infowindow);
        markers_server.push(marker)
      }
    }
  }

  function makeInfoWindowEvent(map, infowindow, contentString, marker, infowindow) {
    google.maps.event.addListener(marker, 'click', function() {
      infowindow.setContent(contentString);
      infowindow.open(map, marker);
    });
  }
  // Append the 'script' element to 'head'
  inicio()

  function inicio() {
    sala = 'mapaDeClientes'
    socket.emit('join_room', sala);
  }
  socket.on('datosMapa', (datos) => {
    console.log("actualizacion");
    data.forEach(element => {
      element.status_user = "OFFLINE";
      let existe = datos.datos.find(ip => ip.ip == element.ip);
      if (existe != undefined) {
        element.status_user = "ONLINE";
      }
    });
    deleteMarkers();
    recorrerInsertar(data, map, infowindow)
    setMapOnAll(map)
  })

</script>