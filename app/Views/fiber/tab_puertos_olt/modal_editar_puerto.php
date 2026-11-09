<div class="modal-body">
  <div class="container">
    <form id="olt-form" method="POST" action="fiber/save_puerto_olt?tipo=<?= $tipo; ?>&olt=<?= $olt; ?>&if_index=<?= $if_index; ?>&chasis=<?= $chasis; ?>&ranura=<?= $ranura; ?>&puerto=<?= $puerto_s; ?>">
      <div class="row">
        <div class="col-sm-4">
          <div class="card mb-2">
            <div class="card-body " style="text-align-last: center;background: cornsilk;">
              <h2 class="card-title"><?= ucfirst($tipo); ?></h2>
            </div>
          </div>
          <div class="card">
            <div class="card-body" style="text-align-last: center;">
              <h5 class="card-title">Herramientas</h5>
              <p class="card-text">
                <a onclick="change_state(this,<?= $puerto[3] == 2 ? 1 : 2; ?>)" data-toggle="tooltip" data-placement="top" title="<?= $puerto[3] == 2 ? "Habilitar" : "Deshabilitar"; ?>" style="margin: 5px;" class="btn btn-<?= $puerto[3] == 2 ? "success" : "danger"; ?>"><i class="fas fa-power-off"></i></a>
                <a data-toggle="tooltip" data-placement="top" title="Ver clientes" style="margin: 5px;" class="btn btn-info"><i class="far fa-eye"></i></a>
              </p>
            </div>
          </div>
        </div>
        <div class="col-sm-8">
          <div class="card">
            <div class="card-body">
              <div class="form-group">
                <label for="exampleInputEmail1">Descripcion</label>
                <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripcion" value="<?= $puerto[0]; ?>">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Rango minimo (km)</label>
                <input type="text" class="form-control" id="min" name="min" placeholder="<?= $puerto[1]; ?>" value="<?= $puerto[1]; ?>">
              </div>
              <div class="form-group">
                <label for="exampleInputEmail1">Rango maximo (km)</label>
                <input type="text" class="form-control" id="max" name="max" placeholder="<?= $puerto[2]; ?>" value="<?= $puerto[2]; ?>">
              </div>
              <button class="btn btn-lg btn-success btn-block" type="submit">Guardar</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>

</div>


</form>


<script>
  $(function() {

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

  function change_state(e, num) {
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
  }
</script>