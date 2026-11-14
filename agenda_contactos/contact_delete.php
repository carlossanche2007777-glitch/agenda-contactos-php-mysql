<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();
require_post();
verify_csrf();
$id = valid_id($_POST['id'] ?? null);
if ($id === null) { http_response_code(400); exit('Solicitud no válida.'); }
// Solo se puede eliminar un registro si pertenece al usuario autenticado.
$stmt = db()->prepare('DELETE FROM contactos WHERE id = ? AND id_usuario = ?');
$stmt->execute([$id, $_SESSION['user_id']]);
flash($stmt->rowCount() ? 'success' : 'error', $stmt->rowCount() ? 'Contacto eliminado.' : 'Contacto no encontrado.');
redirect('dashboard.php');
