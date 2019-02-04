<?php
/**
 * Created by PhpStorm.
 * Ajax: MarcoPolo
 * Date: 30.06.2017
 * Time: 21:15
 */
error_reporting(E_ALL);

$result = [];

//Gets todo Details
$projects = getAllProjects($conn);
$todo = getTodoDetails($conn, $getID);
$priorities = getAllPriorities($conn);

//Loop through returned Array
if (isset($getID) && $getID != '') {
  $result = $todo;
}
?>

<div class="container">
  <div class="row">
    <div class="col-6 space">
      <h2>Aufgabe bearbeiten</h2>
      <div class="space"></div>
      <form class="form" method="POST" action="<?=Config::getURLPrefix()?>/edit-todo/<?= $getID?>">
        <?php
        //Validate form and insert results into db
        if(isset($_POST['submit'])){
          $POST = $_POST;
          $values = validateTodoForm($POST, $conn);
          $errors = $values['errors'];

          if (count($errors) === 0){
            $updateTodo = saveEdit($conn, $values, $getID, $uid);
            if($updateTodo === true){
              redirect(Config::getURLPrefix().'/todo-overview');
              die();
            }else{
              errorMessage("Das Todo konnte nicht aktualisiert werden");
            }
          }else{
            errorMessage($errors);
            $result = $_POST;
            echo '<div class="space"></div>';
          }
        }
        ?>
        <label>
          <?php
          echo (isset($errors['title']))
            ? '<p class="text-error">Projektname*</p>'
            : '<p>Projektname*</p>';
          ?>
          <select name="project">
            <?php foreach($projects as $key => $project){?>
              <option value="<?=$project['id']?>"<?php
                if (isset($result['project_id']) && $project['id'] === $result['project_id']){
                  echo 'selected';
                }else if(isset($_POST['project'])){
                  echo 'selected';
                }
              ?>><?=$project['project_name']?></option>
            <?php } ?>
          </select>
        </label>
        <div class="space"></div>
        <label>
          <?php
          echo (isset($errors['title']))
            ? '<p class="text-error">Titel*</p>'
            : '<p>Titel</p>';
          ?>
          <input name="title" class="form_control" value="<?php
          echo (!empty($result['title']))
            ? $result['title']
            : '';
          ?>">
        </label>
        <div class="space-with-border"></div>
        <p>
          <label>
            <?php
            echo (isset($errors['problem']))
              ? '<p class="text-error">Aufgabenbeschrieb*</p>'
              : '<p>Aufgabenbeschrieb</p>';
            ?>
            <textarea name="problem" id="edit">
            <?php
              echo (!empty($result['problem'])) ? $result['problem'] : '';
            ?>
          </textarea>
          </label>
        </p>
        <div class="space-with-border"></div>
        <fieldset class="fieldset fieldset-radio">
          <?php
            echo (isset($errors['niveau']) && in_array($errors['niveau'], $errors))
              ? '<legend class="legend text-error">Wichtigkeit*</legend>'
              : '<legend class="legend">Wichtigkeit*</legend>';

            foreach($priorities as $priority) { ?>
              <label class="fieldset-container"><?= $priority['niveau'] ?>
                <input type="radio" name="niveau" value="<?= $priority['id'];?>"<?php
                if (isset($result['fk_priority']) && $priority['id'] === $result['fk_priority']) {
                  echo 'checked';
                }
                ?>/>
                <span class="checkmark"></span>
              </label>
            <br>
          <?php } ?>
        </fieldset>
        <div class="space-with-border"></div>
        <label>
          <?php
            echo (isset($errors['todo-type']) && in_array($errors['todo-type'], $errors))
              ? '<legend class="legend text-error">Todo-Zuteilung auswählen*</legend>'
              : '<legend class="legend">Todo-Zuteilung auswählen*</legend>';
          ?>
          <select name="todo-type">
            <option value="1" <?php if ($result['group_id'] === '1') {
              echo 'selected';
            } ?>>Eigentodo</option>
            <option value="<?= $groupID?>"<?php if ($result['group_id'] === $groupID) {
              echo 'selected';
            } ?>><?= $groupname?></option>
          </select>
        </label>
        <div class="space-with-border"></div>
        <p>
          <label>
            <a class="legend">Wann muss das Problem erledigt sein? (Optional)</a>
            <br>
            <input class="form_control" type="date" name="fixed_date" value="<?php
              echo (!empty($result['fixed_date_edit'])) ? $result['fixed_date_edit'] : '';
            ?>">
          </label>
        </p>
        <div class="space-with-border"></div>
        <p>
          <label>
            Webseite: (Optional)
            <br>
            <input placeholder="http(s)://www.example.com" type="text" name="url" class="form_control" value="<?php
              echo (!empty($result['website_url'])) ? $result['website_url'] : '';
            ?>">
          </label>
        </p>
        <div class="space">
          <input type="hidden" id="todo-id" value="<?= $getID?>"/>
          <input type="hidden" id="user-id" value="<?= $result['uid']?>"/>
          <input id="group-log-trigger" type="submit" name="submit" class="button-default" value="Todo speichern">
        </div>
        <p>*Pflichtfelder</p>
      </form>
    </div>
    <div class="col-6 space">
      <div class="aside">
        <h1 class="black-text">Some content</h1>
        <p class="black-text">Comming soon...</p>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  CKEDITOR.replace('edit', {
    customConfig: '/public/assets/js/ckeditor/config.js'
  });
</script>
<?php
mysqli_close($conn);
?>
