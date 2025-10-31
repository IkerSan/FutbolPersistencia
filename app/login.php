<?php
/**
 * @title: Proyecto integrador Ev01 - Acceso al sistema.
 * @description:  Script PHP para acceder al sistema
 *
 * @version 0.1
 *
 * @author ander_frago@cuatrovientos.org miguel_goyena@cuatrovientos.org
 */

require_once '../templates/header.php';
require_once '../persistence/DAO/UserDAO.php';
require_once '../persistence/conf/PersistentManager.php';
require_once '../utils/SessionHelper.php';

// Al pulsar el boton del formulario se recarga la misma página, volviendo a ejecutar este script.
// En caso de que se haya completado los valores del formulario se verifica la existencia de usuarios en la base de datos
// para los valores introducidos.
$error = "";
$userDAO = new UserDAO();

// Verificamos si ya hay una sesión activa
if (SessionHelper::loggedIn()) {
    header("Location: ../index.php");
    exit;
}

if (isset($_POST['user']) && isset($_POST['password']))
{
  $user = $_POST['user'];
  $pass = $_POST['password'];
  
  if ($user == "" || $pass == "") {
      $error = "Debes completar todos los campos<br>";
  }
  else
  {
    // Comprueba que es correcta el User y PASS
    if (!$userDAO->checkExists($user, $pass)) {
      $error = "<span class='error'>Usuario/Contraseña inválida</span><br><br>";
    }
    else
    {
      // Realiza la gestión de la sesión de usuario utilizando SessionHelper
      SessionHelper::setSession($user);
      
      // Redireccionamos a la página principal
      header("Location: ../index.php");
      exit;
    }
  }
}
?>
<div class="container">
  <form class="form-horizontal" role="form" method="POST" action="login.php">
          <div class="row">
              <div class="col-md-3"></div>
              <div class="col-md-6">
                  <h2>Introduzca detalles del acceso</h2>
                  <hr>
              </div>
          </div>
          <div class="row">
              <div class="col-md-3"></div>
              <div class="col-md-6">
                  <div class="form-group has-danger">
                      <label class="sr-only" for="user">Usuario</label>
                      <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                          <div class="input-group-addon" style="width: 2.6rem"></div>
                          <input type="text" name="user" class="form-control" id="user"
                                 placeholder="Jrenzu32" required autofocus>
                      </div>
                  </div>
              </div>
          </div>
          <div class="row">
              <div class="col-md-3"></div>
              <div class="col-md-6">
                  <div class="form-group">
                      <label class="sr-only" for="password">Contraseña:</label>
                      <div class="input-group mb-2 mr-sm-2 mb-sm-0">
                          <div class="input-group-addon" style="width: 2.6rem"></div>
                          <input type="password" name="password" class="form-control" id="password"
                                 placeholder="Contraseña" required>
                      </div>
                  </div>
              </div>
          </div>
          <?php if ($error): ?>
          <div class="row">
              <div class="col-md-3"></div>
              <div class="col-md-6">
                  <div class="alert alert-danger">
                      <?php echo $error ?>
                  </div>
              </div>
          </div>
          <?php endif; ?>
          <div class="row" style="padding-top: 1rem">
              <div class="col-md-3"></div>
              <div class="col-md-6">
                  <button type="submit" class="btn btn-success"><i class="fa fa-sign-in"></i> Acceder</button>
              </div>
          </div>
      </form>
  </div>