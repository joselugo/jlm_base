<div id="content" class="content" style="height: 100%">
<!-- begin breadcrumb -->
<ol class="breadcrumb pull-right">
  <li class="breadcrumb-item"><a href="/admin">Inicio</a></li>
  <li class="breadcrumb-item"><a href="/admin/#ajax/usuarios">Usuarios</a></li>
  <li class="breadcrumb-item active">Nuevo cliente</li>
</ol>
<!-- end breadcrumb -->
<!-- begin page-header -->
<h1 class="page-header">Nuevo Cliente</h1>
<!-- end page-header -->

<!-- begin wizard-form -->
<form action="ajax/usuarios?action=newuser" method="POST" name="form-wizard" class="form-control-with-bg">
<input type="hidden" name="install" value="">
  <!-- begin wizard -->
  <div id="wizard" style="" class="sw-main sw-theme-default">
    <!-- begin wizard-step -->
    <ul class="nav nav-tabs step-anchor">
      <li class="col-md-3 col-sm-4 col-6 active">
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
    <div class="sw-container tab-content" style="min-height: 0px;">
      <!-- begin step-1 -->
      <div id="step-1" class="step-content" style="display: block;">
        <!-- begin fieldset -->
        <fieldset>
          <!-- begin row -->
          <div class="row">
            <!-- begin col-8 -->
            <div class="col-lg-9 offset-md-2">

              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">ID cliente</label>
                <div class="col-md-4">
                  <input type="text" name="usuarios[id]" placeholder="100" class="form-control form-control-sm" data-parsley-group="step-1" autocomplete="off">
            <small class="text-warning">Dejar en blanco para que sea automático.</small>
                </div>
              </div>  
              
              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">Contraseña Portal</label>
                <div class="col-md-4">
                  <input type="text" name="usuarios[codigo]" placeholder="4243Tdp" class="form-control form-control-sm" data-parsley-group="step-1" autocomplete="postal-code">
            <small class="text-warning">Dejar en blanco para que sea automático.</small>
                </div>
              </div>  
              
              
                          <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">Nº Identificación</label>
                <div class="col-md-8">
                  <input value="" type="text" name="usuarios[cedula]" placeholder="223456634" data-parsley-group="step-1" class="form-control form-control-sm" autocomplete="off" style="max-width:160px;float: left;"><button style="float: left;" type="button" class=" btnperu m-l-3 btn btn-white" data-toggle="tooltip" data-placement="top" data-original-title="Validar DNI(reniec) o RUC (Sunat)" onclick="validateperu();" data-loading-text="<i class=&quot;fas fa-spinner fa-spin&quot;></i>"><i class="fas fa-search"></i></button>
              <button style="float: left;" type="button" class="d-none btnargentina m-l-3 btn btn-white" data-toggle="tooltip" data-placement="top" data-original-title="Validar DNI o CUIT" onclick="validateargentina();" data-loading-text="<i class=&quot;fas fa-spinner fa-spin&quot;></i>"><i class="fas fa-search"></i></button>
                  
              <button style="float: left;" type="button" class="d-none btnchile m-l-3 btn btn-white" data-toggle="tooltip" data-placement="top" data-original-title="Validar RUT" onclick="validatechile();" data-loading-text="<i class=&quot;fas fa-spinner fa-spin&quot;></i>"><i class="fas fa-search"></i></button>
                  
              <button style="float: left;" type="button" class="d-none btncolombia m-l-3 btn btn-white" data-toggle="tooltip" data-placement="top" data-original-title="Validar NIT" onclick="validatecolombia();" data-loading-text="<i class=&quot;fas fa-spinner fa-spin&quot;></i>"><i class="fas fa-search"></i></button>
                  <br>                  
<small class="text-warning m-b-10" style="width: 100%;float: left;">CEDULA,DNI,RUC,CUIT,NIT,SAT,RUT,RTN,ETC.</small>                  
                </div>
              </div>
              
              
              <!-- begin form-group -->
              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">Nombre Completo <span class="text-danger">*</span></label>
                <div class="col-md-8">
                  <input value="" type="text" name="usuarios[nombre]" placeholder="Carlos Miguel Santana castro" data-parsley-group="step-1" data-parsley-required="true" class="form-control form-control-sm" autocomplete="off" required="">
                </div>
              </div>
              
              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">Dirección principal</label>
                <div class="col-md-8">
                  <input value="" type="text" name="usuarios[direccion_principal]" placeholder="Av. unios 4453" class="form-control form-control-sm" data-parsley-group="step-1" autocomplete="address-line1'">
                </div>
              </div>

              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">Ubicación</label>
                <div class="col-md-8">
                  
              <select name="config[zona]" data-parsley-group="step-1" style="width: 100%;" data-placeholder="Seleccionar Ubicación" class="slw select2-hidden-accessible" data-select2-id="3" tabindex="-1" aria-hidden="true">
              <option data-select2-id="5"></option>
              <option value="1">ccgdfgsdg</option>              </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="4" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-configzona-ub-container"><span class="select2-selection__rendered" id="select2-configzona-ub-container" role="textbox" aria-readonly="true"><span class="select2-selection__placeholder">Seleccionar Ubicación</span></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
              </div>
              </div>

              
              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">Teléfono fijo
