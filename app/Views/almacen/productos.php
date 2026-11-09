<div style="font-size:.75rem;" class="panel panel-blue">
  <div class="panel-heading">
    <h4 class="panel-title"><b>ALMACEN</b></h4>
    <div class="panel-heading-btn">
    </div>
  </div>
  <div class="panel-body border table-responsive">
    <div class="panel panel-default productos-a-cliente">
      <div style="background:#f2f4f5" class="panel-heading">
        <h4 class="panel-title"><b>PRODUCTOS</b></h4>
        <div class="panel-heading-btn">
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onclick="update_productos()"><i class="fas fa-sync-alt"></i></a>
        </div>
      </div>
      <div class="panel-body border table-responsive">
        <table id="data-producto" class="display nowrap table table-striped table-bordered dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%" role="grid" aria-describedby="data-operadores_info" style="width: 100%;">
          <thead>
            <tr role="row">
              <th>Id</th>
              <th>Producto</th>
              <th>Proveedor</th>
              <th>Nº Serie</th>
              <th>No. Mac</th>
              <th>Costo</th>
              <th>Estado</th>
              <th>Cliente</th>
              <th>Ingreso</th>
              <th>Salida</th>
              <th class="all sorting_disabled" data-orderable="false" rowspan="1" colspan="1" data-column-index="10" style="width: 435px;" aria-label=""></th>
            </tr>
          </thead>
          <tfoot>
            <th class="filterhead"></th>
            <th class="filterhead"></th>
            <th class="filterhead"></th>
            <th class="filterhead"></th>
            <th class="filterhead"></th>
            <th class="filterhead"></th>
            <th class="filterhead"></th>
            <th class="filterhead"></th>
            <th class="filterhead"></th>
            <th class="filterhead"></th>
            <th class="filterhead"></th>
          </tfoot>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
    <div class="panel panel-default productos-a-cliente">
      <div style="background:#f2f4f5" class="panel-heading">
        <h4 class="panel-title"><b>ACCESORIOS</b></h4>
        <div class="panel-heading-btn">
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
          <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onclick="update_accesorios()"><i class="fas fa-sync-alt"></i></a>
        </div>
      </div>
      <div class="panel-body border table-responsive">
        <table id="data-accesorio" class="display nowrap table table-striped table-bordered dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%" role="grid" aria-describedby="data-operadores_info" style="width: 100%;">
          <thead>
            <tr role="row">
              <th>ID</th>
              <th>ACCESORIO</th>
              <th>COSTO UNITARIO</th>
              <th>CANTIDAD <?= $almacen_permiso >= 2 ? "<a onclick=\"getmodal('ajustes/modal_ajustes_almacen?extra=1','Personalizar almacen','lg');\" data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" data-original-title=\"Personalizar colores\">
              <i class=\"fas fa-paint-brush\"></i>
            </a>" : ""; ?></th>
              <th></th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="modaltmp2" role="dialog" style="padding-right: 17px;">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="titulo" class="modal-title">Nuevo Producto</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
      </div>
      <form id="cat-new-form" method="post" action="almacen/new_producto" class="form-horizontal">
        <input class="form-control" id="almacen[id]" type="hidden" placeholder="Sytem SAC" required="">
        <input class="form-control" id="almacen[token]" type="hidden" placeholder="Sytem SAC" required="">
        <div class="modal-body">
          <div class="row">

            <div class="col-sm-12">
              <div class="form-group input-group-sm">
                <label>Sucursal</label>
                <select id="almacen[sucursal]" required="" style="width: 100%" class="slpro sucur">
                  <?= $select_sucursales ?>
                </select>
              </div>
            </div>

            <div class="col-sm-12">
              <div class="form-group input-group-sm">
                <label>Categoría</label>
                <select id="almacen[categoria]" required="" style="width: 100%" class="slpro">
                  <?= $select_categorias ?>
                </select>
              </div>
            </div>


            <div class="col-sm-12">
              <div class="form-group input-group-sm">
                <label>Proveedor</label>
                <select id="almacen[idproveedor]" style="width: 100%" class="slpro2 " data-select2-id="5013" tabindex="-1" aria-hidden="true">
                  <?= $select_proveedores ?>
                </select>
              </div>
            </div>


            <div class="col-sm-12">
              <div class="form-group input-group-sm">
                <label>Precio de Venta o Alquiler</label>
                <input class="form-control" id="almacen[costo]" type="number" step="0.01" placeholder="Precio de venta" value="0" required="">
              </div>
            </div>

            <div class="col-sm-12">
              <div class="form-group input-group-sm">
                <label>Nº Mac</label>
                <input type="hidden" name="almacen[catid]" value="producto">
                <input class="form-control" id="almacen[mac_producto]" type="text" placeholder="00:23:12:34:12:10">
              </div>
            </div>

            <div class="col-sm-12">
              <div class="form-group input-group-sm">
                <label>Nº Serial /código</label>
                <input class="form-control" id="almacen[serial_producto]" type="text" placeholder="SN983763527137">
              </div>
            </div>

          </div>
        </div>
        <div class="modal-footer">
          <a href="javascript:;" class="btn-azul" data-dismiss="modal">Cerrar</a>
          <button id="button" type="submit" class="btn-azul">Registrar</button>
        </div>

      </form>
    </div>
  </div>
