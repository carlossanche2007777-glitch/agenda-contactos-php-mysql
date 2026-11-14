<?php
require_once __DIR__ . '/includes/bootstrap.php';
guest_only();
$errors = [];
$nombre = '';
$correo = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $nombre = is_string($_POST['nombre'] ?? null) ? trim($_POST['nombre']) : '';
    $correo = is_string($_POST['correo'] ?? null) ? mb_strtolower(trim($_POST['correo'])) : '';
    $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
    $confirm = is_string($_POST['confirm_password'] ?? null) ? $_POST['confirm_password'] : '';
    if ($nombre === '' || mb_strlen($nombre) > 100) $errors[] = 'Ingresa un nombre válido (máximo 100 caracteres).';
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL) || mb_strlen($correo) > 190) $errors[] = 'Ingresa un correo válido.';
    if (strlen($password) < 8 || strlen($password) > 72) $errors[] = 'La contraseña debe tener entre 8 y 72 caracteres.';
    if ($password !== $confirm) $errors[] = 'Las contraseñas no coinciden.';
    if (!$errors) {
        $stmt = db()->prepare('SELECT id FROM usuarios WHERE correo = ? LIMIT 1');
        $stmt->execute([$correo]);
        if ($stmt->fetch()) {
            $errors[] = 'El correo ya está registrado.';
        } else {
            try {
                $stmt = db()->prepare('INSERT INTO usuarios (nombre, correo, password) VALUES (?, ?, ?)');
                $stmt->execute([$nombre, $correo, password_hash($password, PASSWORD_DEFAULT)]);
                flash('success', 'Cuenta creada correctamente. Ahora inicia sesión.');
                redirect('login.php');
            } catch (PDOException $ex) {
                if ($ex->getCode() === '23000') $errors[] = 'El correo ya está registrado.';
                else { error_log($ex->getMessage()); $errors[] = 'No fue posible crear la cuenta.'; }
            }
        }
    }
}
$pageTitle = 'Registro';
include __DIR__ . '/includes/header.php';
?>
<section class="card auth-card">
<h1>Crear cuenta</h1>
<p class="muted">Registra tus datos para crear tu agenda privada.</p>
<?php foreach ($errors as $error): ?><div class="alert error" role="alert"><?= e($error) ?></div><?php endforeach; ?>
<form method="post" action="register.php">
  <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
  <label>Nombre completo<input required maxlength="100" name="nombre" autocomplete="name" value="<?= e($nombre) ?>"></label>
  <label>Correo electrónico<input required maxlength="190" type="email" name="correo" autocomplete="email" value="<?= e($correo) ?>"></label>
  <label>Contraseña (mínimo 8 caracteres)<input required minlength="8" maxlength="72" type="password" name="password" autocomplete="new-password"></label>
  <label>Confirmar contraseña<input required minlength="8" maxlength="72" type="password" name="confirm_password" autocomplete="new-password"></label>
  <button class="btn primary" type="submit">Registrarme</button>
</form>
<p>¿Ya tienes cuenta? <a href="login.php">Iniciar sesión</a></p>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
