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

  public function __construct($conn, $uid)
  {
    $this->conn = $conn;
    $this->uid = $uid;
  }

  public function authUser(UserModel $user, $username, $pass)
  {

    $this->userModel = $user;
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
}