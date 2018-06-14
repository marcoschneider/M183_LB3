<?php
$arrayResult = getTodos($conn, $uid);
$resultStatus = countTodoStatus($conn, $uid);
?>
<div class="container">
  <div class="row">
    <h1 class="page-title col-12">Erledigte Todos Übersicht</h1>
  </div>
  <div class="row">
    <div class="col-12">
      <?php
      if($arrayResult){
        foreach($arrayResult as $result){
          if($result['fk_todo_status'] === '1') {
            ?>
            <div class="todo-wrapper col-sm-12 col-md-6 col-lg-3">
              <a class="link" href="?pages=todo-details&id=<?= $result['id'] ?>">
                <h3 class="title-todo-wrapper"><?= $result['projectName'] ?></h3>
                <div class="date-todo-wrapper">
                  <?= trim($result['creation_date']) ?>
                </div>
                <div class="description">
                  <?= strip_tags($result['problem']) ?>
                </div>
                <div class="action-links-wrapper">
                  <a class="overview-action-links" href="?pages=update-todo&id=<?= $result['id'] ?>">
                    <i class="fas fa-reply" aria-hidden="true"></i>
                  </a>
                  <a class="overview-action-links" href="?pages=delete-todo&id=<?= $result['id'] ?>">
                    <i class="fas fa-trash"></i>
                  </a>
                </div>
                <div class="importance">
                  <p><?= $result['niveau'] ?></p>
                </div>
              </a>
              <div class="clearer"></div>
            </div>
            <?php
          }
        }
        if ($resultStatus['countedStatusDoneOverview'] === '0'){
          echo "<p class='aside col-6 black-text'>Du hast alle Todo's abgeschlossen. Happy Birthday</p>";
        }
      }else{
        echo "<p class='aside col-6 black-text'>Es wurden keine Todos in Ihrer Datenbank gefunden</p>";
      }
      ?>
    </div>
  </div>
</div>
