<ol class="breadcrumb pull-right">
        <li class="breadcrumb-item"><a href="/herramientas">Inicio</a></li>
        <li class="breadcrumb-item"><a href="/herramientas/#ajustes">Ajustes</a></li>
        <li class="breadcrumb-item active">Log</li>
</ol>

<h1 class="page-header">Log</h1>

<ul class="nav nav-tabs">
<li class="nav-items"><a href="#default-tab-1" data-toggle="tab" class="nav-link active"><i class="fas fa-desktop"></i> Log sistema</a></li>
<li class="nav-items"><a href="#default-tab-2" data-toggle="tab" class="nav-link"><i class="fas fa-key"></i> Log acceso</a></li>
</ul>


<div class="tab-content">
<div class="tab-pane fade active show" id="default-tab-1">

<table id="data-logsistema" data-order="[[ 0, &quot;desc&quot; ]]" class="display nowrap table table-striped table-bordered" cellspacing="0" width="100%">
<thead>
<tr>
<th style="width: 60px !important; max-width: 60px !important">Fecha</th>
<th>Cliente</th>
<th>Detalle</th>
<th>Operador</th>
</tr>
</thead>
</table>
</div>
	
<div class="tab-pane fade" id="default-tab-2">

<table id="data-loglogin" data-order="[[ 0, &quot;desc&quot; ]]" class="display nowrap table table-striped table-bordered" cellspacing="0" width="100%">
<thead>
<tr>
<th style="width: 60px !important; max-width: 60px !important">Fecha</th>
<th>IP</th>
<th>Usuario</th>
<th>Detalle</th>
</tr>
</thead>
</table>
	
</div>

</div>
     
</div>
</div>
</div>

<script>

var logsystemas,loglogin;	
//$(function(){
configtable('#data-logsistema','Log sistema');
logsystemas=$('#data-logsistema').DataTable({
"ajax": "ajustes/log_sistema_json?inicio=<?= $inicio;?>&final=<?= $final;?>",
"deferRender": true,
"processing": true,
"aoColumns": [
{ mData: 'fecha'},
{ mData: 'cliente'},
{ mData: 'log'},
{ mData: 'operador'}
],
"idDataTables": "40",
initComplete: function(oSettings, json){
	
$("#data-logsistema_wrapper div.tooltablas")
.html('<div class="grupotabla btn-group">\
<div class="logsys input-group input-daterange">\
<input style="max-width: 95px;" type="text" class="form-control" name="start" readonly placeholder="fecha inicio" value="<?= $inicio;?>">\
<span class="input-group-addon">al</span>\
<input  style="max-width: 95px;"  type="text" class="form-control" name="end" readonly placeholder="fecha final" value="<?= $final;?>">\
</div></div>');
initdatepicker();
}
})
	
configtable('#data-loglogin','Log sistema');
loglogin=$('#data-loglogin').DataTable({
"ajax": "ajustes/log_acceso?inicio=<?= $inicio;?>&final=<?= $final;?>",
"deferRender": true,
"aoColumns": [
{ mData: 'fecha',sWidth:'60px'},
{ mData: 'ip'},
{ mData: 'operador'},
{ mData: 'detalle'}
],
"idDataTables": "40",
initComplete: function(oSettings, json){
	
$("#data-loglogin_wrapper div.tooltablas")
.html('<div class="grupotabla btn-group">\
<div class="loglogin input-group input-daterange">\
<input style="max-width: 95px;" type="text" class="form-control" name="start2" readonly placeholder="fecha inicio" value="<?= $inicio;?>">\
<span class="input-group-addon">al</span>\
<input  style="max-width: 95px;"  type="text" class="form-control" name="end2" readonly placeholder="fecha final" value="<?= $final;?>">\
</div>\
</div>');
initdatepicker2();
}
})
	

function uplog(){
logsystemas.ajax.url("ajax/ajustes?action=listlog&inicio="+$('[name="start"]').val()+"&final="+$('[name="end"]').val()).load();
}


function initdatepicker2(){
$('.loglogin').datepicker({
todayHighlight: true,
autoclose: true,
language: "es"
}).on('changeDate', function (ev) {
$(this).datepicker('hide');
loglogin.ajax.url("ajustes/log_acceso?inicio="+$('[name="start2"]').val()+"&final="+$('[name="end2"]').val()).load();
});

}

function initdatepicker(){
$('.logsys').datepicker({
todayHighlight: true,
autoclose: true,
language: "es"
}).on('changeDate', function (ev) {
$(this).datepicker('hide');
logsystemas.ajax.url("ajustes/log_sistema_json?inicio="+$('[name="start"]').val()+"&final="+$('[name="end"]').val()).load();
});

}
	
App.setPageTitle('Configuración');	
</script>