</label>
                <div class="col-md-8">
                  <input value="" type="text" name="usuarios[telefono]" placeholder="564567" class="form-control form-control-sm" data-parsley-group="step-1" autocomplete="tel">
                </div>
              </div>            
              
              <div class="form-group row m-b-10">
          <label class="col-md-3 col-form-label text-md-right">Teléfono Movil</label>
                <div class="col-md-8">
                  <input value="" type="text" name="usuarios[movil]" placeholder="9876526478" class="form-control form-control-sm" data-parsley-group="step-1" autocomplete="off">
                </div>
              </div>              

              <div class="form-group row m-b-10">
                <label class="col-md-3 col-form-label text-md-right">E-mail</label>
                <div class="col-md-8">
                  <input value="" type="text" name="usuarios[correo]" placeholder="jorge@correo.com" class="form-control form-control-sm" data-parsley-group="step-1" autocomplete="email">
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
      <div id="step-2" class="step-content">
        <!-- begin fieldset -->
        <fieldset>
          <!-- begin row -->
          <div class="row">

<div class="col-md-7 form-group row m-b-20">
<label class="col-md-5 col-form-label text-right">Cargar desde plantilla</label>
<div class="col-md-6">
<select name="plantilla" style="width: 100%;" data-placeholder="Seleccionar plantilla" class="sl2 select2-hidden-accessible" data-select2-id="6" tabindex="-1" aria-hidden="true">
<option data-select2-id="8"></option>
</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="7" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-plantilla-2x-container"><span class="select2-selection__rendered" id="select2-plantilla-2x-container" role="textbox" aria-readonly="true"><span class="select2-selection__placeholder">Seleccionar plantilla</span></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
</div>
</div>
            
<div class="col-lg-6 form-horizontal ui-sortable">
<div class="panel panel-default">
<div class="panel-heading ui-sortable-handle">
<h4 class="panel-title"><b><i class="far fa-file"></i> Facturación</b></h4>
</div>
<div class="panel-body border-panel">

<div class="form-group row">
<label class="col-md-5 col-form-label">Tipo</label>
<div class="col-md-6">
<select name="config[tipopago]" class="sl2 form-control form-control-sm select2-hidden-accessible" data-parsley-group="step-2" style="width: 100%;" data-select2-id="9" tabindex="-1" aria-hidden="true">
  <option value="0" data-select2-id="11">Prepago (Adelantado)</option>
  <option value="1">Postpago (Vencido)</option>
  </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="10" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-configtipopago-s8-container"><span class="select2-selection__rendered" id="select2-configtipopago-s8-container" role="textbox" aria-readonly="true" title="Prepago (Adelantado)">Prepago (Adelantado)</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
</div>
</div>

<div class="form-group row">
<label class="col-md-5 col-form-label">Día pago</label>
<div class="col-md-6">
<select name="config[diapago]" class="sl2 form-control form-control-sm select2-hidden-accessible" data-parsley-group="step-2" style="width: 100%;" data-select2-id="12" tabindex="-1" aria-hidden="true">
<option value="1" data-select2-id="14">01</option>
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
</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="13" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-configdiapago-v9-container"><span class="select2-selection__rendered" id="select2-configdiapago-v9-container" role="textbox" aria-readonly="true" title="01">01</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>

</div>
</div>

<div class="form-group row">
<label class="col-md-5 col-form-label">Crear Factura</label>
<div class="col-md-6">
<select name="config[diafactura]" class="sl2 form-control select2-hidden-accessible" style="width: 100%;" data-parsley-group="step-2" data-select2-id="15" tabindex="-1" aria-hidden="true">
<option value="0">Desactivado</option>
<option value="1">1 Día antes</option>
<option value="2">2 Días antes</option>
<option value="3">3 Días antes</option>
<option value="4">4 Días antes</option>
<option value="5" selected="" data-select2-id="17">5 Días antes</option>
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
</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="16" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-configdiafactura-f8-container"><span class="select2-selection__rendered" id="select2-configdiafactura-f8-container" role="textbox" aria-readonly="true" title="5 Días antes">5 Días antes</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
</div>
</div>

<div class="form-group row">
<label class="col-md-5 col-form-label">Tipo impuesto</label>
<div class="col-md-6">
<select style="width: 100%;" class="sl2 form-control select2-hidden-accessible" name="config[impuesto]" data-parsley-group="step-2" data-select2-id="18" tabindex="-1" aria-hidden="true">
<option value="NO" data-select2-id="20">Impuestos incluido</option>
<option value="SI">Mas Impuestos</option>
<option value="NADA">Ninguno</option>
</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="19" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-configimpuesto-q3-container"><span class="select2-selection__rendered" id="select2-configimpuesto-q3-container" role="textbox" aria-readonly="true" title="Impuestos incluido">Impuestos incluido</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
</div>
</div>

