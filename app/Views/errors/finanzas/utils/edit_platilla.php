	<form id="form-editor" method="post" action="ajax/ajustes?action=saveformato">
		<div class="modal-body">
			<div class="row">

				<div class="col-md-2 removeall"><input class="form-control" type="hidden" name="formato[asuntofactura]" value="Fomato Factura PDF">
					<div class="form-group removemail">
						<label>Tamaño Hoja</label>
						<select class="sl2 form-control" name="formato[tamano]" style="width: 100%">
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
						<input type="text" class="form-control" pattern="[0-9]+" name="formato[ancho]" value="0">
					</div>

					<div class="form-group removepos">
						<label>Alto <small>personalizado</small> (mm)</label>
						<input type="text" class="form-control" pattern="[0-9]+" name="formato[alto]" value="0">
					</div>

					<button type="submit" class="removemail btn-azul m-b-15 btnsaveformato"><i class="far fa-save"></i> Guardar Cambios</button>
				</div>
				<div class="col-md-10">
					<div class="form-group">
						<input type="hidden" name="formato[id]" value="8">
						<input type="hidden" name="formato[tipoaviso]" value="factura">
						<textarea id="formatoeditor" name="formato[html]"><html>
<head>
	<title></title>
</head>
<body>
<style type="text/css">
*{
	border: 0;
	box-sizing: content-box;
	color: inherit;
	font-family: inherit;
	font-size: inherit;
	font-style: inherit;
	font-weight: inherit;
	line-height: inherit;
	list-style: none;
	margin: 0;
	text-decoration: none;
}

h3{
font-size:16px;
font-weight:bold;
}


body{
width:100%;
height:100%;
}

body,td,th {
font-family: Arial;
font-size: 12px;
}


.table-invoice {
font-size: 85%;
table-layout: fixed;
border-collapse: collapse;
width:100%;
}

.border{
border-right: 1px solid #d9e8ed ;
}

.table-invoice td {
padding:7px 4px;
border-bottom: 1px solid #d9e8ed;
}

.table-invoice th {
	background:#eef2f3;
	font-weight:bold;
	height:30px;
	vertical-align:middle;
}
.invoice-content{
border:1px solid #d9e8ed;
margin:5px 10px;
min-height:100px;
display:inline-block;
}

.monto-letras{
font-weight:bold;
padding:10px;
padding-top:20px;
background: #eef2f3;
}

.table-total{
font-size: 85%;
table-layout: fixed;
border-collapse: collapse;
width:100%;
}

.table-total td {
padding:5px 5px;
border-bottom: 1px solid #d9e8ed;
}

.content-total{
margin:0px 10px;	
}
.bold,b{
	font-weight:bold;
}
.header{
margin:10px;		
}
.nopagado,.estadocss{
background: #ff5356;
    font-size: 18px;
    font-weight: bold;
    text-transform: uppercase;
   width: 100%;
    padding: 3px;
    color: #fff;
    right: 10px;
    text-align: center;
    margin-bottom: 5px;
}

.pagado{
background: #06C393;
    font-size: 18px;
    font-weight: bold;;
    text-transform: uppercase;
    width: 100%;
    padding: 3px;
    color: #fff;
    right: 10px;
    text-align: center;
    margin-bottom: 5px;
}


.anulado{
background: #e4b932;
    font-size: 18px;
    font-weight: bold;
    text-transform: uppercase;
    width: 100%;
    padding: 3px;
    color: #fff;
    right: 10px;
    text-align: center;
    margin-bottom: 5px;
}

.col{
min-height:30px;
width: 46%;
float:left;
padding:10px;
margin-top:5px;
}

.mayu{
text-transform:uppercase;
}
.table-invoice .pad{
padding:5px 30px 5px 10px !important;
}

.text{
color:#565656;
line-height:inherit;
}
p{
margin: 0px;
margin-bottom: 2px;
}
</style>
<div class="header">
<div class="estadocss">{estado}</div>

<table autosize="2" border="0" cellpadding="0" cellspacing="0" style="border-bottom:1px solid #d9e8ed;" width="100%">
	<tbody><tr><td align="left" valign="middle" width="60%"><img src="images/logofactura.png" style="height: 70px;" /></td><td style="padding-right:5px;" width="50%">
		<table border="0" width="100%">
			<tbody><tr><td align="right">
				<h3>FACTURA # {nfactura}</h3>
				</td></tr><tr><td align="right">Fecha emisi&oacute;n <b>{emitido}</b></td></tr><tr><td align="right">Fecha vencimiento <b>{vencimiento}</b></td></tr>
			</tbody>
		</table>
		</td></tr>
	</tbody>
