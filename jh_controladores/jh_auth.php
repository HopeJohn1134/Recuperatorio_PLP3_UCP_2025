<?php

session_start();
header('Content-Type: application/json');
require_once '../jh_config/jh_conexion.php';


$jh_input = json_decode(file_get_contents('php://input'), true);
$jh_accion = $jh_input['accion'] ?? '';

$jh_respuesta = ['exito' => false, 'mensaje' => 'Acción no válida'];

if ($jh_accion === 'registro') {
    $jh_usuario = $jh_conexion->real_escape_string($jh_input['usuario']);
    $jh_pass = $jh_input['password'];

    // existe? sino error
    $jh_check = $jh_conexion->query("SELECT id_usuario FROM jh_usuarios WHERE jh_nombre = '$jh_usuario'");

    if ($jh_check->num_rows > 0) {
        $jh_respuesta['mensaje'] = 'El usuario ya existe.';
    } else {
        $jh_sql = "INSERT INTO jh_usuarios (jh_nombre, jh_password) VALUES ('$jh_usuario', '$jh_pass')";
        if ($jh_conexion->query($jh_sql)) {
            $jh_respuesta = ['exito' => true, 'mensaje' => 'Registro exitoso. Inicia sesión.'];
        } else {
            $jh_respuesta['mensaje'] = 'Error en la base de datos.';
        }
    }

} elseif ($jh_accion === 'login') {
    $jh_usuario = $jh_conexion->real_escape_string($jh_input['usuario']);
    $jh_pass = $jh_input['password'];

    $jh_sql = "SELECT id_usuario, jh_nombre FROM jh_usuarios WHERE jh_nombre = '$jh_usuario' AND jh_password = '$jh_pass'";
    $jh_resultado = $jh_conexion->query($jh_sql);

    if ($jh_resultado->num_rows === 1) {
        $jh_fila = $jh_resultado->fetch_assoc();

        // variables de sesion
        $_SESSION['jh_id_usuario'] = $jh_fila['id_usuario'];
        $_SESSION['jh_nombre'] = $jh_fila['jh_nombre'];

        $jh_respuesta = [
            'exito' => true,
            'usuario' => $jh_fila['jh_nombre']
        ];
    } else {
        $jh_respuesta['mensaje'] = 'Usuario o contraseña incorrectos.';
    }

} elseif ($jh_accion === 'logout') {
    session_destroy();
    $jh_respuesta = ['exito' => true, 'mensaje' => 'Sesión cerrada'];
}

echo json_encode($jh_respuesta);
?>