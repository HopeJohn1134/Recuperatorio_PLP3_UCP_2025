<?php

session_start();
header('Content-Type: application/json');
require_once '../jh_config/jh_conexion.php';

// control para la sesion sino hay error
if (!isset($_SESSION['jh_id_usuario'])) {
    echo json_encode(['exito' => false, 'mensaje' => 'No autorizado']);
    exit;
}

$jh_input = json_decode(file_get_contents('php://input'), true);
$jh_accion = $jh_input['accion'] ?? '';
$jh_id_usuario = $_SESSION['jh_id_usuario'];

$jh_respuesta = ['exito' => false];



if ($jh_accion === 'jugar') {
    $jh_eleccion_usuario = $jh_input['eleccion']; // piedra, papel, tijera por click en pantalla

    $jh_opciones = ['piedra', 'papel', 'tijera'];
    $jh_eleccion_cpu = $jh_opciones[rand(0, 2)]; // rand que elije que juega la CPu

    $jh_resultado_partida = 'empate'; // casi me olvido que existen los empates jajajajaja

    if ($jh_eleccion_usuario === $jh_eleccion_cpu) {
        $jh_resultado_partida = 'empate';
    } elseif (
        ($jh_eleccion_usuario === 'piedra' && $jh_eleccion_cpu === 'tijera') ||
        ($jh_eleccion_usuario === 'papel' && $jh_eleccion_cpu === 'piedra') ||
        ($jh_eleccion_usuario === 'tijera' && $jh_eleccion_cpu === 'papel')
    ) {
        $jh_resultado_partida = 'victoria';
    } else {
        $jh_resultado_partida = 'derrota';
    }

    // almacenar en BD
    $jh_stmt = $jh_conexion->prepare("INSERT INTO jh_partidas (id_usuario, jh_eleccion_jugador, jh_eleccion_cpu, jh_resultado) VALUES (?, ?, ?, ?)");
    $jh_stmt->bind_param("isss", $jh_id_usuario, $jh_eleccion_usuario, $jh_eleccion_cpu, $jh_resultado_partida);

    if ($jh_stmt->execute()) {
        $jh_respuesta = [
            'exito' => true,
            'eleccion_cpu' => $jh_eleccion_cpu,
            'resultado' => $jh_resultado_partida
        ];
    } else {
        $jh_respuesta['mensaje'] = 'Error al guardar partida';
    }
} elseif ($jh_accion === 'historial') {
    // extraer las partidas viejas
    $jh_sql = "SELECT jh_fecha, jh_eleccion_jugador, jh_eleccion_cpu, jh_resultado 
               FROM jh_partidas 
               WHERE id_usuario = $jh_id_usuario 
               ORDER BY id_partida DESC LIMIT 10";
    // limito a 10 sino es mucho en pantalla
    $jh_resultado_bd = $jh_conexion->query($jh_sql);
    $jh_historial = [];

    while ($row = $jh_resultado_bd->fetch_assoc()) {
        $jh_historial[] = $row;
    }

    $jh_respuesta = ['exito' => true, 'datos' => $jh_historial];
}

echo json_encode($jh_respuesta);
?>