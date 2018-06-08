<?php
/**
 * Created by PhpStorm.
 * User: maschneider
 * Date: 15.06.2017
 * Time: 08:43
 */
$values = [];
$errors = [];
if (isset($_POST['submit'])){
  if (isset($_POST['link_name']) && $_POST['link_name'] != '') {
    $link_name = htmlspecialchars(trim($_POST['link_name']));
    $values['link_name'] = $link_name;
  }else{
    $errors['links_name'] = 'Bitte geben Sie dem Link einen Namen';
  }

  if (isset($_POST['link']) && $_POST['link'] != '') {
    $link = htmlspecialchars(trim($_POST['link']));
    $values['link'] = $link;
  }else{
    $errors['link'] = 'Bitte eine URL hinzufügen';
  }
}

$result = getLinks($conn, $uid);

if(isset($_POST['delete-link-submit'])){
  if(isset($_POST['link_id']) && $_POST['link_id'] != ""){
    $link_id = htmlspecialchars(trim($_POST['link_id']));
    $values['link-to-delete'] = $link_id;
  }else{
    $errors['link-to-delete'] = "Bitte die ID des zu löschenden Links eingeben";
  }
}


?>
<div class="container">
  <div class="row">
    <div class="col-4">
      <h2 class="space">Wichtie Links</h2>
      <div class="support-links">
        <?php
        if($result != false){
            $ul = '<ul>';
            foreach ($result as $link) {
              $ul .= '<li><a target="_blank" href="' . $link['url'] . '">' . $link['link_name'] . '<span class="link-id">' . $link['id'] . '</span></a></li>';
            }
            $ul .= '</ul>';
            echo $ul;
          }else{
            infoMessage("Es wurden keine Links in Ihrer Datenbank gefunden", 6);
          }

        ?>
      </div>
    </div>
    <div class="col-5">
      <h2 class="space">Link hinzufügen</h2>
      <?php
      if (isset($_POST['submit'])){
        if (count($errors) === 0){
          $insertResult = addLink($conn, $values, $uid);
          if($insertResult === true) {
            redirect('?pages=support-links');
          }else{
            $errors['message'] = 'Ups da ist etwas schief gelaufen';
          }
        }else{
          echo '<div class="failbox">';
          foreach ($errors as $error) {
            echo '<p>' . $error . '</p>';
          }
          echo "</div>";
        }
      }
      ?>
      <form class="form" method="POST" action="">
        <label>
          Name des Links*:
          <input type="text" name="link_name" class="form_control" value="<?php
          if(!empty($_POST['link_name'])){
            echo $_POST['link_name'];
          }
          ?>">
        </label>
        <div class="space-with-border"></div>
        <label>
          URL hinzufügen*
          <br>
          <input placeholder="https://www.viaduct.ch" type="url" name="link" class="form_control" value="<?php
          if(!empty($_POST['link'])){
            echo $_POST['link'];
          }
          ?>">
        </label>
        <div class="space"></div>
        <input type="submit" name="submit" class="button-default" value="Link speichern">
      </form>
    </div>
    <div class="col-3">
      <h2 class="space">Link löschen</h2>
        <?php
        if (isset($_POST['delete-link-submit'])){
          if (count($errors) === 0){
            foreach($result as $link){
              if($values['link-to-delete'] === $link['id']){
                $link_id = $values['link-to-delete'];
                $resultDelete = deleteLink($conn, $uid, $link_id);
                if($resultDelete === true){
                  redirect("?pages=support-links");
                }
              }else{
                $error['link-to-delete'] = "Link mit der ID: " . $link_id . " existiert nicht";
              }
            }
            echo '<div class="space"><div class="failbox">';
            echo '<p>' . $error['link-to-delete'] . '</p>';
            echo " </div></div>";
          }else{
            echo '<div class="space"><div class="failbox">';
            foreach ($errors as $error) {
              echo '<p>' . $error . '</p>';
            }
            echo " </div></div>";
          }
        }
        ?>
      <form class="form" method="POST" action="">
        <label>
          ID des Links*:
          <input type="text" name="link_id" class="form_control" value="<?php
          if(!empty($_POST['link_name'])){
            echo $_POST['link_name'];
          }
          ?>">
        </label>
        <div class="space"></div>
        <input type="submit" name="delete-link-submit" class="button-default" value="Link löschen">
      </form>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      Thas some sheit
    </div>
  </div>
</div>