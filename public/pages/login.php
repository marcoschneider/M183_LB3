<?php
/**
 * Created by PhpStorm.
 * Ajax: MarcoPolo
 * Date: 07.04.2017
 * Time: 20:41
 */

  session_start();

  require('../../res/config.inc.php');
  require('../../res/lib/functions.php');

  $conn = Config::getDb();

  // redirect if logged in already
  if(isset($_SESSION['loggedin'])){
    redirect('../../?pages=todo-overview');
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap-grid.min.css" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="../../node_modules/toastr/build/toastr.min.css">
    <link rel="stylesheet" href="../assets/css/sco.styles.css"/>
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/img/favicon-16x16.png">
    <title>Login</title>
  </head>
  <body>
  <div class="container-fluid">
    <header class="row">
      <div class="col-12">
        <div class="brand-logo">
          <img class="logo" src="../assets/img/logo.svg"/>
        </div>
      </div>
    </header>
  </div>
    <div class="content-wrapper">
      <div class="login-wrapper">
        <form class="login-form" method="post" action="">
          <label class="label" for="fname">Benutzername:</label>
          <input class="field-login" type="text" name="username" id="fname" autofocus>
          <label class="label" for="pname">Passwort:</label>
          <input class="field-login" type="password" name="password" id="pname">
          <input id="login-button" class="login-button" type="button" value="Anmelden"/>
          <a class="register-button" href="register.php">Registrieren</a>
        </form>
        <div class="clearer"></div>
        <?php
        if (isset($_POST['submit'])) {
          if (count($errors) != 0) {
            errorMessage($errors);
          }
        }
        ?>
      </div>
    </div>
    <div class="footer-login">
      <p>Resize the browser window to see how the content respond to the resizing.</p>
      <br>
      <p>&copy Copyright Somedia Production Web Support</p>
    </div>
  </body>
  <script type="text/javascript" src="../../bower_components/jquery/dist/jquery.min.js"></script>
  <script type="text/javascript" src="../../bower_components/js-sha256/build/sha256.min.js"></script>
  <script type="text/javascript" src="../../node_modules/toastr/build/toastr.min.js"></script>
  <script type="text/javascript" src="../assets/js/response-handler.js"></script>
  <script type="text/javascript" src="../assets/js/script.js"></script>
</html>