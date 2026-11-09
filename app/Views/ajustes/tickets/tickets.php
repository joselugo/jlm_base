<ol class="breadcrumb pull-right">
  <li class="breadcrumb-item"><a href="/herramientas">Inicio</a></li>
  <li class="breadcrumb-item"><a href="/herramientas/#ajustes">Ajustes</a></li>
  <li class="breadcrumb-item active">Tickets</li>
</ol>
<h1 class="page-header">Tickets</h1>

<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title"><b>DEPARTAMENTOS</b></h4>
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-expand"><i class="fa fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload" onclick="updatedp()"><i class="fas fa-sync-alt"></i></a>
    </div>

  </div>
  <div class="panel-body border-panel">

    <table id="data-dp" class="display nowrap table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre Departamento</th>
          <th>Descripción</th>
          <th>Correo</th>
          <th class="all" data-orderable="false"></th>
        </tr>
      </thead>
    </table>

  </div>
</div>


<script>
  function delete_dp(id) {
    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Esta seguro que desea eliminar este Departamento? ',
      title: '<i class="fa fa-question-circle fa-lg icodialog"></i> Eliminar Departamento',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt icodialog"></i> Eliminar', // With spaces and symbols
          action: function() {
            $.post("ajustes/delete_departamento", {
                id})
              .done(function(data) {
               if (data==1) {
                alerta("exito","Eliminado correctamente.")
              }else if(data==2){
                alerta("warning","Error al eliminar.")
              }else{
                 alerta("warning","No cuentas con los permisos necesarios.")
               }
                updatedp();
              });

          }
        }
      }
    });
  }

  $(function() {
    App.setPageTitle('Ticket');

    configtable('#data-dp', 'dp');

    var dp = $('#data-dp').DataTable({
      "ajax": "ajustes/ticket_json",
      "deferRender": true,
      "idDataTables": "40",
      "aoColumns": [{
          mData: 'id',
          sWidth: '20px'
        },
        {
          mData: 'dp'
        },
        {
          mData: 'dsp'
        },
        {
          mData: 'maildp'
        },
        {
          mData: 'tool',
          sClass: 'text-center',
          sWidth: '30px'
        }
      ],
      initComplete: function(oSettings, json) {
        $("#data-dp_wrapper div.tooltablas")
          .html('<div class="grupotabla btn-group">\
  		<a class="btn btn-default" onclick="getmodal(\'ajustes/nuevo_departamento\',\'Nuevo departamento\',\'md\');"><i class="fas fa-plus"></i> Nuevo</a>\
		</div>');
      }
    });

    updatedp = function() {
      dp.ajax.reload(null, false);
      //ELIMINADO;
    }

  })
</script>