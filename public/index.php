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
        $idx = $dia;

        if (isset($workouts[$idx])) {
            $workouts[$idx]['realizado'] = isset($_POST['realizado']);
            $workouts[$idx]['notas'] = $_POST['notas'] ?? '';

            // Ejercicios AM - limpieza mejorada
            $am_ej = $_POST['am_ejercicios'] ?? [];
            $am_ej = array_map('trim', $am_ej);
            $am_ej = array_values(array_filter($am_ej, fn($v) => $v !== ''));
            $workouts[$idx]['am_ejercicios'] = $am_ej;

            // Ejercicios PM - limpieza mejorada
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .ejercicio-item {
            position: relative;
            margin-bottom: 8px;
        }
        .btn-remove-ejercicio {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            padding: 2px 8px;
            font-size: 14px;
            line-height: 1;
        }
        .ejercicio-input {
            padding-right: 40px;
        }
        .btn-add-ejercicio {
            font-size: 14px;
        }
        .am-section {
            border-left: 3px solid #0d6efd;
            padding-left: 12px;
        }
        .pm-section {
            border-left: 3px solid #0dcaf0;
            padding-left: 12px;
        }
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
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

        <!-- Acordeón con Fuerza A / B -->
        <p class="d-inline-flex gap-1">
            <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                <i class="bi bi-lightning-charge-fill"></i> Fuerza A y B
            </button>
        </p>
        <div class="collapse col-12 col-lg-6" id="collapseExample">
            <div class="card card-body">
                <div class="container">
                    <div class="row align-items-start">
                        <div class="col-12 col-md-6">
                            <span class="fw-semibold text-primary"><i class="bi bi-bookmark-fill"></i> Fuerza plan A:</span>
                            <ul class="mt-2">
                                <li>Press pierna + Deadlift 20kg 3x12</li>
                                <li>Curl femoral 3x12</li>
                                <li>Press pecho 3x12</li>
                                <li>Remo polea 3x12</li>
                            </ul>                            
                        </div>
                        <div class="col-12 col-md-6">
                            <span class="fw-semibold text-info"><i class="bi bi-bookmark-fill"></i> Fuerza plan B:</span>
                            <ul class="mt-2">
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
                        <h2 class="mt-4 mb-3">
                            <i class="bi bi-calendar-week"></i> Semana <?= $week ?> – <?= $fase ?>
                        </h2>
                    </div>
                <?php endif; ?>

                <!-- Card de cada día -->
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="card h-100 shadow-sm">
                        <form method="POST" id="form_dia_<?= $dia ?>">
                            <input type="hidden" name="dia" value="<?= $dia ?>">
                            <div class="card-header d-flex justify-content-between align-items-center bg-white">
                                <span><strong><i class="bi bi-calendar-day"></i> Día <?= htmlspecialchars($dia) ?></strong></span>
                                <?php if (!empty($w['realizado'])): ?>
                                    <span class="badge bg-success"><i class="bi bi-check-circle-fill"></i> Completado</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><i class="bi bi-clock"></i> Pendiente</span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <!-- AM -->
                                <div class="am-section mb-3">
                                    <p class="mb-2"><strong class="text-primary"><i class="bi bi-sunrise-fill"></i> Mañana</strong> <?= htmlspecialchars($w['am_base']) ?></p>
                                    
                                    <div class="ejercicios-container-am" data-tipo="am">
                                        <?php
                                        $am_ej = $w['am_ejercicios'] ?? [];
                                        foreach ($am_ej as $idx => $ej):
                                        ?>
                                            <div class="ejercicio-item fade-in">
                                                <input
                                                    type="text"
                                                    class="form-control form-control-sm ejercicio-input"
                                                    name="am_ejercicios[]"
                                                    value="<?= htmlspecialchars($ej) ?>"
                                                    placeholder="Ejercicio adicional">
                                                <button type="button" class="btn btn-danger btn-sm btn-remove-ejercicio" onclick="removeEjercicio(this)">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <button type="button" class="btn btn-outline-primary btn-sm btn-add-ejercicio mt-2" onclick="addEjercicio('am', <?= $dia ?>)">
                                        <i class="bi bi-plus-circle"></i> Añadir
                                    </button>
                                </div>

                                <!-- PM -->
                                <div class="pm-section mb-3">
                                    <p class="mb-2"><strong class="text-info"><i class="bi bi-sunset-fill"></i> Tarde</strong> <?= htmlspecialchars($w['pm_base']) ?></p>
                                    
                                    <div class="ejercicios-container-pm" data-tipo="pm">
                                        <?php
                                        $pm_ej = $w['pm_ejercicios'] ?? [];
                                        foreach ($pm_ej as $idx => $ej):
                                        ?>
                                            <div class="ejercicio-item fade-in">
                                                <input
                                                    type="text"
                                                    class="form-control form-control-sm ejercicio-input"
                                                    name="pm_ejercicios[]"
                                                    value="<?= htmlspecialchars($ej) ?>"
                                                    placeholder="Ejercicio adicional">
                                                <button type="button" class="btn btn-danger btn-sm btn-remove-ejercicio" onclick="removeEjercicio(this)">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <button type="button" class="btn btn-outline-info btn-sm btn-add-ejercicio mt-2" onclick="addEjercicio('pm', <?= $dia ?>)">
                                        <i class="bi bi-plus-circle"></i> Añadir
                                    </button>
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
                                    <label class="form-label"><i class="bi bi-journal-text"></i> Notas</label>
                                    <textarea
                                        class="form-control form-control-sm"
                                        name="notas"
                                        rows="2"
                                        placeholder=""><?= htmlspecialchars($w['notas']) ?></textarea>
                                </div>

                                <!-- Botón Guardar -->
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-save"></i> Guardar
                                </button>

                            </div>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Añadir nuevo ejercicio
        function addEjercicio(tipo, dia) {
            const container = document.querySelector(`#form_dia_${dia} .ejercicios-container-${tipo}`);
            
            const newItem = document.createElement('div');
            newItem.className = 'ejercicio-item fade-in';
            newItem.innerHTML = `
                <input
                    type="text"
                    class="form-control form-control-sm ejercicio-input"
                    name="${tipo}_ejercicios[]"
                    placeholder="Ejercicio adicional"
                    autofocus>
                <button type="button" class="btn btn-danger btn-sm btn-remove-ejercicio" onclick="removeEjercicio(this)">
                    <i class="bi bi-x-lg"></i>
                </button>
            `;
            
            container.appendChild(newItem);
            
            // Foco automático en el nuevo input
            const newInput = newItem.querySelector('input');
            newInput.focus();
        }

        // Eliminar ejercicio
        function removeEjercicio(btn) {
            const item = btn.closest('.ejercicio-item');
            const input = item.querySelector('input');
            
            // Confirmar solo si hay texto
            if (input.value.trim() !== '') {
                if (!confirm('¿Eliminar este ejercicio?')) {
                    return;
                }
            }
            
            // Animación de salida
            item.style.opacity = '0';
            item.style.transform = 'translateX(-20px)';
            item.style.transition = 'all 0.3s ease';
            
            setTimeout(() => {
                item.remove();
            }, 300);
        }

        // Prevenir envío accidental con Enter
        document.querySelectorAll('.ejercicio-input').forEach(input => {
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>

</html>