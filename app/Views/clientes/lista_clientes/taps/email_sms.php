  <!-- <div class="panel panel-default panel-email">
    <div class="panel-heading">
      <h4 class="panel-title"><b>Correo Enviados</b></h4>
      <div class="panel-heading-btn">
        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-expand"><i class="fas fa-expand"></i></a>
        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-collapse"><i class="fas fa-minus"></i></a>
      </div>
    </div>
    <div class="panel-body border-panel">
      <table id="data-email" class="display nowrap table table-striped table-bordered" cellspacing="0" width="100%" data-order="[[ 0, &quot;desc&quot; ]]">
        <thead>
          <tr>
            <th>ID</th>
            <th>Asunto</th>
            <th>Fecha</th>
            <th>Remitente</th>
            <th>Destino</th>
            <th>Estado</th>
            <th>Log</th>
            <th class="all" style="max-width:20px !important" data-orderable="false"></th>
          </tr>
        </thead>

        <tbody>
        </tbody>

      </table>

    </div>
  </div> -->


  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title"><b>Mensajes de texto</b></h4>
      <div class="panel-heading-btn">
        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-expand"><i class="fas fa-expand"></i></a>
        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-iconpanel" data-click="panel-collapse"><i class="fas fa-minus"></i></a>
      </div>

    </div>
    <div class="panel-body border-panel">
      <table id="data-list-sms" class="display  table table-striped table-bordered" cellspacing="0" width="100%" data-order="[[ 0, &quot;desc&quot; ]]">
        <thead>
          <tr>
            <th>ID</th>
            <th>Mensaje</th>
            <th>Estado</th>
            <th># Destino</th>
            <th>Fecha envío</th>
            <th class="all" style="max-width:20px !important" data-orderable="false"></th>
          </tr>
        </thead>

        <tbody>
          <?php foreach ($mensajes as $mensaje) : ?>
            <?php
            if ($mensaje->stado == "en espera") {
              $css = "orange";
            } elseif ($mensaje->stado == "enviado") {
              $css = "lime";
            } else {
              $css = "red";
            }
            $txt_numeros = "";
            $numeros = explode(",", $mensaje->ndestino);
            foreach ($numeros as $key => $value) {
              $txt_numeros .= " <a target=\"_blank\" href=\"https://api.whatsapp.com/send?phone=$value\"><span class=\"badge bg-gray f-s-12 r-6\"><i style=\"color: #6bd06b;\" class=\"fab fa-whatsapp\"></i>  $value</span></a>";
            }
            ?>
            <tr>
              <td><?= $mensaje->id; ?></td>
              <td><?= $mensaje->mensaje; ?></td>
              <td><span class="badge bg-<?= $css; ?> f-s-10 r-6" style="font-size: 12px !important"><?= ucfirst($mensaje->stado); ?></span></td>
              <td><?= $txt_numeros; ?></td>
              <td><?= $mensaje->enviado== "0000-00-00 00:00:00"?"": $mensaje->enviado; ?></td>
              <td><a data-toggle="tooltip" title="" class="btn btn-default btn-icon btn-sm" onclick="resendsms(<?= $mensaje->id; ?>)" data-original-title="Volver enviar"><i class="far fa-share-square" aria-hidden="true"></i></a></td>
            </tr>
          <?php endforeach; ?>
        </tbody>

      </table>
    </div>
  </div>


  <script>
    $(function() {
      /*      configtable('#data-email', 'Correo');
           $('#data-email').DataTable({
             "idDataTables": "40",
             initComplete: function(oSettings, json) {
               $("#data-email_wrapper div.tooltablas")
                 .html('<div class="grupotabla btn-group">\
             <button type="button" class="btn btn-white" Onclick="getmodal(\'ajax/usuarios?action=newmail&type=edit&id=2\',\'Nuevo correo\',\'lg\');"><i class="fas fa-plus"></i> Nuevo correo</button>\
           </div>');
             }
           }); */

      configtable('#data-list-sms', 'SMS');
      $('#data-list-sms').DataTable({
        "idDataTables": "40",
        "columnDefs": [{
          "width": "45%",
          "targets": 1
        }],
        initComplete: function(oSettings, json) {
          $("#data-list-sms_wrapper div.tooltablas")
            .html('<div class="grupotabla btn-group">\
        <button type="button" class="btn btn-white" Onclick="getmodal(\'clientes/nuevo_mensaje_cliente?id=<?= $id; ?>\',\'Nuevo sms\',\'sm\');"><i class="fas fa-plus"></i> Nuevo sms</button>\
      </div>');
        }
      });


    })
  </script>