<div class="panel panel-blue panel-ipv4">
	<div class="panel-heading">
		<h4 class="panel-title">Redes IPv4</h4>
		<div class="panel-heading-btn">
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fas fa-expand"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onClick="updateipv4()"><i class="fas fa-sync-alt"></i></a>
			<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-collapse"><i class="fas fa-minus"></i></a>
		</div>

	</div>
	<div class="panel-body">

		<table id="data-ipv4" class="display nowrap table table-striped table-bordered table-hover" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th class="all">ID</th>
					<th class="all">Nombre</th>
					<th>Red</th>
					<th>Uso Ips</th>
					<th>CIDR</th>
					<th>Router</th>
					<th>Tipo</th>
					<th class="all" style="max-width:20px !important" data-orderable="false"></th>
				</tr>
			</thead>
		</table>

	</div>
</div>


<script>
	function removeipv4(id) {
		$.confirm({
			theme: 'supervan',
			draggable: false,
			closeIcon: true,
			animationBounce: 2.5,
			escapeKey: false,
			content: 'Solo se podrá eliminar una red IPv4 si no hay clientes utilizando esta red',
			title: '<i class="far fa-question-circle fa-lg icodialog"></i> Eliminar Red Ipv4',
			buttons: {
				Eliminar: {
					text: '<i class="far fa-trash-alt icodialog"></i> Eliminar', // With spaces and symbols
					action: function() {
						$.post("gestion_red/delete_red_ipv4", {
								id: id,
							})
							.done(function(data) {
								data = JSON.parse(data);
								if (data.error == 1) {
									alerta('error', data.mensaje);
								} else {
									alerta('exito', "Exito al eliminar.");
									updateipv4();
								}
							});

					}
				}
			}
		});
	}

	$(function() {
		configtable('#data-ipv4', 'Ipv4');
		var ipv4 = $('#data-ipv4').DataTable({
			"ajax": "gestion_red/redes_ipv4_json",
			"deferRender": true,
			"idDataTables": "40",
			"aoColumns": [{
					mData: 'id',
					sWidth: "10px"
				},
				{
					mData: 'nombre'
				},
				{
					mData: 'red',
					sType: 'ip-address'
				},
				{
					mData: 'uso',
					sClass: 'text-center'
				},
				{
					mData: 'cidr',
					sWidth: "10px"
				},
				{
					mData: 'nodo',
					sClass: 'text-center'
				},
				{
					mData: 'tipo',
					sClass: 'text-center',
					'render': function(data, type, full, meta) {
						if (data == 0) {
							return '<span class="label label-pill label-success">ESTÁTICO</span>';
						} else {
							return '<span class="label label-pill label-warning">POOL</span>';
						}

					}
				},

				{
					mData: 'tool',
					sClass: 'text-center',
					'render': function(data, type, full, meta) {

						var ed = '';
						var es = '';
						var re = '';
						if (full.permiso >= 2) {
							re = '<a data-toggle="tooltip" title="Eliminar" class="btn btn-default btn-icon btn-sm" Onclick="removeipv4(\'' + data + '\');"><i class="far fa-trash-alt"></i></a>';
						}
						ed = '<a data-toggle="tooltip" title="Editar" class="btn btn-default btn-icon btn-sm" Onclick="getmodal(\'gestion_red/modal_edit_red_ipv4?id=' + data + '\',\'Editar Red IPv4\',\'md\');"><i class="far fa-edit"></i></a>';
						/* es = '<a href="#gestion_dsred/klfsdjljf?id=' + data + '"  data-toggle="tooltip" title="Escanear red" class="btn btn-default btn-icon btn-sm"><i class="fas fa-sitemap"></i></a>'; */
						return ed + '<a data-toggle="tooltip" title="Detalles de Red" class="btn btn-default btn-icon btn-sm" Onclick="getmodal(\'gestion_red/modal_detalle_red_ipv4?id=' + data + '\',\'Detalles Red IPv4\',\'lg\');"><i class="fas fa-info-circle"></i></a>' + es + re;
					}
				}
			],
			"drawCallback": function(settings) {
				loaderout('.panel-ipv4');
			},
			initComplete: function(oSettings, json) {
				if (<?= $permiso; ?> >= 2) {
					$("#data-ipv4_wrapper div.tooltablas")
						.html('<div class="grupotabla btn-group">\
  		<button type="button" class="btn btn-success" Onclick="getmodal(\'gestion_red/modal_new_red_ipv4\',\'Nueva Red IPv4\',\'md\');"><i class="fas fa-plus"></i> Nuevo</button>\
		</div>');
				}
			}
		});

		updateipv4 = function() {
			loaderin('.panel-ipv4');
			ipv4.ajax.reload(null, false);
		}

	})

	App.restartGlobalFunction();
	App.setPageTitle('Lista IPv4');
</script>