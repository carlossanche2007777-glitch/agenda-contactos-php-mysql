
# Agenda Personal de Contactos V2 – PHP y MySQL

## 1. Descripción del proyecto

La Agenda Personal de Contactos es una aplicación web desarrollada con PHP y MySQL que permite registrar usuarios, iniciar sesión y administrar contactos personales.

Cada usuario puede agregar, consultar, editar y eliminar sus propios contactos. El sistema incorpora autenticación y sesiones para proteger la información.

En esta segunda versión se implementaron nuevas funcionalidades y se publicó la aplicación en un hosting gratuito.

## 2. URL pública del sistema

La aplicación está disponible en:

http://agenda-contactos-carlos.gamer.free/

Hosting utilizado: InfinityFree.

## 3. Mejoras implementadas en la versión V2

### Mejora A: Búsqueda de contactos
Permite buscar contactos por nombre, correo electrónico o ciudad.

### Mejora B: Ordenamiento de contactos
Permite ordenar los registros por:
- Nombre (A-Z).
- Nombre (Z-A).
- Ciudad.
- Fecha de creación.

### Mejora D: Contactos favoritos
Permite marcar y desmarcar contactos favoritos mediante una estrella.

También incorpora un filtro para mostrar únicamente los contactos favoritos.

## 4. Funcionalidades del sistema

- Registro de usuarios.
- Inicio y cierre de sesión.
- Registro de contactos.
- Visualización de contactos personales.
- Edición y eliminación de contactos.
- Búsqueda por nombre, correo y ciudad.
- Ordenamiento de contactos.
- Gestión de favoritos.
- Separación de contactos por usuario.

## 5. Tecnologías utilizadas

- PHP: desarrollo de la aplicación.
- MySQL/MariaDB: almacenamiento de información.
- HTML y CSS: diseño de la interfaz.
- Apache: servidor web.
- XAMPP: entorno de desarrollo local.
- phpMyAdmin: administración de bases de datos.
- InfinityFree: alojamiento web gratuito.
- GitHub: repositorio y control de versiones.

## 6. Instalación local

1. Instalar XAMPP.
2. Descargar o clonar este repositorio.
3. Copiar la carpeta `agenda_contactos` dentro de `C:\xampp\htdocs`.
4. Iniciar Apache y MySQL desde XAMPP.
5. Abrir `http://localhost/phpmyadmin/`.
6. Crear una base de datos llamada `agenda_contactos`.
7. Importar el archivo `agenda_contactos/sql/hosting_tablas.sql`.
8. Copiar `config/local.example.php` como `config/local.php`.
9. Configurar en `local.php` el host, puerto, nombre de la base, usuario y contraseña locales.
10. Abrir `http://localhost/agenda_contactos/`.
11. Registrar un usuario y comenzar a utilizar la agenda.

No se debe publicar `config/local.php`, porque contiene credenciales privadas.

## 7. Scripts SQL

Dentro de `agenda_contactos/sql` se incluyen:

- `hosting_tablas.sql`: estructura de tablas para una base de datos nueva.
- `migracion_v2.sql`: actualización de una instalación anterior.
- `agenda_contactos.sql`: script de la versión original.

La versión V2 incorpora los campos `favorito` y `fecha_creacion` en la tabla de contactos.

## 8. Despliegue en InfinityFree

Para publicar el proyecto se realizaron las siguientes actividades:

1. Creación de una cuenta de hosting gratuito.
2. Registro de un subdominio público.
3. Creación de una base de datos MySQL.
4. Importación del script `hosting_tablas.sql`.
5. Subida de los archivos PHP a la carpeta `htdocs`.
6. Configuración privada de la conexión a MySQL.
7. Pruebas del registro, inicio de sesión y administración de contactos.
8. Verificación de las mejoras desde Internet.

## 9. Capturas de pantalla

Las evidencias de funcionamiento se incorporarán a la carpeta `agenda_contactos/docs`:

- Pantalla de inicio de sesión.
- Panel principal de contactos.
- Búsqueda de contactos.
- Ordenamiento de registros.
- Filtro de contactos favoritos.

Las capturas utilizarán datos ficticios para proteger información personal.

## 10. Conclusiones

La actualización de la Agenda Personal de Contactos permitió mejorar la experiencia del usuario mediante herramientas de búsqueda, ordenamiento y gestión de favoritos.

La publicación en InfinityFree permitió comprobar el funcionamiento de la aplicación mediante una dirección pública, utilizando PHP y una base de datos MySQL alojada en Internet.

El proyecto demuestra la implementación, actualización y despliegue de una aplicación web dinámica.

