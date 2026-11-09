<div class="tabcontent">
  <!-- begin nav-tabs -->
  <ul class="tabgris nav nav-tabs  nav-ajax">
    <li class="nav-items"><a href="#nav-gpon" data-toggle="tab" data-url="fiber/tab_gpon_ports?action=tabinvoices&amp;id=<?= $id ?>" class="nav-link ">
        <span class="d-sm-block d-none"><i class="fas fa-ethernet"></i> Gpon</span>
        <span class="d-sm-none"><i class="fas fa-ethernet fa-lg"></i></span>
      </a></li>

    <li class="nav-items"><a href="#nav-config" data-toggle="tab" data-url="fiber/olt_uplink?id=<?= $id ?>" class="nav-link">
        <span class="d-sm-block d-none"><i class="fas fa-ethernet"></i> Uplink</span>
        <span class="d-sm-none"><i class="fas fa-ethernet fa-lg"></i></span>
      </a></li>


    <li class="nav-items"><a href="#nav-olt_targetas" data-toggle="tab" data-url="fiber/olt_targetas?id=<?= $id ?>" class="nav-link active show">

        <span class="d-sm-block d-none"><i class="fas fa-sd-card"></i> Tarjetas</span>
        <span class="d-sm-none"><i class="fas fa-sd-card fa-lg"></i></span>
      </a></li>


  </ul>

  <div class="tab-content">

    <div class="tab-pane " id="nav-gpon">

    </div>

    <div class="tab-pane in show active" id="nav-olt_targetas">
    </div>

    <div class="tab-pane" id="nav-saldos">
    </div>

    <div class="tab-pane" id="nav-afip">
    </div>

  </div>

</div>

<script>
  $(function() {

    $('#nav-olt_targetas').load('fiber/olt_targetas?id=<?= $id ?>');

  })
</script>