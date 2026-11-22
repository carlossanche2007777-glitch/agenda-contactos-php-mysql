<?php
declare(strict_types=1);

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    // config/local.php se crea solo en el servidor y NO se sube a GitHub.
    // En XAMPP pueden utilizarse las variables de entorno o los valores locales.
    $local = [];
    if (is_file(__DIR__ . '/local.php')) {
        $local = require __DIR__ . '/local.php';
        if (!is_array($local)) $local = [];
    }
    $host = (string)($local['host'] ?? (getenv('DB_HOST') ?: '127.0.0.1'));
    $port = (string)($local['port'] ?? (getenv('DB_PORT') ?: '3306'));
    $name = (string)($local['name'] ?? (getenv('DB_NAME') ?: 'agenda_contactos'));
    $user = (string)($local['user'] ?? (getenv('DB_USER') ?: 'root'));
    $pass = (string)($local['pass'] ?? (getenv('DB_PASS') ?: ''));
    $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        error_log('Database connection error: ' . $e->getMessage());
        http_response_code(500);
        exit('No se pudo conectar con la base de datos. Revisa la configuración.');
    }
}