</div>
<div class="modal show" id="import" role="dialog" style="padding-right: 17px;">
  <div class="modal-dialog modal-dialog-centered modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Importar EXCEL</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
      </div>
      <style type="text/css">
        .import-list {
          display: block;
          list-style: none;
          margin: 0;
          font-size: 12px;
        }

        .import-list__item {
          display: -ms-flexbox;
          display: flex;
        }

        .import-list__item__nav,
        .import-list__item__number {
          display: -ms-flexbox;
          display: flex;
          width: 6.21429rem;
          -ms-flex-pack: center;
          justify-content: center;
          position: relative;
        }

        .import-list__item__nav,
        .import-list__item__number {
          display: -ms-flexbox;
          display: flex;
          width: 6.21429rem;
          -ms-flex-pack: center;
          justify-content: center;
          position: relative;
        }

        .import-list__item__number {
          z-index: 1;
          -ms-flex-align: center;
          align-items: center;
          width: 30px;
          height: 30px;
          font-size: 17px;
          line-height: 1;
          font-weight: 700;
          background: #fff;
          color: #1dabe3;
          border: .14286rem solid #1dabe3;
          border-radius: 100%;
        }

        .import-list__item__content {
          width: calc(100% - 6.21429rem);
          padding-right: 1.78571rem;
          padding-bottom: 1.42857rem;
          font-weight: 400;
          font-size: 12px;
          line-height: 1.25;
          color: #5b5b5b;
        }

        .import-list__item__content__heading {
          font-weight: 600;
          font-size: 14px;
          line-height: 1.2;
          color: #353535;
          margin-bottom: .21429rem;
        }

        .import-list__item__dot {
          display: block;
          position: relative;
          z-index: 1;
          width: 1.07143rem;
          height: 1.07143rem;
          background: #e9e9e9;
          border-radius: 100%;
        }

        .import-list__item__line {
          display: block;
          width: 1px;
          position: relative;
          background: #1dabe3;
          top: 17px;
          left: -15px;
        }
      </style>

      <form id="import-form" method="post" action="almacen/importar_excel" class="form-horizontal" enctype="multipart/form-data">
        <div class="modal-body">
          <div>
            <div class="text-errori-mp alert alert-danger m-b-10" style="display: none">
              <h4>ERRORES DE IMPORTACIÓN</h4>
              <ul class="ol-error text-left">
              </ul>
            </div>

            <ol class="import-list">
              <li class="import-list__item">
                <div class="import-list__item__nav p-b-20">
                  <div class="import-list__item__number">1</div>
                  <div class="import-list__item__line"></div>
                </div>
                <div class="import-list__item__content p-b-20">
                  <div class="import-list__item__content__heading p-t-10">Descargue el <a href="<?= base_url(); ?>public/uploads/ejemplo_xlsx/productos.xlsx">Formato XLSX</a> de muestra</div>
                </div>
              </li>

              <li class="import-list__item">
                <div class="import-list__item__nav">
                  <div class="import-list__item__number">2</div>
                  <div class="import-list__item__line"></div>
                </div>
                <div class="import-list__item__content">
                  <div class="import-list__item__content__heading">Prepare sus datos</div>
                  <div>
                    Asegúrese de incluir los campos obligatorios <span class="text-danger">(*)</span>.<br>
                    No se puede incluir productos ya existentes en el sistema, si incluye algún Nº de mac o serie repetido será excluido de la importación.
                  </div>

                </div>
              </li>

              <li class="import-list__item">
                <div class="import-list__item__nav">
                  <div class="import-list__item__number">3</div>
                  <div class="import-list__item__line import-list__item__line--sub"></div>
                </div>
                <div class="import-list__item__content">
                  <div class="row">
                    <label class="btn btn-info btn-md" for="imp-pro">
                      <input id="verificacion" name="verificacion" type="hidden" value=0>
                      <input id="imp-pro" name="imp-pro" type="file" accept=".xlsx" style="display:none">
                      <i class="fas fa-upload"></i> Elija el archivo de productos para cargar
                    </label>
                    <div class="text-success txt-imp p-5"></div>
                  </div>
                </div>
              </li>


              <li class="import-list__item">
                <div class="import-list__item__nav p-b-20">
                  <div class="import-list__item__number">4</div>
                  <div class="import-list__item__line"></div>
                </div>
                <div class="import-list__item__content p-b-20">
                  <div class="import-list__item__content__heading p-t-10">Elija una sucursal:</div>

                  <select id="excel_sucursal" name="excel_sucursal" required="" style="width: 100%" class="slpro sucur">
                    <?= $select_sucursales ?>
                  </select>

                </div>
              </li>

            </ol>
          </div>
        </div>

        <div class="modal-footer">
          <a href="javascript:;" class="btn-azul" data-dismiss="modal">Cancelar</a>
          <button id="button_import" type="submit" class="btn-azul btn-import" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Importando">Importar</button>
        </div>

      </form>



    </div>
  </div>
