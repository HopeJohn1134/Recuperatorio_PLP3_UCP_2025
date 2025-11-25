<?php
//php revisar si funciona la bd y iniciar sesion
session_start();
$jh_esta_logueado = isset($_SESSION['jh_id_usuario']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Piedra, Papel o Tijera - John Hope</title>
    <link rel="stylesheet" href="jh_assets/css/jh_estilos.css">
</head>
<body>

    <main class="jh_contenedor_principal">

        <section id="jh_seccion_login" class="<?php echo $jh_esta_logueado ? 'jh_oculto' : ''; ?>">
            <div class="jh_card">
                <h1 class="jh_titulo">Bienvenido</h1>
                <p>Ingresa para jugar</p>
                <div class="jh_form_grupo">
                    <input type="text" id="jh_usuario_input" placeholder="Usuario" class="jh_input">
                    <input type="password" id="jh_password_input" placeholder="Contraseña"
                        class="jh_input">
                </div>
                <div class="jh_botones_grupo">
                    <button id="jh_btn_login" class="jh_boton jh_boton_primary">Iniciar
                        Sesión</button>
                    <button id="jh_btn_registro"
                        class="jh_boton jh_boton_secondary">Registrarse</button>
                </div>
                <p id="jh_mensaje_auth" class="jh_mensaje"></p>
            </div>
        </section>

        <section id="jh_seccion_dashboard"
            class="<?php echo $jh_esta_logueado ? '' : 'jh_oculto'; ?>">
            <div class="jh_header">
                <h2>Hola, <span
                        id="jh_nombre_usuario_display"><?php echo $_SESSION['jh_nombre'] ?? 'Jugador'; ?></span>
                </h2>
                <button id="jh_btn_logout" class="jh_boton jh_boton_danger">Salir</button>
            </div>

            <div class="jh_card_tablero">
                <h3>Últimas 10 Partidas</h3>
                <div class="jh_tabla_container">
                    <table class="jh_tabla">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Tú</th>
                                <th>CPU</th>
                                <th>Resultado</th>
                            </tr>
                        </thead>
                        <tbody id="jh_tabla_cuerpo">
                        </tbody>
                    </table>
                </div>
                <button id="jh_btn_iniciar_juego" class="jh_boton jh_boton_grande">¡JUGAR!</button>
            </div>
        </section>

        <section id="jh_seccion_juego" class="jh_oculto">
            <div class="jh_juego_pantalla">

                <div id="jh_fase_seleccion">
                    <h2>Elige tu arma</h2>
                    <div class="jh_opciones_juego">
                        <button class="jh_opcion" data-eleccion="piedra">Piedra</button>
                        <button class="jh_opcion" data-eleccion="papel">Papel</button>
                        <button class="jh_opcion" data-eleccion="tijera">Tijera</button>
                    </div>
                </div>

                <div id="jh_fase_resultado" class="jh_oculto">
                    <div id="jh_animacion_texto" class="jh_texto_gigante">...</div>

                    <div id="jh_resultado_final" class="jh_oculto">
                        <div class="jh_versus">
                            <div class="jh_lado">
                                <span class="jh_etiqueta">Tú</span>
                                <span id="jh_icono_jugador" class="jh_icono_grande"></span>
                            </div>
                            <div class="jh_vs">VS</div>
                            <div class="jh_lado">
                                <span class="jh_etiqueta">CPU</span>
                                <span id="jh_icono_cpu" class="jh_icono_grande"></span>
                            </div>
                        </div>
                        <h1 id="jh_cartel_resultado" class="jh_titulo_resultado"></h1>
                        <button id="jh_btn_volver" class="jh_boton jh_boton_primary">Volver al
                            Tablero</button>
                    </div>
                </div>

            </div>
        </section>

    </main>

    <script src="jh_assets/js/jh_main.js"></script>
</body>
</html>