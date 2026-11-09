<div class="container-fluid">
  <div class="row">
    <div class="col-12 col-md-3">
    </div>
    <div class="col-12 col-md-2"></div>
    <div class="col-12 col-md-2">
      <div class="widget widget-stats bg-white">
        <div class="stats-content">
          <div style="text-align-last: center;" class="stats-title text-dark">Puntos acumulados</div>
          <div style="text-align-last: center;" class="stats-number m-b-2 text-success" id="total">0</div>
          <div class="stats-desc">
            <div class="row">
              <div class="col-md-12 col-12" style="text-align-last: center;">
                <label style="color:black">Puntos para ganar:</label>
                <input type="text" class="form-control" id="puntosganar" placeholder="180" value="180">
              </div>
              <div class="col-md-12 col-12" style="text-align-last: center;">
                <label style="color:black">Tiempo (s):</label>
                <input type="text" class="form-control" id="cuenta_atras" placeholder="23" value="23">
              </div>
              <div class="col-md-12 col-12" style="text-align-last: center;">
                <button id="boton_play" data-toggle="tooltip" data-original-title="Comenzar juego" data-placement="bottom" class="btn btn-warning btn-block mt-2" style="border-radius:5px 5px 5px 5px;color:white" onclick="iniciar_preguntas_rapidas();">Enlazar</button>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-2"></div>
    <div class="col-12 col-md-3">
    </div>
  </div>
</div>
<div class="container" id="tbl_tablero" style="display:none">
  <div class="row">
    <div class="col-12 col-md-3 mx-auto" style="align-self: center;text-align-last: center;">
      <!-- <a class="btn btn-warning btn-lg" data-toggle="tooltip" data-original-title="Festejar ganador" style="font-size:25px;color:white;cursor:pointer" onclick="ganar()"><i class="fas fa-medal"></i></a> -->
    </div>
    <div class="col-12 col-md-6">
      <table class="table">
        <thead>
          <tr>
            <th scope="col-2"></th>
            <th scope="col-6">Respuesta</th>
            <th scope="col-2">Pts</th>
            <th scope="col-2">accion</th>
          </tr>
        </thead>
        <tbody id="tbody">
          <tr class="bg-danger">>
            <th scope="row">1</th>
            <td style="width: 60%;">Mark</td>
            <td style="width: 20%;">15</td>
            <td style="width: 20%;"><a class="btn btn-light">Destapar</a></td>
          </tr>
          <tr class="bg-danger"> >
            <th scope="row">2</th>
            <td>Jacob</td>
            <td>52</td>
            <td><a class="btn btn-light">Destapar</a></td>
          </tr>
          <tr class="bg-success">>
            <th scope="row">3</th>
            <td>Larry</td>
            <td>10</td>
            <td><a class="btn btn-light">Destapar</a></td>
          </tr>
        </tbody>
      </table>
      <div style="text-align-last: end;"><a class="btn btn-light btn-sm" onclick="atras()"> <i class="fas fa-arrow-left text-success"></i> Elegir pregunta</a></div>
    </div>
    <div class="col-12 col-md-3 mx-auto mt-2 mb-3" style="align-self: center;text-align-last: center;">
      <a class="btn btn-danger btn-lg" id="tache" data-id="" data-toggle="tooltip" data-original-title="Error al responder" style="font-size:25px;color:white;cursor:pointer" onclick="error(this)"><i class="fas fa-times"></i></a>
    </div>
  </div>
</div>
<div class="panel panel-default mt-2" id="tbl_preguntas">
  <div class="panel-heading">
    <h4 class="panel-title"><b>LISTA DE PREGUNTAS</b></h4>
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload" onclick="updatepreguntas()"><i class="fas fa-sync-alt"></i></a>
    </div>
  </div>
  <div class="panel-body border-panel">

    <table id="data-rutas" class="display nowrap table table-striped table-bordered dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%" role="grid" aria-describedby="data-operadores_info" style="width: 100%;">
      <thead>
        <tr role="row">
          <th class="all sorting_asc" tabindex="0" aria-controls="data-operadores" rowspan="1" colspan="1" data-column-index="0" style="width: 20px;" aria-label="ID: Activar para ordenar la columna de manera descendente" aria-sort="ascending">ID</th>
          <th class="sorting" tabindex="0" aria-controls="data-operadores" rowspan="1" colspan="1" data-column-index="1" style="width: 0px; display: none;" aria-label="Nombre: Activar para ordenar la columna de manera ascendente">Pregunta</th>
          <th class="sorting" tabindex="0" aria-controls="data-operadores" rowspan="1" colspan="1" data-column-index="1" style="width: 0px; display: none;" aria-label="Nombre: Activar para ordenar la columna de manera ascendente">ir</th>
          <th class="all text-center sorting_disabled" data-orderable="false" rowspan="1" colspan="1" data-column-index="6" style="width: 36px;" aria-label=""></th>
        </tr>
      </thead>
    </table>
    <a class="btn btn-warning btn-lg" data-toggle="tooltip" data-original-title="Festejar ganador" style="font-size:25px;color:white;cursor:pointer" onclick="ganar()"><i class="fas fa-medal"></i></a>
  </div>
