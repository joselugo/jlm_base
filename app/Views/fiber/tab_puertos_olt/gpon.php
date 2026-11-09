<div class="rowfc">
<table id="data-gpon" class="display nowrap table table-striped table-bordered dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%" data-order="[[ 0, &quot;desc&quot; ]]" style="width: 100%;">
<thead>
<tr role="row">
  <th class="all sorting_asc" style="max-width: 60px !important; width: 74px;" >ID</th>
  <th class="sorting" >Nombre</th>
  <th class="sorting">Ranura</th>
  <th class="sorting">Puerto</th>
  <th class="all sorting">Señal</th>
  <th class="all sorting">Estado/Operación</th>
  <th class="sorting">Onus OLT</th>
  <th class="sorting">Onus Sistema</th>
  <th class="sorting">Tipo</th>
   <th class="sorting">Ancho Banda Disponible UP</th>
  <th class="all sorting_disabled" style="max-width: 20px !important; width: 90px;" data-orderable="false"></th>
</tr>
</thead>


</table>


</div>

<script>

configtable('#data-gpon','Gpon');
var gpon = $('#data-gpon').DataTable({
    "ajax": "fiber/get_gpon_port?id=<?=$id?>",
    "deferRender": true,
    "aoColumns": [
      {
        mData: 'id',
        sWidth: "10px"
      },
      {
        mData: 'nombre'
      },
      {
        mData: 'ranura'
      },
      {
        mData: 'puerto'
      },
      {
        mData: 'senial'
      },
      {
        mData: 'estado'
      },
      {
        mData: 'onus_olt',
        sClass: 'text-center'
      },
      {
        mData: 'onus_sistema',
        sClass: 'text-center'
      },
      {
        mData: 'tipo',
        sClass: 'text-center'
      },
      {
        mData: 'avaliable_bandwith',
        sClass: 'text-center'
      },
      {
        mData: 'tool',
        sClass: 'text-center'
      }
    ],
    "drawCallback": function(settings) {
    //  loaderout('.panel-router');
    },

    initComplete: function(oSettings, json){
     
      $("#data-gpon_wrapper div.tooltablas")
         .html('<div class="grupotabla btn-group">\
      <button type="button" class="btn btn-white" Onclick="getmodal(\'ajax/viewuser?action=newlibre&id=3\',\'Nueva Factura Libre\',\'lg\');"><i class="fas fa-plus"></i> Actualizar</button>\
    <button type="button" class="btn btn-white" Onclick="getmodal(\'ajax/viewuser?action=newservicio&id=3\',\'Nueva Factura Servicio\',\'lg\');"><i class="fas fa-plus"></i> Activar Puertos</button>\
    <button type="button" class="btn btn-white" Onclick="getmodal(\'ajax/viewuser?action=newservicio&id=3\',\'Nueva Factura Servicio\',\'lg\');"><i class="fas fa-plus"></i> Activar Autofind</button>\
    </div>');
      }
});

  
</script>