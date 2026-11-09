<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <title id="page-title">TOOLS</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
    <meta content="" name="description" />
    <!-- <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> -->
    <meta content="" name="author" />
    <link rel="shortcut icon" type="image/ico" href="<?= base_url() ?>public/favicon.png">
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="mask-icon" href="safari-pinned-tab.svg" color="#5bbad5">


    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="<?= base_url() ?>public/css/default/app.min.css?v=6.22" rel="stylesheet" />
    <link href="<?= base_url() ?>public/css/default/theme/blue.min.css" rel="stylesheet" id="theme" />

    <link href="<?= base_url() ?>public/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="<?= base_url() ?>public/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
    <link href="<?= base_url() ?>public/plugins/switchery/switchery.min.css" rel="stylesheet" />
    <link href="<?= base_url() ?>public/plugins/morris/morris.css" rel="stylesheet" />
    <link href="<?= base_url() ?>public/css/jquery-confirm.min.css" rel="stylesheet" />
    <link href="<?= base_url() ?>public/plugins/jquery-tag-it/css/jquery.tagit.css" rel="stylesheet" />
    <link href="<?= base_url() ?>public/plugins/bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="<?= base_url() ?>public/plugins/bootstrap-wysihtml5/dist/bootstrap3-wysihtml5.min.css" rel="stylesheet" />

    <link href="<?= base_url() ?>public/plugins/jquery-smart-wizard/src/css/smart_wizard.css" rel="stylesheet" />
    <link href="<?= base_url() ?>public/plugins/parsley/src/parsley.css" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>public/css/datatables.min.css" />
    <link href="<?= base_url() ?>public/css/ekko-lightbox.css" rel="stylesheet" />
    <link href="<?= base_url() ?>public/plugins/intro-js/minified/introjs.min.css" rel="stylesheet" />

    <link href="<?= base_url() ?>public/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css" rel="stylesheet" />
    <link href="<?= base_url() ?>public/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.css" rel="stylesheet" />
    <link href="<?= base_url(); ?>public/plugins/iCheck/all.css" rel="stylesheet">

    <link href="<?= base_url() ?>public/css/mikrowisp.css?v=6.22" rel="stylesheet" />
    <link href="<?= base_url() ?>public/css/sweetalert.css" rel="stylesheet" />
    <link href="<?= base_url() ?>public/css/vis.min.css" rel="stylesheet" />
    <link href="<?= base_url() ?>public/css/extra.css?t=1578417565" rel="stylesheet" />
    <link href="<?= base_url() ?>public/css/magnific-popup.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">

    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <!-- ================== BEGIN BASE JS ================== -->
    <script src="<?= base_url() ?>public/plugins/pace/pace.min.js"></script>
    <script src="<?= base_url() ?>public/socket.io/socket.io.js"></script>
    <script>
        // Cliente Socket.IO (actualización en tiempo real). Varias pantallas usan socket.on/emit;
        // sin servidor configurado (socket.url en .env) se usa un objeto vacío para que no fallen.
        var socket = (function (url) {
            if (url && typeof io === 'function') {
                return io(url, { transports: ['websocket', 'polling'] });
            }
            return { on: function () { return this; }, off: function () { return this; }, emit: function () { return this; }, _callbacks: {} };
        })(<?= json_encode((string) env('socket.url', '')) ?>);
    </script>

    <link href="<?= base_url() ?>public/css/spectrum.css" rel="stylesheet" />
    <!-- ================== END BASE JS ================== -->

</head>

