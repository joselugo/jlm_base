<form id="frm-save-config-invoice" class="row form-horizontal" method="post" action="clientes_facturacion/guardar_configuracion">
  <input name="config[id]" value="<?= $config->id; ?>" type="hidden">
  <input name="fecha_factura" value="" type="hidden">
  <input name="idcliente" value="<?= $idcliente; ?>" type="hidden">


  <div class="col-lg-6">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title"><b><i class="far fa-file"></i> Facturación</b></h4>
      </div>
      <div class="panel-body border-panel">

        <div class="form-group row">
          <label class="col-md-5 col-form-label">Tipo</label>
          <div class="col-md-6">
            <select name="config[tipopago]" class="sl2 form-control" style="width: 100%;">
              <option value="0">Prepago (Adelantado)</option>
              <option value="1">Postpago (Vencido)</option>
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-5 col-form-label">Día pago</label>
          <div class="col-md-6">
            <select name="config[diapago]" class="sl2 form-control" style="width: 100%;">
              <option value="1">01</option>
              <option value="2">02</option>
              <option value="3">03</option>
              <option value="4">04</option>
              <option value="5">05</option>
              <option value="6">06</option>
              <option value="7">07</option>
              <option value="8">08</option>
              <option value="9">09</option>
              <option value="10">10</option>
              <option value="11">11</option>
              <option value="12">12</option>
              <option value="13">13</option>
              <option value="14">14</option>
              <option value="15">15</option>
              <option value="16">16</option>
              <option value="17">17</option>
              <option value="18">18</option>
              <option value="19">19</option>
              <option value="20">20</option>
              <option value="21">21</option>
              <option value="22">22</option>
              <option value="23">23</option>
              <option value="24">24</option>
              <option value="25">25</option>
              <option value="26">26</option>
              <option value="27">27</option>
              <option value="28">28</option>
            </select>

          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-5 col-form-label">Crear Factura</label>
          <div class="col-md-6">
            <select name="config[diafactura]" class="sl2 form-control" style="width: 100%;">
              <option value="0">Desactivado</option>
              <option value="1">1 Día antes</option>
              <option value="2">2 Días antes</option>
              <option value="3">3 Días antes</option>
              <option value="4">4 Días antes</option>
              <option value="5">5 Días antes</option>
              <option value="6">6 Días antes</option>
              <option value="7">7 Días antes</option>
              <option value="8">8 Días antes</option>
              <option value="9">9 Días antes</option>
              <option value="10">10 Días antes</option>
              <option value="11">11 Días antes</option>
              <option value="12">12 Días antes</option>
              <option value="13">13 Días antes</option>
              <option value="14">14 Días antes</option>
              <option value="15">15 Días antes</option>
              <option value="16">16 Días antes</option>
              <option value="17">17 Días antes</option>
              <option value="18">18 Días antes</option>
              <option value="19">19 Días antes</option>
              <option value="20">20 Días antes</option>
              <option value="21">21 Días antes</option>
              <option value="22">22 Días antes</option>
              <option value="23">23 Días antes</option>
              <option value="24">24 Días antes</option>
              <option value="25">25 Días antes</option>
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-5 col-form-label">Tipo impuesto</label>
          <div class="col-md-6">
            <select style="width: 100%;" class="sl2 form-control" name="config[impuesto]">
              <option value="NO">Impuestos incluido</option>
              <option value="SI">Mas Impuestos</option>
              <option value="NADA">Ninguno</option>
            </select>
          </div>
        </div>




        <div class="form-group row">
          <label class="col-md-5 col-form-label">Días de gracia</label>
          <div class="col-md-6">
            <select name="config[corteautomatico]" class="sl2 form-control" style="width: 100%;">
              <option value="0">0 Días</option>
              <option value="1">1 Día</option>
              <option value="2">2 Días</option>
              <option value="3">3 Días</option>
              <option value="4">4 Días</option>
              <option value="5">5 Días</option>
              <option value="6">6 Días</option>
              <option value="7">7 Días</option>
              <option value="8">8 Días</option>
              <option value="9">9 Días</option>
              <option value="10">10 Días</option>
              <option value="11">11 Días</option>
              <option value="12">12 Días</option>
              <option value="13">13 Días</option>
              <option value="14">14 Días</option>
              <option value="15">15 Días</option>
              <option value="16">16 Días</option>
              <option value="17">17 Días</option>
              <option value="18">18 Días</option>
              <option value="19">19 Días</option>
              <option value="20">20 Días</option>
              <option value="21">21 Días</option>
              <option value="22">22 Días</option>
              <option value="23">23 Días</option>
              <option value="24">24 Días</option>
              <option value="25">25 Días</option>
            </select>
            <small class="text-warning">*días tolerancia para aplicar corte</small>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-5 col-form-label">Aplicar Corte</label>
          <div class="col-md-6">
            <select name="config[meses]" class="sl2 form-control" style="width: 100%;">
              <option value="0">Desactivado</option>
              <option value="1">1 Mes vencido</option>
              <option value="2">2 Meses vencidos</option>
              <option value="3">3 Meses vencidos</option>
              <option value="4">4 Meses vencidos</option>
              <option value="5">5 Meses vencidos</option>
              <option value="6">6 Meses vencidos</option>
              <option value="7">7 Meses vencidos</option>
              <option value="8">8 Meses vencidos</option>
              <option value="9">9 Meses vencidos</option>
              <option value="10">10 Meses vencidos</option>
              <option value="11">11 Meses vencidos</option>
              <option value="12">12 Meses vencidos</option>
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-5 col-form-label">Bajar Velocidad</label>
          <div class="col-md-6">
            <select name="config[limit_velocidad]" class="sl2 form-control" style="width: 100%;">
              <option value="0">Desactivado</option>
              <option value="1">Activar</option>
            </select>
            <small class="text-danger">*Baja la velocidad del cliente cuando vence una Factura, este se aplica solo si ha configurado "Aplicar Corte" + de un mes de vencido.</small>
          </div>
        </div>


        <div class="form-group row">
          <label class="col-form-label col-md-5">Fecha Fija <button type="button" data-html="true" class="btn-ayuda btn btn-default btn-xs" data-toggle="popover" title="Fecha de pago" data-content="
       <p><b>Campo vacío:</b></p><p>
       </p><p>Por defecto podemos dejar este campo vacío, esto permite que el sistema maneje la próxima fecha de pago dinamicamente, por ejemplo si tenemos configurado día de pago 1: esto quiere decir que el 1 de todos los meses será su día de pago e iniciará su facturación el próximo 1.</p><br>
       
       <p><b>Seleccionar fecha:</b></p><p>
       </p><p>Al indicar una fecha especifica estamos indicando al sistema que la facturación/día de pago próxima de este cliente iniciará en la fecha que indiquemos en este campo.
       Esta fecha será reseteado (vacío) al cumplir con la fecha seleccionado.
       Indicar una próxima fecha de pago incorrecta puede ocasionar que no se cree la factura del mes deseado.(fecha pasadas).
       Se debe seleccionar una fecha con el mismo día de pago asignado a nuestro cliente, si nuestro cliente tiene dia de pago 5, debemos seleccionar cualquier mes pero día 5.
       </p><br>
     "><i class="fas fa-question"></i></button></label>
          <div class="col-md-6">
            <div class="input-group">
              <input type="text" id="conf-dia_factura" name="config[fecha_factura]" class="form-control" readonly placeholder="Automático" value="">
              <span class="input-group-addon" style="cursor:pointer" data-toggle="tooltip" data-placement="bottom" title="" onclick="$('#conf-dia_factura').val('')" data-original-title="Borrar Fecha"><i class="far fa-trash-alt" aria-hidden="true"></i></span>
            </div>

          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-5 col-form-label">Aplicar Mora</label>
          <div class="col-md-6">
            <input type="checkbox" class="js-switch" name="config[mora]" checked>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-5 col-form-label">Aplicar Reconexión</label>
          <div class="col-md-6">
            <input type="checkbox" class="js-switch" name="config[reconexion]" checked>
          </div>
        </div>


        <br>
        <h5 class="text-center"><b>Otros Impuestos</b></h5>
        <small class="text-center m-b-10" style="width: 100%; display: block">Estos Impuestos serán Agregados al total de la factura</small>
        <div class="form-group row">
          <label class="col-md-5 col-form-label">Impuesto #1 (%)</label>
          <div class="col-md-6">
            <input type="number" name="impuestos[1]" class="form-control" placeholder="<?= $impuestos->{'1'}; ?>" value="<?= $impuestos->{'1'}; ?>" step="0.1" max="100" min="0">
            <small>* Dejar en 0 (cero) para quedar deshabilitado</small>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-5 col-form-label">Impuesto #2 (%)</label>
          <div class="col-md-6">
            <input type="number" name="impuestos[2]" class="form-control" placeholder="<?= $impuestos->{'2'}; ?>" value="<?= $impuestos->{'2'}; ?>" step="0.1" max="100" min="0">
            <small>* Dejar en 0 (cero) para quedar deshabilitado</small>
          </div>
        </div>


        <div class="form-group row">
          <label class="col-md-5 col-form-label">Impuesto #3 (%)</label>
          <div class="col-md-6">
            <input type="number" name="impuestos[3]" class="form-control" placeholder="<?= $impuestos->{'3'}; ?>" value="<?= $impuestos->{'3'}; ?>" step="0.1" max="100" min="0">
            <small>* Dejar en 0 (cero) para quedar deshabilitado</small>
          </div>
        </div>



        <div class="configafip  d-none">
          <br>
          <h5 class="text-center"><b>Configuración AFIP</b></h5>

          <div class="form-group row">
            <label class="col-md-5 col-form-label">Activar afip</label>
            <div class="col-md-6">
              <input type="hidden" name="config[afip]">
              <input type="checkbox" class="js-switch" name="config[afip]">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-5 col-form-label">Nº Punto de venta</label>
            <div class="col-md-6">
              <input type="text" name="config[afip_punto_venta]" class="form-control" placeholder="Punto de venta general" value="">
              <small>* Dejar vacío para utiliza punto de venta general</small>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-5 col-form-label">Condición frente al IVA</label>
            <div class="col-md-6">
              <select name="config[afip_condicion_iva]" class="sl2 form-control" style="width: 100%;">
                <option value="Consumidor Final">Consumidor Final</option>
                <option value="IVA Sujeto Exento">IVA Sujeto Exento</option>
                <option value="IVA Responsable Inscripto">IVA Responsable Inscripto</option>
                <option value="Responsable Monotributo">Responsable Monotributo</option>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-5 col-form-label">Condición de Venta</label>
            <div class="col-md-6">
              <select name="config[afip_condicion_venta]" class="sl2 form-control" style="width: 100%;">
                <option value="Contado">Contado</option>
                <option value="Tarjeta de débito">Tarjeta de débito</option>
                <option value="Tarjeta de crédito">Tarjeta de crédito</option>
                <option value="Cuenta corriente">Cuenta corriente</option>
                <option value="Chequee">Cheque</option>
                <option value="Ticket">Ticket</option>
                <option value="Otra">Otra</option>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-5 col-form-label">Afip Automático</label>
            <div class="col-md-6">
              <select name="config[afip_automatico]" class="sl2 form-control" style="width: 100%;">
                <option value="0">Desactivado</option>
                <option value="1">Crear AFIP al CREAR factura de servicio</option>
                <option value="2">Crear AFIP al PAGAR factura con pasarela de pago</option>
              </select>
            </div>
          </div>

        </div>

        <br>
        <div class="text-right m-b-10"><button <?= $permiso2 ? "" : "style=\"display:none;\""; ?> type="submit" class="btn-sm btn-azul"><i class="far fa-save"></i> Guardar cambios</button></div>
        <div class="row">
          <div class="col-md-6">
            <div class="alert alert-pago">
              Día de pago: <b class="daypago"></b>
            </div>
          </div>

          <div class="col-md-6">
            <div class="alert alert-corte">
              Día de corte: <b class="daycorte"></b>
              02:30 AM</div>
          </div>

          <div class="col-md-6">
            <div class="alert alert-factura">
              Crear factura: <b class="dayfactura"></b>
              03:30 AM</div>
          </div>
        </div>


      </div>
    </div>
  </div>



  <div class="col-lg-6">





    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title"><b><i class="far fa-bell"></i> Notificaciones</b></h4>
      </div>
      <div class="panel-body border-panel">

        <div class="form-group row">
          <label class="col-md-5 col-form-label">Aviso nueva factura</label>
          <div class="col-md-6">
            <select name="config[tipoaviso]" class="sl2 form-control" style="width: 100%;">
              <option value="0">Desactivado</option>
              <option value="1">Correo</option>
              <option value="2">SMS</option>
              <option value="4">Telegram</option>
              <option value="5">Correo + Telegram</option>
              <option value="3">Correo + SMS</option>
              <option value="6">Telegram + SMS</option>
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-5 col-form-label">Aviso en Pantalla</label>
          <div class="col-md-6">
            <select name="config[avisopantalla]" class="sl2 form-control" style="width: 100%;">
              <option value="0">Desactivado</option>
              <option value="1">1 Día antes</option>
              <option value="2">2 Días antes</option>
              <option value="3">3 Días antes</option>
              <option value="4">4 Días antes</option>
              <option value="5">5 Días antes</option>
              <option value="6">6 Días antes</option>
              <option value="7">7 Días antes</option>
              <option value="8">8 Días antes</option>
              <option value="9">9 Días antes</option>
              <option value="10">10 Días antes</option>
              <option value="11">11 Días antes</option>
              <option value="12">12 Días antes</option>
              <option value="13">13 Días antes</option>
              <option value="14">14 Días antes</option>
              <option value="15">15 Días antes</option>
              <option value="16">16 Días antes</option>
              <option value="17">17 Días antes</option>
              <option value="18">18 Días antes</option>
              <option value="19">19 Días antes</option>
              <option value="20">20 Días antes</option>
              <option value="21">21 Días antes</option>
              <option value="22">22 Días antes</option>
              <option value="23">23 Días antes</option>
              <option value="24">24 Días antes</option>
              <option value="25">25 Días antes</option>
            </select>
            <small class="text-warning">* Aviso solo en páginas HTTP</small>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-5 col-form-label">Recordatorios de pago</label>
          <div class="col-md-6">
            <select name="config[tiporecordatorio]" class="sl2 form-control" style="width: 100%;">
              <option value="0">Desactivado</option>
              <option value="1">Correo</option>
              <option value="2">SMS</option>
              <option value="4">Telegram</option>
              <option value="5">Correo + Telegram</option>
              <option value="3">Correo + SMS</option>
              <option value="6">Telegram + SMS</option>
            </select>

          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-5 col-form-label">Recordatorio #1</label>
          <div class="col-md-6">
            <select name="config[avisosms]" class="sl2 form-control" style="width: 100%;">
              <option value="0">Desactivado</option>

              <optgroup label="Antes del vencimiento">
                <option value="-1">1 Día Antes</option>
                <option value="-2">2 Días Antes</option>
                <option value="-3">3 Días Antes</option>
                <option value="-4">4 Días Antes</option>
                <option value="-5">5 Días Antes</option>
                <option value="-6">6 Días Antes</option>
                <option value="-7">7 Días Antes</option>
                <option value="-8">8 Días Antes</option>
                <option value="-9">9 Días Antes</option>
                <option value="-10">10 Días Antes</option>
              </optgroup>


              <optgroup label="Después del vencimiento">
                <option value="1">1 Día después</option>
                <option value="2">2 Días después</option>
                <option value="3">3 Días después</option>
                <option value="4">4 Días después</option>
                <option value="5">5 Días después</option>
                <option value="6">6 Días después</option>
                <option value="7">7 Días después</option>
                <option value="8">8 Días después</option>
                <option value="9">9 Días después</option>
                <option value="10">10 Días después</option>
                <option value="11">11 Días después</option>
                <option value="12">12 Días después</option>
                <option value="13">13 Días después</option>
                <option value="14">14 Días después</option>
                <option value="15">15 Días después</option>
                <option value="16">16 Días después</option>
                <option value="17">17 Días después</option>
                <option value="18">18 Días después</option>
                <option value="19">19 Días después</option>
                <option value="20">20 Días después</option>
                <option value="21">21 Días después</option>
                <option value="22">22 Días después</option>
                <option value="23">23 Días después</option>
                <option value="24">24 Días después</option>
                <option value="25">25 Días después</option>
              </optgroup>
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-5 col-form-label">Recordatorio #2</label>
          <div class="col-md-6">
            <select name="config[avisosms2]" class="sl2 form-control" style="width: 100%;">
              <option value="0">Desactivado</option>

              <optgroup label="Antes del vencimiento">
                <option value="-1">1 Día Antes</option>
                <option value="-2">2 Días Antes</option>
                <option value="-3">3 Días Antes</option>
                <option value="-4">4 Días Antes</option>
                <option value="-5">5 Días Antes</option>
                <option value="-6">6 Días Antes</option>
                <option value="-7">7 Días Antes</option>
                <option value="-8">8 Días Antes</option>
                <option value="-9">9 Días Antes</option>
                <option value="-10">10 Días Antes</option>
              </optgroup>


              <optgroup label="Después del vencimiento">
                <option value="1">1 Día después</option>
                <option value="2">2 Días después</option>
                <option value="3">3 Días después</option>
                <option value="4">4 Días después</option>
                <option value="5">5 Días después</option>
                <option value="6">6 Días después</option>
                <option value="7">7 Días después</option>
                <option value="8">8 Días después</option>
                <option value="9">9 Días después</option>
                <option value="10">10 Días después</option>
                <option value="11">11 Días después</option>
                <option value="12">12 Días después</option>
                <option value="13">13 Días después</option>
                <option value="14">14 Días después</option>
                <option value="15">15 Días después</option>
                <option value="16">16 Días después</option>
                <option value="17">17 Días después</option>
                <option value="18">18 Días después</option>
                <option value="19">19 Días después</option>
                <option value="20">20 Días después</option>
                <option value="21">21 Días después</option>
                <option value="22">22 Días después</option>
                <option value="23">23 Días después</option>
                <option value="24">24 Días después</option>
                <option value="25">25 Días después</option>
              </optgroup>
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-5 col-form-label">Recordatorio #3</label>
          <div class="col-md-6">
            <select name="config[avisosms3]" class="sl2 form-control" style="width: 100%;">
              <option value="0">Desactivado</option>

              <optgroup label="Antes del vencimiento">
                <option value="-1">1 Día Antes</option>
                <option value="-2">2 Días Antes</option>
                <option value="-3">3 Días Antes</option>
                <option value="-4">4 Días Antes</option>
                <option value="-5">5 Días Antes</option>
                <option value="-6">6 Días Antes</option>
                <option value="-7">7 Días Antes</option>
                <option value="-8">8 Días Antes</option>
                <option value="-9">9 Días Antes</option>
                <option value="-10">10 Días Antes</option>
              </optgroup>


              <optgroup label="Después del vencimiento">
                <option value="1">1 Día después</option>
                <option value="2">2 Días después</option>
                <option value="3">3 Días después</option>
                <option value="4">4 Días después</option>
                <option value="5">5 Días después</option>
                <option value="6">6 Días después</option>
                <option value="7">7 Días después</option>
                <option value="8">8 Días después</option>
                <option value="9">9 Días después</option>
                <option value="10">10 Días después</option>
                <option value="11">11 Días después</option>
                <option value="12">12 Días después</option>
                <option value="13">13 Días después</option>
                <option value="14">14 Días después</option>
                <option value="15">15 Días después</option>
                <option value="16">16 Días después</option>
                <option value="17">17 Días después</option>
                <option value="18">18 Días después</option>
                <option value="19">19 Días después</option>
                <option value="20">20 Días después</option>
                <option value="21">21 Días después</option>
                <option value="22">22 Días después</option>
                <option value="23">23 Días después</option>
                <option value="24">24 Días después</option>
                <option value="25">25 Días después</option>
              </optgroup>
            </select>
            <small class="text-warning">* Días antes/despues del vencimiento de una factura</small>
          </div>
        </div>

        <br>
        <div class="text-right m-b-10"><button type="submit" <?= $permiso2 ? "" : "style=\"display:none;\""; ?> class="btn-sm btn-azul"><i class="far fa-save"></i> Guardar cambios</button></div>
        <div class="row">
          <div class="col-md-6">
            <div class="alert alert-pantalla">
              Aviso pantalla: <b class="daypantalla"></b>
              04:30 AM</div>
          </div>

          <div class="col-md-6">
            <div class="alert alert-sms1">
              Recordatorio #1: <b class="daysms1"></b>
              08:30 AM</div>
          </div>

          <div class="col-md-6">
            <div class="alert alert-sms2">
              Recordatorio #2: <b class="daysms2"></b>
              01:30 PM</div>
          </div>

          <div class="col-md-6">
            <div class="alert alert-sms3">
              Recordatorio #3: <b class="daysms3"></b>
              05:00 PM</div>
          </div>

        </div>

      </div>
    </div>
  </div>