</table>

<div class="col border"><b>De</b>

<p class="text mayu">{nombre_empresa}</p>

<p class="text">Ruc {ruc_empresa}</p>

<p class="text">{direccion_empresa}</p>

<p class="text">Tel&eacute;fono {telefono_empresa}</p>
</div>

<div class="col"><b>Para</b>

<p class="text mayu">{nombre_cliente}</p>

<p class="text">{cedula_cliente}</p>

<p class="text">{direccion_principal_cliente}</p>

<p class="text">Tel&eacute;fono {telefono_cliente} / {movil_cliente}</p>
</div>
</div>

<div class="invoice-content">
<table autosize="2" class="table-invoice">
	<thead><tr>
		<th>Descripci&oacute;n</th>
		<th width="100px">Precio</th>
		<th width="45px">Imp%</th>
		<th width="25px">Cant.</th>
		<th width="100px">Total</th>
		</tr>
	</thead>
	<tbody><!-- TAG PAR MOSTRAR CONTENIDO FACTURA--><!-- 
tag: {items} ->Muestra todas las columnas (descripcion,precio,impuesto,cantidad y total)
tag: {items2} ->Muetra la colunmas descripcion y total
tag: {items3} ->Muestra solo la descripcion
<tr>
<td>{items}</td>
</tr>
--><tr><td>{items}</td></tr><!-- FIN TAG-->
	</tbody>
</table>

<div class="monto-letras">SON: {total_letras}</div>
</div>

<div class="content-total">
<table autosize="2.4" class="table-total">
	<tbody><tr><td align="center"><barcode code="{barcode}" height="3" size="0.75" type="EAN128A"></barcode>

		<div style="font-family: ocrb;font-size:14px">{barcode}</div>
		</td><td align="right" width="215px">
		<table>
			<tbody><tr><td align="right" class="bold" width="105px">SUBTOTAL :</td><td align="left" width="100px">{subtotal}</td></tr><tr><td align="right" class="bold" width="95px">IMPUESTO :</td><td align="left" width="100px">{impuesto}</td></tr><tr class="otrosimpuestos1"><td align="right" class="bold" width="95px">OTRO IMP. 1 :</td><td align="left" width="100px">{otro_impuesto_1}</td></tr><tr class="otrosimpuestos2"><td align="right" class="bold" width="95px">OTRO IMP. 2 :</td><td align="left" width="100px">{otro_impuesto_2}</td></tr><tr class="otrosimpuestos3"><td align="right" class="bold" width="95px">OTRO IMP. 3 :</td><td align="left" width="100px">{otro_impuesto_3}</td></tr><tr><td align="right" class="bold" width="95px">DESCUENTO :</td><td align="left" width="100px">{descuento}</td></tr><tr><td align="right" class="bold" width="95px">TOTAL :</td><td align="left" width="100px">{total}</td></tr>
			</tbody>
		</table>
		</td></tr>
	</tbody>
</table>
</div>

<p>{operaciones}</p>
</body>
</html>
</textarea>
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



			$('select[name="formato\[tamano\]"] option[value="A4"]').prop('selected', true);
			$('select[name="formato\[posicion\]"] option[value="-L"]').prop('selected', true);
			$('.sl2').select2();



			$('#form-editor').on("submit", function() {
				alerta('loader', 'Guardando..');
				$('#modaltmp').modal('hide');
				$('.btnsaveformato').prop('disabled', true);
				$('#formatoeditor').val(CKEDITOR.instances['formatoeditor'].getData());
			})

			$('#form-editor').ajaxForm({
				success: function(data) {

					if (data) {
						$('#default-tab-1').append(data);
					}

					$('#gritter-notice-wrapper').remove();
					alerta('exito', 'Plantilla guardada correctamente');
					for (name in CKEDITOR.instances) {
						CKEDITOR.instances[name].destroy()
					}
				},
				error: function(response) {
					$('#gritter-notice-wrapper').remove();
					alerta('error500', response['responseText']);
				}
			})


		})
	</script>