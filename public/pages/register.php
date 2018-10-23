<?php
/**
 * Created by PhpStorm.
 * Ajax: MarcoPolo
 * Date: 17.04.2017
 * Time: 14:08
 */

require "../../res/config.inc.php";
require "../../res/lib/functions.php";
require '../../vendor/autoload.php';

$conn = Config::getDb();
$groups = getAllGroups($conn);

use RobThree\Auth\TwoFactorAuth;
use RobThree\Auth\TwoFactorAuthException;

try {
  $tfa = new TwoFactorAuth();
  $secret = $tfa->createSecret();
  $img = $tfa->getQRCodeImageAsDataUri('Todo App', $secret);
} catch (TwoFactorAuthException $e) {
  $logger = new Logger();
  $logger->setMessage('2FA not available');
  $logger->save();

  $logQRCode = new Logger();
  $logQRCode->setMessage('Failed loaded QR code' . $e->getMessage());
  $logQRCode->save();
}

//starts secure Session
session_start([
  'cookie_lifetime' => 86400,
]);

$_SESSION['2fa-secret'] = $secret;
?>

<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/sco.styles.css"/>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="../../node_modules/toastr/build/toastr.min.css">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/img/favicon-16x16.png">
    <title>Registrierung</title>
  </head>
  <body>
    <header class="row col-12">
      <div class="brand-logo">
        <img class="logo" src="../assets/img/logo.svg"/>
      </div>
    </header>
    <div class="content-wrapper">
      <div class="login-wrapper">
        <form class="login-form" method="post" action="">
          <label class="label">
            Name:
            <input id="firstname" class="field-login" type="text" name="name" value="">
          </label>
          <label class="label">
            Nachname:
            <input id="surname" class="field-login" type="text" name="surname" value="">
          </label>
          <label class="label">Dein Team wählen:
            <select id="group-in-register" class="field-login" name="project">
              <?php foreach($groups as $group){?>
                <option value="<?=$group['id']?>"<?php
                if (isset($_POST['group']) && $_POST['group'] === $group['id']){
                  echo 'selected';
                }
                ?>><?=$group['group_name']?></option>
              <?php } ?>
            </select>
          </label>
          <label class="label">
            Benutzername:
            <input id="username" class="field-login" type="text" name="username-reg" value="">
          </label>
          <label class="label">
            Passwort:
            <input id="password" class="field-login" type="password" name="password-reg">
          </label>
          <input id="register-button" class="login-button" type="button" value="Registrieren" />
          <a class="register-button" href="login.php">Anmelden</a>
        </form>
        <div class="clearer"></div>
      </div>
      <div id="qr-code-container">
        <p>Scannen Sie diesen Code um die 2 Faktor Authentifizierung zu aktivieren:</p>
        <div>
          <img src="<?=$img?>"/>
        </div>
      </div>
    </div>
    <div class="footer-login col-12">
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