<?php
// ============================================================
//  CONFIGURACIÓN DE SUPABASE
// ============================================================
define('SUPABASE_URL', 'https://ktguvewasxniobwmndfb.supabase.co');
define('SUPABASE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Imt0Z3V2ZXdhc3huaW9id21uZGZiIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzY5NjMwMjgsImV4cCI6MjA5MjUzOTAyOH0.UFMbMUXdKZt8IqRAX3Iv6MGREeVU-yKn5XdscVGTt54');
define('TABLE_PRENDAS',  'prendas');
define('TABLE_USUARIOS', 'usuarios');

define('DEBUG_MODE', false);

// ============================================================
//  CONFIGURACIÓN DE GROQ (cargada desde .env)
// ============================================================
$_env = file_exists(__DIR__ . '/.env') ? parse_ini_file(__DIR__ . '/.env') : [];
define('GROQ_KEY',   $_env['GROQ_KEY']   ?? '');
define('GROQ_MODEL', 'llama-3.1-8b-instant');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ============================================================
//  FUNCIONES DE COMUNICACIÓN CON SUPABASE
// ============================================================
function supabaseRequest($table, $query = '') {
    $url = SUPABASE_URL . '/rest/v1/' . $table . $query;

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            'apikey: '               . SUPABASE_KEY,
            'Authorization: Bearer ' . SUPABASE_KEY,
            'Content-Type: application/json',
            'Accept: application/json',
        ],
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    $response  = curl_exec($ch);
    $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) { debugLog("cURL error en GET $table: $curlError"); return null; }
    if ($httpCode < 200 || $httpCode >= 300) { debugLog("HTTP $httpCode en GET $table$query — Respuesta: $response"); return null; }

    $decoded = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) { debugLog("JSON inválido en GET $table: " . json_last_error_msg()); return null; }

    return $decoded;
}

function supabaseInsert($table, $data) {
    $url = SUPABASE_URL . '/rest/v1/' . $table;

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($data),
        CURLOPT_HTTPHEADER     => [
            'apikey: '               . SUPABASE_KEY,
            'Authorization: Bearer ' . SUPABASE_KEY,
            'Content-Type: application/json',
            'Accept: application/json',
            'Prefer: return=minimal',
        ],
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    $response  = curl_exec($ch);
    $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError) { debugLog("cURL error en INSERT $table: $curlError"); return false; }
    if ($httpCode < 200 || $httpCode >= 300) { debugLog("HTTP $httpCode en INSERT $table — Respuesta: $response"); return false; }

    return true;
}

// ============================================================
//  GROQ — COMENTARIO DE ESTILISTA (texto libre, no JSON)
// ============================================================
function generarComentarioEstilista($outfit, $estilo, $genero, $historial_texto = '') {
    if (!GROQ_KEY) return null;

    // Armar descripción del outfit
    $descripcion = '';
    foreach ($outfit as $prenda) {
        $tipo   = $prenda['tipo']   ?? '';
        $nombre = $prenda['nombre'] ?? '';
        if ($tipo === 'remera_interior') continue;
        $descripcion .= "- $nombre ($tipo)\n";
    }

    if (empty(trim($descripcion))) return null;

    $prompt = "Sos un estilista de moda argentino. Describí en UNA oración corta y directa el outfit recomendado, "
            . "destacando qué lo hace especial. Rango de 55 a 65 palabras. Sin rodeos.\n\n"
            . "Estilo del outfit: $estilo\n"
            . "Género: $genero\n"
            . "Prendas:\n$descripcion";

    if (!empty($historial_texto)) {
        $prompt .= "\nEl usuario tiene este historial de outfits que le gustaron:\n$historial_texto"
                 . "Si corresponde, mencioná brevemente que el outfit va en línea con sus gustos anteriores.";
    }

    $ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode([
            'model'       => GROQ_MODEL,
            'max_tokens'  => 100,
            'temperature' => 0.7,
            'messages'    => [
                [
                    'role'    => 'system',
                    'content' => 'Sos un estilista de moda. Respondés solo con el comentario, sin títulos, sin listas, sin comillas.'
                ],
                [
                    'role'    => 'user',
                    'content' => $prompt
                ]
            ]
        ]),
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . GROQ_KEY,
            'Content-Type: application/json',
        ],
        CURLOPT_TIMEOUT => 15,
    ]);

    $response  = curl_exec($ch);
    $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);

    if ($curlError || $httpCode !== 200) {
        debugLog("Groq error comentario: $curlError | HTTP: $httpCode");
        return null;
    }

    $data    = json_decode($response, true);
    $comentario = trim($data['choices'][0]['message']['content'] ?? '');

    return !empty($comentario) ? $comentario : null;
}

