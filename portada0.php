<?php
session_start();
include_once('funciones.php');

$error_login    = '';
$error_registro = '';

// ── PROCESAR LOGIN ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'login') {
    $usuario    = trim($_POST['usuario']    ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');

    if (!$usuario || !$contrasena) {
        $error_login = 'Completá usuario y contraseña.';
    } else {
        $cuenta = verificarLogin($usuario, $contrasena);
        if ($cuenta) {
            $_SESSION['nombre_usuario'] = $cuenta['usuario'];
            header('Location: index.php');
            exit;
        } else {
            $error_login = 'Usuario o contraseña incorrectos.';
        }
    }
}

// ── PROCESAR REGISTRO ──
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'registro') {
    $usuario    = trim($_POST['usuario']    ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');
    $confirmar  = trim($_POST['confirmar']  ?? '');

    if (!$usuario || !$contrasena || !$confirmar) {
        $error_registro = 'Completá todos los campos.';
    } elseif ($contrasena !== $confirmar) {
        $error_registro = 'Las contraseñas no coinciden.';
    } elseif (strlen($contrasena) < 4) {
        $error_registro = 'La contraseña debe tener al menos 4 caracteres.';
    } else {
        $resultado = crearCuenta($usuario, $contrasena);
        if ($resultado === 'ok') {
            $_SESSION['nombre_usuario'] = $usuario;
            header('Location: index.php');
            exit;
        } elseif ($resultado === 'existe') {
            $error_registro = 'Ese nombre de usuario ya está en uso.';
        } else {
            $error_registro = 'Hubo un error al crear la cuenta. Intentá de nuevo.';
        }
    }
}

$abrir_registro = (!empty($error_registro)) ? 'true' : 'false';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PILCHA IA — Tu estilista con inteligencia artificial</title>
<link rel="icon" type="image/png" href="/favicon.png">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="estilosport.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Instrument+Serif:ital@0;1&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

<style>
    /* Modal de registro con el mismo estilo oscuro que el panel de login */
    #modalRegistro .modal-box {
        background: #0d0d0d;
        border: 1px solid rgba(245,236,220,0.1);
    }

    #modalRegistro .modal-header {
        border-bottom: 1px solid rgba(245,236,220,0.1);
    }

    #modalRegistro .modal-close {
        color: rgba(245,236,220,0.5);
    }
    #modalRegistro .modal-close:hover {
        color: #F5ECDC;
    }

    #modalRegistro .modal-title {
        color: #F5ECDC;
    }

    #modalRegistro .modal-subtitle {
        color: rgba(245,236,220,0.5);
    }

    #modalRegistro .field-label {
        color: rgba(245,236,220,0.5);
    }

    #modalRegistro .field-input {
        background: rgba(245,236,220,0.05);
        border: 1px solid rgba(245,236,220,0.15);
        color: #F5ECDC;
    }

    #modalRegistro .field-input::placeholder {
        color: rgba(245,236,220,0.3);
    }

    #modalRegistro .field-input:focus {
        border-color: #800020;
        outline: none;
    }

    #modalRegistro .modal-title {
    font-family: var(--font-display) !important;
    font-size: 1.6rem !important;
    text-transform: uppercase !important;
    letter-spacing: 0.04em !important;
    text-align: left !important;
}
#modalRegistro .modal-subtitle {
    text-align: left !important;
    font-family: var(--font-mono) !important;
    font-size: 0.65rem !important;
}
#modalRegistro .modal-body {
    text-align: left !important;
    padding: 16px 24px 28px !important;
}
</style>
</head>
<body>

