<?php
// partidos_equipo.php
require_once '../templates/header.php';
require_once '../persistence/DAO/PartidosDAO.php';
require_once '../persistence/conf/PersistentManager.php';
require_once '../utils/SessionHelper.php';

$equipo_id = (int)($_GET['equipo_id'] ?? 0);
if ($equipo_id <= 0) {
    header("Location: equipos.php");
    exit;
}

// nombre equipo
$stmt = $pdo->prepare("SELECT * FROM equipos WHERE id = ?");
$stmt->execute([$equipo_id]);
$equipo = $stmt->fetch();
if (!$equipo) {
    header("Location: equipos.php");
    exit;
}

// partidos donde participa
$stmt = $pdo->prepare("SELECT p.*, el.nombre as local_nombre, ev.nombre as visit_nombre
                       FROM partidos p
                       JOIN equipos el ON p.equipo_local_id = el.id
                       JOIN equipos ev ON p.equipo_visitante_id = ev.id
                       WHERE p.equipo_local_id = ? OR p.equipo_visitante_id = ?
                       ORDER BY p.jornada, p.id");
$stmt->execute([$equipo_id, $equipo_id]);
$partidos = $stmt->fetchAll();
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Partidos de <?= htmlspecialchars($equipo['nombre']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">
    <div class="container">
        <h1>Partidos de <?= htmlspecialchars($equipo['nombre']) ?></h1>
        <p>Estadio: <?= htmlspecialchars($equipo['estadio']) ?></p>

        <table class="table">
            <thead>
                <tr>
                    <th>Jornada</th>
                    <th>Local</th>
                    <th>Visitante</th>
                    <th>Estadio</th>
                    <th>Resultado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$partidos): ?>
                    <tr>
                        <td colspan="5" class="text-center">Este equipo no tiene partidos registrados.</td>
                    </tr>
                <?php endif; ?>
                <?php foreach ($partidos as $p): ?>
                    <tr>
                        <td><?= $p['jornada'] ?></td>
                        <td><?= htmlspecialchars($p['local_nombre']) ?></td>
                        <td><?= htmlspecialchars($p['visit_nombre']) ?></td>
                        <td><?= htmlspecialchars($p['estadio']) ?></td>
                        <td><?= $p['resultado'] ?? '-' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="equipos.php" class="btn btn-link">Volver a Equipos</a>
        <a href="partidos.php" class="btn btn-link">Ver todas las jornadas</a>
    </div>
</body>

</html>