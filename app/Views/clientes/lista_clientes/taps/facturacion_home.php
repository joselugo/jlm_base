<div class="tabcontent">
  <!-- begin nav-tabs -->
  <ul class="tabgris nav nav-tabs  nav-ajax">
    <li class="nav-items"><a href="#nav-facturas" data-toggle="tab" data-url="clientes_facturacion/facturas?id=<?= $id; ?>" class="nav-link active show">
        <span class="d-sm-block d-none"><i class="fas fa-file-alt"></i> Facturas</span>
        <span class="d-sm-none"><i class="fas fa-file-alt fa-lg"></i></span>
      </a></li>

    <li class="nav-items"><a href="#nav-config" data-toggle="tab" data-url="clientes_facturacion/configuracion?id=<?= $id; ?>" class="nav-link">
        <span class="d-sm-block d-none"><i class="fas fa-cogs"></i> Configuración</span>
        <span class="d-sm-none"><i class="fas fa-cogs fa-lg"></i></span>
      </a></li>

    <li class="nav-items  d-none"><a href="#nav-afip" data-toggle="tab" data-url="ajax/viewuser?action=tabafip&id=328" class="nav-link">
        <span class="d-sm-block d-none"><img src="images/afip.png" height="17" alt=""></span>
        <span class="d-sm-none">AFIP</span>
      </a>
    </li>

    <li class="nav-items"><a href="#nav-transacciones" data-toggle="tab" data-url="clientes_facturacion/transacciones?id=<?= $id; ?>" class="nav-link">

        <span class="d-sm-block d-none"><i class="fas fa-dollar-sign"></i> Transacciones</span>
        <span class="d-sm-none"><i class="fas fa-dollar-sign fa-lg"></i></span>
      </a></li>

    <li class="nav-items"><a href="#nav-saldos" data-toggle="tab" data-url="clientes_facturacion/saldos?id=<?= $id; ?>" class="nav-link">
        <span class="d-sm-block d-none"><i class="fas fa-archive"></i> Saldos</span>
        <span class="d-sm-none"><i class="fas fa-archive fa-lg"></i></span>
      </a></li>


  </ul>

  <div class="tab-content">
    <div class="tab-pane" id="nav-config">

    </div>

    <div class="tab-pane in show active" id="nav-facturas">
    </div>

    <div class="tab-pane" id="nav-transacciones">
    </div>

    <div class="tab-pane" id="nav-saldos">
    </div>

    <div class="tab-pane" id="nav-afip">
    </div>

  </div>

</div>
<script>
  $(document).ready(function() {
    $('#nav-facturas').load("clientes_facturacion/facturas?id=<?= $id; ?>");
  });
</script>