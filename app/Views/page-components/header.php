<!-- begin #header -->
<div id="header" class="d-print-none header navbar-inverse">

    <!-- begin mobile sidebar expand / collapse button -->
    <div class="navbar-header">
        <a href="<?= base_url() ?>" class="navbar-brand"><img src="<?= logo_sistema_url() ?>" class="logo-admin" style="height:40px; width:auto"></a>
        <button type="button" class="navbar-toggle" data-click="sidebar-toggled">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <!-- end mobile sidebar expand / collapse button -->

    <!-- begin header navigation right -->
    <ul class="navbar-nav navbar-right">
        <li data-html2canvas-ignore="true" class="searchbars">
            <form class="navbar-form">
                <div class="form-group">
                    <input type="text" id="searchcliente" class="form-control" autofocus placeholder="Buscar cliente..." autocomplete="off">
                    <button type="button" class="btn btn-search"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </li>


        <!--<li><a href="#ajax/telegram" data-toggle="tooltip" title="Mensajes telegram" style="padding: 15px 8px;">
<i class="fab fa-telegram-plane" style="font-size: 20px;"></i><span class="lbl-notytelegram"></span>
</a></li>-->
        <li><a href="#finanzas/registrar_pago" title="Registrar pago" data-toggle="tooltip" style="padding: 15px 8px;"><i class="fas fa-dollar-sign" style="font-size: 20px;"></i>
            </a></li>
        <li class="dropdown navbar-user" id="counter_user_online_page_parpadeo">
            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" style="padding: 15px 10px">
                <i id="counter_user_online_page" id="counter_user_online_page" class="fas fa-user-friends"></i>
            </a>
            <div id="online_page" class="dropdown-menu dropdown-menu-right">

            </div>
        </li>

        <!-- <li class="alertas-notificaciones">
<a href="javascript:;" data-click="noty-panel-expand" class="tlp f-s-14" onClick="loadnotyficaciones()" style="padding: 15px 8px;" title="Notificaciones"><i class="far fa-bell" style="font-size: 18px;"></i> <span class="lbl-notyficaction"></span>
</a>    
</li> -->
        <!--<li class="dropdown alertas-soporte">
<a href="javascript:;" data-toggle="dropdown" class="tlp dropdown-toggle f-s-14" onClick="loadsoporte()" style="padding: 15px 8px;" title="Notificaciones soporte"><i class="fas fa-user-tag"></i> <span class="lbl-soporte"></span>
</a>
<ul class="dropdown-menu media-list dropdown-menu-right loadsoporte" style="max-height: 405px; overflow-y: scroll;">
<li class="media">
<a style="cursor:pointer">
<div class="media-body" style="max-width: 270px;">
<h6 class="media-heading"><span style="white-space: normal;min-width: 270px;display: block;">Sin notificaciones</span></h6>
</div>
</a>
</li>
</ul>
</li> -->





        <li class="dropdown navbar-user">
            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" style="padding: 15px 10px">

                <?= $_SESSION['avatar']; ?>

                <span class="d-none d-md-inline"><?= $_SESSION['nombre'] ?></span><b class="caret"></b>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="#sistema/miperfil" data-toggle="ajax"  class="dropdown-item"><i style="font-size:14px;" class="far fa-user"></i> Mi cuenta</a>
                <a class="dropdown-item" href="javascript:void(0)" onclick="getmodal('clientes/mihistorial','Mi historial','lg');"><i style="font-size:14px;" style="font-size:13px;" class="fas fa-history"></i> Mi
                    historial</a>
                <a class="dropdown-item" href="javascript:void(0)" onclick="getmodal('sistema/modal_cambiar_mi_contrasenia','Cambio Contraseña','sm');"><i class="fas fa-key"></i> Cambiar contraseña</a>
                <a href="<?= base_url() ?>seguridad/logout" class="dropdown-item"><b><i class="fas fa-power-off fa fa-lg"></i> Cerrar sesión</b></a>
                <a class="dropdown-item" href="javascript:void(0)"><small id="counterlogout"></small></a>
            </div>
        </li>
    </ul>
    <!-- end header navigation right -->
</div>
<!-- end #header -->