// ============================================================
//  AUTH
// ============================================================
function verificarLogin($usuario, $contrasena) {
    $query = '?usuario=eq.'    . urlencode($usuario)
           . '&contrasena=eq.' . urlencode($contrasena)
           . '&select=usuario&limit=1';

    $resultado = supabaseRequest('cuentas', $query);
    return ($resultado && count($resultado) > 0) ? $resultado[0] : null;
}

function crearCuenta($usuario, $contrasena) {
    $existe = supabaseRequest('cuentas', '?usuario=eq.' . urlencode($usuario) . '&select=id&limit=1');
    if ($existe && count($existe) > 0) return 'existe';

    $ok = supabaseInsert('cuentas', [
        'usuario'    => $usuario,
        'contrasena' => $contrasena,
    ]);
    return $ok ? 'ok' : 'error';
}

// ============================================================
//  FUNCIÓN PRINCIPAL: GENERAR OUTFIT
// ============================================================
function generarOutfit() {
    // Si viene del botón guardar, mostrar el outfit de sesión directamente
    if (($_POST['accion'] ?? '') === 'guardar_outfit') {
        if (isset($_SESSION['outfit']) && !empty($_SESSION['outfit'])) {
            mostrarOutfit($_SESSION['outfit']);
        }
        return;
    }

    // Recarga sin genero: mostrar outfit de sesión si existe
    if ((!isset($_POST['genero']) || $_POST['genero'] === '') && isset($_SESSION['outfit']) && !empty($_SESSION['outfit'])) {
        mostrarOutfit($_SESSION['outfit']);
        return;
    }

    $genero         = sanitizar($_POST['genero']        ?? '');
    $estilo         = sanitizar($_POST['gustos']        ?? '');
    $tamano         = sanitizar($_POST['tamaño']        ?? '');
    $talle          = sanitizar($_POST['talle']         ?? '');
    $color_remera   = $_POST['color_remera']            ?? '#ffffff';
    $color_pantalon = $_POST['color_pantalon']          ?? '#333333';

    $clave_actual = md5($genero . $estilo . $tamano . $talle . $color_remera . $color_pantalon);
    if (isset($_SESSION['outfit']) && $_SESSION['outfit_clave'] === $clave_actual) {
        mostrarOutfit($_SESSION['outfit']);
        return;
    }

    if (!$genero || !$estilo || !$tamano) {
        mostrarError("Faltan datos del formulario.");
        return;
    }

    $outfit = obtenerOutfitCompleto($genero, $estilo, $tamano, $color_remera, $color_pantalon, $talle);

    if ($outfit && count($outfit) > 0) {
        $_SESSION['outfit']        = $outfit;
        $_SESSION['outfit_clave']  = $clave_actual;
        $_SESSION['outfit_estilo'] = $estilo;
        $_SESSION['outfit_genero'] = $genero;
        mostrarOutfit($outfit);
        $paleta = sanitizar($_POST['paleta'] ?? '');
        $pelo   = sanitizar($_POST['pelo']   ?? '');
        $altura = sanitizar($_POST['altura']  ?? '');
        guardarPreferenciasUsuario($_POST['nombre'] ?? 'Usuario', $genero, $estilo, $paleta, $pelo, $altura);
    } else {
        mostrarError("No encontramos prendas para esa combinación.");
    }
}

