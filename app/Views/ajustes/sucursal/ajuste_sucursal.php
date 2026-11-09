<div class="panel panel-default mt-5">
  <div class="panel-heading">
    <h4 class="panel-title"><b>GESTIÓN DE SUCURSALES</b></h4>
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload" onclick="updatesucursal()"><i class="fas fa-sync-alt"></i></a>
    </div>
  </div>
  <div class="panel-body border-panel">

    <table id="table_config_sucursal" class="display nowrap table table-striped table-bordered dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%" role="grid" aria-describedby="data-operadores_info" style="width: 100%;">
      <thead>
        <tr role="row">
          <th class="all sorting_asc" tabindex="0" aria-controls="data-operadores" rowspan="1" colspan="1" data-column-index="0" style="width: 20px;" aria-label="ID: Activar para ordenar la columna de manera descendente" aria-sort="ascending">ID</th>
          <th class="sorting" tabindex="0" aria-controls="data-operadores" rowspan="1" colspan="1" data-column-index="1" style="width: 0px; display: none;" aria-label="Nombre: Activar para ordenar la columna de manera ascendente">Sucursal</th>
          <th class="all sorting" tabindex="0" aria-controls="data-operadores" rowspan="1" colspan="1" data-column-index="2" style="width: 160px;" aria-label="Usuario: Activar para ordenar la columna de manera ascendente">Direccion</th>
          <th class="all sorting" tabindex="0" aria-controls="data-operadores" rowspan="1" colspan="1" data-column-index="2" style="width: 160px;" aria-label="Usuario: Activar para ordenar la columna de manera ascendente">Ubicacion</th>
          <th class="all text-center sorting_disabled" data-orderable="false" rowspan="1" colspan="1" data-column-index="6" style="width: 36px;" aria-label=""></th>
        </tr>
      </thead>

    </table>



  </div>
</div>
<script>
  function delete_sucursal(id) {

    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Estas seguro de querer eliminar esta sucursal?',
      title: '<i class="fas fa-question-circle fa-lg icodialog"></i> Eliminar sucursal',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt icodialog"></i> Eliminar sucursal',
          action: function() {
            $.post(base_url + 'ajustes/delete_sucursal', {
              id
            }, function(data) {
              if (data == "1") {
                alerta('exito', 'Datos eliminado con exito');
                updatesucursal()
              } else {
                alerta('error', 'Error al actualizar datos')
              }
            })

          }
        },
        Cancelar: {}
      }
    });
  }
  $(function() {
    App.setPageTitle('Gestión sucursal');
    configtable('#table_config_sucursal', 'config_sucursal');

    var sucursal = $('#table_config_sucursal').DataTable({
      "ajax": base_url + "ajustes/get_sucursales",
      "deferRender": true,
      "idDataTables": "1",
      "aoColumns": [{
          mData: 'id',
          sWidth: '20px'
        }, {
          mData: 'sucursal',
          sWidth: '20px'
        },
        {
          mData: 'direccion',
          sWidth: '20px'
        },
        {
          mData: 'ubicacion',
          sWidth: '20px',
        },
        {
          mData: 'tool',
          sWidth: '20px',
          className: 'priority'
        }


      ],
      initComplete: function(oSettings, json) {
        $("#table_config_sucursal_wrapper .dt-buttons")
          .append("<div class=\"btn-group\">\
          <button id=\"create\" onclick=\"getmodal('ajustes/new_sucursal','Nueva sucursal','sm')\" class=\"btn btn-success\" data-toggle=\"modal\" data-target=\"#add\">\
          <i class=\"fas fa-plus\"></i> Nuevo</button></div>");

      }
    }).on('draw', function() {
      $('img[data-name]').initial({
        height: 64,
        width: 64,
        charCount: 2,
        fontSize: 24,
        fontWeight: 600
      });

    });

    updatesucursal = function() {
      sucursal.ajax.reload(null, false);
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
    }

  })
</script>