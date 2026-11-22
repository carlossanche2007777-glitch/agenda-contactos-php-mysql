-- Importar en la base que hayas creado desde el panel del hosting.
-- En InfinityFree NO ejecutar CREATE DATABASE ni USE: el panel asigna el nombre.
-- Selecciona la base correcta en phpMyAdmin y luego ejecuta este archivo.

CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(190) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS contactos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT UNSIGNED NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    telefono VARCHAR(25) NOT NULL,
    correo VARCHAR(190) NOT NULL,
    ciudad VARCHAR(100) NOT NULL,
    favorito TINYINT(1) NOT NULL DEFAULT 0,
    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_contactos_usuario (id_usuario),
    INDEX idx_contactos_usuario_nombre (id_usuario, nombre),
    INDEX idx_contactos_usuario_ciudad (id_usuario, ciudad),
    INDEX idx_contactos_usuario_favorito (id_usuario, favorito),
    INDEX idx_contactos_usuario_fecha (id_usuario, fecha_creacion),
    CONSTRAINT fk_contactos_usuarios FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;
