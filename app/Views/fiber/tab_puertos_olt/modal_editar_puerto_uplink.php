<div class="modal-body">
  <div class="container">
    <form id="olt-form" method="POST" action="fiber/save_puerto_olt?tipo=<?= $tipo; ?>&olt=<?= $olt; ?>&if_index=<?= $if_index; ?>&chasis=<?= $chasis; ?>&ranura=<?= $ranura; ?>&puerto=<?= $puerto_s; ?>">
      <div class="row">
        <div class="col-sm-12">
          <div class="card text-center">
            <div class="card-header">
              <h4><?= ucfirst($tipo); ?></h4>
            </div>
            <div class="card-body">
              <h5 class="card-title">Herramientas</h5>
              <a onclick="change_state(this,<?= $puerto[0] == 2 ? 1 : 2; ?>)" data-toggle="tooltip" data-placement="top" title="<?= $puerto[0] == 2 ? "Habilitar" : "Deshabilitar"; ?>" style="margin: 5px;" class="btn btn-<?= $puerto[0] == 2 ? "success" : "danger"; ?>"><i class="fas fa-power-off"></i></a>
            </div>
            <div class="card-footer text-muted">
              <h6>Ultima modificacion hace :</h6>
              <?= $puerto[1]; ?>
            </div>
          </div>
          <?php if ($avaible_vlan) : ?>
            <div class="card  mt-2">
              <div id="vlan">
                <button id="add_vlan" type="button" data-toggle="popover" class="btn btn-light btn-sm" data-toggle="tooltip" data-placement="top" title="Agregar Vlan" style="position: absolute;right:5px;top:5px"><i style="color:slategrey" class="fas fa-plus"></i></button>
                <div class="card-body" style="text-align-last: center;">
                  <h5 class="card-title">Vlan asignadas</h5>
                  <table class="table" id="tabla_port_vlan">
                    <thead>
                      <tr>
                        <th scope="col">Vlan ID</th>
                        <th scope="col">Accion</th>
                      </tr>
                    </thead>
                    <tbody id="body">
                      <?php foreach ($vlans as $dato) : ?>
                        <tr>
                          <th scope="row"><?= $dato["vlan_id"]; ?></th>
                          <td><button type="button" onclick="eliminar_vlan(this,'<?= $dato["vlan_id"]; ?>')" data-toggle="tooltip" data-placement="right" title="Eliminar" class="btn btn-danger btn-sm"><i class="fas fa-times"></i></button></td>
                        </tr>
                      <?php endforeach; ?>

                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          <?php endif; ?>

        </div>
      </div>
    </form>
  </div>

</div>


</form>

