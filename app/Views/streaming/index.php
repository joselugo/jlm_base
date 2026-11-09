<ul class="nav nav-tabs">
    <li class="nav-items">
        <a href="#default-tab-1" data-toggle="tab" class="nav-link active" onClick="updatecliente()">
            <span class="d-sm-block"><i class="fa fa-users"></i> Clientes</span>
        </a>
    </li>
    <li class="nav-items">
        <a href="#default-tab-2" data-toggle="tab" class="nav-link" onClick="updatecuentas()">
            <span class="d-sm-block"><i class="fa fa-asterisk"></i> Cuentas</span>
        </a>
    </li>
    <li class="nav-items">
        <a href="#default-tab-3" data-toggle="tab" class="nav-link" onClick="updateservicios()">
            <span class="d-sm-block"><i class="fa fa-check-square"></i> Servicios</span>
        </a>
    </li>
    <li class="nav-items">
        <a href="#default-tab-4" data-toggle="tab" class="nav-link" onClick="updatepresupuesto()">
            <span class="d-sm-block"><i class="fa fa-credit-card"></i> Presupuesto</span>
        </a>
    </li>

</ul>

<div class="tab-content">

    <div class="tab-pane fade active show" id="default-tab-1">

        <table id="hs-clientes" class="display nowrap table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Streaming </th>
                    <th>Correo</th>
                    <th>Contraseña</th>
                    <th>Pin</th>
                    <th>Costo</th>
                    <th>Fecha vinculado</th>
                    <th>Estado</th>
                    <th>Día pago cliente</th>
                    <th class="all" style="max-width:20px !important" data-orderable="false"></th>
                </tr>
            </thead>
        </table>

    </div>

    <div class="tab-pane fade" id="default-tab-2">

        <div class="row">
            <div class="col-xl-3 col-md-6 ">
                <div class="widget widget-stats bg-danger">
                    <div class="stats-icon stats-icon-lg"><i class="fas fa-credit-card fa-fw"></i></div>
                    <div class="stats-content">
                        <div class="stats-title">CUENTAS VENCIDAS</div>
                        <div class="stats-number m-b-2" id="total_cuentasv">0</div>
                        <div class="stats-desc"><a onclick="getmodal('streaming/history?action=balance_history','Historial Pagos Netflix','md');">Ver cuentas <i class="fas fa-eye"></i></a></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="widget widget-stats bg-warning">
                    <div class="stats-icon stats-icon-lg"><i class="fas fa-credit-card fa-fw"></i></div>
                    <div class="stats-content">
                        <div class="stats-title">CUENTAS POR VENCER</div>
                        <div class="stats-number m-b-2" id="total_cuentaspv">0</div>
                        <div class="stats-desc"><a onclick="getmodal('streaming/history?action=balance_history','Historial Pagos Netflix','md');">Ver cuentas <i class="fas fa-eye"></i></a></div>
                    </div>
                </div>
            </div>
        </div>

        <table id="hs-cuentas" class="display nowrap table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Servicio</th>
                    <th>Fecha activado</th>
                    <th>Fecha vencimiento</th>
                    <th>Estado cuenta</th>
                    <th>Monto</th>
                    <th>Correo</th>
                    <th>Contraseña</th>
                    <th>Estado</th>
                    <th class="all" style="max-width:20px !important" data-orderable="false"></th>
                </tr>
            </thead>
        </table>

    </div>

    <div class="tab-pane fade" id="default-tab-3">

        <table id="hs-servicio" class="display nowrap table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Servicio</th>
                    <th>Tipo</th>
                    <th>Costo</th>
                    <th>Estado</th>
                    <th class="all" style="max-width:20px !important" data-orderable="false"></th>
                </tr>
            </thead>
        </table>

    </div>

    <div class="tab-pane fade" id="default-tab-4">

        <div class="row">
            <div class="col-xl-3 col-md-6 ">
                <div class="widget widget-stats bg-teal">
                    <div class="stats-icon stats-icon-lg"><i class="fas fa-credit-card fa-fw"></i></div>
                    <div class="stats-content">
                        <div class="stats-title">PRESUPUESTO NETFLIX</div>
                        <div class="stats-number m-b-2" id="total_netflix">$ MNX</div>
                        <div class="stats-desc"><a onclick="getmodal('streaming/nuevo?action=balance','Presupuesto Netflix','md');">Agregar saldo <i class="fas fa-plus"></i></a></div>
                        <div class="stats-desc"><a onclick="getmodal('streaming/history?action=balance_history','Historial Pagos Netflix','md');">Ver historial <i class="fas fa-history"></i></a></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 ">
                <div class="widget widget-stats bg-red">
                    <div class="stats-icon stats-icon-lg"><i class="fas fa-credit-card fa-fw"></i></div>
                    <div class="stats-content">
                        <div class="stats-title">PRESUPUESTO YOUTUBE</div>
                        <div class="stats-number m-b-2" id="total_youtube">$ MNX</div>
                        <div class="stats-desc"><a onclick="getmodal('streaming/nuevo?action=balanceYT','Presupuesto YouTube','md');">Agregar saldo <i class="fas fa-plus"></i></a></div>
                        <div class="stats-desc"><a onclick="getmodal('streaming/history?action=balanceYT_history','Historial Pagos YouTube','md');">Ver historial <i class="fas fa-history"></i></a></div>
                    </div>
                </div>
            </div>
        </div>
        <table id="hs-presupuesto" class="display nowrap table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Servicio</th>
                    <th>Fecha activación</th>
                    <th>Tipo</th>
                    <th>Costo</th>
                    <!--   <th class="all" style="max-width:20px !important" data-orderable="false"></th> -->
                </tr>
            </thead>
        </table>
    </div>