</div>
<div class="modal show" id="modal_accesorio" role="dialog" style="padding-right: 17px;">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 id="accesorio_titulo" class="modal-title">Nuevo Accesorio</h5><button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
      </div>
      <form id="acc-new-form" method="post" class="form-horizontal">
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group input-group-sm">
                <label>Sucursal</label>
                <select id="accesorio[sucursal]" required="" style="width: 100%" class="slpro sucur2">
                  <?= $select_sucursales ?>
                </select>
              </div>
            </div>

            <div class="col-sm-12">
              <div id="select_hidden" class="form-group input-group-sm">
                <label>Categoría</label>
                <select id="accesorio_categoria" required="" style="width: 100%" class="slpro3 select2-hidden-accessible" data-placeholder="Seleccionar categoría" data-select2-id="40" tabindex="-1" aria-hidden="true">
                  <?= $select_categorias_accesorios ?>
                </select>
              </div>
            </div>

            <div class="col-sm-12">
              <div class="form-group input-group-sm">
                <label>Precio Unitario</label>
                <input type="hidden" id="accesorio_token">
                <input type="hidden" id="id_accesorio">
                <input class="form-control" id="accesorio_costo" type="number" step="0.01" placeholder="Precio de venta" value="0" required="">
              </div>
            </div>

            <div class="col-sm-12">
              <div class="form-group input-group-sm">
                <label>Unidades o Metros</label>
                <input class="form-control" id="accesorio[cantidad]" type="number" step="0.01" placeholder="10" required="">
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <a href="javascript:;" class="btn-azul" data-dismiss="modal">Cerrar</a>
          <button id="accesorio_button" type="submit" class="btn-azul">Registrar</button>
        </div>

      </form>
    </div>
  </div>
</div>

