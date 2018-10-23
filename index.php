<?php
require 'res/config.inc.php';
require_once LIBRARY_PATH . "/SessionManager.php";


error_reporting(E_ALL & ~E_NOTICE);
//import configs and import lib
require 'res/lib/router/Route.php';
require LIBRARY_PATH.'/functions.php';

//starts secure Session
session_start([
  'cookie_lifetime' => 86400,
]);

$conn = Config::getDb();
$urlPrefix = Config::getURLPrefix();

//Manages redirect to login page if not logged in.
if(!$_SESSION['loggedin']) {
  redirect("public/pages/login.php");
  die();
}

//SESSION configurations
$uid = $_SESSION['kernel']['userdata']["id"];
$groupname = $_SESSION['kernel']['userdata']['group_name'];
$groupID = $_SESSION['kernel']['userdata']['group_id'];
$username = $_SESSION['kernel']['userdata']['username'];
?>
<noscript>
  Bitte aktivieren Sie Javascript, ansonsten können Sie sich nicht anmelden.
</noscript>
<!DOCTYPE html>
<html>
  <head>
    <?php header('Content-type: text/html');
    Config::styles();
    ?>
    <link rel="icon" type="image/png" sizes="32x32" href="/public/assets/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/public/assets/img/favicon-16x16.png">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"/>
    <meta charset="utf-8" lang="de"/>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html" lang="de"/>
    <script type="text/javascript" src="<?= $urlPrefix?>/bower_components/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="<?= $urlPrefix?>/node_modules/ckeditor/ckeditor.js"></script>
    <title>Todo Web App</title>
  </head>
	<body>
    <header>
      <div class="container-fluid">
        <div class="row">
          <div class="col valign-wrapper">
            <a class="button-default" href="<?= $urlPrefix?>/logout"><i class="fas fa-sign-out-alt fa-2x"></i></a>
          </div>
          <div class="col brand-logo">
            <a href="?pages=support-links ">
              <img class="logo" src="<?= $urlPrefix?>/public/assets/img/logo.svg"/>
            </a>
          </div>
          <div class="col valign-wrapper flex-end">
            <a class="button-default white-text right" href="<?= $urlPrefix?>/user" ><i class="fas fa-user"></i><?= $username?></a>
          </div>
        </div>
        <div class="row">
          <nav class="menu">
            <?php
            $links = array(
              'Favorite Links' => $urlPrefix .'/support-links',
              'Aufgabenübersicht' => $urlPrefix .'/todo-overview',
              'Gruppenübersicht' => $urlPrefix .'/group-overview',
              'Gruppen Log'  => $urlPrefix .'/group-log'
            );

            $navigation =  createMenu($links);
            echo $navigation;
            ?>
            <div class="clearer"></div>
          </nav>
        </div>
      </div>
    </header>
    <div class="tooltip-wrapper">
      <div class="tooltip valign-wrapper">
        <a class="tap-target" href="<?= $urlPrefix?>/create-todo"><i class="tap-target-done-overview fas fa-plus-circle fa-5x" aria-hidden="true"></i></a>
      </div>
      <div class="tooltip valign-wrapper">
        <a class="tap-target" href="<?= $urlPrefix?>/done-overview"><i class="tap-target-create-todo fas fa-check-circle fa-5x" aria-hidden="true"></i></a>
      </div>
    </div>
    <div class="container-wrapper">
      <?php

      Route::add('/todo-overview',function() use ($conn, $uid){
        include('public/pages/todoOverview.php');
      },'get');

      Route::add('/done-overview',function() use ($conn, $uid){
        include('public/pages/doneOverview.php');
      },'get');

      Route::add('/todo-details/([0-9]*)',function($getID) use ($conn, $uid){
        include('public/pages/todoDetails.php');
      },'get');

      Route::add('/group-overview',function() use ($conn, $uid, $groupname){
        include('public/pages/groupOverview.php');
      },'get');

      Route::add('/group-log',function() {
        include('public/pages/groupLogs.php');
      },'get');

      Route::add('/support-links',function() use ($conn, $uid){
        include('public/pages/supportLinks.php');
      },'get');

      Route::add('/create-todo',function() use ($conn, $uid, $groupID, $groupname){
        include('public/pages/createTodo.php');
      },'get');

      Route::add('/edit-todo/([0-9]*)',function($getID) use ($conn, $uid, $groupID, $groupname){
        include('public/pages/editTodo.php');
      },'get');

      Route::add('/user',function() use ($conn, $uid){
        include('public/pages/user.php');
      },'get');

      Route::add('/done-todo/([0-9]*)',function($getID) use ($conn, $uid){
        include('public/pages/doneTodo.php');
      },'get');

      Route::add('/update-todo/([0-9]*)',function($getID) use ($conn, $uid){
        include('public/pages/updateTodo.php');
      },'get');

      Route::add('/delete-todo/([0-9]*)',function($getID) use ($conn, $uid){
        include('public/pages/deleteTodo.php');
      },'get');

      Route::add('/logout',function() {
        session_unset();
        session_destroy();
        redirect("public/pages/login.php");
      },'get');


      Route::add('/support-links',function() use ($conn, $uid){
        include('public/pages/supportLinks.php');
      },'post');

      Route::add('/support-links',function() use ($conn, $uid){
        include('public/pages/supportLinks.php');
      },'post');

      Route::add('/create-todo',function() use ($conn, $uid){
        include('public/pages/createTodo.php');
      },'post');

      Route::add('/edit-todo/([0-9]*)',function($getID) use ($conn, $uid, $groupID, $groupname){
        include('public/pages/editTodo.php');
      },'post');

      Route::add('/done-todo/([0-9]*)',function($getID) use ($conn, $uid){
        include('public/pages/doneTodo.php');
      },'post');

      Route::add('/update-todo/([0-9]*)',function($getID) use ($conn, $uid){
        include('public/pages/updateTodo.php');
      },'post');

      Route::add('/delete-todo/([0-9]*)',function($getID) use ($conn, $uid){
        include('public/pages/deleteTodo.php');
      },'post');

      Route::run('/M133_LB3');

      ?>
    </div>
    <footer class="footer col-12 valign-wrapper center">
      <div class="col-12">
        <p>Resize the browser window to see how the content respond to the resizing.</p>
        <br>
        <p>&copy Copyright Viaduct Web Support</p>
      </div>
    </footer>
    <?php
      Config::scripts();
    ?>
	</body>
</html>