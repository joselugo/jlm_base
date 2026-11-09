<section class="content">
<div class="box">
            <div class="box-header with-border" style="border-bottom: 4px #bbbbbb; background-color: #ebe7e7">
              <h5 class="box-title" style="text-transform: uppercase; font-size: 13px;"><b><i class="fa fa-user"></i> <?php echo $nombres?> <small><b>(#<?php echo $id;?>)</b></small></b></h5>

              <div class="box-tools pull-right">
                <a class="btn btn-xs btn-danger" id="close-box" data-toggle="tooltip" data-placement="bottom" data-original-title="Volver a lista de clientes"><i class="fa fa-times"></i></a>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <!-- Custom Tabs -->
<div class="row">
  <div class="nav-tabs-custom" style="margin: 5px; margin-top: -10px; margin-bottom: -30px; font-size: 13px;">
     <ul class="nav nav-tabs">
      <li class="active"><a href="#tab_1" data-toggle="tab"><span class="fa fa-info"></span> Datos</a></li>
      <li><a href="#tab_2" data-toggle="tab"> <span class="fa fa-list-ul"></span> Servicios</a></li>
      <li><a href="#tab_3" data-toggle="tab"> <span class="fa fa-usd"></span> Facturas</a></li>
      <li><a href="#tab_4" data-toggle="tab"> <span class="fa fa-file-o"></span> Documentos & Notas</a></li>
      <li><a href="#tab_5" data-toggle="tab"> <span class="fa fa-cog"></span> Log</a></li>
      <li><a href="javascript:void(0)" class="toolscliente" data-target="webuiPopover2"><i class="fa fa-wrench"></i> Herramientas</a></li>
      
     </ul>
   <div class="tab-content">
     <div class="tab-pane active" id="tab_1">
      <?= view("clientes/tabs/datos_cliente") ?>
     </div><!-- /.tab-pane -->
     <div class="tab-pane" id="tab_2">
      <?= view("clientes/tabs/servicios") ?>
     </div><!-- /.tab-pane -->
     
        <div class="tab-pane" id="tab_3">
        <?php //$this->load->view("clientes/tabs/facturas") ?>
      </div><!-- /.tab-pane -->


        <div class="tab-pane" id="tab_4">
        <?php //$this->load->view("clientes/tabs/notas") ?>
      </div><!-- /.tab-pane -->

       <div class="tab-pane" id="tab_5">
        <?php //$this->load->view("clientes/tabs/log") ?>
      </div><!-- /.tab-pane -->
       <div class="tab-pane" id="tab_6">
       <?php  //$this->load->view("clientes/tabs/herramientas") ?>
      </div><!-- /.tab-pane -->
                                    
                                    
                                   
   </div><!-- /.tab-content -->
  </div><!-- nav-tabs-custom -->
  </div>
</div>
            <!-- /.box-body -->
 </div>   
</section> 

<script type="text/javascript">
  

$('.toolscliente').webuiPopover({
title:'Herramientas',
placement:'bottom',
width:'240px',height:'auto',
animation:'pop',
closeable:true,
url:'#herramientascliente'
});
</script>

<div class="webui-popover pop bottom pop-out" id="webuiPopover2" style="display: none; width: 244px; top: 320px; left: 240.375px;"><div class="webui-arrow" style="left: 120px;"></div><div class="webui-popover-inner"><a href="#" class="close"></a><h3 class="webui-popover-title">Herramientas</h3><div class="webui-popover-content"><div id="herramientascliente" style=""><label>Seleccionar Servicio</label>
<select class="form-control" id="sl-service" name="sl-service" style="margin-bottom:8px">
<?=$option_servicios?>
</select>
<a class="btn btn-app btnremote" data-toggle="modal" href="tool.php?action=antena" data-remote="false" data-title="Modem Cliente" data-target="#modal-tool2"><i class="fa fa-wifi"></i> Revisar Modem </a>
<a class="btn btn-app btnremote" data-toggle="modal" href="tool.php?action=trafico" data-remote="false" data-title="Tráfico Cliente" data-target="#modal-tool"><i class="fa fa-bar-chart"></i> Tráfico </a>
<a class="btn btn-app btnremote" data-toggle="modal" href="tool.php?action=torch" data-remote="false" data-title="Torch Mikrotik" data-target="#modal-tool"><i class="fa fa-tachometer"></i> Torch Mikrotik </a>
<a class="btn btn-app btnremote" data-toggle="modal" href="tool.php?action=ping" data-remote="false" data-title="Ping Mikrotik" data-target="#modal-tool"><i class="fa fa-exchange"></i> Ping Mikrotik </a>

<script>
$(document).ready(function() {

$('.btnremote').click(function(e) {
e.preventDefault();
if($(this).data('title')=='Modem Cliente'){

var url = base_url+'herramientas/viewmodem/' + $('#sl-service').val();
//var url = base_url+'herramientas/viewmodem/59'
$('#modal-tool2 .modal-body').html('<div class="ubloader" style="padding:30px;"><i class="fa fa-spinner fa-spin fa-lg fa-fw margin-bottom"></i><span>Cargando...</span></div>').load(url);
$('#modal-tool2 h4').html($(this).data('title'));

}


else{
var url = $(this).prop('href')+'&id='+$('#sl-service').val();
$('#modal-tool .modal-body').html('<div class="ubloader" style="padding:30px;"><i class="fa fa-spinner fa-spin fa-lg fa-fw margin-bottom"></i><span>Cargando...</span></div>').load(url);
$('#modal-tool h4').html($(this).data('title'));
}

});
  
});

</script>
</div></div></div></div>


<div class="modal fade in" id="modal-tool2">
<div class="modal-dialog modal-lg">
<div class="modal-content">
<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fa fa-times"></i></button>
<h4 class="modal-title">Modem Cliente</h4>
</div>
<div class="modal-body no-padding">
kbhjvghvghvgvgv
</div>
</div>
</div>
</div>

  
