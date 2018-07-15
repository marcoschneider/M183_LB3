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
  $updateResponse = doneTodo($conn, 1, $uid, $getID);
  if ($updateResponse === true) {
    redirect('/done-overview');
  }
} else {
  infoMessage("Dieses Todo konnte nicht in die History gespeichert werden.", 6);
}

?>