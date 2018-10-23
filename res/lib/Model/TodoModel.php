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

  public function createTodo($values) {
    var_dump($values);

    return $values;
  }

  /**
   * @author maschneider
   *
   * @param $todoID
   *
   * @return string
   */
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

  /**
   * @author maschneider
   *
   * @param $todoID
   *
   * @return bool
   */
  public function declineDeleteGroupTodo($todoID) {
    $sql = "
      DELETE FROM group_log
      WHERE fk_todo = " . $todoID . "
    ";

    $result = $this->conn->query($sql);

    if ($result) {
      return true;
    }else{
      return $this->conn->error;
    }
  }

  /**
   * @author maschneider
   *
   * @param $uid
   *
   * @return array|bool
   */
  public function getLastGroupTodo($uid) {

    $output = [];

    $sql = "
      SELECT
       id 
      FROM todo 
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