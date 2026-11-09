<form id="perfil-form" method="post" action="ajustes/save_personalizacion_almacen" class="form-horizontal">
  <div class="modal-body">
    <div class="alert alert-primary" role="alert">
      Personaliza los colores que toman los numeros en los sig apartados.
    </div>
    <div style="font-size:.75rem;<?php if (isset($individual)) {
                                    echo $extra == 2 ? "" : "display:none;";
                                  } ?>" class="panel panel-blue">
      <div class="panel-heading">
        <h4 class="panel-title"><b>Tipos de productos</b></h4>
        <div class="panel-heading-btn">
        </div>
      </div>
      <div class="panel-body border table-responsive">
        <div class="row">
          <div style="display:inline-flex" class="col-4">
            <label style="align-self: flex-end;margin-right:5px;font-size:10px" for="">Menor a</label>
            <input id="i1" value="<?= $datos[0] ? $datos[0] : 0; ?>" name="i1" style="width: 80px;" class="form-control inputs_ajustes" type="number" placeholder="1">
            <select name="s1" class="select2ajustes">
              <option value="n/a">Selecciona</option>
              <option value="warning">Amarillo</option>
              <option value="danger">Rojo</option>
              <option value="success">Verde</option>
              <option value="primary">Azul</option>
              <option value="secondary">Gris</option>
            </select>
          </div>
          <div style="display:inline-flex" class="col-4">
            <label id="label1" style="align-self: flex-end;margin-right:5px;font-size:10px" for="">Mayor igual a <?= $datos[0] ? $datos[0] : 0; ?> pero menor igual a </label>
            <input value="<?= $datos[2] ? $datos[2] : 0; ?>" name="i2" style="width: 80px;" class="form-control inputs_ajustes" type="number">
            <select name="s2" class="select2ajustes">
              <option value="n/a">Selecciona</option>
              <option value="warning">Amarillo</option>
              <option value="danger">Rojo</option>
              <option value="success">Verde</option>
              <option value="primary">Azul</option>
              <option value="secondary">Gris</option>
            </select>
          </div>
          <div style="display:inline-flex" class="col-4">
            <label style="align-self: flex-end;margin-right:5px" for="">Mayor a</label>
            <input value="<?= $datos[4] ? $datos[4] : 0; ?>" name="i3" style="width: 90px;" class="form-control inputs_ajustes" type="number">
            <select name="s3" class="select2ajustes">
              <option value="n/a">Selecciona</option>
              <option value="warning">Amarillo</option>
              <option value="danger">Rojo</option>
              <option value="success">Verde</option>
              <option value="primary">Azul</option>
              <option value="secondary">Gris</option>
            </select>
          </div>
        </div>
      </div>
    </div>
    <div style="font-size:.75rem;<?php if (isset($individual)) {
                                    echo $extra == 1 ? "" : "display:none;";
                                  } ?>" class="panel panel-blue">
      <div class="panel-heading">
        <h4 class="panel-title"><b>Productos (accesorios)</b></h4>
        <div class="panel-heading-btn">
        </div>
      </div>
      <div class="panel-body border table-responsive">
        <div class="row">
          <div style="display:inline-flex" class="col-4">
            <label style="align-self: flex-end;margin-right:5px;font-size:10px" for="">Menor a</label>
            <input id="i4" value="<?= $datos2[0] ? $datos2[0] : 0; ?>" name="i4" style="width: 80px;" class="form-control inputs_ajustes" type="number" placeholder="1">
            <select name="s4" class="select2ajustes">
              <option value="n/a">Selecciona</option>
              <option value="warning">Amarillo</option>
              <option value="danger">Rojo</option>
              <option value="success">Verde</option>
              <option value="primary">Azul</option>
              <option value="secondary">Gris</option>
            </select>
          </div>
          <div style="display:inline-flex" class="col-4">
            <label id="label2" style="align-self: flex-end;margin-right:5px;font-size:10px" for="">Mayor igual a <?= $datos2[0] ? $datos2[0] : 0; ?> pero menor igual a </label>
            <input value="<?= $datos2[2] ? $datos2[2] : 0; ?>" name="i5" style="width: 80px;" class="form-control inputs_ajustes" type="number">
            <select name="s5" class="select2ajustes">
              <option value="n/a">Selecciona</option>
              <option value="warning">Amarillo</option>
              <option value="danger">Rojo</option>
              <option value="success">Verde</option>
              <option value="primary">Azul</option>
              <option value="secondary">Gris</option>
            </select>
          </div>
          <div style="display:inline-flex" class="col-4">
            <label style="align-self: flex-end;margin-right:5px" for="">Mayor a</label>
            <input value="<?= $datos2[4] ? $datos2[4] : 0; ?>" name="i6" style="width: 90px;" class="form-control inputs_ajustes" type="number">
            <select name="s6" class="select2ajustes">
              <option value="n/a">Selecciona</option>
              <option value="warning">Amarillo</option>
              <option value="danger">Rojo</option>
              <option value="success">Verde</option>
              <option value="primary">Azul</option>
              <option value="secondary">Gris</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
    <button type="submit" style="display:block" class="btn btn-sm btn-success btn-editperfil btn-loader">Guardar</button>
  </div>

