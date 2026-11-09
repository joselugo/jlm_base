<ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="#index/home">Inicio</a></li>
    <li class="breadcrumb-item"><a href="#clientes/instalaciones">Instalaciones</a></li>
    <li class="breadcrumb-item active">Agregar Materiales</li>
</ol>

<h4><b>Materiales Instalación <small>(<?= $materials->cliente ?>)</small></b></h4>


<div class="panel panel-inverse m-t-20">
    <div class="panel-heading">
        <h4 class="panel-title">Productos Instalados <div class="pull-right"><button type="button" class="btn btn-success btn-icon btn-sm" data-toggle="tooltip" title="Agregar producto" onclick="getmodal('clientes/instalaciones_p?id=<?= $materials->id ?>','Productos','md');"><i class="fas fa-plus fa-lg"></i></button></div>
        </h4>
    </div>
    <div class="panel-body table-responsive">
        <table id="data-materials" class="table m-b-0 text-inverse table-prod">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Descripción</th>
                    <th>Nº Mac</th>
                    <th>Nº Serie</th>
                    <th>Condición</th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="panel panel-inverse">
    <div class="panel-heading">
        <h4 class="panel-title">Accesorios utilizados <div class="pull-right"><button type="button" class="btn btn-success btn-icon btn-sm" data-toggle="tooltip" title="Agregar Accesorios" onclick="getmodal('clientes/instalaciones_a?id=<?= $materials->id ?>','Accesorios','md');"><i class="fas fa-plus fa-lg"></i></button></div>
        </h4>
    </div>
    <div class="panel-body table-responsive">

        <table id="data-accesorios" class="table m-b-0 text-inverse table-acc">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th></th>
                </tr>
            </thead>
        </table>

    </div>
</div>


<script>
    var base_url = "<?= base_url(); ?>";
    var materials, accesorio;


    configtable('#data-accesorios');
    accesorio = $('#data-accesorios').DataTable({
        "ajax": base_url + "clientes/get_accesorios_add?id=<?= $materials->id ?>",
        "deferRender": true,
        "idDataTables": "1",
        "aoColumns": [{
                mData: 'descripcion'
            },
            {
                mData: 'cantidad'
            },
            {
                mData: 'tool',
                sClass: 'text-center'
            }
        ],
        initComplete: function(oSettings, json) {
            $("#data-accesorios div.tooltablas")
                .html('<div class="grupotabla btn-group m-r-5">\
		</div>');

        }
    }).on('draw', function() {
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
    });

    update_acc = function() {
        accesorio.ajax.reload(null, false);
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
    }

    configtable('#data-materials');
    materials = $('#data-materials').DataTable({
        "ajax": base_url + "clientes/get_productos_materials?id=<?= $materials->id ?>",
        "deferRender": true,
        "idDataTables": "1",
        "aoColumns": [{
                mData: 'tipo'
            },
            {
                mData: 'producto'
            },
            {
                mData: 'mac_producto'
            },
            {
                mData: 'serial_producto'
            },
            {
                mData: 'condicion'
            },
            {
                mData: 'tool',
                sClass: 'text-center'
            }
        ],
        initComplete: function(oSettings, json) {
            $("#data-materials div.tooltablas")
                .html('<div class="grupotabla btn-group m-r-5">\
		</div>');

        }
    }).on('draw', function() {
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
    });

    update_mat = function() {
        materials.ajax.reload(null, false);
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
    }


    function remove_p_install(id, ida) {

        $.confirm({
            theme: 'supervan',
            draggable: false,
            closeIcon: true,
            animationBounce: 2.5,
            escapeKey: false,
            content: 'El producto volverá estar disponible en el almacén.',
            title: '<i class="fa fa-question-circle fa-lg icodialog"></i> Remover Producto',
            buttons: {
                Eliminar: {
                    text: '<i class="far fa-trash-alt icodialog"></i> Eliminar', // With spaces and symbols
                    action: function() {
                        $.post("clientes/remove_p_install", {
                                id: id,
                                ida: ida
                            })
                            .done(function(data) {
                                $('.tr-p-' + id).remove();
                                alerta('exito', 'Producto Retirado correctamente, Ahora ya se encuentra disponible en almacén');
                                update_mat();
                            });

                    }
                }
            }
        });

    }

    function remove_a_install(id, ida) {

        $.confirm({
            theme: 'supervan',
            draggable: false,
            closeIcon: true,
            animationBounce: 2.5,
            escapeKey: false,
            content: 'La cantidad utilizada volverá al almacén.',
            title: '<i class="fa fa-question-circle fa-lg icodialog"></i> Remover Accesorio',
            buttons: {
                Eliminar: {
                    text: '<i class="far fa-trash-alt icodialog"></i> Eliminar', // With spaces and symbols
                    action: function() {
                        $.post("clientes/remove_a_install", {
                                id: id,
                                ida: ida
                            })
                            .done(function(data) {
                                $('.tr-a-' + id).remove();
                                alerta('exito', 'Accesorio Retirado correctamente');
                                update_acc();
                            });
                    }
                }
            }
        });
    }

    App.restartGlobalFunction();
    App.setPageTitle('Agregar Productos');
</script>