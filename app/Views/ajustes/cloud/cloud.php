<ol class="breadcrumb pull-right">
  <li class="breadcrumb-item"><a href="#index/home">Inicio</a></li>
  <li class="breadcrumb-item"><a href="#ajustes">Ajustes</a></li>
  <li class="breadcrumb-item active">Cloud</li>
</ol>

<h1 class="page-header">Servidores Cloud</h1>

<!-- begin row -->
<div class="row">
  <form method="post" class="formin form-horizontal frm-cloud" action="ajustes/savecloud">

    <div class="col-md-10">
      <div class="panel panel-default">
        <div class="panel-heading" data-click="panel-collapse" style="cursor: pointer" data-original-title="" title="" data-init="true">
          <h4 class="panel-title text-center">
            <img src="<?=base_url()?>public/images/dropbox_logo.svg" style="max-height: 30px;width: 130px;">

            <div class="pull-right spacedropbox"></div>

          </h4>
        </div>
        <div class="panel-body border" style="display: block;">

          <div class="form-group text-center">
            <a href="https://www.dropbox.com/developers" target="_blank">Mas detalles https://www.dropbox.com/developers</a>
          </div>

          <div class="form-group">
            <label>Token Acceso</label>
            <input type="text" class="form-control" name="token_dropbox" value="<?=$token_dropbox?>">
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-4">Backup Base de datos</label>
            <div class="col-sm-8">
              <?=$upbackup_dropbox?>
            </div>
          </div>


          <div class="form-group row">
            <label class="col-form-label col-sm-4">Activar cloud</label>
            <div class="col-sm-8">
                 <?=$estado_dropbox?>
            </div>
          </div>


          <div class="panel-footer text-right">
            <button type="submit" class="btn-azul btn-sm m-l-5"><i class="far fa-save"></i> Guardar cambios</button>
          </div>


        </div>
      </div>
    </div>

  <!--  <div class="col-md-10">
      <div class="panel panel-default">
        <div class="panel-heading" data-click="panel-collapse" style="cursor: pointer" data-original-title="" title="" data-init="true">
          <h4 class="panel-title text-center">
            <svg viewBox="0 0 40 21.6" xmlns="http://www.w3.org/2000/svg" style="max-height:30px;fill: #3d97e4;width: 60px; ">
              <path class="box-logo-svg" d="M39.7 19.2c.5.7.4 1.6-.2 2.1-.7.5-1.7.4-2.2-.2l-3.5-4.5-3.4 4.4c-.5.7-1.5.7-2.2.2-.7-.5-.8-1.4-.3-2.1l4-5.2-4-5.2c-.5-.7-.3-1.7.3-2.2.7-.5 1.7-.3 2.2.3l3.4 4.5L37.3 7c.5-.7 1.4-.8 2.2-.3.7.5.7 1.5.2 2.2L35.8 14l3.9 5.2zm-18.2-.6c-2.6 0-4.7-2-4.7-4.6 0-2.5 2.1-4.6 4.7-4.6s4.7 2.1 4.7 4.6c-.1 2.6-2.2 4.6-4.7 4.6zm-13.8 0c-2.6 0-4.7-2-4.7-4.6 0-2.5 2.1-4.6 4.7-4.6s4.7 2.1 4.7 4.6c0 2.6-2.1 4.6-4.7 4.6zM21.5 6.4c-2.9 0-5.5 1.6-6.8 4-1.3-2.4-3.9-4-6.9-4-1.8 0-3.4.6-4.7 1.5V1.5C3.1.7 2.4 0 1.6 0 .7 0 0 .7 0 1.5v12.6c.1 4.2 3.5 7.5 7.7 7.5 3 0 5.6-1.7 6.9-4.1 1.3 2.4 3.9 4.1 6.8 4.1 4.3 0 7.8-3.4 7.8-7.7.1-4.1-3.4-7.5-7.7-7.5z"></path>
            </svg>
            <div class="pull-right spacebox"></div>
          </h4>
        </div>
        <div class="panel-body border" style="display: none;">

          <div class="form-group text-center">
            <a href="https://www.box.com/" target="_blank">Mas detalles https://www.box.com</a>
          </div>


          <div class="form-group">
            <label>Cliente ID</label>
            <input type="text" id="clienteid" class="form-control" name="2[clienteid]" value="">
          </div>

          <div class="form-group">
            <label>Cliente Secret</label>
            <input type="text" id="clientesecret" class="form-control" name="2[clientesecret]" value="">
          </div>

          <div class="form-group">
            <label>Token acceso</label>
            <div style="width: 100%">
              <input type="text" class="form-control" name="2[token]" value="" style="width: calc(100% - 130px);float: left;">
              <button type="button" class="btn btn-primary btn-sm" style="margin-left:10px;" onClick="gettoken();">Obtener token</button>
            </div>
          </div>

          <div class="form-group">
            <label>Token actualización</label>
            <input type="text" class="form-control" name="2[tokenupdate]" value="">
          </div>


          <div class="form-group row">
            <label class="col-form-label col-sm-4">Backup Base de datos</label>
            <div class="col-sm-8">
              <input type="hidden" name="2[upbackup]">
              <input class="chk" type="checkbox" name="2[upbackup]">
            </div>
          </div>

          <div class="form-group row d-none">
            <label class="col-form-label col-sm-4">Backup log sistema</label>
            <div class="col-sm-8">
              <input type="hidden" name="2[uplog]">
              <input class="chk" type="checkbox" name="2[uplog]">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-4">Backup IP visitadas</label>
            <div class="col-sm-8">
              <input type="hidden" name="2[upvisitadas]">
              <input class="chk" type="checkbox" name="2[upvisitadas]">
            </div>
          </div>

          <div class="form-group row">
            <label class="col-form-label col-sm-4">Activar cloud</label>
            <div class="col-sm-8">
              <input type="hidden" name="2[estado]">
              <input class="chk" type="checkbox" name="2[estado]">
            </div>
          </div>


          <div class="panel-footer text-right">
            <button type="submit" class="btn-azul btn-sm m-l-5" onClick="spacebox();"><i class="far fa-save"></i> Guardar cambios</button>
          </div>


        </div>
      </div>
    </div> -->

  </form>

