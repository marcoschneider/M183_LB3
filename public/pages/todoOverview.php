<?php

$todos = getTodos($conn, $uid);
$resultStatus = countTodoStatus($conn, $uid);

?>
<div class="container">
  <div class="row">
    <h1 class="page-title col-12">Todo Übersicht</h1>
  </div>
  <div class="row">
      <?php
      if(is_array($todos)){
        foreach($todos as $result){
          if($result['fk_todo_status'] === '2' && $result['groupname'] === 'self-todo') {
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
                  <a class="overview-action-links" href="?pages=done-todo&id=<?= $result['id'] ?>">
                    <i class="fas fa-check" aria-hidden="true"></i>
                  </a>
                  <a class="overview-action-links" href="?pages=edit-todo&id=<?= $result['id'] ?>">
                    <i class="fas fa-edit"></i>
                  </a>
                </div>
                <div class="importance">
                  <p><?= $result['niveau'] ?></p>
                </div>
              </a>
            </div>
            <?php
          }
        }
        if ($resultStatus['countedStatusTaskOverview'] === '0'){
          infoMessage("Du hast keine Todos offen. Alle Todos sind erledigt.", "6");
        }
      }else{
        infoMessage("Es wurden keine Todo's in Ihrer Datenbank gefunden", "6");
      }

      ?>
  </div>
</div>