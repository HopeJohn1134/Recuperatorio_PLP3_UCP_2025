# Juego Piedra, Papel o Tijera - Recuperatorio PLP3

**Nombre :** John Hope
**Año:** 2025

## 1. Justificación de Arquitectura y Metodología

Para el desarrollo de este proyecto se optó por una arquitectura basada en el patrón de diseño **MVC (Modelo-Vista-Controlador)** adaptado, priorizando la **Separación de Responsabilidades (Separation of Concerns)**.

### Estructura de Directorios
Se implementó una nomenclatura estricta utilizando el prefijo `jh_` (iniciales del alumno) para facilitar la trazabilidad de los archivos creados.

- **/jh_config:** Aísla la configuración de la base de datos para facilitar la migración entre entornos y mejorar la seguridad.
- **/jh_controladores:** Actúan como intermediarios. Reciben las peticiones asíncronas (AJAX), procesan la lógica de negocio (reglas del juego) y devuelven respuestas en formato JSON.
- **/jh_assets:** Separa los recursos estáticos (CSS, JS) del código del servidor.

### Lógica del Juego (Backend)
Se decidió centralizar la lógica crítica en el **Backend (PHP)**.
- **Seguridad:** Al determinar el ganador en el servidor (`jh_jugar.php`), se evita que el cliente pueda manipular el resultado mediante inyección de código en el navegador.
- **Interactividad:** El Frontend utiliza **JavaScript asíncrono (Fetch API)** para enviar las elecciones y recibir el resultado sin recargar la página (SPA feel), mejorando la experiencia de usuario (UX).

## 2. Base de Datos
Se diseñó un modelo relacional con dos tablas principales:
- `jh_usuarios`: Almacena credenciales.
- `jh_partidas`: Vinculada a usuarios mediante FK, permitiendo generar historiales persistentes y consultas complejas.

## 3. Instalación o Setup
1. Importar el archivo `jh_sql/jh_script_db.sql` en MariaDB.
2. Configurar credenciales en `jh_config/jh_conexion.php`.
3. Ejecutar en servidor local compatible con PHP 8+.