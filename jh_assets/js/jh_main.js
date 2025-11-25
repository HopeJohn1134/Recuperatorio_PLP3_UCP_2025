
document.addEventListener('DOMContentLoaded', () => {
    
    const jh_seccionLogin = document.getElementById('jh_seccion_login');
    const jh_seccionDashboard = document.getElementById('jh_seccion_dashboard');
    const jh_seccionJuego = document.getElementById('jh_seccion_juego');
    const jh_usuarioInput = document.getElementById('jh_usuario_input');
    const jh_passInput = document.getElementById('jh_password_input');
    const jh_msgAuth = document.getElementById('jh_mensaje_auth');

    // Elementos del Juego
    const jh_tablaCuerpo = document.getElementById('jh_tabla_cuerpo');
    const jh_animacionTexto = document.getElementById('jh_animacion_texto');
    const jh_faseSeleccion = document.getElementById('jh_fase_seleccion');
    const jh_faseResultado = document.getElementById('jh_fase_resultado');
    const jh_resultadoFinalDiv = document.getElementById('jh_resultado_final');
    const jh_cartelResultado = document.getElementById('jh_cartel_resultado');
    
    // Iconos para resultado
    const jh_iconos = {
        'piedra': '🪨',
        'papel': '📄',
        'tijera': '✂️'
    };

    if (!jh_seccionDashboard.classList.contains('jh_oculto')) {
        jh_cargarHistorial();
    }



  
    document.getElementById('jh_btn_login').addEventListener('click', () => {
        jh_autenticacion('login');
    });

    document.getElementById('jh_btn_registro').addEventListener('click', () => {
        jh_autenticacion('registro');
    });

    document.getElementById('jh_btn_logout').addEventListener('click', async () => {
        await fetch('jh_controladores/jh_auth.php', {
            method: 'POST',
            body: JSON.stringify({ accion: 'logout' })
        });
        location.reload();// reset
    });

    //Iniciar un juego
    document.getElementById('jh_btn_iniciar_juego').addEventListener('click', () => {
        jh_cambiarVista('juego');
    });

    //botones de seleccion
    document.querySelectorAll('.jh_opcion').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const jh_eleccion = e.target.closest('button').dataset.eleccion;
            jh_iniciarRonda(jh_eleccion);
        });
    });

    // volver a la otra vista
    document.getElementById('jh_btn_volver').addEventListener('click', () => {
        jh_cambiarVista('dashboard');
        jh_cargarHistorial(); // Refrescar tabla
        
        //vista de juego
        jh_faseSeleccion.classList.remove('jh_oculto');
        jh_faseResultado.classList.add('jh_oculto');
        jh_resultadoFinalDiv.classList.add('jh_oculto');
        jh_animacionTexto.style.display = 'block';
        jh_animacionTexto.textContent = '...';
    });


    async function jh_autenticacion(tipo) {
        const usuario = jh_usuarioInput.value;
        const password = jh_passInput.value;

        if (!usuario || !password) {
            jh_msgAuth.textContent = "Por favor completa los campos.";
            jh_msgAuth.style.color = "red";
            return;
        }

        try {
            const respuesta = await fetch('jh_controladores/jh_auth.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ accion: tipo, usuario, password })
            });
            
            const datos = await respuesta.json();

            if (datos.exito) {
                if (tipo === 'registro') {
                    jh_msgAuth.textContent = "Registro exitoso. Ahora inicia sesión.";
                    jh_msgAuth.style.color = "#4caf50";
                } else {
                    location.reload(); // /para mostrar dashboard
                }
            } else {
                jh_msgAuth.textContent = datos.mensaje;
                jh_msgAuth.style.color = "red";
            }
        } catch (error) {
            console.error(error);
            jh_msgAuth.textContent = "Error de conexión.";
        }
    }

    async function jh_cargarHistorial() {// el dashboard podriamos decir
        try {
            const res = await fetch('jh_controladores/jh_jugar.php', {
                method: 'POST',
                body: JSON.stringify({ accion: 'historial' })
            });
            const data = await res.json();
            
            jh_tablaCuerpo.innerHTML = ''; // reset de la paguina

            if (data.exito && data.datos.length > 0) {
                data.datos.forEach(partida => {
                    const fila = `
                        <tr>
                            <td>${partida.jh_fecha}</td>
                            <td>${jh_iconos[partida.jh_eleccion_jugador]}</td>
                            <td>${jh_iconos[partida.jh_eleccion_cpu]}</td>
                            <td><b style="color: ${jh_getColorResultado(partida.jh_resultado)}">${partida.jh_resultado.toUpperCase()}</b></td>
                        </tr>
                    `;
                    jh_tablaCuerpo.innerHTML += fila;
                });
            } else {
                jh_tablaCuerpo.innerHTML = '<tr><td colspan="4">No hay partidas aún.</td></tr>';
            }
        } catch (e) {
            console.error("Error cargando historial", e);
        }
    }

    // Juego y animaciones
    async function jh_iniciarRonda(eleccionJugador) {
        jh_faseSeleccion.classList.add('jh_oculto');// oculta el container
        jh_faseResultado.classList.remove('jh_oculto');
        
        let datosJuego = null;
        const peticionJuego = fetch('jh_controladores/jh_jugar.php', {
            method: 'POST',
            body: JSON.stringify({ accion: 'jugar', eleccion: eleccionJugador })
        }).then(r => r.json());

 
        const secuencia = ["PIEDRA", "PAPEL", "TIJERA"];
        
        for (let palabra of secuencia) {// para mas intensidad jajaja
            jh_animacionTexto.textContent = palabra;
            await new Promise(r => setTimeout(r, 800));// pequeña pausa
        }

        try {
            datosJuego = await peticionJuego; // esto resuevelo de la carga de paguinas
            
            if (datosJuego.exito) {
                jh_animacionTexto.style.display = 'none'; // animaciones para el texto
                jh_resultadoFinalDiv.classList.remove('jh_oculto');
                document.getElementById('jh_icono_jugador').textContent = jh_iconos[eleccionJugador];// iconos de cpu y jugador
                document.getElementById('jh_icono_cpu').textContent = jh_iconos[datosJuego.eleccion_cpu];// porque justo los tengo a mano
                
                const resTexto = datosJuego.resultado.toUpperCase();
                jh_cartelResultado.textContent = resTexto;
                jh_cartelResultado.style.color = jh_getColorResultado(datosJuego.resultado);

            } else {
                alert("Error al procesar la partida");
                location.reload();
            }

        } catch (e) {
            console.error(e);
            alert("Error de conexión con el servidor");
        }
    }

    // manejo de vistas
    function jh_cambiarVista(vista) {
        if (vista === 'juego') {
            jh_seccionDashboard.classList.add('jh_oculto');
            jh_seccionJuego.classList.remove('jh_oculto');
            //dashboard y juego
        } else {
            jh_seccionJuego.classList.add('jh_oculto');
            jh_seccionDashboard.classList.remove('jh_oculto');
        }
    }

    function jh_getColorResultado(resultado) {
        if (resultado === 'victoria') return '#4caf50';
        if (resultado === 'derrota') return '#e94560';
        return '#ff9800'; // en caso de empate que casi no pongo jejej
        //la proxima devería tener un enum o un cath de execciones, esto me dio demasiados dramas ya
    }

});