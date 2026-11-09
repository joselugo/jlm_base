<ol class="breadcrumb pull-right">
  <li class="breadcrumb-item"><a href="#index/home">Inicio</a></li>
  <li class="breadcrumb-item"><a href="#gestion_red/servers">Lista Routers</a></li>
  <li class="breadcrumb-item active">Editar router</li>
</ol>
<h3><b>Configuración de Router </b></h3>
<div class="row">
  <div class="col-sm-12">
    <div class="panel panel-inverse panel-with-tabs">
      <div class="panel-heading p-0">
        <!-- begin nav-tabs -->
        <div class="tab-overflow" style="width: 100%">
          <ul class="nav nav-tabs nav-ajax  nav-tabs-inverse">
            <li class="nav-item prev-button"><a href="javascript:;" data-click="prev-tab"
                class="text-success nav-link"><i class="fas fa-arrow-left"></i></a></li>
            <li class="nav-item"><a id="home" href="#nav-tab-1" data-toggle="tab"
                data-url="gestion_red/tab_general?id=<?= $id;?>" class="nav-link active"><i class="fas fa-desktop"></i>
                Datos
                y Configuración</a></li>
            <li class="nav-item"><a href="#nav-tab-2" data-toggle="tab"
                data-url="gestion_red/tab_firewall?id=<?= $id;?>" class="nav-link"><i class="fas fa-user-times"></i>
                Bloqueo de Páginas</a></li>
            <li class="nav-item"><a href="#nav-tab-3" data-toggle="tab"
                data-url="gestion_red/tab_mikrotik?id=<?= $id;?>" class="nav-link"><i class="fas fa-info-circle"></i>
                Mikrotik</a></li>
            <li class="nav-item"><a href="#nav-tab-5" data-toggle="tab"
                data-url="gestion_red/tab_grafico_mikrotik?id=<?= $id;?>" class="nav-link"><i
                  class="fas fa-chart-line"></i> Gráficos</a></li>
            <li class="nav-item"><a href="#nav-tab-4" data-toggle="tab"
                data-url="gestion_red/tab_logmikrotik?id=<?= $id;?>" class="nav-link"><i class="fas fa-cog"></i>
                Log</a>
            </li>
            <li class="nav-item next-button"><a href="javascript:;" data-click="next-tab"
                class="text-success nav-link"><i class="fas fa-arrow-right"></i></a></li>
          </ul>
        </div>
        <div class="panel-heading-btn mr-2 ml-2 d-flex">
          <a href="javascript:;" class="btn btn-sm btn-icon btn-circle btn-success" data-click="panel-expand"><i
              class="fas fa-expand"></i></a>
          <a href="#gestion_red/tab_" data-toggle="ajax" class="btn btn-sm btn-icon btn-circle btn-success"><i
              class="fas fa-times"></i></a>
        </div>
      </div>
      <div class="panel-body tab-content">
        <div class="tab-pane active show" id="nav-tab-1"></div>
        <div class="tab-pane fade" id="nav-tab-2"></div>
        <div class="tab-pane fade" id="nav-tab-3"></div>
        <div class="tab-pane fade" id="nav-tab-4"></div>
        <div class="tab-pane fade" id="nav-tab-5"></div>
      </div>
    </div>
  </div>
</div>
<script>
$(function() {

  $('#nav-tab-1').load('gestion_red/tab_general?id=<?= $id;?>');

})

App.restartGlobalFunction();
App.setPageTitle('Editar Router');
</script>
