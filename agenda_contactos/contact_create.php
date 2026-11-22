<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();
$values = ['nombre'=>'', 'telefono'=>'', 'correo'=>'', 'ciudad'=>'', 'favorito'=>0];
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    [$values, $errors] = valid_contact($_POST);
    if (!$errors) {
        $stmt = db()->prepare('INSERT INTO contactos (id_usuario, nombre, telefono, correo, ciudad, favorito) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$_SESSION['user_id'], $values['nombre'], $values['telefono'], $values['correo'], $values['ciudad'], $values['favorito']]);
        flash('success', 'Contacto guardado correctamente.');
        redirect('dashboard.php');
    }
}
$pageTitle = 'Nuevo contacto';
include __DIR__ . '/includes/header.php';
?>
<section class="card form-card">
<h1>Nuevo contacto</h1>
<?php foreach ($errors as $error): ?><div class="alert error" role="alert"><?= e($error) ?></div><?php endforeach; ?>
<form method="post" action="contact_create.php">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<?php include __DIR__ . '/includes/contact_fields.php'; ?>
<div class="form-actions"><button class="btn primary" type="submit">Guardar</button><a class="btn" href="dashboard.php">Cancelar</a></div>
</form>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
