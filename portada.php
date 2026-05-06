<?php
// Destruir sesión anterior si existe
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Bienvenido a Outfit AI</title>
    <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body">
    <div class="contenedor portada-animada">
        <header>
            <div class="logo">OUTFIT AI</div>
            <h1>¡Hola! Queremos saber sobre vos</h1>
            <p>Antes de empezar el test, dinos tu nombre.</p>
        </header>
        <form action="index.php" method="POST">
            <div class="seccion-test">
                <input type="text" name="nombre_usuario" placeholder="Tu nombre" required>
            </div>
            <input type="submit" value="INICIAR" class="boton-grande">
        </form>
    </div>
</body>
</html>