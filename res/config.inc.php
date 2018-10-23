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
    $settings['database'] = [];
    require "settings.local.php";
    $database = $settings['database'];

    $mysqli = new mysqli($database['host'], $database['user'], $database['password'], $database['db']);

    if($mysqli->connect_errno){
      return $mysqli->connect_error;
    }

    $mysqli->set_charset("utf8");

    return $mysqli;
  }

  public static function getHostname() {
    $settings = [];
    require "settings.local.php";

    return $settings['domain'];
  }

  public static function styles() {
    $sheets = [
      "/bower_components/fontawesome/web-fonts-with-css/css/fontawesome-all.min.css",
      "/bower_components/bootstrap/dist/css/bootstrap-grid.min.css",
      "/node_modules/toastr/build/toastr.min.css",
      "/bower_components/normalize.css/normalize.css",
      "/public/assets/css/sco.styles.css",
    ];

    foreach($sheets as $src) {
      echo '<link rel="stylesheet" href="' . Config::getHostname() . $src . '"/>';
    }
  }

  public static function scripts() {
    $scripts = [
      "/bower_components/js-sha256/build/sha256.min.js",
      "/node_modules/toastr/build/toastr.min.js",
      "/public/assets/js/script.js",
      "/public/assets/js/response-handler.js",
      "/public/assets/js/menubar.js",
    ];

    foreach($scripts as $src) {
      echo '<script type="text/javascript" src="' . Config::getHostname() . $src . '"></script>';
    }
  }
}
