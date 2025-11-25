<?php
$host = "localhost";
$user = "admin01";
$pass = "admin01";
$db = "Piedra_papel_tijera_PLP3";

$jh_conexion = new mysqli($host, $user, $pass, $db);

if ($jh_conexion->connect_error) {
    die("Error de conexión: " . $jh_conexion->connect_error);
}

$jh_conexion->set_charset("utf8");// las tildessss y los iconos
?>