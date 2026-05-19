<?php
// ============================================================
//  CONFIGURACIÓN DE SUPABASE
// ============================================================
define('SUPABASE_URL', 'https://ktguvewasxniobwmndfb.supabase.co');
define('SUPABASE_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6Imt0Z3V2ZXdhc3huaW9id21uZGZiIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzY5NjMwMjgsImV4cCI6MjA5MjUzOTAyOH0.UFMbMUXdKZt8IqRAX3Iv6MGREeVU-yKn5XdscVGTt54');
define('TABLE_PRENDAS',  'prendas');
define('TABLE_USUARIOS', 'usuarios');

// Modo debug: en TRUE muestra errores en pantalla. Pasar a FALSE en producción.
define('DEBUG_MODE', true);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// ============================================================
//  FUNCIONES DE COMUNICACIÓN CON SUPABASE  (usando cURL)
// ============================================================

/**
 * GET  → trae registros de una tabla.
 * $query es la querystring de PostgREST, ej: "?tipo=eq.remera&limit=10"
 */
function supabaseRequest($table, $query = '') {
    $url = SUPABASE_URL . '/rest/v1/' . $table . $query;

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            'apikey: '        . SUPABASE_KEY,
            'Authorization: Bearer ' . SUPABASE_KEY,
            'Content-Type: application/json',
            'Accept: application/json',
        ],
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    $response   = curl_exec($ch);
    $httpCode   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError  = curl_error($ch);
    curl_close($ch);

    // Log de errores cURL
    if ($curlError) {
        debugLog("cURL error en GET $table: $curlError");
        return null;
    }

    // Log de respuestas HTTP no exitosas
    if ($httpCode < 200 || $httpCode >= 300) {
        debugLog("HTTP $httpCode en GET $table$query — Respuesta: $response");
        return null;
    }

    $decoded = json_decode($response, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        debugLog("JSON inválido en GET $table: " . json_last_error_msg());
        return null;
    }

    return $decoded;
}

/**
 * POST → inserta un registro en una tabla.
 */
