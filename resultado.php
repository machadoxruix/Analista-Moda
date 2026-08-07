<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once("funciones.php");

// Procesar guardado de outfit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['accion'] ?? '') === 'guardar_outfit') {
    guardarOutfitUsuario();
    $_SESSION['outfit_guardado'] = true;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Outfit — PILCHA IA</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="estilosresul.css">
    <link rel="preconnect" href="https://i.ibb.co">
    <link rel="dns-prefetch" href="https://i.ibb.co">
</head>
<body>

<nav class="nav">
    <a href="portada0.php" class="nav-brand">PILCHA<span> IA</span></a>
    <span class="nav-tagline">Tu outfit personalizado</span>
</nav>

<div class="page">

    <div class="result-header">
        <h1>Tu Outfit</h1>
        <p>Selección personalizada basada en tu estilo</p>
    </div>

    <main>
        <?php if (!empty($_SESSION['outfit_guardado'])): ?>
            <div style="text-align:center;margin-bottom:16px;font-family:var(--font-mono);
                        font-size:0.75rem;color:var(--neon);letter-spacing:0.1em;">
                ✓ OUTFIT GUARDADO EN TU HISTORIAL
            </div>
            <?php unset($_SESSION['outfit_guardado']); ?>
        <?php endif; ?>

        <?php generarOutfit(); ?>
    </main>

    <footer class="result-footer">
        &copy; 2026 PILCHA IA
    </footer>

</div>

</body>
</html>