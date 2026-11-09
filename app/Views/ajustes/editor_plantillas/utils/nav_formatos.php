<div class="tab-pane fade active show" id="default-tab-1">
  <div class="row">
    <?php foreach ($documentos as $formato) : ?>
      <div class="itemplantilla">
        <div class="contentplantilla text-center">
          <div style="width:  100%;height: 100%;vertical-align:  middle;display: table;padding-top: 40%;font-size: 43px;font-weight: 700;background-color: #f3f3f3;"><i class="far fa-file-pdf"></i></div>
          <div class="pre"><a class="btn-azul" onclick="getmodal('editor_plantillas/editar_plantilla?idformato=<?= $formato->id; ?>','Editar Plantilla','full');"><i class="far fa-edit"></i> Editar plantilla</a>
            <a class="btn-azul" href="<?= site_url('editor_plantillas/vista_previa?idformato=' . (int) $formato->id); ?>" target="_blank" rel="noopener" style="margin-top: 10px"><i class="far fa-eye"></i> Vista previa</a>
          </div>
        </div>
        <div class="tituloplantilla"><?= esc([
          'cotizacion'      => 'Cotización (página 1)',
          'cotizacion_bco'  => 'Cotización (datos bancarios)',
          'cotizacion_pie'  => 'Cotización (pie de página)',
          'cotizacion_mail' => 'Cotización (correo)',
        ][$formato->tipoaviso] ?? ucfirst($formato->tipoaviso)); ?></div>
      </div>
    <?php endforeach; ?>
  </div>
</div>