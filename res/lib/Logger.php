<?php
/**
 * Created by PhpStorm.
 * User: maschneider
 * Date: 22.10.18
 * Time: 20:29
 */

class Logger {

  private $log = '';

  public function setMessage($message) {
    $this->log = $message;
  }

  public function save() {
    file_put_contents('logs/error_logs.txt', $this->log, FILE_APPEND);
  }
}