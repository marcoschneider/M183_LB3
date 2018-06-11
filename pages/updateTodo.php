<?php
/**
 * Created by PhpStorm.
 * User: School
 * Date: 29.06.2017
 * Time: 22:57
 */

$getId = (int)$_GET['id'];

$errors = [];
$success = [];

if(isset($_GET['pages']) && $_GET['pages'] === 'done-todo') {
  redirect('?pages=done-overview');
}

if (isset($getId) && $getId != '') {
  $updateResponse = updateTodoStatus($conn, $uid, $getId);
  if ($updateResponse === true) {
    redirect('?pages=done-overview');
  }
} else {
  infoMessage("Dieses Todo konnte nicht in die History gespeichert werden.", 6);
}

?>