</div>
<script>
  modo_preguntas_rapidas = false;
  if (typeof time_game !== "undefined") {
    clearTimeout(time_game);
  }

  var err = 0;
  var tbody = document.getElementById("tbody");
  var boton_play = document.getElementById("boton_play");
  var tache = document.getElementById("tache");
  $(function() {
    App.setPageTitle('Game preguntas');
    configtable('#data-game-preguntas', 'game_preguntas');

    var operadores = $('#data-rutas').DataTable({
      "ajax": base_url + "game/get_preguntas_rapidas",
      "deferRender": true,
      "idDataTables": "1",
      "aoColumns": [{
          mData: 'num',
        }, {
          mData: 'pregunta',
          sWidth: '82%'
        }, {
          mData: 'ir',
        },
        {
          mData: 'tool',
          className: 'priority'
        }


      ],
      initComplete: function(oSettings, json) {
        $("#data-rutas_wrapper .dt-buttons")
          .append('<div class="btn-group">\
        <a class="btn btn-success" style="border-radius:0px 5px 5px 0px;color:white" onclick="getmodal(\'game/modal_new_pregunta?tipo=1\',\'Nueva pregunta\',\'md\');"><i class="fas fa-plus"></i> Nuevo</a>\
      </div>');
      }
    }).on('draw', function() {
      $('img[data-name]').initial({
        height: 64,
        width: 64,
        charCount: 2,
        fontSize: 24,
        fontWeight: 600
      });

    });

    updatepreguntas = function() {
      operadores.ajax.reload(null, false);
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
    }

  })

  function eliminar_pregunta(id) {

    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Estas seguro de querer eliminar esta pregunta?',
      title: '<i class="fas fa-question-circle fa-lg icodialog"></i> Eliminar pregunta',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt icodialog"></i> Eliminar pregunta',
          action: function() {
            $.post(base_url + 'game/delete_pregunta', {
              id
            }, function(data) {
              if (data == "1") {
                alerta('exito', 'Datos eliminado con exito');
                updatepreguntas()
              } else {
                alerta('error', 'Error al actualizar datos')
              }
            })

          }
        },
        Cancelar: {}
      }
    });
  }

  function ir_pregunta(e, id, key) {
    rapida = "rapida";
    $.ajax({
      type: "post",
      url: "game/ir_a_pregunta",
      data: {
        id,
        key,
        rapida
      },
      success: function(response) {
        response = JSON.parse(response);
        if (response.error) {
          alerta("warning", "La pregunta no tiene ninguna respuesta asignada.");
        } else {
          tache.dataset.id = key;
          body = "";
          response.respuestas.forEach(respuesta => {
            body += `<tr class="bg-danger">
               <th scope="row">${respuesta.key}</th>
               <td style="width: 60%;">${respuesta.respuesta}</td>
               <td style="width: 20%;">${respuesta.puntos}</td>
               <td style="width: 20%;"><button onclick="destapar(this,'${id}','${respuesta.id}','${respuesta.puntos}','${respuesta.txtbase64}','${response.key}')" class="btn btn-light btn_destapar">Destapar</button></td>
             </tr>`
          });
          tbody.innerHTML = body;
          $("#tbl_tablero").show();
          $("#tbl_preguntas").hide();
          total_equipo1 = parseInt($("#total_equipo1").html());
          total_equipo2 = parseInt($("#total_equipo2").html());
          err = 0
          e.innerHTML = "Ya respondida"
          e.classList.remove("btn-info");
          e.classList.add("btn-warning");
        }


      }
    });
  }

  function elegir_pregunta() {
    $("#tbl_preguntas").show();
    $("#tbl_tablero").hide();
  }

  function atras() {
    $("#tbl_preguntas").show();
    $("#tbl_tablero").hide();
  }


  function error(e) {
    id_pregunta = parseInt(e.dataset.id)-1;
    socket.emit("error_game_respuestas_rapidas", {
      "id": id_pregunta
    });
    atras();
  }

  function ganar() {
    socket.emit("ganar_respuestas_rapidas");
  }

  function capitalizar(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }

  function iniciar_preguntas_rapidas() {

    if (modo_preguntas_rapidas == false) {
      var preguntas_list = document.getElementsByClassName("count_respuestas");
      num_preguntas = preguntas_list.length;
      if (num_preguntas == 0) {
        alerta("warning", "Imposible iniciar este modo por que no se cuenta con ninguna pregunta.");
        return false;
      }
      modo_preguntas_rapidas = true;
      console.log("envi");
      socket.emit("modo_preguntas_rapidas", {
        num_preguntas
      });
      boton_play.innerHTML = `<i class="fas fa-play"></i>`;
    } else {
      totalTime = $("#cuenta_atras").val();
      socket.emit("inicio_tiempo", {
        totalTime
      });
      boton_play.disabled = true;
      updateClock();
    }
  }

  function destapar(e, idpregunta, idrespuesta, puntos, respuesta, key) {
    e.style.display = "none";
    var botones_destapar = document.getElementsByClassName("btn_destapar");
    console.log(botones_destapar);
    for (let i = 0; i < botones_destapar.length; i++) {
      botones_destapar[i].disabled = true;
    }
    key = parseInt(key) - 1;
    total_equipo1 = parseInt($("#total_equipo1").html());
    total_equipo2 = parseInt($("#total_equipo2").html());
    e.parentElement.parentElement.className = "bg-success";
    tmp_total = parseInt($("#total").html());
    puntos = parseInt(puntos);
    total_tmp = tmp_total + puntos;
    $("#total").html(total_tmp);
    socket.emit("destapar_respuesta_rapida", {
      "total": total_tmp,
      respuesta,
      puntos,
      key,
      idrespuesta
    });
    atras();
  };


  function updateClock() {
    boton_play.innerHTML = totalTime;
    if (totalTime == 0) {
      boton_play.disabled = false;
      boton_play.innerHTML = `<i class="fas fa-play"></i>`;
      tiempo_agotado();
    } else {
      totalTime -= 1;
      time_game = setTimeout("updateClock()", 1000);
    }
  }

  function tiempo_agotado() {
    socket.emit("tiempo_agotado");
  }
</script>