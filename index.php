<?php

  //error_reporting(E_ALL & ~E_NOTICE);

//starts Session
  session_start();

  //import db connection and import functions
  require('php/functions/dbcon.php');
  require('php/functions/functions.php');

  //Manages redirect to login page if not logged in
  if(!$_SESSION['loggedin']) {
    header("Location: pages/login.php");
    die();
  }

  // manages logout
  if(isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    redirect("pages/login.php");
  }

  $uid = $_SESSION['kernel']['userdata']["id"];
  $groupname = $_SESSION['kernel']['userdata']['groupname'];
  $username = $_SESSION['kernel']['userdata']['username'];

?>


<!DOCTYPE html>
<html>
  <head>
    <?php header('Content-type: text/html; charset=utf-8'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" />
    <link rel="stylesheet" href="bower_components/bootstrap/dist/css/bootstrap-grid.min.css" />
    <link rel="stylesheet" href="bower_components/normalize.css/normalize.css" />
    <link rel="stylesheet" href="assets/css/sco.styles.css"/>
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/favicon-16x16.png">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, maximum-scale=1.0"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html" lang="de"/>
    <script type="text/javascript" src="bower_components/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="node_modules/ckeditor/ckeditor.js"></script>
    <title>SOPD Support Web App</title>
  </head>
	<body>
    <header>
      <div class="container-fluid">
        <div class="row">
          <div class="col valign-wrapper">
            <a class="button-default" href="index.php?logout"><i class="fa fa-sign-out fa-2x"></i></a>
          </div>
          <div class="col brand-logo">
            <a href="?pages=support-links "><img class="logo" src="assets/img/logo.svg"/></a>
          </div>
          <div class="col valign-wrapper flex-end">
            <a class="button-default white-text right" href="?pages=userdata" ><i class="fa fa-user"></i><?= $username?></a>
          </div>
        </div>
        <div class="row">
          <nav class="menu">
            <?php
            $links = array(
              'Favorite Links' => '?pages=support-links',
              'Aufgabenübersicht' => '?pages=todo-overview',
              'Gruppenübersicht' => '?pages=group-overview',
              'Allgemeine Infos'  => '?pages=infos'
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
        <a class="tap-target" href="?pages=create-todo"><i class="tap-target-done-overview fa fa-plus-circle fa-5x" aria-hidden="true"></i></a>
        <span class="tooltiptext tooltip-create-todo">Todo erstellen</span>
      </div>
      <div class="tooltip valign-wrapper">
        <a class="tap-target" href="?pages=done-overview"><i class="tap-target-create-todo fa fa-check-circle fa-5x" aria-hidden="true"></i></a>
        <span class="tooltiptext tooltip-done-overview">Erledigte Todos</span>
      </div>
    </div>
    <div class="container-wrapper">
      <?php

        if (isset($_GET['pages'])){
          switch ($_GET['pages']){
            case 'support-links':
              include 'pages/supportLinks.php';
              break;
            case 'create-todo':
              include 'pages/createTodo.php';
              break;
            case 'delete-todo':
              include 'pages/deleteTodo.php';
              break;
            case 'todo-overview':
              include 'pages/todoOverview.php';
              break;
            case 'todo-details':
              include 'pages/todoDetails.php';
              break;
            case 'edit-todo':
              include 'pages/editTodo.php';
              break;
            case 'userdata':
              include 'pages/user.php';
              break;
            case 'group-overview':
              include 'pages/groupOverview.php';
              break;
            case 'group-info':
              include '';
              break;
            case 'done-todo':
              include 'pages/doneTodo.php';
              break;
            case 'done-overview':
              include 'pages/doneOverview.php';
              break;
            case 'update-todo':
              include 'pages/updateTodo.php';
              break;
            default:
              include 'pages/notFound.php';
              break;
          }

        }else{
          echo 'Die Seite kann nicht angezeigt werden.';
        }

      ?>
    </div>
    <footer class="footer col-12 valign-wrapper center">
      <div class="col-12">
        <p>Resize the browser window to see how the content respond to the resizing.</p>
        <br>
        <p>&copy Copyright Viaduct Web Support</p>
      </div>
    </footer>
    <script type="text/javascript" src="assets/js/script.js"></script>
    <script type="text/javascript" src="assets/js/menubar.js"></script>
	</body>
</html>