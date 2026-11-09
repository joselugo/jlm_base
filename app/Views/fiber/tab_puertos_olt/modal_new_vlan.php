<div class="modal-body">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <form id="new_vlan" action="fiber/new_vlan?olt=<?= $olt; ?>" method="POST">
          <div class="form-group">
            <label for="name">ID :</label>
            <input class="form-control" type="text" name="nombre" id="nombre" placeholder="100">
          </div>
        <!--   <div class="form-group">
            <label for="name">Descripcion :</label>
            <input class="form-control" type="text" name="descripcion" id="descripcion" placeholder="VLAN-EMPRESARIAL">
          </div> -->
          <button class="mt-1 btn btn-success btn-block" type="submit">Agregar</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
  $('#modaltmp').modal('show');
  $('#new_vlan').ajaxForm({
    success: function(data) {
      data=JSON.parse(data);
      $('#gritter-notice-wrapper').remove();
      if (data.status == 1) {
        alerta('exito', 'Vlan agregado correctamente');
        update_vlans();
      } else if (data.status == 2) {
        if (data.error) {
          alerta('warning', data.error);
          update_vlans();
        }else{
          alerta('error', 'No se agrego la vlan, vuelve a intentarlo.');
        }
      } else {
        alerta('warning', 'No tienes permisos para realizar esta accion.');
      }
    },
    beforeSubmit: function() {
      $('#modaltmp').modal('hide');
    },
    error: function(response) {
      $('#gritter-notice-wrapper').remove();
      alerta('error500', "error");
    }
  })
</script>