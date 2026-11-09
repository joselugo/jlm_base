<form id="acc-form" method="post" action="clientes/new_acc" class="form-horizontal">
    <div class="modal-body" style="width: 100%">
        <input type="hidden" name="idinstall" value="<?= $instal->id ?>">

        <div class='col-sm-12'>
            <div class='form-group input-group-sm'>
                <label style="font-size: 12px;">Categoría</label>
                <select class="slp form-control" id="productoid" name="productoid" style="width:100%" required>
                    <option></option>
                    <?= $select_a ?>
                </select>
            </div>
        </div>

        <div class='col-sm-12'>
            <div class='form-group input-group-sm'>
                <label style="font-size: 12px;">Accesorio</label>
                <select class="slp form-control" id="almacenid" name="accesorio" style="width:100%" required>
                    <option></option>
                </select>
            </div>
        </div>


        <div class='col-md-12'>
            <div class='form-group input-group-sm'>
                <label style="font-size: 12px;">Descripción</label>
                <input type="text" class="form-control" name="descripcion" required>
            </div>
        </div>

        <div class='col-md-4'>
            <div class='form-group input-group-sm'>
                <label style="font-size: 12px;">Cantidad (Unit,Mtrs)</label>
                <input type="number" class="form-control" placeholder="100" name="cantidad" value="1" min="1" required>
            </div>
        </div>

    </div>

    <div class="modal-footer">
        <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
        <button type="submit" class="btn btn-sm btn-success btnaddacc">Agregar</button>
    </div>

</form>


<script>
    $(function() {

        $("#productoid").change(function() {
            $.ajax({
                data: {
                    id: $("#productoid").val()
                },
                url: 'clientes/get_data_select_a',
                type: 'post',
                success: function(data) {
                    if (data) {
                        $("#almacenid").html('<option></option>');
                        $("#almacenid").append(data);
                        $('input[name="descripcion').val('');
                    } else {
                        alerta('error', 'No hay Stock disponible.');
                        $('select[name="accesorio"]').html('<option></option>');
                        $('input[name="descripcion').val('');
                    }

                }
            });
        });

        $("#almacenid").change(function() {
            $('input[name="descripcion').val($(this).find(':selected').data('dsp'));
            $('input[name="cantidad').prop('max', $(this).find(':selected').data('cantidad'));
        });


        $(".slp").select2({
            placeholder: "Seleccionar.."
        });



        $('#acc-form').ajaxForm({
            success: function(data) {
                $('#gritter-notice-wrapper').remove();
                alerta('exito', 'Accesorio registrado correctamente');
                update_acc();
                $('.remove-acc').remove();
                $('.table-acc tbody').append(data['p']);
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