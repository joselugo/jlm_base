<form id="pre_form" method="post" action="clientes/save_update_install" class="form-horizontal inst_form">
    <div class="modal-body" style="width: 100%">
        <div class="row">
            <input type="hidden" value="<?= $instalaciones->id ?>" name="id">

            <div class='col-sm-12'>
                <div class='form-group'>
                    <label>Sucursal</label>
                    <select class="form-control sl2" name="install[sucursal]" required style="width:100%" placeholder="Seleccionar una sucursal">
                        <?= $sucursales ?>
                    </select>
                </div>
            </div>

            <div class='col-sm-12'>
                <div class='form-group'>
                    <label>Nombres y Apellidos</label>
                    <input value="<?= $instalaciones->cliente ?>" class="form-control" name="pre[cliente]" type="text" placeholder="nombre del cliente" autofocus required />
                </div>
            </div>

            <div class='col-sm-12'>
                <div class='form-group'>
                    <label>Dirección</label>
                    <input value="<?= $instalaciones->direccion ?>" class="form-control" name="pre[direccion]" type="text" placeholder="domicilio del cliente" autocomplete="off" required />
                </div>
            </div>

            <div class='col-sm-6'>
                <div class='form-group'>
                    <label>Nº Cedula/DNI/CUIT/RUT</label>
                    <input value="<?= $instalaciones->cedula ?>" class="form-control" name="pre[cedula]" type="text" placeholder="cedula cliente" autocomplete="off" required />
                </div>
            </div>


            <div class='col-sm-6'>
                <div class='form-group'>
                    <label>Telefono Casa</label>
                    <input value="<?= $instalaciones->telefono ?>" class="form-control" name="pre[telefono]" type="text" placeholder="telefono casa" autocomplete="off" />
                </div>
            </div>

            <div class='col-sm-6'>
                <div class='form-group'>
                    <label>Telefono Móvil</label>
                    <input value="<?= $instalaciones->movil ?>" class="form-control" name="pre[movil]" type="text" placeholder="telefono celular" autocomplete="off" />
                </div>
            </div>

            <div class='col-sm-6'>
                <div class='form-group'>
                    <label>Correo</label>
                    <input value="<?= $instalaciones->email ?>" class="form-control" name="pre[email]" type="email" placeholder="correo cliente" autocomplete="off" />
                </div>
            </div>

            <div class='col-sm-6'>
                <div class='form-group'>
                    <label style="width:100%">Fecha y Hora de instalación</label>
                    <input value="" class="form-control fh-install" name="pre[fecha_instalacion]" id="fecha_instalacion" type="text">
                </div>
            </div>

            <div class='col-sm-6'>
                <div class='form-group'>
                    <label>Ubicación</label>
                    <select style="width: 100%;" name="pre[zona]" class="form-control slpre" data-placeholder="Seleccionar Zona">
                        <option></option>
                        <?= $zonas ?>
                    </select>
                </div>
            </div>



            <div class='col-sm-6'>
                <div class='form-group'>
                    <label>Estado</label>
                    <select name="pre[estate]" class="form-control">
                        <option value="INSTALADO">INSTALADO</option>
                        <option value="NO INSTALADO">NO INSTALADO</option>
                        <option value="NO FACTIBLE">NO FACTIBLE</option>
                        <option value="RETIRADO">RETIRADO</option>
                        <option value="ELIMINADO">ELIMINADO</option>
                    </select>
                </div>
            </div>

            <div class='col-sm-6'>
                <div class='form-group'>
                    <label>Técnico Asignado</label>
                    <select style="width: 100%;" name="pre[idtecnico]" class="form-control slpre" data-placeholder="Seleccionar técnico">
                        <option></option>
                        <?= $tbluser ?>
                    </select>
                </div>
            </div>

            <div class='col-sm-6'>
                <div class='form-group'>
                    <label>Fecha Retiro</label>
                    <input value="" class="form-control" name="pre[fecha_salida]" id="fecha_salida" type="text">
                </div>
            </div>



            <div class='col-sm-12'>
                <div class='form-group'>
                    <label>Observaciones</label>
                    <textarea class="form-control" name="pre[notas]" placeholder="notas ..." rows="4" style="height:60px"><?= $instalaciones->notas ?></textarea>
                </div>
            </div>


        </div>



    </div>

    <div class="modal-footer">
        <a href="javascript:;" class="btn btn-white" data-dismiss="modal">Cerrar</a>
        <button type="submit" class="btn btn-success">Guardar</button>
    </div>

</form>


<script>
    $(function() {


        $('input[name="pre\[fecha_salida\]"]').datepicker({
            autoclose: true,
            language: "es",
        });

        $('#pre_form').ajaxForm({
            success: function(data) {
                $('#gritter-notice-wrapper').remove();
                updateinstall();
                updatepre();
                alerta('exito', 'Registro actualizado correctamente');
            },
            beforeSubmit: function() {
                $('#modaltmp').modal('hide');
            },
            error: function(response) {
                $('#gritter-notice-wrapper').remove();
                alerta('error500', response['responseText']);
            }
        })

        $('#modaltmp').modal('show');

        $('.fh-install').datetimepicker({
            locale: 'es',
            stepping: 30,
        });
        $('#fecha_instalacion').val('<?= $instalaciones->fecha_instalacion ?>');
        $('#fecha_instalacion').on('dp.change', function(e) {
            $('#fecha_instalacion').val(moment(e.date).format("YYYY-MM-DD H:mm:ss"));
        });

        $('#fecha_salida').val('<?= $instalaciones->fecha_salida ?>');
        $('.slpre').select2();


        $('[name="install[sucursal]"] option[value="<?= $instalaciones->sucursal ?>"]').prop('selected', true).change();
        $('[name="pre[zona]"] option[value="<?= $instalaciones->zona ?>"]').prop('selected', true).change();
        $('[name="pre[idtecnico]"] option[value="<?= $instalaciones->idtecnico ?>"]').prop('selected', true).change();
        $('[name="pre[estate]"] option[value="<?= $instalaciones->estate ?>"]').prop('selected', true);

    })
</script>