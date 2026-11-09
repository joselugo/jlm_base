<ol class="breadcrumb pull-right">
  <li class="breadcrumb-item"><a href="#index/home">Inicio</a></li>
  <li class="breadcrumb-item"><a href="#clientes">Lista clientes (Activos)</a></li>
  <li class="breadcrumb-item active">Editar cliente</li>
</ol>

<!-- begin page-header -->
<h2 class="page-header"><?= $avatar ?> <span class="newname"><?= $nombre ?></span> <small>(#<?= $id ?>)</small></h2>
<!-- end page-header -->

<!-- begin row -->
<div class="row" data-sortable="false">

  <div class="col-sm-12" data-sortable="false">
    <div class="panel panel-inverse panel-with-tabs">
      <!-- begin panel-heading -->
      <div class="panel-heading p-0">

        <!-- begin nav-tabs -->
        <div class="tab-overflow nav-ajax overflow-right" style="width: 100%">
          <ul class="nav nav-tabs nav-tabs-inverse">
            <li class="nav-item prev-button" style=""><a href="javascript:;" data-click="prev-tab" class="nav-link text-success"><i class="fa fa-arrow-left"></i></a></li>

            <li class="nav-item"><a href="#nav-tab-1" data-toggle="tab" data-url="" class="nav-link active"><i class="fa fa-info-circle"></i> Resumen</a></li>
            <li class="nav-item"><a href="#nav-tab-2" data-toggle="tab" data-url="clientes/servicios_cliente?action=data_servicio&amp;id=<?= $id ?>&amp;token=<?= $_SESSION['token8291'] ?>" class="nav-link"><i class="fa fa-shopping-cart"></i> Servicios</a></li>
            <li class="nav-item"><a href="#nav-tab-7" data-toggle="tab" data-url="clientes_facturacion/home?id=<?= $id ?>" class="nav-link"><i class="fa fa-hand-holding-usd"></i> Facturacion</a></li>
            <li class="nav-item"><a href="#nav-tab-8" data-toggle="tab" data-url="clientes/nav_email_sms?id=<?= $id ?>" class="nav-link"><i class="far fa-envelope"></i> Email & SMS</a></li>
           <!-- <li class="nav-item"><a href="#nav-tab-9" data-toggle="tab" data-url="clientes/documentos_cliente?action=data_log&amp;id=<?= $id ?>" class="nav-link"><i class="fa fa-file-pdf"></i> Documentos & notas</a></li>
            <li class="nav-item"><a href="#nav-tab-6" data-toggle="tab" data-url="clientes/ticket_cliente?id=<?= $id ?>" class="nav-link"><i class="fa fa-life-ring"></i> Tickets</a></li>
            <li class="nav-item"><a href="#nav-tab-4" data-toggle="tab" data-url="estadisticas/home?idcliente=<?= $id ?>" class="nav-link"><i class="far fa-chart-bar"></i> Estadisticas</a></li>-->
            <li class="nav-item"><a href="#nav-tab-3" data-toggle="tab" data-url="clientes/log_cliente?action=data_log&amp;id=<?= $id ?>" class="nav-link"><i class="fa fa-cogs"></i> Log</a></li>

           <!-- <li class="nav-item"><a data-pos="bottom" data-ajaxpopup="<?= base_url() ?>clientes/herramientas?action=tool&amp;id=<?= $id ?>" class="nav-link" style="cursor: pointer"><i class="fas fa-wrench fa-lg"></i></a></li>-->

            <style type="text/css">
              .popover {
                z-index: 1021 !important;
              }
            </style>




            <li class="nav-item next-button" style=""><a href="javascript:;" data-click="next-tab" class="nav-link text-success"><i class="fas fa-arrow-right"></i></a></li>
          </ul>
        </div>
        <!-- end nav-tabs -->

        <div class="panel-heading-btn mr-2 ml-2 d-flex">
          <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-circle btn-success"><i class="fa fa-angle-left"></i></a>
          <a href="javascript:void(0)" class="btn btn-sm btn-icon btn-circle btn-success"><i class="fa fa-angle-right"></i></a>
        </div>

      </div>
      <!-- end panel-heading -->

      <!-- begin tab-content -->
      <div class="panel-body tab-content">
        <!-- begin tab-pane -->
        <div class="tab-pane active show" id="nav-tab-1">
          <script>
           
            $(function() {
              $('img[data-name]').initial({
                height: 64,
                width: 64,
                charCount: 2,
                fontSize: 28,
                fontWeight: 600
              });

              if ($("#searchcliente").autocomplete("instance")) {
                $("#searchcliente").autocomplete("destroy");
                $("#searchcliente").removeData('autocomplete');
              }

              $('#saveresumen').ajaxForm({
                success: function(data) {
                  $('#gritter-notice-wrapper').remove();
                  if (data == 2) {
                    alerta('warning', "Sin cambios para guardar");
                    $('.btsavedata').button('reset');
                  }
                  if (data == 3) {
                    alerta('error', "Ha ocurrido un error");
                    $('.btsavedata').button('reset');
                  } else {
                    alerta('exito', "Datos guardados correctamente");
                    $('.newname').text(data);
                    $('.btsavedata').button('reset');
                  }
                },
                beforeSubmit: function() {
                  $('.btsavedata').button('loading');
                },
                error: function(response) {
                  $('#gritter-notice-wrapper').remove();
                  alerta('error500', response);
                  $('.btsavedata').button('reset');
                }
              })




            })

            $(".Select2").select2({
              placeholder: "Seleccionar..."
            });

            $(".sl2").select2({
              placeholder: "Seleccionar..."
            });
          </script>


          <form id="saveresumen" method="POST" class="savedata" action="clientes/save_edit_cliente?idcliente=<?= $id ?>">

            <div class="row">
              <div class="col-md-7" style="border-right: 1px solid #e2e7ec !important;">
                <legend class="m-b-15"><i class="fas fa-angle-double-right"></i> Datos del cliente</legend>

                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-right">Estado</label>
                  <div class="col-md-8">
                    <div class="form-control dv-state-user" style="border: none;">
                      <?= $estatus ?>
                    </div>
                    <?php if ($estado == "RETIRADO") : ?>
                      <div class="m-t-4 text-warning">Fecha de retiro <?= $fecha_retirado; ?></div>
                      <div class="table-responsive">
                        <button type="button" class="btn btn-sm btn-primary" onclick="$('.servretirados').slideToggle()">Ver servicios retirados</button>
                        <table class="table table-bordered m-t-10 servretirados" style="display: none;">
                          <thead>
                            <tr>
                              <th nowrap="">IP</th>
                              <th nowrap="">MAC</th>
                              <th nowrap="">ID servicio</th>
                              <th nowrap="">Contraseña</th>
                              <th nowrap="">Direccion</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($data_retirado as $key => $datos) : ?>
                              <tr>
                                <td><?= $datos->ip; ?></td>
                                <td><?= $datos->mac; ?></td>
                                <td><?= $datos->id; ?></td>
                                <td><?= $datos->pass_ubnt; ?></td>
                                <td><?= $datos->direccion; ?></td>
                              </tr>
                            <?php endforeach; ?>
                          </tbody>
                        </table>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>

             

                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-right">ID</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" name="cliente[id]" title="Ingrese un número válido" value="<?= $id ?>" pattern="\d+" required="" style="max-width:160px" maxlength="6" readonly="">
                    <input type="hidden" name="id2" value="<?= $id ?>">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-right">Contraseña
                    <button type="button" class="btn btn-icono btn-xs btn-map" data-toggle="tooltip" data-placement="top" data-original-title="Generar contraseña aleatoria" onclick="passwordaleatorio('cliente\[codigo\]')" autocomplete="postal-code"><i class="fa fa-lock"></i></button>
                  </label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" name="cliente[codigo]" value="<?= $contrasenia_portal ?>" required="" style="max-width:160px" autocomplete="postal-code">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-right">Nº Identificación</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" name="cliente[cedula]" value="<?= $cedula ?>" style="max-width:160px;float: left;">


                    <br>
                    <small style="width: 100%;float: left;">CEDULA,DNI,RUC,CUIT,NIT,SAT,RUT,RTN,ETC.</small>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-4 col-form-label text-right text-right">Cliente</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" name="cliente[nombre]" value="<?= $nombre ?>" required="">
                    <input type="hidden" name="oldcliente" value="<?= $nombre ?>">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-right">Dirección Principal</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" name="cliente[direccion_principal]" value="<?= $domicilio_completo ?>" autocomplete="address-line1">
                  </div>
                </div>

            

            

                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-right">Teléfono Movil</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" name="cliente[movil]" value="<?= $movil ?>" autocomplete="tel" ,="">
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-md-4 col-form-label text-right">E-mail</label>
                  <div class="col-md-8">
                    <input type="text" class="form-control" name="cliente[correo]" value="<?= $email ?>" autocomplete="email">
                  </div>
                </div>



                
                <?php if ($tipoacceso > 1) {
                  $button = ' <div class="form-group row justify-content-center">
                  <button type="submit" class="btn-azul p-10 btsavedata" data-loading-text="<i class=\'fas fa-spinner fa-spin\'></i> Guardando cambios..."><i class="far fa-save fa-lg"></i> Guardar datos</button>
                </div>';
                } else {
                  $button = '';
                } ?>
                <?= $button ?>


              </div>

              <div class="col-md-5">

               


                <legend class="m-t-15 m-b-15 p-l-5"><i class="fas fa-angle-double-right"></i> Resumen Notificaciones</legend>

                <div class="col-lg-6" style="padding-left: 5px;padding-right: 5px;float: left;">
                  <div class="btn infouserdara" style="background-color: #2296f3;"><i class="far fa-calendar"></i>
                    <h4><?= $dia_pago ?></h4><small>Día de Pago</small>
                  </div>
                </div>

                <div class="col-lg-6" style="padding-left: 5px;padding-right: 5px;float: left;">
                  <div class="btn infouserdara" style="background-color: #f3a122;"><i class="far fa-envelope"></i>
                    <h4><?= $dia_crear ?> <small><?= $hora_factura ?></small></h4><small>Crear &amp; Enviar Factura</small>
                  </div>
                </div>

                <div class="col-lg-6" style="padding-left: 5px;padding-right: 5px;float: left;">
                  <div class="btn infouserdara" style="background-color: #0aab96;"><i class="fa fa-desktop"></i>
                    <h4><?= $dia_aviso ?> <small><?= $hora_pantalla ?></small></h4><small>Aviso en pantalla</small>
                  </div>
                </div>


            

                <div class="col-lg-6" style="padding-left: 5px;padding-right: 5px;float: left;">
                  <div class="btn infouserdara" style="background-color: #1a6eb9;"><i class="far fa-credit-card"></i>
                    <h4>MX$0.00</h4><small>Deuda Actual</small>
                  </div>
                </div>

                <div class="col-lg-6" style="padding-left: 5px;padding-right: 5px;float: left;">
                  <div class="btn infouserdara" style="background-color: #ce55a8;"><i class="far fa-credit-card"></i>
                    <h4>MX$0.00</h4><small>Saldos</small>
                  </div>
                </div>





              </div>
            </div>
          </form>



        </div>

        <div class="tab-pane" id="nav-tab-2">
        </div>

        <div class="tab-pane" id="nav-tab-3">
        </div>

        <div class="tab-pane" id="nav-tab-4">
        </div>

        <div class="tab-pane" id="nav-tab-5">
        </div>

        <div class="tab-pane" id="nav-tab-6">
        </div>

        <div class="tab-pane" id="nav-tab-7">
        </div>

        <div class="tab-pane" id="nav-tab-8">
        </div>

        <div class="tab-pane" id="nav-tab-9">
        </div>

      </div>
      <!-- end tab-content -->
    </div>

  </div>


</div>
<!-- end row -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script>
  $(function() {


    App.restartGlobalFunction();
    App.setPageTitle('Editar Cliente');

  })
</script>
<!-- ================== END PAGE LEVEL JS ================== -->



<div class="modal" id="modal-tool2" role="dialog" style="padding-right: 17px;" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Modem cliente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
      </div>
      <div class="modal-body">

      </div>
    </div>

  </div>
</div>