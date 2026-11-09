<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          Errores <?= "Totales: ".$total; ?> <?php if ($total != 0) : ?><small> Existentes:<?= $existentes; ?>  Perfiles:<?= $perfiles; ?>  Otros:<?= $otros; ?></small><?php endif; ?>
        </div>
        <div class="card-body">
          <div class="container">
            <div class="row">
              <?php if ($estado == 1) : ?>
                <div class="card">
                  <div class="card-body">
                    No se encontro ningun error al importar a todos los clientes.
                  </div>
                </div>
              <?php else : ?>
                <?php foreach ($clientes as $cliente) : ?>
                  <div class="col-3">
                    <div class="card mb-3">
                      <img src="data:image/jpeg;base64,<?= $cliente['img'] == "" ? "" : base64_encode($cliente['img']); ?>" class="<?= $cliente['img'] == "" ? "avataradmin" : ""; ?>" data-name="<?= $cliente['nombre']; ?>">
                      <div class="card-body">
                        <h5 class="card-title"><?= $cliente['nombre']; ?></h5>
                        <p class="card-text"><?= $cliente['error']; ?></p>
                      </div>
                      <div class="card-body">
                        <a target="_blank" href="#clientes/detalleuser?id=<?= $cliente['id_cliente']; ?>">Ir al cliente </a>
                        <?php if ($cliente['id_perfil'] != null) : ?>
                          <a onclick="getmodal('servicios/modal_editar?id=<?= $cliente['id_perfil']; ?>','Editar Perfil','lg');" class="btn btn-light" data-original-title="Editar">Editar perfil</a>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $('.avataradmin').initial({
    height: 34,
    width: 34,
    charCount: 2,
    fontSize: 15,
    fontWeight: 500
  });
</script>