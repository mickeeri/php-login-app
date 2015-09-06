<?php

//INCLUDE THE FILES NEEDED...
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('controller/LoginController.php');
// require_once('model/LoginModel.php');

// MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// Create objects of controller
$c = new LoginController();

//CREATE OBJECTS OF THE VIEWS
$v = new LoginView($c);
$dtv = new DateTimeView();
$lv = new LayoutView();
// $isLoggedIn = LoginView::$isLoggedIn;
$loggedInCookie  = isset($_COOKIE["CookieStorage"]) ? $_COOKIE["CookieStorage"] : "";
$isLoggedIn;

var_dump($loggedInCookie);

if ($loggedInCookie == "logged-in") {
	$isLoggedIn = true;
} else {
	$isLoggedIn = false;
}



// var_dump(isset($v));
// 



// public function render($isLoggedIn, LoginView $v, DateTimeView $dtv)
$lv->render($isLoggedIn, $v, $dtv);