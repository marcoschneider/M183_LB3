<?php
/**
 * Created by PhpStorm.
 * Ajax: School
 * Date: 29.06.2017
 * Time: 22:57
 */

$errors = [];
$success = [];

if(isset($getID) && $getID != ''){
  if (deleteTodo($conn, $uid, $getID) === true) {
    redirect('/done-overview');
  }
}else{
  infoMessage("Dieses Todo konnte nicht gelöscht werden.", 6);
}

?>