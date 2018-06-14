<?php
/**
 * Created by PhpStorm.
 * User: School
 * Date: 12.06.2018
 * Time: 10:09
 */

define("RESOURCE_PATH", realpath(basename(__DIR__)));
define("LIBRARY_PATH", realpath(basename(__DIR__)) . '/lib');

class Config{
  public static function getPage($pageName){

  }

  public static function getDb() {
    $host = "localhost";
    $user = "root";
    $pass = "toor";
    $db = "m133_todo_app";

    $mysqli = new mysqli($host, $user, $pass, $db);

    if($mysqli->connect_errno){
      return $mysqli->connect_error;
    }

    $mysqli->set_charset("utf8");

    return $mysqli;
  }

  public static function styles() {
    $styles = [
      "path" => ""
    ];
  }
}