<!-- ══ NAV ══ -->
<nav class="nav">
  <a href="portada0.php" class="nav-brand">PILCHA<span> IA</span></a>
  <ul class="nav-center">
    <li><a href="historial.php">Mi Historial</a></li>
  </ul>
  <div class="nav-right">
    <?php if (isset($_SESSION['nombre_usuario'])): ?>
        <a href="historial.php" class="nav-btn-start">MI HISTORIAL</a>
    <?php endif; ?>
    <a href="index.php" class="nav-btn-start">COMENZAR</a>
    <button class="btn-user" id="btnUser" aria-label="Abrir panel de usuario">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
    </button>
  </div>
</nav>

<!-- ══ HERO SLIDER ══ -->
<section class="hero">
  <div class="slides-track" id="slidesTrack">

    <!-- Slide 1: Streetwear urbano -->
    <div class="slide active">
      <div class="slide-img" style="background-image:url('https://plus.unsplash.com/premium_photo-1752533866231-76d5a5ef552c?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D')"></div>
      <div class="slide-overlay"></div>
      <div class="slide-content">
        <div class="slide-tag"><span class="slide-tag-line"></span><span class="slide-tag-text">✦ Moda Urbana ✦</span></div>
        <h1 class="slide-title">Arma tu outfit<br><em>con nosotros</em></h1>
        <p class="slide-sub">Tu look personalizado, generado por IA en segundos.</p>
        <a href="index.php" class="slide-btn">COMENZAR</a>
      </div>
    </div>

    <!-- Slide 2: Outfits frescos -->
    <div class="slide">
      <div class="slide-img" style="background-image:url('https://plus.unsplash.com/premium_photo-1750895096925-903f7ef8c1ff?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D')"></div>
      <div class="slide-overlay"></div>
      <div class="slide-content">
        <div class="slide-tag"><span class="slide-tag-line"></span><span class="slide-tag-text">✦ Streetwear ✦</span></div>
        <h1 class="slide-title">Arma tu outfit<br><em>con nosotros</em></h1>
        <p class="slide-sub">Estilo único que combina con quién sos.</p>
        <a href="index.php" class="slide-btn">COMENZAR</a>
      </div>
    </div>

    <!-- Slide 3: Ropa juvenil -->
    <div class="slide">
      <div class="slide-img" style="background-image:url('https://plus.unsplash.com/premium_photo-1778116512877-83bf67fe9fcb?q=80&w=869&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D')"></div>
      <div class="slide-overlay"></div>
      <div class="slide-content">
        <div class="slide-tag"><span class="slide-tag-line"></span><span class="slide-tag-text">✦ Diseño Moderno ✦</span></div>
        <h1 class="slide-title">Arma tu outfit<br><em>con nosotros</em></h1>
        <p class="slide-sub">IA que entiende tu cuerpo y tu paleta de colores.</p>
        <a href="index.php" class="slide-btn">COMENZAR</a>
      </div>
    </div>

    <!-- Slide 4: Fashion editorial -->
    <div class="slide">
      <div class="slide-img" style="background-image:url('https://images.unsplash.com/photo-1544441893-675973e31985?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D')"></div>
	  
      <div class="slide-overlay"></div>
      <div class="slide-content">
        <div class="slide-tag"><span class="slide-tag-line"></span><span class="slide-tag-text">✦ Ropa Juvenil ✦</span></div>
        <h1 class="slide-title">Arma tu outfit<br><em>con nosotros</em></h1>
        <p class="slide-sub">Looks que hablan por vos, construidos con inteligencia.</p>
        <a href="index.php" class="slide-btn">COMENZAR</a>
      </div>
    </div>

  </div><!-- /slides-track -->

  <!-- Counter -->
  <div class="slide-counter" id="slideCounter">01 / 04</div>

  <!-- Dots -->
  <div class="slider-nav" id="sliderDots">
    <button class="dot active" data-index="0" aria-label="Slide 1"></button>
    <button class="dot" data-index="1" aria-label="Slide 2"></button>
    <button class="dot" data-index="2" aria-label="Slide 3"></button>
    <button class="dot" data-index="3" aria-label="Slide 4"></button>
  </div>

  <!-- Arrows -->
  <div class="slider-arrows">
    <button class="arrow-btn" id="prevSlide" aria-label="Slide anterior">❮</button>
    <button class="arrow-btn" id="nextSlide" aria-label="Slide siguiente">❯</button>
  </div>
