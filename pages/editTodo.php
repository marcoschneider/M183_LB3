<?php
/**
 * Created by PhpStorm.
 * User: MarcoPolo
 * Date: 30.06.2017
 * Time: 21:15
 */
error_reporting(E_ALL);

$result = [];
$getId = $_GET['id'];


//Gets todo Details
$projects = getAllProjects($conn);
$todo = getTodoDetails($conn, $getId);
//Loop through returned Array
if (isset($_GET['id']) && $_GET['id'] != '') {
  $result = $todo;
}


//Set correct time format

$result['datum'] = (int)$result['datum'];
$result['datum'] = date("Y-m-d", $result['datum']);

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
            $updateTodo = updateTodo($conn, $values, $getId, $uid);
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
              ? '<p class="textErrors">Projektname*</p>'
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
                ?>><?=$project['projectName']?></option>
              <?php } ?>
            </select>
          </label>
        </p>
        <div class="space-with-border"></div>
        <p>
          <label>
            <?php
            echo (isset($errors['problem']))
              ? '<p class="textErrors">Aufgabenbeschrieb*</p>'
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
            ? '<legend class="legend textErrors">Wichtigkeit*</legend>'
            : '<legend class="legend">Wichtigkeit*</legend>';
          ?>
          <label>
            <input type="radio" name="niveau" value="1" <?php
            echo (!empty($result['fk_priority']) && $result['fk_priority'] == '1')
              ? 'checked'
              : '';
            ?>>
            High
          </label>
          <br>
          <label>
            <input type="radio" name="niveau" value="2" <?php
            echo (!empty($result['fk_priority']) && $result['fk_priority'] == '2')
              ? 'checked'
              : '';
            ?>>
            Normal
          </label>
          <br>
          <label>
            <input type="radio" name="niveau" value="3" <?php
            echo (!empty($result['fk_priority']) && $result['fk_priority'] == '3')
              ? 'checked'
              : '';
            ?>>
            Low
          </label>
        </fieldset>
        <div class="space-with-border"></div>
        <label>
          <?php
          echo (isset($errors['todo-type']) && in_array($errors['todo-type'], $errors))
            ? '<legend class="legend textErrors">Todo-Zuteilung auswählen*</legend>'
            : '<legend class="legend">Todo-Zuteilung auswählen*</legend>';
          ?>
          <div class="dropdown-trigger">
            <p>
              <?php
              if(isset($result['groupename']) && $result['groupename'] === "self-todo"){
                echo 'In Selbsttodo eintragen';
              }elseif(isset($result['groupename']) && $result['groupename'] === strtolower($_SESSION['groupename'])){
                echo 'In Gruppentodo eintragen';
              }else{
                echo '--Bitte wählen--';
              }

              echo (isset($result['groupename']))
                ? '<input type="hidden" name="todo-type" value="'.$result['groupename'].'" />'
                : '';
              ?>
            </p>
            <ul data-name="todo-type" class="dropdown-list">
              <li data-list-value="self-todo">In Selbsttodo eintragen</li>
              <li data-list-value="<?= strtolower($_SESSION['groupename']) ?>">In Gruppentodo eintragen</li>
            </ul>
          </div>
        </label>
        <div class="space-with-border"></div>
        <p>
          <label>
            <legend class="legend">Wann muss das Problem erledigt sein? (Optional)</legend>
            <br>
            <input class="form_control" type="date" name="date" value="<?php
              echo (!empty($_POST['datum'])) ? $_POST['datum'] : '';
            ?>">
          </label>
        </p>
        <div class="space-with-border"></div>
        <p>
          <label>
            Webseite: (Optional)
            <br>
            <input placeholder="http(s)://www.example.com" type="text" name="url" class="form_control" value="<?php
              echo (!empty($_POST['url'])) ? $_POST['url'] : '';
            ?>">
          </label>
        </p>
        <div class="space">
          <input type="submit" name="submit" class="button-default" value="Todo speichern">
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
    customConfig: '/p/php/sopd-web-support/assets/js/ckeditor/config.js'
  });
</script>
<?php
mysqli_close($conn);
?>
