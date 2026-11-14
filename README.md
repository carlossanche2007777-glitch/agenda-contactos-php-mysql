# Agenda Personal de Contactos – PHP y MySQL

## 1. Descripción del proyecto

La Agenda Personal de Contactos es una aplicación web desarrollada en PHP y MySQL que permite registrar usuarios, iniciar sesión y administrar una agenda personal.

Cada usuario puede agregar, consultar, editar y eliminar sus propios contactos. El sistema utiliza mecanismos de autenticación y sesiones para proteger la información.

## 2. Tecnologías utilizadas

- PHP: desarrollo de las funciones del sistema.
- MySQL: almacenamiento de usuarios y contactos.
- HTML y CSS: diseño de la interfaz.
- XAMPP: servidor local con Apache y MySQL.
- phpMyAdmin: administración de la base de datos.
- GitHub: almacenamiento del código fuente.

## 3. Requisitos para ejecutar la aplicación

- Computadora con Windows.
- XAMPP instalado.
- Apache y MySQL activados.
- Navegador web.
- PHP compatible con las funciones PDO y de manejo de contraseñas.

## 4. Pasos para instalar la aplicación

1. Descargar el repositorio de GitHub.
2. Descomprimir el proyecto.
3. Copiar la carpeta `agenda_contactos` dentro de `C:\xampp\htdocs`.
4. Abrir XAMPP e iniciar Apache y MySQL.
5. Entrar a `http://localhost/phpmyadmin`.
6. Importar el archivo `agenda_contactos/sql/agenda_contactos.sql`.
7. Revisar la configuración de conexión en `config/database.php` y ajustarla si es necesario.
8. Abrir `http://localhost/agenda_contactos/`.
9. Registrar un usuario e iniciar sesión.

## 5. Funcionalidades principales

- Registro de usuarios.
- Inicio y cierre de sesión.
- Protección de contraseñas.
- Agregar contactos.
- Consultar contactos.
- Editar contactos.
- Eliminar contactos con confirmación.
- Restricción de acceso a contactos de otros usuarios.

## 6. Seguridad

La aplicación utiliza `password_hash()` y `password_verify()` para proteger y comprobar las contraseñas. También emplea sesiones PHP y consultas preparadas para gestionar el acceso a los datos.

Los contactos están asociados a su usuario propietario mediante el campo `id_usuario`.

## 7. Base de datos

La base de datos se denomina `agenda_contactos` y contiene dos tablas principales:

- `usuarios`: información de las cuentas registradas.
- `contactos`: información de los contactos y su usuario propietario.

Ambas tablas se relacionan mediante el campo `id_usuario`.
