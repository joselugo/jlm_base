<div class="panel panel-blue panel-Usuarios">
  <div class="panel-heading">
    <h4 class="panel-title">Formatos</h4>
    <div class="panel-heading-btn">
      <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fas fa-expand"></i></a>
      <!--<a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-reload" onclick="refresh_()"><i class="fas fa-sync-alt"></i></a>-->
    </div>
  </div>
  <div class="panel-body">

    <form class="form-inlin" style="margin-bottom:10px" target="_blank" action="formatos/viewformato">
      <div class="row">
        <div class="form-group col-md-4">
          <label> Formato: </label>
          <select class="form-control" id="formato" name="formato">
            <option value="1">Contrato de servicio</option>
            <option value="2">Soporte</option>
            <option value="3">Mantenimiento</option>
            <option value="4">Carta de aviso de cancelacion</option>
            <option value="5">Cancelacion de servicio</option>
            <option value="6">Terminos y condiciones CCTV CAMERA</option>
            <option value="7">Contrato de servicio nuevo</option>
            <option value="8">Carta de adeudo</option>
            <option value="9">Levantamiento de servicio</option>
            <option value="10">Levantamiento de cámaras</option>
            <option value="11">Hoja instalacion ÚNNet</option>
          </select>
        </div>

        <div class="form-group col-md-4">
          <label> Cliente: </label>
          <input type="text" class="form-control" name="cliente" id="cliente" required="">
          <input type="hidden" class="form-control" name="id_cliente" id="id_cliente" required="">
        </div>

        <div class="form-group col-md-4">
          <label> Fecha: </label>
          <input type="text" class="form-control" name="fecha" id="fecha" required="">
        </div>

        <div id="tickets_soporte" style="display: none; width: 100%">
          <div class="form-group col-md-4">
            <label> Folio: </label>
            <input type="text" class="form-control" name="folio" id="folio">
          </div>
          <div class="form-group col-md-4" style="margin-top: 4px;">
            <br>
            <button type="button" class="btn btn-primary" id="modal">Selecciona el folio del ticket</button>
          </div>
        </div>
        <div class="form-group col-md-4" id="block_new_contrato" style="display: none;">
          <label> Tipo de persona: </label>
          <select class="form-control" id="new_contrato_select" name="new_contrato_select">
            <option value="1">Fisica</option>
            <option value="2">Moral</option>
          </select>
        </div>

        <div id="divrfc" class="form-group col-md-4" style="display: none;">
          <label> RFC: </label>
          <input type="text" class="form-control" name="rfc" id="rfc">
        </div>
        <div class="form-group col-md-12">
          <br>
          <button type="submit" class="btn btn-success">Generar formato</button>
        </div>
      </div>
  </div>
  </form>
</div>
</div>


<div class="modal fade" id="modal_all_tickets">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Selecciona ticket</h5>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
      </div>
      <div class="modal-body">

        <div class="row">
          <div class="col-sm-12" style="margin-top: 10px;">
            <table id="list-ti" class="table-hover table table-striped table-bordered dt-responsive nowrap compact" cellspacing="0" width="100%" data-order="[[ 1, &quot;asc&quot; ]]">
              <thead>
                <tr>
                  <th class="all column column" data-orderable="false">Folio</th>
                  <th class="column">Depto</th>
                  <th class="column">Cliente</th>
                  <th class="column">Asunto</th>
                  <th class="column">Fecha</th>
                  <th class="column">Estado</th>
                  <th class="column">Abierto por</th>
                  <th class="all column radios" data-orderable="false" style="min-width:46px !important;max-width:46px !important">Acción</th>
                </tr>
              </thead>

            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="col-sm-12">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"> </i> Cancelar</button>

          <button type="submit" class="btn btn-sm btn-success" onclick="selec_ti()"><i class="fa fa-floppy-o"></i> Aceptar</button>


        </div>
      </div>
    </div>
    <!-- /.modal-content -->

  </div>
</div>


