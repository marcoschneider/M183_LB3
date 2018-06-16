<?php
/**
 * Created by PhpStorm.
 * User: maschneider
 * Date: 16.06.2018
 * Time: 11:24
 */

class Ajax{

  private $conn;
  private $uid;

  public function __construct($conn,$uid)
  {
    $this->conn = $conn;
    $this->uid = $uid;
  }

  public function getRequest(){
    header('Content-Type: application/json');
    if (isset($_REQUEST['jsonData'])) {
      $action = json_decode($_REQUEST['jsonData']);
      $trigger = $action->trigger;
      $this->handleRequest($trigger);
    }
  }

  private function handleRequest($trigger) {
    switch ($trigger){
      case 'getusername':
        $result=$this->getUsername();
        break;
    }
    $this->sendResponse($result);
  }

  private function sendResponse($result) {
    if (isset($result)) {
      echo json_encode($result);
    }
  }

  private function getUsername(){
    $sql = "SELECT username FROM benutzer WHERE id = '".$this->uid."'";
    $query = mysqli_query($this->conn, $sql);

    $result = $query->fetch_assoc();

    return $result;
  }

}

session_start();

require "../config.inc.php";

$conn = Config::getDb();
$uid = $_SESSION['kernel']['userdata']['id'];

$ajax = new Ajax($conn, $uid);
$ajax->getRequest();

?>