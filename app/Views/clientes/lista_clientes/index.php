<style>
  @media (max-width: 576px) {

    .icon-button,
    .dt-buttons {
      display: none;
    }

    .slstatesuser {
      margin-left: -10px;
    }
  }
</style>


<div class="panel panel-blue panel-Usuarios">
  <div class="panel-heading">
    <h4 class="panel-title">Lista Usuarios</h4>
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fas fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onclick="updateusuarios()"><i class="fas fa-sync-alt"></i></a>
    </div>
  </div>
  <div class="panel-body">

    <div class="box box-solid  box-inverse collapse" id="collapseactivo">
      <div class="box-header with-border">
        <h3 class="box-title">Filtro Avanzado</h3>
      </div>
      <div class="box-body">
        <div class="row">
          <div class="col-lg-4 col-6">
            <div class="form-group input-group-sm">
              <label>Seleccionar campo</label>
              <select class="form-control select-filter-a" onchange="$('.txt-filtro-a').val('').focus();Usuarios.search( '' ).columns().search( '' ).draw();saveSettings();">
                <option value="0">Id</option>
                <option value="1">Nombre</option>
                <option value="2">Sindicato</option>
                <option value="3">Domicilio</option>
                <option value="4">Movil</option>
                <option value="5">Correo</option>
                <option value="6">INE</option>
                <option value="7">Alta</option>
                <option value="8">Status</option>
              </select>
            </div>
          </div>


          <div class="col-lg-4 col-6">
            <div class="form-group input-group-sm">
              <label>Buscar</label>
              <input class="form-control txt-filtro-a" type="text" autocomplete="off" autofocus="" onkeyup="filtro_a($('.select-filter-a').val(),this.value)">
            </div>
          </div>

          <div class="col-lg-4 col-6">
            <div class="form-group input-group-sm">
              <label style="color:#FFFFFF; width:100%">Buscar</label>
              <button class="btn btn-success btn-sm" onclick="$('.txt-filtro-a').val('');Usuarios.search( '' ).columns().search( '' ).draw();saveSettings();saveSettings();">Resetear Filtro</button>
            </div>
          </div>

        </div>
      </div>
    </div>


    <!--<table id="list-usuarios" class="table table-striped table-bordered dt-responsive nowrap dataTable dtr-inline collapsed" cellspacing="0" width="100%"  role="grid" aria-describedby="list-usuarios_info" style="width: 100%;">
<thead>
<tr role="row">
<th class="all sorting_asc" aria-sort="ascending">Id</th>
<th class="all sorting" style="min-width: 100px; width: 100px;">Nombre</th>
<th class="sorting">Sindicato</th>
<th class="sorting">Domicilio</th>
<th class="sorting">Movil</th>
<th class="sorting">Correo</th>
<th class="sorting">Licencia</th>
<th class="sorting">INE</th>
<th class="sorting">Alta</th>
<th class="text-center sorting">Status</th>
<th class="all sorting_disabled" style="min-width: 30px; max-width:30px; width: 30px;" data-orderable="false">Acción</th>
</tr>
	
</thead>	
</table>-->

    <table id="list-usuarios" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%" style="width: 100%;">
      <thead>
        <tr>
          <th class="all column" data-orderable="false" style=" padding-right:0px !important"><input type="checkbox" style="font-size: 19px;" class="allsuspendido" onClick="selectallactivos();"></th>
          <th class="column">Id</th>
          <th class="column">Nombres</th>
          <th class="column">Sucursal</th>
    
          <th class="column">Domicilio</th>
         
          <th class="column">Movil</th>

          <th class="column">Correo</th>
          <th class="all column">Status</th>
          <th class="all column" data-orderable="false" style="max-width:40px"></th>
        </tr>
      </thead>

    </table>



  </div>
</div>

