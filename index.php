<?php

require_once('model/LoginModel.php');
require_once('view/Messages.php');
require_once("view/AppView.php");
require_once('view/LayoutView.php');
require_once('view/DateTimeView.php');
require_once('controller/AppController.php');

$showErrors = false;

if ($showErrors) {
	error_reporting(-1);
	ini_set('display_errors', 'ON');
}

session_start();

$av = new \view\AppView();
$ac = new \controller\AppController($av);

$ac->handleInput();

// Choose view to generate.
$view = $ac->generateOutput();

$dtv = new \view\DateTimeView();
$lv = new \view\LayoutView();

// Render layout and login-form. 
$lv->render($ac->isLoggedIn(), $view, $dtv, $av);