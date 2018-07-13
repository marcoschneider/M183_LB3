<?php
/**
 * Created by PhpStorm.
 * Ajax: maschneider
 * Date: 15.06.2017
 * Time: 08:43
 */

$links = getLinks($conn, $uid);

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
      <form id="add-link" class="form" method="POST" action="/support-links/add">
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
      <form id="update-edit-link-form" class="form" method="POST" action="/support-links/delete">
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
</div>