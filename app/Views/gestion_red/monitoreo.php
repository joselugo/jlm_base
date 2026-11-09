<div class="panel panel-blue panel-emisor">
  <div class="panel-heading">
    <h4 class="panel-title">Monitoreo</h4>
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fas fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onClick="updateemisor()"><i class="fas fa-sync-alt"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-collapse"><i class="fas fa-minus"></i></a>
    </div>

  </div>
  <div class="panel-body">

    <table id="data-emisores" class="display  table table-striped table-bordered table-hover" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th class="">ID</th>
          <th class="all">Nombre</th>
          <th>Equipo</th>
          <th class="all">IP</th>
          <th class="all">Estado</th>
          <th class="">Servicios</th>
          <th class="">online</th>
          <th class="">offline</th>
          <th class="">Users</th>
          <th class="">activos</th>
          <th class="">suspen..</th>
          <th class="all" style="max-width:20px !important" data-orderable="false"></th>
        </tr>
      </thead>
    </table>

  </div>
</div>

<script>
  var emisor;

  function updateemisor() {
    loaderin('.panel-emisor');
    emisor.ajax.reload(null, false);
  }

  function delete_emisor(id) {

    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Está seguro que desea eliminar el emisor seleccionado?',
      title: '<i class="far fa-question-circle fa-lg icodialog"></i> Eliminar Emisor',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt icodialog"></i> Si, Eliminar', // With spaces and symbols
          action: function() {
            $.post("gestion_red/delete_emisor", {
                id
              })
              .done(function(data) {
                if (data == 2) {
                  updateemisor();
                  alerta('error', 'Error al eliminar');
                } else {
                  if (data == "<p>ACCESO DENEGADO.</p>") {
                    alerta('warning', 'Permiso denegado');
                  } else {
                    updateemisor();
                    alerta('exito', 'Equipo eliminado correctamente');
                  };
                }
              });

          }
        }
      }
    });

  }

  $(function() {

    $(".Select2").select2({
      placeholder: "Seleccionar..."
    });


    configtable('#data-emisores', 'Emisores');
    emisor = $('#data-emisores').DataTable({
      "ajax": "gestion_red/get_emisores_json",
      "deferRender": true,
      "responsive": true,
      "idDataTables": "40",

      "aoColumns": [{
          mData: 'id',
          sWidth: "20px"
        },
        {
          mData: 'nombre'
        }, {
          mData: 'equipo',
        },
        {
          mData: 'ip',
          'render': function(data, type, full, meta) {
            return `<a href="http://${data}"  target="_blank">${data}</a>`;
          }
        },

        {
          mData: 'estado',
          sClass: 'text-center'
        },
        {
          mData: 'servicios',
          sClass: 'text-center'
        },
        {
          mData: 'online',
          sClass: 'text-center'
        },
        {
          mData: 'offline',
          sClass: 'text-center'
        },
        {
          mData: 'usuarios',
          sClass: 'text-center'
        },
        {
          mData: 'activos',
          sClass: 'text-center'
        },
        {
          mData: 'suspendidos',
          sClass: 'text-center'
        },
        {
          mData: 'tool',
          sClass: 'text-center'
        }
      ],
      "drawCallback": function(settings) {
        loaderout('.panel-emisor');
      },
      initComplete: function(oSettings, json) {
        if (<?= $permiso; ?> >= 2) {
          $("#data-emisores_wrapper div.tooltablas")
            .html('<div class="grupotabla btn-group">\
  		<button type="button" class="btn btn-success" Onclick="getmodal(\'gestion_red/modal_new_emisor\',\'Nuevo Emisor\',\'md\');"><i class="fas fa-plus"></i> Nuevo</button>\
		</div>');
        }
      }
    });


  })


  App.restartGlobalFunction();
  App.setPageTitle('Emisores');
</script>