// ============================================================
//  ARMAR UN OUTFIT COMPLETO (algoritmo matemático de color)
// ============================================================
function obtenerOutfitCompleto($genero, $estilo, $tamano, $color_remera = '#ffffff', $color_pantalon = '#333333', $talle = '') {

    // Lógica de vestido: solo femenino, no deportivo, 50% probabilidad
    $tiene_vestido = false;
    if ($genero === 'femenino' && $estilo !== 'deportivo') {
        $query_vestido = '?genero=in.(femenino,unisex)'
            . '&estilo=eq.' . urlencode($estilo)
            . '&tamano=eq.' . urlencode($tamano)
            . '&tipo=eq.vestido'
            . '&select=id,nombre,tipo,foto,hex&limit=20';
        $vestidos = supabaseRequest(TABLE_PRENDAS, $query_vestido);
        $vestidos = array_values(array_filter($vestidos ?? [], function($p) { return !empty($p['foto']); }));
        $tiene_vestido = count($vestidos) > 0 && rand(0, 1) === 1;
    }

    $tipos = $tiene_vestido ? ['vestido', 'zapatos'] : ['remera', 'pantalon', 'zapatos'];

    // Filtros por tipo — remera incluye chaqueta
    $tipo_filtros = [
        'remera'   => 'in.(remera,chaqueta)',
        'pantalon' => 'eq.pantalon',
        'zapatos'  => 'eq.zapatos',
        'vestido'  => 'eq.vestido',
    ];

    // Traer candidatas de Supabase con filtros progresivos
    $candidatas = [];
    foreach ($tipos as $tipo) {
        $filtro_tipo = $tipo_filtros[$tipo] ?? 'eq.' . urlencode($tipo);
        $intentos    = [];

        if ($talle !== '') {
            $intentos[] = '?genero=in.(' . urlencode($genero) . ',unisex)'
                . '&estilo=eq.' . urlencode($estilo)
                . '&tamano=eq.' . urlencode($tamano)
                . '&talle=eq.'  . urlencode($talle)
                . '&tipo='      . $filtro_tipo
                . '&select=id,nombre,tipo,foto,hex&limit=20';
        }

        $intentos[] = '?genero=in.(' . urlencode($genero) . ',unisex)'
            . '&estilo=eq.' . urlencode($estilo)
            . '&tamano=eq.' . urlencode($tamano)
            . '&tipo='      . $filtro_tipo
            . '&select=id,nombre,tipo,foto,hex&limit=20';

        $intentos[] = '?genero=in.(' . urlencode($genero) . ',unisex)'
            . '&estilo=eq.' . urlencode($estilo)
            . '&tipo='      . $filtro_tipo
            . '&select=id,nombre,tipo,foto,hex&limit=20';

        $intentos[] = '?genero=in.(' . urlencode($genero) . ',unisex)'
            . '&tipo='      . $filtro_tipo
            . '&select=id,nombre,tipo,foto,hex&limit=20';

        foreach ($intentos as $query) {
            $resultado = supabaseRequest(TABLE_PRENDAS, $query);
            $conFoto   = array_values(array_filter($resultado ?? [], function($p) { return !empty($p['foto']); }));
            if (count($conFoto) > 0) { $candidatas[$tipo] = $conFoto; break; }
        }
    }

    if (empty($candidatas)) return [];

    // Selección por distancia de color (matemáticamente exacto)
    $outfit = obtenerOutfitFallback($candidatas, $color_remera, $color_pantalon);

    if (empty($outfit)) return [];

    // Si hay chaqueta, agregar remera interior neutra según estilo
    foreach ($outfit as $prenda) {
        if (($prenda['tipo'] ?? '') === 'chaqueta') {
            if (in_array($estilo, ['elegante', 'formal'])) {
                $estilo_interior = $estilo;
            } elseif ($estilo === 'deportivo') {
                $estilo_interior = 'deportivo';
            } else {
                $estilo_interior = 'casual';
            }

            $color_neutro   = rand(0, 1) === 1 ? '#FFFFFF' : '#000000';
            $query_interior = '?genero=in.(' . urlencode($genero) . ',unisex)'
                . '&tipo=eq.remera'
                . '&estilo=eq.' . urlencode($estilo_interior)
                . '&select=id,nombre,tipo,foto,hex&limit=50';

            $remeras = supabaseRequest(TABLE_PRENDAS, $query_interior);
            $remeras = array_values(array_filter($remeras ?? [], function($p) { return !empty($p['foto']); }));

            // Fallback a casual si no hay del estilo pedido
            if (count($remeras) === 0) {
                $query_interior = '?genero=in.(' . urlencode($genero) . ',unisex)'
                    . '&tipo=eq.remera&estilo=eq.casual'
                    . '&select=id,nombre,tipo,foto,hex&limit=50';
                $remeras = supabaseRequest(TABLE_PRENDAS, $query_interior);
                $remeras = array_values(array_filter($remeras ?? [], function($p) { return !empty($p['foto']); }));
            }

            if (count($remeras) > 0) {
                $mejor = null; $min_dist = PHP_INT_MAX;
                foreach ($remeras as $r) {
                    $dist = distanciaColor($color_neutro, $r['hex'] ?? '#000000');
                    if ($dist < $min_dist) { $min_dist = $dist; $mejor = $r; }
                }
                if ($mejor) {
                    $mejor['tipo'] = 'remera_interior';
                    $outfit[] = $mejor;
                }
            }
            break;
        }
    }

    return $outfit;
}

