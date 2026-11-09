<?php
/**
 * Aviso de acceso denegado (ver acceso_denegado() en app/Helpers/sistema_helper.php).
 * Se inserta dentro del panel (#content o un modal), por eso trae sus propios estilos
 * y no depende de Font Awesome.
 *
 * @var string $permiso nombre legible del permiso que falta ("Ajustes → Zonas"), puede ir vacío
 * @var bool   $pagina  true si se abrió fuera del panel (dirección directa en el navegador)
 */
?>
<?php if ($pagina) : ?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Acceso denegado</title>
</head>
<body style="margin:0; background:#d9e0e7; font-family:'Open Sans', Arial, sans-serif;">
<?php endif; ?>
<style>
  .acceso-denegado { display:flex; align-items:center; justify-content:center; padding:48px 16px; }
  .acceso-denegado__tarjeta { background:#fff; border-radius:6px; box-shadow:0 1px 3px rgba(0,0,0,.08); max-width:440px; width:100%;
    padding:36px 32px 32px; text-align:center; color:#2d353c; }
  .acceso-denegado__icono { width:72px; height:72px; margin:0 auto 18px; border-radius:50%; background:#fdecea;
    display:flex; align-items:center; justify-content:center; }
  .acceso-denegado h2 { font-size:20px; font-weight:600; margin:0 0 8px; }
  .acceso-denegado p { font-size:14px; line-height:1.5; color:#6f7a83; margin:0 0 6px; }
  .acceso-denegado__permiso { display:inline-block; margin:10px 0 4px; padding:4px 12px; border-radius:12px; background:#f2f3f4;
    font-size:12px; color:#2d353c; }
  .acceso-denegado__acciones { margin-top:22px; display:flex; gap:10px; justify-content:center; flex-wrap:wrap; }
  .acceso-denegado__acciones a { display:inline-block; padding:8px 18px; border-radius:20px; font-size:13px; text-decoration:none;
    border:1px solid #348fe2; cursor:pointer; }
  .acceso-denegado__volver { color:#348fe2; background:#fff; }
  .acceso-denegado__inicio { color:#fff; background:#348fe2; }
</style>
<div class="acceso-denegado">
  <div class="acceso-denegado__tarjeta">
    <div class="acceso-denegado__icono">
      <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#e5534b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
        <rect x="4" y="11" width="16" height="10" rx="2"></rect>
        <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
      </svg>
    </div>
    <h2>No tienes acceso a esta sección</h2>
    <p><?= esc($motivo ?? 'Tu usuario no tiene permiso para ver o modificar esta información.') ?></p>
    <p>Si lo necesitas para tu trabajo, pide al administrador que te lo asigne.</p>
    <?php if ($permiso !== '') : ?>
      <span class="acceso-denegado__permiso">Permiso requerido: <strong><?= esc($permiso) ?></strong></span>
    <?php endif; ?>
    <div class="acceso-denegado__acciones">
      <a class="acceso-denegado__volver" onclick="history.back(); return false;" href="#">Volver</a>
      <a class="acceso-denegado__inicio" href="<?= base_url() ?>">Ir al inicio</a>
    </div>
  </div>
</div>
<?php if ($pagina) : ?>
</body>
</html>
<?php endif; ?>
