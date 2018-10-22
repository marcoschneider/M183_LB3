<?php
/**
 * Created by PhpStorm.
 * User: School
 * Date: 23.06.2018
 * Time: 18:43
 */

class UserController
{
  private $conn;
  private $uid;
  private $userModel;
  private $tfa;

  public function __construct(UserModel $userModel, \RobThree\Auth\TwoFactorAuth $tfa)
  {
    $this->userModel = $userModel;
    $this->uid = $this->userModel->uid;
    $this->conn = $this->userModel->conn;
    $this->tfa = $tfa;
  }

  /**
   * @author maschneider
   *
   * @param $username
   * @param $pass
   *
   * @return bool|string
   */
  public function authUser($username, $pass, $code)
  {
    $this->userModel->setUsername(htmlspecialchars($username));

    $escUsername = null;
    $escPass = null;
    $escCode = null;

    if ($username != '' && $pass != hash('sha256', "") && $code != '') {
      $escUsername = mysqli_real_escape_string($this->conn, htmlspecialchars($username));
      //Todo: Hier wird mysqli_real_escape_string benutzt um die WebApp vor sql injection zu schützen.
      $escPass = mysqli_real_escape_string($this->conn, htmlspecialchars($pass));
      $escCode = mysqli_real_escape_string($this->conn, htmlspecialchars($code));
    } else {
      $error = "Bitte alle felder ausfüllen";
    }
    if (!isset($error)) {
      if ($this->userModel->isSecretKeySet()) {
        $result = $this->userModel->getSecretKey();
        $secret = $result->fetch_assoc()['secret'];
        if ($this->tfa->verifyCode($secret, $escCode)) {
          //Checks if username and password matches post
          $sql = "
            SELECT
             id 
            FROM user 
            WHERE 
              `username`='" . $escUsername . "' 
              AND `password`='" . $escPass . "'";
          $result = $this->conn->query($sql);
          if ($result->num_rows > 0) {
            $user = $this->userModel->getUserdata();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['kernel']['userdata'] = $user;
            return TRUE;
          }
          else {
            return "Benutzername oder Passwort falsch";
          }
        }else{
          return 'Die Authenfizierung ist fehlgeschlagen';
        }
      }else{
        $this->userModel->updateSecretKey($_SESSION['2fa-secret']);
      }
    }else {
      return $error;
    }
    return 'Something went wrong';
  }

  /**
   * @author maschneider
   *
   * @param $firstname
   * @param $surname
   * @param $username
   * @param $pass
   * @param $fk_group
   *
   * @return array|bool
   */
  public function registerUser($firstname, $surname, $username, $pass, $fk_group) {

    $username = mysqli_real_escape_string($this->conn, $username);
    //Todo: Hier wird mysqli_real_escape_string benutzt um die WebApp von sql injection zu schützen.
    $pass = mysqli_real_escape_string($this->conn, $pass);

    $value = [];
    $errors = [];

    if(isset($firstname) && $firstname != ''){
      //Todo: Von PHP eingebaute htmlspecialchars Funktion schützt gegen Cross Site Scripting.
      $value['firstname'] = htmlspecialchars($firstname);
    }else{
      $errors[] = 'Vorname wurde nicht ausgefüllt';
    }

    if(isset($surname) && $surname != ''){
      $value['surname'] = htmlspecialchars($surname);
    }else{
      $errors[] = 'Nachname wurde nicht ausgefüllt';
    }

    if(isset($fk_group) && $fk_group != 0){
      $value['team'] = htmlspecialchars($fk_group);
    }else{
      $errors[] = 'Team wurde nicht ausgewählt';
    }

    if(isset($username) && $username != ''){
      $value['username-reg'] = htmlspecialchars($username);
    }else{
      $errors[] = 'Benutzername wurde nicht ausgefüllt';
    }

    if(isset($pass) && $pass != ''){
      $value['password-reg'] = htmlspecialchars($pass);
    }else{
      $errors[] = 'Passwort wurde nicht ausgefüllt';
    }

    if (count($errors) === 0) {

      $sql = "
      INSERT INTO `user` 
        (`firstname`, `surname`, `password`, `username`) 
      VALUES (
        '" . $value['firstname'] . "',
        '" . $value['surname'] . "',
        '" . $value['password-reg'] . "',
        '" . $value['username-reg'] . "'
      )";

      $this->conn->begin_transaction();
      $result = $this->conn->query($sql);
      $lastUserID = mysqli_insert_id($this->conn);

      $sqlGroup = "
      INSERT INTO user_group
      (fk_user, fk_group)
      VALUES (
        " . $lastUserID . ",
        " . $value['team'] . "
      ), (
        " . $lastUserID . ",
        1
      )";

      $this->conn->begin_transaction();
      $resultGroup = $this->conn->query($sqlGroup);

      if ($result && $resultGroup) {
        $this->conn->commit();
        return true;
      } else {
        $this->conn->rollback();
        return $result;
      }
    }else{
      return $errors;
    }
  }

}