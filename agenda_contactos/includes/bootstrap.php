<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_name('agenda_session');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

require_once __DIR__ . '/../config/database.php';

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $path): never
{
    header('Location: ' . $path, true, 303);
    exit;
}

function logged_in(): bool
{
    return isset($_SESSION['user_id']) && is_int($_SESSION['user_id']);
}

function require_login(): void
{
    if (!logged_in()) {
        redirect('login.php');
    }
}

function guest_only(): void
{
    if (logged_in()) {
        redirect('dashboard.php');
    }
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    if (!isset($_POST['csrf_token']) || !is_string($_POST['csrf_token']) ||
        !hash_equals(csrf_token(), $_POST['csrf_token'])) {
        http_response_code(403);
        exit('Solicitud no válida. Recarga la página y vuelve a intentarlo.');
    }
}

function require_post(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        header('Allow: POST');
        exit('Método no permitido.');
    }
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function valid_id(mixed $id): ?int
{
    $value = filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    return $value === false ? null : $value;
}

function valid_contact(array $input): array
{
    $values = [];
    $errors = [];
    foreach (['nombre', 'telefono', 'correo', 'ciudad'] as $field) {
        $values[$field] = isset($input[$field]) && is_string($input[$field]) ? trim($input[$field]) : '';
    }
    if ($values['nombre'] === '' || mb_strlen($values['nombre']) > 100) {
        $errors[] = 'El nombre es obligatorio y debe tener máximo 100 caracteres.';
    }
    if ($values['telefono'] === '' || mb_strlen($values['telefono']) > 25 ||
        !preg_match('/^[0-9+() .\-]{5,25}$/', $values['telefono'])) {
        $errors[] = 'Ingresa un teléfono válido (entre 5 y 25 caracteres).';
    }
    if (!filter_var($values['correo'], FILTER_VALIDATE_EMAIL) || mb_strlen($values['correo']) > 190) {
        $errors[] = 'Ingresa un correo electrónico válido.';
    }
    if ($values['ciudad'] === '' || mb_strlen($values['ciudad']) > 100) {
        $errors[] = 'La ciudad es obligatoria y debe tener máximo 100 caracteres.';
    }
    // El campo favorito es opcional y solo admite 0 o 1.
    $values['favorito'] = isset($input['favorito']) && is_string($input['favorito']) && $input['favorito'] === '1' ? 1 : 0;
    return [$values, $errors];
}
