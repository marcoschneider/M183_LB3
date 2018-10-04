<?php

if (isset($getID) && $getID != '') {
  $result = getTodoDetails($conn, $getID);
}

?>

<div class="row container">
  <div class="col-6">
    <div class="tododetails-wrapper">
    <?php
    if(isset($result)) {  ?>
      <div class="space">
        <div class="row">
          <div class="col-6">
            <h1 class="detail-title"><?= $result['title'] ?></h1>
          </div>
          <div class="col-6">
            <h4>Projekt: </h4>
            <span><?= $result['project_name'] ?></span>
          </div>
        </div>
      </div>
      <div class="detail-output-wrapper">
      <?php
        echo ($result['fixed_date'] != 0)
          ? '<p class="detail-fixed-date">Fixes Datum: </p><span> ' . $result['fixed_date'] . '</span>'
          : '<p>Kein fixes Datum vorhanden.</p>';
      ?>
      </div>
      <div class="todoproblem-wrapper">
        <?= $result['problem'] ?>
      </div>
      <div class="detail-output-wrapper">
        <p class="detail-fixed-date">Erstellt am: </p>
        <p><?= $result['creation_date'] ?></p>
      </div>
      <div class="detail-output-wrapper">
        <?php
          echo $result['last_edit'] != 0
            ? '<p class="detail-fixed-date">Zuletzt bearbeitet: </p><span>' . $result['last_edit'] . '</span>'
            : '';
        ?>
      </div>
      <div class="detail-output-wrapper">
        <p class="detail-fixed-date">Priotirät: </p>
        <p><?= $result['niveau'] ?></p>
      </div>
      <div class="detail-output-wrapper">
        <?php
          echo ($result['website_url'] != '')
            ? '<p class="detail-fixed-date">URL: </p><a href="' . $result['website_url'] . '">' . $result['website_url'] . '</a>'
            : '<p class="detail-fixed-date">URL: </p><p>Keine externe URL erfasst.</p>'
        ?>
      </div>
      <?php
    }else{
    ?>
      <div class="failbox">
        <p>Die Daten können nicht angezeigt werden. Versuchen Sie es später erneut.
        <?php var_dump($result) ?>
        </p>
      </div>
    <?php
    }
    ?>
    </div>
  </div>
</div>
