<?php
/**
 * Created by PhpStorm.
 * Ajax: MarcoPolo
 * Date: 17.04.2017
 * Time: 14:08
 */

error_reporting(E_ALL & ~E_NOTICE);

require('../../res/config.inc.php');
require('../../res/lib/functions.php');

$conn = Config::getDb();

$groups = getAllGroups($conn);

$value = array();
$errors = array();
$registerError = "";
$registerSuccess = "";

if(isset($_POST['submit'])){
  if(isset($_POST['name']) && $_POST['name'] != ''){
    $value['name'] = htmlspecialchars($_POST['name']);
  }else{
    $errors['name'] = 'Name wurde nicht ausgefüllt' . '<br>';
  }

  if(isset($_POST['surname']) && $_POST['surname'] != ''){
    $value['surname'] = htmlspecialchars($_POST['surname']);
  }else{
    $errors['surname'] = 'Nachname wurde nicht ausgefüllt' . '<br>';
  }

  if(isset($_POST['team']) && $_POST['team'] != ""){
    $value['team'] = htmlspecialchars($_POST['team']);
  }else{
    $errors['team'] = 'Team wurde nicht ausgewählt' . '<br>';
  }

  if(isset($_POST['username-reg']) && $_POST['username-reg'] != ''){
    $value['username-reg'] = htmlspecialchars($_POST['username-reg']);
  }else{
    $errors['username-reg'] = 'Benutzername wurde nicht ausgefüllt' . '<br>';
  }

  if(isset($_POST['password-reg']) && $_POST['password-reg'] != ''){
    $value['password-reg'] = htmlspecialchars($_POST['password-reg']);
  }else{
    $errors['password-reg'] = 'Passwort wurde nicht ausgefüllt' . '<br>';
  }

  $name = $value['name'];
  $surname = $value['surname'];
  $team = $value['team'];
  $username_reg = $value['username-reg'];
  $password_reg = $value['password-reg'];

  if(count($errors) === 0){
    $register = insertRegister($name, $surname, $team, $conn, $username_reg, $password_reg);
    if($register === true){
      $registerSuccess = 'Registrierung war erfolgreich';
    }else{
      $registerError = sqlErrors($register);
    }
  }
}



?>

<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/sco.styles.css"/>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
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
            <input class="field-login" type="text" name="name" value="<?= $_POST['name']?>">
          </label>
          <label class="label">
            Nachname:
            <input class="field-login" type="text" name="surname" value="<?= $_POST['surname']?>">
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
            <input class="field-login" type="text" name="username-reg" value="<?= $_POST['username-reg']?>">
          </label>
          <label class="label">
            Passwort:
            <input class="field-login" type="password" name="password-reg">
          </label>
          <input class="login-button" type="submit" name="submit" value="Registrieren"/>
          <a class="register-button" href="login.php">Anmelden</a>
        </form>
        <div class="clearer"></div>
        <?php
          if (isset($_POST['submit'])) {
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
        ?>
      </div>
    </div>
    <div class="footer-login col-12">
      <p>Resize the browser window to see how the content respond to the resizing.</p>
      <br>
      <p>&copy Copyright Somedia Production Web Support</p>
    </div>
  </body>
  <script src="../../bower_components/jquery/dist/jquery.min.js"></script>
  <script src="../assets/js/script.js"></script>
</html>
