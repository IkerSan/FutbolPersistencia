<?php
require_once __DIR__ . '/utils/SessionHelper.php';

// Redirige según estado de sesión: si está loggeado -> partidos, si no -> equipos
if (SessionHelper::loggedIn()) {
    header('Location: app/partidos.php');
    exit;
} else {
    header('Location: app/equipos.php');
    exit;
}
?>