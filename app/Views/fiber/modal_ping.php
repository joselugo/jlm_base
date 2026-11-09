<div class="modal-body"
  style="background:#151515; color:#FFF; font-weight:600; padding:10px;font-family: Consolas,monaco,monospace; width: 100%">
  <?php foreach ($paquetes as $value) : ?>
  <?= utf8_encode($value) ?></br>
  <?php endforeach; ?>
</div>


</form>


<script>
$(function() {

  $('#router-form').ajaxForm({
    success: function(data) {
      $('#gritter-notice-wrapper').remove();
      if (data['estado'] == 'error') {
        alerta('error', data['salida']);
      } else {
        alerta('exito', data['salida']);
        loadurl('#ajax/router?action=edit&token=' + data['token'] + '&id=' + data['id']);
      }
    },
    beforeSubmit: function() {
      $('#modaltmp').modal('hide');
      alerta('loader', '');
    },
    error: function(response) {
      $('#gritter-notice-wrapper').remove();
      alerta('error500', response['responseText']);
    }
  })

  $('#password').password();
  $('#modaltmp').modal('show');


})
</script>
