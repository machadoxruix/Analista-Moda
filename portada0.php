<?php
session_start();
if (isset($_SESSION['nombre_usuario'])) {
    header('Location: index.php');
    exit;
}
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

</head>
<body>

<!-- ══ NAV ══ -->
<nav class="nav">
  <a href="portada0.php" class="nav-brand">PILCHA<span> IA</span></a>
  <ul class="nav-center">
    <li><a href="#">Colecciones</a></li>
    <li><a href="#">Cómo Funciona</a></li>
  </ul>
  <div class="nav-right">
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
    <p class="panel-title">Bienvenido</p>
    <p class="panel-sub">Ingresá para acceder a tu perfil y outfits guardados.</p>

    <div class="field-group">
      <label class="field-label" for="loginEmail">Email</label>
      <input class="field-input" type="email" id="loginEmail" placeholder="tu@email.com" autocomplete="email">
    </div>
    <div class="field-group">
      <label class="field-label" for="loginPass">Contraseña</label>
      <input class="field-input" type="password" id="loginPass" placeholder="••••••••" autocomplete="current-password">
    </div>

    <button class="btn-ingresar" onclick="alert('Login simulado')">INGRESAR</button>

    <div class="divider">o continuá con</div>

    <div class="social-btns">
      <!-- Google -->
      <button class="btn-social" data-provider="google">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
          <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
          <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
          <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        Ingresar con Google
      </button>
      <!-- Facebook -->
      <button class="btn-social" data-provider="facebook">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" fill="#1877F2"/>
        </svg>
        Ingresar con Facebook
      </button>
      <!-- Apple -->
      <button class="btn-social" data-provider="apple">
        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path d="M12.152 6.896c-.948 0-2.415-1.078-3.96-1.04-2.04.027-3.91 1.183-4.961 3.014-2.117 3.675-.546 9.103 1.519 12.09 1.013 1.454 2.208 3.09 3.792 3.039 1.52-.065 2.09-.987 3.935-.987 1.831 0 2.35.987 3.96.948 1.637-.026 2.676-1.48 3.676-2.948 1.156-1.688 1.636-3.325 1.662-3.415-.039-.013-3.182-1.221-3.22-4.857-.026-3.04 2.48-4.494 2.597-4.559-1.429-2.09-3.623-2.324-4.39-2.376-2-.156-3.675 1.09-4.61 1.09zM15.53 3.83c.843-1.012 1.4-2.427 1.245-3.83-1.207.052-2.662.805-3.532 1.818-.78.896-1.454 2.338-1.273 3.714 1.338.104 2.715-.688 3.559-1.701" fill="#000"/>
        </svg>
        Ingresar con Apple
      </button>
    </div>

    <div class="panel-links">
      <a href="#">Registrarse</a>
      <a href="#">Restablecer contraseña</a>
    </div>
  </div>
</aside>

