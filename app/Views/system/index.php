<h1 class="page-header">Sistema</h1>

<ul class="nav nav-tabs">
    <li class="nav-items"><a href="#default-tab-1" data-toggle="tab" class="nav-link active"><i class="fas fa-desktop"></i> Backup Sistema</a></li>
    <li class="nav-items"><a href="#default-tab-2" data-toggle="tab" class="nav-link"><i class="fas fa-recycle"></i> Mantenimiento</a></li>
</ul>


<div class="tab-content">
    <div class="tab-pane fade active show" id="default-tab-1">

        <table id="data-backup" data-order="[[ 1, &quot;desc&quot; ]]" class="display nowrap table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Archivo</th>
                    <th>Fecha</th>
                    <th>Tamaño</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>

    <div class="tab-pane fade" id="default-tab-2">
        <p>Desde aquí es posible purgar algunas tablas de registros para reducir el tamaño de la base de datos, así como borrar antiguos archivos adjuntos para liberar espacio en el disco duro.</p>

        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">Purgar Log de conexión de clientes (online/offline)</h4>
                    </div>
                    <div class="panel-body border">
                        <h5 class="text-center">Total registros: <b class="fechalogcliente">
                                <?= $logconexion->total ?></b></h5>

                        <div class="form-group">
                            <div style="line-height: 36px;margin-right: 10px;" class="text-center">Borrar registros antes del:</div>

                            <div class="col-sm-12 text-center" style="line-height: 35px;">
                                <input class="inputdate form-control " type="text" readonly name="fechalogcliente" placeholder="dd/mm/yy" style="max-width: 105px;display: inline;">
                                <i class="far fa-calendar fa-lg" style="position:relative;left: -25px;"></i>
                                <button type="button" class="btn btn-default" onClick="this.disabled=true;purge('fechalogcliente');">Borrar</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">Purgar emails guardados</h4>
                    </div>
                    <div class="panel-body border">
                        <h5 class="text-center">Total registros: <b class="fechamail"><?= $emails->total ?></b></h5>

                        <div class="form-group">
                            <div style="line-height: 36px;margin-right: 10px;" class="text-center">Borrar registros antes del:</div>

                            <div class="col-sm-12 text-center" style="line-height: 35px;">
                                <input class="inputdate form-control " type="text" readonly name="fechamail" placeholder="dd/mm/yy" style="max-width: 105px;display: inline;">
                                <i class="far fa-calendar fa-lg" style="position:relative;left: -25px;"></i>
                                <button type="button" class="btn btn-default" onClick="this.disabled=true;purge('fechamail');">Borrar</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">Purgar log del sistema</h4>
                    </div>
                    <div class="panel-body border">
                        <h5 class="text-center">Total registros: <b class="fechalogsistema">
                                <?= $logsistema->total ?></b></h5>

                        <div class="form-group">
                            <div style="line-height: 36px;margin-right: 10px;" class="text-center">Borrar registros antes del:</div>

                            <div class="col-sm-12 text-center" style="line-height: 35px;">
                                <input class="inputdate form-control " type="text" readonly name="fechalogsistema" placeholder="dd/mm/yy" style="max-width: 105px;display: inline;">
                                <i class="far fa-calendar fa-lg" style="position:relative;left: -25px;"></i>
                                <button type="button" class="btn btn-default" onClick="this.disabled=true;purge('fechalogsistema');">Borrar</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">Purgar archivos adjuntos</h4>
                    </div>
                    <div class="panel-body border">
                        <h5 class="text-center">Total archivos adjunto: <b class="fechaadjunto"><?= $totalsuport ?></b></h5>
                        <h5 class="text-center">Espacio ocupado por los archivos adjuntos: <b><?= $sizesuport ?></b></h5>

                        <div class="form-group">
                            <div style="line-height: 36px;margin-right: 10px;" class="text-center">Borrar archivos subidos antes del:</div>

                            <div class="col-sm-12 text-center" style="line-height: 35px;">
                                <input class="inputdate form-control " type="text" readonly name="fechaadjunto" placeholder="dd/mm/yy" style="max-width: 105px;display: inline;">
                                <i class="far fa-calendar fa-lg" style="position:relative;left: -25px;"></i>
                                <button type="button" class="btn btn-default" onClick="this.disabled=true;purge('fechaadjunto');">Borrar</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">Purgar Servidor Linux(Logs)</h4>
                    </div>
                    <div class="panel-body border">
                        <h5 class="text-center">Espacio ocupado : <b class="fechalinux"><?= $sizeserver ?></b></h5>

                        <div class="form-group">
                            <div style="line-height: 36px;margin-right: 10px;" class="text-center"></div>

                            <div class="col-sm-12 text-center" style="line-height: 35px;">
                                <input class="d-none " type="text" readonly name="fechalinux" value="04/02/2021">
                                <button type="button" class="btn btn-default" onClick="this.disabled=true;purge('fechalinux');">Borrar</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!--           <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">Purgar archivos IPs visitadas</h4>
                    </div>
                    <div class="panel-body border">
                        <h5 class="text-center">Total archivos: <b class="fechatrafico">189</b></h5>
                        <h5 class="text-center">Espacio ocupado : <b>38.8 KB</b></h5>

                        <div class="form-group">
                            <div style="line-height: 36px;margin-right: 10px;" class="text-center">Borrar archivos creados antes del:</div>

                            <div class="col-sm-12 text-center" style="line-height: 35px;">
                                <input class="inputdate form-control " type="text" readonly name="fechatrafico" placeholder="dd/mm/yy" style="max-width: 105px;display: inline;">
                                <i class="far fa-calendar fa-lg" style="position:relative;left: -25px;"></i>
                                <button type="button" class="btn btn-default" onClick="this.disabled=true;purge('fechatrafico');">Borrar</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="panel panel-inverse">
                    <div class="panel-heading">
                        <h4 class="panel-title">Comprimir Registros Tráfico clientes</h4>
                    </div>
                    <div class="panel-body border">
                        <h5 class="text-center">Total Registros: <b class="ntrafico">0</b></h5>
                        <h5 class="text-center">Tamaño : <b class="ptrafico">0.2 MB</b></h5>

                        <div class="form-group">
                            <div style="line-height: 36px;margin-right: 10px;" class="text-center"><b>Comprimir automáticamente :</b></div>

                            <div class="col-sm-12 text-center" style="line-height: 35px;">
                                <select id="compressauto">
                                    <option value="0">Desactivado</option>
                                    <option value="1">Cada 7 días</option>
                                    <option value="2">Cada 30 días</option>
                                    <option value="3">Cada 2 meses</option>
                                    <option value="4">Cada 3 meses</option>
                                    <option value="5">Cada 4 meses</option>
                                    <option value="6">Cada 6 meses</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <div style="line-height: 36px;margin-right: 10px;" class="text-center">* Esta operación podría tardar algunos minutos, no realice ninguna otra operación hasta terminar esta tarea.<br> <b>Comprimir registros antes del :</b></div>

                            <div class="col-sm-12 text-center" style="line-height: 35px;">
                                <input class="inputdate form-control " type="text" readonly name="trafico" placeholder="dd/mm/yy" style="max-width: 105px;display: inline;">
                                <i class="far fa-calendar fa-lg" style="position:relative;left: -25px;"></i>
                                <button type="button" class="btn btn-default" onClick="this.disabled=true;purge('trafico');">Iniciar</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
 -->

        </div>

    </div>
