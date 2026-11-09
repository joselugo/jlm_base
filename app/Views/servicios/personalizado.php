<div class="panel panel-inverse panel-perfiles-otro">
  <div class="panel-heading">
    <h4 class="panel-title">Servicios Personalizados</h4>
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fas fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onClick="updateperfilesotro()"><i class="fas fa-sync-alt"></i></a>
    </div>

  </div>
  <div class="panel-body">

    <table id="data-perfiles-otro" class="display nowrap table table-striped table-bordered table-hover" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Precio</th>
          <th>Impuesto %</th>
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
  function delete_otro(id, u) {
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
            $.post("servicios/delete_personalizado", {
                id
              })
              .done(function(data) {
                if (data == 1) {
                  alerta('exito', 'Servicio eliminado correctamente');
                  updateperfilesotro();
                } else if (data == 2) {
                  alerta('error', 'Error al eliminar el servicio, vuelva a intentarlo.');
                } else {
                  alerta('warning', 'No cuentas los permisos requeridos.');
                }
              });

          }
        }
      }
    });
  }

  $(document).ready(function() {

    configtable('#data-perfiles-otro', 'Perfiles Personalizados');

    var perfilesotro = $('#data-perfiles-otro').DataTable({
      "ajax": "servicios/get_personalizado",
      "deferRender": true,
      "idDataTables": "40",
      "aoColumns": [{
          mData: 'producto'
        },
        {
          mData: 'costo'
        },
        {
          mData: 'impuesto'
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
        loaderout('.panel-perfiles-otro');
      },
      initComplete: function(oSettings, json) {
        if (<?= $permiso; ?> >= 2) {
          $("#data-perfiles-otro_wrapper div.tooltablas")
            .html('<div class="grupotabla btn-group">\
  		<button type="button" class="btn btn-success" Onclick="getmodal(\'servicios/modal_new_personalizado\',\'Nuevo Perfil\',\'md\');"><i class="fas fa-plus"></i> Nuevo</button>\
		</div>');
        }
      }
    });


    updateperfilesotro = function() {
      loaderin('.panel-perfiles-otro');
      perfilesotro.ajax.reload(null, false);
    }



  });

  App.restartGlobalFunction();
  App.setPageTitle('Otros servicios');
</script>