<script>
  var usuarios;
  var base_url = "<?= base_url(); ?>";

  function delete_socio(id, t, n) {

    var alsup = $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Esta seguro que desea eliminar al socio <br><b class="text-warning">#' + n + '</b><br> Los datos de los Usuarios serán eliminados junto a todas sus unidades registradas.<br>',
      title: '<i class="far fa-question-circle fa-lg icodialog"></i> ELIMINAR',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt"></i> Si, eliminar',
          action: function() {
            this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
            this.buttons.Eliminar.disable();
            $('.jconfirm-closeIcon').remove();
            $.post(base_url + "Usuarios/delete_socio", {
                id: id,
                nombre: n,
                action: "eliminar",
                token: t
              })
              .done(function(data) {
                alsup.close();
                if (data == 'error') {
                  alerta('error', data);
                } else {
                  alerta('exito', 'Socio eliminado correctamente');
                  updateUsuarios();
                }
              });
            return false;

          }
        }
      }
    });

  }

  function suspender_cliente(id, cliente) {
    var alsup = $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Esta seguro que desea suspender al cliente <br><b class="text-warning">' + atob(cliente) + '</b>',
      title: '<i class="far fa-question-circle fa-lg icodialog"></i> SUSPENDER',
      buttons: {
        Eliminar: {
          text: '<i class="fas fa-ban icodialog"></i> Si, suspender',
          action: function() {
            this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
            this.buttons.Eliminar.disable();
            $('.jconfirm-closeIcon').remove();
            $.post("clientes/suspender_cliente", {
                idcliente: id
              })
              .done(function(data) {
                alsup.close();
                data = JSON.parse(data);
                if (data.estado == 2) {
                  alerta('error', 'Error al suspender al cliente.');
                } else {
                  if (data.mensaje != null) {
                    alerta('warning', data.mensaje);
                  } else {
                    alerta('exito', 'Cliente suspendido correctamente');
                  }
                  updateusuarios();
                }
              });
            return false;

          }
        }
      }
    });


  }

  function cambiarstatus(id, t, n, s, s1) {
    var alsup = $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Esta seguro que desea cambiar el status del socio <br><b class="text-warning">' + n + '</b> a ' + s,
      title: '<i class="far fa-question-circle fa-lg icodialog"></i>' + s1,
      buttons: {
        Eliminar: {
          text: '<i class="fas fa-check icodialog"></i> Si, ' + s1,
          action: function() {
            this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
            this.buttons.Eliminar.disable();
            $('.jconfirm-closeIcon').remove();
            $.post("usuarios/update_status", {
                id: id,
                token: t,
                nombre: n,
                status: s
              })
              .done(function(data) {
                alsup.close();
                if (data == 'error') {
                  alerta('error', data);
                } else {
                  alerta('exito', 'Status de socio modificado correctamente');
                  $('[data-toggle="popover"]').popover('toggle')
                  updateusuarios();
                }
              });
            return false;

          }
        }
      }
    });
  }

  function activar_cliente(id, cliente) {
    var alsup = $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Esta seguro que desea Activar al cliente <br><b class="text-warning">' + atob(cliente) + '</b>',
      title: '<i class="far fa-question-circle fa-lg icodialog"></i> ACTIVAR !',
      buttons: {
        Eliminar: {
          text: '<i class="fas fa-check icodialog"></i> Si, Activar',
          action: function() {
            this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
            this.buttons.Eliminar.disable();
            $('.jconfirm-closeIcon').remove();
            $.post("clientes/activar_cliente", {
                idcliente: id,
              })
              .done(function(data) {
                alsup.close();
                data = JSON.parse(data);
                if (data.estado == 2) {
                  alerta('error', 'Error al activar al cliente.');
                } else {
                  if (data.mensaje != null) {
                    alerta('warning', data.mensaje);
                  } else {
                    alerta('exito', 'Cliente activado correctamente');
                  }
                  updateusuarios();
                }
              });
            return false;

          }
        }
      }
    });


  }

  function delete_cliente(id, cliente) {

    var alsup = $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Esta seguro que desea eliminar al cliente <br><b class="text-warning">' + atob(cliente) + '</b><br> Los datos del clientes serán eliminados junto a todos sus servicios registrados(mikrotik).<br> Datos como facturas,pagos,trafico,ticket de soporte,etc serán conservados.<br><br><div class="form-group row"><label class="col-form-label col-md-7 text-right" style="color:#fff"><b>Eliminar todos los datos</b></label><div class="col-md-5 text-left"><label class="switch"><input class="chk" type="checkbox" name="removeall"><span class="slider round"></span></label></div></div>',
      title: '<i class="far fa-question-circle fa-lg icodialog"></i> ELIMINAR',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt"></i> Si, eliminar',
          action: function() {
            this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
            this.buttons.Eliminar.disable();
            $('.jconfirm-closeIcon').remove();
            $.post("clientes/delete_cliente", {
                idcliente: id,
                all: $('*[name="removeall"]').is(':checked')
              })
              .done(function(data) {
                data = JSON.parse(data);
                console.log(data);
                alsup.close();
                if (data.estado == 2) {
                  alerta('error', 'Error al eliminar al usuario');
                } else {
                  if (data.mensaje != null) {
                    alerta('warning', data.mensaje);
                  } else {
                    alerta('exito', 'Cliente eliminado correctamente');
                  }
                  updateusuarios();
                }
              });
            return false;

          }
        }
      }
    });


  }

  function retirar_cliente(id, cliente) {

    var alsup = $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Esta seguro que desea retirar al cliente <br><b class="text-warning">' + atob(cliente) + '</b><br> Todos los servicios del cliente serán eliminados del sistema y del Mikrotik.<br>Los Productos en calidad de comodato serán devueltos a almacén.<br><br><div class="form-group row"><label class="col-form-label col-md-7 text-right" style="color:#fff"><b>Eliminar registro de instalación</b></label><div class="col-md-5 text-left"><label class="switch"><input class="chk" type="checkbox" name="removeinstall"><span class="slider round"></span></label></div></div>',
      title: '<i class="far fa-question-circle fa-lg icodialog"></i> RETIRAR',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt"></i> Si, retirar',
          action: function() {
            this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
            this.buttons.Eliminar.disable();
            $('.jconfirm-closeIcon').remove();
            $.post("clientes/retirar_cliente", {
                idcliente: id,
                all: $('*[name="removeinstall"]').is(':checked')
              })
              .done(function(data) {
                alsup.close();
                if (data == 2) {
                  alerta('error', 'Error al retirar al cliente.');
                } else {
                  alerta('exito', 'Cliente retirado correctamente');
                  updateusuarios();
                }
              });
            return false;

          }
        }
      }
    });


  }


  $(document).ready(function() {

    configtable('#list-usuarios', 'usuarios');
    usuarios = $('#list-usuarios').DataTable({
      "ajax": "clientes/get_clientes_json?action=lista&tipo=TODOS",
      "deferRender": true,
      "idDataTables": "1",
      "aoColumns": [{
          mData: 'id',
          'render': function(data, type, full, meta) {
            return '<input type="checkbox" style="font-size: 19px;" name="slactivo[]" value="' + data + '">';
          },
          sClass: 'dt-center'
        },
        {
          mData: 'id'
        },
        {
          mData: 'nombres'
        },
        {
          mData: 'sucursal'
        },
        
    
        {
          mData: 'domicilio_completo'
        },
        
        {
          mData: 'movil'
        },
        
        {
          mData: 'correo'
        },
        
        {
          mData: 'id',
          'render': function(data, type, full, meta) {
            var coltool = data.split('-');
            var permiso = full['tipoacceso'];
            var nombre = full['nombre'];
            if (permiso > 1) {
              tools = "";
              if (full.estado == 'ACTIVO' || full.estado == 'SUSPENDIDO') {
                icono = full.estado == "ACTIVO" ? 'fas fa-user-times' : 'fas fa-ban';
                delettxt = '<a data-toggle="tooltip" title="" class="btn btn-default btn-icon btn-sm" onclick="delete_cliente(\'' + coltool[0] + '\',\'' + nombre + '\')" data-original-title="Eliminar">\
                    <i class="far fa-trash-alt"></i>\
                  </a>';
                tools = '\
                <div>\
                  <a href="#clientes/detalleuser?id=' + coltool[0] + '" class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Editar Cliente">\
                    <span class="far  fa-edit"></span>\
                  </a>';
                if (permiso == 3) {
                  tools += delettxt;
                }

                
            
              } else {
                tools = '\
                <div>\
                  <a href="#clientes/detalleuser?estado=retirado&id=' + coltool[0] + '" class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Editar Cliente">\
                    <span class="far  fa-edit"></span>\
                  </a>\
                  <a data-toggle="tooltip" title="" class="btn btn-default btn-icon btn-sm" onclick="delete_cliente(\'' + coltool[0] + '\',\'' + nombre + '\')" data-original-title="Eliminar">\
                    <i class="far fa-trash-alt"></i>\
                  </a>\
                  </div>';
              }
              return tools;
            } else {
              tools = '\
                <div>\
                  <a href="#clientes/detalleuser?id=' + coltool[0] + '" class="btn btn-icon btn-sm btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Editar Cliente">\
                    <span class="far  fa-edit"></span>\
                  </a>\
                </div>'
              return tools;
            }
          },
          sClass: 'text-center'
        }
      ],

      initComplete: function(oSettings, json) {

        this.api().columns().every(function() {

          var state = this.state.loaded();
          var column = this;

          if (column[0] == 0) {
            return;
          }

          var txtsrh = '';
          if (state) {
            txtsrh = state.columns[column[0]].search.search;
          }

          $('<input type="text" placeholder="Buscar" class="form-control" value="' + txtsrh + '">')
            .appendTo($(column.footer()).empty())
            .on('keyup', function() {

              column
                .search($(this).val())
                .draw();
            });

        });




        $('.not-search-input').html('');

        //------->Button Col
        $('#list-usuarios_wrapper .dt-buttons').append('');
        if (<?= $tipoacceso; ?> >= 2) {
          $("#list-usuarios_wrapper div.tooltablas").html('<div class="grupotabla  m-r-5">\
  		<a class="btn btn-default" href="#clientes/nuevo_cliente"><i class="fas fa-plus"></i> Nuevo</a>\
		</div>');
        } else {
          $("#list-usuarios_wrapper div.tooltablas").html('<div class="grupotabla  m-r-5">\
		</div>');
        }


        $("#list-usuarios_wrapper div.tooltablas div.grupotabla")
          .append('<select class="slstatesuser form-control" name="tblestado" Onchange="loadtipo();">' +
            '<option value="TODOS">TODOS</option>' +
            '<option value="ACTIVO">Usuarios ACTIVOS</option>' +
            '<option value="SUSPENDIDO">Usuarios SUSPENDIDOS</option>' +
            '<option value="RETIRADO">Usuarios RETIRADOS</option>' +
            '</select>');


        $(".slstatesuser option[value='TODOS']").prop('selected', true);

        $('[name="tblestado"]').select2();
        if ($('[name="tblestado"]').val() == 1) {
          $('.allsp').show();
          $('.allat').hide();
        } else if ($('[name="tblestado"]').val() == 2) {
          $('.allsp').hide();
          $('.allat').show();
        } else {
          $('.allsp').hide();
          $('.allat').hide();
        }
      }
    }).on('processing.dt', function(e, settings, processing) {
      if (processing) {
        loaderin('.panel-Usuarios');
      } else {
        loaderout('.panel-Usuarios');
      }
    }).on('draw', function() {
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
    });

    loadtipo = function() {
      if ($('[name="tblestado"]').val() == 1) {
        $('.allsp').show();
        $('.allat').hide();
      } else if ($('[name="tblestado"]').val() == 2) {
        $('.allsp').hide();
        $('.allat').show();
      } else {
        $('.allsp').hide();
        $('.allat').hide();
      }

      usuarios.ajax.url("clientes/get_clientes_json?action=lista&tipo=" + $('[name="tblestado"]').val()).load();
    }

    updateusuarios = function() {
      usuarios.ajax.reload(null, false);
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
    }


  });

  if (localStorage.caja) {
    $('#collapseactivo').collapse();
  }
  loadSettings();

  //------> Filtro
  function filtro_a(columna, texto) {
    if (texto == "") {
      usuarios.search('').columns().search('').draw();
    } else {
      columna = parseInt(columna);
      usuarios.columns(columna).search(texto).draw();
    }
    saveSettings();
  }

  function loadSettings() {
    $('.select-filter-a option[value="' + localStorage.columna + '"]').prop('selected', true);
    $('.txt-filtro-a').val(localStorage.caja);
  }

  function saveSettings() {
    localStorage.columna = $('.select-filter-a').val();
    localStorage.caja = $('.txt-filtro-a').val();
  }

  if ($("#searchcliente").autocomplete("instance")) {
    $("#searchcliente").autocomplete("destroy");
    $("#searchcliente").removeData('autocomplete');
  }

  App.restartGlobalFunction();
  App.setPageTitle('Usuarios');
</script>