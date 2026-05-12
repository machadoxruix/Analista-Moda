<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    unset($_SESSION['outfit']);
}

// Si viene de POST (nuevo test), guardar en sesión
if (isset($_POST['nombre_usuario'])) {
    $_SESSION['nombre_usuario'] = $_POST['nombre_usuario'];
}

// Usar el nombre de la sesión o mostrar "Invitado"
$nombre = $_SESSION['nombre_usuario'] ?? "Invitado";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Outfit AI - Test de Estilo</title>
    <link rel="icon" type="image/x-icon" href="./favicon.ico">
    <!-- Forzar recarga del CSS sin cacheo -->
    <link href="estilos.css?v=<?php echo time(); ?>" rel="stylesheet" type="text/css">
</head>
<body>

<div class="contenedor">
    <header>
        <h2>Hola <?php echo htmlspecialchars($nombre); ?>, armemos tu outfit</h2>
        <p>Completá tu perfil para una mejor recomendación.</p>
    </header>

    <form action="resultado.php" method="POST">
        <input type="hidden" name="nombre" value="<?php echo htmlspecialchars($nombre); ?>">

        <h3>1. Perfil Físico</h3>
        <div class="contenedor-biometria">
            
            <div class="sub-bloque">
                <p>Tono de Piel</p>
                <div class="slider-container">
                    <div class="slider-wrapper" id="tonoSlider">
                        <div class="slider-item">
                            <label>
                                <input type="radio" name="paleta" value="piel1" required>
                                <img src="fotos/piel/1.jpg" alt="Piel 1">
                            </label>
                        </div>
                        <div class="slider-item">
                            <label>
                                <input type="radio" name="paleta" value="piel2">
                                <img src="fotos/piel/2.jpg" alt="Piel 2">
                            </label>
                        </div>
                        <div class="slider-item">
                            <label>
                                <input type="radio" name="paleta" value="piel3">
                                <img src="fotos/piel/3.jpg" alt="Piel 3">
                            </label>
                        </div>
                        <div class="slider-item">
                            <label>
                                <input type="radio" name="paleta" value="piel4">
                                <img src="fotos/piel/4.jpg" alt="Piel 4">
                            </label>
                        </div>
                        <div class="slider-item">
                            <label>
                                <input type="radio" name="paleta" value="piel5">
                                <img src="fotos/piel/5.jpg" alt="Piel 5">
                            </label>
                        </div>
                        <div class="slider-item">
                            <label>
                                <input type="radio" name="paleta" value="piel6">
                                <img src="fotos/piel/6.jpg" alt="Piel 6">
                            </label>
                        </div>
                    </div>
                    <button type="button" class="slider-btn prev" id="tonoPrev">❮</button>
                    <button type="button" class="slider-btn next" id="tonoNext">❯</button>
                </div>
            </div>

            <div class="sub-bloque">
                <p>Color de Pelo</p>
                <div class="slider-container">
                    <div class="slider-wrapper" id="peloSlider">
                        <div class="slider-item">
                            <label>
                                <input type="radio" name="pelo" value="1" required>
                                <img src="fotos/pelo/1.jpg" alt="1">
                            </label>
                        </div>
                        <div class="slider-item">
                            <label>
                                <input type="radio" name="pelo" value="2">
                                <img src="fotos/pelo/2.jpg" alt="2">
                            </label>
                        </div>
                        <div class="slider-item">
                            <label>
                                <input type="radio" name="pelo" value="3">
                                <img src="fotos/pelo/3.jpg" alt="3">
                            </label>
                        </div>
                        <div class="slider-item">
                            <label>
                                <input type="radio" name="pelo" value="4">
                                <img src="fotos/pelo/4.jpg" alt="4">
                            </label>
                        </div>
                        <div class="slider-item">
                            <label>
                                <input type="radio" name="pelo" value="5">
                                <img src="fotos/pelo/5.jpg" alt="5">
                            </label>
                        </div>
                        <div class="slider-item">
                            <label>
                                <input type="radio" name="pelo" value="6">
                                <img src="fotos/pelo/6.jpg" alt="6">
                            </label>
                        </div>
                    </div>
                    <button type="button" class="slider-btn prev" id="peloPrev">❮</button>
                    <button type="button" class="slider-btn next" id="peloNext">❯</button>
                </div>
            </div>

        </div>

        <h3>2. Estilo, Género y altura</h3>
        <select name="altura" required>
            <option value="" selected disabled>Elegí tu altura...</option>
            <option value="altura1">Menos de 1,60m</option>
            <option value="altura2">Entre 1,60m y 1,75m</option>
            <option value="altura3">Mas de 1,75m</option>
        </select>
        
        <select name="genero" required>
            <option value="" selected disabled>Elegí tu género...</option>
            <option value="masculino">Masculino</option>
            <option value="femenino">Femenino</option>
            <option value="unisex">Unisex</option>
        </select>

        <select name="gustos" required>
            <option value="" selected disabled>Elegí tu estilo...</option>
            <option value="formal">Formal</option>
            <option value="casual">Casual</option>
            <option value="deportivo">Deportivo</option>
            <option value="elegante">Elegante</option>
        </select>

        <select name="tamaño" required>
            <option value="" selected disabled>Elegí el talle...</option>
            <option value="holgada">Holgada (Oversize)</option> 
            <option value="ajustada">Ajustada (Slim)</option> 
            <option value="recto">Recto (Straight)</option> 
        </select>

        <h3>3. Colores de la Indumentaria</h3>
        <div class="contenedor-colores">
            <div class="item-color">
                <p>Superior</p>
                <input type="color" name="color_remera" value="#800020">
            </div>
            <div style="border-left: 1px solid #eee; height: 60px; margin: 0 20px;"></div>
            <div class="item-color">
                <p>Inferior</p>
                <input type="color" name="color_pantalon" value="#333333">
            </div>
        </div>

        <input type="submit" value="GENERAR MI OUTFIT" class="boton-grande">
    </form>
</div>

<script>
    class Slider {
        constructor(wrapperId, prevBtnId, nextBtnId) {
            this.wrapper = document.getElementById(wrapperId);
            this.prevBtn = document.getElementById(prevBtnId);
            this.nextBtn = document.getElementById(nextBtnId);
            this.scrollAmount = 110;
            this.currentPosition = 0;

            this.prevBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.scroll(-1);
            });
            
            this.nextBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.scroll(1);
            });

            this.updateButtonStates();
        }

        scroll(direction) {
            const container = this.wrapper.parentElement;
            const maxScroll = this.wrapper.scrollWidth - container.clientWidth + 70;
            
            this.currentPosition = Math.max(0, Math.min(
                this.currentPosition + (direction * this.scrollAmount),
                maxScroll
            ));
            
            this.wrapper.style.transform = `translateX(-${this.currentPosition}px)`;
            this.updateButtonStates();
        }

        updateButtonStates() {
            const container = this.wrapper.parentElement;
            const maxScroll = this.wrapper.scrollWidth - container.clientWidth + 70;
            
            this.prevBtn.disabled = this.currentPosition <= 0;
            this.nextBtn.disabled = this.currentPosition >= maxScroll;
        }
    }

    new Slider('tonoSlider', 'tonoPrev', 'tonoNext');
    new Slider('peloSlider', 'peloPrev', 'peloNext');
</script>

</body>
</html>