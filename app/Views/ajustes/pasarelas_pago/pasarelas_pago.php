<div>
  <ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="/admin">Inicio</a></li>
    <li class="breadcrumb-item"><a href="/admin/#ajax/ajustes">Ajustes</a></li>
    <li class="breadcrumb-item active">Pasarela</li>
  </ol>

  <h1 class="page-header">Pasarelas de pago</h1>


  <ul class="nav nav-tabs">
    <li class="nav-items"><a href="#default-tab-1" data-toggle="tab" class="nav-link active">Formas de pago</a></li>
    <li class="nav-items"><a href="#default-tab-2" data-toggle="tab" class="nav-link">Otras formas de pago</a></li>
  </ul>


  <div class="tab-content">

    <div class="tab-pane fade active show" id="default-tab-1">

      <div class="row">

        <div class="col-xl-6">
          <div class="panel panel-default">
            <div class="panel-heading" data-click="panel-collapse" style="cursor: pointer">
              <h4 class="panel-title text-center">

                <img src="images/banderas/ecuador.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Ecuador">
                <img src="images/banderas/mexico.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="México">
                <img src="images/banderas/espana.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="España">



                <img src="https://www.paypalobjects.com/images/shared/paypal-logo-129x32.svg" style="height: 25px;width: auto;">
              </h4>
            </div>
            <div class="panel-body border" style="display: none;">
              <form method="post" class="formin form-horizontal frm-pasarela" action="ajax/ajustes?action=savepasarela&tipo=paypal">

                <div class="form-group row">
                  <label>ID cliente</label>
                  <input type="text" class="form-control" name="paypal[pin]" value="Acnq9vm2DnHtlI7kUA5juvwyIe1LGxUBacTgd18pD6i2MjHQd5tBYEJuvmnblxjAMnr6oYCrYcOUiEHP">
                </div>

                <div class="form-group row">
                  <label>Secret</label>
                  <input type="text" class="form-control" name="paypal[cuenta]" value="EHWA49u-kVA_CCv4xU2O5ajTyd2K3zit3Ib2TQf8TMPeR1aH4preaH_HfWa8uzTD5D4kbZ4Kth0D0dRp">
                </div>

                <div class="form-group row">
                  <label>Moneda</label>
                  <select class="form-control sl2" name="paypal[correo]" style="width: 100%">
                    <option value="USD">Dólar Americano</option>
                    <option value="MXN" selected>Peso Mexicano</option>
                    <option value="EUR">Euro</option>
                  </select>
                </div>

                <div class="form-group row">
                  <label style="width: 100%;">Comisión</label>
                  <div class="col-sm-6 comi" style="padding-right: 0px;">
                    <div class="input-group">
                      <input type="number" id="porcentaje" name="paypal[porcentaje]" class="form-control" value="5.40" step="0.01">
                      <span class="input-group-addon"><b>%</b></span>
                    </div>
                    <small>Por defecto: 5.40 %</small>
                  </div>

                  <div class="col-md-6">
                    <div class="input-group">
                      <span class="input-group-addon"><b><i class="fas fa-plus"></i></b></span>
                      <input type="number" id="fijo" name="paypal[fijo]" class="form-control" value="0.30" step="0.01">
                    </div>

                    <small>Por defecto: 0.30</small>
                  </div>

                  <small class="text-center" style="width:100%;float: left;">Puede revisar el valor correcto en : <a href="https://www.paypal.com/us/webapps/mpp/merchant-fees" target="_blank">Lista de comisiones</a></small>

                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Cobrar comisión</label>
                  <div class="col-md-8">
                    <input type="hidden" name="paypal[comision]" value="0">
                    <input class="chk" type="checkbox" name="paypal[comision]" value="1" checked>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Sanbox <i class="far fa-question-circle fa-lg" data-toggle="tooltip" data-placement="top" title="Sandbox: Es el modo de pruebas Paypal para mas detalles consulte con la documentación de paypal."></i></label>
                  <div class="col-md-8">
                    <input type="hidden" name="paypal[sandbox]" value="live">
                    <input class="chk" type="checkbox" name="paypal[sandbox]" value="sandbox" checked>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Activar pasarela</label>
                  <div class="col-md-8">
                    <input type="hidden" name="paypal[estado]">
                    <input class="chk" type="checkbox" name="paypal[estado]">
                  </div>
                </div>

                <div class="panel-footer text-right">
                  <button type="submit" class="btn-azul btn-sm m-l-5"><i class="far fa-save"></i> Guardar cambios</button>
                </div>


              </form>
            </div>
          </div>
        </div>

        <div class="col-xl-6">
          <div class="panel panel-default">
            <div class="panel-heading" data-click="panel-collapse" style="cursor: pointer">
              <h4 class="panel-title text-center">

                <img src="images/banderas/argentina.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Argentina">
                <img src="images/banderas/mexico.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="México">
                <img src="images/banderas/chile.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Chile">
                <img src="images/banderas/colombia.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Colombia">
                <img src="images/banderas/peru.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Perú">
                <img src="images/banderas/venezuela.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Venezuela">

                <img src="https://www.mercadopago.com/org-img/MP3/home/logomp3.gif" style="height: 25px;width: auto;">
                <div class="pull-right"><span class="stateup"></span></div>
              </h4>




            </div>
            <div class="panel-body border" style="display: none">
              <form method="post" class="formin form-horizontal frm-pasarela" action="ajax/ajustes?action=savepasarela&tipo=mp">


                <div class="form-group row">
                  <label>Moneda</label>
                  <select class="form-control sl2" name="mp[correo]" style="width: 100%">
                    <option value="ARS">Peso argentino</option>
                    <option value="BOB">Boliviano</option>
                    <option value="BRL">Real</option>
                    <option value="CLF">Unidad de Fomento</option>
                    <option value="CLP">Peso Chileno</option>
                    <option value="COP">Peso colombiano</option>
                    <option value="CRC">Colones</option>
                    <option value="CUC">Peso Cubano Convertible</option>
                    <option value="CUP">Peso Cubano</option>
                    <option value="DOP">Peso Dominicano</option>
                    <option value="EUR">Euro</option>
                    <option value="GTQ">Quetzal Guatemalteco</option>
                    <option value="HNL">Lempira</option>
                    <option value="MXN">Peso Mexicano</option>
                    <option value="NIO">Córdoba</option>
                    <option value="PAB">Balboa</option>
                    <option value="PEN">Soles</option>
                    <option value="PYG">Guaraní</option>
                    <option value="USD">Dólar</option>
                    <option value="UYU">Peso Uruguayo</option>
                    <option value="VEF">Bolivar fuerte</option>
                    <option value="VES">Bolivar Soberano</option>
                  </select>
                </div>

                <div class="form-group row">
                  Credendciales :
                  <a href="https://www.mercadopago.com/mla/herramientas/aplicaciones" target="_blank">Argentina</a> | <a href="https://www.mercadopago.com/mlc/herramientas/aplicaciones" target="_blank">Chile</a> | <a href="https://www.mercadopago.com/mco/herramientas/aplicaciones" target="_blank">Colombia</a> | <a href="https://www.mercadopago.com/mlm/herramientas/aplicaciones" target="_blank">Mexico</a> | <a href="https://www.mercadopago.com/mpe/account/credentials?type=basic" target="_blank">Peru</a> | <a href="https://www.mercadopago.com/mlv/herramientas/aplicaciones" target="_blank">venezuela</a>
                </div>

                <div class="form-group row">
                  <label>ID cliente</label>
                  <input type="text" class="form-control" name="mp[pin]" value="7804586449470086" required="">
                </div>


                <div class="form-group row">
                  <label>Secret</label>
                  <input type="text" class="form-control" name="mp[cuenta]" value="2kgkwtRYT8bDWHcyMgJmRoyM1sojotda" required="">
                </div>



                <div class="form-group row">
                  <label style="width: 100%;">Comisión</label>
                  <div class="col-sm-6 comi" style="padding-right: 0px;">
                    <div class="input-group">
                      <input type="number" name="mp[porcentaje]" class="form-control" value="5.40" step="0.01">
                      <span class="input-group-addon"><b>%</b></span>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="input-group">
                      <span class="input-group-addon"><b><i class="fas fa-plus"></i></b></span>
                      <input type="number" name="mp[fijo]" class="form-control" value="0.30" step="0.01">
                    </div>
                  </div>

                  <small class="text-center" style="width:100%;float: left;">Puede revisar el valor correcto en : <a href="https://www.mercadopago.com" target="_blank">Aquí</a></small>

                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Cobrar comisión</label>
                  <div class="col-md-8">
                    <input type="hidden" name="mp[comision]" value="0">
                    <input class="chk" type="checkbox" name="mp[comision]" value="1" checked>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Sanbox <i class="far fa-question-circle fa-lg" data-toggle="tooltip" data-placement="top" title="Sandbox: Es el modo de pruebas de mercadopago para mas detalles consulte con la documentación del proveedor."></i></label>
                  <div class="col-md-8">
                    <input type="hidden" name="mp[sandbox]" value="live">
                    <input class="chk" type="checkbox" name="mp[sandbox]" value="sandbox">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Pagos por ticket</label>
                  <div class="col-md-8">
                    <input type="hidden" name="mp[mp_ticket]">
                    <input class="chk" type="checkbox" name="mp[mp_ticket]" checked>
                  </div>
                  <small>* pagofacil,rapipago,efecty,oxxo,etc.</small>
                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Pagos Efectivo</label>
                  <div class="col-md-8">
                    <input type="hidden" name="mp[mp_atm]">
                    <input class="chk" type="checkbox" name="mp[mp_atm]" checked>
                  </div>
                  <small>* Efectivo en agentes y Transferencia Bancaria.</small>
                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Activar pasarela</label>
                  <div class="col-md-8">
                    <input type="hidden" name="mp[estado]">
                    <input class="chk" type="checkbox" name="mp[estado]" checked>
                  </div>
                </div>

                <div class="panel-footer text-right">
                  <button type="submit" class="btn-azul btn-sm m-l-5"><i class="far fa-save"></i> Guardar cambios</button>
                </div>


              </form>
            </div>
          </div>
        </div>

        <div class="col-xl-6">
          <div class="panel panel-default">
            <div class="panel-heading" data-click="panel-collapse" style="cursor: pointer">
              <h4 class="panel-title text-center">

                <img src="images/banderas/argentina.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Argentina">
                <img src="images/banderas/mexico.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="México">
                <img src="images/banderas/colombia.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Colombia">
                <img src="images/banderas/peru.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Perú">

                <img src="https://lh5.googleusercontent.com/fUTUpvlLo4nAiaQUIANW2I42_mlFvm_TCG93Ced4ALNvIOV-hvMBvc51vlVLRISRWd-i0pAtm7qSD7ptFXPbtO6529XKQNEK2xP8_QGECx4oMjlQQruALnu_KMwNnjCcnM_zDaIb" style="height: 30px;width: auto;">
              </h4>
            </div>
            <div class="panel-body border" style="display: none">
              <form method="post" class="formin form-horizontal frm-pasarela" action="ajax/ajustes?action=savepasarela&tipo=payu">

                <div class="form-group row">
                  <label>ID Cuenta</label>
                  <input type="text" class="form-control" name="payu[idserver]" value="" required="">
                </div>


                <div class="form-group row">
                  <label>API key</label>
                  <input type="text" class="form-control" name="payu[pin]" value="" required="">
                </div>


                <div class="form-group row">
                  <label>Api login</label>
                  <input type="text" class="form-control" name="payu[cuenta]" value="" required="">
                </div>

                <div class="form-group row">
                  <label>ID comercio</label>
                  <input type="text" class="form-control" name="payu[correo]" value="" required="">
                </div>

                <div class="form-group row">
                  <label>País</label>
                  <select class="form-control sl2" name="payu[pais]" id="paispayu" style="width: 100%">
                    <option value="ARS">Argentina</option>
                    <option value="COP">Colombia</option>
                    <option value="MXN">México</option>
                    <option value="PEN">Perú</option>
                  </select>
                </div>


                <div class="form-group row">
                  <label style="width: 100%;">Comisión</label>
                  <div class="col-sm-6 comi" style="padding-right: 0px;">
                    <div class="input-group">
                      <input type="number" name="payu[porcentaje]" class="form-control" value="5.40" step="0.01">
                      <span class="input-group-addon"><b>%</b></span>
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="input-group">
                      <span class="input-group-addon"><b><i class="fas fa-plus"></i></b></span>
                      <input type="number" name="payu[fijo]" class="form-control" value="0.30" step="0.01">
                    </div>
                  </div>

                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Cobrar comisión</label>
                  <div class="col-md-8">
                    <input type="hidden" name="payu[comision]" value="0">
                    <input class="chk" type="checkbox" name="payu[comision]" value="1">
                  </div>
                </div>


                <div class="form-group row">
                  <label class="col-form-label col-md-4">Sanbox <i class="far fa-question-circle fa-lg" data-toggle="tooltip" data-placement="top" title="Sandbox: Permite realizar pruebas con esta pasarela. Para mas detalles consulte la documentación de Payu."></i></label>
                  <div class="col-md-8">
                    <input type="hidden" name="payu[sandbox]" value="live">
                    <input class="chk" type="checkbox" name="payu[sandbox]" value="sandbox">
                  </div>
                </div>


                <div class="form-group row d-none">
                  <label>Pago por defecto</label>
                  <select class="form-control sl2" name="payu[mp_atm]" id="metodopayu" style="width: 100%">
                  </select>
                  <small>Forma de pago por defecto para crear ticket al generar nueva factura</small>
                </div>

                <div class="form-group row d-none">
                  <label class="col-form-label col-md-4">Enviar Cupón</label>
                  <div class="col-md-8">
                    <input type="hidden" name="payu[mp_ticket]">
                    <input class="chk" type="checkbox" name="payu[mp_ticket]">
                  </div>
                  <small>* Enviar cupón de pago al crear una nueva factura.</small>
                </div>


                <div class="form-group row">
                  <label class="col-form-label col-md-4">Activar pasarela</label>
                  <div class="col-md-8">
                    <input type="hidden" name="payu[estado]">
                    <input class="chk" type="checkbox" name="payu[estado]">
                  </div>
                </div>

                <div class="panel-footer text-right">
                  <button type="submit" class="btn-azul btn-sm m-l-5"><i class="far fa-save"></i> Guardar cambios</button>
                </div>


              </form>
            </div>
          </div>
        </div>


        <div class="col-xl-6">
          <div class="panel panel-default">
            <div class="panel-heading" data-click="panel-collapse" style="cursor: pointer">
              <h4 class="panel-title text-center">

                <img src="images/banderas/colombia.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Colombia">



                <img src="https://comercios.wompi.co/_nuxt/img/main-logo.bd9f2bf.png" style="height: 30px;width: auto;">
              </h4>
            </div>
            <div class="panel-body border" style="display: none">
              <form method="post" class="formin form-horizontal frm-pasarela" action="ajax/ajustes?action=savepasarela&tipo=wompi">

                <div class="text-center m-b-20">
                  <a href="https://docs.mikrosystem.net/docs/wompi" target="_blank" class="btn btn-white"><i class="fas fa-external-link-alt"></i> Visita nuestra documentación</a>
                </div>
                <div class="form-group row">
                  <label>Llave pública</label>
                  <input type="text" class="form-control" name="wompi[pin]" value="" required="">
                </div>



                <div class="form-group row">
                  <label class="col-form-label col-md-4">Activar pasarela</label>
                  <div class="col-md-8">
                    <input type="hidden" name="wompi[estado]">
                    <input class="chk" type="checkbox" name="wompi[estado]">
                  </div>
                </div>
                <small class="text-success"><b>NOTA: Para validar los pagos necesita agregar un webhook en su cuenta de wompi, la url agregar deber por IP publica/dominio del servidor mikrowisp por ejemplo: http://xxx.xxx.x.x/cliente/ajax/wompi</b></small>
                <div class="panel-footer text-right">
                  <button type="submit" class="btn-azul btn-sm m-l-5"><i class="far fa-save"></i> Guardar cambios</button>
                </div>


              </form>
            </div>
          </div>
        </div>



        <div class="col-xl-6">
          <div class="panel panel-default">
            <div class="panel-heading" data-click="panel-collapse" style="cursor: pointer">
              <h4 class="panel-title text-center">

                <img src="images/banderas/mexico.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="México">



                <img src="https://s3.amazonaws.com/conekta.static/business/oxxopay/oxxopay_logo.svg" style="height: 30px;width: auto;">
              </h4>
            </div>
            <div class="panel-body border" style="display: none">
              <form method="post" class="formin form-horizontal frm-pasarela" action="ajax/ajustes?action=savepasarela&tipo=oxxo">


                <div class="form-group row">
                  <label>API Key</label>
                  <input type="text" class="form-control" name="oxxo[pin]" value="" placeholder="key_..." required="">
                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Enviar Cupón</label>
                  <div class="col-md-8">
                    <input type="hidden" name="oxxo[mp_ticket]">
                    <input class="chk" type="checkbox" name="oxxo[mp_ticket]">
                  </div>
                  <small>* Enviar cupón de pago al crear una nueva factura.</small>
                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Activar pasarela</label>
                  <div class="col-md-8">
                    <input type="hidden" name="oxxo[estado]">
                    <input class="chk" type="checkbox" name="oxxo[estado]">
                  </div>
                </div>
                <small class="text-success"><b>NOTA: Para validar los pagos de OXXO PAY necesita agregar un webhook en su cuenta de conekta, la url agregar deber por IP publica/dominio del servidor mikrowisp por ejemplo: http://xxx.xxx.x.x/admin/ajax/oxxopay</b></small>
                <div class="panel-footer text-right">
                  <button type="submit" class="btn-azul btn-sm m-l-5"><i class="far fa-save"></i> Guardar cambios</button>
                </div>


              </form>
            </div>
          </div>
        </div>

        <div class="col-xl-6">
          <div class="panel panel-default">
            <div class="panel-heading" data-click="panel-collapse" style="cursor: pointer">
              <h4 class="panel-title text-center">

                <img src="images/banderas/colombia.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Colombia">

                <img src="images/epayco.png" style="height: 25px;width: auto;">
              </h4>
            </div>
            <div class="panel-body border" style="display: none">
              <form method="post" class="formin form-horizontal frm-pasarela" action="ajax/ajustes?action=savepasarela&tipo=epayco">

                <div class="form-group row">
                  <label>P_CUST_ID_CLIENTE</label>
                  <input type="text" class="form-control" name="epayco[correo]" value="" required="">
                </div>

                <div class="form-group row">
                  <label>P_KEY</label>
                  <input type="text" class="form-control" name="epayco[cuenta]" value="" required="">
                </div>

                <div class="form-group row">
                  <label>PUBLIC_KEY</label>
                  <input type="text" class="form-control" name="epayco[pin]" value="" required="">
                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Sanbox <i class="far fa-question-circle fa-lg" data-toggle="tooltip" data-placement="top" title="Sandbox: Permite realizar pruebas con esta pasarela. Para mas detalles consulte la documentación de epayco."></i></label>
                  <div class="col-md-8">
                    <input type="hidden" name="epayco[sandbox]" value="live">
                    <input class="chk" type="checkbox" name="epayco[sandbox]" value="sandbox">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Activar pasarela</label>
                  <div class="col-md-8">
                    <input type="hidden" name="epayco[estado]">
                    <input class="chk" type="checkbox" name="epayco[estado]">
                  </div>
                </div>
                <small class="text-success"><b>NOTA: Para validar los pagos Los clientes deben acceder al portal cliente por un dominio publico o IP publica.</b></small>
                <div class="panel-footer text-right">
                  <button type="submit" class="btn-azul btn-sm m-l-5"><i class="far fa-save"></i> Guardar cambios</button>
                </div>


              </form>
            </div>
          </div>
        </div>


        <div class="col-xl-6">
          <div class="panel panel-default">
            <div class="panel-heading" data-click="panel-collapse" style="cursor: pointer">
              <h4 class="panel-title text-center">
                <img src="images/banderas/chile.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Chile">

                <img src="https://khipu.com/assets/logo/logo-purple-large-ec196e63097b7238528b3999de0c18f9.png" style="height: 25px;width: auto;">
              </h4>
            </div>
            <div class="panel-body border" style="display: none">
              <form method="post" class="formin form-horizontal frm-pasarela" action="ajax/ajustes?action=savepasarela&tipo=khipu">


                <div class="form-group row">
                  <label>ID Cobrador</label>
                  <input type="text" class="form-control" name="khipu[pin]" value="" required="">
                </div>

                <div class="form-group row">
                  <label>Llave</label>
                  <input type="text" class="form-control" name="khipu[cuenta]" value="" required="">
                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Activar pasarela</label>
                  <div class="col-md-8">
                    <input type="hidden" name="khipu[estado]">
                    <input class="chk" type="checkbox" name="khipu[estado]">
                  </div>
                </div>

                <div class="panel-footer text-right">
                  <button type="submit" class="btn-azul btn-sm m-l-5"><i class="far fa-save"></i> Guardar cambios</button>
                </div>


              </form>
            </div>
          </div>
        </div>

        <div class="col-xl-6">
          <div class="panel panel-default">
            <div class="panel-heading" data-click="panel-collapse" style="cursor: pointer">
              <h4 class="panel-title text-center">
                <img src="images/banderas/chile.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Chile">
                <img src="https://flow.cl/images/header/logo-flow.svg" style="height: 25px;width: auto;">
              </h4>
            </div>
            <div class="panel-body border" style="display: none">
              <form method="post" class="formin form-horizontal frm-pasarela" action="ajax/ajustes?action=savepasarela&tipo=flow">

                <div class="form-group row">
                  <label>Api Key</label>
                  <input type="text" class="form-control" name="flow[pin]" value="" required="">
                </div>

                <div class="form-group row">
                  <label>Secret Key</label>
                  <input type="text" class="form-control" name="flow[mp_atm]" value="atm" required="">
                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Sanbox <i class="far fa-question-circle fa-lg" data-toggle="tooltip" data-placement="top" title="Sandbox: Permite utilizar la pasarela en modo de pruebas, puede consultar la documentación de flow para mas detalles."></i></label>
                  <div class="col-md-8">
                    <input type="hidden" name="flow[sandbox]" value="live">
                    <input class="chk" type="checkbox" name="flow[sandbox]" value="sandbox">
                  </div>
                </div>


                <div class="form-group row">
                  <label>Medios de pago</label>
                  <select class="slflow form-control sl2" name="flow[correo]" style="width: 100%">
                    <option value="1">webpay</option>
                    <option value="2">Servipag</option>
                    <option value="3">Multicaja</option>
                    <option value="5">Onepay</option>
                    <option value="8">Cryptocompra</option>
                    <option value="9">Todos</option>
                  </select>
                </div>


                <div class="form-group row">
                  <label>Url confirmación</label>
                  <input type="link" class="form-control" name="flow[cuenta]" value="" required="">
                  <small>url de mikrowisp por IP publica o dominio solo para pagos vía multicaja ejm: http://ip-publica-mikrowisp</small>
                </div>


                <div class="form-group row">
                  <label class="col-form-label col-md-4">Activar pasarela</label>
                  <div class="col-md-8">
                    <input type="hidden" name="flow[estado]">
                    <input class="chk" type="checkbox" name="flow[estado]">
                  </div>
                </div>

                <div class="panel-footer text-right">
                  <button type="submit" class="btn-azul btn-sm m-l-5"><i class="far fa-save"></i> Guardar cambios</button>
                </div>


              </form>
            </div>
          </div>
        </div>


        <div class="col-xl-6">
          <div class="panel panel-default">
            <div class="panel-heading" data-click="panel-collapse" style="cursor: pointer">
              <h4 class="panel-title text-center">

                <img src="images/banderas/chile.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Chile">

                <img src="https://www.bancoestado.cl/imagenes/_img/logo-banco-estado.jpg" style="height: 25px;width: auto;"> (Caja Vecina)

              </h4>
            </div>
            <div class="panel-body border" style="display: none">
              <p>Mikrowisp cuenta con un webservice para que Bancoestado puede consultar la factura del cliente y Pagar/Activar un cliente al confirmar un pago.</p>

              <p>La url de mikrowisp debe ser de accesopúblico.</p>

              <p>Para mas detalles consulte nuestra <a href="https://docs.mikrosystem.net/docs/banco-estado" target="_blank">Documentación</a></p>

            </div>
          </div>
        </div>


        <div class="col-xl-6">
          <div class="panel panel-default">
            <div class="panel-heading" data-click="panel-collapse" style="cursor: pointer">
              <h4 class="panel-title text-center">
                <img src="images/banderas/argentina.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Argentina">
                <img src="https://www.bancoroela.com.ar/siroweb/images/logo-siro.png" style="height: 25px;width: auto;">
              </h4>
            </div>
            <div class="panel-body border" style="display: none">
              <form method="post" class="formin form-horizontal frm-pasarela" action="ajax/ajustes?action=savepasarela&tipo=Siro">

                <div class="form-group row">
                  <label style="width: 100%">Tipo de trabajo</label>
                  <select class="sirochangetipo form-control" name="Siro[sandbox]">
                    <option value="live" selected>EnviarBase de pagos automático/Manual al crear facturas</option>
                    <option value="boton">Generar Link de pago desde portal cliente</option>
                  </select>
                </div>

                <div class="form-group row hidesiro">
                  <label style="width: 100%">Fecha Vencimiento</label>
                  <select class="sirochangedias form-control" name="Siro[comision]">
                    <option value="0" selected>Utilizar 3 fechas de vencimiento</option>
                    <option value="1">Utilizar 1 fecha de vencimiento</option>
                  </select>
                </div>

                <div class="form-group row">
                  <label style="width: 100%"> + Días</label>
                  <input type="number" class="form-control" name="Siro[dias]" value="0" required min="1">
                  <small>Ingrese cuantos días va agregar al vencimiento de una factura,Ejm:20)</small>
                </div>

                <div class="form-group row">
                  <label style="width: 100%">Usuario API</label>
                  <input type="text" class="form-control" name="Siro[pin]" value="" required>
                </div>

                <div class="form-group row">
                  <label style="width: 100%">Pasword API</label>
                  <input type="text" class="form-control" name="Siro[cuenta]" value="" required>
                </div>


                <div class="form-group row">
                  <label style="width: 100%">ID Cuenta</label>
                  <input type="text" class="form-control" name="Siro[pais]" value="" required minlength="10" maxlength="10">
                </div>


                <div class="form-group row">
                  <label style="width: 100%">CUIT Administrador</label>
                  <input type="text" class="form-control" name="Siro[correo]" value="" required>
                </div>

                <div class="form-group row">
                  <label style="width: 100%">Nombre Empresa</label>
                  <input type="text" class="form-control" name="Siro[idserver]" value="" required maxlength="15" placeholder="Ejm: MI EMPRESA" style="text-transform: uppercase;">
                  <small class="text-danger">Completar solo con letras mayúsculas (sin acentos, ni "ñ") y números. No admite
                    caracteres especiales (puntos, comas,paréntesis, etc.). </small>
                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Activar pasarela</label>
                  <div class="col-md-8">
                    <input type="hidden" name="Siro[estado]">
                    <input class="chk" type="checkbox" name="Siro[estado]">
                  </div>
                </div>


                <div class="panel-footer text-right">
                  <button type="submit" class="btn-azul btn-sm m-l-5"><i class="far fa-save"></i> Guardar cambios</button>
                </div>


              </form>
            </div>
          </div>
        </div>

        <script>
          $('.sirochangedias').change(function() {
            if ($(this).val() == "0") {
              $('.siromasdias').hide();
            } else {
              $('.siromasdias').show();
            }
          })

          $('.sirochangetipo').change(function() {
            if ($(this).val() == "boton") {
              $('.hidesiro').hide();
            } else {
              $('.hidesiro').show();
            }
          })

          $('.sirochangedias,.sirochangetipo').trigger('change');
        </script>


        <div class="col-xl-6">
          <div class="panel panel-default">
            <div class="panel-heading" data-click="panel-collapse" style="cursor: pointer">
              <h4 class="panel-title text-center">

                <img src="images/banderas/chile.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Chile">

                <img src="https://www.transbankdevelopers.cl/public/library/img/img_webpay.png" style="height: 25px;width: auto;">
              </h4>
            </div>
            <div class="panel-body border" style="display: none">
              <form method="post" class="formin form-horizontal frm-pasarela" action="ajax/ajustes?action=savepasarela&tipo=webpay">

                <div class="form-group row modeinter">
                  <label style="width: 100%">Código comercio</label>
                  <input type="text" class="form-control" name="webpay[pais]" value="0" required="" style="width: 70%">
                  <button type="button" onClick="generatewebpay()" class="btn btn-white" style="width: 30%"><i class="fab fa-expeditedssl fa-lg"></i> Generar Certificado</button>
                </div>

                <div class="form-group row nclientest">
                  <label style="width: 100%">ID Cliente Pruebas <i class="far fa-question-circle fa-lg" data-toggle="tooltip" data-placement="top" title="0=puede probar con cualquier cliente, si indica un ID cliente solo se mostrára para el cliente indicado"></i></label>
                  <input type="text" class="form-control" name="webpay[idserver]" value="
