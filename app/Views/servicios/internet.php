<div class="panel panel-blue panel-perfiles">
  <div class="panel-heading">
    <h4 class="panel-title">Servicios de internet</h4>
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fas fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onClick="updateperfiles()"><i class="fas fa-sync-alt"></i></a>
    </div>

  </div>
  <div class="panel-body">

    <table id="data-perfiles" class="display nowrap table table-striped table-bordered table-hover" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>Descarga Kbps</th>
          <th>Subida Kbps</th>
          <th>Profile</th>
          <th>Precio</th>
          <th>Activos</th>
          <th>Suspendidos</th>
          <th>Retirados</th>
          <th class="all" style="max-width:20px !important" data-orderable="false"></th>
        </tr>
      </thead>
    </table>

  </div>
</div>


<script>
  function delete_internet(id, u) {
    if (u > 0) {
      alerta('error', 'No se puede eliminar el servicio porque cuenta con clientes asociados')
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
            $.post("servicios/delete_plan", {
                id
              })
              .done(function(data) {
                if (data == 1) {
                  alerta('exito', 'Servicio eliminado correctamente.');
                  updateperfiles();
                } else if (data == 2) {
                  alerta('error', 'Error al eliminar.');
                } else {
                  alerta('warning', 'No tienes los permisos requeridos.');
                }
              });

          }
        }
      }
    });
  }


  $(document).ready(function() {

    configtable('#data-perfiles', 'Perfiles Internet');

    var perfiles = $('#data-perfiles').DataTable({
      "ajax": "servicios/get_planes",
      "deferRender": true,
      "idDataTables": "40",
      "aoColumns": [{
          mData: 'id',
          sWidth: '20px'
        },
        {
          mData: 'plan'
        },
        {
          mData: 'down',
          sClass: 'text-center'
        },
        {
          mData: 'up',
          sClass: 'text-center'
        },
        {
          mData: 'profile',
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
          sClass: 'text-center'
        }
      ],
      "drawCallback": function(settings) {
        loaderout('.panel-perfiles');
      },
      initComplete: function(oSettings, json) {
        if (<?= $permiso; ?> >= 2) {
          $("#data-perfiles_wrapper div.tooltablas")
            .html('<div class="grupotabla btn-group">\
  		<button type="button" class="btn btn-success" Onclick="getmodal(\'servicios/modal_nuevo_plan\',\'Nuevo Perfil\',\'lg\');"><i class="fas fa-plus"></i> Nuevo</button>\
		</div>');
        }
      }
    });


    updateperfiles = function() {
      loaderin('.panel-perfiles');
      perfiles.ajax.reload(null, false);
    }



  });

  App.restartGlobalFunction();
  App.setPageTitle('Perfiles de Internet');
</script>