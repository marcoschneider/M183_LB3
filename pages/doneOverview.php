<?php
$arrayResult = getTodos($conn, $uid);
$resultStatus = countTodoStatus($conn, $uid);
?>
<div class="row container">
  <div class="col-12">
    <h1 class="page-title">Erledigte Todos Übersicht</h1>
    <?php
    if($arrayResult){
      foreach($arrayResult as $result){
        if(strlen($result['problem']) >= 200 ) {
          $pos = strpos($result['problem'], ' ', 125);
          $shortText = substr($result['problem'], 0, $pos);
        }else{
          $shortText = $result['problem'];
        }
        $date = date_create($result['creation_date']);
        $date = date_format($date, "d.m.Y \\u\\m H:i");
        if($result['fk_todo_status'] === '1') {
          ?>
          <div class="todo-wrapper">
            <a class="link" href="?pages=todo-details&id=<?= $result['id'] ?>">
              <h3 class="title-todo-wrapper"><?= $result['projectName'] ?></h3>
              <div class="date-todo-wrapper">
                <?= trim($date) ?>
              </div>
              <div class="description">
                <?= strip_tags($shortText) ?>
              </div>
              <div class="action-links-wrapper">
                <a class="overview-action-links" href="?pages=update-todo&id=<?= $result['id'] ?>">
                  <i class="fa fa-reply" aria-hidden="true"></i>
                </a>
                <a class="overview-action-links" href="?pages=delete-todo&id=<?= $result['id'] ?>">
                  <i class="fa fa-trash-o fa-lg"></i>
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
