<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
  <li class="breadcrumb-item"><a href="#index/home">Inicio</a></li>
  <li class="breadcrumb-item"><a href="#clientes">Usuarios</a></li>
  <li class="breadcrumb-item active">Nuevo cliente</li>
</ol>
<!-- end breadcrumb -->
<!-- begin page-header -->
<h1 class="page-header">Nuevo Cliente</h1>
<!-- end page-header -->

<!-- begin wizard-form -->
<form action="clientes/guardar_nuevo_cliente_install" method="POST" name="form-wizard" class="form-control-with-bg">
  <input type="hidden" name="install" value="">
  <!-- begin wizard -->
  <div id="wizard" style="display: none">
    <!-- begin wizard-step -->
    <ul>
      <li class="col-md-3 col-sm-4 col-6">
        <a href="#step-1">
          <span class="number">1</span>
          <span class="info text-ellipsis">
            Datos personales
            <small class="text-ellipsis">Nombre, dirección, teléfonos</small>
          </span>
        </a>
      </li>
      <li class="col-md-3 col-sm-4 col-6">
        <a href="#step-2">
          <span class="number">2</span>
          <span class="info text-ellipsis">
            Facturación y Noficaciones
            <small class="text-ellipsis">Día de pago,Corte,aviso</small>
          </span>
        </a>
      </li>
      <li class="col-md-3 col-sm-4 col-6">
        <a href="#step-3">
          <span class="number">3</span>
          <span class="info text-ellipsis">
            Servicios
            <small class="text-ellipsis">Queues,PPPoE,Hotspot,etc.</small>
          </span>
        </a>
      </li>

    </ul>
    <!-- end wizard-step -->
    <!-- begin wizard-content -->
    <div>
      <!-- begin step-1 -->
      <div id="step-1">
        <!-- begin fieldset -->
        <fieldset>
          <!-- begin row -->
          <div class="row">
            <!-- begin col-8 -->
            <div class="col-lg-9 offset-md-2">

              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">ID cliente</label>
                <div class="col-md-4">
                  <input type="text" name="usuarios[id]" placeholder="100" class="form-control form-control-sm" data-parsley-group="step-1" autocomplete="off" />
                  <small class="text-warning">Dejar en blanco para que sea automático.</small>
                </div>
              </div>

              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">Contraseña Portal</label>
                <div class="col-md-4">
                  <input type="text" name="usuarios[codigo]" placeholder="4243Tdp" class="form-control form-control-sm" data-parsley-group="step-1" autocomplete="postal-code" />
                  <small class="text-warning">Dejar en blanco para que sea automático.</small>
                </div>
              </div>


              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">Nº Identificación</label>
                <div class="col-md-8">
                  <input value="<?= $instalaciones->cedula ?>" type="text" name="usuarios[cedula]" placeholder="223456634" data-parsley-group="step-1" class="form-control form-control-sm" autocomplete="off" style="max-width:160px;float: left;"><button style="float: left;" type="button" class="d-none btnperu m-l-3 btn btn-white" data-toggle="tooltip" data-placement="top" data-original-title="Validar DNI(reniec) o RUC (Sunat)" onClick="validateperu();" data-loading-text='<i class="fas fa-spinner fa-spin"></i>'><i class="fas fa-search"></i></button>
                  <button style="float: left;" type="button" class="d-none btnargentina m-l-3 btn btn-white" data-toggle="tooltip" data-placement="top" data-original-title="Validar DNI o CUIT" onClick="validateargentina();" data-loading-text='<i class="fas fa-spinner fa-spin"></i>'><i class="fas fa-search"></i></button>

                  <button style="float: left;" type="button" class="d-none btnchile m-l-3 btn btn-white" data-toggle="tooltip" data-placement="top" data-original-title="Validar RUT" onClick="validatechile();" data-loading-text='<i class="fas fa-spinner fa-spin"></i>'><i class="fas fa-search"></i></button>

                  <button style="float: left;" type="button" class="d-none btncolombia m-l-3 btn btn-white" data-toggle="tooltip" data-placement="top" data-original-title="Validar NIT" onClick="validatecolombia();" data-loading-text='<i class="fas fa-spinner fa-spin"></i>'><i class="fas fa-search"></i></button>
                  <br>
                  <small class="text-warning m-b-10" style="width: 100%;float: left;">CEDULA,DNI,RUC,CUIT,NIT,SAT,RUT,RTN,ETC.</small>
                </div>
              </div>

              <input type="hidden" name="usuarios[idinstall]" value="<?= $instalaciones->id ?>">
              <!-- begin form-group -->
              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">Nombre Completo <span class="text-danger">*</span></label>
                <div class="col-md-8">
                  <input value="<?= $instalaciones->cliente ?>" type="text" name="usuarios[nombre]" placeholder="Carlos Miguel Santana castro" data-parsley-group="step-1" data-parsley-required="true" class="form-control form-control-sm" autocomplete="off" required />
                </div>
              </div>

              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">Dirección principal</label>
                <div class="col-md-8">
                  <input value="<?= $instalaciones->direccion ?>" type="text" name="usuarios[direccion_principal]" placeholder="Av. unios 4453" class="drprin form-control form-control-sm" data-parsley-group="step-1" autocomplete="address-line1'" />
                </div>
              </div>

              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">Ubicación</label>
                <div class="col-md-8">

                  <select name="config[zona]" data-parsley-group="step-1" style="width: 100%;" data-placeholder="Seleccionar Ubicación" class="slw">
                    <option></option>
                    <?= $zonas; ?>
                  </select>
                </div>
              </div>


              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">Teléfono fijo (separado por comas)
                </label>
                <div class="col-md-8">
                  <input value="<?= $instalaciones->telefono ?>" type="text" name="usuarios[telefono]" placeholder="564567" class="form-control form-control-sm" data-parsley-group="step-1" autocomplete="tel" />
                </div>
              </div>

              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">Teléfono Movil (separado por comas)</label>
                <div class="col-md-8">
                  <input value="<?= $instalaciones->movil ?>" type="text" name="usuarios[movil]" placeholder="9876526478" class="form-control form-control-sm" data-parsley-group="step-1" autocomplete="off" />
                </div>
              </div>

              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">E-mail</label>
                <div class="col-md-8">
                  <input value="<?= $instalaciones->email ?>" type="text" name="usuarios[correo]" placeholder="jorge@correo.com" class="form-control form-control-sm" data-parsley-group="step-1" autocomplete="email" />
                </div>
              </div>

              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">Pasarela</label>
                <div class="col-md-8">
                  <input value="" type="text" name="usuarios[Pasarela]" placeholder="2239" class="form-control form-control-sm" data-parsley-group="step-1" autocomplete="pasarela" />
                </div>
              </div>
              <!-- end form-group -->
            </div>
            <!-- end col-8 -->
          </div>
          <!-- end row -->
        </fieldset>
        <!-- end fieldset -->
      </div>
      <!-- end step-1 -->
      <!-- begin step-2 -->
      <div id="step-2">
        <!-- begin fieldset -->
        <fieldset>
          <!-- begin row -->
          <div class="row">

            <div class="col-lg-6 form-horizontal">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title"><b><i class="far fa-file"></i> Facturación</b></h4>
                </div>
                <div class="panel-body border-panel">

                  <div class="form-group row">
                    <label class="col-md-5 col-form-label">Tipo</label>
                    <div class="col-md-6">
                      <select name="config[tipopago]" class="sl2 form-control form-control-sm " data-parsley-group="step-2" style="width: 100%;">
                        <option value="0">Prepago (Adelantado)</option>
                        <option value="1">Postpago (Vencido)</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-md-5 col-form-label">Día pago</label>
                    <div class="col-md-6">
                      <select name="config[diapago]" class="sl2 form-control form-control-sm" data-parsley-group="step-2" style="width: 100%;">
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
                      <select name="config[diafactura]" class="sl2 form-control" style="width: 100%;" data-parsley-group="step-2">
                        <option value="0">Desactivado</option>
                        <option value="1">1 Día antes</option>
                        <option value="2">2 Días antes</option>
                        <option value="3">3 Días antes</option>
                        <option value="4">4 Días antes</option>
                        <option value="5" selected>5 Días antes</option>
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
                      <select style="width: 100%;" class="sl2 form-control" name="config[impuesto]" data-parsley-group="step-2">
                        <option value="NO">Impuestos incluido</option>
                        <option value="SI">Mas Impuestos</option>
                        <option value="NADA">Ninguno</option>
                      </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-md-5 col-form-label">Días de gracia</label>
                    <div class="col-md-6">
                      <select name="config[corteautomatico]" class="sl2 form-control" style="width: 100%;" data-parsley-group="step-2">
                        <option value="0">0 Días</option>
                        <option value="1">1 Día</option>
                        <option value="2">2 Días</option>
                        <option value="3">3 Días</option>
                        <option value="4">4 Días</option>
                        <option value="5" selected>5 Días</option>
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
                      <select name="config[meses]" class="sl2 form-control" style="width: 100%;" data-parsley-group="step-2">
                        <option value="0">Desactivado</option>
                        <option value="1" selected>1 Mes vencido</option>
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
                    <label class="col-form-label col-md-5">Fecha Fija <button type="button" class="btn-ayuda btn btn-default btn-xs" data-toggle="popover" data-html="true" title="Fecha de pago" data-content="
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
                        <input type="text" id="conf-dia_factura" name="config[fecha_factura]" class="form-control" readonly placeholder="Automático" value="" data-parsley-group="step-2">
                        <span class="input-group-addon" style="cursor:pointer" data-toggle="tooltip" data-placement="bottom" title="" onclick="$('#conf-dia_factura').val('')" data-original-title="Borrar Fecha"><i class="far fa-trash-alt" aria-hidden="true"></i></span>
                      </div>

                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-md-5 col-form-label">Aplicar Mora</label>
                    <div class="col-md-6">
                      <input type="hidden" name="config[mora]" data-parsley-group="step-2">

                      <label class="switch">
                        <input type="checkbox" class="js-switch" data-parsley-group="step-2" name="config[mora]">
                        <span class="slider round"></span>
                      </label>

                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-md-5 col-form-label">Aplicar Reconexión</label>
                    <div class="col-md-6">
                      <input type="hidden" name="config[reconexion]" data-parsley-group="step-2">

                      <label class="switch">
                        <input type="checkbox" class="js-switch" data-parsley-group="step-2" name="config[reconexion]">
                        <span class="slider round"></span>
                      </label>

                    </div>
                  </div>

                  <h5 class="text-center"><b>Otros Impuestos</b></h5>
                  <small class="text-center m-b-10" style="width: 100%; display: block">Estos Impuestos serán Agregados al total de la factura</small>
                  <div class="form-group row">
                    <label class="col-md-5 col-form-label">Impuesto #1 (%)</label>
                    <div class="col-md-6">
                      <input type="number" name="impuestos[1]" class="form-control" placeholder="10" value="" step="0.1" max="100" min="0">
                      <small>* Dejar en 0 (cero) para quedar deshabilitado</small>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-md-5 col-form-label">Impuesto #2 (%)</label>
                    <div class="col-md-6">
                      <input type="number" name="impuestos[2]" class="form-control" placeholder="10" value="" step="0.1" max="100" min="0">
                      <small>* Dejar en 0 (cero) para quedar deshabilitado</small>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label class="col-md-5 col-form-label">Impuesto #3 (%)</label>
                    <div class="col-md-6">
                      <input type="number" name="impuestos[3]" class="form-control" placeholder="10" value="" step="0.1" max="100" min="0">
                      <small>* Dejar en 0 (cero) para quedar deshabilitado</small>
                    </div>
                  </div>

                  <div class="configafip  d-none">
                    <h5 class="text-center"><b>Configuración AFIP</b></h5>

                    <div class="form-group row">
                      <label class="col-md-5 col-form-label">Activar afip</label>
                      <div class="col-md-6">

                        <label class="switch">
                          <input type="hidden" name="config[afip]" data-parsley-group="step-2">
                          <input type="checkbox" class="js-switch" data-parsley-group="step-2" name="config[afip]">
                          <span class="slider round"></span>
                        </label>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-md-5 col-form-label">Nº Punto de venta</label>
                      <div class="col-md-6">
                        <input type="text" name="config[afip_punto_venta]" class="form-control" placeholder="Punto de venta general">
                        <small>* Dejar vacío para utiliza punto de venta general</small>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-md-5 col-form-label">Condición frente al IVA</label>
                      <div class="col-md-6">
                        <select name="config[afip_condicion_iva]" class="sl2 form-control" style="width: 100%;" data-parsley-group="step-2">
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
                        <select name="config[afip_condicion_venta]" class="sl2 form-control" style="width: 100%;" data-parsley-group="step-2">
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
                        <select name="config[afip_automatico]" class="sl2 form-control" style="width: 100%;" data-parsley-group="step-2">
                          <option value="0">Desactivado</option>
                          <option value="1">Crear AFIP al CREAR factura de servicio</option>
                          <option value="2">Crear AFIP al PAGAR factura con pasarela de pago</option>
                        </select>
                      </div>
                    </div>

                  </div>

                </div>
              </div>
            </div>

            <div class="col-lg-6 form-horizontal">


              <div class="panel panel-default">
                <div class="panel-heading">
                  <h4 class="panel-title"><b><i class="far fa-bell"></i> Notificaciones</b></h4>
                </div>
                <div class="panel-body border-panel">

                  <div class="form-group row">
                    <label class="col-md-5 col-form-label">Aviso nueva factura</label>
                    <div class="col-md-6">
                      <select name="config[tipoaviso]" class="sl2 form-control" style="width: 100%;" data-parsley-group="step-2">
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
                      <select name="config[avisopantalla]" class="sl2 form-control" style="width: 100%;" data-parsley-group="step-2">
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
                      <select name="config[tiporecordatorio]" class="sl2 form-control" style="width: 100%;" data-parsley-group="step-2">
                        <option value="0">Desactivado</option>
                        <option value="1" selected>Correo</option>
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
                      <select name="config[avisosms]" class="sl2 form-control" style="width: 100%;" data-parsley-group="step-2">
                        <option value="0">Desactivado</option>

                        <optgroup label="Antes del vencimiento">
                          <option value="-1">1 Día Antes</option>
                          <option value="-2" selected>2 Días Antes</option>
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
                      <select name="config[avisosms2]" class="sl2 form-control" style="width: 100%;" data-parsley-group="step-2">
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
                      <select name="config[avisosms3]" class="sl2 form-control" style="width: 100%;" data-parsley-group="step-2">
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

                </div>
              </div>
            </div>


          </div>
          <!-- end row -->
        </fieldset>
        <!-- end fieldset -->
      </div>
      <!-- end step-2 -->
      <!-- begin step-3 -->
      <div id="step-3">
        <!-- begin fieldset -->
        <fieldset>
          <!-- begin row -->
          <div class="row">
            <input type="hidden" name="equipo">
            <div class="col-lg-6" style="min-height:150px">
              <div class="form-group row">
                <label class="col-md-4 col-form-label text-right">Sucursal</label>
                <div class="col-md-8">
                  <select class="form-control sl2" name="servicio[sucursal]" required style="width:100%" data-placeholder="Seleccionar una sucursal">
                    <option></option>
                    <?= $sucursales; ?>
                  </select>
                </div>
              </div>
              <div class="form-group row">
                <label class="col-md-4 col-form-label text-right">Router</label>
                <div class="col-md-8">
                  <select class="form-control sl2" name="servicio[nodo]" style="width:100%" data-placeholder="Seleccionar Router">
                    <option></option>
                  </select>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-4 col-form-label text-right">Excluir Firewall</label>
                <div class="col-md-8">
                  <input type="hidden" class="form-control" name="servicio[firewall_state]">

                  <label class="switch">
                    <input type="checkbox" class="form-control chk" name="servicio[firewall_state]">
                    <span class="slider round"></span>
                  </label>
                </div>
              </div>


              <div class="dataservice" style="display:none">
                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-right">Perfil Internet</label>
                  <div class="col-md-8">
                    <select class="form-control" name="servicio[idperfil]" style="width:100%" required data-placeholder="Seleccionar perfil">

                    </select>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-right">Descripción</label>
                  <div class="col-md-8">
                    <textarea class="form-control" name="servicio[descripcion]" style="padding-top: 7px;" required></textarea>
                    <small>* Texto para facturación</small>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-right">Costo</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" name="servicio[costo]" required>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-right">Tipo IPv4</label>
                  <div class="col-md-8">
                    <select class="form-control sl2" name="servicio[tipoipv4]" style="width:100%" required data-placeholder="Seleccionar tipo de IP">
                      <option></option>
                      <option value="0">IP fija (Estática)</option>
                      <option value="1">IP dinámica (Pool IPs)</option>
                      <option value="2">Ninguno (router asignará IP)</option>
                    </select>
                  </div>
                </div>

                <div class="form-group row sred" style="display:none">
                  <label class="col-md-4 col-form-label text-right lblred">Redes IPv4</label>
                  <div class="col-md-8">
                    <select class="form-control sl2" name="servicio[redipv4]" style="width:100%" required>
                      <option></option>
                      <option value="0">IP fija (Estática)</option>
                    </select>
                  </div>
                </div>


                <div class="form-group row sip" style="display:none">
                  <label class="col-md-4 col-form-label text-right">IPv4</label>
                  <div class="col-md-8">
                    <select class="form-control sl2" name="servicio[ip][]" style="width:100%" multiple="multiple">
                      <option></option>
                      <option value="0">IP fija (Estática)</option>
                    </select>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-right">Mac
                    <button type="button" class="btn btn-icono btn-xs btnmac" data-toggle="tooltip" data-placement="top" data-original-title="Obtener Mac" onclick="getmac()"><i class="fas fa-question"></i></button>
                  </label>
                  <div class="col-md-8">
                    <input type="text" required class="form-control" name="servicio[mac]" maxlength="17">
                  </div>
                </div>

                <div class="form-group row sppp" style="display:none">
                  <label class="col-md-4 col-form-label text-right">User PPP/HS
                    <button type="button" class="btn btn-icono btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="Generar usuario aleatorio" onclick="useraleatorio('servicio\[pppuser\]')"><i class="far fa-user"></i></button>
                  </label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" name="servicio[pppuser]" value="0000003804">
                  </div>
                </div>

                <div class="form-group row sppp" style="display:none">
                  <label class="col-md-4 col-form-label text-right">password PPP/HS
                    <button type="button" class="btn btn-icono btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="Generar contraseña aleatoria" onclick="passwordaleatorio('servicio\[ppppass\]')"><i class="fas fa-lock"></i></button>
                  </label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" name="servicio[ppppass]">
                  </div>
                </div>

              </div>

            </div>

            <div class="dataservice col-lg-6" style="display:none">

              <div class="form-group row">
                <label class="col-md-3 col-form-label text-right">Dirección
                  <button type="button" class="btn btn-icono btn-xs btn-map" data-toggle="tooltip" data-placement="top" data-original-title="Abrir google maps"><i class="fas fa-street-view"></i></button>
                </label>
                </label>
                <div class="col-md-9">
                  <input type="text" class="form-control srvdire" name="servicio[direccion]" autocomplete="address-line1">
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 col-form-label text-right">Coordenadas
                  <button type="button" class="btn btn-icono btn-xs btn-ubi" data-toggle="tooltip" data-placement="top" data-original-title="Obtener Ubicación Actual" data-loading-text="<i class='fas fa-spinner fa-spin'></i>" onClick="getubicacion()"><i class="fas fa-location-arrow"></i></button>
                </label>
                <div class="col-md-9">
                  <input type="text" class="form-control" name="servicio[coordenadas]">
                  <small>* Latitud,longitud</small>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 col-form-label text-right">Fecha Instalación</label>
                <div class="col-md-9">
                  <div class="input-group">
                    <div class="input-group-addon"><i class="far fa-calendar"></i></div>
                    <input type="text" class="form-control" name="servicio[instalado]" readonly value="<?= $instalaciones->fecha_instalacion ?>">
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 col-form-label text-right">Antena emisora</label>
                <div class="col-md-9">
                  <select class="form-control sl2" name="servicio[emisor]" style="width:100%">
                    <option></option>
                    <?= $emisores; ?>
                  </select>
                </div>
              </div>


              <div class="form-group row">
                <label class="col-md-3 col-form-label text-right">IP administración</label>
                <div class="col-md-9">
                  <input type="text" class="form-control" name="servicio[ipap]">
                  <small>* Ip antena del cliente</small>
                </div>
              </div>

              <div class="form-group row">
                <label class="col-md-3 col-form-label text-right">Tipo antena</label>
                <div class="col-md-9">
                  <select class="form-control sl2" name="servicio[antena]" style="width:100%">
                    <option value="0">Otro</option>
                    <option value="1">Ubiquiti® M</option>
                    <option value="2">Ubiquiti® AC</option>
                    <option value="3">Mikrotik</option>
                    <option value="4">Cambium Networks (ePMP)</option>
                  </select>
                </div>
              </div>

              <div class="form-group row antena" style="display:none">
                <label class="col-md-3 col-form-label text-right">Usuario antena</label>
                <div class="col-md-9">
                  <input type="text" class="form-control no" name="servicio[user_ubnt]">
                </div>
              </div>

              <div class="form-group row antena" style="display:none">
                <label class="col-md-3 col-form-label text-right">Contraseña antena</label>
                <div class="col-md-9">
                  <input type="text" class="form-control no" name="servicio[pass_ubnt]">
                </div>
              </div>


            </div>

          </div>

          <script>
            $('select[name="servicio\[idperfil\]"]').select2();


            function getmac() {
              $.post("ajax/viewuser?action=getmac&router=" + $('select[name="servicio\[nodo\]"]').val() + "&ip=" + $('select[name="servicio\[ip\]\[\]"]').val(), function(data) {
                if (data) {
                  $('input[name="servicio\[mac\]"]').val(data);
                }
              });
            }

            $('.btn-map').on('click', function() {
              $.post("clientes/modal_mapa", {
                  router: $('select[name="servicio\[nodo\]"]').val()
                })
                .done(function(datas) {
                  $('#tmp2').html(datas);
                });
            });
          </script>
          <!-- end row -->
        </fieldset>
        <!-- end fieldset -->
      </div>
      <!-- end step-3 -->
    </div>
    <!-- end wizard-content -->
  </div>
  <!-- end wizard -->