<div class="form-group row">
<label class="col-md-5 col-form-label">Días de gracia</label>
<div class="col-md-6">
<select name="config[corteautomatico]" class="sl2 form-control select2-hidden-accessible" style="width: 100%;" data-parsley-group="step-2" data-select2-id="21" tabindex="-1" aria-hidden="true">
<option value="0">0 Días</option>
<option value="1">1 Día</option>
<option value="2">2 Días</option>
<option value="3">3 Días</option>
<option value="4">4 Días</option>
<option value="5" selected="" data-select2-id="23">5 Días</option>
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
</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="22" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-configcorteautomatico-2a-container"><span class="select2-selection__rendered" id="select2-configcorteautomatico-2a-container" role="textbox" aria-readonly="true" title="5 Días">5 Días</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
<small class="text-warning">*días tolerancia para aplicar corte</small>
</div>
</div>

<div class="form-group row">
<label class="col-md-5 col-form-label">Aplicar Corte</label>
<div class="col-md-6">
<select name="config[meses]" class="sl2 form-control select2-hidden-accessible" style="width: 100%;" data-parsley-group="step-2" data-select2-id="24" tabindex="-1" aria-hidden="true">
<option value="0">Desactivado</option>
<option value="1" selected="" data-select2-id="26">1 Mes vencido</option>
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
</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="25" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-configmeses-rp-container"><span class="select2-selection__rendered" id="select2-configmeses-rp-container" role="textbox" aria-readonly="true" title="1 Mes vencido">1 Mes vencido</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
</div>
</div>

<div class="form-group row">
<label class="col-form-label col-md-5">Fecha Fija <button type="button" class="btn-ayuda btn btn-default btn-xs" data-toggle="popover" data-html="true" title="" data-content="
       <p><b>Campo vacío:</b></p><p>
       </p><p>Por defecto podemos dejar este campo vacío, esto permite que el sistema maneje la próxima fecha de pago dinamicamente, por ejemplo si tenemos configurado día de pago 1: esto quiere decir que el 1 de todos los meses será su día de pago e iniciará su facturación el próximo 1.</p><br>
       
       <p><b>Seleccionar fecha:</b></p><p>
       </p><p>Al indicar una fecha especifica estamos indicando al sistema que la facturación/día de pago próxima de este cliente iniciará en la fecha que indiquemos en este campo.
       Esta fecha será reseteado (vacío) al cumplir con la fecha seleccionado.
       Indicar una próxima fecha de pago incorrecta puede ocasionar que no se cree la factura del mes deseado.(fecha pasadas).
       Se debe seleccionar una fecha con el mismo día de pago asignado a nuestro cliente, si nuestro cliente tiene dia de pago 5, debemos seleccionar cualquier mes pero día 5.
       </p><br>
     " data-original-title="Fecha de pago"><i class="fas fa-question"></i></button></label>
<div class="col-md-6">
<div class="input-group">
<input type="text" id="conf-dia_factura" name="config[fecha_factura]" class="form-control" readonly="" placeholder="Automático" value="" data-parsley-group="step-2">
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
<select name="config[afip_condicion_iva]" class="sl2 form-control select2-hidden-accessible" style="width: 100%;" data-parsley-group="step-2" data-select2-id="27" tabindex="-1" aria-hidden="true">
<option value="Consumidor Final" data-select2-id="29">Consumidor Final</option>
<option value="IVA Sujeto Exento">IVA Sujeto Exento</option>
<option value="IVA Responsable Inscripto">IVA Responsable Inscripto</option>
<option value="Responsable Monotributo">Responsable Monotributo</option>
</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="28" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-configafip_condicion_iva-q8-container"><span class="select2-selection__rendered" id="select2-configafip_condicion_iva-q8-container" role="textbox" aria-readonly="true" title="Consumidor Final">Consumidor Final</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
</div>
</div>

<div class="form-group row">
<label class="col-md-5 col-form-label">Condición de Venta</label>
<div class="col-md-6">
<select name="config[afip_condicion_venta]" class="sl2 form-control select2-hidden-accessible" style="width: 100%;" data-parsley-group="step-2" data-select2-id="30" tabindex="-1" aria-hidden="true">
<option value="Contado" data-select2-id="32">Contado</option>
<option value="Tarjeta de débito">Tarjeta de débito</option>
<option value="Tarjeta de crédito">Tarjeta de crédito</option>
<option value="Cuenta corriente">Cuenta corriente</option>
<option value="Chequee">Cheque</option>
<option value="Ticket">Ticket</option>
<option value="Otra">Otra</option>
</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="31" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-configafip_condicion_venta-6j-container"><span class="select2-selection__rendered" id="select2-configafip_condicion_venta-6j-container" role="textbox" aria-readonly="true" title="Contado">Contado</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
</div>
</div>

