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

  public function authUser($username, $pass)
  {
    $this->userModel->setUsername($username);

    if ($username != '' && strlen($pass) == 64) {
      $escUsername = mysqli_real_escape_string($this->conn, $username);
      $escPass = mysqli_real_escape_string($this->conn, $pass);
    } else {
      $error = "Bitte alle felder ausfüllen";
    }

    if (!isset($error)) {
      //Checks if username and password matches post
      $sql = "SELECT id FROM user WHERE `username`='" . $escUsername . "' AND `password`='" . $escPass . "'";
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

  public function registerUser($firstname, $surname, $username, $pass, $fk_group) {

    $value = [];
    $errors = [];

    if(isset($firstname) && $firstname != ''){
      $value['name'] = htmlspecialchars($firstname);
    }else{
      $errors['name'] = 'Name wurde nicht ausgefüllt' . '<br>';
    }

    if(isset($surname) && $surname != ''){
      $value['surname'] = htmlspecialchars($surname);
    }else{
      $errors['surname'] = 'Nachname wurde nicht ausgefüllt' . '<br>';
    }

    if(isset($fk_group) && $fk_group != ""){
      $value['team'] = htmlspecialchars($fk_group);
    }else{
      $errors['team'] = 'Team wurde nicht ausgewählt' . '<br>';
    }

    if(isset($username) && $username != ''){
      $value['username-reg'] = htmlspecialchars($username);
    }else{
      $errors['username-reg'] = 'Benutzername wurde nicht ausgefüllt' . '<br>';
    }

    if(isset($pass) && $pass != ''){
      $value['password-reg'] = htmlspecialchars($pass);
    }else{
      $errors['password-reg'] = 'Passwort wurde nicht ausgefüllt' . '<br>';
    }

    $firstname = $value['name'];
    $surname = $value['surname'];
    $fk_group = $value['team'];
    $username = $value['username-reg'];
    $pass = $value['password-reg'];



    $sql = "
    INSERT INTO `user` 
      (`firstname`, `surname`, `password`, `username`) 
    VALUES (
      '" . $firstname . "',
      '" . $surname . "',
      '" . $pass . "',
      '" . $username . "'
    )";

    $result = $this->conn->query($sql);
    $lastUserID = mysqli_insert_id($this->conn);

    $sqlGroup = "
      INSERT INTO user_group
      (fk_user, fk_group)
      VALUES (
        " . $lastUserID . ",
        " . $fk_group . "
      )  
      ";

    $resultGroup = $this->conn->query($sqlGroup);

    if (count($errors) === 0) {

      if ($result && $resultGroup) {
        return true;
      } else {
        return $result;
      }
    }
    return $errors;
  }

}