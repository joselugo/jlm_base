<form id="streaming-form" |class="form-horizontal" enctype="multipart/form-data">
    <div class="modal-body row" style="width: 100%">

        <div class="container-fluid">
            <table id="hs-youtube" class="display nowrap table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Log</th>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <!--   <th class="all" style="max-width:20px !important" data-orderable="false"></th> -->
                    </tr>
                </thead>
            </table>
        </div>

    </div>

    <div class="modal-footer">
        <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>
    </div>

</form>


<style type="text/css">
    .select2-dropdown {
        z-index: 1960 !important;
    }
</style>

<script>
    $(document).ready(function() {
        $('#modaltmp').modal('show');
        var balance = $('#hs-youtube').DataTable({
            "ajax": "streaming/get_balance_youtube",
            "deferRender": true,
            "idDataTables": "24",
            "aoColumns": [{
                    mData: 'log'
                },
                {
                    mData: 'fecha'
                },
                {
                    mData: 'usuario'
                }
            ]
        });
    });
</script>