<div class="form-group row">
<label class="col-md-5 col-form-label">Afip Automático</label>
<div class="col-md-6">
<select name="config[afip_automatico]" class="sl2 form-control select2-hidden-accessible" style="width: 100%;" data-parsley-group="step-2" data-select2-id="33" tabindex="-1" aria-hidden="true">
<option value="0" data-select2-id="35">Desactivado</option>
<option value="1">Crear AFIP al CREAR factura de servicio</option>
<option value="2">Crear AFIP al PAGAR factura con pasarela de pago</option>
</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="34" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-configafip_automatico-cr-container"><span class="select2-selection__rendered" id="select2-configafip_automatico-cr-container" role="textbox" aria-readonly="true" title="Desactivado">Desactivado</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
</div>
</div>

</div>

</div>
</div>
</div>

<div class="col-lg-6 form-horizontal ui-sortable">
  
  
<div class="panel panel-default">
<div class="panel-heading ui-sortable-handle">
<h4 class="panel-title"><b><i class="far fa-bell"></i> Notificaciones</b></h4>
</div>
<div class="panel-body border-panel">

<div class="form-group row">
<label class="col-md-5 col-form-label">Aviso nueva factura</label>
<div class="col-md-6">
<select name="config[tipoaviso]" class="sl2 form-control select2-hidden-accessible" style="width: 100%;" data-parsley-group="step-2" data-select2-id="36" tabindex="-1" aria-hidden="true">
<option value="0" data-select2-id="38">Desactivado</option>
<option value="1">Correo</option>
<option value="2">SMS</option>
<option value="4">Telegram</option>
<option value="5">Correo + Telegram</option>
<option value="3">Correo + SMS</option>
<option value="6">Telegram + SMS</option>
</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="37" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-configtipoaviso-u4-container"><span class="select2-selection__rendered" id="select2-configtipoaviso-u4-container" role="textbox" aria-readonly="true" title="Desactivado">Desactivado</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
</div>
</div>

<div class="form-group row">
<label class="col-md-5 col-form-label">Aviso en Pantalla</label>
<div class="col-md-6">
<select name="config[avisopantalla]" class="sl2 form-control select2-hidden-accessible" style="width: 100%;" data-parsley-group="step-2" data-select2-id="39" tabindex="-1" aria-hidden="true">
<option value="0" data-select2-id="41">Desactivado</option>
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
</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="40" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-configavisopantalla-i8-container"><span class="select2-selection__rendered" id="select2-configavisopantalla-i8-container" role="textbox" aria-readonly="true" title="Desactivado">Desactivado</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
<small class="text-warning">* Aviso solo en páginas HTTP</small>
</div>
</div>

<div class="form-group row">
<label class="col-md-5 col-form-label">Recordatorios de pago</label>
<div class="col-md-6">
<select name="config[tiporecordatorio]" class="sl2 form-control select2-hidden-accessible" style="width: 100%;" data-parsley-group="step-2" data-select2-id="42" tabindex="-1" aria-hidden="true">
<option value="0">Desactivado</option>
<option value="1" selected="" data-select2-id="44">Correo</option>
<option value="2">SMS</option>
<option value="4">Telegram</option>
<option value="5">Correo + Telegram</option>
<option value="3">Correo + SMS</option>
<option value="6">Telegram + SMS</option>
</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="43" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-configtiporecordatorio-4u-container"><span class="select2-selection__rendered" id="select2-configtiporecordatorio-4u-container" role="textbox" aria-readonly="true" title="Correo">Correo</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>

</div>
</div>

<div class="form-group row">
<label class="col-md-5 col-form-label">Recordatorio #1</label>
<div class="col-md-6">
<select name="config[avisosms]" class="sl2 form-control select2-hidden-accessible" style="width: 100%;" data-parsley-group="step-2" data-select2-id="45" tabindex="-1" aria-hidden="true">
<option value="0">Desactivado</option>

<optgroup label="Antes del vencimiento">
<option value="-1">1 Día Antes</option>
<option value="-2" selected="" data-select2-id="47">2 Días Antes</option>
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
</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="46" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-configavisosms-rq-container"><span class="select2-selection__rendered" id="select2-configavisosms-rq-container" role="textbox" aria-readonly="true" title="2 Días Antes">2 Días Antes</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
</div>
</div>

<div class="form-group row">
<label class="col-md-5 col-form-label">Recordatorio #2</label>
<div class="col-md-6">
<select name="config[avisosms2]" class="sl2 form-control select2-hidden-accessible" style="width: 100%;" data-parsley-group="step-2" data-select2-id="48" tabindex="-1" aria-hidden="true">
<option value="0" data-select2-id="50">Desactivado</option>

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
</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="49" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-configavisosms2-i0-container"><span class="select2-selection__rendered" id="select2-configavisosms2-i0-container" role="textbox" aria-readonly="true" title="Desactivado">Desactivado</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
</div>
</div>

