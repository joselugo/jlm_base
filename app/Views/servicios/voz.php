<div class="panel panel-inverse panel-perfiles-voip">
  <div class="panel-heading">
    <h4 class="panel-title">Servicios voz</h4>
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fas fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onClick="updateperfilesvoip()"><i class="fas fa-sync-alt"></i></a>
    </div>

  </div>
  <div class="panel-body">

    <table id="data-perfiles-voip" class="display nowrap table table-striped table-bordered table-hover" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Minutos</th>
          <th>Precio</th>
          <th>Activos</th>
          <th>Suspendidos</th>
          <th>Retirados</th>
          <th class="all" data-orderable="false"></th>
        </tr>
      </thead>
    </table>

  </div>
</div>


<script>
  function delete_voip(id, u) {
    if (u > 0) {
      alerta('warning', 'No se puede eliminar el servicio porque cuenta con clientes asociados')
      return false;
    }
    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Solo se podrá eliminar Servicios que no tengan Clientes asociados',
      title: '<i class="fa fa-question-circle fa-lg icodialog"></i> Eliminar Servicio',
      buttons: {
        Eliminar: {
          text: '<i class="fas fa-trash icodialog"></i> Eliminar',
          action: function() {
            $.post("servicios/delete_perfil_voip", {
                id
              })
              .done(function(data) {
                if (data == 1) {
                  alerta('exito', 'Servicio eliminado correctamente');
                  updateperfilesvoip();
                } else if (data == 2) {
                  alerta('error', 'Error al eliminar el servicio.');
                } else {
                  alerta('warning', 'No cuentas con los permisos necesarios');
                }
              });

          }
        }
      }
    });
  }


  $(document).ready(function() {

    configtable('#data-perfiles-voip', 'Perfiles Voip');

    var perfilesvoip = $('#data-perfiles-voip').DataTable({
      "ajax": "servicios/get_servicios_voz",
      "deferRender": true,
      "idDataTables": "40",
      "aoColumns": [{
          mData: 'id',
          sWidth: '20px'
        },
        {
          mData: 'nombre'
        },
        {
          mData: 'minutos',
          sClass: 'text-center',
          visible: false
        },
        {
          mData: 'costo'
        },
        {
          mData: 'activo',
          sClass: 'text-center',
          'render': function(data, type, full, meta) {
            return '<span class="badge label-success">' + data + '</span>';
          }
        },
        {
          mData: 'suspendido',
          sClass: 'text-center',
          'render': function(data, type, full, meta) {
            return '<span class="badge label-warning">' + data + '</span>';
          }
        },
        {
          mData: 'retirado',
          sClass: 'text-center',
          'render': function(data, type, full, meta) {
            return '<span class="badge label-danger">' + data + '</span>';
          }
        },
        {
          mData: 'tool',
          sClass: 'text-center',
          sWidth: '30px'
        }
      ],
      "drawCallback": function(settings) {
        loaderout('.panel-perfiles-voip');
      },
      initComplete: function(oSettings, json) {
        if (<?= $permiso; ?> >= 2) {
          $("#data-perfiles-voip_wrapper div.tooltablas")
            .html('<div class="grupotabla btn-group">\
  		<button type="button" class="btn btn-success" Onclick="getmodal(\'servicios/modal_new_perfil_voip\',\'Nuevo servicio voz\',\'md\');"><i class="fas fa-plus"></i> Nuevo</button>\
		</div>');
        }
      }
    });


    updateperfilesvoip = function() {
      loaderin('.panel-perfiles-voip');
      perfilesvoip.ajax.reload(null, false);
    }



  });

  App.restartGlobalFunction();
  App.setPageTitle('Perfiles Voip');
</script>