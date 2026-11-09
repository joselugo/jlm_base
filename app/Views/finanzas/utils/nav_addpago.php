<input name="validpromesa" type="hidden" value="<?= $promesa_pago; ?>">
<input name="id-cliente" type="hidden" value="<?= $idcliente; ?>">
<div class="panel panel-inverse">
  <div class="panel-heading">
    <h4 class="panel-title"><a href="#clientes/detalleuser?id=<?= $idcliente ?>" target="new"><?= strtoupper($nombre) ?> <span class="label label-<?= $estado=="ACTIVO"?"success":"danger"?>"><?= $estado ?></span></a></h4>
  </div>
  <div class="panel-body border">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-danger fade show m-b-10">
          <span class="close" data-dismiss="alert">×</span>
          El cliente cuenta con <b class="alert-link"><?= $num_facturas ?></b> facturas por cobrar (Total <b class="alert-link">MX$<?= $sumatotal; ?></b>).
        </div>
      </div>

      <div class="col-md-7" style="border-right: 1px solid #ddd;">

        <div class="col-sm-12 p-b-10 float-left">
          <div class="form-group">
            <label style="width:100%">Comprobante a pagar <button type="button" class="btn btn-default btn-sm btnviewfc" style="margin-left: 5px;font-size: 15px;padding: 2px 8px;" data-toggle="tooltip" data-placement="top" title="Ver factura PDF" onclick="view_doc()"><b><i class="far fa-file-pdf"></i></b></button></label>
            <select class="sl2p form-control input-sm" name="p-facturas" style="width:100%;">
              <optgroup label="--- FACTURAS PENDIENTES ---">
                <?= $select_facturas ?>
              </optgroup>
              <optgroup label="--- SALDOS PENDIENTES ---">
                <?= $select_saldos ?>
              </optgroup>
            </select>
          </div>
        </div>

        <div class="col-sm-6 dvtransaccion col-6 float-left">
          <div class="form-group input-group-sm">
            <label>Comisión <b>MX$</b></label>
            <input type="text" pattern="[-+]?[0-9]*[.]?[0-9]+" class="form-control input-sm" name="p-comision" step="0.01" value="0"></div>
        </div>

        <div class="col-sm-6 dvtransaccion col-12 float-left">
          <div class="form-group input-group-sm">
            <label>N° Transacción</label>
            <input type="text" class="form-control input-sm" name="p-transaccion">
          </div>
        </div>

        <div class="col-sm-6 xnlegal col-12 float-left">
          <div class="form-group input-group-sm">
            <label>N° Fiscal</label>
            <input type="number" class="form-control input-sm" name="p-nlegal">
          </div>
        </div>

        <div class="col-md-6 col-12 dpago float-left">
          <div class="form-group input-group-sm">
            <label>Forma de Pago</label>
            <select class="form-control input-sm sl2p" name="p-forma" style="width:100%">
              <?= $select_pasarelas ?>
            </select>
          </div>
        </div>

        <div class="col-md-6 col-12 dpago float-left">
          <div class="form-group input-group-sm">
            <label>Detalle forma de Pago</label>
            <select class="form-control input-sm sl2p" name="p-forma-desc" style="width:100%">

            </select>
          </div>
        </div>


        <div class="col-sm-6 tipopago col-12 float-left">
          <div class="form-group input-group-sm">
            <label>Tipo de pago</label>
            <select name="tipopago" class="sl2p2 form-control input-sm" name="p-tipopago" style="width:100%">
              <option value="1">Registrar pago y Activar</option>
              <option value="2">Solo registrar pago</option>
              <option value="3">Promesa de pago</option>
            </select>
          </div>
        </div>

        <div class="col-sm-6 col-12 promesa float-left" style="display: none;">
          <div class="form-group input-group-sm has-success">
            <label>Fecha Límite de pago</label>
            <div class="input-group">
              <div class="input-group-addon"> <i class="far fa-calendar"></i></div>
              <input type="text" name="fechalimite" class="form-control input-sm" value="09/08/2020" readonly>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-12 d-none float-left">
          <div class="form-group input-group-sm">
            <label>Meses a Pagar</label>
            <select name="p-mesespagar" class="sl2p2 form-control input-sm" style="width:100%">
              <option value="1">1 mes</option>
              <option value="2">2 meses</option>
              <option value="3">3 meses</option>
              <option value="4">4 meses</option>
              <option value="5">5 meses</option>
              <option value="6">6 meses</option>
              <option value="7">7 meses</option>
              <option value="8">8 meses</option>
              <option value="9">9 meses</option>
              <option value="10">10 meses</option>
              <option value="11">11 meses</option>
              <option value="12">1 año</option>
            </select>
          </div>
        </div>

        <div class="col-md-6 col-12 proximodia float-left ">
          <div class="form-group input-group-sm has-success">
            <label>Día pago</label>
            <select type="select" style="width: 100%;" name="p-dia" class="sl2p form-control">
              <option value="1">01 de cada mes</option>
              <option value="2">02 de cada mes</option>
              <option value="3">03 de cada mes</option>
              <option value="4">04 de cada mes</option>
              <option value="5">05 de cada mes</option>
              <option value="6">06 de cada mes</option>
              <option value="7">07 de cada mes</option>
              <option value="8">08 de cada mes</option>
              <option value="9">09 de cada mes</option>
              <option value="10">10 de cada mes</option>
              <option value="11">11 de cada mes</option>
              <option value="12">12 de cada mes</option>
              <option value="13">13 de cada mes</option>
              <option value="14">14 de cada mes</option>
              <option value="15">15 de cada mes</option>
              <option value="16">16 de cada mes</option>
              <option value="17">17 de cada mes</option>
              <option value="18">18 de cada mes</option>
              <option value="19">19 de cada mes</option>
              <option value="20">20 de cada mes</option>
              <option value="21">21 de cada mes</option>
              <option value="22">22 de cada mes</option>
              <option value="23">23 de cada mes</option>
              <option value="24">24 de cada mes</option>
              <option value="25">25 de cada mes</option>
              <option value="26">26 de cada mes</option>
              <option value="27">27 de cada mes</option>
              <option value="28">28 de cada mes</option>
            </select>
          </div>
        </div>

        <div class="col-sm-6 col-12 totalpagar float-left">
          <div class="form-group input-group-sm has-success">
            <label>TOTAL A PAGAR</label>
            <div class="input-group"> <span class="input-group-addon"><b>MX$</b></span> <input type="number" class="form-control" name="p-total" required="" step="0.01" style="font-size: 19px;font-weight: 700;color: #038a8a;">
            </div>
            <input type="hidden" name="total2" value="0">
            <input type="hidden" name="saldos" value="0">
          </div>
        </div>

      </div>

      <div class="col-md-5">

        <div class="col-sm-12">
          <div class="form-group input-group-sm">
            <label>Notas</label>
            <textarea class="form-control" name="p-notas" placeholder="Comentario del pago" style="height: 50px"></textarea>
          </div>
        </div>

        <div class="form-group m-b-5">
          <label class="col-md-3 col-form-label">Imprimir</label>
          <div class="col-md-9">

            <div class="radio radio-css is-valid m-b-2">
              <input type="radio" name="radio_print" id="cssRadio1" checked value="recibo">
              <label for="cssRadio1"><i class="fas fa-print"></i> Recibo normal</label>
            </div>

            <div class="radio radio-css is-valid m-b-2">
              <input type="radio" name="radio_print" id="cssRadio2" value="recibopos">
              <label for="cssRadio2"><i class="fas fa-print"></i> Recibo POS</label>
            </div>

            <div class="radio radio-css is-valid m-b-2 impfactura">
              <input type="radio" name="radio_print" id="cssRadio3" value="factura">
              <label for="cssRadio3"><i class="fas fa-print"></i> Factura</label>
            </div>



            <div class="radio radio-css is-invalid m-b-2">
              <input type="radio" name="radio_print" id="cssRadio5">
              <label for="cssRadio5"><i class="fas fa-ban"></i> No imprimir</label>
            </div>

          </div>

        </div>

        <div class="col-sm-12 m-t-10">
          <div class="form-group input-group-sm text-center">
            <input type="hidden" name="p-tipofc">
            <button type="button" class="btn btn-white btncancelv" onClick="$('.salidainvoice').slideUp().empty();"><i class="fas fa-times"></i> Cancelar</button>
            <button type="submit" class=" loaderbtn btn  btn-info btn-loader btnagregapago"><i class="fas fa-check"></i> Registrar pago</button>
          </div>
        </div>

      </div>

    </div>

  </div>
