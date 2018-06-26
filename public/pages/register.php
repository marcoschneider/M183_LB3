<?php
/**
 * Created by PhpStorm.
 * Ajax: MarcoPolo
 * Date: 17.04.2017
 * Time: 14:08
 */

//error_reporting(E_ALL & ~E_NOTICE);

require "../../res/config.inc.php";
require "../../res/lib/functions.php";

$conn = Config::getDb();
$groups = getAllGroups($conn);
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
          <label class="label">
            Dein Team wählen:
          </label>
          <div id="dropdown-register" class="dropdown-trigger">
            <p>
              <?php
              foreach ($groups as $group){
                if (isset($_POST['group']) && $_POST['group'] === $group['id']) {
                  echo $group['group_name'];
                }else{
                  echo '--Bitte wählen--';
                  echo '<input id="group-in-register" type="hidden" value="0"/>';
                  break;
                }
              }
              ?>
            </p>
            <ul data-name="group" class="dropdown-list">
              <?php
                foreach ($groups as $group) {
                  echo '<li data-list-value="'.$group['id'].'">'.$group['group_name'].'</li>';
                }
              ?>
            </ul>
          </div>
          <label class="label">
            Benutzername:
            <input id="username" class="field-login" type="text" name="username-reg" value="">
          </label>
          <label class="label">
            Passwort:
            <input id="password" class="field-login" type="password" name="password-reg">
          </label>
          <input class="login-button" type="submit" name="submit" value="Registrieren"/>
          <input id="register-button" class="login-button" type="button" value="Registrieren" />
          <a class="register-button" href="login.php">Anmelden</a>
        </form>
        <div class="clearer"></div>
        <?php
/*          if (isset($_POST['submit'])) {
            if (count($errors) != 0) {
              errorMessage($errors);
            }
          }
        if (isset($_POST['submit'])){
          if($registerError != ""){
            errorMessage($registerError);
          }elseif($registerSuccess != ""){
            successMessage($registerSuccess);
          }
        }
        */?>
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