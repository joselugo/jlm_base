<div class="panel panel-blue">
  <div class="panel-heading">
    <h4 class="panel-title"><b>GESTIÓN DE PROVEEDORES</b></h4>
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onclick="updateoperadores()"><i class="fas fa-sync-alt"></i></a>
    </div>
  </div>
  <div class="panel-body border-panel">

    <table id="data-proveedores" class="display nowrap table table-striped table-bordered dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%" role="grid" aria-describedby="data-operadores_info" style="width: 100%;">
      <thead>
        <tr role="row">
          <th>ID</th>
          <th>Proveedor</th>
          <th>Email</th>
          <th>Telefono</th>
          <th>Direccion</th>
          <th class="all text-center sorting_disabled"></th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<div class="modal" id="add" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="titulo" class="modal-title"></h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
      </div>
      <form id="cat-new-form" method="post" class="form-horizontal">
        <input class="form-control" id="almacen[id]" type="hidden" placeholder="Sytem SAC" required="">
        <input class="form-control" id="almacen[token]" type="hidden" placeholder="Sytem SAC" required="">
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group input-group-sm">
                <label>Proveedor</label>
                <input class="form-control" id="almacen[proveedor]" type="text" placeholder="Sytem SAC" required="">
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group input-group-sm">
                <label>Email</label>
                <input class="form-control" id="almacen[mail]" type="email">
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group input-group-sm">
                <label>Teléfono</label>
                <input class="form-control" id="almacen[telefono]" type="text">
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group input-group-sm">
                <label>Dirección</label>
                <textarea id="almacen[direccion]" class="form-control" rows="2" style="height: 60px"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <a href="javascript:;" class="btn-azul" data-dismiss="modal">Cerrar</a>
          <button style="<?= $permiso >= 2 ? "" : "display:none"; ?>" id="button" type="submit" class="btn-azul"></button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  var form = document.getElementById('cat-new-form');
  var proveedor = document.getElementById('almacen[proveedor]')
  var email = document.getElementById('almacen[mail]')
  var telefono = document.getElementById('almacen[telefono]')
  var direccion = document.getElementById('almacen[direccion]')
  var idupdate = document.getElementById('almacen[id]')
  var token = document.getElementById('almacen[token]')
  var titulo = document.getElementById('titulo')
  var button = document.getElementById('button')


  inicio()

  function inicio() {

    sala = 'PROVEEDORES'
    socket.emit('join_room', sala);
  }
  socket.on('get_db_proveedores', () => {
    updateoperadores()
  })

  function update_proveedor(Id, Proveedor, Email, Telefono, Direccion, Token) {
    titulo.innerHTML = 'Editar proveedor'
    button.innerHTML = "Actualizar"
    idupdate.value = Id
    proveedor.value = Proveedor
    email.value = Email
    telefono.value = Telefono
    direccion.value = Direccion
    token.value = Token
  }

  function new_proveedor() {
    titulo.innerHTML = 'Nuevo proveedor'
    button.innerHTML = "Registrar"
  }

  function delete_proveedor(id, session, nombre) {

    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Esta seguro que desea eliminar este proveedor?',
      title: '<i class="far fa-question-circle fa-lg icodialog"></i> Retirar',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt icodialog"></i> Si,retirar', // With spaces and symbols
          action: function() {
            url = "almacen/delete_proveedor"
            $.ajax({
              url: url,
              type: 'POST',
              data: {
                id,
                session,
                nombre
              },
              success: function(result) {
                if (result == 1) {
                  socket.emit('update_db_proveedores')
                  alerta('exito', 'Proveedor retirado correctamente');
                  updateoperadores()
                } else {
                  alerta('error, intente de nuevo');
                }
              }
            });

          }
        }
      }
    });
  }

  form.addEventListener('submit', function(e) {
    url = ""
    e.preventDefault();
    idupdate.value == "" ? url = "almacen/new_proveedor" : url = "almacen/update_proveedor";
    $.ajax({
      url: url,
      type: 'POST',
      data: {
        'token': token.value,
        'id': idupdate.value,
        'proveedor': proveedor.value,
        'email': email.value,
        'telefono': telefono.value,
        'direccion': direccion.value
      },
      success: function(result) {
        if (result == 1) {
          if (idupdate.value == "") {
            socket.emit('update_db_proveedores')
            alerta('exito', 'Proveedor agregado correctamente');
          } else {
            socket.emit('update_db_proveedores')
            alerta('exito', 'Proveedor actualizado correctamente');
          }
          vaciar()
          updateoperadores()
          $('#add').modal('hide');
        } else {
          alerta('error, intente de nuevo');
        }
      }
    });
  })

  function vaciar() {
    proveedor.value = ""
    email.value = ""
    telefono.value = ""
    direccion.value = ""
    idupdate.value = ""
  }

  $(function() {
    App.setPageTitle('Gestión personal');
    configtable('#data-proveedores', 'rutas');

    var operadores = $('#data-proveedores').DataTable({
      "ajax": base_url + "almacen/get_proveedores",
      "deferRender": true,
      "idDataTables": "1",
      "aoColumns": [{
          mData: 'id',
          sWidth: '20px'
        },
        {
          mData: 'proveedor'
        },
        {
          mData: 'email'
        },
        {
          mData: 'telefono'
        },
        {
          mData: 'direccion'
        }, {
          mData: 'tool',
          sClass: 'text-center',
          sWidth: '30px'
        }
      ],
      initComplete: function(oSettings, json) {
        if (<?= $permiso; ?> >= 2) {
          $("#data-proveedores_wrapper .dt-buttons")
            .append('<div class="btn-group">\
        <button id="create" onclick="new_proveedor()" class="btn btn-success" data-toggle="modal" data-target="#add"><i class="fas fa-plus"></i> Nuevo</button>\
      </div>');
        }
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

    updateoperadores = function() {
      operadores.ajax.reload(null, false);
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
    }

  })
</script>