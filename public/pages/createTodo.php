<?php
  $projects = getAllProjects($conn);
  $priorities = getAllPriorities($conn);
?>

  <div class="row container">
    <div class="col-7 space">
      <h2>Aufgabe erstellen</h2>
      <div class="space"></div>
      <?php
        if (isset($_POST['submit'])){
          $POST = $_POST;

          $values = validateTodoForm($POST, $conn);
          $errors = $values['errors'];

          if (count($errors) === 0){
            $addTodo = addTodo($conn, $values, $uid);
            if($addTodo === true){
              //redirect('todo-overview');
            }else{
              $errors['message'] = "<b>Fehlermedlung: </b>" . $addTodo;
            }
          }
        }
      ?>
      <form class="form" method="POST" action="<?= $urlPrefix?>/create-todo">
        <label>
          <?php
          echo (isset($errors['title']))
            ? '<p class="text-error">Projektname*</p>'
            : '<p>Projektname*</p>';
          ?>
          <select name="project">
            <?php foreach($projects as $key => $project){?>
              <option value="<?=$project['id']?>"<?php
              if (isset($_POST['project']) && $project['id'] === $_POST['project']){
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
              : '<p>Titel*</p>';
          ?>
          <input id="todo-title" name="title" class="form_control" value="<?php
          echo (!empty($_POST['title']))
            ? $_POST['title']
            : '';
          ?>">
        </label>
        <div class="space-with-border"></div>
        <label>
          <?php
          echo (isset($errors['todo']))
            ? '<p class="text-error">Aufgabenbeschrieb*</p>'
            : '<p>Aufgabenbeschrieb*</p>'
          ?>
          <textarea name="problem" id="edit">
            <?php
              echo (!empty($_POST['problem'])) ? $_POST['problem'] : '';
            $_POST['problem']
            ?>
          </textarea>
        </label>
        <div class="space-with-border"></div>
        <fieldset class="fieldset fieldset-radio">
          <?php
          echo (isset($errors['niveau']) && in_array($errors['niveau'], $errors))
            ? '<legend class="legend text-error">Wichtigkeit*</legend>'
            : '<legend class="legend">Wichtigkeit*</legend>';

          foreach($priorities as $priority) { ?>
            <label class="fieldset-container"><?= $priority['niveau'] ?>
              <input type="radio" name="niveau" value="<?= $priority['id'] ?>"<?php
              if (isset($_POST['niveau']) && $priority['id'] === $_POST['niveau']) {
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
            <option value="1"<?php
              if (isset($_POST['todo-type']) && $_POST['todo-type'] === '1'){
                echo 'selected';
              }
            ?>>Eigentodo</option>
            <option value="<?= $groupID?>"<?php
            if (isset($_POST['todo-type']) && $_POST['todo-type'] === $groupID){
              echo 'selected';
            }
            ?>><?= $groupname?></option>
          </select>
        </label>
        <div class="space-with-border"></div>
        <label>
          Bis wann muss die Aufbabe erledigt sein? (Optional)
          <input id="todo-fixed-date" class="form_control" type="date" name="fixed_date" value="<?php
            echo (!empty($_POST['date'])) ? $_POST['date'] : '';
          ?>">
        </label>
        <div class="space-with-border"></div>
        <label>
          Webseite: (Optional)
          <br>
          <input id="todo-url" placeholder="http(s)://www.example.com" name="url" class="form_control" value="<?php
            echo (!empty($_POST['url'])) ? $_POST['url'] : '';
          ?>">
        </label>
        <div class="space">
          <input type="submit" name="submit" class="button-default" value="Todo erstellen">
        </div>
        <p>*Pflichtfelder</p>
      </form>
    </div>
    <?php
    if(isset($_POST['submit'])){
      if(count($errors) != 0){
        echo '<div class="col-5 col-s12">';
        errorMessage($errors);
        echo '</div>';
      }
    }
    ?>
  </div>
  <div class="clearer"></div>
  <script type="text/javascript">
    CKEDITOR.replace('edit', {
      customConfig: '/public/assets/js/ckeditor/config.js'
    });
  </script>
<?php
  mysqli_close($conn);
?>