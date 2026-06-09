<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("funciones.php");

if (isset($_POST['nombre'])) {
    $_SESSION['nombre_usuario'] = $_POST['nombre'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tu Outfit — PILCHA IA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="estilosresul.css">
</head>
<body>

<!-- NAV — idéntico al de index.php -->
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
        <?php generarOutfit(); ?>
    </main>

   

    <footer class="result-footer">
        &copy; 2026 PILCHA IA
    </footer>

</div>

</body>
</html>