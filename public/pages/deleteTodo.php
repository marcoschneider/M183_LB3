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

if(isset($getId) && $getId != ''){
  if (deleteTodo($conn, $uid, $getId) === true) {
    redirect('?pages=done-overview');
  }
}else{
  infoMessage("Dieses Todo konnte nicht gelöscht werden.", 6);
}

?>