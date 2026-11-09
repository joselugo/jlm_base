<div class="panel panel-blue panel-router">
  <div class="panel-heading">
    <h4 class="panel-title">Lista de Router</h4>
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fas fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onClick="updaterouter()"><i class="fas fa-sync-alt"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-collapse"><i class="fas fa-minus"></i></a>
    </div>

  </div>
  <div class="panel-body">

    <table id="data-router" data-order="[[ 1, &quot;asc&quot; ]]" class="display nowrap table table-striped table-bordered table-hover" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre</th>
          <th>IP</th>
          <th>Puerto web</th>
          <th>Modelo</th>
          <th>Versión</th>
          <th>Clientes</th>
          <th>Estado</th>
          <th class="all" style="max-width:20px !important" data-orderable="false"></th>
        </tr>
      </thead>
    </table>

  </div>
</div>

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script>
  function mode_mantenimiento(id) {
    $('.tooltip').remove();
    $.post("ajax/router", {
        action: "mante",
        id: id
      })
      .done(function(data) {

        updaterouter();
        alerta('exito', data['salida']);

      });
  }

  function protege_router(id) {

    var protegemk = $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: '<b>¿Está seguro que desea realizar esta operación?</b><br>Al iniciar esta operación Mikrowisp creará reglas de DROP en su filter para proteger el webproxy y DNS caché de Mikrotik.<br><b>Las reglas instaladas apuntan con IN INTERFACE=ETHER-WAN si las reglas creadas no apuntan a la interface correcta puede editarla manualmente.</b>',
      title: '<i class="fa fa-question-circle fa-lg icodialog"></i> PROTEGER ROUTER',
      buttons: {
        Eliminar: {
          text: 'Instalar Reglas', // With spaces and symbols
          action: function() {
            this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
            this.buttons.Eliminar.disable();
            $('.jconfirm-closeIcon').remove();

            $.post("ajax/router", {
                id: id,
                action: "proteger"
              })
              .done(function(data) {
                protegemk.close();
                alerta(data['estado'], data['salida']);
              });
            return false;
          }
        }
      }
    });
  }

  function masive_create_queues(id) {
    var confirm = $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: '<b>¿Está seguro que desea realizar la importacion masiva?<br><br><b>Esta operación puede tardar un tiempo en terminar,esperar la confirmación de finalizado.</b>',
      title: '<i class="fa fa-question-circle fa-lg icodialog"></i> MASIVE IMPORT',
      buttons: {
        Eliminar: {
          text: '<i class="fa fa-trash-o icodialog"></i> Iniciar', // With spaces and symbols
          action: function() {
            this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
            this.buttons.Eliminar.disable();
            $('.jconfirm-closeIcon').remove();

            $.post("clientes/masive_create_queues", {
                id
              })
              .done(function(data) {
                if (data == 2) {
                  alerta("warning", "El servidor no existe o no se encontro ningun servicio asignado al mismo.");
                } else {
                  $("#content").html("");
                  $("#content").html(data);
                }

                confirm.close();
              });
            return false;
          }
        }
      }
    });
  }

  function reparar_router(id) {

    var repararmk = $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: '<b>¿Está seguro que desea realizar esta operación?</b><br>Al iniciar esta operación Mikrowisp realizará cambios en su mikrotik según la configuración de su planes de internet en mikrowisp (Crear/editar Colas simples,pppe,hotspot,etc.)<br><br><b>Esta operación puede tardar un tiempo en terminar,esperar la confirmación de finalizado.</b>',
      title: '<i class="fa fa-question-circle fa-lg icodialog"></i> REPARAR ROUTER',
      buttons: {
        Eliminar: {
          text: '<i class="fa fa-trash-o icodialog"></i> Iniciar', // With spaces and symbols
          action: function() {
            this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
            this.buttons.Eliminar.disable();
            $('.jconfirm-closeIcon').remove();

            $.post("ajax/router", {
                id: id,
                action: "reparar"
              })
              .done(function(data) {
                repararmk.close();
                alerta('exito', 'Operación completada');
              });
            return false;
          }
        }
      }
    });
  }

  function delete_router(id, cliente, conter) {
    if (conter > 0) {
      alerta('warning', 'No se puede eliminar este router porque tiene <b>' + conter + '</b> clientes registrados');
      return false;
    }

    var alsup = $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Esta seguro que desea eliminarel router <br><b class="text-warning text-uppercase">' + window.atob(
        cliente) + '</b>',
      title: '<i class="far fa-question-circle fa-lg icodialog"></i> ELIMINAR',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt"></i> Si, eliminar',
          action: function() {
            this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
            this.buttons.Eliminar.disable();
            $('.jconfirm-closeIcon').remove();
            $.post("gestion_red/delete_server", {
                id
              })
              .done(function(data) {
                alsup.close();
                if (data == 1) {
                  alerta('exito', 'Server eliminado correctamente.');
                  updaterouter();
                } else if (data == 2) {
                  alerta('error', 'Error al eliminar el server, no se elimino.');
                } else {
                  alerta('warning', 'No tienes permisos para realizar esta accion.');
                }
              });
            return false;

          }
        }
      }
    });


  }

  $(document).ready(function() {
    $('[data-toggle="popover"]').popover()

    configtable('#data-router', 'router');

    var router = $('#data-router').DataTable({
      "ajax": "gestion_red/get_servers",
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
          mData: 'ip'
        },
        {
          mData: 'port_web',
          visible: false
        },
        {
          mData: 'modelo'
        },
        {
          mData: 'version'
        },
        {
          mData: 'clientes',
          sClass: 'text-center'
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
      "drawCallback": function(settings) {
        loaderout('.panel-router');
      },

      initComplete: function(oSettings, json) {
        if (<?= $permiso; ?> >= 2) {
          $("#data-router_wrapper div.tooltablas").html('<div class="grupotabla btn-group">\
            <button type="button" class="btn btn-success" Onclick="getmodal(\'gestion_red/new_server\',\'Nuevo Router\',\'md\');"><i class="fas fa-plus"></i> Nuevo</button>\
          </div>');
        }
      }

    });



    updaterouter = function() {
      loaderin('.panel-router');
      router.ajax.reload(null, false);
    }



  });

  App.restartGlobalFunction();
  App.setPageTitle('Routers');
</script>
<!-- ================== END PAGE LEVEL JS ================== -->