function supabaseInsert($table, $data) {
    $url = SUPABASE_URL . '/rest/v1/' . $table;

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($data),
        CURLOPT_HTTPHEADER     => [
            'apikey: '        . SUPABASE_KEY,
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

    if ($curlError) {
        debugLog("cURL error en INSERT $table: $curlError");
        return false;
    }

    if ($httpCode < 200 || $httpCode >= 300) {
        debugLog("HTTP $httpCode en INSERT $table — Respuesta: $response");
        return false;
    }

    return true;
}

// ============================================================
//  VERIFICAR LOGIN CONTRA SUPABASE
// ============================================================
function verificarLogin($usuario, $contrasena) {
    $query = '?usuario=eq.' . urlencode($usuario)
           . '&contrasena=eq.' . urlencode($contrasena)
           . '&select=usuario'
           . '&limit=1';

    $resultado = supabaseRequest('cuentas', $query);

    if ($resultado && count($resultado) > 0) {
        return $resultado[0];
    }

    return null;
}

function crearCuenta($usuario, $contrasena) {
    // Verificar si el usuario ya existe
    $existe = supabaseRequest('cuentas', '?usuario=eq.' . urlencode($usuario) . '&select=id&limit=1');
    if ($existe && count($existe) > 0) {
        return 'existe';
    }

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
    if (isset($_SESSION['outfit']) && !empty($_SESSION['outfit'])) {
    mostrarOutfit($_SESSION['outfit']);
    return;
}
    $genero = sanitizar($_POST['genero']  ?? '');
    $estilo = sanitizar($_POST['gustos']  ?? '');
    $tamano = sanitizar($_POST['tamaño']  ?? '');

    if (!$genero || !$estilo || !$tamano) {
        mostrarError("Faltan datos del formulario.");
        return;
    }

    $color_remera   = $_POST['color_remera']   ?? '#ffffff';
$color_pantalon = $_POST['color_pantalon'] ?? '#333333';
$talle = sanitizar($_POST['talle'] ?? '');
$outfit = obtenerOutfitCompleto($genero, $estilo, $tamano, $color_remera, $color_pantalon, $talle);

    if ($outfit && count($outfit) > 0) {
        mostrarOutfit($outfit);
        $paleta = sanitizar($_POST['paleta'] ?? '');
        $pelo   = sanitizar($_POST['pelo']   ?? '');
        $altura = sanitizar($_POST['altura']  ?? '');
        guardarPreferenciasUsuario($_POST['nombre'] ?? 'Usuario', $genero, $estilo, $paleta, $pelo, $altura);
    } else {
        mostrarError("No encontramos prendas para esa combinación.");
    }
    $_SESSION['outfit'] = $outfit;
}

// ============================================================
//  ARMAR UN OUTFIT COMPLETO (una prenda por tipo)
// ============================================================
function obtenerOutfitCompleto($genero, $estilo, $tamano, $color_remera = '#ffffff', $color_pantalon = '#333333', $talle = '') {
    $outfit = [];

    $colores = [
        'remera'   => $color_remera,
        'pantalon' => $color_pantalon,
        'zapatos'  => null,
    ];

    foreach ($colores as $tipo => $color_elegido) {

        // Intentos en orden: exacto → sin tamaño → sin estilo ni tamaño
        $intentos = [];

// Solo agregar intento con talle si el usuario lo seleccionó
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

            if (count($conFoto) > 0) {
                $prendas = $conFoto;
                break; // encontró, no sigue relajando
            }
        }

        if (count($prendas) === 0) continue;

        if ($color_elegido !== null && !empty($prendas[0]['hex'])) {
            $mejor    = null;
            $min_dist = PHP_INT_MAX;

            foreach ($prendas as $prenda) {
                $dist = distanciaColor($color_elegido, $prenda['hex'] ?? '#000000');
                if ($dist < $min_dist) {
                    $min_dist = $dist;
                    $mejor    = $prenda;
                }
            }

            $outfit[] = $mejor;
        } else {
            $outfit[] = $prendas[array_rand($prendas)];
        }
    }

    return $outfit;
}
// ============================================================
//  MOSTRAR EL OUTFIT EN PANTALLA
// ============================================================
function mostrarOutfit($outfit) {
    echo "<h2 class='titulo-resultado'>Tu Outfit Personalizado</h2>";
    echo "<div class='fichas'>";

    foreach ($outfit as $prenda) {
        $foto   = htmlspecialchars($prenda['foto']   ?? '');
        $nombre = htmlspecialchars($prenda['nombre'] ?? 'Prenda');
        $ruta   = $foto;

        echo "<div class='ficha2' style='display:flex;flex-direction:column;align-items:center;background:#fafafa;border:1px solid #eee;border-radius:14px;padding:16px;width:220px;'>";
        echo "  <div style='width:200px;height:200px;display:flex;align-items:center;justify-content:center;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 4px 8px rgba(0,0,0,0.08);'>";
        echo "    <img src='{$ruta}' alt='{$nombre}' loading='lazy' style='max-width:100%;max-height:100%;width:auto;height:auto;object-fit:contain;display:block;'>";
        echo "  </div>";
        echo "  <p style='margin-top:10px;font-weight:600;color:#444;font-size:0.9rem;text-align:center;'>{$nombre}</p>";
        echo "</div>";
    }

    echo "</div>";

    // Botón para rehacer el test con el mismo usuario
    $nombre = htmlspecialchars($_SESSION['nombre_usuario'] ?? 'Usuario');
    echo "<form method='POST' action='index.php' style='text-align:center;margin-top:30px;'>";
    echo "  <input type='hidden' name='nombre_usuario' value='{$nombre}'>";
    echo "  <button type='submit' class='rehacer'>← REHACER COMPLETO</button>";
    echo "</form>";

    echo "<p style='text-align:center;margin-top:20px;'>";
    echo "<a href='logout.php'>Cerrar sesión</a>";
    echo "</p>";
}

// ============================================================
//  MOSTRAR ERROR AMIGABLE
// ============================================================
function mostrarError($mensaje) {
    $nombre = htmlspecialchars($_SESSION['nombre_usuario'] ?? 'Usuario');

    echo "<div style='text-align:center;padding:20px;background:#fff3cd;
                      border:1px solid #ffc107;border-radius:10px;margin:20px 0;'>";
    echo "  <h3 style='color:#856404;'>¡Ups!</h3>";
    echo "  <p style='color:#856404;'>" . htmlspecialchars($mensaje) . "</p>";

    echo "  <form method='POST' action='index.php' style='margin-top:15px;'>";
    echo "    <input type='hidden' name='nombre_usuario' value='{$nombre}'>";
    echo "    <button type='submit' class='rehacer'>Intentar de nuevo</button>";
    echo "  </form>";
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
        'pelo' => trim($_POST['pelo'] ?? ''),
        'altura'          => $altura,
    ];

    supabaseInsert(TABLE_USUARIOS, $datos);
}

// ============================================================
//  HELPERS
// ============================================================

/** Limpia una cadena: elimina espacios y caracteres peligrosos */
function sanitizar($valor) {
    return htmlspecialchars(strip_tags(trim($valor)), ENT_QUOTES, 'UTF-8');
}

/**
 * Log de debug. Con DEBUG_MODE = true muestra en pantalla.
 * En producción, cambiar a false para que solo escriba en un archivo de log.
 */
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
