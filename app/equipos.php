<?php
require_once '../templates/header.php';
require_once '../persistence/DAO/EquiposDAO.php';
require_once '../persistence/conf/PersistentManager.php';
require_once '../utils/SessionHelper.php';

$errors = [];

$equiposDAO = new EquiposDAO();
$equipos = $equiposDAO->selectAll();

// alta equipo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $estadio = $_POST['estadio'] ?? '';

    if (!empty($nombre) && !empty($estadio)) {
        $equiposDAO->insert($nombre, $estadio);
        header("Location: equipos.php");
        exit;
    }
}

// Guardar equipo en sesión cuando se hace clic en "Ver partidos"
if (isset($_GET['set_team'])) {
    $team_id = (int)$_GET['set_team'];
    $_SESSION['last_team_id'] = $team_id;

    // Si no hay usuario logueado, crear sesión básica
    if (!SessionHelper::loggedIn()) {
        $_SESSION['user'] = ['id' => 1, 'username' => 'invitado'];
    }

    header("Location: partidosEquipo.php?equipo_id=" . $team_id);
    exit;
}

SessionHelper::startSessionIfNotStarted();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Equipos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-4">
        <h1 class="mb-3">Equipos</h1>

        <form method="POST" class="mb-4">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre del equipo" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="estadio" class="form-control" placeholder="Estadio" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">Agregar Equipo</button>
                </div>
            </div>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Estadio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipos as $e): ?>
                    <tr>
                        <td><?= htmlspecialchars($e['nombre']) ?></td>
                        <td><?= htmlspecialchars($e['estadio']) ?></td>
                        <td>
                            <a href="equipos.php?set_team=<?= urlencode($e['id']) ?>"
                                class="btn btn-primary btn-sm">
                                Ver partidos
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</body>

</html>