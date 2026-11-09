<?php
/**
 * Inicio de sesión del administrador.
 *
 * - Banner de fondo: imagen elegida en Ajustes > General ("Imagen Login administrador",
 *   ajuste imgloginadmin). Llega en $img; si el controlador no la manda, se lee del ajuste.
 * - Logo: logo general del sistema (Ajustes > General), ver logo_sistema_url().
 * - $vista_previa: true cuando se muestra en el iframe de Ajustes (el formulario no envía).
 *
 * @var string|null $img
 * @var bool|null   $vista_previa
 */
$img          = basename((string) ($img ?? '')) ?: ajuste_sistema('imgloginadmin', 'login-bg-12.jpg');
$vista_previa = !empty($vista_previa);
$empresa      = ajuste_sistema('nombre_empresa', 'Panel de administración');
$banner       = base_url('public/images/login-bg/' . rawurlencode($img));
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Iniciar sesión · <?= esc($empresa) ?></title>
  <link rel="shortcut icon" type="image/png" href="<?= base_url() ?>public/favicon.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primario: #348fe2;
      --primario-oscuro: #1f6fc0;
      --texto: #1e2933;
      --texto-suave: #6b7785;
      --borde: #dfe4ea;
      --campo: #f6f8fa;
      --error: #d64541;
      --error-fondo: #fdecea;
      --aviso-fondo: #fff7e6;
      --aviso: #a8650b;
    }
    * { box-sizing: border-box; }
    html, body { height: 100%; margin: 0; }
    body {
      font-family: 'Inter', 'Open Sans', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
      color: var(--texto);
      background: #10171e;
      -webkit-font-smoothing: antialiased;
    }

    /* ---------- Banner de fondo ---------- */
    .fondo { position: fixed; inset: 0; overflow: hidden; z-index: 0; }
    .fondo__imagen {
      position: absolute; inset: -4%;
      background: #1b2530 center / cover no-repeat;
      animation: acercar 40s ease-in-out infinite alternate;
    }
    .fondo__velo {
      position: absolute; inset: 0;
      background:
        radial-gradient(1200px 600px at 15% 85%, rgba(52, 143, 226, .35), transparent 60%),
        linear-gradient(115deg, rgba(10, 16, 24, .82) 0%, rgba(10, 16, 24, .55) 45%, rgba(10, 16, 24, .25) 100%);
    }
    @keyframes acercar { from { transform: scale(1); } to { transform: scale(1.08); } }
    @media (prefers-reduced-motion: reduce) { .fondo__imagen { animation: none; } }

    /* ---------- Distribución ---------- */
    .pagina {
      position: relative; z-index: 1; min-height: 100%;
      display: grid; grid-template-columns: 1fr minmax(380px, 460px);
      align-items: center; gap: 48px;
      padding: 48px clamp(24px, 6vw, 96px);
    }
    .bienvenida { color: #fff; max-width: 560px; animation: entrar .7s ease both; }
    .bienvenida__etiqueta {
      display: inline-flex; align-items: center; gap: 8px;
      font-size: 12px; font-weight: 600; letter-spacing: .08em; text-transform: uppercase;
      padding: 6px 12px; border-radius: 999px;
      background: rgba(255, 255, 255, .12); border: 1px solid rgba(255, 255, 255, .18);
      backdrop-filter: blur(6px);
    }
    .bienvenida__etiqueta::before { content: ''; width: 7px; height: 7px; border-radius: 50%; background: #3ddc97; box-shadow: 0 0 0 3px rgba(61, 220, 151, .25); }
    .bienvenida h1 { font-size: clamp(30px, 3.4vw, 46px); line-height: 1.12; font-weight: 700; margin: 20px 0 14px; letter-spacing: -.02em; }
    .bienvenida p { font-size: 16px; line-height: 1.6; color: rgba(255, 255, 255, .78); margin: 0; }

    /* ---------- Tarjeta ---------- */
    .tarjeta {
      background: rgba(255, 255, 255, .97);
      border-radius: 20px;
      box-shadow: 0 30px 60px -20px rgba(0, 0, 0, .55), 0 0 0 1px rgba(255, 255, 255, .4) inset;
      padding: 40px 36px 28px;
      animation: entrar .6s .08s ease both;
    }
    @keyframes entrar { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: none; } }
    .tarjeta__logo { display: flex; justify-content: center; margin-bottom: 26px; min-height: 48px; }
    .tarjeta__logo img { max-width: 220px; max-height: 72px; width: auto; height: auto; object-fit: contain; }
    .tarjeta h2 { font-size: 22px; font-weight: 700; margin: 0 0 6px; text-align: center; letter-spacing: -.01em; }
    .tarjeta .subtitulo { font-size: 14px; color: var(--texto-suave); text-align: center; margin: 0 0 26px; }

    .campo { margin-bottom: 16px; }
    .campo label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 7px; }
    .campo__caja { position: relative; }
    .campo__caja svg.icono { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #93a0ad; pointer-events: none; }
    .campo input {
      width: 100%; height: 48px; padding: 0 44px 0 44px;
      font: inherit; font-size: 15px; color: var(--texto);
      background: var(--campo); border: 1.5px solid var(--borde); border-radius: 12px;
      transition: border-color .15s, box-shadow .15s, background .15s;
    }
    .campo input:focus { outline: none; background: #fff; border-color: var(--primario); box-shadow: 0 0 0 4px rgba(52, 143, 226, .15); }
    .campo input:focus + svg.icono, .campo__caja:focus-within svg.icono { color: var(--primario); }
    .ver-clave {
      position: absolute; right: 6px; top: 50%; transform: translateY(-50%);
      width: 36px; height: 36px; border: 0; border-radius: 8px; background: transparent;
      color: #93a0ad; cursor: pointer; display: flex; align-items: center; justify-content: center;
    }
    .ver-clave:hover { color: var(--texto); background: #eef1f4; }
    .mayusculas { display: none; font-size: 12px; color: var(--aviso); margin-top: 6px; }

    .opciones { display: flex; align-items: center; justify-content: space-between; margin: 4px 0 22px; font-size: 13px; color: var(--texto-suave); }
    .opciones label { display: inline-flex; align-items: center; gap: 8px; cursor: pointer; }
    .opciones input { width: 16px; height: 16px; accent-color: var(--primario); }

    .mensaje { display: none; align-items: flex-start; gap: 10px; font-size: 13px; line-height: 1.45; padding: 11px 14px; border-radius: 10px; margin-bottom: 16px; }
    .mensaje.visible { display: flex; }
    .mensaje--error { background: var(--error-fondo); color: var(--error); }
    .mensaje--aviso { background: var(--aviso-fondo); color: var(--aviso); }
    .sacudir { animation: sacudir .4s; }
    @keyframes sacudir { 20%, 60% { transform: translateX(-6px); } 40%, 80% { transform: translateX(6px); } }

    .boton {
      width: 100%; height: 48px; border: 0; border-radius: 12px; cursor: pointer;
      font: inherit; font-size: 15px; font-weight: 600; color: #fff;
      background: linear-gradient(135deg, var(--primario), var(--primario-oscuro));
      box-shadow: 0 10px 20px -8px rgba(52, 143, 226, .7);
      display: inline-flex; align-items: center; justify-content: center; gap: 10px;
      transition: transform .12s, box-shadow .12s, opacity .12s;
    }
    .boton:hover { transform: translateY(-1px); box-shadow: 0 14px 24px -10px rgba(52, 143, 226, .8); }
    .boton:active { transform: none; }
    .boton[disabled] { opacity: .75; cursor: progress; transform: none; }
    .boton .girando { display: none; width: 18px; height: 18px; border: 2px solid rgba(255, 255, 255, .45); border-top-color: #fff; border-radius: 50%; animation: girar .7s linear infinite; }
    .boton.cargando .girando { display: inline-block; }
    @keyframes girar { to { transform: rotate(360deg); } }
    .enlace { background: none; border: 0; padding: 0; font: inherit; font-size: 13px; color: var(--primario); cursor: pointer; }

    .pie { margin-top: 24px; padding-top: 18px; border-top: 1px solid #edf0f3; text-align: center; font-size: 12px; color: #9aa5b1; }
    .paso-cambio { display: none; }
    .tarjeta.cambiando .paso-acceso { display: none; }
    .tarjeta.cambiando .paso-cambio { display: block; }

    /* ---------- Pantallas chicas ---------- */
    @media (max-width: 991px) {
      .pagina { grid-template-columns: 1fr; justify-items: center; padding: 32px 16px; gap: 24px; }
      .bienvenida { text-align: center; }
      .bienvenida h1 { font-size: 28px; margin: 14px 0 8px; }
      .bienvenida p { display: none; }
      .tarjeta { width: 100%; max-width: 420px; padding: 32px 22px 22px; }
    }
    @media (max-height: 560px) and (max-width: 991px) { .bienvenida { display: none; } }
  </style>
</head>
<body>
  <div class="fondo" aria-hidden="true">
    <div class="fondo__imagen" style="background-image: url('<?= esc($banner, 'attr') ?>');" data-id="login-cover-image"></div>
    <div class="fondo__velo"></div>
  </div>

  <main class="pagina">
    <section class="bienvenida">
      <span class="bienvenida__etiqueta">Panel de administración</span>
      <h1><?= esc($empresa) ?></h1>
      <p>Administra clientes, servicios, eventos, agenda desde un solo lugar.</p>
    </section>

    <section class="tarjeta" id="tarjeta">
      <div class="tarjeta__logo">
        <img src="<?= logo_sistema_url() ?>" alt="<?= esc($empresa, 'attr') ?>">
      </div>

      <!-- Paso 1: acceso -->
      <div class="paso-acceso">
        <h2>Bienvenido de nuevo</h2>
        <p class="subtitulo">Ingresa tus datos para continuar</p>

        <div class="mensaje mensaje--error" id="mensaje" role="alert">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
          <span id="mensaje-texto"></span>
        </div>

        <form id="loginForm" method="post" novalidate>
          <div class="campo">
            <label for="login-login">Usuario</label>
            <div class="campo__caja">
              <input type="text" id="login-login" name="login" autocomplete="username" placeholder="Tu usuario" required autofocus>
              <svg class="icono" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
          </div>

          <div class="campo">
            <label for="password-login">Contraseña</label>
            <div class="campo__caja">
              <input type="password" id="password-login" name="password" autocomplete="current-password" placeholder="Tu contraseña" required>
              <svg class="icono" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
              <button type="button" class="ver-clave" data-campo="password-login" aria-label="Mostrar contraseña" title="Mostrar contraseña">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
            <div class="mayusculas" id="aviso-mayusculas">Bloq Mayús está activado</div>
          </div>

          <div class="opciones">
            <label><input type="checkbox" id="recordar"> Recordar usuario</label>
          </div>

          <button type="submit" class="boton" id="boton-ingresar">
            <span class="girando" aria-hidden="true"></span>
            <span class="texto">Ingresar</span>
          </button>
        </form>
      </div>

      <!-- Paso 2: cambio obligatorio de contraseña (respuesta "change" del servidor) -->
      <div class="paso-cambio">
        <h2>Actualiza tu contraseña</h2>
        <p class="subtitulo">Por seguridad, es necesario que definas una contraseña nueva.</p>

        <div class="mensaje mensaje--error" id="mensaje-cambio" role="alert">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
          <span id="mensaje-cambio-texto"></span>
        </div>

        <form id="cambioForm" novalidate>
          <div class="campo">
            <label for="clave-nueva">Contraseña nueva</label>
            <div class="campo__caja">
              <input type="password" id="clave-nueva" autocomplete="new-password" placeholder="Mínimo 8 caracteres" required>
              <svg class="icono" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
              <button type="button" class="ver-clave" data-campo="clave-nueva" aria-label="Mostrar contraseña" title="Mostrar contraseña">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
          </div>
          <div class="campo">
            <label for="clave-confirmar">Confirmar contraseña</label>
            <div class="campo__caja">
              <input type="password" id="clave-confirmar" autocomplete="new-password" placeholder="Repite la contraseña" required>
              <svg class="icono" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>
            </div>
          </div>
          <button type="submit" class="boton" id="boton-cambiar" style="margin-top:6px">
            <span class="girando" aria-hidden="true"></span>
            <span class="texto">Guardar y entrar</span>
          </button>
          <div style="text-align:center; margin-top:14px">
            <button type="button" class="enlace" id="cancelar-cambio">Volver</button>
          </div>
        </form>
      </div>

      <div class="pie">© <?= date('Y') ?> <?= esc($empresa) ?></div>
    </section>
  </main>

  <script src="<?= base_url() ?>public/plugins/jquery/jquery-3.2.1.min.js"></script>
  <script>
    var base_url = "<?= base_url() ?>";
    var VISTA_PREVIA = <?= $vista_previa ? 'true' : 'false' ?>;

    (function ($) {
      var $tarjeta = $('#tarjeta');
      var credenciales = { login: '', password: '' };

      function guardar(clave, valor) { try { valor === null ? localStorage.removeItem(clave) : localStorage.setItem(clave, valor); } catch (e) {} }
      function leer(clave) { try { return localStorage.getItem(clave); } catch (e) { return null; } }

      function mostrarMensaje(id, texto, tipo) {
        var $m = $('#' + id);
        $m.removeClass('mensaje--error mensaje--aviso').addClass('mensaje--' + (tipo || 'error') + ' visible');
        $('#' + id + '-texto').text(texto);
        $tarjeta.removeClass('sacudir');
        void $tarjeta[0].offsetWidth; // reinicia la animación
        $tarjeta.addClass('sacudir');
      }
      function ocultarMensaje(id) { $('#' + id).removeClass('visible'); }
      function cargando($boton, si) { $boton.prop('disabled', si).toggleClass('cargando', si); }

      // Recordar usuario (solo en este navegador)
      var recordado = leer('login_usuario');
      if (recordado) {
        $('#login-login').val(recordado);
        $('#recordar').prop('checked', true);
        $('#password-login').trigger('focus');
      }

      // Mostrar / ocultar contraseña
      $('.ver-clave').on('click', function () {
        var $campo = $('#' + $(this).data('campo'));
        var visible = $campo.attr('type') === 'text';
        $campo.attr('type', visible ? 'password' : 'text');
        $(this).attr('aria-label', visible ? 'Mostrar contraseña' : 'Ocultar contraseña');
      });

      // Aviso de Bloq Mayús
      $('#password-login').on('keyup keydown', function (e) {
        var activo = e.originalEvent && e.originalEvent.getModifierState && e.originalEvent.getModifierState('CapsLock');
        $('#aviso-mayusculas').toggle(!!activo);
      });

      // Paso 1: iniciar sesión (respuestas: success, error, change)
      $('#loginForm').on('submit', function (e) {
        e.preventDefault();
        if (VISTA_PREVIA) return;
        var l = $.trim($('#login-login').val());
        var p = $('#password-login').val();
        if (l.length < 2) { mostrarMensaje('mensaje', 'Escribe tu usuario.'); $('#login-login').trigger('focus'); return; }
        if (p.length < 2) { mostrarMensaje('mensaje', 'Escribe tu contraseña.'); $('#password-login').trigger('focus'); return; }

        ocultarMensaje('mensaje');
        cargando($('#boton-ingresar'), true);
        $.ajax({ type: 'POST', url: base_url + 'seguridad/iniciar_sesion', data: { login: l, password: p } })
          .done(function (data) {
            if (data === 'success') {
              guardar('login_usuario', $('#recordar').is(':checked') ? l : null);
              $('#boton-ingresar .texto').text('Entrando…');
              window.location.href = base_url;
              return;
            }
            cargando($('#boton-ingresar'), false);
            if (data === 'change') {
              credenciales = { login: l, password: p };
              guardar('login_usuario', $('#recordar').is(':checked') ? l : null);
              $tarjeta.addClass('cambiando');
              $('#clave-nueva').trigger('focus');
            } else {
              mostrarMensaje('mensaje', 'Usuario o contraseña incorrectos.');
              $('#password-login').val('').trigger('focus');
            }
          })
          .fail(function () {
            cargando($('#boton-ingresar'), false);
            mostrarMensaje('mensaje', 'No se pudo conectar con el servidor. Revisa tu conexión e inténtalo de nuevo.', 'aviso');
          });
      });

      // Paso 2: cambio obligatorio (respuestas: success, error, passold)
      $('#cambioForm').on('submit', function (e) {
        e.preventDefault();
        var nueva = $('#clave-nueva').val();
        var confirmar = $('#clave-confirmar').val();
        if (nueva.length < 8) { mostrarMensaje('mensaje-cambio', 'La contraseña debe tener al menos 8 caracteres.'); return; }
        if (nueva !== confirmar) { mostrarMensaje('mensaje-cambio', 'Las contraseñas no coinciden.'); return; }
        if (nueva === credenciales.password) { mostrarMensaje('mensaje-cambio', 'La nueva contraseña no puede ser igual a la anterior.'); return; }

        ocultarMensaje('mensaje-cambio');
        cargando($('#boton-cambiar'), true);
        $.ajax({ type: 'POST', url: base_url + 'seguridad/iniciar_sesion', data: { login: credenciales.login, password: credenciales.password, change: nueva } })
          .done(function (data) {
            if (data === 'success') { window.location.href = base_url; return; }
            cargando($('#boton-cambiar'), false);
            if (data === 'passold') {
              mostrarMensaje('mensaje-cambio', 'La nueva contraseña no puede ser igual a ninguna que hayas usado antes.');
            } else {
              mostrarMensaje('mensaje-cambio', 'No se pudo cambiar la contraseña. Vuelve a intentarlo.');
            }
          })
          .fail(function () {
            cargando($('#boton-cambiar'), false);
            mostrarMensaje('mensaje-cambio', 'No se pudo conectar con el servidor.', 'aviso');
          });
      });

      $('#cancelar-cambio').on('click', function () {
        $tarjeta.removeClass('cambiando');
        $('#clave-nueva, #clave-confirmar, #password-login').val('');
        ocultarMensaje('mensaje-cambio');
        $('#password-login').trigger('focus');
      });
    })(jQuery);
  </script>
</body>
</html>
