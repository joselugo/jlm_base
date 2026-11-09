<div class="rowfc">
  <table id="data-saldos" class="display nowrap table table-striped table-bordered" cellspacing="0" width="100%" data-order="[[ 0, &quot;desc&quot; ]]">
    <thead>
      <tr>
        <th>ID</th>
        <th style="max-width:80px !important">Factura origen</th>
        <th style="max-width:80px !important">Factura destino</th>
        <th>Monto</th>
        <th>Fecha</th>
        <th>Descripción</th>
        <th>Estado</th>
        <th class="all" style="max-width:20px !important" data-orderable="false"></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($saldos as $saldo) : ?>
        <tr>
          <td><?= $saldo->id; ?></td>
          <td>
            <?php
            if ($saldo->idorigen != 0) {
              $origen = "<a onclick=\"getmodal('clientes_facturacion/ver_factura?id=" . $saldo->idorigen . "','Ver Factura','lg');\">" . $saldo->idorigen . "</a>";
            } else {
              $origen = "N/A";
            }; ?>
            <?= $origen; ?>
          </td>
          <td>
            <?php
            if ($saldo->iddestino != 0) {
              $destino = "<a onclick=\"getmodal('clientes_facturacion/ver_factura?id=" . $saldo->iddestino . "','Ver Factura','lg');\">" . $saldo->iddestino . "</a>";
            } else {
              $destino = "N/A";
            };
            ?>
            <?= $destino; ?>
          </td>
          <td data-order="-2000">MX$<?= $saldo->monto; ?></td>
          <td data-order="1533531600"><?= $saldo->fecha; ?></td>
          <td><?= $saldo->descripcion; ?></td>
          <?php
          $estado = $saldo->estado;
          if ($estado == "no cobrado") {
            $css = "danger";
            $txt = "NO FACTURADO";
            if ($permiso2==1) {
              $tool = "<a data-toggle=\"tooltip\" title=\"\" class=\"btn btn-default btn-icon btn-sm\" onclick=\"delete_saldo('$saldo->id','$idcliente')\" data-original-title=\"Eliminar\">
              <i class=\"far fa-trash-alt\" aria-hidden=\"true\"></i>
            </a>";
            }else{
              $tool="";
            }
          }
          if ($estado == "facturado") {
            $css = "success";
            $txt = "FACTURADO";
            $tool = "";
          }
          ?>
          <td><span class="label label-<?= $css ?>"><?= $txt; ?></span></td>
          <td>
            <?= $tool; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>



<script>
  function delete_saldo(id, idcliente) {

    $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: 'Esta por eliminar un saldo No facturado.',
      title: '<i class="fas fa-question-circle fa-lg icodialog"></i> Eliminar Saldo',
      buttons: {
        Eliminar: {
          text: '<i class="far fa-trash-alt icodialog"></i> Eliminar saldo',
          action: function() {
            $.post("clientes_facturacion/delete_saldo", {
                id,
                idcliente
              })
              .done(function(data) {
                $('a[href="#nav-saldos"]').trigger('click');
                alerta('exito', 'saldo eliminado correctamente')
              });

          }
        },
        Cancelar: {}
      }
    });


  }

  $(function() {

    configtable('#data-transaccion', 'Transacciones');
    $('#data-saldos').DataTable({
      "idDataTables": "40",
      initComplete: function(oSettings, json) {
        if (<?= $permiso2; ?> == 1) {
          $("#data-saldos_wrapper div.tooltablas")
            .html('<div class="grupotabla btn-group">\
  		<button type="button" class="btn btn-white" Onclick="getmodal(\'clientes_facturacion/new_saldo?idcliente=<?= $idcliente; ?>\',\'Saldo cliente\',\'sm\');"><i class="fas fa-plus"></i> Agregar saldo</button>\
		</div>');
        }
      }
    });


  })
</script>