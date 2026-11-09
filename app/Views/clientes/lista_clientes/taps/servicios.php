<!--<div class="panel panel-default panel-servicios">
  <div class="panel-heading">
    <h4 class="panel-title"><b>Servicios de Internet</b></h4>
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-expand"><i class="fa fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-reload" onclick="updateservicio()"><i class="fa fa-sync-alt"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
    </div>
  </div>
  <div class="panel-body border-panel">
    <table id="data-servicios" class="display nowrap table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>ID</th>
          <th>Plan</th>
          <th>Costo</th>
          <th>IP</th>
          <th class="desktop">Router</th>
          <th>Mac</th>
          <th>Instalado</th>
          <th class="desktop">Dirección</th>
          <th class="all">Estado</th>
          <th class="all" style="max-width:40px !important" data-orderable="false"></th>
        </tr>
      </thead>
    </table>

  </div>
</div> -->


<!--<div class="panel panel-default panel-serviciosvoip">
  <div class="panel-heading">
    <h4 class="panel-title"><b>Servicios Voip</b></h4>
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-expand"><i class="fa fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-reload" onclick="updatevoip()"><i class="fa fa-sync-alt"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
    </div>
  </div>
  <div class="panel-body border-panel">
    <table id="data-serviciosvoip" class="table-hover table table-striped table-bordered dt-responsive nowrap" width="100%">
      <thead>
        <tr>
          <th>ID</th>
          <th class="all">Plan</th>
          <th>SIP Server</th>
          <th>SIP User</th>
          <th>Authenticate ID</th>
          <th>Nº telefono</th>
          <th class="all">Costo</th>
          <th>Instalado</th>
          <th>Notas</th>
          <th class="all" data-orderable="false" style="min-width:20px !important;max-width:20px !important"></th>
        </tr>
      </thead>
    </table>
  </div>
</div> -->


