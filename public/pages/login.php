<?php
/**
 * Created by PhpStorm.
 * User: MarcoPolo
 * Date: 07.04.2017
 * Time: 20:41
 */

  session_start();

  require('../../res/config.inc.php');
  require('../../res/lib/functions.php');

  $conn = Config::getDb();

  // redirect if logged in already
  if(isset($_SESSION['loggedin'])){
    redirect('../?pages=support-links');
  }

  $errors = [];

  // catch username and password post
  if(isset($_POST['username']) && isset($_POST['password'])) {
    if($_POST['username'] != '' && $_POST['password'] != '') {

      $username = $_POST['username'];
      $password = hash("sha256", $_POST['password']);

      $login = auth_user($conn, $username,$password);

      if ($login === true) {
        $_SESSION['loggedin'] = true;
        $user = getUid($conn, $username);
        $_SESSION['kernel']['userdata'] = $user;
        redirect('../../?pages=support-links');
      } else {
        $errors['usernameAndPassword'] = 'Benutzername oder Passwort falsch!';
      }
    }else{
      $errors['emptyFields'] = 'Felder wurden nicht ausgefüllt';
    }
  }

  mysqli_close($conn);

?>

<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap-grid.min.css" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/sco.styles.css"/>
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/img/favicon-16x16.png">
    <title>Login</title>
  </head>
  <body>
    <header class="row">
      <div class="col-12">
        <div class="brand-logo">
          <img class="logo" src="../assets/img/logo.svg"/>
        </div>
      </div>
    </header>
    <div class="content-wrapper">
      <div class="login-wrapper">
        <form class="login-form" method="post" action="">
          <label class="label" for="fname">Benutzername:</label>
          <input class="field-login" type="text" name="username" id="fname">
          <label class="label" for="pname">Passwort:</label>
          <input class="field-login" type="password" name="password" id="pname">
          <input class="login-button" type="submit" name="submit" value="Anmelden"/>
          <a class="register-button" href="register.php">Registrieren</a>
        </form>
        <div class="clearer"></div>
        <?php
        if (isset($_POST['submit'])) {
          if (count($errors) != 0) {
            foreach ($errors as $error) {
              echo '
                <div class="failbox">
                  <p class="center"> ' . $error . '</p>
                </div>
              ';
            }
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
</html>