<?php
/**
 * Created by PhpStorm.
 * Ajax: maschneider
 * Date: 15.06.2017
 * Time: 08:43
 */

$values = [];
$errors = [];

//Update form validatio
if (isset($_POST['submit']) || isset($_POST['update-link'])){
  if (isset($_POST['link_name']) && $_POST['link_name'] != '') {
    $link_name = htmlspecialchars(trim($_POST['link_name']));
    $values['link_name'] = $link_name;
  }else{
    $errors['links_name'] = 'Bitte geben Sie dem Link einen Namen';
  }

  if (isset($_POST['link_id'])) {
    $values['link_id'] = htmlspecialchars($_POST['link_id']);
  }

  if (isset($_POST['link']) && $_POST['link'] != '') {
    $link = htmlspecialchars(trim($_POST['link']));
    $values['link'] = $link;
  }else{
    $errors['link'] = 'Bitte eine URL hinzufügen';
  }
}

$links = getLinks($conn, $uid);

//Delete form validation
if(isset($_POST['delete-link-submit'])) {
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
        if($links != false){
            $ul = '<ul>';
            foreach ($links as $link) {
              $ul .= '<li><a target="_blank" href="' . $link['link_url'] . '">' . $link['link_name'] . '<span class="link-id">' . $link['id'] . '</span></a></li>';
            }
            $ul .= '</ul>';
            echo $ul;
          }else{
            infoMessage("Es wurden keine Links in Ihrer Datenbank gefunden", 12);
          }

        ?>
      </div>
    </div>
    <div class="col-5">
      <h2 id="add-link-title" class="space">Link hinzufügen</h2>
      <?php
      if (isset($_POST['submit'])){
        if (count($errors) <= 0){
          $insertResult = addLink($conn, $values, $uid);
          if($insertResult === true) {
            redirect('?pages=support-links');
          }else{
            $errors['message'] = $insertResult;
          }
        }else{
          errorMessage($errors);
        }
      }elseif (isset($_POST['update-link'])) {
        if (count($errors) <= 0) {
          $updateResult = updateLink($conn, $values, $uid);
          if ($updateResult === true) {
            redirect('?pages=support-links');
          }else{
            $errors['message'] = $updateResult;
          }
        }else{
          errorMessage($errors);
        }
      }
      ?>
      <form id="add-link" class="form" method="POST" action="">
        <label>
          Name des Links*:
          <input id="name-link" type="text" name="link_name" class="form_control" value="<?php
          echo (isset($_POST ['link_name']))
            ? $_POST['link_name']
            : '';
          ?>">
        </label>
        <div class="space-with-border"></div>
        <label>
          URL hinzufügen*
          <br>
          <input id="link-url" placeholder="https://www.example.ch" type="url" name="link" class="form_control" value="<?php
          echo (isset($_POST ['link']))
            ? $_POST['link']
            : '';
          ?>">
        </label>
        <div class="space"></div>
        <input id="update-link-trigger" type="submit" name="submit" class="button-default" value="Link speichern">
      </form>
    </div>
    <div class="col-3">
      <h2 class="space">Link löschen/bearbeiten</h2>
        <?php
        if (isset($_POST['delete-link-submit'])){
          if (empty($errors)){
            foreach($links as $link){
              if($values['link-to-delete'] === $link['id']){
                $link_id = $values['link-to-delete'];

                $resultDelete = deleteLink($conn, $uid, $link_id);
                if ($resultDelete === true) {
                  redirect("?pages=support-links");
                }else{
                  $errors['delete-link-query'] = $resultDelete;
                }
              }
            }
          }else{
            echo '<div class="space">';
              errorMessage($errors);
            echo '</div>';
          }
        }
        ?>
      <form id="update-edit-link-form" class="form" method="POST" action="">
        <label>
          ID des Links*:
          <input id="link-to-update" type="text" name="link_id" class="form_control" value="<?php
          if(!empty($_POST['link_name'])){
            echo $_POST['link_name'];
          }
          ?>">
        </label>
        <div class="space"></div>
        <input type="submit" name="delete-link-submit" class="button-default" value="Link löschen">
        <div class="space"></div>
        <button id="update-link-submit" type="button" class="button-default">Link bearbeiten</button>
      </form>
    </div>
  </div>
  <div class="row">
    <div class="col-12">

    </div>
  </div>
</div>