<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Solo usuarios logueados
if (!isset($_SESSION['nombre_usuario'])) {
    header('Location: portada0.php');
    exit;
}

include_once("funciones.php");

$usuario  = $_SESSION['nombre_usuario'];
$query    = '?usuario=eq.' . urlencode($usuario)
          . '&order=fecha.desc'
          . '&limit=5'
          . '&select=id,prenda_remera,prenda_pantalon,prenda_zapatos,estilo,genero,fecha';

$historial = supabaseRequest('outfits_guardados', $query) ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Historial — PILCHA IA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="estilosresul.css">
    <style>
        /* ── HISTORIAL ── */
        .historial-grid {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-top: 32px;
        }

        .historial-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 2px;
            padding: 24px 28px;
            display: grid;
            grid-template-columns: auto 1fr auto;
            align-items: center;
            gap: 24px;
            position: relative;
            overflow: hidden;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .historial-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 60px; height: 1px;
            background: var(--neon);
            opacity: 0.5;
        }

        .historial-card::after {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 1px; height: 60px;
            background: var(--neon);
            opacity: 0.5;
        }

        .historial-card:hover {
            border-color: var(--border-strong);
            box-shadow: 0 0 0 1px rgba(104,2,27,0.18), 0 8px 24px rgba(0,0,0,0.3);
        }

        .card-num {
            font-family: var(--font-display);
            font-size: 3rem;
            color: rgba(245,236,220,0.06);
            -webkit-text-stroke: 1px rgba(245,236,220,0.12);
            line-height: 1;
            min-width: 48px;
        }

        .card-prendas {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .prenda-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .prenda-tipo {
            font-family: var(--font-mono);
            font-size: 0.5rem;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: var(--neon);
            min-width: 68px;
        }

        .prenda-nombre {
            font-family: var(--font-body);
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--white);
            letter-spacing: 0.02em;
        }

        .prenda-nombre.vacio {
            color: var(--white-muted);
            font-style: italic;
            font-weight: 400;
        }

        .card-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
            min-width: 100px;
        }

        .meta-estilo {
            font-family: var(--font-mono);
            font-size: 0.55rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--white);
            background: var(--neon);
            padding: 4px 10px;
            border-radius: 1px;
        }

        .meta-fecha {
            font-family: var(--font-mono);
            font-size: 0.55rem;
            color: var(--white-muted);
            letter-spacing: 0.1em;
        }

        .meta-genero {
            font-family: var(--font-mono);
            font-size: 0.52rem;
            color: var(--white-muted);
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        /* Estado vacío */
        .historial-vacio {
            text-align: center;
            padding: 60px 20px;
            border: 1px solid var(--border);
            border-radius: 2px;
            margin-top: 32px;
        }

        .historial-vacio h3 {
            font-family: var(--font-display);
            font-size: 1.8rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--white-muted);
            margin-bottom: 12px;
        }

        .historial-vacio p {
            font-family: var(--font-mono);
            font-size: 0.7rem;
            color: var(--white-muted);
            letter-spacing: 0.1em;
        }

        @media (max-width: 600px) {
            .historial-card {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .card-num { font-size: 2rem; }
            .card-meta { align-items: flex-start; }
        }
    </style>
</head>
<body>

<nav class="nav">
    <a href="portada0.php" class="nav-brand">PILCHA<span> IA</span></a>
    <span class="nav-tagline">Mi Historial</span>
</nav>

<div class="page">

    <div class="result-header">
        <h1>Mi Historial</h1>
        <p>Tus últimos 5 outfits guardados, <?php echo htmlspecialchars($usuario); ?></p>
    </div>

    <?php if (empty($historial)): ?>

        <div class="historial-vacio">
            <h3>Sin outfits aún</h3>
            <p>Todavía no guardaste ningún outfit. Armá uno y presioná ♥ ME GUSTA ESTE OUTFIT.</p>
        </div>

    <?php else: ?>

        <div class="historial-grid">
            <?php foreach ($historial as $i => $item):
                $remera   = $item['prenda_remera']   ?? '';
                $pantalon = $item['prenda_pantalon'] ?? '';
                $zapatos  = $item['prenda_zapatos']  ?? '';
                $estilo   = ucfirst($item['estilo']  ?? '');
                $genero   = ucfirst($item['genero']  ?? '');
                $fecha    = date('d/m/Y', strtotime($item['fecha'] ?? 'now'));
            ?>
            <div class="historial-card">
                <span class="card-num">0<?php echo $i + 1; ?></span>

                <div class="card-prendas">
                    <div class="prenda-item">
                        <span class="prenda-tipo">Superior</span>
                        <span class="prenda-nombre <?php echo $remera ? '' : 'vacio'; ?>">
                            <?php echo $remera ? htmlspecialchars($remera) : '—'; ?>
                        </span>
                    </div>
                    <div class="prenda-item">
                        <span class="prenda-tipo">Inferior</span>
                        <span class="prenda-nombre <?php echo $pantalon ? '' : 'vacio'; ?>">
                            <?php echo $pantalon ? htmlspecialchars($pantalon) : '—'; ?>
                        </span>
                    </div>
                    <div class="prenda-item">
                        <span class="prenda-tipo">Calzado</span>
                        <span class="prenda-nombre <?php echo $zapatos ? '' : 'vacio'; ?>">
                            <?php echo $zapatos ? htmlspecialchars($zapatos) : '—'; ?>
                        </span>
                    </div>
                </div>

                <div class="card-meta">
                    <?php if ($estilo): ?>
                        <span class="meta-estilo"><?php echo htmlspecialchars($estilo); ?></span>
                    <?php endif; ?>
                    <span class="meta-genero"><?php echo htmlspecialchars($genero); ?></span>
                    <span class="meta-fecha"><?php echo $fecha; ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

    <div class="result-actions" style="margin-top:32px;">
        <a href="index.php" class="btn-rehacer">ARMAR NUEVO OUTFIT</a>
    </div>

    <footer class="result-footer">
        &copy; 2026 PILCHA IA
    </footer>

</div>

</body>
</html>
