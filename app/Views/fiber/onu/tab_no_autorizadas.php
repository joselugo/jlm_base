<table id="data-no-autorizadas" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%" style="width: 100%;">
  <thead>
    <tr>
      <th class="column">OLT</th>
      <th class="column" data-orderable="false">Interfaz</th>
      <th class="column">Puerto</th>
      <th class="column">SN</th>
      <th class="column">Modelo</th>
      <th style="width: 50px; max-width: 50px;" class="column all">Acción</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($onus as $key => $value):?>
    <tr>
      <td><?= $value['olt'];?></td>
      <td><?= $value['interface']; ?></td>
      <td><?= $value['puerto'] ?></td>
      <td><?= $value['sn']?></td>
      <td><?= $value['modelo']?></td>
      <td style="text-align: center;"><?= $value['tool']?></td>
    </tr>
    <?php endforeach;?>
  </tbody>
  
</table>
<script>
$(document).ready(function() {

  configtable('#data-no-autorizadas', 'No Autorizadas')

  $('#data-no-autorizadas').DataTable();


});

function autorize_onu(sn,idpon,idolt){
  loadurl("#fiber/autorize_onu/"+sn+"/"+idpon+"/"+idolt);
}
</script>
