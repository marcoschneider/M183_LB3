<?php
/**
 * Created by PhpStorm.
 * Ajax: School
 * Date: 12.06.2018
 * Time: 10:09
 */

define("RESOURCE_PATH", realpath(basename(__DIR__)));
define("LIBRARY_PATH", realpath(basename(__DIR__)) . '/lib');

class Config{

  public static function getDb() {
    $host = "localhost";
    $user = "root";
    $pass = "toor";
    $db = "m133_todo_app_beta";

    $mysqli = new mysqli($host, $user, $pass, $db);

    if($mysqli->connect_errno){
      return $mysqli->connect_error;
    }

    $mysqli->set_charset("utf8");

    return $mysqli;
  }

  public static function styles() {
    $scripts = [
      "public/assets/js/script.js",
      "public/assets/js/response-handler.js",
      "public/assets/js/cargame.js",
      "public/assets/js/menubar.js",
      "bower_components/toastr/toastr.js"
    ];

    foreach($scripts as $script) {
      echo '<script type="text/javascript" src="' . $script . '"></script>';
    }
  }

  public static function scripts() {
    $scripts = [
      "public/assets/js/script.js",
      "public/assets/js/response-handler.js",
      "public/assets/js/cargame.js",
      "public/assets/js/menubar.js",
      "bower_components/toastr/toastr.js"
    ];

    foreach($scripts as $script) {
      echo '<script type="text/javascript" src="' . $script . '"></script>';
    }
  }
}