<!-- ══ GOOGLE MODAL ══ -->
<div class="modal-overlay" id="modalGoogle" role="dialog" aria-modal="true">
  <div class="modal-box">
    <div class="modal-header">
      <svg height="24" viewBox="0 0 74 24" width="74" xmlns="http://www.w3.org/2000/svg"><path d="M34.214 10.491h-9.68v2.86h6.817c-.333 3.948-3.62 5.633-6.804 5.633-4.01 0-7.507-3.163-7.507-7.507 0-4.23 3.35-7.557 7.514-7.557 3.228 0 5.131 2.059 5.131 2.059l2.01-2.083S29.448.5 24.047.5C17.94.5 12.5 5.87 12.5 12.477c0 6.477 5.235 12.023 11.697 12.023 5.68 0 11.1-3.888 11.1-11.124 0-1.396-.183-2.885-.183-2.885h.1zm8.328-3.25c-3.855 0-6.613 3.018-6.613 6.567 0 3.602 2.688 6.654 6.658 6.654 3.598 0 6.565-2.742 6.565-6.59 0-4.38-3.246-6.63-6.61-6.63zm.042 2.656c1.896 0 3.703 1.533 3.703 3.97 0 2.39-1.8 3.953-3.714 3.953-2.11 0-3.77-1.688-3.77-3.97 0-2.224 1.628-3.953 3.78-3.953zm14.43-2.656c-3.855 0-6.614 3.018-6.614 6.567 0 3.602 2.688 6.654 6.658 6.654 3.598 0 6.565-2.742 6.565-6.59 0-4.38-3.246-6.63-6.61-6.63zm.042 2.656c1.895 0 3.702 1.533 3.702 3.97 0 2.39-1.8 3.953-3.714 3.953-2.11 0-3.77-1.688-3.77-3.97 0-2.224 1.628-3.953 3.782-3.953zm13.18-2.63c-3.596 0-6.43 3.15-6.43 6.6 0 3.882 3.23 6.62 6.37 6.62 1.91 0 2.927-.757 3.674-1.628v1.321c0 2.255-1.37 3.592-3.43 3.592-1.993 0-3.003-1.482-3.355-2.327l-2.535 1.058c.85 1.803 2.557 3.923 5.912 3.923 3.53 0 6.133-2.22 6.133-6.889V7.6h-2.73v1.107c-.875-.944-2.07-1.34-3.61-1.34zm.264 2.63c1.76 0 3.554 1.505 3.554 3.986 0 2.52-1.79 3.937-3.59 3.937-1.91 0-3.668-1.55-3.668-3.91 0-2.475 1.818-4.012 3.703-4.012zm10.185-9.25v19.47h2.868V.647h-2.868z" fill="#5f6368"/></svg>
      <button class="modal-close" data-close-modal="google" aria-label="Cerrar">✕</button>
    </div>
    <div class="modal-body">
      <div class="modal-provider-logo">
        <svg width="48" height="48" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
          <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
          <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
          <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
      </div>
      <p class="modal-title">Acceder con Google</p>
      <p class="modal-subtitle">Elegí una cuenta para continuar en PILCHA IA</p>
      <div class="account-list">
        <div class="account-item" onclick="alert('Cuenta seleccionada')">
          <div class="account-avatar" style="background:#e8f0fe;color:#1a73e8;">P</div>
          <div class="account-info">
            <p class="account-name">Pilcha Usuario</p>
            <p class="account-email">usuario@gmail.com</p>
          </div>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#5f6368" stroke-width="2" style="margin-left:auto"><path d="M9 18l6-6-6-6"/></svg>
        </div>
        <div class="account-item" onclick="alert('Cuenta seleccionada')">
          <div class="account-avatar" style="background:#fce8e6;color:#d93025;">M</div>
          <div class="account-info">
            <p class="account-name">Mi Otra Cuenta</p>
            <p class="account-email">otroemail@gmail.com</p>
          </div>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#5f6368" stroke-width="2" style="margin-left:auto"><path d="M9 18l6-6-6-6"/></svg>
        </div>
      </div>
      <button class="btn-otra-cuenta" onclick="alert('Redirigiendo a login de Google...')">Usar otra cuenta</button>
      <div class="modal-footer-links">
        <a href="#">Español</a>
        <div style="display:flex;gap:16px">
          <a href="#">Ayuda</a>
          <a href="#">Privacidad</a>
          <a href="#">Términos</a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ══ FACEBOOK MODAL ══ -->
