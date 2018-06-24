<?php
/**
 * Created by PhpStorm.
 * Ajax: maschneider
 * Date: 15.06.2018
 * Time: 23:36
 */

class UserModel
{

  public $conn;
  public $uid;
  public $username;

  function __construct($conn, $uid) {
    $this->conn = $conn;
    $this->uid = $uid;
    $this->username = $_SESSION['kernel']['userdata']['username'];
  }

  public function getUserdata() {
    $sql = "
    SELECT 
      u.id,
      firstname,
      surname,
      username, 
      g.id AS 'group_id',
      g.group_name,
      g.group_short
    FROM `user` u
      INNER JOIN user_group ug ON ug.fk_user =  u.id
      INNER JOIN `group` g on ug.fk_group = g.id
    WHERE 
      u.username = '" . $this->username . "'
    AND g.group_short != 'self-todo'";

    $result = $this->conn->query($sql);

    if ($result) {
      return $result->fetch_assoc();
    } else {
      return $this->conn->error;
    }
  }

  private function updateUserdata($firstname, $surname, $username) {
    $errors = [];

    if (isset($firstname) && $firstname != '') {
      $firstname = htmlspecialchars($firstname);
    } else {
      $errors[] = "Ein Name muss angegeben werden.";
    }

    if (isset($surname) && $surname != '') {
      $surname = htmlspecialchars($surname);
    } else {
      $errors[] = "Ein Nachname muss angegeben werden.";
    }

    if (isset($username) && $username != '') {
      $username = htmlspecialchars($username);
    } else {
      $errors[] = "Ein Benutzername muss angegeben werden.";
    }

    $sql = "UPDATE user 
    SET 
      firstname = '" . $firstname . "',
      surname = '" . $surname . "',
      username = '" . $username . "'
    WHERE id = '" . $this->uid . "'
    ";

    if (count($errors) === 0) {
      $result = $this->conn->query($sql);
      if ($result) {
        return true;
      } else {
        return $result;
      }
    } else {
      return $errors;
    }
  }

  public function getUsername() {
    $userdata = $this->getUserdata();
    return $userdata['username'];
  }

  public function getFirstname() {
    $userdata = $this->getUserdata();
    return $userdata['firstname'];
  }

  public function getSurname() {
    $userdata = $this->getUserdata();
    return $userdata['surname'];
  }

  public function setUsername($username) {
    $this->username = $username;
  }
}