<?php
require_once '../templates/header.php';
require_once '../persistence/DAO/PartidosDAO.php';
require_once '../persistence/DAO/EquiposDAO.php';
require_once '../persistence/conf/PersistentManager.php';
require_once '../utils/SessionHelper.php';

// Si hay equipo_id, mostrar partidos de ese equipo
// Si no hay equipo_id, mostrar todos los partidos
$equipo_id = isset($_GET['equipo_id']) ? (int)$_GET['equipo_id'] : 0;

$dao = new PartidosDAO();
$equipoDAO = new EquiposDAO();

// obtener jornadas y equipos
$jornadas = $dao->obtenerJornadas();
$equipos = $equipoDAO->selectAll();

// procesar formulario
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jornada = (int)($_POST['jornada'] ?? 0);
    $local = (int)($_POST['equipo_local'] ?? 0);
    $visitante = (int)($_POST['equipo_visitante'] ?? 0);
    $estadio = trim($_POST['estadio'] ?? '');
    $resultado = $_POST['resultado'] ?? '';

    if ($jornada <= 0) $errors[] = "Jornada inválida";
    if ($local <= 0 || $visitante <= 0) $errors[] = "Selecciona ambos equipos";
    if ($local === $visitante) $errors[] = "Un equipo no puede jugar contra sí mismo";
    if ($estadio === '') $errors[] = "Indica el estadio";
    if (!in_array($resultado, ['1', 'X', '2'])) $resultado = '';

    if (!$errors) {
        $ok = $dao->insert($jornada, $local, $visitante, $estadio, $resultado);
        if (!$ok) $errors[] = "Estos equipos ya han jugado previamente";
        else header("Location: partidos.php?jornada=$jornada");
    }
}

// jornada a mostrar
$mostrarJornada = (int)($_GET['jornada'] ?? (count($jornadas) ? $jornadas[0] : 1));
$partidos = $dao->selectByJornada($mostrarJornada);

function h($v)
{
    return htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Partidos - Jornada <?= h($mostrarJornada) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">
    <div class="container">
        <h1>Partidos - Jornada <?= h($mostrarJornada) ?></h1>

        <form method="get" class="mb-3">
            <label>Jornada</label>
            <select name="jornada" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
                <?php
                if (!empty($jornadas)) {
                    foreach ($jornadas as $j): ?>
                        <option value="<?= (int)$j ?>" <?= $j == $mostrarJornada ? 'selected' : '' ?>><?= (int)$j ?></option>
                    <?php endforeach;
                } else {
                    // fallback si no hay jornadas registradas
                    for ($i = 1; $i <= 10; $i++): ?>
                        <option value="<?= $i ?>" <?= $i == $mostrarJornada ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor;
                }
                ?>
            </select>
            <a href="equipos.php" class="btn btn-link ms-3">Ir a Equipos</a>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Local</th>
                    <th>Visitante</th>
                    <th>Estadio</th>
                    <th>Resultado</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($partidos)): ?>
                    <tr>
                        <td colspan="4" class="text-center">No hay partidos</td>
                    </tr>
                    <?php else: foreach ($partidos as $p): ?>
                        <tr>
                            <td><?= h($p['local_nombre']) ?></td>
                            <td><?= h($p['visit_nombre']) ?></td>
                            <td><?= h($p['estadio']) ?></td>
                            <td><?= ($p['resultado'] == '' ? '-' : h($p['resultado'])) ?></td>
                        </tr>
                <?php endforeach;
                endif; ?>
            </tbody>
        </table>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul><?php foreach ($errors as $err) echo "<li>" . h($err) . "</li>"; ?></ul>
            </div>
        <?php endif; ?>

        <div class="card mt-4">
            <div class="card-body">
                <h5>Añadir partido</h5>
                <form method="post" class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Jornada</label>
                        <input class="form-control" type="number" name="jornada" min="1" value="<?= h($mostrarJornada) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Equipo local</label>
                        <select name="equipo_local" class="form-select" required>
                            <option value="">--</option>
                            <?php foreach ($equipos as $eq): ?>
                                <option value="<?= (int)$eq['id'] ?>"><?= h($eq['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Equipo visitante</label>
                        <select name="equipo_visitante" class="form-select" required>
                            <option value="">--</option>
                            <?php foreach ($equipos as $eq): ?>
                                <option value="<?= (int)$eq['id'] ?>"><?= h($eq['nombre']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estadio</label>
                        <input class="form-control" name="estadio" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Resultado</label>
                        <select name="resultado" class="form-select">
                            <option value="">-</option>
                            <option value="1">1</option>
                            <option value="X">X</option>
                            <option value="2">2</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-success" type="submit">Guardar partido</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</body>

</html>