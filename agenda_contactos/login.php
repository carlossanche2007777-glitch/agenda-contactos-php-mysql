<?php
require_once __DIR__ . '/includes/bootstrap.php';
guest_only();
$error = '';
$correo = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $correo = is_string($_POST['correo'] ?? null) ? mb_strtolower(trim($_POST['correo'])) : '';
    $password = is_string($_POST['password'] ?? null) ? $_POST['password'] : '';
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Ingresa correo y contraseña válidos.';
    } else {
        $stmt = db()->prepare('SELECT id, nombre, correo, password FROM usuarios WHERE correo = ? LIMIT 1');
        $stmt->execute([$correo]);
        $user = $stmt->fetch();
        if (!$user || !password_verify($password, $user['password'])) {
            $error = 'Correo o contraseña incorrectos.';
        } else {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['user_email'] = $user['correo'];
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            redirect('dashboard.php');
        }
    }
}
$pageTitle = 'Iniciar sesión';
include __DIR__ . '/includes/header.php';
?>
<section class="card auth-card">
<h1>Iniciar sesión</h1>
<p class="muted">Solo tú puedes consultar y administrar tus contactos.</p>
<?php if ($error): ?><div class="alert error" role="alert"><?= e($error) ?></div><?php endif; ?>
<form method="post" action="login.php">
  <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
  <label>Correo electrónico<input type="email" name="correo" required autocomplete="email" maxlength="190" value="<?= e($correo) ?>"></label>
  <label>Contraseña<input type="password" name="password" required autocomplete="current-password"></label>
  <button class="btn primary" type="submit">Ingresar</button>
</form>
<p>¿No tienes cuenta? <a href="register.php">Crear cuenta</a></p>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
