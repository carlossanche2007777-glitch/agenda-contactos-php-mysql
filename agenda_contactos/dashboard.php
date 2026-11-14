<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();
$stmt = db()->prepare('SELECT id, nombre, telefono, correo, ciudad FROM contactos WHERE id_usuario = ? ORDER BY nombre ASC, id DESC');
$stmt->execute([$_SESSION['user_id']]);
$contactos = $stmt->fetchAll();
$pageTitle = 'Panel principal';
include __DIR__ . '/includes/header.php';
?>
<section class="welcome">
  <div><h1>Mis contactos</h1><p>Bienvenido/a, <strong><?= e($_SESSION['user_name']) ?></strong> · <?= e($_SESSION['user_email']) ?></p></div>
  <a class="btn primary" href="contact_create.php">+ Nuevo contacto</a>
</section>
<section class="card">
  <h2>Agenda personal (<?= count($contactos) ?>)</h2>
  <?php if (!$contactos): ?>
    <p class="muted">Todavía no tienes contactos. Selecciona “Nuevo contacto” para registrar el primero.</p>
  <?php else: ?>
  <div class="table-wrap">
  <table>
    <thead><tr><th>Nombre</th><th>Teléfono</th><th>Correo</th><th>Ciudad</th><th>Acciones</th></tr></thead>
    <tbody>
    <?php foreach ($contactos as $contacto): ?>
      <tr>
        <td><?= e($contacto['nombre']) ?></td>
        <td><?= e($contacto['telefono']) ?></td>
        <td><?= e($contacto['correo']) ?></td>
        <td><?= e($contacto['ciudad']) ?></td>
        <td class="actions">
          <a class="btn small" href="contact_edit.php?id=<?= (int)$contacto['id'] ?>">Editar</a>
          <form class="inline-form" method="post" action="contact_delete.php" onsubmit="return confirm('¿Seguro que deseas eliminar este contacto?');">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="id" value="<?= (int)$contacto['id'] ?>">
            <button type="submit" class="btn danger small">Eliminar</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  </div>
  <?php endif; ?>
</section>
<?php include __DIR__ . '/includes/footer.php'; ?>
