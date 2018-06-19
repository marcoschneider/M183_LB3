<?php
/**
 * Created by PhpStorm.
 * User: maschneider
 * Date: 16.06.2018
 * Time: 11:24
 */

class User{

  private $conn;
  private $uid;

  public function __construct($conn,$uid)
  {
    $this->conn = $conn;
    $this->uid = $uid;
  }

  public function getRequest(){
    header('Content-Type: application/json');
    var_dump($_REQUEST);
    if (isset($_REQUEST['jsonData'])) {
      $action = json_decode($_REQUEST['jsonData']);
      $trigger = $action->trigger;
      $this->handleRequest($trigger, $action);
    }
  }

  private function handleRequest($trigger, $action) {
    switch ($trigger){
      case 'change-userdata':
        $result=$this->updateUserdata($action['firstname'], $action['surname'], $action['username']);
        break;
      case 'getUserdata':
        $result=$this->getUserdata();
        break;
    }
    $this->sendResponse($result);
  }

  private function sendResponse($result) {
    if (isset($result)) {
      echo json_encode($result);
    }
  }

  public function getUserdata() {
    $userData = "SELECT `name`, surname, username FROM user WHERE id = '" . $this->uid . "'";

    $result = $this->conn->query($userData);

    if ($this->conn->error === '') {
      return $result->fetch_assoc();
    }else{
      return $this->conn->error;
    }
  }

  private function updateUserdata($firstname, $surname, $username) {
    $sql = "UPDATE user 
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

?>