<div class="modal-overlay" id="modalFacebook" role="dialog" aria-modal="true">
  <div class="modal-box modal-fb">
    <div class="modal-header">
      <svg height="28" viewBox="0 0 90 90" xmlns="http://www.w3.org/2000/svg"><path d="M90 15.001C90 7.17 82.83 0 75 0H15C7.17 0 0 7.17 0 15.001v59.998C0 82.83 7.17 90 15 90h35.062V55.928H37.501v-14.73h12.561V31.935c0-12.432 7.583-19.242 18.702-19.242 5.33 0 9.905.394 11.232.572v13.025h-7.707c-6.047 0-7.222 2.87-7.222 7.092v9.296h14.44l-1.88 14.73H65.067V90H75c7.83 0 15-7.17 15-15.001V15.001z" fill="#1877f2"/></svg>
      <button class="modal-close" data-close-modal="facebook" aria-label="Cerrar" style="color:#5f6368">✕</button>
    </div>
    <div class="modal-body">
      <p class="modal-title" style="font-family:-apple-system,Arial,sans-serif;font-size:1.3rem;font-weight:700;color:#1c1e21">Acceder con Facebook</p>
      <p class="modal-subtitle" style="font-family:-apple-system,Arial,sans-serif;color:#606770">Elegí tu cuenta para continuar en PILCHA IA</p>
      <div class="account-list" style="border-color:#dddfe2">
        <div class="account-item" onclick="alert('Cuenta de Facebook seleccionada')">
          <div class="account-avatar" style="background:#1877f2;color:white;font-size:20px">👤</div>
          <div class="account-info">
            <p class="account-name" style="font-family:-apple-system,Arial,sans-serif;color:#1c1e21;font-weight:600">Tu Nombre</p>
            <p class="account-email" style="font-family:-apple-system,Arial,sans-serif;color:#606770">Continuar como esta cuenta</p>
          </div>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#606770" stroke-width="2" style="margin-left:auto"><path d="M9 18l6-6-6-6"/></svg>
        </div>
      </div>
      <button class="btn-otra-cuenta" onclick="alert('Redirigiendo a Facebook...')">Ingresar con otra cuenta</button>
      <div class="modal-footer-links" style="justify-content:center;gap:16px">
        <a href="#" style="color:#1877f2;font-family:-apple-system,Arial,sans-serif">Privacidad</a>
        <a href="#" style="color:#1877f2;font-family:-apple-system,Arial,sans-serif">Términos</a>
        <a href="#" style="color:#1877f2;font-family:-apple-system,Arial,sans-serif">Cookies</a>
      </div>
    </div>
  </div>
</div>

<!-- ══ APPLE MODAL ══ -->
<div class="modal-overlay" id="modalApple" role="dialog" aria-modal="true">
  <div class="modal-box modal-apple">
    <div class="modal-header" style="border-bottom:none">
      <svg height="28" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill="#f5f5f7"><path d="M12.152 6.896c-.948 0-2.415-1.078-3.96-1.04-2.04.027-3.91 1.183-4.961 3.014-2.117 3.675-.546 9.103 1.519 12.09 1.013 1.454 2.208 3.09 3.792 3.039 1.52-.065 2.09-.987 3.935-.987 1.831 0 2.35.987 3.96.948 1.637-.026 2.676-1.48 3.676-2.948 1.156-1.688 1.636-3.325 1.662-3.415-.039-.013-3.182-1.221-3.22-4.857-.026-3.04 2.48-4.494 2.597-4.559-1.429-2.09-3.623-2.324-4.39-2.376-2-.156-3.675 1.09-4.61 1.09zM15.53 3.83c.843-1.012 1.4-2.427 1.245-3.83-1.207.052-2.662.805-3.532 1.818-.78.896-1.454 2.338-1.273 3.714 1.338.104 2.715-.688 3.559-1.701"/></svg>
      <button class="modal-close" data-close-modal="apple" aria-label="Cerrar" style="color:#86868b">✕</button>
    </div>
    <div class="modal-body">
      <p class="modal-title">Iniciar sesión con Apple ID</p>
      <p class="modal-subtitle">Usá tu Apple ID para acceder a PILCHA IA</p>
      <div class="account-list">
        <div class="account-item" onclick="alert('Accediendo con Apple...')">
          <div class="account-avatar" style="background:#3a3a3c;color:#f5f5f7">🍎</div>
          <div class="account-info">
            <p class="account-name">Apple ID</p>
            <p class="account-email">u•••@icloud.com</p>
          </div>
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#86868b" stroke-width="2" style="margin-left:auto"><path d="M9 18l6-6-6-6"/></svg>
        </div>
      </div>
      <button class="btn-otra-cuenta" onclick="alert('Redirigiendo a Apple...')">Continuar con Apple</button>
      <div class="modal-footer-links" style="justify-content:center;gap:16px">
        <a href="#" style="color:#2997ff;font-family:-apple-system,Arial,sans-serif;font-size:0.75rem">Privacidad</a>
        <a href="#" style="color:#2997ff;font-family:-apple-system,Arial,sans-serif;font-size:0.75rem">Términos</a>
      </div>
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
  google:   document.getElementById('modalGoogle'),
  facebook: document.getElementById('modalFacebook'),
  apple:    document.getElementById('modalApple')
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

document.querySelectorAll('.btn-social').forEach(btn => {
  btn.addEventListener('click', () => openModal(btn.dataset.provider));
});

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
</script>

</body>
</html>