<div class="form-group row">
<label class="col-md-5 col-form-label">Recordatorio #3</label>
<div class="col-md-6">
<select name="config[avisosms3]" class="sl2 form-control select2-hidden-accessible" style="width: 100%;" data-parsley-group="step-2" data-select2-id="51" tabindex="-1" aria-hidden="true">
<option value="0" data-select2-id="53">Desactivado</option>

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
</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="52" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-configavisosms3-6c-container"><span class="select2-selection__rendered" id="select2-configavisosms3-6c-container" role="textbox" aria-readonly="true" title="Desactivado">Desactivado</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
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
      <div id="step-3" class="step-content">
        <!-- begin fieldset -->
        <fieldset>
          <!-- begin row -->
<div class="row">
<input type="hidden" name="equipo">
<div class="col-lg-6" style="min-height:150px">
<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Router</label>
<div class="col-md-8">
<select class="form-control sl2 select2-hidden-accessible" name="servicio[nodo]" style="width:100%" data-placeholder="Seleccionar Router" data-select2-id="54" tabindex="-1" aria-hidden="true">
<option data-select2-id="56"></option>
</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="55" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-servicionodo-vz-container"><span class="select2-selection__rendered" id="select2-servicionodo-vz-container" role="textbox" aria-readonly="true"><span class="select2-selection__placeholder">Seleccionar Router</span></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
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
<select class="form-control select2-hidden-accessible" name="servicio[idperfil]" style="width:100%" required="" data-placeholder="Seleccionar perfil" data-select2-id="1" tabindex="-1" aria-hidden="true">
</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="2" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-servicioidperfil-pl-container"><span class="select2-selection__rendered" id="select2-servicioidperfil-pl-container" role="textbox" aria-readonly="true"><span class="select2-selection__placeholder">Seleccionar perfil</span></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
</div>
</div>

<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Descripción</label>
<div class="col-md-8">
<textarea class="form-control" name="servicio[descripcion]" style="padding-top: 7px;" required=""></textarea>
<small>* Texto para facturación</small>
</div>
</div>

<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Costo</label>
<div class="col-md-8">
<input type="text" class="form-control" name="servicio[costo]" required="">
</div>
</div>

<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Tipo IPv4</label>
<div class="col-md-8">
<select class="form-control sl2 select2-hidden-accessible" name="servicio[tipoipv4]" style="width:100%" required="" data-placeholder="Seleccionar tipo de IP" data-select2-id="57" tabindex="-1" aria-hidden="true">
<option data-select2-id="59"></option>
<option value="0">IP fija (Estática)</option>
<option value="1">IP dinámica (Pool IPs)</option>
<option value="2">Ninguno (router asignará IP)</option>
</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="58" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-serviciotipoipv4-4o-container"><span class="select2-selection__rendered" id="select2-serviciotipoipv4-4o-container" role="textbox" aria-readonly="true"><span class="select2-selection__placeholder">Seleccionar tipo de IP</span></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
</div>
</div>

<div class="form-group row sred" style="display:none">
<label class="col-md-4 col-form-label text-right lblred">Redes IPv4</label>
<div class="col-md-8">
<select class="form-control sl2 select2-hidden-accessible" name="servicio[redipv4]" style="width:100%" required="" data-select2-id="60" tabindex="-1" aria-hidden="true">
<option data-select2-id="62"></option>
</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="61" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-servicioredipv4-ya-container"><span class="select2-selection__rendered" id="select2-servicioredipv4-ya-container" role="textbox" aria-readonly="true"></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
</div>
</div>


<div class="form-group row sip" style="display:none">
<label class="col-md-4 col-form-label text-right">IPv4</label>
<div class="col-md-8">
<select class="form-control sl2 select2-hidden-accessible" name="servicio[ip][]" style="width:100%" multiple="" data-select2-id="63" tabindex="-1" aria-hidden="true">
<option></option>
</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="64" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--multiple" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="-1" aria-disabled="false"><ul class="select2-selection__rendered"><li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" tabindex="0" autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false" role="searchbox" aria-autocomplete="list" placeholder="" style="width: 0.75em;"></li></ul></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
</div>
</div>

<div class="form-group row">
<label class="col-md-4 col-form-label text-right">Mac
<button type="button" class="btn btn-icono btn-xs btnmac" data-toggle="tooltip" data-placement="top" data-original-title="Obtener Mac" onclick="getmac()"><i class="fas fa-question"></i></button>
</label>
<div class="col-md-8">
<input type="text" class="form-control" name="servicio[mac]" maxlength="17">
</div>
</div>