// ============================================================
//  SELECCIÓN POR DISTANCIA DE COLOR
// ============================================================
function obtenerOutfitFallback($candidatas, $color_remera, $color_pantalon) {
    $colores = [
        'remera'   => $color_remera,
        'vestido'  => $color_remera,
        'chaqueta' => $color_remera,
        'pantalon' => $color_pantalon,
        'zapatos'  => null,
    ];
    $outfit = [];

    foreach ($candidatas as $tipo => $prendas) {
        $color_elegido   = $colores[$tipo] ?? null;
        $prendas_con_hex = array_values(array_filter($prendas, function($p) { return !empty($p['hex']); }));

        if ($color_elegido && count($prendas_con_hex) > 0) {
            $mejor = null; $min_dist = PHP_INT_MAX;
            foreach ($prendas_con_hex as $prenda) {
                $dist = distanciaColor($color_elegido, $prenda['hex']);
                if ($dist < $min_dist) { $min_dist = $dist; $mejor = $prenda; }
            }
            $outfit[] = $mejor;
        } else {
            $outfit[] = $prendas[array_rand($prendas)];
        }
    }

    return $outfit;
}

// ============================================================
//  MOSTRAR EL OUTFIT EN PANTALLA (collage editorial)
// ============================================================
function mostrarOutfit($outfit) {
    $roles = [
        'remera'          => 'slot-top',
        'chaqueta'        => 'slot-top',
        'vestido'         => 'slot-top',
        'remera_interior' => 'slot-interior',
        'pantalon'        => 'slot-bottom',
        'zapatos'         => 'slot-shoes',
    ];

    $prendas_por_tipo = [];
    foreach ($outfit as $prenda) {
        $tipo = $prenda['tipo'] ?? '';
        if (array_key_exists($tipo, $roles)) {
            $prendas_por_tipo[$tipo] = $prenda;
        }
    }

    if (empty($prendas_por_tipo)) {
        $keys = array_keys($roles);
        foreach (array_values($outfit) as $i => $prenda) {
            $tipo_key = $keys[$i] ?? 'remera';
            $prendas_por_tipo[$tipo_key] = $prenda;
        }
    }

    echo "<div class='collage-outer'>";
    echo "<div class='collage-deco'>
        <span class='deco-line deco-line-h'></span>
        <span class='deco-line deco-line-v'></span>
        <span class='deco-tag'>LOOK OF THE DAY</span>
        <span class='deco-season'>SS 2026</span>
    </div>";
    echo "<div class='outfit-collage'>";

    $orden = ['remera', 'chaqueta', 'vestido', 'remera_interior', 'pantalon', 'zapatos'];
    foreach ($orden as $tipo) {
        if (!isset($prendas_por_tipo[$tipo])) continue;
        $prenda = $prendas_por_tipo[$tipo];
        $foto   = htmlspecialchars($prenda['foto']   ?? '');
        $nombre = htmlspecialchars($prenda['nombre'] ?? 'Prenda');
        $slot   = $roles[$tipo];
        $label  = ucfirst($tipo);

        echo "<div class='collage-piece {$slot}'>";
        echo "  <div class='collage-piece-inner'>";
        $priority = in_array($tipo, ['remera', 'chaqueta', 'vestido'])
            ? 'fetchpriority="high" loading="eager"'
            : 'loading="lazy"';
        echo "    <img src='{$foto}' alt='{$nombre}' {$priority}>";
        echo "  </div>";
        echo "  <span class='piece-label'>{$nombre}</span>";
        echo "  <span class='piece-type-tag'>{$label}</span>";
        echo "</div>";
    }

    echo "</div>";

    // Strip de nombres
    echo "<div class='collage-strip'>";
    $orden_strip = ['remera', 'chaqueta', 'vestido', 'remera_interior', 'pantalon', 'zapatos'];
    $idx = 1;
    foreach ($orden_strip as $tipo) {
        if (!isset($prendas_por_tipo[$tipo])) continue;
        $nombre = htmlspecialchars($prendas_por_tipo[$tipo]['nombre'] ?? '');
        $num    = $tipo === 'remera_interior' ? 'INT' : '0' . $idx++;
        echo "<div class='strip-item'>";
        echo "  <span class='strip-num'>{$num}</span>";
        echo "  <span class='strip-name'>{$nombre}</span>";
        echo "</div>";
    }
    echo "</div>";
    echo "</div>"; // fin collage-outer

    // ── COMENTARIO DE ESTILISTA (Groq) ──
    $estilo          = $_SESSION['outfit_estilo'] ?? '';
    $genero          = $_SESSION['outfit_genero'] ?? '';
    $historial_texto = '';

    if (isset($_SESSION['nombre_usuario'])) {
        $hist = supabaseRequest('outfits_guardados',
            '?usuario=eq.' . urlencode($_SESSION['nombre_usuario'])
            . '&order=fecha.desc&limit=5'
            . '&select=prenda_remera,prenda_pantalon,prenda_zapatos,estilo'
        );
        if ($hist && count($hist) > 0) {
            $historial_texto = "";
            foreach ($hist as $h) {
                $historial_texto .= "- " . ($h['prenda_remera'] ?? '—') . " + "
                                        . ($h['prenda_pantalon'] ?? '—') . " + "
                                        . ($h['prenda_zapatos']  ?? '—')
                                        . " (" . ($h['estilo'] ?? '') . ")\n";
            }
        }
    }

    $comentario = generarComentarioEstilista($outfit, $estilo, $genero, $historial_texto);

    if ($comentario) {
        echo "<div style='
            margin-top: 24px;
            padding: 20px 24px;
            background: var(--bg-elevated);
            border: 1px solid var(--border);
            border-left: 3px solid var(--neon);
            border-radius: 2px;
        '>";
        echo "<p style='
            font-family: var(--font-mono);
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--neon);
            margin-bottom: 10px;
        '><strong>✦ ANÁLISIS DE ESTILISTA</strong></p>";
        echo "<p style='
            font-family: var(--font-body);
            font-size: 1rem;
            color: var(--white-dim);
            line-height: 1.6;
            font-weight: 400;
        '>" . htmlspecialchars($comentario) . "</p>";
        echo "</div>";
    }

    // Acciones
    echo "<div class='result-actions' style='margin-top:32px;'>";
    echo "  <a href='index.php' class='btn-rehacer'>ARMAR OTRO OUTFIT</a>";
    if (isset($_SESSION['nombre_usuario'])) {
        echo "  <form method='POST' action='resultado.php' style='display:inline;'>";
        echo "    <input type='hidden' name='accion' value='guardar_outfit'>";
        echo "    <button type='submit' class='btn-rehacer' style='background:transparent;border:2px solid var(--neon);color:var(--white);margin-left:12px;'>♥ ME GUSTA ESTE OUTFIT</button>";
        echo "  </form>";
    }
    echo "</div>";
}

