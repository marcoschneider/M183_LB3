<?php

$getId = $_GET['id'];

if (isset($getId) && $getId != '') {
  $result = getTodoDetails($conn, $getId);
}

?>

<div class="row container">
  <div class="col-6">
    <div class="tododetails-wrapper">
    <?php
    if(isset($result)) {  ?>
      <h1 class="detail-title"><?= $result['title'] ?></h1>
      <h2 class="detail-project"><?= $result['projectName'] ?></h2>
      <div class="detail-output-wrapper">
      <?php
        echo ($result['datum'] != '')
          ? '<p class="detail-fixed-date">Fixes Datum: </p><span> ' . $result['datum'] . '</span>'
          : '<p>Kein fixes Datum vorhanden.</p>';
      ?>
      </div>
      <div class="todoproblem-wrapper">
        <?= $result['problem'] ?>
      </div>
      <div class="detail-output-wrapper">
        <p class="detail-fixed-date">Letzte Erstellung oder Bearbeitung: </p>
        <p><?= $result['creation_date'] ?></p>
      </div>
      <div class="detail-output-wrapper">
        <p class="detail-fixed-date">Priotirät: </p>
        <p><?= $result['niveau'] ?></p>
      </div>
      <div class="detail-output-wrapper">
        <?php
          echo ($result['url'] != '')
            ? '<p class="detail-fixed-date">URL: </p><a href="' . $result['url'] . '">' . $result['url'] . '</a>'
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
