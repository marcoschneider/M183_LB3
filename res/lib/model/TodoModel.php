<?php
/**
 * Created by PhpStorm.
 * User: maschneider
 * Date: 01.07.18
 * Time: 21:16
 */

class TodoModel {

  private $userModel;
  private $conn;

  public function __construct(UserModel $userModel) {
    $this->userModel = $userModel;
    $this->conn = $userModel->conn;
  }

  public function deleteGroupTodo($todoID) {
    $sql = "
      DELETE FROM todo
      WHERE id = " . $todoID . "
    ";

    $result = $this->conn->query($sql);

    if ($result) {
      return 'deletedTodo';
    }else{
      return $this->conn->error;
    }
  }

  public function getLastGroupTodo($uid) {

    $output = [];

    $sql = "
      SELECT
       id 
      FROM m133_todo_app_beta.todo 
      WHERE fk_user = " . $uid . "
        ORDER BY id 
      DESC LIMIT 1;
    ";

    $result = $this->conn->query($sql);

    if ($result) {
      while ($row = $result->fetch_assoc()) {
        $output[] = $row;
      }
      return $output;
    }else{
      return false;
    }
  }

}