0" required style="width: 100px">
                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Modo integración <i class="far fa-question-circle fa-lg" data-toggle="tooltip" data-placement="top" title="Activando esta opción podrá utilizar los certificados por defectos para pruebas."></i></label>

                  <div class="col-md-8">
                    <label class="switch">
                      <input type="hidden" name="webpay[sandbox]" value="live">
                      <input type="checkbox" id="webpayinter" name="webpay[sandbox]" value="sandbox" checked>
                      <span class="slider round"></span>
                    </label>

                  </div>

                </div>


                <div class="form-group row modeinter">
                  <label>Llave Privada</label>
                  <textarea class="form-control" name="webpay[pin]" required style="min-height: 520px"></textarea>
                </div>

                <div class="form-group row modeinter">
                  <label>Certificado Público</label>
                  <textarea class="form-control" name="webpay[cuenta]" required style="min-height: 400px"></textarea>
                </div>

                <div class="form-group row modeinter">
                  <label>Certificado Webpay</label>
                  <textarea class="form-control" name="webpay[correo]" required style="min-height: 400px"></textarea>
                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Activar pasarela</label>
                  <div class="col-md-8">
                    <input type="hidden" name="webpay[estado]">
                    <input class="chk" type="checkbox" name="webpay[estado]">
                  </div>
                </div>

                <div class="panel-footer text-right">
                  <button type="submit" class="btn-azul btn-sm m-l-5"><i class="far fa-save"></i> Guardar cambios</button>
                </div>


              </form>
            </div>
          </div>
        </div>

        <div class="col-xl-6">
          <div class="panel panel-default">
            <div class="panel-heading" data-click="panel-collapse" style="cursor: pointer">
              <h4 class="panel-title text-center">
                <img src="images/banderas/mexico.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="México">
                <img src="images/logo_pagofacil.png" style="height: 25px;width: auto;">
              </h4>
            </div>
            <div class="panel-body border" style="display: none">
              <form method="post" class="formin form-horizontal frm-pasarela" action="ajax/ajustes?action=savepasarela&tipo=pagofacil">

                <div class="form-group row">
                  <label style="width: 100%">ApiKey Sucursal</label>
                  <input type="text" class="form-control" name="pagofacil[pin]" value="" required>
                </div>

                <div class="form-group row">
                  <label style="width: 100%">ApiKey Usuario</label>
                  <input type="text" class="form-control" name="pagofacil[idserver]" value="" required>
                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Modo integración <i class="far fa-question-circle fa-lg" data-toggle="tooltip" data-placement="top" title="Activando esta opción podrá realizar pruebas sobre esta pasarela."></i></label>

                  <div class="col-md-8">
                    <label class="switch">
                      <input type="hidden" name="pagofacil[sandbox]" value="live">
                      <input type="checkbox" class="chk" name="pagofacil[sandbox]" value="sandbox">
                    </label>

                  </div>

                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Activar pasarela</label>
                  <div class="col-md-8">
                    <input type="hidden" name="pagofacil[estado]">
                    <input class="chk" type="checkbox" name="pagofacil[estado]">
                  </div>
                </div>


                <div class="panel-footer text-right">
                  <button type="submit" class="btn-azul btn-sm m-l-5"><i class="far fa-save"></i> Guardar cambios</button>
                </div>


              </form>
            </div>
          </div>
        </div>

        <div class="col-xl-6">
          <div class="panel panel-default">
            <div class="panel-heading" data-click="panel-collapse" style="cursor: pointer;">
              <h4 class="panel-title text-center">

                <img src="images/banderas/puerto-rico.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Puerto Rico">

                <img src="https://athmovilbusiness.com/images/svg/logos/business_long.svg" style="height: 30px;width: auto;">
              </h4>
            </div>
            <div class="panel-body border" style="display: none">
              <p>Para obtener mas detalles puede visitar: <a href="https://athmovilbusiness.com/" target="_blank">https://athmovilbusiness.com/</a></p>
              <form method="post" class="formin form-horizontal frm-pasarela" action="ajax/ajustes?action=savepasarela&tipo=athmovil">

                <div class="form-group row">
                  <label>Llave Pública</label>
                  <input type="text" class="form-control" name="athmovil[pin]" value="" required="">
                </div>

                <div class="form-group row">
                  <label>Llave Privada</label>
                  <input type="text" class="form-control" name="athmovil[cuenta]" value="" required="">
                </div>

                <div class="form-group row">
                  <label style="width: 100%;">Comisión</label>
                  <div class="col-sm-6 comi" style="padding-right: 0px;">
                    <div class="input-group">
                      <input type="number" id="porcentaje" name="athmovil[porcentaje]" class="form-control" value="2.25" step="0.01">
                      <span class="input-group-addon"><b>%</b></span>
                    </div>
                    <small>Por defecto: 2.25 %</small>
                  </div>

                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Cobrar comisión</label>
                  <div class="col-md-4">
                    <input type="hidden" name="athmovil[comision]" value="0">
                    <input class="chk" type="checkbox" name="athmovil[comision]" value="1">
                  </div>
                </div>


                <div class="form-group row">
                  <label class="col-form-label col-md-4">Activar pasarela</label>
                  <div class="col-md-8">
                    <input type="hidden" name="athmovil[estado]">
                    <input class="chk" type="checkbox" name="athmovil[estado]">
                  </div>
                </div>

                <div class="panel-footer text-right">
                  <button type="submit" class="btn-azul btn-sm m-l-5"><i class="far fa-save"></i> Guardar cambios</button>
                </div>


              </form>
            </div>
          </div>
        </div>

        <div class="col-xl-6">
          <div class="panel panel-default">
            <div class="panel-heading" data-click="panel-collapse" style="cursor: pointer;">
              <h4 class="panel-title text-center">

                <img src="images/banderas/peru.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Perú">

                <img src="https://theme.zdassets.com/theme_assets/1286016/e8a147bae7a85c53eba8b9cc0031655e4f6d3799.png" style="height: 30px;width: auto;">
              </h4>
            </div>
            <div class="panel-body border" style="display: none">
              <p>Para obtener mas detalles puede visitar: <a href="https://www.culqi.com/" target="_blank">https://www.culqi.com/ </a></p>
              <form method="post" class="formin form-horizontal frm-pasarela" action="ajax/ajustes?action=savepasarela&tipo=culqi">

                <div class="form-group row">
                  <label>Llave Pública</label>
                  <input type="text" class="form-control" name="culqi[pin]" value="pk_test_123" required="">
                </div>

                <div class="form-group row">
                  <label>Llave Privada</label>
                  <input type="text" class="form-control" name="culqi[cuenta]" value="sk_test_123" required="">
                </div>


                <div class="form-group row">
                  <label class="col-form-label col-md-4">Cobrar comisión</label>
                  <div class="col-md-8">
                    <input type="hidden" name="culqi[comision]" value="0">
                    <input class="chk" type="checkbox" name="culqi[comision]" value="1">
                    <small class="text-warning">* Se cobrará + 5% al cliente</small>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Activar pasarela</label>
                  <div class="col-md-8">
                    <input type="hidden" name="culqi[estado]">
                    <input class="chk" type="checkbox" name="culqi[estado]">
                  </div>
                </div>

                <div class="panel-footer text-right">
                  <button type="submit" class="btn-azul btn-sm m-l-5"><i class="far fa-save"></i> Guardar cambios</button>
                </div>


              </form>
            </div>
          </div>
        </div>

        <div class="col-xl-6">
          <div class="panel panel-default">
            <div class="panel-heading" data-click="panel-collapse" style="cursor: pointer;">
              <h4 class="panel-title text-center">
                <img src="images/banderas/argentina.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Argentina">
                <img src="../admin/images/cuentadigital-horizontal-transparente.png" style="height: 30px;width: auto;">
              </h4>
            </div>
            <div class="panel-body border" style="display: none">
              <form method="post" class="formin form-horizontal frm-pasarela" action="ajax/ajustes?action=savepasarela&tipo=digital">

                <div class="form-group row">
                  <label>Nº de cuenta</label>
                  <input type="text" class="form-control" name="digital[pin]" value="123" required="">
                  <smal class="text-success">* Necesario si desea crear los cupones desde mikrowisp</smal>
                </div>

                <div class="form-group row">
                  <label>Hash Control</label>
                  <input type="text" class="form-control" name="digital[cuenta]" value="1234" required="">
                  <smal>* Puede agregar varias cuentas separados por una coma sin espacios. Ejm: 12345679,23454378</smal>
                </div>

                <div class="form-group row d-none">
                  <label class="col-form-label col-md-4">Enviar Cupón</label>
                  <div class="col-md-8">
                    <input type="hidden" name="digital[mp_ticket]">
                    <input class="chk" type="checkbox" name="digital[mp_ticket]">
                    <small>* Enviar cupón de pago al crear una nueva factura.</small>
                  </div>

                </div>


                <div class="form-group row">
                  <label class="col-form-label col-md-4">Activar pasarela</label>
                  <div class="col-md-8">
                    <input type="hidden" name="digital[estado]">
                    <input class="chk" type="checkbox" name="digital[estado]">
                  </div>
                </div>

                <div class="panel-footer text-right">
                  <button type="submit" class="btn-azul btn-sm m-l-5"><i class="far fa-save"></i> Guardar cambios</button>
                </div>


              </form>
            </div>
          </div>
        </div>

        <div class="col-xl-6">
          <div class="panel panel-default">
            <div class="panel-heading" data-click="panel-collapse" style="cursor: pointer;">
              <h4 class="panel-title text-center">
                <img src="images/banderas/argentina.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Argentina">
                <img src="../admin/images/cobro-digital.png" style="height: 35px;width: auto;">
              </h4>
            </div>
            <div class="panel-body border" style="display: none">
              <form method="post" class="formin form-horizontal frm-pasarela" action="ajax/ajustes?action=savepasarela&tipo=cobro">

                <div class="form-group">
                  <label>ID Comercio</label>
                  <input type="text" class="form-control" name="cobro[pin]" value="HA123" required="">
                </div>

                <div class="form-group">
                  <label>SID</label>
                  <input type="text" class="form-control" name="cobro[cuenta]" value="12345" required="">
                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Enviar Cupón</label>
                  <div class="col-md-8">
                    <input type="hidden" name="cobro[mp_ticket]">
                    <input class="chk" type="checkbox" name="cobro[mp_ticket]">
                    <small>* Generar código de barra al crear una nueva factura.</small>
                  </div>

                </div>


                <div class="form-group row">
                  <label class="col-form-label col-md-4">Activar pasarela</label>
                  <div class="col-md-8">
                    <input type="hidden" name="cobro[estado]">
                    <input class="chk" type="checkbox" name="cobro[estado]">
                  </div>
                </div>

                <div class="panel-footer text-right">
                  <button type="submit" class="btn-azul btn-sm m-l-5"><i class="far fa-save"></i> Guardar cambios</button>
                </div>


              </form>
            </div>
          </div>
        </div>

        <div class="col-xl-6">
          <div class="panel panel-default">
            <div class="panel-heading" data-click="panel-collapse" style="cursor: pointer">
              <h4 class="panel-title text-center">


                <img src="images/banderas/mexico.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="México">


                <img src="https://s3.amazonaws.com/public.openpay.mx/images/logos/logo-bancomer-background-transparent-letters-dark-size-291-662.png" style="height: 34px;width: auto;">
              </h4>
            </div>
            <div class="panel-body border" style="display: none;">
              <form method="post" class="formin form-horizontal frm-pasarela" action="ajax/ajustes?action=savepasarela&tipo=bbva">

                <div class="form-group row">
                  <label>ID</label>
                  <input type="text" class="form-control" name="bbva[pin]" value="">
                </div>

                <div class="form-group row">
                  <label>LLave Privada</label>
                  <input type="text" class="form-control" name="bbva[cuenta]" value="">
                </div>

                <div class="form-group row">
                  <label>Nº Afiliación Bbva</label>
                  <input type="text" class="form-control" name="bbva[correo]" value="">
                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Sanbox <i class="far fa-question-circle fa-lg" data-toggle="tooltip" data-placement="top" title="Sandbox: Es el modo de pruebas."></i></label>
                  <div class="col-md-8">
                    <input type="hidden" name="bbva[sandbox]" value="live">
                    <input class="chk" type="checkbox" name="bbva[sandbox]" value="sandbox">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Activar pasarela</label>
                  <div class="col-md-8">
                    <input type="hidden" name="bbva[estado]">
                    <input class="chk" type="checkbox" name="bbva[estado]">
                  </div>
                </div>

                <div class="panel-footer text-right">
                  <button type="submit" class="btn-azul btn-sm m-l-5"><i class="far fa-save"></i> Guardar cambios</button>
                </div>


              </form>
            </div>
          </div>
        </div>

        <div class="col-xl-6">
          <div class="panel panel-default">
            <div class="panel-heading" data-click="panel-collapse" style="cursor: pointer">
              <h4 class="panel-title text-center">


                <img src="images/banderas/panama.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Panamá">


                <img src="images/paguelofacil.png" style="height: 34px;width: auto;">
              </h4>
            </div>
            <div class="panel-body border" style="display: none;">
              <form method="post" class="formin form-horizontal frm-pasarela" action="ajax/ajustes?action=savepasarela&tipo=paguelofacil">

                <div class="form-group row">
                  <label>CÖDIGO CCLW</label>
                  <input type="text" class="form-control" name="paguelofacil[pin]" value="">
                </div>

                <div class="form-group row">
                  <label>API TOKEN</label>
                  <input type="text" class="form-control" name="paguelofacil[correo]" value="">
                </div>

                <div class="form-group row">
                  <label>API KEY Nequi</label>
                  <input type="text" class="form-control" name="paguelofacil[cuenta]" value="">
                </div>


                <div class="form-group row">
                  <label class="col-form-label col-md-4">Sanbox <i class="far fa-question-circle fa-lg" data-toggle="tooltip" data-placement="top" title="Sandbox: Es el modo de pruebas."></i></label>
                  <div class="col-md-8">
                    <input type="hidden" name="paguelofacil[sandbox]" value="live">
                    <input class="chk" type="checkbox" name="paguelofacil[sandbox]" value="sandbox">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Activar pasarela</label>
                  <div class="col-md-8">
                    <input type="hidden" name="paguelofacil[estado]">
                    <input class="chk" type="checkbox" name="paguelofacil[estado]">
                  </div>
                </div>

                <div class="panel-footer text-right">
                  <button type="submit" class="btn-azul btn-sm m-l-5"><i class="far fa-save"></i> Guardar cambios</button>
                </div>


              </form>
            </div>
          </div>
        </div>

        <div class="col-xl-6">
          <div class="panel panel-default">
            <div class="panel-heading" data-click="panel-collapse" style="cursor: pointer">
              <h4 class="panel-title text-center">


                <img src="images/banderas/colombia.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Colombia">


                <img src="images/combopay.png" style="height: 48px;width: auto;">
              </h4>
            </div>
            <div class="panel-body border" style="display: none;">
              <form method="post" class="formin form-horizontal frm-pasarela" action="ajax/ajustes?action=savepasarela&tipo=mirecaudo">
                <p class="text-center m-b-20 m-t-20"><a href="https://combopay.co/registro" target="_blank">Para poder registrarse puede ir a https://combopay.co</a></p>
                <div class="form-group row">
                  <label>SECRET CLIENTE</label>
                  <input type="text" class="form-control" name="mirecaudo[pin]" value="">
                </div>

                <div class="form-group row">
                  <label>USUARIO</label>
                  <input type="text" class="form-control" name="mirecaudo[correo]" value="">
                </div>

                <div class="form-group row">
                  <label>CONTRASEÑA</label>
                  <input type="text" class="form-control" name="mirecaudo[cuenta]" value="">
                </div>

                <div class="form-group row">
                  <label>ID CLIENTE</label>
                  <input type="text" class="form-control" name="mirecaudo[pais]" value="">
                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Activar pasarela</label>
                  <div class="col-md-8">
                    <input type="hidden" name="mirecaudo[estado]">
                    <input class="chk" type="checkbox" name="mirecaudo[estado]">
                  </div>
                </div>

                <div class="panel-footer text-right">
                  <button type="submit" class="btn-azul btn-sm m-l-5"><i class="far fa-save"></i> Guardar cambios</button>
                </div>


              </form>
            </div>
          </div>
        </div>

        <div class="col-xl-6">
          <div class="panel panel-default">
            <div class="panel-heading" data-click="panel-collapse" style="cursor: pointer">
              <h4 class="panel-title text-center">

                <img src="images/banderas/chile.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Chile">

                <img src="images/banderas/peru.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Perú">

                <img src="images/banderas/colombia.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Colombia">

                <img src="images/banderas/ecuador.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="Ecuador">

                <img src="images/banderas/mexico.png" style="height: 25px;float: left;padding-right: 5px;" data-toggle="tooltip" title="México">


                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTM5IiBoZWlnaHQ9IjM0IiB2aWV3Qm94PSIwIDAgMTM5IDM0IiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cGF0aCBkPSJNNzQuMjc3OCAxMC40MTk4VjE4LjQ5OTFDNzQuMzAyMSAxOS40NjY2IDc0LjEyNzEgMjAuNDI4NiA3My43NjM5IDIxLjMyMzhDNzMuNDAwNyAyMi4yMTkgNzIuODU3MiAyMy4wMjc4IDcyLjE2ODEgMjMuNjk4NkM3MS41NDI2IDI0LjM0MjUgNzAuNzk0OSAyNC44NTE1IDY5Ljk3IDI1LjE5NTJDNjkuMTQ1IDI1LjUzODkgNjguMjYgMjUuNzEwMSA2Ny4zNjggMjUuNjk4NEM2Ni40MTgyIDI1LjcyNDIgNjUuNDczMSAyNS41NTQyIDY0LjU5MDIgMjUuMTk4N0M2My43MDczIDI0Ljg0MzMgNjIuOTA1IDI0LjMwOTggNjIuMjMyMSAyMy42MzA2QzYxLjU2MTEgMjIuOTgxMyA2MS4wMzA0IDIyLjE5ODMgNjAuNjczNCAyMS4zMzA5QzYwLjMxNjQgMjAuNDYzNSA2MC4xNDA5IDE5LjUzMDYgNjAuMTU3OSAxOC41OTExVjkuOTcxODZDNjAuMTQxIDkuNjcyNzcgNjAuMTgzMyA5LjM3MzI4IDYwLjI4MjQgOS4wOTA5OEM2MC4zODE0IDguODA4NjggNjAuNTM1MiA4LjU0OTI4IDYwLjczNDcgOC4zMjgwMUM2MC45MjE2IDguMTMzNzIgNjEuMTQ2MiA3Ljk4MDc5IDYxLjM5NDQgNy44Nzg5QzYxLjY0MjYgNy43NzcwMSA2MS45MDkgNy43MjgzNyA2Mi4xNzY3IDcuNzM2MDdDNjIuNDMyNyA3LjcyODI4IDYyLjY4NzcgNy43NzMxOCA2Mi45MjYgNy44NjgwNUM2My4xNjQ0IDcuOTYyOTMgNjMuMzgxMiA4LjEwNTggNjMuNTYzNCA4LjI4ODAyQzYzLjc2OTYgOC41MTM5OCA2My45MjkzIDguNzc5MjYgNjQuMDMzIDkuMDY4MzZDNjQuMTM2OCA5LjM1NzQ3IDY0LjE4MjcgOS42NjQ2MiA2NC4xNjc5IDkuOTcxODZWMTguNTk5MUM2NC4xNTUyIDE5LjAxOSA2NC4yMjY3IDE5LjQzNzIgNjQuMzc4MSAxOS44Mjg0QzY0LjUyOTUgMjAuMjE5NSA2NC43NTc3IDIwLjU3NTUgNjUuMDQ4OSAyMC44NzQ5QzY1LjM0MjEgMjEuMTc2NSA2NS42OTQxIDIxLjQxMjkgNjYuMDgyNCAyMS41NjkyQzY2LjQ3MDggMjEuNzI1NiA2Ni44ODcxIDIxLjc5ODMgNjcuMzA0OCAyMS43ODI4QzY4LjA0OCAyMS43OTMxIDY4Ljc2NTIgMjEuNTA1NiA2OS4yOTk5IDIwLjk4MjlDNjkuNjIwMyAyMC42NjA3IDY5Ljg3MTEgMjAuMjc0NiA3MC4wMzY0IDE5Ljg0OTFDNzAuMjAxNyAxOS40MjM2IDcwLjI3NzggMTguOTY4IDcwLjI1OTkgMTguNTExMVY5Ljk3MTg2QzcwLjI0MiA5LjY3Mzk3IDcwLjI4MjMgOS4zNzU0MiA3MC4zNzg2IDkuMDkzMzNDNzAuNDc0OCA4LjgxMTI0IDcwLjYyNTIgOC41NTExNiA3MC44MjA5IDguMzI4MDFDNzEuMDA1MiA4LjEzMzY1IDcxLjIyNzcgNy45ODA1NSA3MS40NzQgNy44Nzg1OUM3MS43MjA0IDcuNzc2NjQgNzEuOTg1MSA3LjcyODA4IDcyLjI1MTEgNy43MzYwN0M3Mi42MDc0IDcuNzE0ODYgNzIuOTYyMiA3Ljc5Nzg1IDczLjI3MzIgNy45NzUxNEM3My41ODQzIDguMTUyNDMgNzMuODM4MyA4LjQxNjU0IDc0LjAwNTIgOC43MzU5OEM3NC4yMjIyIDkuMjY4NzMgNzQuMzE1NSA5Ljg0NDgxIDc0LjI3NzggMTAuNDE5OFYxMC40MTk4WiIgZmlsbD0iIzFGMzY2NCIvPgo8cGF0aCBkPSJNODUuNDE0OCA3LjYyMDA4Qzg2LjgyMjIgNy41Nzg4MiA4OC4yMTIgNy45NDUxNCA4OS40MjA4IDguNjc1OTlDOTAuNDI4MyA5LjMzOTkzIDkwLjkzIDEwLjA3MTkgOTAuOTMgMTAuODYzOEM5MC45NDc5IDExLjEzNTIgOTAuOTEyNSAxMS40MDc2IDkwLjgyNTcgMTEuNjY1QzkwLjczODkgMTEuOTIyNSA5MC42MDI0IDEyLjE2IDkwLjQyNDMgMTIuMzYzN0M5MC4yNjE1IDEyLjUzNTkgOTAuMDY1IDEyLjY3MTkgODkuODQ3MyAxMi43NjI4Qzg5LjYyOTYgMTIuODUzNyA4OS4zOTU1IDEyLjg5NzYgODkuMTYwMSAxMi44OTE2Qzg4LjgyOTEgMTIuODc2MiA4OC41MTEyIDEyLjc1NTkgODguMjUxNCAxMi41NDc2Qzg3LjkzNTQgMTIuMzE5NyA4Ny42MzUxIDEyLjA4MzcgODcuMzM4OCAxMS44MzU3Qzg2Ljc5MDYgMTEuMzQxMSA4Ni4wOTU5IDExLjA0NDQgODUuMzYzNCAxMC45OTE4Qzg0Ljg0NiAxMC45NzM1IDg0LjMzOTEgMTEuMTQyMiA4My45MzMzIDExLjQ2NzdDODMuNzU5MyAxMS41OTQ2IDgzLjYxNjkgMTEuNzYwOCA4My41MTc1IDExLjk1MzFDODMuNDE4IDEyLjE0NTQgODMuMzY0MiAxMi4zNTg2IDgzLjM2MDQgMTIuNTc1NkM4My4zNjA0IDEzLjM3NTYgODQuMDYzNyAxMy45MzU1IDg1LjQ3NDEgMTQuMjg3NUM4Ny42NzA3IDE0LjgzMTQgODkuMjE1NCAxNS40ODc0IDkwLjEyNDEgMTYuMjM5M0M5MS4yMTcxIDE3LjEzNTIgOTEuNzY0OSAxOC40MDA0IDkxLjc2NzYgMjAuMDM1QzkxLjc4NTggMjAuODAwMSA5MS42NDAyIDIxLjU2MDMgOTEuMzQwOSAyMi4yNjMxQzkxLjA0MTUgMjIuOTY1OSA5MC41OTU0IDIzLjU5NDggOTAuMDMzMiAyNC4xMDY2Qzg4LjgyMjkgMjUuMjA2NiA4Ny4yMzc3IDI1Ljc4NzUgODUuNjEyMyAyNS43MjY0QzgzLjkxNDQgMjUuNzgzOSA4Mi4yNDE4IDI1LjI5ODMgODAuODMyIDI0LjMzODZDNzkuNjQ2OCAyMy40OTg3IDc5LjAzMDQgMjIuNTM4NyA3OS4wMzA0IDIxLjQ2NjhDNzkuMDIzOCAyMC45NTY1IDc5LjIxNTMgMjAuNDYzOCA3OS41NjM4IDIwLjA5NUM3OS43Mjk1IDE5LjkwODkgNzkuOTMyNiAxOS43NjA4IDgwLjE1OTYgMTkuNjYwOEM4MC4zODY1IDE5LjU2MDggODAuNjMxOSAxOS41MTExIDgwLjg3OTQgMTkuNTE1QzgxLjUwMzYgMTkuNTE1IDgyLjE5NzYgMTkuODU3NiA4Mi45NjE0IDIwLjU0MjlDODMuOTY4OCAyMS40NDI4IDg0Ljg2MTcgMjEuODg2OCA4NS42NDQgMjEuODg2OEM4Ni4yMDI3IDIxLjkxMSA4Ni43NDg2IDIxLjcxMjkgODcuMTY1IDIxLjMzNDhDODcuMzU4MSAyMS4xNTQgODcuNTEwNiAyMC45MzMzIDg3LjYxMjEgMjAuNjg3N0M4Ny43MTM3IDIwLjQ0MiA4Ny43NjE5IDIwLjE3NzEgODcuNzUzNiAxOS45MTFDODcuNzUzNiAxOS4yNzkgODcuMzk0MSAxOC43OTUxIDg2LjY4MyAxOC40NjMxQzg1LjgwNjQgMTguMTEzIDg0LjkxMDggMTcuODEzOSA4NC4wMDA0IDE3LjU2NzJDODIuNDQxMiAxNy4wOTI2IDgxLjMyMTkgMTYuNTU5MyA4MC42NDIzIDE1Ljk2NzNDODAuMjI4OSAxNS41NzQ3IDc5LjkwNTMgMTUuMDk1MiA3OS42OTQ0IDE0LjU2MjRDNzkuNDgzNSAxNC4wMjk3IDc5LjM5MDQgMTMuNDU2NyA3OS40MjE2IDEyLjg4MzZDNzkuMzk5MSAxMi4xNjI5IDc5LjUzNzMgMTEuNDQ2MyA3OS44MjU5IDEwLjc4NzJDODAuMTE0NiAxMC4xMjggODAuNTQ2MiA5LjU0MzA4IDgxLjA4ODggOS4wNzU5NUM4Mi4yMDAyIDguMTA1MzcgODMuNjQyMyA3LjYyMDA4IDg1LjQxNDggNy42MjAwOFoiIGZpbGw9IiMxRjM2NjQiLz4KPHBhdGggZD0iTTEwMC4yOTMgOS45Njc4OVYxNC42Njc1SDEwNi43MjVWOS45Njc4OUMxMDYuNzA4IDkuNjY5MzcgMTA2Ljc1IDkuMzcwMzggMTA2Ljg0OSA5LjA4ODY4QzEwNi45NDggOC44MDY5OCAxMDcuMTAyIDguNTQ4MzEgMTA3LjMwMiA4LjMyODAzQzEwNy40ODkgOC4xMzMzNSAxMDcuNzEzIDcuOTgwMTYgMTA3Ljk2MSA3Ljg3ODI0QzEwOC4yMSA3Ljc3NjMyIDEwOC40NzYgNy43Mjc5MSAxMDguNzQ0IDcuNzM2MDlDMTA5LjAwOSA3LjcyODE3IDEwOS4yNzQgNy43NzY3NyAxMDkuNTE5IDcuODc4NzRDMTA5Ljc2NSA3Ljk4MDcxIDEwOS45ODcgOC4xMzM3OCAxMTAuMTcgOC4zMjgwM0MxMTAuNTY1IDguNzc5MTggMTEwLjc2NyA5LjM3MDM1IDExMC43MzEgOS45NzE4OFYyMy4zNjY3QzExMC43NDggMjMuNjY3OSAxMTAuNzA3IDIzLjk2OTYgMTEwLjYwOCAyNC4yNTQ0QzExMC41MSAyNC41MzkzIDExMC4zNTcgMjQuODAxNyAxMTAuMTU4IDI1LjAyNjVDMTA5Ljk3NyAyNS4yMTk5IDEwOS43NTcgMjUuMzcyNCAxMDkuNTEzIDI1LjQ3NDRDMTA5LjI3IDI1LjU3NjQgMTA5LjAwOCAyNS42MjU0IDEwOC43NDQgMjUuNjE4NUMxMDguNDc3IDI1LjYyMzQgMTA4LjIxMyAyNS41NzIzIDEwNy45NjYgMjUuNDY4NEMxMDcuNzIgMjUuMzY0NCAxMDcuNDk4IDI1LjIwOTkgMTA3LjMxNCAyNS4wMTQ1QzEwNy4xMTEgMjQuNzkwOSAxMDYuOTU1IDI0LjUyODYgMTA2Ljg1NCAyNC4yNDI4QzEwNi43NTIgMjMuOTU3MSAxMDYuNzA5IDIzLjY1MzcgMTA2LjcyNSAyMy4zNTA3VjE4LjM1OTFIMTAwLjI5M1YyMy4zNjI3QzEwMC4zMTEgMjMuNjY0OSAxMDAuMjY5IDIzLjk2NzUgMTAwLjE3IDI0LjI1MzFDMTAwLjA3MSAyNC41Mzg3IDk5LjkxNjcgMjQuODAxNiA5OS43MTY3IDI1LjAyNjVDOTkuNTM0OSAyNS4yMTk5IDk5LjMxNTEgMjUuMzcyNCA5OS4wNzE1IDI1LjQ3NDRDOTguODI3OSAyNS41NzY0IDk4LjU2NTggMjUuNjI1NCA5OC4zMDIzIDI1LjYxODVDOTguMDM2NCAyNS42MjUyIDk3Ljc3MjEgMjUuNTc2MSA5Ny41MjU5IDI1LjQ3NDJDOTcuMjc5OCAyNS4zNzIzIDk3LjA1NzIgMjUuMjE5OSA5Ni44NzIxIDI1LjAyNjVDOTYuNjY4NyAyNC44MDQyIDk2LjUxMTggMjQuNTQyNiA5Ni40MTA2IDI0LjI1NzRDOTYuMzA5NSAyMy45NzIyIDk2LjI2NjIgMjMuNjY5MiA5Ni4yODM1IDIzLjM2NjdWOS45NzE4OEM5Ni4yNjcyIDkuNjcxODggOTYuMzExIDkuMzcxNjQgOTYuNDEyMiA5LjA4OTE3Qzk2LjUxMzMgOC44MDY3IDk2LjY2OTggOC41NDc4MSA5Ni44NzIxIDguMzI4MDNDOTcuMDU4MiA4LjEzMzg1IDk3LjI4MjIgNy45ODA5NiA5Ny41Mjk3IDcuODc5MDVDOTcuNzc3MyA3Ljc3NzE1IDk4LjA0MzEgNy43Mjg0NiA5OC4zMTAyIDcuNzM2MDlDOTguNTY2OCA3LjcyODIyIDk4LjgyMjMgNy43NzMwNyA5OS4wNjEzIDcuODY3OTNDOTkuMzAwNCA3Ljk2MjggOTkuNTE3OSA4LjEwNTcxIDk5LjcwMDkgOC4yODgwNEM5OS45MDUgOC41MTQxIDEwMC4wNjMgOC43NzkxIDEwMC4xNjQgOS4wNjc1NEMxMDAuMjY2IDkuMzU1OTggMTAwLjMxIDkuNjYyMDYgMTAwLjI5MyA5Ljk2Nzg5VjkuOTY3ODlaIiBmaWxsPSIjMUYzNjY0Ii8+CjxwYXRoIGQ9Ik0xMjguNjIgMTEuNTAzN0wxMjMuNjc0IDE2LjQxOTNMMTI5LjM3NSAyMS43NDI4QzEzMC4xMTcgMjIuNDQ2NyAxMzAuNDkzIDIzLjA4NjcgMTMwLjQ5MyAyMy42NjY2QzEzMC41IDIzLjkyOTkgMTMwLjQ1MSAyNC4xOTE1IDEzMC4zNSAyNC40MzQzQzEzMC4yNDkgMjQuNjc3IDEzMC4wOTkgMjQuODk1MyAxMjkuOTA4IDI1LjA3NDVDMTI5LjcxNyAyNS4yNTY4IDEyOS40OTMgMjUuMzk4NyAxMjkuMjQ3IDI1LjQ5MjFDMTI5LjAwMSAyNS41ODU1IDEyOC43NCAyNS42Mjg1IDEyOC40NzggMjUuNjE4NUMxMjcuOTUxIDI1LjYxODUgMTI3LjM0NSAyNS4yOTMyIDEyNi42NjEgMjQuNjQyNUwxMjAuMDU1IDE4LjMzMTFWMjMuMzYyN0MxMjAuMDcxIDIzLjY2NDggMTIwLjAyOSAyMy45NjcyIDExOS45MyAyNC4yNTI3QzExOS44MzEgMjQuNTM4MiAxMTkuNjc4IDI0LjgwMTEgMTE5LjQ3OCAyNS4wMjY1QzExOS4yOTYgMjUuMjE5OCAxMTkuMDc3IDI1LjM3MjQgMTE4LjgzMyAyNS40NzQ0QzExOC41ODkgMjUuNTc2NCAxMTguMzI3IDI1LjYyNTQgMTE4LjA2NCAyNS42MTg1QzExNy43OTcgMjUuNjI1OCAxMTcuNTMxIDI1LjU3NyAxMTcuMjgzIDI1LjQ3NTJDMTE3LjAzNiAyNS4zNzMzIDExNi44MTIgMjUuMjIwNSAxMTYuNjI2IDI1LjAyNjVDMTE2LjQyMyAyNC44MDI5IDExNi4yNjYgMjQuNTQwNSAxMTYuMTY1IDI0LjI1NDhDMTE2LjA2NCAyMy45NjkxIDExNi4wMjEgMjMuNjY1NyAxMTYuMDM3IDIzLjM2MjdWOS45NDc4N0MxMTYuMDA5IDkuMzM0MDEgMTE2LjIyMSA4LjczMzYzIDExNi42MjYgOC4yNzYwMkMxMTYuODA5IDguMDc0ODYgMTE3LjAzMiA3LjkxNTUxIDExNy4yODEgNy44MDg2MUMxMTcuNTI5IDcuNzAxNyAxMTcuNzk4IDcuNjQ5NzEgMTE4LjA2OCA3LjY1NjA4QzExOC4zMjYgNy42NDk1MiAxMTguNTgzIDcuNjk3NzIgMTE4LjgyMiA3Ljc5NzZDMTE5LjA2IDcuODk3NDcgMTE5LjI3NiA4LjA0NjgxIDExOS40NTQgOC4yMzYwM0MxMTkuODY4IDguNzA1NDIgMTIwLjA4NSA5LjMxOTQxIDEyMC4wNTkgOS45NDc4N1YxNC41ODc1TDEyNS44NDcgOC42ODM5OEMxMjYuNTAzIDguMDIwMDQgMTI3LjEzMSA3LjY1NjA4IDEyNy42ODggNy42NTYwOEMxMjcuOTQ4IDcuNjUxMzkgMTI4LjIwNyA3LjcwMDM5IDEyOC40NDggNy44MDAwOUMxMjguNjg5IDcuODk5NzkgMTI4LjkwOCA4LjA0ODExIDEyOS4wOSA4LjIzNjAzQzEyOS4yNzMgOC40MTU2NiAxMjkuNDE4IDguNjMwODcgMTI5LjUxNiA4Ljg2ODY5QzEyOS42MTUgOS4xMDY1MSAxMjkuNjY1IDkuMzYyMDQgMTI5LjY2MyA5LjYxOTlDMTI5LjY2MyAxMC4xOTMyIDEyOS4zMTUgMTAuODIxMSAxMjguNjIgMTEuNTAzN1oiIGZpbGw9IiMxRjM2NjQiLz4KPHBhdGggZD0iTTEzNC4xMjggMjMuMzY2NlY5Ljk3MTg0QzEzNC4xMSA5LjY3MjY2IDEzNC4xNTIgOS4zNzI5MiAxMzQuMjUxIDkuMDkwNDlDMTM0LjM1IDguODA4MDcgMTM0LjUwNCA4LjU0ODc0IDEzNC43MDQgOC4zMjc5OUMxMzUuMDgxIDcuOTU1NjkgMTM1LjU4NCA3Ljc0Mzk3IDEzNi4xMTEgNy43MzY1M0MxMzYuNjM3IDcuNzI5MDkgMTM3LjE0NiA3LjkyNjQ5IDEzNy41MzMgOC4yODc5OUMxMzcuOTQ2IDguNzQ2NDkgMTM4LjE2MiA5LjM1MjE2IDEzOC4xMzQgOS45NzE4NFYyMy4zNjY2QzEzOC4xNjkgMjMuOTc0NCAxMzcuOTY0IDI0LjU3MTQgMTM3LjU2NSAyNS4wMjY1QzEzNy4zODIgMjUuMjIwOCAxMzcuMTYxIDI1LjM3NCAxMzYuOTE2IDI1LjQ3NkMxMzYuNjcxIDI1LjU3OCAxMzYuNDA3IDI1LjYyNjUgMTM2LjE0MiAyNS42MTg0QzEzNS44NzYgMjUuNjIzMSAxMzUuNjEyIDI1LjU3MTggMTM1LjM2NyAyNS40Njc4QzEzNS4xMjEgMjUuMzYzOSAxMzQuOSAyNS4yMDk1IDEzNC43MTYgMjUuMDE0NUMxMzQuNTE0IDI0Ljc5MzYgMTM0LjM1OCAyNC41MzM5IDEzNC4yNTcgMjQuMjUwOUMxMzQuMTU2IDIzLjk2NzkgMTM0LjExMiAyMy42NjcyIDEzNC4xMjggMjMuMzY2NlYyMy4zNjY2WiIgZmlsbD0iIzFGMzY2NCIvPgo8cGF0aCBkPSJNNDAuNzcxNiAyNS40NzA1QzQwLjUwODQgMjUuNDcxIDQwLjI0NzcgMjUuNDE4OSA0MC4wMDQ0IDI1LjMxNzJDMzkuNzYxMiAyNS4yMTU1IDM5LjU0MDIgMjUuMDY2MSAzOS4zNTQxIDI0Ljg3NzdDMzkuMTY4IDI0LjY4OTMgMzkuMDIwNCAyNC40NjU2IDM4LjkyIDI0LjIxOTNDMzguODE5NSAyMy45NzMgMzguNzY4IDIzLjcwOTEgMzguNzY4NiAyMy40NDI3VjkuOTE5ODhDMzguNzY4NiA5LjM4MjA3IDM4Ljk3OTYgOC44NjYyOCAzOS4zNTUyIDguNDg1OTlDMzkuNzMwOSA4LjEwNTcgNDAuMjQwMyA3Ljg5MjA2IDQwLjc3MTYgNy44OTIwNkM0MS4zMDI4IDcuODkyMDYgNDEuODEyMyA4LjEwNTcgNDIuMTg3OSA4LjQ4NTk5QzQyLjU2MzYgOC44NjYyOCA0Mi43NzQ2IDkuMzgyMDcgNDIuNzc0NiA5LjkxOTg4VjIzLjQ0MjdDNDIuNzc1MSAyMy43MDkxIDQyLjcyMzcgMjMuOTczIDQyLjYyMzIgMjQuMjE5M0M0Mi41MjI3IDI0LjQ2NTYgNDIuMzc1MiAyNC42ODkzIDQyLjE4OTEgMjQuODc3N0M0Mi4wMDMgMjUuMDY2MSA0MS43ODIgMjUuMjE1NSA0MS41Mzg3IDI1LjMxNzJDNDEuMjk1NSAyNS40MTg5IDQxLjAzNDggMjUuNDcxIDQwLjc3MTYgMjUuNDcwNVYyNS40NzA1WiIgZmlsbD0iIzFGMzY2NCIvPgo8cGF0aCBkPSJNNTkuMjM3NCAzMS45MzM5QzU4LjcxODcgMzEuOTM0IDU4LjIyMDIgMzEuNzMwNCA1Ny44NDY4IDMxLjM2NTlMNDQuMDUwOCAxNy44OTUxQzQzLjY3MDYgMTcuNTIyNiA0My40NTEgMTcuMDEzMiA0My40Mzk5IDE2LjQ3NzZDNDMuNDI4OCAxNS45NDIgNDMuNjI3MSAxNS40MjM3IDQzLjk5MTUgMTUuMDM1NEw1MC43NjcxIDcuODM2MDNDNTEuMTM5OCA3LjQ3MzQ5IDUxLjYzNjMgNy4yNzA1IDUyLjE1MzEgNy4yNjkzNUM1Mi42NyA3LjI2ODIgNTMuMTY3NCA3LjQ2ODk3IDUzLjU0MTcgNy44Mjk4NUM1My45MTYgOC4xOTA3MiA1NC4xMzgzIDguNjgzODcgNTQuMTYyMyA5LjIwNjU4QzU0LjE4NjQgOS43MjkyOCA1NC4wMTAzIDEwLjI0MTIgNTMuNjcwOCAxMC42MzU4TDQ4LjI3ODEgMTYuMzc1M0w2MC42NDM5IDI4LjQ0NjJDNjAuOTMxNiAyOC43MjcgNjEuMTI5OCAyOS4wODg1IDYxLjIxMjkgMjkuNDg0M0M2MS4yOTYgMjkuODggNjEuMjYwMyAzMC4yOTE5IDYxLjExMDMgMzAuNjY3QzYwLjk2MDQgMzEuMDQyIDYwLjcwMyAzMS4zNjMgNjAuMzcxNCAzMS41ODg4QzYwLjAzOTcgMzEuODE0NiA1OS42NDg5IDMxLjkzNDcgNTkuMjQ5MyAzMS45MzM5SDU5LjIzNzRaIiBmaWxsPSIjMDBFNkIyIi8+CjxwYXRoIGQ9Ik0yOS40MTM0IDYuNDkyMThMMTYuODYxOSAxOS42OTFDMTYuNTI4NSAyMC4wMzg3IDE2LjA3MyAyMC4yMzkyIDE1LjU5NDQgMjAuMjQ5QzE1LjExNTggMjAuMjU4NyAxNC42NTI3IDIwLjA3NjkgMTQuMzA1OCAxOS43NDNMNy44NjYxMSAxMy41MjM1QzcuNjcyMTcgMTMuMzY0MyA3LjUxMjgyIDEzLjE2NjEgNy4zOTgwNSAxMi45NDE2QzcuMjgzMjcgMTIuNzE3IDcuMjE1NTUgMTIuNDcwOSA3LjE5OTEzIDEyLjIxODZDNy4xODI3IDExLjk2NjMgNy4yMTc5MyAxMS43MTMzIDcuMzAyNiAxMS40NzU1QzcuMzg3MjcgMTEuMjM3NiA3LjUxOTU1IDExLjAyIDcuNjkxMTcgMTAuODM2NEM3Ljg2Mjc4IDEwLjY1MjcgOC4wNzAwMiAxMC41MDY5IDguMjk5ODkgMTAuNDA4QzguNTI5NzcgMTAuMzA5MiA4Ljc3NzMzIDEwLjI1OTYgOS4wMjcwNiAxMC4yNjIxQzkuMjc2NzggMTAuMjY0NyA5LjUyMzI5IDEwLjMxOTUgOS43NTExMyAxMC40MjNDOS45Nzg5NyAxMC41MjY1IDEwLjE4MzIgMTAuNjc2NiAxMC4zNTExIDEwLjg2MzhMMTUuNDg3MSAxNS44MTEzTDI2LjkwODYgMy44NTI0MkMyMy42ODc3IDEuMTMzMzggMTkuNTYwNSAtMC4yMzM0ODYgMTUuMzc1IDAuMDMyNjkzQzExLjE4OTUgMC4yOTg4NzIgNy4yNjM3IDIuMTc3ODggNC40MDQyNSA1LjI4MzU5QzEuNTQ0ODEgOC4zODkzMSAtMC4wMzEwNjYgMTIuNDg1OCAwLjAwMDQ2NDE2OCAxNi43MzE0QzAuMDMxOTk0MyAyMC45NzY5IDEuNjY4NTMgMjUuMDQ5IDQuNTczNzggMjguMTEwOEM3LjQ3OTAzIDMxLjE3MjcgMTEuNDMyMyAzMi45OTE3IDE1LjYyMTMgMzMuMTk0MUMxOS44MTAzIDMzLjM5NjYgMjMuOTE2NyAzMS45NjcgMjcuMDk2OCAyOS4xOTkzQzMwLjI3NyAyNi40MzE1IDMyLjI4OTIgMjIuNTM1OCAzMi43MiAxOC4zMTI2QzMzLjE1MDkgMTQuMDg5NCAzMS45Njc2IDkuODU5NTEgMjkuNDEzNCA2LjQ5MjE4VjYuNDkyMThaTTIxLjgwODIgMjQuNTEwNUg5LjczNDhDOS4yNTM4NiAyNC41MTA1IDguNzkyNjIgMjQuMzE3MSA4LjQ1MjU1IDIzLjk3MjhDOC4xMTI0NyAyMy42Mjg2IDcuOTIxNDIgMjMuMTYxNiA3LjkyMTQyIDIyLjY3NDdDNy45MjE0MiAyMi4xODc4IDguMTEyNDcgMjEuNzIwOSA4LjQ1MjU1IDIxLjM3NjZDOC43OTI2MiAyMS4wMzIzIDkuMjUzODYgMjAuODM4OSA5LjczNDggMjAuODM4OUgyMS44MDgyQzIyLjI4OTIgMjAuODM4OSAyMi43NTA0IDIxLjAzMjMgMjMuMDkwNSAyMS4zNzY2QzIzLjQzMDYgMjEuNzIwOSAyMy42MjE2IDIyLjE4NzggMjMuNjIxNiAyMi42NzQ3QzIzLjYyMTYgMjMuMTYxNiAyMy40MzA2IDIzLjYyODYgMjMuMDkwNSAyMy45NzI4QzIyLjc1MDQgMjQuMzE3MSAyMi4yODkyIDI0LjUxMDUgMjEuODA4MiAyNC41MTA1VjI0LjUxMDVaIiBmaWxsPSIjMDBFNkIyIi8+Cjwvc3ZnPgo=" style="height: 25px;width: auto;">
              </h4>
            </div>
            <div class="panel-body border" style="display: none;">
              <form method="post" class="formin form-horizontal frm-pasarela" action="ajax/ajustes?action=savepasarela&tipo=kushki">

                <div class="form-group row">
                  <label>Llave Pública</label>
                  <input type="text" class="form-control" name="kushki[pin]" value="">
                </div>

                <div class="form-group row">
                  <label>LLave Privada</label>
                  <input type="text" class="form-control" name="kushki[cuenta]" value="">
                </div>

                <div class="form-group row">
                  <label>País</label>
                  <select class="form-control sl2" name="kushki[correo]" style="width: 100%">
                    <option value="USD">Ecuador</option>
                    <option value="MXN">México</option>
                    <option value="COP">Colombia</option>
                    <option value="CLP">Chile</option>
                    <option value="PEN" selected>Perú</option>
                  </select>
                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Sanbox <i class="far fa-question-circle fa-lg" data-toggle="tooltip" data-placement="top" title="Sandbox: Es el modo de pruebas."></i></label>
                  <div class="col-md-8">
                    <input type="hidden" name="kushki[sandbox]" value="live">
                    <input class="chk" type="checkbox" name="kushki[sandbox]" value="sandbox">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-form-label col-md-4">Activar pasarela</label>
                  <div class="col-md-8">
                    <input type="hidden" name="kushki[estado]">
                    <input class="chk" type="checkbox" name="kushki[estado]">
                  </div>
                </div>

                <div class="panel-footer text-right">
                  <button type="submit" class="btn-azul btn-sm m-l-5"><i class="far fa-save"></i> Guardar cambios</button>
                </div>


              </form>
            </div>
          </div>
        </div>

      </div>

    </div>
    <div class="tab-pane fade row" id="default-tab-2">

      <table id="data-pasarela" class="display nowrap table table-striped table-bordered" cellspacing="0" width="100%" data-order="[[ 0, &quot;asc&quot; ]]">
        <thead>
          <tr>
            <th class="all" style="max-width:20px !important">ID</th>
            <th>Tipo</th>
            <th>Descrip. 1</th>
            <th>Descrip. 2</th>
            <th>Descrip. 3</th>
            <th>Descrip. 4</th>
            <th class="all" style="max-width:40px !important" data-orderable="false"></th>
          </tr>
        </thead>

      </table>

    </div>

  </div>