</div>

<script>
    /* function delete_router_hs(id, c) {
        if (c > 0) {
            alerta('error', 'No puede eliminar este router porque tiene fichas activas.');
            return false;
        }
        $.confirm({
            theme: 'supervan',
            draggable: false,
            closeIcon: true,
            animationBounce: 2.5,
            escapeKey: false,
            content: 'Solo se podrá eliminar un router que no tenga fichas Activas. Además Los perfiles asociados este routers serán eliminados.',
            title: '<i class="fa fa-question-circle fa-lg icodialog"></i> Eliminar Router',
            buttons: {
                Eliminar: {
                    text: '<i class="far fa-trash-alt icodialog"></i> Eliminar', // With spaces and symbols
                    action: function() {
                        $.post("ajax/hotspot", {
                                id: id,
                                action: "remove_router"
                            })
                            .done(function(data) {
                                alerta('exito', 'Router Eliminado Correctamente');
                                updaterouter();
                            });

                    }
                }
            }
        });

    } */

    function delete_cliente_cuenta(id, id_streaming, id_cliente) {
        $.confirm({
            theme: 'supervan',
            draggable: false,
            closeIcon: true,
            animationBounce: 2.5,
            escapeKey: false,
            content: 'La cuenta asociada a este perfil será liberado para otro usuario. ¿Está seguro de realizar esta operación?',
            title: '<i class="fa fa-question-circle fa-lg icodialog"></i> Eliminar Perfil',
            buttons: {
                Eliminar: {
                    text: '<i class="fa fa-trash icodialog"></i> Si, Eliminar', // With spaces and symbols
                    action: function() {
                        $.post("streaming/update_status_servicio", {
                                id: id,
                                id_streaming: id_streaming,
                                id_cliente: id_cliente,
                                action: "remove_perfil",
                                tipo: "cliente"
                            })
                            .done(function(data) {
                                if (data == "<p>ACCESO DENEGADO.</p>") {
                                    alerta('exito', 'No cuentas con con los permisos necesarios.');
                                } else {
                                    alerta('exito', 'Perfil Eliminado Correctamente');
                                    updatecliente();
                                }
                            });

                    }
                }
            }
        });

    }

    $(document).ready(function() {


        configtable('#hs-clientes', 'Hotspot');

        var cliente = $('#hs-clientes').DataTable({
            "ajax": "streaming/get_clientes_json",
            "deferRender": true,
            "idDataTables": "24",
            "aoColumns": [{
                    mData: 'id',
                    sWidth: "10px"
                },
                {
                    mData: 'nombre'
                },
                {
                    mData: 'streaming'
                },
                {
                    mData: 'correo'
                },
                {
                    mData: 'contrasena'
                },
                {
                    mData: 'pin'
                },
                {
                    mData: 'costo'
                },
                {
                    mData: 'fecha_vinculado'
                },
                {
                    mData: 'estado'
                },
                {
                    mData: 'diapago'
                },
                {
                    mData: 'tool'
                }
            ],
            initComplete: function(oSettings, json) {
                if (<?= $tipoacceso; ?> >= 2) {
                    $("#hs-clientes_wrapper div.tooltablas")
                        .html('<div class="grupotabla btn-group btn-group-md">\
                          <button type="button" class="btn btn-white" Onclick="getmodal(\'streaming/nuevo?action=cliente\',\'Asignar Cuenta Streaming\',\'md\');"><i class="fa fa-plus"></i> Nuevo</button>\
                        </div>');
                }
            }
        });

        var cuentas = $('#hs-cuentas').DataTable({
            "ajax": "streaming/get_cuentas_json",
            "deferRender": true,
            "idDataTables": "24",
            "aoColumns": [{
                    mData: 'id',
                    sWidth: "10px"
                },
                {
                    mData: 'nombre'
                },
                {
                    mData: 'fecha_alta_servicio'
                },
                {
                    mData: 'fecha_vencimiento'
                },
                {
                    mData: 'lastdays'
                },
                {
                    mData: 'costo'
                },
                {
                    mData: 'correo'
                },
                {
                    mData: 'contrasena'
                },
                {
                    mData: 'estatus'
                },
                {
                    mData: 'tool'
                }
            ],
            initComplete: function(oSettings, json) {
                if (<?= $tipoacceso; ?> >= 2) {
                    $("#hs-cuentas_wrapper div.tooltablas")
                        .html('<div class="grupotabla btn-group btn-group-md">\
  		            <button type="button" class="btn btn-white" Onclick="getmodal(\'streaming/nuevo?action=cuenta\',\'Nueva Cuenta Streaming\',\'md\');"><i class="fa fa-plus"></i> Nuevo</button>\
		            </div>');
                }
            }
        });

        var servicios = $('#hs-servicio').DataTable({
            "ajax": "streaming/get_services_json",
            "deferRender": true,
            "idDataTables": "24",
            "aoColumns": [{
                    mData: 'id',
                    sWidth: "10px"
                },
                {
                    mData: 'nombre'
                },
                {
                    mData: 'tipo'
                },
                {
                    mData: 'costo'
                },
                {
                    mData: 'estatus'
                },
                {
                    mData: 'tool'
                }
            ],
            initComplete: function(oSettings, json) {
                if (<?= $tipoacceso; ?> >= 2) {
                    $("#hs-servicio_wrapper div.tooltablas")
                        .html('<div class="grupotabla btn-group btn-group-md">\
                          <button type="button" class="btn btn-white" Onclick="getmodal(\'streaming/nuevo?action=streaming\',\'Nuevo Servicio Streaming\',\'md\');"><i class="fa fa-plus"></i> Nuevo</button>\
                        </div>');
                }
            }
        });

        var balance = $('#hs-presupuesto').DataTable({
            "ajax": "streaming/get_balance_json",
            "deferRender": true,
            "idDataTables": "24",
            "initComplete": function(settings, json) {
                var netflix = json.data_ext.netflix.balance;
                var youtube = json.data_ext.youtube.balance;
                $('#total_netflix').html('$ ' + netflix + ' MNX');
                $('#total_youtube').html('$ ' + youtube + ' MNX');
            },
            "aoColumns": [{
                    mData: 'id',
                    sWidth: "10px"
                },
                {
                    mData: 'servicio'
                },
                {
                    mData: 'fecha_vinculado'
                },
                {
                    mData: 'tipo'
                },
                {
                    mData: 'costo'
                }
            ],

        });

        updatecliente = function() {
            cliente.ajax.reload(null, false);
        }

        updatecuentas = function() {
            cuentas.ajax.reload(function(json) {
                var v = json.contV;
                var pv = json.contPV;
                $('#total_cuentasv').html('Total: ' + v);
                $('#total_cuentaspv').html('Total: ' + pv);
            });
        }

        updateservicios = function() {
            servicios.ajax.reload(null, false);
        }

        updatepresupuesto = function() { //Use the callback to update an external elements
            /*  balance.ajax.reload(null, false); */
            balance.ajax.reload(function(json) {
                var netflix = json.data_ext.netflix.balance;
                var youtube = json.data_ext.youtube.balance;
                $('#total_netflix').html('$ ' + netflix + ' MNX');
                $('#total_youtube').html('$ ' + youtube + ' MNX');
            });
        }

    });


    //Update status
    function update_status_servicio(id, n) {
        var alsup = $.confirm({
            theme: 'supervan',
            draggable: false,
            closeIcon: true,
            animationBounce: 2.5,
            escapeKey: false,
            content: 'Esta seguro que desea desactivar el servicio straming. <br><b class="text-warning">#' + n + '</b>',
            title: '<i class="far fa-question-circle fa-lg icodialog"></i> DESACTIVAR SERVICIO',
            buttons: {
                Eliminar: {
                    text: '<i class="far fa-trash-alt"></i> Si, desactivar',
                    action: function() {
                        this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
                        this.buttons.Eliminar.disable();
                        $('.jconfirm-closeIcon').remove();
                        $.post(base_url + "streaming/update_status_servicio", {
                                id: id,
                                nombre: n,
                                tipo: "servicio",
                                action: "desactivar",
                            })
                            .done(function(data) {
                                alsup.close();
                                if (data == 'error') {
                                    alerta('error', data);
                                } else {
                                    alerta('exito', 'Servicio streaming desactivado correctamente');
                                    updateservicios();
                                }
                            });
                        return false;

                    }
                }
            }
        });

    }
    //Activar
    function update_status_servicio_activo(id, n) {
        var alsup = $.confirm({
            theme: 'supervan',
            draggable: false,
            closeIcon: true,
            animationBounce: 2.5,
            escapeKey: false,
            content: 'Esta seguro que desea activar el servicio straming. <br><b class="text-warning">#' + n + '</b>',
            title: '<i class="far fa-question-circle fa-lg icodialog"></i> ACTIVAR SERVICIO',
            buttons: {
                Eliminar: {
                    text: '<i class="far fa-trash-alt"></i> Si, activar',
                    action: function() {
                        this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
                        this.buttons.Eliminar.disable();
                        $('.jconfirm-closeIcon').remove();
                        $.post(base_url + "streaming/update_status_servicio", {
                                id: id,
                                nombre: n,
                                tipo: "servicio",
                                action: "activar",
                            })
                            .done(function(data) {
                                alsup.close();
                                if (data == 'error') {
                                    alerta('error', data);
                                } else {
                                    alerta('exito', 'Servicio streaming activado correctamente');
                                    updateservicios();
                                }
                            });
                        return false;

                    }
                }
            }
        });

    }
    //Desactivar Cuenta
    function update_status_cuenta(id, n) {
        var alsup = $.confirm({
            theme: 'supervan',
            draggable: false,
            closeIcon: true,
            animationBounce: 2.5,
            escapeKey: false,
            content: 'Esta seguro que desea desactivar el servicio straming. <br><b class="text-warning">#' + n + '</b>',
            title: '<i class="far fa-question-circle fa-lg icodialog"></i> DESACTIVAR SERVICIO',
            buttons: {
                Eliminar: {
                    text: '<i class="far fa-trash-alt"></i> Si, desactivar',
                    action: function() {
                        this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
                        this.buttons.Eliminar.disable();
                        $('.jconfirm-closeIcon').remove();
                        $.post(base_url + "streaming/update_status_servicio", {
                                id: id,
                                nombre: n,
                                tipo: "cuenta",
                                action: "desactivar",
                            })
                            .done(function(data) {
                                alsup.close();
                                if (data == 'error') {
                                    alerta('error', data);
                                } else {
                                    alerta('exito', 'Servicio streaming desactivado correctamente');
                                    updatecuentas();
                                }
                            });
                        return false;

                    }
                }
            }
        });

    }
    //Activar cuenta
    function update_status_cuenta_activo(id, n) {
        var alsup = $.confirm({
            theme: 'supervan',
            draggable: false,
            closeIcon: true,
            animationBounce: 2.5,
            escapeKey: false,
            content: 'Esta seguro que desea activar el servicio straming. <br><b class="text-warning">#' + n + '</b>',
            title: '<i class="far fa-question-circle fa-lg icodialog"></i> ACTIVAR SERVICIO',
            buttons: {
                Eliminar: {
                    text: '<i class="far fa-trash-alt"></i> Si, activar',
                    action: function() {
                        this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
                        this.buttons.Eliminar.disable();
                        $('.jconfirm-closeIcon').remove();
                        $.post(base_url + "streaming/update_status_servicio", {
                                id: id,
                                nombre: n,
                                tipo: "cuenta",
                                action: "activar",
                            })
                            .done(function(data) {
                                alsup.close();
                                if (data == 'error') {
                                    alerta('error', data);
                                } else {
                                    alerta('exito', 'Servicio streaming activado correctamente');
                                    updatecuentas();
                                }
                            });
                        return false;

                    }
                }
            }
        });

    }
    //Cuenta

    //End update status

    App.restartGlobalFunction();
    App.setPageTitle('Servicios Straming');
</script>