</div>
<!-- end row -->

<script>
  function gettoken() {
    var width = 800;
    var height = 500;
    var toppx = ($(window).height() / 2) - (height / 2);
    var leftpx = ($(window).width() / 2) - (width / 2);
    window.open("https://mikrowisp.net/box.php?id=" + $('#clienteid').val() + "&secret=" + $('#clientesecret').val(), "popupWindow", "width=" + width + ",height=" + height + ",scrollbars=no,left=" + leftpx + "top=" + toppx);
  }

  function spacedropbox() {
    $.ajax({
      url: "ajustes/spacedropbox",
      success: function(data) {
        $(".spacedropbox").html(data);
      },
      timeout: 8000 //in milliseconds
    });
  }

  function spacebox() {

    $.ajax({
      url: "ajax/ajustes?action=spacebox",
      success: function(data) {
        $(".spacebox").html(data);
      },
      timeout: 8000 //in milliseconds
    });

  }


  function getdevice() {
    $('.gatewayid').select2('destroy');
    $.post("ajax/ajustes", {
        action: "getdevice",
        api: $('.gatewaytoken').val()
      })
      .done(function(data) {
        if (data['estado'] == 'exito') {
          $('.gatewayid').html(data['salida']);
          $('.gatewayid option[value=""]').prop("selected", true);
        } else {
          alerta('error', data['salida'])
          $('.gatewayid').html('');
        }
        $('.gatewayid').select2();
      });
  }

  $(function() {

    var elems = Array.prototype.slice.call(document.querySelectorAll('.chk'));
    elems.forEach(function(html) {
      var switchery = new Switchery(html, {
        size: 'small',
        color: 'rgb(18, 187, 168)'
      });
    });

    $(".sl2").select2({
      placeholder: "Seleccionar..."
    });


    $('.frm-cloud').ajaxForm({
      success: function(data) {
        $('#gritter-notice-wrapper').remove();
        if(data=="success"){
         alerta('exito', 'Datos guardados correctamente');
         spacedropbox();
        }
        else{
        	 alerta('error', "Ha ocurrido un error");
        }
      },
      error: function(response) {
        $('#gritter-notice-wrapper').remove();
        alerta('error500', response['responseText']);
      }
    })

    App.setPageTitle('Configuración');

    spacedropbox();
   // spacebox();

  })
</script>

</script>