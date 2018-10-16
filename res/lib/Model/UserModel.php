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
    if (isset($_SESSION['kernel']['userdata']['username'])) {
      $this->username = $_SESSION['kernel']['userdata']['username'];
    }
  }

  /**
   * @author maschneider
   *
   * @return mixed
   */
  public function getUserdata() {
    $this->conn->begin_transaction();
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
    $this->conn->commit();

    if ($result) {
      return $result->fetch_assoc();
    } else {
      return $this->conn->error;
    }
  }

  /**
   * @author maschneider
   *
   * @param $firstname
   * @param $surname
   * @param $username
   *
   * @return array|bool
   */
  public function updateUserdata($firstname, $surname, $username) {
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

    $this->conn->begin_transaction();
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
        $_SESSION['kernel']['userdata']['firstname'] = $firstname;
        $_SESSION['kernel']['userdata']['surname'] = $surname;
        $_SESSION['kernel']['userdata']['username'] = $username;
        $this->conn->commit();
        return true;
      } else {
        $this->conn->rollback();
        return $result;
      }
    } else {
      return $errors;
    }
  }

  private function checkForChangePassword($currentPassword, $newPassword, $repeatNewPassword) {

    $value = [];
    $errors = [];

    if(isset($currentPassword) && $currentPassword != hash("sha256", '')){
      $value['currentPassword'] = htmlspecialchars($currentPassword);
    }else{
      $errors[] = 'Bitte das Feld aktuelles Passwort ausfüllen';
    }

    if(isset($newPassword) && $newPassword != hash("sha256", '')){
      $value['newPassword'] = htmlspecialchars($newPassword);
    }else{
      $errors[] = 'Bitte das Feld neues Passwort ausfüllen';
    }

    if(isset($repeatNewPassword) && $repeatNewPassword != hash("sha256", '')){
      if ($newPassword === $repeatNewPassword) {
        $value['repeatNewPassword'] = htmlspecialchars($repeatNewPassword);
      }else{
        $errors[] = 'Passwörter stimmen nicht überein';
      }
    }else{
      $errors[] = 'Bitte das Feld neues Passwort wiederholen ausfüllen';
    }

    if (count($errors) === 0) {
      $sql = "
        SELECT
          username
        FROM user
        WHERE `password` = '" . $currentPassword . "' AND id = '" . $this->uid . "'
      ";

      $result = $this->conn->query($sql);

      if ($result->num_rows > 0) {
        return true;
      }else{
        return false;
      }

    }else{
      return $errors;
    }
  }

  public function updatePassword($currentPassword, $newPassword, $repeatPassword) {
    if ($this->checkForChangePassword($currentPassword, $newPassword, $repeatPassword)) {
      $sql = "
        UPDATE `user`
        SET `password` = '" . $repeatPassword . "'
        WHERE `password` = '" . $currentPassword . "' AND id = " . $this->uid . "
      ";

      $result = $this->conn->query($sql);

      if ($result) {
        return true;
      }
    }else{
      $error = 'Das aktuelle Passwort stimmt nicht mit deiner Eingabe überein';
      return $error;
    }
    return false;
  }

  /**
   * @author maschneider
   *
   * @return mixed
   */
  public function getUsername() {
    $userdata = $this->getUserdata();
    return $userdata['username'];
  }

  /**
   * @author maschneider
   *
   * @return mixed
   */
  public function getFirstname() {
    $userdata = $this->getUserdata();
    return $userdata['firstname'];
  }

  /**
   * @author maschneider
   *
   * @return mixed
   */
  public function getSurname() {
    $userdata = $this->getUserdata();
    return $userdata['surname'];
  }

  /**
   * @author maschneider
   *
   * @return mixed
   */
  public function setUsername($username) {
    $this->username = $username;
  }
}