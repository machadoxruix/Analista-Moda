<?php
session_start();

$error   = '';
$exito   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario    = trim($_POST['usuario']    ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');
    $confirmar  = trim($_POST['confirmar']  ?? '');

    if (!$usuario || !$contrasena || !$confirmar) {
        $error = 'Completá todos los campos.';
    } elseif ($contrasena !== $confirmar) {
        $error = 'Las contraseñas no coinciden.';
    } elseif (strlen($contrasena) < 4) {
        $error = 'La contraseña debe tener al menos 4 caracteres.';
    } else {
        include_once("funciones.php");
        $resultado = crearCuenta($usuario, $contrasena);

        if ($resultado === 'ok') {
            header('Location: portada.php?cuenta=creada');
            exit;
        } elseif ($resultado === 'existe') {
            $error = 'Ese nombre de usuario ya está en uso.';
        } else {
            $error = 'Hubo un error al crear la cuenta. Intentá de nuevo.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Outfit AI — Crear Cuenta</title>
    <link rel="icon" href="fotos/favicon.ico">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
        .login-box { max-width: 400px; margin: 0 auto; }
        .logo { font-size: 2rem; font-weight: 900; color: #800020; letter-spacing: 3px; margin-bottom: 8px; }
        .login-box h1 { font-size: 1.3rem; color: #333; margin-bottom: 6px; }
        .login-box p { color: #888; font-size: 0.9rem; margin-bottom: 30px; }
        .campo { width: 100%; padding: 13px 16px; border: 2px solid #eee; border-radius: 10px; font-size: 1rem; margin-bottom: 14px; box-sizing: border-box; transition: border-color 0.2s ease; text-align: left; }
        .campo:focus { outline: none; border-color: #800020; }
        .error-msg { background: #fff0f0; border: 1px solid #f5c6c6; color: #a00020; border-radius: 8px; padding: 10px 14px; font-size: 0.9rem; margin-bottom: 16px; }
    </style>
</head>
<body>
    <div class="contenedor">
        <div class="login-box">
            <div class="logo">OUTFIT AI</div>
            <h1>Crear cuenta</h1>
            <p>Elegí un usuario y contraseña para ingresar.</p>

            <?php if ($error): ?>
                <div class="error-msg">⚠️ <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="portada0.php" method="POST">
                <input class="campo" type="text" name="usuario" placeholder="Usuario"
                    value="<?php echo htmlspecialchars($_POST['usuario'] ?? ''); ?>" required>
                <input class="campo" type="password" name="contrasena" placeholder="Contraseña" required>
                <input class="campo" type="password" name="confirmar" placeholder="Confirmar contraseña" required>
                <input type="submit" value="CREAR CUENTA" class="boton-grande" style="width:100%;margin-top:6px;">
            </form>

            <p style="margin-top:20px;">
                ¿Ya tenés cuenta? <a href="portada.php">Iniciá sesión</a>
            </p>
        </div>
    </div>
</body>
</html>
