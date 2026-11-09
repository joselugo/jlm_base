<link href="<?= base_url() ?>/public/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet">

<style type="text/css">
  .card-body .switchery>small,
  .horario .switchery>small {
    height: 13px;
    width: 13px;
  }

  .checkbox1 {
    margin-top: 4px;
    margin: auto;
    width: 20px;
    height: 20px;
  }

  .card-body div {

    float: left;
  }

  .card-body {
    display: inline-block;
    width: 100%;
    padding: 5px;
  }

  .jstree-leaf a.jstree-anchor {
    font-size: 11px;
  }

  .card-body .switchery,
  .horario .switchery {
    height: 13px;
    width: 25px;
  }

  .jstree-default .jstree-open>.jstree-ocl {
    background-position: unset;
    display: none;
  }

  .jstree-default .jstree-node {
    margin-left: 0px;
  }

  .jstree-default .jstree-anchor {
    margin-left: 4px;
  }

  .jstree-default .jstree-clicked,
  .jstree-default .jstree-hovered,
  .jstree-default .jstree-wholerow-clicked {
    background: unset !important;
    box-shadow: none !important;
    cursor: unset;
  }

  legend {
    font-size: 14px;
    padding-bottom: 0px;
  }
</style>

<ol class="breadcrumb pull-right">
  <li class="breadcrumb-item"><a href="#index/home">Inicio</a></li>
  <li class="breadcrumb-item"><a href="#sistema/gestion_personal">Lista de rutas</a></li>
  <li class="breadcrumb-item active">Nueva ruta</li>
</ol>
<h2 class="page-header">Nueva ruta</h2>

<div class="panel panel-blue">
  <div class="panel-heading">
    <h4 class="panel-title">Nueva ruta</h4>
  </div>
  <div>



    <div class="col-lg-6">
      <legend class="m-b-15">Datos</legend>
      <div class="form-group row">
        <label class="col-md-4 col-form-label text-right">Nombre</label>
        <input type="hidden" name="id" id="id" required="">
        <div class="col-md-8">
          <input type="text" class="form-control" id="nombre" autofocus="" required="">
        </div>
      </div>

      <div class="form-group row">
        <label class="col-md-4 col-form-label text-right">Descripcion</label>
        <div class="col-md-8">
          <input type="text" class="form-control no" id="descripcion" required="">
        </div>
      </div>
      <div class="form-group row">
        <label class="col-md-4 col-form-label text-right">¿Es un permiso especial?</label>
        <div style="padding-left: 15px; padding-top: 2px;" class="col-md-7">
          <input id="especial" class="checkbox1" type="checkbox" aria-label="Es un permiso especial?">
        </div>
      </div>
      <div id="bloque">
        <div class="form-group row">
          <label class="col-md-4 col-form-label text-right">Url</label>
          <div class="col-md-8">
            <input type="text" class="form-control" id="url" required="">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-md-4 col-form-label text-right">imagen</label>
          <div class="col-md-8">
            <input type="text" class="form-control" id="imagen" placeholder="fa fa-folder">
          </div>
        </div>
      </div>
      <div class="form-group row">
        <label class="col-md-4  text-right">Padre</label>
        <div class="col-md-8">
          <select id="padre" class="custom-select" id="inputGroupSelect02">
            <option value=0 selected>n/a</option>
            <?php
            foreach ($data as $datos) : ?>
              <option value="<?= $datos->id_permiso ?>" selected><?= $datos->modulo ?></option>
            <?php endforeach ?>
          </select>
        </div>
      </div>

      <div class="form-group row">
        <div class="col-md-8">
          <div class="form-group row">
            <div class="col-md-8">
              <input type="submit" id="formulario1" value="Guardar" class="btn btn-info btn-lg">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
<script src="<?= base_url() ?>/public/plugins/jstree/dist/jstree.min.js"></script>
<script>
  var formulario1 = document.getElementById('formulario1');
  var nombre = document.getElementById('nombre');
  var descripcion = document.getElementById('descripcion');
  var url = document.getElementById('url');
  var padre = document.getElementById('padre');
  var especial = document.getElementById('especial');
  var bloque = document.getElementById('bloque');
  var imagen = document.getElementById('imagen');
  especial.addEventListener('click', function() {
    if (especial.checked) {
      bloque.style.display = "none"
    } else {
      bloque.style.display = "block"
    }
  })


  formulario1.addEventListener('click', function() {
    if (especial.checked) {
      imagen = ""
      url_txt = ""
      padre_txt = padre.value
      check = 2
    } else {
      url_txt = url.value
      imagen = imagen.value
      padre_txt = padre.value
      check = 1
    }
    $.post(base_url + 'rutas/create_ruta', {
      'nombre': nombre.value,
      'descripcion': descripcion.value,
      'imagen': imagen,
      'url': url_txt,
      'padre': padre_txt,
      'especial': check,
    }, function(data) {
      if (data == "1") {
        alerta('exito', 'Datos agregados con exito');
        $(location).attr('href', base_url + "#rutas/index");
      } else {
        alerta('error', 'Error al actualizar datos')
      }
    })
  })
</script>