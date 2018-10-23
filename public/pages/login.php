<?php
/**
 * Created by PhpStorm.
 * Ajax: MarcoPolo
 * Date: 07.04.2017
 * Time: 20:41
 */

require '../../res/lib/SessionManager.php';
require '../../res/lib/Logger.php';
require '../../res/config.inc.php';
require '../../res/lib/functions.php';
//starts secure Session

session_start([
  'cookie_lifetime' => 86400,
]);

// redirect if logged in already
if (isset($_SESSION['loggedin'])) {
  redirect(Config::getHostname() . '/todo-overview');
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?= Config::getHostname()?>/bower_components/bootstrap/dist/css/bootstrap-grid.min.css"/>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  <link rel="stylesheet" href="<?= Config::getHostname()?>/node_modules/toastr/build/toastr.min.css">
  <link rel="stylesheet" href="<?= Config::getHostname()?>/public/assets/css/sco.styles.css"/>
  <link rel="icon" type="image/png" sizes="32x32" href="<?= Config::getHostname()?>/public/assets/img/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= Config::getHostname()?>/public/assets/img/favicon-16x16.png">
  <title>Login</title>
</head>
<body>
<div class="container-fluid">
  <header class="row">
    <div class="col-12">
      <div class="brand-logo">
        <img class="logo" src="<?= Config::getHostname()?>/public/assets/img/logo.svg"/>
      </div>
    </div>
  </header>
</div>
<div class="content-wrapper">
  <div class="login-wrapper">
    <form class="login-form" method="post" action="">
      <label id="label-2fa-code" class="label">2FA Code:
        <input class="field-login" type="text" name="2fa-code" id="2fa-code"/>
      </label>
      <div id="fields-user-password">
        <label class="label" for="fname">Benutzername:</label>
        <input class="field-login" type="text" name="username" id="fname" autofocus>
        <label class="label" for="pname">Passwort:</label>
        <input class="field-login" type="password" name="password" id="pname">
      </div>
      <input id="login-button" class="login-button" type="button" value="Anmelden"/>
      <input id="login-button-after-2fa" class="login-button" type="button" value="Anmelden"/>
      <a class="register-button" href="register.php">Registrieren</a>
    </form>
    <div class="space"></div>
    <div class="clearer"></div>
  </div>
</div>
<div class="footer-login">
  <p>Resize the browser window to see how the content respond to the resizing.</p>
  <br>
  <p>&copy Copyright Somedia Production Web Support</p>
</div>
</body>
<script type="text/javascript" src="<?= Config::getHostname()?>/bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="<?= Config::getHostname()?>/bower_components/js-sha256/build/sha256.min.js"></script>
<script type="text/javascript" src="<?= Config::getHostname()?>/node_modules/toastr/build/toastr.min.js"></script>
<script type="text/javascript" src="<?= Config::getHostname()?>/public/assets/js/response-handler.js"></script>
<script type="text/javascript" src="<?= Config::getHostname()?>/public/assets/js/script.js"></script>
</html>