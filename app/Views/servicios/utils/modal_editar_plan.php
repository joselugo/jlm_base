<style type="text/css">
  .popover {
    z-index: 1090;
  }
</style>
<form id="perfil-form" method="post" action="servicios/save_edit_plan" class="form-horizontal">
  <input type="hidden" name="id" value="<?= $plan->id; ?>">
  <div class="modal-body">

    <div class="row">

      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-2 control-label">Nombre Plan</label>
          <div class="col-md-8">
            <input type="text" class="form-control no" name="nombre" value="<?= $plan->plan; ?>" placeholder="<?= $plan->plan; ?>" required autocomplete='name'>
          </div>
        </div>
      </div>

      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-2 control-label">Descripción</label>
          <div class="col-md-8">
            <textarea style="height:140px;" id="txt" class="form-control" name="descripcion" placeholder="Internet banda ancha  4Mbps/2Mbps" required rows="1"><?= $plan->descripcion; ?></textarea>
            <small class="text-warning">* Texto para la Facturación</small>
          </div>
        </div>
      </div>

      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-2 control-label">Precio Plan</label>
          <div class="col-md-4">
            <div class="input-group">
              <span class="input-group-addon">MX$</span>
              <input class="form-control" value="<?= $plan->costo; ?>" placeholder="<?= $plan->costo; ?>" name="precio" required type="number" step="0.01">
            </div>
          </div>
          <label class="col-md-2 control-label">Precio Prepago</label>
          <div class="col-md-4">
            <div class="input-group">
              <span class="input-group-addon">MX$</span>
              <input class="form-control" value="<?= $plan->costo_prepago; ?>" placeholder="<?= $plan->costo_prepago; ?>" name="precioPrepago" type="number" step="0.01">
              <small class="text-warning">* Dejar vacio para que este plan no tenga descuento por prepago.</small>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-2 control-label">Impuesto (%)</label>
          <div class="col-md-4">
            <div class="input-group">
              <input type="text" name="impuesto" class="form-control" placeholder="19" required value="<?= $plan->impuesto; ?>" maxlength="5" pattern="^[0-9]\d*(\.\d+)?$" title="Ingrese un valor correcto para el impuesto">
              <span class="input-group-addon"><i class="fas fa-percent" aria-hidden="true"></i></span>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-2 control-label">Routers</label>
          <div class="col-md-6">
            <select name="id_mikrotik[]" class="slopnodos" multiple="multiple" style="width: 100%" required>
              <option value="%%" class="allnodo">Todos los Routers</option>
              <?= $select; ?>
            </select>
            <small class="text-warning">* Puede selecionar para que routers van estar disponibles este plan.</small>
          </div>
        </div>
      </div>

      <div class="col-sm-12">
        <div class="form-group row">
          <label class="col-md-2 control-label">No crear reglas <i class="far fa-question-circle fa-lg" style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="" data-original-title="Por defecto mikrowisp creará los perfiles ppp,hotspot en mikrotik y si elige trabajar con PCQ mikrowisp creará las reglas mangle y Queutree. Si va utilizar su propia configuración deberá activar esta opción e indicar los nombres manualmente"></i></label>
          <div class="col-md-8">
            <input type="hidden" name="api_mk" value="0">
            <label class="switch">
              <input class="chk" type="checkbox" name="api_mk" value="<?= $plan->id_mikrotik; ?>">
              <span class="slider round"></span>
            </label>

          </div>
        </div>
      </div>

      <div class="col-sm-12">
        <hr>
      </div>

      <div class="col-sm-12 text-center ">
        <h6 class="font-weight-bold">Descarga</h6>
      </div>
      <div class="col-sm-6">

        <div class="form-group row">

          <label class="col-md-4 control-label">Descarga Kbps
            <button type="button" class="btn btn-xs btn-default" data-html="true" data-toggle="popover" title="Velocidades" data-content='
            <ul style="padding-left: 10px;" class="popkbpsdown">
            <li data-down="1000">1 Mbps <small>(1000 kbps)</small></li>
            <li data-down="2000">2 Mbps <small>(2000 kbps)</small></li>
            <li data-down="3000">3 Mbps <small>(3000 kbps)</small></li>
            <li data-down="4000">4 Mbps <small>(4000 kbps)</small></li>
            <li data-down="5000">5 Mbps <small>(5000 kbps)</small></li>
            <li data-down="6000">6 Mbps <small>(6000 kbps)</small></li>
            <li data-down="7000">7 Mbps <small>(7000 kbps)</small></li>
            <li data-down="8000">8 Mbps <small>(8000 kbps)</small></li>
            <li data-down="9000">9 Mbps <small>(9000 kbps)</small></li>
            <li data-down="10000">10 Mbps <small>(10000 kbps)</small></li>
            </ul>
            '><i class="fas fa-plus"></i></button>
          </label>
          <div class="col-md-8">
            <div class="input-group">
              <input class="form-control bst" value="<?= $plan->down; ?>" placeholder="<?= $plan->down; ?>" name="descarga" pattern="\d*" value="0" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
              <span class="input-group-addon">Kbps</span>
            </div>
            <small class="bdown text-success"></small>
          </div>
        </div>
      </div>



      <div class="col-sm-6">
        <div class="form-group row">
          <label class="col-md-4 control-label">Limit AT</label>
          <div class="col-md-8">
            <div class="input-group">
              <input class="form-control bst" name="limitat_descarga" value="<?= $plan->limitat_descarga; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
              <span class="input-group-addon">Kbps</span>
            </div>
            <small class="limitat_descarga text-success"></small>
          </div>
        </div>
      </div>

      <div class="col-sm-6">
        <div class="form-group row">
          <label class="col-md-4 control-label">Burst Limit</label>
          <div class="col-md-8">
            <div class="input-group">
              <input class="form-control bst" name="blimit_descarga" value="<?= $plan->burst_limit_descarga; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
              <span class="input-group-addon">Kbps</span>
            </div>
            <small class="blimit_descarga text-success"></small>
          </div>
        </div>
      </div>

      <div class="col-sm-6">
        <div class="form-group row">
          <label class="col-md-4 control-label">Burst threshold</label>
          <div class="col-md-8">
            <div class="input-group">
              <input class="form-control bst" name="bthreshold_descarga" value="<?= $plan->burst_threshold_descarga; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
              <span class="input-group-addon">Kbps</span>
            </div>
            <small class="bthreshold_descarga text-success"></small>
          </div>
        </div>
      </div>

      <div class="col-sm-6">
        <div class="form-group row">
          <label class="col-md-4 control-label">Burst Time</label>
          <div class="col-md-8">
            <div class="input-group">
              <input class="form-control" name="btime_descarga" value="<?= $plan->burst_time_descarga; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
              <span class="input-group-addon">seg</span>
            </div>
            <small class="text-success">Tiempo de Ráfaga</small>
          </div>
        </div>
      </div>

      <div class="col-sm-6">
        <div class="form-group row">
          <label class="col-md-4 control-label">Prioridad</label>
          <div class="col-md-8">
            <select style="width: 100%;" class="form-control Select2" name="prioridad_descarga">
              <option value="8" selected>Baja (8)</option>
              <option value="7">Baja (7)</option>
              <option value="6">Normal (6)</option>
              <option value="5">Normal (5)</option>
              <option value="4">Normal (4)</option>
              <option value="3">Alto (3)</option>
              <option value="2">Alto (2)</option>
              <option value="1">Alto (1)</option>
            </select>
          </div>
        </div>
      </div>

      <div class="col-sm-6">
        <div class="form-group row">
          <label class="col-md-4 control-label">PCQ</label>
          <div class="col-md-8">
            <input type="text" class="form-control no" value="<?= $plan->pcq_descarga; ?>" name="pcq_descarga" placeholder="<?= $plan->pcq_descarga; ?>">
            <small class="text-warning">*Opcional</small>
          </div>
        </div>
      </div>

      <div class="send_api row" style="display: none; width: 100%">

        <div class="col-sm-6">
          <div class="form-group row">
            <label class="col-md-4 control-label">Profile</label>
            <div class="col-md-8">
              <input type="text" value="<?= $plan->profile; ?>" class="form-control no" name="profile" placeholder="Nombre del profile Mikrotik" autocomplete='given-name'>
              <small class="text-warning">* Nombre del Perfil PPPoe/Hotspot.</small>
            </div>
          </div>
        </div>

        <!--      <div class="col-sm-6">
          <div class="form-group row">
            <label class="col-md-4 control-label">PCQ</label>
            <div class="col-md-8">
              <input type="text" class="form-control no" name="lista_pcq" placeholder="Lista utilizada en su mikrotik">
              <small class="text-warning">* Lista addresslist para PCQ + Addresslist</small>
            </div>
          </div>
        </div> -->

      </div>





      <div class="col-sm-12">
        <hr>
      </div>
      <div class="col-sm-12 text-center ">
        <h6 class="font-weight-bold">Subida</h6>
      </div>
      <div class="col-sm-6">
        <div class="form-group row">
          <label class="col-md-4 control-label">Subida Kbps
            <button type="button" class="btn btn-xs btn-default" data-html="true" data-toggle="popover" title="Velocidades" data-content='
              <ul style="padding-left: 10px;" class="popkbpsup">
              <li data-down="1000">1 Mbps <small>(1000 kbps)</small></li>
              <li data-down="2000">2 Mbps <small>(2000 kbps)</small></li>
              <li data-down="3000">3 Mbps <small>(3000 kbps)</small></li>
              <li data-down="4000">4 Mbps <small>(4000 kbps)</small></li>
              <li data-down="5000">5 Mbps <small>(5000 kbps)</small></li>
              <li data-down="6000">6 Mbps <small>(6000 kbps)</small></li>
              <li data-down="7000">7 Mbps <small>(7000 kbps)</small></li>
              <li data-down="8000">8 Mbps <small>(8000 kbps)</small></li>
              <li data-down="9000">9 Mbps <small>(9000 kbps)</small></li>
              <li data-down="10000">10 Mbps <small>(10000 kbps)</small></li>
              </ul>
              '><i class="fas fa-plus"></i></button>
          </label>
          <div class="col-md-8">

            <div class="input-group">
              <input class="form-control bst" value="<?= $plan->up; ?>" placeholder="1024" name="subida" pattern="\d*" value="0" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
              <span class="input-group-addon">Kbps</span>
            </div>
            <small class="bup text-success"></small>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group row">
          <label class="col-md-4 control-label">Limit AT</label>
          <div class="col-md-8">
            <div class="input-group">
              <input class="form-control bst" name="limitat_subida" value="<?= $plan->limitat_subida; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
              <span class="input-group-addon">Kbps</span>
            </div>
            <small class="limitat_subida text-success"></small>
          </div>
        </div>
      </div>

      <div class="col-sm-6">
        <div class="form-group row">
          <label class="col-md-4 control-label">Burst Limit</label>
          <div class="col-md-8">
            <div class="input-group">
              <input class="form-control bst" name="blimit_subida" value="<?= $plan->burst_limit_subida; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
              <span class="input-group-addon">Kbps</span>
            </div>
            <small class="blimit_subida text-success"></small>
          </div>
        </div>
      </div>

      <div class="col-sm-6">
        <div class="form-group row">
          <label class="col-md-4 control-label">Burst threshold</label>
          <div class="col-md-8">
            <div class="input-group">
              <input class="form-control bst" name="bthreshold_subida" value="<?= $plan->burst_threshold_subida; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
              <span class="input-group-addon">Kbps</span>
            </div>
            <small class="bthreshold_subida text-success"></small>
          </div>
        </div>
      </div>

      <div class="col-sm-6">
        <div class="form-group row">
          <label class="col-md-4 control-label">Burst Time</label>
          <div class="col-md-8">
            <div class="input-group">
              <input class="form-control" name="btime_subida" value="<?= $plan->burst_time_subida; ?>" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')">
              <span class="input-group-addon">seg</span>
            </div>
            <small class="text-success">Tiempo de Ráfaga</small>
          </div>
        </div>
      </div>

      <div class="col-sm-6">
        <div class="form-group row">
          <label class="col-md-4 control-label">Prioridad</label>
          <div class="col-md-8">
            <select style="width: 100%;" class="form-control Select2" name="prioridad_subida">
              <option value="8" selected>Baja (8)</option>
              <option value="7">Baja (7)</option>
              <option value="6">Normal (6)</option>
              <option value="5">Normal (5)</option>
              <option value="4">Normal (4)</option>
              <option value="3">Alto (3)</option>
              <option value="2">Alto (2)</option>
              <option value="1">Alto (1)</option>
            </select>
          </div>
        </div>
      </div>

      <div class="col-sm-6">
        <div class="form-group row">
          <label class="col-md-4 control-label">PCQ</label>
          <div class="col-md-8">
            <input type="text" class="form-control no" value="<?= $plan->pcq_subida; ?>" name="pcq_subida" placeholder="habiliados">
            <small class="text-warning">*Opcional</small>
          </div>
        </div>
      </div>

      <div class="col-sm-12">
        <hr>
      </div>
      <div class="col-sm-6">
        <div class="form-group row">
          <label class="col-md-4 control-label">Addresslist</label>
          <div class="col-md-8">
            <input type="text" class="form-control no" value="<?= $plan->lista; ?>" name="lista" placeholder="habiliados">
            <small class="text-warning">*Opcional Lista personalizada ejm: habiliados</small>
          </div>
        </div>
      </div>

    </div>
  </div>


  <div class="modal-footer">
    <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
    <button type="submit" style="display:<?= $permiso >= 2 ? "block" : "none"; ?>" class="btn btn-sm btn-success btn-editperfil btn-loader">Guardar</button>
  </div>