<div class="form-group row sppp" style="display:none">
<label class="col-md-4 col-form-label text-right">User PPP/HS
<button type="button" class="btn btn-icono btn-xs" data-toggle="tooltip" data-placement="top" data-original-title="Generar usuario aleatorio" onclick="useraleatorio('servicio\[pppuser\]')"><i class="far fa-user"></i></button>
</label>
<div class="col-md-8">
<input type="text" class="form-control" name="servicio[pppuser]" value="0000000002">
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

<div class="col-md-9">
<input type="text" class="form-control" name="servicio[direccion]" autocomplete="address-line1">
</div>
</div>

<div class="form-group row">
<label class="col-md-3 col-form-label text-right">Coordenadas
<button type="button" class="btn btn-icono btn-xs btn-ubi" data-toggle="tooltip" data-placement="top" data-original-title="Obtener Ubicación Actual" data-loading-text="<i class='fas fa-spinner fa-spin'></i>" onclick="getubicacion()"><i class="fas fa-location-arrow"></i></button>
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
<input type="text" class="form-control" name="servicio[instalado]" readonly="" value="03/03/2020">
</div>
</div>
</div>

<div class="form-group row">
<label class="col-md-3 col-form-label text-right">Antena emisora</label>
<div class="col-md-9">
<select class="form-control sl2 select2-hidden-accessible" name="servicio[emisor]" style="width:100%" data-select2-id="65" tabindex="-1" aria-hidden="true">
<option data-select2-id="67"></option>
</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="66" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-servicioemisor-1i-container"><span class="select2-selection__rendered" id="select2-servicioemisor-1i-container" role="textbox" aria-readonly="true"></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
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
<select class="form-control sl2 select2-hidden-accessible" name="servicio[antena]" style="width:100%" data-select2-id="68" tabindex="-1" aria-hidden="true">
<option value="0" data-select2-id="70">Otro</option>
<option value="1">Ubiquiti® M</option>
<option value="2">Ubiquiti® AC</option>
<option value="3">Mikrotik</option>
<option value="4">Cambium Networks (ePMP)</option>
</select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="69" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-servicioantena-op-container"><span class="select2-selection__rendered" id="select2-servicioantena-op-container" role="textbox" aria-readonly="true" title="Otro">Otro</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
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
$("select[name=\"plantilla\"]").change(function(){
$("input[name=\"nameplantilla\"]").val($('option:selected',this).text());
$.post( "ajax/ajustes", { action: "getplantillapredeterminado", id: $("select[name=\"plantilla\"]").val() })
  .done(function( data ) {
$('.btn-gdpr').prop('disabled', false);
$('input[name="impuestos\[1\]"]').val(data['data']['impuestos'][1]);
$('input[name="impuestos\[2\]"]').val(data['data']['impuestos'][2]);
$('input[name="impuestos\[3\]"]').val(data['data']['impuestos'][3]);

$(".sl2").select2('destroy');
$('[name="config\[mora\]"]').prop('checked',false);
$('[name="config\[afip\]"]').prop('checked',false);
$('[name="config\[reconexion\]"]').prop('checked',false);

for(var k in data['data']['config']){
  
if(k=='mora' && data['data']['config'][k]){
$('[name="config\['+k+'\]"]').prop('checked',true); 
}else if(k=='reconexion' && data['data']['config'][k]){
$('[name="config\['+k+'\]"]').prop('checked',true); 
}else if(k=='afip' && data['data']['config'][k]){
$('[name="config\['+k+'\]"]').prop('checked',true); 
}else{
$('select[name="config\['+k+'\]"] option[value="'+data['data']['config'][k]+'"]').prop('selected', true); 
}

}
$(".sl2").select2({
placeholder: "Seleccionar..."
}); 
  });
});
  

function getmac(){
$.post( "ajax/viewuser?action=getmac&router="+$('select[name="servicio\[nodo\]"]').val()+"&ip="+$('select[name="servicio\[ip\]\[\]"]').val(), function( data ) {
if(data){$('input[name="servicio\[mac\]"]').val(data);}
}); 
}

$('.btn-map').on('click', function () {
$.post( "ajax/viewuser", { action: "mapa",router:$('select[name="servicio\[nodo\]"]').val()})
.done(function( datas ) {
$('#tmp2').html(datas);
});
});

</script>
          <!-- end row -->
        </fieldset>
        <!-- end fieldset -->
      </div>
      <!-- end step-3 -->
    </div><nav class="navbar btn-toolbar sw-toolbar sw-toolbar-bottom"><div class="btn-group navbar-btn sw-btn-group-extra pull-right" role="group"><button type="submit" class="btn-loader btn btn-success btn-finish d-none"><i class="far fa-save fa-lg"></i> Registrar Cliente</button></div><div class="btn-group navbar-btn sw-btn-group pull-right" role="group"><button class="btn btn-default sw-btn-prev disabled" type="button">Anterior</button><button class="btn btn-default sw-btn-next" type="button">Siguiente</button></div></nav>
    <!-- end wizard-content -->
  </div>
  <!-- end wizard -->
</form>
<!-- end wizard-form -->


