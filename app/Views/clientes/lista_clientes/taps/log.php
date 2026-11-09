<style type="text/css">
  @media (max-width: 576px) {
    #data-log_wrapper .dt-buttons {
      display: inline-table;
    }

    #data-log_wrapper #data-log_filter {
      margin-top: -20px;
      display: initial;
    }

    #data-log_filter label {
      width: 100%
    }
  }

  @media (max-width: 767.98px) {
    #data-log_wrapper #data-log_filter {
      margin-top: -20px;
      width: 100%;
      display: inline-block;
    }

    #data-log_filter label {
      float: right;
      width: 101.5%
    }
  }
</style>
<div class="tab-pane active show" id="nav-tab-2">
  <div class="panel panel-default panel-log">
    <div class="panel-heading">
      <h4 class="panel-title"><b>log en servicio</b></h4>
      <div class="panel-heading-btn">
        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-expand" data-original-title="" title="" data-init="true"><i class="fa fa-expand"></i></a>
        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-reload" onclick="updateunidad()" data-original-title="" title="" data-init="true"><i class="fa fa-sync-alt"></i></a>
        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-collapse" data-original-title="" title="" data-init="true"><i class="fa fa-minus"></i></a>
      </div>
    </div>
    <div class="panel-body border-panel">


      <table id="data-log" class="display  table table-striped table-bordered table-hover dataTable no-footer dtr-inline" data-order="[[ 0, &quot;asc&quot; ]]" style="width: 100%;">
        <thead>
          <tr role="row">
            <th class="sorting">Id</th>
            <th class="sorting">Log</th>
            <th class="sorting">Fecha</th>
            <th class="sorting">User</th>
          </tr>
        </thead>
      </table>


    </div>
  </div>


  <script>
    var log;
    var base_url = "<?= base_url() ?>";

    updatelog = function() {
      log.ajax.reload(null, false);
    }

    $(function() {

      configtable('#data-log', 'log');

      log = $('#data-log').DataTable({
        "ajax": base_url + "clientes/get_log_json?id=<?= $id ?>",
        "deferRender": true,
        "aoColumns": [{
            mData: 'id',
            "width": "6%"
          },
          {
            mData: 'log',
            "width": "60%"
          },
          {
            mData: 'fecha',
            "width": "17%"
          },
          {
            mData: 'user',
            "width": "17%"
          }
        ],
        initComplete: function(oSettings, json) {
          $("#data-log_wrapper .dt-buttons")
            .append('<div class="btn-group">\
      <button type="button" class="btn btn-default" data-toggle="modal" href="#modal-new-unidad"><i class="fas fa-plus"></i> <b>Nuevo</b></button>\
    </div>');

        }
      }).on('processing.dt', function(e, settings, processing) {
        if (processing) {
          loaderin('.panel-logvoip');
        } else {
          loaderout('.panel-logvoip');
        }
      }).on('draw', function() {
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
      });

    }).on('processing.dt', function(e, settings, processing) {
      if (processing) {
        loaderin('.panel-log');
      } else {
        loaderout('.panel-log');
      }
    }).on('draw', function() {
      $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
    });
  </script>