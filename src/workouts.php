<?php

/**
 * workouts.php
 * 
 * Lógica de carga y guardado del archivo JSON con los 84 días de entrenamiento.
 * Versión simplificada sin rutinas predefinidas - gestión 100% dinámica desde la interfaz.
 */

/**
 * Ruta al archivo JSON
 */
function getJsonPath() {
    return __DIR__ . '/../data/workouts.json';
}

/**
 * Crear directorio data/ si no existe
 */
function ensureDataDirExists() {
    $dir = __DIR__ . '/../data';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

/**
 * Verificar si el JSON existe, si no, generarlo
 */
function ensureWorkoutsJsonExists() {
    ensureDataDirExists();
    $path = getJsonPath();
    if (!file_exists($path)) {
        $workouts = generateDefault84Workouts();
        file_put_contents($path, json_encode($workouts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

/**
 * Generar estructura inicial de 84 días vacía
 * Sin rutinas predefinidas - el usuario las añade dinámicamente
 * 
 * @return array
 */
function generateDefault84Workouts() {
    $workouts = [];
    
    for ($i = 1; $i <= 84; $i++) {
        $workouts[$i] = [
            'dia'           => $i,
            'am_ejercicios' => [],  // Array vacío, se llena desde la interfaz
            'pm_ejercicios' => [],  // Array vacío, se llena desde la interfaz
            'realizado'     => false,
            'notas'         => ''
        ];
    }
    
    return $workouts;
}

/**
 * Cargar el JSON de workouts
 * 
 * @return array
 */
function loadWorkouts() {
    $path = getJsonPath();
    if (!file_exists($path)) {
        return [];
    }
    
    $json = file_get_contents($path);
    $data = json_decode($json, true);
    
    return is_array($data) ? $data : [];
}

/**
 * Guardar el array de workouts en el JSON
 * 
 * @param array $workouts
 * @return bool
 */
function saveWorkouts(array $workouts) {
    ensureDataDirExists();
    $path = getJsonPath();
    $json = json_encode($workouts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
    return file_put_contents($path, $json) !== false;
}

/**
 * Determinar la fase según la semana
 * 
 * @param int $week
 * @return string
 */
function faseFromWeek($week) {
    if ($week <= 4) {
        return 'Adaptación';
    } elseif ($week <= 8) {
        return 'Intensificación';
    } else {
        return 'Consolidación';
    }
}

?>