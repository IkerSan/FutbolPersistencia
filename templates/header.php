<?php
/**
 * @title: Proyecto integrador Ev01 - Cabecera y barrra de navegación.
 * @description:  Script PHP para la gestión de la sesión de usuario.
 *                Dependiendo si el usuario esta registrado o no aparecen unas
 *   opciones en la barra de navegación.
 *
 * @version    0.2
 *
 * @author     Ander Frago & Miguel Goyena <miguel_goyena@cuatrovientos.org>
 */

  // Obtenemos el directorio del proyecto para establecer rutas relativas.
  $dir = __DIR__;
  $urlApp = "/PHP/Futbol";
  require_once $dir . '/../utils/SessionHelper.php';

  ///
  /// Gestión de la sesión de usuario:
  ///
  
  // Verificamos si el usuario está logueado
  $loggedin = SessionHelper::loggedIn();
  
  // Obtenemos el nombre de usuario si está logueado, sino mostramos "Invitado"
  if ($loggedin) {
      SessionHelper::startSessionIfNotStarted();
      $user = $_SESSION['user'];
  } else {
      $user = "Invitado";
  }

?>
<head>
    <meta charset="utf-8">
    <title><?php echo "$user" ?></title>
    <meta name="viewport"
          content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <link href="<?php echo $urlApp ?>/assets/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

</head>


<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

<?php
  // En caso de tener una sesión registrada con antelación mostramos las opciones avanzadas de la aplicación
  if ($loggedin)
  {
  ?>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <button class="navbar-toggler navbar-toggler-right" type="button"
                data-toggle="collapse" data-target="#navbarTogglerDemo02"
                aria-controls="navbarToggler" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="<?php echo $urlApp; ?>/index.php">Inicio</a>

        <div class="collapse navbar-collapse" id="navbarToggler">
            <ul class="navbar-nav mr-auto mt-2 mt-md-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $urlApp; ?>/app/equipos.php">Equipos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $urlApp; ?>/app/partidos.php">Partidos</a>
                </li>
            </ul>
        </div>
    </nav>
  <?php
}
else {
  // En caso de ser usuario no registrado, (Invitado)
  ?>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <button class="navbar-toggler navbar-toggler-right" type="button"
                data-toggle="collapse" data-target="#navbarTogglerDemo02"
                aria-controls="navbarToggler" aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="<?php echo $urlApp; ?>/index.php">Inicio</a>

        <div class="collapse navbar-collapse" id="navbarToggler">
            <ul class="navbar-nav mr-auto mt-2 mt-md-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $urlApp; ?>/app/equipos.php">Equipos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $urlApp; ?>/app/partidos.php">Partidos</a>
                </li>
            </ul>
        </div>
    </nav>
  <?php
}
?>

<!-- TODO Hay que incluir el Bootstrap en Assets -->
<script src=""></script>