</div>



<script>
  function delete_pasarela(id) {

    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Se va eliminar la pasarela de pago seleccionada',
      title: '<i class="far fa-question-circle fa-lg icodialog"></i> Eliminar pasarela',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt icodialog"></i> Eliminar', // With spaces and symbols
          action: function() {
            $.post("ajustes/delete_pasarela_pago_otros", {
                id
              })
              .done(function(data) {
                if (data == 1) {
                  alerta('exito', 'Registro eliminado correctamente');
                } else if (data == 2) {
                  alerta('exito', 'Error al eliminar.');
                } else {
                  alerta('exito', 'No cuentas con los permisos necesarios.');

                }
                updatepasarela();
              });

          }
        }
      }
    });

  }

  function generatewebpay() {
    if ($('input[name="webpay\[pais\]"]').val()) {

      if ($('[name="webpay\[pin\]"]').val()) {
        alerta('error', "No se puede generar un nuevo certificado porque ya tiene una Llave y certificado público asignado,necesita borrar el contenido de Llave Privada y Certificado público.")
        return true;
      }

      $.post("ajax/ajustes", {
          action: "generatecrt",
          code: $('input[name="webpay\[pais\]"]').val()
        })
        .done(function(data) {
          $('[name="webpay\[pin\]"]').val(data['key']);
          $('[name="webpay\[cuenta\]"]').val(data['crt']);
        });

    } else {
      alerta('error', "Debe indicar el código de comercio proporcionado por Webpay")
      return true;
    }

  }

  $('#webpayinter').click(function() {
    if ($(this).is(':checked')) {
      $('.modeinter').slideToggle();
      $('.modeinter textarea').prop('required', false);
      $('.nclientest').slideToggle();
      $('.nclientest input').prop('required', true);
    } else {
      $('.modeinter').slideToggle();
      $('.modeinter textarea').prop('required', true);
      $('.nclientest input').prop('required', false);
      $('.nclientest').slideToggle();
    }
  });



  $('.modeinter').hide();
  $('.nclientest').show();
  $('.modeinter textarea').prop('required', false);
  $('.nclientest input').prop('required', true);
  $(function() {

    updatepasarela = function() {
      pasarela.ajax.reload(null, false);
    }

    configtable('#data-pasarela', 'Pasarelas');
    pasarela = $('#data-pasarela').DataTable({
      "ajax": "ajustes/get_pasarelas_pago_otros_json",
      "deferRender": true,
      "aoColumns": [{
          mData: 'id',
          sWidth: "10px"
        },
        {
          mData: 'pasarela'
        },
        {
          mData: 'dsp1'
        },
        {
          mData: 'dsp2'
        },
        {
          mData: 'dsp3'
        },
        {
          mData: 'dsp4'
        },
        {
          mData: 'tool',
          sClass: 'text-center'
        }
      ],
      "idDataTables": "40",
      initComplete: function(oSettings, json) {
        $("#data-pasarela_wrapper div.tooltablas")
          .html('<div class="grupotabla btn-group">\
  		<button type="button" class="btn btn-white" Onclick="getmodal(\'ajustes/modal_new_pasarela\',\'Nueva forma de pago\',\'md\');"><i class="fas fa-plus"></i> Nuevo</button>\
		</div>');
      }
    });


    $("#paispayu option[value=PEN]").prop("selected", "selected");

    $('.slflow option[value="9"]').prop("selected", "selected");
    $("#paispayu").change(function() {
      if ($(this).val() == "ARS") {
        $('#metodopayu')[0].options.length = 0;
        $('#metodopayu').append('<option value="PAGOFACIL">PAGOFACIL</option><option value="BAPRO">BAPRO</option><option value="COBRO_EXPRESS">COBRO_EXPRESS</option><option value="RAPIPAGO">RAPIPAGO</option><option value="RIPSA">RIPSA</option>');
      }

      if ($(this).val() == "COP") {
        $('#metodopayu')[0].options.length = 0;
        $('#metodopayu').append('<option value="BALOTO">BALOTO</option><option value="EFECTY">EFECTY</option>');
      }

      if ($(this).val() == "MXN") {
        $('#metodopayu')[0].options.length = 0;
        $('#metodopayu').append('<option value="SANTANDER">SANTANDER</option><option value="SCOTIABANK">SCOTIABANK</option><option value="BANCOMER">BANCOMER</option><option value="OXXO">OXXO</option><option value="SEVEN_ELEVEN">SEVEN_ELEVEN</option>');
      }

      if ($(this).val() == "PEN") {
        $('#metodopayu')[0].options.length = 0;
        $('#metodopayu').append('<option value="BCP">BCP</option>');
      }

    });

    $("#paispayu").trigger("change");
    $("#metodopayu option[value=BCP]").prop("selected", "selected");

    $('select[name="mp\[correo\]"] option[value="MXN"]').prop('selected', true);

    var elems = Array.prototype.slice.call(document.querySelectorAll('.chk'));
    elems.forEach(function(html) {
      var switchery = new Switchery(html, {
        size: 'small',
        color: 'rgb(18, 187, 168)'
      });
    });

    App.setPageTitle('Configuración');

    $('.frm-pasarela').ajaxForm({
      success: function(data) {
        $('#gritter-notice-wrapper').remove();
        alerta('exito', 'Datos guardados correctamente');
      },
      error: function(response) {
        $('#gritter-notice-wrapper').remove();
        alerta('error500', response['responseText']);
      }
    })

    $('.sl2').select2();

  })
</script>