<script>
  var base_url = "<?= base_url(); ?>"
  var facturas;

  $.datepicker.regional['es'] = {
    closeText: 'Cerrar',
    prevText: '< Ant',
    nextText: 'Sig >',
    currentText: 'Hoy',
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
    dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
    weekHeader: 'Sm',
    dateFormat: 'dd/mm/yy',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''
  };
  $.datepicker.setDefaults($.datepicker.regional['es']);



  $(function() {
    $('#formato').select2();
    $('#new_contrato_select').select2();

    $('#fecha').val("<?= $fecha_hoy ?>");

    $("#formato").change(function() {
      var id = $(this).val();
      $("#rfc").attr("required", false);
      $("#block_new_contrato").hide();
        $("#new_contrato_select").attr("required", false);
        $("#tickets_soporte").hide();
        $("#folio").attr("required", false);
      if (id == 2 || id == 5) {
        $("#tickets_soporte").show();
        $("#folio").attr("required", true);
      }else if(id==7){
        $("#block_new_contrato").show();
        $("#new_contrato_select").attr("required", true);
      } else {
        
      }
    });
    $("#new_contrato_select").change(function() {
      var id = $(this).val();
      if (id == 2) {
        $("#divrfc").show();
        $("#rfc").attr("required", true);
      } else {
        $("#divrfc").hide();
        $("#rfc").attr("required", false);
      }
    });

    $("#modal").click(function() {
      $("#modal_all_tickets").modal();
      loadlist_ti();
    });


    //--->Fechas
    $('#fecha').datepicker({
      dateFormat: "yy-mm-dd",
      toggleActive: true,
      todayHighlight: true,
      autoclose: true
    });



    $(document).on('keyup', '#cliente', function(e) {
      $("#cliente").autocomplete({
        source: base_url + 'clientes/searchcliente?action=listauser',
        minLength: 3,
        open: function(event, ui) {
          $('img[data-name]').initial({
            height: 64,
            width: 64,
            charCount: 2,
            fontSize: 26,
            fontWeight: 600
          });
        },
        select: function(event, ui) {
          $("#id_cliente").val(ui.item.id);
        }
      }).data("ui-autocomplete")._renderItem = function(ul, item) {
        return $("<li></li>")

          .data("item.autocomplete", item)
          .append(item.socio)
          .appendTo(ul);
      };
    });

    configtable('#list-ti', 'ti');
  }) //////////end funtion ready




  loadlist_ti = function() {
    if (!$.fn.DataTable.isDataTable('#list-ti')) {
      ti = $('#list-ti').DataTable({
        "language": {
          "url": "<?= base_url(); ?>public/js/Datatables-es.json"
        },
        "ajax": "<?= base_url(); ?>index.php/formatos/get_tickets_json",
        "deferRender": true,
        "aoColumns": [{
            mData: 'id',
            'render': function(data, type, full, meta) {
              return full['id']
            }
          },
          {
            mData: 'departamento'
          },
          {
            mData: 'nombre'
          },
          {
            mData: 'asunto'
          }, {
            mData: 'fecha'
          }, {
            mData: 'estado'
          }, {
            mData: 'login'
          },
          {
            mData: 'id',
            'render': function(data, type, full, meta) {
              var coltool = data.split('-');
              return '<input type="radio" class="flat-red" id="flat-red" name="slactivo[]" value="' + data + '">';
            },
            sClass: 'dt-center'
          }
        ],
        "fnDrawCallback": function(oSettings) {
          $('input[type="radio"].flat-red').iCheck({
            radioClass: 'iradio_flat-green',
            increaseArea: '-10%'
          })
          //$('input[type="radio"].flat-red').on( "ifChecked", function( e ) {}); funcion cuando se hace click en iCheck

        },
        "initComplete": function() {


        }

      }).on('processing.dt', function(e, settings, processing) {
        if (processing) {
          loaderin('.panel-ti');
        } else {
          loaderout('.panel-ti');
        }
      }).on('draw', function() {
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
      });

    } else {
      updatelist_ti();
    }

  }

  updatelist_ti = function() {
    ti.ajax.reload(null, false);
  }

  selec_ti = function() {
    var id_ticket = "";
    $('input[name^="slactivo"]:checked').each(function() {
      id_ticket = $(this).val();
    });

    $("#folio").val(id_ticket);
    $('#modal_all_tickets').modal('toggle');


  }
</script>