</form>


<script>
  $(function() {
    var ids = [<?= $plan->id_mikrotik=="%%"?'"%%"': $plan->id_mikrotik; ?>]
    for (let i = 0; i < ids.length; i++) {
      $('.slopnodos option[value="' + ids[i] + '"]').prop("selected", true);
    }
    $('.slopnodos').select2();

    $('.slopnodos').on('select2:select', function(e) {
      $('.slopnodos').select2('destroy');
      $('.slopnodos').select2();
    });

    $('.slopnodos').change(function() {
      if ($('.allnodo').is(':selected')) {
        $(".slopnodos > option").prop("selected", false);
        $('.slopnodos > option[value="%%"]').prop("selected", "selected");
      } else {
        $('.slopnodos > option[value="%%"]').prop("selected", false);
      }
    });

    $('input[name="api_mk"]').change(function() {
      if ($(this).is(":checked")) {
        $('.send_api').show();
      } else {
        $('.send_api').hide();
      }
    });

    $('input[name="api_mk"]').trigger('change');


    $('#perfil-form').ajaxForm({
      beforeSend: function() {
        error_ajax = false;
        if ($('[name="blimit_descarga"]').val() != 0 && $('[name="blimit_subida"]').val() > 0) {
          if ($('[name="blimit_descarga"]').val() <= $('[name="descarga"]').val()) {
            alerta("warning", "Burst Limit(descarga) debe ser mayor a Descarga Kbps");
            error_ajax = true;
          }
        }
        if ($('[name="blimit_subida"]').val() != 0 && $('[name="blimit_subida"]').val() > 0) {
          if ($('[name="blimit_subida"]').val() <= $('[name="subida"]').val()) {
            alerta("warning", "Burst Limit(subida) debe ser mayor a Subida Kbps");
            error_ajax = true;
          }
        }
        if (error_ajax) {
          btnloader('#perfil-form', '<i class="far fa-save fa-lg"></i> Guardar');
          return false;
        }
      },
      success: function(data) {
        if (typeof updateperfiles === 'updateperfiles') {
          updateperfiles();
        }

        $('#gritter-notice-wrapper').remove();
        if (data == 1) {
          $('#modaltmp').modal('hide');
          alerta('exito', 'Editado correctamente')
        } else if (data == 2) {
          alerta('error', 'Error al actualizar.');
          btnloader('#perfil-form', '<i class="far fa-save fa-lg"></i> Guardar');
        } else {
          alerta('warning', 'No tienes los permisos necesarios.');
          btnloader('#perfil-form', '<i class="far fa-save fa-lg"></i> Guardar');
        }

      },
      beforeSubmit: function() {
        btnloader('#perfil-form');
      },
      error: function(response) {
        $('#gritter-notice-wrapper').remove();
        btnloader('#perfil-form', '<i class="far fa-save fa-lg"></i> Guardar');
        alerta('error500', 'Error');
      }
    })

    $('[data-toggle="popover"]').popover();

    $('#modaltmp .modal-header h5').html('Editar: <?= $plan->plan; ?>');

    $('[name="prioridad_descarga"] option[value="<?= $plan->prioridad_descarga; ?>"]').prop('selected', true);
    $('[name="prioridad_subida"] option[value="<?= $plan->prioridad_subida; ?>"]').prop('selected', true);


    $(".bst").keyup(function() {
      var down, up;
      //-->descarga
      if ($('[name="descarga"]').val() > 0 || $('[name="limitat_descarga"]').val() > 0 || $('[name="blimit_descarga"]').val() > 0 || $('[name="bthreshold_descarga"]').val() > 0 || $('[name="subida"]').val() > 0 || $('[name="limitat_subida"]').val() > 0 || $('[name="blimit_subida"]').val() > 0 || $('[name="bthreshold_subida"]').val() > 0) {
        down = Math.round($('[name="descarga"]').val()) / 1000;
        dld = Math.round($('[name="limitat_descarga"]').val()) / 1000;
        dbld = Math.round($('[name="blimit_descarga"]').val()) / 1000;
        dbd = Math.round($('[name="bthreshold_descarga"]').val()) / 1000;
        dbt = Math.round($('[name="btime"]').val()) / 1000;
        up = Math.round($('[name="subida"]').val()) / 1000;
        uld = Math.round($('[name="limitat_subida"]').val()) / 1000;
        ubld = Math.round($('[name="blimit_subida"]').val()) / 1000;
        ubd = Math.round($('[name="bthreshold_subida"]').val()) / 1000;
        ubt = Math.round($('[name="btime"]').val()) / 1000;

        $('.bup').html('Subida: ' + roundto(up) + 'Mbps');
        $('.limitat_subida').html(roundto(uld) + 'Mbps');
        $('.blimit_subida').html(roundto(ubld) + 'Mbps');
        $('.bthreshold_subida').html(roundto(ubd) + 'Mbps');

        $('.bdown').html('Descarga: ' + roundto(down) + 'Mbps');
        $('.limitat_descarga').html(roundto(dld) + 'Mbps');
        $('.blimit_descarga').html(roundto(dbld) + 'Mbps');
        $('.bthreshold_descarga').html(roundto(dbd) + 'Mbps');
      } else {
        $('.bdown').html('Descarga: 0Mbps');
        $('.bup').html('Subida: 0Mbps');
        $('.limitat_descarga').html('0Mbps');
        $('.blimit_descarga').html('0Mbps');
        $('.bthreshold_descarga').html('0Mbps');
        $('.bup').html('0Mbps');
        $('.limitat_subida').html('0Mbps');
        $('.blimit_subida').html('0Mbps');
        $('.bthreshold_subida').html('0Mbps');

      }


    });

    $('input[name="limitat"]').keyup();

    var ta = document.querySelector('textarea');
    ta.addEventListener('focus', function() {
      autosize(ta);
    });

    $(".Select2").select2({
      placeholder: "Seleccionar..."
    });



    $('#modaltmp').modal('show');


  })
</script>