<?php
$results = '';
$userData = "SELECT `name`, surname, username FROM benutzer WHERE id = '" . $uid . "'";

$userData = mysqli_query($conn, $userData);
$results = mysqli_fetch_array($userData, MYSQLI_ASSOC);

$values = [];
$errors = [];

if(isset($_POST['submit'])){

  if(isset($_POST['name']) && $_POST['name'] != ''){
    $values['name'] = htmlspecialchars($_POST['name']);
  }else{
    $errors['name'] = "Ein Name muss angegeben werden.";
  }

  if(isset($_POST['surname']) && $_POST['surname'] != ''){
    $values['surname'] = htmlspecialchars($_POST['surname']);
  }else{
    $errors['surname'] = "Ein Nachname muss angegeben werden.";
  }

  if(isset($_POST['username']) && $_POST['username'] != ''){
    $values['username'] = htmlspecialchars($_POST['username']);
  }else{
    $errors['username'] = "Ein Benutzername muss angegeben werden.";
  }
}

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

  if ($results['password'] === $values['password']) {
    $oldPassword = $values['password'];
  } else {
    $errors['actual-password'] = 'Bitte verwenden Sie ihr aktuelles Passwort';
  }

  if ($values['new_password'] === $values['repeat_password']){
    $newPassword = $oldPassword;
  } else {
    $errors['wrong-repeated-password'] = "Die Passwörter stimmen nicht überein";
  }

  if($results['password'] === $values['new_password']){
    $errors['same-as-before'] = "Bitte nicht das gleiche Passwort wie vorhin verwenden";
  }

}

?>

<div class="row container">
  <div class="col-6">
    <h1 class="margin-bot-25">Information</h1>
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
          if (updateUserdata($conn, $uid, $values) === true) {
            redirect('index.res?logout');
          }else{
            echo '<div class="failbox">
              <p>Diesen Benutzername gibt es bereits</p>
            </div>';
          }
        } else {
        echo '<div class="failbox">';
          foreach ($errors as $error) {
            echo '<p>' . $error . '</p>';
          }
          echo '</div>';
        }
      }
    ?>
    <h1 class="margin-bot-25">Benutzerdaten</h1>
    <div class="user-data-wrapper ">
      <form class="col-12" method="post" action="">
        <div>
          <p class="margin-right-25"><b>Name:</b></p>
          <label>
            <input class="margin-bot-25 input-userdata" name="name" type="text" value="<?= $results['name'] ?>">
          </label>
        </div>
        <div>
          <p class="margin-right-25"><b>Nachname:</b></p>
          <label>
            <input class="margin-bot-25 input-userdata" name="surname" type="text" value="<?= $results['surname'] ?>">
          </label>
        </div>
        <div>
          <p class="margin-right-25"><b>Benutzername:</b></p>
          <label>
            <input class="margin-bot-25 input-userdata" name="username" type="text" value="<?= $results['username'] ?>">
          </label>
        </div>
       <div class="space">
         <input class="button-default" type="submit" name="submit" value="Speichern">
       </div>
      </form>
      <div class="clearer"></div>
      <?php
        if(isset($_POST['submitPassword'])) {
          if (count($errors) === 0) {
            if (updateUserCredentials($conn, $uid, $values['new_password']) === true) {
              redirect('index.res?logout');
            }else{
              echo '<div class="failbox">
                <p>Ein Fehler ist aufgetreten</p>
              </div>';
            }
          } else {
            echo '<div class="failbox">';
            foreach ($errors as $error) {
              echo '<p>' . $error . '</p>';
            }
            echo '</div>';
          }
        }
      ?>
      <form class="col-12" method="post" action="">
        <div>
          <p class="margin-right-25"><b>Passwort*:</b></p>
          <label>
            <input placeholder="Aktuelles Passwort" class="input-userdata" type="password" name="password" value="">
          </label>
        </div>
        <div>
          <label>
            <input placeholder="Neues Passwort" class="input-userdata" type="password" name="new_password" value="">
          </label>
        </div>
        <div>
          <label>
            <input placeholder="Passwort wiederholen" class="input-userdata" type="password" name="repeat_password" value="">
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