<script>
  var form = document.getElementById('cat-new-form');
  var id = document.getElementById('almacen[id]')
  var token = document.getElementById('almacen[token]')
  var categoria = document.getElementById('almacen[categoria]')
  var idproveedor = document.getElementById('almacen[idproveedor]')
  var costo = document.getElementById('almacen[costo]')
  var mac = document.getElementById('almacen[mac_producto]')
  var serial = document.getElementById('almacen[serial_producto]')
  var sucursal = document.getElementById('almacen[sucursal]')
  var accesorio_costo = document.getElementById('accesorio_costo')
  var accesorio_cantidad = document.getElementById('accesorio[cantidad]')
  var accesorio_sucursal = document.getElementById('accesorio[sucursal]')
  var form2 = document.getElementById('acc-new-form')
  var id_accesorio = document.getElementById('id_accesorio')
  var accesorio_token = document.getElementById('accesorio_token')
  var accesorio_categoria = document.getElementById('accesorio_categoria')
  var select_hidden = document.getElementById('select_hidden')

  var titulo = document.getElementById('titulo')
  var button = document.getElementById('button')
  var button_import = document.getElementById('button_import')
  var accesorio_button = document.getElementById('accesorio_button')
  var accesorio_titulo = document.getElementById('accesorio_titulo')

  button_import.style.display = "none"
  $('.slpro').select2()
  $('.slpro2').select2()
  $('.slpro3').select2()
  $('.btn-import').prop('disabled', true).html('Importar');
  configtable('#data-producto');
  configtable('#data-accesorio');

  //------------producto------------
  function update_producto(Id, Nombre) {
    select_hidden.style.display = "none"
    $.post("almacen/get_producto", {
        "id": Id,
      })
      .done(function(data) {
        data = JSON.parse(data)
        if (data.permiso >= 2) {
          $("#button").show();
          $("#accesorio_button").show();
        } else {
          $("#button").hide();
          $("#accesorio_button").hide();
        }
        if (data.tipo == "producto") {

          titulo.innerHTML = 'Editar producto'
          button.innerHTML = "Actualizar"
          id.value = Id
          costo.value = data.costo
          mac.value = data.mac_producto
          serial.value = data.serial_producto
          idproveedor.value = data.idproveedor
          categoria.value = data.productoid
          $(".slpro2").val(data.idproveedor).change();
          $(".slpro").val(data.productoid).change();
          $(".sucur").val(data.sucursal).change();
          $('#modaltmp2').modal('show');
        }
        if (data.tipo == "accesorio") {
          accesorio_titulo.innerHTML = "Editar accesorio"
          accesorio_button.innerHTML = "Actualizar"
          $(".slpro3").val(data.productoid).change();
          $(".sucur2").val(data.sucursal).change();
          id_accesorio.value = Id
          accesorio_costo.value = data.costo
          accesorio_cantidad.value = data.cantidad
          $('#modal_accesorio').modal('show');
        }


      });
  }

  function new_producto() {
    titulo.innerHTML = 'Nuevo producto'
    button.innerHTML = "Registrar"
    id.value = "";
    mac.value = "";
    serial.value = ""
    costo.value = "";
    $(".slpro2").val(1).change();
    $(".slpro").val(1).change();
    $(".sucur").val(1).change();
  }

  function delete_producto(id, nombre) {

    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Esta seguro que desea eliminar este producto?',
      title: '<i class="far fa-question-circle fa-lg icodialog"></i> Retirar',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt icodialog"></i> Si,retirar', // With spaces and symbols
          action: function() {
            $.post("almacen/delete_producto", {
                id,
                nombre
              })
              .done(function(data) {
                if (data == 1) {
                  alerta('exito', 'Producto retirado correctamente');
                  update_al();
                  update_accesorios();
                } else(
                  alerta('error, Acceso denegado')
                )
              });

          }
        }
      }
    });

  }

  form.addEventListener('submit', function(e) {
    console.log(sucursal.value);
    url = ""
    e.preventDefault();
    id.value == "" ? url = "almacen/new_producto" : url = "almacen/update_producto";
    $.ajax({
      url: url,
      type: 'POST',
      data: {
        'token': token.value,
        'id': id.value,
        'categoria': categoria.value,
        'idproveedor': idproveedor.value,
        'costo': costo.value,
        'mac': mac.value,
        'serial': serial.value,
        'sucursal': sucursal.value,
      },
      success: function(result) {
        if (result == 1) {
          vaciar()
          alerta('exito', 'Producto agregado con exito')
          update_productos()
          $('#modaltmp2').modal('hide');
        } else {
          alerta('error, intente de nuevo');
        }
      }
    });
  })

  var productos = $('#data-producto').DataTable({
    "ajax": base_url + "almacen/get_productos?estado=0",
    "deferRender": true,
    "stateSave": false,
    "orderCellsTop": true,
    "idDataTables": "40",

    "aoColumns": [{
        mData: 'id',
        sWidth: '20px'
      },
      {
        mData: 'nombre'
      },
      {
        mData: 'proveedor'
      },
      {
        mData: 'serial_producto'
      },
      {
        mData: 'mac_producto'
      },
      {
        mData: 'costo'

      },
      {

        mData: 'estado'
      },
      {
        mData: 'usuario'
      },
      {
        mData: 'fecha_ingreso'
      },
      {
        mData: 'fecha_salida'
      },
      {
        mData: 'tool',
        sClass: 'text-center',
        sWidth: '30px'
      }
    ],
    initComplete: function(oSettings, json) {

      var api = this.api();

      $('.filterhead', api.table().footer()).each(function(i) {

        if (i == 0) {
          return;
        }

        if (i == 6) {
          return;
        }

        if (i == 10) {
          return;
        }

        var column = api.column(i);
        var select = $('<select style="width: 100%;" class="selecttable"><option value="">Cualquiera</option></select>')
          .appendTo($(this).empty())
          .on('change', function() {
            var val = $.fn.dataTable.util.escapeRegex(
              $(this).val()
            );

            column
              .search(val ? '^' + val + '$' : '', true, false)
              .draw();
          });

        column.data().unique().sort().each(function(d, j) {
          if (!d) {
            return;
          }
          select.append('<option value="' + d + '">' + d + '</option>');
        });
      });

      $('.selecttable').select2();


      /* $("#data-producto_wrapper div.tooltablas")
        .append('<div class="grupotabla btn-group btn-group-md"><button type="button" class="btn btn-default " data-toggle="modal" data-target="#modaltmp" onclick="new_producto();"><i class="fas fa-plus"></i> Nuevo</button><button type="button" class="btn btn-default p-l-8 p-r-8 " onclick="getmodal(\'ajax/almacen?action=import\',\'Importar EXCEL\',\'md\');"><i class="fas fa-upload"></i> Subir</button></div><span class="m-l-5"><select class="slp" name="pro-estado" Onchange="update_al;" data-placeholder="Estado" style="width: 130px">' +
          '<option></option>' +
          '<option value="0">Cualquiera</option>' +
          '<option value="1">No disponibles</option>' +
          '<option value="2">Disponible</option>' +
          '<option value="3">Comodato</option>' +
          '</select></span><span class="m-l-5"><select class="slp" data-placeholder="Categoría" name="pro-cat" Onchange="update_al();" style="width: 180px">' +
          '<option></option>' + json.categorias +
          '</select></span>');
      $('.slp').select2() */
      if (<?= $permiso; ?> >= 2) {
        botones = '<div class="grupotabla btn-group btn-group-md"><button type="button" class="btn btn-default " data-toggle="modal" data-target="#modaltmp2" onclick="new_producto();"><i class="fas fa-plus"></i> Nuevo</button>\
        <button type="button" class="btn btn-default p-l-8 p-r-8 " disabled data-toggle="modal" data-target="#import";"><i class="fas fa-upload"></i> Subir</button></div>';
      }else{
        botones='';
      }
      $("#data-producto_wrapper div.tooltablas")
        .append(botones + '\
        <span class="m-l-5">\
        <select class="slp" name="pro-estado" Onchange="select_estado()" id="estado" data-placeholder="Estado" style="width: 130px">' +
          '<option ></option>' +
          '<option value="0">Cualquiera</option>' +
          '<option value="1">No disponibles</option>' +
          '<option value="2">Disponible</option>' +
          '<option value="3">Comodato</option>' +
          '</select>\
        </span>');
      $('.slp').select2()
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

  function retira_prod(id, nombre) {

    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Esta seguro que desea retirar este producto del cliente?',
      title: '<i class="far fa-question-circle fa-lg icodialog"></i> Retirar',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt icodialog"></i> Si,retirar', // With spaces and symbols
          action: function() {
            $.post("almacen/retira_prod", {
                id,
                nombre
              })
              .done(function(data) {
                if (data == 1) {
                  alerta('exito', 'Producto retirado correctamente');
                  update_al();
                } else(
                  alerta('error, Acceso denegado')
                )
              });

          }
        }
      }
    });

  }

  function update_al() {
    estado = $("#estado").val();
    productos.ajax.url("almacen/get_productos?estado=" +
      estado).load()
  }

  function select_estado() {
    estado = $("#estado").val();
    productos.ajax.url("almacen/get_productos?estado=" + estado).load()
  }
  update_productos = function() {
    productos.ajax.reload(null, false);
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
  }

  $('input[name="imp-pro"]').change(function() {
    $('.txt-imp').html('Archivo a Procesar: <b>' + this.files.item(0).name + '</b>');
    button_import.style.display = "block"
    $('.btn-import').prop('disabled', false).html('Comprobar');
  });

  $('#import-form').ajaxForm({
    success: function(data) {
      data = JSON.parse(data)

      if (data == 2) {
        $('#verificacion').val("0")
        alerta('error', 'Acceso denegado');
      }
      if (data == 0) {
        $('.btn-import').prop('disabled', false).html('Importar');
        $('#verificacion').val("1")

        $('.txt-imp').html("Exito, proceda a importar");
      }
      if (data == 1) {
        $('#verificacion').val("0")
        $('.btn-import').prop('disabled', false).html('Importar');
        update_al();
        $('#gritter-notice-wrapper').remove();
        alerta('exito', 'Productos importados correctamente');
        button_import.style.display = "none"
        $("#import-form")[0].reset()
        $('.txt-imp').html("");
        if (data == 2) {
          $('.import-list').hide();
          $.each(data['error'], function(k, v) {
            $('.text-errori-mp').show();
            $('.ol-error').append('<li>' + v + '.</li>')
          });
        }
        $('#import').modal('hide');
      }
      if (Array.isArray(data)) {
        $('#verificacion').val("0")
        $('.btn-import').prop('disabled', true).html('Comprobar');
        texto = "<h5>Corregir y volver a intentar:</h5>"
        data.forEach(e => {

          e.forEach(element => {

            texto += `<p>${element[0]}</p>`;
          });
        });
        $('.txt-imp').html(texto);

      }
    },
    beforeSubmit: function() {
      $('.btn-import').prop('disabled', true).html('<i class="fa fa-spinner fa-lg fa-spin"></i> Importando...');
    },
    error: function(response) {
      $('.btn-import').button("reset");
      $('#gritter-notice-wrapper').remove();
      alerta('error500', response['responseText']);
    }
  })

  function vaciar() {
    token.value = ""
    id.value = ""
    categoria.value = ""
    idproveedor.value = ""
    costo.value = ""
    mac.value = ""
    serial.value = ""

  }

  //------------accesorio------------
  var accesorio = $('#data-accesorio').DataTable({
    "ajax": base_url + "almacen/get_accesorios",

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
        mData: 'costo'
      },
      {
        mData: 'cantidad'
      },
      {
        mData: 'tools',
        sClass: 'text-center',
        sWidth: '30px'
      }
    ],
    initComplete: function(oSettings, json) {
      if (<?= $permiso; ?> >= 2) {
        $("#data-accesorio_wrapper div.tooltablas")
          .append('<div class="grupotabla btn-group btn-group-md"><button type="button" class="btn btn-default " data-toggle="modal" data-target="#modal_accesorio" onclick="new_accesorio();"><i class="fas fa-plus"></i> Nuevo</button>')
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

  form2.addEventListener('submit', function(e) {
    date = new Date()
    date = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate()
    url = ""
    e.preventDefault();
    id_accesorio.value == "" ? url = "almacen/new_producto_accesorio" : url = "almacen/update_producto_accesorio";
    $.ajax({
      url: url,
      type: 'POST',
      data: {
        'date': date,
        'categoria': accesorio_categoria.value,
        'token': accesorio_token.value,
        'id': id_accesorio.value,
        'costo': accesorio_costo.value,
        'cantidad': accesorio_cantidad.value,
        'sucursal': accesorio_sucursal.value
      },
      success: function(result) {
        if (result == 1) {
          vaciar_accesorio()
          update_accesorios()
          alerta('exito', 'Accesorio agregado con exito')
          $('#modal_accesorio').modal('hide');
        } else {
          alerta('error, intente de nuevo');
        }
      }
    });
  })

  function vaciar_accesorio() {
    accesorio_token.value = ""
    id_accesorio.value = ""
    accesorio_costo.value = ""
    accesorio_cantidad.value = ""
  }

  update_accesorios = function() {
    accesorio.ajax.reload(null, false);
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
  }

  function new_accesorio() {
    select_hidden.style.display = "block"
    vaciar_accesorio()
    accesorio_titulo.innerHTML = "Nuevo accesorio"
    accesorio_button.innerHTML = "Registrar"
  }
</script>