<?php
// ============================================================
//  CONFIGURACIÓN DE SUPABASE
// ============================================================
define('SUPABASE_URL', 'https://ktguvewasxniobwmndfb.supabase.co');
define('SUPABASE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Imt0Z3V2ZXdhc3huaW9id21uZGZiIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzY5NjMwMjgsImV4cCI6MjA5MjUzOTAyOH0.UFMbMUXdKZt8IqRAX3Iv6MGREeVU-yKn5XdscVGTt54');
define('TABLE_PRENDAS',  'prendas');
define('TABLE_USUARIOS', 'usuarios');

define('DEBUG_MODE', true);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ============================================================
//  FUNCIONES DE COMUNICACIÓN CON SUPABASE  (usando cURL)
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
//  AUTH
// ============================================================
function verificarLogin($usuario, $contrasena) {
    $query = '?usuario=eq.' . urlencode($usuario)
           . '&contrasena=eq.' . urlencode($contrasena)
           . '&select=usuario'
           . '&limit=1';

    $resultado = supabaseRequest('cuentas', $query);

    debugLog("Login intento: usuario=$usuario | Query: $query | Resultados: " . count($resultado ?? []));
    debugLog("Respuesta completa: " . print_r($resultado, true));
    die(); // Detiene la ejecución para poder leer el debug

    if ($resultado && count($resultado) > 0) {
        return $resultado[0];
    }

    return null;
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
    // Recarga sin POST: mostrar outfit en sesión si existe
    if (empty($_POST) && isset($_SESSION['outfit']) && !empty($_SESSION['outfit'])) {
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
        $_SESSION['outfit']       = $outfit;
        $_SESSION['outfit_clave'] = $clave_actual;
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
//  ARMAR UN OUTFIT COMPLETO
// ============================================================
function obtenerOutfitCompleto($genero, $estilo, $tamano, $color_remera = '#ffffff', $color_pantalon = '#333333', $talle = '') {
    $outfit = [];

    $tiene_vestido = false;
    if ($genero === 'femenino' && $estilo !== 'deportivo') {
        $query_vestido = '?genero=in.(femenino,unisex)'
            . '&estilo=eq.' . urlencode($estilo)
            . '&tamano=eq.' . urlencode($tamano)
            . '&tipo=eq.vestido'
            . '&select=id,nombre,tipo,foto,hex&limit=100';

        $vestidos = supabaseRequest(TABLE_PRENDAS, $query_vestido);
        $vestidos = array_values(array_filter($vestidos ?? [], function($p) {
            return !empty($p['foto']);
        }));
        $tiene_vestido = count($vestidos) > 0 && rand(0, 1) === 1;
    }

    if ($tiene_vestido) {
        $colores = ['vestido' => $color_remera, 'zapatos' => null];
    } else {
        $colores = ['remera' => $color_remera, 'pantalon' => $color_pantalon, 'zapatos' => null];
    }
    

    foreach ($colores as $tipo => $color_elegido) {
        $intentos = [];

        if ($talle !== '') {
            $intentos[] = '?genero=in.(' . urlencode($genero) . ',unisex)'
                . '&estilo=eq.' . urlencode($estilo)
                . '&tamano=eq.' . urlencode($tamano)
                . '&talle=eq.'  . urlencode($talle)
                . '&tipo=eq.'   . urlencode($tipo)
                . '&select=id,nombre,tipo,foto,hex&limit=100';
        }

        $intentos[] = '?genero=in.(' . urlencode($genero) . ',unisex)'
            . '&estilo=eq.' . urlencode($estilo)
            . '&tamano=eq.' . urlencode($tamano)
            . '&tipo=eq.'   . urlencode($tipo)
            . '&select=id,nombre,tipo,foto,hex&limit=100';

        $intentos[] = '?genero=in.(' . urlencode($genero) . ',unisex)'
            . '&estilo=eq.' . urlencode($estilo)
            . '&tipo=eq.'   . urlencode($tipo)
            . '&select=id,nombre,tipo,foto,hex&limit=100';

        $intentos[] = '?genero=in.(' . urlencode($genero) . ',unisex)'
            . '&tipo=eq.'   . urlencode($tipo)
            . '&select=id,nombre,tipo,foto,hex&limit=100';

        $prendas = [];
        foreach ($intentos as $query) {
            $resultado = supabaseRequest(TABLE_PRENDAS, $query);
            $conFoto   = array_values(array_filter($resultado ?? [], function($p) {
                return !empty($p['foto']);
            }));
            if (count($conFoto) > 0) { $prendas = $conFoto; break; }
        }

        if (count($prendas) === 0) continue;

        // ✅ Verificar si alguna prenda tiene hex, no solo la primera
        $prendas_con_hex = array_values(array_filter($prendas, function($p) {
            return !empty($p['hex']);
        }));

        if ($color_elegido !== null && count($prendas_con_hex) > 0) {
            $mejor    = null;
            $min_dist = PHP_INT_MAX;
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
        'remera'   => 'slot-top',
        'vestido'  => 'slot-top',    // vestido ocupa el mismo slot que remera
        'pantalon' => 'slot-bottom',
        'zapatos'  => 'slot-shoes',
    ];

    // Construir array indexado por tipo
    $prendas_por_tipo = [];
    foreach ($outfit as $prenda) {
        $tipo = $prenda['tipo'] ?? '';
        if (isset($roles[$tipo])) {
            $prendas_por_tipo[$tipo] = $prenda;
        }
    }

    // Fallback: si las prendas no tienen campo tipo, usar orden de llegada
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

    $orden = ['remera', 'vestido', 'pantalon', 'zapatos'];
    foreach ($orden as $tipo) {
        if (!isset($prendas_por_tipo[$tipo])) continue;
        $prenda = $prendas_por_tipo[$tipo];
        $foto   = htmlspecialchars($prenda['foto']   ?? '');
        $nombre = htmlspecialchars($prenda['nombre'] ?? 'Prenda');
        $slot   = $roles[$tipo];
        $label  = ucfirst($tipo);

        echo "<div class='collage-piece {$slot}'>";
        echo "  <div class='collage-piece-inner'>";
        echo "    <img src='{$foto}' alt='{$nombre}' loading='lazy'>";
        echo "  </div>";
        echo "  <span class='piece-label'>{$nombre}</span>";
        echo "  <span class='piece-type-tag'>{$label}</span>";
        echo "</div>";
    }

    echo "</div>";

    // Strip inferior de nombres
    echo "<div class='collage-strip'>";
    $idx = 1;
    foreach ($orden as $tipo) {
        if (!isset($prendas_por_tipo[$tipo])) continue;
        $nombre = htmlspecialchars($prendas_por_tipo[$tipo]['nombre'] ?? '');
        echo "<div class='strip-item'>";
        echo "  <span class='strip-num'>0{$idx}</span>";
        echo "  <span class='strip-name'>{$nombre}</span>";
        echo "</div>";
        $idx++;
    }
    echo "</div>";

    echo "</div>";

    echo "<div class='result-actions' style='margin-top:32px;'>";
    echo "  <a href='index.php' class='btn-rehacer'>ARMAR OTRO OUTFIT</a>";
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