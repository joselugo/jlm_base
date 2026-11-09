 <div class="row">
  <div class="col-md-6">
    <div class="form-group row">
      <label class="col-md-4 col-form-label text-right"></label>
      <div class="col-md-8">
      <select class="form-control select2" name="nodo1">
        <option></option>
        <?=$servers?>
      </select>
      <br>
    </div>
    </div>
  </div>

  <div class="col-md-12">
<table id="list-leases" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%" style="width: 100%;">
<thead >
<tr>
<th  class="column">Cliente</th>
<th  class="column">Ip</th>
<th  class="column">Mac</th>
<th  class="column">AdressList</th>
<th  class="column">Ip activo</th>
<th  class="column">Mac activo</th>
<th  class="column">Status</th>
<th  class="column">Dinámico</th>
<th  class="column">Expira</th>
<th class="all column" data-orderable="false" style="max-width:40px;width: 40px">Acción</th>
</tr>
</thead>
</table>
  
   
  </div>

</div>


<script>
$(function(){

//-------> LISTA DE ACTIVOS
configtable('#list-leases','addresslist');
addresslist=$('#list-leases').DataTable( {
"language": {
 "url": "<?=base_url();?>public/js/Datatables-es.json"
        },
"processing": true,
"ajax": "gestion_red/get_leases?id=-1",
"deferRender": true,
"aaSorting": [[0,'desc']],
"aoColumns": [
{ mData: 'cliente'},
{ mData: 'address'},
{ mData: 'mac-address'},
{ mData: 'address-list'},
{ mData: 'active-address'},
{ mData: 'active-mac-address'},
{ mData: 'status'},
{ mData: 'dynamic'},
{ mData: 'expires-after'},
{ mData: 'tool'}
],
"fnDrawCallback": function( oSettings ) {
},
"initComplete": function() {}
  
 });

//--------> UPDATE 
updatelist=function(){
addresslist.ajax.url("gestion_red/get_leases?id="+$('select[name="nodo1"]').val()).load();
}

 $('select[name="nodo1"]').select2({placeholder: "Selecciona un nodo1",allowClear: true});

 $('select[name="nodo1"]').change(function() {
  addresslist.ajax.url("gestion_red/get_leases?id="+$('select[name="nodo1"]').val()).load();
});

});


</script>
