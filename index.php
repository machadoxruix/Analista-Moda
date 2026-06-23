<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Si no hay sesión activa, redirigir al login
if (!isset($_SESSION['nombre_usuario'])) {
    header('Location: portada0.php');
    exit;
}

unset($_SESSION['outfit']);

$nombre = $_SESSION['nombre_usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PILCHA IA — Armemos tu outfit</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anton&family=Instrument+Serif:ital@0;1&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
	<link href="estilos.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css">
	

</head>
<body>


<nav class="nav">
    <a href="portada0.php" class="nav-brand">PILCHA<span> IA</span></a>
    <span class="nav-tagline">Configurador de estilo</span>
</nav>


<div class="wizard-shell">

    <!-- PROGRESS -->
    <div class="progress-wrap">
        <div class="progress-meta">
            <span class="progress-label" id="progressLabel">Paso 1 de 9</span>
            <span class="progress-count" id="progressStepName">Género</span>
        </div>
        <div class="progress-track">
            <div class="progress-fill" id="progressFill" style="width: 11.1%"></div>
        </div>
        <div class="step-dots" id="stepDots"></div>
    </div>

    <!-- CARD -->
    <div class="step-card">

        <form id="outfitForm" action="resultado.php" method="POST">
            <input type="hidden" name="nombre"         value="Invitado">
            <input type="hidden" name="genero"         id="f_genero">
            <input type="hidden" name="altura"         id="f_altura">
            <input type="hidden" name="gustos"         id="f_gustos">
            <input type="hidden" name="tamaño"         id="f_tamano">
            <input type="hidden" name="talle"          id="f_talle">
            <input type="hidden" name="paleta"         id="f_paleta">
            <input type="hidden" name="pelo"           id="f_pelo">
            <input type="hidden" name="color_remera"   id="f_color_remera"   value="#800020">
            <input type="hidden" name="color_pantalon" id="f_color_pantalon" value="#333333">

            <!-- ── STEP 1: GÉNERO ── -->
            <div class="step active" data-step="1" data-required="genero">
                <span class="step-num">01</span>
                <h2 class="step-heading">¿Cuál es tu género?</h2>
                <p class="step-sub">Esto define la base de tu recomendación</p>
                <div class="option-grid">
                    <button type="button" class="opt-btn" data-field="genero" data-value="masculino">
                        <span class="opt-icon">♂</span>Masculino
                    </button>
                    <button type="button" class="opt-btn" data-field="genero" data-value="femenino">
                        <span class="opt-icon">♀</span>Femenino
                    </button>
                    <button type="button" class="opt-btn" data-field="genero" data-value="unisex">
                        <span class="opt-icon">✦</span>Unisex
                    </button>
                </div>
                <p class="validation-hint" id="hint_1">↑ Seleccioná una opción para continuar</p>
            </div>

            <!-- ── STEP 2: ALTURA ── -->
            <div class="step" data-step="2" data-required="altura">
                <span class="step-num">02</span>
                <h2 class="step-heading">¿Cuánto medís?</h2>
                <p class="step-sub">El corte ideal depende de tu altura</p>
                <div class="option-grid option-grid-tall">
                    <button type="button" class="opt-btn" data-field="altura" data-value="altura1">
                        <svg class="fit-icon" viewBox="0 0 40 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="13" y="2" width="14" height="14" rx="7" fill="currentColor" opacity="0.7"/>
                            <rect x="10" y="18" width="20" height="22" rx="3" fill="currentColor" opacity="0.5"/>
                            <rect x="10" y="42" width="8" height="8" rx="2" fill="currentColor" opacity="0.4"/>
                            <rect x="22" y="42" width="8" height="8" rx="2" fill="currentColor" opacity="0.4"/>
                            <line x1="20" y1="2" x2="20" y2="50" stroke="currentColor" stroke-width="0.5" stroke-dasharray="2 2" opacity="0.2"/>
                        </svg>
                        Menos de 1,60m
                    </button>
                    <button type="button" class="opt-btn" data-field="altura" data-value="altura2">
                        <svg class="fit-icon" viewBox="0 0 40 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="13" y="1" width="14" height="14" rx="7" fill="currentColor" opacity="0.7"/>
                            <rect x="10" y="17" width="20" height="24" rx="3" fill="currentColor" opacity="0.5"/>
                            <rect x="10" y="43" width="8" height="8" rx="2" fill="currentColor" opacity="0.4"/>
                            <rect x="22" y="43" width="8" height="8" rx="2" fill="currentColor" opacity="0.4"/>
                        </svg>
                        1,60m — 1,75m
                    </button>
                    <button type="button" class="opt-btn" data-field="altura" data-value="altura3">
                        <svg class="fit-icon" viewBox="0 0 40 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="13" y="0" width="14" height="14" rx="7" fill="currentColor" opacity="0.7"/>
                            <rect x="10" y="16" width="20" height="26" rx="3" fill="currentColor" opacity="0.5"/>
                            <rect x="10" y="44" width="8" height="8" rx="2" fill="currentColor" opacity="0.4"/>
                            <rect x="22" y="44" width="8" height="8" rx="2" fill="currentColor" opacity="0.4"/>
                        </svg>
                        Más de 1,75m
                    </button>
                </div>
                <p class="validation-hint" id="hint_2">↑ Seleccioná una opción para continuar</p>
                <div class="step-nav">
                    <button type="button" class="btn-back" onclick="goBack()">← Atrás</button>
                </div>
            </div>

            <!-- ── STEP 3: ESTILO (2x2 grid) ── -->
            <div class="step" data-step="3" data-required="gustos">
                <span class="step-num">03</span>
                <h2 class="step-heading">Tu estilo</h2>
                <p class="step-sub">¿Cómo te gusta mostrarte al mundo?</p>
                <div class="option-grid-2x2">
                    <button type="button" class="opt-btn" data-field="gustos" data-value="formal" style="padding:22px 12px;">
                        <span class="opt-icon">◈</span>Formal
                    </button>
                    <button type="button" class="opt-btn" data-field="gustos" data-value="casual" style="padding:22px 12px;">
                        <span class="opt-icon">◇</span>Casual
                    </button>
                    <button type="button" class="opt-btn" data-field="gustos" data-value="deportivo" style="padding:22px 12px;">
                        <span class="opt-icon">◉</span>Deportivo
                    </button>
                    <button type="button" class="opt-btn" data-field="gustos" data-value="elegante" style="padding:22px 12px;">
                        <span class="opt-icon">◆</span>Elegante
                    </button>
                </div>
                <p class="validation-hint" id="hint_3">↑ Seleccioná un estilo para continuar</p>
                <div class="step-nav">
                    <button type="button" class="btn-back" onclick="goBack()">← Atrás</button>
                </div>
            </div>

            <!-- ── STEP 4: CORTE ── -->
            <div class="step" data-step="4" data-required="tamano">
                <span class="step-num">04</span>
                <h2 class="step-heading">Corte de ropa</h2>
                <p class="step-sub">¿Cómo preferís que te quede?</p>
                <div class="option-grid">

                    <!-- OVERSIZE: forma trapezoidal muy ancha — comunica amplitud y holgura -->
                    <button type="button" class="opt-btn" data-field="tamano" data-value="holgada">
                        <svg class="fit-icon" viewBox="0 0 40 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Trapecio invertido muy ancho: base superior ancha, se estrecha levemente abajo -->
                            <path d="M3 8 L37 8 L31 40 L9 40 Z" fill="currentColor" opacity="0.55"/>
                            <!-- Líneas horizontales internas que refuerzan la amplitud -->
                            <line x1="5"  y1="16" x2="35" y2="16" stroke="currentColor" stroke-width="0.8" opacity="0.3"/>
                            <line x1="7"  y1="24" x2="33" y2="24" stroke="currentColor" stroke-width="0.8" opacity="0.3"/>
                            <line x1="9"  y1="32" x2="31" y2="32" stroke="currentColor" stroke-width="0.8" opacity="0.3"/>
                            <!-- Flechas dobles horizontales en la parte superior — indican máxima amplitud -->
                            <line x1="3"  y1="4"  x2="37" y2="4"  stroke="currentColor" stroke-width="1.2" opacity="0.5"/>
                            <line x1="3"  y1="2"  x2="3"  y2="6"  stroke="currentColor" stroke-width="1.2" opacity="0.5"/>
                            <line x1="37" y1="2"  x2="37" y2="6"  stroke="currentColor" stroke-width="1.2" opacity="0.5"/>
                        </svg>
                        Oversize
                    </button>

                    <!-- SLIM: forma de rombo / diamante muy estilizado — comunica ajuste y estrechez -->
                    <button type="button" class="opt-btn" data-field="tamano" data-value="ajustada">
                        <svg class="fit-icon" viewBox="0 0 40 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Rectángulo muy angosto, lados paralelos y contenidos -->
                            <rect x="16" y="6" width="8" height="36" rx="1" fill="currentColor" opacity="0.55"/>
                            <!-- Líneas horizontales internas cortas — refuerzan el angosto -->
                            <line x1="16" y1="16" x2="24" y2="16" stroke="currentColor" stroke-width="0.8" opacity="0.3"/>
                            <line x1="16" y1="24" x2="24" y2="24" stroke="currentColor" stroke-width="0.8" opacity="0.3"/>
                            <line x1="16" y1="32" x2="24" y2="32" stroke="currentColor" stroke-width="0.8" opacity="0.3"/>
                            <!-- Flechas dobles horizontales cortas — indican mínima amplitud -->
                            <line x1="16" y1="3"  x2="24" y2="3"  stroke="currentColor" stroke-width="1.2" opacity="0.5"/>
                            <line x1="16" y1="1"  x2="16" y2="5"  stroke="currentColor" stroke-width="1.2" opacity="0.5"/>
                            <line x1="24" y1="1"  x2="24" y2="5"  stroke="currentColor" stroke-width="1.2" opacity="0.5"/>
                            <!-- Líneas punteadas externas que muestran el espacio "contenido" -->
                            <line x1="11" y1="8"  x2="11" y2="42" stroke="currentColor" stroke-width="0.6" opacity="0.2" stroke-dasharray="2 3"/>
                            <line x1="29" y1="8"  x2="29" y2="42" stroke="currentColor" stroke-width="0.6" opacity="0.2" stroke-dasharray="2 3"/>
                        </svg>
                        Slim
                    </button>

                    <!-- STRAIGHT: rectángulo perfecto y uniforme — comunica equilibrio y verticalidad recta -->
                    <button type="button" class="opt-btn" data-field="tamano" data-value="recto">
                        <svg class="fit-icon" viewBox="0 0 40 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Rectángulo simétrico y equilibrado — ni ancho ni angosto -->
                            <rect x="11" y="6" width="18" height="36" rx="1" fill="currentColor" opacity="0.55"/>
                            <!-- Líneas horizontales internas uniformes -->
                            <line x1="11" y1="16" x2="29" y2="16" stroke="currentColor" stroke-width="0.8" opacity="0.3"/>
                            <line x1="11" y1="24" x2="29" y2="24" stroke="currentColor" stroke-width="0.8" opacity="0.3"/>
                            <line x1="11" y1="32" x2="29" y2="32" stroke="currentColor" stroke-width="0.8" opacity="0.3"/>
                            <!-- Flechas dobles horizontales medianas — indican amplitud media -->
                            <line x1="11" y1="3"  x2="29" y2="3"  stroke="currentColor" stroke-width="1.2" opacity="0.5"/>
                            <line x1="11" y1="1"  x2="11" y2="5"  stroke="currentColor" stroke-width="1.2" opacity="0.5"/>
                            <line x1="29" y1="1"  x2="29" y2="5"  stroke="currentColor" stroke-width="1.2" opacity="0.5"/>
                        </svg>
                        Straight
                    </button>

                </div>
                <p class="validation-hint" id="hint_4">↑ Seleccioná un corte para continuar</p>
                <div class="step-nav">
                    <button type="button" class="btn-back" onclick="goBack()">← Atrás</button>
                </div>
            </div>

            <!-- ── STEP 5: TALLE (opcional) ── -->
            <div class="step" data-step="5" data-required="">
                <span class="step-num">05</span>
                <h2 class="step-heading">Talle</h2>
                <p class="step-sub">Refiná tu recomendación</p>
                <span class="optional-badge">Opcional</span>
                <div class="talle-row">
                    <button type="button" class="opt-btn" data-field="talle" data-value="XS">XS</button>
                    <button type="button" class="opt-btn" data-field="talle" data-value="S">S</button>
                    <button type="button" class="opt-btn" data-field="talle" data-value="M">M</button>
                    <button type="button" class="opt-btn" data-field="talle" data-value="L">L</button>
                    <button type="button" class="opt-btn" data-field="talle" data-value="XL">XL</button>
                    <button type="button" class="opt-btn" data-field="talle" data-value="XXL">XXL</button>
                </div>
                <div class="step-nav">
                    <button type="button" class="btn-back" onclick="goBack()">← Atrás</button>
                    <button type="button" class="btn-next" onclick="goNext()">Continuar →</button>
                </div>
            </div>

            <!-- ── STEP 6: TONO DE PIEL ── -->
            <div class="step" data-step="6" data-required="paleta">
                <span class="step-num">06</span>
                <h2 class="step-heading">Tono de piel</h2>
                <p class="step-sub">Acercá el cursor a los extremos para deslizar, luego seleccioná tu tono</p>

                <div class="swatch-selector">
                    <!-- Swatch track -->
                    <div class="swatch-track-outer">
                        <button type="button" class="swatch-arrow left" id="skinPrev">❮</button>
                        <div class="swatch-track" id="skinTrack">
                            <?php
                            $skinTones = [
                                ['id'=>'piel1','label'=>'Muy claro','color'=>'#FDDBB4'],
                                ['id'=>'piel2','label'=>'Claro',    'color'=>'#F0C28A'],
                                ['id'=>'piel3','label'=>'Medio',    'color'=>'#D4956A'],
                                ['id'=>'piel4','label'=>'Oliva',    'color'=>'#B87A4F'],
                                ['id'=>'piel5','label'=>'Oscuro',   'color'=>'#8B5A2B'],
                                ['id'=>'piel6','label'=>'Muy oscuro','color'=>'#4A2810'],
                            ];
                            foreach($skinTones as $t):
                            ?>
                            <div class="swatch-item" data-swatch-field="paleta" data-swatch-value="<?php echo $t['id']; ?>" data-swatch-label="<?php echo $t['label']; ?>" data-swatch-color="<?php echo $t['color']; ?>">
                                <div class="swatch-circle">
                                    <img src="fotos/piel/<?php echo $t['id']; ?>.jpg"
                                         onerror="this.style.display='none';this.parentElement.style.background='<?php echo $t['color']; ?>'"
                                         alt="<?php echo $t['label']; ?>">
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="swatch-arrow right" id="skinNext">❯</button>
                    </div>
                </div>

                <p class="validation-hint" id="hint_6">↑ Seleccioná un tono para continuar</p>
                <div class="step-nav">
                    <button type="button" class="btn-back" onclick="goBack()">← Atrás</button>
                    <button type="button" class="btn-next" id="btnNext6" onclick="goNext()" disabled>Continuar →</button>
                </div>
            </div>

            <!-- ── STEP 7: COLOR DE PELO ── -->
            <div class="step" data-step="7" data-required="pelo">
                <span class="step-num">07</span>
                <h2 class="step-heading">Color de pelo</h2>
                <p class="step-sub">Acercá el cursor a los extremos para deslizar, luego seleccioná tu color</p>

                <div class="swatch-selector">
                    <!-- Swatch track -->
                    <div class="swatch-track-outer">
                        <button type="button" class="swatch-arrow left" id="hairPrev">❮</button>
                        <div class="swatch-track" id="hairTrack">
                            <?php
                            $hairColors = [
                                ['id'=>'1','label'=>'Negro',     'color'=>'#1C1008'],
                                ['id'=>'2','label'=>'Castaño',   'color'=>'#4A2B0F'],
                                ['id'=>'3','label'=>'Marrón',    'color'=>'#7B4B2A'],
                                ['id'=>'4','label'=>'Rubio',     'color'=>'#C9A84C'],
                                ['id'=>'5','label'=>'Pelirrojo', 'color'=>'#A0522D'],
                                ['id'=>'6','label'=>'Gris/Cano', 'color'=>'#9A9080'],
                            ];
                            foreach($hairColors as $h):
                            ?>
                            <div class="swatch-item" data-swatch-field="pelo" data-swatch-value="<?php echo $h['id']; ?>" data-swatch-label="<?php echo $h['label']; ?>" data-swatch-color="<?php echo $h['color']; ?>">
                                <div class="swatch-circle">
                                    <img src="fotos/pelo/<?php echo $h['id']; ?>.jpg"
                                         onerror="this.style.display='none';this.parentElement.style.background='<?php echo $h['color']; ?>'"
                                         alt="<?php echo $h['label']; ?>">
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="swatch-arrow right" id="hairNext">❯</button>
                    </div>
                </div>

                <p class="validation-hint" id="hint_7">↑ Seleccioná un color para continuar</p>
                <div class="step-nav">
                    <button type="button" class="btn-back" onclick="goBack()">← Atrás</button>
                    <button type="button" class="btn-next" id="btnNext7" onclick="goNext()" disabled>Continuar →</button>
                </div>
            </div>

            <!-- ── STEP 8: COLORES DE ROPA ── -->
            <div class="step" data-step="8" data-required="">
                <span class="step-num">08</span>
                <h2 class="step-heading">Colores de tu ropa</h2>
                <p class="step-sub">Elegí los colores que querés combinar</p>
                <div class="color-cards">
                    <div class="color-card">
                        <span class="color-card-label">Superior</span>
                        <div class="color-preview-ring" id="ring_remera">
                            <div class="color-preview-fill" id="fill_remera" style="background:#800020;"></div>
                            <input type="color" id="pick_remera" value="#800020"
                                   oninput="updateColorCard('remera', this.value)">
                        </div>
                        <span class="color-hex-value" id="hex_remera">#800020</span>
                    </div>
                    <div class="color-card">
                        <span class="color-card-label">Inferior</span>
                        <div class="color-preview-ring" id="ring_pantalon">
                            <div class="color-preview-fill" id="fill_pantalon" style="background:#333333;"></div>
                            <input type="color" id="pick_pantalon" value="#333333"
                                   oninput="updateColorCard('pantalon', this.value)">
                        </div>
                        <span class="color-hex-value" id="hex_pantalon">#333333</span>
                    </div>
                </div>
                <div class="step-nav">
                    <button type="button" class="btn-back" onclick="goBack()">← Atrás</button>
                    <button type="button" class="btn-next" onclick="goNext()">Continuar →</button>
                </div>
            </div>

            <!-- ── STEP 9: CONFIRMAR ── -->
            <div class="step" data-step="9" data-required="">
                <span class="step-num">09</span>
                <h2 class="step-heading">¡Listo para generar!</h2>
                <p class="step-sub">Revisá tu selección y generá tu outfit personalizado</p>
                <div class="summary-grid" id="summaryBox"></div>
                <button type="submit" class="btn-generate">GENERAR MI OUTFIT →</button>
                <div class="step-nav" style="margin-top:14px; justify-content:flex-start;">
                    <button type="button" class="btn-back" onclick="goBack()">← Atrás</button>
                </div>
            </div>

        </form>
    </div><!-- end step-card -->

</div><!-- end wizard-shell -->

<script>
// ─────────────────────────────────────────────
//  WIZARD STATE
// ─────────────────────────────────────────────
const TOTAL = 9;
let current = 1;
const state = {
    genero:         '',
    altura:         '',
    gustos:         '',
    tamano:         '',
    talle:          '',
    paleta:         '',
    pelo:           '',
    color_remera:   '#800020',
    color_pantalon: '#333333',
};

const stepNames = [
    '', 'Género', 'Altura', 'Estilo', 'Corte',
    'Talle', 'Tono de piel', 'Color de pelo', 'Colores', 'Confirmar'
];

// Required fields per step (empty string = optional step)
const stepRequired = {
    1: 'genero',
    2: 'altura',
    3: 'gustos',
    4: 'tamano',
    5: '',       // talle: optional
    6: 'paleta',
    7: 'pelo',
    8: '',       // colors always have default values
    9: '',
};

// Build dots
const dotsContainer = document.getElementById('stepDots');
for(let i=1; i<=TOTAL; i++){
    const d = document.createElement('div');
    d.className = 'dot' + (i===1?' active':'');
    d.dataset.s = i;
    dotsContainer.appendChild(d);
}

function updateProgress() {
    const pct = (current / TOTAL) * 100;
    document.getElementById('progressFill').style.width = pct + '%';
    document.getElementById('progressLabel').textContent = `Paso ${current} de ${TOTAL}`;
    document.getElementById('progressStepName').textContent = stepNames[current];
    document.querySelectorAll('.dot').forEach(d => {
        const n = +d.dataset.s;
        d.className = 'dot' + (n < current ? ' done' : '') + (n === current ? ' active' : '');
    });
}

function setField(field, value) {
    state[field] = value;
    const el = document.getElementById('f_' + field);
    if(el) el.value = value;
}

// ─────────────────────────────────────────────
//  VALIDATION
// ─────────────────────────────────────────────
function isStepValid(step) {
    const required = stepRequired[step];
    if (!required) return true;
    return !!state[required];
}

function showHint(step) {
    const hint = document.getElementById('hint_' + step);
    if (hint) {
        hint.classList.add('visible');
        // Shake the hint
        hint.style.animation = 'none';
        hint.offsetHeight;
        hint.style.animation = '';
        setTimeout(() => hint.classList.remove('visible'), 3000);
    }
}

// ─────────────────────────────────────────────
//  NAVIGATION
// ─────────────────────────────────────────────
function showStep(n) {
    const steps = document.querySelectorAll('.step');
    steps.forEach(s => { s.classList.remove('active'); s.style.display = 'none'; });
    const target = document.querySelector(`.step[data-step="${n}"]`);
    if(target){
        target.style.display = 'flex';
        target.offsetHeight; // reflow
        target.classList.add('active');
    }
    current = n;
    updateProgress();
    if(n === 9) buildSummary();
}

function goNext() {
    if (!isStepValid(current)) {
        showHint(current);
        return;
    }
    if(current < TOTAL) showStep(current + 1);
}

function goBack() {
    if(current > 1) showStep(current - 1);
}

// ─────────────────────────────────────────────
//  OPT BUTTONS — click handler
// ─────────────────────────────────────────────
document.querySelectorAll('.opt-btn[data-field]').forEach(btn => {
    btn.addEventListener('click', function() {
        const field = this.dataset.field;
        const value = this.dataset.value;

        document.querySelectorAll(`.opt-btn[data-field="${field}"]`).forEach(b => b.classList.remove('selected'));
        this.classList.add('selected');
        setField(field, value);

        // Hide any visible hint for the current step
        const hint = document.getElementById('hint_' + current);
        if (hint) hint.classList.remove('visible');

        // Auto-advance for required steps (not talle)
        if(field !== 'talle') {
            setTimeout(() => goNext(), 220);
        }
    });
});

// ─────────────────────────────────────────────
//  SWATCH SELECTORS — carrusel circular con
//  scroll automático al acercar cursor a extremos
// ─────────────────────────────────────────────
function initSwatchSelector(trackId, prevId, nextId, previewDotId, previewNameId, nextBtnId) {
    const track       = document.getElementById(trackId);
    const previewDot  = document.getElementById(previewDotId);
    const previewName = document.getElementById(previewNameId);
    const nextStepBtn = nextBtnId ? document.getElementById(nextBtnId) : null;
    // prevId / nextId se mantienen en la firma para compatibilidad pero ya no se usan
    const prevBtn = document.getElementById(prevId);
    const nextBtn = document.getElementById(nextId);
    if (prevBtn) { prevBtn.style.display = 'none'; }
    if (nextBtn) { nextBtn.style.display = 'none'; }

    if (!track) return;

    // ── 1. Leer items originales y triplicar para loop ────
    const origItems = Array.from(track.querySelectorAll('.swatch-item'));
    const N = origItems.length;
    if (N === 0) return;

    const REPEATS = 3;
    // Limpiar el track y reconstruir con REPEATS copias
    track.innerHTML = '';
    for (let r = 0; r < REPEATS; r++) {
        origItems.forEach(orig => {
            const clone = orig.cloneNode(true);
            track.appendChild(clone);
        });
    }

    const ITEM_W = 82; // 72px swatch + 10px gap
    // Empezar en el bloque del medio (índice N)
    let offset = N * ITEM_W;
    let autoSpeed = 0;
    let isDragging = false, startX = 0, startOffset = 0;

    // El outer es .swatch-track-outer — buscar el padre del track
    const outer = track.closest('.swatch-track-outer');
    if (!outer) return;

    // ── 2. Aplicar transform ──────────────────────────────
    function applyOffset() {
        track.style.transition = 'none';
        track.style.transform  = `translateX(-${offset}px)`;
    }

    // ── 3. Normalizar para mantener el loop ───────────────
    function normalizeOffset() {
        const totalW = N * ITEM_W;
        if (offset >= totalW * (REPEATS - 1)) { offset -= totalW; applyOffset(); }
        if (offset < totalW)                  { offset += totalW; applyOffset(); }
    }

    applyOffset();

    // ── 4. RAF para scroll automático ────────────────────
    const MAX_SPEED   = 2.5;
    const IDLE_SPEED  = 0.38; // velocidad lenta de auto-scroll continuo
    let raf;
    let resumeTimer = null;

    // Arranca con el scroll suave automático
    autoSpeed = IDLE_SPEED;

    function tick() {
        raf = requestAnimationFrame(tick);
        if (isDragging) return;
        offset += autoSpeed;
        normalizeOffset();
        applyOffset();
    }
    raf = requestAnimationFrame(tick);

    // Retoma el auto-scroll suave tras 1.2s de inactividad
    function scheduleResume() {
        if (resumeTimer) clearTimeout(resumeTimer);
        resumeTimer = setTimeout(() => {
            if (!isDragging) autoSpeed = IDLE_SPEED;
        }, 1200);
    }

    // ── 5. Zonas de hover: crear dos overlays invisibles ──
    // Lado izquierdo
    const edgeL = document.createElement('div');
    edgeL.style.cssText = 'position:absolute;top:0;bottom:0;left:0;width:60px;z-index:10;cursor:w-resize;';
    // Lado derecho
    const edgeR = document.createElement('div');
    edgeR.style.cssText = 'position:absolute;top:0;bottom:0;right:0;width:60px;z-index:10;cursor:e-resize;';

    outer.appendChild(edgeL);
    outer.appendChild(edgeR);

    edgeL.addEventListener('mouseenter', () => { if (!isDragging) autoSpeed = -MAX_SPEED; });
    edgeL.addEventListener('mouseleave', () => { scheduleResume(); });
    edgeR.addEventListener('mouseenter', () => { if (!isDragging) autoSpeed = +MAX_SPEED; });
    edgeR.addEventListener('mouseleave', () => { scheduleResume(); });
    outer.addEventListener('mouseleave', () => { scheduleResume(); });

    // ── 6. Drag con mouse ────────────────────────────────
    outer.addEventListener('mousedown', (e) => {
        isDragging = true;
        startX = e.clientX;
        startOffset = offset;
        autoSpeed = 0;
        if (resumeTimer) clearTimeout(resumeTimer);
        outer.style.cursor = 'grabbing';
        e.preventDefault();
    });
    document.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        offset = startOffset + (startX - e.clientX);
        normalizeOffset();
        applyOffset();
    });
    document.addEventListener('mouseup', () => {
        if (!isDragging) return;
        isDragging = false;
        outer.style.cursor = '';
        scheduleResume();
    });

    // ── 7. Touch ──────────────────────────────────────────
    outer.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
        startOffset = offset;
        autoSpeed = 0;
        if (resumeTimer) clearTimeout(resumeTimer);
    }, { passive: true });
    outer.addEventListener('touchmove', (e) => {
        offset = startOffset + (startX - e.touches[0].clientX);
        normalizeOffset();
        applyOffset();
    }, { passive: true });
    outer.addEventListener('touchend', () => {
        scheduleResume();
    }, { passive: true });

    // ── 8. Click para seleccionar ─────────────────────────
    track.addEventListener('click', (e) => {
        // Ignorar si fue un drag
        if (isDragging) return;

        const item = e.target.closest('.swatch-item');
        if (!item) return;

        const field = item.dataset.swatchField;
        const value = item.dataset.swatchValue;
        const label = item.dataset.swatchLabel;
        const color = item.dataset.swatchColor;
        if (!field || !value) return;

        // Marcar activo en todas las copias del mismo value
        track.querySelectorAll('.swatch-item').forEach(s => {
            s.classList.toggle('active', s.dataset.swatchValue === value);
        });

        setField(field, value);

        // Actualizar preview
        const imgEl = item.querySelector('img');
        if (previewDot) {
            const hasImg = imgEl && imgEl.complete && imgEl.naturalHeight > 0;
            previewDot.innerHTML = hasImg
                ? `<img src="${imgEl.src}" style="width:100%;height:100%;object-fit:cover;border-radius:50%;">`
                : `<div style="width:100%;height:100%;border-radius:50%;background:${color};"></div>`;
        }
        if (previewName) previewName.textContent = label;
        if (nextStepBtn) nextStepBtn.disabled = false;

        const hint = document.getElementById('hint_' + current);
        if (hint) hint.classList.remove('visible');

        // Retomar auto-scroll suave tras la selección
        scheduleResume();
    });
}

