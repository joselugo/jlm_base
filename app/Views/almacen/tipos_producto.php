<div class="panel panel-blue">
  <div class="panel-heading">
    <h4 class="panel-title"><b>GESTIÓN DE CATEGORIAS</b></h4>
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onclick="update_tipos_producto()"><i class="fas fa-sync-alt"></i></a>
    </div>
  </div>
  <div class="panel-body border-panel">
    <table id="data-tipo-producto" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%">
      <thead>
        <tr role="row">
          <th>ID</th>
          <th>Producto</th>
          <th>Descripción</th>
          <th>Tipo</th>
          <th>Impuesto (%)</th>
          <th class="all sorting_disabled">Disponibles <?= $almacen_permiso >= 2 ? "<a onclick=\"getmodal('ajustes/modal_ajustes_almacen?extra=2','Personalizar almacen','lg');\" data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" data-original-title=\"Personalizar colores\">
              <i class=\"fas fa-paint-brush\"></i>
            </a>" : ""; ?></th>
          <th class="all sorting_disabled" data-orderable="false"></th>
        </tr>
      </thead>
    </table>
  </div>
</div>

<div class="modal" id="modaltmpa" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="titulo" class="modal-title">Nueva categoría</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
      </div>
      <form id="cat-new-form" method="post" class="form-horizontal">
        <input class="form-control" id="almacen[idupdate]" type="hidden" placeholder="Sytem SAC" required="">
        <input class="form-control" id="almacen[token]" type="hidden" placeholder="Sytem SAC" required="">
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group input-group-sm">
                <label>Categoría</label>
                <select id="almacen[tipo]" required="" style="width: 100%" class="slpro" data-placeholder="Seleccionar Tipo">
                  <?= $categorias ?>
                </select>
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group input-group-sm">
                <label>Nombre Producto</label>
                <input class="form-control" id="almacen[producto]" type="text" placeholder="Router Ubiquiti" value="" required="">
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group input-group-sm">
                <label>Valor Impuesto (%)</label>
                <input class="form-control" id="almacen[impuesto]" type="number" value="0" required="" style="width: 80px">
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group input-group-sm">
                <label>Clave/Código SAT,SUNAT,etc.</label>
                <input class="form-control" id="almacen[clave_invoice]" type="text">
                <small class="text-warning">*Esto código es utilizado para Facturación Electrónica</small>
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group input-group-sm">
                <label>Unidad de medida</label>
                <select id="almacen[medida]" require="" style="width: 100%" class="slpro2" data-placeholder="Seleccionar Tipo">
                  <?= $unidad ?>
                </select>
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group input-group-sm">
                <label>Descripción <small class="text-warning">(* Texto para factura)</small></label>
                <textarea id="almacen[descripcion]" class="form-control" rows="2" style="height: 60px"></textarea>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <a href="javascript:;" class="btn-azul" data-dismiss="modal">Cerrar</a>
          <button style="display:<?= $permiso >= 2 ? "block" : "none"; ?>" id="button" type="submit" class="btn-azul">Registrar</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  var form = document.getElementById('cat-new-form');
  var tipo = document.getElementById('almacen[tipo]')
  var producto = document.getElementById('almacen[producto]')
  var impuesto = document.getElementById('almacen[impuesto]')
  var clave = document.getElementById('almacen[clave_invoice]')
  var medida = document.getElementById('almacen[medida]')
  var descripcion = document.getElementById('almacen[descripcion]')
  var idupdate = document.getElementById('almacen[idupdate]')
  var token = document.getElementById('almacen[token]')

  var titulo = document.getElementById('titulo')
  var button = document.getElementById('button')
  $('.slpro').select2()
  $('.slpro2').select2()

  function update_tipo_producto(e, Id, Tipo, Impuesto, Clave, Medida, Token) {
    $(".slpro").val(capitalizacion(Tipo)).change();
    $(".slpro2").val(Medida).change();
    titulo.innerHTML = 'Editar Categoria'
    button.innerHTML = "Actualizar"
    idupdate.value = Id
    producto.value = e.parentElement.parentElement.childNodes[1].innerHTML
    descripcion.value = e.parentElement.parentElement.childNodes[2].innerHTML
    tipo.value = capitalizacion(Tipo)
    impuesto.value = Impuesto
    clave.value = Clave
    medida.value = medida
    token.value = Token
    console.log("sasa");

    $('#modaltmpa').modal('show');
  }

  function new_tipo_producto() {
    $(".slpro").val("Producto").change();
    $(".slpro2").val("metro").change();
    producto.value = ""
    impuesto.value = 0
    tipo.value = "Producto"
    medida.value = "metros"
    titulo.innerHTML = 'Nueva Categoria'
    button.innerHTML = "Agregar"
    descripcion.value = "Producto Serial {producto_serial} con Nº mac {producto_mac}"
  }

  function delete_tipo_producto(id, session, nombre) {
    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Esta seguro que desea retirar esta categoria?',
      title: '<i class="far fa-question-circle fa-lg icodialog"></i> Retirar',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt icodialog"></i> Si,retirar', // With spaces and symbols
          action: function() {
            url = "almacen/delete_tipo_producto"
            $.ajax({
              url: url,
              type: 'POST',
              data: {
                id,
                session,
                nombre
              },
              success: function(result) {
                console.log(result);
                if (result == 1) {
                  alerta('exito', 'Categoria retirada correctamente');
                  update_tipos_producto()
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
    idupdate.value == "" ? url = "almacen/new_tipo_producto" : url = "almacen/update_tipo_producto";
    $.ajax({
      url: url,
      type: 'POST',
      data: {
        'token': token.value,
        'id': idupdate.value,
        'tipo': tipo.value.toLowerCase(),
        'producto': producto.value,
        'impuesto': impuesto.value,
        'clave': clave.value,
        'medida': medida.value,
        'descripcion': descripcion.value
      },
      success: function(result) {
        if (result == 1) {
          if (idupdate.value == "") {
            alerta('exito', 'Categoria agregada correctamente');
          } else {
            alerta('exito', 'Categoria actualizada correctamente');
          }
          vaciar()
          update_tipos_producto()
          $('#modaltmpa').modal('hide');
        }
        if (result == 'error') alerta('error, Acceso denegado');
      }
    });
  })

  function vaciar() {
    token.value = ""
    idupdate.value = ""
    tipo.value = ""
    producto.value = ""
    impuesto.value = ""
    clave.value = ""
    medida.value = ""
    descripcion.value = ""

  }

  $(function() {

    App.setPageTitle('Gestión productos');
    configtable('#data-tipo-producto', 'productos');

    var operadores = $('#data-tipo-producto').DataTable({
      "ajax": base_url + "almacen/get_tipos_producto",
      "deferRender": true,
      "idDataTables": "1",
      "aoColumns": [{
          mData: 'id',
          sWidth: '20px'
        },
        {
          mData: 'producto',
        },
        {
          mData: 'descripcion',
        },
        {
          mData: 'tipo',
        },

        {
          mData: 'impuesto',
          sClass: 'text-center'
        },
        {
          mData: 'disponibles',
          sClass: 'text-center'
        },
        {
          mData: 'tool',
          sClass: 'text-center',
          sWidth: '20px'
        }
      ],
      initComplete: function(oSettings, json) {
        if (<?= $permiso; ?> >= 2) {
          $("#data-tipo-producto_wrapper .dt-buttons")
            .append('<div class="btn-group">\
          <button id="create" onclick="new_tipo_producto()" class="btn btn-success" data-toggle="modal" data-target="#modaltmpa"><i class="fas fa-plus"></i> Nuevo</button>\
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

    update_tipos_producto = function() {
      operadores.ajax.reload(null, false);
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
      var buttonu = document.getElementsByClassName('buttonu');
    }

  })

  function capitalizacion(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }
</script>