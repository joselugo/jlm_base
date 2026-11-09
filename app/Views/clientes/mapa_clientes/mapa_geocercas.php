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
  permiso = <?= $permiso; ?>;
  var markers = [];
  var markers_server = [];
  var poligonos = [];
  var tipos_poligono = [];
  var map;
  var infowindow
  var modo_edit = false;
  var modo_verclientes = false;
  var modo_ver_tenologia = false;
  var modo_fullscreen = false;
  var modoArrastrar = false;
  var id_poligono_seleccionado = 0;
  var drawingManager = null;
  // Google retiró DrawingManager de la API de Maps (v3.65+): el constructor lanza un error que
  // impedía mostrar el mapa y las geocercas guardadas. Sin él solo se desactiva dibujar nuevas.
  try {
  drawingManager = new google.maps.drawing.DrawingManager({
    drawingControl: true,
    drawingControlOptions: {
      position: google.maps.ControlPosition.TOP_CENTER,
      drawingModes: [
        /*google.maps.drawing.OverlayType.MARKER, */
        google.maps.drawing.OverlayType.CIRCLE,
        google.maps.drawing.OverlayType.POLYGON,
        google.maps.drawing.OverlayType.POLYLINE,
        /* google.maps.drawing.OverlayType.RECTANGLE, */
      ],
    },
    circleOptions: {
      fillColor: "#ffff00",
      fillOpacity: .5,
      strokeWeight: 3,
      clickable: false,
      editable: true,
      zIndex: 1,
    },
  });
  } catch (e) {
    drawingManager = null;
    console.warn('Herramientas de dibujo no disponibles en esta versión de Google Maps:', e.message);
  }

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
    <?php foreach ($geocercas as $dato) : ?>
      tipo = "<?= $dato->tipo; ?>";
      color_linea = "<?= $dato->color_linea == "" ? "#bcbcbc" : $dato->color_linea; ?>";
      color_relleno = "<?= $dato->color_relleno == "" ? "#f9f5f5" : "$dato->color_relleno"; ?>";
      // Construct the polygon.
      <?php if ($dato->tipo == "circle") : ?>
        <?php $txt = $dato->tipo == "circle" ? explode("/", $dato->coordenadas) : array() ?>
        radius = <?= $dato->tipo == "circle" ? $txt[1] : 100; ?>;
        var <?= $dato->tipo ?><?= $dato->id ?> = new google.maps.Circle({
          visible: <?= $dato->tipo_tecnologia == 1 ? "true" : "false" ?>,
          strokeColor: color_linea,
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: color_relleno,
          fillOpacity: 0.35,
          center: new google.maps.LatLng(<?= $dato->tipo == "circle" ? $txt[0] : "10,10"; ?>),
          radius: radius,
        });
        google.maps.event.addListener(<?= $dato->tipo ?><?= $dato->id ?>, 'radius_changed', function() {
          newcoordenadas = <?= $dato->tipo ?><?= $dato->id ?>.center.lat() + "," + <?= $dato->tipo ?><?= $dato->id ?>.center.lng() + "/" + <?= $dato->tipo ?><?= $dato->id ?>.getRadius();
          update_poligono(<?= $dato->id ?>, newcoordenadas)
        })
        google.maps.event.addListener(<?= $dato->tipo ?><?= $dato->id ?>, 'center_changed', function() {
          newcoordenadas = <?= $dato->tipo ?><?= $dato->id ?>.center.lat() + "," + <?= $dato->tipo ?><?= $dato->id ?>.center.lng() + "/" + <?= $dato->tipo ?><?= $dato->id ?>.getRadius();
          update_poligono(<?= $dato->id ?>, newcoordenadas)
        })
      <?php endif ?>
      <?php if ($dato->tipo == "polygon") : ?>
        var coordenadas = [<?= str_replace(" ", "", $dato->coordenadas); ?>];
        var <?= $dato->tipo ?><?= $dato->id ?> = new google.maps.Polygon({
          visible: <?= $dato->tipo_tecnologia == 1 ? "true" : "false" ?>,
          paths: coordenadas,
          strokeColor: color_linea,
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: color_relleno,
          fillOpacity: 0.35,
          clickable: true,
        });
        google.maps.event.addListener(<?= $dato->tipo ?><?= $dato->id ?>.getPath(), 'insert_at', function(index, obj) {
          txt = get_data_update(<?= $dato->tipo ?><?= $dato->id ?>);
          update_poligono(<?= $dato->id ?>, txt)
        });
        google.maps.event.addListener(<?= $dato->tipo ?><?= $dato->id ?>.getPath(), 'set_at', function(index, obj) {
          txt = get_data_update(<?= $dato->tipo ?><?= $dato->id ?>);
          update_poligono(<?= $dato->id ?>, txt)
        });


        google.maps.event.addListener(<?= $dato->tipo ?><?= $dato->id ?>.getPath(), 'remove_at', function(index, obj) {

          txt = get_data_update(<?= $dato->tipo ?><?= $dato->id ?>);
          update_poligono(<?= $dato->id ?>, txt)
        });

      <?php endif ?>
      <?php if ($dato->tipo == "polyline") : ?>
        var coordenadas = [<?= str_replace(" ", "", $dato->coordenadas); ?>];
        var <?= $dato->tipo ?><?= $dato->id ?> = new google.maps.Polyline({
          visible: <?= $dato->tipo_tecnologia == 1 ? "true" : "false" ?>,
          path: coordenadas,
          strokeColor: color_linea,
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: color_relleno,
          fillOpacity: 0.35,
          clickable: true,
        });
        google.maps.event.addListener(<?= $dato->tipo ?><?= $dato->id ?>.getPath(), 'insert_at', function(index, obj) {
          txt = get_data_update(<?= $dato->tipo ?><?= $dato->id ?>);
          update_poligono(<?= $dato->id ?>, txt)
        });
        google.maps.event.addListener(<?= $dato->tipo ?><?= $dato->id ?>.getPath(), 'set_at', function(index, obj) {
          txt = get_data_update(<?= $dato->tipo ?><?= $dato->id ?>);
          update_poligono(<?= $dato->id ?>, txt)
        });


        google.maps.event.addListener(<?= $dato->tipo ?><?= $dato->id ?>.getPath(), 'remove_at', function(index, obj) {

          txt = get_data_update(<?= $dato->tipo ?><?= $dato->id ?>);
          update_poligono(<?= $dato->id ?>, txt)
        });

      <?php endif ?>
      <?= $dato->tipo ?><?= $dato->id ?>.setMap(map);
      poligonos.push(<?= $dato->tipo ?><?= $dato->id ?>);
      tipos_poligono.push(<?= $dato->tipo_tecnologia ?>);

      google.maps.event.addListener(<?= $dato->tipo ?><?= $dato->id ?>, 'rightclick', function(event) {
        click_body_fuction(<?= $dato->tipo ?><?= $dato->id ?>.type, <?= $dato->tipo ?><?= $dato->id ?>, <?= $dato->id; ?>, "<?= ucfirst($dato->nombre); ?>", "<?= ucfirst($dato->descripcion); ?>", "<?= $dato->color_linea; ?>", "<?= $dato->color_relleno; ?>");
        if (modo_edit == true) {
          eliminarPoligono(<?= $dato->tipo ?><?= $dato->id ?>, <?= $dato->id; ?>)
        }
      });
      google.maps.event.addListener(<?= $dato->tipo ?><?= $dato->id ?>, 'click', function(event) {
        click_body_fuction(<?= $dato->tipo ?><?= $dato->id ?>.type, <?= $dato->tipo ?><?= $dato->id ?>, <?= $dato->id; ?>, "<?= ucfirst($dato->nombre); ?>", "<?= ucfirst($dato->descripcion); ?>", "<?= $dato->color_linea; ?>", "<?= $dato->color_relleno; ?>");
      });

    <?php endforeach; ?>



    if (drawingManager) google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
      coordenadasString = "";
      tipo = event.type;
      color_linea = $("#color-picker2").val();
      color_relleno = $("#color-picker").val();
      newOptions = {
        strokeColor: color_linea,
        strokeWeight: 2,
        editable: true,
        fillColor: color_relleno,
        fillOpacity: 0.35,
        clickable: true,
      };
      if (tipo == "circle") {
        var coordenadasString = event.overlay.center.lat() + "," + event.overlay.center.lng() + "/" + event.overlay.getRadius();
      } else {
        event.overlay.getPath().getArray().forEach(function(position, i) {
          coma = i != 0 ? "," : "";
          coordenadasString = coordenadasString + coma + "{lat:" + position.lat() + ",lng:" + position.lng() + "}";
        });

      }
      event.overlay.setOptions(newOptions);
      agregarForma(event, coordenadasString, color_linea, color_relleno);
    });
    google.maps.event.addListener(map, 'bounds_changed', function() {
      if (map.getDiv().firstChild.clientHeight === window.innerHeight && map.getDiv().firstChild.clientWidth === window.innerWidth) {
        modo_fullscreen = true;
      } else {
        modo_fullscreen = false;
      }
    });

    function CenterControl(controlDiv, map) {
      // Set CSS for the control border.
      var controlUI = document.createElement("div");
      controlUI.id = "filtros_geocerca";
      controlUI.style.backgroundColor = "#fff";
      controlUI.style.border = "2px solid #fff";
      controlUI.style.borderRadius = "5px 5px 5px 5px";
      controlUI.style.boxShadow = "0 2px 6px rgba(0,0,0,.3)";
      controlUI.style.cursor = "pointer";
      controlUI.style.marginBottom = "22px";
      controlUI.style.textAlign = "center";
      controlUI.style.marginTop = "15px";
      controlUI.style.marginLeft = "10px";
      controlUI.style.display = "none";
      controlUI.title = "Click to recenter the map";
      controlUI.style.width = "290px"

      if (<?= $permiso; ?> >= 2) {
        controlUI.innerHTML = `
      <div class="container-fluid">
        <form>
          <div class="form-group">
            <div class="row">
              <div style="text-align: left;" class="col mt-1">
               <label style="margin-top:5px">FILTROS</label>
              </div>
              <div class="col">
               <a onclick="cerrar_ver_clientes();" data-toggle="tooltip" title="Dejar de ver a los clientes"  type="button" class="close" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </a>
              </div>
            </div>
            <div class="row">
            <select style="margin-top:10px" onchange="filtrar()" id="sucursal" class="form-control form-control-sm mapselect" placeholder="Seleccionar Nodo">
              <option value="" selected>Sucursales</option>
              ${sucursales}
            </select>
            <select style="margin-top:10px" onchange="filtrar()" id="nodo" class="form-control form-control-sm mapselect" placeholder="Seleccionar Nodo">
              <option value="" selected>Nodos</option>
              ${nodos}
            </select>
            </div>
          </div>
        </form>
      </div>
      `;
      } else {
        controlUI.innerHTML = "";
      }
      var controlUI02 = document.createElement("div");
      controlUI02.id = "filtros_geocerca_tenologia";
      controlUI02.style.backgroundColor = "#fff";
      controlUI02.style.border = "2px solid #fff";
      controlUI02.style.borderRadius = "5px 5px 5px 5px";
      controlUI02.style.boxShadow = "0 2px 6px rgba(0,0,0,.3)";
      controlUI02.style.cursor = "pointer";
      controlUI02.style.marginBottom = "22px";
      controlUI02.style.textAlign = "center";
      controlUI02.style.marginTop = "15px";
      controlUI02.style.marginLeft = "10px";
      controlUI02.style.display = "none";
      controlUI02.title = "Click to recenter the map";
      controlUI02.style.width = "290px"

      if (<?= $permiso; ?> >= 1) {
        controlUI02.innerHTML = `
      <div class="container-fluid">
        <form>
          <div class="form-group">
            <div class="row">
              <div style="text-align: left;" class="col mt-1">
               <label style="margin-top:5px">TECNOLOGIA</label>
              </div>
              <div class="col">
               <a onclick="cerrar_ver_tegnologia();" data-toggle="tooltip" title="Dejar de ver este filtro"  type="button" class="close" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </a>
              </div>
            </div>
            <div class="row">
            <select style="margin-top:10px" onchange="filtrar_tenologia()" id="tecnologia" class="form-control form-control-sm mapselect" placeholder="Seleccionar Nodo">
              <option value="1" selected>WISP</option>
              <option value="2">COAXIAL</option>
              <option value="3">FIBRA</option>
            </select>
            </div>
          </div>
        </form>
      </div>
      `;
      } else {
        controlUI02.innerHTML = "";
      }
      var controlUI01 = document.createElement("div");
      controlUI01.innerHTML = `
       <div class="container-fluid">
        <form>
          <div id="boton_ver_tecnologias" class="form-group">
            <a href="javascript:;"  data-toggle="tooltip" title="Seleccionar tipo de tecnologia" class=" mt-2 btn btn-sm btn-white" onclick="boton_ver_tecnologias();">
            <i style="color: lightsalmon;font-size:20px" class="fas fa-ethernet"></i>
            </a>
           </div>

           </div>
        </form>
      </div>
      `;
      var controlUI1 = document.createElement("div");
      controlUI1.innerHTML = `
       <div class="container-fluid">
        <form>
          <div id="boton_ver_antenas" class="form-group">
            <a href="javascript:;"  data-toggle="tooltip" title="Ver antenas" class=" mt-2 btn btn-sm btn-white" onclick="boton_ver_antenas();">
            <i style="color: lightsalmon;font-size:20px" class="fas fa-podcast"></i>
            </a>
           </div>
          <div id="dejar_de_ver_antenas" style="display:none" class="form-group">
            <a href="javascript:;" style="width:40px"  data-toggle="tooltip" title="Dejar de ver antenas" class=" mt-2 btn btn-sm btn-white" onclick="dejar_de_ver_antenas();">
              <i id="tachita" style = "font-size:19px" class = "fas fa-times" > </i>
            </a>
           </div>
        </form>
      </div>
      `;
      var controlUI0 = document.createElement("div");
      if (<?= $permiso; ?> >= 2) {
        controlUI0.innerHTML = `
       <div class="container-fluid">
        <form>
          <div id="boton_ver_clientes" class="form-group">
            <a href="javascript:;" data-toggle="tooltip" title="Ver clientes" style="width:40px" class=" mt-2 btn btn-sm btn-white" onclick="boton_ver_clientes();">
            <i style="color: lightsalmon;font-size:20px"  class="fas fa-map-pin"></i>
            </a>
          </div>
        </form>
      </div>
      `;
      } else {
        controlUI0.innerHTML = "";

      }
      var controlUI2 = document.createElement("div");
      if (permiso >= 2) {
        controlUI2.innerHTML = `
       <div class="container-fluid">
        <form>
          <div class="form-group">
           <a id="button_edit_geocercas"  data-toggle="tooltip" title="Editar geocercas" href="javascript:;" class=" mt-2 btn btn-sm btn-white" onclick="modo_edit_geocerca();">
            <i  style="color: lightsalmon;font-size:19px" class="fas fa-draw-polygon"></i>
           </a>
           <div style="display:none;" id="div_edit" >
             <a  href="javascript:;" data-toggle="tooltip" data-placement="bottom" title="Personaliza los colores de tu sig. poligono" class=" mt-2 btn btn-sm btn-white">
              Relleno <input id="color-picker"  value="#276cb8" />
              Linea <input id="color-picker2"  value="#FF0000" />
             </a>
             <a href="javascript:;" data-toggle="tooltip" title="Salir de modo edicion" class=" mt-2 btn btn-sm btn-white" onclick="modo_edit_geocerca();">
              <i id="tachita" style = "font-size:19px" class = "fas fa-times" > </i>
             </a>
           </div>
           </div>
        </form>
      </div>
      `;
      } else {
        controlUI2.innerHTML = "";
      }
      controlDiv.appendChild(controlUI);
      controlDiv.appendChild(controlUI0);
      controlDiv.appendChild(controlUI01);
      controlDiv.appendChild(controlUI02);
      controlDiv.appendChild(controlUI1);
      controlDiv.appendChild(controlUI2);


      // Setup the click event listeners: simply set the map to Chicago.
      controlUI.addEventListener("click", function() {});

      // Create the DIV to hold the control and call the CenterControl()
      // constructor passing in this DIV.

    }

    function CenterControlbotton(controlDiv, map) {
      var controlUIbotton = document.createElement("div");
      controlUIbotton.innerHTML = `
       <div id="card_poligono" style="display:none" class="container-fluid">
        <div class="card mb-2" style="width: 18rem;">
          <div id="nombre_poligono" class="card-header">
            Featured
          </div>
          <ul id="card_body_poligono" class="list-group list-group-flush">
          </ul>
        </div>
      </div>
      `;
      controlDiv.appendChild(controlUIbotton);
    }

    var centerControlDiv = document.createElement("div");
    var centerControl = new CenterControl(centerControlDiv, map);
    centerControlDiv.index = 1;
    map.controls[google.maps.ControlPosition.LEFT_TOP].push(centerControlDiv);

    var centerControlDivbotton = document.createElement("div");
    var centerControlbotton = new CenterControlbotton(centerControlDivbotton, map);
    centerControlDivbotton.index = 1;
    map.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(centerControlDivbotton);



    /* recorrerInsertar(data, map, infowindow) */

    setMapOnAll(map)

  };

  function filtrar() {
    nodo = $("#nodo").val()
    sucursal = $("#sucursal").val()
    $.ajax({
      type: "POST",
      url: "clientes/filter_clientes_map_geocercas",
      data: {
        nodo,
        sucursal
      },
      success: function(response) {

        data = JSON.parse(response)
        deleteMarkers();
        recorrerInsertar(data['data'], map, infowindow)

        setMapOnAll(map)
      }
    });
  }

  function filtrar_tenologia() {
    for (let i = 0; i < tipos_poligono.length; i++) {
      var element = tipos_poligono[i];
      if (element == $("#tecnologia").val()) {
        poligonos[i].setVisible(true)
      } else {
        poligonos[i].setVisible(false)
      }
    }
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
        <p><i class="fas fa-laptop"></i> ${data[i].mac} <i class="fas fa-signal"></i> ${data[i].ip} </p>
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
        setMapOnAll(map)
      }
    }
  }

  function makeInfoWindowEvent(map, infowindow, contentString, marker, infowindow) {
    google.maps.event.addListener(marker, 'click', function() {
      infowindow.setContent(contentString);
      infowindow.open(map, marker);
    });
  }


  function agregarForma(event, jsonString, color_linea, color_relleno) {
    nombre = "";
    descripcion = "";
    if (modo_fullscreen) {
      $.post("clientes/save_geocerca", {
          jsonString,
          "tipo": event.type,
          color_linea,
          color_relleno
        })
        .done(function(response) {
          add_post(response, event);
        });
    } else {
      var alsup = $.confirm({
        theme: 'supervan',
        draggable: false,
        closeIcon: function() {
          event.overlay.setVisible(false)
        },
        animationBounce: 2.5,
        escapeKey: false,
        content: '<label>Nombre :</label><input type="text" class="form-control" id="confirm_nombre_poligono" placeholder="Torre 1 s1"><br><label>Descripcion o notas :</label><br><textarea class="p-5"  name="descripcion_confirm_poligono" id="descripcion_confirm_poligono" rows="3" cols="50">',
        title: '<i class="far fa-question-circle fa-lg icodialog"></i> Agregar',
        buttons: {
          Eliminar: {
            text: '<i class="far fa-trash-alt"></i> Si, agregar',
            action: function() {
              descripcion = $('#descripcion_confirm_poligono').val();
              nombre = $('#confirm_nombre_poligono').val();
              $.post("clientes/save_geocerca", {
                  jsonString,
                  "tipo": event.type,
                  color_linea,
                  color_relleno,
                  "descripcion": descripcion,
                  "nombre": nombre,
                  "tipo_tecnologia": $("#tecnologia").val()
                })
                .done(function(response) {
                  add_post(response, event, nombre, descripcion);
                });
              return false;

            }
          }
        }
      });
    }

    function add_post(response, event, nombre, descripcion) {
      response = JSON.parse(response);
      alsup.close();
      code = response.code;
      if (code == 2) {
        alerta('error', 'Error al agregar la geocerca.');
      } else if (code == 3) {
        alerta('warning', 'No tienes los permisos necesarios.');
      } else {
        tipo_tec = $("#tecnologia").val();
        poligonos.push(event.overlay);
        tipos_poligono.push(parseInt(tipo_tec));
        if (event.type == "circle") {
          google.maps.event.addListener(event.overlay, 'radius_changed', function() {
            newcoordenadas = event.overlay.center.lat() + "," + event.overlay.center.lng() + "/" + event.overlay.getRadius();
            update_poligono(response.id, newcoordenadas)
          })
          google.maps.event.addListener(event.overlay, 'center_changed', function() {
            newcoordenadas = event.overlay.center.lat() + "," + event.overlay.center.lng() + "/" + event.overlay.getRadius();
            update_poligono(response.id, newcoordenadas)
          })
        }
        if (event.type == "polygon") {
          google.maps.event.addListener(event.overlay.getPath(), 'insert_at', function(index, obj) {
            txt = get_data_update(event.overlay);
            update_poligono(response.id, txt)
          });
          google.maps.event.addListener(event.overlay.getPath(), 'set_at', function(index, obj) {
            txt = get_data_update(event.overlay);
            update_poligono(response.id, txt)
          });
          google.maps.event.addListener(event.overlay.getPath(), 'remove_at', function(index, obj) {
            txt = get_data_update(event.overlay);
            update_poligono(response.id, txt)
          });

        }
        google.maps.event.addListener(event.overlay, 'rightclick', function(event2) {
          click_body_fuction(event.type, event.overlay, response.id, nombre, descripcion, event.overlay.strokeColor, event.overlay.fillColor);
          if (modo_edit == true) {
            eliminarPoligono(event.overlay, response.id)
          }
        });
        google.maps.event.addListener(event.overlay, 'click', function(event2) {
          click_body_fuction(event.type, event.overlay, response.id, nombre, descripcion, event.overlay.strokeColor, event.overlay.fillColor);
        });
        alerta('exito', 'Geocerca guardada correctamente');
      }
    }

  }

  function eliminarPoligono(poligono, id) {
    if (!modo_fullscreen) {
      if (permiso >= 3) {
        var alsup = $.confirm({
          theme: 'supervan',
          draggable: false,
          closeIcon: true,
          animationBounce: 2.5,
          escapeKey: false,
          content: 'Esta seguro que desea eliminar la geocerca.',
          title: '<i class="far fa-question-circle fa-lg icodialog"></i> Eliminar',
          buttons: {
            Eliminar: {
              text: '<i class="far fa-trash-alt"></i> Si, eliminar',
              action: function() {
                $.post("clientes/delete_geocerca", {
                    id
                  })
                  .done(function(data) {
                    alsup.close();
                    if (data == 2) {
                      alerta('error', 'Error al eliminar la geocerca.');
                    } else {
                      alerta('exito', 'Geocerca eliminada correctamente');
                      poligono.setMap(null)
                    }
                  });
                return false;

              }
            }
          }
        });
      } else {
        alerta("warning", "No tienes los permisos necesarios para eliminar la Geocerca.");
      }
    }
  }


  function boton_ver_clientes() {
    if (!modo_verclientes) {
      modo_verclientes = true;
    }
    filtrar();
    $('#boton_ver_clientes').hide();
    $('#filtros_geocerca').show();
  }

  function boton_ver_tecnologias() {
    if (!modo_ver_tenologia) {
      modo_ver_tenologia = true;
    }
    $('#boton_ver_tecnologias').hide();
    $('#filtros_geocerca_tenologia').show();
  }

  function cerrar_ver_clientes() {
    if (modo_verclientes) {
      modo_verclientes = false;
    }
    clearMarkers()
    $('#boton_ver_clientes').show();
    $('#filtros_geocerca').hide();
  }

  function cerrar_ver_tegnologia() {
    if (modo_ver_tenologia) {
      modo_ver_tenologia = false;
    }
    $('#boton_ver_tecnologias').show();
    $('#filtros_geocerca_tenologia').hide();
  }

  function boton_ver_antenas() {
    if (!modo_verclientes) {
      markers = [];
    }
    recorrerInsertar_server(server, map, infowindow)
    $("#dejar_de_ver_antenas").show();
    $("#boton_ver_antenas").hide();

  }

  function dejar_de_ver_antenas() {
    deleteMarkers();
    clearMarkers();
    $("#dejar_de_ver_antenas").hide();
    $("#boton_ver_antenas").show();
  }

  function modo_edit_geocerca() {
    if (modo_edit) {
      $("#color-picker").spectrum("destroy");
      $("#color-picker2").spectrum("destroy");
      $('#button_edit_geocercas').show();
      $('#div_edit').hide();
      if (drawingManager) drawingManager.setMap(null)
      modo_edit = false
      for (let i = 0; i < poligonos.length; i++) {
        const element = poligonos[i];
        element.setEditable(false)
      }
    } else {
      $('#button_edit_geocercas').hide();
      $('#div_edit').show();
      $('#color-picker').spectrum({
        type: "color",
      });
      $('#color-picker2').spectrum({
        type: "color",
      });

      if (drawingManager) drawingManager.setMap(map);
      modo_edit = true
    }
  }

  function update_poligono(id, txt) {
    $.post("clientes/update_geocerca", {
      id,
      txt
    })
  }

  function get_data_update(poligono_update) {
    tmp = "";
    poligono_update.getPath().getArray().forEach(function(position, i) {
      coma = i == 0 ? "" : ",";
      tmp += coma + "{lat:" + position.lat() + ",lng:" + position.lng() + "}";
    });
    return tmp;
  }

  function get_color_rgba(color) {
    var rgbaCol = 'rgba(' + parseInt(color.slice(-6, -4), 16) +
      ',' + parseInt(color.slice(-4, -2), 16) +
      ',' + parseInt(color.slice(-2), 16) +
      ',0.5)';
    return rgbaCol;
  }

  function update_color_poligono(id, color, tipo) {
    $.post("clientes/update_color_poligono", {
      tipo,
      color,
      id
    })
  }

  function click_body_fuction(type_c, poligon, id, nombre, descripcion_p, color_linea, color_relleno) {
    txt = "";
    if (type_c == "circle") {
      txt = "Radio ";
      var poligonoMetros = poligon.getRadius();
    } else {
      var poligonoMetros = google.maps.geometry.spherical.computeLength(poligon.getPath());
    }
    poligonoMetros = financial(poligonoMetros);
    $("#card_poligono").show()
    if (nombre == "") {
      nombre = "Sin nombre";
    }
    if ($('#color-picker_edit_relleno').length > 0) {
      $("#color-picker_edit_relleno").spectrum("destroy");
    }
    if ($('#color-picker_edit_linea').length > 0) {
      $("#color-picker_edit_linea").spectrum("destroy");
    }
    $("#picker").spectrum("destroy");
    descripcion = '';
    rgba = get_color_rgba(color_relleno);
    $("#nombre_poligono").css("background-color", rgba);
    $("#nombre_poligono").html("<label>" + nombre + "</label>" + ' <label> (' + txt + poligonoMetros + ' m)</label>')
    $("#card_body_poligono").html('');
    if (descripcion_p != "") {
      descripcion = '<li class="list-group-item">' + descripcion_p + '</li>';
    }
    if (modo_edit) {
      $("#card_body_poligono").html(descripcion + '<li class="list-group-item">\
                                        Relleno <input id="color-picker_edit_relleno"  value="' + color_relleno + '" />\
                                        Linea <input id="color-picker_edit_linea"  value="' + color_linea + '" />\
                                        </li>')
      $('#color-picker_edit_relleno').spectrum({
        type: "color",
        change: function(color) {
          new_color = color.toHexString();
          update_color_poligono(id, new_color, "relleno");
          poligon.setOptions({
            fillColor: new_color
          });
        }
      });
      $('#color-picker_edit_linea').spectrum({
        type: "color",
        change: function(color) {
          new_color = color.toHexString();
          update_color_poligono(id, new_color, "linea");
          poligon.setOptions({
            strokeColor: new_color
          });
        }
      });
    } else {
      $("#card_body_poligono").html(descripcion);
    }
    if (modo_edit == true) {
      for (let i = 0; i < poligonos.length; i++) {
        const element = poligonos[i];
        element.setEditable(false)
      }
      if (!poligon.getEditable()) {
        poligon.setEditable(true);
      }
    }
  }

  function financial(x) {
    return Number.parseFloat(x).toFixed(2);
  }
</script>