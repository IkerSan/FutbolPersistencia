<?php

require_once '../templates/header.php';
require_once '../persistence/DAO/UserDAO.php';
require_once '../persistence/conf/PersistentManager.php';
require_once '../utils/SessionHelper.php';

$errors = [];

// alta equipo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_equipo'])) {
    $nombre = trim($_POST['nombre'] ?? '');
    $estadio = trim($_POST['estadio'] ?? '');

    if ($nombre === '') $errors[] = "El nombre es obligatorio.";
    if ($estadio === '') $errors[] = "El estadio es obligatorio.";

    if (!$errors) {
        // insertar (controlar nombre único)
        try {
            $stmt = $pdo->prepare("INSERT INTO equipos (nombre, estadio) VALUES (?, ?)");
            $stmt->execute([$nombre, $estadio]);
            header("Location: equipos.php");
            exit;
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate') !== false) {
                $errors[] = "Ya existe un equipo con ese nombre.";
            } else {
                $errors[] = "Error al guardar: " . $e->getMessage();
            }
        }
    }
}

// leer equipos
$equipos = $pdo->query("SELECT * FROM equipos ORDER BY nombre")->fetchAll();
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Equipos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">
    <div class="container">
        <h1>Equipos</h1>

        <!-- Errores -->
        <?php if ($errors): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $err): ?><li><?= htmlspecialchars($err) ?></li><?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Formulario añadir -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="post" class="row g-3">
                    <input type="hidden" name="add_equipo" value="1">
                    <div class="col-md-6">
                        <label class="form-label">Nombre del equipo</label>
                        <input class="form-control" name="nombre" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estadio</label>
                        <input class="form-control" name="estadio" required>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" type="submit">Añadir equipo</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista equipos -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Estadio</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($equipos as $e): ?>
                    <tr>
                        <td><?= htmlspecialchars($e['nombre']) ?></td>
                        <td><?= htmlspecialchars($e['estadio']) ?></td>
                        <td>
                            <a class="btn btn-sm btn-outline-primary" href="partidos_equipo.php?equipo_id=<?= $e['id'] ?>">Ver partidos</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="partidos.php" class="btn btn-link">Ir a Partidos</a>
    </div>
</body>

</html>