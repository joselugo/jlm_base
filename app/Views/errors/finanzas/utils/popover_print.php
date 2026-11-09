<div class="m-r-5 m-b-5 text-center">
  <a style="width: 130px" class="btn btn-white" onClick="printer('finanzas/transacciones?action=printer&id=<?= $idfactura; ?>','recibo')"><i class="fas fa-print"></i> Recibo</a>
</div>
<div class="m-r-5 m-b-5 text-center">
  <a style="width: 130px" class="btn btn-white" onClick="printer('finanzas/transacciones?action=printer&id=<?= $idfactura; ?>&d=.pdf','recibopos')"><i class="fas fa-print"></i> Recibo POS</a>
</div>
<div class="m-r-5 m-b-5 text-center">
  <a style="width: 130px" class="btn btn-white" onClick="printer('finanzas/transacciones?action=printer&id=<?= $idfactura; ?>&d=.pdf','factura')"><i class="fas fa-print"></i> Factura</a>
</div>
<div class="m-r-5 m-b-5 text-center  d-none">
  <a style="width: 130px" class="btn btn-white" onClick="printer('finanzas/transacciones?action=printer&id=<?= $id; ?>&d=.pdf','afip')"><i class="fas fa-print"></i> AFIP</a>
</div>


<script>
  function printer(url, tipo) {
    closep();
    alerta('loader', 'generando PDF...');
    $('#printframe').remove();
    var t = $("<iframe></iframe>");
    t.attr("id", "printframe").attr("name", "printframe").attr("src", "about:blank").css("width", "400").css("height", "200").css("position", "absolute").css("left", "-99999px").appendTo($("body:first"));
    null != t && null != url && (t.attr("src", url + "&tipo=" + tipo), t.on('load', function() {
      $('#gritter-notice-wrapper').remove();
      if (getChromeVersion() > 76) {
        t.get(0).contentWindow.print();
      }
    }))
  }
</script>