<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script>
  
function validateperu(){
if($('input[name="usuarios\[cedula\]"]').val()!=""){
$('.btnperu').button('loading');
$.post( "ajax/viewuser", { action:"validateperu", codigo: $('input[name="usuarios\[cedula\]"]').val()})
  .done(function(data) {
$('.btnperu').button('reset');
$('.tooltip').remove();
if(data['estado']=="error"){
alerta('error',data['salida']); 
}else{
alerta('exito','Nº Indentifiación correcto');
switch(data['tipo']) {
  case "dni":
$('input[name="usuarios\[nombre\]"]').val(data['data']['nombres']+' '+data['data']['apellidoPaterno']+' '+data['data']['apellidoMaterno']);
    break;
  case "ruc":
$('input[name="usuarios\[direccion_principal\]"]').val(data['data']['direccion']+' - '+data['data']['distrito']);
$('input[name="usuarios\[nombre\]"]').val(data['data']['razonSocial']);
    break;
}
}
  
  
  });
}
}
  
function validateargentina(){
if($('input[name="usuarios\[cedula\]"]').val()!=""){
$('.btnargentina').button('loading');
$.post( "ajax/viewuser", { action:"validateargentina", codigo: $('input[name="usuarios\[cedula\]"]').val()})
  .done(function(data) {
$('.btnargentina').button('reset');
$('.tooltip').remove();
if(data['estado']=="error"){
alerta('error',data['mensaje']);  
}else{
alerta('exito','Nº Indentifiación correcto');
$('input[name="usuarios\[nombre\]"]').val(data['data']);
}
  
  
  });
}
}
  
function validatechile(){
if($('input[name="usuarios\[cedula\]"]').val()!=""){
$('.btnchile').button('loading');
$.post( "ajax/viewuser", { action:"validatechile", codigo: $('input[name="usuarios\[cedula\]"]').val()})
  .done(function(data) {
$('.btnchile').button('reset');
$('.tooltip').remove();
if(data['estado']=="error"){
alerta('error',data['mensaje']);  
}else{
alerta('exito','Nº Indentifiación correcto');
$('input[name="usuarios\[nombre\]"]').val(data['data']['razonSocial']);
$('input[name="usuarios\[direccion_principal\]"]').val(data['data']['direccion']+', '+data['data']['comuna']);
}   
  });
}
}
  

function validatecolombia(){
if($('input[name="usuarios\[cedula\]"]').val()!=""){
$('.btncolombia').button('loading');
$.post( "ajax/viewuser", { action:"validatecolombia", codigo: $('input[name="usuarios\[cedula\]"]').val()})
  .done(function(data) {
$('.btncolombia').button('reset');
$('.tooltip').remove();
if(data['estado']=="error"){
alerta('error',data['mensaje']);  
}else{
alerta('exito','Nº Indentifiación correcto');
$('input[name="usuarios\[nombre\]"]').val(data['data']);
}
  
  
  });
}
}
  
