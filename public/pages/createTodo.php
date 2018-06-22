<?php
  $projects = getAllProjects($conn);
?>

  <div class="row container">
    <?php
      if (isset($_POST['submit'])){
        $POST = $_POST;
        $values = checkForm($POST, $conn);
        $errors = $values['errors'];

        if (count($errors) === 0){
          $addTodo = addTodo($conn, $values, $uid);
          if($addTodo === true){
            redirect('?pages=todo-overview');
          }else{
            $errors['message'] = "<b>Fehlermedlung: </b>" . $addTodo;
          }
        }
      }
    ?>
    <div class="col-7 space">
      <h2>Aufgabe erstellen</h2>
      <div class="space"></div>
      <form class="form" method="POST" action="">
        <label>
          <?php
          echo (isset($errors['project']))
            ? '<p class="text-error">Projektname*</p>'
            : '<p>Projektname*</p>'
          ?>
          <div id="project" class="dropdown-trigger">
            <p>
              <?php
                foreach ($projects as $project){
                  if (isset($_POST['project']) && $_POST['project'] === $project['id']) {
                    echo $project['project_name'];
                  }else{
                    echo '--Bitte wählen--';
                    break;
                  }
                }
              ?>
            </p>
            <ul data-name="project" class="dropdown-list">
              <?php
                foreach ($projects as $project) {
                  echo '<li data-list-value="'.$project['id'].'">'.$project['project_name'].'</li>';
                }
              ?>
            </ul>
          </div>
        </label>
        <div class="space"></div>
        <label>
          <?php
            echo (isset($errors['title']))
              ? '<p class="text-error">Titel*</p>'
              : '<p>Titel</p>';
          ?>
          <input name="title" class="form_control" value="<?php
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
            ?>
          </textarea>
        </label>
        <div class="space-with-border"></div>
        <fieldset class="fieldset">
          <?php
            echo (isset($errors['niveau']) && in_array($errors['niveau'], $errors))
              ? '<legend class="legend text-error">Wichtigkeit*</legend>'
              : '<legend class="legend">Wichtigkeit*</legend>';
          ?>
          <label>
            <input type="radio" name="niveau" value="1" <?php
            echo (!empty($_POST['niveau']) && $_POST['niveau'] == '1')
              ? 'checked'
              : '';
            ?>>
            High
          </label>
          <br>
          <label>
            <input type="radio" name="niveau" value="2" <?php
            echo (!empty($_POST['niveau']) && $_POST['niveau'] == '2')
              ? 'checked'
              : '';
            ?>>
            Normal
          </label>
          <br>
          <label>
            <input type="radio" name="niveau" value="3" <?php
            echo (!empty($_POST['niveau']) && $_POST['niveau'] == '3')
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
              ? '<legend class="legend text-error">Todo-Zuteilung auswählen*</legend>'
              : '<legend class="legend">Todo-Zuteilung auswählen*</legend>';
          ?>
          <div id="todo-type-create" class="dropdown-trigger">
            <p>
            <?php
              if(isset($_REQUEST['todo-type']) && $_REQUEST['todo-type'] === "1"){
                echo 'Eigentodo';
              }elseif (isset($_REQUEST['todo-type']) && $_REQUEST['todo-type'] != "1"){
                echo $_REQUEST['group_name'];
              }else{
                echo '--Bitte wählen--';
              }
              ?>
            </p>
            <ul data-name="todo-type" class="dropdown-list">
              <li data-list-value="1">Eigentodo</li>
              <li data-list-value="<?= $groupID ?>"><?= $groupname ?></li>
            </ul>
          </div>
        </label>
        <div class="space-with-border"></div>
        <label>
          Bis wann muss die Aufbabe erledigt sein? (Optional)
          <input class="form_control" type="date" name="fixed_date" value="<?php
            echo (!empty($_POST['date'])) ? $_POST['date'] : '';
          ?>">
        </label>
        <div class="space-with-border"></div>
        <label>
          Webseite: (Optional)
          <br>
          <input placeholder="http(s)://www.example.com" name="url" class="form_control" value="<?php
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