</div>

<script>
  $(document).ready(function() {

    if (!localStorage.tipoimp) {
      localStorage.setItem("tipoimp", "cssRadio1");
    }

    $('input[type=radio][name=radio_print]').change(function() {
      localStorage.setItem("tipoimp", $(this).prop('id'));
    });


    $('input[name="fechalimite"]').datepicker({
      startDate: '05/08/2020',
      endDate: '24/08/2020',
      autoclose: true,
      language: "es",
    });


    var nlegal = "44601";

    $('select[name="p-dia"] option[value="<?= $diapago;?>"]').prop('selected', true);

    $('.sl2p,.sl2p2').select2();

    $('select[name="tipopago"]').change(function() {
      if ($(this).val() == "3") {

        if ($('input[name="validpromesa"]').val()) {
          alerta('error', 'El cliente ya cuenta con una promesa de pago activo y no se puede agregar otra promesa de pago hasta cumplir o eliminar dicha promesa de pago.');
          $('select[name="tipopago"] option[value="1"]').prop('selected', true);
          return false;
        }

        $('.promesa').show();
        $('.dvtransaccion').hide();
        $('.totalpagar,.xnlegal,.dpago').hide();
        $('.proximodia').hide();
        $('.impafip').hide();
        $("#cssRadio5").prop("checked", true);

      } else {
        $('.promesa').hide();
        $('.dvtransaccion').show();
        $('.totalpagar,.xnlegal,.dpago').show();
        $('.proximodia').show();
        $('.impafip').show();
        $("#cssRadio5").prop("checked", false);
        $("#cssRadio1").prop("checked", true);
      }


    })

    $('input[name="p-total"]').change(function() {
      if ($(this).val() > $('input[name="total2"]').val() || $(this).val() < $('input[name="total2"]').val()) {
        $("#cssRadio4").prop("disabled", true);
        $("#cssRadio4").prop("checked", false);
      }
    });

    $('select[name="p-facturas"]').change(function() {

      //-->Totales
      $('input[name="total2"]').val($(this).find(':selected').data('total'));
      $('input[name="p-total"]').val($(this).find(':selected').data('total'));
      $('input[name="p-tipofc"]').val($(this).find(':selected').data('tipo'));

      //-->N Legal
      if ($(this).find(':selected').data('legal') > 0) {
        $('input[name="p-nlegal"]').val($(this).find(':selected').data('legal')).prop('readonly', true);
      } else if ($(this).val() > 0) {
        $('input[name="p-nlegal"]').val(nlegal);
      } else {
        $('input[name="p-nlegal"]').val('').prop('readonly', true);
      }

      if ($(this).val() == 'todos') {
        $('input[name="p-total"]').prop('readonly', true);
        $("#cssRadio3").prop("disabled", true);
        $("#cssRadio4").prop("disabled", true);
        $("#cssRadio1").prop("checked", true);
        $('.sl2p2').select2({
          disabled: true,
        });
        $(".btnviewfc").attr("disabled", true);
        return true;
      } else {
        $('input[name="p-total"]').prop('readonly', false);
        $("#cssRadio3").prop("disabled", false);
        $("#cssRadio4").prop("disabled", false);
        $('.sl2p2').select2({
          disabled: false,
        });
        $(".btnviewfc").attr("disabled", false);
      }

      //--->TIPOS DE FC
      if ($(this).find(':selected').data('tipo') == "2") {
        $(".xnlegal").show();
        $(".impfactura").show();
        $('input[name="p-total"]').prop('readonly', false);
        $(".btnviewfc").show();
        $('.totalpagar').show();
        $('.proximodia').show();
        $('.tipopago').show();
      } else if ($(this).find(':selected').data('tipo') == "0") {
        $(".xnlegal").hide();
        $(".impfactura").hide();
        $('input[name="p-total"]').prop('readonly', true);
        $(".btnviewfc").hide();
        $('.promesa').hide();
        $('.tipopago').hide();
        $('.proximodia').hide();

        $('textarea[name="p-notas"]').html($(this).find(':selected').data('detalle'));

      } else if ($(this).find(':selected').data('tipo') == "1") {
        $(".xnlegal").show();
        $(".impfactura").show();
        $('input[name="p-total"]').prop('readonly', false);
        $(".btnviewfc").show();
        $('.tipopago').hide();
        $('.totalpagar').show();
        $('.proximodia').hide();
      }

      //-->Selecciona tipo de impresión
      var idoptprint = "#" + localStorage.tipoimp;
      if (!$(idoptprint).is(':disabled')) {
        $(idoptprint).prop("checked", true);
      }

    });

    $('select[name="p-mesespagar"]').change(function() {
      $('input[name="total2"]').val($('select[name="p-facturas"]').find(':selected').data('total') * $(this).val());
      $('input[name="p-total"]').val($('select[name="p-facturas"]').find(':selected').data('total') * $(this).val());
    });

    $('select[name="p-forma"]').change(function() {
      $('select[name="p-forma-desc"]').html('');

      if ($(this).find(':selected').data('dsp1')) {
        $('select[name="p-forma-desc"]').append($("<option></option>").attr("value", $(this).find(':selected').data('dsp1')).text($(this).find(':selected').data('dsp1')));
      }

      if ($(this).find(':selected').data('dsp2')) {
        $('select[name="p-forma-desc"]').append($("<option></option>").attr("value", $(this).find(':selected').data('dsp2')).text($(this).find(':selected').data('dsp2')));
      }

      if ($(this).find(':selected').data('dsp3')) {
        $('select[name="p-forma-desc"]').append($("<option></option>").attr("value", $(this).find(':selected').data('dsp3')).text($(this).find(':selected').data('dsp3')));
      }

      if ($(this).find(':selected').data('dsp4')) {
        $('select[name="p-forma-desc"]').append($("<option></option>").attr("value", $(this).find(':selected').data('dsp4')).text($(this).find(':selected').data('dsp4')));
      }


    });

    $('select[name="p-facturas"],select[name="p-forma"]').trigger('change');
    select = "<?= $select ?>"
    if (select != "") {
      console.log("dif");
      $(`select[name="p-facturas"] option[value="<?= $select ?>"]`).prop('selected', true);

    }
  })
</script>