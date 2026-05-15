<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include("funciones.php");

// Si viene POST, guardar en sesión
if (isset($_POST['nombre'])) {
    $_SESSION['nombre_usuario'] = $_POST['nombre'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Tu Outfit Personalizado</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link href="estilos.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="contenedor">
        <header>
            <div class="logo" style="color: #800020; font-weight: 800; font-size: 1.5rem; margin-bottom: 10px;">PILCHA IA</div>
            <h1>Tu Propuesta de Estilo</h1>
            <p>Hemos seleccionado estas prendas para tu outfit.</p>
        </header>

        <main>
            <?php 
                generarOutfit();
            ?>
        </main>

        <footer style="margin-top: 50px; text-align: center; border-top: 1px solid #eee; padding-top: 20px;">
            <p style="color: #999; font-size: 0.8rem;">&copy; 2026 Outfit AI</p>
        </footer>
    </div>
</body>
</html>