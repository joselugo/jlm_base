
<div class="container-fluid">
                                
    <h3 class="header-title"><i class="fa fa-tachometer-alt"></i> Dashboard</h3>
    <hr>
    <div class="row onus-summary">
        <div class="col-sm-4">
            <h4 class="header-title mb-2"><i class="fa fa-check"></i> ONU's</h4>
         
      <div class="widget widget-stats bg-white">
        <div class="stats-icon stats-icon-sm"><i class="fas fa-cloud text-success"></i></div>
        <div class="stats-content">
          <div class="stats-title text-dark">EN SISTEMA</div>
          <div class="stats-number m-b-2 text-success" id="home_total_online"><?=$en_sistema?></div>
          <div class="stats-desc"><a href="#fiber/onu" class="text-success">Ver <i class="fas fa-arrow-right text-success"></i></a></div>
        </div>
      </div>

      <div class="widget widget-stats bg-white">
        <div class="stats-icon stats-icon-sm"><i class="fas fa-server text-blue"></i></div>
        <div class="stats-content">
          <div class="stats-title text-dark">EN OLT</div>
          <div class="stats-number m-b-2 text-blue" id="home_total_online"><?=$en_olt?></div>
          <div class="stats-desc"><a href="#" class="text-blue">&nbsp; </a></div>
        </div>
      </div>

       <div class="widget widget-stats bg-white">
        <div class="stats-icon stats-icon-sm"><i class="fas fa-times text-orange"></i></div>
        <div class="stats-content">
          <div class="stats-title text-dark">SIN AUTORIZAR</div>
          <div class="stats-number m-b-2 text-orange" id="home_total_online"><?=$sin_autorizar?></div>
          <div class="stats-desc"><a href="#fiber/onu/all/tab_no_autorizadas" class="text-orange">Ver <i class="fas fa-arrow-right text-orange"></i></a></div>
        </div>
      </div>

        </div>

        <div class="col-sm-4">
            <h4 class="header-title mb-2"><i class="fa fa-check"></i> ONU's Autorizadas</h4>
         
      <div class="widget widget-stats bg-white">
        <div class="stats-icon stats-icon-sm"><i class="fas fa-calendar-day text-success"></i></div>
        <div class="stats-content">
          <div class="stats-title text-dark">HOY</div>
          <div class="stats-number m-b-2 text-success" id="home_total_online"><?=$autorizadas_hoy?></div>
           <div class="stats-desc"><a href="#" class="text-blue">&nbsp; </a></div>
        </div>
      </div>

      <div class="widget widget-stats bg-white">
        <div class="stats-icon stats-icon-sm"><i class="fas fa-calendar text-blue"></i></div>
        <div class="stats-content">
          <div class="stats-title text-dark"><?=$mes_actual?></div>
          <div class="stats-number m-b-2 text-blue" id="home_total_online"><?=$autorizadas_mes?></div>
           <div class="stats-desc"><a href="#" class="text-blue">&nbsp; </a></div>
        </div>
      </div>

       <div class="widget widget-stats bg-white">
        <div class="stats-icon stats-icon-sm"><i class="fas fa-calendar-plus text-dark"></i></div>
        <div class="stats-content">
          <div class="stats-title text-dark">TOTAL</div>
          <div class="stats-number m-b-2 text-dark" id="home_total_online"><?=$en_sistema?></div>
          <div class="stats-desc"><a href="#fiber/onu" class="text-dark">Ver <i class="fas fa-arrow-right text-dark"></i></a></div>
        </div>
      </div>

        </div>

         <div class="col-sm-4">
            <h4 class="header-title mb-2"><i class="fa fa-check"></i> Estado ONU's</h4>
         
      <div class="widget widget-stats bg-white">
        <div class="stats-icon stats-icon-sm"><i class="fas fa-globe text-success"></i></div>
        <div class="stats-content">
          <div class="stats-title text-dark">ACTIVAS</div>
          <div class="stats-number m-b-2 text-success" id="home_total_online"><?=$activas?></div>
          <div class="stats-desc"><a href="#fiber/onu" class="text-success">Ver <i class="fas fa-arrow-right text-success"></i></a></div>
        </div>
      </div>

      <div class="widget widget-stats bg-white">
        <div class="stats-icon stats-icon-sm"><i class="fas fa-globe text-red"></i></div>
        <div class="stats-content">
          <div class="stats-title text-dark">OFFLINE</div>
          <div class="stats-number m-b-2 text-red" id="home_total_online"><?=$offline?></div>
          <div class="stats-desc"><a href="#fiber/onu/offline" class="text-red">Ver <i class="fas fa-arrow-right text-red"></i></a></div>
        </div>
      </div>

       <div class="widget widget-stats bg-white">
        <div class="stats-icon stats-icon-sm"><i class="fas fa-wifi text-orange"></i></div>
        <div class="stats-content">
          <div class="stats-title text-dark">BAJA SEÑAL</div>
          <div class="stats-number m-b-2 text-orange" id="baja_senial"><div class="spinner-grow"></div></div>
          <div class="stats-desc"><a href="#clientes" class="text-orange">Ver <i class="fas fa-arrow-right text-orange"></i></a></div>
        </div>
      </div>

        </div>

    </div>


    <small style="font-size: 10px;">Última actualización: <strong id="last-updated"><?=date("d/m/Y h:i:s a")?></strong>
        <a class="bootstrap-link" id="link-actualizar-datos" href="/panel-actualizar/"> <i class="fa fa-sync-alt"></i> Actualizar Ahora</a>
    </small>

    <div class="row">
       <div class="col-xl-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                       <div class="col-sm-3">
                          <h4 class="header-title mt-2"><i class="fa fa-server"></i> ONU´S Autorizadas</h4> 
                       </div>
                       <div class="col-sm-3">
                          <select class="form-control" name="anio">
                              <option value="2020">Año 2020</option>
                              <option value="2021" selected>Año 2021</option>
                              <option value="2022">Año 2022</option>
                              <option value="2023">Año 2023</option>
                          </select>
                       </div>  
                    </div>
                     <div class="col-sm-12">
                       <div class="chart-responsive">
                        <div class="chart" id="bar-chart" style="height: 280px;"></div>
                      </div>
                   </div>
                    
                </div> <!-- end card-body-->
            </div> <!-- end card-->
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
     get_onu_autorize_per_mes("<?=date("Y")?>");    
    get_onu_lower_signal();
    App.setPageTitle('Dashboard Fiber');
    bar = new Morris.Bar({
      element: 'bar-chart',
      resize: true,
      data: [{
          y: 'Ene',
          a: 0
        },
        {
          y: 'Feb',
          a: 0
        },
        {
          y: 'Mar',
          a: 0
        },
        {
          y: 'Abr',
          a: 0
        },
        {
          y: 'May',
          a: 0
        },
        {
          y: 'Jun',
          a: 0
        },
        {
          y: 'Jul',
          a: 0
        },
        {
          y: 'Ago',
          a: 0
        },
        {
          y: 'Sep',
          a: 0
        },
        {
          y: 'Oct',
          a: 0
        },
        {
          y: 'Nov',
          a: 0
        },
        {
          y: 'Dic',
          a: 0
        }
      ],
      barColors: ['#3F88D1'],
      xkey: 'y',
      ykeys: ['a'],
      yLabelFormat: function(y) {
        return y.toString();
      },
      labels: ['ONUS'],
      xLabelAngle: 90,
      hideHover: 'auto'
    });

    $( "[name='anio']" ).change(function() {
     get_onu_autorize_per_mes($("[name='anio']").val());
   });

 }); 

      function get_onu_lower_signal(){

        $('.fa-fw').hide();
          $.ajax({
            url: 'fiber/get_onu_lower_signal',
            type: 'POST',
            dataType: 'json',
            success: function(data){
                $(".spinner-grow").remove();
                $("#baja_senial").html(data);
            },
            error: function(e){
              console.log(e.responseText);
            }
          });
     }


      function get_onu_autorize_per_mes(anio){

        $('.fa-fw').hide();
          $.ajax({
            url: 'fiber/get_onu_autorize_per_mes?anio='+anio,
            type: 'POST',
            dataType: 'json',
            success: function(data){
              bar.setData(data)
            },
            error: function(e){
              console.log(e.responseText);
            }
          });

     }  

</script>