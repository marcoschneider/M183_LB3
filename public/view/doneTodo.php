<?php
/**
 * Created by PhpStorm.
 * Ajax: School
 * Date: 29.06.2017
 * Time: 22:57
 */

$errors = [];
$success = [];

if (isset($getID) && $getID != '') {
  $doneTodoResponse = doneTodo($conn, 0, $uid, $getID);
  if ($doneTodoResponse === true) {
    redirect(Config::getURLPrefix().'/todo-overview');
  }else {
    errorMessage($doneTodoResponse);
  }
}else {
  infoMessage("Dieses Todo konnte nicht in die History gespeichert werden.", 6);
}

?>