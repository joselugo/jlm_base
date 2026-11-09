<table id="lista-promesa" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%" data-order="[[ 0, &quot;desc&quot; ]]">
  <thead>
    <tr>
      <th>ID</th>
      <th>Cliente</th>
      <th># Factura</th>
      <th>Fecha</th>
      <th>Fecha Corte</th>
      <th>Operador</th>
      <th>Descripción</th>
    </tr>
  </thead>

  <tbody>
    <?php foreach ($promesas as $promesa) : ?>
      <tr>
        <td><?= $promesa->id; ?></td>
        <td><?= $promesa->cliente; ?></td>
        <td><?= $promesa->idfactura; ?></td>
        <td><?= $promesa->fechaingreso; ?></td>
        <td><?= $promesa->fecha_limite; ?></td>
        <td><?= $promesa->operador_nombre; ?></td>
        <td><?= $promesa->descripcion; ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>

</table>

<script>
  $(function() {
    configtable('#lista-promesa', 'Promesa de pago');
    transaccioneshoy = $('#lista-promesa').DataTable({
      "idDataTables": "40",
    }).on('draw', function() {
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
    });

  })
</script>