<style>

</style>
<div class="rowfc">
  <table id="data-placas" class="display nowrap table table-striped table-bordered dataTable no-footer dtr-inline collapsed" cellspacing="0" width="100%" data-order="[[ 0, &quot;desc&quot; ]]" style="width: 100%;">
    <thead>
      <tr role="row">
        <th class="all sorting_asc" style="max-width: 60px !important; width: 74px;">Ranura</th>
        <th class="sorting">Nombre</th>
        <th class="sorting">Estado</th>
        <th class="sorting">Modo Operacion</th>
        <th class="sorting">Temperatura</th>
        <th class="sorting">Puertos <a data-toggle="tooltip" data-placement="top" data-original-title="Actualizar puertos" onclick="update_puertos(1)"><i style="color: forestgreen;font-size:13px;margin-left: 3px;" class="fas fa-sync-alt"></i></a></th>
        <th class="all sorting_disabled" style="max-width: 20px !important; width: 90px;" data-orderable="false"></th>
      </tr>
    </thead>
    <tbody>

    </tbody>

  </table>


</div>

<script>
  $(document).ready(function() {
    configtable('#data-placas', 'data-placas');
    targetas = $('#data-placas').DataTable({
      "ajax": "fiber/get_placas?id=<?= $id ?>",
      "deferRender": true,
      "aoColumns": [{
          mData: 'ranura',
          sWidth: "10px"
        }, {
          mData: 'nombre',
        }, {
          mData: 'estado',
        },
        {
          mData: 'modo',
        },

        {
          mData: 'temp',
        },
        {
          mData: 'puertos',
          sClass: "dt-center"
        },
        {
          mData: 'tool',
          sClass: "dt-center"
        },
      ],
      initComplete: function(oSettings, json) {
        update_puertos(0);
        /*   $("#data-placas_wrapper div.tooltablas")
          .html('<div class="grupotabla btn-group">\
      <button type="button" class="btn btn-white" Onclick="autofind()"><i class="fas fa-plus"></i> Autofind</button>\
    </div>'); */
      }
    })

  })
  update_targetas = function() {
    targetas.ajax.reload(null, false);
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust().responsive.recalc();
  }

  function autofind() {
    console.log("autofind");
  }

  function update_puertos(num) {
    if (num == 1) {
      $(".puertos").each(function() {
        $(this).html("<div style=\"width: 20px;height: 20px;\" class=\"spinner-grow\"></div>");
      });
    }
    $.ajax({
      type: "post",
      url: "fiber/olt_puertos?id=<?= $id; ?>",
      success: function(response) {
        $(".puertos").html("");
        response = JSON.parse(response);
        console.log(response.vlans);

        response.data.forEach(element => {
          value = Object.values(element);
          var element_a = $(".puerto_" + value[0] + "_" + value[1] + " > button").length;
          if (element.estado_operacion.estado == "up") {
            style = "success";
          } else if (element.estado_operacion.estado == "dow") {
            style = "danger";
          } else {
            style = "secondary";
          }
          if (element_a == 0) {
            $(".puerto_" + value[0] + "_" + value[1]).append(`<p style="margin-bottom:1px">${element.tipo}</p>`);
          }
          if (element_a == 8 || element_a == 16 || element_a == 32 || element_a == 64) {
            $(".puerto_" + value[0] + "_" + value[1]).append(`<br>`);
          }
          var vlans = [];
          response.vlans.forEach(v_element => {
            ranura = v_element.ranura;
            puerto = v_element.puerto;
            vlan = v_element.vlan;
            if (value[1] == ranura && value[2] == puerto) {
              vlans.push(vlan);
            }
          });
          numVlans = vlans.length
          if (numVlans != 0) {
            $txtVlans = "";
            vlans.forEach(function callback(t_element, index) {
              if (numVlans - 1 != index) {
                style1 = "border-radius: 0px 0px 0px 0px;margin-bottom:1px !important";
              } else {
                style1 = "border-radius: 0px 0px 3px 3px;";
              }
              $txtVlans += `<div style="background: yellow; color: darkgoldenrod; font-weight: 400;${style1}" class="mb-0">${t_element}</div>`;
            });
            $buttonVlans = `
              <button onclick="getmodal('fiber/editar_puerto?chasis=${value[0]}&ranura=${value[1]}&tipo=${element.tipo}&olt=<?= $id; ?>&if_index=${element.if_index}&puerto=${value[2]}','Editar puerto','${element.tipo=="Uplink"?"sm":"lg"}');" data-toggle="tooltip" data-placement="top" data-original-title="${element.estado_operacion.info}" style="color:white;font-size: 11px;margin-left:2px;margin-top:1px;margin-bottom:1px;margin-right:2px;width: 34px;padding-bottom: 0px;padding-left: 0px;padding-right: 0px;border-left-width: 0px;border-right-width: 0px;border-bottom-width: 0px;padding-top:2px;" class="btn ip_${value[0]}_${value[1]}_${value[2]} btn-sm btn-${style}">
                <p class="mb-0" style="margin-bottom:2px;">${value[2]}</p>
                ${$txtVlans}
              </button>`;
          } else {
            $buttonVlans = `
              <button onclick="getmodal('fiber/editar_puerto?chasis=${value[0]}&ranura=${value[1]}&tipo=${element.tipo}&olt=<?= $id; ?>&if_index=${element.if_index}&puerto=${value[2]}','Editar puerto','${element.tipo=="Uplink"?"sm":"lg"}');" data-toggle="tooltip" data-placement="top" data-original-title="${element.estado_operacion.info}" style="color:white;font-size: 11px;margin-bottom:1px;width: 34px;" class="btn ip_${value[0]}_${value[1]}_${value[2]} btn-sm btn-${style}">
            ${value[2]}
              </button>`;
          }
          $(".puerto_" + value[0] + "_" + value[1]).append($buttonVlans);
        });

        $(".puertos").each(function() {
          hijos = $(this).children().length;
          if (hijos > 0) {
            $(this).css({
              'text-align-last': 'center',
              'border-radius': '7px',
              'padding-left': '3px',
              'padding-right': '3px',
              'padding-top': '5px',
              'padding-bottom': '3px',
              'background-color': 'darkseagreen'
            });
          } else {
            $(this).css({});
          }

        });

      }
    });
  }

  function slot_admin(olt, chasis, ranura, opcion) {
    var slot_admin_confirm = $.confirm({
      theme: 'supervan',
      draggable: false,
      closeIcon: true,
      animationBounce: 2.5,
      escapeKey: false,
      content: '<b>¿Está seguro que desea reiciar esta ranura?</b>',
      title: '<i class="fa fa-question-circle fa-lg icodialog"></i> Reiciar ranura',
      buttons: {
        Eliminar: {
          text: 'Reiciar', // With spaces and symbols
          action: function() {
            this.buttons.Eliminar.setText('<i class="fas fa-spinner fa-spin icodialog"></i> Procesando...');
            this.buttons.Eliminar.disable();
            $('.jconfirm-closeIcon').remove();
            $.ajax({
              type: "POST",
              url: "fiber/slot_admin",
              data: {
                olt,
                chasis,
                ranura,
                opcion
              },
              success: function(response) {
                if (response == 1) {
                  alerta("exito", "La ranura se reinicio con exito.");
                  if (typeof autofind === 'function') {
                    $('a[href="#nav-olt_targetas"]').trigger("click");
                  }
                } else if (response == 2) {
                  alerta("error", "Error al reinciar, vuelve a intentarlo.");

                } else if (response == 100) {
                  alerta("warning", "Error SNMP, Esto se debe normalmente a que la OLT se reinicio.<br>Espere a que todas las configuraciones se restablescan,");
                } else {
                  alerta("warning", "No cuentas con los permisos necesarios.");
                }
                slot_admin_confirm.close();
              }
            });
            return false;
          }
        }
      }
    });

  }
</script>