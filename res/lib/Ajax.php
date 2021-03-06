<?php
/**
 * Created by PhpStorm.
 * Ajax: maschneider
 * Date: 16.06.2018
 * Time: 11:24
 */
require_once 'SessionManager.php';

session_start();

require_once './../config.inc.php';
require_once 'model/UserModel.php';
require_once 'model/GroupLogModel.php';
require_once 'model/TodoModel.php';
require_once 'controller/UserController.php';

$conn = Config::getDb();
$username = null;
$uid = null;
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
  private $groupLogModel;
  private $todoModel;

  public function __construct(UserModel $userModel, UserController $userController,
                              GroupLogModel $groupLogModel,
                              TodoModel $todoModel) {
    $this->userModel = $userModel;
    $this->userController = $userController;
    $this->groupLogModel = $groupLogModel;
    $this->todoModel = $todoModel;

    $this->conn = $this->userModel->conn;
    $this->username = $this->userModel->getUsername();
    $this->uid = $this->userModel->uid;
  }

  /**
   * @author maschneider
   */
  public function getRequest()
  {
    header('Content-Type: application/json');
    if (isset($_REQUEST['jsonData'])) {
      $action = json_decode($_REQUEST['jsonData']);
      $trigger = $action->trigger;
      $this->handleRequest($trigger, $action);
    }
  }

  /**
   * @param $trigger
   * @param $action
   *
   * @author maschneider
   */
  private function handleRequest($trigger, $action) {
    $result = '';
    switch ($trigger) {
      case 'updateUserdata':
        $result = $this->userModel->updateUserdata($action->firstname, $action->surname, $action->username);
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
      case 'insertLogAfterEdit':
        $result = $this->groupLogModel->insertGroupLog($action->message, $action->todoID, 1, $action->uid);
        break;
      case 'insertLogAfterDelete':
        $result = $this->groupLogModel->insertLogAfterDelete($action->message, $action->todoID, 2, $action->uid);
        break;
      case 'getInfoGroupLog':
        $result = $this->groupLogModel->getInfoGroupLog();
        break;
      case 'deleteGroupTodo':
        $result = $this->todoModel->deleteGroupTodo($action->idOfTodo);
        break;
      case 'declineDeleteTodo':
        $result = $this->todoModel->declineDeleteGroupTodo($action->idOfTodo);
        break;
      case 'getPendingGroupLogs':
        $result = $this->groupLogModel->getPendingGroupLog();
        break;
      case 'checkPassword':
        $result = $this->userModel->updatePassword($action->currentPassword, $action->newPassword, $action->repeatPassword);
        break;
    }
    $this->sendResponse($result);
  }

  /**
   * @param $result
   *
   * @author maschneider
   */
  private function sendResponse($result) {
    if (isset($result)) {
      echo json_encode($result);
    }
  }

}

$userModel = new UserModel($conn, $uid);
$userController = new UserController($userModel);
$todoModel = new TodoModel($userModel);
$groupLogModel = new GroupLogModel($userModel, $todoModel);

$ajax = new Ajax($userModel, $userController, $groupLogModel, $todoModel);
$ajax->getRequest();

?>