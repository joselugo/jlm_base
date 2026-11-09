<ol class="breadcrumb pull-right">
<li class="breadcrumb-item"><a href="#index/home">Inicio</a></li>
<li class="breadcrumb-item"><a href="#fiber/onu">Lista onus (Autorizadas)</a></li>
<li class="breadcrumb-item active">Editar onu</li>
</ol>

<h4 class="page-header">
    <i class="fas fa-hdd"></i> Detalles Onu ID#<?=$onu->id?> - <?=$onu->nombre?>
    <style type="text/css">
         .result-details{background: #f9f9f9; padding: 20px; color: #7b8a8b;}
    </style>
</h4>


                    <div class="row">
                        <div class="col-xl-3 col-md-6 ">
      <div class="widget widget-stats bg-white widget-estado">
        <div class="stats-icon stats-icon-sm"><div class="spinner-grow"></div>
        <i class="fas fa-power-off icon-widget" style="display: none"></i>
    </div>
        <div class="stats-content">
          <div class="stats-title">ESTADO</div>
          <div class="stats-number m-b-2" id="estado">ONLINE</div>
          
        </div>
      </div>
    </div>
                       <div class="col-xl-3 col-md-6 ">
      <div class="widget widget-stats bg-white widget-rx-onu">
        <div class="stats-icon stats-icon-sm"><div class="spinner-grow"></div><i class="fas fa-signal icon-widget" style="display: none"></i></div>
        <div class="stats-content">
          <div class="stats-title">SEÑAL RX ONU</div>
          <div class="stats-number m-b-2" id="rx-onu">- 26.89</div>
        </div>
      </div>
    </div>

                    <div class="col-xl-3 col-md-6 ">
      <div class="widget widget-stats bg-white widget-tx-onu">
        <div class="stats-icon stats-icon-sm"><div class="spinner-grow"></div><i class="fas fa-signal icon-widget" style="display: none"></i></div>
        <div class="stats-content">
          <div class="stats-title">SEÑAL TX ONU</div>
          <div class="stats-number m-b-2" id="tx-onu">2.67</div>
        </div>
      </div>
    </div>

                    <div class="col-xl-3 col-md-6 ">
      <div class="widget widget-stats bg-white widget-rx-olt">
        <div class="stats-icon stats-icon-sm"><div class="spinner-grow"></div><i class="fas fa-signal icon-widget" style="display: none"></i></div>
        <div class="stats-content">
          <div class="stats-title">SEÑAL RX OLT</div>
          <div class="stats-number m-b-2" id="rx-olt">- 26.89</div>
        </div>
      </div>
    </div>
                        
                    </div>
               


            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs mb-12" id="nav-tab-options">
                        <li class="nav-item">
                            <a id="nav-tab-details" href="#details" data-toggle="tab" aria-expanded="false" class="nav-link active">
                                <i class="fas fa-list d-md-none d-block"></i>
                                <span class="d-none d-md-block"><i class="fa fa-list"></i> Detalles</span>
                            </a>
                        </li>
                        <!--<li class="nav-item">
                            <a href="#traffic" data-toggle="tab" aria-expanded="true" class="nav-link">
                                <i class="fas fa-chart-line d-md-none d-block"></i>
                                <span class="d-none d-md-block"><i class="fa fa-chart-line"></i> Traffic/Signal</span>
                            </a>
                        </li>-->
                        <li class="nav-item">
                            <a href="#" class="nav-link" id="configuration_navbar_dropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-cog d-md-none d-block"></i>
                                <span class="d-none d-md-block dropdown-toggle">
                                    <i class="fa fa-cog"></i> Configuración en la OLT
                                </span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="configuration_navbar_dropdown">
                                <a id="option_get_status" href="#" onclick="info_onu('<?=$onu->id?>','<?=$onu->nombre?>')"  class="dropdown-item" data-original-title="Obtener Estado" data-toggle="tab" aria-expanded="false">
                                    <span class="">
                                        <i class="fa fa-power-off"></i> Obtener Estado
                                    </span>
                                </a>
                                <a id="option_show_config" href="#" class="dropdown-item" data-original-title="Mostrar Configuración" hidden="">
                                    <span class="">
                                        <i class="fa fa-tools"></i> Mostrar Configuración
                                    </span>
                                </a>
                            </div>
                        </li>
                     
                        <li class="nav-item dropdown">
<!--                            <a href="#edit" data-toggle="tab" aria-expanded="false" class="nav-link">-->
<!--                                <i class="mdi mdi-settings-outline d-md-none d-block"></i>-->
<!--                                <span class="d-none d-md-block"><i class="fas fa-arrow-circle-down"></i> Herramientas</span>-->
<!--                            </a>-->
                            <a class="nav-link" href="#" id="tools_navbar_dropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-tools d-md-none d-block"></i>
                                <span class="d-none d-md-block dropdown-toggle">
                                    <i class="fas fa-tools"></i> Herramientas
                                </span>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="tools_navbar_dropdown">
                                <a href="javascript:void(0)" id="button_reboot_onu" class="dropdown-item" onclick="reboot_onu('<?=$onu->id?>','<?=$onu->nombre?>')" data-placement="top" data-original-title="Reiniciar ONU"><span class=""><i class="fas fa-sync"></i> Reiniciar</span></a>
                               
                               
                                <a href="javascript:void(0)" id="button_disable_onu" class="dropdown-item" data-original-title="Deshabilitar ONU"><i class="fas fa-power-off"></i> Deshabilitar ONU</a>

                                <a href="javascript:void(0)" id="button_enable_onu" class="dropdown-item" onclick="open_onu_modal('#button_enable_onu')"  data-placement="top" data-original-title="Habilitar ONU"><i class="fas fa-power-off"></i> Habilitar ONU</a>

                                <a href="#" id="button_restore_onu" class="dropdown-item" onclick="open_onu_modal('#button_restore_onu')"data-placement="top" data-original-title="Restaurar ONU"><i class="fas fa-redo-alt"></i> Resturar ONU de Fabrica</a>
                                <a href="#" id="button_export_onu" class="dropdown-item" onclick="open_onu_modal('#button_export_onu')" data-placement="top" data-original-title="Restaurar ONU"><i class="fas fa-upload"></i> Exportar ONU a la OLT</a>

                                <a href="#" class="dropdown-item" data-placement="top" data-original-title="Migrar ONU a Otra OLT"><i class="fas fa-exchange-alt"></i> Migrar ONU a otra OLT</a>
                                
                            </div>
                        </li>
                        <!--<li class="nav-item">
                            <a href="#stats" data-toggle="tab" aria-expanded="false" class="nav-link">
                                <i class="fa fa-chart-line d-md-none d-block"></i>
                                <span class="d-none d-md-block"><i class="fa fa-chart-line"></i> Estadísticas</span>
                            </a>
                        </li>-->
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="details">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12 col-md-12 text-center mb-3">
                                            <img src="<?=base_url()?>public/images/ont_image/<?=$onu->imagen?>" style="width: 50%;">
                                        </div>
                                        <div class="col-lg-6 col-sm-12 col-md-12 text-center">
                                            <div class="row">
                                                <div class="col-lg-12 col-sm-12 col-md-12">
                                                    <table class="table table-bordered table-centered mb-3">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th colspan="2">Información</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="">
                                                            <tr>
                                                                <td><strong>OLT</strong></td>
                                                                <td><?=$onu->olt?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Chasis</strong></td>
                                                                <td><?=$onu->chasis?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Slot</strong></td>
                                                                <td><?=$onu->tarjeta?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Puerto</strong></td>
                                                                <td><?=$onu->puerto?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Tráfico Máximo Simétrico</strong></td>
                                                                <td class="font-12" style="max-width: 100px;"> <?=$onu->trafic?> - DESCARGA / <?=$onu->trafic?> - SUBIDA</td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Service Port</strong></td>
                                                                <td><?=$onu->srv_port?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-12 col-md-12 text-center">
                                            <div class="row">
                                                <div class="col-lg-12 col-sm-12 col-md-12">
                                                    <table class="table table-responsive-sm table-bordered table-centered mb-3">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th colspan="2">Detalles</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="">
                                                            <tr>
                                                                <td><strong>Interfaz de la ONU</strong></td>
                                                                <td><?=$onu->chasis?>/<?=$onu->tarjeta?>/<?=$onu->puerto?>:<?=$onu->idonu?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Número de Serie</strong></td>
                                                                <td><?=$onu->sn?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Modelo de ONU</strong></td>
                                                                <td><?=$onu->modelo?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Zona</strong></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Nap</strong></td>
                                                                <td></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Nombre</strong></td>
                                                                <td><?=$onu->nombre?></td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Fecha de Registro</strong></td>
                                                                <td><?=date("d/m/Y H:i",strtotime($onu->fecha))?></td>
                                                            </tr>
                                                        
                                                            <tr>
                                                                <td><strong>SVLAN/CVLAN</strong></td>
                                                                <td>
                                                                    <?=$onu->vlan?> / <?=$onu->vlan?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><strong>Comentarios</strong></td>
                                                                <td><?=$onu->comentarios?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-sm-12 col-md-12 text-center">
                                            <div class="row">
                                                <div class="col-lg-12 col-sm-12 col-md-12">
                                                    <table class="table table-bordered table-centered mb-3">
                                                        
                                                        <tbody class="">
                                                            
                                                            
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
<!--                        <div class="tab-pane" id="traffic">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                    </div>
                                </div>
                            </div>
                        </div>-->
                        <div class="tab-pane" id="settings">
                            <div class="card" style="margin-top: 20px;">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div id="result-config-onu" class="result-details" hidden="">
                                            <pre>                                                <code id="result-config-onu-code" class="routes hljs yaml">
                                                    <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">Optical</span></span></span> <span class="hljs-attr"><span class="hljs-attr"><span class="hljs-attr">status:</span></span></span>
                                                    <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">Vendor</span></span></span> <span class="hljs-attr"><span class="hljs-attr"><span class="hljs-attr">ID:</span></span></span>           <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">ZTEG</span></span></span>
<span class="hljs-attr"><span class="hljs-attr"><span class="hljs-attr">                                                    Version:</span></span></span>             <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">V6.0</span></span></span>
<span class="hljs-attr"><span class="hljs-attr"><span class="hljs-attr">                                                    SN:</span></span></span>                  <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">ZTEGcc2e42ee</span></span></span>
<span class="hljs-attr"><span class="hljs-attr"><span class="hljs-attr">                                                    OperState:</span></span></span>           <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">enable</span></span></span>
<span class="hljs-attr"><span class="hljs-attr"><span class="hljs-attr">                                                    EquipID:</span></span></span>             <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">F623V6.0</span></span></span>
                                                    <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">Signal</span></span></span> <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">level</span></span></span> <span class="hljs-attr"><span class="hljs-attr"><span class="hljs-attr">min:</span></span></span>              <span class="hljs-bullet"><span class="hljs-bullet"><span class="hljs-bullet">-30.0</span></span></span><span class="hljs-string"><span class="hljs-string"><span class="hljs-string">(dBm)</span></span></span>
                                                    <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">Signal</span></span></span> <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">level</span></span></span> <span class="hljs-attr"><span class="hljs-attr"><span class="hljs-attr">max:</span></span></span>              <span class="hljs-bullet"><span class="hljs-bullet"><span class="hljs-bullet">-30.0</span></span></span><span class="hljs-string"><span class="hljs-string"><span class="hljs-string">(dBm)</span></span></span>
                                                    <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">Video</span></span></span> <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">lower</span></span></span> <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">optical</span></span></span> <span class="hljs-attr"><span class="hljs-attr"><span class="hljs-attr">threshold:</span></span></span> <span class="hljs-bullet"><span class="hljs-bullet"><span class="hljs-bullet">-9.5</span></span></span><span class="hljs-string"><span class="hljs-string"><span class="hljs-string">(dBm)</span></span></span>
                                                    <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">Video</span></span></span> <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">upper</span></span></span> <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">optical</span></span></span> <span class="hljs-attr"><span class="hljs-attr"><span class="hljs-attr">threshold:</span></span></span> <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">+2.5(dBm)</span></span></span>
                                                    <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">ONU</span></span></span> <span class="hljs-attr"><span class="hljs-attr"><span class="hljs-attr">interface:</span></span></span>         <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">gpon-onu_1/2/4:2</span></span></span>
<span class="hljs-attr"><span class="hljs-attr"><span class="hljs-attr">                                                      Name:</span></span></span>                <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">onu_zte_luis</span></span></span>
<span class="hljs-attr"><span class="hljs-attr"><span class="hljs-attr">                                                      Type:</span></span></span>                <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">ZTE-F660V5.2</span></span></span>
<span class="hljs-attr"><span class="hljs-attr"><span class="hljs-attr">                                                      State:</span></span></span>               <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">ready</span></span></span>
                                                      <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">Phase</span></span></span> <span class="hljs-attr"><span class="hljs-attr"><span class="hljs-attr">state:</span></span></span>         <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">working</span></span></span>
                                                      <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">Serial</span></span></span> <span class="hljs-attr"><span class="hljs-attr"><span class="hljs-attr">number:</span></span></span>       <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">ZTEGCC2E42EE</span></span></span>
                                                      <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">ONU</span></span></span> <span class="hljs-attr"><span class="hljs-attr"><span class="hljs-attr">Status:</span></span></span>          <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">enable</span></span></span>
                                                      <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">ONU</span></span></span> <span class="hljs-attr"><span class="hljs-attr"><span class="hljs-attr">Distance:</span></span></span>        <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">N/A</span></span></span>
                                                      <span class="hljs-string"><span class="hljs-string"><span class="hljs-string">Online</span></span></span> <span class="hljs-attr"><span class="hljs-attr"><span class="hljs-attr">Duration:</span></span></span>     <span class="hljs-number"><span class="hljs-number"><span class="hljs-number">20</span></span></span><span class="hljs-string"><span class="hljs-string"><span class="hljs-string">h</span></span></span> <span class="hljs-number"><span class="hljs-number"><span class="hljs-number">40</span></span></span><span class="hljs-string"><span class="hljs-string"><span class="hljs-string">m</span></span></span> <span class="hljs-number"><span class="hljs-number"><span class="hljs-number">37</span></span></span><span class="hljs-string"><span class="hljs-string"><span class="hljs-string">s</span></span></span>
                                                </code>
                                            </pre>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="edit">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="stats">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        
                                            <ul class="nav nav-tabs mb-12">
                                                <li class="nav-item">
                                                    <a href="#last-day-stats" data-toggle="tab" aria-expanded="false" class="nav-link  active">
                                                        <i class="fa fa-clock d-md-none d-block"></i>
                                                        <span class="d-none d-md-block"><i class="fa fa-clock"></i> Último Día</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#last-7days-stats" data-toggle="tab" aria-expanded="false" class="nav-link">
                                                        <i class="fa fa-calendar-check d-md-none d-block"></i>
                                                        <span class="d-none d-md-block"><i class="fa fa-calendar-check"></i> Últimos 7 Días</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a href="#last-30days-stats" data-toggle="tab" aria-expanded="false" class="nav-link">
                                                        <i class="fa fa-calendar-plus d-md-none d-block"></i>
                                                        <span class="d-none d-md-block"><i class="fa fa-calendar-plus"></i> Último Mes</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item" id="graphs-real-time">
                                                    <a href="#stats-now" data-toggle="tab" aria-expanded="false" class="nav-link">
                                                        <i class="fa fa-hourglass-start d-md-none d-block"></i>
                                                        <span class="d-none d-md-block"><i class="fa fa-hourglass-start"></i> Tiempo Real</span>
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="custom-control custom-switch col-sm-12 col-xs-12 col-md-4">
                                            <label class="col-sm-4 col-xs-12 col-form-label text-sm-center text-dark">Tema </label>
                                                  <div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-off bootstrap-switch-id-theme"><div class="bootstrap-switch-container"><span class="bootstrap-switch-handle-on bootstrap-switch-primary">Ligth</span><span class="bootstrap-switch-label">&nbsp;</span><span class="bootstrap-switch-handle-off bootstrap-switch-default">Dark</span><input type="checkbox" name="state" class="make-switch" data-off-text="Dark" data-on-text="Ligth" id="theme"></div></div>
                                            </div>

                                            <div class="tab-content col-12">
                                                <div class="tab-pane active" id="last-day-stats">
                                                    <div class="row col-xl-12 col-lg-12">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-xl-6 col-lg-6">
                                                                        <div class="card tilebox-one">
                                                                            <div class="card-body" style="min-height: 100px;">
                                                                                <i class="fa fa-cloud float-right text-info"></i>
                                                                                <h6 class="text-uppercase text-dark mt-0">Consumo</h6>
                                                                                <div class="mb-0 mr-0 ml-0 ">
                                                                                    <h5 class="text-success mt-0"> Download: 2169.41 MB</h5>
                                                                                    <h5 class="text-danger mt-0"> Upload: 64.49 MB</h5>
                                                                                </div>
                                                                            </div> <!-- end card-body-->
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-xl-6 col-lg-6">
                                                                        <div class="card tilebox-one">
                                                                            <div class="card-body" style="min-height: 100px;">
                                                                                <i class="fa fa-tachometer-alt float-right text-info"></i>
                                                                                <h6 class="text-uppercase text-dark mt-0">Velocidad</h6>
                                                                                <div class="mb-0 mr-0 ml-0 technologies-details">
                                                                                    <h5 class="text-success mt-0"> Max Download: 2.449 Mbps</h5>
                                                                                    <h5 class="text-danger mt-0"> Max Upload: 0.063 Mbps</h5>
                                                                                </div>
                                                                            </div> <!-- end card-body-->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <iframe id="frame-last-day" width="100%" src="https://grafana.adminolt.com/d/quGhZZKMz/details-onu?var-slot=0&amp;var-puerto=5&amp;var-id_onu=13&amp;var-uuiddb=21f7803a-d9ed-4623-9476-d306b1856c7f&amp;var-id_olt=223&amp;from=now-24h&amp;to=now&amp;var-intervalo=1h" height="600px" border="0" frameborder="0" scrolling="no" style="overflow:hide;overflow-x:hidden;">
                                                        </iframe>
                                                    </div>
                                                </div>
                                                <div class="tab-pane " id="last-7days-stats">
                                                    <div class="row">
                                                        <iframe id="frame-last-7days" width="100%" src="https://grafana.adminolt.com/d/quGhZZKMz/details-onu?var-slot=0&amp;var-puerto=5&amp;var-id_onu=13&amp;var-uuiddb=21f7803a-d9ed-4623-9476-d306b1856c7f&amp;var-id_olt=223&amp;from=now-7d&amp;to=now&amp;var-intervalo=1d" height="600px" border="0" frameborder="0" scrolling="no" style="overflow-x:hidden;"></iframe>
                                                    </div>
                                                </div>
                                                <div class="tab-pane " id="last-30days-stats">
                                                    <div class="row">
                                                        <iframe id="frame-last-30days" width="100%" src="https://grafana.adminolt.com/d/quGhZZKMz/details-onu?var-slot=0&amp;var-puerto=5&amp;var-id_onu=13&amp;var-uuiddb=21f7803a-d9ed-4623-9476-d306b1856c7f&amp;var-id_olt=223&amp;from=now-30d&amp;to=now&amp;var-intervalo=1d" height="600px" border="0" frameborder="0" scrolling="no" style="overflow: hidden;"></iframe>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="stats-now">
                                                    <div class="row">
                                                        <iframe id="frame-real-time" width="100%" src="https://grafana.adminolt.com/d/quGhZZKMz/details-onu?var-slot=0&amp;var-puerto=5&amp;var-id_onu=13&amp;var-uuiddb=21f7803a-d9ed-4623-9476-d306b1856c7f&amp;var-id_olt=223&amp;from=now-15m&amp;to=now&amp;var-intervalo=10s&amp;refresh=3s" height="600px" border="0" frameborder="0" scrolling="no" style="overflow: hidden;"></iframe>

                                                        <div class="alert alert-info alert-dismissible" role="alert" id="collect-real-time">
                                                            <i class="fas fa-exclamation-triangle mr-2"></i> El monitoreo en <strong>Tiempo Real</strong> carga datos durante 2 minutos, puedes ver nuevos datos si así lo requieres.
                                                            <a class="btn btn-light" onclick="collect_data_graphs()">Iniciar a monitorear nuevamente</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>

            </div>
        </div>


   <div class="modal" id="modal-estado-onu" role="dialog" style="padding-right: 17px;" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">ONU INFO OLT</h5>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
      </div>
<div class="modal-body" style="background: black; color: white">

</div> 
</div>

</div>
</div>     


    <script type="text/javascript">
        $(document).ready(function(){
         
          get_parametros_onu();

        });

     function get_parametros_onu(){

        $('.fa-fw').hide();
          $.ajax({
            url: 'fiber/get_onu_estado?id=<?=$onu->id?>',
            type: 'POST',
            dataType: 'json',
            success: function(data){

            $(".spinner-grow").remove();
            $(".icon-widget").show();

            if(data.estado=="ONLINE"){
                $(".widget-estado").removeClass( "bg-white" ).addClass( "bg-teal" );
                $("#estado").html("ONLINE")
            }
            else{
                $(".widget-estado").removeClass( "bg-white" ).addClass( "bg-red" );
                $("#estado").html("OFFLINE")
            }

            if(data.rx_onu>-26){
                $(".widget-rx-onu").removeClass( "bg-white" ).addClass( "bg-teal" );
                $("#rx-onu").html(data.rx_onu)
            }
           if(data.rx_onu<=-26 && data.rx_onu >=-30){
                $(".widget-rx-onu").removeClass( "bg-white" ).addClass( "bg-orange" );
                $("#rx-onu").html(data.rx_onu)
            }
           if(data.rx_onu<=-31 && data.rx_onu >=-50){
                $(".widget-rx-onu").removeClass( "bg-white" ).addClass( "bg-danger" );
                $("#rx-onu").html(data.rx_onu)
            }
            else{
                $(".widget-rx-onu").removeClass( "bg-white" ).addClass( "bg-red" );
                $("#rx-onu").html(data.rx_onu)
            }

            if(data.tx_onu>0 && data.tx_onu <=3){
                $(".widget-tx-onu").removeClass( "bg-white" ).addClass( "bg-teal" );
                $("#tx-onu").html(data.tx_onu)
            }
           if(data.tx_onu>3 && data.tx_onu <=4){
                $(".widget-tx-onu").removeClass( "bg-white" ).addClass( "bg-orange" );
                $("#tx-onu").html(data.tx_onu)
            }
           if(data.tx_onu >4){
                $(".widget-tx-onu").removeClass( "bg-white" ).addClass( "bg-danger" );
                $("#tx-onu").html(data.tx_onu)
            }
            else{
                $(".widget-tx-onu").removeClass( "bg-white" ).addClass( "bg-red" );
                $("#tx-onu").html(data.tx_onu)
            }

            if(data.rx_olt>-26){
                $(".widget-rx-olt").removeClass( "bg-white" ).addClass( "bg-teal" );
                $("#rx-olt").html(data.rx_olt)
            }
           if(data.rx_olt<=-26 && data.rx_olt >=-30){
                $(".widget-rx-olt").removeClass( "bg-white" ).addClass( "bg-orange" );
                $("#rx-olt").html(data.rx_olt)
            }
           if(data.rx_olt<=-31 && data.rx_olt >=-50){
                $(".widget-rx-olt").removeClass( "bg-white" ).addClass( "bg-danger" );
                $("#rx-olt").html(data.rx_olt)
            }
            else{
                $(".widget-rx-olt").removeClass( "bg-white" ).addClass( "bg-red" );
                $("#rx-olt").html(data.rx_olt)
            }

            },
            error: function(e){
              console.log(e.responseText);
            }
          });

     }   

     function reboot_onu(id,n){
       var alsup = $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Esta seguro que desea reiniciar la ONT del cliente <br><b class="text-warning">' + n + '</b><br><br><br>',
      title: '<i class="far fa-question-circle fa-lg icodialog"></i> REINICIAR',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt"></i> Si, reiniciar',
          action: function() {
            this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
            this.buttons.Eliminar.disable();
            $('.jconfirm-closeIcon').remove();
            $.post("fiber/reboot_onu", {id: id,})
              .done(function(data) {
                data = JSON.parse(data);
                alsup.close();
                if (data.estado == "error") {
                  alerta('error', data.mensaje);
                } 
                else if (data.estado == "success") {
                    alerta('exito', data.mensaje);
                    $(".widget").removeClass( "bg-teal" );
                    $(".widget").removeClass( "bg-red" );
                    $(".widget").removeClass( "bg-orange" );
                    $(".widget").addClass( "bg-white" );
                    $(".icon-widget").hide();
                    $( ".stats-icon" ).append('<div class="spinner-grow">');
                    setTimeout(function(){ get_parametros_onu(); }, 5000);
                    
                  } else {
                    alerta('warning', data.mensaje);
                  }    
              });
            return false;

          }
        }
      }
    });
}

function info_onu(id,n){

    $('#modal-estado-onu .modal-body').html('<div class="ubloader" style="padding:30px;"><i class="fa fa-spinner fa-spin fa-lg fa-fw margin-bottom"></i><span>Cargando...</span></div>');
    $('#modal-estado-onu').modal()

     $.post("fiber/info_onu", {id: id,})
              .done(function(data) {
                data = JSON.parse(data);
                if (data.estado == "error") {
                  alerta('error', data.mensaje);
                } 
                else if (data.estado == "success") {
                    $('#modal-estado-onu .modal-body').html(data.response);
                  } else {
                    alerta('warning', data.mensaje);
                  }    
              });
       
}

    </script>
 

   

   



  

   

    

   