</div>


<script>
    var bk;

    $('#compressauto option[value="0"]').prop('selected', true);

    $('#compressauto').select2();
    $('#compressauto').change(function() {

        $.post("ajax/ajustes", {
                action: "savecompress",
                id: $('#compressauto').val()
            })
            .done(function(data) {
                alerta('exito', 'Configuración guardado correctamente.')
            });

    });

    function purge(tipo) {
        var fecha = $('input[name="' + tipo + '"]').val();

        if (!fecha) {
            alerta('error', 'No ha seleccionado una fecha correcta.');
            $('#default-tab-2 button').prop('disabled', false);
            return;
        }
        alerta('loader', 'Espere un momento...');
        $.post("system/purge", {
                action: "purge",
                tipo: tipo,
                fecha: fecha
            })
            .done(function(data) {
                $('#gritter-notice-wrapper').remove();
                alerta('exito', data['mensaje']);
                $('#default-tab-2 button').prop('disabled', false);
                $(data['css']).html(data['contador']);
                if (data['mb']) {
                    $('.ptrafico').html(data['mb'] + ' MB');
                }
            });
    }

    /*  function restorebk(file) {

         $.confirm({
             theme: 'supervan',
             draggable: false,
             closeIcon: true,
             animationBounce: 2.5,
             escapeKey: false,
             content: 'Esta seguro que desea utilizar este backup <b>( ' + file + ' )</b> para restaurar su sistema?',
             title: '<i class="far fa-question-circle fa-lg icodialog"></i> Restaurar Backup',
             buttons: {
                 Eliminar: {
                     text: '<i class="fas fa-cloud-upload-alt icodialog"></i> Si, restaurar', // With spaces and symbols
                     action: function() {
                         alerta('loader', 'Restaurando sistema..');
                         $.post("ajax/ajustes", {
                                 file: file,
                                 action: "restorebackup"
                             })
                             .done(function(data) {
                                 $('#gritter-notice-wrapper').remove();
                                 if (data['estado'] == 'error') {
                                     alerta('error', data['salida']);
                                 } else {
                                     alerta('exito', data['salida']);
                                 }
                             });

                     }
                 }
             }
         });

     } */

    $(function() {
        App.setPageTitle('Configuración');

        $('.inputdate').datepicker({
            todayHighlight: true,
            autoclose: true,
            language: "es"
        })

        configtable('#data-backup', 'Backup sistema');
        bk = $('#data-backup').DataTable({
            "ajax": "system/list_files?action=listbackup",
            "deferRender": true,
            "aoColumns": [{
                    mData: 'name'
                },
                {
                    mData: 'time'
                },
                {
                    mData: 'size'
                },
                {
                    mData: 'tool'
                }

            ],
            "idDataTables": "4",
            initComplete: function(oSettings, json) {
                /*  $("#data-backup_wrapper div.tooltablas")
                     .html('<div class="grupotabla btn-group btn-group-md"><label class="btn btn-default btn-md" for="upbk">\
                     <input id="upbk" name="upbk" type="file" accept=".zip" style="display:none" onChange="savebk(this)">\
                     <i class="fas fa-plus"></i> Subir Backup <small class="text-danger">(Max. 50M)</small>\
                     </label></div>'); */
            }
        })

        updatebk = function() {
            bk.ajax.reload(null, false);
        }


    })

    function savebk(file) {
        var fileSize = file.files[0].size;
        if (fileSize > 50000000) {
            file.value = "";
            alerta("error", "No se permite Subir archivos mas de 50M,necesita ampliar el límite de subida en su servidor");
        } else {
            alerta('loader', 'Subiendo archivo..');
            var file_data = $(file).prop("files")[0];
            var form_data = new FormData();
            form_data.append($(file).attr('id'), file_data)
            $.ajax({
                url: "ajax/ajustes?action=uploadbk",
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                method: 'POST',
                type: 'POST',
                success: function(data) {
                    $('#gritter-notice-wrapper').remove();
                    if (data['estado'] == 'error') {
                        $('#upbk').val('');
                        alerta('error', data['salida']);
                    } else {
                        $('#upbk').val('');
                        updatebk();
                        alerta('exito', data['salida']);
                    }

                }
            });

        }
    }
</script>