<?php
/**
 * Created by PhpStorm.
 * Ajax: School
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
  $doneTodoResponse = doneTodo($conn, 2, $uid, $getId);
  if ($doneTodoResponse === true) {
    redirect('?pages=todo-overview');
  }else {
    errorMessage($doneTodoResponse);
  }
}else {
  infoMessage("Dieses Todo konnte nicht in die History gespeichert werden.", 6);
}

?>