<div class="panel panel-blue panel-Usuarios">
      <div class="panel-heading">
            <h4 class="panel-title">Log calendario</h4>
            <div class="panel-heading-btn">
                  <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fas fa-expand"></i></a>
                  <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onclick="updatelog()"><i class="fas fa-sync-alt"></i></a>
            </div>
      </div>
      <div class="panel-body">

            <table id="data-log" class="table table-striped table-bordered dt-responsive nowrap dataTable no-footer dtr-inline" cellspacing="0" width="100%">
                  <thead>
                        <tr role="row">
                              <th style="max-width: 10px; min-width: 10px; width: 0px;" class="sorting">Id</th>
                              <th class="sorting_asc" tabindex="0" aria-controls="list-logsistema">Log</th>
                              <th class="sorting">Fecha</th>
                              <th class="sorting">Usuario</th>
                        </tr>
                  </thead>
            </table>

      </div>
</div>

<script>
      var log;
      var base_url = "<?= base_url(); ?>";


      $(document).ready(function() {

            configtable('#data-log', 'log');


            log = $('#data-log').DataTable({
                  "ajax": base_url + "calendario/get_log_calendario_json",
                  "deferRender": true,
                  "idDataTables": "1",
                  "aoColumns": [{
                              mData: 'id'
                        },
                        {
                              mData: 'log'
                        },
                        {
                              mData: 'fechalog'
                        },
                        {
                              mData: 'username'
                        },
                  ],

                  initComplete: function(oSettings, json) {

                        $('.not-search-input').html('');
                        //------->Button Col
                        $('#data-log_wrapper .dt-buttons').append('');
                        if (<?= $tipoacceso;?>>=2) {
                              $("#data-log_wrapper div.tooltablas")
                                    .html('<div class="grupotabla btn-group m-r-5">\
            <a class="btn btn-default" data-toggle="modal" data-target="#moda_log"><i class="fas fa-plus"></i> Nuevo</a></div>');
                        }
                  }
            }).on('processing.dt', function(e, settings, processing) {
                  if (processing) {
                        loaderin('.panel-log');
                  } else {
                        loaderout('.panel-log');
                  }
            }).on('draw', function() {
                  $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
            });

            updatelog = function() {
                  log.ajax.reload(null, false);
                  $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
            }


      });


      App.restartGlobalFunction();
      App.setPageTitle('Log calendario');
</script>