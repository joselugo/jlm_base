<table id="tablalog" class="dt-responsive table table-striped table-bordered table-hover" data-page-length="15"
  cellspacing="0" width="100%" data-order="[[ 0, &quot;desc&quot; ]]">
  <thead>
    <tr>
      <th>ID</th>
      <th data-orderable="false">Fecha</th>
      <th>Tipo</th>
      <th>Log</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($logs as $key => $value):?>
    <tr>
      <td><?= $key+1 ?></td>
      <td><?= $value['fecha']; ?></td>
      <td><?= $value['tipo'] ?></td>
      <td><?= $value['log']?></td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>
<script>
$(document).ready(function() {

  configtable('#tablalog', 'Log Mikrotik')

  $('#tablalog').DataTable({});

  $(document).on("click", ".update-log", function(e) {
    $("#logrouter").trigger("click");
  });

});
</script>
