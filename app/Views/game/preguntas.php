<div class="container-fluid">
  <div class="row">
    <div class="col-12 col-md-3">
      <div class="widget widget-stats bg-white">
        <div class="stats-icon stats-icon-sm"><i class="fas fa-cloud text-success"></i></div>
        <div class="stats-content">
          <div id="nombre_equipo_1" class="stats-title text-dark">No asignado 1</div>
          <div class="stats-number m-b-2 text-success" id="total_equipo1">0</div>
          <div class="stats-desc"><a onclick="reiniciar(1)" style="cursor: pointer" class="text-success">Reiniciar</a></div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-2"></div>
    <div class="col-12 col-md-2">
      <div class="widget widget-stats bg-white">
        <div class="stats-content">
          <div style="text-align-last: center;" class="stats-title text-dark">Puntos acumulados</div>
          <div style="text-align-last: center;" class="stats-number m-b-2 text-success" id="total">0</div>
          <button id="boton_play" data-toggle="tooltip" data-original-title="Comenzar juego" data-placement="bottom" class="btn btn-warning btn-block mt-2 mb-2" style="border-radius:5px 5px 5px 5px;color:white" onclick="iniciar_preguntas(this);">Enlazar</button>
          <div class="stats-desc">
            <div class="row">
              <div style="text-align-last: left;" class="col-6">
                <a onclick="add_equipo1()" class="text-success"><i class="fas fa-arrow-left text-success"></i></a>
              </div>
              <div class="col-6">
                <a onclick="add_equipo2()" class="text-success"> <i class="fas fa-arrow-right text-success"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-2"></div>
    <div class="col-12 col-md-3">
      <div class="widget widget-stats bg-white">
        <div class="stats-icon stats-icon-sm"><i class="fas fa-cloud text-success"></i></div>
        <div class="stats-content">
          <div id="nombre_equipo_2" class="stats-title text-dark">No asignado 2</div>
          <div class="stats-number m-b-2 text-success" id="total_equipo2">0</div>
          <div class="stats-desc"><a onclick="reiniciar(2)" style="cursor: pointer" class="text-success">Reiniciar</a></div>
        </div>
      </div>
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
      <a class="btn btn-danger btn-lg" data-toggle="tooltip" data-original-title="Error al responder" style="font-size:25px;color:white;cursor:pointer" onclick="error()"><i class="fas fa-times"></i></a>
    </div>
  </div>
