<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_login();

// Se filtran y validan las entradas recibidas por GET.
$buscar = is_string($_GET['buscar'] ?? null) ? trim($_GET['buscar']) : '';
$buscar = mb_substr($buscar, 0, 100);
$orden = is_string($_GET['orden'] ?? null) ? $_GET['orden'] : 'nombre_asc';
$favoritos = ($_GET['favoritos'] ?? '') === '1';

// Lista blanca: nunca interpolar un ORDER BY proporcionado por el visitante.
$ordenes = [
    'nombre_asc' => 'nombre ASC, id ASC',
    'nombre_desc' => 'nombre DESC, id DESC',
    'ciudad_asc' => 'ciudad ASC, nombre ASC',
    'ciudad_desc' => 'ciudad DESC, nombre ASC',
    'recientes' => 'fecha_creacion DESC, id DESC',
    'antiguos' => 'fecha_creacion ASC, id ASC',
];
if (!isset($ordenes[$orden])) $orden = 'nombre_asc';

$sql = 'SELECT id, nombre, telefono, correo, ciudad, favorito, fecha_creacion
        FROM contactos WHERE id_usuario = ?';
$params = [(int)$_SESSION['user_id']];
if ($buscar !== '') {
    // Se escapan los comodines de LIKE, para hacer búsquedas literales.
    $patron = '%' . str_replace(['!', '%', '_'], ['!!', '!%', '!_'], $buscar) . '%';
    $sql .= " AND (nombre LIKE ? ESCAPE '!' OR correo LIKE ? ESCAPE '!' OR ciudad LIKE ? ESCAPE '!')";
    array_push($params, $patron, $patron, $patron);
}
if ($favoritos) $sql .= ' AND favorito = 1';
$sql .= ' ORDER BY ' . $ordenes[$orden];
$stmt = db()->prepare($sql);
$stmt->execute($params);
$contactos = $stmt->fetchAll();

$stats = db()->prepare('SELECT COUNT(*) AS total, COALESCE(SUM(favorito),0) AS fav FROM contactos WHERE id_usuario = ?');
$stats->execute([(int)$_SESSION['user_id']]);
$totales = $stats->fetch();

$pageTitle = 'Mis contactos';
include __DIR__ . '/includes/header.php';
?>
<section class="welcome">
  <div><h1>Mis contactos</h1><p>Bienvenido/a, <strong><?= e($_SESSION['user_name']) ?></strong> · <?= e($_SESSION['user_email']) ?></p></div>
  <a class="btn primary" href="contact_create.php">+ Nuevo contacto</a>
</section>
<div class="stat-strip" aria-label="Resumen de la agenda">
  <div><strong><?= (int)$totales['total'] ?></strong><span>Contactos</span></div>
  <div><strong><?= (int)$totales['fav'] ?></strong><span>Favoritos</span></div>
  <div><strong><?= count($contactos) ?></strong><span>Resultados actuales</span></div>
</div>
<section class="card">
  <h2>Explorar agenda</h2>
  <form method="get" action="dashboard.php" class="filters">
    <label>Buscar por nombre, correo o ciudad
      <input type="search" name="buscar" maxlength="100" value="<?= e($buscar) ?>" placeholder="Ejemplo: Ana, @correo.com, Quito">
    </label>
    <label>Ordenar contactos
      <select name="orden">
        <?php foreach ([
          'nombre_asc'=>'Nombre (A-Z)', 'nombre_desc'=>'Nombre (Z-A)',
          'ciudad_asc'=>'Ciudad (A-Z)', 'ciudad_desc'=>'Ciudad (Z-A)',
          'recientes'=>'Más recientes', 'antiguos'=>'Más antiguos'
        ] as $clave => $etiqueta): ?>
          <option value="<?= e($clave) ?>" <?= $orden === $clave ? 'selected' : '' ?>><?= e($etiqueta) ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label class="checkbox-label"><input type="checkbox" name="favoritos" value="1" <?= $favoritos ? 'checked' : '' ?>> Solo favoritos ★</label>
    <div class="filter-buttons"><button class="btn primary" type="submit">Aplicar filtros</button><a class="btn" href="dashboard.php">Limpiar</a></div>
  </form>
</section>
<section class="card">
  <h2>Agenda personal (<?= count($contactos) ?>)</h2>
  <?php if (!$contactos): ?>
    <p class="muted"><?= ($buscar !== '' || $favoritos) ? 'No hay contactos que coincidan con los filtros.' : 'Todavía no tienes contactos. Selecciona “Nuevo contacto” para registrar el primero.' ?></p>
  <?php else: ?>
  <div class="table-wrap">
  <table>
    <thead><tr><th scope="col">Favorito</th><th scope="col">Nombre</th><th scope="col">Teléfono</th><th scope="col">Correo</th><th scope="col">Ciudad</th><th scope="col">Creado</th><th scope="col">Acciones</th></tr></thead>
    <tbody>
    <?php foreach ($contactos as $contacto): ?>
      <tr>
        <td>
          <form method="post" action="contact_favorite.php">
            <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
            <input type="hidden" name="id" value="<?= (int)$contacto['id'] ?>">
            <input type="hidden" name="buscar" value="<?= e($buscar) ?>">
            <input type="hidden" name="orden" value="<?= e($orden) ?>">
            <input type="hidden" name="favoritos" value="<?= $favoritos ? '1' : '0' ?>">
            <button class="star-btn <?= (int)$contacto['favorito'] === 1 ? 'is-favorite' : '' ?>" type="submit"
                    title="<?= (int)$contacto['favorito'] === 1 ? 'Quitar de favoritos' : 'Marcar como favorito' ?>"
                    aria-label="<?= (int)$contacto['favorito'] === 1 ? 'Quitar de favoritos a ' : 'Agregar a favoritos a ' ?><?= e($contacto['nombre']) ?>">
              <?= (int)$contacto['favorito'] === 1 ? '★' : '☆' ?>
            </button>
          </form>
        </td>
        <td><strong><?= e($contacto['nombre']) ?></strong></td>
        <td><?= e($contacto['telefono']) ?></td>
        <td><a href="mailto:<?= e($contacto['correo']) ?>"><?= e($contacto['correo']) ?></a></td>
        <td><?= e($contacto['ciudad']) ?></td>
        <td><?= e(substr((string)$contacto['fecha_creacion'], 0, 10)) ?></td>
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