</form>
<div class="pppover"></div>
<script>
  $(function() {
    $('.btn-ayuda').popover({
      container: '.pppover',
      placement: 'top',
      html: true
    });

    $('select[name="config\[limit_velocidad\]"] option[value="<?= $config->limit_velocidad; ?>"]').prop('selected', true);

    $('select[name="config\[tipoaviso\]"] option[value="<?= $config->tipoaviso; ?>"]').prop('selected', true);
    $('select[name="config\[tipopago\]"] option[value="<?= $config->tipopago; ?>"]').prop('selected', true);
    $('select[name="config\[diapago\]"] option[value="<?= $config->diapago; ?>"]').prop('selected', true);
    $('select[name="config\[diafactura\]"] option[value="<?= $config->diafactura; ?>"]').prop('selected', true);
    $('select[name="config\[impuesto\]"] option[value="<?= $config->impuesto; ?>"]').prop('selected', true);
    $('select[name="config\[corteautomatico\]"] option[value="<?= $config->corteautomatico; ?>"]').prop('selected', true);
    $('select[name="config\[meses\]"] option[value="<?= $config->meses; ?>"]').prop('selected', true);
    $('select[name="config\[afip_condicion_iva\]"] option[value="<?= $config->afip_condicion_iva; ?>"]').prop('selected', true);
    $('select[name="config\[afip_condicion_venta\]"] option[value="<?= $config->afip_condicion_venta; ?>"]').prop('selected', true);
    $('select[name="config\[afip_automatico\]"] option[value="<?= $config->afip_automatico; ?>"]').prop('selected', true);
    $('select[name="config\[avisopantalla\]"] option[value="<?= $config->avisopantalla; ?>"]').prop('selected', true);
    $('select[name="config\[tiporecordatorio\]"] option[value="<?= $config->tiporecordatorio; ?>"]').prop('selected', true);
    $('select[name="config\[avisosms\]"] option[value="<?= $config->avisosms; ?>"]').prop('selected', true);
    $('select[name="config\[avisosms2\]"] option[value="<?= $config->avisosms2; ?>"]').prop('selected', true);
    $('select[name="config\[avisosms3\]"] option[value="<?= $config->avisosms3; ?>"]').prop('selected', true);


    $('select[name="config\[diapago\]"],select[name="config\[corteautomatico\]"],select[name="config\[diafactura\]"],select[name="config\[avisopantalla\]"],select[name="config\[avisosms\]"],select[name="config\[avisosms2\]"],select[name="config\[avisosms3\]"],select[name="config\[meses\]"],select[name="config\[tiporecordatorio\]"]').change(function() {
      var datastring = $('#frm-save-config-invoice').serialize();
      $.ajax({
        type: "POST",
        url: "clientes_facturacion/get_notas",
        data: datastring,
        dataType: "json",
        success: function(data) {
          $('.daypago').text(data['daypago']);
          $('.daycorte').text(data['daycorte']);
          $('.dayfactura').text(data['dayfactura']);
          $('.daypantalla').text(data['daypantalla']);
          $('.daysms1').text(data['daysms1']);
          $('.daysms2').text(data['daysms2']);
          $('.daysms3').text(data['daysms3']);
        }
      });
    });


    $('select[name="config\[diapago\]"]').trigger('change');

    $('#frm-save-config-invoice').ajaxForm({
      success: function(data) {
        $('#gritter-notice-wrapper').remove();
        if (data == 2) {
          alerta('error', data['salida']);
        } else {
          if (data == "<p>ACCESO DENEGADO.</p>") {
            alerta('warning', 'Permiso denegado');
          } else {
            alerta('exito', data['salida']);
          };
        }
      },
      beforeSubmit: function() {},
      error: function(response) {
        $('#gritter-notice-wrapper').remove();
        alerta('error500', response['responseText']);
      }
    })


    $('input[name="config\[fecha_factura\]"]').datepicker({
      todayHighlight: true,
      autoclose: true,
      language: "es",
      beforeShowDay: function(date) {
        if (date.getDate() != $('select[name="config\[diapago\]"]').val()) {
          return false
        }
      }
    });


    var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    elems.forEach(function(html) {
      var switchery = new Switchery(html, {
        size: 'small',
        color: 'rgb(18, 187, 168)'
      });
    });

    $(".sl2").select2({
      placeholder: "Seleccionar..."
    });

  })
</script>