<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title"><b>Documentos PDF</b></h4>
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-expand"><i class="fas fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-collapse"><i class="fas fa-minus"></i></a>
    </div>

  </div>
  <div class="panel-body border-panel">
    <table id="data-documentos" class="display nowrap table table-striped table-bordered" cellspacing="0" width="100%" data-order="[[ 0, &quot;desc&quot; ]]">
      <thead>
        <tr>
          <th>Creado por</th>
          <th>Título</th>
          <th>Archivo</th>
          <th>Fecha</th>
          <th class="all" style="max-width:20px !important" data-orderable="false"></th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($documentos as $documento) : ?>
          <tr class="file_<?= $documento->id; ?>">
            <td><?= $documento->nombre; ?></td>
            <td><?= $documento->asunto; ?></td>
            <td><a href="clientes/render_documento_nota?nombre=<?= $documento->adjunto; ?>" target="_BLANK"><?= $documento->nombre_real; ?></a></td>
            <td data-order="1615328144"><?= $documento->fechaalta; ?></td>
            <td class=" text-center">
              <a data-toggle="tooltip" title="" class="btn btn-default btn-icon btn-sm" onclick="getmodal('clientes/modal_edit_documento?id=<?= $documento->id; ?>','Editar Documento','md');" data-original-title="Editar"><i class="far fa-edit"></i></a>
              <a data-toggle="tooltip" title="" class="btn btn-default btn-icon btn-sm" onclick="remove_file('<?= $documento->id; ?>')" data-original-title="Eliminar"><i class="far fa-trash-alt"></i></a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>

    </table>
  </div>
</div>
<div class="panel panel-default panel-notas">
  <div class="panel-heading">
    <h4 class="panel-title"><b>Notas</b></h4>
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-sm btn-info" onclick="getmodal('clientes/modal_new_nota?id=<?= $id; ?>','Nueva Nota','md');"><i class="fas fa-plus"></i> Agrega Nota</a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-expand"><i class="fas fa-expand"></i></a>
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-collapse"><i class="fas fa-minus"></i></a>
    </div>
  </div>
  <div class="panel-body border-panel inbox" style="background: #d9e0e7;">
    <ul class="timeline">
      <?php foreach ($notas as $nota) : ?>
        <li class="nota_<?= $nota->id; ?>">
          <div class="timeline-time"><span class="date"><?= $nota->fecha; ?></span><span class="time"><?= ucfirst($nota->nombre); ?></span></div>
          <div class="timeline-body">
            <div class="timeline-header">
              <span class="username"><a href="javascript:;"><?= $nota->asunto; ?></a> <small></small></span>
              <span class="pull-right text-muted">
                <button type="button" class="btn btn-xs btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Eliminar Nota" onclick="delete_nota('<?= $nota->id; ?>');"><i class="far fa-trash-alt"></i></button>
              </span>
            </div>
            <div class="timeline-content">
              <?= html_entity_decode($nota->mensaje); ?>
              <?php if ($nota->imagen != "" and $nota->adjuntos != "") : ?>
                <ul class="attached-document clearfix">
                  <li class="fa-camera" style="margin-bottom: 0;">
                    <div class="document-file" style="height: auto;">
                      <a target="_black" href="clientes/render_img_nota?nombre=<?= $nota->imagen; ?>">
                        <?php if (strpos($nota->imagen, ".jpg") or strpos($nota->imagen, ".png") or strpos($nota->imagen, ".gif")) : ?>
                          <img src="clientes/render_img_nota?nombre=<?= $nota->imagen; ?>" alt="No disponible">
                        <?php else : ?>
                          <i class="far fa-eye"></i>
                          <span style="font-size: 10px;"> Ver documento</span>
                        <?php endif ?>
                      </a>
                    </div>
                    <div class="document-name"><a download="<?= $nota->imagen; ?>" href="clientes/render_img_nota?nombre=<?= $nota->imagen; ?>"><i class="fas fa-download"></i> <?= $nota->adjuntos; ?></a></div>
                  </li>
                </ul>
              <?php endif ?>
            </div>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</div>

<script>
  function delete_nota(id) {

    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Esta seguro que desea eliminar la nota seleccionada?, los archivos adjuntos de esta nota también serán eliminados del servidor',
      title: '<i class="fas fa-question-circle fa-lg icodialog"></i> Eliminar Nota',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt icodialog"></i> Si, Eliminar', // With spaces and symbols
          action: function() {
            $.post("clientes/delete_nota", {
                id
              })
              .done(function(data) {
                if (data == 1) {
                  $('.nota_' + id + ',.tooltip').remove();
                  alerta('exito', 'Nota eliminada correctamente');
                } else if (data == 3) {
                  alerta('error', 'No se encontro la nota');
                } else {
                  alerta('error', 'Error al eliminar la nota.');

                }
              });

          }
        }
      }
    });

  }

  function remove_file(id) {

    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Esta seguro que desea eliminar este documento?',
      title: '<i class="fas fa-question-circle fa-lg icodialog"></i> Eliminar Documento',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt icodialog"></i> Si, Eliminar', // With spaces and symbols
          action: function() {
            $.post("clientes/remove_documento", {
                id
              })
              .done(function(data) {
                if (data == 1) {
                  $('.file_' + id + ',.tooltip').remove();
                  alerta('exito', 'Documento eliminado correctamente');
                }else if(data==2){
                  $('.file_' + id + ',.tooltip').remove();
                  alerta('exito', 'El Documento ya esta previamente eliminado.');
                }else{
                  alerta("error","Error al eliminar");
                }
              });

          }
        }
      }
    });

  }

  $(function() {
    configtable('#data-documentos', 'Documentos');
    $('#data-documentos').DataTable({
      "idDataTables": "40",
      initComplete: function(oSettings, json) {
        $("#data-documentos_wrapper div.tooltablas")
          .html('<div class="grupotabla btn-group">\
        <button type="button" class="btn btn-white" Onclick="getmodal(\'clientes/modal_new_documento?id=<?= $id; ?>\',\'Nuevo documento\',\'md\');"><i class="fas fa-plus"></i> Nuevo Documento</button>\
      </div>');
      }
    });
  })
</script>