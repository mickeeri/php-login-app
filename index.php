<?php

//INCLUDE THE FILES NEEDED...
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('controller/LoginController.php');
require_once('view/CookieStorage.php');
// require_once('model/LoginModel.php');

// MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Create objects of controller
$c = new LoginController();

//CREATE OBJECTS OF THE VIEWS
// $v = new LoginView($c);
$dtv = new DateTimeView();
$lv = new LayoutView();

// Renders page
$c->renderPage();