</section>

<!-- ══ FOOTER ══ -->
<footer>
  
</footer>

<!-- ══ SHARED OVERLAY ══ -->
<div class="overlay" id="overlay"></div>

<!-- ══ SIDE PANEL LOGIN ══ -->
<aside class="side-panel" id="sidePanel" role="dialog" aria-modal="true" aria-label="Iniciar sesión">
  <div class="panel-header">
    <span class="panel-logo">PILCHA<span> IA</span></span>
    <button class="panel-close" id="panelClose" aria-label="Cerrar panel">✕</button>
  </div>
  <div class="panel-body">

    <?php if (isset($_SESSION['nombre_usuario'])): ?>

        <!-- Usuario ya logueado -->
        <p class="panel-title">Hola, <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?> 👋</p>
        <p class="panel-sub">Ya tenés una sesión activa.</p>

        <a href="index.php" class="btn-ingresar" style="display:block;text-align:center;text-decoration:none;margin-bottom:12px;">
            ARMAR OUTFIT
        </a>
        <a href="logout.php" class="btn-ingresar" style="display:block;text-align:center;text-decoration:none;background:transparent;border:2px solid #800020;color:#800020;">
            CERRAR SESIÓN
        </a>

    <?php else: ?>

        <!-- Formulario de login (el que ya tenés) -->
        <p class="panel-title">Bienvenido</p>
        <p class="panel-sub">Ingresá para acceder a tu perfil y outfits guardados.</p>

        <?php if ($error_login): ?>
            <div class="msg-error" style="background:#fff0f0;border:1px solid #f5c6c6;color:#a00020;border-radius:8px;padding:10px 14px;font-size:0.85rem;margin-bottom:14px;">
                ⚠️ <?php echo htmlspecialchars($error_login); ?>
            </div>
        <?php endif; ?>

        <form action="portada0.php" method="POST">
            <input type="hidden" name="accion" value="login">
            <div class="field-group">
              <label class="field-label" for="loginUsuario">Usuario</label>
              <input class="field-input" type="text" id="loginUsuario" name="usuario" placeholder="Tu nombre de usuario" autocomplete="username" required>
            </div>
            <div class="field-group">
              <label class="field-label" for="loginPass">Contraseña</label>
              <input class="field-input" type="password" id="loginPass" name="contrasena" placeholder="••••••••" autocomplete="current-password" required>
            </div>
            <button type="submit" class="btn-ingresar">INGRESAR</button>
        </form>

        <div class="panel-links">
          <a href="#" onclick="openModal('registro'); return false;">Registrarse</a>
        </div>

    <?php endif; ?>

</div>
</aside>

<!-- ══ MODAL DE REGISTRO ══ -->
<div class="modal-overlay" id="modalRegistro" role="dialog" aria-modal="true">
  <div class="modal-box">
    <div class="modal-header">
      <span class="panel-logo" style="font-size:1.1rem;">PILCHA<span> IA</span></span>
      <button class="modal-close" data-close-modal="registro" aria-label="Cerrar">✕</button>
    </div>
    <div class="modal-body">
      <p class="modal-title">Crear cuenta</p>
      <p class="modal-subtitle">Elegí un usuario y contraseña para empezar.</p>

      <?php if ($error_registro): ?>
        <div class="msg-error" style="background:#fff0f0;border:1px solid #f5c6c6;color:#a00020;border-radius:8px;padding:10px 14px;font-size:0.85rem;margin-bottom:14px;">
            ⚠️ <?php echo htmlspecialchars($error_registro); ?>
        </div>
      <?php endif; ?>

      <form action="portada0.php" method="POST">
        <input type="hidden" name="accion" value="registro">
        <div class="field-group">
          <label class="field-label" for="regUsuario">Usuario</label>
          <input class="field-input" type="text" id="regUsuario" name="usuario" placeholder="Elegí un usuario" autocomplete="username" required>
        </div>
        <div class="field-group">
          <label class="field-label" for="regPass">Contraseña</label>
          <input class="field-input" type="password" id="regPass" name="contrasena" placeholder="••••••••" autocomplete="new-password" required minlength="4">
        </div>
        <div class="field-group">
          <label class="field-label" for="regConfirm">Confirmar contraseña</label>
          <input class="field-input" type="password" id="regConfirm" name="confirmar" placeholder="••••••••" autocomplete="new-password" required minlength="4">
        </div>
        <button type="submit" class="btn-ingresar">CREAR CUENTA</button>
      </form>
    </div>
  </div>
