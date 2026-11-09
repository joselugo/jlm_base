<ol class="breadcrumb pull-right">
  <li class="breadcrumb-item"><a href="/herramientas">Inicio</a></li>
  <li class="breadcrumb-item"><a href="/herramientas/#ajustes">Ajustes</a></li>
  <li class="breadcrumb-item active">Ubicaciones</li>
</ol>

<h1 class="page-header">Ubicaciones</h1>

<!-- begin row -->
<div class="row">

  <div class=" col-md-7">

    <div class="panel panel-inverse panel-router">
      <div class="panel-heading">
        <h4 class="panel-title">Lista Ubicaciones</h4>
      </div>
      <div class="panel-body">

        <table id="data-zona" class="display nowrap table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>ID</th>
              <th>Ubicación</th>
              <th>Activos</th>
              <th>Suspendidos</th>
              <th class="all" style="max-width:20px !important" data-orderable="false"></th>
            </tr>
          </thead>
        </table>



      </div>
    </div>

  </div>

</div>
<!-- end row -->

<script>
  function delete_zona(id, conter) {
    if (conter > 0) {
      alerta('error', 'No se puede eliminar esta ubicación porque tiene clientes asignados');
      return false;
    }

    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Se va eliminar la ubicación seleccionada?',
      title: '<i class="far fa-question-circle fa-lg icodialog"></i> Eliminar',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt icodialog"></i> Eliminar', // With spaces and symbols
          action: function() {
            $.post("ajustes/delete_zona", {
                id
              })
              .done(function(data) {
                alerta('exito', 'Registro eliminado correctamente');
                updatezona();
              });

          }
        }
      }
    });

  }


  $(function() {

    updatezona = function() {
      zona.ajax.reload(null, false);
    }

    configtable('#data-zona', 'Zonas');
    var zona = $('#data-zona').DataTable({
      "ajax": "ajustes/get_zonas_json",
      "deferRender": true,
      "aoColumns": [{
          mData: 'id',
          sWidth: "10px"
        },
        {
          mData: 'zona'
        },
        {
          mData: 'activos'
        },
        {
          mData: 'suspendidos'
        },
        {
          mData: 'tool',
          sClass: 'text-center'
        }
      ],
      "idDataTables": "4",
      initComplete: function(oSettings, json) {
        $("#data-zona_wrapper div.tooltablas")
          .html('<div class="grupotabla btn-group">\
  		<button type="button" class="btn btn-white" Onclick="getmodal(\'ajustes/modal_new_zona\',\'Nueva Ubicación\',\'sm\');"><i class="fas fa-plus"></i> Nuevo</button>\
		</div>');
      }
    });



    App.setPageTitle('Configuración');


  })
</script>