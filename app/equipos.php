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
        header("Location: equipos.php"); // recarga la página para ver el nuevo equipo
        exit;
    }
}
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
            <tr><th>Nombre</th><th>Estadio</th></tr>
        </thead>
        <tbody>
            <?php foreach ($equipos as $e): ?>
                <tr>
                    <td><?= htmlspecialchars($e['nombre']) ?></td>
                    <td><?= htmlspecialchars($e['estadio']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>