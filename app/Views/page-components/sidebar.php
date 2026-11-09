<!-- begin #sidebar -->
<div id="sidebar" class="sidebar d-print-none">
    <!-- begin sidebar scrollbar -->
    <div data-scrollbar="true" data-height="100%">
        <!-- begin sidebar user -->
        <ul class="nav active">

            <li class="nav-profile">
                <div class="cover with-shadow imgsidebarlogin" style="position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background:url(<?= base_url() ?>public/images/<?= $img_slider; ?>) no-repeat !important;
    background-size: cover;"></div>
                <div class="image">
                    <a href="javascript:;">
                        <?= $_SESSION['avatar']; ?>

                    </a>
                </div>
                <div class="info">
                    <?= $_SESSION['rol'] ?><small><?= $_SESSION['nombre'] ?></small>
                </div>
            </li>
        </ul>
        <!-- end sidebar user -->
        <a class="ajaxlink" href="" data-toggle="ajax" style="display: none"></a>
        <!-- begin sidebar nav -->
        <?= $menu ?>
        <!-- end sidebar nav -->
    </div>
    <!-- end sidebar scrollbar -->
</div>
<div class="sidebar-bg d-print-none"></div>
<!-- end #sidebar -->
