	<form id="form-editor" method="post" action="editor_plantillas/guardar_formato">
	  <div class="modal-body">
	    <div class="row">
	      <div class="col-md-2 removeall">
	        <div class="form-group removemail">
	          <label>Tamaño Hoja</label>
	          <select class="sl2 form-control" name="formato[tamano]" style="width: 100%">
	            <option value="A4">A4</option>
	            <option value="A5">A5</option>
	            <option value="A6">A6</option>
	            <option value="A7">A7</option>
	            <option value="A8">A8</option>
	            <option value="A9">A9</option>
	            <option value="A10">A10</option>
	            <option value="LETTER">LETTER</option>
	            <option value="LEGAL">LEGAL</option>
	            <option value="EXECUTIVE">EXECUTIVE</option>
	            <option value="FOLIO">FOLIO</option>
	            <option value="B0">B0</option>
	            <option value="B1">B1</option>
	            <option value="B2">B2</option>
	            <option value="B3">B3</option>
	            <option value="B4">B4</option>
	            <option value="B5">B5</option>
	            <option value="B6">B6</option>
	            <option value="B7">B7</option>
	            <option value="B8">B8</option>
	            <option value="B9">B9</option>
	            <option value="B10">B10</option>
	            <option value="C0">C0</option>
	            <option value="C1">C1</option>
	            <option value="C2">C2</option>
	            <option value="C3">C3</option>
	            <option value="C4">C4</option>
	            <option value="C5">C5</option>
	            <option value="C6">C6</option>
	            <option value="C7">C7</option>
	            <option value="C8">C8</option>
	            <option value="C9">C9</option>
	            <option value="C10">C10</option>
	            <option value="0">Personalizado</option>
	          </select>
	        </div>
	        <div class="form-group removepos">
	          <label>Orientación Hoja</label>
	          <select class="sl2 form-control" name="formato[posicion]" style="width:100%">
	            <option value="">VERTICAL</option>
	            <option value="-L">HORIZONTAL</option>
	          </select>
	        </div>
	        <div class="form-group remover">
	          <label>Ancho <small>personalizado</small> (mm)</label>
	          <input type="text" class="form-control" pattern="[0-9]+" name="formato[ancho]" value="<?= $formato->ancho; ?>">
	        </div>
	        <div class="form-group removepos">
	          <label>Alto <small>personalizado</small> (mm)</label>
	          <input type="text" class="form-control" pattern="[0-9]+" name="formato[alto]" value="<?= $formato->alto; ?>">
	        </div>
	        <button type="submit" class="removemail btn-azul m-b-15 btnsaveformato"><i class="far fa-save"></i> Guardar Cambios</button>
	      </div>
	      <div class="col-md-10">
	        <div class="form-group">
	          <input class="form-control" type="hidden" name="formato[asuntofactura]" value="<?= $formato->asuntofactura; ?>">
	          <input type="hidden" name="formato[id]" value="<?= $formato->id; ?>">
	          <input type="hidden" name="formato[tipoaviso]" value="<?= $formato->tipoaviso; ?>">
	          <textarea id="formatoeditor" name="formato[html]"><?= $formato->html; ?></textarea>
	        </div>
	      </div>
	    </div>
	</form>

	<script>
	  function newcontrato() {
	    $('.btnsaveformato').prop('disabled', true);
	    $('input[name="tipocontrato"]').val('nuevocontrato');
	    $("#form-editor").submit();
	  }
	  $(function() {
	    $('select[name="formato\[tamano\]"] option[value="<?= $formato->tamano; ?>"]').prop('selected', true);
	    $('select[name="formato\[posicion\]"] option[value="<?= $formato->posicion; ?>"]').prop('selected', true);
	    $('select[name="formato\[tamano\]"]').change(function() {
	      if ($(this).val() == '0') {
	        $('input[name="formato\[ancho\]"]').prop("disabled", false);
	        $('input[name="formato\[alto\]"]').prop("disabled", false);
	      } else {
	        $('input[name="formato\[ancho\]"]').prop("disabled", true);
	        $('input[name="formato\[alto\]"]').prop("disabled", true);
	      }
	    });
	    $('select[name="formato\[tamano\]"]').trigger('change');

	    $('#modaltmp').modal('show');
	    CKEDITOR.replace('formatoeditor', {
	      height: '500px',
	      toolbarGroups: [{
	          name: 'document',
	          groups: ['mode', 'document', 'doctools', 'save']
	        },
	        {
	          name: 'clipboard',
	          groups: ['clipboard', 'undo']
	        },
	        {
	          name: 'editing',
	          groups: ['find', 'selection', 'spellchecker', 'editing']
	        },
	        {
	          name: 'forms',
	          groups: ['forms']
	        },
	        {
	          name: 'basicstyles',
	          groups: ['basicstyles', 'cleanup']
	        },
	        {
	          name: 'paragraph',
	          groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']
	        },
	        {
	          name: 'links',
	          groups: ['links']
	        },
	        {
	          name: 'insert',
	          groups: ['insert']
	        },
	        {
	          name: 'styles',
	          groups: ['styles']
	        },
	        {
	          name: 'colors',
	          groups: ['colors']
	        },
	        {
	          name: 'tools',
	          groups: ['tools']
	        },
	        {
	          name: 'others',
	          groups: ['others']
	        },
	        {
	          name: 'about',
	          groups: ['about']
	        }
	      ],
	      removeButtons: 'NewPage,Print,Templates,Find,Scayt,Form,Checkbox,Radio,TextField,Textarea,Button,Select,ImageButton,HiddenField,Superscript,Subscript,CreateDiv,BidiLtr,BidiRtl,Language,Anchor,Flash,Smiley,SpecialChar,Iframe,ShowBlocks,About,Styles,HorizontalRule,Indent,Outdent,Strike,SelectAll'
	    }).on('contentDom', function(e) {
	      $('#cke_formatoeditor .cke_button__save').removeAttr("onclick");
	      $('#cke_formatoeditor .cke_button__save').removeAttr("onkeydown");
	      $('#cke_formatoeditor .cke_button__save').removeAttr("onfocus");
	      $('#cke_formatoeditor .cke_button__save').attr('onclick', '$(\"#form-editor\").submit();');
	    });
	    CKEDITOR.on('instanceReady', function(ev) {
	      var blockTags = ['td', 'tr'];
	      for (var i = 0; i < blockTags.length; i++) {
	        ev.editor.dataProcessor.writer.setRules(blockTags[i], {
	          indent: false,
	          breakBeforeOpen: false,
	          breakAfterOpen: false,
	          breakBeforeClose: false,
	          breakAfterClose: false
	        });
	      }
	    });
	    $('.sl2').select2();
	    $('#form-editor').on("submit", function() {
	      alerta('loader', 'Guardando..');
	      $('#modaltmp').modal('hide');
	      $('.btnsaveformato').prop('disabled', true);
	      $('#formatoeditor').val(CKEDITOR.instances['formatoeditor'].getData());
	    })
	    $('#form-editor').ajaxForm({
	      success: function(data) {
	        console.log(data);
	        if (data == 1) {
	          $('#gritter-notice-wrapper').remove();
	          alerta('exito', 'Plantilla guardada correctamente');
	          /* $('#default-tab-1').append(data); */
	          for (name in CKEDITOR.instances) {
	            CKEDITOR.instances[name].destroy()
	          }
	        } else {
	          alerta('error', 'Error al guardar.');
	        }
	      },
	      error: function(response) {
	        $('#gritter-notice-wrapper').remove();
	        alerta('error500', response['responseText']);
	      }
	    })
	  })
	</script>