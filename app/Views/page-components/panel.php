<div class="noty-panel theme-panel-lg d-print-none"> 
    <div class="theme-panel-content">
         <div style="font-size: 15px;font-weight: 500;line-height: 13px;margin: 0px 10px 16px;">Notificaciones
             <button type="button" class="btn btn-sm btn-default" onclick="removealert('0');" style="font-size: 10px;padding: 3px 6px;" data-click="noty-panel-expand"><i class="far fa-trash-alt"></i> Eliminar todo</button>
             <div class="pull-right"><a href="javascript:;" data-click="noty-panel-expand"><i class="fas fa-times"></i></a></div>
         </div>
         <div class="bodypanelboty"><a data-click="noty-panel-expand" href="javascript:;" onclick="removealert('6129')">
                 <div class="notification_container">
                     <div class="notification_title"><i class="fas fa-asterisk media-object bg-danger" style="padding: 3px;border-radius: 3px;color: #fff;"></i> Actualizaci&oacute;n del sistema</div>
                     <div class="notification_entry">
                         Dia: 26/08/2020 00:00:00 Horas
                     </div>
                 </div>
             </a></div>
     </div>
 </div>
                <!-- begin theme-panel -->
        <div class="theme-panel theme-panel-lg d-print-none">
            <a href="javascript:;" data-click="theme-panel-expand" class="theme-collapse-btn"><i class="fa fa-cog"></i></a>
            <div class="theme-panel-content">
                <h5>Diseño y Colores</h5>
                <ul class="theme-list clearfix">
                    <li><a href="javascript:;" class="bg-red" data-theme="red" data-theme-file="<?=base_url()?>/public/css/default/theme/red.min.css" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Rojo">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-pink" data-theme="pink" data-theme-file="<?=base_url()?>/public/css/default/theme/pink.min.css" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Rosado">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-orange" data-theme="orange" data-theme-file="<?=base_url()?>/public/css/default/theme/orange.min.css" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Naranja">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-yellow" data-theme="yellow" data-theme-file="<?=base_url()?>/public/css/default/theme/yellow.min.css" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Amarrillo">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-lime" data-theme="lime" data-theme-file="<?=base_url()?>/public/css/default/theme/lime.min.css" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Limón">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-green" data-theme="green" data-theme-file="<?=base_url()?>/public/css/default/theme/green.min.css" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Verde">&nbsp;</a></li>
                    <li class="active"><a href="javascript:;" class="bg-teal" data-theme="default" data-theme-file="" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Defecto">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-aqua" data-theme="aqua" data-theme-file="<?=base_url()?>/public/css/default/theme/aqua.min.css" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Agua">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-blue" data-theme="blue" data-theme-file="<?=base_url()?>/public/css/default/theme/blue.min.css" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Azul">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-purple" data-theme="purple" data-theme-file="<?=base_url()?>/public/css/default/theme/purple.min.css" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Púrpura">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-indigo" data-theme="indigo" data-theme-file="<?=base_url()?>/public/css/default/theme/indigo.min.css" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Indigo">&nbsp;</a></li>
                    <li><a href="javascript:;" class="bg-black" data-theme="black" data-theme-file="<?=base_url()?>/public/css/default/theme/black.min.css" data-click="theme-selector" data-toggle="tooltip" data-trigger="hover" data-container="body" data-title="Negro">&nbsp;</a></li>
                </ul>
                <div class="divider"></div>
                <div class="row m-t-10">
                    <div class="col-8 control-label text-inverse f-w-600">Barra fijo</div>
                    <div class="col-4 d-flex">
                        <div class="custom-control custom-switch ml-auto">
                            <input type="checkbox" class="custom-control-input" name="header-fixed" id="headerFixed" value="1" checked />
                            <label class="custom-control-label" for="headerFixed">&nbsp;</label>
                        </div>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-8 control-label text-inverse f-w-600">Barra Oscuro</div>
                    <div class="col-4 d-flex">
                        <div class="custom-control custom-switch ml-auto">
                            <input type="checkbox" class="custom-control-input" name="header-inverse" id="headerInverse" value="1" />
                            <label class="custom-control-label" for="headerInverse">&nbsp;</label>
                        </div>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-8 control-label text-inverse f-w-600">Menú fijo</div>
                    <div class="col-4 d-flex">
                        <div class="custom-control custom-switch ml-auto">
                            <input type="checkbox" class="custom-control-input" name="sidebar-fixed" id="sidebarFixed" value="1" checked />
                            <label class="custom-control-label" for="sidebarFixed">&nbsp;</label>
                        </div>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-8 control-label text-inverse f-w-600">Menú grid</div>
                    <div class="col-4 d-flex">
                        <div class="custom-control custom-switch ml-auto">
                            <input type="checkbox" class="custom-control-input" name="sidebar-grid" id="sidebarGrid" value="1" />
                            <label class="custom-control-label" for="sidebarGrid">&nbsp;</label>
                        </div>
                    </div>
                </div>
                <div class="row m-t-10">
                    <div class="col-md-8 control-label text-inverse f-w-600">Menú degrado</div>
                    <div class="col-md-4 d-flex">
                        <div class="custom-control custom-switch ml-auto">
                            <input type="checkbox" class="custom-control-input" name="sidebar-gradient" id="sidebarGradient" value="1" />
                            <label class="custom-control-label" for="sidebarGradient">&nbsp;</label>
                        </div>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="row m-t-10 d-none">
                    <div class="col-md-12">
                        <a href="javascript:;" class="btn btn-default btn-block btn-rounded" data-click="reset-local-storage"><b>Reiniciar diseño</b></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- end theme-panel -->