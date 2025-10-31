<?php
require_once '../templates/header.php';
require_once '../persistence/DAO/PartidosDAO.php';
require_once '../persistence/DAO/EquiposDAO.php';
require_once '../persistence/conf/PersistentManager.php';

// Obtener el id del equipo
$equipo_id = isset($_GET['equipo_id']) ? (int)$_GET['equipo_id'] : 0;

if ($equipo_id <= 0) {
    die("ID de equipo no válido");
}

$daoPartidos = new PartidosDAO();
$daoEquipos = new EquiposDAO();

// Obtener info del equipo
$equipo = $daoEquipos->selectById($equipo_id);

// Obtener partidos del equipo
$partidos = $daoPartidos->selectByEquipo($equipo_id);

function h($v)
{
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Partidos de <?= h($equipo['nombre']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">
    <div class="container">
        <h1>Partidos de <?= h($equipo['nombre']) ?></h1>

        <table class="table table-bordered">
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
                <?php if (empty($partidos)): ?>
                    <tr>
                        <td colspan="5" class="text-center">Este equipo aún no tiene partidos.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($partidos as $p): ?>
                        <tr>
                            <td><?= h($p['jornada']) ?></td>
                            <td><?= h($p['local_nombre']) ?></td>
                            <td><?= h($p['visit_nombre']) ?></td>
                            <td><?= h($p['estadio']) ?></td>
                            <td><?= ($p['resultado'] == '' ? '-' : h($p['resultado'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>