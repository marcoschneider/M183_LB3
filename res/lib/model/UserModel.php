<?php
/**
 * Created by PhpStorm.
 * User: maschneider
 * Date: 15.06.2018
 * Time: 23:36
 */

class UserModel
{

  private $conn;
  private $uid;

  public function __construct($conn, $uid) {
    $this->conn = $conn;
    $this->uid = $uid;
  }

  public static function updateUserdata($userdata) {

  }

  public function getUserdata() {
    $userData = "SELECT `name`, surname, username FROM benutzer WHERE id = '" . $this->uid . "'";

    $result = $this->conn->query($userData);

    if ($this->conn->error === '') {
      return $result->fetch_assoc();
    }else{
      return $this->conn->error;
    }
  }

}