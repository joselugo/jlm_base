<table id="data-onus" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%" style="width: 100%;">
  <thead>
    <tr>
      <th class="column">ID</th>
      <th class="column">Slot</th>
      <th class="column">Puerto</th>
      <th class="column">ONU ID</th>
      <th class="column">Service Port</th>
      <th class="column">Cliente</th>
      <th class="column">SN</th>
      <th class="column">Modelo</th>
      <th class="column">OLT</th>
      <th class="column">Modo</th>
      <th class="column">Fecha</th>
      <th class="column all">Estado</th>
      <th class="column all" data-orderable="false">Acción</th>
    </tr>
  </thead>
</table>
<script>
  $(document).ready(function() {

    configtable('#data-onus', 'ONUS')

    var onus = $('#data-onus').DataTable({
      "ajax": "fiber/get_onus_autorize?estado=<?= $estado ?>",
      "deferRender": true,
      "idDataTables": "40",
      "aoColumns": [{
          mData: 'id',
          sWidth: "10px"
        },
        {
          mData: 'tarjeta'
        },
        {
          mData: 'puerto'
        },
        {
          mData: 'idonu'
        },
        {
          mData: 'srv_port'
        },
        {
          mData: 'nombre'
        },
        {
          mData: 'sn',
          sClass: 'text-center'
        },
        {
          mData: 'modelo'
        },
        {
          mData: 'olt'
        },
        {
          mData: 'modo'
        },
        {
          mData: 'fecha'
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
        $("#data-router_wrapper div.tooltablas")
          .html('<div class="grupotabla btn-group">\
      <button type="button" class="btn btn-success" Onclick="getmodal(\'gestion_red/new_server\',\'Nuevo\',\'md\');"><i class="fas fa-plus"></i> Nuevo</button>\
    </div>');
      }

    });



    updateonus = function() {
      onus.ajax.reload(null, false);
    }


  });

  function delete_onu(id, idolt, idcliente, sn, n) {
    var alsup = $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Esta seguro que desea eliminar la ONT del cliente <br><b class="text-warning">' + n + '</b><br><br><br>' +
        '<div class="form-group row"><label class="col-form-label col-md-12 text-center" style="color:#fff"><b>Eliminar solo de Netium Fiber (Base de datos)<br>No se eliminará en la OLT</b></label><div class="col-md-12 text-center"><label class="switch"><input class="chk" type="checkbox" name="removeall"><span class="slider round"></span></label></div></div>',
      title: '<i class="far fa-question-circle fa-lg icodialog"></i> ELIMINAR',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt"></i> Si, eliminar',
          action: function() {
            this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
            this.buttons.Eliminar.disable();
            $('.jconfirm-closeIcon').remove();
            $.post("fiber/delete_onu", {
                id: id,
                idolt: idolt,
                idcliente: idcliente,
                sn: sn,
                cliente: n,
                check: $('*[name="removeall"]').is(':checked')
              })
              .done(function(data) {
                if (data == "<p>ACCESO DENEGADO.</p>") {
                  alerta('warning', "No cuentas con los permisos necesarios.");
                }else{
                  data = JSON.parse(data);
                  alsup.close();
                  if (data.estado == "error") {
                    alerta('error', data.mensaje);
                  } else if (data.estado == "success") {
                    alerta('exito', data.mensaje);
                  } else {
                    alerta('warning', data.mensaje);
                  }
                  updateonus();

                }

              });
            return false;

          }
        }
      }
    });
  }
</script>