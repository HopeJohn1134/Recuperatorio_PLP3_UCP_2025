CREATE DATABASE Piedra_papel_tijera_PLP3;
USE Piedra_papel_tijera_PLP3;
CREATE TABLE
    IF NOT EXISTS jh_usuarios (id_usuario INT AUTO_INCREMENT PRIMARY KEY, jh_nombre VARCHAR(50) NOT NULL UNIQUE, jh_password VARCHAR(255) NOT NULL, jh_fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP) ENGINE = InnoDB;
CREATE TABLE
    IF NOT EXISTS jh_partidas (id_partida INT AUTO_INCREMENT PRIMARY KEY, id_usuario INT NOT NULL, jh_eleccion_jugador ENUM ('piedra', 'papel', 'tijera') NOT NULL, jh_eleccion_cpu ENUM ('piedra', 'papel', 'tijera') NOT NULL, jh_resultado ENUM ('victoria', 'derrota', 'empate') NOT NULL, jh_fecha DATETIME DEFAULT CURRENT_TIMESTAMP, CONSTRAINT fk_jh_usuario FOREIGN KEY (id_usuario) REFERENCES jh_usuarios (id_usuario) ON DELETE CASCADE) ENGINE = InnoDB;
INSERT INTO
    jh_usuarios (jh_nombre, jh_password)
VALUES
    ('admin', '1234');