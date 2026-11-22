-- MIGRACIÓN DE UNA SOLA VEZ para la versión ORIGINAL (tablas usuarios/contactos).
-- En phpMyAdmin, selecciona primero la base agenda_contactos.
-- Realiza una copia de seguridad antes de usarlo.
-- NO vuelvas a ejecutar estas instrucciones si los campos ya existen.
-- La fecha_creacion de contactos anteriores se fijará al momento de migrar,
-- puesto que la versión original no registraba la fecha de creación.

ALTER TABLE contactos
    ADD COLUMN favorito TINYINT(1) NOT NULL DEFAULT 0,
    ADD COLUMN fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;

CREATE INDEX idx_contactos_usuario_nombre ON contactos (id_usuario, nombre);
CREATE INDEX idx_contactos_usuario_ciudad ON contactos (id_usuario, ciudad);
CREATE INDEX idx_contactos_usuario_favorito ON contactos (id_usuario, favorito);
CREATE INDEX idx_contactos_usuario_fecha ON contactos (id_usuario, fecha_creacion);

-- Verificación:
SHOW COLUMNS FROM contactos;
