<?php
/**
 * Created by PhpStorm.
 * User: maschneider
 * Date: 22.10.18
 * Time: 20:29
 */

class Logger {

  private $log;

  public function setMessage($message) {
    $this->log = $message;
  }

  public function save() {
    $message = date('H:i', time()) . ' ' . $this->log.PHP_EOL;

    $filename = dirname(__DIR__)."/logs/error_log.txt";
    file_put_contents($filename, $message, FILE_APPEND);
  }
}