<script>
  $(function() {
    $('#add_vlan').popover({
      container: 'body',
      placement: 'right',
      html: true,
      content: `<div style=\"width:100%\">
                  <select style="width: 100%" id="select_vlan">
                   <option value="" selected="selected">Cargando...</option>
                  </select>
                  <button type="button" onclick="agregar_vlan()" class="btn btn-success btn-sm mt-1">Agregar</button>
                </div>`
    })
    $('#add_vlan').on('shown.bs.popover', function() {
      $('.bs-popover-right').css('z-index', 9999);
      $('#select_vlan').select2().on('select2:open', function(e) {
        $('.select2-container--open').css('z-index', 9999);
      });

      $.ajax({
        type: "POST",
        url: "fiber/list_vlan",
        data: {
          olt: <?= $olt; ?>,
          chasis: <?= $chasis; ?>,
          ranura: <?= $ranura; ?>,
          puerto: <?= $puerto_s; ?>
        },
        success: function(response) {


          response = JSON.parse(response);
          html = "";
          response.forEach(element => {
            existe = false;
            $("#tabla_port_vlan tbody tr").each(function(index, value) {
              if (value.childNodes[1].innerText == element) {
                existe = true;
              }
            })
            if (!existe) {
              html += `<option value="${element}" selected="selected">${element}</option>`;
            }
          });
          $('#select_vlan').html(html);
        }
      });
    })
    $('#olt-form').ajaxForm({
      success: function(data) {
        $('#gritter-notice-wrapper').remove();
        if (data == 2) {
          alerta('error', "No se pudo guardar los datos.");
        } else if (data == 1) {
          alerta('exito', "Guardado con exito.");
          /* update_puertos(1); */
        } else {
          alerta('warning', "No tienes los permisos necesarios.");

        }
      },
      beforeSubmit: function() {
        $('#modaltmp').modal('hide');
        alerta('loader', '');
      },
      error: function(response) {
        $('#gritter-notice-wrapper').remove();
        alerta('error500', "error");
      }
    })

    $('#password').password();
    $('#modaltmp').modal('show');


  })

  function agregar_vlan() {
    vlan = $('#select_vlan').val();
    if (vlan != "") {
      var add_vlan = $.confirm({
        theme: 'supervan',
        draggable: false,
        closeIcon: true,
        animationBounce: 2.5,
        escapeKey: false,
        content: '<b>¿Está seguro que deseas agregar esta vlan a este puerto?</b>',
        title: '<i class="fa fa-question-circle fa-lg icodialog"></i> Agregar Vlan',
        buttons: {
          Eliminar: {
            text: 'Si, agregar', // With spaces and symbols
            action: function() {
              this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
              this.buttons.Eliminar.disable();
              $('.jconfirm-closeIcon').remove();
              $.ajax({
                type: "POST",
                url: "fiber/add_vlan_puerto?tipo=<?= $tipo; ?>&olt=<?= $olt; ?>&if_index=<?= $if_index; ?>&chasis=<?= $chasis; ?>&ranura=<?= $ranura; ?>&puerto=<?= $puerto_s; ?>",
                data: {
                  vlan
                },
                success: function(response) {
                  $("[data-toggle='tooltip']").tooltip('hide');
                  if (response == 2) {
                    alerta('error', "No se pudo realizar la accion.");
                  } else if (response == 1) {
                    alerta('exito', "Accion realizada con exito.");
                    html = `
                        <tr>
                          <th scope="row">${vlan}</th>
                          <td><button onclick="eliminar_vlan(this,'${vlan}')" data-toggle="tooltip" data-placement="right" title="Eliminar" class="btn btn-danger btn-sm"><i class="fas fa-times"></i></button></td>
                        </tr>`;
                    $('#body').append(html);
                    divs = $(".ip_<?= $chasis; ?>_<?= $ranura; ?>_<?= $puerto_s; ?> div");
                    if (divs.length == 0) {
                      $(".ip_<?= $chasis; ?>_<?= $ranura; ?>_<?= $puerto_s; ?>").css({
                        "color:white;font-size": "11px",
                        "margin-left": "2px",
                        "margin-top": "1px",
                        "margin-bottom": "1px",
                        "margin-right": "2px",
                        "width": "34px",
                        "padding-bottom": "0px",
                        "padding-left": "0px",
                        "padding-right": "0px",
                        "border-left-width": "0px",
                        "border-right-width": "0px",
                        "border-bottom-width": "0px",
                        "padding-top": "2px"
                      });
                    }
                    divs.each(function(index, value) {
                      value.style.borderRadius = "0px 0px 0px 0px";
                    })
                    $(".ip_<?= $chasis; ?>_<?= $ranura; ?>_<?= $puerto_s; ?>").append(`<div style="background: yellow; color: darkgoldenrod; font-weight: 400;border-radius: 0px 0px 3px 3px;margin-bottom:1px !important" class="mb-0">${vlan}</div>`);
                  } else {
                    alerta('warning', "No tienes los permisos necesarios.");
                  }
                  add_vlan.close()
                }
              });
              return false;
            }
          }
        }
      });
    } else {
      alerta("warning", "Selecciona una vlan valida.");
    }
  }

  function eliminar_vlan(e, id) {
    var eliminar_vlan = $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: '<b>¿Está seguro que deseas eliminar esta vlan de este puerto?</b>',
      title: '<i class="fa fa-question-circle fa-lg icodialog"></i> Eliminar Vlan',
      buttons: {
        Eliminar: {
          text: 'Si', // With spaces and symbols
          action: function() {
            this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
            this.buttons.Eliminar.disable();
            $('.jconfirm-closeIcon').remove();
            $.ajax({
              type: "POST",
              url: "fiber/eliminar_vlan_puerto?tipo=<?= $tipo; ?>&olt=<?= $olt; ?>&if_index=<?= $if_index; ?>&chasis=<?= $chasis; ?>&ranura=<?= $ranura; ?>&puerto=<?= $puerto_s; ?>",
              data: {
                id
              },
              success: function(response) {
                if (response == 2) {
                  alerta('error', "No se pudo realizar la accion.");
                } else if (response == 1) {
                  alerta('exito', "Accion realizada con exito.");
                  e.parentElement.parentElement.remove();
                  divs = $(".ip_<?= $chasis; ?>_<?= $ranura; ?>_<?= $puerto_s; ?> div");
                  console.log(divs.length);
                  if (divs.length == 1) {
                    $(".ip_<?= $chasis; ?>_<?= $ranura; ?>_<?= $puerto_s; ?>").css({
                      "color": "white",
                      "font-size": "11px",
                      "margin-bottom": "0px",
                      "margin-top": "0px",
                      "margin-left": "0px",
                      "margin-right": "0px",
                      "padding": "5px 10px 5px 10px",
                      "border-left-width": "1px",
                      "border-right-width": "1px",
                      "border-bottom-width": "1px;",
                      "border-top-width": "1px",
                      "width": "34px"
                    });
                  }

                  divs.each(function(index, value) {
                    if (value.innerText == id) {

                      if (divs.length == index + 1 && divs.length != 1) {
                        divs[index - 1].style.borderRadius = "0px 0px 3px 3px";
                      }
                      value.remove();
                    }
                  })

                } else {
                  alerta('warning', "No tienes los permisos necesarios.");
                }
                eliminar_vlan.close();
              }
            });
            return false;
          }
        }
      }
    });
  }

  function change_state(e, num) {
    var cambiar_estado = $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: '<b>¿Está seguro que desea hacer esta accion?</b>',
      title: '<i class="fa fa-question-circle fa-lg icodialog"></i> Habilitar / Deshabilitar',
      buttons: {
        Eliminar: {
          text: 'Si', // With spaces and symbols
          action: function() {
            this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
            this.buttons.Eliminar.disable();
            $('.jconfirm-closeIcon').remove();
            $.ajax({
              type: "POST",
              url: "fiber/change_status_port?tipo=<?= $tipo; ?>&olt=<?= $olt; ?>&if_index=<?= $if_index; ?>&chasis=<?= $chasis; ?>&ranura=<?= $ranura; ?>&puerto=<?= $puerto_s; ?>",
              data: {
                num
              },
              success: function(response) {
                if (response == 2) {
                  alerta('error', "No se pudo realizar la accion.");
                } else if (response == 1) {
                  ip_a = document.getElementsByClassName("ip_<?= $chasis; ?>_<?= $ranura; ?>_<?= $puerto_s; ?>");
                  boleano = e.classList.contains("btn-success");
                  if (!boleano) {
                    e.dataset.originalTitle = "Habilitar";
                    e.classList.remove("btn-danger");
                    e.classList.add("btn-success");;
                    for (let i = 0; i < ip_a.length; i++) {
                      ip_a[i].classList.remove("btn-success");
                      ip_a[i].classList.add("btn-danger");
                    }
                  } else {
                    e.classList.remove("btn-success");
                    e.classList.add("btn-danger");
                    for (let i = 0; i < ip_a.length; i++) {
                      ip_a[i].classList.remove("btn-danger");
                      ip_a[i].classList.add("btn-success");
                    }
                    e.dataset.originalTitle = "Deshabilita";

                  }
                  alerta('exito', "Accion realizada con exito.");
                } else {
                  alerta('warning', "No tienes los permisos necesarios.");
                }
              }
            });
            return false;
          }
        }
      }
    });

  }
</script>