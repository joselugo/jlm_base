<div>
  <ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="/herramientas">Inicio</a></li>
    <li class="breadcrumb-item"><a href="/herramientas/#ajustes">Ajustes</a></li>
    <li class="breadcrumb-item active">Facturación</li>
  </ol>

  <h1 class="page-header">Facturación</h1>

  <form class="frmajustes form-horizontal" action="ajustes/save_config_facturacion" method="post">
    <div class="row">
      <div class="col-md-6">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">Moneda</h4>
          </div>
          <div class="panel-body">
            <div class="form-group row">
              <label class="col-form-label col-md-4">Moneda Letras</label>
              <div class="col-md-8">
                <input type="hidden" value="Cambio en Ajustes (Facturación)" name="log">
                <input type="text" class="form-control" name="ajuste[moneda_letra]" value="<?= $config['moneda_letra']; ?>" required="">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-form-label col-md-4">Moneda unidad</label>
              <div class="col-md-8">
                <input type="text" class="form-control" name="ajuste[moneda_unidad]" value="<?= $config['moneda_unidad']; ?>">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-form-label col-md-4">Formato moneda</label>
              <div class="col-md-8">
                <select class="form-control sl2" name="ajuste[currency]" style="width: 100%">
                  <option value="ARS">Peso Argentino ( $ 1.000,00)</option>
                  <option value="BOB">Bolivia, Boliviano ( Bs 1.000,00)</option>
                  <option value="PYG">Paraguay, guaraní ( Gs 1.000)</option>
                  <option value="PYG">Panamá, Balboa ( Gs 1.000)</option>
                  <option value="BRL">Brazil Real ( R$ 1.000,00)</option>
                  <option value="CLP">Peso chileno ( CLP$ 1.000)</option>
                  <option value="COP">Peso colombiano ( COL$ 1.000,00)</option>
                  <option value="CRC">Costa Rica, Colon ( ₡ 1.000,00)</option>
                  <option value="DOP">Peso dominicano ( RD$ 1,000.00)</option>
                  <option value="SVC">El Salvador, Colon ( ₡ 1,000.00)</option>
                  <option value="EUR">Euro ( 1.000,00 €)</option>
                  <option value="GTQ">Guatemala, Quetzal ( Q 1,000.00)</option>
                  <option value="HNL">Honduras, Lempira ( L 1,000.00)</option>
                  <option value="MXN" selected>Peso Mexicano ( MX$1,000.00)</option>
                  <option value="PEN">Peru, Sol ( S/. 1,000.00)</option>
                  <option value="UYU">Peso Uruguayo ( $U 1.000,00)</option>
                  <option value="USD">Dólar Americano ( $ 1,000.00)</option>
                  <option value="VEF">Venezuela Bolivares Fuertes ( Bs. 1.000,00)</option>
                  <option value="VEB">Venezuela, Bolivar ( Bs. 1.000,00)</option>
                </select>

              </div>
            </div>

          </div>
          <div class="panel-footer text-right">
            <button type="submit" class="btn-azul btn-sm m-l-5"><i class="far fa-save"></i> Guardar cambios</button>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="panel panel-inverse">
          <div class="panel-heading">
            <h4 class="panel-title">General</h4>
          </div>
          <div class="panel-body">

            <div class="form-group row">
              <label class="col-form-label col-md-4">Nº Fiscal <i class="far fa-question-circle fa-lg" style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="" data-original-title="Indicar el numero inicial de la numeración alterna al sistema."></i></label>
              <div class="col-md-8">
                <input type="text" class="form-control" name="ajuste[nfacturalegal]" value="<?= $config['nfacturalegal']; ?>" required="">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-form-label col-md-4">Insertar Nº Fiscal</label>
              <div class="col-md-8">
                <div class="radio radio-css radio-inline">
                  <input type="radio" name="ajuste[optlegal]" id="optlegal1" value="1" <?= $config['optlegal'] == 1 ? "checked" : ""; ?>>
                  <label for="optlegal1">Al crear factura</label>
                </div>
                <div class="radio radio-css radio-inline">
                  <input type="radio" name="ajuste[optlegal]" id="optlegal2" value="2" <?= $config['optlegal'] == 2 ? "checked" : ""; ?>>
                  <label for="optlegal2">Al pagar</label>
                </div>

                <div class="radio radio-css radio-inline">
                  <input type="radio" name="ajuste[optlegal]" id="optlegal3" value="3" <?= $config['optlegal'] == 3 ? "checked" : ""; ?>>
                  <label for="optlegal3">Número libre y Al pagar</label>
                </div>

              </div>
            </div>

            <div class="form-group row">
              <label class="col-form-label col-md-4">Reconexión <i class="far fa-question-circle fa-lg" style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="" data-original-title="Puede indicar el monto de Reconexion(Cliente Suspendido) indicando un monto fijo o un porcentaje(Ejm: 5%).Dejar 0 para desactivar"></i></label>
              <div class="col-md-8">
                <input type="text" pattern="[0-9]+(\.[0-9]{0,2})?%?" class="form-control" name="ajuste[reconexion_cliente]" value="<?= $config['reconexion_cliente']; ?>">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-form-label col-md-4">Aplicar reconexión</label>
              <div class="col-md-8">
                <div class="radio radio-css radio-inline">
                  <input type="radio" name="ajuste[tipo_reconexion]" id="tipo_reconexion1" value="1" <?= $config['tipo_reconexion'] == 1 ? "checked" : ""; ?>>
                  <label for="tipo_reconexion1">Al suspender</label>
                </div>
                <div class="radio radio-css radio-inline">
                  <input type="radio" name="ajuste[tipo_reconexion]" id="tipo_reconexion2" value="2" <?= $config['tipo_reconexion'] == 2 ? "checked" : ""; ?>>
                  <label for="tipo_reconexion2">Próxima factura</label>
                </div>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-form-label col-md-4">Texto Reconexión <i class="far fa-question-circle fa-lg" style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="" data-original-title="Inidique el concepto con el cual será agregado en la factura del cliente."></i></label>
              <div class="col-md-8">
                <input type="text" required class="form-control" name="ajuste[reconexion_texto]" value="<?= $config['reconexion_texto']; ?>" placeholder="Mora por reconexión">
              </div>
            </div>


            <div class="form-group row">
              <label class="col-form-label col-md-4">Mora <i class="far fa-question-circle fa-lg" style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="" data-original-title="Puede indicar el monto de mora por vencimiento (1 día despues del vencimiento de la factura) indicando un monto fijo o un porcentaje(Ejm: 5%)."></i></label>
              <div class="col-md-8">
                <input type="hidden" name="ajuste[mora_cliente]">
                <input type="text" pattern="[0-9]+(\.[0-9]{0,2})?%?" class="form-control" name="ajuste[mora_cliente]" value="<?= $config['mora_cliente']; ?>">
              </div>
            </div>

            <div class="form-group row">
              <label class="col-form-label col-md-4">Texto mora <i class="far fa-question-circle fa-lg" style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="" data-original-title="Inidique el concepto con el cual será agregado en la factura del cliente."></i></label>
              <div class="col-md-8">
                <input type="text" required class="form-control" name="ajuste[mora_texto]" value="<?= $config['mora_texto']; ?>" placeholder="Mora factura vencida">
              </div>
            </div>


            <!-- <div class="form-group row d-none">
              <label class="col-form-label col-md-4">No Activar con Deuda <i class="far fa-question-circle fa-lg" style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="" data-original-title="Si Activa esta opción el cliente no será Activado cuando tenga otras facturas por pagar. Por ejemplo si el cliente debe 2 facturas y solo paga 1 este no será Activado."></i></label>
              <div class="col-md-8">
                <input type="hidden" name="ajuste[no_activa_deuda]">
                <input class="chk" type="checkbox" name="ajuste[no_activa_deuda]">
              </div>
            </div> -->


            <div class="form-group row">
              <label class="col-form-label col-md-4">Fecha & Hora PDF <i class="far fa-question-circle fa-lg" style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="" data-original-title="Inserta la Fecha y Hora del PDF generado."></i></label>
              <div class="col-md-8">
                <input type="hidden" name="ajuste[pdf_generado]">
                <input class="chk" type="checkbox" name="ajuste[pdf_generado]" <?= $config['pdf_generado'] == 'on' ? "checked" : ""; ?>>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-form-label col-md-4">Enviar Factura pagada <i class="far fa-question-circle fa-lg" style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="" data-original-title="Enviar factura pagada al registrar pago"></i></label>
              <div class="col-md-8">
                <input type="hidden" name="ajuste[send_pagado]">
                <input class="chk" type="checkbox" name="ajuste[send_pagado]" <?= $config['send_pagado'] == 'on' ? "checked" : ""; ?>>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-form-label col-md-4">Enviar recibo pagada <i class="far fa-question-circle fa-lg" style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="Enviar recibo vía email al reigstrar un pago"></i></label>
              <div class="col-md-8">
                <input type="hidden" name="ajuste[send_recibo]">
                <input class="chk" type="checkbox" name="ajuste[send_recibo]" <?= $config['send_recibo'] == 'on' ? "checked" : ""; ?>>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-form-label col-md-4">Validar pago <i class="far fa-question-circle fa-lg" style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="Al estar activo no permite ingresar Nº de operacion duplicados al reigstrar un pago"></i></label>
              <div class="col-md-8">
                <input type="hidden" name="ajuste[valida_pago]">
                <input class="chk" type="checkbox" name="ajuste[valida_pago]" <?= $config['valida_pago'] == 'on' ? "checked" : ""; ?>>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-form-label col-md-4">Facturar Suspendidos <i class="far fa-question-circle fa-lg" style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="" data-original-title="Al estar activo permite seguir creando factura automaticamente a clientes suspendidos"></i></label>
              <div class="col-md-8">
                <input type="hidden" name="ajuste[facturacontinua]">
                <input class="chk" type="checkbox" name="ajuste[facturacontinua]" <?= $config['facturacontinua'] == 'on' ? "checked" : ""; ?>>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-form-label col-md-4">SMS al suspender <i class="far fa-question-circle fa-lg" style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="" data-original-title="Enviar sms notificando que su servicio fue suspendido. Esta función solo está disponible para cortes Automáticos."></i></label>
              <div class="col-md-8">
                <input type="hidden" name="ajuste[sms_suspendido]">
                <input class="chk" type="checkbox" name="ajuste[sms_suspendido]" <?= $config['sms_suspendido'] == 'on' ? "checked" : ""; ?>>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-form-label col-md-4">SMS al Pagar factura <i class="far fa-question-circle fa-lg" style="cursor:pointer" data-toggle="tooltip" data-placement="top" title="" data-original-title="Enviar sms al cliente notificando que su pago fué confirmado."></i></label>
              <div class="col-md-8">
                <input type="hidden" name="ajuste[sms_alpagar]">
                <input class="chk" type="checkbox" name="ajuste[sms_alpagar]" <?= $config['sms_alpagar'] == 'on' ? "checked" : ""; ?>>
              </div>
            </div>


          </div>
          <div class="panel-footer text-right">
            <button type="submit" class="btn-azul btn-sm m-l-5"><i class="far fa-save"></i> Guardar cambios</button>
          </div>
        </div>
      </div>
    </div>
  </form>

  <script>
    $(function() {
      $('[name="ajuste\[currency\]"] option[value="<?= $config['currency']; ?>"]').prop('selected', true);
      var elems = Array.prototype.slice.call(document.querySelectorAll('.chk'));
      elems.forEach(function(html) {
        var switchery = new Switchery(html, {
          size: 'small',
          color: 'rgb(18, 187, 168)'
        });
      });

      App.setPageTitle('Configuración');

      $('.frmajustes').ajaxForm({
        success: function(data) {
          $('#gritter-notice-wrapper').remove();
          if (data == 1) {
            alerta('exito', 'Datos guardados correctamente');
          } else if (data == 2) {
            alerta('warning', 'Error al actualizar.');
          } else {
            alerta('warning', "No cuentas con los permisos necesarios.")
          }
        },
        error: function(response) {
          $('#gritter-notice-wrapper').remove();
          alerta('error500', response['responseText']);
        }
      })

      $('.sl2').select2();

    })
  </script>