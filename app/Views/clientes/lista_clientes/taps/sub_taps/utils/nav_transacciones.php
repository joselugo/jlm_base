<div class="rowfc">
  <table id="data-transaccion" class="display nowrap table table-striped table-bordered" cellspacing="0" width="100%" data-order="[[ 0, &quot;desc&quot; ]]">
    <thead>
      <tr>
        <th>ID</th>
        <th style="max-width:60px !important">Nº Factura</th>
        <th>Fecha pago</th>
        <th>Total pagado</th>
        <th>Comisión</th>
        <th>saldo</th>
        <th>Nº transacción</th>
        <th>Meses</th>
        <th>Forma pago</th>
        <th>Operador</th>
        <th class="all" style="max-width:20px !important" data-orderable="false"></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($operaciones as $operacion) : ?>
        <tr>
          <td><?= $operacion->id; ?></td>
          <td>
            <a onclick="getmodal('clientes_facturacion/ver_factura?id=<?= $operacion->nfactura; ?>','Ver Factura','lg');"><?= $operacion->nfactura; ?></a>
          </td>
          <td data-order="<?= strtotime($operacion->fecha_pago); ?>"><?= $operacion->fecha_pago; ?></td>
          <td data-order="<?= $operacion->cobrado; ?>">MX$<?= round($operacion->cobrado, 2); ?></td>
          <td data-order="<?= $operacion->comision; ?>"><?= $operacion->comision != 0 ? "MX$" . round($operacion->comision, 2) : "N/A"; ?></td>
          <td data-order="<?= $operacion->saldo; ?>"><?= $operacion->saldo != 0 ? "MX$" . round($operacion->saldo, 2) : "N/A"; ?></td>
          <td><?= $operacion->transaccion; ?></td>
          <td><?= $operacion->meses; ?></td>
          <td><?= $operacion->forma_pago; ?></td>
          <td><?= $operacion->operador; ?></td>
          <td>
            <?php if ($permiso2 >=3) : ?>
              <a data-toggle="tooltip" title="" class="btn btn-default btn-icon btn-sm" onclick="eliminar_pago('<?= $operacion->nfactura; ?>')" data-original-title="Eliminar">
                <i class="far fa-trash-alt" aria-hidden="true"></i>
              </a>
            <?php endif; ?>
            <a data-toggle="tooltip" title="" class="btn btn-default btn-icon btn-sm" onclick="getmodal('finanzas/viewuser?action=edittransaccion&origen=pagos&id=<?= $operacion->nfactura; ?>','Editar Transacción','sm')" data-original-title="Editar">
              <i class="far fa-edit"></i>
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>



<script>
  function eliminar_pago(fc) {

    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Al eliminar esta transacción la factura <b>Nº ' + fc + '</b> pasará como estado <b>NO PAGADO</b>, si hay saldos no procesados estos serán eliminados.',
      title: '<i class="fas fa-question-circle fa-lg icodialog"></i> Eliminar transacción',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt icodialog"></i> Eliminar pago',
          action: function() {
            $.post("finanzas/viewuser", {
                factura: fc,
                action: "deletetransaccion"
              })
              .done(function(data) {
                $('a[href="#nav-transacciones"]').trigger('click');
                alerta('exito', 'Pago eliminado correctamente')
              });

          }
        },
        Cancelar: {}
      }
    });


  }
  $(function() {

    configtable('#data-transaccion', 'Transacciones');
    var router = $('#data-transaccion').DataTable({
      "idDataTables": "40",
      columnDefs: [{
        targets: 7,
        visible: false,
      }]
    });


  })

  function clientes_trasacciones() {}
</script>