</div>
<div class="panel panel-default mt-5" id="tbl_preguntas">
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
  var err = 0;
  var tbody = document.getElementById("tbody");
  $(function() {
    App.setPageTitle('Game preguntas');
    configtable('#data-game-preguntas', 'game_preguntas');

    var operadores = $('#data-rutas').DataTable({
      "ajax": base_url + "game/get_preguntas",
      "deferRender": true,
      "idDataTables": "1",
      "aoColumns": [{
          mData: 'num',
        }, {
          mData: 'pregunta',
          sWidth: '82%'
        },
        {
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
        <a class="btn btn-success" style="color:white" onclick="getmodal(\'game/modal_new_pregunta\',\'Nueva pregunta\',\'md\');"><i class="fas fa-plus"></i> Nuevo</a>\
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
    $.ajax({
      type: "post",
      url: "game/ir_a_pregunta",
      data: {
        id
      },
      success: function(response) {
        response = JSON.parse(response);
        if (response.error) {
          alerta("warning", "La pregunta no tiene ninguna respuesta asignada.");
        } else {
          $("#total").html("0");

          body = "";
          response.respuestas.forEach(respuesta => {
            body += `<tr class="bg-danger">
               <th scope="row">${respuesta.key}</th>
               <td style="width: 60%;">${respuesta.respuesta}</td>
               <td style="width: 20%;">${respuesta.puntos}</td>
               <td style="width: 20%;"><a onclick="destapar(this,'${id}','${respuesta.id}','${respuesta.puntos}','${respuesta.txtbase64}','${respuesta.key}')" class="btn btn-light">Destapar</a></td>
             </tr>`
          });
          tbody.innerHTML = body;
          $("#tbl_tablero").show();
          $("#tbl_preguntas").hide();
          total_equipo1 = parseInt($("#total_equipo1").html());
          total_equipo2 = parseInt($("#total_equipo2").html());
          socket.emit("ir_pregunta", {
            "respuestas": response.respuestas,
            "total_equipo1": total_equipo1,
            "total_equipo2": total_equipo2
          });
          err = 0
          e.innerHTML = "Ya respondida"
          e.classList.remove("btn-info");
          e.classList.add("btn-warning");
        }


      }
    });
  }

  function elegir_pregunta() {
    $("#total").html("0");
    $("#tbl_preguntas").show();
    $("#tbl_tablero").hide();
  }

  function atras() {
    $("#total").html("0");
    $("#tbl_preguntas").show();
    $("#tbl_tablero").hide();
    socket.emit("atras")
  }

  function add_equipo1() {
    tmp_total = parseInt($("#total").html());
    tmp = tmp_total + total_equipo1;
    total_equipo1 = parseInt($("#total_equipo1").html());
    total_equipo2 = parseInt($("#total_equipo2").html());
    $("#total_equipo1").html(tmp);
    $("#total").html("0");
    socket.emit("add_puntos", {
      "equipo": 1,
      "total_equipo1": tmp,
      total_equipo2
    })
    elegir_pregunta()
  }

  function add_equipo2() {
    tmp_total = parseInt($("#total").html());
    tmp = tmp_total + total_equipo2;
    $("#total_equipo2").html(tmp);
    total_equipo2 = parseInt($("#total_equipo2").html());
    total_equipo1 = parseInt($("#total_equipo1").html());
    $("#total").html("0");
    socket.emit("add_puntos", {
      "equipo": 2,
      "total_equipo2": tmp,
      total_equipo1
    })
    elegir_pregunta()
  }

  function iniciar_preguntas(e) {
    e.style.display="none"
    socket.emit("enlazar_respuestas")
  }

  function reiniciar(eq) {
    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      onContentReady: function() {
        $('#nombre_game').focus();
      },
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Esta seguro que deseas reiniciar a este equipo?<br><br><label>Nombre del equipo:</label><br><input type="text" id="nombre_game">',
      title: '<i class="far fa-question-circle fa-lg icodialog"></i> Reiniciar',
      buttons: {
        Eliminar: {
          text: '<i class="fas fa-times icodialog"></i> Si, Reiniciar', // With spaces and symbols
          action: function() {
            nombre_eq = capitalizar($("#nombre_game").val());
            $("#nombre_equipo_" + eq).html(nombre_eq)
            $("#total_equipo" + eq).html("0");
            err = 0;
            socket.emit("reinicio_game", {
              "nombre": nombre_eq,
              "equipo": eq
            })
          }
        }
      }
    });
  }

  function error() {
    socket.emit("error_game", {
      err
    });
    err = err + 1;
  }

  function ganar() {
    console.log("dad");
    total_equipo1 = parseInt($("#total_equipo1").html());
    total_equipo2 = parseInt($("#total_equipo2").html());
    name1 = $("#nombre_equipo_1").html();
    name2 = $("#nombre_equipo_2").html();
    if (total_equipo1 > total_equipo2) {
      ganador = name1;
      idganador = 1;
      puntos = total_equipo1;
    } else if (total_equipo1 < total_equipo2) {
      idganador = 2;
      ganador = name2;
      puntos = total_equipo2;
    } else {
      idganador = 0;
      ganador = name1 + " y " + name2;
      $("#nombre_equipo_1").html();
      puntos = total_equipo2;
    }
    socket.emit("ganar", {
      ganador,
      puntos,
      idganador
    });
  }

  function capitalizar(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }

  function destapar(e, idpregunta, idrespuesta, puntos, respuesta, key) {
    e.style.display = "none";
    key = parseInt(key);
    total_equipo1 = parseInt($("#total_equipo1").html());
    total_equipo2 = parseInt($("#total_equipo2").html());
    e.parentElement.parentElement.className = "bg-success";
    tmp_total = parseInt($("#total").html());
    puntos = parseInt(puntos);
    total_tmp = tmp_total + puntos;
    $("#total").html(total_tmp);
    socket.emit("destapar", {
      "total": total_tmp,
      respuesta,
      puntos,
      key,
      idrespuesta
    });
  };
</script>