</form>
<!-- end wizard-form -->


<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script>
  function validateperu() {
    if ($('input[name="usuarios\[cedula\]"]').val() != "") {
      $('.btnperu').button('loading');
      $.post("ajax/viewuser", {
          action: "validateperu",
          codigo: $('input[name="usuarios\[cedula\]"]').val()
        })
        .done(function(data) {
          $('.btnperu').button('reset');
          $('.tooltip').remove();
          if (data['estado'] == "error") {
            alerta('error', data['salida']);
          } else {
            alerta('exito', 'Nº Indentifiación correcto');

            if (data['data']['direccion']) {
              $('input[name="usuarios\[nombre\]"]').val(data['data']['razon']);
              $('input[name="usuarios\[direccion_principal\]"]').val(data['data']['direccion']);
            } else {
              $('input[name="usuarios\[nombre\]"]').val(data['data']['nombre']);
            }

          }


        });
    }
  }

  function validateargentina() {
    if ($('input[name="usuarios\[cedula\]"]').val() != "") {
      $('.btnargentina').button('loading');
      $.post("ajax/viewuser", {
          action: "validateargentina",
          codigo: $('input[name="usuarios\[cedula\]"]').val()
        })
        .done(function(data) {
          $('.btnargentina').button('reset');
          $('.tooltip').remove();
          if (data['estado'] == "error") {
            alerta('error', data['mensaje']);
          } else {
            alerta('exito', 'Nº Indentifiación correcto');
            $('input[name="usuarios\[nombre\]"]').val(data['data']);
          }


        });
    }
  }

  function validatechile() {
    if ($('input[name="usuarios\[cedula\]"]').val() != "") {
      $('.btnchile').button('loading');
      $.post("ajax/viewuser", {
          action: "validatechile",
          codigo: $('input[name="usuarios\[cedula\]"]').val()
        })
        .done(function(data) {
          $('.btnchile').button('reset');
          $('.tooltip').remove();
          if (data['estado'] == "error") {
            alerta('error', data['mensaje']);
          } else {
            alerta('exito', 'Nº Indentifiación correcto');
            $('input[name="usuarios\[nombre\]"]').val(data['data']['razonSocial']);
            $('input[name="usuarios\[direccion_principal\]"]').val(data['data']['direccion'] + ', ' + data['data']['comuna']);
          }
        });
    }
  }


  function validatecolombia() {
    if ($('input[name="usuarios\[cedula\]"]').val() != "") {
      $('.btncolombia').button('loading');
      $.post("ajax/viewuser", {
          action: "validatecolombia",
          codigo: $('input[name="usuarios\[cedula\]"]').val()
        })
        .done(function(data) {
          $('.btncolombia').button('reset');
          $('.tooltip').remove();
          if (data['estado'] == "error") {
            alerta('error', data['mensaje']);
          } else {
            alerta('exito', 'Nº Indentifiación correcto');
            $('input[name="usuarios\[nombre\]"]').val(data['data']);
          }


        });
    }
  }

  $(function() {

    passwordaleatorio('servicio\[ppppass\]');

    App.setPageTitle('Nuevo cliente');
    App.restartGlobalFunction();

    $('input[name="servicio\[mac\]"]').triggerInputMac({
      upperCase: true,
      splitor: ":"
    });
$('input[name="usuarios\[telefono\]"]').on('input', function () {
  this.value = this.value.replace(/[^0-9/,]/g, '');
});
  $('input[name="usuarios\[movil\]"]').on('input', function () {
  this.value = this.value.replace(/[^0-9/,]/g, '');
});


    $('input[name="servicio\[instalado\]"]').datepicker({
      todayHighlight: true,
      autoclose: true,
      language: "es",
    });

    $('select[name="servicio\[sucursal\]"]').change(function() {
      $('select[name="servicio\[nodo\]"]').select2('destroy');
      $.post("clientes/get_nodos_por_sucursal", {
          sucursal: $(this).val(),
        })
        .done(function(data) {
          $('select[name="servicio\[nodo\]"]').html(data);
          $('select[name="servicio\[nodo\]"]').select2();
        });
    })


    $('select[name="servicio\[nodo\]"]').change(function() {

      //-->Obtener los planes
      $('select[name="servicio\[idperfil\]"]').select2('destroy');
      $.post("clientes/get_planes", {
          nodo: $(this).val(),
        })
        .done(async function(data) {
          await $('select[name="servicio\[idperfil\]"]').html(data);
          $('select[name="servicio\[idperfil\]"]').select2();
        });

      if ($(this).find(':selected').data('tipo') > 2) {
        $('select[name="servicio\[tipoipv4\]"]').html('<option></option><option value="0">IP fija (Estática)</option><option value="1">IP dinámica (Pool IPs)</option>');
      } else {
        $('select[name="servicio\[tipoipv4\]"]').html('<option></option><option value="0">IP fija (Estática)</option>');
      }
      $('.sip,.sred').slideUp();
      $('.sppp').hide();
      if ($(this).find(':selected').data('tipo') > 0) {
        $('.sppp').show();
      }

      $('.dataservice').show();

      autosize(document.querySelectorAll('textarea'));
    });

    $('select[name="servicio\[idperfil\]"]').change(function() {
      $('input[name="servicio\[costo\]"]').val($(this).find(':selected').data('costo'));
      $('textarea[name="servicio\[descripcion\]"]').val($(this).find(':selected').data('descripcion')).height('0');
      $('textarea[name="servicio\[descripcion\]"]').height($("textarea")[0].scrollHeight);
    });

    $('select[name="servicio\[antena\]"]').change(function() {
      if ($(this).val() > 0) {
        $('.antena').slideDown();
      } else {
        $('.antena').slideUp();
      }
    });

    $('select[name="servicio\[tipoipv4\]"]').change(function() {

      $('select[name="servicio\[redipv4\]"]').select2('destroy');
      $('select[name="servicio\[redipv4\]"]').find('option').remove().end().append('<option></option>');

      if ($(this).val() < 2) {
        $.post("clientes/grupo_ipv4_disponibles", {
          tipo: $(this).val(),
          nodo: $('select[name="servicio\[nodo\]"]').val()
        }).done(function(data) {
          data = JSON.parse(data)
          if (data == 2) {
            alerta('error', 'Ocurrio un error al buscar las ips disponibles.');
          } else {
            data.forEach(v => {
              if (v.red) {
                $('select[name="servicio\[redipv4\]"]').append('<option data-cidr="' + v.cidr + '" data-rango="' + v.rango + '" value="' + v.id + '">' + v.red + '</option>');
              }
            });
          }
        });
      }

      $('.sred').slideDown();

      $('select[name="servicio\[ip\]\[\]"]').select2('destroy');

      if ($(this).val() == 1) {
        $('.lblred').text('Pool IPv4');
        $('.sip').slideUp();
        $('.btnmac').prop('disabled', true);

        $('select[name="servicio\[ip\]\[\]"]').find('option').remove().end().append('<option></option>');
        $('select[name="servicio\[ip\]\[\]"]').prop('required', false);
        $('select[name="servicio\[redipv4\]"]').prop('required', true);

      } else if ($(this).val() == 0) {
        $('.lblred').text('Redes IPv4');
        $('.btnmac').prop('disabled', false);
        $('select[name="servicio\[ip\]\[\]"],select[name="servicio\[redipv4\]"]').prop('required', true);
      } else {
        $('.sip,.sred').slideUp();
        $('select[name="servicio\[ip\]\[\]"],select[name="servicio\[redipv4\]"]').prop('required', false);
      }

      $('select[name="servicio\[ip\]\[\]"],select[name="servicio\[redipv4\]"]').select2({
        placeholder: "Seleccionar..."
      });

    });


    $('select[name="servicio\[redipv4\]"]').change(function() {
      if ($('select[name="servicio\[tipoipv4\]"]').val() == 0) {
        $('.sip').slideDown();

        $('select[name="servicio\[ip\]\[\]"]').select2('destroy');
        $('select[name="servicio\[ip\]\[\]"]').find('option').remove().end().append('<option></option>');

        $.post("clientes/ipv4_disponibles", {
          id: $('select[name="servicio\[redipv4\]"]').val(),
          nodo: $('select[name="servicio\[nodo\]"]').val(),
          cidr: $(this).find(':selected').data('cidr'),
          red: $(this).find(':selected').data('rango')
        }).done(function(data) {
          data = JSON.parse(data)
          console.log(data);
          data.forEach(dato => {
            $('select[name="servicio\[ip\]\[\]"]').append('<option value="' + dato.ip + '">' + dato.ip + '</option>');
          });
          $('select[name="servicio\[ip\]\[\]"] option[value="' + data[0].ip + '"]').prop('selected', true);

        });

        $('select[name="servicio\[ip\]\[\]"]').select2({
          placeholder: "Seleccionar Ip disponible"
        });

      }
    });

    $('.slw,.sl2').select2();

    $('.btn-ayuda').popover({
      container: 'body',
      placement: 'top',
      html: true
    });

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

    $('#wizard').smartWizard({
      selected: 0,
      theme: 'default',
      transitionEffect: '',
      transitionSpeed: 0,
      useURLhash: false,
      lang: {
        next: 'Siguiente',
        previous: 'Anterior'
      },
      showStepURLhash: false,
      toolbarSettings: {
        toolbarPosition: 'bottom',
        toolbarExtraButtons: [
          $('<button></button>').html('<i class="far fa-save fa-lg"></i> Registrar Cliente').attr('type', 'submit').addClass('btn-loader btn btn-success btn-finish d-none'),
        ]
      },


    });

    $('#wizard').on('leaveStep', function(e, anchorObject, stepNumber, stepDirection) {
      var res = $('form[name="form-wizard"]').parsley().validate('step-' + (stepNumber + 1));
      return res;
    });


    $("#wizard").on("showStep", function(e, anchorObject, stepNumber, stepDirection) {
      $('.srvdire').val($('.drprin').val());
      if (stepNumber == 2) {
        $('.btn-finish').removeClass("d-none");
        $('.sw-btn-group').hide();
      } else {
        $('.btn-finish').addClass("d-none");
        $('.sw-btn-group').show();
      }
    });


    $('form[name="form-wizard"]').on('submit', function(e) {
      var installers = 0;
      if ($('select[name="servicio\[nodo\]"]').val() > 0 || installers > 0) {
        $(this).parsley().validate();
        if (!$(this).parsley().isValid()) {
          e.preventDefault();
          alerta('error', 'Complete los campos Obligatorios.')
          return false;
        }
      }
    });


    $('form[name="form-wizard"]').ajaxForm({
      success: function(data) {
        data = JSON.parse(data);
        btnloader('form[name="form-wizard"]', '<i class="far fa-save fa-lg"></i> Registrar Cliente');
        $('#gritter-notice-wrapper').remove();
        if (data.estado == 2) {
          alerta('error', 'Error usuario no agregado');
        } else {
          if (data.mensaje != null) {
            loadurl('#clientes');
            alerta('warning', data.mensaje);
          } else {
            loadurl('#clientes');
            alerta('exito', 'Usuario agregado con exito');
          }
        }
      },
      beforeSubmit: function() {
        existe_mac = validarMac($('input[name="servicio\[mac\]"]').val());
        if (existe_mac.mensaje != "") {
          alerta("warning", "Esa mac ya esta ocupada por los sig. usuarios: <br>" + existe_mac.mensaje);
          return false;
        } else {
          btnloader('form[name="form-wizard"]');
        }
      },
      error: function(response) {
        btnloader('form[name="form-wizard"]', '<i class="far fa-save fa-lg"></i> Registrar Cliente');
        $('#gritter-notice-wrapper').remove();
        alerta('error500', response['responseText']);
      }
    })


    //$('select[name="servicio\[nodo\]"]').trigger('change');
    $('#wizard').show();

    $('[name="config[zona]"] option[value="<?= $instalaciones->zona ?>"]').prop('selected', true).change();
    $('[name="servicio[sucursal]"] option[value="<?= $instalaciones->sucursal ?>"]').prop('selected', true).change();

  })


  function validarMac(mac) {
    return $.ajax({
      async: false,
      type: "POST",
      url: "clientes/validar_mac",
      data: {
        mac
      },
      dataType: "JSON"
    }).responseJSON;
  }
</script>
<!-- ================== END PAGE LEVEL JS ================== -->