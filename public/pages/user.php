<?php

/*$userModel = new UserModel($conn, $uid);
$results = $userModel->getUserdata();*/

$values = [];
$errors = [];

if(isset($_POST['submitPassword'])){

  if(isset($_POST['password']) && $_POST['password'] != ''){
    $values['password'] = hash("sha256", htmlspecialchars($_POST['password']));
  }else{
    $errors['password'] = "Bitte das aktuelle Passwort eingeben";
  }

  if(isset($_POST['new_password']) && $_POST['new_password'] != ''){
    $values['new_password'] = hash("sha256", htmlspecialchars($_POST['new_password']));
  }else{
    $errors['new_password'] = "Bitte ein neues Passwort setzten.";
  }

  if(isset($_POST['repeat_password']) && $_POST['repeat_password'] != ''){
    $values['repeat_password'] = hash("sha256", htmlspecialchars($_POST['repeat_password']));
  }else{
    $errors['repeat_password'] = 'Das Feld "Passwort wiederholen" muss ausgefült sein';
  }

}

?>

<div class="row container">
  <div class="col-6">
    <h1 class="">Information</h1>
    <p>
      Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua.
      At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor
      sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam
      et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
    </p>
  </div>
  <div class="col-6">
    <?php
      if(isset($_POST['submit'])) {
        if (count($errors) === 0) {
          $userdataResponse = updateUserdata($conn, $uid, $values);
          if ($userdataResponse === true) {
            redirect("?pages=userdata");
            $_SESSION['kernel']['userdata']['firstname'] = $values['name'];
            $_SESSION['kernel']['userdata']['surname'] = $values['surname'];
            $_SESSION['kernel']['userdata']['username'] = $values['username'];
            successMessage("Deine Benutzerdaten wurden aktualisiert");
          }else{
            errorMessage("Diesen Benutzername gibt es bereits");
          }
        } else {
          errorMessage($errors);
        }
      }
    ?>
    <h1 class="">Benutzerdaten</h1>
    <div class="user-data-wrapper ">
      <form class="col-12" method="post" action="">
        <div>
          <p class="margin-right-25"><b>Name:</b></p>
          <label>
            <input id="input-userdata-firstname" class="input-userdata" name="name" type="text" value="">
          </label>
        </div>
        <div>
          <p class="margin-right-25"><b>Nachname:</b></p>
          <label>
            <input id="input-userdata-surname" class="input-userdata" name="surname" type="text" value="">
          </label>
        </div>
        <div>
          <p class="margin-right-25"><b>Benutzername*:</b></p>
          <label>
            <input id="input-userdata-username" class="input-userdata" name="username" type="text" value="">
          </label>
        </div>
        <input id="update-userdata" class="button-default" type="button" value="Speichern"/>
      <div class="space"></div>
      </form>
      <div class="clearer"></div>
      <?php
        if(isset($_POST['submitPassword'])) {
          if (count($errors) === 0) {
            if (updateUserCredentials($conn, $uid, $values['new_password']) === true) {
              redirect('?pages=logout');
            }else{
              errorMessage("Ein Fehler ist aufgetreten");
            }
          } else {
            errorMessage($errors);
          }
        }
      ?>
      <form class="col-12" method="post" action="">
        <div>
          <p><b>Akutelles Passwort*:</b></p>
          <label>
            <input class="input-userdata" type="password" name="password" value="">
          </label>
        </div>
        <div>
          <p><b>Neues Passwort*:</b></p>
          <label>
            <input class="input-userdata" type="password" name="new_password" value="">
          </label>
        </div>
        <div>
          <p><b>Neues Passwort wiederholen*:</b></p>
          <label>
            <input class="input-userdata" type="password" name="repeat_password" value="">
          </label>
        </div>
        <div class="space">
          <input class="button-default" type="submit" name="submitPassword" value="Neues Passwort setzten">
        </div>
        <p>*Pflichtfelder</p>
      </form>
      <div class="clearer"></div>
    </div>
  </div>
</div>
