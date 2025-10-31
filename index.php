<?php
require_once __DIR__ . '/utils/SessionHelper.php';

SessionHelper::startSessionIfNotStarted();

// Redirige según estado de sesión
if (SessionHelper::loggedIn() && isset($_SESSION['last_team_id'])) {
    header('Location: /PHP/Futbol/app/partidosEquipo.php?equipo_id=' . $_SESSION['last_team_id']);
} else {
    header('Location: /PHP/Futbol/app/equipos.php');
}
exit;
?>