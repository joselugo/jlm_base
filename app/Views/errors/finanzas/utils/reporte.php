<table style="width: 100%;border: solid 1px;">
  <tbody>
    <tr>
      <td style="width: 20%;text-align: left;">
        <h3>Reporte de Transacciones</h3>
      </td>
      <td style="width: 60%;text-align: center;">
        <h2>Desde <?= $fecha; ?> hasta <?= $fecha; ?></h2>
      </td>
      <td style="width: 20%;text-align: right;"></td>
    </tr>
  </tbody>
</table>
<br>
<table class="table1">
  <thead>
    <tr>
      <th>
        Cliente
      </th>
      <th>
        Fecha pago
      </th>
      <th>
        Cobrado
      </th>
      <th>
        Comision
      </th>
      <th>
        Neto
      </th>
      <th>
        Meses
      </th>
      <th>
        ID Factura
      </th>
      <th>
        N°legal
      </th>
      <th>
        Cedula
      </th>
      <th>
        Plan
      </th>
      <th>
        Saldo
      </th>
      <th>
        Router
      </th>
      <th>
        Operador
      </th>
      <th>
        Detalles
      </th>
    </tr>
  </thead>
  <tbody>
    <?php
    $total_cobrado = 0;
    $total_comision = 0;
    $total_neto = 0;
    $total_saldos = 0;
    ?>
    <?php foreach ($data as $dato) : ?>
      <?php
      $total_cobrado += $dato->cobrado;
      $total_comision += $dato->comision;
      $total_neto += $dato->cobrado;
      $total_saldos += $dato->monto;
      foreach ($pasarelas as $pasarela) {
        if ($pasarela->pasarela == $dato->forma_pago) {
          $pasarela->value += $dato->cobrado;
        }
      }

      if ($dato->monto) {
        $monto = "MX$" . $dato->monto;
      } else {
        $monto = "N/A";
      } ?>
      <tr>
        <td><?= strtoupper($dato->nombre); ?></td>
        <td><?= $dato->fecha_pago; ?></td>
        <td>MX$<?= $dato->cobrado; ?></td>
        <td>MX$<?= $dato->comision; ?></td>
        <td>MX$<?= $dato->cobrado; ?></td>
        <td><?= $dato->meses; ?></td>
        <td><?= $dato->nfactura; ?></td>
        <td><?= $dato->legal; ?></td>
        <td><?= $dato->cedula; ?></td>
        <td><?= strtoupper($dato->plan); ?></td>
        <td><?= $monto; ?></td>
        <td><?= $dato->nodo; ?></td>
        <td><?= strtoupper($dato->operador); ?></td>
        <td><?= strtoupper($dato->descripcion); ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<hr>
<div class="caja">
  <table class="table1">
    <thead>
      <tr>
        <th colspan="2" style="text-align: center;">Resumen de pasarelas</th>
      </tr>
    </thead>
    <tbody>

      <?php
      $tr = "";
      $total = 0;
      foreach ($pasarelas as $pasarela) {
        if ($pasarela->value != 0) {
          $total += $pasarela->value;
          $tr = "<tr>
            <td> $pasarela->pasarela</td>
            <td> MX$$pasarela->value</td>
          </tr>";
        }
      } ?>
      <?= $tr ?>
      <tr style="background-color: grey;">
        <td colspan=2>
          <h2>TOTAL : MX$<?= $total; ?></h2>
        </td>
      </tr>
    </tbody>
  </table>
</div>
<br>
<table class="table1 table_totales">
  <thead>
    <tr>
      <th>Total Cobrado</th>
      <th>Total Comisión</th>
      <th>Neto Cobrado</th>
      <th>Saldos</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>MX$<?= $total_cobrado; ?></td>
      <td>MX$<?= $total_comision; ?></td>
      <td>MX$<?= $total_neto; ?></td>
      <td>MX$<?= $total_saldos; ?></td>
    </tr>
  </tbody>
</table>

<style>
  .flexContainer {

    margin: 2px 10px;
    display: flex;
  }

  .left {
    flex-basis: 30%;
  }

  .right {
    flex-basis: 30%;
  }

  .caja {
    text-align: center;
    width: 30%;
    margin: auto;
  }

  .titulo {
    text-align: center;
    width: 60%;
    margin: auto;
  }

  tr:nth-child(even) {
    background-color: #ddd;
  }

  tr td {
    text-align: center;
    padding: 5px;
  }

  table {
    font-size: 7.7px;
    background-color: white;
  }

  .table_totales {
    font-size: 15px;
  }

  thead {
    color: white;
    border-bottom: solid 4px;
  }

  .table1 {
    border: solid .7px;
    border-collapse: collapse;
    width: 100%;
  }

  .table1 thead th {
    padding: 5px;
    color: white;
    background: rgb(040, 040, 040);
  }

  .table1 thead th:first-of-type {
    border-top-left-radius: 10px;
  }

  .table1 thead th:last-of-type {
    border-top-right-radius: 10px;
  }
</style>