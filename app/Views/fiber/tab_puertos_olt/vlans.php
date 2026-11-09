<style>

</style>
<div class="container">
  <div class="row">
    <div class="col-12 col col-sm-10 offset-sm-1 col-md-10 offset-md-1">
      <table id="data-vlans" class="display nowrap table table-striped table-bordered dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%" data-order="[[ 0, &quot;desc&quot; ]]" style="width: 100%;">
        <thead>
          <tr role="row">
            <th class="sorting">id</th>
            <th class="sorting">descripcion</th>
            <th class="sorting">uplinks (Uso)</th>
            <th class="all sorting_disabled" style="max-width: 20px !important; width: 90px;" data-orderable="false"></th>
          </tr>
        </thead>
        <tbody>

        </tbody>

      </table>
    </div>
  </div>
</div>

<script>
  function delete_vlan(id, count) {
    if (count > 0) {
      alerta("warning", `Esta VLAN esta siendo usada por ${count} puerto(s) Uplink(s), por lo que no puede ser eliminada.`);
      return false;
    }
    var confirm_delete_vlan = $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: '<b>¿Está seguro que desea eliminar esta vlan?</b>',
      title: '<i class="fa fa-question-circle fa-lg icodialog"></i> Eliminar VLAN',
      buttons: {
        Eliminar: {
          text: 'Si, Eliminar.', // With spaces and symbols
          action: function() {
            this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
            this.buttons.Eliminar.disable();
            $('.jconfirm-closeIcon').remove();
            $.ajax({
              type: "POST",
              url: "fiber/delete_vlan?olt=<?= $id; ?>",
              data: {
                id
              },
              success: function(response) {
                response = JSON.parse(response);
                if (response.status == 1) {
                  alerta("exito", "Vlan eliminada con exito.");
                } else if (response.status == 2) {
                  if (response.error) {
                    alerta("error", response.error);
                  } else {
                    alerta("warning", "No se realizo la accion, vuelva a intentarlo.");
                  }
                } else {
                  alerta("warning", "No tienes los permisos necesarios.");
                }
                confirm_delete_vlan.close();
              }
            });
            return false;
          }
        }
      }
    });

  }

  $(document).ready(function() {
    configtable('#data-vlans', 'data-vlans');
    vlans = $('#data-vlans').DataTable({
      "ajax": "fiber/get_vlans?id=<?= $id ?>",
      "deferRender": true,
      "aoColumns": [{
          mData: 'id',
          sClass: "dt-center"
        }, {
          mData: 'descripcion',
        },
        {
          mData: 'uso',
        },
        {
          mData: 'tool',
          sClass: "dt-center"
        }
      ],
      initComplete: function(oSettings, json) {

        $("#data-vlans_wrapper div.tooltablas")
          .html('<div class="grupotabla btn-group">\
      <button type="button" class="btn btn-white" Onclick="getmodal(\'fiber/modal_new_vlan?olt=<?= $id; ?>\',\'Nueva Vlan \',\'sm\');"><i class="fas fa-plus"></i> Agregar</button></div>');
      }
    })

  })
  update_vlans = function() {
    vlans.ajax.reload(null, false);
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
  }
</script>