initSwatchSelector('skinTrack', 'skinPrev', 'skinNext', null, null, 'btnNext6');
initSwatchSelector('hairTrack', 'hairPrev', 'hairNext', null, null, 'btnNext7');

// ─────────────────────────────────────────────
//  COLOR PICKER CARDS
// ─────────────────────────────────────────────
function updateColorCard(which, value) {
    setField('color_' + which, value);
    document.getElementById('fill_' + which).style.background = value;
    document.getElementById('hex_' + which).textContent = value;
}

// ─────────────────────────────────────────────
//  SUMMARY
// ─────────────────────────────────────────────
function buildSummary() {
    const labels = {
        genero:  'Género',
        altura:  'Altura',
        gustos:  'Estilo',
        tamano:  'Corte',
        talle:   'Talle',
        paleta:  'Tono piel',
        pelo:    'Pelo',
        color_remera:   'Color superior',
        color_pantalon: 'Color inferior',
    };
    const box = document.getElementById('summaryBox');
    box.innerHTML = '';
    for(const [key, label] of Object.entries(labels)){
        const val = state[key] || (key === 'talle' ? '—' : '');
        if(!val) continue;
        const isColor = key.startsWith('color_');
        box.innerHTML += `
            <div style="display:flex;flex-direction:column;gap:3px;">
                <span style="font-family:var(--font-mono);font-size:0.55rem;font-weight:700;letter-spacing:0.18em;text-transform:uppercase;color:var(--gray-mid);">${label}</span>
                <span style="font-family:var(--font-mono);font-size:0.78rem;color:var(--black);display:flex;align-items:center;gap:7px;">
                    ${isColor ? `<span style="display:inline-block;width:14px;height:14px;background:${val};border-radius:50%;border:1px solid rgba(0,0,0,0.15);flex-shrink:0;"></span>` : ''}
                    ${val}
                </span>
            </div>`;
    }
}

// ─────────────────────────────────────────────
//  INIT
// ─────────────────────────────────────────────
updateProgress();
</script>

</body>
</html>
