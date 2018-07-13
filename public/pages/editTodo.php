<?php
/**
 * Created by PhpStorm.
 * Ajax: MarcoPolo
 * Date: 30.06.2017
 * Time: 21:15
 */
error_reporting(E_ALL);

$result = [];
$getId = $_GET['id'];

//Gets todo Details
$projects = getAllProjects($conn);
$todo = getTodoDetails($conn, $getId);
$priorities = getAllPriorities($conn);

//Loop through returned Array
if (isset($_GET['id']) && $_GET['id'] != '') {
  $result = $todo;
}

?>

<div class="container">
  <div class="row">
    <div class="col-6 space">
      <h2>Aufgabe bearbeiten</h2>
      <div class="space"></div>
      <form class="form" method="POST" action="">
        <?php
        //Validate form and insert results into db
        if(isset($_POST['submit'])){
          $POST = $_POST;
          $values = checkForm($POST, $conn);
          $errors = $values['errors'];

          if (count($errors) === 0){
            $updateTodo = saveEdit($conn, $values, $getId, $uid);
            if($updateTodo === true){
              redirect('?pages=todo-overview');
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
        <p>
          <label>
            <?php
            echo (isset($errors['title']))
              ? '<p class="text-error">Projektname*</p>'
              : '<p>Projektname*</p>';
            ?>
            <select name="project">
              <option value="--Bitte wählen--">--Bitte wählen--</option>
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
        </p>
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
        <fieldset class="fieldset">
          <?php
            echo (isset($errors['niveau']) && in_array($errors['niveau'], $errors))
              ? '<legend class="legend text-error">Wichtigkeit*</legend>'
              : '<legend class="legend">Wichtigkeit*</legend>';

            foreach($priorities as $priority) { ?>
              <label>
                <input type="radio" name="niveau" value="<?= $priority['id'];?>"<?php
                if (isset($result['fk_priority']) && $priority['id'] === $result['fk_priority']) {
                  echo 'checked';
                }
                ?>/>
              </label>
            <?= $priority['niveau'] ?>
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
          <div id="todo-type-edit" class="dropdown-trigger">
            <p>
              <?php
              if(isset($result['group_name']) && $result['group_id'] === "1"){
                echo 'Eigentodo';
              }elseif ($result['group_id'] != "1"){
                echo $result['group_name'];
              }else{
                echo '--Bitte wählen--';
              }

              echo (isset($result['group_id']))
                ? '<input type="hidden" name="todo-type" value="'.$result['group_id'].'" />'
                : '';
              ?>
            </p>
            <ul data-name="todo-type" class="dropdown-list">
              <li data-list-value="1">Eigentodo</li>
              <li data-list-value="<?= $groupID ?>"><?= $groupname ?></li>
            </ul>
          </div>
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
          <input type="hidden" id="todo-id" value="<?= $_GET['id']?>"/>
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