</form>


<script>
  var selects = document.getElementsByClassName('select2ajustes');
  var inputs = document.getElementsByClassName('inputs_ajustes');
  var i1 = document.getElementById('i1');
  var i4 = document.getElementById('i4');
  fallo = false;
  i1.addEventListener('keyup', modificar)
  i1.addEventListener('change', modificar)
  i4.addEventListener('keyup', modificar2)
  i4.addEventListener('change', modificar2)

  function modificar() {
    $('#label1').html(`Mayor igual a ${i1.value} pero menor igual a`);
  }

  function modificar2() {
    $('#label2').html(`Mayor igual a ${i4.value} pero menor igual a`);
  }
  $(function() {
    $('[name="s1"] option[value="<?= $datos[1]; ?>"]').prop('selected', true);
    $('[name="s2"] option[value="<?= $datos[3]; ?>"]').prop('selected', true);
    $('[name="s3"] option[value="<?= $datos[5]; ?>"]').prop('selected', true);
    $('[name="s4"] option[value="<?= $datos2[1]; ?>"]').prop('selected', true);
    $('[name="s5"] option[value="<?= $datos2[3]; ?>"]').prop('selected', true);
    $('[name="s6"] option[value="<?= $datos2[5]; ?>"]').prop('selected', true);
    $('.select2ajustes').select2();
    $('#perfil-form').ajaxForm({
      beforeSend: function() {
        fallo = false;
        for (let i = 0; i < inputs.length; i++) {
          if (inputs[i].value == "") {
            fallo = true;
            inputs[i].classList.add("is-invalid");

          } else {
            inputs[i].classList.remove("is-invalid");
          }
        }
        for (let i = 0; i < selects.length; i++) {
          if (selects[i].value == "n/a") {
            fallo = true;
            selects[i].parentElement.style.backgroundColor = "lavenderblush";

          } else {
            selects[i].parentElement.style.backgroundColor = "";
          }
        }
        if (fallo) {
          alerta('warning', 'Introduzca un numero valido');
          return false;
        } else {
          btnloader('#perfil-form');
        }
      },
      success: function(data) {
        if (typeof update_tipos_producto === 'function') {
          update_tipos_producto();
        }
        if (typeof update_accesorios === 'function') {
          update_accesorios();
        }
        $('#gritter-notice-wrapper').remove();
        if (data == 1) {
          $('#modaltmp').modal('hide');
          alerta('exito', 'Editado correctamente')
        } else if (data == 2) {
          alerta('error', 'Error al actualizar.');
          btnloader('#perfil-form', '<i class="far fa-save fa-lg"></i> Guardar');
        } else {
          alerta('warning', 'No tienes los permisos necesarios.');
          btnloader('#perfil-form', '<i class="far fa-save fa-lg"></i> Guardar');
        }

      },
      error: function(response) {
        $('#gritter-notice-wrapper').remove();
        btnloader('#perfil-form', '<i class="far fa-save fa-lg"></i> Guardar');
        alerta('error500', 'Error');
      }
    })


    $('#modaltmp').modal('show');


  })
</script>
<!-- DEBUG-VIEW ENDED 1 APPPATH/Config/../Views/servicios/utils/modal_editar_plan.php -->