</div>



<script>
/* ── SLIDER ── */
const track       = document.getElementById('slidesTrack');
const slides      = document.querySelectorAll('.slide');
const dots        = document.querySelectorAll('.dot');
const counter     = document.getElementById('slideCounter');
const total       = slides.length;
let current       = 0;
let autoTimer;

function goTo(idx) {
  slides[current].classList.remove('active');
  dots[current].classList.remove('active');
  current = (idx + total) % total;
  slides[current].classList.add('active');
  dots[current].classList.add('active');
  track.style.transform = `translateX(-${current * 25}%)`;
  counter.textContent = String(current + 1).padStart(2,'0') + ' / ' + String(total).padStart(2,'0');
}

function startAuto() {
  clearInterval(autoTimer);
  autoTimer = setInterval(() => goTo(current + 1), 5000);
}

document.getElementById('nextSlide').addEventListener('click', () => { goTo(current + 1); startAuto(); });
document.getElementById('prevSlide').addEventListener('click', () => { goTo(current - 1); startAuto(); });
dots.forEach(d => d.addEventListener('click', () => { goTo(+d.dataset.index); startAuto(); }));

startAuto();

/* ── PANEL / OVERLAY ── */
const overlay   = document.getElementById('overlay');
const sidePanel = document.getElementById('sidePanel');
const btnUser   = document.getElementById('btnUser');
const panelClose = document.getElementById('panelClose');

function openPanel() {
  sidePanel.classList.add('open');
  overlay.classList.add('visible');
  document.body.style.overflow = 'hidden';
}
function closePanel() {
  sidePanel.classList.remove('open');
  overlay.classList.remove('visible');
  document.body.style.overflow = '';
}

btnUser.addEventListener('click', openPanel);
panelClose.addEventListener('click', closePanel);
overlay.addEventListener('click', (e) => {
  if (sidePanel.classList.contains('open')) closePanel();
});

/* ── SOCIAL MODALS ── */
const modals = {
  registro: document.getElementById('modalRegistro')
};

function openModal(provider) {
  const m = modals[provider];
  if (!m) return;
  closePanel();
  setTimeout(() => {
    m.classList.add('open');
    document.body.style.overflow = 'hidden';
  }, 320);
}
function closeModal(provider) {
  const m = modals[provider];
  if (!m) return;
  m.classList.remove('open');
  document.body.style.overflow = '';
}


document.querySelectorAll('[data-close-modal]').forEach(btn => {
  btn.addEventListener('click', () => closeModal(btn.dataset.closeModal));
});

Object.entries(modals).forEach(([key, el]) => {
  el.addEventListener('click', (e) => {
    if (e.target === el) closeModal(key);
  });
});

/* ESC key closes everything */
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    closePanel();
    Object.keys(modals).forEach(k => closeModal(k));
  }
});

/* Si hubo error de registro, reabrir el modal automáticamente */
if (<?php echo $abrir_registro; ?>) {
    openModal('registro');
}
</script>

</body>
</html>