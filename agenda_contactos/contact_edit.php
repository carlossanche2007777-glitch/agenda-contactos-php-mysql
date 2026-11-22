<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();
$id = valid_id($_GET['id'] ?? null);
if ($id === null) { http_response_code(404); exit('Contacto no encontrado.'); }
// La condición id_usuario evita incluso el acceso por URL a contactos ajenos.
$stmt = db()->prepare('SELECT id, nombre, telefono, correo, ciudad, favorito FROM contactos WHERE id = ? AND id_usuario = ? LIMIT 1');
$stmt->execute([$id, $_SESSION['user_id']]);
$contacto = $stmt->fetch();
if (!$contacto) { http_response_code(404); exit('Contacto no encontrado.'); }
$values = $contacto;
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    [$values, $errors] = valid_contact($_POST);
    if (!$errors) {
        $update = db()->prepare('UPDATE contactos SET nombre = ?, telefono = ?, correo = ?, ciudad = ?, favorito = ? WHERE id = ? AND id_usuario = ?');
        $update->execute([$values['nombre'], $values['telefono'], $values['correo'], $values['ciudad'], $values['favorito'], $id, $_SESSION['user_id']]);
        flash('success', 'Contacto actualizado correctamente.');
        redirect('dashboard.php');
    }
}
$pageTitle = 'Editar contacto';
include __DIR__ . '/includes/header.php';
?>
<section class="card form-card">
<h1>Editar contacto</h1>
<?php foreach ($errors as $error): ?><div class="alert error" role="alert"><?= e($error) ?></div><?php endforeach; ?>
<form method="post" action="contact_edit.php?id=<?= $id ?>">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<?php include __DIR__ . '/includes/contact_fields.php'; ?>
<div class="form-actions"><button class="btn primary" type="submit">Guardar cambios</button><a class="btn" href="dashboard.php">Cancelar</a></div>
</form>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
