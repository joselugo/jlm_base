<div class="m-5">
  <div class="panel panel-primary">
    <div class="panel-heading">Ultimas acciones</div>
    <div class="panel-body">

      <table id="myhistory" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline collapsed">
        <thead>
          <tr>
            <th>Fecha</th>
            <th>Detalle</th>
            <th>Operacion</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($historial as $historia) : ?>
            <tr>
              <td><?= date('Y/m/d h:m A', strtotime($historia->fechalog)); ?></td>
              <td><?= $historia->log; ?></td>
              <td><?= $historia->tipo; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $(function() {
      $('#modaltmp').modal('show');
      configtable('#myhistory', 'myhistory');
      historial = $('#myhistory').DataTable({
        "idDataTables": "160",
        "ajax": "fiber/get_placas?id=<?= $id ?>",
        "deferRender": true,
        "aoColumns": [{
          mData: 'id',
          sWidth: "10px"
        }, ]
      }).on('draw', function() {
        //ELIMINADO;
      });

    }) $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();

  });
</script>