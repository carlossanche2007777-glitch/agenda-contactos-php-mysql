<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();
require_post();
verify_csrf();
$id = valid_id($_POST['id'] ?? null);
if ($id === null) { http_response_code(400); exit('Solicitud no válida.'); }

// El propietario se verifica también en el UPDATE, no solo en la interfaz.
$stmt = db()->prepare('UPDATE contactos SET favorito = CASE WHEN favorito = 1 THEN 0 ELSE 1 END WHERE id = ? AND id_usuario = ?');
$stmt->execute([$id, (int)$_SESSION['user_id']]);
flash($stmt->rowCount() ? 'success' : 'error', $stmt->rowCount() ? 'Favoritos actualizados.' : 'Contacto no encontrado.');

// Se conservan filtros conocidos; se impiden redirecciones externas.
$buscar = is_string($_POST['buscar'] ?? null) ? mb_substr(trim($_POST['buscar']), 0, 100) : '';
$orden = is_string($_POST['orden'] ?? null) ? $_POST['orden'] : 'nombre_asc';
if (!in_array($orden, ['nombre_asc','nombre_desc','ciudad_asc','ciudad_desc','recientes','antiguos'], true)) $orden = 'nombre_asc';
$favoritos = ($_POST['favoritos'] ?? '') === '1' ? '1' : '0';
redirect('dashboard.php?' . http_build_query(['buscar'=>$buscar, 'orden'=>$orden, 'favoritos'=>$favoritos]));
