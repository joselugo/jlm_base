<div class="modal-body" style="width:100%">

  <div class="invoice">
    <style type="text/css">
      .table-invoice tbody>tr>td {
        vertical-align: top;
        border-bottom: 1px solid #e2e7eb;
        padding-top: 5px;
        padding-bottom: 5px;
      }
    </style>
    <div class="invoice-header" style="margin:0; margin-bottom:10px; padding-bottom:5px">
      <div class="invoice-from">
        <small>De</small>
        <address class="m-t-5 m-b-5">
          <strong><?= $emisor[1]->value; ?></strong><br>
          <?= $emisor[0]->value; ?><br>
          Teléfono: <?= $emisor[3]->value; ?><br>
        </address>
      </div>
      <div class="invoice-to">
        <small>Para</small>
        <address class="m-t-5 m-b-5">
          <strong><?= $factura->nombre; ?></strong><br>
          <?= $factura->direccion_principal; ?><br>
          Teléfono: <?= $factura->telefono; ?><br>
          Móvil: <?= $factura->movil; ?> </address>
      </div>
      <div class="invoice-date">
        <small>Emisión <?= $factura->emitido; ?></small>
        <div class="date m-t-5"><?= $factura->vencimiento; ?></div>
        <div class="invoice-detail">
          <b>Nº <?= $factura->id; ?></b>
        </div>
      </div>
    </div>

    <div class="invoice-content">
      <div class="table-responsive" style="min-height:200px;">
        <table class="table table-invoice">
          <thead>
            <tr>
              <th width="60%">Descripción</th>
              <th>Precio</th>
              <th width="60px">Imp %</th>
              <th width="58px">Cant.</th>
              <th>Total</th>
            </tr>

          </thead>
          <tbody>
            <?php
            $subtotal = 0;
            $impuesto = 0;
            $total = 0;
            foreach ($items as $item) : ?>
              <?php
              $descripcion = "";
              $descripcion = str_replace("\n", '<br>', $item->descripcion);
              if ($factura->impuesto=="SI") {
                $subtotal+=$item->cantidad;
                $impuesto+=($item->impuesto*$item->cantidad)/100;
              }elseif($factura->impuesto == "NO"){
                $subtotal = $subtotal + ($item->cantidad / (1 + ($item->impuesto / 100)));
                $impuesto = $impuesto - (($item->cantidad / (1 + ($item->impuesto / 100))) - $item->cantidad);

              }else{
                $subtotal += $item->cantidad;
                $impuesto=0;
              }
              $total += $item->cantidad;
              ?>
              <tr>
                <td>
                  <?= $descripcion; ?>
                </td>
                <td>MX$<?= $item->cantidad; ?></td>
                <td><?= $item->impuesto; ?></td>
                <td><?= $item->unidades; ?></td>
                <td>MX$<?= $item->cantidad; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="invoice-price">
        <div class="invoice-price-left">
          <div class="invoice-price-row">
            <div class="sub-price">
              <small>SUBTOTAL</small>
              MX$<?= number_format($subtotal, 2); ?> </div>
            <div class="sub-price">
              <i class="fas fa-plus"></i>
            </div>
            <div class="sub-price">
              <small>IMPUESTOS</small>
              <?php
              if ($factura->impuesto == "SI") {
                $total+=$impuesto;
              }
              ?>
              MX$<?= number_format($impuesto, 2); ?> </div>
            <div class="sub-price">
              <i class="fas fa-plus"></i>
            </div>
            <div class="sub-price">
              <small>OTROS IMPUESTOS</small>
              <?php
              $otros_impuestos = explode(";", $factura->otros_impuestos, -1);
              $total_otros_impuestos = 0;
              foreach ($otros_impuestos as $impuesto) {
                $total_otros_impuestos += $impuesto;
              }
              ?>
              MX$<?= number_format($total_otros_impuestos, 2); ?> </div>
            <div class="sub-price">
              <i class="fas fa-minus"></i>
            </div>
            <div class="sub-price">
              <small>DESCUENTO</small>
              MX$<?= number_format($descuento, 2); ?> </div>
          </div>
        </div>
        <div class="invoice-price-right">
          <?php
          $total += $total_otros_impuestos;
          $total -= $descuento;


          ?>
          <small>TOTsAL</small> MX$<?= number_format($total, 2); ?> </div>
      </div>
    </div>


  </div>
</div>
<script>
  $(function() {
    $('#modaltmp').modal('show');
  })
</script>