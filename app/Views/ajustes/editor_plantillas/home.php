<style type="text/css">
  .pre {
    position: relative;
    top: -180px;
    height: 100%;
    left: 0px;
    padding-top: 60px;
    background: rgba(255, 255, 255, 0.73);
    text-align: center;
    display: none;
  }

  .itemplantilla {
    border: 1px solid #464646;
    width: 170px;
    display: inline-block;
    margin: 10px 10px;
    cursor: pointer;
  }

  .contentplantilla {
    height: 185px;
    border: 3px solid #2f2f2f;
  }

  .contentplantilla:hover {
    border: 3px solid #2196F3;
  }

  .contentplantilla:hover .pre {
    display: block;
  }

  .itemplantilla:hover {
    border: 1px solid #2196F3;
  }

  .itemplantilla:hover .tituloplantilla {
    background: #2196F3;
  }

  .tituloplantilla {
    display: block;
    color: #fff;
    font-size: 11px;
    height: 27px;
    line-height: 27px;
    background: #2f2f2f;
    text-align: center;
    font-weight: 700;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  #selectsucursal {
    font-size: 10px;
    width: 250px;
    margin-left: 10px;
    padding-right: 10px;

  }
</style>
<div>
  <ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="/admin">Inicio</a></li>
    <li class="breadcrumb-item"><a href="/admin/#ajax/ajustes">Ajustes</a></li>
    <li class="breadcrumb-item active">Editor plantillas</li>
  </ol>
  <h1 class="page-header">Plantillas <span>
      <select name="idsucursal" id="selectsucursal">
        <?= $sucursales; ?>
      </select>
    </span></h1>
  <ul class="nav nav-tabs nav-ajax">
    <?php foreach ($formatos as $key => $formato) : ?>
      <?php if ($key == 0) : ?>
        <li class="nav-items"><a id="documentos_nav" data-url="editor_plantillas/formatos?idformato=<?= $formato->id; ?>&idsucursal=<?= $idsucursal; ?>" href="#default-tab-<?= $formato->id; ?>" data-toggle="tab" class="nav-link active"><i class="far fa-file-pdf"></i><?= $formato->descripcion; ?></a></li>
      <?php else : ?>
        <li class="nav-items"><a data-url="editor_plantillas/formatos?idformato=<?= $formato->id; ?>&idsucursal=<?= $idsucursal; ?>" href="#default-tab-<?= $formato->id; ?>" data-toggle="tab" class="nav-link"><i class="far fa-file-pdf"></i><?= $formato->descripcion; ?></a></li>
      <?php endif; ?>
    <?php endforeach; ?>
  </ul>
  <div class="tab-content">
    <div class="tab-pane fade active show" id="default-tab-1">
    </div>
    <div class="tab-pane fade" id="default-tab-2">
    </div>
    <div class="tab-pane fade" id="default-tab-3">
    </div>
    <div class="tab-pane fade" id="default-tab-4">
    </div>
  </div>
</div>
<script>
  $('#selectsucursal option[value="<?= $idsucursal;?>"]').prop('selected', true);
  $("#selectsucursal").select2();

  function inicio() {
    document.getElementById('documentos_nav').click();
  }
  inicio();
  $('#selectsucursal').change(function() {
    window.location.href = "#editor_plantillas/home?idsucursal=" + $('#selectsucursal').val();
  })
</script>