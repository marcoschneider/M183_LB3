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

  public function deleteGroupTodo() {

  }

}