$(function() {
  
passwordaleatorio('servicio\[ppppass\]');
  
    App.setPageTitle('Nuevo cliente');
    App.restartGlobalFunction();
  
$('input[name="servicio\[mac\]"]').triggerInputMac({ upperCase: true , splitor : ":" });

$('input[name="servicio\[instalado\]"]').datepicker({
        todayHighlight: true,
        autoclose: true,
    language: "es",
    });

$('select[name="servicio\[nodo\]"]').change(function() {
  
//-->Obtener los planes
$('select[name="servicio\[idperfil\]"]').select2('destroy');
$.post( "ajax/usuarios", { action: "getplanes", router: $(this).val(),tipo:'nuevo' })
  .done(function( data ) {
$('select[name="servicio\[idperfil\]"]').html(data);
$('select[name="servicio\[idperfil\]"]').select2();
  });
  
if($(this).find(':selected').data('tipo')>2){
$('select[name="servicio\[tipoipv4\]"]').html('<option></option><option value="0">IP fija (Estática)</option><option value="1">IP dinámica (Pool IPs)</option>');
}else{
$('select[name="servicio\[tipoipv4\]"]').html('<option></option><option value="0">IP fija (Estática)</option>');  
}
$('.sip,.sred').slideUp();  
$('.sppp').hide();
if($(this).find(':selected').data('tipo')>0){
$('.sppp').show();
}
  
$('.dataservice').show();

autosize(document.querySelectorAll('textarea'));
});

$('select[name="servicio\[idperfil\]"]').change(function() {
$('input[name="servicio\[costo\]"]').val($(this).find(':selected').data('costo'));
$('textarea[name="servicio\[descripcion\]"]').val($(this).find(':selected').data('descripcion')).height('0');
$('textarea[name="servicio\[descripcion\]"]').height( $("textarea")[0].scrollHeight );
});
 
$('select[name="servicio\[antena\]"]').change(function() {
if($(this).val()>0){
$('.antena').slideDown();
}else{
$('.antena').slideUp();
}
});

$('select[name="servicio\[tipoipv4\]"]').change(function() {

$('select[name="servicio\[redipv4\]"]').select2('destroy');
$('select[name="servicio\[redipv4\]"]').find('option').remove().end().append('<option></option>');

if($(this).val()<2){
$.post( "ajax/viewuser", {action:"getredipv4",tipo:$(this).val(),router:$('select[name="servicio\[nodo\]"]').val()}).done(function( data ) {
$.each(data['redes'], function(k,v) {

if(v.red){$('select[name="servicio\[redipv4\]"]').append('<option data-cidr="'+v.cidr+'" data-rango="'+v.rango+'" value="'+v.id+'">'+v.red+'</option>');}

});

});
}

$('.sred').slideDown();

$('select[name="servicio\[ip\]\[\]"]').select2('destroy');

if($(this).val()==1){
$('.lblred').text('Pool IPv4');
$('.sip').slideUp();
$('.btnmac').prop('disabled', true);

$('select[name="servicio\[ip\]\[\]"]').find('option').remove().end().append('<option></option>');
$('select[name="servicio\[ip\]\[\]"]').prop('required',false);
$('select[name="servicio\[redipv4\]"]').prop('required',true);

}else if($(this).val()==0){
$('.lblred').text('Redes IPv4');
$('.btnmac').prop('disabled', false);
$('select[name="servicio\[ip\]\[\]"],select[name="servicio\[redipv4\]"]').prop('required',true);
}else{
$('.sip,.sred').slideUp();  
$('select[name="servicio\[ip\]\[\]"],select[name="servicio\[redipv4\]"]').prop('required',false);
}

$('select[name="servicio\[ip\]\[\]"],select[name="servicio\[redipv4\]"]').select2({
placeholder: "Seleccionar..."
});

});


$('select[name="servicio\[redipv4\]"]').change(function() {
if($('select[name="servicio\[tipoipv4\]"]').val()==0){
$('.sip').slideDown();

$('select[name="servicio\[ip\]\[\]"]').select2('destroy');
$('select[name="servicio\[ip\]\[\]"]').find('option').remove().end().append('<option></option>');

$.post( "ajax/viewuser", {id:$('select[name="servicio\[redipv4\]"]').val(),action:"getlibre",router:$('select[name="servicio\[nodo\]"]').val(),cidr:$(this).find(':selected').data('cidr'),red:$(this).find(':selected').data('rango')}).done(function( data ) {

$.each(data['rangos'], function(k,v) {
$('select[name="servicio\[ip\]\[\]"]').append('<option value="'+v+'">'+v+'</option>');
});

$('select[name="servicio\[ip\]\[\]"] option[value="'+data['libre']+'"]').prop('selected', true);

});

$('select[name="servicio\[ip\]\[\]"]').select2({
placeholder: "Seleccionar Ip disponible"
});

}
});
  
$('.slw,.sl2').select2();
  
$('.btn-ayuda').popover({
container:'body',
placement:'top',
html:true
});
  
$('input[name="config\[fecha_factura\]"]').datepicker({
        todayHighlight: true,
        autoclose: true,
    language: "es",
  beforeShowDay: function (date){
    if(date.getDate()!=$('select[name="config\[diapago\]"]').val()){
    return false
    }
  }
    }); 

  $('#wizard').smartWizard({ 
    selected: 0, 
    theme: 'default',
    transitionEffect:'',
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
if(stepNumber==2){
$('.btn-finish').removeClass( "d-none" );
$('.sw-btn-group').hide();
}else{
$('.btn-finish').addClass( "d-none" );
$('.sw-btn-group').show();
}
  });


$('form[name="form-wizard"]').on('submit', function(e) {
var installers=0;
if($('select[name="servicio\[nodo\]"]').val()>0 ||installers >0){
$(this).parsley().validate();
 if(!$(this).parsley().isValid()) {
e.preventDefault();
alerta('error','Complete los campos Obligatorios.')
return false; 
 }
}
  });
  
  
$('form[name="form-wizard"]').ajaxForm({
success: function(data) {
btnloader('form[name="form-wizard"]','<i class="far fa-save fa-lg"></i> Registrar Cliente');
$('#gritter-notice-wrapper').remove();
if(data['estado']=='error'){
alerta('error',data['salida']);
}else{
loadurl(data['url']);
alerta('exito',data['salida']);
}
},
beforeSubmit: function() {
btnloader('form[name="form-wizard"]');
},
error: function(response) {
btnloader('form[name="form-wizard"]','<i class="far fa-save fa-lg"></i> Registrar Cliente');
$('#gritter-notice-wrapper').remove();
alerta('error500',response['responseText']);
}})

  
//$('select[name="servicio\[nodo\]"]').trigger('change'); 
$('#wizard').show();
  
})
</script>
<!-- ================== END PAGE LEVEL JS ================== -->





</div>