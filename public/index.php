<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../src/workouts.php';

//el JSON existe
ensureWorkoutsJsonExists();

//Cargamos los 84 días y rutinas
$workouts = loadWorkouts();

//Procesar guardado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dia = isset($_POST['dia']) ? (int)$_POST['dia'] : 0;

    if ($dia > 0) {
        $idx = $dia; // indice $dia en workouts empieza en 1

        if (isset($workouts[$idx])) {
            $workouts[$idx]['realizado'] = isset($_POST['realizado']);
            $workouts[$idx]['notas'] = $_POST['notas'] ?? '';

            // Ejercicios AM
            $am_ej = $_POST['am_ejercicios'] ?? [];
            $am_ej = array_map('trim', $am_ej);
            $am_ej = array_values(array_filter($am_ej, fn($v) => $v !== '')); // fn($v) carga en $v cada valor del array y => $v 
            $workouts[$idx]['am_ejercicios'] = $am_ej;

            // Ejercicios PM
            $pm_ej = $_POST['pm_ejercicios'] ?? [];
            $pm_ej = array_map('trim', $pm_ej);
            $pm_ej = array_values(array_filter($pm_ej, fn($v) => $v !== ''));
            $workouts[$idx]['pm_ejercicios'] = $pm_ej;

            saveWorkouts($workouts);
        }
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

//Progreso
$total = count($workouts);
$done  = 0;
foreach ($workouts as $w) {
    if (!empty($w['realizado'])) {
        $done++;
    }
}
$percent = $total > 0 ? round(($done / $total) * 100) : 0;

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Seguimiento 84 días</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container-fluid py-4">
        <!-- Cabecera con progreso -->
        <header class="d-flex flex-column flex-md-row align-items-md-center justify-content-md-between mb-4">
            <div>
                <h1 class="mb-1">Seguimiento 84 días</h1>
                <p class="mb-0 text-muted">
                    Completados: <strong><?= $done ?>/<?= $total ?></strong> (<?= $percent ?>%)
                </p>
            </div>
            <div class="mt-3 mt-md-0 w-100" style="max-width:400px;">
                <div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?= $percent ?>">
                    <div class="progress-bar" style="width: <?= $percent ?>%;"><?= $percent ?>%</div>
                </div>
            </div>
        </header>

        <!-- Acordeón con Fuerza A / B, etc… -->
        <p class="d-inline-flex gap-1">
            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                Fuerza A y B
            </button>
        </p>
        <div class="collapse col-6" id="collapseExample">
            <div class="card card-body">
                <div class="container">
                    <div class="row align-items-start">
                        <div class="col-6">
                            <span class="fw-semibold">Fuerza plan A:</span>
                            <ul>
                                <li>Press pierna + Deadlift 20kg 3x12</li>
                                <li>Curl femoral 3x12</li>
                                <li>Press pecho 3x12</li>
                                <li>Remo polea 3x12</li>
                            </ul>                            
                        </div>
                        <div class="col-6">
                            <span class="fw-semibold">Fuerza plan B:</span>
                            <ul>
                                <li>Press pierna 3x12</li>
                                <li>Sentadilla asistida 3x12</li>
                                <li>Press pecho 3x12</li>
                                <li>Remo polea supino 3x12</li>
                                <li>Curl femoral 3x12</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Numero de semana y titulo -->
        <?php $currentWeek = 0; ?>

        <div class="container-fluid row">
            <?php foreach ($workouts as $w): ?>
                <?php
                $dia   = (int)$w['dia'];
                $week  = (int)ceil($dia / 7);
                $fase  = faseFromWeek($week);

                if ($week !== $currentWeek):
                    $currentWeek = $week;
                ?>
                    <div class="col-12">
                        <h2 class="mt-3 mb-2">
                            Semana <?= $week ?> – <?= $fase ?>
                        </h2>
                    </div>
                <?php endif; ?>

                <!-- Card de cada día -->
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="card h-100">
                        <form method="POST">
                            <input type="hidden" name="dia" value="<?= $dia ?>">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span><strong>Día <?= htmlspecialchars($dia) ?></strong></span>
                                <?php if (!empty($w['realizado'])): ?>
                                    <span class="badge bg-success">Completado</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Pendiente</span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <!-- AM -->
                                <p class="mb-1"><strong>AM:</strong> <?= htmlspecialchars($w['am_base']) ?></p>

                                <!-- añadir ejercicios en AM -->
                                <div class="mb-3">

                                    <?php
                                    // Array de ejercicios AM (strings simples)
                                    $am_ej = $w['am_ejercicios'] ?? [];
                                    // Añadimos una línea vacía al final para permitir añadir uno nuevo
                                    $am_ej[] = '';
                                    foreach ($am_ej as $ej):
                                    ?>
                                        <input
                                            type="text"
                                            class="form-control form-control-sm mb-1"
                                            name="am_ejercicios[]"
                                            value="<?= htmlspecialchars($ej) ?>"
                                            placeholder="Añadir ejercicio">
                                    <?php endforeach; ?>
                                </div>

                                <!-- PM -->
                                <p class="mb-1"><strong>PM:</strong> <?= htmlspecialchars($w['pm_base']) ?></p>
                                <!-- añadir ejercicios en PM -->
                                <div class="mb-3">

                                    <?php
                                    $pm_ej = $w['pm_ejercicios'] ?? [];
                                    $pm_ej[] = '';
                                    foreach ($pm_ej as $ej):
                                    ?>
                                        <input
                                            type="text"
                                            class="form-control form-control-sm mb-1"
                                            name="pm_ejercicios[]"
                                            value="<?= htmlspecialchars($ej) ?>"
                                            placeholder="Añadir ejercicio">
                                    <?php endforeach; ?>
                                </div>

                                <!-- Checkbox realizado -->
                                <div class="form-check mb-3">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        id="realizado_<?= $dia ?>"
                                        name="realizado"
                                        <?= !empty($w['realizado']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="realizado_<?= $dia ?>">
                                        Marcar como realizado
                                    </label>
                                </div>

                                <!-- Notas -->
                                <div class="mb-3">
                                    <label class="form-label">Notas</label>
                                    <textarea
                                        class="form-control"
                                        name="notas"
                                        rows="2"><?= htmlspecialchars($w['notas']) ?></textarea>
                                </div>

                                <!-- Botón Guardar -->
                                <button type="submit" class="btn btn-primary btn-sm w-100">
                                    Guardar
                                </button>

                            </div>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>