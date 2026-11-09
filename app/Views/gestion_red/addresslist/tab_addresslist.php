 <div class="row">
  <div class="col-md-6">
    <div class="form-group row">
      <label class="col-md-4 col-form-label text-right"></label>
      <div class="col-md-8">
      <select class="form-control select2" name="nodo">
        <option></option>
        <?=$servers?>
      </select>
      <br>
    </div>
    </div>
  </div>

  <div class="col-md-12">
<table id="list-address" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%" style="width: 100%;">
<thead >
<tr>
<th  class="column">Cliente</th>
<th  class="column">Id servicio</th>
<th  class="column">Ip</th>
<th  class="column">AdressList</th>
<th  class="column">Comment</th>
<th class="all column" data-orderable="false" style="max-width:40px;width: 40px">Acción</th>
</tr>
</thead>
</table>
  
   
  </div>

</div>


<script>
$(function(){

//-------> LISTA DE ACTIVOS
configtable('#list-address','addresslist');
addresslist=$('#list-address').DataTable( {
"language": {
 "url": "<?=base_url();?>public/js/Datatables-es.json"
        },
"processing": true,
"ajax": "gestion_red/get_addresslist?id=-1",
"deferRender": true,
"aaSorting": [[0,'desc']],
"aoColumns": [
{ mData: 'cliente'},
{ mData: 'idservicio'},
{ mData: 'address'},
{ mData: 'list'},
{ mData: 'comment'},
{ mData: 'tool'}
],
"fnDrawCallback": function( oSettings ) {
},
"initComplete": function() {}
  
 });

//--------> UPDATE 
updatelist=function(){
addresslist.ajax.url("gestion_red/get_addresslist?id="+$('select[name="nodo"]').val()).load();
}

 $('select[name="nodo"]').select2({placeholder: "Selecciona un nodo",allowClear: true});

 $('select[name="nodo"]').change(function() {
  addresslist.ajax.url("gestion_red/get_addresslist?id="+$('select[name="nodo"]').val()).load();
});

});


</script>
