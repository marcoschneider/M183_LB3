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

  function __construct($conn, $uid) {
    $this->conn = $conn;
    $this->uid = $uid;
  }

  public function getUserdata() {
    $userData = "SELECT firstname, surname, username FROM user WHERE id = '" . $this->uid . "'";

    $result = $this->conn->query($userData);

    if ($this->conn->error === '') {
      return $result->fetch_assoc();
    }else{
      return $this->conn->error;
    }
  }

  private function updateUserdata($firstname, $surname, $username) {
    $sql = "
    UPDATE user 
    SET 
      `name` = '". $firstname ."',
      surname = '".$surname."',
      username = '".$username."'
    WHERE id = '".$this->uid."'
    ";

    $result = $this->conn->query($sql);

    if ($result) {
      return true;
    }else{
      return $result;
    }
  }

}