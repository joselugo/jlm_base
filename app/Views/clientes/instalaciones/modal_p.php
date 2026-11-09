<form id="prodc-form" method="post" action="clientes/new_p_add" class="form-horizontal">
    <div class="modal-body" style="width: 100%">
        <input type="hidden" name="idinstall" value="<?= $instal->id ?>">

        <div class='col-sm-12'>
            <div class='form-group input-group-sm'>
                <label style="font-size: 12px;">Categoria producto</label>
                <select class="slp form-control" name="productoid" id="productoid" style="width:100%">
                    <option></option>
                    <?= $select_p ?>
                </select>
            </div>
        </div>

        <div class='col-sm-12 hideproduct' style="display:none">
            <div class='form-group input-group-sm'>
                <label style="font-size: 12px;">Producto</label>
                <select class="form-control slp" id="almacenid" name="alquileres[productoid]" style="width:100%">
                    <option></option>
                </select>
            </div>
        </div>

        <div class='col-sm-12'>
            <div class='form-group input-group-sm'>
                <label style="font-size: 12px;">Tipo de producto</label>
                <input class="form-control" list="tiposp" name="tipo_producto" required>
                <datalist id="tiposp">
                    <option value="Antena">
                    </option>
                    <option value="Router">
                    </option>
                    <option value="Radio">
                    </option>
                    <option value="Switch">
                    </option>
                </datalist>
            </div>
        </div>


        <div class='col-sm-12'>
            <div class='form-group input-group-sm'>
                <label style="font-size: 12px;">Condición</label>
                <select class="form-control slp condicionprod" name="condicion" style="width:100%">
                    <option value="0">Libre</option>
                    <option value="1">Préstamo/Comodato</option>
                    <option value="2">Venta (Facturar)</option>
                </select>
            </div>
        </div>

        <div class='col-sm-12 ventaprod' style="display: none">
            <div class='form-group input-group-sm'>
                <label style="font-size: 12px;">Tipo de pago</label>
                <select class="form-control slp" name="alquileres[totalcuota]" style="width:100%">
                    <option value="0">Un solo pago</option>
                    <option value="1">Mensualmente</option>
                    <option value="2">Pago en 2 Cuotas</option>
                    <option value="3">Pago en 3 Cuotas</option>
                    <option value="4">Pago en 4 Cuotas</option>
                    <option value="5">Pago en 5 Cuotas</option>
                    <option value="6">Pago en 6 Cuotas</option>
                    <option value="7">Pago en 7 Cuotas</option>
                    <option value="8">Pago en 8 Cuotas</option>
                    <option value="9">Pago en 9 Cuotas</option>
                    <option value="10">Pago en 10 Cuotas</option>
                    <option value="11">Pago en 11 Cuotas</option>
                    <option value="12">Pago en 12 Cuotas</option>
                    <option value="13">Pago en 13 Cuotas</option>
                    <option value="14">Pago en 14 Cuotas</option>
                    <option value="15">Pago en 15 Cuotas</option>
                    <option value="16">Pago en 16 Cuotas</option>
                    <option value="17">Pago en 17 Cuotas</option>
                    <option value="18">Pago en 18 Cuotas</option>
                    <option value="19">Pago en 19 Cuotas</option>
                    <option value="20">Pago en 20 Cuotas</option>
                    <option value="21">Pago en 21 Cuotas</option>
                    <option value="22">Pago en 22 Cuotas</option>
                    <option value="23">Pago en 23 Cuotas</option>
                    <option value="24">Pago en 24 Cuotas</option>
                </select>
            </div>
        </div>

        <div class='col-md-6 ventaprod' style="display: none">
            <div class='form-group input-group-sm'>
                <label style="font-size: 12px;">Monto</label>
                <input type="text" class="form-control" placeholder="100" name="alquileres[monto]" pattern="[-+]?[0-9]*[.]?[0-9]+" value="0">
            </div>
        </div>

    </div>

    <div class="modal-footer">
        <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
        <button type="submit" class="btn btn-sm btn-success btnaddprod" disabled>Agregar</button>
    </div>

</form>


<script>
    $(function() {

        $('.condicionprod').change(function() {
            if ($(this).val() == 2) {
                $('.ventaprod').show();
            } else {
                $('.ventaprod').hide();
            }
        })


        $("#productoid").change(function() {
            $('.condicionprod').trigger('change');

            var myString = $("#productoid").val();
            if (myString == "0") {
                $("#almacenid").html('<option></option>');
                $('.hideproduct').hide();
                $(".btnaddprod").prop("disabled", true);
            } else {
                var arr = $(this).find(':selected').data('tipo');
                if (arr == "otros") {
                    $('.hideproduct').hide();
                    $(".btnaddprod").prop("disabled", false);
                    $('input[name="alquileres\[monto\]"]').val($(this).find(':selected').data('costo'));
                    $('input[name="producto-otros"]').val($(this).val());
                } else {
                    $('input[name="producto-otros"]').val('');
                    $(".btnaddprod").prop("disabled", true);
                    //+++++++++++++++++++
                    $.ajax({
                        data: {
                            id: $("#productoid").val()
                        },
                        url: 'clientes/get_data_select',
                        type: 'post',
                        success: function(data) {
                            if (data) {
                                $('.hideproduct').show();
                                $('#almacenid').html('<option></option>');
                                $('#almacenid').append(data);
                            } else {
                                alerta('error', 'No hay productos disponibles en esta categoría.');
                                $('#almacenid').html('<option></option>');
                            }

                        }
                    });
                    //+++++++++++++++++++
                }
            }

        });

        $("#almacenid").change(function() {
            $('.condicionprod').trigger('change');
            $(".btnaddprod").prop("disabled", false);
            $('input[name="alquileres\[monto\]"]').val($('#almacenid').find(':selected').data('costo'));
        });


        $('select[name="alquileres\[totalcuota\]"]').change(function() {
            $('.condicionprod').trigger('change');
            //-->Productos Otros
            if ($('#productoid').find(':selected').data('costo')) {
                if ($(this).val() > 0 && $('#productoid').find(':selected').data('costo')) {
                    var costomensual = $('#productoid').find(':selected').data('costo') / $(this).val();
                    $('input[name="alquileres\[monto\]"]').val(parseFloat(costomensual).toFixed(2));
                } else {
                    $('input[name="alquileres\[monto\]"]').val($('#productoid').find(':selected').data('costo'));
                }
                return false;
            }

            //-->Productos Almacén
            if ($(this).val() > 0 && $('#almacenid').find(':selected').data('costo')) {
                var costomensual = $('#almacenid').find(':selected').data('costo') / $(this).val();
                $('input[name="alquileres\[monto\]"]').val(parseFloat(costomensual).toFixed(2));
            } else {
                $('input[name="alquileres\[monto\]"]').val($('#almacenid').find(':selected').data('costo'));
            }

        });


        $(".slp").select2({
            placeholder: "Seleccionar.."
        });



        $('#prodc-form').ajaxForm({
            success: function(data) {
                $('#gritter-notice-wrapper').remove();
                alerta('exito', 'Producto registrado correctamente');
                update_mat();
                $('.remove-prod').remove();
                $('.table-prod tbody').append(data['p']);
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
    })
</script>