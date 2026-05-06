<?php
session_start();

// Si ya hay sesión activa, ir directo al test
if (isset($_SESSION['nombre_usuario'])) {
    header('Location: index.php');
    exit;
}

$error = '';

// Procesar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario    = trim($_POST['usuario']    ?? '');
    $contrasena = trim($_POST['contrasena'] ?? '');

    if ($usuario === '' || $contrasena === '') {
        $error = 'Completá ambos campos.';
    } else {
        include_once("funciones.php");
        $cuenta = verificarLogin($usuario, $contrasena);

        if ($cuenta) {
            $_SESSION['nombre_usuario'] = $cuenta['usuario'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Usuario o contraseña incorrectos.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Outfit AI — Iniciar Sesión</title>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
        /* ── Estilos exclusivos del login ── */
        .login-box {
            max-width: 400px;
            margin: 0 auto;
        }

        .logo {
            font-size: 2rem;
            font-weight: 900;
            color: #800020;
            letter-spacing: 3px;
            margin-bottom: 8px;
        }

        .login-box h1 {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 6px;
        }

        .login-box p {
            color: #888;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .campo {
            width: 100%;
            padding: 13px 16px;
            border: 2px solid #eee;
            border-radius: 10px;
            font-size: 1rem;
            margin-bottom: 14px;
            box-sizing: border-box;
            transition: border-color 0.2s ease;
            text-align: left;
        }

        .campo:focus {
            outline: none;
            border-color: #800020;
        }

        .error-msg {
            background: #fff0f0;
            border: 1px solid #f5c6c6;
            color: #a00020;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.9rem;
            margin-bottom: 16px;
        }

        .divisor {
            border: none;
            border-top: 1px solid #eee;
            margin: 24px 0;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <div class="login-box">

            <div class="logo">OUTFIT AI</div>
            <h1>Bienvenido</h1>
            <p>Iniciá sesión para armar tu outfit personalizado.</p>

            <?php if ($error): ?>
                <div class="error-msg">⚠️ <?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="portada.php" method="POST">
                <input
                    class="campo"
                    type="text"
                    name="usuario"
                    placeholder="Usuario"
                    value="<?php echo htmlspecialchars($_POST['usuario'] ?? ''); ?>"
                    required
                    autocomplete="username"
                >
                <input
                    class="campo"
                    type="password"
                    name="contrasena"
                    placeholder="Contraseña"
                    required
                    autocomplete="current-password"
                >
                <input type="submit" value="INICIAR SESIÓN" class="boton-grande" style="width:100%;margin-top:6px;">
            </form>

        </div>
    </div>
</body>
</html>