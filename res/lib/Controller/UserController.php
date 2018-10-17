<?php
/**
 * Created by PhpStorm.
 * User: School
 * Date: 23.06.2018
 * Time: 18:43
 */

class UserController
{
  private $conn;
  private $uid;
  private $userModel;

  public function __construct(UserModel $userModel)
  {
    $this->userModel = $userModel;
    $this->uid = $this->userModel->uid;
    $this->conn = $this->userModel->conn;
  }

  /**
   * @author maschneider
   *
   * @param $username
   * @param $pass
   *
   * @return bool|string
   */
  public function authUser($username, $pass)
  {
    $this->userModel->setUsername(htmlspecialchars($username));

    $escUsername = null;
    $escPass = null;

    if ($username != '' && $pass != hash('sha256', "")) {
      $escUsername = mysqli_real_escape_string($this->conn, htmlspecialchars($username));
      //Todo: Hier wird mysqli_real_escape_string benutzt um die WebApp von sql injection zu schützen.
      $escPass = mysqli_real_escape_string($this->conn, htmlspecialchars($pass));
    } else {
      $error = "Bitte alle felder ausfüllen";
    }

    if (!isset($error)) {
      //Checks if username and password matches post
      $sql = "
      SELECT
       id 
      FROM user 
      WHERE 
        `username`='" . $escUsername . "' 
        AND `password`='" . $escPass . "'";
      $result = $this->conn->query($sql);
      if ($result->num_rows > 0) {
        $user = $this->userModel->getUserdata();
        $_SESSION['loggedin'] = true;
        $_SESSION['kernel']['userdata'] = $user;
        return true;
      } else {
        return "Benutzername oder Passwort falsch";
      }
    } else {
      return $error;
    }
  }

  /**
   * @author maschneider
   *
   * @param $firstname
   * @param $surname
   * @param $username
   * @param $pass
   * @param $fk_group
   *
   * @return array|bool
   */
  public function registerUser($firstname, $surname, $username, $pass, $fk_group) {

    $username = mysqli_real_escape_string($this->conn, $username);
    //Todo: Hier wird mysqli_real_escape_string benutzt um die WebApp von sql injection zu schützen.
    $pass = mysqli_real_escape_string($this->conn, $pass);

    $value = [];
    $errors = [];

    if(isset($firstname) && $firstname != ''){
      //Todo: Von PHP eingebaute htmlspecialchars Funktion schützt gegen Cross Site Scripting.
      $value['firstname'] = htmlspecialchars($firstname);
    }else{
      $errors[] = 'Vorname wurde nicht ausgefüllt';
    }

    if(isset($surname) && $surname != ''){
      $value['surname'] = htmlspecialchars($surname);
    }else{
      $errors[] = 'Nachname wurde nicht ausgefüllt';
    }

    if(isset($fk_group) && $fk_group != 0){
      $value['team'] = htmlspecialchars($fk_group);
    }else{
      $errors[] = 'Team wurde nicht ausgewählt';
    }

    if(isset($username) && $username != ''){
      $value['username-reg'] = htmlspecialchars($username);
    }else{
      $errors[] = 'Benutzername wurde nicht ausgefüllt';
    }

    if(isset($pass) && $pass != ''){
      $value['password-reg'] = htmlspecialchars($pass);
    }else{
      $errors[] = 'Passwort wurde nicht ausgefüllt';
    }

    if (count($errors) === 0) {

      $sql = "
      INSERT INTO `user` 
        (`firstname`, `surname`, `password`, `username`) 
      VALUES (
        '" . $value['firstname'] . "',
        '" . $value['surname'] . "',
        '" . $value['password-reg'] . "',
        '" . $value['username-reg'] . "'
      )";

      $this->conn->begin_transaction();
      $result = $this->conn->query($sql);
      $lastUserID = mysqli_insert_id($this->conn);

      $sqlGroup = "
      INSERT INTO user_group
      (fk_user, fk_group)
      VALUES (
        " . $lastUserID . ",
        " . $value['team'] . "
      ), (
        " . $lastUserID . ",
        1
      )";

      $this->conn->begin_transaction();
      $resultGroup = $this->conn->query($sqlGroup);

      if ($result && $resultGroup) {
        $this->conn->commit();
        return true;
      } else {
        $this->conn->rollback();
        return $result;
      }
    }else{
      return $errors;
    }
  }

}