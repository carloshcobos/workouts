<?php
// src/workouts.php

function getWorkoutsJsonPath(): string {
     return dirname(__DIR__) . DIRECTORY_SEPARATOR . 'workouts.json';
}


function ensureWorkoutsJsonExists(): void {
    $jsonFile = getWorkoutsJsonPath();
    if (!file_exists($jsonFile)) {
        $workouts = buildInitialWorkouts();
        file_put_contents(
            $jsonFile,
            json_encode($workouts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }
}

/**
 * Carga todos los días del JSON
 *
 * @return array // esta @ es para que no de warning en PHP 8.1+, ya que json_decode puede devolver null
 */

function loadWorkouts(): array {
    $jsonFile = getWorkoutsJsonPath();
    if (!file_exists($jsonFile)) {
        ensureWorkoutsJsonExists();
    }

    $data = json_decode(file_get_contents($jsonFile), true);
    return is_array($data) ? $data : [];
}

/**
 * Guarda el array completo en el JSON
 */
function saveWorkouts(array $workouts): void {
    $jsonFile = getWorkoutsJsonPath();
    $encoded = json_encode($workouts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    if($encoded === false){
        // Depurar
        echo "<pre> Error al codificar JSON: " . json_last_error_msg() . "</pre>";
        echo "<pre> Archivo JSON: " . htmlspecialchars($jsonFile) . "</pre>";
        exit;
    }

    $bytes = @file_put_contents($jsonFile, $encoded, LOCK_EX);

    if ($bytes === false) {
        // Depurar
        echo "<pre> Error al escribir en el archivo JSON: " . htmlspecialchars($jsonFile) . "</pre>";
        echo "<pre> Existe el archivo? " . (file_exists($jsonFile) ? 'Sí' : 'No') . "</pre>";
        echo "<pre> Directorio con permisos para escribir? " . (is_writable(dirname($jsonFile)) ? 'Sí' : 'No') . "</pre>";
        exit;
    }
}

/**
 * Devuelve el array inicial de 84 días, con AM/PM base ya rellenado
 */

function buildInitialWorkouts(): array {
    // Aquí pegamos el gran array $plan de días 1..84
    $plan = [
        1  => ['am' => 'Caminata 30 min FC 110–120',               'pm' => 'Movilidad global 20 min + estiramientos 10 min'],
        2  => ['am' => 'Elíptica/bici 35 min suave',               'pm' => 'Fuerza A (ligera)'],
        3  => ['am' => 'Caminata 40 min',                          'pm' => 'Movilidad escapular + core 20 min'],
        4  => ['am' => 'Caminata 35 min',                          'pm' => 'Fuerza B'],
        5  => ['am' => 'Elíptica 30 min',                          'pm' => 'Movilidad + respiración 20 min'],
        6  => ['am' => 'Caminata 45 min',                          'pm' => 'Fuerza A (igual día 2)'],
        7  => ['am' => 'Caminata suave 30 min',                    'pm' => 'Solo estiramientos 20 min (descanso activo)'],

        8  => ['am' => '40 min caminata',                          'pm' => 'Movilidad 20 min'],
        9  => ['am' => '35 min bici',                              'pm' => 'Fuerza B'],
        10 => ['am' => '45 min caminata',                          'pm' => 'Core + escapular 25 min'],
        11 => ['am' => '40 min elíptica',                          'pm' => 'Fuerza A'],
        12 => ['am' => '35–40 min caminata',                       'pm' => 'Movilidad 20 min'],
        13 => ['am' => '50 min caminata',                          'pm' => 'Fuerza B'],
        14 => ['am' => '30 min suave',                             'pm' => 'Estiramientos 20 min'],

        15 => ['am' => '45 min caminata',                          'pm' => 'Movilidad'],
        16 => ['am' => '40 min bici',                              'pm' => 'Fuerza A (añadir 1 serie extra → 4×12)'],
        17 => ['am' => '50 min caminata',                          'pm' => 'Core + movilidad 25 min'],
        18 => ['am' => '45 min elíptica',                          'pm' => 'Fuerza B (4×12)'],
        19 => ['am' => '45 min caminata',                          'pm' => 'Movilidad 20 min'],
        20 => ['am' => '55 min caminata',                          'pm' => 'Fuerza A'],
        21 => ['am' => '35 min suave',                             'pm' => 'Estiramientos 20–25 min'],

        22 => ['am' => '50 min caminata',                          'pm' => 'Movilidad'],
        23 => ['am' => '45 min bici',                              'pm' => 'Fuerza B'],
        24 => ['am' => '55 min caminata',                          'pm' => 'Core + movilidad'],
        25 => ['am' => '50 min elíptica',                          'pm' => 'Fuerza A'],
        26 => ['am' => '50 min caminata',                          'pm' => 'Movilidad'],
        27 => ['am' => '60 min caminata',                          'pm' => 'Fuerza B'],
        28 => ['am' => 'Caminata suave 40 min',                    'pm' => 'Estiramientos + respiración 20 min'],

        // SEMANAS 5–8 – PROGRESIÓN REAL
        29 => ['am' => '55 min caminata rápida FC 118–130',        'pm' => 'Fuerza A (4×12)'],
        30 => ['am' => '50 min elíptica',                          'pm' => 'Core 20 min + movilidad 10 min'],
        31 => ['am' => '60 min caminata',                          'pm' => 'Fuerza B (4×12)'],
        32 => ['am' => '55 min bici',                              'pm' => 'Movilidad 25 min'],
        33 => ['am' => '60 min caminata rápida',                   'pm' => 'Fuerza A'],
        34 => ['am' => '50 min elíptica',                          'pm' => 'Core 25 min'],
        35 => ['am' => '40 min suave',                             'pm' => 'Estiramientos'],

        36 => ['am' => '60 min caminata',                          'pm' => 'Fuerza B'],
        37 => ['am' => '55 min bici',                              'pm' => 'Movilidad'],
        38 => ['am' => '60 min caminata',                          'pm' => 'Fuerza A'],
        39 => ['am' => '50 min elíptica',                          'pm' => 'Core'],
        40 => ['am' => '65 min caminata',                          'pm' => 'Fuerza B'],
        41 => ['am' => '55 min bici',                              'pm' => 'Movilidad 20 min'],
        42 => ['am' => '45 min suave',                             'pm' => 'Estiramientos'],

        43 => ['am' => '60 min caminata rápida',                   'pm' => 'Fuerza A (progresión ligera)'],
        44 => ['am' => '55 min elíptica',                          'pm' => 'Core + movilidad 25 min'],
        45 => ['am' => '65 min caminata',                          'pm' => 'Fuerza B'],
        46 => ['am' => '55 min bici',                              'pm' => 'Movilidad'],
        47 => ['am' => '65 min caminata',                          'pm' => 'Fuerza A'],
        48 => ['am' => '55 min elíptica',                          'pm' => 'Core'],
        49 => ['am' => '45 min suave',                             'pm' => 'Estiramientos'],

        50 => ['am' => '65 min caminata',                          'pm' => 'Fuerza B'],
        51 => ['am' => '55 min bici',                              'pm' => 'Movilidad'],
        52 => ['am' => '70 min caminata',                          'pm' => 'Fuerza A'],
        53 => ['am' => '60 min elíptica',                          'pm' => 'Core'],
        54 => ['am' => '65 min caminata',                          'pm' => 'Fuerza B'],
        55 => ['am' => '55 min bici',                              'pm' => 'Movilidad'],
        56 => ['am' => '45 min suave',                             'pm' => 'Estiramientos'],

        // SEMANAS 9–12 – CONSOLIDACIÓN
        57 => ['am' => '70 min caminata rápida FC 120–135',        'pm' => 'Fuerza A (5×10)'],
        58 => ['am' => '60 min elíptica',                          'pm' => 'Core + movilidad'],
        59 => ['am' => '70 min caminata',                          'pm' => 'Fuerza B'],
        60 => ['am' => '60 min bici',                              'pm' => 'Movilidad'],
        61 => ['am' => '75 min caminata',                          'pm' => 'Fuerza A'],
        62 => ['am' => '60 min elíptica',                          'pm' => 'Core'],
        63 => ['am' => '50 min suave',                             'pm' => 'Estiramientos'],

        64 => ['am' => '70 min caminata',                          'pm' => 'Fuerza B'],
        65 => ['am' => '60 min bici',                              'pm' => 'Movilidad'],
        66 => ['am' => '75 min caminata',                          'pm' => 'Fuerza A'],
        67 => ['am' => '60 min elíptica',                          'pm' => 'Core'],
        68 => ['am' => '75 min caminata rápida',                   'pm' => 'Fuerza B'],
        69 => ['am' => '60 min bici',                              'pm' => 'Movilidad'],
        70 => ['am' => '50 min suave',                             'pm' => 'Estiramientos'],

        71 => ['am' => '75 min caminata',                          'pm' => 'Fuerza A'],
        72 => ['am' => '60 min elíptica',                          'pm' => 'Core'],
        73 => ['am' => '75 min caminata',                          'pm' => 'Fuerza B'],
        74 => ['am' => '60 min bici',                              'pm' => 'Movilidad'],
        75 => ['am' => '80 min caminata',                          'pm' => 'Fuerza A'],
        76 => ['am' => '60 min elíptica',                          'pm' => 'Core'],
        77 => ['am' => '50 min suave',                             'pm' => 'Estiramientos'],

        78 => ['am' => '80 min caminata',                          'pm' => 'Fuerza B'],
        79 => ['am' => '60 min bici',                              'pm' => 'Movilidad'],
        80 => ['am' => '80 min caminata',                          'pm' => 'Fuerza A'],
        81 => ['am' => '60 min elíptica',                          'pm' => 'Core'],
        82 => ['am' => '85 min caminata',                          'pm' => 'Fuerza B'],
        83 => ['am' => '60 min bici',                              'pm' => 'Movilidad'],
        84 => ['am' => '45 min suave',                             'pm' => 'Estiramientos + respiración (cierre del ciclo)'],
    ];

    $workouts = [];
    for ($dia = 1; $dia <= 84; $dia++) {
        $am = $plan[$dia]['am'] ?? '';
        $pm = $plan[$dia]['pm'] ?? '';
        // Usar la clave $dia para que se pueda acceder por número de día: $workouts[5], etc.
        $workouts[$dia] = [
            'dia'            => $dia,
            'am_base'        => $am,
            'pm_base'        => $pm,
            'am_ejercicios'  => [],
            'pm_ejercicios'  => [],
            'realizado'      => false,
            'notas'          => '',
        ];
    }

    return $workouts;
}

/**
 * A partir del número de semana devuelve la fase
 */
function faseFromWeek(int $week): string {
    if ($week >= 1 && $week <= 4) {
        return 'REACTIVACIÓN';
    }
    if ($week >= 5 && $week <= 8) {
        return 'PROGRESIÓN REAL';
    }
    return 'CONSOLIDACIÓN';
}