// ============================================================
//  MOSTRAR ERROR AMIGABLE
// ============================================================
function mostrarError($mensaje) {
    echo "<div class='error-box' style='text-align:center;'>";
    echo "  <h3>¡Ups!</h3>";
    echo "  <p>" . htmlspecialchars($mensaje) . "</p>";
    echo "  <a href='index.php' class='btn-rehacer' style='display:inline-block;margin-top:16px;'>Intentar de nuevo</a>";
    echo "</div>";
}

// ============================================================
//  GUARDAR PREFERENCIAS DEL USUARIO
// ============================================================
function guardarPreferenciasUsuario($nombre, $genero, $estilo, $paleta = '', $pelo = '', $altura = '') {
    $datos = [
        'nombre'          => sanitizar($nombre),
        'genero'          => $genero,
        'estilo_favorito' => $estilo,
        'paleta_piel'     => $paleta,
        'pelo'            => trim($_POST['pelo'] ?? ''),
        'altura'          => $altura,
    ];
    supabaseInsert(TABLE_USUARIOS, $datos);
}

// ============================================================
//  GUARDAR OUTFIT APROBADO POR EL USUARIO
// ============================================================
function guardarOutfitUsuario() {
    if (!isset($_SESSION['nombre_usuario']) || !isset($_SESSION['outfit'])) return;

    $outfit  = $_SESSION['outfit'];
    $usuario = $_SESSION['nombre_usuario'];

    $remera = $pantalon = $zapatos = '';
    $foto_remera = $foto_pantalon = $foto_zapatos = '';

    foreach ($outfit as $prenda) {
        $tipo   = $prenda['tipo']   ?? '';
        $nombre = $prenda['nombre'] ?? '';
        $foto   = $prenda['foto']   ?? '';

        if (in_array($tipo, ['remera', 'vestido', 'chaqueta'])) {
            $remera      = $nombre;
            $foto_remera = $foto;
        }
        if ($tipo === 'pantalon') {
            $pantalon      = $nombre;
            $foto_pantalon = $foto;
        }
        if ($tipo === 'zapatos') {
            $zapatos      = $nombre;
            $foto_zapatos = $foto;
        }
        // remera_interior se ignora en el guardado
    }

    supabaseInsert('outfits_guardados', [
        'usuario'         => $usuario,
        'prenda_remera'   => $remera,
        'prenda_pantalon' => $pantalon,
        'prenda_zapatos'  => $zapatos,
        'foto_remera'     => $foto_remera,
        'foto_pantalon'   => $foto_pantalon,
        'foto_zapatos'    => $foto_zapatos,
        'estilo'          => $_SESSION['outfit_estilo'] ?? '',
        'genero'          => $_SESSION['outfit_genero'] ?? '',
    ]);
}

// ============================================================
//  HELPERS
// ============================================================
function sanitizar($valor) {
    return htmlspecialchars(strip_tags(trim($valor)), ENT_QUOTES, 'UTF-8');
}

function debugLog($mensaje) {
    if (!DEBUG_MODE) return;
    echo "<div style='background:#f8d7da;border:1px solid #f5c6cb;border-radius:6px;
                      padding:8px 14px;margin:6px 0;font-family:monospace;
                      font-size:0.8rem;text-align:left;color:#721c24;'>";
    echo "  🔍 DEBUG: " . htmlspecialchars($mensaje);
    echo "</div>";
}

function distanciaColor($hex1, $hex2) {
    $hex1 = ltrim($hex1, '#');
    $hex2 = ltrim($hex2, '#');

    $r1 = hexdec(substr($hex1, 0, 2));
    $g1 = hexdec(substr($hex1, 2, 2));
    $b1 = hexdec(substr($hex1, 4, 2));

    $r2 = hexdec(substr($hex2, 0, 2));
    $g2 = hexdec(substr($hex2, 2, 2));
    $b2 = hexdec(substr($hex2, 4, 2));

    return sqrt(pow($r1-$r2, 2) + pow($g1-$g2, 2) + pow($b1-$b2, 2));
}