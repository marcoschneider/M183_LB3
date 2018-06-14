<?php

$arrayResult = getGroupTodos($conn, $groupname);
$resultStatus = countTodoStatus($conn, $uid);
?>
<div class="container">
  <div class="row">
    <h1 class="page-title col-8">Todo Gruppenübersicht</h1>
    <p class="team-text col-4 align-right">Dein Team: <span><?= $groupname?></span></p>
  </div>
  <div class="row">
    <?php
    if($arrayResult){
      foreach($arrayResult as $result) {
        if($result['fk_todo_status'] === '2') {
          ?>
          <div class="todo-wrapper col-sm-12 col-md-6 col-lg-3">
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
      if ($resultStatus['countedStatusTaskOverview'] === '0'){
        infoMessage("Du hast keine Todos offen", 6);
      }
    }else{
      echo "<p class='aside col-6 black-text'>Es wurden keine Todos in Ihrer Datenbank gefunden</p>";
    }

    ?>
  </div>
</div>