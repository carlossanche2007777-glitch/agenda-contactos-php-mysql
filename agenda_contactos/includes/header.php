<?php
$pageTitle = $pageTitle ?? 'Agenda de contactos';
$alert = get_flash();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?> | Agenda Personal</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="site-header">
  <div class="container header-content">
    <a class="brand" href="<?= logged_in() ? 'dashboard.php' : 'login.php' ?>">📒 Agenda Personal</a>
    <nav aria-label="Navegación principal">
    <?php if (logged_in()): ?>
      <a href="dashboard.php">Mis contactos</a>
      <form method="post" action="logout.php" class="inline-form">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <button class="nav-button" type="submit">Cerrar sesión</button>
      </form>
    <?php else: ?>
      <a href="login.php">Ingresar</a>
      <a href="register.php">Registrarse</a>
    <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container">
<?php if ($alert): ?>
  <div class="alert <?= $alert['type'] === 'success' ? 'success' : 'error' ?>" role="status"><?= e($alert['message']) ?></div>
<?php endif; ?>
