<div class="modal-body">
  <div class="container">
    <form id="olt-form" method="POST" action="fiber/save_puerto_olt?tipo=<?= $tipo; ?>&olt=<?= $olt; ?>&if_index=<?= $if_index; ?>&chasis=<?= $chasis; ?>&ranura=<?= $ranura; ?>&puerto=<?= $puerto_s; ?>">
      <div class="row">
        <div class="col-sm-4">
          <div class="card mb-2">
            <div class="card-body " style="text-align-last: center;background: cornsilk;">
              <h2 class="card-title"><?= ucfirst($tipo); ?></h2>
              <p class="mb-1" style="font-size:11px;color:burlywood;">Ultima modificacion hace :</p>
              <p style="font-size:9px;color:burlywood;"><?= $puerto[4]; ?></p>
            </div>
          </div>
          <div class="card">
            <div class="card-body" style="text-align-last: center;">
              <h5 class="card-title">Herramientas</h5>
              <p class="card-text">
                <button onclick="change_state(this,<?= $puerto[3] == 2 ? 1 : 2; ?>)" data-toggle="tooltip" data-placement="top" title="<?= $puerto[3] == 2 ? "Habilitar" : "Deshabilitar"; ?>" style="margin: 5px;" class="btn btn-<?= $puerto[3] == 2 ? "success" : "danger"; ?>"><i class="fas fa-power-off"></i></button>
                <button data-toggle="tooltip" data-placement="top" title="Ver clientes" style="margin: 5px;" class="btn btn-info"><i class="far fa-eye"></i></button>
              </p>
            </div>
          </div>
          <?php if ($avaible_vlan) : ?>
            <div class="card  mt-2">
              <button onclick="agregar_vlan()" class="btn btn-light btn-sm" data-toggle="tooltip" data-placement="top" title="Agregar Vlan" style="position: absolute;right:5px;top:5px"><i style="color:slategrey" class="fas fa-plus"></i></button>
              <div class="card-body" style="text-align-last: center;">
                <h5 class="card-title">Vlan asignadas</h5>
                <table class="table">
                  <thead>
                    <tr>
                      <th scope="col">Vlan ID</th>
                      <th scope="col">Accion</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($vlans as $dato) : ?>
                      <tr>
                        <th scope="row"><?= $dato["id_vlan"]; ?></th>
                        <td><button onclick="eliminar_vlan('<?= $dato['id_vlan']; ?>')" data-toggle="tooltip" data-placement="right" title="Eliminar" class="btn btn-danger btn-sm"><i class="fas fa-times"></i></button></td>
                      </tr>
                    <?php endforeach; ?>

                  </tbody>
                </table>
              </div>
            </div>
          <?php endif; ?>
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


    $('#modaltmp').modal('show');


  })

  function change_state(e, num) {
    var slot_admin_confirm = $.confirm({
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