<?php
$request_uri = $_SERVER['REQUEST_URI'];
$todos = getTodos($conn, $uid, $request_uri);
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
              <a class="overview-action-links" href="<?=Config::getURLPrefix()?>/done-todo/<?= $result['id'] ?>">
                <i class="fas fa-check" aria-hidden="true"></i>
              </a>
              <a class="overview-action-links" href="<?=Config::getURLPrefix()?>/edit-todo/<?= $result['id'] ?>">
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
      if (is_object($todos)) {
        if ($todos->num_rows === 0){
          infoMessage("Sie haben keine Todo's welche erledigt werden müssen", 6);
        }
      }
    }
    ?>
  </div>
</div>