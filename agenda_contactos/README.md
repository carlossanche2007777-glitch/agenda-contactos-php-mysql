# Agenda Personal de Contactos

Aplicación web académica desarrollada con **PHP 8.1+ y MySQL/MariaDB**. Permite crear una cuenta, ingresar al sistema y administrar contactos privados. Cada usuario únicamente puede ver, editar y eliminar sus propios registros.

## Tecnologías
- PHP 8.1+ (sesiones, PDO, `password_hash()` y `password_verify()`).
- MySQL 5.7+/8.0 o MariaDB compatible, motor InnoDB.
- HTML5 y CSS3; JavaScript nativo solo para confirmar la eliminación.
- XAMPP (Apache, PHP, MySQL) para la ejecución local.

## Requisitos
- XAMPP con Apache y MySQL encendidos, o entorno equivalente.
- PHP con extensiones `pdo_mysql` y `mbstring` habilitadas.
- Navegador web moderno.

## Instalación con XAMPP (Windows)
1. Copia la carpeta `agenda_contactos` a `C:\\xampp\\htdocs\\`.
2. Abre el panel de XAMPP y activa **Apache** y **MySQL**.
3. Visita `http://localhost/phpmyadmin`.
4. Selecciona **Importar**, carga `sql/agenda_contactos.sql` y pulsa **Continuar**. Se crearán la base `agenda_contactos` y sus dos tablas.
5. La conexión predeterminada es `127.0.0.1`, puerto `3306`, usuario `root`, contraseña vacía y base `agenda_contactos` (configuración típica de XAMPP local). Si tu MySQL usa otra configuración, define las variables de entorno `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS` antes de iniciar Apache; no publiques contraseñas reales en GitHub.
6. Abre `http://localhost/agenda_contactos/`.
7. Registra un usuario e inicia sesión. Ya puedes crear, consultar, editar y eliminar contactos.

> **Nota:** no abras los archivos PHP directamente (doble clic); ejecútalos a través de Apache. El proyecto no incluye usuarios precargados: regístrate desde el formulario.

## Estructura del proyecto
```text
agenda_contactos/
├── assets/style.css             # Apariencia
├── config/database.php          # Conexión PDO con MySQL
├── includes/bootstrap.php       # Sesiones, CSRF, validaciones y helpers
├── includes/header.php          # Encabezado y menú
├── includes/footer.php          # Pie de página
├── includes/contact_fields.php  # Campos comunes de contacto
├── sql/agenda_contactos.sql     # Script SQL
├── index.php                    # Redirige a login o panel
├── register.php                 # Registro de usuarios
├── login.php                    # Inicio de sesión
├── logout.php                   # Cierre seguro de sesión
├── dashboard.php                # Panel privado y listado de contactos
├── contact_create.php           # Crear contacto
├── contact_edit.php             # Editar contacto propio
├── contact_delete.php           # Eliminar contacto propio
└── README.md                    # Este documento
```

## Tablas y relación
- **usuarios**: `id` (PK), `nombre`, `correo` (UNIQUE), `password` (hash), `fecha_registro`.
- **contactos**: `id` (PK), `id_usuario` (FK), `nombre`, `telefono`, `correo`, `ciudad`.
- Relación **1 a muchos**: un usuario puede tener varios contactos; cada contacto pertenece a un único usuario.

## Seguridad implementada
1. `password_hash()` almacena hashes, nunca contraseñas en texto plano.
2. `password_verify()` comprueba las credenciales al ingresar.
3. Sesiones activas obligatorias en todas las páginas privadas y regeneración del ID tras el inicio de sesión.
4. Validación en servidor: nombre, teléfono, correo y ciudad; email único al registrar.
5. PDO con consultas preparadas evita interpolar entradas del usuario en SQL.
6. Listado, edición y eliminación restringidos por `id_usuario = $_SESSION['user_id']`, incluso si se cambia manualmente el ID en la URL.
7. Formularios de modificación protegidos por token CSRF; eliminación solo por POST y con confirmación del navegador.
8. Salida HTML escapada con `htmlspecialchars()` y mensajes de error sin filtrar información sensible.

## Pruebas sugeridas (realizarlas localmente)
1. Registrar el usuario A con un correo válido; intentar registrar de nuevo el mismo correo (debe fallar).
2. Intentar ingresar con contraseña errónea (debe mostrar error); ingresar con la correcta.
3. Crear un contacto, editar su ciudad, verificar el cambio y eliminarlo tras la confirmación.
4. Cerrar sesión y abrir `dashboard.php` (debe redirigir a login).
5. Crear un usuario B, agregar contactos distintos. Con B autenticado, intentar `contact_edit.php?id=ID_DE_UN_CONTACTO_DE_A` (debe mostrar “Contacto no encontrado”). Verificar que la lista solo contiene contactos de B.
6. Intentar enviar `contact_delete.php` con un ID de A mientras está autenticado B (no debe eliminarlo).

## Publicar el repositorio en GitHub
1. Crea un repositorio vacío en GitHub (por ejemplo, `agenda-contactos-php`).
2. En la terminal, dentro de la carpeta de este proyecto, ejecuta:
```bash
git init
git add .
git commit -m "Proyecto agenda de contactos en PHP y MySQL"
git branch -M main
git remote add origin https://github.com/TU_USUARIO/agenda-contactos-php.git
git push -u origin main
```
3. Cambia `TU_USUARIO` por tu usuario real y entrega la URL del repositorio junto con la carpeta del proyecto y el archivo SQL.

## Consideraciones
Proyecto de demostración educativa para instalación local. Para un despliegue público se recomienda agregar HTTPS obligatorio, limitación de intentos de inicio de sesión, configuración de secretos y copias de seguridad.