<div class="panel panel-default panel-recurrentes">
  <div class="panel-heading">
    <h4 class="panel-title"><b>Productos y otros Servicios Recurrentes <small>(CUOTAS Y MENSUAL)</small></b></h4>
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-expand"><i class="fa fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-reload" onclick="updaterecurrente()"><i class="fa fa-sync-alt"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
    </div>
  </div>
  <div class="panel-body border-panel">
    <table id="data-recurrentes" class="table-hover table table-striped table-bordered dt-responsive nowrap" width="100%">
      <thead>
        <tr>
          <th>ID</th>
          <th class="all">Producto</th>
          <th>Monto</th>
          <th>Nº Serie</th>
          <th>Nº Mac</th>
          <th>Fecha inicio</th>
          <th>Nº Cuotas</th>
          <th>Estado</th>
          <th class="all" data-orderable="false" style="min-width:20px !important;max-width:20px !important"></th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<div class="panel panel-default panel-unpago">
  <div class="panel-heading">
    <h4 class="panel-title"><b>Productos y Otros Servicios <small>(UN SOLO PAGO)</small></b></h4>
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-expand"><i class="fa fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-reload" onclick="updateunpago()"><i class="fa fa-sync-alt"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
    </div>
  </div>
  <div class="panel-body border-panel">
    <table id="data-unpago" class="table-hover table table-striped table-bordered dt-responsive nowrap" width="100%">
      <thead>
        <tr>
          <th>ID</th>
          <th class="all">Producto</th>
          <th>Monto</th>
          <th>Nº Serie</th>
          <th>Nº Mac</th>
          <th>Fecha inicio</th>
          <th>Factura</th>
          <th>Estado</th>
          <th class="all" data-orderable="false" style="min-width:20px !important;max-width:20px !important"></th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<script>
  var servicios, serviciosvoip, recurrente, unpago

  function remove_service(idservicio) {
    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Toda informacion del cliente en el Mikrotik será eliminado (Aarp,Queues,pppoe,pcq,hotspot,Address list,etc.)',
      title: '<i class="fas fa-question-circle fa-lg icodialog"></i> Eliminar Servicio',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt icodialog"></i> Si, Eliminar', // With spaces and symbols
          action: function() {
            loaderin('.panel-servicios');
            $.post("clientes/delete_servicio", {
                idservicio: idservicio,
                idcliente: "<?= $id; ?>"
              })
              .done(function(data) {
                if (data == 2) {
                  alerta('warning', "Error al eliminar el servicio");
                  updateservicio();
                } else {
                  alerta('exito', "El servicio se elimino correctamente");
                  updateservicio();
                }
                loaderout('.panel-servicios');
              });

          }
        }
      }
    });
  }

  function remove_recurrente(id) {
    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Esta seguro que desea elimina este producto/servicio recurrente?',
      title: '<i class="fas fa-question-circle fa-lg icodialog"></i> Eliminar',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt icodialog"></i> Si, Eliminar', // With spaces and symbols
          action: function() {
            loaderin('.panel-recurrentes');
            $.post("clientes/delete_servicio_recurrente", {
                idservicio: id,
                idcliente: "<?= $id; ?>"
              })
              .done(function(data) {
                if (data == 2) {
                  alerta('error', 'Error al eliminar el servicio.');
                  updaterecurrente();
                  loaderout('.panel-recurrentes');
                } else {
                  alerta('exito', 'Registro eliminado correctamente');
                  updaterecurrente();
                  loaderout('.panel-recurrentes');
                }
              });

          }
        }
      }
    });
  }

  function remove_unpago(id) {
    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Esta seguro que desea elimina este producto/servicio?',
      title: '<i class="fas fa-question-circle fa-lg icodialog"></i> Eliminar',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt icodialog"></i> Si, Eliminar', // With spaces and symbols
          action: function() {
            loaderin('.panel-unpago');
            $.post("clientes/delete_servicio_un_pago", {
                idservicio: id,
                idcliente: "<?= $id; ?>",
              })
              .done(function(data) {
                if (data == 2) {
                  alerta('error', 'Error al eliminar.');
                  updateunpago();
                  loaderout('.panel-unpago');
                } else {
                  alerta('exito', 'Registro eliminado correctamente');
                  updateunpago();
                  loaderout('.panel-unpago');
                }
              });

          }
        }
      }
    });
  }

  function remove_servicevoip(id) {
    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Esta seguro que desea eliminar este servicio voip?',
      title: '<i class="fas fa-question-circle fa-lg icodialog"></i> Eliminar Servicio',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt icodialog"></i> Si, Eliminar', // With spaces and symbols
          action: function() {
            loaderin('.panel-serviciosvoip');
            $.post("clientes/delete_servicio_voip", {
                idservicio: id,
                idcliente: "<?= $id; ?>",
              })
              .done(function(data) {
                if (data == 2) {
                  alerta('warning', data['salida']);
                } else {
                  alerta('exito', data['salida']);
                }
                updatevoip();
                loaderout('.panel-serviciosvoip');
              });

          }
        }
      }
    });
  }

  updateservicio = function() {
    servicios.ajax.reload(null, false);
  }

  updatevoip = function() {
    serviciosvoip.ajax.reload(null, false);
  }

  updaterecurrente = function() {
    recurrente.ajax.reload(null, false);
  }

  updateunpago = function() {
    unpago.ajax.reload(null, false);
  }

  $(function() {

    configtable('#data-servicios', 'Servicios');

    servicios = $('#data-servicios').DataTable({
      "ajax": "clientes/get_servicios_json?id=" + "<?= $id ?>",
      "deferRender": true,
      "aoColumns": [{
          mData: 'id'
        },
        {
          mData: 'plan'
        },
        {
          mData: 'costo'
        },
        {
          mData: 'ip'
        },
        {
          mData: 'nodo'
        },
        {
          mData: 'mac'
        },
        {
          mData: 'instalado'
        },
        {
          mData: 'direccion'
        },
        {
          mData: 'estado'
        },

        {
          mData: 'tools'
        }
      ],
      initComplete: function(oSettings, json) {
        if (<?= $permiso; ?> >= 2) {

          $("#data-servicios_wrapper div.tooltablas")
            .html('<div class="grupotabla btn-group">\
        <button type="button" class="btn btn-default" Onclick="getmodal(\'clientes/modal_nuevo_servicio?id=<?= $id; ?>\',\'Nuevo servicio internet\',\'lg\');"><i class="fas fa-plus"></i> <b>Nuevo</b></button>\
      </div>');
        }

      }
    }).on('processing.dt', function(e, settings, processing) {
      if (processing) {
        loaderin('.panel-serviciosvoip');
      } else {
        loaderout('.panel-serviciosvoip');
      }
    }).on('draw', function() {
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
    });

  }).on('processing.dt', function(e, settings, processing) {
    if (processing) {
      loaderin('.panel-servicios');
    } else {
      loaderout('.panel-servicios');
    }
  }).on('draw', function() {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
  });

  configtable('#data-serviciosvoip', 'Servicios');
  serviciosvoip = $('#data-serviciosvoip').DataTable({
    "ajax": "clientes/get_serviciosvoip_json?id=" + "<?= $id ?>",
    "deferRender": true,
    "aoColumns": [{
        mData: 'id'
      },
      {
        mData: 'plan'
      },
      {
        mData: 'server'
      },
      {
        mData: 'sipuser'
      },
      {
        mData: 'sipid'
      },
      {
        mData: 'numerotelefono'
      },
      {
        mData: 'costo'
      },
      {
        mData: 'instalacion'
      },
      {
        mData: 'notas'
      },
      {
        mData: 'tools'
      }
    ],
    initComplete: function(oSettings, json) {
      if (<?= $permiso; ?> >= 2) {

        $("#data-serviciosvoip_wrapper div.tooltablas")
          .html('<div class="grupotabla btn-group">\
        <button type="button" class="btn btn-default" Onclick="getmodal(\'clientes/modal_nuevo_servicio_void?idcliente=<?= $id; ?>\',\'Nuevo servicio Voip\',\'lg\');"><i class="fas fa-plus"></i> <b>Nuevo</b></button>\
      </div>');
      }
    }
  })

  configtable('#data-recurrentes', 'Servicios');
  recurrente = $('#data-recurrentes').DataTable({
    "ajax": "clientes/get_productos_y_servicios_recurrentes?idcliente=" + "<?= $id ?>",
    "deferRender": true,
    "idDataTables": "40",
    "aoColumns": [{
        mData: 'id',
        sWidth: "10px"
      },
      {
        mData: 'producto'
      },
      {
        mData: 'monto'
      },
      {
        mData: 'serie'
      },
      {
        mData: 'mac'
      },
      {
        mData: 'fecha'
      },
      {
        mData: 'cuotas'
      },
      {
        mData: 'estado'
      },
      {
        mData: 'tools',
        sClass: 'text-center'
      }
    ],
    initComplete: function(oSettings, json) {
      if (<?= $permiso; ?> >= 2) {

        $("#data-recurrentes_wrapper div.tooltablas")
          .html('<div class="grupotabla btn-group">\
        <button type="button" class="btn btn-default" Onclick="getmodal(\'clientes/modal_new_servicio_recurrente?idcliente=<?= $id; ?>\', \'Agregar Servicios Recurrentes\',\'md\');"><i class="fas fa-plus"></i> <b>Nuevo</b></button>\
      </div>');
      }
    }
  }).on('processing.dt', function(e, settings, processing) {
    if (processing) {
      loaderin('.panel-recurrentes');
    } else {
      loaderout('.panel-recurrentes');
    }
  }).on('draw', function() {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
  });

  configtable('#data-unpago', 'Servicios');
  unpago = $('#data-unpago').DataTable({
    "ajax": "clientes/get_productos_y_servicios_un_solo_pago?idcliente=" + "<?= $id; ?>",
    "deferRender": true,
    "idDataTables": "40",
    "aoColumns": [{
        mData: 'id',
        sWidth: "10px"
      },
      {
        mData: 'producto'
      },
      {
        mData: 'monto'
      },
      {
        mData: 'serie'
      },
      {
        mData: 'mac'
      },
      {
        mData: 'fecha'
      },
      {
        mData: 'factura'
      },
      {
        mData: 'estado'
      },
      {
        mData: 'tools',
        sClass: 'text-center'
      }
    ]
  }).on('processing.dt', function(e, settings, processing) {
    if (processing) {
      loaderin('.panel-unpago');
    } else {
      loaderout('.panel-unpago');
    }
  }).on('draw', function() {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
  });
</script>