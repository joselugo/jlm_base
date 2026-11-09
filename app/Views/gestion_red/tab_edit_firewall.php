<table id="data-router-firewall" class="display nowrap table table-striped table-bordered table-hover" cellspacing="0"
  width="100%">
  <thead>
    <tr>
      <th>#</th>
      <th>Nombre/Host</th>
      <th>Tipo</th>
      <th>IP/Regexp</th>
      <th>Estado</th>
      <th class="all" style="max-width:20px !important" data-orderable="false"></th>
    </tr>
  </thead>
</table>

<script>
var fire;

function delete_firewall(id) {

  $.confirm({
    theme: 'supervan',
    draggable: false,
    closeIcon: true,
    animationBounce: 2.5,
    escapeKey: false,
    content: 'Está seguro que desea eliminar la regla firewall?',
    title: '<i class="fa fa-question-circle fa-lg icodialog"></i> Eliminar Regla',
    buttons: {
      Eliminar: {
        text: '<i class="far fa-trash-alt icodialog"></i> Eliminar', // With spaces and symbols
        action: function() {
          $.post("gestion_red/delete_firewall", {
              id
            })
            .done(function(data) {
              alerta('exito', 'Regla eliminada correctamente');
              updatefire();
            });

        }
      }
    }
  });
}

function state_firewall(id, t) {

  $.confirm({
    theme: 'supervan',
    draggable: false,
    closeIcon: true,
    animationBounce: 2.5,
    escapeKey: false,
    content: 'Está seguro que desea ' + t + ' la regla firewall?',
    title: '<i class="fa fa-question-circle fa-lg icodialog"></i> ' + t + ' Regla',
    buttons: {
      Eliminar: {
        text: '<i class="far fa-trash-alt icodialog"></i> ' + t, // With spaces and symbols
        action: function() {
          $.post("gestion_red/new_rule_firewall", {
              id: id
            })
            .done(function(data) {
              alerta('exito', 'Operación exitosa');
              updatefire();
            });

        }
      }
    }
  });
}

$(document).ready(function() {

  configtable('#data-router-firewall', 'router');

  fire = $('#data-router-firewall').DataTable({
    "ajax": "gestion_red/get_firewall?id=<?= $id;?>",
    "deferRender": true,
    "idDataTables": "40",
    "aoColumns": [{
        mData: 'id',
        sWidth: "10px"
      },
      {
        mData: 'nombre'
      },
      {
        mData: 'tipo'
      },
      {
        mData: 'ip'
      },
      {
        mData: 'estado',
        sClass: 'text-center'
      },
      {
        mData: 'tool',
        sClass: 'text-center'
      }
    ],
    "initComplete": function(oSettings, json) {
      $("#data-router-firewall_wrapper div.tooltablas")
        .html('<div class="grupotabla btn-group">\
            <button type="button" class="btn btn-white" Onclick="getmodal(\'gestion_red/modal_new_rul_firewall?id=<?= $id;?>\',\'Nueva regla firewall\',\'md\');"><i class="fas fa-plus"></i> Nuevo</button>\
            </div>');
    }

  });



  updatefire = function() {
    fire.ajax.reload(null, false);
  }



});
</script>
