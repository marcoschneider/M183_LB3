<?php

$arrayResult = getGroupTodos($conn, $groupename);
$resultStatus = countTodoStatus($conn, $uid);
?>
<div class="row container">
  <div class="col-12">
    <p class="team-text">Dein Team: <span class="ff5252"><?= $groupename?></span></p>
    <h1 class="page-title">Todo Gruppenübersicht</h1>
    <?php
    if($arrayResult){
      foreach($arrayResult as $result) {
        if($result['fk_todo_status'] === '2') {
          ?>
          <div class="todo-wrapper">
            <a class="link" href="?pages=todo-details&id=<?= $result['id'] ?>">
              <h3 class="title-todo-wrapper"><?= $result['title'] ?></h3>
              <div class="date-todo-wrapper">
                <?= trim($result['creation_date']) ?>
              </div>
              <div class="description">
                <?= strip_tags($result['problem']) ?>
              </div>
              <div class="action-links-wrapper">
                <a class="overview-action-links" href="?pages=edit-todo&id=<?= $result['id'] ?>">
                  <i class="fa fa-pencil-square-o fa-lg"></i>
                </a>
              </div>
              <div class="importance">
                <p class="group-informations"><?= $result['niveau'] ?></p>
                <p class="group-informations">Erstellt von: <span><?= $result['username'] ?></span></p>
              </div>
            </a>
            <div class="clearer"></div>
          </div>
          <?php
        }
      }
      /*if ($resultStatus['countedStatusTaskOverview'] === '0'){
        echo "<p class='aside col-6 black-text'>Du hast keine Todos offen. Alle Todos sind erledigt.</p>";
      }*/
    }else{
      echo "<p class='aside col-6 black-text'>Es wurden keine Todos in Ihrer Datenbank gefunden</p>";
    }

    ?>
  </div>
</div>