<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
  <li class="breadcrumb-item"><a href="/admin">Inicio</a></li>
  <li class="breadcrumb-item"><a href="/herramientas/#clientes">Usuarios</a></li>
  <li class="breadcrumb-item active">Nuevo cliente</li>
</ol>
<!-- end breadcrumb -->
<!-- begin page-header -->
<h1 class="page-header">Nuevo Cliente</h1>
<!-- end page-header -->

<!-- begin wizard-form -->
<form action="clientes/guardar_nuevo_cliente" method="POST" name="form-wizard" class="form-control-with-bg">
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

                <div class="form-group row  m-b-10">
                    <label class="col-md-3 col-form-label text-md-right">Sucursal</label>
                    <div class="col-md-4">
                      <select name="usuarios[sucursal]" class="sl2 form-control form-control-sm " data-parsley-group="step-2" style="width: 100%;">
                       <?=$sucursales?>
                      </select>
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
                  <input value="" type="text" name="usuarios[cedula]" placeholder="223456634" data-parsley-group="step-1" class="form-control form-control-sm" autocomplete="off" style="max-width:160px;float: left;"><button style="float: left;" type="button" class="d-none btnperu m-l-3 btn btn-white" data-toggle="tooltip" data-placement="top" data-original-title="Validar DNI(reniec) o RUC (Sunat)" onClick="validateperu();" data-loading-text='<i class="fas fa-spinner fa-spin"></i>'><i class="fas fa-search"></i></button>
                  <button style="float: left;" type="button" class="d-none btnargentina m-l-3 btn btn-white" data-toggle="tooltip" data-placement="top" data-original-title="Validar DNI o CUIT" onClick="validateargentina();" data-loading-text='<i class="fas fa-spinner fa-spin"></i>'><i class="fas fa-search"></i></button>

                  <button style="float: left;" type="button" class="d-none btnchile m-l-3 btn btn-white" data-toggle="tooltip" data-placement="top" data-original-title="Validar RUT" onClick="validatechile();" data-loading-text='<i class="fas fa-spinner fa-spin"></i>'><i class="fas fa-search"></i></button>

                  <button style="float: left;" type="button" class="d-none btncolombia m-l-3 btn btn-white" data-toggle="tooltip" data-placement="top" data-original-title="Validar NIT" onClick="validatecolombia();" data-loading-text='<i class="fas fa-spinner fa-spin"></i>'><i class="fas fa-search"></i></button>
                  <br>
                  <small class="text-warning m-b-10" style="width: 100%;float: left;">CEDULA,DNI,RUC,CUIT,NIT,SAT,RUT,RTN,ETC.</small>
                </div>
              </div>


              <!-- begin form-group -->
              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">Nombre Completo <span class="text-danger">*</span></label>
                <div class="col-md-8">
                  <input value="" type="text" name="usuarios[nombre]" placeholder="Carlos Miguel Santana castro" data-parsley-group="step-1" data-parsley-required="true" class="form-control form-control-sm" autocomplete="off" required />
                </div>
              </div>

              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">Dirección principal</label>
                <div class="col-md-8">
                  <input value="" type="text" name="usuarios[direccion_principal]" placeholder="Av. unios 4453" class="drprin form-control form-control-sm" data-parsley-group="step-1" autocomplete="address-line1'" />
                </div>
              </div>


              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">Teléfono Movil (separado por comas)</label>
                <div class="col-md-8">
                  <input value="" type="text" name="usuarios[movil]" placeholder="9876526478" class="form-control form-control-sm" data-parsley-group="step-1" autocomplete="off" />
                </div>
              </div>

              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">E-mail</label>
                <div class="col-md-8">
                  <input value="" type="text" name="usuarios[correo]" placeholder="jorge@correo.com" class="form-control form-control-sm" data-parsley-group="step-1" autocomplete="email" />
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
                    <label class="col-md-5 col-form-label">Aplicar Mora</label>
                    <div class="col-md-6">
                      <input type="hidden" name="config[mora]" data-parsley-group="step-2">

                      <label class="switch">
                        <input type="checkbox" class="js-switch" data-parsley-group="step-2" name="config[mora]">
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
      <!-- end step-3 -->
    </div>
    <!-- end wizard-content -->
  </div>
  <!-- end wizard -->
</form>
<!-- end wizard-form -->


<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script>

  $(function() {

    passwordaleatorio('servicio\[ppppass\]');

    App.setPageTitle('Nuevo cliente');
    App.restartGlobalFunction();

  $('input[name="usuarios\[telefono\]"]').on('input', function () {
  this.value = this.value.replace(/[^0-9/,]/g, '');
});
  $('input[name="usuarios\[movil\]"]').on('input', function () {
  this.value = this.value.replace(/[^0-9/,]/g, '');
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
      if (stepNumber == 1) {
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
            alerta('warning', data.mensaje)
          } else {
            loadurl('#clientes');
            alerta('exito', 'Usuario agregado con exito');
          }
        }
      },
      beforeSubmit: function() {

      },
      error: function(response) {
        btnloader('form[name="form-wizard"]', '<i class="far fa-save fa-lg"></i> Registrar Cliente');
        $('#gritter-notice-wrapper').remove();
        alerta('error500', response['responseText']);
      }
    })


    //$('select[name="servicio\[nodo\]"]').trigger('change');
    $('#wizard').show();

  })

</script>
<!-- ================== END PAGE LEVEL JS ================== -->