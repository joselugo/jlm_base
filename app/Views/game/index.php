<!doctype html>
<html class="no-js">

<head>
  <meta charset="utf-8">
  <title>100 Yucatecos dijeron</title>
  <link rel="stylesheet" href="public/css/game/animate.css" />
  <link rel="stylesheet" href="public/css/game/play.css" />
  <link rel="stylesheet" href="public/game/TimeCircles.css" />
</head>

<body ng-app="juegoApp" style="background-color:skyblue">
  <style>
    h5 {
      margin-top: 0px;
      margin-bottom: 0px;
    }

    span.grey {
      background: #b19d07;
      border-color: yellow;
      border: 1px solid #000;
      border-radius: 0.8em;
      -moz-border-radius: 0.8em;
      -webkit-border-radius: 0.8em;
      color: #ffffff;
      display: inline-block;
      font-weight: bold;
      line-height: 1.6em;
      margin-right: 15px;
      text-align: center;
      width: 1.6em;
    }
  </style>
  <div id="inicio" ng-controller="JuegoCtrl" style="margin-top: 55px;color:white;text-align: center;">
    <h1>100 Yucatecos dijieron</h1>
  </div>
  <div id="limbo" ng-controller="JuegoCtrl" style="margin-top: 55px;color:white;text-align: center;display:none">
    <div style="width: 100%">
      <h1>Puntuacion Actual</h1>
    </div>
    <div style="width: 100%;height:80vh">
      <div class="row" style="width: 100%;display:flex">
        <div style="width: 50%;text-align: -webkit-center;">
          <h1 id="nombre1">No asignado 1</h1>
          <div id="target1" style="width: 60%;height:150px;background: #3785c7; box-shadow: 2px 2px 5px #464848;;border-radius: 15px;">
            <p id="p1" style="margin: 0px;padding-top: 20px;font-size:80px">0</p>
          </div>
        </div>
        <div style="width: 50%;text-align: -webkit-center;">
          <h1 id="nombre2">No asignado 2</h1>
          <div id="target2" style="width: 60%;height:150px;background: #3785c7; box-shadow: 2px 2px 5px #464848;;border-radius: 15px;">
            <p id="p2" style="margin: 0px;padding-top: 20px;font-size:80px">0</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="tablero" ng-controller="JuegoCtrl" style="margin-top: 25px;display:none">
    <div class="marcador">
      <div id="total_equipo1" class="puntaje-team-1" style="border-radius:20px" ng-class="{ active: turnoTeam == 1 }">0</div>
      <div id="total" class="puntaje-en-juego" style="border-radius: 50px;">0</div>
      <div id="total_equipo2" class="puntaje-team-2" style="border-radius:20px" ng-class="{ active: turnoTeam == 2 }">0</div>
    </div>
    <div class="tablero" style="padding-top:10px;margin-top:25px;border-radius:50px">
      <center>
        <table class="respuestas" id="body">
        </table>
      </center>
    </div>
    <div class="strike" style="margin-top: 10px;" ng-show="strikeActive && !robo">
      <span class="err" style="display: none;" ng-show="strikeCount == 1">X</span>
      <span class="err" style="display: none;" ng-show="strikeCount == 2">X X</span>
      <span class="err" style="display: none;" ng-show="strikeCount == 3">X X X</span>
    </div>
    <div class="strike" ng-show="strikeActive && robo">
      <span></span>
    </div>
  </div>
  <div id="tablero2" ng-controller="JuegoCtrl" style="margin-top: 15px;display:none">
    <div style="width: 100%;text-align:center">
      <h1>Puntuacion Actual</h1>
      <p id="total_acumulado_preguntas_rapidas" style="font-size:100px;margin-top:0px">0</p>
    </div>
    <div class="tablero" style="padding-top:10px;margin-top:25px;border-radius:50px;left: calc((100% - 85%)/2) !important;width: 80% !important;">
      <center>
        <table class="respuestas" id="body2" style="width: 100%;">
          <tr>
            <td style="width: 50%;">
              <div class="container tdrespuesta" data-enable="false" style="display: flex;width: 100%;height:50px">
                <div style="width: 100%;background-color:#6f6f6f;border-radius: 25px 25px 25px 25px;text-align-last: left;padding-left: 10px;padding-top: 5px;">
                  1.
                </div>
              </div>
            </td>
            <td style="width: 50%;">
              <div class="container tdrespuesta1" data-enable="false" style="display: flex;width: 100%;height:50px">
                <div style="width: 100%;background-color:#6f6f6f;border-radius: 25px 25px 25px 25px;text-align-last: left;padding-left: 10px;padding-top: 5px;">1.</div>
              </div>
            </td>
          </tr>
          <tr>
            <td style="width: 50%;">
              <div class="container tdrespuesta" data-enable="false" style="display: flex;width: 100%;height:50px">
                <div style="width: 100%;background-color:#6f6f6f;border-radius: 25px 25px 25px 25px;text-align-last: left;padding-left: 10px;padding-top: 5px;">2.</div>
              </div>
            </td>
            <td style="width: 50%;">
              <div class="container tdrespuesta1" data-enable="false" style="display: flex;width: 100%;height:50px">
                <div style="width: 100%;background-color:#6f6f6f;border-radius: 25px 25px 25px 25px;text-align-last: left;padding-left: 10px;padding-top: 5px;">2.</div>
              </div>
            </td>
          </tr>
          <tr>
            <td style="width: 50%;">
              <div class="container tdrespuesta" data-enable="false" style="display: flex;width: 100%;height:50px">
                <div style="width: 100%;background-color:#6f6f6f;border-radius: 25px 25px 25px 25px;text-align-last: left;padding-left: 10px;padding-top: 5px;">3.</div>
              </div>
            </td>
            <td style="width: 50%;">
              <div class="container tdrespuesta1" data-enable="false" style="display: flex;width: 100%;height:50px">
                <div style="width: 100%;background-color:#6f6f6f;border-radius: 25px 25px 25px 25px;text-align-last: left;padding-left: 10px;padding-top: 5px;">3.</div>
              </div>
            </td>
          </tr>
          <tr>
            <td style="width: 50%;">
              <div class="container tdrespuesta" data-enable="false" style="display: flex;width: 100%;height:50px">
                <div style="width: 100%;background-color:#6f6f6f;border-radius: 25px 25px 25px 25px;text-align-last: left;padding-left: 10px;padding-top: 5px;">4.</div>
              </div>
            </td>
            <td style="width: 50%;">
              <div class="container tdrespuesta1" data-enable="false" style="display: flex;width: 100%;height:50px">
                <div style="width: 100%;background-color:#6f6f6f;border-radius: 25px 25px 25px 25px;text-align-last: left;padding-left: 10px;padding-top: 5px;">4.</div>
              </div>
            </td>
          </tr>
          <tr>
            <td style="width: 50%;">
              <div class="container tdrespuesta" data-enable="false" style="display: flex;width: 100%;height:50px">
                <div style="width: 100%;background-color:#6f6f6f;border-radius: 25px 25px 25px 25px;text-align-last: left;padding-left: 10px;padding-top: 5px;">5.</div>
              </div>
            </td>
            <td style="width: 50%;">
              <div class="container tdrespuesta1" data-enable="false" style="display: flex;width: 100%;height:50px">
                <div style="width: 100%;background-color:#6f6f6f;border-radius: 25px 25px 25px 25px;text-align-last: left;padding-left: 10px;padding-top: 5px;">5.</div>
              </div>
            </td>
          </tr>
          <!--  <tr>
            <td style="width: 50%;">
              <div class="container tdrespuesta" data-enable="false" style="display: flex;width: 100%;height:50px">
                <div style="width: 80%;background-color:slategrey;border-radius: 25px 0px 0px 25px;text-align-last: left;padding-left: 10px;padding-top: 5px;">El vispero</div>
                <div style="width: 20%;background-color: #a548a5;border-radius: 0px 25px 25px 0px;text-align-last: center;padding-top: 5px;">80</div>
              </div>
            </td>
            <td style="width: 50%;">
              <div class="container tdrespuesta" data-enable="false" style="display: flex;width: 100%;height:50px">
                <div style="width: 80%;background-color:slategrey;border-radius: 25px 0px 0px 25px;text-align-last: left;padding-left: 10px;padding-top: 5px;">El vispero</div>
                <div style="width: 20%;background-color: #a548a5;border-radius: 0px 25px 25px 0px;text-align-last: center;padding-top: 5px;">80</div>
              </div>
            </td>
          </tr> -->
        </table>
      </center>
    </div>
    <div class="strike" style="margin-top: 10px;" ng-show="strikeActive && !robo">
      <span class="err2" style="display: none;" ng-show="strikeCount == 1">X</span>
    </div>
    <div class="strike" ng-show="strikeActive && robo">
      <span></span>
    </div>
  </div>
  <div id="cronometro" style="position: absolute;width: 100%;top:0px;display:none">

  </div>
  <script src="http://app.netium.com.mx/herramientas/public/js/confetti.min.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
  <script src="<?= base_url() ?>public/socket.io/socket.io.js"></script>
  <script src="<?= base_url() ?>public/game/TimeCircles.js"></script>
  <script>
    let body = document.getElementById("body");
    let total_equipo1 = document.getElementById("total_equipo1");
    let total_equipo2 = document.getElementById("total_equipo2");
    let span_err = document.getElementsByClassName("err");
    let p1 = document.getElementById("p1");
    let p2 = document.getElementById("p2");
    let target1 = document.getElementById("target1");
    let target2 = document.getElementById("target2");
    let cronometro = document.getElementById("cronometro");
    const cargarSonido = function(fuente) {
      const sonido = document.createElement("audio");
      sonido.src = fuente;
      sonido.setAttribute("preload", "auto");
      sonido.setAttribute("controls", "none");
      sonido.style.display = "none"; // <-- oculto
      document.body.appendChild(sonido);
      return sonido;
    };
    let entrada = cargarSonido("public/game/entrada.mp3");
    let correcto = cargarSonido("public/game/correcto.mp3");
    let incorrecto = cargarSonido("public/game/incorrecto1.mp3");
    let asignar_puntos = cargarSonido("public/game/asignar_puntos.mpeg");
    let inicio_game = cargarSonido("public/game/inicio.mpeg");
    let a_jugar = cargarSonido("public/game/a_jugar.mp3");
    const socket = io('ws://157.245.129.70:2001', {
      query: {
        token: "game"
      }
    });
    socket.on("destapar", (data) => {
      correcto.currentTime = .8;
      correcto.play();
      respuestatxt = atob(data.respuesta)
      if ($(".respuesta_" + data.idrespuesta).length > 0) {
        var campo = document.getElementsByClassName("respuesta_" + data.idrespuesta);
        setTimeout(function() {
          for (let i = 0; i < campo.length; i++) {
            campo[i].innerHTML = `
              <td>${data.key}. ${respuestatxt}</td>
              <td>${data.puntos}</td>
          `;
          }
        }, 500);
      }
      $("#total").html(data.total)
    })
    socket.on("add_puntos", (data) => {
      if (data.equipo == 1) {
        parpadeo(total_equipo1, "#000")
      } else {
        parpadeo(total_equipo2, "#000")
      }
      asignar_puntos.play();
      setTimeout(function() {
        $("#total").html("0")
        $("#total_equipo1").html(data.total_equipo1)
        $("#total_equipo2").html(data.total_equipo2)
        $("#p1").html(data.total_equipo1)
        $("#p2").html(data.total_equipo2)

      }, 500)
    })
    socket.on("atras", (data) => {
      $("#inicio").hide()
      $("#tablero").hide()
      $("#limbo").show()
    })
    socket.on("reinicio_game", (data) => {
      nombre = data.nombre;
      equipo = data.equipo;
      $("#nombre" + equipo).html(nombre);
      $("#p" + equipo).html("0");
      $("#total_equipo" + equipo).html("0");
    })
    socket.on("ganar", (data) => {
      ganador = data.ganador
      puntos = data.puntos
      idganador = data.idganador
      entrada.play();
      console.log(data);
      confetti.start(10000);
      if (idganador == 1) {
        parpadeo(target1, "#3785c7");
      } else if (idganador == 2) {
        parpadeo(target2, "#3785c7");
      } else {
        parpadeo(target1, "#3785c7");
        parpadeo(target2, "#3785c7");
      }
      setInterval(function() {
        confetti.stop();
      }, 33000);
    })
    socket.on("error_game", (data) => {
      incorrecto.play()
      err = data.err
      if (err > 2) {
        err = 3;
      }
      for (let i = 0; i < span_err.length; i++) {
        if (err == i) {
          span_err[i].style.display = "block"
        } else {
          span_err[i].style.display = "none"
        }
      }
      setTimeout(function() {
        for (let i = 0; i < span_err.length; i++) {
          span_err[i].style.display = "none"
        }
      }, 2000);
    })

    function parpadeo(e, color_normal) {
      setTimeout(function() {
        e.style.background = "#71552b";
      }, 500);
      setTimeout(function() {
        e.style.background = color_normal;
      }, 800);
      setTimeout(function() {
        e.style.background = "#71552b";
      }, 1100);
      setTimeout(function() {
        e.style.background = color_normal;
      }, 1400);
      setTimeout(function() {
        e.style.background = "#71552b";
      }, 1700);
      setTimeout(function() {
        e.style.background = color_normal;
        $("#inicio").hide()
        $("#tablero").hide()
        $("#limbo").show()
      }, 2500);
    }

    socket.on("modo_preguntas_rapidas", (data) => {
      console.log(data);
      $("#inicio").hide()
      $("#limbo").hide()
      $("#tablero").hide()
      $("#tablero2").show()
    })
    socket.on("enlazar_respuestas", (data) => {
      console.log(data);
      $("#inicio").hide()
      $("#limbo").show()
      $("#tablero").hide()
      $("#tablero2").hide()
    })
    socket.on("ganar_respuestas_rapidas", (data) => {
      entrada.play();
      confetti.start(10000);
      setInterval(function() {
        confetti.stop();
      }, 33000);
    })
    socket.on("error_game_respuestas_rapidas", (data) => {
      var tdrespuesta = document.getElementsByClassName("tdrespuesta");
      var tdrespuesta1 = document.getElementsByClassName("tdrespuesta1");
      key = data.id
      if (tdrespuesta[key].dataset.enable == "false") {
        tdrespuesta[key].dataset.enable = "true"
        tdrespuesta[key].innerHTML = `
              <div style="width: 80%;background-color:slategrey;border-radius: 25px 0px 0px 25px;text-align-last: left;padding-left: 10px;padding-top: 5px;">${key+1}. ...............</div>
              <div style="width: 20%;background-color: #a548a5;border-radius: 0px 25px 25px 0px;text-align-last: center;padding-top: 5px;">0</div>
              `;
      } else {
        tdrespuesta1[key].innerHTML = `
              <div style="width: 80%;background-color:slategrey;border-radius: 25px 0px 0px 25px;text-align-last: left;padding-left: 10px;padding-top: 5px;">${key+1}. ...............</div>
              <div style="width: 20%;background-color: #a548a5;border-radius: 0px 25px 25px 0px;text-align-last: center;padding-top: 5px;">0</div>
              `;
      }
      incorrecto.play();
    })
    socket.on("destapar_respuesta_rapida", (data) => {
      correcto.currentTime = .8;
      console.log(data);
      correcto.play();
      var key = data.key
      var tdrespuesta = document.getElementsByClassName("tdrespuesta");
      var tdrespuesta1 = document.getElementsByClassName("tdrespuesta1");
      if (tdrespuesta[key].dataset.enable == "false") {
        tdrespuesta[key].dataset.enable = "true"
        tdrespuesta[key].innerHTML = `
              <div style="width: 80%;background-color:slategrey;border-radius: 25px 0px 0px 25px;text-align-last: left;padding-left: 10px;padding-top: 5px;">${key+1}.${atob(data.respuesta)}</div>
              <div style="width: 20%;background-color: #a548a5;border-radius: 0px 25px 25px 0px;text-align-last: center;padding-top: 5px;">${data.puntos}</div>
              `;
      } else {
        tdrespuesta1[key].innerHTML = `
              <div style="width: 80%;background-color:slategrey;border-radius: 25px 0px 0px 25px;text-align-last: left;padding-left: 10px;padding-top: 5px;">${key+1}.${atob(data.respuesta)}</div>
              <div style="width: 20%;background-color: #a548a5;border-radius: 0px 25px 25px 0px;text-align-last: center;padding-top: 5px;">${data.puntos}</div>
              `;
      }
      $("#total_acumulado_preguntas_rapidas").html(data.total);


    })
    socket.on("ir_pregunta", (data) => {
      a_jugar.play();
      $('#total').html("0");
      body_tmp = ""
      data.respuestas.forEach(element => {
        body_tmp += `
        <tr class="respuesta_${element.id}" ng-repeat="respuesta in preguntaActual.respuestas">
            <td colspan="2" style="text-align-last: center;">
              <h5><span class="grey">${element.key}</span></h5>
              </td>
          </tr>
          `;
      });
      body.innerHTML = body_tmp;
      $("#total_equipo1").html(data.total_equipo1)
      $("#total_equipo2").html(data.total_equipo2)
      $("#p1").html(data.total_equipo1)
      $("#p2").html(data.total_equipo2)
      $("#inicio").hide()
      $("#limbo").hide()
      $("#tablero").show()
    })

    function inicio() {
      sala = '100yucatecosDijeron'
      socket.emit('join_room', sala);
      /* entrada.play(); */
    }
    inicio()
    socket.on("inicio_tiempo", (data) => {
      cronometro.innerHTML = "";
      console.log(data);
      console.log("as");
      var div = document.createElement("div");
      div.id = "example";
      div.style.width = "300px";
      div.style.height = "200px";
      div.dataset.timer = data.totalTime;
      cronometro.appendChild(div);
      console.log(cronometro);
      crono = $("#example").TimeCircles({
        time: {
          Days: {
            show: false,
            color: "#C0C8CF",
          },
          Hours: {
            show: false,
            color: "#C0C8CF"
          },
          Minutes: {
            show: false,
            color: "#C0C8CF"
          },
          Seconds: {
            color: "#78c772",
          }
        }
      });
      crono.addListener(function(unit, amount, total) {
        if (total == 0) {
          $("#cronometro").hide()
          crono.destroy();
          crono = null;
          incorrecto.play()
        }
      });
      $("#cronometro").show()
    })
  </script>

</body>

</html>