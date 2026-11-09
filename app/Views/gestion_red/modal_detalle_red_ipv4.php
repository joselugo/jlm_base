<div class="modal-body ">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table table-bordered">
    <thead>
      <tr>
        <th><?= $red->nombre; ?></th>
        <th align="center">Valor</th>
        <th align="center">Hex</th>
        <th align="center">Binario</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td align="right">Dirección IP:</td>
        <td><?= $red->red; ?></td>
        <td><?= $red->redH; ?></td>
        <td><?= $red->redB; ?></td>
      </tr>
      <tr>
        <td align="right">Máscara de subred:</td>
        <td><?= $red->mask; ?></td>
        <td><?= $red->maskH; ?></td>
        <td><?= $red->maskB; ?></td>
      </tr>
      <tr>
        <td align="right">Network Portion:</td>
        <td><?= $red->portion; ?></td>
        <td><?= $red->portionH; ?></td>
        <td><?= $red->portionB; ?></td>
      </tr>
      <tr>
        <td align="right">Número de direcciones IP:</td>
        <td colspan="3"><?= $red->ips; ?></td>
      </tr>
      <tr>
        <td align="right">Número de IP utilizables:</td>
        <td colspan="3"><?= $red->host; ?></td>
      </tr>
      <tr>
        <td align="right">Rango de direcciones IP:</td>
        <td colspan="3"><?= $red->rango; ?></td>
      </tr>
      <tr>
        <td align="right">Broadcast :</td>
        <td colspan="3"><?= $red->broadcast; ?></td>
      </tr>
    </tbody>
  </table>
</div>

<div class="modal-footer">
  <a href="javascript:;" class="btn btn-sm btn-white" data-dismiss="modal">Cerrar</a>

</div>

</form>


<script>
  $(function() {
    $('#modaltmp').modal('show');
  })
</script>