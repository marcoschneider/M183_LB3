<?php

  class GroupLogModel{

    private $userModel;
    private $todoModel;

    private $conn;

    public function __construct(UserModel $userModel, TodoModel $todoModel) {
      $this->userModel = $userModel;
      $this->todoModel = $todoModel;

      $this->conn = $this->userModel->conn;
    }

    public function insertLogAfterDelete($message, $todoID, $logState, $creatorID) {

      $lastGroupId = $this->todoModel->getLastGroupTodo($this->userModel->uid);

      if ($creatorID != $this->userModel->uid) {
        $sql = "
        INSERT INTO group_log
        (message, fk_group_log_state, fk_user, fk_todo, fk_user_creator)
        VALUES
        (
          '" . $message . "',
          " . $logState . ",
          " . $this->userModel->uid . ",
          " . $todoID . ",
          " . $creatorID . "
        )
      ";

        $result = $this->conn->query($sql);

        if ($result) {
          return true;
        }else{
          return $this->conn->error;
        }
      }else{
        $result = $this->todoModel->deleteGroupTodo($todoID);
        return $result;
      }
    }

    public function insertGroupLog($message, $todoID, $logState, $creatorID) {

      if ($creatorID != $this->userModel->uid) {
        $sql = "
          INSERT INTO group_log
            (message, fk_group_log_state, fk_user, fk_todo, fk_user_creator)
          VALUES
            (
              '" . $message . "',
              " . $logState . ",
              " . $this->userModel->uid . ",
              " . $todoID . ",
              " . $creatorID . "
            ) 
        ";

        $result = $this->conn->query($sql);

        if ($result) {
          return true;
        }else{
          return $result;
        }
      }
      return false;
    }

    public function getInfoGroupLog() {

      $output = [];

      $sql = "
        SELECT
          gl.id,
          uc.username as 'todoCreator',
          gl.message,
          u.username,
          t.id AS 'todoID',
          t.title
        FROM group_log gl
          INNER JOIN todo t on gl.fk_todo = t.id
          INNER JOIN user u on gl.fk_user = u.id
          INNER JOIN user uc on gl.fk_user_creator = uc.id
        WHERE
          gl.fk_user_creator = " . $this->userModel->uid . "
        AND
          gl.fk_group_log_state = 1
      ";

      $result = $this->conn->query($sql);

      if ($result){
        while ($row = $result->fetch_assoc()) {
          $output[] = $row;
        }
        return $output;
      }else{
        return $this->conn->error();
      }
    }

    public function getPendingGroupLogs() {
      $output = [];

      $sql = "
        SELECT
          gl.message,
          u.username,
          t.id AS 'todoID',
          t.title
        FROM group_log gl
          INNER JOIN todo t on gl.fk_todo = t.id
          INNER JOIN user u on gl.fk_user = u.id
          INNER JOIN user uc on gl.fk_user_creator = uc.id
        WHERE 
          gl.fk_user_creator = " . $this->userModel->uid . "
        AND
          gl.fk_group_log_state = 2
      ";

      $result = $this->conn->query($sql);

      if ($result) {
        while ($row = $result->fetch_assoc()) {
          $output[] = $row;
        }
        return $output;
      }else{
        return $this->conn->error();
      }
    }
  }

?>