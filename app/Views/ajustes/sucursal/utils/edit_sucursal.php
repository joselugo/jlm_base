<div>
  <ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="/admin">Inicio</a></li>
    <li class="breadcrumb-item"><a href="/herramientas/#Ajustes">Ajustes</a></li>
    <li class="breadcrumb-item"><a href="/herramientas/#Ajustes/ajuste_sucursal">Sucursal</a></li>
    <li class="breadcrumb-item active"><?= $sucursal->sucursal; ?></li>
  </ol>
  <h1 class="page-header">Ajustes generales de sucursal</h1>
  <form class="frmajustes form-horizontal" action="ajustes/save_editar_sucursal?idsucursal=<?= $sucursal->id; ?>" method="post">
    <div class="row">
      <div class="col-md-6 ui-sortable">
        <div class="panel panel-inverse">
          <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title">Datos de sucursal</h4>
          </div>
          <div class="panel-body">

            <div class="form-group row">
              <label class="col-form-label col-md-4">Sucursal</label>
              <div class="col-md-8">
                <input type="text" class="form-control" name="sucursal[nombre]" value="<?= $sucursal->sucursal; ?>" required="">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-form-label col-md-4">Alias</label>
              <div class="col-md-8">
                <input type="text" class="form-control" name="sucursal[alias]" value="<?= $sucursal->alias; ?>" required="">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-form-label col-md-4">Dirección</label>
              <div class="col-md-8">
                <input type="text" class="form-control" name="sucursal[direccion]" value="<?= $sucursal->direccion; ?>">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-form-label col-md-4">Teléfonos</label>
              <div class="col-md-8">
                <input type="text" class="form-control" name="sucursal[telefono]" value="<?= $sucursal->telefono; ?>">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-form-label col-md-4">Ubicacion <span><button type="button" class="btn btn-icono btn-xs btn-map" data-toggle="tooltip" data-placement="top" data-original-title="Abrir google maps"><i class="fas fa-street-view"></i></button></span></label>

              <div class="col-md-8">
                <input type="text" class="form-control" name="sucursal[ubicacion]" value="<?= $sucursal->ubicacion; ?>">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-form-label col-md-4">RFC</label>

              <div class="col-md-8">
                <input type="text" class="form-control" name="sucursal[rfc]" value="<?= $sucursal->rfc; ?>">
              </div>
            </div>
            <div class="form-group row">
              <label class="col-form-label col-md-4">Nodos </label>

              <div class="col-md-8">
                <select name="nodos[]" class="slopnodos" multiple="" style="width: 100%" required="">
                  <?= $nodos; ?>
                </select>
              </div>
            </div>
          </div>

          <div class="panel-footer text-right">
            <button type="submit" class="btn-azul btn-sm m-l-5"><i class="far fa-save"></i> Guardar cambios</button>
          </div>
        </div>
      </div>

      <div class="col-md-6 ui-sortable">
        <div class="panel panel-inverse">
          <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title">Logo (.png)</h4>
          </div>
          <div class="panel-body text-center">
            <div style="padding:10px 0;" class="text-center">
              <?php if ($sucursal->logo_principal != "") :; ?>
                <img src="<?= $sucursal->logo_principal; ?>?ramdom=<?= strtotime(date('Y-m-d H:i:s')) ?>" style="height:38px; width:auto" class="logo-admin">
              <?php else :; ?>
                <img src="" alt=" Imagen vacia" style="height:38px; width:auto" class="logo-admin">
              <?php endif; ?>
            </div>

            <div class="form-group row">
              <label class="col-md-3 control-label"></label>
              <div class="col-md-7">
                <label class="btn btn-default btn-md" for="logosistema">
                  <input id="logosistema" name="logosistema" type="file" accept=".png,.jpg,.jpeg,image/png,image/jpeg" style="display:none">
                  <i class="fas fa-plus"></i> Subir Logo principal
                </label>
                <br>
                <small class="text-danger">*PNG o JPG, máximo <b>2 MB</b></small>
              </div>
            </div>

            <div style="padding:10px 0;" class="text-center">
              <?php if ($sucursal->logo_facturas_recibos != "") :; ?>
                <img src="<?= $sucursal->logo_facturas_recibos; ?>?ramdom=<?= strtotime(date('Y-m-d H:i:s')) ?>" style="height:50px; width:auto" class="logo-doc">
              <?php else :; ?>
                <img src="" alt=" Imagen vacia" style="height:50px; width:auto" class="logo-doc">
              <?php endif; ?>
            </div>

            <div class="form-group row">
              <label class="col-md-3 control-label"></label>
              <div class="col-md-7">
                <label class="btn btn-default btn-md" for="logofactura">
                  <input id="logofactura" name="logofactura" type="file" accept=".png,.jpg,.jpeg,image/png,image/jpeg" style="display:none">
                  <i class="fas fa-plus"></i> Subir Logo Facturas &amp; Recibo
                </label>

                <br>
                <small class="text-danger">*PNG o JPG, máximo <b>2 MB</b></small><br>

              </div>
            </div>
            <small class="text-warning">* Se recomienda que el logo no debe pesar mas de <b>50kb</b> y un ancho no mayor de 400px</small>

          </div>
        </div>
      </div>
    </div>
  </form>


  <script>
    var ids = [<?= $nodos_select ; ?>]
    for (let i = 0; i < ids.length; i++) {
      $('.slopnodos option[value="' + ids[i] + '"]').prop("selected", true);
    }
    $('.slopnodos').select2();

    function loginbgs(option) {
      if (!option.id) {
        return option.text;
      }
      var ob = '<img src="images/login-bg/' + option.text + '" class="img-thumbnail" />';
      return ob;
    }

    $(function() {

      $(".sl2-image").select2({
        minimumResultsForSearch: -1,
        templateResult: loginbgs,
        escapeMarkup: function(m) {
          return m;
        }
      });

      $('.btn-map').on('click', function() {
        $.post("ajustes/modal_mapa_sucursal", {
            router: ""
          })
          .done(function(datas) {
            $('#tmp2').html(datas);
          });
      });

      $('#logofactura,#logosistema').bind('change', function() {
        var fileSize = this.files[0].size;
        if (fileSize > 2 * 1024 * 1024) {
          this.value = "";
          alerta("error", "El logo no puede pesar más de 2 MB");
        } else {

          var file_data = $(this).prop("files")[0];
          var form_data = new FormData();
          form_data.append($(this).attr('id'), file_data)
          $.ajax({
            url: "ajustes/save_logo_sistema?idsucursal=<?= $sucursal->id; ?>",
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            method: 'POST',
            type: 'POST',
            success: function(data) {
              data = JSON.parse(data);
              url = 'public/images/logos_sucursales/' + data.archivo + '? random = ' + Math.floor((Math.random() * 100) + 1);
              alerta('exito', 'Imagen subida correctamente');
              console.log(data);
              if (data.tipo == "logo_principal") {
                console.log(url);
                $('.logo-admin').attr("src", url + "?random=" + Math.floor((Math.random() * 100) + 1))
              } else {
                $('.logo-doc').attr("src", url + "?random=" + Math.floor((Math.random() * 100) + 1))
              }
            },
            error: function(xhr) {
              var r = xhr.responseJSON || {};
              alerta('error', r.error || 'No se pudo subir el logo');
            }
          });
          this.value = "";

        }
      })

      $('.frmajustes').ajaxForm({
        success: function(data) {
          if (data == 1) {
            $('#gritter-notice-wrapper').remove();
            alerta('exito', 'Datos guardados correctamente');
          } else {
            $('#gritter-notice-wrapper').remove();
            alerta('error', 'Error al guadar');

          }
        },
        error: function(response) {
          $('#gritter-notice-wrapper').remove();
          alerta('error500', response['responseText']);
        }
      })

      $(".tagit").tagit();
      $('.sl2').select2();


    })
  </script>
</div>