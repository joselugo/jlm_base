
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
                data-url="fiber/tab_autorizadas/<?=$estado?>" class="nav-link"><i class="fas fa-check"></i>
                Autorizadas</a></li>
            <li class="nav-item"><a id="tab2" href="#nav-tab-2" data-toggle="tab"
                data-url="fiber/tab_no_autorizadas?id=" class="nav-link"><i class="fas fa-times"></i>
                Sin autorizar</a></li>
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
      </div>
    </div>
  </div>
</div>
<script>
$(function() {
  var tab="<?=$tab_click?>";
  if(tab=="tab_no_autorizadas"){
    $('#nav-tab-1').trigger('click');
    $('#tab2').addClass('active'); 
    $('#nav-tab-2').load('fiber/tab_no_autorizadas');
  }
  else{
    $('#home').addClass('active'); 
    $('#nav-tab-1').load('fiber/tab_autorizadas/<?=$estado?>');
  }
  

})

App.restartGlobalFunction();
App.setPageTitle('ONUS');
</script>
