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
      if(isset($todos)){
        foreach($todos as $result){
          if($result['todo_status'] === '1' && $result['group_short'] === 'self-todo') {
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
          infoMessage("Es wurden keine Todos in Ihrer Datenbank gefunden.", 6);
        }
      }

      ?>
  </div>
</div>