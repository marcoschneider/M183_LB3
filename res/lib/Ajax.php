<?php
/**
 * Created by PhpStorm.
 * Ajax: maschneider
 * Date: 16.06.2018
 * Time: 11:24
 */

require_once './../config.inc.php';
require_once 'model/UserModel.php';
require_once 'controller/UserController.php';

session_start();
$conn = Config::getDb();
$username = '';
$uid = '';
if(isset($_SESSION['kernel']['userdata']['username'])){
  $username = $_SESSION['kernel']['userdata']['username'];
  $uid = $_SESSION['kernel']['userdata']['id'];
}

class Ajax
{

  private $conn;
  private $uid;
  private $username;
  private $userModel;
  private $userController;

  public function __construct(UserModel $userModel, UserController $userController)
  {
    $this->userModel = $userModel;
    $this->userController = $userController;

    $this->conn = $this->userModel->conn;
    $this->username = $this->userModel->getUsername();
    $this->uid = $this->userModel->uid;
  }

  public function getRequest()
  {
    header('Content-Type: application/json');
    if (isset($_REQUEST['jsonData'])) {
      $action = json_decode($_REQUEST['jsonData']);
      $trigger = $action->trigger;
      $this->handleRequest($trigger, $action);
    }
  }

  private function handleRequest($trigger, $action)
  {
    $result = '';
    switch ($trigger) {
      case 'updateUserdata':
        $result = $this->updateUserdata($action->firstname, $action->surname, $action->username);
        break;
      case 'getUserdata':
        $result = $this->userModel->getUserdata();
        break;
      case 'authUser':
        $result = $this->userController->authUser($action->username, $action->password);
        break;
      case 'registerUser':
        $result = $this->userController->registerUser($action->firstname, $action->surname, $action->username, $action->password, $action->fk_group);
        break;
    }
    $this->sendResponse($result);
  }

  private function sendResponse($result)
  {
    if (isset($result)) {
      echo json_encode($result);
    }
  }



  private function updateUserdata($firstname, $surname, $username)
  {

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
}

$userModel = new UserModel($conn, $uid);
$userController = new UserController($userModel);

$ajax = new Ajax($userModel, $userController);
$ajax->getRequest();

?>