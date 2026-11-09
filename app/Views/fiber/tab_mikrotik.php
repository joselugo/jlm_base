    <div class="row">
      <div class="col-lg-5">
        <div class="panel panel-default border-default">
          <div class="panel-heading">
            <h4 class="panel-title">Información del Router</h4>
          </div>
          <div class="panel-body no-padding">
            <table class="tablamikrotik table table-striped">
              <tbody>
                <tr>
                  <td align='right'></td>
                  <td><i class="fas fa-spinner fa-spin"></i> Cargando...</td>
                </tr>


              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-lg-7 float-left">
        <div class="panel panel-default border-default">
          <div class="panel-heading">
            <h4 class="panel-title">Tráfico Actual</h4>
          </div>
          <div class="panel-body form-horizontal">

            <div class="form-group row">
              <label class="col-sm-4 control-label">Seleccionar Interface</label>
              <div class="col-sm-8">
                <select class="form-control slmk" name="ethermikrotik" style="width: 100%">
                  <?= $interfaces;?>
                </select>
              </div>
            </div>



            <div id="flot-trafico-mk" style="width:99%;height:295px;margin:0 auto;"></div>
          </div>
        </div>
      </div>

    </div>

    <script>
$('.slmk').select2();
var txt = [],
  cpuCore = [];
var splot;
var dataset;
var totalPoints = 30;
var updateInterval = 1500;
var now = new Date().getTime();
var options = {
  series: {
    lines: {
      show: true
    }
  },
  xaxis: {
    mode: "time",
    tickSize: [1.5, "second"],
    tickFormatter: function(v, axis) {
      return "";
    },
    axisLabel: "",
    axisLabelUseCanvas: false,
    axisLabelFontSizePixels: 1,
  },
  yaxes: [{
    min: 0,
    tickFormatter: function(v, axis) {
      return parseFloat(v).toFixed(2) + " Mbps";
    },
    axisLabel: "TX",
    tickDecimals: 1,
  }],
  legend: {
    //noColumns: 1,
    position: "sw"
  },
  grid: {
    backgroundColor: {
      colors: ["#FFFFFF", "#FFFFFF"]
    },
    autoHighlight: true
  }
};

function initData() {
  for (var i = 0; i < totalPoints; i++) {
    var temp = [now += updateInterval, 0];
    txt.push(temp);
    cpuCore.push(temp);
  }
}

function GetData() {
  $.ajaxSetup({
    cache: false
  });

  $.ajax({
    url: "gestion_red/snmp_mikrotik?id=<?= $id;?>&interface=" + $('[name="ethermikrotik"]').val(),
    dataType: 'json',
    success: update,
    error: function() {
      timechar = setTimeout(GetData, updateInterval);
    }
  });
}

var temp;

function update(_data) {

  txt.shift();
  cpuCore.shift();

  now += updateInterval

  temp = [now, _data.txt];
  txt.push(temp);

  temp = [now, _data.core];
  cpuCore.push(temp);

  $('.tablamikrotik tbody').html(_data.resumen);
  dataset = [{
      label: "TX: " + _data.txt + " Mbps",
      data: txt,
      lines: {
        lineWidth: 2
      },
      color: "#2389C6"
    },
    {
      label: "RX: " + _data.core + " Mbps",
      data: cpuCore,
      lines: {
        lineWidth: 2
      },
      color: "#FF0000"
    }
  ];
  //options.yaxes.max = txt;
  splot = $.plot($("#flot-trafico-mk"), dataset, options);
  timechar = setTimeout(GetData, updateInterval);
}


function cargatr() {
  initData();

  dataset = [{
      label: "TX: ",
      data: txt,
      lines: {},
      color: "#2389C6"
    },
    {
      label: "RX: ",
      data: cpuCore,
      lines: {},
      color: "#FF0000"
    }
  ];

  //options.yaxes.max = txt;
  splot = $.plot($("#flot-trafico-mk"), dataset, options);
  timechar = setTimeout(GetData, updateInterval);
}


$('[name="ethermikrotik"]').change(function() {
  clearTimeout(timechar)
  cargatr();
});

setTimeout(function() {
  $('[name="ethermikrotik"]').trigger('change');
}, 200);
    </script>
