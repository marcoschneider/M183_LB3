<?php
$request_uri = $_SERVER['REQUEST_URI'];
$todos = getTodos($conn, $uid, $request_uri);
$resultStatus = countTodoStatus($conn, $uid);
?>
<div class="container">
  <div class="row">
    <h1 class="page-title col-12">Erledigte Todos Übersicht</h1>
  </div>
  <div class="row">
    <?php
    if(isset($todos)){
      foreach($todos as $result){
        ?>
        <div class="todo-wrapper col-sm-12 col-md-6 col-lg-3">
          <a class="link" href="<?=Config::getURLPrefix()?>/todo-details/<?= $result['id'] ?>">
            <h3 class="title-todo-wrapper"><?= $result['title'] ?></h3>
            <div class="date-todo-wrapper">
              <?= trim($result['creation_date']) ?>
            </div>
            <div class="description">
              <?= strip_tags(htmlspecialchars_decode($result['problem'])) ?>
            </div>
            <div class="action-links-wrapper">
              <a class="overview-action-links" href="<?=Config::getURLPrefix()?>/update-todo/<?= $result['id'] ?>">
                <i class="fas fa-reply" aria-hidden="true"></i>
              </a>
              <a class="overview-action-links" href="<?=Config::getURLPrefix()?>/delete-todo/<?= $result['id'] ?>">
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
      if (is_object($todos)) {
        if ($todos->num_rows === 0){
          infoMessage("Du hast alle Todo's abgeschlossen", 6);
        }
      }
    }else{
      infoMessage("Es wurden keine Todos in Ihrer Datenbank gefunden", 6);
    }
    ?>
  </div>
</div>
