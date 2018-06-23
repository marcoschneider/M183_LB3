<?php
/**
 * Created by PhpStorm.
 * Ajax: maschneider
 * Date: 16.06.2018
 * Time: 11:24
 */

require_once './../config.inc.php';
require_once 'model/UserModel.php';

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
  private $user;

  public function __construct(UserModel $userModel)
  {
    $this->user = $userModel;
    $this->conn = $this->user->conn;
    $this->username = $this->user->getUsername();
    $this->uid = $this->user->uid;
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
        $result = $this->user->getUserdata();
        break;
      case 'authUser':
        $result = $this->authUser($action->username, $action->password);
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

  private function authUser($username, $pass)
  {

    if ($username != '' && $pass != '') {
      $username = mysqli_real_escape_string($this->conn, $username);
      $pass = mysqli_real_escape_string($this->conn, $pass);
      $this->username = $username;
    } else {
      $error = "Bitte alle felder ausfüllen";
    }

    //Checks if username and password matches post
    $sql = "SELECT id FROM user WHERE `username`='" . $username . "' AND `password`='" . $pass . "'";

    if (!isset($error)) {
      $result = $this->conn->query($sql);

      if ($result) {
        $user = $this->getUserdata();
        $_SESSION['loggedin'] = true;
        $_SESSION['kernel']['userdata'] = $user;
        return true;
      } else {
        return $result;
      }
    } else {
      return $error;
    }
  }

  protected function getUserdata()
  {
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
      username = '" . $this->username . "'
    AND g.group_short != 'self-todo'";

    $result = $this->conn->query($sql);

    if ($this->conn->error === '') {
      return $result->fetch_assoc();
    } else {
      return $this->conn->error;
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

$ajax = new Ajax(new UserModel($conn, $uid));
$ajax->getRequest();

?>