<body>
    <!-- begin #page-loader -->
    <div id="page-loader" class="fade show"><span class="spinner"></span></div>

    <!-- end #page-loader -->

    <!-- begin #page-container -->
    <div id="page-container" class="page-container fade page-sidebar-fixed page-header-fixed" style="height: 100%">
        <?= view("page-components/header") ?>

        <?= view("page-components/sidebar") ?>

        <!-- begin #content -->
        <div id="content" class="content" style="height: 100%">

        </div>
        <!-- end #content -->
        <?= view("page-components/panel") ?>
        <!-- begin scroll to top btn -->
        <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fade" data-click="scroll-top"><i class="fas fa-angle-up"></i></a>
        <!-- end scroll to top btn -->
    </div>
    <!-- end page container -->

    <script src="<?= base_url() ?>public/plugins/jquery/jquery-3.2.1.min.js"></script>
    <script src="<?= base_url() ?>public/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="https://getbootstrap.com/docs/4.0/assets/js/vendor/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>

    <script src="<?= base_url() ?>public/plugins/bootstrap/js/bootstrap.bundle.min.js?v=6.22"></script>
    <script src="<?= base_url() ?>public/plugins/js-cookie/js.cookie.js"></script>
    <script src="<?= base_url() ?>public/plugins/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="<?= base_url() ?>public/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>

    <script src="<?= base_url() ?>public/js/app.js?v=6.22"></script>

    <!-- ================== BEGIN BASE JS ================== -->
    <script src="<?= base_url() ?>public/js/ekko-lightbox.min.js"></script>
    <script src="<?= base_url() ?>public/plugins/moment/moment.min.js"></script>

    <script>
        moment.locale('es');
    </script>
    <!--[if lt IE 9]>
        <script src="crossbrowserjs/html5shiv.js"></script>
        <script src="crossbrowserjs/respond.min.js"></script>
        <script src="crossbrowserjs/excanvas.min.js"></script>
    <![endif]-->
    <!-- ================== END BASE JS ================== -->

    <script src="<?= base_url() ?>public/plugins/bootstrap-show-password/bootstrap-show-password.min.js"></script>
    <script src="<?= base_url() ?>public/plugins/gritter/js/jquery.gritter.min.js"></script>
    <script src="<?= base_url() ?>public/plugins/select2/dist/js/select2.min.js"></script>
    <script src="<?= base_url() ?>public/plugins/switchery/switchery.min.js"></script>
    <script src="<?= base_url() ?>public/plugins/morris/morris.min.js"></script>
    <script src="<?= base_url() ?>public/plugins/morris/raphael.min.js"></script>

    <script type="text/javascript" src="<?= base_url() ?>public/plugins/flot/jquery.flot.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/plugins/flot/jquery.flot.time.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/plugins/flot/jquery.flot.symbol.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/js/jquery-confirm.min.js"></script>

    <script type="text/javascript" src="<?= base_url() ?>public/js/html2canvas.min.js?v=17"></script>




    <script type="text/javascript" src="<?= base_url() ?>public/plugins/jquery-tag-it/js/tag-it.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/plugins/ckeditor/ckeditor.js" charset="utf-8"></script>

    <script type="text/javascript" src="<?= base_url() ?>public/plugins/intro-js/minified/intro.min.js"></script>


    <script type="text/javascript" src="<?= base_url() ?>public/js/pdfmake.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/js/vfs_fonts.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/js/datatables.min.js?v=6.33"></script>

    <script type="text/javascript" src="<?= base_url() ?>public/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

    <script type="text/javascript" src="<?= base_url() ?>public/plugins/bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/plugins/bootstrap-wysihtml5/dist/bootstrap3-wysihtml5.all.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/plugins/bootstrap-wysihtml5/dist/locales/bootstrap-wysihtml5.es-ES.js"></script>
    <script type="text/javascript" src="<?= base_url(); ?>public/plugins/iCheck/icheck.min.js"></script>

    <script type="text/javascript" src="<?= base_url() ?>public/plugins/parsley/dist/parsley.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/plugins/jquery-smart-wizard/src/js/jquery.smartWizard.js"></script>

    <script type="text/javascript" src="<?= base_url() ?>public/js/mikrowisp.js?v=6.22"></script>
    <script type="text/javascript" src="<?= base_url() ?>public/js/sweetalert.min.js"></script>


    <!-- ================== END BASE JS ================== -->

    <!-- ================== BEGIN PAGE LEVEL JS ================== -->
    <script src="<?= base_url() ?>public/js/vis.min.js"></script>
    <script src="<?= base_url() ?>public/js/excelexportjs.js"></script>
    <script src="<?= base_url() ?>public/js/initial.min.js"></script>
    <script src="<?= base_url() ?>public/js/confetti.min.js"></script>
    <script src="<?= base_url() ?>public/js/theme/default.min.js"></script>
    <script src="<?= base_url() ?>public/js/jquery.magnific-popup.min.js"></script>
    <script src="<?= base_url() ?>public/js/extra.js?t=1578417565"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <script src="<?= base_url() ?>public/js/spectrum.js"></script>

    <!-- ================== END PAGE LEVEL JS https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places& ================== -->
    <script async="" defer="" src="https://maps.googleapis.com/maps/api/js?libraries=places,drawing,geometry&amp;key=<?= $google_api; ?>">

    </script>

    
    <script>
        var base_url = "<?= base_url() ?>";

        App.settings({
            ajaxMode: true,
            ajaxDefaultUrl: '#index/home',
            ajaxType: 'GET',
            ajaxDataType: 'html'
        });

        function startTimer() {
            var duration = 0;
            var display = document.querySelector('#counterlogout');
            if (duration == 0) {
                $('#counterlogout').html('');
                return false;
            }
            duration = 60 * duration;
            var start = Date.now(),
                diff,
                minutes,
                seconds;
            if (timeoutlogin) {
                clearInterval(timeoutlogin);
            }

            function timer() {
                // get the number of seconds that have elapsed since 
                // startTimer() was called
                diff = duration - (((Date.now() - start) / 1000) | 0);

                // does the same job as parseInt truncates the float
                minutes = (diff / 60) | 0;
                seconds = (diff % 60) | 0;

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = 'Desconectar ' + minutes + ":" + seconds;

                if (diff <= 0) {
                    clearInterval(timeoutlogin);
                    window.location = "ajax/logout";
                }
            };
            // we don't want to wait a full second before the timer starts
            timer();
            timeoutlogin = setInterval(timer, 1000);
        }


        $(function() {

            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                $("#tryhtml2canvas").remove();
            }


            $('*[data-toggle="ajax"]').on('click', function() {
                if ($("#searchcliente").autocomplete("instance")) {
                    $("#searchcliente").autocomplete("destroy");
                    $("#searchcliente").removeData('autocomplete');
                }
                //$.ajaxQ.abortAll();
            });



            $('.avataradmin').initial({
                height: 34,
                width: 34,
                charCount: 2,
                fontSize: 15,
                fontWeight: 500
            });

            $(document).on('keyup', '#searchcliente', function(e) {
                $("#searchcliente").autocomplete({
                    source: base_url + 'clientes/searchcliente?action=listauser',
                    minLength: 3,
                    open: function(event, ui) {
                        $('img[data-name]').initial({
                            height: 64,
                            width: 64,
                            charCount: 2,
                            fontSize: 26,
                            fontWeight: 600
                        });
                    },
                    select: function(event, ui) {
                        $("#searchcliente").autocomplete("destroy");
                        $("#searchcliente").removeData('autocomplete');
                        $("#searchcliente").val("");
                        loadurl('#clientes/detalleuser?id=' + ui.item.id + '&token=' + ui.item.token);
                    }
                }).data("ui-autocomplete")._renderItem = function(ul, item) {
                    return $("<li></li>")
                        .data("item.autocomplete", item)
                        .append(item.socio)
                        .appendTo(ul);
                };
            });





        });
    </script>

    <div class="modal fade" id="modal-ping">
        <div class="modal-dialog modal-lg modal-dialog-centered" style="max-width:640px !important">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Gráfico Ping</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fas fa-times" aria-hidden="true"></i></button>
                </div>
                <div class="modal-body">
                    <div class="text-center" style="margin-bottom:10px">
                        <select id="ping_grafico_tipo" style="width:200px !important" onChange="getgrafico()">
                            <option value="hora" selected="selected">Gráfico última hora</option>
                            <option value="dia">Gráfico del día</option>
                            <option value="semana">Gráfico de la semana</option>
                            <option value="mes">Gráfico del mes</option>
                            <option value="año">Gráfico del Año</option>
                        </select>
                    </div>
                    <input id="id_ping_chart" type="hidden" value="0">
                    <img src="" id="ping_grafico" style="width:100%;max-width:640px !important">
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="newplantilla" style="z-index: 1069;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Nueva Plantilla</h4>
                </div>
                <div class="modal-body" style="width: 100%">

                    <div class="form-group">
                        <label class="col-md-4 control-label">Ingrese el nombre plantilla</label>
                        <div class="col-md-8">
                            <input type="text" class="addplantilla form-control">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <a href="javascript:;" class="btn-azul" onClick="$('#newplantilla').modal('hide')">Cerrar</a>
                    <a href="javascript:;" class="btn-azul" onClick="newplantilla()">Guardar</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modal-loader">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Procesando</h4>
                </div>
                <div class="modal-body row text-center">
                    <i class="fas fa-spinner fa-spin fa-2x"></i> Cargando...
                </div>
            </div>
        </div>
    </div>
    <div id="tmp"></div>
    <div id="tmp2"></div>
    <div class="css-ghzv10 d-print-none" data-html2canvas-ignore="true">
        <!--<div id="tryhtml2canvas" class="css-1xamfmm" data-toggle="tooltip" title="Capturar pantalla"><i class="fas fa-camera"></i></div>-->
    </div>
    <div id="screenshot"></div>
    <a class="ajaxlink" href="" data-toggle="ajax"></a>

    <a href="javascript:;" class="btnbackmovil btn btn-icon btn-circle btn-success" style="
    left: 8px;
    bottom: 7px;
    width: 45px;
display: none !important;
    line-height: 45px;
    height: 45px; display: none !important
                                                                                       
" onclick="window.history.back()"><i class="fas fa-hand-point-left fa-lg"></i>
    </a>
</body>

</html>